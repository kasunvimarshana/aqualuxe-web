<?php
/**
 * The header for our theme
 * 
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main"><?php esc_html_e('Skip to content', KV_THEME_TEXTDOMAIN); ?></a>

<div id="page" class="site">
    <header id="masthead" class="site-header" role="banner">
        <div class="header-top">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="header-contact">
                            <?php
                            $contact_email = kv_get_theme_option('contact_email', '');
                            $contact_phone = kv_get_theme_option('contact_phone', '');
                            
                            if ($contact_email) : ?>
                                <span class="contact-item">
                                    <i class="fas fa-envelope" aria-hidden="true"></i>
                                    <a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a>
                                </span>
                            <?php endif;
                            
                            if ($contact_phone) : ?>
                                <span class="contact-item">
                                    <i class="fas fa-phone" aria-hidden="true"></i>
                                    <a href="tel:<?php echo esc_attr($contact_phone); ?>"><?php echo esc_html($contact_phone); ?></a>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="header-utilities">
                            <?php
                            // Language switcher for multilingual sites
                            if (function_exists('icl_get_languages')) {
                                $languages = icl_get_languages('skip_missing=0&orderby=code');
                                if (!empty($languages)) : ?>
                                    <div class="language-switcher">
                                        <label for="language-select" class="sr-only"><?php esc_html_e('Select Language', KV_THEME_TEXTDOMAIN); ?></label>
                                        <select id="language-select" onchange="window.location.href=this.value;">
                                            <?php foreach ($languages as $lang) : ?>
                                                <option value="<?php echo esc_url($lang['url']); ?>" <?php selected($lang['active']); ?>>
                                                    <?php echo esc_html($lang['native_name']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                <?php endif;
                            }
                            
                            // Currency switcher for multi-currency sites
                            $currencies = kv_get_theme_option('available_currencies', ['USD']);
                            if (count($currencies) > 1) : ?>
                                <div class="currency-switcher">
                                    <label for="currency-select" class="sr-only"><?php esc_html_e('Select Currency', KV_THEME_TEXTDOMAIN); ?></label>
                                    <select id="currency-select" onchange="kvSetCurrency(this.value);">
                                        <?php foreach ($currencies as $currency) : ?>
                                            <option value="<?php echo esc_attr($currency); ?>" <?php selected(kv_get_current_currency(), $currency); ?>>
                                                <?php echo esc_html($currency); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php endif;
                            
                            // User account links
                            if (is_user_logged_in()) : ?>
                                <div class="user-menu">
                                    <a href="<?php echo esc_url(get_edit_user_link()); ?>" class="user-account">
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                        <?php echo esc_html(wp_get_current_user()->display_name); ?>
                                    </a>
                                    <a href="<?php echo esc_url(wp_logout_url()); ?>" class="logout-link">
                                        <i class="fas fa-sign-out-alt" aria-hidden="true"></i>
                                        <?php esc_html_e('Logout', KV_THEME_TEXTDOMAIN); ?>
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="auth-links">
                                    <a href="<?php echo esc_url(wp_login_url()); ?>" class="login-link">
                                        <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                                        <?php esc_html_e('Login', KV_THEME_TEXTDOMAIN); ?>
                                    </a>
                                    <a href="<?php echo esc_url(wp_registration_url()); ?>" class="register-link">
                                        <i class="fas fa-user-plus" aria-hidden="true"></i>
                                        <?php esc_html_e('Register', KV_THEME_TEXTDOMAIN); ?>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Social Media Links -->
                            <?php
                            $social_networks = ['facebook', 'twitter', 'instagram', 'linkedin', 'youtube'];
                            $has_social = false;
                            
                            foreach ($social_networks as $network) {
                                if (kv_get_theme_option("social_{$network}", '')) {
                                    $has_social = true;
                                    break;
                                }
                            }
                            
                            if ($has_social) : ?>
                                <div class="social-links">
                                    <?php foreach ($social_networks as $network) :
                                        $url = kv_get_theme_option("social_{$network}", '');
                                        if ($url) : ?>
                                            <a href="<?php echo esc_url($url); ?>" 
                                               class="social-link social-<?php echo esc_attr($network); ?>" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               aria-label="<?php printf(esc_attr__('Follow us on %s', KV_THEME_TEXTDOMAIN), ucfirst($network)); ?>">
                                                <i class="fab fa-<?php echo esc_attr($network); ?>" aria-hidden="true"></i>
                                            </a>
                                        <?php endif;
                                    endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="header-main">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <div class="site-branding">
                            <?php
                            if (has_custom_logo()) {
                                the_custom_logo();
                            } else {
                                if (is_front_page() && is_home()) : ?>
                                    <h1 class="site-title">
                                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                            <?php bloginfo('name'); ?>
                                        </a>
                                    </h1>
                                <?php else : ?>
                                    <p class="site-title">
                                        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                                            <?php bloginfo('name'); ?>
                                        </a>
                                    </p>
                                <?php endif;
                                
                                $description = get_bloginfo('description', 'display');
                                if ($description || is_customize_preview()) : ?>
                                    <p class="site-description"><?php echo $description; ?></p>
                                <?php endif;
                            } ?>
                        </div>
                    </div>
                    
                    <div class="col-lg-7">
                        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e('Primary Navigation', KV_THEME_TEXTDOMAIN); ?>">
                            <button class="menu-toggle mobile-menu-toggle" 
                                    aria-controls="primary-menu" 
                                    aria-expanded="false"
                                    aria-label="<?php esc_attr_e('Toggle primary menu', KV_THEME_TEXTDOMAIN); ?>">
                                <span class="hamburger">
                                    <span class="hamburger-line"></span>
                                    <span class="hamburger-line"></span>
                                    <span class="hamburger-line"></span>
                                </span>
                                <span class="menu-toggle-text"><?php esc_html_e('Menu', KV_THEME_TEXTDOMAIN); ?></span>
                            </button>
                            
                            <?php
                            wp_nav_menu(array(
                                'theme_location' => 'primary',
                                'menu_id'        => 'primary-menu',
                                'menu_class'     => 'nav-menu',
                                'container'      => false,
                                'fallback_cb'    => 'kv_default_menu_fallback',
                                'walker'         => new KV_Walker_Nav_Menu(),
                            ));
                            ?>
                        </nav>
                    </div>
                    
                    <div class="col-lg-2">
                        <div class="header-actions">
                            <!-- Search -->
                            <div class="search-container">
                                <button class="search-toggle" 
                                        aria-controls="search-form" 
                                        aria-expanded="false"
                                        aria-label="<?php esc_attr_e('Toggle search', KV_THEME_TEXTDOMAIN); ?>">
                                    <i class="fas fa-search" aria-hidden="true"></i>
                                </button>
                                
                                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                                    <label for="search-field" class="sr-only"><?php esc_html_e('Search for:', KV_THEME_TEXTDOMAIN); ?></label>
                                    <input type="search" 
                                           id="search-field" 
                                           class="search-field" 
                                           placeholder="<?php esc_attr_e('Search...', KV_THEME_TEXTDOMAIN); ?>" 
                                           value="<?php echo get_search_query(); ?>" 
                                           name="s" />
                                    <button type="submit" class="search-submit">
                                        <i class="fas fa-search" aria-hidden="true"></i>
                                        <span class="sr-only"><?php esc_html_e('Search', KV_THEME_TEXTDOMAIN); ?></span>
                                    </button>
                                </form>
                                
                                <div class="search-results" style="display: none;"></div>
                            </div>
                            
                            <!-- Shopping Cart (if WooCommerce is active) -->
                            <?php if (class_exists('WooCommerce')) : ?>
                                <div class="cart-container">
                                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>" 
                                       class="cart-link" 
                                       aria-label="<?php esc_attr_e('View cart', KV_THEME_TEXTDOMAIN); ?>">
                                        <i class="fas fa-shopping-cart" aria-hidden="true"></i>
                                        <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                                    </a>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Theme Switcher -->
                            <?php if (kv_get_theme_option('enable_theme_switcher', false)) : ?>
                                <div class="theme-switcher">
                                    <button class="theme-toggle" 
                                            aria-label="<?php esc_attr_e('Toggle theme', KV_THEME_TEXTDOMAIN); ?>"
                                            onclick="kvToggleTheme()">
                                        <i class="fas fa-adjust" aria-hidden="true"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php
        // Secondary navigation (if set)
        if (has_nav_menu('secondary')) : ?>
            <div class="header-secondary">
                <div class="container">
                    <nav class="secondary-navigation" role="navigation" aria-label="<?php esc_attr_e('Secondary Navigation', KV_THEME_TEXTDOMAIN); ?>">
                        <?php
                        wp_nav_menu(array(
                            'theme_location' => 'secondary',
                            'menu_class'     => 'secondary-menu',
                            'container'      => false,
                            'depth'          => 1,
                        ));
                        ?>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Breadcrumbs (except on front page)
        if (!is_front_page() && kv_get_theme_option('show_breadcrumbs', true)) : ?>
            <div class="breadcrumbs-container">
                <div class="container">
                    <nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb Navigation', KV_THEME_TEXTDOMAIN); ?>">
                        <?php kv_render_breadcrumbs(); ?>
                    </nav>
                </div>
            </div>
        <?php endif; ?>
    </header>

    <div id="content" class="site-content">
        
        <?php
        // Page header for archives and special pages
        if (!is_front_page() && (is_archive() || is_search() || is_404())) :
            kv_render_page_header();
        endif;
        ?>

<script>
// Theme switching functionality
function kvToggleTheme() {
    const currentTheme = document.body.getAttribute('data-theme') || 'light';
    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
    
    document.body.setAttribute('data-theme', newTheme);
    localStorage.setItem('kv-theme', newTheme);
    
    // Trigger custom event
    document.dispatchEvent(new CustomEvent('themeChanged', { detail: { theme: newTheme } }));
}

// Currency switching functionality
function kvSetCurrency(currency) {
    // Set cookie
    document.cookie = `kv_currency=${currency}; path=/; max-age=${30 * 24 * 60 * 60}`; // 30 days
    
    // Reload page to apply currency
    window.location.reload();
}

// Load saved theme
(function() {
    const savedTheme = localStorage.getItem('kv-theme');
    if (savedTheme) {
        document.body.setAttribute('data-theme', savedTheme);
    }
})();
</script>
