<?php
/**
 * Template part for displaying the breeding programs services section
 *
 * @package AquaLuxe
 */
?>

<section id="breeding" class="py-16 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row-reverse items-center">
            <div class="md:w-1/2 mb-8 md:mb-0 md:pl-12">
                <div class="relative">
                    <div class="bg-teal-500 absolute -top-4 -right-4 w-24 h-24 opacity-20 rounded-lg"></div>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/breeding-program.jpg'); ?>" alt="Aquatic Breeding Programs" class="rounded-lg shadow-xl relative z-10 w-full">
                    <div class="absolute -bottom-4 -left-4 bg-blue-500 w-32 h-32 opacity-20 rounded-lg"></div>
                </div>
            </div>
            
            <div class="md:w-1/2">
                <span class="text-teal-600 dark:text-teal-400 font-semibold text-sm uppercase tracking-wider">Specialized Programs</span>
                <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Breeding Programs</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Our breeding programs focus on sustainable aquaculture, species preservation, and producing 
                    high-quality specimens. We employ advanced techniques and maintain specialized breeding facilities 
                    for various aquatic species.
                </p>
                
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm">
                        <div class="p-5">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg mb-3">Species Conservation</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                We participate in conservation efforts for endangered and threatened aquatic species, 
                                helping to maintain genetic diversity and reduce pressure on wild populations.
                            </p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded">Endangered Species</span>
                                <span class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded">Genetic Preservation</span>
                                <span class="bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-200 text-xs px-2 py-1 rounded">Sustainable Populations</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600 px-5 py-3 bg-gray-50 dark:bg-gray-800">
                            <a href="#contact" class="text-sm text-teal-600 dark:text-teal-400 font-medium hover:text-teal-700 dark:hover:text-teal-300 transition duration-300 flex items-center">
                                <span>Learn about our conservation partners</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm">
                        <div class="p-5">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg mb-3">Commercial Breeding</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                We develop and maintain breeding programs for commercial species, focusing on quality, 
                                health, and unique color morphs or traits that are in demand in the aquarium trade.
                            </p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="bg-teal-100 dark:bg-teal-800 text-teal-800 dark:text-teal-200 text-xs px-2 py-1 rounded">Rare Varieties</span>
                                <span class="bg-teal-100 dark:bg-teal-800 text-teal-800 dark:text-teal-200 text-xs px-2 py-1 rounded">Color Morphs</span>
                                <span class="bg-teal-100 dark:bg-teal-800 text-teal-800 dark:text-teal-200 text-xs px-2 py-1 rounded">Premium Quality</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600 px-5 py-3 bg-gray-50 dark:bg-gray-800">
                            <a href="#contact" class="text-sm text-teal-600 dark:text-teal-400 font-medium hover:text-teal-700 dark:hover:text-teal-300 transition duration-300 flex items-center">
                                <span>View our available bred specimens</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 rounded-lg overflow-hidden shadow-sm">
                        <div class="p-5">
                            <h3 class="font-medium text-gray-800 dark:text-white text-lg mb-3">Breeding Consultation</h3>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">
                                We provide expert consultation for hobbyists and businesses looking to establish their own 
                                breeding programs, offering guidance on species selection, facility setup, and breeding techniques.
                            </p>
                            <div class="mt-4 flex flex-wrap gap-2">
                                <span class="bg-purple-100 dark:bg-purple-800 text-purple-800 dark:text-purple-200 text-xs px-2 py-1 rounded">Expert Guidance</span>
                                <span class="bg-purple-100 dark:bg-purple-800 text-purple-800 dark:text-purple-200 text-xs px-2 py-1 rounded">Facility Design</span>
                                <span class="bg-purple-100 dark:bg-purple-800 text-purple-800 dark:text-purple-200 text-xs px-2 py-1 rounded">Breeding Techniques</span>
                            </div>
                        </div>
                        <div class="border-t border-gray-200 dark:border-gray-600 px-5 py-3 bg-gray-50 dark:bg-gray-800">
                            <a href="#contact" class="text-sm text-teal-600 dark:text-teal-400 font-medium hover:text-teal-700 dark:hover:text-teal-300 transition duration-300 flex items-center">
                                <span>Schedule a breeding consultation</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="mt-8">
                    <h3 class="font-medium text-gray-800 dark:text-white mb-4">Species We Specialize In:</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">Rare Tetras</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">Dwarf Cichlids</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">Shrimp Varieties</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">Corydoras</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">Killifish</span>
                        </div>
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-600 dark:text-gray-300">Live Plants</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>