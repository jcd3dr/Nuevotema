<?php
get_header();
?>

<main id="primary" class="site-main">
    <section class="error-404 not-found">
        <header class="page-header">
            <h1 class="page-title"><?php esc_html_e( 'Oops! That page can\'t be found.', 'dadecore' ); ?></h1>
        </header>
        <div class="page-content">
            <p><?php esc_html_e( 'It looks like nothing was found at this location.', 'dadecore' ); ?></p>
            <?php get_search_form(); ?>
        </div>
    </section>
</main>

<?php
get_footer();
?>
