<?php
/**
 * AquaLuxe Theme Diagnostic Page
 * 
 * This page helps diagnose theme architecture issues
 * Template Name: Theme Diagnostics
 * 
 * @package AquaLuxe
 * @since 1.2.0
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main diagnostics-page" role="main">
    <div class="container py-8">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-6">🔧 AquaLuxe Theme Diagnostics</h1>
                
                <?php if (!current_user_can('administrator')): ?>
                    <div class="alert alert-danger">
                        ⚠️ Access denied. Administrator privileges required.
                    </div>
                <?php else: ?>
                
                <!-- PHP Version & WordPress Info -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h2 class="card-title">🖥️ System Information</h2>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>PHP Version:</strong> <?php echo PHP_VERSION; ?></li>
                                    <li><strong>WordPress Version:</strong> <?php echo get_bloginfo('version'); ?></li>
                                    <li><strong>Theme Version:</strong> <?php echo defined('AQUALUXE_THEME_VERSION') ? AQUALUXE_THEME_VERSION : 'Not defined'; ?></li>
                                    <li><strong>WP Debug:</strong> <?php echo defined('WP_DEBUG') && WP_DEBUG ? '✅ Enabled' : '❌ Disabled'; ?></li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul class="list-unstyled">
                                    <li><strong>Multisite:</strong> <?php echo is_multisite() ? '✅ Yes' : '❌ No'; ?></li>
                                    <li><strong>Theme Directory:</strong> <?php echo defined('AQUALUXE_THEME_DIR') ? '✅ Defined' : '❌ Not defined'; ?></li>
                                    <li><strong>Theme URL:</strong> <?php echo defined('AQUALUXE_THEME_URL') ? '✅ Defined' : '❌ Not defined'; ?></li>
                                    <li><strong>Active Theme:</strong> <?php echo wp_get_theme()->get('Name'); ?></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Application Status -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h2 class="card-title">🚀 Application Status</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $app_status = [];
                        
                        // Check if Application class exists
                        if (class_exists('\AquaLuxe\Core\Application')) {
                            $app_status['application_class'] = '✅ Application class loaded';
                            
                            try {
                                $app = \AquaLuxe\Core\Application::get_instance();
                                $app_status['application_instance'] = '✅ Application instance created';
                                
                                // Application instance created successfully
                                $app_status['application_initialized'] = '✅ Application instance created';
                                
                            } catch (Exception $e) {
                                $app_status['application_instance'] = '❌ Application instance error: ' . $e->getMessage();
                            }
                        } else {
                            $app_status['application_class'] = '❌ Application class not found';
                        }
                        
                        // Check Core Loader
                        if (class_exists('\AquaLuxe\Core\Core_Loader')) {
                            $app_status['core_loader'] = '✅ Core Loader class loaded';
                        } else {
                            $app_status['core_loader'] = '❌ Core Loader class not found';
                        }
                        
                        foreach ($app_status as $check => $status): ?>
                            <p class="mb-2"><strong><?php echo ucwords(str_replace('_', ' ', $check)); ?>:</strong> <?php echo $status; ?></p>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Module Status -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h2 class="card-title">📦 Module Status</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $modules = [
                            'Theme_Setup' => '\AquaLuxe\Core\Theme_Setup',
                            'Theme_Scripts' => '\AquaLuxe\Core\Theme_Scripts',
                            'Custom_Post_Types' => '\AquaLuxe\Modules\Custom_Post_Types\Custom_Post_Types',
                            'Custom_Taxonomies' => '\AquaLuxe\Modules\Custom_Taxonomies\Custom_Taxonomies',
                            'Wholesale' => '\AquaLuxe\Modules\Wholesale\Wholesale',
                            'UI_UX' => '\AquaLuxe\Modules\Ui_Ux\Ui_Ux',
                            'Dark_Mode' => '\AquaLuxe\Modules\Dark_Mode\Dark_Mode',
                            'Demo_Importer' => '\AquaLuxe\Modules\Demo_Importer\Demo_Importer',
                            'WooCommerce' => '\AquaLuxe\Modules\WooCommerce\WooCommerce',
                        ];
                        
                        foreach ($modules as $name => $class): ?>
                            <div class="mb-3">
                                <strong><?php echo $name; ?>:</strong>
                                <?php if (class_exists($class)): ?>
                                    <span class="text-success">✅ Class exists</span>
                                <?php else: ?>
                                    <span class="text-danger">❌ Class not found</span>
                                <?php endif; ?>
                                
                                <?php if ($name === 'WooCommerce'): ?>
                                    | WooCommerce Plugin: <?php echo class_exists('WooCommerce') ? '✅ Active' : '❌ Not active'; ?>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Plugin Dependencies -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h2 class="card-title">🔌 Plugin Dependencies</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $plugins = [
                            'WooCommerce' => 'WooCommerce',
                            'Advanced Custom Fields' => 'ACF',
                            'Yoast SEO' => 'WPSEO_VERSION',
                            'Contact Form 7' => 'WPCF7_VERSION',
                        ];
                        
                        foreach ($plugins as $plugin_name => $check): ?>
                            <p class="mb-2">
                                <strong><?php echo $plugin_name; ?>:</strong>
                                <?php if (class_exists($check) || defined($check)): ?>
                                    <span class="text-success">✅ Active</span>
                                <?php else: ?>
                                    <span class="text-muted">⚪ Not active</span>
                                <?php endif; ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Asset Files -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h2 class="card-title">📁 Asset Files</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $asset_paths = [
                            'Main CSS' => 'assets/dist/css/main.css',
                            'Main JS' => 'assets/dist/js/app.js',
                            'Style CSS' => 'style.css',
                            'Package JSON' => 'package.json',
                            'Webpack Mix' => 'webpack.mix.js',
                        ];
                        
                        foreach ($asset_paths as $name => $path): 
                            $full_path = get_template_directory() . '/' . $path;
                            ?>
                            <p class="mb-2">
                                <strong><?php echo $name; ?>:</strong>
                                <?php if (file_exists($full_path)): ?>
                                    <span class="text-success">✅ Exists</span>
                                    <small class="text-muted">(<?php echo date('Y-m-d H:i:s', filemtime($full_path)); ?>)</small>
                                <?php else: ?>
                                    <span class="text-danger">❌ Missing</span>
                                    <small class="text-muted">(<?php echo $path; ?>)</small>
                                <?php endif; ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Template Files -->
                <div class="card mb-6">
                    <div class="card-header">
                        <h2 class="card-title">📄 Template Files</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $template_files = [
                            'header.php',
                            'footer.php',
                            'index.php',
                            'single.php',
                            'page.php',
                            'archive.php',
                            'functions.php',
                            'page-demo.php',
                        ];
                        
                        $existing_templates = [];
                        $missing_templates = [];
                        
                        foreach ($template_files as $template) {
                            $template_path = get_template_directory() . '/' . $template;
                            if (file_exists($template_path)) {
                                $existing_templates[] = $template;
                            } else {
                                $missing_templates[] = $template;
                            }
                        }
                        ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <h4 class="text-success">✅ Existing Templates</h4>
                                <ul class="list-unstyled">
                                    <?php foreach ($existing_templates as $template): ?>
                                        <li class="mb-1">📄 <?php echo $template; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            
                            <?php if (!empty($missing_templates)): ?>
                            <div class="col-md-6">
                                <h4 class="text-danger">❌ Missing Templates</h4>
                                <ul class="list-unstyled">
                                    <?php foreach ($missing_templates as $template): ?>
                                        <li class="mb-1">📄 <?php echo $template; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Recent Errors -->
                <?php if (defined('WP_DEBUG') && WP_DEBUG): ?>
                <div class="card mb-6">
                    <div class="card-header">
                        <h2 class="card-title">🐛 Recent Debug Information</h2>
                    </div>
                    <div class="card-body">
                        <?php
                        $debug_log = wp_upload_dir()['basedir'] . '/debug.log';
                        if (file_exists($debug_log)) {
                            $log_contents = file_get_contents($debug_log);
                            $log_lines = explode("\n", $log_contents);
                            $recent_lines = array_slice($log_lines, -20); // Last 20 lines
                            
                            echo '<pre class="bg-dark text-white p-3 rounded" style="max-height: 300px; overflow-y: auto;">';
                            foreach ($recent_lines as $line) {
                                if (!empty(trim($line))) {
                                    echo htmlspecialchars($line) . "\n";
                                }
                            }
                            echo '</pre>';
                        } else {
                            echo '<p class="text-muted">No debug log file found.</p>';
                        }
                        ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h2 class="card-title">⚡ Quick Actions</h2>
                    </div>
                    <div class="card-body">
                        <div class="d-flex gap-3 flex-wrap">
                            <a href="<?php echo admin_url('themes.php'); ?>" class="btn btn-primary">
                                🎨 Manage Themes
                            </a>
                            <a href="<?php echo admin_url('plugins.php'); ?>" class="btn btn-secondary">
                                🔌 Manage Plugins
                            </a>
                            <a href="<?php echo admin_url('options-general.php'); ?>" class="btn btn-outline">
                                ⚙️ Settings
                            </a>
                            <?php if (get_page_by_path('demo')): ?>
                            <a href="<?php echo home_url('/demo/'); ?>" class="btn btn-success">
                                🌊 View Demo Page
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="mt-4">
                            <small class="text-muted">
                                💡 <strong>Tip:</strong> If you see any errors above, check the WordPress debug log 
                                or contact the theme developer with the information from this page.
                            </small>
                        </div>
                    </div>
                </div>

                <?php endif; // End administrator check ?>
            </div>
        </div>
    </div>
</main>

<style>
.diagnostics-page pre {
    font-size: 0.875rem;
    line-height: 1.4;
}

.diagnostics-page .card {
    border: 1px solid var(--color-gray-200);
    border-radius: var(--border-radius-lg);
    margin-bottom: 1.5rem;
}

.diagnostics-page .card-header {
    background-color: var(--color-gray-50);
    border-bottom: 1px solid var(--color-gray-200);
    padding: 1rem 1.5rem;
}

.diagnostics-page .card-body {
    padding: 1.5rem;
}

.diagnostics-page .text-success {
    color: var(--color-success-600) !important;
}

.diagnostics-page .text-danger {
    color: var(--color-danger-600) !important;
}

.diagnostics-page .text-muted {
    color: var(--color-gray-500) !important;
}

.diagnostics-page .alert {
    padding: 1rem;
    border-radius: var(--border-radius-md);
    margin-bottom: 1.5rem;
}

.diagnostics-page .alert-danger {
    background-color: var(--color-danger-50);
    border: 1px solid var(--color-danger-200);
    color: var(--color-danger-800);
}
</style>

<?php get_footer(); ?>
