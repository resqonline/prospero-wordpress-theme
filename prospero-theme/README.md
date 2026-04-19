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
- **Custom Post Types** - Testimonials, Partners, Team Members, Projects, and FAQs
- **FAQ System** - Accordion templates with category grouping, hide-from-view toggle, and Schema.org FAQPage markup
- **Top Bar** - Optional sticky contact bar above the header with phone and email links
- **Menu CTA Buttons** - Highlight any menu item as a styled call-to-action button with independent Customizer controls
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

A dedicated **Menu CTA Button** subsection controls CTA button appearance independently:

- **Style**: Filled or Outline
- **Corners**: Rounded or Square
- **Colors**: Background, text, hover background, hover text
- **Font Style**: Normal or Uppercase

**Accessibility** subsection: *Enforce WCAG AA button contrast (3:1)* checkbox — off by default (1.5:1 fallback threshold). Enable for strict WCAG 2.1 / EAA compliance.

### Header Options

| Setting | Description |
|---------|-------------|
| Sticky Menu | Header stays fixed when scrolling |
| Hamburger Menu | Always show mobile-style hamburger menu |

### Top Bar

| Setting | Description |
|---------|-------------|
| Enable Top Bar | Shows a contact bar above the site header |
| Phone Prefix | Label shown before the phone number (e.g. "Call us:") |
| Phone Number | Phone number — rendered as a clean `tel:` link |
| Email Prefix | Label shown before the email address |
| Email Address | Email address — GDPR-protected via `antispambot()` |

*On mobile (≤ 767 px) only the icons are shown; prefix and value are visually hidden but remain screen-reader accessible. When the sticky header is active, the top bar pins to the top of the viewport and the header shifts down accordingly.*

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
- **Enable FAQs** - Frequently asked questions with category support

Additional FAQ settings (visible when FAQs are enabled):

- **FAQ Archive Title** - Custom heading for the FAQ archive page (defaults to "Frequently Asked Questions")
- **FAQ Archive Description** - Optional intro paragraph shown above the accordion

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

### FAQs

Frequently asked questions displayed as an accessible accordion.

**Fields:**
- Title (the question)
- Content (the answer)
- FAQ Categories

**Templates:**
- Archive: `/faq/` — all FAQs grouped by category with Schema.org `FAQPage` markup
- Taxonomy: `/faq-category/[slug]/` — FAQs filtered to one category
- Single: `/faq/[slug]/` — single FAQ, pre-expanded, with category chips and a back link

**Per-category option:** A *Hide from all-FAQs view* toggle on each `faq_category` term excludes that category's FAQs from the main archive while keeping the taxonomy page accessible.

**Usage:** Add via **FAQs > Add New**. Use the FAQ List block for dynamic output or the `[prospero_faq]` shortcode for manual accordions in page content.

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

### Partner Single Block

Display a single partner with logo and description in a side-by-side layout.

**Settings:**
- Select partner
- Logo position (left or right)
- Show "Visit website" button
- Visit link text and button style (primary, secondary, tertiary)

### FAQ List Block

Display FAQ items from the FAQ post type as a click-to-expand accordion.

**Settings:**
- Category filter (blank = all categories, auto-grouped by term)
- Number of FAQs

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

### Menu CTA Buttons

Any menu item can be displayed as a styled call-to-action button. Enable the **Display as CTA button** checkbox on the individual menu item in **Appearance > Menus**.

CTA button appearance is controlled separately under **Appearance > Customize > Button Styles > Menu CTA Button**, independent of the primary/secondary/tertiary content buttons.

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
├── archive-faq.php          # FAQ archive (grouped accordion, FAQPage schema)
├── single-faq.php           # Single FAQ (pre-expanded, category chips)
├── single-team.php          # Single team member detail
├── taxonomy-faq_category.php  # FAQ category taxonomy template
├── assets/
│   ├── css/                 # Stylesheets
│   │   └── admin-nav-menu.css  # Menu editor: CTA button field styling
│   ├── js/                  # JavaScript files
│   ├── fonts/               # Local font files (auto-generated)
│   └── libs/                # Third-party libraries (Flickity)
├── blocks/                  # Custom Gutenberg blocks
├── inc/                     # PHP functionality modules
│   ├── ajax-filters.php
│   ├── blocks.php
│   ├── customizer.php
│   ├── faqs.php             # FAQ CPT, faq_category taxonomy, hide-from-all toggle
│   ├── frontend-login.php
│   ├── gutenberg.php
│   ├── nav-menu.php         # Menu CTA button field + FAQ archive nav meta box
│   ├── post-types.php
│   ├── security.php
│   ├── seo.php
│   ├── shortcodes.php
│   ├── template-functions.php
│   └── typography.php
├── template-parts/          # Reusable template components
├── languages/               # Translation files
└── functions.php            # Main theme file
```

### Naming Conventions

- **PHP Functions:** `prospero_` prefix with snake_case
- **CSS Classes:** kebab-case
- **JavaScript:** camelCase
- **Post Types:** singular lowercase
- **Meta Keys:** `_prospero_` prefix

### Template Parts

Reusable components in `template-parts/`:

- `content-blog-item.php` - Single blog post card (shared by main loop and AJAX filter)
- `content-testimonial.php` - Testimonial card
- `content-partner.php` - Partner logo card
- `content-team.php` - Team member card
- `content-team-detail.php` - Team member lightbox detail panel
- `content-project.php` - Project card
- `top-bar.php` - Top bar with phone/email contact links

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

## Backlog

Known technical debt / areas to revisit when time allows. Items here are not bugs in current behavior, but setups that could be more robust / maintainable.

- **AJAX blog filter: pass the main query through instead of rebuilding it.** `inc/ajax-filters.php::prospero_filter_blog_ajax()` currently composes its own `WP_Query` args (post type, posts_per_page, orderby, category, sticky handling, excerpt length override, etc.) and has to manually mirror everything the main archive does. A cleaner pattern (see <https://rudrastyh.com/wordpress/ajax-filter-posts-by-category-with-pagination.html>) is to ship the initial archive's `$wp_query->query_vars` to the client, send them back with each AJAX request, and let the handler rebuild the query from those vars — so `pre_get_posts` modifications, sticky-post handling, plugin-driven `excerpt_length` / ordering filters and anything else automatically apply to the filtered response without special-cased parity code. Current solution works, but this refactor would delete a meaningful amount of defensive code.

---

## Changelog

### Version 1.1.0 (2025-04-19)

- Fixed: Font download admin notice now disappears once fonts are downloaded locally.
- Added: Font download is now also triggered on `admin_init` and `customize_save_after`, so the notice resolves itself without needing to visit the frontend or block editor.
- Changed: Notice now uses `notice-warning` and only appears when a download actually failed.
- Fixed: `.page-description` on the projects and blog templates no longer forces `--color-secondary`; it now inherits the default body text color (including in dark mode).
- Changed: `.site-footer` no longer uses `--color-secondary` / hardcoded `#1a1923`. The footer background is now derived from the Customizer background color using `color-mix()` (darkened in light mode, lifted in dark mode), mirroring the approach used for project/blog post cards. Footer text and social icon chips now follow the regular theme text color.
- Fixed: Outline button style from the Customizer now actually renders as a border-only button. Buttons with `.button-primary`, `.button-secondary`, `.button-tertiary`, `.wp-block-button__link` and `.is-style-*` variants all honor the Customizer style (flat / outline), color, radius, hover, and uppercase settings.
- Changed: Button styling in `main.css` now uses CSS custom properties (`--prospero-btn-*-bg`, `-text`, `-border`, `-radius`, `-hover-bg`, `-hover-text`, `-text-transform`, `-letter-spacing`) populated by `prospero_dynamic_css()`. The duplicate static + dynamic rule approach has been removed, so nothing in the cascade can accidentally re-fill an outline button's background.
- Changed: Button CSS custom properties are now generated by a single source of truth (`prospero_get_button_css_vars()` in `inc/template-functions.php`) and reused by both the frontend dynamic CSS and the block editor (`prospero_editor_custom_properties()` in `inc/gutenberg.php`). Keeps frontend and editor previews in sync with the Customizer.
- Changed: Editor button previews in `editor-style.css` now consume the same `--prospero-btn-*` variables and fall back to the `theme.json` palette (`--wp--preset--color--*`), so Customizer-picked outline / color / radius / uppercase settings are reflected in the editor too.
- Docs: `theme.json`'s palette is the Gutenberg-facing source of colors. It is re-populated at runtime from the Customizer via `prospero_filter_theme_json_colors()` so every core block gets Customizer colors as defaults. CSS load order is parent CSS → child CSS → inline Customizer `:root` variables, so child themes override parent styles without ever clashing with the Customizer-driven variables.
- Added: Child theme scaffolding files `prospero-theme-child/assets/css/custom.css` and `prospero-theme-child/assets/js/custom.js` with header comments explaining how and when they are auto-loaded by the parent, what CSS variables / localised JS globals are available, and example usage patterns.
- Added: Descriptions on all Customizer color controls (Primary, Secondary, Tertiary, Text, Dark Text, Highlight, Background, Dark Background) so it's explicit which UI element each color drives.
- Changed: Breadcrumbs (native, Yoast, Rank Math) no longer use primary/secondary accent colors. They now use a muted version of `--color-text` and elevate to the full text color on hover, matching their role as supplemental navigation.
- Changed: Post meta on single posts and in blog grid/list layouts now uses a muted text color (`color-mix(--color-text 60%, transparent)`) instead of `--color-secondary`, so it reads as subtle metadata.
- Changed: `.single-post .post-category` no longer renders as a filled primary-color pill. It now uses a subtle uppercase text label (muted text color, underline on hover) so it doesn't compete with actual CTA buttons.
- Changed: Projects and blog taxonomy filter buttons (`.filter-button`, `.blog-category-filter .filter-button`) default to a neutral text-color style. Only the active filter uses the primary color, and as a text+border accent rather than a filled background.
- Changed: Blog grid and blog list `.entry-summary` now inherit the regular body text color instead of `--color-secondary`.
- Added: Button size hierarchy. `.button-secondary` is slightly smaller than `.button-primary` (0.625rem / 1.25rem padding, 0.9375rem font-size) and `.button-tertiary` is smaller still (0.5rem / 1rem padding, 0.875rem font-size), so the three roles read as a visual hierarchy.
- Fixed: Sticky header background is now derived from the Customizer background color via `color-mix()` at 95% opacity (was a hardcoded `rgba(255, 255, 255, 0.95)` in light mode and `rgba(43, 42, 51, 0.95)` in dark mode).
- Fixed: Search overlay background is now derived from the Customizer background color via `color-mix()` at 98% opacity (was a hardcoded `rgba(255, 255, 255, 0.98)` in light mode and `rgba(43, 42, 51, 0.98)` in dark mode).
- Added: Sleek, modern, global form-field styling covering `input[type="text"|"email"|"password"|"search"|"tel"|"url"|"number"|"date"|"datetime-local"|"month"|"week"|"time"]`, `textarea` and `select`. Soft 1px border, 6px radius, subtle background tint, clear hover/focus states with a primary-colored focus ring, normalised placeholder opacity, styled `<select>` chevron, vertical-only textarea resize, and a WebKit autofill override so Safari's yellow fill doesn't leak through.
- Added: Form-field CSS variables in `:root` (`--prospero-input-bg`, `-border`, `-border-hover`, `-border-focus`, `-text`, `-placeholder`, `-focus-ring`, `-radius`, `-padding-y`, `-padding-x`) all derived from Customizer colors via `color-mix()`. Dark mode redefines the same variables in `body.dark-mode`, so every form control automatically restyles for both modes and Customizer palette changes.
- Fixed: `.search-form .search-field` and `.search-overlay .search-field` no longer carry their own hardcoded colors/placeholder styles. They inherit from the shared form rules; the overlay keeps only its hero-size underline variant (still driven by the same variables).
- Removed: `body.dark-mode .search-form .search-field` and `body.dark-mode .search-overlay .search-field` / `::placeholder` overrides in `dark-mode.css` — superseded by the variable-based system.
- Fixed: Form-field accessibility contrast. The initial variable values were too low (input fill ~1.04:1 against the page and border ~1.4:1) so fields blended into the page. Rebalanced for WCAG 2.1 / European Accessibility Act: input fill is now `--color-background` mixed with ~8% `--color-text` (visible tint), borders use a 55% (light) / 50% (dark) text-on-background mix (>=3:1 per WCAG 1.4.11), hover raises it to 75%, placeholders use a 65% mix (>=4.5:1 per WCAG AA text contrast), and the focus ring is now 30% primary-alpha for clearer focus state. Border is 1.5px instead of 1px for perceptibility.
- Added: **Top Bar** feature. New Customizer section (priority 33, just above Header & Menu) with an enable toggle, optional phone number + prefix label (e.g. "Call us:"), and optional email address + prefix label. Rendered by `template-parts/top-bar.php` above the site header when enabled and at least one contact field is filled. Uses the existing icon font (`icon-phone`, `icon-mail`). Phone links strip formatting to a clean `tel:` URI; email uses `mailto:` wrapped with WordPress `antispambot()` for GDPR-friendly scraping protection. On mobile (<=767px) only the icons are shown; the prefix + value are visually hidden but remain accessible to screen readers via `aria-label` on each link.
- Added: Top bar styling in `main.css` / `dark-mode.css` derived from the Customizer background color (darker shade in light mode, lifted shade in dark mode, same family as footer / cards), with muted text, primary-color focus-visible outline, and a subtle bottom border.
- Changed: Sticky header scroll threshold in `main.js` now uses `header.offsetTop + header.offsetHeight` instead of just `headerHeight`, and recomputes on window resize. This keeps the sticky activation point correct whether or not the top bar is present, without any hardcoded assumptions about the top bar height.
- Changed: In sticky header + `logo-center` layout the vertical distance between the centered logo row and the menu row is now `var(--spacing-xs)` instead of `var(--spacing-md)`. The two-row stacking is preserved on purpose (so wide / full-width menus keep their full horizontal space instead of being squashed next to the logo) but the wasted vertical gap in the pinned header is removed. Smoothly transitioned.
- Changed: Top bar now pins together with the sticky header. When `body.header-is-stuck` is active the top bar becomes `position: fixed` at `top: 0` and the sticky header moves down by the top bar's height, so the contact options stay reachable while scrolling. The top bar's current height is exposed by `main.js` as `--prospero-top-bar-height` on `<html>` (measured on load and on resize), and `body.padding-top` now reserves `calc(--prospero-top-bar-height + 90px)` in sticky state. If no top bar is rendered the variable stays `0px` and the previous sticky behavior is unchanged.
- Fixed: Login / logout / account / user-dropdown menu items now use `display: inline-flex` with `align-items: center` on the anchor instead of `vertical-align: middle` on the icon. Icons are also sized to `1.15em` so they read as proper glyphs next to the label text. Removes the low-sitting / baseline-attached look.
- Added: Menu-item CTA button via a dedicated "Display as CTA button" checkbox on each menu item in Appearance → Menus. Powered by `inc/nav-menu.php` which uses `wp_nav_menu_item_custom_fields` to render the checkbox, `wp_update_nav_menu_item` to persist the flag as `_prospero_menu_cta` post meta, and `nav_menu_css_class` to inject the `menu-item-cta` class on the `<li>` (no custom walker needed - works with both the default walker and the theme's mobile panel walker).
- Added: Dedicated **Menu CTA Button** subsection in Customizer → Button Styles (style / radius / background / text / hover background / hover text / font style), with its own `--prospero-btn-menu-cta-*` variables plumbed through the shared `prospero_get_button_css_vars_for()` helper. Menu CTA buttons can now be themed independently from primary / secondary / tertiary content buttons.
- Changed: Menu CTA CSS selectors now only match `.menu-item-cta` (dropped the `.button` alias to avoid colliding with the generic `.button` utility class) and consume the new `--prospero-btn-menu-cta-*` variables instead of the primary variables.
- Changed: `Prospero_Radio_Image_Control` now skips the preview swatch block when a choice has no `svg` style, and uses tighter padding / min-width for label-only options. The four font-style controls (primary / secondary / tertiary / menu-cta) now render as compact Normal / UPPERCASE boxes instead of reserving ~34px of empty space above the labels. Preview-bearing choices (flat / outline) are unchanged.
- Fixed: Menu CTA button now sits on the exact same optical baseline as the regular menu items. Padding is split per breakpoint: `calc(var(--spacing-xs) - 2px) var(--spacing-sm)` on desktop and `calc(var(--spacing-sm) - 2px) var(--spacing-sm)` on mobile, so the button's total outer height (padding + 2px border) equals the regular menu link height without changing line-height or adding extra space. Removed the previous mobile `li` padding that pushed the button larger than other items.
- Fixed: Menu CTA text color was being overridden in dark mode by `body.dark-mode .main-navigation a` (same specificity, later in the cascade). Added dedicated `body.dark-mode .desktop-menu li.menu-item-cta > a` / mobile counterparts in `dark-mode.css` so the CTA keeps its Customizer-configured text color (and hover text color) in both modes.
- Added: Visible hover / focus feedback on regular desktop menu links. They now gain a `2px` underline at `0.35em` offset in addition to the primary-color shift, so the state change is perceptible. The menu CTA button explicitly resets `text-decoration: none` so the underline doesn't bleed into its button-style hover.
- Added: Accessibility-driven **button color safety net**. A new `prospero_contrast_ratio()` helper (with an rgba/hex-tolerant `prospero_parse_color_to_rgb()`) computes the WCAG 2.1 contrast between any button color and the page background for each mode. `prospero_get_button_css_vars_for()` now accepts a `$mode` (`light` / `dark`) and, if the button color has less than 3:1 contrast against that mode's page background (i.e. would become invisible), it swaps the visible accent - border/text for outline buttons, fill for flat buttons - to the mode's page text color, with an inverse hover text for legibility. `prospero_dynamic_css()` emits the light-mode button variables in `:root` and only adds a `body.dark-mode { ... }` block for the specific variables that the fallback changed, so output stays minimal when everything's fine. Resolves the case where picking (for example) the dark-mode background color as a button border made the outline button invisible in dark mode.
- Added: Dedicated FAQ templates. `archive-faq.php`, `taxonomy-faq_category.php` and `single-faq.php` now render the FAQ post type and its `faq_category` taxonomy as a click-to-expand accordion rather than the generic blog-card loop. Markup matches the existing FAQ list block's accordion output, so `assets/js/faq-accordion.js` drives the toggle behavior without changes. Schema.org `FAQPage` / `Question` / `Answer` microdata is included on archives and the single view (pre-expanded) for SEO. Single / taxonomy pages include a "Back to all FAQs" link; single view also shows the FAQ's category chips.
- Added: Theme-wide `.faq-accordion` styling (previously undefined). Soft bordered items that react on hover / focus-within, a pill-shaped toggle that flips to the Customizer primary color when the question is expanded, WCAG-friendly focus outlines, and dark-mode-aware surfaces derived from the Customizer background / text colors.
- Added: FAQ archive entry in the menu editor out of the box. `inc/nav-menu.php` hooks `default_hidden_meta_boxes` on the `nav-menus` screen to un-hide the FAQ post type's meta box. Because the CPT is registered with `has_archive: true`, that meta box already exposes an "Archives" option that adds the FAQ archive page to any menu with one click - now visible without having to enable Screen Options first.
- Added: Auto-grouping by `faq_category` on the FAQ archive and the FAQ list block when no specific category is selected. New `prospero_group_faqs_by_category()` helper buckets posts by their primary (alphabetically-first) term and puts category-less FAQs under an "Uncategorised" trailing group. `archive-faq.php` and `prospero_render_faq_list_block()` render each non-empty group under a category heading, with its own `.faq-accordion` container so every group is independently expandable. If only one group ends up populated, the heading is suppressed and the output falls back to a flat accordion. Taxonomy pages (already filtered to one term) are unaffected.
- Added: Customizer settings **FAQ Archive Title** and **FAQ Archive Description** (under Theme Options → Post Types, right under the FAQ enable toggle). `archive-faq.php` now uses them for the page heading and optional intro paragraph, falling back to the translatable default "Frequently Asked Questions" when the title is blank and omitting the intro when the description is blank.
- Added: Per-category **"Hide from all-FAQs view"** toggle on `faq_category` terms. New module `inc/faqs.php` registers a REST-visible boolean term meta, renders a checkbox on both the Add New and Edit term screens with a descriptive help text, and saves the value on `created_faq_category` / `edited_faq_category`. When a term is flagged, its FAQs (plus FAQs in every descendant term, so hiding a parent hides the sub-tree) are excluded from the FAQ archive main query via `pre_get_posts` and from the FAQ list block's `get_posts()` call whenever no specific category is selected. Taxonomy pages for the hidden category still render its contents, and selecting the hidden category explicitly in a block still shows the FAQs — only the aggregate "all" view is filtered.
- Fixed: Dark-mode toggle button icon color now tracks the theme text color. The `.dark-mode-toggle` rule was the only header icon button without explicit `color`, so its sun/moon glyph inherited the user-agent button default and didn't switch with the theme. Added `color: var(--color-text)` in the base rule and `color: var(--color-text-dark)` in the `body.dark-mode .dark-mode-toggle` override, matching how `.header-search-toggle` and `.mobile-menu-toggle` already behave.
- Changed: **Partner Single** block renders the logo beside the content (flex row) instead of stacked. Three new attributes: `logoPosition` (`left` default, or `right`), `showVisitLink` (boolean) and `visitLinkText` / `visitLinkStyle` (text + primary/secondary/tertiary). The logo is now automatically wrapped in a link to the partner's external URL (`_prospero_partner_url`) when one is set - matching the partners list behaviour - and the optional "Visit website" button is rendered under the description using the theme's standard button styles. Inspector panel in the editor gained the matching controls, including conditional reveal of the button text / style when the visit-link toggle is on. New `.prospero-partner-single` / `.partner-single-logo-left|right` CSS handles the row layout with a mobile stack breakpoint at 600px.
- Fixed: Testimonial star ratings. Awarded stars render truly filled; unrated stars render as the Lucide outline. Both states are now drawn via CSS `mask-image` on the exact same Prospero star polygon (the one that builds the prospero-icons font glyph), so their rendered sizes are pixel-identical — only fill-vs-stroke differs. Glyph color uses `currentColor` so Customizer highlight / text colors flow through automatically. Replaces the earlier Unicode ★ / ☆ attempt, which suffered from mismatched glyph metrics across fonts (the outlined Unicode star was visibly larger than the filled one).
- Fixed: Blog category AJAX filter output now matches the initial archive render exactly. Root cause was that `inc/ajax-filters.php::prospero_filter_blog_ajax()` duplicated the article markup inline and had drifted from `content-blog-loop.php` on excerpt handling and sticky eligibility. Extracted the single-article render into a shared template part `template-parts/content-blog-item.php` used by both the main loop and the AJAX handler, so grid / list layout, excerpt length, sticky class and thumbnail size are guaranteed identical on initial load and after filtering.
- Fixed: AJAX blog filter now explicitly sets `orderby => date` and `order => DESC` in its `WP_Query` args, so a `pre_get_posts` filter flipping the default order can't desync the filtered response from the main archive.
- Changed: Minor perf win on the AJAX blog filter — `update_post_term_cache` is now set to `false` when no `category_name` is in the query, skipping the term-cache warmup the shared item template doesn't need. Re-enabled automatically on category-filtered paths because WordPress needs that cache to resolve the slug.
- Changed: Testimonial ratings layout no longer center-wraps. Removed the `justify-content: center` override on sliders / single-column grids (which produced odd half-rows) and removed the inter-item divider pseudo-element. Items now flex-wrap left-aligned at desktop, and below 600px they stack one per row so a narrow card never shows a partially-wrapped rating row.
- Added: `.faq-group-title` styling (inherits the theme text color, adapts for dark mode) with a `:first-child` top-margin reset so consecutive group titles sit at a natural rhythm.
- Changed: Customizer section priorities shifted so the new Top Bar slots cleanly: Header & Menu 33→34, Typography 34→35, Content Settings 35→36, Blog Layout 35→37.
- Fixed: AJAX blog filter pagination now matches the initial `the_posts_pagination()` render exactly. Previously the filter replaced the `<nav class="navigation pagination">` wrapper (with its screen-reader heading) with a bare `<ul class="page-numbers">`, so filtering a category silently dropped accessibility semantics. `inc/ajax-filters.php` now wraps the `paginate_links()` output in the same `navigation pagination` nav + `screen-reader-text` heading + `nav-links` div shape WordPress core emits, including `mid_size => 2`.
- Fixed: AJAX blog filter term-cache regression. Setting `update_post_term_cache => false` was documented as a perf win, but because `content-blog-item.php` runs `post_class()` which calls `get_the_category()` per post, suppressing the cache caused one extra DB query per article on every filter response. Removed the flag (and the now-redundant category-path re-enable branch), so the filter query primes the term cache the same way the main archive does.
- Fixed: Blog category filter no longer scrolls the filter bar behind the pinned top bar. `assets/js/blog-filter.js` now reads `--prospero-top-bar-height` (set by `main.js` when a top bar is rendered) and subtracts it from the scroll target alongside `.site-header`'s height, so the filter lands just below the combined pinned chrome in every layout.
- Fixed: Sticky posts were rendered in natural date order (not pinned to the top) when the user clicked "Alle" on the AJAX blog filter. Root cause: WordPress only auto-prepends sticky posts when `$this->is_home && $page <= 1` inside `WP_Query::get_posts()`, which is never true for an admin-ajax custom query — so `ignore_sticky_posts => false` alone had no effect. `inc/ajax-filters.php` now replicates core's behavior on page 1 of the "all" filter: it reads `get_option( 'sticky_posts' )`, strips any sticky IDs that the natural date-order query already returned (so they don't render twice), fetches them separately via `get_posts()` (newest-first, published only, even if their date would otherwise place them on a later page), and prepends the result to `$query->posts`. Paged / category-filtered responses are unaffected.
- Fixed: AJAX blog filter excerpts are no longer roughly twice as long as the initial archive render. The theme's `prospero_excerpt_length` filter returns 30 words, but in some setups a plugin / `is_main_query()`-gated filter was leaving our hook bypassed in the admin-ajax context, so the response fell back to WP's hardcoded 55-word default. `inc/ajax-filters.php` now registers a scoped `excerpt_length` callback at `PHP_INT_MAX` priority for the duration of the render loop (and removes it again immediately after `ob_get_clean()`), guaranteeing parity with the main archive regardless of what else is registered.
- Added: `prospero_get_excerpt_length()` helper in `inc/template-functions.php` returning the theme's 30-word auto-excerpt cap, exposed via the new `prospero_excerpt_length` theme filter. `prospero_excerpt_length` (the `excerpt_length` callback) and the AJAX blog filter both route through the helper, so a child theme can adjust the value in one place and both paths follow.
- Changed: FAQ accordion toggle glyphs are now rendered from the `prospero-icons` font (`.icon-plus` / `.icon-minus`) instead of inline `+` / `−` characters. `archive-faq.php`, `taxonomy-faq_category.php`, `single-faq.php` and the FAQ list block's accordion branch in `inc/blocks.php` emit `<span class="faq-toggle icon-plus">` (collapsed) or `<span class="faq-toggle icon-minus">` (pre-expanded single view). `assets/js/faq-accordion.js` swaps the class (not `textContent`) on click, so the pill-shaped toggle keeps the existing circle background while rendering a proper icon glyph - typographically consistent with the other icon buttons (search, menu, dark-mode, login / logout).
- Fixed: Blog list layout thumbnail-to-content proportions. The thumbnail column was a fixed `200px` on desktop (`160px` on tablet), which collapsed to well under a third of the row on wide containers and made the image look undersized next to the text. Replaced with a flex ratio — `flex: 1 1 0` on `.post-thumbnail`, `flex: 2 1 0` + `min-width: 0` on `.post-content` — so the image reliably occupies about one third of the row and the text two thirds at any container width, with the `aspect-ratio: 4 / 3` frame preserved. The tablet width override is no longer needed; the mobile stack at ≤ 480px now explicitly resets `flex-grow` on both columns so they each span 100% of the row once `flex-direction: column` kicks in.
- Changed: Button contrast safety net is no longer strict WCAG AA by default. The previous 3:1 threshold (per WCAG 1.4.11 Non-text Contrast) was inverting designer-picked colors that were visibly distinct from the page background — e.g. `#b22222` primary / `#5b6e91` secondary against a `#333c50` dark-mode background measure at ~1.6:1 and ~2.2:1 respectively, both below 3:1 but clearly perceivable. The default threshold is now `1.5:1`, so the fallback only kicks in when the button would be nearly indistinguishable from the page. Strict WCAG AA behavior is preserved as an explicit opt-in.
- Added: Customizer checkbox **Button Styles → Accessibility → "Enforce WCAG AA button contrast (3:1)"**, off by default. When checked, `prospero_get_button_contrast_threshold()` returns `3.0` and the legacy strict behavior applies. When unchecked, the threshold is `1.5`. The threshold also routes through a new `prospero_button_contrast_threshold` filter so a child theme can dial in an exact value without toggling the Customizer setting.
- Changed: "Display as CTA button" menu-item field no longer squishes its long helper text inline next to the label. The description is now behind a compact `dashicons-editor-help` info icon and revealed as an accessible popover on hover / focus. The description stays in the DOM (via `role="tooltip"` + the button's `aria-describedby`) so it remains reachable for screen readers. New `assets/css/admin-nav-menu.css` (scoped via a `nav-menus.php`-only `admin_enqueue_scripts` callback in `inc/nav-menu.php`) drives the icon + popover styling.

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
