/**
 * Events Module Scripts
 *
 * @package AquaLuxe
 * @subpackage Modules\Events
 * @since 1.0.0
 */

(function($) {
    'use strict';

    /**
     * Event Calendar Handler
     */
    var EventCalendar = {
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
            $(document).on('change', '#aqualuxe-event-calendar-filter-category, #aqualuxe-event-calendar-filter-venue', this.handleFilterChange);
            $(document).on('click', '.aqualuxe-events-archive-view-toggle button', this.handleViewToggle);
            $(document).on('click', '.aqualuxe-event-action-button.add-to-calendar', this.handleAddToCalendar);
        },

        /**
         * Initialize calendar
         */
        initCalendar: function() {
            // Initialize FullCalendar if available
            if (typeof FullCalendar !== 'undefined') {
                this.initFullCalendar();
            }

            // Initialize mini calendar if available
            if ($('.aqualuxe-mini-calendar').length) {
                this.initMiniCalendar();
            }
        },

        /**
         * Initialize FullCalendar
         */
        initFullCalendar: function() {
            var calendarEl = document.getElementById('aqualuxe-event-calendar');
            
            if (!calendarEl) {
                return;
            }
            
            var categoryFilter = $('#aqualuxe-event-calendar-filter-category');
            var venueFilter = $('#aqualuxe-event-calendar-filter-venue');
            var defaultView = calendarEl.dataset.view || 'dayGridMonth';
            var height = calendarEl.dataset.height || 'auto';

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: defaultView,
                height: height,
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay,listMonth'
                },
                navLinks: true,
                editable: false,
                dayMaxEvents: true,
                events: function(info, successCallback, failureCallback) {
                    $.ajax({
                        url: aqualuxeEvents.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'aqualuxe_get_calendar_events',
                            nonce: aqualuxeEvents.nonce,
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
                            failureCallback(aqualuxeEvents.messages.errorLoadingEvents);
                        }
                    });
                },
                eventDidMount: function(info) {
                    // Add tooltip if tippy.js is available
                    if (typeof tippy !== 'undefined') {
                        tippy(info.el, {
                            content: '<strong>' + info.event.title + '</strong><br>' +
                                     (info.event.extendedProps.location ? info.event.extendedProps.location + '<br>' : '') +
                                     info.event.extendedProps.description,
                            allowHTML: true,
                            placement: 'top',
                            arrow: true,
                            theme: 'light'
                        });
                    }
                },
                eventClick: function(info) {
                    if (info.event.url) {
                        info.jsEvent.preventDefault();
                        window.location.href = info.event.url;
                    }
                }
            });

            calendar.render();
            
            // Store calendar instance
            this.calendar = calendar;
        },

        /**
         * Initialize mini calendar
         */
        initMiniCalendar: function() {
            $('.aqualuxe-mini-calendar').each(function() {
                var calendarEl = this;
                var category = $(calendarEl).data('category') || '';
                var venueId = $(calendarEl).data('venue-id') || 0;

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    headerToolbar: {
                        left: 'prev,next',
                        center: 'title',
                        right: ''
                    },
                    height: 'auto',
                    navLinks: true,
                    editable: false,
                    dayMaxEvents: 1,
                    events: function(info, successCallback, failureCallback) {
                        $.ajax({
                            url: aqualuxeEvents.ajaxUrl,
                            type: 'POST',
                            data: {
                                action: 'aqualuxe_get_calendar_events',
                                nonce: aqualuxeEvents.nonce,
                                start: info.startStr,
                                end: info.endStr,
                                category: category,
                                venue_id: venueId
                            },
                            success: function(response) {
                                if (response.success) {
                                    successCallback(response.data);
                                } else {
                                    failureCallback(response.data.message);
                                }
                            },
                            error: function() {
                                failureCallback(aqualuxeEvents.messages.errorLoadingEvents);
                            }
                        });
                    },
                    eventDidMount: function(info) {
                        // Add tooltip if tippy.js is available
                        if (typeof tippy !== 'undefined') {
                            tippy(info.el, {
                                content: info.event.title,
                                placement: 'top',
                                arrow: true,
                                theme: 'light'
                            });
                        }
                    },
                    eventClick: function(info) {
                        if (info.event.url) {
                            info.jsEvent.preventDefault();
                            window.location.href = info.event.url;
                        }
                    }
                });

                calendar.render();
            });
        },

        /**
         * Handle filter change
         */
        handleFilterChange: function() {
            var categoryFilter = $('#aqualuxe-event-calendar-filter-category');
            var venueFilter = $('#aqualuxe-event-calendar-filter-venue');
            
            // If FullCalendar is initialized, refetch events
            if (EventCalendar.calendar) {
                EventCalendar.calendar.refetchEvents();
            }
            
            // Update event list
            $.ajax({
                url: aqualuxeEvents.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_event_list',
                    nonce: aqualuxeEvents.nonce,
                    category: categoryFilter.val(),
                    venue_id: venueFilter.val(),
                    events_per_page: aqualuxeEvents.eventsPerPage || 10
                },
                success: function(response) {
                    if (response.success) {
                        $('#aqualuxe-event-list').html(response.data);
                    }
                }
            });
        },

        /**
         * Handle view toggle
         */
        handleViewToggle: function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var view = $this.data('view');
            
            // Update active button
            $('.aqualuxe-events-archive-view-toggle button').removeClass('active');
            $this.addClass('active');
            
            // Update view
            if (view === 'grid') {
                $('.aqualuxe-events-archive-list').hide();
                $('.aqualuxe-events-archive-grid').show();
            } else {
                $('.aqualuxe-events-archive-grid').hide();
                $('.aqualuxe-events-archive-list').show();
            }
        },

        /**
         * Handle add to calendar
         */
        handleAddToCalendar: function(e) {
            e.preventDefault();
            
            var $this = $(this);
            var type = $this.data('type');
            var url = $this.attr('href');
            
            if (type === 'google') {
                window.open(url, '_blank');
            } else if (type === 'ical') {
                window.location.href = url;
            }
        }
    };

    /**
     * Event Map Handler
     */
    var EventMap = {
        /**
         * Initialize
         */
        init: function() {
            this.initMaps();
        },

        /**
         * Initialize maps
         */
        initMaps: function() {
            // Check if Google Maps API is available
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                return;
            }
            
            // Initialize venue map
            if ($('#aqualuxe-event-venue-map').length) {
                this.initVenueMap();
            }
        },

        /**
         * Initialize venue map
         */
        initVenueMap: function() {
            var mapEl = document.getElementById('aqualuxe-event-venue-map');
            var lat = parseFloat(mapEl.dataset.lat);
            var lng = parseFloat(mapEl.dataset.lng);
            var title = mapEl.dataset.title;
            
            if (isNaN(lat) || isNaN(lng)) {
                return;
            }
            
            var mapOptions = {
                center: { lat: lat, lng: lng },
                zoom: 15,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                scrollwheel: false
            };
            
            var map = new google.maps.Map(mapEl, mapOptions);
            
            var marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                title: title
            });
            
            // Add info window if title is provided
            if (title) {
                var infoWindow = new google.maps.InfoWindow({
                    content: '<div class="aqualuxe-event-venue-map-info">' + title + '</div>'
                });
                
                marker.addListener('click', function() {
                    infoWindow.open(map, marker);
                });
            }
        }
    };

    /**
     * Document ready
     */
    $(document).ready(function() {
        EventCalendar.init();
        EventMap.init();
    });

})(jQuery);