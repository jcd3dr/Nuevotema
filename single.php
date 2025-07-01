<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php
        // Start the Loop.
        while (have_posts()) :
            the_post();
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title('<h1 class="entry-title">', '</h1>'); ?>

                    <div class="entry-meta">
                        <span class="posted-on"><?php printf(__('Posted on %s', 'textdomain'), get_the_date()); ?></span>
                        <span class="byline"> <?php _e('by', 'textdomain'); ?> <?php the_author_posts_link(); ?></span>
                        <?php if (has_category()) : ?>
                            <span class="cat-links"> <?php _e('in', 'textdomain'); ?> <?php the_category(', '); ?></span>
                        <?php endif; ?>
                    </div><!-- .entry-meta -->
                </header><!-- .entry-header -->

                <?php if (has_post_thumbnail()) : ?>
                    <div class="post-thumbnail">
                        <?php the_post_thumbnail(); ?>
                    </div><!-- .post-thumbnail -->
                <?php endif; ?>

                <div class="entry-content">
                    <?php
                    the_content();

                    wp_link_pages(
                        array(
                            'before'   => '<nav class="page-links" aria-label="' . esc_attr__('Page', 'textdomain') . '">',
                            'after'    => '</nav>',
                            /* translators: %: Page number. */
                            'pagelink' => esc_html__('Page %', 'textdomain'),
                        )
                    );
                    ?>
                </div><!-- .entry-content -->

                <footer class="entry-footer">
                    <?php if (has_tag()) : ?>
                        <span class="tags-links"><?php the_tags('', ', ', ''); ?></span>
                    <?php endif; ?>
                </footer><!-- .entry-footer -->

                <?php // Ad Zone: After Post Content on Single Page ?>
                <div class="ad-zone ad-zone-single-after-content">
                     <p><?php _e('Advertisement - After Content', 'textdomain'); ?></p>
                     <?php // Replace this with your actual ad code or a function that displays an ad ?>
                </div>

                <?php
                // If comments are open or there is at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>

            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // Previous/next post navigation.
            the_post_navigation(
                array(
                    'next_text' => '<span class="meta-nav" aria-hidden="true">' . __('Next Post', 'textdomain') . '</span> ' .
                                   '<span class="screen-reader-text">' . __('Next post:', 'textdomain') . '</span> <br/>' .
                                   '<span class="post-title">%title</span>',
                    'prev_text' => '<span class="meta-nav" aria-hidden="true">' . __('Previous Post', 'textdomain') . '</span> ' .
                                   '<span class="screen-reader-text">' . __('Previous post:', 'textdomain') . '</span> <br/>' .
                                   '<span class="post-title">%title</span>',
                )
            );

        // End the loop.
        endwhile;
        ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
