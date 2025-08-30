<?php
/**
 * AquaLuxe Helper Functions
 * 
 * Utility functions for theme functionality.
 * 
 * @package AquaLuxe
 * @version 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get demo content for default pages
 */
function aqualuxe_get_demo_content($page_type) {
    $content = '';
    
    switch ($page_type) {
        case 'home':
            $content = '
            <!-- wp:group {"className":"hero-section"} -->
            <div class="wp-block-group hero-section">
                <h1>Bringing Elegance to Aquatic Life</h1>
                <p>Discover our premium collection of ornamental fish, aquatic plants, and luxury aquarium solutions designed for enthusiasts worldwide.</p>
                <a href="/shop" class="btn btn-primary">Explore Collection</a>
            </div>
            <!-- /wp:group -->
            
            <!-- wp:group {"className":"featured-products"} -->
            <div class="wp-block-group featured-products">
                <h2>Featured Products</h2>
                <p>Handpicked selections from our premium aquatic collection.</p>
            </div>
            <!-- /wp:group -->
            
            <!-- wp:group {"className":"services-preview"} -->
            <div class="wp-block-group services-preview">
                <h2>Our Services</h2>
                <div class="services-grid">
                    <div class="service-item">
                        <h3>Custom Aquarium Design</h3>
                        <p>Bespoke aquarium solutions tailored to your space and vision.</p>
                    </div>
                    <div class="service-item">
                        <h3>Professional Maintenance</h3>
                        <p>Expert care to keep your aquatic environment thriving.</p>
                    </div>
                    <div class="service-item">
                        <h3>Live Arrival Guarantee</h3>
                        <p>Safe delivery of healthy fish with full guarantee.</p>
                    </div>
                </div>
            </div>
            <!-- /wp:group -->
            ';
            break;
            
        case 'about':
            $content = '
            <h1>About AquaLuxe</h1>
            
            <p>Founded with a passion for aquatic excellence, AquaLuxe has been bringing elegance to aquatic life globally for over a decade. Our commitment to quality, sustainability, and customer satisfaction has made us a trusted name in the ornamental fish industry.</p>
            
            <h2>Our Mission</h2>
            <p>To provide the finest ornamental fish, aquatic plants, and aquarium solutions while promoting responsible aquaculture practices and environmental stewardship.</p>
            
            <h2>Our Values</h2>
            <ul>
                <li><strong>Quality:</strong> We source only the healthiest, most vibrant specimens</li>
                <li><strong>Sustainability:</strong> Ethical breeding and responsible sourcing practices</li>
                <li><strong>Expertise:</strong> Decades of experience in aquaculture and aquarium design</li>
                <li><strong>Service:</strong> Dedicated support for hobbyists and professionals alike</li>
            </ul>
            
            <h2>Global Reach</h2>
            <p>From our state-of-the-art facilities, we serve customers worldwide with specialized shipping solutions ensuring safe delivery of live aquatic specimens to over 50 countries.</p>
            ';
            break;
            
        case 'services':
            $content = '
            <h1>Our Services</h1>
            
            <div class="services-overview">
                <p>AquaLuxe offers comprehensive aquatic solutions for hobbyists, professionals, and commercial clients worldwide.</p>
            </div>
            
            <div class="service-category">
                <h2>Aquarium Design & Installation</h2>
                <p>Custom aquarium solutions from concept to completion, including:</p>
                <ul>
                    <li>Residential aquarium design</li>
                    <li>Commercial installation</li>
                    <li>Public aquarium consulting</li>
                    <li>Aquascaping services</li>
                </ul>
            </div>
            
            <div class="service-category">
                <h2>Maintenance & Care</h2>
                <p>Professional maintenance services to keep your aquatic environment thriving:</p>
                <ul>
                    <li>Regular maintenance schedules</li>
                    <li>Water quality management</li>
                    <li>Fish health monitoring</li>
                    <li>Equipment servicing</li>
                </ul>
            </div>
            
            <div class="service-category">
                <h2>Consultation & Training</h2>
                <p>Expert guidance for aquaculture success:</p>
                <ul>
                    <li>Breeding consultation</li>
                    <li>Species selection guidance</li>
                    <li>Technical training programs</li>
                    <li>Business development support</li>
                </ul>
            </div>
            ';
            break;
            
        case 'contact':
            $content = '
            <h1>Contact Us</h1>
            
            <div class="contact-intro">
                <p>Get in touch with our team of aquatic experts. We\'re here to help with any questions about our products, services, or aquaculture advice.</p>
            </div>
            
            <div class="contact-methods">
                <div class="contact-method">
                    <h3>Phone</h3>
                    <p>+1 (555) 123-4567</p>
                    <p>Monday - Friday: 9:00 AM - 6:00 PM EST</p>
                </div>
                
                <div class="contact-method">
                    <h3>Email</h3>
                    <p>info@aqualuxe.com</p>
                    <p>For general inquiries and support</p>
                </div>
                
                <div class="contact-method">
                    <h3>Address</h3>
                    <p>123 Aquatic Way<br>
                    Marina District<br>
                    Oceanview, CA 90210</p>
                </div>
            </div>
            
            <div class="contact-form-section">
                <h2>Send us a Message</h2>
                <p>Use the form below to get in touch with our team.</p>
                [contact-form-7 id="1" title="Contact form 1"]
            </div>
            ';
            break;
            
        case 'privacy-policy':
            $content = '
            <h1>Privacy Policy</h1>
            
            <p><strong>Last updated:</strong> ' . date('F j, Y') . '</p>
            
            <h2>Information We Collect</h2>
            <p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p>
            
            <h2>How We Use Your Information</h2>
            <p>We use the information we collect to provide, maintain, and improve our services, process transactions, and communicate with you.</p>
            
            <h2>Information Sharing</h2>
            <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>
            
            <h2>Data Security</h2>
            <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
            
            <h2>Your Rights</h2>
            <p>You have the right to access, update, or delete your personal information. Contact us if you wish to exercise these rights.</p>
            
            <h2>Contact Us</h2>
            <p>If you have any questions about this Privacy Policy, please contact us at privacy@aqualuxe.com.</p>
            ';
            break;
            
        case 'terms-conditions':
            $content = '
            <h1>Terms & Conditions</h1>
            
            <p><strong>Last updated:</strong> ' . date('F j, Y') . '</p>
            
            <h2>Acceptance of Terms</h2>
            <p>By accessing and using this website, you accept and agree to be bound by the terms and provision of this agreement.</p>
            
            <h2>Products and Services</h2>
            <p>All products and services are subject to availability. We reserve the right to discontinue any product or service without notice.</p>
            
            <h2>Live Arrival Guarantee</h2>
            <p>We guarantee live arrival of all fish orders. Claims must be reported within 2 hours of delivery with photographic evidence.</p>
            
            <h2>Shipping and Returns</h2>
            <p>Shipping costs are calculated based on destination and package size. Returns are accepted within 30 days for non-living products in original condition.</p>
            
            <h2>Limitation of Liability</h2>
            <p>AquaLuxe shall not be liable for any indirect, incidental, special, consequential, or punitive damages.</p>
            
            <h2>Governing Law</h2>
            <p>These terms shall be governed by and construed in accordance with the laws of California, United States.</p>
            ';
            break;
            
        default:
            $content = '<p>Welcome to AquaLuxe - your premier destination for luxury aquatic solutions.</p>';
            break;
    }
    
    return $content;
}

/**
 * Get navigation menu
 */
function aqualuxe_get_nav_menu($location = 'primary', $args = []) {
    $defaults = [
        'theme_location' => $location,
        'container' => false,
        'menu_class' => 'nav-menu',
        'fallback_cb' => false,
        'depth' => 2
    ];
    
    $args = wp_parse_args($args, $defaults);
    
    return wp_nav_menu($args);
}

/**
 * Display breadcrumbs
 */
function aqualuxe_breadcrumbs() {
    do_action('aqualuxe_breadcrumbs');
}

/**
 * Get post thumbnail with fallback
 */
function aqualuxe_get_post_thumbnail($post_id = null, $size = 'thumbnail', $attr = []) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    if (has_post_thumbnail($post_id)) {
        return get_the_post_thumbnail($post_id, $size, $attr);
    }
    
    // Fallback image
    $fallback_url = AQUALUXE_URL . '/assets/dist/images/placeholder.jpg';
    $alt_text = get_the_title($post_id);
    
    return '<img src="' . esc_url($fallback_url) . '" alt="' . esc_attr($alt_text) . '" class="attachment-' . esc_attr($size) . '">';
}

/**
 * Format price with currency
 */
function aqualuxe_format_price($price, $currency = '') {
    if (empty($currency) && function_exists('get_woocommerce_currency_symbol')) {
        $currency = get_woocommerce_currency_symbol();
    } elseif (empty($currency)) {
        $currency = '$';
    }
    
    return $currency . number_format($price, 2);
}

/**
 * Get social media links
 */
function aqualuxe_get_social_links() {
    $social_networks = [
        'facebook' => [
            'label' => __('Facebook', 'aqualuxe'),
            'icon' => 'fab fa-facebook-f'
        ],
        'instagram' => [
            'label' => __('Instagram', 'aqualuxe'),
            'icon' => 'fab fa-instagram'
        ],
        'twitter' => [
            'label' => __('Twitter', 'aqualuxe'),
            'icon' => 'fab fa-twitter'
        ],
        'youtube' => [
            'label' => __('YouTube', 'aqualuxe'),
            'icon' => 'fab fa-youtube'
        ],
        'linkedin' => [
            'label' => __('LinkedIn', 'aqualuxe'),
            'icon' => 'fab fa-linkedin-in'
        ]
    ];
    
    $links = [];
    
    foreach ($social_networks as $network => $data) {
        $url = get_theme_mod('aqualuxe_social_' . $network, '');
        if ($url) {
            $links[$network] = [
                'url' => $url,
                'label' => $data['label'],
                'icon' => $data['icon']
            ];
        }
    }
    
    return $links;
}

/**
 * Display social media links
 */
function aqualuxe_social_links($class = 'social-links') {
    $links = aqualuxe_get_social_links();
    
    if (empty($links)) {
        return;
    }
    
    echo '<div class="' . esc_attr($class) . '">';
    foreach ($links as $network => $data) {
        echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener" class="social-link social-link-' . esc_attr($network) . '">';
        echo '<i class="' . esc_attr($data['icon']) . '" aria-hidden="true"></i>';
        echo '<span class="sr-only">' . esc_html($data['label']) . '</span>';
        echo '</a>';
    }
    echo '</div>';
}

/**
 * Get excerpt with custom length
 */
function aqualuxe_get_excerpt($post_id = null, $length = 20, $more = '...') {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    if (!$post) {
        return '';
    }
    
    $excerpt = $post->post_excerpt;
    if (empty($excerpt)) {
        $excerpt = $post->post_content;
    }
    
    $excerpt = strip_tags($excerpt);
    $excerpt = wp_trim_words($excerpt, $length, $more);
    
    return $excerpt;
}

/**
 * Check if we're on a WooCommerce page
 */
function aqualuxe_is_woocommerce_page() {
    if (!aqualuxe_is_woocommerce_active()) {
        return false;
    }
    
    return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}

/**
 * Get current page type for body class
 */
function aqualuxe_get_page_type() {
    if (is_front_page()) {
        return 'front-page';
    } elseif (is_home()) {
        return 'blog-home';
    } elseif (is_single()) {
        return 'single-post';
    } elseif (is_page()) {
        return 'page';
    } elseif (is_category()) {
        return 'category';
    } elseif (is_tag()) {
        return 'tag';
    } elseif (is_author()) {
        return 'author';
    } elseif (is_search()) {
        return 'search';
    } elseif (is_404()) {
        return 'error-404';
    } elseif (aqualuxe_is_woocommerce_page()) {
        return 'woocommerce';
    }
    
    return 'default';
}

/**
 * Add custom body classes
 */
function aqualuxe_body_classes($classes) {
    // Add page type class
    $classes[] = 'page-type-' . aqualuxe_get_page_type();
    
    // Add theme options classes
    if (get_theme_mod('aqualuxe_sticky_header', true)) {
        $classes[] = 'sticky-header';
    }
    
    $header_layout = get_theme_mod('aqualuxe_header_layout', 'default');
    $classes[] = 'header-layout-' . $header_layout;
    
    // Add dark mode class if enabled
    $theme = aqualuxe();
    if ($theme && $theme->is_module_active('dark-mode')) {
        $classes[] = 'dark-mode-enabled';
    }
    
    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Custom comment callback
 */
function aqualuxe_comment_callback($comment, $args, $depth) {
    $tag = ($args['style'] === 'div') ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> id="comment-<?php comment_ID(); ?>" <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?>>
        <article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
            <footer class="comment-meta">
                <div class="comment-author vcard">
                    <?php echo get_avatar($comment, $args['avatar_size']); ?>
                    <?php printf(__('<b class="fn">%s</b> <span class="says">says:</span>', 'aqualuxe'), get_comment_author_link()); ?>
                </div>
                
                <div class="comment-metadata">
                    <a href="<?php echo esc_url(get_comment_link($comment->comment_ID)); ?>">
                        <time datetime="<?php comment_time('c'); ?>">
                            <?php printf(_x('%1$s at %2$s', '1: date, 2: time', 'aqualuxe'), get_comment_date(), get_comment_time()); ?>
                        </time>
                    </a>
                    <?php edit_comment_link(__('Edit', 'aqualuxe'), '<span class="edit-link">', '</span>'); ?>
                </div>
                
                <?php if ($comment->comment_approved == '0') : ?>
                    <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'aqualuxe'); ?></p>
                <?php endif; ?>
            </footer>
            
            <div class="comment-content">
                <?php comment_text(); ?>
            </div>
            
            <div class="reply">
                <?php comment_reply_link(array_merge($args, [
                    'add_below' => 'div-comment',
                    'depth' => $depth,
                    'max_depth' => $args['max_depth']
                ])); ?>
            </div>
        </article>
    <?php
}

/**
 * Pagination for posts
 */
function aqualuxe_pagination($args = []) {
    $defaults = [
        'mid_size' => 2,
        'prev_text' => '<i class="fas fa-chevron-left"></i> ' . __('Previous', 'aqualuxe'),
        'next_text' => __('Next', 'aqualuxe') . ' <i class="fas fa-chevron-right"></i>',
        'screen_reader_text' => __('Posts navigation', 'aqualuxe'),
        'type' => 'array'
    ];
    
    $args = wp_parse_args($args, $defaults);
    $links = paginate_links($args);
    
    if ($links) {
        echo '<nav class="pagination-wrapper" aria-label="' . esc_attr($args['screen_reader_text']) . '">';
        echo '<ul class="pagination">';
        foreach ($links as $link) {
            $class = strpos($link, 'current') !== false ? 'active' : '';
            echo '<li class="page-item ' . esc_attr($class) . '">' . $link . '</li>';
        }
        echo '</ul>';
        echo '</nav>';
    }
}

/**
 * Custom search form
 */
function aqualuxe_search_form() {
    $form = '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">';
    $form .= '<label class="sr-only" for="search-input">' . esc_html__('Search for:', 'aqualuxe') . '</label>';
    $form .= '<input type="search" id="search-input" class="search-field" placeholder="' . esc_attr__('Search...', 'aqualuxe') . '" value="' . get_search_query() . '" name="s">';
    $form .= '<button type="submit" class="search-submit"><i class="fas fa-search"></i><span class="sr-only">' . esc_html__('Search', 'aqualuxe') . '</span></button>';
    $form .= '</form>';
    
    return $form;
}

/**
 * Register search form
 */
add_filter('get_search_form', 'aqualuxe_search_form');

/**
 * Time ago function
 */
function aqualuxe_time_ago($time) {
    $time_difference = time() - $time;
    
    if ($time_difference < 1) {
        return __('less than 1 second ago', 'aqualuxe');
    }
    
    $condition = [
        12 * 30 * 24 * 60 * 60 => __('year', 'aqualuxe'),
        30 * 24 * 60 * 60 => __('month', 'aqualuxe'),
        24 * 60 * 60 => __('day', 'aqualuxe'),
        60 * 60 => __('hour', 'aqualuxe'),
        60 => __('minute', 'aqualuxe'),
        1 => __('second', 'aqualuxe')
    ];
    
    foreach ($condition as $secs => $str) {
        $d = $time_difference / $secs;
        
        if ($d >= 1) {
            $t = round($d);
            return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ' . __('ago', 'aqualuxe');
        }
    }
}

/**
 * Reading time estimate
 */
function aqualuxe_reading_time($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $post = get_post($post_id);
    if (!$post) {
        return '';
    }
    
    $word_count = str_word_count(strip_tags($post->post_content));
    $reading_time = ceil($word_count / 200); // 200 words per minute
    
    if ($reading_time === 1) {
        return __('1 min read', 'aqualuxe');
    }
    
    return sprintf(__('%d min read', 'aqualuxe'), $reading_time);
}
