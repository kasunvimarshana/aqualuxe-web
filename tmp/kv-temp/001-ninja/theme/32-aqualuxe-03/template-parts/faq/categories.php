<?php
/**
 * Template part for displaying the FAQ categories on the FAQ page
 *
 * @package AquaLuxe
 */
?>

<section class="faq-categories">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <a href="#shipping" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition duration-300 text-center group">
            <div class="bg-blue-100 dark:bg-blue-900/50 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center group-hover:bg-blue-200 dark:group-hover:bg-blue-800 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Shipping & Delivery</h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm">
                Learn about shipping methods, rates, delivery times, and special handling for live specimens.
            </p>
        </a>
        
        <a href="#care" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition duration-300 text-center group">
            <div class="bg-teal-100 dark:bg-teal-900/50 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center group-hover:bg-teal-200 dark:group-hover:bg-teal-800 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-teal-600 dark:text-teal-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Aquarium Care</h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm">
                Find guidance on water parameters, maintenance routines, feeding, and troubleshooting common issues.
            </p>
        </a>
        
        <a href="#purchasing" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition duration-300 text-center group">
            <div class="bg-purple-100 dark:bg-purple-900/50 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center group-hover:bg-purple-200 dark:group-hover:bg-purple-800 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Purchasing</h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm">
                Information about ordering, payment methods, returns, exchanges, and warranty policies.
            </p>
        </a>
        
        <a href="#export-import" class="bg-white dark:bg-gray-800 rounded-xl p-6 shadow-sm hover:shadow-md transition duration-300 text-center group">
            <div class="bg-amber-100 dark:bg-amber-900/50 rounded-full p-4 w-16 h-16 mx-auto mb-4 flex items-center justify-center group-hover:bg-amber-200 dark:group-hover:bg-amber-800 transition duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-amber-600 dark:text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h3 class="text-lg font-bold text-gray-800 dark:text-white mb-2">Export & Import</h3>
            <p class="text-gray-600 dark:text-gray-300 text-sm">
                Details on international shipping, customs, permits, and regulations for aquatic species.
            </p>
        </a>
    </div>
</section>