<?php
/**
 * Typography and Font Loading
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Get list of popular Google Fonts for reference
 */
function prospero_get_popular_fonts() {
	return array(
		'Inter'          => 'Sans-serif - Modern, highly legible',
		'Roboto'         => 'Sans-serif - Material Design',
		'Open Sans'      => 'Sans-serif - Friendly, clean',
		'Lato'           => 'Sans-serif - Warm, professional',
		'Montserrat'     => 'Sans-serif - Geometric, elegant',
		'Poppins'        => 'Sans-serif - Geometric, modern',
		'Playfair Display' => 'Serif - Elegant, high contrast',
		'Merriweather'   => 'Serif - Classic, readable',
		'Lora'           => 'Serif - Balanced, versatile',
		'PT Serif'       => 'Serif - Traditional, universal',
		'Raleway'        => 'Sans-serif - Elegant, thin',
		'Source Sans Pro' => 'Sans-serif - Professional, clean',
		'Nunito'         => 'Sans-serif - Rounded, friendly',
		'Ubuntu'         => 'Sans-serif - Humanist, clear',
		'Oswald'         => 'Sans-serif - Condensed, bold',
	);
}

/**
 * Sanitize font name
 */
function prospero_sanitize_font_name( $font ) {
	$font = sanitize_text_field( $font );
	// Remove any special characters except spaces, hyphens, and plus signs
	$font = preg_replace( '/[^a-zA-Z0-9\s\-\+]/', '', $font );
	return trim( $font );
}

/**
 * Convert font name to URL-safe format for Google Fonts API
 */
function prospero_format_font_for_url( $font ) {
	return str_replace( ' ', '+', $font );
}

/**
 * Get Google Fonts URL for a given font
 */
function prospero_get_google_font_url( $font, $weights = '300;400;500;600;700' ) {
	if ( empty( $font ) || $font === 'system' ) {
		return false;
	}
	
	$font_url = prospero_format_font_for_url( $font );
	// Google Fonts CSS2 API requires semicolon-separated weights
	return "https://fonts.googleapis.com/css2?family={$font_url}:wght@{$weights}&display=swap";
}

/**
 * Get local font file path
 */
function prospero_get_local_font_path( $font ) {
	$font_slug = sanitize_title( $font );
	return PROSPERO_THEME_DIR . "/assets/fonts/{$font_slug}.css";
}

/**
 * Get local font file URL
 */
function prospero_get_local_font_url( $font ) {
	$font_slug = sanitize_title( $font );
	return PROSPERO_THEME_URI . "/assets/fonts/{$font_slug}.css";
}

/**
 * Check if font is already downloaded locally and is valid CSS
 */
function prospero_is_font_local( $font ) {
	if ( empty( $font ) || $font === 'system' ) {
		return false;
	}
	
	$font_path = prospero_get_local_font_path( $font );
	
	if ( ! file_exists( $font_path ) ) {
		return false;
	}
	
	// Validate the file contains actual CSS, not an error page
	$content = file_get_contents( $font_path );
	if ( strpos( $content, '@font-face' ) === false || strpos( $content, '<!DOCTYPE' ) !== false || strpos( $content, '<html' ) !== false ) {
		// Invalid font file - delete it so it can be re-downloaded
		wp_delete_file( $font_path );
		return false;
	}
	
	return true;
}

/**
 * Download Google Font CSS and convert to local hosting
 * 
 * This function fetches the Google Fonts CSS, downloads all font files,
 * and creates a local CSS file with updated paths.
 */
function prospero_download_google_font( $font ) {
	if ( empty( $font ) || $font === 'system' ) {
		return false;
	}
	
	// Get the Google Fonts CSS URL
	$google_url = prospero_get_google_font_url( $font );
	if ( ! $google_url ) {
		return false;
	}
	
	// Fetch CSS from Google Fonts
	$response = wp_remote_get( $google_url, array(
		'timeout' => 30,
		'user-agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
	) );
	
	if ( is_wp_error( $response ) ) {
		return false;
	}
	
	// Check HTTP response code
	$response_code = wp_remote_retrieve_response_code( $response );
	if ( $response_code !== 200 ) {
		return false;
	}
	
	$css_content = wp_remote_retrieve_body( $response );
	if ( empty( $css_content ) ) {
		return false;
	}
	
	// Validate that response is actual CSS (contains @font-face) and not an error page
	if ( strpos( $css_content, '@font-face' ) === false || strpos( $css_content, '<!DOCTYPE' ) !== false || strpos( $css_content, '<html' ) !== false ) {
		return false;
	}
	
	// Create fonts directory if it doesn't exist
	$fonts_dir = PROSPERO_THEME_DIR . '/assets/fonts';
	if ( ! file_exists( $fonts_dir ) ) {
		wp_mkdir_p( $fonts_dir );
	}
	
	$font_slug = sanitize_title( $font );
	
	// Download all font files referenced in the CSS
	preg_match_all( '/url\((https:\/\/fonts\.gstatic\.com\/[^\)]+)\)/', $css_content, $matches );
	
	if ( ! empty( $matches[1] ) ) {
		foreach ( $matches[1] as $font_url ) {
			// Get font filename
			$font_filename = basename( parse_url( $font_url, PHP_URL_PATH ) );
			$local_font_path = $fonts_dir . '/' . $font_filename;
			
			// Download font file if it doesn't exist
			if ( ! file_exists( $local_font_path ) ) {
				$font_response = wp_remote_get( $font_url, array( 'timeout' => 30 ) );
				
				if ( ! is_wp_error( $font_response ) ) {
					$font_content = wp_remote_retrieve_body( $font_response );
					// Use WP_Filesystem
					global $wp_filesystem;
					if ( empty( $wp_filesystem ) ) {
						require_once ABSPATH . 'wp-admin/includes/file.php';
						WP_Filesystem();
					}
					$wp_filesystem->put_contents( $local_font_path, $font_content, FS_CHMOD_FILE );
				}
			}
			
			// Replace URL in CSS with local path
			$local_font_url = PROSPERO_THEME_URI . '/assets/fonts/' . $font_filename;
			$css_content = str_replace( $font_url, $local_font_url, $css_content );
		}
	}
	
	// Save modified CSS file
	$local_css_path = prospero_get_local_font_path( $font );
	global $wp_filesystem;
	if ( empty( $wp_filesystem ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
		WP_Filesystem();
	}
	
	return $wp_filesystem->put_contents( $local_css_path, $css_content, FS_CHMOD_FILE );
}

/**
 * Enqueue Google Fonts (locally hosted)
 */
function prospero_enqueue_fonts() {
	$heading_font = get_theme_mod( 'prospero_heading_font', 'system' );
	$body_font = get_theme_mod( 'prospero_body_font', 'system' );
	
	$fonts_to_load = array();
	
	// Add heading font if not system
	if ( $heading_font !== 'system' && ! empty( $heading_font ) ) {
		$fonts_to_load['heading'] = $heading_font;
	}
	
	// Add body font if not system and different from heading
	if ( $body_font !== 'system' && ! empty( $body_font ) && $body_font !== $heading_font ) {
		$fonts_to_load['body'] = $body_font;
	}
	
	// Load each font
	foreach ( $fonts_to_load as $type => $font ) {
		$font = prospero_sanitize_font_name( $font );
		
		// Check if font is already local, if not, try to download it
		if ( ! prospero_is_font_local( $font ) ) {
			prospero_download_google_font( $font );
		}
		
		// Enqueue the font if it exists locally
		if ( prospero_is_font_local( $font ) ) {
			$font_slug = sanitize_title( $font );
			wp_enqueue_style(
				"prospero-font-{$type}",
				prospero_get_local_font_url( $font ),
				array(),
				PROSPERO_VERSION
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', 'prospero_enqueue_fonts', 5 );
add_action( 'enqueue_block_editor_assets', 'prospero_enqueue_fonts', 5 );

/**
 * Add font family CSS variables
 */
function prospero_add_font_css() {
	$heading_font = get_theme_mod( 'prospero_heading_font', 'system' );
	$body_font = get_theme_mod( 'prospero_body_font', 'system' );
	
	// Build font stacks
	$heading_stack = prospero_get_font_stack( $heading_font );
	$body_stack = prospero_get_font_stack( $body_font );
	
	// Only output if different from default
	if ( $heading_stack || $body_stack ) {
		$css = '<style id="prospero-typography-css" type="text/css">';
		$css .= ':root {';
		
		if ( $body_stack ) {
			$css .= '--font-family-base: ' . $body_stack . ';';
		}
		
		if ( $heading_stack ) {
			$css .= '--font-family-heading: ' . $heading_stack . ';';
		}
		
		$css .= '}';
		$css .= '</style>';
		
		echo $css;
	}
}
add_action( 'wp_head', 'prospero_add_font_css', 98 );

/**
 * Get font stack with fallbacks
 */
function prospero_get_font_stack( $font ) {
	if ( empty( $font ) || $font === 'system' ) {
		return false;
	}
	
	$font = prospero_sanitize_font_name( $font );
	
	// System font fallback
	$fallback = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif';
	
	// Add quotes around font name if it contains spaces
	if ( strpos( $font, ' ' ) !== false ) {
		$font = '"' . $font . '"';
	}
	
	return $font . ', ' . $fallback;
}

/**
 * Admin notice to inform about font download on first use
 */
function prospero_font_download_notice() {
	$heading_font = get_theme_mod( 'prospero_heading_font', 'system' );
	$body_font = get_theme_mod( 'prospero_body_font', 'system' );
	
	$fonts_to_check = array();
	if ( $heading_font !== 'system' ) {
		$fonts_to_check[] = $heading_font;
	}
	if ( $body_font !== 'system' && $body_font !== $heading_font ) {
		$fonts_to_check[] = $body_font;
	}
	
	$pending_fonts = array();
	foreach ( $fonts_to_check as $font ) {
		if ( ! prospero_is_font_local( $font ) ) {
			$pending_fonts[] = $font;
		}
	}
	
	if ( ! empty( $pending_fonts ) ) {
		?>
		<div class="notice notice-info is-dismissible">
			<p>
				<strong><?php esc_html_e( 'Prospero Theme:', 'prospero-theme' ); ?></strong>
				<?php
				printf(
					/* translators: %s: list of font names */
					esc_html__( 'Google Fonts will be downloaded locally on the next page load: %s', 'prospero-theme' ),
					esc_html( implode( ', ', $pending_fonts ) )
				);
				?>
			</p>
		</div>
		<?php
	}
}
add_action( 'admin_notices', 'prospero_font_download_notice' );
