<header id="masthead" class="site-header">
    <div class="site-branding">
        <?php the_custom_logo(); ?>
        <div class="site-title-description">
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            <p class="site-description"><?php bloginfo( 'description' ); ?></p>
        </div>
    </div>
    <nav id="site-navigation" class="main-navigation">
        <?php wp_nav_menu( array( 'theme_location' => 'menu-1' ) ); ?>
    </nav>
</header>
