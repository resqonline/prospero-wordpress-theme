( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;
	var TextControl = components.TextControl;

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
			},
			logoPosition: {
				type: 'string',
				default: 'left'
			},
			showVisitLink: {
				type: 'boolean',
				default: false
			},
			visitLinkText: {
				type: 'string',
				default: ''
			},
			visitLinkStyle: {
				type: 'string',
				default: 'secondary'
			}
		},
		supports: {
			html: false,
		},
		edit: function( props ) {
			var partnerId       = props.attributes.partnerId;
			var showLogo        = props.attributes.showLogo;
			var logoPosition    = props.attributes.logoPosition;
			var showVisitLink   = props.attributes.showVisitLink;
			var visitLinkText   = props.attributes.visitLinkText;
			var visitLinkStyle  = props.attributes.visitLinkStyle;
			var partners        = window.prosperoBlocks && window.prosperoBlocks.partners ? window.prosperoBlocks.partners : [];

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

			var logoPositionOptions = [
				{ label: __( 'Left', 'prospero-theme' ),  value: 'left' },
				{ label: __( 'Right', 'prospero-theme' ), value: 'right' }
			];

			var visitStyleOptions = [
				{ label: __( 'Primary', 'prospero-theme' ),   value: 'primary' },
				{ label: __( 'Secondary', 'prospero-theme' ), value: 'secondary' },
				{ label: __( 'Tertiary', 'prospero-theme' ),  value: 'tertiary' }
			];

			var visitSettings = [
				el( ToggleControl, {
					key: 'show-visit-link',
					label: __( 'Show "Visit website" button', 'prospero-theme' ),
					help: __( 'Renders a button link below the description using the partner\'s website URL.', 'prospero-theme' ),
					checked: showVisitLink,
					onChange: function( value ) {
						props.setAttributes( { showVisitLink: value } );
					}
				} )
			];

			if ( showVisitLink ) {
				visitSettings.push(
					el( TextControl, {
						key: 'visit-link-text',
						label: __( 'Button Text', 'prospero-theme' ),
						value: visitLinkText,
						placeholder: __( 'Visit website', 'prospero-theme' ),
						help: __( 'Leave empty to use the default "Visit website".', 'prospero-theme' ),
						onChange: function( value ) {
							props.setAttributes( { visitLinkText: value } );
						}
					} ),
					el( SelectControl, {
						key: 'visit-link-style',
						label: __( 'Button Style', 'prospero-theme' ),
						value: visitLinkStyle,
						options: visitStyleOptions,
						onChange: function( value ) {
							props.setAttributes( { visitLinkStyle: value } );
						}
					} )
				);
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
						} ),
						showLogo ? el( SelectControl, {
							label: __( 'Logo Position', 'prospero-theme' ),
							value: logoPosition,
							options: logoPositionOptions,
							onChange: function( value ) {
								props.setAttributes( { logoPosition: value } );
							}
						} ) : null
					),
					el( PanelBody, { title: __( 'Visit Website Button', 'prospero-theme' ), initialOpen: false }, visitSettings )
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
							__( 'Partner Single', 'prospero-theme' )
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
								__( 'Logo displayed on the ', 'prospero-theme' ) + ( logoPosition === 'right' ? __( 'right', 'prospero-theme' ) : __( 'left', 'prospero-theme' ) ) + ' • ' + __( 'linked to partner URL', 'prospero-theme' )
							) : null,
						showVisitLink ?
							el( 'p', { style: { margin: '5px 0 0 0', fontSize: '12px', color: '#999' } },
								__( '"Visit website" button enabled', 'prospero-theme' )
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
