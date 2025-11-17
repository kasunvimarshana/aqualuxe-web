<?php
/**
 * Template Name: Contact Page
 *
 * The template for displaying the contact page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Page header
    if (get_theme_mod('aqualuxe_page_header_enable', true)) :
        $page_title = get_the_title();
        $page_subtitle = get_post_meta(get_the_ID(), 'page_subtitle', true);
        $page_header_bg = get_post_meta(get_the_ID(), 'page_header_bg', true);
        
        if (empty($page_header_bg)) {
            $page_header_bg = get_theme_mod('aqualuxe_page_header_bg', '');
        }
    ?>
        <section class="page-header-wrapper" <?php if (!empty($page_header_bg)) : ?>style="background-image: url('<?php echo esc_url($page_header_bg); ?>');"<?php endif; ?>>
            <div class="container">
                <div class="page-header text-center">
                    <h1 class="page-title"><?php echo esc_html($page_title); ?></h1>
                    
                    <?php if (!empty($page_subtitle)) : ?>
                        <div class="page-subtitle"><?php echo esc_html($page_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php
                    if (function_exists('aqualuxe_breadcrumbs') && get_theme_mod('aqualuxe_breadcrumbs_enable', true)) {
                        aqualuxe_breadcrumbs();
                    }
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="contact-section section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <div class="contact-info" data-aos="fade-right">
                        <?php
                        // Contact info title
                        $contact_info_title = get_theme_mod('aqualuxe_contact_info_title', __('Contact Information', 'aqualuxe'));
                        $contact_info_text = get_theme_mod('aqualuxe_contact_info_text', '');
                        
                        if (!empty($contact_info_title)) :
                        ?>
                            <h3 class="contact-info-title"><?php echo esc_html($contact_info_title); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($contact_info_text)) : ?>
                            <div class="contact-info-text">
                                <?php echo wp_kses_post($contact_info_text); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="contact-details">
                            <?php
                            // Address
                            $address = get_theme_mod('aqualuxe_contact_address', '');
                            if (!empty($address)) :
                            ?>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h4><?php esc_html_e('Address', 'aqualuxe'); ?></h4>
                                        <p><?php echo wp_kses_post($address); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Phone
                            $phone = get_theme_mod('aqualuxe_contact_phone', '');
                            if (!empty($phone)) :
                            ?>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-phone-alt"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h4><?php esc_html_e('Phone', 'aqualuxe'); ?></h4>
                                        <p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>"><?php echo esc_html($phone); ?></a></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Email
                            $email = get_theme_mod('aqualuxe_contact_email', '');
                            if (!empty($email)) :
                            ?>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h4><?php esc_html_e('Email', 'aqualuxe'); ?></h4>
                                        <p><a href="mailto:<?php echo esc_attr($email); ?>"><?php echo esc_html($email); ?></a></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Working Hours
                            $working_hours = get_theme_mod('aqualuxe_contact_hours', '');
                            if (!empty($working_hours)) :
                            ?>
                                <div class="contact-item">
                                    <div class="contact-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="contact-text">
                                        <h4><?php esc_html_e('Working Hours', 'aqualuxe'); ?></h4>
                                        <p><?php echo wp_kses_post($working_hours); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php
                        // Social Media
                        $facebook = get_theme_mod('aqualuxe_social_facebook', '');
                        $twitter = get_theme_mod('aqualuxe_social_twitter', '');
                        $instagram = get_theme_mod('aqualuxe_social_instagram', '');
                        $linkedin = get_theme_mod('aqualuxe_social_linkedin', '');
                        $youtube = get_theme_mod('aqualuxe_social_youtube', '');
                        
                        if (!empty($facebook) || !empty($twitter) || !empty($instagram) || !empty($linkedin) || !empty($youtube)) :
                        ?>
                            <div class="contact-social">
                                <h4><?php esc_html_e('Follow Us', 'aqualuxe'); ?></h4>
                                <div class="social-icons">
                                    <?php if (!empty($facebook)) : ?>
                                        <a href="<?php echo esc_url($facebook); ?>" target="_blank"><i class="fab fa-facebook-f"></i></a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($twitter)) : ?>
                                        <a href="<?php echo esc_url($twitter); ?>" target="_blank"><i class="fab fa-twitter"></i></a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($instagram)) : ?>
                                        <a href="<?php echo esc_url($instagram); ?>" target="_blank"><i class="fab fa-instagram"></i></a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($linkedin)) : ?>
                                        <a href="<?php echo esc_url($linkedin); ?>" target="_blank"><i class="fab fa-linkedin-in"></i></a>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($youtube)) : ?>
                                        <a href="<?php echo esc_url($youtube); ?>" target="_blank"><i class="fab fa-youtube"></i></a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <div class="col-lg-7">
                    <div class="contact-form-wrapper" data-aos="fade-left">
                        <?php
                        // Contact form title
                        $contact_form_title = get_theme_mod('aqualuxe_contact_form_title', __('Send Us a Message', 'aqualuxe'));
                        $contact_form_text = get_theme_mod('aqualuxe_contact_form_text', '');
                        
                        if (!empty($contact_form_title)) :
                        ?>
                            <h3 class="contact-form-title"><?php echo esc_html($contact_form_title); ?></h3>
                        <?php endif; ?>
                        
                        <?php if (!empty($contact_form_text)) : ?>
                            <div class="contact-form-text">
                                <?php echo wp_kses_post($contact_form_text); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="contact-form">
                            <?php
                            // Contact form shortcode
                            $contact_form_shortcode = get_theme_mod('aqualuxe_contact_form_shortcode', '');
                            
                            if (!empty($contact_form_shortcode)) {
                                echo do_shortcode($contact_form_shortcode);
                            } else {
                                // Default contact form
                            ?>
                                <form id="contact-form" class="contact-form">
                                    <div class="form-message"></div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                                                <input type="text" id="name" name="name" class="form-control" required>
                                            </div>
                                        </div>
                                        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="email"><?php esc_html_e('Your Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                                                <input type="email" id="email" name="email" class="form-control" required>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="subject"><?php esc_html_e('Subject', 'aqualuxe'); ?> <span class="required">*</span></label>
                                        <input type="text" id="subject" name="subject" class="form-control" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="message"><?php esc_html_e('Your Message', 'aqualuxe'); ?> <span class="required">*</span></label>
                                        <textarea id="message" name="message" class="form-control" rows="5" required></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary btn-submit">
                                            <span class="btn-text"><?php esc_html_e('Send Message', 'aqualuxe'); ?></span>
                                            <span class="btn-loading"><i class="fas fa-spinner fa-spin"></i></span>
                                        </button>
                                    </div>
                                </form>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php
    // Google Map
    $map_enable = get_theme_mod('aqualuxe_contact_map_enable', true);
    $map_embed = get_theme_mod('aqualuxe_contact_map_embed', '');
    
    if ($map_enable && !empty($map_embed)) :
    ?>
        <section class="contact-map-section">
            <div class="contact-map">
                <?php echo wp_kses_post($map_embed); ?>
            </div>
        </section>
    <?php endif; ?>
    
    <?php
    // Page content
    while (have_posts()) :
        the_post();
        
        if (get_the_content()) :
    ?>
        <section class="page-content-section section">
            <div class="container">
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            </div>
        </section>
    <?php 
        endif;
    endwhile;
    ?>

</main><!-- #primary -->

<?php
get_footer();