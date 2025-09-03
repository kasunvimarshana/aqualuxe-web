<?php
namespace Aqualuxe\Importer;

class Importer
{
    public function import(array $args = []): array
    {
        $log = [];
        // Pages
        $pages = [
            'Home' => ['content' => '<!-- wp:paragraph --><p>Welcome to AquaLuxe.</p><!-- /wp:paragraph -->'],
            'About' => ['content' => '<!-- wp:paragraph --><p>About AquaLuxe.</p><!-- /wp:paragraph -->'],
            'Services' => ['content' => '<!-- wp:list --><ul><li>Design</li><li>Maintenance</li></ul><!-- /wp:list -->'],
            'Blog' => ['content' => ''],
            'Contact' => ['content' => '<!-- wp:paragraph --><p>Contact us.</p><!-- /wp:paragraph -->'],
            'FAQ' => ['content' => ''],
            'Privacy Policy' => ['content' => ''],
            'Terms & Conditions' => ['content' => ''],
            'Shipping & Returns' => ['content' => ''],
            'Cookie Policy' => ['content' => ''],
        ];
        $page_ids = [];
        foreach ($pages as $title => $data) {
            $existing = get_page_by_title($title);
            if ($existing) {
                $page_ids[$title] = $existing->ID;
                $log[] = "Skipped existing page: $title";
                continue;
            }
            $id = wp_insert_post([
                'post_title' => $title,
                'post_type' => 'page',
                'post_status' => 'publish',
                'post_content' => $data['content'],
            ], true);
            if (is_wp_error($id)) {
                $log[] = 'Error creating page ' . $title . ': ' . $id->get_error_message();
            } else {
                $page_ids[$title] = $id;
                $log[] = 'Created page: ' . $title;
            }
        }

        // Set Home as front page
        if (isset($page_ids['Home'])) {
            update_option('page_on_front', $page_ids['Home']);
            update_option('show_on_front', 'page');
        }
        if (isset($page_ids['Blog'])) {
            update_option('page_for_posts', $page_ids['Blog']);
        }

        // Menus
        $menu = wp_get_nav_menu_object('Primary');
        if (!$menu) {
            $menu_id = wp_create_nav_menu('Primary');
            foreach (['Home','Shop','Services','About','Blog','Contact'] as $item) {
                if (!empty($page_ids[$item])) {
                    wp_update_nav_menu_item($menu_id, 0, [
                        'menu-item-title' => $item,
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $page_ids[$item],
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish',
                    ]);
                }
            }
            $locations = get_theme_mod('nav_menu_locations');
            if (!is_array($locations)) { $locations = []; }
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
            $log[] = 'Created primary menu.';
        } else {
            $log[] = 'Primary menu exists.';
        }

        // CPT: Listing example
        $listing_id = wp_insert_post([
            'post_title' => 'Sample Listing',
            'post_type' => 'listing',
            'post_status' => 'publish',
            'post_content' => 'An example classified listing.',
        ], true);
        if (!is_wp_error($listing_id)) {
            $log[] = 'Created sample listing.';
        }

        // WooCommerce sample (if active)
        if (class_exists('WooCommerce')) {
            $cat = wp_insert_term('Rare Fish', 'product_cat');
            $product_id = wp_insert_post([
                'post_title' => 'Blue Diamond Discus',
                'post_type' => 'product',
                'post_status' => 'publish',
                'post_content' => 'Premium discus fish.',
            ], true);
            if (!is_wp_error($product_id)) {
                wp_set_object_terms($product_id, 'simple', 'product_type');
                if (!is_wp_error($cat) && isset($cat['term_id'])) {
                    wp_set_object_terms($product_id, [(int) $cat['term_id']], 'product_cat');
                }
                update_post_meta($product_id, '_regular_price', '199.00');
                update_post_meta($product_id, '_price', '199.00');
                $log[] = 'Created WooCommerce product.';
            }
        }

        return $log;
    }

    public function flush(): array
    {
        $log = [];
        // Remove created pages by title
        foreach (['Home','About','Services','Blog','Contact','FAQ','Privacy Policy','Terms & Conditions','Shipping & Returns','Cookie Policy'] as $title) {
            $page = get_page_by_title($title);
            if ($page) {
                wp_delete_post($page->ID, true);
                $log[] = 'Deleted page: ' . $title;
            }
        }
        // Remove sample listing
        $posts = get_posts(['post_type' => 'listing', 'title' => 'Sample Listing', 'numberposts' => -1]);
        foreach ($posts as $p) {
            wp_delete_post($p->ID, true);
            $log[] = 'Deleted listing: ' . $p->ID;
        }
        // Remove WC sample
        if (class_exists('WooCommerce')) {
            $products = get_posts(['post_type' => 'product', 's' => 'Blue Diamond Discus', 'numberposts' => -1]);
            foreach ($products as $p) {
                wp_delete_post($p->ID, true);
                $log[] = 'Deleted product: ' . $p->ID;
            }
        }
        return $log;
    }
}
