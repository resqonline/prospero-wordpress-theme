<?php
/**
 * Gutenberg Editor Configuration
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register custom Gutenberg blocks
 */
function prospero_register_blocks() {
	// Register block categories
	add_filter( 'block_categories_all', 'prospero_block_categories', 10, 2 );
	
	// Register button block styles
	register_block_style( 'core/button', array(
		'name'  => 'primary',
		'label' => esc_html__( 'Primary', 'prospero-theme' ),
	) );
	
	register_block_style( 'core/button', array(
		'name'  => 'secondary',
		'label' => esc_html__( 'Secondary', 'prospero-theme' ),
	) );
	
	register_block_style( 'core/button', array(
		'name'  => 'tertiary',
		'label' => esc_html__( 'Tertiary', 'prospero-theme' ),
	) );
	
	// TODO: Register custom blocks:
	// - Text Content Block
	// - Call to Action Block
	// - Affiliate Link Block
	// - Member Content Block
	// - Testimonial Block
	// - Partner Block
	// - Team Block
}
add_action( 'init', 'prospero_register_blocks' );

/**
 * Add custom block category
 */
function prospero_block_categories( $categories, $post ) {
	return array_merge(
		$categories,
		array(
			array(
				'slug'  => 'prospero',
				'title' => esc_html__( 'Prospero Blocks', 'prospero-theme' ),
			),
		)
	);
}

/**
 * Update editor color palette from customizer
 */
function prospero_update_editor_colors() {
	$colors = array(
		array(
			'name'  => esc_html__( 'Primary', 'prospero-theme' ),
			'slug'  => 'primary',
			'color' => get_theme_mod( 'prospero_primary_color', '#007bff' ),
		),
		array(
			'name'  => esc_html__( 'Secondary', 'prospero-theme' ),
			'slug'  => 'secondary',
			'color' => get_theme_mod( 'prospero_secondary_color', '#6c757d' ),
		),
		array(
			'name'  => esc_html__( 'Tertiary', 'prospero-theme' ),
			'slug'  => 'tertiary',
			'color' => get_theme_mod( 'prospero_tertiary_color', '#28a745' ),
		),
		array(
			'name'  => esc_html__( 'Highlight', 'prospero-theme' ),
			'slug'  => 'highlight',
			'color' => get_theme_mod( 'prospero_highlight_color', '#ffc107' ),
		),
	);
	
	add_theme_support( 'editor-color-palette', $colors );
}
add_action( 'after_setup_theme', 'prospero_update_editor_colors', 11 );

/**
 * Dynamically inject Customizer colors into theme.json
 * This ensures the block editor always uses current Customizer settings
 *
 * @param WP_Theme_JSON_Data $theme_json The theme.json data object.
 * @return WP_Theme_JSON_Data Modified theme.json data.
 */
function prospero_filter_theme_json_colors( $theme_json ) {
	// Get colors from Customizer
	$primary    = get_theme_mod( 'prospero_primary_color', '#007bff' );
	$secondary  = get_theme_mod( 'prospero_secondary_color', '#6c757d' );
	$tertiary   = get_theme_mod( 'prospero_tertiary_color', '#28a745' );
	$highlight  = get_theme_mod( 'prospero_highlight_color', '#ffc107' );
	$text       = get_theme_mod( 'prospero_text_color', '#333333' );
	$dark_text  = get_theme_mod( 'prospero_dark_text_color', '#f7f7f7' );
	$background = get_theme_mod( 'prospero_background_color', '#ffffff' );
	$dark_bg    = get_theme_mod( 'prospero_dark_background_color', '#2b2a33' );
	
	// Build the new color palette
	$new_data = array(
		'version'  => 2,
		'settings' => array(
			'color' => array(
				'palette' => array(
					array(
						'slug'  => 'primary',
						'color' => $primary,
						'name'  => esc_html__( 'Primary', 'prospero-theme' ),
					),
					array(
						'slug'  => 'secondary',
						'color' => $secondary,
						'name'  => esc_html__( 'Secondary', 'prospero-theme' ),
					),
					array(
						'slug'  => 'tertiary',
						'color' => $tertiary,
						'name'  => esc_html__( 'Tertiary', 'prospero-theme' ),
					),
					array(
						'slug'  => 'highlight',
						'color' => $highlight,
						'name'  => esc_html__( 'Highlight', 'prospero-theme' ),
					),
					array(
						'slug'  => 'text',
						'color' => $text,
						'name'  => esc_html__( 'Text', 'prospero-theme' ),
					),
					array(
						'slug'  => 'dark-text',
						'color' => $dark_text,
						'name'  => esc_html__( 'Dark Mode Text', 'prospero-theme' ),
					),
					array(
						'slug'  => 'background',
						'color' => $background,
						'name'  => esc_html__( 'Background', 'prospero-theme' ),
					),
					array(
						'slug'  => 'dark-background',
						'color' => $dark_bg,
						'name'  => esc_html__( 'Dark Background', 'prospero-theme' ),
					),
				),
			),
		),
	);
	
	return $theme_json->update_with( $new_data );
}
add_filter( 'wp_theme_json_data_theme', 'prospero_filter_theme_json_colors' );

/**
 * Inject the Customizer-driven CSS custom properties into the block editor.
 *
 * theme.json's palette and element styles reference these variables
 * (see `styles.elements.button` in theme.json). By attaching them as inline
 * CSS to the editor stylesheet we guarantee that every Gutenberg block in
 * the editor iframe renders with the current Customizer colors as its
 * default — without us having to restyle each core block individually.
 */
function prospero_editor_custom_properties() {
	if ( ! function_exists( 'prospero_get_button_css_vars' ) ) {
		return;
	}

	$css  = ':root {';
	// Mirror the aliases from the frontend so theme.json fallbacks resolve.
	$css .= '--prospero-primary: ' . esc_attr( get_theme_mod( 'prospero_primary_color', '#007bff' ) ) . ';';
	$css .= '--prospero-secondary: ' . esc_attr( get_theme_mod( 'prospero_secondary_color', '#6c757d' ) ) . ';';
	$css .= '--prospero-tertiary: ' . esc_attr( get_theme_mod( 'prospero_tertiary_color', '#28a745' ) ) . ';';
	// Editor canvas is generally light-mode; pass it explicitly so the
	// contrast fallback in prospero_get_button_css_vars_for() uses the
	// light page background as its reference.
	$css .= prospero_render_css_vars( prospero_get_button_css_vars( 'light' ) );
	$css .= '}';

	wp_add_inline_style( 'prospero-editor-style', $css );
}
add_action( 'enqueue_block_editor_assets', 'prospero_editor_custom_properties', 20 );
