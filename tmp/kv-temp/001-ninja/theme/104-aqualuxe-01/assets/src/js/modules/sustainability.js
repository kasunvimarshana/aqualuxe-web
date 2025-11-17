/**
 * Sustainability Module JavaScript
 * 
 * Handles sustainability tracking and R&D functionality
 */

(function($) {
    'use strict';

    const SustainabilityModule = {
        
        /**
         * Initialize module
         */
        init() {
            this.bindEvents();
            this.initCharts();
            this.initProgressBars();
            this.startCounters();
        },

        /**
         * Bind events
         */
        bindEvents() {
            // Impact calculator
            $(document).on('input', '.impact-calculator input', this.calculateImpact.bind(this));
            
            // Research project filters
            $(document).on('change', '.research-filters select', this.filterProjects.bind(this));
            
            // Sustainability report filters
            $(document).on('change', '.report-filters input', this.filterReports.bind(this));
            
            // Carbon footprint tracker
            $(document).on('submit', '.carbon-tracker-form', this.trackCarbonFootprint.bind(this));
            
            // Initiative progress update
            $(document).on('click', '.update-progress-btn', this.updateInitiativeProgress.bind(this));
        },

        /**
         * Initialize charts
         */
        initCharts() {
            this.initCarbonFootprintChart();
            this.initWaterUsageChart();
            this.initWasteReductionChart();
            this.initImpactTrendChart();
        },

        /**
         * Initialize carbon footprint chart
         */
        initCarbonFootprintChart() {
            const $chart = $('#carbon-footprint-chart');
            if ($chart.length === 0) return;

            // Mock data - in real implementation, this would come from the backend
            const data = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'CO2 Emissions (kg)',
                    data: [1200, 1150, 1100, 1050, 980, 920],
                    borderColor: 'rgb(75, 192, 192)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    tension: 0.4
                }]
            };

            this.createLineChart($chart[0], data);
        },

        /**
         * Initialize water usage chart
         */
        initWaterUsageChart() {
            const $chart = $('#water-usage-chart');
            if ($chart.length === 0) return;

            const data = {
                labels: ['Breeding', 'Cleaning', 'Display', 'Research'],
                datasets: [{
                    data: [40, 25, 20, 15],
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.8)',
                        'rgba(255, 99, 132, 0.8)',
                        'rgba(255, 205, 86, 0.8)',
                        'rgba(75, 192, 192, 0.8)'
                    ]
                }]
            };

            this.createDoughnutChart($chart[0], data);
        },

        /**
         * Initialize waste reduction chart
         */
        initWasteReductionChart() {
            const $chart = $('#waste-reduction-chart');
            if ($chart.length === 0) return;

            const data = {
                labels: ['Q1', 'Q2', 'Q3', 'Q4'],
                datasets: [{
                    label: 'Waste Reduction %',
                    data: [15, 23, 35, 42],
                    backgroundColor: 'rgba(75, 192, 192, 0.8)',
                    borderColor: 'rgb(75, 192, 192)',
                    borderWidth: 2
                }]
            };

            this.createBarChart($chart[0], data);
        },

        /**
         * Initialize impact trend chart
         */
        initImpactTrendChart() {
            const $chart = $('#impact-trend-chart');
            if ($chart.length === 0) return;

            const data = {
                labels: ['2020', '2021', '2022', '2023', '2024'],
                datasets: [
                    {
                        label: 'Carbon Reduction %',
                        data: [5, 12, 18, 25, 35],
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    },
                    {
                        label: 'Water Conservation %',
                        data: [8, 15, 22, 28, 40],
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    },
                    {
                        label: 'Waste Reduction %',
                        data: [10, 18, 26, 35, 45],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    }
                ]
            };

            this.createLineChart($chart[0], data);
        },

        /**
         * Create line chart
         */
        createLineChart(canvas, data) {
            // Simple canvas implementation - in production, use Chart.js or similar
            const ctx = canvas.getContext('2d');
            const width = canvas.width;
            const height = canvas.height;
            
            // Clear canvas
            ctx.clearRect(0, 0, width, height);
            
            // Simple line chart visualization
            ctx.strokeStyle = data.datasets[0].borderColor;
            ctx.lineWidth = 2;
            ctx.beginPath();
            
            const maxValue = Math.max(...data.datasets[0].data);
            const minValue = Math.min(...data.datasets[0].data);
            const range = maxValue - minValue;
            
            data.datasets[0].data.forEach((value, index) => {
                const x = (index / (data.datasets[0].data.length - 1)) * (width - 40) + 20;
                const y = height - 20 - ((value - minValue) / range) * (height - 40);
                
                if (index === 0) {
                    ctx.moveTo(x, y);
                } else {
                    ctx.lineTo(x, y);
                }
            });
            
            ctx.stroke();
        },

        /**
         * Create doughnut chart
         */
        createDoughnutChart(canvas, data) {
            const ctx = canvas.getContext('2d');
            const centerX = canvas.width / 2;
            const centerY = canvas.height / 2;
            const radius = Math.min(centerX, centerY) - 20;
            
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            
            const total = data.datasets[0].data.reduce((sum, value) => sum + value, 0);
            let currentAngle = -Math.PI / 2;
            
            data.datasets[0].data.forEach((value, index) => {
                const sliceAngle = (value / total) * 2 * Math.PI;
                
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, currentAngle, currentAngle + sliceAngle);
                ctx.arc(centerX, centerY, radius * 0.6, currentAngle + sliceAngle, currentAngle, true);
                ctx.closePath();
                
                ctx.fillStyle = data.datasets[0].backgroundColor[index];
                ctx.fill();
                
                currentAngle += sliceAngle;
            });
        },

        /**
         * Create bar chart
         */
        createBarChart(canvas, data) {
            const ctx = canvas.getContext('2d');
            const width = canvas.width;
            const height = canvas.height;
            
            ctx.clearRect(0, 0, width, height);
            
            const barWidth = (width - 60) / data.labels.length;
            const maxValue = Math.max(...data.datasets[0].data);
            
            data.datasets[0].data.forEach((value, index) => {
                const barHeight = (value / maxValue) * (height - 40);
                const x = 30 + index * barWidth + barWidth * 0.1;
                const y = height - 20 - barHeight;
                
                ctx.fillStyle = data.datasets[0].backgroundColor;
                ctx.fillRect(x, y, barWidth * 0.8, barHeight);
                
                // Add value label
                ctx.fillStyle = '#333';
                ctx.font = '12px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(value + '%', x + barWidth * 0.4, y - 5);
            });
        },

        /**
         * Initialize progress bars
         */
        initProgressBars() {
            $('.progress-bar').each((index, element) => {
                const $bar = $(element);
                const percentage = $bar.data('percentage') || 0;
                
                $bar.find('.progress-fill').animate({
                    width: percentage + '%'
                }, 1500);
            });
        },

        /**
         * Start animated counters
         */
        startCounters() {
            $('.metric-counter').each((index, element) => {
                const $counter = $(element);
                const target = parseInt($counter.data('target')) || 0;
                const duration = parseInt($counter.data('duration')) || 2000;
                
                this.animateCounter($counter, target, duration);
            });
        },

        /**
         * Animate counter
         */
        animateCounter($element, target, duration) {
            const startTime = Date.now();
            const startValue = 0;
            
            const animate = () => {
                const now = Date.now();
                const elapsed = now - startTime;
                const progress = Math.min(elapsed / duration, 1);
                
                const easeOutQuart = 1 - Math.pow(1 - progress, 4);
                const currentValue = Math.floor(startValue + (target - startValue) * easeOutQuart);
                
                $element.text(currentValue.toLocaleString());
                
                if (progress < 1) {
                    requestAnimationFrame(animate);
                }
            };
            
            animate();
        },

        /**
         * Calculate environmental impact
         */
        calculateImpact() {
            const $calculator = $('.impact-calculator');
            if ($calculator.length === 0) return;

            const waterUsage = parseFloat($('#water_usage').val()) || 0;
            const energyUsage = parseFloat($('#energy_usage').val()) || 0;
            const wasteGenerated = parseFloat($('#waste_generated').val()) || 0;
            const transportation = parseFloat($('#transportation').val()) || 0;

            // Calculate carbon footprint (simplified formula)
            const carbonFootprint = (waterUsage * 0.001) + (energyUsage * 0.5) + (wasteGenerated * 0.3) + (transportation * 0.2);
            
            // Calculate water impact
            const waterImpact = waterUsage * 1.2; // Including processing water
            
            // Calculate waste impact
            const wasteImpact = wasteGenerated * 0.8; // Recycling factor

            // Update display
            $('.carbon-footprint-result').text(carbonFootprint.toFixed(2) + ' kg CO2');
            $('.water-impact-result').text(waterImpact.toFixed(0) + ' liters');
            $('.waste-impact-result').text(wasteImpact.toFixed(1) + ' kg');

            // Show sustainability score
            const score = Math.max(0, 100 - (carbonFootprint * 2));
            $('.sustainability-score').text(score.toFixed(0));
            
            // Update score indicator
            this.updateScoreIndicator(score);
        },

        /**
         * Update sustainability score indicator
         */
        updateScoreIndicator(score) {
            const $indicator = $('.score-indicator');
            const $fill = $indicator.find('.score-fill');
            const $needle = $indicator.find('.score-needle');
            
            // Update fill color based on score
            let color = '#ff4444'; // Red for low scores
            if (score >= 70) {
                color = '#44ff44'; // Green for high scores
            } else if (score >= 40) {
                color = '#ffaa44'; // Orange for medium scores
            }
            
            $fill.css({
                'width': score + '%',
                'background-color': color
            });
            
            // Rotate needle if present
            if ($needle.length > 0) {
                const rotation = (score / 100) * 180 - 90;
                $needle.css('transform', `rotate(${rotation}deg)`);
            }
        },

        /**
         * Filter research projects
         */
        filterProjects() {
            const category = $('.research-filters select[name="category"]').val();
            const status = $('.research-filters select[name="status"]').val();
            
            $('.research-project-card').each((index, element) => {
                const $card = $(element);
                const cardCategory = $card.data('category');
                const cardStatus = $card.data('status');
                
                let show = true;
                
                if (category && category !== 'all' && cardCategory !== category) {
                    show = false;
                }
                
                if (status && status !== 'all' && cardStatus !== status) {
                    show = false;
                }
                
                $card.toggle(show);
            });
            
            // Update project count
            const visibleProjects = $('.research-project-card:visible').length;
            $('.project-count').text(visibleProjects);
        },

        /**
         * Filter sustainability reports
         */
        filterReports() {
            const year = $('.report-filters select[name="year"]').val();
            const type = $('.report-filters select[name="type"]').val();
            
            $('.sustainability-report-item').each((index, element) => {
                const $item = $(element);
                const itemYear = $item.data('year');
                const itemType = $item.data('type');
                
                let show = true;
                
                if (year && year !== 'all' && itemYear !== year) {
                    show = false;
                }
                
                if (type && type !== 'all' && itemType !== type) {
                    show = false;
                }
                
                $item.toggle(show);
            });
        },

        /**
         * Track carbon footprint
         */
        trackCarbonFootprint(e) {
            e.preventDefault();
            
            const $form = $(e.currentTarget);
            const $submitBtn = $form.find('button[type="submit"]');
            const originalText = $submitBtn.text();
            
            const trackingData = {
                action: 'track_carbon_footprint',
                nonce: aqualuxeSustainability.nonce,
                date: $form.find('input[name="date"]').val(),
                energy_usage: $form.find('input[name="energy_usage"]').val(),
                water_usage: $form.find('input[name="water_usage"]').val(),
                waste_generated: $form.find('input[name="waste_generated"]').val(),
                transportation: $form.find('input[name="transportation"]').val()
            };

            $submitBtn.prop('disabled', true).text('Saving...');

            $.ajax({
                url: aqualuxeSustainability.ajaxUrl,
                type: 'POST',
                data: trackingData,
                success: (response) => {
                    if (response.success) {
                        this.showSuccess('Carbon footprint data saved successfully');
                        $form[0].reset();
                        this.updateDashboardMetrics(response.data);
                    } else {
                        this.showError(response.data.message);
                    }
                },
                error: () => {
                    this.showError('Error saving carbon footprint data');
                },
                complete: () => {
                    $submitBtn.prop('disabled', false).text(originalText);
                }
            });
        },

        /**
         * Update initiative progress
         */
        updateInitiativeProgress(e) {
            e.preventDefault();
            
            const $btn = $(e.currentTarget);
            const initiativeId = $btn.data('initiative-id');
            const newProgress = prompt('Enter new progress percentage (0-100):');
            
            if (newProgress === null || isNaN(newProgress) || newProgress < 0 || newProgress > 100) {
                return;
            }
            
            $.ajax({
                url: aqualuxeSustainability.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'update_initiative_progress',
                    nonce: aqualuxeSustainability.nonce,
                    initiative_id: initiativeId,
                    progress: newProgress
                },
                success: (response) => {
                    if (response.success) {
                        const $progressBar = $btn.closest('.initiative-item').find('.progress-fill');
                        $progressBar.animate({ width: newProgress + '%' }, 500);
                        $btn.siblings('.progress-text').text(newProgress + '% complete');
                        this.showSuccess('Progress updated successfully');
                    } else {
                        this.showError(response.data.message);
                    }
                },
                error: () => {
                    this.showError('Error updating progress');
                }
            });
        },

        /**
         * Update dashboard metrics
         */
        updateDashboardMetrics(data) {
            if (data.totalCarbonReduction) {
                $('.carbon-reduction-metric').text(data.totalCarbonReduction + '%');
            }
            
            if (data.totalWaterSaved) {
                $('.water-saved-metric').text(data.totalWaterSaved.toLocaleString() + ' L');
            }
            
            if (data.totalWasteReduction) {
                $('.waste-reduction-metric').text(data.totalWasteReduction + '%');
            }
        },

        /**
         * Show success message
         */
        showSuccess(message) {
            this.showNotification(message, 'success');
        },

        /**
         * Show error message
         */
        showError(message) {
            this.showNotification(message, 'error');
        },

        /**
         * Show notification
         */
        showNotification(message, type = 'info') {
            const $notification = $(`
                <div class="sustainability-notification ${type}">
                    <span class="notification-message">${message}</span>
                    <button class="notification-close" aria-label="Close">&times;</button>
                </div>
            `);

            $('body').append($notification);

            setTimeout(() => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            }, 5000);

            $notification.find('.notification-close').on('click', () => {
                $notification.fadeOut(300, function() {
                    $(this).remove();
                });
            });
        }
    };

    // Initialize when DOM is ready
    $(document).ready(() => {
        SustainabilityModule.init();
    });

})(jQuery);