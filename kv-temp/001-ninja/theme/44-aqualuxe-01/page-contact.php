<?php
/**
 * Template Name: Contact Page
 *
 * The template for displaying the contact page
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();

// Get contact page options
$contact_address = get_theme_mod('aqualuxe_contact_address', '');
$contact_phone = get_theme_mod('aqualuxe_contact_phone', '');
$contact_email = get_theme_mod('aqualuxe_contact_email', '');
$contact_hours = get_theme_mod('aqualuxe_contact_hours', '');
$google_map_embed = get_theme_mod('aqualuxe_google_map_embed', '');
$contact_form_shortcode = get_theme_mod('aqualuxe_contact_form_shortcode', '');
?>

<main id="primary" class="site-main">
    <?php
    // Page Header
    get_template_part('template-parts/components/page-header');
    ?>

    <div class="container">
        <div class="contact-page-content">
            <div class="row">
                <div class="col-lg-6">
                    <div class="contact-info">
                        <h2><?php echo esc_html__('Get In Touch', 'aqualuxe'); ?></h2>
                        
                        <?php
                        while (have_posts()) :
                            the_post();
                            
                            // Display the content
                            the_content();
                            
                        endwhile; // End of the loop.
                        ?>
                        
                        <div class="contact-details">
                            <?php if (!empty($contact_address)) : ?>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h3><?php echo esc_html__('Address', 'aqualuxe'); ?></h3>
                                        <p><?php echo wp_kses_post($contact_address); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($contact_phone)) : ?>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h3><?php echo esc_html__('Phone', 'aqualuxe'); ?></h3>
                                        <p>
                                            <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_phone)); ?>">
                                                <?php echo esc_html($contact_phone); ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($contact_email)) : ?>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="far fa-envelope"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h3><?php echo esc_html__('Email', 'aqualuxe'); ?></h3>
                                        <p>
                                            <a href="mailto:<?php echo esc_attr($contact_email); ?>">
                                                <?php echo esc_html($contact_email); ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($contact_hours)) : ?>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="far fa-clock"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h3><?php echo esc_html__('Business Hours', 'aqualuxe'); ?></h3>
                                        <p><?php echo wp_kses_post($contact_hours); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="contact-social">
                            <h3><?php echo esc_html__('Follow Us', 'aqualuxe'); ?></h3>
                            <?php aqualuxe_social_links(); ?>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-6">
                    <div class="contact-form-wrapper">
                        <h2><?php echo esc_html__('Send Us a Message', 'aqualuxe'); ?></h2>
                        
                        <?php if (!empty($contact_form_shortcode)) : ?>
                            <div class="contact-form">
                                <?php echo do_shortcode($contact_form_shortcode); ?>
                            </div>
                        <?php else : ?>
                            <div class="contact-form">
                                <form id="aqualuxe-contact-form" class="contact-form" method="post">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact-name"><?php echo esc_html__('Your Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                                                <input type="text" id="contact-name" name="contact-name" class="form-control" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact-email"><?php echo esc_html__('Your Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                                                <input type="email" id="contact-email" name="contact-email" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="contact-subject"><?php echo esc_html__('Subject', 'aqualuxe'); ?></label>
                                        <input type="text" id="contact-subject" name="contact-subject" class="form-control">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="contact-message"><?php echo esc_html__('Your Message', 'aqualuxe'); ?> <span class="required">*</span></label>
                                        <textarea id="contact-message" name="contact-message" class="form-control" rows="6" required></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <input type="hidden" name="action" value="aqualuxe_contact_form">
                                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce('aqualuxe_contact_form_nonce'); ?>">
                                        <button type="submit" class="btn btn-primary"><?php echo esc_html__('Send Message', 'aqualuxe'); ?></button>
                                    </div>
                                    
                                    <div class="form-response"></div>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (!empty($google_map_embed)) : ?>
            <div class="contact-map">
                <div class="map-container">
                    <?php echo wp_kses_post($google_map_embed); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <?php
        // Display locations if available
        $locations_args = array(
            'post_type'      => 'location',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        );
        
        $locations_query = new WP_Query($locations_args);
        
        if (post_type_exists('location') && $locations_query->have_posts()) :
        ?>
        <div class="contact-locations">
            <div class="section-header text-center">
                <h2><?php echo esc_html__('Our Locations', 'aqualuxe'); ?></h2>
                <p><?php echo esc_html__('Visit us at one of our convenient locations', 'aqualuxe'); ?></p>
            </div>
            
            <div class="row">
                <?php
                while ($locations_query->have_posts()) :
                    $locations_query->the_post();
                    
                    // Get location details
                    $location_address = get_post_meta(get_the_ID(), 'location_address', true);
                    $location_phone = get_post_meta(get_the_ID(), 'location_phone', true);
                    $location_email = get_post_meta(get_the_ID(), 'location_email', true);
                    $location_hours = get_post_meta(get_the_ID(), 'location_hours', true);
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="location-card">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="location-image">
                                    <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="location-content">
                                <h3 class="location-title"><?php the_title(); ?></h3>
                                
                                <?php if (!empty($location_address)) : ?>
                                    <div class="location-address">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span><?php echo wp_kses_post($location_address); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($location_phone)) : ?>
                                    <div class="location-phone">
                                        <i class="fas fa-phone-alt"></i>
                                        <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $location_phone)); ?>">
                                            <?php echo esc_html($location_phone); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($location_email)) : ?>
                                    <div class="location-email">
                                        <i class="far fa-envelope"></i>
                                        <a href="mailto:<?php echo esc_attr($location_email); ?>">
                                            <?php echo esc_html($location_email); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($location_hours)) : ?>
                                    <div class="location-hours">
                                        <i class="far fa-clock"></i>
                                        <span><?php echo wp_kses_post($location_hours); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm">
                                    <?php echo esc_html__('View Details', 'aqualuxe'); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
        </div>
        <?php endif; ?>
        
        <?php
        // Call to Action Section
        get_template_part('template-parts/components/cta', 'contact');
        ?>
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();