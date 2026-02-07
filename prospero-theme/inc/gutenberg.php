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
 * Add CSS custom properties to block editor
 * This ensures buttons and other elements use the correct Customizer colors
 */
function prospero_editor_custom_properties() {
	// Get button settings from Customizer
	$primary_style = get_theme_mod( 'prospero_primary_btn_style', 'flat' );
	$primary_bg = get_theme_mod( 'prospero_primary_btn_bg', 'rgba(0, 123, 255, 1)' );
	$primary_text = get_theme_mod( 'prospero_primary_btn_text', 'rgba(255, 255, 255, 1)' );
	$primary_radius = get_theme_mod( 'prospero_primary_btn_radius', '4px' );
	$primary_hover_bg = get_theme_mod( 'prospero_primary_btn_hover_bg', '' );
	$primary_hover_text = get_theme_mod( 'prospero_primary_btn_hover_text', '' );
	
	$secondary_style = get_theme_mod( 'prospero_secondary_btn_style', 'outline' );
	$secondary_bg = get_theme_mod( 'prospero_secondary_btn_bg', 'rgba(108, 117, 125, 1)' );
	$secondary_text = get_theme_mod( 'prospero_secondary_btn_text', 'rgba(255, 255, 255, 1)' );
	$secondary_radius = get_theme_mod( 'prospero_secondary_btn_radius', '4px' );
	
	$tertiary_style = get_theme_mod( 'prospero_tertiary_btn_style', 'flat' );
	$tertiary_bg = get_theme_mod( 'prospero_tertiary_btn_bg', 'rgba(40, 167, 69, 1)' );
	$tertiary_text = get_theme_mod( 'prospero_tertiary_btn_text', 'rgba(255, 255, 255, 1)' );
	$tertiary_radius = get_theme_mod( 'prospero_tertiary_btn_radius', '4px' );
	
	// Calculate hover colors
	$primary_hover_bg_auto = ! empty( $primary_hover_bg ) ? $primary_hover_bg : 'color-mix(in srgb, ' . esc_attr( $primary_bg ) . ' 80%, black)';
	$secondary_hover_bg_auto = ! empty( get_theme_mod( 'prospero_secondary_btn_hover_bg', '' ) ) ? get_theme_mod( 'prospero_secondary_btn_hover_bg', '' ) : 'color-mix(in srgb, ' . esc_attr( $secondary_bg ) . ' 80%, black)';
	$tertiary_hover_bg_auto = ! empty( get_theme_mod( 'prospero_tertiary_btn_hover_bg', '' ) ) ? get_theme_mod( 'prospero_tertiary_btn_hover_bg', '' ) : 'color-mix(in srgb, ' . esc_attr( $tertiary_bg ) . ' 80%, black)';
	
	$css = ':root {
		--prospero-btn-primary-bg: ' . esc_attr( $primary_style === 'outline' ? 'transparent' : $primary_bg ) . ';
		--prospero-btn-primary-text: ' . esc_attr( $primary_style === 'outline' ? $primary_bg : $primary_text ) . ';
		--prospero-btn-primary-radius: ' . esc_attr( $primary_radius ) . ';
		--prospero-btn-primary-hover-bg: ' . $primary_hover_bg_auto . ';
		--prospero-btn-primary-hover-text: ' . esc_attr( ! empty( $primary_hover_text ) ? $primary_hover_text : $primary_text ) . ';
		
		--prospero-btn-secondary-bg: ' . esc_attr( $secondary_style === 'outline' ? 'transparent' : $secondary_bg ) . ';
		--prospero-btn-secondary-text: ' . esc_attr( $secondary_style === 'outline' ? $secondary_bg : $secondary_text ) . ';
		--prospero-btn-secondary-radius: ' . esc_attr( $secondary_radius ) . ';
		--prospero-btn-secondary-hover-bg: ' . $secondary_hover_bg_auto . ';
		
		--prospero-btn-tertiary-bg: ' . esc_attr( $tertiary_style === 'outline' ? 'transparent' : $tertiary_bg ) . ';
		--prospero-btn-tertiary-text: ' . esc_attr( $tertiary_style === 'outline' ? $tertiary_bg : $tertiary_text ) . ';
		--prospero-btn-tertiary-radius: ' . esc_attr( $tertiary_radius ) . ';
		--prospero-btn-tertiary-hover-bg: ' . $tertiary_hover_bg_auto . ';
	}';
	
	wp_add_inline_style( 'prospero-editor-style', $css );
}
add_action( 'enqueue_block_editor_assets', 'prospero_editor_custom_properties', 20 );
