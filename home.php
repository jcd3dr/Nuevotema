<?php get_header(); ?>

<div id="primary" class="content-area">
    <main id="main" class="site-main">

        <?php if (have_posts()) : ?>

            <header class="page-header">
                <h1 class="page-title"><?php _e('Latest Posts', 'textdomain'); ?></h1>
            </header><!-- .page-header -->

            <?php // Ad Zone: Before Content Loop ?>
            <div class="ad-zone ad-zone-before-loop">
                <p><?php _e('Advertisement - Before Loop', 'textdomain'); ?></p>
                <?php // Replace this with your actual ad code or a function that displays an ad ?>
            </div>

            <?php
            $post_counter = 0; // Initialize post counter for ads within the loop
            // Start the Loop.
            while (have_posts()) :
                the_post();
                $post_counter++;
                ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                    <header class="entry-header">
                        <?php the_title(sprintf('<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>'); ?>

                        <div class="entry-meta">
                            <span class="posted-on"><?php printf(__('Posted on %s', 'textdomain'), get_the_date()); ?></span>
                            <span class="byline"> <?php _e('by', 'textdomain'); ?> <?php the_author_posts_link(); ?></span>
                            <?php if (has_category()) : ?>
                                <span class="cat-links"> <?php _e('in', 'textdomain'); ?> <?php the_category(', '); ?></span>
                            <?php endif; ?>
                        </div><!-- .entry-meta -->
                    </header><!-- .entry-header -->

                    <div class="entry-summary">
                        <?php the_excerpt(); ?>
                    </div><!-- .entry-summary -->

                    <footer class="entry-footer">
                        <?php if (has_tag()) : ?>
                            <span class="tags-links"><?php the_tags('', ', ', ''); ?></span>
                        <?php endif; ?>
                        <span class="comments-link"><a href="<?php comments_link(); ?>"><?php comments_number(__('Leave a comment', 'textdomain'), __('1 Comment', 'textdomain'), __('% Comments', 'textdomain')); ?></a></span>
                    </footer><!-- .entry-footer -->
                </article><!-- #post-<?php the_ID(); ?> -->

                <?php
                // Ad Zone: After every N posts (e.g., 3rd post)
                if ($post_counter % 3 === 0) : ?>
                    <div class="ad-zone ad-zone-in-loop">
                        <p><?php _e('Advertisement - In Loop', 'textdomain'); ?></p>
                        <?php // Replace this with your actual ad code or a function that displays an ad ?>
                    </div>
                <?php endif; ?>

            <?php
            // End the loop.
            endwhile;
            ?>

            <?php // Ad Zone: After Content Loop ?>
            <div class="ad-zone ad-zone-after-loop">
                <p><?php _e('Advertisement - After Loop', 'textdomain'); ?></p>
                <?php // Replace this with your actual ad code or a function that displays an ad ?>
            </div>

            <?php
            // Previous/next page navigation.
            the_posts_pagination(
                array(
                    'prev_text'          => __('Previous page', 'textdomain'),
                    'next_text'          => __('Next page', 'textdomain'),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'textdomain') . ' </span>',
                )
            );

        // If no content, include the "No posts found" template.
        else :
            // If you want to display a specific message or include a template part.
            // get_template_part( 'template-parts/content/content-none' );
            ?>
            <p><?php _e('No posts found.', 'textdomain'); ?></p>
        <?php endif; ?>

    </main><!-- #main -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
