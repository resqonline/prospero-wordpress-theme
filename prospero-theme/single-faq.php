<?php
/**
 * Single FAQ template
 *
 * Renders a single FAQ using the same accordion markup as the list
 * templates, but pre-expanded, so the single-page layout is consistent
 * with the archive / taxonomy listings. Keeps the FAQ publicly reachable
 * for sharing / SEO while avoiding the full blog-post chrome.
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();

$archive_url = get_post_type_archive_link( 'faq' );
?>

<main id="main-content" class="site-main single-faq" role="main">
	<?php prospero_breadcrumbs( array( 'wrapper_class' => 'breadcrumbs container' ) ); ?>

	<div class="container">
		<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

			<header class="page-header">
				<?php
				// Show FAQ category chips if the post has any, linking back to the
				// taxonomy page. Uses the same styling as other terms.
				$terms = get_the_terms( get_the_ID(), 'faq_category' );
				if ( $terms && ! is_wp_error( $terms ) ) :
					?>
					<div class="faq-categories">
						<?php foreach ( $terms as $term ) : ?>
							<a class="faq-category" href="<?php echo esc_url( get_term_link( $term ) ); ?>">
								<?php echo esc_html( $term->name ); ?>
							</a>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>

				<?php if ( $archive_url ) : ?>
					<p class="faq-archive-link">
						<a href="<?php echo esc_url( $archive_url ); ?>">
							<?php esc_html_e( '← Back to all FAQs', 'prospero-theme' ); ?>
						</a>
					</p>
				<?php endif; ?>
			</header>

			<div class="prospero-faq-list faq-accordion" itemscope itemtype="https://schema.org/FAQPage">
				<div
					class="faq-item faq-accordion-item is-open"
					itemscope
					itemprop="mainEntity"
					itemtype="https://schema.org/Question"
				>
					<button class="faq-question" aria-expanded="true" itemprop="name">
						<span class="faq-question-text"><?php the_title(); ?></span>
						<span class="faq-toggle icon-minus" aria-hidden="true"></span>
					</button>
					<div
						class="faq-answer"
						itemscope
						itemprop="acceptedAnswer"
						itemtype="https://schema.org/Answer"
					>
						<div itemprop="text">
							<?php the_content(); ?>
						</div>
					</div>
				</div>
			</div>

		<?php endwhile; endif; ?>
	</div>
</main>

<?php
get_footer();
