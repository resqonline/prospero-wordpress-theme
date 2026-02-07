( function( blocks, element, blockEditor, components, data ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var useState = element.useState;
	var useEffect = element.useEffect;
	var InspectorControls = blockEditor.InspectorControls;
	var useBlockProps = blockEditor.useBlockProps;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var RangeControl = components.RangeControl;
	var ToggleControl = components.ToggleControl;
	var CheckboxControl = components.CheckboxControl;
	var Spinner = components.Spinner;

	blocks.registerBlockType( 'prospero/testimonials-list', {
		title: __( 'Testimonials', 'prospero-theme' ),
		description: __( 'Display testimonials from specific posts or a category', 'prospero-theme' ),
		icon: 'format-quote',
		category: 'prospero',
		keywords: [ __( 'testimonials', 'prospero-theme' ), __( 'reviews', 'prospero-theme' ), __( 'quotes', 'prospero-theme' ) ],
		attributes: {
			ids: {
				type: 'array',
				default: []
			},
			category: {
				type: 'string',
				default: ''
			},
			count: {
				type: 'number',
				default: 3
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
			align: [ 'wide', 'full' ]
		},
		edit: function( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			// State for testimonials list
			var testimonials = window.prosperoBlocks && window.prosperoBlocks.testimonials ? window.prosperoBlocks.testimonials : [];
			var categories = window.prosperoBlocks && window.prosperoBlocks.testimonialCategories ? window.prosperoBlocks.testimonialCategories : [];

			var blockProps = useBlockProps ? useBlockProps( {
				className: 'prospero-testimonials-list-editor'
			} ) : { className: props.className + ' prospero-testimonials-list-editor' };

			// Handle ID selection
			var toggleTestimonial = function( id ) {
				var currentIds = attributes.ids.slice();
				var index = currentIds.indexOf( id );
				if ( index === -1 ) {
					currentIds.push( id );
				} else {
					currentIds.splice( index, 1 );
				}
				setAttributes( { ids: currentIds } );
			};

			var isSelected = function( id ) {
				return attributes.ids.indexOf( id ) !== -1;
			};

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'Selection', 'prospero-theme' ), initialOpen: true },
						el( 'p', { className: 'components-base-control__help' },
							__( 'Select specific testimonials or use a category filter.', 'prospero-theme' )
						),
						testimonials.length > 0 ?
							el( 'div', { className: 'prospero-post-selector' },
								el( 'label', { className: 'components-base-control__label' },
									__( 'Select Testimonials', 'prospero-theme' )
								),
								testimonials.map( function( item ) {
									return el( CheckboxControl, {
										key: item.value,
										label: item.label,
										checked: isSelected( item.value ),
										onChange: function() {
											toggleTestimonial( item.value );
										}
									} );
								} )
							) :
							el( 'p', { style: { fontStyle: 'italic', color: '#666' } },
								__( 'No testimonials found. Create some testimonials first.', 'prospero-theme' )
							),
						el( 'hr', { style: { margin: '20px 0' } } ),
						el( SelectControl, {
							label: __( 'Or Filter by Category', 'prospero-theme' ),
							value: attributes.category,
							options: [
								{ label: __( 'All Categories', 'prospero-theme' ), value: '' }
							].concat( categories || [] ),
							onChange: function( value ) {
								setAttributes( { category: value } );
							},
							help: attributes.ids.length > 0 ?
								__( 'Category filter is ignored when specific testimonials are selected.', 'prospero-theme' ) :
								__( 'Filter testimonials by category', 'prospero-theme' )
						} )
					),
					el( PanelBody, { title: __( 'Display Settings', 'prospero-theme' ), initialOpen: false },
						attributes.ids.length === 0 ?
							el( RangeControl, {
								label: __( 'Number to Show', 'prospero-theme' ),
								value: attributes.count,
								onChange: function( value ) {
									setAttributes( { count: value } );
								},
								min: 1,
								max: 20
							} ) : null,
						el( SelectControl, {
							label: __( 'Order By', 'prospero-theme' ),
							value: attributes.orderby,
							options: [
								{ label: __( 'Date', 'prospero-theme' ), value: 'date' },
								{ label: __( 'Title', 'prospero-theme' ), value: 'title' },
								{ label: __( 'Random', 'prospero-theme' ), value: 'rand' }
							],
							onChange: function( value ) {
								setAttributes( { orderby: value } );
							}
						} ),
						el( ToggleControl, {
							label: __( 'Display as Slider', 'prospero-theme' ),
							checked: attributes.slider,
							onChange: function( value ) {
								setAttributes( { slider: value } );
							},
							help: __( 'Uses Flickity for smooth carousel navigation', 'prospero-theme' )
						} )
					)
				),
				el( 'div', blockProps,
					el( 'div', {
						style: {
							padding: '20px',
							backgroundColor: '#f0f0f1',
							border: '1px solid #ddd',
							borderRadius: '4px'
						}
					},
						el( 'p', { style: { margin: 0, fontWeight: 'bold' } },
							__( 'Testimonials', 'prospero-theme' )
						),
						el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } },
							attributes.ids.length > 0 ?
								__( 'Showing', 'prospero-theme' ) + ' ' + attributes.ids.length + ' ' + __( 'selected testimonials', 'prospero-theme' ) :
								attributes.category ?
									__( 'Showing testimonials from category', 'prospero-theme' ) :
									__( 'Showing', 'prospero-theme' ) + ' ' + attributes.count + ' ' + __( 'testimonials', 'prospero-theme' )
						),
						attributes.slider ?
							el( 'p', { style: { margin: '5px 0 0 0', fontSize: '12px', color: '#0073aa' } },
								__( 'Slider mode enabled', 'prospero-theme' )
							) : null
					)
				)
			];
		},
		save: function() {
			return null;
		}
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components,
	window.wp.data
);
