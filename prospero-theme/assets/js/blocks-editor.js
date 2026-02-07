/**
 * Prospero Custom Gutenberg Blocks - Editor Interface
 *
 * @package Prospero
 * @since 1.0.0
 */

(function() {
	'use strict';

	const { registerBlockType } = wp.blocks;
	const { InspectorControls, RichText, URLInput } = wp.blockEditor || wp.editor;
	const { PanelBody, TextControl, SelectControl, ToggleControl, ColorPicker } = wp.components;
	const { Fragment } = wp.element;
	const { __ } = wp.i18n;

	/**
	 * Call to Action Block
	 */
	registerBlockType('prospero/cta', {
		title: __('Call to Action', 'prospero-theme'),
		description: __('Display a call-to-action section with heading, text, and button', 'prospero-theme'),
		category: 'prospero',
		icon: 'megaphone',
		attributes: {
			heading: { type: 'string', default: '' },
			content: { type: 'string', default: '' },
			buttonText: { type: 'string', default: 'Learn More' },
			buttonUrl: { type: 'string', default: '' },
			buttonStyle: { type: 'string', default: 'primary' },
			layout: { type: 'string', default: 'centered' },
			bgColor: { type: 'string', default: '' }
		},
		edit: function(props) {
			return (
				Fragment({},
					InspectorControls({},
						PanelBody({ title: __('Settings', 'prospero-theme') },
							SelectControl({
								label: __('Button Style', 'prospero-theme'),
								value: props.attributes.buttonStyle,
								options: [
									{ label: __('Primary', 'prospero-theme'), value: 'primary' },
									{ label: __('Secondary', 'prospero-theme'), value: 'secondary' },
									{ label: __('Tertiary', 'prospero-theme'), value: 'tertiary' }
								],
								onChange: function(val) { props.setAttributes({ buttonStyle: val }); }
							}),
							SelectControl({
								label: __('Layout', 'prospero-theme'),
								value: props.attributes.layout,
								options: [
									{ label: __('Centered', 'prospero-theme'), value: 'centered' },
									{ label: __('Left', 'prospero-theme'), value: 'left' },
									{ label: __('Right', 'prospero-theme'), value: 'right' }
								],
								onChange: function(val) { props.setAttributes({ layout: val }); }
							})
						)
					),
					wp.element.createElement('div', { className: 'prospero-cta-editor', style: { padding: '20px', background: props.attributes.bgColor || '#f5f5f5', borderRadius: '8px' } },
						RichText({
							tagName: 'h2',
							placeholder: __('Enter heading...', 'prospero-theme'),
							value: props.attributes.heading,
							onChange: function(val) { props.setAttributes({ heading: val }); }
						}),
						RichText({
							tagName: 'p',
							placeholder: __('Enter content...', 'prospero-theme'),
							value: props.attributes.content,
							onChange: function(val) { props.setAttributes({ content: val }); }
						}),
						TextControl({
							label: __('Button Text', 'prospero-theme'),
							value: props.attributes.buttonText,
							onChange: function(val) { props.setAttributes({ buttonText: val }); }
						}),
						TextControl({
							label: __('Button URL', 'prospero-theme'),
							value: props.attributes.buttonUrl,
							onChange: function(val) { props.setAttributes({ buttonUrl: val }); }
						})
					)
				)
			);
		},
		save: function() {
			return null; // Server-side rendering
		}
	});

	/**
	 * Testimonial Single Block
	 */
	registerBlockType('prospero/testimonial-single', {
		title: __('Testimonial (Single)', 'prospero-theme'),
		description: __('Display a single testimonial', 'prospero-theme'),
		category: 'prospero',
		icon: 'format-quote',
		attributes: {
			testimonialId: { type: 'number', default: 0 },
			showImage: { type: 'boolean', default: true }
		},
		edit: function(props) {
			const testimonials = prosperoBlocks.testimonials || [];
			return (
				Fragment({},
					InspectorControls({},
						PanelBody({ title: __('Settings', 'prospero-theme') },
							SelectControl({
								label: __('Select Testimonial', 'prospero-theme'),
								value: props.attributes.testimonialId,
								options: [{ label: __('— Select —', 'prospero-theme'), value: 0 }].concat(testimonials),
								onChange: function(val) { props.setAttributes({ testimonialId: parseInt(val) }); }
							}),
							ToggleControl({
								label: __('Show Image', 'prospero-theme'),
								checked: props.attributes.showImage,
								onChange: function(val) { props.setAttributes({ showImage: val }); }
							})
						)
					),
					wp.element.createElement('div', { className: 'prospero-testimonial-editor', style: { padding: '20px', background: '#f5f5f5', borderRadius: '8px' } },
						wp.element.createElement('p', {},
							props.attributes.testimonialId ? __('Selected testimonial ID: ', 'prospero-theme') + props.attributes.testimonialId : __('Please select a testimonial from the sidebar', 'prospero-theme')
						)
					)
				)
			);
		},
		save: function() {
			return null;
		}
	});

	/**
	 * Testimonials List Block
	 */
	registerBlockType('prospero/testimonials-list', {
		title: __('Testimonials List', 'prospero-theme'),
		description: __('Display multiple testimonials', 'prospero-theme'),
		category: 'prospero',
		icon: 'format-quote',
		attributes: {
			category: { type: 'string', default: '' },
			count: { type: 'number', default: 3 },
			orderby: { type: 'string', default: 'date' }
		},
		edit: function(props) {
			return (
				Fragment({},
					InspectorControls({},
						PanelBody({ title: __('Settings', 'prospero-theme') },
							TextControl({
								label: __('Category Slug', 'prospero-theme'),
								value: props.attributes.category,
								onChange: function(val) { props.setAttributes({ category: val }); }
							}),
							TextControl({
								label: __('Count', 'prospero-theme'),
								type: 'number',
								value: props.attributes.count,
								onChange: function(val) { props.setAttributes({ count: parseInt(val) }); }
							}),
							SelectControl({
								label: __('Order By', 'prospero-theme'),
								value: props.attributes.orderby,
								options: [
									{ label: __('Date', 'prospero-theme'), value: 'date' },
									{ label: __('Title', 'prospero-theme'), value: 'title' },
									{ label: __('Random', 'prospero-theme'), value: 'rand' }
								],
								onChange: function(val) { props.setAttributes({ orderby: val }); }
							})
						)
					),
					wp.element.createElement('div', { className: 'prospero-testimonials-list-editor', style: { padding: '20px', background: '#f5f5f5', borderRadius: '8px' } },
						wp.element.createElement('p', {}, __('Testimonials List:', 'prospero-theme')),
						wp.element.createElement('ul', {},
							wp.element.createElement('li', {}, __('Count: ', 'prospero-theme') + props.attributes.count),
							wp.element.createElement('li', {}, __('Order: ', 'prospero-theme') + props.attributes.orderby)
						)
					)
				)
			);
		},
		save: function() {
			return null;
		}
	});

	/**
	 * Team Member Block
	 */
	registerBlockType('prospero/team-member', {
		title: __('Team Member', 'prospero-theme'),
		description: __('Display a single team member', 'prospero-theme'),
		category: 'prospero',
		icon: 'admin-users',
		attributes: {
			memberId: { type: 'number', default: 0 },
			layout: { type: 'string', default: 'card' }
		},
		edit: function(props) {
			const teamMembers = prosperoBlocks.teamMembers || [];
			return (
				Fragment({},
					InspectorControls({},
						PanelBody({ title: __('Settings', 'prospero-theme') },
							SelectControl({
								label: __('Select Team Member', 'prospero-theme'),
								value: props.attributes.memberId,
								options: [{ label: __('— Select —', 'prospero-theme'), value: 0 }].concat(teamMembers),
								onChange: function(val) { props.setAttributes({ memberId: parseInt(val) }); }
							}),
							SelectControl({
								label: __('Layout', 'prospero-theme'),
								value: props.attributes.layout,
								options: [
									{ label: __('Card (with excerpt)', 'prospero-theme'), value: 'card' },
									{ label: __('Simple (name only)', 'prospero-theme'), value: 'simple' }
								],
								onChange: function(val) { props.setAttributes({ layout: val }); }
							})
						)
					),
					wp.element.createElement('div', { className: 'prospero-team-member-editor', style: { padding: '20px', background: '#f5f5f5', borderRadius: '8px', textAlign: 'center' } },
						wp.element.createElement('p', {},
							props.attributes.memberId ? __('Selected member ID: ', 'prospero-theme') + props.attributes.memberId : __('Please select a team member from the sidebar', 'prospero-theme')
						)
					)
				)
			);
		},
		save: function() {
			return null;
		}
	});

	/**
	 * Member Content Block
	 */
	registerBlockType('prospero/member-content', {
		title: __('Member Content', 'prospero-theme'),
		description: __('Content visible only to logged-in users with specific roles', 'prospero-theme'),
		category: 'prospero',
		icon: 'lock',
		attributes: {
			content: { type: 'string', default: '' },
			requiredRole: { type: 'string', default: 'subscriber' },
			loginMessage: { type: 'string', default: 'Please log in to view this content.' }
		},
		edit: function(props) {
			return (
				Fragment({},
					InspectorControls({},
						PanelBody({ title: __('Settings', 'prospero-theme') },
							SelectControl({
								label: __('Required Role', 'prospero-theme'),
								value: props.attributes.requiredRole,
								options: [
									{ label: __('Subscriber', 'prospero-theme'), value: 'subscriber' },
									{ label: __('Contributor', 'prospero-theme'), value: 'contributor' },
									{ label: __('Author', 'prospero-theme'), value: 'author' },
									{ label: __('Editor', 'prospero-theme'), value: 'editor' },
									{ label: __('Administrator', 'prospero-theme'), value: 'administrator' }
								],
								onChange: function(val) { props.setAttributes({ requiredRole: val }); }
							}),
							TextControl({
								label: __('Login Message', 'prospero-theme'),
								value: props.attributes.loginMessage,
								onChange: function(val) { props.setAttributes({ loginMessage: val }); }
							})
						)
					),
					wp.element.createElement('div', { className: 'prospero-member-content-editor', style: { padding: '20px', background: '#fff3cd', borderRadius: '8px', border: '2px dashed #856404' } },
						wp.element.createElement('p', { style: { margin: '0 0 10px', fontWeight: 'bold' } },
							'🔒 ' + __('Member-Only Content', 'prospero-theme')
						),
						wp.element.createElement('p', { style: { margin: '0 0 10px', fontSize: '12px' } },
							__('Required role: ', 'prospero-theme') + props.attributes.requiredRole
						),
						RichText({
							tagName: 'div',
							placeholder: __('Enter member-only content...', 'prospero-theme'),
							value: props.attributes.content,
							onChange: function(val) { props.setAttributes({ content: val }); }
						})
					)
				)
			);
		},
		save: function() {
			return null;
		}
	});

})();
