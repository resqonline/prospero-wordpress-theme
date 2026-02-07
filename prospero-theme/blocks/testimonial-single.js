( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'prospero/testimonial-single', {
		title: __( 'Testimonial (Single)', 'prospero-theme' ),
		description: __( 'Display a single testimonial', 'prospero-theme' ),
		icon: 'format-quote',
		category: 'prospero',
		keywords: [ __( 'testimonial', 'prospero-theme' ), __( 'quote', 'prospero-theme' ), __( 'review', 'prospero-theme' ) ],
		attributes: {
			testimonialId: {
				type: 'number',
				default: 0
			},
			showImage: {
				type: 'boolean',
				default: true
			}
		},
		supports: {
			html: false,
		},
		edit: function( props ) {
			var testimonialId = props.attributes.testimonialId;
			var showImage = props.attributes.showImage;
			
			// Get testimonial options from localized data
			var testimonialOptions = [ { label: __( 'Select Testimonial...', 'prospero-theme' ), value: 0 } ];
			if ( window.prosperoBlocks && window.prosperoBlocks.testimonials ) {
				testimonialOptions = testimonialOptions.concat( window.prosperoBlocks.testimonials );
			}

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'Testimonial Settings', 'prospero-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Select Testimonial', 'prospero-theme' ),
							value: testimonialId,
							options: testimonialOptions,
							onChange: function( value ) {
								props.setAttributes( { testimonialId: parseInt( value ) } );
							}
						} ),
						el( ToggleControl, {
							label: __( 'Show Image', 'prospero-theme' ),
							checked: showImage,
							onChange: function( value ) {
								props.setAttributes( { showImage: value } );
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
							__( 'Testimonial (Single)', 'prospero-theme' )
						),
						testimonialId > 0 ? 
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, 
								__( 'Testimonial ID: ', 'prospero-theme' ) + testimonialId
							) :
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, 
								__( 'Please select a testimonial from the sidebar.', 'prospero-theme' )
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
