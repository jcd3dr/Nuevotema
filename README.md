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
