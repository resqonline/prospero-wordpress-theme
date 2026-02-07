# Prospero WordPress Theme

A modern, accessible WordPress theme with dark mode support, custom Gutenberg blocks, and extensive customization options. Built for performance, SEO, GDPR compliance, and European Accessibility Act standards.

## Table of Contents

- [Features](#features)
- [Installation](#installation)
- [Theme Customization](#theme-customization)
- [Custom Post Types](#custom-post-types)
- [Gutenberg Blocks](#gutenberg-blocks)
- [Shortcodes](#shortcodes)
- [Page Templates](#page-templates)
- [Menus](#menus)
- [Frontend Login System](#frontend-login-system)
- [Typography](#typography)
- [Accessibility](#accessibility)
- [GDPR Compliance](#gdpr-compliance)
- [Security](#security)
- [Developer Notes](#developer-notes)

---

## Features

- **Dark Mode** - Automatic, light, or dark mode with user preference persistence
- **Custom Gutenberg Blocks** - Text Content, CTA, Affiliate Link, Member Content, Testimonial, Partner, and Team blocks
- **Custom Post Types** - Testimonials, Partners, Team Members, and Projects
- **Frontend Login System** - Complete user registration, login, and account management
- **Local Avatars** - GDPR-compliant avatar system (no Gravatar)
- **Local Google Fonts** - Automatic download and local hosting of fonts
- **Accessibility** - European Accessibility Act compliant
- **SEO Ready** - Schema.org markup and Open Graph tags
- **Security Hardened** - WordPress version hidden, XML-RPC disabled, REST API links removed

---

## Installation

1. Download or clone the theme to your `/wp-content/themes/` directory
2. Activate the theme in **Appearance > Themes**
3. Navigate to **Appearance > Customize** to configure theme options
4. Assign menus in **Appearance > Menus**

---

## Theme Customization

Access all theme settings via **Appearance > Customize**.

### Colors

| Setting | Description |
|---------|-------------|
| Primary Color | Main brand color used for buttons, links, accents |
| Secondary Color | Secondary brand color |
| Tertiary Color | Additional accent color |
| Text Color | Main text color (light mode) |
| Dark Mode Text | Text color for dark mode |
| Highlight Color | Color for highlighted elements |
| Background Color | Page background (light mode) |
| Dark Mode Background | Page background for dark mode |

### Dark Mode Settings

| Setting | Options | Description |
|---------|---------|-------------|
| Default Mode | Light / Dark / Auto | Initial mode for new visitors |

*Auto mode follows the user's system preference.*

### Button Styles

Configure the appearance of primary, secondary, and tertiary buttons:

- **Style**: Filled or Outline
- **Corners**: Rounded or Square

### Header Options

| Setting | Description |
|---------|-------------|
| Sticky Menu | Header stays fixed when scrolling |
| Hamburger Menu | Always show mobile-style hamburger menu |

### Typography

| Setting | Description |
|---------|-------------|
| Heading Font | Font family for headings (h1-h6) |
| Body Font | Font family for body text |

Select from popular Google Fonts or use system fonts. Selected fonts are automatically downloaded and hosted locally for GDPR compliance.

### Custom Post Types

Enable or disable each post type:

- **Enable Testimonials** - Customer/client testimonials
- **Enable Partners** - Partner/client logos
- **Enable Team** - Team member profiles
- **Enable Projects** - Portfolio projects

### Frontend Login

| Setting | Description |
|---------|-------------|
| Enable Frontend Login | Activates the complete frontend login system |

When enabled:
- Login/Logout links appear in the main menu
- Non-admin users are redirected to frontend pages
- WP login redirects to frontend `/login` page
- Required pages are created automatically (Login, Register, Forgot Password, My Account)

---

## Custom Post Types

### Testimonials

Display customer testimonials with author attribution.

**Fields:**
- Title (internal use)
- Content (testimonial text)
- Featured Image (author photo)
- Display Name (shown publicly)
- Testimonial Categories

**Usage:** Add via **Testimonials > Add New** or use the Testimonial block/shortcode.

### Partners

Showcase partner or client logos.

**Fields:**
- Title (partner name)
- Featured Image (logo)
- Partner Categories
- Menu Order (for custom sorting)

**Usage:** Add via **Partners > Add New** or use the Partner block/shortcode.

### Team Members

Display team member profiles with contact information.

**Fields:**
- Title (name)
- Content (biography)
- Featured Image (photo)
- Display Name (if different from title)
- Email Address
- Phone Number
- Social Links: LinkedIn, YouTube, Instagram, Facebook, Xing
- Team Categories
- Menu Order (for custom sorting)

**Usage:** Add via **Team > Add New** or use the Team block/shortcode.

### Projects

Portfolio projects with gallery support.

**Fields:**
- Title
- Content (project description)
- Featured Image
- Image Gallery (multiple images with drag-to-reorder)
- Associated Testimonial (select from existing testimonials)
- Project Tags
- Menu Order (for custom sorting)

**Usage:** Add via **Projects > Add New** or use the Projects template.

---

## Gutenberg Blocks

All custom blocks are found in the **Prospero** category in the block inserter.

### Text Content Block

A styled text block with customizable alignment and width.

**Settings:**
- Content alignment (left, center, right)
- Maximum width
- Padding options

### CTA Block (Call to Action)

Attention-grabbing call-to-action sections.

**Settings:**
- Heading text
- Description
- Button text and URL
- Button style (primary, secondary, tertiary)
- Background color
- Text alignment

### Affiliate Link Block

Styled affiliate/external links with disclosure.

**Settings:**
- Link text
- URL
- Disclosure text (for legal compliance)
- Opens in new tab option

### Member Content Block

Content visible only to logged-in users.

**Settings:**
- Message for non-logged-in users
- Login link text

*Content inside this block is only shown to authenticated users.*

### Testimonial Block

Display testimonials in various layouts.

**Settings:**
- Display mode: Single, Grid, or Slider
- Number of testimonials
- Specific category filter
- Show/hide author image
- Columns (for grid mode)

### Partner Block

Showcase partner logos.

**Settings:**
- Display mode: Grid or Slider
- Number of partners
- Category filter
- Columns
- Show/hide partner name

### Team Block

Display team member cards.

**Settings:**
- Display mode: Grid or Slider
- Number of members
- Category filter
- Columns
- Layout: Card or List
- Show contact info
- Show social links
- Enable lightbox popup

---

## Shortcodes

### Testimonials

```
[prospero_testimonials]
[prospero_testimonials count="3" category="featured" columns="3"]
[prospero_testimonials slider="true"]
```

**Parameters:**
- `count` - Number to display (default: -1 for all)
- `category` - Category slug to filter
- `columns` - Grid columns (default: 3)
- `slider` - Enable slider mode (true/false)

### Partners

```
[prospero_partners]
[prospero_partners count="6" columns="4"]
[prospero_partners slider="true"]
```

**Parameters:**
- `count` - Number to display
- `category` - Category slug to filter
- `columns` - Grid columns (default: 4)
- `slider` - Enable slider mode

### Team

```
[prospero_team]
[prospero_team count="4" columns="4" show_contact="true"]
[prospero_team layout="list" lightbox="true"]
```

**Parameters:**
- `count` - Number to display
- `category` - Category slug to filter
- `columns` - Grid columns (default: 3)
- `layout` - "card" or "list"
- `show_contact` - Show email/phone (true/false)
- `show_social` - Show social links (true/false)
- `lightbox` - Enable lightbox popup (true/false)
- `slider` - Enable slider mode

### Projects

```
[prospero_projects]
[prospero_projects count="6" columns="3"]
```

**Parameters:**
- `count` - Number to display
- `tag` - Tag slug to filter
- `columns` - Grid columns (default: 3)

### FAQ Accordion

```
[prospero_faq]
    [faq_item question="Your question here?"]
        Your answer content here.
    [/faq_item]
    [faq_item question="Another question?"]
        Another answer.
    [/faq_item]
[/prospero_faq]
```

### Login Forms (when frontend login enabled)

```
[prospero_login_form]
[prospero_register_form]
[prospero_forgot_password_form]
[prospero_my_account]
```

---

## Page Templates

### Startpage

**File:** `template-startpage.php`

A clean template for static home pages with an option to hide the page title.

### Blog

**File:** `template-blog.php`

Displays blog posts in a grid layout with pagination.

### Team

**File:** `template-team.php`

Displays all team members grouped by category. Members are sorted by menu order within each category.

### Projects

**File:** `template-projects.php`

Portfolio page with Ajax tag filtering. Displays projects in a grid with tag-based filtering without page reload.

### Login

**File:** `template-login.php`

Frontend login page. Redirects logged-in users to My Account.

### Register

**File:** `template-register.php`

User registration with GDPR terms acceptance.

### Forgot Password

**File:** `template-forgot-password.php`

Password reset request and new password form.

### My Account

**File:** `template-my-account.php`

User account dashboard with:
- Account overview
- Profile editing (including avatar upload)
- Password change

### Logged In

**File:** `template-logged-in.php`

Template for member-only content pages. Non-authenticated users are redirected to login.

---

## Menus

Register and assign menus in **Appearance > Menus**.

### Main Menu

Location: `main-menu`

Primary site navigation displayed in the header. When frontend login is enabled, login/logout items are automatically added.

### Footer Menu

Location: `footer-menu`

Navigation links displayed in the footer.

### Social Menu

Location: `social-menu`

Social media links displayed in the footer. Add custom links to your social profiles. *Note: Icon font integration pending.*

### Logged In Menu

Location: `logged-in-menu`

Navigation for logged-in users. Displayed on account and member pages.

**Default items (when no menu assigned):**
- Account Overview
- Edit Profile
- Change Password
- Logout

---

## Frontend Login System

Enable via **Appearance > Customize > Frontend Login**.

### Features

When enabled, the system provides:

1. **Automatic Page Creation** - Login, Register, Forgot Password, and My Account pages are created automatically

2. **Menu Integration** - Login/Logout links added to main menu with user dropdown showing:
   - User's display name
   - My Account link
   - Logout link

3. **WP Login Redirect** - Standard WordPress login redirects to frontend `/login` page (except for admins and special actions)

4. **Non-Admin Redirect** - Users without editor capabilities are redirected to frontend after login

5. **Admin Bar Hidden** - Hidden for non-admin users

### Account Features

The My Account page includes:

- **Account Overview** - Display name, email, member since date
- **Avatar Upload** - Local avatar upload (GDPR-compliant, no Gravatar)
- **Profile Editing** - First name, last name, display name, email, bio
- **Password Change** - Secure password update with current password verification

### Avatar System

- Gravatar is completely disabled for GDPR compliance
- Users upload avatars locally via My Account
- Supported formats: JPG, PNG, GIF, WebP
- Maximum file size: 2MB
- Old avatars are automatically deleted when replaced

---

## Typography

### Local Google Fonts

The theme automatically downloads and hosts Google Fonts locally for GDPR compliance.

**How it works:**
1. Select a font in Customizer (Heading Font or Body Font)
2. On first page load, font files are downloaded from Google
3. CSS is rewritten with local URLs
4. Fonts are served from your server (no external requests)

**Font weights included:** 300, 400, 500, 600, 700

### System Fonts

Select "System" to use the visitor's native system font stack (no download required).

---

## Accessibility

The theme follows European Accessibility Act standards:

- **Skip Links** - "Skip to content" link for keyboard users
- **ARIA Labels** - Proper labels on interactive elements
- **Keyboard Navigation** - Full keyboard support for menus and interactions
- **Focus Indicators** - Visible focus states
- **Color Contrast** - WCAG AA compliant contrast ratios
- **Screen Reader Support** - Hidden text for context where needed
- **Semantic HTML** - Proper heading hierarchy and landmark regions

---

## GDPR Compliance

### Built-in Protections

- **No External Fonts** - Google Fonts downloaded and hosted locally
- **No Gravatar** - Local avatar system, no requests to Gravatar servers
- **No External CDNs** - All libraries included locally
- **No Tracking** - No analytics or tracking built into the theme

### Registration Terms

The registration form includes a required terms acceptance checkbox. Link this to your Privacy Policy and Terms of Service pages.

### Recommended Plugins

- **Simple Local Avatars** - Alternative avatar plugin (theme has built-in support)
- **Complianz** - Cookie consent management
- **WP GDPR Compliance** - Additional GDPR tools

---

## Security

### Built-in Hardening

The theme includes security measures in `inc/security.php`:

- WordPress version removed from HTML output
- XML-RPC disabled (prevents brute force attacks)
- REST API links removed from head
- RSD and wlwmanifest links removed
- oEmbed discovery links removed

### Form Security

All forms use:
- WordPress nonces for CSRF protection
- Input sanitization
- Output escaping
- Prepared database queries (`$wpdb->prepare`)

### Password Requirements

- Minimum 8 characters
- Current password verification for changes

---

## Developer Notes

### Theme Structure

```
prospero-theme/
├── assets/
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   ├── fonts/         # Local font files (auto-generated)
│   └── libs/          # Third-party libraries (Flickity)
├── blocks/            # Custom Gutenberg blocks
├── inc/               # PHP functionality modules
│   ├── ajax-filters.php
│   ├── blocks.php
│   ├── customizer.php
│   ├── frontend-login.php
│   ├── gutenberg.php
│   ├── post-types.php
│   ├── security.php
│   ├── seo.php
│   ├── shortcodes.php
│   ├── template-functions.php
│   └── typography.php
├── template-parts/    # Reusable template components
├── languages/         # Translation files
└── functions.php      # Main theme file
```

### Naming Conventions

- **PHP Functions:** `prospero_` prefix with snake_case
- **CSS Classes:** kebab-case
- **JavaScript:** camelCase
- **Post Types:** singular lowercase
- **Meta Keys:** `_prospero_` prefix

### Template Parts

Reusable components in `template-parts/`:

- `content-testimonial.php`
- `content-partner.php`
- `content-team.php`
- `content-project.php`

### Hooks and Filters

The theme uses standard WordPress hooks. Key custom filters:

- `pre_get_avatar_data` - Local avatar integration
- `get_avatar` - Avatar URL replacement
- `wp_nav_menu_items` - Login menu integration
- `login_redirect` - Frontend redirect

### Translation

Text domain: `prospero-theme`

All strings use WordPress translation functions:
- `esc_html__()`
- `esc_attr__()`
- `esc_html_e()`
- `_n()` for plurals

### CSS Variables

The theme uses CSS custom properties for colors:

```css
--color-primary
--color-secondary
--color-tertiary
--color-text
--color-text-dark
--color-highlight
--color-background
--color-background-dark
```

---

## Changelog

### Version 1.0.0

- Initial release
- Dark mode support
- Custom Gutenberg blocks
- Custom post types (Testimonials, Partners, Team, Projects)
- Frontend login system
- Local avatar support
- Local Google Fonts
- Accessibility features
- Security hardening

---

## Credits

- **Flickity** - Carousel library (GPL v3)
- **Google Fonts** - Typography (SIL Open Font License)

---

## License

This theme is licensed under the GPL v2 or later.

---

## Support

For issues and feature requests, please contact the theme developer.
