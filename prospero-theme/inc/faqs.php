<?php
/**
 * FAQ-specific enhancements
 *
 * Adds a "Hide from all-FAQs view" checkbox to every `faq_category`
 * term (both the Add New and Edit screens), and uses that flag to
 * exclude matching FAQs from:
 *
 *   - The FAQ archive (`archive-faq.php` / is_post_type_archive('faq'))
 *   - The FAQ list block when no specific category is selected
 *
 * A hidden category also hides every descendant term under it, so
 * flagging a parent category is enough to hide the whole sub-tree.
 *
 * Direct links continue to work: the taxonomy page for a hidden
 * category still shows its FAQs, and selecting the hidden category
 * explicitly in a block still displays them.
 *
 * @package Prospero
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Term meta key for the hide flag.
 */
const PROSPERO_FAQ_CATEGORY_HIDDEN_META_KEY = '_prospero_faq_category_hidden_from_all';

/**
 * Register the term meta so it has a canonical schema (and surfaces
 * in REST for anyone building a block editor integration later).
 */
function prospero_register_faq_category_meta() {
	register_term_meta(
		'faq_category',
		PROSPERO_FAQ_CATEGORY_HIDDEN_META_KEY,
		array(
			'type'              => 'boolean',
			'single'            => true,
			'show_in_rest'      => true,
			'sanitize_callback' => 'rest_sanitize_boolean',
			'auth_callback'     => static function () {
				return current_user_can( 'manage_categories' );
			},
		)
	);
}
add_action( 'init', 'prospero_register_faq_category_meta' );

/**
 * Render the checkbox on the "Add New FAQ Category" screen.
 */
function prospero_faq_category_add_form_field() {
	?>
	<div class="form-field term-prospero-hide-from-all-wrap">
		<label>
			<input
				type="checkbox"
				name="prospero_faq_category_hidden"
				value="1"
			/>
			<?php esc_html_e( 'Hide from all-FAQs view', 'prospero-theme' ); ?>
		</label>
		<p class="description">
			<?php esc_html_e( 'If checked, FAQs in this category (and any of its sub-categories) will not appear when the FAQ archive or the FAQ list block shows all FAQs. They remain visible on the category\'s own taxonomy page and when this category is explicitly selected in a block.', 'prospero-theme' ); ?>
		</p>
	</div>
	<?php
}
add_action( 'faq_category_add_form_fields', 'prospero_faq_category_add_form_field' );

/**
 * Render the checkbox on the "Edit FAQ Category" screen.
 *
 * @param WP_Term $term Current term object.
 */
function prospero_faq_category_edit_form_field( $term ) {
	$hidden = (bool) get_term_meta( $term->term_id, PROSPERO_FAQ_CATEGORY_HIDDEN_META_KEY, true );
	?>
	<tr class="form-field term-prospero-hide-from-all-wrap">
		<th scope="row">
			<label for="prospero_faq_category_hidden">
				<?php esc_html_e( 'Hide from all-FAQs view', 'prospero-theme' ); ?>
			</label>
		</th>
		<td>
			<label>
				<input
					type="checkbox"
					name="prospero_faq_category_hidden"
					id="prospero_faq_category_hidden"
					value="1"
					<?php checked( $hidden ); ?>
				/>
				<?php esc_html_e( 'Hide FAQs in this category from the all-FAQs view', 'prospero-theme' ); ?>
			</label>
			<p class="description">
				<?php esc_html_e( 'If checked, FAQs in this category (and any of its sub-categories) will not appear when the FAQ archive or the FAQ list block shows all FAQs. They remain visible on the category\'s own taxonomy page and when this category is explicitly selected in a block.', 'prospero-theme' ); ?>
			</p>
		</td>
	</tr>
	<?php
}
add_action( 'faq_category_edit_form_fields', 'prospero_faq_category_edit_form_field' );

/**
 * Persist the hide flag when a faq_category term is created or edited.
 *
 * @param int $term_id Term ID.
 */
function prospero_save_faq_category_meta( $term_id ) {
	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	// The term edit screens are already nonce-protected by WP core;
	// we just mirror the submitted value into term meta.
	$is_hidden = ! empty( $_POST['prospero_faq_category_hidden'] );

	if ( $is_hidden ) {
		update_term_meta( $term_id, PROSPERO_FAQ_CATEGORY_HIDDEN_META_KEY, 1 );
	} else {
		delete_term_meta( $term_id, PROSPERO_FAQ_CATEGORY_HIDDEN_META_KEY );
	}
}
add_action( 'created_faq_category', 'prospero_save_faq_category_meta' );
add_action( 'edited_faq_category', 'prospero_save_faq_category_meta' );

/**
 * Return the faq_category term IDs that should be excluded from
 * "all FAQs" queries. Includes both the explicitly hidden terms and
 * every descendant (hidden inherits down the tree).
 *
 * Cached once per request via a static to avoid re-querying for each
 * block / query filter invocation.
 *
 * @return int[]
 */
function prospero_get_hidden_faq_category_ids() {
	static $cache = null;
	if ( null !== $cache ) {
		return $cache;
	}

	$all_terms = get_terms(
		array(
			'taxonomy'   => 'faq_category',
			'hide_empty' => false,
		)
	);

	if ( is_wp_error( $all_terms ) || empty( $all_terms ) ) {
		$cache = array();
		return $cache;
	}

	$roots = array();
	foreach ( $all_terms as $term ) {
		if ( get_term_meta( $term->term_id, PROSPERO_FAQ_CATEGORY_HIDDEN_META_KEY, true ) ) {
			$roots[] = (int) $term->term_id;
		}
	}

	if ( empty( $roots ) ) {
		$cache = array();
		return $cache;
	}

	$all = $roots;
	foreach ( $roots as $root ) {
		$children = get_term_children( $root, 'faq_category' );
		if ( is_wp_error( $children ) || empty( $children ) ) {
			continue;
		}
		foreach ( $children as $child ) {
			$all[] = (int) $child;
		}
	}

	$cache = array_values( array_unique( $all ) );
	return $cache;
}

/**
 * Exclude FAQs in hidden categories from the FAQ archive main query.
 *
 * Taxonomy archives (/faq-category/{term}) are intentionally NOT
 * affected - visiting a hidden category directly still shows its
 * contents. Admin and non-main queries are skipped.
 *
 * @param WP_Query $query Current query.
 */
function prospero_filter_faq_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}

	if ( ! $query->is_post_type_archive( 'faq' ) ) {
		return;
	}

	$hidden_ids = prospero_get_hidden_faq_category_ids();
	if ( empty( $hidden_ids ) ) {
		return;
	}

	$tax_query = $query->get( 'tax_query' );
	if ( ! is_array( $tax_query ) ) {
		$tax_query = array();
	}
	$tax_query[] = array(
		'taxonomy' => 'faq_category',
		'field'    => 'term_id',
		'terms'    => $hidden_ids,
		'operator' => 'NOT IN',
	);
	$query->set( 'tax_query', $tax_query );
}
add_action( 'pre_get_posts', 'prospero_filter_faq_archive_query' );
