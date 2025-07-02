# DadeCore WordPress Theme

DadeCore is a corporate base theme optimized for modern WordPress development. It supports the Gutenberg block editor and popular builders like Elementor.

## Installation

1. Clone or copy the theme into your WordPress `wp-content/themes` directory:
   ```bash
   git clone <repo-url> dadecore
   ```
2. Log into your WordPress admin dashboard and activate the **DadeCore Theme** from the *Appearance → Themes* menu.

### Build Requirements

The theme styles are written in SCSS. You can compile them with any Sass compiler. For example:

```bash
sass assets/css/style.scss assets/css/style.css
```

Make sure the generated CSS files are available in the theme before activating it.

## Usage Notes

- Compatible with Gutenberg blocks and Elementor widgets out of the box.
- Customize templates inside the `template-parts` directory or modify SCSS files under `assets/css`.
- JavaScript enhancements can be added in `assets/js/main.js`.

Feel free to adapt the theme to your needs and extend it with additional features.

## Security Features

This theme ships with a few basic security improvements:

- A filter that changes the login URL slug (default `/login/`).
- Simple rate limiting for failed login attempts.
- Automatic security headers (`X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`).

You can adjust the login slug, the allowed attempts and the lockout duration under **Appearance → DadeCore Options → Login Security**. The login attempt limiter is disabled when a security plugin such as Wordfence is detected so there are no hook conflicts.

## SEO & Metadata Settings

The theme includes a built‑in SEO panel found under **Appearance → DadeCore Options → SEO & Metadata Settings**. Use the checkboxes to toggle:

- Meta tags for the `<title>` element and page description.
- Open Graph tags for improved social sharing.
- JSON‑LD structured data.

These features are disabled automatically whenever a major SEO plugin (such as Yoast SEO or RankMath) is detected, preventing duplicate metadata output.
