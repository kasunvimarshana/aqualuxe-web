<?php
/**
 * Template Name: Events Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="events-hero bg-gradient-to-r from-purple-600 to-indigo-600 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center">
                <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6"><?php echo get_the_title(); ?></h1>
                    <?php if (get_field('hero_subtitle')) : ?>
                        <p class="text-xl md:text-2xl mb-8 opacity-90"><?php echo get_field('hero_subtitle'); ?></p>
                    <?php else : ?>
                        <p class="text-xl md:text-2xl mb-8 opacity-90">Join us for exciting aquatic events, workshops, and exhibitions throughout the year</p>
                    <?php endif; ?>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="#upcoming-events" class="inline-block bg-white text-purple-600 hover:bg-purple-50 font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                            Upcoming Events
                        </a>
                        <a href="#submit-event" class="inline-block bg-transparent border-2 border-white text-white hover:bg-white hover:text-purple-600 font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                            Submit Event
                        </a>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="rounded-lg overflow-hidden shadow-2xl">
                            <?php the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
                        </div>
                    <?php else : ?>
                        <div class="rounded-lg overflow-hidden shadow-2xl bg-purple-700 p-6">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/events-hero.jpg" alt="Aquatic Events" class="w-full h-auto rounded">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Event -->
    <section class="featured-event py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Featured Event</h2>
            
            <div class="bg-gradient-to-r from-purple-50 to-indigo-50 dark:from-purple-900 dark:to-indigo-900 rounded-xl overflow-hidden shadow-lg">
                <div class="flex flex-wrap">
                    <div class="w-full lg:w-1/2">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/featured-event.jpg" alt="International Aquascaping Competition" class="w-full h-full object-cover">
                    </div>
                    <div class="w-full lg:w-1/2 p-8 lg:p-12">
                        <div class="flex items-center mb-4">
                            <span class="bg-purple-600 text-white text-xs font-bold uppercase py-1 px-2 rounded">Featured</span>
                            <span class="ml-3 text-gray-600 dark:text-gray-400 text-sm">July 15-17, 2025</span>
                        </div>
                        <h3 class="text-2xl lg:text-3xl font-bold mb-4 text-gray-900 dark:text-white">International Aquascaping Competition</h3>
                        <p class="text-gray-700 dark:text-gray-300 mb-6">Join us for the premier aquascaping event of the year, featuring world-renowned aquascape artists, live demonstrations, and competitions with over $50,000 in prizes. This three-day event includes workshops, networking opportunities, and exclusive product showcases from leading brands in the aquarium industry.</p>
                        <div class="flex items-center mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-600 dark:text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">Aquatic Convention Center, Orlando, FL</span>
                        </div>
                        <div class="flex flex-wrap gap-4">
                            <a href="#" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-md">
                                Register Now
                            </a>
                            <a href="#" class="inline-block bg-transparent border border-purple-600 text-purple-600 dark:text-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900 font-bold py-3 px-6 rounded-lg transition duration-300">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Event Categories -->
    <section class="event-categories py-16 bg-gray-100 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Event Categories</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Category 1 -->
                <div class="category-card bg-white dark:bg-gray-700 rounded-lg p-6 text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="inline-block p-4 rounded-full bg-purple-100 dark:bg-purple-900 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Exhibitions</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">Large-scale aquarium exhibitions featuring rare and exotic species from around the world.</p>
                    <a href="#" class="text-purple-600 dark:text-purple-400 font-medium hover:underline">View Events</a>
                </div>
                
                <!-- Category 2 -->
                <div class="category-card bg-white dark:bg-gray-700 rounded-lg p-6 text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="inline-block p-4 rounded-full bg-purple-100 dark:bg-purple-900 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Workshops</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">Hands-on learning experiences covering aquascaping, breeding, and aquarium maintenance.</p>
                    <a href="#" class="text-purple-600 dark:text-purple-400 font-medium hover:underline">View Events</a>
                </div>
                
                <!-- Category 3 -->
                <div class="category-card bg-white dark:bg-gray-700 rounded-lg p-6 text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="inline-block p-4 rounded-full bg-purple-100 dark:bg-purple-900 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Competitions</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">Aquascaping contests, breeding competitions, and fish shows with expert judging.</p>
                    <a href="#" class="text-purple-600 dark:text-purple-400 font-medium hover:underline">View Events</a>
                </div>
                
                <!-- Category 4 -->
                <div class="category-card bg-white dark:bg-gray-700 rounded-lg p-6 text-center shadow-md hover:shadow-lg transition-shadow duration-300">
                    <div class="inline-block p-4 rounded-full bg-purple-100 dark:bg-purple-900 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Networking</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">Industry meetups, hobbyist gatherings, and social events for aquarium enthusiasts.</p>
                    <a href="#" class="text-purple-600 dark:text-purple-400 font-medium hover:underline">View Events</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Upcoming Events -->
    <section id="upcoming-events" class="upcoming-events py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center justify-between mb-12">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Upcoming Events</h2>
                
                <div class="flex flex-wrap gap-4 mt-4 md:mt-0">
                    <div class="relative">
                        <select class="appearance-none bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 py-2 px-4 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option>All Categories</option>
                            <option>Exhibitions</option>
                            <option>Workshops</option>
                            <option>Competitions</option>
                            <option>Networking</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-200">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                    
                    <div class="relative">
                        <select class="appearance-none bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 py-2 px-4 pr-8 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option>All Locations</option>
                            <option>North America</option>
                            <option>Europe</option>
                            <option>Asia</option>
                            <option>Online</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-gray-200">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="space-y-8">
                <!-- Event 1 -->
                <div class="event-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/4 lg:w-1/5 bg-purple-100 dark:bg-purple-900 p-6 flex flex-col items-center justify-center text-center">
                            <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">15</span>
                            <span class="text-lg font-medium text-purple-600 dark:text-purple-400">JUL</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400 mt-2">2025</span>
                        </div>
                        <div class="md:w-2/4 lg:w-3/5 p-6">
                            <div class="flex items-center mb-2">
                                <span class="bg-purple-600 text-white text-xs font-bold uppercase py-1 px-2 rounded">Competition</span>
                                <span class="ml-3 text-gray-600 dark:text-gray-400 text-sm">3 Days</span>
                            </div>
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">International Aquascaping Competition</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">Join us for the premier aquascaping event of the year, featuring world-renowned aquascape artists, live demonstrations, and competitions.</p>
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Aquatic Convention Center, Orlando, FL</span>
                            </div>
                        </div>
                        <div class="md:w-1/4 lg:w-1/5 p-6 flex flex-col justify-center items-center md:items-end">
                            <div class="text-center md:text-right mb-4">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">$149</span>
                                <span class="text-gray-600 dark:text-gray-400 block text-sm">Early Bird</span>
                            </div>
                            <a href="#" class="w-full md:w-auto inline-block bg-purple-600 hover:bg-purple-700 text-white text-center font-medium py-2 px-6 rounded-lg transition duration-300">
                                Register
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Event 2 -->
                <div class="event-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/4 lg:w-1/5 bg-purple-100 dark:bg-purple-900 p-6 flex flex-col items-center justify-center text-center">
                            <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">22</span>
                            <span class="text-lg font-medium text-purple-600 dark:text-purple-400">AUG</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400 mt-2">2025</span>
                        </div>
                        <div class="md:w-2/4 lg:w-3/5 p-6">
                            <div class="flex items-center mb-2">
                                <span class="bg-blue-600 text-white text-xs font-bold uppercase py-1 px-2 rounded">Workshop</span>
                                <span class="ml-3 text-gray-600 dark:text-gray-400 text-sm">1 Day</span>
                            </div>
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Advanced Breeding Techniques Workshop</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">Learn advanced breeding techniques for rare and exotic fish species from expert breeders. Includes hands-on demonstrations and take-home materials.</p>
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Aquarium Science Institute, Chicago, IL</span>
                            </div>
                        </div>
                        <div class="md:w-1/4 lg:w-1/5 p-6 flex flex-col justify-center items-center md:items-end">
                            <div class="text-center md:text-right mb-4">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">$89</span>
                                <span class="text-gray-600 dark:text-gray-400 block text-sm">Per Person</span>
                            </div>
                            <a href="#" class="w-full md:w-auto inline-block bg-purple-600 hover:bg-purple-700 text-white text-center font-medium py-2 px-6 rounded-lg transition duration-300">
                                Register
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Event 3 -->
                <div class="event-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/4 lg:w-1/5 bg-purple-100 dark:bg-purple-900 p-6 flex flex-col items-center justify-center text-center">
                            <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">10</span>
                            <span class="text-lg font-medium text-purple-600 dark:text-purple-400">SEP</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400 mt-2">2025</span>
                        </div>
                        <div class="md:w-2/4 lg:w-3/5 p-6">
                            <div class="flex items-center mb-2">
                                <span class="bg-green-600 text-white text-xs font-bold uppercase py-1 px-2 rounded">Exhibition</span>
                                <span class="ml-3 text-gray-600 dark:text-gray-400 text-sm">2 Days</span>
                            </div>
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Rare Species Showcase</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">Explore an extraordinary collection of rare and exotic fish species from around the world. Meet collectors, breeders, and conservation experts.</p>
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Marine Discovery Center, San Diego, CA</span>
                            </div>
                        </div>
                        <div class="md:w-1/4 lg:w-1/5 p-6 flex flex-col justify-center items-center md:items-end">
                            <div class="text-center md:text-right mb-4">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">$35</span>
                                <span class="text-gray-600 dark:text-gray-400 block text-sm">General Admission</span>
                            </div>
                            <a href="#" class="w-full md:w-auto inline-block bg-purple-600 hover:bg-purple-700 text-white text-center font-medium py-2 px-6 rounded-lg transition duration-300">
                                Get Tickets
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Event 4 -->
                <div class="event-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/4 lg:w-1/5 bg-purple-100 dark:bg-purple-900 p-6 flex flex-col items-center justify-center text-center">
                            <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">05</span>
                            <span class="text-lg font-medium text-purple-600 dark:text-purple-400">OCT</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400 mt-2">2025</span>
                        </div>
                        <div class="md:w-2/4 lg:w-3/5 p-6">
                            <div class="flex items-center mb-2">
                                <span class="bg-yellow-600 text-white text-xs font-bold uppercase py-1 px-2 rounded">Networking</span>
                                <span class="ml-3 text-gray-600 dark:text-gray-400 text-sm">1 Day</span>
                            </div>
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Aquarium Industry Summit</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">Connect with industry leaders, retailers, manufacturers, and fellow enthusiasts at this premier networking event for the aquarium industry.</p>
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span>Grand Aquarium Hotel, Miami, FL</span>
                            </div>
                        </div>
                        <div class="md:w-1/4 lg:w-1/5 p-6 flex flex-col justify-center items-center md:items-end">
                            <div class="text-center md:text-right mb-4">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">$199</span>
                                <span class="text-gray-600 dark:text-gray-400 block text-sm">Professional</span>
                            </div>
                            <a href="#" class="w-full md:w-auto inline-block bg-purple-600 hover:bg-purple-700 text-white text-center font-medium py-2 px-6 rounded-lg transition duration-300">
                                Register
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Event 5 -->
                <div class="event-card bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="flex flex-col md:flex-row">
                        <div class="md:w-1/4 lg:w-1/5 bg-purple-100 dark:bg-purple-900 p-6 flex flex-col items-center justify-center text-center">
                            <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">18</span>
                            <span class="text-lg font-medium text-purple-600 dark:text-purple-400">OCT</span>
                            <span class="text-sm text-gray-600 dark:text-gray-400 mt-2">2025</span>
                        </div>
                        <div class="md:w-2/4 lg:w-3/5 p-6">
                            <div class="flex items-center mb-2">
                                <span class="bg-blue-600 text-white text-xs font-bold uppercase py-1 px-2 rounded">Workshop</span>
                                <span class="ml-3 text-gray-600 dark:text-gray-400 text-sm">Online</span>
                            </div>
                            <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Virtual Aquascaping Masterclass</h3>
                            <p class="text-gray-700 dark:text-gray-300 mb-4">Learn aquascaping techniques from world champion aquascapers in this interactive online workshop. Includes live Q&A and personalized feedback.</p>
                            <div class="flex items-center text-gray-600 dark:text-gray-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>Virtual Event (Zoom)</span>
                            </div>
                        </div>
                        <div class="md:w-1/4 lg:w-1/5 p-6 flex flex-col justify-center items-center md:items-end">
                            <div class="text-center md:text-right mb-4">
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">$49</span>
                                <span class="text-gray-600 dark:text-gray-400 block text-sm">Per Person</span>
                            </div>
                            <a href="#" class="w-full md:w-auto inline-block bg-purple-600 hover:bg-purple-700 text-white text-center font-medium py-2 px-6 rounded-lg transition duration-300">
                                Register
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-12 text-center">
                <a href="#" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-md">
                    View All Events
                </a>
            </div>
        </div>
    </section>

    <!-- Event Calendar -->
    <section class="event-calendar py-16 bg-gray-100 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Event Calendar</h2>
            
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow-lg overflow-hidden">
                <div class="p-4 bg-purple-600 text-white flex items-center justify-between">
                    <button class="p-2 hover:bg-purple-700 rounded-full transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                    <h3 class="text-xl font-bold">August 2025</h3>
                    <button class="p-2 hover:bg-purple-700 rounded-full transition duration-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>
                
                <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-600">
                    <div class="p-2 text-center font-medium bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">Sun</div>
                    <div class="p-2 text-center font-medium bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">Mon</div>
                    <div class="p-2 text-center font-medium bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">Tue</div>
                    <div class="p-2 text-center font-medium bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">Wed</div>
                    <div class="p-2 text-center font-medium bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">Thu</div>
                    <div class="p-2 text-center font-medium bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">Fri</div>
                    <div class="p-2 text-center font-medium bg-gray-50 dark:bg-gray-800 text-gray-700 dark:text-gray-300">Sat</div>
                </div>
                
                <div class="grid grid-cols-7 gap-px bg-gray-200 dark:bg-gray-600">
                    <!-- Previous month days -->
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-400 dark:text-gray-500">28</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-400 dark:text-gray-500">29</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-400 dark:text-gray-500">30</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-400 dark:text-gray-500">31</div>
                    
                    <!-- Current month days -->
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">1</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">2</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">3</div>
                    
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">4</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">5</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">6</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">7</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">8</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">9</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">10</div>
                    
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">11</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">12</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">13</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">14</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">15</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">16</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">17</div>
                    
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">18</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">19</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">20</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">21</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 relative">
                        22
                        <div class="absolute bottom-1 left-1 right-1">
                            <div class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs p-1 rounded mb-1 truncate">
                                Workshop
                            </div>
                        </div>
                    </div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">23</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">24</div>
                    
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">25</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">26</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">27</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">28</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">29</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">30</div>
                    <div class="p-2 h-32 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">31</div>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <p class="text-gray-700 dark:text-gray-300">Click on any event in the calendar to view details and register.</p>
            </div>
        </div>
    </section>

    <!-- Submit Event -->
    <section id="submit-event" class="submit-event py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-4 text-gray-900 dark:text-white">Submit Your Event</h2>
                <p class="text-center text-gray-700 dark:text-gray-300 mb-12 max-w-2xl mx-auto">Have an aquarium-related event you'd like to share with our community? Submit your event details below for review and potential inclusion in our calendar.</p>
                
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <form class="event-submission-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Event Information -->
                            <div class="col-span-2">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Event Information</h3>
                            </div>
                            
                            <div class="col-span-2">
                                <label for="event_name" class="block text-gray-700 dark:text-gray-300 mb-2">Event Name *</label>
                                <input type="text" id="event_name" name="event_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="event_category" class="block text-gray-700 dark:text-gray-300 mb-2">Event Category *</label>
                                <select id="event_category" name="event_category" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Category</option>
                                    <option value="exhibition">Exhibition</option>
                                    <option value="workshop">Workshop</option>
                                    <option value="competition">Competition</option>
                                    <option value="networking">Networking</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="event_type" class="block text-gray-700 dark:text-gray-300 mb-2">Event Type *</label>
                                <select id="event_type" name="event_type" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Type</option>
                                    <option value="in_person">In-Person</option>
                                    <option value="virtual">Virtual</option>
                                    <option value="hybrid">Hybrid (In-Person & Virtual)</option>
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <label for="event_description" class="block text-gray-700 dark:text-gray-300 mb-2">Event Description *</label>
                                <textarea id="event_description" name="event_description" rows="4" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white"></textarea>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Provide a detailed description of your event, including what attendees can expect.</p>
                            </div>
                            
                            <!-- Date & Time -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Date & Time</h3>
                            </div>
                            
                            <div>
                                <label for="start_date" class="block text-gray-700 dark:text-gray-300 mb-2">Start Date *</label>
                                <input type="date" id="start_date" name="start_date" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="end_date" class="block text-gray-700 dark:text-gray-300 mb-2">End Date *</label>
                                <input type="date" id="end_date" name="end_date" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="start_time" class="block text-gray-700 dark:text-gray-300 mb-2">Start Time *</label>
                                <input type="time" id="start_time" name="start_time" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="end_time" class="block text-gray-700 dark:text-gray-300 mb-2">End Time *</label>
                                <input type="time" id="end_time" name="end_time" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div class="col-span-2">
                                <label for="timezone" class="block text-gray-700 dark:text-gray-300 mb-2">Timezone *</label>
                                <select id="timezone" name="timezone" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Timezone</option>
                                    <option value="EST">Eastern Time (EST)</option>
                                    <option value="CST">Central Time (CST)</option>
                                    <option value="MST">Mountain Time (MST)</option>
                                    <option value="PST">Pacific Time (PST)</option>
                                    <option value="UTC">Universal Time Coordinated (UTC)</option>
                                </select>
                            </div>
                            
                            <!-- Location -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Location</h3>
                            </div>
                            
                            <div class="col-span-2">
                                <label for="venue_name" class="block text-gray-700 dark:text-gray-300 mb-2">Venue Name *</label>
                                <input type="text" id="venue_name" name="venue_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div class="col-span-2">
                                <label for="address" class="block text-gray-700 dark:text-gray-300 mb-2">Address *</label>
                                <input type="text" id="address" name="address" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="city" class="block text-gray-700 dark:text-gray-300 mb-2">City *</label>
                                <input type="text" id="city" name="city" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="state" class="block text-gray-700 dark:text-gray-300 mb-2">State/Province *</label>
                                <input type="text" id="state" name="state" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="zip" class="block text-gray-700 dark:text-gray-300 mb-2">ZIP/Postal Code *</label>
                                <input type="text" id="zip" name="zip" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="country" class="block text-gray-700 dark:text-gray-300 mb-2">Country *</label>
                                <input type="text" id="country" name="country" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div class="virtual-event-details col-span-2 hidden">
                                <label for="virtual_link" class="block text-gray-700 dark:text-gray-300 mb-2">Virtual Event Link</label>
                                <input type="url" id="virtual_link" name="virtual_link" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">For virtual events, provide the link where attendees can join (Zoom, Teams, etc.)</p>
                            </div>
                            
                            <!-- Organizer Information -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Organizer Information</h3>
                            </div>
                            
                            <div>
                                <label for="organizer_name" class="block text-gray-700 dark:text-gray-300 mb-2">Organizer Name *</label>
                                <input type="text" id="organizer_name" name="organizer_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="organizer_email" class="block text-gray-700 dark:text-gray-300 mb-2">Organizer Email *</label>
                                <input type="email" id="organizer_email" name="organizer_email" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="organizer_phone" class="block text-gray-700 dark:text-gray-300 mb-2">Organizer Phone</label>
                                <input type="tel" id="organizer_phone" name="organizer_phone" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="organizer_website" class="block text-gray-700 dark:text-gray-300 mb-2">Organizer Website</label>
                                <input type="url" id="organizer_website" name="organizer_website" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <!-- Event Details -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Event Details</h3>
                            </div>
                            
                            <div>
                                <label for="event_cost" class="block text-gray-700 dark:text-gray-300 mb-2">Event Cost</label>
                                <input type="text" id="event_cost" name="event_cost" placeholder="e.g. Free, $25, $10-$50" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="registration_link" class="block text-gray-700 dark:text-gray-300 mb-2">Registration Link</label>
                                <input type="url" id="registration_link" name="registration_link" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div class="col-span-2">
                                <label for="event_image" class="block text-gray-700 dark:text-gray-300 mb-2">Event Image</label>
                                <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-6 text-center">
                                    <input type="file" id="event_image" name="event_image" accept="image/*" class="hidden">
                                    <label for="event_image" class="cursor-pointer">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Click to upload an image or drag and drop</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-500">PNG, JPG, GIF up to 5MB</p>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-span-2">
                                <label for="additional_info" class="block text-gray-700 dark:text-gray-300 mb-2">Additional Information</label>
                                <textarea id="additional_info" name="additional_info" rows="3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white"></textarea>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Any other details you'd like to share about your event.</p>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="flex items-start">
                                    <input type="checkbox" name="terms" required class="h-5 w-5 text-purple-600 mt-1">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300 text-sm">
                                        I confirm that this is a legitimate aquarium-related event and agree to the <a href="#" class="text-purple-600 hover:underline">Terms and Conditions</a>. I understand that all submissions are subject to review before being published. *
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="text-center mt-8">
                            <button type="submit" class="inline-block bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-md">
                                Submit Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter -->
    <section class="newsletter py-16 bg-gradient-to-r from-purple-600 to-indigo-600 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">Stay Updated on Upcoming Events</h2>
                <p class="mb-8 opacity-90">Subscribe to our newsletter to receive notifications about new events, early bird discounts, and exclusive content.</p>
                
                <form class="flex flex-col sm:flex-row gap-4 justify-center">
                    <input type="email" placeholder="Your email address" class="px-4 py-3 rounded-lg w-full sm:w-auto flex-grow max-w-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-white">
                    <button type="submit" class="bg-white text-purple-600 hover:bg-purple-50 font-bold py-3 px-8 rounded-lg transition duration-300 shadow-md">
                        Subscribe
                    </button>
                </form>
                
                <p class="mt-4 text-sm opacity-80">We respect your privacy. Unsubscribe at any time.</p>
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
        // Toggle virtual event details based on event type selection
        const eventTypeSelect = document.getElementById('event_type');
        const virtualEventDetails = document.querySelector('.virtual-event-details');
        
        if (eventTypeSelect && virtualEventDetails) {
            eventTypeSelect.addEventListener('change', function() {
                if (this.value === 'virtual' || this.value === 'hybrid') {
                    virtualEventDetails.classList.remove('hidden');
                } else {
                    virtualEventDetails.classList.add('hidden');
                }
            });
        }
    });
</script>

<?php
get_footer();