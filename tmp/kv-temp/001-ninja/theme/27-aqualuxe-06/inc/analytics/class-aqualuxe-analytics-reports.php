<?php
/**
 * AquaLuxe Analytics Reports
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Analytics_Reports Class
 *
 * Handles report generation for analytics
 */
class AquaLuxe_Analytics_Reports {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Analytics data instance.
     *
     * @var AquaLuxe_Analytics_Data
     */
    private $analytics_data;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        $this->analytics_data = AquaLuxe_Analytics_Data::get_instance();
    }

    /**
     * Get the instance of this class.
     *
     * @return AquaLuxe_Analytics_Reports A single instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get sales data for reports.
     *
     * @param array $params Request parameters.
     * @return array Sales report data.
     */
    public function get_sales_data($params) {
        // Set default parameters
        $defaults = array(
            'start_date' => date('Y-m-d', strtotime('-30 days')),
            'end_date' => date('Y-m-d'),
            'compare_start_date' => date('Y-m-d', strtotime('-60 days')),
            'compare_end_date' => date('Y-m-d', strtotime('-31 days')),
            'product_ids' => array(),
            'category_ids' => array(),
            'period' => 'day',
        );
        
        $params = wp_parse_args($params, $defaults);
        
        // Validate dates
        $start_date = $this->validate_date($params['start_date']);
        $end_date = $this->validate_date($params['end_date']);
        $compare_start_date = $this->validate_date($params['compare_start_date']);
        $compare_end_date = $this->validate_date($params['compare_end_date']);
        
        // Get period data
        $period_type = $this->get_period_type($start_date, $end_date);
        
        // Get sales data
        $sales_data = $this->get_sales_by_period($start_date, $end_date, $period_type, $params['product_ids'], $params['category_ids']);
        
        // Get comparison data
        $compare_data = $this->get_sales_by_period($compare_start_date, $compare_end_date, $period_type, $params['product_ids'], $params['category_ids']);
        
        // Get top products
        $top_products = $this->get_top_products($start_date, $end_date, 10);
        
        // Get top categories
        $top_categories = $this->get_top_categories($start_date, $end_date, 5);
        
        // Calculate summary metrics
        $summary = $this->calculate_sales_summary($sales_data, $compare_data);
        
        return array(
            'summary' => $summary,
            'chart_data' => $this->format_chart_data($sales_data, $compare_data, $period_type),
            'top_products' => $top_products,
            'top_categories' => $top_categories,
            'period_type' => $period_type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'compare_start_date' => $compare_start_date,
            'compare_end_date' => $compare_end_date,
        );
    }

    /**
     * Get inventory data for reports.
     *
     * @param array $params Request parameters.
     * @return array Inventory report data.
     */
    public function get_inventory_data($params) {
        // Set default parameters
        $defaults = array(
            'start_date' => date('Y-m-d', strtotime('-30 days')),
            'end_date' => date('Y-m-d'),
            'product_ids' => array(),
            'category_ids' => array(),
            'stock_status' => 'all',
        );
        
        $params = wp_parse_args($params, $defaults);
        
        // Validate dates
        $start_date = $this->validate_date($params['start_date']);
        $end_date = $this->validate_date($params['end_date']);
        
        // Get current stock levels
        $stock_levels = $this->get_current_stock_levels($params['product_ids'], $params['category_ids'], $params['stock_status']);
        
        // Get inventory movement
        $inventory_movement = $this->get_inventory_movement_by_period($start_date, $end_date, $params['product_ids'], $params['category_ids']);
        
        // Get low stock products
        $low_stock_products = $this->get_low_stock_products(10);
        
        // Get out of stock products
        $out_of_stock_products = $this->get_out_of_stock_products();
        
        // Get product turnover rates
        $turnover_rates = $this->get_product_turnover_rates($start_date, $end_date, $params['product_ids'], $params['category_ids']);
        
        return array(
            'stock_levels' => $stock_levels,
            'inventory_movement' => $inventory_movement,
            'low_stock_products' => $low_stock_products,
            'out_of_stock_products' => $out_of_stock_products,
            'turnover_rates' => $turnover_rates,
            'start_date' => $start_date,
            'end_date' => $end_date,
        );
    }

    /**
     * Get customers data for reports.
     *
     * @param array $params Request parameters.
     * @return array Customers report data.
     */
    public function get_customers_data($params) {
        // Set default parameters
        $defaults = array(
            'start_date' => date('Y-m-d', strtotime('-30 days')),
            'end_date' => date('Y-m-d'),
            'compare_start_date' => date('Y-m-d', strtotime('-60 days')),
            'compare_end_date' => date('Y-m-d', strtotime('-31 days')),
            'segment' => 'all',
            'status' => 'all',
        );
        
        $params = wp_parse_args($params, $defaults);
        
        // Validate dates
        $start_date = $this->validate_date($params['start_date']);
        $end_date = $this->validate_date($params['end_date']);
        $compare_start_date = $this->validate_date($params['compare_start_date']);
        $compare_end_date = $this->validate_date($params['compare_end_date']);
        
        // Get period data
        $period_type = $this->get_period_type($start_date, $end_date);
        
        // Get new customers data
        $new_customers = $this->get_new_customers_by_period($start_date, $end_date, $period_type);
        
        // Get comparison data
        $compare_new_customers = $this->get_new_customers_by_period($compare_start_date, $compare_end_date, $period_type);
        
        // Get customer purchase data
        $customer_purchases = $this->get_customer_purchases_by_period($start_date, $end_date, $period_type, $params['segment'], $params['status']);
        
        // Get top customers
        $top_customers = $this->get_top_customers($start_date, $end_date, 10);
        
        // Calculate customer lifetime value
        $customer_ltv = $this->calculate_customer_ltv($start_date, $end_date);
        
        // Calculate summary metrics
        $summary = $this->calculate_customer_summary($new_customers, $compare_new_customers, $customer_purchases);
        
        return array(
            'summary' => $summary,
            'chart_data' => $this->format_chart_data($new_customers, $compare_new_customers, $period_type),
            'top_customers' => $top_customers,
            'customer_ltv' => $customer_ltv,
            'period_type' => $period_type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'compare_start_date' => $compare_start_date,
            'compare_end_date' => $compare_end_date,
        );
    }

    /**
     * Get subscriptions data for reports.
     *
     * @param array $params Request parameters.
     * @return array Subscriptions report data.
     */
    public function get_subscriptions_data($params) {
        // Set default parameters
        $defaults = array(
            'start_date' => date('Y-m-d', strtotime('-30 days')),
            'end_date' => date('Y-m-d'),
            'compare_start_date' => date('Y-m-d', strtotime('-60 days')),
            'compare_end_date' => date('Y-m-d', strtotime('-31 days')),
            'status' => 'all',
            'product_ids' => array(),
        );
        
        $params = wp_parse_args($params, $defaults);
        
        // Validate dates
        $start_date = $this->validate_date($params['start_date']);
        $end_date = $this->validate_date($params['end_date']);
        $compare_start_date = $this->validate_date($params['compare_start_date']);
        $compare_end_date = $this->validate_date($params['compare_end_date']);
        
        // Get period data
        $period_type = $this->get_period_type($start_date, $end_date);
        
        // Get new subscriptions data
        $new_subscriptions = $this->get_new_subscriptions_by_period($start_date, $end_date, $period_type, $params['status'], $params['product_ids']);
        
        // Get comparison data
        $compare_new_subscriptions = $this->get_new_subscriptions_by_period($compare_start_date, $compare_end_date, $period_type, $params['status'], $params['product_ids']);
        
        // Get subscription renewal data
        $subscription_renewals = $this->get_subscription_renewals_by_period($start_date, $end_date, $period_type, $params['status'], $params['product_ids']);
        
        // Get subscription cancellation data
        $subscription_cancellations = $this->get_subscription_cancellations_by_period($start_date, $end_date, $period_type, $params['product_ids']);
        
        // Get top subscription products
        $top_subscription_products = $this->get_top_subscription_products($start_date, $end_date, 10);
        
        // Calculate churn rate
        $churn_rate = $this->calculate_churn_rate($start_date, $end_date);
        
        // Calculate summary metrics
        $summary = $this->calculate_subscription_summary($new_subscriptions, $compare_new_subscriptions, $subscription_renewals, $subscription_cancellations);
        
        return array(
            'summary' => $summary,
            'chart_data' => $this->format_subscription_chart_data($new_subscriptions, $subscription_renewals, $subscription_cancellations, $period_type),
            'top_subscription_products' => $top_subscription_products,
            'churn_rate' => $churn_rate,
            'period_type' => $period_type,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'compare_start_date' => $compare_start_date,
            'compare_end_date' => $compare_end_date,
        );
    }

    /**
     * Validate a date string.
     *
     * @param string $date Date string in Y-m-d format.
     * @return string Validated date string.
     */
    private function validate_date($date) {
        $timestamp = strtotime($date);
        
        if ($timestamp === false) {
            return date('Y-m-d');
        }
        
        return date('Y-m-d', $timestamp);
    }

    /**
     * Get the appropriate period type based on date range.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return string Period type (day, week, month).
     */
    private function get_period_type($start_date, $end_date) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $diff = $start->diff($end);
        
        $days = $diff->days;
        
        if ($days <= 31) {
            return 'day';
        } elseif ($days <= 90) {
            return 'week';
        } else {
            return 'month';
        }
    }

    /**
     * Get sales data by period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period_type Period type (day, week, month).
     * @param array $product_ids Product IDs to filter by.
     * @param array $category_ids Category IDs to filter by.
     * @return array Sales data by period.
     */
    private function get_sales_by_period($start_date, $end_date, $period_type, $product_ids = array(), $category_ids = array()) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = "SELECT date_created, SUM(data_value) as total, SUM(data_count) as count
                 FROM $table_name
                 WHERE data_type = %s
                 AND data_key = %s
                 AND date_created BETWEEN %s AND %s";
        
        $params = array(
            'sales',
            'total',
            $start_datetime,
            $end_datetime,
        );
        
        // Add product filter
        if (!empty($product_ids)) {
            $query = "SELECT date_created, SUM(data_value) as total, SUM(data_count) as count
                     FROM $table_name
                     WHERE data_type = %s
                     AND data_key IN (";
            
            $params = array('sales');
            
            foreach ($product_ids as $index => $product_id) {
                $query .= $index > 0 ? ", %s" : "%s";
                $params[] = 'product_' . $product_id;
            }
            
            $query .= ")
                     AND date_created BETWEEN %s AND %s";
            
            $params[] = $start_datetime;
            $params[] = $end_datetime;
        }
        
        // Add category filter
        if (empty($product_ids) && !empty($category_ids)) {
            // Get products in categories
            $products_in_categories = $this->get_products_in_categories($category_ids);
            
            if (!empty($products_in_categories)) {
                $query = "SELECT date_created, SUM(data_value) as total, SUM(data_count) as count
                         FROM $table_name
                         WHERE data_type = %s
                         AND data_key IN (";
                
                $params = array('sales');
                
                foreach ($products_in_categories as $index => $product_id) {
                    $query .= $index > 0 ? ", %s" : "%s";
                    $params[] = 'product_' . $product_id;
                }
                
                $query .= ")
                         AND date_created BETWEEN %s AND %s";
                
                $params[] = $start_datetime;
                $params[] = $end_datetime;
            }
        }
        
        // Group by period
        switch ($period_type) {
            case 'day':
                $query .= " GROUP BY DATE(date_created)
                           ORDER BY date_created ASC";
                break;
                
            case 'week':
                $query .= " GROUP BY YEARWEEK(date_created, 1)
                           ORDER BY date_created ASC";
                break;
                
            case 'month':
                $query .= " GROUP BY YEAR(date_created), MONTH(date_created)
                           ORDER BY date_created ASC";
                break;
        }
        
        $prepared_query = $wpdb->prepare($query, $params);
        $results = $wpdb->get_results($prepared_query, ARRAY_A);
        
        $sales_data = array();
        
        foreach ($results as $row) {
            $date = new DateTime($row['date_created']);
            
            switch ($period_type) {
                case 'day':
                    $period_key = $date->format('Y-m-d');
                    $period_label = $date->format(get_option('date_format'));
                    break;
                    
                case 'week':
                    $week_number = $date->format('W');
                    $year = $date->format('Y');
                    $period_key = $year . '-W' . $week_number;
                    $period_label = sprintf(__('Week %s, %s', 'aqualuxe'), $week_number, $year);
                    break;
                    
                case 'month':
                    $period_key = $date->format('Y-m');
                    $period_label = $date->format('F Y');
                    break;
            }
            
            $sales_data[$period_key] = array(
                'label' => $period_label,
                'total' => (float) $row['total'],
                'count' => (int) $row['count'],
            );
        }
        
        // Fill in missing periods
        $sales_data = $this->fill_missing_periods($sales_data, $start_date, $end_date, $period_type);
        
        return $sales_data;
    }

    /**
     * Get top selling products.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param int $limit Number of products to return.
     * @return array Top selling products.
     */
    private function get_top_products($start_date, $end_date, $limit = 10) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = $wpdb->prepare(
            "SELECT data_key, SUM(data_value) as total, SUM(data_count) as count
            FROM $table_name
            WHERE data_type = %s
            AND data_key LIKE %s
            AND date_created BETWEEN %s AND %s
            GROUP BY data_key
            ORDER BY total DESC
            LIMIT %d",
            'sales',
            'product_%',
            $start_datetime,
            $end_datetime,
            $limit
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $top_products = array();
        
        foreach ($results as $row) {
            $product_id = str_replace('product_', '', $row['data_key']);
            $product = wc_get_product($product_id);
            
            if (!$product) {
                continue;
            }
            
            $top_products[] = array(
                'id' => $product_id,
                'name' => $product->get_name(),
                'total' => (float) $row['total'],
                'count' => (int) $row['count'],
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                'url' => get_permalink($product_id),
            );
        }
        
        return $top_products;
    }

    /**
     * Get top selling categories.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param int $limit Number of categories to return.
     * @return array Top selling categories.
     */
    private function get_top_categories($start_date, $end_date, $limit = 5) {
        // Get all product sales
        $product_sales = $this->get_product_sales($start_date, $end_date);
        
        $category_sales = array();
        
        // Group product sales by category
        foreach ($product_sales as $product_id => $data) {
            $product_categories = get_the_terms($product_id, 'product_cat');
            
            if (!$product_categories || is_wp_error($product_categories)) {
                continue;
            }
            
            foreach ($product_categories as $category) {
                if (!isset($category_sales[$category->term_id])) {
                    $category_sales[$category->term_id] = array(
                        'id' => $category->term_id,
                        'name' => $category->name,
                        'total' => 0,
                        'count' => 0,
                        'url' => get_term_link($category),
                    );
                }
                
                $category_sales[$category->term_id]['total'] += $data['total'];
                $category_sales[$category->term_id]['count'] += $data['count'];
            }
        }
        
        // Sort categories by total sales
        usort($category_sales, function($a, $b) {
            return $b['total'] - $a['total'];
        });
        
        // Limit the number of categories
        return array_slice($category_sales, 0, $limit);
    }

    /**
     * Get product sales for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Product sales data.
     */
    private function get_product_sales($start_date, $end_date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = $wpdb->prepare(
            "SELECT data_key, SUM(data_value) as total, SUM(data_count) as count
            FROM $table_name
            WHERE data_type = %s
            AND data_key LIKE %s
            AND date_created BETWEEN %s AND %s
            GROUP BY data_key",
            'sales',
            'product_%',
            $start_datetime,
            $end_datetime
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $product_sales = array();
        
        foreach ($results as $row) {
            $product_id = str_replace('product_', '', $row['data_key']);
            
            $product_sales[$product_id] = array(
                'total' => (float) $row['total'],
                'count' => (int) $row['count'],
            );
        }
        
        return $product_sales;
    }

    /**
     * Get products in specific categories.
     *
     * @param array $category_ids Category IDs.
     * @return array Product IDs.
     */
    private function get_products_in_categories($category_ids) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids',
            'tax_query' => array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category_ids,
                ),
            ),
        );
        
        return get_posts($args);
    }

    /**
     * Fill in missing periods in data.
     *
     * @param array $data Data with periods as keys.
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period_type Period type (day, week, month).
     * @return array Data with all periods filled in.
     */
    private function fill_missing_periods($data, $start_date, $end_date, $period_type) {
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $interval = new DateInterval('P1D'); // 1 day interval
        
        if ($period_type === 'week') {
            $interval = new DateInterval('P7D'); // 7 day interval
        } elseif ($period_type === 'month') {
            $interval = new DateInterval('P1M'); // 1 month interval
        }
        
        $period = new DatePeriod($start, $interval, $end);
        
        $filled_data = array();
        
        foreach ($period as $date) {
            switch ($period_type) {
                case 'day':
                    $period_key = $date->format('Y-m-d');
                    $period_label = $date->format(get_option('date_format'));
                    break;
                    
                case 'week':
                    $week_number = $date->format('W');
                    $year = $date->format('Y');
                    $period_key = $year . '-W' . $week_number;
                    $period_label = sprintf(__('Week %s, %s', 'aqualuxe'), $week_number, $year);
                    break;
                    
                case 'month':
                    $period_key = $date->format('Y-m');
                    $period_label = $date->format('F Y');
                    break;
            }
            
            if (isset($data[$period_key])) {
                $filled_data[$period_key] = $data[$period_key];
            } else {
                $filled_data[$period_key] = array(
                    'label' => $period_label,
                    'total' => 0,
                    'count' => 0,
                );
            }
        }
        
        return $filled_data;
    }

    /**
     * Calculate sales summary metrics.
     *
     * @param array $sales_data Sales data.
     * @param array $compare_data Comparison sales data.
     * @return array Summary metrics.
     */
    private function calculate_sales_summary($sales_data, $compare_data) {
        $total_sales = 0;
        $total_orders = 0;
        $compare_total_sales = 0;
        $compare_total_orders = 0;
        
        foreach ($sales_data as $data) {
            $total_sales += $data['total'];
            $total_orders += $data['count'];
        }
        
        foreach ($compare_data as $data) {
            $compare_total_sales += $data['total'];
            $compare_total_orders += $data['count'];
        }
        
        $sales_change = 0;
        $orders_change = 0;
        
        if ($compare_total_sales > 0) {
            $sales_change = (($total_sales - $compare_total_sales) / $compare_total_sales) * 100;
        }
        
        if ($compare_total_orders > 0) {
            $orders_change = (($total_orders - $compare_total_orders) / $compare_total_orders) * 100;
        }
        
        $average_order_value = $total_orders > 0 ? $total_sales / $total_orders : 0;
        $compare_average_order_value = $compare_total_orders > 0 ? $compare_total_sales / $compare_total_orders : 0;
        
        $aov_change = 0;
        
        if ($compare_average_order_value > 0) {
            $aov_change = (($average_order_value - $compare_average_order_value) / $compare_average_order_value) * 100;
        }
        
        return array(
            'total_sales' => $total_sales,
            'total_orders' => $total_orders,
            'average_order_value' => $average_order_value,
            'sales_change' => $sales_change,
            'orders_change' => $orders_change,
            'aov_change' => $aov_change,
        );
    }

    /**
     * Format chart data for visualization.
     *
     * @param array $data Main data.
     * @param array $compare_data Comparison data.
     * @param string $period_type Period type (day, week, month).
     * @return array Formatted chart data.
     */
    private function format_chart_data($data, $compare_data, $period_type) {
        $labels = array();
        $values = array();
        $compare_values = array();
        
        foreach ($data as $period_key => $period_data) {
            $labels[] = $period_data['label'];
            $values[] = $period_data['total'];
        }
        
        foreach ($compare_data as $period_data) {
            $compare_values[] = $period_data['total'];
        }
        
        return array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => __('Current Period', 'aqualuxe'),
                    'data' => $values,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ),
                array(
                    'label' => __('Previous Period', 'aqualuxe'),
                    'data' => $compare_values,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ),
            ),
        );
    }

    /**
     * Get current stock levels.
     *
     * @param array $product_ids Product IDs to filter by.
     * @param array $category_ids Category IDs to filter by.
     * @param string $stock_status Stock status to filter by (all, instock, outofstock, lowstock).
     * @return array Stock level data.
     */
    private function get_current_stock_levels($product_ids = array(), $category_ids = array(), $stock_status = 'all') {
        // Get products to check
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids',
        );
        
        // Add product filter
        if (!empty($product_ids)) {
            $args['post__in'] = $product_ids;
        }
        
        // Add category filter
        if (!empty($category_ids)) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'term_id',
                    'terms' => $category_ids,
                ),
            );
        }
        
        // Add stock status filter
        if ($stock_status !== 'all') {
            $args['meta_query'] = array(
                array(
                    'key' => '_stock_status',
                    'value' => $stock_status,
                ),
            );
        }
        
        $product_ids = get_posts($args);
        
        $stock_levels = array();
        
        foreach ($product_ids as $product_id) {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                continue;
            }
            
            // Skip variable products, we'll get stock from variations
            if ($product->is_type('variable')) {
                $variations = $product->get_children();
                
                foreach ($variations as $variation_id) {
                    $variation = wc_get_product($variation_id);
                    
                    if (!$variation) {
                        continue;
                    }
                    
                    $stock_levels[] = array(
                        'id' => $variation_id,
                        'name' => $variation->get_formatted_name(),
                        'stock' => $variation->get_stock_quantity(),
                        'status' => $variation->get_stock_status(),
                        'image' => wp_get_attachment_image_url($variation->get_image_id(), 'thumbnail'),
                        'url' => $variation->get_permalink(),
                    );
                }
            } else {
                $stock_levels[] = array(
                    'id' => $product_id,
                    'name' => $product->get_name(),
                    'stock' => $product->get_stock_quantity(),
                    'status' => $product->get_stock_status(),
                    'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                    'url' => $product->get_permalink(),
                );
            }
        }
        
        return $stock_levels;
    }

    /**
     * Get inventory movement by period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param array $product_ids Product IDs to filter by.
     * @param array $category_ids Category IDs to filter by.
     * @return array Inventory movement data.
     */
    private function get_inventory_movement_by_period($start_date, $end_date, $product_ids = array(), $category_ids = array()) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = "SELECT data_key, date_created, data_count, data_meta
                 FROM $table_name
                 WHERE data_type = %s
                 AND data_key LIKE %s
                 AND date_created BETWEEN %s AND %s";
        
        $params = array(
            'inventory',
            'product_%',
            $start_datetime,
            $end_datetime,
        );
        
        // Add product filter
        if (!empty($product_ids)) {
            $query = "SELECT data_key, date_created, data_count, data_meta
                     FROM $table_name
                     WHERE data_type = %s
                     AND data_key IN (";
            
            $params = array('inventory');
            
            foreach ($product_ids as $index => $product_id) {
                $query .= $index > 0 ? ", %s" : "%s";
                $params[] = 'product_' . $product_id;
            }
            
            $query .= ")
                     AND date_created BETWEEN %s AND %s";
            
            $params[] = $start_datetime;
            $params[] = $end_datetime;
        }
        
        // Add category filter
        if (empty($product_ids) && !empty($category_ids)) {
            // Get products in categories
            $products_in_categories = $this->get_products_in_categories($category_ids);
            
            if (!empty($products_in_categories)) {
                $query = "SELECT data_key, date_created, data_count, data_meta
                         FROM $table_name
                         WHERE data_type = %s
                         AND data_key IN (";
                
                $params = array('inventory');
                
                foreach ($products_in_categories as $index => $product_id) {
                    $query .= $index > 0 ? ", %s" : "%s";
                    $params[] = 'product_' . $product_id;
                }
                
                $query .= ")
                         AND date_created BETWEEN %s AND %s";
                
                $params[] = $start_datetime;
                $params[] = $end_datetime;
            }
        }
        
        $query .= " ORDER BY date_created ASC";
        
        $prepared_query = $wpdb->prepare($query, $params);
        $results = $wpdb->get_results($prepared_query, ARRAY_A);
        
        $inventory_movement = array();
        
        foreach ($results as $row) {
            $product_id = str_replace('product_', '', $row['data_key']);
            $meta = maybe_unserialize($row['data_meta']);
            $action = isset($meta['action']) ? $meta['action'] : 'unknown';
            
            $date = date('Y-m-d', strtotime($row['date_created']));
            
            if (!isset($inventory_movement[$date])) {
                $inventory_movement[$date] = array(
                    'date' => $date,
                    'label' => date(get_option('date_format'), strtotime($date)),
                    'in' => 0,
                    'out' => 0,
                );
            }
            
            if ($action === 'sale') {
                $inventory_movement[$date]['out'] += (int) $row['data_count'];
            } else {
                $inventory_movement[$date]['in'] += (int) $row['data_count'];
            }
        }
        
        // Sort by date
        ksort($inventory_movement);
        
        return array_values($inventory_movement);
    }

    /**
     * Get low stock products.
     *
     * @param int $limit Number of products to return.
     * @return array Low stock products.
     */
    private function get_low_stock_products($limit = 10) {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $limit,
            'meta_query' => array(
                array(
                    'key' => '_stock_status',
                    'value' => 'instock',
                ),
                array(
                    'key' => '_stock',
                    'value' => array(1, get_option('woocommerce_notify_low_stock_amount', 2)),
                    'type' => 'NUMERIC',
                    'compare' => 'BETWEEN',
                ),
            ),
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_key' => '_stock',
        );
        
        $products = get_posts($args);
        
        $low_stock_products = array();
        
        foreach ($products as $product_post) {
            $product = wc_get_product($product_post->ID);
            
            if (!$product) {
                continue;
            }
            
            $low_stock_products[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'stock' => $product->get_stock_quantity(),
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                'url' => $product->get_permalink(),
            );
        }
        
        return $low_stock_products;
    }

    /**
     * Get out of stock products.
     *
     * @return array Out of stock products.
     */
    private function get_out_of_stock_products() {
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_stock_status',
                    'value' => 'outofstock',
                ),
            ),
        );
        
        $products = get_posts($args);
        
        $out_of_stock_products = array();
        
        foreach ($products as $product_post) {
            $product = wc_get_product($product_post->ID);
            
            if (!$product) {
                continue;
            }
            
            $out_of_stock_products[] = array(
                'id' => $product->get_id(),
                'name' => $product->get_name(),
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                'url' => $product->get_permalink(),
            );
        }
        
        return $out_of_stock_products;
    }

    /**
     * Get product turnover rates.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param array $product_ids Product IDs to filter by.
     * @param array $category_ids Category IDs to filter by.
     * @return array Product turnover rates.
     */
    private function get_product_turnover_rates($start_date, $end_date, $product_ids = array(), $category_ids = array()) {
        // Get inventory movement
        $inventory_movement = $this->get_inventory_movement($start_date, $end_date, $product_ids, $category_ids);
        
        // Get current stock levels
        $stock_levels = $this->get_stock_levels($end_date);
        
        $turnover_rates = array();
        
        foreach ($inventory_movement as $product_id => $movement) {
            $current_stock = isset($stock_levels[$product_id]) ? $stock_levels[$product_id] : 0;
            
            // Skip products with no stock
            if ($current_stock <= 0) {
                continue;
            }
            
            $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
            $turnover_rate = ($movement['quantity'] / $current_stock) * (365 / $days);
            
            $product = wc_get_product($product_id);
            
            if (!$product) {
                continue;
            }
            
            $turnover_rates[] = array(
                'id' => $product_id,
                'name' => $product->get_name(),
                'turnover_rate' => $turnover_rate,
                'current_stock' => $current_stock,
                'sold' => $movement['quantity'],
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                'url' => $product->get_permalink(),
            );
        }
        
        // Sort by turnover rate
        usort($turnover_rates, function($a, $b) {
            return $b['turnover_rate'] - $a['turnover_rate'];
        });
        
        return $turnover_rates;
    }

    /**
     * Get new customers by period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period_type Period type (day, week, month).
     * @return array New customers by period.
     */
    private function get_new_customers_by_period($start_date, $end_date, $period_type) {
        $args = array(
            'role' => 'customer',
            'date_query' => array(
                array(
                    'after' => $start_date,
                    'before' => $end_date,
                    'inclusive' => true,
                ),
            ),
            'fields' => 'ID',
        );
        
        $customers = get_users($args);
        
        $new_customers_by_period = array();
        
        foreach ($customers as $customer_id) {
            $user_data = get_userdata($customer_id);
            $registration_date = $user_data->user_registered;
            
            $date = new DateTime($registration_date);
            
            switch ($period_type) {
                case 'day':
                    $period_key = $date->format('Y-m-d');
                    $period_label = $date->format(get_option('date_format'));
                    break;
                    
                case 'week':
                    $week_number = $date->format('W');
                    $year = $date->format('Y');
                    $period_key = $year . '-W' . $week_number;
                    $period_label = sprintf(__('Week %s, %s', 'aqualuxe'), $week_number, $year);
                    break;
                    
                case 'month':
                    $period_key = $date->format('Y-m');
                    $period_label = $date->format('F Y');
                    break;
            }
            
            if (!isset($new_customers_by_period[$period_key])) {
                $new_customers_by_period[$period_key] = array(
                    'label' => $period_label,
                    'total' => 0,
                    'count' => 0,
                );
            }
            
            $new_customers_by_period[$period_key]['count']++;
        }
        
        // Fill in missing periods
        $new_customers_by_period = $this->fill_missing_periods($new_customers_by_period, $start_date, $end_date, $period_type);
        
        return $new_customers_by_period;
    }

    /**
     * Get customer purchases by period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period_type Period type (day, week, month).
     * @param string $segment Customer segment (all, new, returning).
     * @param string $status Customer status (all, active, inactive).
     * @return array Customer purchases by period.
     */
    private function get_customer_purchases_by_period($start_date, $end_date, $period_type, $segment = 'all', $status = 'all') {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = "SELECT date_created, data_meta, SUM(data_value) as total, SUM(data_count) as count
                 FROM $table_name
                 WHERE data_type = %s
                 AND data_key LIKE %s
                 AND date_created BETWEEN %s AND %s";
        
        $params = array(
            'customers',
            'purchase_%',
            $start_datetime,
            $end_datetime,
        );
        
        // Add segment filter
        if ($segment === 'new') {
            // Get new customers in the period
            $new_customers = $this->get_new_customers($start_date, $end_date);
            
            if (!empty($new_customers)) {
                $query .= " AND (";
                
                foreach ($new_customers as $index => $customer_id) {
                    $query .= $index > 0 ? " OR " : "";
                    $query .= "data_key = %s";
                    $params[] = 'purchase_' . $customer_id;
                }
                
                $query .= ")";
            }
        } elseif ($segment === 'returning') {
            // Get new customers in the period
            $new_customers = $this->get_new_customers($start_date, $end_date);
            
            if (!empty($new_customers)) {
                $query .= " AND (";
                
                foreach ($new_customers as $index => $customer_id) {
                    $query .= $index > 0 ? " AND " : "";
                    $query .= "data_key != %s";
                    $params[] = 'purchase_' . $customer_id;
                }
                
                $query .= ")";
            }
        }
        
        // Add status filter
        if ($status === 'active') {
            // Get active customers (purchased in the last 30 days)
            $active_customers = $this->get_active_customers();
            
            if (!empty($active_customers)) {
                $query .= " AND (";
                
                foreach ($active_customers as $index => $customer_id) {
                    $query .= $index > 0 ? " OR " : "";
                    $query .= "data_key = %s";
                    $params[] = 'purchase_' . $customer_id;
                }
                
                $query .= ")";
            }
        } elseif ($status === 'inactive') {
            // Get inactive customers (not purchased in the last 30 days)
            $active_customers = $this->get_active_customers();
            
            if (!empty($active_customers)) {
                $query .= " AND (";
                
                foreach ($active_customers as $index => $customer_id) {
                    $query .= $index > 0 ? " AND " : "";
                    $query .= "data_key != %s";
                    $params[] = 'purchase_' . $customer_id;
                }
                
                $query .= ")";
            }
        }
        
        // Group by period
        switch ($period_type) {
            case 'day':
                $query .= " GROUP BY DATE(date_created)
                           ORDER BY date_created ASC";
                break;
                
            case 'week':
                $query .= " GROUP BY YEARWEEK(date_created, 1)
                           ORDER BY date_created ASC";
                break;
                
            case 'month':
                $query .= " GROUP BY YEAR(date_created), MONTH(date_created)
                           ORDER BY date_created ASC";
                break;
        }
        
        $prepared_query = $wpdb->prepare($query, $params);
        $results = $wpdb->get_results($prepared_query, ARRAY_A);
        
        $customer_purchases = array();
        
        foreach ($results as $row) {
            $date = new DateTime($row['date_created']);
            
            switch ($period_type) {
                case 'day':
                    $period_key = $date->format('Y-m-d');
                    $period_label = $date->format(get_option('date_format'));
                    break;
                    
                case 'week':
                    $week_number = $date->format('W');
                    $year = $date->format('Y');
                    $period_key = $year . '-W' . $week_number;
                    $period_label = sprintf(__('Week %s, %s', 'aqualuxe'), $week_number, $year);
                    break;
                    
                case 'month':
                    $period_key = $date->format('Y-m');
                    $period_label = $date->format('F Y');
                    break;
            }
            
            if (!isset($customer_purchases[$period_key])) {
                $customer_purchases[$period_key] = array(
                    'label' => $period_label,
                    'total' => 0,
                    'count' => 0,
                );
            }
            
            $customer_purchases[$period_key]['total'] += (float) $row['total'];
            $customer_purchases[$period_key]['count'] += (int) $row['count'];
        }
        
        // Fill in missing periods
        $customer_purchases = $this->fill_missing_periods($customer_purchases, $start_date, $end_date, $period_type);
        
        return $customer_purchases;
    }

    /**
     * Get active customers (purchased in the last 30 days).
     *
     * @return array Active customer IDs.
     */
    private function get_active_customers() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $thirty_days_ago = date('Y-m-d H:i:s', strtotime('-30 days'));
        
        $query = $wpdb->prepare(
            "SELECT DISTINCT REPLACE(data_key, 'purchase_', '') as customer_id
            FROM $table_name
            WHERE data_type = %s
            AND data_key LIKE %s
            AND date_created >= %s",
            'customers',
            'purchase_%',
            $thirty_days_ago
        );
        
        $results = $wpdb->get_col($query);
        
        return $results;
    }

    /**
     * Get top customers by purchase amount.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param int $limit Number of customers to return.
     * @return array Top customers.
     */
    private function get_top_customers($start_date, $end_date, $limit = 10) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = $wpdb->prepare(
            "SELECT data_key, SUM(data_value) as total, SUM(data_count) as count
            FROM $table_name
            WHERE data_type = %s
            AND data_key LIKE %s
            AND date_created BETWEEN %s AND %s
            GROUP BY data_key
            ORDER BY total DESC
            LIMIT %d",
            'customers',
            'purchase_%',
            $start_datetime,
            $end_datetime,
            $limit
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $top_customers = array();
        
        foreach ($results as $row) {
            $customer_id = str_replace('purchase_', '', $row['data_key']);
            $customer = get_user_by('id', $customer_id);
            
            if (!$customer) {
                continue;
            }
            
            $top_customers[] = array(
                'id' => $customer_id,
                'name' => $customer->display_name,
                'email' => $customer->user_email,
                'total' => (float) $row['total'],
                'count' => (int) $row['count'],
                'url' => admin_url('user-edit.php?user_id=' . $customer_id),
            );
        }
        
        return $top_customers;
    }

    /**
     * Calculate customer lifetime value.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Customer lifetime value data.
     */
    private function calculate_customer_ltv($start_date, $end_date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        // Get all customer purchases
        $query = $wpdb->prepare(
            "SELECT data_key, SUM(data_value) as total, SUM(data_count) as count
            FROM $table_name
            WHERE data_type = %s
            AND data_key LIKE %s
            AND date_created BETWEEN %s AND %s
            GROUP BY data_key",
            'customers',
            'purchase_%',
            $start_datetime,
            $end_datetime
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $total_customers = count($results);
        $total_revenue = 0;
        $total_orders = 0;
        
        foreach ($results as $row) {
            $total_revenue += (float) $row['total'];
            $total_orders += (int) $row['count'];
        }
        
        $average_ltv = $total_customers > 0 ? $total_revenue / $total_customers : 0;
        $average_order_value = $total_orders > 0 ? $total_revenue / $total_orders : 0;
        $average_orders_per_customer = $total_customers > 0 ? $total_orders / $total_customers : 0;
        
        return array(
            'average_ltv' => $average_ltv,
            'average_order_value' => $average_order_value,
            'average_orders_per_customer' => $average_orders_per_customer,
            'total_customers' => $total_customers,
            'total_revenue' => $total_revenue,
            'total_orders' => $total_orders,
        );
    }

    /**
     * Calculate customer summary metrics.
     *
     * @param array $new_customers New customers data.
     * @param array $compare_new_customers Comparison new customers data.
     * @param array $customer_purchases Customer purchases data.
     * @return array Summary metrics.
     */
    private function calculate_customer_summary($new_customers, $compare_new_customers, $customer_purchases) {
        $total_new_customers = 0;
        $total_purchases = 0;
        $total_revenue = 0;
        $compare_total_new_customers = 0;
        
        foreach ($new_customers as $data) {
            $total_new_customers += $data['count'];
        }
        
        foreach ($compare_new_customers as $data) {
            $compare_total_new_customers += $data['count'];
        }
        
        foreach ($customer_purchases as $data) {
            $total_purchases += $data['count'];
            $total_revenue += $data['total'];
        }
        
        $new_customers_change = 0;
        
        if ($compare_total_new_customers > 0) {
            $new_customers_change = (($total_new_customers - $compare_total_new_customers) / $compare_total_new_customers) * 100;
        }
        
        $average_revenue_per_customer = $total_new_customers > 0 ? $total_revenue / $total_new_customers : 0;
        
        return array(
            'total_new_customers' => $total_new_customers,
            'total_purchases' => $total_purchases,
            'total_revenue' => $total_revenue,
            'new_customers_change' => $new_customers_change,
            'average_revenue_per_customer' => $average_revenue_per_customer,
        );
    }

    /**
     * Get new subscriptions by period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period_type Period type (day, week, month).
     * @param string $status Subscription status (all, active, paused, cancelled).
     * @param array $product_ids Product IDs to filter by.
     * @return array New subscriptions by period.
     */
    private function get_new_subscriptions_by_period($start_date, $end_date, $period_type, $status = 'all', $product_ids = array()) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = "SELECT date_created, data_meta, COUNT(*) as count
                 FROM $table_name
                 WHERE data_type = %s
                 AND data_key = %s
                 AND date_created BETWEEN %s AND %s";
        
        $params = array(
            'subscriptions',
            'new',
            $start_datetime,
            $end_datetime,
        );
        
        // Add status filter
        if ($status !== 'all') {
            $query .= " AND data_meta LIKE %s";
            $params[] = '%"status":"' . $status . '"%';
        }
        
        // Add product filter
        if (!empty($product_ids)) {
            $query .= " AND (";
            
            foreach ($product_ids as $index => $product_id) {
                $query .= $index > 0 ? " OR " : "";
                $query .= "data_meta LIKE %s";
                $params[] = '%"product_id":' . $product_id . '%';
            }
            
            $query .= ")";
        }
        
        // Group by period
        switch ($period_type) {
            case 'day':
                $query .= " GROUP BY DATE(date_created)
                           ORDER BY date_created ASC";
                break;
                
            case 'week':
                $query .= " GROUP BY YEARWEEK(date_created, 1)
                           ORDER BY date_created ASC";
                break;
                
            case 'month':
                $query .= " GROUP BY YEAR(date_created), MONTH(date_created)
                           ORDER BY date_created ASC";
                break;
        }
        
        $prepared_query = $wpdb->prepare($query, $params);
        $results = $wpdb->get_results($prepared_query, ARRAY_A);
        
        $new_subscriptions = array();
        
        foreach ($results as $row) {
            $date = new DateTime($row['date_created']);
            
            switch ($period_type) {
                case 'day':
                    $period_key = $date->format('Y-m-d');
                    $period_label = $date->format(get_option('date_format'));
                    break;
                    
                case 'week':
                    $week_number = $date->format('W');
                    $year = $date->format('Y');
                    $period_key = $year . '-W' . $week_number;
                    $period_label = sprintf(__('Week %s, %s', 'aqualuxe'), $week_number, $year);
                    break;
                    
                case 'month':
                    $period_key = $date->format('Y-m');
                    $period_label = $date->format('F Y');
                    break;
            }
            
            if (!isset($new_subscriptions[$period_key])) {
                $new_subscriptions[$period_key] = array(
                    'label' => $period_label,
                    'total' => 0,
                    'count' => 0,
                );
            }
            
            $new_subscriptions[$period_key]['count'] += (int) $row['count'];
        }
        
        // Fill in missing periods
        $new_subscriptions = $this->fill_missing_periods($new_subscriptions, $start_date, $end_date, $period_type);
        
        return $new_subscriptions;
    }

    /**
     * Get subscription renewals by period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period_type Period type (day, week, month).
     * @param string $status Subscription status (all, active, paused, cancelled).
     * @param array $product_ids Product IDs to filter by.
     * @return array Subscription renewals by period.
     */
    private function get_subscription_renewals_by_period($start_date, $end_date, $period_type, $status = 'all', $product_ids = array()) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = "SELECT date_created, data_meta, SUM(data_value) as total, COUNT(*) as count
                 FROM $table_name
                 WHERE data_type = %s
                 AND data_key = %s
                 AND date_created BETWEEN %s AND %s";
        
        $params = array(
            'subscriptions',
            'renewal',
            $start_datetime,
            $end_datetime,
        );
        
        // Add status filter
        if ($status !== 'all') {
            $query .= " AND data_meta LIKE %s";
            $params[] = '%"status":"' . $status . '"%';
        }
        
        // Add product filter
        if (!empty($product_ids)) {
            $query .= " AND (";
            
            foreach ($product_ids as $index => $product_id) {
                $query .= $index > 0 ? " OR " : "";
                $query .= "data_meta LIKE %s";
                $params[] = '%"product_id":' . $product_id . '%';
            }
            
            $query .= ")";
        }
        
        // Group by period
        switch ($period_type) {
            case 'day':
                $query .= " GROUP BY DATE(date_created)
                           ORDER BY date_created ASC";
                break;
                
            case 'week':
                $query .= " GROUP BY YEARWEEK(date_created, 1)
                           ORDER BY date_created ASC";
                break;
                
            case 'month':
                $query .= " GROUP BY YEAR(date_created), MONTH(date_created)
                           ORDER BY date_created ASC";
                break;
        }
        
        $prepared_query = $wpdb->prepare($query, $params);
        $results = $wpdb->get_results($prepared_query, ARRAY_A);
        
        $subscription_renewals = array();
        
        foreach ($results as $row) {
            $date = new DateTime($row['date_created']);
            
            switch ($period_type) {
                case 'day':
                    $period_key = $date->format('Y-m-d');
                    $period_label = $date->format(get_option('date_format'));
                    break;
                    
                case 'week':
                    $week_number = $date->format('W');
                    $year = $date->format('Y');
                    $period_key = $year . '-W' . $week_number;
                    $period_label = sprintf(__('Week %s, %s', 'aqualuxe'), $week_number, $year);
                    break;
                    
                case 'month':
                    $period_key = $date->format('Y-m');
                    $period_label = $date->format('F Y');
                    break;
            }
            
            if (!isset($subscription_renewals[$period_key])) {
                $subscription_renewals[$period_key] = array(
                    'label' => $period_label,
                    'total' => 0,
                    'count' => 0,
                );
            }
            
            $subscription_renewals[$period_key]['total'] += (float) $row['total'];
            $subscription_renewals[$period_key]['count'] += (int) $row['count'];
        }
        
        // Fill in missing periods
        $subscription_renewals = $this->fill_missing_periods($subscription_renewals, $start_date, $end_date, $period_type);
        
        return $subscription_renewals;
    }

    /**
     * Get subscription cancellations by period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period_type Period type (day, week, month).
     * @param array $product_ids Product IDs to filter by.
     * @return array Subscription cancellations by period.
     */
    private function get_subscription_cancellations_by_period($start_date, $end_date, $period_type, $product_ids = array()) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = "SELECT date_created, data_meta, COUNT(*) as count
                 FROM $table_name
                 WHERE data_type = %s
                 AND data_key = %s
                 AND date_created BETWEEN %s AND %s";
        
        $params = array(
            'subscriptions',
            'cancelled',
            $start_datetime,
            $end_datetime,
        );
        
        // Add product filter
        if (!empty($product_ids)) {
            $query .= " AND (";
            
            foreach ($product_ids as $index => $product_id) {
                $query .= $index > 0 ? " OR " : "";
                $query .= "data_meta LIKE %s";
                $params[] = '%"product_id":' . $product_id . '%';
            }
            
            $query .= ")";
        }
        
        // Group by period
        switch ($period_type) {
            case 'day':
                $query .= " GROUP BY DATE(date_created)
                           ORDER BY date_created ASC";
                break;
                
            case 'week':
                $query .= " GROUP BY YEARWEEK(date_created, 1)
                           ORDER BY date_created ASC";
                break;
                
            case 'month':
                $query .= " GROUP BY YEAR(date_created), MONTH(date_created)
                           ORDER BY date_created ASC";
                break;
        }
        
        $prepared_query = $wpdb->prepare($query, $params);
        $results = $wpdb->get_results($prepared_query, ARRAY_A);
        
        $subscription_cancellations = array();
        
        foreach ($results as $row) {
            $date = new DateTime($row['date_created']);
            
            switch ($period_type) {
                case 'day':
                    $period_key = $date->format('Y-m-d');
                    $period_label = $date->format(get_option('date_format'));
                    break;
                    
                case 'week':
                    $week_number = $date->format('W');
                    $year = $date->format('Y');
                    $period_key = $year . '-W' . $week_number;
                    $period_label = sprintf(__('Week %s, %s', 'aqualuxe'), $week_number, $year);
                    break;
                    
                case 'month':
                    $period_key = $date->format('Y-m');
                    $period_label = $date->format('F Y');
                    break;
            }
            
            if (!isset($subscription_cancellations[$period_key])) {
                $subscription_cancellations[$period_key] = array(
                    'label' => $period_label,
                    'total' => 0,
                    'count' => 0,
                );
            }
            
            $subscription_cancellations[$period_key]['count'] += (int) $row['count'];
        }
        
        // Fill in missing periods
        $subscription_cancellations = $this->fill_missing_periods($subscription_cancellations, $start_date, $end_date, $period_type);
        
        return $subscription_cancellations;
    }

    /**
     * Get top subscription products.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param int $limit Number of products to return.
     * @return array Top subscription products.
     */
    private function get_top_subscription_products($start_date, $end_date, $limit = 10) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = $wpdb->prepare(
            "SELECT data_meta
            FROM $table_name
            WHERE data_type = %s
            AND data_key = %s
            AND date_created BETWEEN %s AND %s",
            'subscriptions',
            'new',
            $start_datetime,
            $end_datetime
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $product_counts = array();
        
        foreach ($results as $row) {
            $meta = maybe_unserialize($row['data_meta']);
            
            if (isset($meta['product_id'])) {
                $product_id = $meta['product_id'];
                
                if (!isset($product_counts[$product_id])) {
                    $product_counts[$product_id] = 0;
                }
                
                $product_counts[$product_id]++;
            }
        }
        
        // Sort by count
        arsort($product_counts);
        
        // Limit the number of products
        $product_counts = array_slice($product_counts, 0, $limit, true);
        
        $top_products = array();
        
        foreach ($product_counts as $product_id => $count) {
            $product = wc_get_product($product_id);
            
            if (!$product) {
                continue;
            }
            
            $top_products[] = array(
                'id' => $product_id,
                'name' => $product->get_name(),
                'count' => $count,
                'image' => wp_get_attachment_image_url($product->get_image_id(), 'thumbnail'),
                'url' => $product->get_permalink(),
            );
        }
        
        return $top_products;
    }

    /**
     * Calculate churn rate.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Churn rate data.
     */
    private function calculate_churn_rate($start_date, $end_date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        // Get total active subscriptions at the start of the period
        $args = array(
            'post_type' => 'aqualuxe_subscription',
            'posts_per_page' => -1,
            'meta_query' => array(
                array(
                    'key' => '_status',
                    'value' => 'active',
                ),
                array(
                    'key' => '_date_created',
                    'value' => $start_date,
                    'compare' => '<',
                    'type' => 'DATE',
                ),
            ),
            'fields' => 'ids',
        );
        
        $active_subscriptions_start = get_posts($args);
        $total_active_start = count($active_subscriptions_start);
        
        // Get cancelled subscriptions during the period
        $query = $wpdb->prepare(
            "SELECT COUNT(*) as count
            FROM $table_name
            WHERE data_type = %s
            AND data_key = %s
            AND date_created BETWEEN %s AND %s",
            'subscriptions',
            'cancelled',
            $start_datetime,
            $end_datetime
        );
        
        $cancelled_count = $wpdb->get_var($query);
        
        // Calculate churn rate
        $churn_rate = $total_active_start > 0 ? ($cancelled_count / $total_active_start) * 100 : 0;
        
        return array(
            'total_active_start' => $total_active_start,
            'cancelled_count' => $cancelled_count,
            'churn_rate' => $churn_rate,
        );
    }

    /**
     * Calculate subscription summary metrics.
     *
     * @param array $new_subscriptions New subscriptions data.
     * @param array $compare_new_subscriptions Comparison new subscriptions data.
     * @param array $subscription_renewals Subscription renewals data.
     * @param array $subscription_cancellations Subscription cancellations data.
     * @return array Summary metrics.
     */
    private function calculate_subscription_summary($new_subscriptions, $compare_new_subscriptions, $subscription_renewals, $subscription_cancellations) {
        $total_new_subscriptions = 0;
        $total_renewals = 0;
        $total_renewal_revenue = 0;
        $total_cancellations = 0;
        $compare_total_new_subscriptions = 0;
        
        foreach ($new_subscriptions as $data) {
            $total_new_subscriptions += $data['count'];
        }
        
        foreach ($compare_new_subscriptions as $data) {
            $compare_total_new_subscriptions += $data['count'];
        }
        
        foreach ($subscription_renewals as $data) {
            $total_renewals += $data['count'];
            $total_renewal_revenue += $data['total'];
        }
        
        foreach ($subscription_cancellations as $data) {
            $total_cancellations += $data['count'];
        }
        
        $new_subscriptions_change = 0;
        
        if ($compare_total_new_subscriptions > 0) {
            $new_subscriptions_change = (($total_new_subscriptions - $compare_total_new_subscriptions) / $compare_total_new_subscriptions) * 100;
        }
        
        $average_renewal_value = $total_renewals > 0 ? $total_renewal_revenue / $total_renewals : 0;
        
        return array(
            'total_new_subscriptions' => $total_new_subscriptions,
            'total_renewals' => $total_renewals,
            'total_renewal_revenue' => $total_renewal_revenue,
            'total_cancellations' => $total_cancellations,
            'new_subscriptions_change' => $new_subscriptions_change,
            'average_renewal_value' => $average_renewal_value,
        );
    }

    /**
     * Format subscription chart data for visualization.
     *
     * @param array $new_subscriptions New subscriptions data.
     * @param array $subscription_renewals Subscription renewals data.
     * @param array $subscription_cancellations Subscription cancellations data.
     * @param string $period_type Period type (day, week, month).
     * @return array Formatted chart data.
     */
    private function format_subscription_chart_data($new_subscriptions, $subscription_renewals, $subscription_cancellations, $period_type) {
        $labels = array();
        $new_values = array();
        $renewal_values = array();
        $cancellation_values = array();
        
        foreach ($new_subscriptions as $period_key => $period_data) {
            $labels[] = $period_data['label'];
            $new_values[] = $period_data['count'];
            $renewal_values[] = $subscription_renewals[$period_key]['count'];
            $cancellation_values[] = $subscription_cancellations[$period_key]['count'];
        }
        
        return array(
            'labels' => $labels,
            'datasets' => array(
                array(
                    'label' => __('New Subscriptions', 'aqualuxe'),
                    'data' => $new_values,
                    'backgroundColor' => 'rgba(54, 162, 235, 0.2)',
                    'borderColor' => 'rgba(54, 162, 235, 1)',
                    'borderWidth' => 1,
                ),
                array(
                    'label' => __('Renewals', 'aqualuxe'),
                    'data' => $renewal_values,
                    'backgroundColor' => 'rgba(75, 192, 192, 0.2)',
                    'borderColor' => 'rgba(75, 192, 192, 1)',
                    'borderWidth' => 1,
                ),
                array(
                    'label' => __('Cancellations', 'aqualuxe'),
                    'data' => $cancellation_values,
                    'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                    'borderColor' => 'rgba(255, 99, 132, 1)',
                    'borderWidth' => 1,
                ),
            ),
        );
    }
}

// Initialize the analytics reports class
AquaLuxe_Analytics_Reports::get_instance();