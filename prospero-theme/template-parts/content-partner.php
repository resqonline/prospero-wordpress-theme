<?php
/**
 * Template part for displaying a partner item
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

// Get partner data
$partner_url = get_post_meta( get_the_ID(), '_prospero_partner_url', true );
?>
<div class="partner-item<?php echo $slider ? ' flickity-cell' : ''; ?>">
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="partner-logo">
			<?php if ( $partner_url ) : ?>
				<a href="<?php echo esc_url( $partner_url ); ?>" title="<?php echo esc_attr( get_the_title() ); ?>" target="_blank" rel="noopener">
					<?php the_post_thumbnail( 'medium', array( 'class' => 'partner-thumbnail' ) ); ?>
				</a>
			<?php else : ?>
				<?php the_post_thumbnail( 'medium', array( 'class' => 'partner-thumbnail' ) ); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
