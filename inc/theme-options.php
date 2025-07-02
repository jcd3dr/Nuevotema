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
                'adsense_code'    => '',
                'amazon_block'    => '',
                'gtm_container'   => '',
                'login_slug'      => 'login',
                'login_attempts'  => 3,
                'lockout_minutes' => 15,
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

    add_settings_section(
        'dadecore_security_section',
        __( 'Login Security', 'dadecore' ),
        '__return_false',
        'dadecore_options'
    );

    add_settings_field(
        'dadecore_login_slug',
        __( 'Login Slug', 'dadecore' ),
        'dadecore_login_slug_field',
        'dadecore_options',
        'dadecore_security_section'
    );

    add_settings_field(
        'dadecore_login_attempts',
        __( 'Allowed Login Attempts', 'dadecore' ),
        'dadecore_login_attempts_field',
        'dadecore_options',
        'dadecore_security_section'
    );

    add_settings_field(
        'dadecore_lockout_minutes',
        __( 'Lockout Minutes', 'dadecore' ),
        'dadecore_lockout_minutes_field',
        'dadecore_options',
        'dadecore_security_section'
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
    $output['login_slug']      = isset( $input['login_slug'] ) ? sanitize_title( $input['login_slug'] ) : 'login';
    $output['login_attempts']  = isset( $input['login_attempts'] ) ? absint( $input['login_attempts'] ) : 3;
    $output['lockout_minutes'] = isset( $input['lockout_minutes'] ) ? absint( $input['lockout_minutes'] ) : 15;

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

/**
 * Output input field for the login slug.
 */
function dadecore_login_slug_field() {
    $options    = get_option( 'dadecore_options', array() );
    $login_slug = isset( $options['login_slug'] ) ? $options['login_slug'] : 'login';

    printf(
        '<input type="text" name="dadecore_options[login_slug]" value="%s" class="regular-text" />',
        esc_attr( $login_slug )
    );
}

/**
 * Output input field for allowed login attempts.
 */
function dadecore_login_attempts_field() {
    $options         = get_option( 'dadecore_options', array() );
    $login_attempts  = isset( $options['login_attempts'] ) ? (int) $options['login_attempts'] : 3;

    printf(
        '<input type="number" name="dadecore_options[login_attempts]" value="%d" class="small-text" min="1" />',
        $login_attempts
    );
}

/**
 * Output input field for lockout duration in minutes.
 */
function dadecore_lockout_minutes_field() {
    $options         = get_option( 'dadecore_options', array() );
    $lockout_minutes = isset( $options['lockout_minutes'] ) ? (int) $options['lockout_minutes'] : 15;

    printf(
        '<input type="number" name="dadecore_options[lockout_minutes]" value="%d" class="small-text" min="1" />',
        $lockout_minutes
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
