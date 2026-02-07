<?php
/**
 * Template Name: Projects
 * Description: Projects listing template with Ajax filtering
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main projects-template">
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

		<?php
		// Check if projects post type is enabled
		if ( ! get_theme_mod( 'prospero_enable_projects', true ) ) :
			?>
			<div class="notice notice-info">
				<p><?php esc_html_e( 'Projects custom post type is not enabled. Please enable it in the Customizer.', 'prospero-theme' ); ?></p>
			</div>
			<?php
		else :
			// Get all project tags for filtering
			$project_tags = get_terms( array(
				'taxonomy'   => 'project_tag',
				'hide_empty' => true,
			) );

			if ( ! empty( $project_tags ) && ! is_wp_error( $project_tags ) ) :
				?>
				<div class="projects-filters">
					<button class="filter-button active" data-tag="all" data-ajax-nonce="<?php echo esc_attr( wp_create_nonce( 'prospero_filter_projects' ) ); ?>">
						<?php esc_html_e( 'All', 'prospero-theme' ); ?>
					</button>
					<?php foreach ( $project_tags as $tag ) : ?>
						<button class="filter-button" data-tag="<?php echo esc_attr( $tag->slug ); ?>">
							<?php echo esc_html( $tag->name ); ?>
						</button>
					<?php endforeach; ?>
				</div>
				<?php
			endif;
			?>

			<div class="projects-grid" id="projects-grid">
				<?php
				$projects_query = new WP_Query( array(
					'post_type'      => 'project',
					'posts_per_page' => 12,
					'post_status'    => 'publish',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				) );

				if ( $projects_query->have_posts() ) :
					while ( $projects_query->have_posts() ) :
						$projects_query->the_post();
						get_template_part( 'template-parts/content', 'project' );
					endwhile;
					wp_reset_postdata();
				else :
					?>
					<div class="no-projects">
						<p><?php esc_html_e( 'No projects found.', 'prospero-theme' ); ?></p>
					</div>
					<?php
				endif;
				?>
			</div>

			<div class="projects-loading" style="display: none;">
				<span class="loading-spinner"></span>
				<p><?php esc_html_e( 'Loading projects...', 'prospero-theme' ); ?></p>
			</div>
			<?php
		endif;
		?>
	</div>
</main>

<?php
get_footer();
