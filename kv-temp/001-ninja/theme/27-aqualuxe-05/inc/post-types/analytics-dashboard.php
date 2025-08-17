<?php
/**
 * Analytics Dashboard Post Type
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register analytics dashboard post type
 */
function aqualuxe_register_analytics_dashboard_post_type() {
    $labels = array(
        'name'                  => _x('Analytics Dashboards', 'Post Type General Name', 'aqualuxe'),
        'singular_name'         => _x('Analytics Dashboard', 'Post Type Singular Name', 'aqualuxe'),
        'menu_name'             => __('Analytics Dashboards', 'aqualuxe'),
        'name_admin_bar'        => __('Analytics Dashboard', 'aqualuxe'),
        'archives'              => __('Dashboard Archives', 'aqualuxe'),
        'attributes'            => __('Dashboard Attributes', 'aqualuxe'),
        'parent_item_colon'     => __('Parent Dashboard:', 'aqualuxe'),
        'all_items'             => __('All Dashboards', 'aqualuxe'),
        'add_new_item'          => __('Add New Dashboard', 'aqualuxe'),
        'add_new'               => __('Add New', 'aqualuxe'),
        'new_item'              => __('New Dashboard', 'aqualuxe'),
        'edit_item'             => __('Edit Dashboard', 'aqualuxe'),
        'update_item'           => __('Update Dashboard', 'aqualuxe'),
        'view_item'             => __('View Dashboard', 'aqualuxe'),
        'view_items'            => __('View Dashboards', 'aqualuxe'),
        'search_items'          => __('Search Dashboard', 'aqualuxe'),
        'not_found'             => __('Not found', 'aqualuxe'),
        'not_found_in_trash'    => __('Not found in Trash', 'aqualuxe'),
        'featured_image'        => __('Featured Image', 'aqualuxe'),
        'set_featured_image'    => __('Set featured image', 'aqualuxe'),
        'remove_featured_image' => __('Remove featured image', 'aqualuxe'),
        'use_featured_image'    => __('Use as featured image', 'aqualuxe'),
        'insert_into_item'      => __('Insert into dashboard', 'aqualuxe'),
        'uploaded_to_this_item' => __('Uploaded to this dashboard', 'aqualuxe'),
        'items_list'            => __('Dashboards list', 'aqualuxe'),
        'items_list_navigation' => __('Dashboards list navigation', 'aqualuxe'),
        'filter_items_list'     => __('Filter dashboards list', 'aqualuxe'),
    );
    
    $args = array(
        'label'                 => __('Analytics Dashboard', 'aqualuxe'),
        'description'           => __('Custom analytics dashboards', 'aqualuxe'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'author', 'thumbnail', 'revisions'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => 'aqualuxe-analytics',
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-chart-area',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    
    register_post_type('analytics_dashboard', $args);
}
add_action('init', 'aqualuxe_register_analytics_dashboard_post_type');

/**
 * Register analytics report post type
 */
function aqualuxe_register_analytics_report_post_type() {
    $labels = array(
        'name'                  => _x('Analytics Reports', 'Post Type General Name', 'aqualuxe'),
        'singular_name'         => _x('Analytics Report', 'Post Type Singular Name', 'aqualuxe'),
        'menu_name'             => __('Analytics Reports', 'aqualuxe'),
        'name_admin_bar'        => __('Analytics Report', 'aqualuxe'),
        'archives'              => __('Report Archives', 'aqualuxe'),
        'attributes'            => __('Report Attributes', 'aqualuxe'),
        'parent_item_colon'     => __('Parent Report:', 'aqualuxe'),
        'all_items'             => __('All Reports', 'aqualuxe'),
        'add_new_item'          => __('Add New Report', 'aqualuxe'),
        'add_new'               => __('Add New', 'aqualuxe'),
        'new_item'              => __('New Report', 'aqualuxe'),
        'edit_item'             => __('Edit Report', 'aqualuxe'),
        'update_item'           => __('Update Report', 'aqualuxe'),
        'view_item'             => __('View Report', 'aqualuxe'),
        'view_items'            => __('View Reports', 'aqualuxe'),
        'search_items'          => __('Search Report', 'aqualuxe'),
        'not_found'             => __('Not found', 'aqualuxe'),
        'not_found_in_trash'    => __('Not found in Trash', 'aqualuxe'),
        'featured_image'        => __('Featured Image', 'aqualuxe'),
        'set_featured_image'    => __('Set featured image', 'aqualuxe'),
        'remove_featured_image' => __('Remove featured image', 'aqualuxe'),
        'use_featured_image'    => __('Use as featured image', 'aqualuxe'),
        'insert_into_item'      => __('Insert into report', 'aqualuxe'),
        'uploaded_to_this_item' => __('Uploaded to this report', 'aqualuxe'),
        'items_list'            => __('Reports list', 'aqualuxe'),
        'items_list_navigation' => __('Reports list navigation', 'aqualuxe'),
        'filter_items_list'     => __('Filter reports list', 'aqualuxe'),
    );
    
    $args = array(
        'label'                 => __('Analytics Report', 'aqualuxe'),
        'description'           => __('Custom analytics reports', 'aqualuxe'),
        'labels'                => $labels,
        'supports'              => array('title', 'editor', 'author', 'thumbnail', 'revisions'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => 'aqualuxe-analytics',
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-chart-line',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    
    register_post_type('analytics_report', $args);
}
add_action('init', 'aqualuxe_register_analytics_report_post_type');

/**
 * Register analytics filter post type
 */
function aqualuxe_register_analytics_filter_post_type() {
    $labels = array(
        'name'                  => _x('Analytics Filters', 'Post Type General Name', 'aqualuxe'),
        'singular_name'         => _x('Analytics Filter', 'Post Type Singular Name', 'aqualuxe'),
        'menu_name'             => __('Analytics Filters', 'aqualuxe'),
        'name_admin_bar'        => __('Analytics Filter', 'aqualuxe'),
        'archives'              => __('Filter Archives', 'aqualuxe'),
        'attributes'            => __('Filter Attributes', 'aqualuxe'),
        'parent_item_colon'     => __('Parent Filter:', 'aqualuxe'),
        'all_items'             => __('All Filters', 'aqualuxe'),
        'add_new_item'          => __('Add New Filter', 'aqualuxe'),
        'add_new'               => __('Add New', 'aqualuxe'),
        'new_item'              => __('New Filter', 'aqualuxe'),
        'edit_item'             => __('Edit Filter', 'aqualuxe'),
        'update_item'           => __('Update Filter', 'aqualuxe'),
        'view_item'             => __('View Filter', 'aqualuxe'),
        'view_items'            => __('View Filters', 'aqualuxe'),
        'search_items'          => __('Search Filter', 'aqualuxe'),
        'not_found'             => __('Not found', 'aqualuxe'),
        'not_found_in_trash'    => __('Not found in Trash', 'aqualuxe'),
        'featured_image'        => __('Featured Image', 'aqualuxe'),
        'set_featured_image'    => __('Set featured image', 'aqualuxe'),
        'remove_featured_image' => __('Remove featured image', 'aqualuxe'),
        'use_featured_image'    => __('Use as featured image', 'aqualuxe'),
        'insert_into_item'      => __('Insert into filter', 'aqualuxe'),
        'uploaded_to_this_item' => __('Uploaded to this filter', 'aqualuxe'),
        'items_list'            => __('Filters list', 'aqualuxe'),
        'items_list_navigation' => __('Filters list navigation', 'aqualuxe'),
        'filter_items_list'     => __('Filter filters list', 'aqualuxe'),
    );
    
    $args = array(
        'label'                 => __('Analytics Filter', 'aqualuxe'),
        'description'           => __('Custom analytics filters', 'aqualuxe'),
        'labels'                => $labels,
        'supports'              => array('title'),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => true,
        'show_in_menu'          => 'aqualuxe-analytics',
        'menu_position'         => 5,
        'menu_icon'             => 'dashicons-filter',
        'show_in_admin_bar'     => false,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'page',
        'show_in_rest'          => true,
    );
    
    register_post_type('analytics_filter', $args);
}
add_action('init', 'aqualuxe_register_analytics_filter_post_type');

/**
 * Add meta boxes for analytics dashboard
 */
function aqualuxe_add_analytics_dashboard_meta_boxes() {
    add_meta_box(
        'aqualuxe_analytics_dashboard_settings',
        __('Dashboard Settings', 'aqualuxe'),
        'aqualuxe_analytics_dashboard_settings_callback',
        'analytics_dashboard',
        'normal',
        'high'
    );
    
    add_meta_box(
        'aqualuxe_analytics_dashboard_layout',
        __('Dashboard Layout', 'aqualuxe'),
        'aqualuxe_analytics_dashboard_layout_callback',
        'analytics_dashboard',
        'normal',
        'high'
    );
    
    add_meta_box(
        'aqualuxe_analytics_dashboard_widgets',
        __('Dashboard Widgets', 'aqualuxe'),
        'aqualuxe_analytics_dashboard_widgets_callback',
        'analytics_dashboard',
        'normal',
        'high'
    );
    
    add_meta_box(
        'aqualuxe_analytics_dashboard_access',
        __('Dashboard Access', 'aqualuxe'),
        'aqualuxe_analytics_dashboard_access_callback',
        'analytics_dashboard',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'aqualuxe_add_analytics_dashboard_meta_boxes');

/**
 * Dashboard settings meta box callback
 * 
 * @param WP_Post $post Post object
 */
function aqualuxe_analytics_dashboard_settings_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_analytics_dashboard_settings', 'aqualuxe_analytics_dashboard_settings_nonce');
    
    // Get saved values
    $default_date_range = get_post_meta($post->ID, '_default_date_range', true);
    $refresh_interval = get_post_meta($post->ID, '_refresh_interval', true);
    $auto_refresh = get_post_meta($post->ID, '_auto_refresh', true);
    $show_filters = get_post_meta($post->ID, '_show_filters', true);
    $show_export = get_post_meta($post->ID, '_show_export', true);
    
    // Default values
    if (empty($default_date_range)) {
        $default_date_range = '30';
    }
    
    if (empty($refresh_interval)) {
        $refresh_interval = '0';
    }
    
    if (empty($auto_refresh)) {
        $auto_refresh = '0';
    }
    
    if (empty($show_filters)) {
        $show_filters = '1';
    }
    
    if (empty($show_export)) {
        $show_export = '1';
    }
    
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="default_date_range"><?php esc_html_e('Default Date Range', 'aqualuxe'); ?></label>
            </th>
            <td>
                <select name="default_date_range" id="default_date_range">
                    <option value="7" <?php selected($default_date_range, '7'); ?>><?php esc_html_e('Last 7 days', 'aqualuxe'); ?></option>
                    <option value="30" <?php selected($default_date_range, '30'); ?>><?php esc_html_e('Last 30 days', 'aqualuxe'); ?></option>
                    <option value="90" <?php selected($default_date_range, '90'); ?>><?php esc_html_e('Last 90 days', 'aqualuxe'); ?></option>
                    <option value="365" <?php selected($default_date_range, '365'); ?>><?php esc_html_e('Last year', 'aqualuxe'); ?></option>
                    <option value="month" <?php selected($default_date_range, 'month'); ?>><?php esc_html_e('This month', 'aqualuxe'); ?></option>
                    <option value="quarter" <?php selected($default_date_range, 'quarter'); ?>><?php esc_html_e('This quarter', 'aqualuxe'); ?></option>
                    <option value="year" <?php selected($default_date_range, 'year'); ?>><?php esc_html_e('This year', 'aqualuxe'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Select the default date range for this dashboard.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="auto_refresh"><?php esc_html_e('Auto Refresh', 'aqualuxe'); ?></label>
            </th>
            <td>
                <select name="auto_refresh" id="auto_refresh">
                    <option value="0" <?php selected($auto_refresh, '0'); ?>><?php esc_html_e('Disabled', 'aqualuxe'); ?></option>
                    <option value="1" <?php selected($auto_refresh, '1'); ?>><?php esc_html_e('Enabled', 'aqualuxe'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Enable auto refresh for this dashboard.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="refresh_interval"><?php esc_html_e('Refresh Interval', 'aqualuxe'); ?></label>
            </th>
            <td>
                <select name="refresh_interval" id="refresh_interval">
                    <option value="0" <?php selected($refresh_interval, '0'); ?>><?php esc_html_e('Never', 'aqualuxe'); ?></option>
                    <option value="60" <?php selected($refresh_interval, '60'); ?>><?php esc_html_e('Every minute', 'aqualuxe'); ?></option>
                    <option value="300" <?php selected($refresh_interval, '300'); ?>><?php esc_html_e('Every 5 minutes', 'aqualuxe'); ?></option>
                    <option value="900" <?php selected($refresh_interval, '900'); ?>><?php esc_html_e('Every 15 minutes', 'aqualuxe'); ?></option>
                    <option value="1800" <?php selected($refresh_interval, '1800'); ?>><?php esc_html_e('Every 30 minutes', 'aqualuxe'); ?></option>
                    <option value="3600" <?php selected($refresh_interval, '3600'); ?>><?php esc_html_e('Every hour', 'aqualuxe'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Select the refresh interval for this dashboard.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="show_filters"><?php esc_html_e('Show Filters', 'aqualuxe'); ?></label>
            </th>
            <td>
                <select name="show_filters" id="show_filters">
                    <option value="1" <?php selected($show_filters, '1'); ?>><?php esc_html_e('Yes', 'aqualuxe'); ?></option>
                    <option value="0" <?php selected($show_filters, '0'); ?>><?php esc_html_e('No', 'aqualuxe'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Show filters on this dashboard.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="show_export"><?php esc_html_e('Show Export Options', 'aqualuxe'); ?></label>
            </th>
            <td>
                <select name="show_export" id="show_export">
                    <option value="1" <?php selected($show_export, '1'); ?>><?php esc_html_e('Yes', 'aqualuxe'); ?></option>
                    <option value="0" <?php selected($show_export, '0'); ?>><?php esc_html_e('No', 'aqualuxe'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Show export options on this dashboard.', 'aqualuxe'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Dashboard layout meta box callback
 * 
 * @param WP_Post $post Post object
 */
function aqualuxe_analytics_dashboard_layout_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_analytics_dashboard_layout', 'aqualuxe_analytics_dashboard_layout_nonce');
    
    // Get saved values
    $layout_type = get_post_meta($post->ID, '_layout_type', true);
    $layout_columns = get_post_meta($post->ID, '_layout_columns', true);
    $layout_sections = get_post_meta($post->ID, '_layout_sections', true);
    
    // Default values
    if (empty($layout_type)) {
        $layout_type = 'grid';
    }
    
    if (empty($layout_columns)) {
        $layout_columns = '2';
    }
    
    if (empty($layout_sections)) {
        $layout_sections = array(
            'kpi' => array(
                'title' => __('Key Performance Indicators', 'aqualuxe'),
                'enabled' => true,
                'order' => 1,
            ),
            'sales' => array(
                'title' => __('Sales Overview', 'aqualuxe'),
                'enabled' => true,
                'order' => 2,
            ),
            'inventory' => array(
                'title' => __('Inventory Overview', 'aqualuxe'),
                'enabled' => true,
                'order' => 3,
            ),
            'customers' => array(
                'title' => __('Customer Overview', 'aqualuxe'),
                'enabled' => true,
                'order' => 4,
            ),
            'subscriptions' => array(
                'title' => __('Subscription Overview', 'aqualuxe'),
                'enabled' => true,
                'order' => 5,
            ),
            'activity' => array(
                'title' => __('Recent Activity', 'aqualuxe'),
                'enabled' => true,
                'order' => 6,
            ),
        );
    }
    
    ?>
    <table class="form-table">
        <tr>
            <th scope="row">
                <label for="layout_type"><?php esc_html_e('Layout Type', 'aqualuxe'); ?></label>
            </th>
            <td>
                <select name="layout_type" id="layout_type">
                    <option value="grid" <?php selected($layout_type, 'grid'); ?>><?php esc_html_e('Grid', 'aqualuxe'); ?></option>
                    <option value="columns" <?php selected($layout_type, 'columns'); ?>><?php esc_html_e('Columns', 'aqualuxe'); ?></option>
                    <option value="tabs" <?php selected($layout_type, 'tabs'); ?>><?php esc_html_e('Tabs', 'aqualuxe'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Select the layout type for this dashboard.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="layout_columns"><?php esc_html_e('Grid Columns', 'aqualuxe'); ?></label>
            </th>
            <td>
                <select name="layout_columns" id="layout_columns">
                    <option value="1" <?php selected($layout_columns, '1'); ?>><?php esc_html_e('1 Column', 'aqualuxe'); ?></option>
                    <option value="2" <?php selected($layout_columns, '2'); ?>><?php esc_html_e('2 Columns', 'aqualuxe'); ?></option>
                    <option value="3" <?php selected($layout_columns, '3'); ?>><?php esc_html_e('3 Columns', 'aqualuxe'); ?></option>
                    <option value="4" <?php selected($layout_columns, '4'); ?>><?php esc_html_e('4 Columns', 'aqualuxe'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Select the number of columns for grid layout.', 'aqualuxe'); ?></p>
            </td>
        </tr>
    </table>
    
    <h3><?php esc_html_e('Dashboard Sections', 'aqualuxe'); ?></h3>
    <p><?php esc_html_e('Drag and drop to reorder sections. Check/uncheck to enable/disable sections.', 'aqualuxe'); ?></p>
    
    <ul id="aqualuxe-dashboard-sections" class="aqualuxe-sortable-list">
        <?php
        // Sort sections by order
        uasort($layout_sections, function($a, $b) {
            return $a['order'] - $b['order'];
        });
        
        foreach ($layout_sections as $section_id => $section) {
            ?>
            <li data-section="<?php echo esc_attr($section_id); ?>">
                <input type="checkbox" name="layout_sections[<?php echo esc_attr($section_id); ?>][enabled]" id="section_<?php echo esc_attr($section_id); ?>_enabled" value="1" <?php checked($section['enabled'], true); ?>>
                <label for="section_<?php echo esc_attr($section_id); ?>_enabled"><?php echo esc_html($section['title']); ?></label>
                <input type="hidden" name="layout_sections[<?php echo esc_attr($section_id); ?>][order]" value="<?php echo esc_attr($section['order']); ?>" class="section-order">
                <input type="hidden" name="layout_sections[<?php echo esc_attr($section_id); ?>][title]" value="<?php echo esc_attr($section['title']); ?>">
            </li>
            <?php
        }
        ?>
    </ul>
    
    <script>
    jQuery(document).ready(function($) {
        // Make sections sortable
        $('#aqualuxe-dashboard-sections').sortable({
            update: function(event, ui) {
                // Update order values
                $('#aqualuxe-dashboard-sections li').each(function(index) {
                    $(this).find('.section-order').val(index + 1);
                });
            }
        });
    });
    </script>
    
    <style>
    .aqualuxe-sortable-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .aqualuxe-sortable-list li {
        padding: 10px 15px;
        background-color: #f0f0f1;
        border: 1px solid #dcdcde;
        border-radius: 4px;
        margin-bottom: 5px;
        cursor: move;
        display: flex;
        align-items: center;
    }
    
    .aqualuxe-sortable-list li::before {
        content: "\f333";
        font-family: dashicons;
        margin-right: 10px;
        color: #646970;
    }
    
    .aqualuxe-sortable-list li input[type="checkbox"] {
        margin-right: 10px;
    }
    </style>
    <?php
}

/**
 * Dashboard widgets meta box callback
 * 
 * @param WP_Post $post Post object
 */
function aqualuxe_analytics_dashboard_widgets_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_analytics_dashboard_widgets', 'aqualuxe_analytics_dashboard_widgets_nonce');
    
    // Get saved values
    $widgets = get_post_meta($post->ID, '_widgets', true);
    
    // Default values
    if (empty($widgets)) {
        $widgets = array(
            'sales_chart' => array(
                'title' => __('Sales Chart', 'aqualuxe'),
                'type' => 'line',
                'data_source' => 'sales',
                'enabled' => true,
                'size' => 'large',
                'position' => 'sales',
            ),
            'top_products' => array(
                'title' => __('Top Products', 'aqualuxe'),
                'type' => 'list',
                'data_source' => 'products',
                'enabled' => true,
                'size' => 'medium',
                'position' => 'sales',
            ),
            'top_categories' => array(
                'title' => __('Top Categories', 'aqualuxe'),
                'type' => 'list',
                'data_source' => 'categories',
                'enabled' => true,
                'size' => 'medium',
                'position' => 'sales',
            ),
            'inventory_status' => array(
                'title' => __('Inventory Status', 'aqualuxe'),
                'type' => 'stats',
                'data_source' => 'inventory',
                'enabled' => true,
                'size' => 'medium',
                'position' => 'inventory',
            ),
            'low_stock' => array(
                'title' => __('Low Stock Products', 'aqualuxe'),
                'type' => 'list',
                'data_source' => 'low_stock',
                'enabled' => true,
                'size' => 'medium',
                'position' => 'inventory',
            ),
            'customer_growth' => array(
                'title' => __('Customer Growth', 'aqualuxe'),
                'type' => 'bar',
                'data_source' => 'customers',
                'enabled' => true,
                'size' => 'medium',
                'position' => 'customers',
            ),
            'top_customers' => array(
                'title' => __('Top Customers', 'aqualuxe'),
                'type' => 'list',
                'data_source' => 'customers',
                'enabled' => true,
                'size' => 'medium',
                'position' => 'customers',
            ),
            'subscription_growth' => array(
                'title' => __('Subscription Growth', 'aqualuxe'),
                'type' => 'bar',
                'data_source' => 'subscriptions',
                'enabled' => true,
                'size' => 'medium',
                'position' => 'subscriptions',
            ),
            'subscription_metrics' => array(
                'title' => __('Subscription Metrics', 'aqualuxe'),
                'type' => 'stats',
                'data_source' => 'subscriptions',
                'enabled' => true,
                'size' => 'medium',
                'position' => 'subscriptions',
            ),
            'recent_activity' => array(
                'title' => __('Recent Activity', 'aqualuxe'),
                'type' => 'activity',
                'data_source' => 'activity',
                'enabled' => true,
                'size' => 'large',
                'position' => 'activity',
            ),
        );
    }
    
    // Available widget types
    $widget_types = array(
        'line' => __('Line Chart', 'aqualuxe'),
        'bar' => __('Bar Chart', 'aqualuxe'),
        'pie' => __('Pie Chart', 'aqualuxe'),
        'doughnut' => __('Doughnut Chart', 'aqualuxe'),
        'radar' => __('Radar Chart', 'aqualuxe'),
        'polar' => __('Polar Area Chart', 'aqualuxe'),
        'list' => __('List', 'aqualuxe'),
        'stats' => __('Statistics', 'aqualuxe'),
        'activity' => __('Activity Feed', 'aqualuxe'),
        'kpi' => __('KPI Cards', 'aqualuxe'),
    );
    
    // Available data sources
    $data_sources = array(
        'sales' => __('Sales Data', 'aqualuxe'),
        'products' => __('Product Data', 'aqualuxe'),
        'categories' => __('Category Data', 'aqualuxe'),
        'inventory' => __('Inventory Data', 'aqualuxe'),
        'low_stock' => __('Low Stock Data', 'aqualuxe'),
        'customers' => __('Customer Data', 'aqualuxe'),
        'subscriptions' => __('Subscription Data', 'aqualuxe'),
        'activity' => __('Activity Data', 'aqualuxe'),
    );
    
    // Available widget sizes
    $widget_sizes = array(
        'small' => __('Small', 'aqualuxe'),
        'medium' => __('Medium', 'aqualuxe'),
        'large' => __('Large', 'aqualuxe'),
        'full' => __('Full Width', 'aqualuxe'),
    );
    
    // Available widget positions
    $widget_positions = array(
        'kpi' => __('KPI Section', 'aqualuxe'),
        'sales' => __('Sales Section', 'aqualuxe'),
        'inventory' => __('Inventory Section', 'aqualuxe'),
        'customers' => __('Customers Section', 'aqualuxe'),
        'subscriptions' => __('Subscriptions Section', 'aqualuxe'),
        'activity' => __('Activity Section', 'aqualuxe'),
    );
    
    ?>
    <div class="aqualuxe-widgets-container">
        <div class="aqualuxe-widgets-header">
            <button type="button" class="button button-primary add-widget"><?php esc_html_e('Add Widget', 'aqualuxe'); ?></button>
        </div>
        
        <div class="aqualuxe-widgets-list">
            <?php
            foreach ($widgets as $widget_id => $widget) {
                ?>
                <div class="aqualuxe-widget" data-widget-id="<?php echo esc_attr($widget_id); ?>">
                    <div class="aqualuxe-widget-header">
                        <h3><?php echo esc_html($widget['title']); ?></h3>
                        <div class="aqualuxe-widget-actions">
                            <button type="button" class="button edit-widget"><?php esc_html_e('Edit', 'aqualuxe'); ?></button>
                            <button type="button" class="button remove-widget"><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                        </div>
                    </div>
                    
                    <div class="aqualuxe-widget-content">
                        <input type="hidden" name="widgets[<?php echo esc_attr($widget_id); ?>][title]" value="<?php echo esc_attr($widget['title']); ?>">
                        <input type="hidden" name="widgets[<?php echo esc_attr($widget_id); ?>][type]" value="<?php echo esc_attr($widget['type']); ?>">
                        <input type="hidden" name="widgets[<?php echo esc_attr($widget_id); ?>][data_source]" value="<?php echo esc_attr($widget['data_source']); ?>">
                        <input type="hidden" name="widgets[<?php echo esc_attr($widget_id); ?>][enabled]" value="<?php echo esc_attr($widget['enabled'] ? '1' : '0'); ?>">
                        <input type="hidden" name="widgets[<?php echo esc_attr($widget_id); ?>][size]" value="<?php echo esc_attr($widget['size']); ?>">
                        <input type="hidden" name="widgets[<?php echo esc_attr($widget_id); ?>][position]" value="<?php echo esc_attr($widget['position']); ?>">
                        
                        <div class="aqualuxe-widget-info">
                            <div class="aqualuxe-widget-type">
                                <strong><?php esc_html_e('Type:', 'aqualuxe'); ?></strong>
                                <?php echo esc_html($widget_types[$widget['type']] ?? $widget['type']); ?>
                            </div>
                            
                            <div class="aqualuxe-widget-data-source">
                                <strong><?php esc_html_e('Data Source:', 'aqualuxe'); ?></strong>
                                <?php echo esc_html($data_sources[$widget['data_source']] ?? $widget['data_source']); ?>
                            </div>
                            
                            <div class="aqualuxe-widget-size">
                                <strong><?php esc_html_e('Size:', 'aqualuxe'); ?></strong>
                                <?php echo esc_html($widget_sizes[$widget['size']] ?? $widget['size']); ?>
                            </div>
                            
                            <div class="aqualuxe-widget-position">
                                <strong><?php esc_html_e('Position:', 'aqualuxe'); ?></strong>
                                <?php echo esc_html($widget_positions[$widget['position']] ?? $widget['position']); ?>
                            </div>
                            
                            <div class="aqualuxe-widget-status">
                                <strong><?php esc_html_e('Status:', 'aqualuxe'); ?></strong>
                                <?php echo $widget['enabled'] ? esc_html__('Enabled', 'aqualuxe') : esc_html__('Disabled', 'aqualuxe'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
        </div>
    </div>
    
    <!-- Widget Edit Modal -->
    <div id="aqualuxe-widget-modal" class="aqualuxe-modal" style="display: none;">
        <div class="aqualuxe-modal-content">
            <div class="aqualuxe-modal-header">
                <h2><?php esc_html_e('Edit Widget', 'aqualuxe'); ?></h2>
                <button type="button" class="aqualuxe-modal-close">&times;</button>
            </div>
            
            <div class="aqualuxe-modal-body">
                <form id="aqualuxe-widget-form">
                    <input type="hidden" id="widget_id" name="widget_id" value="">
                    
                    <div class="aqualuxe-form-field">
                        <label for="widget_title"><?php esc_html_e('Title', 'aqualuxe'); ?></label>
                        <input type="text" id="widget_title" name="widget_title" value="">
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="widget_type"><?php esc_html_e('Type', 'aqualuxe'); ?></label>
                        <select id="widget_type" name="widget_type">
                            <?php foreach ($widget_types as $type_id => $type_name) { ?>
                                <option value="<?php echo esc_attr($type_id); ?>"><?php echo esc_html($type_name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="widget_data_source"><?php esc_html_e('Data Source', 'aqualuxe'); ?></label>
                        <select id="widget_data_source" name="widget_data_source">
                            <?php foreach ($data_sources as $source_id => $source_name) { ?>
                                <option value="<?php echo esc_attr($source_id); ?>"><?php echo esc_html($source_name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="widget_size"><?php esc_html_e('Size', 'aqualuxe'); ?></label>
                        <select id="widget_size" name="widget_size">
                            <?php foreach ($widget_sizes as $size_id => $size_name) { ?>
                                <option value="<?php echo esc_attr($size_id); ?>"><?php echo esc_html($size_name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="widget_position"><?php esc_html_e('Position', 'aqualuxe'); ?></label>
                        <select id="widget_position" name="widget_position">
                            <?php foreach ($widget_positions as $position_id => $position_name) { ?>
                                <option value="<?php echo esc_attr($position_id); ?>"><?php echo esc_html($position_name); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    
                    <div class="aqualuxe-form-field">
                        <label for="widget_enabled"><?php esc_html_e('Status', 'aqualuxe'); ?></label>
                        <select id="widget_enabled" name="widget_enabled">
                            <option value="1"><?php esc_html_e('Enabled', 'aqualuxe'); ?></option>
                            <option value="0"><?php esc_html_e('Disabled', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                </form>
            </div>
            
            <div class="aqualuxe-modal-footer">
                <button type="button" class="button aqualuxe-modal-cancel"><?php esc_html_e('Cancel', 'aqualuxe'); ?></button>
                <button type="button" class="button button-primary aqualuxe-modal-save"><?php esc_html_e('Save Changes', 'aqualuxe'); ?></button>
            </div>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Edit widget
        $('.edit-widget').on('click', function() {
            const widget = $(this).closest('.aqualuxe-widget');
            const widgetId = widget.data('widget-id');
            
            // Get widget data
            const title = widget.find('input[name="widgets[' + widgetId + '][title]"]').val();
            const type = widget.find('input[name="widgets[' + widgetId + '][type]"]').val();
            const dataSource = widget.find('input[name="widgets[' + widgetId + '][data_source]"]').val();
            const enabled = widget.find('input[name="widgets[' + widgetId + '][enabled]"]').val();
            const size = widget.find('input[name="widgets[' + widgetId + '][size]"]').val();
            const position = widget.find('input[name="widgets[' + widgetId + '][position]"]').val();
            
            // Set form values
            $('#widget_id').val(widgetId);
            $('#widget_title').val(title);
            $('#widget_type').val(type);
            $('#widget_data_source').val(dataSource);
            $('#widget_enabled').val(enabled);
            $('#widget_size').val(size);
            $('#widget_position').val(position);
            
            // Show modal
            $('#aqualuxe-widget-modal').show();
        });
        
        // Remove widget
        $('.remove-widget').on('click', function() {
            if (confirm('<?php esc_html_e('Are you sure you want to remove this widget?', 'aqualuxe'); ?>')) {
                $(this).closest('.aqualuxe-widget').remove();
            }
        });
        
        // Add widget
        $('.add-widget').on('click', function() {
            // Generate unique ID
            const widgetId = 'widget_' + Math.random().toString(36).substr(2, 9);
            
            // Set form values
            $('#widget_id').val(widgetId);
            $('#widget_title').val('<?php esc_html_e('New Widget', 'aqualuxe'); ?>');
            $('#widget_type').val('line');
            $('#widget_data_source').val('sales');
            $('#widget_enabled').val('1');
            $('#widget_size').val('medium');
            $('#widget_position').val('sales');
            
            // Show modal
            $('#aqualuxe-widget-modal').show();
        });
        
        // Close modal
        $('.aqualuxe-modal-close, .aqualuxe-modal-cancel').on('click', function() {
            $('#aqualuxe-widget-modal').hide();
        });
        
        // Save widget
        $('.aqualuxe-modal-save').on('click', function() {
            const widgetId = $('#widget_id').val();
            const title = $('#widget_title').val();
            const type = $('#widget_type').val();
            const dataSource = $('#widget_data_source').val();
            const enabled = $('#widget_enabled').val();
            const size = $('#widget_size').val();
            const position = $('#widget_position').val();
            
            // Check if widget exists
            const widget = $('.aqualuxe-widget[data-widget-id="' + widgetId + '"]');
            
            if (widget.length) {
                // Update existing widget
                widget.find('h3').text(title);
                widget.find('input[name="widgets[' + widgetId + '][title]"]').val(title);
                widget.find('input[name="widgets[' + widgetId + '][type]"]').val(type);
                widget.find('input[name="widgets[' + widgetId + '][data_source]"]').val(dataSource);
                widget.find('input[name="widgets[' + widgetId + '][enabled]"]').val(enabled);
                widget.find('input[name="widgets[' + widgetId + '][size]"]').val(size);
                widget.find('input[name="widgets[' + widgetId + '][position]"]').val(position);
                
                // Update widget info
                widget.find('.aqualuxe-widget-type').html('<strong><?php esc_html_e('Type:', 'aqualuxe'); ?></strong> ' + $('#widget_type option:selected').text());
                widget.find('.aqualuxe-widget-data-source').html('<strong><?php esc_html_e('Data Source:', 'aqualuxe'); ?></strong> ' + $('#widget_data_source option:selected').text());
                widget.find('.aqualuxe-widget-size').html('<strong><?php esc_html_e('Size:', 'aqualuxe'); ?></strong> ' + $('#widget_size option:selected').text());
                widget.find('.aqualuxe-widget-position').html('<strong><?php esc_html_e('Position:', 'aqualuxe'); ?></strong> ' + $('#widget_position option:selected').text());
                widget.find('.aqualuxe-widget-status').html('<strong><?php esc_html_e('Status:', 'aqualuxe'); ?></strong> ' + (enabled === '1' ? '<?php esc_html_e('Enabled', 'aqualuxe'); ?>' : '<?php esc_html_e('Disabled', 'aqualuxe'); ?>'));
            } else {
                // Create new widget
                const newWidget = $('<div class="aqualuxe-widget" data-widget-id="' + widgetId + '"></div>');
                
                newWidget.html(
                    '<div class="aqualuxe-widget-header">' +
                    '<h3>' + title + '</h3>' +
                    '<div class="aqualuxe-widget-actions">' +
                    '<button type="button" class="button edit-widget"><?php esc_html_e('Edit', 'aqualuxe'); ?></button>' +
                    '<button type="button" class="button remove-widget"><?php esc_html_e('Remove', 'aqualuxe'); ?></button>' +
                    '</div>' +
                    '</div>' +
                    '<div class="aqualuxe-widget-content">' +
                    '<input type="hidden" name="widgets[' + widgetId + '][title]" value="' + title + '">' +
                    '<input type="hidden" name="widgets[' + widgetId + '][type]" value="' + type + '">' +
                    '<input type="hidden" name="widgets[' + widgetId + '][data_source]" value="' + dataSource + '">' +
                    '<input type="hidden" name="widgets[' + widgetId + '][enabled]" value="' + enabled + '">' +
                    '<input type="hidden" name="widgets[' + widgetId + '][size]" value="' + size + '">' +
                    '<input type="hidden" name="widgets[' + widgetId + '][position]" value="' + position + '">' +
                    '<div class="aqualuxe-widget-info">' +
                    '<div class="aqualuxe-widget-type"><strong><?php esc_html_e('Type:', 'aqualuxe'); ?></strong> ' + $('#widget_type option:selected').text() + '</div>' +
                    '<div class="aqualuxe-widget-data-source"><strong><?php esc_html_e('Data Source:', 'aqualuxe'); ?></strong> ' + $('#widget_data_source option:selected').text() + '</div>' +
                    '<div class="aqualuxe-widget-size"><strong><?php esc_html_e('Size:', 'aqualuxe'); ?></strong> ' + $('#widget_size option:selected').text() + '</div>' +
                    '<div class="aqualuxe-widget-position"><strong><?php esc_html_e('Position:', 'aqualuxe'); ?></strong> ' + $('#widget_position option:selected').text() + '</div>' +
                    '<div class="aqualuxe-widget-status"><strong><?php esc_html_e('Status:', 'aqualuxe'); ?></strong> ' + (enabled === '1' ? '<?php esc_html_e('Enabled', 'aqualuxe'); ?>' : '<?php esc_html_e('Disabled', 'aqualuxe'); ?>') + '</div>' +
                    '</div>' +
                    '</div>'
                );
                
                $('.aqualuxe-widgets-list').append(newWidget);
                
                // Add event handlers
                newWidget.find('.edit-widget').on('click', function() {
                    const widget = $(this).closest('.aqualuxe-widget');
                    const widgetId = widget.data('widget-id');
                    
                    // Get widget data
                    const title = widget.find('input[name="widgets[' + widgetId + '][title]"]').val();
                    const type = widget.find('input[name="widgets[' + widgetId + '][type]"]').val();
                    const dataSource = widget.find('input[name="widgets[' + widgetId + '][data_source]"]').val();
                    const enabled = widget.find('input[name="widgets[' + widgetId + '][enabled]"]').val();
                    const size = widget.find('input[name="widgets[' + widgetId + '][size]"]').val();
                    const position = widget.find('input[name="widgets[' + widgetId + '][position]"]').val();
                    
                    // Set form values
                    $('#widget_id').val(widgetId);
                    $('#widget_title').val(title);
                    $('#widget_type').val(type);
                    $('#widget_data_source').val(dataSource);
                    $('#widget_enabled').val(enabled);
                    $('#widget_size').val(size);
                    $('#widget_position').val(position);
                    
                    // Show modal
                    $('#aqualuxe-widget-modal').show();
                });
                
                newWidget.find('.remove-widget').on('click', function() {
                    if (confirm('<?php esc_html_e('Are you sure you want to remove this widget?', 'aqualuxe'); ?>')) {
                        $(this).closest('.aqualuxe-widget').remove();
                    }
                });
            }
            
            // Close modal
            $('#aqualuxe-widget-modal').hide();
        });
    });
    </script>
    
    <style>
    .aqualuxe-widgets-container {
        margin-bottom: 20px;
    }
    
    .aqualuxe-widgets-header {
        margin-bottom: 15px;
    }
    
    .aqualuxe-widgets-list {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 15px;
    }
    
    .aqualuxe-widget {
        border: 1px solid #dcdcde;
        border-radius: 4px;
        background-color: #fff;
    }
    
    .aqualuxe-widget-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        border-bottom: 1px solid #dcdcde;
        background-color: #f0f0f1;
    }
    
    .aqualuxe-widget-header h3 {
        margin: 0;
        font-size: 14px;
    }
    
    .aqualuxe-widget-actions {
        display: flex;
        gap: 5px;
    }
    
    .aqualuxe-widget-content {
        padding: 15px;
    }
    
    .aqualuxe-widget-info {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }
    
    .aqualuxe-widget-info > div {
        margin-bottom: 5px;
    }
    
    .aqualuxe-modal {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 100000;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .aqualuxe-modal-content {
        background-color: #fff;
        border-radius: 4px;
        width: 600px;
        max-width: 90%;
        max-height: 90%;
        display: flex;
        flex-direction: column;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    }
    
    .aqualuxe-modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        border-bottom: 1px solid #dcdcde;
    }
    
    .aqualuxe-modal-header h2 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
    }
    
    .aqualuxe-modal-close {
        background: none;
        border: none;
        font-size: 20px;
        cursor: pointer;
        color: #646970;
    }
    
    .aqualuxe-modal-body {
        padding: 20px;
        overflow-y: auto;
        flex-grow: 1;
    }
    
    .aqualuxe-modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 15px 20px;
        border-top: 1px solid #dcdcde;
        gap: 10px;
    }
    
    .aqualuxe-form-field {
        margin-bottom: 15px;
    }
    
    .aqualuxe-form-field label {
        display: block;
        margin-bottom: 5px;
        font-weight: 600;
    }
    
    .aqualuxe-form-field input[type="text"],
    .aqualuxe-form-field select {
        width: 100%;
        padding: 8px;
        border: 1px solid #dcdcde;
        border-radius: 4px;
    }
    </style>
    <?php
}

/**
 * Dashboard access meta box callback
 * 
 * @param WP_Post $post Post object
 */
function aqualuxe_analytics_dashboard_access_callback($post) {
    // Add nonce for security
    wp_nonce_field('aqualuxe_analytics_dashboard_access', 'aqualuxe_analytics_dashboard_access_nonce');
    
    // Get saved values
    $access_roles = get_post_meta($post->ID, '_access_roles', true);
    $access_users = get_post_meta($post->ID, '_access_users', true);
    
    // Default values
    if (empty($access_roles)) {
        $access_roles = array('administrator');
    }
    
    if (empty($access_users)) {
        $access_users = array();
    }
    
    // Get all roles
    $roles = wp_roles()->get_names();
    
    ?>
    <div class="aqualuxe-access-container">
        <h4><?php esc_html_e('User Roles', 'aqualuxe'); ?></h4>
        <p><?php esc_html_e('Select which user roles can access this dashboard.', 'aqualuxe'); ?></p>
        
        <div class="aqualuxe-access-roles">
            <?php foreach ($roles as $role_id => $role_name) { ?>
                <label>
                    <input type="checkbox" name="access_roles[]" value="<?php echo esc_attr($role_id); ?>" <?php checked(in_array($role_id, $access_roles)); ?>>
                    <?php echo esc_html($role_name); ?>
                </label>
            <?php } ?>
        </div>
        
        <h4><?php esc_html_e('Specific Users', 'aqualuxe'); ?></h4>
        <p><?php esc_html_e('Select specific users who can access this dashboard, regardless of their role.', 'aqualuxe'); ?></p>
        
        <select name="access_users[]" id="access_users" multiple>
            <?php
            // Get selected users
            if (!empty($access_users)) {
                foreach ($access_users as $user_id) {
                    $user = get_user_by('id', $user_id);
                    if ($user) {
                        ?>
                        <option value="<?php echo esc_attr($user_id); ?>" selected>
                            <?php echo esc_html($user->display_name . ' (' . $user->user_email . ')'); ?>
                        </option>
                        <?php
                    }
                }
            }
            ?>
        </select>
        
        <p class="description"><?php esc_html_e('Start typing to search for users.', 'aqualuxe'); ?></p>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        // Initialize select2 for user selection
        if (typeof $.fn.select2 !== 'undefined') {
            $('#access_users').select2({
                placeholder: '<?php esc_html_e('Select users', 'aqualuxe'); ?>',
                allowClear: true,
                ajax: {
                    url: ajaxurl,
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            action: 'aqualuxe_analytics_search_users',
                            term: params.term,
                            page: params.page || 1,
                            nonce: '<?php echo wp_create_nonce('aqualuxe_analytics_search_users'); ?>'
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;
                        
                        return {
                            results: data.data,
                            pagination: {
                                more: (params.page * 10) < data.total_count
                            }
                        };
                    },
                    cache: true
                },
                minimumInputLength: 2
            });
        }
    });
    </script>
    
    <style>
    .aqualuxe-access-container {
        margin-bottom: 20px;
    }
    
    .aqualuxe-access-roles {
        margin-bottom: 15px;
    }
    
    .aqualuxe-access-roles label {
        display: block;
        margin-bottom: 5px;
    }
    
    .select2-container {
        width: 100% !important;
    }
    </style>
    <?php
}

/**
 * Save analytics dashboard meta box data
 * 
 * @param int $post_id Post ID
 */
function aqualuxe_save_analytics_dashboard_meta_boxes($post_id) {
    // Check if our nonce is set
    if (!isset($_POST['aqualuxe_analytics_dashboard_settings_nonce'])) {
        return;
    }
    
    // Verify that the nonce is valid
    if (!wp_verify_nonce($_POST['aqualuxe_analytics_dashboard_settings_nonce'], 'aqualuxe_analytics_dashboard_settings')) {
        return;
    }
    
    // If this is an autosave, our form has not been submitted, so we don't want to do anything
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check the user's permissions
    if (isset($_POST['post_type']) && 'analytics_dashboard' === $_POST['post_type']) {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }
    
    // Save dashboard settings
    if (isset($_POST['default_date_range'])) {
        update_post_meta($post_id, '_default_date_range', sanitize_text_field($_POST['default_date_range']));
    }
    
    if (isset($_POST['refresh_interval'])) {
        update_post_meta($post_id, '_refresh_interval', sanitize_text_field($_POST['refresh_interval']));
    }
    
    if (isset($_POST['auto_refresh'])) {
        update_post_meta($post_id, '_auto_refresh', sanitize_text_field($_POST['auto_refresh']));
    }
    
    if (isset($_POST['show_filters'])) {
        update_post_meta($post_id, '_show_filters', sanitize_text_field($_POST['show_filters']));
    }
    
    if (isset($_POST['show_export'])) {
        update_post_meta($post_id, '_show_export', sanitize_text_field($_POST['show_export']));
    }
    
    // Save dashboard layout
    if (isset($_POST['layout_type'])) {
        update_post_meta($post_id, '_layout_type', sanitize_text_field($_POST['layout_type']));
    }
    
    if (isset($_POST['layout_columns'])) {
        update_post_meta($post_id, '_layout_columns', sanitize_text_field($_POST['layout_columns']));
    }
    
    if (isset($_POST['layout_sections'])) {
        $layout_sections = array();
        
        foreach ($_POST['layout_sections'] as $section_id => $section) {
            $layout_sections[$section_id] = array(
                'title' => sanitize_text_field($section['title']),
                'enabled' => isset($section['enabled']) ? true : false,
                'order' => intval($section['order']),
            );
        }
        
        update_post_meta($post_id, '_layout_sections', $layout_sections);
    }
    
    // Save dashboard widgets
    if (isset($_POST['widgets'])) {
        $widgets = array();
        
        foreach ($_POST['widgets'] as $widget_id => $widget) {
            $widgets[$widget_id] = array(
                'title' => sanitize_text_field($widget['title']),
                'type' => sanitize_text_field($widget['type']),
                'data_source' => sanitize_text_field($widget['data_source']),
                'enabled' => $widget['enabled'] === '1',
                'size' => sanitize_text_field($widget['size']),
                'position' => sanitize_text_field($widget['position']),
            );
        }
        
        update_post_meta($post_id, '_widgets', $widgets);
    }
    
    // Save dashboard access
    if (isset($_POST['access_roles'])) {
        $access_roles = array_map('sanitize_text_field', $_POST['access_roles']);
        update_post_meta($post_id, '_access_roles', $access_roles);
    } else {
        update_post_meta($post_id, '_access_roles', array());
    }
    
    if (isset($_POST['access_users'])) {
        $access_users = array_map('intval', $_POST['access_users']);
        update_post_meta($post_id, '_access_users', $access_users);
    } else {
        update_post_meta($post_id, '_access_users', array());
    }
}
add_action('save_post', 'aqualuxe_save_analytics_dashboard_meta_boxes');

/**
 * AJAX handler for searching users
 */
function aqualuxe_analytics_search_users_ajax() {
    // Check nonce
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe_analytics_search_users')) {
        wp_send_json_error('Invalid nonce');
    }
    
    // Check permissions
    if (!current_user_can('edit_posts')) {
        wp_send_json_error('Insufficient permissions');
    }
    
    $term = isset($_POST['term']) ? sanitize_text_field($_POST['term']) : '';
    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    
    // Search users
    $args = array(
        'search' => '*' . $term . '*',
        'search_columns' => array('user_login', 'user_email', 'display_name'),
        'number' => 10,
        'paged' => $page,
        'fields' => array('ID', 'user_login', 'user_email', 'display_name'),
    );
    
    $user_query = new WP_User_Query($args);
    $users = $user_query->get_results();
    
    $results = array();
    
    foreach ($users as $user) {
        $results[] = array(
            'id' => $user->ID,
            'text' => $user->display_name . ' (' . $user->user_email . ')',
        );
    }
    
    wp_send_json_success(array(
        'data' => $results,
        'total_count' => $user_query->get_total(),
    ));
}
add_action('wp_ajax_aqualuxe_analytics_search_users', 'aqualuxe_analytics_search_users_ajax');