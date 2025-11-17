<?php
/**
 * Template part for displaying the purchasing FAQs on the FAQ page
 *
 * @package AquaLuxe
 */
?>

<section id="purchasing" class="faq-section">
    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            Purchasing
        </h2>
    </div>
    
    <div class="space-y-6" x-data="{selected:null}">
        <!-- FAQ Item 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 1 ? selected = 1 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 1 ? 'true' : 'false'"
                aria-controls="purchasing-faq-1"
            >
                <span class="font-medium text-gray-800 dark:text-white">What payment methods do you accept?</span>
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
                id="purchasing-faq-1" 
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
                    We accept a variety of payment methods to make your shopping experience convenient:
                </p>
                <div class="mt-4 grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-center">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-400 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M2.5 3C1.67 3 1 3.67 1 4.5V19.5C1 20.33 1.67 21 2.5 21H21.5C22.33 21 23 20.33 23 19.5V4.5C23 3.67 22.33 3 21.5 3H2.5ZM2.5 4H21.5C21.78 4 22 4.22 22 4.5V19.5C22 19.78 21.78 20 21.5 20H2.5C2.22 20 2 19.78 2 19.5V4.5C2 4.22 2.22 4 2.5 4ZM5 7C4.45 7 4 7.45 4 8V16C4 16.55 4.45 17 5 17H19C19.55 17 20 16.55 20 16V8C20 7.45 19.55 7 19 7H5ZM5 8H19V16H5V8ZM6 9.5C6 9.22 6.22 9 6.5 9H7.5C7.78 9 8 9.22 8 9.5V10.5C8 10.78 7.78 11 7.5 11H6.5C6.22 11 6 10.78 6 10.5V9.5ZM9 9.5C9 9.22 9.22 9 9.5 9H10.5C10.78 9 11 9.22 11 9.5V10.5C11 10.78 10.78 11 10.5 11H9.5C9.22 11 9 10.78 9 10.5V9.5ZM12 9.5C12 9.22 12.22 9 12.5 9H13.5C13.78 9 14 9.22 14 9.5V10.5C14 10.78 13.78 11 13.5 11H12.5C12.22 11 12 10.78 12 10.5V9.5ZM15 9.5C15 9.22 15.22 9 15.5 9H16.5C16.78 9 17 9.22 17 9.5V10.5C17 10.78 16.78 11 16.5 11H15.5C15.22 11 15 10.78 15 10.5V9.5ZM6 12.5C6 12.22 6.22 12 6.5 12H7.5C7.78 12 8 12.22 8 12.5V13.5C8 13.78 7.78 14 7.5 14H6.5C6.22 14 6 13.78 6 13.5V12.5ZM9 12.5C9 12.22 9.22 12 9.5 12H10.5C10.78 12 11 12.22 11 12.5V13.5C11 13.78 10.78 14 10.5 14H9.5C9.22 14 9 13.78 9 13.5V12.5ZM12 12.5C12 12.22 12.22 12 12.5 12H13.5C13.78 12 14 12.22 14 12.5V13.5C14 13.78 13.78 14 13.5 14H12.5C12.22 14 12 13.78 12 13.5V12.5ZM15 12.5C15 12.22 15.22 12 15.5 12H16.5C16.78 12 17 12.22 17 12.5V13.5C17 13.78 16.78 14 16.5 14H15.5C15.22 14 15 13.78 15 13.5V12.5Z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Credit Cards</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-center">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-400 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M19.14,12.94c0.04-0.3,0.06-0.61,0.06-0.94c0-0.32-0.02-0.64-0.07-0.94l2.03-1.58c0.18-0.14,0.23-0.41,0.12-0.61 l-1.92-3.32c-0.12-0.22-0.37-0.29-0.59-0.22l-2.39,0.96c-0.5-0.38-1.03-0.7-1.62-0.94L14.4,2.81c-0.04-0.24-0.24-0.41-0.48-0.41 h-3.84c-0.24,0-0.43,0.17-0.47,0.41L9.25,5.35C8.66,5.59,8.12,5.92,7.63,6.29L5.24,5.33c-0.22-0.08-0.47,0-0.59,0.22L2.74,8.87 C2.62,9.08,2.66,9.34,2.86,9.48l2.03,1.58C4.84,11.36,4.8,11.69,4.8,12s0.02,0.64,0.07,0.94l-2.03,1.58 c-0.18,0.14-0.23,0.41-0.12,0.61l1.92,3.32c0.12,0.22,0.37,0.29,0.59,0.22l2.39-0.96c0.5,0.38,1.03,0.7,1.62,0.94l0.36,2.54 c0.05,0.24,0.24,0.41,0.48,0.41h3.84c0.24,0,0.44-0.17,0.47-0.41l0.36-2.54c0.59-0.24,1.13-0.56,1.62-0.94l2.39,0.96 c0.22,0.08,0.47,0,0.59-0.22l1.92-3.32c0.12-0.22,0.07-0.47-0.12-0.61L19.14,12.94z M12,15.6c-1.98,0-3.6-1.62-3.6-3.6 s1.62-3.6,3.6-3.6s3.6,1.62,3.6,3.6S13.98,15.6,12,15.6z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">PayPal</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-center">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-400 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M17.5,12C17.5,8.41,14.59,5.5,11,5.5C7.41,5.5,4.5,8.41,4.5,12S7.41,18.5,11,18.5C14.59,18.5,17.5,15.59,17.5,12M11,20A8,8 0 0,1 3,12A8,8 0 0,1 11,4A8,8 0 0,1 19,12A8,8 0 0,1 11,20M15,9.5C15,10.33 14.33,11 13.5,11C12.67,11 12,10.33 12,9.5C12,8.67 12.67,8 13.5,8C14.33,8 15,8.67 15,9.5M9,9.5C9,10.33 8.33,11 7.5,11C6.67,11 6,10.33 6,9.5C6,8.67 6.67,8 7.5,8C8.33,8 9,8.67 9,9.5M11,14C13.5,14 15.11,13.5 16,12.5C15.67,13.5 14,15 11,15C8,15 6.33,13.5 6,12.5C6.89,13.5 8.5,14 11,14Z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Apple Pay</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-center">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-400 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12,2A10,10 0 0,1 22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2M12,4A8,8 0 0,0 4,12A8,8 0 0,0 12,20A8,8 0 0,0 20,12A8,8 0 0,0 12,4M11,17V16H9V14H13V13H10A1,1 0 0,1 9,12V9A1,1 0 0,1 10,8H11V7H13V8H15V10H11V11H14A1,1 0 0,1 15,12V15A1,1 0 0,1 14,16H13V17H11Z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Google Pay</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-center">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-400 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M20,8H4V6H20M20,18H4V12H20M20,4H4C2.89,4 2,4.89 2,6V18A2,2 0 0,0 4,20H20A2,2 0 0,0 22,18V6C22,4.89 21.1,4 20,4Z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Debit Cards</span>
                    </div>
                    <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg flex items-center">
                        <svg class="h-8 w-8 text-blue-600 dark:text-blue-400 mr-3" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12.89,3L14.85,3.4L11.11,21L9.15,20.6L12.89,3M19.59,12L16,8.41V5.58L22.42,12L16,18.41V15.58L19.59,12M1.58,12L8,5.58V8.41L4.41,12L8,15.58V18.41L1.58,12Z" />
                        </svg>
                        <span class="text-gray-700 dark:text-gray-300">Bank Transfer</span>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>Security:</strong> All payment information is encrypted using industry-standard SSL technology. We do not store your credit card information on our servers.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    <strong>International Payments:</strong> For international orders, we accept credit cards, PayPal, and wire transfers. Please note that your bank may charge additional foreign transaction fees.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    <strong>Purchase Orders:</strong> We accept purchase orders from educational institutions, government agencies, and approved businesses. Please contact our business sales department for more information.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 2 ? selected = 2 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 2 ? 'true' : 'false'"
                aria-controls="purchasing-faq-2"
            >
                <span class="font-medium text-gray-800 dark:text-white">What is your return and exchange policy?</span>
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
                id="purchasing-faq-2" 
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
                    We want you to be completely satisfied with your purchase. Our return and exchange policy is designed to be fair and straightforward:
                </p>
                <div class="mt-4 space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Dry Goods (Equipment, Supplies, Accessories)</h4>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>30-day return window from date of delivery</li>
                            <li>Items must be unused, unopened, and in original packaging</li>
                            <li>Original receipt or proof of purchase required</li>
                            <li>Refunds issued to original payment method</li>
                            <li>Return shipping costs are customer's responsibility unless item is defective</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Electrical Equipment</h4>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>30-day return window for unused items</li>
                            <li>Defective items can be returned within 60 days</li>
                            <li>Manufacturer warranty applies after return period (typically 1-3 years)</li>
                            <li>We'll help facilitate warranty claims with manufacturers</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Live Fish, Invertebrates, and Plants</h4>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>7-day guarantee on live arrival</li>
                            <li>DOA (Dead On Arrival) must be reported with photos within 2 hours of delivery</li>
                            <li>Water parameters must be verified and within acceptable ranges</li>
                            <li>Proper acclimation procedures must be followed</li>
                            <li>Store credit or replacement offered for qualifying losses</li>
                            <li>No returns on live specimens after successful acclimation</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Special Order Items</h4>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>Custom-ordered items are non-returnable unless defective</li>
                            <li>Special order livestock has the same 7-day guarantee as regular stock</li>
                            <li>50% deposit required for special orders, non-refundable if order is canceled</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Sale Items</h4>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>Items marked as "Final Sale" cannot be returned</li>
                            <li>Regular sale items follow standard return policy unless otherwise noted</li>
                        </ul>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>How to Initiate a Return:</strong> Contact our customer service team within the applicable return window by email at returns@aqualuxe.com or by phone at (555) 123-4567. You'll receive a Return Merchandise Authorization (RMA) number and detailed instructions.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    <strong>Restocking Fees:</strong> A 15% restocking fee may apply to returned items that are not defective or incorrectly shipped.
                </p>
                <a href="#" class="inline-block mt-4 text-teal-600 dark:text-teal-400 hover:underline">View our full return policy</a>
            </div>
        </div>
        
        <!-- FAQ Item 3 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 3 ? selected = 3 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 3 ? 'true' : 'false'"
                aria-controls="purchasing-faq-3"
            >
                <span class="font-medium text-gray-800 dark:text-white">Do you offer price matching?</span>
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
                id="purchasing-faq-3" 
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
                    Yes, we offer price matching on identical products from authorized retailers. Our price match policy helps ensure you get the best value while still receiving our exceptional service and guarantees.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    <strong>Price Match Eligibility Requirements:</strong>
                </p>
                <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                    <li>The competitor must be an authorized retailer of the product</li>
                    <li>The item must be identical (same brand, model, size, color, etc.)</li>
                    <li>The item must be in stock at both our store and the competitor</li>
                    <li>The price must include all costs (shipping, handling, taxes, etc.)</li>
                    <li>Price match requests must be made before purchase or within 14 days after purchase</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    <strong>Exclusions:</strong>
                </p>
                <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                    <li>Marketplace sellers (Amazon Marketplace, eBay, etc.)</li>
                    <li>Auction sites or liquidation sales</li>
                    <li>Pricing errors or misprints</li>
                    <li>Limited quantity offers, flash sales, or doorbusters</li>
                    <li>Clearance, open-box, refurbished, or used items</li>
                    <li>Competitors' loyalty program pricing or member-only deals</li>
                    <li>Bundle offers or free gift promotions</li>
                    <li>Live animals, plants, and special order items</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    <strong>How to Request a Price Match:</strong>
                </p>
                <ol class="list-decimal list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                    <li>Contact our customer service team via phone, email, or live chat</li>
                    <li>Provide the competitor's name and a link to the product page showing the current price</li>
                    <li>For in-store purchases, bring proof of the competitor's current price</li>
                    <li>For post-purchase price matches (within 14 days), provide your order number</li>
                </ol>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    We reserve the right to verify competitor pricing and availability before approving price match requests. Our customer service team will review all requests and make the final determination on eligibility.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 4 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 4 ? selected = 4 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 4 ? 'true' : 'false'"
                aria-controls="purchasing-faq-4"
            >
                <span class="font-medium text-gray-800 dark:text-white">Do you offer warranties on products?</span>
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
                id="purchasing-faq-4" 
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
                    All products sold by AquaLuxe come with warranty coverage, which varies by product type and manufacturer:
                </p>
                <div class="mt-4 space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Manufacturer Warranties</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            Most equipment comes with manufacturer warranties ranging from 1-5 years depending on the brand and product type. These warranties cover defects in materials and workmanship under normal use.
                        </p>
                        <div class="mt-3 overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-600">
                                    <tr>
                                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200">Product Category</th>
                                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200">Typical Warranty Period</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Filters & Pumps</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">2-3 years</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Lighting Systems</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">1-3 years</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Heaters</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">1-2 years</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Controllers & Monitors</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">1-5 years</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Aquariums & Stands</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Limited lifetime (tanks), 1-3 years (stands)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">AquaLuxe Extended Protection Plans</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            We offer optional extended protection plans for most electronic equipment, providing coverage beyond the manufacturer's warranty:
                        </p>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>1-year extension: 10% of product price</li>
                            <li>2-year extension: 15% of product price</li>
                            <li>3-year extension: 20% of product price</li>
                        </ul>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">
                            Extended protection plans cover parts and labor for repairs, with no deductibles or hidden fees. They also include power surge protection and accidental damage coverage.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Live Arrival Guarantee</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            All live fish, invertebrates, and plants come with our 7-day live arrival guarantee. If any issues occur, please take photos and contact us within 2 hours of delivery. See our <a href="#" class="text-teal-600 dark:text-teal-400 hover:underline">Live Arrival Policy</a> for full details.
                        </p>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>Warranty Claims Process:</strong>
                </p>
                <ol class="list-decimal list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                    <li>Contact our customer service team with your order number and product information</li>
                    <li>Provide a description of the issue and photos/videos if applicable</li>
                    <li>Our team will guide you through the warranty process, which may involve:
                        <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                            <li>Direct replacement from our inventory</li>
                            <li>Return for repair</li>
                            <li>Manufacturer warranty claim assistance</li>
                        </ul>
                    </li>
                </ol>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    We strive to make the warranty process as smooth as possible. For high-value equipment, we may offer advance replacement options to minimize downtime for your aquarium system.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 5 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 5 ? selected = 5 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 5 ? 'true' : 'false'"
                aria-controls="purchasing-faq-5"
            >
                <span class="font-medium text-gray-800 dark:text-white">How do I track my order?</span>
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
                id="purchasing-faq-5" 
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
                    Tracking your order is easy with our comprehensive order management system. Here's how to stay updated on your purchase:
                </p>
                <div class="mt-4 space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Order Confirmation</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            Immediately after placing your order, you'll receive an order confirmation email with:
                        </p>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>Order number</li>
                            <li>Order summary</li>
                            <li>Estimated shipping date</li>
                            <li>Link to your account order history</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Online Order Tracking</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            You can track your order through your AquaLuxe account:
                        </p>
                        <ol class="list-decimal list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>Log in to your account at aqualuxe.com</li>
                            <li>Navigate to "My Orders" in your account dashboard</li>
                            <li>Select the order you want to track</li>
                            <li>View detailed order status, including:
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Processing status</li>
                                    <li>Shipping information</li>
                                    <li>Tracking number(s)</li>
                                    <li>Estimated delivery date</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Shipping Notifications</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            You'll receive email notifications at key stages of the shipping process:
                        </p>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>Order processing confirmation</li>
                            <li>Shipment confirmation with tracking number(s)</li>
                            <li>Delivery confirmation (for most carriers)</li>
                        </ul>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">
                            For live specimen orders, you'll receive additional notifications:
                        </p>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>Pre-shipment notification (1-2 days before shipping)</li>
                            <li>Shipping day confirmation with estimated delivery time</li>
                            <li>Delivery day reminder with acclimation instructions</li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Carrier Tracking</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            Once your order ships, you can track it directly through the carrier's website using the provided tracking number. We ship with:
                        </p>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>FedEx</li>
                            <li>UPS</li>
                            <li>USPS</li>
                            <li>DHL (international orders)</li>
                        </ul>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>Don't have an account?</strong> You can still track your order using the order number and email address provided during checkout. Visit our <a href="#" class="text-teal-600 dark:text-teal-400 hover:underline">Order Lookup</a> page.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    <strong>Need assistance?</strong> Our customer service team is available to help with any tracking inquiries. Contact us at (555) 123-4567 or support@aqualuxe.com with your order number ready.
                </p>
            </div>
        </div>
    </div>
</section>