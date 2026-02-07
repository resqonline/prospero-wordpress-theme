<?php
/**
 * Theme Update Checker
 *
 * Enables automatic updates from GitHub releases.
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Initialize the theme update checker.
 *
 * Uses the Plugin Update Checker library by YahnisElsts.
 * Library must be installed in inc/lib/plugin-update-checker/
 *
 * @see https://github.com/YahnisElsts/plugin-update-checker
 */
function prospero_init_theme_updater() {
	// Path to the update checker library.
	$update_checker_path = PROSPERO_THEME_DIR . '/inc/lib/plugin-update-checker/plugin-update-checker.php';

	// Check if the library exists.
	if ( ! file_exists( $update_checker_path ) ) {
		// Show admin notice if library is missing.
		add_action( 'admin_notices', 'prospero_update_checker_missing_notice' );
		return;
	}

	// Load the update checker library.
	require_once $update_checker_path;

	// Initialize the update checker for this theme.
	$update_checker = YahnisElsts\PluginUpdateChecker\v5\PucFactory::buildUpdateChecker(
		'https://github.com/resqonline/prospero-wordpress-theme/',
		PROSPERO_THEME_DIR . '/style.css',
		'prospero-theme'
	);

	// Set the branch that contains the stable release.
	$update_checker->setBranch( 'main' );

	// Enable release assets - looks for ZIP files attached to GitHub releases.
	$update_checker->getVcsApi()->enableReleaseAssets();
}
add_action( 'init', 'prospero_init_theme_updater' );

/**
 * Display admin notice when update checker library is missing.
 */
function prospero_update_checker_missing_notice() {
	// Only show to administrators.
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$class   = 'notice notice-warning';
	$message = sprintf(
		/* translators: %s: URL to Plugin Update Checker library */
		__( 'Prospero Theme: The update checker library is not installed. Automatic updates from GitHub are disabled. Please download the library from %s and place it in the theme\'s inc/lib/plugin-update-checker/ directory.', 'prospero-theme' ),
		'<a href="https://github.com/YahnisElsts/plugin-update-checker" target="_blank">GitHub</a>'
	);

	printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), wp_kses_post( $message ) );
}
