<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main error-404-template">
	<div class="container">
		<div class="error-404-content">
			<header class="page-header">
				<span class="error-code">404</span>
				<h1 class="page-title"><?php esc_html_e( 'Page Not Found', 'prospero-theme' ); ?></h1>
			</header>

			<div class="page-content">
				<p class="error-message">
					<?php esc_html_e( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'prospero-theme' ); ?>
				</p>

				<div class="error-search">
					<h2><?php esc_html_e( 'Try searching', 'prospero-theme' ); ?></h2>
					<?php get_search_form(); ?>
				</div>

				<div class="error-suggestions">
					<h2><?php esc_html_e( 'Or try these links', 'prospero-theme' ); ?></h2>
					<ul class="suggestions-list">
						<li>
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<?php esc_html_e( 'Home Page', 'prospero-theme' ); ?>
							</a>
						</li>
						<?php if ( get_option( 'page_for_posts' ) ) : ?>
							<li>
								<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>">
									<?php esc_html_e( 'Blog', 'prospero-theme' ); ?>
								</a>
							</li>
						<?php endif; ?>
						<?php if ( has_nav_menu( 'main-menu' ) ) : ?>
							<?php
							// Get a few items from the main menu
							$menu_locations = get_nav_menu_locations();
							$menu_items     = wp_get_nav_menu_items( $menu_locations['main-menu'], array( 'posts_per_page' => 5 ) );

							if ( $menu_items ) :
								foreach ( $menu_items as $menu_item ) :
									// Only show top-level items
									if ( 0 === (int) $menu_item->menu_item_parent ) :
										?>
										<li>
											<a href="<?php echo esc_url( $menu_item->url ); ?>">
												<?php echo esc_html( $menu_item->title ); ?>
											</a>
										</li>
										<?php
									endif;
								endforeach;
							endif;
							?>
						<?php endif; ?>
					</ul>
				</div>

				<div class="error-back">
					<a href="javascript:history.back()" class="button button-secondary">
						<?php esc_html_e( 'Go Back', 'prospero-theme' ); ?>
					</a>
				</div>
			</div>
		</div>
	</div>
</main>

<?php
get_footer();
