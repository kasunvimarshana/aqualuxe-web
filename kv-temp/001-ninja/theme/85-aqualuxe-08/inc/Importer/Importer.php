<?php
namespace Aqualuxe\Importer;

class Importer
{
    public function import(array $args = []): array
    {
        $log = [];
        // Guarded WP call helper for static analyzers
        $fn = function (string $name, ...$args) {
            return \function_exists($name) ? \call_user_func_array($name, $args) : null;
        };
        // Pages (from business outline)
        $pages = [
            'Home' => ['content' => '<!-- wp:pattern {"slug":"aqualuxe/hero"} /--><!-- wp:spacer {"height":"24px"} --><div style="height:24px" aria-hidden="true" class="wp-block-spacer"></div><!-- /wp:spacer --><!-- wp:pattern {"slug":"aqualuxe/cta-links"} /-->'],
            'Shop' => ['content' => '<!-- wp:paragraph --><p>Explore our premium livestock, plants, and equipment.</p><!-- /wp:paragraph -->'],
            'Wholesale & B2B' => ['content' => '<!-- wp:pattern {"slug":"aqualuxe/cta-links"} /--><!-- wp:heading --><h2>Wholesale & B2B</h2><!-- /wp:heading --><!-- wp:paragraph --><p>Bulk pricing, logistics, and dedicated support.</p><!-- /wp:paragraph --><!-- wp:shortcode -->[aqualuxe_wholesale_form]<!-- /wp:shortcode -->'],
            'Buy, Sell & Trade' => ['content' => '<!-- wp:heading --><h2>Buy, Sell & Trade</h2><!-- /wp:heading --><!-- wp:list --><ul><li>Trade-in program</li><li>Auctions</li><li>Marketplace guidelines</li></ul><!-- /wp:list -->'],
            'Services' => ['content' => '<!-- wp:pattern {"slug":"aqualuxe/cta-links"} /--><!-- wp:list --><ul><li>Design & Installation</li><li>Maintenance</li><li>Quarantine</li><li>Consultancy</li></ul><!-- /wp:list -->'],
            'Export' => ['content' => '<!-- wp:pattern {"slug":"aqualuxe/cta-links"} /--><!-- wp:paragraph --><p>Global exports with certifications and compliance.</p><!-- /wp:paragraph --><!-- wp:shortcode -->[aqualuxe_export_quote]<!-- /wp:shortcode -->'],
            'Events & Experiences' => ['content' => '<!-- wp:paragraph --><p>Expos, competitions, tours, and experience center bookings.</p><!-- /wp:paragraph -->'],
            'Learning Hub' => ['content' => '<!-- wp:pattern {"slug":"aqualuxe/feature-grid"} /--><!-- wp:paragraph --><p>Fishkeeping guides, aquascaping tips, regulations.</p><!-- /wp:paragraph -->'],
            'About' => ['content' => '<!-- wp:pattern {"slug":"aqualuxe/feature-grid"} /--><!-- wp:paragraph --><p>About AquaLuxe.</p><!-- /wp:paragraph -->'],
            'Blog' => ['content' => ''],
            'Contact' => ['content' => '<!-- wp:pattern {"slug":"aqualuxe/cta-links"} /--><!-- wp:shortcode -->[aqualuxe_contact_form]<!-- /wp:shortcode -->'],
            'Wishlist' => ['content' => '<!-- wp:shortcode -->[aqualuxe_wishlist]<!-- /wp:shortcode -->'],
            'FAQ' => ['content' => ''],
            'Privacy Policy' => ['content' => ''],
            'Terms & Conditions' => ['content' => ''],
            'Shipping & Returns' => ['content' => ''],
            'Cookie Policy' => ['content' => ''],
        ];
        $page_ids = [];
        foreach ($pages as $title => $data) {
            $existing = $fn('get_page_by_title', $title);
            if ($existing) {
                $page_ids[$title] = $existing->ID;
                // If this is demo content, update it with latest content definition
                if ((int) $fn('get_post_meta', $existing->ID, '_aqlx_demo', true) === 1) {
                    $update = [
                        'ID' => $existing->ID,
                        'post_content' => $data['content'],
                    ];
                    $res = $fn('wp_update_post', $update, true);
                    if (!$fn('is_wp_error', $res)) {
                        $log[] = "Updated content for existing demo page: $title";
                    }
                } else {
                    $log[] = "Skipped existing page: $title";
                }
                continue;
            }
            $id = $fn('wp_insert_post', [
                'post_title' => $title,
                'post_type' => 'page',
                'post_status' => 'publish',
                'post_content' => $data['content'],
            ], true);
            if ($fn('is_wp_error', $id)) {
                $log[] = 'Error creating page ' . $title . ': ' . $id->get_error_message();
            } else {
                $page_ids[$title] = $id;
                $fn('add_post_meta', $id, '_aqlx_demo', 1, true);
                $log[] = 'Created page: ' . $title;
            }
        }

        // Set Home as front page
        if (isset($page_ids['Home'])) {
            $fn('update_option', 'page_on_front', $page_ids['Home']);
            $fn('update_option', 'show_on_front', 'page');
        }
        if (isset($page_ids['Blog'])) {
            $fn('update_option', 'page_for_posts', $page_ids['Blog']);
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
                $existing = $fn('get_page_by_title', $title);
                if ($existing) {
                    $pid = $existing->ID;
                } else {
                    $pid = $fn('wp_insert_post', [
                        'post_title' => $title,
                        'post_type' => 'page',
                        'post_status' => 'publish',
                        'post_content' => '',
                    ], true);
                    if (!$fn('is_wp_error', $pid)) { $fn('add_post_meta', $pid, '_aqlx_demo', 1, true); $log[] = "Created page: $title"; }
                }
                if (!$fn('is_wp_error', $pid) && $pid) {
                    $fn('update_option', $meta['option'], $pid);
                    if ($title === 'Shop') { $shop_page_id = $pid; $page_ids['Shop'] = $pid; }
                }
            }

            // Product categories (hierarchy)
            $terms = [];
            $ensure_term = function ($name, $parent = 0) use (&$terms, &$log, $fn) {
                if ($fn('term_exists', $name, 'product_cat')) {
                    $t = $fn('term_exists', $name, 'product_cat');
                    $tid = is_array($t) ? (int) $t['term_id'] : (int) $t;
                    $terms[$name] = $tid;
                    return $tid;
                }
                $res = $fn('wp_insert_term', $name, 'product_cat', ['parent' => $parent]);
                if (!$fn('is_wp_error', $res)) {
                    $tid = (int) $res['term_id'];
                    $terms[$name] = $tid;
                    $fn('add_term_meta', $tid, '_aqlx_demo', 1, true);
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
        $menu = $fn('wp_get_nav_menu_object', 'Primary');
        if (!$menu) {
            $menu_id = $fn('wp_create_nav_menu', 'Primary');
            $fn('add_term_meta', $menu_id, '_aqlx_demo', 1, true);

            $add_page_item = function (string $title, int $parent = 0) use ($menu_id, $page_ids, $fn) {
                if (empty($page_ids[$title])) { return 0; }
                $item_id = $fn('wp_update_nav_menu_item', $menu_id, 0, [
                    'menu-item-title' => $title,
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $page_ids[$title],
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish',
                    'menu-item-parent-id' => $parent,
                ]);
                return (int) $item_id;
            };

            $add_term_item = function (string $name, int $parent = 0) use ($menu_id, $fn) {
                $t = $fn('term_exists', $name, 'product_cat');
                $tid = $t ? (is_array($t) ? (int) $t['term_id'] : (int) $t) : 0;
                if (!$tid) { return 0; }
                $item_id = $fn('wp_update_nav_menu_item', $menu_id, 0, [
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
            $wishlist = $add_page_item('Wishlist');

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
            $locations = $fn('get_theme_mod', 'nav_menu_locations');
            if (!is_array($locations)) { $locations = []; }
            $locations['primary'] = $menu_id;
            $fn('set_theme_mod', 'nav_menu_locations', $locations);
            $log[] = 'Created primary menu.';
        } else {
            $log[] = 'Primary menu exists.';
        }

        // CPT: Listing example
    $listing_id = $fn('wp_insert_post', [
            'post_title' => 'Sample Listing',
            'post_type' => 'listing',
            'post_status' => 'publish',
            'post_content' => 'An example classified listing.',
        ], true);
    if (!$fn('is_wp_error', $listing_id)) {
            $log[] = 'Created sample listing.';
        }

    // WooCommerce sample (if active)
        if (\class_exists('WooCommerce')) {
            $cat = $fn('wp_insert_term', 'Rare Fish', 'product_cat');
            $product_id = $fn('wp_insert_post', [
                'post_title' => 'Blue Diamond Discus',
                'post_type' => 'product',
                'post_status' => 'publish',
                'post_content' => 'Premium discus fish.',
            ], true);
            if (!$fn('is_wp_error', $product_id)) {
                $fn('wp_set_object_terms', $product_id, 'simple', 'product_type');
                if (!$fn('is_wp_error', $cat) && isset($cat['term_id'])) {
                    $fn('wp_set_object_terms', $product_id, [(int) $cat['term_id']], 'product_cat');
                }
                $fn('update_post_meta', $product_id, '_regular_price', '199.00');
                $fn('update_post_meta', $product_id, '_price', '199.00');
        $fn('add_post_meta', $product_id, '_aqlx_demo', 1, true);
                $log[] = 'Created WooCommerce product.';
            }
        }

        return $log;
    }

    public function flush(): array
    {
        $log = [];
        $fn = function (string $name, ...$args) {
            return \function_exists($name) ? \call_user_func_array($name, $args) : null;
        };
        // Remove all posts created by importer
        $created = $fn('get_posts', [
            'post_type' => 'any',
            'post_status' => 'any',
            'meta_key' => '_aqlx_demo',
            'meta_value' => 1,
            'numberposts' => -1,
        ]);
        foreach ($created as $p) {
            $fn('wp_delete_post', $p->ID, true);
            $log[] = 'Deleted post: ' . $p->ID;
        }

        // Remove demo product categories
    if ($fn('taxonomy_exists', 'product_cat')) {
        $terms = $fn('get_terms', [
                'taxonomy' => 'product_cat',
                'hide_empty' => false,
                'meta_query' => [ [ 'key' => '_aqlx_demo', 'compare' => 'EXISTS' ] ],
            ]);
        if (!$fn('is_wp_error', $terms)) {
                foreach ($terms as $term) {
            $fn('wp_delete_term', $term->term_id, 'product_cat');
                    $log[] = 'Deleted term: ' . $term->name;
                }
            }
        }

        // Remove demo menu and clear primary location
        $menus = $fn('get_terms', [
            'taxonomy' => 'nav_menu',
            'hide_empty' => false,
            'meta_query' => [ [ 'key' => '_aqlx_demo', 'compare' => 'EXISTS' ] ],
        ]);
        if (!$fn('is_wp_error', $menus)) {
            foreach ($menus as $m) {
                $locations = $fn('get_theme_mod', 'nav_menu_locations');
                if (is_array($locations)) {
                    foreach ($locations as $loc => $mid) {
                        if ((int) $mid === (int) $m->term_id) { unset($locations[$loc]); }
                    }
                    $fn('set_theme_mod', 'nav_menu_locations', $locations);
                }
                if (\function_exists('wp_delete_nav_menu')) {
                    $fn('wp_delete_nav_menu', $m);
                } else {
                    $fn('wp_delete_term', $m->term_id, 'nav_menu');
                }
                $log[] = 'Deleted menu: ' . $m->name;
            }
        }

        // Unset Woo page options if they reference deleted demo pages
    if (\class_exists('WooCommerce')) {
            foreach (['woocommerce_shop_page_id','woocommerce_cart_page_id','woocommerce_checkout_page_id','woocommerce_myaccount_page_id'] as $opt) {
        $pid = (int) $fn('get_option', $opt);
        if ($pid && $fn('get_post_meta', $pid, '_aqlx_demo', true)) {
            $fn('update_option', $opt, 0);
                }
            }
        }
        return $log;
    }
}
