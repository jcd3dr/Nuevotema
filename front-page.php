<?php
if ( ! elementor_theme_do_location( 'single' ) ) {
    get_header();
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post();
            the_content();
        }
    }
    get_footer();
}
?>
