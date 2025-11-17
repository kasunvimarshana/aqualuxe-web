<?php
/**
 * Template part for displaying a fallback checkout page when WooCommerce is not active
 *
 * @package AquaLuxe
 */
?>

<div class="checkout-fallback container mx-auto px-4 py-12 text-center">
    <h1 class="page-title text-4xl md:text-5xl mb-6"><?php esc_html_e('Checkout', 'aqualuxe'); ?></h1>
    
    <div class="checkout-fallback-message mb-8">
        <p><?php esc_html_e('The checkout is currently unavailable. WooCommerce plugin is required to use the checkout functionality.', 'aqualuxe'); ?></p>
    </div>
    
    <?php if (current_user_can('install_plugins')) : ?>
        <a href="<?php echo esc_url(admin_url('plugin-install.php?s=woocommerce&tab=search&type=term')); ?>" class="shop-fallback-button">
            <?php esc_html_e('Install WooCommerce', 'aqualuxe'); ?>
        </a>
    <?php else : ?>
        <a href="<?php echo esc_url(home_url()); ?>" class="shop-fallback-button">
            <?php esc_html_e('Return to Home', 'aqualuxe'); ?>
        </a>
    <?php endif; ?>
    
    <div class="checkout-illustration mt-12 mb-12 flex justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-48 w-48 text-gray-300 dark:text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
    </div>
    
    <div class="contact-section mt-16">
        <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Contact Us', 'aqualuxe'); ?></h2>
        
        <div class="max-w-2xl mx-auto">
            <p class="mb-8"><?php esc_html_e('If you have any questions or inquiries, please feel free to contact us using the form below:', 'aqualuxe'); ?></p>
            
            <div class="contact-form bg-white dark:bg-gray-800 p-8 rounded-lg shadow-md">
                <?php
                // Check if Contact Form 7 is active
                if (function_exists('wpcf7_contact_form')) {
                    // Try to get a contact form by ID or shortcode
                    $contact_form = wpcf7_contact_form(get_theme_mod('aqualuxe_contact_form_id', 1));
                    
                    if ($contact_form) {
                        echo do_shortcode('[contact-form-7 id="' . $contact_form->id() . '"]');
                    } else {
                        // Fallback simple contact form
                        ?>
                        <form action="#" method="post" class="contact-fallback-form">
                            <div class="mb-4">
                                <label for="name" class="block text-left text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                <input type="text" id="name" name="name" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded">
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="block text-left text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e('Your Email', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                <input type="email" id="email" name="email" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded">
                            </div>
                            
                            <div class="mb-4">
                                <label for="subject" class="block text-left text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e('Subject', 'aqualuxe'); ?></label>
                                <input type="text" id="subject" name="subject" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded">
                            </div>
                            
                            <div class="mb-6">
                                <label for="message" class="block text-left text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e('Your Message', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                                <textarea id="message" name="message" rows="5" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded"></textarea>
                            </div>
                            
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded transition-colors">
                                <?php esc_html_e('Send Message', 'aqualuxe'); ?>
                            </button>
                        </form>
                        <?php
                    }
                } else {
                    // Fallback simple contact form
                    ?>
                    <form action="#" method="post" class="contact-fallback-form">
                        <div class="mb-4">
                            <label for="name" class="block text-left text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e('Your Name', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                            <input type="text" id="name" name="name" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded">
                        </div>
                        
                        <div class="mb-4">
                            <label for="email" class="block text-left text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e('Your Email', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                            <input type="email" id="email" name="email" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded">
                        </div>
                        
                        <div class="mb-4">
                            <label for="subject" class="block text-left text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e('Subject', 'aqualuxe'); ?></label>
                            <input type="text" id="subject" name="subject" class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded">
                        </div>
                        
                        <div class="mb-6">
                            <label for="message" class="block text-left text-gray-700 dark:text-gray-300 mb-2"><?php esc_html_e('Your Message', 'aqualuxe'); ?> <span class="text-red-500">*</span></label>
                            <textarea id="message" name="message" rows="5" required class="w-full p-2 border border-gray-300 dark:border-gray-600 rounded"></textarea>
                        </div>
                        
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded transition-colors">
                            <?php esc_html_e('Send Message', 'aqualuxe'); ?>
                        </button>
                    </form>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>
</div>