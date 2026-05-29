<?php
/**
 * Per-post / per-page Featured Image Width override.
 *
 * Stores `_prospero_featured_fullwidth` ('', 'fullwidth' or 'constrained')
 * and resolves via prospero_should_show_fullwidth_featured() in
 * inc/template-functions.php.
 *
 * @package Prospero
 * @since 1.1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the meta field for REST / block editor compatibility.
 */
function prospero_register_featured_fullwidth_meta() {
	$args = array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'default'           => '',
		'sanitize_callback' => 'prospero_sanitize_featured_fullwidth',
		'auth_callback'     => function () {
			return current_user_can( 'edit_posts' );
		},
	);

	register_post_meta( 'post', '_prospero_featured_fullwidth', $args );
	register_post_meta( 'page', '_prospero_featured_fullwidth', $args );
}
add_action( 'init', 'prospero_register_featured_fullwidth_meta' );

/**
 * Sanitize the meta value to one of the three allowed states.
 *
 * @param mixed $value Raw input.
 * @return string '', 'fullwidth' or 'constrained'.
 */
function prospero_sanitize_featured_fullwidth( $value ) {
	$allowed = array( '', 'fullwidth', 'constrained' );
	return in_array( $value, $allowed, true ) ? $value : '';
}

/**
 * Register the meta box on the classic editor sidebar.
 */
function prospero_add_featured_fullwidth_meta_box() {
	add_meta_box(
		'prospero_featured_fullwidth',
		esc_html__( 'Featured Image Width', 'prospero-theme' ),
		'prospero_featured_fullwidth_meta_box_cb',
		array( 'post', 'page' ),
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'prospero_add_featured_fullwidth_meta_box' );

/**
 * Meta box callback.
 *
 * @param WP_Post $post Current post.
 */
function prospero_featured_fullwidth_meta_box_cb( $post ) {
	wp_nonce_field( 'prospero_featured_fullwidth_nonce', 'prospero_featured_fullwidth_nonce' );

	$current = get_post_meta( $post->ID, '_prospero_featured_fullwidth', true );

	$default_key = 'page' === $post->post_type
		? 'prospero_page_featured_fullwidth'
		: 'prospero_single_featured_fullwidth';
	$default_label = get_theme_mod( $default_key, false )
		? esc_html__( 'Full-width banner', 'prospero-theme' )
		: esc_html__( 'Constrained to content width', 'prospero-theme' );
	?>
	<p>
		<label>
			<input type="radio" name="prospero_featured_fullwidth" value="" <?php checked( $current, '' ); ?> />
			<?php
			printf(
				/* translators: %s: Resolved default label. */
				esc_html__( 'Use theme default (%s)', 'prospero-theme' ),
				esc_html( $default_label )
			);
			?>
		</label>
	</p>
	<p>
		<label>
			<input type="radio" name="prospero_featured_fullwidth" value="fullwidth" <?php checked( $current, 'fullwidth' ); ?> />
			<?php esc_html_e( 'Full-width banner', 'prospero-theme' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="radio" name="prospero_featured_fullwidth" value="constrained" <?php checked( $current, 'constrained' ); ?> />
			<?php esc_html_e( 'Constrained to content width', 'prospero-theme' ); ?>
		</label>
	</p>
	<p class="description">
		<?php esc_html_e( 'Overrides the theme default for this entry only. Has no effect when no featured image is set.', 'prospero-theme' ); ?>
	</p>
	<?php
}

/**
 * Save the meta value on post save.
 *
 * @param int $post_id Current post ID.
 */
function prospero_save_featured_fullwidth_meta( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! isset( $_POST['prospero_featured_fullwidth_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prospero_featured_fullwidth_nonce'] ) ), 'prospero_featured_fullwidth_nonce' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$value = isset( $_POST['prospero_featured_fullwidth'] )
		? prospero_sanitize_featured_fullwidth( wp_unslash( $_POST['prospero_featured_fullwidth'] ) )
		: '';

	if ( '' === $value ) {
		delete_post_meta( $post_id, '_prospero_featured_fullwidth' );
	} else {
		update_post_meta( $post_id, '_prospero_featured_fullwidth', $value );
	}
}
add_action( 'save_post', 'prospero_save_featured_fullwidth_meta' );
