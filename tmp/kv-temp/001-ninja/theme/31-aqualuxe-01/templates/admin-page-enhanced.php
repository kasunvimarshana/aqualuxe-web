<?php
/**
 * Demo Content Importer Admin Page Template (Enhanced)
 *
 * @package DemoContentImporter
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap dci-admin-page">
    <h1><?php esc_html_e('Demo Content Importer', 'demo-content-importer'); ?></h1>
    
    <div class="dci-admin-notices">
        <?php if (isset($notices) && !empty($notices)) : ?>
            <?php foreach ($notices as $notice) : ?>
                <div class="dci-notice dci-notice-<?php echo esc_attr($notice['type']); ?>">
                    <p><?php echo wp_kses_post($notice['message']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    
    <div class="dci-admin-tabs">
        <nav class="dci-admin-tabs-nav">
            <a href="#demos" class="dci-admin-tab active"><?php esc_html_e('Demo Content', 'demo-content-importer'); ?></a>
            <a href="#backup" class="dci-admin-tab"><?php esc_html_e('Backup & Restore', 'demo-content-importer'); ?></a>
            <a href="#reset" class="dci-admin-tab"><?php esc_html_e('Reset', 'demo-content-importer'); ?></a>
            <a href="#settings" class="dci-admin-tab"><?php esc_html_e('Settings', 'demo-content-importer'); ?></a>
            <a href="#logs" class="dci-admin-tab"><?php esc_html_e('Logs', 'demo-content-importer'); ?></a>
        </nav>
        
        <div class="dci-admin-tabs-content">
            <!-- Demo Content Tab -->
            <div id="demos" class="dci-admin-tab-content active">
                <h2><?php esc_html_e('Available Demo Content', 'demo-content-importer'); ?></h2>
                
                <div class="dci-demo-grid">
                    <?php if (isset($demo_packages) && !empty($demo_packages)) : ?>
                        <?php foreach ($demo_packages as $demo_id => $demo) : ?>
                            <div class="dci-demo-item" data-demo-id="<?php echo esc_attr($demo_id); ?>">
                                <div class="dci-demo-item-inner">
                                    <div class="dci-demo-screenshot">
                                        <img src="<?php echo esc_url($demo['screenshot']); ?>" alt="<?php echo esc_attr($demo['name']); ?>">
                                    </div>
                                    <div class="dci-demo-info">
                                        <h3 class="dci-demo-name"><?php echo esc_html($demo['name']); ?></h3>
                                        <p class="dci-demo-description"><?php echo esc_html($demo['description']); ?></p>
                                    </div>
                                    <div class="dci-demo-actions">
                                        <a href="<?php echo esc_url($demo['preview_url']); ?>" class="button" target="_blank"><?php esc_html_e('Preview', 'demo-content-importer'); ?></a>
                                        <button class="button button-primary dci-import-demo" data-demo-id="<?php echo esc_attr($demo_id); ?>"><?php esc_html_e('Import', 'demo-content-importer'); ?></button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <div class="dci-notice dci-notice-info">
                            <p><?php esc_html_e('No demo packages found. Please check your theme integration.', 'demo-content-importer'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Import Modal -->
                <div id="dci-import-modal" class="dci-modal">
                    <div class="dci-modal-content">
                        <span class="dci-modal-close">&times;</span>
                        <h2><?php esc_html_e('Import Demo Content', 'demo-content-importer'); ?></h2>
                        
                        <div id="dci-import-options" class="dci-modal-body">
                            <div class="dci-import-preview">
                                <img src="" alt="" class="dci-preview-image">
                                <p class="dci-preview-description"></p>
                            </div>
                            
                            <div class="dci-import-options">
                                <h3><?php esc_html_e('Import Options', 'demo-content-importer'); ?></h3>
                                
                                <div class="dci-import-option">
                                    <div class="dci-import-option-header">
                                        <input type="checkbox" id="dci-import-content" checked>
                                        <label for="dci-import-content" class="dci-import-option-title"><?php esc_html_e('Content', 'demo-content-importer'); ?></label>
                                    </div>
                                    <p class="dci-import-option-description"><?php esc_html_e('Import posts, pages, and custom post types.', 'demo-content-importer'); ?></p>
                                </div>
                                
                                <div class="dci-import-option">
                                    <div class="dci-import-option-header">
                                        <input type="checkbox" id="dci-import-media" checked>
                                        <label for="dci-import-media" class="dci-import-option-title"><?php esc_html_e('Media', 'demo-content-importer'); ?></label>
                                    </div>
                                    <p class="dci-import-option-description"><?php esc_html_e('Import images and other media files.', 'demo-content-importer'); ?></p>
                                </div>
                                
                                <div class="dci-import-option">
                                    <div class="dci-import-option-header">
                                        <input type="checkbox" id="dci-import-widgets" checked>
                                        <label for="dci-import-widgets" class="dci-import-option-title"><?php esc_html_e('Widgets', 'demo-content-importer'); ?></label>
                                    </div>
                                    <p class="dci-import-option-description"><?php esc_html_e('Import widget configurations.', 'demo-content-importer'); ?></p>
                                </div>
                                
                                <div class="dci-import-option">
                                    <div class="dci-import-option-header">
                                        <input type="checkbox" id="dci-import-customizer" checked>
                                        <label for="dci-import-customizer" class="dci-import-option-title"><?php esc_html_e('Customizer Settings', 'demo-content-importer'); ?></label>
                                    </div>
                                    <p class="dci-import-option-description"><?php esc_html_e('Import theme customizer settings.', 'demo-content-importer'); ?></p>
                                </div>
                                
                                <div class="dci-import-option">
                                    <div class="dci-import-option-header">
                                        <input type="checkbox" id="dci-import-options" checked>
                                        <label for="dci-import-options" class="dci-import-option-title"><?php esc_html_e('Theme Options', 'demo-content-importer'); ?></label>
                                    </div>
                                    <p class="dci-import-option-description"><?php esc_html_e('Import theme options and settings.', 'demo-content-importer'); ?></p>
                                </div>
                                
                                <div class="dci-import-option">
                                    <div class="dci-import-option-header">
                                        <input type="checkbox" id="dci-import-menus" checked>
                                        <label for="dci-import-menus" class="dci-import-option-title"><?php esc_html_e('Menus', 'demo-content-importer'); ?></label>
                                    </div>
                                    <p class="dci-import-option-description"><?php esc_html_e('Set up navigation menus.', 'demo-content-importer'); ?></p>
                                </div>
                                
                                <div class="dci-import-option">
                                    <div class="dci-import-option-header">
                                        <input type="checkbox" id="dci-import-plugins" checked>
                                        <label for="dci-import-plugins" class="dci-import-option-title"><?php esc_html_e('Plugins', 'demo-content-importer'); ?></label>
                                    </div>
                                    <p class="dci-import-option-description"><?php esc_html_e('Install and activate required plugins.', 'demo-content-importer'); ?></p>
                                </div>
                                
                                <div class="dci-import-option">
                                    <div class="dci-import-option-header">
                                        <input type="checkbox" id="dci-import-replace">
                                        <label for="dci-import-replace" class="dci-import-option-title"><?php esc_html_e('Replace Existing Content', 'demo-content-importer'); ?></label>
                                    </div>
                                    <p class="dci-import-option-description"><?php esc_html_e('Replace existing content with demo content. Warning: This will delete your existing content.', 'demo-content-importer'); ?></p>
                                </div>
                            </div>
                            
                            <div class="dci-modal-actions">
                                <button id="dci-cancel-import" class="button"><?php esc_html_e('Cancel', 'demo-content-importer'); ?></button>
                                <button id="dci-start-import" class="button button-primary" data-demo-id=""><?php esc_html_e('Start Import', 'demo-content-importer'); ?></button>
                            </div>
                        </div>
                        
                        <!-- Progress Indicator (Initially Hidden) -->
                        <?php include_once DCI_DIR . '/templates/progress-template.php'; ?>
                    </div>
                </div>
            </div>
            
            <!-- Backup & Restore Tab -->
            <div id="backup" class="dci-admin-tab-content">
                <h2><?php esc_html_e('Backup & Restore', 'demo-content-importer'); ?></h2>
                
                <div class="dci-backup-options">
                    <h3><?php esc_html_e('Create Backup', 'demo-content-importer'); ?></h3>
                    
                    <p><?php esc_html_e('Create a backup of your site before importing demo content or making significant changes.', 'demo-content-importer'); ?></p>
                    
                    <div class="dci-backup-form">
                        <div class="dci-backup-option">
                            <input type="checkbox" id="dci-backup-database" checked>
                            <label for="dci-backup-database"><?php esc_html_e('Database', 'demo-content-importer'); ?></label>
                            <p class="description"><?php esc_html_e('Backup all WordPress database tables.', 'demo-content-importer'); ?></p>
                        </div>
                        
                        <div class="dci-backup-option">
                            <input type="checkbox" id="dci-backup-files" checked>
                            <label for="dci-backup-files"><?php esc_html_e('Files', 'demo-content-importer'); ?></label>
                            <p class="description"><?php esc_html_e('Backup uploads, themes, and plugins.', 'demo-content-importer'); ?></p>
                        </div>
                        
                        <div class="dci-backup-option">
                            <label for="dci-backup-name"><?php esc_html_e('Backup Name', 'demo-content-importer'); ?></label>
                            <input type="text" id="dci-backup-name" value="<?php echo esc_attr('backup-' . date('Y-m-d-H-i-s')); ?>">
                        </div>
                        
                        <div class="dci-backup-actions">
                            <button id="dci-create-backup" class="button button-primary"><?php esc_html_e('Create Backup', 'demo-content-importer'); ?></button>
                        </div>
                    </div>
                </div>
                
                <div class="dci-restore-options">
                    <h3><?php esc_html_e('Restore Backup', 'demo-content-importer'); ?></h3>
                    
                    <p><?php esc_html_e('Restore your site from a previous backup.', 'demo-content-importer'); ?></p>
                    
                    <div class="dci-backup-list">
                        <?php if (isset($backups) && !empty($backups)) : ?>
                            <?php foreach ($backups as $backup_id => $backup) : ?>
                                <div class="dci-backup-item" data-backup-id="<?php echo esc_attr($backup_id); ?>">
                                    <div class="dci-backup-info">
                                        <div class="dci-backup-name"><?php echo esc_html($backup['name']); ?></div>
                                        <div class="dci-backup-meta">
                                            <?php echo esc_html(date('F j, Y, g:i a', $backup['time'])); ?> &bull;
                                            <?php echo esc_html(size_format($backup['size'])); ?> &bull;
                                            <?php echo esc_html($backup['type']); ?>
                                        </div>
                                    </div>
                                    <div class="dci-backup-actions">
                                        <button class="button dci-download-backup" data-backup-id="<?php echo esc_attr($backup_id); ?>"><?php esc_html_e('Download', 'demo-content-importer'); ?></button>
                                        <button class="button dci-restore-backup" data-backup-id="<?php echo esc_attr($backup_id); ?>"><?php esc_html_e('Restore', 'demo-content-importer'); ?></button>
                                        <button class="button dci-delete-backup" data-backup-id="<?php echo esc_attr($backup_id); ?>"><?php esc_html_e('Delete', 'demo-content-importer'); ?></button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <div class="dci-notice dci-notice-info">
                                <p><?php esc_html_e('No backups found. Create a backup to get started.', 'demo-content-importer'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Reset Tab -->
            <div id="reset" class="dci-admin-tab-content">
                <h2><?php esc_html_e('Reset Site', 'demo-content-importer'); ?></h2>
                
                <div class="dci-reset-warning">
                    <p><strong><?php esc_html_e('Warning:', 'demo-content-importer'); ?></strong> <?php esc_html_e('Resetting your site will permanently delete selected content. This action cannot be undone. Please backup your site before proceeding.', 'demo-content-importer'); ?></p>
                </div>
                
                <div class="dci-reset-options">
                    <h3><?php esc_html_e('Select Content to Reset', 'demo-content-importer'); ?></h3>
                    
                    <div class="dci-reset-option">
                        <input type="checkbox" id="dci-reset-content">
                        <label for="dci-reset-content"><?php esc_html_e('Content', 'demo-content-importer'); ?></label>
                        <p class="description"><?php esc_html_e('Reset posts, pages, and custom post types.', 'demo-content-importer'); ?></p>
                    </div>
                    
                    <div class="dci-reset-option">
                        <input type="checkbox" id="dci-reset-media">
                        <label for="dci-reset-media"><?php esc_html_e('Media', 'demo-content-importer'); ?></label>
                        <p class="description"><?php esc_html_e('Reset media library.', 'demo-content-importer'); ?></p>
                    </div>
                    
                    <div class="dci-reset-option">
                        <input type="checkbox" id="dci-reset-widgets">
                        <label for="dci-reset-widgets"><?php esc_html_e('Widgets', 'demo-content-importer'); ?></label>
                        <p class="description"><?php esc_html_e('Reset widget configurations.', 'demo-content-importer'); ?></p>
                    </div>
                    
                    <div class="dci-reset-option">
                        <input type="checkbox" id="dci-reset-customizer">
                        <label for="dci-reset-customizer"><?php esc_html_e('Customizer', 'demo-content-importer'); ?></label>
                        <p class="description"><?php esc_html_e('Reset theme customizer settings.', 'demo-content-importer'); ?></p>
                    </div>
                    
                    <div class="dci-reset-option">
                        <input type="checkbox" id="dci-reset-options">
                        <label for="dci-reset-options"><?php esc_html_e('Theme Options', 'demo-content-importer'); ?></label>
                        <p class="description"><?php esc_html_e('Reset theme options and settings.', 'demo-content-importer'); ?></p>
                    </div>
                    
                    <div class="dci-reset-option">
                        <input type="checkbox" id="dci-reset-menus">
                        <label for="dci-reset-menus"><?php esc_html_e('Menus', 'demo-content-importer'); ?></label>
                        <p class="description"><?php esc_html_e('Reset navigation menus.', 'demo-content-importer'); ?></p>
                    </div>
                    
                    <div class="dci-reset-option">
                        <input type="checkbox" id="dci-reset-plugins">
                        <label for="dci-reset-plugins"><?php esc_html_e('Plugins', 'demo-content-importer'); ?></label>
                        <p class="description"><?php esc_html_e('Deactivate and reset plugin settings.', 'demo-content-importer'); ?></p>
                    </div>
                    
                    <div class="dci-reset-actions">
                        <button id="dci-reset-site" class="button button-primary"><?php esc_html_e('Reset Site', 'demo-content-importer'); ?></button>
                    </div>
                </div>
            </div>
            
            <!-- Settings Tab -->
            <div id="settings" class="dci-admin-tab-content">
                <h2><?php esc_html_e('Settings', 'demo-content-importer'); ?></h2>
                
                <form class="dci-settings-form" method="post" action="">
                    <?php wp_nonce_field('dci_save_settings', 'dci_settings_nonce'); ?>
                    
                    <div class="dci-settings-section">
                        <h3><?php esc_html_e('General Settings', 'demo-content-importer'); ?></h3>
                        
                        <div class="dci-settings-field">
                            <label for="dci-setting-import-timeout"><?php esc_html_e('Import Timeout (seconds)', 'demo-content-importer'); ?></label>
                            <input type="number" id="dci-setting-import-timeout" name="dci_settings[import_timeout]" value="<?php echo esc_attr(isset($settings['import_timeout']) ? $settings['import_timeout'] : 300); ?>" min="60" max="3600">
                            <p class="description"><?php esc_html_e('Maximum time allowed for each import step. Increase this value if you have a large amount of content to import.', 'demo-content-importer'); ?></p>
                        </div>
                        
                        <div class="dci-settings-field">
                            <label for="dci-setting-backup-dir"><?php esc_html_e('Backup Directory', 'demo-content-importer'); ?></label>
                            <input type="text" id="dci-setting-backup-dir" name="dci_settings[backup_dir]" value="<?php echo esc_attr(isset($settings['backup_dir']) ? $settings['backup_dir'] : 'backups'); ?>">
                            <p class="description"><?php esc_html_e('Directory where backups will be stored. Relative to wp-content directory.', 'demo-content-importer'); ?></p>
                        </div>
                    </div>
                    
                    <div class="dci-settings-section">
                        <h3><?php esc_html_e('Import Settings', 'demo-content-importer'); ?></h3>
                        
                        <div class="dci-settings-field">
                            <label for="dci-setting-log-level"><?php esc_html_e('Log Level', 'demo-content-importer'); ?></label>
                            <select id="dci-setting-log-level" name="dci_settings[log_level]">
                                <option value="debug" <?php selected(isset($settings['log_level']) ? $settings['log_level'] : 'info', 'debug'); ?>><?php esc_html_e('Debug', 'demo-content-importer'); ?></option>
                                <option value="info" <?php selected(isset($settings['log_level']) ? $settings['log_level'] : 'info', 'info'); ?>><?php esc_html_e('Info', 'demo-content-importer'); ?></option>
                                <option value="warning" <?php selected(isset($settings['log_level']) ? $settings['log_level'] : 'info', 'warning'); ?>><?php esc_html_e('Warning', 'demo-content-importer'); ?></option>
                                <option value="error" <?php selected(isset($settings['log_level']) ? $settings['log_level'] : 'info', 'error'); ?>><?php esc_html_e('Error', 'demo-content-importer'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Level of detail for import logs.', 'demo-content-importer'); ?></p>
                        </div>
                        
                        <div class="dci-settings-field">
                            <label for="dci-setting-media-import"><?php esc_html_e('Media Import Method', 'demo-content-importer'); ?></label>
                            <select id="dci-setting-media-import" name="dci_settings[media_import]">
                                <option value="copy" <?php selected(isset($settings['media_import']) ? $settings['media_import'] : 'copy', 'copy'); ?>><?php esc_html_e('Copy (Faster)', 'demo-content-importer'); ?></option>
                                <option value="download" <?php selected(isset($settings['media_import']) ? $settings['media_import'] : 'copy', 'download'); ?>><?php esc_html_e('Download (More Compatible)', 'demo-content-importer'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Method used to import media files.', 'demo-content-importer'); ?></p>
                        </div>
                    </div>
                    
                    <div class="dci-settings-section">
                        <h3><?php esc_html_e('Advanced Settings', 'demo-content-importer'); ?></h3>
                        
                        <div class="dci-settings-field">
                            <label for="dci-setting-batch-size"><?php esc_html_e('Batch Size', 'demo-content-importer'); ?></label>
                            <input type="number" id="dci-setting-batch-size" name="dci_settings[batch_size]" value="<?php echo esc_attr(isset($settings['batch_size']) ? $settings['batch_size'] : 10); ?>" min="1" max="100">
                            <p class="description"><?php esc_html_e('Number of items to process in each batch. Lower values are more reliable but slower.', 'demo-content-importer'); ?></p>
                        </div>
                        
                        <div class="dci-settings-field">
                            <label for="dci-setting-debug-mode"><?php esc_html_e('Debug Mode', 'demo-content-importer'); ?></label>
                            <select id="dci-setting-debug-mode" name="dci_settings[debug_mode]">
                                <option value="0" <?php selected(isset($settings['debug_mode']) ? $settings['debug_mode'] : '0', '0'); ?>><?php esc_html_e('Disabled', 'demo-content-importer'); ?></option>
                                <option value="1" <?php selected(isset($settings['debug_mode']) ? $settings['debug_mode'] : '0', '1'); ?>><?php esc_html_e('Enabled', 'demo-content-importer'); ?></option>
                            </select>
                            <p class="description"><?php esc_html_e('Enable debug mode for additional logging and error reporting.', 'demo-content-importer'); ?></p>
                        </div>
                    </div>
                    
                    <div class="dci-settings-actions">
                        <button id="dci-save-settings" class="button button-primary" type="submit"><?php esc_html_e('Save Settings', 'demo-content-importer'); ?></button>
                    </div>
                </form>
            </div>
            
            <!-- Logs Tab -->
            <div id="logs" class="dci-admin-tab-content">
                <h2><?php esc_html_e('Logs', 'demo-content-importer'); ?></h2>
                
                <div class="dci-logs-actions">
                    <button id="dci-refresh-logs" class="button"><?php esc_html_e('Refresh Logs', 'demo-content-importer'); ?></button>
                    <button id="dci-clear-logs" class="button"><?php esc_html_e('Clear Logs', 'demo-content-importer'); ?></button>
                </div>
                
                <div class="dci-logs-container">
                    <?php if (isset($logs) && !empty($logs)) : ?>
                        <div class="dci-logs">
                            <?php foreach ($logs as $log) : ?>
                                <div class="dci-log-entry <?php echo esc_attr($log['type']); ?>">
                                    <span class="dci-log-time">[<?php echo esc_html(date('Y-m-d H:i:s', $log['time'])); ?>]</span>
                                    <?php echo esc_html($log['message']); ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else : ?>
                        <div class="dci-notice dci-notice-info">
                            <p><?php esc_html_e('No logs found.', 'demo-content-importer'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>