<?php
/**
 * Front Page: Hero Section
 *
 * @package AquaLuxe
 */
?>
<section class="hero-section relative h-screen flex items-center justify-center text-center text-white overflow-hidden">
    <div class="absolute top-0 left-0 w-full h-full bg-black opacity-50 z-10"></div>
    <video autoplay loop muted playsinline class="absolute top-0 left-0 w-full h-full object-cover z-0">
        <source src="<?php echo esc_url( get_template_directory_uri() . '/assets/videos/hero-background.mp4' ); ?>" type="video/mp4">
        Your browser does not support the video tag.
    </video>
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
