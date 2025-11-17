<?php
/**
 * Demo Importer Admin Interface
 *
 * Admin page for the demo content importer
 *
 * @package AquaLuxe\DemoImporter
 * @since 1.0.0
 */

namespace AquaLuxe\DemoImporter;

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Class DemoImporterAdmin
 *
 * Handles the admin interface for demo importing
 */
class DemoImporterAdmin {
    
    /**
     * Single instance of the class
     *
     * @var DemoImporterAdmin
     */
    private static $instance = null;

    /**
     * Get instance
     *
     * @return DemoImporterAdmin
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }

    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_theme_page(
            __('Demo Importer', 'aqualuxe'),
            __('Demo Importer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-importer',
            array($this, 'render_admin_page')
        );
    }

    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'appearance_page_aqualuxe-demo-importer') {
            return;
        }

        wp_enqueue_script(
            'aqualuxe-demo-importer',
            AQUALUXE_ASSETS_URI . '/js/admin/demo-importer.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );

        wp_localize_script('aqualuxe-demo-importer', 'aqualuxeDemo', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_demo_import'),
            'reset_nonce' => wp_create_nonce('aqualuxe_demo_reset'),
            'strings' => array(
                'importing' => __('Importing...', 'aqualuxe'),
                'imported' => __('Import completed successfully!', 'aqualuxe'),
                'error' => __('Import failed. Please try again.', 'aqualuxe'),
                'resetting' => __('Resetting...', 'aqualuxe'),
                'reset_complete' => __('Reset completed successfully!', 'aqualuxe'),
                'reset_error' => __('Reset failed. Please try again.', 'aqualuxe'),
                'confirm_reset' => __('Are you sure you want to reset all demo content? This action cannot be undone.', 'aqualuxe'),
            ),
        ));

        wp_enqueue_style(
            'aqualuxe-demo-importer',
            AQUALUXE_ASSETS_URI . '/css/admin/demo-importer.css',
            array(),
            AQUALUXE_VERSION
        );
    }

    /**
     * Render admin page
     */
    public function render_admin_page() {
        ?>
        <div class="wrap aqualuxe-demo-importer">
            <h1><?php esc_html_e('AquaLuxe Demo Importer', 'aqualuxe'); ?></h1>
            
            <div class="aqualuxe-demo-intro">
                <p><?php esc_html_e('Import demo content to quickly set up your AquaLuxe theme with sample content, including pages, posts, products, and settings.', 'aqualuxe'); ?></p>
                
                <div class="notice notice-warning">
                    <p>
                        <strong><?php esc_html_e('Important:', 'aqualuxe'); ?></strong>
                        <?php esc_html_e('This will import demo content to your site. It is recommended to run this on a fresh WordPress installation.', 'aqualuxe'); ?>
                    </p>
                </div>
            </div>

            <div class="aqualuxe-demo-tabs">
                <nav class="nav-tab-wrapper">
                    <a href="#import" class="nav-tab nav-tab-active"><?php esc_html_e('Import', 'aqualuxe'); ?></a>
                    <a href="#settings" class="nav-tab"><?php esc_html_e('Settings', 'aqualuxe'); ?></a>
                    <a href="#reset" class="nav-tab"><?php esc_html_e('Reset', 'aqualuxe'); ?></a>
                </nav>

                <!-- Import Tab -->
                <div id="import" class="tab-content active">
                    <div class="aqualuxe-import-options">
                        <h2><?php esc_html_e('Import Options', 'aqualuxe'); ?></h2>
                        
                        <form id="demo-import-form">
                            <div class="import-type-selection">
                                <h3><?php esc_html_e('Import Type', 'aqualuxe'); ?></h3>
                                
                                <label>
                                    <input type="radio" name="import_type" value="full" checked>
                                    <strong><?php esc_html_e('Full Import', 'aqualuxe'); ?></strong>
                                    <p class="description"><?php esc_html_e('Import all demo content including pages, posts, products, menus, widgets, and settings.', 'aqualuxe'); ?></p>
                                </label>
                                
                                <label>
                                    <input type="radio" name="import_type" value="selective">
                                    <strong><?php esc_html_e('Selective Import', 'aqualuxe'); ?></strong>
                                    <p class="description"><?php esc_html_e('Choose specific content types to import.', 'aqualuxe'); ?></p>
                                </label>
                                
                                <label>
                                    <input type="radio" name="import_type" value="content_only">
                                    <strong><?php esc_html_e('Content Only', 'aqualuxe'); ?></strong>
                                    <p class="description"><?php esc_html_e('Import only posts, pages, and products without changing settings.', 'aqualuxe'); ?></p>
                                </label>
                                
                                <label>
                                    <input type="radio" name="import_type" value="settings_only">
                                    <strong><?php esc_html_e('Settings Only', 'aqualuxe'); ?></strong>
                                    <p class="description"><?php esc_html_e('Import only theme settings and customizer options.', 'aqualuxe'); ?></p>
                                </label>
                            </div>

                            <div class="selective-options" style="display: none;">
                                <h3><?php esc_html_e('Select Content Types', 'aqualuxe'); ?></h3>
                                
                                <label>
                                    <input type="checkbox" name="selective_options[]" value="posts">
                                    <?php esc_html_e('Posts & Pages', 'aqualuxe'); ?>
                                </label>
                                
                                <label>
                                    <input type="checkbox" name="selective_options[]" value="products">
                                    <?php esc_html_e('WooCommerce Products', 'aqualuxe'); ?>
                                </label>
                                
                                <label>
                                    <input type="checkbox" name="selective_options[]" value="services">
                                    <?php esc_html_e('Services', 'aqualuxe'); ?>
                                </label>
                                
                                <label>
                                    <input type="checkbox" name="selective_options[]" value="media">
                                    <?php esc_html_e('Media Files', 'aqualuxe'); ?>
                                </label>
                                
                                <label>
                                    <input type="checkbox" name="selective_options[]" value="menus">
                                    <?php esc_html_e('Navigation Menus', 'aqualuxe'); ?>
                                </label>
                                
                                <label>
                                    <input type="checkbox" name="selective_options[]" value="widgets">
                                    <?php esc_html_e('Widgets', 'aqualuxe'); ?>
                                </label>
                                
                                <label>
                                    <input type="checkbox" name="selective_options[]" value="settings">
                                    <?php esc_html_e('Theme Settings', 'aqualuxe'); ?>
                                </label>
                            </div>

                            <div class="import-actions">
                                <button type="submit" class="button button-primary button-hero" id="start-import">
                                    <?php esc_html_e('Start Import', 'aqualuxe'); ?>
                                </button>
                            </div>
                        </form>

                        <div class="import-progress" style="display: none;">
                            <h3><?php esc_html_e('Import Progress', 'aqualuxe'); ?></h3>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 0%;"></div>
                            </div>
                            <p class="progress-message"><?php esc_html_e('Preparing import...', 'aqualuxe'); ?></p>
                            <div class="progress-log"></div>
                        </div>

                        <div class="import-complete" style="display: none;">
                            <div class="notice notice-success">
                                <p>
                                    <strong><?php esc_html_e('Import Completed!', 'aqualuxe'); ?></strong>
                                    <?php esc_html_e('Your demo content has been successfully imported.', 'aqualuxe'); ?>
                                </p>
                            </div>
                            <p>
                                <a href="<?php echo esc_url(home_url()); ?>" class="button button-primary" target="_blank">
                                    <?php esc_html_e('View Your Site', 'aqualuxe'); ?>
                                </a>
                                <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="button">
                                    <?php esc_html_e('Customize', 'aqualuxe'); ?>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Settings Tab -->
                <div id="settings" class="tab-content">
                    <h2><?php esc_html_e('Import Settings', 'aqualuxe'); ?></h2>
                    
                    <form method="post" action="options.php">
                        <?php settings_fields('aqualuxe_demo_importer_settings'); ?>
                        
                        <table class="form-table">
                            <tr>
                                <th scope="row"><?php esc_html_e('Backup Before Import', 'aqualuxe'); ?></th>
                                <td>
                                    <label>
                                        <input type="checkbox" name="aqualuxe_demo_backup" value="1" <?php checked(get_option('aqualuxe_demo_backup', 1)); ?>>
                                        <?php esc_html_e('Create backup before importing demo content', 'aqualuxe'); ?>
                                    </label>
                                    <p class="description"><?php esc_html_e('Recommended for existing sites with content.', 'aqualuxe'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Batch Size', 'aqualuxe'); ?></th>
                                <td>
                                    <input type="number" name="aqualuxe_demo_batch_size" value="<?php echo esc_attr(get_option('aqualuxe_demo_batch_size', 10)); ?>" min="1" max="50" class="small-text">
                                    <p class="description"><?php esc_html_e('Number of items to process at once. Lower values for slower servers.', 'aqualuxe'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row"><?php esc_html_e('Import Timeout', 'aqualuxe'); ?></th>
                                <td>
                                    <input type="number" name="aqualuxe_demo_timeout" value="<?php echo esc_attr(get_option('aqualuxe_demo_timeout', 300)); ?>" min="60" max="600" class="small-text"> <?php esc_html_e('seconds', 'aqualuxe'); ?>
                                    <p class="description"><?php esc_html_e('Maximum time allowed for import process.', 'aqualuxe'); ?></p>
                                </td>
                            </tr>
                        </table>
                        
                        <?php submit_button(); ?>
                    </form>
                </div>

                <!-- Reset Tab -->
                <div id="reset" class="tab-content">
                    <h2><?php esc_html_e('Reset Demo Content', 'aqualuxe'); ?></h2>
                    
                    <div class="notice notice-error">
                        <p>
                            <strong><?php esc_html_e('Warning:', 'aqualuxe'); ?></strong>
                            <?php esc_html_e('This will permanently delete all imported demo content. This action cannot be undone.', 'aqualuxe'); ?>
                        </p>
                    </div>
                    
                    <p><?php esc_html_e('Use this option to remove all demo content that was previously imported. This includes:', 'aqualuxe'); ?></p>
                    
                    <ul>
                        <li><?php esc_html_e('Demo pages and posts', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Demo products and services', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Imported media files', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Demo menus and widgets', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('Theme customizer settings', 'aqualuxe'); ?></li>
                    </ul>
                    
                    <div class="reset-actions">
                        <button type="button" class="button button-secondary" id="reset-demo">
                            <?php esc_html_e('Reset Demo Content', 'aqualuxe'); ?>
                        </button>
                    </div>
                    
                    <div class="reset-progress" style="display: none;">
                        <h3><?php esc_html_e('Reset Progress', 'aqualuxe'); ?></h3>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 0%;"></div>
                        </div>
                        <p class="progress-message"><?php esc_html_e('Preparing reset...', 'aqualuxe'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .aqualuxe-demo-importer {
            max-width: 1000px;
        }
        
        .aqualuxe-demo-intro {
            margin-bottom: 30px;
        }
        
        .aqualuxe-demo-tabs .tab-content {
            display: none;
            padding: 20px 0;
        }
        
        .aqualuxe-demo-tabs .tab-content.active {
            display: block;
        }
        
        .import-type-selection label {
            display: block;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .import-type-selection label:hover {
            background-color: #f9f9f9;
        }
        
        .import-type-selection input[type="radio"] {
            margin-right: 10px;
        }
        
        .selective-options {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 4px;
        }
        
        .selective-options label {
            display: block;
            margin-bottom: 10px;
        }
        
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #f0f0f0;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(45deg, #0891b2, #065f46);
            transition: width 0.3s ease;
        }
        
        .progress-log {
            max-height: 200px;
            overflow-y: auto;
            background: #f9f9f9;
            padding: 10px;
            font-family: monospace;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .import-actions,
        .reset-actions {
            margin-top: 30px;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Tab switching
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href').substring(1);
                
                $('.nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                
                $('.tab-content').removeClass('active');
                $('#' + target).addClass('active');
            });
            
            // Show/hide selective options
            $('input[name="import_type"]').on('change', function() {
                if ($(this).val() === 'selective') {
                    $('.selective-options').show();
                } else {
                    $('.selective-options').hide();
                }
            });
        });
        </script>
        <?php
    }
}

// Initialize the admin interface
DemoImporterAdmin::get_instance();