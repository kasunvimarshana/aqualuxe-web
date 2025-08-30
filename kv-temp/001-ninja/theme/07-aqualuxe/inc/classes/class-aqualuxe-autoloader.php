<?php
/**
 * AquaLuxe Autoloader
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe Autoloader Class
 */
class AquaLuxe_Autoloader {
    /**
     * Classes map
     *
     * @var array
     */
    private static $classes_map = array();

    /**
     * Register autoloader
     */
    public static function run() {
        spl_autoload_register(array(__CLASS__, 'autoload'));
    }

    /**
     * Get classes map
     *
     * @return array
     */
    private static function get_classes_map() {
        if (empty(self::$classes_map)) {
            self::$classes_map = array(
                // Core classes
                'AquaLuxe_Assets' => 'inc/classes/class-aqualuxe-assets.php',
                'AquaLuxe_Theme' => 'inc/classes/class-aqualuxe-theme.php',
                'AquaLuxe_Template' => 'inc/classes/class-aqualuxe-template.php',
                'AquaLuxe_Admin' => 'inc/classes/class-aqualuxe-admin.php',
                
                // Customizer classes
                'AquaLuxe_Customizer' => 'inc/customizer/class-aqualuxe-customizer.php',
                'AquaLuxe_Customizer_Control_Heading' => 'inc/customizer/controls/class-aqualuxe-customizer-control-heading.php',
                'AquaLuxe_Customizer_Control_Separator' => 'inc/customizer/controls/class-aqualuxe-customizer-control-separator.php',
                'AquaLuxe_Customizer_Control_Slider' => 'inc/customizer/controls/class-aqualuxe-customizer-control-slider.php',
                'AquaLuxe_Customizer_Control_Toggle' => 'inc/customizer/controls/class-aqualuxe-customizer-control-toggle.php',
                'AquaLuxe_Customizer_Control_Radio_Image' => 'inc/customizer/controls/class-aqualuxe-customizer-control-radio-image.php',
                'AquaLuxe_Customizer_Control_Sortable' => 'inc/customizer/controls/class-aqualuxe-customizer-control-sortable.php',
                'AquaLuxe_Customizer_Control_Color_Alpha' => 'inc/customizer/controls/class-aqualuxe-customizer-control-color-alpha.php',
                'AquaLuxe_Customizer_Control_Dimensions' => 'inc/customizer/controls/class-aqualuxe-customizer-control-dimensions.php',
                'AquaLuxe_Customizer_Control_Typography' => 'inc/customizer/controls/class-aqualuxe-customizer-control-typography.php',
                
                // Widget classes
                'AquaLuxe_Widget_Recent_Posts' => 'inc/widgets/class-aqualuxe-widget-recent-posts.php',
                'AquaLuxe_Widget_Social_Icons' => 'inc/widgets/class-aqualuxe-widget-social-icons.php',
                'AquaLuxe_Widget_Contact_Info' => 'inc/widgets/class-aqualuxe-widget-contact-info.php',
                
                // WooCommerce classes
                'AquaLuxe_WooCommerce' => 'inc/classes/class-aqualuxe-woocommerce.php',
                'AquaLuxe_WooCommerce_Template' => 'inc/classes/class-aqualuxe-woocommerce-template.php',
                'AquaLuxe_WooCommerce_Wishlist' => 'inc/classes/class-aqualuxe-woocommerce-wishlist.php',
                'AquaLuxe_WooCommerce_Quick_View' => 'inc/classes/class-aqualuxe-woocommerce-quick-view.php',
                'AquaLuxe_WooCommerce_Ajax' => 'inc/classes/class-aqualuxe-woocommerce-ajax.php',
                
                // Multilingual classes
                'AquaLuxe_Multilingual' => 'inc/classes/class-aqualuxe-multilingual.php',
                
                // Demo importer
                'AquaLuxe_Demo_Importer' => 'inc/classes/class-aqualuxe-demo-importer.php',
            );
        }

        return self::$classes_map;
    }

    /**
     * Autoload function
     *
     * @param string $class Class name
     */
    public static function autoload($class) {
        // Check if class has AquaLuxe_ prefix
        if (strpos($class, 'AquaLuxe_') !== 0) {
            return;
        }

        $classes_map = self::get_classes_map();

        if (isset($classes_map[$class])) {
            $file = AQUALUXE_DIR . '/' . $classes_map[$class];
            
            if (file_exists($file)) {
                require_once $file;
            }
        } else {
            // Try to autoload based on class name
            $file_name = strtolower(
                preg_replace(
                    array('/^AquaLuxe_/', '/([a-z])([A-Z])/', '/_/', '/\\\/'),
                    array('', '$1-$2', '-', DIRECTORY_SEPARATOR),
                    $class
                )
            );

            $file = AQUALUXE_DIR . '/inc/classes/class-' . $file_name . '.php';

            if (file_exists($file)) {
                require_once $file;
            }
        }
    }
}

// Run the autoloader
AquaLuxe_Autoloader::run();