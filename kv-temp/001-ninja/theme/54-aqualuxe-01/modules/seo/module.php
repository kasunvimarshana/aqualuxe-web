<?php
/**
 * AquaLuxe SEO Module
 *
 * @package AquaLuxe
 * @subpackage SEO
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe SEO Module Class
 * 
 * Handles SEO functionality for the theme including schema.org markup and Open Graph tags
 */
class AquaLuxe_SEO_Module {
    /**
     * Instance of this class
     *
     * @var AquaLuxe_SEO_Module
     */
    private static $instance = null;

    /**
     * Module settings
     *
     * @var array
     */
    private $settings = [];

    /**
     * Get the singleton instance of this class
     *
     * @return AquaLuxe_SEO_Module
     */
    public static function instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        // Load module settings
        $this->load_settings();
        
        // Initialize module
        $this->init();
    }

    /**
     * Load module settings
     */
    private function load_settings() {
        $this->settings = apply_filters('aqualuxe_seo_settings', [
            'enabled' => true,
            'schema_markup' => true,
            'open_graph' => true,
            'twitter_cards' => true,
            'social_profiles' => [
                'facebook' => get_theme_mod('aqualuxe_facebook_url', ''),
                'twitter' => get_theme_mod('aqualuxe_twitter_url', ''),
                'instagram' => get_theme_mod('aqualuxe_instagram_url', ''),
                'linkedin' => get_theme_mod('aqualuxe_linkedin_url', ''),
                'youtube' => get_theme_mod('aqualuxe_youtube_url', ''),
                'pinterest' => get_theme_mod('aqualuxe_pinterest_url', ''),
            ],
            'twitter_username' => get_theme_mod('aqualuxe_twitter_username', ''),
            'facebook_app_id' => get_theme_mod('aqualuxe_facebook_app_id', ''),
            'default_image' => get_theme_mod('aqualuxe_default_social_image', get_template_directory_uri() . '/assets/images/social-default.jpg'),
            'organization_schema' => [
                'name' => get_bloginfo('name'),
                'logo' => get_theme_mod('aqualuxe_organization_logo', get_template_directory_uri() . '/assets/images/logo.png'),
                'type' => get_theme_mod('aqualuxe_organization_type', 'Organization'),
            ],
        ]);
    }

    /**
     * Initialize module
     */
    private function init() {
        // Check if module is enabled
        if (!$this->settings['enabled']) {
            return;
        }

        // Register hooks
        $this->register_hooks();
    }

    /**
     * Register hooks
     */
    private function register_hooks() {
        // Add Open Graph tags to head
        if ($this->settings['open_graph']) {
            add_action('wp_head', [$this, 'add_open_graph_tags'], 5);
        }
        
        // Add Twitter Card tags to head
        if ($this->settings['twitter_cards']) {
            add_action('wp_head', [$this, 'add_twitter_card_tags'], 5);
        }
        
        // Add schema.org markup
        if ($this->settings['schema_markup']) {
            add_action('wp_footer', [$this, 'add_schema_markup'], 10);
        }
        
        // Add customizer settings
        add_action('customize_register', [$this, 'customize_register']);
        
        // Add admin settings
        add_action('admin_init', [$this, 'admin_init']);
        
        // Add admin menu
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    /**
     * Add Open Graph tags to head
     */
    public function add_open_graph_tags() {
        global $post;
        
        // Default values
        $og_title = get_bloginfo('name');
        $og_description = get_bloginfo('description');
        $og_url = home_url('/');
        $og_image = $this->settings['default_image'];
        $og_type = 'website';
        
        // Get values for current page
        if (is_singular() && $post) {
            $og_title = get_the_title($post->ID);
            $og_url = get_permalink($post->ID);
            $og_type = is_single() ? 'article' : 'website';
            
            // Get excerpt or content for description
            $og_description = has_excerpt($post->ID) ? get_the_excerpt($post->ID) : wp_trim_words(strip_shortcodes($post->post_content), 20);
            
            // Get featured image
            if (has_post_thumbnail($post->ID)) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
                if ($image) {
                    $og_image = $image[0];
                }
            }
        } elseif (is_tax() || is_category() || is_tag()) {
            $term = get_queried_object();
            if ($term) {
                $og_title = $term->name;
                $og_description = $term->description ? $term->description : $og_description;
                $og_url = get_term_link($term);
            }
        } elseif (is_author()) {
            $author = get_queried_object();
            if ($author) {
                $og_title = $author->display_name;
                $og_description = $author->description ? $author->description : $og_description;
                $og_url = get_author_posts_url($author->ID);
                $og_type = 'profile';
            }
        } elseif (is_search()) {
            $og_title = sprintf(__('Search results for "%s"', 'aqualuxe'), get_search_query());
            $og_description = sprintf(__('Search results for "%s" at %s', 'aqualuxe'), get_search_query(), get_bloginfo('name'));
            $og_url = get_search_link();
        } elseif (is_archive()) {
            if (is_date()) {
                if (is_day()) {
                    $og_title = sprintf(__('Daily Archives: %s', 'aqualuxe'), get_the_date());
                } elseif (is_month()) {
                    $og_title = sprintf(__('Monthly Archives: %s', 'aqualuxe'), get_the_date('F Y'));
                } elseif (is_year()) {
                    $og_title = sprintf(__('Yearly Archives: %s', 'aqualuxe'), get_the_date('Y'));
                }
            }
            $og_url = get_permalink();
        }
        
        // Clean up description
        $og_description = wp_strip_all_tags($og_description);
        
        // Output Open Graph tags
        echo '<meta property="og:locale" content="' . esc_attr(get_locale()) . '" />' . "\n";
        echo '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
        echo '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
        echo '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
        echo '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
        echo '<meta property="og:site_name" content="' . esc_attr(get_bloginfo('name')) . '" />' . "\n";
        echo '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
        
        // Add article specific tags
        if ($og_type === 'article' && is_singular()) {
            echo '<meta property="article:published_time" content="' . esc_attr(get_the_date('c')) . '" />' . "\n";
            echo '<meta property="article:modified_time" content="' . esc_attr(get_the_modified_date('c')) . '" />' . "\n";
            
            // Add categories as article:section
            $categories = get_the_category();
            if ($categories) {
                foreach ($categories as $category) {
                    echo '<meta property="article:section" content="' . esc_attr($category->name) . '" />' . "\n";
                }
            }
            
            // Add tags as article:tag
            $tags = get_the_tags();
            if ($tags) {
                foreach ($tags as $tag) {
                    echo '<meta property="article:tag" content="' . esc_attr($tag->name) . '" />' . "\n";
                }
            }
        }
        
        // Add Facebook specific tags
        if ($this->settings['facebook_app_id']) {
            echo '<meta property="fb:app_id" content="' . esc_attr($this->settings['facebook_app_id']) . '" />' . "\n";
        }
    }

    /**
     * Add Twitter Card tags to head
     */
    public function add_twitter_card_tags() {
        global $post;
        
        // Default values
        $twitter_title = get_bloginfo('name');
        $twitter_description = get_bloginfo('description');
        $twitter_image = $this->settings['default_image'];
        $twitter_card = 'summary_large_image';
        
        // Get values for current page
        if (is_singular() && $post) {
            $twitter_title = get_the_title($post->ID);
            
            // Get excerpt or content for description
            $twitter_description = has_excerpt($post->ID) ? get_the_excerpt($post->ID) : wp_trim_words(strip_shortcodes($post->post_content), 20);
            
            // Get featured image
            if (has_post_thumbnail($post->ID)) {
                $image = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
                if ($image) {
                    $twitter_image = $image[0];
                }
            }
        } elseif (is_tax() || is_category() || is_tag()) {
            $term = get_queried_object();
            if ($term) {
                $twitter_title = $term->name;
                $twitter_description = $term->description ? $term->description : $twitter_description;
            }
        }
        
        // Clean up description
        $twitter_description = wp_strip_all_tags($twitter_description);
        
        // Output Twitter Card tags
        echo '<meta name="twitter:card" content="' . esc_attr($twitter_card) . '" />' . "\n";
        echo '<meta name="twitter:title" content="' . esc_attr($twitter_title) . '" />' . "\n";
        echo '<meta name="twitter:description" content="' . esc_attr($twitter_description) . '" />' . "\n";
        echo '<meta name="twitter:image" content="' . esc_url($twitter_image) . '" />' . "\n";
        
        // Add Twitter site username if available
        if ($this->settings['twitter_username']) {
            echo '<meta name="twitter:site" content="@' . esc_attr($this->settings['twitter_username']) . '" />' . "\n";
        }
    }

    /**
     * Add schema.org markup to footer
     */
    public function add_schema_markup() {
        global $post;
        
        // Website schema
        $website_schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'url' => home_url('/'),
            'name' => get_bloginfo('name'),
            'description' => get_bloginfo('description'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => home_url('/?s={search_term_string}'),
                'query-input' => 'required name=search_term_string',
            ],
        ];
        
        // Organization schema
        $organization_schema = [
            '@context' => 'https://schema.org',
            '@type' => $this->settings['organization_schema']['type'],
            '@id' => home_url('/#organization'),
            'name' => $this->settings['organization_schema']['name'],
            'url' => home_url('/'),
            'logo' => [
                '@type' => 'ImageObject',
                'url' => $this->settings['organization_schema']['logo'],
            ],
        ];
        
        // Add social profiles if available
        $social_profiles = array_filter($this->settings['social_profiles']);
        if (!empty($social_profiles)) {
            $organization_schema['sameAs'] = array_values($social_profiles);
        }
        
        // Page-specific schema
        $page_schema = [];
        
        if (is_singular() && $post) {
            // Get featured image
            $image = '';
            if (has_post_thumbnail($post->ID)) {
                $image_data = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID), 'large');
                if ($image_data) {
                    $image = $image_data[0];
                }
            }
            
            if (is_single()) {
                // Article schema for blog posts
                $page_schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'Article',
                    'mainEntityOfPage' => [
                        '@type' => 'WebPage',
                        '@id' => get_permalink(),
                    ],
                    'headline' => get_the_title(),
                    'description' => has_excerpt() ? get_the_excerpt() : wp_trim_words(strip_shortcodes($post->post_content), 20),
                    'datePublished' => get_the_date('c'),
                    'dateModified' => get_the_modified_date('c'),
                    'author' => [
                        '@type' => 'Person',
                        'name' => get_the_author(),
                    ],
                    'publisher' => [
                        '@type' => 'Organization',
                        'name' => get_bloginfo('name'),
                        'logo' => [
                            '@type' => 'ImageObject',
                            'url' => $this->settings['organization_schema']['logo'],
                        ],
                    ],
                ];
                
                // Add image if available
                if ($image) {
                    $page_schema['image'] = [
                        '@type' => 'ImageObject',
                        'url' => $image,
                    ];
                }
            } else {
                // WebPage schema for pages
                $page_schema = [
                    '@context' => 'https://schema.org',
                    '@type' => 'WebPage',
                    '@id' => get_permalink(),
                    'url' => get_permalink(),
                    'name' => get_the_title(),
                    'description' => has_excerpt() ? get_the_excerpt() : wp_trim_words(strip_shortcodes($post->post_content), 20),
                    'datePublished' => get_the_date('c'),
                    'dateModified' => get_the_modified_date('c'),
                    'isPartOf' => [
                        '@type' => 'WebSite',
                        '@id' => home_url('/#website'),
                    ],
                    'inLanguage' => get_locale(),
                ];
                
                // Add image if available
                if ($image) {
                    $page_schema['primaryImageOfPage'] = [
                        '@type' => 'ImageObject',
                        '@id' => get_permalink() . '#primaryimage',
                        'url' => $image,
                    ];
                }
                
                // Add special schema for specific templates
                if (is_page_template('page-templates/services.php')) {
                    // Add service schema for services page
                    $service_schema = $this->get_service_schema();
                    if ($service_schema) {
                        $page_schema['mainEntity'] = $service_schema;
                    }
                } elseif (is_page_template('page-templates/about.php')) {
                    // Add about page schema
                    $about_schema = $this->get_about_schema();
                    if ($about_schema) {
                        $page_schema['mainEntity'] = $about_schema;
                    }
                }
            }
        } elseif (is_home() || is_archive()) {
            // CollectionPage schema for archives and blog
            $page_schema = [
                '@context' => 'https://schema.org',
                '@type' => 'CollectionPage',
                '@id' => get_permalink(),
                'url' => get_permalink(),
                'name' => is_home() ? __('Blog', 'aqualuxe') : get_the_archive_title(),
                'isPartOf' => [
                    '@type' => 'WebSite',
                    '@id' => home_url('/#website'),
                ],
                'inLanguage' => get_locale(),
            ];
        }
        
        // Output schema markup
        echo '<script type="application/ld+json">' . wp_json_encode($website_schema) . '</script>' . "\n";
        echo '<script type="application/ld+json">' . wp_json_encode($organization_schema) . '</script>' . "\n";
        
        if (!empty($page_schema)) {
            echo '<script type="application/ld+json">' . wp_json_encode($page_schema) . '</script>' . "\n";
        }
        
        // Add breadcrumb schema if available
        $breadcrumb_schema = $this->get_breadcrumb_schema();
        if ($breadcrumb_schema) {
            echo '<script type="application/ld+json">' . wp_json_encode($breadcrumb_schema) . '</script>' . "\n";
        }
    }

    /**
     * Get service schema for services page
     * 
     * @return array
     */
    private function get_service_schema() {
        // Sample service data - in a real implementation, this would come from custom fields or post meta
        $services = [
            [
                'name' => __('Pool Installation', 'aqualuxe'),
                'description' => __('Professional installation of residential and commercial pools with expert precision and attention to detail.', 'aqualuxe'),
                'url' => home_url('/services/#pool-installation'),
            ],
            [
                'name' => __('Maintenance & Cleaning', 'aqualuxe'),
                'description' => __('Regular maintenance and cleaning services to keep your pool in pristine condition year-round.', 'aqualuxe'),
                'url' => home_url('/services/#maintenance'),
            ],
            [
                'name' => __('Repair Services', 'aqualuxe'),
                'description' => __('Expert repair services for all types of pool equipment, fixtures, and systems.', 'aqualuxe'),
                'url' => home_url('/services/#repair'),
            ],
        ];
        
        $service_schema = [];
        
        foreach ($services as $service) {
            $service_schema[] = [
                '@type' => 'Service',
                'name' => $service['name'],
                'description' => $service['description'],
                'url' => $service['url'],
                'provider' => [
                    '@type' => 'Organization',
                    'name' => get_bloginfo('name'),
                    'url' => home_url('/'),
                ],
            ];
        }
        
        return $service_schema;
    }

    /**
     * Get about schema for about page
     * 
     * @return array
     */
    private function get_about_schema() {
        // About page schema
        return [
            '@type' => 'AboutPage',
            'name' => __('About Us', 'aqualuxe'),
            'description' => __('Learn more about our company, mission, and team.', 'aqualuxe'),
        ];
    }

    /**
     * Get breadcrumb schema
     * 
     * @return array|false
     */
    private function get_breadcrumb_schema() {
        // Only add breadcrumbs if not on the homepage
        if (is_front_page()) {
            return false;
        }
        
        $breadcrumbs = [];
        
        // Add home as first item
        $breadcrumbs[] = [
            '@type' => 'ListItem',
            'position' => 1,
            'name' => __('Home', 'aqualuxe'),
            'item' => home_url('/'),
        ];
        
        $position = 2;
        
        if (is_singular()) {
            global $post;
            
            // Add parent pages if any
            if ($post->post_parent) {
                $parent_id = $post->post_parent;
                $parents = [];
                
                while ($parent_id) {
                    $parent = get_post($parent_id);
                    $parents[] = [
                        'name' => get_the_title($parent->ID),
                        'url' => get_permalink($parent->ID),
                    ];
                    $parent_id = $parent->post_parent;
                }
                
                // Add parents in correct order
                $parents = array_reverse($parents);
                
                foreach ($parents as $parent) {
                    $breadcrumbs[] = [
                        '@type' => 'ListItem',
                        'position' => $position,
                        'name' => $parent['name'],
                        'item' => $parent['url'],
                    ];
                    $position++;
                }
            }
            
            // Add current page
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => get_the_title(),
                'item' => get_permalink(),
            ];
        } elseif (is_archive()) {
            if (is_category() || is_tag() || is_tax()) {
                $term = get_queried_object();
                
                // Add parent terms if any
                if ($term->parent) {
                    $taxonomy = $term->taxonomy;
                    $parents = [];
                    $parent_id = $term->parent;
                    
                    while ($parent_id) {
                        $parent = get_term($parent_id, $taxonomy);
                        $parents[] = [
                            'name' => $parent->name,
                            'url' => get_term_link($parent),
                        ];
                        $parent_id = $parent->parent;
                    }
                    
                    // Add parents in correct order
                    $parents = array_reverse($parents);
                    
                    foreach ($parents as $parent) {
                        $breadcrumbs[] = [
                            '@type' => 'ListItem',
                            'position' => $position,
                            'name' => $parent['name'],
                            'item' => $parent['url'],
                        ];
                        $position++;
                    }
                }
                
                // Add current term
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $term->name,
                    'item' => get_term_link($term),
                ];
            } elseif (is_author()) {
                // Add author archive
                $author = get_queried_object();
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => $author->display_name,
                    'item' => get_author_posts_url($author->ID),
                ];
            } elseif (is_date()) {
                // Add date archive
                if (is_year()) {
                    $breadcrumbs[] = [
                        '@type' => 'ListItem',
                        'position' => $position,
                        'name' => get_the_date('Y'),
                        'item' => get_year_link(get_query_var('year')),
                    ];
                } elseif (is_month()) {
                    // Add year
                    $breadcrumbs[] = [
                        '@type' => 'ListItem',
                        'position' => $position,
                        'name' => get_the_date('Y'),
                        'item' => get_year_link(get_query_var('year')),
                    ];
                    $position++;
                    
                    // Add month
                    $breadcrumbs[] = [
                        '@type' => 'ListItem',
                        'position' => $position,
                        'name' => get_the_date('F'),
                        'item' => get_month_link(get_query_var('year'), get_query_var('monthnum')),
                    ];
                } elseif (is_day()) {
                    // Add year
                    $breadcrumbs[] = [
                        '@type' => 'ListItem',
                        'position' => $position,
                        'name' => get_the_date('Y'),
                        'item' => get_year_link(get_query_var('year')),
                    ];
                    $position++;
                    
                    // Add month
                    $breadcrumbs[] = [
                        '@type' => 'ListItem',
                        'position' => $position,
                        'name' => get_the_date('F'),
                        'item' => get_month_link(get_query_var('year'), get_query_var('monthnum')),
                    ];
                    $position++;
                    
                    // Add day
                    $breadcrumbs[] = [
                        '@type' => 'ListItem',
                        'position' => $position,
                        'name' => get_the_date('j'),
                        'item' => get_day_link(get_query_var('year'), get_query_var('monthnum'), get_query_var('day')),
                    ];
                }
            } else {
                // Generic archive
                $breadcrumbs[] = [
                    '@type' => 'ListItem',
                    'position' => $position,
                    'name' => get_the_archive_title(),
                    'item' => get_permalink(),
                ];
            }
        } elseif (is_search()) {
            // Search results
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => sprintf(__('Search results for "%s"', 'aqualuxe'), get_search_query()),
                'item' => get_search_link(),
            ];
        } elseif (is_404()) {
            // 404 page
            $breadcrumbs[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => __('Page not found', 'aqualuxe'),
                'item' => home_url(add_query_arg([], $GLOBALS['wp']->request)),
            ];
        }
        
        // Return breadcrumb schema
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbs,
        ];
    }

    /**
     * Register customizer settings
     *
     * @param WP_Customize_Manager $wp_customize Customizer object
     */
    public function customize_register($wp_customize) {
        // Add section
        $wp_customize->add_section('aqualuxe_seo', [
            'title' => __('SEO Settings', 'aqualuxe'),
            'priority' => 130,
        ]);
        
        // Add settings
        $wp_customize->add_setting('aqualuxe_seo_enabled', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_schema_markup', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_open_graph', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_twitter_cards', [
            'default' => true,
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ]);
        
        $wp_customize->add_setting('aqualuxe_twitter_username', [
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_facebook_app_id', [
            'default' => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_default_social_image', [
            'default' => get_template_directory_uri() . '/assets/images/social-default.jpg',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        
        $wp_customize->add_setting('aqualuxe_organization_type', [
            'default' => 'Organization',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        
        $wp_customize->add_setting('aqualuxe_organization_logo', [
            'default' => get_template_directory_uri() . '/assets/images/logo.png',
            'sanitize_callback' => 'esc_url_raw',
        ]);
        
        // Add controls
        $wp_customize->add_control('aqualuxe_seo_enabled', [
            'label' => __('Enable SEO Features', 'aqualuxe'),
            'section' => 'aqualuxe_seo',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_schema_markup', [
            'label' => __('Enable Schema.org Markup', 'aqualuxe'),
            'section' => 'aqualuxe_seo',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_open_graph', [
            'label' => __('Enable Open Graph Tags', 'aqualuxe'),
            'section' => 'aqualuxe_seo',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_twitter_cards', [
            'label' => __('Enable Twitter Cards', 'aqualuxe'),
            'section' => 'aqualuxe_seo',
            'type' => 'checkbox',
        ]);
        
        $wp_customize->add_control('aqualuxe_twitter_username', [
            'label' => __('Twitter Username (without @)', 'aqualuxe'),
            'section' => 'aqualuxe_seo',
            'type' => 'text',
        ]);
        
        $wp_customize->add_control('aqualuxe_facebook_app_id', [
            'label' => __('Facebook App ID', 'aqualuxe'),
            'section' => 'aqualuxe_seo',
            'type' => 'text',
        ]);
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_default_social_image', [
            'label' => __('Default Social Image', 'aqualuxe'),
            'description' => __('Used when no featured image is available', 'aqualuxe'),
            'section' => 'aqualuxe_seo',
            'settings' => 'aqualuxe_default_social_image',
        ]));
        
        $wp_customize->add_control('aqualuxe_organization_type', [
            'label' => __('Organization Type', 'aqualuxe'),
            'section' => 'aqualuxe_seo',
            'type' => 'select',
            'choices' => [
                'Organization' => __('Organization', 'aqualuxe'),
                'LocalBusiness' => __('Local Business', 'aqualuxe'),
                'Corporation' => __('Corporation', 'aqualuxe'),
                'Store' => __('Store', 'aqualuxe'),
                'Restaurant' => __('Restaurant', 'aqualuxe'),
                'Hotel' => __('Hotel', 'aqualuxe'),
            ],
        ]);
        
        $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'aqualuxe_organization_logo', [
            'label' => __('Organization Logo', 'aqualuxe'),
            'description' => __('Used in schema.org markup', 'aqualuxe'),
            'section' => 'aqualuxe_seo',
            'settings' => 'aqualuxe_organization_logo',
        ]));
    }

    /**
     * Admin init
     */
    public function admin_init() {
        // Register settings
        register_setting('aqualuxe_seo_settings', 'aqualuxe_seo_settings', [
            'sanitize_callback' => [$this, 'sanitize_settings'],
        ]);
        
        // Add settings section
        add_settings_section(
            'aqualuxe_seo_section',
            __('SEO Settings', 'aqualuxe'),
            [$this, 'settings_section_callback'],
            'aqualuxe_seo'
        );
        
        // Add settings fields
        add_settings_field(
            'aqualuxe_seo_enabled',
            __('Enable SEO Features', 'aqualuxe'),
            [$this, 'enabled_field_callback'],
            'aqualuxe_seo',
            'aqualuxe_seo_section'
        );
        
        add_settings_field(
            'aqualuxe_schema_markup',
            __('Enable Schema.org Markup', 'aqualuxe'),
            [$this, 'schema_field_callback'],
            'aqualuxe_seo',
            'aqualuxe_seo_section'
        );
        
        add_settings_field(
            'aqualuxe_open_graph',
            __('Enable Open Graph Tags', 'aqualuxe'),
            [$this, 'open_graph_field_callback'],
            'aqualuxe_seo',
            'aqualuxe_seo_section'
        );
        
        add_settings_field(
            'aqualuxe_twitter_cards',
            __('Enable Twitter Cards', 'aqualuxe'),
            [$this, 'twitter_cards_field_callback'],
            'aqualuxe_seo',
            'aqualuxe_seo_section'
        );
        
        add_settings_field(
            'aqualuxe_social_profiles',
            __('Social Profiles', 'aqualuxe'),
            [$this, 'social_profiles_field_callback'],
            'aqualuxe_seo',
            'aqualuxe_seo_section'
        );
    }

    /**
     * Admin menu
     */
    public function admin_menu() {
        add_submenu_page(
            'themes.php',
            __('SEO Settings', 'aqualuxe'),
            __('SEO Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe_seo',
            [$this, 'settings_page']
        );
    }

    /**
     * Settings section callback
     */
    public function settings_section_callback() {
        echo '<p>' . esc_html__('Configure SEO settings for your site.', 'aqualuxe') . '</p>';
    }

    /**
     * Enabled field callback
     */
    public function enabled_field_callback() {
        $settings = get_option('aqualuxe_seo_settings', []);
        $enabled = isset($settings['enabled']) ? $settings['enabled'] : true;
        
        echo '<input type="checkbox" name="aqualuxe_seo_settings[enabled]" value="1"' . checked($enabled, true, false) . '>';
    }

    /**
     * Schema field callback
     */
    public function schema_field_callback() {
        $settings = get_option('aqualuxe_seo_settings', []);
        $schema = isset($settings['schema_markup']) ? $settings['schema_markup'] : true;
        
        echo '<input type="checkbox" name="aqualuxe_seo_settings[schema_markup]" value="1"' . checked($schema, true, false) . '>';
    }

    /**
     * Open Graph field callback
     */
    public function open_graph_field_callback() {
        $settings = get_option('aqualuxe_seo_settings', []);
        $open_graph = isset($settings['open_graph']) ? $settings['open_graph'] : true;
        
        echo '<input type="checkbox" name="aqualuxe_seo_settings[open_graph]" value="1"' . checked($open_graph, true, false) . '>';
    }

    /**
     * Twitter Cards field callback
     */
    public function twitter_cards_field_callback() {
        $settings = get_option('aqualuxe_seo_settings', []);
        $twitter_cards = isset($settings['twitter_cards']) ? $settings['twitter_cards'] : true;
        
        echo '<input type="checkbox" name="aqualuxe_seo_settings[twitter_cards]" value="1"' . checked($twitter_cards, true, false) . '>';
    }

    /**
     * Social profiles field callback
     */
    public function social_profiles_field_callback() {
        $settings = get_option('aqualuxe_seo_settings', []);
        $social_profiles = isset($settings['social_profiles']) ? $settings['social_profiles'] : [];
        
        $networks = [
            'facebook' => __('Facebook', 'aqualuxe'),
            'twitter' => __('Twitter', 'aqualuxe'),
            'instagram' => __('Instagram', 'aqualuxe'),
            'linkedin' => __('LinkedIn', 'aqualuxe'),
            'youtube' => __('YouTube', 'aqualuxe'),
            'pinterest' => __('Pinterest', 'aqualuxe'),
        ];
        
        echo '<table class="form-table">';
        foreach ($networks as $network => $label) {
            $value = isset($social_profiles[$network]) ? $social_profiles[$network] : '';
            
            echo '<tr>';
            echo '<th>' . esc_html($label) . '</th>';
            echo '<td><input type="url" name="aqualuxe_seo_settings[social_profiles][' . esc_attr($network) . ']" value="' . esc_url($value) . '" class="regular-text"></td>';
            echo '</tr>';
        }
        echo '</table>';
    }

    /**
     * Settings page
     */
    public function settings_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Show settings form
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields('aqualuxe_seo_settings');
                do_settings_sections('aqualuxe_seo');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Sanitize settings
     *
     * @param array $input Settings input
     * @return array
     */
    public function sanitize_settings($input) {
        $output = [];
        
        // Sanitize enabled
        $output['enabled'] = isset($input['enabled']) ? (bool) $input['enabled'] : false;
        
        // Sanitize schema_markup
        $output['schema_markup'] = isset($input['schema_markup']) ? (bool) $input['schema_markup'] : false;
        
        // Sanitize open_graph
        $output['open_graph'] = isset($input['open_graph']) ? (bool) $input['open_graph'] : false;
        
        // Sanitize twitter_cards
        $output['twitter_cards'] = isset($input['twitter_cards']) ? (bool) $input['twitter_cards'] : false;
        
        // Sanitize social_profiles
        if (isset($input['social_profiles']) && is_array($input['social_profiles'])) {
            foreach ($input['social_profiles'] as $network => $url) {
                $output['social_profiles'][$network] = esc_url_raw($url);
            }
        }
        
        return $output;
    }
}

// Initialize the module
AquaLuxe_SEO_Module::instance();