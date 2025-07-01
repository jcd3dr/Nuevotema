<?php
/**
 * Enqueue scripts and styles
 */

function dadecore_scripts() {
    wp_enqueue_style( 'dadecore-style', get_stylesheet_uri(), array(), '1.0' ); // This usually refers to the theme's style.css in the root of the theme.

    // Enqueue your new compiled SCSS file
    wp_enqueue_style(
        'dadecore-main-styles', // New handle for your main compiled SCSS
        get_template_directory_uri() . '/assets/css/style.css', // Path to your compiled style.css
        array('dadecore-style'), // Potentially depends on the base 'dadecore-style'
        filemtime(get_template_directory() . '/assets/css/style.css') // Versioning using file modification time
    );

    wp_enqueue_style( 'dadecore-custom-styles', get_template_directory_uri() . '/assets/css/custom-styles.css', array('dadecore-main-styles'), filemtime(get_template_directory() . '/assets/css/custom-styles.css') ); // Make custom-styles depend on main-styles

    wp_enqueue_script( 'dadecore-script', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery' ), '1.0', true );
}
add_action( 'wp_enqueue_scripts', 'dadecore_scripts' );
?>
