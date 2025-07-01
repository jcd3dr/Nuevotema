<?php
get_header();
?>

<main id="primary" class="site-main">
    <?php if ( have_posts() ) : ?>
        <header class="page-header">
            <h1 class="page-title"><?php printf( esc_html__( 'Search Results for: %s', 'dadecore' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
        </header>
        <?php while ( have_posts() ) : the_post(); ?>
            <?php get_template_part( 'template-parts/content/content', get_post_type() ); ?>
        <?php endwhile; ?>
    <?php else : ?>
        <?php get_template_part( 'template-parts/content/content', 'none' ); ?>
    <?php endif; ?>
</main>

<?php get_sidebar();
get_footer();
?>
