<?php
/**
 * Template Name: AquaLuxe Business Dashboard
 * 
 * Comprehensive business operations dashboard for AquaLuxe's
 * ornamental aquatic solutions platform
 */

if (!defined('ABSPATH')) {
    exit;
}

// Ensure user has appropriate permissions
if (!is_user_logged_in()) {
    wp_redirect(wp_login_url(get_permalink()));
    exit;
}

$current_user = wp_get_current_user();
$user_roles = $current_user->roles;

// Check if user has business access
$business_roles = ['vendor', 'export_manager', 'service_provider', 'administrator'];
$has_access = array_intersect($user_roles, $business_roles);

if (empty($has_access)) {
    wp_die(__('You do not have permission to access this page.', 'enterprise-theme'));
}

get_header();

// Get business module instance
$business_module = AquaLuxe_Business_Module::get_instance();
?>

<div class="business-dashboard">
    <div class="container">
        
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1 class="dashboard-title">
                        <?php esc_html_e('AquaLuxe Business Portal', 'enterprise-theme'); ?>
                    </h1>
                    <p class="dashboard-subtitle">
                        <?php esc_html_e('Premium Ornamental Aquatic Solutions Platform', 'enterprise-theme'); ?>
                    </p>
                </div>
                <div class="col-md-4 text-end">
                    <div class="user-info">
                        <div class="user-avatar">
                            <?php echo get_avatar($current_user->ID, 48); ?>
                        </div>
                        <div class="user-details">
                            <div class="user-name"><?php echo esc_html($current_user->display_name); ?></div>
                            <div class="user-role"><?php echo esc_html(ucfirst($user_roles[0])); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Navigation -->
        <nav class="business-nav">
            <a href="#overview" class="business-nav-item active" data-tab="overview">
                <span class="business-nav-icon">📊</span>
                <span><?php esc_html_e('Overview', 'enterprise-theme'); ?></span>
            </a>
            
            <?php if (in_array('vendor', $user_roles) || in_array('administrator', $user_roles)): ?>
            <a href="#wholesale" class="business-nav-item" data-tab="wholesale">
                <span class="business-nav-icon">🏢</span>
                <span><?php esc_html_e('Wholesale', 'enterprise-theme'); ?></span>
            </a>
            <?php endif; ?>
            
            <a href="#retail" class="business-nav-item" data-tab="retail">
                <span class="business-nav-icon">🛒</span>
                <span><?php esc_html_e('Retail', 'enterprise-theme'); ?></span>
            </a>
            
            <a href="#trading" class="business-nav-item" data-tab="trading">
                <span class="business-nav-icon">🔄</span>
                <span><?php esc_html_e('Trading', 'enterprise-theme'); ?></span>
            </a>
            
            <?php if (in_array('export_manager', $user_roles) || in_array('administrator', $user_roles)): ?>
            <a href="#export" class="business-nav-item" data-tab="export">
                <span class="business-nav-icon">🌍</span>
                <span><?php esc_html_e('Export', 'enterprise-theme'); ?></span>
            </a>
            <?php endif; ?>
            
            <?php if (in_array('service_provider', $user_roles) || in_array('administrator', $user_roles)): ?>
            <a href="#services" class="business-nav-item" data-tab="services">
                <span class="business-nav-icon">⚙️</span>
                <span><?php esc_html_e('Services', 'enterprise-theme'); ?></span>
            </a>
            <?php endif; ?>
        </nav>

        <!-- Dashboard Content Tabs -->
        <div class="dashboard-content">

            <!-- Overview Tab -->
            <div id="overview" class="tab-content active">
                <div class="row">
                    <!-- Quick Stats -->
                    <div class="col-12 mb-6">
                        <div class="stats-grid">
                            <div class="stat-card">
                                <div class="stat-icon">🐠</div>
                                <div class="stat-info">
                                    <div class="stat-value" id="total-fish-species">0</div>
                                    <div class="stat-label"><?php esc_html_e('Fish Species', 'enterprise-theme'); ?></div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">🌿</div>
                                <div class="stat-info">
                                    <div class="stat-value" id="total-plants">0</div>
                                    <div class="stat-label"><?php esc_html_e('Aquatic Plants', 'enterprise-theme'); ?></div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">💰</div>
                                <div class="stat-info">
                                    <div class="stat-value" id="monthly-revenue">$0</div>
                                    <div class="stat-label"><?php esc_html_e('Monthly Revenue', 'enterprise-theme'); ?></div>
                                </div>
                            </div>
                            
                            <div class="stat-card">
                                <div class="stat-icon">📦</div>
                                <div class="stat-info">
                                    <div class="stat-value" id="pending-orders">0</div>
                                    <div class="stat-label"><?php esc_html_e('Pending Orders', 'enterprise-theme'); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Activity -->
                    <div class="col-md-8">
                        <div class="dashboard-widget">
                            <div class="widget-header">
                                <h3><?php esc_html_e('Recent Activity', 'enterprise-theme'); ?></h3>
                            </div>
                            <div class="widget-content">
                                <div id="recent-activity" class="activity-list">
                                    <!-- Activity items will be loaded via AJAX -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="col-md-4">
                        <div class="dashboard-widget">
                            <div class="widget-header">
                                <h3><?php esc_html_e('Quick Actions', 'enterprise-theme'); ?></h3>
                            </div>
                            <div class="widget-content">
                                <div class="quick-actions">
                                    <a href="#" class="action-btn" data-action="add-product">
                                        <span class="action-icon">➕</span>
                                        <span><?php esc_html_e('Add Product', 'enterprise-theme'); ?></span>
                                    </a>
                                    
                                    <a href="#" class="action-btn" data-action="create-trade">
                                        <span class="action-icon">🔄</span>
                                        <span><?php esc_html_e('Create Trade', 'enterprise-theme'); ?></span>
                                    </a>
                                    
                                    <a href="#" class="action-btn" data-action="book-service">
                                        <span class="action-icon">📅</span>
                                        <span><?php esc_html_e('Book Service', 'enterprise-theme'); ?></span>
                                    </a>
                                    
                                    <a href="#" class="action-btn" data-action="export-request">
                                        <span class="action-icon">🌍</span>
                                        <span><?php esc_html_e('Export Request', 'enterprise-theme'); ?></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wholesale Tab -->
            <?php if (in_array('vendor', $user_roles) || in_array('administrator', $user_roles)): ?>
            <div id="wholesale" class="tab-content">
                <div class="wholesale-portal">
                    <div class="row">
                        <div class="col-12 mb-6">
                            <h2><?php esc_html_e('Wholesale Operations', 'enterprise-theme'); ?></h2>
                            <p><?php esc_html_e('Manage bulk orders and vendor relationships', 'enterprise-theme'); ?></p>
                        </div>
                    </div>

                    <!-- Wholesale Tiers -->
                    <div class="wholesale-tiers">
                        <div class="wholesale-tier">
                            <div class="tier-name"><?php esc_html_e('Bronze Tier', 'enterprise-theme'); ?></div>
                            <div class="tier-minimum"><?php esc_html_e('Minimum Order: $500', 'enterprise-theme'); ?></div>
                            <div class="tier-discount">10%</div>
                            <ul class="tier-benefits">
                                <li><?php esc_html_e('10% bulk discount', 'enterprise-theme'); ?></li>
                                <li><?php esc_html_e('Priority customer support', 'enterprise-theme'); ?></li>
                                <li><?php esc_html_e('Monthly product updates', 'enterprise-theme'); ?></li>
                            </ul>
                            <button class="btn btn-primary btn-block"><?php esc_html_e('Apply Now', 'enterprise-theme'); ?></button>
                        </div>

                        <div class="wholesale-tier featured">
                            <div class="tier-name"><?php esc_html_e('Silver Tier', 'enterprise-theme'); ?></div>
                            <div class="tier-minimum"><?php esc_html_e('Minimum Order: $2,000', 'enterprise-theme'); ?></div>
                            <div class="tier-discount">20%</div>
                            <ul class="tier-benefits">
                                <li><?php esc_html_e('20% bulk discount', 'enterprise-theme'); ?></li>
                                <li><?php esc_html_e('Dedicated account manager', 'enterprise-theme'); ?></li>
                                <li><?php esc_html_e('Custom packaging options', 'enterprise-theme'); ?></li>
                                <li><?php esc_html_e('Flexible payment terms', 'enterprise-theme'); ?></li>
                            </ul>
                            <button class="btn btn-primary btn-block"><?php esc_html_e('Apply Now', 'enterprise-theme'); ?></button>
                        </div>

                        <div class="wholesale-tier">
                            <div class="tier-name"><?php esc_html_e('Gold Tier', 'enterprise-theme'); ?></div>
                            <div class="tier-minimum"><?php esc_html_e('Minimum Order: $10,000', 'enterprise-theme'); ?></div>
                            <div class="tier-discount">35%</div>
                            <ul class="tier-benefits">
                                <li><?php esc_html_e('35% bulk discount', 'enterprise-theme'); ?></li>
                                <li><?php esc_html_e('Priority shipping', 'enterprise-theme'); ?></li>
                                <li><?php esc_html_e('Exclusive rare species access', 'enterprise-theme'); ?></li>
                                <li><?php esc_html_e('Custom breeding programs', 'enterprise-theme'); ?></li>
                                <li><?php esc_html_e('White-label partnerships', 'enterprise-theme'); ?></li>
                            </ul>
                            <button class="btn btn-primary btn-block"><?php esc_html_e('Apply Now', 'enterprise-theme'); ?></button>
                        </div>
                    </div>

                    <!-- Wholesale Orders -->
                    <div class="row mt-8">
                        <div class="col-12">
                            <div class="dashboard-widget">
                                <div class="widget-header">
                                    <h3><?php esc_html_e('Recent Wholesale Orders', 'enterprise-theme'); ?></h3>
                                    <button class="btn btn-primary btn-sm"><?php esc_html_e('New Order', 'enterprise-theme'); ?></button>
                                </div>
                                <div class="widget-content">
                                    <div id="wholesale-orders" class="orders-table">
                                        <!-- Orders will be loaded via AJAX -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Retail Tab -->
            <div id="retail" class="tab-content">
                <div class="retail-section">
                    <div class="row">
                        <div class="col-12 mb-6">
                            <h2><?php esc_html_e('Retail Operations', 'enterprise-theme'); ?></h2>
                            <p><?php esc_html_e('Individual customer sales and inventory management', 'enterprise-theme'); ?></p>
                        </div>
                    </div>

                    <!-- Product Catalog with Filters -->
                    <div class="product-catalog">
                        <!-- Filters Sidebar -->
                        <div class="product-filters">
                            <div class="filter-group">
                                <h4 class="filter-title"><?php esc_html_e('Categories', 'enterprise-theme'); ?></h4>
                                <div class="filter-options">
                                    <div class="filter-option">
                                        <input type="checkbox" id="fish-species" value="fish_species">
                                        <label for="fish-species"><?php esc_html_e('Fish Species', 'enterprise-theme'); ?></label>
                                    </div>
                                    <div class="filter-option">
                                        <input type="checkbox" id="aquatic-plants" value="aquatic_plants">
                                        <label for="aquatic-plants"><?php esc_html_e('Aquatic Plants', 'enterprise-theme'); ?></label>
                                    </div>
                                    <div class="filter-option">
                                        <input type="checkbox" id="equipment" value="equipment">
                                        <label for="equipment"><?php esc_html_e('Equipment', 'enterprise-theme'); ?></label>
                                    </div>
                                </div>
                            </div>

                            <div class="filter-group">
                                <h4 class="filter-title"><?php esc_html_e('Price Range', 'enterprise-theme'); ?></h4>
                                <div class="price-range-slider">
                                    <input type="range" id="price-min" min="0" max="1000" value="0">
                                    <input type="range" id="price-max" min="0" max="1000" value="1000">
                                    <div class="price-range-values">
                                        <span id="price-min-value">$0</span> - <span id="price-max-value">$1000</span>
                                    </div>
                                </div>
                            </div>

                            <div class="filter-group">
                                <h4 class="filter-title"><?php esc_html_e('Availability', 'enterprise-theme'); ?></h4>
                                <div class="filter-options">
                                    <div class="filter-option">
                                        <input type="checkbox" id="in-stock" value="in_stock">
                                        <label for="in-stock"><?php esc_html_e('In Stock', 'enterprise-theme'); ?></label>
                                    </div>
                                    <div class="filter-option">
                                        <input type="checkbox" id="pre-order" value="pre_order">
                                        <label for="pre-order"><?php esc_html_e('Pre-Order', 'enterprise-theme'); ?></label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Products Grid -->
                        <div class="products-container">
                            <div class="products-header">
                                <div class="results-info">
                                    <span id="results-count">0</span> <?php esc_html_e('products found', 'enterprise-theme'); ?>
                                </div>
                                <div class="sort-options">
                                    <select id="sort-products">
                                        <option value="name-asc"><?php esc_html_e('Name (A-Z)', 'enterprise-theme'); ?></option>
                                        <option value="name-desc"><?php esc_html_e('Name (Z-A)', 'enterprise-theme'); ?></option>
                                        <option value="price-asc"><?php esc_html_e('Price (Low-High)', 'enterprise-theme'); ?></option>
                                        <option value="price-desc"><?php esc_html_e('Price (High-Low)', 'enterprise-theme'); ?></option>
                                        <option value="date-desc"><?php esc_html_e('Newest First', 'enterprise-theme'); ?></option>
                                    </select>
                                </div>
                            </div>

                            <div id="products-grid" class="products-grid">
                                <!-- Products will be loaded via AJAX -->
                            </div>

                            <div class="products-pagination">
                                <!-- Pagination will be loaded via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trading Tab -->
            <div id="trading" class="tab-content">
                <div class="trading-section">
                    <div class="row">
                        <div class="col-12 mb-6">
                            <h2><?php esc_html_e('Trading & Exchange', 'enterprise-theme'); ?></h2>
                            <p><?php esc_html_e('Connect with other aquarists for species trading and exchanges', 'enterprise-theme'); ?></p>
                        </div>
                    </div>

                    <!-- Trading Board -->
                    <div class="trading-board">
                        <div class="trading-header">
                            <div class="trading-tabs">
                                <button class="trading-tab active" data-tab="active-trades">
                                    <?php esc_html_e('Active Trades', 'enterprise-theme'); ?>
                                </button>
                                <button class="trading-tab" data-tab="my-trades">
                                    <?php esc_html_e('My Trades', 'enterprise-theme'); ?>
                                </button>
                                <button class="trading-tab" data-tab="completed">
                                    <?php esc_html_e('Completed', 'enterprise-theme'); ?>
                                </button>
                            </div>
                            <button class="btn btn-primary" id="create-trade-btn">
                                <?php esc_html_e('Create Trade Request', 'enterprise-theme'); ?>
                            </button>
                        </div>

                        <div class="trade-requests" id="trade-requests">
                            <!-- Trade requests will be loaded via AJAX -->
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Tab -->
            <?php if (in_array('export_manager', $user_roles) || in_array('administrator', $user_roles)): ?>
            <div id="export" class="tab-content">
                <div class="export-section">
                    <div class="row">
                        <div class="col-12 mb-6">
                            <h2><?php esc_html_e('Export Operations', 'enterprise-theme'); ?></h2>
                            <p><?php esc_html_e('International shipping and customs management', 'enterprise-theme'); ?></p>
                        </div>
                    </div>

                    <!-- Export Dashboard -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="dashboard-widget">
                                <div class="widget-header">
                                    <h3><?php esc_html_e('Active Shipments', 'enterprise-theme'); ?></h3>
                                </div>
                                <div class="widget-content">
                                    <div id="export-shipments">
                                        <!-- Shipments will be loaded via AJAX -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="dashboard-widget">
                                <div class="widget-header">
                                    <h3><?php esc_html_e('Export Statistics', 'enterprise-theme'); ?></h3>
                                </div>
                                <div class="widget-content">
                                    <div class="export-stats">
                                        <div class="stat-item">
                                            <div class="stat-value" id="total-exports">0</div>
                                            <div class="stat-label"><?php esc_html_e('Total Exports', 'enterprise-theme'); ?></div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value" id="countries-served">0</div>
                                            <div class="stat-label"><?php esc_html_e('Countries Served', 'enterprise-theme'); ?></div>
                                        </div>
                                        <div class="stat-item">
                                            <div class="stat-value" id="export-revenue">$0</div>
                                            <div class="stat-label"><?php esc_html_e('Export Revenue', 'enterprise-theme'); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Services Tab -->
            <?php if (in_array('service_provider', $user_roles) || in_array('administrator', $user_roles)): ?>
            <div id="services" class="tab-content">
                <div class="services-section">
                    <div class="row">
                        <div class="col-12 mb-6">
                            <h2><?php esc_html_e('Professional Services', 'enterprise-theme'); ?></h2>
                            <p><?php esc_html_e('Aquarium consultation, maintenance, and design services', 'enterprise-theme'); ?></p>
                        </div>
                    </div>

                    <!-- Service Booking Form -->
                    <div class="row">
                        <div class="col-md-8">
                            <div class="service-booking-form">
                                <h3><?php esc_html_e('Book a Service', 'enterprise-theme'); ?></h3>
                                
                                <div class="service-types">
                                    <div class="service-type" data-service="consultation">
                                        <div class="service-type-icon">💡</div>
                                        <div class="service-type-name"><?php esc_html_e('Consultation', 'enterprise-theme'); ?></div>
                                        <div class="service-type-description"><?php esc_html_e('Expert advice and planning', 'enterprise-theme'); ?></div>
                                    </div>

                                    <div class="service-type" data-service="maintenance">
                                        <div class="service-type-icon">🔧</div>
                                        <div class="service-type-name"><?php esc_html_e('Maintenance', 'enterprise-theme'); ?></div>
                                        <div class="service-type-description"><?php esc_html_e('Regular aquarium upkeep', 'enterprise-theme'); ?></div>
                                    </div>

                                    <div class="service-type" data-service="design">
                                        <div class="service-type-icon">🎨</div>
                                        <div class="service-type-name"><?php esc_html_e('Design', 'enterprise-theme'); ?></div>
                                        <div class="service-type-description"><?php esc_html_e('Custom aquarium design', 'enterprise-theme'); ?></div>
                                    </div>

                                    <div class="service-type" data-service="installation">
                                        <div class="service-type-icon">⚡</div>
                                        <div class="service-type-name"><?php esc_html_e('Installation', 'enterprise-theme'); ?></div>
                                        <div class="service-type-description"><?php esc_html_e('Professional setup service', 'enterprise-theme'); ?></div>
                                    </div>
                                </div>

                                <form id="service-booking-form" class="mt-6">
                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <label for="service-date" class="form-label"><?php esc_html_e('Preferred Date', 'enterprise-theme'); ?></label>
                                            <input type="date" id="service-date" name="service_date" class="form-control" required>
                                        </div>
                                        
                                        <div class="col-md-6 mb-4">
                                            <label for="service-time" class="form-label"><?php esc_html_e('Preferred Time', 'enterprise-theme'); ?></label>
                                            <select id="service-time" name="service_time" class="form-control" required>
                                                <option value=""><?php esc_html_e('Select Time', 'enterprise-theme'); ?></option>
                                                <option value="09:00">09:00 AM</option>
                                                <option value="10:00">10:00 AM</option>
                                                <option value="11:00">11:00 AM</option>
                                                <option value="14:00">02:00 PM</option>
                                                <option value="15:00">03:00 PM</option>
                                                <option value="16:00">04:00 PM</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="service-notes" class="form-label"><?php esc_html_e('Additional Notes', 'enterprise-theme'); ?></label>
                                        <textarea id="service-notes" name="service_notes" class="form-control" rows="4" placeholder="<?php esc_attr_e('Describe your requirements...', 'enterprise-theme'); ?>"></textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <?php esc_html_e('Book Service', 'enterprise-theme'); ?>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="dashboard-widget">
                                <div class="widget-header">
                                    <h3><?php esc_html_e('Upcoming Services', 'enterprise-theme'); ?></h3>
                                </div>
                                <div class="widget-content">
                                    <div id="upcoming-services">
                                        <!-- Upcoming services will be loaded via AJAX -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<!-- Modals and JavaScript will be included via footer -->
<?php get_footer(); ?>

<script>
jQuery(document).ready(function($) {
    // Initialize dashboard
    initializeDashboard();
    
    // Tab switching
    $('.business-nav-item').on('click', function(e) {
        e.preventDefault();
        
        const tab = $(this).data('tab');
        
        // Update nav
        $('.business-nav-item').removeClass('active');
        $(this).addClass('active');
        
        // Show tab content
        $('.tab-content').removeClass('active');
        $('#' + tab).addClass('active');
        
        // Load tab-specific content
        loadTabContent(tab);
    });
    
    // Initialize with overview tab
    loadTabContent('overview');
});

function initializeDashboard() {
    // Load dashboard statistics
    loadDashboardStats();
    
    // Set up real-time updates
    setInterval(updateDashboardStats, 30000); // Update every 30 seconds
}

function loadTabContent(tab) {
    switch(tab) {
        case 'overview':
            loadOverviewContent();
            break;
        case 'wholesale':
            loadWholesaleContent();
            break;
        case 'retail':
            loadRetailContent();
            break;
        case 'trading':
            loadTradingContent();
            break;
        case 'export':
            loadExportContent();
            break;
        case 'services':
            loadServicesContent();
            break;
    }
}

function loadDashboardStats() {
    jQuery.ajax({
        url: aqualuxe_ajax.ajax_url,
        type: 'POST',
        data: {
            action: 'aqualuxe_get_dashboard_stats',
            nonce: aqualuxe_ajax.nonce
        },
        success: function(response) {
            if (response.success) {
                updateStatsDisplay(response.data);
            }
        }
    });
}

function updateStatsDisplay(stats) {
    jQuery('#total-fish-species').text(stats.fish_species || 0);
    jQuery('#total-plants').text(stats.plants || 0);
    jQuery('#monthly-revenue').text('$' + (stats.revenue || 0).toLocaleString());
    jQuery('#pending-orders').text(stats.pending_orders || 0);
}

// Additional functions for each business module will be implemented
// based on the specific requirements of each section
</script>
