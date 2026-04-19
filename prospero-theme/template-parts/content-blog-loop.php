<?php
/**
 * Template part for displaying blog posts in grid or list layout
 *
 * Used by home.php, archive.php, category.php, tag.php, author.php,
 * search.php. Each individual article is rendered by
 * `template-parts/content-blog-item.php` so the initial page load and
 * the ajax filter response (see inc/ajax-filters.php) stay byte-for-byte
 * identical.
 *
 * @package Prospero
 * @since 1.0.0
 */

// Get layout settings
$blog_layout  = get_theme_mod( 'prospero_blog_layout', 'grid' );
$blog_columns = get_theme_mod( 'prospero_blog_columns', '3' );
$show_excerpt = get_theme_mod( 'prospero_blog_grid_excerpt', true );

if ( have_posts() ) :
	if ( 'grid' === $blog_layout ) : ?>
		<div class="posts-grid" data-columns="<?php echo esc_attr( $blog_columns ); ?>">
	<?php else : ?>
		<div class="posts-list">
	<?php endif;

	$item_args = array(
		'layout'       => $blog_layout,
		'show_excerpt' => (bool) $show_excerpt,
		'allow_sticky' => ! is_paged(),
	);

	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content-blog-item', null, $item_args );
	endwhile;
	?>
	</div>
	<?php

	// Pagination (skip on home page as it has its own AJAX-enabled pagination container)
	if ( ! is_home() ) :
		the_posts_pagination( array(
			'mid_size'  => 2,
			'prev_text' => esc_html__( '&laquo; Previous', 'prospero-theme' ),
			'next_text' => esc_html__( 'Next &raquo;', 'prospero-theme' ),
		) );
	endif;

else :
	?>
	<div class="no-posts">
		<p><?php esc_html_e( 'No posts found.', 'prospero-theme' ); ?></p>
	</div>
	<?php
endif;
