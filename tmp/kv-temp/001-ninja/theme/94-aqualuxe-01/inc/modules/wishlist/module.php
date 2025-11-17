<?php
namespace AquaLuxe\Modules\Wishlist;

if (!defined('ABSPATH')) { exit; }

class Wishlist {
    public static function init(): void {
        if (!class_exists('WooCommerce')) { return; }
        \add_action('wp_ajax_al_toggle_wishlist', [__CLASS__, 'toggle']);
        \add_action('wp_ajax_nopriv_al_toggle_wishlist', [__CLASS__, 'toggle']);
        \add_shortcode('al_wishlist', [__CLASS__, 'render_list']);
    }

    private static function get_key(): string { return \is_user_logged_in() ? 'user_' . \get_current_user_id() : 'guest_' . self::guest_id(); }
    private static function guest_id(): string {
        if (isset($_COOKIE['al_wl'])) return \sanitize_text_field(\wp_unslash($_COOKIE['al_wl']));
        $id = wp_generate_password(12, false);
        \setcookie('al_wl', $id, time()+\MONTH_IN_SECONDS, \COOKIEPATH, \COOKIE_DOMAIN, \is_ssl());
        return $id;
    }

    private static function store(array $ids): void { \set_transient('al_wl_' . self::get_key(), array_map('absint', $ids), \WEEK_IN_SECONDS); }
    private static function load(): array { return (array) \get_transient('al_wl_' . self::get_key()) ?: []; }

    public static function toggle(): void {
    \check_ajax_referer('aqualuxe_nonce', 'nonce');
    $pid = isset($_POST['product_id']) ? \absint($_POST['product_id']) : 0;
    if ($pid < 1) \wp_send_json_error('bad_id');
        $ids = self::load();
        if (in_array($pid, $ids, true)) { $ids = array_values(array_diff($ids, [$pid])); $state = 'removed'; }
        else { $ids[] = $pid; $state = 'added'; }
        self::store($ids);
    \wp_send_json_success(['state'=>$state,'ids'=>$ids]);
    }

    public static function render_list(): string {
        $ids = self::load();
        if (empty($ids)) return '<p>' . esc_html__('Your wishlist is empty.', 'aqualuxe') . '</p>';
        $q = new \WP_Query(['post_type'=>'product','post__in'=>$ids,'posts_per_page'=>-1]);
        ob_start();
        echo '<div class="grid grid-cols-2 md:grid-cols-4 gap-6">';
        while ($q->have_posts()): $q->the_post();
            \wc_get_template_part('content', 'product');
        endwhile; \wp_reset_postdata();
        echo '</div>';
        return ob_get_clean();
    }
}
