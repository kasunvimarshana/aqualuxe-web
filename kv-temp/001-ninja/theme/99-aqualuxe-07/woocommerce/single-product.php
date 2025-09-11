<?php
/**
 * Single product template
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop'); ?>

<div class="single-product-container">
    <?php while (have_posts()) : the_post(); ?>
        
        <div class="product-breadcrumb">
            <div class="container">
                <?php
                /**
                 * Hook: woocommerce_before_single_product_summary.
                 * Breadcrumbs and other content before the main product area
                 */
                // woocommerce_breadcrumb();
                ?>
                <nav class="breadcrumb" aria-label="<?php esc_attr_e('Breadcrumb', 'aqualuxe'); ?>">
                    <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'aqualuxe'); ?></a>
                    <span class="separator">›</span>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Shop', 'aqualuxe'); ?></a>
                    <?php
                    $product_cats = wp_get_post_terms(get_the_ID(), 'product_cat');
                    if (!empty($product_cats) && !is_wp_error($product_cats)) :
                        $first_cat = array_shift($product_cats);
                        ?>
                        <span class="separator">›</span>
                        <a href="<?php echo esc_url(get_term_link($first_cat)); ?>"><?php echo esc_html($first_cat->name); ?></a>
                    <?php endif; ?>
                    <span class="separator">›</span>
                    <span class="current"><?php the_title(); ?></span>
                </nav>
            </div>
        </div>

        <div class="product-main">
            <div class="container">
                <div id="product-<?php the_ID(); ?>" <?php wc_product_class('single-product-layout', get_queried_object()); ?>>
                    
                    <div class="product-gallery-summary">
                        <!-- Product Images -->
                        <div class="product-images">
                            <?php
                            /**
                             * Hook: woocommerce_before_single_product_summary.
                             * Product images, gallery, etc.
                             */
                            do_action('woocommerce_before_single_product_summary');
                            ?>
                        </div>

                        <!-- Product Summary -->
                        <div class="product-summary">
                            <?php
                            /**
                             * Hook: woocommerce_single_product_summary.
                             * Product title, price, add to cart, etc.
                             */
                            do_action('woocommerce_single_product_summary');
                            ?>

                            <!-- Additional product actions -->
                            <div class="product-actions-extra">
                                <div class="product-actions-row">
                                    <button class="wishlist-btn" data-product-id="<?php echo esc_attr(get_the_ID()); ?>">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                        </svg>
                                        <?php esc_html_e('Add to Wishlist', 'aqualuxe'); ?>
                                    </button>
                                    
                                    <button class="compare-btn" data-product-id="<?php echo esc_attr(get_the_ID()); ?>">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                        </svg>
                                        <?php esc_html_e('Compare', 'aqualuxe'); ?>
                                    </button>
                                </div>

                                <!-- Social sharing -->
                                <div class="product-share">
                                    <span class="share-label"><?php esc_html_e('Share:', 'aqualuxe'); ?></span>
                                    <div class="share-buttons">
                                        <a href="<?php echo esc_url('https://www.facebook.com/sharer/sharer.php?u=' . urlencode(get_permalink())); ?>" target="_blank" rel="noopener" class="share-btn facebook">
                                            <?php echo aqualuxe_get_social_icon('facebook'); ?>
                                        </a>
                                        <a href="<?php echo esc_url('https://twitter.com/intent/tweet?url=' . urlencode(get_permalink()) . '&text=' . urlencode(get_the_title())); ?>" target="_blank" rel="noopener" class="share-btn twitter">
                                            <?php echo aqualuxe_get_social_icon('twitter'); ?>
                                        </a>
                                        <a href="<?php echo esc_url('mailto:?subject=' . urlencode(get_the_title()) . '&body=' . urlencode(get_permalink())); ?>" class="share-btn email">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Data Tabs -->
                    <div class="product-tabs-wrapper">
                        <?php
                        /**
                         * Hook: woocommerce_after_single_product_summary.
                         * Product tabs, related products, etc.
                         */
                        do_action('woocommerce_after_single_product_summary');
                        ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Product Features Section -->
        <div class="product-features">
            <div class="container">
                <div class="features-grid">
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h4><?php esc_html_e('Free Shipping', 'aqualuxe'); ?></h4>
                            <p><?php esc_html_e('On orders over $75', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h4><?php esc_html_e('Quality Guarantee', 'aqualuxe'); ?></h4>
                            <p><?php esc_html_e('30-day money back', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h4><?php esc_html_e('Expert Support', 'aqualuxe'); ?></h4>
                            <p><?php esc_html_e('Professional advice', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    
                    <div class="feature-item">
                        <div class="feature-icon">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="feature-content">
                            <h4><?php esc_html_e('Fast Delivery', 'aqualuxe'); ?></h4>
                            <p><?php esc_html_e('Same day dispatch', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <?php endwhile; ?>
</div>

<?php get_footer('shop'); ?>