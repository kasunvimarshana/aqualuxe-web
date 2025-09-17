<?php
/**
 * Code Review and Refactoring Tools
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

defined('ABSPATH') || exit;

/**
 * Code Review Class
 */
class Code_Review {

    /**
     * Instance
     *
     * @var Code_Review
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return Code_Review
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize code review
     */
    public function init() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_aqualuxe_run_code_review', array($this, 'ajax_run_code_review'));
        add_action('wp_ajax_aqualuxe_fix_issues', array($this, 'ajax_fix_issues'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__('Code Review', 'aqualuxe'),
            esc_html__('Code Review', 'aqualuxe'),
            'manage_options',
            'aqualuxe-code-review',
            array($this, 'admin_page')
        );
    }

    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap aqualuxe-code-review">
            <h1><?php esc_html_e('AquaLuxe Code Review', 'aqualuxe'); ?></h1>
            
            <div class="review-section">
                <h3><?php esc_html_e('Code Quality Analysis', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('Analyze the theme code for performance, security, readability, and WordPress coding standards compliance.', 'aqualuxe'); ?></p>
                
                <div class="review-options">
                    <label>
                        <input type="checkbox" name="check_performance" checked>
                        <?php esc_html_e('Performance Issues', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="check_security" checked>
                        <?php esc_html_e('Security Vulnerabilities', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="check_standards" checked>
                        <?php esc_html_e('WordPress Coding Standards', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="check_redundancy" checked>
                        <?php esc_html_e('Code Redundancy', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="check_accessibility" checked>
                        <?php esc_html_e('Accessibility Issues', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="check_seo" checked>
                        <?php esc_html_e('SEO Optimization', 'aqualuxe'); ?>
                    </label>
                </div>
                
                <button type="button" id="run-code-review" class="button button-primary">
                    <?php esc_html_e('Run Code Review', 'aqualuxe'); ?>
                </button>
                
                <div id="review-results" class="review-results"></div>
            </div>

            <div class="review-section">
                <h3><?php esc_html_e('Automated Fixes', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('Automatically fix common issues found during the review.', 'aqualuxe'); ?></p>
                
                <button type="button" id="auto-fix-issues" class="button button-secondary" disabled>
                    <?php esc_html_e('Auto-Fix Issues', 'aqualuxe'); ?>
                </button>
                
                <div id="fix-results" class="fix-results"></div>
            </div>

            <div class="review-section">
                <h3><?php esc_html_e('Refactoring Suggestions', 'aqualuxe'); ?></h3>
                <div id="refactoring-suggestions" class="refactoring-suggestions"></div>
            </div>
        </div>

        <style>
        .aqualuxe-code-review .review-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .review-options label {
            display: block;
            margin-bottom: 10px;
        }
        .review-results, .fix-results, .refactoring-suggestions {
            margin-top: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #0073aa;
            min-height: 50px;
        }
        .issue-item {
            padding: 10px;
            margin: 10px 0;
            border-left: 4px solid #dc3232;
            background: #fff;
        }
        .issue-item.warning {
            border-left-color: #ffb900;
        }
        .issue-item.info {
            border-left-color: #0073aa;
        }
        .issue-item.success {
            border-left-color: #00a32a;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('#run-code-review').on('click', function() {
                var button = $(this);
                var options = {};
                $('.review-options input:checked').each(function() {
                    options[$(this).attr('name')] = true;
                });
                
                button.prop('disabled', true).text('<?php esc_js(_e('Analyzing...', 'aqualuxe')); ?>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_run_code_review',
                        options: options,
                        nonce: '<?php echo wp_create_nonce('aqualuxe_code_review'); ?>'
                    },
                    success: function(response) {
                        $('#review-results').html(response.data.results);
                        if (response.data.fixable_issues > 0) {
                            $('#auto-fix-issues').prop('disabled', false);
                        }
                        $('#refactoring-suggestions').html(response.data.suggestions);
                        button.prop('disabled', false).text('<?php esc_js(_e('Run Code Review', 'aqualuxe')); ?>');
                    },
                    error: function() {
                        $('#review-results').html('<p style="color: red;"><?php esc_js(_e('Review failed. Please try again.', 'aqualuxe')); ?></p>');
                        button.prop('disabled', false).text('<?php esc_js(_e('Run Code Review', 'aqualuxe')); ?>');
                    }
                });
            });

            $('#auto-fix-issues').on('click', function() {
                if (!confirm('<?php esc_js(_e('Are you sure you want to auto-fix issues? This will modify your theme files.', 'aqualuxe')); ?>')) {
                    return;
                }
                
                var button = $(this);
                button.prop('disabled', true).text('<?php esc_js(_e('Fixing...', 'aqualuxe')); ?>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_fix_issues',
                        nonce: '<?php echo wp_create_nonce('aqualuxe_code_review'); ?>'
                    },
                    success: function(response) {
                        $('#fix-results').html(response.data);
                        button.text('<?php esc_js(_e('Auto-Fix Issues', 'aqualuxe')); ?>');
                    },
                    error: function() {
                        $('#fix-results').html('<p style="color: red;"><?php esc_js(_e('Auto-fix failed. Please try again.', 'aqualuxe')); ?></p>');
                        button.prop('disabled', false).text('<?php esc_js(_e('Auto-Fix Issues', 'aqualuxe')); ?>');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * AJAX run code review
     */
    public function ajax_run_code_review() {
        check_ajax_referer('aqualuxe_code_review', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'aqualuxe'));
        }

        $options = $_POST['options'] ?? array();
        $results = $this->run_code_review($options);
        wp_send_json_success($results);
    }

    /**
     * AJAX fix issues
     */
    public function ajax_fix_issues() {
        check_ajax_referer('aqualuxe_code_review', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'aqualuxe'));
        }

        $result = $this->auto_fix_issues();
        wp_send_json_success($result);
    }

    /**
     * Run code review
     *
     * @param array $options Review options
     * @return array
     */
    private function run_code_review($options) {
        $theme_dir = get_template_directory();
        $issues = array();
        $suggestions = array();
        $fixable_count = 0;

        if (isset($options['check_performance'])) {
            $performance_issues = $this->check_performance_issues($theme_dir);
            $issues = array_merge($issues, $performance_issues);
        }

        if (isset($options['check_security'])) {
            $security_issues = $this->check_security_issues($theme_dir);
            $issues = array_merge($issues, $security_issues);
        }

        if (isset($options['check_standards'])) {
            $standards_issues = $this->check_coding_standards($theme_dir);
            $issues = array_merge($issues, $standards_issues);
        }

        if (isset($options['check_redundancy'])) {
            $redundancy_issues = $this->check_code_redundancy($theme_dir);
            $issues = array_merge($issues, $redundancy_issues);
        }

        if (isset($options['check_accessibility'])) {
            $accessibility_issues = $this->check_accessibility($theme_dir);
            $issues = array_merge($issues, $accessibility_issues);
        }

        if (isset($options['check_seo'])) {
            $seo_issues = $this->check_seo_optimization($theme_dir);
            $issues = array_merge($issues, $seo_issues);
        }

        // Generate refactoring suggestions
        $suggestions = $this->generate_refactoring_suggestions($theme_dir);

        // Count fixable issues
        foreach ($issues as $issue) {
            if ($issue['fixable']) {
                $fixable_count++;
            }
        }

        $results_html = $this->format_issues_html($issues);
        $suggestions_html = $this->format_suggestions_html($suggestions);

        return array(
            'results' => $results_html,
            'suggestions' => $suggestions_html,
            'fixable_issues' => $fixable_count
        );
    }

    /**
     * Check performance issues
     *
     * @param string $theme_dir Theme directory
     * @return array
     */
    private function check_performance_issues($theme_dir) {
        $issues = array();

        // Check for direct database queries
        $files = $this->get_php_files($theme_dir);
        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (preg_match('/\$wpdb->query\(|mysql_query\(|mysqli_query\(/', $content)) {
                $issues[] = array(
                    'type' => 'performance',
                    'severity' => 'warning',
                    'file' => str_replace($theme_dir . '/', '', $file),
                    'message' => 'Direct database queries found. Consider using WordPress APIs.',
                    'fixable' => false
                );
            }
        }

        // Check for missing wp_enqueue_script/style
        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (preg_match('/<script[^>]*src=["\'](?!.*wp-content).*["\']/', $content)) {
                $issues[] = array(
                    'type' => 'performance',
                    'severity' => 'warning',
                    'file' => str_replace($theme_dir . '/', '', $file),
                    'message' => 'Hardcoded script tags found. Use wp_enqueue_script() instead.',
                    'fixable' => false
                );
            }
        }

        return $issues;
    }

    /**
     * Check security issues
     *
     * @param string $theme_dir Theme directory
     * @return array
     */
    private function check_security_issues($theme_dir) {
        $issues = array();

        $files = $this->get_php_files($theme_dir);
        foreach ($files as $file) {
            $content = file_get_contents($file);
            
            // Check for unescaped output
            if (preg_match('/echo\s+\$[^;]*;|print\s+\$[^;]*;/', $content)) {
                $issues[] = array(
                    'type' => 'security',
                    'severity' => 'error',
                    'file' => str_replace($theme_dir . '/', '', $file),
                    'message' => 'Unescaped output detected. Use esc_html(), esc_attr(), etc.',
                    'fixable' => false
                );
            }

            // Check for missing nonce verification
            if (preg_match('/\$_POST\[|\$_GET\[/', $content) && !preg_match('/wp_verify_nonce|check_ajax_referer/', $content)) {
                $issues[] = array(
                    'type' => 'security',
                    'severity' => 'error',
                    'file' => str_replace($theme_dir . '/', '', $file),
                    'message' => 'Form processing without nonce verification detected.',
                    'fixable' => false
                );
            }
        }

        return $issues;
    }

    /**
     * Check coding standards
     *
     * @param string $theme_dir Theme directory
     * @return array
     */
    private function check_coding_standards($theme_dir) {
        $issues = array();

        $files = $this->get_php_files($theme_dir);
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $lines = explode("\n", $content);
            
            foreach ($lines as $line_num => $line) {
                // Check for missing spaces around operators
                if (preg_match('/[a-zA-Z0-9]\+[a-zA-Z0-9]|[a-zA-Z0-9]=[a-zA-Z0-9]/', $line)) {
                    $issues[] = array(
                        'type' => 'standards',
                        'severity' => 'info',
                        'file' => str_replace($theme_dir . '/', '', $file) . ':' . ($line_num + 1),
                        'message' => 'Missing spaces around operators.',
                        'fixable' => true
                    );
                }

                // Check for tab indentation instead of spaces
                if (preg_match('/^\t/', $line)) {
                    $issues[] = array(
                        'type' => 'standards',
                        'severity' => 'info',
                        'file' => str_replace($theme_dir . '/', '', $file) . ':' . ($line_num + 1),
                        'message' => 'Tab indentation found. Use 4 spaces instead.',
                        'fixable' => true
                    );
                }
            }
        }

        return $issues;
    }

    /**
     * Check code redundancy
     *
     * @param string $theme_dir Theme directory
     * @return array
     */
    private function check_code_redundancy($theme_dir) {
        $issues = array();

        // This would be more complex in a real implementation
        // For now, just check for duplicate function definitions (basic check)
        $functions = array();
        $files = $this->get_php_files($theme_dir);
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            preg_match_all('/function\s+([a-zA-Z_][a-zA-Z0-9_]*)\s*\(/', $content, $matches);
            
            foreach ($matches[1] as $function_name) {
                if (isset($functions[$function_name])) {
                    $issues[] = array(
                        'type' => 'redundancy',
                        'severity' => 'warning',
                        'file' => str_replace($theme_dir . '/', '', $file),
                        'message' => "Potential duplicate function: {$function_name}",
                        'fixable' => false
                    );
                } else {
                    $functions[$function_name] = $file;
                }
            }
        }

        return $issues;
    }

    /**
     * Check accessibility
     *
     * @param string $theme_dir Theme directory
     * @return array
     */
    private function check_accessibility($theme_dir) {
        $issues = array();

        $template_files = glob($theme_dir . '/*.php');
        $template_files = array_merge($template_files, glob($theme_dir . '/templates/**/*.php'));

        foreach ($template_files as $file) {
            $content = file_get_contents($file);
            
            // Check for images without alt attributes
            if (preg_match('/<img[^>]*>/', $content, $matches)) {
                foreach ($matches as $img_tag) {
                    if (!preg_match('/alt\s*=/', $img_tag)) {
                        $issues[] = array(
                            'type' => 'accessibility',
                            'severity' => 'warning',
                            'file' => str_replace($theme_dir . '/', '', $file),
                            'message' => 'Image tag without alt attribute found.',
                            'fixable' => false
                        );
                    }
                }
            }

            // Check for missing skip links
            if (strpos($file, 'header.php') !== false && !preg_match('/skip-link|skip-to-content/', $content)) {
                $issues[] = array(
                    'type' => 'accessibility',
                    'severity' => 'info',
                    'file' => str_replace($theme_dir . '/', '', $file),
                    'message' => 'Missing skip link for keyboard navigation.',
                    'fixable' => false
                );
            }
        }

        return $issues;
    }

    /**
     * Check SEO optimization
     *
     * @param string $theme_dir Theme directory
     * @return array
     */
    private function check_seo_optimization($theme_dir) {
        $issues = array();

        // Check header.php for required meta tags
        $header_file = $theme_dir . '/header.php';
        if (file_exists($header_file)) {
            $content = file_get_contents($header_file);
            
            if (!preg_match('/wp_head\(\)/', $content)) {
                $issues[] = array(
                    'type' => 'seo',
                    'severity' => 'error',
                    'file' => 'header.php',
                    'message' => 'Missing wp_head() function call.',
                    'fixable' => true
                );
            }
        }

        // Check for structured data implementation
        $files = $this->get_php_files($theme_dir);
        $has_structured_data = false;
        
        foreach ($files as $file) {
            $content = file_get_contents($file);
            if (preg_match('/application\/ld\+json|schema\.org/', $content)) {
                $has_structured_data = true;
                break;
            }
        }

        if (!$has_structured_data) {
            $issues[] = array(
                'type' => 'seo',
                'severity' => 'info',
                'file' => 'theme',
                'message' => 'No structured data (Schema.org) implementation found.',
                'fixable' => false
            );
        }

        return $issues;
    }

    /**
     * Generate refactoring suggestions
     *
     * @param string $theme_dir Theme directory
     * @return array
     */
    private function generate_refactoring_suggestions($theme_dir) {
        $suggestions = array();

        // Suggest breaking down large files
        $files = $this->get_php_files($theme_dir);
        foreach ($files as $file) {
            $line_count = count(file($file));
            if ($line_count > 500) {
                $suggestions[] = array(
                    'type' => 'refactoring',
                    'priority' => 'medium',
                    'file' => str_replace($theme_dir . '/', '', $file),
                    'suggestion' => "File has {$line_count} lines. Consider breaking it into smaller, more focused files."
                );
            }
        }

        // Suggest using proper class structure
        foreach ($files as $file) {
            $content = file_get_contents($file);
            $function_count = preg_match_all('/^function\s+/m', $content);
            $class_count = preg_match_all('/^class\s+/m', $content);
            
            if ($function_count > 10 && $class_count === 0) {
                $suggestions[] = array(
                    'type' => 'refactoring',
                    'priority' => 'high',
                    'file' => str_replace($theme_dir . '/', '', $file),
                    'suggestion' => 'Multiple functions found. Consider organizing into a class structure.'
                );
            }
        }

        return $suggestions;
    }

    /**
     * Get PHP files from directory
     *
     * @param string $dir Directory path
     * @return array
     */
    private function get_php_files($dir) {
        $files = array();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $path = $file->getPathname();
                // Skip node_modules and vendor directories
                if (strpos($path, 'node_modules') === false && strpos($path, 'vendor') === false) {
                    $files[] = $path;
                }
            }
        }

        return $files;
    }

    /**
     * Format issues as HTML
     *
     * @param array $issues Issues array
     * @return string
     */
    private function format_issues_html($issues) {
        if (empty($issues)) {
            return '<p style="color: green;">✓ No issues found!</p>';
        }

        $html = '<h4>Issues Found:</h4>';
        
        $grouped_issues = array();
        foreach ($issues as $issue) {
            $grouped_issues[$issue['type']][] = $issue;
        }

        foreach ($grouped_issues as $type => $type_issues) {
            $html .= '<h5>' . ucfirst($type) . ' Issues (' . count($type_issues) . ')</h5>';
            
            foreach ($type_issues as $issue) {
                $css_class = 'issue-item ' . $issue['severity'];
                $html .= '<div class="' . $css_class . '">';
                $html .= '<strong>' . esc_html($issue['file']) . '</strong><br>';
                $html .= esc_html($issue['message']);
                if ($issue['fixable']) {
                    $html .= ' <em>(Auto-fixable)</em>';
                }
                $html .= '</div>';
            }
        }

        return $html;
    }

    /**
     * Format suggestions as HTML
     *
     * @param array $suggestions Suggestions array
     * @return string
     */
    private function format_suggestions_html($suggestions) {
        if (empty($suggestions)) {
            return '<p>No refactoring suggestions at this time.</p>';
        }

        $html = '<h4>Refactoring Suggestions:</h4>';
        
        foreach ($suggestions as $suggestion) {
            $priority_class = 'suggestion-' . $suggestion['priority'];
            $html .= '<div class="issue-item ' . $priority_class . '">';
            $html .= '<strong>' . esc_html($suggestion['file']) . '</strong> ';
            $html .= '<span class="priority">[' . ucfirst($suggestion['priority']) . ' Priority]</span><br>';
            $html .= esc_html($suggestion['suggestion']);
            $html .= '</div>';
        }

        return $html;
    }

    /**
     * Auto-fix issues
     *
     * @return string
     */
    private function auto_fix_issues() {
        // This would implement actual fixes for fixable issues
        // For now, just return a placeholder message
        return 'Auto-fix functionality would be implemented here. This would include fixes for coding standards, missing spaces, etc.';
    }
}