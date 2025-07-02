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
require_once get_template_directory() . '/inc/seo-metadata.php';
require_once get_template_directory() . '/inc/consent.php';

// Elementor locations
// La siguiente función y acción registran las ubicaciones del tema central de WordPress (header, footer, archive, single)
// para que Elementor Pro pueda reconocerlas y ofrecer la opción de reemplazarlas mediante su Theme Builder.
// Aunque el tema ya no llama activamente a `elementor_theme_do_location()`, mantener esto puede ser útil
// para usuarios con Elementor Pro que deseen esta funcionalidad.
// Si se prefiere una desvinculación total o si no se espera que los usuarios usen Elementor Pro para estas áreas,
// se puede eliminar o comentar por completo. Por ahora, se deja funcional para mayor compatibilidad con Elementor Pro.
function dadecore_register_elementor_locations( $elementor_theme_manager ) {
    $elementor_theme_manager->register_core_location( 'header' );
    $elementor_theme_manager->register_core_location( 'footer' );
    $elementor_theme_manager->register_core_location( 'archive' );
    $elementor_theme_manager->register_core_location( 'single' );
}
add_action( 'elementor/theme/register_locations', 'dadecore_register_elementor_locations' );

// Ejemplo de cómo se podría comentar si se decide no registrar las ubicaciones:
/*
function dadecore_register_elementor_locations( $elementor_theme_manager ) {
    $elementor_theme_manager->register_all_core_location();
}
add_action( 'elementor/theme/register_locations', 'dadecore_register_elementor_locations' );
*/
?>
