<?php
/**
 * Template Loader Class
 * 
 * Handles template loading and hierarchy
 * 
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Template_Loader {
    
    /**
     * Get template part with fallback
     */
    public function get_template_part($slug, $name = null, $args = []) {
        // Extract args to variables
        if (!empty($args) && is_array($args)) {
            extract($args);
        }
        
        $templates = [];
        
        if (isset($name)) {
            $templates[] = "{$slug}-{$name}.php";
        }
        
        $templates[] = "{$slug}.php";
        
        // Check in template-parts directory first (new WordPress standard)
        foreach ($templates as $template) {
            $template_path = AQUALUXE_THEME_DIR . '/template-parts/' . $template;
            if (file_exists($template_path)) {
                include $template_path;
                return;
            }
        }
        
        // Check in templates directory
        foreach ($templates as $template) {
            $template_path = AQUALUXE_THEME_DIR . '/templates/' . $template;
            if (file_exists($template_path)) {
                include $template_path;
                return;
            }
        }
        
        // Fallback to WordPress default
        get_template_part($slug, $name, $args);
    }
    
    /**
     * Locate template with module support
     */
    public function locate_template($template_names, $module = null) {
        $located = '';
        
        foreach ((array) $template_names as $template_name) {
            if (!$template_name) {
                continue;
            }
            
            // Check in module directory first
            if ($module) {
                $module_path = AQUALUXE_MODULES_DIR . '/' . $module . '/templates/' . $template_name;
                if (file_exists($module_path)) {
                    $located = $module_path;
                    break;
                }
            }
            
            // Check in theme templates directory
            $theme_path = AQUALUXE_THEME_DIR . '/templates/' . $template_name;
            if (file_exists($theme_path)) {
                $located = $theme_path;
                break;
            }
            
            // Check in theme root
            $root_path = AQUALUXE_THEME_DIR . '/' . $template_name;
            if (file_exists($root_path)) {
                $located = $root_path;
                break;
            }
        }
        
        return $located;
    }
    
    /**
     * Load template with args
     */
    public function load_template($template, $args = []) {
        if (!empty($args) && is_array($args)) {
            extract($args);
        }
        
        if (file_exists($template)) {
            include $template;
        }
    }
    
    /**
     * Get module template
     */
    public function get_module_template($module, $template, $args = []) {
        $template_path = $this->locate_template($template, $module);
        
        if ($template_path) {
            $this->load_template($template_path, $args);
        }
    }
}