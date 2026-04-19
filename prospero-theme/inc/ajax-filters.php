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

/**
 * Ajax handler for filtering blog posts by category
 */
function prospero_filter_blog_ajax() {
	// Verify nonce
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'prospero_filter_blog' ) ) {
		wp_send_json_error( array(
			'message' => esc_html__( 'Security verification failed.', 'prospero-theme' ),
		) );
	}

	// Get filter parameters
	$category = isset( $_POST['category'] ) ? sanitize_text_field( wp_unslash( $_POST['category'] ) ) : 'all';
	$paged    = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

	// Build query args. `orderby` + `order` are set explicitly so the ajax
	// response always matches the main archive's newest-first ordering,
	// regardless of any `pre_get_posts` filter that might flip defaults.
	// Term cache is left at its default (primed) because the shared
	// `content-blog-item.php` template runs `post_class()`, which calls
	// `get_the_category()` per post - without the cache primed that's an
	// extra query per article on every filter response.
	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'paged'          => $paged,
		'post_status'    => 'publish',
		'orderby'        => 'date',
		'order'          => 'DESC',
	);

	// Add category filter if not 'all'
	if ( 'all' !== $category && ! empty( $category ) ) {
		$args['category_name'] = $category;
	}

	// Replicate WP core's home-query sticky-prepend behavior. WP_Query
	// only auto-prepends sticky posts when `$this->is_home && $page <= 1`
	// - which is never true for an admin-ajax custom query - so setting
	// `ignore_sticky_posts => false` alone does NOT move stickies to the
	// top. Without this block, clicking "Alle" on the filter bar shows
	// the sticky post in its natural date position instead of pinned
	// above the rest.
	$prepend_sticky = ( 1 === $paged && 'all' === $category );
	$sticky_ids     = array();
	if ( $prepend_sticky ) {
		$sticky_ids = array_map( 'intval', (array) get_option( 'sticky_posts', array() ) );
	}

	// We always handle sticky manually; let the regular query treat them
	// as normal posts (they'll be pulled out + prepended below on page 1).
	$args['ignore_sticky_posts'] = true;

	// Query posts
	$query = new WP_Query( $args );

	if ( $prepend_sticky && ! empty( $sticky_ids ) ) {
		// Drop any sticky posts from the natural-order page so they're
		// not rendered twice after we prepend the dedicated sticky fetch.
		$regular_posts = array();
		foreach ( $query->posts as $post ) {
			if ( ! in_array( (int) $post->ID, $sticky_ids, true ) ) {
				$regular_posts[] = $post;
			}
		}

		// Fetch sticky posts themselves - newest first, published only -
		// so a sticky post that would naturally sit on a later page by
		// date still lands at the top of page 1.
		$sticky_posts = get_posts(
			array(
				'post__in'            => $sticky_ids,
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => count( $sticky_ids ),
				'orderby'             => 'date',
				'order'               => 'DESC',
				'ignore_sticky_posts' => true,
			)
		);

		if ( ! empty( $sticky_posts ) ) {
			$query->posts      = array_merge( $sticky_posts, $regular_posts );
			$query->post_count = count( $query->posts );
		}
	}

	if ( ! $query->have_posts() ) {
		wp_send_json_success( array(
			'html'       => '<div class="no-posts"><p>' . esc_html__( 'No posts found.', 'prospero-theme' ) . '</p></div>',
			'count'      => 0,
			'pagination' => '',
		) );
	}

	// Get layout settings
	$blog_layout  = get_theme_mod( 'prospero_blog_layout', 'grid' );
	$blog_columns = get_theme_mod( 'prospero_blog_columns', '3' );
	$show_excerpt = get_theme_mod( 'prospero_blog_grid_excerpt', true );

	// Build HTML output using the same template part the main archive
	// uses, so excerpt length / layout / class names stay consistent.
	$item_args = array(
		'layout'       => $blog_layout,
		'show_excerpt' => (bool) $show_excerpt,
		'allow_sticky' => ( 1 === $paged && 'all' === $category ),
	);

	// Guarantee the theme's auto-excerpt length applies to the AJAX
	// response, matching what home.php's main query renders. Some setups
	// (plugins with `is_main_query()` / `is_home()`-gated filters) leave
	// our registered `prospero_excerpt_length` bypassed in the admin-ajax
	// context, which surfaces as filtered posts showing an excerpt
	// roughly twice as long as the initial archive (WP's hardcoded default
	// is 55 words, ours is 30). A scoped PHP_INT_MAX-priority callback
	// wins against any other registered filter for the duration of the
	// render loop and is removed before we send the response.
	$ajax_excerpt_length = static function () {
		return function_exists( 'prospero_get_excerpt_length' )
			? prospero_get_excerpt_length()
			: 30;
	};
	add_filter( 'excerpt_length', $ajax_excerpt_length, PHP_INT_MAX );

	ob_start();
	if ( 'grid' === $blog_layout ) : ?>
		<div class="posts-grid" data-columns="<?php echo esc_attr( $blog_columns ); ?>">
	<?php else : ?>
		<div class="posts-list">
	<?php endif;

	while ( $query->have_posts() ) :
		$query->the_post();
		get_template_part( 'template-parts/content-blog-item', null, $item_args );
	endwhile;
	?>
	</div>
	<?php
	$html = ob_get_clean();

	remove_filter( 'excerpt_length', $ajax_excerpt_length, PHP_INT_MAX );

	// Build pagination. We mirror the markup produced by
	// `the_posts_pagination()` on the initial archive render (used by
	// home.php) so swapping the container's innerHTML on a filter click
	// doesn't reshape the pagination wrapper / heading. `paginate_links()`
	// with the default `plain` type returns the same anchor / span
	// sequence WordPress wraps in core's `_navigation_markup()`, so we
	// just wrap it manually with the matching nav structure.
	$big         = 999999999;
	$page_links  = paginate_links(
		array(
			'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'    => '?paged=%#%',
			'current'   => $paged,
			'total'     => $query->max_num_pages,
			'mid_size'  => 2,
			'prev_text' => esc_html__( '&laquo; Previous', 'prospero-theme' ),
			'next_text' => esc_html__( 'Next &raquo;', 'prospero-theme' ),
		)
	);

	if ( $page_links ) {
		$pagination  = '<nav class="navigation pagination" aria-label="' . esc_attr__( 'Posts', 'prospero-theme' ) . '">';
		$pagination .= '<h2 class="screen-reader-text">' . esc_html__( 'Posts navigation', 'prospero-theme' ) . '</h2>';
		$pagination .= '<div class="nav-links">' . $page_links . '</div>';
		$pagination .= '</nav>';
	} else {
		$pagination = '';
	}

	wp_reset_postdata();

	// Send success response
	wp_send_json_success( array(
		'html'       => $html,
		'count'      => $query->found_posts,
		'pagination' => $pagination,
		'max_pages'  => $query->max_num_pages,
	) );
}
add_action( 'wp_ajax_prospero_filter_blog', 'prospero_filter_blog_ajax' );
add_action( 'wp_ajax_nopriv_prospero_filter_blog', 'prospero_filter_blog_ajax' );
