<?php
namespace Aqualuxe\Providers;

use Aqualuxe\Support\Container;

class Woo_UI_Service_Provider
{
    public function register(Container $c): void
    {
        if (!\class_exists('WooCommerce')) { return; }

        \add_filter('woocommerce_checkout_fields', [$this, 'style_checkout_fields']);
        \add_filter('woocommerce_default_address_fields', [$this, 'style_address_fields']);
        \add_filter('woocommerce_order_button_html', [$this, 'order_button_html']);
        \add_filter('woocommerce_loop_add_to_cart_link', [$this, 'loop_add_to_cart_link'], 10, 3);
    }

    public function boot(Container $c): void {}

    public function style_checkout_fields(array $fields): array
    {
        foreach ($fields as $section => &$group) {
            foreach ($group as $key => &$field) {
                $classes = $field['input_class'] ?? [];
                if (!\in_array('aqlx-input', $classes, true)) {
                    $classes[] = 'aqlx-input';
                }
                $field['input_class'] = $classes;
            }
        }
        return $fields;
    }

    public function style_address_fields(array $fields): array
    {
        foreach ($fields as $key => &$field) {
            $classes = $field['input_class'] ?? [];
            if (!\in_array('aqlx-input', $classes, true)) {
                $classes[] = 'aqlx-input';
            }
            $field['input_class'] = $classes;
        }
        return $fields;
    }

    public function order_button_html(string $html): string
    {
        // Ensure consistent button classes
        if (\strpos($html, 'aqlx-btn') === false) {
            $html = \preg_replace('/class="([^"]*)"/i', 'class="$1 aqlx-btn aqlx-btn--primary"', $html, 1);
        }
        return $html;
    }

    public function loop_add_to_cart_link(string $link, $product, array $args): string
    {
        if (\strpos($link, 'aqlx-btn') === false) {
            $link = \preg_replace('/class="([^"]*)"/i', 'class="$1 aqlx-btn aqlx-btn--outline"', $link, 1);
        }
        return $link;
    }
}
