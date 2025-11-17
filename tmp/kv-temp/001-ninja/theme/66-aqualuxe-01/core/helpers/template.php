<?php
/**
 * Template helper functions
 *
 * @package AquaLuxe
 */

/**
 * Get template part
 *
 * @param string $slug
 * @param string $name
 * @param array $args
 */
function aqualuxe_get_template_part($slug, $name = null, $args = []) {
    // Extract args to variables
    if (!empty($args) && is_array($args)) {
        extract($args);
    }

    // Look in theme/templates/slug-name.php
    if ($name) {
        $template = locate_template(['templates/' . $slug . '-' . $name . '.php']);
    } else {
        $template = '';
    }

    // If template doesn't exist, look in theme/templates/slug.php
    if (!$template) {
        $template = locate_template(['templates/' . $slug . '.php']);
    }

    // Allow plugins/themes to override the template
    $template = apply_filters('aqualuxe_get_template_part', $template, $slug, $name, $args);

    // If template exists, include it
    if ($template) {
        include $template;
    }
}

/**
 * Get template
 *
 * @param string $template_name
 * @param array $args
 * @param string $template_path
 * @param string $default_path
 */
function aqualuxe_get_template($template_name, $args = [], $template_path = '', $default_path = '') {
    // Extract args to variables
    if (!empty($args) && is_array($args)) {
        extract($args);
    }

    // Set default template path if not provided
    if (!$template_path) {
        $template_path = 'templates/';
    }

    // Set default path if not provided
    if (!$default_path) {
        $default_path = AQUALUXE_TEMPLATES_DIR;
    }

    // Look for template in theme
    $template = locate_template([
        trailingslashit($template_path) . $template_name,
        $template_name,
    ]);

    // Get default template if not found in theme
    if (!$template) {
        $template = $default_path . $template_name;
    }

    // Allow plugins/themes to override the template
    $template = apply_filters('aqualuxe_get_template', $template, $template_name, $args, $template_path, $default_path);

    // If template exists, include it
    if (file_exists($template)) {
        include $template;
    }
}

/**
 * Get template HTML
 *
 * @param string $template_name
 * @param array $args
 * @param string $template_path
 * @param string $default_path
 * @return string
 */
function aqualuxe_get_template_html($template_name, $args = [], $template_path = '', $default_path = '') {
    ob_start();
    aqualuxe_get_template($template_name, $args, $template_path, $default_path);
    return ob_get_clean();
}

/**
 * Get module template part
 *
 * @param string $module
 * @param string $slug
 * @param string $name
 * @param array $args
 */
function aqualuxe_get_module_template_part($module, $slug, $name = null, $args = []) {
    $theme = \AquaLuxe\Theme::get_instance();
    
    // Check if module is active
    if (!$theme->is_module_active($module)) {
        return;
    }
    
    // Get module instance
    $modules = $theme->get_active_modules();
    $module_instance = $modules[$module] ?? null;
    
    if ($module_instance) {
        $module_instance->get_template_part($slug, $name, $args);
    }
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
    $theme = \AquaLuxe\Theme::get_instance();
    return $theme->is_woocommerce_active();
}

/**
 * Check if a module is active
 *
 * @param string $module
 * @return bool
 */
function aqualuxe_is_module_active($module) {
    $theme = \AquaLuxe\Theme::get_instance();
    return $theme->is_module_active($module);
}

/**
 * Get active modules
 *
 * @return array
 */
function aqualuxe_get_active_modules() {
    $theme = \AquaLuxe\Theme::get_instance();
    return $theme->get_active_modules();
}

/**
 * Get header template
 *
 * @param string $name
 * @param array $args
 */
function aqualuxe_get_header($name = null, $args = []) {
    aqualuxe_get_template_part('header', $name, $args);
}

/**
 * Get footer template
 *
 * @param string $name
 * @param array $args
 */
function aqualuxe_get_footer($name = null, $args = []) {
    aqualuxe_get_template_part('footer', $name, $args);
}

/**
 * Get sidebar template
 *
 * @param string $name
 * @param array $args
 */
function aqualuxe_get_sidebar($name = null, $args = []) {
    aqualuxe_get_template_part('sidebar', $name, $args);
}

/**
 * Add schema.org markup
 *
 * @param string $type
 * @param array $attributes
 * @return string
 */
function aqualuxe_schema_markup($type, $attributes = []) {
    $schema = 'itemscope itemtype="https://schema.org/' . esc_attr($type) . '"';
    
    foreach ($attributes as $key => $value) {
        $schema .= ' itemprop="' . esc_attr($key) . '" content="' . esc_attr($value) . '"';
    }
    
    return $schema;
}

/**
 * Get Open Graph meta tags
 *
 * @return string
 */
function aqualuxe_get_open_graph_meta() {
    $meta = '';
    
    // Default values
    $og_title = get_the_title();
    $og_description = get_the_excerpt();
    $og_url = get_permalink();
    $og_type = is_single() ? 'article' : 'website';
    $og_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
    
    // Add meta tags
    $meta .= '<meta property="og:title" content="' . esc_attr($og_title) . '" />' . "\n";
    $meta .= '<meta property="og:description" content="' . esc_attr($og_description) . '" />' . "\n";
    $meta .= '<meta property="og:url" content="' . esc_url($og_url) . '" />' . "\n";
    $meta .= '<meta property="og:type" content="' . esc_attr($og_type) . '" />' . "\n";
    
    if ($og_image) {
        $meta .= '<meta property="og:image" content="' . esc_url($og_image) . '" />' . "\n";
    }
    
    // Allow filtering
    return apply_filters('aqualuxe_open_graph_meta', $meta);
}

/**
 * Get Twitter Card meta tags
 *
 * @return string
 */
function aqualuxe_get_twitter_card_meta() {
    $meta = '';
    
    // Default values
    $twitter_card = 'summary_large_image';
    $twitter_title = get_the_title();
    $twitter_description = get_the_excerpt();
    $twitter_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
    
    // Add meta tags
    $meta .= '<meta name="twitter:card" content="' . esc_attr($twitter_card) . '" />' . "\n";
    $meta .= '<meta name="twitter:title" content="' . esc_attr($twitter_title) . '" />' . "\n";
    $meta .= '<meta name="twitter:description" content="' . esc_attr($twitter_description) . '" />' . "\n";
    
    if ($twitter_image) {
        $meta .= '<meta name="twitter:image" content="' . esc_url($twitter_image) . '" />' . "\n";
    }
    
    // Allow filtering
    return apply_filters('aqualuxe_twitter_card_meta', $meta);
}