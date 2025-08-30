<div class="wrap dci-admin-page">
    <h1><?php esc_html_e('Demo Content Importer', 'demo-content-importer'); ?></h1>
    
    <div class="dci-admin-notices">
        <div class="dci-notice dci-notice-warning">
            <p><strong><?php esc_html_e('Warning:', 'demo-content-importer'); ?></strong> <?php esc_html_e('Importing demo content will overwrite your current content. It is recommended to backup your database before proceeding.', 'demo-content-importer'); ?></p>
        </div>
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
                    <?php foreach ($this->demo_config as $demo_id => $demo) : ?>
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
                </div>
                
                <!-- Import Modal -->
                <div id="dci-import-modal" class="dci-modal">
                    <div class="dci-modal-content">
                        <span class="dci-modal-close">&times;</span>
                        <h2><?php esc_html_e('Import Demo Content', 'demo-content-importer'); ?></h2>
                        
                        <div class="dci-modal-body">
                            <div class="dci-import-options">
                                <h3><?php esc_html_e('Import Options', 'demo-content-importer'); ?></h3>
                                <p><?php esc_html_e('Select which content you want to import:', 'demo-content-importer'); ?></p>
                                
                                <div class="dci-import-options-list">
                                    <!-- Options will be loaded dynamically -->
                                </div>
                            </div>
                            
                            <div class="dci-required-plugins">
                                <h3><?php esc_html_e('Required Plugins', 'demo-content-importer'); ?></h3>
                                <p><?php esc_html_e('The following plugins are required for this demo:', 'demo-content-importer'); ?></p>
                                
                                <div class="dci-required-plugins-list">
                                    <!-- Plugins will be loaded dynamically -->
                                </div>
                            </div>
                            
                            <div class="dci-import-progress" style="display: none;">
                                <h3><?php esc_html_e('Import Progress', 'demo-content-importer'); ?></h3>
                                <div class="dci-progress-bar">
                                    <div class="dci-progress-bar-inner"></div>
                                </div>
                                <div class="dci-progress-status">
                                    <span class="dci-progress-percentage">0%</span>
                                    <span class="dci-progress-step"><?php esc_html_e('Preparing...', 'demo-content-importer'); ?></span>
                                </div>
                                <div class="dci-progress-log"></div>
                            </div>
                        </div>
                        
                        <div class="dci-modal-footer">
                            <button class="button dci-modal-cancel"><?php esc_html_e('Cancel', 'demo-content-importer'); ?></button>
                            <button class="button button-primary dci-modal-import"><?php esc_html_e('Import', 'demo-content-importer'); ?></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Backup & Restore Tab -->
            <div id="backup" class="dci-admin-tab-content">
                <h2><?php esc_html_e('Backup & Restore', 'demo-content-importer'); ?></h2>
                
                <div class="dci-backup-section">
                    <h3><?php esc_html_e('Create Backup', 'demo-content-importer'); ?></h3>
                    <p><?php esc_html_e('Create a backup of your current database. This will allow you to restore your site if something goes wrong.', 'demo-content-importer'); ?></p>
                    <button class="button button-primary dci-create-backup"><?php esc_html_e('Create Backup', 'demo-content-importer'); ?></button>
                    
                    <div class="dci-backup-progress" style="display: none;">
                        <div class="dci-progress-bar">
                            <div class="dci-progress-bar-inner"></div>
                        </div>
                        <div class="dci-progress-status">
                            <span class="dci-progress-percentage">0%</span>
                            <span class="dci-progress-step"><?php esc_html_e('Preparing...', 'demo-content-importer'); ?></span>
                        </div>
                    </div>
                </div>
                
                <div class="dci-restore-section">
                    <h3><?php esc_html_e('Restore Backup', 'demo-content-importer'); ?></h3>
                    <p><?php esc_html_e('Restore your site from a previous backup.', 'demo-content-importer'); ?></p>
                    
                    <div class="dci-backup-files">
                        <table class="widefat dci-backup-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Backup File', 'demo-content-importer'); ?></th>
                                    <th><?php esc_html_e('Date', 'demo-content-importer'); ?></th>
                                    <th><?php esc_html_e('Size', 'demo-content-importer'); ?></th>
                                    <th><?php esc_html_e('Actions', 'demo-content-importer'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Backup files will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="dci-restore-progress" style="display: none;">
                        <div class="dci-progress-bar">
                            <div class="dci-progress-bar-inner"></div>
                        </div>
                        <div class="dci-progress-status">
                            <span class="dci-progress-percentage">0%</span>
                            <span class="dci-progress-step"><?php esc_html_e('Preparing...', 'demo-content-importer'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Reset Tab -->
            <div id="reset" class="dci-admin-tab-content">
                <h2><?php esc_html_e('Reset Site', 'demo-content-importer'); ?></h2>
                
                <div class="dci-reset-section">
                    <div class="dci-notice dci-notice-danger">
                        <p><strong><?php esc_html_e('Warning:', 'demo-content-importer'); ?></strong> <?php esc_html_e('Resetting your site will delete all your content, including posts, pages, custom post types, categories, tags, custom taxonomies, and media uploads. This action cannot be undone.', 'demo-content-importer'); ?></p>
                    </div>
                    
                    <p><?php esc_html_e('Reset your site to a clean state. This is useful if you want to start fresh or if you want to import a demo without any conflicts.', 'demo-content-importer'); ?></p>
                    
                    <div class="dci-reset-options">
                        <h3><?php esc_html_e('Reset Options', 'demo-content-importer'); ?></h3>
                        <p><?php esc_html_e('Select what you want to reset:', 'demo-content-importer'); ?></p>
                        
                        <div class="dci-reset-options-list">
                            <label>
                                <input type="checkbox" name="reset_content" value="1" checked>
                                <?php esc_html_e('Content (posts, pages, custom post types)', 'demo-content-importer'); ?>
                            </label>
                            
                            <label>
                                <input type="checkbox" name="reset_uploads" value="1" checked>
                                <?php esc_html_e('Media uploads', 'demo-content-importer'); ?>
                            </label>
                            
                            <label>
                                <input type="checkbox" name="reset_options" value="1" checked>
                                <?php esc_html_e('Theme options', 'demo-content-importer'); ?>
                            </label>
                            
                            <label>
                                <input type="checkbox" name="reset_widgets" value="1" checked>
                                <?php esc_html_e('Widgets', 'demo-content-importer'); ?>
                            </label>
                            
                            <label>
                                <input type="checkbox" name="reset_menus" value="1" checked>
                                <?php esc_html_e('Menus', 'demo-content-importer'); ?>
                            </label>
                            
                            <label>
                                <input type="checkbox" name="reset_customizer" value="1" checked>
                                <?php esc_html_e('Customizer settings', 'demo-content-importer'); ?>
                            </label>
                        </div>
                    </div>
                    
                    <div class="dci-reset-confirmation">
                        <label>
                            <input type="checkbox" name="reset_confirmation" value="1">
                            <?php esc_html_e('I understand that this action will delete all my content and cannot be undone.', 'demo-content-importer'); ?>
                        </label>
                    </div>
                    
                    <button class="button button-primary dci-reset-site" disabled><?php esc_html_e('Reset Site', 'demo-content-importer'); ?></button>
                    
                    <div class="dci-reset-progress" style="display: none;">
                        <div class="dci-progress-bar">
                            <div class="dci-progress-bar-inner"></div>
                        </div>
                        <div class="dci-progress-status">
                            <span class="dci-progress-percentage">0%</span>
                            <span class="dci-progress-step"><?php esc_html_e('Preparing...', 'demo-content-importer'); ?></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Settings Tab -->
            <div id="settings" class="dci-admin-tab-content">
                <h2><?php esc_html_e('Settings', 'demo-content-importer'); ?></h2>
                
                <form id="dci-settings-form" class="dci-settings-form">
                    <table class="form-table">
                        <tbody>
                            <tr>
                                <th scope="row">
                                    <label for="dci-setting-backup-location"><?php esc_html_e('Backup Location', 'demo-content-importer'); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="dci-setting-backup-location" name="backup_location" value="<?php echo esc_attr(DCI_BACKUP_DIR); ?>" class="regular-text">
                                    <p class="description"><?php esc_html_e('Directory where backups are stored.', 'demo-content-importer'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="dci-setting-backup-retention"><?php esc_html_e('Backup Retention', 'demo-content-importer'); ?></label>
                                </th>
                                <td>
                                    <input type="number" id="dci-setting-backup-retention" name="backup_retention" value="10" min="1" max="100" class="small-text">
                                    <p class="description"><?php esc_html_e('Number of backups to keep before deleting old ones.', 'demo-content-importer'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="dci-setting-import-images"><?php esc_html_e('Import Images', 'demo-content-importer'); ?></label>
                                </th>
                                <td>
                                    <select id="dci-setting-import-images" name="import_images">
                                        <option value="all"><?php esc_html_e('All Images', 'demo-content-importer'); ?></option>
                                        <option value="featured"><?php esc_html_e('Featured Images Only', 'demo-content-importer'); ?></option>
                                        <option value="none"><?php esc_html_e('No Images', 'demo-content-importer'); ?></option>
                                    </select>
                                    <p class="description"><?php esc_html_e('Choose which images to import.', 'demo-content-importer'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th scope="row">
                                    <label for="dci-setting-debug-mode"><?php esc_html_e('Debug Mode', 'demo-content-importer'); ?></label>
                                </th>
                                <td>
                                    <label>
                                        <input type="checkbox" id="dci-setting-debug-mode" name="debug_mode" value="1">
                                        <?php esc_html_e('Enable debug mode', 'demo-content-importer'); ?>
                                    </label>
                                    <p class="description"><?php esc_html_e('Enable detailed logging for troubleshooting.', 'demo-content-importer'); ?></p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <p class="submit">
                        <button type="submit" class="button button-primary"><?php esc_html_e('Save Settings', 'demo-content-importer'); ?></button>
                    </p>
                </form>
            </div>
            
            <!-- Logs Tab -->
            <div id="logs" class="dci-admin-tab-content">
                <h2><?php esc_html_e('Logs', 'demo-content-importer'); ?></h2>
                
                <div class="dci-logs-section">
                    <div class="dci-logs-filters">
                        <select id="dci-log-filter-type">
                            <option value=""><?php esc_html_e('All Types', 'demo-content-importer'); ?></option>
                            <option value="info"><?php esc_html_e('Info', 'demo-content-importer'); ?></option>
                            <option value="warning"><?php esc_html_e('Warning', 'demo-content-importer'); ?></option>
                            <option value="error"><?php esc_html_e('Error', 'demo-content-importer'); ?></option>
                            <option value="success"><?php esc_html_e('Success', 'demo-content-importer'); ?></option>
                        </select>
                        
                        <select id="dci-log-filter-session">
                            <option value=""><?php esc_html_e('All Sessions', 'demo-content-importer'); ?></option>
                            <!-- Sessions will be loaded dynamically -->
                        </select>
                        
                        <button class="button dci-refresh-logs"><?php esc_html_e('Refresh', 'demo-content-importer'); ?></button>
                        <button class="button dci-clear-logs"><?php esc_html_e('Clear Logs', 'demo-content-importer'); ?></button>
                    </div>
                    
                    <div class="dci-logs-viewer">
                        <table class="widefat dci-logs-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Time', 'demo-content-importer'); ?></th>
                                    <th><?php esc_html_e('Type', 'demo-content-importer'); ?></th>
                                    <th><?php esc_html_e('Message', 'demo-content-importer'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Logs will be loaded dynamically -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>