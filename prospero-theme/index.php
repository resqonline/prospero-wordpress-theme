<?php
/**
 * The main template file
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main" role="main">
	<div class="container">
		<?php
		if ( have_posts() ) :
			// Check if this is a blog page
			if ( is_home() && ! is_front_page() ) :
				?>
				<header class="page-header">
					<h1 class="page-title"><?php single_post_title(); ?></h1>
				</header>
				<?php
			endif;

			// Start the Loop
			while ( have_posts() ) :
				the_post();
				get_template_part( 'template-parts/content', get_post_type() );
			endwhile;

			// Pagination
			the_posts_navigation();

		else :
			// No content found
			get_template_part( 'template-parts/content', 'none' );

		endif;
		?>
	</div>
</main>

<?php
get_footer();
