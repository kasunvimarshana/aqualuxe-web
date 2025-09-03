/**
 * ================================================================
 * AQUALUXE BUSINESS MODULE JAVASCRIPT
 * ================================================================
 * 
 * Frontend functionality for AquaLuxe's comprehensive business
 * operations including wholesale, retail, trading, export, and services
 */

(function($) {
    'use strict';

    /**
     * AquaLuxe Business Module Class
     */
    class AquaLuxeBusinessModule {
        constructor() {
            this.init();
        }

        init() {
            this.bindEvents();
            this.initializeComponents();
            this.loadInitialData();
        }

        bindEvents() {
            // Dashboard navigation
            $(document).on('click', '.business-nav-item', this.handleTabSwitch.bind(this));
            
            // Product filtering
            $(document).on('change', '.filter-option input', this.handleProductFilter.bind(this));
            $(document).on('input', '#price-min, #price-max', this.handlePriceRange.bind(this));
            $(document).on('change', '#sort-products', this.handleProductSort.bind(this));
            
            // Product actions
            $(document).on('click', '.pricing-selector button', this.handlePricingModel.bind(this));
            $(document).on('click', '.product-actions .btn', this.handleProductAction.bind(this));
            
            // Trading system
            $(document).on('click', '.trading-tab', this.handleTradingTab.bind(this));
            $(document).on('click', '#create-trade-btn', this.showTradeModal.bind(this));
            $(document).on('submit', '#trade-form', this.submitTradeRequest.bind(this));
            
            // Service booking
            $(document).on('click', '.service-type', this.selectServiceType.bind(this));
            $(document).on('submit', '#service-booking-form', this.submitServiceBooking.bind(this));
            
            // Export tracking
            $(document).on('click', '.track-shipment', this.showShipmentTracking.bind(this));
            
            // Wholesale applications
            $(document).on('click', '.wholesale-tier .btn', this.handleWholesaleApplication.bind(this));
            
            // Quick actions
            $(document).on('click', '.action-btn', this.handleQuickAction.bind(this));
        }

        initializeComponents() {
            // Initialize price range sliders
            this.initPriceRangeSliders();
            
            // Initialize date pickers
            this.initDatePickers();
            
            // Initialize tooltips
            this.initTooltips();
            
            // Initialize real-time updates
            this.initRealTimeUpdates();
        }

        loadInitialData() {
            // Load dashboard statistics
            this.loadDashboardStats();
            
            // Load recent activity
            this.loadRecentActivity();
            
            // Load default product grid
            this.loadProducts();
        }

        /**
         * Dashboard Statistics
         */
        loadDashboardStats() {
            $.ajax({
                url: aqualuxe_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_dashboard_stats',
                    nonce: aqualuxe_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.updateStatsDisplay(response.data);
                    }
                },
                error: (xhr, status, error) => {
                    console.error('Failed to load dashboard stats:', error);
                }
            });
        }

        updateStatsDisplay(stats) {
            $('#total-fish-species').text(stats.fish_species || 0);
            $('#total-plants').text(stats.plants || 0);
            $('#monthly-revenue').text('$' + (stats.revenue || 0).toLocaleString());
            $('#pending-orders').text(stats.pending_orders || 0);
            
            // Update export stats if available
            if (stats.export) {
                $('#total-exports').text(stats.export.total || 0);
                $('#countries-served').text(stats.export.countries || 0);
                $('#export-revenue').text('$' + (stats.export.revenue || 0).toLocaleString());
            }
        }

        /**
         * Tab Navigation
         */
        handleTabSwitch(e) {
            e.preventDefault();
            
            const $tab = $(e.currentTarget);
            const tabId = $tab.data('tab');
            
            // Update navigation
            $('.business-nav-item').removeClass('active');
            $tab.addClass('active');
            
            // Show tab content
            $('.tab-content').removeClass('active');
            $('#' + tabId).addClass('active');
            
            // Load tab-specific content
            this.loadTabContent(tabId);
        }

        loadTabContent(tabId) {
            switch(tabId) {
                case 'overview':
                    this.loadOverviewContent();
                    break;
                case 'wholesale':
                    this.loadWholesaleContent();
                    break;
                case 'retail':
                    this.loadRetailContent();
                    break;
                case 'trading':
                    this.loadTradingContent();
                    break;
                case 'export':
                    this.loadExportContent();
                    break;
                case 'services':
                    this.loadServicesContent();
                    break;
            }
        }

        /**
         * Product Management
         */
        loadProducts(filters = {}) {
            const $grid = $('#products-grid');
            $grid.html('<div class="loading-spinner">Loading products...</div>');
            
            $.ajax({
                url: aqualuxe_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_load_products',
                    filters: filters,
                    nonce: aqualuxe_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderProducts(response.data.products);
                        this.updateResultsCount(response.data.count);
                        this.renderPagination(response.data.pagination);
                    }
                },
                error: (xhr, status, error) => {
                    $grid.html('<div class="error-message">Failed to load products</div>');
                }
            });
        }

        renderProducts(products) {
            const $grid = $('#products-grid');
            
            if (!products || products.length === 0) {
                $grid.html('<div class="no-products">No products found</div>');
                return;
            }
            
            let html = '';
            products.forEach(product => {
                html += this.renderProductCard(product);
            });
            
            $grid.html(html);
        }

        renderProductCard(product) {
            const badges = this.renderProductBadges(product.badges || []);
            const pricing = this.renderProductPricing(product.pricing || {});
            
            return `
                <div class="product-card" data-product-id="${product.id}">
                    <div class="product-card-image">
                        <img src="${product.image || '/assets/images/placeholder-fish.jpg'}" 
                             alt="${product.title}" 
                             loading="lazy">
                        ${badges}
                    </div>
                    <div class="product-info">
                        <div class="product-category">${product.category}</div>
                        <h3 class="product-title">
                            <a href="${product.url}">${product.title}</a>
                        </h3>
                        <div class="product-specs">
                            ${product.specs.map(spec => `<span class="product-spec">${spec}</span>`).join('')}
                        </div>
                        ${pricing}
                        <div class="product-actions">
                            <button class="btn btn-primary" data-action="add-to-cart" data-product-id="${product.id}">
                                Add to Cart
                            </button>
                            <button class="btn btn-outline" data-action="quick-view" data-product-id="${product.id}">
                                Quick View
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }

        renderProductBadges(badges) {
            if (!badges.length) return '';
            
            return `
                <div class="product-badges">
                    ${badges.map(badge => `<span class="product-badge product-badge-${badge.type}">${badge.text}</span>`).join('')}
                </div>
            `;
        }

        renderProductPricing(pricing) {
            const models = ['retail', 'wholesale', 'bulk'];
            
            return `
                <div class="product-pricing">
                    <div class="pricing-selector">
                        ${models.map(model => `
                            <button type="button" 
                                    class="pricing-model ${model === 'retail' ? 'active' : ''}" 
                                    data-model="${model}">
                                ${model.charAt(0).toUpperCase() + model.slice(1)}
                            </button>
                        `).join('')}
                    </div>
                    <div class="price-display" data-model="retail">
                        <span class="price-current">$${pricing.retail?.current || '0.00'}</span>
                        ${pricing.retail?.original ? `<span class="price-original">$${pricing.retail.original}</span>` : ''}
                        ${pricing.retail?.discount ? `<span class="price-discount">${pricing.retail.discount}% off</span>` : ''}
                    </div>
                    <div class="price-display" data-model="wholesale" style="display: none;">
                        <span class="price-current">$${pricing.wholesale?.current || '0.00'}</span>
                        <div class="price-notes">${pricing.wholesale?.notes || 'Wholesale pricing available'}</div>
                    </div>
                    <div class="price-display" data-model="bulk" style="display: none;">
                        <span class="price-current">$${pricing.bulk?.current || '0.00'}</span>
                        <div class="price-notes">${pricing.bulk?.notes || 'Bulk discount applied'}</div>
                    </div>
                </div>
            `;
        }

        handlePricingModel(e) {
            const $btn = $(e.currentTarget);
            const model = $btn.data('model');
            const $card = $btn.closest('.product-card');
            
            // Update button states
            $card.find('.pricing-model').removeClass('active');
            $btn.addClass('active');
            
            // Show appropriate pricing
            $card.find('.price-display').hide();
            $card.find(`[data-model="${model}"]`).show();
        }

        handleProductFilter() {
            const filters = this.collectFilters();
            this.loadProducts(filters);
        }

        collectFilters() {
            const filters = {
                categories: [],
                price_min: $('#price-min').val(),
                price_max: $('#price-max').val(),
                availability: [],
                sort: $('#sort-products').val()
            };
            
            // Collect category filters
            $('.filter-option input[type="checkbox"]:checked').each(function() {
                const value = $(this).val();
                if (['fish_species', 'aquatic_plants', 'equipment'].includes(value)) {
                    filters.categories.push(value);
                } else {
                    filters.availability.push(value);
                }
            });
            
            return filters;
        }

        /**
         * Trading System
         */
        loadTradingContent() {
            this.loadTradeRequests('active');
        }

        loadTradeRequests(type = 'active') {
            $.ajax({
                url: aqualuxe_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_load_trades',
                    type: type,
                    nonce: aqualuxe_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderTradeRequests(response.data);
                    }
                }
            });
        }

        renderTradeRequests(trades) {
            const $container = $('#trade-requests');
            
            if (!trades || trades.length === 0) {
                $container.html('<div class="no-trades">No trade requests found</div>');
                return;
            }
            
            let html = '';
            trades.forEach(trade => {
                html += this.renderTradeRequest(trade);
            });
            
            $container.html(html);
        }

        renderTradeRequest(trade) {
            return `
                <div class="trade-request" data-trade-id="${trade.id}">
                    <div class="trade-user-avatar">
                        ${trade.user.avatar || trade.user.name.charAt(0)}
                    </div>
                    <div class="trade-details">
                        <div class="trade-user-name">${trade.user.name}</div>
                        <div class="trade-description">${trade.description}</div>
                        <div class="trade-items">
                            <div class="trade-section">
                                <div class="trade-section-title">Offering</div>
                                <div class="trade-item-list">
                                    ${trade.offering.map(item => `<span class="trade-item">${item}</span>`).join('')}
                                </div>
                            </div>
                            <div class="trade-section">
                                <div class="trade-section-title">Seeking</div>
                                <div class="trade-item-list">
                                    ${trade.seeking.map(item => `<span class="trade-item">${item}</span>`).join('')}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="trade-actions">
                        <span class="trade-status trade-status-${trade.status}">${trade.status}</span>
                        ${trade.can_respond ? `
                            <button class="btn btn-sm btn-primary" data-action="respond-trade" data-trade-id="${trade.id}">
                                Respond
                            </button>
                        ` : ''}
                    </div>
                </div>
            `;
        }

        handleTradingTab(e) {
            const $tab = $(e.currentTarget);
            const type = $tab.data('tab');
            
            $('.trading-tab').removeClass('active');
            $tab.addClass('active');
            
            this.loadTradeRequests(type);
        }

        showTradeModal() {
            // Implementation for trade creation modal
            this.createModal('create-trade', this.renderTradeForm(), {
                title: 'Create Trade Request',
                size: 'large'
            });
        }

        renderTradeForm() {
            return `
                <form id="trade-form">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>What are you offering?</h4>
                            <div class="trade-items-input">
                                <input type="text" class="form-control" placeholder="Add item..." data-field="offering">
                                <div class="selected-items" data-field="offering"></div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h4>What are you seeking?</h4>
                            <div class="trade-items-input">
                                <input type="text" class="form-control" placeholder="Add item..." data-field="seeking">
                                <div class="selected-items" data-field="seeking"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label for="trade-description">Description</label>
                        <textarea id="trade-description" class="form-control" rows="3" 
                                  placeholder="Describe your trade request..."></textarea>
                    </div>
                    <div class="mt-4 text-end">
                        <button type="button" class="btn btn-secondary me-2" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Create Trade Request</button>
                    </div>
                </form>
            `;
        }

        /**
         * Service Booking
         */
        selectServiceType(e) {
            const $type = $(e.currentTarget);
            
            $('.service-type').removeClass('selected');
            $type.addClass('selected');
            
            // Update form based on selected service
            const serviceType = $type.data('service');
            this.updateServiceForm(serviceType);
        }

        updateServiceForm(serviceType) {
            // Customize form fields based on service type
            const formCustomizations = {
                consultation: {
                    duration: '1-2 hours',
                    price: '$150/hour',
                    requirements: 'Photos of current setup recommended'
                },
                maintenance: {
                    duration: '2-4 hours',
                    price: '$200/visit',
                    requirements: 'Regular monthly or bi-weekly visits'
                },
                design: {
                    duration: '1-3 days',
                    price: '$500-2000',
                    requirements: 'Site visit required for measurements'
                },
                installation: {
                    duration: '1-2 days',
                    price: '$300-1500',
                    requirements: 'All equipment must be on-site'
                }
            };
            
            const config = formCustomizations[serviceType];
            if (config) {
                this.displayServiceInfo(config);
            }
        }

        submitServiceBooking(e) {
            e.preventDefault();
            
            const formData = {
                service_type: $('.service-type.selected').data('service'),
                service_date: $('#service-date').val(),
                service_time: $('#service-time').val(),
                service_notes: $('#service-notes').val()
            };
            
            $.ajax({
                url: aqualuxe_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_book_service',
                    booking_data: formData,
                    nonce: aqualuxe_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.showNotification('Service booked successfully!', 'success');
                        this.resetServiceForm();
                        this.loadUpcomingServices();
                    } else {
                        this.showNotification(response.data.message || 'Booking failed', 'error');
                    }
                }
            });
        }

        /**
         * Export Tracking
         */
        loadExportContent() {
            this.loadExportShipments();
            this.loadExportStats();
        }

        loadExportShipments() {
            $.ajax({
                url: aqualuxe_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_load_export_shipments',
                    nonce: aqualuxe_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.renderExportShipments(response.data);
                    }
                }
            });
        }

        showShipmentTracking(e) {
            const shipmentId = $(e.currentTarget).data('shipment-id');
            
            $.ajax({
                url: aqualuxe_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_shipment_tracking',
                    shipment_id: shipmentId,
                    nonce: aqualuxe_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        this.createModal('shipment-tracking', this.renderTrackingTimeline(response.data), {
                            title: `Tracking: ${shipmentId}`,
                            size: 'large'
                        });
                    }
                }
            });
        }

        renderTrackingTimeline(tracking) {
            return `
                <div class="export-tracking">
                    <div class="export-tracking-header">
                        <div class="tracking-number">${tracking.tracking_number}</div>
                        <div class="tracking-summary">
                            <div class="tracking-stat">
                                <div class="tracking-stat-value">${tracking.status}</div>
                                <div class="tracking-stat-label">Current Status</div>
                            </div>
                            <div class="tracking-stat">
                                <div class="tracking-stat-value">${tracking.destination}</div>
                                <div class="tracking-stat-label">Destination</div>
                            </div>
                            <div class="tracking-stat">
                                <div class="tracking-stat-value">${tracking.estimated_delivery}</div>
                                <div class="tracking-stat-label">Est. Delivery</div>
                            </div>
                        </div>
                    </div>
                    <div class="tracking-timeline">
                        ${tracking.timeline.map(event => `
                            <div class="timeline-item">
                                <div class="timeline-icon ${event.status}">
                                    ${event.icon}
                                </div>
                                <div class="timeline-content">
                                    <div class="timeline-title">${event.title}</div>
                                    <div class="timeline-description">${event.description}</div>
                                    <div class="timeline-date">${event.date}</div>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `;
        }

        /**
         * Utility Functions
         */
        createModal(id, content, options = {}) {
            const modalHtml = `
                <div class="modal fade" id="${id}" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-${options.size || 'md'}" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">${options.title || 'Modal'}</h5>
                                <button type="button" class="btn-close" data-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                ${content}
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Remove existing modal
            $(`#${id}`).remove();
            
            // Add new modal
            $('body').append(modalHtml);
            
            // Show modal
            $(`#${id}`).modal('show');
        }

        showNotification(message, type = 'info') {
            const notification = `
                <div class="notification notification-${type}">
                    <div class="notification-content">
                        <span class="notification-message">${message}</span>
                        <button class="notification-close">&times;</button>
                    </div>
                </div>
            `;
            
            $('body').append(notification);
            
            // Auto-hide after 5 seconds
            setTimeout(() => {
                $('.notification').fadeOut();
            }, 5000);
        }

        initPriceRangeSliders() {
            // Initialize custom price range sliders
            $('#price-min, #price-max').on('input', (e) => {
                const min = $('#price-min').val();
                const max = $('#price-max').val();
                
                $('#price-min-value').text('$' + min);
                $('#price-max-value').text('$' + max);
            });
        }

        initDatePickers() {
            // Set minimum date to today
            const today = new Date().toISOString().split('T')[0];
            $('#service-date').attr('min', today);
        }

        initTooltips() {
            // Initialize tooltips for various elements
            $('[data-tooltip]').each(function() {
                $(this).attr('title', $(this).data('tooltip'));
            });
        }

        initRealTimeUpdates() {
            // Set up periodic updates for dashboard
            setInterval(() => {
                if ($('#overview').hasClass('active')) {
                    this.loadDashboardStats();
                }
            }, 30000); // Update every 30 seconds
        }

        // Load content for different tabs
        loadOverviewContent() {
            this.loadRecentActivity();
        }

        loadWholesaleContent() {
            this.loadWholesaleOrders();
        }

        loadRetailContent() {
            this.loadProducts();
        }

        loadServicesContent() {
            this.loadUpcomingServices();
        }

        loadRecentActivity() {
            $.ajax({
                url: aqualuxe_ajax.ajax_url,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_recent_activity',
                    nonce: aqualuxe_ajax.nonce
                },
                success: (response) => {
                    if (response.success) {
                        $('#recent-activity').html(this.renderActivityList(response.data));
                    }
                }
            });
        }

        renderActivityList(activities) {
            return activities.map(activity => `
                <div class="activity-item">
                    <div class="activity-icon">${activity.icon}</div>
                    <div class="activity-content">
                        <div class="activity-description">${activity.description}</div>
                        <div class="activity-time">${activity.time}</div>
                    </div>
                </div>
            `).join('');
        }

        handleQuickAction(e) {
            e.preventDefault();
            const action = $(e.currentTarget).data('action');
            
            switch(action) {
                case 'add-product':
                    this.showAddProductModal();
                    break;
                case 'create-trade':
                    this.showTradeModal();
                    break;
                case 'book-service':
                    $('.business-nav-item[data-tab="services"]').click();
                    break;
                case 'export-request':
                    this.showExportRequestModal();
                    break;
            }
        }

        handleWholesaleApplication(e) {
            e.preventDefault();
            const tier = $(e.currentTarget).closest('.wholesale-tier').find('.tier-name').text();
            this.showWholesaleApplicationModal(tier);
        }
    }

    // Initialize the business module when document is ready
    $(document).ready(function() {
        window.aqualuxeBusiness = new AquaLuxeBusinessModule();
    });

})(jQuery);
