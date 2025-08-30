<?php
/**
 * Template part for displaying contact page content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Get contact information from theme options
$contact_address = aqualuxe_get_option('contact_address', '123 Aquarium Way, San Francisco, CA 94105');
$contact_phone = aqualuxe_get_option('contact_phone', '+1 (800) 123-4567');
$contact_email = aqualuxe_get_option('contact_email', 'info@aqualuxe.com');
$contact_hours = aqualuxe_get_option('contact_hours', 'Mon-Sat: 10am-8pm, Sunday: 12pm-6pm');
$google_maps_api_key = aqualuxe_get_option('google_maps_api_key', '');
$google_maps_latitude = aqualuxe_get_option('google_maps_latitude', '37.7749');
$google_maps_longitude = aqualuxe_get_option('google_maps_longitude', '-122.4194');
$google_maps_zoom = aqualuxe_get_option('google_maps_zoom', '14');
$contact_form_shortcode = aqualuxe_get_option('contact_form_shortcode', '');
?>

<div class="contact-page-content">
    <!-- Hero Section -->
    <section class="contact-hero bg-primary text-white py-16 md:py-24 mb-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold mb-6">Contact Us</h1>
                <p class="text-xl md:text-2xl opacity-90 mb-8">Get in Touch with Our Aquarium Experts</p>
                <div class="flex justify-center">
                    <div class="w-24 h-1 bg-accent"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Information Section -->
    <section class="contact-info py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Contact Cards -->
                <div class="contact-cards col-span-1 lg:col-span-1">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Visit Us -->
                        <div class="contact-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                            <div class="contact-card-icon text-4xl text-primary mb-4">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Visit Us</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                <?php echo nl2br(esc_html($contact_address)); ?>
                            </p>
                            <?php if ($google_maps_api_key) : ?>
                                <a href="https://maps.google.com/?q=<?php echo esc_attr($google_maps_latitude); ?>,<?php echo esc_attr($google_maps_longitude); ?>" target="_blank" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                                    Get Directions
                                    <i class="fas fa-arrow-right ml-2"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Call Us -->
                        <div class="contact-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                            <div class="contact-card-icon text-4xl text-primary mb-4">
                                <i class="fas fa-phone-alt"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Call Us</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                <?php echo esc_html($contact_phone); ?>
                            </p>
                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_phone)); ?>" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                                Call Now
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                        
                        <!-- Email Us -->
                        <div class="contact-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                            <div class="contact-card-icon text-4xl text-primary mb-4">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Email Us</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">
                                <?php echo esc_html($contact_email); ?>
                            </p>
                            <a href="mailto:<?php echo esc_attr($contact_email); ?>" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                                Send Email
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                        
                        <!-- Business Hours -->
                        <div class="contact-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                            <div class="contact-card-icon text-4xl text-primary mb-4">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3 class="text-xl font-bold mb-2">Business Hours</h3>
                            <p class="text-gray-600 dark:text-gray-400">
                                <?php echo nl2br(esc_html($contact_hours)); ?>
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="contact-form-wrapper col-span-1 lg:col-span-2">
                    <div class="bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 md:p-8">
                        <h2 class="text-2xl font-serif font-bold mb-6">Send Us a Message</h2>
                        
                        <?php if ($contact_form_shortcode) : ?>
                            <?php echo do_shortcode($contact_form_shortcode); ?>
                        <?php else : ?>
                            <form class="contact-form">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                    <div class="form-group">
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Name</label>
                                        <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Email</label>
                                        <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white" required>
                                    </div>
                                </div>
                                
                                <div class="form-group mb-6">
                                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white">
                                </div>
                                
                                <div class="form-group mb-6">
                                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
                                    <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white" required>
                                </div>
                                
                                <div class="form-group mb-6">
                                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Message</label>
                                    <textarea id="message" name="message" rows="6" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white" required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
                                        Send Message
                                    </button>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <?php if ($google_maps_api_key) : ?>
    <section class="contact-map py-12 md:py-16 bg-light-dark dark:bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Find Us
                </h2>
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Visit our showroom to explore our premium aquarium products and speak with our experts in person.
                </p>
            </div>
            
            <div class="map-container rounded-lg overflow-hidden shadow-lg" style="height: 500px;">
                <div id="google-map" style="width: 100%; height: 100%;"></div>
            </div>
        </div>
    </section>
    
    <script>
        function initMap() {
            const location = { lat: <?php echo esc_js($google_maps_latitude); ?>, lng: <?php echo esc_js($google_maps_longitude); ?> };
            const map = new google.maps.Map(document.getElementById("google-map"), {
                zoom: <?php echo esc_js($google_maps_zoom); ?>,
                center: location,
                styles: [
                    {
                        "featureType": "water",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#e9e9e9"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    },
                    {
                        "featureType": "landscape",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f5f5f5"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 17
                            }
                        ]
                    },
                    {
                        "featureType": "road.highway",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 29
                            },
                            {
                                "weight": 0.2
                            }
                        ]
                    },
                    {
                        "featureType": "road.arterial",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 18
                            }
                        ]
                    },
                    {
                        "featureType": "road.local",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "featureType": "poi",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f5f5f5"
                            },
                            {
                                "lightness": 21
                            }
                        ]
                    },
                    {
                        "featureType": "poi.park",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#dedede"
                            },
                            {
                                "lightness": 21
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.stroke",
                        "stylers": [
                            {
                                "visibility": "on"
                            },
                            {
                                "color": "#ffffff"
                            },
                            {
                                "lightness": 16
                            }
                        ]
                    },
                    {
                        "elementType": "labels.text.fill",
                        "stylers": [
                            {
                                "saturation": 36
                            },
                            {
                                "color": "#333333"
                            },
                            {
                                "lightness": 40
                            }
                        ]
                    },
                    {
                        "elementType": "labels.icon",
                        "stylers": [
                            {
                                "visibility": "off"
                            }
                        ]
                    },
                    {
                        "featureType": "transit",
                        "elementType": "geometry",
                        "stylers": [
                            {
                                "color": "#f2f2f2"
                            },
                            {
                                "lightness": 19
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.fill",
                        "stylers": [
                            {
                                "color": "#fefefe"
                            },
                            {
                                "lightness": 20
                            }
                        ]
                    },
                    {
                        "featureType": "administrative",
                        "elementType": "geometry.stroke",
                        "stylers": [
                            {
                                "color": "#fefefe"
                            },
                            {
                                "lightness": 17
                            },
                            {
                                "weight": 1.2
                            }
                        ]
                    }
                ]
            });
            
            const marker = new google.maps.Marker({
                position: location,
                map: map,
                title: "AquaLuxe",
                icon: {
                    url: "<?php echo esc_url(get_template_directory_uri() . '/assets/images/map-marker.png'); ?>",
                    scaledSize: new google.maps.Size(40, 40)
                }
            });
            
            const infowindow = new google.maps.InfoWindow({
                content: "<div style='padding: 10px;'><strong>AquaLuxe</strong><br><?php echo esc_js(str_replace("\n", '<br>', $contact_address)); ?></div>"
            });
            
            marker.addListener("click", () => {
                infowindow.open(map, marker);
            });
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr($google_maps_api_key); ?>&callback=initMap" async defer></script>
    <?php endif; ?>

    <!-- FAQ Section -->
    <section class="contact-faq py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        FAQ
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Frequently Asked Questions
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Find answers to common questions about contacting us and our response times.
                </p>
            </div>
            
            <div class="max-w-3xl mx-auto">
                <div class="faq-list space-y-4">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>What is your typical response time?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                We strive to respond to all inquiries within 24 hours during business days. For urgent matters, please call our customer service line for immediate assistance.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>Do you offer consultations outside of business hours?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Yes, we understand that many of our clients have busy schedules. We can arrange consultations outside of regular business hours by appointment. Please contact us to schedule a time that works for you.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>How can I request a quote for a custom aquarium?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                You can request a quote by filling out our contact form with details about your project, including dimensions, desired features, and any specific requirements. Alternatively, you can call us directly to discuss your project with one of our design consultants.
                            </p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 4 -->
                    <div class="faq-item bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left font-medium focus:outline-none">
                            <span>Do you offer virtual consultations?</span>
                            <i class="fas fa-chevron-down text-primary transition-transform"></i>
                        </button>
                        <div class="faq-answer px-6 pb-6">
                            <p class="text-gray-600 dark:text-gray-400">
                                Yes, we offer virtual consultations via video call for clients who prefer remote meetings or are located outside our service area. These consultations are just as comprehensive as in-person meetings and allow us to discuss your needs and provide recommendations.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="contact-newsletter py-12 md:py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-4">
                    Subscribe to Our Newsletter
                </h2>
                
                <p class="mb-6 text-white/80">
                    Stay updated with our latest products, special offers, and expert aquarium care tips.
                </p>
                
                <div class="newsletter-form">
                    <form action="#" method="post" class="flex flex-col md:flex-row gap-4 max-w-xl mx-auto">
                        <input type="email" name="email" placeholder="<?php esc_attr_e('Your Email Address', 'aqualuxe'); ?>" class="flex-grow px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
                        <button type="submit" class="px-6 py-3 bg-accent hover:bg-accent-dark text-dark font-medium rounded-lg transition-colors">
                            <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>