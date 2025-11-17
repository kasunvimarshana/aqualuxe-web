<?php
/**
 * Template part for displaying the aquarium care FAQs on the FAQ page
 *
 * @package AquaLuxe
 */
?>

<section id="care" class="faq-section">
    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-teal-600 dark:text-teal-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
            </svg>
            Aquarium Care
        </h2>
    </div>
    
    <div class="space-y-6" x-data="{selected:null}">
        <!-- FAQ Item 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 1 ? selected = 1 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 1 ? 'true' : 'false'"
                aria-controls="care-faq-1"
            >
                <span class="font-medium text-gray-800 dark:text-white">How do I cycle a new aquarium?</span>
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
                id="care-faq-1" 
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
                    Cycling a new aquarium is essential for establishing beneficial bacteria that process toxic ammonia and nitrite. Here's a step-by-step guide to the nitrogen cycle:
                </p>
                <ol class="list-decimal list-inside mt-3 space-y-3 text-gray-600 dark:text-gray-300">
                    <li>
                        <strong>Set up your aquarium</strong> with all equipment (filter, heater, substrate, decorations) but no livestock
                    </li>
                    <li>
                        <strong>Add an ammonia source</strong> using one of these methods:
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Use a commercial ammonia solution (recommended)</li>
                            <li>Add a small amount of fish food to decompose</li>
                            <li>Use a small piece of raw seafood (remove after 24 hours)</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Test water parameters</strong> daily using a reliable test kit that measures:
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Ammonia (NH₃/NH₄⁺)</li>
                            <li>Nitrite (NO₂⁻)</li>
                            <li>Nitrate (NO₃⁻)</li>
                            <li>pH</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Monitor the cycle progression:</strong>
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>First 1-2 weeks: Ammonia rises then begins to fall</li>
                            <li>Weeks 2-4: Nitrite rises as ammonia falls</li>
                            <li>Weeks 3-6: Nitrate rises as nitrite falls</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Cycle is complete</strong> when ammonia and nitrite both read 0 ppm, and nitrate is present
                    </li>
                    <li>
                        <strong>Perform a water change</strong> (30-50%) to reduce nitrate levels before adding fish
                    </li>
                    <li>
                        <strong>Stock slowly</strong>, adding only a few fish at first and gradually increasing over several weeks
                    </li>
                </ol>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    The cycling process typically takes 4-6 weeks. You can speed up the process by using commercial bacterial starters or media from an established aquarium.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    <strong>Note:</strong> Never add fish to an uncycled aquarium as the ammonia and nitrite will be toxic to them.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 2 ? selected = 2 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 2 ? 'true' : 'false'"
                aria-controls="care-faq-2"
            >
                <span class="font-medium text-gray-800 dark:text-white">How often should I change my aquarium water?</span>
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
                id="care-faq-2" 
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
                    Water change frequency depends on several factors including tank size, stocking level, filtration, and aquarium type. Here are general guidelines:
                </p>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-600">
                            <tr>
                                <th class="py-2 px-4 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Aquarium Type</th>
                                <th class="py-2 px-4 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Frequency</th>
                                <th class="py-2 px-4 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Amount</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Freshwater (lightly stocked)</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Every 2 weeks</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">20-30%</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Freshwater (heavily stocked)</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Weekly</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">30-50%</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Planted tank</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Weekly</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">25-40%</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Reef tank</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Weekly</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">10-20%</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Fish-only marine</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Every 2 weeks</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">15-25%</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Breeding tanks</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">2-3 times weekly</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">30-50%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>Important water change tips:</strong>
                </p>
                <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                    <li>Always use a dechlorinator when adding tap water</li>
                    <li>Match temperature of new water to tank temperature (±2°F)</li>
                    <li>Vacuum the substrate during water changes to remove debris</li>
                    <li>Monitor water parameters regularly and adjust frequency as needed</li>
                    <li>More frequent, smaller water changes are better than infrequent large ones</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    Let water parameters guide your maintenance schedule. If nitrates exceed 20ppm in freshwater or 5ppm in saltwater, increase water change frequency or volume.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 3 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 3 ? selected = 3 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 3 ? 'true' : 'false'"
                aria-controls="care-faq-3"
            >
                <span class="font-medium text-gray-800 dark:text-white">How do I acclimate new fish to my aquarium?</span>
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
                id="care-faq-3" 
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
                    Proper acclimation is crucial for reducing stress and preventing shock when introducing new fish to your aquarium. We recommend the drip acclimation method for most species:
                </p>
                <ol class="list-decimal list-inside mt-3 space-y-3 text-gray-600 dark:text-gray-300">
                    <li>
                        <strong>Prepare your supplies:</strong>
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Clean bucket (3-5 gallons)</li>
                            <li>Air line tubing</li>
                            <li>Adjustable clip or valve for the tubing</li>
                            <li>Net</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Float the bag:</strong> Place the sealed fish bag in your aquarium for 15 minutes to equalize temperature
                    </li>
                    <li>
                        <strong>Transfer to bucket:</strong> Open the bag and gently pour the fish and shipping water into your clean bucket
                    </li>
                    <li>
                        <strong>Set up drip system:</strong>
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Place one end of the tubing in your aquarium</li>
                            <li>Start a siphon by sucking on the other end (or use a pump)</li>
                            <li>Adjust the flow rate to 2-4 drops per second using the clip/valve</li>
                            <li>Place the dripping end into the bucket with the fish</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Drip acclimate:</strong> Allow tank water to slowly drip into the bucket
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>For freshwater fish: 30-45 minutes (double the water volume)</li>
                            <li>For saltwater fish/invertebrates: 1-2 hours (triple the water volume)</li>
                            <li>For sensitive species: 2+ hours (quadruple the water volume)</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Transfer fish:</strong> Use a net to gently catch the fish and place them in your aquarium
                    </li>
                    <li>
                        <strong>Discard acclimation water:</strong> Do not add the shipping/acclimation water to your tank
                    </li>
                </ol>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    <strong>Special considerations:</strong>
                </p>
                <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                    <li>For very sensitive species (e.g., wild-caught specimens), extend acclimation time</li>
                    <li>For shrimp and invertebrates, match GH, KH, and TDS parameters closely</li>
                    <li>Keep lights off for 24 hours after introducing new fish to reduce stress</li>
                    <li>Monitor new additions closely for the first week</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    <strong>Note:</strong> Never release shipping water into your aquarium as it may contain medications, ammonia, or pathogens.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 4 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 4 ? selected = 4 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 4 ? 'true' : 'false'"
                aria-controls="care-faq-4"
            >
                <span class="font-medium text-gray-800 dark:text-white">What are the ideal water parameters for different aquarium types?</span>
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
                id="care-faq-4" 
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
                    Different aquarium types and species require specific water parameters. Here are the ideal ranges for common setups:
                </p>
                <div class="mt-4 overflow-x-auto">
                    <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden">
                        <thead class="bg-gray-100 dark:bg-gray-600">
                            <tr>
                                <th class="py-2 px-4 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Aquarium Type</th>
                                <th class="py-2 px-4 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Temperature</th>
                                <th class="py-2 px-4 text-left text-sm font-medium text-gray-700 dark:text-gray-200">pH</th>
                                <th class="py-2 px-4 text-left text-sm font-medium text-gray-700 dark:text-gray-200">GH</th>
                                <th class="py-2 px-4 text-left text-sm font-medium text-gray-700 dark:text-gray-200">KH</th>
                                <th class="py-2 px-4 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Ammonia/Nitrite</th>
                                <th class="py-2 px-4 text-left text-sm font-medium text-gray-700 dark:text-gray-200">Nitrate</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Community Freshwater</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">74-78°F</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">6.8-7.5</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">4-8 dGH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">3-8 dKH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">0 ppm</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">&lt;20 ppm</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">African Cichlid</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">76-82°F</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">7.8-8.6</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">12-20 dGH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">10-18 dKH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">0 ppm</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">&lt;20 ppm</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Discus/Angelfish</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">82-86°F</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">6.0-7.0</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">1-5 dGH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">1-3 dKH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">0 ppm</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">&lt;10 ppm</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Planted Tank</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">72-78°F</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">6.5-7.2</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">3-8 dGH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">2-5 dKH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">0 ppm</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">5-20 ppm</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Reef Tank</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">76-79°F</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">8.1-8.4</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">N/A</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">8-12 dKH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">0 ppm</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">&lt;5 ppm</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Saltwater Fish-Only</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">75-80°F</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">8.0-8.4</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">N/A</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">7-12 dKH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">0 ppm</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">&lt;10 ppm</td>
                            </tr>
                            <tr>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">Shrimp Tank</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">70-76°F</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">6.4-7.6</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">4-8 dGH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">2-5 dKH</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">0 ppm</td>
                                <td class="py-2 px-4 text-sm text-gray-600 dark:text-gray-300">&lt;10 ppm</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>Additional parameters for saltwater tanks:</strong>
                </p>
                <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                    <li><strong>Salinity:</strong> 1.023-1.025 for fish-only, 1.025-1.026 for reef</li>
                    <li><strong>Calcium:</strong> 380-450 ppm for reef tanks</li>
                    <li><strong>Magnesium:</strong> 1250-1350 ppm for reef tanks</li>
                    <li><strong>Phosphate:</strong> &lt;0.03 ppm for reef tanks</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    Always research the specific needs of your species, as some may require parameters outside these general ranges. Stability is often more important than achieving "perfect" parameters.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 5 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 5 ? selected = 5 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 5 ? 'true' : 'false'"
                aria-controls="care-faq-5"
            >
                <span class="font-medium text-gray-800 dark:text-white">How do I diagnose and treat common fish diseases?</span>
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
                id="care-faq-5" 
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
                    Early detection and proper treatment are crucial for fish health. Here's a guide to common diseases and their treatments:
                </p>
                <div class="mt-4 space-y-6">
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Ich (White Spot Disease)</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            <strong>Symptoms:</strong> White salt-like spots on fins and body, flashing (rubbing against objects), clamped fins, lethargy
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            <strong>Treatment:</strong> Raise temperature to 86°F (if fish can tolerate it) and treat with ich-specific medication containing malachite green or formalin. Remove carbon filtration during treatment. Complete the full treatment cycle even if spots disappear.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Fin Rot</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            <strong>Symptoms:</strong> Frayed, discolored fin edges, receding fins, sometimes with white edges
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            <strong>Treatment:</strong> Improve water quality with water changes, treat with antibacterial medication containing erythromycin or tetracycline. Address any aggression issues if fin nipping is occurring.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Velvet (Oodinium)</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            <strong>Symptoms:</strong> Gold/rust colored "dust" on body, rapid breathing, scratching, loss of appetite
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            <strong>Treatment:</strong> Copper-based medications (freshwater and marine), dim lights during treatment as the parasite is photosynthetic. Complete full treatment cycle.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Dropsy</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            <strong>Symptoms:</strong> Swollen body, scales protruding outward ("pinecone" appearance), lethargy, loss of appetite
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            <strong>Treatment:</strong> Isolate affected fish, treat with broad-spectrum antibiotics, add Epsom salt (1 tbsp per 5 gallons). Dropsy is often a symptom of internal organ failure and can be difficult to cure once advanced.
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Swim Bladder Disorder</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            <strong>Symptoms:</strong> Difficulty swimming upright, floating at surface, sinking to bottom, swimming sideways
                        </p>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            <strong>Treatment:</strong> Fast fish for 24-48 hours, then feed blanched peas (for herbivores/omnivores) or daphnia (for carnivores). Treat with antibiotics if bacterial infection is suspected. Epsom salt baths can help.
                        </p>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>General disease prevention tips:</strong>
                </p>
                <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                    <li>Maintain excellent water quality with regular testing and water changes</li>
                    <li>Quarantine all new fish for 2-4 weeks before adding to main tank</li>
                    <li>Provide proper nutrition with varied diet</li>
                    <li>Avoid overcrowding</li>
                    <li>Maintain stable water parameters</li>
                    <li>Sterilize equipment between tanks to prevent cross-contamination</li>
                </ul>
                <p class="text-gray-600 dark:text-gray-300 mt-3">
                    <strong>Note:</strong> Always remove carbon filtration during medication treatments, and follow medication instructions carefully. Some medications are not safe for all species or tank types (especially invertebrates, plants, and beneficial bacteria).
                </p>
            </div>
        </div>
    </div>
</section>