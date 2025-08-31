<?php
namespace AquaLuxe\Modules\Wishlist;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        add_action('init', [__CLASS__, 'register_endpoint']);
        add_shortcode('aqlx_wishlist', [__CLASS__, 'render_page']);
        add_action('wp_ajax_aqlx_toggle_wishlist', [__CLASS__, 'toggle']);
        add_action('wp_ajax_nopriv_aqlx_toggle_wishlist', [__CLASS__, 'toggle']);
        if (class_exists('WooCommerce')) {
            add_action('woocommerce_after_shop_loop_item', [__CLASS__, 'button_for_loop'], 15);
        }
        add_action('wp_enqueue_scripts', [__CLASS__, 'js']);
    }

    public static function register_endpoint(): void {
        // Store wishlist in user meta or cookie.
    }

    public static function key(): string { return 'aqlx_wishlist'; }

    public static function get_list(): array {
        if (is_user_logged_in()) {
            $ids = get_user_meta(get_current_user_id(), self::key(), true);
            return is_array($ids) ? $ids : [];
        }
        $cookie = isset($_COOKIE[self::key()]) ? wp_unslash($_COOKIE[self::key()]) : '';
        $ids = $cookie ? array_filter(array_map('absint', explode(',', $cookie))) : [];
        return $ids;
    }

    public static function save_list(array $ids): void {
        $ids = array_values(array_unique(array_filter(array_map('absint', $ids))));
        if (is_user_logged_in()) {
            update_user_meta(get_current_user_id(), self::key(), $ids);
        } else {
            setcookie(self::key(), implode(',', $ids), time()+YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
        }
    }

    public static function toggle(): void {
        check_ajax_referer('aqualuxe-nonce', 'nonce');
        $id = isset($_POST['id']) ? absint($_POST['id']) : 0;
        if (!$id) wp_send_json_error(['message' => 'Invalid']);
        $ids = self::get_list();
        if (in_array($id, $ids, true)) {
            $ids = array_values(array_diff($ids, [$id]));
            $state = 'removed';
        } else {
            $ids[] = $id;
            $state = 'added';
        }
        self::save_list($ids);
        wp_send_json_success(['state' => $state, 'count' => count($ids)]);
    }

    public static function render_button(int $product_id): string {
        $ids = self::get_list();
        $active = in_array($product_id, $ids, true);
        $label = $active ? __('Wishlisted', 'aqualuxe') : __('Add to wishlist', 'aqualuxe');
        return '<button class="aqlx-wishlist-btn ' . ($active ? 'is-active' : '') . '" data-product-id="' . (int)$product_id . '">' . esc_html($label) . '</button>';
    }

    public static function button_for_loop(): void {
        global $product;
        if (!$product) return;
        echo '<div class="mt-2">' . self::render_button((int)$product->get_id()) . '</div>';
    }

    public static function js(): void {
        wp_add_inline_script('aqualuxe-app', "document.addEventListener('click',function(e){var b=e.target.closest('.aqlx-wishlist-btn');if(!b)return;e.preventDefault();var id=b.getAttribute('data-product-id');var fd=new FormData();fd.append('action','aqlx_toggle_wishlist');fd.append('nonce',AquaLuxe.nonce);fd.append('id',id);fetch(AquaLuxe.ajaxUrl,{method:'POST',body:fd}).then(r=>r.json()).then(d=>{if(d && d.success){b.classList.toggle('is-active');b.textContent=b.classList.contains('is-active')?'" . esc_js(__('Wishlisted','aqualuxe')) . "':'" . esc_js(__('Add to wishlist','aqualuxe')) . "';}});});", 'after');
    }

    public static function render_page(): string {
        $ids = self::get_list();
        if (empty($ids)) return '<p>' . esc_html__('Your wishlist is empty.', 'aqualuxe') . '</p>';
        if (function_exists('do_shortcode')) {
            return do_shortcode('[products ids="' . implode(',', $ids) . '"]');
        }
        return '<ul><li>' . implode('</li><li>', array_map('esc_html', $ids)) . '</li></ul>';
    }
}
