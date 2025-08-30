<?php
/**
 * Template Name: Contact Page
 *
 * This is the template that displays the contact page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    $hero_image = get_post_meta(get_the_ID(), 'contact_hero_image', true);
    if (!$hero_image) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    $hero_title = get_post_meta(get_the_ID(), 'contact_hero_title', true);
    if (!$hero_title) {
        $hero_title = get_the_title();
    }
    $hero_subtitle = get_post_meta(get_the_ID(), 'contact_hero_subtitle', true);
    ?>
    <section class="contact-hero relative py-16 bg-cover bg-center" <?php if ($hero_image) : ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_subtitle) : ?>
                    <p class="text-xl md:text-2xl"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="contact-main py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12">
                <div class="contact-info">
                    <?php
                    $info_title = get_post_meta(get_the_ID(), 'contact_info_title', true);
                    $info_content = get_post_meta(get_the_ID(), 'contact_info_content', true);
                    $address = get_theme_mod('aqualuxe_contact_address', '');
                    $phone = get_theme_mod('aqualuxe_contact_phone', '');
                    $email = get_theme_mod('aqualuxe_contact_email', '');
                    $hours = get_theme_mod('aqualuxe_contact_hours', '');
                    ?>
                    
                    <?php if ($info_title) : ?>
                        <h2 class="text-3xl font-bold mb-6"><?php echo esc_html($info_title); ?></h2>
                    <?php else : ?>
                        <h2 class="text-3xl font-bold mb-6"><?php esc_html_e('Contact Information', 'aqualuxe'); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ($info_content) : ?>
                        <div class="prose dark:prose-invert max-w-none mb-8">
                            <?php echo wp_kses_post(wpautop($info_content)); ?>
                        </div>
                    <?php elseif (get_the_content()) : ?>
                        <div class="prose dark:prose-invert max-w-none mb-8">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="contact-details space-y-6">
                        <?php if ($address) : ?>
                        <div class="contact-address flex">
                            <div class="contact-icon mr-4 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold mb-1"><?php esc_html_e('Address', 'aqualuxe'); ?></h3>
                                <p class="text-gray-600 dark:text-gray-300"><?php echo nl2br(esc_html($address)); ?></p>
                                <?php
                                // If Google Maps is enabled
                                if (get_theme_mod('aqualuxe_enable_google_maps', true)) :
                                    $map_url = 'https://www.google.com/maps?q=' . urlencode($address);
                                ?>
                                <a href="<?php echo esc_url($map_url); ?>" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline mt-2">
                                    <?php esc_html_e('View on Google Maps', 'aqualuxe'); ?>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                    </svg>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($phone) : ?>
                        <div class="contact-phone flex">
                            <div class="contact-icon mr-4 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold mb-1"><?php esc_html_e('Phone', 'aqualuxe'); ?></h3>
                                <p class="text-gray-600 dark:text-gray-300">
                                    <a href="tel:<?php echo esc_attr(preg_replace('/[^0-9+]/', '', $phone)); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                        <?php echo esc_html($phone); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($email) : ?>
                        <div class="contact-email flex">
                            <div class="contact-icon mr-4 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold mb-1"><?php esc_html_e('Email', 'aqualuxe'); ?></h3>
                                <p class="text-gray-600 dark:text-gray-300">
                                    <a href="mailto:<?php echo esc_attr($email); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                        <?php echo esc_html($email); ?>
                                    </a>
                                </p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($hours) : ?>
                        <div class="contact-hours flex">
                            <div class="contact-icon mr-4 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold mb-1"><?php esc_html_e('Business Hours', 'aqualuxe'); ?></h3>
                                <p class="text-gray-600 dark:text-gray-300"><?php echo nl2br(esc_html($hours)); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (get_theme_mod('aqualuxe_enable_social_icons', true)) : ?>
                    <div class="contact-social mt-8">
                        <h3 class="text-lg font-bold mb-3"><?php esc_html_e('Connect With Us', 'aqualuxe'); ?></h3>
                        <div class="social-icons flex space-x-4">
                            <?php if (get_theme_mod('aqualuxe_social_facebook')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_social_facebook')); ?>" target="_blank" rel="noopener noreferrer" class="bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-3 rounded-full transition-colors">
                                <span class="sr-only"><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_theme_mod('aqualuxe_social_instagram')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_social_instagram')); ?>" target="_blank" rel="noopener noreferrer" class="bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-3 rounded-full transition-colors">
                                <span class="sr-only"><?php esc_html_e('Instagram', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_theme_mod('aqualuxe_social_twitter')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_social_twitter')); ?>" target="_blank" rel="noopener noreferrer" class="bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-3 rounded-full transition-colors">
                                <span class="sr-only"><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_theme_mod('aqualuxe_social_youtube')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_social_youtube')); ?>" target="_blank" rel="noopener noreferrer" class="bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-3 rounded-full transition-colors">
                                <span class="sr-only"><?php esc_html_e('YouTube', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php if (get_theme_mod('aqualuxe_social_linkedin')) : ?>
                            <a href="<?php echo esc_url(get_theme_mod('aqualuxe_social_linkedin')); ?>" target="_blank" rel="noopener noreferrer" class="bg-gray-200 dark:bg-gray-700 hover:bg-primary-100 dark:hover:bg-primary-900 text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 p-3 rounded-full transition-colors">
                                <span class="sr-only"><?php esc_html_e('LinkedIn', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                                </svg>
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="contact-form bg-white dark:bg-gray-700 p-8 rounded-lg shadow-md">
                    <?php
                    $form_title = get_post_meta(get_the_ID(), 'contact_form_title', true);
                    $form_shortcode = get_post_meta(get_the_ID(), 'contact_form_shortcode', true);
                    ?>
                    
                    <?php if ($form_title) : ?>
                        <h2 class="text-2xl font-bold mb-6"><?php echo esc_html($form_title); ?></h2>
                    <?php else : ?>
                        <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Send Us a Message', 'aqualuxe'); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ($form_shortcode) : ?>
                        <?php echo do_shortcode($form_shortcode); ?>
                    <?php else : ?>
                        <!-- Default Contact Form -->
                        <form action="#" method="post" class="contact-form-default space-y-4">
                            <div class="form-group">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Your Email', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Phone Number', 'aqualuxe'); ?></label>
                                <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white">
                            </div>
                            
                            <div class="form-group">
                                <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Subject', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                <input type="text" id="subject" name="subject" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"><?php esc_html_e('Your Message', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                <textarea id="message" name="message" rows="6" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-white" required></textarea>
                            </div>
                            
                            <div class="form-group">
                                <button type="submit" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-colors">
                                    <?php esc_html_e('Send Message', 'aqualuxe'); ?>
                                </button>
                            </div>
                            
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <?php esc_html_e('Fields marked with * are required', 'aqualuxe'); ?>
                            </p>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    // Map Section
    $show_map = get_post_meta(get_the_ID(), 'contact_show_map', true);
    $map_embed = get_post_meta(get_the_ID(), 'contact_map_embed', true);
    
    if ($show_map && ($map_embed || $address)) :
    ?>
    <section class="contact-map py-8">
        <div class="container mx-auto px-4">
            <div class="map-container h-96 rounded-lg overflow-hidden">
                <?php if ($map_embed) : ?>
                    <?php echo wp_kses($map_embed, array(
                        'iframe' => array(
                            'src' => array(),
                            'width' => array(),
                            'height' => array(),
                            'frameborder' => array(),
                            'style' => array(),
                            'allowfullscreen' => array(),
                            'aria-hidden' => array(),
                            'tabindex' => array(),
                            'loading' => array(),
                            'class' => array(),
                        ),
                    )); ?>
                <?php elseif ($address) : 
                    $map_url = 'https://maps.google.com/maps?q=' . urlencode($address) . '&output=embed';
                ?>
                    <iframe src="<?php echo esc_url($map_url); ?>" width="100%" height="100%" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0" loading="lazy" class="w-full h-full"></iframe>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // FAQ Section
    $show_faq = get_post_meta(get_the_ID(), 'contact_show_faq', true);
    $faq_title = get_post_meta(get_the_ID(), 'contact_faq_title', true);
    $faq_items = get_post_meta(get_the_ID(), 'contact_faq_items', true);
    
    if ($show_faq && ($faq_items || $faq_title)) :
    ?>
    <section class="contact-faq py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <?php if ($faq_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($faq_title); ?></h2>
                <?php else : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Frequently Asked Questions', 'aqualuxe'); ?></h2>
                <?php endif; ?>
            </div>
            
            <?php if ($faq_items && is_array($faq_items)) : ?>
                <div class="max-w-3xl mx-auto" x-data="{ activeTab: 0 }">
                    <?php foreach ($faq_items as $index => $item) : ?>
                        <div class="faq-item mb-4 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button 
                                @click="activeTab = activeTab === <?php echo esc_attr($index); ?> ? null : <?php echo esc_attr($index); ?>"
                                class="w-full flex justify-between items-center p-4 bg-white dark:bg-gray-700 text-left font-medium focus:outline-none"
                            >
                                <span><?php echo esc_html($item['question']); ?></span>
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="h-5 w-5 transform transition-transform" 
                                    :class="{ 'rotate-180': activeTab === <?php echo esc_attr($index); ?> }"
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div 
                                x-show="activeTab === <?php echo esc_attr($index); ?>"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                class="p-4 bg-gray-50 dark:bg-gray-800 prose dark:prose-invert max-w-none"
                            >
                                <?php echo wp_kses_post(wpautop($item['answer'])); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : 
                // Try to get FAQs from custom post type
                $args = array(
                    'post_type' => 'aqualuxe_faq',
                    'posts_per_page' => 6,
                );
                $faq_query = new WP_Query($args);
                
                if ($faq_query->have_posts()) :
            ?>
                <div class="max-w-3xl mx-auto" x-data="{ activeTab: 0 }">
                    <?php 
                    $index = 0;
                    while ($faq_query->have_posts()) : $faq_query->the_post(); 
                    ?>
                        <div class="faq-item mb-4 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <button 
                                @click="activeTab = activeTab === <?php echo esc_attr($index); ?> ? null : <?php echo esc_attr($index); ?>"
                                class="w-full flex justify-between items-center p-4 bg-white dark:bg-gray-700 text-left font-medium focus:outline-none"
                            >
                                <span><?php the_title(); ?></span>
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="h-5 w-5 transform transition-transform" 
                                    :class="{ 'rotate-180': activeTab === <?php echo esc_attr($index); ?> }"
                                    fill="none" 
                                    viewBox="0 0 24 24" 
                                    stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div 
                                x-show="activeTab === <?php echo esc_attr($index); ?>"
                                x-transition:enter="transition ease-out duration-200"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                class="p-4 bg-gray-50 dark:bg-gray-800 prose dark:prose-invert max-w-none"
                            >
                                <?php the_content(); ?>
                            </div>
                        </div>
                    <?php 
                    $index++;
                    endwhile; 
                    wp_reset_postdata();
                    ?>
                </div>
            <?php endif; ?>
            <?php endif; ?>
            
            <div class="text-center mt-8">
                <a href="<?php echo esc_url(home_url('/faq/')); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:underline">
                    <?php esc_html_e('View All FAQs', 'aqualuxe'); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main><!-- #main -->

<?php
get_footer();