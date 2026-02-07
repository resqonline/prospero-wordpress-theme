<?php
/**
 * Frontend Login System
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Redirect WP login to frontend login when enabled
 */
function prospero_redirect_wp_login() {
	if ( ! get_theme_mod( 'prospero_enable_frontend_login', false ) ) {
		return;
	}

	// Don't redirect if user is trying to log out
	if ( isset( $_GET['action'] ) && 'logout' === $_GET['action'] ) {
		return;
	}

	// Don't redirect if user is trying to reset password
	if ( isset( $_GET['action'] ) && in_array( $_GET['action'], array( 'lostpassword', 'rp', 'resetpass' ), true ) ) {
		return;
	}

	// Don't redirect admin users
	if ( is_user_logged_in() && current_user_can( 'unfiltered_html' ) ) {
		return;
	}

	// Don't redirect POST requests (login form submission)
	if ( 'POST' === $_SERVER['REQUEST_METHOD'] ) {
		return;
	}

	// Redirect to frontend login
	wp_safe_redirect( home_url( '/login' ) );
	exit;
}
add_action( 'login_init', 'prospero_redirect_wp_login' );

/**
 * Add login/logout/account to menu
 */
function prospero_add_login_logout_menu( $items, $args ) {
	if ( ! get_theme_mod( 'prospero_enable_frontend_login', false ) ) {
		return $items;
	}

	if ( $args->theme_location === 'main-menu' ) {
		if ( is_user_logged_in() ) {
			$current_user = wp_get_current_user();
			$items .= '<li class="menu-item menu-item-has-children menu-item-user-account">';
			$items .= '<a href="' . esc_url( home_url( '/my-account' ) ) . '"><span class="icon-circle-user" aria-hidden="true"></span> ' . esc_html( $current_user->display_name ) . '</a>';
			$items .= '<ul class="sub-menu">';
			$items .= '<li class="menu-item menu-item-account"><a href="' . esc_url( home_url( '/my-account' ) ) . '"><span class="icon-user" aria-hidden="true"></span> ' . esc_html__( 'My Account', 'prospero-theme' ) . '</a></li>';
			$items .= '<li class="menu-item menu-item-logout"><a href="' . esc_url( wp_logout_url( home_url() ) ) . '"><span class="icon-log-out" aria-hidden="true"></span> ' . esc_html__( 'Logout', 'prospero-theme' ) . '</a></li>';
			$items .= '</ul>';
			$items .= '</li>';
		} else {
			$items .= '<li class="menu-item menu-item-login"><a href="' . esc_url( home_url( '/login' ) ) . '"><span class="icon-user" aria-hidden="true"></span> ' . esc_html__( 'Login', 'prospero-theme' ) . '</a></li>';
		}
	}

	return $items;
}
add_filter( 'wp_nav_menu_items', 'prospero_add_login_logout_menu', 10, 2 );

/**
 * Disable Gravatar completely for GDPR compliance
 * Uses local avatars stored in user meta instead
 */
function prospero_disable_gravatar( $avatar_defaults ) {
	// Remove all gravatar options, keep only local/mystery avatar
	return array(
		'mystery' => __( 'Mystery Person', 'prospero-theme' ),
		'blank'   => __( 'Blank', 'prospero-theme' ),
	);
}
add_filter( 'avatar_defaults', 'prospero_disable_gravatar' );

/**
 * Filter avatar to use local avatar if available
 */
function prospero_get_local_avatar( $avatar, $id_or_email, $size, $default, $alt, $args ) {
	// Get user ID
	$user_id = 0;

	if ( is_numeric( $id_or_email ) ) {
		$user_id = absint( $id_or_email );
	} elseif ( is_string( $id_or_email ) ) {
		$user = get_user_by( 'email', $id_or_email );
		if ( $user ) {
			$user_id = $user->ID;
		}
	} elseif ( $id_or_email instanceof WP_User ) {
		$user_id = $id_or_email->ID;
	} elseif ( $id_or_email instanceof WP_Post ) {
		$user_id = $id_or_email->post_author;
	} elseif ( $id_or_email instanceof WP_Comment ) {
		if ( $id_or_email->user_id ) {
			$user_id = $id_or_email->user_id;
		}
	}

	if ( ! $user_id ) {
		return $avatar;
	}

	// Check for local avatar
	$local_avatar_id = get_user_meta( $user_id, '_prospero_local_avatar', true );

	if ( $local_avatar_id ) {
		$avatar_url = wp_get_attachment_image_url( $local_avatar_id, array( $size, $size ) );

		if ( $avatar_url ) {
			$class = array( 'avatar', 'avatar-' . $size, 'photo', 'local-avatar' );

			if ( ! empty( $args['class'] ) ) {
				if ( is_array( $args['class'] ) ) {
					$class = array_merge( $class, $args['class'] );
				} else {
					$class[] = $args['class'];
				}
			}

			$avatar = sprintf(
				'<img alt="%s" src="%s" class="%s" height="%d" width="%d" loading="lazy" decoding="async" />',
				esc_attr( $alt ),
				esc_url( $avatar_url ),
				esc_attr( implode( ' ', $class ) ),
				esc_attr( $size ),
				esc_attr( $size )
			);
		}
	}

	return $avatar;
}
add_filter( 'get_avatar', 'prospero_get_local_avatar', 10, 6 );

/**
 * Pre-filter to disable Gravatar requests entirely
 */
function prospero_pre_get_avatar_data( $args, $id_or_email ) {
	// Force local processing, no external requests
	$args['found_avatar'] = false;

	// Get user ID
	$user_id = 0;

	if ( is_numeric( $id_or_email ) ) {
		$user_id = absint( $id_or_email );
	} elseif ( is_string( $id_or_email ) ) {
		$user = get_user_by( 'email', $id_or_email );
		if ( $user ) {
			$user_id = $user->ID;
		}
	} elseif ( $id_or_email instanceof WP_User ) {
		$user_id = $id_or_email->ID;
	}

	if ( $user_id ) {
		$local_avatar_id = get_user_meta( $user_id, '_prospero_local_avatar', true );

		if ( $local_avatar_id ) {
			$avatar_url = wp_get_attachment_image_url( $local_avatar_id, array( $args['size'], $args['size'] ) );

			if ( $avatar_url ) {
				$args['url']          = $avatar_url;
				$args['found_avatar'] = true;
			}
		}
	}

	// If no local avatar, use default (no Gravatar request)
	if ( ! $args['found_avatar'] ) {
		$args['url'] = includes_url( 'images/blank.gif' );
	}

	return $args;
}
add_filter( 'pre_get_avatar_data', 'prospero_pre_get_avatar_data', 10, 2 );

/**
 * Handle avatar upload
 */
function prospero_handle_avatar_upload() {
	if ( ! isset( $_POST['upload_avatar'] ) ) {
		return;
	}

	if ( ! is_user_logged_in() ) {
		wp_die( esc_html__( 'You must be logged in to upload an avatar.', 'prospero-theme' ) );
	}

	// Verify nonce
	if ( ! isset( $_POST['prospero_avatar_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prospero_avatar_nonce'] ) ), 'prospero_upload_avatar' ) ) {
		wp_die( esc_html__( 'Security verification failed.', 'prospero-theme' ) );
	}

	$user_id = get_current_user_id();

	// Check if file was uploaded
	if ( empty( $_FILES['avatar_file'] ) || empty( $_FILES['avatar_file']['name'] ) ) {
		wp_die( esc_html__( 'Please select an image to upload.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}

	// Validate file type
	$allowed_types = array( 'image/jpeg', 'image/png', 'image/gif', 'image/webp' );
	$file_type     = wp_check_filetype( $_FILES['avatar_file']['name'] );

	if ( ! in_array( $_FILES['avatar_file']['type'], $allowed_types, true ) ) {
		wp_die( esc_html__( 'Invalid file type. Please upload a JPG, PNG, GIF, or WebP image.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}

	// Check file size (max 2MB)
	if ( $_FILES['avatar_file']['size'] > 2 * 1024 * 1024 ) {
		wp_die( esc_html__( 'File size too large. Maximum size is 2MB.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}

	// Include required files
	if ( ! function_exists( 'wp_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';
	}
	if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
		require_once ABSPATH . 'wp-admin/includes/image.php';
	}
	if ( ! function_exists( 'media_handle_upload' ) ) {
		require_once ABSPATH . 'wp-admin/includes/media.php';
	}

	// Delete old avatar if exists
	$old_avatar_id = get_user_meta( $user_id, '_prospero_local_avatar', true );
	if ( $old_avatar_id ) {
		wp_delete_attachment( $old_avatar_id, true );
	}

	// Upload the file
	$attachment_id = media_handle_upload( 'avatar_file', 0 );

	if ( is_wp_error( $attachment_id ) ) {
		wp_die( esc_html( $attachment_id->get_error_message() ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}

	// Save avatar ID to user meta
	update_user_meta( $user_id, '_prospero_local_avatar', $attachment_id );

	// Redirect with success
	wp_safe_redirect( add_query_arg( 'avatar', 'updated', home_url( '/my-account' ) ) );
	exit;
}
add_action( 'init', 'prospero_handle_avatar_upload' );

/**
 * Handle avatar removal
 */
function prospero_handle_avatar_removal() {
	if ( ! isset( $_POST['remove_avatar'] ) ) {
		return;
	}

	if ( ! is_user_logged_in() ) {
		wp_die( esc_html__( 'You must be logged in to remove your avatar.', 'prospero-theme' ) );
	}

	// Verify nonce
	if ( ! isset( $_POST['prospero_avatar_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prospero_avatar_nonce'] ) ), 'prospero_upload_avatar' ) ) {
		wp_die( esc_html__( 'Security verification failed.', 'prospero-theme' ) );
	}

	$user_id = get_current_user_id();

	// Delete avatar attachment
	$avatar_id = get_user_meta( $user_id, '_prospero_local_avatar', true );
	if ( $avatar_id ) {
		wp_delete_attachment( $avatar_id, true );
		delete_user_meta( $user_id, '_prospero_local_avatar' );
	}

	// Redirect with success
	wp_safe_redirect( add_query_arg( 'avatar', 'removed', home_url( '/my-account' ) ) );
	exit;
}
add_action( 'init', 'prospero_handle_avatar_removal' );

/**
 * Redirect users without editor capabilities to frontend after login
 */
function prospero_login_redirect( $redirect_to, $request, $user ) {
	if ( ! get_theme_mod( 'prospero_enable_frontend_login', false ) ) {
		return $redirect_to;
	}
	
	if ( isset( $user->ID ) ) {
		if ( user_can( $user->ID, 'unfiltered_html' ) ) {
			return admin_url();
		} else {
			return home_url( '/my-account' );
		}
	}
	
	return $redirect_to;
}
add_filter( 'login_redirect', 'prospero_login_redirect', 10, 3 );

/**
 * Hide admin bar for users without editor capabilities
 */
function prospero_hide_admin_bar() {
	if ( ! current_user_can( 'unfiltered_html' ) ) {
		show_admin_bar( false );
	}
}
add_action( 'after_setup_theme', 'prospero_hide_admin_bar' );

/**
 * Customize login logo
 */
function prospero_custom_login_logo() {
	if ( has_custom_logo() ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo_url = wp_get_attachment_image_url( $custom_logo_id, 'full' );
	} else {
		$logo_url = get_template_directory_uri() . '/logo.svg';
	}
	
	?>
	<style type="text/css">
		#login h1 a, .login h1 a {
			background-image: url(<?php echo esc_url( $logo_url ); ?>);
			background-size: contain;
			width: 100%;
		}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'prospero_custom_login_logo' );

/**
 * Change login logo URL
 */
function prospero_login_logo_url() {
	return home_url();
}
add_filter( 'login_headerurl', 'prospero_login_logo_url' );

/**
 * Change login logo title
 */
function prospero_login_logo_url_title() {
	return get_bloginfo( 'name' );
}
add_filter( 'login_headertext', 'prospero_login_logo_url_title' );

/**
 * Create frontend login pages when setting is enabled
 */
function prospero_create_frontend_pages( $setting ) {
	// Only create pages if setting is being enabled
	if ( 'prospero_enable_frontend_login' === $setting->id && true === $setting->post_value() ) {
		$pages = array(
			array(
				'title'    => __( 'Login', 'prospero-theme' ),
				'slug'     => 'login',
				'template' => '',
				'content'  => '[prospero_login_form]',
			),
			array(
				'title'    => __( 'Register', 'prospero-theme' ),
				'slug'     => 'register',
				'template' => '',
				'content'  => '[prospero_register_form]',
			),
			array(
				'title'    => __( 'Forgot Password', 'prospero-theme' ),
				'slug'     => 'forgot-password',
				'template' => '',
				'content'  => '[prospero_forgot_password_form]',
			),
			array(
				'title'    => __( 'My Account', 'prospero-theme' ),
				'slug'     => 'my-account',
				'template' => '',
				'content'  => '[prospero_my_account]',
			),
		);
		
		foreach ( $pages as $page ) {
			// Check if page already exists
			$existing_page = get_page_by_path( $page['slug'] );
			
			if ( ! $existing_page ) {
				// Create the page
				$page_id = wp_insert_post( array(
					'post_title'   => $page['title'],
					'post_name'    => $page['slug'],
					'post_content' => $page['content'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_author'  => 1,
				) );
				
				// Set page template if specified
				if ( ! empty( $page['template'] ) && ! is_wp_error( $page_id ) ) {
					update_post_meta( $page_id, '_wp_page_template', $page['template'] );
				}
			}
		}
	}
}
add_action( 'customize_save_after', 'prospero_create_frontend_pages' );

/**
 * Handle user registration
 */
function prospero_handle_registration() {
	if ( ! isset( $_POST['register_user'] ) ) {
		return;
	}
	
	// Verify nonce
	if ( ! isset( $_POST['prospero_register_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prospero_register_nonce'] ) ), 'prospero_register_user' ) ) {
		wp_die( esc_html__( 'Security verification failed.', 'prospero-theme' ) );
	}
	
	// Get form data
	$username = isset( $_POST['reg_username'] ) ? sanitize_user( wp_unslash( $_POST['reg_username'] ) ) : '';
	$email = isset( $_POST['reg_email'] ) ? sanitize_email( wp_unslash( $_POST['reg_email'] ) ) : '';
	$password = isset( $_POST['reg_password'] ) ? $_POST['reg_password'] : '';
	$password_confirm = isset( $_POST['reg_password_confirm'] ) ? $_POST['reg_password_confirm'] : '';
	$first_name = isset( $_POST['reg_first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['reg_first_name'] ) ) : '';
	$last_name = isset( $_POST['reg_last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['reg_last_name'] ) ) : '';
	
	// Validate
	$errors = array();
	
	if ( empty( $username ) ) {
		$errors[] = __( 'Username is required.', 'prospero-theme' );
	}
	
	if ( username_exists( $username ) ) {
		$errors[] = __( 'Username already exists.', 'prospero-theme' );
	}
	
	if ( ! validate_username( $username ) ) {
		$errors[] = __( 'Invalid username.', 'prospero-theme' );
	}
	
	if ( empty( $email ) || ! is_email( $email ) ) {
		$errors[] = __( 'Valid email is required.', 'prospero-theme' );
	}
	
	if ( email_exists( $email ) ) {
		$errors[] = __( 'Email already registered.', 'prospero-theme' );
	}
	
	if ( empty( $password ) || strlen( $password ) < 8 ) {
		$errors[] = __( 'Password must be at least 8 characters.', 'prospero-theme' );
	}
	
	if ( $password !== $password_confirm ) {
		$errors[] = __( 'Passwords do not match.', 'prospero-theme' );
	}
	
	if ( ! isset( $_POST['reg_terms'] ) ) {
		$errors[] = __( 'You must accept the terms and conditions.', 'prospero-theme' );
	}
	
	if ( ! empty( $errors ) ) {
		wp_die( implode( '<br>', array_map( 'esc_html', $errors ) ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	// Create user
	$user_id = wp_create_user( $username, $password, $email );
	
	if ( is_wp_error( $user_id ) ) {
		wp_die( esc_html( $user_id->get_error_message() ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	// Update user data
	wp_update_user( array(
		'ID'         => $user_id,
		'first_name' => $first_name,
		'last_name'  => $last_name,
	) );
	
	// Log user in
	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id );
	
	// Redirect
	wp_safe_redirect( home_url( '/my-account' ) );
	exit;
}
add_action( 'init', 'prospero_handle_registration' );

/**
 * Handle password reset request
 */
function prospero_handle_forgot_password() {
	if ( ! isset( $_POST['forgot_password'] ) ) {
		return;
	}
	
	// Verify nonce
	if ( ! isset( $_POST['prospero_forgot_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prospero_forgot_nonce'] ) ), 'prospero_forgot_password' ) ) {
		wp_die( esc_html__( 'Security verification failed.', 'prospero-theme' ) );
	}
	
	$email = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
	
	if ( empty( $email ) || ! is_email( $email ) ) {
		wp_die( esc_html__( 'Please enter a valid email address.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	$user = get_user_by( 'email', $email );
	
	if ( ! $user ) {
		wp_die( esc_html__( 'No user found with that email address.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	// Use WordPress core function to send password reset email
	$result = retrieve_password( $user->user_login );
	
	if ( is_wp_error( $result ) ) {
		wp_die( esc_html( $result->get_error_message() ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	// Success message and redirect
	wp_safe_redirect( add_query_arg( 'reset', 'sent', home_url( '/forgot-password' ) ) );
	exit;
}
add_action( 'init', 'prospero_handle_forgot_password' );

/**
 * Handle password reset with key
 */
function prospero_handle_reset_password() {
	if ( ! isset( $_POST['reset_password'] ) ) {
		return;
	}
	
	// Verify nonce
	if ( ! isset( $_POST['prospero_reset_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prospero_reset_nonce'] ) ), 'prospero_reset_password' ) ) {
		wp_die( esc_html__( 'Security verification failed.', 'prospero-theme' ) );
	}
	
	$reset_key = isset( $_POST['reset_key'] ) ? sanitize_text_field( wp_unslash( $_POST['reset_key'] ) ) : '';
	$reset_login = isset( $_POST['reset_login'] ) ? sanitize_text_field( wp_unslash( $_POST['reset_login'] ) ) : '';
	$new_password = isset( $_POST['new_password'] ) ? $_POST['new_password'] : '';
	$confirm_password = isset( $_POST['confirm_password'] ) ? $_POST['confirm_password'] : '';
	
	// Validate
	if ( empty( $new_password ) || strlen( $new_password ) < 8 ) {
		wp_die( esc_html__( 'Password must be at least 8 characters.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	if ( $new_password !== $confirm_password ) {
		wp_die( esc_html__( 'Passwords do not match.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	// Check reset key
	$user = check_password_reset_key( $reset_key, $reset_login );
	
	if ( is_wp_error( $user ) ) {
		wp_die( esc_html__( 'Invalid or expired reset link.', 'prospero-theme' ) . '<br><br><a href="' . esc_url( home_url( '/forgot-password' ) ) . '">' . esc_html__( 'Request new link', 'prospero-theme' ) . '</a>' );
	}
	
	// Reset password
	reset_password( $user, $new_password );
	
	// Redirect to login
	wp_safe_redirect( add_query_arg( 'password', 'reset', home_url( '/login' ) ) );
	exit;
}
add_action( 'init', 'prospero_handle_reset_password' );

/**
 * Handle profile update
 */
function prospero_handle_profile_update() {
	if ( ! isset( $_POST['update_profile'] ) ) {
		return;
	}
	
	if ( ! is_user_logged_in() ) {
		wp_die( esc_html__( 'You must be logged in to update your profile.', 'prospero-theme' ) );
	}
	
	// Verify nonce
	if ( ! isset( $_POST['prospero_profile_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prospero_profile_nonce'] ) ), 'prospero_update_profile' ) ) {
		wp_die( esc_html__( 'Security verification failed.', 'prospero-theme' ) );
	}
	
	$user_id = get_current_user_id();
	
	// Get form data
	$first_name = isset( $_POST['first_name'] ) ? sanitize_text_field( wp_unslash( $_POST['first_name'] ) ) : '';
	$last_name = isset( $_POST['last_name'] ) ? sanitize_text_field( wp_unslash( $_POST['last_name'] ) ) : '';
	$display_name = isset( $_POST['display_name'] ) ? sanitize_text_field( wp_unslash( $_POST['display_name'] ) ) : '';
	$user_email = isset( $_POST['user_email'] ) ? sanitize_email( wp_unslash( $_POST['user_email'] ) ) : '';
	$description = isset( $_POST['description'] ) ? sanitize_textarea_field( wp_unslash( $_POST['description'] ) ) : '';
	
	// Validate
	if ( empty( $display_name ) ) {
		wp_die( esc_html__( 'Display name is required.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	if ( empty( $user_email ) || ! is_email( $user_email ) ) {
		wp_die( esc_html__( 'Valid email is required.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	// Check if email exists for another user
	$email_owner = email_exists( $user_email );
	if ( $email_owner && $email_owner !== $user_id ) {
		wp_die( esc_html__( 'This email is already registered to another user.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	// Update user
	$result = wp_update_user( array(
		'ID'           => $user_id,
		'first_name'   => $first_name,
		'last_name'    => $last_name,
		'display_name' => $display_name,
		'user_email'   => $user_email,
		'description'  => $description,
	) );
	
	if ( is_wp_error( $result ) ) {
		wp_die( esc_html( $result->get_error_message() ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	// Redirect with success message
	wp_safe_redirect( add_query_arg( 'profile', 'updated', home_url( '/my-account' ) ) );
	exit;
}
add_action( 'init', 'prospero_handle_profile_update' );

/**
 * Shortcode: Login Form
 * Usage: [prospero_login_form]
 */
function prospero_login_form_shortcode() {
	// Redirect if already logged in
	if ( is_user_logged_in() ) {
		return '<p>' . esc_html__( 'You are already logged in.', 'prospero-theme' ) . ' <a href="' . esc_url( home_url( '/my-account' ) ) . '">' . esc_html__( 'Go to My Account', 'prospero-theme' ) . '</a></p>';
	}
	
	ob_start();
	?>
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
	<?php
	return ob_get_clean();
}
add_shortcode( 'prospero_login_form', 'prospero_login_form_shortcode' );

/**
 * Shortcode: Registration Form
 * Usage: [prospero_register_form]
 */
function prospero_register_form_shortcode() {
	// Redirect if already logged in
	if ( is_user_logged_in() ) {
		return '<p>' . esc_html__( 'You are already registered and logged in.', 'prospero-theme' ) . ' <a href="' . esc_url( home_url( '/my-account' ) ) . '">' . esc_html__( 'Go to My Account', 'prospero-theme' ) . '</a></p>';
	}
	
	ob_start();
	?>
	<div class="register-form-container">
		<form method="post" action="" class="register-form" id="register-form">
			<?php wp_nonce_field( 'prospero_register_user', 'prospero_register_nonce' ); ?>
			
			<div class="form-group">
				<label for="reg_username">
					<?php esc_html_e( 'Username', 'prospero-theme' ); ?>
					<span class="required">*</span>
				</label>
				<input type="text" name="reg_username" id="reg_username" class="form-control" required autocomplete="username">
			</div>
			
			<div class="form-group">
				<label for="reg_email">
					<?php esc_html_e( 'Email Address', 'prospero-theme' ); ?>
					<span class="required">*</span>
				</label>
				<input type="email" name="reg_email" id="reg_email" class="form-control" required autocomplete="email">
			</div>
			
			<div class="form-row">
				<div class="form-group">
					<label for="reg_first_name"><?php esc_html_e( 'First Name', 'prospero-theme' ); ?></label>
					<input type="text" name="reg_first_name" id="reg_first_name" class="form-control" autocomplete="given-name">
				</div>
				
				<div class="form-group">
					<label for="reg_last_name"><?php esc_html_e( 'Last Name', 'prospero-theme' ); ?></label>
					<input type="text" name="reg_last_name" id="reg_last_name" class="form-control" autocomplete="family-name">
				</div>
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
				<label class="checkbox-label">
					<input type="checkbox" name="reg_terms" required>
					<?php esc_html_e( 'I agree to the Terms and Conditions', 'prospero-theme' ); ?>
					<span class="required">*</span>
				</label>
			</div>
			
			<div class="form-actions">
				<button type="submit" name="register_user" class="button button-primary">
					<?php esc_html_e( 'Register', 'prospero-theme' ); ?>
				</button>
			</div>
		</form>
		
		<div class="form-footer">
			<p>
				<?php esc_html_e( 'Already have an account?', 'prospero-theme' ); ?>
				<a href="<?php echo esc_url( home_url( '/login' ) ); ?>" class="login-link">
					<?php esc_html_e( 'Login here', 'prospero-theme' ); ?>
				</a>
			</p>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'prospero_register_form', 'prospero_register_form_shortcode' );

/**
 * Shortcode: Forgot Password Form
 * Usage: [prospero_forgot_password_form]
 */
function prospero_forgot_password_form_shortcode() {
	// Redirect if already logged in
	if ( is_user_logged_in() ) {
		return '<p>' . esc_html__( 'You are already logged in.', 'prospero-theme' ) . ' <a href="' . esc_url( home_url( '/my-account' ) ) . '">' . esc_html__( 'Go to My Account', 'prospero-theme' ) . '</a></p>';
	}
	
	ob_start();
	?>
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
				<a href="<?php echo esc_url( home_url( '/login' ) ); ?>" class="login-link">
					<?php esc_html_e( '← Back to Login', 'prospero-theme' ); ?>
				</a>
			</p>
		</div>
	</div>
	<?php
	return ob_get_clean();
}
add_shortcode( 'prospero_forgot_password_form', 'prospero_forgot_password_form_shortcode' );

/**
 * Shortcode: My Account Dashboard
 * Usage: [prospero_my_account]
 */
function prospero_my_account_shortcode() {
	// Redirect if not logged in
	if ( ! is_user_logged_in() ) {
		return '<p>' . esc_html__( 'You must be logged in to view this page.', 'prospero-theme' ) . ' <a href="' . esc_url( home_url( '/login' ) ) . '">' . esc_html__( 'Login here', 'prospero-theme' ) . '</a></p>';
	}
	
	$current_user = wp_get_current_user();
	
	ob_start();
	?>
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
				<ul>
					<li class="active">
						<a href="#account-overview"><?php esc_html_e( 'Account Overview', 'prospero-theme' ); ?></a>
					</li>
					<li>
						<a href="#edit-profile"><?php esc_html_e( 'Edit Profile', 'prospero-theme' ); ?></a>
					</li>
					<li>
						<a href="#change-password"><?php esc_html_e( 'Change Password', 'prospero-theme' ); ?></a>
					</li>
					<li>
						<a href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php esc_html_e( 'Logout', 'prospero-theme' ); ?></a>
					</li>
				</ul>
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
	<?php
	return ob_get_clean();
}
add_shortcode( 'prospero_my_account', 'prospero_my_account_shortcode' );

/**
 * Handle password change
 */
function prospero_handle_password_change() {
	if ( ! isset( $_POST['change_password'] ) ) {
		return;
	}
	
	if ( ! is_user_logged_in() ) {
		wp_die( esc_html__( 'You must be logged in to change your password.', 'prospero-theme' ) );
	}
	
	// Verify nonce
	if ( ! isset( $_POST['prospero_password_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prospero_password_nonce'] ) ), 'prospero_change_password' ) ) {
		wp_die( esc_html__( 'Security verification failed.', 'prospero-theme' ) );
	}
	
	$user_id = get_current_user_id();
	$current_password = isset( $_POST['current_password'] ) ? $_POST['current_password'] : '';
	$new_password = isset( $_POST['new_password'] ) ? $_POST['new_password'] : '';
	$confirm_password = isset( $_POST['confirm_password'] ) ? $_POST['confirm_password'] : '';
	
	// Get user
	$user = get_user_by( 'id', $user_id );
	
	// Verify current password
	if ( ! wp_check_password( $current_password, $user->user_pass, $user_id ) ) {
		wp_die( esc_html__( 'Current password is incorrect.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	// Validate new password
	if ( empty( $new_password ) || strlen( $new_password ) < 8 ) {
		wp_die( esc_html__( 'New password must be at least 8 characters.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	if ( $new_password !== $confirm_password ) {
		wp_die( esc_html__( 'Passwords do not match.', 'prospero-theme' ) . '<br><br><a href="javascript:history.back()">' . esc_html__( 'Go back', 'prospero-theme' ) . '</a>' );
	}
	
	// Update password
	wp_set_password( $new_password, $user_id );
	
	// Log user in again (changing password logs them out)
	wp_set_current_user( $user_id );
	wp_set_auth_cookie( $user_id );
	
	// Redirect with success message
	wp_safe_redirect( add_query_arg( 'password', 'changed', home_url( '/my-account' ) ) );
	exit;
}
add_action( 'init', 'prospero_handle_password_change' );
