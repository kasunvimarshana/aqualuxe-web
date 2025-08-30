<?php
/**
 * Template tags for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display header top section
 *
 * @return void
 */
function aqualuxe_header_top() {
    ?>
    <div class="aqualuxe-header-top">
        <div class="container">
            <div class="aqualuxe-header-top-inner">
                <?php do_action('aqualuxe_header_top'); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display header main section
 *
 * @return void
 */
function aqualuxe_header_main() {
    ?>
    <div class="aqualuxe-header-main">
        <div class="container">
            <div class="aqualuxe-header-main-inner">
                <?php do_action('aqualuxe_header_main'); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display header bottom section
 *
 * @return void
 */
function aqualuxe_header_bottom() {
    ?>
    <div class="aqualuxe-header-bottom">
        <div class="container">
            <div class="aqualuxe-header-bottom-inner">
                <?php do_action('aqualuxe_header_bottom'); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display header top left section
 *
 * @return void
 */
function aqualuxe_header_top_left() {
    ?>
    <div class="aqualuxe-header-top-left">
        <?php do_action('aqualuxe_header_top_left'); ?>
    </div>
    <?php
}

/**
 * Display header top right section
 *
 * @return void
 */
function aqualuxe_header_top_right() {
    ?>
    <div class="aqualuxe-header-top-right">
        <?php do_action('aqualuxe_header_top_right'); ?>
    </div>
    <?php
}

/**
 * Display header contact info
 *
 * @return void
 */
function aqualuxe_header_contact_info() {
    $phone = aqualuxe_get_option('contact_phone');
    $email = aqualuxe_get_option('contact_email');
    
    if (empty($phone) && empty($email)) {
        return;
    }
    ?>
    <div class="aqualuxe-header-contact-info">
        <?php if (!empty($phone)) : ?>
            <div class="aqualuxe-header-contact-phone">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/></svg>
                <a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($email)) : ?>
            <div class="aqualuxe-header-contact-email">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/></svg>
                <a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display header language switcher
 *
 * @return void
 */
function aqualuxe_header_language_switcher() {
    aqualuxe_language_switcher();
}

/**
 * Display header currency switcher
 *
 * @return void
 */
function aqualuxe_header_currency_switcher() {
    aqualuxe_currency_switcher();
}

/**
 * Display header dark mode toggle
 *
 * @return void
 */
function aqualuxe_header_dark_mode_toggle() {
    aqualuxe_dark_mode_toggle();
}

/**
 * Display header secondary menu
 *
 * @return void
 */
function aqualuxe_header_secondary_menu() {
    aqualuxe_navigation('secondary', array(
        'container_class' => 'aqualuxe-header-secondary-menu',
        'menu_class' => 'aqualuxe-header-secondary-menu-list',
    ));
}

/**
 * Display header logo
 *
 * @return void
 */
function aqualuxe_header_logo() {
    ?>
    <div class="aqualuxe-header-logo">
        <?php aqualuxe_site_logo(); ?>
    </div>
    <?php
}

/**
 * Display header navigation
 *
 * @return void
 */
function aqualuxe_header_navigation() {
    ?>
    <div class="aqualuxe-header-navigation">
        <?php aqualuxe_navigation('primary'); ?>
    </div>
    <?php
}

/**
 * Display header actions
 *
 * @return void
 */
function aqualuxe_header_actions() {
    ?>
    <div class="aqualuxe-header-actions">
        <?php do_action('aqualuxe_header_actions'); ?>
    </div>
    <?php
}

/**
 * Display header search
 *
 * @return void
 */
function aqualuxe_header_search() {
    ?>
    <div class="aqualuxe-header-search">
        <button class="aqualuxe-header-search-toggle" aria-label="<?php esc_attr_e('Toggle search', 'aqualuxe'); ?>">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"></path></svg>
        </button>
        
        <div class="aqualuxe-header-search-dropdown">
            <?php aqualuxe_search_form(); ?>
        </div>
    </div>
    <?php
}

/**
 * Display header account
 *
 * @return void
 */
function aqualuxe_header_account() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    ?>
    <div class="aqualuxe-header-account">
        <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="aqualuxe-header-account-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 3c1.66 0 3 1.34 3 3s-1.34 3-3 3-3-1.34-3-3 1.34-3 3-3zm0 14.2c-2.5 0-4.71-1.28-6-3.22.03-1.99 4-3.08 6-3.08 1.99 0 5.97 1.09 6 3.08-1.29 1.94-3.5 3.22-6 3.22z"></path></svg>
            <span class="aqualuxe-header-account-text"><?php esc_html_e('Account', 'aqualuxe'); ?></span>
        </a>
        
        <?php if (!is_user_logged_in()) : ?>
            <div class="aqualuxe-header-account-dropdown">
                <?php woocommerce_login_form(array(
                    'redirect' => wc_get_account_endpoint_url('dashboard'),
                )); ?>
                
                <div class="aqualuxe-header-account-register">
                    <p><?php esc_html_e('Don\'t have an account?', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="button"><?php esc_html_e('Register', 'aqualuxe'); ?></a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
}

/**
 * Display header wishlist
 *
 * @return void
 */
function aqualuxe_header_wishlist() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Check if wishlist is enabled
    $wishlist_enabled = aqualuxe_get_option('wishlist_enabled', true);
    
    if (!$wishlist_enabled) {
        return;
    }
    
    // Get wishlist URL
    $wishlist_url = '#';
    
    // Check if YITH WooCommerce Wishlist is active
    if (class_exists('YITH_WCWL')) {
        $wishlist_url = YITH_WCWL()->get_wishlist_url();
    }
    
    // Get wishlist count
    $wishlist_count = 0;
    
    // Check if YITH WooCommerce Wishlist is active
    if (class_exists('YITH_WCWL')) {
        $wishlist_count = YITH_WCWL()->count_products();
    }
    ?>
    <div class="aqualuxe-header-wishlist">
        <a href="<?php echo esc_url($wishlist_url); ?>" class="aqualuxe-header-wishlist-link">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"></path></svg>
            <span class="aqualuxe-header-wishlist-count"><?php echo esc_html($wishlist_count); ?></span>
        </a>
    </div>
    <?php
}

/**
 * Display header cart
 *
 * @return void
 */
function aqualuxe_header_cart() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    aqualuxe_mini_cart();
}

/**
 * Display header categories menu
 *
 * @return void
 */
function aqualuxe_header_categories_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    // Check if categories menu is enabled
    $categories_menu_enabled = aqualuxe_get_option('categories_menu_enabled', true);
    
    if (!$categories_menu_enabled) {
        return;
    }
    
    // Get product categories
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0,
    ));
    
    if (empty($categories)) {
        return;
    }
    ?>
    <div class="aqualuxe-header-categories">
        <button class="aqualuxe-header-categories-toggle">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"></path></svg>
            <span><?php esc_html_e('Categories', 'aqualuxe'); ?></span>
        </button>
        
        <div class="aqualuxe-header-categories-dropdown">
            <ul class="aqualuxe-header-categories-list">
                <?php foreach ($categories as $category) : ?>
                    <li class="aqualuxe-header-categories-item">
                        <a href="<?php echo esc_url(get_term_link($category)); ?>" class="aqualuxe-header-categories-link">
                            <?php echo esc_html($category->name); ?>
                        </a>
                        
                        <?php
                        // Get subcategories
                        $subcategories = get_terms(array(
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                            'parent' => $category->term_id,
                        ));
                        
                        if (!empty($subcategories)) :
                        ?>
                            <ul class="aqualuxe-header-subcategories-list">
                                <?php foreach ($subcategories as $subcategory) : ?>
                                    <li class="aqualuxe-header-subcategories-item">
                                        <a href="<?php echo esc_url(get_term_link($subcategory)); ?>" class="aqualuxe-header-subcategories-link">
                                            <?php echo esc_html($subcategory->name); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php
}

/**
 * Display footer widgets
 *
 * @return void
 */
function aqualuxe_footer_widgets() {
    // Check if any footer widget area has widgets
    if (!is_active_sidebar('footer-1') && !is_active_sidebar('footer-2') && !is_active_sidebar('footer-3') && !is_active_sidebar('footer-4')) {
        return;
    }
    ?>
    <div class="aqualuxe-footer-widgets">
        <div class="container">
            <div class="aqualuxe-footer-widgets-inner">
                <?php do_action('aqualuxe_footer_widgets'); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display footer widgets column 1
 *
 * @return void
 */
function aqualuxe_footer_widgets_column_1() {
    if (!is_active_sidebar('footer-1')) {
        return;
    }
    ?>
    <div class="aqualuxe-footer-widgets-column">
        <?php dynamic_sidebar('footer-1'); ?>
    </div>
    <?php
}

/**
 * Display footer widgets column 2
 *
 * @return void
 */
function aqualuxe_footer_widgets_column_2() {
    if (!is_active_sidebar('footer-2')) {
        return;
    }
    ?>
    <div class="aqualuxe-footer-widgets-column">
        <?php dynamic_sidebar('footer-2'); ?>
    </div>
    <?php
}

/**
 * Display footer widgets column 3
 *
 * @return void
 */
function aqualuxe_footer_widgets_column_3() {
    if (!is_active_sidebar('footer-3')) {
        return;
    }
    ?>
    <div class="aqualuxe-footer-widgets-column">
        <?php dynamic_sidebar('footer-3'); ?>
    </div>
    <?php
}

/**
 * Display footer widgets column 4
 *
 * @return void
 */
function aqualuxe_footer_widgets_column_4() {
    if (!is_active_sidebar('footer-4')) {
        return;
    }
    ?>
    <div class="aqualuxe-footer-widgets-column">
        <?php dynamic_sidebar('footer-4'); ?>
    </div>
    <?php
}

/**
 * Display footer main
 *
 * @return void
 */
function aqualuxe_footer_main() {
    ?>
    <div class="aqualuxe-footer-main">
        <div class="container">
            <div class="aqualuxe-footer-main-inner">
                <?php do_action('aqualuxe_footer_main'); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display footer logo
 *
 * @return void
 */
function aqualuxe_footer_logo() {
    ?>
    <div class="aqualuxe-footer-logo">
        <?php aqualuxe_site_logo(array('class' => 'footer-logo')); ?>
    </div>
    <?php
}

/**
 * Display footer menu
 *
 * @return void
 */
function aqualuxe_footer_menu() {
    aqualuxe_navigation('footer', array(
        'container_class' => 'aqualuxe-footer-menu',
        'menu_class' => 'aqualuxe-footer-menu-list',
    ));
}

/**
 * Display footer social
 *
 * @return void
 */
function aqualuxe_footer_social() {
    $facebook = aqualuxe_get_option('social_facebook');
    $twitter = aqualuxe_get_option('social_twitter');
    $instagram = aqualuxe_get_option('social_instagram');
    $youtube = aqualuxe_get_option('social_youtube');
    $linkedin = aqualuxe_get_option('social_linkedin');
    $pinterest = aqualuxe_get_option('social_pinterest');
    
    if (empty($facebook) && empty($twitter) && empty($instagram) && empty($youtube) && empty($linkedin) && empty($pinterest)) {
        return;
    }
    ?>
    <div class="aqualuxe-footer-social">
        <ul class="aqualuxe-footer-social-list">
            <?php if (!empty($facebook)) : ?>
                <li class="aqualuxe-footer-social-item">
                    <a href="<?php echo esc_url($facebook); ?>" class="aqualuxe-footer-social-link" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z"></path></svg>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php if (!empty($twitter)) : ?>
                <li class="aqualuxe-footer-social-item">
                    <a href="<?php echo esc_url($twitter); ?>" class="aqualuxe-footer-social-link" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"></path></svg>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php if (!empty($instagram)) : ?>
                <li class="aqualuxe-footer-social-item">
                    <a href="<?php echo esc_url($instagram); ?>" class="aqualuxe-footer-social-link" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php if (!empty($youtube)) : ?>
                <li class="aqualuxe-footer-social-item">
                    <a href="<?php echo esc_url($youtube); ?>" class="aqualuxe-footer-social-link" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M21.582,6.186c-0.23-0.86-0.908-1.538-1.768-1.768C18.254,4,12,4,12,4S5.746,4,4.186,4.418 c-0.86,0.23-1.538,0.908-1.768,1.768C2,7.746,2,12,2,12s0,4.254,0.418,5.814c0.23,0.86,0.908,1.538,1.768,1.768 C5.746,20,12,20,12,20s6.254,0,7.814-0.418c0.861-0.23,1.538-0.908,1.768-1.768C22,16.254,22,12,22,12S22,7.746,21.582,6.186z M10,15.464V8.536L16,12L10,15.464z"></path></svg>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php if (!empty($linkedin)) : ?>
                <li class="aqualuxe-footer-social-item">
                    <a href="<?php echo esc_url($linkedin); ?>" class="aqualuxe-footer-social-link" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"></path></svg>
                    </a>
                </li>
            <?php endif; ?>
            
            <?php if (!empty($pinterest)) : ?>
                <li class="aqualuxe-footer-social-item">
                    <a href="<?php echo esc_url($pinterest); ?>" class="aqualuxe-footer-social-link" target="_blank" rel="noopener noreferrer">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M9.04 21.54c.96.29 1.93.46 2.96.46a10 10 0 0 0 10-10A10 10 0 0 0 12 2 10 10 0 0 0 2 12c0 4.25 2.67 7.9 6.44 9.34-.09-.78-.18-2.07 0-2.96l1.15-4.94s-.29-.58-.29-1.5c0-1.38.86-2.41 1.84-2.41.86 0 1.26.63 1.26 1.44 0 .86-.57 2.09-.86 3.27-.17.98.52 1.84 1.52 1.84 1.78 0 3.16-1.9 3.16-4.58 0-2.4-1.72-4.04-4.19-4.04-2.82 0-4.48 2.1-4.48 4.31 0 .86.28 1.73.74 2.3.09.06.09.14.06.29l-.29 1.09c0 .17-.11.23-.28.11-1.28-.56-2.02-2.38-2.02-3.85 0-3.16 2.24-6.03 6.56-6.03 3.44 0 6.12 2.47 6.12 5.75 0 3.44-2.13 6.2-5.18 6.2-.97 0-1.92-.52-2.26-1.13l-.67 2.37c-.23.86-.86 2.01-1.29 2.7v-.03z"></path></svg>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
    <?php
}

/**
 * Display footer bottom
 *
 * @return void
 */
function aqualuxe_footer_bottom() {
    ?>
    <div class="aqualuxe-footer-bottom">
        <div class="container">
            <div class="aqualuxe-footer-bottom-inner">
                <?php do_action('aqualuxe_footer_bottom'); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display footer copyright
 *
 * @return void
 */
function aqualuxe_footer_copyright() {
    $copyright = aqualuxe_get_option('copyright_text');
    
    if (empty($copyright)) {
        $copyright = sprintf(
            /* translators: %1$s: Current year, %2$s: Site name */
            __('&copy; %1$s %2$s. All rights reserved.', 'aqualuxe'),
            date('Y'),
            get_bloginfo('name')
        );
    }
    ?>
    <div class="aqualuxe-footer-copyright">
        <?php echo wp_kses_post($copyright); ?>
    </div>
    <?php
}

/**
 * Display footer payment icons
 *
 * @return void
 */
function aqualuxe_footer_payment_icons() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $payment_icons = aqualuxe_get_option('payment_icons', array());
    
    if (empty($payment_icons)) {
        return;
    }
    ?>
    <div class="aqualuxe-footer-payment-icons">
        <ul class="aqualuxe-footer-payment-icons-list">
            <?php foreach ($payment_icons as $icon) : ?>
                <li class="aqualuxe-footer-payment-icons-item">
                    <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . 'images/payment-icons/' . $icon . '.svg'); ?>" alt="<?php echo esc_attr($icon); ?>" class="aqualuxe-footer-payment-icons-image" />
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php
}

/**
 * Display page header
 *
 * @return void
 */
function aqualuxe_page_header() {
    if (is_front_page()) {
        return;
    }
    
    $title = '';
    $description = '';
    
    if (is_home()) {
        $title = get_the_title(get_option('page_for_posts'));
    } elseif (is_archive()) {
        $title = get_the_archive_title();
        $description = get_the_archive_description();
    } elseif (is_search()) {
        $title = sprintf(
            /* translators: %s: Search query */
            __('Search results for: %s', 'aqualuxe'),
            get_search_query()
        );
    } elseif (is_404()) {
        $title = __('Page not found', 'aqualuxe');
    } elseif (is_singular()) {
        $title = get_the_title();
    }
    
    if (empty($title)) {
        return;
    }
    ?>
    <header class="aqualuxe-page-header">
        <div class="container">
            <h1 class="aqualuxe-page-title"><?php echo wp_kses_post($title); ?></h1>
            
            <?php if (!empty($description)) : ?>
                <div class="aqualuxe-page-description">
                    <?php echo wp_kses_post($description); ?>
                </div>
            <?php endif; ?>
        </div>
    </header>
    <?php
}

/**
 * Display homepage hero
 *
 * @return void
 */
function aqualuxe_homepage_hero() {
    $hero_enabled = aqualuxe_get_option('homepage_hero_enabled', true);
    
    if (!$hero_enabled) {
        return;
    }
    
    $hero_title = aqualuxe_get_option('homepage_hero_title', __('Bringing elegance to aquatic life – globally.', 'aqualuxe'));
    $hero_description = aqualuxe_get_option('homepage_hero_description', __('Discover our premium collection of rare fish species, aquatic plants, and high-quality equipment.', 'aqualuxe'));
    $hero_button_text = aqualuxe_get_option('homepage_hero_button_text', __('Shop Now', 'aqualuxe'));
    $hero_button_url = aqualuxe_get_option('homepage_hero_button_url', '#');
    $hero_image = aqualuxe_get_option('homepage_hero_image');
    
    if (empty($hero_image)) {
        $hero_image = AQUALUXE_ASSETS_URI . 'images/hero.jpg';
    }
    ?>
    <section class="aqualuxe-homepage-hero" style="background-image: url('<?php echo esc_url($hero_image); ?>');">
        <div class="container">
            <div class="aqualuxe-homepage-hero-content">
                <?php if (!empty($hero_title)) : ?>
                    <h2 class="aqualuxe-homepage-hero-title"><?php echo esc_html($hero_title); ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($hero_description)) : ?>
                    <div class="aqualuxe-homepage-hero-description">
                        <?php echo wp_kses_post($hero_description); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($hero_button_text) && !empty($hero_button_url)) : ?>
                    <div class="aqualuxe-homepage-hero-button">
                        <a href="<?php echo esc_url($hero_button_url); ?>" class="button button-primary"><?php echo esc_html($hero_button_text); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display homepage featured products
 *
 * @return void
 */
function aqualuxe_homepage_featured_products() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $featured_products_enabled = aqualuxe_get_option('homepage_featured_products_enabled', true);
    
    if (!$featured_products_enabled) {
        return;
    }
    
    $featured_products_title = aqualuxe_get_option('homepage_featured_products_title', __('Featured Products', 'aqualuxe'));
    $featured_products_description = aqualuxe_get_option('homepage_featured_products_description', __('Explore our handpicked selection of premium aquatic products.', 'aqualuxe'));
    $featured_products_count = aqualuxe_get_option('homepage_featured_products_count', 4);
    ?>
    <section class="aqualuxe-homepage-featured-products">
        <div class="container">
            <div class="aqualuxe-section-header">
                <?php if (!empty($featured_products_title)) : ?>
                    <h2 class="aqualuxe-section-title"><?php echo esc_html($featured_products_title); ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($featured_products_description)) : ?>
                    <div class="aqualuxe-section-description">
                        <?php echo wp_kses_post($featured_products_description); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="aqualuxe-homepage-featured-products-content">
                <?php
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => $featured_products_count,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_visibility',
                            'field' => 'name',
                            'terms' => 'featured',
                            'operator' => 'IN',
                        ),
                    ),
                );
                
                $featured_products = new WP_Query($args);
                
                if ($featured_products->have_posts()) {
                    woocommerce_product_loop_start();
                    
                    while ($featured_products->have_posts()) {
                        $featured_products->the_post();
                        wc_get_template_part('content', 'product');
                    }
                    
                    woocommerce_product_loop_end();
                    wp_reset_postdata();
                } else {
                    echo '<p>' . esc_html__('No featured products found.', 'aqualuxe') . '</p>';
                }
                ?>
            </div>
            
            <div class="aqualuxe-homepage-featured-products-button">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="button button-outline"><?php esc_html_e('View All Products', 'aqualuxe'); ?></a>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display homepage categories
 *
 * @return void
 */
function aqualuxe_homepage_categories() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $categories_enabled = aqualuxe_get_option('homepage_categories_enabled', true);
    
    if (!$categories_enabled) {
        return;
    }
    
    $categories_title = aqualuxe_get_option('homepage_categories_title', __('Shop by Category', 'aqualuxe'));
    $categories_description = aqualuxe_get_option('homepage_categories_description', __('Browse our wide range of aquatic categories.', 'aqualuxe'));
    $categories_count = aqualuxe_get_option('homepage_categories_count', 4);
    
    $categories = get_terms(array(
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0,
        'number' => $categories_count,
    ));
    
    if (empty($categories)) {
        return;
    }
    ?>
    <section class="aqualuxe-homepage-categories">
        <div class="container">
            <div class="aqualuxe-section-header">
                <?php if (!empty($categories_title)) : ?>
                    <h2 class="aqualuxe-section-title"><?php echo esc_html($categories_title); ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($categories_description)) : ?>
                    <div class="aqualuxe-section-description">
                        <?php echo wp_kses_post($categories_description); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="aqualuxe-homepage-categories-content">
                <div class="aqualuxe-homepage-categories-grid">
                    <?php foreach ($categories as $category) : ?>
                        <div class="aqualuxe-homepage-category">
                            <a href="<?php echo esc_url(get_term_link($category)); ?>" class="aqualuxe-homepage-category-link">
                                <?php
                                $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                                $image = wp_get_attachment_image_src($thumbnail_id, 'medium');
                                
                                if ($image) {
                                    echo '<img src="' . esc_url($image[0]) . '" alt="' . esc_attr($category->name) . '" class="aqualuxe-homepage-category-image" />';
                                }
                                ?>
                                
                                <div class="aqualuxe-homepage-category-content">
                                    <h3 class="aqualuxe-homepage-category-title"><?php echo esc_html($category->name); ?></h3>
                                    
                                    <div class="aqualuxe-homepage-category-count">
                                        <?php
                                        echo sprintf(
                                            /* translators: %d: Product count */
                                            _n('%d product', '%d products', $category->count, 'aqualuxe'),
                                            $category->count
                                        );
                                        ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display homepage services
 *
 * @return void
 */
function aqualuxe_homepage_services() {
    $services_enabled = aqualuxe_get_option('homepage_services_enabled', true);
    
    if (!$services_enabled) {
        return;
    }
    
    $services_title = aqualuxe_get_option('homepage_services_title', __('Our Services', 'aqualuxe'));
    $services_description = aqualuxe_get_option('homepage_services_description', __('Professional aquatic services for your needs.', 'aqualuxe'));
    $services = aqualuxe_get_option('homepage_services', array());
    
    if (empty($services)) {
        // Default services
        $services = array(
            array(
                'title' => __('Aquarium Design', 'aqualuxe'),
                'description' => __('Custom aquarium design for homes, offices, and commercial spaces.', 'aqualuxe'),
                'icon' => 'design',
                'url' => '#',
            ),
            array(
                'title' => __('Maintenance', 'aqualuxe'),
                'description' => __('Regular maintenance services to keep your aquarium in perfect condition.', 'aqualuxe'),
                'icon' => 'maintenance',
                'url' => '#',
            ),
            array(
                'title' => __('Consultation', 'aqualuxe'),
                'description' => __('Expert consultation for aquarium setup, fish selection, and care.', 'aqualuxe'),
                'icon' => 'consultation',
                'url' => '#',
            ),
            array(
                'title' => __('Installation', 'aqualuxe'),
                'description' => __('Professional installation of aquariums and related equipment.', 'aqualuxe'),
                'icon' => 'installation',
                'url' => '#',
            ),
        );
    }
    ?>
    <section class="aqualuxe-homepage-services">
        <div class="container">
            <div class="aqualuxe-section-header">
                <?php if (!empty($services_title)) : ?>
                    <h2 class="aqualuxe-section-title"><?php echo esc_html($services_title); ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($services_description)) : ?>
                    <div class="aqualuxe-section-description">
                        <?php echo wp_kses_post($services_description); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="aqualuxe-homepage-services-content">
                <div class="aqualuxe-homepage-services-grid">
                    <?php foreach ($services as $service) : ?>
                        <div class="aqualuxe-homepage-service">
                            <?php if (!empty($service['icon'])) : ?>
                                <div class="aqualuxe-homepage-service-icon">
                                    <?php echo aqualuxe_get_icon($service['icon']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="aqualuxe-homepage-service-content">
                                <?php if (!empty($service['title'])) : ?>
                                    <h3 class="aqualuxe-homepage-service-title"><?php echo esc_html($service['title']); ?></h3>
                                <?php endif; ?>
                                
                                <?php if (!empty($service['description'])) : ?>
                                    <div class="aqualuxe-homepage-service-description">
                                        <?php echo wp_kses_post($service['description']); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($service['url'])) : ?>
                                    <div class="aqualuxe-homepage-service-button">
                                        <a href="<?php echo esc_url($service['url']); ?>" class="button button-text"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display homepage testimonials
 *
 * @return void
 */
function aqualuxe_homepage_testimonials() {
    $testimonials_enabled = aqualuxe_get_option('homepage_testimonials_enabled', true);
    
    if (!$testimonials_enabled) {
        return;
    }
    
    $testimonials_title = aqualuxe_get_option('homepage_testimonials_title', __('What Our Customers Say', 'aqualuxe'));
    $testimonials_description = aqualuxe_get_option('homepage_testimonials_description', __('Read testimonials from our satisfied customers.', 'aqualuxe'));
    $testimonials = aqualuxe_get_option('homepage_testimonials', array());
    
    if (empty($testimonials)) {
        // Default testimonials
        $testimonials = array(
            array(
                'name' => __('John Doe', 'aqualuxe'),
                'position' => __('Aquarium Enthusiast', 'aqualuxe'),
                'content' => __('AquaLuxe has the best selection of rare fish species I\'ve ever seen. Their customer service is exceptional, and the fish arrived in perfect condition.', 'aqualuxe'),
                'image' => '',
            ),
            array(
                'name' => __('Jane Smith', 'aqualuxe'),
                'position' => __('Professional Aquascaper', 'aqualuxe'),
                'content' => __('I\'ve been using AquaLuxe for all my aquascaping needs. Their plants are always healthy, and their equipment is top-notch. Highly recommended!', 'aqualuxe'),
                'image' => '',
            ),
            array(
                'name' => __('Mike Johnson', 'aqualuxe'),
                'position' => __('Restaurant Owner', 'aqualuxe'),
                'content' => __('AquaLuxe designed and installed a stunning aquarium for our restaurant. Our customers love it, and their maintenance service keeps it looking perfect.', 'aqualuxe'),
                'image' => '',
            ),
        );
    }
    ?>
    <section class="aqualuxe-homepage-testimonials">
        <div class="container">
            <div class="aqualuxe-section-header">
                <?php if (!empty($testimonials_title)) : ?>
                    <h2 class="aqualuxe-section-title"><?php echo esc_html($testimonials_title); ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($testimonials_description)) : ?>
                    <div class="aqualuxe-section-description">
                        <?php echo wp_kses_post($testimonials_description); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="aqualuxe-homepage-testimonials-content">
                <div class="aqualuxe-homepage-testimonials-slider">
                    <?php foreach ($testimonials as $testimonial) : ?>
                        <div class="aqualuxe-homepage-testimonial">
                            <div class="aqualuxe-homepage-testimonial-content">
                                <?php if (!empty($testimonial['content'])) : ?>
                                    <div class="aqualuxe-homepage-testimonial-text">
                                        <?php echo wp_kses_post($testimonial['content']); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="aqualuxe-homepage-testimonial-author">
                                <?php if (!empty($testimonial['image'])) : ?>
                                    <div class="aqualuxe-homepage-testimonial-image">
                                        <img src="<?php echo esc_url($testimonial['image']); ?>" alt="<?php echo esc_attr($testimonial['name']); ?>" />
                                    </div>
                                <?php endif; ?>
                                
                                <div class="aqualuxe-homepage-testimonial-info">
                                    <?php if (!empty($testimonial['name'])) : ?>
                                        <div class="aqualuxe-homepage-testimonial-name">
                                            <?php echo esc_html($testimonial['name']); ?>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($testimonial['position'])) : ?>
                                        <div class="aqualuxe-homepage-testimonial-position">
                                            <?php echo esc_html($testimonial['position']); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display homepage blog
 *
 * @return void
 */
function aqualuxe_homepage_blog() {
    $blog_enabled = aqualuxe_get_option('homepage_blog_enabled', true);
    
    if (!$blog_enabled) {
        return;
    }
    
    $blog_title = aqualuxe_get_option('homepage_blog_title', __('Latest Articles', 'aqualuxe'));
    $blog_description = aqualuxe_get_option('homepage_blog_description', __('Read our latest articles and guides.', 'aqualuxe'));
    $blog_count = aqualuxe_get_option('homepage_blog_count', 3);
    ?>
    <section class="aqualuxe-homepage-blog">
        <div class="container">
            <div class="aqualuxe-section-header">
                <?php if (!empty($blog_title)) : ?>
                    <h2 class="aqualuxe-section-title"><?php echo esc_html($blog_title); ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($blog_description)) : ?>
                    <div class="aqualuxe-section-description">
                        <?php echo wp_kses_post($blog_description); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="aqualuxe-homepage-blog-content">
                <div class="aqualuxe-homepage-blog-grid">
                    <?php
                    $args = array(
                        'post_type' => 'post',
                        'posts_per_page' => $blog_count,
                    );
                    
                    $blog_posts = new WP_Query($args);
                    
                    if ($blog_posts->have_posts()) {
                        while ($blog_posts->have_posts()) {
                            $blog_posts->the_post();
                            ?>
                            <article class="aqualuxe-homepage-blog-post">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="aqualuxe-homepage-blog-post-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium'); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="aqualuxe-homepage-blog-post-content">
                                    <div class="aqualuxe-homepage-blog-post-meta">
                                        <span class="aqualuxe-homepage-blog-post-date">
                                            <?php echo get_the_date(); ?>
                                        </span>
                                        
                                        <?php
                                        $categories = get_the_category();
                                        
                                        if (!empty($categories)) {
                                            echo '<span class="aqualuxe-homepage-blog-post-category">';
                                            echo '<a href="' . esc_url(get_category_link($categories[0]->term_id)) . '">' . esc_html($categories[0]->name) . '</a>';
                                            echo '</span>';
                                        }
                                        ?>
                                    </div>
                                    
                                    <h3 class="aqualuxe-homepage-blog-post-title">
                                        <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </h3>
                                    
                                    <div class="aqualuxe-homepage-blog-post-excerpt">
                                        <?php the_excerpt(); ?>
                                    </div>
                                    
                                    <div class="aqualuxe-homepage-blog-post-button">
                                        <a href="<?php the_permalink(); ?>" class="button button-text"><?php esc_html_e('Read More', 'aqualuxe'); ?></a>
                                    </div>
                                </div>
                            </article>
                            <?php
                        }
                        
                        wp_reset_postdata();
                    } else {
                        echo '<p>' . esc_html__('No blog posts found.', 'aqualuxe') . '</p>';
                    }
                    ?>
                </div>
            </div>
            
            <div class="aqualuxe-homepage-blog-button">
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="button button-outline"><?php esc_html_e('View All Articles', 'aqualuxe'); ?></a>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display homepage newsletter
 *
 * @return void
 */
function aqualuxe_homepage_newsletter() {
    $newsletter_enabled = aqualuxe_get_option('homepage_newsletter_enabled', true);
    
    if (!$newsletter_enabled) {
        return;
    }
    
    $newsletter_title = aqualuxe_get_option('homepage_newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
    $newsletter_description = aqualuxe_get_option('homepage_newsletter_description', __('Stay updated with our latest products, offers, and aquatic care tips.', 'aqualuxe'));
    $newsletter_form = aqualuxe_get_option('homepage_newsletter_form', '');
    $newsletter_image = aqualuxe_get_option('homepage_newsletter_image', '');
    
    if (empty($newsletter_image)) {
        $newsletter_image = AQUALUXE_ASSETS_URI . 'images/newsletter.jpg';
    }
    ?>
    <section class="aqualuxe-homepage-newsletter" style="background-image: url('<?php echo esc_url($newsletter_image); ?>');">
        <div class="container">
            <div class="aqualuxe-homepage-newsletter-content">
                <?php if (!empty($newsletter_title)) : ?>
                    <h2 class="aqualuxe-homepage-newsletter-title"><?php echo esc_html($newsletter_title); ?></h2>
                <?php endif; ?>
                
                <?php if (!empty($newsletter_description)) : ?>
                    <div class="aqualuxe-homepage-newsletter-description">
                        <?php echo wp_kses_post($newsletter_description); ?>
                    </div>
                <?php endif; ?>
                
                <div class="aqualuxe-homepage-newsletter-form">
                    <?php
                    if (!empty($newsletter_form)) {
                        echo do_shortcode($newsletter_form);
                    } else {
                        ?>
                        <form action="#" method="post" class="aqualuxe-newsletter-form">
                            <div class="aqualuxe-newsletter-form-fields">
                                <input type="email" name="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" required />
                                <button type="submit" class="button button-primary"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
                            </div>
                            <div class="aqualuxe-newsletter-form-privacy">
                                <?php esc_html_e('By subscribing, you agree to our Privacy Policy.', 'aqualuxe'); ?>
                            </div>
                        </form>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>
    <?php
}

/**
 * Display post thumbnail
 *
 * @return void
 */
function aqualuxe_post_thumbnail() {
    if (!has_post_thumbnail()) {
        return;
    }
    ?>
    <div class="aqualuxe-post-thumbnail">
        <?php the_post_thumbnail('full'); ?>
    </div>
    <?php
}

/**
 * Display post meta
 *
 * @return void
 */
function aqualuxe_post_meta() {
    ?>
    <div class="aqualuxe-post-meta">
        <span class="aqualuxe-post-date">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M19 3h-1V1h-2v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V8h14v11zM7 10h5v5H7z"></path></svg>
            <?php echo get_the_date(); ?>
        </span>
        
        <span class="aqualuxe-post-author">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"></path></svg>
            <?php the_author_posts_link(); ?>
        </span>
        
        <?php
        $categories = get_the_category();
        
        if (!empty($categories)) {
            echo '<span class="aqualuxe-post-category">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M17.63 5.84C17.27 5.33 16.67 5 16 5L5 5.01C3.9 5.01 3 5.9 3 7v10c0 1.1.9 1.99 2 1.99L16 19c.67 0 1.27-.33 1.63-.84L22 12l-4.37-6.16zM16 17H5V7h11l3.55 5L16 17z"></path></svg>';
            
            $category_links = array();
            
            foreach ($categories as $category) {
                $category_links[] = '<a href="' . esc_url(get_category_link($category->term_id)) . '">' . esc_html($category->name) . '</a>';
            }
            
            echo implode(', ', $category_links);
            
            echo '</span>';
        }
        ?>
        
        <span class="aqualuxe-post-comments">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M21.99 4c0-1.1-.89-2-1.99-2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h14l4 4-.01-18zM18 14H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"></path></svg>
            <?php
            comments_popup_link(
                __('No Comments', 'aqualuxe'),
                __('1 Comment', 'aqualuxe'),
                __('% Comments', 'aqualuxe')
            );
            ?>
        </span>
    </div>
    <?php
}

/**
 * Display post content
 *
 * @return void
 */
function aqualuxe_post_content() {
    ?>
    <div class="aqualuxe-post-content">
        <?php the_content(); ?>
        
        <?php
        wp_link_pages(array(
            'before' => '<div class="aqualuxe-post-pages">' . __('Pages:', 'aqualuxe'),
            'after' => '</div>',
        ));
        ?>
    </div>
    <?php
}

/**
 * Display post tags
 *
 * @return void
 */
function aqualuxe_post_tags() {
    $tags = get_the_tags();
    
    if (empty($tags)) {
        return;
    }
    ?>
    <div class="aqualuxe-post-tags">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M21.41 11.58l-9-9C12.05 2.22 11.55 2 11 2H4c-1.1 0-2 .9-2 2v7c0 .55.22 1.05.59 1.42l9 9c.36.36.86.58 1.41.58.55 0 1.05-.22 1.41-.59l7-7c.37-.36.59-.86.59-1.41 0-.55-.23-1.06-.59-1.42zM5.5 7C4.67 7 4 6.33 4 5.5S4.67 4 5.5 4 7 4.67 7 5.5 6.33 7 5.5 7z"></path></svg>
        
        <?php
        $tag_links = array();
        
        foreach ($tags as $tag) {
            $tag_links[] = '<a href="' . esc_url(get_tag_link($tag->term_id)) . '">' . esc_html($tag->name) . '</a>';
        }
        
        echo implode(', ', $tag_links);
        ?>
    </div>
    <?php
}

/**
 * Display post author bio
 *
 * @return void
 */
function aqualuxe_post_author_bio() {
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name');
    $author_description = get_the_author_meta('description');
    $author_url = get_author_posts_url($author_id);
    
    if (empty($author_description)) {
        return;
    }
    ?>
    <div class="aqualuxe-post-author-bio">
        <div class="aqualuxe-post-author-avatar">
            <?php echo get_avatar($author_id, 80); ?>
        </div>
        
        <div class="aqualuxe-post-author-content">
            <h3 class="aqualuxe-post-author-name">
                <a href="<?php echo esc_url($author_url); ?>"><?php echo esc_html($author_name); ?></a>
            </h3>
            
            <div class="aqualuxe-post-author-description">
                <?php echo wpautop($author_description); ?>
            </div>
            
            <div class="aqualuxe-post-author-link">
                <a href="<?php echo esc_url($author_url); ?>" class="button button-text"><?php esc_html_e('View all posts', 'aqualuxe'); ?></a>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display post navigation
 *
 * @return void
 */
function aqualuxe_post_navigation() {
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if (empty($prev_post) && empty($next_post)) {
        return;
    }
    ?>
    <nav class="aqualuxe-post-navigation">
        <?php if (!empty($prev_post)) : ?>
            <div class="aqualuxe-post-navigation-prev">
                <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" class="aqualuxe-post-navigation-link">
                    <span class="aqualuxe-post-navigation-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"></path></svg>
                    </span>
                    <span class="aqualuxe-post-navigation-text">
                        <span class="aqualuxe-post-navigation-label"><?php esc_html_e('Previous Post', 'aqualuxe'); ?></span>
                        <span class="aqualuxe-post-navigation-title"><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
                    </span>
                </a>
            </div>
        <?php endif; ?>
        
        <?php if (!empty($next_post)) : ?>
            <div class="aqualuxe-post-navigation-next">
                <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" class="aqualuxe-post-navigation-link">
                    <span class="aqualuxe-post-navigation-text">
                        <span class="aqualuxe-post-navigation-label"><?php esc_html_e('Next Post', 'aqualuxe'); ?></span>
                        <span class="aqualuxe-post-navigation-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
                    </span>
                    <span class="aqualuxe-post-navigation-arrow">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"></path></svg>
                    </span>
                </a>
            </div>
        <?php endif; ?>
    </nav>
    <?php
}

/**
 * Display post related
 *
 * @return void
 */
function aqualuxe_post_related() {
    $related_posts_enabled = aqualuxe_get_option('related_posts_enabled', true);
    
    if (!$related_posts_enabled) {
        return;
    }
    
    $related_posts_title = aqualuxe_get_option('related_posts_title', __('Related Posts', 'aqualuxe'));
    $related_posts_count = aqualuxe_get_option('related_posts_count', 3);
    
    $categories = get_the_category();
    
    if (empty($categories)) {
        return;
    }
    
    $category_ids = array();
    
    foreach ($categories as $category) {
        $category_ids[] = $category->term_id;
    }
    
    $args = array(
        'post_type' => 'post',
        'posts_per_page' => $related_posts_count,
        'post__not_in' => array(get_the_ID()),
        'category__in' => $category_ids,
    );
    
    $related_posts = new WP_Query($args);
    
    if (!$related_posts->have_posts()) {
        return;
    }
    ?>
    <div class="aqualuxe-post-related">
        <?php if (!empty($related_posts_title)) : ?>
            <h3 class="aqualuxe-post-related-title"><?php echo esc_html($related_posts_title); ?></h3>
        <?php endif; ?>
        
        <div class="aqualuxe-post-related-grid">
            <?php
            while ($related_posts->have_posts()) {
                $related_posts->the_post();
                ?>
                <article class="aqualuxe-post-related-item">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="aqualuxe-post-related-image">
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium'); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="aqualuxe-post-related-content">
                        <h4 class="aqualuxe-post-related-item-title">
                            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                        </h4>
                        
                        <div class="aqualuxe-post-related-item-meta">
                            <?php echo get_the_date(); ?>
                        </div>
                    </div>
                </article>
                <?php
            }
            
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
}

/**
 * Display post comments
 *
 * @return void
 */
function aqualuxe_post_comments() {
    if (!comments_open() && !get_comments_number()) {
        return;
    }
    
    comments_template();
}

/**
 * Display page content
 *
 * @return void
 */
function aqualuxe_page_content() {
    ?>
    <div class="aqualuxe-page-content">
        <?php the_content(); ?>
        
        <?php
        wp_link_pages(array(
            'before' => '<div class="aqualuxe-page-pages">' . __('Pages:', 'aqualuxe'),
            'after' => '</div>',
        ));
        ?>
    </div>
    <?php
}

/**
 * Display page comments
 *
 * @return void
 */
function aqualuxe_page_comments() {
    if (!comments_open() && !get_comments_number()) {
        return;
    }
    
    comments_template();
}

/**
 * Get sidebar
 *
 * @return void
 */
function aqualuxe_get_sidebar() {
    if (aqualuxe_is_woocommerce_page()) {
        get_sidebar('shop');
    } else {
        get_sidebar();
    }
}