<?php
/**
 * WooCommerce Fallback
 *
 * This file provides fallback functionality when WooCommerce is not active.
 * It's part of the dual-state architecture of the AquaLuxe theme.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Define fallback constants and functions that would normally be provided by WooCommerce.
 */

if ( ! function_exists( 'is_woocommerce' ) ) {
    /**
     * Fallback for is_woocommerce function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_woocommerce() {
        return false;
    }
}

if ( ! function_exists( 'is_shop' ) ) {
    /**
     * Fallback for is_shop function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_shop() {
        return false;
    }
}

if ( ! function_exists( 'is_product_category' ) ) {
    /**
     * Fallback for is_product_category function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_product_category() {
        return false;
    }
}

if ( ! function_exists( 'is_product_tag' ) ) {
    /**
     * Fallback for is_product_tag function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_product_tag() {
        return false;
    }
}

if ( ! function_exists( 'is_product' ) ) {
    /**
     * Fallback for is_product function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_product() {
        return false;
    }
}

if ( ! function_exists( 'is_cart' ) ) {
    /**
     * Fallback for is_cart function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_cart() {
        return false;
    }
}

if ( ! function_exists( 'is_checkout' ) ) {
    /**
     * Fallback for is_checkout function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_checkout() {
        return false;
    }
}

if ( ! function_exists( 'is_account_page' ) ) {
    /**
     * Fallback for is_account_page function.
     *
     * @return bool Always returns false when WooCommerce is not active.
     */
    function is_account_page() {
        return false;
    }
}

/**
 * Register fallback shop page.
 *
 * @return void
 */
function aqualuxe_register_shop_page() {
    // Check if the shop page exists.
    $shop_page = get_page_by_path( 'shop' );

    // If the shop page doesn't exist, create it.
    if ( ! $shop_page ) {
        $shop_page_id = wp_insert_post( array(
            'post_title'     => __( 'Shop', 'aqualuxe' ),
            'post_content'   => aqualuxe_get_shop_page_content(),
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
        ) );
    }
}
add_action( 'after_switch_theme', 'aqualuxe_register_shop_page' );

/**
 * Get shop page content.
 *
 * @return string
 */
function aqualuxe_get_shop_page_content() {
    ob_start();
    ?>
    <div class="woocommerce-not-active">
        <h2><?php esc_html_e( 'Shop Coming Soon', 'aqualuxe' ); ?></h2>
        <p><?php esc_html_e( 'Our online shop is currently being set up. Please check back later.', 'aqualuxe' ); ?></p>
        
        <?php if ( current_user_can( 'activate_plugins' ) ) : ?>
            <div class="admin-notice">
                <p><?php esc_html_e( 'Admin Notice: WooCommerce is not active. To enable e-commerce functionality, please install and activate the WooCommerce plugin.', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>" class="button"><?php esc_html_e( 'Go to Plugins', 'aqualuxe' ); ?></a>
            </div>
        <?php endif; ?>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Add shop menu item to primary menu if it doesn't exist.
 *
 * @param array $items Menu items.
 * @param object $args Menu arguments.
 * @return array
 */
function aqualuxe_add_shop_menu_item( $items, $args ) {
    // Only add to primary menu.
    if ( $args->theme_location !== 'primary' ) {
        return $items;
    }

    // Check if a shop menu item already exists.
    $shop_exists = false;
    foreach ( $items as $item ) {
        if ( $item->title === 'Shop' || $item->title === __( 'Shop', 'aqualuxe' ) ) {
            $shop_exists = true;
            break;
        }
    }

    // If shop menu item doesn't exist, add it.
    if ( ! $shop_exists ) {
        $shop_page = get_page_by_path( 'shop' );
        if ( $shop_page ) {
            $shop_item = new stdClass();
            $shop_item->ID = 0;
            $shop_item->db_id = 0;
            $shop_item->menu_item_parent = 0;
            $shop_item->object_id = $shop_page->ID;
            $shop_item->object = 'page';
            $shop_item->type = 'post_type';
            $shop_item->type_label = 'Page';
            $shop_item->url = get_permalink( $shop_page->ID );
            $shop_item->title = __( 'Shop', 'aqualuxe' );
            $shop_item->target = '';
            $shop_item->attr_title = '';
            $shop_item->description = '';
            $shop_item->classes = array( 'menu-item', 'menu-item-type-post_type', 'menu-item-object-page' );
            $shop_item->xfn = '';
            $shop_item->current = false;

            // Add shop item to the end of the menu.
            $items[] = $shop_item;
        }
    }

    return $items;
}
add_filter( 'wp_nav_menu_objects', 'aqualuxe_add_shop_menu_item', 10, 2 );

/**
 * Add shop sidebar.
 *
 * @return void
 */
function aqualuxe_register_shop_sidebar() {
    register_sidebar( array(
        'name'          => __( 'Shop Sidebar', 'aqualuxe' ),
        'id'            => 'sidebar-shop',
        'description'   => __( 'Add widgets here to appear in your shop sidebar.', 'aqualuxe' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'aqualuxe_register_shop_sidebar' );

/**
 * Add shop sidebar to shop page.
 *
 * @param string $sidebar Sidebar ID.
 * @return string
 */
function aqualuxe_get_sidebar( $sidebar ) {
    if ( is_page( 'shop' ) && $sidebar === 'sidebar-1' ) {
        return 'sidebar-shop';
    }
    return $sidebar;
}
add_filter( 'aqualuxe_get_sidebar', 'aqualuxe_get_sidebar' );

/**
 * Add body class for shop page.
 *
 * @param array $classes Body classes.
 * @return array
 */
function aqualuxe_shop_body_class( $classes ) {
    if ( is_page( 'shop' ) ) {
        $classes[] = 'woocommerce-fallback';
        $classes[] = 'woocommerce-shop';
    }
    return $classes;
}
add_filter( 'body_class', 'aqualuxe_shop_body_class' );

/**
 * Add shop page to breadcrumbs.
 *
 * @param array $items Breadcrumb items.
 * @return array
 */
function aqualuxe_shop_breadcrumb( $items ) {
    if ( is_page( 'shop' ) ) {
        $items[] = array(
            'text' => __( 'Shop', 'aqualuxe' ),
            'url'  => get_permalink( get_page_by_path( 'shop' ) ),
        );
    }
    return $items;
}
add_filter( 'aqualuxe_breadcrumb_items', 'aqualuxe_shop_breadcrumb' );

/**
 * Add featured products section to homepage.
 *
 * @return void
 */
function aqualuxe_featured_products_section() {
    // Only show on homepage.
    if ( ! is_front_page() ) {
        return;
    }

    // Get recent posts to display as "products".
    $recent_posts = get_posts( array(
        'posts_per_page' => 4,
        'post_type'      => 'post',
    ) );

    if ( empty( $recent_posts ) ) {
        return;
    }
    ?>
    <section class="featured-products">
        <div class="container">
            <h2 class="section-title"><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></h2>
            <div class="products columns-4">
                <?php foreach ( $recent_posts as $post ) : ?>
                    <div class="product">
                        <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="product-link">
                            <?php if ( has_post_thumbnail( $post->ID ) ) : ?>
                                <div class="product-image">
                                    <?php echo get_the_post_thumbnail( $post->ID, 'medium' ); ?>
                                </div>
                            <?php else : ?>
                                <div class="product-image no-image">
                                    <div class="placeholder"><?php esc_html_e( 'No Image', 'aqualuxe' ); ?></div>
                                </div>
                            <?php endif; ?>
                            <h3 class="product-title"><?php echo esc_html( get_the_title( $post->ID ) ); ?></h3>
                        </a>
                        <div class="product-categories">
                            <?php
                            $categories = get_the_category( $post->ID );
                            if ( ! empty( $categories ) ) {
                                echo esc_html( $categories[0]->name );
                            }
                            ?>
                        </div>
                        <a href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>" class="button"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?></a>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="more-products">
                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'shop' ) ) ); ?>" class="button"><?php esc_html_e( 'View All', 'aqualuxe' ); ?></a>
            </div>
        </div>
    </section>
    <?php
}
add_action( 'aqualuxe_after_content', 'aqualuxe_featured_products_section', 20 );

/**
 * Add notice about WooCommerce not being active.
 *
 * @return void
 */
function aqualuxe_woocommerce_notice() {
    // Only show to admins.
    if ( ! current_user_can( 'activate_plugins' ) ) {
        return;
    }

    // Only show on dashboard and themes page.
    $screen = get_current_screen();
    if ( ! $screen || ( $screen->id !== 'dashboard' && $screen->id !== 'themes' ) ) {
        return;
    }

    ?>
    <div class="notice notice-info is-dismissible">
        <p>
            <?php
            printf(
                /* translators: %1$s: Theme name, %2$s: WooCommerce URL */
                esc_html__( 'The %1$s theme works best with WooCommerce. To enable all e-commerce features, please %2$s.', 'aqualuxe' ),
                '<strong>AquaLuxe</strong>',
                '<a href="' . esc_url( admin_url( 'plugin-install.php?s=woocommerce&tab=search&type=term' ) ) . '">' . esc_html__( 'install and activate WooCommerce', 'aqualuxe' ) . '</a>'
            );
            ?>
        </p>
    </div>
    <?php
}
add_action( 'admin_notices', 'aqualuxe_woocommerce_notice' );

/**
 * Add CSS for WooCommerce fallback.
 *
 * @return void
 */
function aqualuxe_woocommerce_fallback_styles() {
    // Only load on shop page.
    if ( ! is_page( 'shop' ) && ! is_front_page() ) {
        return;
    }

    ?>
    <style type="text/css">
        /* Featured Products Section */
        .featured-products {
            padding: 60px 0;
            background-color: #f9f9f9;
        }
        
        .featured-products .section-title {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .featured-products .products {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 30px;
        }
        
        @media (max-width: 991px) {
            .featured-products .products {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 575px) {
            .featured-products .products {
                grid-template-columns: 1fr;
            }
        }
        
        .featured-products .product {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .featured-products .product:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .featured-products .product-image {
            height: 200px;
            overflow: hidden;
        }
        
        .featured-products .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .featured-products .product:hover .product-image img {
            transform: scale(1.05);
        }
        
        .featured-products .product-image.no-image {
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .featured-products .product-image .placeholder {
            color: #999;
            font-size: 14px;
        }
        
        .featured-products .product-title {
            padding: 15px 15px 5px;
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .featured-products .product-categories {
            padding: 0 15px 15px;
            color: #777;
            font-size: 14px;
        }
        
        .featured-products .button {
            display: block;
            margin: 0 15px 15px;
            padding: 8px 15px;
            background-color: var(--color-primary);
            color: #fff;
            text-align: center;
            border-radius: 3px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        
        .featured-products .button:hover {
            background-color: var(--color-primary-700);
        }
        
        .featured-products .more-products {
            text-align: center;
            margin-top: 40px;
        }
        
        .featured-products .more-products .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
        }
        
        /* WooCommerce Not Active Notice */
        .woocommerce-not-active {
            text-align: center;
            padding: 60px 20px;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        
        .woocommerce-not-active h2 {
            margin-bottom: 20px;
            font-size: 28px;
        }
        
        .woocommerce-not-active p {
            margin-bottom: 30px;
            font-size: 18px;
            color: #666;
        }
        
        .woocommerce-not-active .admin-notice {
            margin-top: 40px;
            padding: 20px;
            background-color: #fff;
            border-left: 4px solid #00a0d2;
            box-shadow: 0 1px 5px rgba(0, 0, 0, 0.05);
            text-align: left;
        }
        
        .woocommerce-not-active .admin-notice p {
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        .woocommerce-not-active .button {
            display: inline-block;
            padding: 10px 20px;
            background-color: var(--color-primary);
            color: #fff;
            text-decoration: none;
            border-radius: 3px;
            transition: background-color 0.3s ease;
        }
        
        .woocommerce-not-active .button:hover {
            background-color: var(--color-primary-700);
        }
    </style>
    <?php
}
add_action( 'wp_head', 'aqualuxe_woocommerce_fallback_styles' );