<?php
/**
 * Module loader for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Load theme modules
 */
function aqualuxe_load_modules() {
    // Get available modules
    $available_modules = aqualuxe_get_available_modules();
    
    // Get active modules
    $active_modules = aqualuxe_get_active_modules();
    
    // If no active modules are set, initialize with defaults
    if (empty($active_modules)) {
        $active_modules = array();
        
        foreach ($available_modules as $module_id => $module_data) {
            $active_modules[$module_id] = $module_data['default'];
        }
        
        update_option('aqualuxe_active_modules', $active_modules);
    }
    
    // Load active modules
    foreach ($active_modules as $module_id => $is_active) {
        if ($is_active) {
            $module_file = AQUALUXE_DIR . '/modules/' . $module_id . '/module.php';
            
            if (file_exists($module_file)) {
                require_once $module_file;
            }
        }
    }
    
    // Hook for modules to register their functionality
    do_action('aqualuxe_modules_loaded');
}

/**
 * Register module settings page
 */
function aqualuxe_register_module_settings_page() {
    add_theme_page(
        __('AquaLuxe Modules', 'aqualuxe'),
        __('AquaLuxe Modules', 'aqualuxe'),
        'manage_options',
        'aqualuxe-modules',
        'aqualuxe_module_settings_page'
    );
}
add_action('admin_menu', 'aqualuxe_register_module_settings_page');

/**
 * Module settings page
 */
function aqualuxe_module_settings_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Save settings
    if (isset($_POST['aqualuxe_modules_nonce']) && wp_verify_nonce($_POST['aqualuxe_modules_nonce'], 'aqualuxe_modules_save')) {
        $active_modules = array();
        
        foreach (aqualuxe_get_available_modules() as $module_id => $module_data) {
            $active_modules[$module_id] = isset($_POST['aqualuxe_module_' . $module_id]) ? true : false;
        }
        
        update_option('aqualuxe_active_modules', $active_modules);
        
        // Show success message
        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Module settings saved.', 'aqualuxe') . '</p></div>';
    }
    
    // Get available modules
    $available_modules = aqualuxe_get_available_modules();
    
    // Get active modules
    $active_modules = aqualuxe_get_active_modules();
    ?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <form method="post" action="">
            <?php wp_nonce_field('aqualuxe_modules_save', 'aqualuxe_modules_nonce'); ?>
            
            <table class="form-table">
                <tbody>
                    <?php foreach ($available_modules as $module_id => $module_data) : ?>
                        <tr>
                            <th scope="row">
                                <label for="aqualuxe_module_<?php echo esc_attr($module_id); ?>">
                                    <?php echo esc_html($module_data['name']); ?>
                                </label>
                            </th>
                            <td>
                                <label for="aqualuxe_module_<?php echo esc_attr($module_id); ?>">
                                    <input type="checkbox" id="aqualuxe_module_<?php echo esc_attr($module_id); ?>" name="aqualuxe_module_<?php echo esc_attr($module_id); ?>" value="1" <?php checked(isset($active_modules[$module_id]) && $active_modules[$module_id]); ?>>
                                    <?php echo esc_html($module_data['description']); ?>
                                </label>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <?php submit_button(__('Save Settings', 'aqualuxe')); ?>
        </form>
    </div>
    <?php
}

/**
 * Create module directory structure
 *
 * @param string $module_id Module ID.
 * @return bool
 */
function aqualuxe_create_module_structure($module_id) {
    $module_dir = AQUALUXE_DIR . '/modules/' . $module_id;
    
    // Create module directory
    if (!file_exists($module_dir)) {
        if (!mkdir($module_dir, 0755, true)) {
            return false;
        }
    }
    
    // Create module subdirectories
    $subdirs = array(
        'assets',
        'assets/js',
        'assets/css',
        'inc',
        'templates',
    );
    
    foreach ($subdirs as $subdir) {
        $dir = $module_dir . '/' . $subdir;
        
        if (!file_exists($dir)) {
            if (!mkdir($dir, 0755, true)) {
                return false;
            }
        }
    }
    
    // Create module.php file if it doesn't exist
    $module_file = $module_dir . '/module.php';
    
    if (!file_exists($module_file)) {
        $module_content = "<?php\n/**\n * {$module_id} module\n *\n * @package AquaLuxe\n * @subpackage Modules\n */\n\nif (!defined('ABSPATH')) {\n    exit; // Exit if accessed directly\n}\n\n/**\n * Initialize module\n */\nfunction aqualuxe_{$module_id}_init() {\n    // Module initialization code here\n}\nadd_action('aqualuxe_modules_loaded', 'aqualuxe_{$module_id}_init');\n";
        
        if (file_put_contents($module_file, $module_content) === false) {
            return false;
        }
    }
    
    return true;
}

/**
 * Register module assets
 *
 * @param string $module_id Module ID.
 * @param array $assets Array of assets to register.
 */
function aqualuxe_register_module_assets($module_id, $assets = array()) {
    if (empty($assets)) {
        return;
    }
    
    $module_url = AQUALUXE_URI . '/modules/' . $module_id;
    
    // Register styles
    if (isset($assets['styles']) && is_array($assets['styles'])) {
        foreach ($assets['styles'] as $handle => $style) {
            $deps = isset($style['deps']) ? $style['deps'] : array();
            $ver = isset($style['ver']) ? $style['ver'] : AQUALUXE_VERSION;
            $media = isset($style['media']) ? $style['media'] : 'all';
            
            wp_register_style(
                'aqualuxe-' . $module_id . '-' . $handle,
                $module_url . '/assets/css/' . $style['src'],
                $deps,
                $ver,
                $media
            );
            
            if (isset($style['enqueue']) && $style['enqueue']) {
                wp_enqueue_style('aqualuxe-' . $module_id . '-' . $handle);
            }
        }
    }
    
    // Register scripts
    if (isset($assets['scripts']) && is_array($assets['scripts'])) {
        foreach ($assets['scripts'] as $handle => $script) {
            $deps = isset($script['deps']) ? $script['deps'] : array();
            $ver = isset($script['ver']) ? $script['ver'] : AQUALUXE_VERSION;
            $in_footer = isset($script['in_footer']) ? $script['in_footer'] : true;
            
            wp_register_script(
                'aqualuxe-' . $module_id . '-' . $handle,
                $module_url . '/assets/js/' . $script['src'],
                $deps,
                $ver,
                $in_footer
            );
            
            if (isset($script['enqueue']) && $script['enqueue']) {
                wp_enqueue_script('aqualuxe-' . $module_id . '-' . $handle);
            }
            
            if (isset($script['localize']) && is_array($script['localize'])) {
                foreach ($script['localize'] as $object_name => $data) {
                    wp_localize_script(
                        'aqualuxe-' . $module_id . '-' . $handle,
                        $object_name,
                        $data
                    );
                }
            }
        }
    }
}

/**
 * Get module template part
 *
 * @param string $module_id Module ID.
 * @param string $template Template name.
 * @param string $part Template part.
 * @param array $args Template arguments.
 */
function aqualuxe_get_module_template_part($module_id, $template, $part = null, $args = array()) {
    $template_file = $part ? "{$template}-{$part}.php" : "{$template}.php";
    $template_path = AQUALUXE_DIR . '/modules/' . $module_id . '/templates/' . $template_file;
    
    // Check if template exists in theme
    if (file_exists($template_path)) {
        // Extract args to make them available in the template
        if ($args && is_array($args)) {
            extract($args);
        }
        
        include $template_path;
    }
}

/**
 * Get module option
 *
 * @param string $module_id Module ID.
 * @param string $option Option name.
 * @param mixed $default Default value.
 * @return mixed
 */
function aqualuxe_get_module_option($module_id, $option, $default = '') {
    $options = get_option('aqualuxe_module_' . $module_id . '_options', array());
    
    return isset($options[$option]) ? $options[$option] : $default;
}

/**
 * Update module option
 *
 * @param string $module_id Module ID.
 * @param string $option Option name.
 * @param mixed $value Option value.
 * @return bool
 */
function aqualuxe_update_module_option($module_id, $option, $value) {
    $options = get_option('aqualuxe_module_' . $module_id . '_options', array());
    $options[$option] = $value;
    
    return update_option('aqualuxe_module_' . $module_id . '_options', $options);
}

/**
 * Delete module option
 *
 * @param string $module_id Module ID.
 * @param string $option Option name.
 * @return bool
 */
function aqualuxe_delete_module_option($module_id, $option) {
    $options = get_option('aqualuxe_module_' . $module_id . '_options', array());
    
    if (isset($options[$option])) {
        unset($options[$option]);
        return update_option('aqualuxe_module_' . $module_id . '_options', $options);
    }
    
    return false;
}

/**
 * Register module settings
 *
 * @param string $module_id Module ID.
 * @param array $settings Settings array.
 */
function aqualuxe_register_module_settings($module_id, $settings = array()) {
    if (empty($settings)) {
        return;
    }
    
    // Register setting
    register_setting(
        'aqualuxe_module_' . $module_id . '_options',
        'aqualuxe_module_' . $module_id . '_options',
        array(
            'sanitize_callback' => 'aqualuxe_sanitize_module_options',
        )
    );
    
    // Add settings sections
    if (isset($settings['sections']) && is_array($settings['sections'])) {
        foreach ($settings['sections'] as $section_id => $section) {
            add_settings_section(
                'aqualuxe_module_' . $module_id . '_' . $section_id,
                $section['title'],
                isset($section['callback']) ? $section['callback'] : null,
                'aqualuxe_module_' . $module_id
            );
            
            // Add settings fields
            if (isset($section['fields']) && is_array($section['fields'])) {
                foreach ($section['fields'] as $field_id => $field) {
                    add_settings_field(
                        'aqualuxe_module_' . $module_id . '_' . $field_id,
                        $field['title'],
                        isset($field['callback']) ? $field['callback'] : 'aqualuxe_module_settings_field_callback',
                        'aqualuxe_module_' . $module_id,
                        'aqualuxe_module_' . $module_id . '_' . $section_id,
                        array_merge(
                            array(
                                'module_id' => $module_id,
                                'field_id' => $field_id,
                            ),
                            isset($field['args']) ? $field['args'] : array()
                        )
                    );
                }
            }
        }
    }
}

/**
 * Module settings field callback
 *
 * @param array $args Field arguments.
 */
function aqualuxe_module_settings_field_callback($args) {
    $module_id = $args['module_id'];
    $field_id = $args['field_id'];
    $type = isset($args['type']) ? $args['type'] : 'text';
    $default = isset($args['default']) ? $args['default'] : '';
    $description = isset($args['description']) ? $args['description'] : '';
    $options = isset($args['options']) ? $args['options'] : array();
    
    $value = aqualuxe_get_module_option($module_id, $field_id, $default);
    $name = 'aqualuxe_module_' . $module_id . '_options[' . $field_id . ']';
    $id = 'aqualuxe_module_' . $module_id . '_' . $field_id;
    
    switch ($type) {
        case 'text':
        case 'email':
        case 'url':
        case 'number':
            echo '<input type="' . esc_attr($type) . '" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" class="regular-text">';
            break;
            
        case 'textarea':
            echo '<textarea id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" rows="5" cols="50" class="large-text">' . esc_textarea($value) . '</textarea>';
            break;
            
        case 'checkbox':
            echo '<input type="checkbox" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="1" ' . checked(1, $value, false) . '>';
            break;
            
        case 'select':
            echo '<select id="' . esc_attr($id) . '" name="' . esc_attr($name) . '">';
            foreach ($options as $option_value => $option_label) {
                echo '<option value="' . esc_attr($option_value) . '" ' . selected($option_value, $value, false) . '>' . esc_html($option_label) . '</option>';
            }
            echo '</select>';
            break;
            
        case 'radio':
            foreach ($options as $option_value => $option_label) {
                echo '<label><input type="radio" name="' . esc_attr($name) . '" value="' . esc_attr($option_value) . '" ' . checked($option_value, $value, false) . '> ' . esc_html($option_label) . '</label><br>';
            }
            break;
            
        case 'color':
            echo '<input type="text" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '" class="color-picker">';
            break;
            
        case 'image':
            $image_url = $value ? wp_get_attachment_image_url($value, 'thumbnail') : '';
            echo '<div class="module-image-field">';
            echo '<div class="image-preview">';
            if ($image_url) {
                echo '<img src="' . esc_url($image_url) . '" alt="">';
            }
            echo '</div>';
            echo '<input type="hidden" id="' . esc_attr($id) . '" name="' . esc_attr($name) . '" value="' . esc_attr($value) . '">';
            echo '<button type="button" class="button upload-image">' . esc_html__('Upload Image', 'aqualuxe') . '</button>';
            echo '<button type="button" class="button remove-image" ' . ($value ? '' : 'style="display:none;"') . '>' . esc_html__('Remove Image', 'aqualuxe') . '</button>';
            echo '</div>';
            break;
    }
    
    if ($description) {
        echo '<p class="description">' . wp_kses_post($description) . '</p>';
    }
}

/**
 * Sanitize module options
 *
 * @param array $options Options to sanitize.
 * @return array
 */
function aqualuxe_sanitize_module_options($options) {
    // Default sanitization - can be extended for specific modules
    if (!is_array($options)) {
        return array();
    }
    
    foreach ($options as $key => $value) {
        if (is_string($value)) {
            $options[$key] = sanitize_text_field($value);
        }
    }
    
    return $options;
}

/**
 * Register module admin page
 *
 * @param string $module_id Module ID.
 * @param array $args Page arguments.
 */
function aqualuxe_register_module_admin_page($module_id, $args = array()) {
    $defaults = array(
        'page_title' => sprintf(__('%s Settings', 'aqualuxe'), ucfirst($module_id)),
        'menu_title' => sprintf(__('%s Settings', 'aqualuxe'), ucfirst($module_id)),
        'capability' => 'manage_options',
        'menu_slug' => 'aqualuxe-' . $module_id,
        'callback' => 'aqualuxe_module_admin_page_callback',
        'parent_slug' => 'themes.php',
    );
    
    $args = wp_parse_args($args, $defaults);
    
    // Store module admin page data
    $admin_pages = get_option('aqualuxe_module_admin_pages', array());
    $admin_pages[$module_id] = $args;
    update_option('aqualuxe_module_admin_pages', $admin_pages);
    
    // Register admin page
    add_action('admin_menu', function() use ($module_id, $args) {
        add_submenu_page(
            $args['parent_slug'],
            $args['page_title'],
            $args['menu_title'],
            $args['capability'],
            $args['menu_slug'],
            function() use ($module_id, $args) {
                $callback = $args['callback'];
                
                if (is_callable($callback)) {
                    call_user_func($callback, $module_id);
                } else {
                    aqualuxe_module_admin_page_callback($module_id);
                }
            }
        );
    });
}

/**
 * Module admin page callback
 *
 * @param string $module_id Module ID.
 */
function aqualuxe_module_admin_page_callback($module_id) {
    $admin_pages = get_option('aqualuxe_module_admin_pages', array());
    $page_data = isset($admin_pages[$module_id]) ? $admin_pages[$module_id] : array();
    $page_title = isset($page_data['page_title']) ? $page_data['page_title'] : sprintf(__('%s Settings', 'aqualuxe'), ucfirst($module_id));
    ?>
    <div class="wrap">
        <h1><?php echo esc_html($page_title); ?></h1>
        
        <form method="post" action="options.php">
            <?php
            settings_fields('aqualuxe_module_' . $module_id . '_options');
            do_settings_sections('aqualuxe_module_' . $module_id);
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * Enqueue module admin scripts
 */
function aqualuxe_module_admin_scripts() {
    $screen = get_current_screen();
    
    if ($screen && strpos($screen->id, 'aqualuxe-') === 0) {
        wp_enqueue_media();
        wp_enqueue_style('wp-color-picker');
        
        wp_enqueue_script(
            'aqualuxe-module-admin',
            get_template_directory_uri() . '/assets/dist/js/module-admin.js',
            array('jquery', 'wp-color-picker'),
            AQUALUXE_VERSION,
            true
        );
    }
}
add_action('admin_enqueue_scripts', 'aqualuxe_module_admin_scripts');

/**
 * Create default modules
 */
function aqualuxe_create_default_modules() {
    $available_modules = aqualuxe_get_available_modules();
    
    foreach ($available_modules as $module_id => $module_data) {
        aqualuxe_create_module_structure($module_id);
    }
}
add_action('after_switch_theme', 'aqualuxe_create_default_modules');