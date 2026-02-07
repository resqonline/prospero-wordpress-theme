<?php
/**
 * Template Name: Startpage
 * Description: Homepage template with custom layout
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main startpage">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'startpage-content' ); ?>>
			<div class="container">
				<?php
				// Display page title if not hidden
				if ( ! get_post_meta( get_the_ID(), '_prospero_hide_title', true ) ) :
					?>
					<header class="page-header">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</header>
					<?php
				endif;
				?>
				
				<div class="page-content">
					<?php
					the_content();
					
					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'prospero-theme' ),
						'after'  => '</div>',
					) );
					?>
				</div>
			</div>
		</article>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
