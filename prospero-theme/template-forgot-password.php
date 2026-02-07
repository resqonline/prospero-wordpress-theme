<?php
/**
 * Template Name: Forgot Password
 * Description: Frontend password reset page
 *
 * @package Prospero
 * @since 1.0.0
 */

// Redirect to home if already logged in
if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url() );
	exit;
}

get_header();
?>

<main id="main-content" class="site-main forgot-password-template">
	<div class="container">
		<div class="forgot-password-wrapper">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Reset Password', 'prospero-theme' ); ?></h1>
				<p class="page-description">
					<?php esc_html_e( 'Enter your email address and we\'ll send you a link to reset your password.', 'prospero-theme' ); ?>
				</p>
			</header>

			<div class="forgot-password-form-container">
				<?php
				// Display success message if password reset email was sent
				if ( isset( $_GET['reset'] ) && 'sent' === sanitize_text_field( wp_unslash( $_GET['reset'] ) ) ) :
					?>
					<div class="message success" role="alert">
						<p><?php esc_html_e( 'Password reset email sent! Please check your inbox.', 'prospero-theme' ); ?></p>
					</div>
					<?php
				endif;
				
				// Check if we're handling a password reset with key
				$reset_key  = isset( $_GET['key'] ) ? sanitize_text_field( wp_unslash( $_GET['key'] ) ) : '';
				$reset_login = isset( $_GET['login'] ) ? sanitize_text_field( wp_unslash( $_GET['login'] ) ) : '';
				
				if ( ! empty( $reset_key ) && ! empty( $reset_login ) ) :
					// Show password reset form
					?>
					<form method="post" action="" class="reset-password-form" id="reset-password-form">
						<?php wp_nonce_field( 'prospero_reset_password', 'prospero_reset_nonce' ); ?>
						<input type="hidden" name="reset_key" value="<?php echo esc_attr( $reset_key ); ?>">
						<input type="hidden" name="reset_login" value="<?php echo esc_attr( $reset_login ); ?>">
						
						<div class="form-group">
							<label for="new_password">
								<?php esc_html_e( 'New Password', 'prospero-theme' ); ?>
								<span class="required">*</span>
							</label>
							<input type="password" name="new_password" id="new_password" class="form-control" required autocomplete="new-password">
							<small class="form-text">
								<?php esc_html_e( 'Must be at least 8 characters long.', 'prospero-theme' ); ?>
							</small>
						</div>
						
						<div class="form-group">
							<label for="confirm_password">
								<?php esc_html_e( 'Confirm New Password', 'prospero-theme' ); ?>
								<span class="required">*</span>
							</label>
							<input type="password" name="confirm_password" id="confirm_password" class="form-control" required autocomplete="new-password">
						</div>
						
						<div class="form-actions">
							<button type="submit" name="reset_password" class="button button-primary">
								<?php esc_html_e( 'Reset Password', 'prospero-theme' ); ?>
							</button>
						</div>
					</form>
					<?php
				else :
					// Show password reset request form
					?>
					<form method="post" action="" class="forgot-password-form" id="forgot-password-form">
						<?php wp_nonce_field( 'prospero_forgot_password', 'prospero_forgot_nonce' ); ?>
						
						<div class="form-group">
							<label for="user_email">
								<?php esc_html_e( 'Email Address', 'prospero-theme' ); ?>
								<span class="required">*</span>
							</label>
							<input type="email" name="user_email" id="user_email" class="form-control" required autocomplete="email" placeholder="<?php esc_attr_e( 'your@email.com', 'prospero-theme' ); ?>">
						</div>
						
						<div class="form-actions">
							<button type="submit" name="forgot_password" class="button button-primary">
								<?php esc_html_e( 'Send Reset Link', 'prospero-theme' ); ?>
							</button>
						</div>
					</form>
					<?php
				endif;
				?>

				<div class="form-footer">
					<p>
						<a href="<?php echo esc_url( wp_login_url() ); ?>" class="login-link">
							<?php esc_html_e( '← Back to Login', 'prospero-theme' ); ?>
						</a>
					</p>
					<p>
						<?php esc_html_e( 'Don\'t have an account?', 'prospero-theme' ); ?>
						<a href="<?php echo esc_url( wp_registration_url() ); ?>" class="register-link">
							<?php esc_html_e( 'Register here', 'prospero-theme' ); ?>
						</a>
					</p>
				</div>
			</div>

			<div class="security-notice">
				<h2><?php esc_html_e( 'Security Notice', 'prospero-theme' ); ?></h2>
				<ul class="notice-list">
					<li><?php esc_html_e( 'The password reset link will expire in 24 hours', 'prospero-theme' ); ?></li>
					<li><?php esc_html_e( 'For security reasons, we do not store your password', 'prospero-theme' ); ?></li>
					<li><?php esc_html_e( 'If you don\'t receive an email, check your spam folder', 'prospero-theme' ); ?></li>
					<li><?php esc_html_e( 'Never share your password reset link with anyone', 'prospero-theme' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
