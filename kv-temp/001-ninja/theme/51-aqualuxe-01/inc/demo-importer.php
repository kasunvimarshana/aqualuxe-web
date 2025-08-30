<?php
/**
 * Demo content importer for AquaLuxe theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize demo importer
 */
function aqualuxe_demo_importer_init() {
    // Add demo importer menu
    add_action('admin_menu', 'aqualuxe_demo_importer_menu');
    
    // Register demo importer AJAX actions
    add_action('wp_ajax_aqualuxe_import_demo', 'aqualuxe_import_demo_content');
}
add_action('after_setup_theme', 'aqualuxe_demo_importer_init');

/**
 * Add demo importer menu
 */
function aqualuxe_demo_importer_menu() {
    add_theme_page(
        __('AquaLuxe Demo Importer', 'aqualuxe'),
        __('Import Demo Content', 'aqualuxe'),
        'manage_options',
        'aqualuxe-demo-importer',
        'aqualuxe_demo_importer_page'
    );
}

/**
 * Demo importer page
 */
function aqualuxe_demo_importer_page() {
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }
    
    // Get available demos
    $demos = aqualuxe_get_available_demos();
    
    // Check if WooCommerce is active
    $is_woocommerce_active = aqualuxe_is_woocommerce_active();
    
    ?>
    <div class="wrap aqualuxe-demo-importer">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        
        <div class="aqualuxe-demo-importer-description">
            <p><?php esc_html_e('Import demo content to get started with the AquaLuxe theme. This will import pages, posts, menus, widgets, theme options, and other content.', 'aqualuxe'); ?></p>
            
            <?php if (!$is_woocommerce_active && aqualuxe_demo_requires_woocommerce()) : ?>
                <div class="notice notice-warning">
                    <p><?php esc_html_e('Some demo content requires WooCommerce plugin. Please install and activate WooCommerce to import all content.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
            
            <div class="notice notice-warning">
                <p><?php esc_html_e('Importing demo content will overwrite your current theme options, widgets, and may add new pages and posts. It is recommended to use this on a fresh WordPress installation.', 'aqualuxe'); ?></p>
            </div>
        </div>
        
        <div class="aqualuxe-demo-importer-demos">
            <?php foreach ($demos as $demo_id => $demo) : ?>
                <div class="aqualuxe-demo-importer-demo" data-demo-id="<?php echo esc_attr($demo_id); ?>">
                    <div class="aqualuxe-demo-importer-demo-image">
                        <img src="<?php echo esc_url($demo['image']); ?>" alt="<?php echo esc_attr($demo['name']); ?>">
                    </div>
                    
                    <div class="aqualuxe-demo-importer-demo-info">
                        <h3 class="aqualuxe-demo-importer-demo-name"><?php echo esc_html($demo['name']); ?></h3>
                        
                        <div class="aqualuxe-demo-importer-demo-description">
                            <?php echo wp_kses_post($demo['description']); ?>
                        </div>
                        
                        <?php if (isset($demo['requires_woocommerce']) && $demo['requires_woocommerce'] && !$is_woocommerce_active) : ?>
                            <div class="aqualuxe-demo-importer-demo-notice">
                                <p><?php esc_html_e('Requires WooCommerce plugin', 'aqualuxe'); ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <div class="aqualuxe-demo-importer-demo-actions">
                            <button class="button button-primary aqualuxe-demo-importer-import-button" data-demo-id="<?php echo esc_attr($demo_id); ?>" <?php echo (isset($demo['requires_woocommerce']) && $demo['requires_woocommerce'] && !$is_woocommerce_active) ? 'disabled' : ''; ?>>
                                <?php esc_html_e('Import Demo', 'aqualuxe'); ?>
                            </button>
                            
                            <?php if (isset($demo['preview_url'])) : ?>
                                <a href="<?php echo esc_url($demo['preview_url']); ?>" class="button" target="_blank">
                                    <?php esc_html_e('Preview', 'aqualuxe'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="aqualuxe-demo-importer-demo-progress">
                        <div class="aqualuxe-demo-importer-demo-progress-bar"></div>
                        <div class="aqualuxe-demo-importer-demo-progress-text"></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <style>
        .aqualuxe-demo-importer {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .aqualuxe-demo-importer-description {
            margin-bottom: 30px;
        }
        
        .aqualuxe-demo-importer-demos {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            grid-gap: 30px;
        }
        
        .aqualuxe-demo-importer-demo {
            border: 1px solid #ddd;
            border-radius: 5px;
            overflow: hidden;
            background-color: #fff;
        }
        
        .aqualuxe-demo-importer-demo-image img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .aqualuxe-demo-importer-demo-info {
            padding: 20px;
        }
        
        .aqualuxe-demo-importer-demo-name {
            margin-top: 0;
            margin-bottom: 10px;
        }
        
        .aqualuxe-demo-importer-demo-description {
            margin-bottom: 20px;
            color: #666;
        }
        
        .aqualuxe-demo-importer-demo-notice {
            margin-bottom: 20px;
            color: #d63638;
        }
        
        .aqualuxe-demo-importer-demo-actions {
            display: flex;
            gap: 10px;
        }
        
        .aqualuxe-demo-importer-demo-progress {
            display: none;
            padding: 20px;
            border-top: 1px solid #ddd;
        }
        
        .aqualuxe-demo-importer-demo-progress-bar {
            height: 10px;
            background-color: #f0f0f0;
            border-radius: 5px;
            overflow: hidden;
            margin-bottom: 10px;
        }
        
        .aqualuxe-demo-importer-demo-progress-bar:before {
            content: '';
            display: block;
            height: 100%;
            width: 0;
            background-color: #2271b1;
            transition: width 0.3s ease;
        }
        
        .aqualuxe-demo-importer-demo-progress-text {
            font-size: 12px;
            color: #666;
        }
    </style>
    
    <script>
        jQuery(document).ready(function($) {
            $('.aqualuxe-demo-importer-import-button').on('click', function() {
                const button = $(this);
                const demoId = button.data('demo-id');
                const demoContainer = $('.aqualuxe-demo-importer-demo[data-demo-id="' + demoId + '"]');
                const progressBar = demoContainer.find('.aqualuxe-demo-importer-demo-progress-bar');
                const progressText = demoContainer.find('.aqualuxe-demo-importer-demo-progress-text');
                
                // Confirm import
                if (!confirm('<?php esc_html_e('Are you sure you want to import this demo content? This will overwrite your current theme options, widgets, and may add new pages and posts.', 'aqualuxe'); ?>')) {
                    return;
                }
                
                // Disable button
                button.prop('disabled', true).text('<?php esc_html_e('Importing...', 'aqualuxe'); ?>');
                
                // Show progress
                demoContainer.find('.aqualuxe-demo-importer-demo-progress').show();
                progressText.text('<?php esc_html_e('Preparing import...', 'aqualuxe'); ?>');
                
                // Import demo content
                importDemoContent(demoId, 'prepare', 0, progressBar, progressText, button);
            });
            
            function importDemoContent(demoId, step, progress, progressBar, progressText, button) {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_import_demo',
                        demo_id: demoId,
                        step: step,
                        nonce: '<?php echo wp_create_nonce('aqualuxe_import_demo_nonce'); ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update progress
                            progress = response.data.progress;
                            progressBar.find(':before').css('width', progress + '%');
                            progressText.text(response.data.message);
                            
                            // Continue import
                            if (response.data.step !== 'complete') {
                                importDemoContent(demoId, response.data.step, progress, progressBar, progressText, button);
                            } else {
                                // Import complete
                                progressText.text('<?php esc_html_e('Import complete!', 'aqualuxe'); ?>');
                                button.text('<?php esc_html_e('Imported', 'aqualuxe'); ?>');
                                
                                // Show success message
                                $('<div class="notice notice-success"><p><?php esc_html_e('Demo content imported successfully!', 'aqualuxe'); ?></p></div>').insertBefore('.aqualuxe-demo-importer-description');
                                
                                // Redirect to homepage after 2 seconds
                                setTimeout(function() {
                                    window.location.href = '<?php echo esc_url(home_url('/')); ?>';
                                }, 2000);
                            }
                        } else {
                            // Import failed
                            progressText.text('<?php esc_html_e('Import failed!', 'aqualuxe'); ?>');
                            button.prop('disabled', false).text('<?php esc_html_e('Import Demo', 'aqualuxe'); ?>');
                            
                            // Show error message
                            $('<div class="notice notice-error"><p>' + response.data.message + '</p></div>').insertBefore('.aqualuxe-demo-importer-description');
                        }
                    },
                    error: function() {
                        // Import failed
                        progressText.text('<?php esc_html_e('Import failed!', 'aqualuxe'); ?>');
                        button.prop('disabled', false).text('<?php esc_html_e('Import Demo', 'aqualuxe'); ?>');
                        
                        // Show error message
                        $('<div class="notice notice-error"><p><?php esc_html_e('An error occurred during import. Please try again.', 'aqualuxe'); ?></p></div>').insertBefore('.aqualuxe-demo-importer-description');
                    }
                });
            }
        });
    </script>
    <?php
}

/**
 * Get available demos
 *
 * @return array
 */
function aqualuxe_get_available_demos() {
    $demos = array(
        'default' => array(
            'name' => __('Default Demo', 'aqualuxe'),
            'description' => __('The default demo includes all pages, posts, products, and other content shown in the theme demo.', 'aqualuxe'),
            'image' => AQUALUXE_ASSETS_URI . 'images/demos/default.jpg',
            'preview_url' => 'https://aqualuxe.example.com/',
            'requires_woocommerce' => true,
        ),
        'minimal' => array(
            'name' => __('Minimal Demo', 'aqualuxe'),
            'description' => __('A minimal demo with basic pages and content, without WooCommerce products.', 'aqualuxe'),
            'image' => AQUALUXE_ASSETS_URI . 'images/demos/minimal.jpg',
            'preview_url' => 'https://aqualuxe.example.com/minimal/',
            'requires_woocommerce' => false,
        ),
    );
    
    return apply_filters('aqualuxe_available_demos', $demos);
}

/**
 * Check if any demo requires WooCommerce
 *
 * @return bool
 */
function aqualuxe_demo_requires_woocommerce() {
    $demos = aqualuxe_get_available_demos();
    
    foreach ($demos as $demo) {
        if (isset($demo['requires_woocommerce']) && $demo['requires_woocommerce']) {
            return true;
        }
    }
    
    return false;
}

/**
 * Import demo content
 */
function aqualuxe_import_demo_content() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_import_demo_nonce')) {
        wp_send_json_error(array('message' => __('Invalid nonce', 'aqualuxe')));
    }
    
    // Check user capabilities
    if (!current_user_can('manage_options')) {
        wp_send_json_error(array('message' => __('You do not have permission to import demo content', 'aqualuxe')));
    }
    
    // Check demo ID
    if (!isset($_POST['demo_id'])) {
        wp_send_json_error(array('message' => __('Invalid demo ID', 'aqualuxe')));
    }
    
    // Get demo ID
    $demo_id = sanitize_text_field($_POST['demo_id']);
    
    // Get available demos
    $demos = aqualuxe_get_available_demos();
    
    // Check if demo exists
    if (!isset($demos[$demo_id])) {
        wp_send_json_error(array('message' => __('Demo not found', 'aqualuxe')));
    }
    
    // Get demo
    $demo = $demos[$demo_id];
    
    // Check if WooCommerce is required but not active
    if (isset($demo['requires_woocommerce']) && $demo['requires_woocommerce'] && !aqualuxe_is_woocommerce_active()) {
        wp_send_json_error(array('message' => __('This demo requires WooCommerce plugin. Please install and activate WooCommerce to import this demo.', 'aqualuxe')));
    }
    
    // Get step
    $step = isset($_POST['step']) ? sanitize_text_field($_POST['step']) : 'prepare';
    
    // Import demo content based on step
    switch ($step) {
        case 'prepare':
            // Prepare import
            wp_send_json_success(array(
                'step' => 'theme_options',
                'progress' => 10,
                'message' => __('Importing theme options...', 'aqualuxe'),
            ));
            break;
            
        case 'theme_options':
            // Import theme options
            aqualuxe_import_theme_options($demo_id);
            
            wp_send_json_success(array(
                'step' => 'widgets',
                'progress' => 20,
                'message' => __('Importing widgets...', 'aqualuxe'),
            ));
            break;
            
        case 'widgets':
            // Import widgets
            aqualuxe_import_widgets($demo_id);
            
            wp_send_json_success(array(
                'step' => 'content',
                'progress' => 30,
                'message' => __('Importing content...', 'aqualuxe'),
            ));
            break;
            
        case 'content':
            // Import content
            aqualuxe_import_content($demo_id);
            
            wp_send_json_success(array(
                'step' => 'menus',
                'progress' => 60,
                'message' => __('Importing menus...', 'aqualuxe'),
            ));
            break;
            
        case 'menus':
            // Import menus
            aqualuxe_import_menus($demo_id);
            
            wp_send_json_success(array(
                'step' => 'homepage',
                'progress' => 80,
                'message' => __('Setting up homepage...', 'aqualuxe'),
            ));
            break;
            
        case 'homepage':
            // Set up homepage
            aqualuxe_setup_homepage($demo_id);
            
            wp_send_json_success(array(
                'step' => 'finalize',
                'progress' => 90,
                'message' => __('Finalizing import...', 'aqualuxe'),
            ));
            break;
            
        case 'finalize':
            // Finalize import
            aqualuxe_finalize_import($demo_id);
            
            wp_send_json_success(array(
                'step' => 'complete',
                'progress' => 100,
                'message' => __('Import complete!', 'aqualuxe'),
            ));
            break;
            
        default:
            wp_send_json_error(array('message' => __('Invalid step', 'aqualuxe')));
            break;
    }
}

/**
 * Import theme options
 *
 * @param string $demo_id
 */
function aqualuxe_import_theme_options($demo_id) {
    // Get demo options
    $options_file = AQUALUXE_DIR . 'inc/demo-content/' . $demo_id . '/options.json';
    
    if (file_exists($options_file)) {
        $options = json_decode(file_get_contents($options_file), true);
        
        if ($options) {
            update_option('aqualuxe_options', $options);
        }
    }
}

/**
 * Import widgets
 *
 * @param string $demo_id
 */
function aqualuxe_import_widgets($demo_id) {
    // Get demo widgets
    $widgets_file = AQUALUXE_DIR . 'inc/demo-content/' . $demo_id . '/widgets.json';
    
    if (file_exists($widgets_file)) {
        $widgets = json_decode(file_get_contents($widgets_file), true);
        
        if ($widgets) {
            // Import widgets
            foreach ($widgets as $sidebar_id => $sidebar_widgets) {
                $sidebars_widgets = get_option('sidebars_widgets', array());
                $sidebars_widgets[$sidebar_id] = array();
                
                foreach ($sidebar_widgets as $widget_id => $widget_data) {
                    $widget_base = preg_replace('/-[0-9]+$/', '', $widget_id);
                    $widget_index = preg_replace('/^.*-([0-9]+)$/', '$1', $widget_id);
                    
                    $current_widgets = get_option('widget_' . $widget_base, array());
                    $current_widgets[$widget_index] = $widget_data;
                    
                    update_option('widget_' . $widget_base, $current_widgets);
                    
                    $sidebars_widgets[$sidebar_id][] = $widget_id;
                }
                
                update_option('sidebars_widgets', $sidebars_widgets);
            }
        }
    }
}

/**
 * Import content
 *
 * @param string $demo_id
 */
function aqualuxe_import_content($demo_id) {
    // Get demo content
    $content_file = AQUALUXE_DIR . 'inc/demo-content/' . $demo_id . '/content.xml';
    
    if (file_exists($content_file)) {
        // Include WordPress importer
        if (!class_exists('WP_Importer')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
        }
        
        if (!class_exists('WP_Import')) {
            require_once AQUALUXE_DIR . 'inc/demo-content/wordpress-importer.php';
        }
        
        // Import content
        $importer = new WP_Import();
        $importer->fetch_attachments = true;
        $importer->import($content_file);
    }
}

/**
 * Import menus
 *
 * @param string $demo_id
 */
function aqualuxe_import_menus($demo_id) {
    // Get demo menus
    $menus_file = AQUALUXE_DIR . 'inc/demo-content/' . $demo_id . '/menus.json';
    
    if (file_exists($menus_file)) {
        $menus = json_decode(file_get_contents($menus_file), true);
        
        if ($menus) {
            // Import menus
            foreach ($menus as $location => $menu_name) {
                $menu = get_term_by('name', $menu_name, 'nav_menu');
                
                if ($menu) {
                    $locations = get_theme_mod('nav_menu_locations', array());
                    $locations[$location] = $menu->term_id;
                    set_theme_mod('nav_menu_locations', $locations);
                }
            }
        }
    }
}

/**
 * Set up homepage
 *
 * @param string $demo_id
 */
function aqualuxe_setup_homepage($demo_id) {
    // Get demo homepage
    $homepage_file = AQUALUXE_DIR . 'inc/demo-content/' . $demo_id . '/homepage.json';
    
    if (file_exists($homepage_file)) {
        $homepage = json_decode(file_get_contents($homepage_file), true);
        
        if ($homepage && isset($homepage['page_title'])) {
            // Find homepage by title
            $page = get_page_by_title($homepage['page_title']);
            
            if ($page) {
                // Set homepage
                update_option('page_on_front', $page->ID);
                update_option('show_on_front', 'page');
            }
        }
    }
    
    // Set blog page
    $blog_page = get_page_by_title('Blog');
    
    if ($blog_page) {
        update_option('page_for_posts', $blog_page->ID);
    }
}

/**
 * Finalize import
 *
 * @param string $demo_id
 */
function aqualuxe_finalize_import($demo_id) {
    // Regenerate CSS
    if (function_exists('aqualuxe_generate_css')) {
        aqualuxe_generate_css();
    }
    
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Update import status
    update_option('aqualuxe_demo_imported', $demo_id);
    update_option('aqualuxe_demo_import_time', time());
}