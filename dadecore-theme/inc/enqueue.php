<?php
/**
 * Enqueue scripts and styles
 */

function dadecore_scripts() {
    // Enqueue main stylesheet
    wp_enqueue_style( 'dadecore-style', get_stylesheet_uri(), array(), '1.0' );

    // Enqueue theme stylesheet
    wp_enqueue_style( 'dadecore-theme-style', get_template_directory_uri() . '/assets/css/theme.css', array('dadecore-style'), '1.0' );

    // Enqueue JavaScript
    wp_enqueue_script( 'dadecore-script', get_template_directory_uri() . '/assets/js/scripts.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'dadecore_scripts' );
?>
