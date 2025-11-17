<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300'); ?>>
    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-content p-6">
        <header class="entry-header mb-4">
            <?php the_title(sprintf('<h2 class="entry-title text-xl font-bold text-gray-900 dark:text-white mb-2"><a href="%s" rel="bookmark" class="hover:text-primary dark:hover:text-primary-dark transition-colors duration-300">', esc_url(get_permalink())), '</a></h2>'); ?>

            <?php if ('post' === get_post_type()) : ?>
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <?php
                    aqualuxe_posted_on();
                    aqualuxe_posted_by();
                    ?>
                </div><!-- .entry-meta -->
            <?php elseif ('product' === get_post_type() && class_exists('WooCommerce')) : ?>
                <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mb-2">
                    <?php
                    global $product;
                    if ($product) {
                        echo '<span class="price text-primary dark:text-primary-dark font-bold">' . $product->get_price_html() . '</span>';
                    }
                    ?>
                </div>
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-summary text-gray-700 dark:text-gray-300 mb-4">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->

        <div class="mt-4">
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-dark transition-colors duration-300">
                <?php 
                if ('product' === get_post_type()) {
                    esc_html_e('View Product', 'aqualuxe');
                } else {
                    esc_html_e('Read More', 'aqualuxe');
                }
                ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </div><!-- .entry-content -->
</article><!-- #post-<?php the_ID(); ?> -->