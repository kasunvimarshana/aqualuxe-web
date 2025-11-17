<?php
/**
 * Hook Manager Service
 *
 * Manages WordPress hooks and filters in a centralized way
 *
 * @package AquaLuxe\Core\Services
 * @version 1.0.0
 */

namespace AquaLuxe\Core\Services;

use AquaLuxe\Core\Interfaces\ServiceInterface;

/**
 * Hook Manager Class
 */
class HookManager implements ServiceInterface {
    
    /**
     * Registered hooks
     *
     * @var array
     */
    private $hooks = [];
    
    /**
     * Initialize hook manager
     *
     * @return void
     */
    public function init(): void {
        // Register core theme hooks
        $this->register_core_hooks();
        
        // Allow modules to register their hooks
        do_action('aqualuxe_register_hooks', $this);
    }
    
    /**
     * Get service name
     *
     * @return string
     */
    public function getName(): string {
        return 'HookManager';
    }
    
    /**
     * Register core theme hooks
     *
     * @return void
     */
    private function register_core_hooks(): void {
        // Performance optimizations
        $this->add_action('wp_head', [$this, 'add_theme_meta_tags'], 1);
        $this->add_action('wp_head', [$this, 'add_schema_markup'], 2);
        
        // Security enhancements
        $this->add_action('init', [$this, 'security_headers']);
        $this->add_action('wp_head', [$this, 'remove_version_info']);
        
        // WooCommerce hooks (if active)
        if (aqualuxe_is_woocommerce_active()) {
            $this->register_woocommerce_hooks();
        }
        
        // Custom hooks for theme
        $this->register_custom_hooks();
    }
    
    /**
     * Register WooCommerce specific hooks
     *
     * @return void
     */
    private function register_woocommerce_hooks(): void {
        // Remove WooCommerce styles (we'll use our own)
        $this->add_filter('woocommerce_enqueue_styles', '__return_empty_array');
        
        // Customize WooCommerce templates
        $this->add_action('after_setup_theme', [$this, 'woocommerce_setup']);
        
        // Product gallery
        $this->add_action('wp_enqueue_scripts', [$this, 'woocommerce_scripts'], 99);
        
        // Cart fragments for AJAX
        $this->add_filter('woocommerce_add_to_cart_fragments', [$this, 'cart_count_fragments']);
        
        // Customize checkout fields
        $this->add_filter('woocommerce_checkout_fields', [$this, 'customize_checkout_fields']);
        
        // Add custom product tabs
        $this->add_filter('woocommerce_product_tabs', [$this, 'custom_product_tabs']);
    }
    
    /**
     * Register custom theme hooks
     *
     * @return void
     */
    private function register_custom_hooks(): void {
        // Custom body classes
        $this->add_filter('body_class', [$this, 'custom_body_classes']);
        
        // Custom post classes
        $this->add_filter('post_class', [$this, 'custom_post_classes']);
        
        // Excerpt customization
        $this->add_filter('excerpt_length', [$this, 'custom_excerpt_length']);
        $this->add_filter('excerpt_more', [$this, 'custom_excerpt_more']);
        
        // Navigation customization
        $this->add_filter('wp_nav_menu_args', [$this, 'nav_menu_args']);
        $this->add_filter('nav_menu_css_class', [$this, 'nav_menu_css_classes'], 10, 2);
        
        // Comment form customization
        $this->add_filter('comment_form_defaults', [$this, 'custom_comment_form']);
        
        // Search form customization
        $this->add_filter('get_search_form', [$this, 'custom_search_form']);
        
        // Pagination customization
        $this->add_filter('paginate_links_output', [$this, 'custom_pagination']);
    }
    
    /**
     * Add action hook
     *
     * @param string $hook
     * @param callable $callback
     * @param int $priority
     * @param int $accepted_args
     * @return void
     */
    public function add_action($hook, $callback, $priority = 10, $accepted_args = 1): void {
        add_action($hook, $callback, $priority, $accepted_args);
        $this->hooks[] = ['type' => 'action', 'hook' => $hook, 'callback' => $callback, 'priority' => $priority];
    }
    
    /**
     * Add filter hook
     *
     * @param string $hook
     * @param callable $callback
     * @param int $priority
     * @param int $accepted_args
     * @return void
     */
    public function add_filter($hook, $callback, $priority = 10, $accepted_args = 1): void {
        add_filter($hook, $callback, $priority, $accepted_args);
        $this->hooks[] = ['type' => 'filter', 'hook' => $hook, 'callback' => $callback, 'priority' => $priority];
    }
    
    /**
     * Remove action hook
     *
     * @param string $hook
     * @param callable $callback
     * @param int $priority
     * @return bool
     */
    public function remove_action($hook, $callback, $priority = 10): bool {
        return remove_action($hook, $callback, $priority);
    }
    
    /**
     * Remove filter hook
     *
     * @param string $hook
     * @param callable $callback
     * @param int $priority
     * @return bool
     */
    public function remove_filter($hook, $callback, $priority = 10): bool {
        return remove_filter($hook, $callback, $priority);
    }
    
    /**
     * Add theme meta tags
     *
     * @return void
     */
    public function add_theme_meta_tags(): void {
        echo '<meta name="theme-color" content="#06a9d6">' . "\n";
        echo '<meta name="msapplication-TileColor" content="#06a9d6">' . "\n";
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">' . "\n";
    }
    
    /**
     * Add JSON-LD schema markup
     *
     * @return void
     */
    public function add_schema_markup(): void {
        if (is_front_page()) {
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'Organization',
                'name' => get_bloginfo('name'),
                'description' => get_bloginfo('description'),
                'url' => home_url(),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => get_theme_mod('custom_logo') ? wp_get_attachment_image_url(get_theme_mod('custom_logo'), 'full') : '',
                ],
            ];
            
            echo '<script type="application/ld+json">' . wp_json_encode($schema) . '</script>' . "\n";
        }
    }
    
    /**
     * Add security headers
     *
     * @return void
     */
    public function security_headers(): void {
        if (!is_admin()) {
            header('X-Content-Type-Options: nosniff');
            header('X-Frame-Options: SAMEORIGIN');
            header('X-XSS-Protection: 1; mode=block');
            header('Referrer-Policy: strict-origin-when-cross-origin');
        }
    }
    
    /**
     * Remove WordPress version info from head
     *
     * @return void
     */
    public function remove_version_info(): void {
        remove_action('wp_head', 'wp_generator');
    }
    
    /**
     * WooCommerce setup
     *
     * @return void
     */
    public function woocommerce_setup(): void {
        add_theme_support('woocommerce', [
            'thumbnail_image_width' => 350,
            'single_image_width' => 600,
            'product_grid' => [
                'default_rows' => 3,
                'min_rows' => 2,
                'max_rows' => 8,
                'default_columns' => 4,
                'min_columns' => 2,
                'max_columns' => 5,
            ],
        ]);
    }
    
    /**
     * Enqueue WooCommerce scripts
     *
     * @return void
     */
    public function woocommerce_scripts(): void {
        if (is_product()) {
            wp_enqueue_script('wc-single-product');
        }
    }
    
    /**
     * Cart count fragments for AJAX
     *
     * @param array $fragments
     * @return array
     */
    public function cart_count_fragments($fragments): array {
        $fragments['.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
        return $fragments;
    }
    
    /**
     * Customize checkout fields
     *
     * @param array $fields
     * @return array
     */
    public function customize_checkout_fields($fields): array {
        // Add custom CSS classes to checkout fields
        foreach ($fields['billing'] as $key => $field) {
            $fields['billing'][$key]['class'][] = 'form-field';
        }
        
        foreach ($fields['shipping'] as $key => $field) {
            $fields['shipping'][$key]['class'][] = 'form-field';
        }
        
        return $fields;
    }
    
    /**
     * Add custom product tabs
     *
     * @param array $tabs
     * @return array
     */
    public function custom_product_tabs($tabs): array {
        // Add care instructions tab for aquatic products
        $tabs['care_instructions'] = [
            'title' => __('Care Instructions', 'aqualuxe'),
            'priority' => 25,
            'callback' => function() {
                $care_instructions = get_post_meta(get_the_ID(), '_care_instructions', true);
                if ($care_instructions) {
                    echo '<div class="care-instructions">' . wp_kses_post($care_instructions) . '</div>';
                }
            },
        ];
        
        return $tabs;
    }
    
    /**
     * Custom body classes
     *
     * @param array $classes
     * @return array
     */
    public function custom_body_classes($classes): array {
        // Add theme classes
        $classes[] = 'aqualuxe-theme';
        
        // Add WooCommerce class if active
        if (aqualuxe_is_woocommerce_active()) {
            $classes[] = 'has-woocommerce';
        }
        
        // Add dark mode class if enabled
        if (get_theme_mod('dark_mode_enabled', false)) {
            $classes[] = 'dark-mode-available';
        }
        
        // Add page-specific classes
        if (is_page_template()) {
            $template = get_page_template_slug();
            $classes[] = 'page-template-' . sanitize_html_class(str_replace(['templates/', '.php'], '', $template));
        }
        
        return $classes;
    }
    
    /**
     * Custom post classes
     *
     * @param array $classes
     * @return array
     */
    public function custom_post_classes($classes): array {
        $classes[] = 'entry';
        
        if (has_post_thumbnail()) {
            $classes[] = 'has-thumbnail';
        }
        
        return $classes;
    }
    
    /**
     * Custom excerpt length
     *
     * @param int $length
     * @return int
     */
    public function custom_excerpt_length($length): int {
        return get_theme_mod('excerpt_length', 25);
    }
    
    /**
     * Custom excerpt more text
     *
     * @param string $more
     * @return string
     */
    public function custom_excerpt_more($more): string {
        return '&hellip; <a href="' . get_permalink() . '" class="read-more">' . __('Read More', 'aqualuxe') . '</a>';
    }
    
    /**
     * Navigation menu arguments
     *
     * @param array $args
     * @return array
     */
    public function nav_menu_args($args): array {
        if (isset($args['theme_location']) && $args['theme_location'] === 'primary') {
            $args['container_class'] = 'nav-container';
            $args['menu_class'] = 'nav-menu';
        }
        
        return $args;
    }
    
    /**
     * Navigation menu CSS classes
     *
     * @param array $classes
     * @param object $item
     * @return array
     */
    public function nav_menu_css_classes($classes, $item): array {
        $classes[] = 'nav-item';
        
        if (in_array('current-menu-item', $classes)) {
            $classes[] = 'active';
        }
        
        return $classes;
    }
    
    /**
     * Custom comment form
     *
     * @param array $defaults
     * @return array
     */
    public function custom_comment_form($defaults): array {
        $defaults['class_form'] = 'comment-form';
        $defaults['class_submit'] = 'btn btn-primary submit-comment';
        $defaults['title_reply'] = __('Leave a Comment', 'aqualuxe');
        
        return $defaults;
    }
    
    /**
     * Custom search form
     *
     * @param string $form
     * @return string
     */
    public function custom_search_form($form): string {
        $form = '<form role="search" method="get" class="search-form" action="' . home_url('/') . '">';
        $form .= '<div class="search-field-wrap">';
        $form .= '<input type="search" class="search-field" placeholder="' . esc_attr__('Search...', 'aqualuxe') . '" value="' . get_search_query() . '" name="s" />';
        $form .= '<button type="submit" class="search-submit">';
        $form .= '<span class="sr-only">' . __('Search', 'aqualuxe') . '</span>';
        $form .= '<svg class="search-icon" width="20" height="20" viewBox="0 0 24 24" fill="currentColor"><path d="M15.5 14h-.79l-.28-.27A6.471 6.471 0 0 0 16 9.5 6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>';
        $form .= '</button>';
        $form .= '</div>';
        $form .= '</form>';
        
        return $form;
    }
    
    /**
     * Custom pagination
     *
     * @param string $output
     * @return string
     */
    public function custom_pagination($output): string {
        // Add custom classes to pagination
        $output = str_replace('class="page-numbers', 'class="page-numbers btn', $output);
        $output = str_replace('class="page-numbers current', 'class="page-numbers btn btn-primary current', $output);
        
        return $output;
    }
    
    /**
     * Get registered hooks
     *
     * @return array
     */
    public function get_hooks(): array {
        return $this->hooks;
    }
}