<?php
/**
 * Template part for displaying the maintenance services section
 *
 * @package AquaLuxe
 */
?>

<section id="maintenance" class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row-reverse items-center">
            <div class="md:w-1/2 mb-8 md:mb-0 md:pl-12">
                <div class="relative">
                    <div class="bg-blue-500 absolute -top-4 -right-4 w-24 h-24 opacity-20 rounded-lg"></div>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/aquarium-maintenance.jpg'); ?>" alt="Professional Aquarium Maintenance" class="rounded-lg shadow-xl relative z-10 w-full">
                    <div class="absolute -bottom-4 -left-4 bg-teal-500 w-32 h-32 opacity-20 rounded-lg"></div>
                </div>
            </div>
            
            <div class="md:w-1/2">
                <span class="text-teal-600 dark:text-teal-400 font-semibold text-sm uppercase tracking-wider">Professional Care</span>
                <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Aquarium Maintenance</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Our maintenance services ensure your aquarium remains healthy, clean, and visually stunning. 
                    We offer flexible maintenance schedules tailored to your specific needs, whether you have a 
                    small home aquarium or a large commercial installation.
                </p>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-700 p-5 rounded-lg shadow-sm">
                        <div class="text-teal-600 dark:text-teal-400 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg mb-2">Regular Service</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Scheduled maintenance including water changes, filter cleaning, glass cleaning, and water testing.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 p-5 rounded-lg shadow-sm">
                        <div class="text-teal-600 dark:text-teal-400 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg mb-2">Health Monitoring</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Regular assessment of fish health, early disease detection, and preventative treatments.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 p-5 rounded-lg shadow-sm">
                        <div class="text-teal-600 dark:text-teal-400 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg mb-2">Equipment Maintenance</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Inspection, cleaning, and maintenance of filters, pumps, heaters, and lighting systems.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 p-5 rounded-lg shadow-sm">
                        <div class="text-teal-600 dark:text-teal-400 mb-3">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg mb-2">Aquascape Refreshing</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-sm">
                            Plant trimming, hardscape adjustment, and substrate cleaning to maintain aesthetic appeal.
                        </p>
                    </div>
                </div>
                
                <div class="mt-8 p-4 bg-blue-50 dark:bg-blue-900 rounded-lg">
                    <h3 class="font-medium text-gray-800 dark:text-white mb-2">Maintenance Plans</h3>
                    <div class="flex flex-wrap gap-3">
                        <span class="bg-white dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full">Weekly</span>
                        <span class="bg-white dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full">Bi-Weekly</span>
                        <span class="bg-white dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full">Monthly</span>
                        <span class="bg-white dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full">Custom Schedule</span>
                        <span class="bg-white dark:bg-gray-700 text-sm text-gray-700 dark:text-gray-300 px-3 py-1 rounded-full">Emergency Service</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>