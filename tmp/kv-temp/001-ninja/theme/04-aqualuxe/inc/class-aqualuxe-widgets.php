<?php
/**
 * AquaLuxe Widgets Class
 *
 * Handles all custom widgets
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Widgets Class
 */
class AquaLuxe_Widgets {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Widgets
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Widgets
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Register widgets
        add_action('widgets_init', array($this, 'register_widgets'));
    }

    /**
     * Register widgets
     */
    public function register_widgets() {
        // Include widget classes
        require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-about-widget.php';
        require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-recent-posts-widget.php';
        require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-social-widget.php';
        require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-contact-widget.php';
        require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-newsletter-widget.php';
        
        // WooCommerce specific widgets
        if (class_exists('WooCommerce')) {
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-product-filter-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-product-categories-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-product-brands-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-product-tags-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-product-price-filter-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-product-rating-filter-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-product-attribute-filter-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-featured-products-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-recent-products-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-sale-products-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-best-selling-products-widget.php';
            require_once AQUALUXE_DIR . '/inc/widgets/class-aqualuxe-top-rated-products-widget.php';
        }
        
        // Register widgets
        register_widget('AquaLuxe_About_Widget');
        register_widget('AquaLuxe_Recent_Posts_Widget');
        register_widget('AquaLuxe_Social_Widget');
        register_widget('AquaLuxe_Contact_Widget');
        register_widget('AquaLuxe_Newsletter_Widget');
        
        // Register WooCommerce specific widgets
        if (class_exists('WooCommerce')) {
            register_widget('AquaLuxe_Product_Filter_Widget');
            register_widget('AquaLuxe_Product_Categories_Widget');
            register_widget('AquaLuxe_Product_Brands_Widget');
            register_widget('AquaLuxe_Product_Tags_Widget');
            register_widget('AquaLuxe_Product_Price_Filter_Widget');
            register_widget('AquaLuxe_Product_Rating_Filter_Widget');
            register_widget('AquaLuxe_Product_Attribute_Filter_Widget');
            register_widget('AquaLuxe_Featured_Products_Widget');
            register_widget('AquaLuxe_Recent_Products_Widget');
            register_widget('AquaLuxe_Sale_Products_Widget');
            register_widget('AquaLuxe_Best_Selling_Products_Widget');
            register_widget('AquaLuxe_Top_Rated_Products_Widget');
        }
    }
}