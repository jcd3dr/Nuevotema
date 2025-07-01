<?php
/**
 * Theme customizer
 */

function dadecore_customize_register( $wp_customize ) {
    // Section for GTM and Cookie Settings
    $wp_customize->add_section( 'dadecore_gtm_cookie_section', array(
        'title'    => __( 'GTM & Cookie Consent', 'dadecore' ),
        'priority' => 30,
    ) );

    // Google Tag Manager ID
    $wp_customize->add_setting( 'dadecore_gtm_id', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh', // or postMessage
    ) );
    $wp_customize->add_control( 'dadecore_gtm_id_control', array(
        'label'    => __( 'Google Tag Manager ID', 'dadecore' ),
        'section'  => 'dadecore_gtm_cookie_section',
        'settings' => 'dadecore_gtm_id',
        'type'     => 'text',
        'description' => __( 'Enter your GTM ID (e.g., GTM-XXXXXX).', 'dadecore' ),
    ) );

    // Enable Consent Mode v2
    $wp_customize->add_setting( 'dadecore_consent_mode_enabled', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'dadecore_consent_mode_enabled_control', array(
        'label'    => __( 'Enable Google Consent Mode v2', 'dadecore' ),
        'section'  => 'dadecore_gtm_cookie_section',
        'settings' => 'dadecore_consent_mode_enabled',
        'type'     => 'checkbox',
    ) );

    // Default Consent States
    $consent_types = array(
        'ad_storage' => __( 'Ad Storage', 'dadecore' ),
        'analytics_storage' => __( 'Analytics Storage', 'dadecore' ),
        'functionality_storage' => __( 'Functionality Storage', 'dadecore' ),
        'personalization_storage' => __( 'Personalization Storage', 'dadecore' ),
        'security_storage' => __( 'Security Storage', 'dadecore' ),
    );

    foreach ( $consent_types as $slug => $label ) {
        $wp_customize->add_setting( "dadecore_default_consent_{$slug}", array(
            'default'           => 'denied',
            'sanitize_callback' => 'dadecore_sanitize_consent_state',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "dadecore_default_consent_{$slug}_control", array(
            'label'    => sprintf( __( 'Default %s State', 'dadecore' ), $label ),
            'section'  => 'dadecore_gtm_cookie_section',
            'settings' => "dadecore_default_consent_{$slug}",
            'type'     => 'select',
            'choices'  => array(
                'denied'  => __( 'Denied', 'dadecore' ),
                'granted' => __( 'Granted', 'dadecore' ),
            ),
            'description' => sprintf( __( 'Default state for %s when Consent Mode is enabled.', 'dadecore' ), $label ),
        ) );
    }

    // --- Cookie Banner Settings ---

    // Separator
    $wp_customize->add_setting( 'dadecore_cookie_banner_separator', array(
        'sanitize_callback' => 'esc_html', // No actual value, just for structure
    ));
    $wp_customize->add_control( new WP_Customize_Control( $wp_customize, 'dadecore_cookie_banner_separator_control', array(
        'type' => 'hidden', // Will use customize_controls_print_styles to output HTML
        'section' => 'dadecore_gtm_cookie_section',
        'settings' => 'dadecore_cookie_banner_separator', // Dummy setting
        'description' => '<hr style="margin-top: 20px; margin-bottom: 20px;"><h3>' . __( 'Cookie Consent Banner', 'dadecore' ) . '</h3>',
    )));

    // Enable Cookie Banner
    $wp_customize->add_setting( 'dadecore_cookie_banner_enabled', array(
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'dadecore_cookie_banner_enabled_control', array(
        'label'    => __( 'Enable Cookie Consent Banner', 'dadecore' ),
        'section'  => 'dadecore_gtm_cookie_section',
        'settings' => 'dadecore_cookie_banner_enabled',
        'type'     => 'checkbox',
    ) );

    // Banner Title
    $wp_customize->add_setting( 'dadecore_cookie_banner_title', array(
        'default'           => __( 'Cookie Consent', 'dadecore' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'dadecore_cookie_banner_title_control', array(
        'label'    => __( 'Banner Title', 'dadecore' ),
        'section'  => 'dadecore_gtm_cookie_section',
        'settings' => 'dadecore_cookie_banner_title',
        'type'     => 'text',
    ) );

    // Banner Text
    $wp_customize->add_setting( 'dadecore_cookie_banner_text', array(
        'default'           => __( 'This website uses cookies to ensure you get the best experience on our website. Please accept cookies for optimal performance.', 'dadecore' ),
        'sanitize_callback' => 'wp_kses_post', // Allows some HTML
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'dadecore_cookie_banner_text_control', array(
        'label'    => __( 'Banner Text', 'dadecore' ),
        'section'  => 'dadecore_gtm_cookie_section',
        'settings' => 'dadecore_cookie_banner_text',
        'type'     => 'textarea',
    ) );

    // Accept Button Text
    $wp_customize->add_setting( 'dadecore_cookie_banner_accept_text', array(
        'default'           => __( 'Accept All', 'dadecore' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'dadecore_cookie_banner_accept_text_control', array(
        'label'    => __( 'Accept Button Text', 'dadecore' ),
        'section'  => 'dadecore_gtm_cookie_section',
        'settings' => 'dadecore_cookie_banner_accept_text',
        'type'     => 'text',
    ) );

    // Decline Button Text
    $wp_customize->add_setting( 'dadecore_cookie_banner_decline_text', array(
        'default'           => __( 'Decline', 'dadecore' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'dadecore_cookie_banner_decline_text_control', array(
        'label'    => __( 'Decline Button Text', 'dadecore' ),
        'section'  => 'dadecore_gtm_cookie_section',
        'settings' => 'dadecore_cookie_banner_decline_text',
        'type'     => 'text',
    ) );

    // Settings Button Text
    $wp_customize->add_setting( 'dadecore_cookie_banner_settings_text', array(
        'default'           => __( 'Cookie Settings', 'dadecore' ),
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ) );
    $wp_customize->add_control( 'dadecore_cookie_banner_settings_text_control', array(
        'label'    => __( 'Settings Button Text', 'dadecore' ),
        'section'  => 'dadecore_gtm_cookie_section',
        'settings' => 'dadecore_cookie_banner_settings_text',
        'type'     => 'text',
    ) );

    // Cookie Categories
    // For simplicity, we'll define fixed categories and allow enabling/disabling and custom descriptions.
    // A repeater control would be more flexible but adds complexity.
    $cookie_categories = array(
        'necessary' => array(
            'label' => __( 'Necessary Cookies', 'dadecore' ),
            'default_enabled' => true, // Necessary cookies are typically always enabled
            'description' => __( 'These cookies are essential for the website to function properly. They are usually set in response to actions made by you, such as setting your privacy preferences, logging in, or filling in forms.', 'dadecore' )
        ),
        'analytics' => array(
            'label' => __( 'Analytics Cookies', 'dadecore' ),
            'default_enabled' => false,
            'description' => __( 'These cookies allow us to count visits and traffic sources, so we can measure and improve the performance of our site. They help us know which pages are the most and least popular and see how visitors move around the site.', 'dadecore' )
        ),
        'marketing' => array(
            'label' => __( 'Marketing Cookies', 'dadecore' ),
            'default_enabled' => false,
            'description' => __( 'These cookies may be set through our site by our advertising partners. They may be used by those companies to build a profile of your interests and show you relevant adverts on other sites.', 'dadecore' )
        ),
    );

    foreach ( $cookie_categories as $slug => $cat ) {
        // Enable/Disable Toggle (except for necessary)
        if ( $slug !== 'necessary' ) {
            $wp_customize->add_setting( "dadecore_cookie_cat_enabled_{$slug}", array(
                'default'           => $cat['default_enabled'],
                'sanitize_callback' => 'wp_validate_boolean',
                'transport'         => 'refresh',
            ) );
            $wp_customize->add_control( "dadecore_cookie_cat_enabled_{$slug}_control", array(
                'label'    => sprintf( __( 'Enable %s', 'dadecore' ), $cat['label'] ),
                'section'  => 'dadecore_gtm_cookie_section',
                'settings' => "dadecore_cookie_cat_enabled_{$slug}",
                'type'     => 'checkbox',
            ) );
        }

        // Description Textarea
        $wp_customize->add_setting( "dadecore_cookie_cat_desc_{$slug}", array(
            'default'           => $cat['description'],
            'sanitize_callback' => 'wp_kses_post',
            'transport'         => 'refresh',
        ) );
        $wp_customize->add_control( "dadecore_cookie_cat_desc_{$slug}_control", array(
            'label'    => sprintf( __( '%s Description', 'dadecore' ), $cat['label'] ),
            'section'  => 'dadecore_gtm_cookie_section',
            'settings' => "dadecore_cookie_cat_desc_{$slug}",
            'type'     => 'textarea',
        ) );
    }
    // Add other customizer settings here
}
add_action( 'customize_register', 'dadecore_customize_register' );

/**
 * Sanitize consent state.
 */
function dadecore_sanitize_consent_state( $input ) {
    $valid = array( 'denied', 'granted' );
    if ( in_array( $input, $valid, true ) ) {
        return $input;
    }
    return 'denied';
}

?>
