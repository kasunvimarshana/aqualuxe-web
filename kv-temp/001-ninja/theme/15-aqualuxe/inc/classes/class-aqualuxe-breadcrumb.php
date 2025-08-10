<?php
/**
 * AquaLuxe Breadcrumb Class
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Breadcrumb class
 * 
 * Generates breadcrumb navigation for the theme
 */
class AquaLuxe_Breadcrumb {
    /**
     * Breadcrumb trail
     *
     * @var array
     */
    private $trail = array();

    /**
     * Breadcrumb args
     *
     * @var array
     */
    private $args = array();

    /**
     * Constructor
     *
     * @param array $args Arguments for the breadcrumb.
     */
    public function __construct($args = array()) {
        $defaults = array(
            'home_title'            => __('Home', 'aqualuxe'),
            'blog_title'            => __('Blog', 'aqualuxe'),
            'shop_title'            => __('Shop', 'aqualuxe'),
            'separator'             => '/',
            'show_on_home'          => false,
            'show_home_link'        => true,
            'show_current'          => true,
            'show_title'            => true,
            'before'                => '<span class="breadcrumb-item current">',
            'after'                 => '</span>',
            'before_link'           => '<span class="breadcrumb-item">',
            'after_link'            => '</span>',
            'home_class'            => 'home',
            'current_class'         => 'current',
            'link_before'           => '',
            'link_after'            => '',
            'link_in_before'        => '',
            'link_in_after'         => '',
        );

        $this->args = wp_parse_args($args, $defaults);
        $this->generate_trail();
    }

    /**
     * Generate the breadcrumb trail
     */
    private function generate_trail() {
        // Add home link
        if ($this->args['show_home_link']) {
            $this->add_home_link();
        }

        if (is_home()) {
            // Blog page
            $this->add_blog_link();
        } elseif (is_single() && !is_attachment()) {
            // Single post
            if (get_post_type() === 'post') {
                $this->add_blog_link();
                $this->add_post_categories();
                $this->add_current_post();
            } elseif (get_post_type() === 'product' && function_exists('is_product')) {
                // WooCommerce product
                $this->add_shop_link();
                $this->add_product_categories();
                $this->add_current_post();
            } elseif (get_post_type() === 'aqualuxe_service') {
                // Service post type
                $this->add_archive_link('aqualuxe_service', __('Services', 'aqualuxe'));
                $this->add_current_post();
            } elseif (get_post_type() === 'aqualuxe_event') {
                // Event post type
                $this->add_archive_link('aqualuxe_event', __('Events', 'aqualuxe'));
                $this->add_current_post();
            } else {
                // Other post types
                $post_type_obj = get_post_type_object(get_post_type());
                if ($post_type_obj) {
                    $this->add_archive_link(get_post_type(), $post_type_obj->labels->name);
                    $this->add_current_post();
                }
            }
        } elseif (is_page() && !is_front_page()) {
            // Page
            $this->add_page_ancestors();
            $this->add_current_page();
        } elseif (is_post_type_archive()) {
            // Post type archive
            $post_type = get_post_type();
            
            if ($post_type === 'product' && function_exists('is_shop')) {
                $this->add_shop_link(false);
            } else {
                $post_type_obj = get_post_type_object($post_type);
                if ($post_type_obj) {
                    $this->trail[] = array(
                        'title' => $post_type_obj->labels->name,
                        'url'   => '',
                    );
                }
            }
        } elseif (is_tax() || is_category() || is_tag()) {
            // Taxonomy archive
            $term = get_queried_object();
            
            if (is_tax('product_cat') && function_exists('is_product_category')) {
                // WooCommerce product category
                $this->add_shop_link();
                $this->add_term_ancestors($term);
                $this->add_current_term($term);
            } elseif (is_tax('product_tag') && function_exists('is_product_tag')) {
                // WooCommerce product tag
                $this->add_shop_link();
                $this->add_current_term($term);
            } elseif (is_category()) {
                // Post category
                $this->add_blog_link();
                $this->add_term_ancestors($term);
                $this->add_current_term($term);
            } elseif (is_tag()) {
                // Post tag
                $this->add_blog_link();
                $this->add_current_term($term);
            } else {
                // Other taxonomies
                $taxonomy = get_taxonomy($term->taxonomy);
                if ($taxonomy) {
                    $post_type = isset($taxonomy->object_type[0]) ? $taxonomy->object_type[0] : '';
                    
                    if ($post_type) {
                        $post_type_obj = get_post_type_object($post_type);
                        if ($post_type_obj) {
                            $this->add_archive_link($post_type, $post_type_obj->labels->name);
                        }
                    }
                    
                    $this->add_term_ancestors($term);
                    $this->add_current_term($term);
                }
            }
        } elseif (is_author()) {
            // Author archive
            $this->add_blog_link();
            
            $author_id = get_query_var('author');
            $this->trail[] = array(
                'title' => get_the_author_meta('display_name', $author_id),
                'url'   => '',
            );
        } elseif (is_date()) {
            // Date archive
            $this->add_blog_link();
            
            if (is_year()) {
                $this->trail[] = array(
                    'title' => get_the_date('Y'),
                    'url'   => '',
                );
            } elseif (is_month()) {
                $this->trail[] = array(
                    'title' => get_the_date('Y'),
                    'url'   => get_year_link(get_the_date('Y')),
                );
                $this->trail[] = array(
                    'title' => get_the_date('F'),
                    'url'   => '',
                );
            } elseif (is_day()) {
                $this->trail[] = array(
                    'title' => get_the_date('Y'),
                    'url'   => get_year_link(get_the_date('Y')),
                );
                $this->trail[] = array(
                    'title' => get_the_date('F'),
                    'url'   => get_month_link(get_the_date('Y'), get_the_date('m')),
                );
                $this->trail[] = array(
                    'title' => get_the_date('j'),
                    'url'   => '',
                );
            }
        } elseif (is_search()) {
            // Search results
            $this->trail[] = array(
                'title' => sprintf(__('Search results for: %s', 'aqualuxe'), get_search_query()),
                'url'   => '',
            );
        } elseif (is_404()) {
            // 404 page
            $this->trail[] = array(
                'title' => __('Page not found', 'aqualuxe'),
                'url'   => '',
            );
        }
    }

    /**
     * Add home link to trail
     */
    private function add_home_link() {
        $this->trail[] = array(
            'title' => $this->args['home_title'],
            'url'   => home_url('/'),
        );
    }

    /**
     * Add blog link to trail
     */
    private function add_blog_link() {
        $blog_page_id = get_option('page_for_posts');
        
        if ($blog_page_id) {
            $this->trail[] = array(
                'title' => get_the_title($blog_page_id),
                'url'   => get_permalink($blog_page_id),
            );
        } else {
            $this->trail[] = array(
                'title' => $this->args['blog_title'],
                'url'   => get_post_type_archive_link('post'),
            );
        }
    }

    /**
     * Add shop link to trail
     *
     * @param bool $link Whether to link to the shop page.
     */
    private function add_shop_link($link = true) {
        if (function_exists('wc_get_page_id')) {
            $shop_page_id = wc_get_page_id('shop');
            
            if ($shop_page_id) {
                $this->trail[] = array(
                    'title' => get_the_title($shop_page_id),
                    'url'   => $link ? get_permalink($shop_page_id) : '',
                );
                return;
            }
        }
        
        $this->trail[] = array(
            'title' => $this->args['shop_title'],
            'url'   => $link ? get_post_type_archive_link('product') : '',
        );
    }

    /**
     * Add archive link to trail
     *
     * @param string $post_type Post type.
     * @param string $title     Archive title.
     */
    private function add_archive_link($post_type, $title) {
        $this->trail[] = array(
            'title' => $title,
            'url'   => get_post_type_archive_link($post_type),
        );
    }

    /**
     * Add post categories to trail
     */
    private function add_post_categories() {
        $categories = get_the_category();
        
        if ($categories) {
            $main_category = $categories[0];
            $this->add_term_ancestors($main_category);
            
            $this->trail[] = array(
                'title' => $main_category->name,
                'url'   => get_term_link($main_category),
            );
        }
    }

    /**
     * Add product categories to trail
     */
    private function add_product_categories() {
        if (function_exists('wc_get_product_terms')) {
            global $post;
            
            $terms = wc_get_product_terms(
                $post->ID,
                'product_cat',
                array(
                    'orderby' => 'parent',
                    'order'   => 'DESC',
                )
            );
            
            if ($terms) {
                $main_term = $terms[0];
                $this->add_term_ancestors($main_term);
                
                $this->trail[] = array(
                    'title' => $main_term->name,
                    'url'   => get_term_link($main_term),
                );
            }
        }
    }

    /**
     * Add term ancestors to trail
     *
     * @param object $term Term object.
     */
    private function add_term_ancestors($term) {
        $ancestors = get_ancestors($term->term_id, $term->taxonomy);
        
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $ancestor_term = get_term($ancestor, $term->taxonomy);
                
                if ($ancestor_term) {
                    $this->trail[] = array(
                        'title' => $ancestor_term->name,
                        'url'   => get_term_link($ancestor_term),
                    );
                }
            }
        }
    }

    /**
     * Add current term to trail
     *
     * @param object $term Term object.
     */
    private function add_current_term($term) {
        $this->trail[] = array(
            'title' => $term->name,
            'url'   => '',
        );
    }

    /**
     * Add page ancestors to trail
     */
    private function add_page_ancestors() {
        global $post;
        
        $ancestors = get_post_ancestors($post);
        
        if ($ancestors) {
            $ancestors = array_reverse($ancestors);
            
            foreach ($ancestors as $ancestor) {
                $this->trail[] = array(
                    'title' => get_the_title($ancestor),
                    'url'   => get_permalink($ancestor),
                );
            }
        }
    }

    /**
     * Add current post to trail
     */
    private function add_current_post() {
        if ($this->args['show_current']) {
            $this->trail[] = array(
                'title' => get_the_title(),
                'url'   => '',
            );
        }
    }

    /**
     * Add current page to trail
     */
    private function add_current_page() {
        if ($this->args['show_current']) {
            $this->trail[] = array(
                'title' => get_the_title(),
                'url'   => '',
            );
        }
    }

    /**
     * Get the breadcrumb trail
     *
     * @return array
     */
    public function get_trail() {
        return $this->trail;
    }

    /**
     * Display the breadcrumb
     *
     * @param array $args Arguments for the breadcrumb.
     */
    public function display($args = array()) {
        $args = wp_parse_args($args, $this->args);
        
        // Don't display on the homepage
        if (is_front_page() && !$args['show_on_home']) {
            return;
        }
        
        $trail = $this->get_trail();
        
        if (empty($trail)) {
            return;
        }
        
        $output = '<nav class="breadcrumbs" aria-label="' . esc_attr__('Breadcrumb', 'aqualuxe') . '">';
        $output .= '<ol class="breadcrumb-list flex flex-wrap items-center text-sm">';
        
        foreach ($trail as $key => $item) {
            $is_last = ($key === count($trail) - 1);
            
            if (!$is_last && !empty($item['url'])) {
                $output .= $args['before_link'];
                $output .= '<a href="' . esc_url($item['url']) . '" class="breadcrumb-link text-dark-light dark:text-light-dark hover:text-primary dark:hover:text-secondary-light transition-colors duration-200">';
                $output .= $args['link_in_before'] . esc_html($item['title']) . $args['link_in_after'];
                $output .= '</a>';
                $output .= $args['after_link'];
            } else {
                $output .= $args['before'];
                $output .= esc_html($item['title']);
                $output .= $args['after'];
            }
            
            if (!$is_last) {
                $output .= '<span class="breadcrumb-separator mx-2 text-dark-light dark:text-light-dark" aria-hidden="true">' . $args['separator'] . '</span>';
            }
        }
        
        $output .= '</ol>';
        $output .= '</nav>';
        
        echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}