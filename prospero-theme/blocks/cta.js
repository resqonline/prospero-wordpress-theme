( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var MediaUpload = blockEditor.MediaUpload;
	var MediaUploadCheck = blockEditor.MediaUploadCheck;
	var useBlockProps = blockEditor.useBlockProps;
	var PanelBody = components.PanelBody;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var SelectControl = components.SelectControl;
	var Button = components.Button;
	var ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'prospero/cta', {
		title: __( 'Call to Action', 'prospero-theme' ),
		description: __( 'A call to action block with headline, text, button, and optional images', 'prospero-theme' ),
		icon: 'megaphone',
		category: 'prospero',
		keywords: [ __( 'cta', 'prospero-theme' ), __( 'call to action', 'prospero-theme' ), __( 'button', 'prospero-theme' ) ],
		attributes: {
			heading: {
				type: 'string',
				default: ''
			},
			content: {
				type: 'string',
				default: ''
			},
			buttonText: {
				type: 'string',
				default: ''
			},
			buttonUrl: {
				type: 'string',
				default: ''
			},
			buttonStyle: {
				type: 'string',
				default: 'primary'
			},
			layout: {
				type: 'string',
				default: 'center'
			},
			imageId: {
				type: 'number',
				default: 0
			},
			imageUrl: {
				type: 'string',
				default: ''
			},
			bgImageId: {
				type: 'number',
				default: 0
			},
			bgImageUrl: {
				type: 'string',
				default: ''
			},
			bgColor: {
				type: 'string',
				default: 'secondary'
			}
		},
		supports: {
			html: false,
			align: [ 'wide', 'full' ]
		},
		edit: function( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			var blockProps = useBlockProps ? useBlockProps( {
				className: 'prospero-cta prospero-cta-' + attributes.layout
			} ) : { className: props.className + ' prospero-cta prospero-cta-' + attributes.layout };

			// Build inline styles for background
			var wrapperStyle = {};
			if ( attributes.bgImageUrl ) {
				wrapperStyle.backgroundImage = 'url(' + attributes.bgImageUrl + ')';
				wrapperStyle.backgroundSize = 'cover';
				wrapperStyle.backgroundPosition = 'center';
			}

			var onSelectImage = function( media ) {
				setAttributes( {
					imageId: media.id,
					imageUrl: media.url
				} );
			};

			var onRemoveImage = function() {
				setAttributes( {
					imageId: 0,
					imageUrl: ''
				} );
			};

			var onSelectBgImage = function( media ) {
				setAttributes( {
					bgImageId: media.id,
					bgImageUrl: media.url
				} );
			};

			var onRemoveBgImage = function() {
				setAttributes( {
					bgImageId: 0,
					bgImageUrl: ''
				} );
			};

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'Content', 'prospero-theme' ), initialOpen: true },
						el( TextControl, {
							label: __( 'Heading', 'prospero-theme' ),
							value: attributes.heading,
							onChange: function( value ) {
								setAttributes( { heading: value } );
							}
						} ),
						el( TextareaControl, {
							label: __( 'Text Content', 'prospero-theme' ),
							value: attributes.content,
							onChange: function( value ) {
								setAttributes( { content: value } );
							},
							rows: 4
						} ),
						el( TextControl, {
							label: __( 'Button Text', 'prospero-theme' ),
							value: attributes.buttonText,
							onChange: function( value ) {
								setAttributes( { buttonText: value } );
							}
						} ),
						el( TextControl, {
							label: __( 'Button URL', 'prospero-theme' ),
							value: attributes.buttonUrl,
							onChange: function( value ) {
								setAttributes( { buttonUrl: value } );
							},
							type: 'url'
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
						} )
					),
					el( PanelBody, { title: __( 'Layout', 'prospero-theme' ), initialOpen: false },
						el( SelectControl, {
							label: __( 'Layout', 'prospero-theme' ),
							value: attributes.layout,
							options: [
								{ label: __( 'Center', 'prospero-theme' ), value: 'center' },
								{ label: __( 'Left (Image Left)', 'prospero-theme' ), value: 'left' },
								{ label: __( 'Right (Image Right)', 'prospero-theme' ), value: 'right' }
							],
							onChange: function( value ) {
								setAttributes( { layout: value } );
							},
							help: __( 'Choose how content and image are arranged', 'prospero-theme' )
						} ),
						el( SelectControl, {
							label: __( 'Background Color', 'prospero-theme' ),
							value: attributes.bgColor,
							options: [
								{ label: __( 'None', 'prospero-theme' ), value: '' },
								{ label: __( 'Primary', 'prospero-theme' ), value: 'primary' },
								{ label: __( 'Secondary', 'prospero-theme' ), value: 'secondary' },
								{ label: __( 'Tertiary', 'prospero-theme' ), value: 'tertiary' },
								{ label: __( 'Highlight', 'prospero-theme' ), value: 'highlight' }
							],
							onChange: function( value ) {
								setAttributes( { bgColor: value } );
							}
						} )
					),
					el( PanelBody, { title: __( 'Image', 'prospero-theme' ), initialOpen: false },
						el( 'div', { className: 'prospero-media-upload' },
							el( 'p', { className: 'components-base-control__label' }, __( 'Content Image', 'prospero-theme' ) ),
							el( MediaUploadCheck, {},
								el( MediaUpload, {
									onSelect: onSelectImage,
									allowedTypes: [ 'image' ],
									value: attributes.imageId,
									render: function( obj ) {
										return el( 'div', {},
											attributes.imageUrl ?
												el( 'div', { style: { marginBottom: '10px' } },
													el( 'img', { src: attributes.imageUrl, style: { maxWidth: '100%', height: 'auto' } } ),
													el( Button, {
														onClick: onRemoveImage,
														isDestructive: true,
														isSmall: true,
														style: { marginTop: '10px' }
													}, __( 'Remove Image', 'prospero-theme' ) )
												) :
												el( Button, {
													onClick: obj.open,
													variant: 'secondary'
												}, __( 'Select Image', 'prospero-theme' ) )
										);
									}
								} )
							)
						)
					),
					el( PanelBody, { title: __( 'Background Image', 'prospero-theme' ), initialOpen: false },
						el( 'div', { className: 'prospero-media-upload' },
							el( MediaUploadCheck, {},
								el( MediaUpload, {
									onSelect: onSelectBgImage,
									allowedTypes: [ 'image' ],
									value: attributes.bgImageId,
									render: function( obj ) {
										return el( 'div', {},
											attributes.bgImageUrl ?
												el( 'div', { style: { marginBottom: '10px' } },
													el( 'img', { src: attributes.bgImageUrl, style: { maxWidth: '100%', height: 'auto' } } ),
													el( Button, {
														onClick: onRemoveBgImage,
														isDestructive: true,
														isSmall: true,
														style: { marginTop: '10px' }
													}, __( 'Remove Background', 'prospero-theme' ) )
												) :
												el( Button, {
													onClick: obj.open,
													variant: 'secondary'
												}, __( 'Select Background Image', 'prospero-theme' ) )
										);
									}
								} )
							),
							el( 'p', { className: 'components-base-control__help', style: { marginTop: '10px' } },
								__( 'Background image will overlay the background color', 'prospero-theme' )
							)
						)
					)
				),
				el( 'div', Object.assign( {}, blockProps, { style: wrapperStyle, key: 'editor' } ),
					attributes.bgImageUrl ? el( 'div', { className: 'prospero-cta-overlay' } ) : null,
					( attributes.layout !== 'center' && attributes.imageUrl ) ?
						el( 'div', { className: 'prospero-cta-image' },
							el( 'img', { src: attributes.imageUrl, alt: '' } )
						) : null,
					el( 'div', { className: 'prospero-cta-content' },
						( attributes.layout === 'center' && attributes.imageUrl ) ?
							el( 'div', { className: 'prospero-cta-image prospero-cta-image-center' },
								el( 'img', { src: attributes.imageUrl, alt: '' } )
							) : null,
						attributes.heading ?
							el( 'h2', { className: 'prospero-cta-heading' }, attributes.heading ) :
							el( 'h2', { className: 'prospero-cta-heading prospero-placeholder' }, __( 'Add heading...', 'prospero-theme' ) ),
						attributes.content ?
							el( 'div', { className: 'prospero-cta-text' }, attributes.content ) :
							el( 'div', { className: 'prospero-cta-text prospero-placeholder' }, __( 'Add content...', 'prospero-theme' ) ),
						attributes.buttonText ?
							el( 'div', { className: 'prospero-cta-button' },
								el( 'span', { className: 'button button-' + attributes.buttonStyle }, attributes.buttonText )
							) :
							el( 'div', { className: 'prospero-cta-button prospero-placeholder' },
								el( 'span', { className: 'button button-' + attributes.buttonStyle }, __( 'Button Text', 'prospero-theme' ) )
							)
					)
				)
			];
		},
		save: function() {
			// Server-side rendered
			return null;
		}
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components
);
