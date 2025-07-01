<?php
/**
 * Basic security enhancements for Dadecore theme.
 */

// 1. Remove WordPress Version Information
// Helps obscure your WordPress version from potential attackers.
remove_action('wp_head', 'wp_generator'); // For front-end
add_filter('the_generator', '__return_false'); // For RSS feeds

// 2. Disable File Editing from WordPress Dashboard
// Prevents users (even admins) from editing theme/plugin files from Appearance > Editor or Plugins > Editor.
if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
    define( 'DISALLOW_FILE_EDIT', true );
}
// Note: DISALLOW_FILE_MODS also blocks updates and installations.
// define( 'DISALLOW_FILE_MODS', true ); // Uncomment if you want to block all file modifications including updates.

// 3. Add Security Headers via 'wp_headers' filter or 'send_headers' action
// Using 'send_headers' as it's called on every page load where headers are sent.
function dadecore_security_headers() {
    // X-Content-Type-Options: Prevents browsers from MIME-sniffing a response away from the declared content-type.
    header('X-Content-Type-Options: nosniff');

    // X-Frame-Options: Provides clickjacking protection.
    header('X-Frame-Options: SAMEORIGIN'); // Or DENY if you don't need to frame your site anywhere.

    // X-XSS-Protection: Enables the XSS Protections in browsers that support it.
    // '1; mode=block' tells the browser to block the page if it detects XSS, rather than trying to sanitize it.
    // Note: Many modern browsers have deprecated this header in favor of Content Security Policy.
    header('X-XSS-Protection: 1; mode=block');

    // Referrer-Policy: Controls how much referrer information the browser includes with navigations.
    header('Referrer-Policy: strict-origin-when-cross-origin');

    // Permissions-Policy (formerly Feature-Policy): Allows you to selectively enable/disable browser features.
    // Example: header('Permissions-Policy: geolocation=(self), microphone=()'); // Allow geolocation for own origin, deny microphone.
    // For a basic setup, we might not specify one, or a very restrictive one.
    // Let's start with a simple one, disallowing common potentially risky features by default unless specified.
    header('Permissions-Policy: accelerometer=(), ambient-light-sensor=(), autoplay=(), battery=(), camera=(), display-capture=(), document-domain=(), encrypted-media=(), execution-while-not-rendered=(), execution-while-out-of-viewport=(), fullscreen=(), geolocation=(), gyroscope=(), layout-animations=(), legacy-image-formats=(), magnetometer=(), microphone=(), midi=(), navigation-override=(), oversized-images=(), payment=(), picture-in-picture=(), publickey-credentials-get=(), sync-xhr=(), usb=(), vr=(), wake-lock=(), screen-wake-lock=(), xr-spatial-tracking=()');

    // Content Security Policy (CSP) - Use Report-Only to avoid breaking things initially.
    // A full CSP is complex and site-specific. This is a very basic example.
    // It's better to build this up carefully.
    // header("Content-Security-Policy-Report-Only: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; frame-src 'self';");
    // For now, let's keep it simple and focused on what was asked.
    // A very basic one:
    // header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'");
    // Given the scope, just adding the other headers is safer. If CSP is strictly needed, it requires more detail.
    // The user asked for "XSS, headers, etc.". X-XSS-Protection is a header. CSP is the more modern way.
    // Let's add a very simple CSP to show intent, but it would need significant tuning for a real site.
    // Using Report-Only to be safe.
    $csp_directives = [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval'", // 'unsafe-inline' and 'unsafe-eval' are often needed for WP plugins/themes
        "style-src 'self' 'unsafe-inline'", // 'unsafe-inline' for inline styles
        "img-src 'self' data: http: https:", // Allow images from self, data URIs, and http/https
        "font-src 'self' data: http: https:", // Allow fonts from self, data URIs, and http/https
        "connect-src 'self'",
        "frame-src 'self'", // Or specify domains if you embed content e.g. youtube.com
        // "report-uri /csp-violation-report-endpoint/" // You'd need an endpoint to collect reports
    ];
    // header("Content-Security-Policy-Report-Only: " . implode('; ', $csp_directives));
    // For now, let's stick to the more common, less likely to break headers as the primary implementation.
    // The request was "XSS, headers, etc.". The X-XSS-Protection header is added.
    // Other headers like X-Frame-Options, X-Content-Type-Options are standard.
}
add_action( 'send_headers', 'dadecore_security_headers' );

// 4. Further XSS Protection: Escape output.
// This is a principle, not a single setting. WordPress functions like esc_html(), esc_attr(), esc_js(), wp_kses_post() should be used throughout the theme.
// This file is for global settings, so we'll just note this principle.
// Example: echo esc_html( $user_input );

// 5. Protect WordPress configuration files and directories.
// This is typically done in .htaccess or nginx config, not in PHP.
// Example for .htaccess (place in root .htaccess, not theme):
/*
<Files wp-config.php>
order allow,deny
deny from all
</Files>
<Files .htaccess>
order allow,deny
deny from all
</Files>
<Files readme.html>
order allow,deny
deny from all
</Files>
# Disable directory browsing
Options -Indexes
*/

// Note: Some security plugins handle many of these features comprehensively.
// These are basic measures that can be implemented at the theme level.
?>
