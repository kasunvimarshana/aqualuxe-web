<?php
/**
 * Social Sharing Functions
 *
 * @package AquaLuxe
 */

/**
 * Add social sharing buttons to single posts and products.
 */
function aqualuxe_social_sharing_buttons() {
    // Check if social sharing is enabled
    $enable_social_sharing = get_theme_mod('aqualuxe_enable_social_sharing', true);
    
    if (!$enable_social_sharing) {
        return;
    }
    
    // Only show on single posts and products
    if (!is_singular('post') && !is_singular('product')) {
        return;
    }
    
    // Get current page URL
    $url = urlencode(get_permalink());
    
    // Get current page title
    $title = urlencode(get_the_title());
    
    // Get current page thumbnail
    $thumbnail = '';
    if (has_post_thumbnail()) {
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full');
        $thumbnail = urlencode($thumbnail_url[0]);
    }
    
    // Get enabled social networks
    $enabled_networks = get_theme_mod('aqualuxe_social_sharing_networks', array('facebook', 'twitter', 'pinterest', 'linkedin', 'email'));
    
    // Define sharing URLs
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
    $twitter_url = 'https://twitter.com/intent/tweet?text=' . $title . '&url=' . $url;
    $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $thumbnail . '&description=' . $title;
    $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title;
    $email_url = 'mailto:?subject=' . $title . '&body=' . $url;
    $whatsapp_url = 'https://api.whatsapp.com/send?text=' . $title . ' ' . $url;
    $telegram_url = 'https://telegram.me/share/url?url=' . $url . '&text=' . $title;
    $reddit_url = 'https://reddit.com/submit?url=' . $url . '&title=' . $title;
    
    // Start sharing buttons output
    $output = '<div class="social-sharing">';
    $output .= '<h4 class="social-sharing-title">' . esc_html__('Share this:', 'aqualuxe') . '</h4>';
    $output .= '<ul class="social-sharing-buttons">';
    
    // Facebook
    if (in_array('facebook', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-facebook">';
        $output .= '<a href="' . esc_url($facebook_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on Facebook', 'aqualuxe') . '">';
        $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" class="social-icon"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>';
        $output .= '<span class="social-sharing-text">' . esc_html__('Facebook', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Twitter
    if (in_array('twitter', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-twitter">';
        $output .= '<a href="' . esc_url($twitter_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on Twitter', 'aqualuxe') . '">';
        $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="social-icon"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>';
        $output .= '<span class="social-sharing-text">' . esc_html__('Twitter', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Pinterest (only show if we have a thumbnail)
    if (in_array('pinterest', $enabled_networks) && !empty($thumbnail)) {
        $output .= '<li class="social-sharing-button social-pinterest">';
        $output .= '<a href="' . esc_url($pinterest_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on Pinterest', 'aqualuxe') . '">';
        $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" class="social-icon"><path d="M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1 10.1-16.5 25.2-43.5 30.8-65 3-11.6 15.4-59 15.4-59 8.1 15.4 31.7 28.5 56.8 28.5 74.8 0 128.7-68.8 128.7-154.3 0-81.9-66.9-143.2-152.9-143.2-107 0-163.9 71.8-163.9 150.1 0 36.4 19.4 81.7 50.3 96.1 4.7 2.2 7.2 1.2 8.3-3.3.8-3.4 5-20.3 6.9-28.1.6-2.5.3-4.7-1.7-7.1-10.1-12.5-18.3-35.3-18.3-56.6 0-54.7 41.4-107.6 112-107.6 60.9 0 103.6 41.5 103.6 100.9 0 67.1-33.9 113.6-78 113.6-24.3 0-42.6-20.1-36.7-44.8 7-29.5 20.5-61.3 20.5-82.6 0-19-10.2-34.9-31.4-34.9-24.9 0-44.9 25.7-44.9 60.2 0 22 7.4 36.8 7.4 36.8s-24.5 103.8-29 123.2c-5 21.4-3 51.6-.9 71.2C65.4 450.9 0 361.1 0 256 0 119 111 8 248 8s248 111 248 248z"/></svg>';
        $output .= '<span class="social-sharing-text">' . esc_html__('Pinterest', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // LinkedIn
    if (in_array('linkedin', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-linkedin">';
        $output .= '<a href="' . esc_url($linkedin_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on LinkedIn', 'aqualuxe') . '">';
        $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="social-icon"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>';
        $output .= '<span class="social-sharing-text">' . esc_html__('LinkedIn', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // WhatsApp
    if (in_array('whatsapp', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-whatsapp">';
        $output .= '<a href="' . esc_url($whatsapp_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on WhatsApp', 'aqualuxe') . '">';
        $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="social-icon"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>';
        $output .= '<span class="social-sharing-text">' . esc_html__('WhatsApp', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Telegram
    if (in_array('telegram', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-telegram">';
        $output .= '<a href="' . esc_url($telegram_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on Telegram', 'aqualuxe') . '">';
        $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 496 512" class="social-icon"><path d="M248 8C111 8 0 119 0 256s111 248 248 248 248-111 248-248S385 8 248 8zm121.8 169.9l-40.7 191.8c-3 13.6-11.1 16.9-22.4 10.5l-62-45.7-29.9 28.8c-3.3 3.3-6.1 6.1-12.5 6.1l4.4-63.1 114.9-103.8c5-4.4-1.1-6.9-7.7-2.5l-142 89.4-61.2-19.1c-13.3-4.2-13.6-13.3 2.8-19.7l239.1-92.2c11.1-4 20.8 2.7 17.2 19.5z"/></svg>';
        $output .= '<span class="social-sharing-text">' . esc_html__('Telegram', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Reddit
    if (in_array('reddit', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-reddit">';
        $output .= '<a href="' . esc_url($reddit_url) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Share on Reddit', 'aqualuxe') . '">';
        $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="social-icon"><path d="M201.5 305.5c-13.8 0-24.9-11.1-24.9-24.6 0-13.8 11.1-24.9 24.9-24.9 13.6 0 24.6 11.1 24.6 24.9 0 13.6-11.1 24.6-24.6 24.6zM504 256c0 137-111 248-248 248S8 393 8 256 119 8 256 8s248 111 248 248zm-132.3-41.2c-9.4 0-17.7 3.9-23.8 10-22.4-15.5-52.6-25.5-86.1-26.6l17.4-78.3 55.4 12.5c0 13.6 11.1 24.6 24.6 24.6 13.8 0 24.9-11.3 24.9-24.9s-11.1-24.9-24.9-24.9c-9.7 0-18 5.8-22.1 13.8l-61.2-13.6c-3-.8-6.1 1.4-6.9 4.4l-19.1 86.4c-33.2 1.4-63.1 11.3-85.5 26.8-6.1-6.4-14.7-10.2-24.1-10.2-34.9 0-46.3 46.9-14.4 62.8-1.1 5-1.7 10.2-1.7 15.5 0 52.6 59.2 95.2 132 95.2 73.1 0 132.3-42.6 132.3-95.2 0-5.3-.6-10.8-1.9-15.8 31.3-16 19.8-62.5-14.9-62.5zM302.8 331c-18.2 18.2-76.1 17.9-93.6 0-2.2-2.2-6.1-2.2-8.3 0-2.5 2.5-2.5 6.4 0 8.6 22.8 22.8 87.3 22.8 110.2 0 2.5-2.2 2.5-6.1 0-8.6-2.2-2.2-6.1-2.2-8.3 0zm7.7-75c-13.6 0-24.6 11.1-24.6 24.9 0 13.6 11.1 24.6 24.6 24.6 13.8 0 24.9-11.1 24.9-24.6 0-13.8-11-24.9-24.9-24.9z"/></svg>';
        $output .= '<span class="social-sharing-text">' . esc_html__('Reddit', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Email
    if (in_array('email', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-email">';
        $output .= '<a href="' . esc_url($email_url) . '" aria-label="' . esc_attr__('Share via Email', 'aqualuxe') . '">';
        $output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="social-icon"><path d="M502.3 190.8c3.9-3.1 9.7-.2 9.7 4.7V400c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V195.6c0-5 5.7-7.8 9.7-4.7 22.4 17.4 52.1 39.5 154.1 113.6 21.1 15.4 56.7 47.8 92.2 47.6 35.7.3 72-32.8 92.3-47.6 102-74.1 131.6-96.3 154-113.7zM256 320c23.2.4 56.6-29.2 73.4-41.4 132.7-96.3 142.8-104.7 173.4-128.7 5.8-4.5 9.2-11.5 9.2-18.9v-19c0-26.5-21.5-48-48-48H48C21.5 64 0 85.5 0 112v19c0 7.4 3.4 14.3 9.2 18.9 30.6 23.9 40.7 32.4 173.4 128.7 16.8 12.2 50.2 41.8 73.4 41.4z"/></svg>';
        $output .= '<span class="social-sharing-text">' . esc_html__('Email', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    // Add CSS styles
    $output .= '<style>
        .social-sharing {
            margin: 2rem 0;
            padding: 1rem;
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
        }
        
        .social-sharing-title {
            margin-bottom: 1rem;
            font-size: 1rem;
            font-weight: 600;
        }
        
        .social-sharing-buttons {
            display: flex;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
            list-style: none;
        }
        
        .social-sharing-button {
            margin-right: 0.5rem;
            margin-bottom: 0.5rem;
        }
        
        .social-sharing-button a {
            display: flex;
            align-items: center;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            color: #fff;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }
        
        .social-sharing-button .social-icon {
            width: 1rem;
            height: 1rem;
            margin-right: 0.5rem;
            fill: currentColor;
        }
        
        .social-sharing-text {
            font-size: 0.875rem;
        }
        
        .social-facebook a {
            background-color: #3b5998;
        }
        
        .social-facebook a:hover {
            background-color: #2d4373;
        }
        
        .social-twitter a {
            background-color: #1da1f2;
        }
        
        .social-twitter a:hover {
            background-color: #0c85d0;
        }
        
        .social-pinterest a {
            background-color: #bd081c;
        }
        
        .social-pinterest a:hover {
            background-color: #8c0615;
        }
        
        .social-linkedin a {
            background-color: #0077b5;
        }
        
        .social-linkedin a:hover {
            background-color: #005582;
        }
        
        .social-whatsapp a {
            background-color: #25d366;
        }
        
        .social-whatsapp a:hover {
            background-color: #1da851;
        }
        
        .social-telegram a {
            background-color: #0088cc;
        }
        
        .social-telegram a:hover {
            background-color: #006699;
        }
        
        .social-reddit a {
            background-color: #ff4500;
        }
        
        .social-reddit a:hover {
            background-color: #cc3700;
        }
        
        .social-email a {
            background-color: #777;
        }
        
        .social-email a:hover {
            background-color: #5e5e5e;
        }
        
        @media (max-width: 576px) {
            .social-sharing-buttons {
                justify-content: center;
            }
            
            .social-sharing-button {
                margin-right: 0.25rem;
                margin-left: 0.25rem;
            }
            
            .social-sharing-text {
                display: none;
            }
            
            .social-sharing-button a {
                padding: 0.5rem;
            }
            
            .social-sharing-button .social-icon {
                margin-right: 0;
                width: 1.25rem;
                height: 1.25rem;
            }
        }
        
        .dark-mode .social-sharing {
            border-color: #333;
        }
    </style>';
    
    echo $output;
}
add_action('aqualuxe_after_content', 'aqualuxe_social_sharing_buttons');

/**
 * Add social sharing to single posts.
 */
function aqualuxe_add_social_sharing_to_posts() {
    if (is_singular('post')) {
        do_action('aqualuxe_after_content');
    }
}
add_action('aqualuxe_after_post_content', 'aqualuxe_add_social_sharing_to_posts');

/**
 * Add social sharing to WooCommerce products.
 */
function aqualuxe_add_social_sharing_to_products() {
    if (is_singular('product')) {
        do_action('aqualuxe_after_content');
    }
}
add_action('woocommerce_share', 'aqualuxe_add_social_sharing_to_products');

/**
 * Add social sharing customizer settings.
 *
 * @param WP_Customize_Manager $wp_customize Customizer manager instance.
 */
function aqualuxe_social_sharing_customizer($wp_customize) {
    // Add social sharing section
    $wp_customize->add_section('aqualuxe_social_sharing', array(
        'title' => __('Social Sharing', 'aqualuxe'),
        'priority' => 120,
        'description' => __('Configure social sharing options.', 'aqualuxe'),
    ));
    
    // Add social sharing enable setting
    $wp_customize->add_setting('aqualuxe_enable_social_sharing', array(
        'default' => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('aqualuxe_enable_social_sharing', array(
        'label' => __('Enable Social Sharing', 'aqualuxe'),
        'section' => 'aqualuxe_social_sharing',
        'type' => 'checkbox',
    ));
    
    // Add social networks setting
    $wp_customize->add_setting('aqualuxe_social_sharing_networks', array(
        'default' => array('facebook', 'twitter', 'pinterest', 'linkedin', 'email'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_choices',
    ));
    
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'aqualuxe_social_sharing_networks', array(
        'label' => __('Social Networks', 'aqualuxe'),
        'section' => 'aqualuxe_social_sharing',
        'type' => 'select',
        'choices' => array(
            'facebook' => __('Facebook', 'aqualuxe'),
            'twitter' => __('Twitter', 'aqualuxe'),
            'pinterest' => __('Pinterest', 'aqualuxe'),
            'linkedin' => __('LinkedIn', 'aqualuxe'),
            'whatsapp' => __('WhatsApp', 'aqualuxe'),
            'telegram' => __('Telegram', 'aqualuxe'),
            'reddit' => __('Reddit', 'aqualuxe'),
            'email' => __('Email', 'aqualuxe'),
        ),
        'multiple' => true,
    )));
    
    // Add social sharing position setting
    $wp_customize->add_setting('aqualuxe_social_sharing_position', array(
        'default' => 'after',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_social_sharing_position', array(
        'label' => __('Sharing Position', 'aqualuxe'),
        'section' => 'aqualuxe_social_sharing',
        'type' => 'select',
        'choices' => array(
            'before' => __('Before Content', 'aqualuxe'),
            'after' => __('After Content', 'aqualuxe'),
            'both' => __('Before and After Content', 'aqualuxe'),
        ),
    ));
    
    // Add social sharing style setting
    $wp_customize->add_setting('aqualuxe_social_sharing_style', array(
        'default' => 'buttons',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ));
    
    $wp_customize->add_control('aqualuxe_social_sharing_style', array(
        'label' => __('Sharing Style', 'aqualuxe'),
        'section' => 'aqualuxe_social_sharing',
        'type' => 'select',
        'choices' => array(
            'buttons' => __('Buttons', 'aqualuxe'),
            'icons' => __('Icons Only', 'aqualuxe'),
            'minimal' => __('Minimal', 'aqualuxe'),
        ),
    ));
    
    // Add social sharing title setting
    $wp_customize->add_setting('aqualuxe_social_sharing_title', array(
        'default' => __('Share this:', 'aqualuxe'),
        'sanitize_callback' => 'sanitize_text_field',
    ));
    
    $wp_customize->add_control('aqualuxe_social_sharing_title', array(
        'label' => __('Sharing Title', 'aqualuxe'),
        'section' => 'aqualuxe_social_sharing',
        'type' => 'text',
    ));
    
    // Add social sharing post types setting
    $wp_customize->add_setting('aqualuxe_social_sharing_post_types', array(
        'default' => array('post', 'product'),
        'sanitize_callback' => 'aqualuxe_sanitize_multi_choices',
    ));
    
    $post_types = get_post_types(array('public' => true), 'objects');
    $post_type_choices = array();
    
    foreach ($post_types as $post_type) {
        $post_type_choices[$post_type->name] = $post_type->label;
    }
    
    $wp_customize->add_control(new WP_Customize_Control($wp_customize, 'aqualuxe_social_sharing_post_types', array(
        'label' => __('Post Types', 'aqualuxe'),
        'section' => 'aqualuxe_social_sharing',
        'type' => 'select',
        'choices' => $post_type_choices,
        'multiple' => true,
    )));
}
add_action('customize_register', 'aqualuxe_social_sharing_customizer');

/**
 * Sanitize checkbox.
 *
 * @param bool $checked Whether the checkbox is checked.
 * @return bool
 */
function aqualuxe_sanitize_checkbox($checked) {
    return (isset($checked) && true === $checked) ? true : false;
}

/**
 * Sanitize select.
 *
 * @param string $input Select value.
 * @param WP_Customize_Setting $setting Setting instance.
 * @return string
 */
function aqualuxe_sanitize_select($input, $setting) {
    $input = sanitize_key($input);
    $choices = $setting->manager->get_control($setting->id)->choices;
    
    return (array_key_exists($input, $choices) ? $input : $setting->default);
}

/**
 * Sanitize multi choices.
 *
 * @param array $values Values to sanitize.
 * @return array
 */
function aqualuxe_sanitize_multi_choices($values) {
    $multi_values = !is_array($values) ? explode(',', $values) : $values;
    
    return !empty($multi_values) ? array_map('sanitize_text_field', $multi_values) : array();
}

/**
 * Add Open Graph meta tags for better social sharing.
 */
function aqualuxe_add_opengraph_tags() {
    // Check if social sharing is enabled
    $enable_social_sharing = get_theme_mod('aqualuxe_enable_social_sharing', true);
    
    if (!$enable_social_sharing) {
        return;
    }
    
    // Only add on single posts and products
    if (!is_singular()) {
        return;
    }
    
    global $post;
    
    // Get post title
    $og_title = get_the_title();
    
    // Get post excerpt
    $og_description = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 55, '...');
    $og_description = strip_tags($og_description);
    
    // Get post URL
    $og_url = get_permalink();
    
    // Get post thumbnail
    $og_image = '';
    if (has_post_thumbnail()) {
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'large');
        $og_image = $thumbnail_url[0];
    }
    
    // Get site name
    $og_site_name = get_bloginfo('name');
    
    // Get post type
    $og_type = is_singular('product') ? 'product' : 'article';
    
    // Output Open Graph tags
    echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
    echo '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
    
    if (!empty($og_image)) {
        echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
    
    echo '<meta property="og:site_name" content="' . esc_attr($og_site_name) . '" />' . "\n";
    echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    
    // Output Twitter Card tags
    echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
    echo '<meta name="twitter:title" content="' . esc_attr($og_title) . '" />' . "\n";
    echo '<meta name="twitter:description" content="' . esc_attr($og_description) . '" />' . "\n";
    
    if (!empty($og_image)) {
        echo '<meta name="twitter:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_add_opengraph_tags');

/**
 * Add social sharing buttons to the content.
 *
 * @param string $content Post content.
 * @return string
 */
function aqualuxe_add_social_sharing_to_content($content) {
    // Check if social sharing is enabled
    $enable_social_sharing = get_theme_mod('aqualuxe_enable_social_sharing', true);
    
    if (!$enable_social_sharing) {
        return $content;
    }
    
    // Check if we're on a single post or product
    if (!is_singular()) {
        return $content;
    }
    
    // Get sharing position
    $position = get_theme_mod('aqualuxe_social_sharing_position', 'after');
    
    // Get post types to display sharing buttons on
    $post_types = get_theme_mod('aqualuxe_social_sharing_post_types', array('post', 'product'));
    
    // Check if current post type is enabled
    if (!in_array(get_post_type(), $post_types)) {
        return $content;
    }
    
    // Start output buffering
    ob_start();
    aqualuxe_social_sharing_buttons();
    $sharing_buttons = ob_get_clean();
    
    // Add sharing buttons based on position
    if ($position === 'before') {
        return $sharing_buttons . $content;
    } elseif ($position === 'after') {
        return $content . $sharing_buttons;
    } elseif ($position === 'both') {
        return $sharing_buttons . $content . $sharing_buttons;
    }
    
    return $content;
}
add_filter('the_content', 'aqualuxe_add_social_sharing_to_content');

/**
 * Add social sharing buttons to WooCommerce product summary.
 */
function aqualuxe_add_social_sharing_to_product_summary() {
    // Check if social sharing is enabled
    $enable_social_sharing = get_theme_mod('aqualuxe_enable_social_sharing', true);
    
    if (!$enable_social_sharing) {
        return;
    }
    
    // Get post types to display sharing buttons on
    $post_types = get_theme_mod('aqualuxe_social_sharing_post_types', array('post', 'product'));
    
    // Check if product post type is enabled
    if (!in_array('product', $post_types)) {
        return;
    }
    
    // Get sharing position
    $position = get_theme_mod('aqualuxe_social_sharing_position', 'after');
    
    // Only add to product summary if position is 'before' or 'both'
    if ($position === 'before' || $position === 'both') {
        aqualuxe_social_sharing_buttons();
    }
}
add_action('woocommerce_single_product_summary', 'aqualuxe_add_social_sharing_to_product_summary', 50);

/**
 * Add social sharing buttons to WooCommerce after product summary.
 */
function aqualuxe_add_social_sharing_after_product_summary() {
    // Check if social sharing is enabled
    $enable_social_sharing = get_theme_mod('aqualuxe_enable_social_sharing', true);
    
    if (!$enable_social_sharing) {
        return;
    }
    
    // Get post types to display sharing buttons on
    $post_types = get_theme_mod('aqualuxe_social_sharing_post_types', array('post', 'product'));
    
    // Check if product post type is enabled
    if (!in_array('product', $post_types)) {
        return;
    }
    
    // Get sharing position
    $position = get_theme_mod('aqualuxe_social_sharing_position', 'after');
    
    // Only add after product summary if position is 'after' or 'both'
    if ($position === 'after' || $position === 'both') {
        aqualuxe_social_sharing_buttons();
    }
}
add_action('woocommerce_after_single_product_summary', 'aqualuxe_add_social_sharing_after_product_summary', 11);

/**
 * Add social sharing shortcode.
 *
 * @param array $atts Shortcode attributes.
 * @return string
 */
function aqualuxe_social_sharing_shortcode($atts) {
    // Check if social sharing is enabled
    $enable_social_sharing = get_theme_mod('aqualuxe_enable_social_sharing', true);
    
    if (!$enable_social_sharing) {
        return '';
    }
    
    // Start output buffering
    ob_start();
    aqualuxe_social_sharing_buttons();
    return ob_get_clean();
}
add_shortcode('aqualuxe_social_sharing', 'aqualuxe_social_sharing_shortcode');

/**
 * Add social sharing widget.
 */
class Aqualuxe_Social_Sharing_Widget extends WP_Widget {
    /**
     * Constructor.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_social_sharing_widget',
            __('AquaLuxe Social Sharing', 'aqualuxe'),
            array(
                'description' => __('Display social sharing buttons.', 'aqualuxe'),
            )
        );
    }
    
    /**
     * Widget output.
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     */
    public function widget($args, $instance) {
        // Check if social sharing is enabled
        $enable_social_sharing = get_theme_mod('aqualuxe_enable_social_sharing', true);
        
        if (!$enable_social_sharing) {
            return;
        }
        
        // Only show on single posts and products
        if (!is_singular()) {
            return;
        }
        
        $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : '';
        
        echo $args['before_widget'];
        
        if (!empty($title)) {
            echo $args['before_title'] . $title . $args['after_title'];
        }
        
        aqualuxe_social_sharing_buttons();
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget form.
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Share This', 'aqualuxe');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }
    
    /**
     * Widget update.
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = !empty($new_instance['title']) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

/**
 * Register social sharing widget.
 */
function aqualuxe_register_social_sharing_widget() {
    register_widget('Aqualuxe_Social_Sharing_Widget');
}
add_action('widgets_init', 'aqualuxe_register_social_sharing_widget');

/**
 * Add social sharing to AMP pages.
 *
 * @param array $data AMP data.
 * @return array
 */
function aqualuxe_add_social_sharing_to_amp($data) {
    // Check if social sharing is enabled
    $enable_social_sharing = get_theme_mod('aqualuxe_enable_social_sharing', true);
    
    if (!$enable_social_sharing) {
        return $data;
    }
    
    // Only add on single posts and products
    if (!is_singular()) {
        return $data;
    }
    
    // Get post types to display sharing buttons on
    $post_types = get_theme_mod('aqualuxe_social_sharing_post_types', array('post', 'product'));
    
    // Check if current post type is enabled
    if (!in_array(get_post_type(), $post_types)) {
        return $data;
    }
    
    // Get current page URL
    $url = urlencode(get_permalink());
    
    // Get current page title
    $title = urlencode(get_the_title());
    
    // Get current page thumbnail
    $thumbnail = '';
    if (has_post_thumbnail()) {
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail_url = wp_get_attachment_image_src($thumbnail_id, 'full');
        $thumbnail = urlencode($thumbnail_url[0]);
    }
    
    // Get enabled social networks
    $enabled_networks = get_theme_mod('aqualuxe_social_sharing_networks', array('facebook', 'twitter', 'pinterest', 'linkedin', 'email'));
    
    // Define sharing URLs
    $facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
    $twitter_url = 'https://twitter.com/intent/tweet?text=' . $title . '&url=' . $url;
    $pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $thumbnail . '&description=' . $title;
    $linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title;
    $email_url = 'mailto:?subject=' . $title . '&body=' . $url;
    $whatsapp_url = 'https://api.whatsapp.com/send?text=' . $title . ' ' . $url;
    $telegram_url = 'https://telegram.me/share/url?url=' . $url . '&text=' . $title;
    $reddit_url = 'https://reddit.com/submit?url=' . $url . '&title=' . $title;
    
    // Start sharing buttons output
    $output = '<div class="social-sharing">';
    $output .= '<h4 class="social-sharing-title">' . esc_html__('Share this:', 'aqualuxe') . '</h4>';
    $output .= '<ul class="social-sharing-buttons">';
    
    // Facebook
    if (in_array('facebook', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-facebook">';
        $output .= '<a href="' . esc_url($facebook_url) . '" target="_blank" rel="noopener noreferrer">';
        $output .= '<span class="social-sharing-text">' . esc_html__('Facebook', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Twitter
    if (in_array('twitter', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-twitter">';
        $output .= '<a href="' . esc_url($twitter_url) . '" target="_blank" rel="noopener noreferrer">';
        $output .= '<span class="social-sharing-text">' . esc_html__('Twitter', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Pinterest (only show if we have a thumbnail)
    if (in_array('pinterest', $enabled_networks) && !empty($thumbnail)) {
        $output .= '<li class="social-sharing-button social-pinterest">';
        $output .= '<a href="' . esc_url($pinterest_url) . '" target="_blank" rel="noopener noreferrer">';
        $output .= '<span class="social-sharing-text">' . esc_html__('Pinterest', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // LinkedIn
    if (in_array('linkedin', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-linkedin">';
        $output .= '<a href="' . esc_url($linkedin_url) . '" target="_blank" rel="noopener noreferrer">';
        $output .= '<span class="social-sharing-text">' . esc_html__('LinkedIn', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // WhatsApp
    if (in_array('whatsapp', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-whatsapp">';
        $output .= '<a href="' . esc_url($whatsapp_url) . '" target="_blank" rel="noopener noreferrer">';
        $output .= '<span class="social-sharing-text">' . esc_html__('WhatsApp', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Telegram
    if (in_array('telegram', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-telegram">';
        $output .= '<a href="' . esc_url($telegram_url) . '" target="_blank" rel="noopener noreferrer">';
        $output .= '<span class="social-sharing-text">' . esc_html__('Telegram', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Reddit
    if (in_array('reddit', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-reddit">';
        $output .= '<a href="' . esc_url($reddit_url) . '" target="_blank" rel="noopener noreferrer">';
        $output .= '<span class="social-sharing-text">' . esc_html__('Reddit', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    // Email
    if (in_array('email', $enabled_networks)) {
        $output .= '<li class="social-sharing-button social-email">';
        $output .= '<a href="' . esc_url($email_url) . '">';
        $output .= '<span class="social-sharing-text">' . esc_html__('Email', 'aqualuxe') . '</span>';
        $output .= '</a>';
        $output .= '</li>';
    }
    
    $output .= '</ul>';
    $output .= '</div>';
    
    // Add sharing buttons to AMP content
    $data['post_amp_content'] .= $output;
    
    return $data;
}
add_filter('amp_post_template_data', 'aqualuxe_add_social_sharing_to_amp');

/**
 * Add social sharing CSS to AMP pages.
 *
 * @param array $data AMP data.
 * @return array
 */
function aqualuxe_add_social_sharing_css_to_amp($data) {
    // Check if social sharing is enabled
    $enable_social_sharing = get_theme_mod('aqualuxe_enable_social_sharing', true);
    
    if (!$enable_social_sharing) {
        return $data;
    }
    
    // Only add on single posts and products
    if (!is_singular()) {
        return $data;
    }
    
    // Get post types to display sharing buttons on
    $post_types = get_theme_mod('aqualuxe_social_sharing_post_types', array('post', 'product'));
    
    // Check if current post type is enabled
    if (!in_array(get_post_type(), $post_types)) {
        return $data;
    }
    
    // Add CSS
    $data['post_amp_stylesheets'] = array_merge(
        $data['post_amp_stylesheets'],
        array(
            'aqualuxe-social-sharing' => '
                .social-sharing {
                    margin: 2rem 0;
                    padding: 1rem;
                    border-top: 1px solid #eee;
                    border-bottom: 1px solid #eee;
                }
                
                .social-sharing-title {
                    margin-bottom: 1rem;
                    font-size: 1rem;
                    font-weight: 600;
                }
                
                .social-sharing-buttons {
                    display: flex;
                    flex-wrap: wrap;
                    margin: 0;
                    padding: 0;
                    list-style: none;
                }
                
                .social-sharing-button {
                    margin-right: 0.5rem;
                    margin-bottom: 0.5rem;
                }
                
                .social-sharing-button a {
                    display: flex;
                    align-items: center;
                    padding: 0.5rem 1rem;
                    border-radius: 4px;
                    color: #fff;
                    text-decoration: none;
                }
                
                .social-sharing-text {
                    font-size: 0.875rem;
                }
                
                .social-facebook a {
                    background-color: #3b5998;
                }
                
                .social-twitter a {
                    background-color: #1da1f2;
                }
                
                .social-pinterest a {
                    background-color: #bd081c;
                }
                
                .social-linkedin a {
                    background-color: #0077b5;
                }
                
                .social-whatsapp a {
                    background-color: #25d366;
                }
                
                .social-telegram a {
                    background-color: #0088cc;
                }
                
                .social-reddit a {
                    background-color: #ff4500;
                }
                
                .social-email a {
                    background-color: #777;
                }
                
                @media (max-width: 576px) {
                    .social-sharing-buttons {
                        justify-content: center;
                    }
                    
                    .social-sharing-button {
                        margin-right: 0.25rem;
                        margin-left: 0.25rem;
                    }
                }
            '
        )
    );
    
    return $data;
}
add_filter('amp_post_template_data', 'aqualuxe_add_social_sharing_css_to_amp');