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

$page_fullwidth_featured = has_post_thumbnail() && prospero_should_show_fullwidth_featured();
?>

<main id="main-content" class="site-main page-template" role="main">
	<?php if ( $page_fullwidth_featured ) : ?>
		<div class="post-featured-image post-featured-image-fullwidth">
			<?php echo get_the_post_thumbnail( get_queried_object_id(), 'full', array( 'class' => 'post-thumbnail' ) ); ?>
		</div>
	<?php endif; ?>

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
