<?php
/**
 * AquaLuxe Template Helper Functions
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Get template part
 *
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array  $args Template arguments
 */
function aqualuxe_get_template_part($slug, $name = null, $args = []) {
    if ($args && is_array($args)) {
        extract($args);
    }
    
    $template = '';
    
    // Look in yourtheme/slug-name.php and yourtheme/templates/slug-name.php
    if ($name) {
        $template = locate_template(["$slug-$name.php", "templates/$slug-$name.php"]);
    }
    
    // Get default slug-name.php
    if (!$template && $name && file_exists(AQUALUXE_DIR . "templates/$slug-$name.php")) {
        $template = AQUALUXE_DIR . "templates/$slug-$name.php";
    }
    
    // If template file doesn't exist, look in yourtheme/slug.php and yourtheme/templates/slug.php
    if (!$template) {
        $template = locate_template(["$slug.php", "templates/$slug.php"]);
    }
    
    // Get default slug.php
    if (!$template && file_exists(AQUALUXE_DIR . "templates/$slug.php")) {
        $template = AQUALUXE_DIR . "templates/$slug.php";
    }
    
    // Allow 3rd party plugins to filter template file from their plugin
    $template = apply_filters('aqualuxe_get_template_part', $template, $slug, $name);
    
    if ($template) {
        include $template;
    }
}

/**
 * Get template
 *
 * @param string $template_name Template name
 * @param array  $args          Template arguments
 * @param string $template_path Template path
 * @param string $default_path  Default path
 */
function aqualuxe_get_template($template_name, $args = [], $template_path = '', $default_path = '') {
    if ($args && is_array($args)) {
        extract($args);
    }
    
    $located = aqualuxe_locate_template($template_name, $template_path, $default_path);
    
    if (!file_exists($located)) {
        /* translators: %s template */
        _doing_it_wrong(__FUNCTION__, sprintf(__('"%s" does not exist.', 'aqualuxe'), $located), '1.0.0');
        return;
    }
    
    // Allow 3rd party plugin filter template file from their plugin
    $located = apply_filters('aqualuxe_get_template', $located, $template_name, $args, $template_path, $default_path);
    
    do_action('aqualuxe_before_template_part', $template_name, $template_path, $located, $args);
    
    include $located;
    
    do_action('aqualuxe_after_template_part', $template_name, $template_path, $located, $args);
}

/**
 * Locate template
 *
 * @param string $template_name Template name
 * @param string $template_path Template path
 * @param string $default_path  Default path
 * @return string
 */
function aqualuxe_locate_template($template_name, $template_path = '', $default_path = '') {
    if (!$template_path) {
        $template_path = 'templates/';
    }
    
    if (!$default_path) {
        $default_path = AQUALUXE_DIR . 'templates/';
    }
    
    // Look within passed path within the theme - this is priority
    $template = locate_template([
        trailingslashit($template_path) . $template_name,
        $template_name,
    ]);
    
    // Get default template
    if (!$template) {
        $template = $default_path . $template_name;
    }
    
    // Return what we found
    return apply_filters('aqualuxe_locate_template', $template, $template_name, $template_path);
}

/**
 * Get header
 *
 * @param string $name Header name
 * @param array  $args Header arguments
 */
function aqualuxe_get_header($name = null, $args = []) {
    $args = apply_filters('aqualuxe_get_header_args', $args);
    
    if ($name) {
        $template = "header-{$name}.php";
    } else {
        $template = 'header.php';
    }
    
    $located = aqualuxe_locate_template($template);
    
    if (file_exists($located)) {
        load_template($located, true, $args);
    } else {
        get_header($name, $args);
    }
}

/**
 * Get footer
 *
 * @param string $name Footer name
 * @param array  $args Footer arguments
 */
function aqualuxe_get_footer($name = null, $args = []) {
    $args = apply_filters('aqualuxe_get_footer_args', $args);
    
    if ($name) {
        $template = "footer-{$name}.php";
    } else {
        $template = 'footer.php';
    }
    
    $located = aqualuxe_locate_template($template);
    
    if (file_exists($located)) {
        load_template($located, true, $args);
    } else {
        get_footer($name, $args);
    }
}

/**
 * Get sidebar
 *
 * @param string $name Sidebar name
 * @param array  $args Sidebar arguments
 */
function aqualuxe_get_sidebar($name = null, $args = []) {
    $args = apply_filters('aqualuxe_get_sidebar_args', $args);
    
    if ($name) {
        $template = "sidebar-{$name}.php";
    } else {
        $template = 'sidebar.php';
    }
    
    $located = aqualuxe_locate_template($template);
    
    if (file_exists($located)) {
        load_template($located, true, $args);
    } else {
        get_sidebar($name, $args);
    }
}

/**
 * Get template HTML
 *
 * @param string $template_name Template name
 * @param array  $args          Template arguments
 * @param string $template_path Template path
 * @param string $default_path  Default path
 * @return string
 */
function aqualuxe_get_template_html($template_name, $args = [], $template_path = '', $default_path = '') {
    ob_start();
    aqualuxe_get_template($template_name, $args, $template_path, $default_path);
    return ob_get_clean();
}

/**
 * Get page template
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_get_page_template($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $template = get_page_template_slug($post_id);
    
    if (!$template) {
        $template = get_post_meta($post_id, '_wp_page_template', true);
    }
    
    if (!$template) {
        $template = 'default';
    }
    
    return $template;
}

/**
 * Get page template name
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_get_page_template_name($post_id = null) {
    $template = aqualuxe_get_page_template($post_id);
    
    if ($template === 'default') {
        return __('Default Template', 'aqualuxe');
    }
    
    $templates = wp_get_theme()->get_page_templates();
    
    if (isset($templates[$template])) {
        return $templates[$template];
    }
    
    return $template;
}

/**
 * Get post template
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_get_post_template($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $template = get_post_meta($post_id, '_wp_page_template', true);
    
    if (!$template) {
        $template = 'default';
    }
    
    return $template;
}

/**
 * Get post template name
 *
 * @param int $post_id Post ID
 * @return string
 */
function aqualuxe_get_post_template_name($post_id = null) {
    $template = aqualuxe_get_post_template($post_id);
    
    if ($template === 'default') {
        return __('Default Template', 'aqualuxe');
    }
    
    $templates = wp_get_theme()->get_page_templates(get_post($post_id));
    
    if (isset($templates[$template])) {
        return $templates[$template];
    }
    
    return $template;
}

/**
 * Get archive template
 *
 * @return string
 */
function aqualuxe_get_archive_template() {
    $template = 'archive.php';
    
    if (is_post_type_archive()) {
        $post_type = get_query_var('post_type');
        
        if (is_array($post_type)) {
            $post_type = reset($post_type);
        }
        
        $template = "archive-{$post_type}.php";
    } elseif (is_category()) {
        $category = get_queried_object();
        
        if ($category) {
            $templates = [
                "category-{$category->slug}.php",
                "category-{$category->term_id}.php",
                'category.php',
            ];
            
            $template = locate_template($templates);
            
            if (!$template) {
                $template = 'category.php';
            }
        }
    } elseif (is_tag()) {
        $tag = get_queried_object();
        
        if ($tag) {
            $templates = [
                "tag-{$tag->slug}.php",
                "tag-{$tag->term_id}.php",
                'tag.php',
            ];
            
            $template = locate_template($templates);
            
            if (!$template) {
                $template = 'tag.php';
            }
        }
    } elseif (is_tax()) {
        $term = get_queried_object();
        
        if ($term) {
            $templates = [
                "taxonomy-{$term->taxonomy}-{$term->slug}.php",
                "taxonomy-{$term->taxonomy}.php",
                'taxonomy.php',
            ];
            
            $template = locate_template($templates);
            
            if (!$template) {
                $template = 'taxonomy.php';
            }
        }
    } elseif (is_author()) {
        $author = get_queried_object();
        
        if ($author) {
            $templates = [
                "author-{$author->user_nicename}.php",
                "author-{$author->ID}.php",
                'author.php',
            ];
            
            $template = locate_template($templates);
            
            if (!$template) {
                $template = 'author.php';
            }
        }
    } elseif (is_date()) {
        $template = 'date.php';
    }
    
    return $template;
}

/**
 * Get search template
 *
 * @return string
 */
function aqualuxe_get_search_template() {
    return 'search.php';
}

/**
 * Get 404 template
 *
 * @return string
 */
function aqualuxe_get_404_template() {
    return '404.php';
}

/**
 * Get attachment template
 *
 * @return string
 */
function aqualuxe_get_attachment_template() {
    $attachment = get_queried_object();
    
    if ($attachment) {
        $mime_type = get_post_mime_type($attachment);
        
        if ($mime_type) {
            $mime_parts = explode('/', $mime_type);
            
            if (isset($mime_parts[0]) && isset($mime_parts[1])) {
                $templates = [
                    "{$mime_parts[0]}-{$mime_parts[1]}.php",
                    "{$mime_parts[0]}.php",
                ];
                
                $template = locate_template($templates);
                
                if ($template) {
                    return $template;
                }
            }
        }
    }
    
    return 'attachment.php';
}

/**
 * Get embed template
 *
 * @return string
 */
function aqualuxe_get_embed_template() {
    $object = get_queried_object();
    
    if ($object) {
        $templates = [];
        
        if (is_a($object, 'WP_Post')) {
            $post_type = get_post_type($object);
            
            if ($post_type) {
                $templates[] = "embed-{$post_type}.php";
            }
        } elseif (is_a($object, 'WP_Term')) {
            $taxonomy = $object->taxonomy;
            
            if ($taxonomy) {
                $templates[] = "embed-{$taxonomy}.php";
            }
        }
        
        $templates[] = 'embed.php';
        
        $template = locate_template($templates);
        
        if ($template) {
            return $template;
        }
    }
    
    return 'embed.php';
}

/**
 * Get singular template
 *
 * @return string
 */
function aqualuxe_get_singular_template() {
    $object = get_queried_object();
    
    if (!$object) {
        return 'singular.php';
    }
    
    $templates = [];
    
    if (is_a($object, 'WP_Post')) {
        $post_type = get_post_type($object);
        
        if ($post_type) {
            $templates[] = "singular-{$post_type}.php";
        }
    }
    
    $templates[] = 'singular.php';
    
    $template = locate_template($templates);
    
    if ($template) {
        return $template;
    }
    
    return 'singular.php';
}

/**
 * Get WooCommerce template
 *
 * @param string $template_name Template name
 * @param array  $args          Template arguments
 * @param string $template_path Template path
 * @param string $default_path  Default path
 */
function aqualuxe_get_woocommerce_template($template_name, $args = [], $template_path = '', $default_path = '') {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    if (function_exists('wc_get_template')) {
        wc_get_template($template_name, $args, $template_path, $default_path);
    }
}

/**
 * Get WooCommerce template part
 *
 * @param string $slug Template slug
 * @param string $name Template name
 * @param array  $args Template arguments
 */
function aqualuxe_get_woocommerce_template_part($slug, $name = null, $args = []) {
    if (!aqualuxe_is_woocommerce_active()) {
        return;
    }
    
    if (function_exists('wc_get_template_part')) {
        wc_get_template_part($slug, $name, $args);
    }
}

/**
 * Get WooCommerce template HTML
 *
 * @param string $template_name Template name
 * @param array  $args          Template arguments
 * @param string $template_path Template path
 * @param string $default_path  Default path
 * @return string
 */
function aqualuxe_get_woocommerce_template_html($template_name, $args = [], $template_path = '', $default_path = '') {
    if (!aqualuxe_is_woocommerce_active()) {
        return '';
    }
    
    ob_start();
    aqualuxe_get_woocommerce_template($template_name, $args, $template_path, $default_path);
    return ob_get_clean();
}

/**
 * Get module template
 *
 * @param string $module_id     Module ID
 * @param string $template_name Template name
 * @param array  $args          Template arguments
 */
function aqualuxe_get_module_template($module_id, $template_name, $args = []) {
    if ($args && is_array($args)) {
        extract($args);
    }
    
    $template = '';
    
    // Look in yourtheme/modules/module-id/template-name.php
    $template = locate_template(["modules/{$module_id}/{$template_name}.php"]);
    
    // Get default modules/module-id/template-name.php
    if (!$template && file_exists(AQUALUXE_MODULES_DIR . "{$module_id}/templates/{$template_name}.php")) {
        $template = AQUALUXE_MODULES_DIR . "{$module_id}/templates/{$template_name}.php";
    }
    
    // Allow 3rd party plugins to filter template file from their plugin
    $template = apply_filters('aqualuxe_get_module_template', $template, $module_id, $template_name, $args);
    
    if ($template) {
        include $template;
    }
}

/**
 * Get module template HTML
 *
 * @param string $module_id     Module ID
 * @param string $template_name Template name
 * @param array  $args          Template arguments
 * @return string
 */
function aqualuxe_get_module_template_html($module_id, $template_name, $args = []) {
    ob_start();
    aqualuxe_get_module_template($module_id, $template_name, $args);
    return ob_get_clean();
}

/**
 * Get template ID
 *
 * @param string $template_type Template type
 * @param string $object_type   Object type
 * @return int
 */
function aqualuxe_get_template_id($template_type, $object_type = '') {
    $template_id = 0;
    
    // Get template from options
    $option_name = "aqualuxe_{$template_type}_template";
    
    if ($object_type) {
        $option_name .= "_{$object_type}";
    }
    
    $template_id = get_option($option_name, 0);
    
    return $template_id;
}

/**
 * Set template ID
 *
 * @param string $template_type Template type
 * @param string $object_type   Object type
 * @param int    $template_id   Template ID
 */
function aqualuxe_set_template_id($template_type, $object_type = '', $template_id = 0) {
    $option_name = "aqualuxe_{$template_type}_template";
    
    if ($object_type) {
        $option_name .= "_{$object_type}";
    }
    
    update_option($option_name, $template_id);
}

/**
 * Get header template ID
 *
 * @return int
 */
function aqualuxe_get_header_template_id() {
    return aqualuxe_get_template_id('header');
}

/**
 * Get footer template ID
 *
 * @return int
 */
function aqualuxe_get_footer_template_id() {
    return aqualuxe_get_template_id('footer');
}

/**
 * Get sidebar template ID
 *
 * @return int
 */
function aqualuxe_get_sidebar_template_id() {
    return aqualuxe_get_template_id('sidebar');
}

/**
 * Get single template ID
 *
 * @param string $post_type Post type
 * @return int
 */
function aqualuxe_get_single_template_id($post_type = '') {
    return aqualuxe_get_template_id('single', $post_type);
}

/**
 * Get archive template ID
 *
 * @param string $post_type Post type
 * @return int
 */
function aqualuxe_get_archive_template_id($post_type = '') {
    return aqualuxe_get_template_id('archive', $post_type);
}

/**
 * Get search template ID
 *
 * @return int
 */
function aqualuxe_get_search_template_id() {
    return aqualuxe_get_template_id('search');
}

/**
 * Get 404 template ID
 *
 * @return int
 */
function aqualuxe_get_404_template_id() {
    return aqualuxe_get_template_id('404');
}

/**
 * Get template content
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_content($template_id) {
    if (!$template_id) {
        return '';
    }
    
    $content = get_post_field('post_content', $template_id);
    
    if (!$content) {
        return '';
    }
    
    return $content;
}

/**
 * Get header template content
 *
 * @return string
 */
function aqualuxe_get_header_template_content() {
    $template_id = aqualuxe_get_header_template_id();
    return aqualuxe_get_template_content($template_id);
}

/**
 * Get footer template content
 *
 * @return string
 */
function aqualuxe_get_footer_template_content() {
    $template_id = aqualuxe_get_footer_template_id();
    return aqualuxe_get_template_content($template_id);
}

/**
 * Get sidebar template content
 *
 * @return string
 */
function aqualuxe_get_sidebar_template_content() {
    $template_id = aqualuxe_get_sidebar_template_id();
    return aqualuxe_get_template_content($template_id);
}

/**
 * Get single template content
 *
 * @param string $post_type Post type
 * @return string
 */
function aqualuxe_get_single_template_content($post_type = '') {
    $template_id = aqualuxe_get_single_template_id($post_type);
    return aqualuxe_get_template_content($template_id);
}

/**
 * Get archive template content
 *
 * @param string $post_type Post type
 * @return string
 */
function aqualuxe_get_archive_template_content($post_type = '') {
    $template_id = aqualuxe_get_archive_template_id($post_type);
    return aqualuxe_get_template_content($template_id);
}

/**
 * Get search template content
 *
 * @return string
 */
function aqualuxe_get_search_template_content() {
    $template_id = aqualuxe_get_search_template_id();
    return aqualuxe_get_template_content($template_id);
}

/**
 * Get 404 template content
 *
 * @return string
 */
function aqualuxe_get_404_template_content() {
    $template_id = aqualuxe_get_404_template_id();
    return aqualuxe_get_template_content($template_id);
}

/**
 * Get template meta
 *
 * @param int    $template_id Template ID
 * @param string $key         Meta key
 * @param bool   $single      Single
 * @return mixed
 */
function aqualuxe_get_template_meta($template_id, $key, $single = true) {
    if (!$template_id) {
        return '';
    }
    
    return get_post_meta($template_id, $key, $single);
}

/**
 * Set template meta
 *
 * @param int    $template_id Template ID
 * @param string $key         Meta key
 * @param mixed  $value       Meta value
 */
function aqualuxe_set_template_meta($template_id, $key, $value) {
    if (!$template_id) {
        return;
    }
    
    update_post_meta($template_id, $key, $value);
}

/**
 * Delete template meta
 *
 * @param int    $template_id Template ID
 * @param string $key         Meta key
 */
function aqualuxe_delete_template_meta($template_id, $key) {
    if (!$template_id) {
        return;
    }
    
    delete_post_meta($template_id, $key);
}

/**
 * Get template type
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_type($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_type');
}

/**
 * Get template subtype
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_subtype($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_subtype');
}

/**
 * Get template conditions
 *
 * @param int $template_id Template ID
 * @return array
 */
function aqualuxe_get_template_conditions($template_id) {
    if (!$template_id) {
        return [];
    }
    
    $conditions = aqualuxe_get_template_meta($template_id, '_aqualuxe_template_conditions');
    
    if (!$conditions) {
        return [];
    }
    
    if (is_string($conditions)) {
        $conditions = json_decode($conditions, true);
    }
    
    if (!is_array($conditions)) {
        return [];
    }
    
    return $conditions;
}

/**
 * Set template conditions
 *
 * @param int   $template_id Template ID
 * @param array $conditions  Conditions
 */
function aqualuxe_set_template_conditions($template_id, $conditions) {
    if (!$template_id) {
        return;
    }
    
    if (!is_array($conditions)) {
        $conditions = [];
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_conditions', wp_json_encode($conditions));
}

/**
 * Check if template has condition
 *
 * @param int    $template_id Template ID
 * @param string $condition   Condition
 * @return bool
 */
function aqualuxe_template_has_condition($template_id, $condition) {
    if (!$template_id) {
        return false;
    }
    
    $conditions = aqualuxe_get_template_conditions($template_id);
    
    if (!$conditions) {
        return false;
    }
    
    return in_array($condition, $conditions);
}

/**
 * Add template condition
 *
 * @param int    $template_id Template ID
 * @param string $condition   Condition
 */
function aqualuxe_add_template_condition($template_id, $condition) {
    if (!$template_id) {
        return;
    }
    
    $conditions = aqualuxe_get_template_conditions($template_id);
    
    if (!in_array($condition, $conditions)) {
        $conditions[] = $condition;
        aqualuxe_set_template_conditions($template_id, $conditions);
    }
}

/**
 * Remove template condition
 *
 * @param int    $template_id Template ID
 * @param string $condition   Condition
 */
function aqualuxe_remove_template_condition($template_id, $condition) {
    if (!$template_id) {
        return;
    }
    
    $conditions = aqualuxe_get_template_conditions($template_id);
    
    if (in_array($condition, $conditions)) {
        $conditions = array_diff($conditions, [$condition]);
        aqualuxe_set_template_conditions($template_id, $conditions);
    }
}

/**
 * Get template settings
 *
 * @param int $template_id Template ID
 * @return array
 */
function aqualuxe_get_template_settings($template_id) {
    if (!$template_id) {
        return [];
    }
    
    $settings = aqualuxe_get_template_meta($template_id, '_aqualuxe_template_settings');
    
    if (!$settings) {
        return [];
    }
    
    if (is_string($settings)) {
        $settings = json_decode($settings, true);
    }
    
    if (!is_array($settings)) {
        return [];
    }
    
    return $settings;
}

/**
 * Set template settings
 *
 * @param int   $template_id Template ID
 * @param array $settings    Settings
 */
function aqualuxe_set_template_settings($template_id, $settings) {
    if (!$template_id) {
        return;
    }
    
    if (!is_array($settings)) {
        $settings = [];
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_settings', wp_json_encode($settings));
}

/**
 * Get template setting
 *
 * @param int    $template_id Template ID
 * @param string $key         Setting key
 * @param mixed  $default     Default value
 * @return mixed
 */
function aqualuxe_get_template_setting($template_id, $key, $default = '') {
    if (!$template_id) {
        return $default;
    }
    
    $settings = aqualuxe_get_template_settings($template_id);
    
    if (isset($settings[$key])) {
        return $settings[$key];
    }
    
    return $default;
}

/**
 * Set template setting
 *
 * @param int    $template_id Template ID
 * @param string $key         Setting key
 * @param mixed  $value       Setting value
 */
function aqualuxe_set_template_setting($template_id, $key, $value) {
    if (!$template_id) {
        return;
    }
    
    $settings = aqualuxe_get_template_settings($template_id);
    $settings[$key] = $value;
    
    aqualuxe_set_template_settings($template_id, $settings);
}

/**
 * Delete template setting
 *
 * @param int    $template_id Template ID
 * @param string $key         Setting key
 */
function aqualuxe_delete_template_setting($template_id, $key) {
    if (!$template_id) {
        return;
    }
    
    $settings = aqualuxe_get_template_settings($template_id);
    
    if (isset($settings[$key])) {
        unset($settings[$key]);
        aqualuxe_set_template_settings($template_id, $settings);
    }
}

/**
 * Get template status
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_status($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return get_post_status($template_id);
}

/**
 * Set template status
 *
 * @param int    $template_id Template ID
 * @param string $status      Status
 */
function aqualuxe_set_template_status($template_id, $status) {
    if (!$template_id) {
        return;
    }
    
    wp_update_post([
        'ID' => $template_id,
        'post_status' => $status,
    ]);
}

/**
 * Get template title
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_title($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return get_the_title($template_id);
}

/**
 * Set template title
 *
 * @param int    $template_id Template ID
 * @param string $title       Title
 */
function aqualuxe_set_template_title($template_id, $title) {
    if (!$template_id) {
        return;
    }
    
    wp_update_post([
        'ID' => $template_id,
        'post_title' => $title,
    ]);
}

/**
 * Get template author
 *
 * @param int $template_id Template ID
 * @return int
 */
function aqualuxe_get_template_author($template_id) {
    if (!$template_id) {
        return 0;
    }
    
    $post = get_post($template_id);
    
    if (!$post) {
        return 0;
    }
    
    return $post->post_author;
}

/**
 * Set template author
 *
 * @param int $template_id Template ID
 * @param int $author_id   Author ID
 */
function aqualuxe_set_template_author($template_id, $author_id) {
    if (!$template_id) {
        return;
    }
    
    wp_update_post([
        'ID' => $template_id,
        'post_author' => $author_id,
    ]);
}

/**
 * Get template date
 *
 * @param int    $template_id Template ID
 * @param string $format      Date format
 * @return string
 */
function aqualuxe_get_template_date($template_id, $format = '') {
    if (!$template_id) {
        return '';
    }
    
    return get_the_date($format, $template_id);
}

/**
 * Get template modified date
 *
 * @param int    $template_id Template ID
 * @param string $format      Date format
 * @return string
 */
function aqualuxe_get_template_modified_date($template_id, $format = '') {
    if (!$template_id) {
        return '';
    }
    
    return get_the_modified_date($format, $template_id);
}

/**
 * Get template edit URL
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_edit_url($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return get_edit_post_link($template_id);
}

/**
 * Get template preview URL
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_preview_url($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return get_preview_post_link($template_id);
}

/**
 * Get template permalink
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_permalink($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return get_permalink($template_id);
}

/**
 * Get template thumbnail
 *
 * @param int    $template_id Template ID
 * @param string $size        Image size
 * @return string
 */
function aqualuxe_get_template_thumbnail($template_id, $size = 'thumbnail') {
    if (!$template_id) {
        return '';
    }
    
    if (has_post_thumbnail($template_id)) {
        return get_the_post_thumbnail($template_id, $size);
    }
    
    return '';
}

/**
 * Get template thumbnail URL
 *
 * @param int    $template_id Template ID
 * @param string $size        Image size
 * @return string
 */
function aqualuxe_get_template_thumbnail_url($template_id, $size = 'thumbnail') {
    if (!$template_id) {
        return '';
    }
    
    if (has_post_thumbnail($template_id)) {
        return get_the_post_thumbnail_url($template_id, $size);
    }
    
    return '';
}

/**
 * Get template thumbnail ID
 *
 * @param int $template_id Template ID
 * @return int
 */
function aqualuxe_get_template_thumbnail_id($template_id) {
    if (!$template_id) {
        return 0;
    }
    
    return get_post_thumbnail_id($template_id);
}

/**
 * Set template thumbnail
 *
 * @param int $template_id   Template ID
 * @param int $thumbnail_id  Thumbnail ID
 */
function aqualuxe_set_template_thumbnail($template_id, $thumbnail_id) {
    if (!$template_id) {
        return;
    }
    
    set_post_thumbnail($template_id, $thumbnail_id);
}

/**
 * Remove template thumbnail
 *
 * @param int $template_id Template ID
 */
function aqualuxe_remove_template_thumbnail($template_id) {
    if (!$template_id) {
        return;
    }
    
    delete_post_thumbnail($template_id);
}

/**
 * Get template CSS
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_css($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_css');
}

/**
 * Set template CSS
 *
 * @param int    $template_id Template ID
 * @param string $css         CSS
 */
function aqualuxe_set_template_css($template_id, $css) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_css', $css);
}

/**
 * Get template JS
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_js($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_js');
}

/**
 * Set template JS
 *
 * @param int    $template_id Template ID
 * @param string $js          JS
 */
function aqualuxe_set_template_js($template_id, $js) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_js', $js);
}

/**
 * Get template HTML
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_html($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_html');
}

/**
 * Set template HTML
 *
 * @param int    $template_id Template ID
 * @param string $html        HTML
 */
function aqualuxe_set_template_html($template_id, $html) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_html', $html);
}

/**
 * Get template data
 *
 * @param int $template_id Template ID
 * @return array
 */
function aqualuxe_get_template_data($template_id) {
    if (!$template_id) {
        return [];
    }
    
    $data = aqualuxe_get_template_meta($template_id, '_aqualuxe_template_data');
    
    if (!$data) {
        return [];
    }
    
    if (is_string($data)) {
        $data = json_decode($data, true);
    }
    
    if (!is_array($data)) {
        return [];
    }
    
    return $data;
}

/**
 * Set template data
 *
 * @param int   $template_id Template ID
 * @param array $data        Data
 */
function aqualuxe_set_template_data($template_id, $data) {
    if (!$template_id) {
        return;
    }
    
    if (!is_array($data)) {
        $data = [];
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_data', wp_json_encode($data));
}

/**
 * Get template data value
 *
 * @param int    $template_id Template ID
 * @param string $key         Data key
 * @param mixed  $default     Default value
 * @return mixed
 */
function aqualuxe_get_template_data_value($template_id, $key, $default = '') {
    if (!$template_id) {
        return $default;
    }
    
    $data = aqualuxe_get_template_data($template_id);
    
    if (isset($data[$key])) {
        return $data[$key];
    }
    
    return $default;
}

/**
 * Set template data value
 *
 * @param int    $template_id Template ID
 * @param string $key         Data key
 * @param mixed  $value       Data value
 */
function aqualuxe_set_template_data_value($template_id, $key, $value) {
    if (!$template_id) {
        return;
    }
    
    $data = aqualuxe_get_template_data($template_id);
    $data[$key] = $value;
    
    aqualuxe_set_template_data($template_id, $data);
}

/**
 * Delete template data value
 *
 * @param int    $template_id Template ID
 * @param string $key         Data key
 */
function aqualuxe_delete_template_data_value($template_id, $key) {
    if (!$template_id) {
        return;
    }
    
    $data = aqualuxe_get_template_data($template_id);
    
    if (isset($data[$key])) {
        unset($data[$key]);
        aqualuxe_set_template_data($template_id, $data);
    }
}

/**
 * Get template config
 *
 * @param int $template_id Template ID
 * @return array
 */
function aqualuxe_get_template_config($template_id) {
    if (!$template_id) {
        return [];
    }
    
    $config = aqualuxe_get_template_meta($template_id, '_aqualuxe_template_config');
    
    if (!$config) {
        return [];
    }
    
    if (is_string($config)) {
        $config = json_decode($config, true);
    }
    
    if (!is_array($config)) {
        return [];
    }
    
    return $config;
}

/**
 * Set template config
 *
 * @param int   $template_id Template ID
 * @param array $config      Config
 */
function aqualuxe_set_template_config($template_id, $config) {
    if (!$template_id) {
        return;
    }
    
    if (!is_array($config)) {
        $config = [];
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_config', wp_json_encode($config));
}

/**
 * Get template config value
 *
 * @param int    $template_id Template ID
 * @param string $key         Config key
 * @param mixed  $default     Default value
 * @return mixed
 */
function aqualuxe_get_template_config_value($template_id, $key, $default = '') {
    if (!$template_id) {
        return $default;
    }
    
    $config = aqualuxe_get_template_config($template_id);
    
    if (isset($config[$key])) {
        return $config[$key];
    }
    
    return $default;
}

/**
 * Set template config value
 *
 * @param int    $template_id Template ID
 * @param string $key         Config key
 * @param mixed  $value       Config value
 */
function aqualuxe_set_template_config_value($template_id, $key, $value) {
    if (!$template_id) {
        return;
    }
    
    $config = aqualuxe_get_template_config($template_id);
    $config[$key] = $value;
    
    aqualuxe_set_template_config($template_id, $config);
}

/**
 * Delete template config value
 *
 * @param int    $template_id Template ID
 * @param string $key         Config key
 */
function aqualuxe_delete_template_config_value($template_id, $key) {
    if (!$template_id) {
        return;
    }
    
    $config = aqualuxe_get_template_config($template_id);
    
    if (isset($config[$key])) {
        unset($config[$key]);
        aqualuxe_set_template_config($template_id, $config);
    }
}

/**
 * Get template dependencies
 *
 * @param int $template_id Template ID
 * @return array
 */
function aqualuxe_get_template_dependencies($template_id) {
    if (!$template_id) {
        return [];
    }
    
    $dependencies = aqualuxe_get_template_meta($template_id, '_aqualuxe_template_dependencies');
    
    if (!$dependencies) {
        return [];
    }
    
    if (is_string($dependencies)) {
        $dependencies = json_decode($dependencies, true);
    }
    
    if (!is_array($dependencies)) {
        return [];
    }
    
    return $dependencies;
}

/**
 * Set template dependencies
 *
 * @param int   $template_id  Template ID
 * @param array $dependencies Dependencies
 */
function aqualuxe_set_template_dependencies($template_id, $dependencies) {
    if (!$template_id) {
        return;
    }
    
    if (!is_array($dependencies)) {
        $dependencies = [];
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_dependencies', wp_json_encode($dependencies));
}

/**
 * Add template dependency
 *
 * @param int    $template_id Template ID
 * @param string $dependency  Dependency
 */
function aqualuxe_add_template_dependency($template_id, $dependency) {
    if (!$template_id) {
        return;
    }
    
    $dependencies = aqualuxe_get_template_dependencies($template_id);
    
    if (!in_array($dependency, $dependencies)) {
        $dependencies[] = $dependency;
        aqualuxe_set_template_dependencies($template_id, $dependencies);
    }
}

/**
 * Remove template dependency
 *
 * @param int    $template_id Template ID
 * @param string $dependency  Dependency
 */
function aqualuxe_remove_template_dependency($template_id, $dependency) {
    if (!$template_id) {
        return;
    }
    
    $dependencies = aqualuxe_get_template_dependencies($template_id);
    
    if (in_array($dependency, $dependencies)) {
        $dependencies = array_diff($dependencies, [$dependency]);
        aqualuxe_set_template_dependencies($template_id, $dependencies);
    }
}

/**
 * Check if template has dependency
 *
 * @param int    $template_id Template ID
 * @param string $dependency  Dependency
 * @return bool
 */
function aqualuxe_template_has_dependency($template_id, $dependency) {
    if (!$template_id) {
        return false;
    }
    
    $dependencies = aqualuxe_get_template_dependencies($template_id);
    
    return in_array($dependency, $dependencies);
}

/**
 * Get template version
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_version($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_version');
}

/**
 * Set template version
 *
 * @param int    $template_id Template ID
 * @param string $version     Version
 */
function aqualuxe_set_template_version($template_id, $version) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_version', $version);
}

/**
 * Get template author name
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_author_name($template_id) {
    if (!$template_id) {
        return '';
    }
    
    $author_id = aqualuxe_get_template_author($template_id);
    
    if (!$author_id) {
        return '';
    }
    
    $author = get_userdata($author_id);
    
    if (!$author) {
        return '';
    }
    
    return $author->display_name;
}

/**
 * Get template author URL
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_author_url($template_id) {
    if (!$template_id) {
        return '';
    }
    
    $author_id = aqualuxe_get_template_author($template_id);
    
    if (!$author_id) {
        return '';
    }
    
    return get_author_posts_url($author_id);
}

/**
 * Get template author avatar
 *
 * @param int $template_id Template ID
 * @param int $size        Avatar size
 * @return string
 */
function aqualuxe_get_template_author_avatar($template_id, $size = 96) {
    if (!$template_id) {
        return '';
    }
    
    $author_id = aqualuxe_get_template_author($template_id);
    
    if (!$author_id) {
        return '';
    }
    
    return get_avatar($author_id, $size);
}

/**
 * Get template author email
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_author_email($template_id) {
    if (!$template_id) {
        return '';
    }
    
    $author_id = aqualuxe_get_template_author($template_id);
    
    if (!$author_id) {
        return '';
    }
    
    $author = get_userdata($author_id);
    
    if (!$author) {
        return '';
    }
    
    return $author->user_email;
}

/**
 * Get template categories
 *
 * @param int $template_id Template ID
 * @return array
 */
function aqualuxe_get_template_categories($template_id) {
    if (!$template_id) {
        return [];
    }
    
    $categories = aqualuxe_get_template_meta($template_id, '_aqualuxe_template_categories');
    
    if (!$categories) {
        return [];
    }
    
    if (is_string($categories)) {
        $categories = json_decode($categories, true);
    }
    
    if (!is_array($categories)) {
        return [];
    }
    
    return $categories;
}

/**
 * Set template categories
 *
 * @param int   $template_id Template ID
 * @param array $categories  Categories
 */
function aqualuxe_set_template_categories($template_id, $categories) {
    if (!$template_id) {
        return;
    }
    
    if (!is_array($categories)) {
        $categories = [];
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_categories', wp_json_encode($categories));
}

/**
 * Add template category
 *
 * @param int    $template_id Template ID
 * @param string $category    Category
 */
function aqualuxe_add_template_category($template_id, $category) {
    if (!$template_id) {
        return;
    }
    
    $categories = aqualuxe_get_template_categories($template_id);
    
    if (!in_array($category, $categories)) {
        $categories[] = $category;
        aqualuxe_set_template_categories($template_id, $categories);
    }
}

/**
 * Remove template category
 *
 * @param int    $template_id Template ID
 * @param string $category    Category
 */
function aqualuxe_remove_template_category($template_id, $category) {
    if (!$template_id) {
        return;
    }
    
    $categories = aqualuxe_get_template_categories($template_id);
    
    if (in_array($category, $categories)) {
        $categories = array_diff($categories, [$category]);
        aqualuxe_set_template_categories($template_id, $categories);
    }
}

/**
 * Check if template has category
 *
 * @param int    $template_id Template ID
 * @param string $category    Category
 * @return bool
 */
function aqualuxe_template_has_category($template_id, $category) {
    if (!$template_id) {
        return false;
    }
    
    $categories = aqualuxe_get_template_categories($template_id);
    
    return in_array($category, $categories);
}

/**
 * Get template tags
 *
 * @param int $template_id Template ID
 * @return array
 */
function aqualuxe_get_template_tags($template_id) {
    if (!$template_id) {
        return [];
    }
    
    $tags = aqualuxe_get_template_meta($template_id, '_aqualuxe_template_tags');
    
    if (!$tags) {
        return [];
    }
    
    if (is_string($tags)) {
        $tags = json_decode($tags, true);
    }
    
    if (!is_array($tags)) {
        return [];
    }
    
    return $tags;
}

/**
 * Set template tags
 *
 * @param int   $template_id Template ID
 * @param array $tags        Tags
 */
function aqualuxe_set_template_tags($template_id, $tags) {
    if (!$template_id) {
        return;
    }
    
    if (!is_array($tags)) {
        $tags = [];
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_tags', wp_json_encode($tags));
}

/**
 * Add template tag
 *
 * @param int    $template_id Template ID
 * @param string $tag         Tag
 */
function aqualuxe_add_template_tag($template_id, $tag) {
    if (!$template_id) {
        return;
    }
    
    $tags = aqualuxe_get_template_tags($template_id);
    
    if (!in_array($tag, $tags)) {
        $tags[] = $tag;
        aqualuxe_set_template_tags($template_id, $tags);
    }
}

/**
 * Remove template tag
 *
 * @param int    $template_id Template ID
 * @param string $tag         Tag
 */
function aqualuxe_remove_template_tag($template_id, $tag) {
    if (!$template_id) {
        return;
    }
    
    $tags = aqualuxe_get_template_tags($template_id);
    
    if (in_array($tag, $tags)) {
        $tags = array_diff($tags, [$tag]);
        aqualuxe_set_template_tags($template_id, $tags);
    }
}

/**
 * Check if template has tag
 *
 * @param int    $template_id Template ID
 * @param string $tag         Tag
 * @return bool
 */
function aqualuxe_template_has_tag($template_id, $tag) {
    if (!$template_id) {
        return false;
    }
    
    $tags = aqualuxe_get_template_tags($template_id);
    
    return in_array($tag, $tags);
}

/**
 * Get template screenshot
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_screenshot($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_screenshot');
}

/**
 * Set template screenshot
 *
 * @param int    $template_id Template ID
 * @param string $screenshot  Screenshot
 */
function aqualuxe_set_template_screenshot($template_id, $screenshot) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_screenshot', $screenshot);
}

/**
 * Get template preview
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_preview($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_preview');
}

/**
 * Set template preview
 *
 * @param int    $template_id Template ID
 * @param string $preview     Preview
 */
function aqualuxe_set_template_preview($template_id, $preview) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_preview', $preview);
}

/**
 * Get template demo
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_demo($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_demo');
}

/**
 * Set template demo
 *
 * @param int    $template_id Template ID
 * @param string $demo        Demo
 */
function aqualuxe_set_template_demo($template_id, $demo) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_demo', $demo);
}

/**
 * Get template documentation
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_documentation($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_documentation');
}

/**
 * Set template documentation
 *
 * @param int    $template_id    Template ID
 * @param string $documentation  Documentation
 */
function aqualuxe_set_template_documentation($template_id, $documentation) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_documentation', $documentation);
}

/**
 * Get template support
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_support($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_support');
}

/**
 * Set template support
 *
 * @param int    $template_id Template ID
 * @param string $support     Support
 */
function aqualuxe_set_template_support($template_id, $support) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_support', $support);
}

/**
 * Get template license
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_license($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_license');
}

/**
 * Set template license
 *
 * @param int    $template_id Template ID
 * @param string $license     License
 */
function aqualuxe_set_template_license($template_id, $license) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_license', $license);
}

/**
 * Get template price
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_price($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_price');
}

/**
 * Set template price
 *
 * @param int    $template_id Template ID
 * @param string $price       Price
 */
function aqualuxe_set_template_price($template_id, $price) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_price', $price);
}

/**
 * Get template rating
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_rating($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_rating');
}

/**
 * Set template rating
 *
 * @param int    $template_id Template ID
 * @param string $rating      Rating
 */
function aqualuxe_set_template_rating($template_id, $rating) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_rating', $rating);
}

/**
 * Get template downloads
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_downloads($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_downloads');
}

/**
 * Set template downloads
 *
 * @param int    $template_id Template ID
 * @param string $downloads   Downloads
 */
function aqualuxe_set_template_downloads($template_id, $downloads) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_downloads', $downloads);
}

/**
 * Get template sales
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_sales($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_sales');
}

/**
 * Set template sales
 *
 * @param int    $template_id Template ID
 * @param string $sales       Sales
 */
function aqualuxe_set_template_sales($template_id, $sales) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_sales', $sales);
}

/**
 * Get template views
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_views($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_views');
}

/**
 * Set template views
 *
 * @param int    $template_id Template ID
 * @param string $views       Views
 */
function aqualuxe_set_template_views($template_id, $views) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_views', $views);
}

/**
 * Get template favorites
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_favorites($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_favorites');
}

/**
 * Set template favorites
 *
 * @param int    $template_id Template ID
 * @param string $favorites   Favorites
 */
function aqualuxe_set_template_favorites($template_id, $favorites) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_favorites', $favorites);
}

/**
 * Get template comments
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_comments($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_comments');
}

/**
 * Set template comments
 *
 * @param int    $template_id Template ID
 * @param string $comments    Comments
 */
function aqualuxe_set_template_comments($template_id, $comments) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_comments', $comments);
}

/**
 * Get template reviews
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_reviews($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_reviews');
}

/**
 * Set template reviews
 *
 * @param int    $template_id Template ID
 * @param string $reviews     Reviews
 */
function aqualuxe_set_template_reviews($template_id, $reviews) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_reviews', $reviews);
}

/**
 * Get template likes
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_likes($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_likes');
}

/**
 * Set template likes
 *
 * @param int    $template_id Template ID
 * @param string $likes       Likes
 */
function aqualuxe_set_template_likes($template_id, $likes) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_likes', $likes);
}

/**
 * Get template dislikes
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_dislikes($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_dislikes');
}

/**
 * Set template dislikes
 *
 * @param int    $template_id Template ID
 * @param string $dislikes    Dislikes
 */
function aqualuxe_set_template_dislikes($template_id, $dislikes) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_dislikes', $dislikes);
}

/**
 * Get template shares
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_shares($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_shares');
}

/**
 * Set template shares
 *
 * @param int    $template_id Template ID
 * @param string $shares      Shares
 */
function aqualuxe_set_template_shares($template_id, $shares) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_shares', $shares);
}

/**
 * Get template imports
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_imports($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_imports');
}

/**
 * Set template imports
 *
 * @param int    $template_id Template ID
 * @param string $imports     Imports
 */
function aqualuxe_set_template_imports($template_id, $imports) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_imports', $imports);
}

/**
 * Get template exports
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_exports($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_exports');
}

/**
 * Set template exports
 *
 * @param int    $template_id Template ID
 * @param string $exports     Exports
 */
function aqualuxe_set_template_exports($template_id, $exports) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_exports', $exports);
}

/**
 * Get template clones
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_clones($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_clones');
}

/**
 * Set template clones
 *
 * @param int    $template_id Template ID
 * @param string $clones      Clones
 */
function aqualuxe_set_template_clones($template_id, $clones) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_clones', $clones);
}

/**
 * Get template parent
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_parent($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_parent');
}

/**
 * Set template parent
 *
 * @param int    $template_id Template ID
 * @param string $parent      Parent
 */
function aqualuxe_set_template_parent($template_id, $parent) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_parent', $parent);
}

/**
 * Get template children
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_children($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_children');
}

/**
 * Set template children
 *
 * @param int    $template_id Template ID
 * @param string $children    Children
 */
function aqualuxe_set_template_children($template_id, $children) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_children', $children);
}

/**
 * Get template siblings
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_siblings($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_siblings');
}

/**
 * Set template siblings
 *
 * @param int    $template_id Template ID
 * @param string $siblings    Siblings
 */
function aqualuxe_set_template_siblings($template_id, $siblings) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_siblings', $siblings);
}

/**
 * Get template related
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_related($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_related');
}

/**
 * Set template related
 *
 * @param int    $template_id Template ID
 * @param string $related     Related
 */
function aqualuxe_set_template_related($template_id, $related) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_related', $related);
}

/**
 * Get template similar
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_similar($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_similar');
}

/**
 * Set template similar
 *
 * @param int    $template_id Template ID
 * @param string $similar     Similar
 */
function aqualuxe_set_template_similar($template_id, $similar) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_similar', $similar);
}

/**
 * Get template recommended
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_recommended($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_recommended');
}

/**
 * Set template recommended
 *
 * @param int    $template_id  Template ID
 * @param string $recommended  Recommended
 */
function aqualuxe_set_template_recommended($template_id, $recommended) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_recommended', $recommended);
}

/**
 * Get template popular
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_popular($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_popular');
}

/**
 * Set template popular
 *
 * @param int    $template_id Template ID
 * @param string $popular     Popular
 */
function aqualuxe_set_template_popular($template_id, $popular) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_popular', $popular);
}

/**
 * Get template trending
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_trending($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_trending');
}

/**
 * Set template trending
 *
 * @param int    $template_id Template ID
 * @param string $trending    Trending
 */
function aqualuxe_set_template_trending($template_id, $trending) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_trending', $trending);
}

/**
 * Get template featured
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_featured($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_featured');
}

/**
 * Set template featured
 *
 * @param int    $template_id Template ID
 * @param string $featured    Featured
 */
function aqualuxe_set_template_featured($template_id, $featured) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_featured', $featured);
}

/**
 * Get template new
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_new($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_new');
}

/**
 * Set template new
 *
 * @param int    $template_id Template ID
 * @param string $new         New
 */
function aqualuxe_set_template_new($template_id, $new) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_new', $new);
}

/**
 * Get template hot
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_hot($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_hot');
}

/**
 * Set template hot
 *
 * @param int    $template_id Template ID
 * @param string $hot         Hot
 */
function aqualuxe_set_template_hot($template_id, $hot) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_hot', $hot);
}

/**
 * Get template best
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_best($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_best');
}

/**
 * Set template best
 *
 * @param int    $template_id Template ID
 * @param string $best        Best
 */
function aqualuxe_set_template_best($template_id, $best) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_best', $best);
}

/**
 * Get template top
 *
 * @param int $template_id Template ID
 * @return string
 */
function aqualuxe_get_template_top($template_id) {
    if (!$template_id) {
        return '';
    }
    
    return aqualuxe_get_template_meta($template_id, '_aqualuxe_template_top');
}

/**
 * Set template top
 *
 * @param int    $template_id Template ID
 * @param string $top         Top
 */
function aqualuxe_set_template_top($template_id, $top) {
    if (!$template_id) {
        return;
    }
    
    aqualuxe_set_template_meta($template_id, '_aqualuxe_template_top', $top);
}