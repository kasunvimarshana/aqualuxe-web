<?php
/**
 * Call to Action Section for Home Page
 *
 * @package AquaLuxe
 */
?>

<section class="cta-section bg-gradient-to-r from-gray-800 via-gray-900 to-black py-20 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
            <defs>
                <pattern id="grid" width="60" height="60" patternUnits="userSpaceOnUse">
                    <path d="m 60,0 L 0,0 0,60" fill="none" stroke="currentColor" stroke-width="1"/>
                </pattern>
            </defs>
            <rect width="100%" height="100%" fill="url(#grid)" />
        </svg>
    </div>

    <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-cyan-400 rounded-full opacity-20 animate-pulse"></div>
    <div class="absolute bottom-20 right-20 w-16 h-16 bg-blue-400 rounded-full opacity-15 animate-bounce"></div>
    <div class="absolute top-1/2 right-10 w-12 h-12 bg-teal-400 rounded-full opacity-25"></div>

    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Ready to Transform Your Space with
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-400">
                    Aquatic Elegance?
                </span>
            </h2>

            <p class="text-xl text-gray-300 mb-8 leading-relaxed">
                Whether you're looking for rare ornamental fish, custom aquarium design, or professional maintenance services,
                our team of aquatic specialists is here to bring your vision to life.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8">
                <a href="<?php echo get_page_link(get_page_by_path('contact')); ?>"
                   class="group inline-flex items-center justify-center bg-gradient-to-r from-cyan-500 to-blue-500 hover:from-cyan-400 hover:to-blue-400 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105 hover:shadow-xl">
                    Get Started Today
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                    </svg>
                </a>

                <a href="<?php echo get_post_type_archive_link('service'); ?>"
                   class="group inline-flex items-center justify-center border-2 border-white text-white hover:bg-white hover:text-gray-900 px-8 py-4 rounded-lg font-semibold transition-all duration-300">
                    Explore Services
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            <!-- Stats or Features -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12 pt-12 border-t border-gray-700">
                <div class="text-center">
                    <div class="text-3xl font-bold text-cyan-400 mb-2">500+</div>
                    <div class="text-gray-300">Species Available</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-400 mb-2">50+</div>
                    <div class="text-gray-300">Countries Served</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl font-bold text-teal-400 mb-2">1000+</div>
                    <div class="text-gray-300">Happy Customers</div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
@keyframes pulse {
    0%, 100% {
        opacity: 0.2;
        transform: scale(1);
    }
    50% {
        opacity: 0.4;
        transform: scale(1.05);
    }
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
        opacity: 0.15;
    }
    50% {
        transform: translateY(-10px);
        opacity: 0.25;
    }
}

.animate-pulse {
    animation: pulse 4s ease-in-out infinite;
}

.animate-bounce {
    animation: bounce 3s ease-in-out infinite;
}
</style>
