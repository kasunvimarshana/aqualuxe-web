<?php
/**
 * Template Name: Contact Page
 *
 * This is the template for displaying the Contact page.
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main contact-page">
    <?php
    // Hero Section
    $hero_image = get_post_meta(get_the_ID(), 'aqualuxe_contact_hero_image', true);
    $hero_title = get_post_meta(get_the_ID(), 'aqualuxe_contact_hero_title', true) ?: get_the_title();
    $hero_subtitle = get_post_meta(get_the_ID(), 'aqualuxe_contact_hero_subtitle', true);
    
    if (empty($hero_image)) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    ?>
    
    <section class="contact-hero" <?php if ($hero_image) : ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_subtitle) : ?>
                    <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="container">
        <?php if (function_exists('aqualuxe_breadcrumbs')) : ?>
            <?php aqualuxe_breadcrumbs(); ?>
        <?php endif; ?>
    </div>

    <?php
    // Contact Information Section
    $contact_info_title = get_post_meta(get_the_ID(), 'aqualuxe_contact_info_title', true) ?: __('Get In Touch', 'aqualuxe');
    $contact_address = get_post_meta(get_the_ID(), 'aqualuxe_contact_address', true);
    $contact_phone = get_post_meta(get_the_ID(), 'aqualuxe_contact_phone', true);
    $contact_email = get_post_meta(get_the_ID(), 'aqualuxe_contact_email', true);
    $contact_hours = get_post_meta(get_the_ID(), 'aqualuxe_contact_hours', true);
    $contact_form_shortcode = get_post_meta(get_the_ID(), 'aqualuxe_contact_form_shortcode', true);
    ?>
    
    <section class="contact-info-section">
        <div class="container">
            <div class="contact-wrapper">
                <div class="contact-info">
                    <h2 class="section-title"><?php echo esc_html($contact_info_title); ?></h2>
                    
                    <div class="contact-details">
                        <?php if ($contact_address) : ?>
                            <div class="contact-detail">
                                <div class="contact-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                        <circle cx="12" cy="10" r="3"></circle>
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h3><?php esc_html_e('Address', 'aqualuxe'); ?></h3>
                                    <p><?php echo wp_kses_post(nl2br($contact_address)); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($contact_phone) : ?>
                            <div class="contact-detail">
                                <div class="contact-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h3><?php esc_html_e('Phone', 'aqualuxe'); ?></h3>
                                    <p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $contact_phone)); ?>"><?php echo esc_html($contact_phone); ?></a></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($contact_email) : ?>
                            <div class="contact-detail">
                                <div class="contact-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h3><?php esc_html_e('Email', 'aqualuxe'); ?></h3>
                                    <p><a href="mailto:<?php echo esc_attr($contact_email); ?>"><?php echo esc_html($contact_email); ?></a></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($contact_hours) : ?>
                            <div class="contact-detail">
                                <div class="contact-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="12" cy="12" r="10"></circle>
                                        <polyline points="12 6 12 12 16 14"></polyline>
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h3><?php esc_html_e('Business Hours', 'aqualuxe'); ?></h3>
                                    <p><?php echo wp_kses_post(nl2br($contact_hours)); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php
                    // Social Media Links
                    $facebook = get_theme_mod('aqualuxe_facebook_link');
                    $twitter = get_theme_mod('aqualuxe_twitter_link');
                    $instagram = get_theme_mod('aqualuxe_instagram_link');
                    $linkedin = get_theme_mod('aqualuxe_linkedin_link');
                    $youtube = get_theme_mod('aqualuxe_youtube_link');
                    
                    if ($facebook || $twitter || $instagram || $linkedin || $youtube) :
                    ?>
                    <div class="contact-social">
                        <h3><?php esc_html_e('Follow Us', 'aqualuxe'); ?></h3>
                        <div class="social-icons">
                            <?php if ($facebook) : ?>
                                <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" class="social-icon facebook" aria-label="<?php esc_attr_e('Facebook', 'aqualuxe'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($twitter) : ?>
                                <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="social-icon twitter" aria-label="<?php esc_attr_e('Twitter', 'aqualuxe'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($instagram) : ?>
                                <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" class="social-icon instagram" aria-label="<?php esc_attr_e('Instagram', 'aqualuxe'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($linkedin) : ?>
                                <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" class="social-icon linkedin" aria-label="<?php esc_attr_e('LinkedIn', 'aqualuxe'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($youtube) : ?>
                                <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener noreferrer" class="social-icon youtube" aria-label="<?php esc_attr_e('YouTube', 'aqualuxe'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="contact-form-container">
                    <h2 class="section-title"><?php esc_html_e('Send Us a Message', 'aqualuxe'); ?></h2>
                    
                    <?php if ($contact_form_shortcode) : ?>
                        <div class="contact-form">
                            <?php echo do_shortcode($contact_form_shortcode); ?>
                        </div>
                    <?php else : ?>
                        <div class="contact-form">
                            <form id="contact-form" class="default-contact-form">
                                <div class="form-group">
                                    <label for="name" class="form-label"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="required">*</span></label>
                                    <input type="text" id="name" name="name" class="form-input" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="form-label"><?php esc_html_e('Your Email', 'aqualuxe'); ?> <span class="required">*</span></label>
                                    <input type="email" id="email" name="email" class="form-input" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="phone" class="form-label"><?php esc_html_e('Phone Number', 'aqualuxe'); ?></label>
                                    <input type="tel" id="phone" name="phone" class="form-input">
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject" class="form-label"><?php esc_html_e('Subject', 'aqualuxe'); ?> <span class="required">*</span></label>
                                    <input type="text" id="subject" name="subject" class="form-input" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="message" class="form-label"><?php esc_html_e('Your Message', 'aqualuxe'); ?> <span class="required">*</span></label>
                                    <textarea id="message" name="message" class="form-textarea" rows="5" required></textarea>
                                </div>
                                
                                <div class="form-submit">
                                    <button type="submit" class="btn btn-primary"><?php esc_html_e('Send Message', 'aqualuxe'); ?></button>
                                </div>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    // Map Section
    $map_embed = get_post_meta(get_the_ID(), 'aqualuxe_contact_map_embed', true);
    
    if ($map_embed) :
    ?>
    <section class="contact-map">
        <div class="map-container">
            <?php echo wp_kses($map_embed, array(
                'iframe' => array(
                    'src'             => array(),
                    'height'          => array(),
                    'width'           => array(),
                    'frameborder'     => array(),
                    'style'           => array(),
                    'allowfullscreen' => array(),
                    'aria-hidden'     => array(),
                    'tabindex'        => array(),
                    'loading'         => array(),
                ),
            )); ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // FAQ Section
    $faq_title = get_post_meta(get_the_ID(), 'aqualuxe_contact_faq_title', true) ?: __('Frequently Asked Questions', 'aqualuxe');
    $faq_description = get_post_meta(get_the_ID(), 'aqualuxe_contact_faq_description', true);
    $faq_count = get_post_meta(get_the_ID(), 'aqualuxe_contact_faq_count', true) ?: 4;
    
    // Check if FAQs CPT exists
    if (post_type_exists('faqs')) :
    ?>
    <section class="contact-faq">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html($faq_title); ?></h2>
                <?php if ($faq_description) : ?>
                    <p class="section-description"><?php echo esc_html($faq_description); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="faq-wrapper">
                <?php
                $args = array(
                    'post_type' => 'faqs',
                    'posts_per_page' => intval($faq_count),
                    'orderby' => 'menu_order',
                    'order' => 'ASC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'faq_category',
                            'field'    => 'slug',
                            'terms'    => 'contact',
                        ),
                    ),
                );
                
                $faq_query = new WP_Query($args);
                
                if ($faq_query->have_posts()) :
                    echo '<div class="faq-accordion">';
                    
                    while ($faq_query->have_posts()) :
                        $faq_query->the_post();
                        ?>
                        <div class="faq-item">
                            <h3 class="faq-question">
                                <button class="accordion-trigger" aria-expanded="false">
                                    <?php the_title(); ?>
                                    <span class="accordion-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="plus-icon"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="minus-icon"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    </span>
                                </button>
                            </h3>
                            <div class="faq-answer" hidden>
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    
                    echo '</div>';
                    
                    wp_reset_postdata();
                else :
                    // Try without the taxonomy filter
                    $args = array(
                        'post_type' => 'faqs',
                        'posts_per_page' => intval($faq_count),
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                    );
                    
                    $faq_query = new WP_Query($args);
                    
                    if ($faq_query->have_posts()) :
                        echo '<div class="faq-accordion">';
                        
                        while ($faq_query->have_posts()) :
                            $faq_query->the_post();
                            ?>
                            <div class="faq-item">
                                <h3 class="faq-question">
                                    <button class="accordion-trigger" aria-expanded="false">
                                        <?php the_title(); ?>
                                        <span class="accordion-icon">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="plus-icon"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="minus-icon"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                        </span>
                                    </button>
                                </h3>
                                <div class="faq-answer" hidden>
                                    <?php the_content(); ?>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        
                        echo '</div>';
                        
                        wp_reset_postdata();
                    else :
                        echo '<p class="no-faqs">' . esc_html__('No FAQs found.', 'aqualuxe') . '</p>';
                    endif;
                endif;
                ?>
            </div>
            
            <?php
            // View all FAQs link
            $faq_archive_url = get_post_type_archive_link('faqs');
            if ($faq_archive_url) :
            ?>
            <div class="view-all-wrapper">
                <a href="<?php echo esc_url($faq_archive_url); ?>" class="btn btn-primary"><?php esc_html_e('View All FAQs', 'aqualuxe'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>
</main>

<?php
get_footer();