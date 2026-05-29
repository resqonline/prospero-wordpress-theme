/**
 * Widget Column Span - Block Editor Support
 *
 * Adds a "Column Span (Pre-Footer)" SelectControl to every block in the
 * block-based widgets editor, mirroring the classic-widget Column Span
 * field registered via `in_widget_form` in inc/sidebars.php.
 *
 * The `prosperoColumnSpan` attribute is read server-side by
 * `prospero_widget_column_span_class()` (also in inc/sidebars.php) via
 * `parse_blocks()`, which injects a `.span-N` class on the widget's
 * outer `before_widget` wrapper. Only the pre-footer sidebar's CSS grid
 * reacts to that class, so the control is harmless in other sidebars.
 *
 * The script is only enqueued on the widgets screen (`$screen->id ===
 * 'widgets'`), so the InspectorControls panel is rendered for every
 * block in the widgets editor. An older version of this file tried to
 * limit the panel to top-level blocks via `getBlockRootClientId()`,
 * but in the block-based widgets editor each sidebar is itself a
 * `core/widget-area` container block - so the blocks a user drops
 * into a sidebar are NOT at the editor root and the check silently
 * hid the panel on every block. We now trust the enqueue scoping and
 * the label copy ("Column Span (Pre-Footer)" + the help line) to
 * signal when the control actually takes effect.
 *
 * @package Prospero
 * @since   1.1.0
 */
( function( wp ) {
	'use strict';

	if (
		! wp ||
		! wp.hooks ||
		! wp.blockEditor ||
		! wp.components ||
		! wp.compose ||
		! wp.element ||
		! wp.i18n
	) {
		return;
	}

	var __                          = wp.i18n.__;
	var el                          = wp.element.createElement;
	var Fragment                    = wp.element.Fragment;
	var createHigherOrderComponent  = wp.compose.createHigherOrderComponent;
	var InspectorControls           = wp.blockEditor.InspectorControls;
	var PanelBody                   = wp.components.PanelBody;
	var SelectControl               = wp.components.SelectControl;
	var addFilter                   = wp.hooks.addFilter;

	/**
	 * Step 1: Register the `prosperoColumnSpan` attribute on every block
	 * at registration time. Guard against re-registration so a block
	 * that already declares the attribute (now or in the future) is
	 * left untouched.
	 */
	addFilter(
		'blocks.registerBlockType',
		'prospero/widget-column-span/attribute',
		function( settings ) {
			if ( ! settings.attributes || settings.attributes.prosperoColumnSpan ) {
				return settings;
			}
			settings.attributes = Object.assign( {}, settings.attributes, {
				prosperoColumnSpan: {
					type: 'string',
					default: '1'
				}
			} );
			return settings;
		}
	);

	/**
	 * Step 2: Inject an InspectorControls panel into every block's edit UI.
	 * Shown for all blocks in the widgets editor (see module header for
	 * why we don't try to restrict to top-level blocks here).
	 */
	var withColumnSpanControl = createHigherOrderComponent( function( BlockEdit ) {
		return function( props ) {
			if ( ! props ) {
				return el( BlockEdit, props );
			}

			var currentSpan = props.attributes && props.attributes.prosperoColumnSpan
				? props.attributes.prosperoColumnSpan
				: '1';

			return el( Fragment, {},
				el( BlockEdit, props ),
				el( InspectorControls, {},
					el( PanelBody, {
						title: __( 'Pre-Footer Layout', 'prospero-theme' ),
						initialOpen: false
					},
						el( SelectControl, {
							label: __( 'Column Span (Pre-Footer)', 'prospero-theme' ),
							value: currentSpan,
							options: [
								{ label: __( '1 Column', 'prospero-theme' ), value: '1' },
								{ label: __( '2 Columns', 'prospero-theme' ), value: '2' },
								{ label: __( '3 Columns (Full Width)', 'prospero-theme' ), value: '3' }
							],
							onChange: function( value ) {
								props.setAttributes( { prosperoColumnSpan: value } );
							},
							help: __( 'This setting only applies when the block is placed in the Pre-Footer widget area.', 'prospero-theme' )
						} )
					)
				)
			);
		};
	}, 'withColumnSpanControl' );

	addFilter(
		'editor.BlockEdit',
		'prospero/widget-column-span/ui',
		withColumnSpanControl
	);
} )( window.wp );
