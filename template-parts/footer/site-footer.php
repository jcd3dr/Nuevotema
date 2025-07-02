<?php
// Customizable footer text set from the Customizer
$footer_text = get_theme_mod( 'footer_text', sprintf( esc_html__( 'Proudly powered by %s', 'dadecore' ), 'WordPress' ) );
?>
<footer id="colophon" class="site-footer">
    <div class="site-info">
        <?php echo esc_html( $footer_text ); ?>
    </div>
</footer>
