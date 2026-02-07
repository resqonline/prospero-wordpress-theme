<?php
/**
 * Template Name: Blog
 * Description: Blog listing template with sidebar
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main blog-template">
	<div class="container">
		<header class="page-header">
			<h1 class="page-title"><?php echo esc_html( get_the_title() ); ?></h1>
			<?php
			$description = get_the_content();
			if ( $description ) :
				?>
				<div class="page-description">
					<?php echo wp_kses_post( wpautop( $description ) ); ?>
				</div>
				<?php
			endif;
			?>
		</header>

		<div class="blog-posts">
			<?php
			$paged = ( get_query_var( 'paged' ) ) ? absint( get_query_var( 'paged' ) ) : 1;
			
			$blog_query = new WP_Query( array(
				'post_type'      => 'post',
				'posts_per_page' => get_option( 'posts_per_page' ),
				'paged'          => $paged,
				'post_status'    => 'publish',
			) );

			if ( $blog_query->have_posts() ) :
				?>
				<div class="posts-grid">
					<?php
					while ( $blog_query->have_posts() ) :
						$blog_query->the_post();
						?>
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post-item' ); ?>>
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="post-thumbnail">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'large' ); ?>
									</a>
								</div>
							<?php endif; ?>
							
							<div class="post-content">
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
				// Pagination
				the_posts_pagination( array(
					'mid_size'  => 2,
					'prev_text' => esc_html__( '&laquo; Previous', 'prospero-theme' ),
					'next_text' => esc_html__( 'Next &raquo;', 'prospero-theme' ),
				) );
				
				wp_reset_postdata();
			else :
				?>
				<div class="no-posts">
					<p><?php esc_html_e( 'No posts found.', 'prospero-theme' ); ?></p>
				</div>
				<?php
			endif;
			?>
		</div>
	</div>
</main>

<?php
get_footer();
