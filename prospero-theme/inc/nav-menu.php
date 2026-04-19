<?php
/**
 * Navigation menu enhancements
 *
 * - Adds a "Display as CTA button" checkbox to every menu item in
 *   Appearance -> Menus. When checked, the menu item renders with the
 *   Menu CTA button styling (see Customizer -> Button Styles ->
 *   Menu CTA Button and the --prospero-btn-menu-cta-* variables).
 * - Persists the flag as post meta on the nav menu item.
 * - Injects the `menu-item-cta` class on the <li> via
 *   `nav_menu_css_class` so no custom walker is required (works with
 *   both the default walker and the theme's mobile panel walker).
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meta key used to store the CTA flag on nav menu items.
 */
const PROSPERO_MENU_CTA_META_KEY = '_prospero_menu_cta';

/**
 * Render the "Display as CTA button" checkbox on each menu item in
 * Appearance -> Menus.
 *
 * The long helper text used to sit inline next to the label which
 * squished the row. It is now tucked behind a compact info icon
 * (dashicons-editor-help) and revealed on hover / focus as a small
 * popover. The description element stays in the DOM so screen readers
 * still find it via the button's `aria-describedby` association.
 *
 * @param int $item_id Menu item ID.
 */
function prospero_nav_menu_cta_field( $item_id ) {
	$is_cta      = (bool) get_post_meta( $item_id, PROSPERO_MENU_CTA_META_KEY, true );
	$tooltip_id  = 'prospero-menu-cta-help-' . $item_id;
	$description = esc_html__( 'Renders this menu item as the Menu CTA button. Configure its appearance under Appearance > Customize > Button Styles > Menu CTA Button.', 'prospero-theme' );
	?>
	<p class="field-prospero-menu-cta description description-wide">
		<label for="edit-menu-item-prospero-cta-<?php echo esc_attr( $item_id ); ?>">
			<input
				type="checkbox"
				id="edit-menu-item-prospero-cta-<?php echo esc_attr( $item_id ); ?>"
				class="edit-menu-item-prospero-cta"
				name="menu-item-prospero-cta[<?php echo esc_attr( $item_id ); ?>]"
				value="1"
				<?php checked( $is_cta ); ?>
			/>
			<?php esc_html_e( 'Display as CTA button', 'prospero-theme' ); ?>
		</label>
		<button
			type="button"
			class="prospero-menu-help-toggle"
			aria-label="<?php esc_attr_e( 'What does "Display as CTA button" do?', 'prospero-theme' ); ?>"
			aria-describedby="<?php echo esc_attr( $tooltip_id ); ?>"
		>
			<span class="dashicons dashicons-editor-help" aria-hidden="true"></span>
		</button>
		<span class="prospero-menu-help-tooltip" id="<?php echo esc_attr( $tooltip_id ); ?>" role="tooltip">
			<?php echo $description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped above via esc_html__. ?>
		</span>
	</p>
	<?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'prospero_nav_menu_cta_field' );

/**
 * Enqueue the admin stylesheet that drives the help icon + tooltip on
 * the nav-menus screen. Scoped to `nav-menus.php` only so we don't ship
 * the CSS to every admin page.
 *
 * Dashicons are loaded globally by WP admin so we don't need to
 * enqueue them separately, but we declare the dependency explicitly
 * in case WP ever changes that default.
 *
 * @param string $hook Current admin screen hook.
 */
function prospero_enqueue_nav_menu_admin_assets( $hook ) {
	if ( 'nav-menus.php' !== $hook ) {
		return;
	}

	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style(
		'prospero-admin-nav-menu',
		PROSPERO_THEME_URI . '/assets/css/admin-nav-menu.css',
		array( 'dashicons' ),
		PROSPERO_VERSION
	);
}
add_action( 'admin_enqueue_scripts', 'prospero_enqueue_nav_menu_admin_assets' );

/**
 * Persist the CTA flag on `wp_update_nav_menu_item`.
 *
 * @param int   $menu_id         The menu ID (unused).
 * @param int   $menu_item_db_id The menu item ID.
 */
function prospero_save_nav_menu_cta_field( $menu_id, $menu_item_db_id ) {
	// Permission check handled by WP core on this hook, but double-check.
	if ( ! current_user_can( 'edit_theme_options' ) ) {
		return;
	}

	// Nonce check is implicit here because this action is fired from within
	// `wp_update_nav_menu_item()` which is itself called from the menu edit
	// screen that WP verifies; avoid false negatives from custom bulk tools.

	$posted = isset( $_POST['menu-item-prospero-cta'] ) && is_array( $_POST['menu-item-prospero-cta'] )
		? wp_unslash( $_POST['menu-item-prospero-cta'] )
		: array();

	if ( isset( $posted[ $menu_item_db_id ] ) && '1' === (string) $posted[ $menu_item_db_id ] ) {
		update_post_meta( $menu_item_db_id, PROSPERO_MENU_CTA_META_KEY, 1 );
	} else {
		delete_post_meta( $menu_item_db_id, PROSPERO_MENU_CTA_META_KEY );
	}
}
add_action( 'wp_update_nav_menu_item', 'prospero_save_nav_menu_cta_field', 10, 2 );

/**
 * Inject the `menu-item-cta` class on the <li> for menu items that
 * have the CTA flag set. Works with any walker because it hooks the
 * generic `nav_menu_css_class` filter.
 *
 * @param string[] $classes Array of the menu item's classes.
 * @param WP_Post  $item    The current menu item.
 * @return string[] Filtered classes.
 */
function prospero_nav_menu_cta_class( $classes, $item ) {
	if ( isset( $item->ID ) && get_post_meta( $item->ID, PROSPERO_MENU_CTA_META_KEY, true ) ) {
		if ( ! in_array( 'menu-item-cta', $classes, true ) ) {
			$classes[] = 'menu-item-cta';
		}
	}
	return $classes;
}
add_filter( 'nav_menu_css_class', 'prospero_nav_menu_cta_class', 10, 2 );

/**
 * Show the FAQ post type's nav menu meta box by default on the
 * Appearance -> Menus screen. WP normally hides custom post type
 * meta boxes and users have to tick them in Screen Options first,
 * which hides an important detail: because the FAQ CPT is registered
 * with `has_archive: true`, its meta box contains a top-level
 * "Archives" entry that lets users add the FAQ archive page to any
 * menu with a single click. Un-hiding it by default makes that
 * entry point discoverable out of the box.
 *
 * @param string[]  $hidden Meta box IDs hidden by default.
 * @param WP_Screen $screen Current screen object.
 * @return string[] Filtered list.
 */
function prospero_show_faq_nav_menu_metabox( $hidden, $screen ) {
	if ( isset( $screen->id ) && 'nav-menus' === $screen->id ) {
		// Meta box registered by wp_nav_menu_setup() for the FAQ CPT.
		$hidden = array_diff( $hidden, array( 'add-faq' ) );
	}
	return $hidden;
}
add_filter( 'default_hidden_meta_boxes', 'prospero_show_faq_nav_menu_metabox', 10, 2 );
