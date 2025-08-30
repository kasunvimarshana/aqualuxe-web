<?php
/**
 * Template part for displaying results in search pages
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 pb-8 border-b border-secondary-200 dark:border-secondary-700 last:border-0 last:pb-0'); ?>>
    <header class="entry-header mb-4">
        <?php the_title(sprintf('<h2 class="entry-title text-xl md:text-2xl font-bold mb-2"><a href="%s" rel="bookmark" class="hover:text-primary-500 transition-colors">', esc_url(get_permalink())), '</a></h2>'); ?>

        <?php if ('post' === get_post_type()) : ?>
            <div class="entry-meta text-sm text-secondary-500 dark:text-secondary-400">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php elseif ('page' === get_post_type()) : ?>
            <div class="entry-meta text-sm text-secondary-500 dark:text-secondary-400">
                <span class="post-type"><?php esc_html_e('Page', 'aqualuxe'); ?></span>
            </div><!-- .entry-meta -->
        <?php elseif ('product' === get_post_type() && class_exists('WooCommerce')) : ?>
            <div class="entry-meta text-sm text-secondary-500 dark:text-secondary-400">
                <span class="post-type"><?php esc_html_e('Product', 'aqualuxe'); ?></span>
                <?php if (function_exists('wc_get_product')) : 
                    $product = wc_get_product(get_the_ID());
                    if ($product) : ?>
                        <span class="product-price ml-2"><?php echo wp_kses_post($product->get_price_html()); ?></span>
                    <?php endif;
                endif; ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if (has_post_thumbnail()) : ?>
        <div class="post-thumbnail mb-4">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail('medium', array('class' => 'rounded-lg w-full h-auto')); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-summary">
        <?php the_excerpt(); ?>
    </div><!-- .entry-summary -->

    <footer class="entry-footer mt-4 text-sm">
        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-500 hover:text-primary-700 font-medium transition-colors">
            <?php 
            if ('product' === get_post_type() && class_exists('WooCommerce')) {
                esc_html_e('View Product', 'aqualuxe');
            } else {
                esc_html_e('Read More', 'aqualuxe');
            }
            ?>
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->