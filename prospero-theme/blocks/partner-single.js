( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'prospero/partner-single', {
		title: __( 'Partner Single', 'prospero-theme' ),
		description: __( 'Display a single partner', 'prospero-theme' ),
		icon: 'groups',
		category: 'prospero',
		keywords: [ __( 'partner', 'prospero-theme' ), __( 'sponsor', 'prospero-theme' ), __( 'client', 'prospero-theme' ) ],
		attributes: {
			partnerId: {
				type: 'number',
				default: 0
			},
			showLogo: {
				type: 'boolean',
				default: true
			}
		},
		supports: {
			html: false,
		},
		edit: function( props ) {
			var partnerId = props.attributes.partnerId;
			var showLogo = props.attributes.showLogo;
			var partners = window.prosperoBlocks && window.prosperoBlocks.partners ? window.prosperoBlocks.partners : [];

			var partnerOptions = [ { label: __( 'Select a partner', 'prospero-theme' ), value: 0 } ];
			partners.forEach( function( partner ) {
				partnerOptions.push( {
					label: partner.label,
					value: partner.value
				} );
			} );

			var selectedPartnerName = '';
			if ( partnerId ) {
				var selectedPartner = partners.find( function( p ) {
					return p.value === partnerId;
				} );
				if ( selectedPartner ) {
					selectedPartnerName = selectedPartner.label;
				}
			}

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'Partner Settings', 'prospero-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Select Partner', 'prospero-theme' ),
							value: partnerId,
							options: partnerOptions,
							onChange: function( value ) {
								props.setAttributes( { partnerId: parseInt( value ) } );
							}
						} ),
						el( ToggleControl, {
							label: __( 'Show Logo', 'prospero-theme' ),
							checked: showLogo,
							onChange: function( value ) {
								props.setAttributes( { showLogo: value } );
							}
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
							__( '🤝 Partner Single', 'prospero-theme' )
						),
						selectedPartnerName ?
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, 
								selectedPartnerName
							) :
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#999', fontStyle: 'italic' } }, 
								__( 'No partner selected', 'prospero-theme' )
							),
						showLogo ?
							el( 'p', { style: { margin: '5px 0 0 0', fontSize: '12px', color: '#999' } }, 
								__( 'Logo will be displayed', 'prospero-theme' )
							) : null
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
