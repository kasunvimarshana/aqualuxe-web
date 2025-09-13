<?php
/**
 * Order Tracking Template
 *
 * Custom order tracking page with enhanced features
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header('shop'); ?>

<div class="order-tracking-page">
    <div class="container mx-auto px-4 py-8">
        
        <!-- Page Header -->
        <div class="page-header text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">
                <?php esc_html_e('Track Your Order', 'aqualuxe'); ?>
            </h1>
            <p class="text-lg text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">
                <?php esc_html_e('Enter your order details below to track the status and location of your aquatic treasures.', 'aqualuxe'); ?>
            </p>
        </div>

        <!-- Tracking Form -->
        <div class="tracking-form-wrapper max-w-lg mx-auto mb-12">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <form id="order-tracking-form" class="space-y-6">
                    
                    <div class="form-group">
                        <label for="order_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php esc_html_e('Order Number', 'aqualuxe'); ?>
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               id="order_number" 
                               name="order_number" 
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                               placeholder="<?php esc_attr_e('e.g., #12345', 'aqualuxe'); ?>"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="order_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <?php esc_html_e('Email Address', 'aqualuxe'); ?>
                            <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               id="order_email" 
                               name="order_email" 
                               class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white"
                               placeholder="<?php esc_attr_e('your@email.com', 'aqualuxe'); ?>"
                               required>
                    </div>

                    <button type="submit" class="w-full btn btn-primary">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <?php esc_html_e('Track Order', 'aqualuxe'); ?>
                    </button>

                    <?php wp_nonce_field('aqualuxe_track_order', 'track_order_nonce'); ?>
                </form>
            </div>

            <!-- Help Text -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <?php esc_html_e('Need help? Contact our', 'aqualuxe'); ?> 
                    <a href="<?php echo esc_url(get_permalink(get_option('woocommerce_myaccount_page_id'))); ?>" class="text-primary-600 hover:text-primary-700 font-medium">
                        <?php esc_html_e('customer support team', 'aqualuxe'); ?>
                    </a>
                </p>
            </div>
        </div>

        <!-- Order Tracking Results -->
        <div id="tracking-results" class="hidden">
            <!-- Results will be populated via AJAX -->
        </div>

        <!-- Sample Tracking Information -->
        <div class="tracking-info bg-blue-50 dark:bg-blue-900 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-blue-900 dark:text-blue-100 mb-4">
                <?php esc_html_e('Tracking Information', 'aqualuxe'); ?>
            </h3>
            <div class="grid md:grid-cols-3 gap-6 text-sm">
                <div class="info-item">
                    <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-2">
                        <?php esc_html_e('Processing Time', 'aqualuxe'); ?>
                    </h4>
                    <p class="text-blue-700 dark:text-blue-300">
                        <?php esc_html_e('1-2 business days for order preparation and specialized packaging', 'aqualuxe'); ?>
                    </p>
                </div>
                <div class="info-item">
                    <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-2">
                        <?php esc_html_e('Shipping Time', 'aqualuxe'); ?>
                    </h4>
                    <p class="text-blue-700 dark:text-blue-300">
                        <?php esc_html_e('2-5 business days with live arrival guarantee for fish orders', 'aqualuxe'); ?>
                    </p>
                </div>
                <div class="info-item">
                    <h4 class="font-medium text-blue-800 dark:text-blue-200 mb-2">
                        <?php esc_html_e('Delivery Updates', 'aqualuxe'); ?>
                    </h4>
                    <p class="text-blue-700 dark:text-blue-300">
                        <?php esc_html_e('Real-time SMS and email notifications throughout the journey', 'aqualuxe'); ?>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('order-tracking-form');
    const results = document.getElementById('tracking-results');
    
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        formData.append('action', 'aqualuxe_track_order');
        
        // Show loading state
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Tracking...';
        submitBtn.disabled = true;
        
        fetch('<?php echo esc_url(admin_url('admin-ajax.php')); ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                results.innerHTML = data.data.html;
                results.classList.remove('hidden');
                results.scrollIntoView({ behavior: 'smooth' });
            } else {
                alert(data.data || '<?php esc_html_e('Order not found. Please check your details and try again.', 'aqualuxe'); ?>');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('<?php esc_html_e('An error occurred. Please try again.', 'aqualuxe'); ?>');
        })
        .finally(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        });
    });
});
</script>

<?php get_footer('shop'); ?>