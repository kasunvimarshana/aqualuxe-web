<?php

namespace App\Modules\DemoImporter\Importers;

use App\Modules\DemoImporter\Util\Logger;
use App\Modules\DemoImporter\Util\WpImporterUtil;
use WC_Product_Simple;
use WC_Product_Variable;
use WC_Product_Variation;
use WC_Product_Attribute;

/**
 * Class ProductImporter
 *
 * Imports WooCommerce products.
 *
 * @package App\Modules\DemoImporter\Importers
 */
class ProductImporter implements ImporterInterface
{
    public function import(array $data = [])
    {
        if (!class_exists('WooCommerce')) {
            Logger::log('WooCommerce is not active. Skipping product import.');
            return;
        }

        Logger::log('---');
        Logger::log('Starting product import...');

        $file_path = AQUALUXE_DIR . "/app/modules/demo-importer/data/products.json";
        if (!file_exists($file_path)) {
            Logger::log("Data file not found for 'products', skipping.");
            return;
        }

        $products = json_decode(file_get_contents($file_path), true);
        if (empty($products)) {
            Logger::log("No products found, skipping.");
            return;
        }

        Logger::log("Importing " . count($products) . " products.");

        foreach ($products as $product_data) {
            $this->import_product($product_data);
        }

        Logger::log('Product import finished.');
        Logger::log('---');
    }

    private function import_product(array $product_data)
    {
        $product_title = $product_data['post_title'] ?? 'Untitled Product';

        if (WpImporterUtil::post_exists($product_title)) {
            Logger::log("Product '{$product_title}' already exists. Skipping.");
            return;
        }

        $is_variable = isset($product_data['variations']) && !empty($product_data['variations']);
        $product = $is_variable ? new WC_Product_Variable() : new WC_Product_Simple();

        $product->set_name($product_title);
        $product->set_status('publish');
        $product->set_description($product_data['post_content'] ?? '');
        $product->set_short_description($product_data['post_excerpt'] ?? '');
        $product->set_sku($product_data['meta_input']['_sku'] ?? '');

        if (!$is_variable) {
            $product->set_regular_price($product_data['meta_input']['_regular_price'] ?? '');
            if (isset($product_data['meta_input']['_sale_price'])) {
                $product->set_sale_price($product_data['meta_input']['_sale_price'] ?? '');
            }
            $product->set_stock_status($product_data['meta_input']['_stock_status'] ?? 'instock');
            if (isset($product_data['meta_input']['_manage_stock']) && $product_data['meta_input']['_manage_stock'] === 'yes') {
                $product->set_manage_stock(true);
                $product->set_stock_quantity($product_data['meta_input']['_stock'] ?? 0);
            }
        }

        if (isset($product_data['meta_input']['_virtual'])) {
            $product->set_virtual($product_data['meta_input']['_virtual'] === 'yes');
        }

        // Attributes
        if (isset($product_data['attributes']) && !empty($product_data['attributes'])) {
            $attributes = [];
            foreach ($product_data['attributes'] as $attr_data) {
                $attribute = new WC_Product_Attribute();
                $attribute->set_name($attr_data['name']);
                $attribute->set_options($attr_data['options']);
                $attribute->set_position($attr_data['position']);
                $attribute->set_visible($attr_data['visible']);
                $attribute->set_variation($attr_data['variation']);
                $attributes[] = $attribute;
            }
            $product->set_attributes($attributes);
        }

        $product_id = $product->save();
        Logger::log("Created product '{$product_title}' (ID: {$product_id}).");

        // Variations
        if ($is_variable && $product_id) {
            foreach ($product_data['variations'] as $var_data) {
                $variation = new WC_Product_Variation();
                $variation->set_parent_id($product_id);
                $variation->set_attributes($var_data['attributes']);
                $variation->set_regular_price($var_data['regular_price']);
                if (isset($var_data['sale_price'])) {
                    $variation->set_sale_price($var_data['sale_price']);
                }
                $variation->set_sku($var_data['sku']);
                $variation->set_manage_stock(true);
                $variation->set_stock_quantity($var_data['stock'] ?? 10);
                $variation->set_stock_status($var_data['stock_status'] ?? 'instock');
                $variation->save();
            }
            Logger::log("...and added " . count($product_data['variations']) . " variations.");
        }

        // Categories and Tags
        if ($product_id && isset($product_data['terms'])) {
            foreach ($product_data['terms'] as $taxonomy => $term_slugs) {
                wp_set_object_terms($product_id, $term_slugs, $taxonomy);
            }
            Logger::log("...and assigned terms.");
        }
    }

    public function get_name(): string
    {
        return 'products';
    }
}
