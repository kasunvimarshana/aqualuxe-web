<?php
/**
 * Template for the Affiliate Dashboard.
 *
 * This template is loaded by the [aqualuxe_affiliate_dashboard] shortcode.
 * It receives $stats and $referral_url as query vars.
 */

$stats = get_query_var('stats', []);
$referral_url = get_query_var('referral_url', '');

?>
<div class="aqualuxe-affiliate-dashboard bg-white dark:bg-gray-800 p-8 rounded-lg shadow-lg">
    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">Your Affiliate Dashboard</h2>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg text-center">
            <div class="text-4xl font-extrabold text-blue-600 dark:text-blue-400"><?php echo esc_html($stats['referrals'] ?? 0); ?></div>
            <div class="text-lg font-medium text-gray-500 dark:text-gray-400 mt-1"><?php _e('Total Referrals', 'aqualuxe'); ?></div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg text-center">
            <div class="text-4xl font-extrabold text-green-600 dark:text-green-400"><?php echo esc_html($stats['earnings'] ?? '0.00'); ?></div>
            <div class="text-lg font-medium text-gray-500 dark:text-gray-400 mt-1"><?php _e('Total Earnings', 'aqualuxe'); ?></div>
        </div>
        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg text-center">
            <div class="text-4xl font-extrabold text-purple-600 dark:text-purple-400"><?php echo esc_html($stats['rate'] ?? 'N/A'); ?></div>
            <div class="text-lg font-medium text-gray-500 dark:text-gray-400 mt-1"><?php _e('Commission Rate', 'aqualuxe'); ?></div>
        </div>
    </div>

    <!-- Referral URL Section -->
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Your Referral Link</h3>
        <div class="flex items-center bg-gray-100 dark:bg-gray-700 p-4 rounded-lg">
            <input id="aqualuxe-referral-url" type="text" value="<?php echo esc_url($referral_url); ?>" readonly class="flex-grow bg-transparent border-none text-gray-800 dark:text-gray-200 focus:ring-0">
            <button id="aqualuxe-copy-referral-url" class="ml-4 px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                <?php _e('Copy', 'aqualuxe'); ?>
            </button>
        </div>
        <div id="aqualuxe-copy-feedback" class="text-sm text-green-600 mt-2 h-4"></div>
    </div>

    <!-- Links to AffiliateWP Area -->
    <div>
        <a href="<?php echo esc_url(affwp_get_affiliate_area_page_id() ? get_permalink(affwp_get_affiliate_area_page_id()) : '#'); ?>" class="aqualuxe-btn-primary">
            <?php _e('View Full Affiliate Area', 'aqualuxe'); ?>
        </a>
    </div>
</div>
