/**
 * Admin JavaScript for AquaLuxe Theme
 * 
 * Handles admin-specific functionality including meta boxes,
 * settings pages, and demo content import.
 */

(function($) {
    'use strict';

    // Admin object
    const AquaLuxeAdmin = {

        /**
         * Initialize admin functionality
         */
        init() {
            this.initMetaBoxes();
            this.initTaxonomyFields();
            this.initColorPickers();
            this.initMediaUploaders();
            this.initDemoImporter();
            this.initSettingsTabs();
            this.initDashboardWidgets();
            this.initBookingManager();
            
            // Trigger loaded event
            $(document).trigger('aqualuxe:admin-loaded');
        },

        /**
         * Initialize meta boxes
         */
        initMetaBoxes() {
            // Service meta box enhancements
            $('#aqualuxe_service_featured').on('change', function() {
                const $notice = $('#featured-service-notice');
                if ($(this).is(':checked')) {
                    if ($notice.length === 0) {
                        $(this).parent().after('<p id="featured-service-notice" class="description" style="color: #d63638;">This service will be highlighted on the homepage.</p>');
                    }
                } else {
                    $notice.remove();
                }
            });

            // Project gallery field enhancement
            $('#aqualuxe_project_gallery').on('input', function() {
                const value = $(this).val();
                const imageIds = value.split(',').filter(id => id.trim());
                const $preview = $('#gallery-preview');
                
                if (imageIds.length > 0 && $preview.length === 0) {
                    $(this).after('<div id="gallery-preview" class="gallery-preview" style="margin-top: 10px;"><small>Gallery will contain ' + imageIds.length + ' images</small></div>');
                } else if (imageIds.length === 0) {
                    $preview.remove();
                } else {
                    $preview.find('small').text('Gallery will contain ' + imageIds.length + ' images');
                }
            });

            // Event date validation
            $('#aqualuxe_event_start_date, #aqualuxe_event_end_date').on('change', function() {
                const startDate = new Date($('#aqualuxe_event_start_date').val());
                const endDate = new Date($('#aqualuxe_event_end_date').val());
                
                if (startDate && endDate && endDate < startDate) {
                    alert('End date cannot be before start date.');
                    $('#aqualuxe_event_end_date').val('');
                }
            });

            // Booking status change notifications
            $('#aqualuxe_booking_status').on('change', function() {
                const status = $(this).val();
                const customerEmail = $('#aqualuxe_booking_customer_email').val();
                
                if (customerEmail && ['confirmed', 'cancelled'].includes(status)) {
                    const sendNotification = confirm('Would you like to send an email notification to the customer?');
                    if (sendNotification) {
                        // This would trigger an AJAX request to send email
                        console.log('Sending notification email for status:', status);
                    }
                }
            });
        },

        /**
         * Initialize taxonomy fields
         */
        initTaxonomyFields() {
            // Icon picker for category fields
            if (typeof wp !== 'undefined' && wp.media) {
                // Add icon selection functionality
                $('.icon-picker').on('click', function(e) {
                    e.preventDefault();
                    
                    const button = $(this);
                    const input = button.siblings('input');
                    
                    // Create a simple icon picker modal
                    const icons = [
                        'fas fa-fish', 'fas fa-leaf', 'fas fa-tools', 'fas fa-flask',
                        'fas fa-water', 'fas fa-seedling', 'fas fa-cog', 'fas fa-heart',
                        'fas fa-star', 'fas fa-fire', 'fas fa-snowflake', 'fas fa-sun'
                    ];
                    
                    let modalHtml = '<div class="icon-picker-modal"><h3>Select an Icon</h3><div class="icon-grid">';
                    icons.forEach(icon => {
                        modalHtml += `<button type="button" class="icon-option" data-icon="${icon}"><i class="${icon}"></i></button>`;
                    });
                    modalHtml += '</div><button type="button" class="button icon-picker-close">Close</button></div>';
                    
                    $('body').append(modalHtml);
                    
                    $('.icon-option').on('click', function() {
                        const selectedIcon = $(this).data('icon');
                        input.val(selectedIcon);
                        $('.icon-picker-modal').remove();
                    });
                    
                    $('.icon-picker-close').on('click', function() {
                        $('.icon-picker-modal').remove();
                    });
                });
            }
        },

        /**
         * Initialize color pickers
         */
        initColorPickers() {
            if ($.fn.wpColorPicker) {
                $('.color-picker').wpColorPicker({
                    change: function(event, ui) {
                        const color = ui.color.toString();
                        $(this).val(color).trigger('change');
                    }
                });
            }
        },

        /**
         * Initialize media uploaders
         */
        initMediaUploaders() {
            if (typeof wp !== 'undefined' && wp.media) {
                $('.media-upload-button').on('click', function(e) {
                    e.preventDefault();
                    
                    const button = $(this);
                    const input = button.siblings('input');
                    const preview = button.siblings('.media-preview');
                    
                    const mediaUploader = wp.media({
                        title: 'Select Media',
                        button: {
                            text: 'Use this media'
                        },
                        multiple: false
                    });
                    
                    mediaUploader.on('select', function() {
                        const attachment = mediaUploader.state().get('selection').first().toJSON();
                        input.val(attachment.id);
                        
                        if (attachment.type === 'image') {
                            preview.html(`<img src="${attachment.sizes.thumbnail.url}" alt="" style="max-width: 100px;">`);
                        } else {
                            preview.html(`<p>${attachment.filename}</p>`);
                        }
                    });
                    
                    mediaUploader.open();
                });
                
                $('.media-remove-button').on('click', function(e) {
                    e.preventDefault();
                    $(this).siblings('input').val('');
                    $(this).siblings('.media-preview').empty();
                });
            }
        },

        /**
         * Initialize demo content importer
         */
        initDemoImporter() {
            let importInProgress = false;
            
            $('#aqualuxe-demo-import').on('click', function(e) {
                e.preventDefault();
                
                if (importInProgress) {
                    return;
                }
                
                const button = $(this);
                const originalText = button.text();
                const progressBar = $('#import-progress');
                const logContainer = $('#import-log');
                
                if (!confirm('This will import demo content and may overwrite existing data. Continue?')) {
                    return;
                }
                
                importInProgress = true;
                button.text('Importing...').prop('disabled', true);
                progressBar.show();
                logContainer.empty();
                
                // Start import process
                this.runDemoImport(0, function(success) {
                    importInProgress = false;
                    button.text(originalText).prop('disabled', false);
                    progressBar.hide();
                    
                    if (success) {
                        logContainer.append('<p style="color: green;">Import completed successfully!</p>');
                    } else {
                        logContainer.append('<p style="color: red;">Import failed. Please check the logs.</p>');
                    }
                });
            });
            
            $('#aqualuxe-demo-flush').on('click', function(e) {
                e.preventDefault();
                
                if (!confirm('This will delete ALL demo content and reset the site. This action cannot be undone. Continue?')) {
                    return;
                }
                
                const button = $(this);
                const originalText = button.text();
                
                button.text('Flushing...').prop('disabled', true);
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_flush_demo_content',
                        nonce: aqualuxe_admin.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Demo content flushed successfully!');
                            location.reload();
                        } else {
                            alert('Flush failed: ' + response.data);
                        }
                    },
                    error: function() {
                        alert('Flush failed due to a network error.');
                    },
                    complete: function() {
                        button.text(originalText).prop('disabled', false);
                    }
                });
            });
        },

        /**
         * Run demo import in steps
         */
        runDemoImport(step, callback) {
            const steps = [
                'posts',
                'pages',
                'product-categories',
                'products',
                'services',
                'events',
                'subscriptions',
                'auctions',
                'vendors',
                'franchise',
                'research',
                'testimonials',
                'team',
                'media',
                'menus',
                'customizer'
            ];
            
            if (step >= steps.length) {
                callback(true);
                return;
            }
            
            const currentStep = steps[step];
            const progressPercent = Math.round((step / steps.length) * 100);
            
            $('#import-progress .progress-bar').css('width', progressPercent + '%');
            $('#import-log').append(`<p>Importing ${currentStep}...</p>`);
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_import_demo_step',
                    step: currentStep,
                    nonce: aqualuxe_admin.nonce
                },
                success: (response) => {
                    if (response.success) {
                        $('#import-log').append(`<p style="color: green;">✓ ${currentStep} imported successfully</p>`);
                        this.runDemoImport(step + 1, callback);
                    } else {
                        $('#import-log').append(`<p style="color: red;">✗ Failed to import ${currentStep}: ${response.data}</p>`);
                        callback(false);
                    }
                },
                error: () => {
                    $('#import-log').append(`<p style="color: red;">✗ Network error importing ${currentStep}</p>`);
                    callback(false);
                }
            });
        },

        /**
         * Initialize settings tabs
         */
        initSettingsTabs() {
            $('.nav-tab').on('click', function(e) {
                e.preventDefault();
                
                const target = $(this).attr('href');
                
                // Update active tab
                $('.nav-tab').removeClass('nav-tab-active');
                $(this).addClass('nav-tab-active');
                
                // Show target section
                $('.settings-section').hide();
                $(target).show();
                
                // Update URL hash
                window.history.replaceState(null, null, target);
            });
            
            // Show active section on load
            const hash = window.location.hash;
            if (hash && $(hash).length) {
                $('.nav-tab[href="' + hash + '"]').click();
            } else {
                $('.nav-tab').first().click();
            }
        },

        /**
         * Initialize dashboard widgets
         */
        initDashboardWidgets() {
            // Refresh stats
            $('.refresh-stats').on('click', function(e) {
                e.preventDefault();
                
                const button = $(this);
                const widget = button.closest('.dashboard-widget');
                
                button.prop('disabled', true);
                widget.addClass('loading');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_refresh_stats',
                        nonce: aqualuxe_admin.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            widget.find('.stats-grid').html(response.data.html);
                        }
                    },
                    complete: function() {
                        button.prop('disabled', false);
                        widget.removeClass('loading');
                    }
                });
            });
        },

        /**
         * Initialize booking manager
         */
        initBookingManager() {
            // Quick status update
            $('.booking-status-quick').on('change', function() {
                const bookingId = $(this).data('booking-id');
                const newStatus = $(this).val();
                const row = $(this).closest('tr');
                
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_update_booking_status',
                        booking_id: bookingId,
                        status: newStatus,
                        nonce: aqualuxe_admin.nonce
                    },
                    success: function(response) {
                        if (response.success) {
                            row.find('.status-indicator').removeClass().addClass('status-indicator status-' + newStatus);
                            
                            // Show success message
                            const notice = $('<div class="notice notice-success is-dismissible"><p>Booking status updated successfully!</p></div>');
                            $('.wrap h1').after(notice);
                            
                            setTimeout(() => {
                                notice.fadeOut();
                            }, 3000);
                        } else {
                            alert('Failed to update booking status: ' + response.data);
                        }
                    },
                    error: function() {
                        alert('Failed to update booking status due to network error.');
                    }
                });
            });
            
            // Booking calendar integration
            if ($('#booking-calendar').length) {
                this.initBookingCalendar();
            }
        },

        /**
         * Initialize booking calendar
         */
        initBookingCalendar() {
            // This would integrate with a calendar library like FullCalendar
            // For now, we'll just add a placeholder
            $('#booking-calendar').html('<p>Calendar integration would be implemented here with FullCalendar or similar library.</p>');
        }
    };

    // Initialize when DOM is ready
    $(document).ready(function() {
        AquaLuxeAdmin.init();
    });

    // Export for use in other scripts
    window.AquaLuxeAdmin = AquaLuxeAdmin;

})(jQuery);