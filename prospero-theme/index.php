<?php
/**
 * The main template file
 *
 * This is the fallback template used when no more specific template is found.
 * It uses the same blog layout settings as home.php and archive.php.
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();

$blog_layout  = get_theme_mod( 'prospero_blog_layout', 'grid' );
$layout_class = 'layout-' . $blog_layout;
?>

<main id="main-content" class="site-main blog-template <?php echo esc_attr( $layout_class ); ?>" role="main">
	<?php prospero_breadcrumbs( array( 'wrapper_class' => 'breadcrumbs container' ) ); ?>

	<div class="container">
		<?php
		// Show page title if this is the blog page (not front page)
		if ( is_home() && ! is_front_page() ) :
			?>
			<header class="page-header">
				<h1 class="page-title"><?php single_post_title(); ?></h1>
			</header>
			<?php
		endif;
		?>

		<div class="blog-posts">
			<?php get_template_part( 'template-parts/content-blog-loop' ); ?>
		</div>
	</div>
</main>

<?php
get_footer();
