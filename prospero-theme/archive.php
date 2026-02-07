<?php
/**
 * The archive template
 *
 * Used for category, tag, author, date, and other archives.
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();

$blog_layout  = get_theme_mod( 'prospero_blog_layout', 'grid' );
$layout_class = 'layout-' . $blog_layout;
?>

<main id="main-content" class="site-main blog-template archive-template <?php echo esc_attr( $layout_class ); ?>" role="main">
	<?php prospero_breadcrumbs( array( 'wrapper_class' => 'breadcrumbs container' ) ); ?>

	<div class="container">
		<header class="page-header">
			<?php
			the_archive_title( '<h1 class="page-title">', '</h1>' );
			the_archive_description( '<div class="page-description">', '</div>' );
			?>
		</header>

		<div class="blog-posts">
			<?php get_template_part( 'template-parts/content-blog-loop' ); ?>
		</div>
	</div>
</main>

<?php
get_footer();
