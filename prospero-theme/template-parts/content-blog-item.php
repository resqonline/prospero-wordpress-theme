<?php
/**
 * Template part: single blog post article (grid or list layout).
 *
 * Used by `template-parts/content-blog-loop.php` (initial page load)
 * AND by `inc/ajax-filters.php::prospero_filter_blog_ajax()` (category
 * filter requests), so both code paths produce byte-identical markup
 * for the same post. Previously the two were duplicated inline and had
 * drifted on excerpt handling and sticky eligibility.
 *
 * @package Prospero
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional arguments.
 *     @type string $layout       'grid' | 'list'. Default 'grid'.
 *     @type bool   $show_excerpt Whether to render the excerpt block in
 *                                grid layout. List layout always shows it.
 *                                Default true.
 *     @type bool   $allow_sticky Whether this render context may flag
 *                                sticky posts with the sticky label /
 *                                class. Disabled on paged views and on
 *                                category-filtered ajax responses so a
 *                                sticky post isn't double-surfaced.
 *                                Default true.
 * }
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$layout       = isset( $args['layout'] ) && 'list' === $args['layout'] ? 'list' : 'grid';
$show_excerpt = isset( $args['show_excerpt'] ) ? (bool) $args['show_excerpt'] : true;
$allow_sticky = isset( $args['allow_sticky'] ) ? (bool) $args['allow_sticky'] : true;

$is_sticky = $allow_sticky && is_sticky();

$item_class  = 'grid' === $layout ? 'blog-post-item' : 'blog-list-item';
$item_class .= $is_sticky ? ' sticky-post' : '';
if ( 'list' === $layout ) {
	$item_class .= has_post_thumbnail() ? ' has-thumbnail' : '';
}

$thumb_size = 'list' === $layout
	? 'medium'
	: ( $is_sticky ? 'large' : 'medium_large' );
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( $item_class ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php the_post_thumbnail( $thumb_size ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="post-content">
		<?php if ( $is_sticky ) : ?>
			<span class="sticky-label"><?php esc_html_e( 'Featured', 'prospero-theme' ); ?></span>
		<?php endif; ?>

		<header class="entry-header">
			<h2 class="entry-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h2>
			<div class="entry-meta">
				<span class="posted-on">
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<?php echo esc_html( get_the_date() ); ?>
					</time>
				</span>
				<?php if ( 'list' === $layout ) : ?>
					<span class="byline">
						<?php esc_html_e( 'by', 'prospero-theme' ); ?>
						<span class="author"><?php the_author(); ?></span>
					</span>
					<?php if ( function_exists( 'prospero_get_reading_time' ) ) : ?>
						<span class="reading-time">
							<?php echo esc_html( prospero_get_reading_time( get_the_ID() ) ); ?>
						</span>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</header>

		<?php if ( 'list' === $layout || $show_excerpt || $is_sticky ) : ?>
			<div class="entry-summary">
				<?php the_excerpt(); ?>
			</div>
		<?php endif; ?>

		<footer class="entry-footer">
			<a href="<?php the_permalink(); ?>" class="read-more button button-secondary">
				<?php esc_html_e( 'Read More', 'prospero-theme' ); ?>
			</a>
		</footer>
	</div>
</article>
