<?php
/**
 * AquaLuxe Security Audit Checklist
 *
 * Implements security audit functionality for the AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Security Audit Class
 */
class AquaLuxe_Security_Audit {
    /**
     * Constructor
     */
    public function __construct() {
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register security settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add security headers
        add_action('send_headers', array($this, 'add_security_headers'));
        
        // Add security notices
        add_action('admin_notices', array($this, 'security_notices'));
        
        // Schedule security scan
        if (!wp_next_scheduled('aqualuxe_security_scan')) {
            wp_schedule_event(time(), 'weekly', 'aqualuxe_security_scan');
        }
        
        // Run security scan
        add_action('aqualuxe_security_scan', array($this, 'run_security_scan'));
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'tools.php',
            __('Security Audit', 'aqualuxe'),
            __('Security Audit', 'aqualuxe'),
            'manage_options',
            'aqualuxe-security-audit',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('aqualuxe_security_settings', 'aqualuxe_security_options');
        
        add_settings_section(
            'aqualuxe_security_section',
            __('Security Settings', 'aqualuxe'),
            array($this, 'security_section_callback'),
            'aqualuxe-security-settings'
        );
        
        add_settings_field(
            'enable_security_headers',
            __('Security Headers', 'aqualuxe'),
            array($this, 'enable_security_headers_callback'),
            'aqualuxe-security-settings',
            'aqualuxe_security_section'
        );
        
        add_settings_field(
            'enable_login_protection',
            __('Login Protection', 'aqualuxe'),
            array($this, 'enable_login_protection_callback'),
            'aqualuxe-security-settings',
            'aqualuxe_security_section'
        );
        
        add_settings_field(
            'enable_file_scanning',
            __('File Scanning', 'aqualuxe'),
            array($this, 'enable_file_scanning_callback'),
            'aqualuxe-security-settings',
            'aqualuxe_security_section'
        );
    }
    
    /**
     * Security section callback
     */
    public function security_section_callback() {
        echo '<p>' . esc_html__('Configure security settings for your site.', 'aqualuxe') . '</p>';
    }
    
    /**
     * Enable security headers callback
     */
    public function enable_security_headers_callback() {
        $options = get_option('aqualuxe_security_options');
        $value = isset($options['enable_security_headers']) ? $options['enable_security_headers'] : 1;
        
        echo '<input type="checkbox" id="enable_security_headers" name="aqualuxe_security_options[enable_security_headers]" value="1" ' . checked(1, $value, false) . '/>';
        echo '<label for="enable_security_headers">' . esc_html__('Enable security headers (recommended)', 'aqualuxe') . '</label>';
        echo '<p class="description">' . esc_html__('Adds security headers like Content-Security-Policy, X-XSS-Protection, etc.', 'aqualuxe') . '</p>';
    }
    
    /**
     * Enable login protection callback
     */
    public function enable_login_protection_callback() {
        $options = get_option('aqualuxe_security_options');
        $value = isset($options['enable_login_protection']) ? $options['enable_login_protection'] : 1;
        
        echo '<input type="checkbox" id="enable_login_protection" name="aqualuxe_security_options[enable_login_protection]" value="1" ' . checked(1, $value, false) . '/>';
        echo '<label for="enable_login_protection">' . esc_html__('Enable login protection (recommended)', 'aqualuxe') . '</label>';
        echo '<p class="description">' . esc_html__('Adds protection against brute force attacks on the login page.', 'aqualuxe') . '</p>';
    }
    
    /**
     * Enable file scanning callback
     */
    public function enable_file_scanning_callback() {
        $options = get_option('aqualuxe_security_options');
        $value = isset($options['enable_file_scanning']) ? $options['enable_file_scanning'] : 1;
        
        echo '<input type="checkbox" id="enable_file_scanning" name="aqualuxe_security_options[enable_file_scanning]" value="1" ' . checked(1, $value, false) . '/>';
        echo '<label for="enable_file_scanning">' . esc_html__('Enable file scanning (recommended)', 'aqualuxe') . '</label>';
        echo '<p class="description">' . esc_html__('Scans theme files for potential security issues.', 'aqualuxe') . '</p>';
    }
    
    /**
     * Admin page
     */
    public function admin_page() {
        // Run security check if requested
        if (isset($_POST['aqualuxe_run_security_check']) && check_admin_referer('aqualuxe_security_check')) {
            $this->run_security_scan();
        }
        
        // Get security status
        $security_status = get_option('aqualuxe_security_status', array());
        
        ?>
        <div class="wrap">
            <h1><?php esc_html_e('AquaLuxe Security Audit', 'aqualuxe'); ?></h1>
            
            <h2 class="nav-tab-wrapper">
                <a href="?page=aqualuxe-security-audit&tab=audit" class="nav-tab <?php echo (!isset($_GET['tab']) || $_GET['tab'] === 'audit') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Security Audit', 'aqualuxe'); ?></a>
                <a href="?page=aqualuxe-security-audit&tab=settings" class="nav-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'settings') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Settings', 'aqualuxe'); ?></a>
                <a href="?page=aqualuxe-security-audit&tab=checklist" class="nav-tab <?php echo (isset($_GET['tab']) && $_GET['tab'] === 'checklist') ? 'nav-tab-active' : ''; ?>"><?php esc_html_e('Checklist', 'aqualuxe'); ?></a>
            </h2>
            
            <div class="tab-content">
                <?php
                $tab = isset($_GET['tab']) ? $_GET['tab'] : 'audit';
                
                switch ($tab) {
                    case 'settings':
                        $this->settings_tab();
                        break;
                    case 'checklist':
                        $this->checklist_tab();
                        break;
                    default:
                        $this->audit_tab($security_status);
                        break;
                }
                ?>
            </div>
        </div>
        <?php
    }
    
    /**
     * Audit tab
     */
    public function audit_tab($security_status) {
        $last_scan = isset($security_status['last_scan']) ? $security_status['last_scan'] : 0;
        $issues = isset($security_status['issues']) ? $security_status['issues'] : array();
        $passed = isset($security_status['passed']) ? $security_status['passed'] : array();
        
        ?>
        <div class="aqualuxe-security-audit">
            <div class="aqualuxe-security-header">
                <h2><?php esc_html_e('Security Audit Results', 'aqualuxe'); ?></h2>
                
                <form method="post" action="">
                    <?php wp_nonce_field('aqualuxe_security_check'); ?>
                    <input type="submit" name="aqualuxe_run_security_check" class="button button-primary" value="<?php esc_attr_e('Run Security Check', 'aqualuxe'); ?>" />
                </form>
            </div>
            
            <?php if ($last_scan > 0) : ?>
                <div class="aqualuxe-security-summary">
                    <p>
                        <?php printf(
                            esc_html__('Last scan: %s', 'aqualuxe'),
                            date_i18n(get_option('date_format') . ' ' . get_option('time_format'), $last_scan)
                        ); ?>
                    </p>
                    
                    <div class="aqualuxe-security-stats">
                        <div class="aqualuxe-security-stat aqualuxe-security-passed">
                            <span class="aqualuxe-security-count"><?php echo count($passed); ?></span>
                            <span class="aqualuxe-security-label"><?php esc_html_e('Passed', 'aqualuxe'); ?></span>
                        </div>
                        
                        <div class="aqualuxe-security-stat aqualuxe-security-issues">
                            <span class="aqualuxe-security-count"><?php echo count($issues); ?></span>
                            <span class="aqualuxe-security-label"><?php esc_html_e('Issues', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                </div>
                
                <?php if (!empty($issues)) : ?>
                    <div class="aqualuxe-security-issues">
                        <h3><?php esc_html_e('Security Issues', 'aqualuxe'); ?></h3>
                        
                        <table class="widefat">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Issue', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Severity', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Recommendation', 'aqualuxe'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($issues as $issue) : ?>
                                    <tr>
                                        <td><?php echo esc_html($issue['title']); ?></td>
                                        <td>
                                            <span class="aqualuxe-security-severity aqualuxe-security-severity-<?php echo esc_attr($issue['severity']); ?>">
                                                <?php echo esc_html(ucfirst($issue['severity'])); ?>
                                            </span>
                                        </td>
                                        <td><?php echo esc_html($issue['recommendation']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($passed)) : ?>
                    <div class="aqualuxe-security-passed">
                        <h3><?php esc_html_e('Passed Checks', 'aqualuxe'); ?></h3>
                        
                        <table class="widefat">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Check', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Description', 'aqualuxe'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($passed as $check) : ?>
                                    <tr>
                                        <td><?php echo esc_html($check['title']); ?></td>
                                        <td><?php echo esc_html($check['description']); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
                
            <?php else : ?>
                <div class="aqualuxe-security-no-scan">
                    <p><?php esc_html_e('No security scan has been run yet. Click the button above to run a security check.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <style>
            .aqualuxe-security-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }
            
            .aqualuxe-security-summary {
                background: #fff;
                padding: 15px;
                border: 1px solid #ccd0d4;
                margin-bottom: 20px;
            }
            
            .aqualuxe-security-stats {
                display: flex;
                gap: 20px;
                margin-top: 10px;
            }
            
            .aqualuxe-security-stat {
                padding: 15px;
                border-radius: 4px;
                text-align: center;
                min-width: 100px;
            }
            
            .aqualuxe-security-passed {
                background: #edfaef;
                border: 1px solid #c3e6cb;
            }
            
            .aqualuxe-security-issues {
                background: #f8d7da;
                border: 1px solid #f5c6cb;
            }
            
            .aqualuxe-security-count {
                display: block;
                font-size: 24px;
                font-weight: bold;
            }
            
            .aqualuxe-security-label {
                display: block;
                margin-top: 5px;
            }
            
            .aqualuxe-security-severity {
                display: inline-block;
                padding: 3px 8px;
                border-radius: 3px;
                color: #fff;
                font-size: 12px;
                font-weight: bold;
            }
            
            .aqualuxe-security-severity-high {
                background: #dc3545;
            }
            
            .aqualuxe-security-severity-medium {
                background: #ffc107;
                color: #212529;
            }
            
            .aqualuxe-security-severity-low {
                background: #17a2b8;
            }
            
            .aqualuxe-security-issues,
            .aqualuxe-security-passed {
                margin-bottom: 30px;
            }
            
            .aqualuxe-security-no-scan {
                background: #f8f9fa;
                padding: 20px;
                border: 1px solid #ccd0d4;
                text-align: center;
            }
        </style>
        <?php
    }
    
    /**
     * Settings tab
     */
    public function settings_tab() {
        ?>
        <div class="aqualuxe-security-settings">
            <h2><?php esc_html_e('Security Settings', 'aqualuxe'); ?></h2>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('aqualuxe_security_settings');
                do_settings_sections('aqualuxe-security-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
    
    /**
     * Checklist tab
     */
    public function checklist_tab() {
        ?>
        <div class="aqualuxe-security-checklist">
            <h2><?php esc_html_e('Security Checklist', 'aqualuxe'); ?></h2>
            
            <div class="aqualuxe-security-checklist-intro">
                <p><?php esc_html_e('Use this checklist to ensure your WordPress site is secure. Check off items as you complete them.', 'aqualuxe'); ?></p>
            </div>
            
            <div class="aqualuxe-security-checklist-items">
                <h3><?php esc_html_e('WordPress Core', 'aqualuxe'); ?></h3>
                <ul>
                    <li>
                        <input type="checkbox" id="check-wp-version" />
                        <label for="check-wp-version"><?php esc_html_e('WordPress is updated to the latest version', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-file-permissions" />
                        <label for="check-file-permissions"><?php esc_html_e('File permissions are set correctly (755 for directories, 644 for files)', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-debug-mode" />
                        <label for="check-debug-mode"><?php esc_html_e('Debug mode is disabled in production (WP_DEBUG set to false)', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-admin-user" />
                        <label for="check-admin-user"><?php esc_html_e('Default "admin" username is not used', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-db-prefix" />
                        <label for="check-db-prefix"><?php esc_html_e('Database table prefix is not the default "wp_"', 'aqualuxe'); ?></label>
                    </li>
                </ul>
                
                <h3><?php esc_html_e('Plugins & Themes', 'aqualuxe'); ?></h3>
                <ul>
                    <li>
                        <input type="checkbox" id="check-plugins-updated" />
                        <label for="check-plugins-updated"><?php esc_html_e('All plugins are updated to the latest version', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-themes-updated" />
                        <label for="check-themes-updated"><?php esc_html_e('All themes are updated to the latest version', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-inactive-plugins" />
                        <label for="check-inactive-plugins"><?php esc_html_e('Inactive plugins are removed', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-inactive-themes" />
                        <label for="check-inactive-themes"><?php esc_html_e('Inactive themes are removed', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-plugin-sources" />
                        <label for="check-plugin-sources"><?php esc_html_e('All plugins are from reputable sources', 'aqualuxe'); ?></label>
                    </li>
                </ul>
                
                <h3><?php esc_html_e('User Authentication', 'aqualuxe'); ?></h3>
                <ul>
                    <li>
                        <input type="checkbox" id="check-strong-passwords" />
                        <label for="check-strong-passwords"><?php esc_html_e('Strong password policy is enforced', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-2fa" />
                        <label for="check-2fa"><?php esc_html_e('Two-factor authentication is enabled for admin users', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-login-attempts" />
                        <label for="check-login-attempts"><?php esc_html_e('Failed login attempts are limited', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-user-roles" />
                        <label for="check-user-roles"><?php esc_html_e('User roles and capabilities are properly configured', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-login-url" />
                        <label for="check-login-url"><?php esc_html_e('Login URL is changed from the default /wp-admin/', 'aqualuxe'); ?></label>
                    </li>
                </ul>
                
                <h3><?php esc_html_e('Data Protection', 'aqualuxe'); ?></h3>
                <ul>
                    <li>
                        <input type="checkbox" id="check-ssl" />
                        <label for="check-ssl"><?php esc_html_e('SSL certificate is installed and forced HTTPS is enabled', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-security-headers" />
                        <label for="check-security-headers"><?php esc_html_e('Security headers are implemented (CSP, X-XSS-Protection, etc.)', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-form-validation" />
                        <label for="check-form-validation"><?php esc_html_e('All forms have proper validation and sanitization', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-data-encryption" />
                        <label for="check-data-encryption"><?php esc_html_e('Sensitive data is encrypted', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-privacy-policy" />
                        <label for="check-privacy-policy"><?php esc_html_e('Privacy policy is up-to-date and compliant with regulations', 'aqualuxe'); ?></label>
                    </li>
                </ul>
                
                <h3><?php esc_html_e('Backups & Recovery', 'aqualuxe'); ?></h3>
                <ul>
                    <li>
                        <input type="checkbox" id="check-regular-backups" />
                        <label for="check-regular-backups"><?php esc_html_e('Regular automated backups are configured', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-backup-storage" />
                        <label for="check-backup-storage"><?php esc_html_e('Backups are stored in multiple locations', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-backup-testing" />
                        <label for="check-backup-testing"><?php esc_html_e('Backup restoration has been tested', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-disaster-recovery" />
                        <label for="check-disaster-recovery"><?php esc_html_e('Disaster recovery plan is documented', 'aqualuxe'); ?></label>
                    </li>
                </ul>
                
                <h3><?php esc_html_e('Monitoring & Maintenance', 'aqualuxe'); ?></h3>
                <ul>
                    <li>
                        <input type="checkbox" id="check-security-scanning" />
                        <label for="check-security-scanning"><?php esc_html_e('Regular security scanning is set up', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-activity-logging" />
                        <label for="check-activity-logging"><?php esc_html_e('User activity logging is enabled', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-file-integrity" />
                        <label for="check-file-integrity"><?php esc_html_e('File integrity monitoring is in place', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-update-schedule" />
                        <label for="check-update-schedule"><?php esc_html_e('Regular update schedule is established', 'aqualuxe'); ?></label>
                    </li>
                    <li>
                        <input type="checkbox" id="check-security-team" />
                        <label for="check-security-team"><?php esc_html_e('Security response team and procedures are defined', 'aqualuxe'); ?></label>
                    </li>
                </ul>
            </div>
            
            <div class="aqualuxe-security-checklist-actions">
                <button type="button" class="button" id="save-checklist"><?php esc_html_e('Save Progress', 'aqualuxe'); ?></button>
                <button type="button" class="button" id="reset-checklist"><?php esc_html_e('Reset Checklist', 'aqualuxe'); ?></button>
                <button type="button" class="button button-primary" id="export-checklist"><?php esc_html_e('Export as PDF', 'aqualuxe'); ?></button>
            </div>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                // Load saved checklist state
                const savedChecklist = localStorage.getItem('aqualuxeSecurityChecklist');
                if (savedChecklist) {
                    const checkedItems = JSON.parse(savedChecklist);
                    checkedItems.forEach(function(id) {
                        $('#' + id).prop('checked', true);
                    });
                }
                
                // Save checklist state
                $('#save-checklist').on('click', function() {
                    const checkedItems = [];
                    $('.aqualuxe-security-checklist-items input:checked').each(function() {
                        checkedItems.push($(this).attr('id'));
                    });
                    
                    localStorage.setItem('aqualuxeSecurityChecklist', JSON.stringify(checkedItems));
                    alert('<?php esc_html_e('Checklist progress saved!', 'aqualuxe'); ?>');
                });
                
                // Reset checklist
                $('#reset-checklist').on('click', function() {
                    if (confirm('<?php esc_html_e('Are you sure you want to reset the checklist? All progress will be lost.', 'aqualuxe'); ?>')) {
                        $('.aqualuxe-security-checklist-items input').prop('checked', false);
                        localStorage.removeItem('aqualuxeSecurityChecklist');
                    }
                });
                
                // Export as PDF
                $('#export-checklist').on('click', function() {
                    alert('<?php esc_html_e('PDF export functionality will be implemented in the next version.', 'aqualuxe'); ?>');
                });
            });
        </script>
        
        <style>
            .aqualuxe-security-checklist-intro {
                margin-bottom: 20px;
            }
            
            .aqualuxe-security-checklist-items h3 {
                margin-top: 30px;
                padding-bottom: 5px;
                border-bottom: 1px solid #ccd0d4;
            }
            
            .aqualuxe-security-checklist-items ul {
                margin-left: 20px;
            }
            
            .aqualuxe-security-checklist-items li {
                margin-bottom: 10px;
            }
            
            .aqualuxe-security-checklist-items input[type="checkbox"] {
                margin-right: 10px;
            }
            
            .aqualuxe-security-checklist-actions {
                margin-top: 30px;
                padding-top: 20px;
                border-top: 1px solid #ccd0d4;
            }
        </style>
        <?php
    }
    
    /**
     * Add security headers
     */
    public function add_security_headers() {
        $options = get_option('aqualuxe_security_options');
        
        if (isset($options['enable_security_headers']) && $options['enable_security_headers']) {
            // X-XSS-Protection
            header('X-XSS-Protection: 1; mode=block');
            
            // X-Content-Type-Options
            header('X-Content-Type-Options: nosniff');
            
            // X-Frame-Options
            header('X-Frame-Options: SAMEORIGIN');
            
            // Referrer-Policy
            header('Referrer-Policy: strict-origin-when-cross-origin');
            
            // Permissions-Policy
            header('Permissions-Policy: geolocation=(self), microphone=(), camera=()');
            
            // Content-Security-Policy
            $csp = "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' *.googleapis.com *.gstatic.com www.google-analytics.com; style-src 'self' 'unsafe-inline' *.googleapis.com; img-src 'self' data: *.googleapis.com *.gstatic.com www.google-analytics.com; connect-src 'self' *.googleapis.com; font-src 'self' data: *.gstatic.com *.googleapis.com; object-src 'none'; media-src 'self'; frame-src 'self'; frame-ancestors 'self'; form-action 'self'; upgrade-insecure-requests;";
            
            // Allow customization of CSP
            $csp = apply_filters('aqualuxe_content_security_policy', $csp);
            
            header("Content-Security-Policy: $csp");
        }
    }
    
    /**
     * Security notices
     */
    public function security_notices() {
        // Check if user can manage options
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Check WordPress version
        global $wp_version;
        if (version_compare($wp_version, '5.8', '<')) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php printf(esc_html__('Security Notice: You are using WordPress %s, which is outdated. Please update to the latest version.', 'aqualuxe'), $wp_version); ?></p>
            </div>
            <?php
        }
        
        // Check if debug mode is enabled
        if (defined('WP_DEBUG') && WP_DEBUG) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php esc_html_e('Security Notice: WordPress debug mode is enabled. This should be disabled in production environments.', 'aqualuxe'); ?></p>
            </div>
            <?php
        }
        
        // Check for admin user
        $admin_user = get_user_by('login', 'admin');
        if ($admin_user) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php esc_html_e('Security Notice: The default "admin" username is being used. This is a security risk. Please create a new administrator account and delete this one.', 'aqualuxe'); ?></p>
            </div>
            <?php
        }
        
        // Check for SSL
        if (!is_ssl()) {
            ?>
            <div class="notice notice-warning is-dismissible">
                <p><?php esc_html_e('Security Notice: Your site is not using HTTPS. We recommend enabling SSL for improved security.', 'aqualuxe'); ?></p>
            </div>
            <?php
        }
    }
    
    /**
     * Run security scan
     */
    public function run_security_scan() {
        $issues = array();
        $passed = array();
        
        // Check WordPress version
        global $wp_version;
        if (version_compare($wp_version, '5.8', '<')) {
            $issues[] = array(
                'title' => sprintf(__('WordPress %s is outdated', 'aqualuxe'), $wp_version),
                'severity' => 'high',
                'recommendation' => __('Update WordPress to the latest version', 'aqualuxe'),
            );
        } else {
            $passed[] = array(
                'title' => sprintf(__('WordPress %s is up to date', 'aqualuxe'), $wp_version),
                'description' => __('Your WordPress installation is running the latest version', 'aqualuxe'),
            );
        }
        
        // Check if debug mode is enabled
        if (defined('WP_DEBUG') && WP_DEBUG) {
            $issues[] = array(
                'title' => __('Debug mode is enabled', 'aqualuxe'),
                'severity' => 'medium',
                'recommendation' => __('Disable WP_DEBUG in wp-config.php for production environments', 'aqualuxe'),
            );
        } else {
            $passed[] = array(
                'title' => __('Debug mode is disabled', 'aqualuxe'),
                'description' => __('WordPress debug mode is correctly disabled in production', 'aqualuxe'),
            );
        }
        
        // Check for admin user
        $admin_user = get_user_by('login', 'admin');
        if ($admin_user) {
            $issues[] = array(
                'title' => __('Default admin username is used', 'aqualuxe'),
                'severity' => 'high',
                'recommendation' => __('Create a new administrator account and delete the default "admin" user', 'aqualuxe'),
            );
        } else {
            $passed[] = array(
                'title' => __('No default admin username', 'aqualuxe'),
                'description' => __('The default "admin" username is not being used', 'aqualuxe'),
            );
        }
        
        // Check for SSL
        if (!is_ssl()) {
            $issues[] = array(
                'title' => __('SSL is not enabled', 'aqualuxe'),
                'severity' => 'high',
                'recommendation' => __('Enable HTTPS for your website and force SSL in WordPress settings', 'aqualuxe'),
            );
        } else {
            $passed[] = array(
                'title' => __('SSL is enabled', 'aqualuxe'),
                'description' => __('Your website is properly secured with HTTPS', 'aqualuxe'),
            );
        }
        
        // Check file permissions
        $upload_dir = wp_upload_dir();
        $upload_base = $upload_dir['basedir'];
        
        if (is_dir($upload_base) && substr(sprintf('%o', fileperms($upload_base)), -4) !== '0755') {
            $issues[] = array(
                'title' => __('Incorrect upload directory permissions', 'aqualuxe'),
                'severity' => 'medium',
                'recommendation' => __('Set uploads directory permissions to 755', 'aqualuxe'),
            );
        } else {
            $passed[] = array(
                'title' => __('Upload directory permissions', 'aqualuxe'),
                'description' => __('Upload directory has correct permissions', 'aqualuxe'),
            );
        }
        
        // Check wp-config.php location
        if (file_exists(ABSPATH . 'wp-config.php')) {
            $issues[] = array(
                'title' => __('wp-config.php location', 'aqualuxe'),
                'severity' => 'medium',
                'recommendation' => __('Move wp-config.php to the directory above your WordPress installation', 'aqualuxe'),
            );
        } else if (file_exists(dirname(ABSPATH) . '/wp-config.php')) {
            $passed[] = array(
                'title' => __('wp-config.php location', 'aqualuxe'),
                'description' => __('wp-config.php is properly located in the directory above WordPress', 'aqualuxe'),
            );
        }
        
        // Check database prefix
        global $wpdb;
        if ($wpdb->prefix === 'wp_') {
            $issues[] = array(
                'title' => __('Default database prefix', 'aqualuxe'),
                'severity' => 'medium',
                'recommendation' => __('Change the database prefix from the default "wp_" to something unique', 'aqualuxe'),
            );
        } else {
            $passed[] = array(
                'title' => __('Custom database prefix', 'aqualuxe'),
                'description' => __('Database is using a custom table prefix', 'aqualuxe'),
            );
        }
        
        // Check security keys
        $security_keys = array('AUTH_KEY', 'SECURE_AUTH_KEY', 'LOGGED_IN_KEY', 'NONCE_KEY', 'AUTH_SALT', 'SECURE_AUTH_SALT', 'LOGGED_IN_SALT', 'NONCE_SALT');
        $weak_keys = 0;
        
        foreach ($security_keys as $key) {
            if (!defined($key) || constant($key) === 'put your unique phrase here' || strlen(constant($key)) < 64) {
                $weak_keys++;
            }
        }
        
        if ($weak_keys > 0) {
            $issues[] = array(
                'title' => __('Weak security keys', 'aqualuxe'),
                'severity' => 'high',
                'recommendation' => __('Generate strong security keys in wp-config.php using the WordPress API', 'aqualuxe'),
            );
        } else {
            $passed[] = array(
                'title' => __('Strong security keys', 'aqualuxe'),
                'description' => __('WordPress security keys are properly configured', 'aqualuxe'),
            );
        }
        
        // Check for file editing
        if (defined('DISALLOW_FILE_EDIT') && DISALLOW_FILE_EDIT) {
            $passed[] = array(
                'title' => __('File editing disabled', 'aqualuxe'),
                'description' => __('File editing is disabled in wp-config.php', 'aqualuxe'),
            );
        } else {
            $issues[] = array(
                'title' => __('File editing enabled', 'aqualuxe'),
                'severity' => 'medium',
                'recommendation' => __('Disable file editing by adding DISALLOW_FILE_EDIT to wp-config.php', 'aqualuxe'),
            );
        }
        
        // Check for automatic updates
        if (defined('AUTOMATIC_UPDATER_DISABLED') && AUTOMATIC_UPDATER_DISABLED) {
            $issues[] = array(
                'title' => __('Automatic updates disabled', 'aqualuxe'),
                'severity' => 'medium',
                'recommendation' => __('Enable automatic updates for security patches', 'aqualuxe'),
            );
        } else {
            $passed[] = array(
                'title' => __('Automatic updates enabled', 'aqualuxe'),
                'description' => __('WordPress automatic updates are enabled', 'aqualuxe'),
            );
        }
        
        // Check for login protection
        $options = get_option('aqualuxe_security_options');
        if (!isset($options['enable_login_protection']) || !$options['enable_login_protection']) {
            $issues[] = array(
                'title' => __('Login protection disabled', 'aqualuxe'),
                'severity' => 'medium',
                'recommendation' => __('Enable login protection in AquaLuxe security settings', 'aqualuxe'),
            );
        } else {
            $passed[] = array(
                'title' => __('Login protection enabled', 'aqualuxe'),
                'description' => __('Brute force protection for login page is active', 'aqualuxe'),
            );
        }
        
        // Save scan results
        $security_status = array(
            'last_scan' => time(),
            'issues' => $issues,
            'passed' => $passed,
        );
        
        update_option('aqualuxe_security_status', $security_status);
        
        return $security_status;
    }
}

// Initialize the security audit
new AquaLuxe_Security_Audit();