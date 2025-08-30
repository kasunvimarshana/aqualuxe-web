<?php
defined('ABSPATH') || exit;

// Admin UI: Demo Import + Full Reset
add_action('admin_menu', function () {
    add_theme_page(
        __('AquaLuxe Setup', 'aqualuxe'),
        __('Setup & Demo', 'aqualuxe'),
        'manage_options',
        'aqlx-demo',
        function () {
            if (!current_user_can('manage_options')) return;

            // Handle actions
            if (isset($_POST['aqlx_action']) && isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'aqlx_demo')) {
                $action = sanitize_text_field(wp_unslash($_POST['aqlx_action']));
                $scope = [
                    'content'     => !empty($_POST['scope_content']),
                    'media'       => !empty($_POST['scope_media']),
                    'taxonomies'  => !empty($_POST['scope_taxonomies']),
                    'menus'       => !empty($_POST['scope_menus']),
                    'widgets'     => !empty($_POST['scope_widgets']),
                    'theme_mods'  => !empty($_POST['scope_theme_mods']),
                    'woo_orders'  => !empty($_POST['scope_woo_orders']),
                ];
                $config = [
                    'volume' => isset($_POST['aqlx_volume']) ? sanitize_text_field(wp_unslash($_POST['aqlx_volume'])) : 'standard',
                    'region' => isset($_POST['aqlx_region']) ? sanitize_text_field(wp_unslash($_POST['aqlx_region'])) : 'US',
                    'variant' => isset($_POST['aqlx_variant']) ? sanitize_text_field(wp_unslash($_POST['aqlx_variant'])) : 'classic',
                    'tune_settings' => !empty($_POST['aqlx_tune_settings']),
                ];

                if (in_array($action, ['reset', 'reset_import'], true)) {
                    $confirm = isset($_POST['confirm']) ? sanitize_text_field(wp_unslash($_POST['confirm'])) : '';
                    if ($confirm !== 'CONFIRM') {
                        echo '<div class="notice notice-error"><p>' . esc_html__('Type CONFIRM to proceed with reset.', 'aqualuxe') . '</p></div>';
                    } else {
                        aqlx_reset_site($scope);
                        echo '<div class="notice notice-warning"><p>' . esc_html__('Site reset complete.', 'aqualuxe') . '</p></div>';
                    }
                }
                if (in_array($action, ['import', 'reset_import'], true)) {
                    $log = [];$errors = [];
                    $ok = aqlx_run_demo_import($config, $log, $errors);
                    if ($ok) {
                        echo '<div class="notice notice-success"><p>' . esc_html__('Demo content imported.', 'aqualuxe') . '</p></div>';
                    } else {
                        echo '<div class="notice notice-error"><p>' . esc_html__('Import failed. Rollback executed.', 'aqualuxe') . '</p></div>';
                    }
                    if (!empty($log)) {
                        echo '<details open style="margin-top:1rem"><summary>' . esc_html__('Import Log', 'aqualuxe') . '</summary><ul style="max-height:260px;overflow:auto;">';
                        foreach ($log as $line) echo '<li>' . esc_html($line) . '</li>';
                        echo '</ul>';
                        if (!empty($errors)) {
                            echo '<p style="color:#b32d2e"><strong>' . esc_html__('Errors', 'aqualuxe') . ':</strong></p><ul>';
                            foreach ($errors as $e) echo '<li style="color:#b32d2e">' . esc_html($e) . '</li>';
                            echo '</ul>';
                        }
                        echo '</details>';
                    }
                }
                if ($action === 'rollback') {
                    $rolled = aqlx_rollback_last_import();
                    if ($rolled) echo '<div class="notice notice-warning"><p>' . esc_html__('Rolled back last import.', 'aqualuxe') . '</p></div>';
                    else echo '<div class="notice notice-info"><p>' . esc_html__('No previous import artifacts found.', 'aqualuxe') . '</p></div>';
                }
            }

            // Render UI
            echo '<div class="wrap">';
            echo '<h1>' . esc_html__('AquaLuxe Setup & Demo Import', 'aqualuxe') . '</h1>';
            echo '<p>' . esc_html__('Use these tools to reset the site to a clean state and import fully functional demo content.', 'aqualuxe') . '</p>';

            echo '<h2>' . esc_html__('Reset (Danger Zone)', 'aqualuxe') . '</h2>';
            echo '<form method="post" class="card" style="padding:1rem;max-width:860px;">';
            wp_nonce_field('aqlx_demo');
            echo '<p>' . esc_html__('Select what to reset:', 'aqualuxe') . '</p>';
            echo '<label><input type="checkbox" name="scope_content" checked> ' . esc_html__('Content (posts, pages, products, CPTs)', 'aqualuxe') . '</label><br>';
            echo '<label><input type="checkbox" name="scope_media" checked> ' . esc_html__('Media (attachments)', 'aqualuxe') . '</label><br>';
            echo '<label><input type="checkbox" name="scope_taxonomies" checked> ' . esc_html__('Taxonomies (categories, tags, product terms)', 'aqualuxe') . '</label><br>';
            echo '<label><input type="checkbox" name="scope_menus" checked> ' . esc_html__('Menus and locations', 'aqualuxe') . '</label><br>';
            echo '<label><input type="checkbox" name="scope_widgets" checked> ' . esc_html__('Widgets and sidebars', 'aqualuxe') . '</label><br>';
            echo '<label><input type="checkbox" name="scope_theme_mods"> ' . esc_html__('Theme mods/Customizer (colors, homepage settings, etc.)', 'aqualuxe') . '</label><br>';
            echo '<label><input type="checkbox" name="scope_woo_orders"> ' . esc_html__('WooCommerce orders, coupons, subscriptions (if Woo active)', 'aqualuxe') . '</label><br>';
            echo '<p style="margin-top:8px;color:#b32d2e;"><strong>' . esc_html__('This is destructive and cannot be undone.', 'aqualuxe') . '</strong></p>';
            echo '<p><label>' . esc_html__('Type CONFIRM to proceed:', 'aqualuxe') . ' <input name="confirm" type="text" placeholder="CONFIRM"></label></p>';
            echo '<div style="display:flex;gap:8px;">';
            echo '<button class="button button-secondary" name="aqlx_action" value="reset">' . esc_html__('Reset only', 'aqualuxe') . '</button>';
            echo '<button class="button button-primary" name="aqlx_action" value="reset_import">' . esc_html__('Reset and Import Demo', 'aqualuxe') . '</button>';
            echo '</div>';
            echo '</form>';

            echo '<h2 style="margin-top:2rem;">' . esc_html__('Import Demo Content', 'aqualuxe') . '</h2>';
            echo '<form method="post" class="card" style="padding:1rem;max-width:860px;">';
            wp_nonce_field('aqlx_demo');
            echo '<p>' . esc_html__('Import pages, menus, widgets, users, posts, and sample products/orders.', 'aqualuxe') . '</p>';
            echo '<div style="display:flex;gap:16px;flex-wrap:wrap">';
            echo '<p><label><strong>' . esc_html__('Data volume', 'aqualuxe') . ':</strong> <select name="aqlx_volume">'
                . '<option value="lite">' . esc_html__('Lite', 'aqualuxe') . '</option>'
                . '<option value="standard" selected>' . esc_html__('Standard', 'aqualuxe') . '</option>'
                . '<option value="heavy">' . esc_html__('Heavy', 'aqualuxe') . '</option>'
                . '</select></label></p>';
            echo '<p><label><strong>' . esc_html__('Region', 'aqualuxe') . ':</strong> <select name="aqlx_region">'
                . '<option value="US" selected>US (USD)</option>'
                . '<option value="EU">EU (EUR)</option>'
                . '<option value="UK">UK (GBP)</option>'
                . '<option value="AU">AU (AUD)</option>'
                . '</select></label></p>';
            echo '<p><label><strong>' . esc_html__('Theme variant', 'aqualuxe') . ':</strong> <select name="aqlx_variant">'
                . '<option value="classic" selected>Classic</option>'
                . '<option value="dark">Dark</option>'
                . '<option value="aqua">Aqua</option>'
                . '</select></label></p>';
            echo '<p><label><input type="checkbox" name="aqlx_tune_settings" checked> ' . esc_html__('Apply recommended settings (homepage, permalinks, Woo currency)', 'aqualuxe') . '</label></p>';
            echo '</div>';
            echo '<button class="button button-primary" name="aqlx_action" value="import">' . esc_html__('Import Demo', 'aqualuxe') . '</button>';
            echo '</form>';

            echo '<h2 style="margin-top:2rem;">' . esc_html__('Rollback', 'aqualuxe') . '</h2>';
            echo '<form method="post" class="card" style="padding:1rem;max-width:860px;">';
            wp_nonce_field('aqlx_demo');
            echo '<p>' . esc_html__('Remove artifacts from the last import and restore prior state where possible.', 'aqualuxe') . '</p>';
            echo '<button class="button" name="aqlx_action" value="rollback">' . esc_html__('Rollback last import', 'aqualuxe') . '</button>';
            echo '</form>';

            echo '</div>';
        }
    );
});

// Reset implementation
function aqlx_reset_site(array $scope): void
{
    // Content: posts/pages/products/CPTs
    if (!empty($scope['content'])) {
        $post_types = get_post_types(['public' => true], 'names');
        // Include some private CPTs used by modules and Woo
        foreach (['product', 'product_variation', 'shop_order', 'shop_coupon', 'shop_subscription', 'service', 'event', 'tradein_request', 'quote_request', 'saved_filter'] as $pt) {
            if (post_type_exists($pt)) $post_types[$pt] = $pt;
        }
        // Optionally exclude orders/coupons/subscriptions unless explicitly requested
        if (empty($scope['woo_orders'])) {
            unset($post_types['shop_order'], $post_types['shop_coupon'], $post_types['shop_subscription']);
        }
        foreach ($post_types as $pt) {
            $to_delete = get_posts([
                'post_type' => $pt,
                'numberposts' => -1,
                'post_status' => 'any',
                'fields' => 'ids',
                'no_found_rows' => true,
            ]);
            foreach ($to_delete as $pid) {
                wp_delete_post($pid, true);
            }
        }
    }

    // Media
    if (!empty($scope['media'])) {
        $attachments = get_posts([
            'post_type' => 'attachment',
            'numberposts' => -1,
            'post_status' => 'any',
            'fields' => 'ids',
            'no_found_rows' => true,
        ]);
        foreach ($attachments as $aid) {
            wp_delete_attachment($aid, true);
        }
    }

    // Taxonomies (public + product taxonomies)
    if (!empty($scope['taxonomies'])) {
        $taxes = get_taxonomies(['public' => true], 'names');
        foreach (['product_cat', 'product_tag', 'product_visibility', 'product_shipping_class', 'pa_color', 'pa_size'] as $tx) {
            if (taxonomy_exists($tx)) $taxes[$tx] = $tx;
        }
        foreach ($taxes as $tax) {
            $terms = get_terms(['taxonomy' => $tax, 'hide_empty' => false]);
            if (is_wp_error($terms)) continue;
            foreach ($terms as $term) {
                // Don't delete built-in `uncategorized`
                if ($tax === 'category' && (int) get_option('default_category') === (int) $term->term_id) continue;
                wp_delete_term($term->term_id, $tax);
            }
        }
    }

    // Menus and locations
    if (!empty($scope['menus'])) {
        $menus = wp_get_nav_menus();
        foreach ($menus as $menu) {
            wp_delete_nav_menu($menu->term_id);
        }
        set_theme_mod('nav_menu_locations', []);
    }

    // Widgets and sidebars
    if (!empty($scope['widgets'])) {
        $sidebars = wp_get_sidebars_widgets();
        $sidebars = ['wp_inactive_widgets' => []];
        update_option('sidebars_widgets', $sidebars);
        // Leave widget_* options intact to avoid plugin issues; sidebars are cleared.
    }

    // Theme mods / Customizer
    if (!empty($scope['theme_mods'])) {
        remove_theme_mods();
        update_option('show_on_front', 'posts');
        delete_option('page_on_front');
        delete_option('page_for_posts');
    }

    // Flush rewrites
    flush_rewrite_rules();
}

// Import implementation
function aqlx_run_demo_import(array $config, array &$log = [], array &$errors = []): bool
{
    $artifacts = [
        'posts' => [], 'attachments' => [], 'terms' => [], 'users' => [],
        'menus' => [], 'menu_locations_prev' => null, 'sidebars_prev' => null,
        'options_prev' => [], 'theme_mods_prev' => null,
    ];
    $log[] = 'Starting import...';
    $volume = in_array($config['volume'], ['lite','standard','heavy'], true) ? $config['volume'] : 'standard';
    $region = in_array($config['region'], ['US','EU','UK','AU'], true) ? $config['region'] : 'US';
    $variant = in_array($config['variant'], ['classic','dark','aqua'], true) ? $config['variant'] : 'classic';

    // Snapshot state for rollback
    $artifacts['menu_locations_prev'] = get_theme_mod('nav_menu_locations');
    $artifacts['sidebars_prev'] = get_option('sidebars_widgets');
    $artifacts['options_prev']['woocommerce_currency'] = get_option('woocommerce_currency');
    $artifacts['options_prev']['show_on_front'] = get_option('show_on_front');
    $artifacts['options_prev']['page_on_front'] = get_option('page_on_front');
    $artifacts['options_prev']['page_for_posts'] = get_option('page_for_posts');
    $artifacts['theme_mods_prev'] = get_theme_mods();
    $file = AQUALUXE_PATH . 'demo/demo.json';
    $data = file_exists($file) ? json_decode(file_get_contents($file), true) : null;
    if (!is_array($data)) $data = ['pages' => [], 'menus' => []];

    // Ensure required pages (including Shop) exist
    $pages = $data['pages'];
    $ensure = [
        ['title' => 'Home', 'slug' => 'home', 'template' => 'front-page.php'],
        ['title' => 'Contact', 'slug' => 'contact', 'template' => 'page-contact.php'],
        ['title' => 'Blog', 'slug' => 'blog'],
    ];
    if (class_exists('WooCommerce')) {
        $ensure[] = ['title' => 'Shop', 'slug' => 'shop'];
    }
    // Merge unique by slug
    $by_slug = [];
    foreach (array_merge($pages, $ensure) as $p) { $by_slug[$p['slug']] = $p; }
    $pages = array_values($by_slug);

    $created = [];
    foreach ($pages as $page) {
        $exists = get_page_by_path($page['slug']);
        if ($exists) { $created[$page['slug']] = $exists->ID; continue; }
        $content = '';
        if ($page['slug'] === 'home') {
            $content = '<!-- wp:cover {"dimRatio":20} --><div class="wp-block-cover"><span aria-hidden="true" class="wp-block-cover__background has-background-dim"></span><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center"} --><h2 class="has-text-align-center">Welcome to AquaLuxe</h2><!-- /wp:heading --></div></div><!-- /wp:cover -->';
        }
        $post_id = wp_insert_post([
            'post_title' => sanitize_text_field($page['title']),
            'post_name'  => sanitize_title($page['slug']),
            'post_type'  => 'page',
            'post_status'=> 'publish',
            'post_content' => $content,
        ]);
        if ($post_id && !is_wp_error($post_id)) {
            if (!empty($page['template'])) {
                update_post_meta($post_id, '_wp_page_template', $page['template']);
            }
            $created[$page['slug']] = $post_id;
            $artifacts['posts'][] = (int) $post_id;
            $log[] = 'Created page: ' . $page['title'];
        }
    }

    // WooCommerce basics: Shop page & categories & products
    if (class_exists('WooCommerce')) {
        if (!empty($config['tune_settings'])) {
            $currency_map = ['US' => 'USD', 'EU' => 'EUR', 'UK' => 'GBP', 'AU' => 'AUD'];
            $cur = $currency_map[$region] ?? 'USD';
            update_option('woocommerce_currency', $cur);
            $log[] = 'Set Woo currency: ' . $cur;
        }
        // Create categories if missing
        $cats = [
            'rare-fish' => __('Rare Fish Species', 'aqualuxe'),
            'aquatic-plants' => __('Aquatic Plants', 'aqualuxe'),
            'premium-equipment' => __('Premium Equipment', 'aqualuxe'),
            'care-supplies' => __('Care Supplies', 'aqualuxe'),
        ];
        foreach ($cats as $slug => $name) {
            if (!term_exists($name, 'product_cat')) {
                $term = wp_insert_term($name, 'product_cat', ['slug' => $slug]);
                if (!is_wp_error($term) && isset($term['term_id'])) { $artifacts['terms'][] = (int) $term['term_id']; $log[] = 'Created product_cat: ' . $name; }
            }
        }

        // Ensure Shop page
        $shop_page = get_page_by_path('shop');
        if ($shop_page) {
            update_option('woocommerce_shop_page_id', (int) $shop_page->ID);
        } else {
            $sid = wp_insert_post([
                'post_title' => 'Shop',
                'post_name'  => 'shop',
                'post_type'  => 'page',
                'post_status'=> 'publish',
            ]);
            if ($sid && !is_wp_error($sid)) update_option('woocommerce_shop_page_id', (int) $sid);
            $created['shop'] = $sid;
        }

        // Sample products
        $samples = [
            ['name' => 'Golden Arowana', 'price' => 1299.00, 'cat' => 'rare-fish'],
            ['name' => 'Crystal Moss Ball', 'price' => 12.50, 'cat' => 'aquatic-plants'],
            ['name' => 'Titanium Heater 300W', 'price' => 89.00, 'cat' => 'premium-equipment'],
            ['name' => 'Bio Filter Media Pack', 'price' => 24.00, 'cat' => 'care-supplies'],
            ['name' => 'Koi Nishikigoi', 'price' => 499.00, 'cat' => 'rare-fish'],
            ['name' => 'Anubias Nana Petite', 'price' => 8.90, 'cat' => 'aquatic-plants'],
            ['name' => 'LED Light Bar 90cm', 'price' => 149.99, 'cat' => 'premium-equipment'],
            ['name' => 'Water Conditioner 1L', 'price' => 14.99, 'cat' => 'care-supplies'],
        ];
        if ($volume === 'lite') $samples = array_slice($samples, 0, 4);
        if ($volume === 'heavy') $samples = array_merge($samples, $samples, $samples);
        foreach ($samples as $s) {
            $pid = wp_insert_post([
                'post_title' => sanitize_text_field($s['name']),
                'post_type'  => 'product',
                'post_status'=> 'publish',
                'post_content' => 'Quality guaranteed by AquaLuxe.',
            ]);
            if (is_wp_error($pid) || !$pid) continue;
            wp_set_object_terms($pid, $s['cat'], 'product_cat');
            update_post_meta($pid, '_regular_price', (string) $s['price']);
            update_post_meta($pid, '_price', (string) $s['price']);
            update_post_meta($pid, '_stock_status', 'instock');
            update_post_meta($pid, '_manage_stock', 'no');
            wp_set_object_terms($pid, 'simple', 'product_type');
            // Thumbnail from theme screenshot
            $thumb_id = aqlx_import_theme_image('screenshot.png');
            if ($thumb_id) set_post_thumbnail($pid, $thumb_id);
            $artifacts['posts'][] = (int) $pid; if ($thumb_id) $artifacts['attachments'][] = (int) $thumb_id;
            $log[] = 'Created product: ' . $s['name'];
        }

        // Create coupons
        $coupon_count = ($volume === 'lite') ? 1 : (($volume === 'heavy') ? 5 : 2);
        for ($i=0; $i<$coupon_count; $i++) {
            $code = 'AQLX' . wp_generate_password(6, false, false);
            $cid = wp_insert_post([
                'post_title' => $code,
                'post_type' => 'shop_coupon',
                'post_status' => 'publish',
                'post_excerpt' => 'Demo coupon',
            ]);
            if ($cid && !is_wp_error($cid)) {
                update_post_meta($cid, 'discount_type', 'percent');
                update_post_meta($cid, 'coupon_amount', '10');
                update_post_meta($cid, 'individual_use', 'no');
                $artifacts['posts'][] = (int) $cid; $log[] = 'Created coupon: ' . $code;
            }
        }
    }

    // Menus
    $menus = $data['menus'];
    // Add shop to primary menu automatically if Woo active
    if (class_exists('WooCommerce')) {
        if (empty($menus['primary'])) $menus['primary'] = [];
        if (!in_array('shop', $menus['primary'], true)) $menus['primary'][] = 'shop';
    }
    foreach (['primary','footer'] as $loc) {
        if (empty($menus[$loc])) continue;
        $menu_name = 'AquaLuxe ' . ucfirst($loc);
    $menu = wp_get_nav_menu_object($menu_name);
    $menu_id = $menu ? (int) $menu->term_id : (int) wp_create_nav_menu($menu_name);
    if (!$menu) $artifacts['menus'][] = (int) $menu_id;
        foreach ($menus[$loc] as $slug) {
            $pid = $created[$slug] ?? ($slug === 'shop' ? (int) get_option('woocommerce_shop_page_id') : 0);
            if (!$pid) continue;
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-object-id' => $pid,
                'menu-item-object' => 'page',
                'menu-item-type' => 'post_type',
                'menu-item-status' => 'publish',
            ]);
        }
        $locations = get_theme_mod('nav_menu_locations');
        if (!is_array($locations)) $locations = [];
        $locations[$loc] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    // Set homepage and posts page
    if (isset($created['home'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', (int) $created['home']);
    }
    if (isset($created['blog'])) {
        update_option('page_for_posts', (int) $created['blog']);
    }

    // Widgets: populate Shop Filters with Woo widgets if available
    if (class_exists('WooCommerce')) {
        aqlx_assign_widget('shop-filters', 'woocommerce_price_filter', ['title' => __('Filter by price', 'aqualuxe')]);
        aqlx_assign_widget('shop-filters', 'woocommerce_layered_nav', [
            'title' => __('Filter by category', 'aqualuxe'),
            'attribute' => 'product_cat',
            'query_type' => 'and',
            'display_type' => 'list',
            'show_count' => 0,
            'hide_empty' => 0,
        ]);
    }

    // Blog posts and categories
    $post_titles = ['Aquarium Care 101', 'Choosing the Right Filter', 'Top 5 Rare Fish', 'Beginner’s Guide to Aquascaping'];
    if ($volume === 'lite') $post_titles = array_slice($post_titles, 0, 2);
    if ($volume === 'heavy') $post_titles = array_merge($post_titles, $post_titles);
    $blog_cat = term_exists('Guides', 'category');
    if (!$blog_cat) { $blog_cat = wp_insert_term('Guides', 'category'); if (!is_wp_error($blog_cat)) $artifacts['terms'][] = (int) $blog_cat['term_id']; }
    foreach ($post_titles as $t) {
        $bid = wp_insert_post([
            'post_title' => $t,
            'post_type' => 'post',
            'post_status' => 'publish',
            'post_content' => 'Demo article content for ' . $t . '.',
        ]);
        if ($bid && !is_wp_error($bid)) {
            $artifacts['posts'][] = (int) $bid;
            if (!is_wp_error($blog_cat) && isset($blog_cat['term_id'])) wp_set_post_terms($bid, [(int) $blog_cat['term_id']], 'category');
            $log[] = 'Created post: ' . $t;
        }
    }

    // Users: customers, author, shop manager
    $user_count = ($volume === 'lite') ? 1 : (($volume === 'heavy') ? 6 : 3);
    $demo_users = [];
    for ($i = 0; $i < $user_count; $i++) {
        $username = 'aqlx_customer' . ($i + 1);
        $email = $username . '@example.com';
        $uid = username_exists($username);
        if (!$uid) {
            $uid = wp_create_user($username, wp_generate_password(12, true, false), $email);
            if (!is_wp_error($uid)) {
                $u = new WP_User($uid);
                if ($u && method_exists($u, 'set_role')) $u->set_role('customer');
                update_user_meta($uid, 'aqlx_demo', 1);
                update_user_meta($uid, 'billing_first_name', 'Customer');
                update_user_meta($uid, 'billing_last_name', (string) ($i + 1));
                update_user_meta($uid, 'billing_country', $region === 'EU' ? 'DE' : ($region === 'UK' ? 'GB' : ($region === 'AU' ? 'AU' : 'US')));
                $artifacts['users'][] = (int) $uid; $log[] = 'Created user: ' . $username;
                $demo_users[] = (int) $uid;
            }
        } else {
            $demo_users[] = (int) $uid;
        }
    }
    $author = username_exists('aqlx_author');
    if (!$author) {
        $author = wp_create_user('aqlx_author', wp_generate_password(12, true, false), 'author@example.com');
        if (!is_wp_error($author)) { (new WP_User($author))->set_role('author'); update_user_meta($author, 'aqlx_demo', 1); $artifacts['users'][] = (int) $author; $log[] = 'Created user: aqlx_author'; }
    }
    $manager = username_exists('aqlx_manager');
    if (class_exists('WooCommerce') && !$manager) {
        $manager = wp_create_user('aqlx_manager', wp_generate_password(12, true, false), 'manager@example.com');
        if (!is_wp_error($manager)) { (new WP_User($manager))->set_role('shop_manager'); update_user_meta($manager, 'aqlx_demo', 1); $artifacts['users'][] = (int) $manager; $log[] = 'Created user: aqlx_manager'; }
    }

    // Product reviews
    if (class_exists('WooCommerce')) {
        $products = get_posts(['post_type' => 'product', 'numberposts' => -1, 'fields' => 'ids']);
        $review_count = ($volume === 'lite') ? 2 : (($volume === 'heavy') ? 12 : 6);
        foreach (array_slice($products, 0, max(1, count($products))) as $pid) {
            for ($i = 0; $i < min($review_count, 3); $i++) {
                $cid = wp_insert_comment([
                    'comment_post_ID' => $pid,
                    'comment_author' => 'Demo Reviewer',
                    'comment_content' => 'Great quality and fast shipping!',
                    'comment_type' => 'review',
                    'comment_approved' => 1,
                ]);
                if ($cid) { update_comment_meta($cid, 'rating', rand(4, 5)); $log[] = 'Added review to product #' . $pid; }
            }
        }
    }

    // Orders
    if (class_exists('WooCommerce') && function_exists('wc_create_order')) {
        $order_count = ($volume === 'lite') ? 1 : (($volume === 'heavy') ? 6 : 3);
        $products = get_posts(['post_type' => 'product', 'numberposts' => -1, 'fields' => 'ids']);
        for ($i = 0; $i < $order_count; $i++) {
            try {
                $order = wc_create_order();
                if (!empty($products)) {
                    $pid = $products[array_rand($products)];
                    $order->add_product(wc_get_product($pid), rand(1, 3));
                }
                if (!empty($demo_users)) $order->set_customer_id($demo_users[array_rand($demo_users)]);
                $order->calculate_totals();
                $order->update_status('completed');
                $artifacts['posts'][] = (int) $order->get_id();
                $log[] = 'Created order #' . $order->get_id();
            } catch (Throwable $e) {
                $errors[] = 'Order creation failed: ' . $e->getMessage();
                aqlx_import_rollback($artifacts, $log);
                return false;
            }
        }
    }

    // Apply theme variant flag
    set_theme_mod('aqlx_theme_variant', $variant);
    $log[] = 'Theme variant applied: ' . $variant;
    // Save artifacts for potential rollback
    update_option('aqlx_import_artifacts', $artifacts);
    $log[] = 'Import completed successfully.';
    return true;
}

// Rollback helpers
function aqlx_import_rollback(array $artifacts, array &$log): void
{
    if (!empty($artifacts['posts'])) { foreach ($artifacts['posts'] as $pid) wp_delete_post($pid, true); $log[] = 'Rolled back posts.'; }
    if (!empty($artifacts['attachments'])) { foreach ($artifacts['attachments'] as $aid) wp_delete_attachment($aid, true); $log[] = 'Rolled back attachments.'; }
    if (!empty($artifacts['terms'])) { foreach ($artifacts['terms'] as $tid) { $term = get_term($tid); if ($term && !is_wp_error($term)) wp_delete_term($tid, $term->taxonomy); } $log[] = 'Rolled back terms.'; }
    if (!empty($artifacts['users'])) { foreach ($artifacts['users'] as $uid) wp_delete_user($uid); $log[] = 'Rolled back users.'; }
    if (!empty($artifacts['menus'])) { foreach ($artifacts['menus'] as $mid) wp_delete_nav_menu($mid); $log[] = 'Rolled back menus.'; }
    if (isset($artifacts['menu_locations_prev'])) set_theme_mod('nav_menu_locations', $artifacts['menu_locations_prev']);
    if (isset($artifacts['sidebars_prev'])) update_option('sidebars_widgets', $artifacts['sidebars_prev']);
    if (!empty($artifacts['options_prev'])) foreach ($artifacts['options_prev'] as $k => $v) update_option($k, $v);
    if (isset($artifacts['theme_mods_prev'])) foreach ($artifacts['theme_mods_prev'] as $k => $v) set_theme_mod($k, $v);
}

function aqlx_rollback_last_import(): bool
{
    $artifacts = get_option('aqlx_import_artifacts');
    if (!$artifacts || !is_array($artifacts)) return false;
    $log = [];
    aqlx_import_rollback($artifacts, $log);
    delete_option('aqlx_import_artifacts');
    return true;
}

// Helper: copy an image from theme into uploads and return attachment ID
function aqlx_import_theme_image(string $basename): int
{
    $path = AQUALUXE_PATH . $basename;
    if (!file_exists($path)) return 0;
    $bits = wp_upload_bits($basename, null, file_get_contents($path));
    if (!empty($bits['error'])) return 0;
    $filetype = wp_check_filetype($bits['file'], null);
    $attachment = [
        'post_mime_type' => $filetype['type'],
        'post_title'     => sanitize_file_name($basename),
        'post_content'   => '',
        'post_status'    => 'inherit',
    ];
    $attach_id = wp_insert_attachment($attachment, $bits['file']);
    if (is_wp_error($attach_id) || !$attach_id) return 0;
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $attach_data = wp_generate_attachment_metadata($attach_id, $bits['file']);
    wp_update_attachment_metadata($attach_id, $attach_data);
    return (int) $attach_id;
}

// Helper: add a widget instance to a sidebar
function aqlx_assign_widget(string $sidebar_id, string $id_base, array $settings = []): void
{
    $sidebars = wp_get_sidebars_widgets();
    if (!is_array($sidebars)) $sidebars = [];
    if (!isset($sidebars[$sidebar_id])) $sidebars[$sidebar_id] = [];

    $opt_name = 'widget_' . $id_base;
    $instances = get_option($opt_name, []);
    if (!is_array($instances)) $instances = [];
    // Find next numeric index
    $next = 2; // WordPress uses 2+ for widgets
    while (isset($instances[$next])) $next++;
    $instances[$next] = $settings;
    update_option($opt_name, $instances);

    // Compose widget unique ID like id_base-index
    $widget_uid = $id_base . '-' . $next;
    if (!in_array($widget_uid, $sidebars[$sidebar_id], true)) {
        $sidebars[$sidebar_id][] = $widget_uid;
        update_option('sidebars_widgets', $sidebars);
    }
}
