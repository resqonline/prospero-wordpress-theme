<?php
/**
 * Prospero Child Theme Functions
 *
 * @package Prospero_Child
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Child theme setup
 *
 * Runs after parent theme setup (priority 11).
 * Use this to add or modify theme support.
 */
function prospero_child_setup() {
	// Load child theme text domain for translations
	load_child_theme_textdomain( 'prospero-theme-child', get_stylesheet_directory() . '/languages' );

	// Add any additional theme support here
	// Example: add_theme_support( 'custom-header' );
}
add_action( 'after_setup_theme', 'prospero_child_setup', 11 );

/**
 * Enqueue child theme scripts and styles
 *
 * Runs after parent theme scripts (via prospero_after_enqueue_scripts action).
 * Use this to add additional scripts/styles or dequeue parent scripts.
 */
function prospero_child_enqueue_scripts() {
	// Example: Add a custom script
	// wp_enqueue_script( 'my-custom-script', get_stylesheet_directory_uri() . '/assets/js/my-script.js', array( 'jquery' ), '1.0.0', true );

	// Example: Dequeue a parent script you don't need
	// wp_dequeue_script( 'prospero-lightbox' );
}
add_action( 'prospero_after_enqueue_scripts', 'prospero_child_enqueue_scripts' );

/**
 * Modify parent theme functionality
 *
 * Examples of how to modify parent theme behavior:
 */

// Example: Modify excerpt length
// function prospero_child_excerpt_length( $length ) {
//     return 40; // Change from parent's 30 words to 40
// }
// add_filter( 'excerpt_length', 'prospero_child_excerpt_length', 11 );

// Example: Add a custom post type
// function prospero_child_register_post_types() {
//     register_post_type( 'portfolio', array(
//         'labels' => array( 'name' => 'Portfolio' ),
//         'public' => true,
//         'show_in_rest' => true,
//         'supports' => array( 'title', 'editor', 'thumbnail' ),
//     ) );
// }
// add_action( 'init', 'prospero_child_register_post_types' );

// Example: Add custom Customizer settings
// function prospero_child_customizer( $wp_customize ) {
//     $wp_customize->add_setting( 'child_custom_setting', array(
//         'default' => '',
//         'sanitize_callback' => 'sanitize_text_field',
//     ) );
//     $wp_customize->add_control( 'child_custom_setting', array(
//         'label' => 'Custom Setting',
//         'section' => 'title_tagline',
//         'type' => 'text',
//     ) );
// }
// add_action( 'customize_register', 'prospero_child_customizer', 11 );

// Example: Override a parent theme template function
// if ( ! function_exists( 'prospero_custom_function' ) ) {
//     function prospero_custom_function() {
//         // Your custom implementation
//     }
// }

/**
 * Available hooks from parent theme:
 *
 * Actions:
 * - prospero_after_enqueue_scripts: After all parent scripts/styles are loaded
 *
 * Filters:
 * - prospero_content_width: Modify the content width
 *
 * Constants available:
 * - PROSPERO_VERSION: Parent theme version
 * - PROSPERO_THEME_DIR: Parent theme directory path
 * - PROSPERO_THEME_URI: Parent theme directory URI
 * - PROSPERO_CHILD_DIR: Child theme directory path (same as get_stylesheet_directory())
 * - PROSPERO_CHILD_URI: Child theme directory URI (same as get_stylesheet_directory_uri())
 * - PROSPERO_IS_CHILD_THEME: Boolean, true when child theme is active
 */
