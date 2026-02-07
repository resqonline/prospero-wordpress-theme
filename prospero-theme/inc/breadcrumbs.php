<?php
/**
 * Breadcrumbs Functionality
 *
 * Provides breadcrumb navigation with support for common SEO plugins.
 * Falls back to theme's own implementation if no supported plugin is active.
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if breadcrumbs are enabled in Customizer
 *
 * @return bool
 */
function prospero_breadcrumbs_enabled() {
	return get_theme_mod( 'prospero_enable_breadcrumbs', true );
}

/**
 * Detect which SEO plugin is active and has breadcrumbs enabled
 *
 * @return string|false Plugin identifier or false if none detected
 */
function prospero_detect_seo_plugin() {
	// Yoast SEO
	if ( function_exists( 'yoast_breadcrumb' ) ) {
		// Check if Yoast breadcrumbs are enabled
		$yoast_enabled = get_option( 'wpseo_titles' );
		if ( isset( $yoast_enabled['breadcrumbs-enable'] ) && $yoast_enabled['breadcrumbs-enable'] ) {
			return 'yoast';
		}
	}

	// Rank Math
	if ( function_exists( 'rank_math_the_breadcrumbs' ) ) {
		// Check if Rank Math breadcrumbs are enabled
		$rank_math_enabled = get_option( 'rank-math-options-general' );
		if ( isset( $rank_math_enabled['breadcrumbs'] ) && $rank_math_enabled['breadcrumbs'] ) {
			return 'rankmath';
		}
	}

	// SEOPress
	if ( function_exists( 'seopress_display_breadcrumbs' ) ) {
		$seopress_enabled = get_option( 'seopress_toggle' );
		if ( isset( $seopress_enabled['toggle-breadcrumbs'] ) && $seopress_enabled['toggle-breadcrumbs'] === '1' ) {
			return 'seopress';
		}
	}

	// The SEO Framework
	if ( function_exists( 'the_seo_framework' ) ) {
		$tsf = the_seo_framework();
		if ( method_exists( $tsf, 'get_breadcrumb_trail' ) ) {
			return 'seoframework';
		}
	}

	// All in One SEO
	if ( function_exists( 'aioseo' ) && method_exists( aioseo(), 'breadcrumbs' ) ) {
		return 'aioseo';
	}

	// Slim SEO
	if ( class_exists( 'SlimSEO\Breadcrumbs' ) ) {
		return 'slimseo';
	}

	// Breadcrumb NavXT (dedicated breadcrumb plugin)
	if ( function_exists( 'bcn_display' ) ) {
		return 'navxt';
	}

	// WooCommerce (for shop pages)
	if ( function_exists( 'woocommerce_breadcrumb' ) && function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
		return 'woocommerce';
	}

	return false;
}

/**
 * Display breadcrumbs using detected SEO plugin or theme fallback
 *
 * @param array $args Optional arguments for customization
 */
function prospero_breadcrumbs( $args = array() ) {
	// Check if breadcrumbs are enabled
	if ( ! prospero_breadcrumbs_enabled() ) {
		return;
	}

	// Don't show on front page
	if ( is_front_page() ) {
		return;
	}

	// Get settings from Customizer
	$separator_char = get_theme_mod( 'prospero_breadcrumbs_separator', '/' );
	$home_text = get_theme_mod( 'prospero_breadcrumbs_home_text', __( 'Home', 'prospero-theme' ) );

	$defaults = array(
		'wrapper_class' => 'breadcrumbs',
		'show_home'     => true,
		'home_text'     => $home_text,
		'separator'     => '<span class="breadcrumb-separator" aria-hidden="true">' . esc_html( $separator_char ) . '</span>',
	);

	$args = wp_parse_args( $args, $defaults );

	$detected_plugin = prospero_detect_seo_plugin();

	echo '<nav class="' . esc_attr( $args['wrapper_class'] ) . '" aria-label="' . esc_attr__( 'Breadcrumb', 'prospero-theme' ) . '">';

	if ( $detected_plugin ) {
		// Use SEO plugin breadcrumbs
		switch ( $detected_plugin ) {
			case 'yoast':
				yoast_breadcrumb( '<div class="breadcrumb-trail">', '</div>' );
				break;

			case 'rankmath':
				rank_math_the_breadcrumbs();
				break;

			case 'seopress':
				seopress_display_breadcrumbs();
				break;

			case 'seoframework':
				$tsf = the_seo_framework();
				if ( method_exists( $tsf, 'get_breadcrumb_trail' ) ) {
					echo $tsf->get_breadcrumb_trail(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				break;

			case 'aioseo':
				if ( method_exists( aioseo()->breadcrumbs, 'output' ) ) {
					echo aioseo()->breadcrumbs->output(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}
				break;

			case 'slimseo':
				if ( class_exists( 'SlimSEO\Breadcrumbs' ) ) {
					$breadcrumbs = new \SlimSEO\Breadcrumbs();
					$breadcrumbs->output();
				}
				break;

			case 'navxt':
				echo '<div class="breadcrumb-trail">';
				bcn_display();
				echo '</div>';
				break;

			case 'woocommerce':
				woocommerce_breadcrumb();
				break;
		}
	} else {
		// Use theme's own breadcrumbs
		prospero_render_breadcrumbs( $args );
	}

	echo '</nav>';
}

/**
 * Render theme's built-in breadcrumbs
 *
 * @param array $args Breadcrumb arguments
 */
function prospero_render_breadcrumbs( $args ) {
	$items = array();

	// Home link
	if ( $args['show_home'] ) {
		$items[] = array(
			'url'   => home_url( '/' ),
			'title' => $args['home_text'],
		);
	}

	// Build breadcrumb trail based on page type
	if ( is_singular() ) {
		$post = get_queried_object();

		// Projects - use configurable Projects page
		if ( $post->post_type === 'project' ) {
			$projects_page_id = get_theme_mod( 'prospero_projects_page', 0 );
			if ( $projects_page_id ) {
				$items[] = array(
					'url'   => get_permalink( $projects_page_id ),
					'title' => get_the_title( $projects_page_id ),
				);
			}
		}
		// Team - use configurable Team page
		elseif ( $post->post_type === 'team' ) {
			$team_page_id = get_theme_mod( 'prospero_team_page', 0 );
			if ( $team_page_id ) {
				$items[] = array(
					'url'   => get_permalink( $team_page_id ),
					'title' => get_the_title( $team_page_id ),
				);
			}
		}
		// Other custom post types (not post/page)
		elseif ( $post->post_type !== 'post' && $post->post_type !== 'page' ) {
			$post_type_obj = get_post_type_object( $post->post_type );
			if ( $post_type_obj && $post_type_obj->has_archive ) {
				$items[] = array(
					'url'   => get_post_type_archive_link( $post->post_type ),
					'title' => $post_type_obj->labels->name,
				);
			}

			// Get primary taxonomy for custom post type
			$taxonomies = get_object_taxonomies( $post->post_type, 'objects' );
			foreach ( $taxonomies as $taxonomy ) {
				if ( $taxonomy->hierarchical ) {
					$terms = get_the_terms( $post->ID, $taxonomy->name );
					if ( $terms && ! is_wp_error( $terms ) ) {
						$term = $terms[0];
						$ancestors = get_ancestors( $term->term_id, $taxonomy->name );
						$ancestors = array_reverse( $ancestors );

						foreach ( $ancestors as $ancestor_id ) {
							$ancestor = get_term( $ancestor_id, $taxonomy->name );
							$items[] = array(
								'url'   => get_term_link( $ancestor ),
								'title' => $ancestor->name,
							);
						}

						$items[] = array(
							'url'   => get_term_link( $term ),
							'title' => $term->name,
						);
					}
					break;
				}
			}
		}

		// Blog posts
		if ( $post->post_type === 'post' ) {
			// Blog page
			$blog_page_id = get_option( 'page_for_posts' );
			if ( $blog_page_id ) {
				$items[] = array(
					'url'   => get_permalink( $blog_page_id ),
					'title' => get_the_title( $blog_page_id ),
				);
			}

			// Category
			$categories = get_the_category();
			if ( $categories ) {
				$category = $categories[0];
				$ancestors = get_ancestors( $category->term_id, 'category' );
				$ancestors = array_reverse( $ancestors );

				foreach ( $ancestors as $ancestor_id ) {
					$ancestor = get_category( $ancestor_id );
					$items[] = array(
						'url'   => get_category_link( $ancestor ),
						'title' => $ancestor->name,
					);
				}

				$items[] = array(
					'url'   => get_category_link( $category ),
					'title' => $category->name,
				);
			}
		}

		// Pages - show parent hierarchy
		if ( $post->post_type === 'page' && $post->post_parent ) {
			$ancestors = get_post_ancestors( $post->ID );
			$ancestors = array_reverse( $ancestors );

			foreach ( $ancestors as $ancestor_id ) {
				$items[] = array(
					'url'   => get_permalink( $ancestor_id ),
					'title' => get_the_title( $ancestor_id ),
				);
			}
		}

		// Current page (no link)
		$items[] = array(
			'url'   => '',
			'title' => get_the_title(),
		);

	} elseif ( is_archive() ) {
		// Get blog page for post-related archives
		$blog_page_id = get_option( 'page_for_posts' );

		if ( is_category() ) {
			// Add blog page first
			if ( $blog_page_id ) {
				$items[] = array(
					'url'   => get_permalink( $blog_page_id ),
					'title' => get_the_title( $blog_page_id ),
				);
			}

			$category = get_queried_object();
			$ancestors = get_ancestors( $category->term_id, 'category' );
			$ancestors = array_reverse( $ancestors );

			foreach ( $ancestors as $ancestor_id ) {
				$ancestor = get_category( $ancestor_id );
				$items[] = array(
					'url'   => get_category_link( $ancestor ),
					'title' => $ancestor->name,
				);
			}

			$items[] = array(
				'url'   => '',
				'title' => $category->name,
			);

		} elseif ( is_tag() ) {
			// Add blog page first
			if ( $blog_page_id ) {
				$items[] = array(
					'url'   => get_permalink( $blog_page_id ),
					'title' => get_the_title( $blog_page_id ),
				);
			}

			$items[] = array(
				'url'   => '',
				'title' => single_tag_title( '', false ),
			);

		} elseif ( is_tax() ) {
			$term = get_queried_object();
			$taxonomy = get_taxonomy( $term->taxonomy );

			// Add post type archive if available
			$post_types = $taxonomy->object_type;
			if ( ! empty( $post_types ) ) {
				$post_type_obj = get_post_type_object( $post_types[0] );
				if ( $post_type_obj && $post_type_obj->has_archive ) {
					$items[] = array(
						'url'   => get_post_type_archive_link( $post_types[0] ),
						'title' => $post_type_obj->labels->name,
					);
				}
			}

			// Add term ancestors
			if ( is_taxonomy_hierarchical( $term->taxonomy ) ) {
				$ancestors = get_ancestors( $term->term_id, $term->taxonomy );
				$ancestors = array_reverse( $ancestors );

				foreach ( $ancestors as $ancestor_id ) {
					$ancestor = get_term( $ancestor_id, $term->taxonomy );
					$items[] = array(
						'url'   => get_term_link( $ancestor ),
						'title' => $ancestor->name,
					);
				}
			}

			$items[] = array(
				'url'   => '',
				'title' => $term->name,
			);

		} elseif ( is_post_type_archive() ) {
			$post_type_obj = get_queried_object();
			$items[] = array(
				'url'   => '',
				'title' => $post_type_obj->labels->name,
			);

		} elseif ( is_author() ) {
			// Add blog page first
			if ( $blog_page_id ) {
				$items[] = array(
					'url'   => get_permalink( $blog_page_id ),
					'title' => get_the_title( $blog_page_id ),
				);
			}

			$items[] = array(
				'url'   => '',
				/* translators: %s: Author name */
				'title' => sprintf( __( 'Posts by %s', 'prospero-theme' ), get_the_author() ),
			);

		} elseif ( is_date() ) {
			// Add blog page first
			if ( $blog_page_id ) {
				$items[] = array(
					'url'   => get_permalink( $blog_page_id ),
					'title' => get_the_title( $blog_page_id ),
				);
			}

			if ( is_year() ) {
				$items[] = array(
					'url'   => '',
					'title' => get_the_date( 'Y' ),
				);
			} elseif ( is_month() ) {
				$items[] = array(
					'url'   => get_year_link( get_the_date( 'Y' ) ),
					'title' => get_the_date( 'Y' ),
				);
				$items[] = array(
					'url'   => '',
					'title' => get_the_date( 'F' ),
				);
			} elseif ( is_day() ) {
				$items[] = array(
					'url'   => get_year_link( get_the_date( 'Y' ) ),
					'title' => get_the_date( 'Y' ),
				);
				$items[] = array(
					'url'   => get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ),
					'title' => get_the_date( 'F' ),
				);
				$items[] = array(
					'url'   => '',
					'title' => get_the_date( 'j' ),
				);
			}
		}

	} elseif ( is_search() ) {
		$items[] = array(
			'url'   => '',
			/* translators: %s: Search query */
			'title' => sprintf( __( 'Search results for: %s', 'prospero-theme' ), get_search_query() ),
		);

	} elseif ( is_404() ) {
		$items[] = array(
			'url'   => '',
			'title' => __( 'Page not found', 'prospero-theme' ),
		);
	}

	// Render the breadcrumb trail
	if ( ! empty( $items ) ) {
		echo '<ol class="breadcrumb-trail" itemscope itemtype="https://schema.org/BreadcrumbList">';

		$count = count( $items );
		foreach ( $items as $index => $item ) {
			$is_last = ( $index === $count - 1 );

			echo '<li class="breadcrumb-item' . ( $is_last ? ' breadcrumb-item-current' : '' ) . '" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';

			if ( ! empty( $item['url'] ) && ! $is_last ) {
				echo '<a href="' . esc_url( $item['url'] ) . '" itemprop="item">';
				echo '<span itemprop="name">' . esc_html( $item['title'] ) . '</span>';
				echo '</a>';
			} else {
				echo '<span itemprop="name">' . esc_html( $item['title'] ) . '</span>';
			}

			echo '<meta itemprop="position" content="' . ( $index + 1 ) . '" />';
			echo '</li>';

			// Add separator (except after last item)
			if ( ! $is_last ) {
				echo $args['separator']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		echo '</ol>';
	}
}

/**
 * Get the detected SEO plugin name for display purposes
 *
 * @return string
 */
function prospero_get_detected_seo_plugin_name() {
	$plugin = prospero_detect_seo_plugin();

	$names = array(
		'yoast'        => 'Yoast SEO',
		'rankmath'     => 'Rank Math',
		'seopress'     => 'SEOPress',
		'seoframework' => 'The SEO Framework',
		'aioseo'       => 'All in One SEO',
		'slimseo'      => 'Slim SEO',
		'navxt'        => 'Breadcrumb NavXT',
		'woocommerce'  => 'WooCommerce',
	);

	return isset( $names[ $plugin ] ) ? $names[ $plugin ] : '';
}

/**
 * Generate Customizer section description showing plugin status
 *
 * @return string
 */
function prospero_breadcrumbs_customizer_description() {
	$detected_plugin = prospero_get_detected_seo_plugin_name();

	if ( $detected_plugin ) {
		return sprintf(
			/* translators: %s: SEO plugin name */
			esc_html__( 'Breadcrumbs from %s will be used. The settings below only apply when the theme\'s built-in breadcrumbs are used.', 'prospero-theme' ),
			'<strong>' . esc_html( $detected_plugin ) . '</strong>'
		);
	}

	return esc_html__( 'No SEO plugin detected. The theme\'s built-in breadcrumbs will be used. Supported plugins: Yoast SEO, Rank Math, SEOPress, The SEO Framework, All in One SEO, Slim SEO, Breadcrumb NavXT.', 'prospero-theme' );
}
