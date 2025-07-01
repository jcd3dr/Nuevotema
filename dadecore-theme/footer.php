<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package DadeCore
 */

?>
    </div><!-- #content -->

    <?php
    // Cargar directamente la plantilla de pie de página del tema.
    // Elementor Pro (si está activo y configurado) puede sobrescribir este pie de página
    // utilizando sus propias funcionalidades de Theme Builder sin necesidad de `elementor_theme_do_location()`.
    get_template_part( 'template-parts/footer/site-footer' );
    ?>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
