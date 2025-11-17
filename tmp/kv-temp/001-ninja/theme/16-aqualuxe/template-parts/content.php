<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden transition-transform duration-300 hover:-translate-y-1'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('aqualuxe-card', ['class' => 'w-full h-64 object-cover']); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content p-6">
        <header class="entry-header mb-4">
            <?php
            if (is_sticky() && is_home() && !is_paged()) {
                echo '<span class="sticky-post bg-accent text-primary-dark text-xs px-2 py-1 rounded-full mb-2 inline-block">' . esc_html__('Featured', 'aqualuxe') . '</span>';
            }
            
            if ('post' === get_post_type()) :
                ?>
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <?php
                    aqualuxe_posted_on();
                    aqualuxe_posted_by();
                    ?>
                </div><!-- .entry-meta -->
            <?php endif; ?>

            <?php
            if (is_singular()) :
                the_title('<h1 class="entry-title text-2xl font-bold">', '</h1>');
            else :
                the_title('<h2 class="entry-title text-xl font-bold"><a href="' . esc_url(get_permalink()) . '" rel="bookmark" class="hover:text-primary transition-colors duration-300">', '</a></h2>');
            endif;
            ?>
        </header><!-- .entry-header -->

        <div class="entry-summary text-gray-600 dark:text-gray-300">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->

        <footer class="entry-footer mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center">
            <div class="post-categories text-sm">
                <?php
                $categories = get_the_category();
                if ($categories) {
                    echo '<span class="cat-links flex items-center">';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>';
                    
                    $category_list = array();
                    foreach ($categories as $category) {
                        $category_list[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="hover:text-primary transition-colors duration-300">' . esc_html($category->name) . '</a>';
                    }
                    
                    echo implode(', ', $category_list);
                    echo '</span>';
                }
                ?>
            </div>

            <a href="<?php the_permalink(); ?>" class="read-more text-primary hover:text-primary-dark text-sm font-medium transition-colors duration-300 flex items-center">
                <?php esc_html_e('Read More', 'aqualuxe'); ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </footer><!-- .entry-footer -->
    </div>
</article><!-- #post-<?php the_ID(); ?> -->