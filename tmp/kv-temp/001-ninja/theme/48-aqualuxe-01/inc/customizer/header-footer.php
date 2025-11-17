<?php
/**
 * AquaLuxe Theme Header and Footer Customization
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

/**
 * Header Layout Component
 * 
 * Renders the header layout based on customizer settings
 *
 * @return void
 */
function aqualuxe_header_layout() {
    $header_layout = aqualuxe_get_option('header_layout', 'default');
    
    switch ($header_layout) {
        case 'centered':
            get_template_part('template-parts/header/header', 'centered');
            break;
        case 'transparent':
            get_template_part('template-parts/header/header', 'transparent');
            break;
        case 'minimal':
            get_template_part('template-parts/header/header', 'minimal');
            break;
        case 'split':
            get_template_part('template-parts/header/header', 'split');
            break;
        default:
            get_template_part('template-parts/header/header', 'main');
            break;
    }
}

/**
 * Footer Layout Component
 * 
 * Renders the footer layout based on customizer settings
 *
 * @return void
 */
function aqualuxe_footer_layout() {
    $footer_layout = aqualuxe_get_option('footer_layout', 'default');
    
    switch ($footer_layout) {
        case 'three-column':
            get_template_part('template-parts/footer/footer', 'three-column');
            break;
        case 'two-column':
            get_template_part('template-parts/footer/footer', 'two-column');
            break;
        case 'one-column':
            get_template_part('template-parts/footer/footer', 'one-column');
            break;
        case 'custom':
            get_template_part('template-parts/footer/footer', 'custom');
            break;
        default:
            get_template_part('template-parts/footer/footer', 'widgets');
            break;
    }
}

/**
 * Header Top Bar Component
 * 
 * Renders the header top bar based on customizer settings
 *
 * @return void
 */
function aqualuxe_header_top_bar() {
    if (aqualuxe_get_option('enable_top_bar', true)) {
        get_template_part('template-parts/header/header', 'top-bar');
    }
}

/**
 * Header Bottom Bar Component
 * 
 * Renders the header bottom bar based on customizer settings
 *
 * @return void
 */
function aqualuxe_header_bottom_bar() {
    if (aqualuxe_get_option('enable_bottom_bar', true)) {
        get_template_part('template-parts/header/header', 'bottom-bar');
    }
}

/**
 * Header Search Component
 * 
 * Renders the header search based on customizer settings
 *
 * @return void
 */
function aqualuxe_header_search() {
    if (aqualuxe_get_option('header_search', true)) {
        ?>
        <div class="header-search">
            <button class="search-toggle" aria-expanded="false" aria-label="<?php esc_attr_e('Toggle Search', 'aqualuxe'); ?>">
                <i class="fas fa-search"></i>
            </button>
            <div class="search-dropdown">
                <?php get_search_form(); ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Header Cart Component
 * 
 * Renders the header cart based on customizer settings
 *
 * @return void
 */
function aqualuxe_header_cart() {
    if (aqualuxe_get_option('header_cart', true) && aqualuxe_is_woocommerce_active()) {
        get_template_part('template-parts/woocommerce/mini', 'cart');
    }
}

/**
 * Header Account Component
 * 
 * Renders the header account based on customizer settings
 *
 * @return void
 */
function aqualuxe_header_account() {
    if (aqualuxe_get_option('header_account', true) && aqualuxe_is_woocommerce_active()) {
        ?>
        <div class="header-account">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" class="account-link">
                <i class="fas fa-user"></i>
                <span class="screen-reader-text"><?php esc_html_e('My Account', 'aqualuxe'); ?></span>
            </a>
        </div>
        <?php
    }
}

/**
 * Header Wishlist Component
 * 
 * Renders the header wishlist based on customizer settings
 *
 * @return void
 */
function aqualuxe_header_wishlist() {
    if (aqualuxe_get_option('header_wishlist', true) && aqualuxe_is_woocommerce_active() && function_exists('YITH_WCWL')) {
        ?>
        <div class="header-wishlist">
            <a href="<?php echo esc_url(YITH_WCWL()->get_wishlist_url()); ?>" class="wishlist-link">
                <i class="fas fa-heart"></i>
                <span class="wishlist-count"><?php echo esc_html(YITH_WCWL()->count_products()); ?></span>
                <span class="screen-reader-text"><?php esc_html_e('Wishlist', 'aqualuxe'); ?></span>
            </a>
        </div>
        <?php
    }
}

/**
 * Header Contact Component
 * 
 * Renders the header contact based on customizer settings
 *
 * @return void
 */
function aqualuxe_header_contact() {
    if (aqualuxe_get_option('header_contact', true)) {
        $contact_phone = aqualuxe_get_option('contact_phone', '');
        $contact_email = aqualuxe_get_option('contact_email', '');
        
        if ($contact_phone || $contact_email) {
            ?>
            <div class="header-contact">
                <?php if ($contact_phone) : ?>
                    <div class="header-contact-phone">
                        <i class="fas fa-phone"></i>
                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_phone)); ?>">
                            <?php echo esc_html($contact_phone); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ($contact_email) : ?>
                    <div class="header-contact-email">
                        <i class="fas fa-envelope"></i>
                        <a href="mailto:<?php echo esc_attr($contact_email); ?>">
                            <?php echo esc_html($contact_email); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
            <?php
        }
    }
}

/**
 * Footer Widgets Component
 * 
 * Renders the footer widgets based on customizer settings
 *
 * @return void
 */
function aqualuxe_footer_widgets() {
    if (aqualuxe_get_option('footer_widgets', true)) {
        $footer_layout = aqualuxe_get_option('footer_layout', 'default');
        $columns = 4; // Default
        
        switch ($footer_layout) {
            case 'three-column':
                $columns = 3;
                break;
            case 'two-column':
                $columns = 2;
                break;
            case 'one-column':
                $columns = 1;
                break;
            case 'custom':
                // Custom layout handled separately
                return;
            default:
                $columns = 4;
                break;
        }
        
        // Check if footer sidebars are active
        $has_active_sidebar = false;
        for ($i = 1; $i <= $columns; $i++) {
            if (is_active_sidebar('footer-' . $i)) {
                $has_active_sidebar = true;
                break;
            }
        }
        
        if ($has_active_sidebar) {
            ?>
            <div class="footer-widgets-container">
                <div class="container mx-auto px-4">
                    <div class="footer-widgets-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo esc_attr($columns); ?> gap-8">
                        <?php
                        for ($i = 1; $i <= $columns; $i++) {
                            if (is_active_sidebar('footer-' . $i)) {
                                ?>
                                <div class="footer-widget footer-widget-<?php echo esc_attr($i); ?>">
                                    <?php dynamic_sidebar('footer-' . $i); ?>
                                </div>
                                <?php
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

/**
 * Footer Logo Component
 * 
 * Renders the footer logo based on customizer settings
 *
 * @return void
 */
function aqualuxe_footer_logo() {
    $footer_logo_id = aqualuxe_get_option('footer_logo', '');
    
    if ($footer_logo_id) {
        $logo_url = wp_get_attachment_image_url($footer_logo_id, 'full');
        $logo_alt = get_bloginfo('name');
        
        if ($logo_url) {
            ?>
            <div class="footer-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" rel="home">
                    <img src="<?php echo esc_url($logo_url); ?>" alt="<?php echo esc_attr($logo_alt); ?>" class="custom-logo">
                </a>
            </div>
            <?php
        }
    }
}

/**
 * Footer Menu Component
 * 
 * Renders the footer menu based on customizer settings
 *
 * @return void
 */
function aqualuxe_footer_menu() {
    if (aqualuxe_get_option('footer_menu', true) && has_nav_menu('footer')) {
        ?>
        <nav class="footer-navigation" aria-label="<?php esc_attr_e('Footer Menu', 'aqualuxe'); ?>">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'footer',
                'menu_class'     => 'footer-menu flex flex-wrap justify-center',
                'container'      => false,
                'depth'          => 1,
                'fallback_cb'    => false,
            ));
            ?>
        </nav>
        <?php
    }
}

/**
 * Footer Payment Icons Component
 * 
 * Renders the footer payment icons based on customizer settings
 *
 * @return void
 */
function aqualuxe_footer_payment_icons() {
    if (aqualuxe_get_option('footer_payment', true)) {
        ?>
        <div class="footer-payment-icons">
            <div class="payment-icons-wrapper flex flex-wrap justify-center gap-3">
                <div class="payment-icon payment-visa">
                    <i class="fab fa-cc-visa" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Visa', 'aqualuxe'); ?></span>
                </div>
                <div class="payment-icon payment-mastercard">
                    <i class="fab fa-cc-mastercard" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Mastercard', 'aqualuxe'); ?></span>
                </div>
                <div class="payment-icon payment-amex">
                    <i class="fab fa-cc-amex" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('American Express', 'aqualuxe'); ?></span>
                </div>
                <div class="payment-icon payment-paypal">
                    <i class="fab fa-cc-paypal" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('PayPal', 'aqualuxe'); ?></span>
                </div>
                <div class="payment-icon payment-apple">
                    <i class="fab fa-apple-pay" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Apple Pay', 'aqualuxe'); ?></span>
                </div>
                <div class="payment-icon payment-google">
                    <i class="fab fa-google-pay" aria-hidden="true"></i>
                    <span class="screen-reader-text"><?php esc_html_e('Google Pay', 'aqualuxe'); ?></span>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Footer Copyright Component
 * 
 * Renders the footer copyright based on customizer settings
 *
 * @return void
 */
function aqualuxe_footer_copyright() {
    $copyright_text = aqualuxe_get_option('copyright_text', '&copy; ' . date('Y') . ' ' . get_bloginfo('name') . '. ' . __('All rights reserved.', 'aqualuxe'));
    
    if ($copyright_text) {
        ?>
        <div class="footer-copyright">
            <?php echo wp_kses_post($copyright_text); ?>
        </div>
        <?php
    }
}

/**
 * Newsletter Form Component
 * 
 * Renders the newsletter form based on customizer settings
 *
 * @return void
 */
function aqualuxe_newsletter_form() {
    if (aqualuxe_get_option('enable_newsletter', true)) {
        $newsletter_title = aqualuxe_get_option('newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
        $newsletter_description = aqualuxe_get_option('newsletter_description', __('Stay updated with our latest news and offers.', 'aqualuxe'));
        $newsletter_form_action = aqualuxe_get_option('newsletter_form_action', '#');
        $newsletter_button_text = aqualuxe_get_option('newsletter_button_text', __('Subscribe', 'aqualuxe'));
        
        ?>
        <div class="newsletter-section py-12 md:py-16 bg-primary text-white">
            <div class="container mx-auto px-4">
                <div class="newsletter-content max-w-3xl mx-auto text-center">
                    <?php if ($newsletter_title) : ?>
                        <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-4">
                            <?php echo esc_html($newsletter_title); ?>
                        </h2>
                    <?php endif; ?>
                    
                    <?php if ($newsletter_description) : ?>
                        <p class="mb-6 text-white/80">
                            <?php echo esc_html($newsletter_description); ?>
                        </p>
                    <?php endif; ?>
                    
                    <div class="newsletter-form">
                        <form action="<?php echo esc_url($newsletter_form_action); ?>" method="post" class="flex flex-col md:flex-row gap-4 max-w-xl mx-auto">
                            <input type="email" name="email" placeholder="<?php esc_attr_e('Your Email Address', 'aqualuxe'); ?>" class="flex-grow px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
                            <button type="submit" class="px-6 py-3 bg-accent hover:bg-accent-dark text-dark font-medium rounded-lg transition-colors">
                                <?php echo esc_html($newsletter_button_text); ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

/**
 * Back to Top Button Component
 * 
 * Renders the back to top button based on customizer settings
 *
 * @return void
 */
function aqualuxe_back_to_top() {
    if (aqualuxe_get_option('enable_back_to_top', true)) {
        get_template_part('template-parts/footer/back-to-top');
    }
}

/**
 * Add header and footer hooks
 */
function aqualuxe_header_footer_hooks() {
    // Header hooks
    add_action('aqualuxe_header', 'aqualuxe_header_layout', 10);
    add_action('aqualuxe_header_top', 'aqualuxe_header_top_bar', 10);
    add_action('aqualuxe_header_main', 'aqualuxe_header_search', 20);
    add_action('aqualuxe_header_main', 'aqualuxe_header_cart', 30);
    add_action('aqualuxe_header_main', 'aqualuxe_header_account', 40);
    add_action('aqualuxe_header_main', 'aqualuxe_header_wishlist', 50);
    add_action('aqualuxe_header_bottom', 'aqualuxe_header_bottom_bar', 10);
    add_action('aqualuxe_header_top_bar', 'aqualuxe_header_contact', 10);
    
    // Footer hooks
    add_action('aqualuxe_footer', 'aqualuxe_newsletter_form', 10);
    add_action('aqualuxe_footer', 'aqualuxe_footer_layout', 20);
    add_action('aqualuxe_footer_widgets', 'aqualuxe_footer_widgets', 10);
    add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_logo', 10);
    add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_menu', 20);
    add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_copyright', 30);
    add_action('aqualuxe_footer_bottom', 'aqualuxe_footer_payment_icons', 40);
    add_action('aqualuxe_after_footer', 'aqualuxe_back_to_top', 10);
}
add_action('after_setup_theme', 'aqualuxe_header_footer_hooks');

/**
 * Add custom CSS classes to body based on header and footer settings
 *
 * @param array $classes Body classes
 * @return array Modified body classes
 */
function aqualuxe_header_footer_body_classes($classes) {
    // Header layout
    $header_layout = aqualuxe_get_option('header_layout', 'default');
    $classes[] = 'header-layout-' . $header_layout;
    
    // Sticky header
    if (aqualuxe_get_option('sticky_header', true)) {
        $classes[] = 'has-sticky-header';
    }
    
    // Footer layout
    $footer_layout = aqualuxe_get_option('footer_layout', 'default');
    $classes[] = 'footer-layout-' . $footer_layout;
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_header_footer_body_classes');