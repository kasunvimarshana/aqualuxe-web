<?php
/**
 * AquaLuxe Menus Setup
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Get menu by location
 *
 * @param string $location Menu location
 * @return object|false
 */
function aqualuxe_get_menu_by_location($location) {
    $locations = get_nav_menu_locations();
    
    if (!isset($locations[$location])) {
        return false;
    }
    
    $menu_id = $locations[$location];
    
    return wp_get_nav_menu_object($menu_id);
}

/**
 * Get menu items by location
 *
 * @param string $location Menu location
 * @return array
 */
function aqualuxe_get_menu_items_by_location($location) {
    $menu = aqualuxe_get_menu_by_location($location);
    
    if (!$menu) {
        return [];
    }
    
    return wp_get_nav_menu_items($menu->term_id);
}

/**
 * Display primary menu
 *
 * @param array $args Menu arguments
 */
function aqualuxe_primary_menu($args = []) {
    $defaults = [
        'theme_location'  => 'primary',
        'container'       => 'nav',
        'container_class' => 'primary-menu-container',
        'container_id'    => 'primary-menu-container',
        'menu_class'      => 'primary-menu',
        'menu_id'         => 'primary-menu',
        'fallback_cb'     => 'aqualuxe_primary_menu_fallback',
        'walker'          => new AquaLuxe_Walker_Nav_Menu(),
        'depth'           => 3,
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    wp_nav_menu($args);
}

/**
 * Primary menu fallback
 */
function aqualuxe_primary_menu_fallback() {
    ?>
    <nav id="primary-menu-container" class="primary-menu-container">
        <ul id="primary-menu" class="primary-menu">
            <li class="menu-item">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'aqualuxe'); ?></a>
            </li>
            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                <li class="menu-item">
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>"><?php esc_html_e('Shop', 'aqualuxe'); ?></a>
                </li>
            <?php endif; ?>
            <li class="menu-item">
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>"><?php esc_html_e('Blog', 'aqualuxe'); ?></a>
            </li>
            <li class="menu-item">
                <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php esc_html_e('Add a menu', 'aqualuxe'); ?></a>
            </li>
        </ul>
    </nav>
    <?php
}

/**
 * Display secondary menu
 *
 * @param array $args Menu arguments
 */
function aqualuxe_secondary_menu($args = []) {
    $defaults = [
        'theme_location'  => 'secondary',
        'container'       => 'nav',
        'container_class' => 'secondary-menu-container',
        'container_id'    => 'secondary-menu-container',
        'menu_class'      => 'secondary-menu',
        'menu_id'         => 'secondary-menu',
        'fallback_cb'     => 'aqualuxe_secondary_menu_fallback',
        'walker'          => new AquaLuxe_Walker_Nav_Menu(),
        'depth'           => 2,
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    wp_nav_menu($args);
}

/**
 * Secondary menu fallback
 */
function aqualuxe_secondary_menu_fallback() {
    ?>
    <nav id="secondary-menu-container" class="secondary-menu-container">
        <ul id="secondary-menu" class="secondary-menu">
            <?php if (aqualuxe_is_woocommerce_active()) : ?>
                <li class="menu-item">
                    <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"><?php esc_html_e('My Account', 'aqualuxe'); ?></a>
                </li>
                <li class="menu-item">
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>"><?php esc_html_e('Cart', 'aqualuxe'); ?></a>
                </li>
            <?php endif; ?>
            <?php if (is_user_logged_in()) : ?>
                <li class="menu-item">
                    <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>"><?php esc_html_e('Logout', 'aqualuxe'); ?></a>
                </li>
            <?php else : ?>
                <li class="menu-item">
                    <a href="<?php echo esc_url(wp_login_url()); ?>"><?php esc_html_e('Login', 'aqualuxe'); ?></a>
                </li>
                <li class="menu-item">
                    <a href="<?php echo esc_url(wp_registration_url()); ?>"><?php esc_html_e('Register', 'aqualuxe'); ?></a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
    <?php
}

/**
 * Display footer menu
 *
 * @param array $args Menu arguments
 */
function aqualuxe_footer_menu($args = []) {
    $defaults = [
        'theme_location'  => 'footer',
        'container'       => 'nav',
        'container_class' => 'footer-menu-container',
        'container_id'    => 'footer-menu-container',
        'menu_class'      => 'footer-menu',
        'menu_id'         => 'footer-menu',
        'fallback_cb'     => 'aqualuxe_footer_menu_fallback',
        'depth'           => 1,
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    wp_nav_menu($args);
}

/**
 * Footer menu fallback
 */
function aqualuxe_footer_menu_fallback() {
    ?>
    <nav id="footer-menu-container" class="footer-menu-container">
        <ul id="footer-menu" class="footer-menu">
            <li class="menu-item">
                <a href="<?php echo esc_url(home_url('/')); ?>"><?php esc_html_e('Home', 'aqualuxe'); ?></a>
            </li>
            <?php if (get_privacy_policy_url()) : ?>
                <li class="menu-item">
                    <a href="<?php echo esc_url(get_privacy_policy_url()); ?>"><?php esc_html_e('Privacy Policy', 'aqualuxe'); ?></a>
                </li>
            <?php endif; ?>
            <li class="menu-item">
                <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php esc_html_e('Add a menu', 'aqualuxe'); ?></a>
            </li>
        </ul>
    </nav>
    <?php
}

/**
 * Display social menu
 *
 * @param array $args Menu arguments
 */
function aqualuxe_social_menu($args = []) {
    $defaults = [
        'theme_location'  => 'social',
        'container'       => 'nav',
        'container_class' => 'social-menu-container',
        'container_id'    => 'social-menu-container',
        'menu_class'      => 'social-menu',
        'menu_id'         => 'social-menu',
        'fallback_cb'     => 'aqualuxe_social_menu_fallback',
        'link_before'     => '<span class="screen-reader-text">',
        'link_after'      => '</span>',
        'depth'           => 1,
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    wp_nav_menu($args);
}

/**
 * Social menu fallback
 */
function aqualuxe_social_menu_fallback() {
    ?>
    <nav id="social-menu-container" class="social-menu-container">
        <ul id="social-menu" class="social-menu">
            <li class="menu-item">
                <a href="https://facebook.com/" target="_blank" rel="noopener noreferrer">
                    <?php aqualuxe_svg_icon('facebook'); ?>
                    <span class="screen-reader-text"><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                </a>
            </li>
            <li class="menu-item">
                <a href="https://twitter.com/" target="_blank" rel="noopener noreferrer">
                    <?php aqualuxe_svg_icon('twitter'); ?>
                    <span class="screen-reader-text"><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                </a>
            </li>
            <li class="menu-item">
                <a href="https://instagram.com/" target="_blank" rel="noopener noreferrer">
                    <?php aqualuxe_svg_icon('instagram'); ?>
                    <span class="screen-reader-text"><?php esc_html_e('Instagram', 'aqualuxe'); ?></span>
                </a>
            </li>
            <li class="menu-item">
                <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>">
                    <?php aqualuxe_svg_icon('plus'); ?>
                    <span class="screen-reader-text"><?php esc_html_e('Add a menu', 'aqualuxe'); ?></span>
                </a>
            </li>
        </ul>
    </nav>
    <?php
}

/**
 * Get social icon from URL
 *
 * @param string $url URL
 * @return string
 */
function aqualuxe_get_social_icon_from_url($url) {
    $icon = '';
    
    if (strpos($url, 'facebook.com') !== false) {
        $icon = 'facebook';
    } elseif (strpos($url, 'twitter.com') !== false) {
        $icon = 'twitter';
    } elseif (strpos($url, 'instagram.com') !== false) {
        $icon = 'instagram';
    } elseif (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
        $icon = 'youtube';
    } elseif (strpos($url, 'linkedin.com') !== false) {
        $icon = 'linkedin';
    } elseif (strpos($url, 'pinterest.com') !== false) {
        $icon = 'pinterest';
    } elseif (strpos($url, 'github.com') !== false) {
        $icon = 'github';
    } elseif (strpos($url, 'dribbble.com') !== false) {
        $icon = 'dribbble';
    } elseif (strpos($url, 'behance.net') !== false) {
        $icon = 'behance';
    } elseif (strpos($url, 'flickr.com') !== false) {
        $icon = 'flickr';
    } elseif (strpos($url, 'spotify.com') !== false) {
        $icon = 'spotify';
    } elseif (strpos($url, 'soundcloud.com') !== false) {
        $icon = 'soundcloud';
    } elseif (strpos($url, 'vimeo.com') !== false) {
        $icon = 'vimeo';
    } elseif (strpos($url, 'twitch.tv') !== false) {
        $icon = 'twitch';
    } elseif (strpos($url, 'reddit.com') !== false) {
        $icon = 'reddit';
    } elseif (strpos($url, 'tumblr.com') !== false) {
        $icon = 'tumblr';
    } elseif (strpos($url, 'whatsapp.com') !== false) {
        $icon = 'whatsapp';
    } elseif (strpos($url, 'telegram.org') !== false) {
        $icon = 'telegram';
    } elseif (strpos($url, 'tiktok.com') !== false) {
        $icon = 'tiktok';
    } elseif (strpos($url, 'snapchat.com') !== false) {
        $icon = 'snapchat';
    } elseif (strpos($url, 'discord.com') !== false || strpos($url, 'discord.gg') !== false) {
        $icon = 'discord';
    } elseif (strpos($url, 'mailto:') !== false) {
        $icon = 'mail';
    } elseif (strpos($url, 'tel:') !== false) {
        $icon = 'phone';
    } else {
        $icon = 'globe';
    }
    
    return $icon;
}

/**
 * Add social icons to menu items
 *
 * @param string $items Menu items HTML
 * @param object $args Menu arguments
 * @return string
 */
function aqualuxe_social_icons_in_menu($items, $args) {
    if ($args->theme_location === 'social') {
        $dom = new DOMDocument();
        @$dom->loadHTML(mb_convert_encoding($items, 'HTML-ENTITIES', 'UTF-8'));
        $links = $dom->getElementsByTagName('a');
        
        foreach ($links as $link) {
            $url = $link->getAttribute('href');
            $icon = aqualuxe_get_social_icon_from_url($url);
            
            if ($icon) {
                $icon_html = aqualuxe_get_svg_icon($icon);
                
                if ($icon_html) {
                    $fragment = $dom->createDocumentFragment();
                    @$fragment->appendXML($icon_html);
                    
                    // Insert icon before the text
                    $link->insertBefore($fragment, $link->firstChild);
                }
            }
        }
        
        $items = $dom->saveHTML();
    }
    
    return $items;
}
add_filter('wp_nav_menu_items', 'aqualuxe_social_icons_in_menu', 10, 2);

/**
 * Add menu item classes
 *
 * @param array $classes Menu item classes
 * @param object $item Menu item
 * @param object $args Menu arguments
 * @return array
 */
function aqualuxe_menu_item_classes($classes, $item, $args) {
    // Add Tailwind classes to menu items
    if ($args->theme_location === 'primary') {
        $classes[] = 'primary-menu-item';
    } elseif ($args->theme_location === 'secondary') {
        $classes[] = 'secondary-menu-item';
    } elseif ($args->theme_location === 'footer') {
        $classes[] = 'footer-menu-item';
    } elseif ($args->theme_location === 'social') {
        $classes[] = 'social-menu-item';
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_menu_item_classes', 10, 3);

/**
 * Add menu link classes
 *
 * @param array $atts Menu link attributes
 * @param object $item Menu item
 * @param object $args Menu arguments
 * @return array
 */
function aqualuxe_menu_link_classes($atts, $item, $args) {
    // Add Tailwind classes to menu links
    if ($args->theme_location === 'primary') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' primary-menu-link' : 'primary-menu-link';
    } elseif ($args->theme_location === 'secondary') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' secondary-menu-link' : 'secondary-menu-link';
    } elseif ($args->theme_location === 'footer') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' footer-menu-link' : 'footer-menu-link';
    } elseif ($args->theme_location === 'social') {
        $atts['class'] = isset($atts['class']) ? $atts['class'] . ' social-menu-link' : 'social-menu-link';
    }
    
    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_menu_link_classes', 10, 3);