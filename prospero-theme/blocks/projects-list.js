( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var RangeControl = components.RangeControl;
	var ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'prospero/projects-list', {
		title: __( 'Projects List', 'prospero-theme' ),
		description: __( 'Display a list of projects', 'prospero-theme' ),
		icon: 'portfolio',
		category: 'prospero',
		keywords: [ __( 'projects', 'prospero-theme' ), __( 'portfolio', 'prospero-theme' ), __( 'work', 'prospero-theme' ) ],
		attributes: {
			tag: {
				type: 'string',
				default: ''
			},
			count: {
				type: 'number',
				default: -1
			},
			orderby: {
				type: 'string',
				default: 'date'
			},
			slider: {
				type: 'boolean',
				default: false
			}
		},
		supports: {
			html: false,
		},
		edit: function( props ) {
			var tag = props.attributes.tag;
			var count = props.attributes.count;
			var orderby = props.attributes.orderby;
			var slider = props.attributes.slider;

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'Projects List Settings', 'prospero-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Tag', 'prospero-theme' ),
							value: tag,
							options: [
								{ label: __( 'All Tags', 'prospero-theme' ), value: '' },
							],
							onChange: function( value ) {
								props.setAttributes( { tag: value } );
							},
							help: __( 'Filter by project tag', 'prospero-theme' )
						} ),
						el( RangeControl, {
							label: __( 'Number of Projects', 'prospero-theme' ),
							value: count,
							onChange: function( value ) {
								props.setAttributes( { count: value } );
							},
							min: -1,
							max: 50,
							help: __( 'Set to -1 to show all projects', 'prospero-theme' )
						} ),
						el( SelectControl, {
							label: __( 'Order By', 'prospero-theme' ),
							value: orderby,
							options: [
								{ label: __( 'Date', 'prospero-theme' ), value: 'date' },
								{ label: __( 'Custom Order', 'prospero-theme' ), value: 'menu_order' },
								{ label: __( 'Title', 'prospero-theme' ), value: 'title' },
							],
							onChange: function( value ) {
								props.setAttributes( { orderby: value } );
							}
						} ),
						el( ToggleControl, {
							label: __( 'Display as Slider', 'prospero-theme' ),
							checked: slider,
							onChange: function( value ) {
								props.setAttributes( { slider: value } );
							},
							help: __( 'Enable Flickity slider mode', 'prospero-theme' )
						} )
					)
				),
				el( 'div', { className: props.className, key: 'editor' },
					el( 'div', { 
						style: { 
							padding: '20px', 
							backgroundColor: '#f0f0f1', 
							border: '1px solid #ddd',
							borderRadius: '4px'
						} 
					},
						el( 'p', { style: { margin: 0, fontWeight: 'bold' } }, 
							__( '📁 Projects List', 'prospero-theme' )
						),
						el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, 
							slider ? 
								__( 'Slider mode enabled', 'prospero-theme' ) :
								__( 'Grid display', 'prospero-theme' )
						),
						el( 'p', { style: { margin: '5px 0 0 0', fontSize: '14px', color: '#666' } }, 
							count === -1 ? 
								__( 'Showing all projects', 'prospero-theme' ) :
								__( 'Showing ' + count + ' projects', 'prospero-theme' )
						)
					)
				)
			];
		},
		save: function() {
			return null;
		},
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components
);
