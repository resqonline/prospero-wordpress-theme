<?php
/**
 * Template Name: Team
 * Description: Team members listing template grouped by category
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main team-template">
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
		// Check if team post type is enabled
		if ( ! get_theme_mod( 'prospero_enable_team', true ) ) :
			?>
			<div class="notice notice-info">
				<p><?php esc_html_e( 'Team custom post type is not enabled. Please enable it in the Customizer.', 'prospero-theme' ); ?></p>
			</div>
			<?php
		else :
			// Get all team categories (ordered by menu_order/name)
			$team_categories = get_terms( array(
				'taxonomy'   => 'team_category',
				'hide_empty' => true,
				'orderby'    => 'name',
				'order'      => 'ASC',
			) );

			if ( ! empty( $team_categories ) && ! is_wp_error( $team_categories ) ) :
				// Display team members grouped by category
				foreach ( $team_categories as $category ) :
					// Query team members in this category
					$team_query = new WP_Query( array(
						'post_type'      => 'team',
						'posts_per_page' => -1,
						'post_status'    => 'publish',
						'orderby'        => 'menu_order',
						'order'          => 'ASC',
						'tax_query'      => array(
							array(
								'taxonomy' => 'team_category',
								'field'    => 'term_id',
								'terms'    => $category->term_id,
							),
						),
					) );

					if ( $team_query->have_posts() ) :
						?>
						<section class="team-category-section" id="team-<?php echo esc_attr( $category->slug ); ?>">
							<h2 class="team-category-title"><?php echo esc_html( $category->name ); ?></h2>
							<?php if ( $category->description ) : ?>
								<p class="team-category-description"><?php echo esc_html( $category->description ); ?></p>
							<?php endif; ?>

							<div class="team-grid">
								<?php
								while ( $team_query->have_posts() ) :
									$team_query->the_post();
									get_template_part( 'template-parts/content', 'team', array(
										'show_excerpt' => true,
									) );
								endwhile;
								wp_reset_postdata();
								?>
							</div>
						</section>
						<?php
					endif;
				endforeach;
			else :
				// No categories - show all team members without grouping
				$team_query = new WP_Query( array(
					'post_type'      => 'team',
					'posts_per_page' => -1,
					'post_status'    => 'publish',
					'orderby'        => 'menu_order',
					'order'          => 'ASC',
				) );

				if ( $team_query->have_posts() ) :
					?>
					<div class="team-grid">
						<?php
						while ( $team_query->have_posts() ) :
							$team_query->the_post();
							get_template_part( 'template-parts/content', 'team', array(
								'show_excerpt' => true,
							) );
						endwhile;
						wp_reset_postdata();
						?>
					</div>
					<?php
				else :
					?>
					<div class="no-team-members">
						<p><?php esc_html_e( 'No team members found.', 'prospero-theme' ); ?></p>
					</div>
					<?php
				endif;
			endif;
		endif;
		?>
	</div>
</main>

<?php
get_footer();
