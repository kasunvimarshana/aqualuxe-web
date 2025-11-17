<?php
/**
 * Template part for displaying the quarantine services section
 *
 * @package AquaLuxe
 */
?>

<section id="quarantine" class="py-16 border-t border-gray-200 dark:border-gray-700">
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row items-center">
            <div class="md:w-1/2 mb-8 md:mb-0 md:pr-12">
                <div class="relative">
                    <div class="bg-blue-500 absolute -top-4 -left-4 w-24 h-24 opacity-20 rounded-lg"></div>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/quarantine-service.jpg'); ?>" alt="Aquatic Quarantine Services" class="rounded-lg shadow-xl relative z-10 w-full">
                    <div class="absolute -bottom-4 -right-4 bg-teal-500 w-32 h-32 opacity-20 rounded-lg"></div>
                </div>
            </div>
            
            <div class="md:w-1/2">
                <span class="text-teal-600 dark:text-teal-400 font-semibold text-sm uppercase tracking-wider">Specialized Care</span>
                <h2 class="text-3xl font-bold mb-6 text-gray-800 dark:text-white">Quarantine Services</h2>
                <p class="text-gray-600 dark:text-gray-300 mb-6">
                    Our professional quarantine services help protect your existing aquatic ecosystem by safely 
                    introducing new specimens. We maintain dedicated quarantine facilities with strict protocols 
                    to ensure the health and safety of all aquatic life.
                </p>
                
                <div class="space-y-6">
                    <div class="bg-white dark:bg-gray-700 p-5 rounded-lg shadow-sm">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg mb-2 flex items-center">
                            <span class="bg-teal-100 dark:bg-teal-900 rounded-full p-1 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            New Specimen Quarantine
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 ml-9">
                            We provide professional quarantine services for newly acquired fish, invertebrates, and plants 
                            before introducing them to your main display. This critical step helps prevent the introduction 
                            of diseases, parasites, and unwanted organisms to your established system.
                        </p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 p-5 rounded-lg shadow-sm">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg mb-2 flex items-center">
                            <span class="bg-teal-100 dark:bg-teal-900 rounded-full p-1 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M7 2a1 1 0 00-.707 1.707L7 4.414v3.758a1 1 0 01-.293.707l-4 4C.817 14.769 2.156 18 4.828 18h10.343c2.673 0 4.012-3.231 2.122-5.121l-4-4A1 1 0 0113 8.172V4.414l.707-.707A1 1 0 0013 2H7zm2 6.172V4h2v4.172a3 3 0 00.879 2.12l1.027 1.028a4 4 0 00-2.171.102l-.47.156a4 4 0 01-2.53 0l-.563-.187a1.993 1.993 0 00-.114-.035l1.063-1.063A3 3 0 009 8.172z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            Treatment Protocols
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 ml-9">
                            Our quarantine process includes observation periods, preventative treatments, and testing 
                            to identify and address potential health issues before they can affect your main system.
                        </p>
                        <div class="ml-9 mt-3 grid grid-cols-2 gap-2">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 dark:text-teal-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Parasite treatments</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 dark:text-teal-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Bacterial control</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 dark:text-teal-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Fungal prevention</span>
                            </div>
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-teal-600 dark:text-teal-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Viral screening</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-700 p-5 rounded-lg shadow-sm">
                        <h3 class="font-medium text-gray-800 dark:text-white text-lg mb-2 flex items-center">
                            <span class="bg-teal-100 dark:bg-teal-900 rounded-full p-1 mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z" />
                                </svg>
                            </span>
                            Quarantine Facilities
                        </h3>
                        <p class="text-gray-600 dark:text-gray-400 ml-9">
                            We maintain dedicated quarantine systems with specialized equipment for different species 
                            and requirements. Our facilities feature isolated water systems, UV sterilization, and 
                            strict biosecurity protocols to prevent cross-contamination.
                        </p>
                    </div>
                </div>
                
                <div class="mt-8 p-4 bg-yellow-50 dark:bg-yellow-900/30 rounded-lg border-l-4 border-yellow-400 dark:border-yellow-600">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                <strong>Important:</strong> Proper quarantine is essential for maintaining a healthy aquarium ecosystem. 
                                Even seemingly healthy specimens can carry pathogens that may devastate an established system.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>