/**
 * Events Module Admin Scripts
 *
 * @package AquaLuxe
 * @subpackage Modules\Events
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Event Admin Handler
     */
    var EventAdmin = {
        /**
         * Initialize
         */
        init: function() {
            this.initCalendar();
            this.bindEvents();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            // Meta box events
            $(document).on('change', '#aqualuxe-event-start-date, #aqualuxe-event-end-date', this.validateDateRange);
            $(document).on('change', '#aqualuxe-event-cost', this.validateNumericInput);
            $(document).on('change', '#aqualuxe-event-capacity', this.validateNumericInput);
            $(document).on('change', '#aqualuxe-venue-capacity', this.validateNumericInput);
            
            // Modal events
            $(document).on('click', '.aqualuxe-modal-close, .aqualuxe-modal', this.closeModal);
            $(document).on('click', '.aqualuxe-modal-content', function(e) {
                e.stopPropagation();
            });
            
            // Event actions
            $(document).on('click', '.confirm-booking', this.handleStatusChange);
            $(document).on('click', '.complete-booking', this.handleStatusChange);
            $(document).on('click', '.cancel-booking', this.handleStatusChange);
            $(document).on('click', '.view-booking', this.handleViewBooking);
            
            // Calendar filters
            $(document).on('change', '#aqualuxe-admin-calendar-filter-category, #aqualuxe-admin-calendar-filter-venue', this.handleCalendarFilterChange);
        },

        /**
         * Initialize calendar
         */
        initCalendar: function() {
            var calendarEl = document.getElementById('aqualuxe-admin-calendar');
            
            if (!calendarEl) {
                return;
            }
            
            // Check if FullCalendar is available
            if (typeof FullCalendar === 'undefined') {
                return;
            }
            
            var categoryFilter = $('#aqualuxe-admin-calendar-filter-category');
            var venueFilter = $('#aqualuxe-admin-calendar-filter-venue');
            
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                },
                navLinks: true,
                editable: false,
                dayMaxEvents: true,
                events: function(info, successCallback, failureCallback) {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_get_calendar_events',
                            nonce: aqualuxeEventsAdmin.nonce,
                            start: info.startStr,
                            end: info.endStr,
                            category: categoryFilter.val(),
                            venue_id: venueFilter.val()
                        },
                        success: function(response) {
                            if (response.success) {
                                successCallback(response.data);
                            } else {
                                failureCallback(response.data.message);
                            }
                        },
                        error: function() {
                            failureCallback('Error loading events.');
                        }
                    });
                },
                eventClick: function(info) {
                    info.jsEvent.preventDefault();
                    var eventId = info.event.id;
                    EventAdmin.viewEventDetails(eventId);
                }
            });
            
            calendar.render();
            
            // Store calendar instance
            this.calendar = calendar;
        },

        /**
         * Validate date range
         */
        validateDateRange: function() {
            var startDate = $('#aqualuxe-event-start-date').val();
            var endDate = $('#aqualuxe-event-end-date').val();
            
            if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
                alert('End date cannot be before start date.');
                $('#aqualuxe-event-end-date').val('');
            }
        },

        /**
         * Validate numeric input
         */
        validateNumericInput: function() {
            var value = $(this).val();
            
            if (value && (isNaN(value) || parseFloat(value) < 0)) {
                alert('Please enter a valid positive number.');
                $(this).val('');
            }
        },

        /**
         * Close modal
         */
        closeModal: function(e) {
            if (e.target === this) {
                $('.aqualuxe-modal').hide();
            }
        },

        /**
         * Handle status change
         * 
         * @param {object} e Event object
         */
        handleStatusChange: function(e) {
            e.preventDefault();
            
            var $link = $(this);
            var url = $link.attr('href');
            var eventId = $link.data('event-id');
            var status = '';
            
            if ($link.hasClass('confirm-booking')) {
                status = 'confirmed';
            } else if ($link.hasClass('complete-booking')) {
                status = 'completed';
            } else if ($link.hasClass('cancel-booking')) {
                status = 'cancelled';
            }
            
            if (!status) {
                return;
            }
            
            // Confirm action
            if (!confirm('Are you sure you want to mark this event as ' + status + '?')) {
                return;
            }
            
            // Show loading
            $link.text('Processing...');
            
            // Send AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_update_event_status',
                    _wpnonce: aqualuxeEventsAdmin.nonce,
                    event_id: eventId,
                    status: status
                },
                success: function(response) {
                    if (response.success) {
                        // Reload page
                        window.location.reload();
                    } else {
                        alert(response.data.message || 'Error updating event status.');
                        $link.text($link.data('original-text'));
                    }
                },
                error: function() {
                    alert('Error updating event status.');
                    $link.text($link.data('original-text'));
                }
            });
        },

        /**
         * Handle view booking
         * 
         * @param {object} e Event object
         */
        handleViewBooking: function(e) {
            e.preventDefault();
            
            var $link = $(this);
            var eventId = $link.data('event-id');
            
            EventAdmin.viewEventDetails(eventId);
        },

        /**
         * View event details
         * 
         * @param {number} eventId 
         */
        viewEventDetails: function(eventId) {
            // Send AJAX request
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_event_details',
                    _wpnonce: aqualuxeEventsAdmin.nonce,
                    event_id: eventId
                },
                success: function(response) {
                    if (response.success) {
                        EventAdmin.showEventDetailsModal(response.data);
                    } else {
                        alert(response.data.message || 'Error loading event details.');
                    }
                },
                error: function() {
                    alert('Error loading event details.');
                }
            });
        },

        /**
         * Show event details modal
         * 
         * @param {object} event 
         */
        showEventDetailsModal: function(event) {
            // Create modal if it doesn't exist
            if (!$('#aqualuxe-event-details-modal').length) {
                $('body').append('<div id="aqualuxe-event-details-modal" class="aqualuxe-modal"><div class="aqualuxe-modal-content"><span class="aqualuxe-modal-close">&times;</span><div class="aqualuxe-modal-header"><h2>Event Details</h2></div><div class="aqualuxe-modal-body"></div><div class="aqualuxe-modal-footer"></div></div></div>');
            }
            
            // Build modal content
            var html = '<div class="aqualuxe-event-details">';
            
            // Event details
            html += '<div class="aqualuxe-event-detail"><span class="aqualuxe-event-detail-label">Title:</span> ' + event.title + '</div>';
            html += '<div class="aqualuxe-event-detail"><span class="aqualuxe-event-detail-label">Start Date:</span> ' + event.start_date + '</div>';
            
            if (event.end_date) {
                html += '<div class="aqualuxe-event-detail"><span class="aqualuxe-event-detail-label">End Date:</span> ' + event.end_date + '</div>';
            }
            
            html += '<div class="aqualuxe-event-detail"><span class="aqualuxe-event-detail-label">Status:</span> <span class="event-status status-' + event.status + '">' + event.status_label + '</span></div>';
            
            if (event.venue_name) {
                html += '<div class="aqualuxe-event-detail"><span class="aqualuxe-event-detail-label">Venue:</span> ' + event.venue_name + '</div>';
            }
            
            if (event.organizer) {
                html += '<div class="aqualuxe-event-detail"><span class="aqualuxe-event-detail-label">Organizer:</span> ' + event.organizer + '</div>';
            }
            
            html += '<div class="aqualuxe-event-detail"><span class="aqualuxe-event-detail-label">Cost:</span> ' + event.formatted_cost + '</div>';
            
            if (event.capacity) {
                html += '<div class="aqualuxe-event-detail"><span class="aqualuxe-event-detail-label">Capacity:</span> ' + event.capacity + '</div>';
            }
            
            html += '</div>';
            
            // Update modal content
            $('#aqualuxe-event-details-modal .aqualuxe-modal-body').html(html);
            
            // Update modal footer
            var footerHtml = '<a href="' + event.edit_url + '" class="button button-primary">Edit Event</a> ';
            
            if (event.status === 'open') {
                footerHtml += '<a href="#" class="button cancel-booking" data-event-id="' + event.id + '">Cancel Event</a>';
            } else if (event.status === 'cancelled') {
                footerHtml += '<a href="#" class="button confirm-booking" data-event-id="' + event.id + '">Reopen Event</a>';
            }
            
            $('#aqualuxe-event-details-modal .aqualuxe-modal-footer').html(footerHtml);
            
            // Show modal
            $('#aqualuxe-event-details-modal').show();
        },

        /**
         * Handle calendar filter change
         */
        handleCalendarFilterChange: function() {
            // If calendar is initialized, refetch events
            if (EventAdmin.calendar) {
                EventAdmin.calendar.refetchEvents();
            }
        }
    };

    /**
     * Venue Admin Handler
     */
    var VenueAdmin = {
        /**
         * Initialize
         */
        init: function() {
            this.initMap();
            this.bindEvents();
        },

        /**
         * Bind events
         */
        bindEvents: function() {
            $(document).on('change', '#aqualuxe-venue-address, #aqualuxe-venue-city, #aqualuxe-venue-state, #aqualuxe-venue-postal-code, #aqualuxe-venue-country', this.geocodeAddress);
            $(document).on('click', '#aqualuxe-venue-geocode', this.handleGeocodeClick);
        },

        /**
         * Initialize map
         */
        initMap: function() {
            // Check if Google Maps API is available
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                return;
            }
            
            var mapEl = document.getElementById('aqualuxe-venue-map');
            
            if (!mapEl) {
                return;
            }
            
            var lat = parseFloat($('#aqualuxe-venue-latitude').val()) || 0;
            var lng = parseFloat($('#aqualuxe-venue-longitude').val()) || 0;
            
            var mapOptions = {
                center: { lat: lat, lng: lng },
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            };
            
            this.map = new google.maps.Map(mapEl, mapOptions);
            
            this.marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: this.map,
                draggable: true
            });
            
            // Update latitude and longitude when marker is dragged
            google.maps.event.addListener(this.marker, 'dragend', function() {
                var position = VenueAdmin.marker.getPosition();
                $('#aqualuxe-venue-latitude').val(position.lat());
                $('#aqualuxe-venue-longitude').val(position.lng());
            });
        },

        /**
         * Geocode address
         */
        geocodeAddress: function() {
            // Check if Google Maps API is available
            if (typeof google === 'undefined' || typeof google.maps === 'undefined' || !VenueAdmin.map) {
                return;
            }
            
            var address = [
                $('#aqualuxe-venue-address').val(),
                $('#aqualuxe-venue-city').val(),
                $('#aqualuxe-venue-state').val(),
                $('#aqualuxe-venue-postal-code').val(),
                $('#aqualuxe-venue-country').val()
            ].filter(Boolean).join(', ');
            
            if (!address) {
                return;
            }
            
            var geocoder = new google.maps.Geocoder();
            
            geocoder.geocode({ 'address': address }, function(results, status) {
                if (status === google.maps.GeocoderStatus.OK) {
                    var location = results[0].geometry.location;
                    
                    VenueAdmin.map.setCenter(location);
                    VenueAdmin.marker.setPosition(location);
                    
                    $('#aqualuxe-venue-latitude').val(location.lat());
                    $('#aqualuxe-venue-longitude').val(location.lng());
                }
            });
        },

        /**
         * Handle geocode click
         * 
         * @param {object} e Event object
         */
        handleGeocodeClick: function(e) {
            e.preventDefault();
            VenueAdmin.geocodeAddress();
        }
    };

    /**
     * Document ready
     */
    $(document).ready(function() {
        EventAdmin.init();
        VenueAdmin.init();
    });

})(jQuery);