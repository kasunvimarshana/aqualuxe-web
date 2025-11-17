<?php
/**
 * Template part for displaying the design services section
 *
 * @package AquaLuxe
 */
?>

<section id="design" class="py-16 border-t border-gray-200 dark:border-gray-700">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0 md:pr-12">
                <div class="relative">
                    <div class="bg-teal-500 absolute -top-4 -left-4 w-24 h-24 opacity-20 rounded-lg"></div>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/aquarium-design.jpg'); ?>" alt="Custom Aquarium Design" class="rounded-lg shadow-xl relative z-10 w-full">
                    <div class="absolute -bottom-4 -right-4 bg-blue-500 w-32 h-32 opacity-20 rounded-lg"></div>
                </div>
            </div>
            
            <div class="md:w-1/2">
                <span class="text-teal-600 dark:text-teal-400 font-semibold text-sm uppercase tracking-wider">Expert Design Services</span>
                <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Custom Aquarium Design</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Our design team creates custom aquarium environments that blend seamlessly with your space while providing 
                    optimal conditions for aquatic life. We consider aesthetics, functionality, and the specific needs of your 
                    desired ecosystem.
                </p>
                
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="bg-teal-100 dark:bg-teal-900 rounded-full p-1 mr-4 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 dark:text-teal-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-white">Custom Aquascaping</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                Artistic arrangement of hardscape elements and live plants to create stunning underwater landscapes.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-teal-100 dark:bg-teal-900 rounded-full p-1 mr-4 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 dark:text-teal-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-white">Biotope Creation</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                Authentic recreations of natural aquatic environments from specific geographic regions.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-teal-100 dark:bg-teal-900 rounded-full p-1 mr-4 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 dark:text-teal-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-white">Custom Tank Fabrication</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                Bespoke aquarium sizes and shapes designed to fit your specific space and vision.
                            </p>
                        </div>
                    </div>
                    
                    <div class="flex items-start">
                        <div class="bg-teal-100 dark:bg-teal-900 rounded-full p-1 mr-4 mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 dark:text-teal-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-medium text-gray-800 dark:text-white">Advanced Filtration Design</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                Tailored filtration systems that maintain water quality while remaining discreet and efficient.
                            </p>
                        </div>
                    </div>
                </div>
                
                <a href="#contact" class="inline-flex items-center mt-8 text-teal-600 dark:text-teal-400 font-medium hover:text-teal-700 dark:hover:text-teal-300 transition duration-300">
                    <span>Request Design Consultation</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</section>