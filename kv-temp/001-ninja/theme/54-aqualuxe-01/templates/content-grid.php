<?php
/**
 * Template part for displaying posts in grid layout
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-grid-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-transform hover:-translate-y-1 hover:shadow-lg'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="entry-thumbnail">
            <a href="<?php the_permalink(); ?>" class="block aspect-video overflow-hidden">
                <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover transition-transform hover:scale-105']); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content p-6">
        <header class="entry-header mb-4">
            <?php
            the_title('<h2 class="entry-title text-xl font-bold mb-2"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">', '</a></h2>');
            
            if ('post' === get_post_type()) :
                ?>
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
                    <?php
                    aqualuxe_posted_on();
                    ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-summary">
            <?php
            $excerpt_length = get_theme_mod('aqualuxe_excerpt_length', 55);
            echo aqualuxe_get_excerpt(null, $excerpt_length);
            ?>
        </div><!-- .entry-summary -->

        <footer class="entry-footer mt-4">
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300 transition-colors text-sm">
                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </footer><!-- .entry-footer -->
    </div>
</article><!-- #post-<?php the_ID(); ?> -->