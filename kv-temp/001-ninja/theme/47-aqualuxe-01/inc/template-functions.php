<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes($classes) {
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    // Adds a class if sidebar is active
    if (is_active_sidebar('sidebar-1') && !is_page_template('templates/full-width.php')) {
        $classes[] = 'has-sidebar';
    } else {
        $classes[] = 'no-sidebar';
    }

    // Add class for dark mode
    if (aqualuxe_is_dark_mode()) {
        $classes[] = 'dark-mode';
    }

    // Add class for tenant
    $classes[] = 'tenant-' . aqualuxe_get_tenant_id();

    // Add class for language
    $classes[] = 'lang-' . aqualuxe_get_current_language();

    // Add class for currency
    $classes[] = 'currency-' . strtolower(aqualuxe_get_current_currency());

    // Add class for WooCommerce pages
    if (aqualuxe_is_woocommerce_active()) {
        if (is_shop() || is_product_category() || is_product_tag()) {
            $classes[] = 'woocommerce-shop-page';
        }

        if (is_product()) {
            $classes[] = 'woocommerce-product-page';
        }

        if (is_cart()) {
            $classes[] = 'woocommerce-cart-page';
        }

        if (is_checkout()) {
            $classes[] = 'woocommerce-checkout-page';
        }

        if (is_account_page()) {
            $classes[] = 'woocommerce-account-page';
        }
    }

    return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
    if (is_singular() && pings_open()) {
        printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
    }
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Add schema markup to the body element.
 *
 * @param array $attr Array of attributes.
 * @return array
 */
function aqualuxe_body_schema($attr) {
    $schema = 'https://schema.org/';
    $type = 'WebPage';

    // Check if it's a single post or page
    if (is_singular('post')) {
        $type = 'Article';
    } elseif (is_author()) {
        $type = 'ProfilePage';
    } elseif (is_search()) {
        $type = 'SearchResultsPage';
    }

    // Apply filters for custom schema
    $type = apply_filters('aqualuxe_schema_type', $type);

    // Add itemtype and itemscope attributes
    $attr['itemtype'] = $schema . $type;
    $attr['itemscope'] = 'itemscope';

    return $attr;
}
add_filter('aqualuxe_body_attributes', 'aqualuxe_body_schema');

/**
 * Output the body attributes.
 */
function aqualuxe_body_attributes() {
    $attributes = array();
    $attributes = apply_filters('aqualuxe_body_attributes', $attributes);

    $output = '';
    foreach ($attributes as $name => $value) {
        $output .= sprintf(' %s="%s"', esc_attr($name), esc_attr($value));
    }

    echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Adds custom classes to the array of post classes.
 *
 * @param array $classes Classes for the post element.
 * @return array
 */
function aqualuxe_post_classes($classes) {
    // Add has-post-thumbnail class if post has thumbnail
    if (has_post_thumbnail()) {
        $classes[] = 'has-post-thumbnail';
    } else {
        $classes[] = 'no-post-thumbnail';
    }

    return $classes;
}
add_filter('post_class', 'aqualuxe_post_classes');

/**
 * Change the excerpt length
 *
 * @param int $length Excerpt length.
 * @return int
 */
function aqualuxe_excerpt_length($length) {
    return 20;
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Change the excerpt more string
 *
 * @param string $more The excerpt more string.
 * @return string
 */
function aqualuxe_excerpt_more($more) {
    return '&hellip;';
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Filter the archive title
 *
 * @param string $title Archive title.
 * @return string
 */
function aqualuxe_archive_title($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    }

    return $title;
}
add_filter('get_the_archive_title', 'aqualuxe_archive_title');

/**
 * Add custom meta tags to the head
 */
function aqualuxe_meta_tags() {
    // Add viewport meta tag
    echo '<meta name="viewport" content="width=device-width, initial-scale=1">' . "\n";

    // Add theme color meta tag
    $theme_color = aqualuxe_get_option('theme_color', '#0077B6');
    echo '<meta name="theme-color" content="' . esc_attr($theme_color) . '">' . "\n";

    // Add mobile web app capable meta tag
    echo '<meta name="mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="apple-mobile-web-app-capable" content="yes">' . "\n";
    echo '<meta name="apple-mobile-web-app-title" content="' . esc_attr(get_bloginfo('name')) . '">' . "\n";

    // Add Open Graph meta tags
    if (is_singular()) {
        global $post;

        echo '<meta property="og:title" content="' . esc_attr(get_the_title()) . '" />' . "\n";
        echo '<meta property="og:type" content="article" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url(get_permalink()) . '" />' . "\n";
        
        if (has_post_thumbnail()) {
            $thumbnail_id = get_post_thumbnail_id();
            $thumbnail_src = wp_get_attachment_image_src($thumbnail_id, 'large');
            echo '<meta property="og:image" content="' . esc_url($thumbnail_src[0]) . '" />' . "\n";
        }
        
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        
        $excerpt = $post->post_excerpt;
        if (empty($excerpt)) {
            $excerpt = wp_trim_words(wp_strip_all_tags(strip_shortcodes($post->post_content)), 55, '...');
        }
        echo '<meta property="og:description" content="' . esc_attr($excerpt) . '" />' . "\n";
    }
}
add_action('wp_head', 'aqualuxe_meta_tags', 1);

/**
 * Add preconnect for Google Fonts.
 *
 * @param array  $urls           URLs to print for resource hints.
 * @param string $relation_type  The relation type the URLs are printed.
 * @return array $urls           URLs to print for resource hints.
 */
function aqualuxe_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin',
        );
    }

    return $urls;
}
add_filter('wp_resource_hints', 'aqualuxe_resource_hints', 10, 2);

/**
 * Add dark mode toggle script to footer
 */
function aqualuxe_dark_mode_script() {
    ?>
    <script>
        (function() {
            // Dark mode toggle
            var darkModeToggle = document.getElementById('dark-mode-toggle');
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    document.body.classList.toggle('dark-mode');
                    
                    // Save preference to cookie
                    var isDarkMode = document.body.classList.contains('dark-mode');
                    document.cookie = 'aqualuxe_dark_mode=' + isDarkMode + ';path=/;max-age=31536000'; // 1 year
                });
            }
            
            // Check for saved preference
            function getCookie(name) {
                var value = '; ' + document.cookie;
                var parts = value.split('; ' + name + '=');
                if (parts.length === 2) return parts.pop().split(';').shift();
            }
            
            var savedDarkMode = getCookie('aqualuxe_dark_mode');
            if (savedDarkMode === 'true' && !document.body.classList.contains('dark-mode')) {
                document.body.classList.add('dark-mode');
            } else if (savedDarkMode === 'false' && document.body.classList.contains('dark-mode')) {
                document.body.classList.remove('dark-mode');
            }
        })();
    </script>
    <?php
}
add_action('wp_footer', 'aqualuxe_dark_mode_script');

/**
 * Add structured data to single posts
 */
function aqualuxe_structured_data_article() {
    if (!is_singular('post')) {
        return;
    }

    global $post;
    
    $author_id = $post->post_author;
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_url = get_author_posts_url($author_id);
    
    $published_date = get_the_date('c');
    $modified_date = get_the_modified_date('c');
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => get_the_title(),
        'datePublished' => $published_date,
        'dateModified' => $modified_date,
        'author' => array(
            '@type' => 'Person',
            'name' => $author_name,
            'url' => $author_url,
        ),
        'publisher' => array(
            '@type' => 'Organization',
            'name' => get_bloginfo('name'),
            'logo' => array(
                '@type' => 'ImageObject',
                'url' => get_site_icon_url(),
            ),
        ),
        'mainEntityOfPage' => array(
            '@type' => 'WebPage',
            '@id' => get_permalink(),
        ),
    );
    
    // Add featured image if available
    if (has_post_thumbnail()) {
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail_src = wp_get_attachment_image_src($thumbnail_id, 'full');
        
        $schema['image'] = array(
            '@type' => 'ImageObject',
            'url' => $thumbnail_src[0],
            'width' => $thumbnail_src[1],
            'height' => $thumbnail_src[2],
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}
add_action('wp_head', 'aqualuxe_structured_data_article');

/**
 * Add structured data to products
 */
function aqualuxe_structured_data_product() {
    if (!aqualuxe_is_woocommerce_active() || !is_product()) {
        return;
    }

    global $product;
    
    $schema = array(
        '@context' => 'https://schema.org',
        '@type' => 'Product',
        'name' => $product->get_name(),
        'description' => wp_strip_all_tags($product->get_short_description() ? $product->get_short_description() : $product->get_description()),
        'sku' => $product->get_sku(),
        'brand' => array(
            '@type' => 'Brand',
            'name' => get_bloginfo('name'),
        ),
        'offers' => array(
            '@type' => 'Offer',
            'price' => $product->get_price(),
            'priceCurrency' => get_woocommerce_currency(),
            'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'url' => get_permalink(),
        ),
    );
    
    // Add product image
    if (has_post_thumbnail()) {
        $thumbnail_id = get_post_thumbnail_id();
        $thumbnail_src = wp_get_attachment_image_src($thumbnail_id, 'full');
        
        $schema['image'] = $thumbnail_src[0];
    }
    
    // Add reviews if available
    if ($product->get_review_count() > 0) {
        $schema['aggregateRating'] = array(
            '@type' => 'AggregateRating',
            'ratingValue' => $product->get_average_rating(),
            'reviewCount' => $product->get_review_count(),
        );
    }
    
    echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>';
}
add_action('wp_head', 'aqualuxe_structured_data_product');

/**
 * Add breadcrumbs to pages
 */
function aqualuxe_breadcrumbs() {
    $breadcrumbs = aqualuxe_get_breadcrumbs();
    
    if (empty($breadcrumbs)) {
        return;
    }
    
    echo '<nav class="aqualuxe-breadcrumbs" aria-label="' . esc_attr__('Breadcrumbs', 'aqualuxe') . '">';
    echo '<ol itemscope itemtype="https://schema.org/BreadcrumbList">';
    
    $i = 1;
    foreach ($breadcrumbs as $breadcrumb) {
        echo '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
        
        if (!empty($breadcrumb['url'])) {
            echo '<a href="' . esc_url($breadcrumb['url']) . '" itemprop="item">';
            echo '<span itemprop="name">' . esc_html($breadcrumb['title']) . '</span>';
            echo '</a>';
        } else {
            echo '<span itemprop="name">' . esc_html($breadcrumb['title']) . '</span>';
        }
        
        echo '<meta itemprop="position" content="' . esc_attr($i) . '">';
        echo '</li>';
        
        $i++;
    }
    
    echo '</ol>';
    echo '</nav>';
}

/**
 * Add social sharing buttons to posts
 */
function aqualuxe_social_sharing() {
    if (!is_singular('post')) {
        return;
    }
    
    $share_links = aqualuxe_get_social_share_links();
    
    if (empty($share_links)) {
        return;
    }
    
    echo '<div class="aqualuxe-social-sharing">';
    echo '<h3>' . esc_html__('Share This Post', 'aqualuxe') . '</h3>';
    echo '<ul class="social-links">';
    
    foreach ($share_links as $network => $data) {
        echo '<li class="' . esc_attr($network) . '">';
        echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener noreferrer">';
        echo '<span class="screen-reader-text">' . esc_html($data['label']) . '</span>';
        echo aqualuxe_get_icon($network, array('class' => 'social-icon'));
        echo '</a>';
        echo '</li>';
    }
    
    echo '</ul>';
    echo '</div>';
}

/**
 * Add related posts to single posts
 */
function aqualuxe_related_posts() {
    if (!is_singular('post')) {
        return;
    }
    
    $related_posts = aqualuxe_get_related_posts();
    
    if (empty($related_posts)) {
        return;
    }
    
    echo '<div class="aqualuxe-related-posts">';
    echo '<h3>' . esc_html__('Related Posts', 'aqualuxe') . '</h3>';
    echo '<div class="related-posts-grid">';
    
    foreach ($related_posts as $related_post) {
        echo '<article class="related-post">';
        
        if (has_post_thumbnail($related_post->ID)) {
            echo '<a href="' . esc_url(get_permalink($related_post->ID)) . '" class="post-thumbnail">';
            echo get_the_post_thumbnail($related_post->ID, 'aqualuxe-blog-thumbnail');
            echo '</a>';
        }
        
        echo '<h4 class="entry-title"><a href="' . esc_url(get_permalink($related_post->ID)) . '">' . esc_html(get_the_title($related_post->ID)) . '</a></h4>';
        
        echo '<div class="entry-meta">';
        echo '<span class="posted-on">' . esc_html(get_the_date('', $related_post->ID)) . '</span>';
        echo '</div>';
        
        echo '</article>';
    }
    
    echo '</div>';
    echo '</div>';
}

/**
 * Add author bio to single posts
 */
function aqualuxe_author_bio() {
    if (!is_singular('post')) {
        return;
    }
    
    $author_id = get_the_author_meta('ID');
    $author_name = get_the_author_meta('display_name', $author_id);
    $author_description = get_the_author_meta('description', $author_id);
    $author_url = get_author_posts_url($author_id);
    
    if (empty($author_description)) {
        return;
    }
    
    echo '<div class="aqualuxe-author-bio">';
    echo '<div class="author-avatar">';
    echo get_avatar($author_id, 100);
    echo '</div>';
    
    echo '<div class="author-content">';
    echo '<h3 class="author-name">' . esc_html__('About', 'aqualuxe') . ' ' . esc_html($author_name) . '</h3>';
    echo '<div class="author-description">' . wp_kses_post($author_description) . '</div>';
    echo '<a href="' . esc_url($author_url) . '" class="author-link">' . esc_html__('View all posts by', 'aqualuxe') . ' ' . esc_html($author_name) . '</a>';
    echo '</div>';
    echo '</div>';
}

/**
 * Add post navigation to single posts
 */
function aqualuxe_post_navigation() {
    if (!is_singular('post')) {
        return;
    }
    
    the_post_navigation(array(
        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous Post', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next Post', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
    ));
}

/**
 * Add language switcher
 */
function aqualuxe_language_switcher() {
    // Check if WPML is active
    if (defined('ICL_LANGUAGE_CODE')) {
        $languages = apply_filters('wpml_active_languages', null, array('skip_missing' => 0));
        
        if (!empty($languages)) {
            echo '<div class="aqualuxe-language-switcher">';
            echo '<span class="current-language">' . esc_html(ICL_LANGUAGE_CODE) . '</span>';
            echo '<ul class="language-list">';
            
            foreach ($languages as $language) {
                echo '<li>';
                echo '<a href="' . esc_url($language['url']) . '" class="' . ($language['active'] ? 'active' : '') . '">';
                echo esc_html($language['language_code']);
                echo '</a>';
                echo '</li>';
            }
            
            echo '</ul>';
            echo '</div>';
        }
    }
    
    // Check if Polylang is active
    if (function_exists('pll_the_languages')) {
        echo '<div class="aqualuxe-language-switcher">';
        echo '<span class="current-language">' . esc_html(pll_current_language()) . '</span>';
        echo '<ul class="language-list">';
        
        pll_the_languages(array(
            'show_flags' => 0,
            'show_names' => 1,
            'hide_if_empty' => 0,
            'echo' => 1,
        ));
        
        echo '</ul>';
        echo '</div>';
    }
}

/**
 * Add dark mode toggle
 */
function aqualuxe_dark_mode_toggle() {
    echo '<button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="' . esc_attr__('Toggle Dark Mode', 'aqualuxe') . '">';
    echo aqualuxe_get_icon('sun', array('class' => 'light-icon'));
    echo aqualuxe_get_icon('moon', array('class' => 'dark-icon'));
    echo '</button>';
}

/**
 * Add search form
 */
function aqualuxe_search_form() {
    echo '<div class="aqualuxe-search-form">';
    echo '<form role="search" method="get" class="search-form" action="' . esc_url(home_url('/')) . '">';
    echo '<label>';
    echo '<span class="screen-reader-text">' . esc_html__('Search for:', 'aqualuxe') . '</span>';
    echo '<input type="search" class="search-field" placeholder="' . esc_attr__('Search…', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" />';
    echo '</label>';
    echo '<button type="submit" class="search-submit">';
    echo aqualuxe_get_icon('search', array('class' => 'search-icon'));
    echo '<span class="screen-reader-text">' . esc_html__('Search', 'aqualuxe') . '</span>';
    echo '</button>';
    
    // Add product search if WooCommerce is active
    if (aqualuxe_is_woocommerce_active()) {
        echo '<input type="hidden" name="post_type" value="product" />';
    }
    
    echo '</form>';
    echo '</div>';
}

/**
 * Add mobile menu toggle
 */
function aqualuxe_mobile_menu_toggle() {
    echo '<button id="mobile-menu-toggle" class="mobile-menu-toggle" aria-controls="primary-menu" aria-expanded="false">';
    echo aqualuxe_get_icon('menu', array('class' => 'menu-icon'));
    echo aqualuxe_get_icon('close', array('class' => 'close-icon'));
    echo '<span class="screen-reader-text">' . esc_html__('Menu', 'aqualuxe') . '</span>';
    echo '</button>';
}

/**
 * Add scroll to top button
 */
function aqualuxe_scroll_to_top() {
    echo '<button id="scroll-to-top" class="scroll-to-top" aria-label="' . esc_attr__('Scroll to top', 'aqualuxe') . '">';
    echo aqualuxe_get_icon('chevron-up', array('class' => 'scroll-icon'));
    echo '</button>';
}
add_action('wp_footer', 'aqualuxe_scroll_to_top');

/**
 * Add scroll to top script
 */
function aqualuxe_scroll_to_top_script() {
    ?>
    <script>
        (function() {
            // Scroll to top button
            var scrollToTopButton = document.getElementById('scroll-to-top');
            
            if (scrollToTopButton) {
                // Show/hide button based on scroll position
                window.addEventListener('scroll', function() {
                    if (window.pageYOffset > 300) {
                        scrollToTopButton.classList.add('show');
                    } else {
                        scrollToTopButton.classList.remove('show');
                    }
                });
                
                // Scroll to top when button is clicked
                scrollToTopButton.addEventListener('click', function() {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                });
            }
        })();
    </script>
    <?php
}
add_action('wp_footer', 'aqualuxe_scroll_to_top_script');

/**
 * Add lazy loading to images
 */
function aqualuxe_lazy_loading_images($content) {
    if (is_admin() || empty($content)) {
        return $content;
    }
    
    // Replace src with data-src and add loading="lazy"
    $content = preg_replace('/<img(.*?)src=[\'"](.*?)[\'"](.*?)>/i', '<img$1src="data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' viewBox=\'0 0 1 1\'%3E%3C/svg%3E" data-src="$2"$3 loading="lazy">', $content);
    
    // Add lazy loading script
    add_action('wp_footer', 'aqualuxe_lazy_loading_script');
    
    return $content;
}
add_filter('the_content', 'aqualuxe_lazy_loading_images');

/**
 * Add lazy loading script
 */
function aqualuxe_lazy_loading_script() {
    ?>
    <script>
        (function() {
            // Lazy loading images
            var lazyImages = [].slice.call(document.querySelectorAll('img[data-src]'));
            
            if ('IntersectionObserver' in window) {
                var lazyImageObserver = new IntersectionObserver(function(entries, observer) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var lazyImage = entry.target;
                            lazyImage.src = lazyImage.dataset.src;
                            lazyImage.removeAttribute('data-src');
                            lazyImageObserver.unobserve(lazyImage);
                        }
                    });
                });
                
                lazyImages.forEach(function(lazyImage) {
                    lazyImageObserver.observe(lazyImage);
                });
            } else {
                // Fallback for browsers without IntersectionObserver support
                var active = false;
                
                var lazyLoad = function() {
                    if (active === false) {
                        active = true;
                        
                        setTimeout(function() {
                            lazyImages.forEach(function(lazyImage) {
                                if ((lazyImage.getBoundingClientRect().top <= window.innerHeight && lazyImage.getBoundingClientRect().bottom >= 0) && getComputedStyle(lazyImage).display !== 'none') {
                                    lazyImage.src = lazyImage.dataset.src;
                                    lazyImage.removeAttribute('data-src');
                                    
                                    lazyImages = lazyImages.filter(function(image) {
                                        return image !== lazyImage;
                                    });
                                    
                                    if (lazyImages.length === 0) {
                                        document.removeEventListener('scroll', lazyLoad);
                                        window.removeEventListener('resize', lazyLoad);
                                        window.removeEventListener('orientationchange', lazyLoad);
                                    }
                                }
                            });
                            
                            active = false;
                        }, 200);
                    }
                };
                
                document.addEventListener('scroll', lazyLoad);
                window.addEventListener('resize', lazyLoad);
                window.addEventListener('orientationchange', lazyLoad);
                lazyLoad();
            }
        })();
    </script>
    <?php
}

/**
 * Add custom page templates
 *
 * @param array $templates Array of page templates.
 * @return array
 */
function aqualuxe_add_page_templates($templates) {
    $templates['templates/full-width.php'] = __('Full Width', 'aqualuxe');
    $templates['templates/landing-page.php'] = __('Landing Page', 'aqualuxe');
    $templates['templates/contact.php'] = __('Contact Page', 'aqualuxe');
    $templates['templates/about.php'] = __('About Page', 'aqualuxe');
    $templates['templates/services.php'] = __('Services Page', 'aqualuxe');
    $templates['templates/faq.php'] = __('FAQ Page', 'aqualuxe');
    
    return $templates;
}
add_filter('theme_page_templates', 'aqualuxe_add_page_templates');

/**
 * Add custom image sizes
 */
function aqualuxe_add_image_sizes() {
    add_image_size('aqualuxe-featured', 1200, 675, true);
    add_image_size('aqualuxe-blog-thumbnail', 400, 250, true);
    add_image_size('aqualuxe-hero', 1920, 800, true);
    
    // Add WooCommerce image sizes if active
    if (aqualuxe_is_woocommerce_active()) {
        add_image_size('aqualuxe-product-thumbnail', 300, 300, true);
        add_image_size('aqualuxe-product-gallery', 800, 800, true);
    }
}
add_action('after_setup_theme', 'aqualuxe_add_image_sizes');

/**
 * Add custom image sizes to media library
 *
 * @param array $sizes Array of image sizes.
 * @return array
 */
function aqualuxe_custom_image_sizes($sizes) {
    return array_merge($sizes, array(
        'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
        'aqualuxe-blog-thumbnail' => __('Blog Thumbnail', 'aqualuxe'),
        'aqualuxe-hero' => __('Hero Image', 'aqualuxe'),
        'aqualuxe-product-thumbnail' => __('Product Thumbnail', 'aqualuxe'),
        'aqualuxe-product-gallery' => __('Product Gallery', 'aqualuxe'),
    ));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Add custom logo support
 */
function aqualuxe_custom_logo_setup() {
    $defaults = array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => array('site-title', 'site-description'),
    );
    add_theme_support('custom-logo', $defaults);
}
add_action('after_setup_theme', 'aqualuxe_custom_logo_setup');

/**
 * Add custom header support
 */
function aqualuxe_custom_header_setup() {
    $args = array(
        'default-image'          => '',
        'default-text-color'     => '000000',
        'width'                  => 1920,
        'height'                 => 500,
        'flex-width'             => true,
        'flex-height'            => true,
        'wp-head-callback'       => 'aqualuxe_header_style',
    );
    add_theme_support('custom-header', $args);
}
add_action('after_setup_theme', 'aqualuxe_custom_header_setup');

/**
 * Custom header style
 */
function aqualuxe_header_style() {
    $header_text_color = get_header_textcolor();
    
    if (get_theme_support('custom-header', 'default-text-color') === $header_text_color) {
        return;
    }
    
    ?>
    <style type="text/css">
        <?php if (!display_header_text()) : ?>
            .site-title,
            .site-description {
                position: absolute;
                clip: rect(1px, 1px, 1px, 1px);
            }
        <?php else : ?>
            .site-title a,
            .site-description {
                color: #<?php echo esc_attr($header_text_color); ?>;
            }
        <?php endif; ?>
    </style>
    <?php
}

/**
 * Add custom background support
 */
function aqualuxe_custom_background_setup() {
    $args = array(
        'default-color'          => 'f0f9ff',
        'default-image'          => '',
        'default-repeat'         => 'no-repeat',
        'default-position-x'     => 'center',
        'default-position-y'     => 'center',
        'default-size'           => 'cover',
        'default-attachment'     => 'fixed',
    );
    add_theme_support('custom-background', $args);
}
add_action('after_setup_theme', 'aqualuxe_custom_background_setup');

/**
 * Add editor color palette
 */
function aqualuxe_editor_color_palette() {
    add_theme_support('editor-color-palette', array(
        array(
            'name'  => __('Primary', 'aqualuxe'),
            'slug'  => 'primary',
            'color' => '#0077B6',
        ),
        array(
            'name'  => __('Secondary', 'aqualuxe'),
            'slug'  => 'secondary',
            'color' => '#00B4D8',
        ),
        array(
            'name'  => __('Accent', 'aqualuxe'),
            'slug'  => 'accent',
            'color' => '#FFD700',
        ),
        array(
            'name'  => __('Dark', 'aqualuxe'),
            'slug'  => 'dark',
            'color' => '#023E8A',
        ),
        array(
            'name'  => __('Light', 'aqualuxe'),
            'slug'  => 'light',
            'color' => '#F0F9FF',
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_editor_color_palette');

/**
 * Add editor font sizes
 */
function aqualuxe_editor_font_sizes() {
    add_theme_support('editor-font-sizes', array(
        array(
            'name'      => __('Small', 'aqualuxe'),
            'shortName' => __('S', 'aqualuxe'),
            'size'      => 14,
            'slug'      => 'small',
        ),
        array(
            'name'      => __('Normal', 'aqualuxe'),
            'shortName' => __('M', 'aqualuxe'),
            'size'      => 16,
            'slug'      => 'normal',
        ),
        array(
            'name'      => __('Large', 'aqualuxe'),
            'shortName' => __('L', 'aqualuxe'),
            'size'      => 20,
            'slug'      => 'large',
        ),
        array(
            'name'      => __('Huge', 'aqualuxe'),
            'shortName' => __('XL', 'aqualuxe'),
            'size'      => 24,
            'slug'      => 'huge',
        ),
    ));
}
add_action('after_setup_theme', 'aqualuxe_editor_font_sizes');

/**
 * Add custom styles to editor
 */
function aqualuxe_add_editor_styles() {
    add_editor_style('assets/dist/css/editor-style.css');
}
add_action('admin_init', 'aqualuxe_add_editor_styles');

/**
 * Add custom styles to login page
 */
function aqualuxe_login_styles() {
    wp_enqueue_style('aqualuxe-login', AQUALUXE_URI . 'assets/dist/css/login.css', array(), AQUALUXE_VERSION);
}
add_action('login_enqueue_scripts', 'aqualuxe_login_styles');

/**
 * Add custom logo to login page
 */
function aqualuxe_login_logo() {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
    
    if ($logo) {
        ?>
        <style type="text/css">
            .login h1 a {
                background-image: url(<?php echo esc_url($logo[0]); ?>);
                background-size: contain;
                width: 320px;
                height: 80px;
            }
        </style>
        <?php
    }
}
add_action('login_head', 'aqualuxe_login_logo');

/**
 * Change login logo URL
 */
function aqualuxe_login_logo_url() {
    return home_url('/');
}
add_filter('login_headerurl', 'aqualuxe_login_logo_url');

/**
 * Change login logo title
 */
function aqualuxe_login_logo_url_title() {
    return get_bloginfo('name');
}
add_filter('login_headertext', 'aqualuxe_login_logo_url_title');

/**
 * Add custom admin footer text
 */
function aqualuxe_admin_footer_text($text) {
    $text = sprintf(
        /* translators: %s: Site name */
        __('Thank you for creating with <a href="%1$s" target="_blank">WordPress</a> | %2$s by <a href="%3$s" target="_blank">AquaLuxe</a>', 'aqualuxe'),
        __('https://wordpress.org/', 'aqualuxe'),
        get_bloginfo('name'),
        home_url('/')
    );
    
    return $text;
}
add_filter('admin_footer_text', 'aqualuxe_admin_footer_text');

/**
 * Add custom favicon
 */
function aqualuxe_favicon() {
    if (function_exists('has_site_icon') && has_site_icon()) {
        return;
    }
    
    echo '<link rel="shortcut icon" href="' . esc_url(AQUALUXE_URI . 'assets/dist/images/favicon.ico') . '" />' . "\n";
    echo '<link rel="apple-touch-icon" href="' . esc_url(AQUALUXE_URI . 'assets/dist/images/apple-touch-icon.png') . '" />' . "\n";
}
add_action('wp_head', 'aqualuxe_favicon');

/**
 * Add custom CSS variables
 */
function aqualuxe_custom_css_variables() {
    $primary_color = aqualuxe_get_option('primary_color', '#0077B6');
    $secondary_color = aqualuxe_get_option('secondary_color', '#00B4D8');
    $accent_color = aqualuxe_get_option('accent_color', '#FFD700');
    $dark_color = aqualuxe_get_option('dark_color', '#023E8A');
    $light_color = aqualuxe_get_option('light_color', '#F0F9FF');
    
    ?>
    <style type="text/css">
        :root {
            --aqualuxe-primary-color: <?php echo esc_attr($primary_color); ?>;
            --aqualuxe-secondary-color: <?php echo esc_attr($secondary_color); ?>;
            --aqualuxe-accent-color: <?php echo esc_attr($accent_color); ?>;
            --aqualuxe-dark-color: <?php echo esc_attr($dark_color); ?>;
            --aqualuxe-light-color: <?php echo esc_attr($light_color); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'aqualuxe_custom_css_variables');

/**
 * Add custom body attributes
 */
function aqualuxe_body_attributes_filter($attr) {
    $attr['data-tenant'] = aqualuxe_get_tenant_id();
    $attr['data-language'] = aqualuxe_get_current_language();
    $attr['data-currency'] = aqualuxe_get_current_currency();
    
    return $attr;
}
add_filter('aqualuxe_body_attributes', 'aqualuxe_body_attributes_filter');

/**
 * Add custom classes to menu items
 */
function aqualuxe_menu_item_classes($classes, $item, $args) {
    // Add class for current menu item
    if (in_array('current-menu-item', $classes)) {
        $classes[] = 'active';
    }
    
    // Add class for menu items with children
    if (in_array('menu-item-has-children', $classes)) {
        $classes[] = 'has-dropdown';
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'aqualuxe_menu_item_classes', 10, 3);

/**
 * Add custom attributes to menu items
 */
function aqualuxe_menu_item_attributes($atts, $item, $args) {
    // Add aria-current for current menu item
    if (in_array('current-menu-item', $item->classes)) {
        $atts['aria-current'] = 'page';
    }
    
    // Add aria-expanded for menu items with children
    if (in_array('menu-item-has-children', $item->classes)) {
        $atts['aria-expanded'] = 'false';
    }
    
    return $atts;
}
add_filter('nav_menu_link_attributes', 'aqualuxe_menu_item_attributes', 10, 3);

/**
 * Add custom classes to post navigation
 */
function aqualuxe_post_navigation_classes($args) {
    $args['prev_class'] = 'nav-previous';
    $args['next_class'] = 'nav-next';
    
    return $args;
}
add_filter('navigation_markup_template', 'aqualuxe_post_navigation_classes');

/**
 * Add custom classes to comments
 */
function aqualuxe_comment_classes($classes, $class, $comment_id, $post_id, $comment) {
    if ($comment->comment_approved === '1') {
        $classes[] = 'approved';
    }
    
    if ($comment->user_id > 0) {
        $classes[] = 'user-comment';
    } else {
        $classes[] = 'guest-comment';
    }
    
    return $classes;
}
add_filter('comment_class', 'aqualuxe_comment_classes', 10, 5);

/**
 * Add custom classes to widgets
 */
function aqualuxe_widget_classes($params) {
    $params[0]['before_widget'] = str_replace('class="', 'class="widget-container ', $params[0]['before_widget']);
    
    return $params;
}
add_filter('dynamic_sidebar_params', 'aqualuxe_widget_classes');

/**
 * Add custom classes to galleries
 */
function aqualuxe_gallery_classes($output, $attr, $instance) {
    return str_replace('class="gallery', 'class="gallery aqualuxe-gallery', $output);
}
add_filter('post_gallery', 'aqualuxe_gallery_classes', 10, 3);

/**
 * Add custom classes to forms
 */
function aqualuxe_form_classes($html) {
    $html = str_replace('<form', '<form class="aqualuxe-form"', $html);
    
    return $html;
}
add_filter('get_search_form', 'aqualuxe_form_classes');
add_filter('comment_form_defaults', 'aqualuxe_form_classes');

/**
 * Add custom classes to buttons
 */
function aqualuxe_button_classes($html) {
    $html = str_replace('<button', '<button class="aqualuxe-button"', $html);
    
    return $html;
}
add_filter('get_search_form', 'aqualuxe_button_classes');
add_filter('comment_form_defaults', 'aqualuxe_button_classes');

/**
 * Add custom classes to inputs
 */
function aqualuxe_input_classes($html) {
    $html = str_replace('<input', '<input class="aqualuxe-input"', $html);
    
    return $html;
}
add_filter('get_search_form', 'aqualuxe_input_classes');
add_filter('comment_form_defaults', 'aqualuxe_input_classes');

/**
 * Add custom classes to textareas
 */
function aqualuxe_textarea_classes($html) {
    $html = str_replace('<textarea', '<textarea class="aqualuxe-textarea"', $html);
    
    return $html;
}
add_filter('comment_form_defaults', 'aqualuxe_textarea_classes');

/**
 * Add custom classes to labels
 */
function aqualuxe_label_classes($html) {
    $html = str_replace('<label', '<label class="aqualuxe-label"', $html);
    
    return $html;
}
add_filter('get_search_form', 'aqualuxe_label_classes');
add_filter('comment_form_defaults', 'aqualuxe_label_classes');

/**
 * Add custom classes to selects
 */
function aqualuxe_select_classes($html) {
    $html = str_replace('<select', '<select class="aqualuxe-select"', $html);
    
    return $html;
}
add_filter('get_search_form', 'aqualuxe_select_classes');
add_filter('comment_form_defaults', 'aqualuxe_select_classes');

/**
 * Add custom classes to options
 */
function aqualuxe_option_classes($html) {
    $html = str_replace('<option', '<option class="aqualuxe-option"', $html);
    
    return $html;
}
add_filter('get_search_form', 'aqualuxe_option_classes');
add_filter('comment_form_defaults', 'aqualuxe_option_classes');

/**
 * Add custom classes to fieldsets
 */
function aqualuxe_fieldset_classes($html) {
    $html = str_replace('<fieldset', '<fieldset class="aqualuxe-fieldset"', $html);
    
    return $html;
}
add_filter('get_search_form', 'aqualuxe_fieldset_classes');
add_filter('comment_form_defaults', 'aqualuxe_fieldset_classes');

/**
 * Add custom classes to legends
 */
function aqualuxe_legend_classes($html) {
    $html = str_replace('<legend', '<legend class="aqualuxe-legend"', $html);
    
    return $html;
}
add_filter('get_search_form', 'aqualuxe_legend_classes');
add_filter('comment_form_defaults', 'aqualuxe_legend_classes');

/**
 * Add custom classes to tables
 */
function aqualuxe_table_classes($html) {
    $html = str_replace('<table', '<table class="aqualuxe-table"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_table_classes');

/**
 * Add custom classes to table headers
 */
function aqualuxe_th_classes($html) {
    $html = str_replace('<th', '<th class="aqualuxe-th"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_th_classes');

/**
 * Add custom classes to table rows
 */
function aqualuxe_tr_classes($html) {
    $html = str_replace('<tr', '<tr class="aqualuxe-tr"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_tr_classes');

/**
 * Add custom classes to table cells
 */
function aqualuxe_td_classes($html) {
    $html = str_replace('<td', '<td class="aqualuxe-td"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_td_classes');

/**
 * Add custom classes to lists
 */
function aqualuxe_list_classes($html) {
    $html = str_replace('<ul', '<ul class="aqualuxe-list"', $html);
    $html = str_replace('<ol', '<ol class="aqualuxe-list"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_list_classes');

/**
 * Add custom classes to list items
 */
function aqualuxe_li_classes($html) {
    $html = str_replace('<li', '<li class="aqualuxe-list-item"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_li_classes');

/**
 * Add custom classes to blockquotes
 */
function aqualuxe_blockquote_classes($html) {
    $html = str_replace('<blockquote', '<blockquote class="aqualuxe-blockquote"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_blockquote_classes');

/**
 * Add custom classes to code
 */
function aqualuxe_code_classes($html) {
    $html = str_replace('<code', '<code class="aqualuxe-code"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_code_classes');

/**
 * Add custom classes to pre
 */
function aqualuxe_pre_classes($html) {
    $html = str_replace('<pre', '<pre class="aqualuxe-pre"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_pre_classes');

/**
 * Add custom classes to figures
 */
function aqualuxe_figure_classes($html) {
    $html = str_replace('<figure', '<figure class="aqualuxe-figure"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_figure_classes');

/**
 * Add custom classes to figcaptions
 */
function aqualuxe_figcaption_classes($html) {
    $html = str_replace('<figcaption', '<figcaption class="aqualuxe-figcaption"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_figcaption_classes');

/**
 * Add custom classes to iframes
 */
function aqualuxe_iframe_classes($html) {
    $html = str_replace('<iframe', '<iframe class="aqualuxe-iframe"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_iframe_classes');

/**
 * Add custom classes to embeds
 */
function aqualuxe_embed_classes($html) {
    $html = str_replace('<embed', '<embed class="aqualuxe-embed"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_embed_classes');

/**
 * Add custom classes to objects
 */
function aqualuxe_object_classes($html) {
    $html = str_replace('<object', '<object class="aqualuxe-object"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_object_classes');

/**
 * Add custom classes to videos
 */
function aqualuxe_video_classes($html) {
    $html = str_replace('<video', '<video class="aqualuxe-video"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_video_classes');

/**
 * Add custom classes to audio
 */
function aqualuxe_audio_classes($html) {
    $html = str_replace('<audio', '<audio class="aqualuxe-audio"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_audio_classes');

/**
 * Add custom classes to canvas
 */
function aqualuxe_canvas_classes($html) {
    $html = str_replace('<canvas', '<canvas class="aqualuxe-canvas"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_canvas_classes');

/**
 * Add custom classes to details
 */
function aqualuxe_details_classes($html) {
    $html = str_replace('<details', '<details class="aqualuxe-details"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_details_classes');

/**
 * Add custom classes to summary
 */
function aqualuxe_summary_classes($html) {
    $html = str_replace('<summary', '<summary class="aqualuxe-summary"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_summary_classes');

/**
 * Add custom classes to progress
 */
function aqualuxe_progress_classes($html) {
    $html = str_replace('<progress', '<progress class="aqualuxe-progress"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_progress_classes');

/**
 * Add custom classes to meter
 */
function aqualuxe_meter_classes($html) {
    $html = str_replace('<meter', '<meter class="aqualuxe-meter"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_meter_classes');

/**
 * Add custom classes to time
 */
function aqualuxe_time_classes($html) {
    $html = str_replace('<time', '<time class="aqualuxe-time"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_time_classes');

/**
 * Add custom classes to mark
 */
function aqualuxe_mark_classes($html) {
    $html = str_replace('<mark', '<mark class="aqualuxe-mark"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_mark_classes');

/**
 * Add custom classes to small
 */
function aqualuxe_small_classes($html) {
    $html = str_replace('<small', '<small class="aqualuxe-small"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_small_classes');

/**
 * Add custom classes to cite
 */
function aqualuxe_cite_classes($html) {
    $html = str_replace('<cite', '<cite class="aqualuxe-cite"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_cite_classes');

/**
 * Add custom classes to abbr
 */
function aqualuxe_abbr_classes($html) {
    $html = str_replace('<abbr', '<abbr class="aqualuxe-abbr"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_abbr_classes');

/**
 * Add custom classes to data
 */
function aqualuxe_data_classes($html) {
    $html = str_replace('<data', '<data class="aqualuxe-data"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_data_classes');

/**
 * Add custom classes to var
 */
function aqualuxe_var_classes($html) {
    $html = str_replace('<var', '<var class="aqualuxe-var"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_var_classes');

/**
 * Add custom classes to samp
 */
function aqualuxe_samp_classes($html) {
    $html = str_replace('<samp', '<samp class="aqualuxe-samp"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_samp_classes');

/**
 * Add custom classes to kbd
 */
function aqualuxe_kbd_classes($html) {
    $html = str_replace('<kbd', '<kbd class="aqualuxe-kbd"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_kbd_classes');

/**
 * Add custom classes to sub
 */
function aqualuxe_sub_classes($html) {
    $html = str_replace('<sub', '<sub class="aqualuxe-sub"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_sub_classes');

/**
 * Add custom classes to sup
 */
function aqualuxe_sup_classes($html) {
    $html = str_replace('<sup', '<sup class="aqualuxe-sup"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_sup_classes');

/**
 * Add custom classes to i
 */
function aqualuxe_i_classes($html) {
    $html = str_replace('<i', '<i class="aqualuxe-i"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_i_classes');

/**
 * Add custom classes to b
 */
function aqualuxe_b_classes($html) {
    $html = str_replace('<b', '<b class="aqualuxe-b"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_b_classes');

/**
 * Add custom classes to u
 */
function aqualuxe_u_classes($html) {
    $html = str_replace('<u', '<u class="aqualuxe-u"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_u_classes');

/**
 * Add custom classes to s
 */
function aqualuxe_s_classes($html) {
    $html = str_replace('<s', '<s class="aqualuxe-s"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_s_classes');

/**
 * Add custom classes to strong
 */
function aqualuxe_strong_classes($html) {
    $html = str_replace('<strong', '<strong class="aqualuxe-strong"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_strong_classes');

/**
 * Add custom classes to em
 */
function aqualuxe_em_classes($html) {
    $html = str_replace('<em', '<em class="aqualuxe-em"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_em_classes');

/**
 * Add custom classes to del
 */
function aqualuxe_del_classes($html) {
    $html = str_replace('<del', '<del class="aqualuxe-del"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_del_classes');

/**
 * Add custom classes to ins
 */
function aqualuxe_ins_classes($html) {
    $html = str_replace('<ins', '<ins class="aqualuxe-ins"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_ins_classes');

/**
 * Add custom classes to hr
 */
function aqualuxe_hr_classes($html) {
    $html = str_replace('<hr', '<hr class="aqualuxe-hr"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_hr_classes');

/**
 * Add custom classes to br
 */
function aqualuxe_br_classes($html) {
    $html = str_replace('<br', '<br class="aqualuxe-br"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_br_classes');

/**
 * Add custom classes to wbr
 */
function aqualuxe_wbr_classes($html) {
    $html = str_replace('<wbr', '<wbr class="aqualuxe-wbr"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_wbr_classes');

/**
 * Add custom classes to ruby
 */
function aqualuxe_ruby_classes($html) {
    $html = str_replace('<ruby', '<ruby class="aqualuxe-ruby"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_ruby_classes');

/**
 * Add custom classes to rt
 */
function aqualuxe_rt_classes($html) {
    $html = str_replace('<rt', '<rt class="aqualuxe-rt"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_rt_classes');

/**
 * Add custom classes to rp
 */
function aqualuxe_rp_classes($html) {
    $html = str_replace('<rp', '<rp class="aqualuxe-rp"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_rp_classes');

/**
 * Add custom classes to bdi
 */
function aqualuxe_bdi_classes($html) {
    $html = str_replace('<bdi', '<bdi class="aqualuxe-bdi"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_bdi_classes');

/**
 * Add custom classes to bdo
 */
function aqualuxe_bdo_classes($html) {
    $html = str_replace('<bdo', '<bdo class="aqualuxe-bdo"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_bdo_classes');

/**
 * Add custom classes to span
 */
function aqualuxe_span_classes($html) {
    $html = str_replace('<span', '<span class="aqualuxe-span"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_span_classes');

/**
 * Add custom classes to div
 */
function aqualuxe_div_classes($html) {
    $html = str_replace('<div', '<div class="aqualuxe-div"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_div_classes');

/**
 * Add custom classes to p
 */
function aqualuxe_p_classes($html) {
    $html = str_replace('<p', '<p class="aqualuxe-p"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_p_classes');

/**
 * Add custom classes to h1
 */
function aqualuxe_h1_classes($html) {
    $html = str_replace('<h1', '<h1 class="aqualuxe-h1"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_h1_classes');

/**
 * Add custom classes to h2
 */
function aqualuxe_h2_classes($html) {
    $html = str_replace('<h2', '<h2 class="aqualuxe-h2"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_h2_classes');

/**
 * Add custom classes to h3
 */
function aqualuxe_h3_classes($html) {
    $html = str_replace('<h3', '<h3 class="aqualuxe-h3"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_h3_classes');

/**
 * Add custom classes to h4
 */
function aqualuxe_h4_classes($html) {
    $html = str_replace('<h4', '<h4 class="aqualuxe-h4"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_h4_classes');

/**
 * Add custom classes to h5
 */
function aqualuxe_h5_classes($html) {
    $html = str_replace('<h5', '<h5 class="aqualuxe-h5"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_h5_classes');

/**
 * Add custom classes to h6
 */
function aqualuxe_h6_classes($html) {
    $html = str_replace('<h6', '<h6 class="aqualuxe-h6"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_h6_classes');

/**
 * Add custom classes to header
 */
function aqualuxe_header_classes($html) {
    $html = str_replace('<header', '<header class="aqualuxe-header"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_header_classes');

/**
 * Add custom classes to footer
 */
function aqualuxe_footer_classes($html) {
    $html = str_replace('<footer', '<footer class="aqualuxe-footer"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_footer_classes');

/**
 * Add custom classes to main
 */
function aqualuxe_main_classes($html) {
    $html = str_replace('<main', '<main class="aqualuxe-main"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_main_classes');

/**
 * Add custom classes to section
 */
function aqualuxe_section_classes($html) {
    $html = str_replace('<section', '<section class="aqualuxe-section"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_section_classes');

/**
 * Add custom classes to article
 */
function aqualuxe_article_classes($html) {
    $html = str_replace('<article', '<article class="aqualuxe-article"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_article_classes');

/**
 * Add custom classes to aside
 */
function aqualuxe_aside_classes($html) {
    $html = str_replace('<aside', '<aside class="aqualuxe-aside"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_aside_classes');

/**
 * Add custom classes to nav
 */
function aqualuxe_nav_classes($html) {
    $html = str_replace('<nav', '<nav class="aqualuxe-nav"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_nav_classes');

/**
 * Add custom classes to address
 */
function aqualuxe_address_classes($html) {
    $html = str_replace('<address', '<address class="aqualuxe-address"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_address_classes');

/**
 * Add custom classes to hgroup
 */
function aqualuxe_hgroup_classes($html) {
    $html = str_replace('<hgroup', '<hgroup class="aqualuxe-hgroup"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_hgroup_classes');

/**
 * Add custom classes to dl
 */
function aqualuxe_dl_classes($html) {
    $html = str_replace('<dl', '<dl class="aqualuxe-dl"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_dl_classes');

/**
 * Add custom classes to dt
 */
function aqualuxe_dt_classes($html) {
    $html = str_replace('<dt', '<dt class="aqualuxe-dt"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_dt_classes');

/**
 * Add custom classes to dd
 */
function aqualuxe_dd_classes($html) {
    $html = str_replace('<dd', '<dd class="aqualuxe-dd"', $html);
    
    return $html;
}
add_filter('the_content', 'aqualuxe_dd_classes');