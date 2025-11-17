<?php
/**
 * Multitenant support for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize multitenant support
 */
function aqualuxe_multitenant_init() {
    // Add multitenant support for multisite
    if (is_multisite()) {
        add_filter('body_class', 'aqualuxe_multitenant_body_class');
        add_action('wp_head', 'aqualuxe_multitenant_custom_css', 100);
        add_action('after_setup_theme', 'aqualuxe_multitenant_setup');
        add_filter('aqualuxe_get_option', 'aqualuxe_multitenant_get_option', 10, 3);
    }
}
add_action('after_setup_theme', 'aqualuxe_multitenant_init');

/**
 * Add multitenant body class
 *
 * @param array $classes
 * @return array
 */
function aqualuxe_multitenant_body_class($classes) {
    $classes[] = 'multitenant';
    $classes[] = 'tenant-' . get_current_blog_id();
    
    return $classes;
}

/**
 * Add multitenant custom CSS
 */
function aqualuxe_multitenant_custom_css() {
    $tenant_id = get_current_blog_id();
    $tenant_options = get_blog_option($tenant_id, 'aqualuxe_options', array());
    
    // Check if tenant has custom colors
    if (isset($tenant_options['primary_color']) || isset($tenant_options['secondary_color']) || isset($tenant_options['accent_color'])) {
        $primary_color = isset($tenant_options['primary_color']) ? $tenant_options['primary_color'] : '#0073aa';
        $secondary_color = isset($tenant_options['secondary_color']) ? $tenant_options['secondary_color'] : '#00a0d2';
        $accent_color = isset($tenant_options['accent_color']) ? $tenant_options['accent_color'] : '#00c1af';
        
        echo '<style id="aqualuxe-tenant-' . esc_attr($tenant_id) . '-css">';
        echo '.tenant-' . esc_attr($tenant_id) . ' {';
        echo '--primary-color: ' . esc_attr($primary_color) . ';';
        echo '--secondary-color: ' . esc_attr($secondary_color) . ';';
        echo '--accent-color: ' . esc_attr($accent_color) . ';';
        echo '}';
        echo '</style>';
    }
}

/**
 * Set up multitenant theme
 */
function aqualuxe_multitenant_setup() {
    // Get tenant ID
    $tenant_id = get_current_blog_id();
    
    // Set up tenant-specific theme mods
    $tenant_mods = get_blog_option($tenant_id, 'theme_mods_aqualuxe', array());
    
    // If tenant has no theme mods, use default theme mods
    if (empty($tenant_mods) && $tenant_id !== 1) {
        $default_mods = get_blog_option(1, 'theme_mods_aqualuxe', array());
        
        if (!empty($default_mods)) {
            update_blog_option($tenant_id, 'theme_mods_aqualuxe', $default_mods);
        }
    }
}

/**
 * Get tenant option
 *
 * @param mixed $value
 * @param string $option_name
 * @param mixed $default
 * @return mixed
 */
function aqualuxe_multitenant_get_option($value, $option_name, $default) {
    // Get tenant ID
    $tenant_id = get_current_blog_id();
    
    // Get tenant options
    $tenant_options = get_blog_option($tenant_id, 'aqualuxe_options', array());
    
    // Check if tenant has this option
    if (isset($tenant_options[$option_name])) {
        return $tenant_options[$option_name];
    }
    
    // If tenant has no options and is not the main site, use main site options
    if (empty($tenant_options) && $tenant_id !== 1) {
        $main_options = get_blog_option(1, 'aqualuxe_options', array());
        
        if (isset($main_options[$option_name])) {
            return $main_options[$option_name];
        }
    }
    
    return $value;
}

/**
 * Get tenant ID
 *
 * @return int
 */
function aqualuxe_get_tenant_id() {
    if (is_multisite()) {
        return get_current_blog_id();
    }
    
    return 1;
}

/**
 * Get tenant data
 *
 * @param int $tenant_id
 * @return array
 */
function aqualuxe_get_tenant_data($tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        $blog_details = get_blog_details($tenant_id);
        
        if ($blog_details) {
            return array(
                'id' => $tenant_id,
                'name' => $blog_details->blogname,
                'url' => $blog_details->siteurl,
                'description' => $blog_details->blogdescription,
            );
        }
    }
    
    return array(
        'id' => $tenant_id,
        'name' => get_bloginfo('name'),
        'url' => home_url(),
        'description' => get_bloginfo('description'),
    );
}

/**
 * Get all tenants
 *
 * @return array
 */
function aqualuxe_get_all_tenants() {
    $tenants = array();
    
    if (is_multisite()) {
        $sites = get_sites(array(
            'public' => 1,
            'archived' => 0,
            'spam' => 0,
            'deleted' => 0,
        ));
        
        foreach ($sites as $site) {
            $tenant_id = $site->blog_id;
            $tenants[$tenant_id] = aqualuxe_get_tenant_data($tenant_id);
        }
    } else {
        $tenants[1] = aqualuxe_get_tenant_data(1);
    }
    
    return $tenants;
}

/**
 * Switch to tenant
 *
 * @param int $tenant_id
 * @return bool
 */
function aqualuxe_switch_to_tenant($tenant_id) {
    if (is_multisite()) {
        return switch_to_blog($tenant_id);
    }
    
    return false;
}

/**
 * Restore current tenant
 *
 * @return void
 */
function aqualuxe_restore_tenant() {
    if (is_multisite()) {
        restore_current_blog();
    }
}

/**
 * Get tenant option
 *
 * @param string $option_name
 * @param mixed $default
 * @param int $tenant_id
 * @return mixed
 */
function aqualuxe_get_tenant_option($option_name, $default = '', $tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        $switched = false;
        
        if ($tenant_id !== get_current_blog_id()) {
            $switched = aqualuxe_switch_to_tenant($tenant_id);
        }
        
        $options = get_option('aqualuxe_options', array());
        $value = isset($options[$option_name]) ? $options[$option_name] : $default;
        
        if ($switched) {
            aqualuxe_restore_tenant();
        }
        
        return $value;
    }
    
    $options = get_option('aqualuxe_options', array());
    return isset($options[$option_name]) ? $options[$option_name] : $default;
}

/**
 * Update tenant option
 *
 * @param string $option_name
 * @param mixed $value
 * @param int $tenant_id
 * @return bool
 */
function aqualuxe_update_tenant_option($option_name, $value, $tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        $switched = false;
        
        if ($tenant_id !== get_current_blog_id()) {
            $switched = aqualuxe_switch_to_tenant($tenant_id);
        }
        
        $options = get_option('aqualuxe_options', array());
        $options[$option_name] = $value;
        $result = update_option('aqualuxe_options', $options);
        
        if ($switched) {
            aqualuxe_restore_tenant();
        }
        
        return $result;
    }
    
    $options = get_option('aqualuxe_options', array());
    $options[$option_name] = $value;
    return update_option('aqualuxe_options', $options);
}

/**
 * Delete tenant option
 *
 * @param string $option_name
 * @param int $tenant_id
 * @return bool
 */
function aqualuxe_delete_tenant_option($option_name, $tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        $switched = false;
        
        if ($tenant_id !== get_current_blog_id()) {
            $switched = aqualuxe_switch_to_tenant($tenant_id);
        }
        
        $options = get_option('aqualuxe_options', array());
        
        if (isset($options[$option_name])) {
            unset($options[$option_name]);
            $result = update_option('aqualuxe_options', $options);
        } else {
            $result = false;
        }
        
        if ($switched) {
            aqualuxe_restore_tenant();
        }
        
        return $result;
    }
    
    $options = get_option('aqualuxe_options', array());
    
    if (isset($options[$option_name])) {
        unset($options[$option_name]);
        return update_option('aqualuxe_options', $options);
    }
    
    return false;
}

/**
 * Get tenant theme mod
 *
 * @param string $name
 * @param mixed $default
 * @param int $tenant_id
 * @return mixed
 */
function aqualuxe_get_tenant_theme_mod($name, $default = '', $tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        $switched = false;
        
        if ($tenant_id !== get_current_blog_id()) {
            $switched = aqualuxe_switch_to_tenant($tenant_id);
        }
        
        $value = get_theme_mod($name, $default);
        
        if ($switched) {
            aqualuxe_restore_tenant();
        }
        
        return $value;
    }
    
    return get_theme_mod($name, $default);
}

/**
 * Set tenant theme mod
 *
 * @param string $name
 * @param mixed $value
 * @param int $tenant_id
 * @return void
 */
function aqualuxe_set_tenant_theme_mod($name, $value, $tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        $switched = false;
        
        if ($tenant_id !== get_current_blog_id()) {
            $switched = aqualuxe_switch_to_tenant($tenant_id);
        }
        
        set_theme_mod($name, $value);
        
        if ($switched) {
            aqualuxe_restore_tenant();
        }
    } else {
        set_theme_mod($name, $value);
    }
}

/**
 * Remove tenant theme mod
 *
 * @param string $name
 * @param int $tenant_id
 * @return void
 */
function aqualuxe_remove_tenant_theme_mod($name, $tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        $switched = false;
        
        if ($tenant_id !== get_current_blog_id()) {
            $switched = aqualuxe_switch_to_tenant($tenant_id);
        }
        
        remove_theme_mod($name);
        
        if ($switched) {
            aqualuxe_restore_tenant();
        }
    } else {
        remove_theme_mod($name);
    }
}

/**
 * Get tenant permalink
 *
 * @param int $post_id
 * @param int $tenant_id
 * @return string
 */
function aqualuxe_get_tenant_permalink($post_id, $tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        $switched = false;
        
        if ($tenant_id !== get_current_blog_id()) {
            $switched = aqualuxe_switch_to_tenant($tenant_id);
        }
        
        $permalink = get_permalink($post_id);
        
        if ($switched) {
            aqualuxe_restore_tenant();
        }
        
        return $permalink;
    }
    
    return get_permalink($post_id);
}

/**
 * Get tenant home URL
 *
 * @param int $tenant_id
 * @return string
 */
function aqualuxe_get_tenant_home_url($tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        return get_home_url($tenant_id);
    }
    
    return home_url();
}

/**
 * Get tenant site URL
 *
 * @param int $tenant_id
 * @return string
 */
function aqualuxe_get_tenant_site_url($tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        return get_site_url($tenant_id);
    }
    
    return site_url();
}

/**
 * Get tenant admin URL
 *
 * @param int $tenant_id
 * @return string
 */
function aqualuxe_get_tenant_admin_url($tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        return get_admin_url($tenant_id);
    }
    
    return admin_url();
}

/**
 * Get tenant upload directory
 *
 * @param int $tenant_id
 * @return array
 */
function aqualuxe_get_tenant_upload_dir($tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        $switched = false;
        
        if ($tenant_id !== get_current_blog_id()) {
            $switched = aqualuxe_switch_to_tenant($tenant_id);
        }
        
        $upload_dir = wp_upload_dir();
        
        if ($switched) {
            aqualuxe_restore_tenant();
        }
        
        return $upload_dir;
    }
    
    return wp_upload_dir();
}

/**
 * Get tenant users
 *
 * @param int $tenant_id
 * @return array
 */
function aqualuxe_get_tenant_users($tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        $users = get_users(array(
            'blog_id' => $tenant_id,
        ));
        
        return $users;
    }
    
    return get_users();
}

/**
 * Check if user belongs to tenant
 *
 * @param int $user_id
 * @param int $tenant_id
 * @return bool
 */
function aqualuxe_is_tenant_user($user_id, $tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        return is_user_member_of_blog($user_id, $tenant_id);
    }
    
    return true;
}

/**
 * Add user to tenant
 *
 * @param int $user_id
 * @param string $role
 * @param int $tenant_id
 * @return bool
 */
function aqualuxe_add_tenant_user($user_id, $role = 'subscriber', $tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        return add_user_to_blog($tenant_id, $user_id, $role);
    }
    
    return false;
}

/**
 * Remove user from tenant
 *
 * @param int $user_id
 * @param int $tenant_id
 * @return bool
 */
function aqualuxe_remove_tenant_user($user_id, $tenant_id = 0) {
    if ($tenant_id === 0) {
        $tenant_id = aqualuxe_get_tenant_id();
    }
    
    if (is_multisite()) {
        return remove_user_from_blog($user_id, $tenant_id);
    }
    
    return false;
}