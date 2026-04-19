<?php
/**
 * Single Team Member Template
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();

$image_style = get_theme_mod( 'prospero_team_image_style', 'square' );
?>

<main id="main-content" class="site-main single-team">
	<?php prospero_breadcrumbs( array( 'wrapper_class' => 'breadcrumbs container' ) ); ?>

	<article id="team-<?php the_ID(); ?>" <?php post_class(); ?>>
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<div class="container">
			<div class="single-team-profile">
				<?php get_template_part( 'template-parts/content-team-detail', null, array(
					'image_style' => $image_style,
					'heading_tag' => 'h1',
				) ); ?>
			</div>
		</div>
		<?php endwhile; endif; ?>
	</article>
</main>

<?php get_footer();
