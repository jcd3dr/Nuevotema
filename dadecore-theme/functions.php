<?php
/**
 * Theme functions and definitions
 */

// Include modular files
require_once get_template_directory() . '/inc/setup.php';
require_once get_template_directory() . '/inc/enqueue.php';
require_once get_template_directory() . '/inc/customizer.php';
require_once get_template_directory() . '/inc/theme-options.php';
require_once get_template_directory() . '/inc/security.php';

// Elementor locations
function dadecore_register_elementor_locations( $elementor_theme_manager ) {
    $elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'dadecore_register_elementor_locations' );
?>
