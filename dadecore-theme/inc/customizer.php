<?php
/**
 * Theme customizer
 */

function dadecore_customize_register( $wp_customize ) {

    // Site Title & Tagline color (already part of WordPress, but we can add more controls or modify existing ones if needed)
    // Example: $wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
    // Example: $wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';

    // 1. Theme Colors Section
    $wp_customize->add_section( 'dadecore_colors_section', array(
        'title'    => __( 'Theme Colors', 'dadecore' ),
        'priority' => 30,
    ) );

    // Primary Color Setting
    $wp_customize->add_setting( 'dadecore_primary_color', array(
        'default'   => '#007bff',
        'transport' => 'refresh', // or 'postMessage' for live preview
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_primary_color_control', array(
        'label'    => __( 'Primary Color', 'dadecore' ),
        'section'  => 'dadecore_colors_section',
        'settings' => 'dadecore_primary_color',
    ) ) );

    // Secondary Color Setting
    $wp_customize->add_setting( 'dadecore_secondary_color', array(
        'default'   => '#6c757d',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_secondary_color_control', array(
        'label'    => __( 'Secondary Color', 'dadecore' ),
        'section'  => 'dadecore_colors_section',
        'settings' => 'dadecore_secondary_color',
    ) ) );

    // Body Text Color Setting
    $wp_customize->add_setting( 'dadecore_body_text_color', array(
        'default'   => '#212529',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'dadecore_body_text_color_control', array(
        'label'    => __( 'Body Text Color', 'dadecore' ),
        'section'  => 'dadecore_colors_section',
        'settings' => 'dadecore_body_text_color',
    ) ) );


    // 2. Theme Typography Section
    $wp_customize->add_section( 'dadecore_typography_section', array(
        'title'    => __( 'Theme Typography', 'dadecore' ),
        'priority' => 35,
    ) );

    // Primary Font Setting
    $wp_customize->add_setting( 'dadecore_primary_font', array(
        'default'   => 'Arial, sans-serif',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'dadecore_primary_font_control', array(
        'label'    => __( 'Primary Font (e.g., Headings)', 'dadecore' ),
        'section'  => 'dadecore_typography_section',
        'settings' => 'dadecore_primary_font',
        'type'     => 'text',
    ) );

    // Secondary Font Setting
    $wp_customize->add_setting( 'dadecore_secondary_font', array(
        'default'   => 'Georgia, serif',
        'transport' => 'refresh',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'dadecore_secondary_font_control', array(
        'label'    => __( 'Secondary Font (e.g., Body)', 'dadecore' ),
        'section'  => 'dadecore_typography_section',
        'settings' => 'dadecore_secondary_font',
        'type'     => 'text',
    ) );

    // It's good practice to also add CSS output for these settings.
    // This is often done in a separate function hooked to `wp_head` or by enqueuing inline styles.
    // For this task, only parameters are requested, so I will omit the CSS output part for now.
}
add_action( 'customize_register', 'dadecore_customize_register' );

// Example of how you might output CSS (optional for this task based on wording):
/*
function dadecore_customizer_css() {
    ?>
    <style type="text/css">
        body {
            color: <?php echo esc_attr( get_theme_mod( 'dadecore_body_text_color', '#212529' ) ); ?>;
            font-family: <?php echo esc_attr( get_theme_mod( 'dadecore_secondary_font', 'Georgia, serif' ) ); ?>;
        }
        h1, h2, h3, h4, h5, h6 {
            font-family: <?php echo esc_attr( get_theme_mod( 'dadecore_primary_font', 'Arial, sans-serif' ) ); ?>;
        }
        a {
            color: <?php echo esc_attr( get_theme_mod( 'dadecore_primary_color', '#007bff' ) ); ?>;
        }
        /* Add more rules for secondary color, etc. */
    </style>
    <?php
}
add_action( 'wp_head', 'dadecore_customizer_css' );
*/
?>
