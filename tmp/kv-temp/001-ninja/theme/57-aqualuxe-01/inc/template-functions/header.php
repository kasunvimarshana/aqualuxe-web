<?php
/**
 * Header template functions
 *
 * @package AquaLuxe
 */

/**
 * Display the site logo
 */
function aqualuxe_site_logo() {
    $logo_id = get_theme_mod('custom_logo');
    $logo_alt = get_bloginfo('name');
    
    if ($logo_id) {
        $logo_attrs = array(
            'class' => 'custom-logo',
            'alt'   => $logo_alt,
        );

        // Get dark mode logo if available
        $dark_logo_id = get_theme_mod('aqualuxe_dark_logo');
        
        if ($dark_logo_id) {
            echo '<a href="' . esc_url(home_url('/')) . '" class="custom-logo-link" rel="home">';
            echo wp_get_attachment_image($logo_id, 'full', false, array_merge($logo_attrs, array('class' => 'custom-logo light-logo')));
            echo wp_get_attachment_image($dark_logo_id, 'full', false, array_merge($logo_attrs, array('class' => 'custom-logo dark-logo')));
            echo '</a>';
        } else {
            the_custom_logo();
        }
    } else {
        echo '<a href="' . esc_url(home_url('/')) . '" class="site-title" rel="home">' . esc_html(get_bloginfo('name')) . '</a>';
        
        $description = get_bloginfo('description', 'display');
        if ($description || is_customize_preview()) {
            echo '<p class="site-description">' . $description . '</p>';
        }
    }
}

/**
 * Display the top bar
 */
function aqualuxe_top_bar() {
    // Check if top bar is enabled
    if (!get_theme_mod('aqualuxe_enable_top_bar', true)) {
        return;
    }

    echo '<div class="top-bar">';
    echo '<div class="container mx-auto px-4">';
    echo '<div class="top-bar-inner">';
    
    // Left side - Contact info
    echo '<div class="top-bar-left">';
    aqualuxe_top_bar_contact_info();
    echo '</div>';
    
    // Right side - Menu, social links, etc.
    echo '<div class="top-bar-right">';
    aqualuxe_top_bar_menu();
    aqualuxe_top_bar_social_links();
    aqualuxe_language_switcher();
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * Display contact information in the top bar
 */
function aqualuxe_top_bar_contact_info() {
    $contact_info = aqualuxe_get_contact_info();
    
    if (empty($contact_info)) {
        return;
    }
    
    echo '<div class="contact-info">';
    
    if (!empty($contact_info['phone'])) {
        echo '<span class="contact-phone">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd" /></svg>';
        echo '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $contact_info['phone'])) . '">' . esc_html($contact_info['phone']) . '</a>';
        echo '</span>';
    }
    
    if (!empty($contact_info['email'])) {
        echo '<span class="contact-email">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" /><path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" /></svg>';
        echo '<a href="mailto:' . esc_attr($contact_info['email']) . '">' . esc_html($contact_info['email']) . '</a>';
        echo '</span>';
    }
    
    if (!empty($contact_info['hours'])) {
        echo '<span class="contact-hours">';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>';
        echo esc_html($contact_info['hours']);
        echo '</span>';
    }
    
    echo '</div>';
}

/**
 * Display the top bar menu
 */
function aqualuxe_top_bar_menu() {
    if (has_nav_menu('top-bar-menu')) {
        wp_nav_menu(array(
            'theme_location' => 'top-bar-menu',
            'container'      => false,
            'menu_class'     => 'top-bar-menu',
            'depth'          => 1,
            'fallback_cb'    => false,
        ));
    }
}

/**
 * Display social links in the top bar
 */
function aqualuxe_top_bar_social_links() {
    $social_links = aqualuxe_get_social_links();
    
    if (empty($social_links)) {
        return;
    }
    
    echo '<div class="social-links">';
    
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
 * Display language switcher
 */
function aqualuxe_language_switcher() {
    // Check if multilingual module is enabled
    if (!function_exists('aqualuxe_multilingual_enabled') || !aqualuxe_multilingual_enabled()) {
        return;
    }

    // Check if WPML or Polylang is active
    if (function_exists('icl_get_languages')) {
        // WPML
        $languages = icl_get_languages('skip_missing=0&orderby=code');
        
        if (!empty($languages)) {
            echo '<div class="language-switcher">';
            echo '<div class="current-language">';
            
            foreach ($languages as $language) {
                if ($language['active']) {
                    echo '<span class="language-flag">';
                    if ($language['country_flag_url']) {
                        echo '<img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['language_code']) . '" width="18" height="12">';
                    }
                    echo '</span>';
                    echo '<span class="language-code">' . esc_html(strtoupper($language['language_code'])) . '</span>';
                    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" /></svg>';
                    break;
                }
            }
            
            echo '</div>';
            
            echo '<ul class="language-dropdown">';
            foreach ($languages as $language) {
                echo '<li class="' . ($language['active'] ? 'active' : '') . '">';
                echo '<a href="' . esc_url($language['url']) . '">';
                
                if ($language['country_flag_url']) {
                    echo '<span class="language-flag"><img src="' . esc_url($language['country_flag_url']) . '" alt="' . esc_attr($language['language_code']) . '" width="18" height="12"></span>';
                }
                
                echo '<span class="language-name">' . esc_html($language['native_name']) . '</span>';
                echo '</a>';
                echo '</li>';
            }
            echo '</ul>';
            
            echo '</div>';
        }
    } elseif (function_exists('pll_the_languages')) {
        // Polylang
        $args = array(
            'show_flags' => 1,
            'show_names' => 1,
            'dropdown'   => 1,
        );
        
        echo '<div class="language-switcher polylang">';
        pll_the_languages($args);
        echo '</div>';
    }
}

/**
 * Display the dark mode toggle
 */
function aqualuxe_dark_mode_toggle() {
    // Check if dark mode module is enabled
    if (!function_exists('aqualuxe_dark_mode_enabled') || !aqualuxe_dark_mode_enabled()) {
        return;
    }

    $is_dark_mode = aqualuxe_is_dark_mode();
    
    echo '<button id="dark-mode-toggle" class="dark-mode-toggle" aria-pressed="' . ($is_dark_mode ? 'true' : 'false') . '">';
    echo '<span class="screen-reader-text">' . esc_html__('Toggle dark mode', 'aqualuxe') . '</span>';
    
    // Sun icon (light mode)
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 sun-icon"><path d="M10 2a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 2zM10 15a.75.75 0 01.75.75v1.5a.75.75 0 01-1.5 0v-1.5A.75.75 0 0110 15zM10 7a3 3 0 100 6 3 3 0 000-6zM15.657 5.404a.75.75 0 10-1.06-1.06l-1.061 1.06a.75.75 0 001.06 1.06l1.06-1.06zM6.464 14.596a.75.75 0 10-1.06-1.06l-1.06 1.06a.75.75 0 001.06 1.06l1.06-1.06zM18 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 0118 10zM5 10a.75.75 0 01-.75.75h-1.5a.75.75 0 010-1.5h1.5A.75.75 0 015 10zM14.596 15.657a.75.75 0 001.06-1.06l-1.06-1.061a.75.75 0 10-1.06 1.06l1.06 1.06zM5.404 6.464a.75.75 0 001.06-1.06l-1.06-1.06a.75.75 0 10-1.061 1.06l1.06 1.06z" /></svg>';
    
    // Moon icon (dark mode)
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 moon-icon"><path fill-rule="evenodd" d="M7.455 2.004a.75.75 0 01.26.77 7 7 0 009.958 7.967.75.75 0 011.067.853A8.5 8.5 0 116.647 1.921a.75.75 0 01.808.083z" clip-rule="evenodd" /></svg>';
    
    echo '</button>';
}

/**
 * Display the search toggle
 */
function aqualuxe_search_toggle() {
    // Check if search is enabled
    if (!get_theme_mod('aqualuxe_enable_header_search', true)) {
        return;
    }

    echo '<button id="search-toggle" class="search-toggle" aria-expanded="false">';
    echo '<span class="screen-reader-text">' . esc_html__('Toggle search', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z" clip-rule="evenodd" /></svg>';
    echo '</button>';
}

/**
 * Display the search form
 */
function aqualuxe_search_form() {
    // Check if search is enabled
    if (!get_theme_mod('aqualuxe_enable_header_search', true)) {
        return;
    }

    echo '<div id="header-search" class="header-search" style="display: none;">';
    echo '<div class="container mx-auto px-4">';
    
    get_search_form();
    
    echo '<button id="search-close" class="search-close">';
    echo '<span class="screen-reader-text">' . esc_html__('Close search', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>';
    echo '</button>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display the mobile menu toggle
 */
function aqualuxe_mobile_menu_toggle() {
    echo '<button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-controls="mobile-menu" aria-expanded="false">';
    echo '<span class="screen-reader-text">' . esc_html__('Toggle menu', 'aqualuxe') . '</span>';
    echo '<span class="hamburger-icon">';
    echo '<span class="hamburger-inner"></span>';
    echo '</span>';
    echo '</button>';
}

/**
 * Display the mobile menu
 */
function aqualuxe_mobile_menu() {
    echo '<div id="mobile-menu" class="mobile-menu" style="display: none;">';
    echo '<div class="mobile-menu-inner">';
    
    echo '<div class="mobile-menu-header">';
    aqualuxe_site_logo();
    
    echo '<button id="mobile-menu-close" class="mobile-menu-close">';
    echo '<span class="screen-reader-text">' . esc_html__('Close menu', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>';
    echo '</button>';
    echo '</div>';
    
    if (has_nav_menu('mobile-menu')) {
        wp_nav_menu(array(
            'theme_location' => 'mobile-menu',
            'container'      => false,
            'menu_class'     => 'mobile-menu-nav',
            'depth'          => 3,
            'fallback_cb'    => false,
        ));
    } elseif (has_nav_menu('primary-menu')) {
        wp_nav_menu(array(
            'theme_location' => 'primary-menu',
            'container'      => false,
            'menu_class'     => 'mobile-menu-nav',
            'depth'          => 3,
            'fallback_cb'    => false,
        ));
    }
    
    echo '<div class="mobile-menu-extras">';
    
    // Contact info
    $contact_info = aqualuxe_get_contact_info();
    if (!empty($contact_info)) {
        echo '<div class="mobile-contact-info">';
        
        if (!empty($contact_info['phone'])) {
            echo '<div class="mobile-contact-phone">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd" /></svg>';
            echo '<a href="tel:' . esc_attr(preg_replace('/[^0-9+]/', '', $contact_info['phone'])) . '">' . esc_html($contact_info['phone']) . '</a>';
            echo '</div>';
        }
        
        if (!empty($contact_info['email'])) {
            echo '<div class="mobile-contact-email">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" /><path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" /></svg>';
            echo '<a href="mailto:' . esc_attr($contact_info['email']) . '">' . esc_html($contact_info['email']) . '</a>';
            echo '</div>';
        }
        
        if (!empty($contact_info['address'])) {
            echo '<div class="mobile-contact-address">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" /></svg>';
            echo esc_html($contact_info['address']);
            echo '</div>';
        }
        
        if (!empty($contact_info['hours'])) {
            echo '<div class="mobile-contact-hours">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 inline-block align-text-bottom"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>';
            echo esc_html($contact_info['hours']);
            echo '</div>';
        }
        
        echo '</div>';
    }
    
    // Social links
    aqualuxe_top_bar_social_links();
    
    // Language switcher
    aqualuxe_language_switcher();
    
    // Dark mode toggle
    aqualuxe_dark_mode_toggle();
    
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
}

/**
 * Display WooCommerce header icons
 */
function aqualuxe_woocommerce_header_icons() {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }

    echo '<div class="header-icons">';
    
    // Account icon
    echo '<a href="' . esc_url(wc_get_account_endpoint_url('dashboard')) . '" class="header-icon account-icon">';
    echo '<span class="screen-reader-text">' . esc_html__('My Account', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-5.5-2.5a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0zM10 12a5.99 5.99 0 00-4.793 2.39A6.483 6.483 0 0010 16.5a6.483 6.483 0 004.793-2.11A5.99 5.99 0 0010 12z" clip-rule="evenodd" /></svg>';
    echo '</a>';
    
    // Wishlist icon (if enabled)
    if (get_theme_mod('aqualuxe_enable_wishlist', true) && function_exists('aqualuxe_get_wishlist_url')) {
        echo '<a href="' . esc_url(aqualuxe_get_wishlist_url()) . '" class="header-icon wishlist-icon">';
        echo '<span class="screen-reader-text">' . esc_html__('Wishlist', 'aqualuxe') . '</span>';
        echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M9.653 16.915l-.005-.003-.019-.01a20.759 20.759 0 01-1.162-.682 22.045 22.045 0 01-2.582-1.9C4.045 12.733 2 10.352 2 7.5a4.5 4.5 0 018-2.828A4.5 4.5 0 0118 7.5c0 2.852-2.044 5.233-3.885 6.82a22.049 22.049 0 01-3.744 2.582l-.019.01-.005.003h-.002a.739.739 0 01-.69.001l-.002-.001z" /></svg>';
        
        $wishlist_count = function_exists('aqualuxe_get_wishlist_count') ? aqualuxe_get_wishlist_count() : 0;
        if ($wishlist_count > 0) {
            echo '<span class="count">' . esc_html($wishlist_count) . '</span>';
        }
        
        echo '</a>';
    }
    
    // Cart icon
    echo '<a href="' . esc_url(wc_get_cart_url()) . '" class="header-icon cart-icon">';
    echo '<span class="screen-reader-text">' . esc_html__('Cart', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M1 1.75A.75.75 0 011.75 1h1.628a1.75 1.75 0 011.734 1.51L5.18 3a65.25 65.25 0 0113.36 1.412.75.75 0 01.58.875 48.645 48.645 0 01-1.618 6.2.75.75 0 01-.712.513H6a2.503 2.503 0 00-2.292 1.5H17.25a.75.75 0 010 1.5H2.76a.75.75 0 01-.748-.807 4.002 4.002 0 012.716-3.486L3.626 2.716a.25.25 0 00-.248-.216H1.75A.75.75 0 011 1.75zM6 17.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15.5 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" /></svg>';
    
    $cart_count = WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
    if ($cart_count > 0) {
        echo '<span class="count">' . esc_html($cart_count) . '</span>';
    }
    
    echo '</a>';
    
    echo '</div>';
}

/**
 * Display the mini cart
 */
function aqualuxe_mini_cart() {
    if (!aqualuxe_is_woocommerce_active() || !get_theme_mod('aqualuxe_enable_mini_cart', true)) {
        return;
    }

    echo '<div id="mini-cart" class="mini-cart" style="display: none;">';
    echo '<div class="mini-cart-inner">';
    
    echo '<div class="mini-cart-header">';
    echo '<h3>' . esc_html__('Your Cart', 'aqualuxe') . '</h3>';
    echo '<button id="mini-cart-close" class="mini-cart-close">';
    echo '<span class="screen-reader-text">' . esc_html__('Close cart', 'aqualuxe') . '</span>';
    echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>';
    echo '</button>';
    echo '</div>';
    
    woocommerce_mini_cart();
    
    echo '</div>';
    echo '</div>';
}