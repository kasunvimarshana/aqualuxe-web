/**
 * Events Module Admin JavaScript
 *
 * @package AquaLuxe\Modules\Events
 */

(function($) {
    'use strict';

    /**
     * Events Admin
     */
    var AquaLuxeEventsAdmin = {
        /**
         * Initialize
         */
        init: function() {
            // Initialize datepickers
            AquaLuxeEventsAdmin.initDatepickers();
            
            // Initialize timepickers
            AquaLuxeEventsAdmin.initTimepickers();
            
            // Initialize Google Maps
            AquaLuxeEventsAdmin.initGoogleMaps();
            
            // Initialize ticket sorting
            AquaLuxeEventsAdmin.initTicketSorting();
            
            // Initialize registration actions
            AquaLuxeEventsAdmin.initRegistrationActions();
        },
        
        /**
         * Initialize datepickers
         */
        initDatepickers: function() {
            // Event dates
            $('#aqualuxe_event_start_date, #aqualuxe_event_end_date, #aqualuxe_event_registration_start_date, #aqualuxe_event_registration_end_date').datepicker({
                dateFormat: 'yy-mm-dd',
                firstDay: aqualuxeEventsAdmin.settings.firstDay,
                changeMonth: true,
                changeYear: true,
                yearRange: 'c-1:c+5'
            });
            
            // Ticket dates
            $(document).on('focus', '[name^="aqualuxe_tickets"][name$="[start_date]"], [name^="aqualuxe_tickets"][name$="[end_date]"]', function() {
                $(this).datepicker({
                    dateFormat: 'yy-mm-dd',
                    firstDay: aqualuxeEventsAdmin.settings.firstDay,
                    changeMonth: true,
                    changeYear: true,
                    yearRange: 'c-1:c+5'
                });
            });
        },
        
        /**
         * Initialize timepickers
         */
        initTimepickers: function() {
            // Check if timepicker is available
            if ($.fn.timepicker) {
                $('#aqualuxe_event_start_time, #aqualuxe_event_end_time').timepicker({
                    timeFormat: aqualuxeEventsAdmin.settings.timeFormat,
                    step: 15,
                    scrollDefault: 'now'
                });
            }
        },
        
        /**
         * Initialize Google Maps
         */
        initGoogleMaps: function() {
            // Check if Google Maps API is loaded
            if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                return;
            }
            
            // Check if map container exists
            var $mapContainer = $('#aqualuxe-venue-map');
            
            if (!$mapContainer.length) {
                return;
            }
            
            // Get coordinates
            var lat = parseFloat($('#aqualuxe_venue_latitude').val()) || 40.7128;
            var lng = parseFloat($('#aqualuxe_venue_longitude').val()) || -74.0060;
            
            // Initialize map
            var map = new google.maps.Map($mapContainer[0], {
                center: { lat: lat, lng: lng },
                zoom: 14
            });
            
            // Add marker
            var marker = new google.maps.Marker({
                position: { lat: lat, lng: lng },
                map: map,
                draggable: true
            });
            
            // Update coordinates when marker is dragged
            google.maps.event.addListener(marker, 'dragend', function(event) {
                $('#aqualuxe_venue_latitude').val(event.latLng.lat().toFixed(6));
                $('#aqualuxe_venue_longitude').val(event.latLng.lng().toFixed(6));
            });
            
            // Add click event to map
            google.maps.event.addListener(map, 'click', function(event) {
                marker.setPosition(event.latLng);
                $('#aqualuxe_venue_latitude').val(event.latLng.lat().toFixed(6));
                $('#aqualuxe_venue_longitude').val(event.latLng.lng().toFixed(6));
            });
            
            // Add search button functionality
            $('#aqualuxe-venue-map-search').on('click', function(e) {
                e.preventDefault();
                
                var address = [
                    $('#aqualuxe_venue_address').val(),
                    $('#aqualuxe_venue_city').val(),
                    $('#aqualuxe_venue_state').val(),
                    $('#aqualuxe_venue_zip').val(),
                    $('#aqualuxe_venue_country').val()
                ].filter(Boolean).join(', ');
                
                if (!address) {
                    alert(aqualuxeEventsAdmin.i18n.enterAddress);
                    return;
                }
                
                var geocoder = new google.maps.Geocoder();
                
                geocoder.geocode({ 'address': address }, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        var location = results[0].geometry.location;
                        
                        map.setCenter(location);
                        marker.setPosition(location);
                        
                        $('#aqualuxe_venue_latitude').val(location.lat().toFixed(6));
                        $('#aqualuxe_venue_longitude').val(location.lng().toFixed(6));
                    } else {
                        alert(aqualuxeEventsAdmin.i18n.geocodeError + status);
                    }
                });
            });
        },
        
        /**
         * Initialize ticket sorting
         */
        initTicketSorting: function() {
            // Check if sortable is available
            if (!$.fn.sortable) {
                return;
            }
            
            // Make tickets sortable
            $('#aqualuxe-tickets-list').sortable({
                handle: '.aqualuxe-event-admin__ticket-drag-handle',
                axis: 'y',
                opacity: 0.7,
                update: function(event, ui) {
                    // Update ticket indices after sorting
                    $('.aqualuxe-event-admin__ticket').each(function(index) {
                        var $ticket = $(this);
                        
                        $ticket.find('input[name^="aqualuxe_tickets["]').each(function() {
                            var name = $(this).attr('name');
                            var newName = name.replace(/aqualuxe_tickets\[\d+\]/, 'aqualuxe_tickets[' + index + ']');
                            $(this).attr('name', newName);
                        });
                        
                        $ticket.find('textarea[name^="aqualuxe_tickets["]').each(function() {
                            var name = $(this).attr('name');
                            var newName = name.replace(/aqualuxe_tickets\[\d+\]/, 'aqualuxe_tickets[' + index + ']');
                            $(this).attr('name', newName);
                        });
                        
                        $ticket.find('select[name^="aqualuxe_tickets["]').each(function() {
                            var name = $(this).attr('name');
                            var newName = name.replace(/aqualuxe_tickets\[\d+\]/, 'aqualuxe_tickets[' + index + ']');
                            $(this).attr('name', newName);
                        });
                    });
                }
            });
        },
        
        /**
         * Initialize registration actions
         */
        initRegistrationActions: function() {
            // Confirm registration
            $(document).on('click', '.aqualuxe-event-admin__registrations-action--confirm', function(e) {
                e.preventDefault();
                
                var registrationId = $(this).data('registration-id');
                var nonce = $(this).data('nonce');
                
                if (confirm(aqualuxeEventsAdmin.i18n.confirmApprove)) {
                    AquaLuxeEventsAdmin.updateRegistrationStatus(registrationId, 'confirmed', nonce);
                }
            });
            
            // Cancel registration
            $(document).on('click', '.aqualuxe-event-admin__registrations-action--cancel', function(e) {
                e.preventDefault();
                
                var registrationId = $(this).data('registration-id');
                var nonce = $(this).data('nonce');
                
                if (confirm(aqualuxeEventsAdmin.i18n.confirmCancel)) {
                    AquaLuxeEventsAdmin.updateRegistrationStatus(registrationId, 'cancelled', nonce);
                }
            });
            
            // Complete registration
            $(document).on('click', '.aqualuxe-event-admin__registrations-action--complete', function(e) {
                e.preventDefault();
                
                var registrationId = $(this).data('registration-id');
                var nonce = $(this).data('nonce');
                
                if (confirm(aqualuxeEventsAdmin.i18n.confirmComplete)) {
                    AquaLuxeEventsAdmin.updateRegistrationStatus(registrationId, 'completed', nonce);
                }
            });
            
            // Refund registration
            $(document).on('click', '.aqualuxe-event-admin__registrations-action--refund', function(e) {
                e.preventDefault();
                
                var registrationId = $(this).data('registration-id');
                var nonce = $(this).data('nonce');
                
                if (confirm(aqualuxeEventsAdmin.i18n.confirmRefund)) {
                    AquaLuxeEventsAdmin.refundRegistration(registrationId, nonce);
                }
            });
            
            // Delete registration
            $(document).on('click', '.aqualuxe-event-admin__registrations-action--delete', function(e) {
                e.preventDefault();
                
                var registrationId = $(this).data('registration-id');
                var nonce = $(this).data('nonce');
                
                if (confirm(aqualuxeEventsAdmin.i18n.confirmDelete)) {
                    AquaLuxeEventsAdmin.deleteRegistration(registrationId, nonce);
                }
            });
            
            // Email registration
            $(document).on('click', '.aqualuxe-event-admin__registrations-action--email', function(e) {
                e.preventDefault();
                
                var registrationId = $(this).data('registration-id');
                var $row = $(this).closest('tr');
                var email = $row.find('.aqualuxe-event-admin__registrations-attendee-email a').text().trim();
                var name = $row.find('.aqualuxe-event-admin__registrations-attendee-name').text().trim();
                
                // Set values in modal
                $('#aqualuxe-event-admin__email-registration-id').val(registrationId);
                $('#aqualuxe-event-admin__email-to').val(email);
                
                // Show modal
                $('#aqualuxe-event-admin__email-modal').show();
            });
            
            // Close modal
            $(document).on('click', '.aqualuxe-event-admin__modal-close, .aqualuxe-event-admin__modal-cancel', function(e) {
                e.preventDefault();
                $('#aqualuxe-event-admin__email-modal').hide();
            });
            
            // Send email
            $('#aqualuxe-event-admin__email-form').on('submit', function(e) {
                e.preventDefault();
                
                var $form = $(this);
                var $submitButton = $form.find('.aqualuxe-event-admin__modal-submit');
                
                // Disable submit button
                $submitButton.prop('disabled', true).text('Sending...');
                
                // Send email
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'aqualuxe_send_registration_email',
                        registration_id: $('#aqualuxe-event-admin__email-registration-id').val(),
                        subject: $('#aqualuxe-event-admin__email-subject').val(),
                        message: $('#aqualuxe-event-admin__email-message').val(),
                        include_ticket: $('#aqualuxe-event-admin__email-include-ticket').is(':checked') ? 1 : 0,
                        nonce: $form.find('input[name="nonce"]').val()
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Email sent successfully!');
                            $('#aqualuxe-event-admin__email-modal').hide();
                        } else {
                            alert(response.data.message || 'Failed to send email.');
                        }
                        
                        $submitButton.prop('disabled', false).text('Send Email');
                    },
                    error: function() {
                        alert('An error occurred. Please try again.');
                        $submitButton.prop('disabled', false).text('Send Email');
                    }
                });
            });
            
            // Toggle dropdown
            $(document).on('click', '.aqualuxe-event-admin__registrations-dropdown-toggle', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                var $dropdown = $(this).next('.aqualuxe-event-admin__registrations-dropdown-content');
                $('.aqualuxe-event-admin__registrations-dropdown-content').not($dropdown).hide();
                $dropdown.toggle();
            });
            
            // Close dropdown when clicking outside
            $(document).on('click', function(e) {
                if (!$(e.target).closest('.aqualuxe-event-admin__registrations-dropdown').length) {
                    $('.aqualuxe-event-admin__registrations-dropdown-content').hide();
                }
            });
            
            // Filter registrations by status
            $('#aqualuxe-registrations-filter-status').on('change', function() {
                var status = $(this).val();
                
                if (status) {
                    $('.aqualuxe-event-admin__registrations-table-row').hide();
                    $('.aqualuxe-event-admin__registrations-table-row[data-registration-status="' + status + '"]').show();
                } else {
                    $('.aqualuxe-event-admin__registrations-table-row').show();
                }
            });
            
            // Filter registrations by search
            $('#aqualuxe-registrations-filter-search').on('keyup', function() {
                var search = $(this).val().toLowerCase();
                
                $('.aqualuxe-event-admin__registrations-table-row').each(function() {
                    var text = $(this).text().toLowerCase();
                    
                    if (text.indexOf(search) > -1) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });
            
            // Export CSV
            $('#aqualuxe-registrations-export-csv').on('click', function(e) {
                e.preventDefault();
                
                var csv = [];
                var rows = $('.aqualuxe-event-admin__registrations-table tr:visible');
                
                rows.each(function(index, row) {
                    var rowData = [];
                    
                    $(row).find('th, td').each(function(cellIndex, cell) {
                        if (cellIndex < 8) { // Skip actions column
                            rowData.push('"' + $(cell).text().trim().replace(/"/g, '""') + '"');
                        }
                    });
                    
                    csv.push(rowData.join(','));
                });
                
                var csvContent = csv.join('\n');
                var blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
                var url = URL.createObjectURL(blob);
                
                var link = document.createElement('a');
                link.href = url;
                link.setAttribute('download', 'event_registrations.csv');
                link.click();
            });
            
            // Print registrations
            $('#aqualuxe-registrations-print').on('click', function(e) {
                e.preventDefault();
                
                var printWindow = window.open('', '_blank');
                var eventTitle = $('h1.wp-heading-inline').text();
                
                var printContent = '<html><head><title>' + eventTitle + ' - Registrations</title>';
                printContent += '<style>';
                printContent += 'body { font-family: Arial, sans-serif; }';
                printContent += 'table { width: 100%; border-collapse: collapse; }';
                printContent += 'th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }';
                printContent += 'th { background-color: #f2f2f2; }';
                printContent += '.header { margin-bottom: 20px; }';
                printContent += '.header h1 { margin: 0; }';
                printContent += '.header p { margin: 5px 0; }';
                printContent += '</style>';
                printContent += '</head><body>';
                
                printContent += '<div class="header">';
                printContent += '<h1>' + eventTitle + '</h1>';
                printContent += '<p>Registrations: ' + $('.aqualuxe-event-admin__registrations-table-row:visible').length + '</p>';
                printContent += '</div>';
                
                printContent += '<table>';
                printContent += '<thead><tr>';
                
                $('.aqualuxe-event-admin__registrations-table-header').each(function(index) {
                    if (index < 8) { // Skip actions column
                        printContent += '<th>' + $(this).text() + '</th>';
                    }
                });
                
                printContent += '</tr></thead>';
                printContent += '<tbody>';
                
                $('.aqualuxe-event-admin__registrations-table-row:visible').each(function() {
                    printContent += '<tr>';
                    
                    $(this).find('td').each(function(index) {
                        if (index < 8) { // Skip actions column
                            printContent += '<td>' + $(this).text().trim() + '</td>';
                        }
                    });
                    
                    printContent += '</tr>';
                });
                
                printContent += '</tbody></table>';
                printContent += '</body></html>';
                
                printWindow.document.write(printContent);
                printWindow.document.close();
                printWindow.focus();
                printWindow.print();
                printWindow.close();
            });
        },
        
        /**
         * Update registration status
         * 
         * @param {number} registrationId Registration ID
         * @param {string} status Status
         * @param {string} nonce Nonce
         */
        updateRegistrationStatus: function(registrationId, status, nonce) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_update_registration_status',
                    registration_id: registrationId,
                    status: status,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || 'Failed to update registration status.');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        },
        
        /**
         * Refund registration
         * 
         * @param {number} registrationId Registration ID
         * @param {string} nonce Nonce
         */
        refundRegistration: function(registrationId, nonce) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_refund_registration',
                    registration_id: registrationId,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || 'Failed to refund registration.');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        },
        
        /**
         * Delete registration
         * 
         * @param {number} registrationId Registration ID
         * @param {string} nonce Nonce
         */
        deleteRegistration: function(registrationId, nonce) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_delete_registration',
                    registration_id: registrationId,
                    nonce: nonce
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.data.message || 'Failed to delete registration.');
                    }
                },
                error: function() {
                    alert('An error occurred. Please try again.');
                }
            });
        }
    };

    /**
     * Document ready
     */
    $(document).ready(function() {
        // Initialize events admin
        AquaLuxeEventsAdmin.init();
        
        // Add ticket
        $('.aqualuxe-event-admin__add-ticket-button').on('click', function(e) {
            e.preventDefault();
            
            var ticketTemplate = $('#aqualuxe-ticket-template').html();
            var ticketIndex = $('.aqualuxe-event-admin__ticket').length;
            var newTicket = ticketTemplate.replace(/{index}/g, ticketIndex);
            
            $('#aqualuxe-tickets-list').append(newTicket);
            
            // Remove no tickets message if it exists
            $('.aqualuxe-event-admin__no-tickets').remove();
        });
        
        // Remove ticket
        $(document).on('click', '.aqualuxe-event-admin__ticket-remove-button', function(e) {
            e.preventDefault();
            
            if (confirm(aqualuxeEventsAdmin.i18n.confirmDelete)) {
                $(this).closest('.aqualuxe-event-admin__ticket').remove();
                
                // Show no tickets message if no tickets left
                if ($('.aqualuxe-event-admin__ticket').length === 0) {
                    $('#aqualuxe-tickets-list').append('<div class="aqualuxe-event-admin__no-tickets"><p>' + aqualuxeEventsAdmin.i18n.noTickets + '</p></div>');
                }
            }
        });
        
        // Toggle ticket content
        $(document).on('click', '.aqualuxe-event-admin__ticket-toggle-button', function(e) {
            e.preventDefault();
            
            var $ticket = $(this).closest('.aqualuxe-event-admin__ticket');
            var $content = $ticket.find('.aqualuxe-event-admin__ticket-content');
            var $icon = $(this).find('.dashicons');
            
            $content.slideToggle(200);
            
            if ($icon.hasClass('dashicons-arrow-down-alt2')) {
                $icon.removeClass('dashicons-arrow-down-alt2').addClass('dashicons-arrow-up-alt2');
            } else {
                $icon.removeClass('dashicons-arrow-up-alt2').addClass('dashicons-arrow-down-alt2');
            }
        });
        
        // Copy organizer from user
        $('#aqualuxe-organizer-copy-from-user').on('click', function(e) {
            e.preventDefault();
            
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_current_user_data',
                    nonce: aqualuxeEventsAdmin.nonce
                },
                success: function(response) {
                    if (response.success) {
                        var userData = response.data;
                        
                        $('#aqualuxe_organizer_name').val(userData.display_name);
                        $('#aqualuxe_organizer_email').val(userData.user_email);
                        
                        if (userData.user_url) {
                            $('#aqualuxe_organizer_website').val(userData.user_url);
                        }
                        
                        if (userData.description) {
                            $('#aqualuxe_organizer_description').val(userData.description);
                        }
                        
                        if (userData.phone) {
                            $('#aqualuxe_organizer_phone').val(userData.phone);
                        }
                    } else {
                        alert('Failed to get user data.');
                    }
                },
                error: function() {
                    alert('An error occurred while getting user data.');
                }
            });
        });
    });
})(jQuery);