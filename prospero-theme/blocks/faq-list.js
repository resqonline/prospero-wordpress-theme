( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var RangeControl = components.RangeControl;
	var ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'prospero/faq-list', {
		title: __( 'FAQ List', 'prospero-theme' ),
		description: __( 'Display a list of FAQs with accordion option', 'prospero-theme' ),
		icon: 'editor-help',
		category: 'prospero',
		keywords: [ __( 'faq', 'prospero-theme' ), __( 'questions', 'prospero-theme' ), __( 'accordion', 'prospero-theme' ) ],
		attributes: {
			category: {
				type: 'string',
				default: ''
			},
			count: {
				type: 'number',
				default: -1
			},
			orderby: {
				type: 'string',
				default: 'menu_order'
			},
			accordion: {
				type: 'boolean',
				default: true
			}
		},
		supports: {
			html: false,
		},
		edit: function( props ) {
			var category = props.attributes.category;
			var count = props.attributes.count;
			var orderby = props.attributes.orderby;
			var accordion = props.attributes.accordion;

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'FAQ List Settings', 'prospero-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Category', 'prospero-theme' ),
							value: category,
							options: [
								{ label: __( 'All Categories', 'prospero-theme' ), value: '' },
							],
							onChange: function( value ) {
								props.setAttributes( { category: value } );
							},
							help: __( 'Filter by FAQ category (requires categories to be created)', 'prospero-theme' )
						} ),
						el( RangeControl, {
							label: __( 'Number of FAQs', 'prospero-theme' ),
							value: count,
							onChange: function( value ) {
								props.setAttributes( { count: value } );
							},
							min: -1,
							max: 50,
							help: __( 'Set to -1 to show all FAQs', 'prospero-theme' )
						} ),
						el( SelectControl, {
							label: __( 'Order By', 'prospero-theme' ),
							value: orderby,
							options: [
								{ label: __( 'Custom Order', 'prospero-theme' ), value: 'menu_order' },
								{ label: __( 'Title', 'prospero-theme' ), value: 'title' },
								{ label: __( 'Date', 'prospero-theme' ), value: 'date' },
							],
							onChange: function( value ) {
								props.setAttributes( { orderby: value } );
							}
						} ),
						el( ToggleControl, {
							label: __( 'Accordion Mode', 'prospero-theme' ),
							checked: accordion,
							onChange: function( value ) {
								props.setAttributes( { accordion: value } );
							},
							help: __( 'Enable accordion (collapsible) mode for FAQs', 'prospero-theme' )
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
							__( '❓ FAQ List', 'prospero-theme' )
						),
						el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, 
							accordion ? 
								__( 'Accordion mode enabled - FAQs will be collapsible on frontend', 'prospero-theme' ) :
								__( 'All FAQs will be displayed expanded', 'prospero-theme' )
						),
						el( 'p', { style: { margin: '5px 0 0 0', fontSize: '14px', color: '#666' } }, 
							count === -1 ? 
								__( 'Showing all FAQs', 'prospero-theme' ) :
								__( 'Showing ' + count + ' FAQs', 'prospero-theme' )
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
