<?php
/**
 * Template part for displaying the hero section on the FAQ page
 *
 * @package AquaLuxe
 */
?>

<section class="relative bg-gradient-to-r from-blue-900 to-teal-800 text-white py-20">
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute inset-0 bg-blue-900 opacity-60"></div>
        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/faq-hero-bg.jpg'); ?>" alt="" class="w-full h-full object-cover">
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">Frequently Asked Questions</h1>
            <p class="text-xl md:text-2xl mb-8 text-blue-100">
                Find answers to common questions about our products, services, and aquarium care.
            </p>
            
            <div class="mt-8 max-w-xl mx-auto">
                <form role="search" method="get" class="search-form relative" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <label class="sr-only">
                        <span><?php echo _x( 'Search FAQs:', 'label', 'aqualuxe' ); ?></span>
                    </label>
                    <div class="relative">
                        <input type="search" class="w-full px-5 py-3 rounded-lg text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="<?php echo esc_attr_x( 'Search for answers...', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-teal-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" name="post_type" value="page" />
                </form>
            </div>
        </div>
    </div>
</section>