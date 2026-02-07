# Shortcodes Guide

## Overview

The Prospero theme includes four shortcodes for displaying custom post types: Testimonials, Partners, Team, and Projects. All shortcodes support filtering, sorting, and responsive layouts.

## Available Shortcodes

### 1. Testimonials

Display customer testimonials with optional filtering and customization.

**Basic Usage**:
```
[testimonials]
```

**Parameters**:
- `category` - Filter by testimonial category slug (default: none, shows all)
- `count` - Number of testimonials to display (default: -1, shows all)
- `orderby` - Sort by: date, title, menu_order, rand (default: date)
- `order` - Sort order: ASC or DESC (default: DESC)

**Examples**:
```
[testimonials count="3"]
[testimonials category="customer-reviews" count="5"]
[testimonials orderby="rand" count="3"]
```

**Output**: Displays testimonials with circular thumbnail, quoted text, and author name.

---

### 2. Partners

Display partner logos in a responsive grid.

**Basic Usage**:
```
[partners]
```

**Parameters**:
- `category` - Filter by partner category slug (default: none, shows all)
- `count` - Number of partners to display (default: -1, shows all)
- `orderby` - Sort by: menu_order, title, date, rand (default: menu_order)
- `order` - Sort order: ASC or DESC (default: ASC)

**Examples**:
```
[partners]
[partners category="sponsors" count="8"]
[partners orderby="title" order="ASC"]
```

**Output**: Displays partner logos in a responsive grid with hover effects.

---

### 3. Team

Display team members in various layouts.

**Basic Usage**:
```
[team]
```

**Parameters**:
- `category` - Filter by team category slug (default: none, shows all)
- `count` - Number of team members to display (default: -1, shows all)
- `layout` - Layout style: grid, list, simple (default: grid)
- `columns` - Number of columns (1-6, default: 3)
- `orderby` - Sort by: menu_order, title, date, rand (default: menu_order)
- `order` - Sort order: ASC or DESC (default: ASC)

**Examples**:
```
[team layout="grid" columns="4"]
[team category="management" layout="list"]
[team layout="simple" columns="5"]
[team orderby="rand" count="6" columns="3"]
```

**Layouts**:
- **grid**: Image on top, name and excerpt below (default)
- **list**: Horizontal layout with image on left, content on right
- **simple**: Image and name only, no excerpt (compact)

**Output**: Responsive grid or list of team members with images, names, and optional excerpts.

---

### 4. Projects

Display project portfolio in a responsive grid.

**Basic Usage**:
```
[projects]
```

**Parameters**:
- `tags` - Filter by project tags (comma-separated slugs, default: none)
- `count` - Number of projects to display (default: 9)
- `orderby` - Sort by: date, title, menu_order, rand (default: date)
- `order` - Sort order: ASC or DESC (default: DESC)
- `columns` - Number of columns (1-6, default: 3)

**Examples**:
```
[projects columns="3"]
[projects tags="web-design,branding" count="6"]
[projects orderby="rand" count="12" columns="4"]
[projects tags="featured" columns="2"]
```

**Output**: Grid of project cards with featured images, titles, excerpts, and "View Project" buttons.

---

## Usage in Pages

### Adding to Page Content

1. Edit any page in WordPress
2. Switch to "Text" or "Code" editor
3. Add shortcode where you want content to appear:
   ```
   <h2>What Our Customers Say</h2>
   [testimonials count="3"]
   
   <h2>Our Partners</h2>
   [partners category="sponsors"]
   ```

### Using in Gutenberg

1. Add a "Shortcode" block
2. Type or paste the shortcode
3. Preview to see results

### Using in Widgets

1. Go to Appearance → Widgets
2. Add "Text" or "HTML" widget
3. Add shortcode in widget content

### Using in Templates

```php
<?php echo do_shortcode( '[team layout="grid" columns="4"]' ); ?>
```

---

## Styling & Customization

### Responsive Behavior

**Desktop (> 768px)**:
- Testimonials: Stacked vertically
- Partners: Auto-fit grid (150px minimum)
- Team: Grid with specified columns
- Projects: Grid with specified columns

**Tablet (481px - 768px)**:
- Team & Projects with 3+ columns: Force 2 columns
- Partners: Smaller grid cells (100px minimum)
- List layouts: Stack vertically

**Mobile (≤ 480px)**:
- All grids: Single column
- Partners: 2 columns only

### Dark Mode Support

All shortcodes automatically adapt to dark mode with:
- Adjusted background colors
- Proper text contrast
- Modified hover states
- Optimized image blend modes (partners)

### Custom CSS

To override styles, add custom CSS in **Appearance → Customize → Additional CSS**:

```css
/* Make testimonials smaller */
.testimonial-text {
	font-size: 1rem;
}

/* Change partner logo size */
.partner-thumbnail {
	max-width: 150px;
}

/* Adjust team card spacing */
.prospero-team-grid {
	gap: 2rem;
}

/* Customize project cards */
.project-item {
	border: 2px solid var(--color-primary);
}
```

---

## Testing Instructions

### 1. Test Testimonials

**Setup**:
1. Go to WordPress Admin → Testimonials
2. Add 3-5 testimonials with:
   - Title (person's name)
   - Content (the testimonial text)
   - Featured image (person's photo)
3. Assign some to a category

**Tests**:
```
[testimonials]
[testimonials count="2"]
[testimonials category="customer-reviews"]
[testimonials orderby="rand" count="3"]
```

**Verify**:
- Circular thumbnail displays
- Quote marks appear
- Author name shows below quote
- Responsive on mobile

---

### 2. Test Partners

**Setup**:
1. Go to WordPress Admin → Partners
2. Add 6-8 partners with:
   - Title (company name)
   - Featured image (company logo - PNG with transparency works best)
3. Assign some to a category
4. Set "Order" attribute for custom sorting

**Tests**:
```
[partners]
[partners count="6"]
[partners category="sponsors"]
[partners orderby="title"]
```

**Verify**:
- Logos display in grid
- Hover effects work
- Mix-blend-mode works in light/dark modes
- Links work if permalinks are set

---

### 3. Test Team

**Setup**:
1. Go to WordPress Admin → Team
2. Add 6-9 team members with:
   - Title (member name)
   - Content/Excerpt (bio)
   - Featured image (profile photo)
3. Assign some to categories
4. Set "Order" attribute for custom sorting

**Tests**:
```
[team]
[team layout="grid" columns="4"]
[team layout="list"]
[team layout="simple" columns="5"]
[team category="management" layout="list"]
```

**Verify**:
- Grid layout works with different column counts
- List layout displays horizontally
- Simple layout shows name only
- Images have hover zoom effect
- Responsive behavior on tablet/mobile

---

### 4. Test Projects

**Setup**:
1. Go to WordPress Admin → Projects
2. Add 9-12 projects with:
   - Title (project name)
   - Content/Excerpt (project description)
   - Featured image (project thumbnail - 16:9 ratio recommended)
3. Add project tags

**Tests**:
```
[projects]
[projects columns="2"]
[projects columns="4"]
[projects tags="web-design" count="6"]
[projects orderby="rand" count="6"]
```

**Verify**:
- Grid displays correctly
- 16:9 aspect ratio maintained
- "View Project" button works
- Card hover effects work
- Tag filtering works
- Responsive on mobile (single column)

---

## Common Issues & Solutions

### Issue: Shortcode displays as text
**Solution**: Make sure you're using the correct syntax `[shortcode]` not `{shortcode}` or `(shortcode)`. In Gutenberg, use the Shortcode block, not a Paragraph block.

### Issue: No posts displaying
**Possible Causes**:
1. Post type is disabled in Customizer (Appearance → Customize → Post Types)
2. No posts have been published
3. Category/tag filter doesn't match any posts
4. Count is set to 0

**Solution**: Check Customizer settings, verify posts are published, check filter values.

### Issue: Layout looks broken
**Possible Causes**:
1. Theme CSS not loaded
2. Conflicting plugin CSS
3. Custom CSS override

**Solution**: Clear cache, check for CSS conflicts in browser DevTools, verify `shortcodes.css` is enqueued.

### Issue: Images not displaying
**Possible Causes**:
1. No featured image set
2. Image permissions issue
3. Image file missing

**Solution**: Set featured images in post editor, check file permissions, re-upload images.

---

## Performance Tips

1. **Limit Count**: Use `count` parameter to limit queries:
   ```
   [testimonials count="5"]  // Better than showing all
   ```

2. **Use Caching**: If using many shortcodes, consider a caching plugin.

3. **Optimize Images**: Compress images before uploading, especially for:
   - Partners: Logos should be small (< 100KB)
   - Team: Profiles at medium size (500x500px)
   - Projects: Thumbnails at 1200x675px (16:9)

4. **Avoid Random on Every Load**: `orderby="rand"` queries are slower. Use sparingly.

---

## Accessibility

All shortcodes follow accessibility best practices:

- **Semantic HTML**: Proper heading hierarchy, article tags, blockquote for testimonials
- **Alt Text**: Uses post titles for image alt attributes
- **Keyboard Navigation**: All links are keyboard accessible
- **Focus Indicators**: Visible focus states on all interactive elements
- **Screen Readers**: Proper labeling and ARIA attributes
- **Color Contrast**: WCAG AA compliant in both light and dark modes

---

## Future Enhancements (Optional)

- [ ] Add Flickity carousel/slider integration
- [ ] Add Ajax filtering for projects
- [ ] Add lightbox for team member details
- [ ] Add pagination for large result sets
- [ ] Add load more button
- [ ] Add custom templates support
- [ ] Add schema.org markup for testimonials
- [ ] Add social media links for team members
- [ ] Add project categories filter UI
- [ ] Add masonry layout option

---

## Code Files

**PHP**: `prospero-theme/inc/shortcodes.php` (323 lines)
**CSS**: `prospero-theme/assets/css/shortcodes.css` (420 lines)
**Enqueue**: `prospero-theme/functions.php` (line 109)

---

## Support

For issues or questions:
1. Check this guide first
2. Verify post type is enabled in Customizer
3. Clear all caches (browser, WordPress, CDN)
4. Check browser console for JavaScript errors
5. Test with a default WordPress theme to isolate theme-specific issues
