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
 * Supports both classic widgets (column span stored under the
 * `prospero_column_span` instance key, set via `in_widget_form`) and
 * block widgets (WP_Widget_Block, where the block HTML is stored under
 * the `content` key and the top-level block's `prosperoColumnSpan`
 * attribute is parsed out via `parse_blocks()`). The resulting
 * `.span-1` / `.span-2` / `.span-3` class is injected on the outer
 * `before_widget` wrapper so the Pre-Footer CSS grid resizes the
 * widget regardless of whether it's classic or block-based.
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
	
	$widget_obj  = $wp_registered_widgets[ $widget_id ];
	$column_span = '1';
	
	if ( isset( $widget_obj['callback'][0] ) && is_object( $widget_obj['callback'][0] ) ) {
		$widget_instance = $widget_obj['callback'][0];
		$option_name     = isset( $widget_instance->option_name ) ? $widget_instance->option_name : '';
		$widget_opt      = $option_name ? get_option( $option_name ) : array();
		$widget_num      = isset( $widget_obj['params'][0]['number'] ) ? $widget_obj['params'][0]['number'] : null;
		
		if ( null !== $widget_num && is_array( $widget_opt ) && isset( $widget_opt[ $widget_num ] ) ) {
			$instance = $widget_opt[ $widget_num ];
			
			if ( class_exists( 'WP_Widget_Block' ) && $widget_instance instanceof WP_Widget_Block && ! empty( $instance['content'] ) ) {
				// Block widget: parse the stored block HTML and read the
				// first non-empty top-level block's `prosperoColumnSpan`
				// attribute. Nested blocks are intentionally ignored -
				// only the outermost block sits on the sidebar grid.
				$parsed = parse_blocks( $instance['content'] );
				foreach ( $parsed as $block ) {
					if ( empty( $block['blockName'] ) ) {
						continue;
					}
					if ( ! empty( $block['attrs']['prosperoColumnSpan'] ) ) {
						$column_span = (string) $block['attrs']['prosperoColumnSpan'];
					}
					break;
				}
			} elseif ( isset( $instance['prospero_column_span'] ) ) {
				// Classic widget path
				$column_span = (string) $instance['prospero_column_span'];
			}
		}
	}
	
	// Validate against allow-list
	if ( ! in_array( $column_span, array( '1', '2', '3' ), true ) ) {
		$column_span = '1';
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
 * Enqueue the Column Span UI on the block-based widgets screen.
 *
 * The script:
 * - registers a `prosperoColumnSpan` attribute on every block,
 * - adds an InspectorControls panel ("Pre-Footer Layout" -> "Column
 *   Span (Pre-Footer)") to the edit UI of top-level blocks only.
 *
 * The attribute is read server-side by `prospero_widget_column_span_class()`
 * above, so no save-time class injection is required on the JS side.
 */
function prospero_enqueue_widget_block_editor_assets() {
	$screen = get_current_screen();
	
	// Only load on widgets screen
	if ( ! $screen || 'widgets' !== $screen->id ) {
		return;
	}
	
	wp_enqueue_script(
		'prospero-widget-column-span',
		PROSPERO_THEME_URI . '/assets/js/widget-column-span.js',
		array(
			'wp-blocks',
			'wp-element',
			'wp-compose',
			'wp-block-editor',
			'wp-components',
			'wp-hooks',
			'wp-i18n',
		),
		PROSPERO_VERSION,
		true
	);
	
	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'prospero-widget-column-span', 'prospero-theme' );
	}
}
add_action( 'enqueue_block_editor_assets', 'prospero_enqueue_widget_block_editor_assets' );
