<?php
/**
 * Theme setup
 */

function dadecore_theme_setup() {
    // Make theme available for translation
    load_theme_textdomain( 'dadecore', get_template_directory() . '/languages' );

    // Add default posts and comments RSS feed links to head
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title
    add_theme_support( 'title-tag' );

    // Enable support for Post Thumbnails
    add_theme_support( 'post-thumbnails' );

    // Register navigation menus
    register_nav_menus( array(
        'primary' => esc_html__( 'Primary Menu', 'dadecore' ),
        'footer'  => esc_html__( 'Footer Menu', 'dadecore' ),
    ) );

    // Switch default core markup for search form, comment form, and comments
    // Note: 'search-form', 'gallery', 'caption' are required by the task.
    // 'comment-form' and 'comment-list' are kept from existing code.
    add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

    // Custom logo support
    add_theme_support( 'custom-logo' );

    // WooCommerce support
    add_theme_support( 'woocommerce' );

    // Editor styles
    add_theme_support( 'editor-styles' );
    add_editor_style( 'assets/css/editor-style.css' );
}
add_action( 'after_setup_theme', 'dadecore_theme_setup' );

// Widgets area
function dadecore_widgets_init() {
    register_sidebar( array(
        'name'          => esc_html__( 'Main Sidebar', 'dadecore' ),
        'id'            => 'main-sidebar',
        'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'dadecore' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'dadecore_widgets_init' );
?>
