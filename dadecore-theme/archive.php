<?php
get_header();
?>

<main id="primary" class="site-main">
    <?php if ( have_posts() ) : ?>
        <header class="page-header">
            <?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
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
