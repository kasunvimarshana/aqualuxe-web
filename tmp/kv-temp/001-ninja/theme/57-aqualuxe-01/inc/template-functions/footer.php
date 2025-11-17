<?php
/**
 * Footer template functions
 *
 * @package AquaLuxe
 */

/**
 * Display the footer widgets
 */
function aqualuxe_footer_widgets() {
    // Check if footer widgets are enabled
    if (!get_theme_mod('aqualuxe_enable_footer_widgets', true)) {
        return;
    }

    // Get the number of footer widget areas
    $footer_widget_areas = apply_filters('aqualuxe_footer_widget_areas', 4);
    
    // Check if any footer widget area is active
    $has_active_widgets = false;
    for ($i = 1; $i <= $footer_widget_areas; $i++) {
        if (is_active_sidebar('footer-' . $i)) {
            $has_active_widgets = true;
            break;
        }
    }
    
    // Return if no footer widget area is active
    if (!$has_active_widgets) {
        return;
    }
    
    echo '<div class="footer-widgets">';
    echo '<div class="container mx-auto px-4">';
    echo '<div class="footer-widgets-inner grid grid-cols-1 md:grid-cols-2 lg:grid-cols-' . esc_attr($footer_widget_areas) . ' gap-8">';
    
    for ($i = 1; $i <= $footer_widget_areas; $i++) {
        echo '<div class="footer-widget-area footer-widget-area-' . esc_attr($i) . '">';
        dynamic_sidebar('footer-' . $i);
        echo '</div>';
    }
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display the footer logo
 */
function aqualuxe_footer_logo() {
    $footer_logo_id = get_theme_mod('aqualuxe_footer_logo');
    $logo_alt = get_bloginfo('name');
    
    if ($footer_logo_id) {
        $logo_attrs = array(
            'class' => 'footer-logo',
            'alt'   => $logo_alt,
        );
        
        echo '<div class="footer-logo-wrap">';
        echo '<a href="' . esc_url(home_url('/')) . '" rel="home">';
        echo wp_get_attachment_image($footer_logo_id, 'full', false, $logo_attrs);
        echo '</a>';
        echo '</div>';
    } else {
        // Use the custom logo if footer logo is not set
        $logo_id = get_theme_mod('custom_logo');
        
        if ($logo_id) {
            $logo_attrs = array(
                'class' => 'footer-logo',
                'alt'   => $logo_alt,
            );
            
            echo '<div class="footer-logo-wrap">';
            echo '<a href="' . esc_url(home_url('/')) . '" rel="home">';
            echo wp_get_attachment_image($logo_id, 'full', false, $logo_attrs);
            echo '</a>';
            echo '</div>';
        } else {
            echo '<div class="footer-site-title">';
            echo '<a href="' . esc_url(home_url('/')) . '" rel="home">' . esc_html(get_bloginfo('name')) . '</a>';
            echo '</div>';
        }
    }
}

/**
 * Display the footer menu
 */
function aqualuxe_footer_menu() {
    if (has_nav_menu('footer-menu')) {
        echo '<nav class="footer-navigation" aria-label="' . esc_attr__('Footer Menu', 'aqualuxe') . '">';
        wp_nav_menu(array(
            'theme_location' => 'footer-menu',
            'container'      => false,
            'menu_class'     => 'footer-menu',
            'depth'          => 1,
            'fallback_cb'    => false,
        ));
        echo '</nav>';
    }
}

/**
 * Display the footer social links
 */
function aqualuxe_footer_social_links() {
    $social_links = aqualuxe_get_social_links();
    
    if (empty($social_links)) {
        return;
    }
    
    echo '<div class="footer-social-links">';
    
    foreach ($social_links as $network => $url) {
        if (empty($url)) {
            continue;
        }
        
        echo '<a href="' . esc_url($url) . '" class="social-link social-' . esc_attr($network) . '" target="_blank" rel="noopener noreferrer">';
        echo '<span class="screen-reader-text">' . esc_html(ucfirst($network)) . '</span>';
        
        switch ($network) {
            case 'facebook':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="w-5 h-5"><path fill="currentColor" d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>';
                break;
            case 'twitter':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5"><path fill="currentColor" d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>';
                break;
            case 'instagram':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5"><path fill="currentColor" d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>';
                break;
            case 'youtube':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-5 h-5"><path fill="currentColor" d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>';
                break;
            case 'linkedin':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-5 h-5"><path fill="currentColor" d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>';
                break;
            case 'pinterest':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-5 h-5"><path fill="currentColor" d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg>';
                break;
            default:
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="w-5 h-5"><path fill="currentColor" d="M352 256c0 22.2-1.2 43.6-3.3 64H163.3c-2.2-20.4-3.3-41.8-3.3-64s1.2-43.6 3.3-64H348.7c2.2 20.4 3.3 41.8 3.3 64zm28.8-64H503.9c5.3 20.5 8.1 41.9 8.1 64s-2.8 43.5-8.1 64H380.8c2.1-20.6 3.2-42 3.2-64s-1.1-43.4-3.2-64zm112.6-32H376.7c-10-63.9-29.8-117.4-55.3-151.6c78.3 20.7 142 77.5 171.9 151.6zm-149.1 0H167.7c6.1-36.4 15.5-68.6 27-94.7c10.5-23.6 22.2-40.7 33.5-51.5C239.4 3.2 248.7 0 256 0s16.6 3.2 27.8 13.8c11.3 10.8 23 27.9 33.5 51.5c11.6 26 20.9 58.2 27 94.7zm-209 0H18.6C48.6 85.9 112.2 29.1 190.6 8.4C165.1 42.6 145.3 96.1 135.3 160zM8.1 192C2.8 212.5 0 233.9 0 256s2.8 43.5 8.1 64H131.2c-2.1-20.6-3.2-42-3.2-64s1.1-43.4 3.2-64H8.1zm136.7 256c10 63.9 29.8 117.4 55.3 151.6C121.7 579.3 58 522.5 28.1 448h116.7zm149.1 0H218.2c-6.1 36.4-15.5 68.6-27 94.7c-10.5 23.6-22.2 40.7-33.5 51.5C146.6 604.8 137.3 608 128 608s-18.6-3.2-29.8-13.8c-11.3-10.8-23-27.9-33.5-51.5c-11.6-26-20.9-58.2-27-94.7zm209 0H376.8c-29.9 74.1-93.6 130.9-171.9 151.6c25.5-34.2 45.2-87.7 55.3-151.6z"/></svg>';
        }
        
        echo '</a>';
    }
    
    echo '</div>';
}

/**
 * Display the footer contact information
 */
function aqualuxe_footer_contact_info() {
    $contact_info = aqualuxe_get_contact_info();
    
    if (empty($contact_info)) {
        return;
    }
    
    echo '<div class="footer-contact-info">';
    
    if (!empty($contact_info['address'])) {
        echo '<div class="footer-address">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" /></svg>';
        echo '<span>' . esc_html($contact_info['address']) . '</span>';
        echo '</div>';
    }
    
    if (!empty($contact_info['phone'])) {
        echo '<div class="footer-phone">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd" /></svg>';
        echo '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $contact_info['phone'])) . '">' . esc_html($contact_info['phone']) . '</a>';
        echo '</div>';
    }
    
    if (!empty($contact_info['email'])) {
        echo '<div class="footer-email">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" /><path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" /></svg>';
        echo '<a href="mailto:' . esc_attr($contact_info['email']) . '">' . esc_html($contact_info['email']) . '</a>';
        echo '</div>';
    }
    
    if (!empty($contact_info['hours'])) {
        echo '<div class="footer-hours">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>';
        echo '<span>' . esc_html($contact_info['hours']) . '</span>';
        echo '</div>';
    }
    
    echo '</div>';
}

/**
 * Display the footer newsletter
 */
function aqualuxe_footer_newsletter() {
    // Check if newsletter is enabled
    if (!get_theme_mod('aqualuxe_enable_footer_newsletter', true)) {
        return;
    }

    $newsletter_title = get_theme_mod('aqualuxe_newsletter_title', esc_html__('Subscribe to our newsletter', 'aqualuxe'));
    $newsletter_text = get_theme_mod('aqualuxe_newsletter_text', esc_html__('Stay updated with our latest news and offers.', 'aqualuxe'));
    $newsletter_shortcode = get_theme_mod('aqualuxe_newsletter_shortcode', '');
    
    echo '<div class="footer-newsletter">';
    
    if ($newsletter_title) {
        echo '<h3 class="footer-newsletter-title">' . esc_html($newsletter_title) . '</h3>';
    }
    
    if ($newsletter_text) {
        echo '<div class="footer-newsletter-text">' . wp_kses_post($newsletter_text) . '</div>';
    }
    
    echo '<div class="footer-newsletter-form">';
    
    if ($newsletter_shortcode) {
        echo do_shortcode($newsletter_shortcode);
    } else {
        // Default newsletter form
        echo '<form action="#" method="post" class="newsletter-form">';
        echo '<div class="newsletter-form-inner">';
        echo '<input type="email" name="email" placeholder="' . esc_attr__('Your email address', 'aqualuxe') . '" required>';
        echo '<button type="submit">' . esc_html__('Subscribe', 'aqualuxe') . '</button>';
        echo '</div>';
        echo '<div class="newsletter-privacy">';
        echo '<label>';
        echo '<input type="checkbox" name="privacy" required>';
        echo esc_html__('I agree to the privacy policy', 'aqualuxe');
        echo '</label>';
        echo '</div>';
        echo '</form>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display the footer payment icons
 */
function aqualuxe_footer_payment_icons() {
    // Check if payment icons are enabled
    if (!get_theme_mod('aqualuxe_enable_payment_icons', true)) {
        return;
    }

    $payment_icons = array(
        'visa' => get_theme_mod('aqualuxe_payment_icon_visa', true),
        'mastercard' => get_theme_mod('aqualuxe_payment_icon_mastercard', true),
        'amex' => get_theme_mod('aqualuxe_payment_icon_amex', true),
        'discover' => get_theme_mod('aqualuxe_payment_icon_discover', true),
        'paypal' => get_theme_mod('aqualuxe_payment_icon_paypal', true),
        'apple_pay' => get_theme_mod('aqualuxe_payment_icon_apple_pay', true),
        'google_pay' => get_theme_mod('aqualuxe_payment_icon_google_pay', true),
    );
    
    // Filter out disabled icons
    $payment_icons = array_filter($payment_icons);
    
    if (empty($payment_icons)) {
        return;
    }
    
    echo '<div class="footer-payment-icons">';
    
    foreach (array_keys($payment_icons) as $icon) {
        echo '<span class="payment-icon payment-icon-' . esc_attr($icon) . '">';
        
        switch ($icon) {
            case 'visa':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-8 h-8"><path fill="currentColor" d="M470.1 231.3s7.6 37.2 9.3 45H446c3.3-8.9 16-43.5 16-43.5-.2.3 3.3-9.1 5.3-14.9l2.8 13.4zM576 80v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48zM152.5 331.2L215.7 176h-42.5l-39.3 106-4.3-21.5-14-71.4c-2.3-9.9-9.4-12.7-18.2-13.1H32.7l-.7 3.1c15.8 4 29.9 9.8 42.2 17.1l35.8 135h42.5zm94.4.2L272.1 176h-40.2l-25.1 155.4h40.1zm139.9-50.8c.2-17.7-10.6-31.2-33.7-42.3-14.1-7.1-22.7-11.9-22.7-19.2.2-6.6 7.3-13.4 23.1-13.4 13.1-.3 22.7 2.8 29.9 5.9l3.6 1.7 5.5-33.6c-7.9-3.1-20.5-6.6-36-6.6-39.7 0-67.6 21.2-67.8 51.4-.3 22.3 20 34.7 35.2 42.2 15.5 7.6 20.8 12.6 20.8 19.3-.2 10.4-12.6 15.2-24.1 15.2-16 0-24.6-2.5-37.7-8.3l-5.3-2.5-5.6 34.9c9.4 4.3 26.8 8.1 44.8 8.3 42.2.1 69.7-20.8 70-53zM528 331.4L495.6 176h-31.1c-9.6 0-16.9 2.8-21 12.9l-59.7 142.5H426s6.9-19.2 8.4-23.3H486c1.2 5.5 4.8 23.3 4.8 23.3H528z"/></svg>';
                break;
            case 'mastercard':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-8 h-8"><path fill="currentColor" d="M482.9 410.3c0 6.8-4.6 11.7-11.2 11.7-6.8 0-11.2-5.2-11.2-11.7 0-6.5 4.4-11.7 11.2-11.7 6.6 0 11.2 5.2 11.2 11.7zm-310.8-11.7c-7.1 0-11.2 5.2-11.2 11.7 0 6.5 4.1 11.7 11.2 11.7 6.5 0 10.9-4.9 10.9-11.7-.1-6.5-4.4-11.7-10.9-11.7zm117.5-.3c-5.4 0-8.7 3.5-9.5 8.7h19.1c-.9-5.7-4.4-8.7-9.6-8.7zm107.8.3c-6.8 0-10.9 5.2-10.9 11.7 0 6.5 4.1 11.7 10.9 11.7 6.8 0 11.2-4.9 11.2-11.7 0-6.5-4.4-11.7-11.2-11.7zm105.9 26.1c0 .3.3.5.3 1.1 0 .3-.3.5-.3 1.1-.3.3-.3.5-.5.8-.3.3-.5.5-1.1.5-.3.3-.5.3-1.1.3-.3 0-.5 0-1.1-.3-.3 0-.5-.3-.8-.5-.3-.3-.5-.5-.5-.8-.3-.5-.3-.8-.3-1.1 0-.5 0-.8.3-1.1 0-.5.3-.8.5-.8.3-.3.5-.5.8-.5.5-.3.8-.3 1.1-.3.5 0 .8 0 1.1.3.5.3.8.3.8.5.5.3.5.5.5.8zm-17.3-9.8c0 .8.3 1.6.5 2.1.8 1.1 1.9 1.6 3.5 1.6 1.1 0 1.9-.3 2.7-.8.8-.5 1.1-1.1 1.1-1.9 0-.8-.3-1.6-.8-2.1-.8-.5-1.9-.8-3.5-.8h-1.1c-1.9.3-2.4 1.1-2.4 1.9zm-110.8 9.8c0 .3.3.5.3 1.1 0 .3-.3.5-.3 1.1-.3.3-.3.5-.5.8-.3.3-.5.5-1.1.5-.3.3-.5.3-1.1.3-.3 0-.5 0-1.1-.3-.3 0-.5-.3-.8-.5-.3-.3-.5-.5-.5-.8-.3-.5-.3-.8-.3-1.1 0-.5 0-.8.3-1.1 0-.5.3-.8.5-.8.3-.3.5-.5.8-.5.5-.3.8-.3 1.1-.3.5 0 .8 0 1.1.3.5.3.8.3.8.5.5.3.5.5.5.8zm-117.3-9.8c0 .8.3 1.6.5 2.1.8 1.1 1.9 1.6 3.5 1.6 1.1 0 1.9-.3 2.7-.8.8-.5 1.1-1.1 1.1-1.9 0-.8-.3-1.6-.8-2.1-.8-.5-1.9-.8-3.5-.8h-1.1c-1.9.3-2.4 1.1-2.4 1.9zm-114.7 9.8c0 .3.3.5.3 1.1 0 .3-.3.5-.3 1.1-.3.3-.3.5-.5.8-.3.3-.5.5-1.1.5-.3.3-.5.3-1.1.3-.3 0-.5 0-1.1-.3-.3 0-.5-.3-.8-.5-.3-.3-.5-.5-.5-.8-.3-.5-.3-.8-.3-1.1 0-.5 0-.8.3-1.1 0-.5.3-.8.5-.8.3-.3.5-.5.8-.5.5-.3.8-.3 1.1-.3.5 0 .8 0 1.1.3.5.3.8.3.8.5.5.3.5.5.5.8zm-17.3-9.8c0 .8.3 1.6.5 2.1.8 1.1 1.9 1.6 3.5 1.6 1.1 0 1.9-.3 2.7-.8.8-.5 1.1-1.1 1.1-1.9 0-.8-.3-1.6-.8-2.1-.8-.5-1.9-.8-3.5-.8h-1.1c-1.9.3-2.4 1.1-2.4 1.9zM576 80v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48zm-41.6 334.9c3.8-8.8 2-19.1-1.9-28.2-4.3-9.6-10.5-15.8-18.7-20.7-7.2-4.1-15.8-6.8-24.2-6.8h-29.3c-5.5 0-10.4 3.8-11.8 9.1-21.9-15.3-48.5-24.2-76.5-24.2-72.1 0-130.9 58.8-130.9 130.9 0 72.1 58.8 130.9 130.9 130.9 72.1 0 130.9-58.8 130.9-130.9 0-20.7-4.8-40.1-13.4-57.5h50.5c14.2 0 21.9 15.6 15.4 26.5zm-180 130.9c0 30.9-25.1 56-56 56s-56-25.1-56-56 25.1-56 56-56 56 25.1 56 56zm-110.9-56c-30.9 0-56 25.1-56 56s25.1 56 56 56 56-25.1 56-56-25.1-56-56-56z"/></svg>';
                break;
            case 'amex':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-8 h-8"><path fill="currentColor" d="M325.1 167.8c0-16.4-14.1-18.4-27.4-18.4l-39.1-.3v69.3H275v-25.1h18c18.4 0 14.5 10.3 14.8 25.1h16.6v-13.5c0-9.2-1.5-15.1-11-18.4 7.4-3 11.8-10.7 11.7-18.7zm-29.4 11.3H275v-15.3h21c5.1 0 10.7 1 10.7 7.4 0 6.6-5.3 7.9-11 7.9zM279 268.6h-52.7l-21 22.8-20.5-22.8h-66.5l-.1 69.3h65.4l21.3-23 20.4 23h32.2l.1-23.3c18.9 0 49.3 4.6 49.3-23.3 0-17.3-12.3-22.7-27.9-22.7zm-103.8 54.7h-40.6v-13.8h36.3v-14.1h-36.3v-12.5h41.7l17.9 20.2zm65.8 8.2l-25.3-28.1L241 276zm37.8-31h-21.2v-17.6h21.5c5.6 0 10.2 2.3 10.2 8.4 0 6.4-4.6 9.2-10.5 9.2zm-31.6-136.7v-14.6h-55.5v69.3h55.5v-14.3h-38.9v-13.8h37.8v-14.1h-37.8v-12.5zM576 255.4h-.2zm-194.6 31.9c0-16.4-14.1-18.7-27.1-18.7h-39.4l-.1 69.3h16.6l.1-25.3h17.6c11 0 14.8 2 14.8 13.8l-.1 11.5h16.6l.1-13.8c0-8.9-1.8-15.1-11-18.4 7.7-3.1 11.8-10.8 11.9-18.4zm-29.2 11.2h-20.7v-15.6h21c5.1 0 10.7 1 10.7 7.4 0 6.9-5.4 8.2-11 8.2zm-172.8-80v-69.3h-27.6l-19.7 47-21.7-47H83.3v65.7l-28.1-65.7H30.7L1 218.5h17.9l6.4-15.3h34.5l6.4 15.3H100v-54.2l24 54.2h14.6l24-54.2v54.2zM31.2 188.8l11.2-27.6 11.5 27.6zm477.4 158.9v-4.5c-10.8 5.6-3.9 4.5-156.7 4.5 0-25.2.1-23.9 0-25.2-1.7-.1-3.2-.1-9.4-.1 0 17.9-.1 6.8-.1 25.3h-39.6c0-12.1.1-15.3.1-29.2-10 6-22.8 6.4-34.3 6.2 0 14.7-.1 8.3-.1 23h-48.9c-5.1-5.7-2.7-3.1-15.4-17.4-3.2 3.5-12.8 13.9-16.1 17.4h-82v-92.3h83.1c5 5.6 2.8 3.1 15.5 17.2 3.2-3.5 12.2-13.4 15.7-17.2h58c9.8 0 18 1.9 24.3 5.6v-5.6c54.3 0 64.3-1.4 75.7 5.1v-5.1h78.2v5.2c11.4-6.9 19.6-5.2 64.9-5.2v5c10.3-5.9 16.6-5.2 54.3-5V80c0-26.5-21.5-48-48-48h-480c-26.5 0-48 21.5-48 48v352c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48v-144.2c-2 .1-4.2.2-7.2.2-14.6 0-21.4-5.5-24.5-8.8-5.3 5.2-14.2 8.8-24.5 8.8-9.8 0-18.9-3.8-24.5-10.6-5.5 6.8-14.8 10.6-24.5 10.6-15.5 0-26.5-10.7-26.5-26.7 0-14.7 10-26.9 26.5-26.9 15.5 0 26.5 10.7 26.5 26.9 0 .5 0 1.1-.1 1.6.7.7 1.5 1.3 2.3 1.9l-1.2-3.6c.1 0 .2-.1.3-.1.2.6.4 1.2.6 1.8 0-.1.1-.1.1-.2l1.2 3.5c.8.1 1.6.3 2.5.3 15.5 0 26.2-10.7 26.2-26.9 0-14.7-10.7-26.9-26.2-26.9z"/></svg>';
                break;
            case 'discover':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="w-8 h-8"><path fill="currentColor" d="M520.4 196.1c0-7.9-5.5-12.1-15.6-12.1h-4.9v24.9h4.7c10.3 0 15.8-4.4 15.8-12.8zM528 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-44.1 138.9c22.6 0 52.9-4.1 52.9 24.4 0 12.6-6.6 20.7-18.7 23.2l25.8 34.4h-19.6l-22.2-32.8h-2.2v32.8h-16zm-55.9.1h45.3v14H444v12.5h29.3V210H444v13.9h30.3v14H428zm-68.7 0l21.9 55.2 22.2-55.2h17.5l-35.5 84.2h-8.6l-35-84.2zm-55.9-3c24.7 0 44.6 20 44.6 44.6 0 24.7-20 44.6-44.6 44.6-24.7 0-44.6-20-44.6-44.6 0-24.7 20-44.6 44.6-44.6zm-49.3 6.1v19c-20.1-20.1-46.8-4.7-46.8 19 0 25 27.5 38.5 46.8 19.2v19c-29.7 14.3-63.3-5.7-63.3-38.2 0-31.2 33.1-53 63.3-38zm-97.2 66.3c11.4 0 22.4-15.3-3.3-24.4-15-5.5-20.2-11.4-20.2-22.7 0-23.2 30.6-31.4 49.7-14.3l-8.4 10.8c-10.4-11.6-24.9-6.2-24.9 2.5 0 4.4 2.7 6.9 12.3 10.3 18.2 6.6 23.6 12.5 23.6 25.6 0 29.5-38.8 37.4-56.6 11.3l10.3-9.9c3.7 7.1 9.9 10.8 17.5 10.8zM55.4 253H32v-82h23.4c26.1 0 44.1 17 44.1 41.1 0 18.5-13.2 40.9-44.1 40.9zm67.5 0h-16v-82h16zM544 433c0 8.2-6.8 15-15 15H128c189.6-35.6 382.7-139.2 416-160zM74.1 191.6c-5.2-4.9-11.6-6.6-21.9-6.6H48v54.2h4.2c10.3 0 17-2 21.9-6.4 5.7-5.2 8.9-12.8 8.9-20.7s-3.2-15.5-8.9-20.5z"/></svg>';
                break;
            case 'paypal':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" class="w-8 h-8"><path fill="currentColor" d="M111.4 295.9c-3.5 19.2-17.4 108.7-21.5 134-.3 1.8-1 2.5-3 2.5H12.3c-7.6 0-13.1-6.6-12.1-13.9L58.8 46.6c1.5-9.6 10.1-16.9 20-16.9 152.3 0 165.1-3.7 204 11.4 60.1 23.3 65.6 79.5 44 140.3-21.5 62.6-72.5 89.5-140.1 90.3-43.4.7-69.5-7-75.3 24.2zM357.1 152c-1.8-1.3-2.5-1.8-3 1.3-2 11.4-5.1 22.5-8.8 33.6-39.9 113.8-150.5 103.9-204.5 103.9-6.1 0-10.1 3.3-10.9 9.4-22.6 140.4-27.1 169.7-27.1 169.7-1 7.1 3.5 12.9 10.6 12.9h63.5c8.6 0 15.7-6.3 17.4-14.9.7-5.4-1.1 6.1 14.4-91.3 4.6-22 14.3-19.7 29.3-19.7 71 0 126.4-28.8 142.9-112.3 6.5-34.8 4.6-71.4-23.8-92.6z"/></svg>';
                break;
            case 'apple_pay':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="w-8 h-8"><path fill="currentColor" d="M116.9 158.5c-7.5 8.9-19.5 15.9-31.5 14.9-1.5-12 4.4-24.8 11.3-32.6 7.5-9.1 20.6-15.6 31.3-16.1 1.2 12.4-3.7 24.7-11.1 33.8m10.9 17.2c-17.4-1-32.3 9.9-40.5 9.9-8.4 0-21-9.4-34.8-9.1-17.9.3-34.5 10.4-43.6 26.5-18.8 32.3-4.9 80 13.3 106.3 8.9 13 19.5 27.3 33.5 26.8 13.3-.5 18.5-8.6 34.5-8.6 16.1 0 20.8 8.6 34.8 8.4 14.5-.3 23.6-13 32.5-26 10.1-14.8 14.3-29.1 14.5-29.9-.3-.3-28-10.9-28.3-42.9-.3-26.8 21.9-39.5 22.9-40.3-12.5-18.6-32-20.6-38.8-21.1m100.4-36.2v194.9h30.3v-66.6h41.9c38.3 0 65.1-26.3 65.1-64.3s-26.4-64-64.1-64h-73.2zm30.3 25.5h34.9c26.3 0 41.3 14 41.3 38.6s-15 38.8-41.4 38.8h-34.8V165zm162.2 170.9c19 0 36.6-9.6 44.6-24.9h.6v23.4h28v-97.4c0-28.1-22.5-46.3-57.1-46.3-32.1 0-55.9 18.4-56.8 43.6h27.3c2.3-12 13.4-19.9 28.6-19.9 18.5 0 28.9 8.6 28.9 24.5v10.8l-37.8 2.3c-35.1 2.1-54.1 16.5-54.1 41.5.1 25.2 19.7 42.4 47.8 42.4zm8.2-23.1c-16.1 0-26.4-7.8-26.4-19.6 0-12.3 9.9-19.4 28.8-20.5l33.6-2.1v11c0 18.2-15.5 31.2-36 31.2zm102.5 74.6c29.5 0 43.4-11.3 55.5-45.4L640 193h-30.8l-35.6 115.1h-.6L537.4 193h-31.6L557 334.9l-2.8 8.6c-4.6 14.6-12.1 20.3-25.5 20.3-2.4 0-7-.3-8.9-.5v23.4c1.8.4 9.3.7 11.6.7z"/></svg>';
                break;
            case 'google_pay':
                echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" class="w-8 h-8"><path fill="currentColor" d="M105.72,215v41.25h57.1a49.66,49.66,0,0,1-21.14,32.6c-9.54,6.55-21.72,10.28-36,10.28-27.6,0-50.93-18.91-59.3-44.22a65.61,65.61,0,0,1,0-41l0,0c8.37-25.46,31.7-44.37,59.3-44.37a56.43,56.43,0,0,1,40.51,16.08L176.47,155a101.24,101.24,0,0,0-70.75-27.84,105.55,105.55,0,0,0-94.38,59.11,107.64,107.64,0,0,0,0,96.18v.15a105.41,105.41,0,0,0,94.38,59c28.47,0,52.55-9.53,70-25.91,20-18.61,31.41-46.15,31.41-78.91A133.76,133.76,0,0,0,205.38,215Zm389.41-4c-10.13-9.38-23.93-14.14-41.39-14.14-22.46,0-39.34,8.34-50.5,24.86l20.85,13.26q11.45-17,31.26-17a34.05,34.05,0,0,1,22.75,8.79A28.14,28.14,0,0,1,487.79,248v5.51c-9.1-5.07-20.55-7.75-34.64-7.75-16.44,0-29.65,3.88-39.49,11.77s-14.82,18.31-14.82,31.56a39.74,39.74,0,0,0,13.94,31.27c9.25,8.34,21,12.51,34.79,12.51,16.29,0,29.21-7.3,39-21.89h1v17.72h22.61V250C510.25,233.45,505.26,220.34,495.13,211ZM475.9,300.3a37.32,37.32,0,0,1-26.57,11.16A28.61,28.61,0,0,1,431,305.21a19.41,19.41,0,0,1-7.77-15.63c0-7,3.22-12.81,9.54-17.42s14.53-7,24.07-7C470,265.17,480,269,487.64,273V285.1C487.64,292.56,483.68,297.52,475.9,300.3ZM233.94,210.26,294.1,322.3h-.16L233.1,210.26Zm-23.39-5.51-81.53,176.28h27.14l22.4-48.92h83.17L284,332.11h27.14L229.3,204.75ZM313.63,210h23.69V332.11H313.63Zm47.84,83.09c0-19.33,8.34-35.48,24.86-48.44,16.29-12.66,36.66-19.89,60.94-21.5l25.37-1.59v-8.1c0-11.95-4-21.73-12.21-29.21-8.19-7.75-19.32-11.77-33.22-11.77-15.79,0-31.27,7.45-46.77,22.46L366.4,185.61c21.89-19,43.47-28.64,64.91-28.64,22.45,0,40.65,6.05,54.37,18.46,13.57,12.2,20.4,28.09,20.4,47.26V332.11H483.86V313.55h-1c-16,15.33-31.57,23-46.92,23-14.22,0-26.57-4.82-36.81-14.29C389,312.79,384,300.3,384,285.1Zm126.19-16.29V249l-23.2,1.6c-14.37,1-25.37,4.27-33.23,10-7.59,5.66-11.46,13.26-11.46,22.61a20,20,0,0,0,7.46,15.94,25.79,25.79,0,0,0,17.73,6.35c8.8,0,16.44-2.28,23.2-7C498.29,293.23,505,284,510.22,268.81Z"/></svg>';
                break;
        }
        
        echo '</span>';
    }
    
    echo '</div>';
}

/**
 * Display the footer copyright
 */
function aqualuxe_footer_copyright() {
    $copyright_text = get_theme_mod('aqualuxe_copyright_text', sprintf(esc_html__('© %s AquaLuxe. All rights reserved.', 'aqualuxe'), date('Y')));
    
    echo '<div class="footer-copyright">';
    echo wp_kses_post($copyright_text);
    echo '</div>';
}

/**
 * Display the back to top button
 */
function aqualuxe_back_to_top() {
    // Check if back to top button is enabled
    if (!get_theme_mod('aqualuxe_enable_back_to_top', true)) {
        return;
    }

    echo '<a href="#" id="back-to-top" class="back-to-top" aria-label="' . esc_attr__('Back to top', 'aqualuxe') . '">';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M14.77 12.79a.75.75 0 01-1.06-.02L10 8.832 6.29 12.77a.75.75 0 11-1.08-1.04l4.25-4.5a.75.75 0 011.08 0l4.25 4.5a.75.75 0 01-.02 1.06z" clip-rule="evenodd" /></svg>';
    echo '</a>';
}

/**
 * Display the cookie notice
 */
function aqualuxe_cookie_notice() {
    // Check if cookie notice is enabled
    if (!get_theme_mod('aqualuxe_enable_cookie_notice', true)) {
        return;
    }

    // Check if cookie notice has been accepted
    if (isset($_COOKIE['aqualuxe_cookie_notice_accepted'])) {
        return;
    }

    $cookie_notice_text = get_theme_mod('aqualuxe_cookie_notice_text', esc_html__('We use cookies to improve your experience on our website. By browsing this website, you agree to our use of cookies.', 'aqualuxe'));
    $cookie_notice_button_text = get_theme_mod('aqualuxe_cookie_notice_button_text', esc_html__('Accept', 'aqualuxe'));
    $cookie_notice_privacy_link = get_theme_mod('aqualuxe_cookie_notice_privacy_link', '');
    $cookie_notice_privacy_text = get_theme_mod('aqualuxe_cookie_notice_privacy_text', esc_html__('Privacy Policy', 'aqualuxe'));
    
    echo '<div id="cookie-notice" class="cookie-notice">';
    echo '<div class="container mx-auto px-4">';
    echo '<div class="cookie-notice-inner">';
    
    echo '<div class="cookie-notice-content">';
    echo wp_kses_post($cookie_notice_text);
    
    if ($cookie_notice_privacy_link) {
        echo ' <a href="' . esc_url($cookie_notice_privacy_link) . '">' . esc_html($cookie_notice_privacy_text) . '</a>';
    }
    
    echo '</div>';
    
    echo '<div class="cookie-notice-actions">';
    echo '<button id="cookie-notice-accept" class="cookie-notice-accept">' . esc_html($cookie_notice_button_text) . '</button>';
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}