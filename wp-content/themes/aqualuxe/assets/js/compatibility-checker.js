/**
 * AquaLuxe Fish Compatibility Checker
 * 
 * Handles functionality for the fish compatibility checker tool
 */

(function($) {
    'use strict';

    // Main compatibility checker object
    const CompatibilityChecker = {
        // Store form elements
        elements: {},
        
        // Store fish compatibility database
        fishDatabase: {},
        
        // Store selected fish
        selectedFish: [],
        
        // Initialize the compatibility checker
        init: function() {
            this.loadFishDatabase();
            this.cacheElements();
            this.bindEvents();
        },
        
        // Load fish compatibility database
        loadFishDatabase: function() {
            const self = this;
            
            // Show loading indicator
            $('#compatibility-loading').show();
            
            // Fetch fish database from server
            $.ajax({
                url: aqualuxe_compatibility.ajax_url,
                type: 'post',
                data: {
                    action: 'aqualuxe_get_fish_database',
                    security: aqualuxe_compatibility.nonce
                },
                success: function(response) {
                    if (response.success) {
                        self.fishDatabase = response.data.fish;
                        self.initializeFishSelector();
                    } else {
                        self.showError('Failed to load fish database. Please try again later.');
                    }
                    $('#compatibility-loading').hide();
                },
                error: function() {
                    self.showError('Failed to connect to server. Please check your internet connection and try again.');
                    $('#compatibility-loading').hide();
                }
            });
        },
        
        // Cache DOM elements
        cacheElements: function() {
            this.elements.form = $('#compatibility-checker-form');
            this.elements.tankSize = $('#tank-size');
            this.elements.tankType = $('#tank-type');
            this.elements.fishSelector = $('#fish-selector');
            this.elements.searchInput = $('#fish-search');
            this.elements.fishResults = $('#fish-search-results');
            this.elements.selectedFishContainer = $('#selected-fish');
            this.elements.checkCompatibilityBtn = $('#check-compatibility');
            this.elements.resetBtn = $('#reset-compatibility');
            this.elements.resultsContainer = $('#compatibility-results');
            this.elements.compatibilityMatrix = $('#compatibility-matrix');
            this.elements.recommendationsContainer = $('#compatibility-recommendations');
            this.elements.saveBtn = $('#save-compatibility');
            this.elements.printBtn = $('#print-compatibility');
        },
        
        // Bind event handlers
        bindEvents: function() {
            const self = this;
            
            // Search input keyup
            this.elements.searchInput.on('keyup', function() {
                self.searchFish($(this).val());
            });
            
            // Check compatibility button click
            this.elements.checkCompatibilityBtn.on('click', function(e) {
                e.preventDefault();
                self.checkCompatibility();
            });
            
            // Reset button click
            this.elements.resetBtn.on('click', function(e) {
                e.preventDefault();
                self.resetForm();
            });
            
            // Save button click
            this.elements.saveBtn.on('click', function(e) {
                e.preventDefault();
                self.saveResults();
            });
            
            // Print button click
            this.elements.printBtn.on('click', function(e) {
                e.preventDefault();
                window.print();
            });
            
            // Tank type change
            this.elements.tankType.on('change', function() {
                self.filterFishByTankType();
            });
        },
        
        // Initialize fish selector
        initializeFishSelector: function() {
            const self = this;
            
            // Clear any existing options
            this.elements.fishResults.empty();
            
            // Filter fish by tank type
            this.filterFishByTankType();
            
            // Add click event for fish selection
            $(document).on('click', '.fish-result-item', function() {
                const fishId = $(this).data('fish-id');
                self.addFish(fishId);
            });
            
            // Add click event for removing selected fish
            $(document).on('click', '.remove-fish', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const fishId = $(this).closest('.selected-fish-item').data('fish-id');
                self.removeFish(fishId);
            });
        },
        
        // Filter fish by tank type
        filterFishByTankType: function() {
            const tankType = this.elements.tankType.val();
            
            // Filter fish database by tank type
            this.filteredFish = Object.values(this.fishDatabase).filter(function(fish) {
                return fish.tank_type === tankType || fish.tank_type === 'both';
            });
            
            // Clear search input and results
            this.elements.searchInput.val('');
            this.elements.fishResults.empty();
            this.elements.fishResults.hide();
            
            // Check if any currently selected fish need to be removed due to tank type change
            const self = this;
            const incompatibleFish = this.selectedFish.filter(function(fishId) {
                const fish = self.fishDatabase[fishId];
                return fish.tank_type !== tankType && fish.tank_type !== 'both';
            });
            
            if (incompatibleFish.length > 0) {
                incompatibleFish.forEach(function(fishId) {
                    self.removeFish(fishId);
                });
                
                alert('Some selected fish have been removed because they are not compatible with the selected tank type.');
            }
        },
        
        // Search fish
        searchFish: function(query) {
            // Clear previous results
            this.elements.fishResults.empty();
            
            if (query.length < 2) {
                this.elements.fishResults.hide();
                return;
            }
            
            query = query.toLowerCase();
            
            // Filter fish by query
            const results = this.filteredFish.filter(function(fish) {
                return fish.name.toLowerCase().includes(query) || 
                       fish.scientific_name.toLowerCase().includes(query) ||
                       (fish.common_names && fish.common_names.some(name => name.toLowerCase().includes(query)));
            });
            
            // Limit to 10 results
            const limitedResults = results.slice(0, 10);
            
            if (limitedResults.length === 0) {
                this.elements.fishResults.html('<div class="no-results">No fish found matching your search.</div>');
                this.elements.fishResults.show();
                return;
            }
            
            // Build results HTML
            let resultsHtml = '<ul class="fish-results-list">';
            
            limitedResults.forEach(fish => {
                // Skip if already selected
                if (this.selectedFish.includes(fish.id)) {
                    return;
                }
                
                resultsHtml += `<li class="fish-result-item" data-fish-id="${fish.id}">`;
                
                if (fish.image) {
                    resultsHtml += `<div class="fish-result-image"><img src="${fish.image}" alt="${fish.name}"></div>`;
                }
                
                resultsHtml += '<div class="fish-result-info">';
                resultsHtml += `<div class="fish-result-name">${fish.name}</div>`;
                resultsHtml += `<div class="fish-result-scientific">${fish.scientific_name}</div>`;
                resultsHtml += '</div>';
                resultsHtml += '</li>';
            });
            
            resultsHtml += '</ul>';
            
            // Display results
            this.elements.fishResults.html(resultsHtml);
            this.elements.fishResults.show();
        },
        
        // Add fish to selected list
        addFish: function(fishId) {
            // Check if fish is already selected
            if (this.selectedFish.includes(fishId)) {
                return;
            }
            
            // Get fish data
            const fish = this.fishDatabase[fishId];
            
            if (!fish) {
                return;
            }
            
            // Add to selected fish array
            this.selectedFish.push(fishId);
            
            // Create selected fish item
            let fishHtml = `<div class="selected-fish-item" data-fish-id="${fish.id}">`;
            
            if (fish.image) {
                fishHtml += `<div class="selected-fish-image"><img src="${fish.image}" alt="${fish.name}"></div>`;
            }
            
            fishHtml += '<div class="selected-fish-info">';
            fishHtml += `<div class="selected-fish-name">${fish.name}</div>`;
            fishHtml += `<div class="selected-fish-scientific">${fish.scientific_name}</div>`;
            fishHtml += '</div>';
            fishHtml += '<button class="remove-fish" title="Remove fish"><span class="remove-icon">×</span></button>';
            fishHtml += '</div>';
            
            // Add to selected fish container
            this.elements.selectedFishContainer.append(fishHtml);
            
            // Clear search input and results
            this.elements.searchInput.val('');
            this.elements.fishResults.hide();
            
            // Enable check compatibility button if at least 2 fish are selected
            if (this.selectedFish.length >= 2) {
                this.elements.checkCompatibilityBtn.prop('disabled', false);
            }
        },
        
        // Remove fish from selected list
        removeFish: function(fishId) {
            // Remove from selected fish array
            this.selectedFish = this.selectedFish.filter(id => id !== fishId);
            
            // Remove from DOM
            this.elements.selectedFishContainer.find(`.selected-fish-item[data-fish-id="${fishId}"]`).remove();
            
            // Disable check compatibility button if less than 2 fish are selected
            if (this.selectedFish.length < 2) {
                this.elements.checkCompatibilityBtn.prop('disabled', true);
            }
            
            // Hide results if no fish are selected
            if (this.selectedFish.length === 0) {
                this.elements.resultsContainer.hide();
            }
        },
        
        // Check compatibility between selected fish
        checkCompatibility: function() {
            // Validate form
            if (!this.validateForm()) {
                return;
            }
            
            const self = this;
            const tankSize = parseFloat(this.elements.tankSize.val());
            const tankType = this.elements.tankType.val();
            
            // Get selected fish data
            const selectedFishData = this.selectedFish.map(fishId => this.fishDatabase[fishId]);
            
            // Calculate compatibility matrix
            const compatibilityMatrix = this.calculateCompatibilityMatrix(selectedFishData);
            
            // Calculate space requirements
            const spaceRequirements = this.calculateSpaceRequirements(selectedFishData, tankSize);
            
            // Calculate water parameter compatibility
            const parameterCompatibility = this.calculateParameterCompatibility(selectedFishData);
            
            // Generate recommendations
            const recommendations = this.generateRecommendations(compatibilityMatrix, spaceRequirements, parameterCompatibility);
            
            // Display results
            this.displayResults(compatibilityMatrix, spaceRequirements, parameterCompatibility, recommendations);
            
            // Store current results
            this.currentResults = {
                date: new Date(),
                tankSize: tankSize,
                tankType: tankType,
                fish: selectedFishData,
                compatibilityMatrix: compatibilityMatrix,
                spaceRequirements: spaceRequirements,
                parameterCompatibility: parameterCompatibility,
                recommendations: recommendations
            };
            
            // Show results container
            this.elements.resultsContainer.show();
            
            // Scroll to results
            $('html, body').animate({
                scrollTop: this.elements.resultsContainer.offset().top - 50
            }, 500);
        },
        
        // Validate form
        validateForm: function() {
            let isValid = true;
            
            // Check if tank size is entered
            const tankSize = this.elements.tankSize.val().trim();
            if (tankSize === '') {
                this.showError(this.elements.tankSize, 'Please enter your tank size');
                isValid = false;
            } else if (isNaN(parseFloat(tankSize)) || parseFloat(tankSize) <= 0) {
                this.showError(this.elements.tankSize, 'Please enter a valid tank size');
                isValid = false;
            } else {
                this.clearError(this.elements.tankSize);
            }
            
            // Check if at least 2 fish are selected
            if (this.selectedFish.length < 2) {
                this.showError(this.elements.fishSelector, 'Please select at least 2 fish to check compatibility');
                isValid = false;
            } else {
                this.clearError(this.elements.fishSelector);
            }
            
            return isValid;
        },
        
        // Show error message
        showError: function($element, message) {
            const $formGroup = $element.closest('.form-group');
            $formGroup.addClass('has-error');
            
            // Remove any existing error message
            $formGroup.find('.error-message').remove();
            
            // Add new error message
            $formGroup.append(`<div class="error-message">${message}</div>`);
        },
        
        // Clear error message
        clearError: function($element) {
            const $formGroup = $element.closest('.form-group');
            $formGroup.removeClass('has-error');
            $formGroup.find('.error-message').remove();
        },
        
        // Calculate compatibility matrix between selected fish
        calculateCompatibilityMatrix: function(fishData) {
            const matrix = {};
            
            // Initialize matrix
            fishData.forEach(fish1 => {
                matrix[fish1.id] = {};
                
                fishData.forEach(fish2 => {
                    if (fish1.id === fish2.id) {
                        matrix[fish1.id][fish2.id] = {
                            status: 'self',
                            score: 10,
                            issues: []
                        };
                    } else {
                        // Default to compatible
                        matrix[fish1.id][fish2.id] = {
                            status: 'compatible',
                            score: 10,
                            issues: []
                        };
                    }
                });
            });
            
            // Check compatibility between each pair of fish
            fishData.forEach(fish1 => {
                fishData.forEach(fish2 => {
                    if (fish1.id === fish2.id) {
                        return; // Skip self
                    }
                    
                    // Check explicit compatibility
                    if (fish1.compatibility && fish1.compatibility.incompatible && fish1.compatibility.incompatible.includes(fish2.id)) {
                        matrix[fish1.id][fish2.id].status = 'incompatible';
                        matrix[fish1.id][fish2.id].score = 0;
                        matrix[fish1.id][fish2.id].issues.push('Known incompatibility');
                    } else if (fish1.compatibility && fish1.compatibility.caution && fish1.compatibility.caution.includes(fish2.id)) {
                        matrix[fish1.id][fish2.id].status = 'caution';
                        matrix[fish1.id][fish2.id].score = 5;
                        matrix[fish1.id][fish2.id].issues.push('Potential issues');
                    }
                    
                    // Check temperament compatibility
                    if (fish1.temperament === 'aggressive' && fish2.temperament === 'peaceful') {
                        matrix[fish1.id][fish2.id].status = fish1.size > fish2.size ? 'incompatible' : 'caution';
                        matrix[fish1.id][fish2.id].score -= fish1.size > fish2.size ? 10 : 3;
                        matrix[fish1.id][fish2.id].issues.push('Temperament mismatch');
                    }
                    
                    // Check size compatibility (if one fish is more than 3x larger)
                    if (fish1.size > fish2.size * 3 && fish1.temperament !== 'peaceful') {
                        matrix[fish1.id][fish2.id].status = 'caution';
                        matrix[fish1.id][fish2.id].score -= 3;
                        matrix[fish1.id][fish2.id].issues.push('Size difference');
                    }
                    
                    // Check feeding habits
                    if (fish1.diet === 'carnivore' && fish2.size < fish1.size * 0.5) {
                        matrix[fish1.id][fish2.id].status = 'incompatible';
                        matrix[fish1.id][fish2.id].score -= 8;
                        matrix[fish1.id][fish2.id].issues.push('Predator/prey risk');
                    }
                    
                    // Check water level preferences
                    if (fish1.water_level && fish2.water_level && 
                        fish1.water_level !== 'all' && fish2.water_level !== 'all' && 
                        fish1.water_level !== fish2.water_level) {
                        // Different water levels is actually good - less competition
                        matrix[fish1.id][fish2.id].score += 1;
                    }
                    
                    // Normalize score
                    matrix[fish1.id][fish2.id].score = Math.max(0, Math.min(10, matrix[fish1.id][fish2.id].score));
                    
                    // Update status based on final score
                    if (matrix[fish1.id][fish2.id].score === 0) {
                        matrix[fish1.id][fish2.id].status = 'incompatible';
                    } else if (matrix[fish1.id][fish2.id].score < 7) {
                        matrix[fish1.id][fish2.id].status = 'caution';
                    } else {
                        matrix[fish1.id][fish2.id].status = 'compatible';
                    }
                });
            });
            
            return matrix;
        },
        
        // Calculate space requirements
        calculateSpaceRequirements: function(fishData, tankSize) {
            let totalSpaceNeeded = 0;
            const fishSpaceNeeds = {};
            
            // Calculate space needed for each fish
            fishData.forEach(fish => {
                // Base space calculation on fish size and quantity
                const spacePerFish = Math.pow(fish.size, 1.5) * 10; // Cubic inches per inch of fish
                const totalFishSpace = spacePerFish * fish.min_group;
                
                fishSpaceNeeds[fish.id] = {
                    spacePerFish: spacePerFish,
                    totalSpace: totalFishSpace,
                    minGroup: fish.min_group
                };
                
                totalSpaceNeeded += totalFishSpace;
            });
            
            // Convert tank size from gallons to cubic inches
            const tankSpaceInCubicInches = tankSize * 231; // 1 gallon = 231 cubic inches
            
            // Calculate space utilization
            const spaceUtilization = (totalSpaceNeeded / tankSpaceInCubicInches) * 100;
            
            return {
                tankSize: tankSize,
                tankSpaceInCubicInches: tankSpaceInCubicInches,
                totalSpaceNeeded: totalSpaceNeeded,
                spaceUtilization: spaceUtilization,
                fishSpaceNeeds: fishSpaceNeeds,
                status: spaceUtilization > 100 ? 'overstocked' : (spaceUtilization > 80 ? 'full' : 'adequate')
            };
        },
        
        // Calculate water parameter compatibility
        calculateParameterCompatibility: function(fishData) {
            // Initialize with extreme values
            let phMin = 0, phMax = 14;
            let tempMin = 0, tempMax = 100;
            let hardnessMin = 0, hardnessMax = 30;
            
            // Find overlapping parameter ranges
            fishData.forEach(fish => {
                phMin = Math.max(phMin, fish.parameters.ph.min);
                phMax = Math.min(phMax, fish.parameters.ph.max);
                
                tempMin = Math.max(tempMin, fish.parameters.temperature.min);
                tempMax = Math.min(tempMax, fish.parameters.temperature.max);
                
                hardnessMin = Math.max(hardnessMin, fish.parameters.hardness.min);
                hardnessMax = Math.min(hardnessMax, fish.parameters.hardness.max);
            });
            
            // Check if ranges overlap
            const phCompatible = phMin <= phMax;
            const tempCompatible = tempMin <= tempMax;
            const hardnessCompatible = hardnessMin <= hardnessMax;
            
            // Calculate overall parameter compatibility
            const parameterIssues = [];
            
            if (!phCompatible) {
                parameterIssues.push('pH ranges do not overlap');
            }
            
            if (!tempCompatible) {
                parameterIssues.push('Temperature ranges do not overlap');
            }
            
            if (!hardnessCompatible) {
                parameterIssues.push('Water hardness ranges do not overlap');
            }
            
            return {
                ph: {
                    min: phMin,
                    max: phMax,
                    compatible: phCompatible
                },
                temperature: {
                    min: tempMin,
                    max: tempMax,
                    compatible: tempCompatible
                },
                hardness: {
                    min: hardnessMin,
                    max: hardnessMax,
                    compatible: hardnessCompatible
                },
                issues: parameterIssues,
                compatible: phCompatible && tempCompatible && hardnessCompatible
            };
        },
        
        // Generate recommendations based on compatibility analysis
        generateRecommendations: function(compatibilityMatrix, spaceRequirements, parameterCompatibility) {
            const recommendations = [];
            
            // Check for incompatible fish pairs
            const incompatiblePairs = [];
            
            for (const fish1Id in compatibilityMatrix) {
                for (const fish2Id in compatibilityMatrix[fish1Id]) {
                    if (fish1Id === fish2Id) continue;
                    
                    if (compatibilityMatrix[fish1Id][fish2Id].status === 'incompatible') {
                        // Check if the reverse pair is already in the list
                        const reversePairExists = incompatiblePairs.some(pair => 
                            pair.fish1 === fish2Id && pair.fish2 === fish1Id
                        );
                        
                        if (!reversePairExists) {
                            incompatiblePairs.push({
                                fish1: fish1Id,
                                fish2: fish2Id,
                                issues: compatibilityMatrix[fish1Id][fish2Id].issues
                            });
                        }
                    }
                }
            }
            
            // Add incompatible pairs to recommendations
            if (incompatiblePairs.length > 0) {
                incompatiblePairs.forEach(pair => {
                    const fish1 = this.fishDatabase[pair.fish1];
                    const fish2 = this.fishDatabase[pair.fish2];
                    
                    recommendations.push({
                        type: 'incompatible',
                        priority: 'high',
                        message: `${fish1.name} and ${fish2.name} are not compatible. Issues: ${pair.issues.join(', ')}.`,
                        solution: `Consider removing either ${fish1.name} or ${fish2.name} from your selection.`
                    });
                });
            }
            
            // Check for overstocking
            if (spaceRequirements.status === 'overstocked') {
                recommendations.push({
                    type: 'overstocked',
                    priority: 'high',
                    message: `Your tank is overstocked. The selected fish require approximately ${Math.round(spaceRequirements.spaceUtilization)}% of your tank's capacity.`,
                    solution: 'Consider a larger tank or reduce the number of fish.'
                });
            } else if (spaceRequirements.status === 'full') {
                recommendations.push({
                    type: 'full',
                    priority: 'medium',
                    message: `Your tank is approaching full capacity at ${Math.round(spaceRequirements.spaceUtilization)}% utilization.`,
                    solution: 'Monitor fish growth and consider upgrading your tank in the future.'
                });
            }
            
            // Check for water parameter issues
            if (parameterCompatibility.issues.length > 0) {
                recommendations.push({
                    type: 'parameters',
                    priority: 'high',
                    message: `Water parameter incompatibility: ${parameterCompatibility.issues.join(', ')}.`,
                    solution: 'Select fish with more compatible water parameter requirements.'
                });
            }
            
            // Check for schooling fish with insufficient numbers
            this.selectedFish.forEach(fishId => {
                const fish = this.fishDatabase[fishId];
                if (fish.min_group > 1) {
                    recommendations.push({
                        type: 'schooling',
                        priority: 'medium',
                        message: `${fish.name} is a schooling fish and should be kept in groups of at least ${fish.min_group}.`,
                        solution: `Add more ${fish.name} to create a proper school.`
                    });
                }
            });
            
            return recommendations;
        },
        
        // Display compatibility results
        displayResults: function(compatibilityMatrix, spaceRequirements, parameterCompatibility, recommendations) {
            // Build compatibility matrix HTML
            let matrixHtml = '<div class="compatibility-matrix-container">';
            matrixHtml += '<h3>Fish Compatibility Matrix</h3>';
            matrixHtml += '<table class="compatibility-matrix-table">';
            
            // Header row with fish names
            matrixHtml += '<tr><th></th>';
            this.selectedFish.forEach(fishId => {
                const fish = this.fishDatabase[fishId];
                matrixHtml += `<th class="fish-header"><div class="fish-header-name">${fish.name}</div></th>`;
            });
            matrixHtml += '</tr>';
            
            // Matrix rows
            this.selectedFish.forEach(fish1Id => {
                const fish1 = this.fishDatabase[fish1Id];
                matrixHtml += '<tr>';
                matrixHtml += `<th class="fish-header"><div class="fish-header-name">${fish1.name}</div></th>`;
                
                this.selectedFish.forEach(fish2Id => {
                    const compatibility = compatibilityMatrix[fish1Id][fish2Id];
                    let cellClass = '';
                    let cellContent = '';
                    
                    if (fish1Id === fish2Id) {
                        cellClass = 'self';
                        cellContent = '—';
                    } else {
                        cellClass = compatibility.status;
                        
                        if (compatibility.status === 'compatible') {
                            cellContent = '<span class="compatibility-icon compatible">✓</span>';
                        } else if (compatibility.status === 'caution') {
                            cellContent = '<span class="compatibility-icon caution">!</span>';
                        } else {
                            cellContent = '<span class="compatibility-icon incompatible">✗</span>';
                        }
                        
                        if (compatibility.issues.length > 0) {
                            cellContent += `<div class="compatibility-tooltip">${compatibility.issues.join('<br>')}</div>`;
                        }
                    }
                    
                    matrixHtml += `<td class="compatibility-cell ${cellClass}">${cellContent}</td>`;
                });
                
                matrixHtml += '</tr>';
            });
            
            matrixHtml += '</table>';
            
            // Add legend
            matrixHtml += '<div class="compatibility-legend">';
            matrixHtml += '<div class="legend-item"><span class="legend-icon compatible">✓</span> Compatible</div>';
            matrixHtml += '<div class="legend-item"><span class="legend-icon caution">!</span> Caution</div>';
            matrixHtml += '<div class="legend-item"><span class="legend-icon incompatible">✗</span> Incompatible</div>';
            matrixHtml += '</div>';
            
            matrixHtml += '</div>';
            
            // Build space requirements HTML
            let spaceHtml = '<div class="space-requirements-container">';
            spaceHtml += '<h3>Tank Space Analysis</h3>';
            
            // Tank utilization gauge
            const utilizationPercent = Math.min(100, Math.round(spaceRequirements.spaceUtilization));
            let gaugeClass = 'adequate';
            
            if (utilizationPercent > 100) {
                gaugeClass = 'overstocked';
            } else if (utilizationPercent > 80) {
                gaugeClass = 'full';
            }
            
            spaceHtml += '<div class="tank-utilization">';
            spaceHtml += `<div class="utilization-label">Tank Utilization: ${utilizationPercent}%</div>`;
            spaceHtml += '<div class="utilization-gauge-container">';
            spaceHtml += `<div class="utilization-gauge ${gaugeClass}" style="width: ${Math.min(100, utilizationPercent)}%"></div>`;
            spaceHtml += '</div>';
            spaceHtml += '</div>';
            
            // Fish space requirements table
            spaceHtml += '<table class="space-requirements-table">';
            spaceHtml += '<tr><th>Fish</th><th>Minimum Group</th><th>Space Required</th></tr>';
            
            this.selectedFish.forEach(fishId => {
                const fish = this.fishDatabase[fishId];
                const spaceNeeds = spaceRequirements.fishSpaceNeeds[fishId];
                const gallonsNeeded = (spaceNeeds.totalSpace / 231).toFixed(1); // Convert cubic inches to gallons
                
                spaceHtml += '<tr>';
                spaceHtml += `<td>${fish.name}</td>`;
                spaceHtml += `<td>${spaceNeeds.minGroup}</td>`;
                spaceHtml += `<td>${gallonsNeeded} gallons</td>`;
                spaceHtml += '</tr>';
            });
            
            spaceHtml += '</table>';
            spaceHtml += '</div>';
            
            // Build water parameters HTML
            let paramsHtml = '<div class="water-parameters-container">';
            paramsHtml += '<h3>Water Parameter Compatibility</h3>';
            
            // Parameter compatibility table
            paramsHtml += '<table class="parameter-compatibility-table">';
            paramsHtml += '<tr><th>Parameter</th><th>Compatible Range</th><th>Status</th></tr>';
            
            // pH row
            paramsHtml += '<tr>';
            paramsHtml += '<td>pH</td>';
            
            if (parameterCompatibility.ph.compatible) {
                paramsHtml += `<td>${parameterCompatibility.ph.min.toFixed(1)} - ${parameterCompatibility.ph.max.toFixed(1)}</td>`;
                paramsHtml += '<td class="compatible">Compatible</td>';
            } else {
                paramsHtml += '<td>No compatible range</td>';
                paramsHtml += '<td class="incompatible">Incompatible</td>';
            }
            paramsHtml += '</tr>';
            
            // Temperature row
            paramsHtml += '<tr>';
            paramsHtml += '<td>Temperature</td>';
            
            if (parameterCompatibility.temperature.compatible) {
                paramsHtml += `<td>${parameterCompatibility.temperature.min.toFixed(1)}°F - ${parameterCompatibility.temperature.max.toFixed(1)}°F</td>`;
                paramsHtml += '<td class="compatible">Compatible</td>';
            } else {
                paramsHtml += '<td>No compatible range</td>';
                paramsHtml += '<td class="incompatible">Incompatible</td>';
            }
            paramsHtml += '</tr>';
            
            // Hardness row
            paramsHtml += '<tr>';
            paramsHtml += '<td>Hardness</td>';
            
            if (parameterCompatibility.hardness.compatible) {
                paramsHtml += `<td>${parameterCompatibility.hardness.min.toFixed(1)} dGH - ${parameterCompatibility.hardness.max.toFixed(1)} dGH</td>`;
                paramsHtml += '<td class="compatible">Compatible</td>';
            } else {
                paramsHtml += '<td>No compatible range</td>';
                paramsHtml += '<td class="incompatible">Incompatible</td>';
            }
            paramsHtml += '</tr>';
            
            paramsHtml += '</table>';
            paramsHtml += '</div>';
            
            // Build recommendations HTML
            let recommendationsHtml = '';
            
            if (recommendations.length > 0) {
                recommendationsHtml += '<div class="recommendations-container">';
                recommendationsHtml += '<h3>Recommendations</h3>';
                recommendationsHtml += '<ul class="recommendations-list">';
                
                // Sort recommendations by priority
                const sortedRecommendations = [...recommendations].sort((a, b) => {
                    const priorityOrder = { high: 0, medium: 1, low: 2 };
                    return priorityOrder[a.priority] - priorityOrder[b.priority];
                });
                
                sortedRecommendations.forEach(rec => {
                    recommendationsHtml += `<li class="recommendation-item ${rec.type} ${rec.priority}">`;
                    recommendationsHtml += `<div class="recommendation-message">${rec.message}</div>`;
                    recommendationsHtml += `<div class="recommendation-solution">${rec.solution}</div>`;
                    recommendationsHtml += '</li>';
                });
                
                recommendationsHtml += '</ul>';
                recommendationsHtml += '</div>';
            } else {
                recommendationsHtml += '<div class="recommendations-container all-compatible">';
                recommendationsHtml += '<h3>All Compatible!</h3>';
                recommendationsHtml += '<p>Your selected fish are compatible with each other and suitable for your tank size.</p>';
                recommendationsHtml += '</div>';
            }
            
            // Update the DOM
            this.elements.compatibilityMatrix.html(matrixHtml + spaceHtml + paramsHtml);
            this.elements.recommendationsContainer.html(recommendationsHtml);
        },
        
        // Reset the form
        resetForm: function() {
            this.elements.form[0].reset();
            this.elements.selectedFishContainer.empty();
            this.elements.resultsContainer.hide();
            this.selectedFish = [];
            this.elements.checkCompatibilityBtn.prop('disabled', true);
            this.filterFishByTankType();
        },
        
        // Save results as PDF
        saveResults: function() {
            if (!this.currentResults) return;
            
            // Show loading indicator
            const $saveBtn = this.elements.saveBtn;
            const originalText = $saveBtn.text();
            $saveBtn.text('Generating PDF...').prop('disabled', true);
            
            // Prepare data for PDF generation
            const pdfData = {
                date: new Date().toISOString(),
                tankSize: this.currentResults.tankSize,
                tankType: this.currentResults.tankType,
                fish: this.currentResults.fish.map(fish => fish.id),
                compatibilityMatrix: this.currentResults.compatibilityMatrix,
                spaceRequirements: this.currentResults.spaceRequirements,
                parameterCompatibility: this.currentResults.parameterCompatibility,
                recommendations: this.currentResults.recommendations
            };
            
            // Send AJAX request to generate PDF
            $.ajax({
                url: aqualuxe_compatibility.ajax_url,
                type: 'post',
                data: {
                    action: 'aqualuxe_generate_compatibility_pdf',
                    security: aqualuxe_compatibility.nonce,
                    data: JSON.stringify(pdfData)
                },
                success: function(response) {
                    $saveBtn.text(originalText).prop('disabled', false);
                    
                    if (response.success && response.data.pdf_url) {
                        // Create temporary link and trigger download
                        const link = document.createElement('a');
                        link.href = response.data.pdf_url;
                        link.download = response.data.filename || 'fish-compatibility.pdf';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    } else {
                        alert('Error generating PDF. Please try again.');
                    }
                },
                error: function() {
                    $saveBtn.text(originalText).prop('disabled', false);
                    alert('Error connecting to server. Please try again.');
                }
            });
        }
    };
    
    // Initialize compatibility checker when document is ready
    $(document).ready(function() {
        CompatibilityChecker.init();
    });

})(jQuery);