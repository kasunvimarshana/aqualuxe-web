<?php
namespace AquaLuxe\Modules\Currency;

class Module {
    protected static $default = 'USD';
    protected static $rates = [ 'USD'=>1, 'EUR'=>0.92, 'GBP'=>0.78 ];

    public static function init(): void {
        add_action('wp_footer', [__CLASS__, 'render_switcher']);
        add_filter('woocommerce_get_price_html', [__CLASS__, 'convert_price_html'], 20, 2);
        add_filter('woocommerce_product_get_price', [__CLASS__, 'convert_price'], 20, 2);
        add_filter('woocommerce_product_get_regular_price', [__CLASS__, 'convert_price'], 20, 2);
        add_filter('woocommerce_product_get_sale_price', [__CLASS__, 'convert_price'], 20, 2);
    }

    public static function current(): string {
        if (!empty($_COOKIE['aqlx_currency']) && isset(self::$rates[$_COOKIE['aqlx_currency']])) {
            return $_COOKIE['aqlx_currency'];
        }
        return self::$default;
    }

    public static function convert_price($price, $product) {
        if (!class_exists('WooCommerce')) return $price;
        $cur = self::current();
        $rate = self::$rates[$cur] ?? 1;
        return (float) $price * (float) $rate;
    }

    public static function convert_price_html($html, $product) {
        if (!class_exists('WooCommerce')) return $html;
        $cur = self::current();
        return str_replace(get_woocommerce_currency_symbol(), self::symbol($cur), $html);
    }

    public static function symbol($code): string {
        return [ 'USD' => '$', 'EUR' => '€', 'GBP' => '£' ][$code] ?? $code;
    }

    public static function render_switcher(): void {
        if (!class_exists('WooCommerce')) return;
        $cur = self::current();
        echo '<form class="fixed bottom-4 right-4 bg-white/90 dark:bg-slate-800/90 p-2 rounded shadow">';
        echo '<select name="currency" onchange="document.cookie=\'aqlx_currency=\'+this.value+\'; path=/\'; location.reload();">';
        echo '<option value="USD"' . ($cur === 'USD' ? ' selected' : '') . '>USD</option>';
        echo '<option value="EUR"' . ($cur === 'EUR' ? ' selected' : '') . '>EUR</option>';
        echo '<option value="GBP"' . ($cur === 'GBP' ? ' selected' : '') . '>GBP</option>';
        echo '</select></form>';
    }
}
