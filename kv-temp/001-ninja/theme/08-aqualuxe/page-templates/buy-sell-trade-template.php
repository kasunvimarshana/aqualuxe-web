<?php
/**
 * Template Name: Buy/Sell/Trade Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="buy-sell-trade-hero bg-gradient-to-r from-teal-600 to-blue-500 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center">
                <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6"><?php echo get_the_title(); ?></h1>
                    <?php if (get_field('hero_subtitle')) : ?>
                        <p class="text-xl md:text-2xl mb-8 opacity-90"><?php echo get_field('hero_subtitle'); ?></p>
                    <?php else : ?>
                        <p class="text-xl md:text-2xl mb-8 opacity-90">Buy, sell, or trade ornamental fish with our community of enthusiasts and collectors</p>
                    <?php endif; ?>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="#buy-section" class="inline-block bg-white text-teal-600 hover:bg-teal-50 font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                            Buy Fish
                        </a>
                        <a href="#sell-section" class="inline-block bg-transparent border-2 border-white text-white hover:bg-white hover:text-teal-600 font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                            Sell/Trade Fish
                        </a>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="rounded-lg overflow-hidden shadow-2xl">
                            <?php the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
                        </div>
                    <?php else : ?>
                        <div class="rounded-lg overflow-hidden shadow-2xl bg-teal-700 p-6">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/buy-sell-trade-hero.jpg" alt="Buy Sell Trade Fish" class="w-full h-auto rounded">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="how-it-works py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">How It Works</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Step 1 -->
                <div class="step-card text-center">
                    <div class="step-icon mx-auto mb-6 w-20 h-20 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Create an Account</h3>
                    <p class="text-gray-700 dark:text-gray-300">Sign up for a free account to access our buy/sell/trade marketplace. Verify your identity to build trust with other members.</p>
                </div>
                
                <!-- Step 2 -->
                <div class="step-card text-center">
                    <div class="step-icon mx-auto mb-6 w-20 h-20 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Browse or List</h3>
                    <p class="text-gray-700 dark:text-gray-300">Browse available fish for purchase or list your own fish for sale or trade. Include high-quality photos and detailed descriptions.</p>
                </div>
                
                <!-- Step 3 -->
                <div class="step-card text-center">
                    <div class="step-icon mx-auto mb-6 w-20 h-20 rounded-full bg-teal-100 dark:bg-teal-900 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Complete Transaction</h3>
                    <p class="text-gray-700 dark:text-gray-300">Negotiate terms, arrange shipping or pickup, and complete your transaction securely through our platform's payment system.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Buy Section -->
    <section id="buy-section" class="buy-section py-16 bg-gray-100 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center justify-between mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Available Fish</h2>
                
                <div class="flex flex-wrap gap-4 mt-4 md:mt-0">
                    <div class="relative">
                        <select class="appearance-none bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 py-2 px-4 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option>All Categories</option>
                            <option>Tropical Freshwater</option>
                            <option>Marine Fish</option>
                            <option>Koi & Goldfish</option>
                            <option>Rare & Exotic</option>
                            <option>Breeding Pairs</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-200">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <select class="appearance-none bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 py-2 px-4 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500">
                            <option>Sort By: Latest</option>
                            <option>Price: Low to High</option>
                            <option>Price: High to Low</option>
                            <option>Popularity</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-200">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Fish Listing 1 -->
                <div class="fish-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fish-listing-1.jpg" alt="Blue Diamond Discus" class="w-full h-48 object-cover">
                        <span class="absolute top-2 right-2 bg-teal-500 text-white text-xs font-bold uppercase py-1 px-2 rounded">For Sale</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Blue Diamond Discus</h3>
                            <span class="text-teal-600 dark:text-teal-400 font-bold">$89.99</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Healthy 4" specimen, tank raised with brilliant coloration. Perfect for show tanks.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Listed by: AquaExpert</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Miami, FL</span>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <button class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Contact Seller
                            </button>
                            <button class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Fish Listing 2 -->
                <div class="fish-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fish-listing-2.jpg" alt="Platinum Arowana" class="w-full h-48 object-cover">
                        <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs font-bold uppercase py-1 px-2 rounded">For Trade</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Platinum Arowana</h3>
                            <span class="text-blue-600 dark:text-blue-400 font-bold">Trade Only</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">12" juvenile platinum arowana, CITES certified. Looking to trade for rare plecos or similar value fish.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Listed by: RareFishCollector</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Houston, TX</span>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Offer Trade
                            </button>
                            <button class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Fish Listing 3 -->
                <div class="fish-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fish-listing-3.jpg" alt="Koi Pair" class="w-full h-48 object-cover">
                        <span class="absolute top-2 right-2 bg-teal-500 text-white text-xs font-bold uppercase py-1 px-2 rounded">For Sale</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Kohaku Koi Pair</h3>
                            <span class="text-teal-600 dark:text-teal-400 font-bold">$250.00</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Breeding pair of show-quality Kohaku koi, 15" female and 12" male. Excellent pattern and coloration.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Listed by: KoiMaster</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Portland, OR</span>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <button class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Contact Seller
                            </button>
                            <button class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Fish Listing 4 -->
                <div class="fish-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fish-listing-4.jpg" alt="Clownfish Pair" class="w-full h-48 object-cover">
                        <span class="absolute top-2 right-2 bg-purple-500 text-white text-xs font-bold uppercase py-1 px-2 rounded">Sale/Trade</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Premium Clownfish Pair</h3>
                            <span class="text-teal-600 dark:text-teal-400 font-bold">$120.00</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Captive-bred Picasso Percula Clownfish pair, fully acclimated to tank life. Will consider trades for rare corals.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Listed by: ReefKeeper</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">San Diego, CA</span>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <button class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Contact Seller
                            </button>
                            <button class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Fish Listing 5 -->
                <div class="fish-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fish-listing-5.jpg" alt="Angelfish Group" class="w-full h-48 object-cover">
                        <span class="absolute top-2 right-2 bg-teal-500 text-white text-xs font-bold uppercase py-1 px-2 rounded">For Sale</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Angelfish Group (5)</h3>
                            <span class="text-teal-600 dark:text-teal-400 font-bold">$75.00</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Group of 5 juvenile marble angelfish, tank-raised and eating well. Perfect for community tanks.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Listed by: FreshwaterFan</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Chicago, IL</span>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <button class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Contact Seller
                            </button>
                            <button class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Fish Listing 6 -->
                <div class="fish-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fish-listing-6.jpg" alt="Rare Pleco" class="w-full h-48 object-cover">
                        <span class="absolute top-2 right-2 bg-blue-500 text-white text-xs font-bold uppercase py-1 px-2 rounded">For Trade</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">L046 Zebra Pleco</h3>
                            <span class="text-blue-600 dark:text-blue-400 font-bold">Trade Only</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Rare L046 Zebra Pleco, 3" adult. Looking to trade for other rare plecos or high-end discus.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Listed by: PlecoCollector</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Atlanta, GA</span>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <button class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Offer Trade
                            </button>
                            <button class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Fish Listing 7 -->
                <div class="fish-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fish-listing-7.jpg" alt="Betta Fish" class="w-full h-48 object-cover">
                        <span class="absolute top-2 right-2 bg-teal-500 text-white text-xs font-bold uppercase py-1 px-2 rounded">For Sale</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Show Betta Male</h3>
                            <span class="text-teal-600 dark:text-teal-400 font-bold">$45.00</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">Competition-grade halfmoon betta male with stunning coloration. Excellent genetics from champion bloodline.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Listed by: BettaBreeder</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Seattle, WA</span>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <button class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Contact Seller
                            </button>
                            <button class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Fish Listing 8 -->
                <div class="fish-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="relative">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/fish-listing-8.jpg" alt="Fancy Guppies" class="w-full h-48 object-cover">
                        <span class="absolute top-2 right-2 bg-teal-500 text-white text-xs font-bold uppercase py-1 px-2 rounded">For Sale</span>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Fancy Guppy Trio</h3>
                            <span class="text-teal-600 dark:text-teal-400 font-bold">$35.00</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-300 mb-3">1 male, 2 female Moscow Blue guppies. Home-bred with excellent finnage and coloration.</p>
                        <div class="flex justify-between items-center">
                            <span class="text-xs text-gray-500 dark:text-gray-400">Listed by: GuppyGuru</span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">Denver, CO</span>
                        </div>
                        <div class="mt-4 flex justify-between">
                            <button class="bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Contact Seller
                            </button>
                            <button class="bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-700 dark:text-gray-200 text-sm font-medium py-2 px-4 rounded transition-colors duration-300">
                                Save
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <button class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-md">
                    Load More Listings
                </button>
            </div>
        </div>
    </section>

    <!-- Sell/Trade Section -->
    <section id="sell-section" class="sell-section py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-4 text-gray-900 dark:text-white">List Your Fish</h2>
                <p class="text-center text-gray-700 dark:text-gray-300 mb-12 max-w-2xl mx-auto">Have fish to sell or trade? Create a listing to connect with buyers and other enthusiasts in our community.</p>
                
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <form class="listing-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Listing Type -->
                            <div class="col-span-2">
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">Listing Type *</label>
                                <div class="flex flex-wrap gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="listing_type" value="sale" class="h-5 w-5 text-teal-600" checked>
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">For Sale</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="listing_type" value="trade" class="h-5 w-5 text-teal-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">For Trade</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio" name="listing_type" value="both" class="h-5 w-5 text-teal-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Sale or Trade</span>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Fish Information -->
                            <div class="col-span-2">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Fish Information</h3>
                            </div>
                            
                            <div>
                                <label for="fish_name" class="block text-gray-700 dark:text-gray-300 mb-2">Fish Name/Species *</label>
                                <input type="text" id="fish_name" name="fish_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="fish_category" class="block text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                                <select id="fish_category" name="fish_category" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Category</option>
                                    <option value="tropical_freshwater">Tropical Freshwater</option>
                                    <option value="marine_fish">Marine Fish</option>
                                    <option value="koi_goldfish">Koi & Goldfish</option>
                                    <option value="rare_exotic">Rare & Exotic</option>
                                    <option value="breeding_pairs">Breeding Pairs</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="quantity" class="block text-gray-700 dark:text-gray-300 mb-2">Quantity *</label>
                                <input type="number" id="quantity" name="quantity" min="1" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="size" class="block text-gray-700 dark:text-gray-300 mb-2">Size (inches) *</label>
                                <input type="text" id="size" name="size" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div class="price-field">
                                <label for="price" class="block text-gray-700 dark:text-gray-300 mb-2">Price ($) *</label>
                                <input type="number" id="price" name="price" min="0" step="0.01" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div class="trade-field hidden">
                                <label for="trade_for" class="block text-gray-700 dark:text-gray-300 mb-2">Trade For *</label>
                                <input type="text" id="trade_for" name="trade_for" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div class="col-span-2">
                                <label for="description" class="block text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                                <textarea id="description" name="description" rows="4" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white"></textarea>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Include details about age, coloration, diet, breeding history, and any other relevant information.</p>
                            </div>
                            
                            <div class="col-span-2">
                                <label for="photos" class="block text-gray-700 dark:text-gray-300 mb-2">Photos *</label>
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                                    <input type="file" id="photos" name="photos" multiple accept="image/*" class="hidden">
                                    <label for="photos" class="cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Click to upload photos or drag and drop</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">Upload up to 5 high-quality photos (Max 5MB each)</p>
                                    </label>
                                </div>
                            </div>
                            
                            <!-- Location Information -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Location & Shipping</h3>
                            </div>
                            
                            <div>
                                <label for="city" class="block text-gray-700 dark:text-gray-300 mb-2">City *</label>
                                <input type="text" id="city" name="city" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="state" class="block text-gray-700 dark:text-gray-300 mb-2">State *</label>
                                <input type="text" id="state" name="state" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">Delivery Options *</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="delivery_options[]" value="local_pickup" class="h-5 w-5 text-teal-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Local Pickup</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="delivery_options[]" value="shipping" class="h-5 w-5 text-teal-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Shipping Available</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="shipping-details col-span-2 hidden">
                                <label for="shipping_info" class="block text-gray-700 dark:text-gray-300 mb-2">Shipping Information</label>
                                <textarea id="shipping_info" name="shipping_info" rows="2" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-teal-500 dark:bg-gray-700 dark:text-white"></textarea>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Include shipping costs, methods, and any restrictions.</p>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="flex items-start">
                                    <input type="checkbox" name="terms" required class="h-5 w-5 text-teal-600 mt-1">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300 text-sm">
                                        I agree to the <a href="#" class="text-teal-600 hover:underline">Terms and Conditions</a> and acknowledge that my listing will be reviewed before being published. I confirm that I am legally allowed to sell/trade these fish. *
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="text-center mt-8">
                            <button type="submit" class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-md">
                                Submit Listing
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Safety Tips -->
    <section class="safety-tips py-16 bg-gray-100 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Safety Tips for Buyers & Sellers</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Buyer Tips -->
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        For Buyers
                    </h3>
                    <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Always check seller ratings and reviews before making a purchase.</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Request additional photos or videos if the listing images aren't clear enough.</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Ask about the fish's history, including diet, tank conditions, and any health issues.</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>For expensive purchases, consider using our secure payment system with buyer protection.</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>If meeting in person, choose a public location and bring someone with you if possible.</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Ensure you have proper acclimation equipment ready before the fish arrives.</span>
                        </li>
                    </ul>
                </div>
                
                <!-- Seller Tips -->
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        For Sellers
                    </h3>
                    <ul class="space-y-3 text-gray-700 dark:text-gray-300">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Take clear, well-lit photos that accurately represent the fish's appearance.</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Provide detailed descriptions including age, size, diet, and any special care requirements.</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Be honest about any health issues or special needs the fish may have.</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Use proper shipping methods with insulation, heat/cold packs as needed, and oxygen bags.</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Respond promptly to buyer questions and provide additional information when requested.</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-teal-600 dark:text-teal-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Include acclimation instructions with all shipped fish to ensure their safe transition.</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-10 text-center">
                <p class="text-gray-700 dark:text-gray-300">
                    <strong>Remember:</strong> Always ensure you comply with local laws and regulations regarding the buying, selling, and shipping of aquatic life.
                </p>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle trade fields based on listing type selection
        const listingTypeRadios = document.querySelectorAll('input[name="listing_type"]');
        const priceField = document.querySelector('.price-field');
        const tradeField = document.querySelector('.trade-field');
        
        listingTypeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'sale') {
                    priceField.classList.remove('hidden');
                    tradeField.classList.add('hidden');
                    document.getElementById('trade_for').removeAttribute('required');
                    document.getElementById('price').setAttribute('required', 'required');
                } else if (this.value === 'trade') {
                    priceField.classList.add('hidden');
                    tradeField.classList.remove('hidden');
                    document.getElementById('price').removeAttribute('required');
                    document.getElementById('trade_for').setAttribute('required', 'required');
                } else {
                    priceField.classList.remove('hidden');
                    tradeField.classList.remove('hidden');
                    document.getElementById('price').setAttribute('required', 'required');
                    document.getElementById('trade_for').setAttribute('required', 'required');
                }
            });
        });
        
        // Toggle shipping details based on delivery options
        const shippingCheckbox = document.querySelector('input[value="shipping"]');
        const shippingDetails = document.querySelector('.shipping-details');
        
        if (shippingCheckbox && shippingDetails) {
            shippingCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    shippingDetails.classList.remove('hidden');
                } else {
                    shippingDetails.classList.add('hidden');
                }
            });
        }
    });
</script>

<?php
get_footer();