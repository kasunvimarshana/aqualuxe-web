<?php
/**
 * Analytics Dashboard Template
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get current user
$current_user = wp_get_current_user();

// Get date range
$start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : date('Y-m-d', strtotime('-30 days'));
$end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : date('Y-m-d');
$compare_start_date = isset($_GET['compare_start_date']) ? sanitize_text_field($_GET['compare_start_date']) : date('Y-m-d', strtotime('-60 days'));
$compare_end_date = isset($_GET['compare_end_date']) ? sanitize_text_field($_GET['compare_end_date']) : date('Y-m-d', strtotime('-31 days'));

// Get dashboard layout
$dashboard_layout = get_user_meta($current_user->ID, 'aqualuxe_analytics_dashboard_layout', true);
if (!$dashboard_layout) {
    $dashboard_layout = 'default';
}

// Get dashboard settings
$settings = get_option('aqualuxe_analytics_settings', array());
$default_date_range = isset($settings['default_date_range']) ? $settings['default_date_range'] : '30';
$chart_colors = isset($settings['chart_colors']) ? $settings['chart_colors'] : array(
    'primary' => '#2271b1',
    'secondary' => '#72aee6',
    'tertiary' => '#c3c4c7',
    'quaternary' => '#f0f0f1',
    'positive' => '#00a32a',
    'negative' => '#d63638',
);

?>
<div class="wrap aqualuxe-analytics-dashboard">
    <h1><?php esc_html_e('Analytics Dashboard', 'aqualuxe'); ?></h1>
    
    <div class="aqualuxe-analytics-header">
        <div class="aqualuxe-analytics-date-range">
            <form method="get" action="">
                <input type="hidden" name="page" value="aqualuxe-analytics">
                
                <div class="aqualuxe-analytics-date-inputs">
                    <div class="aqualuxe-analytics-date-field">
                        <label for="start_date"><?php esc_html_e('Start Date', 'aqualuxe'); ?></label>
                        <input type="date" id="start_date" name="start_date" value="<?php echo esc_attr($start_date); ?>">
                    </div>
                    
                    <div class="aqualuxe-analytics-date-field">
                        <label for="end_date"><?php esc_html_e('End Date', 'aqualuxe'); ?></label>
                        <input type="date" id="end_date" name="end_date" value="<?php echo esc_attr($end_date); ?>">
                    </div>
                    
                    <div class="aqualuxe-analytics-compare-toggle">
                        <input type="checkbox" id="compare_toggle" name="compare_toggle" <?php checked(isset($_GET['compare_toggle'])); ?>>
                        <label for="compare_toggle"><?php esc_html_e('Compare to', 'aqualuxe'); ?></label>
                    </div>
                    
                    <div class="aqualuxe-analytics-compare-dates" <?php echo isset($_GET['compare_toggle']) ? '' : 'style="display: none;"'; ?>>
                        <div class="aqualuxe-analytics-date-field">
                            <label for="compare_start_date"><?php esc_html_e('Compare Start', 'aqualuxe'); ?></label>
                            <input type="date" id="compare_start_date" name="compare_start_date" value="<?php echo esc_attr($compare_start_date); ?>">
                        </div>
                        
                        <div class="aqualuxe-analytics-date-field">
                            <label for="compare_end_date"><?php esc_html_e('Compare End', 'aqualuxe'); ?></label>
                            <input type="date" id="compare_end_date" name="compare_end_date" value="<?php echo esc_attr($compare_end_date); ?>">
                        </div>
                    </div>
                    
                    <div class="aqualuxe-analytics-date-presets">
                        <button type="button" class="button date-preset" data-days="7"><?php esc_html_e('Last 7 days', 'aqualuxe'); ?></button>
                        <button type="button" class="button date-preset" data-days="30"><?php esc_html_e('Last 30 days', 'aqualuxe'); ?></button>
                        <button type="button" class="button date-preset" data-days="90"><?php esc_html_e('Last 90 days', 'aqualuxe'); ?></button>
                        <button type="button" class="button date-preset" data-days="365"><?php esc_html_e('Last year', 'aqualuxe'); ?></button>
                    </div>
                    
                    <div class="aqualuxe-analytics-date-submit">
                        <button type="submit" class="button button-primary"><?php esc_html_e('Apply', 'aqualuxe'); ?></button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="aqualuxe-analytics-actions">
            <button type="button" class="button aqualuxe-export-dashboard" data-type="pdf">
                <span class="dashicons dashicons-pdf"></span>
                <?php esc_html_e('Export PDF', 'aqualuxe'); ?>
            </button>
            
            <button type="button" class="button aqualuxe-export-dashboard" data-type="csv">
                <span class="dashicons dashicons-media-spreadsheet"></span>
                <?php esc_html_e('Export CSV', 'aqualuxe'); ?>
            </button>
            
            <button type="button" class="button aqualuxe-export-dashboard" data-type="email">
                <span class="dashicons dashicons-email"></span>
                <?php esc_html_e('Email Report', 'aqualuxe'); ?>
            </button>
            
            <button type="button" class="button aqualuxe-customize-dashboard">
                <span class="dashicons dashicons-admin-customizer"></span>
                <?php esc_html_e('Customize', 'aqualuxe'); ?>
            </button>
        </div>
    </div>
    
    <div class="aqualuxe-analytics-content">
        <div class="aqualuxe-analytics-loading">
            <div class="spinner is-active"></div>
            <p><?php esc_html_e('Loading analytics data...', 'aqualuxe'); ?></p>
        </div>
        
        <div class="aqualuxe-analytics-dashboard-content" style="display: none;">
            <!-- KPI Section -->
            <div class="aqualuxe-analytics-kpi-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Key Performance Indicators', 'aqualuxe'); ?></h2>
                </div>
                
                <div class="aqualuxe-analytics-kpi-grid">
                    <!-- KPI cards will be added here via JavaScript -->
                </div>
            </div>
            
            <!-- Sales Overview Section -->
            <div class="aqualuxe-analytics-sales-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Sales Overview', 'aqualuxe'); ?></h2>
                    <div class="aqualuxe-analytics-section-actions">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-analytics-sales')); ?>" class="button">
                            <?php esc_html_e('View Detailed Report', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
                
                <div class="aqualuxe-analytics-chart-container">
                    <canvas id="sales-chart" height="300"></canvas>
                </div>
                
                <div class="aqualuxe-analytics-grid-2">
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Top Products', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-top-products">
                            <!-- Top products will be added here via JavaScript -->
                        </div>
                    </div>
                    
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Top Categories', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-top-categories">
                            <!-- Top categories will be added here via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Inventory Overview Section -->
            <div class="aqualuxe-analytics-inventory-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Inventory Overview', 'aqualuxe'); ?></h2>
                    <div class="aqualuxe-analytics-section-actions">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-analytics-inventory')); ?>" class="button">
                            <?php esc_html_e('View Detailed Report', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
                
                <div class="aqualuxe-analytics-grid-2">
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Stock Status', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-stock-status">
                            <!-- Stock status will be added here via JavaScript -->
                        </div>
                    </div>
                    
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Low Stock Products', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-low-stock">
                            <!-- Low stock products will be added here via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Customer Overview Section -->
            <div class="aqualuxe-analytics-customer-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Customer Overview', 'aqualuxe'); ?></h2>
                    <div class="aqualuxe-analytics-section-actions">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-analytics-customers')); ?>" class="button">
                            <?php esc_html_e('View Detailed Report', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
                
                <div class="aqualuxe-analytics-grid-2">
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('New Customers', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-new-customers-chart">
                            <canvas id="new-customers-chart" height="200"></canvas>
                        </div>
                    </div>
                    
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Top Customers', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-top-customers">
                            <!-- Top customers will be added here via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Subscription Overview Section -->
            <div class="aqualuxe-analytics-subscription-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Subscription Overview', 'aqualuxe'); ?></h2>
                    <div class="aqualuxe-analytics-section-actions">
                        <a href="<?php echo esc_url(admin_url('admin.php?page=aqualuxe-analytics-subscriptions')); ?>" class="button">
                            <?php esc_html_e('View Detailed Report', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
                
                <div class="aqualuxe-analytics-grid-2">
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Subscription Growth', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-subscription-chart">
                            <canvas id="subscription-chart" height="200"></canvas>
                        </div>
                    </div>
                    
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Subscription Metrics', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-subscription-metrics">
                            <!-- Subscription metrics will be added here via JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity Section -->
            <div class="aqualuxe-analytics-activity-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Recent Activity', 'aqualuxe'); ?></h2>
                </div>
                
                <div class="aqualuxe-analytics-card">
                    <div class="aqualuxe-analytics-activity-list">
                        <!-- Recent activity will be added here via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Dashboard Customization Modal -->
    <div id="aqualuxe-customize-dashboard-modal" class="aqualuxe-modal" style="display: none;">
        <div class="aqualuxe-modal-content">
            <div class="aqualuxe-modal-header">
                <h2><?php esc_html_e('Customize Dashboard', 'aqualuxe'); ?></h2>
                <button type="button" class="aqualuxe-modal-close">&times;</button>
            </div>
            
            <div class="aqualuxe-modal-body">
                <form id="aqualuxe-dashboard-customize-form">
                    <h3><?php esc_html_e('Dashboard Layout', 'aqualuxe'); ?></h3>
                    <div class="aqualuxe-layout-options">
                        <label>
                            <input type="radio" name="dashboard_layout" value="default" <?php checked($dashboard_layout, 'default'); ?>>
                            <?php esc_html_e('Default', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="radio" name="dashboard_layout" value="compact" <?php checked($dashboard_layout, 'compact'); ?>>
                            <?php esc_html_e('Compact', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="radio" name="dashboard_layout" value="expanded" <?php checked($dashboard_layout, 'expanded'); ?>>
                            <?php esc_html_e('Expanded', 'aqualuxe'); ?>
                        </label>
                    </div>
                    
                    <h3><?php esc_html_e('Visible Sections', 'aqualuxe'); ?></h3>
                    <div class="aqualuxe-section-options">
                        <label>
                            <input type="checkbox" name="visible_sections[]" value="kpi" checked>
                            <?php esc_html_e('Key Performance Indicators', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" name="visible_sections[]" value="sales" checked>
                            <?php esc_html_e('Sales Overview', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" name="visible_sections[]" value="inventory" checked>
                            <?php esc_html_e('Inventory Overview', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" name="visible_sections[]" value="customers" checked>
                            <?php esc_html_e('Customer Overview', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" name="visible_sections[]" value="subscriptions" checked>
                            <?php esc_html_e('Subscription Overview', 'aqualuxe'); ?>
                        </label>
                        
                        <label>
                            <input type="checkbox" name="visible_sections[]" value="activity" checked>
                            <?php esc_html_e('Recent Activity', 'aqualuxe'); ?>
                        </label>
                    </div>
                    
                    <h3><?php esc_html_e('Section Order', 'aqualuxe'); ?></h3>
                    <p><?php esc_html_e('Drag and drop to reorder sections', 'aqualuxe'); ?></p>
                    <ul id="aqualuxe-section-order" class="aqualuxe-sortable-list">
                        <li data-section="kpi"><?php esc_html_e('Key Performance Indicators', 'aqualuxe'); ?></li>
                        <li data-section="sales"><?php esc_html_e('Sales Overview', 'aqualuxe'); ?></li>
                        <li data-section="inventory"><?php esc_html_e('Inventory Overview', 'aqualuxe'); ?></li>
                        <li data-section="customers"><?php esc_html_e('Customer Overview', 'aqualuxe'); ?></li>
                        <li data-section="subscriptions"><?php esc_html_e('Subscription Overview', 'aqualuxe'); ?></li>
                        <li data-section="activity"><?php esc_html_e('Recent Activity', 'aqualuxe'); ?></li>
                    </ul>
                </form>
            </div>
            
            <div class="aqualuxe-modal-footer">
                <button type="button" class="button aqualuxe-modal-cancel"><?php esc_html_e('Cancel', 'aqualuxe'); ?></button>
                <button type="button" class="button button-primary aqualuxe-modal-save"><?php esc_html_e('Save Changes', 'aqualuxe'); ?></button>
            </div>
        </div>
    </div>
    
    <!-- Email Report Modal -->
    <div id="aqualuxe-email-report-modal" class="aqualuxe-modal" style="display: none;">
        <div class="aqualuxe-modal-content">
            <div class="aqualuxe-modal-header">
                <h2><?php esc_html_e('Email Report', 'aqualuxe'); ?></h2>
                <button type="button" class="aqualuxe-modal-close">&times;</button>
            </div>
            
            <div class="aqualuxe-modal-body">
                <form id="aqualuxe-email-report-form">
                    <div class="aqualuxe-form-field">
                        <label for="email_recipients"><?php esc_html_e('Recipients', 'aqualuxe'); ?></label>
                        <input type="text" id="email_recipients" name="email_recipients" placeholder="<?php esc_attr_e('Enter email addresses separated by commas', 'aqualuxe'); ?>">
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="email_subject"><?php esc_html_e('Subject', 'aqualuxe'); ?></label>
                        <input type="text" id="email_subject" name="email_subject" value="<?php esc_attr_e('Analytics Report', 'aqualuxe'); ?>">
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="email_message"><?php esc_html_e('Message', 'aqualuxe'); ?></label>
                        <textarea id="email_message" name="email_message" rows="5" placeholder="<?php esc_attr_e('Enter a message to include with the report', 'aqualuxe'); ?>"></textarea>
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="email_format"><?php esc_html_e('Format', 'aqualuxe'); ?></label>
                        <select id="email_format" name="email_format">
                            <option value="pdf"><?php esc_html_e('PDF', 'aqualuxe'); ?></option>
                            <option value="csv"><?php esc_html_e('CSV', 'aqualuxe'); ?></option>
                            <option value="html"><?php esc_html_e('HTML', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </form>
            </div>
            
            <div class="aqualuxe-modal-footer">
                <button type="button" class="button aqualuxe-modal-cancel"><?php esc_html_e('Cancel', 'aqualuxe'); ?></button>
                <button type="button" class="button button-primary aqualuxe-modal-send"><?php esc_html_e('Send Report', 'aqualuxe'); ?></button>
            </div>
        </div>
    </div>
</div>