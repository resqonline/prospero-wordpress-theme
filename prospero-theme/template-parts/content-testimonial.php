<?php
/**
 * Template part for displaying a testimonial item
 *
 * @package Prospero
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional arguments.
 *     @type bool $slider Whether the item is displayed in a slider. Default false.
 * }
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get arguments
$slider = isset( $args['slider'] ) ? $args['slider'] : false;

// Get testimonial data
$display_name = get_post_meta( get_the_ID(), '_prospero_testimonial_display_name', true );
?>
<div class="testimonial-item<?php echo $slider ? ' flickity-cell' : ''; ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="testimonial-image">
			<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'testimonial-thumbnail' ) ); ?>
		</div>
	<?php endif; ?>
	<div class="testimonial-content">
		<blockquote class="testimonial-text">
			<?php the_content(); ?>
		</blockquote>
		<?php if ( $display_name ) : ?>
			<cite class="testimonial-author"><?php echo esc_html( $display_name ); ?></cite>
		<?php endif; ?>
	</div>
</div>
