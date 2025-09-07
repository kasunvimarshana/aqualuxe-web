<?php
/**
 * Template part for displaying quote posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 shadow-md rounded-lg p-6'); ?>>
    <div class="entry-content">
        <blockquote class="p-4 my-4 border-l-4 border-gray-300 bg-gray-50 dark:bg-gray-700">
            <div class="quote-content">
                <?php the_content(); ?>
            </div>
            <cite class="block text-right text-gray-500 dark:text-gray-400 mt-4">&mdash; <?php the_title(); ?></cite>
        </blockquote>
    </div><!-- .entry-content -->
	<footer class="entry-footer mt-4 text-sm text-gray-500 dark:text-gray-400">
		<span class="cat-links"><?php echo get_the_category_list( ', ' ); ?></span>
		<span class="tags-links"><?php echo get_the_tag_list( '', ', ' ); ?></span>
		<?php if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) : ?>
			<span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'aqualuxe' ), __( '1 Comment', 'aqualuxe' ), __( '% Comments', 'aqualuxe' ) ); ?></span>
		<?php endif; ?>
	</footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
