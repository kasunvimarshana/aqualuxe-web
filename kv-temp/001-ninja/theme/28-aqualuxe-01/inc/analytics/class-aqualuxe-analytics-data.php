<?php
/**
 * AquaLuxe Analytics Data
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Analytics_Data Class
 *
 * Handles data collection and processing for analytics
 */
class AquaLuxe_Analytics_Data {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        $this->define_hooks();
    }

    /**
     * Get the instance of this class.
     *
     * @return AquaLuxe_Analytics_Data A single instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Define the hooks for data collection.
     *
     * @return void
     */
    private function define_hooks() {
        // Daily data aggregation
        add_action('aqualuxe_analytics_daily_aggregate', array($this, 'aggregate_daily_data'));
        
        // Weekly data aggregation
        add_action('aqualuxe_analytics_weekly_aggregate', array($this, 'aggregate_weekly_data'));
        
        // Monthly data aggregation
        add_action('aqualuxe_analytics_monthly_aggregate', array($this, 'aggregate_monthly_data'));
    }

    /**
     * Aggregate daily data.
     *
     * @return void
     */
    public function aggregate_daily_data() {
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $this->aggregate_sales_data($yesterday, $yesterday, 'daily');
        $this->aggregate_inventory_data($yesterday, $yesterday, 'daily');
        $this->aggregate_customer_data($yesterday, $yesterday, 'daily');
        $this->aggregate_subscription_data($yesterday, $yesterday, 'daily');
    }

    /**
     * Aggregate weekly data.
     *
     * @return void
     */
    public function aggregate_weekly_data() {
        $week_start = date('Y-m-d', strtotime('last monday'));
        $week_end = date('Y-m-d', strtotime('last sunday'));
        $this->aggregate_sales_data($week_start, $week_end, 'weekly');
        $this->aggregate_inventory_data($week_start, $week_end, 'weekly');
        $this->aggregate_customer_data($week_start, $week_end, 'weekly');
        $this->aggregate_subscription_data($week_start, $week_end, 'weekly');
    }

    /**
     * Aggregate monthly data.
     *
     * @return void
     */
    public function aggregate_monthly_data() {
        $month_start = date('Y-m-01', strtotime('last month'));
        $month_end = date('Y-m-t', strtotime('last month'));
        $this->aggregate_sales_data($month_start, $month_end, 'monthly');
        $this->aggregate_inventory_data($month_start, $month_end, 'monthly');
        $this->aggregate_customer_data($month_start, $month_end, 'monthly');
        $this->aggregate_subscription_data($month_start, $month_end, 'monthly');
    }

    /**
     * Aggregate sales data for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period Period type (daily, weekly, monthly).
     * @return void
     */
    public function aggregate_sales_data($start_date, $end_date, $period) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        // Get total sales for the period
        $total_sales = $this->get_total_sales($start_date, $end_date);
        
        // Get product sales for the period
        $product_sales = $this->get_product_sales($start_date, $end_date);
        
        // Get category sales for the period
        $category_sales = $this->get_category_sales($start_date, $end_date);
        
        // Store aggregated total sales
        $this->store_aggregated_data('sales_aggregate', 'total_' . $period, $total_sales['total'], $total_sales['count'], array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'period' => $period,
        ));
        
        // Store aggregated product sales
        foreach ($product_sales as $product_id => $data) {
            $this->store_aggregated_data('sales_aggregate', 'product_' . $product_id . '_' . $period, $data['total'], $data['count'], array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'period' => $period,
                'product_id' => $product_id,
            ));
        }
        
        // Store aggregated category sales
        foreach ($category_sales as $category_id => $data) {
            $this->store_aggregated_data('sales_aggregate', 'category_' . $category_id . '_' . $period, $data['total'], $data['count'], array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'period' => $period,
                'category_id' => $category_id,
            ));
        }
    }

    /**
     * Aggregate inventory data for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period Period type (daily, weekly, monthly).
     * @return void
     */
    public function aggregate_inventory_data($start_date, $end_date, $period) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        // Get inventory movement for the period
        $inventory_movement = $this->get_inventory_movement($start_date, $end_date);
        
        // Get stock levels at the end of the period
        $stock_levels = $this->get_stock_levels($end_date);
        
        // Store aggregated inventory movement
        foreach ($inventory_movement as $product_id => $data) {
            $this->store_aggregated_data('inventory_aggregate', 'movement_' . $product_id . '_' . $period, 0, $data['quantity'], array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'period' => $period,
                'product_id' => $product_id,
            ));
        }
        
        // Store aggregated stock levels
        foreach ($stock_levels as $product_id => $stock) {
            $this->store_aggregated_data('inventory_aggregate', 'stock_' . $product_id . '_' . $period, $stock, 0, array(
                'date' => $end_date,
                'period' => $period,
                'product_id' => $product_id,
            ));
        }
    }

    /**
     * Aggregate customer data for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period Period type (daily, weekly, monthly).
     * @return void
     */
    public function aggregate_customer_data($start_date, $end_date, $period) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        // Get new customers for the period
        $new_customers = $this->get_new_customers($start_date, $end_date);
        
        // Get customer purchases for the period
        $customer_purchases = $this->get_customer_purchases($start_date, $end_date);
        
        // Store aggregated new customers
        $this->store_aggregated_data('customers_aggregate', 'new_' . $period, 0, count($new_customers), array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'period' => $period,
        ));
        
        // Store aggregated customer purchases
        $total_purchases = 0;
        $total_amount = 0;
        
        foreach ($customer_purchases as $customer_id => $data) {
            $total_purchases += $data['count'];
            $total_amount += $data['total'];
            
            $this->store_aggregated_data('customers_aggregate', 'purchase_' . $customer_id . '_' . $period, $data['total'], $data['count'], array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'period' => $period,
                'customer_id' => $customer_id,
            ));
        }
        
        // Store aggregated total customer purchases
        $this->store_aggregated_data('customers_aggregate', 'purchases_' . $period, $total_amount, $total_purchases, array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'period' => $period,
        ));
    }

    /**
     * Aggregate subscription data for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @param string $period Period type (daily, weekly, monthly).
     * @return void
     */
    public function aggregate_subscription_data($start_date, $end_date, $period) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        // Get new subscriptions for the period
        $new_subscriptions = $this->get_new_subscriptions($start_date, $end_date);
        
        // Get subscription renewals for the period
        $subscription_renewals = $this->get_subscription_renewals($start_date, $end_date);
        
        // Get subscription cancellations for the period
        $subscription_cancellations = $this->get_subscription_cancellations($start_date, $end_date);
        
        // Store aggregated new subscriptions
        $this->store_aggregated_data('subscriptions_aggregate', 'new_' . $period, 0, count($new_subscriptions), array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'period' => $period,
        ));
        
        // Store aggregated subscription renewals
        $total_renewals = 0;
        $total_renewal_amount = 0;
        
        foreach ($subscription_renewals as $subscription_id => $data) {
            $total_renewals += $data['count'];
            $total_renewal_amount += $data['total'];
            
            $this->store_aggregated_data('subscriptions_aggregate', 'renewal_' . $subscription_id . '_' . $period, $data['total'], $data['count'], array(
                'start_date' => $start_date,
                'end_date' => $end_date,
                'period' => $period,
                'subscription_id' => $subscription_id,
            ));
        }
        
        // Store aggregated total subscription renewals
        $this->store_aggregated_data('subscriptions_aggregate', 'renewals_' . $period, $total_renewal_amount, $total_renewals, array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'period' => $period,
        ));
        
        // Store aggregated subscription cancellations
        $this->store_aggregated_data('subscriptions_aggregate', 'cancellations_' . $period, 0, count($subscription_cancellations), array(
            'start_date' => $start_date,
            'end_date' => $end_date,
            'period' => $period,
        ));
    }

    /**
     * Get total sales for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Total sales data.
     */
    public function get_total_sales($start_date, $end_date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = $wpdb->prepare(
            "SELECT SUM(data_value) as total, SUM(data_count) as count
            FROM $table_name
            WHERE data_type = %s
            AND data_key = %s
            AND date_created BETWEEN %s AND %s",
            'sales',
            'total',
            $start_datetime,
            $end_datetime
        );
        
        $result = $wpdb->get_row($query, ARRAY_A);
        
        return array(
            'total' => (float) ($result['total'] ?? 0),
            'count' => (int) ($result['count'] ?? 0),
        );
    }

    /**
     * Get product sales for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Product sales data.
     */
    public function get_product_sales($start_date, $end_date) {
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
     * Get category sales for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Category sales data.
     */
    public function get_category_sales($start_date, $end_date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
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
                        'total' => 0,
                        'count' => 0,
                    );
                }
                
                $category_sales[$category->term_id]['total'] += $data['total'];
                $category_sales[$category->term_id]['count'] += $data['count'];
            }
        }
        
        return $category_sales;
    }

    /**
     * Get inventory movement for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Inventory movement data.
     */
    public function get_inventory_movement($start_date, $end_date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = $wpdb->prepare(
            "SELECT data_key, SUM(data_count) as quantity
            FROM $table_name
            WHERE data_type = %s
            AND data_key LIKE %s
            AND date_created BETWEEN %s AND %s
            GROUP BY data_key",
            'inventory',
            'product_%',
            $start_datetime,
            $end_datetime
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $inventory_movement = array();
        
        foreach ($results as $row) {
            $product_id = str_replace('product_', '', $row['data_key']);
            
            $inventory_movement[$product_id] = array(
                'quantity' => (int) $row['quantity'],
            );
        }
        
        return $inventory_movement;
    }

    /**
     * Get stock levels at a given date.
     *
     * @param string $date Date in Y-m-d format.
     * @return array Stock level data.
     */
    public function get_stock_levels($date) {
        // Get all WooCommerce products
        $args = array(
            'post_type' => 'product',
            'posts_per_page' => -1,
            'fields' => 'ids',
        );
        
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
                    
                    $stock_levels[$variation_id] = $variation->get_stock_quantity();
                }
            } else {
                $stock_levels[$product_id] = $product->get_stock_quantity();
            }
        }
        
        return $stock_levels;
    }

    /**
     * Get new customers for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array New customer IDs.
     */
    public function get_new_customers($start_date, $end_date) {
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
        
        $new_customers = get_users($args);
        
        return $new_customers;
    }

    /**
     * Get customer purchases for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Customer purchase data.
     */
    public function get_customer_purchases($start_date, $end_date) {
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
            'customers',
            'purchase_%',
            $start_datetime,
            $end_datetime
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $customer_purchases = array();
        
        foreach ($results as $row) {
            $customer_id = str_replace('purchase_', '', $row['data_key']);
            
            $customer_purchases[$customer_id] = array(
                'total' => (float) $row['total'],
                'count' => (int) $row['count'],
            );
        }
        
        return $customer_purchases;
    }

    /**
     * Get new subscriptions for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array New subscription IDs.
     */
    public function get_new_subscriptions($start_date, $end_date) {
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
        
        $subscription_ids = array();
        
        foreach ($results as $row) {
            $meta = maybe_unserialize($row['data_meta']);
            
            if (isset($meta['subscription_id'])) {
                $subscription_ids[] = $meta['subscription_id'];
            }
        }
        
        return $subscription_ids;
    }

    /**
     * Get subscription renewals for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Subscription renewal data.
     */
    public function get_subscription_renewals($start_date, $end_date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        $query = $wpdb->prepare(
            "SELECT data_meta, data_value
            FROM $table_name
            WHERE data_type = %s
            AND data_key = %s
            AND date_created BETWEEN %s AND %s",
            'subscriptions',
            'renewal',
            $start_datetime,
            $end_datetime
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $subscription_renewals = array();
        
        foreach ($results as $row) {
            $meta = maybe_unserialize($row['data_meta']);
            
            if (isset($meta['subscription_id'])) {
                $subscription_id = $meta['subscription_id'];
                
                if (!isset($subscription_renewals[$subscription_id])) {
                    $subscription_renewals[$subscription_id] = array(
                        'total' => 0,
                        'count' => 0,
                    );
                }
                
                $subscription_renewals[$subscription_id]['total'] += (float) $row['data_value'];
                $subscription_renewals[$subscription_id]['count']++;
            }
        }
        
        return $subscription_renewals;
    }

    /**
     * Get subscription cancellations for a given period.
     *
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Cancelled subscription IDs.
     */
    public function get_subscription_cancellations($start_date, $end_date) {
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
            'cancelled',
            $start_datetime,
            $end_datetime
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $subscription_ids = array();
        
        foreach ($results as $row) {
            $meta = maybe_unserialize($row['data_meta']);
            
            if (isset($meta['subscription_id'])) {
                $subscription_ids[] = $meta['subscription_id'];
            }
        }
        
        return $subscription_ids;
    }

    /**
     * Store aggregated data in the database.
     *
     * @param string $data_type The type of aggregated data.
     * @param string $data_key The specific data key.
     * @param float $data_value The value to store.
     * @param int $data_count The count to store.
     * @param array $data_meta Additional metadata.
     * @return bool|int The ID of the inserted row, or false on failure.
     */
    public function store_aggregated_data($data_type, $data_key, $data_value, $data_count, $data_meta = array()) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        // Sanitize inputs
        $data_type = sanitize_text_field($data_type);
        $data_key = sanitize_text_field($data_key);
        $data_value = floatval($data_value);
        $data_count = intval($data_count);
        
        // Sanitize meta data
        if (is_array($data_meta)) {
            foreach ($data_meta as $key => $value) {
                if (is_string($value)) {
                    $data_meta[$key] = sanitize_text_field($value);
                } elseif (is_numeric($value)) {
                    $data_meta[$key] = floatval($value);
                }
            }
        } else {
            $data_meta = array();
        }
        
        $result = $wpdb->insert(
            $table_name,
            array(
                'date_created' => current_time('mysql'),
                'data_type' => $data_type,
                'data_key' => $data_key,
                'data_value' => $data_value,
                'data_count' => $data_count,
                'data_meta' => maybe_serialize($data_meta),
            ),
            array(
                '%s',
                '%s',
                '%s',
                '%f',
                '%d',
                '%s',
            )
        );
        
        if ($result) {
            return $wpdb->insert_id;
        }
        
        return false;
    }

    /**
     * Get aggregated data from the database.
     *
     * @param string $data_type The type of aggregated data.
     * @param string $data_key The specific data key.
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Aggregated data.
     */
    public function get_aggregated_data($data_type, $data_key, $start_date = null, $end_date = null) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        // Sanitize inputs
        $data_type = sanitize_text_field($data_type);
        $data_key = sanitize_text_field($data_key);
        
        // Use placeholders for all variables in the query
        $query = "SELECT * FROM $table_name WHERE data_type = %s AND data_key = %s";
        $params = array($data_type, $data_key);
        
        if ($start_date && $end_date) {
            // Validate dates
            $start_date = sanitize_text_field($start_date);
            $end_date = sanitize_text_field($end_date);
            
            if (strtotime($start_date) && strtotime($end_date)) {
                $start_datetime = $start_date . ' 00:00:00';
                $end_datetime = $end_date . ' 23:59:59';
                
                $query .= " AND date_created BETWEEN %s AND %s";
                $params[] = $start_datetime;
                $params[] = $end_datetime;
            }
        }
        
        $query .= " ORDER BY date_created ASC";
        
        // Use prepare to safely add variables to the query
        $prepared_query = $wpdb->prepare($query, $params);
        $results = $wpdb->get_results($prepared_query, ARRAY_A);
        
        $data = array();
        
        foreach ($results as $row) {
            $data[] = array(
                'id' => $row['id'],
                'date_created' => $row['date_created'],
                'data_value' => (float) $row['data_value'],
                'data_count' => (int) $row['data_count'],
                'data_meta' => maybe_unserialize($row['data_meta']),
            );
        }
        
        return $data;
    }

    /**
     * Get aggregated data for a specific period.
     *
     * @param string $data_type The type of aggregated data.
     * @param string $period Period type (daily, weekly, monthly).
     * @param string $start_date Start date in Y-m-d format.
     * @param string $end_date End date in Y-m-d format.
     * @return array Aggregated data.
     */
    public function get_period_data($data_type, $period, $start_date, $end_date) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        // Sanitize inputs
        $data_type = sanitize_text_field($data_type);
        $period = sanitize_text_field($period);
        $start_date = sanitize_text_field($start_date);
        $end_date = sanitize_text_field($end_date);
        
        // Validate dates
        if (!strtotime($start_date) || !strtotime($end_date)) {
            return array();
        }
        
        $start_datetime = $start_date . ' 00:00:00';
        $end_datetime = $end_date . ' 23:59:59';
        
        // Validate period to prevent SQL injection
        $allowed_periods = array('daily', 'weekly', 'monthly');
        if (!in_array($period, $allowed_periods)) {
            return array();
        }
        
        $query = $wpdb->prepare(
            "SELECT data_key, data_value, data_count, data_meta
            FROM $table_name
            WHERE data_type = %s
            AND data_key LIKE %s
            AND date_created BETWEEN %s AND %s
            ORDER BY date_created ASC",
            $data_type . '_aggregate',
            '%_' . $period,
            $start_datetime,
            $end_datetime
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $data = array();
        
        foreach ($results as $row) {
            $key = str_replace('_' . $period, '', $row['data_key']);
            
            $data[$key] = array(
                'value' => (float) $row['data_value'],
                'count' => (int) $row['data_count'],
                'meta' => maybe_unserialize($row['data_meta']),
            );
        }
        
        return $data;
    }
}

// Initialize the analytics data class
AquaLuxe_Analytics_Data::get_instance();