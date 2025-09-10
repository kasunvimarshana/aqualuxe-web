<?php
/**
 * Template part for displaying service content
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('service-item bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden transform hover:-translate-y-2 transition-transform duration-300'); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php the_permalink(); ?>" class="block">
			<div class="post-thumbnail">
				<?php the_post_thumbnail('medium_large', ['class' => 'w-full h-48 object-cover']); ?>
			</div>
		</a>
	<?php endif; ?>
    <div class="p-6">
        <header class="entry-header mb-4">
            <?php the_title( '<h2 class="entry-title text-2xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
        </header>

        <div class="entry-content">
            <?php the_excerpt(); ?>
        </div>
        <footer class="entry-footer mt-4">
            <a href="<?php the_permalink(); ?>" class="read-more text-blue-600 dark:text-blue-400 hover:underline font-semibold"><?php esc_html_e( 'Learn More &rarr;', 'aqualuxe' ); ?></a>
        </footer>
    </div>
</article>
