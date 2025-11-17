<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get blog layout
$blog_layout = get_theme_mod( 'aqualuxe_blog_layout', 'grid' );

// Set post classes
$post_classes = array( 'post-item', 'search-item' );
if ( 'grid' === $blog_layout || 'masonry' === $blog_layout ) {
	$post_classes[] = 'post-grid-item';
} elseif ( 'list' === $blog_layout ) {
	$post_classes[] = 'post-list-item';
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_classes ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<div class="post-thumbnail">
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail( 'medium', array( 'class' => 'post-thumbnail-img' ) ); ?>
			</a>
		</div>
	<?php endif; ?>

	<div class="post-content">
		<header class="entry-header">
			<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

			<?php if ( 'post' === get_post_type() ) : ?>
				<div class="entry-meta">
					<?php
					aqualuxe_posted_on();
					aqualuxe_posted_by();
					?>
				</div><!-- .entry-meta -->
			<?php endif; ?>
		</header><!-- .entry-header -->

		<div class="entry-summary">
			<?php the_excerpt(); ?>
		</div><!-- .entry-summary -->

		<footer class="entry-footer">
			<?php
			// Post type
			$post_type = get_post_type();
			$post_type_obj = get_post_type_object( $post_type );
			if ( $post_type_obj ) {
				echo '<span class="post-type">' . esc_html( $post_type_obj->labels->singular_name ) . '</span>';
			}
			?>
			<a href="<?php the_permalink(); ?>" class="read-more-link"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?></a>
		</footer><!-- .entry-footer -->
	</div><!-- .post-content -->
</article><!-- #post-<?php the_ID(); ?> -->