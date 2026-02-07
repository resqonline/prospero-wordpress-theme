<?php
/**
 * Template Name: Login
 * Description: Frontend login page
 *
 * @package Prospero
 * @since 1.0.0
 */

// Redirect to My Account if already logged in
if ( is_user_logged_in() ) {
	wp_safe_redirect( home_url( '/my-account' ) );
	exit;
}

get_header();
?>

<main id="main-content" class="site-main login-template">
	<div class="container">
		<div class="login-wrapper">
			<header class="page-header">
				<h1 class="page-title"><?php esc_html_e( 'Login', 'prospero-theme' ); ?></h1>
			</header>

			<div class="login-form-container">
				<?php
				// Display success message if coming from password reset
				if ( isset( $_GET['password'] ) && 'reset' === sanitize_text_field( wp_unslash( $_GET['password'] ) ) ) :
					?>
					<div class="message success" role="alert">
						<p><?php esc_html_e( 'Password reset successfully! You can now log in.', 'prospero-theme' ); ?></p>
					</div>
					<?php
				endif;
				?>

				<form method="post" action="<?php echo esc_url( site_url( 'wp-login.php', 'login_post' ) ); ?>" class="login-form" id="loginform">
					<div class="form-group">
						<label for="user_login">
							<?php esc_html_e( 'Username or Email', 'prospero-theme' ); ?>
							<span class="required">*</span>
						</label>
						<input type="text" name="log" id="user_login" class="form-control" required autocomplete="username">
					</div>

					<div class="form-group">
						<label for="user_pass">
							<?php esc_html_e( 'Password', 'prospero-theme' ); ?>
							<span class="required">*</span>
						</label>
						<input type="password" name="pwd" id="user_pass" class="form-control" required autocomplete="current-password">
					</div>

					<div class="form-group">
						<label class="checkbox-label">
							<input type="checkbox" name="rememberme" value="forever">
							<?php esc_html_e( 'Remember Me', 'prospero-theme' ); ?>
						</label>
					</div>

					<input type="hidden" name="redirect_to" value="<?php echo esc_url( home_url( '/my-account' ) ); ?>">

					<div class="form-actions">
						<button type="submit" name="wp-submit" class="button button-primary">
							<?php esc_html_e( 'Log In', 'prospero-theme' ); ?>
						</button>
					</div>
				</form>

				<div class="form-footer">
					<p>
						<a href="<?php echo esc_url( home_url( '/forgot-password' ) ); ?>" class="forgot-password-link">
							<?php esc_html_e( 'Forgot your password?', 'prospero-theme' ); ?>
						</a>
					</p>
					<p>
						<?php esc_html_e( 'Don\'t have an account?', 'prospero-theme' ); ?>
						<a href="<?php echo esc_url( home_url( '/register' ) ); ?>" class="register-link">
							<?php esc_html_e( 'Register here', 'prospero-theme' ); ?>
						</a>
					</p>
				</div>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
