( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var useBlockProps = blockEditor.useBlockProps;
	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'prospero/affiliate-link', {
		title: __( 'Affiliate Link', 'prospero-theme' ),
		description: __( 'Display an affiliate link button or embed affiliate widget scripts', 'prospero-theme' ),
		icon: 'admin-links',
		category: 'prospero',
		keywords: [
			__( 'affiliate', 'prospero-theme' ),
			__( 'link', 'prospero-theme' ),
			__( 'amazon', 'prospero-theme' ),
			__( 'awin', 'prospero-theme' ),
			__( 'embed', 'prospero-theme' )
		],
		attributes: {
			mode: {
				type: 'string',
				default: 'button'
			},
			url: {
				type: 'string',
				default: ''
			},
			linkText: {
				type: 'string',
				default: ''
			},
			buttonStyle: {
				type: 'string',
				default: 'primary'
			},
			disclosure: {
				type: 'boolean',
				default: true
			},
			newTab: {
				type: 'boolean',
				default: true
			},
			nofollow: {
				type: 'boolean',
				default: true
			},
			embedCode: {
				type: 'string',
				default: ''
			},
			embedLabel: {
				type: 'string',
				default: ''
			}
		},
		supports: {
			html: false,
			align: [ 'left', 'center', 'right' ]
		},
		edit: function( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			var blockProps = useBlockProps ? useBlockProps( {
				className: 'prospero-affiliate-link-editor'
			} ) : { className: props.className + ' prospero-affiliate-link-editor' };

			var isButtonMode = attributes.mode === 'button';
			var isEmbedMode = attributes.mode === 'embed';

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'Display Mode', 'prospero-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Type', 'prospero-theme' ),
							value: attributes.mode,
							options: [
								{ label: __( 'Button Link', 'prospero-theme' ), value: 'button' },
								{ label: __( 'Embed Script (Amazon, Awin, etc.)', 'prospero-theme' ), value: 'embed' }
							],
							onChange: function( value ) {
								setAttributes( { mode: value } );
							}
						} ),
						el( ToggleControl, {
							label: __( 'Show Disclosure Notice', 'prospero-theme' ),
							checked: attributes.disclosure,
							onChange: function( value ) {
								setAttributes( { disclosure: value } );
							},
							help: __( 'Display product placement notice', 'prospero-theme' )
						} )
					),
					isButtonMode ?
						el( PanelBody, { title: __( 'Button Settings', 'prospero-theme' ), initialOpen: true },
							el( TextControl, {
								label: __( 'URL', 'prospero-theme' ),
								value: attributes.url,
								onChange: function( value ) {
									setAttributes( { url: value } );
								},
								help: __( 'The affiliate link URL', 'prospero-theme' )
							} ),
							el( TextControl, {
								label: __( 'Button Text', 'prospero-theme' ),
								value: attributes.linkText,
								onChange: function( value ) {
									setAttributes( { linkText: value } );
								}
							} ),
							el( SelectControl, {
								label: __( 'Button Style', 'prospero-theme' ),
								value: attributes.buttonStyle,
								options: [
									{ label: __( 'Primary', 'prospero-theme' ), value: 'primary' },
									{ label: __( 'Secondary', 'prospero-theme' ), value: 'secondary' },
									{ label: __( 'Tertiary', 'prospero-theme' ), value: 'tertiary' }
								],
								onChange: function( value ) {
									setAttributes( { buttonStyle: value } );
								}
							} ),
							el( ToggleControl, {
								label: __( 'Open in New Tab', 'prospero-theme' ),
								checked: attributes.newTab,
								onChange: function( value ) {
									setAttributes( { newTab: value } );
								}
							} ),
							el( ToggleControl, {
								label: __( 'Add rel="nofollow"', 'prospero-theme' ),
								checked: attributes.nofollow,
								onChange: function( value ) {
									setAttributes( { nofollow: value } );
								},
								help: __( 'Recommended for affiliate links', 'prospero-theme' )
							} )
						) : null,
					isEmbedMode ?
						el( PanelBody, { title: __( 'Embed Settings', 'prospero-theme' ), initialOpen: true },
							el( TextControl, {
								label: __( 'Label (optional)', 'prospero-theme' ),
								value: attributes.embedLabel,
								onChange: function( value ) {
									setAttributes( { embedLabel: value } );
								},
								help: __( 'Internal label for this embed (not displayed)', 'prospero-theme' )
							} ),
							el( TextareaControl, {
								label: __( 'Embed Code', 'prospero-theme' ),
								value: attributes.embedCode,
								onChange: function( value ) {
									setAttributes( { embedCode: value } );
								},
								help: __( 'Paste the script or iframe code from Amazon, Awin, or other affiliate programs', 'prospero-theme' ),
								rows: 6
							} ),
							el( 'p', { style: { fontSize: '12px', color: '#666' } },
								__( 'Note: Scripts will only execute on the frontend, not in the editor preview.', 'prospero-theme' )
							)
						) : null
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
							isButtonMode ?
								__( 'Affiliate Link Button', 'prospero-theme' ) :
								__( 'Affiliate Embed', 'prospero-theme' )
						),
						isButtonMode && attributes.linkText ?
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } },
								__( 'Button: ', 'prospero-theme' ) + attributes.linkText
							) : null,
						isEmbedMode && attributes.embedLabel ?
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } },
								attributes.embedLabel
							) : null,
						isEmbedMode && attributes.embedCode ?
							el( 'p', { style: { margin: '5px 0 0 0', fontSize: '12px', color: '#0073aa' } },
								__( 'Embed code configured', 'prospero-theme' ) +
								' (' + attributes.embedCode.length + ' ' + __( 'characters', 'prospero-theme' ) + ')'
							) : null,
						isEmbedMode && ! attributes.embedCode ?
							el( 'p', { style: { margin: '5px 0 0 0', fontSize: '12px', color: '#d63638' } },
								__( 'No embed code entered', 'prospero-theme' )
							) : null,
						attributes.disclosure ?
							el( 'p', { style: { margin: '5px 0 0 0', fontSize: '12px', color: '#999', fontStyle: 'italic' } },
								__( 'Disclosure notice will be shown', 'prospero-theme' )
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
