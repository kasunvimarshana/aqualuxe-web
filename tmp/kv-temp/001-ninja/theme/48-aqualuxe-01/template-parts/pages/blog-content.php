<?php
/**
 * Template part for displaying blog page content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="blog-page-content">
    <!-- Hero Section -->
    <section class="blog-hero bg-primary text-white py-16 md:py-24 mb-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold mb-6">AquaLuxe Blog</h1>
                <p class="text-xl md:text-2xl opacity-90 mb-8">Expert Tips, Insights, and Inspiration for Aquarium Enthusiasts</p>
                <div class="flex justify-center">
                    <div class="w-24 h-1 bg-accent"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Search & Categories Section -->
    <section class="blog-filters py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Search -->
                <div class="blog-search lg:col-span-2">
                    <form class="flex flex-col sm:flex-row gap-4">
                        <input type="text" placeholder="Search articles..." class="flex-grow px-4 py-3 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white">
                        <button type="submit" class="px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
                            <i class="fas fa-search mr-2"></i> Search
                        </button>
                    </form>
                </div>
                
                <!-- Categories -->
                <div class="blog-categories">
                    <div class="bg-white dark:bg-dark-medium rounded-lg shadow-soft p-4">
                        <h3 class="text-lg font-bold mb-3">Categories</h3>
                        <div class="flex flex-wrap gap-2">
                            <a href="#" class="inline-block px-3 py-1 bg-primary-light dark:bg-primary-dark text-primary rounded-full text-sm font-medium transition-colors hover:bg-primary hover:text-white">
                                All
                            </a>
                            <a href="#" class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium transition-colors hover:bg-primary hover:text-white">
                                Freshwater
                            </a>
                            <a href="#" class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium transition-colors hover:bg-primary hover:text-white">
                                Saltwater
                            </a>
                            <a href="#" class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium transition-colors hover:bg-primary hover:text-white">
                                Planted Tanks
                            </a>
                            <a href="#" class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium transition-colors hover:bg-primary hover:text-white">
                                Equipment
                            </a>
                            <a href="#" class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium transition-colors hover:bg-primary hover:text-white">
                                Maintenance
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Article Section -->
    <section class="featured-article py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-0">
                    <div class="featured-article-image aspect-w-16 aspect-h-9 lg:aspect-none lg:h-full">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/blog-featured.jpg' ); ?>" alt="Featured Article" class="w-full h-full object-cover">
                    </div>
                    <div class="featured-article-content p-6 md:p-8 lg:p-12 flex flex-col justify-center">
                        <div class="article-meta flex items-center mb-4">
                            <span class="inline-block px-3 py-1 bg-primary-light dark:bg-primary-dark text-primary rounded-full text-sm font-medium mr-3">
                                Featured
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i> August 10, 2025
                            </span>
                        </div>
                        
                        <h2 class="text-2xl md:text-3xl font-serif font-bold mb-4">
                            <a href="#" class="hover:text-primary transition-colors">
                                The Ultimate Guide to Reef Tank Maintenance
                            </a>
                        </h2>
                        
                        <p class="text-gray-600 dark:text-gray-400 mb-6">
                            Maintaining a thriving reef tank requires attention to detail and consistent care. In this comprehensive guide, we cover everything from water parameters and lighting to coral placement and fish compatibility. Learn the professional techniques that will help your reef ecosystem flourish.
                        </p>
                        
                        <div class="article-footer flex items-center justify-between">
                            <div class="article-author flex items-center">
                                <div class="author-image w-10 h-10 rounded-full overflow-hidden mr-3">
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/author-1.jpg' ); ?>" alt="Dr. Michael Rodriguez" class="w-full h-full object-cover">
                                </div>
                                <div class="author-name text-sm font-medium">
                                    Dr. Michael Rodriguez
                                </div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                                Read More
                                <i class="fas fa-arrow-right ml-2"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Recent Articles Section -->
    <section class="recent-articles py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="section-header flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-serif font-bold">
                    Recent Articles
                </h2>
                <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                    View All
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Article 1 -->
                <div class="article-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="article-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/blog-1.jpg' ); ?>" alt="Freshwater Aquascaping Trends" class="w-full h-full object-cover">
                    </div>
                    <div class="article-content p-6">
                        <div class="article-meta flex items-center justify-between mb-3">
                            <span class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium">
                                Freshwater
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i> August 5, 2025
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-3">
                            <a href="#" class="hover:text-primary transition-colors">
                                Top Freshwater Aquascaping Trends for 2025
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Discover the latest trends in freshwater aquascaping, from minimalist Iwagumi layouts to lush Dutch-style planted tanks. We explore innovative techniques and materials that are shaping the art of aquarium design.
                        </p>
                        
                        <div class="article-footer flex items-center justify-between">
                            <div class="article-author flex items-center">
                                <div class="author-image w-8 h-8 rounded-full overflow-hidden mr-2">
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/author-2.jpg' ); ?>" alt="Sarah Chen" class="w-full h-full object-cover">
                                </div>
                                <div class="author-name text-sm">
                                    Sarah Chen
                                </div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors text-sm">
                                Read More
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Article 2 -->
                <div class="article-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="article-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/blog-2.jpg' ); ?>" alt="LED Lighting Systems" class="w-full h-full object-cover">
                    </div>
                    <div class="article-content p-6">
                        <div class="article-meta flex items-center justify-between mb-3">
                            <span class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium">
                                Equipment
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i> July 28, 2025
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-3">
                            <a href="#" class="hover:text-primary transition-colors">
                                Choosing the Right LED Lighting System for Your Aquarium
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            LED lighting technology has revolutionized aquarium keeping. This guide helps you navigate the options, from spectrum and PAR values to controllability features, ensuring you select the perfect lighting for your specific setup.
                        </p>
                        
                        <div class="article-footer flex items-center justify-between">
                            <div class="article-author flex items-center">
                                <div class="author-image w-8 h-8 rounded-full overflow-hidden mr-2">
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/author-3.jpg' ); ?>" alt="Emma Thompson" class="w-full h-full object-cover">
                                </div>
                                <div class="author-name text-sm">
                                    Emma Thompson
                                </div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors text-sm">
                                Read More
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Article 3 -->
                <div class="article-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="article-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/blog-3.jpg' ); ?>" alt="Coral Propagation" class="w-full h-full object-cover">
                    </div>
                    <div class="article-content p-6">
                        <div class="article-meta flex items-center justify-between mb-3">
                            <span class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium">
                                Saltwater
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i> July 15, 2025
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-3">
                            <a href="#" class="hover:text-primary transition-colors">
                                Beginner's Guide to Coral Propagation
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Coral propagation is both rewarding and environmentally responsible. Learn the fundamentals of fragging different coral species, proper tools and techniques, and how to care for coral fragments as they grow into thriving colonies.
                        </p>
                        
                        <div class="article-footer flex items-center justify-between">
                            <div class="article-author flex items-center">
                                <div class="author-image w-8 h-8 rounded-full overflow-hidden mr-2">
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/author-1.jpg' ); ?>" alt="Dr. Michael Rodriguez" class="w-full h-full object-cover">
                                </div>
                                <div class="author-name text-sm">
                                    Dr. Michael Rodriguez
                                </div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors text-sm">
                                Read More
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popular Topics Section -->
    <section class="popular-topics py-12 md:py-16 bg-light-dark dark:bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        Popular Topics
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Explore Our Most-Read Categories
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Dive into our most popular content categories, featuring expert advice, step-by-step guides, and in-depth articles on aquarium care and maintenance.
                </p>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Topic 1 -->
                <a href="#" class="topic-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="topic-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/topic-1.jpg' ); ?>" alt="Beginner Guides" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-6 text-white">
                                <h3 class="text-xl font-bold mb-1">Beginner Guides</h3>
                                <p class="text-sm opacity-90">12 Articles</p>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Topic 2 -->
                <a href="#" class="topic-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="topic-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/topic-2.jpg' ); ?>" alt="Advanced Techniques" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-6 text-white">
                                <h3 class="text-xl font-bold mb-1">Advanced Techniques</h3>
                                <p class="text-sm opacity-90">8 Articles</p>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Topic 3 -->
                <a href="#" class="topic-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="topic-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/topic-3.jpg' ); ?>" alt="Species Spotlights" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-6 text-white">
                                <h3 class="text-xl font-bold mb-1">Species Spotlights</h3>
                                <p class="text-sm opacity-90">15 Articles</p>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Topic 4 -->
                <a href="#" class="topic-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="topic-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/topic-4.jpg' ); ?>" alt="Troubleshooting" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-6 text-white">
                                <h3 class="text-xl font-bold mb-1">Troubleshooting</h3>
                                <p class="text-sm opacity-90">10 Articles</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- More Articles Section -->
    <section class="more-articles py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="section-header flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-serif font-bold">
                    More Articles
                </h2>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button class="px-3 py-1 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Article 4 -->
                <div class="article-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="article-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/blog-4.jpg' ); ?>" alt="Water Parameters" class="w-full h-full object-cover">
                    </div>
                    <div class="article-content p-6">
                        <div class="article-meta flex items-center justify-between mb-3">
                            <span class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium">
                                Maintenance
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i> July 8, 2025
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-3">
                            <a href="#" class="hover:text-primary transition-colors">
                                Understanding and Maintaining Optimal Water Parameters
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Water quality is the foundation of a healthy aquarium. Learn how to test and maintain critical parameters like pH, ammonia, nitrite, nitrate, and more to ensure your aquatic life thrives.
                        </p>
                        
                        <div class="article-footer flex items-center justify-between">
                            <div class="article-author flex items-center">
                                <div class="author-image w-8 h-8 rounded-full overflow-hidden mr-2">
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/author-4.jpg' ); ?>" alt="James Mitchell" class="w-full h-full object-cover">
                                </div>
                                <div class="author-name text-sm">
                                    James Mitchell
                                </div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors text-sm">
                                Read More
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Article 5 -->
                <div class="article-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="article-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/blog-5.jpg' ); ?>" alt="Planted Tank" class="w-full h-full object-cover">
                    </div>
                    <div class="article-content p-6">
                        <div class="article-meta flex items-center justify-between mb-3">
                            <span class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium">
                                Planted Tanks
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i> June 30, 2025
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-3">
                            <a href="#" class="hover:text-primary transition-colors">
                                Essential CO2 Systems for Thriving Planted Aquariums
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Carbon dioxide supplementation can transform your planted aquarium. This article explores different CO2 systems, from DIY setups to high-end pressurized systems, and how to implement them safely.
                        </p>
                        
                        <div class="article-footer flex items-center justify-between">
                            <div class="article-author flex items-center">
                                <div class="author-image w-8 h-8 rounded-full overflow-hidden mr-2">
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/author-2.jpg' ); ?>" alt="Sarah Chen" class="w-full h-full object-cover">
                                </div>
                                <div class="author-name text-sm">
                                    Sarah Chen
                                </div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors text-sm">
                                Read More
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Article 6 -->
                <div class="article-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="article-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/blog-6.jpg' ); ?>" alt="Fish Health" class="w-full h-full object-cover">
                    </div>
                    <div class="article-content p-6">
                        <div class="article-meta flex items-center justify-between mb-3">
                            <span class="inline-block px-3 py-1 bg-light-dark dark:bg-dark-light rounded-full text-sm font-medium">
                                Fish Care
                            </span>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="far fa-calendar-alt mr-1"></i> June 22, 2025
                            </span>
                        </div>
                        
                        <h3 class="text-xl font-bold mb-3">
                            <a href="#" class="hover:text-primary transition-colors">
                                Identifying and Treating Common Fish Diseases
                            </a>
                        </h3>
                        
                        <p class="text-gray-600 dark:text-gray-400 mb-4">
                            Early detection is crucial when dealing with fish health issues. Learn to recognize symptoms of common diseases, understand their causes, and implement effective treatment and prevention strategies.
                        </p>
                        
                        <div class="article-footer flex items-center justify-between">
                            <div class="article-author flex items-center">
                                <div class="author-image w-8 h-8 rounded-full overflow-hidden mr-2">
                                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/author-1.jpg' ); ?>" alt="Dr. Michael Rodriguez" class="w-full h-full object-cover">
                                </div>
                                <div class="author-name text-sm">
                                    Dr. Michael Rodriguez
                                </div>
                            </div>
                            
                            <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors text-sm">
                                Read More
                                <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="pagination flex justify-center mt-12">
                <div class="flex items-center space-x-1">
                    <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a href="#" class="px-4 py-2 bg-primary text-white rounded-md">1</a>
                    <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">2</a>
                    <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">3</a>
                    <span class="px-4 py-2">...</span>
                    <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">8</a>
                    <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="blog-newsletter py-12 md:py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-4">
                    Subscribe to Our Newsletter
                </h2>
                
                <p class="mb-6 text-white/80">
                    Stay updated with our latest articles, expert tips, and exclusive offers delivered straight to your inbox.
                </p>
                
                <div class="newsletter-form">
                    <form action="#" method="post" class="flex flex-col md:flex-row gap-4 max-w-xl mx-auto">
                        <input type="email" name="email" placeholder="<?php esc_attr_e('Your Email Address', 'aqualuxe'); ?>" class="flex-grow px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
                        <button type="submit" class="px-6 py-3 bg-accent hover:bg-accent-dark text-dark font-medium rounded-lg transition-colors">
                            <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>