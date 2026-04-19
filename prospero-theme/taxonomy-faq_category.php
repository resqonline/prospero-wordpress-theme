<?php
/**
 * FAQ category taxonomy template
 *
 * Renders a `faq_category` taxonomy term page (e.g. /faq-category/shipping)
 * as an accordion of the FAQs in that category. Uses the same accordion
 * markup as the FAQ archive and the FAQ list block so faq-accordion.js
 * drives the toggle behavior with no code change.
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();

$term        = get_queried_object();
$archive_url = get_post_type_archive_link( 'faq' );
?>

<main id="main-content" class="site-main faq-archive-template faq-taxonomy-template" role="main">
	<?php prospero_breadcrumbs( array( 'wrapper_class' => 'breadcrumbs container' ) ); ?>

	<div class="container">
		<header class="page-header">
			<h1 class="page-title">
				<?php
				/* translators: %s: FAQ category name */
				printf( esc_html__( 'FAQs: %s', 'prospero-theme' ), esc_html( $term->name ) );
				?>
			</h1>
			<?php if ( ! empty( $term->description ) ) : ?>
				<div class="page-description"><?php echo wp_kses_post( $term->description ); ?></div>
			<?php endif; ?>
			<?php if ( $archive_url ) : ?>
				<p class="faq-archive-link">
					<a href="<?php echo esc_url( $archive_url ); ?>">
						<?php esc_html_e( '← Back to all FAQs', 'prospero-theme' ); ?>
					</a>
				</p>
			<?php endif; ?>
		</header>

		<?php if ( have_posts() ) : ?>
			<div class="prospero-faq-list faq-accordion" itemscope itemtype="https://schema.org/FAQPage">
				<?php while ( have_posts() ) : the_post(); ?>
					<div
						id="faq-<?php echo esc_attr( get_post_field( 'post_name' ) ); ?>"
						class="faq-item faq-accordion-item"
						itemscope
						itemprop="mainEntity"
						itemtype="https://schema.org/Question"
					>
						<button class="faq-question" aria-expanded="false" itemprop="name">
							<span class="faq-question-text"><?php the_title(); ?></span>
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
								<?php the_content(); ?>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</div>

			<?php
			the_posts_pagination(
				array(
					'prev_text' => esc_html__( '« Previous', 'prospero-theme' ),
					'next_text' => esc_html__( 'Next »', 'prospero-theme' ),
				)
			);
			?>
		<?php else : ?>
			<p class="no-posts"><?php esc_html_e( 'No FAQs found in this category.', 'prospero-theme' ); ?></p>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
