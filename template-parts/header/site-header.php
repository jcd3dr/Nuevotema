<?php
// Allow users to change the header color from the Customizer
$header_bg = get_theme_mod( 'header_background_color', '#ffffff' );
?>
<header id="masthead" class="site-header" style="background-color: <?php echo esc_attr( $header_bg ); ?>;">
    <div class="site-branding">
        <?php the_custom_logo(); ?>
        <div class="site-title-description">
            <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
            <p class="site-description"><?php bloginfo( 'description' ); ?></p>
        </div>
    </div>
    <button id="menu-toggle" class="menu-toggle" aria-controls="site-navigation" aria-expanded="false">&#9776;</button>
    <nav id="site-navigation" class="main-navigation">
        <?php wp_nav_menu( array( 'theme_location' => 'menu-1' ) ); ?>
    </nav>
</header>
