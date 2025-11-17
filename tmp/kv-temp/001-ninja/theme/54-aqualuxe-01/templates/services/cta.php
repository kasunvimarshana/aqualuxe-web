<?php
/**
 * Services CTA Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer options
$title = get_theme_mod('aqualuxe_services_cta_title', __('Ready to Transform Your Aquatic Experience?', 'aqualuxe'));
$subtitle = get_theme_mod('aqualuxe_services_cta_subtitle', __('Get Started Today', 'aqualuxe'));
$description = get_theme_mod('aqualuxe_services_cta_description', __('Contact our team of experts to discuss your project or schedule a consultation. We\'re here to help you create the perfect aquatic environment.', 'aqualuxe'));
$image = get_theme_mod('aqualuxe_services_cta_image', get_template_directory_uri() . '/assets/images/services/cta-background.jpg');
$overlay = get_theme_mod('aqualuxe_services_cta_overlay', true);
$overlay_opacity = get_theme_mod('aqualuxe_services_cta_overlay_opacity', 0.8);
$overlay_color = get_theme_mod('aqualuxe_services_cta_overlay_color', 'blue-900');
$text_color = get_theme_mod('aqualuxe_services_cta_text_color', 'text-white');
$primary_button_text = get_theme_mod('aqualuxe_services_cta_primary_button_text', __('Request a Quote', 'aqualuxe'));
$primary_button_url = get_theme_mod('aqualuxe_services_cta_primary_button_url', '#quote');
$secondary_button_text = get_theme_mod('aqualuxe_services_cta_secondary_button_text', __('Contact Us', 'aqualuxe'));
$secondary_button_url = get_theme_mod('aqualuxe_services_cta_secondary_button_url', '#contact');
$show_contact_info = get_theme_mod('aqualuxe_services_cta_show_contact_info', true);
$phone = get_theme_mod('aqualuxe_services_cta_phone', '(555) 123-4567');
$email = get_theme_mod('aqualuxe_services_cta_email', 'info@aqualuxe.com');
$style = get_theme_mod('aqualuxe_services_cta_style', 'centered'); // centered, split, or boxed
?>

<section class="services-cta relative py-16 md:py-24 bg-cover bg-center" <?php if ($image) : ?>style="background-image: url('<?php echo esc_url($image); ?>');"<?php endif; ?>>
    <?php if ($overlay) : ?>
        <div class="absolute inset-0 bg-<?php echo esc_attr($overlay_color); ?> opacity-<?php echo esc_attr($overlay_opacity * 100); ?>"></div>
    <?php endif; ?>
    
    <div class="container relative z-10 mx-auto px-4">
        <?php if ($style === 'centered') : ?>
            <div class="max-w-3xl mx-auto text-center">
                <?php if ($subtitle) : ?>
                    <span class="inline-block px-3 py-1 mb-4 text-sm font-semibold tracking-wider uppercase rounded-full <?php echo esc_attr($text_color); ?> bg-white bg-opacity-20">
                        <?php echo esc_html($subtitle); ?>
                    </span>
                <?php endif; ?>
                
                <?php if ($title) : ?>
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6 <?php echo esc_attr($text_color); ?>">
                        <?php echo esc_html($title); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ($description) : ?>
                    <p class="text-lg md:text-xl mb-8 <?php echo esc_attr($text_color); ?> opacity-90">
                        <?php echo esc_html($description); ?>
                    </p>
                <?php endif; ?>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <?php if ($primary_button_text && $primary_button_url) : ?>
                        <a href="<?php echo esc_url($primary_button_url); ?>" class="btn btn-primary text-lg px-8 py-3">
                            <?php echo esc_html($primary_button_text); ?>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($secondary_button_text && $secondary_button_url) : ?>
                        <a href="<?php echo esc_url($secondary_button_url); ?>" class="btn btn-outline-light text-lg px-8 py-3">
                            <?php echo esc_html($secondary_button_text); ?>
                        </a>
                    <?php endif; ?>
                </div>
                
                <?php if ($show_contact_info && ($phone || $email)) : ?>
                    <div class="mt-8 flex flex-wrap justify-center gap-6">
                        <?php if ($phone) : ?>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 <?php echo esc_attr($text_color); ?> mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <span class="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($phone); ?></span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($email) : ?>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 <?php echo esc_attr($text_color); ?> mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="<?php echo esc_attr($text_color); ?>"><?php echo esc_html($email); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php elseif ($style === 'split') : ?>
            <div class="flex flex-wrap items-center -mx-4">
                <div class="w-full lg:w-1/2 px-4 mb-10 lg:mb-0">
                    <?php if ($subtitle) : ?>
                        <span class="inline-block px-3 py-1 mb-4 text-sm font-semibold tracking-wider uppercase rounded-full <?php echo esc_attr($text_color); ?> bg-white bg-opacity-20">
                            <?php echo esc_html($subtitle); ?>
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($title) : ?>
                        <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6 <?php echo esc_attr($text_color); ?>">
                            <?php echo esc_html($title); ?>
                        </h2>
                    <?php endif; ?>
                    
                    <?php if ($description) : ?>
                        <p class="text-lg mb-8 <?php echo esc_attr($text_color); ?> opacity-90">
                            <?php echo esc_html($description); ?>
                        </p>
                    <?php endif; ?>
                    
                    <?php if ($show_contact_info && ($phone || $email)) : ?>
                        <div class="mb-8 space-y-3">
                            <?php if ($phone) : ?>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 <?php echo esc_attr($text_color); ?> mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span class="<?php echo esc_attr($text_color); ?> text-lg"><?php echo esc_html($phone); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($email) : ?>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 <?php echo esc_attr($text_color); ?> mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="<?php echo esc_attr($text_color); ?> text-lg"><?php echo esc_html($email); ?></span>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="w-full lg:w-1/2 px-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl p-8">
                        <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                            <?php esc_html_e('Request Information', 'aqualuxe'); ?>
                        </h3>
                        
                        <form class="space-y-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <?php esc_html_e('Name', 'aqualuxe'); ?> *
                                    </label>
                                    <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                </div>
                                
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        <?php esc_html_e('Email', 'aqualuxe'); ?> *
                                    </label>
                                    <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                </div>
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <?php esc_html_e('Phone', 'aqualuxe'); ?>
                                </label>
                                <input type="tel" id="phone" name="phone" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="service" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <?php esc_html_e('Service Interested In', 'aqualuxe'); ?> *
                                </label>
                                <select id="service" name="service" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                                    <option value=""><?php esc_html_e('Select a service', 'aqualuxe'); ?></option>
                                    <option value="installation"><?php esc_html_e('Pool Installation', 'aqualuxe'); ?></option>
                                    <option value="maintenance"><?php esc_html_e('Maintenance & Cleaning', 'aqualuxe'); ?></option>
                                    <option value="repair"><?php esc_html_e('Repair Services', 'aqualuxe'); ?></option>
                                    <option value="water-treatment"><?php esc_html_e('Water Treatment', 'aqualuxe'); ?></option>
                                    <option value="renovation"><?php esc_html_e('Renovation & Upgrades', 'aqualuxe'); ?></option>
                                    <option value="consultation"><?php esc_html_e('Consultation & Design', 'aqualuxe'); ?></option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <?php esc_html_e('Message', 'aqualuxe'); ?>
                                </label>
                                <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white"></textarea>
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    <?php esc_html_e('Submit Request', 'aqualuxe'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php elseif ($style === 'boxed') : ?>
            <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/2 p-8 lg:p-12 bg-blue-600">
                        <?php if ($subtitle) : ?>
                            <span class="inline-block px-3 py-1 mb-4 text-sm font-semibold tracking-wider uppercase rounded-full text-white bg-white bg-opacity-20">
                                <?php echo esc_html($subtitle); ?>
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($title) : ?>
                            <h2 class="text-3xl md:text-4xl font-bold mb-6 text-white">
                                <?php echo esc_html($title); ?>
                            </h2>
                        <?php endif; ?>
                        
                        <?php if ($description) : ?>
                            <p class="text-lg mb-8 text-white text-opacity-90">
                                <?php echo esc_html($description); ?>
                            </p>
                        <?php endif; ?>
                        
                        <?php if ($show_contact_info && ($phone || $email)) : ?>
                            <div class="space-y-4 mb-8">
                                <?php if ($phone) : ?>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-white text-lg"><?php echo esc_html($phone); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($email) : ?>
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-4">
                                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-white text-lg"><?php echo esc_html($email); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="w-full lg:w-1/2 p-8 lg:p-12">
                        <h3 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                            <?php esc_html_e('Get in Touch', 'aqualuxe'); ?>
                        </h3>
                        
                        <form class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <?php esc_html_e('Name', 'aqualuxe'); ?> *
                                </label>
                                <input type="text" id="name" name="name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            </div>
                            
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <?php esc_html_e('Email', 'aqualuxe'); ?> *
                                </label>
                                <input type="email" id="email" name="email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required>
                            </div>
                            
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <?php esc_html_e('Message', 'aqualuxe'); ?> *
                                </label>
                                <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-white" required></textarea>
                            </div>
                            
                            <div>
                                <button type="submit" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                    <?php esc_html_e('Send Message', 'aqualuxe'); ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>