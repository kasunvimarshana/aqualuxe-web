<?php
/**
 * Template Name: Contact Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div class="container">
    <div class="row no-sidebar">
        <main id="primary" class="site-main">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-contact-page'); ?>>
                    <header class="aqualuxe-contact-header">
                        <?php the_title('<h1 class="aqualuxe-contact-title">', '</h1>'); ?>
                        
                        <?php if (has_excerpt()) : ?>
                            <div class="aqualuxe-contact-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <div class="aqualuxe-contact-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                
                <div class="aqualuxe-contact-container">
                    <div class="aqualuxe-contact-info">
                        <?php
                        // Get contact information
                        $phone = aqualuxe_get_option('contact_phone', '');
                        $email = aqualuxe_get_option('contact_email', '');
                        $address = aqualuxe_get_option('contact_address', '');
                        $hours = aqualuxe_get_option('contact_hours', '');
                        
                        // Get ACF fields if available
                        if (function_exists('get_field')) {
                            $phone = get_field('contact_phone') ? get_field('contact_phone') : $phone;
                            $email = get_field('contact_email') ? get_field('contact_email') : $email;
                            $address = get_field('contact_address') ? get_field('contact_address') : $address;
                            $hours = get_field('contact_hours') ? get_field('contact_hours') : $hours;
                        }
                        ?>
                        
                        <div class="aqualuxe-contact-info-item">
                            <div class="aqualuxe-contact-info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"></path></svg>
                            </div>
                            <div class="aqualuxe-contact-info-content">
                                <h3><?php esc_html_e('Phone', 'aqualuxe'); ?></h3>
                                <?php if ($phone) : ?>
                                    <p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></p>
                                <?php else : ?>
                                    <p><?php esc_html_e('(123) 456-7890', 'aqualuxe'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="aqualuxe-contact-info-item">
                            <div class="aqualuxe-contact-info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"></path></svg>
                            </div>
                            <div class="aqualuxe-contact-info-content">
                                <h3><?php esc_html_e('Email', 'aqualuxe'); ?></h3>
                                <?php if ($email) : ?>
                                    <p><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
                                <?php else : ?>
                                    <p><?php esc_html_e('info@aqualuxe.example.com', 'aqualuxe'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="aqualuxe-contact-info-item">
                            <div class="aqualuxe-contact-info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path></svg>
                            </div>
                            <div class="aqualuxe-contact-info-content">
                                <h3><?php esc_html_e('Address', 'aqualuxe'); ?></h3>
                                <?php if ($address) : ?>
                                    <p><?php echo wp_kses_post(nl2br($address)); ?></p>
                                <?php else : ?>
                                    <p><?php esc_html_e('123 Aquarium Street', 'aqualuxe'); ?><br><?php esc_html_e('Coral City, Ocean 12345', 'aqualuxe'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="aqualuxe-contact-info-item">
                            <div class="aqualuxe-contact-info-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path></svg>
                            </div>
                            <div class="aqualuxe-contact-info-content">
                                <h3><?php esc_html_e('Business Hours', 'aqualuxe'); ?></h3>
                                <?php if ($hours) : ?>
                                    <p><?php echo wp_kses_post(nl2br($hours)); ?></p>
                                <?php else : ?>
                                    <p><?php esc_html_e('Monday - Friday: 9am - 6pm', 'aqualuxe'); ?><br><?php esc_html_e('Saturday: 10am - 4pm', 'aqualuxe'); ?><br><?php esc_html_e('Sunday: Closed', 'aqualuxe'); ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php
                        // Social media links
                        $facebook = aqualuxe_get_option('social_facebook', '');
                        $twitter = aqualuxe_get_option('social_twitter', '');
                        $instagram = aqualuxe_get_option('social_instagram', '');
                        $youtube = aqualuxe_get_option('social_youtube', '');
                        $linkedin = aqualuxe_get_option('social_linkedin', '');
                        $pinterest = aqualuxe_get_option('social_pinterest', '');
                        
                        if ($facebook || $twitter || $instagram || $youtube || $linkedin || $pinterest) :
                        ?>
                            <div class="aqualuxe-contact-social">
                                <h3><?php esc_html_e('Follow Us', 'aqualuxe'); ?></h3>
                                <div class="aqualuxe-contact-social-links">
                                    <?php if ($facebook) : ?>
                                        <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-contact-social-link aqualuxe-contact-social-facebook">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 2C6.5 2 2 6.5 2 12c0 5 3.7 9.1 8.4 9.9v-7H7.9V12h2.5V9.8c0-2.5 1.5-3.9 3.8-3.9 1.1 0 2.2.2 2.2.2v2.5h-1.3c-1.2 0-1.6.8-1.6 1.6V12h2.8l-.4 2.9h-2.3v7C18.3 21.1 22 17 22 12c0-5.5-4.5-10-10-10z"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($twitter) : ?>
                                        <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-contact-social-link aqualuxe-contact-social-twitter">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($instagram) : ?>
                                        <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-contact-social-link aqualuxe-contact-social-instagram">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($youtube) : ?>
                                        <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-contact-social-link aqualuxe-contact-social-youtube">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M21.582,6.186c-0.23-0.86-0.908-1.538-1.768-1.768C18.254,4,12,4,12,4S5.746,4,4.186,4.418 c-0.86,0.23-1.538,0.908-1.768,1.768C2,7.746,2,12,2,12s0,4.254,0.418,5.814c0.23,0.86,0.908,1.538,1.768,1.768 C5.746,20,12,20,12,20s6.254,0,7.814-0.418c0.861-0.23,1.538-0.908,1.768-1.768C22,16.254,22,12,22,12S22,7.746,21.582,6.186z M10,15.464V8.536L16,12L10,15.464z"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($linkedin) : ?>
                                        <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-contact-social-link aqualuxe-contact-social-linkedin">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M19 3a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14m-.5 15.5v-5.3a3.26 3.26 0 0 0-3.26-3.26c-.85 0-1.84.52-2.32 1.3v-1.11h-2.79v8.37h2.79v-4.93c0-.77.62-1.4 1.39-1.4a1.4 1.4 0 0 1 1.4 1.4v4.93h2.79M6.88 8.56a1.68 1.68 0 0 0 1.68-1.68c0-.93-.75-1.69-1.68-1.69a1.69 1.69 0 0 0-1.69 1.69c0 .93.76 1.68 1.69 1.68m1.39 9.94v-8.37H5.5v8.37h2.77z"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($pinterest) : ?>
                                        <a href="<?php echo esc_url($pinterest); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-contact-social-link aqualuxe-contact-social-pinterest">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path d="M9.04 21.54c.96.29 1.93.46 2.96.46a10 10 0 0 0 10-10A10 10 0 0 0 12 2 10 10 0 0 0 2 12c0 4.25 2.67 7.9 6.44 9.34-.09-.78-.18-2.07 0-2.96l1.15-4.94s-.29-.58-.29-1.5c0-1.38.86-2.41 1.84-2.41.86 0 1.26.63 1.26 1.44 0 .86-.57 2.09-.86 3.27-.17.98.52 1.84 1.52 1.84 1.78 0 3.16-1.9 3.16-4.58 0-2.4-1.72-4.04-4.19-4.04-2.82 0-4.48 2.1-4.48 4.31 0 .86.28 1.73.74 2.3.09.06.09.14.06.29l-.29 1.09c0 .17-.11.23-.28.11-1.28-.56-2.02-2.38-2.02-3.85 0-3.16 2.24-6.03 6.56-6.03 3.44 0 6.12 2.47 6.12 5.75 0 3.44-2.13 6.2-5.18 6.2-.97 0-1.92-.52-2.26-1.13l-.67 2.37c-.23.86-.86 2.01-1.29 2.7v-.03z"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="aqualuxe-contact-form">
                        <h2><?php esc_html_e('Send Us a Message', 'aqualuxe'); ?></h2>
                        
                        <?php
                        // Check if Contact Form 7 is active
                        if (function_exists('wpcf7_contact_form')) {
                            // Get contact form ID from ACF if available
                            $contact_form_id = function_exists('get_field') ? get_field('contact_form_id') : '';
                            
                            if ($contact_form_id) {
                                echo do_shortcode('[contact-form-7 id="' . esc_attr($contact_form_id) . '"]');
                            } else {
                                // Try to get the first contact form
                                $contact_forms = wpcf7_contact_form_tags();
                                
                                if (!empty($contact_forms)) {
                                    echo do_shortcode('[contact-form-7 id="' . esc_attr($contact_forms[0]->id()) . '"]');
                                } else {
                                    // Fallback contact form
                                    ?>
                                    <form class="aqualuxe-contact-form-fallback">
                                        <div class="aqualuxe-form-row">
                                            <div class="aqualuxe-form-group">
                                                <label for="name"><?php esc_html_e('Name', 'aqualuxe'); ?></label>
                                                <input type="text" id="name" name="name" required>
                                            </div>
                                            
                                            <div class="aqualuxe-form-group">
                                                <label for="email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
                                                <input type="email" id="email" name="email" required>
                                            </div>
                                        </div>
                                        
                                        <div class="aqualuxe-form-group">
                                            <label for="subject"><?php esc_html_e('Subject', 'aqualuxe'); ?></label>
                                            <input type="text" id="subject" name="subject" required>
                                        </div>
                                        
                                        <div class="aqualuxe-form-group">
                                            <label for="message"><?php esc_html_e('Message', 'aqualuxe'); ?></label>
                                            <textarea id="message" name="message" rows="5" required></textarea>
                                        </div>
                                        
                                        <div class="aqualuxe-form-submit">
                                            <button type="submit" class="button button-primary"><?php esc_html_e('Send Message', 'aqualuxe'); ?></button>
                                        </div>
                                        
                                        <div class="aqualuxe-form-note">
                                            <p><?php esc_html_e('Note: This is a demo form. To make it functional, please install Contact Form 7 or another form plugin.', 'aqualuxe'); ?></p>
                                        </div>
                                    </form>
                                    <?php
                                }
                            }
                        } else {
                            // Fallback contact form
                            ?>
                            <form class="aqualuxe-contact-form-fallback">
                                <div class="aqualuxe-form-row">
                                    <div class="aqualuxe-form-group">
                                        <label for="name"><?php esc_html_e('Name', 'aqualuxe'); ?></label>
                                        <input type="text" id="name" name="name" required>
                                    </div>
                                    
                                    <div class="aqualuxe-form-group">
                                        <label for="email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
                                        <input type="email" id="email" name="email" required>
                                    </div>
                                </div>
                                
                                <div class="aqualuxe-form-group">
                                    <label for="subject"><?php esc_html_e('Subject', 'aqualuxe'); ?></label>
                                    <input type="text" id="subject" name="subject" required>
                                </div>
                                
                                <div class="aqualuxe-form-group">
                                    <label for="message"><?php esc_html_e('Message', 'aqualuxe'); ?></label>
                                    <textarea id="message" name="message" rows="5" required></textarea>
                                </div>
                                
                                <div class="aqualuxe-form-submit">
                                    <button type="submit" class="button button-primary"><?php esc_html_e('Send Message', 'aqualuxe'); ?></button>
                                </div>
                                
                                <div class="aqualuxe-form-note">
                                    <p><?php esc_html_e('Note: This is a demo form. To make it functional, please install Contact Form 7 or another form plugin.', 'aqualuxe'); ?></p>
                                </div>
                            </form>
                            <?php
                        }
                        ?>
                    </div>
                </div>
                
                <?php
                // Google Maps
                $map_api_key = aqualuxe_get_option('google_maps_api_key', '');
                $map_latitude = aqualuxe_get_option('map_latitude', '');
                $map_longitude = aqualuxe_get_option('map_longitude', '');
                
                // Get ACF fields if available
                if (function_exists('get_field')) {
                    $map_api_key = get_field('google_maps_api_key') ? get_field('google_maps_api_key') : $map_api_key;
                    $map_latitude = get_field('map_latitude') ? get_field('map_latitude') : $map_latitude;
                    $map_longitude = get_field('map_longitude') ? get_field('map_longitude') : $map_longitude;
                    
                    // Check if ACF Google Map field is available
                    $map = get_field('map');
                    
                    if ($map) {
                        $map_latitude = $map['lat'];
                        $map_longitude = $map['lng'];
                    }
                }
                
                if ($map_api_key && $map_latitude && $map_longitude) :
                ?>
                    <div class="aqualuxe-contact-map">
                        <div id="aqualuxe-google-map" data-lat="<?php echo esc_attr($map_latitude); ?>" data-lng="<?php echo esc_attr($map_longitude); ?>"></div>
                        
                        <script>
                            function initMap() {
                                var mapElement = document.getElementById('aqualuxe-google-map');
                                var lat = parseFloat(mapElement.getAttribute('data-lat'));
                                var lng = parseFloat(mapElement.getAttribute('data-lng'));
                                
                                var map = new google.maps.Map(mapElement, {
                                    center: {lat: lat, lng: lng},
                                    zoom: 15
                                });
                                
                                var marker = new google.maps.Marker({
                                    position: {lat: lat, lng: lng},
                                    map: map,
                                    title: '<?php echo esc_js(get_bloginfo('name')); ?>'
                                });
                            }
                        </script>
                        
                        <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo esc_attr($map_api_key); ?>&callback=initMap"></script>
                    </div>
                <?php else : ?>
                    <div class="aqualuxe-contact-map-placeholder">
                        <div class="aqualuxe-contact-map-placeholder-inner">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="64" height="64"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"></path></svg>
                            <p><?php esc_html_e('Map will be displayed here. Please add Google Maps API key in theme options or ACF fields.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
            endwhile; // End of the loop.
            ?>
        </main><!-- #main -->
    </div>
</div>

<?php
get_footer();