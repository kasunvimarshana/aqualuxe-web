<?php
/**
 * Note: This file uses WordPress core functions (wp_enqueue_style, get_template_directory_uri, etc.)
 * which are available at runtime in the WordPress environment. Lint errors in static analysis can be ignored.
 */
/**
 * AquaLuxe Portfolio Module
 * Modular portfolio/gallery feature
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class AquaLuxe_Portfolio {
    /**
     * Add meta box for portfolio gallery images
     */
    public static function add_gallery_metabox() {
        add_meta_box(
            'aqualuxe_portfolio_gallery',
            __('Portfolio Gallery', 'aqualuxe'),
            [__CLASS__, 'render_gallery_metabox'],
            'aqualuxe_portfolio',
            'normal',
            'default'
        );
    }

    /**
     * Render the gallery meta box UI
     */
    public static function render_gallery_metabox($post) {
        $gallery = get_post_meta($post->ID, 'portfolio_gallery', true);
        if (!is_array($gallery)) $gallery = array();
        wp_nonce_field('aqualuxe_portfolio_gallery', 'aqualuxe_portfolio_gallery_nonce');
        echo '<div id="aqualuxe-portfolio-gallery-metabox">';
        echo '<div class="portfolio-gallery-preview">';
        foreach ($gallery as $img_id) {
            $img_url = wp_get_attachment_image_url($img_id, 'thumbnail');
            if ($img_url) {
                echo '<div class="portfolio-gallery-thumb" data-img-id="' . esc_attr($img_id) . '"><img src="' . esc_url($img_url) . '" /><span class="remove-gallery-image">&times;</span></div>';
            }
        }
        echo '</div>';
        echo '<button type="button" class="button add-portfolio-gallery-image">' . __('Add Images', 'aqualuxe') . '</button>';
        echo '<input type="hidden" name="portfolio_gallery" id="portfolio_gallery_input" value="' . esc_attr(implode(',', $gallery)) . '" />';
        echo '</div>';
    }

    /**
     * Save the gallery meta box data
     */
    public static function save_gallery_metabox($post_id) {
        if (!isset($_POST['aqualuxe_portfolio_gallery_nonce']) || !wp_verify_nonce($_POST['aqualuxe_portfolio_gallery_nonce'], 'aqualuxe_portfolio_gallery')) return;
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
        if (isset($_POST['post_type']) && $_POST['post_type'] === 'aqualuxe_portfolio') {
            if (!current_user_can('edit_post', $post_id)) return;
        }
        if (isset($_POST['portfolio_gallery'])) {
            $ids = array_filter(array_map('absint', explode(',', $_POST['portfolio_gallery'])));
            update_post_meta($post_id, 'portfolio_gallery', $ids);
        }
    }
    /**
     * Enqueue admin assets for portfolio gallery meta box
     */
    public static function enqueue_admin_assets($hook) {
        global $post;
        if (
            $hook === 'post-new.php' || $hook === 'post.php'
        ) {
            if (isset($post) && $post->post_type === 'aqualuxe_portfolio') {
                wp_enqueue_style(
                    'aqualuxe-portfolio-css',
                    get_template_directory_uri() . '/modules/portfolio/assets/portfolio.css',
                    array(),
                    '1.0.0'
                );
                wp_enqueue_script(
                    'aqualuxe-portfolio-admin-js',
                    get_template_directory_uri() . '/modules/portfolio/assets/portfolio-admin.js',
                    array('jquery'),
                    '1.0.0',
                    true
                );
            }
        }
    }
    public static function init() {
        add_action( 'init', [ __CLASS__, 'register_cpt' ] );
        add_action( 'init', [ __CLASS__, 'register_taxonomies' ] );
        add_filter( 'template_include', [ __CLASS__, 'template_loader' ] );
        add_action( 'wp_enqueue_scripts', [ __CLASS__, 'enqueue_assets' ] );
    add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue_admin_assets' ] );
    add_action( 'add_meta_boxes', [ __CLASS__, 'add_gallery_metabox' ] );
    add_action( 'save_post', [ __CLASS__, 'save_gallery_metabox' ] );
    }

    public static function register_cpt() {
        $labels = [
            'name' => __( 'Portfolio', 'aqualuxe' ),
            'singular_name' => __( 'Portfolio Item', 'aqualuxe' ),
            'add_new' => __( 'Add New', 'aqualuxe' ),
            'add_new_item' => __( 'Add New Portfolio Item', 'aqualuxe' ),
            'edit_item' => __( 'Edit Portfolio Item', 'aqualuxe' ),
            'new_item' => __( 'New Portfolio Item', 'aqualuxe' ),
            'view_item' => __( 'View Portfolio Item', 'aqualuxe' ),
            'search_items' => __( 'Search Portfolio', 'aqualuxe' ),
            'not_found' => __( 'No portfolio items found', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No portfolio items found in Trash', 'aqualuxe' ),
        ];
        $args = [
            'labels' => $labels,
            'public' => true,
            'has_archive' => true,
            'rewrite' => [ 'slug' => 'portfolio' ],
            'supports' => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ],
            'show_in_rest' => true,
        ];
        register_post_type( 'aqualuxe_portfolio', $args );
    }

    public static function register_taxonomies() {
        register_taxonomy( 'portfolio_category', 'aqualuxe_portfolio', [
            'label' => __( 'Categories', 'aqualuxe' ),
            'hierarchical' => true,
            'show_in_rest' => true,
        ] );
        register_taxonomy( 'portfolio_tag', 'aqualuxe_portfolio', [
            'label' => __( 'Tags', 'aqualuxe' ),
            'hierarchical' => false,
            'show_in_rest' => true,
        ] );
    }

    public static function template_loader( $template ) {
        if ( is_singular( 'aqualuxe_portfolio' ) ) {
            $custom = locate_template( 'modules/portfolio/single-portfolio.php' );
            if ( $custom ) return $custom;
        }
        if ( is_post_type_archive( 'aqualuxe_portfolio' ) ) {
            $custom = locate_template( 'modules/portfolio/archive-portfolio.php' );
            if ( $custom ) return $custom;
        }
        return $template;
    }

    public static function enqueue_assets() {
        // Always enqueue CSS for portfolio grid and lightbox (front-end and admin for gallery UI)
        wp_enqueue_style(
            'aqualuxe-portfolio-css',
            get_template_directory_uri() . '/modules/portfolio/assets/portfolio.css',
            array(),
            '1.0.0'
        );
        // Enqueue JS only on portfolio archive/single
        if ( is_singular( 'aqualuxe_portfolio' ) || is_post_type_archive( 'aqualuxe_portfolio' ) ) {
            wp_enqueue_script(
                'aqualuxe-portfolio-js',
                get_template_directory_uri() . '/modules/portfolio/assets/portfolio.js',
                array('jquery'),
                '1.0.0',
                true
            );
        }
    }
}

AquaLuxe_Portfolio::init();
