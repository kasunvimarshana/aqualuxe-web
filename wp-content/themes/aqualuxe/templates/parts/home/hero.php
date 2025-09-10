<?php
/**
 * Hero Section for Home Page
 *
 * @package AquaLuxe
 */
?>

<section class="hero-section bg-gradient-to-br from-blue-900 via-teal-800 to-cyan-600 relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Background Animation -->
    <div class="absolute inset-0 opacity-20">
        <div class="wave-animation"></div>
    </div>

    <!-- Hero Content -->
    <div class="container mx-auto px-4 relative z-10 text-center text-white">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-5xl md:text-7xl font-bold mb-6 animate-fade-in-up">
                Bringing <span class="text-cyan-300">Elegance</span> to Aquatic Life
            </h1>
            <p class="text-xl md:text-2xl mb-8 text-gray-200 animate-fade-in-up animation-delay-200">
                Discover premium ornamental fish, aquatic plants, and custom aquarium solutions for homes and businesses worldwide.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center animate-fade-in-up animation-delay-400">
                <a href="<?php echo get_post_type_archive_link('product'); ?>" class="btn-primary bg-cyan-500 hover:bg-cyan-400 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                    Shop Now
                </a>
                <a href="<?php echo get_page_link(get_page_by_path('services')); ?>" class="btn-secondary border-2 border-white text-white hover:bg-white hover:text-gray-900 px-8 py-4 rounded-lg font-semibold transition-all duration-300">
                    Our Services
                </a>
            </div>
        </div>
    </div>

    <!-- Floating Elements -->
    <div class="absolute top-20 left-10 animate-float">
        <div class="w-4 h-4 bg-cyan-300 rounded-full opacity-60"></div>
    </div>
    <div class="absolute bottom-32 right-16 animate-float-delayed">
        <div class="w-6 h-6 bg-blue-300 rounded-full opacity-40"></div>
    </div>
    <div class="absolute top-1/2 left-1/4 animate-float-slow">
        <div class="w-3 h-3 bg-teal-200 rounded-full opacity-50"></div>
    </div>
</section>

<style>
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

@keyframes floatDelayed {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-15px); }
}

@keyframes floatSlow {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-fade-in-up {
    animation: fadeInUp 0.8s ease-out forwards;
}

.animation-delay-200 {
    animation-delay: 0.2s;
    opacity: 0;
}

.animation-delay-400 {
    animation-delay: 0.4s;
    opacity: 0;
}

.animate-float {
    animation: float 6s ease-in-out infinite;
}

.animate-float-delayed {
    animation: floatDelayed 8s ease-in-out infinite;
}

.animate-float-slow {
    animation: floatSlow 10s ease-in-out infinite;
}

.wave-animation {
    background: linear-gradient(-45deg, transparent 30%, rgba(59, 130, 246, 0.1) 50%, transparent 70%);
    background-size: 200% 200%;
    animation: wave 15s ease-in-out infinite;
    height: 100%;
    width: 100%;
}

@keyframes wave {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}
</style>
