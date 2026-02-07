<?php
/**
 * Template Name: Register
 * Description: Frontend user registration page
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

<main id="main-content" class="site-main register-template">
	<div class="container">
		<div class="register-wrapper">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Create Account', 'prospero-theme' ); ?></h1>
				<p class="page-description">
					<?php esc_html_e( 'Join our community and get access to exclusive content and features.', 'prospero-theme' ); ?>
				</p>
			</header>

			<div class="register-form-container">
				<form method="post" action="" class="register-form" id="register-form">
					<?php wp_nonce_field( 'prospero_register_user', 'prospero_register_nonce' ); ?>
					
					<div class="form-group">
						<label for="reg_username">
							<?php esc_html_e( 'Username', 'prospero-theme' ); ?>
							<span class="required">*</span>
						</label>
						<input type="text" name="reg_username" id="reg_username" class="form-control" required autocomplete="username">
						<small class="form-text">
							<?php esc_html_e( 'Letters, numbers, and underscores only.', 'prospero-theme' ); ?>
						</small>
					</div>
					
					<div class="form-group">
						<label for="reg_email">
							<?php esc_html_e( 'Email Address', 'prospero-theme' ); ?>
							<span class="required">*</span>
						</label>
						<input type="email" name="reg_email" id="reg_email" class="form-control" required autocomplete="email">
					</div>
					
					<div class="form-group">
						<label for="reg_password">
							<?php esc_html_e( 'Password', 'prospero-theme' ); ?>
							<span class="required">*</span>
						</label>
						<input type="password" name="reg_password" id="reg_password" class="form-control" required autocomplete="new-password">
						<small class="form-text">
							<?php esc_html_e( 'Must be at least 8 characters long.', 'prospero-theme' ); ?>
						</small>
					</div>
					
					<div class="form-group">
						<label for="reg_password_confirm">
							<?php esc_html_e( 'Confirm Password', 'prospero-theme' ); ?>
							<span class="required">*</span>
						</label>
						<input type="password" name="reg_password_confirm" id="reg_password_confirm" class="form-control" required autocomplete="new-password">
					</div>
					
					<div class="form-group">
						<label for="reg_first_name">
							<?php esc_html_e( 'First Name', 'prospero-theme' ); ?>
						</label>
						<input type="text" name="reg_first_name" id="reg_first_name" class="form-control" autocomplete="given-name">
					</div>
					
					<div class="form-group">
						<label for="reg_last_name">
							<?php esc_html_e( 'Last Name', 'prospero-theme' ); ?>
						</label>
						<input type="text" name="reg_last_name" id="reg_last_name" class="form-control" autocomplete="family-name">
					</div>
					
					<div class="form-group checkbox-group">
						<label class="checkbox-label">
							<input type="checkbox" name="reg_terms" id="reg_terms" required>
							<span>
								<?php
								/* translators: %s: Terms and Conditions page URL */
								printf(
									wp_kses_post( __( 'I agree to the <a href="%s" target="_blank">Terms and Conditions</a>', 'prospero-theme' ) ),
									esc_url( get_privacy_policy_url() )
								);
								?>
								<span class="required">*</span>
							</span>
						</label>
					</div>
					
					<div class="form-group checkbox-group">
						<label class="checkbox-label">
							<input type="checkbox" name="reg_newsletter" id="reg_newsletter">
							<span><?php esc_html_e( 'Subscribe to our newsletter', 'prospero-theme' ); ?></span>
						</label>
					</div>
					
					<div class="form-actions">
						<button type="submit" name="register_user" class="button button-primary">
							<?php esc_html_e( 'Create Account', 'prospero-theme' ); ?>
						</button>
					</div>
				</form>

				<div class="register-footer">
					<p>
						<?php esc_html_e( 'Already have an account?', 'prospero-theme' ); ?>
						<a href="<?php echo esc_url( wp_login_url() ); ?>" class="login-link">
							<?php esc_html_e( 'Login here', 'prospero-theme' ); ?>
						</a>
					</p>
				</div>
			</div>

			<div class="register-benefits">
				<h2><?php esc_html_e( 'Member Benefits', 'prospero-theme' ); ?></h2>
				<ul class="benefits-list">
					<li><?php esc_html_e( 'Access to exclusive member content', 'prospero-theme' ); ?></li>
					<li><?php esc_html_e( 'Personalized dashboard', 'prospero-theme' ); ?></li>
					<li><?php esc_html_e( 'Save and track your favorite content', 'prospero-theme' ); ?></li>
					<li><?php esc_html_e( 'Connect with our community', 'prospero-theme' ); ?></li>
				</ul>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
