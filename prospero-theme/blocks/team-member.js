( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;

	blocks.registerBlockType( 'prospero/team-member', {
		title: __( 'Team Member', 'prospero-theme' ),
		description: __( 'Display a single team member', 'prospero-theme' ),
		icon: 'admin-users',
		category: 'prospero',
		keywords: [ __( 'team', 'prospero-theme' ), __( 'member', 'prospero-theme' ), __( 'staff', 'prospero-theme' ) ],
		attributes: {
			memberId: {
				type: 'number',
				default: 0
			},
			layout: {
				type: 'string',
				default: 'card'
			}
		},
		supports: {
			html: false,
		},
		edit: function( props ) {
			var memberId = props.attributes.memberId;
			var layout = props.attributes.layout;
			
			// Get team member options from localized data
			var memberOptions = [ { label: __( 'Select Team Member...', 'prospero-theme' ), value: 0 } ];
			if ( window.prosperoBlocks && window.prosperoBlocks.teamMembers ) {
				memberOptions = memberOptions.concat( window.prosperoBlocks.teamMembers );
			}

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'Team Member Settings', 'prospero-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Select Team Member', 'prospero-theme' ),
							value: memberId,
							options: memberOptions,
							onChange: function( value ) {
								props.setAttributes( { memberId: parseInt( value ) } );
							}
						} ),
						el( SelectControl, {
							label: __( 'Layout', 'prospero-theme' ),
							value: layout,
							options: [
								{ label: __( 'Card (with excerpt)', 'prospero-theme' ), value: 'card' },
								{ label: __( 'Simple (name only)', 'prospero-theme' ), value: 'simple' }
							],
							onChange: function( value ) {
								props.setAttributes( { layout: value } );
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
							__( 'Team Member', 'prospero-theme' )
						),
						memberId > 0 ? 
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, 
								__( 'Member ID: ', 'prospero-theme' ) + memberId + ' (' + layout + ' layout)'
							) :
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, 
								__( 'Please select a team member from the sidebar.', 'prospero-theme' )
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
