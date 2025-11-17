<?php
declare(strict_types=1);

namespace Aqualuxe\Modules\Currency;

// Lightweight, non-converting currency selector using a cookie.
// NOTE: This does not change store currency or perform conversion; it only adjusts the displayed symbol.

const COOKIE = 'aqlx_currency';

function currencies(): array {
    return [
        'USD' => ['symbol' => '$', 'label' => 'USD'],
        'EUR' => ['symbol' => '€', 'label' => 'EUR'],
        'GBP' => ['symbol' => '£', 'label' => 'GBP'],
        'JPY' => ['symbol' => '¥', 'label' => 'JPY'],
    ];
}

// Accept ?aqlx_currency=XXX and set cookie.
add_action('init', static function () {
    if (! empty($_GET['aqlx_currency'])) {
        $code = strtoupper(sanitize_text_field((string) $_GET['aqlx_currency']));
        $supported = array_keys(currencies());
        if (in_array($code, $supported, true)) {
            setcookie(COOKIE, $code, time() + 3600 * 24 * 60, COOKIEPATH ?: '/');
            $_COOKIE[COOKIE] = $code;
        }
    }
});

// Shortcode: render a selector; if a multi-currency plugin exists, defer to it.
add_shortcode('aqlx_currency_switcher', static function () {
    if (function_exists('wmc_dropdown_currencies')) {
        ob_start();
        echo do_shortcode('[woomulti_currency_switcher]');
        return ob_get_clean();
    }
    $currs = currencies();
    $current = strtoupper(sanitize_text_field((string) ($_COOKIE[COOKIE] ?? 'USD')));
    if (! isset($currs[$current])) { $current = 'USD'; }
    ob_start();
    ?>
        <form class="aqlx-currency inline-flex items-center gap-2" method="get">
      <label for="aqlx-currency-select" class="sr-only"><?php esc_html_e('Currency', 'aqualuxe'); ?></label>
            <select id="aqlx-currency-select" class="min-w-[90px] px-3 py-1 rounded border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary/50 focus:border-primary" name="aqlx_currency" onchange="this.form.submit()">
        <?php foreach ($currs as $code => $info): ?>
          <option value="<?php echo esc_attr($code); ?>" <?php selected($current, $code); ?>><?php echo esc_html($info['label']); ?></option>
        <?php endforeach; ?>
      </select>
    </form>
    <?php
    return (string) ob_get_clean();
});

// Adjust symbol based on cookie-selected currency (display only).
add_filter('woocommerce_currency_symbol', static function ($symbol, $currency) {
    $currs = currencies();
    $sel = strtoupper(sanitize_text_field((string) ($_COOKIE[COOKIE] ?? '')));
    if ($sel && isset($currs[$sel])) {
        return (string) $currs[$sel]['symbol'];
    }
    return $symbol;
}, 10, 2);

// Keep suffix untouched (placeholder hook retained for extensibility)
add_filter('woocommerce_get_price_suffix', static function ($suffix, $product) {
    return $suffix;
}, 10, 2);
