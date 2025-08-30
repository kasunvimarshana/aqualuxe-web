<?php
/**
 * Template Name: Services Page
 *
 * This is the template that displays the services page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="page-hero bg-gradient-to-r from-blue-900 to-teal-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-4xl md:text-5xl font-bold mb-6">
                    <?php the_title(); ?>
                </h1>
                <?php if (get_field('page_subtitle')) : ?>
                    <p class="text-xl text-blue-100">
                        <?php echo esc_html(get_field('page_subtitle')); ?>
                    </p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Services Introduction -->
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-12">
                <?php if (get_field('services_intro_heading')) : ?>
                    <h2 class="text-3xl font-bold mb-6 text-gray-900 dark:text-white">
                        <?php echo esc_html(get_field('services_intro_heading')); ?>
                    </h2>
                <?php endif; ?>
                
                <div class="prose dark:prose-invert max-w-none mx-auto">
                    <?php 
                    if (get_field('services_intro_content')) {
                        echo wp_kses_post(get_field('services_intro_content'));
                    } else {
                        the_content();
                    }
                    ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Services List -->
    <section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <?php if (have_rows('services')) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while (have_rows('services')) : the_row(); ?>
                        <div class="service-card bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                            <?php if (get_sub_field('image')) : ?>
                                <div class="service-image h-48">
                                    <img src="<?php echo esc_url(get_sub_field('image')); ?>" alt="<?php echo esc_attr(get_sub_field('title')); ?>" class="w-full h-full object-cover">
                                </div>
                            <?php endif; ?>
                            <div class="p-6">
                                <?php if (get_sub_field('icon')) : ?>
                                    <div class="service-icon text-primary dark:text-primary-dark mb-4">
                                        <?php echo wp_kses_post(get_sub_field('icon')); ?>
                                    </div>
                                <?php endif; ?>
                                <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                                    <?php echo esc_html(get_sub_field('title')); ?>
                                </h3>
                                <div class="text-gray-700 dark:text-gray-300 mb-4">
                                    <?php echo wp_kses_post(get_sub_field('description')); ?>
                                </div>
                                <?php if (get_sub_field('link')) : ?>
                                    <a href="<?php echo esc_url(get_sub_field('link')); ?>" class="inline-flex items-center text-primary dark:text-primary-dark hover:text-primary-dark dark:hover:text-primary transition-colors duration-300">
                                        <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <!-- Default Services -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Service 1: Custom Aquarium Design -->
                    <div class="service-card bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="service-image h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                                <?php esc_html_e('Custom Aquarium Design', 'aqualuxe'); ?>
                            </h3>
                            <div class="text-gray-700 dark:text-gray-300 mb-4">
                                <p><?php esc_html_e('Our expert designers create custom aquarium setups tailored to your space and preferences. From concept to installation, we handle every detail to create a stunning aquatic display.', 'aqualuxe'); ?></p>
                            </div>
                            <a href="#" class="inline-flex items-center text-primary dark:text-primary-dark hover:text-primary-dark dark:hover:text-primary transition-colors duration-300">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Service 2: Aquarium Maintenance -->
                    <div class="service-card bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="service-image h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                                <?php esc_html_e('Aquarium Maintenance', 'aqualuxe'); ?>
                            </h3>
                            <div class="text-gray-700 dark:text-gray-300 mb-4">
                                <p><?php esc_html_e('Our professional maintenance services keep your aquarium in pristine condition. Regular cleaning, water testing, equipment checks, and fish health monitoring ensure a thriving ecosystem.', 'aqualuxe'); ?></p>
                            </div>
                            <a href="#" class="inline-flex items-center text-primary dark:text-primary-dark hover:text-primary-dark dark:hover:text-primary transition-colors duration-300">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Service 3: Breeding Consultation -->
                    <div class="service-card bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="service-image h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                                <?php esc_html_e('Breeding Consultation', 'aqualuxe'); ?>
                            </h3>
                            <div class="text-gray-700 dark:text-gray-300 mb-4">
                                <p><?php esc_html_e('Our breeding experts provide consultation services for collectors interested in breeding rare fish species. We offer guidance on tank setup, water parameters, feeding, and breeding techniques.', 'aqualuxe'); ?></p>
                            </div>
                            <a href="#" class="inline-flex items-center text-primary dark:text-primary-dark hover:text-primary-dark dark:hover:text-primary transition-colors duration-300">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Service 4: Aquascaping -->
                    <div class="service-card bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="service-image h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                                <?php esc_html_e('Aquascaping', 'aqualuxe'); ?>
                            </h3>
                            <div class="text-gray-700 dark:text-gray-300 mb-4">
                                <p><?php esc_html_e('Transform your aquarium into a living work of art with our professional aquascaping services. Our designers create stunning underwater landscapes using premium plants, hardscape materials, and lighting.', 'aqualuxe'); ?></p>
                            </div>
                            <a href="#" class="inline-flex items-center text-primary dark:text-primary-dark hover:text-primary-dark dark:hover:text-primary transition-colors duration-300">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Service 5: Fish Health Services -->
                    <div class="service-card bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="service-image h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                                <?php esc_html_e('Fish Health Services', 'aqualuxe'); ?>
                            </h3>
                            <div class="text-gray-700 dark:text-gray-300 mb-4">
                                <p><?php esc_html_e('Our fish health specialists provide diagnostic and treatment services for sick or stressed fish. We offer water quality testing, disease identification, and treatment recommendations to keep your aquatic pets healthy.', 'aqualuxe'); ?></p>
                            </div>
                            <a href="#" class="inline-flex items-center text-primary dark:text-primary-dark hover:text-primary-dark dark:hover:text-primary transition-colors duration-300">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Service 6: Custom Fish Sourcing -->
                    <div class="service-card bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="service-image h-48 bg-gray-200 dark:bg-gray-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-24 w-24 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">
                                <?php esc_html_e('Custom Fish Sourcing', 'aqualuxe'); ?>
                            </h3>
                            <div class="text-gray-700 dark:text-gray-300 mb-4">
                                <p><?php esc_html_e('Looking for a specific rare species? Our global network allows us to source even the most elusive fish species for serious collectors. We handle all logistics to deliver healthy specimens to your door.', 'aqualuxe'); ?></p>
                            </div>
                            <a href="#" class="inline-flex items-center text-primary dark:text-primary-dark hover:text-primary-dark dark:hover:text-primary transition-colors duration-300">
                                <?php esc_html_e('Learn More', 'aqualuxe'); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Service Process -->
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_field('process_heading', __('Our Service Process', 'aqualuxe'))); ?>
            </h2>
            
            <?php if (have_rows('process_steps')) : ?>
                <div class="max-w-4xl mx-auto">
                    <div class="process-timeline relative">
                        <?php 
                        $step_count = 1;
                        while (have_rows('process_steps')) : the_row(); 
                        ?>
                            <div class="process-step relative pl-16 pb-12 <?php echo ($step_count < count(get_field('process_steps'))) ? 'border-l-2 border-primary dark:border-primary-dark' : ''; ?>">
                                <div class="process-number absolute -left-4 w-8 h-8 rounded-full bg-primary dark:bg-primary-dark flex items-center justify-center text-white font-bold">
                                    <?php echo esc_html($step_count); ?>
                                </div>
                                <div class="process-content">
                                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                        <?php echo esc_html(get_sub_field('title')); ?>
                                    </h3>
                                    <div class="text-gray-700 dark:text-gray-300">
                                        <?php echo wp_kses_post(get_sub_field('description')); ?>
                                    </div>
                                </div>
                            </div>
                        <?php 
                        $step_count++;
                        endwhile; 
                        ?>
                    </div>
                </div>
            <?php else : ?>
                <!-- Default Process Steps -->
                <div class="max-w-4xl mx-auto">
                    <div class="process-timeline relative">
                        <div class="process-step relative pl-16 pb-12 border-l-2 border-primary dark:border-primary-dark">
                            <div class="process-number absolute -left-4 w-8 h-8 rounded-full bg-primary dark:bg-primary-dark flex items-center justify-center text-white font-bold">
                                1
                            </div>
                            <div class="process-content">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                    <?php esc_html_e('Initial Consultation', 'aqualuxe'); ?>
                                </h3>
                                <div class="text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('We begin with a detailed consultation to understand your needs, preferences, and goals. Whether you\'re looking for a custom aquarium design or specific fish species, we take the time to understand your vision.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="process-step relative pl-16 pb-12 border-l-2 border-primary dark:border-primary-dark">
                            <div class="process-number absolute -left-4 w-8 h-8 rounded-full bg-primary dark:bg-primary-dark flex items-center justify-center text-white font-bold">
                                2
                            </div>
                            <div class="process-content">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                    <?php esc_html_e('Custom Proposal', 'aqualuxe'); ?>
                                </h3>
                                <div class="text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('Based on your consultation, we create a detailed proposal including design concepts, species recommendations, equipment specifications, and pricing. We work with you to refine the proposal until it perfectly matches your vision.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="process-step relative pl-16 pb-12 border-l-2 border-primary dark:border-primary-dark">
                            <div class="process-number absolute -left-4 w-8 h-8 rounded-full bg-primary dark:bg-primary-dark flex items-center justify-center text-white font-bold">
                                3
                            </div>
                            <div class="process-content">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                    <?php esc_html_e('Implementation', 'aqualuxe'); ?>
                                </h3>
                                <div class="text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('Our expert team handles the implementation of your project with meticulous attention to detail. From aquarium installation to aquascaping and fish introduction, we ensure everything is done to the highest standards.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="process-step relative pl-16">
                            <div class="process-number absolute -left-4 w-8 h-8 rounded-full bg-primary dark:bg-primary-dark flex items-center justify-center text-white font-bold">
                                4
                            </div>
                            <div class="process-content">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                    <?php esc_html_e('Ongoing Support', 'aqualuxe'); ?>
                                </h3>
                                <div class="text-gray-700 dark:text-gray-300">
                                    <p><?php esc_html_e('Our relationship doesn\'t end with delivery. We provide ongoing support, maintenance services, and expert advice to ensure your aquatic ecosystem thrives for years to come.', 'aqualuxe'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- Pricing Section -->
    <?php if (get_field('show_pricing') || have_rows('pricing_plans')) : ?>
    <section class="py-16 bg-gray-50 dark:bg-gray-800 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_field('pricing_heading', __('Our Pricing Plans', 'aqualuxe'))); ?>
            </h2>
            
            <?php if (have_rows('pricing_plans')) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <?php while (have_rows('pricing_plans')) : the_row(); ?>
                        <div class="pricing-plan bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1 <?php echo (get_sub_field('featured')) ? 'border-2 border-primary dark:border-primary-dark relative' : ''; ?>">
                            <?php if (get_sub_field('featured')) : ?>
                                <div class="absolute top-0 right-0">
                                    <div class="bg-primary text-white text-xs font-bold px-3 py-1 rounded-bl">
                                        <?php esc_html_e('Popular', 'aqualuxe'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="p-6">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                    <?php echo esc_html(get_sub_field('name')); ?>
                                </h3>
                                <div class="price-amount text-3xl font-bold text-primary dark:text-primary-dark mb-4">
                                    <?php echo esc_html(get_sub_field('price')); ?>
                                    <?php if (get_sub_field('price_period')) : ?>
                                        <span class="text-sm text-gray-600 dark:text-gray-400 font-normal">
                                            / <?php echo esc_html(get_sub_field('price_period')); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="text-gray-700 dark:text-gray-300 mb-6">
                                    <?php echo wp_kses_post(get_sub_field('description')); ?>
                                </div>
                                <?php if (have_rows('features')) : ?>
                                    <ul class="space-y-3 mb-6">
                                        <?php while (have_rows('features')) : the_row(); ?>
                                            <li class="flex items-start">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                </svg>
                                                <span class="text-gray-700 dark:text-gray-300">
                                                    <?php echo esc_html(get_sub_field('feature')); ?>
                                                </span>
                                            </li>
                                        <?php endwhile; ?>
                                    </ul>
                                <?php endif; ?>
                                <?php if (get_sub_field('button_url')) : ?>
                                    <a href="<?php echo esc_url(get_sub_field('button_url')); ?>" class="block w-full text-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm <?php echo (get_sub_field('featured')) ? 'text-white bg-primary hover:bg-primary-dark' : 'text-primary bg-white border-primary hover:bg-gray-50 dark:text-primary-dark dark:bg-gray-700 dark:border-primary-dark dark:hover:bg-gray-600'; ?> focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-dark transition-colors duration-300">
                                        <?php echo esc_html(get_sub_field('button_text', __('Get Started', 'aqualuxe'))); ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <!-- Default Pricing Plans -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 max-w-5xl mx-auto">
                    <!-- Basic Plan -->
                    <div class="pricing-plan bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                <?php esc_html_e('Basic Maintenance', 'aqualuxe'); ?>
                            </h3>
                            <div class="price-amount text-3xl font-bold text-primary dark:text-primary-dark mb-4">
                                $99<span class="text-sm text-gray-600 dark:text-gray-400 font-normal">/ month</span>
                            </div>
                            <div class="text-gray-700 dark:text-gray-300 mb-6">
                                <p><?php esc_html_e('Essential maintenance for small to medium aquariums.', 'aqualuxe'); ?></p>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Monthly maintenance visit', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Water testing & treatment', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Filter cleaning', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Basic glass cleaning', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Email support', 'aqualuxe'); ?>
                                    </span>
                                </li>
                            </ul>
                            <a href="#" class="block w-full text-center px-6 py-3 border border-primary text-primary bg-white hover:bg-gray-50 dark:text-primary-dark dark:bg-gray-700 dark:border-primary-dark dark:hover:bg-gray-600 font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-dark transition-colors duration-300">
                                <?php esc_html_e('Get Started', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Premium Plan -->
                    <div class="pricing-plan bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1 border-2 border-primary dark:border-primary-dark relative">
                        <div class="absolute top-0 right-0">
                            <div class="bg-primary text-white text-xs font-bold px-3 py-1 rounded-bl">
                                <?php esc_html_e('Popular', 'aqualuxe'); ?>
                            </div>
                        </div>
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                <?php esc_html_e('Premium Maintenance', 'aqualuxe'); ?>
                            </h3>
                            <div class="price-amount text-3xl font-bold text-primary dark:text-primary-dark mb-4">
                                $199<span class="text-sm text-gray-600 dark:text-gray-400 font-normal">/ month</span>
                            </div>
                            <div class="text-gray-700 dark:text-gray-300 mb-6">
                                <p><?php esc_html_e('Comprehensive care for serious aquarium enthusiasts.', 'aqualuxe'); ?></p>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Bi-weekly maintenance visits', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Advanced water testing & treatment', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Complete equipment maintenance', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Algae control & prevention', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Fish health monitoring', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Priority phone & email support', 'aqualuxe'); ?>
                                    </span>
                                </li>
                            </ul>
                            <a href="#" class="block w-full text-center px-6 py-3 border border-transparent text-white bg-primary hover:bg-primary-dark font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-dark transition-colors duration-300">
                                <?php esc_html_e('Get Started', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Elite Plan -->
                    <div class="pricing-plan bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="p-6">
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">
                                <?php esc_html_e('Elite Maintenance', 'aqualuxe'); ?>
                            </h3>
                            <div class="price-amount text-3xl font-bold text-primary dark:text-primary-dark mb-4">
                                $349<span class="text-sm text-gray-600 dark:text-gray-400 font-normal">/ month</span>
                            </div>
                            <div class="text-gray-700 dark:text-gray-300 mb-6">
                                <p><?php esc_html_e('Complete care solution for large or multiple aquariums.', 'aqualuxe'); ?></p>
                            </div>
                            <ul class="space-y-3 mb-6">
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Weekly maintenance visits', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Comprehensive water quality management', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Complete system maintenance', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Aquascaping maintenance', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('Proactive fish health management', 'aqualuxe'); ?>
                                    </span>
                                </li>
                                <li class="flex items-start">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary dark:text-primary-dark mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span class="text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e('24/7 emergency support', 'aqualuxe'); ?>
                                    </span>
                                </li>
                            </ul>
                            <a href="#" class="block w-full text-center px-6 py-3 border border-primary text-primary bg-white hover:bg-gray-50 dark:text-primary-dark dark:bg-gray-700 dark:border-primary-dark dark:hover:bg-gray-600 font-medium rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary dark:focus:ring-primary-dark transition-colors duration-300">
                                <?php esc_html_e('Get Started', 'aqualuxe'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if (get_field('pricing_note')) : ?>
                <div class="max-w-3xl mx-auto text-center mt-8 text-gray-600 dark:text-gray-400">
                    <?php echo wp_kses_post(get_field('pricing_note')); ?>
                </div>
            <?php else : ?>
                <div class="max-w-3xl mx-auto text-center mt-8 text-gray-600 dark:text-gray-400">
                    <p><?php esc_html_e('Need a custom solution? Contact us for a personalized quote tailored to your specific needs.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- Testimonials -->
    <section class="py-16 bg-white dark:bg-gray-900 transition-colors duration-300">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">
                <?php echo esc_html(get_field('testimonials_heading', __('What Our Clients Say', 'aqualuxe'))); ?>
            </h2>
            
            <?php if (have_rows('testimonials')) : ?>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php while (have_rows('testimonials')) : the_row(); ?>
                        <div class="testimonial-card bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                            <div class="flex items-center mb-4">
                                <div class="testimonial-quote text-primary dark:text-primary-dark mr-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                    </svg>
                                </div>
                                <div class="testimonial-author">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white"><?php echo esc_html(get_sub_field('name')); ?></h3>
                                    <?php if (get_sub_field('role')) : ?>
                                        <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo esc_html(get_sub_field('role')); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="testimonial-content text-gray-700 dark:text-gray-300">
                                <p><?php echo esc_html(get_sub_field('content')); ?></p>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php else : ?>
                <!-- Default Testimonials -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="testimonial-card bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="testimonial-quote text-primary dark:text-primary-dark mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                </svg>
                            </div>
                            <div class="testimonial-author">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white"><?php esc_html_e('Robert Chen', 'aqualuxe'); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e('Restaurant Owner', 'aqualuxe'); ?></p>
                            </div>
                        </div>
                        <div class="testimonial-content text-gray-700 dark:text-gray-300">
                            <p><?php esc_html_e('AquaLuxe designed and maintains the stunning reef aquarium in our restaurant. Their service is impeccable, and the aquarium has become a major attraction for our customers.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    
                    <div class="testimonial-card bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="testimonial-quote text-primary dark:text-primary-dark mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                </svg>
                            </div>
                            <div class="testimonial-author">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white"><?php esc_html_e('Sarah Johnson', 'aqualuxe'); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e('Collector', 'aqualuxe'); ?></p>
                            </div>
                        </div>
                        <div class="testimonial-content text-gray-700 dark:text-gray-300">
                            <p><?php esc_html_e('I\'ve been using AquaLuxe\'s maintenance services for my rare fish collection for over 3 years. Their expertise has been invaluable in keeping my prized specimens healthy and thriving.', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                    
                    <div class="testimonial-card bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-6 transition-transform duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="flex items-center mb-4">
                            <div class="testimonial-quote text-primary dark:text-primary-dark mr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                </svg>
                            </div>
                            <div class="testimonial-author">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-white"><?php esc_html_e('Michael Torres', 'aqualuxe'); ?></h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e('Office Manager', 'aqualuxe'); ?></p>
                            </div>
                        </div>
                        <div class="testimonial-content text-gray-700 dark:text-gray-300">
                            <p><?php esc_html_e('AquaLuxe installed a stunning aquarium in our office reception area. Their maintenance team keeps it looking perfect, and our clients are always impressed. Worth every penny!', 'aqualuxe'); ?></p>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-6">
                    <?php echo esc_html(get_field('cta_heading', __('Ready to Get Started?', 'aqualuxe'))); ?>
                </h2>
                <p class="text-lg mb-8 text-blue-100">
                    <?php echo esc_html(get_field('cta_text', __('Contact us today to discuss your aquarium needs and discover how our services can help you create and maintain a stunning aquatic environment.', 'aqualuxe'))); ?>
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="<?php echo esc_url(get_field('cta_primary_button_url', get_permalink(get_page_by_path('contact')))); ?>" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-primary bg-white hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-300">
                        <?php echo esc_html(get_field('cta_primary_button_text', __('Contact Us', 'aqualuxe'))); ?>
                    </a>
                    <?php if (get_field('cta_secondary_button_url')) : ?>
                        <a href="<?php echo esc_url(get_field('cta_secondary_button_url')); ?>" class="inline-flex items-center px-6 py-3 border border-white text-base font-medium rounded-md text-white hover:bg-white hover:text-primary focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-white transition-colors duration-300">
                            <?php echo esc_html(get_field('cta_secondary_button_text', __('Learn More', 'aqualuxe'))); ?>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main><!-- #main -->

<?php
get_footer();