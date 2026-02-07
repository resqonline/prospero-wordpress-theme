<?php
/**
 * Template part for displaying a project card
 *
 * @package Prospero
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional arguments.
 *     @type bool $show_tags    Whether to show project tags. Default true.
 *     @type bool $show_excerpt Whether to show excerpt. Default true.
 *     @type bool $show_link    Whether to show "View Project" link. Default true.
 * }
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get arguments with defaults
$show_tags    = isset( $args['show_tags'] ) ? $args['show_tags'] : true;
$show_excerpt = isset( $args['show_excerpt'] ) ? $args['show_excerpt'] : true;
$show_link    = isset( $args['show_link'] ) ? $args['show_link'] : true;

// Get project tags
$tags = get_the_terms( get_the_ID(), 'project_tag' );
$tag_classes = '';
if ( $tags && ! is_wp_error( $tags ) ) {
	$tag_slugs = array_map( function( $tag ) {
		return $tag->slug;
	}, $tags );
	$tag_classes = ' ' . implode( ' ', $tag_slugs );
}
?>
<article id="project-<?php the_ID(); ?>" <?php post_class( 'project-item' . $tag_classes ); ?>>
	<div class="project-card">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="project-image">
				<a href="<?php the_permalink(); ?>">
					<?php the_post_thumbnail( 'large', array( 'class' => 'project-thumbnail' ) ); ?>
				</a>
			</div>
		<?php endif; ?>
		
		<div class="project-content">
			<h3 class="project-title">
				<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
			</h3>
			
			<?php if ( $show_tags && $tags && ! is_wp_error( $tags ) ) : ?>
				<div class="project-tags">
					<?php foreach ( $tags as $tag ) : ?>
						<span class="project-tag"><?php echo esc_html( $tag->name ); ?></span>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $show_excerpt && has_excerpt() ) : ?>
				<div class="project-excerpt">
					<?php the_excerpt(); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $show_link ) : ?>
				<a href="<?php the_permalink(); ?>" class="project-link button button-secondary">
					<?php esc_html_e( 'View Project', 'prospero-theme' ); ?>
				</a>
			<?php endif; ?>
		</div>
	</div>
</article>
