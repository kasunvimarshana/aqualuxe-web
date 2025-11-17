<?php
/**
 * Template Name: Contact Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        // Display breadcrumbs if a breadcrumb plugin is active
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<div class="breadcrumbs">', '</div>' );
        } elseif ( function_exists( 'bcn_display' ) ) {
            echo '<div class="breadcrumbs">';
            bcn_display();
            echo '</div>';
        }
        ?>

        <div class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
            
            <?php
            // Get page subtitle
            $subtitle = get_post_meta( get_the_ID(), 'page_subtitle', true );
            if ( empty( $subtitle ) ) {
                $subtitle = __( 'Get in touch with our aquatic experts', 'aqualuxe' );
            }
            ?>
            
            <p class="page-subtitle"><?php echo esc_html( $subtitle ); ?></p>
        </div>

        <?php
        // Check if the page has a featured image
        if ( has_post_thumbnail() ) :
        ?>
        <div class="contact-hero">
            <?php the_post_thumbnail( 'full', array( 'class' => 'contact-hero-image' ) ); ?>
        </div>
        <?php endif; ?>

        <div class="contact-intro">
            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>

        <div class="contact-layout">
            <div class="contact-form-section">
                <h2><?php esc_html_e( 'Send Us a Message', 'aqualuxe' ); ?></h2>
                
                <?php
                // Contact form
                $contact_form_shortcode = get_post_meta( get_the_ID(), 'contact_form_shortcode', true );
                
                if ( ! empty( $contact_form_shortcode ) ) {
                    echo do_shortcode( $contact_form_shortcode );
                } else {
                    // Get stored form data if any
                    $form_data = function_exists( 'aqualuxe_get_contact_form_data' ) ? aqualuxe_get_contact_form_data() : array(
                        'name' => '',
                        'email' => '',
                        'phone' => '',
                        'subject' => '',
                        'service' => '',
                        'message' => '',
                    );
                    
                    // Display form messages
                    do_action( 'aqualuxe_before_contact_form' );
                    
                    // Default contact form HTML if no shortcode is provided
                    ?>
                    <form id="aqualuxe-contact-form" class="contact-form" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
                        <input type="hidden" name="action" value="aqualuxe_contact_form">
                        <?php wp_nonce_field( 'aqualuxe_contact_nonce', 'contact_nonce' ); ?>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-name"><?php esc_html_e( 'Your Name', 'aqualuxe' ); ?> <span class="required">*</span></label>
                                <input type="text" id="contact-name" name="contact_name" value="<?php echo esc_attr( $form_data['name'] ); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-email"><?php esc_html_e( 'Email Address', 'aqualuxe' ); ?> <span class="required">*</span></label>
                                <input type="email" id="contact-email" name="contact_email" value="<?php echo esc_attr( $form_data['email'] ); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-row">
                            <div class="form-group">
                                <label for="contact-phone"><?php esc_html_e( 'Phone Number', 'aqualuxe' ); ?></label>
                                <input type="tel" id="contact-phone" name="contact_phone" value="<?php echo esc_attr( $form_data['phone'] ); ?>">
                            </div>
                            
                            <div class="form-group">
                                <label for="contact-subject"><?php esc_html_e( 'Subject', 'aqualuxe' ); ?> <span class="required">*</span></label>
                                <input type="text" id="contact-subject" name="contact_subject" value="<?php echo esc_attr( $form_data['subject'] ); ?>" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-service"><?php esc_html_e( 'Service of Interest', 'aqualuxe' ); ?></label>
                            <select id="contact-service" name="contact_service">
                                <option value=""><?php esc_html_e( 'Select a service', 'aqualuxe' ); ?></option>
                                <option value="design" <?php selected( $form_data['service'], 'design' ); ?>><?php esc_html_e( 'Aquarium Design & Installation', 'aqualuxe' ); ?></option>
                                <option value="maintenance" <?php selected( $form_data['service'], 'maintenance' ); ?>><?php esc_html_e( 'Maintenance Services', 'aqualuxe' ); ?></option>
                                <option value="health" <?php selected( $form_data['service'], 'health' ); ?>><?php esc_html_e( 'Aquatic Health Services', 'aqualuxe' ); ?></option>
                                <option value="consultation" <?php selected( $form_data['service'], 'consultation' ); ?>><?php esc_html_e( 'Expert Consultation', 'aqualuxe' ); ?></option>
                                <option value="commercial" <?php selected( $form_data['service'], 'commercial' ); ?>><?php esc_html_e( 'Commercial Installations', 'aqualuxe' ); ?></option>
                                <option value="rare" <?php selected( $form_data['service'], 'rare' ); ?>><?php esc_html_e( 'Rare Species Sourcing', 'aqualuxe' ); ?></option>
                                <option value="other" <?php selected( $form_data['service'], 'other' ); ?>><?php esc_html_e( 'Other', 'aqualuxe' ); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="contact-message"><?php esc_html_e( 'Your Message', 'aqualuxe' ); ?> <span class="required">*</span></label>
                            <textarea id="contact-message" name="contact_message" rows="6" required><?php echo esc_textarea( $form_data['message'] ); ?></textarea>
                        </div>
                        
                        <div class="form-group consent-checkbox">
                            <input type="checkbox" id="contact-consent" name="contact_consent" required>
                            <label for="contact-consent"><?php esc_html_e( 'I agree to the privacy policy and consent to having my data processed.', 'aqualuxe' ); ?> <span class="required">*</span></label>
                        </div>
                        
                        <div class="form-submit">
                            <button type="submit" class="btn btn-primary"><?php esc_html_e( 'Send Message', 'aqualuxe' ); ?></button>
                        </div>
                    </form>
                    <?php
                }
                ?>
            </div>
            
            <div class="contact-info-section">
                <?php
                // Contact information
                $contact_info = get_post_meta( get_the_ID(), 'contact_info', true );
                
                if ( empty( $contact_info ) ) {
                    // Default contact information if none is defined
                    $contact_info = array(
                        'address' => '123 Aquatic Avenue, Coral City, CA 90210',
                        'phone' => '+1 (555) 123-4567',
                        'email' => 'info@aqualuxetheme.com',
                        'hours' => array(
                            'Monday - Friday' => '9:00 AM - 6:00 PM',
                            'Saturday' => '10:00 AM - 4:00 PM',
                            'Sunday' => 'Closed',
                        ),
                        'social' => array(
                            'facebook' => '#',
                            'instagram' => '#',
                            'twitter' => '#',
                            'youtube' => '#',
                        ),
                    );
                }
                ?>
                
                <div class="contact-info">
                    <h3><?php esc_html_e( 'Contact Information', 'aqualuxe' ); ?></h3>
                    
                    <?php if ( ! empty( $contact_info['address'] ) ) : ?>
                        <div class="info-item">
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                                    <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="info-content">
                                <h4><?php esc_html_e( 'Address', 'aqualuxe' ); ?></h4>
                                <p><?php echo esc_html( $contact_info['address'] ); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $contact_info['phone'] ) ) : ?>
                        <div class="info-item">
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                                    <path fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="info-content">
                                <h4><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></h4>
                                <p><a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $contact_info['phone'] ) ); ?>"><?php echo esc_html( $contact_info['phone'] ); ?></a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $contact_info['email'] ) ) : ?>
                        <div class="info-item">
                            <div class="info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                                    <path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" />
                                    <path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" />
                                </svg>
                            </div>
                            <div class="info-content">
                                <h4><?php esc_html_e( 'Email', 'aqualuxe' ); ?></h4>
                                <p><a href="mailto:<?php echo esc_attr( $contact_info['email'] ); ?>"><?php echo esc_html( $contact_info['email'] ); ?></a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ( ! empty( $contact_info['hours'] ) && is_array( $contact_info['hours'] ) ) : ?>
                    <div class="business-hours">
                        <h3><?php esc_html_e( 'Business Hours', 'aqualuxe' ); ?></h3>
                        <ul class="hours-list">
                            <?php foreach ( $contact_info['hours'] as $day => $hours ) : ?>
                                <li>
                                    <span class="day"><?php echo esc_html( $day ); ?></span>
                                    <span class="hours"><?php echo esc_html( $hours ); ?></span>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $contact_info['social'] ) && is_array( $contact_info['social'] ) ) : ?>
                    <div class="social-links">
                        <h3><?php esc_html_e( 'Connect With Us', 'aqualuxe' ); ?></h3>
                        <div class="social-icons">
                            <?php foreach ( $contact_info['social'] as $network => $url ) : ?>
                                <?php if ( ! empty( $url ) ) : ?>
                                    <a href="<?php echo esc_url( $url ); ?>" class="social-icon <?php echo esc_attr( $network ); ?>" target="_blank" rel="noopener noreferrer">
                                        <?php
                                        switch ( $network ) {
                                            case 'facebook':
                                                ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                                </svg>
                                                <?php
                                                break;
                                            case 'instagram':
                                                ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                                </svg>
                                                <?php
                                                break;
                                            case 'twitter':
                                                ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                                </svg>
                                                <?php
                                                break;
                                            case 'youtube':
                                                ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                                                    <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                                                </svg>
                                                <?php
                                                break;
                                            default:
                                                ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                                                    <path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" />
                                                </svg>
                                                <?php
                                                break;
                                        }
                                        ?>
                                    </a>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php
        // Google Maps
        $map_api_key = get_theme_mod( 'aqualuxe_google_maps_api_key', '' );
        $map_location = get_post_meta( get_the_ID(), 'map_location', true );
        $map_zoom = get_post_meta( get_the_ID(), 'map_zoom', true );
        
        if ( empty( $map_location ) ) {
            $map_location = '34.0522, -118.2437'; // Default to Los Angeles
        }
        
        if ( empty( $map_zoom ) ) {
            $map_zoom = '15';
        }
        
        if ( ! empty( $map_api_key ) ) :
        ?>
        <div class="map-section">
            <h2><?php esc_html_e( 'Find Us', 'aqualuxe' ); ?></h2>
            <div id="aqualuxe-map" class="google-map" data-location="<?php echo esc_attr( $map_location ); ?>" data-zoom="<?php echo esc_attr( $map_zoom ); ?>"></div>
            
            <script>
            function initAqualuxeMap() {
                const mapElement = document.getElementById('aqualuxe-map');
                const locationStr = mapElement.getAttribute('data-location');
                const zoom = parseInt(mapElement.getAttribute('data-zoom'), 10);
                
                const [lat, lng] = locationStr.split(',').map(coord => parseFloat(coord.trim()));
                const location = { lat, lng };
                
                const map = new google.maps.Map(mapElement, {
                    center: location,
                    zoom: zoom,
                    styles: [
                        {
                            "featureType": "water",
                            "elementType": "geometry",
                            "stylers": [
                                { "color": "#e9e9e9" },
                                { "lightness": 17 }
                            ]
                        },
                        {
                            "featureType": "landscape",
                            "elementType": "geometry",
                            "stylers": [
                                { "color": "#f5f5f5" },
                                { "lightness": 20 }
                            ]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "geometry.fill",
                            "stylers": [
                                { "color": "#ffffff" },
                                { "lightness": 17 }
                            ]
                        },
                        {
                            "featureType": "road.highway",
                            "elementType": "geometry.stroke",
                            "stylers": [
                                { "color": "#ffffff" },
                                { "lightness": 29 },
                                { "weight": 0.2 }
                            ]
                        },
                        {
                            "featureType": "road.arterial",
                            "elementType": "geometry",
                            "stylers": [
                                { "color": "#ffffff" },
                                { "lightness": 18 }
                            ]
                        },
                        {
                            "featureType": "road.local",
                            "elementType": "geometry",
                            "stylers": [
                                { "color": "#ffffff" },
                                { "lightness": 16 }
                            ]
                        },
                        {
                            "featureType": "poi",
                            "elementType": "geometry",
                            "stylers": [
                                { "color": "#f5f5f5" },
                                { "lightness": 21 }
                            ]
                        },
                        {
                            "featureType": "poi.park",
                            "elementType": "geometry",
                            "stylers": [
                                { "color": "#dedede" },
                                { "lightness": 21 }
                            ]
                        },
                        {
                            "elementType": "labels.text.stroke",
                            "stylers": [
                                { "visibility": "on" },
                                { "color": "#ffffff" },
                                { "lightness": 16 }
                            ]
                        },
                        {
                            "elementType": "labels.text.fill",
                            "stylers": [
                                { "saturation": 36 },
                                { "color": "#333333" },
                                { "lightness": 40 }
                            ]
                        },
                        {
                            "elementType": "labels.icon",
                            "stylers": [
                                { "visibility": "off" }
                            ]
                        },
                        {
                            "featureType": "transit",
                            "elementType": "geometry",
                            "stylers": [
                                { "color": "#f2f2f2" },
                                { "lightness": 19 }
                            ]
                        },
                        {
                            "featureType": "administrative",
                            "elementType": "geometry.fill",
                            "stylers": [
                                { "color": "#fefefe" },
                                { "lightness": 20 }
                            ]
                        },
                        {
                            "featureType": "administrative",
                            "elementType": "geometry.stroke",
                            "stylers": [
                                { "color": "#fefefe" },
                                { "lightness": 17 },
                                { "weight": 1.2 }
                            ]
                        }
                    ]
                });
                
                const marker = new google.maps.Marker({
                    position: location,
                    map: map,
                    title: '<?php echo esc_js( get_bloginfo( 'name' ) ); ?>',
                    icon: {
                        url: '<?php echo esc_js( get_template_directory_uri() . '/assets/dist/images/map-marker.png' ); ?>',
                        scaledSize: new google.maps.Size(40, 40)
                    }
                });
                
                const infoWindow = new google.maps.InfoWindow({
                    content: '<div class="map-info-window"><h4><?php echo esc_js( get_bloginfo( 'name' ) ); ?></h4><p><?php echo esc_js( $contact_info['address'] ); ?></p></div>'
                });
                
                marker.addListener('click', function() {
                    infoWindow.open(map, marker);
                });
            }
            </script>
            <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr( $map_api_key ); ?>&callback=initAqualuxeMap" async defer></script>
        </div>
        <?php else : ?>
        <div class="map-section map-placeholder">
            <h2><?php esc_html_e( 'Find Us', 'aqualuxe' ); ?></h2>
            <div class="map-placeholder-content">
                <p><?php esc_html_e( 'Google Maps API key is required to display the map. Please add your API key in the Theme Customizer.', 'aqualuxe' ); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <?php
        // Call to action
        $cta_title = get_post_meta( get_the_ID(), 'cta_title', true );
        $cta_text = get_post_meta( get_the_ID(), 'cta_text', true );
        $cta_button_text = get_post_meta( get_the_ID(), 'cta_button_text', true );
        $cta_button_url = get_post_meta( get_the_ID(), 'cta_button_url', true );
        $cta_background = get_post_meta( get_the_ID(), 'cta_background', true );
        
        if ( empty( $cta_title ) ) {
            $cta_title = __( 'Ready to Transform Your Aquatic Experience?', 'aqualuxe' );
        }
        
        if ( empty( $cta_text ) ) {
            $cta_text = __( 'Our team of experts is ready to help you create the perfect aquatic environment for your home or business.', 'aqualuxe' );
        }
        
        if ( empty( $cta_button_text ) ) {
            $cta_button_text = __( 'View Our Services', 'aqualuxe' );
        }
        
        if ( empty( $cta_button_url ) ) {
            $cta_button_url = home_url( '/services' );
        }
        
        if ( empty( $cta_background ) ) {
            $cta_background = get_template_directory_uri() . '/assets/dist/images/cta-background.jpg';
        }
        ?>
        <div class="cta-section" style="background-image: url('<?php echo esc_url( $cta_background ); ?>');">
            <div class="cta-overlay"></div>
            <div class="cta-content">
                <h2 class="cta-title"><?php echo esc_html( $cta_title ); ?></h2>
                <p class="cta-text"><?php echo esc_html( $cta_text ); ?></p>
                <a href="<?php echo esc_url( $cta_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $cta_button_text ); ?></a>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const contactForm = document.getElementById('aqualuxe-contact-form');
    
    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            const requiredFields = contactForm.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(function(field) {
                if (!field.value.trim()) {
                    isValid = false;
                    field.classList.add('error');
                } else {
                    field.classList.remove('error');
                }
            });
            
            // Email validation
            const emailField = contactForm.querySelector('#contact-email');
            if (emailField && emailField.value) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(emailField.value)) {
                    isValid = false;
                    emailField.classList.add('error');
                }
            }
            
            if (!isValid) {
                e.preventDefault();
                
                // Show error message
                let errorMessage = contactForm.querySelector('.form-error-message');
                if (!errorMessage) {
                    errorMessage = document.createElement('div');
                    errorMessage.className = 'form-error-message';
                    errorMessage.textContent = '<?php esc_html_e( 'Please fill in all required fields correctly.', 'aqualuxe' ); ?>';
                    contactForm.querySelector('.form-submit').insertBefore(errorMessage, contactForm.querySelector('.form-submit button'));
                }
            }
        });
        
        // Clear error state on input
        contactForm.querySelectorAll('input, textarea, select').forEach(function(field) {
            field.addEventListener('input', function() {
                this.classList.remove('error');
                
                const errorMessage = contactForm.querySelector('.form-error-message');
                if (errorMessage) {
                    errorMessage.remove();
                }
            });
        });
    }
});
</script>

<?php
get_footer();