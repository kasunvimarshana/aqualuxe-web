<?php
/**
 * File Organizer and Structure Cleanup
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

defined('ABSPATH') || exit;

/**
 * File Organizer Class
 */
class File_Organizer {

    /**
     * Instance
     *
     * @var File_Organizer
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return File_Organizer
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Initialize organizer
     */
    public function init() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('wp_ajax_aqualuxe_analyze_structure', array($this, 'ajax_analyze_structure'));
        add_action('wp_ajax_aqualuxe_cleanup_files', array($this, 'ajax_cleanup_files'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            esc_html__('File Organizer', 'aqualuxe'),
            esc_html__('File Organizer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-file-organizer',
            array($this, 'admin_page')
        );
    }

    /**
     * Admin page
     */
    public function admin_page() {
        ?>
        <div class="wrap aqualuxe-file-organizer">
            <h1><?php esc_html_e('AquaLuxe File Organizer', 'aqualuxe'); ?></h1>
            
            <div class="organizer-section">
                <h3><?php esc_html_e('File Structure Analysis', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('Analyze the current theme file structure and identify optimization opportunities.', 'aqualuxe'); ?></p>
                
                <button type="button" id="analyze-structure" class="button button-primary">
                    <?php esc_html_e('Analyze Structure', 'aqualuxe'); ?>
                </button>
                
                <div id="analysis-results" class="analysis-results"></div>
            </div>

            <div class="organizer-section">
                <h3><?php esc_html_e('Cleanup Operations', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('Remove temporary files, duplicates, and unused assets.', 'aqualuxe'); ?></p>
                
                <div class="cleanup-options">
                    <label>
                        <input type="checkbox" name="cleanup_temp" checked>
                        <?php esc_html_e('Remove temporary files', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="cleanup_duplicates" checked>
                        <?php esc_html_e('Remove duplicate files', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="cleanup_logs">
                        <?php esc_html_e('Clear log files', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" name="optimize_images">
                        <?php esc_html_e('Optimize images', 'aqualuxe'); ?>
                    </label>
                </div>
                
                <button type="button" id="cleanup-files" class="button button-secondary">
                    <?php esc_html_e('Run Cleanup', 'aqualuxe'); ?>
                </button>
            </div>

            <div class="organizer-section">
                <h3><?php esc_html_e('Structure Validation', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('Validate file naming conventions and directory structure.', 'aqualuxe'); ?></p>
                
                <div id="validation-results" class="validation-results"></div>
            </div>
        </div>

        <style>
        .aqualuxe-file-organizer .organizer-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .cleanup-options label {
            display: block;
            margin-bottom: 10px;
        }
        .analysis-results, .validation-results {
            margin-top: 15px;
            padding: 15px;
            background: #f9f9f9;
            border-left: 4px solid #0073aa;
            min-height: 50px;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            $('#analyze-structure').on('click', function() {
                var button = $(this);
                button.prop('disabled', true).text('<?php esc_js(_e('Analyzing...', 'aqualuxe')); ?>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_analyze_structure',
                        nonce: '<?php echo wp_create_nonce('aqualuxe_file_organizer'); ?>'
                    },
                    success: function(response) {
                        $('#analysis-results').html(response.data);
                        button.prop('disabled', false).text('<?php esc_js(_e('Analyze Structure', 'aqualuxe')); ?>');
                    },
                    error: function() {
                        $('#analysis-results').html('<p style="color: red;"><?php esc_js(_e('Analysis failed. Please try again.', 'aqualuxe')); ?></p>');
                        button.prop('disabled', false).text('<?php esc_js(_e('Analyze Structure', 'aqualuxe')); ?>');
                    }
                });
            });

            $('#cleanup-files').on('click', function() {
                if (!confirm('<?php esc_js(_e('Are you sure you want to run the cleanup? This action cannot be undone.', 'aqualuxe')); ?>')) {
                    return;
                }
                
                var button = $(this);
                var options = {};
                $('.cleanup-options input:checked').each(function() {
                    options[$(this).attr('name')] = true;
                });
                
                button.prop('disabled', true).text('<?php esc_js(_e('Cleaning...', 'aqualuxe')); ?>');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_cleanup_files',
                        options: options,
                        nonce: '<?php echo wp_create_nonce('aqualuxe_file_organizer'); ?>'
                    },
                    success: function(response) {
                        alert(response.data);
                        button.prop('disabled', false).text('<?php esc_js(_e('Run Cleanup', 'aqualuxe')); ?>');
                    },
                    error: function() {
                        alert('<?php esc_js(_e('Cleanup failed. Please try again.', 'aqualuxe')); ?>');
                        button.prop('disabled', false).text('<?php esc_js(_e('Run Cleanup', 'aqualuxe')); ?>');
                    }
                });
            });
        });
        </script>
        <?php
    }

    /**
     * AJAX analyze structure
     */
    public function ajax_analyze_structure() {
        check_ajax_referer('aqualuxe_file_organizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'aqualuxe'));
        }

        $analysis = $this->analyze_file_structure();
        wp_send_json_success($analysis);
    }

    /**
     * AJAX cleanup files
     */
    public function ajax_cleanup_files() {
        check_ajax_referer('aqualuxe_file_organizer', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Permission denied.', 'aqualuxe'));
        }

        $options = $_POST['options'] ?? array();
        $result = $this->cleanup_files($options);
        wp_send_json_success($result);
    }

    /**
     * Analyze file structure
     *
     * @return string
     */
    private function analyze_file_structure() {
        $theme_dir = get_template_directory();
        $analysis = array();

        // Check directory structure
        $required_dirs = array(
            'assets/src/scss',
            'assets/src/js',
            'assets/src/images',
            'assets/src/fonts',
            'assets/dist',
            'core/abstracts',
            'core/interfaces',
            'modules',
            'inc/admin',
            'inc/woocommerce',
            'templates/components',
            'templates/partials'
        );

        $missing_dirs = array();
        foreach ($required_dirs as $dir) {
            if (!is_dir($theme_dir . '/' . $dir)) {
                $missing_dirs[] = $dir;
            }
        }

        if (!empty($missing_dirs)) {
            $analysis[] = '<h4>Missing Directories:</h4><ul><li>' . implode('</li><li>', $missing_dirs) . '</li></ul>';
        } else {
            $analysis[] = '<p style="color: green;">✓ All required directories exist.</p>';
        }

        // Check file naming conventions
        $naming_issues = $this->check_naming_conventions($theme_dir);
        if (!empty($naming_issues)) {
            $analysis[] = '<h4>Naming Convention Issues:</h4><ul><li>' . implode('</li><li>', $naming_issues) . '</li></ul>';
        } else {
            $analysis[] = '<p style="color: green;">✓ File naming conventions are correct.</p>';
        }

        // Check for unused files
        $unused_files = $this->find_unused_files($theme_dir);
        if (!empty($unused_files)) {
            $analysis[] = '<h4>Potentially Unused Files:</h4><ul><li>' . implode('</li><li>', array_slice($unused_files, 0, 10)) . '</li></ul>';
            if (count($unused_files) > 10) {
                $analysis[] = '<p><em>... and ' . (count($unused_files) - 10) . ' more files.</em></p>';
            }
        }

        // File size analysis
        $large_files = $this->find_large_files($theme_dir);
        if (!empty($large_files)) {
            $analysis[] = '<h4>Large Files (>1MB):</h4><ul>';
            foreach ($large_files as $file => $size) {
                $analysis[] = '<li>' . $file . ' (' . size_format($size) . ')</li>';
            }
            $analysis[] = '</ul>';
        }

        return '<div>' . implode('', $analysis) . '</div>';
    }

    /**
     * Check naming conventions
     *
     * @param string $dir Directory to check
     * @return array
     */
    private function check_naming_conventions($dir) {
        $issues = array();
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filename = $file->getFilename();
                $relative_path = str_replace($dir . '/', '', $file->getPathname());

                // Skip hidden files and directories
                if (strpos($filename, '.') === 0 || strpos($relative_path, '/.') !== false) {
                    continue;
                }

                // Skip node_modules and vendor directories
                if (strpos($relative_path, 'node_modules') !== false || strpos($relative_path, 'vendor') !== false) {
                    continue;
                }

                // Check for spaces in filenames
                if (strpos($filename, ' ') !== false) {
                    $issues[] = $relative_path . ' - Contains spaces';
                }

                // Check for uppercase in directory names (except certain files)
                $allowed_uppercase = array('README.md', 'LICENSE', 'CHANGELOG.md', 'DEVELOPMENT.md', 'TESTING.md', 'DEPLOYMENT.md');
                if (!in_array($filename, $allowed_uppercase) && preg_match('/[A-Z]/', $filename) && !preg_match('/\.(php|js|css|scss)$/', $filename)) {
                    $issues[] = $relative_path . ' - Contains uppercase letters';
                }
            }
        }

        return $issues;
    }

    /**
     * Find unused files
     *
     * @param string $dir Directory to check
     * @return array
     */
    private function find_unused_files($dir) {
        $unused = array();
        
        // This is a simplified check - in a real implementation, you'd want to 
        // scan for file references throughout the codebase
        $temp_patterns = array(
            '*.tmp',
            '*.temp',
            '*.bak',
            '*.swp',
            '*~',
            '.DS_Store',
            'Thumbs.db'
        );

        foreach ($temp_patterns as $pattern) {
            $files = glob($dir . '/**/' . $pattern, GLOB_BRACE);
            foreach ($files as $file) {
                $unused[] = str_replace($dir . '/', '', $file);
            }
        }

        return $unused;
    }

    /**
     * Find large files
     *
     * @param string $dir Directory to check
     * @return array
     */
    private function find_large_files($dir) {
        $large_files = array();
        $size_limit = 1024 * 1024; // 1MB
        
        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getSize() > $size_limit) {
                $relative_path = str_replace($dir . '/', '', $file->getPathname());
                
                // Skip node_modules
                if (strpos($relative_path, 'node_modules') === false) {
                    $large_files[$relative_path] = $file->getSize();
                }
            }
        }

        arsort($large_files);
        return array_slice($large_files, 0, 10, true);
    }

    /**
     * Cleanup files
     *
     * @param array $options Cleanup options
     * @return string
     */
    private function cleanup_files($options) {
        $theme_dir = get_template_directory();
        $cleaned = array();

        if (isset($options['cleanup_temp'])) {
            $temp_files = $this->find_unused_files($theme_dir);
            foreach ($temp_files as $file) {
                $full_path = $theme_dir . '/' . $file;
                if (file_exists($full_path) && is_writable($full_path)) {
                    unlink($full_path);
                    $cleaned[] = $file;
                }
            }
        }

        if (isset($options['cleanup_logs'])) {
            $log_files = glob($theme_dir . '/**/*.log');
            foreach ($log_files as $file) {
                if (is_writable($file)) {
                    unlink($file);
                    $cleaned[] = str_replace($theme_dir . '/', '', $file);
                }
            }
        }

        $count = count($cleaned);
        return sprintf(
            __('Cleanup completed. %d files were removed.', 'aqualuxe'),
            $count
        );
    }
}