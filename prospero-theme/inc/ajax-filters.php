<?php
/**
 * Ajax Filtering Functions
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajax handler for filtering projects
 */
function prospero_filter_projects_ajax() {
	// Verify nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'prospero_filter_projects' ) ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'Security verification failed.', 'prospero-theme' ),
		) );
	}
	
	// Get filter parameters
	$tag = isset( $_POST['tag'] ) ? sanitize_text_field( wp_unslash( $_POST['tag'] ) ) : 'all';
	$posts_per_page = isset( $_POST['count'] ) ? absint( $_POST['count'] ) : 12;
	$orderby = isset( $_POST['orderby'] ) ? sanitize_text_field( wp_unslash( $_POST['orderby'] ) ) : 'menu_order';
	$order = isset( $_POST['order'] ) ? sanitize_text_field( wp_unslash( $_POST['order'] ) ) : 'ASC';
	
	// Build query args
	$args = array(
		'post_type'      => 'project',
		'posts_per_page' => $posts_per_page,
		'orderby'        => $orderby,
		'order'          => $order,
		'post_status'    => 'publish',
	);
	
	// Add tag filter if not 'all'
	if ( $tag !== 'all' && ! empty( $tag ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'project_tag',
				'field'    => 'slug',
				'terms'    => $tag,
			),
		);
	}
	
	// Query projects
	$query = new WP_Query( $args );
	
	if ( ! $query->have_posts() ) {
		wp_send_json_success( array(
			'html'  => '<div class="no-projects"><p>' . esc_html__( 'No projects found.', 'prospero-theme' ) . '</p></div>',
			'count' => 0,
		) );
	}
	
	// Build HTML output
	ob_start();
	while ( $query->have_posts() ) :
		$query->the_post();
		get_template_part( 'template-parts/content', 'project' );
	endwhile;
	$html = ob_get_clean();
	wp_reset_postdata();
	
	// Send success response
	wp_send_json_success( array(
		'html'  => $html,
		'count' => $query->found_posts,
	) );
}
add_action( 'wp_ajax_prospero_filter_projects', 'prospero_filter_projects_ajax' );
add_action( 'wp_ajax_nopriv_prospero_filter_projects', 'prospero_filter_projects_ajax' );
