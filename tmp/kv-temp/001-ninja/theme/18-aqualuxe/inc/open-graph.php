<?php
/**
 * Open Graph metadata implementation
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Open Graph Class
 * 
 * Implements Open Graph and Twitter Card metadata for better social sharing
 */
class AquaLuxe_Open_Graph {
    /**
     * Constructor
     */
    public function __construct() {
        // Add Open Graph tags to head
        add_action('wp_head', [$this, 'output_open_graph_tags'], 5);
        
        // Add Twitter Card tags to head
        add_action('wp_head', [$this, 'output_twitter_card_tags'], 5);
    }

    /**
     * Output Open Graph tags
     */
    public function output_open_graph_tags() {
        // Get Open Graph data
        $og_tags = $this->get_open_graph_data();
        
        // Output Open Graph tags
        foreach ($og_tags as $property => $content) {
            if (!empty($content)) {
                echo '<meta property="' . esc_attr($property) . '" content="' . esc_attr($content) . '" />' . "\n";
            }
        }
    }

    /**
     * Output Twitter Card tags
     */
    public function output_twitter_card_tags() {
        // Get Twitter Card data
        $twitter_tags = $this->get_twitter_card_data();
        
        // Output Twitter Card tags
        foreach ($twitter_tags as $name => $content) {
            if (!empty($content)) {
                echo '<meta name="' . esc_attr($name) . '" content="' . esc_attr($content) . '" />' . "\n";
            }
        }
    }

    /**
     * Get Open Graph data based on current page
     * 
     * @return array Open Graph data
     */
    private function get_open_graph_data() {
        $og_tags = [
            'og:locale' => get_locale(),
            'og:site_name' => get_bloginfo('name'),
            'og:type' => 'website',
        ];
        
        // Set page-specific Open Graph data
        if (is_front_page() || is_home()) {
            $og_tags['og:title'] = get_bloginfo('name');
            $og_tags['og:description'] = get_bloginfo('description');
            $og_tags['og:url'] = home_url('/');
            
            // Get site logo or default image
            $og_tags['og:image'] = $this->get_default_image();
            
        } elseif (is_singular()) {
            $og_tags['og:title'] = get_the_title();
            $og_tags['og:description'] = $this->get_excerpt_or_content();
            $og_tags['og:url'] = get_permalink();
            
            // Set content type
            if (is_singular('post')) {
                $og_tags['og:type'] = 'article';
                
                // Add article specific tags
                $og_tags['article:published_time'] = get_the_date('c');
                $og_tags['article:modified_time'] = get_the_modified_date('c');
                $og_tags['article:author'] = get_author_posts_url(get_the_author_meta('ID'));
                
                // Add categories and tags
                $categories = get_the_category();
                if (!empty($categories)) {
                    $og_tags['article:section'] = $categories[0]->name;
                }
                
                $tags = get_the_tags();
                if (!empty($tags)) {
                    $tag_names = [];
                    foreach ($tags as $tag) {
                        $tag_names[] = $tag->name;
                    }
                    $og_tags['article:tag'] = implode(', ', $tag_names);
                }
            } elseif (is_singular('product')) {
                $og_tags['og:type'] = 'product';
                
                // Add product specific tags if WooCommerce is active
                if (class_exists('WooCommerce')) {
                    global $product;
                    
                    if (is_a($product, 'WC_Product')) {
                        $og_tags['product:price:amount'] = $product->get_price();
                        $og_tags['product:price:currency'] = get_woocommerce_currency();
                        
                        if ($product->is_in_stock()) {
                            $og_tags['product:availability'] = 'in stock';
                        } else {
                            $og_tags['product:availability'] = 'out of stock';
                        }
                    }
                }
            }
            
            // Get featured image or default image
            $og_tags['og:image'] = $this->get_featured_image();
            
        } elseif (is_archive()) {
            if (is_category() || is_tag() || is_tax()) {
                $term = get_queried_object();
                $og_tags['og:title'] = single_term_title('', false);
                $og_tags['og:description'] = term_description();
                $og_tags['og:url'] = get_term_link($term);
            } elseif (is_post_type_archive()) {
                $post_type = get_post_type_object(get_post_type());
                $og_tags['og:title'] = $post_type->labels->name;
                $og_tags['og:url'] = get_post_type_archive_link(get_post_type());
            } elseif (is_author()) {
                $og_tags['og:title'] = get_the_author_meta('display_name', get_query_var('author'));
                $og_tags['og:description'] = get_the_author_meta('description', get_query_var('author'));
                $og_tags['og:url'] = get_author_posts_url(get_query_var('author'));
            } else {
                $og_tags['og:title'] = get_the_archive_title();
                $og_tags['og:description'] = get_the_archive_description();
                $og_tags['og:url'] = get_permalink();
            }
            
            // Get default image
            $og_tags['og:image'] = $this->get_default_image();
        } elseif (is_search()) {
            $og_tags['og:title'] = sprintf(__('Search Results for "%s"', 'aqualuxe'), get_search_query());
            $og_tags['og:url'] = get_search_link();
            
            // Get default image
            $og_tags['og:image'] = $this->get_default_image();
        }
        
        // Add image dimensions if available
        if (!empty($og_tags['og:image'])) {
            $image_id = $this->get_image_id_from_url($og_tags['og:image']);
            if ($image_id) {
                $image_data = wp_get_attachment_image_src($image_id, 'full');
                if ($image_data) {
                    $og_tags['og:image:width'] = $image_data[1];
                    $og_tags['og:image:height'] = $image_data[2];
                    $og_tags['og:image:alt'] = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                }
            }
        }
        
        // Filter Open Graph tags
        return apply_filters('aqualuxe_open_graph_tags', $og_tags);
    }

    /**
     * Get Twitter Card data based on current page
     * 
     * @return array Twitter Card data
     */
    private function get_twitter_card_data() {
        // Get Twitter username from theme options
        $twitter_username = get_theme_mod('aqualuxe_twitter_username', '');
        
        $twitter_tags = [
            'twitter:card' => 'summary_large_image',
        ];
        
        // Add Twitter site username if available
        if (!empty($twitter_username)) {
            $twitter_tags['twitter:site'] = '@' . str_replace('@', '', $twitter_username);
        }
        
        // Set page-specific Twitter Card data
        if (is_front_page() || is_home()) {
            $twitter_tags['twitter:title'] = get_bloginfo('name');
            $twitter_tags['twitter:description'] = get_bloginfo('description');
            $twitter_tags['twitter:image'] = $this->get_default_image();
            
        } elseif (is_singular()) {
            $twitter_tags['twitter:title'] = get_the_title();
            $twitter_tags['twitter:description'] = $this->get_excerpt_or_content();
            $twitter_tags['twitter:image'] = $this->get_featured_image();
            
            // Add author Twitter handle if available
            $author_twitter = get_the_author_meta('twitter', get_the_author_meta('ID'));
            if (!empty($author_twitter)) {
                $twitter_tags['twitter:creator'] = '@' . str_replace('@', '', $author_twitter);
            }
            
        } elseif (is_archive()) {
            if (is_category() || is_tag() || is_tax()) {
                $twitter_tags['twitter:title'] = single_term_title('', false);
                $twitter_tags['twitter:description'] = term_description();
            } elseif (is_post_type_archive()) {
                $post_type = get_post_type_object(get_post_type());
                $twitter_tags['twitter:title'] = $post_type->labels->name;
            } elseif (is_author()) {
                $twitter_tags['twitter:title'] = get_the_author_meta('display_name', get_query_var('author'));
                $twitter_tags['twitter:description'] = get_the_author_meta('description', get_query_var('author'));
            } else {
                $twitter_tags['twitter:title'] = get_the_archive_title();
                $twitter_tags['twitter:description'] = get_the_archive_description();
            }
            
            $twitter_tags['twitter:image'] = $this->get_default_image();
            
        } elseif (is_search()) {
            $twitter_tags['twitter:title'] = sprintf(__('Search Results for "%s"', 'aqualuxe'), get_search_query());
            $twitter_tags['twitter:image'] = $this->get_default_image();
        }
        
        // Add image alt text if available
        if (!empty($twitter_tags['twitter:image'])) {
            $image_id = $this->get_image_id_from_url($twitter_tags['twitter:image']);
            if ($image_id) {
                $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                if (!empty($alt_text)) {
                    $twitter_tags['twitter:image:alt'] = $alt_text;
                }
            }
        }
        
        // Filter Twitter Card tags
        return apply_filters('aqualuxe_twitter_card_tags', $twitter_tags);
    }

    /**
     * Get featured image URL
     * 
     * @return string Featured image URL or default image URL
     */
    private function get_featured_image() {
        if (has_post_thumbnail()) {
            $image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'full');
            if ($image) {
                return $image[0];
            }
        }
        
        return $this->get_default_image();
    }

    /**
     * Get default image URL
     * 
     * @return string Default image URL
     */
    private function get_default_image() {
        // Try to get site logo
        $logo_id = get_theme_mod('custom_logo');
        if ($logo_id) {
            $logo_image = wp_get_attachment_image_src($logo_id, 'full');
            if ($logo_image) {
                return $logo_image[0];
            }
        }
        
        // Try to get default OG image from theme options
        $default_og_image = get_theme_mod('aqualuxe_default_og_image');
        if ($default_og_image) {
            return $default_og_image;
        }
        
        // Return a placeholder image as last resort
        return AQUALUXE_URI . '/assets/images/default-og-image.jpg';
    }

    /**
     * Get excerpt or content for description
     * 
     * @return string Excerpt or trimmed content
     */
    private function get_excerpt_or_content() {
        if (has_excerpt()) {
            return wp_strip_all_tags(get_the_excerpt());
        }
        
        $content = get_the_content();
        $content = strip_shortcodes($content);
        $content = wp_strip_all_tags($content);
        $content = str_replace(']]>', ']]&gt;', $content);
        $content = mb_substr($content, 0, 300);
        
        return $content;
    }

    /**
     * Get image ID from URL
     * 
     * @param string $url Image URL
     * @return int|false Image ID or false if not found
     */
    private function get_image_id_from_url($url) {
        global $wpdb;
        
        // Remove any image size from URL
        $url = preg_replace('/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $url);
        
        // Get attachment ID from URL
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $url));
        
        if (!empty($attachment)) {
            return $attachment[0];
        }
        
        // Try to get attachment ID by comparing URL to uploads directory
        $upload_dir = wp_upload_dir();
        $base_url = $upload_dir['baseurl'] . '/';
        
        if (strpos($url, $base_url) !== false) {
            $file = basename($url);
            $query = [
                'post_type' => 'attachment',
                'fields' => 'ids',
                'meta_query' => [
                    [
                        'value' => $file,
                        'compare' => 'LIKE',
                    ],
                ],
            ];
            
            $ids = get_posts($query);
            
            if (!empty($ids)) {
                return $ids[0];
            }
        }
        
        return false;
    }
}

// Initialize the Open Graph class
new AquaLuxe_Open_Graph();