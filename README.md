# Prospero WordPress Theme

A modern, accessible WordPress theme with dark mode support, Gutenberg blocks, and extensive customization options. Built for performance, SEO, GDPR compliance, and European Accessibility Act standards.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Theme Structure](#theme-structure)
- [Customization](#customization)
- [Browser Support](#browser-support)
- [Accessibility](#accessibility)
- [GDPR Compliance](#gdpr-compliance)
- [Security](#security)
- [Credits](#credits)
- [License](#license)
- [Support](#support)
- [Changelog](#changelog)
- [Author](#author)

## Features

### Core Features
- **Dark Mode / Light Mode**: Automatic, light, or dark mode with CSS3 animations and user preference storage
- **Fully Responsive**: Mobile-first design that works on all devices
- **Gutenberg Ready**: Full block editor support with custom blocks
- **Accessibility Ready**: European Accessibility Act compliant with ARIA labels, keyboard navigation, and skip links
- **SEO Optimized**: Structured data, Open Graph meta tags, and proper heading hierarchy
- **GDPR Compliant**: Local font hosting, no external dependencies, privacy-friendly features
- **Security Focused**: Uses `$wpdb->prepare` for queries, nonce verification, input sanitization

### Customizer Options
- Custom logo with fallback to default `logo.svg`
- Color scheme customization:
	- Primary, Secondary, Tertiary colors
	- Text color / Dark mode text color
	- Highlight color
	- Background / Dark mode background colors
- Button styles (Primary, Secondary, Tertiary) with outline, rounded corner, and uppercase options
- Menu CTA Button styling (independent controls, separate from content buttons)
- Top Bar with phone and email contact links (sticky, icon-only on mobile)
- Sticky menu option
- Hamburger menu option (always visible or responsive)
- Google Fonts with local hosting support
- Product placement notice text for affiliate content
- Frontend login system toggle
- Custom CSS field
- Post type activation toggles (Testimonials, Partners, Team, Projects, FAQs)
- FAQ archive title and description

### Custom Post Types
All post types can be enabled/disabled via Customizer:

1. **Testimonials**
	- noindex, nofollow for SEO
	- No single post template or archive
	- Custom taxonomy: Testimonial Categories
	- Fields: Title, Content, Name, Thumbnail

2. **Partners**
	- noindex, nofollow for SEO
	- Custom taxonomy: Partner Categories
	- Fields: Title, Website Link, Logo Image, Content
	- Custom order support

3. **Team**
	- Custom taxonomy: Team Categories
	- Fields: Title, Name, Position, Thumbnail, Secondary Thumbnail (hover)
	- Custom order support
	- Multiple layout options

4. **Projects**
	- Custom taxonomy: Project Tags
	- Fields: Title, Content, Image Gallery, Project Website, Testimonial link
	- Custom order support

5. **FAQs**
	- Archive, taxonomy and single templates with accordion display
	- Schema.org FAQPage markup
	- Custom taxonomy: FAQ Categories (with per-category hide-from-all-view toggle)
	- Fields: Title (question), Content (answer)
	- Customizer: FAQ Archive Title and FAQ Archive Description

### Custom Gutenberg Blocks
- **Text Content Block** - Styled text with alignment and width options
- **Call to Action Block** - Headline, text, button, images, layout options
- **Affiliate Link Block** - Styled external links with disclosure notice
- **Member Content Block** - Role-based visibility for logged-in users
- **Testimonial Block** - Grid or slider display with Flickity integration
- **Partner Block** - Grid or slider display with Flickity integration
- **Partner Single Block** - Single partner with logo/content side-by-side layout, optional visit-link button
- **Team Block** - Grid/slider layouts with lightbox support for contact info
- **FAQ List Block** - FAQ CPT content as a click-to-expand accordion, auto-grouped by category

### Shortcodes
- `[testimonials category="" slider="yes"]` - Display testimonials
- `[partners category="" slider="yes"]` - Display partners
- `[team category="" layout="grid"]` - Display team members
- `[projects tags="" ajax_filter="yes"]` - Display projects with filtering

### Page Templates
- **Startpage** - Static home page template
- **Blog** - Blog listing page
- **Team** - Team members display
- **Projects** - Project portfolio with Ajax filtering
- **Login** - Frontend login form
- **Register** - Frontend registration form
- **Forgot Password** - Password reset form
- **My Account** - User profile and account management
- **Logged In** - Restricted content for members

### Menu Locations
- Main Menu
- Footer Menu
- Social Menu (with icon display)
- Logged In Menu

### Frontend Login System
When enabled in Customizer:
- Custom login/logout menu items
- Frontend login forms
- User account page with profile editing
- Integration with Simple Local Avatars plugin (disables Gravatar)
- Non-admin users redirected to frontend after login
- Admin bar hidden for non-admins

### Additional Features
- Custom logo on WP login screen
- Product placement notices for posts with affiliate links
- Social menu with icon support
- Flickity slider library (locally hosted for GDPR)

## Installation

### From GitHub
1. Download or clone this repository
2. Copy the `prospero-theme` folder to your WordPress installation's `/wp-content/themes/` directory
3. Activate the theme through the WordPress admin panel
4. Customize the theme via Appearance → Customize

### Automatic Updates
The theme includes automatic update functionality via GitHub releases. When a new version is released, you'll see an update notification in WordPress just like themes from WordPress.org.

### Child Theme
A starter child theme is included in the `prospero-theme-child/` directory:
1. Copy `prospero-theme-child/` to your WordPress themes directory alongside the parent theme
2. Activate the child theme in Appearance → Themes
3. Add custom CSS in `assets/css/custom.css` and JavaScript in `assets/js/custom.js` — both are auto-loaded by the parent
4. Override any parent template by copying it to the same relative path in the child theme

## Theme Structure

The repository contains the parent theme and a starter child theme:

```
prospero-wordpress-theme/
├── prospero-theme/                  # Parent theme
│   ├── archive-faq.php              # FAQ archive template
│   ├── single-faq.php               # Single FAQ template
│   ├── single-team.php              # Single team member template
│   ├── taxonomy-faq_category.php    # FAQ category template
│   ├── assets/
│   │   ├── css/
│   │   │   ├── main.css             # Main theme styles
│   │   │   ├── dark-mode.css        # Dark mode styles
│   │   │   ├── blocks.css           # Custom block styles
│   │   │   ├── shortcodes.css       # Shortcode styles
│   │   │   ├── editor-style.css     # Gutenberg editor styles
│   │   │   └── admin-nav-menu.css   # Menu editor styles
│   │   ├── js/
│   │   │   ├── main.js              # Main JavaScript (menu, sticky header)
│   │   │   ├── dark-mode.js         # Dark mode functionality
│   │   │   ├── flickity-init.js     # Slider initialization
│   │   │   ├── faq-accordion.js     # FAQ accordion functionality
│   │   │   ├── blog-filter.js       # Blog AJAX filter
│   │   │   └── projects-filter.js   # Ajax project filtering
│   │   ├── fonts/                   # Local fonts (auto-downloaded)
│   │   └── libs/
│   │       └── flickity/            # Flickity slider library
│   ├── blocks/                      # Custom Gutenberg blocks
│   ├── inc/
│   │   ├── ajax-filters.php         # Ajax filter handlers
│   │   ├── blocks.php               # Block registration
│   │   ├── customizer.php           # Theme Customizer settings
│   │   ├── faqs.php                 # FAQ post type and taxonomy
│   │   ├── frontend-login.php       # Frontend login system
│   │   ├── gutenberg.php            # Gutenberg configuration
│   │   ├── nav-menu.php             # Menu CTA button field
│   │   ├── post-types.php           # Custom post types
│   │   ├── security.php             # Security functions
│   │   ├── seo.php                  # SEO functions
│   │   ├── shortcodes.php           # Shortcode implementations
│   │   ├── template-functions.php   # Template helper functions
│   │   ├── theme-updater.php        # GitHub auto-updates
│   │   ├── typography.php           # Google Fonts local hosting
│   │   └── lib/
│   │       └── plugin-update-checker/  # Update checker library
│   ├── languages/                   # Translation files
│   ├── template-parts/              # Reusable template components
│   ├── functions.php                # Theme functions
│   ├── header.php / footer.php      # Header and footer templates
│   ├── index.php                    # Main template fallback
│   ├── template-*.php               # Page templates
│   └── style.css                    # Theme stylesheet with header
└── prospero-theme-child/            # Starter child theme
    ├── assets/
    │   ├── css/custom.css           # Custom styles (auto-loaded)
    │   └── js/custom.js             # Custom scripts (auto-loaded)
    ├── functions.php                # Child theme functions
    └── style.css                    # Child theme header + custom CSS
```

## Customization

### Colors
Navigate to Appearance → Customize → Theme Colors to customize:
- Primary, Secondary, Tertiary colors
- Text and background colors for light and dark modes
- Highlight color

### Typography
Navigate to Appearance → Customize → Typography:
- Choose Google Fonts (will be downloaded and hosted locally)
- Or use system fonts for better performance

### Top Bar
Navigate to Appearance → Customize → Top Bar:
- Enable/disable the contact bar above the header
- Phone number with optional prefix label (e.g. "Call us:")
- Email address with optional prefix label (GDPR-protected)

### Post Types
Navigate to Appearance → Customize → Post Types to enable/disable:
- Testimonials, Partners, Team, Projects, FAQs
- FAQ archive title and description (when FAQs are enabled)

### Dark Mode
Navigate to Appearance → Customize → Dark Mode Settings:
- Enable/disable dark mode
- Set default mode (Light, Dark, or Auto based on system preference)

### Menu CTA Buttons
In Appearance → Menus, check **Display as CTA button** on any menu item to style it as a call-to-action. Configure appearance independently in Appearance → Customize → Button Styles → Menu CTA Button.

## Browser Support

- Chrome (last 2 versions)
- Firefox (last 2 versions)
- Safari (last 2 versions)
- Edge (last 2 versions)

## Accessibility

This theme follows the European Accessibility Act standards and WCAG 2.1 Level AA guidelines:
- Proper color contrast ratios
- Keyboard navigation support
- ARIA labels where appropriate
- Skip links for screen readers
- Focus indicators
- Responsive text sizing

## GDPR Compliance

- All fonts and libraries hosted locally
- No external requests without user consent
- Privacy-friendly default settings
- User data handling according to EU/German/Austrian regulations

## Security

- Uses `$wpdb->prepare` for all database queries
- Nonce verification for forms
- Input sanitization and output escaping
- Capability checks for admin functions
- XML-RPC disabled
- WordPress version information removed

## Credits

### Libraries & Resources
- **Flickity** (GPL v3) - https://flickity.metafizzy.co/ - Image carousels and sliders
- **Plugin Update Checker** (MIT) - https://github.com/YahnisElsts/plugin-update-checker - GitHub auto-updates
- **Google Fonts** (SIL Open Font License) - Downloaded and hosted locally for GDPR compliance

## License

GNU General Public License v2 or later
http://www.gnu.org/licenses/gpl-2.0.html

## Support

For support, please open an issue in the GitHub repository.

## Changelog

### Version 1.1.0
- FAQ post type with archive, taxonomy and single templates, Schema.org FAQPage markup, and accordion display
- Top Bar with phone/email contact links (sticky, icon-only on mobile)
- Menu CTA Button: per-item toggle in Appearance → Menus, dedicated Customizer styling controls
- Partner Single Block: logo + content side-by-side, logo position, optional visit-link button
- FAQ List Block: FAQ CPT content as a click-to-expand accordion, auto-grouped by category
- Button overhaul: all styles driven by CSS custom properties from Customizer (flat/outline, radius, color, uppercase)
- Global form field styling with CSS variables, dark mode, and Customizer color support
- Blog AJAX filter fixes: shared template part, sticky post handling, scoped excerpt length
- Testimonial star ratings via CSS mask-image for consistent glyph metrics
- Footer, sticky header, and search overlay backgrounds derived from Customizer colors
- Button size hierarchy: primary > secondary > tertiary
- Child theme scaffolding: `assets/css/custom.css` and `assets/js/custom.js` with usage documentation
- Optional WCAG AA button contrast enforcement (Button Styles → Accessibility)

### Version 1.0.0
- Complete theme structure and architecture
- Full Customizer integration (colors, typography, layout, post types)
- Dark mode / Light mode with preference storage
- Four custom post types: Testimonials, Partners, Team, Projects
- Seven custom Gutenberg blocks
- All shortcodes for custom post types
- Nine page templates including full frontend login system
- Flickity slider integration
- Google Fonts local hosting (GDPR compliant)
- Team member lightbox functionality
- Ajax project filtering
- GitHub auto-update system
- Accessibility features (European Accessibility Act compliant)
- SEO optimizations (Schema.org, Open Graph)
- Security hardening

## Author

resQ online e.U.
https://www.resqonline.eu
