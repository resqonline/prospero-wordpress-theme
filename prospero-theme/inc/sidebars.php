<?php
/**
 * Sidebar Registration and Widget Column Span Functionality
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register widget areas
 */
function prospero_register_sidebars() {
	// Pre-footer widget area
	register_sidebar( array(
		'name'          => esc_html__( 'Pre-Footer', 'prospero-theme' ),
		'id'            => 'pre-footer',
		'description'   => esc_html__( 'Widget area displayed above the footer. Uses a 3-column grid layout.', 'prospero-theme' ),
		'before_widget' => '<div id="%1$s" class="pre-footer-widget widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
}
add_action( 'widgets_init', 'prospero_register_sidebars' );

/**
 * Add column span field to classic widgets
 *
 * @param WP_Widget $widget  The widget instance.
 * @param null      $return  Return null if new fields are added.
 * @param array     $instance The widget settings.
 */
function prospero_widget_column_span_field( $widget, $return, $instance ) {
	// Only show for pre-footer sidebar widgets
	$widget_id = $widget->id;
	
	// Get current column span value
	$column_span = isset( $instance['prospero_column_span'] ) ? $instance['prospero_column_span'] : '1';
	?>
	<p>
		<label for="<?php echo esc_attr( $widget->get_field_id( 'prospero_column_span' ) ); ?>">
			<?php esc_html_e( 'Column Span (Pre-Footer)', 'prospero-theme' ); ?>
		</label>
		<select 
			id="<?php echo esc_attr( $widget->get_field_id( 'prospero_column_span' ) ); ?>" 
			name="<?php echo esc_attr( $widget->get_field_name( 'prospero_column_span' ) ); ?>" 
			class="widefat"
		>
			<option value="1" <?php selected( $column_span, '1' ); ?>>
				<?php esc_html_e( '1 Column', 'prospero-theme' ); ?>
			</option>
			<option value="2" <?php selected( $column_span, '2' ); ?>>
				<?php esc_html_e( '2 Columns', 'prospero-theme' ); ?>
			</option>
			<option value="3" <?php selected( $column_span, '3' ); ?>>
				<?php esc_html_e( '3 Columns (Full Width)', 'prospero-theme' ); ?>
			</option>
		</select>
		<span class="description" style="display: block; margin-top: 5px; font-size: 12px; color: #666;">
			<?php esc_html_e( 'This setting only applies when the widget is placed in the Pre-Footer area.', 'prospero-theme' ); ?>
		</span>
	</p>
	<?php
	return $return;
}
add_action( 'in_widget_form', 'prospero_widget_column_span_field', 10, 3 );

/**
 * Save the column span field value
 *
 * @param array     $instance     The widget instance settings.
 * @param array     $new_instance The new settings.
 * @param array     $old_instance The old settings.
 * @param WP_Widget $widget       The widget instance.
 * @return array Modified instance.
 */
function prospero_save_widget_column_span( $instance, $new_instance, $old_instance, $widget ) {
	$allowed_spans = array( '1', '2', '3' );
	
	if ( isset( $new_instance['prospero_column_span'] ) && in_array( $new_instance['prospero_column_span'], $allowed_spans, true ) ) {
		$instance['prospero_column_span'] = $new_instance['prospero_column_span'];
	} else {
		$instance['prospero_column_span'] = '1';
	}
	
	return $instance;
}
add_filter( 'widget_update_callback', 'prospero_save_widget_column_span', 10, 4 );

/**
 * Add column span class to widget wrapper
 *
 * @param array $params Widget parameters.
 * @return array Modified parameters.
 */
function prospero_widget_column_span_class( $params ) {
	// Only modify pre-footer widgets
	if ( 'pre-footer' !== $params[0]['id'] ) {
		return $params;
	}
	
	global $wp_registered_widgets;
	
	$widget_id = $params[0]['widget_id'];
	
	if ( ! isset( $wp_registered_widgets[ $widget_id ] ) ) {
		return $params;
	}
	
	$widget_obj = $wp_registered_widgets[ $widget_id ];
	
	// Get widget settings
	$widget_opt = get_option( $widget_obj['callback'][0]->option_name );
	$widget_num = $widget_obj['params'][0]['number'];
	
	$column_span = '1';
	if ( isset( $widget_opt[ $widget_num ]['prospero_column_span'] ) ) {
		$column_span = $widget_opt[ $widget_num ]['prospero_column_span'];
	}
	
	// Add span class to before_widget
	$params[0]['before_widget'] = str_replace(
		'class="pre-footer-widget',
		'class="pre-footer-widget span-' . esc_attr( $column_span ),
		$params[0]['before_widget']
	);
	
	return $params;
}
add_filter( 'dynamic_sidebar_params', 'prospero_widget_column_span_class' );

/**
 * Render the pre-footer widget area
 */
function prospero_render_pre_footer() {
	if ( ! is_active_sidebar( 'pre-footer' ) ) {
		return;
	}
	?>
	<aside class="pre-footer-area" role="complementary" aria-label="<?php esc_attr_e( 'Pre-Footer Widgets', 'prospero-theme' ); ?>">
		<div class="container">
			<div class="pre-footer-widgets">
				<?php dynamic_sidebar( 'pre-footer' ); ?>
			</div>
		</div>
	</aside>
	<?php
}

/**
 * Add block widget column span support via JavaScript in the block editor
 */
function prospero_enqueue_widget_block_editor_assets() {
	$screen = get_current_screen();
	
	// Only load on widgets screen
	if ( ! $screen || 'widgets' !== $screen->id ) {
		return;
	}
	
	wp_add_inline_script(
		'wp-blocks',
		"
		// Add column span attribute to all widgets when in pre-footer sidebar
		wp.hooks.addFilter(
			'blocks.registerBlockType',
			'prospero/widget-column-span',
			function( settings, name ) {
				// Only modify widget blocks
				if ( name.indexOf( 'core/' ) !== 0 ) {
					return settings;
				}
				
				// Add column span attribute
				settings.attributes = Object.assign( {}, settings.attributes, {
					prosperoColumnSpan: {
						type: 'string',
						default: '1'
					}
				});
				
				return settings;
			}
		);
		"
	);
}
add_action( 'enqueue_block_editor_assets', 'prospero_enqueue_widget_block_editor_assets' );
