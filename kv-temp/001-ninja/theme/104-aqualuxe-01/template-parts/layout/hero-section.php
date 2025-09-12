<?php
/**
 * Template part for displaying hero section
 *
 * @package AquaLuxe
 */

$hero_image = isset($hero_image) ? $hero_image : aqualuxe_get_option('hero_image', '');
$hero_title = isset($hero_title) ? $hero_title : aqualuxe_get_option('hero_title', __('Bringing Elegance to Aquatic Life', 'aqualuxe'));
$hero_subtitle = isset($hero_subtitle) ? $hero_subtitle : aqualuxe_get_option('hero_subtitle', __('Premium aquatic solutions for discerning enthusiasts worldwide', 'aqualuxe'));
$hero_cta_text = isset($hero_cta_text) ? $hero_cta_text : aqualuxe_get_option('hero_cta_text', __('Explore Our Collection', 'aqualuxe'));
$hero_cta_url = isset($hero_cta_url) ? $hero_cta_url : (aqualuxe_is_woocommerce_active() ? wc_get_page_permalink('shop') : '#');
$hero_video = isset($hero_video) ? $hero_video : aqualuxe_get_option('hero_video', '');
$hero_height = isset($hero_height) ? $hero_height : 'min-h-screen';
?>

<section class="hero-section relative <?php echo esc_attr($hero_height); ?> flex items-center justify-center overflow-hidden" role="banner">
    
    <!-- Background Media -->
    <div class="hero-background absolute inset-0 z-0">
        <?php if (!empty($hero_video)) : ?>
            <!-- Video Background -->
            <video 
                class="absolute inset-0 w-full h-full object-cover" 
                autoplay 
                muted 
                loop 
                playsinline
                aria-hidden="true"
            >
                <source src="<?php echo esc_url($hero_video); ?>" type="video/mp4">
                <!-- Fallback to image if video fails -->
                <?php if (!empty($hero_image)) : ?>
                    <img 
                        src="<?php echo esc_url($hero_image); ?>" 
                        alt="<?php echo esc_attr($hero_title); ?>"
                        class="w-full h-full object-cover"
                    >
                <?php endif; ?>
            </video>
        <?php elseif (!empty($hero_image)) : ?>
            <!-- Image Background -->
            <img 
                src="<?php echo esc_url($hero_image); ?>" 
                alt="<?php echo esc_attr($hero_title); ?>"
                class="w-full h-full object-cover"
                loading="eager"
            >
        <?php else : ?>
            <!-- Default Gradient Background -->
            <div class="w-full h-full bg-gradient-to-br from-blue-600 via-teal-500 to-cyan-400"></div>
        <?php endif; ?>
        
        <!-- Overlay -->
        <div class="absolute inset-0 bg-black bg-opacity-40"></div>
    </div>
    
    <!-- Hero Content -->
    <div class="hero-content relative z-10 container mx-auto px-4 text-center text-white">
        <div class="max-w-4xl mx-auto">
            
            <!-- Animated Elements -->
            <div class="hero-animation" data-aos="fade-up" data-aos-duration="1000">
                
                <!-- Title -->
                <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl xl:text-7xl font-bold mb-6 leading-tight">
                    <?php echo wp_kses_post($hero_title); ?>
                </h1>
                
                <!-- Subtitle -->
                <?php if (!empty($hero_subtitle)) : ?>
                    <p class="hero-subtitle text-lg md:text-xl lg:text-2xl mb-8 text-gray-200 leading-relaxed max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="200">
                        <?php echo wp_kses_post($hero_subtitle); ?>
                    </p>
                <?php endif; ?>
                
                <!-- CTA Buttons -->
                <div class="hero-cta space-y-4 sm:space-y-0 sm:space-x-4 sm:flex sm:justify-center" data-aos="fade-up" data-aos-delay="400">
                    <?php if (!empty($hero_cta_text) && !empty($hero_cta_url)) : ?>
                        <a href="<?php echo esc_url($hero_cta_url); ?>" 
                           class="btn btn-primary btn-lg inline-flex items-center">
                            <?php echo esc_html($hero_cta_text); ?>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                            </svg>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Secondary CTA -->
                    <a href="<?php echo esc_url(get_permalink(get_page_by_path('about'))); ?>" 
                       class="btn btn-outline btn-lg inline-flex items-center text-white border-white hover:bg-white hover:text-gray-900">
                        <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </a>
                </div>
                
                <!-- Features List -->
                <div class="hero-features mt-12 grid grid-cols-1 md:grid-cols-3 gap-6 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="600">
                    <div class="feature-item flex items-center justify-center space-x-3">
                        <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="text-sm md:text-base"><?php esc_html_e('Premium Quality', 'aqualuxe'); ?></span>
                    </div>
                    
                    <div class="feature-item flex items-center justify-center space-x-3">
                        <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9v-9m0-9v9"></path>
                        </svg>
                        <span class="text-sm md:text-base"><?php esc_html_e('Global Shipping', 'aqualuxe'); ?></span>
                    </div>
                    
                    <div class="feature-item flex items-center justify-center space-x-3">
                        <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                        </svg>
                        <span class="text-sm md:text-base"><?php esc_html_e('Expert Care', 'aqualuxe'); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="hero-scroll-indicator absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10" data-aos="fade-in" data-aos-delay="1000">
        <a href="#main-content" class="scroll-down text-white hover:text-primary-400 transition-colors duration-300" aria-label="<?php esc_attr_e('Scroll down', 'aqualuxe'); ?>">
            <div class="scroll-down-text text-sm mb-2"><?php esc_html_e('Scroll Down', 'aqualuxe'); ?></div>
            <svg class="w-6 h-6 mx-auto animate-bounce" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
            </svg>
        </a>
    </div>
</section>