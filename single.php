<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
    get_header();
    while ( have_posts() ) {
        the_post();
        get_template_part( 'template-parts/content/content', 'single' );
    }
    get_sidebar();
    get_footer();
}
?>
