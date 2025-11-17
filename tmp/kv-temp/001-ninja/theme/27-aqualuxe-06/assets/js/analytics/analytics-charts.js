/**
 * AquaLuxe Analytics Charts
 *
 * JavaScript file for handling chart creation and manipulation in the analytics dashboard.
 */

(function($) {
    'use strict';

    // Analytics Charts Class
    const AquaLuxeAnalyticsCharts = {
        /**
         * Initialize charts
         */
        init: function() {
            this.setupChartDefaults();
        },

        /**
         * Set up Chart.js defaults
         */
        setupChartDefaults: function() {
            if (typeof Chart === 'undefined') {
                return;
            }

            // Set default font family
            Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif';
            
            // Set default font size
            Chart.defaults.font.size = 12;
            
            // Set default colors
            Chart.defaults.color = '#646970';
            
            // Set default tooltip settings
            Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
            Chart.defaults.plugins.tooltip.titleColor = '#fff';
            Chart.defaults.plugins.tooltip.bodyColor = '#fff';
            Chart.defaults.plugins.tooltip.borderColor = 'rgba(0, 0, 0, 0.1)';
            Chart.defaults.plugins.tooltip.borderWidth = 1;
            Chart.defaults.plugins.tooltip.padding = 10;
            Chart.defaults.plugins.tooltip.cornerRadius = 4;
            
            // Set default legend settings
            Chart.defaults.plugins.legend.labels.usePointStyle = true;
            Chart.defaults.plugins.legend.labels.padding = 15;
            
            // Set default animation settings
            Chart.defaults.animation.duration = 1000;
            Chart.defaults.animation.easing = 'easeOutQuart';
            
            // Register custom plugins
            this.registerPlugins();
        },

        /**
         * Register custom Chart.js plugins
         */
        registerPlugins: function() {
            // Plugin to add a background color to the chart
            const backgroundPlugin = {
                id: 'chartBackground',
                beforeDraw: function(chart) {
                    if (chart.config.options.chartArea && chart.config.options.chartArea.backgroundColor) {
                        const ctx = chart.ctx;
                        const chartArea = chart.chartArea;
                        
                        ctx.save();
                        ctx.fillStyle = chart.config.options.chartArea.backgroundColor;
                        ctx.fillRect(chartArea.left, chartArea.top, chartArea.right - chartArea.left, chartArea.bottom - chartArea.top);
                        ctx.restore();
                    }
                }
            };
            
            // Plugin to add a custom label to the center of doughnut/pie charts
            const centerLabelPlugin = {
                id: 'centerLabel',
                afterDraw: function(chart) {
                    if (chart.config.type === 'doughnut' || chart.config.type === 'pie') {
                        if (chart.config.options.centerLabel) {
                            const ctx = chart.ctx;
                            const centerX = (chart.chartArea.left + chart.chartArea.right) / 2;
                            const centerY = (chart.chartArea.top + chart.chartArea.bottom) / 2;
                            
                            ctx.save();
                            ctx.textAlign = 'center';
                            ctx.textBaseline = 'middle';
                            
                            // Draw main label
                            if (chart.config.options.centerLabel.text) {
                                ctx.font = chart.config.options.centerLabel.fontStyle + ' ' + 
                                           chart.config.options.centerLabel.fontSize + 'px ' + 
                                           chart.config.options.centerLabel.fontFamily;
                                ctx.fillStyle = chart.config.options.centerLabel.color;
                                ctx.fillText(chart.config.options.centerLabel.text, centerX, centerY);
                            }
                            
                            // Draw sub label
                            if (chart.config.options.centerLabel.subText) {
                                ctx.font = chart.config.options.centerLabel.subFontStyle + ' ' + 
                                           chart.config.options.centerLabel.subFontSize + 'px ' + 
                                           chart.config.options.centerLabel.fontFamily;
                                ctx.fillStyle = chart.config.options.centerLabel.subColor;
                                ctx.fillText(chart.config.options.centerLabel.subText, centerX, centerY + parseInt(chart.config.options.centerLabel.fontSize) + 5);
                            }
                            
                            ctx.restore();
                        }
                    }
                }
            };
            
            // Register plugins
            Chart.register(backgroundPlugin);
            Chart.register(centerLabelPlugin);
        },

        /**
         * Create a line chart
         * 
         * @param {string} elementId Element ID
         * @param {Object} data Chart data
         * @param {Object} options Chart options
         * @return {Chart} Chart instance
         */
        createLineChart: function(elementId, data, options) {
            const ctx = document.getElementById(elementId).getContext('2d');
            
            // Destroy existing chart if it exists
            if (window[elementId + 'Chart'] instanceof Chart) {
                window[elementId + 'Chart'].destroy();
            }
            
            // Default options
            const defaultOptions = {
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
                                if (context.parsed.y !== null) {
                                    label += AquaLuxeAnalyticsCharts.formatValue(context.parsed.y, options.valueFormat);
                                }
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
                                return AquaLuxeAnalyticsCharts.formatValue(value, options.valueFormat);
                            }
                        }
                    }
                }
            };
            
            // Merge options
            const chartOptions = $.extend(true, {}, defaultOptions, options);
            
            // Create chart
            window[elementId + 'Chart'] = new Chart(ctx, {
                type: 'line',
                data: data,
                options: chartOptions
            });
            
            return window[elementId + 'Chart'];
        },

        /**
         * Create a bar chart
         * 
         * @param {string} elementId Element ID
         * @param {Object} data Chart data
         * @param {Object} options Chart options
         * @return {Chart} Chart instance
         */
        createBarChart: function(elementId, data, options) {
            const ctx = document.getElementById(elementId).getContext('2d');
            
            // Destroy existing chart if it exists
            if (window[elementId + 'Chart'] instanceof Chart) {
                window[elementId + 'Chart'].destroy();
            }
            
            // Default options
            const defaultOptions = {
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
                                if (context.parsed.y !== null) {
                                    label += AquaLuxeAnalyticsCharts.formatValue(context.parsed.y, options.valueFormat);
                                }
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
                                return AquaLuxeAnalyticsCharts.formatValue(value, options.valueFormat);
                            }
                        }
                    }
                }
            };
            
            // Merge options
            const chartOptions = $.extend(true, {}, defaultOptions, options);
            
            // Create chart
            window[elementId + 'Chart'] = new Chart(ctx, {
                type: 'bar',
                data: data,
                options: chartOptions
            });
            
            return window[elementId + 'Chart'];
        },

        /**
         * Create a doughnut chart
         * 
         * @param {string} elementId Element ID
         * @param {Object} data Chart data
         * @param {Object} options Chart options
         * @return {Chart} Chart instance
         */
        createDoughnutChart: function(elementId, data, options) {
            const ctx = document.getElementById(elementId).getContext('2d');
            
            // Destroy existing chart if it exists
            if (window[elementId + 'Chart'] instanceof Chart) {
                window[elementId + 'Chart'].destroy();
            }
            
            // Default options
            const defaultOptions = {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += AquaLuxeAnalyticsCharts.formatValue(context.parsed, options.valueFormat);
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                    }
                }
            };
            
            // Merge options
            const chartOptions = $.extend(true, {}, defaultOptions, options);
            
            // Create chart
            window[elementId + 'Chart'] = new Chart(ctx, {
                type: 'doughnut',
                data: data,
                options: chartOptions
            });
            
            return window[elementId + 'Chart'];
        },

        /**
         * Create a pie chart
         * 
         * @param {string} elementId Element ID
         * @param {Object} data Chart data
         * @param {Object} options Chart options
         * @return {Chart} Chart instance
         */
        createPieChart: function(elementId, data, options) {
            const ctx = document.getElementById(elementId).getContext('2d');
            
            // Destroy existing chart if it exists
            if (window[elementId + 'Chart'] instanceof Chart) {
                window[elementId + 'Chart'].destroy();
            }
            
            // Default options
            const defaultOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += AquaLuxeAnalyticsCharts.formatValue(context.parsed, options.valueFormat);
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                    }
                }
            };
            
            // Merge options
            const chartOptions = $.extend(true, {}, defaultOptions, options);
            
            // Create chart
            window[elementId + 'Chart'] = new Chart(ctx, {
                type: 'pie',
                data: data,
                options: chartOptions
            });
            
            return window[elementId + 'Chart'];
        },

        /**
         * Create a radar chart
         * 
         * @param {string} elementId Element ID
         * @param {Object} data Chart data
         * @param {Object} options Chart options
         * @return {Chart} Chart instance
         */
        createRadarChart: function(elementId, data, options) {
            const ctx = document.getElementById(elementId).getContext('2d');
            
            // Destroy existing chart if it exists
            if (window[elementId + 'Chart'] instanceof Chart) {
                window[elementId + 'Chart'].destroy();
            }
            
            // Default options
            const defaultOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed !== null) {
                                    label += AquaLuxeAnalyticsCharts.formatValue(context.parsed, options.valueFormat);
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return AquaLuxeAnalyticsCharts.formatValue(value, options.valueFormat);
                            }
                        }
                    }
                }
            };
            
            // Merge options
            const chartOptions = $.extend(true, {}, defaultOptions, options);
            
            // Create chart
            window[elementId + 'Chart'] = new Chart(ctx, {
                type: 'radar',
                data: data,
                options: chartOptions
            });
            
            return window[elementId + 'Chart'];
        },

        /**
         * Create a polar area chart
         * 
         * @param {string} elementId Element ID
         * @param {Object} data Chart data
         * @param {Object} options Chart options
         * @return {Chart} Chart instance
         */
        createPolarAreaChart: function(elementId, data, options) {
            const ctx = document.getElementById(elementId).getContext('2d');
            
            // Destroy existing chart if it exists
            if (window[elementId + 'Chart'] instanceof Chart) {
                window[elementId + 'Chart'].destroy();
            }
            
            // Default options
            const defaultOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.r !== null) {
                                    label += AquaLuxeAnalyticsCharts.formatValue(context.parsed.r, options.valueFormat);
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                    }
                },
                scales: {
                    r: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return AquaLuxeAnalyticsCharts.formatValue(value, options.valueFormat);
                            }
                        }
                    }
                }
            };
            
            // Merge options
            const chartOptions = $.extend(true, {}, defaultOptions, options);
            
            // Create chart
            window[elementId + 'Chart'] = new Chart(ctx, {
                type: 'polarArea',
                data: data,
                options: chartOptions
            });
            
            return window[elementId + 'Chart'];
        },

        /**
         * Create a bubble chart
         * 
         * @param {string} elementId Element ID
         * @param {Object} data Chart data
         * @param {Object} options Chart options
         * @return {Chart} Chart instance
         */
        createBubbleChart: function(elementId, data, options) {
            const ctx = document.getElementById(elementId).getContext('2d');
            
            // Destroy existing chart if it exists
            if (window[elementId + 'Chart'] instanceof Chart) {
                window[elementId + 'Chart'].destroy();
            }
            
            // Default options
            const defaultOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.x !== null && context.parsed.y !== null) {
                                    label += '(' + AquaLuxeAnalyticsCharts.formatValue(context.parsed.x, options.xFormat) + ', ' + 
                                             AquaLuxeAnalyticsCharts.formatValue(context.parsed.y, options.yFormat) + ', ' + 
                                             AquaLuxeAnalyticsCharts.formatValue(context.parsed.r, options.rFormat) + ')';
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        position: 'bottom',
                    }
                },
                scales: {
                    x: {
                        ticks: {
                            callback: function(value) {
                                return AquaLuxeAnalyticsCharts.formatValue(value, options.xFormat);
                            }
                        }
                    },
                    y: {
                        ticks: {
                            callback: function(value) {
                                return AquaLuxeAnalyticsCharts.formatValue(value, options.yFormat);
                            }
                        }
                    }
                }
            };
            
            // Merge options
            const chartOptions = $.extend(true, {}, defaultOptions, options);
            
            // Create chart
            window[elementId + 'Chart'] = new Chart(ctx, {
                type: 'bubble',
                data: data,
                options: chartOptions
            });
            
            return window[elementId + 'Chart'];
        },

        /**
         * Format value based on format type
         * 
         * @param {number} value Value to format
         * @param {string} format Format type (currency, number, percent, etc.)
         * @return {string} Formatted value
         */
        formatValue: function(value, format) {
            if (format === undefined || value === undefined || value === null) {
                return value;
            }
            
            switch (format) {
                case 'currency':
                    const currencySymbol = aqualuxeAnalytics.currency || '$';
                    return currencySymbol + parseFloat(value).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
                
                case 'number':
                    return parseFloat(value).toLocaleString();
                
                case 'percent':
                    return parseFloat(value).toFixed(1) + '%';
                
                case 'integer':
                    return Math.round(parseFloat(value)).toLocaleString();
                
                case 'decimal':
                    return parseFloat(value).toFixed(2);
                
                default:
                    return value;
            }
        },

        /**
         * Generate random colors
         * 
         * @param {number} count Number of colors to generate
         * @param {number} opacity Color opacity (0-1)
         * @return {Array} Array of color strings
         */
        generateColors: function(count, opacity) {
            const colors = [];
            const backgroundColors = [];
            const borderColors = [];
            
            opacity = opacity || 0.2;
            
            // Predefined colors for first few items
            const baseColors = [
                'rgb(54, 162, 235)', // Blue
                'rgb(255, 99, 132)', // Red
                'rgb(75, 192, 192)', // Green
                'rgb(255, 205, 86)', // Yellow
                'rgb(153, 102, 255)', // Purple
                'rgb(255, 159, 64)', // Orange
                'rgb(201, 203, 207)' // Grey
            ];
            
            for (let i = 0; i < count; i++) {
                let color;
                
                if (i < baseColors.length) {
                    color = baseColors[i];
                } else {
                    // Generate random color
                    const r = Math.floor(Math.random() * 255);
                    const g = Math.floor(Math.random() * 255);
                    const b = Math.floor(Math.random() * 255);
                    color = 'rgb(' + r + ', ' + g + ', ' + b + ')';
                }
                
                colors.push(color);
                backgroundColors.push(color.replace('rgb', 'rgba').replace(')', ', ' + opacity + ')'));
                borderColors.push(color);
            }
            
            return {
                colors: colors,
                backgroundColors: backgroundColors,
                borderColors: borderColors
            };
        }
    };

    // Initialize on document ready
    $(document).ready(function() {
        AquaLuxeAnalyticsCharts.init();
    });

    // Make available globally
    window.AquaLuxeAnalyticsCharts = AquaLuxeAnalyticsCharts;

})(jQuery);