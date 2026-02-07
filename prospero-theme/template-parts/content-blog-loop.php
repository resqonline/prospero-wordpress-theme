<?php
/**
 * Template part for displaying blog posts in grid or list layout
 *
 * Used by home.php, archive.php, category.php, tag.php, author.php, search.php
 *
 * @package Prospero
 * @since 1.0.0
 */

// Get layout settings
$blog_layout  = get_theme_mod( 'prospero_blog_layout', 'grid' );
$blog_columns = get_theme_mod( 'prospero_blog_columns', '3' );
$show_excerpt = get_theme_mod( 'prospero_blog_grid_excerpt', true );

if ( have_posts() ) :
	if ( 'grid' === $blog_layout ) :
		// Grid Layout
		?>
		<div class="posts-grid" data-columns="<?php echo esc_attr( $blog_columns ); ?>">
			<?php
			while ( have_posts() ) :
				the_post();
				$is_sticky   = is_sticky() && ! is_paged();
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
			while ( have_posts() ) :
				the_post();
				$is_sticky   = is_sticky() && ! is_paged();
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
								<?php if ( function_exists( 'prospero_get_reading_time' ) ) : ?>
									<span class="reading-time">
										<?php echo esc_html( prospero_get_reading_time( get_the_ID() ) ); ?>
									</span>
								<?php endif; ?>
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
