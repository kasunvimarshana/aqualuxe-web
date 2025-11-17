/**
 * AquaLuxe Water Parameter Calculator
 * 
 * Handles calculations and functionality for the water parameter calculator tool
 */

(function($) {
    'use strict';

    // Main calculator object
    const WaterCalculator = {
        // Store form elements
        elements: {},
        
        // Store parameter ranges for different fish types
        fishParameters: {
            tropical: {
                ph: { min: 6.5, max: 7.5, ideal: 7.0 },
                ammonia: { min: 0, max: 0, ideal: 0 },
                nitrite: { min: 0, max: 0, ideal: 0 },
                nitrate: { min: 0, max: 20, ideal: 5 },
                kh: { min: 4, max: 8, ideal: 6 },
                gh: { min: 5, max: 15, ideal: 10 },
                temperature: { min: 75, max: 82, ideal: 78 }
            },
            goldfish: {
                ph: { min: 7.0, max: 8.4, ideal: 7.5 },
                ammonia: { min: 0, max: 0, ideal: 0 },
                nitrite: { min: 0, max: 0, ideal: 0 },
                nitrate: { min: 0, max: 40, ideal: 20 },
                kh: { min: 5, max: 10, ideal: 7 },
                gh: { min: 10, max: 20, ideal: 15 },
                temperature: { min: 65, max: 74, ideal: 70 }
            },
            cichlid: {
                ph: { min: 7.8, max: 8.5, ideal: 8.2 },
                ammonia: { min: 0, max: 0, ideal: 0 },
                nitrite: { min: 0, max: 0, ideal: 0 },
                nitrate: { min: 0, max: 20, ideal: 10 },
                kh: { min: 10, max: 18, ideal: 14 },
                gh: { min: 12, max: 20, ideal: 16 },
                temperature: { min: 74, max: 82, ideal: 78 }
            },
            discus: {
                ph: { min: 6.0, max: 7.0, ideal: 6.5 },
                ammonia: { min: 0, max: 0, ideal: 0 },
                nitrite: { min: 0, max: 0, ideal: 0 },
                nitrate: { min: 0, max: 10, ideal: 5 },
                kh: { min: 2, max: 6, ideal: 4 },
                gh: { min: 3, max: 8, ideal: 5 },
                temperature: { min: 82, max: 86, ideal: 84 }
            },
            planted: {
                ph: { min: 6.0, max: 7.2, ideal: 6.8 },
                ammonia: { min: 0, max: 0, ideal: 0 },
                nitrite: { min: 0, max: 0, ideal: 0 },
                nitrate: { min: 5, max: 20, ideal: 10 },
                kh: { min: 3, max: 8, ideal: 5 },
                gh: { min: 4, max: 12, ideal: 7 },
                temperature: { min: 72, max: 80, ideal: 76 }
            },
            reef: {
                ph: { min: 8.1, max: 8.4, ideal: 8.3 },
                ammonia: { min: 0, max: 0, ideal: 0 },
                nitrite: { min: 0, max: 0, ideal: 0 },
                nitrate: { min: 0, max: 5, ideal: 0 },
                calcium: { min: 380, max: 450, ideal: 420 },
                alkalinity: { min: 8, max: 12, ideal: 10 },
                magnesium: { min: 1250, max: 1350, ideal: 1300 },
                salinity: { min: 1.023, max: 1.025, ideal: 1.024 },
                temperature: { min: 76, max: 82, ideal: 78 }
            },
            shrimp: {
                ph: { min: 6.4, max: 7.6, ideal: 7.0 },
                ammonia: { min: 0, max: 0, ideal: 0 },
                nitrite: { min: 0, max: 0, ideal: 0 },
                nitrate: { min: 0, max: 10, ideal: 5 },
                kh: { min: 2, max: 6, ideal: 4 },
                gh: { min: 6, max: 10, ideal: 8 },
                tds: { min: 150, max: 250, ideal: 200 },
                temperature: { min: 70, max: 78, ideal: 74 }
            }
        },
        
        // Initialize the calculator
        init: function() {
            this.cacheElements();
            this.bindEvents();
            this.initializeForm();
        },
        
        // Cache DOM elements
        cacheElements: function() {
            this.elements.form = $('#water-parameter-calculator-form');
            this.elements.tankType = $('#tank-type');
            this.elements.fishType = $('#fish-type');
            this.elements.parameterInputs = $('.parameter-input');
            this.elements.calculateBtn = $('#calculate-parameters');
            this.elements.resetBtn = $('#reset-parameters');
            this.elements.resultsContainer = $('#calculator-results');
            this.elements.resultsTable = $('#results-table');
            this.elements.recommendationsContainer = $('#recommendations-container');
            this.elements.saveBtn = $('#save-results');
            this.elements.exportBtn = $('#export-results');
            this.elements.historyContainer = $('#history-container');
            this.elements.historyTable = $('#history-table tbody');
            this.elements.clearHistoryBtn = $('#clear-history');
        },
        
        // Bind event handlers
        bindEvents: function() {
            const self = this;
            
            // Change visible parameters based on tank type
            this.elements.tankType.on('change', function() {
                self.updateParameterFields();
            });
            
            // Change parameter ranges based on fish type
            this.elements.fishType.on('change', function() {
                self.updateParameterRanges();
            });
            
            // Calculate button click
            this.elements.calculateBtn.on('click', function(e) {
                e.preventDefault();
                self.calculateParameters();
            });
            
            // Reset button click
            this.elements.resetBtn.on('click', function(e) {
                e.preventDefault();
                self.resetForm();
            });
            
            // Save results button click
            this.elements.saveBtn.on('click', function(e) {
                e.preventDefault();
                self.saveResults();
            });
            
            // Export results button click
            this.elements.exportBtn.on('click', function(e) {
                e.preventDefault();
                self.exportResults();
            });
            
            // Clear history button click
            this.elements.clearHistoryBtn.on('click', function(e) {
                e.preventDefault();
                self.clearHistory();
            });
        },
        
        // Initialize the form
        initializeForm: function() {
            this.updateParameterFields();
            this.updateParameterRanges();
            this.loadHistory();
        },
        
        // Update visible parameter fields based on tank type
        updateParameterFields: function() {
            const tankType = this.elements.tankType.val();
            
            // Hide all parameter groups first
            $('.parameter-group').hide();
            
            // Show common parameters for all tank types
            $('.parameter-common').show();
            
            // Show specific parameters based on tank type
            if (tankType === 'freshwater') {
                $('.parameter-freshwater').show();
                this.elements.fishType.find('option').show();
                this.elements.fishType.find('option[value="reef"]').hide();
            } else if (tankType === 'saltwater') {
                $('.parameter-saltwater').show();
                this.elements.fishType.find('option').hide();
                this.elements.fishType.find('option[value="reef"]').show();
                this.elements.fishType.val('reef');
            } else if (tankType === 'brackish') {
                $('.parameter-brackish').show();
                this.elements.fishType.find('option').show();
                this.elements.fishType.find('option[value="reef"]').hide();
                this.elements.fishType.find('option[value="discus"]').hide();
                this.elements.fishType.find('option[value="shrimp"]').hide();
            }
            
            this.updateParameterRanges();
        },
        
        // Update parameter ranges based on fish type
        updateParameterRanges: function() {
            const fishType = this.elements.fishType.val();
            const parameters = this.fishParameters[fishType];
            
            if (!parameters) return;
            
            // Update range indicators for each parameter
            for (const param in parameters) {
                const $rangeElement = $(`.range-${param}`);
                if ($rangeElement.length) {
                    $rangeElement.html(`Ideal: ${parameters[param].ideal} (Range: ${parameters[param].min} - ${parameters[param].max})`);
                }
            }
        },
        
        // Calculate water parameters
        calculateParameters: function() {
            // Validate form
            if (!this.validateForm()) {
                return;
            }
            
            const tankType = this.elements.tankType.val();
            const fishType = this.elements.fishType.val();
            const parameters = this.fishParameters[fishType];
            const results = {};
            const issues = [];
            
            // Get all input values
            this.elements.parameterInputs.each(function() {
                const $input = $(this);
                const param = $input.attr('name');
                const value = parseFloat($input.val());
                
                if (!isNaN(value) && $input.closest('.parameter-group').is(':visible')) {
                    results[param] = value;
                }
            });
            
            // Check each parameter against ideal ranges
            for (const param in results) {
                if (parameters && parameters[param]) {
                    const value = results[param];
                    const ideal = parameters[param];
                    
                    results[`${param}_status`] = this.getParameterStatus(value, ideal.min, ideal.max);
                    
                    // Add to issues if not in range
                    if (results[`${param}_status`] !== 'good') {
                        issues.push({
                            parameter: param,
                            value: value,
                            ideal: ideal,
                            status: results[`${param}_status`]
                        });
                    }
                }
            }
            
            // Store results
            this.currentResults = {
                date: new Date(),
                tankType: tankType,
                fishType: fishType,
                parameters: results,
                issues: issues
            };
            
            // Display results
            this.displayResults();
        },
        
        // Validate the form inputs
        validateForm: function() {
            let isValid = true;
            const self = this;
            
            this.elements.parameterInputs.each(function() {
                const $input = $(this);
                const $group = $input.closest('.parameter-group');
                
                // Only validate visible inputs
                if ($group.is(':visible')) {
                    const value = $input.val().trim();
                    
                    if (value === '') {
                        self.showError($input, 'This field is required');
                        isValid = false;
                    } else if (isNaN(parseFloat(value))) {
                        self.showError($input, 'Please enter a valid number');
                        isValid = false;
                    } else {
                        self.clearError($input);
                    }
                }
            });
            
            return isValid;
        },
        
        // Show error message for an input
        showError: function($input, message) {
            const $formGroup = $input.closest('.form-group');
            $formGroup.addClass('has-error');
            
            // Remove any existing error message
            $formGroup.find('.error-message').remove();
            
            // Add new error message
            $formGroup.append(`<div class="error-message">${message}</div>`);
        },
        
        // Clear error message for an input
        clearError: function($input) {
            const $formGroup = $input.closest('.form-group');
            $formGroup.removeClass('has-error');
            $formGroup.find('.error-message').remove();
        },
        
        // Get parameter status based on value and range
        getParameterStatus: function(value, min, max) {
            if (value < min) {
                return 'low';
            } else if (value > max) {
                return 'high';
            } else {
                return 'good';
            }
        },
        
        // Display calculation results
        displayResults: function() {
            if (!this.currentResults) return;
            
            const results = this.currentResults;
            const parameters = this.fishParameters[results.fishType];
            let tableHtml = '';
            let recommendationsHtml = '';
            
            // Build results table
            tableHtml += '<table class="results-table">';
            tableHtml += '<thead><tr><th>Parameter</th><th>Your Value</th><th>Ideal Range</th><th>Status</th></tr></thead>';
            tableHtml += '<tbody>';
            
            for (const param in results.parameters) {
                // Skip status fields
                if (param.endsWith('_status')) continue;
                
                const value = results.parameters[param];
                const status = results.parameters[`${param}_status`];
                
                if (parameters && parameters[param]) {
                    const ideal = parameters[param];
                    const paramName = this.getParameterName(param);
                    
                    tableHtml += `<tr class="status-${status || 'unknown'}">`;
                    tableHtml += `<td>${paramName}</td>`;
                    tableHtml += `<td>${value}</td>`;
                    tableHtml += `<td>${ideal.min} - ${ideal.max}</td>`;
                    tableHtml += `<td>${this.getStatusText(status)}</td>`;
                    tableHtml += '</tr>';
                }
            }
            
            tableHtml += '</tbody></table>';
            
            // Build recommendations
            if (results.issues.length > 0) {
                recommendationsHtml += '<h3>Recommendations</h3>';
                recommendationsHtml += '<ul class="recommendations-list">';
                
                results.issues.forEach(issue => {
                    recommendationsHtml += `<li class="recommendation-item status-${issue.status}">`;
                    recommendationsHtml += `<strong>${this.getParameterName(issue.parameter)}:</strong> `;
                    recommendationsHtml += this.getRecommendation(issue.parameter, issue.status, issue.value, issue.ideal);
                    recommendationsHtml += '</li>';
                });
                
                recommendationsHtml += '</ul>';
            } else {
                recommendationsHtml += '<div class="all-good-message">';
                recommendationsHtml += '<h3>All Parameters Look Good!</h3>';
                recommendationsHtml += '<p>Your water parameters are within the ideal range for your selected tank and fish type.</p>';
                recommendationsHtml += '</div>';
            }
            
            // Update the DOM
            this.elements.resultsTable.html(tableHtml);
            this.elements.recommendationsContainer.html(recommendationsHtml);
            this.elements.resultsContainer.show();
            
            // Scroll to results
            $('html, body').animate({
                scrollTop: this.elements.resultsContainer.offset().top - 50
            }, 500);
        },
        
        // Get display name for parameter
        getParameterName: function(param) {
            const names = {
                ph: 'pH',
                ammonia: 'Ammonia (NH₃)',
                nitrite: 'Nitrite (NO₂)',
                nitrate: 'Nitrate (NO₃)',
                kh: 'Carbonate Hardness (KH)',
                gh: 'General Hardness (GH)',
                temperature: 'Temperature (°F)',
                calcium: 'Calcium (Ca)',
                alkalinity: 'Alkalinity (dKH)',
                magnesium: 'Magnesium (Mg)',
                salinity: 'Salinity (SG)',
                tds: 'Total Dissolved Solids (TDS)'
            };
            
            return names[param] || param;
        },
        
        // Get status text
        getStatusText: function(status) {
            switch (status) {
                case 'low':
                    return 'Too Low';
                case 'high':
                    return 'Too High';
                case 'good':
                    return 'Good';
                default:
                    return 'Unknown';
            }
        },
        
        // Get recommendation text based on parameter and status
        getRecommendation: function(param, status, value, ideal) {
            let recommendation = '';
            
            if (status === 'low') {
                switch (param) {
                    case 'ph':
                        recommendation = `Your pH is too low (${value}). Increase it gradually using pH buffer products or crushed coral substrate. Target: ${ideal.ideal}.`;
                        break;
                    case 'kh':
                        recommendation = `Your KH is too low (${value}). Add carbonate supplements or crushed coral to increase buffering capacity. Target: ${ideal.ideal}.`;
                        break;
                    case 'gh':
                        recommendation = `Your GH is too low (${value}). Add GH supplements or minerals to increase water hardness. Target: ${ideal.ideal}.`;
                        break;
                    case 'temperature':
                        recommendation = `Your temperature is too low (${value}°F). Adjust your heater to gradually increase temperature. Target: ${ideal.ideal}°F.`;
                        break;
                    case 'calcium':
                        recommendation = `Your calcium level is too low (${value}). Add calcium supplements to increase levels. Target: ${ideal.ideal}.`;
                        break;
                    case 'alkalinity':
                        recommendation = `Your alkalinity is too low (${value}). Add alkalinity supplements to increase buffering capacity. Target: ${ideal.ideal}.`;
                        break;
                    case 'magnesium':
                        recommendation = `Your magnesium level is too low (${value}). Add magnesium supplements to increase levels. Target: ${ideal.ideal}.`;
                        break;
                    case 'salinity':
                        recommendation = `Your salinity is too low (${value}). Add marine salt mix to increase salinity. Target: ${ideal.ideal}.`;
                        break;
                    case 'tds':
                        recommendation = `Your TDS is too low (${value}). Add mineral supplements to increase TDS. Target: ${ideal.ideal}.`;
                        break;
                    default:
                        recommendation = `Your ${this.getParameterName(param)} is too low (${value}). Ideal range: ${ideal.min} - ${ideal.max}.`;
                }
            } else if (status === 'high') {
                switch (param) {
                    case 'ph':
                        recommendation = `Your pH is too high (${value}). Lower it gradually using pH down products or driftwood. Target: ${ideal.ideal}.`;
                        break;
                    case 'ammonia':
                        recommendation = `Your ammonia level is too high (${value}). Perform a 30-50% water change immediately and check your filtration. Target: 0.`;
                        break;
                    case 'nitrite':
                        recommendation = `Your nitrite level is too high (${value}). Perform a 30-50% water change immediately and check your filtration. Target: 0.`;
                        break;
                    case 'nitrate':
                        recommendation = `Your nitrate level is too high (${value}). Perform a water change and consider adding live plants or improving filtration. Target: ${ideal.ideal}.`;
                        break;
                    case 'kh':
                        recommendation = `Your KH is too high (${value}). Dilute with RO/DI water to reduce carbonate hardness. Target: ${ideal.ideal}.`;
                        break;
                    case 'gh':
                        recommendation = `Your GH is too high (${value}). Use RO/DI water for water changes to reduce general hardness. Target: ${ideal.ideal}.`;
                        break;
                    case 'temperature':
                        recommendation = `Your temperature is too high (${value}°F). Adjust your heater or add a fan to gradually decrease temperature. Target: ${ideal.ideal}°F.`;
                        break;
                    case 'calcium':
                        recommendation = `Your calcium level is too high (${value}). Perform water changes with properly balanced salt mix. Target: ${ideal.ideal}.`;
                        break;
                    case 'alkalinity':
                        recommendation = `Your alkalinity is too high (${value}). Perform water changes with properly balanced salt mix. Target: ${ideal.ideal}.`;
                        break;
                    case 'magnesium':
                        recommendation = `Your magnesium level is too high (${value}). Perform water changes with properly balanced salt mix. Target: ${ideal.ideal}.`;
                        break;
                    case 'salinity':
                        recommendation = `Your salinity is too high (${value}). Add RO/DI water to decrease salinity. Target: ${ideal.ideal}.`;
                        break;
                    case 'tds':
                        recommendation = `Your TDS is too high (${value}). Perform water changes with RO/DI water to reduce TDS. Target: ${ideal.ideal}.`;
                        break;
                    default:
                        recommendation = `Your ${this.getParameterName(param)} is too high (${value}). Ideal range: ${ideal.min} - ${ideal.max}.`;
                }
            }
            
            return recommendation;
        },
        
        // Reset the form
        resetForm: function() {
            this.elements.form[0].reset();
            this.elements.resultsContainer.hide();
            this.elements.parameterInputs.each(function() {
                const $input = $(this);
                const $formGroup = $input.closest('.form-group');
                $formGroup.removeClass('has-error');
                $formGroup.find('.error-message').remove();
            });
            this.updateParameterFields();
        },
        
        // Save results to history
        saveResults: function() {
            if (!this.currentResults) return;
            
            // Get existing history or initialize new array
            let history = JSON.parse(localStorage.getItem('waterParameterHistory') || '[]');
            
            // Add current results to history
            history.push({
                date: new Date().toISOString(),
                tankType: this.currentResults.tankType,
                fishType: this.currentResults.fishType,
                parameters: this.currentResults.parameters
            });
            
            // Limit history to last 20 entries
            if (history.length > 20) {
                history = history.slice(history.length - 20);
            }
            
            // Save to localStorage
            localStorage.setItem('waterParameterHistory', JSON.stringify(history));
            
            // Update history display
            this.loadHistory();
            
            // Show confirmation
            alert('Results saved to history!');
        },
        
        // Export results as CSV
        exportResults: function() {
            if (!this.currentResults) return;
            
            const results = this.currentResults;
            const parameters = this.fishParameters[results.fishType];
            let csvContent = 'data:text/csv;charset=utf-8,';
            
            // Add header row
            csvContent += 'Date,Tank Type,Fish Type,Parameter,Value,Ideal Min,Ideal Max,Ideal Value,Status\n';
            
            // Add data rows
            for (const param in results.parameters) {
                // Skip status fields
                if (param.endsWith('_status')) continue;
                
                const value = results.parameters[param];
                const status = results.parameters[`${param}_status`] || 'unknown';
                
                if (parameters && parameters[param]) {
                    const ideal = parameters[param];
                    const paramName = this.getParameterName(param);
                    const date = new Date().toISOString().split('T')[0];
                    
                    csvContent += `${date},${results.tankType},${results.fishType},${paramName},${value},${ideal.min},${ideal.max},${ideal.ideal},${this.getStatusText(status)}\n`;
                }
            }
            
            // Create download link
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement('a');
            link.setAttribute('href', encodedUri);
            link.setAttribute('download', `water_parameters_${new Date().toISOString().split('T')[0]}.csv`);
            document.body.appendChild(link);
            
            // Trigger download
            link.click();
            
            // Clean up
            document.body.removeChild(link);
        },
        
        // Load history from localStorage
        loadHistory: function() {
            const history = JSON.parse(localStorage.getItem('waterParameterHistory') || '[]');
            
            if (history.length === 0) {
                this.elements.historyContainer.hide();
                return;
            }
            
            let tableHtml = '';
            
            // Sort history by date (newest first)
            history.sort((a, b) => new Date(b.date) - new Date(a.date));
            
            // Build history table
            history.forEach((entry, index) => {
                const date = new Date(entry.date).toLocaleDateString();
                const time = new Date(entry.date).toLocaleTimeString();
                
                tableHtml += '<tr>';
                tableHtml += `<td>${date} ${time}</td>`;
                tableHtml += `<td>${this.getTankTypeName(entry.tankType)}</td>`;
                tableHtml += `<td>${this.getFishTypeName(entry.fishType)}</td>`;
                
                // Add key parameters
                if (entry.parameters.ph) {
                    tableHtml += `<td>${entry.parameters.ph}</td>`;
                } else {
                    tableHtml += '<td>-</td>';
                }
                
                if (entry.parameters.ammonia) {
                    tableHtml += `<td>${entry.parameters.ammonia}</td>`;
                } else {
                    tableHtml += '<td>-</td>';
                }
                
                if (entry.parameters.nitrate) {
                    tableHtml += `<td>${entry.parameters.nitrate}</td>`;
                } else {
                    tableHtml += '<td>-</td>';
                }
                
                // Add view details button
                tableHtml += `<td><button class="view-history-details" data-index="${index}">View Details</button></td>`;
                tableHtml += '</tr>';
            });
            
            // Update the DOM
            this.elements.historyTable.html(tableHtml);
            this.elements.historyContainer.show();
            
            // Bind click event to view details buttons
            $('.view-history-details').on('click', function() {
                const index = $(this).data('index');
                WaterCalculator.viewHistoryDetails(index);
            });
        },
        
        // View details of a history entry
        viewHistoryDetails: function(index) {
            const history = JSON.parse(localStorage.getItem('waterParameterHistory') || '[]');
            
            if (!history[index]) return;
            
            const entry = history[index];
            const parameters = this.fishParameters[entry.fishType];
            let detailsHtml = '';
            
            // Build modal content
            detailsHtml += `<h3>Water Parameters - ${new Date(entry.date).toLocaleString()}</h3>`;
            detailsHtml += `<p><strong>Tank Type:</strong> ${this.getTankTypeName(entry.tankType)}</p>`;
            detailsHtml += `<p><strong>Fish Type:</strong> ${this.getFishTypeName(entry.fishType)}</p>`;
            
            detailsHtml += '<table class="history-details-table">';
            detailsHtml += '<thead><tr><th>Parameter</th><th>Value</th><th>Ideal Range</th></tr></thead>';
            detailsHtml += '<tbody>';
            
            for (const param in entry.parameters) {
                // Skip status fields
                if (param.endsWith('_status')) continue;
                
                const value = entry.parameters[param];
                
                if (parameters && parameters[param]) {
                    const ideal = parameters[param];
                    const paramName = this.getParameterName(param);
                    
                    detailsHtml += '<tr>';
                    detailsHtml += `<td>${paramName}</td>`;
                    detailsHtml += `<td>${value}</td>`;
                    detailsHtml += `<td>${ideal.min} - ${ideal.max}</td>`;
                    detailsHtml += '</tr>';
                }
            }
            
            detailsHtml += '</tbody></table>';
            
            // Create modal
            const $modal = $('<div class="water-calculator-modal"></div>');
            const $modalContent = $('<div class="water-calculator-modal-content"></div>');
            const $closeBtn = $('<span class="water-calculator-modal-close">&times;</span>');
            
            $modalContent.append($closeBtn);
            $modalContent.append(detailsHtml);
            $modal.append($modalContent);
            $('body').append($modal);
            
            // Show modal
            $modal.fadeIn(300);
            
            // Close modal on click
            $closeBtn.on('click', function() {
                $modal.fadeOut(300, function() {
                    $modal.remove();
                });
            });
            
            // Close modal when clicking outside
            $modal.on('click', function(e) {
                if ($(e.target).is($modal)) {
                    $modal.fadeOut(300, function() {
                        $modal.remove();
                    });
                }
            });
        },
        
        // Clear history
        clearHistory: function() {
            if (confirm('Are you sure you want to clear all history?')) {
                localStorage.removeItem('waterParameterHistory');
                this.elements.historyTable.html('');
                this.elements.historyContainer.hide();
            }
        },
        
        // Get tank type display name
        getTankTypeName: function(type) {
            const names = {
                freshwater: 'Freshwater',
                saltwater: 'Saltwater',
                brackish: 'Brackish'
            };
            
            return names[type] || type;
        },
        
        // Get fish type display name
        getFishTypeName: function(type) {
            const names = {
                tropical: 'Tropical Community',
                goldfish: 'Goldfish',
                cichlid: 'Cichlid',
                discus: 'Discus',
                planted: 'Planted Tank',
                reef: 'Reef Tank',
                shrimp: 'Shrimp Tank'
            };
            
            return names[type] || type;
        }
    };
    
    // Initialize calculator when document is ready
    $(document).ready(function() {
        WaterCalculator.init();
    });

})(jQuery);