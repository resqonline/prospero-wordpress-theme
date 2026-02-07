<?php
/**
 * Template Name: My Account
 * Description: Frontend user account management page
 *
 * @package Prospero
 * @since 1.0.0
 */

// Redirect to login if not logged in
if ( ! is_user_logged_in() ) {
	$login_url = wp_login_url( get_permalink() );
	wp_safe_redirect( $login_url );
	exit;
}

get_header();

$current_user = wp_get_current_user();
?>

<main id="main-content" class="site-main my-account-template">
	<div class="container">
		<header class="page-header">
			<h1 class="page-title">
				<?php
				/* translators: %s: User display name */
				printf( esc_html__( 'Welcome, %s', 'prospero-theme' ), esc_html( $current_user->display_name ) );
				?>
			</h1>
		</header>

		<div class="account-dashboard">
			<?php
			// Display success messages
			if ( isset( $_GET['profile'] ) && 'updated' === sanitize_text_field( wp_unslash( $_GET['profile'] ) ) ) :
				?>
				<div class="message success" role="alert">
					<p><?php esc_html_e( 'Profile updated successfully!', 'prospero-theme' ); ?></p>
				</div>
				<?php
			endif;
			
			if ( isset( $_GET['password'] ) && 'changed' === sanitize_text_field( wp_unslash( $_GET['password'] ) ) ) :
				?>
				<div class="message success" role="alert">
					<p><?php esc_html_e( 'Password changed successfully!', 'prospero-theme' ); ?></p>
				</div>
				<?php
			endif;

			if ( isset( $_GET['avatar'] ) && 'updated' === sanitize_text_field( wp_unslash( $_GET['avatar'] ) ) ) :
				?>
				<div class="message success" role="alert">
					<p><?php esc_html_e( 'Avatar updated successfully!', 'prospero-theme' ); ?></p>
				</div>
				<?php
			endif;

			if ( isset( $_GET['avatar'] ) && 'removed' === sanitize_text_field( wp_unslash( $_GET['avatar'] ) ) ) :
				?>
				<div class="message success" role="alert">
					<p><?php esc_html_e( 'Avatar removed successfully!', 'prospero-theme' ); ?></p>
				</div>
				<?php
			endif;
			?>

			<div class="account-sidebar">
				<nav class="account-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Account Navigation', 'prospero-theme' ); ?>">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'logged-in-menu',
						'menu_id'        => 'logged-in-menu',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => 'prospero_logged_in_menu_fallback',
					) );
					?>
				</nav>
			</div>

			<div class="account-content">
				<!-- Account Overview Section -->
				<section id="account-overview" class="account-section active">
					<h2><?php esc_html_e( 'Account Overview', 'prospero-theme' ); ?></h2>
					
					<div class="account-info-grid">
						<div class="info-item">
							<strong><?php esc_html_e( 'Username:', 'prospero-theme' ); ?></strong>
							<span><?php echo esc_html( $current_user->user_login ); ?></span>
						</div>
						<div class="info-item">
							<strong><?php esc_html_e( 'Email:', 'prospero-theme' ); ?></strong>
							<span><?php echo esc_html( $current_user->user_email ); ?></span>
						</div>
						<div class="info-item">
							<strong><?php esc_html_e( 'Display Name:', 'prospero-theme' ); ?></strong>
							<span><?php echo esc_html( $current_user->display_name ); ?></span>
						</div>
						<div class="info-item">
							<strong><?php esc_html_e( 'Member Since:', 'prospero-theme' ); ?></strong>
							<span><?php echo esc_html( date_i18n( get_option( 'date_format' ), strtotime( $current_user->user_registered ) ) ); ?></span>
						</div>
					</div>
				</section>

				<!-- Edit Profile Section -->
				<section id="edit-profile" class="account-section">
					<h2><?php esc_html_e( 'Edit Profile', 'prospero-theme' ); ?></h2>

					<!-- Avatar Upload -->
					<div class="avatar-section">
						<h3><?php esc_html_e( 'Profile Picture', 'prospero-theme' ); ?></h3>
						<div class="avatar-preview">
							<?php echo get_avatar( $current_user->ID, 150 ); ?>
						</div>
						<form method="post" action="" enctype="multipart/form-data" class="avatar-form">
							<?php wp_nonce_field( 'prospero_upload_avatar', 'prospero_avatar_nonce' ); ?>
							<div class="form-group">
								<label for="avatar_file"><?php esc_html_e( 'Upload new avatar', 'prospero-theme' ); ?></label>
								<input type="file" name="avatar_file" id="avatar_file" accept="image/jpeg,image/png,image/gif,image/webp" class="form-control">
								<small class="form-text"><?php esc_html_e( 'JPG, PNG, GIF or WebP. Max 2MB.', 'prospero-theme' ); ?></small>
							</div>
							<div class="avatar-actions">
								<button type="submit" name="upload_avatar" class="button button-primary">
									<?php esc_html_e( 'Upload Avatar', 'prospero-theme' ); ?>
								</button>
								<?php if ( get_user_meta( $current_user->ID, '_prospero_local_avatar', true ) ) : ?>
									<button type="submit" name="remove_avatar" class="button button-secondary">
										<?php esc_html_e( 'Remove Avatar', 'prospero-theme' ); ?>
									</button>
								<?php endif; ?>
							</div>
						</form>
					</div>

					<hr class="section-divider">

					<!-- Profile Form -->
					<form method="post" action="" class="account-form" id="profile-form">
						<?php wp_nonce_field( 'prospero_update_profile', 'prospero_profile_nonce' ); ?>

						<div class="form-group">
							<label for="first_name"><?php esc_html_e( 'First Name', 'prospero-theme' ); ?></label>
							<input type="text" name="first_name" id="first_name" value="<?php echo esc_attr( $current_user->first_name ); ?>" class="form-control">
						</div>
						
						<div class="form-group">
							<label for="last_name"><?php esc_html_e( 'Last Name', 'prospero-theme' ); ?></label>
							<input type="text" name="last_name" id="last_name" value="<?php echo esc_attr( $current_user->last_name ); ?>" class="form-control">
						</div>
						
						<div class="form-group">
							<label for="display_name"><?php esc_html_e( 'Display Name', 'prospero-theme' ); ?></label>
							<input type="text" name="display_name" id="display_name" value="<?php echo esc_attr( $current_user->display_name ); ?>" class="form-control" required>
						</div>
						
						<div class="form-group">
							<label for="user_email"><?php esc_html_e( 'Email Address', 'prospero-theme' ); ?></label>
							<input type="email" name="user_email" id="user_email" value="<?php echo esc_attr( $current_user->user_email ); ?>" class="form-control" required>
						</div>
						
						<div class="form-group">
							<label for="description"><?php esc_html_e( 'Biographical Info', 'prospero-theme' ); ?></label>
							<textarea name="description" id="description" rows="5" class="form-control"><?php echo esc_textarea( $current_user->description ); ?></textarea>
						</div>
						
						<button type="submit" name="update_profile" class="button button-primary">
							<?php esc_html_e( 'Update Profile', 'prospero-theme' ); ?>
						</button>
					</form>
				</section>

				<!-- Change Password Section -->
				<section id="change-password" class="account-section">
					<h2><?php esc_html_e( 'Change Password', 'prospero-theme' ); ?></h2>
					
					<form method="post" action="" class="account-form" id="password-form">
						<?php wp_nonce_field( 'prospero_change_password', 'prospero_password_nonce' ); ?>
						
						<div class="form-group">
							<label for="current_password"><?php esc_html_e( 'Current Password', 'prospero-theme' ); ?></label>
							<input type="password" name="current_password" id="current_password" class="form-control" required>
						</div>
						
						<div class="form-group">
							<label for="new_password"><?php esc_html_e( 'New Password', 'prospero-theme' ); ?></label>
							<input type="password" name="new_password" id="new_password" class="form-control" required>
						</div>
						
						<div class="form-group">
							<label for="confirm_password"><?php esc_html_e( 'Confirm New Password', 'prospero-theme' ); ?></label>
							<input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
						</div>
						
						<button type="submit" name="change_password" class="button button-primary">
							<?php esc_html_e( 'Change Password', 'prospero-theme' ); ?>
						</button>
					</form>
				</section>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
