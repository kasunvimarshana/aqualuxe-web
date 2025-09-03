<?php
namespace Aqualuxe\Importer;

class Importer
{
    public function import(array $args = []): array
    {
        $log = [];
        // Pages (from business outline)
        $pages = [
            'Home' => ['content' => '<!-- wp:cover {"dimRatio":40,"isDark":false} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1} --><h1 class="has-text-align-center">Bringing elegance to aquatic life – globally.</h1><!-- /wp:heading --><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} --><div class="wp-block-buttons"><!-- wp:button {"className":"is-style-fill"} --><div class="wp-block-button is-style-fill"><a class="wp-block-button__link" href="/shop">Shop Now</a></div><!-- /wp:button --><!-- wp:button {"className":"is-style-outline"} --><div class="wp-block-button is-style-outline"><a class="wp-block-button__link" href="/export">Request a Quote</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div></div><!-- /wp:cover --><!-- wp:columns --><div class="wp-block-columns"><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Wholesale & B2B</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Bulk pricing and logistics support.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link" href="/wholesale-b2b">Apply</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Export</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Global export with certifications.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link" href="/export">Get Quote</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --><!-- wp:column --><div class="wp-block-column"><!-- wp:heading {"level":3} --><h3>Services</h3><!-- /wp:heading --><!-- wp:paragraph --><p>Design, installation, maintenance.</p><!-- /wp:paragraph --><!-- wp:buttons --><div class="wp-block-buttons"><!-- wp:button --><div class="wp-block-button"><a class="wp-block-button__link" href="/services">Book</a></div><!-- /wp:button --></div><!-- /wp:buttons --></div><!-- /wp:column --></div><!-- /wp:columns -->'],
            'Shop' => ['content' => '<!-- wp:paragraph --><p>Explore our premium livestock, plants, and equipment.</p><!-- /wp:paragraph -->'],
            'Wholesale & B2B' => ['content' => '<!-- wp:heading --><h2>Wholesale & B2B</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Bulk pricing, logistics, and dedicated support.</p><!-- /wp:paragraph --><!-- wp:shortcode -->[aqualuxe_wholesale_form]<!-- /wp:shortcode -->'],
            'Buy, Sell & Trade' => ['content' => '<!-- wp:heading --><h2>Buy, Sell & Trade</h2><!-- /wp:heading --><!-- wp:list --><ul><li>Trade-in program</li><li>Auctions</li><li>Marketplace guidelines</li></ul><!-- /wp:list -->'],
            'Services' => ['content' => '<!-- wp:list --><ul><li>Design & Installation</li><li>Maintenance</li><li>Quarantine</li><li>Consultancy</li></ul><!-- /wp:list -->'],
            'Export' => ['content' => '<!-- wp:paragraph --><p>Global exports with certifications and compliance.</p><!-- /wp:paragraph --><!-- wp:shortcode -->[aqualuxe_export_quote]<!-- /wp:shortcode -->'],
            'Events & Experiences' => ['content' => '<!-- wp:paragraph --><p>Expos, competitions, tours, and experience center bookings.</p><!-- /wp:paragraph -->'],
            'Learning Hub' => ['content' => '<!-- wp:paragraph --><p>Fishkeeping guides, aquascaping tips, regulations.</p><!-- /wp:paragraph -->'],
            'About' => ['content' => '<!-- wp:paragraph --><p>About AquaLuxe.</p><!-- /wp:paragraph -->'],
            'Blog' => ['content' => ''],
            'Contact' => ['content' => '<!-- wp:shortcode -->[aqualuxe_contact_form]<!-- /wp:shortcode -->'],
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
                add_post_meta($id, '_aqlx_demo', 1, true);
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

        // WooCommerce pages and categories
        $shop_page_id = null;
        if (class_exists('WooCommerce')) {
            // Ensure core Woo pages exist
            $woo_pages = [
                'Shop' => ['option' => 'woocommerce_shop_page_id'],
                'Cart' => ['option' => 'woocommerce_cart_page_id'],
                'Checkout' => ['option' => 'woocommerce_checkout_page_id'],
                'My Account' => ['option' => 'woocommerce_myaccount_page_id'],
            ];
            foreach ($woo_pages as $title => $meta) {
                $existing = get_page_by_title($title);
                if ($existing) {
                    $pid = $existing->ID;
                } else {
                    $pid = wp_insert_post([
                        'post_title' => $title,
                        'post_type' => 'page',
                        'post_status' => 'publish',
                        'post_content' => '',
                    ], true);
                    if (!is_wp_error($pid)) { add_post_meta($pid, '_aqlx_demo', 1, true); $log[] = "Created page: $title"; }
                }
                if (!is_wp_error($pid) && $pid) {
                    update_option($meta['option'], $pid);
                    if ($title === 'Shop') { $shop_page_id = $pid; $page_ids['Shop'] = $pid; }
                }
            }

            // Product categories (hierarchy)
            $terms = [];
            $ensure_term = function ($name, $parent = 0) use (&$terms, &$log) {
                if (term_exists($name, 'product_cat')) {
                    $t = term_exists($name, 'product_cat');
                    $tid = is_array($t) ? (int) $t['term_id'] : (int) $t;
                    $terms[$name] = $tid;
                    return $tid;
                }
                $res = wp_insert_term($name, 'product_cat', ['parent' => $parent]);
                if (!is_wp_error($res)) {
                    $tid = (int) $res['term_id'];
                    $terms[$name] = $tid;
                    add_term_meta($tid, '_aqlx_demo', 1, true);
                    $log[] = 'Created category: ' . $name;
                    return $tid;
                }
                return 0;
            };
            $fish = $ensure_term('Fish');
            $ensure_term('Freshwater Fish', $fish);
            $ensure_term('Marine Fish', $fish);
            $ensure_term('Exotic & Rare', $fish);
            $ensure_term('Invertebrates');
            $ensure_term('Aquatic Plants');
            $ensure_term('Aquariums & Custom Tanks');
            $ensure_term('Equipment');
            $ensure_term('Feed & Supplements');
            $ensure_term('Decor & Accessories');
        }

        // Menus: build hierarchical primary menu
        $menu = wp_get_nav_menu_object('Primary');
        if (!$menu) {
            $menu_id = wp_create_nav_menu('Primary');
            add_term_meta($menu_id, '_aqlx_demo', 1, true);

            $add_page_item = function (string $title, int $parent = 0) use ($menu_id, $page_ids) {
                if (empty($page_ids[$title])) { return 0; }
                $item_id = wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title' => $title,
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page_ids[$title],
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish',
                    'menu-item-parent-id' => $parent,
                ]);
                return (int) $item_id;
            };

            $add_term_item = function (string $name, int $parent = 0) use ($menu_id) {
                $t = term_exists($name, 'product_cat');
                $tid = $t ? (is_array($t) ? (int) $t['term_id'] : (int) $t) : 0;
                if (!$tid) { return 0; }
                $item_id = wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title' => $name,
                    'menu-item-object' => 'product_cat',
                    'menu-item-object-id' => $tid,
                    'menu-item-type' => 'taxonomy',
                    'menu-item-status' => 'publish',
                    'menu-item-parent-id' => $parent,
                ]);
                return (int) $item_id;
            };

            // Top-level
            $home = $add_page_item('Home');
            $shop = $add_page_item('Shop');
            $wholesale = $add_page_item('Wholesale & B2B');
            $trade = $add_page_item('Buy, Sell & Trade');
            $services = $add_page_item('Services');
            $export = $add_page_item('Export');
            $events = $add_page_item('Events & Experiences');
            $learning = $add_page_item('Learning Hub');
            $about = $add_page_item('About');
            $blog = $add_page_item('Blog');
            $contact = $add_page_item('Contact');

            // Shop submenu (if Woo categories exist)
            if ($shop) {
                $fish = $add_term_item('Fish', $shop);
                if ($fish) {
                    $add_term_item('Freshwater Fish', $fish);
                    $add_term_item('Marine Fish', $fish);
                    $add_term_item('Exotic & Rare', $fish);
                }
                $add_term_item('Invertebrates', $shop);
                $add_term_item('Aquatic Plants', $shop);
                $add_term_item('Aquariums & Custom Tanks', $shop);
                $add_term_item('Equipment', $shop);
                $add_term_item('Feed & Supplements', $shop);
                $add_term_item('Decor & Accessories', $shop);
            }

            // Assign to primary location
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
        add_post_meta($product_id, '_aqlx_demo', 1, true);
                $log[] = 'Created WooCommerce product.';
            }
        }

        return $log;
    }

    public function flush(): array
    {
        $log = [];
        // Remove all posts created by importer
        $created = get_posts([
            'post_type' => 'any',
            'post_status' => 'any',
            'meta_key' => '_aqlx_demo',
            'meta_value' => 1,
            'numberposts' => -1,
        ]);
        foreach ($created as $p) {
            wp_delete_post($p->ID, true);
            $log[] = 'Deleted post: ' . $p->ID;
        }

        // Remove demo product categories
        if (taxonomy_exists('product_cat')) {
            $terms = get_terms([
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'meta_query' => [ [ 'key' => '_aqlx_demo', 'compare' => 'EXISTS' ] ],
            ]);
            if (!is_wp_error($terms)) {
                foreach ($terms as $term) {
                    wp_delete_term($term->term_id, 'product_cat');
                    $log[] = 'Deleted term: ' . $term->name;
                }
            }
        }

        // Remove demo menu and clear primary location
        $menus = get_terms([
            'taxonomy' => 'nav_menu',
            'hide_empty' => false,
            'meta_query' => [ [ 'key' => '_aqlx_demo', 'compare' => 'EXISTS' ] ],
        ]);
        if (!is_wp_error($menus)) {
            foreach ($menus as $m) {
                $locations = get_theme_mod('nav_menu_locations');
                if (is_array($locations)) {
                    foreach ($locations as $loc => $mid) {
                        if ((int) $mid === (int) $m->term_id) { unset($locations[$loc]); }
                    }
                    set_theme_mod('nav_menu_locations', $locations);
                }
                if (function_exists('wp_delete_nav_menu')) {
                    wp_delete_nav_menu($m);
                } else {
                    wp_delete_term($m->term_id, 'nav_menu');
                }
                $log[] = 'Deleted menu: ' . $m->name;
            }
        }

        // Unset Woo page options if they reference deleted demo pages
        if (class_exists('WooCommerce')) {
            foreach (['woocommerce_shop_page_id','woocommerce_cart_page_id','woocommerce_checkout_page_id','woocommerce_myaccount_page_id'] as $opt) {
                $pid = (int) get_option($opt);
                if ($pid && get_post_meta($pid, '_aqlx_demo', true)) {
                    update_option($opt, 0);
                }
            }
        }
        return $log;
    }
}
