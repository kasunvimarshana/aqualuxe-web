<?php
/**
 * Template Name: Contact Page
 * Template Post Type: page
 *
 * A template for creating contact pages with a contact form and map.
 *
 * @package AquaLuxe
 */

get_header();

// Get contact page settings
$contact_layout = get_post_meta( get_the_ID(), '_aqualuxe_contact_layout', true );
$contact_layout = $contact_layout ? $contact_layout : 'split'; // Default to split layout

$show_map = get_post_meta( get_the_ID(), '_aqualuxe_contact_show_map', true );
$show_map = $show_map !== '' ? $show_map : true; // Default to showing map

$map_location = get_post_meta( get_the_ID(), '_aqualuxe_contact_map_location', true );
$map_zoom = get_post_meta( get_the_ID(), '_aqualuxe_contact_map_zoom', true );
$map_zoom = $map_zoom ? $map_zoom : 15; // Default zoom level

$contact_info = array(
    'address' => get_post_meta( get_the_ID(), '_aqualuxe_contact_address', true ),
    'phone' => get_post_meta( get_the_ID(), '_aqualuxe_contact_phone', true ),
    'email' => get_post_meta( get_the_ID(), '_aqualuxe_contact_email', true ),
    'hours' => get_post_meta( get_the_ID(), '_aqualuxe_contact_hours', true ),
);

// Set layout classes
$container_class = 'container mx-auto px-4 py-12';
$content_class = '';

if ( $contact_layout === 'split' ) {
    $content_class = 'grid grid-cols-1 lg:grid-cols-2 gap-12';
} elseif ( $contact_layout === 'form-top' ) {
    $content_class = 'flex flex-col gap-12';
} elseif ( $contact_layout === 'form-bottom' ) {
    $content_class = 'flex flex-col-reverse gap-12';
}
?>

<main id="primary" class="site-main">
    <div class="<?php echo esc_attr( $container_class ); ?>">
        <?php
        while ( have_posts() ) :
            the_post();
            
            // Display page title and content
            ?>
            <header class="page-header mb-8">
                <?php the_title( '<h1 class="page-title text-3xl font-bold text-gray-900 dark:text-gray-100">', '</h1>' ); ?>
                
                <div class="page-description prose dark:prose-invert max-w-none mt-4">
                    <?php the_content(); ?>
                </div>
            </header>
            <?php
        endwhile; // End of the page loop.
        ?>
        
        <div class="<?php echo esc_attr( $content_class ); ?>">
            <div class="contact-form-container bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100"><?php esc_html_e( 'Send us a Message', 'aqualuxe' ); ?></h2>
                
                <?php
                // Check if Contact Form 7 is active
                if ( function_exists( 'wpcf7_contact_form' ) ) {
                    // Get contact form ID from page meta
                    $contact_form_id = get_post_meta( get_the_ID(), '_aqualuxe_contact_form_id', true );
                    
                    if ( $contact_form_id ) {
                        echo do_shortcode( '[contact-form-7 id="' . esc_attr( $contact_form_id ) . '"]' );
                    } else {
                        // Display all available forms if no specific form is selected
                        $forms = get_posts( array(
                            'post_type' => 'wpcf7_contact_form',
                            'posts_per_page' => 1,
                        ) );
                        
                        if ( ! empty( $forms ) ) {
                            echo do_shortcode( '[contact-form-7 id="' . esc_attr( $forms[0]->ID ) . '"]' );
                        } else {
                            echo '<div class="alert alert-warning">';
                            esc_html_e( 'Please create a contact form with Contact Form 7 and assign it to this page.', 'aqualuxe' );
                            echo '</div>';
                        }
                    }
                } else {
                    // Display a message if Contact Form 7 is not active
                    ?>
                    <div class="alert alert-warning p-4 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded mb-6">
                        <?php esc_html_e( 'Please install and activate the Contact Form 7 plugin to use this template.', 'aqualuxe' ); ?>
                    </div>
                    
                    <!-- Fallback basic form -->
                    <form action="#" method="post" class="contact-form space-y-6">
                        <div class="form-group">
                            <label for="name" class="block text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e( 'Your Name', 'aqualuxe' ); ?> <span class="text-red-600">*</span></label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e( 'Your Email', 'aqualuxe' ); ?> <span class="text-red-600">*</span></label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="subject" class="block text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e( 'Subject', 'aqualuxe' ); ?></label>
                            <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        </div>
                        
                        <div class="form-group">
                            <label for="message" class="block text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e( 'Your Message', 'aqualuxe' ); ?> <span class="text-red-600">*</span></label>
                            <textarea id="message" name="message" rows="6" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" required></textarea>
                        </div>
                        
                        <div class="form-submit">
                            <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors">
                                <?php esc_html_e( 'Send Message', 'aqualuxe' ); ?>
                            </button>
                        </div>
                    </form>
                    <?php
                }
                ?>
            </div>
            
            <div class="contact-info-container">
                <div class="contact-info bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md mb-8">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-gray-100"><?php esc_html_e( 'Contact Information', 'aqualuxe' ); ?></h2>
                    
                    <div class="contact-details space-y-4">
                        <?php if ( ! empty( $contact_info['address'] ) ) : ?>
                            <div class="contact-item flex items-start">
                                <div class="contact-icon mr-4 mt-1 text-blue-600 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php esc_html_e( 'Address', 'aqualuxe' ); ?></h3>
                                    <p class="text-gray-600 dark:text-gray-400"><?php echo nl2br( esc_html( $contact_info['address'] ) ); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $contact_info['phone'] ) ) : ?>
                            <div class="contact-item flex items-start">
                                <div class="contact-icon mr-4 mt-1 text-blue-600 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php esc_html_e( 'Phone', 'aqualuxe' ); ?></h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $contact_info['phone'] ) ); ?>" class="hover:text-blue-600 dark:hover:text-blue-400">
                                            <?php echo esc_html( $contact_info['phone'] ); ?>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $contact_info['email'] ) ) : ?>
                            <div class="contact-item flex items-start">
                                <div class="contact-icon mr-4 mt-1 text-blue-600 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php esc_html_e( 'Email', 'aqualuxe' ); ?></h3>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        <a href="mailto:<?php echo esc_attr( $contact_info['email'] ); ?>" class="hover:text-blue-600 dark:hover:text-blue-400">
                                            <?php echo esc_html( $contact_info['email'] ); ?>
                                        </a>
                                    </p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $contact_info['hours'] ) ) : ?>
                            <div class="contact-item flex items-start">
                                <div class="contact-icon mr-4 mt-1 text-blue-600 dark:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100"><?php esc_html_e( 'Business Hours', 'aqualuxe' ); ?></h3>
                                    <p class="text-gray-600 dark:text-gray-400"><?php echo nl2br( esc_html( $contact_info['hours'] ) ); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php
                    // Display social media links if available
                    $social_links = array(
                        'facebook' => get_theme_mod( 'aqualuxe_social_facebook', '' ),
                        'twitter' => get_theme_mod( 'aqualuxe_social_twitter', '' ),
                        'instagram' => get_theme_mod( 'aqualuxe_social_instagram', '' ),
                        'linkedin' => get_theme_mod( 'aqualuxe_social_linkedin', '' ),
                        'youtube' => get_theme_mod( 'aqualuxe_social_youtube', '' ),
                    );
                    
                    $has_social = false;
                    foreach ( $social_links as $link ) {
                        if ( ! empty( $link ) ) {
                            $has_social = true;
                            break;
                        }
                    }
                    
                    if ( $has_social ) :
                    ?>
                        <div class="social-links mt-8">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4"><?php esc_html_e( 'Follow Us', 'aqualuxe' ); ?></h3>
                            
                            <div class="flex gap-4">
                                <?php if ( ! empty( $social_links['facebook'] ) ) : ?>
                                    <a href="<?php echo esc_url( $social_links['facebook'] ); ?>" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-400" target="_blank" rel="noopener noreferrer">
                                        <span class="sr-only"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $social_links['twitter'] ) ) : ?>
                                    <a href="<?php echo esc_url( $social_links['twitter'] ); ?>" class="text-gray-600 hover:text-blue-400 dark:text-gray-400 dark:hover:text-blue-300" target="_blank" rel="noopener noreferrer">
                                        <span class="sr-only"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $social_links['instagram'] ) ) : ?>
                                    <a href="<?php echo esc_url( $social_links['instagram'] ); ?>" class="text-gray-600 hover:text-pink-600 dark:text-gray-400 dark:hover:text-pink-400" target="_blank" rel="noopener noreferrer">
                                        <span class="sr-only"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $social_links['linkedin'] ) ) : ?>
                                    <a href="<?php echo esc_url( $social_links['linkedin'] ); ?>" class="text-gray-600 hover:text-blue-700 dark:text-gray-400 dark:hover:text-blue-500" target="_blank" rel="noopener noreferrer">
                                        <span class="sr-only"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $social_links['youtube'] ) ) : ?>
                                    <a href="<?php echo esc_url( $social_links['youtube'] ); ?>" class="text-gray-600 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-500" target="_blank" rel="noopener noreferrer">
                                        <span class="sr-only"><?php esc_html_e( 'YouTube', 'aqualuxe' ); ?></span>
                                        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ( $show_map && ! empty( $map_location ) ) : ?>
                    <div class="contact-map bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md overflow-hidden">
                        <div class="map-container h-[400px]">
                            <?php
                            // Check if we're using Google Maps or OpenStreetMap
                            $map_provider = get_theme_mod( 'aqualuxe_map_provider', 'google' );
                            
                            if ( $map_provider === 'google' ) {
                                // Google Maps API Key
                                $google_maps_api_key = get_theme_mod( 'aqualuxe_google_maps_api_key', '' );
                                
                                if ( ! empty( $google_maps_api_key ) ) {
                                    ?>
                                    <div class="google-map h-full" id="google-map" data-location="<?php echo esc_attr( $map_location ); ?>" data-zoom="<?php echo esc_attr( $map_zoom ); ?>"></div>
                                    <script>
                                        function initMap() {
                                            const mapElement = document.getElementById('google-map');
                                            const location = mapElement.dataset.location.split(',');
                                            const zoom = parseInt(mapElement.dataset.zoom);
                                            
                                            const map = new google.maps.Map(mapElement, {
                                                center: { lat: parseFloat(location[0]), lng: parseFloat(location[1]) },
                                                zoom: zoom,
                                                styles: [/* Custom map styles would go here */]
                                            });
                                            
                                            const marker = new google.maps.Marker({
                                                position: { lat: parseFloat(location[0]), lng: parseFloat(location[1]) },
                                                map: map,
                                                title: '<?php echo esc_js( get_bloginfo( 'name' ) ); ?>'
                                            });
                                        }
                                    </script>
                                    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr( $google_maps_api_key ); ?>&callback=initMap" async defer></script>
                                    <?php
                                } else {
                                    echo '<div class="map-placeholder bg-gray-200 dark:bg-gray-700 h-full flex items-center justify-center">';
                                    esc_html_e( 'Please add a Google Maps API key in the theme customizer.', 'aqualuxe' );
                                    echo '</div>';
                                }
                            } else {
                                // OpenStreetMap (no API key required)
                                ?>
                                <div class="leaflet-map h-full" id="leaflet-map" data-location="<?php echo esc_attr( $map_location ); ?>" data-zoom="<?php echo esc_attr( $map_zoom ); ?>"></div>
                                <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
                                <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const mapElement = document.getElementById('leaflet-map');
                                        const location = mapElement.dataset.location.split(',');
                                        const zoom = parseInt(mapElement.dataset.zoom);
                                        
                                        const map = L.map('leaflet-map').setView([parseFloat(location[0]), parseFloat(location[1])], zoom);
                                        
                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
                                        }).addTo(map);
                                        
                                        L.marker([parseFloat(location[0]), parseFloat(location[1])]).addTo(map)
                                            .bindPopup('<?php echo esc_js( get_bloginfo( 'name' ) ); ?>')
                                            .openPopup();
                                    });
                                </script>
                                <?php
                            }
                            ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main><!-- #main -->

<?php
get_footer();