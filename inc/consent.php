<?php
/**
 * Cookie banner and consent handling.
 */

/**
 * Output Google Consent Mode default settings.
 */
function dadecore_output_consent_mode() {
    $options = get_option( 'dadecore_options', array() );
    if ( ! empty( $options['disable_gcm'] ) ) {
        return;
    }
    ?>
    <script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('consent','default',{
        'ad_storage':'denied',
        'analytics_storage':'denied',
        'ad_user_data':'denied',
        'ad_personalization':'denied',
        'wait_for_update':500
    });
    </script>
    <?php
}
add_action( 'wp_head', 'dadecore_output_consent_mode' );

/**
 * Load Google Tag Manager after consent.
 */
function dadecore_output_gtm_script() {
    $options = get_option( 'dadecore_options', array() );
    $container = isset( $options['gtm_container'] ) ? trim( $options['gtm_container'] ) : '';
    if ( ! $container ) {
        return;
    }
    ?>
    <script>
    function dadecore_load_gtm(){
        if(window.dadecore_gtm_loaded) return;
        window.dadecore_gtm_loaded = true;
        var f=document.getElementsByTagName('script')[0];
        var j=document.createElement('script');
        j.async=true;
        j.src='https://www.googletagmanager.com/gtm.js?id=<?php echo esc_js( $container ); ?>';
        f.parentNode.insertBefore(j,f);
    }
    if(localStorage.getItem('cookieConsent')==='granted'){
        dadecore_load_gtm();
    }else{
        document.addEventListener('cookies-consent-granted', dadecore_load_gtm);
    }
    </script>
    <?php
}
add_action( 'wp_head', 'dadecore_output_gtm_script', 20 );

function dadecore_output_gtm_noscript() {
    $options = get_option( 'dadecore_options', array() );
    $container = isset( $options['gtm_container'] ) ? trim( $options['gtm_container'] ) : '';
    if ( ! $container ) {
        return;
    }
    echo '<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=' . esc_attr( $container ) . '" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>';
}
add_action( 'wp_body_open', 'dadecore_output_gtm_noscript' );

/**
 * Display the cookie banner markup.
 */
function dadecore_cookie_banner_markup() {
    ?>
    <div id="cookie-banner">
        <span><?php echo esc_html__( 'We use cookies to improve your experience.', 'dadecore' ); ?></span>
        <button class="cookie-accept"><?php echo esc_html__( 'Accept', 'dadecore' ); ?></button>
        <button class="cookie-decline"><?php echo esc_html__( 'Decline', 'dadecore' ); ?></button>
    </div>
    <?php
}
add_action( 'wp_footer', 'dadecore_cookie_banner_markup' );
