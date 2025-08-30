<?php
/**
 * Template part for displaying the shipping FAQs on the FAQ page
 *
 * @package AquaLuxe
 */
?>

<section id="shipping" class="faq-section">
    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
            Shipping & Delivery
        </h2>
    </div>
    
    <div class="space-y-6" x-data="{selected:null}">
        <!-- FAQ Item 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 1 ? selected = 1 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 1 ? 'true' : 'false'"
                aria-controls="shipping-faq-1"
            >
                <span class="font-medium text-gray-800 dark:text-white">What shipping methods do you offer?</span>
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    class="h-5 w-5 text-gray-500 dark:text-gray-400 transition-transform" 
                    :class="{'rotate-180': selected === 1}"
                    viewBox="0 0 20 20" 
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div 
                id="shipping-faq-1" 
                x-show="selected === 1" 
                x-transition:enter="transition ease-out duration-200" 
                x-transition:enter-start="opacity-0 -translate-y-2" 
                x-transition:enter-end="opacity-100 translate-y-0" 
                x-transition:leave="transition ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0" 
                x-transition:leave-end="opacity-0 -translate-y-2" 
                class="p-5 pt-0 border-t border-gray-200 dark:border-gray-700"
            >
                <p class="text-gray-600 dark:text-gray-300">
                    We offer several shipping methods to ensure your aquatic products arrive safely:
                </p>
                <ul class="list-disc list-inside mt-3 space-y-2 text-gray-600 dark:text-gray-300">
                    <li><strong>Standard Shipping:</strong> 5-7 business days for dry goods and equipment</li>
                    <li><strong>Express Shipping:</strong> 2-3 business days for dry goods and equipment</li>
                    <li><strong>Overnight Shipping:</strong> Next business day delivery, required for all live fish, invertebrates, and plants</li>
                    <li><strong>International Shipping:</strong> Available to select countries with varying delivery times</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    All live specimens are shipped with insulated packaging and heat packs or cold packs as needed based on your climate and season.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 2 ? selected = 2 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 2 ? 'true' : 'false'"
                aria-controls="shipping-faq-2"
            >
                <span class="font-medium text-gray-800 dark:text-white">How much does shipping cost?</span>
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    class="h-5 w-5 text-gray-500 dark:text-gray-400 transition-transform" 
                    :class="{'rotate-180': selected === 2}"
                    viewBox="0 0 20 20" 
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div 
                id="shipping-faq-2" 
                x-show="selected === 2" 
                x-transition:enter="transition ease-out duration-200" 
                x-transition:enter-start="opacity-0 -translate-y-2" 
                x-transition:enter-end="opacity-100 translate-y-0" 
                x-transition:leave="transition ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0" 
                x-transition:leave-end="opacity-0 -translate-y-2" 
                class="p-5 pt-0 border-t border-gray-200 dark:border-gray-700"
            >
                <p class="text-gray-600 dark:text-gray-300">
                    Our shipping rates are calculated based on the following factors:
                </p>
                <ul class="list-disc list-inside mt-3 space-y-2 text-gray-600 dark:text-gray-300">
                    <li><strong>Order Value:</strong> Free standard shipping on all orders over $75 within the continental US</li>
                    <li><strong>Weight and Dimensions:</strong> Heavier or larger items may incur additional shipping costs</li>
                    <li><strong>Shipping Method:</strong> Standard ($7.95+), Express ($14.95+), Overnight ($29.95+)</li>
                    <li><strong>Destination:</strong> Shipping to Alaska, Hawaii, or international locations will have higher rates</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    <strong>Live Specimen Shipping:</strong> All live fish, invertebrates, and plants require overnight shipping with special handling. These shipping costs start at $29.95 and may be higher based on quantity, size, and destination.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    Exact shipping costs will be calculated at checkout based on your specific order and location.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 3 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 3 ? selected = 3 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 3 ? 'true' : 'false'"
                aria-controls="shipping-faq-3"
            >
                <span class="font-medium text-gray-800 dark:text-white">How do you ensure live specimens arrive safely?</span>
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    class="h-5 w-5 text-gray-500 dark:text-gray-400 transition-transform" 
                    :class="{'rotate-180': selected === 3}"
                    viewBox="0 0 20 20" 
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div 
                id="shipping-faq-3" 
                x-show="selected === 3" 
                x-transition:enter="transition ease-out duration-200" 
                x-transition:enter-start="opacity-0 -translate-y-2" 
                x-transition:enter-end="opacity-100 translate-y-0" 
                x-transition:leave="transition ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0" 
                x-transition:leave-end="opacity-0 -translate-y-2" 
                class="p-5 pt-0 border-t border-gray-200 dark:border-gray-700"
            >
                <p class="text-gray-600 dark:text-gray-300">
                    We take several precautions to ensure live specimens arrive healthy and safe:
                </p>
                <ul class="list-disc list-inside mt-3 space-y-2 text-gray-600 dark:text-gray-300">
                    <li><strong>Specialized Packaging:</strong> All live specimens are double-bagged with oxygen and placed in insulated shipping boxes</li>
                    <li><strong>Temperature Control:</strong> Heat packs in winter and cold packs in summer are included as needed</li>
                    <li><strong>Overnight Shipping Only:</strong> We only ship live specimens via overnight services to minimize transit time</li>
                    <li><strong>Weather Monitoring:</strong> We track weather conditions and may delay shipments during extreme temperatures</li>
                    <li><strong>Acclimation Instructions:</strong> Detailed acclimation instructions are included with every live shipment</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    We also offer a Live Arrival Guarantee for all properly acclimated specimens. If any issues occur, please take photos and contact us within 2 hours of delivery.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 4 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 4 ? selected = 4 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 4 ? 'true' : 'false'"
                aria-controls="shipping-faq-4"
            >
                <span class="font-medium text-gray-800 dark:text-white">Do you ship internationally?</span>
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    class="h-5 w-5 text-gray-500 dark:text-gray-400 transition-transform" 
                    :class="{'rotate-180': selected === 4}"
                    viewBox="0 0 20 20" 
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div 
                id="shipping-faq-4" 
                x-show="selected === 4" 
                x-transition:enter="transition ease-out duration-200" 
                x-transition:enter-start="opacity-0 -translate-y-2" 
                x-transition:enter-end="opacity-100 translate-y-0" 
                x-transition:leave="transition ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0" 
                x-transition:leave-end="opacity-0 -translate-y-2" 
                class="p-5 pt-0 border-t border-gray-200 dark:border-gray-700"
            >
                <p class="text-gray-600 dark:text-gray-300">
                    Yes, we ship to many international destinations with some limitations:
                </p>
                <ul class="list-disc list-inside mt-3 space-y-2 text-gray-600 dark:text-gray-300">
                    <li><strong>Dry Goods:</strong> We ship equipment, supplies, and dry goods to most countries worldwide</li>
                    <li><strong>Live Specimens:</strong> International shipping of live specimens is available to select countries and requires proper import permits</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    <strong>Important International Shipping Notes:</strong>
                </p>
                <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                    <li>The customer is responsible for all import duties, taxes, and customs fees</li>
                    <li>Import permits must be provided by the customer before shipping live specimens</li>
                    <li>Some countries have restrictions on importing certain species</li>
                    <li>International shipping times vary by destination and customs processing</li>
                    <li>Live specimen shipping internationally requires special arrangements and additional fees</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    Please contact our customer service team before placing an international order for live specimens to ensure we can ship to your location and to discuss specific requirements.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 5 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 5 ? selected = 5 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 5 ? 'true' : 'false'"
                aria-controls="shipping-faq-5"
            >
                <span class="font-medium text-gray-800 dark:text-white">What days do you ship live specimens?</span>
                <svg 
                    xmlns="http://www.w3.org/2000/svg" 
                    class="h-5 w-5 text-gray-500 dark:text-gray-400 transition-transform" 
                    :class="{'rotate-180': selected === 5}"
                    viewBox="0 0 20 20" 
                    fill="currentColor"
                >
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>
            <div 
                id="shipping-faq-5" 
                x-show="selected === 5" 
                x-transition:enter="transition ease-out duration-200" 
                x-transition:enter-start="opacity-0 -translate-y-2" 
                x-transition:enter-end="opacity-100 translate-y-0" 
                x-transition:leave="transition ease-in duration-200" 
                x-transition:leave-start="opacity-100 translate-y-0" 
                x-transition:leave-end="opacity-0 -translate-y-2" 
                class="p-5 pt-0 border-t border-gray-200 dark:border-gray-700"
            >
                <p class="text-gray-600 dark:text-gray-300">
                    We ship live specimens (fish, invertebrates, and plants) on specific days to ensure they don't spend weekends in transit:
                </p>
                <ul class="list-disc list-inside mt-3 space-y-2 text-gray-600 dark:text-gray-300">
                    <li><strong>Monday - Wednesday:</strong> Primary shipping days for all live specimens</li>
                    <li><strong>Thursday:</strong> Limited shipping to nearby locations only (1-day transit zones)</li>
                    <li><strong>Friday - Sunday:</strong> No live specimen shipping</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    Orders containing live specimens placed after 12:00 PM EST will be processed the following business day. During extreme weather conditions (below 32°F or above 85°F in either your location or ours), we may hold shipments until conditions improve to ensure the safety of the specimens.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    Dry goods and equipment can be shipped any day of the week, regardless of weather conditions.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Alpine.js for FAQ accordion functionality -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>