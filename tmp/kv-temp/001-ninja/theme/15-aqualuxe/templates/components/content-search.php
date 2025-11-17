<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 pb-8 border-b border-gray-200 dark:border-gray-700'); ?>>
    <header class="entry-header mb-4">
        <?php the_title(sprintf('<h2 class="entry-title text-xl md:text-2xl font-bold mb-2"><a href="%s" rel="bookmark" class="hover:text-primary-600 dark:hover:text-primary-400">', esc_url(get_permalink())), '</a></h2>'); ?>

        <?php if ('post' === get_post_type()) : ?>
        <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
            <span class="post-date">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <?php echo get_the_date(); ?>
            </span>
            <span class="mx-2">•</span>
            <span class="post-type">
                <?php
                $post_type_obj = get_post_type_object(get_post_type());
                if ($post_type_obj) {
                    echo esc_html($post_type_obj->labels->singular_name);
                }
                ?>
            </span>
        </div><!-- .entry-meta -->
        <?php elseif ('product' === get_post_type() && class_exists('WooCommerce')) : ?>
        <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 flex items-center justify-between">
            <span class="post-type">
                <?php esc_html_e('Product', 'aqualuxe'); ?>
            </span>
            <span class="product-price font-medium text-primary-600 dark:text-primary-400">
                <?php wc_get_template('loop/price.php'); ?>
            </span>
        </div><!-- .entry-meta -->
        <?php else : ?>
        <div class="entry-meta text-sm text-gray-600 dark:text-gray-400">
            <span class="post-type">
                <?php
                $post_type_obj = get_post_type_object(get_post_type());
                if ($post_type_obj) {
                    echo esc_html($post_type_obj->labels->singular_name);
                }
                ?>
            </span>
        </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail()) : ?>
    <div class="flex flex-wrap md:flex-nowrap md:space-x-4">
        <div class="post-thumbnail w-full md:w-1/4 mb-4 md:mb-0">
            <a href="<?php the_permalink(); ?>">
                <?php the_post_thumbnail('thumbnail', array('class' => 'w-full h-auto rounded-md')); ?>
            </a>
        </div>
        <div class="w-full md:w-3/4">
    <?php endif; ?>

    <div class="entry-summary">
        <?php the_excerpt(); ?>
        <div class="mt-4">
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                <?php 
                if ('product' === get_post_type() && class_exists('WooCommerce')) {
                    esc_html_e('View Product', 'aqualuxe');
                } else {
                    esc_html_e('Read More', 'aqualuxe');
                }
                ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </a>
        </div>
    </div><!-- .entry-summary -->

    <?php if (has_post_thumbnail()) : ?>
        </div>
    </div>
    <?php endif; ?>

    <footer class="entry-footer mt-4 text-sm text-gray-500 dark:text-gray-400">
        <?php if ('post' === get_post_type() && has_category()) : ?>
            <div class="post-categories">
                <span class="font-medium"><?php esc_html_e('Categories:', 'aqualuxe'); ?></span> <?php the_category(', '); ?>
            </div>
        <?php endif; ?>
        
        <?php if ('product' === get_post_type() && class_exists('WooCommerce')) : 
            global $product;
            if ($product) :
                $categories = wc_get_product_category_list($product->get_id());
                if ($categories) :
        ?>
                <div class="product-categories">
                    <span class="font-medium"><?php esc_html_e('Categories:', 'aqualuxe'); ?></span> <?php echo wp_kses_post($categories); ?>
                </div>
        <?php 
                endif;
            endif;
        endif; ?>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->