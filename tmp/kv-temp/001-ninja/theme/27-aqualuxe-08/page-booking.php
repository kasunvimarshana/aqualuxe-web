<?php
/**
 * Template Name: Booking Page
 *
 * This template is used for the consultation booking page.
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <div class="max-w-4xl mx-auto">
            <header class="page-header mb-8">
                <h1 class="page-title text-3xl md:text-4xl font-bold text-primary-900 dark:text-primary-100">
                    <?php the_title(); ?>
                </h1>
                <?php if ( has_excerpt() ) : ?>
                    <div class="page-description mt-4 text-lg text-gray-600 dark:text-gray-300">
                        <?php the_excerpt(); ?>
                    </div>
                <?php endif; ?>
            </header>

            <div class="booking-container bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <!-- Booking Form -->
                <div class="booking-form-wrapper p-6 md:p-8">
                    <div class="booking-result hidden p-4 mb-6 rounded-md"></div>
                    
                    <form class="booking-form space-y-6" method="post">
                        <?php wp_nonce_field( 'aqualuxe_booking_nonce', 'booking_nonce' ); ?>
                        <input type="hidden" name="action" value="aqualuxe_submit_booking">
                        
                        <!-- Personal Information -->
                        <div class="personal-info space-y-4">
                            <h3 class="text-xl font-semibold text-primary-900 dark:text-primary-100">
                                <?php esc_html_e( 'Personal Information', 'aqualuxe' ); ?>
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="booking_name" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e( 'Full Name', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="booking_name" name="booking_name" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="booking_email" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e( 'Email Address', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="booking_email" name="booking_email" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="booking_phone" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e( 'Phone Number', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="tel" id="booking_phone" name="booking_phone" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" required>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Consultation Details -->
                        <div class="consultation-details space-y-4">
                            <h3 class="text-xl font-semibold text-primary-900 dark:text-primary-100">
                                <?php esc_html_e( 'Consultation Details', 'aqualuxe' ); ?>
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="booking_service" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e( 'Service Type', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <select id="booking_service" name="booking_service" class="booking-service w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" required>
                                        <option value=""><?php esc_html_e( 'Select a service', 'aqualuxe' ); ?></option>
                                        <!-- Options will be populated via JavaScript -->
                                    </select>
                                </div>
                                
                                <div class="form-group">
                                    <label for="booking_date" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e( 'Preferred Date', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="booking_date" name="booking_date" class="booking-date w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" placeholder="<?php esc_attr_e( 'Select a date', 'aqualuxe' ); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="booking_time" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                                        <?php esc_html_e( 'Preferred Time', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <select id="booking_time" name="booking_time" class="booking-time w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" required disabled>
                                        <option value=""><?php esc_html_e( 'Select a time', 'aqualuxe' ); ?></option>
                                        <!-- Options will be populated via JavaScript -->
                                    </select>
                                </div>
                            </div>
                            
                            <div class="service-details grid grid-cols-1 md:grid-cols-2 gap-4 mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-md">
                                <div class="duration">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Duration', 'aqualuxe' ); ?></span>
                                    <span class="booking-duration text-lg font-medium text-gray-900 dark:text-gray-100"></span>
                                </div>
                                <div class="price">
                                    <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Price', 'aqualuxe' ); ?></span>
                                    <span class="booking-price text-lg font-medium text-gray-900 dark:text-gray-100"></span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Additional Information -->
                        <div class="additional-info space-y-4">
                            <h3 class="text-xl font-semibold text-primary-900 dark:text-primary-100">
                                <?php esc_html_e( 'Additional Information', 'aqualuxe' ); ?>
                            </h3>
                            
                            <div class="form-group">
                                <label for="booking_message" class="block mb-1 font-medium text-gray-700 dark:text-gray-300">
                                    <?php esc_html_e( 'Message', 'aqualuxe' ); ?>
                                </label>
                                <textarea id="booking_message" name="booking_message" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" placeholder="<?php esc_attr_e( 'Please provide any additional information about your consultation needs...', 'aqualuxe' ); ?>"></textarea>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="form-submit">
                            <button type="submit" class="booking-submit w-full md:w-auto px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-md transition duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <?php esc_html_e( 'Book Consultation', 'aqualuxe' ); ?>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Booking Summary (will be shown after successful booking) -->
                <div class="booking-summary hidden p-6 md:p-8 border-t border-gray-200 dark:border-gray-700"></div>
                
                <!-- Loading Overlay -->
                <div class="booking-loading hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div class="loader p-5 bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <div class="animate-spin rounded-full h-10 w-10 border-t-2 border-b-2 border-primary-600 mx-auto"></div>
                        <p class="mt-3 text-center text-gray-700 dark:text-gray-300"><?php esc_html_e( 'Processing...', 'aqualuxe' ); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php
get_footer();