<?php
/**
 * Template part for displaying the contact form on the Contact page
 *
 * @package AquaLuxe
 */
?>

<section id="contact-form" class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
    <div class="p-6 md:p-8 lg:p-10">
        <h2 class="text-2xl md:text-3xl font-bold mb-2 text-gray-800 dark:text-white">Send Us a Message</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-8">
            Fill out the form below and our team will get back to you within 24 hours.
        </p>
        
        <form class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="first-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">First Name</label>
                    <input type="text" id="first-name" name="first-name" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Your first name" required>
                </div>
                
                <div>
                    <label for="last-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Last Name</label>
                    <input type="text" id="last-name" name="last-name" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Your last name" required>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                    <input type="email" id="email" name="email" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Your email address" required>
                </div>
                
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Phone Number</label>
                    <input type="tel" id="phone" name="phone" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="Your phone number">
                </div>
            </div>
            
            <div>
                <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Subject</label>
                <input type="text" id="subject" name="subject" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="What is this regarding?" required>
            </div>
            
            <div>
                <label for="inquiry-type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Inquiry Type</label>
                <select id="inquiry-type" name="inquiry-type" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white" required>
                    <option value="">Select an inquiry type</option>
                    <option value="product">Product Information</option>
                    <option value="service">Service Inquiry</option>
                    <option value="support">Technical Support</option>
                    <option value="order">Order Status</option>
                    <option value="return">Returns & Exchanges</option>
                    <option value="other">Other</option>
                </select>
            </div>
            
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
                <textarea id="message" name="message" rows="5" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-transparent dark:bg-gray-700 dark:text-white" placeholder="How can we help you?" required></textarea>
            </div>
            
            <div class="flex items-start">
                <div class="flex items-center h-5">
                    <input id="newsletter" name="newsletter" type="checkbox" class="h-4 w-4 text-teal-600 focus:ring-teal-500 border-gray-300 rounded">
                </div>
                <div class="ml-3 text-sm">
                    <label for="newsletter" class="font-medium text-gray-700 dark:text-gray-300">Subscribe to our newsletter</label>
                    <p class="text-gray-500 dark:text-gray-400">Get the latest updates on products, services, and aquarium care tips.</p>
                </div>
            </div>
            
            <div>
                <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-teal-600 hover:from-blue-700 hover:to-teal-700 text-white font-medium py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
                    <span>Send Message</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</section>