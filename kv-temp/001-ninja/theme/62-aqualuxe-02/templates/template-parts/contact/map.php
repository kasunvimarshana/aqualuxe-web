<?php
/**
 * Contact Page Map Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get map settings from customizer or ACF
$show_map = get_theme_mod( 'aqualuxe_contact_show_map', true );
$map_type = get_theme_mod( 'aqualuxe_contact_map_type', 'google' );
$map_height = get_theme_mod( 'aqualuxe_contact_map_height', '400px' );
$map_zoom = get_theme_mod( 'aqualuxe_contact_map_zoom', 15 );
$map_lat = get_theme_mod( 'aqualuxe_contact_map_lat', '37.4419' );
$map_lng = get_theme_mod( 'aqualuxe_contact_map_lng', '-122.1430' );
$map_address = get_theme_mod( 'aqualuxe_contact_map_address', '123 Main Street, Palo Alto, CA 94301' );
$map_marker_title = get_theme_mod( 'aqualuxe_contact_map_marker_title', 'AquaLuxe Headquarters' );
$map_api_key = get_theme_mod( 'aqualuxe_google_maps_api_key', '' );

// Skip if map is disabled
if ( ! $show_map ) {
    return;
}

// Map style
$map_style = 'height: ' . esc_attr( $map_height ) . ';';
?>

<section class="map-section">
    <?php if ( $map_type === 'google' && $map_api_key ) : ?>
        <div id="google-map" class="contact-map" style="<?php echo esc_attr( $map_style ); ?>"></div>
        <script>
            function initMap() {
                var location = {lat: <?php echo esc_js( $map_lat ); ?>, lng: <?php echo esc_js( $map_lng ); ?>};
                var map = new google.maps.Map(document.getElementById('google-map'), {
                    zoom: <?php echo esc_js( $map_zoom ); ?>,
                    center: location,
                    styles: [
                        {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [{"color": "#e9e9e9"}, {"lightness": 17}]
                        },
                        {
                            "featureType": "landscape",
                            "elementType": "geometry",
                            "stylers": [{"color": "#f5f5f5"}, {"lightness": 20}]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "geometry.fill",
                            "stylers": [{"color": "#ffffff"}, {"lightness": 17}]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "geometry.stroke",
                            "stylers": [{"color": "#ffffff"}, {"lightness": 29}, {"weight": 0.2}]
                        },
                        {
                            "featureType": "road.arterial",
                            "elementType": "geometry",
                            "stylers": [{"color": "#ffffff"}, {"lightness": 18}]
                        },
                        {
                            "featureType": "road.local",
                            "elementType": "geometry",
                            "stylers": [{"color": "#ffffff"}, {"lightness": 16}]
                        },
                        {
                            "featureType": "poi",
                            "elementType": "geometry",
                            "stylers": [{"color": "#f5f5f5"}, {"lightness": 21}]
                        },
                        {
                            "featureType": "poi.park",
                            "elementType": "geometry",
                            "stylers": [{"color": "#dedede"}, {"lightness": 21}]
                        },
                        {
                            "elementType": "labels.text.stroke",
                            "stylers": [{"visibility": "on"}, {"color": "#ffffff"}, {"lightness": 16}]
                        },
                        {
                            "elementType": "labels.text.fill",
                            "stylers": [{"saturation": 36}, {"color": "#333333"}, {"lightness": 40}]
                        },
                        {
                            "elementType": "labels.icon",
                            "stylers": [{"visibility": "off"}]
                        },
                        {
                            "featureType": "transit",
                            "elementType": "geometry",
                            "stylers": [{"color": "#f2f2f2"}, {"lightness": 19}]
                        },
                        {
                            "featureType": "administrative",
                            "elementType": "geometry.fill",
                            "stylers": [{"color": "#fefefe"}, {"lightness": 20}]
                        },
                        {
                            "featureType": "administrative",
                            "elementType": "geometry.stroke",
                            "stylers": [{"color": "#fefefe"}, {"lightness": 17}, {"weight": 1.2}]
                        }
                    ]
                });
                
                var marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: '<?php echo esc_js( $map_marker_title ); ?>'
                });
                
                var infowindow = new google.maps.InfoWindow({
                    content: '<div class="map-info-window"><h4><?php echo esc_js( $map_marker_title ); ?></h4><p><?php echo esc_js( $map_address ); ?></p></div>'
                });
                
                marker.addListener('click', function() {
                    infowindow.open(map, marker);
                });
            }
        </script>
        <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr( $map_api_key ); ?>&callback=initMap"></script>
    <?php elseif ( $map_type === 'embed' ) : ?>
        <div class="contact-map map-embed" style="<?php echo esc_attr( $map_style ); ?>">
            <iframe
                width="100%"
                height="100%"
                frameborder="0"
                style="border:0"
                src="https://www.google.com/maps/embed/v1/place?key=<?php echo esc_attr( $map_api_key ); ?>&q=<?php echo esc_attr( urlencode( $map_address ) ); ?>&zoom=<?php echo esc_attr( $map_zoom ); ?>"
                allowfullscreen
            ></iframe>
        </div>
    <?php else : ?>
        <div class="contact-map map-fallback" style="<?php echo esc_attr( $map_style ); ?>">
            <div class="map-fallback-content">
                <h3><?php esc_html_e( 'Find Us', 'aqualuxe' ); ?></h3>
                <p><?php echo esc_html( $map_address ); ?></p>
                <a href="https://maps.google.com/?q=<?php echo esc_attr( urlencode( $map_address ) ); ?>" class="btn btn-primary" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Get Directions', 'aqualuxe' ); ?></a>
            </div>
        </div>
    <?php endif; ?>
</section>