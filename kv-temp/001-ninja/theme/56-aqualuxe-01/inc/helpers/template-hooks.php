<?php
/**
 * Template hooks for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Header hooks
 */
add_action( 'aqualuxe_header_top_left', 'aqualuxe_header_contact_info', 10 );
add_action( 'aqualuxe_header_top_right', 'aqualuxe_header_social_icons', 10 );
add_action( 'aqualuxe_header_top_right', 'aqualuxe_header_account_links', 20 );
add_action( 'aqualuxe_header_logo', 'aqualuxe_site_branding', 10 );
add_action( 'aqualuxe_header_navigation', 'aqualuxe_primary_navigation', 10 );
add_action( 'aqualuxe_header_actions', 'aqualuxe_header_search', 10 );
add_action( 'aqualuxe_header_actions', 'aqualuxe_header_cart', 20 );
add_action( 'aqualuxe_header_actions', 'aqualuxe_header_wishlist', 30 );
add_action( 'aqualuxe_header_actions', 'aqualuxe_dark_mode_toggle', 40 );
add_action( 'aqualuxe_header_bottom_content', 'aqualuxe_breadcrumbs', 10 );

/**
 * Footer hooks
 */
add_action( 'aqualuxe_footer_top_content', 'aqualuxe_footer_newsletter', 10 );
add_action( 'aqualuxe_footer_widgets', 'aqualuxe_footer_widgets', 10 );
add_action( 'aqualuxe_footer_copyright', 'aqualuxe_footer_copyright', 10 );
add_action( 'aqualuxe_footer_menu', 'aqualuxe_footer_menu', 10 );

/**
 * Content hooks
 */
add_action( 'aqualuxe_content_top', 'aqualuxe_page_header', 10 );
add_action( 'aqualuxe_content_bottom', 'aqualuxe_related_posts', 10 );

/**
 * Post hooks
 */
add_action( 'aqualuxe_post_header', 'aqualuxe_post_thumbnail', 10 );
add_action( 'aqualuxe_post_header', 'aqualuxe_post_title', 20 );
add_action( 'aqualuxe_post_header', 'aqualuxe_post_meta', 30 );
add_action( 'aqualuxe_post_footer', 'aqualuxe_post_tags', 10 );
add_action( 'aqualuxe_post_footer', 'aqualuxe_post_author_bio', 20 );
add_action( 'aqualuxe_post_footer', 'aqualuxe_post_navigation', 30 );
add_action( 'aqualuxe_post_footer', 'aqualuxe_post_comments', 40 );

/**
 * Page hooks
 */
add_action( 'aqualuxe_page_header', 'aqualuxe_page_title', 10 );
add_action( 'aqualuxe_page_header', 'aqualuxe_page_featured_image', 20 );

/**
 * Archive hooks
 */
add_action( 'aqualuxe_archive_header', 'aqualuxe_archive_title', 10 );
add_action( 'aqualuxe_archive_header', 'aqualuxe_archive_description', 20 );
add_action( 'aqualuxe_archive_footer', 'aqualuxe_pagination', 10 );

/**
 * Search hooks
 */
add_action( 'aqualuxe_search_header', 'aqualuxe_search_title', 10 );
add_action( 'aqualuxe_search_header', 'aqualuxe_search_form', 20 );
add_action( 'aqualuxe_search_footer', 'aqualuxe_pagination', 10 );

/**
 * 404 hooks
 */
add_action( 'aqualuxe_404_header', 'aqualuxe_404_title', 10 );
add_action( 'aqualuxe_404_content', 'aqualuxe_404_content', 10 );
add_action( 'aqualuxe_404_content', 'aqualuxe_search_form', 20 );

/**
 * Comments hooks
 */
add_action( 'aqualuxe_comments_list', 'aqualuxe_comments_list', 10 );
add_action( 'aqualuxe_comments_navigation', 'aqualuxe_comments_navigation', 10 );
add_action( 'aqualuxe_comments_form', 'aqualuxe_comments_form', 10 );

/**
 * WooCommerce hooks
 */
if ( class_exists( 'WooCommerce' ) ) {
    add_action( 'aqualuxe_shop_header', 'aqualuxe_shop_title', 10 );
    add_action( 'aqualuxe_shop_header', 'aqualuxe_shop_description', 20 );
    add_action( 'aqualuxe_shop_header', 'aqualuxe_shop_filters', 30 );
    add_action( 'aqualuxe_shop_footer', 'aqualuxe_pagination', 10 );
    
    add_action( 'aqualuxe_product_header', 'aqualuxe_product_gallery', 10 );
    add_action( 'aqualuxe_product_header', 'aqualuxe_product_summary', 20 );
    add_action( 'aqualuxe_product_footer', 'aqualuxe_product_tabs', 10 );
    add_action( 'aqualuxe_product_footer', 'aqualuxe_related_products', 20 );
    add_action( 'aqualuxe_product_footer', 'aqualuxe_upsell_products', 30 );
    
    add_action( 'aqualuxe_product_summary_before', 'aqualuxe_product_badges', 10 );
    add_action( 'aqualuxe_product_summary_after', 'aqualuxe_product_meta', 10 );
    add_action( 'aqualuxe_product_summary_after', 'aqualuxe_product_sharing', 20 );
}

/**
 * Display the site branding.
 */
function aqualuxe_site_branding() {
    ?>
    <div class="site-branding flex items-center">
        <?php
        if ( has_custom_logo() ) :
            the_custom_logo();
        else :
            ?>
            <div class="site-title-wrap">
                <h1 class="site-title text-xl md:text-2xl font-bold">
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500">
                        <?php bloginfo( 'name' ); ?>
                    </a>
                </h1>
                <?php
                $aqualuxe_description = get_bloginfo( 'description', 'display' );
                if ( $aqualuxe_description || is_customize_preview() ) :
                    ?>
                    <p class="site-description text-sm text-gray-600 dark:text-gray-400">
                        <?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div><!-- .site-branding -->
    <?php
}

/**
 * Display the primary navigation.
 */
function aqualuxe_primary_navigation() {
    if ( has_nav_menu( 'primary' ) ) :
        ?>
        <nav id="site-navigation" class="main-navigation hidden lg:block" aria-label="<?php esc_attr_e( 'Primary Navigation', 'aqualuxe' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'primary',
                    'menu_id'        => 'primary-menu',
                    'menu_class'     => 'primary-menu flex space-x-6',
                    'container'      => false,
                )
            );
            ?>
        </nav><!-- #site-navigation -->
        
        <button id="mobile-menu-toggle" class="mobile-menu-toggle lg:hidden flex items-center justify-center p-2 text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500" aria-controls="mobile-menu" aria-expanded="false">
            <span class="sr-only"><?php esc_html_e( 'Toggle menu', 'aqualuxe' ); ?></span>
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <div id="mobile-menu" class="mobile-menu fixed inset-0 z-50 bg-white dark:bg-dark-800 lg:hidden hidden">
            <div class="mobile-menu-header flex items-center justify-between p-4 border-b border-gray-200 dark:border-dark-700">
                <?php aqualuxe_site_branding(); ?>
                <button id="mobile-menu-close" class="mobile-menu-close flex items-center justify-center p-2 text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500">
                    <span class="sr-only"><?php esc_html_e( 'Close menu', 'aqualuxe' ); ?></span>
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="mobile-menu-content p-4">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'mobile',
                        'menu_id'        => 'mobile-menu',
                        'menu_class'     => 'mobile-menu-items',
                        'container'      => false,
                        'fallback_cb'    => function() {
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'primary',
                                    'menu_id'        => 'mobile-menu',
                                    'menu_class'     => 'mobile-menu-items',
                                    'container'      => false,
                                )
                            );
                        },
                    )
                );
                ?>
            </div>
        </div>
        <?php
    endif;
}

/**
 * Display the header search.
 */
function aqualuxe_header_search() {
    // Check if header search is enabled.
    $show_header_search = get_theme_mod( 'aqualuxe_show_header_search', true );
    if ( ! $show_header_search ) {
        return;
    }
    ?>
    <div class="header-search relative">
        <button id="header-search-toggle" class="header-search-toggle flex items-center justify-center p-2 text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500" aria-expanded="false" aria-controls="header-search-form">
            <span class="sr-only"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
        <div id="header-search-form" class="header-search-form absolute right-0 top-full mt-2 w-72 bg-white dark:bg-dark-700 rounded-lg shadow-lg p-4 hidden z-10">
            <?php get_search_form(); ?>
        </div>
    </div>
    <?php
}

/**
 * Display the header cart.
 */
function aqualuxe_header_cart() {
    // Check if WooCommerce is active.
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Check if header cart is enabled.
    $show_header_cart = get_theme_mod( 'aqualuxe_show_header_cart', true );
    if ( ! $show_header_cart ) {
        return;
    }
    ?>
    <div class="header-cart relative">
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-cart-link flex items-center justify-center p-2 text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500">
            <span class="sr-only"><?php esc_html_e( 'Cart', 'aqualuxe' ); ?></span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span class="cart-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                <?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?>
            </span>
        </a>
        <div class="header-cart-dropdown absolute right-0 top-full mt-2 w-80 bg-white dark:bg-dark-700 rounded-lg shadow-lg p-4 hidden z-10">
            <div class="widget_shopping_cart_content">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display the header wishlist.
 */
function aqualuxe_header_wishlist() {
    // Check if WooCommerce is active.
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Check if header wishlist is enabled.
    $show_header_wishlist = get_theme_mod( 'aqualuxe_show_header_wishlist', true );
    if ( ! $show_header_wishlist ) {
        return;
    }

    // Get the wishlist count.
    $wishlist_count = 0;
    if ( is_user_logged_in() ) {
        $wishlist = get_user_meta( get_current_user_id(), 'aqualuxe_wishlist', true );
        if ( is_array( $wishlist ) ) {
            $wishlist_count = count( $wishlist );
        }
    } else {
        $wishlist = isset( $_COOKIE['aqualuxe_wishlist'] ) ? json_decode( sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_wishlist'] ) ), true ) : [];
        if ( is_array( $wishlist ) ) {
            $wishlist_count = count( $wishlist );
        }
    }
    ?>
    <div class="header-wishlist">
        <a href="<?php echo esc_url( get_permalink( get_option( 'aqualuxe_wishlist_page_id' ) ) ); ?>" class="header-wishlist-link flex items-center justify-center p-2 text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500">
            <span class="sr-only"><?php esc_html_e( 'Wishlist', 'aqualuxe' ); ?></span>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
            </svg>
            <span class="wishlist-count absolute -top-1 -right-1 bg-primary-600 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                <?php echo esc_html( $wishlist_count ); ?>
            </span>
        </a>
    </div>
    <?php
}

/**
 * Display the dark mode toggle.
 */
function aqualuxe_dark_mode_toggle() {
    // Check if dark mode is enabled.
    $show_dark_mode = get_theme_mod( 'aqualuxe_show_dark_mode', true );
    if ( ! $show_dark_mode ) {
        return;
    }
    ?>
    <div class="dark-mode-toggle" x-data="darkMode">
        <button @click="toggleDarkMode" class="dark-mode-toggle-button flex items-center justify-center p-2 text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500" aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
            <svg x-show="!isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
            </svg>
            <svg x-show="isDarkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
            </svg>
        </button>
    </div>
    <?php
}

/**
 * Display the header contact information.
 */
function aqualuxe_header_contact_info() {
    // Check if header contact info is enabled.
    $show_header_contact = get_theme_mod( 'aqualuxe_show_header_contact', true );
    if ( ! $show_header_contact ) {
        return;
    }

    // Get the contact information.
    $phone = get_theme_mod( 'aqualuxe_contact_phone', '+1 (555) 123-4567' );
    $email = get_theme_mod( 'aqualuxe_contact_email', 'info@aqualuxe.example.com' );
    ?>
    <div class="header-contact-info flex items-center space-x-4 text-sm">
        <?php if ( $phone ) : ?>
            <div class="header-phone flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                </svg>
                <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="hover:text-primary-600">
                    <?php echo esc_html( $phone ); ?>
                </a>
            </div>
        <?php endif; ?>
        <?php if ( $email ) : ?>
            <div class="header-email flex items-center">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <a href="mailto:<?php echo esc_attr( $email ); ?>" class="hover:text-primary-600">
                    <?php echo esc_html( $email ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display the header social icons.
 */
function aqualuxe_header_social_icons() {
    // Check if header social icons are enabled.
    $show_header_social = get_theme_mod( 'aqualuxe_show_header_social', true );
    if ( ! $show_header_social ) {
        return;
    }

    // Get the social links.
    $facebook = get_theme_mod( 'aqualuxe_social_facebook', '#' );
    $twitter = get_theme_mod( 'aqualuxe_social_twitter', '#' );
    $instagram = get_theme_mod( 'aqualuxe_social_instagram', '#' );
    $youtube = get_theme_mod( 'aqualuxe_social_youtube', '#' );
    $linkedin = get_theme_mod( 'aqualuxe_social_linkedin', '#' );
    ?>
    <div class="header-social-icons flex items-center space-x-2">
        <?php if ( $facebook ) : ?>
            <a href="<?php echo esc_url( $facebook ); ?>" class="social-icon flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-dark-600 text-dark-600 dark:text-light-500 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white transition-colors" aria-label="<?php esc_attr_e( 'Facebook', 'aqualuxe' ); ?>" target="_blank" rel="noopener noreferrer">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"></path>
                </svg>
            </a>
        <?php endif; ?>
        <?php if ( $twitter ) : ?>
            <a href="<?php echo esc_url( $twitter ); ?>" class="social-icon flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-dark-600 text-dark-600 dark:text-light-500 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white transition-colors" aria-label="<?php esc_attr_e( 'Twitter', 'aqualuxe' ); ?>" target="_blank" rel="noopener noreferrer">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                </svg>
            </a>
        <?php endif; ?>
        <?php if ( $instagram ) : ?>
            <a href="<?php echo esc_url( $instagram ); ?>" class="social-icon flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-dark-600 text-dark-600 dark:text-light-500 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white transition-colors" aria-label="<?php esc_attr_e( 'Instagram', 'aqualuxe' ); ?>" target="_blank" rel="noopener noreferrer">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                </svg>
            </a>
        <?php endif; ?>
        <?php if ( $youtube ) : ?>
            <a href="<?php echo esc_url( $youtube ); ?>" class="social-icon flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-dark-600 text-dark-600 dark:text-light-500 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white transition-colors" aria-label="<?php esc_attr_e( 'YouTube', 'aqualuxe' ); ?>" target="_blank" rel="noopener noreferrer">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd"></path>
                </svg>
            </a>
        <?php endif; ?>
        <?php if ( $linkedin ) : ?>
            <a href="<?php echo esc_url( $linkedin ); ?>" class="social-icon flex items-center justify-center w-8 h-8 rounded-full bg-gray-100 dark:bg-dark-600 text-dark-600 dark:text-light-500 hover:bg-primary-600 hover:text-white dark:hover:bg-primary-600 dark:hover:text-white transition-colors" aria-label="<?php esc_attr_e( 'LinkedIn', 'aqualuxe' ); ?>" target="_blank" rel="noopener noreferrer">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                </svg>
            </a>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display the header account links.
 */
function aqualuxe_header_account_links() {
    // Check if WooCommerce is active.
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Check if header account links are enabled.
    $show_header_account = get_theme_mod( 'aqualuxe_show_header_account', true );
    if ( ! $show_header_account ) {
        return;
    }
    ?>
    <div class="header-account ml-4">
        <?php if ( is_user_logged_in() ) : ?>
            <div class="header-account-dropdown relative">
                <button id="header-account-toggle" class="header-account-toggle flex items-center text-sm hover:text-primary-600" aria-expanded="false" aria-controls="header-account-menu">
                    <span class="mr-1"><?php echo esc_html( wp_get_current_user()->display_name ); ?></span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="header-account-menu" class="header-account-menu absolute right-0 top-full mt-2 w-48 bg-white dark:bg-dark-700 rounded-lg shadow-lg p-2 hidden z-10">
                    <ul class="space-y-1">
                        <li>
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-dark-600">
                                <?php esc_html_e( 'Dashboard', 'aqualuxe' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>" class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-dark-600">
                                <?php esc_html_e( 'Orders', 'aqualuxe' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-address' ) ); ?>" class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-dark-600">
                                <?php esc_html_e( 'Addresses', 'aqualuxe' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'edit-account' ) ); ?>" class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-dark-600">
                                <?php esc_html_e( 'Account details', 'aqualuxe' ); ?>
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'customer-logout' ) ); ?>" class="block px-4 py-2 rounded-md hover:bg-gray-100 dark:hover:bg-dark-600">
                                <?php esc_html_e( 'Logout', 'aqualuxe' ); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php else : ?>
            <div class="header-account-links flex items-center space-x-4 text-sm">
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="hover:text-primary-600">
                    <?php esc_html_e( 'Login', 'aqualuxe' ); ?>
                </a>
                <span class="text-gray-400">|</span>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="hover:text-primary-600">
                    <?php esc_html_e( 'Register', 'aqualuxe' ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display the footer widgets.
 */
function aqualuxe_footer_widgets() {
    // Check if footer widgets are enabled.
    $show_footer_widgets = get_theme_mod( 'aqualuxe_show_footer_widgets', true );
    if ( ! $show_footer_widgets ) {
        return;
    }

    // Check if any footer widget area is active.
    if ( ! is_active_sidebar( 'footer-1' ) && ! is_active_sidebar( 'footer-2' ) && ! is_active_sidebar( 'footer-3' ) && ! is_active_sidebar( 'footer-4' ) ) {
        return;
    }
    ?>
    <div class="footer-widgets py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    </div>
                <?php endif; ?>
                <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    </div>
                <?php endif; ?>
                <?php if ( is_active_sidebar( 'footer-3' ) ) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar( 'footer-3' ); ?>
                    </div>
                <?php endif; ?>
                <?php if ( is_active_sidebar( 'footer-4' ) ) : ?>
                    <div class="footer-widget">
                        <?php dynamic_sidebar( 'footer-4' ); ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display the footer copyright.
 */
function aqualuxe_footer_copyright() {
    // Get the copyright text.
    $copyright_text = get_theme_mod( 'aqualuxe_copyright_text', '&copy; ' . date( 'Y' ) . ' ' . get_bloginfo( 'name' ) . '. All rights reserved.' );
    ?>
    <div class="footer-copyright py-4 text-center text-sm">
        <div class="container mx-auto px-4">
            <?php echo wp_kses_post( $copyright_text ); ?>
        </div>
    </div>
    <?php
}

/**
 * Display the footer menu.
 */
function aqualuxe_footer_menu() {
    if ( has_nav_menu( 'footer' ) ) :
        ?>
        <div class="footer-menu py-4">
            <div class="container mx-auto px-4">
                <nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer Navigation', 'aqualuxe' ); ?>">
                    <?php
                    wp_nav_menu(
                        array(
                            'theme_location' => 'footer',
                            'menu_id'        => 'footer-menu',
                            'menu_class'     => 'footer-menu flex flex-wrap justify-center space-x-4 text-sm',
                            'container'      => false,
                            'depth'          => 1,
                        )
                    );
                    ?>
                </nav>
            </div>
        </div>
        <?php
    endif;
}

/**
 * Display the footer newsletter.
 */
function aqualuxe_footer_newsletter() {
    // Check if footer newsletter is enabled.
    $show_footer_newsletter = get_theme_mod( 'aqualuxe_show_footer_newsletter', true );
    if ( ! $show_footer_newsletter ) {
        return;
    }

    // Get the newsletter settings.
    $newsletter_title = get_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to our newsletter', 'aqualuxe' ) );
    $newsletter_description = get_theme_mod( 'aqualuxe_newsletter_description', __( 'Get the latest news and updates from AquaLuxe.', 'aqualuxe' ) );
    ?>
    <div class="footer-newsletter py-12 bg-primary-600 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <?php if ( $newsletter_title ) : ?>
                    <h3 class="text-2xl md:text-3xl font-serif font-bold mb-4">
                        <?php echo esc_html( $newsletter_title ); ?>
                    </h3>
                <?php endif; ?>
                <?php if ( $newsletter_description ) : ?>
                    <p class="mb-6">
                        <?php echo esc_html( $newsletter_description ); ?>
                    </p>
                <?php endif; ?>
                <form class="newsletter-form flex flex-col sm:flex-row gap-4 justify-center" action="#" method="post">
                    <input type="email" name="email" class="newsletter-email w-full sm:w-auto flex-grow px-4 py-3 rounded-md text-dark-600" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required>
                    <button type="submit" class="newsletter-submit bg-accent-600 hover:bg-accent-700 text-dark-600 px-6 py-3 rounded-md font-medium transition-colors">
                        <?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display the page header.
 */
function aqualuxe_page_header() {
    if ( is_front_page() ) {
        return;
    }

    if ( is_singular() ) {
        return;
    }

    if ( is_archive() || is_search() || is_home() ) {
        ?>
        <header class="page-header mb-8">
            <?php
            if ( is_archive() ) {
                the_archive_title( '<h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500 mb-4">', '</h1>' );
                the_archive_description( '<div class="archive-description text-gray-600 dark:text-gray-400">', '</div>' );
            } elseif ( is_search() ) {
                ?>
                <h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500 mb-4">
                    <?php
                    /* translators: %s: search query. */
                    printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
                    ?>
                </h1>
                <?php
            } elseif ( is_home() ) {
                $blog_title = get_the_title( get_option( 'page_for_posts', true ) );
                ?>
                <h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500">
                    <?php echo esc_html( $blog_title ); ?>
                </h1>
                <?php
            }
            ?>
        </header><!-- .page-header -->
        <?php
    }
}

/**
 * Display the page title.
 */
function aqualuxe_page_title() {
    ?>
    <h1 class="entry-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500 mb-6">
        <?php the_title(); ?>
    </h1>
    <?php
}

/**
 * Display the page featured image.
 */
function aqualuxe_page_featured_image() {
    aqualuxe_post_thumbnail( 'aqualuxe-featured' );
}

/**
 * Display the post title.
 */
function aqualuxe_post_title() {
    if ( is_singular() ) :
        ?>
        <h1 class="entry-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500 mb-4">
            <?php the_title(); ?>
        </h1>
        <?php
    else :
        ?>
        <h2 class="entry-title text-xl md:text-2xl font-serif font-bold mb-2">
            <a href="<?php the_permalink(); ?>" class="text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500" rel="bookmark">
                <?php the_title(); ?>
            </a>
        </h2>
        <?php
    endif;
}

/**
 * Display the post tags.
 */
function aqualuxe_post_tags() {
    if ( 'post' !== get_post_type() ) {
        return;
    }

    $tags_list = get_the_tag_list( '', ', ' );
    if ( $tags_list ) {
        ?>
        <div class="entry-tags mb-6">
            <span class="tags-links text-sm text-gray-600 dark:text-gray-400">
                <span class="font-medium"><?php esc_html_e( 'Tags:', 'aqualuxe' ); ?></span> <?php echo $tags_list; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </span>
        </div>
        <?php
    }
}

/**
 * Display the post author bio.
 */
function aqualuxe_post_author_bio() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    // Check if author bio is enabled.
    $show_author_bio = get_theme_mod( 'aqualuxe_show_author_bio', true );
    if ( ! $show_author_bio ) {
        return;
    }

    $author_id = get_the_author_meta( 'ID' );
    $author_bio = get_the_author_meta( 'description' );
    if ( ! $author_bio ) {
        return;
    }
    ?>
    <div class="author-bio bg-gray-50 dark:bg-dark-700 rounded-lg p-6 mb-8">
        <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
            <div class="author-avatar flex-shrink-0">
                <?php echo get_avatar( $author_id, 96, '', '', array( 'class' => 'rounded-full' ) ); ?>
            </div>
            <div class="author-content">
                <h3 class="author-name text-xl font-serif font-bold text-dark-600 dark:text-light-500 mb-2">
                    <?php echo esc_html( get_the_author_meta( 'display_name' ) ); ?>
                </h3>
                <div class="author-description text-gray-600 dark:text-gray-400 mb-4">
                    <?php echo wp_kses_post( $author_bio ); ?>
                </div>
                <a href="<?php echo esc_url( get_author_posts_url( $author_id ) ); ?>" class="author-link text-sm font-medium text-primary-600 hover:text-primary-700">
                    <?php
                    /* translators: %s: author name */
                    printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), esc_html( get_the_author_meta( 'display_name' ) ) );
                    ?>
                </a>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display the post navigation.
 */
function aqualuxe_post_navigation() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    // Check if post navigation is enabled.
    $show_post_navigation = get_theme_mod( 'aqualuxe_show_post_navigation', true );
    if ( ! $show_post_navigation ) {
        return;
    }

    the_post_navigation(
        array(
            'prev_text' => '<span class="nav-subtitle text-sm text-gray-600 dark:text-gray-400">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title font-medium">%title</span>',
            'next_text' => '<span class="nav-subtitle text-sm text-gray-600 dark:text-gray-400">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title font-medium">%title</span>',
            'class'     => 'post-navigation flex flex-col md:flex-row justify-between mb-8 text-dark-600 dark:text-light-500',
        )
    );
}

/**
 * Display the post comments.
 */
function aqualuxe_post_comments() {
    // If comments are open or we have at least one comment, load up the comment template.
    if ( comments_open() || get_comments_number() ) {
        comments_template();
    }
}

/**
 * Display related posts.
 */
function aqualuxe_related_posts() {
    if ( ! is_singular( 'post' ) ) {
        return;
    }

    // Check if related posts are enabled.
    $show_related_posts = get_theme_mod( 'aqualuxe_show_related_posts', true );
    if ( ! $show_related_posts ) {
        return;
    }

    // Get the current post categories.
    $categories = get_the_category();
    if ( empty( $categories ) ) {
        return;
    }

    // Get the category IDs.
    $category_ids = array();
    foreach ( $categories as $category ) {
        $category_ids[] = $category->term_id;
    }

    // Query related posts.
    $related_posts = new WP_Query(
        array(
            'category__in'        => $category_ids,
            'post__not_in'        => array( get_the_ID() ),
            'posts_per_page'      => 3,
            'ignore_sticky_posts' => 1,
        )
    );

    if ( ! $related_posts->have_posts() ) {
        return;
    }
    ?>
    <div class="related-posts mt-12">
        <h3 class="related-posts-title text-2xl font-serif font-bold text-dark-600 dark:text-light-500 mb-6">
            <?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php
            while ( $related_posts->have_posts() ) :
                $related_posts->the_post();
                ?>
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'card bg-white dark:bg-dark-700 rounded-lg shadow-soft overflow-hidden' ); ?>>
                    <?php if ( has_post_thumbnail() ) : ?>
                        <a href="<?php the_permalink(); ?>" class="block">
                            <?php the_post_thumbnail( 'aqualuxe-card', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
                        </a>
                    <?php endif; ?>
                    <div class="card-content p-6">
                        <h4 class="entry-title text-lg font-serif font-bold mb-2">
                            <a href="<?php the_permalink(); ?>" class="text-dark-600 dark:text-light-500 hover:text-primary-600 dark:hover:text-primary-500" rel="bookmark">
                                <?php the_title(); ?>
                            </a>
                        </h4>
                        <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mb-4">
                            <?php echo esc_html( get_the_date() ); ?>
                        </div>
                        <div class="entry-summary text-gray-600 dark:text-gray-400">
                            <?php the_excerpt(); ?>
                        </div>
                    </div>
                </article>
                <?php
            endwhile;
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
}

/**
 * Display the archive title.
 */
function aqualuxe_archive_title() {
    the_archive_title( '<h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500 mb-4">', '</h1>' );
}

/**
 * Display the archive description.
 */
function aqualuxe_archive_description() {
    the_archive_description( '<div class="archive-description text-gray-600 dark:text-gray-400 mb-8">', '</div>' );
}

/**
 * Display the search title.
 */
function aqualuxe_search_title() {
    ?>
    <h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500 mb-4">
        <?php
        /* translators: %s: search query. */
        printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
        ?>
    </h1>
    <?php
}

/**
 * Display the search form.
 */
function aqualuxe_search_form() {
    ?>
    <div class="search-form-container mb-8">
        <?php get_search_form(); ?>
    </div>
    <?php
}

/**
 * Display the 404 title.
 */
function aqualuxe_404_title() {
    ?>
    <h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500 mb-4">
        <?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?>
    </h1>
    <?php
}

/**
 * Display the 404 content.
 */
function aqualuxe_404_content() {
    ?>
    <div class="page-content text-gray-600 dark:text-gray-400 mb-8">
        <p><?php esc_html_e( 'It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe' ); ?></p>
    </div>
    <?php
}

/**
 * Display the comments list.
 */
function aqualuxe_comments_list() {
    if ( have_comments() ) :
        ?>
        <h2 class="comments-title text-2xl font-serif font-bold text-dark-600 dark:text-light-500 mb-6">
            <?php
            $aqualuxe_comment_count = get_comments_number();
            if ( '1' === $aqualuxe_comment_count ) {
                printf(
                    /* translators: 1: title. */
                    esc_html__( 'One comment on &ldquo;%1$s&rdquo;', 'aqualuxe' ),
                    '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                );
            } else {
                printf(
                    /* translators: 1: comment count number, 2: title. */
                    esc_html( _nx( '%1$s comment on &ldquo;%2$s&rdquo;', '%1$s comments on &ldquo;%2$s&rdquo;', $aqualuxe_comment_count, 'comments title', 'aqualuxe' ) ),
                    number_format_i18n( $aqualuxe_comment_count ),
                    '<span>' . wp_kses_post( get_the_title() ) . '</span>'
                );
            }
            ?>
        </h2>

        <ol class="comment-list mb-8">
            <?php
            wp_list_comments(
                array(
                    'style'      => 'ol',
                    'short_ping' => true,
                )
            );
            ?>
        </ol>
        <?php
    endif;
}

/**
 * Display the comments navigation.
 */
function aqualuxe_comments_navigation() {
    if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
        ?>
        <nav class="comment-navigation mb-8" aria-label="<?php esc_attr_e( 'Comments navigation', 'aqualuxe' ); ?>">
            <div class="flex justify-between">
                <div class="nav-previous">
                    <?php previous_comments_link( esc_html__( '&larr; Older Comments', 'aqualuxe' ) ); ?>
                </div>
                <div class="nav-next">
                    <?php next_comments_link( esc_html__( 'Newer Comments &rarr;', 'aqualuxe' ) ); ?>
                </div>
            </div>
        </nav>
        <?php
    endif;
}

/**
 * Display the comments form.
 */
function aqualuxe_comments_form() {
    if ( comments_open() ) :
        comment_form(
            array(
                'title_reply'         => esc_html__( 'Leave a Comment', 'aqualuxe' ),
                'title_reply_before'  => '<h3 id="reply-title" class="comment-reply-title text-2xl font-serif font-bold text-dark-600 dark:text-light-500 mb-6">',
                'title_reply_after'   => '</h3>',
                'comment_notes_before' => '<p class="comment-notes text-gray-600 dark:text-gray-400 mb-4">' . esc_html__( 'Your email address will not be published. Required fields are marked *', 'aqualuxe' ) . '</p>',
                'class_submit'        => 'submit bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-md font-medium transition-colors',
            )
        );
    endif;
}

/**
 * Display the shop title.
 */
function aqualuxe_shop_title() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    if ( is_shop() ) {
        ?>
        <h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500 mb-4">
            <?php woocommerce_page_title(); ?>
        </h1>
        <?php
    } elseif ( is_product_category() || is_product_tag() ) {
        ?>
        <h1 class="page-title text-3xl md:text-4xl lg:text-5xl font-serif font-bold text-dark-600 dark:text-light-500 mb-4">
            <?php woocommerce_page_title(); ?>
        </h1>
        <?php
    }
}

/**
 * Display the shop description.
 */
function aqualuxe_shop_description() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    if ( is_shop() && get_option( 'woocommerce_shop_page_id' ) ) {
        $shop_page_id = get_option( 'woocommerce_shop_page_id' );
        $shop_page = get_post( $shop_page_id );
        if ( $shop_page && ! empty( $shop_page->post_content ) ) {
            ?>
            <div class="shop-description text-gray-600 dark:text-gray-400 mb-8">
                <?php echo apply_filters( 'the_content', $shop_page->post_content ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php
        }
    } elseif ( is_product_category() || is_product_tag() ) {
        $term_description = wc_format_content( term_description() );
        if ( $term_description ) {
            ?>
            <div class="term-description text-gray-600 dark:text-gray-400 mb-8">
                <?php echo $term_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
            <?php
        }
    }
}

/**
 * Display the shop filters.
 */
function aqualuxe_shop_filters() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        return;
    }

    // Check if shop filters are enabled.
    $show_shop_filters = get_theme_mod( 'aqualuxe_show_shop_filters', true );
    if ( ! $show_shop_filters ) {
        return;
    }

    if ( is_shop() || is_product_category() || is_product_tag() ) {
        ?>
        <div class="shop-filters flex flex-col md:flex-row justify-between items-start md:items-center mb-8">
            <div class="shop-result-count mb-4 md:mb-0 text-gray-600 dark:text-gray-400">
                <?php woocommerce_result_count(); ?>
            </div>
            <div class="shop-ordering">
                <?php woocommerce_catalog_ordering(); ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Display the product gallery.
 */
function aqualuxe_product_gallery() {
    if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
        return;
    }

    woocommerce_show_product_images();
}

/**
 * Display the product summary.
 */
function aqualuxe_product_summary() {
    if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
        return;
    }

    woocommerce_template_single_title();
    woocommerce_template_single_rating();
    woocommerce_template_single_price();
    woocommerce_template_single_excerpt();
    woocommerce_template_single_add_to_cart();
    woocommerce_template_single_meta();
    woocommerce_template_single_sharing();
}

/**
 * Display the product tabs.
 */
function aqualuxe_product_tabs() {
    if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
        return;
    }

    woocommerce_output_product_data_tabs();
}

/**
 * Display related products.
 */
function aqualuxe_related_products() {
    if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
        return;
    }

    // Check if related products are enabled.
    $show_related_products = get_theme_mod( 'aqualuxe_show_related_products', true );
    if ( ! $show_related_products ) {
        return;
    }

    woocommerce_related_products();
}

/**
 * Display upsell products.
 */
function aqualuxe_upsell_products() {
    if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
        return;
    }

    // Check if upsell products are enabled.
    $show_upsell_products = get_theme_mod( 'aqualuxe_show_upsell_products', true );
    if ( ! $show_upsell_products ) {
        return;
    }

    woocommerce_upsell_display();
}

/**
 * Display product badges.
 */
function aqualuxe_product_badges() {
    if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
        return;
    }

    global $product;

    // Sale badge.
    if ( $product->is_on_sale() ) {
        echo '<span class="product-badge sale-badge bg-accent-600 text-dark-600 text-xs font-medium px-2 py-1 rounded-md absolute top-4 left-4 z-10">' . esc_html__( 'Sale', 'aqualuxe' ) . '</span>';
    }

    // New badge.
    $new_days = 14; // Number of days to consider a product as new.
    $product_date = strtotime( $product->get_date_created() );
    $now = time();
    $days_diff = floor( ( $now - $product_date ) / ( 60 * 60 * 24 ) );

    if ( $days_diff < $new_days ) {
        echo '<span class="product-badge new-badge bg-primary-600 text-white text-xs font-medium px-2 py-1 rounded-md absolute top-4 right-4 z-10">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
    }

    // Out of stock badge.
    if ( ! $product->is_in_stock() ) {
        echo '<span class="product-badge out-of-stock-badge bg-gray-600 text-white text-xs font-medium px-2 py-1 rounded-md absolute top-4 left-4 z-10">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
    }
}

/**
 * Display product meta.
 */
function aqualuxe_product_meta() {
    if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
        return;
    }

    woocommerce_template_single_meta();
}

/**
 * Display product sharing.
 */
function aqualuxe_product_sharing() {
    if ( ! class_exists( 'WooCommerce' ) || ! is_product() ) {
        return;
    }

    // Check if product sharing is enabled.
    $show_product_sharing = get_theme_mod( 'aqualuxe_show_product_sharing', true );
    if ( ! $show_product_sharing ) {
        return;
    }

    woocommerce_template_single_sharing();
}