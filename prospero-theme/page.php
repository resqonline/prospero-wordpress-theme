<?php
/**
 * The page template
 *
 * Used for displaying single pages.
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main page-template" role="main">
	<?php prospero_breadcrumbs( array( 'wrapper_class' => 'breadcrumbs container' ) ); ?>

	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<header class="page-header">
				<div class="container">
					<h1 class="page-title"><?php the_title(); ?></h1>
				</div>
			</header>

			<div class="page-content">
				<div class="container">
					<?php the_content(); ?>

					<?php
					// Page pagination for multi-page content
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
		</article>

	<?php endwhile; endif; ?>
</main>

<?php
get_footer();
