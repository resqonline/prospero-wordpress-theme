( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InspectorControls = blockEditor.InspectorControls;
	var PanelBody = components.PanelBody;
	var SelectControl = components.SelectControl;
	var ToggleControl = components.ToggleControl;

	blocks.registerBlockType( 'prospero/project-single', {
		title: __( 'Project Single', 'prospero-theme' ),
		description: __( 'Display a single project', 'prospero-theme' ),
		icon: 'portfolio',
		category: 'prospero',
		keywords: [ __( 'project', 'prospero-theme' ), __( 'portfolio', 'prospero-theme' ), __( 'work', 'prospero-theme' ) ],
		attributes: {
			projectId: {
				type: 'number',
				default: 0
			},
			showFeatured: {
				type: 'boolean',
				default: true
			}
		},
		supports: {
			html: false,
		},
		edit: function( props ) {
			var projectId = props.attributes.projectId;
			var showFeatured = props.attributes.showFeatured;
			var projects = window.prosperoBlocks && window.prosperoBlocks.projects ? window.prosperoBlocks.projects : [];

			var projectOptions = [ { label: __( 'Select a project', 'prospero-theme' ), value: 0 } ];
			projects.forEach( function( project ) {
				projectOptions.push( {
					label: project.label,
					value: project.value
				} );
			} );

			var selectedProjectName = '';
			if ( projectId ) {
				var selectedProject = projects.find( function( p ) {
					return p.value === projectId;
				} );
				if ( selectedProject ) {
					selectedProjectName = selectedProject.label;
				}
			}

			return [
				el( InspectorControls, { key: 'inspector' },
					el( PanelBody, { title: __( 'Project Settings', 'prospero-theme' ), initialOpen: true },
						el( SelectControl, {
							label: __( 'Select Project', 'prospero-theme' ),
							value: projectId,
							options: projectOptions,
							onChange: function( value ) {
								props.setAttributes( { projectId: parseInt( value ) } );
							}
						} ),
						el( ToggleControl, {
							label: __( 'Show Featured Image', 'prospero-theme' ),
							checked: showFeatured,
							onChange: function( value ) {
								props.setAttributes( { showFeatured: value } );
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
							__( '📁 Project Single', 'prospero-theme' )
						),
						selectedProjectName ?
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, 
								selectedProjectName
							) :
							el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#999', fontStyle: 'italic' } }, 
								__( 'No project selected', 'prospero-theme' )
							),
						showFeatured ?
							el( 'p', { style: { margin: '5px 0 0 0', fontSize: '12px', color: '#999' } }, 
								__( 'Featured image will be displayed', 'prospero-theme' )
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
