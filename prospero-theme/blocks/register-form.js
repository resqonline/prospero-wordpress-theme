( function( blocks, element, blockEditor ) {
	var el = element.createElement;
	var __ = wp.i18n.__;

	blocks.registerBlockType( 'prospero/register-form', {
		title: __( 'Register Form', 'prospero-theme' ),
		description: __( 'Display a user registration form', 'prospero-theme' ),
		icon: 'admin-users',
		category: 'prospero',
		keywords: [ __( 'register', 'prospero-theme' ), __( 'signup', 'prospero-theme' ), __( 'account', 'prospero-theme' ) ],
		supports: {
			html: false,
			customClassName: false,
		},
		edit: function( props ) {
			return el(
				'div',
				{ className: props.className },
				el( 'div', { 
					style: { 
						padding: '20px', 
						backgroundColor: '#f0f0f1', 
						border: '1px solid #ddd',
						borderRadius: '4px',
						textAlign: 'center'
					} 
				},
					el( 'p', { style: { margin: 0, fontWeight: 'bold' } }, __( 'Register Form', 'prospero-theme' ) ),
					el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, __( 'The registration form will be displayed here on the frontend.', 'prospero-theme' ) )
				)
			);
		},
		save: function() {
			return null;
		},
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor
);
