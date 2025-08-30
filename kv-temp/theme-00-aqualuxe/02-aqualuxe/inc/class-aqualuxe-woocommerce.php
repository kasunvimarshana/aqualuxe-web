<?php
/**
 * AquaLuxe WooCommerce Integration
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class AquaLuxe_WooCommerce {
    
    /**
     * Initialize WooCommerce features
     */
    public static function init() {
        add_action( 'after_setup_theme', array( __CLASS__, 'setup' ) );
        add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );
        add_filter( 'woocommerce_enqueue_styles', array( __CLASS__, 'dequeue_styles' ) );
        add_filter( 'loop_shop_per_page', array( __CLASS__, 'products_per_page' ), 20 );
        add_filter( 'loop_shop_columns', array( __CLASS__, 'shop_columns' ) );
        add_filter( 'woocommerce_product_tabs', array( __CLASS__, 'product_tabs' ) );
        add_filter( 'woocommerce_breadcrumb_defaults', array( __CLASS__, 'breadcrumb_defaults' ) );
        add_filter( 'woocommerce_output_related_products_args', array( __CLASS__, 'related_products_args' ) );
    }
    
    /**
     * WooCommerce setup
     */
    public static function setup() {
        // Add WooCommerce support
        add_theme_support( 'woocommerce' );
        add_theme_support( 'wc-product-gallery-zoom' );
        add_theme_support( 'wc-product-gallery-lightbox' );
        add_theme_support( 'wc-product-gallery-slider' );
    }
    
    /**
     * Enqueue WooCommerce scripts
     */
    public static function enqueue_scripts() {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        wp_enqueue_script(
            'aqualuxe-woocommerce',
            get_stylesheet_directory_uri() . '/assets/js/woocommerce.js',
            array( 'jquery', 'wc-add-to-cart', 'wc-cart-fragments' ),
            '1.0.0',
            true
        );
    }
    
    /**
     * Dequeue default WooCommerce styles
     */
    public static function dequeue_styles( $enqueue_styles ) {
        unset( $enqueue_styles['woocommerce-general'] );
        unset( $enqueue_styles['woocommerce-layout'] );
        unset( $enqueue_styles['woocommerce-smallscreen'] );
        return $enqueue_styles;
    }
    
    /**
     * Products per page
     */
    public static function products_per_page( $per_page ) {
        return 12;
    }
    
    /**
     * Shop columns
     */
    public static function shop_columns() {
        return 4;
    }
    
    /**
     * Product tabs
     */
    public static function product_tabs( $tabs ) {
        global $product;
        
        // Add custom tab
        $tabs['care_guide'] = array(
            'title'    => __( 'Care Guide', 'aqualuxe' ),
            'priority' => 30,
            'callback' => array( __CLASS__, 'care_guide_tab_content' ),
        );
        
        return $tabs;
    }
    
    /**
     * Care guide tab content
     */
    public static function care_guide_tab_content() {
        global $product;
        
        $care_guide = get_post_meta( $product->get_id(), '_aqualuxe_care_guide', true );
        
        if ( $care_guide ) {
            echo wpautop( $care_guide );
        } else {
            echo '<p>' . __( 'Care guide coming soon.', 'aqualuxe' ) . '</p>';
        }
    }
    
    /**
     * Breadcrumb defaults
     */
    public static function breadcrumb_defaults( $defaults ) {
        $defaults['delimiter'] = '<span class="breadcrumb-separator">/</span>';
        $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb">';
        $defaults['wrap_after'] = '</nav>';
        return $defaults;
    }
    
    /**
     * Related products args
     */
    public static function related_products_args( $args ) {
        $args['posts_per_page'] = 4;
        $args['columns'] = 4;
        return $args;
    }
}

// Initialize
AquaLuxe_WooCommerce::init();
