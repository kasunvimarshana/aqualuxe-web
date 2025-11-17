<?php
namespace AquaLuxe;

if (!class_exists('WooCommerce')) { return; }

class WooIntegration {
    public static function init(): void {
        \add_action('wp_ajax_aqualuxe_quickview', [self::class, 'quickview']);
        \add_action('wp_ajax_nopriv_aqualuxe_quickview', [self::class, 'quickview']);
        \add_action('wp_enqueue_scripts', [self::class, 'localize']);
        // Styling hooks for checkout/address fields and buttons
        \add_filter('woocommerce_checkout_fields', [self::class, 'style_checkout_fields']);
        \add_filter('woocommerce_default_address_fields', [self::class, 'style_address_fields']);
        \add_filter('woocommerce_order_button_html', [self::class, 'style_place_order_button']);
    }

    public static function localize(): void {
        \wp_localize_script('aqualuxe-woo', 'AquaLuxeWoo', [
            'ajax_url' => \admin_url('admin-ajax.php'),
            'nonce' => \wp_create_nonce('aqualuxe_woo'),
            'wishlist_ajax' => \admin_url('admin-ajax.php?action=aqualuxe_wishlist_toggle'),
            'wishlist_nonce' => \wp_create_nonce('aqualuxe_wishlist'),
            'logged_in' => \is_user_logged_in(),
        ]);
    }

    public static function style_checkout_fields(array $fields): array {
        $fields = array_map(function($group){
            if (!is_array($group)) return $group;
            foreach ($group as $key => &$field) {
                $field['class'] = array_values(array_unique(array_merge($field['class'] ?? [], ['mb-4'])));
                $input = ['border','rounded','px-3','py-2','w-full'];
                $field['input_class'] = array_values(array_unique(array_merge($field['input_class'] ?? [], $input)));
                $field['label_class'] = array_values(array_unique(array_merge($field['label_class'] ?? [], ['block','text-sm','mb-1'])));
            }
            return $group;
        }, $fields);
        return $fields;
    }

    public static function style_address_fields(array $fields): array {
        foreach ($fields as $key => &$field) {
            $field['class'] = array_values(array_unique(array_merge($field['class'] ?? [], ['mb-4'])));
            $input = ['border','rounded','px-3','py-2','w-full'];
            $field['input_class'] = array_values(array_unique(array_merge($field['input_class'] ?? [], $input)));
            $field['label_class'] = array_values(array_unique(array_merge($field['label_class'] ?? [], ['block','text-sm','mb-1'])));
        }
        return $fields;
    }

    public static function style_place_order_button(string $html): string {
        // Add btn-primary and utility classes to the Place order button
        if (strpos($html, 'btn-primary') === false) {
            $html = preg_replace('/class=("|\\')(.*?)(\1)/', 'class=$1$2 btn-primary inline-flex items-center justify-center$1', $html, 1) ?: $html;
        }
        return $html;
    }

    public static function quickview(): void {
        \check_ajax_referer('aqualuxe_woo');
        $id = \absint($_GET['id'] ?? 0);
        if (!$id) \wp_send_json_error(['message' => __('Invalid product ID', 'aqualuxe')]);
        $product = \wc_get_product($id);
        if (!$product) \wp_send_json_error(['message' => __('Product not found', 'aqualuxe')]);
        \ob_start();
        $GLOBALS['post'] = \get_post($id); \setup_postdata($GLOBALS['post']);
        \locate_template('templates/woo-quickview.php', true, false);
        \wp_reset_postdata();
        $html = \ob_get_clean();
        \wp_send_json_success(['html' => $html]);
    }
}

WooIntegration::init();
