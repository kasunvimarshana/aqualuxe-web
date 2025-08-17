<?php
/**
 * AquaLuxe Security Functions
 *
 * Functions for implementing security best practices
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Remove WordPress version number
 */
function aqualuxe_remove_version_info() {
    return '';
}
add_filter( 'the_generator', 'aqualuxe_remove_version_info' );

/**
 * Disable XML-RPC
 */
function aqualuxe_disable_xmlrpc() {
    // Disable XML-RPC methods that require authentication
    add_filter( 'xmlrpc_enabled', '__return_false' );
    
    // Disable X-Pingback header
    add_filter( 'wp_headers', function( $headers ) {
        unset( $headers['X-Pingback'] );
        return $headers;
    } );
    
    // Disable pingbacks
    add_filter( 'xmlrpc_methods', function( $methods ) {
        unset( $methods['pingback.ping'] );
        unset( $methods['pingback.extensions.getPingbacks'] );
        return $methods;
    } );
}
add_action( 'init', 'aqualuxe_disable_xmlrpc' );

/**
 * Add security headers
 */
function aqualuxe_security_headers() {
    // X-Content-Type-Options
    header( 'X-Content-Type-Options: nosniff' );
    
    // X-Frame-Options
    header( 'X-Frame-Options: SAMEORIGIN' );
    
    // X-XSS-Protection
    header( 'X-XSS-Protection: 1; mode=block' );
    
    // Referrer-Policy
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
    
    // Permissions-Policy (formerly Feature-Policy)
    header( 'Permissions-Policy: geolocation=(self), microphone=(), camera=(), fullscreen=(self)' );
    
    // Content-Security-Policy
    $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.google-analytics.com https://www.googletagmanager.com https://maps.googleapis.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; img-src 'self' data: https://secure.gravatar.com https://www.google-analytics.com https://maps.googleapis.com https://maps.gstatic.com; font-src 'self' https://fonts.gstatic.com; connect-src 'self' https://www.google-analytics.com; frame-src 'self' https://www.youtube.com; object-src 'none'";
    
    // Only add CSP header if the option is enabled
    if ( get_theme_mod( 'aqualuxe_enable_csp', false ) ) {
        header( "Content-Security-Policy: $csp" );
    }
}
add_action( 'send_headers', 'aqualuxe_security_headers' );

/**
 * Disable file editing in admin
 */
function aqualuxe_disable_file_editing() {
    if ( ! defined( 'DISALLOW_FILE_EDIT' ) ) {
        define( 'DISALLOW_FILE_EDIT', true );
    }
}
add_action( 'init', 'aqualuxe_disable_file_editing' );

/**
 * Prevent username enumeration
 */
function aqualuxe_prevent_username_enumeration( $redirect_to, $requested_redirect_to, $user ) {
    if ( isset( $_REQUEST['author'] ) && ! is_admin() ) {
        wp_redirect( home_url(), 301 );
        exit;
    }
    
    return $redirect_to;
}
add_filter( 'login_redirect', 'aqualuxe_prevent_username_enumeration', 10, 3 );

/**
 * Block username enumeration via REST API
 */
function aqualuxe_block_rest_user_enumeration( $response, $handler, $request ) {
    if ( strpos( $request->get_route(), '/wp/v2/users' ) !== false ) {
        if ( ! current_user_can( 'list_users' ) ) {
            return new WP_Error( 'rest_user_cannot_view', __( 'Sorry, you are not allowed to access this endpoint.', 'aqualuxe' ), array( 'status' => rest_authorization_required_code() ) );
        }
    }
    
    return $response;
}
add_filter( 'rest_request_before_callbacks', 'aqualuxe_block_rest_user_enumeration', 10, 3 );

/**
 * Disable REST API for unauthenticated users
 */
function aqualuxe_restrict_rest_api( $result ) {
    // Allow access to authentication endpoints
    if ( strpos( $_SERVER['REQUEST_URI'], '/wp-json/jwt-auth/' ) !== false ) {
        return $result;
    }
    
    // Check if user is logged in
    if ( ! is_user_logged_in() ) {
        // Get the REST API restriction level from theme options
        $rest_api_restriction = get_theme_mod( 'aqualuxe_rest_api_restriction', 'public' );
        
        // Allow public access
        if ( $rest_api_restriction === 'public' ) {
            return $result;
        }
        
        // Restrict sensitive endpoints
        if ( $rest_api_restriction === 'limited' ) {
            // Block access to users endpoint
            if ( strpos( $_SERVER['REQUEST_URI'], '/wp/v2/users' ) !== false ) {
                return new WP_Error( 'rest_api_restricted', __( 'REST API access restricted.', 'aqualuxe' ), array( 'status' => 401 ) );
            }
            
            // Allow access to other endpoints
            return $result;
        }
        
        // Completely restrict REST API
        if ( $rest_api_restriction === 'private' ) {
            return new WP_Error( 'rest_api_restricted', __( 'REST API access restricted.', 'aqualuxe' ), array( 'status' => 401 ) );
        }
    }
    
    return $result;
}
add_filter( 'rest_authentication_errors', 'aqualuxe_restrict_rest_api' );

/**
 * Disable login error messages
 */
function aqualuxe_disable_login_errors() {
    return __( 'Invalid login credentials.', 'aqualuxe' );
}
add_filter( 'login_errors', 'aqualuxe_disable_login_errors' );

/**
 * Add login protection
 */
function aqualuxe_login_protection() {
    // Limit login attempts
    if ( get_theme_mod( 'aqualuxe_limit_login_attempts', true ) ) {
        // Get the client IP address
        $client_ip = $_SERVER['REMOTE_ADDR'];
        
        // Get the login attempts transient
        $login_attempts = get_transient( 'aqualuxe_login_attempts_' . md5( $client_ip ) );
        
        // If the transient doesn't exist, create it
        if ( false === $login_attempts ) {
            $login_attempts = array(
                'count' => 0,
                'time'  => time(),
            );
        }
        
        // Check if the IP is blocked
        if ( $login_attempts['count'] >= 5 && ( time() - $login_attempts['time'] ) < 3600 ) {
            wp_die( __( 'Too many failed login attempts. Please try again in an hour.', 'aqualuxe' ), __( 'Login Blocked', 'aqualuxe' ), array( 'response' => 403 ) );
        }
    }
}
add_action( 'login_init', 'aqualuxe_login_protection' );

/**
 * Track failed login attempts
 */
function aqualuxe_track_login_attempts( $username ) {
    // Only track if limit login attempts is enabled
    if ( get_theme_mod( 'aqualuxe_limit_login_attempts', true ) ) {
        // Get the client IP address
        $client_ip = $_SERVER['REMOTE_ADDR'];
        
        // Get the login attempts transient
        $login_attempts = get_transient( 'aqualuxe_login_attempts_' . md5( $client_ip ) );
        
        // If the transient doesn't exist, create it
        if ( false === $login_attempts ) {
            $login_attempts = array(
                'count' => 1,
                'time'  => time(),
            );
        } else {
            // Increment the count
            $login_attempts['count']++;
            $login_attempts['time'] = time();
        }
        
        // Set the transient for 1 hour
        set_transient( 'aqualuxe_login_attempts_' . md5( $client_ip ), $login_attempts, 3600 );
    }
}
add_action( 'wp_login_failed', 'aqualuxe_track_login_attempts' );

/**
 * Reset login attempts on successful login
 */
function aqualuxe_reset_login_attempts( $user_login, $user ) {
    // Only reset if limit login attempts is enabled
    if ( get_theme_mod( 'aqualuxe_limit_login_attempts', true ) ) {
        // Get the client IP address
        $client_ip = $_SERVER['REMOTE_ADDR'];
        
        // Delete the login attempts transient
        delete_transient( 'aqualuxe_login_attempts_' . md5( $client_ip ) );
    }
}
add_action( 'wp_login', 'aqualuxe_reset_login_attempts', 10, 2 );

/**
 * Add CAPTCHA to login form
 */
function aqualuxe_add_login_captcha() {
    // Only add CAPTCHA if the option is enabled
    if ( get_theme_mod( 'aqualuxe_login_captcha', false ) ) {
        // Generate random numbers
        $num1 = rand( 1, 10 );
        $num2 = rand( 1, 10 );
        
        // Store the answer in a session
        if ( ! session_id() ) {
            session_start();
        }
        $_SESSION['aqualuxe_captcha_answer'] = $num1 + $num2;
        
        // Output the CAPTCHA field
        ?>
        <p>
            <label for="aqualuxe_captcha"><?php printf( __( 'Captcha: %1$s + %2$s = ?', 'aqualuxe' ), $num1, $num2 ); ?></label>
            <input type="number" name="aqualuxe_captcha" id="aqualuxe_captcha" class="input" required />
        </p>
        <?php
    }
}
add_action( 'login_form', 'aqualuxe_add_login_captcha' );

/**
 * Verify CAPTCHA on login
 */
function aqualuxe_verify_login_captcha( $user, $password ) {
    // Only verify CAPTCHA if the option is enabled
    if ( get_theme_mod( 'aqualuxe_login_captcha', false ) ) {
        // Start session if not started
        if ( ! session_id() ) {
            session_start();
        }
        
        // Check if CAPTCHA is set
        if ( isset( $_SESSION['aqualuxe_captcha_answer'] ) ) {
            // Get the CAPTCHA answer
            $captcha_answer = $_SESSION['aqualuxe_captcha_answer'];
            
            // Check if the CAPTCHA field is set
            if ( ! isset( $_POST['aqualuxe_captcha'] ) || empty( $_POST['aqualuxe_captcha'] ) ) {
                return new WP_Error( 'captcha_required', __( 'Please enter the CAPTCHA.', 'aqualuxe' ) );
            }
            
            // Check if the CAPTCHA is correct
            if ( intval( $_POST['aqualuxe_captcha'] ) !== $captcha_answer ) {
                return new WP_Error( 'captcha_incorrect', __( 'CAPTCHA is incorrect.', 'aqualuxe' ) );
            }
        }
    }
    
    return $user;
}
add_filter( 'authenticate', 'aqualuxe_verify_login_captcha', 10, 2 );

/**
 * Add security to contact forms
 */
function aqualuxe_secure_contact_forms() {
    // Check if Contact Form 7 is active
    if ( class_exists( 'WPCF7' ) ) {
        // Add honeypot field to Contact Form 7 forms
        add_filter( 'wpcf7_form_elements', function( $content ) {
            $content .= '<div class="aqualuxe-honeypot" aria-hidden="true"><input type="text" name="aqualuxe_hp_email" value="" autocomplete="off" tabindex="-1"></div>';
            
            // Add CSS to hide the honeypot field
            $content .= '<style>.aqualuxe-honeypot{position:absolute;left:-9999px;}</style>';
            
            return $content;
        } );
        
        // Validate honeypot field
        add_filter( 'wpcf7_validate', function( $result, $tags ) {
            // Check if the honeypot field is filled
            if ( isset( $_POST['aqualuxe_hp_email'] ) && ! empty( $_POST['aqualuxe_hp_email'] ) ) {
                $result->invalidate( '', __( 'Validation errors occurred. Please check your input.', 'aqualuxe' ) );
            }
            
            return $result;
        }, 10, 2 );
    }
}
add_action( 'init', 'aqualuxe_secure_contact_forms' );

/**
 * Add security to comments
 */
function aqualuxe_secure_comments() {
    // Add honeypot field to comment form
    add_filter( 'comment_form_default_fields', function( $fields ) {
        $fields['aqualuxe_hp_comment'] = '<div class="aqualuxe-honeypot" aria-hidden="true"><input type="text" name="aqualuxe_hp_comment" value="" autocomplete="off" tabindex="-1"></div><style>.aqualuxe-honeypot{position:absolute;left:-9999px;}</style>';
        
        return $fields;
    } );
    
    // Validate honeypot field
    add_filter( 'preprocess_comment', function( $commentdata ) {
        // Check if the honeypot field is filled
        if ( isset( $_POST['aqualuxe_hp_comment'] ) && ! empty( $_POST['aqualuxe_hp_comment'] ) ) {
            wp_die( __( 'Comment submission failed.', 'aqualuxe' ), __( 'Comment Submission Error', 'aqualuxe' ), array( 'response' => 403 ) );
        }
        
        return $commentdata;
    } );
}
add_action( 'init', 'aqualuxe_secure_comments' );

/**
 * Prevent direct access to PHP files
 */
function aqualuxe_prevent_direct_access() {
    // Add code to prevent direct access to PHP files
    $theme_dir = get_template_directory();
    $files = glob( $theme_dir . '/**/*.php' );
    
    foreach ( $files as $file ) {
        // Skip files that already have the check
        $content = file_get_contents( $file );
        
        if ( strpos( $content, 'defined( \'ABSPATH\' ) || exit;' ) !== false || strpos( $content, "defined( 'ABSPATH' ) || die;" ) !== false ) {
            continue;
        }
        
        // Skip files in the vendor directory
        if ( strpos( $file, '/vendor/' ) !== false ) {
            continue;
        }
        
        // Add the check to the file
        $new_content = preg_replace( '/^(<\?php)/', "$1\n\ndefined( 'ABSPATH' ) || exit;\n", $content );
        
        // Write the new content to the file
        file_put_contents( $file, $new_content );
    }
}
// Only run this during theme activation
// add_action( 'after_switch_theme', 'aqualuxe_prevent_direct_access' );

/**
 * Add security to uploads directory
 */
function aqualuxe_secure_uploads_directory() {
    // Get the uploads directory
    $upload_dir = wp_upload_dir();
    
    // Create .htaccess file in uploads directory
    $htaccess_file = $upload_dir['basedir'] . '/.htaccess';
    
    // Check if the file exists
    if ( ! file_exists( $htaccess_file ) ) {
        // Create the .htaccess file
        $htaccess_content = "# Protect against PHP execution in uploads directory
<FilesMatch &quot;\.(php|phtml|php3|php4|php5|php7|phps|phar|pht|phtm|phtml)$&quot;>
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order deny,allow
        Deny from all
    </IfModule>
</FilesMatch>

# Protect against SVG XSS
<FilesMatch &quot;\.(svg)$&quot;>
    <IfModule mod_headers.c>
        Header set Content-Security-Policy &quot;default-src 'none'; script-src 'none'; style-src 'self'; img-src 'self'; font-src 'self'&quot;
    </IfModule>
</FilesMatch>

# Protect against malicious file uploads
<FilesMatch &quot;\.(cgi|pl|py|sh|bash|exe|htaccess|htpasswd)$&quot;>
    <IfModule mod_authz_core.c>
        Require all denied
    </IfModule>
    <IfModule !mod_authz_core.c>
        Order deny,allow
        Deny from all
    </IfModule>
</FilesMatch>";
        
        // Write the content to the file
        file_put_contents( $htaccess_file, $htaccess_content );
    }
}
add_action( 'admin_init', 'aqualuxe_secure_uploads_directory' );

/**
 * Sanitize file uploads
 */
function aqualuxe_sanitize_file_uploads( $file ) {
    // List of allowed file extensions
    $allowed_extensions = array(
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'csv', 'zip'
    );
    
    // Get the file extension
    $file_extension = strtolower( pathinfo( $file['name'], PATHINFO_EXTENSION ) );
    
    // Check if the file extension is allowed
    if ( ! in_array( $file_extension, $allowed_extensions ) ) {
        $file['error'] = __( 'File type not allowed.', 'aqualuxe' );
    }
    
    // Check for PHP code in the file
    if ( in_array( $file_extension, array( 'jpg', 'jpeg', 'png', 'gif', 'webp' ) ) ) {
        $file_content = file_get_contents( $file['tmp_name'] );
        
        // Check for PHP code in the file
        if ( preg_match( '/<\?php/i', $file_content ) ) {
            $file['error'] = __( 'File contains malicious code.', 'aqualuxe' );
        }
    }
    
    return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'aqualuxe_sanitize_file_uploads' );

/**
 * Sanitize SVG uploads
 */
function aqualuxe_sanitize_svg_uploads( $file ) {
    // Check if the file is an SVG
    if ( 'image/svg+xml' === $file['type'] ) {
        // Read the file
        $file_content = file_get_contents( $file['tmp_name'] );
        
        // Check for malicious code
        if ( preg_match( '/<script|javascript:|onclick|onload|onmouseover|eval\(|setTimeout|setInterval/i', $file_content ) ) {
            $file['error'] = __( 'SVG file contains malicious code.', 'aqualuxe' );
        }
    }
    
    return $file;
}
add_filter( 'wp_handle_upload_prefilter', 'aqualuxe_sanitize_svg_uploads' );

/**
 * Add customizer options for security
 */
function aqualuxe_security_customizer_options( $wp_customize ) {
    // Add security section
    $wp_customize->add_section( 'aqualuxe_security', array(
        'title'    => __( 'Security', 'aqualuxe' ),
        'priority' => 120,
    ) );
    
    // Enable Content Security Policy
    $wp_customize->add_setting( 'aqualuxe_enable_csp', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_enable_csp', array(
        'label'    => __( 'Enable Content Security Policy', 'aqualuxe' ),
        'description' => __( 'Adds CSP headers to prevent XSS attacks', 'aqualuxe' ),
        'section'  => 'aqualuxe_security',
        'type'     => 'checkbox',
    ) );
    
    // Limit login attempts
    $wp_customize->add_setting( 'aqualuxe_limit_login_attempts', array(
        'default'           => true,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_limit_login_attempts', array(
        'label'    => __( 'Limit Login Attempts', 'aqualuxe' ),
        'description' => __( 'Blocks IP addresses after 5 failed login attempts', 'aqualuxe' ),
        'section'  => 'aqualuxe_security',
        'type'     => 'checkbox',
    ) );
    
    // Add CAPTCHA to login form
    $wp_customize->add_setting( 'aqualuxe_login_captcha', array(
        'default'           => false,
        'sanitize_callback' => 'aqualuxe_sanitize_checkbox',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_login_captcha', array(
        'label'    => __( 'Add CAPTCHA to Login Form', 'aqualuxe' ),
        'description' => __( 'Adds a simple math CAPTCHA to the login form', 'aqualuxe' ),
        'section'  => 'aqualuxe_security',
        'type'     => 'checkbox',
    ) );
    
    // REST API restriction
    $wp_customize->add_setting( 'aqualuxe_rest_api_restriction', array(
        'default'           => 'public',
        'sanitize_callback' => 'aqualuxe_sanitize_select',
    ) );
    
    $wp_customize->add_control( 'aqualuxe_rest_api_restriction', array(
        'label'    => __( 'REST API Restriction', 'aqualuxe' ),
        'description' => __( 'Restricts access to the REST API', 'aqualuxe' ),
        'section'  => 'aqualuxe_security',
        'type'     => 'select',
        'choices'  => array(
            'public'  => __( 'Public (No restriction)', 'aqualuxe' ),
            'limited' => __( 'Limited (Block sensitive endpoints)', 'aqualuxe' ),
            'private' => __( 'Private (Block all for unauthenticated users)', 'aqualuxe' ),
        ),
    ) );
}
add_action( 'customize_register', 'aqualuxe_security_customizer_options' );

/**
 * Sanitize checkbox
 */
function aqualuxe_sanitize_checkbox( $input ) {
    return ( isset( $input ) && true == $input ) ? true : false;
}

/**
 * Sanitize select
 */
function aqualuxe_sanitize_select( $input, $setting ) {
    $input = sanitize_key( $input );
    $choices = $setting->manager->get_control( $setting->id )->choices;
    return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Implement nonce verification for forms
 */
function aqualuxe_add_nonce_to_forms() {
    // Add nonce to comment form
    add_action( 'comment_form', function() {
        wp_nonce_field( 'aqualuxe_comment_nonce', 'aqualuxe_comment_nonce' );
    } );
    
    // Verify comment nonce
    add_filter( 'preprocess_comment', function( $commentdata ) {
        // Skip nonce verification for logged-in users
        if ( is_user_logged_in() ) {
            return $commentdata;
        }
        
        // Verify nonce
        if ( ! isset( $_POST['aqualuxe_comment_nonce'] ) || ! wp_verify_nonce( $_POST['aqualuxe_comment_nonce'], 'aqualuxe_comment_nonce' ) ) {
            wp_die( __( 'Comment submission failed.', 'aqualuxe' ), __( 'Comment Submission Error', 'aqualuxe' ), array( 'response' => 403 ) );
        }
        
        return $commentdata;
    } );
}
add_action( 'init', 'aqualuxe_add_nonce_to_forms' );

/**
 * Implement capability checks for admin functions
 */
function aqualuxe_check_admin_capabilities() {
    // Check capabilities for theme options
    add_action( 'admin_init', function() {
        // Check if the user has the required capability
        if ( isset( $_GET['page'] ) && $_GET['page'] === 'aqualuxe-options' && ! current_user_can( 'manage_options' ) ) {
            wp_die( __( 'You do not have sufficient permissions to access this page.', 'aqualuxe' ) );
        }
    } );
}
add_action( 'init', 'aqualuxe_check_admin_capabilities' );

/**
 * Implement data sanitization and validation
 */
function aqualuxe_sanitize_data() {
    // Sanitize theme options
    add_filter( 'pre_update_option_aqualuxe_options', function( $value, $old_value ) {
        // Sanitize text fields
        if ( isset( $value['site_title'] ) ) {
            $value['site_title'] = sanitize_text_field( $value['site_title'] );
        }
        
        // Sanitize URLs
        if ( isset( $value['logo_url'] ) ) {
            $value['logo_url'] = esc_url_raw( $value['logo_url'] );
        }
        
        // Sanitize email addresses
        if ( isset( $value['contact_email'] ) ) {
            $value['contact_email'] = sanitize_email( $value['contact_email'] );
        }
        
        // Sanitize HTML content
        if ( isset( $value['footer_text'] ) ) {
            $value['footer_text'] = wp_kses_post( $value['footer_text'] );
        }
        
        return $value;
    }, 10, 2 );
}
add_action( 'init', 'aqualuxe_sanitize_data' );

/**
 * Implement output escaping
 */
function aqualuxe_escape_output() {
    // Escape theme options output
    add_filter( 'aqualuxe_option', function( $value, $option ) {
        // Escape text fields
        if ( in_array( $option, array( 'site_title', 'copyright_text' ) ) ) {
            return esc_html( $value );
        }
        
        // Escape URLs
        if ( in_array( $option, array( 'logo_url', 'favicon_url' ) ) ) {
            return esc_url( $value );
        }
        
        // Escape HTML content
        if ( in_array( $option, array( 'footer_text', 'custom_css' ) ) ) {
            return wp_kses_post( $value );
        }
        
        return $value;
    }, 10, 2 );
}
add_action( 'init', 'aqualuxe_escape_output' );

/**
 * Implement secure cookie settings
 */
function aqualuxe_secure_cookies() {
    // Set secure cookies if HTTPS is enabled
    if ( is_ssl() ) {
        // Set secure flag for cookies
        add_filter( 'secure_signon_cookie', '__return_true' );
        add_filter( 'secure_auth_cookie', '__return_true' );
        add_filter( 'secure_logged_in_cookie', '__return_true' );
        
        // Set HttpOnly flag for cookies
        add_filter( 'session_use_only_cookies', '__return_true' );
        
        // Set SameSite attribute for cookies
        add_filter( 'wp_headers', function( $headers ) {
            $headers['Set-Cookie'] = 'SameSite=Lax';
            return $headers;
        } );
    }
}
add_action( 'init', 'aqualuxe_secure_cookies' );

/**
 * Implement password strength requirements
 */
function aqualuxe_password_strength() {
    // Add password strength meter to registration form
    add_action( 'wp_enqueue_scripts', function() {
        if ( is_page( 'register' ) || is_page( 'my-account' ) ) {
            wp_enqueue_script( 'password-strength-meter' );
        }
    } );
    
    // Add password strength check to registration
    add_filter( 'registration_errors', function( $errors, $sanitized_user_login, $user_email ) {
        // Get the password
        $password = isset( $_POST['password'] ) ? $_POST['password'] : '';
        
        // Check password length
        if ( strlen( $password ) < 8 ) {
            $errors->add( 'password_too_short', __( 'Password must be at least 8 characters long.', 'aqualuxe' ) );
        }
        
        // Check password complexity
        if ( ! preg_match( '/[A-Z]/', $password ) ) {
            $errors->add( 'password_no_uppercase', __( 'Password must contain at least one uppercase letter.', 'aqualuxe' ) );
        }
        
        if ( ! preg_match( '/[a-z]/', $password ) ) {
            $errors->add( 'password_no_lowercase', __( 'Password must contain at least one lowercase letter.', 'aqualuxe' ) );
        }
        
        if ( ! preg_match( '/[0-9]/', $password ) ) {
            $errors->add( 'password_no_number', __( 'Password must contain at least one number.', 'aqualuxe' ) );
        }
        
        if ( ! preg_match( '/[^A-Za-z0-9]/', $password ) ) {
            $errors->add( 'password_no_special_char', __( 'Password must contain at least one special character.', 'aqualuxe' ) );
        }
        
        return $errors;
    }, 10, 3 );
}
add_action( 'init', 'aqualuxe_password_strength' );

/**
 * Implement security logging
 */
function aqualuxe_security_logging() {
    // Log failed login attempts
    add_action( 'wp_login_failed', function( $username ) {
        // Get the client IP address
        $client_ip = $_SERVER['REMOTE_ADDR'];
        
        // Log the failed login attempt
        error_log( sprintf( 'Failed login attempt for username "%s" from IP %s', $username, $client_ip ) );
    } );
    
    // Log successful logins
    add_action( 'wp_login', function( $user_login, $user ) {
        // Get the client IP address
        $client_ip = $_SERVER['REMOTE_ADDR'];
        
        // Log the successful login
        error_log( sprintf( 'Successful login for username "%s" from IP %s', $user_login, $client_ip ) );
    }, 10, 2 );
    
    // Log password reset requests
    add_action( 'retrieve_password', function( $user_login ) {
        // Get the client IP address
        $client_ip = $_SERVER['REMOTE_ADDR'];
        
        // Log the password reset request
        error_log( sprintf( 'Password reset request for username "%s" from IP %s', $user_login, $client_ip ) );
    } );
}
add_action( 'init', 'aqualuxe_security_logging' );