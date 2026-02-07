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

	// Build query args
	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'paged'          => $paged,
		'post_status'    => 'publish',
	);

	// Add category filter if not 'all'
	if ( 'all' !== $category && ! empty( $category ) ) {
		$args['category_name'] = $category;
	}

	// Handle sticky posts on first page
	if ( 1 === $paged && 'all' === $category ) {
		$args['ignore_sticky_posts'] = false;
	} else {
		$args['ignore_sticky_posts'] = true;
	}

	// Query posts
	$query = new WP_Query( $args );

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

	// Build HTML output
	ob_start();

	if ( 'grid' === $blog_layout ) :
		?>
		<div class="posts-grid" data-columns="<?php echo esc_attr( $blog_columns ); ?>">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$is_sticky   = is_sticky() && 1 === $paged && 'all' === $category;
				$item_class  = 'blog-post-item';
				$item_class .= $is_sticky ? ' sticky-post' : '';
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( $item_class ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="post-thumbnail">
							<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
								<?php the_post_thumbnail( $is_sticky ? 'large' : 'medium_large' ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="post-content">
						<?php if ( $is_sticky ) : ?>
							<span class="sticky-label"><?php esc_html_e( 'Featured', 'prospero-theme' ); ?></span>
						<?php endif; ?>

						<header class="entry-header">
							<h2 class="entry-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<div class="entry-meta">
								<span class="posted-on">
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
										<?php echo esc_html( get_the_date() ); ?>
									</time>
								</span>
							</div>
						</header>

						<?php if ( $show_excerpt || $is_sticky ) : ?>
							<div class="entry-summary">
								<?php the_excerpt(); ?>
							</div>
						<?php endif; ?>

						<footer class="entry-footer">
							<a href="<?php the_permalink(); ?>" class="read-more button button-secondary">
								<?php esc_html_e( 'Read More', 'prospero-theme' ); ?>
							</a>
						</footer>
					</div>
				</article>
				<?php
			endwhile;
			?>
		</div>
		<?php
	else :
		// List Layout
		?>
		<div class="posts-list">
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				$is_sticky   = is_sticky() && 1 === $paged && 'all' === $category;
				$item_class  = 'blog-list-item';
				$item_class .= $is_sticky ? ' sticky-post' : '';
				$item_class .= has_post_thumbnail() ? ' has-thumbnail' : '';
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( $item_class ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="post-thumbnail">
							<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
								<?php the_post_thumbnail( 'medium' ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="post-content">
						<?php if ( $is_sticky ) : ?>
							<span class="sticky-label"><?php esc_html_e( 'Featured', 'prospero-theme' ); ?></span>
						<?php endif; ?>

						<header class="entry-header">
							<h2 class="entry-title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<div class="entry-meta">
								<span class="posted-on">
									<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
										<?php echo esc_html( get_the_date() ); ?>
									</time>
								</span>
								<span class="byline">
									<?php esc_html_e( 'by', 'prospero-theme' ); ?>
									<span class="author"><?php the_author(); ?></span>
								</span>
							</div>
						</header>

						<div class="entry-summary">
							<?php the_excerpt(); ?>
						</div>

						<footer class="entry-footer">
							<a href="<?php the_permalink(); ?>" class="read-more button button-secondary">
								<?php esc_html_e( 'Read More', 'prospero-theme' ); ?>
							</a>
						</footer>
					</div>
				</article>
				<?php
			endwhile;
			?>
		</div>
		<?php
	endif;

	$html = ob_get_clean();

	// Build pagination
	ob_start();
	$big = 999999999;
	echo paginate_links( array(
		'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'    => '?paged=%#%',
		'current'   => $paged,
		'total'     => $query->max_num_pages,
		'prev_text' => esc_html__( '&laquo; Previous', 'prospero-theme' ),
		'next_text' => esc_html__( 'Next &raquo;', 'prospero-theme' ),
		'type'      => 'list',
	) );
	$pagination = ob_get_clean();

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
