<?php
/**
 * Template part for displaying event posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Aqualuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('group bg-white shadow-md rounded-lg overflow-hidden transition-shadow duration-300 hover:shadow-xl'); ?>>
    <header class="entry-header">
        <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" class="block overflow-hidden">
                <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105']); ?>
            </a>
        <?php endif; ?>
    </header><!-- .entry-header -->

    <div class="entry-content p-6">
        <?php
        the_title(sprintf('<h2 class="entry-title text-2xl font-semibold mb-2"><a href="%s" rel="bookmark">', esc_url(get_permalink())), '</a></h2>');
        ?>

        <div class="entry-meta text-sm text-gray-500 mb-4">
            <?php
            // Display event categories
            $terms = get_the_terms(get_the_ID(), 'event-category');
            if ($terms && !is_wp_error($terms)) {
                echo '<span class="cat-links">';
                foreach ($terms as $term) {
                    echo '<a href="' . get_term_link($term) . '" class="mr-2 bg-gray-100 px-2 py-1 rounded">' . $term->name . '</a>';
                }
                echo '</span>';
            }
            ?>
        </div><!-- .entry-meta -->

        <div class="prose prose-sm max-w-none">
            <?php the_excerpt(); ?>
        </div>

    </div><!-- .entry-content -->

    <footer class="entry-footer p-6 bg-gray-50">
        <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:text-blue-800 font-semibold">
            <?php esc_html_e('View Event Details', 'aqualuxe'); ?> &rarr;
        </a>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->
