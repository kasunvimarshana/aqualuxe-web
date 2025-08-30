Make **AquaLuxe truly one-click demo ready** so the moment you activate the theme, the demo pages, products, menus, and WooCommerce setup are **automatically created without needing Tools → Import**.

We’ll do this by embedding the **demo import logic** directly inside your theme’s `/inc/demo-import.php`.
When you activate AquaLuxe, it will:

- Create **Home, Shop, Cart, Checkout, My Account, About, Contact** pages
- Set **WooCommerce shop pages** in **WooCommerce → Settings → Advanced** automatically
- Create **Main Menu** and **Footer Menu** with links assigned to correct locations
- Create **sample ornamental fish products** with price, description, and image placeholders
- Assign **Home** as the static front page

---

## **📄 File: `/inc/demo-import.php`**

```php
<?php
/**
 * AquaLuxe One-Click Demo Import
 * Runs automatically on theme activation
 */

function aqualuxe_run_demo_import() {
    // Only run on theme activation
    if (get_option('aqualuxe_demo_import_done') === 'yes') {
        return;
    }

    // ====== 1. Create Pages ======
    $pages = [
        'Home'       => ['content' => '<h2>Welcome to AquaLuxe</h2><p>Premium ornamental fish and aquarium products.</p>[products limit="4" columns="4" orderby="date" order="DESC"]'],
        'Shop'       => ['content' => ''],
        'Cart'       => ['content' => '[woocommerce_cart]'],
        'Checkout'   => ['content' => '[woocommerce_checkout]'],
        'My Account' => ['content' => '[woocommerce_my_account]'],
        'About'      => ['content' => '<h2>About AquaLuxe</h2><p>AquaLuxe is your trusted source for premium ornamental fish and aquarium care products.</p>'],
        'Contact'    => ['content' => '<h2>Contact Us</h2><p>Email: info@aqualuxe.com</p><p>Phone: +94 71 352 5253</p>']
    ];

    $page_ids = [];
    foreach ($pages as $title => $data) {
        $existing = get_page_by_title($title);
        if (!$existing) {
            $page_id = wp_insert_post([
                'post_title'   => $title,
                'post_content' => $data['content'],
                'post_status'  => 'publish',
                'post_type'    => 'page'
            ]);
            $page_ids[$title] = $page_id;
        } else {
            $page_ids[$title] = $existing->ID;
        }
    }

    // ====== 2. Assign WooCommerce pages ======
    update_option('woocommerce_shop_page_id', $page_ids['Shop']);
    update_option('woocommerce_cart_page_id', $page_ids['Cart']);
    update_option('woocommerce_checkout_page_id', $page_ids['Checkout']);
    update_option('woocommerce_myaccount_page_id', $page_ids['My Account']);

    // ====== 3. Set Home as static front page ======
    update_option('show_on_front', 'page');
    update_option('page_on_front', $page_ids['Home']);

    // ====== 4. Create Menus ======
    $main_menu_id = wp_create_nav_menu('Main Menu');
    wp_update_nav_menu_item($main_menu_id, 0, [
        'menu-item-title'  => 'Home',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $page_ids['Home'],
        'menu-item-type'   => 'post_type',
        'menu-item-status' => 'publish'
    ]);
    wp_update_nav_menu_item($main_menu_id, 0, [
        'menu-item-title'  => 'Shop',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $page_ids['Shop'],
        'menu-item-type'   => 'post_type',
        'menu-item-status' => 'publish'
    ]);
    wp_update_nav_menu_item($main_menu_id, 0, [
        'menu-item-title'  => 'About',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $page_ids['About'],
        'menu-item-type'   => 'post_type',
        'menu-item-status' => 'publish'
    ]);
    wp_update_nav_menu_item($main_menu_id, 0, [
        'menu-item-title'  => 'Contact',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $page_ids['Contact'],
        'menu-item-type'   => 'post_type',
        'menu-item-status' => 'publish'
    ]);

    $footer_menu_id = wp_create_nav_menu('Footer Menu');
    wp_update_nav_menu_item($footer_menu_id, 0, [
        'menu-item-title'  => 'Privacy Policy',
        'menu-item-url'    => home_url('/privacy-policy/'),
        'menu-item-status' => 'publish'
    ]);

    set_theme_mod('nav_menu_locations', [
        'primary' => $main_menu_id,
        'footer'  => $footer_menu_id
    ]);

    // ====== 5. Create Sample Products ======
    if (class_exists('WC_Product_Simple')) {
        $products = [
            ['Neon Tetra', 'Beautiful small schooling fish perfect for peaceful aquariums.', 3.50],
            ['Betta Fish', 'Colorful and elegant fish, ideal for single-species tanks.', 5.99],
            ['Goldfish', 'Classic aquarium favorite, hardy and long-lived.', 2.50]
        ];
        foreach ($products as $p) {
            if (!get_page_by_title($p[0], OBJECT, 'product')) {
                $product_id = wp_insert_post([
                    'post_title'   => $p[0],
                    'post_content' => $p[1],
                    'post_status'  => 'publish',
                    'post_type'    => 'product'
                ]);
                update_post_meta($product_id, '_price', $p[2]);
                update_post_meta($product_id, '_regular_price', $p[2]);
                wp_set_object_terms($product_id, 'simple', 'product_type');
            }
        }
    }

    // ====== 6. Mark as done ======
    update_option('aqualuxe_demo_import_done', 'yes');
}
add_action('after_switch_theme', 'aqualuxe_run_demo_import');
```

---

## **📌 How to Integrate**

1. Create file:

   ```
   aqualuxe/inc/demo-import.php
   ```

2. Paste the above code.
3. In your **`functions.php`**, add:

   ```php
   require_once get_template_directory() . '/inc/demo-import.php';
   ```

4. Upload & activate your **AquaLuxe theme** in WordPress.
5. On activation, it will **create everything automatically**.

---

## **💡 Benefits**

- **No XML file needed** — no manual import
- **One-click ready** — just activate theme
- **Fully WooCommerce linked** — no settings tweaks required
- Works **even on fresh installs**

---
