<?php
/**
 * Template Functions
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display custom logo with dark mode support.
 * Shows both light and dark logos, CSS handles visibility based on mode.
 */
function prospero_custom_logo() {
	$site_name   = get_bloginfo( 'name' );
	$home_url    = home_url( '/' );
	$dark_logo_id = get_theme_mod( 'prospero_dark_logo' );
	
	// Light mode logo
	$light_logo_html = '';
	if ( has_custom_logo() ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo_image     = wp_get_attachment_image( $custom_logo_id, 'full', false, array(
			'class' => 'custom-logo',
			'alt'   => esc_attr( $site_name ),
		) );
		$light_logo_html = sprintf(
			'<a href="%1$s" class="custom-logo-link logo-light" rel="home">%2$s</a>',
			esc_url( $home_url ),
			$logo_image
		);
	} elseif ( file_exists( PROSPERO_THEME_DIR . '/logo.svg' ) ) {
		$light_logo_html = sprintf(
			'<a href="%1$s" class="custom-logo-link logo-light" rel="home"><img src="%2$s" class="custom-logo" alt="%3$s" /></a>',
			esc_url( $home_url ),
			esc_url( PROSPERO_THEME_URI . '/logo.svg' ),
			esc_attr( $site_name )
		);
	}
	
	// Dark mode logo
	$dark_logo_html = '';
	if ( $dark_logo_id ) {
		// Custom dark logo uploaded
		$dark_logo_image = wp_get_attachment_image( $dark_logo_id, 'full', false, array(
			'class' => 'custom-logo',
			'alt'   => esc_attr( $site_name ),
		) );
		$dark_logo_html = sprintf(
			'<a href="%1$s" class="custom-logo-link logo-dark" rel="home">%2$s</a>',
			esc_url( $home_url ),
			$dark_logo_image
		);
	} elseif ( file_exists( PROSPERO_THEME_DIR . '/logo-dark.svg' ) ) {
		// Fallback to logo-dark.svg
		$dark_logo_html = sprintf(
			'<a href="%1$s" class="custom-logo-link logo-dark" rel="home"><img src="%2$s" class="custom-logo" alt="%3$s" /></a>',
			esc_url( $home_url ),
			esc_url( PROSPERO_THEME_URI . '/logo-dark.svg' ),
			esc_attr( $site_name )
		);
	}
	
	// Output both logos (CSS controls visibility)
	echo $light_logo_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo $dark_logo_html;  // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Add body classes
 */
function prospero_body_classes( $classes ) {
	// Add dark mode class if enabled
	$default_mode = get_theme_mod( 'prospero_default_mode', 'light' );
	if ( $default_mode === 'dark' ) {
		$classes[] = 'dark-mode';
	}
	
	// Add hamburger menu class
	if ( get_theme_mod( 'prospero_hamburger_menu', false ) ) {
		$classes[] = 'has-hamburger-menu';
	}
	
	// Add sticky menu class
	if ( get_theme_mod( 'prospero_sticky_menu', false ) ) {
		$classes[] = 'has-sticky-menu';
	}
	
	return $classes;
}
add_filter( 'body_class', 'prospero_body_classes' );

/**
 * Calculate relative luminance of a color for contrast calculations
 *
 * @param string $hex_color Hex color code.
 * @return float Relative luminance (0-1).
 */
function prospero_get_luminance( $hex_color ) {
	// Remove # if present
	$hex = ltrim( $hex_color, '#' );
	
	// Handle shorthand hex (e.g., #fff)
	if ( strlen( $hex ) === 3 ) {
		$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
	}
	
	// Convert to RGB
	$r = hexdec( substr( $hex, 0, 2 ) ) / 255;
	$g = hexdec( substr( $hex, 2, 2 ) ) / 255;
	$b = hexdec( substr( $hex, 4, 2 ) ) / 255;
	
	// Apply gamma correction
	$r = $r <= 0.03928 ? $r / 12.92 : pow( ( $r + 0.055 ) / 1.055, 2.4 );
	$g = $g <= 0.03928 ? $g / 12.92 : pow( ( $g + 0.055 ) / 1.055, 2.4 );
	$b = $b <= 0.03928 ? $b / 12.92 : pow( ( $b + 0.055 ) / 1.055, 2.4 );
	
	// Calculate luminance
	return 0.2126 * $r + 0.7152 * $g + 0.0722 * $b;
}

/**
 * Get a contrasting text color (black or white) for a given background
 *
 * @param string $bg_color Background hex color.
 * @return string '#1a1a1a' for light backgrounds, '#ffffff' for dark backgrounds.
 */
function prospero_get_contrast_text_color( $bg_color ) {
	$luminance = prospero_get_luminance( $bg_color );

	// Use dark text for light backgrounds (luminance > 0.5)
	// Use white text for dark backgrounds
	return $luminance > 0.5 ? '#1a1a1a' : '#ffffff';
}

/**
 * Parse any CSS color string the Customizer might hand us (hex like
 * `#fff` / `#ffffff`, or rgb/rgba like `rgba(0, 0, 0, 1)`) into a
 * 0-255 RGB triplet. Returns null if the color cannot be parsed (e.g.
 * `transparent`, `currentColor`, named colors).
 *
 * @param string $color Color string.
 * @return int[]|null [ r, g, b ] or null.
 */
function prospero_parse_color_to_rgb( $color ) {
	$color = trim( (string) $color );

	if ( '' === $color || 'transparent' === strtolower( $color ) ) {
		return null;
	}

	// Hex: #abc or #aabbcc
	if ( preg_match( '/^#([a-f0-9]{3}|[a-f0-9]{6})$/i', $color, $m ) ) {
		$hex = $m[1];
		if ( strlen( $hex ) === 3 ) {
			$hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
		}

		return array(
			(int) hexdec( substr( $hex, 0, 2 ) ),
			(int) hexdec( substr( $hex, 2, 2 ) ),
			(int) hexdec( substr( $hex, 4, 2 ) ),
		);
	}

	// rgb() / rgba()
	if ( preg_match( '/rgba?\s*\(\s*(\d+)\s*,\s*(\d+)\s*,\s*(\d+)/i', $color, $m ) ) {
		return array( (int) $m[1], (int) $m[2], (int) $m[3] );
	}

	return null;
}

/**
 * Calculate the WCAG 2.1 contrast ratio between two colors.
 *
 * If either color cannot be parsed (or is transparent), returns the
 * maximum ratio (21) so downstream checks treat the pairing as "safe"
 * and do not apply a fallback.
 *
 * @param string $color_a First color.
 * @param string $color_b Second color.
 * @return float Ratio in the range 1.0 to 21.0.
 */
function prospero_contrast_ratio( $color_a, $color_b ) {
	$rgb_a = prospero_parse_color_to_rgb( $color_a );
	$rgb_b = prospero_parse_color_to_rgb( $color_b );

	if ( ! $rgb_a || ! $rgb_b ) {
		return 21.0;
	}

	$luminance = function ( $rgb ) {
		$channels = array();
		foreach ( $rgb as $c ) {
			$c = $c / 255;
			$channels[] = $c <= 0.03928 ? $c / 12.92 : pow( ( $c + 0.055 ) / 1.055, 2.4 );
		}

		return 0.2126 * $channels[0] + 0.7152 * $channels[1] + 0.0722 * $channels[2];
	};

	$l_a     = $luminance( $rgb_a );
	$l_b     = $luminance( $rgb_b );
	$lighter = max( $l_a, $l_b );
	$darker  = min( $l_a, $l_b );

	return ( $lighter + 0.05 ) / ( $darker + 0.05 );
}

/**
 * The contrast-ratio threshold below which the button's configured
 * accent is considered "too close" to the target page background and
 * the safety-net fallback kicks in.
 *
 * Two tiers:
 *
 * - Default (designer-friendly): 1.5:1. Only swap when the button
 *   color is nearly indistinguishable from the page background. This
 *   lets a designer pick colors that don't meet WCAG 1.4.11's strict
 *   3:1 non-text-UI threshold but are still visually obviously
 *   different from the page (e.g. a muted red on a dark slate bg).
 *
 * - Strict WCAG AA: 3.0:1. Enforces WCAG 1.4.11 (Non-text Contrast)
 *   for any accessibility-first site. Opt-in via the Customizer
 *   setting `prospero_button_enforce_wcag_contrast`.
 *
 * Exposed through the `prospero_button_contrast_threshold` filter
 * so a child theme can force an exact value if neither preset fits.
 *
 * @return float Contrast ratio threshold (1.0 – 21.0).
 */
function prospero_get_button_contrast_threshold() {
	$strict = (bool) get_theme_mod( 'prospero_button_enforce_wcag_contrast', false );
	$value  = $strict ? 3.0 : 1.5;

	/**
	 * Filters the contrast-ratio threshold for the button safety net.
	 *
	 * @param float $value  Current threshold (1.5 default, 3.0 with WCAG mode on).
	 * @param bool  $strict Whether WCAG strict mode is enabled.
	 */
	return (float) apply_filters( 'prospero_button_contrast_threshold', $value, $strict );
}

/**
 * Build the map of button CSS custom properties for a single button role
 * (primary / secondary / tertiary / menu-cta) based on the current
 * Customizer settings.
 *
 * Outline buttons get a transparent background and borrow the configured
 * background color for text and border, which produces a border-only
 * appearance until hover.
 *
 * Accessibility safety net: if the user picks a button color whose
 * contrast against the target mode's page background is below
 * `prospero_get_button_contrast_threshold()` (1.5:1 by default, 3:1
 * with WCAG AA mode enabled), the button's visible accent (border/text
 * for outline; background for flat) is swapped for the mode's page
 * text color so the button stays readable. This is applied per-mode,
 * so a color that works in light mode is untouched in light mode and
 * only substituted in dark mode (or vice-versa).
 *
 * @param string $prefix Variable prefix (primary|secondary|tertiary|menu-cta).
 * @param string $mode   Target page mode (`light` or `dark`). Default `light`.
 * @return array Map of CSS custom property name => value.
 */
function prospero_get_button_css_vars_for( $prefix, $mode = 'light' ) {
	// Per-role defaults mirror the values declared in inc/customizer.php.
	$defaults = array(
		'primary'   => array(
			'style' => 'flat',
			'bg'    => 'rgba(0, 123, 255, 1)',
			'text'  => 'rgba(255, 255, 255, 1)',
		),
		'secondary' => array(
			'style' => 'outline',
			'bg'    => 'rgba(108, 117, 125, 1)',
			'text'  => 'rgba(255, 255, 255, 1)',
		),
		'tertiary'  => array(
			'style' => 'flat',
			'bg'    => 'rgba(40, 167, 69, 1)',
			'text'  => 'rgba(255, 255, 255, 1)',
		),
		'menu-cta'  => array(
			'style' => 'flat',
			'bg'    => 'rgba(0, 123, 255, 1)',
			'text'  => 'rgba(255, 255, 255, 1)',
		),
	);

	if ( ! isset( $defaults[ $prefix ] ) ) {
		return array();
	}

	$style      = get_theme_mod( "prospero_{$prefix}_btn_style", $defaults[ $prefix ]['style'] );
	$bg         = get_theme_mod( "prospero_{$prefix}_btn_bg", $defaults[ $prefix ]['bg'] );
	$text       = get_theme_mod( "prospero_{$prefix}_btn_text", $defaults[ $prefix ]['text'] );
	$hover_bg   = get_theme_mod( "prospero_{$prefix}_btn_hover_bg", '' );
	$hover_text = get_theme_mod( "prospero_{$prefix}_btn_hover_text", '' );
	$font_style = get_theme_mod( "prospero_{$prefix}_btn_font_style", 'none' );
	$radius     = get_theme_mod( "prospero_{$prefix}_btn_radius", '4px' );

	// Page background / text colors for the requested mode, used as the
	// contrast reference and as the safe-fallback accent.
	$page_bg = 'dark' === $mode
		? get_theme_mod( 'prospero_dark_background_color', '#2b2a33' )
		: get_theme_mod( 'prospero_background_color', '#ffffff' );
	$page_text = 'dark' === $mode
		? get_theme_mod( 'prospero_dark_text_color', '#f7f7f7' )
		: get_theme_mod( 'prospero_text_color', '#333333' );

	$too_close = prospero_contrast_ratio( $bg, $page_bg ) < prospero_get_button_contrast_threshold();

	if ( 'outline' === $style ) {
		$resolved_bg         = 'transparent';
		$resolved_text       = $bg;
		$resolved_border     = $bg;
		$resolved_hover_bg   = ! empty( $hover_bg ) ? $hover_bg : $bg;
		$resolved_hover_text = ! empty( $hover_text ) ? $hover_text : $text;

		if ( $too_close ) {
			// The outline accent would disappear against the page bg in
			// this mode. Substitute the mode's page text color so the
			// button stays readable and keeps its "outline" silhouette.
			$resolved_text   = $page_text;
			$resolved_border = $page_text;

			if ( empty( $hover_bg ) ) {
				$resolved_hover_bg = $page_text;
			}
			if ( empty( $hover_text ) ) {
				// Inverse of the hover bg for legible hover text.
				$resolved_hover_text = $page_bg;
			}
		}
	} else {
		$resolved_bg         = $bg;
		$resolved_text       = $text;
		$resolved_border     = $bg;
		$resolved_hover_bg   = ! empty( $hover_bg )
			? $hover_bg
			: 'color-mix(in srgb, ' . $bg . ' 80%, black)';
		$resolved_hover_text = ! empty( $hover_text ) ? $hover_text : $text;

		if ( $too_close ) {
			// Flat button matches the page - the whole button would
			// vanish except for its text. Swap to page text color so
			// the fill is visible, with page bg as the text color.
			$resolved_bg         = $page_text;
			$resolved_border     = $page_text;
			$resolved_text       = $page_bg;
			if ( empty( $hover_bg ) ) {
				$resolved_hover_bg = 'color-mix(in srgb, ' . $page_text . ' 80%, black)';
			}
			if ( empty( $hover_text ) ) {
				$resolved_hover_text = $page_bg;
			}
		}
	}

	$text_transform = $font_style === 'uppercase' ? 'uppercase' : 'none';
	$letter_spacing = $font_style === 'uppercase' ? '0.5px' : 'normal';

	return array(
		"--prospero-btn-{$prefix}-bg"              => $resolved_bg,
		"--prospero-btn-{$prefix}-text"            => $resolved_text,
		"--prospero-btn-{$prefix}-border"          => $resolved_border,
		"--prospero-btn-{$prefix}-radius"          => $radius,
		"--prospero-btn-{$prefix}-hover-bg"        => $resolved_hover_bg,
		"--prospero-btn-{$prefix}-hover-text"      => $resolved_hover_text,
		"--prospero-btn-{$prefix}-text-transform"  => $text_transform,
		"--prospero-btn-{$prefix}-letter-spacing"  => $letter_spacing,
	);
}

/**
 * Build the combined map of all button CSS custom properties for the
 * given page mode.
 *
 * @param string $mode `light` (default) or `dark`.
 * @return array Map of CSS custom property name => value.
 */
function prospero_get_button_css_vars( $mode = 'light' ) {
	return array_merge(
		prospero_get_button_css_vars_for( 'primary', $mode ),
		prospero_get_button_css_vars_for( 'secondary', $mode ),
		prospero_get_button_css_vars_for( 'tertiary', $mode ),
		prospero_get_button_css_vars_for( 'menu-cta', $mode )
	);
}

/**
 * Render a set of :root CSS custom properties as a CSS string.
 *
 * @param array $vars Map of variable name => value.
 * @return string CSS declarations (without surrounding `:root { }`).
 */
function prospero_render_css_vars( $vars ) {
	$out = '';
	foreach ( $vars as $var_name => $var_value ) {
		$out .= $var_name . ': ' . esc_attr( $var_value ) . ';';
	}
	return $out;
}

/**
 * Generate dynamic CSS from customizer settings (frontend).
 */
function prospero_dynamic_css() {
	$primary_color     = get_theme_mod( 'prospero_primary_color', '#007bff' );
	$secondary_color   = get_theme_mod( 'prospero_secondary_color', '#6c757d' );
	$tertiary_color    = get_theme_mod( 'prospero_tertiary_color', '#28a745' );
	$text_color        = get_theme_mod( 'prospero_text_color', '#333333' );
	$dark_text_color   = get_theme_mod( 'prospero_dark_text_color', '#f7f7f7' );
	$highlight_color   = get_theme_mod( 'prospero_highlight_color', '#ffc107' );
	$bg_color          = get_theme_mod( 'prospero_background_color', '#ffffff' );
	$dark_bg_color     = get_theme_mod( 'prospero_dark_background_color', '#2b2a33' );

	// Calculate contrast text color for highlight (sticky label)
	$highlight_text_color = prospero_get_contrast_text_color( $highlight_color );

	// Layout
	$content_width = get_theme_mod( 'prospero_content_width', '1200px' );

	$css  = ':root {';
	$css .= '--content-width: ' . esc_attr( $content_width ) . ';';
	// Theme colors
	$css .= '--color-primary: ' . esc_attr( $primary_color ) . ';';
	$css .= '--color-secondary: ' . esc_attr( $secondary_color ) . ';';
	$css .= '--color-tertiary: ' . esc_attr( $tertiary_color ) . ';';
	$css .= '--color-text: ' . esc_attr( $text_color ) . ';';
	$css .= '--color-text-dark: ' . esc_attr( $dark_text_color ) . ';';
	$css .= '--color-highlight: ' . esc_attr( $highlight_color ) . ';';
	$css .= '--color-background: ' . esc_attr( $bg_color ) . ';';
	$css .= '--color-background-dark: ' . esc_attr( $dark_bg_color ) . ';';
	$css .= '--color-sticky-label-text: ' . esc_attr( $highlight_text_color ) . ';';

	// Prospero-prefixed aliases (kept for backwards compatibility / theme.json).
	$css .= '--prospero-primary: ' . esc_attr( $primary_color ) . ';';
	$css .= '--prospero-secondary: ' . esc_attr( $secondary_color ) . ';';
	$css .= '--prospero-tertiary: ' . esc_attr( $tertiary_color ) . ';';

	// Button CSS custom properties (light-mode flavour) shared with the
	// block editor.
	$light_button_vars = prospero_get_button_css_vars( 'light' );
	$css .= prospero_render_css_vars( $light_button_vars );

	$css .= '}';

	// Emit dark-mode overrides only for button variables that actually
	// differ from light mode (i.e. the contrast fallback kicked in for
	// one of the roles). Keeps the output minimal when everything's fine.
	$dark_button_vars  = prospero_get_button_css_vars( 'dark' );
	$dark_overrides    = array();
	foreach ( $dark_button_vars as $var_name => $var_value ) {
		if ( ! array_key_exists( $var_name, $light_button_vars ) || $light_button_vars[ $var_name ] !== $var_value ) {
			$dark_overrides[ $var_name ] = $var_value;
		}
	}
	if ( ! empty( $dark_overrides ) ) {
		$css .= 'body.dark-mode {';
		$css .= prospero_render_css_vars( $dark_overrides );
		$css .= '}';
	}

	echo '<style type="text/css" id="prospero-dynamic-css">' . $css . '</style>';
}
add_action( 'wp_head', 'prospero_dynamic_css', 99 );

/**
 * Calculate reading time for content
 *
 * @param string $content The post content.
 * @return int Reading time in minutes.
 */
function prospero_get_reading_time( $content ) {
	$word_count = str_word_count( wp_strip_all_tags( $content ) );
	$reading_time = ceil( $word_count / 200 ); // Average reading speed: 200 words per minute
	return max( 1, $reading_time ); // Minimum 1 minute
}

/**
 * Group FAQ posts by their primary `faq_category` term.
 *
 * Each FAQ with one or more terms is placed under its first term
 * (WP's natural menu_order / alphabetical pick). FAQs without a
 * category fall into a final "uncategorised" group that is rendered
 * under the "Uncategorised" heading. Non-empty groups are returned
 * in alphabetical order of their term name.
 *
 * The returned shape keeps term objects available so the caller can
 * render links back to the taxonomy page for each group.
 *
 * @param WP_Post[] $faqs Array of FAQ posts.
 * @return array<int, array{term:WP_Term|null, posts:WP_Post[]}>
 */
function prospero_group_faqs_by_category( $faqs ) {
	$groups        = array();
	$uncategorised = array();

	foreach ( $faqs as $faq ) {
		$terms = get_the_terms( $faq->ID, 'faq_category' );

		if ( $terms && ! is_wp_error( $terms ) ) {
			// Use the alphabetically-first term as the "primary" bucket
			// so grouping is stable even when get_the_terms() order varies.
			usort( $terms, static function ( $a, $b ) {
				return strcasecmp( $a->name, $b->name );
			} );
			$primary = $terms[0];

			if ( ! isset( $groups[ $primary->term_id ] ) ) {
				$groups[ $primary->term_id ] = array(
					'term'  => $primary,
					'posts' => array(),
				);
			}
			$groups[ $primary->term_id ]['posts'][] = $faq;
		} else {
			$uncategorised[] = $faq;
		}
	}

	// Stable alphabetical order for the category groups.
	$groups = array_values( $groups );
	usort( $groups, static function ( $a, $b ) {
		return strcasecmp( $a['term']->name, $b['term']->name );
	} );

	if ( ! empty( $uncategorised ) ) {
		$groups[] = array(
			'term'  => null,
			'posts' => $uncategorised,
		);
	}

	return $groups;
}

/**
 * The theme's configured auto-excerpt length in words.
 *
 * Exposed through a dedicated filter (`prospero_excerpt_length`) so
 * child themes can override the value in a single place and both
 * the frontend archive and the AJAX blog-filter response stay in sync.
 *
 * @return int Word count cap for auto-generated excerpts.
 */
function prospero_get_excerpt_length() {
	$length = 30;

	/**
	 * Filters the theme's auto-excerpt word count.
	 *
	 * @param int $length Default 30.
	 */
	return (int) apply_filters( 'prospero_excerpt_length', $length );
}

/**
 * WordPress `excerpt_length` filter callback.
 */
function prospero_excerpt_length( $length ) {
	return prospero_get_excerpt_length();
}
add_filter( 'excerpt_length', 'prospero_excerpt_length' );

/**
 * Custom excerpt more
 */
function prospero_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'prospero_excerpt_more' );

/**
 * Add product placement notice to content if set in customizer
 */
function prospero_add_product_placement_notice( $content ) {
	if ( ! is_singular( 'post' ) ) {
		return $content;
	}

	$notice = get_theme_mod( 'prospero_product_placement_text', '' );
	if ( empty( $notice ) ) {
		return $content;
	}

	// Check if post has specific meta flag for product placement
	if ( get_post_meta( get_the_ID(), '_prospero_has_product_placement', true ) ) {
		$notice_html = '<div class="product-placement-notice">' . wp_kses_post( $notice ) . '</div>';
		$content = $notice_html . $content;
	}

	return $content;
}
add_filter( 'the_content', 'prospero_add_product_placement_notice' );

/**
 * Mobile Menu Walker
 *
 * Custom walker for mobile menu with hierarchical panel navigation
 */
class Prospero_Mobile_Menu_Walker extends Walker_Nav_Menu {

	/**
	 * Starts the element output.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Check if item has children
		$has_children = in_array( 'menu-item-has-children', $classes, true );

		if ( $has_children ) {
			$classes[] = 'has-submenu';
		}

		$class_names = implode( ' ', array_filter( $classes ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$output .= '<li' . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
			}
		}

		$title = apply_filters( 'the_title', $item->title, $item->ID );

		$item_output = '';

		if ( $has_children ) {
			// Item with submenu: link + toggle button
			$item_output .= '<div class="menu-item-inner">';
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $title;
			$item_output .= '</a>';
			$item_output .= '<button type="button" class="submenu-toggle" aria-expanded="false" data-submenu="submenu-' . $item->ID . '">';
			$item_output .= '<span class="submenu-toggle-icon" aria-hidden="true">&rsaquo;</span>';
			$item_output .= '<span class="screen-reader-text">' . esc_html__( 'Open submenu', 'prospero-theme' ) . '</span>';
			$item_output .= '</button>';
			$item_output .= '</div>';
		} else {
			// Regular item: just the link
			$item_output .= '<a' . $attributes . '>';
			$item_output .= $title;
			$item_output .= '</a>';
		}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * Starts the list before the elements are added.
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		// Get the parent item ID from the output
		preg_match( '/menu-item-(\d+)"[^>]*>\s*$/', $output, $matches );
		$parent_id = isset( $matches[1] ) ? $matches[1] : '';

		$indent  = str_repeat( "\t", $depth );
		$output .= "\n{$indent}<div class=\"submenu-panel\" data-submenu-id=\"submenu-{$parent_id}\" aria-hidden=\"true\">";
		$output .= '<button type="button" class="submenu-back">';
		$output .= '<span class="submenu-back-icon" aria-hidden="true">&lsaquo;</span>';
		$output .= '<span class="submenu-back-text">' . esc_html__( 'Back', 'prospero-theme' ) . '</span>';
		$output .= '</button>';
		$output .= "\n{$indent}<ul class=\"sub-menu\">\n";
	}

	/**
	 * Ends the list after the elements are added.
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent  = str_repeat( "\t", $depth );
		$output .= "{$indent}</ul>\n";
		$output .= "{$indent}</div>\n";
	}
}
