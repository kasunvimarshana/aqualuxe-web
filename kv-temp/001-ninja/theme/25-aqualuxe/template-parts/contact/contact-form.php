<?php
/**
 * Template part for displaying the contact page form and info section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_contact_form_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_contact_form_title', __('Send Us a Message', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_contact_form_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_contact_form_subtitle', __('Get in Touch', 'aqualuxe'));
}

$contact_form_shortcode = get_post_meta($page_id, '_aqualuxe_contact_form_shortcode', true);
if (!$contact_form_shortcode) {
    $contact_form_shortcode = get_theme_mod('aqualuxe_contact_form_shortcode', '');
}

$info_title = get_post_meta($page_id, '_aqualuxe_contact_info_title', true);
if (!$info_title) {
    $info_title = get_theme_mod('aqualuxe_contact_info_title', __('Contact Information', 'aqualuxe'));
}

$address = get_post_meta($page_id, '_aqualuxe_contact_address', true);
if (!$address) {
    $address = get_theme_mod('aqualuxe_contact_address', '123 Aquarium Street, Marine City, FL 12345, USA');
}

$phone = get_post_meta($page_id, '_aqualuxe_contact_phone', true);
if (!$phone) {
    $phone = get_theme_mod('aqualuxe_contact_phone', '+1 (555) 123-4567');
}

$email = get_post_meta($page_id, '_aqualuxe_contact_email', true);
if (!$email) {
    $email = get_theme_mod('aqualuxe_contact_email', 'info@aqualuxe.com');
}

$hours = get_post_meta($page_id, '_aqualuxe_contact_hours', true);
if (!$hours) {
    $hours = get_theme_mod('aqualuxe_contact_hours', "Monday - Friday: 9:00 AM - 6:00 PM\nSaturday: 10:00 AM - 4:00 PM\nSunday: Closed");
}

$section_background = get_post_meta($page_id, '_aqualuxe_contact_form_background', true);
if ($section_background === '') {
    $section_background = get_theme_mod('aqualuxe_contact_form_background', 'white');
}

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';
?>

<section class="contact-form-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ($section_subtitle) : ?>
                <div class="section-subtitle text-primary text-lg mb-2">
                    <?php echo esc_html($section_subtitle); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($section_title) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-bold mb-4">
                    <?php echo esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
        </div>
        
        <div class="contact-container flex flex-col lg:flex-row gap-12">
            <div class="contact-form-container w-full lg:w-2/3">
                <div class="contact-form bg-white rounded-lg shadow-md p-8">
                    <?php
                    if ($contact_form_shortcode && shortcode_exists('contact-form-7')) {
                        echo do_shortcode($contact_form_shortcode);
                    } else {
                        // Fallback form if no shortcode is provided
                        ?>
                        <form action="#" method="post" class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="form-group">
                                    <label for="name" class="block text-gray-700 font-medium mb-2"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="block text-gray-700 font-medium mb-2"><?php esc_html_e('Your Email', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="block text-gray-700 font-medium mb-2"><?php esc_html_e('Phone Number', 'aqualuxe'); ?></label>
                                <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50">
                            </div>
                            
                            <div class="form-group">
                                <label for="subject" class="block text-gray-700 font-medium mb-2"><?php esc_html_e('Subject', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                <input type="text" id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="message" class="block text-gray-700 font-medium mb-2"><?php esc_html_e('Your Message', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                <textarea id="message" name="message" rows="6" class="w-full px-4 py-3 border border-gray-300 rounded-md focus:border-primary focus:ring focus:ring-primary focus:ring-opacity-50" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="button button-primary"><?php esc_html_e('Send Message', 'aqualuxe'); ?></button>
                            </div>
                        </form>
                        <?php
                    }
                    ?>
                </div>
            </div>
            
            <div class="contact-info-container w-full lg:w-1/3">
                <div class="contact-info bg-primary text-white rounded-lg shadow-md p-8">
                    <?php if ($info_title) : ?>
                        <h3 class="text-2xl font-bold mb-6"><?php echo esc_html($info_title); ?></h3>
                    <?php endif; ?>
                    
                    <div class="contact-details space-y-6">
                        <?php if ($address) : ?>
                            <div class="contact-item flex">
                                <div class="contact-icon mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h4 class="font-medium mb-1"><?php esc_html_e('Address', 'aqualuxe'); ?></h4>
                                    <address class="not-italic">
                                        <?php echo wp_kses_post(nl2br($address)); ?>
                                    </address>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($phone) : ?>
                            <div class="contact-item flex">
                                <div class="contact-icon mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h4 class="font-medium mb-1"><?php esc_html_e('Phone', 'aqualuxe'); ?></h4>
                                    <p><a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" class="hover:text-white hover:underline"><?php echo esc_html($phone); ?></a></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($email) : ?>
                            <div class="contact-item flex">
                                <div class="contact-icon mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h4 class="font-medium mb-1"><?php esc_html_e('Email', 'aqualuxe'); ?></h4>
                                    <p><a href="mailto:<?php echo esc_attr($email); ?>" class="hover:text-white hover:underline"><?php echo esc_html($email); ?></a></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($hours) : ?>
                            <div class="contact-item flex">
                                <div class="contact-icon mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="contact-text">
                                    <h4 class="font-medium mb-1"><?php esc_html_e('Business Hours', 'aqualuxe'); ?></h4>
                                    <div class="hours">
                                        <?php echo wp_kses_post(nl2br($hours)); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (has_nav_menu('social')) : ?>
                        <div class="contact-social mt-8">
                            <h4 class="font-medium mb-3"><?php esc_html_e('Follow Us', 'aqualuxe'); ?></h4>
                            <?php
                            wp_nav_menu(
                                array(
                                    'theme_location' => 'social',
                                    'menu_class'     => 'flex space-x-4',
                                    'container'      => false,
                                    'depth'          => 1,
                                    'link_before'    => '<span class="screen-reader-text">',
                                    'link_after'     => '</span>',
                                )
                            );
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>