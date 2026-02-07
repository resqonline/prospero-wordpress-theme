( function( blocks, element, blockEditor ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var RichText = blockEditor.RichText;

	blocks.registerBlockType( 'prospero/login-form', {
		title: __( 'Login Form', 'prospero-theme' ),
		description: __( 'Display a login form', 'prospero-theme' ),
		icon: 'admin-users',
		category: 'prospero',
		keywords: [ __( 'login', 'prospero-theme' ), __( 'user', 'prospero-theme' ), __( 'account', 'prospero-theme' ) ],
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
					el( 'p', { style: { margin: 0, fontWeight: 'bold' } }, __( '🔐 Login Form', 'prospero-theme' ) ),
					el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, __( 'The login form will be displayed here on the frontend.', 'prospero-theme' ) )
				)
			);
		},
		save: function() {
			return null; // Dynamic block, server-side rendered
		},
	} );
} )(
	window.wp.blocks,
	window.wp.element,
	window.wp.blockEditor
);
