<?php
/**
 * The template for displaying search results
 *
 * @package Prospero
 * @since 1.0.0
 */

get_header();
?>

<main id="main-content" class="site-main search-results-template">
	<div class="container">
		<header class="page-header">
			<h1 class="page-title">
				<?php
				/* translators: %s: search query */
				printf( esc_html__( 'Search Results for: %s', 'prospero-theme' ), '<span>' . get_search_query() . '</span>' );
				?>
			</h1>
			<div class="search-form-header">
				<?php get_search_form(); ?>
			</div>
		</header>

		<?php if ( have_posts() ) : ?>
			<p class="results-count">
				<?php
				/* translators: %d: number of results */
				printf(
					esc_html( _n( '%d result found', '%d results found', $wp_query->found_posts, 'prospero-theme' ) ),
					absint( $wp_query->found_posts )
				);
				?>
			</p>

			<div class="search-results-list">
				<?php
				while ( have_posts() ) :
					the_post();
					?>
					<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result-item' ); ?>>
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="result-thumbnail">
								<a href="<?php the_permalink(); ?>">
									<?php the_post_thumbnail( 'thumbnail' ); ?>
								</a>
							</div>
						<?php endif; ?>

						<div class="result-content">
							<header class="result-header">
								<span class="result-type">
									<?php
									$post_type_obj = get_post_type_object( get_post_type() );
									echo esc_html( $post_type_obj->labels->singular_name );
									?>
								</span>
								<h2 class="result-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h2>
							</header>

							<div class="result-excerpt">
								<?php
								if ( has_excerpt() ) {
									the_excerpt();
								} else {
									echo wp_kses_post( wp_trim_words( get_the_content(), 30, '...' ) );
								}
								?>
							</div>

							<footer class="result-meta">
								<?php if ( 'post' === get_post_type() ) : ?>
									<span class="result-date">
										<?php echo esc_html( get_the_date() ); ?>
									</span>
								<?php endif; ?>
								<a href="<?php the_permalink(); ?>" class="result-link">
									<?php esc_html_e( 'Read more', 'prospero-theme' ); ?>
								</a>
							</footer>
						</div>
					</article>
				<?php endwhile; ?>
			</div>

			<?php
			// Pagination
			the_posts_pagination( array(
				'mid_size'  => 2,
				'prev_text' => esc_html__( 'Previous', 'prospero-theme' ),
				'next_text' => esc_html__( 'Next', 'prospero-theme' ),
			) );
			?>

		<?php else : ?>
			<div class="no-results">
				<h2><?php esc_html_e( 'Nothing Found', 'prospero-theme' ); ?></h2>
				<p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with different keywords.', 'prospero-theme' ); ?></p>

				<div class="search-suggestions">
					<h3><?php esc_html_e( 'Suggestions', 'prospero-theme' ); ?></h3>
					<ul>
						<li><?php esc_html_e( 'Check your spelling', 'prospero-theme' ); ?></li>
						<li><?php esc_html_e( 'Try more general keywords', 'prospero-theme' ); ?></li>
						<li><?php esc_html_e( 'Try different keywords', 'prospero-theme' ); ?></li>
					</ul>
				</div>

				<div class="search-try-again">
					<?php get_search_form(); ?>
				</div>
			</div>
		<?php endif; ?>
	</div>
</main>

<?php
get_footer();
