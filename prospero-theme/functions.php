<?php
/**
 * Prospero Theme Functions
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Define theme constants
define( 'PROSPERO_VERSION', '1.0.0' );
define( 'PROSPERO_THEME_DIR', get_template_directory() );
define( 'PROSPERO_THEME_URI', get_template_directory_uri() );

/**
 * Theme Setup
 */
function prospero_theme_setup() {
	// Make theme available for translation
	load_theme_textdomain( 'prospero-theme', PROSPERO_THEME_DIR . '/languages' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Let WordPress manage the document title
	add_theme_support( 'title-tag' );

	// Enable support for Post Thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add support for responsive embeds
	add_theme_support( 'responsive-embeds' );

	// Add support for editor styles
	add_theme_support( 'editor-styles' );
	add_editor_style( 'assets/css/editor-style.css' );

	// Add support for wide and full alignment
	add_theme_support( 'align-wide' );

	// Add support for custom logo
	add_theme_support( 'custom-logo', array(
		'height'      => 100,
		'width'       => 400,
		'flex-height' => true,
		'flex-width'  => true,
		'header-text' => array( 'site-title', 'site-description' ),
	) );

	// Add support for HTML5 markup
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
		'style',
		'script',
	) );

	// Add support for Gutenberg color palette (will be populated dynamically from customizer)
	add_theme_support( 'editor-color-palette', array() );

	// Disable custom colors
	add_theme_support( 'disable-custom-colors' );

	// Disable custom font sizes
	add_theme_support( 'disable-custom-font-sizes' );

	// Add support for Block Styles
	add_theme_support( 'wp-block-styles' );

	// Register nav menus
	register_nav_menus( array(
		'main-menu'      => esc_html__( 'Main Menu', 'prospero-theme' ),
		'footer-menu'    => esc_html__( 'Footer Menu', 'prospero-theme' ),
		'social-menu'    => esc_html__( 'Social Menu', 'prospero-theme' ),
		'logged-in-menu' => esc_html__( 'Logged In Menu', 'prospero-theme' ),
	) );
}
add_action( 'after_setup_theme', 'prospero_theme_setup' );

/**
 * Fallback for logged-in menu when no menu is assigned
 *
 * @param array $args Menu arguments.
 */
function prospero_logged_in_menu_fallback( $args ) {
	if ( ! is_user_logged_in() ) {
		return;
	}

	$my_account_page = get_page_by_path( 'my-account' );
	$my_account_url  = $my_account_page ? get_permalink( $my_account_page ) : home_url( '/my-account/' );

	$menu_items = array(
		array(
			'title' => __( 'Account Overview', 'prospero-theme' ),
			'url'   => $my_account_url . '#account-overview',
			'class' => 'menu-item-account-overview',
		),
		array(
			'title' => __( 'Edit Profile', 'prospero-theme' ),
			'url'   => $my_account_url . '#edit-profile',
			'class' => 'menu-item-edit-profile',
		),
		array(
			'title' => __( 'Change Password', 'prospero-theme' ),
			'url'   => $my_account_url . '#change-password',
			'class' => 'menu-item-change-password',
		),
		array(
			'title' => __( 'Logout', 'prospero-theme' ),
			'url'   => wp_logout_url( home_url() ),
			'class' => 'menu-item-logout',
		),
	);

	$output = '<ul id="' . esc_attr( $args['menu_id'] ) . '" class="menu">';

	foreach ( $menu_items as $item ) {
		$output .= sprintf(
			'<li class="menu-item %s"><a href="%s">%s</a></li>',
			esc_attr( $item['class'] ),
			esc_url( $item['url'] ),
			esc_html( $item['title'] )
		);
	}

	$output .= '</ul>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped above
}

/**
 * Set content width from customizer
 */
function prospero_content_width() {
	$width = get_theme_mod( 'prospero_content_width', '1200px' );
	// Extract numeric value for WordPress global (expects integer)
	$numeric_width = intval( preg_replace( '/[^0-9]/', '', $width ) );
	$GLOBALS['content_width'] = apply_filters( 'prospero_content_width', $numeric_width );
}
add_action( 'after_setup_theme', 'prospero_content_width', 0 );

/**
 * Override theme.json layout settings based on customizer
 */
function prospero_override_theme_json_layout( $theme_json ) {
	$content_width = get_theme_mod( 'prospero_content_width', '1200px' );
	
	$new_data = array(
		'version'  => 2,
		'settings' => array(
			'layout' => array(
				'contentSize' => '800px',
				'wideSize'    => $content_width,
			),
		),
	);
	
	return $theme_json->update_with( $new_data );
}
add_filter( 'wp_theme_json_data_theme', 'prospero_override_theme_json_layout' );

/**
 * Enqueue scripts and styles
 */
function prospero_enqueue_scripts() {
	// Main stylesheet
	wp_enqueue_style( 'prospero-style', get_stylesheet_uri(), array(), PROSPERO_VERSION );
	
	// Theme styles
	wp_enqueue_style( 'prospero-main', PROSPERO_THEME_URI . '/assets/css/main.css', array(), PROSPERO_VERSION );
	
	// Dark mode styles
	wp_enqueue_style( 'prospero-dark-mode', PROSPERO_THEME_URI . '/assets/css/dark-mode.css', array( 'prospero-main' ), PROSPERO_VERSION );
	
	// Shortcode styles
	wp_enqueue_style( 'prospero-shortcodes', PROSPERO_THEME_URI . '/assets/css/shortcodes.css', array( 'prospero-main' ), PROSPERO_VERSION );
	
	// Block styles
	wp_enqueue_style( 'prospero-blocks', PROSPERO_THEME_URI . '/assets/css/blocks.css', array( 'prospero-main' ), PROSPERO_VERSION );
	
	// Flickity library
	wp_enqueue_style( 'flickity', PROSPERO_THEME_URI . '/assets/libs/flickity/flickity.min.css', array(), '2.3.0' );
	wp_enqueue_script( 'flickity', PROSPERO_THEME_URI . '/assets/libs/flickity/flickity.pkgd.min.js', array(), '2.3.0', true );
	wp_enqueue_script( 'prospero-flickity-init', PROSPERO_THEME_URI . '/assets/js/flickity-init.js', array( 'flickity' ), PROSPERO_VERSION, true );
	
	// Main JavaScript
	wp_enqueue_script( 'prospero-main', PROSPERO_THEME_URI . '/assets/js/main.js', array(), PROSPERO_VERSION, true );
	
	// Localize main script with i18n strings
	wp_localize_script( 'prospero-main', 'prosperoI18n', array(
		'teamMemberDetails' => esc_html__( 'Team member details', 'prospero-theme' ),
		'closeDialog'       => esc_html__( 'Close dialog', 'prospero-theme' ),
	) );
	
	// Dark mode JavaScript
	wp_enqueue_script( 'prospero-dark-mode', PROSPERO_THEME_URI . '/assets/js/dark-mode.js', array( 'prospero-main' ), PROSPERO_VERSION, true );
	
	// FAQ accordion JavaScript
	wp_enqueue_script( 'prospero-faq-accordion', PROSPERO_THEME_URI . '/assets/js/faq-accordion.js', array(), PROSPERO_VERSION, true );
	
	// Projects filter JavaScript (on projects template)
	if ( is_page_template( 'template-projects.php' ) ) {
		wp_enqueue_script( 'prospero-projects-filter', PROSPERO_THEME_URI . '/assets/js/projects-filter.js', array(), PROSPERO_VERSION, true );
		wp_localize_script( 'prospero-projects-filter', 'prosperoAjax', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		) );
	}
	
	// Pass customizer settings to JavaScript
	wp_localize_script( 'prospero-dark-mode', 'prosperoSettings', array(
		'defaultMode'  => get_theme_mod( 'prospero_default_mode', 'light' ),
		'stickyMenu'   => get_theme_mod( 'prospero_sticky_menu', false ),
		'hamburgerMenu' => get_theme_mod( 'prospero_hamburger_menu', false ),
	) );
	
	// Comment reply script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'prospero_enqueue_scripts' );

// Load theme components
require_once PROSPERO_THEME_DIR . '/inc/customizer.php';
require_once PROSPERO_THEME_DIR . '/inc/post-types.php';
require_once PROSPERO_THEME_DIR . '/inc/template-functions.php';
require_once PROSPERO_THEME_DIR . '/inc/gutenberg.php';
require_once PROSPERO_THEME_DIR . '/inc/blocks.php';
require_once PROSPERO_THEME_DIR . '/inc/shortcodes.php';
require_once PROSPERO_THEME_DIR . '/inc/typography.php';
require_once PROSPERO_THEME_DIR . '/inc/ajax-filters.php';
require_once PROSPERO_THEME_DIR . '/inc/frontend-login.php';
require_once PROSPERO_THEME_DIR . '/inc/security.php';
require_once PROSPERO_THEME_DIR . '/inc/seo.php';
require_once PROSPERO_THEME_DIR . '/inc/theme-updater.php';
