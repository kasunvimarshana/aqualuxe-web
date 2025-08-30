<?php
/**
 * AquaLuxe Performance Optimization
 *
 * @package AquaLuxe
 */

/**
 * Add performance optimization features
 */
function aqualuxe_performance_optimization() {
    // Check if performance optimization is enabled
    if (!get_theme_mod('aqualuxe_enable_performance_optimization', true)) {
        return;
    }
    
    /**
     * Implement lazy loading for images and iframes
     */
    function aqualuxe_lazy_loading() {
        // Check if lazy loading is enabled
        if (!get_theme_mod('aqualuxe_enable_lazy_loading', true)) {
            return;
        }
        
        // Add lazy loading to post content images
        function aqualuxe_add_lazy_loading_to_content_images($content) {
            // Skip if content is empty
            if (empty($content)) {
                return $content;
            }
            
            // Skip if lazy loading is already added by WordPress core
            if (function_exists('wp_lazy_loading_enabled') && wp_lazy_loading_enabled('img', 'the_content')) {
                return $content;
            }
            
            // Add lazy loading to images
            $content = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
                // Skip if already has loading attribute
                if (strpos($matches[1], 'loading=') !== false) {
                    return $matches[0];
                }
                
                // Add loading="lazy" attribute
                return '<img' . $matches[1] . ' loading="lazy">';
            }, $content);
            
            // Add lazy loading to iframes
            $content = preg_replace_callback('/<iframe([^>]+)>/i', function($matches) {
                // Skip if already has loading attribute
                if (strpos($matches[1], 'loading=') !== false) {
                    return $matches[0];
                }
                
                // Add loading="lazy" attribute
                return '<iframe' . $matches[1] . ' loading="lazy">';
            }, $content);
            
            return $content;
        }
        add_filter('the_content', 'aqualuxe_add_lazy_loading_to_content_images', 99);
        
        // Add lazy loading to post thumbnails
        function aqualuxe_add_lazy_loading_to_thumbnails($html) {
            // Skip if empty
            if (empty($html)) {
                return $html;
            }
            
            // Skip if lazy loading is already added by WordPress core
            if (function_exists('wp_lazy_loading_enabled') && wp_lazy_loading_enabled('img', 'post_thumbnail')) {
                return $html;
            }
            
            // Add lazy loading to images
            $html = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
                // Skip if already has loading attribute
                if (strpos($matches[1], 'loading=') !== false) {
                    return $matches[0];
                }
                
                // Add loading="lazy" attribute
                return '<img' . $matches[1] . ' loading="lazy">';
            }, $html);
            
            return $html;
        }
        add_filter('post_thumbnail_html', 'aqualuxe_add_lazy_loading_to_thumbnails', 99);
        
        // Add lazy loading to avatar images
        function aqualuxe_add_lazy_loading_to_avatars($html) {
            // Skip if empty
            if (empty($html)) {
                return $html;
            }
            
            // Skip if lazy loading is already added by WordPress core
            if (function_exists('wp_lazy_loading_enabled') && wp_lazy_loading_enabled('img', 'get_avatar')) {
                return $html;
            }
            
            // Add lazy loading to images
            $html = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
                // Skip if already has loading attribute
                if (strpos($matches[1], 'loading=') !== false) {
                    return $matches[0];
                }
                
                // Add loading="lazy" attribute
                return '<img' . $matches[1] . ' loading="lazy">';
            }, $html);
            
            return $html;
        }
        add_filter('get_avatar', 'aqualuxe_add_lazy_loading_to_avatars', 99);
        
        // Add lazy loading to gallery images
        function aqualuxe_add_lazy_loading_to_gallery_images($html) {
            // Skip if empty
            if (empty($html)) {
                return $html;
            }
            
            // Skip if lazy loading is already added by WordPress core
            if (function_exists('wp_lazy_loading_enabled') && wp_lazy_loading_enabled('img', 'gallery_shortcode')) {
                return $html;
            }
            
            // Add lazy loading to images
            $html = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
                // Skip if already has loading attribute
                if (strpos($matches[1], 'loading=') !== false) {
                    return $matches[0];
                }
                
                // Add loading="lazy" attribute
                return '<img' . $matches[1] . ' loading="lazy">';
            }, $html);
            
            return $html;
        }
        add_filter('gallery_style', 'aqualuxe_add_lazy_loading_to_gallery_images', 99);
        
        // Add lazy loading to widget images
        function aqualuxe_add_lazy_loading_to_widget_images($html) {
            // Skip if empty
            if (empty($html)) {
                return $html;
            }
            
            // Add lazy loading to images
            $html = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
                // Skip if already has loading attribute
                if (strpos($matches[1], 'loading=') !== false) {
                    return $matches[0];
                }
                
                // Add loading="lazy" attribute
                return '<img' . $matches[1] . ' loading="lazy">';
            }, $html);
            
            return $html;
        }
        add_filter('widget_text', 'aqualuxe_add_lazy_loading_to_widget_images', 99);
        
        // Add lazy loading to comment images
        function aqualuxe_add_lazy_loading_to_comment_images($html) {
            // Skip if empty
            if (empty($html)) {
                return $html;
            }
            
            // Add lazy loading to images
            $html = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
                // Skip if already has loading attribute
                if (strpos($matches[1], 'loading=') !== false) {
                    return $matches[0];
                }
                
                // Add loading="lazy" attribute
                return '<img' . $matches[1] . ' loading="lazy">';
            }, $html);
            
            return $html;
        }
        add_filter('comment_text', 'aqualuxe_add_lazy_loading_to_comment_images', 99);
    }
    add_action('wp', 'aqualuxe_lazy_loading');
    
    /**
     * Minify and enqueue assets
     */
    function aqualuxe_minify_assets() {
        // Check if minification is enabled
        if (!get_theme_mod('aqualuxe_enable_minification', true)) {
            return;
        }
        
        // Minify CSS
        function aqualuxe_minify_css($css) {
            // Remove comments
            $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);
            
            // Remove whitespace
            $css = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $css);
            
            // Remove unnecessary spaces
            $css = preg_replace('/\s+/', ' ', $css);
            $css = preg_replace('/\s*({|}|,|:|;)\s*/', '$1', $css);
            $css = preg_replace('/;}/', '}', $css);
            
            return $css;
        }
        
        // Minify JavaScript
        function aqualuxe_minify_js($js) {
            // Remove comments (single line)
            $js = preg_replace('/(^|\n)\/\/[^\n]*/', '', $js);
            
            // Remove comments (multi-line)
            $js = preg_replace('/\/\*.*?\*\//s', '', $js);
            
            // Remove whitespace
            $js = preg_replace('/\s+/', ' ', $js);
            $js = preg_replace('/\s*({|}|,|:|;|=|\+|\-|\*|\/|\?|\||\&|\|)\s*/', '$1', $js);
            
            return $js;
        }
        
        // Minify inline CSS
        function aqualuxe_minify_inline_css($html) {
            // Skip if empty
            if (empty($html)) {
                return $html;
            }
            
            // Minify inline CSS
            $html = preg_replace_callback('/<style[^>]*>(.*?)<\/style>/is', function($matches) {
                return '<style>' . aqualuxe_minify_css($matches[1]) . '</style>';
            }, $html);
            
            return $html;
        }
        add_filter('wp_head', 'aqualuxe_minify_inline_css', 99);
        
        // Minify inline JavaScript
        function aqualuxe_minify_inline_js($html) {
            // Skip if empty
            if (empty($html)) {
                return $html;
            }
            
            // Minify inline JavaScript
            $html = preg_replace_callback('/<script[^>]*>(.*?)<\/script>/is', function($matches) {
                // Skip if already minified or external script
                if (empty($matches[1]) || strpos($matches[0], 'src=') !== false) {
                    return $matches[0];
                }
                
                return '<script>' . aqualuxe_minify_js($matches[1]) . '</script>';
            }, $html);
            
            return $html;
        }
        add_filter('wp_footer', 'aqualuxe_minify_inline_js', 99);
    }
    add_action('wp', 'aqualuxe_minify_assets');
    
    /**
     * Optimize images
     */
    function aqualuxe_optimize_images() {
        // Check if image optimization is enabled
        if (!get_theme_mod('aqualuxe_enable_image_optimization', true)) {
            return;
        }
        
        // Add WebP support
        function aqualuxe_webp_support() {
            // Check if WebP is enabled
            if (!get_theme_mod('aqualuxe_enable_webp', true)) {
                return;
            }
            
            // Check if browser supports WebP
            function aqualuxe_browser_supports_webp() {
                // Check Accept header
                if (isset($_SERVER['HTTP_ACCEPT']) && strpos($_SERVER['HTTP_ACCEPT'], 'image/webp') !== false) {
                    return true;
                }
                
                // Check User-Agent header
                if (isset($_SERVER['HTTP_USER_AGENT'])) {
                    // Chrome 9+, Opera 12+, Firefox 65+
                    if (preg_match('/Chrome\/([0-9]+)/', $_SERVER['HTTP_USER_AGENT'], $matches) && $matches[1] >= 9) {
                        return true;
                    }
                    
                    if (preg_match('/Opera\/([0-9]+)/', $_SERVER['HTTP_USER_AGENT'], $matches) && $matches[1] >= 12) {
                        return true;
                    }
                    
                    if (preg_match('/Firefox\/([0-9]+)/', $_SERVER['HTTP_USER_AGENT'], $matches) && $matches[1] >= 65) {
                        return true;
                    }
                }
                
                return false;
            }
            
            // Add WebP support to images
            function aqualuxe_add_webp_support($html) {
                // Skip if browser doesn't support WebP
                if (!aqualuxe_browser_supports_webp()) {
                    return $html;
                }
                
                // Skip if empty
                if (empty($html)) {
                    return $html;
                }
                
                // Add WebP support to images
                $html = preg_replace_callback('/<img([^>]+)>/i', function($matches) {
                    // Skip if already has srcset attribute
                    if (strpos($matches[1], 'srcset=') !== false) {
                        return $matches[0];
                    }
                    
                    // Get src attribute
                    if (preg_match('/src=[\'"](.*?)[\'"]/i', $matches[1], $src)) {
                        $src = $src[1];
                        
                        // Skip if not a local image
                        if (strpos($src, site_url()) === false) {
                            return $matches[0];
                        }
                        
                        // Get image path
                        $path = str_replace(site_url(), ABSPATH, $src);
                        
                        // Check if WebP version exists
                        $webp_path = preg_replace('/\.(jpe?g|png)$/i', '.webp', $path);
                        $webp_url = preg_replace('/\.(jpe?g|png)$/i', '.webp', $src);
                        
                        if (file_exists($webp_path)) {
                            // Add srcset attribute
                            return '<img' . $matches[1] . ' srcset="' . esc_url($webp_url) . ' 1x, ' . esc_url($src) . ' 1x">';
                        }
                    }
                    
                    return $matches[0];
                }, $html);
                
                return $html;
            }
            add_filter('the_content', 'aqualuxe_add_webp_support', 99);
            add_filter('post_thumbnail_html', 'aqualuxe_add_webp_support', 99);
            add_filter('widget_text', 'aqualuxe_add_webp_support', 99);
            add_filter('get_avatar', 'aqualuxe_add_webp_support', 99);
        }
        add_action('wp', 'aqualuxe_webp_support');
        
        // Add responsive images
        function aqualuxe_responsive_images() {
            // Check if responsive images are enabled
            if (!get_theme_mod('aqualuxe_enable_responsive_images', true)) {
                return;
            }
            
            // Add responsive image sizes
            add_image_size('aqualuxe-small', 300, 9999);
            add_image_size('aqualuxe-medium', 600, 9999);
            add_image_size('aqualuxe-large', 1200, 9999);
            add_image_size('aqualuxe-xlarge', 1800, 9999);
            
            // Add responsive image sizes to content
            function aqualuxe_add_responsive_image_sizes($html, $post_id, $post_thumbnail_id, $size, $attr) {
                // Skip if empty
                if (empty($html)) {
                    return $html;
                }
                
                // Skip if already has srcset attribute
                if (strpos($html, 'srcset=') !== false) {
                    return $html;
                }
                
                // Get image URL
                if (preg_match('/src=[\'"](.*?)[\'"]/i', $html, $src)) {
                    $src = $src[1];
                    
                    // Skip if not a local image
                    if (strpos($src, site_url()) === false) {
                        return $html;
                    }
                    
                    // Get image sizes
                    $small = wp_get_attachment_image_src($post_thumbnail_id, 'aqualuxe-small');
                    $medium = wp_get_attachment_image_src($post_thumbnail_id, 'aqualuxe-medium');
                    $large = wp_get_attachment_image_src($post_thumbnail_id, 'aqualuxe-large');
                    $xlarge = wp_get_attachment_image_src($post_thumbnail_id, 'aqualuxe-xlarge');
                    
                    // Build srcset
                    $srcset = array();
                    
                    if ($small) {
                        $srcset[] = $small[0] . ' ' . $small[1] . 'w';
                    }
                    
                    if ($medium) {
                        $srcset[] = $medium[0] . ' ' . $medium[1] . 'w';
                    }
                    
                    if ($large) {
                        $srcset[] = $large[0] . ' ' . $large[1] . 'w';
                    }
                    
                    if ($xlarge) {
                        $srcset[] = $xlarge[0] . ' ' . $xlarge[1] . 'w';
                    }
                    
                    // Add srcset and sizes attributes
                    if (!empty($srcset)) {
                        $html = str_replace('<img', '<img srcset="' . esc_attr(implode(', ', $srcset)) . '" sizes="(max-width: 600px) 100vw, (max-width: 1200px) 50vw, 33vw"', $html);
                    }
                }
                
                return $html;
            }
            add_filter('post_thumbnail_html', 'aqualuxe_add_responsive_image_sizes', 10, 5);
        }
        add_action('after_setup_theme', 'aqualuxe_responsive_images');
    }
    add_action('after_setup_theme', 'aqualuxe_optimize_images');
    
    /**
     * Implement caching strategies
     */
    function aqualuxe_caching_strategies() {
        // Check if caching is enabled
        if (!get_theme_mod('aqualuxe_enable_caching', true)) {
            return;
        }
        
        // Add browser caching headers
        function aqualuxe_browser_caching_headers() {
            // Skip if headers already sent
            if (headers_sent()) {
                return;
            }
            
            // Skip if not a GET request
            if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
                return;
            }
            
            // Skip if admin or login page
            if (is_admin() || is_user_logged_in()) {
                return;
            }
            
            // Set cache control headers
            header('Cache-Control: public, max-age=31536000, stale-while-revalidate=86400, stale-if-error=604800');
            header('Pragma: public');
            
            // Set expires header
            $expires = 60 * 60 * 24 * 365; // 1 year
            header('Expires: ' . gmdate('D, d M Y H:i:s', time() + $expires) . ' GMT');
            
            // Set ETag header
            $etag = md5($_SERVER['REQUEST_URI'] . filemtime(get_template_directory() . '/style.css'));
            header('ETag: "' . $etag . '"');
            
            // Check if page is cached
            if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] === '"' . $etag . '"') {
                header('HTTP/1.1 304 Not Modified');
                exit;
            }
        }
        add_action('template_redirect', 'aqualuxe_browser_caching_headers', 999);
        
        // Add preload and prefetch
        function aqualuxe_preload_prefetch() {
            // Check if preload is enabled
            if (!get_theme_mod('aqualuxe_enable_preload', true)) {
                return;
            }
            
            // Preload critical assets
            function aqualuxe_preload_critical_assets() {
                // Preload logo
                $logo_id = get_theme_mod('custom_logo');
                if ($logo_id) {
                    $logo = wp_get_attachment_image_src($logo_id, 'full');
                    if ($logo) {
                        echo '<link rel="preload" href="' . esc_url($logo[0]) . '" as="image" type="' . esc_attr(get_post_mime_type($logo_id)) . '" crossorigin="anonymous">' . "\n";
                    }
                }
                
                // Preload critical CSS
                echo '<link rel="preload" href="' . esc_url(get_template_directory_uri() . '/assets/css/tailwind.css') . '" as="style">' . "\n";
                echo '<link rel="preload" href="' . esc_url(get_template_directory_uri() . '/assets/css/custom.css') . '" as="style">' . "\n";
                
                // Preload critical JavaScript
                echo '<link rel="preload" href="' . esc_url(get_template_directory_uri() . '/assets/js/navigation.js') . '" as="script">' . "\n";
            }
            add_action('wp_head', 'aqualuxe_preload_critical_assets', 1);
            
            // Prefetch external resources
            function aqualuxe_prefetch_external_resources() {
                // Check if prefetch is enabled
                if (!get_theme_mod('aqualuxe_enable_prefetch', true)) {
                    return;
                }
                
                // Prefetch Google Fonts
                echo '<link rel="dns-prefetch" href="//fonts.googleapis.com">' . "\n";
                echo '<link rel="dns-prefetch" href="//fonts.gstatic.com">' . "\n";
                
                // Prefetch Google Analytics
                echo '<link rel="dns-prefetch" href="//www.google-analytics.com">' . "\n";
                
                // Prefetch social media domains
                echo '<link rel="dns-prefetch" href="//www.facebook.com">' . "\n";
                echo '<link rel="dns-prefetch" href="//platform.twitter.com">' . "\n";
                echo '<link rel="dns-prefetch" href="//www.instagram.com">' . "\n";
                echo '<link rel="dns-prefetch" href="//www.youtube.com">' . "\n";
                
                // Prefetch Gravatar
                echo '<link rel="dns-prefetch" href="//secure.gravatar.com">' . "\n";
            }
            add_action('wp_head', 'aqualuxe_prefetch_external_resources', 1);
        }
        add_action('wp', 'aqualuxe_preload_prefetch');
    }
    add_action('wp', 'aqualuxe_caching_strategies');
    
    /**
     * Add schema.org markup
     */
    function aqualuxe_schema_markup() {
        // Check if schema markup is enabled
        if (!get_theme_mod('aqualuxe_enable_schema_markup', true)) {
            return;
        }
        
        // Add schema.org markup to HTML tag
        function aqualuxe_add_schema_to_html() {
            echo ' itemscope itemtype="https://schema.org/WebSite"';
        }
        add_filter('language_attributes', 'aqualuxe_add_schema_to_html');
        
        // Add schema.org markup to header
        function aqualuxe_add_schema_to_header() {
            echo '<meta itemprop="name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            echo '<meta itemprop="description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
            echo '<meta itemprop="url" content="' . esc_url(home_url('/')) . '">' . "\n";
            
            // Add organization schema
            echo '<script type="application/ld+json">' . "\n";
            echo json_encode(array(
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'url' => home_url('/'),
                'logo' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
                'sameAs' => array(
                    get_theme_mod('aqualuxe_social_facebook', ''),
                    get_theme_mod('aqualuxe_social_twitter', ''),
                    get_theme_mod('aqualuxe_social_instagram', ''),
                    get_theme_mod('aqualuxe_social_linkedin', ''),
                    get_theme_mod('aqualuxe_social_youtube', ''),
                    get_theme_mod('aqualuxe_social_pinterest', ''),
                ),
            )) . "\n";
            echo '</script>' . "\n";
            
            // Add website schema
            echo '<script type="application/ld+json">' . "\n";
            echo json_encode(array(
                '@context' => 'https://schema.org',
                '@type' => 'WebSite',
                'name' => get_bloginfo('name'),
                'url' => home_url('/'),
                'potentialAction' => array(
                    '@type' => 'SearchAction',
                    'target' => home_url('/?s={search_term_string}'),
                    'query-input' => 'required name=search_term_string',
                ),
            )) . "\n";
            echo '</script>' . "\n";
        }
        add_action('wp_head', 'aqualuxe_add_schema_to_header');
        
        // Add schema.org markup to posts
        function aqualuxe_add_schema_to_posts() {
            // Skip if not a single post
            if (!is_singular('post')) {
                return;
            }
            
            // Get post data
            $post = get_post();
            $post_url = get_permalink();
            $post_title = get_the_title();
            $post_excerpt = get_the_excerpt();
            $post_date = get_the_date('c');
            $post_modified = get_the_modified_date('c');
            $post_author = get_the_author();
            $post_author_url = get_author_posts_url(get_the_author_meta('ID'));
            $post_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
            
            // Add article schema
            echo '<script type="application/ld+json">' . "\n";
            echo json_encode(array(
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'mainEntityOfPage' => array(
                    '@type' => 'WebPage',
                    '@id' => $post_url,
                ),
                'headline' => $post_title,
                'description' => $post_excerpt,
                'image' => $post_image,
                'datePublished' => $post_date,
                'dateModified' => $post_modified,
                'author' => array(
                    '@type' => 'Person',
                    'name' => $post_author,
                    'url' => $post_author_url,
                ),
                'publisher' => array(
                    '@type' => 'Organization',
                    'name' => get_bloginfo('name'),
                    'logo' => array(
                        '@type' => 'ImageObject',
                        'url' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
                    ),
                ),
            )) . "\n";
            echo '</script>' . "\n";
        }
        add_action('wp_head', 'aqualuxe_add_schema_to_posts');
        
        // Add schema.org markup to products
        function aqualuxe_add_schema_to_products() {
            // Skip if not a single product
            if (!is_singular('product')) {
                return;
            }
            
            // Skip if WooCommerce is not active
            if (!class_exists('WooCommerce')) {
                return;
            }
            
            // Get product data
            global $product;
            
            if (!$product) {
                return;
            }
            
            $product_url = get_permalink();
            $product_name = $product->get_name();
            $product_description = $product->get_short_description();
            $product_image = wp_get_attachment_image_url($product->get_image_id(), 'full');
            $product_price = $product->get_price();
            $product_currency = get_woocommerce_currency();
            $product_availability = $product->is_in_stock() ? 'InStock' : 'OutOfStock';
            $product_sku = $product->get_sku();
            $product_brand = '';
            
            // Get product brand
            $brands = get_the_terms($product->get_id(), 'product_brand');
            if ($brands && !is_wp_error($brands)) {
                $product_brand = $brands[0]->name;
            }
            
            // Add product schema
            echo '<script type="application/ld+json">' . "\n";
            echo json_encode(array(
                '@context' => 'https://schema.org',
                '@type' => 'Product',
                'name' => $product_name,
                'description' => $product_description,
                'image' => $product_image,
                'sku' => $product_sku,
                'brand' => array(
                    '@type' => 'Brand',
                    'name' => $product_brand,
                ),
                'offers' => array(
                    '@type' => 'Offer',
                    'price' => $product_price,
                    'priceCurrency' => $product_currency,
                    'availability' => 'https://schema.org/' . $product_availability,
                    'url' => $product_url,
                ),
            )) . "\n";
            echo '</script>' . "\n";
        }
        add_action('wp_head', 'aqualuxe_add_schema_to_products');
    }
    add_action('wp', 'aqualuxe_schema_markup');
    
    /**
     * Add Open Graph metadata
     */
    function aqualuxe_open_graph_metadata() {
        // Check if Open Graph metadata is enabled
        if (!get_theme_mod('aqualuxe_enable_open_graph', true)) {
            return;
        }
        
        // Add Open Graph metadata
        function aqualuxe_add_open_graph() {
            // Skip if Yoast SEO is active
            if (defined('WPSEO_VERSION')) {
                return;
            }
            
            // Get default image
            $default_image = get_theme_mod('aqualuxe_default_opengraph_image', '');
            
            // Basic Open Graph tags
            echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '">' . "\n";
            echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
            
            // Page-specific Open Graph tags
            if (is_singular()) {
                // Get post data
                $post = get_post();
                $post_url = get_permalink();
                $post_title = get_the_title();
                $post_excerpt = get_the_excerpt();
                $post_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                
                // Set Open Graph type
                if (is_singular('product')) {
                    echo '<meta property="og:type" content="product">' . "\n";
                } else {
                    echo '<meta property="og:type" content="article">' . "\n";
                }
                
                // Set Open Graph URL
                echo '<meta property="og:url" content="' . esc_url($post_url) . '">' . "\n";
                
                // Set Open Graph title
                echo '<meta property="og:title" content="' . esc_attr($post_title) . '">' . "\n";
                
                // Set Open Graph description
                echo '<meta property="og:description" content="' . esc_attr($post_excerpt) . '">' . "\n";
                
                // Set Open Graph image
                if ($post_image) {
                    echo '<meta property="og:image" content="' . esc_url($post_image) . '">' . "\n";
                    
                    // Get image dimensions
                    $image_id = get_post_thumbnail_id();
                    if ($image_id) {
                        $image_data = wp_get_attachment_image_src($image_id, 'full');
                        if ($image_data) {
                            echo '<meta property="og:image:width" content="' . esc_attr($image_data[1]) . '">' . "\n";
                            echo '<meta property="og:image:height" content="' . esc_attr($image_data[2]) . '">' . "\n";
                        }
                    }
                } elseif ($default_image) {
                    echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
                }
                
                // Set article-specific tags
                if (is_singular('post')) {
                    echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '">' . "\n";
                    echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '">' . "\n";
                    echo '<meta property="article:author" content="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . "\n";
                    
                    // Add article tags
                    $tags = get_the_tags();
                    if ($tags) {
                        foreach ($tags as $tag) {
                            echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '">' . "\n";
                        }
                    }
                    
                    // Add article categories
                    $categories = get_the_category();
                    if ($categories) {
                        foreach ($categories as $category) {
                            echo '<meta property="article:section" content="' . esc_attr($category->name) . '">' . "\n";
                        }
                    }
                }
                
                // Set product-specific tags
                if (is_singular('product') && class_exists('WooCommerce')) {
                    global $product;
                    
                    if ($product) {
                        echo '<meta property="product:price:amount" content="' . esc_attr($product->get_price()) . '">' . "\n";
                        echo '<meta property="product:price:currency" content="' . esc_attr(get_woocommerce_currency()) . '">' . "\n";
                        echo '<meta property="product:availability" content="' . esc_attr($product->is_in_stock() ? 'in stock' : 'out of stock') . '">' . "\n";
                    }
                }
            } elseif (is_archive() || is_home()) {
                // Archive pages
                echo '<meta property="og:type" content="website">' . "\n";
                echo '<meta property="og:url" content="' . esc_url(get_pagenum_link()) . '">' . "\n";
                
                if (is_home()) {
                    // Blog page
                    $blog_page_id = get_option('page_for_posts');
                    if ($blog_page_id) {
                        echo '<meta property="og:title" content="' . esc_attr(get_the_title($blog_page_id)) . '">' . "\n";
                        echo '<meta property="og:description" content="' . esc_attr(get_the_excerpt($blog_page_id)) . '">' . "\n";
                        
                        $blog_image = get_the_post_thumbnail_url($blog_page_id, 'full');
                        if ($blog_image) {
                            echo '<meta property="og:image" content="' . esc_url($blog_image) . '">' . "\n";
                        } elseif ($default_image) {
                            echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
                        }
                    } else {
                        echo '<meta property="og:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
                        echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
                        
                        if ($default_image) {
                            echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
                        }
                    }
                } elseif (is_category() || is_tag() || is_tax()) {
                    // Category, tag, or taxonomy archive
                    $term = get_queried_object();
                    
                    echo '<meta property="og:title" content="' . esc_attr($term->name) . '">' . "\n";
                    echo '<meta property="og:description" content="' . esc_attr($term->description) . '">' . "\n";
                    
                    if ($default_image) {
                        echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
                    }
                } elseif (is_author()) {
                    // Author archive
                    $author_id = get_query_var('author');
                    
                    echo '<meta property="og:title" content="' . esc_attr(get_the_author_meta('display_name', $author_id)) . '">' . "\n";
                    echo '<meta property="og:description" content="' . esc_attr(get_the_author_meta('description', $author_id)) . '">' . "\n";
                    
                    $author_avatar = get_avatar_url($author_id, array('size' => 200));
                    if ($author_avatar) {
                        echo '<meta property="og:image" content="' . esc_url($author_avatar) . '">' . "\n";
                    } elseif ($default_image) {
                        echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
                    }
                } elseif (is_post_type_archive()) {
                    // Post type archive
                    $post_type = get_query_var('post_type');
                    $post_type_obj = get_post_type_object($post_type);
                    
                    echo '<meta property="og:title" content="' . esc_attr($post_type_obj->labels->name) . '">' . "\n";
                    echo '<meta property="og:description" content="' . esc_attr($post_type_obj->description) . '">' . "\n";
                    
                    if ($default_image) {
                        echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
                    }
                } elseif (is_date()) {
                    // Date archive
                    if (is_day()) {
                        echo '<meta property="og:title" content="' . esc_attr(sprintf(__('Daily Archives: %s', 'aqualuxe'), get_the_date())) . '">' . "\n";
                    } elseif (is_month()) {
                        echo '<meta property="og:title" content="' . esc_attr(sprintf(__('Monthly Archives: %s', 'aqualuxe'), get_the_date('F Y'))) . '">' . "\n";
                    } elseif (is_year()) {
                        echo '<meta property="og:title" content="' . esc_attr(sprintf(__('Yearly Archives: %s', 'aqualuxe'), get_the_date('Y'))) . '">' . "\n";
                    }
                    
                    echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
                    
                    if ($default_image) {
                        echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
                    }
                }
            } else {
                // Other pages
                echo '<meta property="og:type" content="website">' . "\n";
                echo '<meta property="og:url" content="' . esc_url(home_url($_SERVER['REQUEST_URI'])) . '">' . "\n";
                echo '<meta property="og:title" content="' . esc_attr(wp_get_document_title()) . '">' . "\n";
                echo '<meta property="og:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
                
                if ($default_image) {
                    echo '<meta property="og:image" content="' . esc_url($default_image) . '">' . "\n";
                }
            }
        }
        add_action('wp_head', 'aqualuxe_add_open_graph', 5);
        
        // Add Twitter Card metadata
        function aqualuxe_add_twitter_card() {
            // Skip if Yoast SEO is active
            if (defined('WPSEO_VERSION')) {
                return;
            }
            
            // Get Twitter username
            $twitter_username = get_theme_mod('aqualuxe_twitter_username', '');
            
            // Get default image
            $default_image = get_theme_mod('aqualuxe_default_opengraph_image', '');
            
            // Basic Twitter Card tags
            echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
            
            if ($twitter_username) {
                echo '<meta name="twitter:site" content="@' . esc_attr($twitter_username) . '">' . "\n";
            }
            
            // Page-specific Twitter Card tags
            if (is_singular()) {
                // Get post data
                $post = get_post();
                $post_title = get_the_title();
                $post_excerpt = get_the_excerpt();
                $post_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
                
                // Set Twitter Card title
                echo '<meta name="twitter:title" content="' . esc_attr($post_title) . '">' . "\n";
                
                // Set Twitter Card description
                echo '<meta name="twitter:description" content="' . esc_attr($post_excerpt) . '">' . "\n";
                
                // Set Twitter Card image
                if ($post_image) {
                    echo '<meta name="twitter:image" content="' . esc_url($post_image) . '">' . "\n";
                } elseif ($default_image) {
                    echo '<meta name="twitter:image" content="' . esc_url($default_image) . '">' . "\n";
                }
                
                // Set Twitter Card author
                $twitter_author = get_the_author_meta('twitter', $post->post_author);
                if ($twitter_author) {
                    echo '<meta name="twitter:creator" content="@' . esc_attr($twitter_author) . '">' . "\n";
                }
            } elseif (is_archive() || is_home()) {
                // Archive pages
                if (is_home()) {
                    // Blog page
                    $blog_page_id = get_option('page_for_posts');
                    if ($blog_page_id) {
                        echo '<meta name="twitter:title" content="' . esc_attr(get_the_title($blog_page_id)) . '">' . "\n";
                        echo '<meta name="twitter:description" content="' . esc_attr(get_the_excerpt($blog_page_id)) . '">' . "\n";
                        
                        $blog_image = get_the_post_thumbnail_url($blog_page_id, 'full');
                        if ($blog_image) {
                            echo '<meta name="twitter:image" content="' . esc_url($blog_image) . '">' . "\n";
                        } elseif ($default_image) {
                            echo '<meta name="twitter:image" content="' . esc_url($default_image) . '">' . "\n";
                        }
                    } else {
                        echo '<meta name="twitter:title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";
                        echo '<meta name="twitter:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
                        
                        if ($default_image) {
                            echo '<meta name="twitter:image" content="' . esc_url($default_image) . '">' . "\n";
                        }
                    }
                } elseif (is_category() || is_tag() || is_tax()) {
                    // Category, tag, or taxonomy archive
                    $term = get_queried_object();
                    
                    echo '<meta name="twitter:title" content="' . esc_attr($term->name) . '">' . "\n";
                    echo '<meta name="twitter:description" content="' . esc_attr($term->description) . '">' . "\n";
                    
                    if ($default_image) {
                        echo '<meta name="twitter:image" content="' . esc_url($default_image) . '">' . "\n";
                    }
                } elseif (is_author()) {
                    // Author archive
                    $author_id = get_query_var('author');
                    
                    echo '<meta name="twitter:title" content="' . esc_attr(get_the_author_meta('display_name', $author_id)) . '">' . "\n";
                    echo '<meta name="twitter:description" content="' . esc_attr(get_the_author_meta('description', $author_id)) . '">' . "\n";
                    
                    $author_avatar = get_avatar_url($author_id, array('size' => 200));
                    if ($author_avatar) {
                        echo '<meta name="twitter:image" content="' . esc_url($author_avatar) . '">' . "\n";
                    } elseif ($default_image) {
                        echo '<meta name="twitter:image" content="' . esc_url($default_image) . '">' . "\n";
                    }
                } elseif (is_post_type_archive()) {
                    // Post type archive
                    $post_type = get_query_var('post_type');
                    $post_type_obj = get_post_type_object($post_type);
                    
                    echo '<meta name="twitter:title" content="' . esc_attr($post_type_obj->labels->name) . '">' . "\n";
                    echo '<meta name="twitter:description" content="' . esc_attr($post_type_obj->description) . '">' . "\n";
                    
                    if ($default_image) {
                        echo '<meta name="twitter:image" content="' . esc_url($default_image) . '">' . "\n";
                    }
                } elseif (is_date()) {
                    // Date archive
                    if (is_day()) {
                        echo '<meta name="twitter:title" content="' . esc_attr(sprintf(__('Daily Archives: %s', 'aqualuxe'), get_the_date())) . '">' . "\n";
                    } elseif (is_month()) {
                        echo '<meta name="twitter:title" content="' . esc_attr(sprintf(__('Monthly Archives: %s', 'aqualuxe'), get_the_date('F Y'))) . '">' . "\n";
                    } elseif (is_year()) {
                        echo '<meta name="twitter:title" content="' . esc_attr(sprintf(__('Yearly Archives: %s', 'aqualuxe'), get_the_date('Y'))) . '">' . "\n";
                    }
                    
                    echo '<meta name="twitter:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
                    
                    if ($default_image) {
                        echo '<meta name="twitter:image" content="' . esc_url($default_image) . '">' . "\n";
                    }
                }
            } else {
                // Other pages
                echo '<meta name="twitter:title" content="' . esc_attr(wp_get_document_title()) . '">' . "\n";
                echo '<meta name="twitter:description" content="' . esc_attr(get_bloginfo('description')) . '">' . "\n";
                
                if ($default_image) {
                    echo '<meta name="twitter:image" content="' . esc_url($default_image) . '">' . "\n";
                }
            }
        }
        add_action('wp_head', 'aqualuxe_add_twitter_card', 5);
    }
    add_action('wp', 'aqualuxe_open_graph_metadata');
    
    /**
     * Add performance settings to customizer
     */
    function aqualuxe_performance_customizer_settings($wp_customize) {
        // Add performance settings section
        $wp_customize->add_section('aqualuxe_performance_settings', array(
            'title' => __('Performance Settings', 'aqualuxe'),
            'description' => __('Configure performance optimization settings for your site.', 'aqualuxe'),
            'panel' => 'aqualuxe_theme_options',
            'priority' => 100,
        ));
        
        // Add performance settings
        $wp_customize->add_setting('aqualuxe_enable_performance_optimization', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_performance_optimization', array(
            'label' => __('Enable Performance Optimization', 'aqualuxe'),
            'description' => __('Enable performance optimization features for your site.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
        
        // Add lazy loading setting
        $wp_customize->add_setting('aqualuxe_enable_lazy_loading', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_lazy_loading', array(
            'label' => __('Enable Lazy Loading', 'aqualuxe'),
            'description' => __('Lazy load images and iframes to improve page load speed.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
        
        // Add minification setting
        $wp_customize->add_setting('aqualuxe_enable_minification', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_minification', array(
            'label' => __('Enable Minification', 'aqualuxe'),
            'description' => __('Minify CSS and JavaScript files to reduce file size.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
        
        // Add image optimization setting
        $wp_customize->add_setting('aqualuxe_enable_image_optimization', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_image_optimization', array(
            'label' => __('Enable Image Optimization', 'aqualuxe'),
            'description' => __('Optimize images for better performance.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
        
        // Add WebP support setting
        $wp_customize->add_setting('aqualuxe_enable_webp', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_webp', array(
            'label' => __('Enable WebP Support', 'aqualuxe'),
            'description' => __('Use WebP image format for better compression and faster loading.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
        
        // Add responsive images setting
        $wp_customize->add_setting('aqualuxe_enable_responsive_images', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_responsive_images', array(
            'label' => __('Enable Responsive Images', 'aqualuxe'),
            'description' => __('Use responsive images to serve appropriate image sizes based on device.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
        
        // Add caching setting
        $wp_customize->add_setting('aqualuxe_enable_caching', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_caching', array(
            'label' => __('Enable Caching', 'aqualuxe'),
            'description' => __('Enable browser caching for better performance.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
        
        // Add preload setting
        $wp_customize->add_setting('aqualuxe_enable_preload', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_preload', array(
            'label' => __('Enable Resource Preloading', 'aqualuxe'),
            'description' => __('Preload critical resources to improve page load speed.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
        
        // Add prefetch setting
        $wp_customize->add_setting('aqualuxe_enable_prefetch', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_prefetch', array(
            'label' => __('Enable DNS Prefetching', 'aqualuxe'),
            'description' => __('Prefetch DNS for external resources to improve page load speed.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
        
        // Add schema markup setting
        $wp_customize->add_setting('aqualuxe_enable_schema_markup', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_schema_markup', array(
            'label' => __('Enable Schema.org Markup', 'aqualuxe'),
            'description' => __('Add schema.org markup to improve SEO.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
        
        // Add Open Graph setting
        $wp_customize->add_setting('aqualuxe_enable_open_graph', array(
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ));
        
        $wp_customize->add_control('aqualuxe_enable_open_graph', array(
            'label' => __('Enable Open Graph Metadata', 'aqualuxe'),
            'description' => __('Add Open Graph metadata for better social media sharing.', 'aqualuxe'),
            'section' => 'aqualuxe_performance_settings',
            'type' => 'checkbox',
        ));
    }
    add_action('customize_register', 'aqualuxe_performance_customizer_settings');
}
add_action('after_setup_theme', 'aqualuxe_performance_optimization');