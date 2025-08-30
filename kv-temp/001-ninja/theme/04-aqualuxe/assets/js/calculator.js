/**
 * AquaLuxe Water Parameter Calculator
 *
 * JavaScript functionality for the water parameter calculator tools
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

(function($) {
    'use strict';

    // Calculator tabs functionality
    function initCalculatorTabs() {
        $('.calculator-tabs .tab-navigation li').on('click', function() {
            const tabId = $(this).data('tab');
            
            // Update active tab navigation
            $(this).siblings().removeClass('active');
            $(this).addClass('active');
            
            // Show selected tab content
            $(this).closest('.calculator-tabs').find('.tab-pane').removeClass('active');
            $(this).closest('.calculator-tabs').find('.tab-pane#' + tabId).addClass('active');
        });
    }

    // pH Calculator form submission
    function initPHCalculator() {
        $('.ph-calculator-form').on('submit', function(e) {
            e.preventDefault();
            
            const currentPH = parseFloat($('#current-ph').val());
            const targetPH = parseFloat($('#target-ph').val());
            const tankVolume = parseFloat($('#tank-volume-ph').val());
            const adjustmentType = $('#adjustment-type').val();
            
            // Validate inputs
            if (isNaN(currentPH) || isNaN(targetPH) || isNaN(tankVolume) || !adjustmentType) {
                alert(aqualuxeCalculator.i18n.error);
                return;
            }
            
            // Check if adjustment type matches the actual change needed
            const actualChange = (targetPH > currentPH) ? 'increase' : 'decrease';
            if (adjustmentType !== actualChange) {
                alert('Your selected adjustment type does not match the change needed based on your current and target pH values.');
                return;
            }
            
            // Show loading state
            const resultsContainer = $('.ph-calculator-results');
            resultsContainer.find('.results-content').html('<p>' + aqualuxeCalculator.i18n.loading + '</p>');
            resultsContainer.show();
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCalculator.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_calculate_parameters',
                    nonce: aqualuxeCalculator.nonce,
                    tab: 'ph-adjustment',
                    current_ph: currentPH,
                    target_ph: targetPH,
                    tank_volume: tankVolume,
                    adjustment_type: adjustmentType
                },
                success: function(response) {
                    if (response.success) {
                        resultsContainer.find('.results-content').html(response.data.html);
                    } else {
                        resultsContainer.find('.results-content').html('<p class="error">' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    resultsContainer.find('.results-content').html('<p class="error">' + aqualuxeCalculator.i18n.error + '</p>');
                }
            });
        });
    }

    // Hardness Calculator form submission
    function initHardnessCalculator() {
        $('.hardness-calculator-form').on('submit', function(e) {
            e.preventDefault();
            
            const currentHardness = parseFloat($('#current-hardness').val());
            const targetHardness = parseFloat($('#target-hardness').val());
            const tankVolume = parseFloat($('#tank-volume-hardness').val());
            const adjustmentType = $('#hardness-adjustment-type').val();
            
            // Validate inputs
            if (isNaN(currentHardness) || isNaN(targetHardness) || isNaN(tankVolume) || !adjustmentType) {
                alert(aqualuxeCalculator.i18n.error);
                return;
            }
            
            // Check if adjustment type matches the actual change needed
            const actualChange = (targetHardness > currentHardness) ? 'increase' : 'decrease';
            if (adjustmentType !== actualChange) {
                alert('Your selected adjustment type does not match the change needed based on your current and target hardness values.');
                return;
            }
            
            // Show loading state
            const resultsContainer = $('.hardness-calculator-results');
            resultsContainer.find('.results-content').html('<p>' + aqualuxeCalculator.i18n.loading + '</p>');
            resultsContainer.show();
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCalculator.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_calculate_parameters',
                    nonce: aqualuxeCalculator.nonce,
                    tab: 'hardness-adjustment',
                    current_hardness: currentHardness,
                    target_hardness: targetHardness,
                    tank_volume: tankVolume,
                    adjustment_type: adjustmentType
                },
                success: function(response) {
                    if (response.success) {
                        resultsContainer.find('.results-content').html(response.data.html);
                    } else {
                        resultsContainer.find('.results-content').html('<p class="error">' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    resultsContainer.find('.results-content').html('<p class="error">' + aqualuxeCalculator.i18n.error + '</p>');
                }
            });
        });
    }

    // Medication Calculator form submission
    function initMedicationCalculator() {
        // Show/hide custom medication input based on selection
        $('#medication-type').on('change', function() {
            if ($(this).val() === 'custom') {
                $('.custom-medication-row').show();
            } else {
                $('.custom-medication-row').hide();
            }
        });
        
        $('.medication-calculator-form').on('submit', function(e) {
            e.preventDefault();
            
            const medicationType = $('#medication-type').val();
            const tankVolume = parseFloat($('#tank-volume-medication').val());
            const customDosage = $('#custom-dosage').val();
            
            // Validate inputs
            if (!medicationType || isNaN(tankVolume)) {
                alert(aqualuxeCalculator.i18n.error);
                return;
            }
            
            if (medicationType === 'custom' && !customDosage) {
                alert('Please enter a custom dosage rate.');
                return;
            }
            
            // Show loading state
            const resultsContainer = $('.medication-calculator-results');
            resultsContainer.find('.results-content').html('<p>' + aqualuxeCalculator.i18n.loading + '</p>');
            resultsContainer.show();
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCalculator.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_calculate_parameters',
                    nonce: aqualuxeCalculator.nonce,
                    tab: 'medication-dosage',
                    medication_type: medicationType,
                    tank_volume: tankVolume,
                    custom_dosage: customDosage
                },
                success: function(response) {
                    if (response.success) {
                        resultsContainer.find('.results-content').html(response.data.html);
                    } else {
                        resultsContainer.find('.results-content').html('<p class="error">' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    resultsContainer.find('.results-content').html('<p class="error">' + aqualuxeCalculator.i18n.error + '</p>');
                }
            });
        });
    }

    // Tank Volume Calculator
    function initTankVolumeCalculator() {
        // Rectangular tank calculator
        $('.rectangular-calculator-form').on('submit', function(e) {
            e.preventDefault();
            
            const length = parseFloat($('#rect-length').val());
            const width = parseFloat($('#rect-width').val());
            const height = parseFloat($('#rect-height').val());
            const substrate = parseFloat($('#rect-substrate').val()) || 0;
            
            // Validate inputs
            if (isNaN(length) || isNaN(width) || isNaN(height)) {
                alert(aqualuxeCalculator.i18n.error);
                return;
            }
            
            // Show loading state
            const resultsContainer = $('.rectangular-calculator-results');
            resultsContainer.find('.results-content').html('<p>' + aqualuxeCalculator.i18n.loading + '</p>');
            resultsContainer.show();
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCalculator.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_calculate_volume',
                    nonce: aqualuxeCalculator.nonce,
                    tank_type: 'rectangular',
                    length: length,
                    width: width,
                    height: height,
                    substrate: substrate
                },
                success: function(response) {
                    if (response.success) {
                        resultsContainer.find('.results-content').html(response.data.html);
                    } else {
                        resultsContainer.find('.results-content').html('<p class="error">' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    resultsContainer.find('.results-content').html('<p class="error">' + aqualuxeCalculator.i18n.error + '</p>');
                }
            });
        });
        
        // Cylindrical tank calculator
        $('.cylindrical-calculator-form').on('submit', function(e) {
            e.preventDefault();
            
            const diameter = parseFloat($('#cyl-diameter').val());
            const height = parseFloat($('#cyl-height').val());
            const substrate = parseFloat($('#cyl-substrate').val()) || 0;
            
            // Validate inputs
            if (isNaN(diameter) || isNaN(height)) {
                alert(aqualuxeCalculator.i18n.error);
                return;
            }
            
            // Show loading state
            const resultsContainer = $('.cylindrical-calculator-results');
            resultsContainer.find('.results-content').html('<p>' + aqualuxeCalculator.i18n.loading + '</p>');
            resultsContainer.show();
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCalculator.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_calculate_volume',
                    nonce: aqualuxeCalculator.nonce,
                    tank_type: 'cylindrical',
                    diameter: diameter,
                    height: height,
                    substrate: substrate
                },
                success: function(response) {
                    if (response.success) {
                        resultsContainer.find('.results-content').html(response.data.html);
                    } else {
                        resultsContainer.find('.results-content').html('<p class="error">' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    resultsContainer.find('.results-content').html('<p class="error">' + aqualuxeCalculator.i18n.error + '</p>');
                }
            });
        });
        
        // Bowfront tank calculator
        $('.bowfront-calculator-form').on('submit', function(e) {
            e.preventDefault();
            
            const length = parseFloat($('#bow-length').val());
            const widthBack = parseFloat($('#bow-width-back').val());
            const widthCenter = parseFloat($('#bow-width-center').val());
            const height = parseFloat($('#bow-height').val());
            const substrate = parseFloat($('#bow-substrate').val()) || 0;
            
            // Validate inputs
            if (isNaN(length) || isNaN(widthBack) || isNaN(widthCenter) || isNaN(height)) {
                alert(aqualuxeCalculator.i18n.error);
                return;
            }
            
            // Show loading state
            const resultsContainer = $('.bowfront-calculator-results');
            resultsContainer.find('.results-content').html('<p>' + aqualuxeCalculator.i18n.loading + '</p>');
            resultsContainer.show();
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCalculator.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_calculate_volume',
                    nonce: aqualuxeCalculator.nonce,
                    tank_type: 'bowfront',
                    length: length,
                    width_back: widthBack,
                    width_center: widthCenter,
                    height: height,
                    substrate: substrate
                },
                success: function(response) {
                    if (response.success) {
                        resultsContainer.find('.results-content').html(response.data.html);
                    } else {
                        resultsContainer.find('.results-content').html('<p class="error">' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    resultsContainer.find('.results-content').html('<p class="error">' + aqualuxeCalculator.i18n.error + '</p>');
                }
            });
        });
    }

    // Stocking Calculator
    function initStockingCalculator() {
        // Add fish button
        $('.add-fish').on('click', function() {
            const fishEntry = $('.fish-entry').first().clone();
            fishEntry.find('input').val('');
            fishEntry.find('.remove-fish').show();
            $('.fish-list-container').append(fishEntry);
        });
        
        // Remove fish button (delegated event)
        $('.fish-list-container').on('click', '.remove-fish', function() {
            $(this).closest('.fish-entry').remove();
        });
        
        // Form submission
        $('.stocking-calculator-form').on('submit', function(e) {
            e.preventDefault();
            
            const tankVolume = parseFloat($('#tank-volume-stocking').val());
            const filtrationCapacity = parseFloat($('#filtration-capacity').val()) || 0;
            const tankType = $('#tank-type').val();
            
            // Collect fish data
            const fishNames = [];
            const fishCounts = [];
            const fishSizes = [];
            
            $('.fish-entry').each(function() {
                const name = $(this).find('input[name="fish_name[]"]').val();
                const count = parseInt($(this).find('input[name="fish_count[]"]').val());
                const size = parseFloat($(this).find('input[name="fish_size[]"]').val());
                
                if (name && !isNaN(count) && !isNaN(size)) {
                    fishNames.push(name);
                    fishCounts.push(count);
                    fishSizes.push(size);
                }
            });
            
            // Validate inputs
            if (isNaN(tankVolume) || !tankType || fishNames.length === 0) {
                alert(aqualuxeCalculator.i18n.error);
                return;
            }
            
            // Show loading state
            const resultsContainer = $('.stocking-calculator-results');
            resultsContainer.find('.results-content').html('<p>' + aqualuxeCalculator.i18n.loading + '</p>');
            resultsContainer.show();
            
            // Send AJAX request
            $.ajax({
                url: aqualuxeCalculator.ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_calculate_stocking',
                    nonce: aqualuxeCalculator.nonce,
                    tank_volume: tankVolume,
                    filtration_capacity: filtrationCapacity,
                    tank_type: tankType,
                    fish_name: fishNames,
                    fish_count: fishCounts,
                    fish_size: fishSizes
                },
                success: function(response) {
                    if (response.success) {
                        resultsContainer.find('.results-content').html(response.data.html);
                        
                        // Update stocking meter
                        const percentage = response.data.stocking_percentage;
                        const stockingClass = response.data.stocking_class;
                        
                        $('.stocking-meter-fill').css('width', Math.min(100, percentage) + '%');
                        $('.stocking-meter-fill').removeClass().addClass('stocking-meter-fill ' + stockingClass);
                    } else {
                        resultsContainer.find('.results-content').html('<p class="error">' + response.data.message + '</p>');
                    }
                },
                error: function() {
                    resultsContainer.find('.results-content').html('<p class="error">' + aqualuxeCalculator.i18n.error + '</p>');
                }
            });
        });
    }

    // Initialize all calculators when document is ready
    $(document).ready(function() {
        initCalculatorTabs();
        initPHCalculator();
        initHardnessCalculator();
        initMedicationCalculator();
        initTankVolumeCalculator();
        initStockingCalculator();
    });

})(jQuery);