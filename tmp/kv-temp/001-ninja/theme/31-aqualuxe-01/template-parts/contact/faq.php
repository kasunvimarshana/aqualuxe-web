<?php
/**
 * Template part for displaying the FAQ section on the Contact page
 *
 * @package AquaLuxe
 */
?>

<section class="py-12">
    <div class="container mx-auto px-4">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Frequently Asked Questions</h2>
            <p class="text-gray-600 dark:text-gray-300 mt-2">
                Find quick answers to common questions about our services and products.
            </p>
        </div>
        
        <div class="max-w-4xl mx-auto">
            <div class="space-y-6" x-data="{selected:null}">
                <!-- FAQ Item 1 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <button 
                        @click="selected !== 1 ? selected = 1 : selected = null"
                        class="flex items-center justify-between w-full p-5 text-left"
                        :aria-expanded="selected === 1 ? 'true' : 'false'"
                        aria-controls="faq-1"
                    >
                        <span class="font-medium text-gray-800 dark:text-white text-lg">What are your shipping rates and policies?</span>
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
                        id="faq-1" 
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
                            We offer free shipping on all orders over $75 within the continental United States. For orders under $75, shipping rates start at $7.95. International shipping is available to select countries with rates calculated at checkout based on weight and destination. All orders are processed within 1-2 business days and typically arrive within 3-7 business days depending on your location.
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 mt-3">
                            For live fish and plants, we use expedited shipping methods to ensure they arrive in excellent condition. Additional shipping fees may apply for these items.
                        </p>
                        <a href="#" class="inline-block mt-4 text-teal-600 dark:text-teal-400 hover:underline">View our full shipping policy</a>
                    </div>
                </div>
                
                <!-- FAQ Item 2 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <button 
                        @click="selected !== 2 ? selected = 2 : selected = null"
                        class="flex items-center justify-between w-full p-5 text-left"
                        :aria-expanded="selected === 2 ? 'true' : 'false'"
                        aria-controls="faq-2"
                    >
                        <span class="font-medium text-gray-800 dark:text-white text-lg">How do I schedule a maintenance service?</span>
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
                        id="faq-2" 
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
                            Scheduling a maintenance service is easy! You can:
                        </p>
                        <ul class="list-disc list-inside mt-3 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>Fill out our contact form above and select "Maintenance Services" as your inquiry type</li>
                            <li>Call us directly at (555) 123-4567 during business hours</li>
                            <li>Email us at service@aqualuxe.com with details about your aquarium and maintenance needs</li>
                        </ul>
                        <p class="text-gray-600 dark:text-gray-300 mt-3">
                            Our team will respond within 24 hours to discuss your needs, provide a quote, and schedule your first service appointment. We offer flexible scheduling options including weekly, bi-weekly, and monthly maintenance plans.
                        </p>
                    </div>
                </div>
                
                <!-- FAQ Item 3 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <button 
                        @click="selected !== 3 ? selected = 3 : selected = null"
                        class="flex items-center justify-between w-full p-5 text-left"
                        :aria-expanded="selected === 3 ? 'true' : 'false'"
                        aria-controls="faq-3"
                    >
                        <span class="font-medium text-gray-800 dark:text-white text-lg">Do you offer custom aquarium design services?</span>
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
                        id="faq-3" 
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
                            Yes, we specialize in custom aquarium design for both residential and commercial spaces. Our design services include:
                        </p>
                        <ul class="list-disc list-inside mt-3 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>Custom tank sizing and configuration</li>
                            <li>Specialized aquascaping and hardscape design</li>
                            <li>Custom cabinetry and furniture</li>
                            <li>Advanced filtration and life support systems</li>
                            <li>Specialized lighting solutions</li>
                            <li>Integration with home automation systems</li>
                        </ul>
                        <p class="text-gray-600 dark:text-gray-300 mt-3">
                            Our design process begins with a consultation to understand your vision, space, and requirements. We then create detailed designs and proposals before moving to installation and setup.
                        </p>
                        <a href="#" class="inline-block mt-4 text-teal-600 dark:text-teal-400 hover:underline">View our design portfolio</a>
                    </div>
                </div>
                
                <!-- FAQ Item 4 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <button 
                        @click="selected !== 4 ? selected = 4 : selected = null"
                        class="flex items-center justify-between w-full p-5 text-left"
                        :aria-expanded="selected === 4 ? 'true' : 'false'"
                        aria-controls="faq-4"
                    >
                        <span class="font-medium text-gray-800 dark:text-white text-lg">What is your return and exchange policy?</span>
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
                        id="faq-4" 
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
                            We accept returns of unused, unopened merchandise within 30 days of purchase with original receipt. A refund will be issued to the original payment method. For defective items, we offer replacements or refunds within 60 days of purchase.
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 mt-3">
                            <strong>Special considerations:</strong>
                        </p>
                        <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>Live fish, invertebrates, and plants have a 7-day guarantee (with water parameter verification)</li>
                            <li>Electrical equipment has a 30-day return window and is covered by manufacturer warranties</li>
                            <li>Custom-ordered items are non-returnable unless defective</li>
                            <li>Sale items may have modified return policies as noted at time of purchase</li>
                        </ul>
                        <a href="#" class="inline-block mt-4 text-teal-600 dark:text-teal-400 hover:underline">View our full return policy</a>
                    </div>
                </div>
                
                <!-- FAQ Item 5 -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <button 
                        @click="selected !== 5 ? selected = 5 : selected = null"
                        class="flex items-center justify-between w-full p-5 text-left"
                        :aria-expanded="selected === 5 ? 'true' : 'false'"
                        aria-controls="faq-5"
                    >
                        <span class="font-medium text-gray-800 dark:text-white text-lg">Do you ship live fish and plants internationally?</span>
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
                        id="faq-5" 
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
                            Yes, we ship live fish, invertebrates, and plants to select international destinations. International shipping of live specimens requires:
                        </p>
                        <ul class="list-disc list-inside mt-3 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>Proper import permits from your country (customer responsibility)</li>
                            <li>Compliance with CITES regulations for protected species</li>
                            <li>Special shipping arrangements with expedited delivery</li>
                            <li>Additional packaging and handling fees</li>
                        </ul>
                        <p class="text-gray-600 dark:text-gray-300 mt-3">
                            Due to the complexity of international shipping for live specimens, please contact us directly to discuss your specific needs and destination country requirements. We'll work with you to ensure all necessary documentation and shipping arrangements are in place.
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 mt-3">
                            For dry goods and equipment, we ship to most countries worldwide through our standard international shipping options available at checkout.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="mt-10 text-center">
                <p class="text-gray-600 dark:text-gray-300">
                    Can't find the answer you're looking for?
                </p>
                <a href="#contact-form" class="inline-block mt-4 bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-6 rounded-lg transition duration-300">
                    Contact Our Support Team
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Alpine.js for FAQ accordion functionality -->
<script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>