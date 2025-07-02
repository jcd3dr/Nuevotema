<?php
/**
 * Theme customizer
 */

function dadecore_customize_register( $wp_customize ) {
    // Load options from theme.json so defaults stay in sync
    $json    = json_decode( file_get_contents( get_template_directory() . '/theme.json' ), true );
    $options = isset( $json['settings'] ) ? $json['settings'] : array();

    /* ------------------------------------ COLORS ------------------------------------ */
    // Create color controls based on the palette defined in theme.json
    if ( ! empty( $options['color']['palette'] ) ) {
        foreach ( $options['color']['palette'] as $color ) {
            $slug  = $color['slug'];
            $label = $color['name'];

            // Setting stores the selected color for the slug
            $wp_customize->add_setting( "palette_{$slug}", array(
                'default'           => $color['color'],
                'sanitize_callback' => 'sanitize_hex_color',
            ) );

            // Color picker control for the palette color
            $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, "palette_{$slug}", array(
                'label'   => sprintf( __( '%s Color', 'dadecore' ), $label ),
                'section' => 'colors',
            ) ) );
        }
    }

    /* ---------------------------------- TYPOGRAPHY --------------------------------- */
    $wp_customize->add_section( 'dadecore_typography', array(
        'title' => __( 'Typography', 'dadecore' ),
    ) );

    if ( ! empty( $options['typography']['fontFamilies'] ) ) {
        // Map slugs to readable names for the font family select
        $fonts        = array();
        $default_font = '';
        foreach ( $options['typography']['fontFamilies'] as $index => $font ) {
            $fonts[ $font['slug'] ] = $font['name'];
            if ( 0 === $index ) {
                $default_font = $font['slug'];
            }
        }

        // Chosen font family for body text
        $wp_customize->add_setting( 'font_family', array(
            'default'           => $default_font,
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $wp_customize->add_control( 'font_family', array(
            'label'   => __( 'Font Family', 'dadecore' ),
            'section' => 'dadecore_typography',
            'type'    => 'select',
            'choices' => $fonts,
        ) );
    }

    if ( ! empty( $options['typography']['fontSizes'] ) ) {
        // Map slugs to names for the base size select
        $sizes        = array();
        $default_size = '';
        foreach ( $options['typography']['fontSizes'] as $index => $size ) {
            $sizes[ $size['slug'] ] = $size['name'];
            if ( 'regular' === $size['slug'] ) {
                $default_size = $size['slug'];
            }
        }

        // Base font size slug
        $wp_customize->add_setting( 'font_size', array(
            'default'           => $default_size,
            'sanitize_callback' => 'sanitize_text_field',
        ) );

        $wp_customize->add_control( 'font_size', array(
            'label'   => __( 'Base Font Size', 'dadecore' ),
            'section' => 'dadecore_typography',
            'type'    => 'select',
            'choices' => $sizes,
        ) );
    }

    /* ------------------------------------ LAYOUT ------------------------------------ */
    $wp_customize->add_section( 'dadecore_layout', array(
        'title' => __( 'Layout', 'dadecore' ),
    ) );

    $content_default = isset( $options['layout']['contentSize'] ) ? $options['layout']['contentSize'] : '800px';
    $wide_default    = isset( $options['layout']['wideSize'] ) ? $options['layout']['wideSize'] : '1200px';

    // Width for normal content area
    $wp_customize->add_setting( 'content_width', array(
        'default'           => $content_default,
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'content_width', array(
        'label'   => __( 'Content Width', 'dadecore' ),
        'section' => 'dadecore_layout',
        'type'    => 'text',
    ) );

    // Width for wide blocks
    $wp_customize->add_setting( 'wide_width', array(
        'default'           => $wide_default,
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'wide_width', array(
        'label'   => __( 'Wide Width', 'dadecore' ),
        'section' => 'dadecore_layout',
        'type'    => 'text',
    ) );

    /* ---------------------------- HEADER & FOOTER SETTINGS --------------------------- */
    $wp_customize->add_section( 'dadecore_header', array(
        'title'    => __( 'Header', 'dadecore' ),
        'priority' => 30,
    ) );

    // Background color for the site header
    $wp_customize->add_setting( 'header_background_color', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'header_background_color', array(
        'label'   => __( 'Header Background Color', 'dadecore' ),
        'section' => 'dadecore_header',
    ) ) );

    $wp_customize->add_section( 'dadecore_footer', array(
        'title'    => __( 'Footer', 'dadecore' ),
        'priority' => 40,
    ) );

    // Text displayed in the footer area
    $wp_customize->add_setting( 'footer_text', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ) );
    $wp_customize->add_control( 'footer_text', array(
        'label'   => __( 'Footer Text', 'dadecore' ),
        'section' => 'dadecore_footer',
        'type'    => 'text',
    ) );
}
add_action( 'customize_register', 'dadecore_customize_register' );
?>
