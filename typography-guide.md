# Typography & Google Fonts Local Hosting

## Overview

The Prospero theme includes automatic local hosting of Google Fonts for GDPR compliance. Fonts are downloaded once and stored locally, eliminating external requests to Google's servers.

## How It Works

### 1. Font Selection
Users can select fonts via **Appearance → Customize → Typography**:
- **Heading Font**: Used for h1-h6 elements
- **Body Font**: Used for paragraphs and body text
- **System Fonts**: Default option, uses native system fonts (no download needed)

### 2. Automatic Download Process
When a Google Font is selected:
1. On the next page load, the theme checks if the font exists locally
2. If not found, it automatically:
   - Fetches the font CSS from Google Fonts API
   - Downloads all font file variants (woff2, woff, etc.)
   - Saves files to `prospero-theme/assets/fonts/`
   - Rewrites CSS URLs to point to local files
3. Font CSS is enqueued on subsequent page loads
4. Admin notice appears to inform about pending downloads

### 3. Font Weights Included
The system downloads these font weights: 300, 400, 500, 600, 700
- Covers light, regular, medium, semi-bold, and bold variants
- Sufficient for most design needs

## File Structure

```
prospero-theme/assets/fonts/
├── .gitkeep                      # Preserves directory in Git
├── inter.css                     # Font CSS (if Inter is selected)
├── [font-files].woff2            # Font files (various formats)
└── ...                           # Additional fonts as selected
```

**Note**: Font files are `.gitignore`d as they can be regenerated automatically.

## Customizer Settings

### Path
**Appearance → Customize → Typography**

### Fields

**Heading Font** (text input)
- Default: `system`
- Enter Google Font name (e.g., `Inter`, `Montserrat`, `Playfair Display`)
- Case-sensitive, must match exact Google Font name
- Or enter `system` to use system fonts

**Body Font** (text input)
- Default: `system`
- Same rules as Heading Font
- Can be same as or different from Heading Font

## Testing Instructions

### Basic Font Selection

1. Go to **Appearance → Customize → Typography**
2. **Heading Font**: Enter `Montserrat`
3. **Body Font**: Enter `Open Sans`
4. Click **Publish**
5. Admin notice should appear: "Google Fonts will be downloaded locally on the next page load..."
6. Visit the frontend (any page)
7. Fonts are downloaded automatically
8. Refresh to see fonts applied
9. Check `prospero-theme/assets/fonts/` directory:
   - Should contain `montserrat.css` and font files
   - Should contain `open-sans.css` and font files

### System Fonts

1. Set both fonts to `system`
2. Verify no external requests in Network tab
3. Verify fonts use system stack:
   ```
   -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif
   ```

### Same Font for Both

1. **Heading Font**: `Inter`
2. **Body Font**: `Inter`
3. Verify only one font CSS file is loaded (not duplicated)

### Font Names with Spaces

1. **Heading Font**: `Playfair Display`
2. Verify it downloads correctly
3. Check CSS output wraps font name in quotes: `"Playfair Display"`

### Performance Check

1. Open browser DevTools → Network tab
2. Filter by "Fonts"
3. Verify:
   - No requests to `fonts.googleapis.com` or `fonts.gstatic.com`
   - All font files load from `/wp-content/themes/prospero-theme/assets/fonts/`
4. Check page load time - should be fast with local fonts

## Popular Google Fonts Reference

The theme includes a reference list of popular fonts (see `prospero_get_popular_fonts()` in `inc/typography.php`):

### Sans-Serif
- **Inter** - Modern, highly legible
- **Roboto** - Material Design standard
- **Open Sans** - Friendly, clean
- **Lato** - Warm, professional
- **Montserrat** - Geometric, elegant
- **Poppins** - Geometric, modern
- **Raleway** - Elegant, thin
- **Source Sans Pro** - Professional, clean
- **Nunito** - Rounded, friendly
- **Ubuntu** - Humanist, clear
- **Oswald** - Condensed, bold

### Serif
- **Playfair Display** - Elegant, high contrast
- **Merriweather** - Classic, readable
- **Lora** - Balanced, versatile
- **PT Serif** - Traditional, universal

## GDPR Compliance

### What Makes This GDPR-Compliant

1. **No External Requests**: Fonts served from theme directory, not Google servers
2. **No User Tracking**: Google cannot track users via font loading
3. **No Cookies**: No third-party cookies from Google
4. **Local Control**: All font files under site owner's control
5. **No IP Logging**: User IPs not sent to Google

### Legal Notes

- Google Fonts are open-source (SIL Open Font License)
- Local hosting is explicitly permitted
- No attribution required in user-facing content
- Font files themselves are not committed to Git (regenerated automatically)

## Technical Details

### Font Stack Fallbacks

Every Google Font includes system font fallbacks:
```css
"Font Name", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif
```

This ensures:
- Fonts display even if download fails
- Content is readable immediately (before fonts load)
- Graceful degradation on older browsers

### CSS Custom Properties

Fonts are applied via CSS custom properties:
```css
:root {
  --font-family-base: "Open Sans", ...fallbacks;
  --font-family-heading: "Montserrat", ...fallbacks;
}

body {
  font-family: var(--font-family-base);
}

h1, h2, h3, h4, h5, h6 {
  font-family: var(--font-family-heading);
}
```

### File Naming Convention

Font CSS files are named using WordPress `sanitize_title()`:
- `Inter` → `inter.css`
- `Open Sans` → `open-sans.css`
- `Playfair Display` → `playfair-display.css`

### Security

- Font names sanitized with `sanitize_text_field()` and regex
- Only alphanumeric characters, spaces, hyphens, and plus signs allowed
- Uses WordPress HTTP API (`wp_remote_get()`) for secure downloads
- Uses WP_Filesystem API for file operations
- Proper file permissions set (`FS_CHMOD_FILE`)

## Troubleshooting

### Font Not Downloading

**Issue**: Admin notice appears but font doesn't download.

**Possible Causes**:
1. **Incorrect font name**: Check exact spelling on [Google Fonts](https://fonts.google.com/)
2. **File permissions**: Check `prospero-theme/assets/fonts/` is writable
3. **HTTP timeout**: Server couldn't reach Google Fonts API
4. **WP_Filesystem issue**: Hosting doesn't support WP_Filesystem

**Solutions**:
- Verify exact Google Font name (case-sensitive)
- Check directory permissions: `chmod 755 prospero-theme/assets/fonts/`
- Check server can make outbound HTTP requests
- Contact hosting if WP_Filesystem not working

### Font Not Displaying

**Issue**: Font downloaded but not showing on frontend.

**Debugging Steps**:
1. Check browser DevTools → Network tab for 404 errors
2. Verify CSS file exists: `prospero-theme/assets/fonts/[font-slug].css`
3. Check CSS file content - should have local URLs, not Google URLs
4. Clear browser cache
5. Clear WordPress cache if using caching plugin
6. Check CSS custom properties in DevTools:
   ```
   :root {
     --font-family-base: ...
     --font-family-heading: ...
   }
   ```

### Admin Notice Won't Dismiss

**Issue**: Notice keeps appearing even after font downloaded.

**Cause**: Font file exists but CSS file doesn't (incomplete download).

**Solution**:
1. Delete files from `prospero-theme/assets/fonts/`
2. Reload admin page
3. Visit frontend to trigger re-download

## Browser Support

### Modern Browsers
- ✅ Chrome 36+
- ✅ Firefox 39+
- ✅ Safari 10+
- ✅ Edge 14+

### Font Formats
- **WOFF2**: Primary format (best compression)
- **WOFF**: Fallback for older browsers
- **TTF/OTF**: Further fallback if needed

### System Fonts
Always available as fallback, even on ancient browsers.

## Performance Considerations

### Advantages of Local Hosting
- **Faster**: No DNS lookup to Google servers
- **Reliable**: No dependency on external service
- **Cacheable**: Fonts cache with theme assets
- **No CORS**: No cross-origin issues

### File Sizes
- Single font family with 5 weights: ~100-300KB (WOFF2)
- System fonts: 0KB (already on user's device)

### Optimization Tips
1. Use system fonts when possible (0KB)
2. Limit to 2 font families maximum
3. Only use weights you actually need (customize in `typography.php`)
4. Consider font-display: swap for faster rendering

## Code Files

### Core Implementation
- **`inc/typography.php`** - Main font loading logic
  - `prospero_download_google_font()` - Downloads and converts fonts
  - `prospero_enqueue_fonts()` - Enqueues font CSS files
  - `prospero_add_font_css()` - Outputs CSS custom properties
  - `prospero_get_font_stack()` - Builds font stacks with fallbacks

### Integration Points
- **`functions.php`** - Includes `typography.php`
- **`inc/customizer.php`** - Typography section settings (lines 247-275)
- **`assets/css/main.css`** - Uses CSS custom properties

### Git Configuration
- **`.gitignore`** - Excludes downloaded font files
- **`assets/fonts/.gitkeep`** - Preserves directory in repository

## Future Enhancements (Optional)

- [ ] Add font preview in Customizer
- [ ] Add dropdown with popular fonts
- [ ] Add font-display customization option
- [ ] Add selective weight loading (checkboxes for specific weights)
- [ ] Add variable font support
- [ ] Add font subsetting for specific character sets
- [ ] Add manual font upload option (for commercial fonts)
- [ ] Add font pairing suggestions
