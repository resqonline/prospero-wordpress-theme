<?php
/**
 * FAQ archive template
 *
 * Renders the FAQ post type archive as a single accordion list
 * (question -> click to reveal answer) instead of the blog-card loop
 * used by the generic archive.php.
 *
 * Markup shape matches the `.faq-accordion` output produced by the
 * FAQ list block, so the existing faq-accordion.js handles the
 * expand/collapse behavior without any changes.
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main faq-archive-template" role="main">
	<?php prospero_breadcrumbs( array( 'wrapper_class' => 'breadcrumbs container' ) ); ?>

	<?php
	// Customizer-driven title + description with translatable fallback.
	$faq_archive_title = trim( (string) get_theme_mod( 'prospero_faq_archive_title', '' ) );
	if ( '' === $faq_archive_title ) {
		/* translators: Default title for the FAQ archive page */
		$faq_archive_title = __( 'Frequently Asked Questions', 'prospero-theme' );
	}
	$faq_archive_description = (string) get_theme_mod( 'prospero_faq_archive_description', '' );
	?>
	<div class="container">
		<header class="page-header">
			<h1 class="page-title"><?php echo esc_html( $faq_archive_title ); ?></h1>
			<?php if ( '' !== trim( $faq_archive_description ) ) : ?>
				<div class="page-description">
					<?php echo wp_kses_post( wpautop( $faq_archive_description ) ); ?>
				</div>
			<?php endif; ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<?php
			global $wp_query;
			$groups        = prospero_group_faqs_by_category( $wp_query->posts );
			$show_headings = count( $groups ) > 1;
			?>
			<div class="prospero-faq-list" itemscope itemtype="https://schema.org/FAQPage">
				<?php foreach ( $groups as $group ) : ?>
					<?php if ( $show_headings ) : ?>
						<h2 class="faq-group-title">
							<?php
							if ( $group['term'] ) {
								echo esc_html( $group['term']->name );
							} else {
								esc_html_e( 'Uncategorised', 'prospero-theme' );
							}
							?>
						</h2>
					<?php endif; ?>
					<div class="faq-accordion">
						<?php foreach ( $group['posts'] as $faq ) : ?>
							<div
								id="faq-<?php echo esc_attr( $faq->post_name ); ?>"
								class="faq-item faq-accordion-item"
								itemscope
								itemprop="mainEntity"
								itemtype="https://schema.org/Question"
							>
								<button class="faq-question" aria-expanded="false" itemprop="name">
									<span class="faq-question-text"><?php echo esc_html( get_the_title( $faq ) ); ?></span>
									<span class="faq-toggle icon-plus" aria-hidden="true"></span>
								</button>
								<div
									class="faq-answer"
									hidden
									itemscope
									itemprop="acceptedAnswer"
									itemtype="https://schema.org/Answer"
								>
									<div itemprop="text">
										<?php echo wp_kses_post( apply_filters( 'the_content', $faq->post_content ) ); ?>
									</div>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<?php
			// Paging for very long FAQ libraries; hidden by WP core when not needed.
			the_posts_pagination(
				array(
					'prev_text' => esc_html__( '« Previous', 'prospero-theme' ),
					'next_text' => esc_html__( 'Next »', 'prospero-theme' ),
				)
			);
			?>
		<?php else : ?>
			<p class="no-posts"><?php esc_html_e( 'No FAQs found.', 'prospero-theme' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
