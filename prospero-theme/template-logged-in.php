<?php
/**
 * Template Name: Logged In
 * Description: Template for member-only content pages
 *
 * @package Prospero
 * @since 1.0.0
 */

// Redirect to login if not logged in
if ( ! is_user_logged_in() ) {
	$login_url = wp_login_url( get_permalink() );
	wp_safe_redirect( $login_url );
	exit;
}

get_header();
?>

<main id="main-content" class="site-main logged-in-template">
	<div class="container">
		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<nav class="logged-in-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Member Navigation', 'prospero-theme' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'logged-in-menu',
					'menu_id'        => 'logged-in-menu-content',
					'container'      => false,
					'depth'          => 1,
					'fallback_cb'    => 'prospero_logged_in_menu_fallback',
				) );
				?>
			</nav>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'member-content' ); ?>>
				<header class="entry-header">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					
				<div class="member-badge">
					<span class="badge-text"><?php esc_html_e( 'Member-Only Content', 'prospero-theme' ); ?></span>
				</div>
				</header>

				<div class="entry-content">
					<?php
					the_content();

					wp_link_pages( array(
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'prospero-theme' ),
						'after'  => '</div>',
					) );
					?>
				</div>

				<?php if ( get_edit_post_link() ) : ?>
					<footer class="entry-footer">
						<?php
						edit_post_link(
							sprintf(
								wp_kses(
									/* translators: %s: Name of current post. Only visible to screen readers */
									__( 'Edit <span class="screen-reader-text">%s</span>', 'prospero-theme' ),
									array(
										'span' => array(
											'class' => array(),
										),
									)
								),
								esc_html( get_the_title() )
							),
							'<span class="edit-link">',
							'</span>'
						);
						?>
					</footer>
				<?php endif; ?>
			</article>

			<?php
			// If comments are open or we have at least one comment, load up the comment template.
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile; // End of the loop.
		?>
	</div>
</main>

<?php
get_footer();
