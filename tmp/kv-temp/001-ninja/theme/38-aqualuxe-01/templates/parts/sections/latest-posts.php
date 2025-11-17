<?php
/**
 * Template part for displaying the latest posts section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get latest posts section settings from theme mods
$section_title = get_theme_mod( 'aqualuxe_latest_posts_title', esc_html__( 'Latest Articles', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_latest_posts_subtitle', esc_html__( 'From Our Blog', 'aqualuxe' ) );
$section_text = get_theme_mod( 'aqualuxe_latest_posts_text', esc_html__( 'Stay updated with the latest news, tips, and insights from the world of aquatics.', 'aqualuxe' ) );
$posts_count = get_theme_mod( 'aqualuxe_latest_posts_count', 3 );
$show_view_all = get_theme_mod( 'aqualuxe_latest_posts_show_view_all', true );
$view_all_text = get_theme_mod( 'aqualuxe_latest_posts_view_all_text', esc_html__( 'View All Posts', 'aqualuxe' ) );

// Check if latest posts section should be displayed
$show_latest_posts = get_theme_mod( 'aqualuxe_show_latest_posts', true );

if ( ! $show_latest_posts ) {
	return;
}

// Query latest posts
$args = array(
	'post_type'           => 'post',
	'posts_per_page'      => $posts_count,
	'ignore_sticky_posts' => true,
);

$latest_posts = new WP_Query( $args );

// Return if no posts found
if ( ! $latest_posts->have_posts() ) {
	return;
}
?>

<section id="latest-posts" class="latest-posts-section">
	<div class="container">
		<div class="section-header">
			<?php if ( $section_subtitle ) : ?>
				<div class="section-subtitle"><?php echo esc_html( $section_subtitle ); ?></div>
			<?php endif; ?>

			<?php if ( $section_title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
			<?php endif; ?>

			<?php if ( $section_text ) : ?>
				<div class="section-text"><?php echo wp_kses_post( $section_text ); ?></div>
			<?php endif; ?>
		</div>

		<div class="posts-grid">
			<?php
			while ( $latest_posts->have_posts() ) :
				$latest_posts->the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<div class="post-thumbnail">
							<a href="<?php the_permalink(); ?>">
								<?php the_post_thumbnail( 'aqualuxe-card', array( 'class' => 'card-image' ) ); ?>
							</a>
						</div>
					<?php endif; ?>

					<div class="post-content">
						<header class="entry-header">
							<div class="entry-meta">
								<span class="posted-on">
									<time class="entry-date published" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time>
								</span>
								<?php
								$categories = get_the_category();
								if ( ! empty( $categories ) ) :
									?>
									<span class="post-category">
										<a href="<?php echo esc_url( get_category_link( $categories[0]->term_id ) ); ?>"><?php echo esc_html( $categories[0]->name ); ?></a>
									</span>
								<?php endif; ?>
							</div>

							<?php the_title( '<h3 class="entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>
						</header><!-- .entry-header -->

						<div class="entry-summary">
							<?php the_excerpt(); ?>
						</div><!-- .entry-summary -->

						<footer class="entry-footer">
							<a href="<?php the_permalink(); ?>" class="read-more-link"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <span class="screen-reader-text"><?php esc_html_e( 'about', 'aqualuxe' ); ?> <?php the_title(); ?></span></a>
						</footer><!-- .entry-footer -->
					</div><!-- .post-content -->
				</article><!-- #post-<?php the_ID(); ?> -->
			<?php endwhile; ?>
		</div>

		<?php if ( $show_view_all && $view_all_text ) : ?>
			<div class="section-footer">
				<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ); ?>" class="button button-secondary"><?php echo esc_html( $view_all_text ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section><!-- .latest-posts-section -->

<?php
// Reset post data
wp_reset_postdata();