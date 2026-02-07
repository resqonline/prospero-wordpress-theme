<?php
/**
 * Theme Customizer
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom Customizer Control: Heading
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	class Prospero_Heading_Control extends WP_Customize_Control {
		public $type = 'heading';
		
		public function render_content() {
			?>
			<label>
				<span class="customize-control-title" style="font-size: 14px; font-weight: 600; display: block; margin-top: 20px; margin-bottom: 10px; padding-top: 15px; border-top: 1px solid #ddd;"><?php echo esc_html( $this->label ); ?></span>
			</label>
			<?php
		}
	}
	
	/**
	 * Custom Customizer Control: Radio Image
	 */
	class Prospero_Radio_Image_Control extends WP_Customize_Control {
		public $type = 'radio-image';
		
		public function render_content() {
			if ( empty( $this->choices ) ) {
				return;
			}
			?>
			<label>
				<?php if ( ! empty( $this->label ) ) : ?>
					<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
				<?php endif; ?>
				<?php if ( ! empty( $this->description ) ) : ?>
					<span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
				<?php endif; ?>
			</label>
			
			<div class="radio-image-control" style="display: flex; gap: 8px; flex-wrap: wrap;">
				<?php foreach ( $this->choices as $value => $args ) : ?>
					<label style="cursor: pointer; position: relative;">
						<input 
							type="radio" 
							name="<?php echo esc_attr( $this->id ); ?>" 
							value="<?php echo esc_attr( $value ); ?>" 
							<?php $this->link(); ?>
							<?php checked( $this->value(), $value ); ?>
							style="position: absolute; opacity: 0; width: 0; height: 0;"
						/>
						<div style="
							padding: 8px;
							border: 2px solid #ddd;
							border-radius: 4px;
							transition: all 0.2s;
							background: #fff;
							text-align: center;
							min-width: 70px;
							<?php echo $this->value() === $value ? 'border-color: #0073aa; box-shadow: 0 0 0 1px #0073aa;' : ''; ?>
						">
							<div style="<?php echo esc_attr( $args['svg'] ); ?> margin-bottom: 4px;"></div>
							<span style="font-size: 11px; display: block; color: #555;"><?php echo esc_html( $args['label'] ); ?></span>
						</div>
					</label>
				<?php endforeach; ?>
			</div>
			<?php
		}
		
		public function enqueue() {
			// Add hover styles via inline script
			wp_add_inline_script( 'customize-controls', "
				jQuery(document).ready(function($) {
					$('.radio-image-control label').hover(
						function() { $(this).find('div').first().css('border-color', '#0073aa'); },
						function() { 
							if (!$(this).find('input').is(':checked')) {
								$(this).find('div').first().css('border-color', '#ddd');
							}
						}
					);
					$('.radio-image-control input').on('change', function() {
						$(this).closest('.radio-image-control').find('div').css({
							'border-color': '#ddd',
							'box-shadow': 'none'
						});
						$(this).siblings('div').css({
							'border-color': '#0073aa',
							'box-shadow': '0 0 0 1px #0073aa'
						});
					});
				});
			" );
		}
	}
}

/**
 * Add color palette to Customizer color pickers
 * Note: theme.json provides presets for Gutenberg, but Customizer needs separate configuration
 */
function prospero_customizer_color_palettes() {
	// Get theme colors
	$primary = get_theme_mod( 'prospero_primary_color', '#007bff' );
	$secondary = get_theme_mod( 'prospero_secondary_color', '#6c757d' );
	$tertiary = get_theme_mod( 'prospero_tertiary_color', '#28a745' );
	$text = get_theme_mod( 'prospero_text_color', '#333333' );
	$dark_text = get_theme_mod( 'prospero_dark_text_color', '#f7f7f7' );
	$highlight = get_theme_mod( 'prospero_highlight_color', '#ffc107' );
	$bg = get_theme_mod( 'prospero_background_color', '#ffffff' );
	$dark_bg = get_theme_mod( 'prospero_dark_background_color', '#2b2a33' );
	
	$palettes = array( $primary, $secondary, $tertiary, $highlight, $text, $dark_text, $bg, $dark_bg );
	
	// Output inline script to update iris after controls are ready
	?>
	<script type="text/javascript">
		(function( $, api ) {
			'use strict';
			
			var prosperoPalette = <?php echo wp_json_encode( $palettes ); ?>;
			
			api.bind( 'ready', function() {
				// Update all existing color controls
				$( '.customize-control-color input.color-picker-hex' ).each( function() {
					var $this = $( this );
					
					// Check if iris is initialized
					if ( $this.data( 'a8cIris' ) ) {
						// Update the iris instance with our palette
						$this.iris( 'option', 'palettes', prosperoPalette );
					}
				});
			});
			
		})( jQuery, wp.customize );
	</script>
	<?php
}
add_action( 'customize_controls_print_footer_scripts', 'prospero_customizer_color_palettes' );

/**
 * Register customizer settings
 */
function prospero_customize_register( $wp_customize ) {
	
	// ===== SITE IDENTITY - DARK MODE LOGO =====
	// Add dark logo setting to existing Site Identity section
	$wp_customize->add_setting( 'prospero_dark_logo', array(
		'default'           => '',
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'prospero_dark_logo', array(
		'label'       => esc_html__( 'Dark Mode Logo', 'prospero-theme' ),
		'description' => esc_html__( 'Upload a logo for dark mode. Falls back to logo-dark.svg if not set.', 'prospero-theme' ),
		'section'     => 'title_tagline',
		'mime_type'   => 'image',
		'priority'    => 9, // Right after the default custom_logo
	) ) );
	
	// ===== COLORS SECTION =====
	$wp_customize->add_section( 'prospero_colors', array(
		'title'    => esc_html__( 'Theme Colors', 'prospero-theme' ),
		'priority' => 30,
	) );
	
	// Primary Color
	$wp_customize->add_setting( 'prospero_primary_color', array(
		'default'           => '#007bff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_primary_color', array(
		'label'   => esc_html__( 'Primary Color', 'prospero-theme' ),
		'section' => 'prospero_colors',
	) ) );
	
	// Secondary Color
	$wp_customize->add_setting( 'prospero_secondary_color', array(
		'default'           => '#6c757d',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_secondary_color', array(
		'label'   => esc_html__( 'Secondary Color', 'prospero-theme' ),
		'section' => 'prospero_colors',
	) ) );
	
	// Tertiary Color
	$wp_customize->add_setting( 'prospero_tertiary_color', array(
		'default'           => '#28a745',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_tertiary_color', array(
		'label'   => esc_html__( 'Tertiary Color', 'prospero-theme' ),
		'section' => 'prospero_colors',
	) ) );
	
	// Text Color
	$wp_customize->add_setting( 'prospero_text_color', array(
		'default'           => '#333333',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_text_color', array(
		'label'   => esc_html__( 'Text Color', 'prospero-theme' ),
		'section' => 'prospero_colors',
	) ) );
	
	// Dark Mode Text Color
	$wp_customize->add_setting( 'prospero_dark_text_color', array(
		'default'           => '#f7f7f7',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_dark_text_color', array(
		'label'   => esc_html__( 'Dark Mode Text Color', 'prospero-theme' ),
		'section' => 'prospero_colors',
	) ) );
	
	// Highlight Color
	$wp_customize->add_setting( 'prospero_highlight_color', array(
		'default'           => '#ffc107',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_highlight_color', array(
		'label'   => esc_html__( 'Highlight Color', 'prospero-theme' ),
		'section' => 'prospero_colors',
	) ) );
	
	// Background Color
	$wp_customize->add_setting( 'prospero_background_color', array(
		'default'           => '#ffffff',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_background_color', array(
		'label'   => esc_html__( 'Background Color', 'prospero-theme' ),
		'section' => 'prospero_colors',
	) ) );
	
	// Dark Mode Background Color
	$wp_customize->add_setting( 'prospero_dark_background_color', array(
		'default'           => '#2b2a33',
		'sanitize_callback' => 'sanitize_hex_color',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_dark_background_color', array(
		'label'   => esc_html__( 'Dark Mode Background Color', 'prospero-theme' ),
		'section' => 'prospero_colors',
	) ) );
	
	// ===== DARK MODE SECTION =====
	$wp_customize->add_section( 'prospero_dark_mode', array(
		'title'    => esc_html__( 'Dark Mode Settings', 'prospero-theme' ),
		'priority' => 31,
	) );
	
	// Enable Dark Mode
	$wp_customize->add_setting( 'prospero_enable_dark_mode', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_enable_dark_mode', array(
		'label'   => esc_html__( 'Enable Dark Mode', 'prospero-theme' ),
		'section' => 'prospero_dark_mode',
		'type'    => 'checkbox',
	) );
	
	// Default Mode
	$wp_customize->add_setting( 'prospero_default_mode', array(
		'default'           => 'light',
		'sanitize_callback' => 'prospero_sanitize_default_mode',
	) );
	$wp_customize->add_control( 'prospero_default_mode', array(
		'label'   => esc_html__( 'Default Mode', 'prospero-theme' ),
		'section' => 'prospero_dark_mode',
		'type'    => 'select',
		'choices' => array(
			'light' => esc_html__( 'Light', 'prospero-theme' ),
			'dark'  => esc_html__( 'Dark', 'prospero-theme' ),
			'auto'  => esc_html__( 'Auto (System Preference)', 'prospero-theme' ),
		),
	) );
	
	// Show Dark Mode Toggle
	$wp_customize->add_setting( 'prospero_show_dark_mode_toggle', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_show_dark_mode_toggle', array(
		'label'       => esc_html__( 'Show Dark Mode Toggle in Header', 'prospero-theme' ),
		'description' => esc_html__( 'Allow visitors to switch between light and dark mode', 'prospero-theme' ),
		'section'     => 'prospero_dark_mode',
		'type'        => 'checkbox',
	) );
	
	// ===== BUTTON STYLES SECTION =====
	$wp_customize->add_section( 'prospero_buttons', array(
		'title'    => esc_html__( 'Button Styles', 'prospero-theme' ),
		'priority' => 32,
	) );
	
	// --- PRIMARY BUTTON ---
	$wp_customize->add_setting( 'prospero_primary_button_heading', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Prospero_Heading_Control( $wp_customize, 'prospero_primary_button_heading', array(
		'label'   => esc_html__( 'Primary Button', 'prospero-theme' ),
		'section' => 'prospero_buttons',
	) ) );
	
	// Primary Button Style
	$wp_customize->add_setting( 'prospero_primary_btn_style', array(
		'default'           => 'flat',
		'sanitize_callback' => 'prospero_sanitize_button_style',
	) );
	$wp_customize->add_control( new Prospero_Radio_Image_Control( $wp_customize, 'prospero_primary_btn_style', array(
		'label'   => esc_html__( 'Style', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'choices' => array(
			'flat'    => array(
				'label' => 'Flat',
				'svg'   => 'width: 50px; height: 30px; background: #0073aa;',
			),
			'outline' => array(
				'label' => 'Outline',
				'svg'   => 'width: 50px; height: 30px; background: transparent; border: 2px solid #0073aa;',
			),
		),
	) ) );
	
	// Primary Button Border Radius
	$wp_customize->add_setting( 'prospero_primary_btn_radius', array(
		'default'           => '4px',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'prospero_primary_btn_radius', array(
		'label'       => esc_html__( 'Border Radius', 'prospero-theme' ),
		'description' => esc_html__( 'E.g., 0, 4px, 50px, 0.5em, 50%', 'prospero-theme' ),
		'section'     => 'prospero_buttons',
		'type'        => 'text',
	) );
	
	// Primary Button Background Color (with alpha/transparency support)
	$wp_customize->add_setting( 'prospero_primary_btn_bg', array(
		'default'           => 'rgba(0, 123, 255, 1)',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_primary_btn_bg', array(
		'label'   => esc_html__( 'Background Color', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'mode'    => 'rgba',
	) ) );
	
	// Primary Button Text Color
	$wp_customize->add_setting( 'prospero_primary_btn_text', array(
		'default'           => 'rgba(255, 255, 255, 1)',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_primary_btn_text', array(
		'label'   => esc_html__( 'Text Color', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'mode'    => 'rgba',
	) ) );
	
	// Primary Button Hover Background
	$wp_customize->add_setting( 'prospero_primary_btn_hover_bg', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_primary_btn_hover_bg', array(
		'label'       => esc_html__( 'Hover Background', 'prospero-theme' ),
		'description' => esc_html__( 'Leave empty for automatic darker shade', 'prospero-theme' ),
		'section'     => 'prospero_buttons',
		'mode'        => 'rgba',
	) ) );
	
	// Primary Button Hover Text Color
	$wp_customize->add_setting( 'prospero_primary_btn_hover_text', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_primary_btn_hover_text', array(
		'label'       => esc_html__( 'Hover Text Color', 'prospero-theme' ),
		'description' => esc_html__( 'Leave empty for automatic', 'prospero-theme' ),
		'section'     => 'prospero_buttons',
		'mode'        => 'rgba',
	) ) );
	
	// Primary Button Font Style
	$wp_customize->add_setting( 'prospero_primary_btn_font_style', array(
		'default'           => 'none',
		'sanitize_callback' => 'prospero_sanitize_button_font_style',
	) );
	$wp_customize->add_control( new Prospero_Radio_Image_Control( $wp_customize, 'prospero_primary_btn_font_style', array(
		'label'   => esc_html__( 'Font Style', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'choices' => array(
			'none'      => array(
				'label' => 'Normal',
				'svg'   => 'width: 50px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #555; font-weight: normal;',
			),
			'uppercase' => array(
				'label' => 'UPPERCASE',
				'svg'   => 'width: 50px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #555; font-weight: 600; letter-spacing: 0.5px;',
			),
		),
	) ) );
	
	// --- SECONDARY BUTTON ---
	$wp_customize->add_setting( 'prospero_secondary_button_heading', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Prospero_Heading_Control( $wp_customize, 'prospero_secondary_button_heading', array(
		'label'   => esc_html__( 'Secondary Button', 'prospero-theme' ),
		'section' => 'prospero_buttons',
	) ) );
	
	// Secondary Button Style
	$wp_customize->add_setting( 'prospero_secondary_btn_style', array(
		'default'           => 'outline',
		'sanitize_callback' => 'prospero_sanitize_button_style',
	) );
	$wp_customize->add_control( new Prospero_Radio_Image_Control( $wp_customize, 'prospero_secondary_btn_style', array(
		'label'   => esc_html__( 'Style', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'choices' => array(
			'flat'    => array( 'label' => 'Flat', 'svg' => 'width: 50px; height: 30px; background: #0073aa;' ),
			'outline' => array( 'label' => 'Outline', 'svg' => 'width: 50px; height: 30px; background: transparent; border: 2px solid #0073aa;' ),
		),
	) ) );
	
	// Secondary Button Border Radius
	$wp_customize->add_setting( 'prospero_secondary_btn_radius', array(
		'default'           => '4px',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'prospero_secondary_btn_radius', array(
		'label'       => esc_html__( 'Border Radius', 'prospero-theme' ),
		'description' => esc_html__( 'E.g., 0, 4px, 50px, 0.5em, 50%', 'prospero-theme' ),
		'section'     => 'prospero_buttons',
		'type'        => 'text',
	) );
	
	
	// Secondary Button Background Color (with alpha/transparency support)
	$wp_customize->add_setting( 'prospero_secondary_btn_bg', array(
		'default'           => 'rgba(108, 117, 125, 1)',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_secondary_btn_bg', array(
		'label'   => esc_html__( 'Background Color', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'mode'    => 'rgba',
	) ) );
	
	// Secondary Button Text Color
	$wp_customize->add_setting( 'prospero_secondary_btn_text', array(
		'default'           => 'rgba(255, 255, 255, 1)',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_secondary_btn_text', array(
		'label'   => esc_html__( 'Text Color', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'mode'    => 'rgba',
	) ) );
	
	// Secondary Button Hover Background
	$wp_customize->add_setting( 'prospero_secondary_btn_hover_bg', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_secondary_btn_hover_bg', array(
		'label'       => esc_html__( 'Hover Background', 'prospero-theme' ),
		'description' => esc_html__( 'Leave empty for automatic', 'prospero-theme' ),
		'section'     => 'prospero_buttons',
		'mode'        => 'rgba',
	) ) );
	
	// Secondary Button Hover Text Color
	$wp_customize->add_setting( 'prospero_secondary_btn_hover_text', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_secondary_btn_hover_text', array(
		'label'       => esc_html__( 'Hover Text Color', 'prospero-theme' ),
		'description' => esc_html__( 'Leave empty for automatic', 'prospero-theme' ),
		'section'     => 'prospero_buttons',
		'mode'        => 'rgba',
	) ) );
	
	// Secondary Button Font Style
	$wp_customize->add_setting( 'prospero_secondary_btn_font_style', array(
		'default'           => 'none',
		'sanitize_callback' => 'prospero_sanitize_button_font_style',
	) );
	$wp_customize->add_control( new Prospero_Radio_Image_Control( $wp_customize, 'prospero_secondary_btn_font_style', array(
		'label'   => esc_html__( 'Font Style', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'choices' => array(
			'none'      => array( 'label' => 'Normal', 'svg' => 'width: 50px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #555; font-weight: normal;' ),
			'uppercase' => array( 'label' => 'UPPERCASE', 'svg' => 'width: 50px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #555; font-weight: 600; letter-spacing: 0.5px;' ),
		),
	) ) );
	
	// --- TERTIARY BUTTON ---
	$wp_customize->add_setting( 'prospero_tertiary_button_heading', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Prospero_Heading_Control( $wp_customize, 'prospero_tertiary_button_heading', array(
		'label'   => esc_html__( 'Tertiary Button', 'prospero-theme' ),
		'section' => 'prospero_buttons',
	) ) );
	
	// Tertiary Button Style
	$wp_customize->add_setting( 'prospero_tertiary_btn_style', array(
		'default'           => 'flat',
		'sanitize_callback' => 'prospero_sanitize_button_style',
	) );
	$wp_customize->add_control( new Prospero_Radio_Image_Control( $wp_customize, 'prospero_tertiary_btn_style', array(
		'label'   => esc_html__( 'Style', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'choices' => array(
			'flat'    => array( 'label' => 'Flat', 'svg' => 'width: 50px; height: 30px; background: #0073aa;' ),
			'outline' => array( 'label' => 'Outline', 'svg' => 'width: 50px; height: 30px; background: transparent; border: 2px solid #0073aa;' ),
		),
	) ) );
	
	// Tertiary Button Border Radius
	$wp_customize->add_setting( 'prospero_tertiary_btn_radius', array(
		'default'           => '4px',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'prospero_tertiary_btn_radius', array(
		'label'       => esc_html__( 'Border Radius', 'prospero-theme' ),
		'description' => esc_html__( 'E.g., 0, 4px, 50px, 0.5em, 50%', 'prospero-theme' ),
		'section'     => 'prospero_buttons',
		'type'        => 'text',
	) );
	
	
	// Tertiary Button Background Color (with alpha/transparency support)
	$wp_customize->add_setting( 'prospero_tertiary_btn_bg', array(
		'default'           => 'rgba(40, 167, 69, 1)',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_tertiary_btn_bg', array(
		'label'   => esc_html__( 'Background Color', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'mode'    => 'rgba',
	) ) );
	
	// Tertiary Button Text Color
	$wp_customize->add_setting( 'prospero_tertiary_btn_text', array(
		'default'           => 'rgba(255, 255, 255, 1)',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_tertiary_btn_text', array(
		'label'   => esc_html__( 'Text Color', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'mode'    => 'rgba',
	) ) );
	
	// Tertiary Button Hover Background
	$wp_customize->add_setting( 'prospero_tertiary_btn_hover_bg', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_tertiary_btn_hover_bg', array(
		'label'       => esc_html__( 'Hover Background', 'prospero-theme' ),
		'description' => esc_html__( 'Leave empty for automatic', 'prospero-theme' ),
		'section'     => 'prospero_buttons',
		'mode'        => 'rgba',
	) ) );
	
	// Tertiary Button Hover Text Color
	$wp_customize->add_setting( 'prospero_tertiary_btn_hover_text', array(
		'default'           => '',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'prospero_tertiary_btn_hover_text', array(
		'label'       => esc_html__( 'Hover Text Color', 'prospero-theme' ),
		'description' => esc_html__( 'Leave empty for automatic', 'prospero-theme' ),
		'section'     => 'prospero_buttons',
		'mode'        => 'rgba',
	) ) );
	
	// Tertiary Button Font Style
	$wp_customize->add_setting( 'prospero_tertiary_btn_font_style', array(
		'default'           => 'none',
		'sanitize_callback' => 'prospero_sanitize_button_font_style',
	) );
	$wp_customize->add_control( new Prospero_Radio_Image_Control( $wp_customize, 'prospero_tertiary_btn_font_style', array(
		'label'   => esc_html__( 'Font Style', 'prospero-theme' ),
		'section' => 'prospero_buttons',
		'choices' => array(
			'none'      => array( 'label' => 'Normal', 'svg' => 'width: 50px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #555; font-weight: normal;' ),
			'uppercase' => array( 'label' => 'UPPERCASE', 'svg' => 'width: 50px; height: 30px; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #555; font-weight: 600; letter-spacing: 0.5px;' ),
		),
	) ) );
	
	// ===== HEADER & MENU SETTINGS SECTION =====
	$wp_customize->add_section( 'prospero_menu_settings', array(
		'title'    => esc_html__( 'Header & Menu', 'prospero-theme' ),
		'priority' => 33,
	) );

	// Header Layout Heading
	$wp_customize->add_setting( 'prospero_header_layout_heading', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Prospero_Heading_Control( $wp_customize, 'prospero_header_layout_heading', array(
		'label'   => esc_html__( 'Header Layout', 'prospero-theme' ),
		'section' => 'prospero_menu_settings',
	) ) );

	// Logo Position
	$wp_customize->add_setting( 'prospero_logo_position', array(
		'default'           => 'left',
		'sanitize_callback' => 'prospero_sanitize_header_position',
	) );
	$wp_customize->add_control( 'prospero_logo_position', array(
		'label'   => esc_html__( 'Logo Position', 'prospero-theme' ),
		'section' => 'prospero_menu_settings',
		'type'    => 'select',
		'choices' => array(
			'left'   => esc_html__( 'Left', 'prospero-theme' ),
			'center' => esc_html__( 'Center', 'prospero-theme' ),
			'right'  => esc_html__( 'Right', 'prospero-theme' ),
		),
	) );

	// Menu Position
	$wp_customize->add_setting( 'prospero_menu_position', array(
		'default'           => 'right',
		'sanitize_callback' => 'prospero_sanitize_header_position',
	) );
	$wp_customize->add_control( 'prospero_menu_position', array(
		'label'   => esc_html__( 'Menu Position (Desktop)', 'prospero-theme' ),
		'section' => 'prospero_menu_settings',
		'type'    => 'select',
		'choices' => array(
			'left'   => esc_html__( 'Left', 'prospero-theme' ),
			'center' => esc_html__( 'Center', 'prospero-theme' ),
			'right'  => esc_html__( 'Right', 'prospero-theme' ),
		),
	) );

	// Menu Options Heading
	$wp_customize->add_setting( 'prospero_menu_options_heading', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Prospero_Heading_Control( $wp_customize, 'prospero_menu_options_heading', array(
		'label'   => esc_html__( 'Menu Options', 'prospero-theme' ),
		'section' => 'prospero_menu_settings',
	) ) );

	// Sticky Menu
	$wp_customize->add_setting( 'prospero_sticky_menu', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_sticky_menu', array(
		'label'   => esc_html__( 'Enable Sticky Header', 'prospero-theme' ),
		'section' => 'prospero_menu_settings',
		'type'    => 'checkbox',
	) );

	// Hamburger Menu
	$wp_customize->add_setting( 'prospero_hamburger_menu', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_hamburger_menu', array(
		'label'       => esc_html__( 'Always Show Mobile Menu', 'prospero-theme' ),
		'description' => esc_html__( 'Display mobile menu on all screen sizes', 'prospero-theme' ),
		'section'     => 'prospero_menu_settings',
		'type'        => 'checkbox',
	) );
	
	// Header Search
	$wp_customize->add_setting( 'prospero_header_search', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_header_search', array(
		'label'       => esc_html__( 'Show Search in Header', 'prospero-theme' ),
		'description' => esc_html__( 'Display a search icon that opens a search overlay', 'prospero-theme' ),
		'section'     => 'prospero_menu_settings',
		'type'        => 'checkbox',
	) );
	
	// ===== TYPOGRAPHY SECTION =====
	$wp_customize->add_section( 'prospero_typography', array(
		'title'    => esc_html__( 'Typography', 'prospero-theme' ),
		'priority' => 34,
	) );
	
	// Heading Font
	$wp_customize->add_setting( 'prospero_heading_font', array(
		'default'           => 'system',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'prospero_heading_font', array(
		'label'       => esc_html__( 'Heading Font', 'prospero-theme' ),
		'description' => esc_html__( 'Enter a Google Font name (will be downloaded locally) or "system" for system fonts', 'prospero-theme' ),
		'section'     => 'prospero_typography',
		'type'        => 'text',
	) );
	
	// Body Font
	$wp_customize->add_setting( 'prospero_body_font', array(
		'default'           => 'system',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'prospero_body_font', array(
		'label'       => esc_html__( 'Body Font', 'prospero-theme' ),
		'description' => esc_html__( 'Enter a Google Font name (will be downloaded locally) or "system" for system fonts', 'prospero-theme' ),
		'section'     => 'prospero_typography',
		'type'        => 'text',
	) );
	
	// ===== CONTENT SETTINGS SECTION =====
	$wp_customize->add_section( 'prospero_content', array(
		'title'    => esc_html__( 'Content Settings', 'prospero-theme' ),
		'priority' => 35,
	) );
	
	// Max Content Width
	$wp_customize->add_setting( 'prospero_content_width', array(
		'default'           => '1200px',
		'sanitize_callback' => 'sanitize_text_field',
		'transport'         => 'refresh',
	) );
	$wp_customize->add_control( 'prospero_content_width', array(
		'label'       => esc_html__( 'Max Content Width', 'prospero-theme' ),
		'description' => esc_html__( 'E.g., 1200px, 80rem, 90vw', 'prospero-theme' ),
		'section'     => 'prospero_content',
		'type'        => 'text',
	) );
	
	// Product Placement Text
	$wp_customize->add_setting( 'prospero_product_placement_text', array(
		'default'           => '',
		'sanitize_callback' => 'wp_kses_post',
	) );
	$wp_customize->add_control( 'prospero_product_placement_text', array(
		'label'       => esc_html__( 'Product Placement Notice', 'prospero-theme' ),
		'description' => esc_html__( 'Text to display on posts with affiliate links or product placements', 'prospero-theme' ),
		'section'     => 'prospero_content',
		'type'        => 'textarea',
	) );
	
	// ===== BLOG LAYOUT SECTION =====
	$wp_customize->add_section( 'prospero_blog_layout', array(
		'title'    => esc_html__( 'Blog Layout', 'prospero-theme' ),
		'priority' => 35,
	) );
	
	// Blog Layout Style
	$wp_customize->add_setting( 'prospero_blog_layout', array(
		'default'           => 'grid',
		'sanitize_callback' => 'prospero_sanitize_blog_layout',
	) );
	$wp_customize->add_control( new Prospero_Radio_Image_Control( $wp_customize, 'prospero_blog_layout', array(
		'label'   => esc_html__( 'Blog Layout', 'prospero-theme' ),
		'section' => 'prospero_blog_layout',
		'choices' => array(
			'grid' => array(
				'label' => esc_html__( 'Grid', 'prospero-theme' ),
				'svg'   => 'width: 60px; height: 40px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 4px;',
			),
			'list' => array(
				'label' => esc_html__( 'List', 'prospero-theme' ),
				'svg'   => 'width: 60px; height: 40px; display: flex; flex-direction: column; gap: 4px;',
			),
		),
	) ) );
	
	// Blog Grid Columns
	$wp_customize->add_setting( 'prospero_blog_columns', array(
		'default'           => '3',
		'sanitize_callback' => 'prospero_sanitize_blog_columns',
	) );
	$wp_customize->add_control( 'prospero_blog_columns', array(
		'label'       => esc_html__( 'Grid Columns', 'prospero-theme' ),
		'description' => esc_html__( 'Number of columns in grid layout.', 'prospero-theme' ),
		'section'     => 'prospero_blog_layout',
		'type'        => 'select',
		'choices'     => array(
			'2' => esc_html__( '2 Columns', 'prospero-theme' ),
			'3' => esc_html__( '3 Columns', 'prospero-theme' ),
			'4' => esc_html__( '4 Columns', 'prospero-theme' ),
		),
	) );
	
	// Show Excerpt in Grid
	$wp_customize->add_setting( 'prospero_blog_grid_excerpt', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_blog_grid_excerpt', array(
		'label'       => esc_html__( 'Show Excerpt in Grid', 'prospero-theme' ),
		'description' => esc_html__( 'Display post excerpt in grid view.', 'prospero-theme' ),
		'section'     => 'prospero_blog_layout',
		'type'        => 'checkbox',
	) );
	
	// Single Post Heading
	$wp_customize->add_setting( 'prospero_single_post_heading', array(
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( new Prospero_Heading_Control( $wp_customize, 'prospero_single_post_heading', array(
		'label'   => esc_html__( 'Single Post', 'prospero-theme' ),
		'section' => 'prospero_blog_layout',
	) ) );
	
	// Featured Image Position
	$wp_customize->add_setting( 'prospero_single_featured_position', array(
		'default'           => 'below',
		'sanitize_callback' => 'prospero_sanitize_featured_position',
	) );
	$wp_customize->add_control( 'prospero_single_featured_position', array(
		'label'       => esc_html__( 'Featured Image Position', 'prospero-theme' ),
		'description' => esc_html__( 'Where to display the featured image on single posts.', 'prospero-theme' ),
		'section'     => 'prospero_blog_layout',
		'type'        => 'select',
		'choices'     => array(
			'above'  => esc_html__( 'Above title', 'prospero-theme' ),
			'below'  => esc_html__( 'Below title and meta', 'prospero-theme' ),
			'hidden' => esc_html__( 'Hidden', 'prospero-theme' ),
		),
	) );
	
	// ===== BREADCRUMBS SECTION =====
	$wp_customize->add_section( 'prospero_breadcrumbs', array(
		'title'       => esc_html__( 'Breadcrumbs', 'prospero-theme' ),
		'description' => prospero_breadcrumbs_customizer_description(),
		'priority'    => 36,
	) );
	
	// Enable Breadcrumbs
	$wp_customize->add_setting( 'prospero_enable_breadcrumbs', array(
		'default'           => true,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_enable_breadcrumbs', array(
		'label'       => esc_html__( 'Enable Breadcrumbs', 'prospero-theme' ),
		'description' => esc_html__( 'Display breadcrumb navigation on pages and posts.', 'prospero-theme' ),
		'section'     => 'prospero_breadcrumbs',
		'type'        => 'checkbox',
	) );
	
	// Breadcrumbs Home Text
	$wp_customize->add_setting( 'prospero_breadcrumbs_home_text', array(
		'default'           => __( 'Home', 'prospero-theme' ),
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'prospero_breadcrumbs_home_text', array(
		'label'       => esc_html__( 'Home Link Text', 'prospero-theme' ),
		'description' => esc_html__( 'Text for the home link in breadcrumbs.', 'prospero-theme' ),
		'section'     => 'prospero_breadcrumbs',
		'type'        => 'text',
	) );
	
	// Breadcrumbs Separator
	$wp_customize->add_setting( 'prospero_breadcrumbs_separator', array(
		'default'           => '/',
		'sanitize_callback' => 'sanitize_text_field',
	) );
	$wp_customize->add_control( 'prospero_breadcrumbs_separator', array(
		'label'       => esc_html__( 'Separator Character', 'prospero-theme' ),
		'description' => esc_html__( 'Character or symbol between breadcrumb items.', 'prospero-theme' ),
		'section'     => 'prospero_breadcrumbs',
		'type'        => 'text',
	) );
	
	// ===== FRONTEND LOGIN SECTION =====
	$wp_customize->add_section( 'prospero_frontend_login', array(
		'title'    => esc_html__( 'Frontend Login', 'prospero-theme' ),
		'priority' => 36,
	) );
	
	// Enable Frontend Login
	$wp_customize->add_setting( 'prospero_enable_frontend_login', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_enable_frontend_login', array(
		'label'       => esc_html__( 'Use Frontend Login/Profile Editing', 'prospero-theme' ),
		'description' => esc_html__( 'Creates frontend pages for login, registration, password reset, and account management. Pages will be created automatically when enabled.', 'prospero-theme' ),
		'section'     => 'prospero_frontend_login',
		'type'        => 'checkbox',
	) );
	
	// ===== POST TYPES SECTION =====
	$wp_customize->add_section( 'prospero_post_types', array(
		'title'    => esc_html__( 'Post Types', 'prospero-theme' ),
		'priority' => 37,
	) );
	
	// Enable Testimonials
	$wp_customize->add_setting( 'prospero_enable_testimonials', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_enable_testimonials', array(
		'label'   => esc_html__( 'Enable Testimonials Post Type', 'prospero-theme' ),
		'section' => 'prospero_post_types',
		'type'    => 'checkbox',
	) );
	
	// Enable Partners
	$wp_customize->add_setting( 'prospero_enable_partners', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_enable_partners', array(
		'label'   => esc_html__( 'Enable Partners Post Type', 'prospero-theme' ),
		'section' => 'prospero_post_types',
		'type'    => 'checkbox',
	) );
	
	// Enable Team
	$wp_customize->add_setting( 'prospero_enable_team', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_enable_team', array(
		'label'   => esc_html__( 'Enable Team Post Type', 'prospero-theme' ),
		'section' => 'prospero_post_types',
		'type'    => 'checkbox',
	) );
	
	// Team Page (for breadcrumbs)
	$wp_customize->add_setting( 'prospero_team_page', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'prospero_team_page', array(
		'label'       => esc_html__( 'Team Page', 'prospero-theme' ),
		'description' => esc_html__( 'Select the page that displays team members (used in breadcrumbs).', 'prospero-theme' ),
		'section'     => 'prospero_post_types',
		'type'        => 'dropdown-pages',
	) );
	
	// Enable Projects
	$wp_customize->add_setting( 'prospero_enable_projects', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_enable_projects', array(
		'label'   => esc_html__( 'Enable Projects Post Type', 'prospero-theme' ),
		'section' => 'prospero_post_types',
		'type'    => 'checkbox',
	) );
	
	// Projects Page (for breadcrumbs)
	$wp_customize->add_setting( 'prospero_projects_page', array(
		'default'           => 0,
		'sanitize_callback' => 'absint',
	) );
	$wp_customize->add_control( 'prospero_projects_page', array(
		'label'       => esc_html__( 'Projects Page', 'prospero-theme' ),
		'description' => esc_html__( 'Select the page that displays projects (used in breadcrumbs).', 'prospero-theme' ),
		'section'     => 'prospero_post_types',
		'type'        => 'dropdown-pages',
	) );
	
	// Enable FAQ
	$wp_customize->add_setting( 'prospero_enable_faq', array(
		'default'           => false,
		'sanitize_callback' => 'rest_sanitize_boolean',
	) );
	$wp_customize->add_control( 'prospero_enable_faq', array(
		'label'   => esc_html__( 'Enable FAQ Post Type', 'prospero-theme' ),
		'section' => 'prospero_post_types',
		'type'    => 'checkbox',
	) );
	
}
add_action( 'customize_register', 'prospero_customize_register' );

/**
 * Sanitize default mode setting
 */
function prospero_sanitize_default_mode( $input ) {
	$valid = array( 'light', 'dark', 'auto' );
	return in_array( $input, $valid, true ) ? $input : 'light';
}

/**
 * Sanitize button style setting
 */
function prospero_sanitize_button_style( $input ) {
	$valid = array( 'flat', 'outline' );
	return in_array( $input, $valid, true ) ? $input : 'flat';
}

/**
 * Sanitize button color setting
 */
function prospero_sanitize_button_color( $input ) {
	$valid = array( 'primary', 'secondary', 'tertiary' );
	return in_array( $input, $valid, true ) ? $input : 'primary';
}

/**
 * Sanitize button font style setting
 */
function prospero_sanitize_button_font_style( $input ) {
	$valid = array( 'none', 'uppercase' );
	return in_array( $input, $valid, true ) ? $input : 'none';
}

/**
 * Sanitize header position setting (logo/menu)
 */
function prospero_sanitize_header_position( $input ) {
	$valid = array( 'left', 'center', 'right' );
	return in_array( $input, $valid, true ) ? $input : 'left';
}

/**
 * Sanitize blog layout setting
 */
function prospero_sanitize_blog_layout( $input ) {
	$valid = array( 'grid', 'list' );
	return in_array( $input, $valid, true ) ? $input : 'grid';
}

/**
 * Sanitize blog columns setting
 */
function prospero_sanitize_blog_columns( $input ) {
	$valid = array( '2', '3', '4' );
	return in_array( $input, $valid, true ) ? $input : '3';
}

/**
 * Sanitize featured image position setting
 */
function prospero_sanitize_featured_position( $input ) {
	$valid = array( 'above', 'below', 'hidden' );
	return in_array( $input, $valid, true ) ? $input : 'below';
}
