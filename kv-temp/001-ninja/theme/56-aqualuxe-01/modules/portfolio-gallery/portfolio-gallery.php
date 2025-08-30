<?php
/**
 * Portfolio Gallery Module
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Modules\PortfolioGallery;

use AquaLuxe\Core\Module;
use AquaLuxe\Core\ModuleInterface;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Portfolio Gallery Module class
 */
class PortfolioGalleryModule extends Module implements ModuleInterface {
    /**
     * Setup module properties
     *
     * @return void
     */
    protected function setup() {
        $this->id          = 'portfolio-gallery';
        $this->name        = __( 'Portfolio Gallery', 'aqualuxe' );
        $this->description = __( 'Display a filterable portfolio or gallery with images, titles, and descriptions.', 'aqualuxe' );
        $this->version     = '1.0.0';
        $this->dependencies = [];
        $this->settings     = [
            'title'           => __( 'Our Portfolio', 'aqualuxe' ),
            'subtitle'        => __( 'Recent Work', 'aqualuxe' ),
            'description'     => __( 'Explore our latest projects and see the quality of our work.', 'aqualuxe' ),
            'layout'          => 'grid',
            'style'           => 'default',
            'columns'         => 3,
            'show_filters'    => true,
            'show_title'      => true,
            'show_excerpt'    => true,
            'show_zoom'       => true,
            'animation'       => 'fade',
            'items_per_page'  => 9,
            'categories'      => [
                'all'       => __( 'All', 'aqualuxe' ),
                'web'       => __( 'Web Design', 'aqualuxe' ),
                'branding'  => __( 'Branding', 'aqualuxe' ),
                'print'     => __( 'Print', 'aqualuxe' ),
                'photography' => __( 'Photography', 'aqualuxe' ),
            ],
            'items'           => [
                [
                    'title'       => __( 'Modern Website Design', 'aqualuxe' ),
                    'excerpt'     => __( 'A clean and modern website design for a tech startup.', 'aqualuxe' ),
                    'image'       => '',
                    'category'    => 'web',
                    'link'        => '#',
                ],
                [
                    'title'       => __( 'Brand Identity Package', 'aqualuxe' ),
                    'excerpt'     => __( 'Complete brand identity design including logo, business cards, and stationery.', 'aqualuxe' ),
                    'image'       => '',
                    'category'    => 'branding',
                    'link'        => '#',
                ],
                [
                    'title'       => __( 'Product Catalog', 'aqualuxe' ),
                    'excerpt'     => __( 'Beautifully designed product catalog for a furniture company.', 'aqualuxe' ),
                    'image'       => '',
                    'category'    => 'print',
                    'link'        => '#',
                ],
                [
                    'title'       => __( 'Corporate Photography', 'aqualuxe' ),
                    'excerpt'     => __( 'Professional corporate photography for a financial services firm.', 'aqualuxe' ),
                    'image'       => '',
                    'category'    => 'photography',
                    'link'        => '#',
                ],
                [
                    'title'       => __( 'E-commerce Website', 'aqualuxe' ),
                    'excerpt'     => __( 'Custom e-commerce website with advanced filtering and product showcase.', 'aqualuxe' ),
                    'image'       => '',
                    'category'    => 'web',
                    'link'        => '#',
                ],
                [
                    'title'       => __( 'Logo Design', 'aqualuxe' ),
                    'excerpt'     => __( 'Modern and versatile logo design for a fitness brand.', 'aqualuxe' ),
                    'image'       => '',
                    'category'    => 'branding',
                    'link'        => '#',
                ],
                [
                    'title'       => __( 'Marketing Brochure', 'aqualuxe' ),
                    'excerpt'     => __( 'Tri-fold marketing brochure design for a real estate company.', 'aqualuxe' ),
                    'image'       => '',
                    'category'    => 'print',
                    'link'        => '#',
                ],
                [
                    'title'       => __( 'Product Photography', 'aqualuxe' ),
                    'excerpt'     => __( 'High-quality product photography for an e-commerce website.', 'aqualuxe' ),
                    'image'       => '',
                    'category'    => 'photography',
                    'link'        => '#',
                ],
                [
                    'title'       => __( 'Mobile App UI Design', 'aqualuxe' ),
                    'excerpt'     => __( 'User interface design for a mobile banking application.', 'aqualuxe' ),
                    'image'       => '',
                    'category'    => 'web',
                    'link'        => '#',
                ],
            ],
        ];
    }

    /**
     * Register module hooks
     *
     * @return void
     */
    protected function register_hooks() {
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_assets' ] );
        add_action( 'aqualuxe_portfolio_gallery', [ $this, 'render' ] );
        add_shortcode( 'aqualuxe_portfolio_gallery', [ $this, 'shortcode' ] );
    }

    /**
     * Initialize the module
     *
     * @return void
     */
    public function init() {
        // Nothing to initialize
    }

    /**
     * Enqueue module assets
     *
     * @return void
     */
    public function enqueue_assets() {
        $this->enqueue_styles();
        $this->enqueue_scripts();
    }

    /**
     * Render the module
     *
     * @param array $args Module arguments.
     * @return void
     */
    public function render( $args = [] ) {
        $defaults = $this->settings;
        $args = wp_parse_args( $args, $defaults );
        
        // Get module settings from theme mods if not provided in args
        foreach ( $defaults as $key => $default ) {
            if ( ! isset( $args[ $key ] ) || empty( $args[ $key ] ) ) {
                if ( $key === 'items' ) {
                    // For portfolio items, we'll use either custom post type or theme mods
                    if ( post_type_exists( 'portfolio' ) ) {
                        $items = $this->get_portfolio_items( $args['items_per_page'] );
                        if ( ! empty( $items ) ) {
                            $args['items'] = $items;
                        }
                    } else {
                        // Use theme mods for portfolio items
                        $item_count = get_theme_mod( 'aqualuxe_portfolio_items_count', count( $default ) );
                        $items = [];
                        
                        for ( $i = 1; $i <= $item_count; $i++ ) {
                            $item = [
                                'title'       => get_theme_mod( "aqualuxe_portfolio_item_{$i}_title", $default[$i-1]['title'] ?? '' ),
                                'excerpt'     => get_theme_mod( "aqualuxe_portfolio_item_{$i}_excerpt", $default[$i-1]['excerpt'] ?? '' ),
                                'image'       => get_theme_mod( "aqualuxe_portfolio_item_{$i}_image", $default[$i-1]['image'] ?? '' ),
                                'category'    => get_theme_mod( "aqualuxe_portfolio_item_{$i}_category", $default[$i-1]['category'] ?? '' ),
                                'link'        => get_theme_mod( "aqualuxe_portfolio_item_{$i}_link", $default[$i-1]['link'] ?? '' ),
                            ];
                            
                            $items[] = $item;
                        }
                        
                        $args['items'] = $items;
                    }
                } elseif ( $key === 'categories' ) {
                    // For categories, we'll use either taxonomy terms or theme mods
                    if ( taxonomy_exists( 'portfolio_category' ) ) {
                        $categories = $this->get_portfolio_categories();
                        if ( ! empty( $categories ) ) {
                            $args['categories'] = $categories;
                        }
                    } else {
                        // Use theme mods for categories
                        $category_count = get_theme_mod( 'aqualuxe_portfolio_categories_count', count( $default ) );
                        $categories = [];
                        
                        // Always include 'all' category
                        $categories['all'] = __( 'All', 'aqualuxe' );
                        
                        for ( $i = 1; $i < $category_count; $i++ ) {
                            $slug = get_theme_mod( "aqualuxe_portfolio_category_{$i}_slug", array_keys( $default )[$i] ?? '' );
                            $name = get_theme_mod( "aqualuxe_portfolio_category_{$i}_name", array_values( $default )[$i] ?? '' );
                            
                            if ( ! empty( $slug ) && ! empty( $name ) ) {
                                $categories[ $slug ] = $name;
                            }
                        }
                        
                        $args['categories'] = $categories;
                    }
                } else {
                    $theme_mod = get_theme_mod( 'aqualuxe_portfolio_' . $key, $default );
                    $args[ $key ] = $theme_mod;
                }
            }
        }
        
        $this->get_template_part( 'portfolio-gallery', $args );
    }

    /**
     * Get portfolio items from custom post type
     *
     * @param int $items_per_page Number of items to get.
     * @return array
     */
    private function get_portfolio_items( $items_per_page = 9 ) {
        $args = [
            'post_type'      => 'portfolio',
            'posts_per_page' => $items_per_page,
            'post_status'    => 'publish',
        ];
        
        $query = new \WP_Query( $args );
        $items = [];
        
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                
                $categories = get_the_terms( get_the_ID(), 'portfolio_category' );
                $category_slugs = [];
                
                if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                    foreach ( $categories as $category ) {
                        $category_slugs[] = $category->slug;
                    }
                }
                
                $items[] = [
                    'title'       => get_the_title(),
                    'excerpt'     => get_the_excerpt(),
                    'image'       => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
                    'category'    => implode( ' ', $category_slugs ),
                    'link'        => get_permalink(),
                ];
            }
            
            wp_reset_postdata();
        }
        
        return $items;
    }

    /**
     * Get portfolio categories from taxonomy
     *
     * @return array
     */
    private function get_portfolio_categories() {
        $terms = get_terms( [
            'taxonomy'   => 'portfolio_category',
            'hide_empty' => true,
        ] );
        
        $categories = [
            'all' => __( 'All', 'aqualuxe' ),
        ];
        
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $categories[ $term->slug ] = $term->name;
            }
        }
        
        return $categories;
    }

    /**
     * Shortcode handler
     *
     * @param array $atts Shortcode attributes.
     * @return string
     */
    public function shortcode( $atts ) {
        $atts = shortcode_atts( [
            'title'           => $this->settings['title'],
            'subtitle'        => $this->settings['subtitle'],
            'description'     => $this->settings['description'],
            'layout'          => $this->settings['layout'],
            'style'           => $this->settings['style'],
            'columns'         => $this->settings['columns'],
            'show_filters'    => $this->settings['show_filters'],
            'show_title'      => $this->settings['show_title'],
            'show_excerpt'    => $this->settings['show_excerpt'],
            'show_zoom'       => $this->settings['show_zoom'],
            'animation'       => $this->settings['animation'],
            'items_per_page'  => $this->settings['items_per_page'],
            'category'        => '', // Filter by specific category
            'post_ids'        => '', // Comma-separated list of portfolio post IDs
        ], $atts, 'aqualuxe_portfolio_gallery' );
        
        // Convert string boolean values to actual booleans
        $atts['show_filters'] = filter_var( $atts['show_filters'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_title'] = filter_var( $atts['show_title'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_excerpt'] = filter_var( $atts['show_excerpt'], FILTER_VALIDATE_BOOLEAN );
        $atts['show_zoom'] = filter_var( $atts['show_zoom'], FILTER_VALIDATE_BOOLEAN );
        
        // If post_ids is provided, get specific portfolio items
        if ( ! empty( $atts['post_ids'] ) && post_type_exists( 'portfolio' ) ) {
            $post_ids = explode( ',', $atts['post_ids'] );
            $items = [];
            
            foreach ( $post_ids as $post_id ) {
                $post_id = trim( $post_id );
                $post = get_post( $post_id );
                
                if ( $post && $post->post_type === 'portfolio' ) {
                    $categories = get_the_terms( $post_id, 'portfolio_category' );
                    $category_slugs = [];
                    
                    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                        foreach ( $categories as $category ) {
                            $category_slugs[] = $category->slug;
                        }
                    }
                    
                    $items[] = [
                        'title'       => get_the_title( $post_id ),
                        'excerpt'     => get_the_excerpt( $post_id ),
                        'image'       => get_the_post_thumbnail_url( $post_id, 'large' ),
                        'category'    => implode( ' ', $category_slugs ),
                        'link'        => get_permalink( $post_id ),
                    ];
                }
            }
            
            $atts['items'] = $items;
        }
        // If category is provided, filter by category
        elseif ( ! empty( $atts['category'] ) && post_type_exists( 'portfolio' ) ) {
            $args = [
                'post_type'      => 'portfolio',
                'posts_per_page' => $atts['items_per_page'],
                'post_status'    => 'publish',
                'tax_query'      => [
                    [
                        'taxonomy' => 'portfolio_category',
                        'field'    => 'slug',
                        'terms'    => $atts['category'],
                    ],
                ],
            ];
            
            $query = new \WP_Query( $args );
            $items = [];
            
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    
                    $categories = get_the_terms( get_the_ID(), 'portfolio_category' );
                    $category_slugs = [];
                    
                    if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                        foreach ( $categories as $category ) {
                            $category_slugs[] = $category->slug;
                        }
                    }
                    
                    $items[] = [
                        'title'       => get_the_title(),
                        'excerpt'     => get_the_excerpt(),
                        'image'       => get_the_post_thumbnail_url( get_the_ID(), 'large' ),
                        'category'    => implode( ' ', $category_slugs ),
                        'link'        => get_permalink(),
                    ];
                }
                
                wp_reset_postdata();
            }
            
            $atts['items'] = $items;
        }
        
        ob_start();
        $this->render( $atts );
        return ob_get_clean();
    }

    /**
     * Register module customizer settings
     *
     * @param \WP_Customize_Manager $wp_customize Customizer instance.
     * @return void
     */
    public function register_customizer_settings( $wp_customize ) {
        // Add Portfolio Gallery section
        $wp_customize->add_section( 'aqualuxe_portfolio_gallery', [
            'title'       => __( 'Portfolio Gallery', 'aqualuxe' ),
            'description' => __( 'Customize the portfolio gallery module', 'aqualuxe' ),
            'panel'       => 'aqualuxe_modules',
            'priority'    => 60,
        ] );
        
        // Content Settings
        $wp_customize->add_setting( 'aqualuxe_portfolio_title', [
            'default'           => $this->settings['title'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_title', [
            'label'       => __( 'Title', 'aqualuxe' ),
            'description' => __( 'Enter the portfolio gallery section title', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_portfolio_subtitle', [
            'default'           => $this->settings['subtitle'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_subtitle', [
            'label'       => __( 'Subtitle', 'aqualuxe' ),
            'description' => __( 'Enter the portfolio gallery section subtitle', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'text',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_portfolio_description', [
            'default'           => $this->settings['description'],
            'sanitize_callback' => 'wp_kses_post',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_description', [
            'label'       => __( 'Description', 'aqualuxe' ),
            'description' => __( 'Enter the portfolio gallery section description', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'textarea',
        ] );
        
        // Layout Settings
        $wp_customize->add_setting( 'aqualuxe_portfolio_layout', [
            'default'           => $this->settings['layout'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_layout', [
            'label'       => __( 'Layout', 'aqualuxe' ),
            'description' => __( 'Select the layout style', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'select',
            'choices'     => [
                'grid'    => __( 'Grid', 'aqualuxe' ),
                'masonry' => __( 'Masonry', 'aqualuxe' ),
                'carousel' => __( 'Carousel', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_portfolio_style', [
            'default'           => $this->settings['style'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_style', [
            'label'       => __( 'Style', 'aqualuxe' ),
            'description' => __( 'Select the visual style', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'select',
            'choices'     => [
                'default'  => __( 'Default', 'aqualuxe' ),
                'overlay'  => __( 'Overlay', 'aqualuxe' ),
                'minimal'  => __( 'Minimal', 'aqualuxe' ),
                'bordered' => __( 'Bordered', 'aqualuxe' ),
                'shadow'   => __( 'Shadow', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_portfolio_columns', [
            'default'           => $this->settings['columns'],
            'sanitize_callback' => 'absint',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_columns', [
            'label'       => __( 'Columns', 'aqualuxe' ),
            'description' => __( 'Select the number of columns', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'select',
            'choices'     => [
                1 => __( '1 Column', 'aqualuxe' ),
                2 => __( '2 Columns', 'aqualuxe' ),
                3 => __( '3 Columns', 'aqualuxe' ),
                4 => __( '4 Columns', 'aqualuxe' ),
            ],
        ] );
        
        // Display Settings
        $wp_customize->add_setting( 'aqualuxe_portfolio_show_filters', [
            'default'           => $this->settings['show_filters'],
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_show_filters', [
            'label'       => __( 'Show Filters', 'aqualuxe' ),
            'description' => __( 'Display category filters', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'checkbox',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_portfolio_show_title', [
            'default'           => $this->settings['show_title'],
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_show_title', [
            'label'       => __( 'Show Title', 'aqualuxe' ),
            'description' => __( 'Display item titles', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'checkbox',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_portfolio_show_excerpt', [
            'default'           => $this->settings['show_excerpt'],
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_show_excerpt', [
            'label'       => __( 'Show Excerpt', 'aqualuxe' ),
            'description' => __( 'Display item excerpts', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'checkbox',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_portfolio_show_zoom', [
            'default'           => $this->settings['show_zoom'],
            'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_show_zoom', [
            'label'       => __( 'Show Zoom', 'aqualuxe' ),
            'description' => __( 'Display zoom icon for lightbox', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'checkbox',
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_portfolio_animation', [
            'default'           => $this->settings['animation'],
            'sanitize_callback' => 'sanitize_text_field',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_animation', [
            'label'       => __( 'Animation', 'aqualuxe' ),
            'description' => __( 'Select the animation effect', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'select',
            'choices'     => [
                'none'   => __( 'None', 'aqualuxe' ),
                'fade'   => __( 'Fade', 'aqualuxe' ),
                'slide'  => __( 'Slide', 'aqualuxe' ),
                'zoom'   => __( 'Zoom', 'aqualuxe' ),
                'flip'   => __( 'Flip', 'aqualuxe' ),
            ],
        ] );
        
        $wp_customize->add_setting( 'aqualuxe_portfolio_items_per_page', [
            'default'           => $this->settings['items_per_page'],
            'sanitize_callback' => 'absint',
        ] );
        
        $wp_customize->add_control( 'aqualuxe_portfolio_items_per_page', [
            'label'       => __( 'Items Per Page', 'aqualuxe' ),
            'description' => __( 'Number of items to display', 'aqualuxe' ),
            'section'     => 'aqualuxe_portfolio_gallery',
            'type'        => 'number',
            'input_attrs' => [
                'min'  => 1,
                'max'  => 24,
                'step' => 1,
            ],
        ] );
        
        // If no portfolio custom post type, add portfolio items settings
        if ( ! post_type_exists( 'portfolio' ) ) {
            // Portfolio Categories
            $wp_customize->add_setting( 'aqualuxe_portfolio_categories_count', [
                'default'           => count( $this->settings['categories'] ),
                'sanitize_callback' => 'absint',
            ] );
            
            $wp_customize->add_control( 'aqualuxe_portfolio_categories_count', [
                'label'       => __( 'Number of Categories', 'aqualuxe' ),
                'description' => __( 'Select the number of categories', 'aqualuxe' ),
                'section'     => 'aqualuxe_portfolio_gallery',
                'type'        => 'number',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 10,
                    'step' => 1,
                ],
            ] );
            
            $category_count = get_theme_mod( 'aqualuxe_portfolio_categories_count', count( $this->settings['categories'] ) );
            
            // Always include 'all' category
            $wp_customize->add_setting( 'aqualuxe_portfolio_category_0_slug', [
                'default'           => 'all',
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            
            $wp_customize->add_control( 'aqualuxe_portfolio_category_0_slug', [
                'label'       => __( 'All Category Slug', 'aqualuxe' ),
                'description' => __( 'Slug for the "All" category (cannot be changed)', 'aqualuxe' ),
                'section'     => 'aqualuxe_portfolio_gallery',
                'type'        => 'hidden',
            ] );
            
            $wp_customize->add_setting( 'aqualuxe_portfolio_category_0_name', [
                'default'           => __( 'All', 'aqualuxe' ),
                'sanitize_callback' => 'sanitize_text_field',
            ] );
            
            $wp_customize->add_control( 'aqualuxe_portfolio_category_0_name', [
                'label'       => __( 'All Category Name', 'aqualuxe' ),
                'description' => __( 'Name for the "All" category', 'aqualuxe' ),
                'section'     => 'aqualuxe_portfolio_gallery',
                'type'        => 'text',
            ] );
            
            // Add settings for each category
            for ( $i = 1; $i < $category_count; $i++ ) {
                $default_slug = array_keys( $this->settings['categories'] )[$i] ?? '';
                $default_name = array_values( $this->settings['categories'] )[$i] ?? '';
                
                $wp_customize->add_setting( "aqualuxe_portfolio_category_{$i}_slug", [
                    'default'           => $default_slug,
                    'sanitize_callback' => 'sanitize_text_field',
                ] );
                
                $wp_customize->add_control( "aqualuxe_portfolio_category_{$i}_slug", [
                    'label'       => sprintf( __( 'Category %d Slug', 'aqualuxe' ), $i ),
                    'description' => __( 'Enter the category slug (lowercase, no spaces)', 'aqualuxe' ),
                    'section'     => 'aqualuxe_portfolio_gallery',
                    'type'        => 'text',
                ] );
                
                $wp_customize->add_setting( "aqualuxe_portfolio_category_{$i}_name", [
                    'default'           => $default_name,
                    'sanitize_callback' => 'sanitize_text_field',
                ] );
                
                $wp_customize->add_control( "aqualuxe_portfolio_category_{$i}_name", [
                    'label'       => sprintf( __( 'Category %d Name', 'aqualuxe' ), $i ),
                    'description' => __( 'Enter the category name', 'aqualuxe' ),
                    'section'     => 'aqualuxe_portfolio_gallery',
                    'type'        => 'text',
                ] );
            }
            
            // Portfolio Items
            $wp_customize->add_setting( 'aqualuxe_portfolio_items_count', [
                'default'           => count( $this->settings['items'] ),
                'sanitize_callback' => 'absint',
            ] );
            
            $wp_customize->add_control( 'aqualuxe_portfolio_items_count', [
                'label'       => __( 'Number of Portfolio Items', 'aqualuxe' ),
                'description' => __( 'Select the number of portfolio items', 'aqualuxe' ),
                'section'     => 'aqualuxe_portfolio_gallery',
                'type'        => 'number',
                'input_attrs' => [
                    'min'  => 1,
                    'max'  => 24,
                    'step' => 1,
                ],
            ] );
            
            $item_count = get_theme_mod( 'aqualuxe_portfolio_items_count', count( $this->settings['items'] ) );
            
            // Add settings for each portfolio item
            for ( $i = 1; $i <= $item_count; $i++ ) {
                $default_item = isset( $this->settings['items'][$i-1] ) ? $this->settings['items'][$i-1] : [
                    'title'       => '',
                    'excerpt'     => '',
                    'image'       => '',
                    'category'    => '',
                    'link'        => '',
                ];
                
                $wp_customize->add_setting( "aqualuxe_portfolio_item_{$i}_title", [
                    'default'           => $default_item['title'],
                    'sanitize_callback' => 'sanitize_text_field',
                ] );
                
                $wp_customize->add_control( "aqualuxe_portfolio_item_{$i}_title", [
                    'label'       => sprintf( __( 'Item %d Title', 'aqualuxe' ), $i ),
                    'description' => __( 'Enter the portfolio item title', 'aqualuxe' ),
                    'section'     => 'aqualuxe_portfolio_gallery',
                    'type'        => 'text',
                ] );
                
                $wp_customize->add_setting( "aqualuxe_portfolio_item_{$i}_excerpt", [
                    'default'           => $default_item['excerpt'],
                    'sanitize_callback' => 'wp_kses_post',
                ] );
                
                $wp_customize->add_control( "aqualuxe_portfolio_item_{$i}_excerpt", [
                    'label'       => sprintf( __( 'Item %d Excerpt', 'aqualuxe' ), $i ),
                    'description' => __( 'Enter the portfolio item excerpt', 'aqualuxe' ),
                    'section'     => 'aqualuxe_portfolio_gallery',
                    'type'        => 'textarea',
                ] );
                
                $wp_customize->add_setting( "aqualuxe_portfolio_item_{$i}_image", [
                    'default'           => $default_item['image'],
                    'sanitize_callback' => 'esc_url_raw',
                ] );
                
                $wp_customize->add_control( new \WP_Customize_Image_Control(
                    $wp_customize,
                    "aqualuxe_portfolio_item_{$i}_image",
                    [
                        'label'       => sprintf( __( 'Item %d Image', 'aqualuxe' ), $i ),
                        'description' => __( 'Select the portfolio item image', 'aqualuxe' ),
                        'section'     => 'aqualuxe_portfolio_gallery',
                    ]
                ) );
                
                $wp_customize->add_setting( "aqualuxe_portfolio_item_{$i}_category", [
                    'default'           => $default_item['category'],
                    'sanitize_callback' => 'sanitize_text_field',
                ] );
                
                $wp_customize->add_control( "aqualuxe_portfolio_item_{$i}_category", [
                    'label'       => sprintf( __( 'Item %d Category', 'aqualuxe' ), $i ),
                    'description' => __( 'Enter the portfolio item category slug', 'aqualuxe' ),
                    'section'     => 'aqualuxe_portfolio_gallery',
                    'type'        => 'select',
                    'choices'     => array_combine( array_keys( $this->settings['categories'] ), array_keys( $this->settings['categories'] ) ),
                ] );
                
                $wp_customize->add_setting( "aqualuxe_portfolio_item_{$i}_link", [
                    'default'           => $default_item['link'],
                    'sanitize_callback' => 'esc_url_raw',
                ] );
                
                $wp_customize->add_control( "aqualuxe_portfolio_item_{$i}_link", [
                    'label'       => sprintf( __( 'Item %d Link', 'aqualuxe' ), $i ),
                    'description' => __( 'Enter the portfolio item link URL', 'aqualuxe' ),
                    'section'     => 'aqualuxe_portfolio_gallery',
                    'type'        => 'url',
                ] );
            }
        }
    }
}

// Initialize the module
return new PortfolioGalleryModule();