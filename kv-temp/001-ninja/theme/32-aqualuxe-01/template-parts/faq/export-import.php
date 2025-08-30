<?php
/**
 * Template part for displaying the export and import FAQs on the FAQ page
 *
 * @package AquaLuxe
 */
?>

<section id="export-import" class="faq-section">
    <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-amber-600 dark:text-amber-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Export & Import
        </h2>
    </div>
    
    <div class="space-y-6" x-data="{selected:null}">
        <!-- FAQ Item 1 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 1 ? selected = 1 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 1 ? 'true' : 'false'"
                aria-controls="export-import-faq-1"
            >
                <span class="font-medium text-gray-800 dark:text-white">What permits are required for importing aquatic species?</span>
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
                id="export-import-faq-1" 
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
                    Importing aquatic species requires compliance with various regulations and permits depending on the species, country of origin, and destination. Here's a comprehensive overview of the permits typically required:
                </p>
                <div class="mt-4 space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">United States Import Requirements</h4>
                        <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>U.S. Fish and Wildlife Service (USFWS) Import Permit:</strong> Required for most fish and aquatic species. You'll need to file a Declaration for Importation (Form 3-177) and may need additional permits for certain species.
                            </li>
                            <li>
                                <strong>CITES Permit:</strong> Required for species listed in the Convention on International Trade in Endangered Species of Wild Fauna and Flora. There are three appendices with different levels of protection:
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Appendix I: Species threatened with extinction - commercial trade generally prohibited</li>
                                    <li>Appendix II: Species not necessarily threatened with extinction but requiring controlled trade</li>
                                    <li>Appendix III: Species protected in at least one country that has asked for assistance in controlling trade</li>
                                </ul>
                            </li>
                            <li>
                                <strong>USDA Animal and Plant Health Inspection Service (APHIS) Permits:</strong> May be required for certain aquatic plants and animals to prevent the introduction of pests and diseases.
                            </li>
                            <li>
                                <strong>Lacey Act Compliance:</strong> Prohibits trade in wildlife, fish, and plants that have been illegally taken, possessed, transported, or sold.
                            </li>
                            <li>
                                <strong>Injurious Wildlife Permit:</strong> Required for species listed as injurious under the Lacey Act, which includes certain fish like snakeheads and walking catfish.
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">International Considerations</h4>
                        <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>Export Permits from Country of Origin:</strong> Most countries require export permits for native species, especially for wild-caught specimens.
                            </li>
                            <li>
                                <strong>Health Certificates:</strong> Many countries require health certificates issued by authorized veterinarians or government agencies in the exporting country.
                            </li>
                            <li>
                                <strong>Quarantine Requirements:</strong> Some countries require imported aquatic species to undergo quarantine upon arrival.
                            </li>
                            <li>
                                <strong>Country-Specific Regulations:</strong> Each country has its own set of regulations regarding which species can be imported and under what conditions.
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Prohibited Species</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            Certain species are prohibited from import into many countries due to their invasive potential or other concerns:
                        </p>
                        <ul class="list-disc list-inside mt-2 space-y-1 text-gray-600 dark:text-gray-300">
                            <li>Snakehead fish (family Channidae)</li>
                            <li>Walking catfish (Clarias batrachus)</li>
                            <li>Certain carp species</li>
                            <li>Certain crayfish species</li>
                            <li>Zebra mussels and other invasive mollusks</li>
                            <li>Certain aquatic plants like hydrilla and water hyacinth</li>
                        </ul>
                        <p class="text-gray-600 dark:text-gray-300 mt-2">
                            The list of prohibited species varies by country and is subject to change as new invasive threats are identified.
                        </p>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>AquaLuxe Import Assistance:</strong> We offer import assistance services for customers looking to import specific species. Our team can help navigate the complex permit requirements and ensure compliance with all regulations. Contact our specialist team at imports@aqualuxe.com for more information.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    <strong>Important Note:</strong> Regulations and permit requirements change frequently. Always check with the relevant authorities for the most up-to-date information before planning any import of aquatic species.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 2 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 2 ? selected = 2 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 2 ? 'true' : 'false'"
                aria-controls="export-import-faq-2"
            >
                <span class="font-medium text-gray-800 dark:text-white">How do I export aquatic species internationally?</span>
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
                id="export-import-faq-2" 
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
                    Exporting aquatic species internationally involves several steps to ensure compliance with both domestic and international regulations. Here's a step-by-step guide:
                </p>
                <ol class="list-decimal list-inside mt-3 space-y-3 text-gray-600 dark:text-gray-300">
                    <li>
                        <strong>Research Destination Country Requirements:</strong> Before planning any export, research the import requirements of the destination country. Each country has specific regulations regarding:
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Permitted species</li>
                            <li>Quarantine requirements</li>
                            <li>Health certification needs</li>
                            <li>Import permits that the recipient must obtain</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Obtain Export Permits:</strong> In the United States, you'll need:
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>USFWS Export Declaration (Form 3-177)</li>
                            <li>CITES permits for protected species</li>
                            <li>USDA health certificates if required by the destination country</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Prepare Proper Documentation:</strong> Ensure you have:
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Commercial invoice with detailed species information (scientific names, quantities, values)</li>
                            <li>Packing list</li>
                            <li>Health certificates</li>
                            <li>CITES documentation (if applicable)</li>
                            <li>Certificate of origin</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Arrange Proper Shipping:</strong>
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Use an experienced live animal shipper with international experience</li>
                            <li>Ensure proper packaging that meets IATA Live Animal Regulations</li>
                            <li>Include appropriate water volume, oxygen, insulation, and temperature control</li>
                            <li>Label packages properly with "Live Animals" markings</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Coordinate with the Recipient:</strong>
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Ensure they have obtained any necessary import permits</li>
                            <li>Confirm they're prepared for customs clearance</li>
                            <li>Verify they understand any quarantine requirements</li>
                            <li>Provide tracking information and estimated arrival time</li>
                        </ul>
                    </li>
                    <li>
                        <strong>Customs Clearance:</strong>
                        <ul class="list-disc list-inside ml-6 mt-2 space-y-1">
                            <li>Submit all required documentation to customs</li>
                            <li>Pay any applicable duties and taxes</li>
                            <li>Arrange for inspection by relevant authorities</li>
                        </ul>
                    </li>
                </ol>
                <div class="mt-4 bg-amber-50 dark:bg-amber-900/30 border-l-4 border-amber-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-amber-700 dark:text-amber-200">
                                <strong>Important:</strong> Exporting without proper permits can result in significant fines, confiscation of shipments, and even criminal charges. Always ensure full compliance with all regulations.
                            </p>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>AquaLuxe Export Services:</strong> We offer comprehensive export services for customers looking to ship internationally. Our team handles all documentation, packaging, and coordination with authorities to ensure smooth export processes. Contact our export team at exports@aqualuxe.com for assistance.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 3 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 3 ? selected = 3 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 3 ? 'true' : 'false'"
                aria-controls="export-import-faq-3"
            >
                <span class="font-medium text-gray-800 dark:text-white">What are CITES regulations and how do they affect aquarium trade?</span>
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
                id="export-import-faq-3" 
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
                    CITES (Convention on International Trade in Endangered Species of Wild Fauna and Flora) is an international agreement between governments that regulates the international trade of wild animal and plant species to ensure their survival is not threatened. Here's how CITES affects the aquarium trade:
                </p>
                <div class="mt-4 space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">CITES Appendices</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            CITES categorizes species into three appendices based on their conservation status:
                        </p>
                        <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>Appendix I:</strong> Includes species threatened with extinction. Commercial international trade is generally prohibited, with rare exceptions requiring both import and export permits.
                            </li>
                            <li>
                                <strong>Appendix II:</strong> Includes species not necessarily threatened with extinction but requiring controlled trade to prevent utilization incompatible with their survival. Export permits are required, and for some countries, import permits as well.
                            </li>
                            <li>
                                <strong>Appendix III:</strong> Includes species protected in at least one country that has asked other CITES parties for assistance in controlling trade. Export permits are required from the country that listed the species.
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">CITES-Listed Aquatic Species</h4>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">
                            Several aquatic species popular in the aquarium trade are CITES-listed, including:
                        </p>
                        <div class="mt-3 overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-600">
                                    <tr>
                                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200">Species</th>
                                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200">CITES Appendix</th>
                                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200">Trade Restrictions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">All seahorses (Hippocampus spp.)</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Appendix II</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Export permit required</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">All stony corals (Order Scleractinia)</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Appendix II</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Export permit required</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Giant clams (Tridacnidae spp.)</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Appendix II</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Export permit required</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Asian arowana (Scleropages formosus)</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Appendix I</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Commercial trade prohibited (except certified captive-bred specimens)</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Certain freshwater stingrays (Potamotrygon spp.)</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Appendix III (Brazil)</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Export permit required from Brazil</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Impact on Aquarium Trade</h4>
                        <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>Documentation Requirements:</strong> Traders must obtain proper CITES permits for listed species, which can be time-consuming and costly.
                            </li>
                            <li>
                                <strong>Trade Restrictions:</strong> Some species may be completely prohibited from commercial trade, while others require strict documentation of sustainable harvesting.
                            </li>
                            <li>
                                <strong>Captive Breeding Incentives:</strong> CITES regulations have encouraged captive breeding programs for many species, reducing pressure on wild populations.
                            </li>
                            <li>
                                <strong>Enforcement and Penalties:</strong> Violations of CITES regulations can result in confiscation of shipments, significant fines, and even criminal charges.
                            </li>
                            <li>
                                <strong>Price Impact:</strong> The additional costs of compliance with CITES regulations often increase the price of regulated species in the aquarium trade.
                            </li>
                        </ul>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>AquaLuxe Commitment:</strong> We are fully committed to CITES compliance and sustainable trade practices. All CITES-listed species we sell are legally acquired with proper documentation. For certain species, we prioritize captive-bred specimens to reduce pressure on wild populations.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    <strong>Verification:</strong> When purchasing CITES-listed species from us, you can request to see the relevant CITES documentation. For international orders, we'll guide you through any additional requirements for importing these species to your country.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    <strong>Note:</strong> CITES listings are periodically updated. For the most current information, visit the <a href="https://cites.org/eng/app/appendices.php" target="_blank" rel="noopener noreferrer" class="text-teal-600 dark:text-teal-400 hover:underline">official CITES website</a>.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 4 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 4 ? selected = 4 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 4 ? 'true' : 'false'"
                aria-controls="export-import-faq-4"
            >
                <span class="font-medium text-gray-800 dark:text-white">How do I prepare aquatic species for international shipping?</span>
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
                id="export-import-faq-4" 
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
                    Properly preparing aquatic species for international shipping is crucial to ensure they arrive at their destination alive and healthy. Here's a comprehensive guide to the preparation process:
                </p>
                <div class="mt-4 space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Pre-Shipping Preparation (1-2 Weeks Before)</h4>
                        <ol class="list-decimal list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>Conditioning:</strong> Ensure all specimens are healthy and feeding well for at least 1-2 weeks before shipping.
                            </li>
                            <li>
                                <strong>Quarantine:</strong> Keep specimens in quarantine systems to monitor health and treat any potential issues.
                            </li>
                            <li>
                                <strong>Feeding Schedule:</strong> For fish and invertebrates:
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Feed normally until 48-72 hours before shipping</li>
                                    <li>Stop feeding completely 48 hours before shipping to reduce waste production during transit</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Medication:</strong> Complete any medication treatments at least one week before shipping to ensure specimens are fully recovered.
                            </li>
                            <li>
                                <strong>Water Parameters:</strong> Gradually adjust water parameters if the destination has significantly different requirements.
                            </li>
                        </ol>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Packaging Materials</h4>
                        <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>Shipping Bags:</strong> Use heavy-duty polyethylene bags specifically designed for fish shipping (3-4 mil thickness).
                            </li>
                            <li>
                                <strong>Oxygen:</strong> Pure oxygen supply for filling bags (not just air).
                            </li>
                            <li>
                                <strong>Rubber Bands:</strong> Strong rubber bands for sealing bags.
                            </li>
                            <li>
                                <strong>Insulation:</strong> Styrofoam boxes with appropriate thickness (1-2 inches) for temperature regulation.
                            </li>
                            <li>
                                <strong>Outer Packaging:</strong> Sturdy cardboard boxes that meet airline requirements.
                            </li>
                            <li>
                                <strong>Temperature Control:</strong> Heat packs or cold packs depending on season and species requirements.
                            </li>
                            <li>
                                <strong>Cushioning:</strong> Newspaper, packing peanuts, or other cushioning materials.
                            </li>
                            <li>
                                <strong>Water Conditioners:</strong> Stress coat additives, ammonia neutralizers, or sedatives (where legally permitted).
                            </li>
                        </ul>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Packing Process</h4>
                        <ol class="list-decimal list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>Bag Preparation:</strong>
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Use double or triple bagging for added security</li>
                                    <li>Fill bags with 1/3 water and 2/3 oxygen</li>
                                    <li>Add appropriate water conditioners</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Species-Specific Considerations:</strong>
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Separate aggressive species</li>
                                    <li>Use breather bags for sensitive species</li>
                                    <li>Add methylene blue for fish prone to bacterial infections</li>
                                    <li>Include small pieces of filter media for beneficial bacteria</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Bag Sealing:</strong> Twist the top of the bag tightly and secure with multiple rubber bands.
                            </li>
                            <li>
                                <strong>Box Preparation:</strong>
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Line styrofoam box with newspaper for additional insulation</li>
                                    <li>Arrange bags to prevent movement and contact between incompatible species</li>
                                    <li>Add heat packs or cold packs as needed (separated from direct contact with bags)</li>
                                    <li>Fill empty spaces with cushioning material</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Outer Packaging:</strong>
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Seal styrofoam box with tape</li>
                                    <li>Place in cardboard outer box</li>
                                    <li>Seal outer box securely</li>
                                    <li>Apply proper labeling: "Live Animals," "This Way Up," etc.</li>
                                    <li>Attach all required documentation</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Shipping Considerations</h4>
                        <ul class="list-disc list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>Timing:</strong> Ship early in the week to avoid weekend delays.
                            </li>
                            <li>
                                <strong>Weather:</strong> Monitor weather conditions at origin, destination, and transit points.
                            </li>
                            <li>
                                <strong>Carrier Selection:</strong> Use carriers experienced with live animal shipments.
                            </li>
                            <li>
                                <strong>Transit Time:</strong> Choose the fastest shipping method available, ideally direct flights.
                            </li>
                            <li>
                                <strong>Tracking:</strong> Use services with real-time tracking and notifications.
                            </li>
                            <li>
                                <strong>Coordination:</strong> Ensure recipient is prepared for arrival and customs clearance.
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="mt-4 bg-blue-50 dark:bg-blue-900/30 border-l-4 border-blue-500 p-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700 dark:text-blue-200">
                                <strong>Professional Assistance:</strong> For international shipping of live aquatic specimens, we strongly recommend working with professional exporters or using our shipping services. The complexity of international regulations and the specialized knowledge required for proper packing make professional assistance invaluable.
                            </p>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>AquaLuxe Shipping Services:</strong> We offer professional packing and shipping services for international orders. Our team has extensive experience in preparing aquatic species for long-distance transport, ensuring the highest survival rates. Contact our shipping department at shipping@aqualuxe.com for more information.
                </p>
            </div>
        </div>
        
        <!-- FAQ Item 5 -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
            <button 
                @click="selected !== 5 ? selected = 5 : selected = null"
                class="flex items-center justify-between w-full p-5 text-left"
                :aria-expanded="selected === 5 ? 'true' : 'false'"
                aria-controls="export-import-faq-5"
            >
                <span class="font-medium text-gray-800 dark:text-white">What are the customs clearance procedures for live aquatic imports?</span>
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
                id="export-import-faq-5" 
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
                    Customs clearance for live aquatic imports involves several critical steps that must be completed efficiently to ensure the survival of the specimens. Here's a detailed overview of the process:
                </p>
                <div class="mt-4 space-y-4">
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Pre-Arrival Preparation</h4>
                        <ol class="list-decimal list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>Documentation Preparation:</strong> Gather all required documents before the shipment arrives:
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Commercial invoice</li>
                                    <li>Packing list with scientific names and quantities</li>
                                    <li>Import permits</li>
                                    <li>Health certificates</li>
                                    <li>CITES documentation (if applicable)</li>
                                    <li>Certificate of origin</li>
                                    <li>Airway bill</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Customs Broker:</strong> For complex shipments, engage a customs broker experienced with live animal imports.
                            </li>
                            <li>
                                <strong>Pre-Notification:</strong> Notify relevant authorities about the incoming shipment:
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>U.S. Fish and Wildlife Service (at least 48 hours in advance)</li>
                                    <li>Customs and Border Protection</li>
                                    <li>USDA/APHIS (if applicable)</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Payment Preparation:</strong> Be prepared to pay:
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Import duties</li>
                                    <li>Processing fees</li>
                                    <li>Inspection fees</li>
                                    <li>Broker fees (if applicable)</li>
                                </ul>
                            </li>
                        </ol>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Arrival and Inspection Process</h4>
                        <ol class="list-decimal list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>Shipment Arrival:</strong> The carrier will notify you when the shipment arrives at the port of entry.
                            </li>
                            <li>
                                <strong>Document Submission:</strong> Submit all required documentation to:
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Customs and Border Protection</li>
                                    <li>Fish and Wildlife Service</li>
                                    <li>Other relevant agencies</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Physical Inspection:</strong> Government officials may inspect the shipment to:
                                <ul class="list-disc list-inside ml-6 mt-1 space-y-1">
                                    <li>Verify species identification</li>
                                    <li>Confirm quantities match documentation</li>
                                    <li>Check for prohibited species</li>
                                    <li>Assess health condition</li>
                                </ul>
                            </li>
                            <li>
                                <strong>Clearance Approval:</strong> Once all agencies have approved the shipment, customs will release it.
                            </li>
                            <li>
                                <strong>Payment of Fees:</strong> Pay all applicable duties, taxes, and fees.
                            </li>
                        </ol>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Post-Clearance Procedures</h4>
                        <ol class="list-decimal list-inside mt-2 space-y-2 text-gray-600 dark:text-gray-300">
                            <li>
                                <strong>Immediate Transport:</strong> Arrange for immediate pickup and transport to minimize stress on the animals.
                            </li>
                            <li>
                                <strong>Quarantine:</strong> Transfer specimens to prepared quarantine facilities.
                            </li>
                            <li>
                                <strong>Record Keeping:</strong> Maintain copies of all import documentation for at least 5 years.
                            </li>
                            <li>
                                <strong>Health Assessment:</strong> Conduct thorough health assessments of all imported specimens.
                            </li>
                        </ol>
                    </div>
                    
                    <div>
                        <h4 class="font-medium text-gray-800 dark:text-white">Common Challenges and Solutions</h4>
                        <div class="mt-3 overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-700 rounded-lg overflow-hidden text-sm">
                                <thead class="bg-gray-100 dark:bg-gray-600">
                                    <tr>
                                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200">Challenge</th>
                                        <th class="py-2 px-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200">Solution</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Delayed inspection</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Pre-notify agencies; request expedited inspection for live animals</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Documentation discrepancies</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Double-check all documents before shipping; have originals and copies available</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Species identification issues</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Include clear photos and accurate scientific names; work with taxonomic experts</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Weekend arrivals</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Schedule shipments to arrive Monday-Thursday; arrange for after-hours inspection if necessary</td>
                                    </tr>
                                    <tr>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Unexpected holds</td>
                                        <td class="py-2 px-3 text-gray-600 dark:text-gray-300">Have contingency plans for temporary housing; maintain good relationships with inspectors</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <p class="text-gray-600 dark:text-gray-300 mt-4">
                    <strong>AquaLuxe Import Assistance:</strong> Our import team can handle the entire customs clearance process for you, from documentation preparation to coordinating with agencies and arranging transportation. We have established relationships with customs officials and brokers at major ports of entry, allowing for smoother clearance processes. Contact our import specialists at imports@aqualuxe.com for assistance.
                </p>
                <p class="text-gray-600 dark:text-gray-300 mt-2">
                    <strong>Important Note:</strong> Customs procedures vary by country and can change frequently. Always verify current requirements with relevant authorities or consult with our import specialists before planning any international shipments.
                </p>
            </div>
        </div>
    </div>
</section>