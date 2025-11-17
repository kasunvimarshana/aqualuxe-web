<?php
/**
 * Template part for displaying shop page content
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<div class="shop-page-content">
    <!-- Hero Section -->
    <section class="shop-hero bg-primary text-white py-16 md:py-24 mb-12">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-serif font-bold mb-6">Premium Aquarium Products</h1>
                <p class="text-xl md:text-2xl opacity-90 mb-8">Quality Equipment, Supplies, and Accessories for Your Aquatic Environment</p>
                <div class="flex justify-center">
                    <div class="w-24 h-1 bg-accent"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- Shop Categories Section -->
    <section class="shop-categories py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Category 1 -->
                <a href="#" class="category-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="category-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/category-aquariums.jpg' ); ?>" alt="Aquariums" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-6 text-white">
                                <h3 class="text-xl font-bold mb-1">Aquariums</h3>
                                <p class="text-sm opacity-90">Premium tanks for every space</p>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Category 2 -->
                <a href="#" class="category-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="category-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/category-equipment.jpg' ); ?>" alt="Equipment" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-6 text-white">
                                <h3 class="text-xl font-bold mb-1">Equipment</h3>
                                <p class="text-sm opacity-90">Filters, pumps, heaters & more</p>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Category 3 -->
                <a href="#" class="category-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="category-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/category-lighting.jpg' ); ?>" alt="Lighting" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-6 text-white">
                                <h3 class="text-xl font-bold mb-1">Lighting</h3>
                                <p class="text-sm opacity-90">LED systems for optimal growth</p>
                            </div>
                        </div>
                    </div>
                </a>
                
                <!-- Category 4 -->
                <a href="#" class="category-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="category-image aspect-w-16 aspect-h-9">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/category-decor.jpg' ); ?>" alt="Décor" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
                            <div class="p-6 text-white">
                                <h3 class="text-xl font-bold mb-1">Décor</h3>
                                <p class="text-sm opacity-90">Substrates, rocks & ornaments</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </section>

    <!-- Featured Products Section -->
    <section class="featured-products py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="section-header flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-serif font-bold">
                    Featured Products
                </h2>
                <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                    View All
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Product 1 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-primary text-white text-xs font-bold px-2 py-1 rounded">New</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-1.jpg' ); ?>" alt="AquaLuxe Crystal Series 60 Gallon" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Aquariums
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Crystal Series 60 Gallon
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(24)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$599.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
                
                <!-- Product 2 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-accent text-dark text-xs font-bold px-2 py-1 rounded">Sale</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-2.jpg' ); ?>" alt="AquaLuxe Pro Filter 3000" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Filtration
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Pro Filter 3000
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(42)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$149.99</div>
                            <div class="product-price-old text-sm text-gray-500 dark:text-gray-400 line-through">$199.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
                
                <!-- Product 3 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-3.jpg' ); ?>" alt="AquaLuxe Spectrum LED Light" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Lighting
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Spectrum LED Light
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(18)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$129.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
                
                <!-- Product 4 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded">Best Seller</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-4.jpg' ); ?>" alt="AquaLuxe Natural Rock Formation" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Décor
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Natural Rock Formation
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(56)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$79.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- New Arrivals Section -->
    <section class="new-arrivals py-8 md:py-12 bg-light-dark dark:bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="section-header flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-serif font-bold">
                    New Arrivals
                </h2>
                <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                    View All
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Product 5 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-primary text-white text-xs font-bold px-2 py-1 rounded">New</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-5.jpg' ); ?>" alt="AquaLuxe Smart Controller" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Electronics
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Smart Controller
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(12)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$249.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
                
                <!-- Product 6 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-primary text-white text-xs font-bold px-2 py-1 rounded">New</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-6.jpg' ); ?>" alt="AquaLuxe CO2 System" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Plant Care
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe CO2 System
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="far fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(8)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$189.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
                
                <!-- Product 7 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-primary text-white text-xs font-bold px-2 py-1 rounded">New</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-7.jpg' ); ?>" alt="AquaLuxe Protein Skimmer" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Saltwater
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Protein Skimmer
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(15)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$219.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
                
                <!-- Product 8 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-primary text-white text-xs font-bold px-2 py-1 rounded">New</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-8.jpg' ); ?>" alt="AquaLuxe Water Test Kit" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Maintenance
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Water Test Kit
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(27)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$49.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Best Sellers Section -->
    <section class="best-sellers py-8 md:py-12">
        <div class="container mx-auto px-4">
            <div class="section-header flex justify-between items-center mb-8">
                <h2 class="text-2xl md:text-3xl font-serif font-bold">
                    Best Sellers
                </h2>
                <a href="#" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
                    View All
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Product 9 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded">Best Seller</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-9.jpg' ); ?>" alt="AquaLuxe Nano Cube 10 Gallon" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Aquariums
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Nano Cube 10 Gallon
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(89)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$149.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
                
                <!-- Product 10 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded">Best Seller</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-10.jpg' ); ?>" alt="AquaLuxe Plant Substrate" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Planted Tanks
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Plant Substrate
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(76)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$39.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
                
                <!-- Product 11 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded">Best Seller</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-11.jpg' ); ?>" alt="AquaLuxe Aquarium Heater" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Equipment
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Aquarium Heater
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(103)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$34.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
                
                <!-- Product 12 -->
                <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                    <div class="product-badges absolute top-4 left-4 z-10 flex flex-col gap-2">
                        <span class="badge bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded">Best Seller</span>
                    </div>
                    <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-12.jpg' ); ?>" alt="AquaLuxe Water Conditioner" class="w-full h-full object-cover">
                    </a>
                    <div class="product-content p-4">
                        <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                            Water Care
                        </div>
                        <h3 class="product-title text-lg font-bold mb-2">
                            <a href="#" class="hover:text-primary transition-colors">
                                AquaLuxe Water Conditioner
                            </a>
                        </h3>
                        <div class="product-rating flex items-center mb-2">
                            <div class="stars text-accent">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">(142)</span>
                        </div>
                        <div class="product-price-wrapper flex items-center justify-between mb-3">
                            <div class="product-price font-bold text-lg">$19.99</div>
                        </div>
                        <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                            Add to Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Collection Section -->
    <section class="featured-collection py-12 md:py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="featured-collection-content">
                    <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                        Premium Reef Collection
                    </h2>
                    
                    <p class="text-lg opacity-90 mb-6">
                        Discover our exclusive collection of premium reef aquarium products, designed to create and maintain a thriving marine ecosystem. From state-of-the-art filtration to specialized lighting and carefully selected live rock, our reef collection represents the pinnacle of aquarium technology.
                    </p>
                    
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-accent mt-1 mr-3"></i>
                            <span>Professional-grade equipment for optimal reef health</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-accent mt-1 mr-3"></i>
                            <span>Specialized lighting systems for coral growth</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-accent mt-1 mr-3"></i>
                            <span>Advanced filtration for pristine water quality</span>
                        </li>
                        <li class="flex items-start">
                            <i class="fas fa-check-circle text-accent mt-1 mr-3"></i>
                            <span>Carefully selected live rock and substrate</span>
                        </li>
                    </ul>
                    
                    <a href="#" class="inline-block px-6 py-3 bg-white text-primary hover:bg-accent hover:text-dark font-medium rounded-lg transition-colors">
                        Explore Collection
                    </a>
                </div>
                
                <div class="featured-collection-image">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/reef-collection.jpg' ); ?>" alt="Premium Reef Collection" class="rounded-lg shadow-lg w-full">
                </div>
            </div>
        </div>
    </section>

    <!-- Shop by Brand Section -->
    <section class="shop-by-brand py-12 md:py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    Shop by Brand
                </h2>
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    We partner with the most trusted names in the aquarium industry to bring you quality products you can rely on.
                </p>
            </div>
            
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                <!-- Brand 1 -->
                <a href="#" class="brand-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-4 flex items-center justify-center transition-transform duration-300 hover:-translate-y-1">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand-1.png' ); ?>" alt="AquaLuxe" class="max-h-16">
                </a>
                
                <!-- Brand 2 -->
                <a href="#" class="brand-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-4 flex items-center justify-center transition-transform duration-300 hover:-translate-y-1">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand-2.png' ); ?>" alt="ReefMaster" class="max-h-16">
                </a>
                
                <!-- Brand 3 -->
                <a href="#" class="brand-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-4 flex items-center justify-center transition-transform duration-300 hover:-translate-y-1">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand-3.png' ); ?>" alt="AquaTech" class="max-h-16">
                </a>
                
                <!-- Brand 4 -->
                <a href="#" class="brand-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-4 flex items-center justify-center transition-transform duration-300 hover:-translate-y-1">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand-4.png' ); ?>" alt="MarineLife" class="max-h-16">
                </a>
                
                <!-- Brand 5 -->
                <a href="#" class="brand-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-4 flex items-center justify-center transition-transform duration-300 hover:-translate-y-1">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand-5.png' ); ?>" alt="PlantGrow" class="max-h-16">
                </a>
                
                <!-- Brand 6 -->
                <a href="#" class="brand-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-4 flex items-center justify-center transition-transform duration-300 hover:-translate-y-1">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/brand-6.png' ); ?>" alt="AquaFilter" class="max-h-16">
                </a>
            </div>
        </div>
    </section>

    <!-- Customer Reviews Section -->
    <section class="customer-reviews py-12 md:py-16 bg-light-dark dark:bg-dark-light">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        Customer Reviews
                    </span>
                </div>
                
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-6">
                    What Our Customers Say
                </h2>
                
                <p class="max-w-3xl mx-auto text-gray-600 dark:text-gray-400">
                    Read reviews from aquarium enthusiasts who have experienced the AquaLuxe difference.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Review 1 -->
                <div class="review-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                    <div class="review-rating text-accent mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="review-content italic mb-6 text-gray-600 dark:text-gray-400">
                        "The AquaLuxe Crystal Series aquarium exceeded my expectations. The clarity is amazing, and the built-in filtration system is both efficient and quiet. Setup was straightforward, and customer support was excellent when I had questions."
                    </div>
                    <div class="review-product flex items-center mb-4">
                        <div class="review-product-image w-12 h-12 rounded overflow-hidden mr-3">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-1.jpg' ); ?>" alt="AquaLuxe Crystal Series" class="w-full h-full object-cover">
                        </div>
                        <div class="review-product-info">
                            <div class="review-product-name text-sm font-medium">AquaLuxe Crystal Series 60 Gallon</div>
                            <div class="review-product-price text-xs text-gray-500 dark:text-gray-400">$599.99</div>
                        </div>
                    </div>
                    <div class="review-author flex items-center">
                        <div class="review-author-image w-10 h-10 rounded-full overflow-hidden mr-3">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/customer-1.jpg' ); ?>" alt="Michael T." class="w-full h-full object-cover">
                        </div>
                        <div class="review-author-info">
                            <div class="review-author-name font-medium">Michael T.</div>
                            <div class="review-date text-xs text-gray-500 dark:text-gray-400">Verified Purchase - July 28, 2025</div>
                        </div>
                    </div>
                </div>
                
                <!-- Review 2 -->
                <div class="review-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                    <div class="review-rating text-accent mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="review-content italic mb-6 text-gray-600 dark:text-gray-400">
                        "I've been using the AquaLuxe Spectrum LED Light for my planted tank for three months now, and the growth has been phenomenal. The customizable spectrum settings allow me to create the perfect lighting for different plant species. Worth every penny!"
                    </div>
                    <div class="review-product flex items-center mb-4">
                        <div class="review-product-image w-12 h-12 rounded overflow-hidden mr-3">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-3.jpg' ); ?>" alt="AquaLuxe Spectrum LED Light" class="w-full h-full object-cover">
                        </div>
                        <div class="review-product-info">
                            <div class="review-product-name text-sm font-medium">AquaLuxe Spectrum LED Light</div>
                            <div class="review-product-price text-xs text-gray-500 dark:text-gray-400">$129.99</div>
                        </div>
                    </div>
                    <div class="review-author flex items-center">
                        <div class="review-author-image w-10 h-10 rounded-full overflow-hidden mr-3">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/customer-2.jpg' ); ?>" alt="Jennifer L." class="w-full h-full object-cover">
                        </div>
                        <div class="review-author-info">
                            <div class="review-author-name font-medium">Jennifer L.</div>
                            <div class="review-date text-xs text-gray-500 dark:text-gray-400">Verified Purchase - July 15, 2025</div>
                        </div>
                    </div>
                </div>
                
                <!-- Review 3 -->
                <div class="review-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 transition-transform duration-300 hover:-translate-y-1">
                    <div class="review-rating text-accent mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <div class="review-content italic mb-6 text-gray-600 dark:text-gray-400">
                        "The AquaLuxe Pro Filter 3000 transformed my tank's water quality. It's incredibly efficient at removing waste while maintaining beneficial bacteria. Installation was simple, and maintenance is a breeze. The only reason for 4.5 stars is that it's slightly louder than expected."
                    </div>
                    <div class="review-product flex items-center mb-4">
                        <div class="review-product-image w-12 h-12 rounded overflow-hidden mr-3">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/product-2.jpg' ); ?>" alt="AquaLuxe Pro Filter 3000" class="w-full h-full object-cover">
                        </div>
                        <div class="review-product-info">
                            <div class="review-product-name text-sm font-medium">AquaLuxe Pro Filter 3000</div>
                            <div class="review-product-price text-xs text-gray-500 dark:text-gray-400">$149.99</div>
                        </div>
                    </div>
                    <div class="review-author flex items-center">
                        <div class="review-author-image w-10 h-10 rounded-full overflow-hidden mr-3">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/customer-3.jpg' ); ?>" alt="Robert K." class="w-full h-full object-cover">
                        </div>
                        <div class="review-author-info">
                            <div class="review-author-name font-medium">Robert K.</div>
                            <div class="review-date text-xs text-gray-500 dark:text-gray-400">Verified Purchase - June 30, 2025</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-8">
                <a href="#" class="inline-block px-6 py-3 bg-primary hover:bg-primary-dark text-white rounded-lg font-medium transition-colors">
                    Read More Reviews
                </a>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="shop-newsletter py-12 md:py-16 bg-primary text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-4">
                    Subscribe for Exclusive Offers
                </h2>
                
                <p class="mb-6 text-white/80">
                    Join our newsletter to receive updates on new products, special promotions, and expert aquarium care tips.
                </p>
                
                <div class="newsletter-form">
                    <form action="#" method="post" class="flex flex-col md:flex-row gap-4 max-w-xl mx-auto">
                        <input type="email" name="email" placeholder="<?php esc_attr_e('Your Email Address', 'aqualuxe'); ?>" class="flex-grow px-4 py-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-accent" required>
                        <button type="submit" class="px-6 py-3 bg-accent hover:bg-accent-dark text-dark font-medium rounded-lg transition-colors">
                            <?php esc_html_e('Subscribe', 'aqualuxe'); ?>
                        </button>
                    </form>
                </div>
                
                <p class="text-sm mt-4 text-white/70">
                    By subscribing, you agree to receive marketing emails from AquaLuxe. You can unsubscribe at any time.
                </p>
            </div>
        </div>
    </section>
</div>