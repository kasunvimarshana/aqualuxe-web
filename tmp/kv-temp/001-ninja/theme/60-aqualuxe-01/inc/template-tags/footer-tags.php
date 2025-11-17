<?php
/**
 * AquaLuxe Footer Template Tags
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Display site footer
 */
function aqualuxe_site_footer() {
    $footer_layout = aqualuxe_get_footer_layout();
    
    // Get footer template based on layout
    aqualuxe_get_template_part('template-parts/footer/footer', $footer_layout);
}

/**
 * Display footer widgets
 */
function aqualuxe_footer_widgets() {
    $show_footer_widgets = get_theme_mod('aqualuxe_show_footer_widgets', true);
    
    if (!$show_footer_widgets) {
        return;
    }
    
    $footer_columns = get_theme_mod('aqualuxe_footer_columns', 4);
    
    // Check if any footer widget area is active
    $has_active_widgets = false;
    for ($i = 1; $i <= $footer_columns; $i++) {
        if (is_active_sidebar('footer-' . $i)) {
            $has_active_widgets = true;
            break;
        }
    }
    
    if (!$has_active_widgets) {
        return;
    }
    
    ?>
    <div class="footer-widgets">
        <div class="container">
            <div class="row">
                <?php
                // Calculate column width based on number of columns
                $column_class = 'col-lg-' . (12 / $footer_columns);
                
                for ($i = 1; $i <= $footer_columns; $i++) :
                    if (is_active_sidebar('footer-' . $i)) :
                        ?>
                        <div class="<?php echo esc_attr($column_class); ?> footer-column footer-column-<?php echo esc_attr($i); ?>">
                            <?php dynamic_sidebar('footer-' . $i); ?>
                        </div>
                        <?php
                    endif;
                endfor;
                ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display footer logo
 */
function aqualuxe_footer_logo() {
    $show_footer_logo = get_theme_mod('aqualuxe_show_footer_logo', true);
    
    if (!$show_footer_logo) {
        return;
    }
    
    $footer_logo_id = get_theme_mod('aqualuxe_footer_logo');
    
    if ($footer_logo_id) {
        echo '<div class="footer-logo">';
        echo '<a href="' . esc_url(home_url('/')) . '" rel="home">';
        echo wp_get_attachment_image($footer_logo_id, 'full', false, ['class' => 'custom-logo']);
        echo '</a>';
        echo '</div>';
    } else {
        echo '<div class="footer-logo">';
        aqualuxe_site_logo();
        echo '</div>';
    }
}

/**
 * Display footer info
 */
function aqualuxe_footer_info() {
    $footer_info = get_theme_mod('aqualuxe_footer_info', '');
    
    if (!$footer_info) {
        return;
    }
    
    echo '<div class="footer-info">';
    echo wp_kses_post($footer_info);
    echo '</div>';
}

/**
 * Display footer contact info
 */
function aqualuxe_footer_contact_info() {
    $show_footer_contact_info = get_theme_mod('aqualuxe_show_footer_contact_info', true);
    
    if (!$show_footer_contact_info) {
        return;
    }
    
    $contact_info = aqualuxe_get_contact_info();
    
    if (!$contact_info) {
        return;
    }
    
    echo '<div class="footer-contact-info">';
    
    foreach ($contact_info as $key => $contact) {
        echo '<div class="footer-contact-info-item footer-contact-info-' . esc_attr($key) . '">';
        
        if (!empty($contact['url'])) {
            echo '<a href="' . esc_url($contact['url']) . '" class="footer-contact-info-link">';
        }
        
        echo '<i class="' . esc_attr($contact['icon']) . '"></i>';
        echo '<span class="footer-contact-info-text">' . esc_html($contact['value']) . '</span>';
        
        if (!empty($contact['url'])) {
            echo '</a>';
        }
        
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Display footer social links
 */
function aqualuxe_footer_social_links() {
    $show_footer_social_links = get_theme_mod('aqualuxe_show_footer_social_links', true);
    
    if (!$show_footer_social_links) {
        return;
    }
    
    $social_links = aqualuxe_get_social_links();
    
    if (!$social_links) {
        return;
    }
    
    echo '<div class="footer-social-links">';
    
    if (get_theme_mod('aqualuxe_footer_social_title', '')) {
        echo '<h3 class="footer-social-title">' . esc_html(get_theme_mod('aqualuxe_footer_social_title')) . '</h3>';
    }
    
    echo '<div class="social-links">';
    
    foreach ($social_links as $key => $social) {
        echo '<a href="' . esc_url($social['url']) . '" target="_blank" rel="noopener noreferrer" class="social-link social-' . esc_attr($key) . '">';
        echo '<i class="' . esc_attr($social['icon']) . '"></i>';
        echo '<span class="screen-reader-text">' . esc_html($social['label']) . '</span>';
        echo '</a>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display footer menu
 */
function aqualuxe_footer_menu() {
    $show_footer_menu = get_theme_mod('aqualuxe_show_footer_menu', true);
    
    if (!$show_footer_menu) {
        return;
    }
    
    if (has_nav_menu('footer')) {
        echo '<div class="footer-menu-wrapper">';
        
        if (get_theme_mod('aqualuxe_footer_menu_title', '')) {
            echo '<h3 class="footer-menu-title">' . esc_html(get_theme_mod('aqualuxe_footer_menu_title')) . '</h3>';
        }
        
        wp_nav_menu([
            'theme_location' => 'footer',
            'menu_id' => 'footer-menu',
            'container' => 'nav',
            'container_class' => 'footer-navigation',
            'menu_class' => 'footer-menu',
            'depth' => 1,
        ]);
        
        echo '</div>';
    }
}

/**
 * Display footer newsletter
 */
function aqualuxe_footer_newsletter() {
    $show_footer_newsletter = get_theme_mod('aqualuxe_show_footer_newsletter', false);
    
    if (!$show_footer_newsletter) {
        return;
    }
    
    $newsletter_title = get_theme_mod('aqualuxe_footer_newsletter_title', __('Subscribe to Our Newsletter', 'aqualuxe'));
    $newsletter_text = get_theme_mod('aqualuxe_footer_newsletter_text', __('Subscribe to our newsletter and get 10% off your first purchase', 'aqualuxe'));
    $newsletter_form = get_theme_mod('aqualuxe_footer_newsletter_form', '');
    
    if (!$newsletter_form) {
        return;
    }
    
    ?>
    <div class="footer-newsletter">
        <div class="container">
            <div class="footer-newsletter-inner">
                <div class="footer-newsletter-content">
                    <?php if ($newsletter_title) : ?>
                        <h3 class="footer-newsletter-title"><?php echo esc_html($newsletter_title); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ($newsletter_text) : ?>
                        <div class="footer-newsletter-text"><?php echo wp_kses_post($newsletter_text); ?></div>
                    <?php endif; ?>
                </div>
                <div class="footer-newsletter-form">
                    <?php echo do_shortcode($newsletter_form); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display footer payment methods
 */
function aqualuxe_footer_payment_methods() {
    $show_footer_payment_methods = get_theme_mod('aqualuxe_show_footer_payment_methods', false);
    
    if (!$show_footer_payment_methods) {
        return;
    }
    
    $payment_methods = get_theme_mod('aqualuxe_footer_payment_methods', '');
    
    if (!$payment_methods) {
        return;
    }
    
    $payment_methods = explode(',', $payment_methods);
    
    if (empty($payment_methods)) {
        return;
    }
    
    echo '<div class="footer-payment-methods">';
    
    if (get_theme_mod('aqualuxe_footer_payment_title', '')) {
        echo '<h3 class="footer-payment-title">' . esc_html(get_theme_mod('aqualuxe_footer_payment_title')) . '</h3>';
    }
    
    echo '<div class="payment-methods">';
    
    foreach ($payment_methods as $payment_method) {
        echo wp_get_attachment_image($payment_method, 'full', false, ['class' => 'payment-method']);
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display footer bottom
 */
function aqualuxe_footer_bottom() {
    $show_footer_bottom = get_theme_mod('aqualuxe_show_footer_bottom', true);
    
    if (!$show_footer_bottom) {
        return;
    }
    
    ?>
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <div class="footer-bottom-left">
                    <?php aqualuxe_copyright_text(); ?>
                </div>
                <div class="footer-bottom-right">
                    <?php aqualuxe_footer_credits(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display back to top button
 */
function aqualuxe_back_to_top_button() {
    $show_back_to_top = get_theme_mod('aqualuxe_show_back_to_top', true);
    
    if (!$show_back_to_top) {
        return;
    }
    
    ?>
    <a href="#" id="back-to-top" class="back-to-top" aria-label="<?php esc_attr_e('Back to top', 'aqualuxe'); ?>">
        <i class="fas fa-chevron-up"></i>
    </a>
    <?php
}

/**
 * Display footer partners
 */
function aqualuxe_footer_partners() {
    $show_footer_partners = get_theme_mod('aqualuxe_show_footer_partners', false);
    
    if (!$show_footer_partners) {
        return;
    }
    
    $partners = get_theme_mod('aqualuxe_footer_partners', '');
    
    if (!$partners) {
        return;
    }
    
    $partners = explode(',', $partners);
    
    if (empty($partners)) {
        return;
    }
    
    ?>
    <div class="footer-partners">
        <div class="container">
            <?php if (get_theme_mod('aqualuxe_footer_partners_title', '')) : ?>
                <h3 class="footer-partners-title"><?php echo esc_html(get_theme_mod('aqualuxe_footer_partners_title')); ?></h3>
            <?php endif; ?>
            
            <div class="partners-slider">
                <?php foreach ($partners as $partner) : ?>
                    <div class="partner-item">
                        <?php echo wp_get_attachment_image($partner, 'full', false, ['class' => 'partner-logo']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display footer features
 */
function aqualuxe_footer_features() {
    $show_footer_features = get_theme_mod('aqualuxe_show_footer_features', false);
    
    if (!$show_footer_features) {
        return;
    }
    
    $features = get_theme_mod('aqualuxe_footer_features', '');
    
    if (!$features) {
        return;
    }
    
    $features = json_decode($features, true);
    
    if (empty($features)) {
        return;
    }
    
    ?>
    <div class="footer-features">
        <div class="container">
            <div class="row">
                <?php foreach ($features as $feature) : ?>
                    <div class="col-md-3">
                        <div class="footer-feature">
                            <?php if (!empty($feature['icon'])) : ?>
                                <div class="footer-feature-icon">
                                    <i class="<?php echo esc_attr($feature['icon']); ?>"></i>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($feature['title'])) : ?>
                                <h4 class="footer-feature-title"><?php echo esc_html($feature['title']); ?></h4>
                            <?php endif; ?>
                            
                            <?php if (!empty($feature['text'])) : ?>
                                <div class="footer-feature-text"><?php echo wp_kses_post($feature['text']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display footer banner
 */
function aqualuxe_footer_banner() {
    $show_footer_banner = get_theme_mod('aqualuxe_show_footer_banner', false);
    
    if (!$show_footer_banner) {
        return;
    }
    
    $banner_title = get_theme_mod('aqualuxe_footer_banner_title', '');
    $banner_text = get_theme_mod('aqualuxe_footer_banner_text', '');
    $banner_button_text = get_theme_mod('aqualuxe_footer_banner_button_text', '');
    $banner_button_url = get_theme_mod('aqualuxe_footer_banner_button_url', '');
    $banner_background = get_theme_mod('aqualuxe_footer_banner_background', '');
    
    if (!$banner_title && !$banner_text) {
        return;
    }
    
    ?>
    <div class="footer-banner" <?php echo $banner_background ? 'style="background-image: url(' . esc_url(wp_get_attachment_image_url($banner_background, 'full')) . ')"' : ''; ?>>
        <div class="container">
            <div class="footer-banner-inner">
                <div class="footer-banner-content">
                    <?php if ($banner_title) : ?>
                        <h3 class="footer-banner-title"><?php echo esc_html($banner_title); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ($banner_text) : ?>
                        <div class="footer-banner-text"><?php echo wp_kses_post($banner_text); ?></div>
                    <?php endif; ?>
                </div>
                
                <?php if ($banner_button_text && $banner_button_url) : ?>
                    <div class="footer-banner-action">
                        <a href="<?php echo esc_url($banner_button_url); ?>" class="btn btn-primary"><?php echo esc_html($banner_button_text); ?></a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display footer top
 */
function aqualuxe_footer_top() {
    $show_footer_top = get_theme_mod('aqualuxe_show_footer_top', false);
    
    if (!$show_footer_top) {
        return;
    }
    
    ?>
    <div class="footer-top">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <?php aqualuxe_footer_logo(); ?>
                    <?php aqualuxe_footer_info(); ?>
                    <?php aqualuxe_footer_social_links(); ?>
                </div>
                <div class="col-md-4">
                    <?php aqualuxe_footer_contact_info(); ?>
                </div>
                <div class="col-md-4">
                    <?php aqualuxe_footer_menu(); ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Display footer middle
 */
function aqualuxe_footer_middle() {
    $show_footer_middle = get_theme_mod('aqualuxe_show_footer_middle', true);
    
    if (!$show_footer_middle) {
        return;
    }
    
    aqualuxe_footer_widgets();
}

/**
 * Display footer
 */
function aqualuxe_footer() {
    ?>
    <footer id="colophon" class="site-footer">
        <?php
        aqualuxe_footer_banner();
        aqualuxe_footer_features();
        aqualuxe_footer_newsletter();
        aqualuxe_footer_partners();
        aqualuxe_footer_top();
        aqualuxe_footer_middle();
        aqualuxe_footer_bottom();
        aqualuxe_back_to_top_button();
        ?>
    </footer>
    <?php
}

/**
 * Display footer widgets area
 */
function aqualuxe_footer_widgets_area() {
    if (!is_active_sidebar('footer-widgets')) {
        return;
    }
    
    ?>
    <div class="footer-widgets-area">
        <div class="container">
            <?php dynamic_sidebar('footer-widgets'); ?>
        </div>
    </div>
    <?php
}

/**
 * Display footer default
 */
function aqualuxe_footer_default() {
    ?>
    <footer id="colophon" class="site-footer footer-default">
        <?php
        aqualuxe_footer_widgets();
        ?>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-bottom-left">
                        <?php aqualuxe_copyright_text(); ?>
                    </div>
                    <div class="footer-bottom-right">
                        <?php aqualuxe_footer_credits(); ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Display footer centered
 */
function aqualuxe_footer_centered() {
    ?>
    <footer id="colophon" class="site-footer footer-centered">
        <div class="footer-main">
            <div class="container">
                <div class="footer-logo-wrapper">
                    <?php aqualuxe_footer_logo(); ?>
                </div>
                <div class="footer-info-wrapper">
                    <?php aqualuxe_footer_info(); ?>
                </div>
                <div class="footer-menu-wrapper">
                    <?php aqualuxe_footer_menu(); ?>
                </div>
                <div class="footer-social-wrapper">
                    <?php aqualuxe_footer_social_links(); ?>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-copyright">
                        <?php aqualuxe_copyright_text(); ?>
                    </div>
                    <div class="footer-credits">
                        <?php aqualuxe_footer_credits(); ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Display footer minimal
 */
function aqualuxe_footer_minimal() {
    ?>
    <footer id="colophon" class="site-footer footer-minimal">
        <div class="container">
            <div class="footer-minimal-inner">
                <div class="footer-minimal-left">
                    <?php aqualuxe_copyright_text(); ?>
                </div>
                <div class="footer-minimal-center">
                    <?php aqualuxe_footer_menu(); ?>
                </div>
                <div class="footer-minimal-right">
                    <?php aqualuxe_footer_social_links(); ?>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Display footer widgets layout
 */
function aqualuxe_footer_widgets_layout() {
    ?>
    <footer id="colophon" class="site-footer footer-widgets-layout">
        <?php
        aqualuxe_footer_widgets();
        ?>
        <div class="footer-bottom">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <?php aqualuxe_footer_logo(); ?>
                    </div>
                    <div class="col-md-4">
                        <?php aqualuxe_copyright_text(); ?>
                    </div>
                    <div class="col-md-4">
                        <?php aqualuxe_footer_social_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Display footer dark
 */
function aqualuxe_footer_dark() {
    ?>
    <footer id="colophon" class="site-footer footer-dark">
        <?php
        aqualuxe_footer_widgets();
        ?>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-bottom-left">
                        <?php aqualuxe_copyright_text(); ?>
                    </div>
                    <div class="footer-bottom-center">
                        <?php aqualuxe_footer_menu(); ?>
                    </div>
                    <div class="footer-bottom-right">
                        <?php aqualuxe_footer_social_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Display footer light
 */
function aqualuxe_footer_light() {
    ?>
    <footer id="colophon" class="site-footer footer-light">
        <?php
        aqualuxe_footer_widgets();
        ?>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-bottom-left">
                        <?php aqualuxe_copyright_text(); ?>
                    </div>
                    <div class="footer-bottom-center">
                        <?php aqualuxe_footer_menu(); ?>
                    </div>
                    <div class="footer-bottom-right">
                        <?php aqualuxe_footer_social_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Display footer complex
 */
function aqualuxe_footer_complex() {
    ?>
    <footer id="colophon" class="site-footer footer-complex">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <?php aqualuxe_footer_logo(); ?>
                        <?php aqualuxe_footer_info(); ?>
                        <?php aqualuxe_footer_social_links(); ?>
                    </div>
                    <div class="col-md-4">
                        <?php aqualuxe_footer_contact_info(); ?>
                    </div>
                    <div class="col-md-4">
                        <?php aqualuxe_footer_newsletter(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-middle">
            <?php aqualuxe_footer_widgets(); ?>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-bottom-left">
                        <?php aqualuxe_copyright_text(); ?>
                    </div>
                    <div class="footer-bottom-center">
                        <?php aqualuxe_footer_menu(); ?>
                    </div>
                    <div class="footer-bottom-right">
                        <?php aqualuxe_footer_payment_methods(); ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Display footer simple
 */
function aqualuxe_footer_simple() {
    ?>
    <footer id="colophon" class="site-footer footer-simple">
        <div class="container">
            <div class="footer-simple-inner">
                <div class="footer-simple-logo">
                    <?php aqualuxe_footer_logo(); ?>
                </div>
                <div class="footer-simple-menu">
                    <?php aqualuxe_footer_menu(); ?>
                </div>
                <div class="footer-simple-social">
                    <?php aqualuxe_footer_social_links(); ?>
                </div>
                <div class="footer-simple-copyright">
                    <?php aqualuxe_copyright_text(); ?>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Display footer modern
 */
function aqualuxe_footer_modern() {
    ?>
    <footer id="colophon" class="site-footer footer-modern">
        <div class="footer-widgets">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <?php aqualuxe_footer_logo(); ?>
                        <?php aqualuxe_footer_info(); ?>
                        <?php aqualuxe_footer_social_links(); ?>
                    </div>
                    <div class="col-md-2">
                        <?php
                        if (is_active_sidebar('footer-1')) {
                            dynamic_sidebar('footer-1');
                        }
                        ?>
                    </div>
                    <div class="col-md-2">
                        <?php
                        if (is_active_sidebar('footer-2')) {
                            dynamic_sidebar('footer-2');
                        }
                        ?>
                    </div>
                    <div class="col-md-4">
                        <?php aqualuxe_footer_newsletter(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-bottom-left">
                        <?php aqualuxe_copyright_text(); ?>
                    </div>
                    <div class="footer-bottom-right">
                        <?php aqualuxe_footer_payment_methods(); ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Display footer classic
 */
function aqualuxe_footer_classic() {
    ?>
    <footer id="colophon" class="site-footer footer-classic">
        <div class="footer-top">
            <div class="container">
                <div class="row">
                    <div class="col-md-4">
                        <?php aqualuxe_footer_logo(); ?>
                        <?php aqualuxe_footer_info(); ?>
                    </div>
                    <div class="col-md-4">
                        <?php aqualuxe_footer_contact_info(); ?>
                    </div>
                    <div class="col-md-4">
                        <?php aqualuxe_footer_social_links(); ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="footer-middle">
            <?php aqualuxe_footer_widgets(); ?>
        </div>
        <div class="footer-bottom">
            <div class="container">
                <div class="footer-bottom-inner">
                    <div class="footer-bottom-left">
                        <?php aqualuxe_copyright_text(); ?>
                    </div>
                    <div class="footer-bottom-right">
                        <?php aqualuxe_footer_menu(); ?>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <?php
}

/**
 * Display footer based on layout
 */
function aqualuxe_display_footer() {
    $footer_layout = aqualuxe_get_footer_layout();
    
    switch ($footer_layout) {
        case 'centered':
            aqualuxe_footer_centered();
            break;
        case 'minimal':
            aqualuxe_footer_minimal();
            break;
        case 'widgets':
            aqualuxe_footer_widgets_layout();
            break;
        case 'dark':
            aqualuxe_footer_dark();
            break;
        case 'light':
            aqualuxe_footer_light();
            break;
        case 'complex':
            aqualuxe_footer_complex();
            break;
        case 'simple':
            aqualuxe_footer_simple();
            break;
        case 'modern':
            aqualuxe_footer_modern();
            break;
        case 'classic':
            aqualuxe_footer_classic();
            break;
        case 'default':
        default:
            aqualuxe_footer_default();
            break;
    }
    
    aqualuxe_back_to_top_button();
}

/**
 * Display footer modals
 */
function aqualuxe_footer_modals() {
    // Add any footer modals here
}

/**
 * Display footer scripts
 */
function aqualuxe_footer_scripts() {
    $custom_js = get_theme_mod('aqualuxe_custom_js', '');
    
    if ($custom_js) {
        echo '<script>' . $custom_js . '</script>';
    }
}

/**
 * Display footer
 */
function aqualuxe_display_footer_complete() {
    aqualuxe_display_footer();
    aqualuxe_footer_modals();
    aqualuxe_footer_scripts();
}