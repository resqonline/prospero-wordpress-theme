# Prospero WordPress Theme

A modern, accessible WordPress theme with dark mode support, Gutenberg blocks, and extensive customization options. Built for performance, SEO, GDPR compliance, and European Accessibility Act standards.

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
- Button styles (Primary, Secondary, Tertiary) with outline and rounded corner options
- Sticky menu option
- Hamburger menu option (always visible or responsive)
- Google Fonts with local hosting support
- Product placement notice text for affiliate content
- Frontend login system toggle
- Custom CSS field
- Post type activation toggles

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

### Custom Gutenberg Blocks
- **Text Content Block** - Styled text with alignment and width options
- **Call to Action Block** - Headline, text, button, images, layout options
- **Affiliate Link Block** - Styled external links with disclosure notice
- **Member Content Block** - Role-based visibility for logged-in users
- **Testimonial Block** - Grid or slider display with Flickity integration
- **Partner Block** - Grid or slider display with Flickity integration
- **Team Block** - Grid/slider layouts with lightbox support for contact info

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

## Theme Structure

```
prospero-theme/
├── assets/
│   ├── css/
│   │   ├── main.css           # Main theme styles
│   │   ├── dark-mode.css      # Dark mode specific styles
│   │   ├── blocks.css         # Custom block styles
│   │   ├── shortcodes.css     # Shortcode styles
│   │   └── editor-style.css   # Gutenberg editor styles
│   ├── js/
│   │   ├── main.js            # Main JavaScript (menu, lightbox)
│   │   ├── dark-mode.js       # Dark mode functionality
│   │   ├── flickity-init.js   # Slider initialization
│   │   ├── faq-accordion.js   # FAQ accordion functionality
│   │   └── projects-filter.js # Ajax project filtering
│   ├── fonts/                 # Local fonts (auto-downloaded)
│   └── libs/
│       └── flickity/          # Flickity slider library
├── blocks/                    # Custom Gutenberg blocks
├── inc/
│   ├── ajax-filters.php       # Ajax filter handlers
│   ├── blocks.php             # Block registration
│   ├── customizer.php         # Theme Customizer settings
│   ├── frontend-login.php     # Frontend login system
│   ├── gutenberg.php          # Gutenberg configuration
│   ├── post-types.php         # Custom post types
│   ├── security.php           # Security functions
│   ├── seo.php                # SEO functions
│   ├── shortcodes.php         # Shortcode implementations
│   ├── template-functions.php # Template helper functions
│   ├── theme-updater.php      # GitHub auto-updates
│   ├── typography.php         # Google Fonts local hosting
│   └── lib/
│       └── plugin-update-checker/  # Update checker library
├── languages/                 # Translation files
├── template-parts/            # Reusable template components
├── functions.php              # Theme functions
├── header.php / footer.php    # Header and footer templates
├── index.php                  # Main template fallback
├── template-*.php             # Page templates
├── single-*.php               # Single post type templates
├── archive-*.php              # Archive templates
└── style.css                  # Theme stylesheet with header
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

### Post Types
Navigate to Appearance → Customize → Post Types to enable/disable:
- Testimonials
- Partners
- Team
- Projects

### Dark Mode
Navigate to Appearance → Customize → Dark Mode Settings:
- Enable/disable dark mode
- Set default mode (Light, Dark, or Auto based on system preference)

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
