( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var useBlockProps = blockEditor.useBlockProps;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var RangeControl = components.RangeControl;
	var ToggleControl = components.ToggleControl;
	var CheckboxControl = components.CheckboxControl;

	blocks.registerBlockType( 'prospero/partners-list', {
		title: __( 'Partners', 'prospero-theme' ),
		description: __( 'Display partners from specific posts or a category', 'prospero-theme' ),
		icon: 'groups',
		category: 'prospero',
		keywords: [ __( 'partners', 'prospero-theme' ), __( 'clients', 'prospero-theme' ), __( 'sponsors', 'prospero-theme' ) ],
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
				default: -1
			},
			orderby: {
				type: 'string',
				default: 'menu_order'
			},
			columns: {
				type: 'number',
				default: 4
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

			var partners = window.prosperoBlocks && window.prosperoBlocks.partners ? window.prosperoBlocks.partners : [];
			var categories = window.prosperoBlocks && window.prosperoBlocks.partnerCategories ? window.prosperoBlocks.partnerCategories : [];

			var blockProps = useBlockProps ? useBlockProps( {
				className: 'prospero-partners-list-editor'
			} ) : { className: props.className + ' prospero-partners-list-editor' };

			var togglePartner = function( id ) {
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
						partners.length > 0 ?
							el( 'div', { className: 'prospero-post-selector' },
								el( 'label', { className: 'components-base-control__label' },
									__( 'Select Partners', 'prospero-theme' )
								),
								partners.map( function( item ) {
									return el( CheckboxControl, {
										key: item.value,
										label: item.label,
										checked: isSelected( item.value ),
										onChange: function() {
											togglePartner( item.value );
										}
									} );
								} )
							) :
							el( 'p', { style: { fontStyle: 'italic', color: '#666' } },
								__( 'No partners found.', 'prospero-theme' )
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
							}
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
								min: -1,
								max: 50,
								help: __( 'Set to -1 for all', 'prospero-theme' )
							} ) : null,
						el( SelectControl, {
							label: __( 'Order By', 'prospero-theme' ),
							value: attributes.orderby,
							options: [
								{ label: __( 'Custom Order', 'prospero-theme' ), value: 'menu_order' },
								{ label: __( 'Title', 'prospero-theme' ), value: 'title' },
								{ label: __( 'Date', 'prospero-theme' ), value: 'date' }
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
							}
						} ),
						attributes.slider ?
							el( RangeControl, {
								label: __( 'Items per View', 'prospero-theme' ),
								value: attributes.columns,
								onChange: function( value ) {
									setAttributes( { columns: value } );
								},
								min: 2,
								max: 8,
								help: __( 'Number of partners visible at once in slider mode', 'prospero-theme' )
							} ) : null
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
							__( 'Partners', 'prospero-theme' )
						),
						el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } },
							attributes.ids.length > 0 ?
								attributes.ids.length + ' ' + __( 'selected', 'prospero-theme' ) :
								attributes.count === -1 ?
									__( 'Showing all partners', 'prospero-theme' ) :
									__( 'Showing', 'prospero-theme' ) + ' ' + attributes.count
						),
						attributes.slider ?
							el( 'p', { style: { margin: '5px 0 0 0', fontSize: '12px', color: '#0073aa' } },
								__( 'Slider mode', 'prospero-theme' )
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
	window.wp.components
);
