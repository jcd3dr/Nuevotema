<?php
/**
 * Enqueue scripts and styles
 */

function dadecore_scripts() {
    // Base stylesheet loaded by WordPress
    wp_enqueue_style(
        'dadecore-style',
        get_stylesheet_uri(),
        array(),
        '1.0'
    );

    // Compiled SCSS output
    wp_enqueue_style(
        'dadecore-main-styles',
        get_template_directory_uri() . '/assets/css/style.css',
        array( 'dadecore-style' ),
        filemtime( get_template_directory() . '/assets/css/style.css' )
    );

    // Optional custom overrides
    wp_enqueue_style(
        'dadecore-custom-styles',
        get_template_directory_uri() . '/assets/css/custom-styles.css',
        array( 'dadecore-main-styles' ),
        filemtime( get_template_directory() . '/assets/css/custom-styles.css' )
    );

    wp_enqueue_script(
        'dadecore-script',
        get_template_directory_uri() . '/assets/js/main.js',
        array( 'jquery' ),
        '1.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'dadecore_scripts' );
?>
