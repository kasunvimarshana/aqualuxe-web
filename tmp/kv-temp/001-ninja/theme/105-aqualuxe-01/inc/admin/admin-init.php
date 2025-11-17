<?php
/**
 * Admin Initialization
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Initialize admin functionality
 */
function aqualuxe_admin_init() {
    // Add theme admin page
    add_action('admin_menu', 'aqualuxe_add_admin_page');
    
    // Add admin notices
    add_action('admin_notices', 'aqualuxe_admin_notices');
    
    // Add admin scripts and styles
    add_action('admin_enqueue_scripts', 'aqualuxe_admin_scripts');
}
add_action('admin_init', 'aqualuxe_admin_init');

/**
 * Add enhanced theme admin page with multiple tabs
 */
function aqualuxe_add_admin_page() {
    $page_hook = add_theme_page(
        __('AquaLuxe Theme Dashboard', 'aqualuxe'),
        __('AquaLuxe', 'aqualuxe'),
        'manage_options',
        'aqualuxe-dashboard',
        'aqualuxe_admin_dashboard_callback'
    );
    
    // Add help tabs to the admin page
    add_action("load-{$page_hook}", 'aqualuxe_add_admin_help_tabs');
}

/**
 * Enhanced admin dashboard callback with tabs
 */
function aqualuxe_admin_dashboard_callback() {
    $active_tab = $_GET['tab'] ?? 'dashboard';
    ?>
    <div class="wrap aqualuxe-admin-wrap">
        <h1 class="wp-heading-inline">
            <?php esc_html_e('AquaLuxe Theme Dashboard', 'aqualuxe'); ?>
        </h1>
        
        <nav class="nav-tab-wrapper wp-clearfix">
            <a href="?page=aqualuxe-dashboard&tab=dashboard" class="nav-tab <?php echo $active_tab === 'dashboard' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Dashboard', 'aqualuxe'); ?>
            </a>
            <a href="?page=aqualuxe-dashboard&tab=modules" class="nav-tab <?php echo $active_tab === 'modules' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Modules', 'aqualuxe'); ?>
            </a>
            <a href="?page=aqualuxe-dashboard&tab=demo-importer" class="nav-tab <?php echo $active_tab === 'demo-importer' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Demo Importer', 'aqualuxe'); ?>
            </a>
            <a href="?page=aqualuxe-dashboard&tab=performance" class="nav-tab <?php echo $active_tab === 'performance' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('Performance', 'aqualuxe'); ?>
            </a>
            <a href="?page=aqualuxe-dashboard&tab=system-info" class="nav-tab <?php echo $active_tab === 'system-info' ? 'nav-tab-active' : ''; ?>">
                <?php esc_html_e('System Info', 'aqualuxe'); ?>
            </a>
        </nav>
        
        <div class="aqualuxe-admin-content">
            <?php
            switch ($active_tab) {
                case 'dashboard':
                    aqualuxe_admin_dashboard_tab();
                    break;
                case 'modules':
                    aqualuxe_admin_modules_tab();
                    break;
                case 'demo-importer':
                    aqualuxe_admin_demo_importer_tab();
                    break;
                case 'performance':
                    aqualuxe_admin_performance_tab();
                    break;
                case 'system-info':
                    aqualuxe_admin_system_info_tab();
                    break;
                default:
                    aqualuxe_admin_dashboard_tab();
            }
            ?>
        </div>
    </div>
    <?php
}

/**
 * Check if WooCommerce is active
 */
function aqualuxe_is_woocommerce_active() {
    return class_exists('WooCommerce');
}
function aqualuxe_admin_dashboard_tab() {
    $theme = wp_get_theme();
    $active_modules = get_option('aqualuxe_enabled_modules', array());
    
    ?>
    <div class="aqualuxe-dashboard-grid">
        <div class="aqualuxe-card">
            <h2><?php esc_html_e('Theme Overview', 'aqualuxe'); ?></h2>
            <table class="widefat">
                <tr>
                    <td><strong><?php esc_html_e('Theme Version:', 'aqualuxe'); ?></strong></td>
                    <td><?php echo esc_html($theme->get('Version')); ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e('Active Modules:', 'aqualuxe'); ?></strong></td>
                    <td><?php echo count(array_filter($active_modules)); ?></td>
                </tr>
                <tr>
                    <td><strong><?php esc_html_e('WooCommerce:', 'aqualuxe'); ?></strong></td>
                    <td><?php echo aqualuxe_is_woocommerce_active() ? esc_html__('Active', 'aqualuxe') : esc_html__('Inactive', 'aqualuxe'); ?></td>
                </tr>
            </table>
        </div>
        
        <div class="aqualuxe-card">
            <h2><?php esc_html_e('Quick Actions', 'aqualuxe'); ?></h2>
            <p>
                <a href="<?php echo admin_url('customize.php'); ?>" class="button button-primary">
                    <?php esc_html_e('Customize Theme', 'aqualuxe'); ?>
                </a>
                <a href="?page=aqualuxe-dashboard&tab=demo-importer" class="button">
                    <?php esc_html_e('Import Demo Content', 'aqualuxe'); ?>
                </a>
            </p>
        </div>
        
        <div class="aqualuxe-card">
            <h2><?php esc_html_e('System Status', 'aqualuxe'); ?></h2>
            <?php aqualuxe_display_system_status(); ?>
        </div>
    </div>
    <?php
}

/**
 * Modules tab content
 */
function aqualuxe_admin_modules_tab() {
    if (isset($_POST['save_modules']) && wp_verify_nonce($_POST['_wpnonce'], 'aqualuxe_modules')) {
        $enabled_modules = array_map('sanitize_text_field', $_POST['modules'] ?? array());
        update_option('aqualuxe_enabled_modules', $enabled_modules);
        echo '<div class="notice notice-success"><p>' . esc_html__('Module settings saved!', 'aqualuxe') . '</p></div>';
    }
    
    $module_manager = \AquaLuxe\Core\ModuleManager::get_instance();
    $modules = $module_manager->get_module_configs();
    $enabled_modules = get_option('aqualuxe_enabled_modules', array());
    
    ?>
    <form method="post" action="">
        <?php wp_nonce_field('aqualuxe_modules'); ?>
        
        <div class="aqualuxe-modules-grid">
            <?php foreach ($modules as $module_id => $module_config) : ?>
            <div class="aqualuxe-module-card">
                <div class="module-header">
                    <h3><?php echo esc_html($module_config['name']); ?></h3>
                    <label class="module-toggle">
                        <input type="checkbox" name="modules[<?php echo esc_attr($module_id); ?>]" value="1" 
                               <?php checked(isset($enabled_modules[$module_id]) && $enabled_modules[$module_id]); ?>>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
                <p class="module-description"><?php echo esc_html($module_config['description']); ?></p>
                <div class="module-meta">
                    <span class="module-category"><?php echo esc_html(ucfirst($module_config['category'])); ?></span>
                    <span class="module-version">v<?php echo esc_html($module_config['version']); ?></span>
                </div>
                <?php if (!empty($module_config['dependencies'])) : ?>
                <div class="module-dependencies">
                    <small><?php esc_html_e('Requires:', 'aqualuxe'); ?> <?php echo esc_html(implode(', ', $module_config['dependencies'])); ?></small>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        
        <p class="submit">
            <input type="submit" name="save_modules" class="button button-primary" value="<?php esc_attr_e('Save Module Settings', 'aqualuxe'); ?>">
        </p>
    </form>
    <?php
}

/**
 * Demo Importer tab content
 */
function aqualuxe_admin_demo_importer_tab() {
    ?>
    <div class="aqualuxe-demo-importer">
        <div class="importer-section">
            <h2><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></h2>
            <p><?php esc_html_e('Import demo content to quickly set up your site with sample data.', 'aqualuxe'); ?></p>
            
            <div class="import-options">
                <h3><?php esc_html_e('Import Options', 'aqualuxe'); ?></h3>
                <label>
                    <input type="radio" name="import_type" value="full" checked>
                    <?php esc_html_e('Full Import (Recommended)', 'aqualuxe'); ?>
                </label>
                <label>
                    <input type="radio" name="import_type" value="selective">
                    <?php esc_html_e('Selective Import', 'aqualuxe'); ?>
                </label>
                <label>
                    <input type="radio" name="import_type" value="content_only">
                    <?php esc_html_e('Content Only', 'aqualuxe'); ?>
                </label>
            </div>
            
            <div class="selective-options" style="display: none;">
                <h4><?php esc_html_e('Select Content to Import:', 'aqualuxe'); ?></h4>
                <label><input type="checkbox" name="selective_options[]" value="posts"> <?php esc_html_e('Posts', 'aqualuxe'); ?></label>
                <label><input type="checkbox" name="selective_options[]" value="pages"> <?php esc_html_e('Pages', 'aqualuxe'); ?></label>
                <label><input type="checkbox" name="selective_options[]" value="products"> <?php esc_html_e('Products', 'aqualuxe'); ?></label>
                <label><input type="checkbox" name="selective_options[]" value="media"> <?php esc_html_e('Media', 'aqualuxe'); ?></label>
                <label><input type="checkbox" name="selective_options[]" value="menus"> <?php esc_html_e('Menus', 'aqualuxe'); ?></label>
                <label><input type="checkbox" name="selective_options[]" value="customizer"> <?php esc_html_e('Customizer Settings', 'aqualuxe'); ?></label>
            </div>
            
            <div class="import-actions">
                <button type="button" class="button button-primary" id="start-import">
                    <?php esc_html_e('Start Import', 'aqualuxe'); ?>
                </button>
                <button type="button" class="button button-secondary" id="reset-content">
                    <?php esc_html_e('Reset All Content', 'aqualuxe'); ?>
                </button>
            </div>
        </div>
        
        <div class="import-progress" style="display: none;">
            <h3><?php esc_html_e('Import Progress', 'aqualuxe'); ?></h3>
            <div class="progress-bar">
                <div class="progress-fill" style="width: 0%"></div>
            </div>
            <div class="progress-info">
                <span class="progress-text"><?php esc_html_e('Preparing import...', 'aqualuxe'); ?></span>
                <span class="progress-percentage">0%</span>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Performance tab content
 */
function aqualuxe_admin_performance_tab() {
    ?>
    <div class="aqualuxe-performance">
        <h2><?php esc_html_e('Performance Settings', 'aqualuxe'); ?></h2>
        
        <form method="post" action="options.php">
            <?php settings_fields('aqualuxe_performance'); ?>
            
            <table class="form-table">
                <tr>
                    <th scope="row"><?php esc_html_e('Critical CSS', 'aqualuxe'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="aqualuxe_critical_css" value="1" 
                                   <?php checked(get_option('aqualuxe_critical_css', 1)); ?>>
                            <?php esc_html_e('Enable critical CSS inlining', 'aqualuxe'); ?>
                        </label>
                        <p class="description"><?php esc_html_e('Inline critical CSS for above-the-fold content.', 'aqualuxe'); ?></p>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Lazy Loading', 'aqualuxe'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="aqualuxe_lazy_loading" value="1" 
                                   <?php checked(get_option('aqualuxe_lazy_loading', 1)); ?>>
                            <?php esc_html_e('Enable lazy loading for images', 'aqualuxe'); ?>
                        </label>
                    </td>
                </tr>
                <tr>
                    <th scope="row"><?php esc_html_e('Asset Optimization', 'aqualuxe'); ?></th>
                    <td>
                        <label>
                            <input type="checkbox" name="aqualuxe_defer_scripts" value="1" 
                                   <?php checked(get_option('aqualuxe_defer_scripts', 1)); ?>>
                            <?php esc_html_e('Defer non-critical JavaScript', 'aqualuxe'); ?>
                        </label>
                    </td>
                </tr>
            </table>
            
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

/**
 * System Info tab content
 */
function aqualuxe_admin_system_info_tab() {
    ?>
    <div class="aqualuxe-system-info">
        <h2><?php esc_html_e('System Information', 'aqualuxe'); ?></h2>
        
        <div class="system-info-grid">
            <div class="info-section">
                <h3><?php esc_html_e('WordPress Environment', 'aqualuxe'); ?></h3>
                <table class="widefat">
                    <tr>
                        <td><?php esc_html_e('WordPress Version:', 'aqualuxe'); ?></td>
                        <td><?php echo esc_html(get_bloginfo('version')); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('PHP Version:', 'aqualuxe'); ?></td>
                        <td><?php echo esc_html(PHP_VERSION); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('Memory Limit:', 'aqualuxe'); ?></td>
                        <td><?php echo esc_html(ini_get('memory_limit')); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('Max Execution Time:', 'aqualuxe'); ?></td>
                        <td><?php echo esc_html(ini_get('max_execution_time')); ?>s</td>
                    </tr>
                </table>
            </div>
            
            <div class="info-section">
                <h3><?php esc_html_e('Theme Information', 'aqualuxe'); ?></h3>
                <?php
                $theme = wp_get_theme();
                $parent_theme = $theme->parent();
                ?>
                <table class="widefat">
                    <tr>
                        <td><?php esc_html_e('Theme Name:', 'aqualuxe'); ?></td>
                        <td><?php echo esc_html($theme->get('Name')); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('Theme Version:', 'aqualuxe'); ?></td>
                        <td><?php echo esc_html($theme->get('Version')); ?></td>
                    </tr>
                    <tr>
                        <td><?php esc_html_e('Parent Theme:', 'aqualuxe'); ?></td>
                        <td><?php echo $parent_theme ? esc_html($parent_theme->get('Name')) : esc_html__('No parent theme', 'aqualuxe'); ?></td>
                    </tr>
                </table>
            </div>
        </div>
        
        <div class="system-tools">
            <h3><?php esc_html_e('System Tools', 'aqualuxe'); ?></h3>
            <p>
                <button type="button" class="button" onclick="aqualuxeExportSystemInfo()">
                    <?php esc_html_e('Export System Info', 'aqualuxe'); ?>
                </button>
                <button type="button" class="button" onclick="aqualuxeClearCache()">
                    <?php esc_html_e('Clear Theme Cache', 'aqualuxe'); ?>
                </button>
            </p>
        </div>
    </div>
    <?php
}

/**
 * Display system status indicators
 */
function aqualuxe_display_system_status() {
    $status_items = array(
        'php_version' => array(
            'label' => __('PHP Version', 'aqualuxe'),
            'value' => PHP_VERSION,
            'status' => version_compare(PHP_VERSION, '7.4', '>=') ? 'good' : 'warning',
        ),
        'memory_limit' => array(
            'label' => __('Memory Limit', 'aqualuxe'),
            'value' => ini_get('memory_limit'),
            'status' => (int) ini_get('memory_limit') >= 128 ? 'good' : 'warning',
        ),
        'woocommerce' => array(
            'label' => __('WooCommerce', 'aqualuxe'),
            'value' => aqualuxe_is_woocommerce_active() ? __('Active', 'aqualuxe') : __('Inactive', 'aqualuxe'),
            'status' => aqualuxe_is_woocommerce_active() ? 'good' : 'info',
        ),
    );
    
    ?>
    <ul class="system-status-list">
        <?php foreach ($status_items as $item) : ?>
        <li class="status-item status-<?php echo esc_attr($item['status']); ?>">
            <span class="status-label"><?php echo esc_html($item['label']); ?>:</span>
            <span class="status-value"><?php echo esc_html($item['value']); ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
    <?php
}

/**
 * Add help tabs to admin page
 */
function aqualuxe_add_admin_help_tabs() {
    $screen = get_current_screen();
    
    $screen->add_help_tab(array(
        'id' => 'aqualuxe-overview',
        'title' => __('Overview', 'aqualuxe'),
        'content' => '<p>' . __('The AquaLuxe dashboard provides access to theme settings, module management, demo content import, and system information.', 'aqualuxe') . '</p>',
    ));
    
    $screen->add_help_tab(array(
        'id' => 'aqualuxe-modules',
        'title' => __('Modules', 'aqualuxe'),
        'content' => '<p>' . __('Enable or disable theme modules to customize functionality. Some modules may have dependencies that need to be enabled first.', 'aqualuxe') . '</p>',
    ));
    
    $screen->set_help_sidebar(
        '<p><strong>' . __('For more information:', 'aqualuxe') . '</strong></p>' .
        '<p><a href="#" target="_blank">' . __('Theme Documentation', 'aqualuxe') . '</a></p>' .
        '<p><a href="#" target="_blank">' . __('Support Forum', 'aqualuxe') . '</a></p>'
    );
}