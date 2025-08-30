<?php
/**
 * WooCommerce Fallback Templates
 *
 * Functions to handle fallback templates when WooCommerce is not active
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Load appropriate fallback template based on page type
 *
 * @return void
 */
function aqualuxe_load_woocommerce_fallback_template() {
    // Only load fallback if WooCommerce is not active
    if (aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    global $post;
    
    if (!$post) {
        return;
    }
    
    // Determine which fallback template to load
    $template_path = '';
    
    if ($post->post_name === 'shop' || $post->post_title === 'Shop') {
        $template_path = 'template-parts/woocommerce/fallback-shop.php';
    } elseif ($post->post_name === 'cart' || $post->post_title === 'Cart') {
        $template_path = 'template-parts/woocommerce/fallback-cart.php';
    } elseif ($post->post_name === 'checkout' || $post->post_title === 'Checkout') {
        $template_path = 'template-parts/woocommerce/fallback-checkout.php';
    } elseif ($post->post_name === 'my-account' || $post->post_title === 'My Account') {
        $template_path = 'template-parts/woocommerce/fallback-account.php';
    } elseif ($post->post_type === 'product') {
        $template_path = 'template-parts/woocommerce/fallback-product.php';
    } elseif (aqualuxe_is_woocommerce_page_by_slug()) {
        $template_path = 'template-parts/woocommerce/fallback-shop.php';
    }
    
    // Load the fallback template if found
    if (!empty($template_path) && file_exists(get_template_directory() . '/' . $template_path)) {
        include(get_template_directory() . '/' . $template_path);
        exit; // Stop further execution
    }
}

/**
 * Hook into template_redirect to check for WooCommerce pages
 */
function aqualuxe_check_woocommerce_pages() {
    // Only check if WooCommerce is not active
    if (!aqualuxe_is_woocommerce_active()) {
        // Check if current page is a WooCommerce page
        if (aqualuxe_is_woocommerce_page_by_slug()) {
            add_action('template_include', 'aqualuxe_woocommerce_fallback_template_include');
        }
    }
}
add_action('template_redirect', 'aqualuxe_check_woocommerce_pages');

/**
 * Include fallback template
 *
 * @param string $template Original template path
 * @return string Modified template path
 */
function aqualuxe_woocommerce_fallback_template_include($template) {
    // Get the template name
    $template_name = basename($template);
    
    // Create a custom template path
    $custom_template = get_template_directory() . '/page.php';
    
    // Check if the custom template exists
    if (file_exists($custom_template)) {
        // Set a flag to load fallback content
        set_query_var('load_woocommerce_fallback', true);
        return $custom_template;
    }
    
    return $template;
}

/**
 * Display fallback content in page template
 */
function aqualuxe_display_woocommerce_fallback() {
    // Check if we should load fallback content
    if (get_query_var('load_woocommerce_fallback', false)) {
        aqualuxe_load_woocommerce_fallback_template();
    }
}
add_action('aqualuxe_before_page_content', 'aqualuxe_display_woocommerce_fallback');

/**
 * Add a notice to the admin dashboard if WooCommerce is not active
 */
function aqualuxe_woocommerce_admin_notice() {
    // Only show notice to admins
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Only show notice if WooCommerce is not active
    if (aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Check if notice has been dismissed
    $dismissed = get_option('aqualuxe_woocommerce_notice_dismissed', false);
    if ($dismissed) {
        return;
    }
    
    ?>
    <div class="notice notice-info is-dismissible aqualuxe-woocommerce-notice">
        <p>
            <?php 
            printf(
                __('The AquaLuxe theme works best with WooCommerce. Some features may be limited without it. <a href="%s">Install WooCommerce</a> or <a href="#" class="aqualuxe-dismiss-notice">dismiss this notice</a>.', 'aqualuxe'),
                admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')
            ); 
            ?>
        </p>
    </div>
    <script>
        jQuery(document).ready(function($) {
            $('.aqualuxe-dismiss-notice').on('click', function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_dismiss_woocommerce_notice',
                        nonce: '<?php echo wp_create_nonce('aqualuxe-dismiss-notice'); ?>'
                    }
                });
                
                $(this).closest('.aqualuxe-woocommerce-notice').fadeOut();
            });
        });
    </script>
    <?php
}
add_action('admin_notices', 'aqualuxe_woocommerce_admin_notice');

/**
 * AJAX handler to dismiss the WooCommerce notice
 */
function aqualuxe_dismiss_woocommerce_notice() {
    // Verify nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-dismiss-notice')) {
        wp_send_json_error('Invalid nonce');
        exit;
    }
    
    // Update option to dismiss notice
    update_option('aqualuxe_woocommerce_notice_dismissed', true);
    
    wp_send_json_success();
    exit;
}
add_action('wp_ajax_aqualuxe_dismiss_woocommerce_notice', 'aqualuxe_dismiss_woocommerce_notice');

/**
 * Register a custom widget area for WooCommerce fallback content
 */
function aqualuxe_register_woocommerce_fallback_widget_area() {
    register_sidebar(
        array(
            'name'          => esc_html__('WooCommerce Fallback', 'aqualuxe'),
            'id'            => 'woocommerce-fallback',
            'description'   => esc_html__('Add widgets here to appear on WooCommerce pages when WooCommerce is not active.', 'aqualuxe'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        )
    );
}
add_action('widgets_init', 'aqualuxe_register_woocommerce_fallback_widget_area');

/**
 * Display WooCommerce fallback widget area
 */
function aqualuxe_display_woocommerce_fallback_widgets() {
    if (is_active_sidebar('woocommerce-fallback')) {
        ?>
        <div class="woocommerce-fallback-widgets">
            <?php dynamic_sidebar('woocommerce-fallback'); ?>
        </div>
        <?php
    }
}