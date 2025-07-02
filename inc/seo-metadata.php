<?php
/**
 * Basic SEO metadata generation.
 *
 * Outputs fundamental meta tags and structured data when no dedicated
 * SEO plugin is detected. Developers can override this behavior via the
 * `dadecore_skip_native_seo` filter or disable individual pieces of output
 * through both filters and options.
 *
 * Options are read from the `dadecore_options` option. Expected keys and
 * their boolean values are:
 * - `seo_meta_enabled` Enable `<title>` and description meta tags.
 * - `seo_open_graph`   Enable Open Graph tags.
 * - `seo_json_ld`      Enable JSON-LD schema output.
 *
 * Each section can be controlled via filters that receive a boolean value:
 * - `dadecore_enable_meta_tags`   Output `<title>` and description meta tags.
 * - `dadecore_enable_open_graph`  Output Open Graph tags.
 * - `dadecore_enable_schema`      Output JSON-LD schema.
 *
 * Metadata output is automatically disabled when Yoast SEO or RankMath is
 * detected unless these filters override the default behavior.
 *
 * @package DadeCore
 */

/**
 * Determine if native SEO output should run.
 *
 * The default behavior is to skip output when a well known SEO plugin such as
 * Yoast SEO or RankMath is active. Developers can force output or suppression
 * by filtering `dadecore_skip_native_seo`.
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
    $options = get_option( 'dadecore_options', array() );

    // Determine which sections should output based on options. Each option
    // stores a boolean value (1 to enable, 0 to disable).
    $meta_enabled    = ! empty( $options['seo_meta_enabled'] );
    $og_enabled      = ! empty( $options['seo_open_graph'] );
    $schema_enabled  = ! empty( $options['seo_json_ld'] );

    // Allow developers to toggle each block via filters. Each filter expects
    // a boolean and should return true to output its associated tags.
    $meta_enabled   = apply_filters( 'dadecore_enable_meta_tags', $meta_enabled );
    $og_enabled     = apply_filters( 'dadecore_enable_open_graph', $og_enabled );
    $schema_enabled = apply_filters( 'dadecore_enable_schema', $schema_enabled );

    global $post;

    /* -------------------------- <title> & description ----------------------- */
    if ( $meta_enabled ) {
        $default_title = isset( $options['seo_default_title'] ) ? $options['seo_default_title'] : '';
        $title = is_front_page() ? ( $default_title ? $default_title : get_bloginfo( 'name' ) ) : wp_get_document_title();
        echo '<title>' . esc_html( $title ) . "</title>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

        $description = '';
        if ( is_singular() ) {
            if ( has_excerpt( $post ) ) {
                $description = get_the_excerpt();
            } else {
                $description = wp_trim_words( wp_strip_all_tags( get_the_content() ), 55 );
            }
        } else {
            $default_desc = isset( $options['seo_default_description'] ) ? $options['seo_default_description'] : '';
            $description  = $default_desc ? $default_desc : get_bloginfo( 'description' );
        }

        echo '<meta name="description" content="' . esc_attr( $description ) . "">\n";
    }

    /* --------------------------- Open Graph tags --------------------------- */
    if ( $og_enabled ) {
        $default_title = isset( $options['seo_default_title'] ) ? $options['seo_default_title'] : '';
        $default_desc  = isset( $options['seo_default_description'] ) ? $options['seo_default_description'] : '';
        $og_title       = is_singular() ? get_the_title() : ( $default_title ? $default_title : get_bloginfo( 'name' ) );
        $og_description = is_singular() ? ( has_excerpt( $post ) ? get_the_excerpt() : wp_trim_words( wp_strip_all_tags( $post->post_content ), 55 ) ) : ( $default_desc ? $default_desc : get_bloginfo( 'description' ) );
        $og_url         = is_singular() ? get_permalink() : home_url();
        $og_image       = '';

        if ( is_singular() && has_post_thumbnail() ) {
            $og_image = get_the_post_thumbnail_url( null, 'full' );
        } else {
            $logo_id = isset( $options['seo_org_logo'] ) ? absint( $options['seo_org_logo'] ) : 0;
            if ( ! $logo_id ) {
                $logo_id = get_theme_mod( 'custom_logo' );
            }
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

    /* ----------------------------- JSON-LD schema --------------------------- */
    if ( $schema_enabled ) {
        // Organization schema.
        $org = array(
            '@context'    => 'https://schema.org',
            '@type'       => 'Organization',
            'url'         => home_url(),
            'name'        => isset( $options['seo_org_name'] ) && $options['seo_org_name'] ? $options['seo_org_name'] : get_bloginfo( 'name' ),
            'description' => isset( $options['seo_org_description'] ) ? $options['seo_org_description'] : '',
        );

        $logo_id = isset( $options['seo_org_logo'] ) ? absint( $options['seo_org_logo'] ) : 0;
        if ( ! $logo_id ) {
            $logo_id = get_theme_mod( 'custom_logo' );
        }
        if ( $logo_id ) {
            $logo = wp_get_attachment_image_src( $logo_id, 'full' );
            if ( $logo ) {
                $org['logo'] = $logo[0];
            }
        }
        if ( ! empty( $options['seo_org_contact'] ) ) {
            $org['contactPoint'] = array( array( '@type' => 'ContactPoint', 'contactType' => 'customer support', 'telephone' => $options['seo_org_contact'] ) );
        }
        if ( ! empty( $options['seo_org_social'] ) ) {
            $urls = array_filter( array_map( 'trim', explode( "\n", $options['seo_org_social'] ) ) );
            if ( $urls ) {
                $org['sameAs'] = array_map( 'esc_url', $urls );
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
            'serviceType' => isset( $options['seo_org_name'] ) && $options['seo_org_name'] ? $options['seo_org_name'] : get_bloginfo( 'name' ),
            'provider'    => array(
                '@type' => 'Organization',
                'name'  => isset( $options['seo_org_name'] ) && $options['seo_org_name'] ? $options['seo_org_name'] : get_bloginfo( 'name' ),
                'url'   => home_url(),
            ),
        );
        echo "<script type=\"application/ld+json\">\n" . wp_json_encode( $service ) . "\n</script>\n";
    }
}

?>
