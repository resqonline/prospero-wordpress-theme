<?php
/**
 * Security Functions
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Remove WordPress version from head
 */
remove_action( 'wp_head', 'wp_generator' );

/**
 * Remove RSD link
 */
remove_action( 'wp_head', 'rsd_link' );

/**
 * Remove wlwmanifest link
 */
remove_action( 'wp_head', 'wlwmanifest_link' );

/**
 * Disable XML-RPC
 */
add_filter( 'xmlrpc_enabled', '__return_false' );

/**
 * Remove REST API links from head
 */
remove_action( 'wp_head', 'rest_output_link_wp_head' );
remove_action( 'template_redirect', 'rest_output_link_header', 11 );

/**
 * Secure database queries helper
 * Example usage in custom code
 */
function prospero_secure_query_example() {
	global $wpdb;
	
	// Always use $wpdb->prepare for queries
	// Example: $results = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->posts} WHERE ID = %d", $post_id ) );
}
