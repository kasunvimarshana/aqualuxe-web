<?php
/**
 * AquaLuxe Header Template Tags
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Display site header
 */
function aqualuxe_site_header() {
    $header_layout = aqualuxe_get_header_layout();
    
    // Get header template based on layout
    aqualuxe_get_template_part('template-parts/header/header', $header_layout);
}

/**
 * Display header top bar
 */
function aqualuxe_header_top_bar() {
    $show_top_bar = get_theme_mod('aqualuxe_show_top_bar', true);
    
    if (!$show_top_bar) {
        return;
    }
    
    ?>
    <div class="header-top-bar">
        <div class="container">
            <div class="header-top-bar-inner">
                <div class="header-top-bar-left">
                    <?php aqualuxe_header_top_bar_left(); ?>
                </div>
                <div class="header-top-bar-right">
                    <?php aqualuxe_header_top_bar_right(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display header top bar left content
 */
function aqualuxe_header_top_bar_left() {
    $top_bar_left_content = get_theme_mod('aqualuxe_top_bar_left_content', 'contact');
    
    switch ($top_bar_left_content) {
        case 'contact':
            aqualuxe_header_contact_info();
            break;
        case 'menu':
            aqualuxe_secondary_menu();
            break;
        case 'text':
            echo wp_kses_post(get_theme_mod('aqualuxe_top_bar_left_text', ''));
            break;
        case 'social':
            aqualuxe_social_links();
            break;
        case 'none':
        default:
            break;
    }
}

/**
 * Display header top bar right content
 */
function aqualuxe_header_top_bar_right() {
    $top_bar_right_content = get_theme_mod('aqualuxe_top_bar_right_content', 'social');
    
    switch ($top_bar_right_content) {
        case 'contact':
            aqualuxe_header_contact_info();
            break;
        case 'menu':
            aqualuxe_secondary_menu();
            break;
        case 'text':
            echo wp_kses_post(get_theme_mod('aqualuxe_top_bar_right_text', ''));
            break;
        case 'social':
            aqualuxe_social_links();
            break;
        case 'language':
            aqualuxe_language_switcher();
            break;
        case 'currency':
            aqualuxe_currency_switcher();
            break;
        case 'none':
        default:
            break;
    }
}

/**
 * Display header contact info
 */
function aqualuxe_header_contact_info() {
    $contact_info = aqualuxe_get_contact_info();
    
    if (!$contact_info) {
        return;
    }
    
    echo '<div class="header-contact-info">';
    
    foreach ($contact_info as $key => $contact) {
        echo '<div class="header-contact-info-item header-contact-info-' . esc_attr($key) . '">';
        
        if (!empty($contact['url'])) {
            echo '<a href="' . esc_url($contact['url']) . '" class="header-contact-info-link">';
        }
        
        echo '<i class="' . esc_attr($contact['icon']) . '"></i>';
        echo '<span class="header-contact-info-text">' . esc_html($contact['value']) . '</span>';
        
        if (!empty($contact['url'])) {
            echo '</a>';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Display header main
 */
function aqualuxe_header_main() {
    ?>
    <div class="header-main">
        <div class="container">
            <div class="header-main-inner">
                <div class="header-main-left">
                    <?php aqualuxe_header_main_left(); ?>
                </div>
                <div class="header-main-center">
                    <?php aqualuxe_header_main_center(); ?>
                </div>
                <div class="header-main-right">
                    <?php aqualuxe_header_main_right(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display header main left content
 */
function aqualuxe_header_main_left() {
    $header_main_left_content = get_theme_mod('aqualuxe_header_main_left_content', 'logo');
    
    switch ($header_main_left_content) {
        case 'logo':
            aqualuxe_site_logo();
            break;
        case 'menu':
            aqualuxe_primary_menu();
            break;
        case 'search':
            aqualuxe_search_icon();
            break;
        case 'none':
        default:
            break;
    }
}

/**
 * Display header main center content
 */
function aqualuxe_header_main_center() {
    $header_main_center_content = get_theme_mod('aqualuxe_header_main_center_content', 'menu');
    
    switch ($header_main_center_content) {
        case 'logo':
            aqualuxe_site_logo();
            break;
        case 'menu':
            aqualuxe_primary_menu();
            break;
        case 'search':
            aqualuxe_search_icon();
            break;
        case 'none':
        default:
            break;
    }
}

/**
 * Display header main right content
 */
function aqualuxe_header_main_right() {
    $header_main_right_content = get_theme_mod('aqualuxe_header_main_right_content', 'icons');
    
    switch ($header_main_right_content) {
        case 'logo':
            aqualuxe_site_logo();
            break;
        case 'menu':
            aqualuxe_primary_menu();
            break;
        case 'search':
            aqualuxe_search_icon();
            break;
        case 'icons':
            aqualuxe_header_icons();
            break;
        case 'none':
        default:
            break;
    }
}

/**
 * Display header icons
 */
function aqualuxe_header_icons() {
    ?>
    <div class="header-icons">
        <?php
        aqualuxe_search_icon();
        
        if (aqualuxe_is_woocommerce_active()) {
            aqualuxe_account_link();
            aqualuxe_mini_cart();
        }
        
        aqualuxe_color_mode_switcher();
        ?>
        <button class="mobile-menu-toggle" aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>">
            <span class="mobile-menu-toggle-icon"><span></span></span>
        </button>
    </div>
    <?php
}

/**
 * Display header bottom
 */
function aqualuxe_header_bottom() {
    $show_header_bottom = get_theme_mod('aqualuxe_show_header_bottom', false);
    
    if (!$show_header_bottom) {
        return;
    }
    
    ?>
    <div class="header-bottom">
        <div class="container">
            <div class="header-bottom-inner">
                <div class="header-bottom-left">
                    <?php aqualuxe_header_bottom_left(); ?>
                </div>
                <div class="header-bottom-center">
                    <?php aqualuxe_header_bottom_center(); ?>
                </div>
                <div class="header-bottom-right">
                    <?php aqualuxe_header_bottom_right(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display header bottom left content
 */
function aqualuxe_header_bottom_left() {
    $header_bottom_left_content = get_theme_mod('aqualuxe_header_bottom_left_content', 'categories');
    
    switch ($header_bottom_left_content) {
        case 'categories':
            if (aqualuxe_is_woocommerce_active()) {
                aqualuxe_product_categories_menu();
            }
            break;
        case 'menu':
            aqualuxe_shop_menu();
            break;
        case 'search':
            if (aqualuxe_is_woocommerce_active()) {
                aqualuxe_woocommerce_product_search();
            } else {
                aqualuxe_search_icon();
            }
            break;
        case 'none':
        default:
            break;
    }
}

/**
 * Display header bottom center content
 */
function aqualuxe_header_bottom_center() {
    $header_bottom_center_content = get_theme_mod('aqualuxe_header_bottom_center_content', 'search');
    
    switch ($header_bottom_center_content) {
        case 'categories':
            if (aqualuxe_is_woocommerce_active()) {
                aqualuxe_product_categories_menu();
            }
            break;
        case 'menu':
            aqualuxe_shop_menu();
            break;
        case 'search':
            if (aqualuxe_is_woocommerce_active()) {
                aqualuxe_woocommerce_product_search();
            } else {
                aqualuxe_search_icon();
            }
            break;
        case 'none':
        default:
            break;
    }
}

/**
 * Display header bottom right content
 */
function aqualuxe_header_bottom_right() {
    $header_bottom_right_content = get_theme_mod('aqualuxe_header_bottom_right_content', 'menu');
    
    switch ($header_bottom_right_content) {
        case 'categories':
            if (aqualuxe_is_woocommerce_active()) {
                aqualuxe_product_categories_menu();
            }
            break;
        case 'menu':
            aqualuxe_shop_menu();
            break;
        case 'search':
            if (aqualuxe_is_woocommerce_active()) {
                aqualuxe_woocommerce_product_search();
            } else {
                aqualuxe_search_icon();
            }
            break;
        case 'none':
        default:
            break;
    }
}

/**
 * Display product categories menu
 */
function aqualuxe_product_categories_menu() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0,
    ]);
    
    if (!$categories || is_wp_error($categories)) {
        return;
    }
    
    ?>
    <div class="product-categories-menu">
        <button class="product-categories-toggle">
            <i class="fas fa-bars"></i>
            <span><?php esc_html_e('Categories', 'aqualuxe'); ?></span>
            <i class="fas fa-chevron-down"></i>
        </button>
        <div class="product-categories-dropdown">
            <ul class="product-categories-list">
                <?php foreach ($categories as $category) : ?>
                    <li class="product-category-item">
                        <a href="<?php echo esc_url(get_term_link($category)); ?>" class="product-category-link">
                            <?php
                            $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                            if ($thumbnail_id) {
                                echo wp_get_attachment_image($thumbnail_id, 'thumbnail', false, ['class' => 'product-category-image']);
                            }
                            ?>
                            <span class="product-category-name"><?php echo esc_html($category->name); ?></span>
                        </a>
                        <?php
                        $child_categories = get_terms([
                            'taxonomy' => 'product_cat',
                            'hide_empty' => true,
                            'parent' => $category->term_id,
                        ]);
                        
                        if ($child_categories && !is_wp_error($child_categories)) :
                            ?>
                            <ul class="product-subcategories-list">
                                <?php foreach ($child_categories as $child_category) : ?>
                                    <li class="product-subcategory-item">
                                        <a href="<?php echo esc_url(get_term_link($child_category)); ?>" class="product-subcategory-link">
                                            <?php echo esc_html($child_category->name); ?>
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
 * Display header sticky
 */
function aqualuxe_header_sticky() {
    $enable_sticky_header = get_theme_mod('aqualuxe_enable_sticky_header', true);
    
    if (!$enable_sticky_header) {
        return;
    }
    
    ?>
    <div class="header-sticky">
        <div class="container">
            <div class="header-sticky-inner">
                <div class="header-sticky-left">
                    <?php aqualuxe_site_logo(); ?>
                </div>
                <div class="header-sticky-center">
                    <?php aqualuxe_primary_menu(); ?>
                </div>
                <div class="header-sticky-right">
                    <?php aqualuxe_header_icons(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display header mobile
 */
function aqualuxe_header_mobile() {
    ?>
    <div class="header-mobile">
        <div class="container">
            <div class="header-mobile-inner">
                <div class="header-mobile-left">
                    <?php aqualuxe_site_logo(); ?>
                </div>
                <div class="header-mobile-right">
                    <?php
                    aqualuxe_search_icon();
                    
                    if (aqualuxe_is_woocommerce_active()) {
                        aqualuxe_account_link();
                        aqualuxe_mini_cart();
                    }
                    ?>
                    <button class="mobile-menu-toggle" aria-label="<?php esc_attr_e('Toggle mobile menu', 'aqualuxe'); ?>">
                        <span class="mobile-menu-toggle-icon"><span></span></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display mobile menu
 */
function aqualuxe_mobile_menu_container() {
    ?>
    <div id="mobile-menu-container" class="mobile-menu-container">
        <div class="mobile-menu-container-inner">
            <div class="mobile-menu-header">
                <div class="mobile-menu-logo">
                    <?php aqualuxe_site_logo(); ?>
                </div>
                <button class="mobile-menu-close" aria-label="<?php esc_attr_e('Close menu', 'aqualuxe'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="mobile-menu-content">
                <?php
                if (aqualuxe_is_woocommerce_active()) {
                    aqualuxe_woocommerce_product_search();
                } else {
                    get_search_form();
                }
                
                aqualuxe_mobile_menu();
                
                if (aqualuxe_is_woocommerce_active()) {
                    echo '<div class="mobile-menu-account">';
                    if (is_user_logged_in()) {
                        echo '<a href="' . esc_url(wc_get_account_endpoint_url('dashboard')) . '" class="mobile-menu-account-link">';
                        echo '<i class="fas fa-user"></i>';
                        echo esc_html__('My Account', 'aqualuxe');
                        echo '</a>';
                        
                        echo '<a href="' . esc_url(wc_get_endpoint_url('orders', '', wc_get_page_permalink('myaccount'))) . '" class="mobile-menu-account-link">';
                        echo '<i class="fas fa-box"></i>';
                        echo esc_html__('My Orders', 'aqualuxe');
                        echo '</a>';
                        
                        echo '<a href="' . esc_url(wc_logout_url()) . '" class="mobile-menu-account-link">';
                        echo '<i class="fas fa-sign-out-alt"></i>';
                        echo esc_html__('Logout', 'aqualuxe');
                        echo '</a>';
                    } else {
                        echo '<a href="' . esc_url(wc_get_page_permalink('myaccount')) . '" class="mobile-menu-account-link">';
                        echo '<i class="fas fa-user"></i>';
                        echo esc_html__('Login / Register', 'aqualuxe');
                        echo '</a>';
                    }
                    echo '</div>';
                }
                
                aqualuxe_social_links();
                aqualuxe_header_contact_info();
                aqualuxe_language_switcher();
                aqualuxe_currency_switcher();
                aqualuxe_color_mode_switcher();
                ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display search modal
 */
function aqualuxe_search_modal() {
    ?>
    <div id="search-modal" class="search-modal">
        <div class="search-modal-inner">
            <div class="search-modal-header">
                <h3 class="search-modal-title"><?php esc_html_e('Search', 'aqualuxe'); ?></h3>
                <button class="search-modal-close" aria-label="<?php esc_attr_e('Close search', 'aqualuxe'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="search-modal-content">
                <?php
                if (aqualuxe_is_woocommerce_active()) {
                    aqualuxe_woocommerce_product_search();
                } else {
                    get_search_form();
                }
                ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display quick view modal
 */
function aqualuxe_quick_view_modal() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div id="quick-view-modal" class="quick-view-modal">
        <div class="quick-view-modal-inner">
            <div class="quick-view-modal-header">
                <h3 class="quick-view-modal-title"><?php esc_html_e('Quick View', 'aqualuxe'); ?></h3>
                <button class="quick-view-modal-close" aria-label="<?php esc_attr_e('Close quick view', 'aqualuxe'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="quick-view-modal-content">
                <div class="quick-view-loading">
                    <div class="spinner"></div>
                </div>
                <div class="quick-view-product"></div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display wishlist modal
 */
function aqualuxe_wishlist_modal() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div id="wishlist-modal" class="wishlist-modal">
        <div class="wishlist-modal-inner">
            <div class="wishlist-modal-header">
                <h3 class="wishlist-modal-title"><?php esc_html_e('Wishlist', 'aqualuxe'); ?></h3>
                <button class="wishlist-modal-close" aria-label="<?php esc_attr_e('Close wishlist', 'aqualuxe'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="wishlist-modal-content">
                <div class="wishlist-loading">
                    <div class="spinner"></div>
                </div>
                <div class="wishlist-products"></div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display compare modal
 */
function aqualuxe_compare_modal() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div id="compare-modal" class="compare-modal">
        <div class="compare-modal-inner">
            <div class="compare-modal-header">
                <h3 class="compare-modal-title"><?php esc_html_e('Compare Products', 'aqualuxe'); ?></h3>
                <button class="compare-modal-close" aria-label="<?php esc_attr_e('Close compare', 'aqualuxe'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="compare-modal-content">
                <div class="compare-loading">
                    <div class="spinner"></div>
                </div>
                <div class="compare-products"></div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display cart modal
 */
function aqualuxe_cart_modal() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div id="cart-modal" class="cart-modal">
        <div class="cart-modal-inner">
            <div class="cart-modal-header">
                <h3 class="cart-modal-title"><?php esc_html_e('Shopping Cart', 'aqualuxe'); ?></h3>
                <button class="cart-modal-close" aria-label="<?php esc_attr_e('Close cart', 'aqualuxe'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="cart-modal-content">
                <div class="widget_shopping_cart_content">
                    <?php woocommerce_mini_cart(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display login modal
 */
function aqualuxe_login_modal() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    ?>
    <div id="login-modal" class="login-modal">
        <div class="login-modal-inner">
            <div class="login-modal-header">
                <h3 class="login-modal-title"><?php esc_html_e('Login', 'aqualuxe'); ?></h3>
                <button class="login-modal-close" aria-label="<?php esc_attr_e('Close login', 'aqualuxe'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="login-modal-content">
                <div class="login-modal-tabs">
                    <button class="login-modal-tab active" data-tab="login"><?php esc_html_e('Login', 'aqualuxe'); ?></button>
                    <button class="login-modal-tab" data-tab="register"><?php esc_html_e('Register', 'aqualuxe'); ?></button>
                </div>
                <div class="login-modal-tab-content active" data-tab-content="login">
                    <?php woocommerce_login_form(); ?>
                    <p class="lost-password">
                        <a href="<?php echo esc_url(wp_lostpassword_url()); ?>"><?php esc_html_e('Lost your password?', 'aqualuxe'); ?></a>
                    </p>
                </div>
                <div class="login-modal-tab-content" data-tab-content="register">
                    <?php if (get_option('woocommerce_enable_myaccount_registration') === 'yes') : ?>
                        <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>
                            <?php do_action('woocommerce_register_form_start'); ?>
                            
                            <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <label for="reg_username"><?php esc_html_e('Username', 'aqualuxe'); ?> <span class="required">*</span></label>
                                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
                                </p>
                            <?php endif; ?>
                            
                            <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                <label for="reg_email"><?php esc_html_e('Email address', 'aqualuxe'); ?> <span class="required">*</span></label>
                                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
                            </p>
                            
                            <?php if ('no' === get_option('woocommerce_registration_generate_password')) : ?>
                                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
                                    <label for="reg_password"><?php esc_html_e('Password', 'aqualuxe'); ?> <span class="required">*</span></label>
                                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                                </p>
                            <?php else : ?>
                                <p><?php esc_html_e('A password will be sent to your email address.', 'aqualuxe'); ?></p>
                            <?php endif; ?>
                            
                            <?php do_action('woocommerce_register_form'); ?>
                            
                            <p class="woocommerce-form-row form-row">
                                <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                                <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e('Register', 'aqualuxe'); ?>"><?php esc_html_e('Register', 'aqualuxe'); ?></button>
                            </p>
                            
                            <?php do_action('woocommerce_register_form_end'); ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display newsletter modal
 */
function aqualuxe_newsletter_modal() {
    $show_newsletter_modal = get_theme_mod('aqualuxe_show_newsletter_modal', false);
    
    if (!$show_newsletter_modal) {
        return;
    }
    
    $newsletter_title = get_theme_mod('aqualuxe_newsletter_modal_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
    $newsletter_content = get_theme_mod('aqualuxe_newsletter_modal_content', __('Subscribe to our newsletter and get 10% off your first purchase', 'aqualuxe'));
    $newsletter_form = get_theme_mod('aqualuxe_newsletter_modal_form', '');
    $newsletter_image = get_theme_mod('aqualuxe_newsletter_modal_image', '');
    
    ?>
    <div id="newsletter-modal" class="newsletter-modal">
        <div class="newsletter-modal-inner">
            <button class="newsletter-modal-close" aria-label="<?php esc_attr_e('Close newsletter', 'aqualuxe'); ?>">
                <i class="fas fa-times"></i>
            </button>
            <div class="newsletter-modal-content">
                <?php if ($newsletter_image) : ?>
                    <div class="newsletter-modal-image">
                        <?php echo wp_get_attachment_image($newsletter_image, 'medium'); ?>
                    </div>
                <?php endif; ?>
                <div class="newsletter-modal-form">
                    <h3 class="newsletter-modal-title"><?php echo esc_html($newsletter_title); ?></h3>
                    <div class="newsletter-modal-description">
                        <?php echo wp_kses_post($newsletter_content); ?>
                    </div>
                    <div class="newsletter-modal-form-content">
                        <?php echo do_shortcode($newsletter_form); ?>
                    </div>
                    <div class="newsletter-modal-footer">
                        <label class="newsletter-modal-dont-show">
                            <input type="checkbox" name="dont_show_again" value="1">
                            <span><?php esc_html_e('Don\'t show this popup again', 'aqualuxe'); ?></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display cookie notice
 */
function aqualuxe_cookie_notice() {
    $show_cookie_notice = get_theme_mod('aqualuxe_show_cookie_notice', true);
    
    if (!$show_cookie_notice) {
        return;
    }
    
    $cookie_notice_text = get_theme_mod('aqualuxe_cookie_notice_text', __('We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies.', 'aqualuxe'));
    $cookie_notice_button_text = get_theme_mod('aqualuxe_cookie_notice_button_text', __('Accept', 'aqualuxe'));
    $cookie_notice_more_link = get_theme_mod('aqualuxe_cookie_notice_more_link', '');
    $cookie_notice_more_text = get_theme_mod('aqualuxe_cookie_notice_more_text', __('More information', 'aqualuxe'));
    
    ?>
    <div id="cookie-notice" class="cookie-notice">
        <div class="container">
            <div class="cookie-notice-inner">
                <div class="cookie-notice-content">
                    <?php echo wp_kses_post($cookie_notice_text); ?>
                    
                    <?php if ($cookie_notice_more_link) : ?>
                        <a href="<?php echo esc_url($cookie_notice_more_link); ?>" class="cookie-notice-more"><?php echo esc_html($cookie_notice_more_text); ?></a>
                    <?php endif; ?>
                </div>
                <div class="cookie-notice-actions">
                    <button id="cookie-notice-accept" class="cookie-notice-accept"><?php echo esc_html($cookie_notice_button_text); ?></button>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display age verification modal
 */
function aqualuxe_age_verification_modal() {
    $show_age_verification = get_theme_mod('aqualuxe_show_age_verification', false);
    
    if (!$show_age_verification) {
        return;
    }
    
    $age_verification_title = get_theme_mod('aqualuxe_age_verification_title', __('Age Verification', 'aqualuxe'));
    $age_verification_content = get_theme_mod('aqualuxe_age_verification_content', __('This website requires you to be 18 years or older to enter.', 'aqualuxe'));
    $age_verification_yes_text = get_theme_mod('aqualuxe_age_verification_yes_text', __('I am 18 or older', 'aqualuxe'));
    $age_verification_no_text = get_theme_mod('aqualuxe_age_verification_no_text', __('I am under 18', 'aqualuxe'));
    $age_verification_exit_link = get_theme_mod('aqualuxe_age_verification_exit_link', 'https://www.google.com');
    $age_verification_background = get_theme_mod('aqualuxe_age_verification_background', '');
    
    ?>
    <div id="age-verification-modal" class="age-verification-modal">
        <div class="age-verification-modal-inner" <?php echo $age_verification_background ? 'style="background-image: url(' . esc_url(wp_get_attachment_image_url($age_verification_background, 'full')) . ')"' : ''; ?>>
            <div class="age-verification-modal-content">
                <h3 class="age-verification-modal-title"><?php echo esc_html($age_verification_title); ?></h3>
                <div class="age-verification-modal-description">
                    <?php echo wp_kses_post($age_verification_content); ?>
                </div>
                <div class="age-verification-modal-actions">
                    <button id="age-verification-yes" class="age-verification-yes"><?php echo esc_html($age_verification_yes_text); ?></button>
                    <a href="<?php echo esc_url($age_verification_exit_link); ?>" id="age-verification-no" class="age-verification-no"><?php echo esc_html($age_verification_no_text); ?></a>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display promo bar
 */
function aqualuxe_promo_bar() {
    $show_promo_bar = get_theme_mod('aqualuxe_show_promo_bar', false);
    
    if (!$show_promo_bar) {
        return;
    }
    
    $promo_bar_text = get_theme_mod('aqualuxe_promo_bar_text', __('Free shipping on all orders over $50', 'aqualuxe'));
    $promo_bar_link = get_theme_mod('aqualuxe_promo_bar_link', '');
    $promo_bar_button_text = get_theme_mod('aqualuxe_promo_bar_button_text', __('Shop Now', 'aqualuxe'));
    $promo_bar_bg_color = get_theme_mod('aqualuxe_promo_bar_bg_color', '#000000');
    $promo_bar_text_color = get_theme_mod('aqualuxe_promo_bar_text_color', '#ffffff');
    
    ?>
    <div class="promo-bar" style="background-color: <?php echo esc_attr($promo_bar_bg_color); ?>; color: <?php echo esc_attr($promo_bar_text_color); ?>;">
        <div class="container">
            <div class="promo-bar-inner">
                <div class="promo-bar-content">
                    <?php echo wp_kses_post($promo_bar_text); ?>
                </div>
                <?php if ($promo_bar_link && $promo_bar_button_text) : ?>
                    <div class="promo-bar-action">
                        <a href="<?php echo esc_url($promo_bar_link); ?>" class="promo-bar-button"><?php echo esc_html($promo_bar_button_text); ?></a>
                    </div>
                <?php endif; ?>
                <button class="promo-bar-close" aria-label="<?php esc_attr_e('Close promo bar', 'aqualuxe'); ?>">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display header modals
 */
function aqualuxe_header_modals() {
    aqualuxe_mobile_menu_container();
    aqualuxe_search_modal();
    
    if (aqualuxe_is_woocommerce_active()) {
        aqualuxe_quick_view_modal();
        aqualuxe_wishlist_modal();
        aqualuxe_compare_modal();
        aqualuxe_cart_modal();
        aqualuxe_login_modal();
    }
    
    aqualuxe_newsletter_modal();
    aqualuxe_cookie_notice();
    aqualuxe_age_verification_modal();
}

/**
 * Display header notifications
 */
function aqualuxe_header_notifications() {
    aqualuxe_promo_bar();
    
    if (aqualuxe_is_woocommerce_active()) {
        aqualuxe_woocommerce_notices();
    }
}

/**
 * Display header
 */
function aqualuxe_header() {
    ?>
    <header id="masthead" class="site-header">
        <?php
        aqualuxe_header_notifications();
        aqualuxe_header_top_bar();
        aqualuxe_header_main();
        aqualuxe_header_bottom();
        aqualuxe_header_sticky();
        aqualuxe_header_mobile();
        aqualuxe_header_modals();
        ?>
    </header>
    <?php
}

/**
 * Display page header
 */
function aqualuxe_page_header_wrapper() {
    if (is_front_page()) {
        return;
    }
    
    $show_page_header = get_theme_mod('aqualuxe_show_page_header', true);
    
    if (!$show_page_header) {
        return;
    }
    
    // Check if page header is disabled for this page
    if (is_singular()) {
        $disable_page_header = get_post_meta(get_the_ID(), '_aqualuxe_disable_page_header', true);
        
        if ($disable_page_header) {
            return;
        }
    }
    
    $page_header_layout = get_theme_mod('aqualuxe_page_header_layout', 'default');
    
    // Get page header template based on layout
    aqualuxe_get_template_part('template-parts/page-header/page-header', $page_header_layout);
}

/**
 * Display page title
 */
function aqualuxe_page_title_wrapper() {
    if (is_front_page()) {
        return;
    }
    
    $show_page_title = get_theme_mod('aqualuxe_show_page_title', true);
    
    if (!$show_page_title) {
        return;
    }
    
    // Check if page title is disabled for this page
    if (is_singular()) {
        $disable_page_title = get_post_meta(get_the_ID(), '_aqualuxe_disable_page_title', true);
        
        if ($disable_page_title) {
            return;
        }
    }
    
    $page_title = aqualuxe_get_page_title();
    $page_subtitle = '';
    
    if (is_singular()) {
        $page_subtitle = get_post_meta(get_the_ID(), '_aqualuxe_page_subtitle', true);
    }
    
    $show_breadcrumbs = get_theme_mod('aqualuxe_show_breadcrumbs', true);
    
    ?>
    <div class="page-title-wrapper">
        <div class="container">
            <div class="page-title-inner">
                <?php if ($show_breadcrumbs) : ?>
                    <?php aqualuxe_breadcrumbs(); ?>
                <?php endif; ?>
                
                <h1 class="page-title"><?php echo wp_kses_post($page_title); ?></h1>
                
                <?php if ($page_subtitle) : ?>
                    <div class="page-subtitle"><?php echo wp_kses_post($page_subtitle); ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display breadcrumbs wrapper
 */
function aqualuxe_breadcrumbs_wrapper() {
    if (is_front_page()) {
        return;
    }
    
    $show_breadcrumbs = get_theme_mod('aqualuxe_show_breadcrumbs', true);
    
    if (!$show_breadcrumbs) {
        return;
    }
    
    // Check if breadcrumbs are disabled for this page
    if (is_singular()) {
        $disable_breadcrumbs = get_post_meta(get_the_ID(), '_aqualuxe_disable_breadcrumbs', true);
        
        if ($disable_breadcrumbs) {
            return;
        }
    }
    
    ?>
    <div class="breadcrumbs-wrapper">
        <div class="container">
            <?php aqualuxe_breadcrumbs(); ?>
        </div>
    </div>
    <?php
}

/**
 * Display header widgets
 */
function aqualuxe_header_widgets() {
    if (!is_active_sidebar('header-widgets')) {
        return;
    }
    
    ?>
    <div class="header-widgets">
        <div class="container">
            <?php dynamic_sidebar('header-widgets'); ?>
        </div>
    </div>
    <?php
}

/**
 * Display header banner
 */
function aqualuxe_header_banner() {
    $show_header_banner = get_theme_mod('aqualuxe_show_header_banner', false);
    
    if (!$show_header_banner) {
        return;
    }
    
    // Check if header banner is disabled for this page
    if (is_singular()) {
        $disable_header_banner = get_post_meta(get_the_ID(), '_aqualuxe_disable_header_banner', true);
        
        if ($disable_header_banner) {
            return;
        }
    }
    
    $header_banner_content = get_theme_mod('aqualuxe_header_banner_content', '');
    
    if (!$header_banner_content) {
        return;
    }
    
    ?>
    <div class="header-banner">
        <div class="container">
            <?php echo do_shortcode($header_banner_content); ?>
        </div>
    </div>
    <?php
}

/**
 * Display header slider
 */
function aqualuxe_header_slider() {
    $show_header_slider = get_theme_mod('aqualuxe_show_header_slider', false);
    
    if (!$show_header_slider) {
        return;
    }
    
    // Check if header slider is disabled for this page
    if (is_singular()) {
        $disable_header_slider = get_post_meta(get_the_ID(), '_aqualuxe_disable_header_slider', true);
        
        if ($disable_header_slider) {
            return;
        }
    }
    
    $header_slider_content = get_theme_mod('aqualuxe_header_slider_content', '');
    
    if (!$header_slider_content) {
        return;
    }
    
    ?>
    <div class="header-slider">
        <?php echo do_shortcode($header_slider_content); ?>
    </div>
    <?php
}

/**
 * Display header video
 */
function aqualuxe_header_video() {
    $show_header_video = get_theme_mod('aqualuxe_show_header_video', false);
    
    if (!$show_header_video) {
        return;
    }
    
    // Check if header video is disabled for this page
    if (is_singular()) {
        $disable_header_video = get_post_meta(get_the_ID(), '_aqualuxe_disable_header_video', true);
        
        if ($disable_header_video) {
            return;
        }
    }
    
    $header_video_url = get_theme_mod('aqualuxe_header_video_url', '');
    $header_video_poster = get_theme_mod('aqualuxe_header_video_poster', '');
    
    if (!$header_video_url) {
        return;
    }
    
    ?>
    <div class="header-video">
        <video autoplay muted loop playsinline <?php echo $header_video_poster ? 'poster="' . esc_url(wp_get_attachment_image_url($header_video_poster, 'full')) . '"' : ''; ?>>
            <source src="<?php echo esc_url($header_video_url); ?>" type="video/mp4">
        </video>
        <div class="header-video-overlay"></div>
        <div class="header-video-content">
            <div class="container">
                <?php echo wp_kses_post(get_theme_mod('aqualuxe_header_video_content', '')); ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display header content
 */
function aqualuxe_header_content() {
    if (!is_front_page()) {
        return;
    }
    
    $header_content_type = get_theme_mod('aqualuxe_header_content_type', 'none');
    
    switch ($header_content_type) {
        case 'slider':
            aqualuxe_header_slider();
            break;
        case 'video':
            aqualuxe_header_video();
            break;
        case 'banner':
            aqualuxe_header_banner();
            break;
        case 'none':
        default:
            break;
    }
}

/**
 * Display header
 */
function aqualuxe_display_header() {
    aqualuxe_header();
    aqualuxe_header_content();
    aqualuxe_header_widgets();
    aqualuxe_page_header_wrapper();
}