<?php
/**
 * Template part for displaying a team member's full detail view
 *
 * Used by the team lightbox and the single-team.php template.
 *
 * @package Prospero
 * @since 1.0.0
 *
 * @param array $args {
 *     Optional arguments.
 *     @type string $image_style  Image shape: 'square', 'round', 'portrait'. Default 'square'.
 *     @type string $heading_tag  Heading element: 'h1', 'h2', 'h3'. Default 'h2'.
 * }
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Arguments with defaults
$image_style = isset( $args['image_style'] ) ? $args['image_style'] : 'square';
$heading_tag = isset( $args['heading_tag'] ) ? $args['heading_tag'] : 'h2';

// Validate heading tag
$allowed_heading_tags = array( 'h1', 'h2', 'h3' );
if ( ! in_array( $heading_tag, $allowed_heading_tags, true ) ) {
	$heading_tag = 'h2';
}

// Get team member data
$display_name = get_post_meta( get_the_ID(), '_prospero_team_display_name', true );
$name_output  = $display_name ? $display_name : get_the_title();
$position     = get_post_meta( get_the_ID(), '_prospero_team_position', true );
$email        = get_post_meta( get_the_ID(), '_prospero_team_email', true );
$phone        = get_post_meta( get_the_ID(), '_prospero_team_phone', true );

// Social links
$social_links = array(
	'linkedin'  => array(
		'url'  => get_post_meta( get_the_ID(), '_prospero_team_linkedin', true ),
		'icon' => 'icon-linkedin',
	),
	'youtube'   => array(
		'url'  => get_post_meta( get_the_ID(), '_prospero_team_youtube', true ),
		'icon' => 'icon-youtube',
	),
	'instagram' => array(
		'url'  => get_post_meta( get_the_ID(), '_prospero_team_instagram', true ),
		'icon' => 'icon-instagram',
	),
	'facebook'  => array(
		'url'  => get_post_meta( get_the_ID(), '_prospero_team_facebook', true ),
		'icon' => 'icon-facebook',
	),
	'xing'      => array(
		'url'  => get_post_meta( get_the_ID(), '_prospero_team_xing', true ),
		'icon' => 'icon-xing',
	),
);

$has_social = false;
foreach ( $social_links as $link ) {
	if ( ! empty( $link['url'] ) ) {
		$has_social = true;
		break;
	}
}
?>
<div class="team-lightbox-inner">
	<div class="team-lightbox-top">
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="team-lightbox-image team-image-<?php echo esc_attr( $image_style ); ?>">
				<span class="team-image-container">
					<?php the_post_thumbnail( 'large' ); ?>
				</span>
			</div>
		<?php endif; ?>

		<div class="team-lightbox-details">
			<<?php echo $heading_tag; ?> class="team-lightbox-name"><?php echo esc_html( $name_output ); ?></<?php echo $heading_tag; ?>>

			<?php if ( $position ) : ?>
				<p class="team-lightbox-position"><?php echo esc_html( $position ); ?></p>
			<?php endif; ?>

			<?php if ( $email || $phone ) : ?>
				<div class="team-lightbox-contact">
					<?php if ( $email ) : ?>
						<a href="<?php echo antispambot( 'mailto:' . $email ); ?>" class="team-contact-link">
							<span class="icon-mail" aria-hidden="true"></span>
							<?php echo antispambot( $email ); ?>
						</a>
					<?php endif; ?>
					<?php if ( $phone ) : ?>
						<a href="<?php echo antispambot( 'tel:' . preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="team-contact-link">
							<span class="icon-phone" aria-hidden="true"></span>
							<?php echo antispambot( $phone ); ?>
						</a>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $has_social ) : ?>
				<div class="team-lightbox-social">
					<?php foreach ( $social_links as $network => $data ) : ?>
						<?php if ( ! empty( $data['url'] ) ) : ?>
							<a href="<?php echo esc_url( $data['url'] ); ?>" class="team-social-link team-social-<?php echo esc_attr( $network ); ?>" target="_blank" rel="noopener noreferrer" title="<?php echo esc_attr( ucfirst( $network ) ); ?>">
								<span class="<?php echo esc_attr( $data['icon'] ); ?>" aria-hidden="true"></span>
								<span class="screen-reader-text"><?php echo esc_html( ucfirst( $network ) ); ?></span>
							</a>
						<?php endif; ?>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>

	<?php
	$post_content = get_the_content();
	if ( ! empty( trim( $post_content ) ) ) :
	?>
		<div class="team-lightbox-bio">
			<?php the_content(); ?>
		</div>
	<?php endif; ?>
</div>
