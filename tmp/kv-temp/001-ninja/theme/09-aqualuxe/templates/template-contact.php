<?php
/**
 * Template Name: Contact
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <div class="container py-12">
        <header class="page-header text-center mb-12">
            <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <div class="page-description text-lg text-gray-600 max-w-3xl mx-auto">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="page-content">
            <div class="flex flex-col lg:flex-row gap-12">
                <!-- Contact Form Section -->
                <div class="contact-form-section lg:w-2/3">
                    <?php
                    // Display the page content first
                    while (have_posts()) :
                        the_post();
                        the_content();
                    endwhile;
                    
                    // Check if Contact Form 7 is active and a form shortcode is set
                    $contact_form_shortcode = get_theme_mod('aqualuxe_contact_form_shortcode', '');
                    
                    if (!empty($contact_form_shortcode) && function_exists('wpcf7_contact_form_tag_func')) {
                        echo do_shortcode($contact_form_shortcode);
                    } else {
                        // Default contact form
                        ?>
                        <div class="default-contact-form bg-white p-8 rounded-lg shadow-md">
                            <h3 class="text-2xl font-bold mb-6"><?php esc_html_e('Send Us a Message', 'aqualuxe'); ?></h3>
                            
                            <form id="contact-form" class="space-y-6" action="#" method="post">
                                <div class="form-group">
                                    <label for="name" class="block text-gray-700 mb-2"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="email" class="block text-gray-700 mb-2"><?php esc_html_e('Your Email', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="subject" class="block text-gray-700 mb-2"><?php esc_html_e('Subject', 'aqualuxe'); ?></label>
                                    <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary">
                                </div>
                                
                                <div class="form-group">
                                    <label for="message" class="block text-gray-700 mb-2"><?php esc_html_e('Your Message', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                    <textarea id="message" name="message" rows="6" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary"><?php esc_html_e('Send Message', 'aqualuxe'); ?></button>
                                </div>
                                
                                <p class="text-sm text-gray-500"><?php esc_html_e('Fields marked with * are required', 'aqualuxe'); ?></p>
                            </form>
                            
                            <div class="form-note mt-6 text-sm text-gray-600">
                                <p><?php esc_html_e('Note: This is a demo form. In a live site, you would integrate Contact Form 7 or another form plugin.', 'aqualuxe'); ?></p>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                
                <!-- Contact Information Section -->
                <div class="contact-info-section lg:w-1/3">
                    <div class="bg-gray-50 p-8 rounded-lg shadow-md">
                        <h3 class="text-2xl font-bold mb-6"><?php esc_html_e('Contact Information', 'aqualuxe'); ?></h3>
                        
                        <div class="contact-details space-y-6">
                            <?php
                            // Address
                            $address = get_theme_mod('aqualuxe_contact_address', '123 Aquarium Street, Colombo, Sri Lanka');
                            if ($address) :
                            ?>
                                <div class="contact-address flex items-start">
                                    <div class="icon text-primary mr-4 mt-1">
                                        <i class="fa fa-map-marker-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-medium mb-1"><?php esc_html_e('Address', 'aqualuxe'); ?></h4>
                                        <p class="text-gray-600"><?php echo nl2br(esc_html($address)); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Phone
                            $phone = get_theme_mod('aqualuxe_contact_phone', '+94 123 456 7890');
                            if ($phone) :
                            ?>
                                <div class="contact-phone flex items-start">
                                    <div class="icon text-primary mr-4 mt-1">
                                        <i class="fa fa-phone-alt text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-medium mb-1"><?php esc_html_e('Phone', 'aqualuxe'); ?></h4>
                                        <p class="text-gray-600"><?php echo esc_html($phone); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Email
                            $email = get_theme_mod('aqualuxe_contact_email', 'info@aqualuxe.com');
                            if ($email) :
                            ?>
                                <div class="contact-email flex items-start">
                                    <div class="icon text-primary mr-4 mt-1">
                                        <i class="fa fa-envelope text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-medium mb-1"><?php esc_html_e('Email', 'aqualuxe'); ?></h4>
                                        <p class="text-gray-600">
                                            <a href="mailto:<?php echo esc_attr($email); ?>" class="hover:text-primary"><?php echo esc_html($email); ?></a>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Hours
                            $hours = get_theme_mod('aqualuxe_contact_hours', "Monday - Friday: 9:00 AM - 5:00 PM\nSaturday: 10:00 AM - 2:00 PM\nSunday: Closed");
                            if ($hours) :
                            ?>
                                <div class="contact-hours flex items-start">
                                    <div class="icon text-primary mr-4 mt-1">
                                        <i class="fa fa-clock text-xl"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-medium mb-1"><?php esc_html_e('Business Hours', 'aqualuxe'); ?></h4>
                                        <p class="text-gray-600"><?php echo nl2br(esc_html($hours)); ?></p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php
                        // Social Media
                        $facebook = get_theme_mod('aqualuxe_facebook', '');
                        $instagram = get_theme_mod('aqualuxe_instagram', '');
                        $twitter = get_theme_mod('aqualuxe_twitter', '');
                        $youtube = get_theme_mod('aqualuxe_youtube', '');
                        $pinterest = get_theme_mod('aqualuxe_pinterest', '');
                        
                        if ($facebook || $instagram || $twitter || $youtube || $pinterest) :
                        ?>
                            <div class="social-links mt-8">
                                <h4 class="text-lg font-medium mb-3"><?php esc_html_e('Follow Us', 'aqualuxe'); ?></h4>
                                <div class="flex gap-4">
                                    <?php if ($facebook) : ?>
                                        <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" class="social-link w-10 h-10 rounded-full bg-gray-200 hover:bg-primary hover:text-white flex items-center justify-center transition duration-300">
                                            <i class="fab fa-facebook-f"></i>
                                            <span class="sr-only"><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($instagram) : ?>
                                        <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" class="social-link w-10 h-10 rounded-full bg-gray-200 hover:bg-primary hover:text-white flex items-center justify-center transition duration-300">
                                            <i class="fab fa-instagram"></i>
                                            <span class="sr-only"><?php esc_html_e('Instagram', 'aqualuxe'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($twitter) : ?>
                                        <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="social-link w-10 h-10 rounded-full bg-gray-200 hover:bg-primary hover:text-white flex items-center justify-center transition duration-300">
                                            <i class="fab fa-twitter"></i>
                                            <span class="sr-only"><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($youtube) : ?>
                                        <a href="<?php echo esc_url($youtube); ?>" target="_blank" rel="noopener noreferrer" class="social-link w-10 h-10 rounded-full bg-gray-200 hover:bg-primary hover:text-white flex items-center justify-center transition duration-300">
                                            <i class="fab fa-youtube"></i>
                                            <span class="sr-only"><?php esc_html_e('YouTube', 'aqualuxe'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($pinterest) : ?>
                                        <a href="<?php echo esc_url($pinterest); ?>" target="_blank" rel="noopener noreferrer" class="social-link w-10 h-10 rounded-full bg-gray-200 hover:bg-primary hover:text-white flex items-center justify-center transition duration-300">
                                            <i class="fab fa-pinterest-p"></i>
                                            <span class="sr-only"><?php esc_html_e('Pinterest', 'aqualuxe'); ?></span>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            
            <!-- Google Map Section -->
            <?php
            $show_map = get_theme_mod('aqualuxe_contact_show_map', true);
            $map_embed = get_theme_mod('aqualuxe_contact_map_embed', '');
            
            if ($show_map) :
            ?>
                <div class="google-map-section mt-12">
                    <div class="map-container rounded-lg overflow-hidden shadow-md h-96">
                        <?php if ($map_embed) : ?>
                            <?php echo $map_embed; ?>
                        <?php else : ?>
                            <div class="map-placeholder bg-gray-200 h-full flex items-center justify-center">
                                <p class="text-gray-600"><?php esc_html_e('Google Map Embed will appear here. Add your Google Maps embed code in Theme Customizer.', 'aqualuxe'); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- FAQ Section -->
            <?php
            $show_faq = get_theme_mod('aqualuxe_contact_show_faq', true);
            $faq_title = get_theme_mod('aqualuxe_contact_faq_title', __('Frequently Asked Questions', 'aqualuxe'));
            
            // FAQs (in a real theme, this would be a custom post type or repeater field)
            $faqs = array();
            
            for ($i = 1; $i <= 5; $i++) {
                $question = get_theme_mod('aqualuxe_faq_question_' . $i, '');
                $answer = get_theme_mod('aqualuxe_faq_answer_' . $i, '');
                
                if (!empty($question) && !empty($answer)) {
                    $faqs[] = array(
                        'question' => $question,
                        'answer' => $answer,
                    );
                }
            }
            
            // If no custom FAQs, use defaults
            if (empty($faqs)) {
                $faqs = array(
                    array(
                        'question' => __('Do you ship internationally?', 'aqualuxe'),
                        'answer' => __('Yes, we ship to most countries worldwide. Shipping costs and delivery times vary depending on your location. Please contact us for specific shipping information for your country.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('What is your return policy?', 'aqualuxe'),
                        'answer' => __('Due to the nature of live aquatic products, we have a specific return policy. Please contact us within 24 hours of receiving your order if there are any issues. Non-living products can be returned within 14 days in their original packaging.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Do you offer wholesale pricing?', 'aqualuxe'),
                        'answer' => __('Yes, we offer wholesale pricing for pet stores, aquarium shops, and other businesses. Please contact our wholesale department for more information and to set up an account.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('How do you ensure the health of your fish during shipping?', 'aqualuxe'),
                        'answer' => __('We use specialized packaging techniques including insulated boxes, heat packs or cooling packs (depending on the season), and oxygen-filled bags. All fish are fasted before shipping and water is treated with stress-reducing additives.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Do you offer aquarium maintenance services?', 'aqualuxe'),
                        'answer' => __('Yes, we offer professional aquarium maintenance services for both residential and commercial clients. Our services include regular cleaning, water testing, equipment maintenance, and livestock health checks.', 'aqualuxe'),
                    ),
                );
            }
            
            if ($show_faq && !empty($faqs)) :
            ?>
                <div class="faq-section mt-16">
                    <?php if ($faq_title) : ?>
                        <h2 class="section-title text-3xl font-bold mb-8 text-center"><?php echo esc_html($faq_title); ?></h2>
                    <?php endif; ?>
                    
                    <div class="faq-items space-y-4" x-data="{ openItem: null }">
                        <?php foreach ($faqs as $index => $faq) : ?>
                            <div class="faq-item bg-white rounded-lg shadow-md overflow-hidden">
                                <button 
                                    class="faq-question w-full text-left px-6 py-4 flex justify-between items-center focus:outline-none"
                                    @click="openItem = openItem === <?php echo $index; ?> ? null : <?php echo $index; ?>"
                                >
                                    <span class="text-lg font-medium"><?php echo esc_html($faq['question']); ?></span>
                                    <span class="transform transition-transform" :class="{ 'rotate-180': openItem === <?php echo $index; ?> }">
                                        <i class="fa fa-chevron-down"></i>
                                    </span>
                                </button>
                                
                                <div 
                                    class="faq-answer px-6 pb-4" 
                                    x-show="openItem === <?php echo $index; ?>"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                >
                                    <p class="text-gray-600"><?php echo esc_html($faq['answer']); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

</main><!-- #main -->

<?php
get_footer();