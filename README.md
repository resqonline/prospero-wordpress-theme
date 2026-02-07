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

### Custom Gutenberg Blocks (TODO)
- Text Content Block
- Call to Action Block (headline, text, button, images, layout options)
- Affiliate Link Block (for scripts from affiliate programs)
- Member Content Block (role-based visibility)
- Testimonial Block (single/multiple, optional Flickity slider)
- Partner Block (single/multiple, optional Flickity slider)
- Team Block (multiple layouts: columns, list, grid, lightbox support)

### Shortcodes (TODO)
- `[testimonials category="" slider="yes"]`
- `[partners category="" slider="yes"]`
- `[team category="" layout="grid"]`
- `[projects tags="" ajax_filter="yes"]`

### Page Templates (TODO)
- Startpage (static home page)
- Blog page
- Team page
- My Account (frontend login)
- Register (frontend login)
- Forgot Password (frontend login)
- Logged In page (for restricted content)
- Project page (with Ajax filtering)

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

1. Download or clone this repository
2. Copy the `prospero-theme` folder to your WordPress installation's `/wp-content/themes/` directory
3. Activate the theme through the WordPress admin panel
4. Customize the theme via Appearance → Customize

## Development Status

### Completed
✅ Core theme structure
✅ Customizer with all settings
✅ Dark mode / Light mode functionality
✅ Custom post types (Testimonials, Partners, Team, Projects)
✅ Responsive design with CSS3 animations
✅ Accessibility features (skip links, ARIA labels, keyboard navigation)
✅ SEO optimizations (structured data, Open Graph tags)
✅ Security measures
✅ Frontend login system basics
✅ Menu locations
✅ Basic template files

### TODO
- [ ] Custom Gutenberg blocks implementation
- [ ] Flickity library integration
- [ ] Complete shortcode implementations
- [ ] Page templates (Startpage, Account, Register, etc.)
- [ ] Ajax filtering for projects
- [ ] Google Fonts local hosting implementation
- [ ] Button style customizations in CSS
- [ ] Complete frontend login pages
- [ ] Team secondary image upload functionality
- [ ] Lightbox implementation for team members
- [ ] Complete testing and refinement

## Theme Structure

```
prospero-theme/
├── assets/
│   ├── css/
│   │   ├── main.css           # Main theme styles
│   │   ├── dark-mode.css      # Dark mode specific styles
│   │   └── editor-style.css   # Gutenberg editor styles
│   ├── js/
│   │   ├── main.js            # Main JavaScript
│   │   └── dark-mode.js       # Dark mode functionality
│   └── fonts/                 # Local fonts directory
├── blocks/                    # Custom Gutenberg blocks
├── inc/
│   ├── customizer.php         # Theme Customizer settings
│   ├── post-types.php         # Custom post types
│   ├── template-functions.php # Template helper functions
│   ├── gutenberg.php          # Gutenberg configuration
│   ├── shortcodes.php         # Shortcode implementations
│   ├── frontend-login.php     # Frontend login system
│   ├── security.php           # Security functions
│   └── seo.php                # SEO functions
├── languages/                 # Translation files
├── template-parts/
│   ├── content.php            # Default post content template
│   └── content-none.php       # No content found template
├── functions.php              # Theme functions
├── header.php                 # Header template
├── footer.php                 # Footer template
├── index.php                  # Main template
├── style.css                  # Theme stylesheet with header
└── logo.svg                   # Default logo file
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

## WordPress.org Submission Guidelines

This theme is being developed with WordPress.org theme directory submission in mind. To prepare for submission:

1. Complete all TODO items
2. Test with Theme Check plugin
3. Test with Theme Unit Test data
4. Ensure all strings are translatable
5. Create proper screenshot.png (1200x900px)
6. Review WordPress Theme Review requirements
7. Test with accessibility tools
8. Validate HTML/CSS
9. Test across different browsers

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
- Flickity (to be included) - https://flickity.metafizzy.co/
- System fonts for performance

### Inspiration
Based on user's previous theme work and requirements from past projects including:
- austrian arts sessions theme
- BWG theme
- ESI theme
- And other custom client themes

## License

GNU General Public License v2 or later
http://www.gnu.org/licenses/gpl-2.0.html

## Support

For support, please open an issue in the GitHub repository.

## Changelog

### Version 1.0.0 (Initial Development)
- Initial theme structure
- Customizer settings
- Custom post types
- Dark mode functionality
- Responsive design
- Accessibility features
- SEO optimizations
- Security implementations

## Author

resQ online e.U.
https://www.resqonline.eu
