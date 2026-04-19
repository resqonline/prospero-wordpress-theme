<?php
/**
 * Top bar template part
 *
 * Renders an optional contact bar above the site header with a phone
 * number and / or email address, each with an icon and an optional
 * short prefix label (e.g. "Call us:"). Controlled entirely by the
 * Customizer "Top Bar" section. Bar is not rendered if the feature
 * is disabled or both contact fields are empty.
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Feature flag
if ( ! get_theme_mod( 'prospero_top_bar_enable', false ) ) {
	return;
}

$phone         = trim( (string) get_theme_mod( 'prospero_top_bar_phone', '' ) );
$phone_prefix  = trim( (string) get_theme_mod( 'prospero_top_bar_phone_prefix', '' ) );
$email         = sanitize_email( (string) get_theme_mod( 'prospero_top_bar_email', '' ) );
$email_prefix  = trim( (string) get_theme_mod( 'prospero_top_bar_email_prefix', '' ) );

// Nothing to render if both are empty.
if ( '' === $phone && '' === $email ) {
	return;
}

// Strip formatting (spaces, hyphens, parens, dots) from the phone for
// the tel: URI but keep the original for display.
$phone_href = '' !== $phone ? preg_replace( '/[^0-9+]/', '', $phone ) : '';
?>
<div class="site-top-bar" role="complementary" aria-label="<?php esc_attr_e( 'Contact information', 'prospero-theme' ); ?>">
	<div class="container top-bar-inner">

		<?php if ( '' !== $phone && '' !== $phone_href ) :
			$phone_aria = '' !== $phone_prefix
				? sprintf( '%1$s %2$s', $phone_prefix, $phone )
				: sprintf(
					/* translators: %s: phone number */
					esc_html__( 'Call %s', 'prospero-theme' ),
					$phone
				);
			?>
			<a class="top-bar-link top-bar-phone"
				href="<?php echo esc_attr( antispambot( 'tel:' . $phone_href ) ); ?>"
				aria-label="<?php echo esc_attr( $phone_aria ); ?>">
				<span class="icon-phone top-bar-icon" aria-hidden="true"></span>
				<span class="top-bar-text">
					<?php if ( '' !== $phone_prefix ) : ?>
						<span class="top-bar-prefix"><?php echo esc_html( $phone_prefix ); ?></span>
					<?php endif; ?>
					<span class="top-bar-value"><?php echo esc_html( $phone ); ?></span>
				</span>
			</a>
		<?php endif; ?>

		<?php if ( '' !== $email ) :
			$email_aria = '' !== $email_prefix
				? sprintf( '%1$s %2$s', $email_prefix, $email )
				: sprintf(
					/* translators: %s: email address */
					esc_html__( 'Email %s', 'prospero-theme' ),
					$email
				);
			?>
			<a class="top-bar-link top-bar-email"
				href="<?php echo esc_attr( antispambot( 'mailto:' . $email ) ); ?>"
				aria-label="<?php echo esc_attr( $email_aria ); ?>">
				<span class="icon-mail top-bar-icon" aria-hidden="true"></span>
				<span class="top-bar-text">
					<?php if ( '' !== $email_prefix ) : ?>
						<span class="top-bar-prefix"><?php echo esc_html( $email_prefix ); ?></span>
					<?php endif; ?>
					<span class="top-bar-value"><?php echo esc_html( antispambot( $email ) ); ?></span>
				</span>
			</a>
		<?php endif; ?>

	</div>
</div>
