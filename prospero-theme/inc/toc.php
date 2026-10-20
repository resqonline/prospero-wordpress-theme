<?php
/**
 * Table of Contents
 *
 * Builds a heading-based TOC for posts and pages with multipage support.
 * Auto-insert is opt-in via Customizer; per-entry override is Gutenberg-ready.
 *
 * @package Prospero
 * @since 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sanitize TOC per-entry meta to one of the three allowed states.
 *
 * @param mixed $value Raw input.
 * @return string '', 'enabled' or 'disabled'.
 */
function prospero_sanitize_toc_status( $value ) {
	$allowed = array( '', 'enabled', 'disabled' );
	return in_array( $value, $allowed, true ) ? $value : '';
}

/**
 * Register TOC meta for REST / block editor compatibility.
 */
function prospero_register_toc_meta() {
	$args = array(
		'show_in_rest'      => true,
		'single'            => true,
		'type'              => 'string',
		'default'           => '',
		'sanitize_callback' => 'prospero_sanitize_toc_status',
		'auth_callback'     => function () {
			return current_user_can( 'edit_posts' );
		},
	);

	register_post_meta( 'post', '_prospero_toc_status', $args );
	register_post_meta( 'page', '_prospero_toc_status', $args );
}
add_action( 'init', 'prospero_register_toc_meta' );

/**
 * Resolve the current singular post for TOC logic.
 *
 * Prefers get_queried_object() so multilingual plugins that temporarily
 * swap global $post during the_content do not pick the wrong entry.
 *
 * @return WP_Post|null
 */
function prospero_toc_get_post() {
	$queried = get_queried_object();
	if ( $queried instanceof WP_Post ) {
		return $queried;
	}

	global $post;
	return ( $post instanceof WP_Post ) ? $post : null;
}

/**
 * Whether the post content contains a manual TOC shortcode.
 *
 * @param WP_Post|null $post Post object.
 * @return bool
 */
function prospero_toc_has_shortcode( $post = null ) {
	$post = $post instanceof WP_Post ? $post : prospero_toc_get_post();
	if ( ! $post ) {
		return false;
	}

	return has_shortcode( $post->post_content, 'prospero_toc' )
		|| has_shortcode( $post->post_content, 'toc' );
}

/**
 * Whether TOC output is forced off for this entry.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function prospero_toc_is_disabled( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_queried_object_id();
	return 'disabled' === get_post_meta( $post_id, '_prospero_toc_status', true );
}

/**
 * Whether auto-insert should run for this entry.
 *
 * Per-entry "enabled" / "disabled" overrides the Customizer default.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function prospero_toc_should_auto_insert( $post_id = null ) {
	$post_id = $post_id ? (int) $post_id : get_queried_object_id();
	$status  = get_post_meta( $post_id, '_prospero_toc_status', true );

	if ( 'disabled' === $status ) {
		return false;
	}

	if ( 'enabled' === $status ) {
		return true;
	}

	return (bool) get_theme_mod( 'prospero_toc_auto_insert', false );
}

/**
 * Whether heading anchors should be injected for this request.
 *
 * Anchors are needed for auto-insert and for a manually placed shortcode.
 *
 * @param int|null $post_id Optional post ID.
 * @return bool
 */
function prospero_toc_should_add_anchors( $post_id = null ) {
	if ( prospero_toc_is_disabled( $post_id ) ) {
		return false;
	}

	return prospero_toc_should_auto_insert( $post_id ) || prospero_toc_has_shortcode();
}

/**
 * Extract an existing id attribute from a heading opening-tag attribute string.
 *
 * @param string $attrs Attribute string from an <hN ...> tag.
 * @return string Empty when no usable id is present.
 */
function prospero_toc_extract_existing_id( $attrs ) {
	if ( preg_match( '/\bid\s*=\s*(?:"([^"]*)"|\'([^\']*)\'|([^\s>]+))/i', $attrs, $m ) ) {
		$id = isset( $m[1] ) && '' !== $m[1] ? $m[1] : ( isset( $m[2] ) && '' !== $m[2] ? $m[2] : ( isset( $m[3] ) ? $m[3] : '' ) );
		$id = trim( html_entity_decode( $id, ENT_QUOTES | ENT_HTML5, 'UTF-8' ) );
		return $id;
	}
	return '';
}

/**
 * Reserve a slug in the used-slugs map (for existing ids and generated ids).
 *
 * @param string $slug       Slug / id to reserve.
 * @param array  $used_slugs Reference array of slugs already used.
 */
function prospero_toc_reserve_slug( $slug, &$used_slugs ) {
	if ( '' === $slug ) {
		return;
	}
	if ( ! isset( $used_slugs[ $slug ] ) ) {
		$used_slugs[ $slug ] = 0;
	}
}

/**
 * Generate a URL-safe anchor slug from heading content.
 *
 * Limits to the first 5 words and suffixes duplicates so shortcode links
 * stay in sync with the_content anchor injection.
 *
 * @param string $text        Heading content (may contain HTML).
 * @param array  $used_slugs  Reference array of slugs already used.
 * @return string Slug without the prospero-h- prefix.
 */
function prospero_heading_slug( $text, &$used_slugs ) {
	// Strip anchor elements (Gutenberg heading links) before slugifying.
	$clean = preg_replace( '/<a\b[^>]*>.*?<\/a>/is', '', $text );
	$plain = wp_strip_all_tags( $clean );

	$words = preg_split( '/\s+/u', trim( $plain ), 6, PREG_SPLIT_NO_EMPTY );
	$plain = implode( ' ', array_slice( (array) $words, 0, 5 ) );

	$slug = sanitize_title( $plain );
	if ( '' === $slug ) {
		$slug = 'section';
	}

	// Avoid colliding with a reserved bare slug or a reserved prospero-h-* id.
	$candidate = $slug;
	$i         = 0;
	while (
		isset( $used_slugs[ $candidate ] )
		|| isset( $used_slugs[ 'prospero-h-' . $candidate ] )
	) {
		$i++;
		$candidate = $slug . '-' . $i;
	}

	$used_slugs[ $candidate ] = 0;
	return $candidate;
}

/**
 * Resolve the final element id for a heading (existing or generated).
 *
 * @param string $attrs       Opening-tag attributes.
 * @param string $inner_html  Heading inner HTML.
 * @param array  $used_slugs  Reference array of slugs/ids already used.
 * @return array{id:string,attrs:string,generated:bool}
 */
function prospero_toc_resolve_heading_id( $attrs, $inner_html, &$used_slugs ) {
	$existing = prospero_toc_extract_existing_id( $attrs );
	if ( '' !== $existing ) {
		prospero_toc_reserve_slug( $existing, $used_slugs );
		return array(
			'id'        => $existing,
			'attrs'     => $attrs,
			'generated' => false,
		);
	}

	$slug  = prospero_heading_slug( $inner_html, $used_slugs );
	$id    = 'prospero-h-' . $slug;
	// Track the full id too so later existing ids cannot collide with it.
	prospero_toc_reserve_slug( $id, $used_slugs );

	return array(
		'id'        => $id,
		'attrs'     => $attrs,
		'generated' => true,
	);
}

/**
 * Add stable anchor IDs to h2-h6 headings that do not already have one.
 *
 * @param string $content Post content.
 * @return string
 */
function prospero_add_heading_anchors( $content ) {
	if ( ! is_singular( array( 'post', 'page' ) ) || ! prospero_toc_should_add_anchors() ) {
		return $content;
	}

	$used_slugs = array();

	return preg_replace_callback(
		'/<h([2-6])([^>]*)>(.*?)<\/h\1>/is',
		function ( $m ) use ( &$used_slugs ) {
			$resolved = prospero_toc_resolve_heading_id( $m[2], $m[3], $used_slugs );

			if ( ! $resolved['generated'] ) {
				// Keep author / Gutenberg ids untouched.
				return $m[0];
			}

			return '<h' . $m[1] . ' id="' . esc_attr( $resolved['id'] ) . '"' . $resolved['attrs'] . '>'
				. $m[3]
				. '</h' . $m[1] . '>';
		},
		$content
	);
}
add_filter( 'the_content', 'prospero_add_heading_anchors', 9 );

/**
 * Build multipage base permalink for TOC / page-nav links.
 *
 * @param WP_Post $post Post object.
 * @return string
 */
function prospero_toc_base_url( $post ) {
	if ( is_preview() ) {
		$preview = get_preview_post_link( $post );
		if ( $preview ) {
			return $preview;
		}
	}

	return get_permalink( $post );
}

/**
 * Build a multipage URL for a given page number (and optional hash).
 *
 * Mirrors core `_wp_link_page()` but uses a stable post object so multilingual
 * plugins swapping global $post during the_content cannot poison the URL.
 *
 * @param WP_Post $post     Post object.
 * @param int     $page_num 1-based page number.
 * @param string  $anchor   Optional hash target without leading #.
 * @return string
 */
function prospero_toc_page_url( $post, $page_num, $anchor = '' ) {
	global $wp_rewrite;

	$page_num = max( 1, (int) $page_num );
	$base     = prospero_toc_base_url( $post );

	if ( $page_num <= 1 ) {
		$url = $base;
	} elseif ( '' === get_option( 'permalink_structure' ) || in_array( $post->post_status, array( 'draft', 'pending', 'auto-draft' ), true ) ) {
		$url = add_query_arg( 'page', $page_num, $base );
	} elseif ( 'page' === get_option( 'show_on_front' ) && (int) get_option( 'page_on_front' ) === (int) $post->ID ) {
		$url = trailingslashit( $base ) . user_trailingslashit( $wp_rewrite->pagination_base . '/' . $page_num, 'single_paged' );
	} else {
		$url = trailingslashit( $base ) . user_trailingslashit( (string) $page_num, 'single_paged' );
	}

	if ( '' !== $anchor ) {
		$url .= '#' . $anchor;
	}

	return $url;
}

/**
 * Escape an href that may be a bare hash or a full URL.
 *
 * esc_url() strips leading-hash values, which breaks same-page TOC links.
 *
 * @param string $href Href value.
 * @return string
 */
function prospero_toc_esc_href( $href ) {
	if ( is_string( $href ) && isset( $href[0] ) && '#' === $href[0] ) {
		return esc_attr( $href );
	}

	return esc_url( $href );
}

/**
 * [prospero_toc] shortcode — table of contents with multipage support.
 *
 * Attributes:
 *   levels       Comma-separated heading levels. Default '2,3'.
 *   title        Label above the list. Default 'Table of Contents'.
 *   min_headings Minimum headings required before rendering. Default 2.
 *   collapsed    Non-empty / non-false wraps the TOC in <details>.
 *
 * Alias: [toc]
 *
 * @param array|string $atts Shortcode attributes.
 * @return string
 */
function prospero_toc_shortcode( $atts ) {
	$post = prospero_toc_get_post();
	if ( ! $post || prospero_toc_is_disabled( $post->ID ) ) {
		return '';
	}

	$atts = shortcode_atts(
		array(
			'levels'       => '2,3',
			'title'        => __( 'Table of Contents', 'prospero-theme' ),
			'min_headings' => 2,
			'collapsed'    => '',
		),
		$atts,
		'prospero_toc'
	);

	$is_collapsed = ! empty( $atts['collapsed'] ) && 'false' !== (string) $atts['collapsed'];

	$levels = array_filter( array_map( 'intval', explode( ',', $atts['levels'] ) ) );
	$levels = array_values( array_intersect( $levels, array( 2, 3, 4, 5, 6 ) ) );
	if ( empty( $levels ) ) {
		$levels = array( 2, 3 );
	}

	// Classic <!--nextpage--> and Gutenberg <!-- wp:nextpage /-->.
	$pages       = preg_split( '/<!--nextpage-->|<!--\s*wp:nextpage\s*\/-->/', $post->post_content );
	$total_pages = count( $pages );
	$toc_items   = array();

	global $page;
	$current_post_page = max( 1, (int) $page );

	foreach ( $pages as $page_index => $page_content ) {
		$page_num   = $page_index + 1;
		$used_slugs = array();

		// Walk every h2-h6 so slug counters stay aligned with anchor injection,
		// even when the shortcode only lists a subset of levels.
		preg_match_all(
			'/<h([2-6])([^>]*)>(.*?)<\/h\1>/is',
			$page_content,
			$matches,
			PREG_SET_ORDER
		);

		foreach ( $matches as $match ) {
			$level        = (int) $match[1];
			$heading_html = $match[3];
			$heading_text = wp_strip_all_tags(
				preg_replace( '/<a\b[^>]*>.*?<\/a>/is', '', $heading_html )
			);
			$resolved = prospero_toc_resolve_heading_id( $match[2], $heading_html, $used_slugs );

			if ( ! in_array( $level, $levels, true ) ) {
				continue;
			}
			if ( '' === trim( $heading_text ) ) {
				continue;
			}

			$anchor_id  = $resolved['id'];
			$cross_page = ( $total_pages > 1 && $page_num !== $current_post_page );
			$link       = $cross_page
				? prospero_toc_page_url( $post, $page_num, $anchor_id )
				: '#' . $anchor_id;

			$toc_items[] = array(
				'level' => $level,
				'text'  => $heading_text,
				'link'  => $link,
				'page'  => $page_num,
			);
		}
	}

	if ( count( $toc_items ) < (int) $atts['min_headings'] ) {
		return '';
	}

	ob_start();

	$html  = '<nav class="prospero-toc" aria-label="' . esc_attr__( 'Table of Contents', 'prospero-theme' ) . '">';
	if ( ! $is_collapsed && ! empty( $atts['title'] ) ) {
		$html .= '<p class="prospero-toc-title">' . esc_html( $atts['title'] ) . '</p>';
	}
	$html .= '<ul class="prospero-toc-list">';

	$prev_level      = 0;
	$sub_open        = false;
	$current_page_in = 0;

	foreach ( $toc_items as $item ) {
		if ( $total_pages > 1 && $item['page'] !== $current_page_in ) {
			if ( $item['page'] > 1 ) {
				if ( $sub_open ) {
					$html    .= '</li></ul></li>';
					$sub_open = false;
				} elseif ( $prev_level > 0 ) {
					$html .= '</li>';
				}
				$prev_level = 0;
				$html      .= '<li class="prospero-toc-page-marker" aria-hidden="true">'
					. sprintf(
						/* translators: %d: page number within a multipage post. */
						esc_html__( 'Page %d', 'prospero-theme' ),
						(int) $item['page']
					)
					. '</li>';
			}
			$current_page_in = $item['page'];
		}

		$a_open = '<a href="' . prospero_toc_esc_href( $item['link'] ) . '">';

		// Nested list: h2 top-level, h3+ indented under the previous h2.
		if ( 2 === $item['level'] ) {
			if ( $sub_open ) {
				$html    .= '</li></ul></li>';
				$sub_open = false;
			} elseif ( 2 === $prev_level ) {
				$html .= '</li>';
			}
			$html      .= '<li>' . $a_open . esc_html( $item['text'] ) . '</a>';
			$prev_level = 2;
		} else {
			if ( ! $sub_open && 2 === $prev_level ) {
				$html    .= '<ul>';
				$sub_open = true;
			} elseif ( $sub_open || $prev_level > 2 ) {
				$html .= '</li>';
			} elseif ( 0 === $prev_level ) {
				// Orphan lower-level heading before any h2.
				$html .= '<li>' . $a_open . esc_html( $item['text'] ) . '</a>';
				$prev_level = $item['level'];
				continue;
			}
			$html      .= '<li>' . $a_open . esc_html( $item['text'] ) . '</a>';
			$prev_level = $item['level'];
		}
	}

	if ( $sub_open ) {
		$html .= '</li></ul></li>';
	} elseif ( $prev_level > 0 ) {
		$html .= '</li>';
	}

	$html .= '</ul></nav>';

	if ( $is_collapsed ) {
		$label = ! empty( $atts['title'] ) ? $atts['title'] : __( 'Table of Contents', 'prospero-theme' );
		$html  = '<details class="prospero-toc-collapsible">'
			. '<summary class="prospero-toc-summary">' . esc_html( $label ) . '</summary>'
			. $html
			. '</details>';
	}

	// Page navigation stays outside the collapsible so it remains visible.
	if ( $total_pages > 1 ) {
		$page_nav  = '<nav class="prospero-page-links" aria-label="' . esc_attr__( 'Pages', 'prospero-theme' ) . '">';
		$page_nav .= '<span class="prospero-page-links-label">' . esc_html__( 'Pages: ', 'prospero-theme' ) . '</span>';
		for ( $pn = 1; $pn <= $total_pages; $pn++ ) {
			if ( $pn === $current_post_page ) {
				$page_nav .= '<span aria-current="page">' . $pn . '</span>';
			} else {
				$page_nav .= '<span><a href="' . esc_url( prospero_toc_page_url( $post, $pn ) ) . '">' . $pn . '</a></span>';
			}
		}
		$page_nav .= '</nav>';
		$html     .= $page_nav;
	}

	ob_end_clean();

	return $html;
}
add_shortcode( 'prospero_toc', 'prospero_toc_shortcode' );
add_shortcode( 'toc', 'prospero_toc_shortcode' );

/**
 * Auto-insert TOC after the first paragraph (page 1) or collapsed at top (pages 2+).
 *
 * @param string $content Post content.
 * @return string
 */
function prospero_toc_auto_insert( $content ) {
	static $done = false;

	if ( $done || ! in_the_loop() || ! is_main_query() ) {
		return $content;
	}

	if ( ! is_singular( array( 'post', 'page' ) ) || ! prospero_toc_should_auto_insert() ) {
		return $content;
	}

	$post = prospero_toc_get_post();
	if ( $post && prospero_toc_has_shortcode( $post ) ) {
		return $content;
	}

	global $page;
	$current_post_page = max( 1, (int) $page );

	if ( $current_post_page > 1 ) {
		$toc_html = prospero_toc_shortcode( array( 'collapsed' => '1' ) );
		if ( $toc_html ) {
			$content = $toc_html . $content;
		}
	} else {
		$toc_html = prospero_toc_shortcode( array() );
		if ( $toc_html ) {
			$pos     = strpos( $content, '</p>' );
			$content = false !== $pos
				? substr( $content, 0, $pos + 4 ) . $toc_html . substr( $content, $pos + 4 )
				: $toc_html . $content;
		}
	}

	$done = true;
	return $content;
}
add_filter( 'the_content', 'prospero_toc_auto_insert', 10 );

/**
 * Register classic / block-editor sidebar meta box.
 */
function prospero_toc_meta_box_register() {
	add_meta_box(
		'prospero_toc_settings',
		esc_html__( 'Table of Contents', 'prospero-theme' ),
		'prospero_toc_meta_box_render',
		array( 'post', 'page' ),
		'side',
		'default',
		array(
			// Block editor uses assets/js/toc-editor.js document panel instead.
			'__back_compat_meta_box' => true,
		)
	);
}
add_action( 'add_meta_boxes', 'prospero_toc_meta_box_register' );

/**
 * Meta box callback.
 *
 * @param WP_Post $post Current post.
 */
function prospero_toc_meta_box_render( $post ) {
	wp_nonce_field( 'prospero_toc_save', 'prospero_toc_nonce' );

	$current = get_post_meta( $post->ID, '_prospero_toc_status', true );
	$default_label = get_theme_mod( 'prospero_toc_auto_insert', false )
		? esc_html__( 'Auto-insert on', 'prospero-theme' )
		: esc_html__( 'Auto-insert off', 'prospero-theme' );
	?>
	<p>
		<label>
			<input type="radio" name="prospero_toc_status" value="" <?php checked( $current, '' ); ?> />
			<?php
			printf(
				/* translators: %s: Resolved Customizer default label. */
				esc_html__( 'Use theme default (%s)', 'prospero-theme' ),
				esc_html( $default_label )
			);
			?>
		</label>
	</p>
	<p>
		<label>
			<input type="radio" name="prospero_toc_status" value="enabled" <?php checked( $current, 'enabled' ); ?> />
			<?php esc_html_e( 'Enable for this entry', 'prospero-theme' ); ?>
		</label>
	</p>
	<p>
		<label>
			<input type="radio" name="prospero_toc_status" value="disabled" <?php checked( $current, 'disabled' ); ?> />
			<?php esc_html_e( 'Disable for this entry', 'prospero-theme' ); ?>
		</label>
	</p>
	<p class="description">
		<?php esc_html_e( 'Overrides the Customizer auto-insert setting. You can still place [prospero_toc] manually unless this entry is disabled.', 'prospero-theme' ); ?>
	</p>
	<?php
}

/**
 * Save TOC meta from the classic editor form.
 *
 * @param int $post_id Current post ID.
 */
function prospero_toc_meta_box_save( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! isset( $_POST['prospero_toc_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['prospero_toc_nonce'] ) ), 'prospero_toc_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$value = isset( $_POST['prospero_toc_status'] )
		? prospero_sanitize_toc_status( wp_unslash( $_POST['prospero_toc_status'] ) )
		: '';

	if ( '' === $value ) {
		delete_post_meta( $post_id, '_prospero_toc_status' );
	} else {
		update_post_meta( $post_id, '_prospero_toc_status', $value );
	}
}
add_action( 'save_post', 'prospero_toc_meta_box_save' );

/**
 * Enqueue block editor document panel for TOC per-entry override.
 */
function prospero_toc_enqueue_editor_assets() {
	$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
	if ( ! $screen || ! in_array( $screen->base, array( 'post' ), true ) ) {
		return;
	}
	if ( ! in_array( $screen->post_type, array( 'post', 'page' ), true ) ) {
		return;
	}

	wp_enqueue_script(
		'prospero-toc-editor',
		PROSPERO_THEME_URI . '/assets/js/toc-editor.js',
		array(
			'wp-plugins',
			'wp-edit-post',
			'wp-element',
			'wp-components',
			'wp-data',
			'wp-core-data',
			'wp-i18n',
		),
		PROSPERO_VERSION,
		true
	);

	wp_localize_script(
		'prospero-toc-editor',
		'prosperoTocEditor',
		array(
			'autoInsertDefault' => (bool) get_theme_mod( 'prospero_toc_auto_insert', false ),
		)
	);

	if ( function_exists( 'wp_set_script_translations' ) ) {
		wp_set_script_translations( 'prospero-toc-editor', 'prospero-theme' );
	}
}
add_action( 'enqueue_block_editor_assets', 'prospero_toc_enqueue_editor_assets' );
