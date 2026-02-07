<?php
/**
 * Shortcodes
 *
 * @package Prospero
 * @since 1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Testimonials shortcode
 * Usage: [testimonials ids="" category="" count="-1" orderby="date" order="DESC" columns="1" slider="no"]
 */
function prospero_testimonials_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'ids'      => '',
		'category' => '',
		'count'    => -1,
		'orderby'  => 'date',
		'order'    => 'DESC',
		'columns'  => 1,
		'slider'   => 'no',
	), $atts, 'testimonials' );
	
	// Sanitize attributes
	$ids = ! empty( $atts['ids'] ) ? array_map( 'absint', explode( ',', $atts['ids'] ) ) : array();
	$category = sanitize_text_field( $atts['category'] );
	$count = intval( $atts['count'] );
	$orderby = sanitize_text_field( $atts['orderby'] );
	$order = sanitize_text_field( $atts['order'] );
	$columns = max( 1, min( 4, intval( $atts['columns'] ) ) ); // Limit 1-4 columns
	$slider = ( $atts['slider'] === 'yes' ) ? true : false;
	
	// Build query args
	$args = array(
		'post_type'      => 'testimonial',
		'posts_per_page' => $count,
		'orderby'        => $orderby,
		'order'          => $order,
		'post_status'    => 'publish',
	);
	
	// If specific IDs are provided, use them (takes precedence over category)
	if ( ! empty( $ids ) ) {
		$args['post__in'] = $ids;
		$args['posts_per_page'] = count( $ids );
		if ( $orderby === 'date' ) {
			$args['orderby'] = 'post__in'; // Preserve the order of IDs
		}
	} elseif ( ! empty( $category ) ) {
		// Add category filter if specified
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'testimonial_category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}
	
	// Query testimonials
	$query = new WP_Query( $args );
	
	if ( ! $query->have_posts() ) {
		return '';
	}
	
	// Build output
	ob_start();
	$wrapper_classes = 'prospero-testimonials';
	if ( $slider ) {
		$wrapper_classes .= ' prospero-slider';
	}
	?>
	<div class="prospero-slider-wrapper">
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>"<?php echo $slider ? ' data-slider-type="testimonials"' : ''; ?>>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				get_template_part( 'template-parts/content', 'testimonial', array( 'slider' => $slider ) );
			endwhile;
			?>
		</div>
	</div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'testimonials', 'prospero_testimonials_shortcode' );

/**
 * Partners shortcode
 * Usage: [partners ids="" category="" count="-1" orderby="menu_order" order="ASC" columns="4" slider="no"]
 */
function prospero_partners_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'ids'      => '',
		'category' => '',
		'count'    => -1,
		'orderby'  => 'menu_order',
		'order'    => 'ASC',
		'columns'  => 4,
		'slider'   => 'no',
	), $atts, 'partners' );
	
	// Sanitize attributes
	$ids = ! empty( $atts['ids'] ) ? array_map( 'absint', explode( ',', $atts['ids'] ) ) : array();
	$category = sanitize_text_field( $atts['category'] );
	$count = intval( $atts['count'] );
	$orderby = sanitize_text_field( $atts['orderby'] );
	$order = sanitize_text_field( $atts['order'] );
	$columns = max( 2, min( 8, intval( $atts['columns'] ) ) ); // Limit 2-8 columns
	$slider = ( $atts['slider'] === 'yes' ) ? true : false;
	
	// Build query args
	$args = array(
		'post_type'      => 'partner',
		'posts_per_page' => $count,
		'orderby'        => $orderby,
		'order'          => $order,
		'post_status'    => 'publish',
	);
	
	// If specific IDs are provided, use them (takes precedence over category)
	if ( ! empty( $ids ) ) {
		$args['post__in'] = $ids;
		$args['posts_per_page'] = count( $ids );
		if ( $orderby === 'menu_order' ) {
			$args['orderby'] = 'post__in'; // Preserve the order of IDs
		}
	} elseif ( ! empty( $category ) ) {
		// Add category filter if specified
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'partner_category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}
	
	// Query partners
	$query = new WP_Query( $args );
	
	if ( ! $query->have_posts() ) {
		return '';
	}
	
	// Build output
	ob_start();
	$wrapper_classes = 'prospero-partners';
	if ( $slider ) {
		$wrapper_classes .= ' prospero-slider';
	}
	?>
	<div class="prospero-slider-wrapper">
		<div class="<?php echo esc_attr( $wrapper_classes ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>"<?php echo $slider ? ' data-slider-type="partners"' : ''; ?>>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				get_template_part( 'template-parts/content', 'partner', array( 'slider' => $slider ) );
			endwhile;
			?>
		</div>
	</div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'partners', 'prospero_partners_shortcode' );

/**
 * Team shortcode
 * Usage: [team ids="" category="" count="-1" layout="columns" columns="3" image_style="square" lightbox="no" slider="no"]
 * Layouts: columns, list
 * Image styles: square, round, portrait
 */
function prospero_team_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'ids'         => '',
		'category'    => '',
		'count'       => -1,
		'layout'      => 'columns',
		'columns'     => 3,
		'image_style' => 'square',
		'lightbox'    => 'no',
		'orderby'     => 'menu_order',
		'order'       => 'ASC',
		'slider'      => 'no',
	), $atts, 'team' );
	
	// Sanitize attributes
	$ids = ! empty( $atts['ids'] ) ? array_map( 'absint', explode( ',', $atts['ids'] ) ) : array();
	$category = sanitize_text_field( $atts['category'] );
	$count = intval( $atts['count'] );
	$layout = sanitize_text_field( $atts['layout'] );
	$columns = max( 2, min( 6, intval( $atts['columns'] ) ) ); // Limit 2-6 columns
	$image_style = sanitize_text_field( $atts['image_style'] );
	$lightbox = ( $atts['lightbox'] === 'yes' ) ? true : false;
	$orderby = sanitize_text_field( $atts['orderby'] );
	$order = sanitize_text_field( $atts['order'] );
	$slider = ( $atts['slider'] === 'yes' ) ? true : false;
	
	// Build query args
	$args = array(
		'post_type'      => 'team',
		'posts_per_page' => $count,
		'orderby'        => $orderby,
		'order'          => $order,
		'post_status'    => 'publish',
	);
	
	// If specific IDs are provided, use them (takes precedence over category)
	if ( ! empty( $ids ) ) {
		$args['post__in'] = $ids;
		$args['posts_per_page'] = count( $ids );
		if ( $orderby === 'menu_order' ) {
			$args['orderby'] = 'post__in'; // Preserve the order of IDs
		}
	} elseif ( ! empty( $category ) ) {
		// Add category filter if specified
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'team_category',
				'field'    => 'slug',
				'terms'    => $category,
			),
		);
	}
	
	// Query team members
	$query = new WP_Query( $args );
	
	if ( ! $query->have_posts() ) {
		return '';
	}
	
	// Build wrapper classes
	$wrapper_classes = array(
		'prospero-team',
		'prospero-team-' . esc_attr( $layout ),
		'prospero-team-image-' . esc_attr( $image_style ),
	);
	if ( $slider ) {
		$wrapper_classes[] = 'prospero-slider';
	}
	if ( $lightbox ) {
		$wrapper_classes[] = 'prospero-team-lightbox';
	}
	
	// Build output
	ob_start();
	?>
	<div class="prospero-slider-wrapper">
		<div class="<?php echo esc_attr( implode( ' ', $wrapper_classes ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>"<?php echo $slider ? ' data-slider-type="team"' : ''; ?><?php echo $lightbox ? ' data-lightbox="true"' : ''; ?>>
			<?php
			while ( $query->have_posts() ) :
				$query->the_post();
				get_template_part( 'template-parts/content', 'team', array(
					'slider'       => $slider,
					'lightbox'     => $lightbox,
					'layout'       => $layout,
					'show_contact' => false,
					'show_social'  => false,
					'show_link'    => false,
				) );
			endwhile;
			?>
		</div>
	</div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'team', 'prospero_team_shortcode' );

/**
 * Projects shortcode
 * Usage: [projects tags="" count="9" orderby="date" order="DESC" columns="3"]
 */
function prospero_projects_shortcode( $atts ) {
	$atts = shortcode_atts( array(
		'tags'     => '',
		'count'    => 9,
		'orderby'  => 'date',
		'order'    => 'DESC',
		'columns'  => 3,
	), $atts, 'projects' );
	
	// Sanitize attributes
	$tags = sanitize_text_field( $atts['tags'] );
	$count = intval( $atts['count'] );
	$orderby = sanitize_text_field( $atts['orderby'] );
	$order = sanitize_text_field( $atts['order'] );
	$columns = max( 1, min( 6, intval( $atts['columns'] ) ) ); // Limit 1-6 columns
	
	// Build query args
	$args = array(
		'post_type'      => 'project',
		'posts_per_page' => $count,
		'orderby'        => $orderby,
		'order'          => $order,
		'post_status'    => 'publish',
	);
	
	// Add tag filter if specified
	if ( ! empty( $tags ) ) {
		$tags_array = array_map( 'trim', explode( ',', $tags ) );
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'project_tag',
				'field'    => 'slug',
				'terms'    => $tags_array,
			),
		);
	}
	
	// Query projects
	$query = new WP_Query( $args );
	
	if ( ! $query->have_posts() ) {
		return '';
	}
	
	// Build output
	ob_start();
	?>
	<div class="prospero-projects" data-columns="<?php echo esc_attr( $columns ); ?>">
		<?php
		while ( $query->have_posts() ) :
			$query->the_post();
			get_template_part( 'template-parts/content', 'project', array(
				'show_tags' => false,
			) );
		endwhile;
		?>
	</div>
	<?php
	wp_reset_postdata();
	return ob_get_clean();
}
add_shortcode( 'projects', 'prospero_projects_shortcode' );
