<?php
/**
 * AquaLuxe Template Functions
 *
 * Functions for the templating system.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Lazy load images
 * 
 * Modifies image HTML to support lazy loading
 * 
 * @param string $html The HTML image tag
 * @param int $id The attachment ID
 * @param string $alt The image alt text
 * @param string $title The image title
 * @return string Modified HTML image tag with lazy loading
 */
function aqualuxe_lazy_load_images($html, $id, $alt, $title) {
    // Skip lazy loading for admin, feed, or if the image is already lazy loaded
    if (is_admin() || is_feed() || strpos($html, 'data-src') !== false) {
        return $html;
    }
    
    // Don't lazy load images in the admin bar
    if (strpos($html, 'admin-bar') !== false) {
        return $html;
    }
    
    // Get image source
    preg_match('/src="([^"]+)"/', $html, $src_matches);
    
    // If no src found, return original HTML
    if (!isset($src_matches[1])) {
        return $html;
    }
    
    $src = $src_matches[1];
    
    // Create placeholder image (1x1 transparent GIF)
    $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    
    // Replace src with placeholder and add data-src
    $html = str_replace('src="' . $src . '"', 'src="' . $placeholder . '" data-src="' . $src . '"', $html);
    
    // Add lazy class
    $html = str_replace('<img', '<img class="lazy"', $html);
    
    // Handle srcset if present
    if (strpos($html, 'srcset') !== false) {
        preg_match('/srcset="([^"]+)"/', $html, $srcset_matches);
        
        if (isset($srcset_matches[1])) {
            $srcset = $srcset_matches[1];
            $html = str_replace('srcset="' . $srcset . '"', 'data-srcset="' . $srcset . '"', $html);
        }
    }
    
    // Add noscript fallback
    $noscript = '<noscript>' . str_replace('class="lazy"', '', str_replace('src="' . $placeholder . '" data-src="', 'src="', $html)) . '</noscript>';
    
    return $html . $noscript;
}
add_filter('get_image_tag', 'aqualuxe_lazy_load_images', 10, 4);

/**
 * Lazy load post thumbnails
 * 
 * Adds lazy loading to post thumbnails
 * 
 * @param string $html The post thumbnail HTML
 * @return string Modified HTML with lazy loading
 */
function aqualuxe_lazy_load_post_thumbnails($html) {
    // Skip lazy loading for admin or feed
    if (is_admin() || is_feed()) {
        return $html;
    }
    
    // If already lazy loaded, return original HTML
    if (strpos($html, 'data-src') !== false) {
        return $html;
    }
    
    // Get image source
    preg_match('/src="([^"]+)"/', $html, $src_matches);
    
    // If no src found, return original HTML
    if (!isset($src_matches[1])) {
        return $html;
    }
    
    $src = $src_matches[1];
    
    // Create placeholder image (1x1 transparent GIF)
    $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
    
    // Replace src with placeholder and add data-src
    $html = str_replace('src="' . $src . '"', 'src="' . $placeholder . '" data-src="' . $src . '"', $html);
    
    // Add lazy class
    if (strpos($html, 'class="') !== false) {
        $html = str_replace('class="', 'class="lazy ', $html);
    } else {
        $html = str_replace('<img', '<img class="lazy"', $html);
    }
    
    // Handle srcset if present
    if (strpos($html, 'srcset') !== false) {
        preg_match('/srcset="([^"]+)"/', $html, $srcset_matches);
        
        if (isset($srcset_matches[1])) {
            $srcset = $srcset_matches[1];
            $html = str_replace('srcset="' . $srcset . '"', 'data-srcset="' . $srcset . '"', $html);
        }
    }
    
    // Add noscript fallback
    $noscript = '<noscript>' . str_replace(array('class="lazy ', 'class="lazy"'), array('class="', ''), str_replace('src="' . $placeholder . '" data-src="', 'src="', $html)) . '</noscript>';
    
    return $html . $noscript;
}
add_filter('post_thumbnail_html', 'aqualuxe_lazy_load_post_thumbnails', 10, 1);

/**
 * Lazy load content images
 * 
 * Adds lazy loading to images in post content
 * 
 * @param string $content The post content
 * @return string Modified content with lazy loaded images
 */
function aqualuxe_lazy_load_content_images($content) {
    // Skip lazy loading for admin or feed
    if (is_admin() || is_feed()) {
        return $content;
    }
    
    // If no image in content, return original content
    if (strpos($content, '<img') === false) {
        return $content;
    }
    
    // Regular expression to find all images in content
    $pattern = '/<img([^>]+)>/i';
    
    // Replace each image with lazy loading version
    $content = preg_replace_callback($pattern, function($matches) {
        $image_html = $matches[0];
        
        // If already lazy loaded, return original HTML
        if (strpos($image_html, 'data-src') !== false) {
            return $image_html;
        }
        
        // Get image source
        preg_match('/src="([^"]+)"/', $image_html, $src_matches);
        
        // If no src found, return original HTML
        if (!isset($src_matches[1])) {
            return $image_html;
        }
        
        $src = $src_matches[1];
        
        // Create placeholder image (1x1 transparent GIF)
        $placeholder = 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7';
        
        // Replace src with placeholder and add data-src
        $image_html = str_replace('src="' . $src . '"', 'src="' . $placeholder . '" data-src="' . $src . '"', $image_html);
        
        // Add lazy class
        if (strpos($image_html, 'class="') !== false) {
            $image_html = str_replace('class="', 'class="lazy ', $image_html);
        } else {
            $image_html = str_replace('<img', '<img class="lazy"', $image_html);
        }
        
        // Handle srcset if present
        if (strpos($image_html, 'srcset') !== false) {
            preg_match('/srcset="([^"]+)"/', $image_html, $srcset_matches);
            
            if (isset($srcset_matches[1])) {
                $srcset = $srcset_matches[1];
                $image_html = str_replace('srcset="' . $srcset . '"', 'data-srcset="' . $srcset . '"', $image_html);
            }
        }
        
        // Add noscript fallback
        $noscript = '<noscript>' . str_replace(array('class="lazy ', 'class="lazy"'), array('class="', ''), str_replace('src="' . $placeholder . '" data-src="', 'src="', $image_html)) . '</noscript>';
        
        return $image_html . $noscript;
    }, $content);
    
    return $content;
}
add_filter('the_content', 'aqualuxe_lazy_load_content_images', 99);/** * Display site logo */function aqualuxe_site_logo() {    if (has_custom_logo()) {        the_custom_logo();    } else {        ?>        <a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="site-title">            <?php bloginfo('name'); ?>        </a>        <?php        $description = get_bloginfo('description', 'display');        if ($description || is_customize_preview()) {            ?>            <p class="site-description"><?php echo esc_html($description); ?></p>            <?php        }    }}/** * Display primary navigation */function aqualuxe_primary_navigation() {    ?>    <nav id="site-navigation" class="main-navigation" aria-label="<?php esc_attr_e('Primary Navigation', 'aqualuxe'); ?>">        <button class="menu-toggle" aria-controls="primary-menu" aria-expanded="false">            <span class="menu-toggle-icon">                <span></span>                <span></span>                <span></span>            </span>            <span class="screen-reader-text"><?php esc_html_e('Menu', 'aqualuxe'); ?></span>        </button>        <?php        wp_nav_menu(array(            'theme_location' => 'primary',            'menu_id'        => 'primary-menu',            'container'      => false,            'fallback_cb'    => 'aqualuxe_primary_menu_fallback',        ));        ?>    </nav>    <?php}/** * Primary menu fallback */function aqualuxe_primary_menu_fallback() {    ?>    <ul id="primary-menu" class="menu">        <li class="menu-item">            <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>"><?php esc_html_e('Add a menu', 'aqualuxe'); ?></a>        </li>    </ul>    <?php}/** * Display secondary navigation */function aqualuxe_secondary_navigation() {    if (has_nav_menu('secondary')) {        ?>        <nav id="secondary-navigation" class="secondary-navigation" aria-label="<?php esc_attr_e('Secondary Navigation', 'aqualuxe'); ?>">            <?php            wp_nav_menu(array(                'theme_location' => 'secondary',                'menu_id'        => 'secondary-menu',                'container'      => false,                'fallback_cb'    => false,            ));            ?>        </nav>        <?php    }}/** * Display footer navigation */function aqualuxe_footer_navigation() {    if (has_nav_menu('footer')) {        ?>        <nav id="footer-navigation" class="footer-navigation" aria-label="<?php esc_attr_e('Footer Navigation', 'aqualuxe'); ?>">            <?php            wp_nav_menu(array(                'theme_location' => 'footer',                'menu_id'        => 'footer-menu',                'container'      => false,                'depth'          => 1,                'fallback_cb'    => false,            ));            ?>        </nav>        <?php    }}/** * Display social navigation */function aqualuxe_social_navigation() {    if (has_nav_menu('social')) {        ?>        <nav id="social-navigation" class="social-navigation" aria-label="<?php esc_attr_e('Social Links', 'aqualuxe'); ?>">            <?php            wp_nav_menu(array(                'theme_location' => 'social',                'menu_id'        => 'social-menu',                'container'      => false,                'depth'          => 1,                'link_before'    => '<span class="screen-reader-text">',                'link_after'     => '</span>',                'fallback_cb'    => false,            ));            ?>        </nav>        <?php    }}/** * Display breadcrumbs */function aqualuxe_breadcrumbs() {    do_action('aqualuxe_breadcrumbs');}/** * Display page header */function aqualuxe_page_header() {    do_action('aqualuxe_page_header');}/** * Display page title */function aqualuxe_page_title() {    if (is_front_page()) {        return;    }        ?>    <header class="page-header">        <?php        if (is_home()) {            $page_for_posts = get_option('page_for_posts');            if ($page_for_posts) {                echo '<h1 class="page-title">' . esc_html(get_the_title($page_for_posts)) . '</h1>';            } else {                echo '<h1 class="page-title">' . esc_html__('Blog', 'aqualuxe') . '</h1>';            }        } elseif (is_archive()) {            the_archive_title('<h1 class="page-title">', '</h1>');            the_archive_description('<div class="archive-description">', '</div>');        } elseif (is_search()) {            echo '<h1 class="page-title">';            /* translators: %s: search query */            printf(esc_html__('Search Results for: %s', 'aqualuxe'), '<span>' . get_search_query() . '</span>');            echo '</h1>';        } elseif (is_404()) {            echo '<h1 class="page-title">' . esc_html__('Oops! That page can&rsquo;t be found.', 'aqualuxe') . '</h1>';        } elseif (is_page()) {            echo '<h1 class="page-title">' . esc_html(get_the_title()) . '</h1>';        } elseif (is_singular('post')) {            echo '<h1 class="page-title">' . esc_html(get_the_title()) . '</h1>';        }        ?>    </header>    <?php}