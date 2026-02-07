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

// Child theme constants (will differ from parent if child theme is active)
define( 'PROSPERO_CHILD_DIR', get_stylesheet_directory() );
define( 'PROSPERO_CHILD_URI', get_stylesheet_directory_uri() );
define( 'PROSPERO_IS_CHILD_THEME', PROSPERO_THEME_DIR !== PROSPERO_CHILD_DIR );

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
 *
 * Child themes can:
 * - Override parent styles by creating their own style.css (loaded last)
 * - Add custom styles in assets/css/custom.css
 * - Dequeue/deregister parent scripts/styles and add their own
 * - Use 'prospero_enqueue_scripts' action (priority > 10) to add scripts after parent
 */
function prospero_enqueue_scripts() {
	// Parent theme styles - always load from parent theme directory
	wp_enqueue_style( 'prospero-main', PROSPERO_THEME_URI . '/assets/css/main.css', array(), PROSPERO_VERSION );
	wp_enqueue_style( 'prospero-dark-mode', PROSPERO_THEME_URI . '/assets/css/dark-mode.css', array( 'prospero-main' ), PROSPERO_VERSION );
	wp_enqueue_style( 'prospero-shortcodes', PROSPERO_THEME_URI . '/assets/css/shortcodes.css', array( 'prospero-main' ), PROSPERO_VERSION );
	wp_enqueue_style( 'prospero-blocks', PROSPERO_THEME_URI . '/assets/css/blocks.css', array( 'prospero-main' ), PROSPERO_VERSION );

	// Child theme style.css - loaded after all parent styles so it can override
	// For child themes, get_stylesheet_uri() returns child's style.css
	// For parent theme alone, it returns parent's style.css
	if ( PROSPERO_IS_CHILD_THEME ) {
		// Load child theme's style.css with parent styles as dependency
		wp_enqueue_style(
			'prospero-child-style',
			get_stylesheet_uri(),
			array( 'prospero-main', 'prospero-dark-mode', 'prospero-shortcodes', 'prospero-blocks' ),
			wp_get_theme()->get( 'Version' )
		);

		// Load child theme's custom.css if it exists
		$child_custom_css = PROSPERO_CHILD_DIR . '/assets/css/custom.css';
		if ( file_exists( $child_custom_css ) ) {
			wp_enqueue_style(
				'prospero-child-custom',
				PROSPERO_CHILD_URI . '/assets/css/custom.css',
				array( 'prospero-child-style' ),
				wp_get_theme()->get( 'Version' )
			);
		}
	} else {
		// Parent theme only - load parent's style.css
		wp_enqueue_style( 'prospero-style', get_stylesheet_uri(), array( 'prospero-main' ), PROSPERO_VERSION );
	}

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

	// Lightbox JavaScript
	wp_enqueue_script( 'prospero-lightbox', PROSPERO_THEME_URI . '/assets/js/lightbox.js', array(), PROSPERO_VERSION, true );
	wp_localize_script( 'prospero-lightbox', 'prosperoLightbox', array(
		'ariaLabel'  => esc_html__( 'Image lightbox', 'prospero-theme' ),
		'closeLabel' => esc_html__( 'Close lightbox', 'prospero-theme' ),
		'prevLabel'  => esc_html__( 'Previous image', 'prospero-theme' ),
		'nextLabel'  => esc_html__( 'Next image', 'prospero-theme' ),
	) );

	// Projects filter JavaScript (on projects template)
	if ( is_page_template( 'template-projects.php' ) ) {
		wp_enqueue_script( 'prospero-projects-filter', PROSPERO_THEME_URI . '/assets/js/projects-filter.js', array(), PROSPERO_VERSION, true );
		wp_localize_script( 'prospero-projects-filter', 'prosperoAjax', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
		) );
	}

	// Blog filter JavaScript (on blog page)
	if ( is_home() ) {
		wp_enqueue_script( 'prospero-blog-filter', PROSPERO_THEME_URI . '/assets/js/blog-filter.js', array(), PROSPERO_VERSION, true );
		wp_localize_script( 'prospero-blog-filter', 'prosperoBlogFilter', array(
			'ajaxUrl' => admin_url( 'admin-ajax.php' ),
			'nonce'   => wp_create_nonce( 'prospero_filter_blog' ),
		) );
	}

	// Pass customizer settings to JavaScript
	wp_localize_script( 'prospero-dark-mode', 'prosperoSettings', array(
		'defaultMode'   => get_theme_mod( 'prospero_default_mode', 'light' ),
		'stickyMenu'    => get_theme_mod( 'prospero_sticky_menu', false ),
		'hamburgerMenu' => get_theme_mod( 'prospero_hamburger_menu', false ),
	) );

	// Comment reply script
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	// Child theme custom.js if it exists
	if ( PROSPERO_IS_CHILD_THEME ) {
		$child_custom_js = PROSPERO_CHILD_DIR . '/assets/js/custom.js';
		if ( file_exists( $child_custom_js ) ) {
			wp_enqueue_script(
				'prospero-child-custom',
				PROSPERO_CHILD_URI . '/assets/js/custom.js',
				array( 'prospero-main' ),
				wp_get_theme()->get( 'Version' ),
				true
			);
		}
	}

	/**
	 * Fires after all Prospero scripts and styles are enqueued.
	 *
	 * Child themes can hook here to add additional scripts/styles
	 * that load after all parent theme assets.
	 *
	 * @since 1.0.0
	 */
	do_action( 'prospero_after_enqueue_scripts' );
}
add_action( 'wp_enqueue_scripts', 'prospero_enqueue_scripts' );

/**
 * Put sticky posts at the top of archive queries
 *
 * WordPress only puts sticky posts at top on the main blog page (is_home),
 * not on archives. This filter replicates that behavior for archives.
 *
 * @param array    $posts The array of posts.
 * @param WP_Query $query The query object.
 * @return array Modified posts array with sticky posts first.
 */
function prospero_sticky_posts_on_archives( $posts, $query ) {
	// Only modify main query on frontend
	if ( is_admin() || ! $query->is_main_query() ) {
		return $posts;
	}

	// Only for post archives on first page (category, tag, author, date)
	if ( ! $query->is_paged() && ( $query->is_category() || $query->is_tag() || $query->is_author() || $query->is_date() ) ) {
		$sticky_posts = get_option( 'sticky_posts' );

		if ( ! empty( $sticky_posts ) ) {
			$sticky = array();
			$regular = array();

			foreach ( $posts as $post ) {
				if ( in_array( $post->ID, $sticky_posts, true ) ) {
					$sticky[] = $post;
				} else {
					$regular[] = $post;
				}
			}

			// Merge sticky posts first, then regular posts
			$posts = array_merge( $sticky, $regular );
		}
	}

	return $posts;
}
add_filter( 'the_posts', 'prospero_sticky_posts_on_archives', 10, 2 );

// Load theme components
require_once PROSPERO_THEME_DIR . '/inc/breadcrumbs.php';
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
require_once PROSPERO_THEME_DIR . '/inc/sidebars.php';
require_once PROSPERO_THEME_DIR . '/inc/theme-updater.php';
