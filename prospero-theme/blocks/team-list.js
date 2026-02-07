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

	blocks.registerBlockType( 'prospero/team-list', {
		title: __( 'Team', 'prospero-theme' ),
		description: __( 'Display team members with layout and style options', 'prospero-theme' ),
		icon: 'groups',
		category: 'prospero',
		keywords: [ __( 'team', 'prospero-theme' ), __( 'members', 'prospero-theme' ), __( 'staff', 'prospero-theme' ) ],
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
			layout: {
				type: 'string',
				default: 'columns'
			},
			columns: {
				type: 'number',
				default: 3
			},
			imageStyle: {
				type: 'string',
				default: 'square'
			},
			lightbox: {
				type: 'boolean',
				default: false
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

			var teamMembers = window.prosperoBlocks && window.prosperoBlocks.teamMembers ? window.prosperoBlocks.teamMembers : [];
			var categories = window.prosperoBlocks && window.prosperoBlocks.teamCategories ? window.prosperoBlocks.teamCategories : [];

			var blockProps = useBlockProps ? useBlockProps( {
				className: 'prospero-team-list-editor'
			} ) : { className: props.className + ' prospero-team-list-editor' };

			var toggleMember = function( id ) {
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

			var layoutLabel = attributes.layout === 'columns' ? __( 'Columns', 'prospero-theme' ) : __( 'List', 'prospero-theme' );
			var imageStyleLabels = {
				square: __( 'Square', 'prospero-theme' ),
				round: __( 'Round', 'prospero-theme' ),
				portrait: __( 'Portrait', 'prospero-theme' )
			};

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'Selection', 'prospero-theme' ), initialOpen: true },
						teamMembers.length > 0 ?
							el( 'div', { className: 'prospero-post-selector' },
								el( 'label', { className: 'components-base-control__label' },
									__( 'Select Team Members', 'prospero-theme' )
								),
								teamMembers.map( function( item ) {
									return el( CheckboxControl, {
										key: item.value,
										label: item.label,
										checked: isSelected( item.value ),
										onChange: function() {
											toggleMember( item.value );
										}
									} );
								} )
							) :
							el( 'p', { style: { fontStyle: 'italic', color: '#666' } },
								__( 'No team members found.', 'prospero-theme' )
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
					el( PanelBody, { title: __( 'Layout', 'prospero-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Layout Style', 'prospero-theme' ),
							value: attributes.layout,
							options: [
								{ label: __( 'Columns (Grid)', 'prospero-theme' ), value: 'columns' },
								{ label: __( 'List', 'prospero-theme' ), value: 'list' }
							],
							onChange: function( value ) {
								setAttributes( { layout: value } );
							}
						} ),
						( attributes.layout === 'columns' || attributes.slider ) ?
							el( RangeControl, {
								label: attributes.slider ? __( 'Items per View', 'prospero-theme' ) : __( 'Columns', 'prospero-theme' ),
								value: attributes.columns,
								onChange: function( value ) {
									setAttributes( { columns: value } );
								},
								min: 2,
								max: 6,
								help: attributes.slider ? __( 'Number of team members visible at once in slider mode', 'prospero-theme' ) : null
							} ) : null,
						el( SelectControl, {
							label: __( 'Image Style', 'prospero-theme' ),
							value: attributes.imageStyle,
							options: [
								{ label: __( 'Square', 'prospero-theme' ), value: 'square' },
								{ label: __( 'Round', 'prospero-theme' ), value: 'round' },
								{ label: __( 'Portrait', 'prospero-theme' ), value: 'portrait' }
							],
							onChange: function( value ) {
								setAttributes( { imageStyle: value } );
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
								{ label: __( 'Name', 'prospero-theme' ), value: 'title' },
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
						el( ToggleControl, {
							label: __( 'Open in Lightbox', 'prospero-theme' ),
							checked: attributes.lightbox,
							onChange: function( value ) {
								setAttributes( { lightbox: value } );
							},
							help: attributes.lightbox ?
								__( 'Content opens in lightbox overlay', 'prospero-theme' ) :
								__( 'Links to single page', 'prospero-theme' )
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
							__( 'Team', 'prospero-theme' )
						),
						el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } },
							attributes.ids.length > 0 ?
								attributes.ids.length + ' ' + __( 'selected', 'prospero-theme' ) :
								attributes.count === -1 ?
									__( 'Showing all members', 'prospero-theme' ) :
									__( 'Showing', 'prospero-theme' ) + ' ' + attributes.count
						),
						el( 'p', { style: { margin: '5px 0 0 0', fontSize: '12px', color: '#0073aa' } },
							layoutLabel + ' / ' + imageStyleLabels[ attributes.imageStyle ] +
							( attributes.slider ? ' / ' + __( 'Slider', 'prospero-theme' ) : '' ) +
							( attributes.lightbox ? ' / ' + __( 'Lightbox', 'prospero-theme' ) : '' )
						)
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
