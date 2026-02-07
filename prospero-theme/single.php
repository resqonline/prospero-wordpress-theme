<?php
/**
 * Single Post Template
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();

$featured_position = get_theme_mod( 'prospero_single_featured_position', 'below' );
$show_featured     = has_post_thumbnail() && 'hidden' !== $featured_position;
?>

<main id="main-content" class="site-main single-post">
	<?php prospero_breadcrumbs( array( 'wrapper_class' => 'breadcrumbs container' ) ); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<?php
		// Featured image above title
		if ( $show_featured && 'above' === $featured_position ) :
		?>
			<div class="post-featured-image post-featured-image-above">
				<div class="container">
					<?php the_post_thumbnail( 'full', array( 'class' => 'post-thumbnail' ) ); ?>
				</div>
			</div>
		<?php endif; ?>

		<header class="post-header">
			<div class="container">
				<?php
				$categories = get_the_category();
				if ( $categories && ! is_wp_error( $categories ) ) :
				?>
					<div class="post-categories">
						<?php foreach ( $categories as $category ) : ?>
							<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="post-category">
								<?php echo esc_html( $category->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<h1 class="post-title"><?php the_title(); ?></h1>

				<div class="post-meta">
					<span class="post-date">
						<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
							<?php echo esc_html( get_the_date() ); ?>
						</time>
					</span>
					<span class="post-author">
						<?php
						printf(
							/* translators: %s: Author name */
							esc_html__( 'by %s', 'prospero-theme' ),
							'<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
						);
						?>
					</span>
					<?php
					$reading_time = prospero_get_reading_time( get_the_content() );
					if ( $reading_time ) :
					?>
						<span class="post-reading-time">
							<?php
							printf(
								/* translators: %d: Reading time in minutes */
								esc_html( _n( '%d min read', '%d min read', $reading_time, 'prospero-theme' ) ),
								$reading_time
							);
							?>
						</span>
					<?php endif; ?>
				</div>

				<?php
				$tags = get_the_tags();
				if ( $tags && ! is_wp_error( $tags ) ) :
				?>
					<div class="post-tags">
						<?php foreach ( $tags as $tag ) : ?>
							<a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="post-tag">
								<?php echo esc_html( $tag->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</header>

		<?php
		// Featured image below title
		if ( $show_featured && 'below' === $featured_position ) :
		?>
			<div class="post-featured-image">
				<div class="container">
					<?php the_post_thumbnail( 'full', array( 'class' => 'post-thumbnail' ) ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="post-content-wrapper">
			<div class="container">
				<div class="post-content">
					<?php the_content(); ?>
				</div>

				<?php
				// Post pagination for multi-page posts
				wp_link_pages(
					array(
						'before'      => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'prospero-theme' ) . '"><span class="page-links-title">' . esc_html__( 'Pages:', 'prospero-theme' ) . '</span>',
						'after'       => '</nav>',
						'link_before' => '<span class="page-link">',
						'link_after'  => '</span>',
					)
				);
				?>
			</div>
		</div>

		<?php
		// Author bio
		$author_bio = get_the_author_meta( 'description' );
		if ( $author_bio ) :
		?>
		<footer class="post-footer">
			<div class="container">
				<div class="author-bio">
					<div class="author-avatar">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 80 ); ?>
					</div>
					<div class="author-info">
						<h3 class="author-name">
							<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
								<?php the_author(); ?>
							</a>
						</h3>
						<p class="author-description"><?php echo esc_html( $author_bio ); ?></p>
					</div>
				</div>
			</div>
		</footer>
		<?php endif; ?>

		<?php
		// Related Posts - same category or random
		$related_args = array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
			'orderby'        => 'rand',
		);

		// Try to get posts from the same category
		if ( $categories && ! is_wp_error( $categories ) ) {
			$category_ids = wp_list_pluck( $categories, 'term_id' );
			$related_args['category__in'] = $category_ids;
		}

		$related_posts = new WP_Query( $related_args );

		// If no related posts in same category, get random ones
		if ( ! $related_posts->have_posts() && $categories && ! is_wp_error( $categories ) ) {
			unset( $related_args['category__in'] );
			$related_posts = new WP_Query( $related_args );
		}

		if ( $related_posts->have_posts() ) :
		?>
		<section class="related-posts">
			<div class="container">
				<h2 class="section-title"><?php esc_html_e( 'Related Posts', 'prospero-theme' ); ?></h2>
				<div class="related-posts-grid">
					<?php while ( $related_posts->have_posts() ) : $related_posts->the_post(); ?>
						<article class="related-post-card">
							<a href="<?php the_permalink(); ?>" class="related-post-link">
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="related-post-thumbnail">
										<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
									</div>
								<?php endif; ?>
								<div class="related-post-content">
									<h3 class="related-post-title"><?php the_title(); ?></h3>
									<span class="related-post-date">
										<?php echo esc_html( get_the_date() ); ?>
									</span>
								</div>
							</a>
						</article>
					<?php endwhile; ?>
				</div>
			</div>
		</section>
		<?php
		wp_reset_postdata();
		endif;
		?>

		<?php endwhile; endif; ?>
	</article>
</main>

<?php
get_footer();
