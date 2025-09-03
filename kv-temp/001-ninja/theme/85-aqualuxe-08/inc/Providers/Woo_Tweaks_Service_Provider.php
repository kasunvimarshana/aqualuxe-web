<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Woo_Tweaks_Service_Provider
{
    public function register(Container $c): void
    {
        if (!\class_exists('WooCommerce')) { return; }
        \add_action('woocommerce_after_cart_totals', [$this, 'continue_shopping_button']);
        \add_action('woocommerce_cart_is_empty', [$this, 'back_to_shop_button']);
    }

    public function boot(Container $c): void {}

    private function shop_url(): string
    {
        $shop_id = \function_exists('wc_get_page_id') ? (int) \wc_get_page_id('shop') : 0;
        $url = $shop_id > 0 ? \get_permalink($shop_id) : \home_url('/');
        return $url ?: '/';
    }

    public function continue_shopping_button(): void
    {
        $url = $this->shop_url();
        echo '<p class="aqlx-cart-actions"><a class="aqlx-btn aqlx-btn--ghost" href="' . \esc_url($url) . '">' . \esc_html__('Continue shopping', 'aqualuxe') . '</a></p>';
    }

    public function back_to_shop_button(): void
    {
        $url = $this->shop_url();
        echo '<p class="aqlx-cart-empty-action"><a class="aqlx-btn aqlx-btn--primary" href="' . \esc_url($url) . '">' . \esc_html__('Back to shop', 'aqualuxe') . '</a></p>';
    }
}
