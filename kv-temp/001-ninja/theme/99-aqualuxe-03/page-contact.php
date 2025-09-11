<?php
/**
 * Template for Contact page
 *
 * Template Name: Contact Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    
    <?php while (have_posts()) : the_post(); ?>
    
    <!-- Hero Section -->
    <section class="page-hero bg-gradient-to-br from-aqua-50 to-luxe-50 py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">
                    <?php the_title(); ?>
                </h1>
                <p class="text-xl text-gray-600 leading-relaxed">
                    <?php _e('Get in touch with our aquatic experts. We\'re here to help with your questions, orders, and consultation needs.', 'aqualuxe'); ?>
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Information -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-3 gap-8 mb-16">
                <?php
                $contact_methods = array(
                    array(
                        'title' => __('Phone Support', 'aqualuxe'),
                        'icon' => '📞',
                        'primary' => '+1 (555) 123-AQUA',
                        'secondary' => __('Mon-Fri 8AM-8PM EST', 'aqualuxe'),
                        'description' => __('Speak directly with our aquatic experts for immediate assistance.', 'aqualuxe'),
                    ),
                    array(
                        'title' => __('Email Support', 'aqualuxe'),
                        'icon' => '✉️',
                        'primary' => 'info@aqualuxe.com',
                        'secondary' => __('Response within 24 hours', 'aqualuxe'),
                        'description' => __('Send us your questions and we\'ll get back to you promptly.', 'aqualuxe'),
                    ),
                    array(
                        'title' => __('Live Chat', 'aqualuxe'),
                        'icon' => '💬',
                        'primary' => __('Available Now', 'aqualuxe'),
                        'secondary' => __('Mon-Fri 9AM-6PM EST', 'aqualuxe'),
                        'description' => __('Chat with our team for quick answers to your questions.', 'aqualuxe'),
                    ),
                );
                
                foreach ($contact_methods as $index => $method) :
                ?>
                    <div class="contact-method text-center p-8 bg-gray-50 rounded-xl hover:shadow-lg transition-all duration-300" 
                         data-animate="fade-in" style="animation-delay: <?php echo $index * 0.2; ?>s">
                        <div class="method-icon text-5xl mb-4"><?php echo $method['icon']; ?></div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo $method['title']; ?></h3>
                        <div class="method-primary text-lg font-semibold text-aqua-600 mb-2"><?php echo $method['primary']; ?></div>
                        <div class="method-secondary text-sm text-gray-500 mb-4"><?php echo $method['secondary']; ?></div>
                        <p class="text-gray-600 leading-relaxed"><?php echo $method['description']; ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Contact Form & Map -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-16">
                
                <!-- Contact Form -->
                <div class="contact-form-section" data-animate="fade-in">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">
                        <?php _e('Send us a Message', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-lg text-gray-600 mb-8">
                        <?php _e('Have a question about our products or services? Fill out the form below and we\'ll get back to you as soon as possible.', 'aqualuxe'); ?>
                    </p>
                    
                    <form class="contact-form bg-white rounded-xl p-8 shadow-lg">
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <div class="form-group">
                                <label for="contact_first_name" class="block text-gray-700 font-semibold mb-2"><?php _e('First Name *', 'aqualuxe'); ?></label>
                                <input type="text" id="contact_first_name" name="first_name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-aqua-500 focus:border-transparent">
                            </div>
                            <div class="form-group">
                                <label for="contact_last_name" class="block text-gray-700 font-semibold mb-2"><?php _e('Last Name *', 'aqualuxe'); ?></label>
                                <input type="text" id="contact_last_name" name="last_name" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-aqua-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="grid md:grid-cols-2 gap-6 mb-6">
                            <div class="form-group">
                                <label for="contact_email" class="block text-gray-700 font-semibold mb-2"><?php _e('Email Address *', 'aqualuxe'); ?></label>
                                <input type="email" id="contact_email" name="email" required 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-aqua-500 focus:border-transparent">
                            </div>
                            <div class="form-group">
                                <label for="contact_phone" class="block text-gray-700 font-semibold mb-2"><?php _e('Phone Number', 'aqualuxe'); ?></label>
                                <input type="tel" id="contact_phone" name="phone" 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-aqua-500 focus:border-transparent">
                            </div>
                        </div>
                        
                        <div class="form-group mb-6">
                            <label for="contact_subject" class="block text-gray-700 font-semibold mb-2"><?php _e('Subject *', 'aqualuxe'); ?></label>
                            <select id="contact_subject" name="subject" required 
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-aqua-500 focus:border-transparent">
                                <option value=""><?php _e('Select a subject', 'aqualuxe'); ?></option>
                                <option value="general"><?php _e('General Inquiry', 'aqualuxe'); ?></option>
                                <option value="products"><?php _e('Product Information', 'aqualuxe'); ?></option>
                                <option value="services"><?php _e('Services Inquiry', 'aqualuxe'); ?></option>
                                <option value="shipping"><?php _e('Shipping & Returns', 'aqualuxe'); ?></option>
                                <option value="technical"><?php _e('Technical Support', 'aqualuxe'); ?></option>
                                <option value="partnership"><?php _e('Partnership Opportunities', 'aqualuxe'); ?></option>
                                <option value="other"><?php _e('Other', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group mb-6">
                            <label for="contact_message" class="block text-gray-700 font-semibold mb-2"><?php _e('Message *', 'aqualuxe'); ?></label>
                            <textarea id="contact_message" name="message" rows="6" required 
                                      placeholder="<?php esc_attr_e('Please provide details about your inquiry...', 'aqualuxe'); ?>"
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-aqua-500 focus:border-transparent"></textarea>
                        </div>
                        
                        <div class="form-group mb-6">
                            <label class="flex items-center">
                                <input type="checkbox" name="newsletter" value="1" class="mr-3 text-aqua-600 focus:ring-aqua-500">
                                <span class="text-gray-700"><?php _e('Subscribe to our newsletter for aquatic tips and product updates', 'aqualuxe'); ?></span>
                            </label>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary bg-aqua-600 hover:bg-aqua-700 text-white px-8 py-4 rounded-lg font-semibold transition-colors w-full md:w-auto">
                                <?php _e('Send Message', 'aqualuxe'); ?>
                            </button>
                        </div>
                        
                        <?php wp_nonce_field('contact_form', 'contact_form_nonce'); ?>
                    </form>
                </div>
                
                <!-- Map & Office Info -->
                <div class="map-section" data-animate="fade-in">
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">
                        <?php _e('Visit Our Location', 'aqualuxe'); ?>
                    </h2>
                    
                    <!-- Map Placeholder -->
                    <div class="map-container bg-gray-300 rounded-xl h-80 mb-8 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-gray-600 text-center">
                                <div class="text-4xl mb-2">🗺️</div>
                                <div><?php _e('Interactive Map', 'aqualuxe'); ?></div>
                                <div class="text-sm"><?php _e('(Map integration available)', 'aqualuxe'); ?></div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Office Information -->
                    <div class="office-info bg-white rounded-xl p-8 shadow-lg">
                        <h3 class="text-xl font-bold text-gray-900 mb-4"><?php _e('AquaLuxe Headquarters', 'aqualuxe'); ?></h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-start">
                                <div class="text-aqua-600 mr-3 mt-1">📍</div>
                                <div>
                                    <div class="font-semibold text-gray-900"><?php _e('Address', 'aqualuxe'); ?></div>
                                    <div class="text-gray-600">
                                        123 Aquatic Way<br>
                                        Marina District<br>
                                        San Francisco, CA 94123
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-aqua-600 mr-3 mt-1">🕒</div>
                                <div>
                                    <div class="font-semibold text-gray-900"><?php _e('Business Hours', 'aqualuxe'); ?></div>
                                    <div class="text-gray-600">
                                        <?php _e('Monday - Friday: 9:00 AM - 6:00 PM', 'aqualuxe'); ?><br>
                                        <?php _e('Saturday: 10:00 AM - 4:00 PM', 'aqualuxe'); ?><br>
                                        <?php _e('Sunday: Closed', 'aqualuxe'); ?>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-start">
                                <div class="text-aqua-600 mr-3 mt-1">🚗</div>
                                <div>
                                    <div class="font-semibold text-gray-900"><?php _e('Parking', 'aqualuxe'); ?></div>
                                    <div class="text-gray-600">
                                        <?php _e('Free customer parking available', 'aqualuxe'); ?><br>
                                        <?php _e('Wheelchair accessible', 'aqualuxe'); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <a href="#" class="btn btn-outline border-2 border-aqua-600 text-aqua-600 hover:bg-aqua-600 hover:text-white px-6 py-3 rounded-lg font-semibold transition-colors w-full text-center">
                                <?php _e('Get Directions', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    <?php _e('Frequently Asked Questions', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?php _e('Find quick answers to common questions about our products and services.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="max-w-4xl mx-auto">
                <div class="faq-accordion space-y-4">
                    <?php
                    $faqs = array(
                        array(
                            'question' => __('What is your shipping policy?', 'aqualuxe'),
                            'answer' => __('We offer worldwide shipping with live arrival guarantee for fish. Standard shipping takes 2-5 business days, with express options available. All livestock is shipped with proper insulation and oxygen packs.', 'aqualuxe'),
                        ),
                        array(
                            'question' => __('Do you offer installation services?', 'aqualuxe'),
                            'answer' => __('Yes, we provide complete aquarium design and installation services in major metropolitan areas. Our certified technicians handle everything from setup to initial stocking.', 'aqualuxe'),
                        ),
                        array(
                            'question' => __('What kind of guarantee do you provide?', 'aqualuxe'),
                            'answer' => __('We guarantee live arrival for all fish shipments and offer a 30-day health guarantee. Equipment purchases include manufacturer warranties plus our own satisfaction guarantee.', 'aqualuxe'),
                        ),
                        array(
                            'question' => __('Can you help with quarantine services?', 'aqualuxe'),
                            'answer' => __('Absolutely! We offer professional quarantine services for new fish purchases. Our quarantine facilities ensure your fish are healthy and acclimated before joining your aquarium.', 'aqualuxe'),
                        ),
                        array(
                            'question' => __('Do you work with commercial clients?', 'aqualuxe'),
                            'answer' => __('Yes, we work with restaurants, hotels, offices, and other commercial clients. We offer special pricing for bulk orders and ongoing maintenance contracts.', 'aqualuxe'),
                        ),
                    );
                    
                    foreach ($faqs as $index => $faq) :
                    ?>
                        <div class="faq-item bg-gray-50 rounded-xl" data-animate="fade-in" style="animation-delay: <?php echo $index * 0.1; ?>s">
                            <button class="faq-question w-full text-left p-6 font-semibold text-gray-900 hover:text-aqua-600 transition-colors flex justify-between items-center" 
                                    aria-expanded="false">
                                <span><?php echo $faq['question']; ?></span>
                                <svg class="w-6 h-6 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="faq-answer px-6 pb-6 text-gray-600 leading-relaxed" style="display: none;">
                                <?php echo $faq['answer']; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <div class="text-center mt-12">
                    <p class="text-gray-600 mb-4"><?php _e('Can\'t find what you\'re looking for?', 'aqualuxe'); ?></p>
                    <a href="<?php echo home_url('/faq/'); ?>" 
                       class="btn btn-outline border-2 border-aqua-600 text-aqua-600 hover:bg-aqua-600 hover:text-white px-6 py-3 rounded-lg font-semibold transition-colors">
                        <?php _e('View Full FAQ', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Emergency Contact -->
    <section class="py-16 bg-gradient-to-r from-red-600 to-red-700 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">
                    <?php _e('Emergency Aquarium Support', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl mb-6 opacity-90">
                    <?php _e('Experiencing a critical aquarium emergency? Our emergency support team is available 24/7.', 'aqualuxe'); ?>
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="tel:+1555EMERGENCY" 
                       class="btn bg-white text-red-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition-colors">
                        📞 <?php _e('Emergency Hotline', 'aqualuxe'); ?>
                    </a>
                    <a href="#contact-form" 
                       class="btn border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-red-600 transition-all">
                        💬 <?php _e('Live Chat Support', 'aqualuxe'); ?>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <?php endwhile; ?>

</main>

<script>
// FAQ Accordion functionality
document.addEventListener('DOMContentLoaded', function() {
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(question => {
        question.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const answer = this.nextElementSibling;
            
            // Close all other FAQs
            faqQuestions.forEach(otherQuestion => {
                if (otherQuestion !== this) {
                    otherQuestion.setAttribute('aria-expanded', 'false');
                    otherQuestion.nextElementSibling.style.display = 'none';
                    otherQuestion.querySelector('svg').style.transform = 'rotate(0deg)';
                }
            });
            
            // Toggle current FAQ
            if (isExpanded) {
                this.setAttribute('aria-expanded', 'false');
                answer.style.display = 'none';
                this.querySelector('svg').style.transform = 'rotate(0deg)';
            } else {
                this.setAttribute('aria-expanded', 'true');
                answer.style.display = 'block';
                this.querySelector('svg').style.transform = 'rotate(180deg)';
            }
        });
    });
});
</script>

<?php
get_footer();
?>