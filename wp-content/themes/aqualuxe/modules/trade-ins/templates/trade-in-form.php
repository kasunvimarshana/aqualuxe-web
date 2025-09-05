<?php
/**
 * Template for the Trade-In Request Form.
 */
?>
<div id="aqualuxe-trade-in-form-wrapper" class="max-w-2xl mx-auto p-8 bg-white dark:bg-gray-800 rounded-lg shadow-md">
    <form id="aqualuxe-trade-in-form" class="space-y-6">
        <h2 class="text-2xl font-bold text-center text-gray-900 dark:text-white">Request a Trade-In</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label for="trade-in-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                <input type="text" name="name" id="trade-in-name" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
            <div>
                <label for="trade-in-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                <input type="email" name="email" id="trade-in-email" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
            </div>
        </div>

        <div>
            <label for="trade-in-product-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Name</label>
            <input type="text" name="product_name" id="trade-in-product-name" required placeholder="e.g., AquaLuxe Pro Filter" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
        </div>

        <div>
            <label for="trade-in-condition" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Condition</label>
            <select id="trade-in-condition" name="condition" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                <option>Like New</option>
                <option>Excellent</option>
                <option>Good</option>
                <option>Fair</option>
                <option>Poor</option>
            </select>
        </div>

        <div>
            <label for="trade-in-message" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Additional Details</label>
            <textarea id="trade-in-message" name="message" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white"></textarea>
        </div>

        <div>
            <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Submit Request
            </button>
        </div>
    </form>
    <div id="aqualuxe-trade-in-response" class="mt-6 text-center"></div>
</div>
