<?php
/**
 * Template part for displaying the sidebar on the FAQ page
 *
 * @package AquaLuxe
 */
?>

<div class="space-y-8">
    <!-- Quick Navigation -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Quick Navigation</h3>
        </div>
        <div class="p-5">
            <nav class="space-y-1">
                <a href="#shipping" class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 dark:text-blue-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    <span>Shipping & Delivery</span>
                </a>
                <a href="#care" class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                    </svg>
                    <span>Aquarium Care</span>
                </a>
                <a href="#purchasing" class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-600 dark:text-purple-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span>Purchasing</span>
                </a>
                <a href="#export-import" class="flex items-center px-3 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-600 dark:text-amber-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Export & Import</span>
                </a>
            </nav>
        </div>
    </div>
    
    <!-- Popular Articles -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Popular Articles</h3>
        </div>
        <div class="p-5">
            <ul class="space-y-3">
                <li>
                    <a href="#care-faq-1" class="flex items-start group">
                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 text-xs font-medium px-2.5 py-0.5 rounded-full flex-shrink-0 mt-0.5">Care</span>
                        <span class="ml-2 text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition duration-200">How do I cycle a new aquarium?</span>
                    </a>
                </li>
                <li>
                    <a href="#shipping-faq-3" class="flex items-start group">
                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 text-xs font-medium px-2.5 py-0.5 rounded-full flex-shrink-0 mt-0.5">Shipping</span>
                        <span class="ml-2 text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition duration-200">How do you ensure live specimens arrive safely?</span>
                    </a>
                </li>
                <li>
                    <a href="#purchasing-faq-2" class="flex items-start group">
                        <span class="bg-purple-100 dark:bg-purple-900 text-purple-600 dark:text-purple-400 text-xs font-medium px-2.5 py-0.5 rounded-full flex-shrink-0 mt-0.5">Purchasing</span>
                        <span class="ml-2 text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition duration-200">What is your return and exchange policy?</span>
                    </a>
                </li>
                <li>
                    <a href="#care-faq-5" class="flex items-start group">
                        <span class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 text-xs font-medium px-2.5 py-0.5 rounded-full flex-shrink-0 mt-0.5">Care</span>
                        <span class="ml-2 text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition duration-200">How do I diagnose and treat common fish diseases?</span>
                    </a>
                </li>
                <li>
                    <a href="#export-import-faq-1" class="flex items-start group">
                        <span class="bg-amber-100 dark:bg-amber-900 text-amber-600 dark:text-amber-400 text-xs font-medium px-2.5 py-0.5 rounded-full flex-shrink-0 mt-0.5">Export</span>
                        <span class="ml-2 text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition duration-200">What permits are required for importing aquatic species?</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Need Help -->
    <div class="bg-gradient-to-br from-teal-500 to-blue-600 rounded-xl shadow-sm overflow-hidden text-white">
        <div class="p-5">
            <h3 class="font-bold text-xl mb-3">Need More Help?</h3>
            <p class="mb-4 text-blue-100">
                Can't find the answer you're looking for? Our customer support team is ready to assist you.
            </p>
            <div class="space-y-3">
                <a href="#" class="flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>
                    <span>Live Chat</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span>Email Support</span>
                </a>
                <a href="#" class="flex items-center px-4 py-2 bg-white/20 hover:bg-white/30 rounded-lg transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    <span>Call (555) 123-4567</span>
                </a>
            </div>
        </div>
    </div>
    
    <!-- Resources -->
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="font-bold text-gray-800 dark:text-white text-lg">Helpful Resources</h3>
        </div>
        <div class="p-5">
            <ul class="space-y-3">
                <li>
                    <a href="#" class="flex items-center group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-3 group-hover:text-teal-700 dark:group-hover:text-teal-300 transition duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition duration-200">Beginner's Guide to Aquariums</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-3 group-hover:text-teal-700 dark:group-hover:text-teal-300 transition duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition duration-200">Water Parameter Guide</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-3 group-hover:text-teal-700 dark:group-hover:text-teal-300 transition duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition duration-200">Compatibility Charts</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-3 group-hover:text-teal-700 dark:group-hover:text-teal-300 transition duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition duration-200">Maintenance Checklists</span>
                    </a>
                </li>
                <li>
                    <a href="#" class="flex items-center group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-teal-600 dark:text-teal-400 mr-3 group-hover:text-teal-700 dark:group-hover:text-teal-300 transition duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300 group-hover:text-teal-600 dark:group-hover:text-teal-400 transition duration-200">Video Tutorials</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>