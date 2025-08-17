<?php
/**
 * Analytics Sales Report Template
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

// Get filter values
$product_ids = isset($_GET['product_ids']) ? sanitize_text_field($_GET['product_ids']) : '';
$category_ids = isset($_GET['category_ids']) ? sanitize_text_field($_GET['category_ids']) : '';
$compare_toggle = isset($_GET['compare_toggle']) ? true : false;

?>
<div class="wrap aqualuxe-analytics-report">
    <h1><?php esc_html_e('Sales Report', 'aqualuxe'); ?></h1>
    
    <div class="aqualuxe-analytics-header">
        <div class="aqualuxe-analytics-date-range">
            <form method="get" action="">
                <input type="hidden" name="page" value="aqualuxe-analytics-sales">
                <input type="hidden" name="product_ids" id="product_ids" value="<?php echo esc_attr($product_ids); ?>">
                <input type="hidden" name="category_ids" id="category_ids" value="<?php echo esc_attr($category_ids); ?>">
                
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
                        <input type="checkbox" id="compare_toggle" name="compare_toggle" <?php checked($compare_toggle); ?>>
                        <label for="compare_toggle"><?php esc_html_e('Compare to', 'aqualuxe'); ?></label>
                    </div>
                    
                    <div class="aqualuxe-analytics-compare-dates" <?php echo $compare_toggle ? '' : 'style="display: none;"'; ?>>
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
            <button type="button" class="button aqualuxe-export-report" data-type="pdf">
                <span class="dashicons dashicons-pdf"></span>
                <?php esc_html_e('Export PDF', 'aqualuxe'); ?>
            </button>
            
            <button type="button" class="button aqualuxe-export-report" data-type="csv">
                <span class="dashicons dashicons-media-spreadsheet"></span>
                <?php esc_html_e('Export CSV', 'aqualuxe'); ?>
            </button>
            
            <button type="button" class="button aqualuxe-export-report" data-type="email">
                <span class="dashicons dashicons-email"></span>
                <?php esc_html_e('Email Report', 'aqualuxe'); ?>
            </button>
            
            <button type="button" class="button aqualuxe-filter-report">
                <span class="dashicons dashicons-filter"></span>
                <?php esc_html_e('Filter', 'aqualuxe'); ?>
            </button>
        </div>
    </div>
    
    <div class="aqualuxe-analytics-filters" style="display: none;">
        <div class="aqualuxe-analytics-filter-container">
            <div class="aqualuxe-analytics-filter-group">
                <h3><?php esc_html_e('Product Filters', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-analytics-filter-field">
                    <label for="product_filter"><?php esc_html_e('Products', 'aqualuxe'); ?></label>
                    <select id="product_filter" name="product_filter" multiple>
                        <!-- Products will be loaded via AJAX -->
                    </select>
                    <p class="description"><?php esc_html_e('Select products to filter by.', 'aqualuxe'); ?></p>
                </div>
                
                <div class="aqualuxe-analytics-filter-field">
                    <label for="category_filter"><?php esc_html_e('Categories', 'aqualuxe'); ?></label>
                    <select id="category_filter" name="category_filter" multiple>
                        <!-- Categories will be loaded via AJAX -->
                    </select>
                    <p class="description"><?php esc_html_e('Select categories to filter by.', 'aqualuxe'); ?></p>
                </div>
            </div>
            
            <div class="aqualuxe-analytics-filter-group">
                <h3><?php esc_html_e('Order Filters', 'aqualuxe'); ?></h3>
                
                <div class="aqualuxe-analytics-filter-field">
                    <label for="status_filter"><?php esc_html_e('Order Status', 'aqualuxe'); ?></label>
                    <select id="status_filter" name="status_filter" class="status-filter">
                        <option value="all"><?php esc_html_e('All Statuses', 'aqualuxe'); ?></option>
                        <option value="completed"><?php esc_html_e('Completed', 'aqualuxe'); ?></option>
                        <option value="processing"><?php esc_html_e('Processing', 'aqualuxe'); ?></option>
                        <option value="on-hold"><?php esc_html_e('On Hold', 'aqualuxe'); ?></option>
                        <option value="pending"><?php esc_html_e('Pending', 'aqualuxe'); ?></option>
                        <option value="cancelled"><?php esc_html_e('Cancelled', 'aqualuxe'); ?></option>
                        <option value="refunded"><?php esc_html_e('Refunded', 'aqualuxe'); ?></option>
                        <option value="failed"><?php esc_html_e('Failed', 'aqualuxe'); ?></option>
                    </select>
                    <p class="description"><?php esc_html_e('Filter by order status.', 'aqualuxe'); ?></p>
                </div>
                
                <div class="aqualuxe-analytics-filter-field">
                    <label for="payment_filter"><?php esc_html_e('Payment Method', 'aqualuxe'); ?></label>
                    <select id="payment_filter" name="payment_filter">
                        <option value="all"><?php esc_html_e('All Payment Methods', 'aqualuxe'); ?></option>
                        <!-- Payment methods will be loaded via AJAX -->
                    </select>
                    <p class="description"><?php esc_html_e('Filter by payment method.', 'aqualuxe'); ?></p>
                </div>
            </div>
            
            <div class="aqualuxe-analytics-filter-actions">
                <button type="button" class="button reset-filters"><?php esc_html_e('Reset Filters', 'aqualuxe'); ?></button>
                <button type="button" class="button button-primary apply-filters"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
            </div>
        </div>
    </div>
    
    <div class="aqualuxe-analytics-content">
        <div class="aqualuxe-analytics-loading">
            <div class="spinner is-active"></div>
            <p><?php esc_html_e('Loading sales data...', 'aqualuxe'); ?></p>
        </div>
        
        <div class="aqualuxe-analytics-report-content" style="display: none;">
            <!-- Sales Summary Section -->
            <div class="aqualuxe-analytics-summary-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Sales Summary', 'aqualuxe'); ?></h2>
                </div>
                
                <div class="aqualuxe-analytics-summary-grid">
                    <!-- Summary cards will be added here via JavaScript -->
                </div>
            </div>
            
            <!-- Sales Trend Section -->
            <div class="aqualuxe-analytics-trend-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Sales Trend', 'aqualuxe'); ?></h2>
                    <div class="aqualuxe-analytics-chart-controls">
                        <select id="chart-type">
                            <option value="line"><?php esc_html_e('Line Chart', 'aqualuxe'); ?></option>
                            <option value="bar"><?php esc_html_e('Bar Chart', 'aqualuxe'); ?></option>
                            <option value="area"><?php esc_html_e('Area Chart', 'aqualuxe'); ?></option>
                        </select>
                        
                        <select id="chart-grouping">
                            <option value="day"><?php esc_html_e('Daily', 'aqualuxe'); ?></option>
                            <option value="week"><?php esc_html_e('Weekly', 'aqualuxe'); ?></option>
                            <option value="month"><?php esc_html_e('Monthly', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="aqualuxe-analytics-chart-container">
                    <canvas id="sales-trend-chart" height="300"></canvas>
                </div>
            </div>
            
            <!-- Product Performance Section -->
            <div class="aqualuxe-analytics-products-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Product Performance', 'aqualuxe'); ?></h2>
                    <div class="aqualuxe-analytics-section-actions">
                        <select id="product-sort">
                            <option value="revenue"><?php esc_html_e('Sort by Revenue', 'aqualuxe'); ?></option>
                            <option value="quantity"><?php esc_html_e('Sort by Quantity', 'aqualuxe'); ?></option>
                            <option value="average"><?php esc_html_e('Sort by Average Price', 'aqualuxe'); ?></option>
                        </select>
                        
                        <select id="product-limit">
                            <option value="10"><?php esc_html_e('Top 10', 'aqualuxe'); ?></option>
                            <option value="20"><?php esc_html_e('Top 20', 'aqualuxe'); ?></option>
                            <option value="50"><?php esc_html_e('Top 50', 'aqualuxe'); ?></option>
                            <option value="100"><?php esc_html_e('Top 100', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="aqualuxe-analytics-grid-2">
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Top Products', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-product-table-container">
                            <table class="aqualuxe-analytics-product-table">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Product', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Revenue', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Quantity', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Avg. Price', 'aqualuxe'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Product rows will be added here via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Product Distribution', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-product-chart-container">
                            <canvas id="product-distribution-chart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Category Performance Section -->
            <div class="aqualuxe-analytics-categories-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Category Performance', 'aqualuxe'); ?></h2>
                </div>
                
                <div class="aqualuxe-analytics-grid-2">
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Top Categories', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-category-table-container">
                            <table class="aqualuxe-analytics-category-table">
                                <thead>
                                    <tr>
                                        <th><?php esc_html_e('Category', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Revenue', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('Products Sold', 'aqualuxe'); ?></th>
                                        <th><?php esc_html_e('% of Total', 'aqualuxe'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Category rows will be added here via JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Category Distribution', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-category-chart-container">
                            <canvas id="category-distribution-chart" height="300"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Order Analysis Section -->
            <div class="aqualuxe-analytics-orders-section">
                <div class="aqualuxe-analytics-section-header">
                    <h2><?php esc_html_e('Order Analysis', 'aqualuxe'); ?></h2>
                </div>
                
                <div class="aqualuxe-analytics-grid-2">
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Order Status Distribution', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-order-status-chart-container">
                            <canvas id="order-status-chart" height="300"></canvas>
                        </div>
                    </div>
                    
                    <div class="aqualuxe-analytics-card">
                        <h3><?php esc_html_e('Order Value Distribution', 'aqualuxe'); ?></h3>
                        <div class="aqualuxe-analytics-order-value-chart-container">
                            <canvas id="order-value-chart" height="300"></canvas>
                        </div>
                    </div>
                </div>
                
                <div class="aqualuxe-analytics-card">
                    <h3><?php esc_html_e('Recent Orders', 'aqualuxe'); ?></h3>
                    <div class="aqualuxe-analytics-recent-orders-container">
                        <table class="aqualuxe-analytics-recent-orders-table">
                            <thead>
                                <tr>
                                    <th><?php esc_html_e('Order', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Date', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Customer', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Status', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Items', 'aqualuxe'); ?></th>
                                    <th><?php esc_html_e('Total', 'aqualuxe'); ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Order rows will be added here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
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
                        <input type="text" id="email_subject" name="email_subject" value="<?php esc_attr_e('Sales Report', 'aqualuxe'); ?>">
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

<script>
jQuery(document).ready(function($) {
    // Initialize the sales report
    AquaLuxeAnalyticsReports.initSalesReport({
        startDate: '<?php echo esc_js($start_date); ?>',
        endDate: '<?php echo esc_js($end_date); ?>',
        compareStartDate: '<?php echo esc_js($compare_start_date); ?>',
        compareEndDate: '<?php echo esc_js($compare_end_date); ?>',
        compareEnabled: <?php echo $compare_toggle ? 'true' : 'false'; ?>,
        productIds: '<?php echo esc_js($product_ids); ?>',
        categoryIds: '<?php echo esc_js($category_ids); ?>'
    });
    
    // Toggle filters
    $('.aqualuxe-filter-report').on('click', function() {
        $('.aqualuxe-analytics-filters').slideToggle();
    });
    
    // Email report modal
    $('.aqualuxe-export-report[data-type="email"]').on('click', function() {
        $('#aqualuxe-email-report-modal').show();
    });
    
    // Close modal
    $('.aqualuxe-modal-close, .aqualuxe-modal-cancel').on('click', function() {
        $(this).closest('.aqualuxe-modal').hide();
    });
    
    // Send email report
    $('.aqualuxe-modal-send').on('click', function() {
        // Get form data
        const recipients = $('#email_recipients').val();
        const subject = $('#email_subject').val();
        const message = $('#email_message').val();
        const format = $('#email_format').val();
        
        // Validate recipients
        if (!recipients) {
            alert('<?php esc_html_e('Please enter at least one recipient email address.', 'aqualuxe'); ?>');
            return;
        }
        
        // Send report
        AquaLuxeAnalyticsReports.sendEmailReport('sales', {
            recipients: recipients,
            subject: subject,
            message: message,
            format: format,
            startDate: '<?php echo esc_js($start_date); ?>',
            endDate: '<?php echo esc_js($end_date); ?>',
            compareStartDate: '<?php echo esc_js($compare_start_date); ?>',
            compareEndDate: '<?php echo esc_js($compare_end_date); ?>',
            compareEnabled: <?php echo $compare_toggle ? 'true' : 'false'; ?>,
            productIds: '<?php echo esc_js($product_ids); ?>',
            categoryIds: '<?php echo esc_js($category_ids); ?>'
        });
        
        // Hide modal
        $('#aqualuxe-email-report-modal').hide();
    });
});
</script>