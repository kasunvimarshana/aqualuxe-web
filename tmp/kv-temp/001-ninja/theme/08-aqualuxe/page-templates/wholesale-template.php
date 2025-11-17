<?php
/**
 * Template Name: Wholesale/B2B Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="wholesale-hero bg-gradient-to-r from-blue-600 to-teal-500 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center">
                <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6"><?php echo get_the_title(); ?></h1>
                    <?php if (get_field('hero_subtitle')) : ?>
                        <p class="text-xl md:text-2xl mb-8 opacity-90"><?php echo get_field('hero_subtitle'); ?></p>
                    <?php else : ?>
                        <p class="text-xl md:text-2xl mb-8 opacity-90">Premium quality ornamental fish for wholesale and B2B customers</p>
                    <?php endif; ?>
                    
                    <?php if (get_field('hero_button_text') && get_field('hero_button_link')) : ?>
                        <a href="<?php echo get_field('hero_button_link'); ?>" class="inline-block bg-white text-blue-600 hover:bg-blue-50 font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                            <?php echo get_field('hero_button_text'); ?>
                        </a>
                    <?php else : ?>
                        <a href="#contact-form" class="inline-block bg-white text-blue-600 hover:bg-blue-50 font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                            Become a Partner
                        </a>
                    <?php endif; ?>
                </div>
                <div class="w-full lg:w-1/2">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="rounded-lg overflow-hidden shadow-2xl">
                            <?php the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
                        </div>
                    <?php else : ?>
                        <div class="rounded-lg overflow-hidden shadow-2xl bg-blue-700 p-6">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/wholesale-fish.jpg" alt="Wholesale Fish" class="w-full h-auto rounded">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Benefits Section -->
    <section class="wholesale-benefits py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Why Partner With Us</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Benefit 1 -->
                <div class="benefit-card bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="text-primary-600 dark:text-primary-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Premium Quality</h3>
                    <p class="text-gray-700 dark:text-gray-300">Our fish are bred in state-of-the-art facilities with strict quality control measures to ensure healthy, vibrant specimens.</p>
                </div>
                
                <!-- Benefit 2 -->
                <div class="benefit-card bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="text-primary-600 dark:text-primary-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Competitive Pricing</h3>
                    <p class="text-gray-700 dark:text-gray-300">We offer volume-based discounts and flexible payment terms to help maximize your business profitability.</p>
                </div>
                
                <!-- Benefit 3 -->
                <div class="benefit-card bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="text-primary-600 dark:text-primary-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Fast Delivery</h3>
                    <p class="text-gray-700 dark:text-gray-300">Our specialized shipping methods ensure your order arrives quickly and safely, with minimal stress to the fish.</p>
                </div>
                
                <!-- Benefit 4 -->
                <div class="benefit-card bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="text-primary-600 dark:text-primary-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Extensive Catalog</h3>
                    <p class="text-gray-700 dark:text-gray-300">Access to over 200 species of ornamental fish, including rare and exotic varieties not commonly available.</p>
                </div>
                
                <!-- Benefit 5 -->
                <div class="benefit-card bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="text-primary-600 dark:text-primary-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Dedicated Support</h3>
                    <p class="text-gray-700 dark:text-gray-300">Our B2B specialists provide personalized service and expert advice to help grow your business.</p>
                </div>
                
                <!-- Benefit 6 -->
                <div class="benefit-card bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="text-primary-600 dark:text-primary-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Quality Guarantee</h3>
                    <p class="text-gray-700 dark:text-gray-300">We stand behind our products with a satisfaction guarantee and responsive after-sales support.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Categories -->
    <section class="wholesale-products py-16 bg-gray-100 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Our Wholesale Categories</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Category 1 -->
                <div class="category-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="category-image h-48 overflow-hidden">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/tropical-freshwater.jpg" alt="Tropical Freshwater Fish" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Tropical Freshwater Fish</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">Vibrant varieties of angelfish, tetras, discus, guppies, and more from around the world.</p>
                        <a href="#" class="text-primary-600 dark:text-primary-400 font-medium hover:underline flex items-center">
                            View Catalog
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Category 2 -->
                <div class="category-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="category-image h-48 overflow-hidden">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/marine-fish.jpg" alt="Marine Fish" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Marine Fish</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">Stunning saltwater species including clownfish, tangs, gobies, and rare exotic varieties.</p>
                        <a href="#" class="text-primary-600 dark:text-primary-400 font-medium hover:underline flex items-center">
                            View Catalog
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Category 3 -->
                <div class="category-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="category-image h-48 overflow-hidden">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/koi-goldfish.jpg" alt="Koi & Goldfish" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Koi & Goldfish</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">Premium quality koi and goldfish varieties for ponds and aquariums in various sizes.</p>
                        <a href="#" class="text-primary-600 dark:text-primary-400 font-medium hover:underline flex items-center">
                            View Catalog
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Category 4 -->
                <div class="category-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="category-image h-48 overflow-hidden">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/aquatic-plants.jpg" alt="Aquatic Plants" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Aquatic Plants</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">Healthy live plants for aquascaping, including rare and tissue-cultured varieties.</p>
                        <a href="#" class="text-primary-600 dark:text-primary-400 font-medium hover:underline flex items-center">
                            View Catalog
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Category 5 -->
                <div class="category-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="category-image h-48 overflow-hidden">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/aquarium-supplies.jpg" alt="Aquarium Supplies" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Aquarium Supplies</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">Bulk aquarium equipment, food, medications, and accessories at wholesale prices.</p>
                        <a href="#" class="text-primary-600 dark:text-primary-400 font-medium hover:underline flex items-center">
                            View Catalog
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Category 6 -->
                <div class="category-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="category-image h-48 overflow-hidden">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/breeding-pairs.jpg" alt="Breeding Pairs" class="w-full h-full object-cover">
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Breeding Pairs</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-4">Carefully selected breeding pairs of popular species for fish farms and breeders.</p>
                        <a href="#" class="text-primary-600 dark:text-primary-400 font-medium hover:underline flex items-center">
                            View Catalog
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-md">
                    Request Full Catalog
                </a>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">How Our Wholesale Program Works</h2>
            
            <div class="max-w-4xl mx-auto">
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-primary-200 dark:bg-primary-900"></div>
                    
                    <!-- Step 1 -->
                    <div class="relative z-10 mb-12">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Apply for an Account</h3>
                                <p class="text-gray-700 dark:text-gray-300">Fill out our wholesale application form with your business details and requirements.</p>
                            </div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                1
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0 hidden md:block"></div>
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="relative z-10 mb-12">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right hidden md:block"></div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                2
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Account Verification</h3>
                                <p class="text-gray-700 dark:text-gray-300">Our team will verify your business credentials and approve your wholesale account.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="relative z-10 mb-12">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Access Wholesale Portal</h3>
                                <p class="text-gray-700 dark:text-gray-300">Once approved, you'll gain access to our exclusive wholesale portal with pricing and inventory.</p>
                            </div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                3
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0 hidden md:block"></div>
                        </div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="relative z-10 mb-12">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right hidden md:block"></div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                4
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Place Your Order</h3>
                                <p class="text-gray-700 dark:text-gray-300">Browse our catalog and place orders through the portal or with your dedicated account manager.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 5 -->
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Receive & Enjoy</h3>
                                <p class="text-gray-700 dark:text-gray-300">Your order is carefully packed and shipped to your location with our quality guarantee.</p>
                            </div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                5
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0 hidden md:block"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="wholesale-testimonials py-16 bg-gray-100 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">What Our Partners Say</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="testimonial-card bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <div class="mb-4 text-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-6 italic">"AquaLuxe has been our primary supplier for over 5 years. Their fish quality is consistently excellent, and their shipping methods ensure our stock arrives in perfect condition every time."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/testimonial-1.jpg" alt="Testimonial" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Robert Johnson</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Aquatic World, California</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 2 -->
                <div class="testimonial-card bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <div class="mb-4 text-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-6 italic">"The wholesale program at AquaLuxe has transformed our business. Their competitive pricing and exclusive access to rare species has given us a significant edge in our local market."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/testimonial-2.jpg" alt="Testimonial" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">Sarah Martinez</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Tropical Haven, Florida</p>
                        </div>
                    </div>
                </div>
                
                <!-- Testimonial 3 -->
                <div class="testimonial-card bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                    <div class="mb-4 text-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                        </svg>
                    </div>
                    <p class="text-gray-700 dark:text-gray-300 mb-6 italic">"As a new aquarium shop owner, the support from AquaLuxe's B2B team has been invaluable. They've helped us select the right inventory mix and provided excellent business advice."</p>
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full overflow-hidden mr-4">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/testimonial-3.jpg" alt="Testimonial" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white">David Chen</h4>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Ocean Wonders, New York</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section id="contact-form" class="wholesale-contact py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-4 text-gray-900 dark:text-white">Become a Wholesale Partner</h2>
                <p class="text-center text-gray-700 dark:text-gray-300 mb-12 max-w-2xl mx-auto">Fill out the form below to apply for a wholesale account. Our team will review your application and contact you within 1-2 business days.</p>
                
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <form class="wholesale-application-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Business Information -->
                            <div class="col-span-2">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Business Information</h3>
                            </div>
                            
                            <div>
                                <label for="business_name" class="block text-gray-700 dark:text-gray-300 mb-2">Business Name *</label>
                                <input type="text" id="business_name" name="business_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="business_type" class="block text-gray-700 dark:text-gray-300 mb-2">Business Type *</label>
                                <select id="business_type" name="business_type" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Business Type</option>
                                    <option value="retail">Retail Store</option>
                                    <option value="online">Online Store</option>
                                    <option value="breeder">Fish Breeder</option>
                                    <option value="distributor">Distributor</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="tax_id" class="block text-gray-700 dark:text-gray-300 mb-2">Tax ID / Business License *</label>
                                <input type="text" id="tax_id" name="tax_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="years_in_business" class="block text-gray-700 dark:text-gray-300 mb-2">Years in Business</label>
                                <input type="number" id="years_in_business" name="years_in_business" min="0" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div class="col-span-2">
                                <label for="business_address" class="block text-gray-700 dark:text-gray-300 mb-2">Business Address *</label>
                                <input type="text" id="business_address" name="business_address" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="city" class="block text-gray-700 dark:text-gray-300 mb-2">City *</label>
                                <input type="text" id="city" name="city" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="state" class="block text-gray-700 dark:text-gray-300 mb-2">State/Province *</label>
                                <input type="text" id="state" name="state" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="zip" class="block text-gray-700 dark:text-gray-300 mb-2">ZIP/Postal Code *</label>
                                <input type="text" id="zip" name="zip" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="country" class="block text-gray-700 dark:text-gray-300 mb-2">Country *</label>
                                <input type="text" id="country" name="country" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <!-- Contact Information -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Contact Information</h3>
                            </div>
                            
                            <div>
                                <label for="contact_name" class="block text-gray-700 dark:text-gray-300 mb-2">Contact Name *</label>
                                <input type="text" id="contact_name" name="contact_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="position" class="block text-gray-700 dark:text-gray-300 mb-2">Position *</label>
                                <input type="text" id="position" name="position" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-gray-700 dark:text-gray-300 mb-2">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <!-- Business Requirements -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Business Requirements</h3>
                            </div>
                            
                            <div class="col-span-2">
                                <label for="product_interest" class="block text-gray-700 dark:text-gray-300 mb-2">Products of Interest (Select all that apply) *</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="product_interest[]" value="tropical_freshwater" class="h-5 w-5 text-primary-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Tropical Freshwater Fish</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="product_interest[]" value="marine_fish" class="h-5 w-5 text-primary-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Marine Fish</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="product_interest[]" value="koi_goldfish" class="h-5 w-5 text-primary-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Koi & Goldfish</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="product_interest[]" value="aquatic_plants" class="h-5 w-5 text-primary-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Aquatic Plants</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="product_interest[]" value="aquarium_supplies" class="h-5 w-5 text-primary-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Aquarium Supplies</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="product_interest[]" value="breeding_pairs" class="h-5 w-5 text-primary-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Breeding Pairs</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <label for="estimated_order" class="block text-gray-700 dark:text-gray-300 mb-2">Estimated Monthly Order Value *</label>
                                <select id="estimated_order" name="estimated_order" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Range</option>
                                    <option value="under_1000">Under $1,000</option>
                                    <option value="1000_5000">$1,000 - $5,000</option>
                                    <option value="5000_10000">$5,000 - $10,000</option>
                                    <option value="10000_25000">$10,000 - $25,000</option>
                                    <option value="over_25000">Over $25,000</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="frequency" class="block text-gray-700 dark:text-gray-300 mb-2">Expected Order Frequency *</label>
                                <select id="frequency" name="frequency" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Frequency</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="biweekly">Bi-Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="as_needed">As Needed</option>
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <label for="additional_info" class="block text-gray-700 dark:text-gray-300 mb-2">Additional Information</label>
                                <textarea id="additional_info" name="additional_info" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white"></textarea>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="flex items-start">
                                    <input type="checkbox" name="terms" required class="h-5 w-5 text-primary-600 mt-1">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300 text-sm">
                                        I agree to the <a href="#" class="text-primary-600 hover:underline">Terms and Conditions</a> and acknowledge that my information will be processed in accordance with the <a href="#" class="text-primary-600 hover:underline">Privacy Policy</a>. *
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="text-center mt-8">
                            <button type="submit" class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-md">
                                Submit Application
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="wholesale-faq py-16 bg-gray-100 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Frequently Asked Questions</h2>
            
            <div class="max-w-4xl mx-auto">
                <div class="space-y-6">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">What are the minimum order requirements?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>Our minimum order requirement for wholesale accounts is $500 for the first order and $300 for subsequent orders. This helps us maintain efficiency in our shipping and handling processes while providing you with the best possible pricing.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">How does shipping work for wholesale orders?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>We ship wholesale orders using specialized transport methods designed specifically for live fish. For orders over $1,000, we offer free shipping within the continental United States. International shipping is available with additional fees based on destination. All shipments include heat or cold packs as needed and oxygen-enriched bags to ensure the health of the fish during transit.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">What payment methods do you accept?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>We accept various payment methods including credit cards, wire transfers, and ACH payments. For established wholesale partners, we offer Net 30 terms after a successful payment history. New accounts typically require payment before shipment for the first 3 orders.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 4 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">Do you offer dropshipping services?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>Yes, we offer dropshipping services for qualified partners. This service includes white-label packaging and custom invoicing. There is a small additional fee per order for this service, and it requires a separate application process. Please contact our wholesale department for more information.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 5 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">What is your DOA (Dead on Arrival) policy?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>We stand behind the quality of our fish with a comprehensive DOA policy. If any fish arrive dead, we provide credit or replacement with proper documentation (photos within 2 hours of delivery). Our average DOA rate is less than 1%, significantly below industry standards, thanks to our careful packing and shipping procedures.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 6 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">How often do you update your inventory?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>Our inventory is updated daily, and our wholesale portal reflects real-time availability. We receive new shipments weekly from our global network of breeders and suppliers. Wholesale partners receive advance notice of upcoming special shipments and rare species availability through our newsletter.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php
    while ( have_posts() ) :
        the_post();
        
        get_template_part( 'template-parts/content/content', 'page' );
        
    endwhile; // End of the loop.
    ?>
</main><!-- #main -->

<script>
    // Simple FAQ toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const faqQuestions = document.querySelectorAll('.faq-question');
        
        faqQuestions.forEach(question => {
            question.addEventListener('click', () => {
                const answer = question.nextElementSibling;
                const icon = question.querySelector('svg');
                
                // Toggle answer visibility
                if (answer.style.maxHeight) {
                    answer.style.maxHeight = null;
                    icon.classList.remove('rotate-180');
                } else {
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                    icon.classList.add('rotate-180');
                }
            });
        });
    });
</script>

<?php
get_footer();