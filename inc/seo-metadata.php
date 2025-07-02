<?php
/**
 * Basic SEO metadata generation.
 *
 * Outputs fundamental meta tags and structured data when no dedicated
 * SEO plugin is detected. Developers can override this behavior via the
 * `dadecore_skip_native_seo` filter or disable individual pieces of output
 * through both filters and options.
 *
 * Options are read from the `dadecore_seo_options` option. Expected keys:
 * - `disable_title`       Whether to skip the <title> tag.
 * - `disable_description` Whether to skip the meta description tag.
 * - `disable_open_graph`  Whether to skip Open Graph tags.
 * - `disable_json_ld`     Whether to skip JSON-LD structured data.
 *
 * Each key defaults to false when the option is missing. Additionally, each
 * output section has a dedicated filter that receives a boolean value:
 * - `dadecore_enable_seo_title`
 * - `dadecore_enable_seo_description`
 * - `dadecore_enable_open_graph`
 * - `dadecore_enable_json_ld`
 *
 * @package DadeCore
 */

/**
 * Determine if native SEO output should run.
 *
 * The default behavior is to skip output when a well known SEO plugin is
 * active. Developers can force skipping by filtering `dadecore_skip_native_seo`.
 *
 * @return bool True if the theme should handle SEO metadata.
 */
function dadecore_should_output_seo() {
    $has_plugin = defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' );
    $skip       = apply_filters( 'dadecore_skip_native_seo', $has_plugin );

    return ! $skip;
}

if ( dadecore_should_output_seo() ) {
    add_action( 'wp_head', 'dadecore_output_seo_metadata', 1 );
}

/**
 * Render SEO meta tags and structured data.
 */
function dadecore_output_seo_metadata() {
    $options = get_option( 'dadecore_seo_options', array() );

    $title_enabled = ! isset( $options['disable_title'] ) || ! $options['disable_title'];
    $title_enabled = apply_filters( 'dadecore_enable_seo_title', $title_enabled );

    $desc_enabled = ! isset( $options['disable_description'] ) || ! $options['disable_description'];
    $desc_enabled = apply_filters( 'dadecore_enable_seo_description', $desc_enabled );

    $og_enabled = ! isset( $options['disable_open_graph'] ) || ! $options['disable_open_graph'];
    $og_enabled = apply_filters( 'dadecore_enable_open_graph', $og_enabled );

    $jsonld_enabled = ! isset( $options['disable_json_ld'] ) || ! $options['disable_json_ld'];
    $jsonld_enabled = apply_filters( 'dadecore_enable_json_ld', $jsonld_enabled );

    global $post;

    /* -------------------------- <title> & description ----------------------- */
    if ( $title_enabled ) {
        $title = is_front_page() ? get_bloginfo( 'name' ) : wp_get_document_title();
        echo '<title>' . esc_html( $title ) . "</title>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    if ( $desc_enabled ) {
        $description = '';
        if ( is_singular() ) {
            if ( has_excerpt( $post ) ) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words( wp_strip_all_tags( get_the_content() ), 55 );
            }
        } else {
            $description = get_bloginfo( 'description' );
        }

        echo '<meta name="description" content="' . esc_attr( $description ) . "">\n";
    }

    /* --------------------------- Open Graph tags --------------------------- */
    if ( $og_enabled ) {
        $og_title       = is_singular() ? get_the_title() : get_bloginfo( 'name' );
        $og_description = is_singular() ? ( has_excerpt( $post ) ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( $post->post_content ), 55 ) ) : get_bloginfo( 'description' );
        $og_url         = is_singular() ? get_permalink() : home_url();
        $og_image       = '';

        if ( is_singular() && has_post_thumbnail() ) {
            $og_image = get_the_post_thumbnail_url( null, 'full' );
        } else {
            $logo_id = get_theme_mod( 'custom_logo' );
            if ( $logo_id ) {
                $logo = wp_get_attachment_image_src( $logo_id, 'full' );
                if ( $logo ) {
                    $og_image = $logo[0];
                }
            }
        }

        echo '<meta property="og:type" content="' . ( is_singular() ? 'article' : 'website' ) . "">\n";
        echo '<meta property="og:title" content="' . esc_attr( $og_title ) . "">\n";
        echo '<meta property="og:description" content="' . esc_attr( $og_description ) . "">\n";
        echo '<meta property="og:url" content="' . esc_url( $og_url ) . "">\n";
        if ( $og_image ) {
            echo '<meta property="og:image" content="' . esc_url( $og_image ) . "">\n";
        }
    }

    /* ----------------------------- JSON-LD data ---------------------------- */
    if ( $jsonld_enabled ) {
        // Organization schema.
        $org = array(
            '@context' => 'https://schema.org',
            '@type'    => 'Organization',
            'url'      => home_url(),
            'name'     => get_bloginfo( 'name' ),
        );
        $logo_id = get_theme_mod( 'custom_logo' );
        if ( $logo_id ) {
            $logo = wp_get_attachment_image_src( $logo_id, 'full' );
            if ( $logo ) {
                $org['logo'] = $logo[0];
            }
        }
        echo "<script type=\"application/ld+json\">\n" . wp_json_encode( $org ) . "\n</script>\n";

        // Article schema for singular content.
        if ( is_singular() ) {
            $article = array(
                '@context'       => 'https://schema.org',
                '@type'          => 'Article',
                'mainEntityOfPage' => get_permalink(),
                'headline'       => get_the_title(),
                'datePublished'  => get_the_date( 'c' ),
                'dateModified'   => get_the_modified_date( 'c' ),
                'author'         => array(
                    '@type' => 'Person',
                    'name'  => get_the_author_meta( 'display_name', $post->post_author ),
                ),
                'publisher'      => array(
                    '@type' => 'Organization',
                    'name'  => get_bloginfo( 'name' ),
                ),
            );
            if ( has_post_thumbnail() ) {
                $img = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
                if ( $img ) {
                    $article['image'] = array( $img[0] );
                }
            }
            if ( has_excerpt( $post ) ) {
                $article['description'] = get_the_excerpt();
            }

            echo "<script type=\"application/ld+json\">\n" . wp_json_encode( $article ) . "\n</script>\n";
        }

        // Generic service schema.
        $service = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'Service',
            'serviceType' => get_bloginfo( 'name' ),
            'provider'    => array(
                '@type' => 'Organization',
                'name'  => get_bloginfo( 'name' ),
                'url'   => home_url(),
            ),
        );
        echo "<script type=\"application/ld+json\">\n" . wp_json_encode( $service ) . "\n</script>\n";
    }
}

?>
