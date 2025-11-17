<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Woo_Layout_Service_Provider
{
    public function register(Container $c): void
    {
        if (!\class_exists('WooCommerce')) { return; }
    \add_action('woocommerce_before_main_content', [$this, 'open_wrapper'], 1);
    \add_action('woocommerce_before_main_content', [$this, 'print_breadcrumbs'], 15);
    \add_action('woocommerce_after_main_content', [$this, 'close_wrapper'], 100);
    \add_filter('body_class', [$this, 'body_classes']);
    \add_filter('woocommerce_breadcrumb_defaults', [$this, 'breadcrumb_defaults']);
    }

    public function boot(Container $c): void {}

    public function open_wrapper(): void
    {
    $ctx = $this->get_context();
        echo '<div class="aqlx-woo aqlx-woo--' . \esc_attr($ctx) . '">';
    }

    public function close_wrapper(): void
    {
        echo '</div>';
    }

    public function print_breadcrumbs(): void
    {
        // Show breadcrumbs on archive/product/taxonomy views only
        if ((\function_exists('is_product') && \is_product()) || (\function_exists('is_product_taxonomy') && \is_product_taxonomy()) || (\function_exists('is_shop') && \is_shop())) {
            if (\function_exists('woocommerce_breadcrumb')) { \woocommerce_breadcrumb(); }
        }
    }

    /**
     * Add contextual Woo classes to the body for styling hooks.
     */
    public function body_classes(array $classes): array
    {
        if ($this->is_woo_context()) {
            $classes[] = 'aqlx-woo';
            $classes[] = 'aqlx-woo--' . $this->get_context();
        }
        return $classes;
    }

    private function get_context(): string
    {
        $ctx = 'shop';
        if (\function_exists('is_cart') && \is_cart()) { return 'cart'; }
        if (\function_exists('is_checkout') && \is_checkout()) { return 'checkout'; }
        if (\function_exists('is_account_page') && \is_account_page()) { return 'account'; }
        if (\function_exists('is_product') && \is_product()) { return 'product'; }
        if (\function_exists('is_product_taxonomy') && \is_product_taxonomy()) { return 'taxonomy'; }
        return $ctx;
    }

    private function is_woo_context(): bool
    {
        if (\function_exists('is_woocommerce') && \is_woocommerce()) { return true; }
        if (\function_exists('is_cart') && \is_cart()) { return true; }
        if (\function_exists('is_checkout') && \is_checkout()) { return true; }
        if (\function_exists('is_account_page') && \is_account_page()) { return true; }
        if (\function_exists('is_product') && \is_product()) { return true; }
        if (\function_exists('is_product_taxonomy') && \is_product_taxonomy()) { return true; }
        return false;
    }

    public function breadcrumb_defaults(array $defaults): array
    {
        $defaults['delimiter'] = '<span class="sep" aria-hidden="true">›</span>';
        $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="Breadcrumbs">';
        $defaults['wrap_after'] = '</nav>';
        return $defaults;
    }
}
