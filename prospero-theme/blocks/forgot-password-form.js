( function( blocks, element, blockEditor ) {
	var el = element.createElement;
	var __ = wp.i18n.__;

	blocks.registerBlockType( 'prospero/forgot-password-form', {
		title: __( 'Forgot Password Form', 'prospero-theme' ),
		description: __( 'Display a password reset form', 'prospero-theme' ),
		icon: 'admin-users',
		category: 'prospero',
		keywords: [ __( 'password', 'prospero-theme' ), __( 'reset', 'prospero-theme' ), __( 'forgot', 'prospero-theme' ) ],
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
					el( 'p', { style: { margin: 0, fontWeight: 'bold' } }, __( '🔑 Forgot Password Form', 'prospero-theme' ) ),
					el( 'p', { style: { margin: '10px 0 0 0', fontSize: '14px', color: '#666' } }, __( 'The password reset form will be displayed here on the frontend.', 'prospero-theme' ) )
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
