<?php
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {
    get_header();
    while ( have_posts() ) {
        the_post();
        the_content();
    }
    comments_template();
    get_footer();
}
?>
