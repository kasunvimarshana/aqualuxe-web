<?php
/**
 * Template Name: Export Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <!-- Hero Section -->
    <section class="export-hero bg-gradient-to-r from-blue-700 to-indigo-600 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="flex flex-wrap items-center">
                <div class="w-full lg:w-1/2 mb-10 lg:mb-0">
                    <h1 class="text-4xl md:text-5xl font-bold mb-6"><?php echo get_the_title(); ?></h1>
                    <?php if (get_field('hero_subtitle')) : ?>
                        <p class="text-xl md:text-2xl mb-8 opacity-90"><?php echo get_field('hero_subtitle'); ?></p>
                    <?php else : ?>
                        <p class="text-xl md:text-2xl mb-8 opacity-90">International ornamental fish export services with comprehensive logistics and regulatory compliance</p>
                    <?php endif; ?>
                    
                    <div class="flex flex-wrap gap-4">
                        <a href="#export-services" class="inline-block bg-white text-blue-700 hover:bg-blue-50 font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                            Our Services
                        </a>
                        <a href="#contact-form" class="inline-block bg-transparent border-2 border-white text-white hover:bg-white hover:text-blue-700 font-bold py-3 px-8 rounded-lg transition duration-300 shadow-lg">
                            Request Quote
                        </a>
                    </div>
                </div>
                <div class="w-full lg:w-1/2">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="rounded-lg overflow-hidden shadow-2xl">
                            <?php the_post_thumbnail('large', array('class' => 'w-full h-auto')); ?>
                        </div>
                    <?php else : ?>
                        <div class="rounded-lg overflow-hidden shadow-2xl bg-blue-800 p-6">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/export-hero.jpg" alt="Fish Export" class="w-full h-auto rounded">
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

    <!-- Export Services -->
    <section id="export-services" class="export-services py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Our Export Services</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="service-card bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="text-blue-600 dark:text-blue-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">International Shipping</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">We provide specialized shipping services for ornamental fish to over 60 countries worldwide, with temperature-controlled packaging and expedited delivery options.</p>
                    <ul class="text-gray-600 dark:text-gray-400 space-y-2 mb-4">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Temperature-controlled packaging</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Expedited delivery options</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Live arrival guarantee</span>
                        </li>
                    </ul>
                    <a href="#" class="text-blue-600 dark:text-blue-400 font-medium hover:underline flex items-center">
                        Learn More
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                
                <!-- Service 2 -->
                <div class="service-card bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="text-blue-600 dark:text-blue-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Documentation & Compliance</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">Our experts handle all necessary export documentation, permits, and compliance requirements to ensure smooth customs clearance and regulatory adherence.</p>
                    <ul class="text-gray-600 dark:text-gray-400 space-y-2 mb-4">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>CITES permit processing</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Health certificates</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Customs documentation</span>
                        </li>
                    </ul>
                    <a href="#" class="text-blue-600 dark:text-blue-400 font-medium hover:underline flex items-center">
                        Learn More
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
                
                <!-- Service 3 -->
                <div class="service-card bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow duration-300">
                    <div class="text-blue-600 dark:text-blue-400 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Consulting Services</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">We provide expert guidance on international market entry, regulatory requirements, and best practices for ornamental fish exporters.</p>
                    <ul class="text-gray-600 dark:text-gray-400 space-y-2 mb-4">
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Market entry strategies</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Regulatory compliance</span>
                        </li>
                        <li class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span>Business development</span>
                        </li>
                    </ul>
                    <a href="#" class="text-blue-600 dark:text-blue-400 font-medium hover:underline flex items-center">
                        Learn More
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Export Process -->
    <section class="export-process py-16 bg-gray-100 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Our Export Process</h2>
            
            <div class="max-w-4xl mx-auto">
                <div class="relative">
                    <!-- Timeline Line -->
                    <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-full w-1 bg-blue-200 dark:bg-blue-900"></div>
                    
                    <!-- Step 1 -->
                    <div class="relative z-10 mb-12">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Initial Consultation</h3>
                                <p class="text-gray-700 dark:text-gray-300">We begin with a detailed consultation to understand your specific export needs, target markets, and species requirements.</p>
                            </div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                1
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0 hidden md:block"></div>
                        </div>
                    </div>
                    
                    <!-- Step 2 -->
                    <div class="relative z-10 mb-12">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right hidden md:block"></div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                2
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Documentation Preparation</h3>
                                <p class="text-gray-700 dark:text-gray-300">Our team prepares all necessary export documentation, including permits, health certificates, and customs paperwork.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 3 -->
                    <div class="relative z-10 mb-12">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Quality Control & Preparation</h3>
                                <p class="text-gray-700 dark:text-gray-300">Fish are carefully selected, quarantined if necessary, and prepared for shipping with proper acclimation protocols.</p>
                            </div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                3
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0 hidden md:block"></div>
                        </div>
                    </div>
                    
                    <!-- Step 4 -->
                    <div class="relative z-10 mb-12">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right hidden md:block"></div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                4
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Specialized Packaging</h3>
                                <p class="text-gray-700 dark:text-gray-300">We use advanced packaging techniques with temperature control, oxygen enrichment, and protective measures to ensure fish safety.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Step 5 -->
                    <div class="relative z-10 mb-12">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Expedited Shipping</h3>
                                <p class="text-gray-700 dark:text-gray-300">Our logistics team coordinates with airlines and freight forwarders to ensure the fastest possible transit times for live fish.</p>
                            </div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                5
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0 hidden md:block"></div>
                        </div>
                    </div>
                    
                    <!-- Step 6 -->
                    <div class="relative z-10">
                        <div class="flex flex-col md:flex-row items-center">
                            <div class="flex-1 md:pr-8 mb-4 md:mb-0 md:text-right hidden md:block"></div>
                            <div class="mx-auto md:mx-0 flex-shrink-0 w-12 h-12 rounded-full bg-blue-500 flex items-center justify-center text-white font-bold text-xl shadow-lg">
                                6
                            </div>
                            <div class="flex-1 md:pl-8 mt-4 md:mt-0">
                                <h3 class="text-xl font-bold mb-2 text-gray-900 dark:text-white">Arrival Support & Follow-up</h3>
                                <p class="text-gray-700 dark:text-gray-300">We provide arrival notifications, acclimation instructions, and post-delivery support to ensure successful outcomes.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Export Markets -->
    <section class="export-markets py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Our Export Markets</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Global Reach</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-6">We export ornamental fish to over 60 countries across 6 continents, with established logistics networks and regulatory expertise in each region.</p>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">Top Export Regions:</h4>
                            <ul class="text-gray-700 dark:text-gray-300 space-y-1">
                                <li>• Europe</li>
                                <li>• Asia</li>
                                <li>• Middle East</li>
                                <li>• North America</li>
                                <li>• Australia</li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white mb-2">Emerging Markets:</h4>
                            <ul class="text-gray-700 dark:text-gray-300 space-y-1">
                                <li>• South America</li>
                                <li>• Southeast Asia</li>
                                <li>• Eastern Europe</li>
                                <li>• Africa</li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Market Compliance</h3>
                    <p class="text-gray-700 dark:text-gray-300 mb-6">We maintain up-to-date knowledge of import regulations and requirements for each destination country, ensuring smooth customs clearance.</p>
                    
                    <div>
                        <h4 class="font-bold text-gray-900 dark:text-white mb-2">Compliance Expertise:</h4>
                        <ul class="text-gray-700 dark:text-gray-300 space-y-2">
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>CITES regulations for protected species</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Country-specific health certification</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Import permit requirements</span>
                            </li>
                            <li class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-blue-600 dark:text-blue-400 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span>Quarantine regulations</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-md p-6 inline-block">
                    <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Global Export Map</h3>
                    <div class="world-map-container h-96 bg-blue-50 dark:bg-blue-900 rounded-lg overflow-hidden">
                        <!-- World map image or interactive map would go here -->
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/world-export-map.jpg" alt="World Export Map" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Species We Export -->
    <section class="export-species py-16 bg-gray-100 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Species We Export</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Category 1 -->
                <div class="species-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="species-image h-48 overflow-hidden">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/tropical-freshwater.jpg" alt="Tropical Freshwater Fish" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Tropical Freshwater</h3>
                        <ul class="text-gray-700 dark:text-gray-300 space-y-1 mb-4">
                            <li>• Discus</li>
                            <li>• Angelfish</li>
                            <li>• Tetras</li>
                            <li>• Cichlids</li>
                            <li>• Barbs</li>
                            <li>• Gouramis</li>
                        </ul>
                        <a href="#" class="text-blue-600 dark:text-blue-400 font-medium hover:underline flex items-center">
                            View Catalog
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Category 2 -->
                <div class="species-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="species-image h-48 overflow-hidden">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/marine-fish.jpg" alt="Marine Fish" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Marine Fish</h3>
                        <ul class="text-gray-700 dark:text-gray-300 space-y-1 mb-4">
                            <li>• Clownfish</li>
                            <li>• Tangs</li>
                            <li>• Angelfish</li>
                            <li>• Gobies</li>
                            <li>• Wrasses</li>
                            <li>• Butterflyfish</li>
                        </ul>
                        <a href="#" class="text-blue-600 dark:text-blue-400 font-medium hover:underline flex items-center">
                            View Catalog
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Category 3 -->
                <div class="species-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="species-image h-48 overflow-hidden">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/koi-goldfish.jpg" alt="Koi & Goldfish" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Koi & Goldfish</h3>
                        <ul class="text-gray-700 dark:text-gray-300 space-y-1 mb-4">
                            <li>• Kohaku Koi</li>
                            <li>• Showa Koi</li>
                            <li>• Sanke Koi</li>
                            <li>• Oranda Goldfish</li>
                            <li>• Ranchu Goldfish</li>
                            <li>• Ryukin Goldfish</li>
                        </ul>
                        <a href="#" class="text-blue-600 dark:text-blue-400 font-medium hover:underline flex items-center">
                            View Catalog
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Category 4 -->
                <div class="species-card bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition-shadow duration-300">
                    <div class="species-image h-48 overflow-hidden">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/rare-exotic.jpg" alt="Rare & Exotic Species" class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-bold mb-3 text-gray-900 dark:text-white">Rare & Exotic</h3>
                        <ul class="text-gray-700 dark:text-gray-300 space-y-1 mb-4">
                            <li>• Arowana</li>
                            <li>• Stingrays</li>
                            <li>• L-Series Plecos</li>
                            <li>• Wild Discus</li>
                            <li>• Rare Cichlids</li>
                            <li>• Exotic Catfish</li>
                        </ul>
                        <a href="#" class="text-blue-600 dark:text-blue-400 font-medium hover:underline flex items-center">
                            View Catalog
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-12">
                <a href="#" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-md">
                    Download Complete Species List
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Form -->
    <section id="contact-form" class="export-contact py-16 bg-white dark:bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-3xl font-bold text-center mb-4 text-gray-900 dark:text-white">Request Export Quote</h2>
                <p class="text-center text-gray-700 dark:text-gray-300 mb-12 max-w-2xl mx-auto">Fill out the form below to request a customized export quote. Our team will review your requirements and contact you within 1-2 business days.</p>
                
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg p-8">
                    <form class="export-quote-form">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Business Information -->
                            <div class="col-span-2">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Business Information</h3>
                            </div>
                            
                            <div>
                                <label for="business_name" class="block text-gray-700 dark:text-gray-300 mb-2">Business Name *</label>
                                <input type="text" id="business_name" name="business_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="business_type" class="block text-gray-700 dark:text-gray-300 mb-2">Business Type *</label>
                                <select id="business_type" name="business_type" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Business Type</option>
                                    <option value="wholesaler">Wholesaler</option>
                                    <option value="retailer">Retailer</option>
                                    <option value="distributor">Distributor</option>
                                    <option value="breeder">Breeder</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="contact_name" class="block text-gray-700 dark:text-gray-300 mb-2">Contact Name *</label>
                                <input type="text" id="contact_name" name="contact_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="position" class="block text-gray-700 dark:text-gray-300 mb-2">Position *</label>
                                <input type="text" id="position" name="position" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="email" class="block text-gray-700 dark:text-gray-300 mb-2">Email Address *</label>
                                <input type="email" id="email" name="email" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="phone" class="block text-gray-700 dark:text-gray-300 mb-2">Phone Number *</label>
                                <input type="tel" id="phone" name="phone" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div class="col-span-2">
                                <label for="address" class="block text-gray-700 dark:text-gray-300 mb-2">Business Address *</label>
                                <input type="text" id="address" name="address" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="city" class="block text-gray-700 dark:text-gray-300 mb-2">City *</label>
                                <input type="text" id="city" name="city" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="country" class="block text-gray-700 dark:text-gray-300 mb-2">Country *</label>
                                <input type="text" id="country" name="country" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <!-- Export Requirements -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Export Requirements</h3>
                            </div>
                            
                            <div>
                                <label for="destination_country" class="block text-gray-700 dark:text-gray-300 mb-2">Destination Country *</label>
                                <input type="text" id="destination_country" name="destination_country" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                            </div>
                            
                            <div>
                                <label for="frequency" class="block text-gray-700 dark:text-gray-300 mb-2">Expected Order Frequency *</label>
                                <select id="frequency" name="frequency" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Frequency</option>
                                    <option value="weekly">Weekly</option>
                                    <option value="biweekly">Bi-Weekly</option>
                                    <option value="monthly">Monthly</option>
                                    <option value="quarterly">Quarterly</option>
                                    <option value="one_time">One-Time Order</option>
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="block text-gray-700 dark:text-gray-300 mb-2">Species Interest (Select all that apply) *</label>
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="species_interest[]" value="tropical_freshwater" class="h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Tropical Freshwater</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="species_interest[]" value="marine_fish" class="h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Marine Fish</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="species_interest[]" value="koi_goldfish" class="h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Koi & Goldfish</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="species_interest[]" value="rare_exotic" class="h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Rare & Exotic</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="species_interest[]" value="aquatic_plants" class="h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Aquatic Plants</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="species_interest[]" value="invertebrates" class="h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Invertebrates</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div>
                                <label for="estimated_quantity" class="block text-gray-700 dark:text-gray-300 mb-2">Estimated Quantity (per order) *</label>
                                <select id="estimated_quantity" name="estimated_quantity" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Range</option>
                                    <option value="under_100">Under 100 specimens</option>
                                    <option value="100_500">100-500 specimens</option>
                                    <option value="500_1000">500-1,000 specimens</option>
                                    <option value="1000_5000">1,000-5,000 specimens</option>
                                    <option value="over_5000">Over 5,000 specimens</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="estimated_value" class="block text-gray-700 dark:text-gray-300 mb-2">Estimated Order Value *</label>
                                <select id="estimated_value" name="estimated_value" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white">
                                    <option value="">Select Range</option>
                                    <option value="under_5000">Under $5,000</option>
                                    <option value="5000_10000">$5,000-$10,000</option>
                                    <option value="10000_25000">$10,000-$25,000</option>
                                    <option value="25000_50000">$25,000-$50,000</option>
                                    <option value="over_50000">Over $50,000</option>
                                </select>
                            </div>
                            
                            <div class="col-span-2">
                                <label for="specific_requirements" class="block text-gray-700 dark:text-gray-300 mb-2">Specific Requirements</label>
                                <textarea id="specific_requirements" name="specific_requirements" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white"></textarea>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Please provide any specific species, sizes, quantities, or special shipping requirements.</p>
                            </div>
                            
                            <!-- Services Required -->
                            <div class="col-span-2 mt-6">
                                <h3 class="text-xl font-bold mb-4 text-gray-900 dark:text-white">Services Required</h3>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                    <label class="flex items-center">
                                        <input type="checkbox" name="services[]" value="export_shipping" class="h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Export Shipping</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="services[]" value="documentation" class="h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Documentation & Compliance</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="services[]" value="consulting" class="h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Export Consulting</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox" name="services[]" value="sourcing" class="h-5 w-5 text-blue-600">
                                        <span class="ml-2 text-gray-700 dark:text-gray-300">Species Sourcing</span>
                                    </label>
                                </div>
                            </div>
                            
                            <div class="col-span-2">
                                <label class="flex items-start">
                                    <input type="checkbox" name="terms" required class="h-5 w-5 text-blue-600 mt-1">
                                    <span class="ml-2 text-gray-700 dark:text-gray-300 text-sm">
                                        I agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a> and acknowledge that my information will be processed in accordance with the <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>. *
                                    </span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="text-center mt-8">
                            <button type="submit" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg transition duration-300 shadow-md">
                                Submit Quote Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="export-faq py-16 bg-gray-100 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center mb-12 text-gray-900 dark:text-white">Frequently Asked Questions</h2>
            
            <div class="max-w-4xl mx-auto">
                <div class="space-y-6">
                    <!-- FAQ Item 1 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">What documentation is required for exporting ornamental fish?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>The required documentation varies by destination country but typically includes:</p>
                            <ul class="list-disc pl-5 mt-2 space-y-1">
                                <li>Commercial invoice</li>
                                <li>Packing list</li>
                                <li>Health certificate from authorized veterinarian</li>
                                <li>CITES permits (for protected species)</li>
                                <li>Export permit from country of origin</li>
                                <li>Import permit from destination country</li>
                                <li>Airway bill</li>
                            </ul>
                            <p class="mt-2">Our documentation team handles all these requirements as part of our export service.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 2 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">How do you ensure fish survive international shipping?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>We employ several specialized techniques to ensure high survival rates during shipping:</p>
                            <ul class="list-disc pl-5 mt-2 space-y-1">
                                <li>Pre-shipping conditioning and fasting protocols</li>
                                <li>Double-bagging with pure oxygen</li>
                                <li>Specialized water additives to reduce stress and ammonia buildup</li>
                                <li>Temperature-controlled packaging with insulation and heat/cold packs as needed</li>
                                <li>Expedited shipping routes with minimal transit time</li>
                                <li>Coordination with airlines for priority handling</li>
                                <li>Detailed acclimation instructions for recipients</li>
                            </ul>
                            <p class="mt-2">Our average survival rate exceeds 99% for most shipments, significantly above industry standards.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 3 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">What are your minimum order requirements?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>Our minimum order requirements vary based on destination:</p>
                            <ul class="list-disc pl-5 mt-2 space-y-1">
                                <li>North America: $3,000 minimum order value</li>
                                <li>Europe: $5,000 minimum order value</li>
                                <li>Asia: $3,500 minimum order value</li>
                                <li>Middle East: $5,000 minimum order value</li>
                                <li>Australia/New Zealand: $4,000 minimum order value</li>
                            </ul>
                            <p class="mt-2">These minimums help ensure shipping efficiency and cost-effectiveness. For smaller orders, we offer consolidation services where multiple small orders can be combined into a single shipment.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 4 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">How long does the export process take?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>The timeline varies depending on several factors:</p>
                            <ul class="list-disc pl-5 mt-2 space-y-1">
                                <li>Standard orders: 2-3 weeks from order confirmation to delivery</li>
                                <li>Rush orders: 7-10 business days (additional fees apply)</li>
                                <li>CITES-listed species: Add 1-2 weeks for permit processing</li>
                                <li>First-time orders to new countries: Add 1 week for regulatory verification</li>
                            </ul>
                            <p class="mt-2">The process includes order processing, documentation preparation, fish conditioning, packing, and shipping. We provide regular updates throughout the process so you always know the status of your order.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 5 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">Do you provide guarantees on your exports?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>Yes, we offer the following guarantees:</p>
                            <ul class="list-disc pl-5 mt-2 space-y-1">
                                <li><strong>Live Arrival Guarantee:</strong> We guarantee that fish will arrive alive at the destination airport. Any DOA (Dead On Arrival) losses are credited or replaced with proper documentation.</li>
                                <li><strong>Species Accuracy Guarantee:</strong> We guarantee that all fish are correctly identified and labeled.</li>
                                <li><strong>Size Accuracy Guarantee:</strong> We guarantee that fish meet the specified size ranges ordered.</li>
                                <li><strong>Health Guarantee:</strong> All fish are quarantined and health-checked before shipping.</li>
                            </ul>
                            <p class="mt-2">Claims must be filed within 2 hours of delivery with photographic evidence. Our average DOA rate is less than 1%, significantly below industry standards.</p>
                        </div>
                    </div>
                    
                    <!-- FAQ Item 6 -->
                    <div class="faq-item bg-white dark:bg-gray-700 rounded-lg shadow-md overflow-hidden">
                        <button class="faq-question w-full flex justify-between items-center p-6 text-left focus:outline-none">
                            <span class="text-lg font-semibold text-gray-900 dark:text-white">What payment methods do you accept?</span>
                            <svg class="h-6 w-6 text-gray-500 dark:text-gray-400 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div class="faq-answer px-6 pb-6 text-gray-700 dark:text-gray-300">
                            <p>We accept the following payment methods for international exports:</p>
                            <ul class="list-disc pl-5 mt-2 space-y-1">
                                <li>Wire transfer (preferred method)</li>
                                <li>Letter of Credit (for large orders)</li>
                                <li>International credit cards (Visa, Mastercard, American Express)</li>
                                <li>PayPal (for smaller orders, fees apply)</li>
                            </ul>
                            <p class="mt-2">Payment terms:</p>
                            <ul class="list-disc pl-5 mt-2 space-y-1">
                                <li>New customers: 50% deposit at order confirmation, 50% before shipping</li>
                                <li>Established customers: Net 30 terms available after credit approval</li>
                                <li>Regular customers: Volume discounts and preferential payment terms available</li>
                            </ul>
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