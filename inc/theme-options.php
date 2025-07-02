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
                'adsense_code'        => '',
                'amazon_block'        => '',
                'gtm_container'       => '',
                'login_slug'          => 'login',
                'login_attempts'      => 3,
                'lockout_minutes'     => 15,
                // SEO options: 1 to enable, 0 to disable each metadata block.
                'seo_meta_enabled'    => 1, // Controls <title> and description tags.
                'seo_open_graph'      => 1, // Controls Open Graph output.
                'seo_json_ld'         => 1, // Controls JSON-LD schema output.
                'seo_default_title'   => '',
                'seo_default_description' => '',
                'seo_org_logo'        => 0,
                'seo_org_name'        => '',
                'seo_org_description' => '',
                'seo_org_contact'     => '',
                'seo_org_social'      => '',
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

    add_settings_section(
        'dadecore_seo_section',
        __( 'SEO & Metadata Settings', 'dadecore' ),
        '__return_false',
        'dadecore_options'
    );

    add_settings_field(
        'dadecore_seo_meta_enabled',
        __( 'Enable Meta Tags', 'dadecore' ),
        'dadecore_seo_meta_field',
        'dadecore_options',
        'dadecore_seo_section'
    );

    add_settings_field(
        'dadecore_seo_open_graph',
        __( 'Enable Open Graph', 'dadecore' ),
        'dadecore_seo_open_graph_field',
        'dadecore_options',
        'dadecore_seo_section'
    );

    add_settings_field(
        'dadecore_seo_json_ld',
        __( 'Enable JSON-LD', 'dadecore' ),
        'dadecore_seo_json_ld_field',
        'dadecore_options',
        'dadecore_seo_section'
    );

    add_settings_field(
        'dadecore_seo_default_title',
        __( 'Default Title', 'dadecore' ),
        'dadecore_seo_default_title_field',
        'dadecore_options',
        'dadecore_seo_section'
    );

    add_settings_field(
        'dadecore_seo_default_description',
        __( 'Default Description', 'dadecore' ),
        'dadecore_seo_default_description_field',
        'dadecore_options',
        'dadecore_seo_section'
    );

    add_settings_field(
        'dadecore_seo_org_logo',
        __( 'Organization Logo', 'dadecore' ),
        'dadecore_seo_org_logo_field',
        'dadecore_options',
        'dadecore_seo_section'
    );

    add_settings_field(
        'dadecore_seo_org_name',
        __( 'Organization Name', 'dadecore' ),
        'dadecore_seo_org_name_field',
        'dadecore_options',
        'dadecore_seo_section'
    );

    add_settings_field(
        'dadecore_seo_org_description',
        __( 'Organization Description', 'dadecore' ),
        'dadecore_seo_org_description_field',
        'dadecore_options',
        'dadecore_seo_section'
    );

    add_settings_field(
        'dadecore_seo_org_contact',
        __( 'Contact Information', 'dadecore' ),
        'dadecore_seo_org_contact_field',
        'dadecore_options',
        'dadecore_seo_section'
    );

    add_settings_field(
        'dadecore_seo_org_social',
        __( 'Social Profiles', 'dadecore' ),
        'dadecore_seo_org_social_field',
        'dadecore_options',
        'dadecore_seo_section'
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
    $output['login_slug']          = isset( $input['login_slug'] ) ? sanitize_title( $input['login_slug'] ) : 'login';
    $output['login_attempts']      = isset( $input['login_attempts'] ) ? absint( $input['login_attempts'] ) : 3;
    $output['lockout_minutes']     = isset( $input['lockout_minutes'] ) ? absint( $input['lockout_minutes'] ) : 15;
    // Cast SEO option values to boolean for consistency.
    $output['seo_meta_enabled']    = isset( $input['seo_meta_enabled'] ) ? (bool) $input['seo_meta_enabled'] : false;
    $output['seo_open_graph']      = isset( $input['seo_open_graph'] ) ? (bool) $input['seo_open_graph'] : false;
    $output['seo_json_ld']         = isset( $input['seo_json_ld'] ) ? (bool) $input['seo_json_ld'] : false;
    $output['seo_default_title']   = isset( $input['seo_default_title'] ) ? sanitize_text_field( $input['seo_default_title'] ) : '';
    $output['seo_default_description'] = isset( $input['seo_default_description'] ) ? sanitize_textarea_field( $input['seo_default_description'] ) : '';
    $output['seo_org_logo']        = isset( $input['seo_org_logo'] ) ? absint( $input['seo_org_logo'] ) : 0;
    $output['seo_org_name']        = isset( $input['seo_org_name'] ) ? sanitize_text_field( $input['seo_org_name'] ) : '';
    $output['seo_org_description'] = isset( $input['seo_org_description'] ) ? sanitize_textarea_field( $input['seo_org_description'] ) : '';
    $output['seo_org_contact']     = isset( $input['seo_org_contact'] ) ? sanitize_textarea_field( $input['seo_org_contact'] ) : '';
    $output['seo_org_social']      = isset( $input['seo_org_social'] ) ? sanitize_textarea_field( $input['seo_org_social'] ) : '';

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

/**
 * Checkbox to enable meta tag output.
 */
function dadecore_seo_meta_field() {
    $options = get_option( 'dadecore_options', array() );
    $enabled = ! empty( $options['seo_meta_enabled'] );

    printf(
        '<input type="checkbox" name="dadecore_options[seo_meta_enabled]" value="1" %s />',
        checked( $enabled, true, false )
    );
}

/**
 * Checkbox to enable Open Graph tags.
 */
function dadecore_seo_open_graph_field() {
    $options = get_option( 'dadecore_options', array() );
    $enabled = ! empty( $options['seo_open_graph'] );

    printf(
        '<input type="checkbox" name="dadecore_options[seo_open_graph]" value="1" %s />',
        checked( $enabled, true, false )
    );
}

/**
 * Checkbox to enable JSON-LD output.
 */
function dadecore_seo_json_ld_field() {
    $options = get_option( 'dadecore_options', array() );
    $enabled = ! empty( $options['seo_json_ld'] );

    printf(
        '<input type="checkbox" name="dadecore_options[seo_json_ld]" value="1" %s />',
        checked( $enabled, true, false )
    );
}

/**
 * Input for the default SEO title.
 */
function dadecore_seo_default_title_field() {
    $options = get_option( 'dadecore_options', array() );
    $value   = isset( $options['seo_default_title'] ) ? $options['seo_default_title'] : '';

    printf(
        '<input type="text" name="dadecore_options[seo_default_title]" value="%s" class="regular-text" />',
        esc_attr( $value )
    );
}

/**
 * Input for the default meta description.
 */
function dadecore_seo_default_description_field() {
    $options = get_option( 'dadecore_options', array() );
    $value   = isset( $options['seo_default_description'] ) ? $options['seo_default_description'] : '';

    printf(
        '<textarea name="dadecore_options[seo_default_description]" rows="3" class="large-text">%s</textarea>',
        esc_textarea( $value )
    );
}

/**
 * Media field for the organization logo.
 */
function dadecore_seo_org_logo_field() {
    $options  = get_option( 'dadecore_options', array() );
    $logo_id  = isset( $options['seo_org_logo'] ) ? absint( $options['seo_org_logo'] ) : 0;
    $logo_url = $logo_id ? wp_get_attachment_image_url( $logo_id, 'medium' ) : '';

    printf(
        '<input type="hidden" id="seo_org_logo" name="dadecore_options[seo_org_logo]" value="%d" />',
        $logo_id
    );
    echo '<img id="seo_org_logo_preview" src="' . esc_url( $logo_url ) . '" style="max-width:150px;display:block;margin-top:5px;" />';
    echo '<button type="button" class="button dadecore-media-button" data-target="seo_org_logo">' . esc_html__( 'Select Logo', 'dadecore' ) . '</button> ';
    echo '<button type="button" class="button dadecore-media-remove" data-target="seo_org_logo">' . esc_html__( 'Remove', 'dadecore' ) . '</button>';
}

/**
 * Input for organization name.
 */
function dadecore_seo_org_name_field() {
    $options = get_option( 'dadecore_options', array() );
    $value   = isset( $options['seo_org_name'] ) ? $options['seo_org_name'] : '';

    printf(
        '<input type="text" name="dadecore_options[seo_org_name]" value="%s" class="regular-text" />',
        esc_attr( $value )
    );
}

/**
 * Textarea for organization description.
 */
function dadecore_seo_org_description_field() {
    $options = get_option( 'dadecore_options', array() );
    $value   = isset( $options['seo_org_description'] ) ? $options['seo_org_description'] : '';

    printf(
        '<textarea name="dadecore_options[seo_org_description]" rows="3" class="large-text">%s</textarea>',
        esc_textarea( $value )
    );
}

/**
 * Textarea for organization contact information.
 */
function dadecore_seo_org_contact_field() {
    $options = get_option( 'dadecore_options', array() );
    $value   = isset( $options['seo_org_contact'] ) ? $options['seo_org_contact'] : '';

    printf(
        '<textarea name="dadecore_options[seo_org_contact]" rows="3" class="large-text">%s</textarea>',
        esc_textarea( $value )
    );
}

/**
 * Textarea for social profile URLs.
 */
function dadecore_seo_org_social_field() {
    $options = get_option( 'dadecore_options', array() );
    $value   = isset( $options['seo_org_social'] ) ? $options['seo_org_social'] : '';

    printf(
        '<textarea name="dadecore_options[seo_org_social]" rows="3" class="large-text">%s</textarea>',
        esc_textarea( $value )
    );
}

/**
 * Enqueue media scripts for the options page.
 */
function dadecore_options_enqueue_media( $hook ) {
    if ( 'appearance_page_dadecore-options' !== $hook ) {
        return;
    }
    wp_enqueue_media();
    wp_add_inline_script(
        'jquery',
        "jQuery(function($){\n    $('.dadecore-media-button').on('click', function(e){\n        e.preventDefault();\n        var target = $(this).data('target');\n        var frame = wp.media({title: 'Select Image', multiple: false});\n        frame.on('select', function(){\n            var attachment = frame.state().get('selection').first().toJSON();\n            $('#'+target).val(attachment.id);\n            $('#'+target+'_preview').attr('src', attachment.url);\n        });\n        frame.open();\n    });\n    $('.dadecore-media-remove').on('click', function(){\n        var target = $(this).data('target');\n        $('#'+target).val('');\n        $('#'+target+'_preview').attr('src','');\n    });\n});"
    );
}
add_action( 'admin_enqueue_scripts', 'dadecore_options_enqueue_media' );

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
