<?php

/**
 * AquaLuxe WooCommerce Class
 *
 * @package aqualuxe
 */

if (! defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe WooCommerce class
 */
class AquaLuxe_WooCommerce
{

    /**
     * Constructor
     */
    public function __construct()
    {
        // Initialize
        $this->init();
    }

    /**
     * Initialize the class
     */
    private function init()
    {
        // Actions
        add_action('after_setup_theme', array($this, 'setup'));
    }

    /**
     * Theme setup
     */
    public function setup()
    {
        // Add theme support
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');
    }

    /**
     * Wrapper start
     */
    public static function wrapper_start()
    {
        echo '<div class="aqualuxe-content"><div class="container"><div class="row"><div class="col-md-12">';
    }

    /**
     * Wrapper end
     */
    public static function wrapper_end()
    {
        echo '</div></div></div></div>';
    }

    /**
     * Product thumbnail
     */
    public static function product_thumbnail()
    {
        global $product;
        echo '<div class="product-thumbnail">' . woocommerce_get_product_thumbnail() . '</div>';
    }

    /**
     * Product title
     */
    public static function product_title()
    {
        global $product;
        echo '<h3 class="product-title"><a href="' . get_permalink() . '">' . get_the_title() . '</a></h3>';
    }

    /**
     * Product price
     */
    public static function product_price()
    {
        global $product;
        echo '<div class="product-price">' . $product->get_price_html() . '</div>';
    }

    /**
     * Add to cart
     */
    public static function add_to_cart()
    {
        global $product;
        echo '<div class="product-add-to-cart">';
        woocommerce_template_loop_add_to_cart();
        echo '</div>';
    }
}

new AquaLuxe_WooCommerce();
