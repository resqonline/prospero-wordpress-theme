<?php
/**
 * Template part for displaying a testimonial item
 *
 * @package Prospero
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional arguments.
 *     @type bool $slider       Whether the item is displayed in a slider. Default false.
 *     @type bool $show_ratings Whether to display star ratings. Default false.
 * }
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get arguments
$slider       = isset( $args['slider'] ) ? $args['slider'] : false;
$show_ratings = isset( $args['show_ratings'] ) ? $args['show_ratings'] : false;

// Get testimonial data
$display_name = get_post_meta( get_the_ID(), '_prospero_testimonial_display_name', true );
$ratings      = get_post_meta( get_the_ID(), '_prospero_testimonial_ratings', true );
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
		<?php if ( $show_ratings && ! empty( $ratings ) && is_array( $ratings ) ) : ?>
			<div class="testimonial-ratings">
				<?php foreach ( $ratings as $rating ) :
					$label = isset( $rating['label'] ) ? $rating['label'] : '';
					$value = isset( $rating['value'] ) ? absint( $rating['value'] ) : 0;
					if ( $value < 1 || $value > 5 ) {
						continue;
					}
					?>
					<div class="testimonial-rating-item">
						<?php if ( $label ) : ?>
							<span class="rating-label"><?php echo esc_html( $label ); ?></span>
						<?php endif; ?>
						<span class="rating-stars" aria-label="<?php echo esc_attr( sprintf( /* translators: %d: rating value out of 5 */ __( '%d out of 5 stars', 'prospero-theme' ), $value ) ); ?>">
							<?php
							// Star glyphs rendered via CSS `mask-image` on the same
							// Lucide / Prospero star polygon used by the icon font
							// (see .rating-star rules in blocks.css). Both filled and
							// empty states share the exact same SVG source, so their
							// on-screen sizes are pixel-identical - only the fill vs
							// stroke rendering differs.
							for ( $i = 1; $i <= 5; $i++ ) :
								$is_filled = ( $i <= $value );
								?>
								<span class="rating-star <?php echo $is_filled ? 'is-filled' : 'is-empty'; ?>" aria-hidden="true"></span>
							<?php endfor; ?>
						</span>
					</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</div>
