/**
 * Table of Contents - Block Editor document settings panel.
 *
 * Reads/writes `_prospero_toc_status` via post meta (show_in_rest).
 * Mirrors the classic sidebar meta box in inc/toc.php.
 *
 * @package Prospero
 * @since 1.3.0
 */
( function ( wp ) {
	'use strict';

	if (
		! wp ||
		! wp.plugins ||
		! wp.editPost ||
		! wp.element ||
		! wp.components ||
		! wp.data ||
		! wp.i18n ||
		! wp.coreData
	) {
		return;
	}

	var __ = wp.i18n.__;
	var sprintf = wp.i18n.sprintf;
	var el = wp.element.createElement;
	var Fragment = wp.element.Fragment;
	var useSelect = wp.data.useSelect;
	var useEntityProp = wp.coreData.useEntityProp;
	var PluginDocumentSettingPanel = wp.editPost.PluginDocumentSettingPanel;
	var RadioControl = wp.components.RadioControl;
	var registerPlugin = wp.plugins.registerPlugin;

	function TocDocumentPanel() {
		var postType = useSelect( function ( select ) {
			return select( 'core/editor' ).getCurrentPostType();
		}, [] );

		if ( postType !== 'post' && postType !== 'page' ) {
			return null;
		}

		var metaState = useEntityProp( 'postType', postType, 'meta' );
		var meta = metaState[0] || {};
		var setMeta = metaState[1];
		var current = meta._prospero_toc_status || '';

		var autoInsertDefault = (
			typeof window.prosperoTocEditor !== 'undefined' &&
			window.prosperoTocEditor.autoInsertDefault
		);
		var defaultLabel = autoInsertDefault
			? __( 'Auto-insert on', 'prospero-theme' )
			: __( 'Auto-insert off', 'prospero-theme' );

		return el(
			PluginDocumentSettingPanel,
			{
				name: 'prospero-toc',
				title: __( 'Table of Contents', 'prospero-theme' ),
				className: 'prospero-toc-document-panel'
			},
			el( RadioControl, {
				label: __( 'TOC for this entry', 'prospero-theme' ),
				selected: current,
				options: [
					{
						label: sprintf(
							/* translators: %s: Resolved Customizer default label. */
							__( 'Use theme default (%s)', 'prospero-theme' ),
							defaultLabel
						),
						value: ''
					},
					{
						label: __( 'Enable for this entry', 'prospero-theme' ),
						value: 'enabled'
					},
					{
						label: __( 'Disable for this entry', 'prospero-theme' ),
						value: 'disabled'
					}
				],
				onChange: function ( value ) {
					setMeta( Object.assign( {}, meta, {
						_prospero_toc_status: value || ''
					} ) );
				},
				help: __( 'Overrides the Customizer auto-insert setting. You can still place [prospero_toc] manually unless this entry is disabled.', 'prospero-theme' )
			} )
		);
	}

	registerPlugin( 'prospero-toc', {
		render: function () {
			return el( Fragment, null, el( TocDocumentPanel ) );
		}
	} );
} )( window.wp );
