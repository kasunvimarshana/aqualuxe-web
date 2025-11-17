<?php
/**
 * Template Name: Contact Page
 *
 * This is the template that displays the contact page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="page-hero bg-gradient-to-r from-blue-900 to-teal-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php the_title(); ?>
                </h1>
                <?php if (get_field('page_subtitle')) : ?>
                    <p class="text-xl text-blue-100">
                        <?php echo esc_html(get_field('page_subtitle')); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Form -->
                <div class="contact-form bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 transition-colors duration-300">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                        <?php echo esc_html(get_field('form_heading', __('Send Us a Message', 'aqualuxe'))); ?>
                    </h2>
                    
                    <?php if (get_field('form_shortcode')) : ?>
                        <?php echo do_shortcode(get_field('form_shortcode')); ?>
                    <?php else : ?>
                        <!-- Default Contact Form (Replace with your contact form plugin shortcode) -->
                        <form class="contact-form-default space-y-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" required class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                            </div>
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <?php esc_html_e('Email Address', 'aqualuxe'); ?> <span class="text-red-500">*</span>
                                </label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                            </div>
                            <div>
                                <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <?php esc_html_e('Subject', 'aqualuxe'); ?>
                                </label>
                                <input type="text" id="subject" name="subject" class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark">
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    <?php esc_html_e('Message', 'aqualuxe'); ?> <span class="text-red-500">*</span>
                                </label>
                                <textarea id="message" name="message" rows="5" required class="w-full px-4 py-2 rounded-md border border-gray-300 dark:border-gray-700 dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-primary dark:focus:ring-primary-dark"></textarea>
                            </div>
                            <div>
                                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark transition-colors duration-300">
                                    <?php esc_html_e('Send Message', 'aqualuxe'); ?>
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Contact Information -->
                <div class="contact-info">
                    <h2 class="text-2xl font-bold mb-6 text-gray-900 dark:text-white">
                        <?php echo esc_html(get_field('info_heading', __('Contact Information', 'aqualuxe'))); ?>
                    </h2>
                    
                    <div class="prose dark:prose-invert max-w-none mb-8">
                        <?php 
                        if (get_field('contact_info_text')) {
                            echo wp_kses_post(get_field('contact_info_text'));
                        } else {
                            the_content();
                        }
                        ?>
                    </div>
                    
                    <div class="space-y-6">
                        <?php if (get_field('address')) : ?>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 text-gray-700 dark:text-gray-300">
                                    <?php echo wp_kses_post(get_field('address')); ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('123 Aquarium Way', 'aqualuxe'); ?><br>
                                    <?php esc_html_e('Coral City, FL 33157', 'aqualuxe'); ?><br>
                                    <?php esc_html_e('United States', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (get_field('phone')) : ?>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="ml-3 text-gray-700 dark:text-gray-300">
                                    <p><?php echo esc_html(get_field('phone')); ?></p>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                </div>
                                <div class="ml-3 text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('+1 (555) 123-4567', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (get_field('email')) : ?>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-3 text-gray-700 dark:text-gray-300">
                                    <p><a href="mailto:<?php echo esc_attr(get_field('email')); ?>" class="hover:text-primary dark:hover:text-primary-dark transition-colors duration-300"><?php echo esc_html(get_field('email')); ?></a></p>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                </div>
                                <div class="ml-3 text-gray-700 dark:text-gray-300">
                                    <p><a href="mailto:info@aqualuxe.com" class="hover:text-primary dark:hover:text-primary-dark transition-colors duration-300"><?php esc_html_e('info@aqualuxe.com', 'aqualuxe'); ?></a></p>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (get_field('hours')) : ?>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 text-gray-700 dark:text-gray-300">
                                    <?php echo wp_kses_post(get_field('hours')); ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary dark:text-primary-dark" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-3 text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('Monday - Friday: 9:00 AM - 6:00 PM', 'aqualuxe'); ?><br>
                                    <?php esc_html_e('Saturday: 10:00 AM - 4:00 PM', 'aqualuxe'); ?><br>
                                    <?php esc_html_e('Sunday: Closed', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="mt-8">
                        <h3 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">
                            <?php esc_html_e('Connect With Us', 'aqualuxe'); ?>
                        </h3>
                        <div class="flex space-x-4">
                            <?php if (get_field('facebook_url')) : ?>
                                <a href="<?php echo esc_url(get_field('facebook_url')); ?>" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                                    <span class="sr-only"><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php else : ?>
                                <a href="#" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300">
                                    <span class="sr-only"><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (get_field('instagram_url')) : ?>
                                <a href="<?php echo esc_url(get_field('instagram_url')); ?>" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                                    <span class="sr-only"><?php esc_html_e('Instagram', 'aqualuxe'); ?></span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php else : ?>
                                <a href="#" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300">
                                    <span class="sr-only"><?php esc_html_e('Instagram', 'aqualuxe'); ?></span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (get_field('twitter_url')) : ?>
                                <a href="<?php echo esc_url(get_field('twitter_url')); ?>" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                                    <span class="sr-only"><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                    </svg>
                                </a>
                            <?php else : ?>
                                <a href="#" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300">
                                    <span class="sr-only"><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php if (get_field('youtube_url')) : ?>
                                <a href="<?php echo esc_url(get_field('youtube_url')); ?>" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                                    <span class="sr-only"><?php esc_html_e('YouTube', 'aqualuxe'); ?></span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php else : ?>
                                <a href="#" class="text-gray-600 hover:text-primary dark:text-gray-400 dark:hover:text-primary-dark transition-colors duration-300">
                                    <span class="sr-only"><?php esc_html_e('YouTube', 'aqualuxe'); ?></span>
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.746 22 12 22 12s0 3.255-.418 4.814a2.504 2.504 0 0 1-1.768 1.768c-1.56.419-7.814.419-7.814.419s-6.255 0-7.814-.419a2.505 2.505 0 0 1-1.768-1.768C2 15.255 2 12 2 12s0-3.255.417-4.814a2.507 2.507 0 0 1 1.768-1.768C5.744 5 11.998 5 11.998 5s6.255 0 7.814.418ZM15.194 12 10 15V9l5.194 3Z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
        <?php if (get_field('map_embed_code')) : ?>
            <div class="map-container h-96">
                <?php echo get_field('map_embed_code'); ?>
            </div>
        <?php else : ?>
            <div class="map-container h-96 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                <div class="text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-400 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400"><?php esc_html_e('Map location will be displayed here. Add a map embed code in the page settings.', 'aqualuxe'); ?></p>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <!-- FAQ Section (Optional) -->
    <?php if (have_rows('faqs') || get_field('show_default_faqs')) : ?>
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_field('faq_heading', __('Frequently Asked Questions', 'aqualuxe'))); ?>
            </h2>
            
            <div class="max-w-3xl mx-auto">
                <div class="space-y-6">
                    <?php if (have_rows('faqs')) : ?>
                        <?php while (have_rows('faqs')) : the_row(); ?>
                            <div class="faq-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">
                                <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center focus:outline-none">
                                    <span class="text-lg font-medium text-gray-900 dark:text-white"><?php echo esc_html(get_sub_field('question')); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div class="faq-answer px-6 pb-4 hidden">
                                    <div class="prose dark:prose-invert max-w-none">
                                        <?php echo wp_kses_post(get_sub_field('answer')); ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php elseif (get_field('show_default_faqs')) : ?>
                        <!-- Default FAQs -->
                        <div class="faq-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">
                            <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center focus:outline-none">
                                <span class="text-lg font-medium text-gray-900 dark:text-white"><?php esc_html_e('How do I place an order?', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="faq-answer px-6 pb-4 hidden">
                                <div class="prose dark:prose-invert max-w-none">
                                    <p><?php esc_html_e('You can place an order through our online shop by selecting the fish or products you want, adding them to your cart, and proceeding to checkout. If you need assistance with a special order, please contact our customer service team.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="faq-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">
                            <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center focus:outline-none">
                                <span class="text-lg font-medium text-gray-900 dark:text-white"><?php esc_html_e('How do you ship live fish?', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="faq-answer px-6 pb-4 hidden">
                                <div class="prose dark:prose-invert max-w-none">
                                    <p><?php esc_html_e('We use specialized packaging with insulated containers, oxygen, and heat/cold packs as needed. Our shipping methods are designed to minimize stress and ensure your fish arrive healthy. We only ship on specific days to avoid weekend delays.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="faq-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">
                            <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center focus:outline-none">
                                <span class="text-lg font-medium text-gray-900 dark:text-white"><?php esc_html_e('What is your return policy?', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="faq-answer px-6 pb-4 hidden">
                                <div class="prose dark:prose-invert max-w-none">
                                    <p><?php esc_html_e('We offer a 100% satisfaction guarantee on all our fish. If your fish arrives dead or dies within 24 hours of arrival, we will replace it or provide a refund. Please take photos and contact us immediately if there are any issues upon arrival.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="faq-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-colors duration-300">
                            <button class="faq-question w-full text-left px-6 py-4 flex justify-between items-center focus:outline-none">
                                <span class="text-lg font-medium text-gray-900 dark:text-white"><?php esc_html_e('Do you offer international shipping?', 'aqualuxe'); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500 dark:text-gray-400 faq-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="faq-answer px-6 pb-4 hidden">
                                <div class="prose dark:prose-invert max-w-none">
                                    <p><?php esc_html_e('Yes, we ship to many countries worldwide. International shipping requires additional documentation and may be subject to customs regulations. Please contact us before placing an international order so we can provide you with specific information for your location.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main><!-- #main -->

<?php
get_footer();
?>

<script>
    // FAQ Toggle Script
    document.addEventListener('DOMContentLoaded', function() {
        const faqQuestions = document.querySelectorAll('.faq-question');
        
        faqQuestions.forEach(question => {
            question.addEventListener('click', function() {
                const answer = this.nextElementSibling;
                const icon = this.querySelector('.faq-icon');
                
                // Toggle answer visibility
                if (answer.classList.contains('hidden')) {
                    answer.classList.remove('hidden');
                    icon.style.transform = 'rotate(180deg)';
                } else {
                    answer.classList.add('hidden');
                    icon.style.transform = 'rotate(0)';
                }
            });
        });
    });
</script>