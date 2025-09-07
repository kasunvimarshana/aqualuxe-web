<?php
/**
 * Front Page: Hero Section
 *
 * @package AquaLuxe
 */
?>
<section class="hero-section relative h-screen flex items-center justify-center text-center text-white overflow-hidden bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900">
    <!-- Animated background overlay -->
    <div class="absolute top-0 left-0 w-full h-full bg-black opacity-30 z-10"></div>
    
    <!-- CSS animated water effect -->
    <div class="absolute top-0 left-0 w-full h-full z-5">
        <div class="wave wave1"></div>
        <div class="wave wave2"></div>
        <div class="wave wave3"></div>
    </div>
    
    <div class="relative z-20 p-8">
        <h1 class="text-5xl md:text-7xl font-bold leading-tight mb-4 animate-fade-in-down">
            <?php echo esc_html( get_bloginfo( 'name' ) ); ?>
        </h1>
        <p class="text-lg md:text-2xl mb-8 animate-fade-in-up">
            <?php echo esc_html( get_bloginfo( 'description' ) ); ?>
        </p>
        <a href="#featured-products" class="bg-primary hover:bg-primary-dark text-white font-bold py-3 px-8 rounded-full transition duration-300 ease-in-out transform hover:scale-105">
            <?php esc_html_e( 'Explore Our Collection', 'aqualuxe' ); ?>
        </a>
    </div>
</section>
