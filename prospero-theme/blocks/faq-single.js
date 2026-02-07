( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'prospero/faq-single', {
		title: __( 'FAQ Single', 'prospero-theme' ),
		description: __( 'Display a single FAQ item', 'prospero-theme' ),
		icon: 'editor-help',
		category: 'prospero',
		keywords: [ __( 'faq', 'prospero-theme' ), __( 'question', 'prospero-theme' ), __( 'answer', 'prospero-theme' ) ],
		attributes: {
			faqId: {
				type: 'number',
				default: 0
			},
			showTitle: {
				type: 'boolean',
				default: true
			}
		},
		supports: {
			html: false,
		},
		edit: function( props ) {
			var faqId = props.attributes.faqId;
			var showTitle = props.attributes.showTitle;
			
			// Get FAQ options from localized data
			var faqOptions = [ { label: __( 'Select FAQ...', 'prospero-theme' ), value: 0 } ];
			if ( window.prosperoBlocks && window.prosperoBlocks.faqs ) {
				faqOptions = faqOptions.concat( window.prosperoBlocks.faqs );
			}

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'FAQ Settings', 'prospero-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Select FAQ', 'prospero-theme' ),
							value: faqId,
							options: faqOptions,
							onChange: function( value ) {
								props.setAttributes( { faqId: parseInt( value ) } );
							}
						} ),
						el( ToggleControl, {
							label: __( 'Show Question', 'prospero-theme' ),
							checked: showTitle,
							onChange: function( value ) {
								props.setAttributes( { showTitle: value } );
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
							__( '❓ FAQ Single', 'prospero-theme' )
						),
						faqId > 0 ? 
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, 
								__( 'FAQ ID: ' + faqId, 'prospero-theme' )
							) :
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, 
								__( 'Please select an FAQ from the sidebar.', 'prospero-theme' )
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
