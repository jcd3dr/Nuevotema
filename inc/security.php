<?php
/**
 * Basic security enhancements
 */

// Custom login slug and simple login attempt limiting.
// Skip these hooks if Wordfence or similar security plugin defines WORDFENCE_VERSION.

if ( ! defined( 'WORDFENCE_VERSION' ) ) {
    /**
     * Serve wp-login.php from a custom slug.
     */
    function dadecore_login_rewrite() {
        $options      = get_option( 'dadecore_options', array() );
        $slug         = isset( $options['login_slug'] ) ? sanitize_title( $options['login_slug'] ) : 'login';
        $request_path = trim( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ), '/' );

        if ( $request_path === $slug ) {
            require_once ABSPATH . 'wp-login.php';
            exit;
        }

        if ( ! is_user_logged_in() && ( $request_path === 'wp-login.php' || strpos( $request_path, 'wp-admin' ) === 0 ) ) {
            global $wp_query;
            $wp_query->set_404();
            status_header( 404 );
            nocache_headers();
            include get_query_template( '404' );
            exit;
        }
    }
    add_action( 'init', 'dadecore_login_rewrite' );

    /**
     * Filter login URLs to use the custom slug.
     */
    function dadecore_filter_login_url( $login_url, $redirect, $force_reauth ) {
        $options   = get_option( 'dadecore_options', array() );
        $slug      = isset( $options['login_slug'] ) ? sanitize_title( $options['login_slug'] ) : 'login';
        $login_url = home_url( '/' . $slug . '/' );
        if ( $redirect ) {
            $login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
        }
        if ( $force_reauth ) {
            $login_url = add_query_arg( 'reauth', '1', $login_url );
        }
        return $login_url;
    }
    add_filter( 'login_url', 'dadecore_filter_login_url', 10, 3 );

    /**
     * Filter site_url calls for login posts to use the custom slug.
     *
     * @param string $url         The complete site URL including scheme and path.
     * @param string $path        Path relative to the site URL.
     * @param string $orig_scheme Scheme to give the site URL context.
     * @return string Filtered URL.
     */
    function dadecore_filter_site_login_url( $url, $path, $orig_scheme ) {
        if ( false !== strpos( $path, 'wp-login.php' ) && 'login_post' === $orig_scheme ) {
            $options = get_option( 'dadecore_options', array() );
            $slug    = isset( $options['login_slug'] ) ? sanitize_title( $options['login_slug'] ) : 'login';
            $url     = home_url( '/' . $slug . '/' );
        }

        return $url;
    }
    add_filter( 'site_url', 'dadecore_filter_site_login_url', 10, 3 );

    /**
     * Limit failed login attempts per IP address.
     */
    function dadecore_limit_login_attempts( $user, $username, $password ) {
        if ( $user instanceof WP_User ) {
            $key = 'dadecore_login_attempts_' . md5( $_SERVER['REMOTE_ADDR'] );
            delete_transient( $key );
            return $user;
        }

        $options  = get_option( 'dadecore_options', array() );
        $limit    = isset( $options['login_attempts'] ) ? absint( $options['login_attempts'] ) : 3;
        $duration = isset( $options['lockout_minutes'] ) ? absint( $options['lockout_minutes'] ) : 15;

        $key      = 'dadecore_login_attempts_' . md5( $_SERVER['REMOTE_ADDR'] );
        $attempts = (int) get_transient( $key );

        if ( $attempts >= $limit ) {
            return new WP_Error( 'too_many_attempts', __( '<strong>Error</strong>: Too many login attempts. Please try again later.' ) );
        }

        set_transient( $key, $attempts + 1, $duration * MINUTE_IN_SECONDS );
        return $user;
    }
    add_filter( 'authenticate', 'dadecore_limit_login_attempts', 30, 3 );
}

/**
 * Send security headers if they are not already present.
 */
function dadecore_send_security_headers() {
    if ( headers_sent() ) {
        return;
    }

    $existing = array();
    foreach ( headers_list() as $header ) {
        list( $name ) = explode( ':', $header, 2 );
        $existing[] = trim( $name );
    }

    if ( ! in_array( 'X-Frame-Options', $existing, true ) ) {
        header( 'X-Frame-Options: SAMEORIGIN' );
    }
    if ( ! in_array( 'X-Content-Type-Options', $existing, true ) ) {
        header( 'X-Content-Type-Options: nosniff' );
    }
    if ( ! in_array( 'Referrer-Policy', $existing, true ) ) {
        header( 'Referrer-Policy: same-origin' );
    }
}
add_action( 'send_headers', 'dadecore_send_security_headers' );
