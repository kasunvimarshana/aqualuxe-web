/**
 * AquaLuxe Analytics Dashboard
 *
 * Main JavaScript file for the analytics dashboard functionality.
 */

(function($) {
    'use strict';

    // Analytics Dashboard Class
    const AquaLuxeAnalyticsDashboard = {
        /**
         * Initialize the dashboard
         */
        init: function() {
            this.initDatePickers();
            this.initDatePresets();
            this.initCompareToggle();
            this.initExportButtons();
            this.initCustomizeModal();
            this.initEmailModal();
            this.loadDashboardData();
        },

        /**
         * Initialize date pickers
         */
        initDatePickers: function() {
            // Initialize date range picker if available
            if (typeof $.fn.daterangepicker !== 'undefined') {
                const dateFormat = aqualuxeAnalytics.dateFormat || 'YYYY-MM-DD';
                
                $('#start_date, #end_date, #compare_start_date, #compare_end_date').daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoApply: true,
                    locale: {
                        format: dateFormat
                    }
                });
            }
        },

        /**
         * Initialize date presets
         */
        initDatePresets: function() {
            $('.date-preset').on('click', function() {
                const days = $(this).data('days');
                const endDate = moment();
                const startDate = moment().subtract(days, 'days');
                
                $('#end_date').val(endDate.format('YYYY-MM-DD')).trigger('change');
                $('#start_date').val(startDate.format('YYYY-MM-DD')).trigger('change');
                
                // If compare is enabled, set compare dates
                if ($('#compare_toggle').is(':checked')) {
                    const compareEndDate = moment(startDate).subtract(1, 'days');
                    const compareStartDate = moment(compareEndDate).subtract(days, 'days');
                    
                    $('#compare_end_date').val(compareEndDate.format('YYYY-MM-DD')).trigger('change');
                    $('#compare_start_date').val(compareStartDate.format('YYYY-MM-DD')).trigger('change');
                }
            });
        },

        /**
         * Initialize compare toggle
         */
        initCompareToggle: function() {
            $('#compare_toggle').on('change', function() {
                if ($(this).is(':checked')) {
                    $('.aqualuxe-analytics-compare-dates').show();
                } else {
                    $('.aqualuxe-analytics-compare-dates').hide();
                }
            });
        },

        /**
         * Initialize export buttons
         */
        initExportButtons: function() {
            $('.aqualuxe-export-dashboard').on('click', function() {
                const exportType = $(this).data('type');
                
                if (exportType === 'email') {
                    $('#aqualuxe-email-report-modal').show();
                    return;
                }
                
                AquaLuxeAnalyticsDashboard.exportDashboard(exportType);
            });
        },

        /**
         * Initialize customize modal
         */
        initCustomizeModal: function() {
            // Open modal
            $('.aqualuxe-customize-dashboard').on('click', function() {
                $('#aqualuxe-customize-dashboard-modal').show();
            });
            
            // Close modal
            $('.aqualuxe-modal-close, .aqualuxe-modal-cancel').on('click', function() {
                $(this).closest('.aqualuxe-modal').hide();
            });
            
            // Save changes
            $('.aqualuxe-modal-save').on('click', function() {
                const form = $('#aqualuxe-dashboard-customize-form');
                const layout = form.find('input[name="dashboard_layout"]:checked').val();
                const visibleSections = form.find('input[name="visible_sections[]"]:checked').map(function() {
                    return $(this).val();
                }).get();
                
                const sectionOrder = $('#aqualuxe-section-order li').map(function() {
                    return $(this).data('section');
                }).get();
                
                AquaLuxeAnalyticsDashboard.saveDashboardLayout(layout, visibleSections, sectionOrder);
            });
            
            // Make sections sortable
            if (typeof $.fn.sortable !== 'undefined') {
                $('#aqualuxe-section-order').sortable({
                    placeholder: 'ui-state-highlight',
                    update: function(event, ui) {
                        // Update order when items are sorted
                    }
                });
            }
        },

        /**
         * Initialize email modal
         */
        initEmailModal: function() {
            // Send email report
            $('.aqualuxe-modal-send').on('click', function() {
                const form = $('#aqualuxe-email-report-form');
                const recipients = form.find('#email_recipients').val();
                const subject = form.find('#email_subject').val();
                const message = form.find('#email_message').val();
                const format = form.find('#email_format').val();
                
                AquaLuxeAnalyticsDashboard.sendEmailReport(recipients, subject, message, format);
            });
        },

        /**
         * Load dashboard data
         */
        loadDashboardData: function() {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();
            const compareStartDate = $('#compare_start_date').val();
            const compareEndDate = $('#compare_end_date').val();
            const compareToggle = $('#compare_toggle').is(':checked');
            
            // Show loading indicator
            $('.aqualuxe-analytics-loading').show();
            $('.aqualuxe-analytics-dashboard-content').hide();
            
            // Fetch dashboard data from API
            $.ajax({
                url: aqualuxeAnalytics.apiUrl + 'dashboard',
                method: 'GET',
                data: {
                    start_date: startDate,
                    end_date: endDate,
                    compare_start_date: compareToggle ? compareStartDate : null,
                    compare_end_date: compareToggle ? compareEndDate : null,
                },
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', aqualuxeAnalytics.nonce);
                },
                success: function(response) {
                    AquaLuxeAnalyticsDashboard.renderDashboard(response);
                },
                error: function(error) {
                    console.error('Error loading dashboard data:', error);
                    
                    // Hide loading indicator
                    $('.aqualuxe-analytics-loading').hide();
                    
                    // Show error message
                    $('.aqualuxe-analytics-dashboard-content').html(
                        '<div class="notice notice-error"><p>' + 
                        'Error loading dashboard data. Please try again.' + 
                        '</p></div>'
                    ).show();
                }
            });
        },

        /**
         * Render dashboard with data
         * 
         * @param {Object} data Dashboard data
         */
        renderDashboard: function(data) {
            // Hide loading indicator
            $('.aqualuxe-analytics-loading').hide();
            
            // Render KPIs
            this.renderKPIs(data.kpis);
            
            // Render sales chart
            this.renderSalesChart(data.sales_chart);
            
            // Render top products
            this.renderTopProducts(data.top_products);
            
            // Render top categories
            this.renderTopCategories(data.top_categories);
            
            // Render inventory summary
            this.renderInventorySummary(data.inventory_summary);
            
            // Render customer chart
            this.renderCustomerChart(data.customer_summary);
            
            // Render subscription chart
            this.renderSubscriptionChart(data.subscription_summary);
            
            // Render recent activity
            this.renderRecentActivity(data.recent_activity);
            
            // Show dashboard content
            $('.aqualuxe-analytics-dashboard-content').show();
        },

        /**
         * Render KPIs
         * 
         * @param {Object} kpis KPI data
         */
        renderKPIs: function(kpis) {
            const kpiContainer = $('.aqualuxe-analytics-kpi-grid');
            kpiContainer.empty();
            
            // Loop through KPIs and create cards
            $.each(kpis, function(key, kpi) {
                const changeClass = kpi.change >= 0 ? 'positive' : 'negative';
                const changeIcon = kpi.change >= 0 ? 'dashicons-arrow-up-alt' : 'dashicons-arrow-down-alt';
                const formattedValue = key.includes('revenue') || key === 'average_order' 
                    ? AquaLuxeAnalyticsDashboard.formatCurrency(kpi.value)
                    : AquaLuxeAnalyticsDashboard.formatNumber(kpi.value);
                
                const kpiCard = $('<div class="aqualuxe-analytics-kpi-card"></div>');
                
                kpiCard.append('<div class="kpi-icon"><span class="dashicons ' + kpi.icon + '"></span></div>');
                kpiCard.append('<div class="kpi-value">' + formattedValue + '</div>');
                kpiCard.append('<div class="kpi-label">' + kpi.label + '</div>');
                
                if (kpi.change !== 0) {
                    kpiCard.append(
                        '<div class="kpi-change ' + changeClass + '">' +
                        '<span class="dashicons ' + changeIcon + '"></span>' +
                        Math.abs(kpi.change).toFixed(1) + '%' +
                        '</div>'
                    );
                }
                
                kpiContainer.append(kpiCard);
            });
        },

        /**
         * Render sales chart
         * 
         * @param {Object} chartData Chart data
         */
        renderSalesChart: function(chartData) {
            const ctx = document.getElementById('sales-chart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (window.salesChart instanceof Chart) {
                window.salesChart.destroy();
            }
            
            // Create new chart
            window.salesChart = new Chart(ctx, {
                type: 'line',
                data: chartData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    label += AquaLuxeAnalyticsDashboard.formatCurrency(context.parsed.y);
                                    return label;
                                }
                            }
                        },
                        legend: {
                            position: 'bottom',
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return AquaLuxeAnalyticsDashboard.formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        },

        /**
         * Render top products
         * 
         * @param {Array} products Top products data
         */
        renderTopProducts: function(products) {
            const container = $('.aqualuxe-analytics-top-products');
            container.empty();
            
            if (products.length === 0) {
                container.append('<p>No product data available.</p>');
                return;
            }
            
            // Loop through products and create items
            $.each(products, function(index, product) {
                const productItem = $('<div class="aqualuxe-analytics-product-item"></div>');
                
                productItem.append(
                    '<div class="aqualuxe-analytics-product-image">' +
                    '<img src="' + (product.image || 'https://via.placeholder.com/40') + '" alt="' + product.name + '">' +
                    '</div>'
                );
                
                productItem.append(
                    '<div class="aqualuxe-analytics-product-info">' +
                    '<div class="aqualuxe-analytics-product-name">' + product.name + '</div>' +
                    '<div class="aqualuxe-analytics-product-stats">' +
                    'Sales: ' + AquaLuxeAnalyticsDashboard.formatCurrency(product.total) + ' / ' +
                    'Quantity: ' + product.count +
                    '</div>' +
                    '</div>'
                );
                
                container.append(productItem);
            });
        },

        /**
         * Render top categories
         * 
         * @param {Array} categories Top categories data
         */
        renderTopCategories: function(categories) {
            const container = $('.aqualuxe-analytics-top-categories');
            container.empty();
            
            if (categories.length === 0) {
                container.append('<p>No category data available.</p>');
                return;
            }
            
            // Loop through categories and create items
            $.each(categories, function(index, category) {
                const categoryItem = $('<div class="aqualuxe-analytics-category-item"></div>');
                
                categoryItem.append(
                    '<div class="aqualuxe-analytics-category-info">' +
                    '<div class="aqualuxe-analytics-category-name">' + category.name + '</div>' +
                    '<div class="aqualuxe-analytics-category-stats">' +
                    'Sales: ' + AquaLuxeAnalyticsDashboard.formatCurrency(category.total) + ' / ' +
                    'Products: ' + category.count +
                    '</div>' +
                    '</div>'
                );
                
                container.append(categoryItem);
            });
        },

        /**
         * Render inventory summary
         * 
         * @param {Object} inventory Inventory summary data
         */
        renderInventorySummary: function(inventory) {
            const stockStatus = $('.aqualuxe-analytics-stock-status');
            stockStatus.empty();
            
            // Create stock status items
            stockStatus.append(
                '<div class="aqualuxe-analytics-stock-item">' +
                '<div class="aqualuxe-analytics-stock-value">' + inventory.low_stock_count + '</div>' +
                '<div class="aqualuxe-analytics-stock-label">Low Stock</div>' +
                '</div>'
            );
            
            stockStatus.append(
                '<div class="aqualuxe-analytics-stock-item">' +
                '<div class="aqualuxe-analytics-stock-value">' + inventory.out_of_stock_count + '</div>' +
                '<div class="aqualuxe-analytics-stock-label">Out of Stock</div>' +
                '</div>'
            );
        },

        /**
         * Render customer chart
         * 
         * @param {Object} customerData Customer data
         */
        renderCustomerChart: function(customerData) {
            const ctx = document.getElementById('new-customers-chart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (window.customerChart instanceof Chart) {
                window.customerChart.destroy();
            }
            
            // Create simple bar chart showing new customers
            window.customerChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['New Customers'],
                    datasets: [{
                        label: 'Current Period',
                        data: [customerData.total_new_customers],
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
        },

        /**
         * Render subscription chart
         * 
         * @param {Object} subscriptionData Subscription data
         */
        renderSubscriptionChart: function(subscriptionData) {
            const ctx = document.getElementById('subscription-chart').getContext('2d');
            
            // Destroy existing chart if it exists
            if (window.subscriptionChart instanceof Chart) {
                window.subscriptionChart.destroy();
            }
            
            // Create simple bar chart showing subscription metrics
            window.subscriptionChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['New', 'Renewals', 'Cancellations'],
                    datasets: [{
                        data: [
                            subscriptionData.total_new_subscriptions,
                            subscriptionData.total_renewals,
                            subscriptionData.total_cancellations
                        ],
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(255, 99, 132, 0.2)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(255, 99, 132, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                }
            });
            
            // Render subscription metrics
            const metricsContainer = $('.aqualuxe-analytics-subscription-metrics');
            metricsContainer.empty();
            
            metricsContainer.append(
                '<div class="aqualuxe-analytics-subscription-metric">' +
                '<div class="aqualuxe-analytics-subscription-value">' + 
                AquaLuxeAnalyticsDashboard.formatCurrency(subscriptionData.total_renewal_revenue) + 
                '</div>' +
                '<div class="aqualuxe-analytics-subscription-label">Renewal Revenue</div>' +
                '</div>'
            );
            
            metricsContainer.append(
                '<div class="aqualuxe-analytics-subscription-metric">' +
                '<div class="aqualuxe-analytics-subscription-value">' + 
                AquaLuxeAnalyticsDashboard.formatCurrency(subscriptionData.average_renewal_value) + 
                '</div>' +
                '<div class="aqualuxe-analytics-subscription-label">Avg. Renewal Value</div>' +
                '</div>'
            );
        },

        /**
         * Render recent activity
         * 
         * @param {Array} activity Recent activity data
         */
        renderRecentActivity: function(activity) {
            const container = $('.aqualuxe-analytics-activity-list');
            container.empty();
            
            if (activity.length === 0) {
                container.append('<p>No recent activity.</p>');
                return;
            }
            
            // Loop through activity items and create elements
            $.each(activity, function(index, item) {
                const activityItem = $('<div class="aqualuxe-analytics-activity-item"></div>');
                
                activityItem.append(
                    '<div class="aqualuxe-analytics-activity-icon">' +
                    '<span class="dashicons ' + (item.icon || 'dashicons-admin-generic') + '"></span>' +
                    '</div>'
                );
                
                const timeAgo = AquaLuxeAnalyticsDashboard.timeAgo(item.timestamp);
                
                activityItem.append(
                    '<div class="aqualuxe-analytics-activity-content">' +
                    '<div class="aqualuxe-analytics-activity-title">' + item.title + '</div>' +
                    '<div class="aqualuxe-analytics-activity-description">' + item.description + '</div>' +
                    '<div class="aqualuxe-analytics-activity-time">' + timeAgo + '</div>' +
                    '</div>'
                );
                
                container.append(activityItem);
            });
        },

        /**
         * Save dashboard layout
         * 
         * @param {string} layout Layout type
         * @param {Array} visibleSections Visible sections
         * @param {Array} sectionOrder Section order
         */
        saveDashboardLayout: function(layout, visibleSections, sectionOrder) {
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'aqualuxe_analytics_save_dashboard_layout',
                    layout: layout,
                    visible_sections: visibleSections,
                    section_order: sectionOrder,
                    nonce: aqualuxeAnalytics.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Close modal
                        $('#aqualuxe-customize-dashboard-modal').hide();
                        
                        // Reload page to apply changes
                        window.location.reload();
                    } else {
                        alert('Error saving dashboard layout: ' + response.data);
                    }
                },
                error: function() {
                    alert('Error saving dashboard layout. Please try again.');
                }
            });
        },

        /**
         * Export dashboard
         * 
         * @param {string} format Export format (pdf, csv)
         */
        exportDashboard: function(format) {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();
            
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'aqualuxe_analytics_export_report',
                    report_type: 'dashboard',
                    format: format,
                    start_date: startDate,
                    end_date: endDate,
                    nonce: aqualuxeAnalytics.nonce
                },
                success: function(response) {
                    if (response.success && response.data.download_url) {
                        // Download the file
                        window.location.href = response.data.download_url;
                    } else {
                        alert('Error exporting dashboard: ' + (response.data || 'Unknown error'));
                    }
                },
                error: function() {
                    alert('Error exporting dashboard. Please try again.');
                }
            });
        },

        /**
         * Send email report
         * 
         * @param {string} recipients Email recipients
         * @param {string} subject Email subject
         * @param {string} message Email message
         * @param {string} format Report format
         */
        sendEmailReport: function(recipients, subject, message, format) {
            const startDate = $('#start_date').val();
            const endDate = $('#end_date').val();
            
            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'aqualuxe_analytics_email_report',
                    report_type: 'dashboard',
                    recipients: recipients,
                    subject: subject,
                    message: message,
                    format: format,
                    start_date: startDate,
                    end_date: endDate,
                    nonce: aqualuxeAnalytics.nonce
                },
                success: function(response) {
                    if (response.success) {
                        // Close modal
                        $('#aqualuxe-email-report-modal').hide();
                        
                        // Show success message
                        alert('Report sent successfully!');
                    } else {
                        alert('Error sending report: ' + (response.data || 'Unknown error'));
                    }
                },
                error: function() {
                    alert('Error sending report. Please try again.');
                }
            });
        },

        /**
         * Format currency
         * 
         * @param {number} value Value to format
         * @return {string} Formatted currency
         */
        formatCurrency: function(value) {
            const currencySymbol = aqualuxeAnalytics.currency || '$';
            return currencySymbol + parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        },

        /**
         * Format number
         * 
         * @param {number} value Value to format
         * @return {string} Formatted number
         */
        formatNumber: function(value) {
            return parseFloat(value).toLocaleString();
        },

        /**
         * Calculate time ago
         * 
         * @param {number} timestamp Unix timestamp
         * @return {string} Time ago string
         */
        timeAgo: function(timestamp) {
            const now = Math.floor(Date.now() / 1000);
            const diff = now - timestamp;
            
            if (diff < 60) {
                return 'Just now';
            } else if (diff < 3600) {
                const minutes = Math.floor(diff / 60);
                return minutes + ' minute' + (minutes > 1 ? 's' : '') + ' ago';
            } else if (diff < 86400) {
                const hours = Math.floor(diff / 3600);
                return hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
            } else if (diff < 2592000) {
                const days = Math.floor(diff / 86400);
                return days + ' day' + (days > 1 ? 's' : '') + ' ago';
            } else if (diff < 31536000) {
                const months = Math.floor(diff / 2592000);
                return months + ' month' + (months > 1 ? 's' : '') + ' ago';
            } else {
                const years = Math.floor(diff / 31536000);
                return years + ' year' + (years > 1 ? 's' : '') + ' ago';
            }
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeAnalyticsDashboard.init();
    });

})(jQuery);