<?php
/**
 * Custom Post Types
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Testimonials Post Type
 */
function prospero_register_testimonials() {
	if ( ! get_theme_mod( 'prospero_enable_testimonials', true ) ) {
		return;
	}

	$labels = array(
		'name'               => esc_html__( 'Testimonials', 'prospero-theme' ),
		'singular_name'      => esc_html__( 'Testimonial', 'prospero-theme' ),
		'menu_name'          => esc_html__( 'Testimonials', 'prospero-theme' ),
		'add_new'            => esc_html__( 'Add New', 'prospero-theme' ),
		'add_new_item'       => esc_html__( 'Add New Testimonial', 'prospero-theme' ),
		'edit_item'          => esc_html__( 'Edit Testimonial', 'prospero-theme' ),
		'new_item'           => esc_html__( 'New Testimonial', 'prospero-theme' ),
		'view_item'          => esc_html__( 'View Testimonial', 'prospero-theme' ),
		'search_items'       => esc_html__( 'Search Testimonials', 'prospero-theme' ),
		'not_found'          => esc_html__( 'No testimonials found', 'prospero-theme' ),
		'not_found_in_trash' => esc_html__( 'No testimonials found in trash', 'prospero-theme' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => false,
		'publicly_queryable'  => false,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => false,
		'rewrite'             => false,
		'capability_type'     => 'post',
		'has_archive'         => false,
		'hierarchical'        => false,
		'menu_position'       => 20,
		'menu_icon'           => 'dashicons-format-quote',
		'supports'            => array( 'title', 'editor', 'thumbnail' ),
		'show_in_rest'        => true,
	);

	register_post_type( 'testimonial', $args );

	// Register taxonomy
	register_taxonomy( 'testimonial_category', 'testimonial', array(
		'labels'            => array(
			'name'          => esc_html__( 'Testimonial Categories', 'prospero-theme' ),
			'singular_name' => esc_html__( 'Testimonial Category', 'prospero-theme' ),
		),
		'hierarchical'      => true,
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => false,
	) );
}
add_action( 'init', 'prospero_register_testimonials' );

/**
 * Register Partners Post Type
 */
function prospero_register_partners() {
	if ( ! get_theme_mod( 'prospero_enable_partners', true ) ) {
		return;
	}

	$labels = array(
		'name'               => esc_html__( 'Partners', 'prospero-theme' ),
		'singular_name'      => esc_html__( 'Partner', 'prospero-theme' ),
		'menu_name'          => esc_html__( 'Partners', 'prospero-theme' ),
		'add_new'            => esc_html__( 'Add New', 'prospero-theme' ),
		'add_new_item'       => esc_html__( 'Add New Partner', 'prospero-theme' ),
		'edit_item'          => esc_html__( 'Edit Partner', 'prospero-theme' ),
		'new_item'           => esc_html__( 'New Partner', 'prospero-theme' ),
		'view_item'          => esc_html__( 'View Partner', 'prospero-theme' ),
		'search_items'       => esc_html__( 'Search Partners', 'prospero-theme' ),
		'not_found'          => esc_html__( 'No partners found', 'prospero-theme' ),
		'not_found_in_trash' => esc_html__( 'No partners found in trash', 'prospero-theme' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'partners' ),
		'capability_type'     => 'post',
		'has_archive'         => false,
		'hierarchical'        => false,
		'menu_position'       => 21,
		'menu_icon'           => 'dashicons-groups',
		'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'show_in_rest'        => true,
	);

	register_post_type( 'partner', $args );

	// Register taxonomy
	register_taxonomy( 'partner_category', 'partner', array(
		'labels'            => array(
			'name'          => esc_html__( 'Partner Categories', 'prospero-theme' ),
			'singular_name' => esc_html__( 'Partner Category', 'prospero-theme' ),
		),
		'hierarchical'      => true,
		'public'            => false,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => false,
	) );
}
add_action( 'init', 'prospero_register_partners' );

/**
 * Register Team Post Type
 */
function prospero_register_team() {
	if ( ! get_theme_mod( 'prospero_enable_team', true ) ) {
		return;
	}

	$labels = array(
		'name'               => esc_html__( 'Team', 'prospero-theme' ),
		'singular_name'      => esc_html__( 'Team Member', 'prospero-theme' ),
		'menu_name'          => esc_html__( 'Team', 'prospero-theme' ),
		'add_new'            => esc_html__( 'Add New', 'prospero-theme' ),
		'add_new_item'       => esc_html__( 'Add New Team Member', 'prospero-theme' ),
		'edit_item'          => esc_html__( 'Edit Team Member', 'prospero-theme' ),
		'new_item'           => esc_html__( 'New Team Member', 'prospero-theme' ),
		'view_item'          => esc_html__( 'View Team Member', 'prospero-theme' ),
		'search_items'       => esc_html__( 'Search Team', 'prospero-theme' ),
		'not_found'          => esc_html__( 'No team members found', 'prospero-theme' ),
		'not_found_in_trash' => esc_html__( 'No team members found in trash', 'prospero-theme' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'team' ),
		'capability_type'     => 'post',
		'has_archive'         => false,
		'hierarchical'        => false,
		'menu_position'       => 22,
		'menu_icon'           => 'dashicons-groups',
		'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'show_in_rest'        => true,
	);

	register_post_type( 'team', $args );

	// Register taxonomy
	register_taxonomy( 'team_category', 'team', array(
		'labels'            => array(
			'name'          => esc_html__( 'Team Categories', 'prospero-theme' ),
			'singular_name' => esc_html__( 'Team Category', 'prospero-theme' ),
		),
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'team-category' ),
	) );
}
add_action( 'init', 'prospero_register_team' );

/**
 * Register Projects Post Type
 */
function prospero_register_projects() {
	if ( ! get_theme_mod( 'prospero_enable_projects', true ) ) {
		return;
	}

	$labels = array(
		'name'               => esc_html__( 'Projects', 'prospero-theme' ),
		'singular_name'      => esc_html__( 'Project', 'prospero-theme' ),
		'menu_name'          => esc_html__( 'Projects', 'prospero-theme' ),
		'add_new'            => esc_html__( 'Add New', 'prospero-theme' ),
		'add_new_item'       => esc_html__( 'Add New Project', 'prospero-theme' ),
		'edit_item'          => esc_html__( 'Edit Project', 'prospero-theme' ),
		'new_item'           => esc_html__( 'New Project', 'prospero-theme' ),
		'view_item'          => esc_html__( 'View Project', 'prospero-theme' ),
		'search_items'       => esc_html__( 'Search Projects', 'prospero-theme' ),
		'not_found'          => esc_html__( 'No projects found', 'prospero-theme' ),
		'not_found_in_trash' => esc_html__( 'No projects found in trash', 'prospero-theme' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'projects' ),
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => 23,
		'menu_icon'           => 'dashicons-portfolio',
		'supports'            => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
		'show_in_rest'        => true,
	);

	register_post_type( 'project', $args );

	// Register taxonomy
	register_taxonomy( 'project_tag', 'project', array(
		'labels'            => array(
			'name'          => esc_html__( 'Project Tags', 'prospero-theme' ),
			'singular_name' => esc_html__( 'Project Tag', 'prospero-theme' ),
		),
		'hierarchical'      => false,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'project-tag' ),
	) );
}
add_action( 'init', 'prospero_register_projects' );

/**
 * Register FAQ Post Type
 */
function prospero_register_faq() {
	if ( ! get_theme_mod( 'prospero_enable_faq', true ) ) {
		return;
	}

	$labels = array(
		'name'               => esc_html__( 'FAQs', 'prospero-theme' ),
		'singular_name'      => esc_html__( 'FAQ', 'prospero-theme' ),
		'menu_name'          => esc_html__( 'FAQs', 'prospero-theme' ),
		'add_new'            => esc_html__( 'Add New', 'prospero-theme' ),
		'add_new_item'       => esc_html__( 'Add New FAQ', 'prospero-theme' ),
		'edit_item'          => esc_html__( 'Edit FAQ', 'prospero-theme' ),
		'new_item'           => esc_html__( 'New FAQ', 'prospero-theme' ),
		'view_item'          => esc_html__( 'View FAQ', 'prospero-theme' ),
		'search_items'       => esc_html__( 'Search FAQs', 'prospero-theme' ),
		'not_found'          => esc_html__( 'No FAQs found', 'prospero-theme' ),
		'not_found_in_trash' => esc_html__( 'No FAQs found in trash', 'prospero-theme' ),
	);

	$args = array(
		'labels'              => $labels,
		'public'              => true,
		'publicly_queryable'  => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'query_var'           => true,
		'rewrite'             => array( 'slug' => 'faq' ),
		'capability_type'     => 'post',
		'has_archive'         => true,
		'hierarchical'        => false,
		'menu_position'       => 24,
		'menu_icon'           => 'dashicons-editor-help',
		'supports'            => array( 'title', 'editor', 'page-attributes' ),
		'show_in_rest'        => true,
	);

	register_post_type( 'faq', $args );

	// Register taxonomy
	register_taxonomy( 'faq_category', 'faq', array(
		'labels'            => array(
			'name'          => esc_html__( 'FAQ Categories', 'prospero-theme' ),
			'singular_name' => esc_html__( 'FAQ Category', 'prospero-theme' ),
		),
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_rest'      => true,
		'rewrite'           => array( 'slug' => 'faq-category' ),
	) );
}
add_action( 'init', 'prospero_register_faq' );

/**
 * Add meta boxes for custom fields
 */
function prospero_add_meta_boxes() {
	// Testimonial display name
	if ( get_theme_mod( 'prospero_enable_testimonials', false ) ) {
		add_meta_box(
			'prospero_testimonial_display_name',
			esc_html__( 'Display Name', 'prospero-theme' ),
			'prospero_testimonial_display_name_callback',
			'testimonial',
			'normal',
			'high'
		);
	}

	// Partner website URL
	if ( get_theme_mod( 'prospero_enable_partners', false ) ) {
		add_meta_box(
			'prospero_partner_url',
			esc_html__( 'Partner Website', 'prospero-theme' ),
			'prospero_partner_url_callback',
			'partner',
			'normal',
			'high'
		);
	}

	// Team member fields
	if ( get_theme_mod( 'prospero_enable_team', false ) ) {
		// Display name
		add_meta_box(
			'prospero_team_display_name',
			esc_html__( 'Display Name', 'prospero-theme' ),
			'prospero_team_display_name_callback',
			'team',
			'normal',
			'high'
		);

		// Position
		add_meta_box(
			'prospero_team_position',
			esc_html__( 'Position', 'prospero-theme' ),
			'prospero_team_position_callback',
			'team',
			'normal',
			'high'
		);
		
		// Secondary thumbnail
		add_meta_box(
			'prospero_team_secondary_image',
			esc_html__( 'Secondary Image (Hover)', 'prospero-theme' ),
			'prospero_team_secondary_image_callback',
			'team',
			'side',
			'low'
		);

		// Contact info
		add_meta_box(
			'prospero_team_contact',
			esc_html__( 'Contact Information', 'prospero-theme' ),
			'prospero_team_contact_callback',
			'team',
			'normal',
			'default'
		);

		// Social links
		add_meta_box(
			'prospero_team_social',
			esc_html__( 'Social Links', 'prospero-theme' ),
			'prospero_team_social_callback',
			'team',
			'normal',
			'default'
		);
	}

	// Project fields
	if ( get_theme_mod( 'prospero_enable_projects', false ) ) {
		// Project website URL
		add_meta_box(
			'prospero_project_url',
			esc_html__( 'Project Website', 'prospero-theme' ),
			'prospero_project_url_callback',
			'project',
			'normal',
			'high'
		);

		// Image gallery
		add_meta_box(
			'prospero_project_gallery',
			esc_html__( 'Project Gallery', 'prospero-theme' ),
			'prospero_project_gallery_callback',
			'project',
			'normal',
			'default'
		);

		// Testimonial selection
		if ( get_theme_mod( 'prospero_enable_testimonials', false ) ) {
			add_meta_box(
				'prospero_project_testimonial',
				esc_html__( 'Related Testimonial', 'prospero-theme' ),
				'prospero_project_testimonial_callback',
				'project',
				'side',
				'default'
			);
		}
	}
}
add_action( 'add_meta_boxes', 'prospero_add_meta_boxes' );

/**
 * Testimonial display name callback
 */
function prospero_testimonial_display_name_callback( $post ) {
	wp_nonce_field( 'prospero_testimonial_display_name_nonce', 'prospero_testimonial_display_name_nonce' );
	$value = get_post_meta( $post->ID, '_prospero_testimonial_display_name', true );
	echo '<p class="description">' . esc_html__( 'The name shown on the frontend (e.g., "John D., CEO at Company")', 'prospero-theme' ) . '</p>';
	echo '<input type="text" name="prospero_testimonial_display_name" value="' . esc_attr( $value ) . '" class="widefat" />';
}

/**
 * Partner URL callback
 */
function prospero_partner_url_callback( $post ) {
	wp_nonce_field( 'prospero_partner_url_nonce', 'prospero_partner_url_nonce' );
	$value = get_post_meta( $post->ID, '_prospero_partner_url', true );
	echo '<input type="url" name="prospero_partner_url" value="' . esc_url( $value ) . '" class="widefat" placeholder="https://" />';
}

/**
 * Team display name callback
 */
function prospero_team_display_name_callback( $post ) {
	wp_nonce_field( 'prospero_team_display_name_nonce', 'prospero_team_display_name_nonce' );
	$value = get_post_meta( $post->ID, '_prospero_team_display_name', true );
	echo '<p class="description">' . esc_html__( 'The name shown on the frontend (title is used for admin sorting)', 'prospero-theme' ) . '</p>';
	echo '<input type="text" name="prospero_team_display_name" value="' . esc_attr( $value ) . '" class="widefat" />';
}

/**
 * Team position callback
 */
function prospero_team_position_callback( $post ) {
	wp_nonce_field( 'prospero_team_position_nonce', 'prospero_team_position_nonce' );
	$value = get_post_meta( $post->ID, '_prospero_team_position', true );
	echo '<p class="description">' . esc_html__( 'Job title or role (e.g., "CEO", "Marketing Manager")', 'prospero-theme' ) . '</p>';
	echo '<input type="text" name="prospero_team_position" value="' . esc_attr( $value ) . '" class="widefat" />';
}

/**
 * Team secondary image callback
 */
function prospero_team_secondary_image_callback( $post ) {
	wp_nonce_field( 'prospero_team_secondary_image_nonce', 'prospero_team_secondary_image_nonce' );
	$value = get_post_meta( $post->ID, '_prospero_team_secondary_image', true );
	?>
	<div class="prospero-image-upload">
		<input type="hidden" name="prospero_team_secondary_image" id="prospero_team_secondary_image" value="<?php echo esc_attr( $value ); ?>" />
		<button type="button" class="button prospero-upload-image"><?php esc_html_e( 'Upload Image', 'prospero-theme' ); ?></button>
		<button type="button" class="button prospero-remove-image" style="<?php echo $value ? '' : 'display:none;'; ?>"><?php esc_html_e( 'Remove Image', 'prospero-theme' ); ?></button>
		<div class="prospero-image-preview">
			<?php if ( $value ) : ?>
				<img src="<?php echo esc_url( wp_get_attachment_url( $value ) ); ?>" style="max-width: 100%;" />
			<?php endif; ?>
		</div>
	</div>
	<?php
}

/**
 * Team contact information callback
 */
function prospero_team_contact_callback( $post ) {
	wp_nonce_field( 'prospero_team_contact_nonce', 'prospero_team_contact_nonce' );
	$email = get_post_meta( $post->ID, '_prospero_team_email', true );
	$phone = get_post_meta( $post->ID, '_prospero_team_phone', true );
	?>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="prospero_team_email"><?php esc_html_e( 'Email', 'prospero-theme' ); ?></label></th>
			<td><input type="email" name="prospero_team_email" id="prospero_team_email" value="<?php echo esc_attr( $email ); ?>" class="regular-text" placeholder="email@example.com" /></td>
		</tr>
		<tr>
			<th scope="row"><label for="prospero_team_phone"><?php esc_html_e( 'Phone', 'prospero-theme' ); ?></label></th>
			<td><input type="tel" name="prospero_team_phone" id="prospero_team_phone" value="<?php echo esc_attr( $phone ); ?>" class="regular-text" placeholder="+43 123 456 789" /></td>
		</tr>
	</table>
	<?php
}

/**
 * Team social links callback
 */
function prospero_team_social_callback( $post ) {
	wp_nonce_field( 'prospero_team_social_nonce', 'prospero_team_social_nonce' );
	$linkedin = get_post_meta( $post->ID, '_prospero_team_linkedin', true );
	$youtube = get_post_meta( $post->ID, '_prospero_team_youtube', true );
	$instagram = get_post_meta( $post->ID, '_prospero_team_instagram', true );
	$facebook = get_post_meta( $post->ID, '_prospero_team_facebook', true );
	$xing = get_post_meta( $post->ID, '_prospero_team_xing', true );
	?>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="prospero_team_linkedin"><?php esc_html_e( 'LinkedIn', 'prospero-theme' ); ?></label></th>
			<td><input type="url" name="prospero_team_linkedin" id="prospero_team_linkedin" value="<?php echo esc_url( $linkedin ); ?>" class="regular-text" placeholder="https://linkedin.com/in/..." /></td>
		</tr>
		<tr>
			<th scope="row"><label for="prospero_team_youtube"><?php esc_html_e( 'YouTube', 'prospero-theme' ); ?></label></th>
			<td><input type="url" name="prospero_team_youtube" id="prospero_team_youtube" value="<?php echo esc_url( $youtube ); ?>" class="regular-text" placeholder="https://youtube.com/@..." /></td>
		</tr>
		<tr>
			<th scope="row"><label for="prospero_team_instagram"><?php esc_html_e( 'Instagram', 'prospero-theme' ); ?></label></th>
			<td><input type="url" name="prospero_team_instagram" id="prospero_team_instagram" value="<?php echo esc_url( $instagram ); ?>" class="regular-text" placeholder="https://instagram.com/..." /></td>
		</tr>
		<tr>
			<th scope="row"><label for="prospero_team_facebook"><?php esc_html_e( 'Facebook', 'prospero-theme' ); ?></label></th>
			<td><input type="url" name="prospero_team_facebook" id="prospero_team_facebook" value="<?php echo esc_url( $facebook ); ?>" class="regular-text" placeholder="https://facebook.com/..." /></td>
		</tr>
		<tr>
			<th scope="row"><label for="prospero_team_xing"><?php esc_html_e( 'Xing', 'prospero-theme' ); ?></label></th>
			<td><input type="url" name="prospero_team_xing" id="prospero_team_xing" value="<?php echo esc_url( $xing ); ?>" class="regular-text" placeholder="https://xing.com/profile/..." /></td>
		</tr>
	</table>
	<?php
}

/**
 * Project URL callback
 */
function prospero_project_url_callback( $post ) {
	wp_nonce_field( 'prospero_project_url_nonce', 'prospero_project_url_nonce' );
	$value = get_post_meta( $post->ID, '_prospero_project_url', true );
	echo '<input type="url" name="prospero_project_url" value="' . esc_url( $value ) . '" class="widefat" placeholder="https://" />';
}

/**
 * Project gallery callback
 */
function prospero_project_gallery_callback( $post ) {
	wp_nonce_field( 'prospero_project_gallery_nonce', 'prospero_project_gallery_nonce' );
	$gallery_ids = get_post_meta( $post->ID, '_prospero_project_gallery', true );
	$gallery_ids = is_array( $gallery_ids ) ? $gallery_ids : array();
	?>
	<div class="prospero-gallery-upload">
		<input type="hidden" name="prospero_project_gallery" id="prospero_project_gallery" value="<?php echo esc_attr( implode( ',', $gallery_ids ) ); ?>" />
		<div class="prospero-gallery-preview" id="prospero-gallery-preview">
			<?php foreach ( $gallery_ids as $image_id ) : 
				$image_url = wp_get_attachment_image_url( $image_id, 'thumbnail' );
				if ( $image_url ) :
			?>
				<div class="prospero-gallery-item" data-id="<?php echo esc_attr( $image_id ); ?>">
					<img src="<?php echo esc_url( $image_url ); ?>" alt="" />
					<button type="button" class="prospero-gallery-remove" aria-label="<?php esc_attr_e( 'Remove image', 'prospero-theme' ); ?>">&times;</button>
				</div>
			<?php endif; endforeach; ?>
		</div>
		<p>
			<button type="button" class="button prospero-gallery-add" id="prospero-gallery-add">
				<?php esc_html_e( 'Add Images', 'prospero-theme' ); ?>
			</button>
		</p>
		<p class="description"><?php esc_html_e( 'Click to add images. Drag to reorder.', 'prospero-theme' ); ?></p>
	</div>
	<?php
}

/**
 * Project testimonial callback
 */
function prospero_project_testimonial_callback( $post ) {
	wp_nonce_field( 'prospero_project_testimonial_nonce', 'prospero_project_testimonial_nonce' );
	$selected = get_post_meta( $post->ID, '_prospero_project_testimonial', true );
	
	// Get all testimonials
	$testimonials = get_posts( array(
		'post_type'      => 'testimonial',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
	?>
	<select name="prospero_project_testimonial" class="widefat">
		<option value=""><?php esc_html_e( '— None —', 'prospero-theme' ); ?></option>
		<?php foreach ( $testimonials as $testimonial ) : ?>
			<option value="<?php echo esc_attr( $testimonial->ID ); ?>" <?php selected( $selected, $testimonial->ID ); ?>>
				<?php echo esc_html( $testimonial->post_title ); ?>
			</option>
		<?php endforeach; ?>
	</select>
	<p class="description"><?php esc_html_e( 'Select a testimonial to display with this project.', 'prospero-theme' ); ?></p>
	<?php
}

/**
 * Save meta box data
 */
function prospero_save_meta_boxes( $post_id ) {
	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Testimonial display name
	if ( isset( $_POST['prospero_testimonial_display_name_nonce'] ) && wp_verify_nonce( $_POST['prospero_testimonial_display_name_nonce'], 'prospero_testimonial_display_name_nonce' ) ) {
		if ( isset( $_POST['prospero_testimonial_display_name'] ) ) {
			update_post_meta( $post_id, '_prospero_testimonial_display_name', sanitize_text_field( $_POST['prospero_testimonial_display_name'] ) );
		}
	}

	// Partner URL
	if ( isset( $_POST['prospero_partner_url_nonce'] ) && wp_verify_nonce( $_POST['prospero_partner_url_nonce'], 'prospero_partner_url_nonce' ) ) {
		if ( isset( $_POST['prospero_partner_url'] ) ) {
			update_post_meta( $post_id, '_prospero_partner_url', esc_url_raw( $_POST['prospero_partner_url'] ) );
		}
	}

	// Team display name
	if ( isset( $_POST['prospero_team_display_name_nonce'] ) && wp_verify_nonce( $_POST['prospero_team_display_name_nonce'], 'prospero_team_display_name_nonce' ) ) {
		if ( isset( $_POST['prospero_team_display_name'] ) ) {
			update_post_meta( $post_id, '_prospero_team_display_name', sanitize_text_field( $_POST['prospero_team_display_name'] ) );
		}
	}

	// Team position
	if ( isset( $_POST['prospero_team_position_nonce'] ) && wp_verify_nonce( $_POST['prospero_team_position_nonce'], 'prospero_team_position_nonce' ) ) {
		if ( isset( $_POST['prospero_team_position'] ) ) {
			update_post_meta( $post_id, '_prospero_team_position', sanitize_text_field( $_POST['prospero_team_position'] ) );
		}
	}

	// Team secondary image
	if ( isset( $_POST['prospero_team_secondary_image_nonce'] ) && wp_verify_nonce( $_POST['prospero_team_secondary_image_nonce'], 'prospero_team_secondary_image_nonce' ) ) {
		if ( isset( $_POST['prospero_team_secondary_image'] ) ) {
			update_post_meta( $post_id, '_prospero_team_secondary_image', absint( $_POST['prospero_team_secondary_image'] ) );
		}
	}

	// Team contact info
	if ( isset( $_POST['prospero_team_contact_nonce'] ) && wp_verify_nonce( $_POST['prospero_team_contact_nonce'], 'prospero_team_contact_nonce' ) ) {
		if ( isset( $_POST['prospero_team_email'] ) ) {
			update_post_meta( $post_id, '_prospero_team_email', sanitize_email( $_POST['prospero_team_email'] ) );
		}
		if ( isset( $_POST['prospero_team_phone'] ) ) {
			update_post_meta( $post_id, '_prospero_team_phone', sanitize_text_field( $_POST['prospero_team_phone'] ) );
		}
	}

	// Team social links
	if ( isset( $_POST['prospero_team_social_nonce'] ) && wp_verify_nonce( $_POST['prospero_team_social_nonce'], 'prospero_team_social_nonce' ) ) {
		if ( isset( $_POST['prospero_team_linkedin'] ) ) {
			update_post_meta( $post_id, '_prospero_team_linkedin', esc_url_raw( $_POST['prospero_team_linkedin'] ) );
		}
		if ( isset( $_POST['prospero_team_youtube'] ) ) {
			update_post_meta( $post_id, '_prospero_team_youtube', esc_url_raw( $_POST['prospero_team_youtube'] ) );
		}
		if ( isset( $_POST['prospero_team_instagram'] ) ) {
			update_post_meta( $post_id, '_prospero_team_instagram', esc_url_raw( $_POST['prospero_team_instagram'] ) );
		}
		if ( isset( $_POST['prospero_team_facebook'] ) ) {
			update_post_meta( $post_id, '_prospero_team_facebook', esc_url_raw( $_POST['prospero_team_facebook'] ) );
		}
		if ( isset( $_POST['prospero_team_xing'] ) ) {
			update_post_meta( $post_id, '_prospero_team_xing', esc_url_raw( $_POST['prospero_team_xing'] ) );
		}
	}

	// Project URL
	if ( isset( $_POST['prospero_project_url_nonce'] ) && wp_verify_nonce( $_POST['prospero_project_url_nonce'], 'prospero_project_url_nonce' ) ) {
		if ( isset( $_POST['prospero_project_url'] ) ) {
			update_post_meta( $post_id, '_prospero_project_url', esc_url_raw( $_POST['prospero_project_url'] ) );
		}
	}

	// Project gallery
	if ( isset( $_POST['prospero_project_gallery_nonce'] ) && wp_verify_nonce( $_POST['prospero_project_gallery_nonce'], 'prospero_project_gallery_nonce' ) ) {
		if ( isset( $_POST['prospero_project_gallery'] ) ) {
			$gallery_ids = array_filter( array_map( 'absint', explode( ',', $_POST['prospero_project_gallery'] ) ) );
			update_post_meta( $post_id, '_prospero_project_gallery', $gallery_ids );
		}
	}

	// Project testimonial
	if ( isset( $_POST['prospero_project_testimonial_nonce'] ) && wp_verify_nonce( $_POST['prospero_project_testimonial_nonce'], 'prospero_project_testimonial_nonce' ) ) {
		if ( isset( $_POST['prospero_project_testimonial'] ) ) {
			$testimonial_id = absint( $_POST['prospero_project_testimonial'] );
			if ( $testimonial_id ) {
				update_post_meta( $post_id, '_prospero_project_testimonial', $testimonial_id );
			} else {
				delete_post_meta( $post_id, '_prospero_project_testimonial' );
			}
		}
	}
}
add_action( 'save_post', 'prospero_save_meta_boxes' );

/**
 * Add noindex meta to testimonials
 */
function prospero_noindex_testimonials() {
	if ( is_singular( 'testimonial' ) ) {
		echo '<meta name="robots" content="noindex,nofollow" />' . "\n";
	}
}
add_action( 'wp_head', 'prospero_noindex_testimonials' );

/**
 * Add noindex meta to partners
 */
function prospero_noindex_partners() {
	if ( is_singular( 'partner' ) || is_post_type_archive( 'partner' ) ) {
		echo '<meta name="robots" content="noindex,nofollow" />' . "\n";
	}
}
add_action( 'wp_head', 'prospero_noindex_partners' );

/**
 * Enqueue admin scripts and styles for meta boxes
 */
function prospero_admin_enqueue_scripts( $hook ) {
	global $post_type;
	
	// Only load on post edit screens for our custom post types
	if ( ! in_array( $hook, array( 'post.php', 'post-new.php' ), true ) ) {
		return;
	}
	
	$custom_post_types = array( 'testimonial', 'partner', 'team', 'project' );
	if ( ! in_array( $post_type, $custom_post_types, true ) ) {
		return;
	}
	
	// Enqueue media uploader
	wp_enqueue_media();
	
	// Enqueue jQuery UI sortable for gallery
	wp_enqueue_script( 'jquery-ui-sortable' );
	
	// Admin CSS
	wp_enqueue_style(
		'prospero-admin-meta-boxes',
		PROSPERO_THEME_URI . '/assets/css/admin-meta-boxes.css',
		array(),
		PROSPERO_VERSION
	);
	
	// Admin JS
	wp_enqueue_script(
		'prospero-admin-meta-boxes',
		PROSPERO_THEME_URI . '/assets/js/admin-meta-boxes.js',
		array( 'jquery', 'jquery-ui-sortable' ),
		PROSPERO_VERSION,
		true
	);
	
	// Localize script
	wp_localize_script( 'prospero-admin-meta-boxes', 'prosperoAdmin', array(
		'uploadImageTitle'  => esc_html__( 'Select Image', 'prospero-theme' ),
		'useImageText'      => esc_html__( 'Use this image', 'prospero-theme' ),
		'addGalleryTitle'   => esc_html__( 'Add Gallery Images', 'prospero-theme' ),
		'addToGalleryText'  => esc_html__( 'Add to gallery', 'prospero-theme' ),
		'removeImageLabel'  => esc_html__( 'Remove image', 'prospero-theme' ),
	) );
}
add_action( 'admin_enqueue_scripts', 'prospero_admin_enqueue_scripts' );
