<?php
/**
 * Additional theme options
 *
 * Provides a settings page under Appearance > "DadeCore Options" for managing
 * advertising and analytics snippets. Options are stored via the WordPress
 * Settings API.
 */

/**
 * Register settings, sections and fields for the options page.
 */
function dadecore_register_theme_options() {
    register_setting(
        'dadecore_options_group',
        'dadecore_options',
        array(
            'type'              => 'array',
            'sanitize_callback' => 'dadecore_sanitize_options',
            'default'           => array(
                'adsense_code'  => '',
                'amazon_block'  => '',
                'gtm_container' => '',
            ),
        )
    );

    add_settings_section(
        'dadecore_ads_section',
        __( 'Advertising', 'dadecore' ),
        '__return_false',
        'dadecore_options'
    );

    add_settings_field(
        'dadecore_adsense_code',
        __( 'Google AdSense Code', 'dadecore' ),
        'dadecore_adsense_field',
        'dadecore_options',
        'dadecore_ads_section'
    );

    add_settings_field(
        'dadecore_amazon_block',
        __( 'Amazon Affiliate Block', 'dadecore' ),
        'dadecore_amazon_field',
        'dadecore_options',
        'dadecore_ads_section'
    );

    add_settings_field(
        'dadecore_gtm_container',
        __( 'Google Tag Manager Container ID', 'dadecore' ),
        'dadecore_gtm_field',
        'dadecore_options',
        'dadecore_ads_section'
    );
}
add_action( 'admin_init', 'dadecore_register_theme_options' );

/**
 * Sanitize the options before saving.
 *
 * @param array $input Raw options.
 * @return array Sanitized options.
 */
function dadecore_sanitize_options( $input ) {
    $output = array();
    $output['adsense_code']  = isset( $input['adsense_code'] ) ? wp_kses_post( $input['adsense_code'] ) : '';
    $output['amazon_block']  = isset( $input['amazon_block'] ) ? wp_kses_post( $input['amazon_block'] ) : '';
    $output['gtm_container'] = isset( $input['gtm_container'] ) ? sanitize_text_field( $input['gtm_container'] ) : '';

    return $output;
}

/** Field callbacks ------------------------------------------------------- */

/**
 * Output textarea for Google AdSense code.
 */
function dadecore_adsense_field() {
    $options      = get_option( 'dadecore_options', array() );
    $adsense_code = isset( $options['adsense_code'] ) ? $options['adsense_code'] : '';

    printf(
        '<textarea name="dadecore_options[adsense_code]" rows="5" class="large-text code">%s</textarea>',
        esc_textarea( $adsense_code )
    );
}

/**
 * Output textarea for Amazon affiliate block code.
 */
function dadecore_amazon_field() {
    $options      = get_option( 'dadecore_options', array() );
    $amazon_block = isset( $options['amazon_block'] ) ? $options['amazon_block'] : '';

    printf(
        '<textarea name="dadecore_options[amazon_block]" rows="5" class="large-text code">%s</textarea>',
        esc_textarea( $amazon_block )
    );
}

/**
 * Output input field for Google Tag Manager container ID.
 */
function dadecore_gtm_field() {
    $options       = get_option( 'dadecore_options', array() );
    $gtm_container = isset( $options['gtm_container'] ) ? $options['gtm_container'] : '';

    printf(
        '<input type="text" name="dadecore_options[gtm_container]" value="%s" class="regular-text" />',
        esc_attr( $gtm_container )
    );
}

/** Menu and page rendering ------------------------------------------------ */

/**
 * Add the options page under Appearance.
 */
function dadecore_theme_options_menu() {
    add_theme_page(
        __( 'DadeCore Options', 'dadecore' ),
        __( 'DadeCore Options', 'dadecore' ),
        'manage_options',
        'dadecore-options',
        'dadecore_render_options_page'
    );
}
add_action( 'admin_menu', 'dadecore_theme_options_menu' );

/**
 * Render the options page markup.
 */
function dadecore_render_options_page() {
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            settings_fields( 'dadecore_options_group' );
            do_settings_sections( 'dadecore_options' );
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

?>
