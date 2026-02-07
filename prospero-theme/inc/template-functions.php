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
 * Generate dynamic CSS from customizer settings
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
	
	// PRIMARY BUTTON settings
	$primary_style = get_theme_mod( 'prospero_primary_btn_style', 'flat' );
	$primary_bg = get_theme_mod( 'prospero_primary_btn_bg', 'rgba(0, 123, 255, 1)' );
	$primary_text = get_theme_mod( 'prospero_primary_btn_text', 'rgba(255, 255, 255, 1)' );
	$primary_hover_bg = get_theme_mod( 'prospero_primary_btn_hover_bg', '' );
	$primary_hover_text = get_theme_mod( 'prospero_primary_btn_hover_text', '' );
	$primary_font_style = get_theme_mod( 'prospero_primary_btn_font_style', 'none' );
	$primary_radius = get_theme_mod( 'prospero_primary_btn_radius', '4px' );
	
	// SECONDARY BUTTON settings
	$secondary_style = get_theme_mod( 'prospero_secondary_btn_style', 'outline' );
	$secondary_bg = get_theme_mod( 'prospero_secondary_btn_bg', 'rgba(108, 117, 125, 1)' );
	$secondary_text = get_theme_mod( 'prospero_secondary_btn_text', 'rgba(255, 255, 255, 1)' );
	$secondary_hover_bg = get_theme_mod( 'prospero_secondary_btn_hover_bg', '' );
	$secondary_hover_text = get_theme_mod( 'prospero_secondary_btn_hover_text', '' );
	$secondary_font_style = get_theme_mod( 'prospero_secondary_btn_font_style', 'none' );
	$secondary_radius = get_theme_mod( 'prospero_secondary_btn_radius', '4px' );
	
	// TERTIARY BUTTON settings
	$tertiary_style = get_theme_mod( 'prospero_tertiary_btn_style', 'flat' );
	$tertiary_bg = get_theme_mod( 'prospero_tertiary_btn_bg', 'rgba(40, 167, 69, 1)' );
	$tertiary_text = get_theme_mod( 'prospero_tertiary_btn_text', 'rgba(255, 255, 255, 1)' );
	$tertiary_hover_bg = get_theme_mod( 'prospero_tertiary_btn_hover_bg', '' );
	$tertiary_hover_text = get_theme_mod( 'prospero_tertiary_btn_hover_text', '' );
	$tertiary_font_style = get_theme_mod( 'prospero_tertiary_btn_font_style', 'none' );
	$tertiary_radius = get_theme_mod( 'prospero_tertiary_btn_radius', '4px' );
	
	// Calculate auto-hover colors if not set
	$primary_hover_bg_auto = ! empty( $primary_hover_bg ) ? $primary_hover_bg : 'color-mix(in srgb, ' . esc_attr( $primary_bg ) . ' 80%, black)';
	$secondary_hover_bg_auto = ! empty( $secondary_hover_bg ) ? $secondary_hover_bg : 'color-mix(in srgb, ' . esc_attr( $secondary_bg ) . ' 80%, black)';
	$tertiary_hover_bg_auto = ! empty( $tertiary_hover_bg ) ? $tertiary_hover_bg : 'color-mix(in srgb, ' . esc_attr( $tertiary_bg ) . ' 80%, black)';
	
	// Layout
	$content_width = get_theme_mod( 'prospero_content_width', '1200px' );
	
	$css = ':root {';
	// Layout
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
	
	// Prospero-prefixed variables
	$css .= '--prospero-primary: ' . esc_attr( $primary_color ) . ';';
	$css .= '--prospero-secondary: ' . esc_attr( $secondary_color ) . ';';
	$css .= '--prospero-tertiary: ' . esc_attr( $tertiary_color ) . ';';
	
	// Primary button CSS custom properties (used by theme.json)
	$css .= '--prospero-btn-primary-bg: ' . esc_attr( $primary_style === 'outline' ? 'transparent' : $primary_bg ) . ';';
	$css .= '--prospero-btn-primary-text: ' . esc_attr( $primary_style === 'outline' ? $primary_bg : $primary_text ) . ';';
	$css .= '--prospero-btn-primary-radius: ' . esc_attr( $primary_radius ) . ';';
	$css .= '--prospero-btn-primary-hover-bg: ' . $primary_hover_bg_auto . ';';
	$css .= '--prospero-btn-primary-hover-text: ' . esc_attr( ! empty( $primary_hover_text ) ? $primary_hover_text : $primary_text ) . ';';
	
	// Secondary button CSS custom properties
	$css .= '--prospero-btn-secondary-bg: ' . esc_attr( $secondary_style === 'outline' ? 'transparent' : $secondary_bg ) . ';';
	$css .= '--prospero-btn-secondary-text: ' . esc_attr( $secondary_style === 'outline' ? $secondary_bg : $secondary_text ) . ';';
	$css .= '--prospero-btn-secondary-radius: ' . esc_attr( $secondary_radius ) . ';';
	$css .= '--prospero-btn-secondary-hover-bg: ' . $secondary_hover_bg_auto . ';';
	$css .= '--prospero-btn-secondary-hover-text: ' . esc_attr( ! empty( $secondary_hover_text ) ? $secondary_hover_text : $secondary_text ) . ';';
	
	// Tertiary button CSS custom properties
	$css .= '--prospero-btn-tertiary-bg: ' . esc_attr( $tertiary_style === 'outline' ? 'transparent' : $tertiary_bg ) . ';';
	$css .= '--prospero-btn-tertiary-text: ' . esc_attr( $tertiary_style === 'outline' ? $tertiary_bg : $tertiary_text ) . ';';
	$css .= '--prospero-btn-tertiary-radius: ' . esc_attr( $tertiary_radius ) . ';';
	$css .= '--prospero-btn-tertiary-hover-bg: ' . $tertiary_hover_bg_auto . ';';
	$css .= '--prospero-btn-tertiary-hover-text: ' . esc_attr( ! empty( $tertiary_hover_text ) ? $tertiary_hover_text : $tertiary_text ) . ';';
	
	$css .= '}';
	
	// Helper function to generate button CSS
	$generate_button_css = function( $class, $style, $bg_color, $text_color, $font_style, $radius, $hover_bg = '', $hover_text = '' ) use ( &$css ) {
		$css .= $class . ' {';
		
		// Style (flat or outline)
		if ( $style === 'outline' ) {
			$css .= 'background-color: transparent;';
			$css .= 'color: ' . esc_attr( $bg_color ) . ';';
			$css .= 'border: 2px solid ' . esc_attr( $bg_color ) . ';';
		} else {
			$css .= 'background-color: ' . esc_attr( $bg_color ) . ';';
			$css .= 'color: ' . esc_attr( $text_color ) . ';';
			$css .= 'border: 2px solid ' . esc_attr( $bg_color ) . ';';
		}
		
		// Border radius
		$css .= 'border-radius: ' . esc_attr( $radius ) . ';';
		
		// Font style
		if ( $font_style === 'uppercase' ) {
			$css .= 'text-transform: uppercase;';
			$css .= 'letter-spacing: 0.5px;';
		}
		
		$css .= '}';
		
		// Hover state
		$css .= $class . ':hover, ' . $class . ':focus {';
		if ( ! empty( $hover_bg ) ) {
			// Use custom hover background if provided
			$css .= 'background-color: ' . esc_attr( $hover_bg ) . ';';
		} elseif ( $style === 'outline' ) {
			$css .= 'background-color: ' . esc_attr( $bg_color ) . ';';
		} else {
			// Auto-darken for flat buttons
			$css .= 'background-color: color-mix(in srgb, ' . esc_attr( $bg_color ) . ' 80%, black);';
		}
		
		if ( ! empty( $hover_text ) ) {
			// Use custom hover text color if provided
			$css .= 'color: ' . esc_attr( $hover_text ) . ';';
		} elseif ( $style === 'outline' ) {
			$css .= 'color: ' . esc_attr( $text_color ) . ';';
		}
		
		$css .= '}';
	};
	
	// Primary button (includes submit buttons by default)
	$generate_button_css(
		'.button-primary, .wp-block-button.is-style-primary .wp-block-button__link, input[type="submit"], button[type="submit"], .wp-block-button__link',
		$primary_style,
		$primary_bg,
		$primary_text,
		$primary_font_style,
		$primary_radius,
		$primary_hover_bg,
		$primary_hover_text
	);
	
	// Secondary button
	$generate_button_css(
		'.button-secondary, .wp-block-button.is-style-secondary .wp-block-button__link',
		$secondary_style,
		$secondary_bg,
		$secondary_text,
		$secondary_font_style,
		$secondary_radius,
		$secondary_hover_bg,
		$secondary_hover_text
	);
	
	// Tertiary button
	$generate_button_css(
		'.button-tertiary, .wp-block-button.is-style-tertiary .wp-block-button__link',
		$tertiary_style,
		$tertiary_bg,
		$tertiary_text,
		$tertiary_font_style,
		$tertiary_radius,
		$tertiary_hover_bg,
		$tertiary_hover_text
	);
	
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
 * Custom excerpt length
 */
function prospero_excerpt_length( $length ) {
	return 30;
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
