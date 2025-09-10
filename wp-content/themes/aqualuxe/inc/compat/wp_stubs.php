<?php
// These lightweight stubs help static analysis in non-WordPress contexts (e.g., VS Code).
// When WordPress is loaded, these are ignored because the functions already exist.
if (! defined('ABSPATH')) { define('ABSPATH', __DIR__); }

// Common WP functions (no-op stubs for tooling; runtime WordPress overrides).
foreach ([
    'add_action','add_filter','do_action','apply_filters','load_theme_textdomain','register_nav_menus','register_sidebar',
    'add_theme_support','register_post_type','register_taxonomy','wp_enqueue_style','wp_enqueue_script','wp_localize_script',
    'wp_create_nonce','admin_url','wp_nonce_field','submit_button','esc_html__','esc_html_e','esc_attr','esc_html','wp_kses_post','wpautop',
    'shortcode_atts','add_shortcode','get_posts','has_custom_logo','the_custom_logo','home_url','esc_url','wp_head','wp_footer','body_class',
    'language_attributes','bloginfo','wp_nav_menu','have_posts','the_post','the_ID','post_class','the_permalink','the_title','the_excerpt',
    'the_posts_pagination','load_textdomain','register_block_type','wp_verify_nonce','wp_unslash','sanitize_text_field','sanitize_hex_color',
    'get_locale','get_bloginfo','get_theme_mod','is_admin','add_query_arg','get_template_directory','wp_insert_post','wp_set_object_terms',
    'update_post_meta','wp_delete_post','wp_get_upload_dir'
] as $fn) {
    if (! function_exists($fn)) {
        eval('function ' . $fn . '(...$args) { return $args[0] ?? null; }');
    }
}

if (! function_exists('__')) { function __($text, $domain = null) { return $text; } }
if (! function_exists('wp_die')) { function wp_die($message = '') { die($message); } }

// WooCommerce helpers stubs
if (! function_exists('wc_get_cart_url')) { function wc_get_cart_url() { return '/cart'; } }
if (! function_exists('WC')) {
    function WC() {
        return (object) [
            'cart' => new class {
                public function get_cart_contents_count() { return 0; }
            }
        ];
    }
}

// Customizer class stubs
if (! class_exists('WP_Customize_Manager')) { class WP_Customize_Manager {} }
if (! class_exists('WP_Customize_Color_Control')) { class WP_Customize_Color_Control { public function __construct(){} } }
