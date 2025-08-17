<?php
/**
 * AquaLuxe API Dashboard Template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('AquaLuxe API Dashboard', 'aqualuxe'); ?></h1>
    
    <div class="aqualuxe-api-dashboard">
        <?php if ($api_enabled) : ?>
            <div class="aqualuxe-api-notice success">
                <p><?php _e('The AquaLuxe API is currently enabled and operational.', 'aqualuxe'); ?></p>
            </div>
        <?php else : ?>
            <div class="aqualuxe-api-notice error">
                <p><?php _e('The AquaLuxe API is currently disabled. Enable it in the API Settings.', 'aqualuxe'); ?></p>
            </div>
        <?php endif; ?>
        
        <div class="aqualuxe-api-stats">
            <div class="aqualuxe-api-stat-card">
                <h3><?php _e('Total API Requests', 'aqualuxe'); ?></h3>
                <div class="stat-value"><?php echo number_format($total_requests); ?></div>
            </div>
            
            <div class="aqualuxe-api-stat-card">
                <h3><?php _e('Active API Users', 'aqualuxe'); ?></h3>
                <div class="stat-value"><?php echo number_format($active_users); ?></div>
            </div>
            
            <div class="aqualuxe-api-stat-card">
                <h3><?php _e('Registered Devices', 'aqualuxe'); ?></h3>
                <div class="stat-value"><?php echo number_format($device_count); ?></div>
            </div>
        </div>
        
        <div class="aqualuxe-api-card">
            <h2><?php _e('API Connection Test', 'aqualuxe'); ?></h2>
            <p><?php _e('Test the API connection to ensure it is working properly.', 'aqualuxe'); ?></p>
            
            <button id="aqualuxe-api-test-connection" class="aqualuxe-api-button"><?php _e('Test Connection', 'aqualuxe'); ?></button>
            <span class="aqualuxe-api-loading" style="display: none;"></span>
            
            <div id="aqualuxe-api-test-connection-result" style="margin-top: 15px;"></div>
        </div>
        
        <div class="aqualuxe-api-card">
            <h2><?php _e('Recent API Requests', 'aqualuxe'); ?></h2>
            
            <?php if (!empty($recent_logs)) : ?>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th><?php _e('Time', 'aqualuxe'); ?></th>
                            <th><?php _e('Endpoint', 'aqualuxe'); ?></th>
                            <th><?php _e('Method', 'aqualuxe'); ?></th>
                            <th><?php _e('Status', 'aqualuxe'); ?></th>
                            <th><?php _e('User', 'aqualuxe'); ?></th>
                            <th><?php _e('IP Address', 'aqualuxe'); ?></th>
                            <th><?php _e('Execution Time', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recent_logs as $log) : ?>
                            <tr>
                                <td><?php echo date_i18n(get_option('date_format') . ' ' . get_option('time_format'), strtotime($log->timestamp)); ?></td>
                                <td><?php echo esc_html($log->endpoint); ?></td>
                                <td><?php echo esc_html($log->method); ?></td>
                                <td>
                                    <span class="status-<?php echo esc_attr($log->response_code); ?>">
                                        <?php echo esc_html($log->response_code); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php
                                    if ($log->user_id) {
                                        $user = get_user_by('id', $log->user_id);
                                        echo $user ? esc_html($user->display_name) : __('Unknown', 'aqualuxe');
                                    } else {
                                        echo __('Guest', 'aqualuxe');
                                    }
                                    ?>
                                </td>
                                <td><?php echo esc_html($log->ip_address); ?></td>
                                <td><?php echo number_format($log->execution_time * 1000, 2); ?> ms</td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <p style="margin-top: 15px;">
                    <a href="<?php echo admin_url('admin.php?page=aqualuxe-api-logs'); ?>" class="aqualuxe-api-button secondary"><?php _e('View All Logs', 'aqualuxe'); ?></a>
                </p>
            <?php else : ?>
                <p><?php _e('No API requests have been logged yet.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-api-card">
            <h2><?php _e('Quick Links', 'aqualuxe'); ?></h2>
            
            <div style="display: flex; flex-wrap: wrap;">
                <a href="<?php echo admin_url('admin.php?page=aqualuxe-api-settings'); ?>" class="aqualuxe-api-button" style="margin-right: 10px; margin-bottom: 10px;">
                    <?php _e('API Settings', 'aqualuxe'); ?>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=aqualuxe-api-notifications'); ?>" class="aqualuxe-api-button" style="margin-right: 10px; margin-bottom: 10px;">
                    <?php _e('Push Notifications', 'aqualuxe'); ?>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=aqualuxe-api-sync'); ?>" class="aqualuxe-api-button" style="margin-right: 10px; margin-bottom: 10px;">
                    <?php _e('Sync Management', 'aqualuxe'); ?>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=aqualuxe-api-logs'); ?>" class="aqualuxe-api-button" style="margin-right: 10px; margin-bottom: 10px;">
                    <?php _e('API Logs', 'aqualuxe'); ?>
                </a>
                
                <a href="<?php echo admin_url('admin.php?page=aqualuxe-api-docs'); ?>" class="aqualuxe-api-button" style="margin-right: 10px; margin-bottom: 10px;">
                    <?php _e('Documentation', 'aqualuxe'); ?>
                </a>
            </div>
        </div>
    </div>
</div>