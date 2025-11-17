<?php
/**
 * AquaLuxe API Logs Template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap">
    <h1><?php _e('AquaLuxe API Logs', 'aqualuxe'); ?></h1>
    
    <div class="aqualuxe-api-logs">
        <div class="aqualuxe-api-card">
            <h2><?php _e('API Request Logs', 'aqualuxe'); ?></h2>
            
            <div style="margin-bottom: 15px;">
                <button id="aqualuxe-api-clear-logs" class="aqualuxe-api-button danger"><?php _e('Clear All Logs', 'aqualuxe'); ?></button>
                <span class="aqualuxe-api-loading" style="display: none;"></span>
                
                <div id="aqualuxe-api-clear-logs-result" style="margin-top: 15px;"></div>
            </div>
            
            <?php if (!empty($logs['logs'])) : ?>
                <table class="aqualuxe-api-logs-table">
                    <thead>
                        <tr>
                            <th class="column-timestamp"><?php _e('Time', 'aqualuxe'); ?></th>
                            <th class="column-endpoint"><?php _e('Endpoint', 'aqualuxe'); ?></th>
                            <th class="column-method"><?php _e('Method', 'aqualuxe'); ?></th>
                            <th class="column-status"><?php _e('Status', 'aqualuxe'); ?></th>
                            <th><?php _e('User', 'aqualuxe'); ?></th>
                            <th class="column-ip"><?php _e('IP Address', 'aqualuxe'); ?></th>
                            <th class="column-time"><?php _e('Execution Time', 'aqualuxe'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($logs['logs'] as $log) : ?>
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
                
                <?php if ($logs['pages'] > 1) : ?>
                    <div class="aqualuxe-api-logs-pagination">
                        <?php
                        $page_links = array();
                        $total_pages = $logs['pages'];
                        $current_page = $logs['page'];
                        
                        // First page
                        if ($current_page > 1) {
                            $page_links[] = '<a href="' . add_query_arg('page_num', 1) . '" class="page-numbers">&laquo;</a>';
                        }
                        
                        // Previous page
                        if ($current_page > 1) {
                            $page_links[] = '<a href="' . add_query_arg('page_num', $current_page - 1) . '" class="page-numbers">&lsaquo;</a>';
                        }
                        
                        // Page numbers
                        $start_page = max(1, $current_page - 2);
                        $end_page = min($total_pages, $current_page + 2);
                        
                        for ($i = $start_page; $i <= $end_page; $i++) {
                            if ($i == $current_page) {
                                $page_links[] = '<span class="page-numbers current">' . $i . '</span>';
                            } else {
                                $page_links[] = '<a href="' . add_query_arg('page_num', $i) . '" class="page-numbers">' . $i . '</a>';
                            }
                        }
                        
                        // Next page
                        if ($current_page < $total_pages) {
                            $page_links[] = '<a href="' . add_query_arg('page_num', $current_page + 1) . '" class="page-numbers">&rsaquo;</a>';
                        }
                        
                        // Last page
                        if ($current_page < $total_pages) {
                            $page_links[] = '<a href="' . add_query_arg('page_num', $total_pages) . '" class="page-numbers">&raquo;</a>';
                        }
                        
                        echo implode(' ', $page_links);
                        ?>
                    </div>
                <?php endif; ?>
            <?php else : ?>
                <p><?php _e('No API requests have been logged yet.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-api-card">
            <h2><?php _e('Log Settings', 'aqualuxe'); ?></h2>
            
            <form method="post" action="options.php">
                <?php settings_fields('aqualuxe_api_logs'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_api_log_retention"><?php _e('Log Retention', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <select name="aqualuxe_api_log_retention" id="aqualuxe_api_log_retention">
                                <option value="7" <?php selected(get_option('aqualuxe_api_log_retention', 30), 7); ?>><?php _e('7 days', 'aqualuxe'); ?></option>
                                <option value="14" <?php selected(get_option('aqualuxe_api_log_retention', 30), 14); ?>><?php _e('14 days', 'aqualuxe'); ?></option>
                                <option value="30" <?php selected(get_option('aqualuxe_api_log_retention', 30), 30); ?>><?php _e('30 days', 'aqualuxe'); ?></option>
                                <option value="60" <?php selected(get_option('aqualuxe_api_log_retention', 30), 60); ?>><?php _e('60 days', 'aqualuxe'); ?></option>
                                <option value="90" <?php selected(get_option('aqualuxe_api_log_retention', 30), 90); ?>><?php _e('90 days', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php _e('How long to keep API request logs before automatically deleting them.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="aqualuxe_api_log_level"><?php _e('Log Level', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <select name="aqualuxe_api_log_level" id="aqualuxe_api_log_level">
                                <option value="all" <?php selected(get_option('aqualuxe_api_log_level', 'all'), 'all'); ?>><?php _e('All Requests', 'aqualuxe'); ?></option>
                                <option value="errors" <?php selected(get_option('aqualuxe_api_log_level', 'all'), 'errors'); ?>><?php _e('Errors Only', 'aqualuxe'); ?></option>
                            </select>
                            <p class="description"><?php _e('Which API requests to log.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(__('Save Settings', 'aqualuxe')); ?>
            </form>
        </div>
    </div>
</div>