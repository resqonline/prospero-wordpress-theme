<?php
/**
 * The blog posts page template
 *
 * This template is used when a static page is set as the "Posts page"
 * in Settings > Reading, or when showing the latest posts on the front page.
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();

$blog_layout  = get_theme_mod( 'prospero_blog_layout', 'grid' );
$layout_class = 'layout-' . $blog_layout;

// Get categories that have posts
$categories = get_categories( array(
	'hide_empty' => true,
	'orderby'    => 'name',
	'order'      => 'ASC',
) );

$posts_page_id        = (int) get_option( 'page_for_posts' );
$blog_banner_fullwidth = $posts_page_id
	&& has_post_thumbnail( $posts_page_id )
	&& prospero_should_show_fullwidth_featured( $posts_page_id );
?>

<main id="main-content" class="site-main blog-template <?php echo esc_attr( $layout_class ); ?>" role="main">
	<?php if ( $blog_banner_fullwidth ) : ?>
		<div class="post-featured-image post-featured-image-fullwidth">
			<?php echo get_the_post_thumbnail( $posts_page_id, 'full', array( 'class' => 'post-thumbnail' ) ); ?>
		</div>
	<?php endif; ?>

	<?php prospero_breadcrumbs( array( 'wrapper_class' => 'breadcrumbs container' ) ); ?>

	<div class="container">
		<?php
		// Show page title if this is the posts page (not front page)
		if ( is_home() && ! is_front_page() ) :
			$posts_page_id = get_option( 'page_for_posts' );
			$posts_page    = get_post( $posts_page_id );
			?>
			<header class="page-header">
				<h1 class="page-title"><?php echo esc_html( get_the_title( $posts_page_id ) ); ?></h1>
				<?php
				// Show page content as description if it exists
				if ( $posts_page && ! empty( $posts_page->post_content ) ) :
					?>
					<div class="page-description">
						<?php echo wp_kses_post( wpautop( $posts_page->post_content ) ); ?>
					</div>
					<?php
				endif;
				?>
			</header>
			<?php
		endif;

		// Show category filter if there are categories
		if ( ! empty( $categories ) ) :
			?>
			<nav class="blog-category-filter" aria-label="<?php esc_attr_e( 'Filter posts by category', 'prospero-theme' ); ?>">
				<button type="button" class="filter-button active" data-category="all" aria-pressed="true">
					<?php esc_html_e( 'All', 'prospero-theme' ); ?>
				</button>
				<?php foreach ( $categories as $category ) : ?>
					<button type="button" class="filter-button" data-category="<?php echo esc_attr( $category->slug ); ?>" aria-pressed="false">
						<?php echo esc_html( $category->name ); ?>
					</button>
				<?php endforeach; ?>
			</nav>
			<?php
		endif;
		?>

		<div class="blog-posts" aria-live="polite">
			<?php get_template_part( 'template-parts/content-blog-loop' ); ?>
		</div>

		<div class="blog-pagination">
			<?php
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => esc_html__( '&laquo; Previous', 'prospero-theme' ),
				'next_text' => esc_html__( 'Next &raquo;', 'prospero-theme' ),
			) );
			?>
		</div>
	</div>
</main>

<?php
get_footer();
