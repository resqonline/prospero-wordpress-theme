( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var InnerBlocks = blockEditor.InnerBlocks;
	var useBlockProps = blockEditor.useBlockProps;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var TextControl = components.TextControl;
	var TextareaControl = components.TextareaControl;
	var ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'prospero/member-content', {
		title: __( 'Member Content', 'prospero-theme' ),
		description: __( 'Content visible only to logged-in users with specific roles', 'prospero-theme' ),
		icon: 'lock',
		category: 'prospero',
		keywords: [ __( 'member', 'prospero-theme' ), __( 'restricted', 'prospero-theme' ), __( 'login', 'prospero-theme' ), __( 'private', 'prospero-theme' ) ],
		attributes: {
			requiredRole: {
				type: 'string',
				default: 'subscriber'
			},
			loginMessage: {
				type: 'string',
				default: ''
			},
			showFallbackForm: {
				type: 'boolean',
				default: false
			},
			fallbackShortcode: {
				type: 'string',
				default: ''
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
				className: 'prospero-member-content-wrapper'
			} ) : { className: props.className + ' prospero-member-content-wrapper' };

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'Access Settings', 'prospero-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Minimum Required Role', 'prospero-theme' ),
							value: attributes.requiredRole,
							options: [
								{ label: __( 'Subscriber (any logged-in user)', 'prospero-theme' ), value: 'subscriber' },
								{ label: __( 'Contributor', 'prospero-theme' ), value: 'contributor' },
								{ label: __( 'Author', 'prospero-theme' ), value: 'author' },
								{ label: __( 'Editor', 'prospero-theme' ), value: 'editor' },
								{ label: __( 'Administrator', 'prospero-theme' ), value: 'administrator' }
							],
							onChange: function( value ) {
								setAttributes( { requiredRole: value } );
							},
							help: __( 'Users with this role or higher can see the content', 'prospero-theme' )
						} )
					),
					el( PanelBody, { title: __( 'Fallback Content', 'prospero-theme' ), initialOpen: false },
						el( TextareaControl, {
							label: __( 'Message for Non-Logged-In Users', 'prospero-theme' ),
							value: attributes.loginMessage,
							onChange: function( value ) {
								setAttributes( { loginMessage: value } );
							},
							help: __( 'Leave empty for default message', 'prospero-theme' ),
							rows: 3
						} ),
						el( ToggleControl, {
							label: __( 'Show Form for Non-Logged-In Users', 'prospero-theme' ),
							checked: attributes.showFallbackForm,
							onChange: function( value ) {
								setAttributes( { showFallbackForm: value } );
							},
							help: __( 'Display a form (e.g., login or registration) instead of just a message', 'prospero-theme' )
						} ),
						attributes.showFallbackForm ?
							el( TextControl, {
								label: __( 'Form Shortcode', 'prospero-theme' ),
								value: attributes.fallbackShortcode,
								onChange: function( value ) {
									setAttributes( { fallbackShortcode: value } );
								},
								help: __( 'E.g., [gravityform id="1"] or [prospero_login_form]', 'prospero-theme' ),
								placeholder: '[shortcode]'
							} ) : null
					)
				),
				el( 'div', blockProps,
					el( 'div', { className: 'prospero-member-content-header' },
						el( 'span', { className: 'prospero-member-content-icon' }),
						el( 'span', { className: 'prospero-member-content-label' },
							__( 'Member Content', 'prospero-theme' ) + ' (' + attributes.requiredRole + '+)'
						)
					),
					el( 'div', { className: 'prospero-member-content-inner' },
						el( InnerBlocks, {
							templateLock: false
						} )
					),
					attributes.showFallbackForm && attributes.fallbackShortcode ?
						el( 'div', { className: 'prospero-member-content-fallback-preview' },
							el( 'small', {},
								__( 'Fallback form:', 'prospero-theme' ) + ' ' + attributes.fallbackShortcode
							)
						) : null
				)
			];
		},
		save: function() {
			return el( 'div', useBlockProps ? useBlockProps.save() : {},
				el( InnerBlocks.Content )
			);
		}
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor,
	window.wp.components
);
