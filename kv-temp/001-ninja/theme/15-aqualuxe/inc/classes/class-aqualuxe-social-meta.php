<?php
/**
 * AquaLuxe Social Media Metadata
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Social_Meta class
 * 
 * Handles Open Graph and Twitter Card metadata
 */
class AquaLuxe_Social_Meta {
    /**
     * Constructor
     */
    public function __construct() {
        // Add meta tags to head
        add_action('wp_head', array($this, 'output_social_meta'), 5);
    }

    /**
     * Output social media meta tags
     */
    public function output_social_meta() {
        // Basic meta tags
        $this->output_basic_meta();
        
        // Open Graph meta tags
        $this->output_open_graph_meta();
        
        // Twitter Card meta tags
        $this->output_twitter_card_meta();
    }

    /**
     * Output basic meta tags
     */
    private function output_basic_meta() {
        // Get page title
        $title = $this->get_title();
        
        // Get page description
        $description = $this->get_description();
        
        // Output meta tags
        echo '<meta name="description" content="' . esc_attr($description) . '" />' . "\n";
        
        // Canonical URL
        $canonical = $this->get_canonical_url();
        echo '<link rel="canonical" href="' . esc_url($canonical) . '" />' . "\n";
    }

    /**
     * Output Open Graph meta tags
     */
    private function output_open_graph_meta() {
        // Get page title
        $title = $this->get_title();
        
        // Get page description
        $description = $this->get_description();
        
        // Get page URL
        $url = $this->get_canonical_url();
        
        // Get page image
        $image = $this->get_image();
        
        // Get site name
        $site_name = get_bloginfo('name');
        
        // Get content type
        $type = $this->get_content_type();
        
        // Get locale
        $locale = get_locale();
        
        // Output meta tags
        echo '<meta property="og:title" content="' . esc_attr($title) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($description) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url($url) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr($site_name) . '" />' . "\n";
        echo '<meta property="og:type" content="' . esc_attr($type) . '" />' . "\n";
        echo '<meta property="og:locale" content="' . esc_attr($locale) . '" />' . "\n";
        
        if ($image) {
            echo '<meta property="og:image" content="' . esc_url($image['url']) . '" />' . "\n";
            echo '<meta property="og:image:width" content="' . esc_attr($image['width']) . '" />' . "\n";
            echo '<meta property="og:image:height" content="' . esc_attr($image['height']) . '" />' . "\n";
            echo '<meta property="og:image:alt" content="' . esc_attr($image['alt']) . '" />' . "\n";
        }
        
        // Add article specific meta tags
        if ('article' === $type && is_singular('post')) {
            $post = get_post();
            
            // Published time
            echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '" />' . "\n";
            
            // Modified time
            echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '" />' . "\n";
            
            // Author
            $author = get_the_author_meta('display_name', $post->post_author);
            echo '<meta property="article:author" content="' . esc_attr($author) . '" />' . "\n";
            
            // Categories
            $categories = get_the_category();
            if (!empty($categories)) {
                foreach ($categories as $category) {
                    echo '<meta property="article:section" content="' . esc_attr($category->name) . '" />' . "\n";
                }
            }
            
            // Tags
            $tags = get_the_tags();
            if (!empty($tags)) {
                foreach ($tags as $tag) {
                    echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '" />' . "\n";
                }
            }
        }
        
        // Add product specific meta tags
        if ('product' === $type && is_singular('product') && class_exists('WooCommerce')) {
            global $product;
            
            if ($product) {
                // Price
                if ($product->get_price()) {
                    echo '<meta property="product:price:amount" content="' . esc_attr($product->get_price()) . '" />' . "\n";
                    echo '<meta property="product:price:currency" content="' . esc_attr(get_woocommerce_currency()) . '" />' . "\n";
                }
                
                // Availability
                $availability = $product->is_in_stock() ? 'instock' : 'oos';
                echo '<meta property="product:availability" content="' . esc_attr($availability) . '" />' . "\n";
                
                // Brand
                $brands = wp_get_post_terms($product->get_id(), 'pa_brand');
                if (!empty($brands) && !is_wp_error($brands)) {
                    echo '<meta property="product:brand" content="' . esc_attr($brands[0]->name) . '" />' . "\n";
                }
            }
        }
    }

    /**
     * Output Twitter Card meta tags
     */
    private function output_twitter_card_meta() {
        // Get Twitter card type
        $card_type = $this->get_twitter_card_type();
        
        // Get page title
        $title = $this->get_title();
        
        // Get page description
        $description = $this->get_description();
        
        // Get page image
        $image = $this->get_image();
        
        // Get Twitter username
        $twitter_username = get_theme_mod('aqualuxe_twitter_username', '');
        
        // Output meta tags
        echo '<meta name="twitter:card" content="' . esc_attr($card_type) . '" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($title) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($description) . '" />' . "\n";
        
        if ($twitter_username) {
            echo '<meta name="twitter:site" content="@' . esc_attr($twitter_username) . '" />' . "\n";
        }
        
        if ($image) {
            echo '<meta name="twitter:image" content="' . esc_url($image['url']) . '" />' . "\n";
            echo '<meta name="twitter:image:alt" content="' . esc_attr($image['alt']) . '" />' . "\n";
        }
    }

    /**
     * Get page title
     *
     * @return string
     */
    private function get_title() {
        $title = '';
        
        if (is_home()) {
            $title = get_bloginfo('name');
        } elseif (is_singular()) {
            $title = get_the_title();
        } elseif (is_archive()) {
            $title = get_the_archive_title();
        } elseif (is_search()) {
            $title = sprintf(__('Search Results for: %s', 'aqualuxe'), get_search_query());
        } elseif (is_404()) {
            $title = __('Page Not Found', 'aqualuxe');
        }
        
        // Fallback to site name
        if (empty($title)) {
            $title = get_bloginfo('name');
        }
        
        return apply_filters('aqualuxe_social_meta_title', $title);
    }

    /**
     * Get page description
     *
     * @return string
     */
    private function get_description() {
        $description = '';
        
        if (is_singular()) {
            // Get post excerpt
            $post = get_post();
            
            if ($post) {
                if (has_excerpt($post->ID)) {
                    $description = get_the_excerpt();
                } else {
                    $description = $post->post_content;
                }
            }
        } elseif (is_home()) {
            $description = get_bloginfo('description');
        } elseif (is_category() || is_tag() || is_tax()) {
            $description = term_description();
        }
        
        // Clean up description
        if ($description) {
            // Strip shortcodes
            $description = strip_shortcodes($description);
            
            // Strip HTML tags
            $description = wp_strip_all_tags($description);
            
            // Trim to 155 characters
            $description = wp_trim_words($description, 30, '...');
        }
        
        // Fallback to site description
        if (empty($description)) {
            $description = get_bloginfo('description');
        }
        
        return apply_filters('aqualuxe_social_meta_description', $description);
    }

    /**
     * Get canonical URL
     *
     * @return string
     */
    private function get_canonical_url() {
        $url = '';
        
        if (is_singular()) {
            $url = get_permalink();
        } elseif (is_home()) {
            $url = home_url('/');
        } elseif (is_category() || is_tag() || is_tax()) {
            $url = get_term_link(get_queried_object());
        } elseif (is_post_type_archive()) {
            $url = get_post_type_archive_link(get_post_type());
        } elseif (is_author()) {
            $url = get_author_posts_url(get_queried_object_id());
        } elseif (is_search()) {
            $url = get_search_link();
        } elseif (is_404()) {
            $url = home_url('404');
        }
        
        // Fallback to current URL
        if (empty($url)) {
            global $wp;
            $url = home_url($wp->request);
        }
        
        return apply_filters('aqualuxe_social_meta_url', $url);
    }

    /**
     * Get page image
     *
     * @return array|false
     */
    private function get_image() {
        $image = false;
        
        if (is_singular() && has_post_thumbnail()) {
            $image_id = get_post_thumbnail_id();
            $image_data = wp_get_attachment_image_src($image_id, 'large');
            
            if ($image_data) {
                $image = array(
                    'url'    => $image_data[0],
                    'width'  => $image_data[1],
                    'height' => $image_data[2],
                    'alt'    => get_post_meta($image_id, '_wp_attachment_image_alt', true),
                );
            }
        }
        
        // Fallback to site logo
        if (!$image) {
            $custom_logo_id = get_theme_mod('custom_logo');
            
            if ($custom_logo_id) {
                $image_data = wp_get_attachment_image_src($custom_logo_id, 'full');
                
                if ($image_data) {
                    $image = array(
                        'url'    => $image_data[0],
                        'width'  => $image_data[1],
                        'height' => $image_data[2],
                        'alt'    => get_bloginfo('name'),
                    );
                }
            }
        }
        
        // Fallback to default image
        if (!$image) {
            $default_image = get_theme_mod('aqualuxe_default_social_image', '');
            
            if ($default_image) {
                $image_id = attachment_url_to_postid($default_image);
                
                if ($image_id) {
                    $image_data = wp_get_attachment_image_src($image_id, 'large');
                    
                    if ($image_data) {
                        $image = array(
                            'url'    => $image_data[0],
                            'width'  => $image_data[1],
                            'height' => $image_data[2],
                            'alt'    => get_bloginfo('name'),
                        );
                    }
                } else {
                    // If we can't get the attachment ID, use the URL directly
                    $image = array(
                        'url'    => $default_image,
                        'width'  => 1200, // Default width
                        'height' => 630,  // Default height
                        'alt'    => get_bloginfo('name'),
                    );
                }
            }
        }
        
        return apply_filters('aqualuxe_social_meta_image', $image);
    }

    /**
     * Get content type
     *
     * @return string
     */
    private function get_content_type() {
        $type = 'website';
        
        if (is_singular('post')) {
            $type = 'article';
        } elseif (is_singular('product') && class_exists('WooCommerce')) {
            $type = 'product';
        }
        
        return apply_filters('aqualuxe_social_meta_type', $type);
    }

    /**
     * Get Twitter card type
     *
     * @return string
     */
    private function get_twitter_card_type() {
        $card_type = 'summary';
        
        // Use large image card if we have a featured image
        if (is_singular() && has_post_thumbnail()) {
            $card_type = 'summary_large_image';
        }
        
        return apply_filters('aqualuxe_twitter_card_type', $card_type);
    }
}

// Initialize the social meta class
new AquaLuxe_Social_Meta();