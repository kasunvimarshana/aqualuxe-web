<?php
/**
 * The template for displaying the offline page
 *
 * @package AquaLuxe
 */

get_header();
?>

<div class="offline-page container mx-auto px-4 py-16">
    <div class="text-center">
        <svg class="mx-auto h-24 w-24 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728m-3.536-3.536a5 5 0 010-7.072m-3.182 3.536a1 1 0 11-1.414-1.414 1 1 0 011.414 1.414z" />
        </svg>
        
        <h1 class="mt-6 text-3xl font-bold text-gray-900">You're currently offline</h1>
        
        <p class="mt-4 text-lg text-gray-600">
            It looks like you've lost your internet connection. Please check your connection and try again.
        </p>
        
        <div class="mt-8">
            <button id="retry-button" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md shadow-sm text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                <svg class="mr-2 -ml-1 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                Retry Connection
            </button>
        </div>
        
        <div class="mt-12">
            <h2 class="text-xl font-semibold text-gray-900">Available Offline</h2>
            
            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3" id="offline-pages">
                <!-- Offline pages will be populated here by JavaScript -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900">Home</h3>
                    <p class="mt-2 text-sm text-gray-500">Visit our homepage</p>
                    <a href="/" class="mt-4 inline-flex items-center text-sm font-medium text-primary hover:text-primary-dark">
                        Visit page
                        <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        
        <div class="mt-12">
            <h2 class="text-xl font-semibold text-gray-900">Need Help?</h2>
            
            <p class="mt-4 text-gray-600">
                If you continue to experience issues, please contact our support team when you're back online.
            </p>
            
            <div class="mt-4">
                <a href="/contact/" class="text-primary hover:text-primary-dark font-medium">Contact Support</a>
            </div>
        </div>
    </div>
</div>

<script>
    // Retry button functionality
    document.getElementById('retry-button').addEventListener('click', function() {
        window.location.reload();
    });
    
    // Check for cached pages
    if ('caches' in window) {
        caches.open('aqualuxe-pages-v1').then(function(cache) {
            cache.keys().then(function(requests) {
                const offlinePagesContainer = document.getElementById('offline-pages');
                
                // Clear existing content
                offlinePagesContainer.innerHTML = '';
                
                if (requests.length === 0) {
                    offlinePagesContainer.innerHTML = '<p class="col-span-full text-center text-gray-500">No pages available offline</p>';
                    return;
                }
                
                // Filter out non-HTML requests and limit to 6
                const htmlRequests = requests
                    .filter(request => {
                        const url = new URL(request.url);
                        return !url.pathname.match(/\.(css|js|png|jpg|jpeg|gif|svg|webp|woff|woff2|ttf|otf|eot)$/);
                    })
                    .slice(0, 6);
                
                // Create page cards
                htmlRequests.forEach(function(request) {
                    const url = new URL(request.url);
                    let title = url.pathname === '/' ? 'Home' : url.pathname.split('/').filter(Boolean).pop();
                    title = title.charAt(0).toUpperCase() + title.slice(1).replace(/-/g, ' ');
                    
                    const card = document.createElement('div');
                    card.className = 'bg-white rounded-lg shadow p-6';
                    card.innerHTML = `
                        <h3 class="text-lg font-medium text-gray-900">${title}</h3>
                        <p class="mt-2 text-sm text-gray-500">${url.pathname}</p>
                        <a href="${url.pathname}" class="mt-4 inline-flex items-center text-sm font-medium text-primary hover:text-primary-dark">
                            Visit page
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    `;
                    
                    offlinePagesContainer.appendChild(card);
                });
            });
        });
    }
</script>

<?php
get_footer();