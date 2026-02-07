<?php
/**
 * Custom Gutenberg Blocks
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register all custom blocks
 */
function prospero_register_custom_blocks() {
	// Text Content Block (wrapper for paragraphs/headings)
	register_block_type( 'prospero/text-content', array(
		'editor_script' => 'prospero-text-content-block',
	) );
	
	// Call to Action Block
	register_block_type( 'prospero/cta', array(
		'attributes'      => array(
			'heading'     => array( 'type' => 'string', 'default' => '' ),
			'content'     => array( 'type' => 'string', 'default' => '' ),
			'buttonText'  => array( 'type' => 'string', 'default' => '' ),
			'buttonUrl'   => array( 'type' => 'string', 'default' => '' ),
			'buttonStyle' => array( 'type' => 'string', 'default' => 'primary' ),
			'layout'      => array( 'type' => 'string', 'default' => 'center' ),
			'imageId'     => array( 'type' => 'number', 'default' => 0 ),
			'imageUrl'    => array( 'type' => 'string', 'default' => '' ),
			'bgImageId'   => array( 'type' => 'number', 'default' => 0 ),
			'bgImageUrl'  => array( 'type' => 'string', 'default' => '' ),
			'bgColor'     => array( 'type' => 'string', 'default' => 'secondary' ),
		),
		'render_callback' => 'prospero_render_cta_block',
	) );
	
	// Testimonial Single Block
	register_block_type( 'prospero/testimonial-single', array(
		'attributes'      => array(
			'testimonialId' => array( 'type' => 'number', 'default' => 0 ),
			'showImage'     => array( 'type' => 'boolean', 'default' => true ),
		),
		'render_callback' => 'prospero_render_testimonial_single_block',
	) );
	
	// Testimonials List Block
	register_block_type( 'prospero/testimonials-list', array(
		'attributes'      => array(
			'ids'      => array( 'type' => 'array', 'default' => array() ),
			'category' => array( 'type' => 'string', 'default' => '' ),
			'count'    => array( 'type' => 'number', 'default' => 3 ),
			'orderby'  => array( 'type' => 'string', 'default' => 'date' ),
			'columns'  => array( 'type' => 'number', 'default' => 1 ),
			'slider'   => array( 'type' => 'boolean', 'default' => false ),
		),
		'render_callback' => 'prospero_render_testimonials_list_block',
	) );
	
	// Team Member Block
	register_block_type( 'prospero/team-member', array(
		'attributes'      => array(
			'memberId' => array( 'type' => 'number', 'default' => 0 ),
			'layout'   => array( 'type' => 'string', 'default' => 'card' ),
		),
		'render_callback' => 'prospero_render_team_member_block',
	) );
	
	// Member Content Block (role-based visibility with InnerBlocks)
	register_block_type( 'prospero/member-content', array(
		'attributes'      => array(
			'requiredRole'      => array( 'type' => 'string', 'default' => 'subscriber' ),
			'loginMessage'      => array( 'type' => 'string', 'default' => '' ),
			'showFallbackForm'  => array( 'type' => 'boolean', 'default' => false ),
			'fallbackShortcode' => array( 'type' => 'string', 'default' => '' ),
		),
		'render_callback' => 'prospero_render_member_content_block',
	) );
	
	// Login Form Block
	register_block_type( 'prospero/login-form', array(
		'render_callback' => 'prospero_render_login_form_block',
	) );
	
	// Register Form Block
	register_block_type( 'prospero/register-form', array(
		'render_callback' => 'prospero_render_register_form_block',
	) );
	
	// Forgot Password Form Block
	register_block_type( 'prospero/forgot-password-form', array(
		'render_callback' => 'prospero_render_forgot_password_form_block',
	) );
	
	// My Account Block
	register_block_type( 'prospero/my-account', array(
		'render_callback' => 'prospero_render_my_account_block',
	) );
	
	// FAQ Single Block
	register_block_type( 'prospero/faq-single', array(
		'attributes'      => array(
			'faqId'     => array( 'type' => 'number', 'default' => 0 ),
			'showTitle' => array( 'type' => 'boolean', 'default' => true ),
		),
		'render_callback' => 'prospero_render_faq_single_block',
	) );
	
	// FAQ List Block
	register_block_type( 'prospero/faq-list', array(
		'attributes'      => array(
			'category'  => array( 'type' => 'string', 'default' => '' ),
			'count'     => array( 'type' => 'number', 'default' => -1 ),
			'orderby'   => array( 'type' => 'string', 'default' => 'menu_order' ),
			'accordion' => array( 'type' => 'boolean', 'default' => true ),
		),
		'render_callback' => 'prospero_render_faq_list_block',
	) );
	
	// Partners List Block
	register_block_type( 'prospero/partners-list', array(
		'attributes'      => array(
			'ids'      => array( 'type' => 'array', 'default' => array() ),
			'category' => array( 'type' => 'string', 'default' => '' ),
			'count'    => array( 'type' => 'number', 'default' => -1 ),
			'orderby'  => array( 'type' => 'string', 'default' => 'menu_order' ),
			'columns'  => array( 'type' => 'number', 'default' => 4 ),
			'slider'   => array( 'type' => 'boolean', 'default' => false ),
		),
		'render_callback' => 'prospero_render_partners_list_block',
	) );
	
	// Team List Block
	register_block_type( 'prospero/team-list', array(
		'attributes'      => array(
			'ids'        => array( 'type' => 'array', 'default' => array() ),
			'category'   => array( 'type' => 'string', 'default' => '' ),
			'count'      => array( 'type' => 'number', 'default' => -1 ),
			'orderby'    => array( 'type' => 'string', 'default' => 'menu_order' ),
			'layout'     => array( 'type' => 'string', 'default' => 'columns' ),
			'columns'    => array( 'type' => 'number', 'default' => 3 ),
			'imageStyle' => array( 'type' => 'string', 'default' => 'square' ),
			'lightbox'   => array( 'type' => 'boolean', 'default' => false ),
			'slider'     => array( 'type' => 'boolean', 'default' => false ),
		),
		'render_callback' => 'prospero_render_team_list_block',
	) );
	
	// Projects List Block
	register_block_type( 'prospero/projects-list', array(
		'attributes'      => array(
			'category' => array( 'type' => 'string', 'default' => '' ),
			'count'    => array( 'type' => 'number', 'default' => -1 ),
			'orderby'  => array( 'type' => 'string', 'default' => 'date' ),
			'slider'   => array( 'type' => 'boolean', 'default' => false ),
		),
		'render_callback' => 'prospero_render_projects_list_block',
	) );
	
	// Affiliate Link Block
	register_block_type( 'prospero/affiliate-link', array(
		'attributes'      => array(
			'mode'        => array( 'type' => 'string', 'default' => 'button' ),
			'url'         => array( 'type' => 'string', 'default' => '' ),
			'linkText'    => array( 'type' => 'string', 'default' => '' ),
			'buttonStyle' => array( 'type' => 'string', 'default' => 'primary' ),
			'disclosure'  => array( 'type' => 'boolean', 'default' => true ),
			'newTab'      => array( 'type' => 'boolean', 'default' => true ),
			'nofollow'    => array( 'type' => 'boolean', 'default' => true ),
			'embedCode'   => array( 'type' => 'string', 'default' => '' ),
			'embedLabel'  => array( 'type' => 'string', 'default' => '' ),
		),
		'render_callback' => 'prospero_render_affiliate_link_block',
	) );
	
	// Partner Single Block
	register_block_type( 'prospero/partner-single', array(
		'attributes'      => array(
			'partnerId' => array( 'type' => 'number', 'default' => 0 ),
			'showLogo'  => array( 'type' => 'boolean', 'default' => true ),
		),
		'render_callback' => 'prospero_render_partner_single_block',
	) );
	
	// Project Single Block
	register_block_type( 'prospero/project-single', array(
		'attributes'      => array(
			'projectId'    => array( 'type' => 'number', 'default' => 0 ),
			'showFeatured' => array( 'type' => 'boolean', 'default' => true ),
		),
		'render_callback' => 'prospero_render_project_single_block',
	) );
}
add_action( 'init', 'prospero_register_custom_blocks' );

/**
 * Render Call to Action Block
 */
function prospero_render_cta_block( $attributes ) {
	$heading      = ! empty( $attributes['heading'] ) ? $attributes['heading'] : '';
	$content      = ! empty( $attributes['content'] ) ? $attributes['content'] : '';
	$button_text  = ! empty( $attributes['buttonText'] ) ? $attributes['buttonText'] : '';
	$button_url   = ! empty( $attributes['buttonUrl'] ) ? $attributes['buttonUrl'] : '';
	$button_style = ! empty( $attributes['buttonStyle'] ) ? $attributes['buttonStyle'] : 'primary';
	$layout       = ! empty( $attributes['layout'] ) ? $attributes['layout'] : 'center';
	$image_id     = ! empty( $attributes['imageId'] ) ? absint( $attributes['imageId'] ) : 0;
	$image_url    = ! empty( $attributes['imageUrl'] ) ? $attributes['imageUrl'] : '';
	$bg_image_url = ! empty( $attributes['bgImageUrl'] ) ? $attributes['bgImageUrl'] : '';
	$bg_color     = ! empty( $attributes['bgColor'] ) ? $attributes['bgColor'] : 'secondary';
	
	// Build wrapper classes
	$wrapper_classes = array( 'prospero-cta', 'prospero-cta-' . esc_attr( $layout ) );
	if ( $bg_color ) {
		$wrapper_classes[] = 'has-' . esc_attr( $bg_color ) . '-background-color';
	}
	if ( $bg_image_url ) {
		$wrapper_classes[] = 'has-background-image';
	}
	if ( $image_url && $layout !== 'center' ) {
		$wrapper_classes[] = 'has-image';
	}
	
	// Build inline styles
	$styles = array();
	if ( $bg_image_url ) {
		$styles[] = 'background-image: url(' . esc_url( $bg_image_url ) . ')';
	}
	$style_attr = ! empty( $styles ) ? 'style="' . esc_attr( implode( ';', $styles ) ) . '"' : '';
	
	// Determine text color based on background for accessibility
	$text_color_class = '';
	if ( in_array( $bg_color, array( 'primary', 'secondary', 'tertiary' ), true ) ) {
		$text_color_class = 'has-light-text';
	}
	
	ob_start();
	?>
	<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" <?php echo $style_attr; ?>>
		<?php if ( $bg_image_url ) : ?>
			<div class="prospero-cta-overlay"></div>
		<?php endif; ?>
		
		<?php if ( $image_url && $layout !== 'center' ) : ?>
			<div class="prospero-cta-image">
				<img src="<?php echo esc_url( $image_url ); ?>" alt="" loading="lazy" />
			</div>
		<?php endif; ?>
		
		<div class="prospero-cta-content <?php echo esc_attr( $text_color_class ); ?>">
			<?php if ( $image_url && $layout === 'center' ) : ?>
				<div class="prospero-cta-image prospero-cta-image-center">
					<img src="<?php echo esc_url( $image_url ); ?>" alt="" loading="lazy" />
				</div>
			<?php endif; ?>
			
			<?php if ( $heading ) : ?>
				<h2 class="prospero-cta-heading"><?php echo esc_html( $heading ); ?></h2>
			<?php endif; ?>
			
			<?php if ( $content ) : ?>
				<div class="prospero-cta-text">
					<?php echo wp_kses_post( wpautop( $content ) ); ?>
				</div>
			<?php endif; ?>
			
			<?php if ( $button_url && $button_text ) : ?>
				<div class="prospero-cta-button">
					<a href="<?php echo esc_url( $button_url ); ?>" class="button button-<?php echo esc_attr( $button_style ); ?>">
						<?php echo esc_html( $button_text ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render Testimonial Single Block
 */
function prospero_render_testimonial_single_block( $attributes ) {
	$testimonial_id = isset( $attributes['testimonialId'] ) ? absint( $attributes['testimonialId'] ) : 0;
	$show_image = isset( $attributes['showImage'] ) ? (bool) $attributes['showImage'] : true;
	
	if ( ! $testimonial_id ) {
		return '<p>' . esc_html__( 'Please select a testimonial.', 'prospero-theme' ) . '</p>';
	}
	
	$testimonial = get_post( $testimonial_id );
	
	if ( ! $testimonial || $testimonial->post_type !== 'testimonial' ) {
		return '';
	}
	
	ob_start();
	?>
	<div class="prospero-testimonial-block">
		<?php if ( $show_image && has_post_thumbnail( $testimonial_id ) ) : ?>
			<div class="testimonial-image">
				<?php echo get_the_post_thumbnail( $testimonial_id, 'thumbnail', array( 'class' => 'testimonial-thumbnail' ) ); ?>
			</div>
		<?php endif; ?>
		<div class="testimonial-content">
			<blockquote class="testimonial-text">
				<?php echo wp_kses_post( apply_filters( 'the_content', $testimonial->post_content ) ); ?>
			</blockquote>
			<cite class="testimonial-author"><?php echo esc_html( $testimonial->post_title ); ?></cite>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render Testimonials List Block
 */
function prospero_render_testimonials_list_block( $attributes ) {
	$ids      = isset( $attributes['ids'] ) && is_array( $attributes['ids'] ) ? array_map( 'absint', $attributes['ids'] ) : array();
	$category = isset( $attributes['category'] ) ? sanitize_text_field( $attributes['category'] ) : '';
	$count    = isset( $attributes['count'] ) ? intval( $attributes['count'] ) : 3;
	$orderby  = isset( $attributes['orderby'] ) ? sanitize_text_field( $attributes['orderby'] ) : 'date';
	$columns  = isset( $attributes['columns'] ) ? absint( $attributes['columns'] ) : 1;
	$slider   = isset( $attributes['slider'] ) && $attributes['slider'] ? 'yes' : 'no';
	
	// Use the shortcode function
	return prospero_testimonials_shortcode( array(
		'ids'      => ! empty( $ids ) ? implode( ',', $ids ) : '',
		'category' => $category,
		'count'    => $count,
		'orderby'  => $orderby,
		'order'    => 'DESC',
		'columns'  => $columns,
		'slider'   => $slider,
	) );
}

/**
 * Render Team Member Block
 */
function prospero_render_team_member_block( $attributes ) {
	$member_id = isset( $attributes['memberId'] ) ? absint( $attributes['memberId'] ) : 0;
	$layout = isset( $attributes['layout'] ) ? sanitize_text_field( $attributes['layout'] ) : 'card';
	
	if ( ! $member_id ) {
		return '<p>' . esc_html__( 'Please select a team member.', 'prospero-theme' ) . '</p>';
	}
	
	$member = get_post( $member_id );
	
	if ( ! $member || $member->post_type !== 'team' ) {
		return '';
	}
	
	ob_start();
	?>
	<div class="prospero-team-member-block prospero-team-member-<?php echo esc_attr( $layout ); ?>">
		<?php if ( has_post_thumbnail( $member_id ) ) : ?>
			<div class="team-member-image">
				<a href="<?php echo esc_url( get_permalink( $member_id ) ); ?>">
					<?php echo get_the_post_thumbnail( $member_id, 'medium', array( 'class' => 'team-member-thumbnail' ) ); ?>
				</a>
			</div>
		<?php endif; ?>
		<div class="team-member-content">
			<h3 class="team-member-name">
				<a href="<?php echo esc_url( get_permalink( $member_id ) ); ?>"><?php echo esc_html( $member->post_title ); ?></a>
			</h3>
			<?php if ( $layout === 'card' ) : ?>
				<div class="team-member-excerpt">
					<?php echo wp_kses_post( apply_filters( 'the_excerpt', get_the_excerpt( $member_id ) ) ); ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render Member Content Block (role-based visibility with InnerBlocks)
 */
function prospero_render_member_content_block( $attributes, $content ) {
	$required_role      = isset( $attributes['requiredRole'] ) ? sanitize_text_field( $attributes['requiredRole'] ) : 'subscriber';
	$login_message      = isset( $attributes['loginMessage'] ) ? $attributes['loginMessage'] : '';
	$show_fallback_form = isset( $attributes['showFallbackForm'] ) ? (bool) $attributes['showFallbackForm'] : false;
	$fallback_shortcode = isset( $attributes['fallbackShortcode'] ) ? $attributes['fallbackShortcode'] : '';
	
	// Default login message
	if ( empty( $login_message ) ) {
		$login_message = esc_html__( 'Please log in to view this content.', 'prospero-theme' );
	}
	
	// Check if user is logged in
	if ( ! is_user_logged_in() ) {
		ob_start();
		?>
		<div class="prospero-member-content-login">
			<p><?php echo esc_html( $login_message ); ?></p>
			<?php
			if ( $show_fallback_form && ! empty( $fallback_shortcode ) ) {
				// Process the shortcode
				echo '<div class="prospero-member-content-form">';
				echo do_shortcode( wp_kses_post( $fallback_shortcode ) );
				echo '</div>';
			}
			?>
		</div>
		<?php
		return ob_get_clean();
	}
	
	// Check if user has required role
	$user = wp_get_current_user();
	$role_hierarchy = array(
		'subscriber'    => 1,
		'contributor'   => 2,
		'author'        => 3,
		'editor'        => 4,
		'administrator' => 5,
	);
	
	$required_level = isset( $role_hierarchy[ $required_role ] ) ? $role_hierarchy[ $required_role ] : 1;
	$user_level = 0;
	
	foreach ( $user->roles as $role ) {
		if ( isset( $role_hierarchy[ $role ] ) && $role_hierarchy[ $role ] > $user_level ) {
			$user_level = $role_hierarchy[ $role ];
		}
	}
	
	if ( $user_level < $required_level ) {
		return '<div class="prospero-member-content-restricted"><p>' . esc_html__( 'You do not have permission to view this content.', 'prospero-theme' ) . '</p></div>';
	}
	
	// User has access, show the InnerBlocks content
	return '<div class="prospero-member-content">' . $content . '</div>';
}

/**
 * Enqueue block editor assets
 */
function prospero_enqueue_block_editor_assets() {
	// Enqueue editor styles
	wp_enqueue_style( 'prospero-editor-style', PROSPERO_THEME_URI . '/assets/css/editor-style.css', array(), PROSPERO_VERSION );
	
	// Enqueue block editor script
	wp_enqueue_script(
		'prospero-blocks-editor',
		PROSPERO_THEME_URI . '/assets/js/blocks-editor.js',
		array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	// Pass data to JavaScript
	wp_localize_script( 'prospero-blocks-editor', 'prosperoBlocks', array(
		'testimonials'           => prospero_get_testimonials_for_editor(),
		'testimonialCategories'  => prospero_get_testimonial_categories_for_editor(),
		'teamMembers'            => prospero_get_team_members_for_editor(),
		'teamCategories'         => prospero_get_team_categories_for_editor(),
		'faqs'                   => prospero_get_faqs_for_editor(),
		'faqCategories'          => prospero_get_faq_categories_for_editor(),
		'partners'               => prospero_get_partners_for_editor(),
		'partnerCategories'      => prospero_get_partner_categories_for_editor(),
		'projects'               => prospero_get_projects_for_editor(),
		'projectTags'            => prospero_get_project_tags_for_editor(),
	) );
	
	// Enqueue frontend login blocks
	wp_enqueue_script(
		'prospero-login-form-block',
		PROSPERO_THEME_URI . '/blocks/login-form.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-register-form-block',
		PROSPERO_THEME_URI . '/blocks/register-form.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-forgot-password-form-block',
		PROSPERO_THEME_URI . '/blocks/forgot-password-form.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-my-account-block',
		PROSPERO_THEME_URI . '/blocks/my-account.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-faq-single-block',
		PROSPERO_THEME_URI . '/blocks/faq-single.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'prospero-blocks-editor' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-faq-list-block',
		PROSPERO_THEME_URI . '/blocks/faq-list.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-partners-list-block',
		PROSPERO_THEME_URI . '/blocks/partners-list.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'prospero-blocks-editor' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-team-list-block',
		PROSPERO_THEME_URI . '/blocks/team-list.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'prospero-blocks-editor' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-projects-list-block',
		PROSPERO_THEME_URI . '/blocks/projects-list.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-affiliate-link-block',
		PROSPERO_THEME_URI . '/blocks/affiliate-link.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-partner-single-block',
		PROSPERO_THEME_URI . '/blocks/partner-single.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'prospero-blocks-editor' ),
		PROSPERO_VERSION,
		true
	);
	
	wp_enqueue_script(
		'prospero-project-single-block',
		PROSPERO_THEME_URI . '/blocks/project-single.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'prospero-blocks-editor' ),
		PROSPERO_VERSION,
		true
	);
	
	// Text Content Block
	wp_enqueue_script(
		'prospero-text-content-block',
		PROSPERO_THEME_URI . '/blocks/text-content.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	// CTA Block
	wp_enqueue_script(
		'prospero-cta-block',
		PROSPERO_THEME_URI . '/blocks/cta.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	// Member Content Block
	wp_enqueue_script(
		'prospero-member-content-block',
		PROSPERO_THEME_URI . '/blocks/member-content.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n' ),
		PROSPERO_VERSION,
		true
	);
	
	// Testimonials List Block
	wp_enqueue_script(
		'prospero-testimonials-list-block',
		PROSPERO_THEME_URI . '/blocks/testimonials-list.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'prospero-blocks-editor' ),
		PROSPERO_VERSION,
		true
	);
	
	// Testimonial Single Block
	wp_enqueue_script(
		'prospero-testimonial-single-block',
		PROSPERO_THEME_URI . '/blocks/testimonial-single.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'prospero-blocks-editor' ),
		PROSPERO_VERSION,
		true
	);
	
	// Team Member Block
	wp_enqueue_script(
		'prospero-team-member-block',
		PROSPERO_THEME_URI . '/blocks/team-member.js',
		array( 'wp-blocks', 'wp-element', 'wp-block-editor', 'wp-components', 'wp-i18n', 'prospero-blocks-editor' ),
		PROSPERO_VERSION,
		true
	);
}
add_action( 'enqueue_block_editor_assets', 'prospero_enqueue_block_editor_assets' );

/**
 * Render FAQ Single Block
 */
function prospero_render_faq_single_block( $attributes ) {
	$faq_id = isset( $attributes['faqId'] ) ? absint( $attributes['faqId'] ) : 0;
	$show_title = isset( $attributes['showTitle'] ) ? (bool) $attributes['showTitle'] : true;
	
	if ( ! $faq_id ) {
		return '<p>' . esc_html__( 'Please select an FAQ.', 'prospero-theme' ) . '</p>';
	}
	
	$faq = get_post( $faq_id );
	
	if ( ! $faq || $faq->post_type !== 'faq' ) {
		return '';
	}
	
	ob_start();
	?>
	<div class="prospero-faq-single" itemscope itemtype="https://schema.org/Question">
		<?php if ( $show_title ) : ?>
			<h3 class="faq-question" itemprop="name"><?php echo esc_html( $faq->post_title ); ?></h3>
		<?php endif; ?>
		<div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
			<div itemprop="text">
				<?php echo wp_kses_post( apply_filters( 'the_content', $faq->post_content ) ); ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render FAQ List Block
 */
function prospero_render_faq_list_block( $attributes ) {
	$category = isset( $attributes['category'] ) ? sanitize_text_field( $attributes['category'] ) : '';
	$count = isset( $attributes['count'] ) ? intval( $attributes['count'] ) : -1;
	$orderby = isset( $attributes['orderby'] ) ? sanitize_text_field( $attributes['orderby'] ) : 'menu_order';
	$accordion = isset( $attributes['accordion'] ) && $attributes['accordion'] ? true : false;
	
	$args = array(
		'post_type'      => 'faq',
		'posts_per_page' => $count,
		'orderby'        => $orderby,
		'order'          => 'ASC',
		'post_status'    => 'publish',
	);
	
	if ( ! empty( $category ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'faq_category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}
	
	$faqs = get_posts( $args );
	
	if ( empty( $faqs ) ) {
		return '<p>' . esc_html__( 'No FAQs found.', 'prospero-theme' ) . '</p>';
	}
	
	ob_start();
	?>
	<div class="prospero-faq-list<?php echo $accordion ? ' faq-accordion' : ''; ?>" itemscope itemtype="https://schema.org/FAQPage">
		<?php foreach ( $faqs as $faq ) : ?>
			<div class="faq-item<?php echo $accordion ? ' faq-accordion-item' : ''; ?>" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
				<?php if ( $accordion ) : ?>
					<button class="faq-question" aria-expanded="false" itemprop="name">
						<?php echo esc_html( $faq->post_title ); ?>
						<span class="faq-toggle" aria-hidden="true">+</span>
					</button>
					<div class="faq-answer" hidden itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<?php echo wp_kses_post( apply_filters( 'the_content', $faq->post_content ) ); ?>
						</div>
					</div>
				<?php else : ?>
					<h3 class="faq-question" itemprop="name"><?php echo esc_html( $faq->post_title ); ?></h3>
					<div class="faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
						<div itemprop="text">
							<?php echo wp_kses_post( apply_filters( 'the_content', $faq->post_content ) ); ?>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render Partners List Block
 */
function prospero_render_partners_list_block( $attributes ) {
	$ids      = isset( $attributes['ids'] ) && is_array( $attributes['ids'] ) ? array_map( 'absint', $attributes['ids'] ) : array();
	$category = isset( $attributes['category'] ) ? sanitize_text_field( $attributes['category'] ) : '';
	$count    = isset( $attributes['count'] ) ? intval( $attributes['count'] ) : -1;
	$orderby  = isset( $attributes['orderby'] ) ? sanitize_text_field( $attributes['orderby'] ) : 'menu_order';
	$columns  = isset( $attributes['columns'] ) ? absint( $attributes['columns'] ) : 4;
	$slider   = isset( $attributes['slider'] ) && $attributes['slider'] ? 'yes' : 'no';
	
	// Use the shortcode function
	return prospero_partners_shortcode( array(
		'ids'      => ! empty( $ids ) ? implode( ',', $ids ) : '',
		'category' => $category,
		'count'    => $count,
		'orderby'  => $orderby,
		'order'    => 'ASC',
		'columns'  => $columns,
		'slider'   => $slider,
	) );
}

/**
 * Render Team List Block
 */
function prospero_render_team_list_block( $attributes ) {
	$ids        = isset( $attributes['ids'] ) && is_array( $attributes['ids'] ) ? array_map( 'absint', $attributes['ids'] ) : array();
	$category   = isset( $attributes['category'] ) ? sanitize_text_field( $attributes['category'] ) : '';
	$count      = isset( $attributes['count'] ) ? intval( $attributes['count'] ) : -1;
	$orderby    = isset( $attributes['orderby'] ) ? sanitize_text_field( $attributes['orderby'] ) : 'menu_order';
	$layout     = isset( $attributes['layout'] ) ? sanitize_text_field( $attributes['layout'] ) : 'columns';
	$columns    = isset( $attributes['columns'] ) ? absint( $attributes['columns'] ) : 3;
	$imageStyle = isset( $attributes['imageStyle'] ) ? sanitize_text_field( $attributes['imageStyle'] ) : 'square';
	$lightbox   = isset( $attributes['lightbox'] ) && $attributes['lightbox'] ? 'yes' : 'no';
	$slider     = isset( $attributes['slider'] ) && $attributes['slider'] ? 'yes' : 'no';
	
	// Use the shortcode function
	return prospero_team_shortcode( array(
		'ids'         => ! empty( $ids ) ? implode( ',', $ids ) : '',
		'category'    => $category,
		'count'       => $count,
		'orderby'     => $orderby,
		'order'       => 'ASC',
		'layout'      => $layout,
		'columns'     => $columns,
		'image_style' => $imageStyle,
		'lightbox'    => $lightbox,
		'slider'      => $slider,
	) );
}

/**
 * Render Projects List Block
 */
function prospero_render_projects_list_block( $attributes ) {
	$category = isset( $attributes['category'] ) ? sanitize_text_field( $attributes['category'] ) : '';
	$count = isset( $attributes['count'] ) ? intval( $attributes['count'] ) : -1;
	$orderby = isset( $attributes['orderby'] ) ? sanitize_text_field( $attributes['orderby'] ) : 'date';
	$slider = isset( $attributes['slider'] ) && $attributes['slider'] ? 'yes' : 'no';
	
	// Use the shortcode function
	return prospero_projects_shortcode( array(
		'category' => $category,
		'count'    => $count,
		'orderby'  => $orderby,
		'order'    => 'DESC',
		'slider'   => $slider,
	) );
}

/**
 * Render Affiliate Link Block
 */
function prospero_render_affiliate_link_block( $attributes ) {
	$mode         = isset( $attributes['mode'] ) ? sanitize_text_field( $attributes['mode'] ) : 'button';
	$disclosure   = isset( $attributes['disclosure'] ) && $attributes['disclosure'] ? true : false;
	
	ob_start();
	?>
	<div class="prospero-affiliate-link prospero-affiliate-<?php echo esc_attr( $mode ); ?>">
		<?php if ( $disclosure ) :
			$disclosure_text = get_theme_mod( 'prospero_product_placement_text', '' );
			if ( ! empty( $disclosure_text ) ) :
		?>
			<div class="affiliate-disclosure">
				<?php echo wp_kses_post( wpautop( $disclosure_text ) ); ?>
			</div>
		<?php
			endif;
		endif;
		
		if ( $mode === 'embed' ) :
			// Embed mode: output the embed code
			$embed_code = isset( $attributes['embedCode'] ) ? $attributes['embedCode'] : '';
			if ( ! empty( $embed_code ) ) :
		?>
			<div class="affiliate-embed">
				<?php
				// Allow scripts, iframes and common embed elements
				$allowed_html = array(
					'script' => array(
						'src'     => true,
						'async'   => true,
						'defer'   => true,
						'type'    => true,
						'charset' => true,
						'id'      => true,
						'class'   => true,
					),
					'iframe' => array(
						'src'             => true,
						'width'           => true,
						'height'          => true,
						'frameborder'     => true,
						'marginwidth'     => true,
						'marginheight'    => true,
						'scrolling'       => true,
						'style'           => true,
						'class'           => true,
						'id'              => true,
						'sandbox'         => true,
						'allow'           => true,
						'allowfullscreen' => true,
						'loading'         => true,
						'title'           => true,
					),
					'div' => array(
						'class' => true,
						'id'    => true,
						'style' => true,
						'data-*' => true,
					),
					'a' => array(
						'href'   => true,
						'target' => true,
						'rel'    => true,
						'class'  => true,
						'id'     => true,
					),
					'img' => array(
						'src'    => true,
						'alt'    => true,
						'width'  => true,
						'height' => true,
						'class'  => true,
						'border' => true,
						'loading' => true,
					),
					'span' => array(
						'class' => true,
						'id'    => true,
						'style' => true,
					),
				);
				echo wp_kses( $embed_code, $allowed_html );
				?>
			</div>
		<?php
			else :
				echo '<p class="prospero-notice">' . esc_html__( 'No embed code configured.', 'prospero-theme' ) . '</p>';
			endif;
		else :
			// Button mode
			$url          = isset( $attributes['url'] ) ? esc_url( $attributes['url'] ) : '';
			$link_text    = isset( $attributes['linkText'] ) ? $attributes['linkText'] : '';
			$button_style = isset( $attributes['buttonStyle'] ) ? sanitize_text_field( $attributes['buttonStyle'] ) : 'primary';
			$new_tab      = isset( $attributes['newTab'] ) && $attributes['newTab'] ? true : false;
			$nofollow     = isset( $attributes['nofollow'] ) && $attributes['nofollow'] ? true : false;
			
			if ( $url && $link_text ) :
				$rel_attrs = array();
				if ( $nofollow ) {
					$rel_attrs[] = 'nofollow';
				}
				if ( $new_tab ) {
					$rel_attrs[] = 'noopener';
				}
				$rel = ! empty( $rel_attrs ) ? ' rel="' . esc_attr( implode( ' ', $rel_attrs ) ) . '"' : '';
				$target = $new_tab ? ' target="_blank"' : '';
		?>
			<div class="affiliate-link-button">
				<a href="<?php echo $url; ?>" class="button button-<?php echo esc_attr( $button_style ); ?>"<?php echo $rel . $target; ?>>
					<?php echo esc_html( $link_text ); ?>
				</a>
			</div>
		<?php
			else :
				echo '<p class="prospero-notice">' . esc_html__( 'Please configure the affiliate link.', 'prospero-theme' ) . '</p>';
			endif;
		endif;
		?>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render Partner Single Block
 */
function prospero_render_partner_single_block( $attributes ) {
	$partner_id = isset( $attributes['partnerId'] ) ? absint( $attributes['partnerId'] ) : 0;
	$show_logo = isset( $attributes['showLogo'] ) ? (bool) $attributes['showLogo'] : true;
	
	if ( ! $partner_id ) {
		return '<p>' . esc_html__( 'Please select a partner.', 'prospero-theme' ) . '</p>';
	}
	
	$partner = get_post( $partner_id );
	
	if ( ! $partner || $partner->post_type !== 'partner' ) {
		return '';
	}
	
	ob_start();
	?>
	<div class="prospero-partner-single">
		<?php if ( $show_logo && has_post_thumbnail( $partner_id ) ) : ?>
			<div class="partner-logo">
				<?php echo get_the_post_thumbnail( $partner_id, 'medium', array( 'class' => 'partner-thumbnail' ) ); ?>
			</div>
		<?php endif; ?>
		<div class="partner-content">
			<h3 class="partner-name"><?php echo esc_html( $partner->post_title ); ?></h3>
			<div class="partner-description">
				<?php echo wp_kses_post( apply_filters( 'the_content', $partner->post_content ) ); ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Render Project Single Block
 */
function prospero_render_project_single_block( $attributes ) {
	$project_id = isset( $attributes['projectId'] ) ? absint( $attributes['projectId'] ) : 0;
	$show_featured = isset( $attributes['showFeatured'] ) ? (bool) $attributes['showFeatured'] : true;
	
	if ( ! $project_id ) {
		return '<p>' . esc_html__( 'Please select a project.', 'prospero-theme' ) . '</p>';
	}
	
	$project = get_post( $project_id );
	
	if ( ! $project || $project->post_type !== 'project' ) {
		return '';
	}
	
	ob_start();
	?>
	<div class="prospero-project-single">
		<?php if ( $show_featured && has_post_thumbnail( $project_id ) ) : ?>
			<div class="project-featured-image">
				<?php echo get_the_post_thumbnail( $project_id, 'large', array( 'class' => 'project-thumbnail' ) ); ?>
			</div>
		<?php endif; ?>
		<div class="project-content">
			<h3 class="project-title"><?php echo esc_html( $project->post_title ); ?></h3>
			<div class="project-description">
				<?php echo wp_kses_post( apply_filters( 'the_content', $project->post_content ) ); ?>
			</div>
		</div>
	</div>
	<?php
	return ob_get_clean();
}

/**
 * Get testimonials for block editor
 */
function prospero_get_testimonials_for_editor() {
	$testimonials = get_posts( array(
		'post_type'      => 'testimonial',
		'posts_per_page' => 100,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
	
	$options = array();
	foreach ( $testimonials as $testimonial ) {
		$options[] = array(
			'label' => $testimonial->post_title,
			'value' => $testimonial->ID,
		);
	}
	
	return $options;
}

/**
 * Get FAQs for block editor
 */
function prospero_get_faqs_for_editor() {
	$faqs = get_posts( array(
		'post_type'      => 'faq',
		'posts_per_page' => 100,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
	
	$options = array();
	foreach ( $faqs as $faq ) {
		$options[] = array(
			'label' => $faq->post_title,
			'value' => $faq->ID,
		);
	}
	
	return $options;
}

/**
 * Render Login Form Block
 */
function prospero_render_login_form_block() {
	return prospero_login_form_shortcode();
}

/**
 * Render Register Form Block
 */
function prospero_render_register_form_block() {
	return prospero_register_form_shortcode();
}

/**
 * Render Forgot Password Form Block
 */
function prospero_render_forgot_password_form_block() {
	return prospero_forgot_password_form_shortcode();
}

/**
 * Render My Account Block
 */
function prospero_render_my_account_block() {
	return prospero_my_account_shortcode();
}

/**
 * Get team members for block editor
 */
function prospero_get_team_members_for_editor() {
	$members = get_posts( array(
		'post_type'      => 'team',
		'posts_per_page' => 100,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
	
	$options = array();
	foreach ( $members as $member ) {
		$options[] = array(
			'label' => $member->post_title,
			'value' => $member->ID,
		);
	}
	
	return $options;
}

/**
 * Get partners for block editor
 */
function prospero_get_partners_for_editor() {
	$partners = get_posts( array(
		'post_type'      => 'partner',
		'posts_per_page' => 100,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
	
	$options = array();
	foreach ( $partners as $partner ) {
		$options[] = array(
			'label' => $partner->post_title,
			'value' => $partner->ID,
		);
	}
	
	return $options;
}

/**
 * Get projects for block editor
 */
function prospero_get_projects_for_editor() {
	$projects = get_posts( array(
		'post_type'      => 'project',
		'posts_per_page' => 100,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	) );
	
	$options = array();
	foreach ( $projects as $project ) {
		$options[] = array(
			'label' => $project->post_title,
			'value' => $project->ID,
		);
	}
	
	return $options;
}

/**
 * Get testimonial categories for block editor
 */
function prospero_get_testimonial_categories_for_editor() {
	$terms = get_terms( array(
		'taxonomy'   => 'testimonial_category',
		'hide_empty' => false,
	) );
	
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	
	$options = array();
	foreach ( $terms as $term ) {
		$options[] = array(
			'label' => $term->name,
			'value' => $term->slug,
		);
	}
	
	return $options;
}

/**
 * Get team categories for block editor
 */
function prospero_get_team_categories_for_editor() {
	$terms = get_terms( array(
		'taxonomy'   => 'team_category',
		'hide_empty' => false,
	) );
	
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	
	$options = array();
	foreach ( $terms as $term ) {
		$options[] = array(
			'label' => $term->name,
			'value' => $term->slug,
		);
	}
	
	return $options;
}

/**
 * Get FAQ categories for block editor
 */
function prospero_get_faq_categories_for_editor() {
	$terms = get_terms( array(
		'taxonomy'   => 'faq_category',
		'hide_empty' => false,
	) );
	
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	
	$options = array();
	foreach ( $terms as $term ) {
		$options[] = array(
			'label' => $term->name,
			'value' => $term->slug,
		);
	}
	
	return $options;
}

/**
 * Get partner categories for block editor
 */
function prospero_get_partner_categories_for_editor() {
	$terms = get_terms( array(
		'taxonomy'   => 'partner_category',
		'hide_empty' => false,
	) );
	
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	
	$options = array();
	foreach ( $terms as $term ) {
		$options[] = array(
			'label' => $term->name,
			'value' => $term->slug,
		);
	}
	
	return $options;
}

/**
 * Get project tags for block editor
 */
function prospero_get_project_tags_for_editor() {
	$terms = get_terms( array(
		'taxonomy'   => 'project_tag',
		'hide_empty' => false,
	) );
	
	if ( is_wp_error( $terms ) ) {
		return array();
	}
	
	$options = array();
	foreach ( $terms as $term ) {
		$options[] = array(
			'label' => $term->name,
			'value' => $term->slug,
		);
	}
	
	return $options;
}
