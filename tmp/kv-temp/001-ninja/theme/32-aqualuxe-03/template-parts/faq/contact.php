<?php
/**
 * Template part for displaying the contact section on the FAQ page
 *
 * @package AquaLuxe
 */
?>

<section class="py-12 bg-gradient-to-r from-blue-900 to-teal-800 text-white">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl font-bold mb-6">Still Have Questions?</h2>
            <p class="text-xl text-blue-100 mb-8">
                Our team of aquatic experts is ready to help you with any questions or concerns.
            </p>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10">
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 hover:bg-white/20 transition duration-300">
                    <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Email Us</h3>
                    <p class="text-blue-100 mb-4">
                        Send us a detailed message and we'll respond within 24 hours.
                    </p>
                    <a href="mailto:support@aqualuxe.com" class="inline-block bg-white/20 hover:bg-white/30 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        support@aqualuxe.com
                    </a>
                </div>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 hover:bg-white/20 transition duration-300">
                    <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Call Us</h3>
                    <p class="text-blue-100 mb-4">
                        Speak directly with our customer support team.
                    </p>
                    <a href="tel:+15551234567" class="inline-block bg-white/20 hover:bg-white/30 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        (555) 123-4567
                    </a>
                </div>
                
                <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 hover:bg-white/20 transition duration-300">
                    <div class="bg-white/20 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Live Chat</h3>
                    <p class="text-blue-100 mb-4">
                        Chat with our team in real-time for immediate assistance.
                    </p>
                    <a href="#" class="inline-block bg-white/20 hover:bg-white/30 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                        Start Chat
                    </a>
                </div>
            </div>
            
            <div class="mt-12 bg-white/10 backdrop-blur-sm rounded-xl p-8">
                <h3 class="text-2xl font-bold mb-6">Contact Form</h3>
                <form class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-blue-100 mb-1">Name</label>
                            <input type="text" id="name" name="name" class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg focus:ring-2 focus:ring-white/50 focus:border-transparent text-white placeholder-blue-200" placeholder="Your name">
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-blue-100 mb-1">Email</label>
                            <input type="email" id="email" name="email" class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg focus:ring-2 focus:ring-white/50 focus:border-transparent text-white placeholder-blue-200" placeholder="Your email">
                        </div>
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-blue-100 mb-1">Subject</label>
                        <select id="subject" name="subject" class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg focus:ring-2 focus:ring-white/50 focus:border-transparent text-white">
                            <option value="" class="text-gray-800">Select a subject</option>
                            <option value="shipping" class="text-gray-800">Shipping & Delivery</option>
                            <option value="care" class="text-gray-800">Aquarium Care</option>
                            <option value="purchasing" class="text-gray-800">Purchasing</option>
                            <option value="export-import" class="text-gray-800">Export & Import</option>
                            <option value="other" class="text-gray-800">Other</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-blue-100 mb-1">Message</label>
                        <textarea id="message" name="message" rows="4" class="w-full px-4 py-2 bg-white/20 border border-white/30 rounded-lg focus:ring-2 focus:ring-white/50 focus:border-transparent text-white placeholder-blue-200" placeholder="Your question or message"></textarea>
                    </div>
                    
                    <div>
                        <button type="submit" class="w-full bg-teal-600 hover:bg-teal-700 text-white font-medium py-2 px-4 rounded-lg transition duration-300">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>