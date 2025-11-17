<?php
/**
 * Template part for displaying the hero section on the Services page
 *
 * @package AquaLuxe
 */
?>

<section class="relative bg-gradient-to-r from-blue-900 to-teal-800 text-white py-24">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute inset-0 bg-blue-900 opacity-60"></div>
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/services-hero-bg.jpg'); ?>" alt="" class="w-full h-full object-cover">
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">Premium Aquatic Services</h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">Expert aquarium design, maintenance, and specialized services for hobbyists and professionals alike.</p>
            <div class="flex flex-wrap gap-4">
                <a href="#contact" class="bg-teal-500 hover:bg-teal-600 text-white font-medium py-3 px-6 rounded-lg transition duration-300 inline-flex items-center">
                    <span>Schedule Consultation</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
                <a href="#services" class="bg-white bg-opacity-20 hover:bg-opacity-30 text-white font-medium py-3 px-6 rounded-lg transition duration-300">
                    Explore Services
                </a>
            </div>
        </div>
    </div>
</section>