<?php
/**
 * Template part for displaying the hero section on the Contact page
 *
 * @package AquaLuxe
 */
?>

<section class="relative bg-gradient-to-r from-blue-900 to-teal-800 text-white py-20">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute inset-0 bg-blue-900 opacity-60"></div>
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/contact-hero-bg.jpg'); ?>" alt="" class="w-full h-full object-cover">
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">Get in Touch</h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                Have questions about our products or services? We're here to help you create and maintain your perfect aquatic environment.
            </p>
            
            <div class="flex flex-wrap gap-4">
                <a href="#contact-form" class="bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 px-6 rounded-lg transition duration-300 inline-flex items-center">
                    <span>Send a Message</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </a>
                <a href="tel:+15551234567" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-3 px-6 rounded-lg transition duration-300 inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                    </svg>
                    <span>Call Us</span>
                </a>
            </div>
        </div>
    </div>
</section>