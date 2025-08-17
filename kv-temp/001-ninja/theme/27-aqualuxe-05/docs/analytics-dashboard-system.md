# AquaLuxe Analytics Dashboard System

## Overview

The Analytics Dashboard System is a comprehensive solution for tracking and visualizing business metrics for the AquaLuxe WordPress theme. It provides real-time insights into sales, inventory, customers, and subscriptions, allowing business owners to make data-driven decisions.

## Features

### Dashboard Overview

1. **Key Performance Indicators (KPIs)**
   - Revenue tracking with period-over-period comparison
   - Order count and average order value
   - New customer acquisition metrics
   - Subscription growth and revenue

2. **Sales Analytics**
   - Revenue trends over time (daily, weekly, monthly)
   - Top-selling products and categories
   - Sales by time period and comparison
   - Order status distribution

3. **Inventory Management**
   - Stock level monitoring
   - Low stock alerts
   - Product turnover rates
   - Inventory movement tracking

4. **Customer Analytics**
   - Customer growth trends
   - Customer lifetime value
   - Purchase frequency
   - Customer segmentation

5. **Subscription Analytics**
   - Subscription growth and churn
   - Recurring revenue tracking
   - Renewal rates
   - Popular subscription products

6. **Activity Monitoring**
   - Recent orders and sales
   - Inventory changes
   - Customer registrations
   - Subscription events

### Dashboard Customization

1. **Layout Options**
   - Grid layout with adjustable columns
   - Column-based layout for vertical organization
   - Tab-based layout for focused viewing

2. **Widget System**
   - Drag-and-drop widget placement
   - Resizable widgets (small, medium, large, full-width)
   - Multiple chart types (line, bar, pie, doughnut, radar, etc.)
   - Data tables and lists

3. **Filtering and Date Ranges**
   - Flexible date range selection
   - Period comparison (vs. previous period)
   - Product, category, and customer filters
   - Status-based filtering

4. **Export and Sharing**
   - PDF report generation
   - CSV data export
   - Email report scheduling
   - Shareable dashboard links

### User Permissions

1. **Role-Based Access**
   - Administrator: Full access to all analytics
   - Manager: Access to sales and inventory data
   - Staff: Limited access to specific reports

2. **Custom Dashboards by Role**
   - Create role-specific dashboards
   - Limit data visibility based on permissions
   - Customize metrics shown to different user types

## Technical Implementation

### Data Collection

The Analytics Dashboard System collects data from various sources:

1. **WooCommerce Integration**
   - Orders and products
   - Customer information
   - Payment and refund data

2. **Custom Post Types**
   - Subscription data
   - Care guide usage
   - Auction and trade-in activity

3. **User Activity**
   - Page views and engagement
   - Search queries
   - Feature usage

### Data Processing

Data is processed and aggregated for efficient retrieval:

1. **Real-time Processing**
   - Order status changes
   - Inventory updates
   - Subscription events

2. **Scheduled Aggregation**
   - Daily, weekly, and monthly summaries
   - Period-over-period comparisons
   - Trend calculations

3. **Data Storage**
   - Custom database tables for analytics data
   - Efficient indexing for fast queries
   - Data retention policies

### Visualization

The system uses modern visualization techniques:

1. **Chart.js Integration**
   - Responsive and interactive charts
   - Multiple chart types
   - Custom styling and theming

2. **Data Tables**
   - Sortable and filterable tables
   - Pagination for large datasets
   - Export functionality

3. **KPI Cards**
   - Visual indicators for performance
   - Trend arrows and color coding
   - Period comparison

## File Structure

```
aqualuxe/
├── includes/
│   ├── analytics/
│   │   ├── class-aqualuxe-analytics.php
│   │   ├── class-aqualuxe-analytics-data.php
│   │   ├── class-aqualuxe-analytics-reports.php
│   │   ├── class-aqualuxe-analytics-dashboard.php
│   │   └── class-aqualuxe-analytics-admin.php
│   └── post-types/
│       └── analytics-dashboard.php
├── assets/
│   ├── js/
│   │   ├── analytics/
│   │   │   ├── analytics-dashboard.js
│   │   │   ├── analytics-charts.js
│   │   │   └── analytics-filters.js
│   └── css/
│       └── analytics/
│           └── analytics-dashboard.css
├── templates/
│   └── analytics/
│       ├── dashboard.php
│       ├── sales-report.php
│       ├── inventory-report.php
│       ├── customer-report.php
│       └── subscription-report.php
└── docs/
    └── analytics-dashboard-system.md
```

## Database Schema

### Analytics Data Table

```sql
CREATE TABLE {$wpdb->prefix}aqualuxe_analytics_data (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  date_created datetime NOT NULL,
  data_type varchar(50) NOT NULL,
  data_key varchar(100) NOT NULL,
  data_value decimal(19,4) NOT NULL DEFAULT 0,
  data_count int(11) NOT NULL DEFAULT 0,
  data_meta longtext,
  PRIMARY KEY (id),
  KEY date_created (date_created),
  KEY data_type (data_type),
  KEY data_key (data_key)
) {$charset_collate};
```

### Analytics Settings Table

```sql
CREATE TABLE {$wpdb->prefix}aqualuxe_analytics_settings (
  id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  user_id bigint(20) unsigned NOT NULL,
  setting_key varchar(100) NOT NULL,
  setting_value longtext,
  PRIMARY KEY (id),
  KEY user_id (user_id),
  KEY setting_key (setting_key)
) {$charset_collate};
```

## API Endpoints

The Analytics Dashboard System provides REST API endpoints for retrieving data:

### Dashboard Data

- `GET /wp-json/aqualuxe/v1/analytics/dashboard`
  - Returns overview data for the main dashboard

### Sales Data

- `GET /wp-json/aqualuxe/v1/analytics/sales`
  - Parameters: start_date, end_date, compare_start_date, compare_end_date, product_ids, category_ids

### Inventory Data

- `GET /wp-json/aqualuxe/v1/analytics/inventory`
  - Parameters: start_date, end_date, product_ids, category_ids, stock_status

### Customer Data

- `GET /wp-json/aqualuxe/v1/analytics/customers`
  - Parameters: start_date, end_date, segment, status

### Subscription Data

- `GET /wp-json/aqualuxe/v1/analytics/subscriptions`
  - Parameters: start_date, end_date, status, product_ids

## Usage

### Accessing the Dashboard

1. Log in to the WordPress admin area
2. Navigate to "Analytics" in the main menu
3. Select "Dashboard" to view the main analytics dashboard

### Creating Custom Dashboards

1. Navigate to "Analytics" > "All Dashboards"
2. Click "Add New" to create a new dashboard
3. Configure dashboard settings:
   - Set title and description
   - Choose layout type and columns
   - Select visible sections
   - Configure widgets
   - Set access permissions
4. Click "Publish" to save the dashboard

### Customizing Widgets

1. In the dashboard edit screen, locate the "Dashboard Widgets" meta box
2. Click "Add Widget" to add a new widget
3. Configure widget settings:
   - Set title
   - Choose widget type (chart, list, stats, etc.)
   - Select data source
   - Set size and position
   - Enable/disable the widget
4. Click "Save Changes" to update the widget

### Filtering Data

1. Use the date range selector to choose a time period
2. Toggle "Compare to" to enable period comparison
3. Use product, category, and status filters to refine data
4. Click "Apply" to update the dashboard with filtered data

### Exporting Reports

1. Click "Export PDF" to generate a PDF report
2. Click "Export CSV" to download data in CSV format
3. Click "Email Report" to send the report via email

## Hooks and Filters

### Actions

- `aqualuxe_analytics_data_collected`: Fired after analytics data is collected
- `aqualuxe_analytics_dashboard_init`: Fired when the dashboard is initialized
- `aqualuxe_analytics_report_generated`: Fired after a report is generated
- `aqualuxe_analytics_widget_rendered`: Fired after a widget is rendered

### Filters

- `aqualuxe_analytics_data_types`: Filter available data types
- `aqualuxe_analytics_widget_types`: Filter available widget types
- `aqualuxe_analytics_date_ranges`: Filter available date ranges
- `aqualuxe_analytics_dashboard_sections`: Filter dashboard sections
- `aqualuxe_analytics_report_data`: Filter report data before rendering

## Extending the System

### Adding Custom Widgets

```php
// Register custom widget type
add_filter('aqualuxe_analytics_widget_types', 'add_custom_widget_type');
function add_custom_widget_type($widget_types) {
    $widget_types['custom_widget'] = array(
        'name' => __('Custom Widget', 'aqualuxe'),
        'description' => __('A custom widget type', 'aqualuxe'),
        'icon' => 'dashicons-chart-area',
        'callback' => 'render_custom_widget',
    );
    
    return $widget_types;
}

// Render custom widget
function render_custom_widget($widget, $data) {
    // Widget rendering code
    echo '<div class="custom-widget">';
    echo '<h3>' . esc_html($widget['title']) . '</h3>';
    // Render widget content
    echo '</div>';
}
```

### Adding Custom Data Sources

```php
// Register custom data source
add_filter('aqualuxe_analytics_data_sources', 'add_custom_data_source');
function add_custom_data_source($data_sources) {
    $data_sources['custom_data'] = array(
        'name' => __('Custom Data', 'aqualuxe'),
        'description' => __('Custom data source', 'aqualuxe'),
        'callback' => 'get_custom_data',
    );
    
    return $data_sources;
}

// Get custom data
function get_custom_data($params) {
    // Data retrieval code
    $data = array(
        // Your custom data
    );
    
    return $data;
}
```

### Creating Custom Reports

```php
// Register custom report
add_filter('aqualuxe_analytics_reports', 'add_custom_report');
function add_custom_report($reports) {
    $reports['custom_report'] = array(
        'name' => __('Custom Report', 'aqualuxe'),
        'description' => __('A custom analytics report', 'aqualuxe'),
        'icon' => 'dashicons-chart-line',
        'callback' => 'render_custom_report',
        'capability' => 'manage_options',
    );
    
    return $reports;
}

// Render custom report
function render_custom_report() {
    // Report rendering code
    echo '<div class="custom-report">';
    echo '<h2>' . __('Custom Report', 'aqualuxe') . '</h2>';
    // Render report content
    echo '</div>';
}
```

## Conclusion

The AquaLuxe Analytics Dashboard System provides a powerful and flexible solution for tracking and visualizing business metrics. With its customizable dashboards, comprehensive data collection, and intuitive interface, it enables business owners to gain valuable insights into their operations and make data-driven decisions.

The system is designed to be extensible, allowing developers to add custom widgets, data sources, and reports to meet specific business needs. Its integration with WooCommerce and other AquaLuxe features ensures a seamless experience for users.