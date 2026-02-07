<?php
/**
 * The footer template
 *
 * @package Prospero
 * @since 1.0.0
 */
?>

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="container">
			<?php if ( has_nav_menu( 'footer-menu' ) ) : ?>
				<nav class="footer-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'prospero-theme' ); ?>">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer-menu',
						'menu_id'        => 'footer-menu',
						'container'      => false,
						'depth'          => 1,
					) );
					?>
				</nav>
			<?php endif; ?>

			<?php if ( has_nav_menu( 'social-menu' ) ) : ?>
				<nav class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Social Menu', 'prospero-theme' ); ?>">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'social-menu',
						'menu_id'        => 'social-menu',
						'container'      => false,
						'depth'          => 1,
						'link_before'    => '<span class="screen-reader-text">',
						'link_after'     => '</span>',
					) );
					?>
				</nav>
			<?php endif; ?>

			<div class="site-info">
				<?php
				printf(
					/* translators: 1: Current year, 2: Site name */
					esc_html__( '&copy; %1$s %2$s. All rights reserved.', 'prospero-theme' ),
					date_i18n( 'Y' ),
					get_bloginfo( 'name' )
				);
				?>
			</div>
		</div>
	</footer>
</div><!-- #page -->

<!-- Back to top button -->
<button type="button" class="back-to-top" aria-label="<?php esc_attr_e( 'Back to top', 'prospero-theme' ); ?>" hidden>
	<span class="back-to-top-icon" aria-hidden="true">&uarr;</span>
</button>

<?php wp_footer(); ?>

</body>
</html>
