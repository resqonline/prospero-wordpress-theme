<?php
/**
 * The header template
 *
 * @package Prospero
 * @since 1.0.0
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content">
	<?php esc_html_e( 'Skip to content', 'prospero-theme' ); ?>
</a>

<?php
$logo_position = get_theme_mod( 'prospero_logo_position', 'left' );
$menu_position = get_theme_mod( 'prospero_menu_position', 'right' );
$header_classes = array( 'site-header' );

if ( get_theme_mod( 'prospero_sticky_menu', false ) ) {
	$header_classes[] = 'sticky-header';
}

$header_classes[] = 'logo-' . $logo_position;
$header_classes[] = 'menu-' . $menu_position;

if ( get_theme_mod( 'prospero_hamburger_menu', false ) ) {
	$header_classes[] = 'has-hamburger-menu';
}
?>
<div id="page" class="site">
	<header id="masthead" class="<?php echo esc_attr( implode( ' ', $header_classes ) ); ?>" role="banner">
		<div class="container header-inner">
			<div class="site-branding">
				<?php prospero_custom_logo(); ?>
				<div class="site-title-wrap">
					<?php if ( is_front_page() && is_home() ) : ?>
						<h1 class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						</h1>
					<?php else : ?>
						<p class="site-title">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
								<?php bloginfo( 'name' ); ?>
							</a>
						</p>
					<?php endif; ?>

					<?php
					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) :
						?>
						<p class="site-description"><?php echo esc_html( $description ); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<!-- Desktop Navigation -->
			<nav id="site-navigation" class="main-navigation desktop-menu" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'prospero-theme' ); ?>">
				<?php
				wp_nav_menu( array(
					'theme_location' => 'main-menu',
					'menu_id'        => 'primary-menu',
					'container'      => false,
					'fallback_cb'    => false,
				) );
				?>
			</nav>

			<div class="header-actions">
				<?php if ( get_theme_mod( 'prospero_header_search', false ) ) : ?>
					<button id="header-search-toggle" class="header-search-toggle" aria-label="<?php esc_attr_e( 'Open search', 'prospero-theme' ); ?>" aria-expanded="false" aria-controls="search-overlay">
						<span class="icon-search" aria-hidden="true"></span>
					</button>
				<?php endif; ?>
				<?php if ( get_theme_mod( 'prospero_enable_dark_mode', true ) && get_theme_mod( 'prospero_show_dark_mode_toggle', true ) ) : ?>
					<button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'prospero-theme' ); ?>">
						<span class="dark-mode-icon light-icon icon-sun" aria-hidden="true"></span>
						<span class="dark-mode-icon dark-icon icon-moon" aria-hidden="true"></span>
					</button>
				<?php endif; ?>

				<!-- Mobile Menu Toggle -->
				<button class="mobile-menu-toggle" aria-controls="mobile-menu-panel" aria-expanded="false">
					<span class="menu-toggle-icon icon-menu" aria-hidden="true"></span>
					<span class="menu-toggle-icon icon-x" aria-hidden="true"></span>
					<span class="screen-reader-text"><?php esc_html_e( 'Menu', 'prospero-theme' ); ?></span>
				</button>
			</div>
		</div>
	</header>

	<!-- Mobile Menu Panel (Slide-in) -->
	<div id="mobile-menu-panel" class="mobile-menu-panel" aria-hidden="true">
		<div class="mobile-menu-header">
			<button class="mobile-menu-close" aria-label="<?php esc_attr_e( 'Close menu', 'prospero-theme' ); ?>">
				<span class="icon-x" aria-hidden="true"></span>
			</button>
		</div>
		<nav class="mobile-menu-nav" role="navigation" aria-label="<?php esc_attr_e( 'Mobile Menu', 'prospero-theme' ); ?>">
			<div class="mobile-menu-panels">
				<div class="mobile-menu-panel-content" data-panel="main">
					<?php
					wp_nav_menu( array(
						'theme_location' => 'main-menu',
						'menu_id'        => 'mobile-menu',
						'menu_class'     => 'mobile-menu-list',
						'container'      => false,
						'fallback_cb'    => false,
						'walker'         => class_exists( 'Prospero_Mobile_Menu_Walker' ) ? new Prospero_Mobile_Menu_Walker() : null,
					) );
					?>
				</div>
			</div>
		</nav>
	</div>
	<div class="mobile-menu-overlay" aria-hidden="true"></div>

	<?php if ( get_theme_mod( 'prospero_header_search', false ) ) : ?>
	<!-- Search Overlay -->
	<div id="search-overlay" class="search-overlay" aria-hidden="true">
		<div class="search-overlay-inner">
			<button class="search-overlay-close" aria-label="<?php esc_attr_e( 'Close search', 'prospero-theme' ); ?>">
				<span class="icon-x" aria-hidden="true"></span>
			</button>
			<div class="search-overlay-content">
				<h2 class="screen-reader-text"><?php esc_html_e( 'Search', 'prospero-theme' ); ?></h2>
				<?php get_search_form(); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
