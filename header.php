<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package DadeCore
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'dadecore' ); ?></a>

    <?php
    // Cargar directamente la plantilla de cabecera del tema.
    // Elementor Pro (si está activo y configurado) puede sobrescribir esta cabecera
    // utilizando sus propias funcionalidades de Theme Builder sin necesidad de `elementor_theme_do_location()`.
    get_template_part( 'template-parts/header/site-header' );
    ?>
    <div id="content" class="site-content">
