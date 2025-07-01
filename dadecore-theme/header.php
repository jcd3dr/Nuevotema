<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of کی<head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package DadeCore
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php
    // GTM and Consent Mode Integration
    $dadecore_gtm_id = get_theme_mod( 'dadecore_gtm_id' );
    $dadecore_consent_mode_enabled = get_theme_mod( 'dadecore_consent_mode_enabled', false );

    if ( ! empty( $dadecore_gtm_id ) ) : ?>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        <?php if ( $dadecore_consent_mode_enabled ) : ?>
        gtag('consent', 'default', {
            'ad_storage': '<?php echo esc_js( get_theme_mod( 'dadecore_default_consent_ad_storage', 'denied' ) ); ?>',
            'analytics_storage': '<?php echo esc_js( get_theme_mod( 'dadecore_default_consent_analytics_storage', 'denied' ) ); ?>',
            'functionality_storage': '<?php echo esc_js( get_theme_mod( 'dadecore_default_consent_functionality_storage', 'denied' ) ); ?>',
            'personalization_storage': '<?php echo esc_js( get_theme_mod( 'dadecore_default_consent_personalization_storage', 'denied' ) ); ?>',
            'security_storage': '<?php echo esc_js( get_theme_mod( 'dadecore_default_consent_security_storage', 'denied' ) ); ?>',
            'wait_for_update': 500 // Optional: Adjust as needed
        });
        <?php
            // Example of data redaction, can be expanded
            if (get_theme_mod( 'dadecore_default_consent_ad_storage', 'denied' ) === 'denied') {
                echo "gtag('set', 'ads_data_redaction', true);\n";
            }
        ?>
        <?php endif; // end consent_mode_enabled ?>
        // Fallback for dataLayer if GTM doesn't load (e.g. adblocker)
        if (typeof gtag === 'undefined') {
            function gtag() { console.warn('GTM not loaded, gtag calls will not be processed.'); dataLayer.push(arguments); }
        }
    </script>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo esc_js( $dadecore_gtm_id ); ?>');</script>
    <!-- End Google Tag Manager -->
    <?php endif; // end !empty gtm_id ?>
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); // Hook for GTM noscript and other body opening tags ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'dadecore' ); ?></a>

    <?php
    // Cargar directamente la plantilla de cabecera del tema.
    // Elementor Pro (si está activo y configurado) puede sobrescribir esta cabecera
    // utilizando sus propias funcionalidades de Theme Builder sin necesidad de `elementor_theme_do_location()`.
    get_template_part( 'template-parts/header/site-header' );
    ?>
    <div id="content" class="site-content">
