<?php
/**
 * SEO Functions
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add structured data (Schema.org)
 */
function prospero_add_schema_markup() {
	if ( is_singular( 'post' ) ) {
		?>
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "BlogPosting",
			"headline": "<?php echo esc_js( get_the_title() ); ?>",
			"datePublished": "<?php echo esc_attr( get_the_date( 'c' ) ); ?>",
			"dateModified": "<?php echo esc_attr( get_the_modified_date( 'c' ) ); ?>",
			"author": {
				"@type": "Person",
				"name": "<?php echo esc_js( get_the_author() ); ?>"
			}
		}
		</script>
		<?php
	}
}
add_action( 'wp_head', 'prospero_add_schema_markup' );

/**
 * Add Open Graph meta tags
 */
function prospero_add_og_tags() {
	if ( is_singular() ) {
		global $post;
		?>
		<meta property="og:title" content="<?php echo esc_attr( get_the_title() ); ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content="<?php echo esc_url( get_permalink() ); ?>" />
		<?php if ( has_post_thumbnail() ) : ?>
		<meta property="og:image" content="<?php echo esc_url( get_the_post_thumbnail_url( $post, 'large' ) ); ?>" />
		<?php endif; ?>
		<meta property="og:description" content="<?php echo esc_attr( wp_trim_words( get_the_excerpt(), 20 ) ); ?>" />
		<?php
	}
}
add_action( 'wp_head', 'prospero_add_og_tags' );
