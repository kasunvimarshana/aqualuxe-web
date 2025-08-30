# AquaLuxe Theme Security Guide

## Overview
This document outlines the security measures and best practices implemented in the AquaLuxe WooCommerce child theme. It provides guidance on maintaining security standards and protecting against common vulnerabilities.

## Security Principles

### 1. Defense in Depth
Multiple layers of security to protect against various attack vectors:
- Input validation and sanitization
- Output escaping
- Authentication and authorization
- Secure coding practices
- Regular updates and patches

### 2. Principle of Least Privilege
Users and processes should have only the minimum permissions necessary:
- Role-based access control
- Capability checks for sensitive operations
- Secure file permissions
- Restricted admin functionality

### 3. Secure by Default
Security measures enabled by default:
- Nonce verification for forms
- Data validation for all inputs
- Secure headers
- Protection against common attacks

## 1. Input Validation and Sanitization

### 1.1 Data Validation

#### Form Inputs
```php
// Validate and sanitize form data
function aqualuxe_validate_form_data($data) {
    $validated_data = array();
    
    // Validate email
    if (isset($data['email'])) {
        $email = sanitize_email($data['email']);
        if (is_email($email)) {
            $validated_data['email'] = $email;
        } else {
            // Handle invalid email
            return new WP_Error('invalid_email', 'Invalid email address');
        }
    }
    
    // Validate text input
    if (isset($data['name'])) {
        $validated_data['name'] = sanitize_text_field($data['name']);
    }
    
    // Validate numeric input
    if (isset($data['quantity'])) {
        $quantity = absint($data['quantity']);
        if ($quantity > 0) {
            $validated_data['quantity'] = $quantity;
        }
    }
    
    return $validated_data;
}
```

#### Customizer Settings
```php
// Sanitize customizer settings
function aqualuxe_sanitize_customizer_setting($value, $setting) {
    switch ($setting->id) {
        case 'aqualuxe_color_scheme':
            // Validate against allowed color schemes
            $allowed_schemes = array('blue', 'green', 'purple');
            if (in_array($value, $allowed_schemes)) {
                return $value;
            }
            return 'blue'; // Default fallback
            
        case 'aqualuxe_header_layout':
            // Validate against allowed layouts
            $allowed_layouts = array('standard', 'sticky', 'minimal');
            if (in_array($value, $allowed_layouts)) {
                return $value;
            }
            return 'standard'; // Default fallback
            
        default:
            return sanitize_text_field($value);
    }
}
```

### 1.2 Data Sanitization

#### Text Data
```php
// Sanitize text data
function aqualuxe_sanitize_text($text) {
    // Remove HTML tags
    $text = wp_strip_all_tags($text);
    
    // Convert special characters
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    
    // Limit length
    $text = substr($text, 0, 255);
    
    return $text;
}
```

#### URL Data
```php
// Sanitize URL data
function aqualuxe_sanitize_url($url) {
    // Validate URL format
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return '';
    }
    
    // Ensure HTTPS
    $url = set_url_scheme($url, 'https');
    
    // Sanitize with WordPress function
    return esc_url_raw($url);
}
```

#### File Uploads
```php
// Validate file uploads
function aqualuxe_validate_file_upload($file) {
    // Check file type
    $allowed_types = array('jpg', 'jpeg', 'png', 'gif');
    $file_type = wp_check_filetype($file['name']);
    
    if (!in_array($file_type['ext'], $allowed_types)) {
        return new WP_Error('invalid_file_type', 'Invalid file type');
    }
    
    // Check file size (max 2MB)
    if ($file['size'] > 2 * 1024 * 1024) {
        return new WP_Error('file_too_large', 'File too large');
    }
    
    return true;
}
```

## 2. Output Escaping

### 2.1 HTML Escaping
```php
// Escape HTML output
function aqualuxe_escape_html($content) {
    return esc_html($content);
}

// Example usage in template
echo '<h1>' . esc_html(get_the_title()) . '</h1>';
```

### 2.2 URL Escaping
```php
// Escape URLs
function aqualuxe_escape_url($url) {
    return esc_url($url);
}

// Example usage
echo '<a href="' . esc_url(get_permalink()) . '">Read More</a>';
```

### 2.3 Attribute Escaping
```php
// Escape HTML attributes
function aqualuxe_escape_attribute($value) {
    return esc_attr($value);
}

// Example usage
echo '<div class="' . esc_attr($css_class) . '">Content</div>';
```

### 2.4 JavaScript Escaping
```php
// Escape JavaScript data
function aqualuxe_escape_js($data) {
    return esc_js($data);
}

// Example usage
echo '<script>var productData = ' . json_encode($product_data) . ';</script>';
```

## 3. Authentication and Authorization

### 3.1 Nonce Verification

#### Form Nonces
```php
// Create nonce for forms
function aqualuxe_create_nonce($action = 'aqualuxe_action') {
    return wp_create_nonce($action);
}

// Verify nonce
function aqualuxe_verify_nonce($nonce, $action = 'aqualuxe_action') {
    return wp_verify_nonce($nonce, $action);
}

// Example form with nonce
function aqualuxe_render_form() {
    ?>
    <form method="post" action="">
        <?php wp_nonce_field('aqualuxe_save_settings', 'aqualuxe_nonce'); ?>
        <input type="text" name="setting_value" />
        <input type="submit" value="Save" />
    </form>
    <?php
}

// Process form with nonce verification
function aqualuxe_process_form() {
    if (!isset($_POST['aqualuxe_nonce']) || 
        !wp_verify_nonce($_POST['aqualuxe_nonce'], 'aqualuxe_save_settings')) {
        wp_die('Security check failed');
    }
    
    // Process form data
    $setting_value = sanitize_text_field($_POST['setting_value']);
    update_option('aqualuxe_setting', $setting_value);
}
```

#### AJAX Nonces
```php
// Localize nonce for AJAX
function aqualuxe_localize_script() {
    wp_localize_script('aqualuxe-scripts', 'aqualuxe_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('aqualuxe_ajax_nonce')
    ));
}

// Verify AJAX nonce
function aqualuxe_verify_ajax_nonce() {
    if (!wp_verify_nonce($_POST['nonce'], 'aqualuxe_ajax_nonce')) {
        wp_die('Security check failed');
    }
}
```

### 3.2 Capability Checks

#### Admin Functions
```php
// Check user capabilities
function aqualuxe_check_capabilities($capability = 'manage_options') {
    if (!current_user_can($capability)) {
        wp_die('Insufficient permissions');
    }
}

// Example admin function
function aqualuxe_admin_function() {
    // Check capabilities
    aqualuxe_check_capabilities('manage_options');
    
    // Perform admin action
    // ...
}
```

#### Role-Based Access
```php
// Check user roles
function aqualuxe_check_user_role($role) {
    $user = wp_get_current_user();
    return in_array($role, (array) $user->roles);
}

// Example role-based content
function aqualuxe_role_based_content() {
    if (aqualuxe_check_user_role('administrator')) {
        // Admin-only content
        echo '<div class="admin-content">Admin content</div>';
    } elseif (aqualuxe_check_user_role('customer')) {
        // Customer content
        echo '<div class="customer-content">Customer content</div>';
    }
}
```

## 4. Secure Coding Practices

### 4.1 SQL Injection Prevention

#### Prepared Statements
```php
// Use WordPress database methods
global $wpdb;

// Safe query with prepared statement
$results = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT * FROM {$wpdb->prefix}aqualuxe_data WHERE user_id = %d AND status = %s",
        $user_id,
        $status
    )
);

// Safe insert with prepared statement
$wpdb->insert(
    $wpdb->prefix . 'aqualuxe_data',
    array(
        'user_id' => $user_id,
        'data' => $data,
        'created_at' => current_time('mysql')
    ),
    array('%d', '%s', '%s')
);
```

#### Input Validation for Database
```php
// Validate database inputs
function aqualuxe_validate_db_input($input, $type = 'text') {
    switch ($type) {
        case 'int':
            return absint($input);
        case 'text':
            return sanitize_text_field($input);
        case 'email':
            return sanitize_email($input);
        case 'url':
            return esc_url_raw($input);
        default:
            return sanitize_text_field($input);
    }
}
```

### 4.2 Cross-Site Scripting (XSS) Prevention

#### Content Security Policy
```php
// Add Content Security Policy headers
function aqualuxe_add_csp_headers() {
    header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");
}

add_action('send_headers', 'aqualuxe_add_csp_headers');
```

#### Output Sanitization
```php
// Sanitize user-generated content
function aqualuxe_sanitize_user_content($content) {
    // Remove dangerous HTML tags
    $allowed_tags = array(
        'a' => array('href' => array(), 'title' => array()),
        'br' => array(),
        'em' => array(),
        'strong' => array(),
        'p' => array(),
        'ul' => array(),
        'ol' => array(),
        'li' => array()
    );
    
    return wp_kses($content, $allowed_tags);
}
```

### 4.3 Cross-Site Request Forgery (CSRF) Prevention

#### Form Tokens
```php
// Generate and verify CSRF tokens
class AquaLuxe_CSRF {
    private static $token_name = 'aqualuxe_csrf_token';
    
    public static function generate_token() {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        $token = wp_generate_password(32, false);
        $_SESSION[self::$token_name] = $token;
        
        return $token;
    }
    
    public static function verify_token($token) {
        if (!isset($_SESSION)) {
            session_start();
        }
        
        if (!isset($_SESSION[self::$token_name])) {
            return false;
        }
        
        return hash_equals($_SESSION[self::$token_name], $token);
    }
    
    public static function get_token_field() {
        $token = self::generate_token();
        return '<input type="hidden" name="' . self::$token_name . '" value="' . $token . '">';
    }
}
```

## 5. File and Directory Security

### 5.1 Secure File Permissions

#### Directory Permissions
```bash
# Set secure permissions
find /path/to/aqualuxe -type d -exec chmod 755 {} \;
find /path/to/aqualuxe -type f -exec chmod 644 {} \;

# Secure configuration files
chmod 600 /path/to/aqualuxe/config.php
```

#### WordPress Specific Files
```bash
# Secure wp-config.php
chmod 600 wp-config.php

# Secure .htaccess
chmod 644 .htaccess

# Secure uploads directory
chmod 755 wp-content/uploads
```

### 5.2 File Access Protection

#### .htaccess Security
```apache
# Protect theme files
<Files "functions.php">
    Order Allow,Deny
    Deny from all
</Files>

<Files "style.css">
    Order Allow,Deny
    Allow from all
</Files>

# Prevent direct access to PHP files
<Files "*.php">
    Order Allow,Deny
    Deny from all
</Files>

# Allow access to specific files
<Files "index.php">
    Order Allow,Deny
    Allow from all
</Files>
```

#### WordPress Security Functions
```php
// Prevent direct file access
if (!defined('ABSPATH')) {
    exit('Direct access forbidden');
}

// Protect sensitive files
function aqualuxe_protect_sensitive_files() {
    $protected_files = array(
        'functions.php',
        'customizer.php',
        'template-functions.php'
    );
    
    $current_file = basename($_SERVER['PHP_SELF']);
    
    if (in_array($current_file, $protected_files)) {
        wp_die('Direct access forbidden');
    }
}
```

## 6. WooCommerce Security

### 6.1 Product Data Security

#### Product Validation
```php
// Validate product data
function aqualuxe_validate_product_data($product_data) {
    $validated_data = array();
    
    // Validate product name
    if (isset($product_data['name'])) {
        $validated_data['name'] = sanitize_text_field($product_data['name']);
    }
    
    // Validate product price
    if (isset($product_data['price'])) {
        $price = floatval($product_data['price']);
        if ($price >= 0) {
            $validated_data['price'] = $price;
        }
    }
    
    // Validate product description
    if (isset($product_data['description'])) {
        $validated_data['description'] = aqualuxe_sanitize_user_content($product_data['description']);
    }
    
    return $validated_data;
}
```

#### Order Security
```php
// Validate order data
function aqualuxe_validate_order_data($order_data) {
    $validated_data = array();
    
    // Validate order status
    $allowed_statuses = array('pending', 'processing', 'completed', 'cancelled');
    if (isset($order_data['status']) && in_array($order_data['status'], $allowed_statuses)) {
        $validated_data['status'] = $order_data['status'];
    }
    
    // Validate customer data
    if (isset($order_data['customer_id'])) {
        $customer_id = absint($order_data['customer_id']);
        if (get_user_by('id', $customer_id)) {
            $validated_data['customer_id'] = $customer_id;
        }
    }
    
    return $validated_data;
}
```

### 6.2 Payment Security

#### Payment Form Security
```php
// Secure payment form handling
function aqualuxe_secure_payment_form() {
    // Verify nonce
    if (!wp_verify_nonce($_POST['payment_nonce'], 'aqualuxe_payment')) {
        wp_die('Security check failed');
    }
    
    // Validate payment data
    $payment_data = array();
    $payment_data['amount'] = floatval($_POST['amount']);
    $payment_data['currency'] = sanitize_text_field($_POST['currency']);
    
    // Process payment securely
    // ...
}
```

## 7. Security Headers and HTTP Security

### 7.1 Security Headers

#### WordPress Header Functions
```php
// Add security headers
function aqualuxe_add_security_headers() {
    // X-Content-Type-Options
    header('X-Content-Type-Options: nosniff');
    
    // X-Frame-Options
    header('X-Frame-Options: SAMEORIGIN');
    
    // X-XSS-Protection
    header('X-XSS-Protection: 1; mode=block');
    
    // Referrer-Policy
    header('Referrer-Policy: strict-origin-when-cross-origin');
    
    // Permissions-Policy
    header('Permissions-Policy: geolocation=(), microphone=(), camera=()');
}

add_action('send_headers', 'aqualuxe_add_security_headers');
```

### 7.2 HTTPS Enforcement

#### SSL Redirect
```php
// Force HTTPS
function aqualuxe_force_https() {
    if (!is_ssl() && !preg_match('/^192\.168\.|^10\.|^172\.(1[6-9]|2[0-9]|3[01])\./', $_SERVER['REMOTE_ADDR'])) {
        if (0 === strpos($_SERVER['REQUEST_URI'], '/wp-admin') || 0 === strpos($_SERVER['REQUEST_URI'], '/wp-login.php')) {
            if (force_ssl_admin() && !is_admin()) {
                if (!is_ssl()) {
                    wp_redirect(set_url_scheme($_SERVER['REQUEST_URI'], 'https'));
                    exit();
                }
            }
        }
    }
}

add_action('init', 'aqualuxe_force_https');
```

## 8. Security Monitoring and Logging

### 8.1 Security Logging

#### Security Event Logging
```php
// Log security events
function aqualuxe_log_security_event($event, $data = array()) {
    $log_entry = array(
        'timestamp' => current_time('mysql'),
        'event' => $event,
        'user_id' => get_current_user_id(),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'],
        'data' => $data
    );
    
    // Log to file or database
    error_log('AquaLuxe Security Event: ' . json_encode($log_entry));
}

// Example usage
function aqualuxe_log_failed_login() {
    aqualuxe_log_security_event('failed_login', array(
        'username' => $_POST['username'] ?? '',
        'referer' => $_SERVER['HTTP_REFERER'] ?? ''
    ));
}
```

### 8.2 Security Audits

#### Regular Security Checks
```php
// Perform security audit
function aqualuxe_security_audit() {
    $audit_results = array();
    
    // Check file permissions
    $audit_results['file_permissions'] = aqualuxe_check_file_permissions();
    
    // Check for vulnerable plugins
    $audit_results['vulnerable_plugins'] = aqualuxe_check_vulnerable_plugins();
    
    // Check security headers
    $audit_results['security_headers'] = aqualuxe_check_security_headers();
    
    return $audit_results;
}

// Check file permissions
function aqualuxe_check_file_permissions() {
    $checks = array();
    
    // Check wp-config.php permissions
    $wp_config_perms = fileperms(ABSPATH . 'wp-config.php');
    $checks['wp_config_permissions'] = ($wp_config_perms & 0777) === 0600;
    
    return $checks;
}
```

## 9. Vulnerability Prevention

### 9.1 Common Vulnerabilities

#### SQL Injection Prevention
```php
// Always use prepared statements
function aqualuxe_safe_query($user_id, $status) {
    global $wpdb;
    
    return $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM {$wpdb->prefix}aqualuxe_orders WHERE user_id = %d AND status = %s",
            $user_id,
            $status
        )
    );
}
```

#### XSS Prevention
```php
// Always escape output
function aqualuxe_safe_output($content) {
    return esc_html($content);
}

// For URLs
function aqualuxe_safe_url($url) {
    return esc_url($url);
}

// For attributes
function aqualuxe_safe_attribute($value) {
    return esc_attr($value);
}
```

#### CSRF Prevention
```php
// Always use nonces
function aqualuxe_secure_form() {
    ?>
    <form method="post">
        <?php wp_nonce_field('aqualuxe_action', 'aqualuxe_nonce'); ?>
        <!-- form fields -->
    </form>
    <?php
}

function aqualuxe_verify_form() {
    if (!isset($_POST['aqualuxe_nonce']) || 
        !wp_verify_nonce($_POST['aqualuxe_nonce'], 'aqualuxe_action')) {
        wp_die('Security check failed');
    }
}
```

## 10. Security Best Practices

### 10.1 Development Practices

#### Code Review Checklist
- [ ] All inputs are validated and sanitized
- [ ] All outputs are escaped
- [ ] Nonces are used for form submissions
- [ ] Capability checks are implemented
- [ ] SQL queries use prepared statements
- [ ] File uploads are validated
- [ ] Error messages don't expose sensitive information
- [ ] Security headers are implemented
- [ ] HTTPS is enforced
- [ ] Security logging is in place

#### Regular Maintenance
- [ ] Update WordPress core regularly
- [ ] Update themes and plugins regularly
- [ ] Monitor security advisories
- [ ] Perform security audits
- [ ] Review file permissions
- [ ] Check for vulnerable code
- [ ] Update security measures
- [ ] Test security features

### 10.2 User Education

#### Admin Security Guidelines
- Use strong passwords
- Enable two-factor authentication
- Keep software updated
- Monitor user accounts
- Review security logs
- Limit admin access
- Backup regularly
- Scan for malware

#### Developer Security Guidelines
- Follow secure coding practices
- Validate all inputs
- Escape all outputs
- Use WordPress security functions
- Implement proper error handling
- Test for vulnerabilities
- Keep dependencies updated
- Document security measures

## Conclusion

The AquaLuxe theme implements comprehensive security measures to protect against common vulnerabilities and ensure a secure user experience. By following the security principles and best practices outlined in this guide, developers can maintain the theme's security standards and protect user data.

Key security features include:
1. **Input Validation**: All data is validated and sanitized
2. **Output Escaping**: All outputs are properly escaped
3. **Authentication**: Nonce verification and capability checks
4. **Secure Coding**: Prevention of SQL injection, XSS, and CSRF
5. **File Security**: Proper file permissions and access controls
6. **Security Headers**: Implementation of security HTTP headers
7. **Monitoring**: Security logging and audit capabilities

Regular review and updates of these security measures will ensure that the AquaLuxe theme continues to provide a secure environment for users and administrators.