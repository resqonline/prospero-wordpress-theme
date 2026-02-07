<?php
/**
 * Single Project Template
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();

// Get project meta
$project_url = get_post_meta( get_the_ID(), '_prospero_project_url', true );
$gallery_ids = get_post_meta( get_the_ID(), '_prospero_project_gallery', true );
$testimonial_id = get_post_meta( get_the_ID(), '_prospero_project_testimonial', true );

// Get taxonomies
$categories = get_the_terms( get_the_ID(), 'project_category' );
$tags = get_the_terms( get_the_ID(), 'project_tag' );
?>

<main id="main-content" class="site-main single-project">
	<?php prospero_breadcrumbs( array( 'wrapper_class' => 'breadcrumbs container' ) ); ?>

	<article id="project-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		
		<header class="project-header">
			<div class="container">
				<?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
					<div class="project-categories">
						<?php foreach ( $categories as $category ) : ?>
							<a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="project-category">
								<?php echo esc_html( $category->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
				
				<h1 class="project-title"><?php the_title(); ?></h1>
				
				<?php if ( $tags && ! is_wp_error( $tags ) ) : ?>
					<div class="project-tags">
						<?php foreach ( $tags as $tag ) : ?>
							<span class="project-tag"><?php echo esc_html( $tag->name ); ?></span>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( has_post_thumbnail() ) : ?>
			<div class="project-featured-image">
				<div class="container">
					<?php the_post_thumbnail( 'full', array( 'class' => 'project-thumbnail' ) ); ?>
				</div>
			</div>
		<?php endif; ?>

		<div class="project-content-wrapper">
			<div class="container">
				<div class="project-content">
					<?php the_content(); ?>
				</div>

				<?php if ( $project_url ) : ?>
					<div class="project-website">
						<a href="<?php echo esc_url( $project_url ); ?>" class="button button-primary" target="_blank" rel="noopener noreferrer">
							<?php esc_html_e( 'Visit Project Website', 'prospero-theme' ); ?>
						</a>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<?php if ( ! empty( $gallery_ids ) && is_array( $gallery_ids ) ) : ?>
			<div class="project-gallery-section">
				<div class="container">
					<h2 class="section-title"><?php esc_html_e( 'Project Gallery', 'prospero-theme' ); ?></h2>
					<div class="project-gallery">
						<?php foreach ( $gallery_ids as $image_id ) : 
							$image_url = wp_get_attachment_image_url( $image_id, 'large' );
							$image_full = wp_get_attachment_image_url( $image_id, 'full' );
							$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
							if ( $image_url ) :
						?>
							<a href="<?php echo esc_url( $image_full ); ?>" class="gallery-item" data-lightbox="project-gallery">
								<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( $image_alt ); ?>" loading="lazy" />
							</a>
						<?php 
							endif;
						endforeach; ?>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php if ( $testimonial_id ) : 
			$testimonial = get_post( $testimonial_id );
			if ( $testimonial && $testimonial->post_status === 'publish' ) :
				$display_name = get_post_meta( $testimonial_id, '_prospero_testimonial_display_name', true );
				$author_name = $display_name ? $display_name : $testimonial->post_title;
		?>
			<div class="project-testimonial-section">
				<div class="container">
					<div class="project-testimonial">
						<?php if ( has_post_thumbnail( $testimonial_id ) ) : ?>
							<div class="testimonial-image">
								<?php echo get_the_post_thumbnail( $testimonial_id, 'thumbnail', array( 'class' => 'testimonial-thumbnail' ) ); ?>
							</div>
						<?php endif; ?>
						<div class="testimonial-content">
							<blockquote class="testimonial-text">
								<?php echo wp_kses_post( apply_filters( 'the_content', $testimonial->post_content ) ); ?>
							</blockquote>
							<cite class="testimonial-author"><?php echo esc_html( $author_name ); ?></cite>
						</div>
					</div>
				</div>
			</div>
		<?php 
			endif;
		endif; ?>

		<?php
		// Related Projects - same category or random if no category
		$related_args = array(
			'post_type'      => 'project',
			'posts_per_page' => 3,
			'post__not_in'   => array( get_the_ID() ),
			'orderby'        => 'rand',
		);

		// Try to get projects from the same category
		if ( $categories && ! is_wp_error( $categories ) ) {
			$category_ids = wp_list_pluck( $categories, 'term_id' );
			$related_args['tax_query'] = array(
				array(
					'taxonomy' => 'project_category',
					'field'    => 'term_id',
					'terms'    => $category_ids,
				),
			);
		}

		$related_projects = new WP_Query( $related_args );

		// If no related projects in same category, get random ones
		if ( ! $related_projects->have_posts() && $categories && ! is_wp_error( $categories ) ) {
			unset( $related_args['tax_query'] );
			$related_projects = new WP_Query( $related_args );
		}

		if ( $related_projects->have_posts() ) :
		?>
		<section class="related-projects">
			<div class="container">
				<h2 class="section-title"><?php esc_html_e( 'Related Projects', 'prospero-theme' ); ?></h2>
				<div class="related-projects-grid">
					<?php while ( $related_projects->have_posts() ) : $related_projects->the_post(); ?>
						<article class="related-project-card">
							<a href="<?php the_permalink(); ?>" class="related-project-link">
								<?php if ( has_post_thumbnail() ) : ?>
									<div class="related-project-thumbnail">
										<?php the_post_thumbnail( 'medium', array( 'loading' => 'lazy' ) ); ?>
									</div>
								<?php endif; ?>
								<h3 class="related-project-title"><?php the_title(); ?></h3>
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
