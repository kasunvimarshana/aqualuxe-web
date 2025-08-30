<?php
/**
 * AquaLuxe Analytics Dashboard
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe_Analytics_Dashboard Class
 *
 * Handles the main analytics dashboard functionality
 */
class AquaLuxe_Analytics_Dashboard {

    /**
     * Instance of this class.
     *
     * @var object
     */
    protected static $instance = null;

    /**
     * Analytics reports instance.
     *
     * @var AquaLuxe_Analytics_Reports
     */
    private $analytics_reports;

    /**
     * Initialize the class and set its properties.
     */
    public function __construct() {
        $this->analytics_reports = AquaLuxe_Analytics_Reports::get_instance();
    }

    /**
     * Get the instance of this class.
     *
     * @return AquaLuxe_Analytics_Dashboard A single instance of this class.
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Get dashboard data for the main analytics dashboard.
     *
     * @param array $params Request parameters.
     * @return array Dashboard data.
     */
    public function get_dashboard_data($params) {
        // Set default parameters
        $defaults = array(
            'start_date' => date('Y-m-d', strtotime('-30 days')),
            'end_date' => date('Y-m-d'),
            'compare_start_date' => date('Y-m-d', strtotime('-60 days')),
            'compare_end_date' => date('Y-m-d', strtotime('-31 days')),
        );
        
        $params = wp_parse_args($params, $defaults);
        
        // Get sales summary
        $sales_data = $this->analytics_reports->get_sales_data($params);
        
        // Get inventory summary
        $inventory_data = $this->analytics_reports->get_inventory_data($params);
        
        // Get customer summary
        $customer_data = $this->analytics_reports->get_customers_data($params);
        
        // Get subscription summary
        $subscription_data = $this->analytics_reports->get_subscriptions_data($params);
        
        // Get recent activity
        $recent_activity = $this->get_recent_activity();
        
        // Get KPIs
        $kpis = $this->get_kpis($sales_data, $customer_data, $subscription_data);
        
        return array(
            'kpis' => $kpis,
            'sales_summary' => $sales_data['summary'],
            'sales_chart' => $sales_data['chart_data'],
            'top_products' => $sales_data['top_products'],
            'top_categories' => $sales_data['top_categories'],
            'inventory_summary' => array(
                'low_stock_count' => count($inventory_data['low_stock_products']),
                'out_of_stock_count' => count($inventory_data['out_of_stock_products']),
            ),
            'customer_summary' => $customer_data['summary'],
            'subscription_summary' => $subscription_data['summary'],
            'recent_activity' => $recent_activity,
            'period_type' => $sales_data['period_type'],
            'start_date' => $params['start_date'],
            'end_date' => $params['end_date'],
            'compare_start_date' => $params['compare_start_date'],
            'compare_end_date' => $params['compare_end_date'],
        );
    }

    /**
     * Get key performance indicators.
     *
     * @param array $sales_data Sales data.
     * @param array $customer_data Customer data.
     * @param array $subscription_data Subscription data.
     * @return array KPIs.
     */
    private function get_kpis($sales_data, $customer_data, $subscription_data) {
        return array(
            'revenue' => array(
                'value' => $sales_data['summary']['total_sales'],
                'change' => $sales_data['summary']['sales_change'],
                'label' => __('Revenue', 'aqualuxe'),
                'icon' => 'dashicons-chart-bar',
            ),
            'orders' => array(
                'value' => $sales_data['summary']['total_orders'],
                'change' => $sales_data['summary']['orders_change'],
                'label' => __('Orders', 'aqualuxe'),
                'icon' => 'dashicons-cart',
            ),
            'average_order' => array(
                'value' => $sales_data['summary']['average_order_value'],
                'change' => $sales_data['summary']['aov_change'],
                'label' => __('Average Order', 'aqualuxe'),
                'icon' => 'dashicons-money-alt',
            ),
            'new_customers' => array(
                'value' => $customer_data['summary']['total_new_customers'],
                'change' => $customer_data['summary']['new_customers_change'],
                'label' => __('New Customers', 'aqualuxe'),
                'icon' => 'dashicons-groups',
            ),
            'subscriptions' => array(
                'value' => $subscription_data['summary']['total_new_subscriptions'],
                'change' => $subscription_data['summary']['new_subscriptions_change'],
                'label' => __('New Subscriptions', 'aqualuxe'),
                'icon' => 'dashicons-update',
            ),
            'subscription_revenue' => array(
                'value' => $subscription_data['summary']['total_renewal_revenue'],
                'change' => 0, // No comparison data for this metric
                'label' => __('Subscription Revenue', 'aqualuxe'),
                'icon' => 'dashicons-money',
            ),
        );
    }

    /**
     * Get recent activity.
     *
     * @param int $limit Number of activities to return.
     * @return array Recent activity data.
     */
    private function get_recent_activity($limit = 10) {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_analytics_data';
        
        $query = $wpdb->prepare(
            "SELECT data_type, data_key, data_value, data_count, data_meta, date_created
            FROM $table_name
            ORDER BY date_created DESC
            LIMIT %d",
            $limit
        );
        
        $results = $wpdb->get_results($query, ARRAY_A);
        
        $activity = array();
        
        foreach ($results as $row) {
            $meta = maybe_unserialize($row['data_meta']);
            $date = new DateTime($row['date_created']);
            
            $activity_item = array(
                'date' => $date->format(get_option('date_format') . ' ' . get_option('time_format')),
                'timestamp' => $date->getTimestamp(),
            );
            
            switch ($row['data_type']) {
                case 'sales':
                    if ($row['data_key'] === 'total') {
                        $order_id = isset($meta['order_id']) ? $meta['order_id'] : 0;
                        $customer_id = isset($meta['customer_id']) ? $meta['customer_id'] : 0;
                        
                        $activity_item['type'] = 'order';
                        $activity_item['title'] = sprintf(__('New order #%s', 'aqualuxe'), $order_id);
                        $activity_item['description'] = sprintf(__('Order total: %s', 'aqualuxe'), wc_price($row['data_value']));
                        $activity_item['url'] = admin_url('post.php?post=' . $order_id . '&action=edit');
                        $activity_item['icon'] = 'dashicons-cart';
                    } elseif (strpos($row['data_key'], 'product_') === 0) {
                        $product_id = str_replace('product_', '', $row['data_key']);
                        $order_id = isset($meta['order_id']) ? $meta['order_id'] : 0;
                        $product = wc_get_product($product_id);
                        
                        if ($product) {
                            $activity_item['type'] = 'product_sale';
                            $activity_item['title'] = sprintf(__('Product sold: %s', 'aqualuxe'), $product->get_name());
                            $activity_item['description'] = sprintf(__('Quantity: %d, Total: %s', 'aqualuxe'), $row['data_count'], wc_price($row['data_value']));
                            $activity_item['url'] = admin_url('post.php?post=' . $order_id . '&action=edit');
                            $activity_item['icon'] = 'dashicons-products';
                        }
                    }
                    break;
                    
                case 'inventory':
                    $product_id = str_replace('product_', '', $row['data_key']);
                    $product = wc_get_product($product_id);
                    $action = isset($meta['action']) ? $meta['action'] : 'unknown';
                    
                    if ($product) {
                        $activity_item['type'] = 'inventory';
                        
                        if ($action === 'sale') {
                            $activity_item['title'] = sprintf(__('Inventory decreased: %s', 'aqualuxe'), $product->get_name());
                            $activity_item['description'] = sprintf(__('Quantity: -%d', 'aqualuxe'), $row['data_count']);
                            $activity_item['icon'] = 'dashicons-arrow-down';
                        } else {
                            $activity_item['title'] = sprintf(__('Inventory increased: %s', 'aqualuxe'), $product->get_name());
                            $activity_item['description'] = sprintf(__('Quantity: +%d', 'aqualuxe'), $row['data_count']);
                            $activity_item['icon'] = 'dashicons-arrow-up';
                        }
                        
                        $activity_item['url'] = admin_url('post.php?post=' . $product_id . '&action=edit');
                    }
                    break;
                    
                case 'customers':
                    if (strpos($row['data_key'], 'purchase_') === 0) {
                        $customer_id = str_replace('purchase_', '', $row['data_key']);
                        $order_id = isset($meta['order_id']) ? $meta['order_id'] : 0;
                        $customer = get_user_by('id', $customer_id);
                        
                        if ($customer) {
                            $activity_item['type'] = 'customer_purchase';
                            $activity_item['title'] = sprintf(__('Customer purchase: %s', 'aqualuxe'), $customer->display_name);
                            $activity_item['description'] = sprintf(__('Order #%s, Total: %s', 'aqualuxe'), $order_id, wc_price($row['data_value']));
                            $activity_item['url'] = admin_url('user-edit.php?user_id=' . $customer_id);
                            $activity_item['icon'] = 'dashicons-businessman';
                        }
                    }
                    break;
                    
                case 'subscriptions':
                    $subscription_id = isset($meta['subscription_id']) ? $meta['subscription_id'] : 0;
                    $customer_id = isset($meta['customer_id']) ? $meta['customer_id'] : 0;
                    $customer = get_user_by('id', $customer_id);
                    
                    if ($row['data_key'] === 'new') {
                        $activity_item['type'] = 'new_subscription';
                        $activity_item['title'] = __('New subscription', 'aqualuxe');
                        
                        if ($customer) {
                            $activity_item['description'] = sprintf(__('Customer: %s', 'aqualuxe'), $customer->display_name);
                        } else {
                            $activity_item['description'] = sprintf(__('Subscription ID: %s', 'aqualuxe'), $subscription_id);
                        }
                        
                        $activity_item['url'] = admin_url('post.php?post=' . $subscription_id . '&action=edit');
                        $activity_item['icon'] = 'dashicons-update';
                    } elseif ($row['data_key'] === 'renewal') {
                        $order_id = isset($meta['order_id']) ? $meta['order_id'] : 0;
                        
                        $activity_item['type'] = 'subscription_renewal';
                        $activity_item['title'] = __('Subscription renewed', 'aqualuxe');
                        
                        if ($customer) {
                            $activity_item['description'] = sprintf(__('Customer: %s, Total: %s', 'aqualuxe'), $customer->display_name, wc_price($row['data_value']));
                        } else {
                            $activity_item['description'] = sprintf(__('Subscription ID: %s, Total: %s', 'aqualuxe'), $subscription_id, wc_price($row['data_value']));
                        }
                        
                        $activity_item['url'] = admin_url('post.php?post=' . $subscription_id . '&action=edit');
                        $activity_item['icon'] = 'dashicons-update';
                    } elseif ($row['data_key'] === 'cancelled') {
                        $activity_item['type'] = 'subscription_cancelled';
                        $activity_item['title'] = __('Subscription cancelled', 'aqualuxe');
                        
                        if ($customer) {
                            $activity_item['description'] = sprintf(__('Customer: %s', 'aqualuxe'), $customer->display_name);
                        } else {
                            $activity_item['description'] = sprintf(__('Subscription ID: %s', 'aqualuxe'), $subscription_id);
                        }
                        
                        $activity_item['url'] = admin_url('post.php?post=' . $subscription_id . '&action=edit');
                        $activity_item['icon'] = 'dashicons-no';
                    }
                    break;
            }
            
            if (isset($activity_item['title'])) {
                $activity[] = $activity_item;
            }
        }
        
        return $activity;
    }
}

// Initialize the analytics dashboard class
AquaLuxe_Analytics_Dashboard::get_instance();