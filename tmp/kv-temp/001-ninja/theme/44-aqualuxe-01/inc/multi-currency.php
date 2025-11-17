<?php
/**
 * Multi-currency support for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize multi-currency support
 */
function aqualuxe_multi_currency_init() {
    // Only initialize if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Register currency switcher widget
    add_action('widgets_init', 'aqualuxe_register_currency_switcher_widget');
    
    // Add currency switcher to header
    add_action('aqualuxe_header_top_right', 'aqualuxe_header_currency_switcher', 20);
    
    // Add currency switcher to mobile menu
    add_action('aqualuxe_mobile_menu_after', 'aqualuxe_mobile_currency_switcher', 20);
    
    // Add currency cookie handling
    add_action('init', 'aqualuxe_handle_currency_cookie');
    
    // Filter product prices
    add_filter('woocommerce_product_get_price', 'aqualuxe_convert_price', 10, 2);
    add_filter('woocommerce_product_get_regular_price', 'aqualuxe_convert_price', 10, 2);
    add_filter('woocommerce_product_get_sale_price', 'aqualuxe_convert_price', 10, 2);
    add_filter('woocommerce_product_variation_get_price', 'aqualuxe_convert_price', 10, 2);
    add_filter('woocommerce_product_variation_get_regular_price', 'aqualuxe_convert_price', 10, 2);
    add_filter('woocommerce_product_variation_get_sale_price', 'aqualuxe_convert_price', 10, 2);
    
    // Filter formatted prices
    add_filter('woocommerce_get_price_html', 'aqualuxe_filter_price_html', 10, 2);
    
    // Add currency symbol to price format
    add_filter('woocommerce_currency', 'aqualuxe_get_current_currency');
    add_filter('woocommerce_currency_symbol', 'aqualuxe_get_current_currency_symbol');
}
add_action('after_setup_theme', 'aqualuxe_multi_currency_init');

/**
 * Register currency switcher widget
 */
function aqualuxe_register_currency_switcher_widget() {
    register_widget('AquaLuxe_Currency_Switcher_Widget');
}

/**
 * Get available currencies
 *
 * @return array Available currencies
 */
function aqualuxe_get_available_currencies() {
    $currencies = array(
        'USD' => array(
            'name'   => __('US Dollar', 'aqualuxe'),
            'symbol' => '$',
            'rate'   => 1, // Base currency
        ),
        'EUR' => array(
            'name'   => __('Euro', 'aqualuxe'),
            'symbol' => '€',
            'rate'   => 0.85, // Example rate
        ),
        'GBP' => array(
            'name'   => __('British Pound', 'aqualuxe'),
            'symbol' => '£',
            'rate'   => 0.75, // Example rate
        ),
        'CAD' => array(
            'name'   => __('Canadian Dollar', 'aqualuxe'),
            'symbol' => 'CA$',
            'rate'   => 1.25, // Example rate
        ),
        'AUD' => array(
            'name'   => __('Australian Dollar', 'aqualuxe'),
            'symbol' => 'A$',
            'rate'   => 1.35, // Example rate
        ),
    );
    
    // Allow filtering of currencies
    return apply_filters('aqualuxe_available_currencies', $currencies);
}

/**
 * Get base currency
 *
 * @return string Base currency code
 */
function aqualuxe_get_base_currency() {
    return apply_filters('aqualuxe_base_currency', 'USD');
}

/**
 * Get current currency
 *
 * @return string Current currency code
 */
function aqualuxe_get_current_currency() {
    $base_currency = aqualuxe_get_base_currency();
    $current_currency = isset($_COOKIE['aqualuxe_currency']) ? sanitize_text_field($_COOKIE['aqualuxe_currency']) : $base_currency;
    
    // Validate currency
    $available_currencies = aqualuxe_get_available_currencies();
    if (!isset($available_currencies[$current_currency])) {
        $current_currency = $base_currency;
    }
    
    return apply_filters('aqualuxe_current_currency', $current_currency);
}

/**
 * Get current currency symbol
 *
 * @param string $symbol Default currency symbol
 * @return string Current currency symbol
 */
function aqualuxe_get_current_currency_symbol($symbol = '') {
    $current_currency = aqualuxe_get_current_currency();
    $available_currencies = aqualuxe_get_available_currencies();
    
    if (isset($available_currencies[$current_currency]['symbol'])) {
        $symbol = $available_currencies[$current_currency]['symbol'];
    }
    
    return $symbol;
}

/**
 * Get currency exchange rate
 *
 * @param string $currency Currency code
 * @return float Exchange rate
 */
function aqualuxe_get_currency_rate($currency = '') {
    if (empty($currency)) {
        $currency = aqualuxe_get_current_currency();
    }
    
    $available_currencies = aqualuxe_get_available_currencies();
    $rate = 1;
    
    if (isset($available_currencies[$currency]['rate'])) {
        $rate = $available_currencies[$currency]['rate'];
    }
    
    return apply_filters('aqualuxe_currency_rate', $rate, $currency);
}

/**
 * Convert price to current currency
 *
 * @param float  $price   Price in base currency
 * @param object $product Product object
 * @return float Price in current currency
 */
function aqualuxe_convert_price($price, $product) {
    if (empty($price)) {
        return $price;
    }
    
    $current_currency = aqualuxe_get_current_currency();
    $base_currency = aqualuxe_get_base_currency();
    
    // If current currency is the same as base currency, return original price
    if ($current_currency === $base_currency) {
        return $price;
    }
    
    // Get exchange rate
    $rate = aqualuxe_get_currency_rate($current_currency);
    
    // Convert price
    $converted_price = $price * $rate;
    
    return apply_filters('aqualuxe_converted_price', $converted_price, $price, $rate, $current_currency, $product);
}

/**
 * Filter price HTML to show currency code
 *
 * @param string $price_html Price HTML
 * @param object $product    Product object
 * @return string Modified price HTML
 */
function aqualuxe_filter_price_html($price_html, $product) {
    $current_currency = aqualuxe_get_current_currency();
    
    // Add currency code to price HTML
    $price_html = str_replace('</span>', ' ' . $current_currency . '</span>', $price_html);
    
    return $price_html;
}

/**
 * Handle currency cookie
 */
function aqualuxe_handle_currency_cookie() {
    if (isset($_GET['currency']) && !empty($_GET['currency'])) {
        $currency = sanitize_text_field($_GET['currency']);
        $available_currencies = aqualuxe_get_available_currencies();
        
        // Validate currency
        if (isset($available_currencies[$currency])) {
            setcookie('aqualuxe_currency', $currency, time() + (86400 * 30), '/'); // 30 days
            $_COOKIE['aqualuxe_currency'] = $currency;
        }
    }
}

/**
 * Get currency switcher HTML
 *
 * @param array $args Arguments for the currency switcher
 * @return string Currency switcher HTML
 */
function aqualuxe_get_currency_switcher($args = array()) {
    // Only show if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return '';
    }
    
    $defaults = array(
        'dropdown'     => false,
        'show_symbols' => true,
        'show_names'   => false,
        'classes'      => 'aqualuxe-currency-switcher',
    );
    
    $args = wp_parse_args($args, $defaults);
    $output = '';
    
    $available_currencies = aqualuxe_get_available_currencies();
    $current_currency = aqualuxe_get_current_currency();
    
    if (!empty($available_currencies)) {
        if ($args['dropdown']) {
            $output .= '<div class="' . esc_attr($args['classes']) . ' dropdown">';
            $output .= '<div class="current-currency dropdown-toggle" data-toggle="dropdown">';
            
            if (isset($available_currencies[$current_currency])) {
                if ($args['show_symbols']) {
                    $output .= '<span class="currency-symbol">' . esc_html($available_currencies[$current_currency]['symbol']) . '</span>';
                }
                
                $output .= '<span class="currency-code">' . esc_html($current_currency) . '</span>';
                
                if ($args['show_names']) {
                    $output .= '<span class="currency-name">' . esc_html($available_currencies[$current_currency]['name']) . '</span>';
                }
                
                $output .= '<i class="fas fa-chevron-down"></i>';
            }
            
            $output .= '</div>';
            $output .= '<ul class="dropdown-menu">';
            
            foreach ($available_currencies as $code => $currency) {
                $url = add_query_arg('currency', $code);
                $output .= '<li' . ($code === $current_currency ? ' class="active"' : '') . '>';
                $output .= '<a href="' . esc_url($url) . '">';
                
                if ($args['show_symbols']) {
                    $output .= '<span class="currency-symbol">' . esc_html($currency['symbol']) . '</span>';
                }
                
                $output .= '<span class="currency-code">' . esc_html($code) . '</span>';
                
                if ($args['show_names']) {
                    $output .= '<span class="currency-name">' . esc_html($currency['name']) . '</span>';
                }
                
                $output .= '</a>';
                $output .= '</li>';
            }
            
            $output .= '</ul>';
            $output .= '</div>';
        } else {
            $output .= '<ul class="' . esc_attr($args['classes']) . '">';
            
            foreach ($available_currencies as $code => $currency) {
                $url = add_query_arg('currency', $code);
                $output .= '<li' . ($code === $current_currency ? ' class="active"' : '') . '>';
                $output .= '<a href="' . esc_url($url) . '">';
                
                if ($args['show_symbols']) {
                    $output .= '<span class="currency-symbol">' . esc_html($currency['symbol']) . '</span>';
                }
                
                $output .= '<span class="currency-code">' . esc_html($code) . '</span>';
                
                if ($args['show_names']) {
                    $output .= '<span class="currency-name">' . esc_html($currency['name']) . '</span>';
                }
                
                $output .= '</a>';
                $output .= '</li>';
            }
            
            $output .= '</ul>';
        }
    }
    
    return $output;
}

/**
 * Add currency switcher to header
 */
function aqualuxe_header_currency_switcher() {
    // Only show if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Get currency switcher
    $currency_switcher = aqualuxe_get_currency_switcher(array(
        'dropdown'     => true,
        'show_symbols' => true,
        'show_names'   => false,
        'classes'      => 'header-currency-switcher',
    ));
    
    // Output currency switcher
    if (!empty($currency_switcher)) {
        echo '<div class="header-currency-switcher-wrapper">' . $currency_switcher . '</div>';
    }
}

/**
 * Add currency switcher to mobile menu
 */
function aqualuxe_mobile_currency_switcher() {
    // Only show if WooCommerce is active
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Get currency switcher
    $currency_switcher = aqualuxe_get_currency_switcher(array(
        'dropdown'     => false,
        'show_symbols' => true,
        'show_names'   => true,
        'classes'      => 'mobile-currency-switcher',
    ));
    
    // Output currency switcher
    if (!empty($currency_switcher)) {
        echo '<div class="mobile-currency-switcher-wrapper">';
        echo '<h4>' . esc_html__('Select Currency', 'aqualuxe') . '</h4>';
        echo $currency_switcher;
        echo '</div>';
    }
}

/**
 * Currency Switcher Widget
 */
class AquaLuxe_Currency_Switcher_Widget extends WP_Widget {
    /**
     * Register widget with WordPress
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_currency_switcher',
            esc_html__('AquaLuxe Currency Switcher', 'aqualuxe'),
            array(
                'description' => esc_html__('Display a currency switcher', 'aqualuxe'),
                'classname'   => 'widget_currency_switcher',
            )
        );
    }

    /**
     * Front-end display of widget
     *
     * @param array $args     Widget arguments
     * @param array $instance Saved values from database
     */
    public function widget($args, $instance) {
        // Only show if WooCommerce is active
        if (!aqualuxe_is_woocommerce_active()) {
            return;
        }
        
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        // Get currency switcher
        $currency_switcher = aqualuxe_get_currency_switcher(array(
            'dropdown'     => !empty($instance['dropdown']),
            'show_symbols' => !empty($instance['show_symbols']),
            'show_names'   => !empty($instance['show_names']),
            'classes'      => 'widget-currency-switcher',
        ));
        
        // Output currency switcher
        echo $currency_switcher;
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form
     *
     * @param array $instance Previously saved values from database
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : esc_html__('Currency', 'aqualuxe');
        $dropdown = !empty($instance['dropdown']) ? (bool) $instance['dropdown'] : false;
        $show_symbols = !empty($instance['show_symbols']) ? (bool) $instance['show_symbols'] : true;
        $show_names = !empty($instance['show_names']) ? (bool) $instance['show_names'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($dropdown); ?> id="<?php echo esc_attr($this->get_field_id('dropdown')); ?>" name="<?php echo esc_attr($this->get_field_name('dropdown')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('dropdown')); ?>"><?php esc_html_e('Display as dropdown', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_symbols); ?> id="<?php echo esc_attr($this->get_field_id('show_symbols')); ?>" name="<?php echo esc_attr($this->get_field_name('show_symbols')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_symbols')); ?>"><?php esc_html_e('Show currency symbols', 'aqualuxe'); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked($show_names); ?> id="<?php echo esc_attr($this->get_field_id('show_names')); ?>" name="<?php echo esc_attr($this->get_field_name('show_names')); ?>">
            <label for="<?php echo esc_attr($this->get_field_id('show_names')); ?>"><?php esc_html_e('Show currency names', 'aqualuxe'); ?></label>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved
     *
     * @param array $new_instance Values just sent to be saved
     * @param array $old_instance Previously saved values from database
     * @return array Updated safe values to be saved
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['dropdown'] = (!empty($new_instance['dropdown'])) ? 1 : 0;
        $instance['show_symbols'] = (!empty($new_instance['show_symbols'])) ? 1 : 0;
        $instance['show_names'] = (!empty($new_instance['show_names'])) ? 1 : 0;
        
        return $instance;
    }
}