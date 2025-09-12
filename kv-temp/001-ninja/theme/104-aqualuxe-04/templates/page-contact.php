<?php
/**
 * Template for displaying Contact page
 * 
 * Template Name: Contact Page
 * 
 * @package AquaLuxe
 */

get_header(); ?>

<main id="primary" class="site-main">
    <?php while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('contact-page'); ?>>
            
            <!-- Hero Section -->
            <section class="contact-hero relative bg-gradient-to-r from-primary-600 to-primary-800 py-20">
                <div class="absolute inset-0 bg-black bg-opacity-20"></div>
                <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                        <?php the_title(); ?>
                    </h1>
                    <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                        Get in touch with our aquatic experts. We're here to help you create the perfect aquatic environment.
                    </p>
                </div>
            </section>

            <!-- Contact Content -->
            <section class="contact-content py-16">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="grid lg:grid-cols-2 gap-16">
                        
                        <!-- Contact Form -->
                        <div class="contact-form-section">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                                Send us a Message
                            </h2>
                            
                            <form class="contact-form space-y-6" method="post" action="">
                                <?php wp_nonce_field('aqualuxe_contact_form', 'contact_nonce'); ?>
                                
                                <div class="form-group">
                                    <label for="contact_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Full Name *
                                    </label>
                                    <input type="text" 
                                           id="contact_name" 
                                           name="contact_name" 
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                
                                <div class="form-group">
                                    <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Email Address *
                                    </label>
                                    <input type="email" 
                                           id="contact_email" 
                                           name="contact_email" 
                                           required
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                
                                <div class="form-group">
                                    <label for="contact_phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel" 
                                           id="contact_phone" 
                                           name="contact_phone"
                                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                
                                <div class="form-group">
                                    <label for="contact_subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Subject *
                                    </label>
                                    <select id="contact_subject" 
                                            name="contact_subject" 
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                        <option value="">Select a subject...</option>
                                        <option value="general">General Inquiry</option>
                                        <option value="products">Product Information</option>
                                        <option value="services">Services & Consultation</option>
                                        <option value="custom">Custom Aquarium Design</option>
                                        <option value="wholesale">Wholesale Inquiry</option>
                                        <option value="franchise">Franchise Opportunity</option>
                                        <option value="support">Customer Support</option>
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="contact_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Message *
                                    </label>
                                    <textarea id="contact_message" 
                                              name="contact_message" 
                                              rows="6" 
                                              required
                                              placeholder="Tell us about your project or inquiry..."
                                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label class="flex items-center">
                                        <input type="checkbox" 
                                               name="contact_newsletter" 
                                               value="1"
                                               class="mr-3 h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300 dark:border-gray-600 rounded">
                                        <span class="text-sm text-gray-700 dark:text-gray-300">
                                            Subscribe to our newsletter for aquatic care tips and product updates
                                        </span>
                                    </label>
                                </div>
                                
                                <button type="submit" 
                                        name="submit_contact" 
                                        class="btn btn-primary w-full justify-center">
                                    <i class="fas fa-paper-plane mr-2"></i>
                                    Send Message
                                </button>
                            </form>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="contact-info-section">
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">
                                Get in Touch
                            </h2>
                            
                            <div class="contact-info space-y-6 mb-8">
                                
                                <?php if ($phone = aqualuxe_get_option('phone')) : ?>
                                <div class="contact-item flex items-start">
                                    <div class="contact-icon w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                        <i class="fas fa-phone text-primary-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Phone</h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            <a href="tel:<?php echo esc_attr(str_replace(' ', '', $phone)); ?>" 
                                               class="hover:text-primary-600 transition-colors">
                                                <?php echo esc_html($phone); ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($email = aqualuxe_get_option('email')) : ?>
                                <div class="contact-item flex items-start">
                                    <div class="contact-icon w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                        <i class="fas fa-envelope text-primary-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Email</h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            <a href="mailto:<?php echo esc_attr($email); ?>" 
                                               class="hover:text-primary-600 transition-colors">
                                                <?php echo esc_html($email); ?>
                                            </a>
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($address = aqualuxe_get_option('address')) : ?>
                                <div class="contact-item flex items-start">
                                    <div class="contact-icon w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                        <i class="fas fa-map-marker-alt text-primary-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Address</h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            <?php echo wp_kses_post($address); ?>
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($hours = aqualuxe_get_option('business_hours')) : ?>
                                <div class="contact-item flex items-start">
                                    <div class="contact-icon w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-lg flex items-center justify-center mr-4 flex-shrink-0">
                                        <i class="fas fa-clock text-primary-600"></i>
                                    </div>
                                    <div>
                                        <h3 class="font-semibold text-gray-900 dark:text-white mb-1">Business Hours</h3>
                                        <p class="text-gray-600 dark:text-gray-400">
                                            <?php echo wp_kses_post($hours); ?>
                                        </p>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                            </div>
                            
                            <!-- Social Media -->
                            <div class="social-media">
                                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Follow Us</h3>
                                <div class="flex space-x-4">
                                    <?php
                                    $social_networks = [
                                        'facebook' => ['fab fa-facebook-f', 'Facebook'],
                                        'instagram' => ['fab fa-instagram', 'Instagram'], 
                                        'twitter' => ['fab fa-twitter', 'Twitter'],
                                        'youtube' => ['fab fa-youtube', 'YouTube'],
                                        'linkedin' => ['fab fa-linkedin', 'LinkedIn'],
                                        'pinterest' => ['fab fa-pinterest', 'Pinterest']
                                    ];
                                    
                                    foreach ($social_networks as $network => $data) :
                                        $url = aqualuxe_get_option($network . '_url');
                                        if ($url) :
                                    ?>
                                        <a href="<?php echo esc_url($url); ?>" 
                                           target="_blank" 
                                           rel="noopener noreferrer"
                                           class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center text-gray-600 dark:text-gray-400 hover:bg-primary-600 hover:text-white transition-colors"
                                           title="<?php echo esc_attr($data[1]); ?>">
                                            <i class="<?php echo esc_attr($data[0]); ?>"></i>
                                        </a>
                                    <?php 
                                        endif;
                                    endforeach; 
                                    ?>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </section>

            <!-- Map Section -->
            <section class="map-section">
                <div class="map-container h-96 bg-gray-200 dark:bg-gray-700 relative">
                    <!-- Google Maps integration would go here -->
                    <div class="flex items-center justify-center h-full">
                        <div class="text-center">
                            <i class="fas fa-map-marked-alt text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-600 dark:text-gray-400">
                                Interactive map will be displayed here
                            </p>
                        </div>
                    </div>
                </div>
            </section>

        </article>
    <?php endwhile; ?>
</main>

<?php
// Handle form submission
if (isset($_POST['submit_contact']) && wp_verify_nonce($_POST['contact_nonce'], 'aqualuxe_contact_form')) {
    $name = sanitize_text_field($_POST['contact_name']);
    $email = sanitize_email($_POST['contact_email']);
    $phone = sanitize_text_field($_POST['contact_phone']);
    $subject = sanitize_text_field($_POST['contact_subject']);
    $message = sanitize_textarea_field($_POST['contact_message']);
    $newsletter = isset($_POST['contact_newsletter']) ? 1 : 0;
    
    // Send email
    $to = aqualuxe_get_option('email', get_option('admin_email'));
    $headers = ['Content-Type: text/html; charset=UTF-8'];
    
    $email_subject = 'Contact Form: ' . $subject;
    $email_message = "
        <h3>New Contact Form Submission</h3>
        <p><strong>Name:</strong> {$name}</p>
        <p><strong>Email:</strong> {$email}</p>
        <p><strong>Phone:</strong> {$phone}</p>
        <p><strong>Subject:</strong> {$subject}</p>
        <p><strong>Message:</strong></p>
        <p>{$message}</p>
        <p><strong>Newsletter Subscription:</strong> " . ($newsletter ? 'Yes' : 'No') . "</p>
    ";
    
    if (wp_mail($to, $email_subject, $email_message, $headers)) {
        echo '<script>alert("Thank you for your message! We will get back to you soon.");</script>';
    } else {
        echo '<script>alert("Sorry, there was an error sending your message. Please try again.");</script>';
    }
}
?>

<?php get_footer(); ?>