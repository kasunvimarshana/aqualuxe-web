<?php
namespace AquaLuxe\Modules\MultiCurrency;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        add_filter('woocommerce_currency', [__CLASS__, 'currency']);
        add_action('wp_footer', [__CLASS__, 'switcher']);
    }

    public static function currency($currency) {
        if (!isset($_GET['cur'])) return $currency;
        $cur = strtoupper(sanitize_text_field($_GET['cur']));
        $allowed = apply_filters('aqualuxe/currencies', ['USD','EUR','GBP']);
        if (in_array($cur, $allowed, true)) return $cur;
        return $currency;
    }

    public static function switcher(): void {
        $allowed = apply_filters('aqualuxe/currencies', ['USD','EUR','GBP']);
        echo '<div class="fixed bottom-20 left-4 bg-white/90 dark:bg-slate-900/90 p-2 rounded shadow">';
        echo '<form method="get">';
        echo '<select name="cur" class="min-w-[100px]">';
        foreach ($allowed as $c) {
            $sel = selected(isset($_GET['cur']) && strtoupper($_GET['cur'])===$c, true, false);
            echo '<option ' . $sel . ' value="' . esc_attr($c) . '">' . esc_html($c) . '</option>';
        }
        echo '</select> <button class="ml-2 px-3 py-1 bg-sky-600 text-white rounded">' . esc_html__('Set','aqualuxe') . '</button>';
        echo '</form></div>';
    }
}
