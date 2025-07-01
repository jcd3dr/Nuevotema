<?php
/**
 * Enqueue scripts and styles
 */

function dadecore_scripts() {
    wp_enqueue_style( 'dadecore-style', get_stylesheet_uri(), array(), '1.0' );
    wp_enqueue_script( 'dadecore-script', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'dadecore_scripts' );
?>
