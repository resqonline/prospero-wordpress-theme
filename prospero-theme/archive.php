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

// Borrow the Posts page banner on post-type=post archives (category, tag,
// date, author, post archive). Custom post type archives keep their own templates.
$is_post_archive       = is_category() || is_tag() || is_author() || is_date() || is_post_type_archive( 'post' );
$posts_page_id         = (int) get_option( 'page_for_posts' );
$blog_banner_fullwidth = $is_post_archive
	&& $posts_page_id
	&& has_post_thumbnail( $posts_page_id )
	&& prospero_should_show_fullwidth_featured( $posts_page_id );
?>

<main id="main-content" class="site-main blog-template archive-template <?php echo esc_attr( $layout_class ); ?>" role="main">
	<?php if ( $blog_banner_fullwidth ) : ?>
		<div class="post-featured-image post-featured-image-fullwidth">
			<?php echo get_the_post_thumbnail( $posts_page_id, 'full', array( 'class' => 'post-thumbnail' ) ); ?>
		</div>
	<?php endif; ?>

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
