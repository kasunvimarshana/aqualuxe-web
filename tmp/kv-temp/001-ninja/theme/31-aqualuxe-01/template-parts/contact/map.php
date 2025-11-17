<?php
/**
 * Template part for displaying the Google Maps section on the Contact page
 *
 * @package AquaLuxe
 */
?>

<section id="map" class="py-12 bg-gray-50 dark:bg-gray-800">
    <div class="container mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Find Us</h2>
            <p class="text-gray-600 dark:text-gray-300 mt-2">Visit our store and showroom</p>
        </div>
        
        <div class="bg-white dark:bg-gray-700 rounded-xl overflow-hidden shadow-lg">
            <div class="aspect-w-16 aspect-h-9">
                <!-- Google Maps iframe - Replace with your own Google Maps embed code -->
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d114964.53925916665!2d-80.29949920266738!3d25.782390733064336!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x88d9b0a20ec8c111%3A0xff96f271ddad4f65!2sMiami%2C%20FL!5e0!3m2!1sen!2sus!4v1628610181500!5m2!1sen!2sus" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy"
                    class="w-full h-full"
                    title="AquaLuxe Store Location">
                </iframe>
            </div>
            
            <div class="p-6 flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="font-bold text-gray-800 dark:text-white text-lg">AquaLuxe Aquarium Store</h3>
                    <p class="text-gray-600 dark:text-gray-300">123 Coral Reef Drive, Miami, FL 33101</p>
                </div>
                
                <div class="flex flex-wrap gap-3">
                    <a href="https://goo.gl/maps/your-google-maps-link" target="_blank" rel="noopener noreferrer" class="inline-flex items-center bg-blue-100 dark:bg-blue-900 hover:bg-blue-200 dark:hover:bg-blue-800 text-blue-700 dark:text-blue-300 px-4 py-2 rounded-lg transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12 1.586l-4 4v12.828l4-4V1.586zM3.707 3.293A1 1 0 002 4v10a1 1 0 00.293.707L6 18.414V5.586L3.707 3.293zM17.707 5.293L14 1.586v12.828l2.293 2.293A1 1 0 0018 16V6a1 1 0 00-.293-.707z" clip-rule="evenodd" />
                        </svg>
                        <span>Get Directions</span>
                    </a>
                    
                    <a href="tel:+15551234567" class="inline-flex items-center bg-teal-100 dark:bg-teal-900 hover:bg-teal-200 dark:hover:bg-teal-800 text-teal-700 dark:text-teal-300 px-4 py-2 rounded-lg transition duration-300">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z" />
                        </svg>
                        <span>Call Store</span>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Store Hours</h3>
                </div>
                <ul class="space-y-2 text-gray-600 dark:text-gray-300">
                    <li class="flex justify-between">
                        <span>Monday - Friday</span>
                        <span>9:00 AM - 6:00 PM</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Saturday</span>
                        <span>10:00 AM - 4:00 PM</span>
                    </li>
                    <li class="flex justify-between">
                        <span>Sunday</span>
                        <span>Closed</span>
                    </li>
                </ul>
            </div>
            
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600 dark:text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Parking</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-300">
                    Free parking is available in our dedicated lot. Additional street parking is available nearby.
                </p>
            </div>
            
            <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-800 dark:text-white">Public Transit</h3>
                </div>
                <p class="text-gray-600 dark:text-gray-300">
                    Bus routes 42 and 67 stop directly in front of our store. The nearest metro station is Coral Way, a 10-minute walk away.
                </p>
            </div>
        </div>
    </div>
</section>