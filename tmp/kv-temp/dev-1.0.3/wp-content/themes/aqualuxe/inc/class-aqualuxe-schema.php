<?php

/**
 * AquaLuxe Schema Markup
 *
 * @package AquaLuxe
 */

if (! defined('ABSPATH')) exit;

class AquaLuxe_Schema
{

    public function __construct()
    {
        add_action('wp_head', [$this, 'output_schema']);
    }

    public function output_schema()
    {
        if (is_singular('product') && function_exists('wc_get_product')) {
            global $post;
            $product = wc_get_product($post->ID);
            $schema = [
                '@context' => 'https://schema.org/',
                '@type'    => 'Product',
                'name'     => get_the_title(),
                'image'    => wp_get_attachment_url($product->get_image_id()),
                'description' => wp_strip_all_tags(get_the_excerpt()),
                'sku'      => $product->get_sku(),
                'offers'   => [
                    '@type'         => 'Offer',
                    'priceCurrency' => get_woocommerce_currency(),
                    'price'         => $product->get_price(),
                    'availability'  => 'https://schema.org/' . ($product->is_in_stock() ? 'InStock' : 'OutOfStock')
                ]
            ];
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
        }
    }
}
