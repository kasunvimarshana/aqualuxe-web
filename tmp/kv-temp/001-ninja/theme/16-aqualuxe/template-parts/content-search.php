<?php
/**
 * Template part for displaying results in search pages
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

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden mb-6 transition-transform duration-300 hover:-translate-y-1'); ?>>
    <div class="flex flex-col md:flex-row">
        <?php if (has_post_thumbnail()) : ?>
            <div class="post-thumbnail md:w-1/3">
                <a href="<?php the_permalink(); ?>">
                    <?php the_post_thumbnail('medium', ['class' => 'w-full h-full object-cover']); ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="entry-content p-6 <?php echo has_post_thumbnail() ? 'md:w-2/3' : 'w-full'; ?>">
            <header class="entry-header mb-4">
                <?php the_title(sprintf('<h2 class="entry-title text-xl font-bold"><a href="%s" rel="bookmark" class="hover:text-primary transition-colors duration-300">', esc_url(get_permalink())), '</a></h2>'); ?>

                <?php if ('post' === get_post_type()) : ?>
                    <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mt-2">
                        <?php
                        aqualuxe_posted_on();
                        aqualuxe_posted_by();
                        ?>
                    </div><!-- .entry-meta -->
                <?php endif; ?>
                
                <div class="post-type-badge mt-2">
                    <span class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs px-2 py-1 rounded">
                        <?php 
                        $post_type = get_post_type();
                        $post_type_obj = get_post_type_object($post_type);
                        echo esc_html($post_type_obj->labels->singular_name);
                        ?>
                    </span>
                </div>
            </header><!-- .entry-header -->

            <div class="entry-summary text-gray-600 dark:text-gray-300">
                <?php the_excerpt(); ?>
            </div><!-- .entry-summary -->

            <footer class="entry-footer mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                <?php if ('post' === get_post_type()) : ?>
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
                <?php endif; ?>

                <div class="mt-4">
                    <a href="<?php the_permalink(); ?>" class="read-more text-primary hover:text-primary-dark text-sm font-medium transition-colors duration-300 flex items-center">
                        <?php esc_html_e('Read More', 'aqualuxe'); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </footer><!-- .entry-footer -->
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->