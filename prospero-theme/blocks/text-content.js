( function( blocks, element, blockEditor, components ) {
	var el = element.createElement;
	var __ = wp.i18n.__;
	var InnerBlocks = blockEditor.InnerBlocks;
	var useBlockProps = blockEditor.useBlockProps;

	// Allowed blocks inside Text Content wrapper
	var ALLOWED_BLOCKS = [
		'core/paragraph',
		'core/heading',
		'core/list',
		'core/quote',
		'core/pullquote'
	];

	// Template for new blocks
	var TEMPLATE = [
		[ 'core/heading', { level: 2, placeholder: __( 'Add heading...', 'prospero-theme' ) } ],
		[ 'core/paragraph', { placeholder: __( 'Add content...', 'prospero-theme' ) } ]
	];

	blocks.registerBlockType( 'prospero/text-content', {
		title: __( 'Text Content', 'prospero-theme' ),
		description: __( 'A wrapper block for text content with headings and paragraphs', 'prospero-theme' ),
		icon: 'text',
		category: 'prospero',
		keywords: [ __( 'text', 'prospero-theme' ), __( 'content', 'prospero-theme' ), __( 'wrapper', 'prospero-theme' ) ],
		supports: {
			html: false,
			align: [ 'wide', 'full' ]
		},
		edit: function( props ) {
			var blockProps = useBlockProps ? useBlockProps( {
				className: 'prospero-text-content'
			} ) : { className: props.className + ' prospero-text-content' };

			return el( 'div', blockProps,
				el( InnerBlocks, {
					allowedBlocks: ALLOWED_BLOCKS,
					template: TEMPLATE,
					templateLock: false
				} )
			);
		},
		save: function() {
			var blockProps = useBlockProps ? useBlockProps.save( {
				className: 'prospero-text-content'
			} ) : { className: 'prospero-text-content' };

			return el( 'div', blockProps,
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
