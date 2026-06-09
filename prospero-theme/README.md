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

No open items.

---

## Changelog

### Version 1.2.0 (2026-06-09, release)

Minor release rolling up everything since the v1.1.0 tag. Highlights:

- **Full-width feature image banners** for single posts and pages, with per-entry override (Customizer toggles + sidebar meta box + REST-registered meta).
- **Full-width alignment fix:** `.alignfull` blocks now reliably reach the viewport edges via symmetric negative-margin breakout; `overflow-x: clip` on `.site` prevents rounding-induced horizontal scroll.
- **Blog-page banner on `home.php` and post-type=post archives** (category, tag, author, date), using the page assigned as the Posts page so taxonomy and archive views inherit a hero without needing a separate taxonomy-image feature.
- **Related Posts now use the shared blog item template** (`template-parts/content-blog-item.php`), so excerpt visibility follows the "Show Excerpt in Grid" Customizer setting and the visual style matches the blog grid.
- **Active menu item highlight** for the current page, parent menu items, and ancestors on both desktop and mobile menus; the Posts-page menu item now stays highlighted across single posts and all blog archives.
- **Testimonial position field** rendered below the display name in the testimonial template part, the `prospero/testimonial-single` block, and the project testimonial section.
- **Header / footer chrome colors** with light + dark variants and an automatic contrast safety net for button colors.
- **Top Bar** with optional phone / email and a pinned-with-the-sticky-header behavior.
- **Smooth scroll for same-page anchor links** site-wide, with sticky-header offset and URL-hash cleanup.
- **WordPress gallery as a Flickity slider** (opt-in block style + autoplay variant).
- **Menu CTA Button** support and admin nav-menu polish.
- **FAQ archive** with per-category visibility toggles, customizable title / description, and accordion grouping.
- **Frontend login system** improvements (mobile branding fix when no logo, login redirect, local-avatar handling).
- **Mobile submenu chevron fix** (parent menu-item ID no longer extracted by a fragile regex; tracked explicitly in the walker).
- **Translation refresh** for `prospero-theme.pot`, `de_DE.po`, and `de_DE_formal.po` plus regenerated `.mo` binaries; 108 newly-translated strings + the `Star`/`Stars` plural pair.

See the dated subsections below for the per-change detail.

### Version 1.2.0 (2026-06-09, mobile submenu toggle fix)

- Fixed: **Mobile menu submenu chevron didn't open the panel.** `Prospero_Mobile_Menu_Walker::start_lvl()` tried to extract the parent menu-item ID by running `preg_match( '/menu-item-(\d+)"[^>]*>\s*$/', $output, $matches )` against the output buffer, but `start_el()` ends with a `</div>` (the closing `.menu-item-inner` wrapper), so the regex never matched. The rendered panel ended up with `data-submenu-id="submenu-"` while the chevron carried `data-submenu="submenu-42"`, so the JS `querySelector('[data-submenu-id="submenu-42"]')` returned nothing and the click did nothing. The walker now tracks the most recently rendered item's ID in a `$current_parent_id` property updated in `start_el()` and consumed in `start_lvl()` - no regex needed, and the panel/toggle IDs match.
- Changed: `initMobileMenu()` in `assets/js/main.js` now toggles `aria-expanded` on the `.submenu-toggle` button when opening/closing a submenu, and flips `aria-hidden` on the `.submenu-panel` accordingly. Queries are scoped to the mobile menu panel instead of the whole document so they don't accidentally collide with similarly-named selectors elsewhere on the page.

### Version 1.2.0 (2026-06-09, related posts share blog layout; blog-page banner on archives)

- Changed: **Related Posts now use the same `content-blog-item` template part as the blog loop.** `single.php` no longer renders its own bespoke `.related-post-card` markup. Instead it iterates the related query with `get_template_part( 'template-parts/content-blog-item', null, $args )` inside a `.posts-grid[data-columns="3"]` wrapper, with `allow_sticky => false` so a related sticky post doesn't get the featured label, and `show_excerpt` read from the `prospero_blog_grid_excerpt` Customizer setting. The wrapping `<section>` carries the `blog-template` class so the existing `.blog-template .posts-grid` styling applies without duplication. The orphaned `.related-posts-grid` / `.related-post-card` / `.related-post-thumbnail` / `.related-post-content` / `.related-post-title` / `.related-post-date` rules in `assets/css/main.css` were removed.
- Added: **Blog-page featured-image banner on `home.php` and post archives.** `home.php` now picks up the `page_for_posts` page's thumbnail and renders it via the same `.post-featured-image-fullwidth` banner used on `page.php` (resolved through `prospero_should_show_fullwidth_featured( $posts_page_id )`, so the per-page meta override and the `prospero_page_featured_fullwidth` Customizer toggle both apply). The breadcrumbs follow the banner, mirroring `page.php`.
- Added: `archive.php` borrows the same Posts-page banner on post-type=post archives (category, tag, author, date, and the `post` post-type archive). Since the theme has no taxonomy-image feature, this gives category and tag pages the same hero look as the blog page itself. CPT archives (project, team, faq, …) are explicitly excluded via an `is_post_archive` guard so they keep their own templates untouched.

### Version 1.2.0 (2026-06-09, translations refreshed)

- Updated: `languages/prospero-theme.pot`, `languages/de_DE.po` and `languages/de_DE_formal.po` with 108 previously-untranslated strings plus the `Star` / `Stars` plural pair. Covers the strings added since the last translation pass (full-width featured-image banner, per-entry Featured Image Width meta box, Top Bar, header/footer chrome colors, Menu CTA button, FAQ archive options, testimonial position field, accessibility toggles, etc.). German formal vs. informal forms differ where the string addresses the visitor directly ("Don't have an account?", "Enter your email...", etc.); back-end Customizer labels share the same translation in both files. The compiled `.mo` files were regenerated as well.

### Version 1.2.0 (2026-06-08, testimonial position field)

- Added: **Position field on the testimonial post type.** New `_prospero_testimonial_position` meta with a sidebar meta box on the testimonial editor (e.g. "CEO at Acme"), saved through the existing nonce-guarded `prospero_save_meta_boxes` flow. The position is rendered below the display name on the testimonial template part, the `prospero/testimonial-single` block, and the testimonial section of `single-project.php`. Styling is smaller than the name (0.875 rem, italic, 65% text-color mix) so it reads as supplementary metadata; dark mode mirrors the same mix against the dark text color.
- Changed: The `prospero/testimonial-single` block render in `inc/blocks.php` now uses the testimonial's `_prospero_testimonial_display_name` (falling back to `post_title`) instead of always rendering the post title, matching how `template-parts/content-testimonial.php` and `single-project.php` already resolve the author name.

### Version 1.2.0 (2026-06-08, active menu item highlight)

- Added: **Active menu item highlight on desktop and mobile.** `.desktop-menu` and `.mobile-menu-list` now style `.current-menu-item`, `.current-menu-parent` and `.current-menu-ancestor` (plus their `current_page_*` siblings) so the user sees where they are in the site structure - primary-color text + 2 px underline on desktop, primary-color text + 600 weight on mobile. Sub-menu pages automatically light up their parent via WP's existing `current-menu-ancestor`/`current_page_ancestor` classes; the theme just styles them. Menu-CTA items (`.menu-item-cta`) suppress the underline so they keep their button-style appearance when active.
- Added: `prospero_nav_menu_blog_current_class` filter on `nav_menu_css_class` in `inc/nav-menu.php`. WordPress core only flags the "Posts page" menu item as current on the blog home itself; this filter adds `current-menu-item` / `current_page_item` to the same item when viewing a single post, a category / tag / author / date archive, or the `post` post-type archive, so the blog menu entry stays highlighted across the entire blog section.

### Version 1.2.0 (2026-05-26, full-width alignment fix + featured-image banner)

- Fixed: **`.alignfull` blocks now reliably reach the viewport edges.** The previous implementation used `width: 100vw` plus a single negative left margin, which left visible gaps on the right when nested inside the theme's centered wrappers (`.container` + `.post-content`) and could trigger a horizontal scrollbar from the scrollbar-width offset between `100vw` and document width. `assets/css/blocks.css` now uses `width: auto; max-width: none; margin-left: calc(50% - 50vw); margin-right: calc(50% - 50vw)`, which fills the viewport edge-to-edge regardless of the centered ancestor's padding. Inner content wrappers (`.wp-block-group__inner-container`, `.wp-block-cover__inner-container`, `.wp-block-columns`, `.prospero-cta-inner`, custom Prospero block inner wrappers, and the explicit `.alignfull-content` opt-in) are constrained back to `--content-width` so backgrounds bleed full-bleed while readable content stays inside the page's content area. Removed the overly broad `.alignfull > *:not(.alignfull-background)` catch-all that previously re-constrained media inside full-bleed blocks.
- Changed: Consolidated the alignment CSS in `assets/css/blocks.css`. The repeated per-block `.alignfull` declarations for CTA, Text Content, Team, Testimonials, Partners and FAQs (each duplicating the same `100vw` / negative-margin trick) are now grouped into a single inner-wrapper rule that all blocks share. Wide alignment (`.alignwide`) was simplified to `max-width: var(--wp--style--global--wide-size, var(--content-width, 1200px))` with auto margins, which naturally collapses on small viewports without needing the previous responsive `@media (max-width: 1024px)` override.
- Added: `overflow-x: clip` on `.site` in `assets/css/main.css` so any sub-pixel rounding from the negative-margin breakout cannot produce a horizontal scrollbar. `clip` was chosen over `hidden` because it does not establish a new scroll container - the sticky header and other `position: fixed` children continue to escape it as expected.
- Added: **Full-width featured-image banner** option for single posts and pages. New Customizer toggles `prospero_single_featured_fullwidth` (Blog Layout) and `prospero_page_featured_fullwidth` (Content Settings), both off by default. When enabled, the featured image is rendered outside `.container` directly inside `.site-main` and gets the new `.post-featured-image-fullwidth` class - the image spans the full viewport width, drops its border radius, capped at `max-height: 70vh` (50vh on mobile) with `object-fit: cover` so portrait or square thumbnails don't take over the viewport. Pages don't render a featured image at all by default, so the page toggle is purely opt-in and existing pages are unaffected.
- Added: **Per-post / per-page Featured Image Width** override in a new sidebar meta box (post and page screens). Three choices - "Use theme default", "Full-width banner", "Constrained to content width" - stored as `_prospero_featured_fullwidth` post meta. Default is empty (inherit); the meta is registered with `show_in_rest` so the block editor can read/write it and `sanitize_callback` keeps the value within the three allow-listed states. New module `inc/featured-image-meta.php` registers the meta, renders the meta box, and saves on `save_post` with a nonce + capability check.
- Added: Helper `prospero_should_show_fullwidth_featured( $post )` in `inc/template-functions.php` that resolves the per-entry override first, then falls back to the matching Customizer toggle based on `post_type`. `single.php` and `page.php` call the helper to decide whether to render the banner outside `.container` or keep the existing constrained layout. The single post template still honors the existing "above" / "below" / "hidden" position setting; the banner option only changes the width, not the position.
- Changed: When the full-width banner is rendered above the post/page (single posts with the "above" position, or pages with the toggle enabled), the breadcrumbs now appear underneath the image so the banner sits flush against the site header. The `.site-main` top padding is suppressed via `:has(> .post-featured-image-fullwidth:first-child)` for the same reason. Other positions / non-banner layouts keep breadcrumbs at the top.

### Version 1.2.0 (2026-05-11, smooth anchor scroll)

- Added: **Smooth scroll for every same-page anchor link, with sticky-header offset and URL-hash cleanup.** A new delegated handler `initAnchorScroll()` in `assets/js/main.js` registers a single `click` listener on `document` and catches `<a href="#section">` links anywhere on the page - post / page content, Gutenberg blocks, sidebar widgets, footer, table-of-contents lists, and the regular desktop navigation. Each click is filtered through `resolveSamePageAnchorTarget()`, which returns the actual DOM element only when the link's href matches the current path + querystring AND has a hash AND the target id (or legacy `a[name="..."]`) actually exists; everything else falls through to native browser behavior. When a target is found the handler calls `preventDefault()` and routes to the shared `scrollToAnchor(target)` helper.
- Added: `scrollToAnchor()` helper that performs the actual scroll. It offsets the target by `--prospero-top-bar-height + #masthead.offsetHeight` when the sticky header is (or is about to become) active, plus a 32 px breathing-room gap (matching the theme's `--spacing-md`) so headings land with comfortable space above them instead of flush against the pinned chrome. The scroll uses `window.scrollTo({ behavior: 'smooth' })`, switching to instant when `prefers-reduced-motion: reduce` is set (WCAG 2.3.3). Keyboard focus is moved to the target (with a temporary self-cleaning `tabindex="-1"` for non-interactive elements), and `history.replaceState()` strips the hash from the address bar without pushing a history entry.
- Added: **Hamburger panel anchor links use the same helper, with a 220 ms close-first delay.** The previous behavior fired the native browser jump while the panel was still on top of the viewport, so the user only saw the page "reset to the top". The mobile menu link handler now intercepts same-page anchor clicks, calls `closeMenu()` first, and then runs `scrollToAnchor(target, 220)` so the panel's 0.3 s slide-out transition has time to play before the scroll motion begins. Applies to both mobile breakpoints AND the desktop "always hamburger" mode (same `.mobile-menu-panel` markup). Submenu parent links (`href="#"`) are still ignored so they keep opening the child panel; cross-page links keep the previous 100 ms close-delay.
- Changed: The global handler skips clicks inside `.mobile-menu-panel` (handled by the hamburger handler above), modifier-key clicks (`Cmd` / `Ctrl` / `Shift` / `Alt`) so "open in new tab / window / save as" still works, non-primary mouse buttons, `target="_blank"` / `download` links, links carrying a `data-no-smooth-scroll` opt-out attribute, and events whose `defaultPrevented` is already `true`. Tabbed-content widgets or other components that emit `<a href="#tab-id">` to drive UI state (not actual navigation) can opt out via `data-no-smooth-scroll`; none of the theme's own accordion / FAQ / lightbox components are affected because they all use `<button>` elements rather than hash links.

### Version 1.2.0 (2026-05-11, even later)

- Fixed: **Site title and tagline missing on mobile / tablet when the header has no logo.** `assets/css/main.css` contained an unconditional `.site-title-wrap { display: none; }` inside the `@media (max-width: 1024px)` block, originally added to keep the mobile header tight when the branding column already had a logo. Once the 2026-05-08 "Show Logo in Header" toggle landed (and any pre-existing site that simply hadn't uploaded a custom logo and was relying on `prospero_show_logo` being off), that blanket hide left the `.site-branding` column completely empty on every viewport <= 1024px - mobile users lost both the site name and the tagline with no fallback. The rule is now scoped to `.site-header:has(.custom-logo-link) .site-title-wrap`, so the title + tagline are only hidden when `prospero_custom_logo()` actually emitted a logo link (the marker class `.custom-logo-link` is set by both the custom-logo path and the bundled `logo.svg` / `logo-dark.svg` fallback path). When no logo is rendered - because the toggle is off, or because no logo is configured - the title and tagline now stay visible on mobile and tablet, restoring the site's branding. Browsers that don't support `:has()` (a vanishingly small share at this point) fall through to the default (title visible everywhere on mobile), which is the safer fallback regardless. No PHP / JS changes were needed; the marker class was already in the markup.

### Version 1.2.0 (2026-05-11, later)

- Added: **Autoplay variant for the WordPress Gallery slider.** Two opt-in mechanisms, mirroring the manual / classic gallery options from the initial slider work: (1) a second block style `Slider (autoplay)` registered for `core/gallery` in `inc/gutenberg.php` (adds `is-style-slider-autoplay` to the wrapper), and (2) a `prospero-gallery-autoplay` additive class users can drop into Advanced -> Additional CSS classes on any slider-flagged gallery (works for the classic `[gallery]` shortcode and for manually classed Gutenberg galleries that already carry `prospero-gallery-slider`). Block styles are mutually exclusive in WordPress so the second style entry is a separate picker option rather than an additive toggle - this is the cleanest UX for editor users while still leaving an additive class path for everything else.
- Changed: `assets/js/flickity-init.js::initGallerySlider()` now detects either signal on the wrapper (`is-style-slider-autoplay` or `prospero-gallery-autoplay`) and enables Flickity's `autoPlay: 5000` plus mouseenter/mouseleave pause/resume hooks - identical to the testimonials / partners listing sliders. Autoplay is automatically suppressed when there aren't enough cells to actually rotate (single-static-slide case) so a one-image gallery flagged as autoplay doesn't waste a timer. The gallery slider's selector list in the DOMContentLoaded handler picks up `is-style-slider-autoplay` so the new block style is wired in without a separate code path.
- Changed: `assets/css/blocks.css` selectors for the slider variant now include `is-style-slider-autoplay` on every rule (wrapper layout neutralization, cell margin/max-width reset, image normalization, caption spacing, pre-init guard). The block style header comment was updated to document all three opt-in paths and the autoplay class combinations.

### Version 1.2.0 (2026-05-11)

- Added: **WordPress Gallery as Flickity slider (opt-in).** A new "Slider" block style is registered for `core/gallery` in `inc/gutenberg.php`, so users can flip any Gutenberg gallery into a slider from the Styles panel in the editor (adds `.is-style-slider` to the wrapper). Users can also opt in manually by adding `prospero-gallery-slider` via the Advanced -> Additional CSS classes field, which works for the classic `[gallery]` shortcode and any markup the block style picker doesn't reach. Default galleries are untouched - the slider is purely opt-in.
- Changed: `assets/js/flickity-init.js` gained a new `initGallerySlider()` path that handles all three gallery markup variants: modern Gutenberg 5.9+ (direct `.wp-block-image` children inside `.wp-block-gallery.has-nested-images`), legacy Gutenberg (`.blocks-gallery-grid` `<ul>` with `.blocks-gallery-item` `<li>` cells), and the classic `[gallery]` shortcode (`.gallery` with `.gallery-item` figures). Flickity is initialized on the inner cells container (not the outer wrapper), so any CSS on `.wp-block-gallery` keeps applying. Columns are detected from the wrapper's `columns-N` / `gallery-columns-N` class with a fallback of 3; cell widths are computed as `calc((100% - Npx) / cols)` with a 16px inter-cell gap, applied inline at init and on debounced resize. `wrapAround`, `prevNextButtons`, `pageDots`, and `draggable` are toggled off when there aren't enough cells to actually need them, matching the existing listing-slider behavior. Keyboard navigation (left / right arrows) wired up the same way as the listing sliders. A new `.prospero-gallery-slider-init` class is added to the wrapper after a successful init for double-init protection and for the CSS pre-init guard described below.
- Added: CSS in `assets/css/blocks.css` to neutralize the default gallery flex / grid layout when the slider is active. Replaces `display: flex` with `display: block` on the wrapper, clears item margins / max-widths so the inline cell widths win, normalises captions inside slider cells, and adds a pre-init guard (`:not(.prospero-gallery-slider-init)`) that hides all cells except the first one until Flickity has finished initializing - prevents the flash of a tall, mis-laid-out gallery while images are still loading.
- Note: the existing lightbox (`assets/js/lightbox.js`) already groups `.wp-block-gallery` and `.gallery` containers as a single lightbox gallery, so clicking a slide image in slider mode still opens the full gallery in the lightbox with prev/next navigation - no additional integration code was needed.

### Version 1.2.0 (2026-05-08)

- Added: **"Show Logo in Header" toggle in Site Identity (Customizer).** Default `true` so existing sites are unaffected. When unchecked, `prospero_custom_logo()` in `inc/template-functions.php` returns early without emitting any markup, so the `.site-branding` column shows only the site title and tagline (the existing `.site-title-wrap` markup in `header.php` is unconditional and was already rendering alongside the logo). The same toggle is honored by `prospero_custom_login_logo()` in `inc/frontend-login.php`: with the toggle off, the login screen no longer emits its `#login h1 a` background-image override, so WordPress falls back to its own default mark instead of the bundled `logo.svg`. Resolves the previous behavior where removing the WP custom logo via Site Identity silently substituted the theme's bundled `logo.svg` / `logo-dark.svg` placeholder, with no way to display only the site title and tagline. The bundled SVGs still ship with the theme and are still used as a fallback when the toggle is on and no custom logo is uploaded - they're only suppressed when the user explicitly opts into a logo-less header.

### Version 1.2.0 (2026-04-29)

- Added: **Header and footer background color settings (with light + dark variants).** Four new Customizer fields under Theme Colors: `prospero_header_bg_color`, `prospero_header_bg_color_dark`, `prospero_footer_bg_color`, `prospero_footer_bg_color_dark`. All four default to empty so existing sites keep today's behavior - the header continues to use the page background and the footer continues to use today's auto-darkened shade until the user actually picks a color. Foreground text color is auto-resolved per region per mode: a new helper `prospero_get_chrome_css_vars_for( $region, $mode )` in `inc/template-functions.php` runs `prospero_contrast_ratio()` for the configured background against both `prospero_text_color` and `prospero_dark_text_color` and picks whichever has the higher contrast - so a dark header on a light page automatically flips its text color to the dark-mode text setting, and vice versa. No separate "header text color" / "footer text color" setting is exposed; the auto-resolved value is good enough for almost every case and keeps the Customizer surface small.
- Changed: `prospero_dynamic_css()` now emits four new CSS custom properties: `--prospero-header-bg`, `--prospero-header-text`, `--prospero-footer-bg`, `--prospero-footer-text`. Light-mode values land in `:root`; dark-mode values are emitted in `body.dark-mode { ... }` only when they actually differ from the light values (re-using the existing minimal-emit pattern from the button safety net). When a region's bg setting is empty, the helper emits the previously hardcoded fallback as the variable value (`var(--color-background)` / `var(--color-background-dark)` for the header; `color-mix(in srgb, var(--color-background) 92%, black)` / `color-mix(in srgb, var(--color-background-dark) 88%, white)` for the footer), so the variable is always defined and the fallback chains in the CSS files stay simple.
- Changed: `assets/css/main.css` and `assets/css/dark-mode.css` now consume the new variables. `.site-header { background-color: var(--prospero-header-bg, ...); color: var(--prospero-header-text, ...); }`, the sticky header's `color-mix()` uses the header variable as its source, and `.site-footer` mirrors the same pattern with the footer variables. Existing per-element rules that hard-coded `var(--color-text)` for header chrome (`.site-title a` inside the header, desktop `.desktop-menu a`, `.menu-toggle-icon`, `.header-search-toggle`, `.dark-mode-toggle`) now read from `var(--prospero-header-text, var(--color-text))` so the auto-resolved foreground color flows through to all the menu links / toggle icons / dark-mode button. The dark-mode equivalents (`body.dark-mode .menu-toggle-icon`, `body.dark-mode .header-search-toggle`, `body.dark-mode .dark-mode-toggle`) were updated for the same reason - their original `var(--color-text-dark)` hard-codes had higher specificity than the body-scope cascade and would have masked the new variable in dark mode otherwise.
- Changed: The Customizer color picker palette (`prospero_customizer_color_palettes`) now includes the four new chrome colors when set, so when you open any color picker you can quickly re-use a header / footer color you've already picked elsewhere. Empty values are filtered out so the palette doesn't pad itself with empty swatches.

### Version 1.2.0 (2026-04-28)

- Fixed: **Custom post types ignored the Customizer toggles on fresh installs (and on every child-theme activation).** `inc/customizer.php` registers all five `prospero_enable_*` settings (testimonials, partners, team, projects, FAQ) with `'default' => false`, but the call sites that gate post-type and meta-box registration in `inc/post-types.php` and the `is-this-CPT-enabled` guards in `template-team.php` / `template-projects.php` passed `true` as the `get_theme_mod()` fallback. `get_theme_mod()` only consults the second argument when no value has been saved yet, so the customizer-registered default never came into play - any theme_mods table that hadn't been touched (a brand-new install, or a freshly-activated child theme, since theme_mods are stored per stylesheet under `theme_mods_<stylesheet>` and don't carry over from parent to child) ended up registering every CPT regardless of customizer state. Fallbacks are now aligned to `false` everywhere: `prospero_register_testimonials`, `prospero_register_partners`, `prospero_register_team`, `prospero_register_faq` (the projects registration was already correct), the matching `add_meta_box()` branches inside `prospero_add_meta_boxes()` (including the nested testimonial-selector inside the project meta boxes), plus the front-end "is enabled" checks in `template-team.php` and `template-projects.php`. Behaviour is now opt-in across the board: a CPT only appears in the admin menu and on its template after the corresponding Customizer toggle is checked. Note: existing parent-theme installs that relied on the previous accidental opt-out (i.e. never saved a value, but had post types visible because of the `true` fallback) need to flip the toggles on once after updating; saved post data is preserved either way.

### Version 1.2.0 (2026-04-22, even later)

- Added: **Column Span (Pre-Footer) control for block widgets.** Classic widgets already carried the control via `in_widget_form` in `inc/sidebars.php`, but block widgets only had an orphan stub: the previous `prospero_enqueue_widget_block_editor_assets()` injected an inline `blocks.registerBlockType` filter that declared a `prosperoColumnSpan` attribute on every core block, but never rendered a UI for it and never emitted a class, so the setting was invisible and had no effect. Replaced the inline stub with a proper enqueue of the new `assets/js/widget-column-span.js` file, which (1) registers the `prosperoColumnSpan` attribute on every block (with a collision guard so blocks that already declare the attribute are left alone), and (2) uses an `editor.BlockEdit` HOC built on `createHigherOrderComponent` to inject an `InspectorControls` panel ("Pre-Footer Layout" -> "Column Span (Pre-Footer)") with a `SelectControl` offering 1 / 2 / 3 columns. The panel is rendered for every block in the widgets editor; the enqueue is already scoped to `$screen->id === 'widgets'` so it won't appear in the post editor or site editor. An earlier draft of the HOC tried to limit the panel to top-level blocks via `getBlockRootClientId( props.clientId )`, but that silently hid the panel on every widget - in the block-based widgets editor each sidebar is itself a `core/widget-area` container block, so the blocks a user drops into a sidebar are NOT at the editor root and the guard rejected all of them. The `wp-data` dependency that only existed to support that check was dropped as well.
- Changed: `prospero_widget_column_span_class()` (the `dynamic_sidebar_params` filter that injects `.span-N` on the `.pre-footer-widget` wrapper) now handles block widgets in addition to classic widgets. When the resolved widget instance is a `WP_Widget_Block`, the filter runs `parse_blocks()` on the stored `content` HTML and reads the first non-empty top-level block's `prosperoColumnSpan` attribute. Classic widgets keep reading the `prospero_column_span` instance key set by `in_widget_form`. Both paths now share the same allow-list (`'1'`, `'2'`, `'3'`) and defensive checks around missing `option_name` / `number` params, so malformed widget registrations fall back cleanly to `span-1` instead of throwing. No CSS changes were needed - the existing `.pre-footer-widgets > .pre-footer-widget.span-N` grid rules in `assets/css/main.css` already handle both.

### Version 1.2.0 (2026-04-22, later)

- Changed: **CTA block - no more rounded corners on the background element or the content image.** `.prospero-cta` (the outer coloured / image-backed wrapper) and all three `.prospero-cta-image` / `.prospero-cta-image-center` inner images lost their `border-radius: 8px`. Applied symmetrically in both `assets/css/blocks.css` (frontend) and `assets/css/editor-style.css` (block editor preview) so the editor matches the rendered page. CTA now reads as a flat, full-bleed block by default; anyone who wants rounded corners back can add them via theme.json, the child theme's `custom.css`, or the Advanced → Additional CSS classes field (which now flows through to the wrapper, see previous changelog entry).
- Changed: **CTA block - "Text Content" now supports inline formatting and multiple paragraphs.** The Inspector's plain `TextareaControl` is gone; the text is edited inline inside the block preview via Gutenberg's `RichText` with `multiline: 'p'`. Pressing Enter produces a new `<p>`, Shift+Enter produces a `<br>`, and the `allowedFormats` list enables **bold** (`core/bold`), *italic* (`core/italic`), and inline links (`core/link`). Everything else from the floating formatting toolbar (headings, lists, code, inline images, etc.) is intentionally suppressed so the CTA text doesn't drift into full-block editing. A help line on the Heading field in the Inspector points users at the inline editor for the text content. PHP render is unchanged - `wp_kses_post( wpautop( $content ) )` already allows the new tags and `wpautop` is idempotent for content that's already wrapped in `<p>` tags, so legacy plain-text CTAs still render as before.

### Version 1.2.0 (2026-04-22)

- Fixed: **"Additional CSS classes" from the block editor's Advanced panel were missing on the frontend for every server-rendered custom block.** Originally reported against `prospero/cta`; the same bug affected every other `render_callback` in `inc/blocks.php` that built its wrapper markup manually. Editor previews were unaffected because each block's `edit()` uses `useBlockProps()`, which merges Gutenberg's implicit `className` attribute automatically - but the PHP render callbacks read only the block's own attributes and dropped `$attributes['className']` entirely. New shared helper `prospero_block_custom_classes( $attributes )` parses the Advanced-panel value, splits it on whitespace, runs each token through `sanitize_html_class()` (so nothing that doesn't reduce to a valid CSS class name gets emitted) and returns the survivors as an array. Helper is now used by: `prospero_render_cta_block`, `prospero_render_testimonial_single_block`, `prospero_render_testimonials_list_block`, `prospero_render_team_member_block`, `prospero_render_member_content_block` (all three output branches: not-logged-in, role-restricted, access-granted), `prospero_render_faq_single_block`, `prospero_render_faq_list_block`, `prospero_render_partners_list_block`, `prospero_render_team_list_block`, `prospero_render_projects_list_block`, `prospero_render_affiliate_link_block`, `prospero_render_partner_single_block`, and `prospero_render_project_single_block`. For the four list blocks that delegate to shortcodes (testimonials / partners / team / projects) the helper integrates with the existing optional align wrapper: a wrapping `<div>` is now emitted whenever align OR a custom class is set (previously only align triggered the wrap, and the projects list never wrapped at all). The four form blocks (`login-form`, `register-form`, `forgot-password-form`, `my-account`) declare `supports.customClassName: false` and are intentionally not fixed because they don't expose the field in the editor.

### Version 1.2.0 (2026-04-20)

- Removed: Page title (`.page-header` with `<h1 class="page-title">`) from `template-startpage.php`. Start pages are typically SEO-oriented with the visible content starting in a hero element or block that carries its own heading, so rendering the WP page title at the top of the template duplicated that heading. Also removed the orphaned `_prospero_hide_title` post-meta check that guarded the header - nothing in the theme (no meta box, Customizer control, or admin UI) ever set that meta, so the conditional was always true and amounted to dead code. Child themes that want the title back can override the template.

### Version 1.2.0 (2025-04-20)

- Changed: **AJAX filter refactor — pass query vars through instead of rebuilding them.** Both `prospero_filter_blog_ajax()` and `prospero_filter_projects_ajax()` previously hardcoded `posts_per_page`, `orderby`, and `order` in their own `WP_Query` args, duplicating the values used by the initial template render. The blog filter (`home.php`) now captures those three vars from `$wp_query->query_vars` after `pre_get_posts` has run and localises them into `prosperoBlogFilter.baseQueryVars`; `blog-filter.js` sends them as `base_query_vars` JSON with every AJAX request; the PHP handler JSON-decodes them and uses them as the query base (with an allowlist: `orderby` validated against WP-native values, `posts_per_page` capped at 100, `order` restricted to `ASC`/`DESC`, `post_type` and `post_status` hardcoded server-side). The projects filter (`template-projects.php`) follows the same philosophy via the DOM: the initial `WP_Query` args are defined once in `$projects_query_args` and both the query and three `data-*` attributes on `#projects-grid` are driven from that single variable; `projects-filter.js` reads them at runtime instead of hardcoding. Projects filter nonce moved from `data-ajax-nonce` on the first filter button to `prosperoAjax.nonce` (consistent with the blog filter pattern). Any `pre_get_posts` change to ordering or pagination count on the blog archive now flows through to filtered responses automatically.

### Version 1.2.0 (2025-04-19)

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
