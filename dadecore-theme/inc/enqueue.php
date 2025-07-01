<?php
/**
 * Enqueue scripts and styles
 */

function dadecore_scripts() {
    wp_enqueue_style( 'dadecore-style', get_stylesheet_uri(), array(), '1.0' );
    wp_enqueue_style( 'dadecore-custom-styles', get_template_directory_uri() . '/assets/css/custom-styles.css', array('dadecore-style'), filemtime(get_template_directory() . '/assets/css/custom-styles.css') );
    wp_enqueue_script( 'dadecore-script', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), '1.0', true );

    // Cookie Consent Banner Assets
    if ( get_theme_mod( 'dadecore_cookie_banner_enabled', false ) ) {
        wp_enqueue_style( 'dadecore-cookie-banner-style', get_template_directory_uri() . '/assets/css/cookie-banner.css', array('dadecore-style'), filemtime(get_template_directory() . '/assets/css/cookie-banner.css') );
        wp_enqueue_script( 'dadecore-cookie-consent-script', get_template_directory_uri() . '/assets/js/cookie-consent.js', array(), filemtime(get_template_directory() . '/assets/js/cookie-consent.js'), true );

        // Localize script with settings
        $cookie_categories_settings = array();
        $defined_categories = array( // Should match those in customizer.php
            'necessary' => array( 'label' => get_theme_mod('dadecore_cookie_cat_label_necessary', __( 'Necessary Cookies', 'dadecore' )), 'defaultEnabled' => true ), // Default label, actual description from customizer
            'analytics' => array( 'label' => get_theme_mod('dadecore_cookie_cat_label_analytics', __( 'Analytics Cookies', 'dadecore' )), 'defaultEnabled' => get_theme_mod('dadecore_cookie_cat_enabled_analytics', false) ),
            'marketing' => array( 'label' => get_theme_mod('dadecore_cookie_cat_label_marketing', __( 'Marketing Cookies', 'dadecore' )), 'defaultEnabled' => get_theme_mod('dadecore_cookie_cat_enabled_marketing', false) ),
        );

        foreach ( $defined_categories as $slug => $cat_defaults ) {
            // Only add category if it's 'necessary' or enabled in customizer
            if ( $slug === 'necessary' || get_theme_mod( "dadecore_cookie_cat_enabled_{$slug}", $cat_defaults['defaultEnabled'] ) ) {
                 $cookie_categories_settings[$slug] = array(
                    'label' => get_theme_mod( "dadecore_cookie_cat_label_{$slug}", $cat_defaults['label'] ), // This customizer field doesn't exist yet, using default. Actual label for display is in customizer desc.
                    'description' => get_theme_mod( "dadecore_cookie_cat_desc_{$slug}", '' ), // Fetch from customizer
                    'defaultEnabled' => ($slug === 'necessary') ? true : get_theme_mod( "dadecore_cookie_cat_enabled_{$slug}", $cat_defaults['defaultEnabled'] ),
                );
            }
        }

        // Get PHP default consent states to pass to JS (for fallback if banner is off but GTM is on)
        $php_default_consents = array(
            'ad_storage' => get_theme_mod( 'dadecore_default_consent_ad_storage', 'denied' ),
            'analytics_storage' => get_theme_mod( 'dadecore_default_consent_analytics_storage', 'denied' ),
            // Add other GCM types if you have corresponding theme options
        );

        wp_localize_script( 'dadecore-cookie-consent-script', 'dadecoreCookieConsent', array(
            'bannerEnabled'         => get_theme_mod( 'dadecore_cookie_banner_enabled', false ),
            'bannerTitle'           => get_theme_mod( 'dadecore_cookie_banner_title', __( 'Cookie Consent', 'dadecore' ) ),
            'bannerText'            => wp_kses_post( get_theme_mod( 'dadecore_cookie_banner_text', '' ) ),
            'acceptText'            => get_theme_mod( 'dadecore_cookie_banner_accept_text', __( 'Accept All', 'dadecore' ) ),
            'declineText'           => get_theme_mod( 'dadecore_cookie_banner_decline_text', __( 'Decline', 'dadecore' ) ),
            'settingsText'          => get_theme_mod( 'dadecore_cookie_banner_settings_text', __( 'Cookie Settings', 'dadecore' ) ),
            'saveSettingsText'      => __( 'Save Settings', 'dadecore' ), // This text is not in customizer yet, adding directly
            'categories'            => $cookie_categories_settings,
            'gtmId'                 => get_theme_mod( 'dadecore_gtm_id', '' ),
            'consentModeEnabled'    => get_theme_mod( 'dadecore_consent_mode_enabled', false ),
            'phpDefaultConsents'    => $php_default_consents
        ) );
    }
}
add_action( 'wp_enqueue_scripts', 'dadecore_scripts' );
?>
