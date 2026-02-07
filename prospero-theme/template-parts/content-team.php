<?php
/**
 * Template part for displaying a team member card
 *
 * @package Prospero
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional arguments.
 *     @type bool   $slider       Whether the item is displayed in a slider. Default false.
 *     @type bool   $lightbox     Whether to use lightbox instead of links. Default false.
 *     @type string $layout       Layout style: 'columns' or 'list'. Default 'columns'.
 *     @type bool   $show_contact Whether to show contact info. Default true.
 *     @type bool   $show_social  Whether to show social links. Default true.
 *     @type bool   $show_excerpt Whether to show excerpt. Default false (true for list layout).
 *     @type bool   $show_link    Whether to show "View Profile" link. Default true.
 * }
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get arguments with defaults
$slider       = isset( $args['slider'] ) ? $args['slider'] : false;
$lightbox     = isset( $args['lightbox'] ) ? $args['lightbox'] : false;
$layout       = isset( $args['layout'] ) ? $args['layout'] : 'columns';
$show_contact = isset( $args['show_contact'] ) ? $args['show_contact'] : true;
$show_social  = isset( $args['show_social'] ) ? $args['show_social'] : true;
$show_excerpt = isset( $args['show_excerpt'] ) ? $args['show_excerpt'] : ( $layout === 'list' );
$show_link    = isset( $args['show_link'] ) ? $args['show_link'] : true;

// Get team member data
$display_name = get_post_meta( get_the_ID(), '_prospero_team_display_name', true );
$name_output  = $display_name ? $display_name : get_the_title();
$position     = get_post_meta( get_the_ID(), '_prospero_team_position', true );
$email        = get_post_meta( get_the_ID(), '_prospero_team_email', true );
$phone        = get_post_meta( get_the_ID(), '_prospero_team_phone', true );

// Social links
$social_links = array(
	'linkedin'  => get_post_meta( get_the_ID(), '_prospero_team_linkedin', true ),
	'youtube'   => get_post_meta( get_the_ID(), '_prospero_team_youtube', true ),
	'instagram' => get_post_meta( get_the_ID(), '_prospero_team_instagram', true ),
	'facebook'  => get_post_meta( get_the_ID(), '_prospero_team_facebook', true ),
	'xing'      => get_post_meta( get_the_ID(), '_prospero_team_xing', true ),
);

// Check if there are any social links
$has_social = false;
foreach ( $social_links as $link ) {
	if ( ! empty( $link ) ) {
		$has_social = true;
		break;
	}
}
?>
<div class="team-member<?php echo $slider ? ' flickity-cell' : ''; ?>"<?php echo $lightbox ? ' data-team-id="' . get_the_ID() . '"' : ''; ?>>
	<div class="team-member-card">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="team-member-image">
				<?php if ( $lightbox ) : ?>
					<button type="button" class="team-lightbox-trigger" aria-label="<?php echo esc_attr( sprintf( __( 'View details for %s', 'prospero-theme' ), $name_output ) ); ?>">
						<?php the_post_thumbnail( 'medium', array( 'class' => 'team-thumbnail' ) ); ?>
					</button>
				<?php else : ?>
					<?php the_post_thumbnail( 'medium' ); ?>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
		<div class="team-member-content">
			<h3 class="team-member-name">
				<?php if ( $lightbox ) : ?>
					<button type="button" class="team-lightbox-trigger"><?php echo esc_html( $name_output ); ?></button>
				<?php else : ?>
					<?php echo esc_html( $name_output ); ?>
				<?php endif; ?>
			</h3>
			
			<?php if ( $position ) : ?>
				<p class="team-member-position"><?php echo esc_html( $position ); ?></p>
			<?php endif; ?>
			
			<?php if ( $show_excerpt && has_excerpt() ) : ?>
				<div class="team-member-excerpt">
					<?php the_excerpt(); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $show_contact && ( $email || $phone ) ) : ?>
				<div class="team-member-contact">
					<?php if ( $email ) : ?>
						<a href="mailto:<?php echo esc_attr( $email ); ?>" class="team-contact-link email" aria-label="<?php esc_attr_e( 'Email', 'prospero-theme' ); ?>">
							<span class="screen-reader-text"><?php esc_html_e( 'Email:', 'prospero-theme' ); ?></span>
							<?php echo esc_html( $email ); ?>
						</a>
					<?php endif; ?>
					
					<?php if ( $phone ) : ?>
						<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="team-contact-link phone" aria-label="<?php esc_attr_e( 'Phone', 'prospero-theme' ); ?>">
							<span class="screen-reader-text"><?php esc_html_e( 'Phone:', 'prospero-theme' ); ?></span>
							<?php echo esc_html( $phone ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $show_social && $has_social ) : ?>
				<div class="team-member-social">
					<?php foreach ( $social_links as $network => $url ) : ?>
						<?php if ( ! empty( $url ) ) : ?>
							<a href="<?php echo esc_url( $url ); ?>" class="social-link <?php echo esc_attr( $network ); ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( ucfirst( $network ) ); ?>">
								<span class="screen-reader-text"><?php echo esc_html( ucfirst( $network ) ); ?></span>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $show_link && ! $lightbox ) : ?>
				<a href="<?php the_permalink(); ?>" class="team-member-link button button-secondary">
					<?php esc_html_e( 'View Profile', 'prospero-theme' ); ?>
				</a>
			<?php endif; ?>
		</div>

		<?php if ( $lightbox ) : ?>
			<div class="team-lightbox-content" hidden>
				<div class="team-lightbox-inner">
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="team-lightbox-image">
							<?php the_post_thumbnail( 'large', array( 'class' => 'team-thumbnail-large' ) ); ?>
						</div>
					<?php endif; ?>
					<div class="team-lightbox-details">
						<h2 class="team-lightbox-name"><?php echo esc_html( $name_output ); ?></h2>
						<?php if ( $position ) : ?>
							<p class="team-lightbox-position"><?php echo esc_html( $position ); ?></p>
						<?php endif; ?>
						<div class="team-lightbox-bio">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>
	</div>
</div>
