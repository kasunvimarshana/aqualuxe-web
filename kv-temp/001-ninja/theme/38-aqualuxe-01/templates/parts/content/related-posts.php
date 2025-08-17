<?php
/**
 * Template part for displaying related posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if not on a single post or if related posts are disabled
if ( ! is_single() || ! get_theme_mod( 'aqualuxe_show_related_posts', true ) ) {
	return;
}

// Get current post categories
$categories = get_the_category();

if ( empty( $categories ) ) {
	return;
}

// Get category IDs
$category_ids = array();
foreach ( $categories as $category ) {
	$category_ids[] = $category->term_id;
}

// Query related posts
$related_args = array(
	'category__in'        => $category_ids,
	'post__not_in'        => array( get_the_ID() ),
	'posts_per_page'      => 3,
	'ignore_sticky_posts' => 1,
);

$related_query = new WP_Query( $related_args );

// Only show if we have related posts
if ( ! $related_query->have_posts() ) {
	return;
}
?>

<div class="related-posts">
	<h3 class="related-posts-title"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>

	<div class="related-posts-grid">
		<?php
		while ( $related_query->have_posts() ) :
			$related_query->the_post();
			?>
			<article class="related-post">
				<?php if ( has_post_thumbnail() ) : ?>
					<a href="<?php the_permalink(); ?>" class="related-post-thumbnail">
						<?php the_post_thumbnail( 'aqualuxe-card', array( 'class' => 'related-image' ) ); ?>
					</a>
				<?php endif; ?>

				<div class="related-post-content">
					<h4 class="related-post-title">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</h4>

					<div class="related-post-meta">
						<span class="related-post-date"><?php echo esc_html( get_the_date() ); ?></span>
					</div>
				</div>
			</article>
		<?php endwhile; ?>
	</div>
</div><!-- .related-posts -->

<?php
// Reset post data
wp_reset_postdata();