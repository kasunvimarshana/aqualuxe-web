<?php
/**
 * Template part for vendor dashboard
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Demo vendor data
$current_vendor = array(
    'id' => 1,
    'name' => 'Coral Reef Specialists',
    'logo' => get_template_directory_uri() . '/assets/images/vendor-1.png',
    'description' => 'Specializing in premium coral and reef supplies for enthusiasts and professionals.',
    'rating' => 4.8,
    'reviews' => 124,
    'joined' => '2023-05-15',
    'products' => 47,
    'sales' => 1258,
    'balance' => 12450.75,
);

// Demo recent orders
$recent_orders = array(
    array(
        'id' => '#ORD-9876',
        'date' => '2025-08-15',
        'customer' => 'John D.',
        'amount' => 349.99,
        'status' => 'completed',
        'items' => 3
    ),
    array(
        'id' => '#ORD-9875',
        'date' => '2025-08-14',
        'customer' => 'Sarah M.',
        'amount' => 129.50,
        'status' => 'processing',
        'items' => 2
    ),
    array(
        'id' => '#ORD-9874',
        'date' => '2025-08-13',
        'customer' => 'Robert K.',
        'amount' => 78.25,
        'status' => 'completed',
        'items' => 1
    ),
    array(
        'id' => '#ORD-9873',
        'date' => '2025-08-12',
        'customer' => 'Emily T.',
        'amount' => 215.75,
        'status' => 'completed',
        'items' => 4
    ),
    array(
        'id' => '#ORD-9872',
        'date' => '2025-08-11',
        'customer' => 'Michael P.',
        'amount' => 89.99,
        'status' => 'shipped',
        'items' => 1
    ),
);

// Demo top products
$top_products = array(
    array(
        'id' => 'PRD-1234',
        'name' => 'Premium Live Rock',
        'image' => get_template_directory_uri() . '/assets/images/product-rock.jpg',
        'price' => 49.99,
        'sales' => 87,
        'rating' => 4.9,
        'stock' => 35
    ),
    array(
        'id' => 'PRD-1235',
        'name' => 'Coral Food Blend',
        'image' => get_template_directory_uri() . '/assets/images/product-food.jpg',
        'price' => 24.99,
        'sales' => 65,
        'rating' => 4.7,
        'stock' => 120
    ),
    array(
        'id' => 'PRD-1236',
        'name' => 'Reef LED Light',
        'image' => get_template_directory_uri() . '/assets/images/product-light.jpg',
        'price' => 199.99,
        'sales' => 42,
        'rating' => 4.8,
        'stock' => 18
    ),
);

// Get status class
function get_status_class($status) {
    switch ($status) {
        case 'completed':
            return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200';
        case 'processing':
            return 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200';
        case 'shipped':
            return 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200';
        case 'pending':
            return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200';
        case 'cancelled':
            return 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200';
        default:
            return 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200';
    }
}
?>

<div class="vendor-dashboard">
    <!-- Vendor Header -->
    <div class="vendor-header bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 mb-6">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
            <div class="flex items-center mb-4 md:mb-0">
                <div class="vendor-logo w-16 h-16 rounded-lg overflow-hidden mr-4">
                    <img src="<?php echo esc_url($current_vendor['logo']); ?>" alt="<?php echo esc_attr($current_vendor['name']); ?>" class="w-full h-full object-cover">
                </div>
                <div class="vendor-info">
                    <h1 class="text-xl font-bold mb-1"><?php echo esc_html($current_vendor['name']); ?></h1>
                    <div class="vendor-meta flex items-center text-sm text-gray-600 dark:text-gray-400">
                        <div class="vendor-rating flex items-center mr-4">
                            <div class="stars text-accent mr-1">
                                <i class="fas fa-star"></i>
                            </div>
                            <span><?php echo esc_html($current_vendor['rating']); ?> (<?php echo esc_html($current_vendor['reviews']); ?> reviews)</span>
                        </div>
                        <div class="vendor-since">
                            Member since <?php echo esc_html(date('F Y', strtotime($current_vendor['joined']))); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="#" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                    <i class="fas fa-plus mr-1"></i> Add Product
                </a>
                <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light hover:bg-gray-300 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <i class="fas fa-cog mr-1"></i> Settings
                </a>
            </div>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="dashboard-stats grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Stat 1 -->
        <div class="stat-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Products</h3>
                <div class="stat-icon w-10 h-10 rounded-full bg-primary-light dark:bg-primary-dark flex items-center justify-center text-primary">
                    <i class="fas fa-box"></i>
                </div>
            </div>
            <div class="stat-value text-2xl font-bold mb-1"><?php echo esc_html($current_vendor['products']); ?></div>
            <div class="stat-trend text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up mr-1"></i> 12% from last month
            </div>
        </div>
        
        <!-- Stat 2 -->
        <div class="stat-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Sales</h3>
                <div class="stat-icon w-10 h-10 rounded-full bg-accent-light dark:bg-accent-dark flex items-center justify-center text-accent">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
            <div class="stat-value text-2xl font-bold mb-1"><?php echo esc_html($current_vendor['sales']); ?></div>
            <div class="stat-trend text-sm text-green-600 dark:text-green-400">
                <i class="fas fa-arrow-up mr-1"></i> 8% from last month
            </div>
        </div>
        
        <!-- Stat 3 -->
        <div class="stat-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Current Balance</h3>
                <div class="stat-icon w-10 h-10 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center text-green-600 dark:text-green-400">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-value text-2xl font-bold mb-1">$<?php echo esc_html(number_format($current_vendor['balance'], 2)); ?></div>
            <div class="stat-action text-sm">
                <a href="#" class="text-primary hover:text-primary-dark transition-colors">Withdraw Funds</a>
            </div>
        </div>
        
        <!-- Stat 4 -->
        <div class="stat-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Conversion Rate</h3>
                <div class="stat-icon w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900 flex items-center justify-center text-purple-600 dark:text-purple-400">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-value text-2xl font-bold mb-1">3.2%</div>
            <div class="stat-trend text-sm text-red-600 dark:text-red-400">
                <i class="fas fa-arrow-down mr-1"></i> 0.5% from last month
            </div>
        </div>
    </div>

    <!-- Recent Orders & Top Products -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Recent Orders -->
        <div class="recent-orders lg:col-span-2 bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
            <div class="section-header flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold">Recent Orders</h2>
                <a href="#" class="text-sm text-primary hover:text-primary-dark transition-colors">View All</a>
            </div>
            <div class="section-content p-6">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="text-left text-sm text-gray-500 dark:text-gray-400">
                                <th class="pb-3 font-medium">Order ID</th>
                                <th class="pb-3 font-medium">Date</th>
                                <th class="pb-3 font-medium">Customer</th>
                                <th class="pb-3 font-medium">Amount</th>
                                <th class="pb-3 font-medium">Status</th>
                                <th class="pb-3 font-medium">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recent_orders as $order) : ?>
                                <tr class="border-t border-gray-200 dark:border-gray-700">
                                    <td class="py-3 text-sm"><?php echo esc_html($order['id']); ?></td>
                                    <td class="py-3 text-sm"><?php echo esc_html(date('M d, Y', strtotime($order['date']))); ?></td>
                                    <td class="py-3 text-sm"><?php echo esc_html($order['customer']); ?></td>
                                    <td class="py-3 text-sm">$<?php echo esc_html(number_format($order['amount'], 2)); ?></td>
                                    <td class="py-3 text-sm">
                                        <span class="px-2 py-1 rounded-full text-xs <?php echo esc_attr(get_status_class($order['status'])); ?>">
                                            <?php echo esc_html(ucfirst($order['status'])); ?>
                                        </span>
                                    </td>
                                    <td class="py-3 text-sm">
                                        <a href="#" class="text-primary hover:text-primary-dark transition-colors">View</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Top Products -->
        <div class="top-products bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
            <div class="section-header flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-bold">Top Products</h2>
                <a href="#" class="text-sm text-primary hover:text-primary-dark transition-colors">View All</a>
            </div>
            <div class="section-content p-6">
                <div class="space-y-4">
                    <?php foreach ($top_products as $product) : ?>
                        <div class="product-item flex items-center">
                            <div class="product-image w-12 h-12 rounded overflow-hidden mr-3">
                                <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['name']); ?>" class="w-full h-full object-cover">
                            </div>
                            <div class="product-info flex-grow">
                                <h4 class="product-name text-sm font-medium mb-1"><?php echo esc_html($product['name']); ?></h4>
                                <div class="product-meta flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                                    <div class="product-price">$<?php echo esc_html(number_format($product['price'], 2)); ?></div>
                                    <div class="product-sales"><?php echo esc_html($product['sales']); ?> sales</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 mb-6">
        <h2 class="text-lg font-bold mb-4">Quick Actions</h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-4">
            <a href="#" class="action-card bg-light-dark dark:bg-dark-light rounded-lg p-4 text-center hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors">
                <div class="action-icon text-2xl text-primary mb-2">
                    <i class="fas fa-plus-circle"></i>
                </div>
                <div class="action-name text-sm font-medium">Add Product</div>
            </a>
            
            <a href="#" class="action-card bg-light-dark dark:bg-dark-light rounded-lg p-4 text-center hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors">
                <div class="action-icon text-2xl text-primary mb-2">
                    <i class="fas fa-tag"></i>
                </div>
                <div class="action-name text-sm font-medium">Create Coupon</div>
            </a>
            
            <a href="#" class="action-card bg-light-dark dark:bg-dark-light rounded-lg p-4 text-center hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors">
                <div class="action-icon text-2xl text-primary mb-2">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="action-name text-sm font-medium">View Reports</div>
            </a>
            
            <a href="#" class="action-card bg-light-dark dark:bg-dark-light rounded-lg p-4 text-center hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors">
                <div class="action-icon text-2xl text-primary mb-2">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="action-name text-sm font-medium">Withdraw</div>
            </a>
            
            <a href="#" class="action-card bg-light-dark dark:bg-dark-light rounded-lg p-4 text-center hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors">
                <div class="action-icon text-2xl text-primary mb-2">
                    <i class="fas fa-cog"></i>
                </div>
                <div class="action-name text-sm font-medium">Settings</div>
            </a>
            
            <a href="#" class="action-card bg-light-dark dark:bg-dark-light rounded-lg p-4 text-center hover:bg-gray-300 dark:hover:bg-gray-700 transition-colors">
                <div class="action-icon text-2xl text-primary mb-2">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="action-name text-sm font-medium">Help</div>
            </a>
        </div>
    </div>

    <!-- Inventory Alert -->
    <div class="inventory-alert bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 dark:border-yellow-600 p-4 rounded-lg mb-6">
        <div class="flex items-center">
            <div class="alert-icon text-yellow-600 dark:text-yellow-400 mr-3">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="alert-content">
                <h3 class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-1">Low Stock Alert</h3>
                <p class="text-xs text-yellow-700 dark:text-yellow-300">3 products are running low on inventory. <a href="#" class="underline">View inventory</a></p>
            </div>
        </div>
    </div>
</div>