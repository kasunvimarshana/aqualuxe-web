<?php
/**
 * Security Enhancements for AquaLuxe Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class AquaLuxe_Security {
    
    /**
     * Constructor
     */
    public function __construct() {
        $this->init_security_measures();
    }
    
    /**
     * Initialize security measures
     */
    private function init_security_measures() {
        // Remove WordPress version information
        add_filter('the_generator', '__return_empty_string');
        remove_action('wp_head', 'wp_generator');
        
        // Remove WooCommerce version
        add_filter('woocommerce_generator_content', '__return_empty_string');
        
        // Disable XML-RPC
        add_filter('xmlrpc_enabled', '__return_false');
        
        // Remove REST API links from head
        remove_action('wp_head', 'rest_output_link_wp_head', 10);
        remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
        
        // Disable file editing from admin
        if (!defined('DISALLOW_FILE_EDIT')) {
            define('DISALLOW_FILE_EDIT', true);
        }
        
        // Hide login errors
        add_filter('login_errors', array($this, 'hide_login_errors'));
        
        // Add security headers
        add_action('send_headers', array($this, 'add_security_headers'));
        
        // Sanitize file uploads
        add_filter('wp_handle_upload_prefilter', array($this, 'sanitize_file_uploads'));
        
        // Prevent directory browsing
        add_action('init', array($this, 'prevent_directory_browsing'));
        
        // Add nonce to forms
        add_action('wp_footer', array($this, 'add_nonce_to_forms'));
        
        // Content Security Policy
        add_action('wp_head', array($this, 'add_content_security_policy'), 1);
        
        // Input sanitization and validation
        add_action('init', array($this, 'sanitize_global_inputs'));
    }
    
    /**
     * Hide login error messages
     */
    public function hide_login_errors($error) {
        return __('Invalid login credentials. Please try again.', 'aqualuxe');
    }
    
    /**
     * Add security headers
     */
    public function add_security_headers() {
        // Prevent MIME type sniffing
        header('X-Content-Type-Options: nosniff');
        
        // Prevent clickjacking
        header('X-Frame-Options: SAMEORIGIN');
        
        // Enable XSS protection
        header('X-XSS-Protection: 1; mode=block');
        
        // Referrer policy
        header('Referrer-Policy: strict-origin-when-cross-origin');
        
        // Feature policy
        header("Feature-Policy: camera 'none'; microphone 'none'; geolocation 'self'");
        
        // Permissions policy (newer version of feature policy)
        header("Permissions-Policy: camera=(), microphone=(), geolocation=(self)");
        
        // HSTS (only if using HTTPS)
        if (is_ssl()) {
            header('Strict-Transport-Security: max-age=31536000; includeSubDomains; preload');
        }
    }
    
    /**
     * Sanitize file uploads
     */
    public function sanitize_file_uploads($file) {
        // Allowed file types
        $allowed_types = array(
            'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg',
            'pdf', 'doc', 'docx', 'txt'
        );
        
        // Get file extension
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Check if file type is allowed
        if (!in_array($file_ext, $allowed_types)) {
            $file['error'] = __('File type not allowed.', 'aqualuxe');
            return $file;
        }
        
        // Sanitize file name
        $file['name'] = sanitize_file_name($file['name']);
        
        // Additional security checks
        $file_content = file_get_contents($file['tmp_name']);
        
        // Check for PHP code in files
        if (strpos($file_content, '<?php') !== false || strpos($file_content, '<?=') !== false) {
            $file['error'] = __('Suspicious file content detected.', 'aqualuxe');
            return $file;
        }
        
        return $file;
    }
    
    /**
     * Prevent directory browsing
     */
    public function prevent_directory_browsing() {
        $htaccess_file = ABSPATH . '.htaccess';
        
        if (is_writable($htaccess_file)) {
            $rules = "\n# AquaLuxe Security Rules\n";
            $rules .= "Options -Indexes\n";
            $rules .= "<Files wp-config.php>\n";
            $rules .= "order allow,deny\n";
            $rules .= "deny from all\n";
            $rules .= "</Files>\n";
            
            $current_rules = file_get_contents($htaccess_file);
            
            if (strpos($current_rules, '# AquaLuxe Security Rules') === false) {
                file_put_contents($htaccess_file, $rules . $current_rules);
            }
        }
    }
    
    /**
     * Add nonce to forms
     */
    public function add_nonce_to_forms() {
        if (is_admin()) {
            return;
        }
        ?>
        <script>
        jQuery(document).ready(function($) {
            // Add nonce to AJAX forms
            $('form[data-ajax="true"]').each(function() {
                if (!$(this).find('input[name="aqualuxe_nonce"]').length) {
                    $(this).append('<input type="hidden" name="aqualuxe_nonce" value="<?php echo wp_create_nonce('aqualuxe_form_nonce'); ?>">');
                }
            });
            
            // Validate forms before submission
            $('form').on('submit', function(e) {
                var form = $(this);
                var isValid = true;
                
                // Check for required fields
                form.find('[required]').each(function() {
                    if (!$(this).val().trim()) {
                        isValid = false;
                        $(this).addClass('error');
                    } else {
                        $(this).removeClass('error');
                    }
                });
                
                // Email validation
                form.find('input[type="email"]').each(function() {
                    var email = $(this).val();
                    var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    
                    if (email && !emailRegex.test(email)) {
                        isValid = false;
                        $(this).addClass('error');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('<?php echo esc_js(__('Please fill in all required fields correctly.', 'aqualuxe')); ?>');
                }
            });
        });
        </script>
        <?php
    }
    
    /**
     * Add Content Security Policy
     */
    public function add_content_security_policy() {
        $csp_directives = array(
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com *.google.com *.doubleclick.net",
            "style-src 'self' 'unsafe-inline' *.googleapis.com fonts.googleapis.com",
            "font-src 'self' fonts.gstatic.com fonts.googleapis.com",
            "img-src 'self' data: *.gravatar.com *.wp.com *.googleapis.com *.gstatic.com",
            "media-src 'self'",
            "frame-src 'self' *.youtube.com *.vimeo.com",
            "connect-src 'self'"
        );
        
        $csp = implode('; ', $csp_directives);
        echo '<meta http-equiv="Content-Security-Policy" content="' . esc_attr($csp) . '">' . "\n";
    }
    
    /**
     * Sanitize global inputs
     */
    public function sanitize_global_inputs() {
        // Sanitize GET parameters
        if (!empty($_GET)) {
            foreach ($_GET as $key => $value) {
                $_GET[$key] = $this->deep_sanitize($value);
            }
        }
        
        // Sanitize POST parameters (except for specific cases)
        if (!empty($_POST) && !wp_doing_ajax()) {
            foreach ($_POST as $key => $value) {
                if (!in_array($key, array('content', 'description'))) {
                    $_POST[$key] = $this->deep_sanitize($value);
                }
            }
        }
    }
    
    /**
     * Deep sanitize arrays and strings
     */
    private function deep_sanitize($data) {
        if (is_array($data)) {
            return array_map(array($this, 'deep_sanitize'), $data);
        } else {
            return sanitize_text_field($data);
        }
    }
    
    /**
     * Validate and sanitize user input
     */
    public static function validate_input($input, $type = 'text') {
        switch ($type) {
            case 'email':
                return sanitize_email($input);
            
            case 'url':
                return esc_url_raw($input);
            
            case 'int':
                return intval($input);
            
            case 'float':
                return floatval($input);
            
            case 'html':
                return wp_kses_post($input);
            
            case 'textarea':
                return sanitize_textarea_field($input);
            
            case 'slug':
                return sanitize_title($input);
            
            default:
                return sanitize_text_field($input);
        }
    }
    
    /**
     * Generate secure token
     */
    public static function generate_secure_token($length = 32) {
        if (function_exists('random_bytes')) {
            return bin2hex(random_bytes($length / 2));
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            return bin2hex(openssl_random_pseudo_bytes($length / 2));
        } else {
            return wp_generate_password($length, false);
        }
    }
    
    /**
     * Rate limiting for login attempts
     */
    public static function check_rate_limit($identifier, $max_attempts = 5, $time_window = 900) {
        $transient_key = 'rate_limit_' . md5($identifier);
        $attempts = get_transient($transient_key);
        
        if ($attempts === false) {
            $attempts = 1;
            set_transient($transient_key, $attempts, $time_window);
            return true;
        }
        
        if ($attempts >= $max_attempts) {
            return false;
        }
        
        $attempts++;
        set_transient($transient_key, $attempts, $time_window);
        return true;
    }
}

// Initialize security measures
new AquaLuxe_Security();

/**
 * Security helper functions
 */

/**
 * Verify nonce for forms
 */
function aqualuxe_verify_nonce($nonce, $action = 'aqualuxe_form_nonce') {
    return wp_verify_nonce($nonce, $action);
}

/**
 * Escape output for display
 */
function aqualuxe_escape_output($data, $context = 'html') {
    switch ($context) {
        case 'html':
            return esc_html($data);
        
        case 'attr':
            return esc_attr($data);
        
        case 'url':
            return esc_url($data);
        
        case 'js':
            return esc_js($data);
        
        case 'textarea':
            return esc_textarea($data);
        
        default:
            return esc_html($data);
    }
}

/**
 * Log security events
 */
function aqualuxe_log_security_event($event, $data = array()) {
    if (!defined('WP_DEBUG') || !WP_DEBUG) {
        return;
    }
    
    $log_entry = array(
        'timestamp' => current_time('mysql'),
        'event' => $event,
        'data' => $data,
        'user_ip' => $_SERVER['REMOTE_ADDR'] ?? 'unknown',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown'
    );
    
    error_log('[AQUALUXE SECURITY] ' . json_encode($log_entry));
}