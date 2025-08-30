<?php
/**
 * Event Venue Meta Box Template
 *
 * @package AquaLuxe\Modules\Events
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get event and module from template args
$event = $args['event'] ?? null;
$module = $args['module'] ?? null;

if ( ! $event || ! $module ) {
	return;
}

// Get venue data
$venue = $event->get_venue_data();
$venue_name = $venue['name'] ?? '';
$venue_address = $venue['address'] ?? '';
$venue_city = $venue['city'] ?? '';
$venue_state = $venue['state'] ?? '';
$venue_zip = $venue['zip'] ?? '';
$venue_country = $venue['country'] ?? '';
$venue_latitude = $venue['latitude'] ?? '';
$venue_longitude = $venue['longitude'] ?? '';
$venue_website = $venue['website'] ?? '';
$venue_phone = $venue['phone'] ?? '';

// Get Google Maps API key
$google_maps_api_key = $module->get_setting( 'google_maps_api_key', '' );
?>

<div class="aqualuxe-event-admin">
    <div class="aqualuxe-event-admin__section">
        <div class="aqualuxe-event-admin__field">
            <label for="aqualuxe_venue_name" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Venue Name', 'aqualuxe' ); ?></label>
            <input type="text" id="aqualuxe_venue_name" name="aqualuxe_venue_data[name]" value="<?php echo esc_attr( $venue_name ); ?>" class="aqualuxe-event-admin__input">
        </div>
        
        <div class="aqualuxe-event-admin__field">
            <label for="aqualuxe_venue_address" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Address', 'aqualuxe' ); ?></label>
            <textarea id="aqualuxe_venue_address" name="aqualuxe_venue_data[address]" class="aqualuxe-event-admin__textarea"><?php echo esc_textarea( $venue_address ); ?></textarea>
        </div>
        
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_venue_city" class="aqualuxe-event-admin__label"><?php esc_html_e( 'City', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_venue_city" name="aqualuxe_venue_data[city]" value="<?php echo esc_attr( $venue_city ); ?>" class="aqualuxe-event-admin__input">
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_venue_state" class="aqualuxe-event-admin__label"><?php esc_html_e( 'State/Province', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_venue_state" name="aqualuxe_venue_data[state]" value="<?php echo esc_attr( $venue_state ); ?>" class="aqualuxe-event-admin__input">
            </div>
        </div>
        
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_venue_zip" class="aqualuxe-event-admin__label"><?php esc_html_e( 'ZIP/Postal Code', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_venue_zip" name="aqualuxe_venue_data[zip]" value="<?php echo esc_attr( $venue_zip ); ?>" class="aqualuxe-event-admin__input">
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_venue_country" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Country', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_venue_country" name="aqualuxe_venue_data[country]" value="<?php echo esc_attr( $venue_country ); ?>" class="aqualuxe-event-admin__input">
            </div>
        </div>
    </div>
    
    <div class="aqualuxe-event-admin__section">
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_venue_website" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Website', 'aqualuxe' ); ?></label>
                <input type="url" id="aqualuxe_venue_website" name="aqualuxe_venue_data[website]" value="<?php echo esc_attr( $venue_website ); ?>" class="aqualuxe-event-admin__input">
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_venue_phone" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></label>
                <input type="tel" id="aqualuxe_venue_phone" name="aqualuxe_venue_data[phone]" value="<?php echo esc_attr( $venue_phone ); ?>" class="aqualuxe-event-admin__input">
            </div>
        </div>
    </div>
    
    <div class="aqualuxe-event-admin__section">
        <div class="aqualuxe-event-admin__field aqualuxe-event-admin__field--row">
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_venue_latitude" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Latitude', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_venue_latitude" name="aqualuxe_venue_data[latitude]" value="<?php echo esc_attr( $venue_latitude ); ?>" class="aqualuxe-event-admin__input">
            </div>
            
            <div class="aqualuxe-event-admin__field-col">
                <label for="aqualuxe_venue_longitude" class="aqualuxe-event-admin__label"><?php esc_html_e( 'Longitude', 'aqualuxe' ); ?></label>
                <input type="text" id="aqualuxe_venue_longitude" name="aqualuxe_venue_data[longitude]" value="<?php echo esc_attr( $venue_longitude ); ?>" class="aqualuxe-event-admin__input">
            </div>
        </div>
        
        <?php if ( $google_maps_api_key ) : ?>
            <div class="aqualuxe-event-admin__field">
                <div class="aqualuxe-event-admin__map-container">
                    <div id="aqualuxe-venue-map" class="aqualuxe-event-admin__map"></div>
                    <button type="button" id="aqualuxe-venue-map-search" class="button"><?php esc_html_e( 'Find Location', 'aqualuxe' ); ?></button>
                </div>
                
                <p class="aqualuxe-event-admin__help"><?php esc_html_e( 'Search for a location or click on the map to set the venue coordinates.', 'aqualuxe' ); ?></p>
                
                <script>
                    jQuery(document).ready(function($) {
                        // Initialize map
                        function initMap() {
                            var lat = parseFloat($('#aqualuxe_venue_latitude').val()) || 40.7128;
                            var lng = parseFloat($('#aqualuxe_venue_longitude').val()) || -74.0060;
                            var mapOptions = {
                                center: { lat: lat, lng: lng },
                                zoom: 14
                            };
                            
                            var map = new google.maps.Map(document.getElementById('aqualuxe-venue-map'), mapOptions);
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
                            
                            // Search button
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
                                    alert('<?php esc_html_e( 'Please enter an address to search.', 'aqualuxe' ); ?>');
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
                                        alert('<?php esc_html_e( 'Geocode was not successful for the following reason: ', 'aqualuxe' ); ?>' + status);
                                    }
                                });
                            });
                        }
                        
                        // Load Google Maps API
                        if (typeof google === 'undefined' || typeof google.maps === 'undefined') {
                            $.getScript('https://maps.googleapis.com/maps/api/js?key=<?php echo esc_js( $google_maps_api_key ); ?>&libraries=places&callback=initMap');
                        } else {
                            initMap();
                        }
                        
                        // Make initMap global
                        window.initMap = initMap;
                    });
                </script>
            </div>
        <?php else : ?>
            <div class="aqualuxe-event-admin__notice aqualuxe-event-admin__notice--warning">
                <p><?php esc_html_e( 'Google Maps API key is not configured. Please add it in the module settings to enable the map feature.', 'aqualuxe' ); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>