# Prospero Child Theme

A starter child theme for the Prospero WordPress theme.

## Installation

1. Make sure the parent theme `prospero-theme` is installed in `/wp-content/themes/`
2. Copy this `prospero-theme-child` folder to `/wp-content/themes/`
3. Activate "Prospero Child" in Appearance > Themes

## File Structure

```
prospero-theme-child/
├── style.css           # Required: Theme header + custom CSS
├── functions.php       # Optional: Custom PHP functions
├── assets/
│   ├── css/
│   │   └── custom.css  # Optional: Additional CSS (auto-loaded)
│   └── js/
│       └── custom.js   # Optional: Additional JS (auto-loaded)
├── languages/          # Optional: Translation files
└── README.md
```

## Customization Options

### CSS Overrides

Add your custom styles to `style.css`. They will load after all parent theme styles.

For larger style modifications, create `assets/css/custom.css` - it will be automatically enqueued after `style.css`.

**Available CSS custom properties:**

```css
:root {
    --color-primary: /* from Customizer */;
    --color-secondary: /* from Customizer */;
    --color-tertiary: /* from Customizer */;
    --color-text: /* from Customizer */;
    --color-text-dark: /* from Customizer */;
    --color-highlight: /* from Customizer */;
    --color-background: /* from Customizer */;
    --color-background-dark: /* from Customizer */;
    --content-width: /* from Customizer */;
    --border-radius: 4px;
    --spacing-xs: 0.5rem;
    --spacing-sm: 1rem;
    --spacing-md: 1.5rem;
    --spacing-lg: 2rem;
    --spacing-xl: 3rem;
}
```

### JavaScript

Create `assets/js/custom.js` for custom JavaScript. It will be automatically enqueued with `prospero-main` as a dependency.

For more control, use the `prospero_after_enqueue_scripts` action in `functions.php`.

### Template Overrides

Copy any template file from the parent theme to your child theme to override it:

- `header.php`, `footer.php` - Site header/footer
- `page.php` - Page template
- `single.php` - Single post template
- `archive.php`, `home.php` - Archive/blog templates
- `template-parts/*.php` - Reusable template parts

WordPress will automatically use the child theme's version.

### PHP Functions

Add custom functionality in `functions.php`. The file includes examples for:

- Adding theme support
- Registering custom post types
- Adding Customizer settings
- Modifying parent theme behavior with filters

### Hooks Available

**Actions:**
- `prospero_after_enqueue_scripts` - Fires after all parent scripts/styles are loaded

**Filters:**
- `prospero_content_width` - Modify the content width value

### Constants

These constants are available in your child theme:

```php
PROSPERO_VERSION       // Parent theme version
PROSPERO_THEME_DIR     // Parent theme directory path
PROSPERO_THEME_URI     // Parent theme directory URI
PROSPERO_CHILD_DIR     // Child theme directory path
PROSPERO_CHILD_URI     // Child theme directory URI
PROSPERO_IS_CHILD_THEME // true when child theme is active
```

## Dequeuing Parent Styles/Scripts

To remove a parent script or style:

```php
function my_dequeue_scripts() {
    wp_dequeue_style( 'prospero-shortcodes' );
    wp_dequeue_script( 'prospero-lightbox' );
}
add_action( 'prospero_after_enqueue_scripts', 'my_dequeue_scripts' );
```

## Translations

1. Create a `/languages/` folder in your child theme
2. Use Poedit or similar to create translation files
3. Name them: `prospero-theme-child-{locale}.po/.mo` (e.g., `prospero-theme-child-de_DE.po`)

## Support

For issues with the parent theme, please refer to the main Prospero theme documentation.

For child theme customization help, consult the [WordPress Child Themes documentation](https://developer.wordpress.org/themes/advanced-topics/child-themes/).
