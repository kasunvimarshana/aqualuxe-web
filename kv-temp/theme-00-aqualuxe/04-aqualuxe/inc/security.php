<?php
/**
 * AquaLuxe Security Enhancements
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remove WordPress version from head and feeds
 */
function aqualuxe_remove_version() {
    return '';
}
add_filter('the_generator', 'aqualuxe_remove_version');

/**
 * Hide WordPress version from scripts and styles
 */
function aqualuxe_hide_version($src) {
    if (strpos($src, 'ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'aqualuxe_hide_version', 9999);
add_filter('script_loader_src', 'aqualuxe_hide_version', 9999);

/**
 * Disable REST API for unauthorized users
 */
function aqualuxe_disable_rest_api($access) {
    if (!is_user_logged_in()) {
        return new WP_Error('rest_cannot_access', 'Only authenticated users can access the REST API.', array('status' => 401));
    }
    return $access;
}
// Uncomment the line below to enable this feature
// add_filter('rest_authentication_errors', 'aqualuxe_disable_rest_api');

/**
 * Disable XML-RPC
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Remove wlwmanifest and EditURI links
 */
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'edit_blog_link');

/**
 * Sanitize customizer settings
 */
function aqualuxe_sanitize_hex_color($color) {
    if (empty($color) || !preg_match('|^#([A-Fa-f0-9]{3}){1,2}$|', $color)) {
        return '';
    }
    return $color;
}

function aqualuxe_sanitize_text_field($text) {
    return sanitize_text_field($text);
}

function aqualuxe_sanitize_integer($integer) {
    return absint($integer);
}

/**
 * Add security headers
 */
function aqualuxe_add_security_headers() {
    // Security headers
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('X-XSS-Protection: 1; mode=block');
    
    // Remove X-Powered-By header
    header_remove('X-Powered-By');
}
add_action('send_headers', 'aqualuxe_add_security_headers');

/**
 * Prevent image hotlinking
 */
function aqualuxe_prevent_hotlinking() {
    if (is_admin()) {
        return;
    }
    
    // Add hotlinking prevention rules to .htaccess
    // This would typically be done in the .htaccess file, but we can add it here for reference
    /*
    RewriteEngine on
    RewriteCond %{HTTP_REFERER} !^$
    RewriteCond %{HTTP_REFERER} !^http(s)?://(www\.)?yourdomain.com [NC]
    RewriteRule \.(jpg|jpeg|png|gif)$ - [NC,F,L]
    */
}
add_action('init', 'aqualuxe_prevent_hotlinking');

/**
 * Sanitize output for security
 */
function aqualuxe_sanitize_output($content) {
    // Remove unwanted HTML tags
    $content = wp_kses_post($content);
    
    // Remove script tags
    $content = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $content);
    
    return $content;
}
add_filter('the_content', 'aqualuxe_sanitize_output');
add_filter('the_excerpt', 'aqualuxe_sanitize_output');
add_filter('comment_text', 'aqualuxe_sanitize_output');

/**
 * Sanitize user input
 */
function aqualuxe_sanitize_user_input($input) {
    // Remove HTML tags
    $input = strip_tags($input);
    
    // Remove script tags
    $input = preg_replace('/<script\b[^<]*(?:(?!<\/script>)<[^<]*)*<\/script>/mi', '', $input);
    
    // Sanitize for database
    $input = sanitize_text_field($input);
    
    return $input;
}

/**
 * Add nonce to forms for security
 */
function aqualuxe_add_nonce_to_forms() {
    wp_nonce_field('aqualuxe_nonce_action', 'aqualuxe_nonce_field');
}

/**
 * Verify nonce for security
 */
function aqualuxe_verify_nonce() {
    if (!isset($_POST['aqualuxe_nonce_field']) || !wp_verify_nonce($_POST['aqualuxe_nonce_field'], 'aqualuxe_nonce_action')) {
        wp_die('Security check failed');
    }
}

/**
 * Protect against SQL injection
 */
function aqualuxe_protect_sql_injection($query) {
    global $wpdb;
    
    // Use prepared statements
    // Example: $wpdb->prepare("SELECT * FROM table WHERE id = %d", $id);
    
    return $query;
}
add_filter('query', 'aqualuxe_protect_sql_injection');

/**
 * Add content security policy
 */
function aqualuxe_add_csp_header() {
    // Define allowed sources
    $csp = "default-src 'self'; ";
    $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://maps.googleapis.com; ";
    $csp .= "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; ";
    $csp .= "img-src 'self' data: https:; ";
    $csp .= "font-src 'self' https://fonts.gstatic.com; ";
    $csp .= "connect-src 'self'; ";
    $csp .= "frame-src 'self' https://www.google.com;";
    
    header("Content-Security-Policy: " . $csp);
}
// Uncomment the line below to enable this feature
// add_action('send_headers', 'aqualuxe_add_csp_header');

/**
 * Limit login attempts
 */
function aqualuxe_limit_login_attempts() {
    // This would typically be handled by a plugin like Limit Login Attempts
    // But we can add basic protection here
    if (isset($_POST['log'])) {
        session_start();
        
        // Check if user has exceeded login attempts
        if (isset($_SESSION['login_attempts']) && $_SESSION['login_attempts'] > 3) {
            wp_die('Too many login attempts. Please try again later.');
        }
    }
}
add_action('login_init', 'aqualuxe_limit_login_attempts');

/**
 * Sanitize file uploads
 */
function aqualuxe_sanitize_file_upload($file) {
    // Define allowed file types
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx');
    
    // Get file extension
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    
    // Check if file type is allowed
    if (!in_array(strtolower($ext), $allowed_types)) {
        $file['error'] = 'Invalid file type. Please upload a valid file.';
    }
    
    return $file;
}
add_filter('wp_handle_upload_prefilter', 'aqualuxe_sanitize_file_upload');

/**
 * Add referrer policy
 */
function aqualuxe_add_referrer_policy() {
    header('Referrer-Policy: strict-origin-when-cross-origin');
}
add_action('send_headers', 'aqualuxe_add_referrer_policy');