<?php
/**
 * Template Name: Trade-In Request Page
 *
 * This template is used for the trade-in request form.
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

            <div class="trade-in-container">
                <?php the_content(); ?>
                
                <div class="trade-in-request-container">
                    <div class="trade-in-request-result hidden"></div>
                    
                    <form class="trade-in-request-form">
                        <input type="hidden" name="action" value="aqualuxe_submit_trade_in_request">
                        <input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'aqualuxe_ajax_nonce' ); ?>">
                        
                        <!-- Item Information -->
                        <div class="trade-in-form-section">
                            <h3 class="trade-in-form-section-title">
                                <?php esc_html_e( 'Item Information', 'aqualuxe' ); ?>
                            </h3>
                            
                            <div class="trade-in-form-row">
                                <div class="trade-in-form-field">
                                    <label for="item_name" class="trade-in-form-label">
                                        <?php esc_html_e( 'Item Name', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="item_name" name="item_name" class="trade-in-form-input" required>
                                </div>
                                
                                <div class="trade-in-form-field">
                                    <label for="item_type" class="trade-in-form-label">
                                        <?php esc_html_e( 'Item Type', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <select id="item_type" name="item_type" class="trade-in-form-select" required>
                                        <option value=""><?php esc_html_e( 'Select Item Type', 'aqualuxe' ); ?></option>
                                        <option value="fish"><?php esc_html_e( 'Fish', 'aqualuxe' ); ?></option>
                                        <option value="equipment"><?php esc_html_e( 'Equipment', 'aqualuxe' ); ?></option>
                                        <option value="aquarium"><?php esc_html_e( 'Aquarium', 'aqualuxe' ); ?></option>
                                        <option value="accessory"><?php esc_html_e( 'Accessory', 'aqualuxe' ); ?></option>
                                        <option value="other"><?php esc_html_e( 'Other', 'aqualuxe' ); ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="trade-in-form-row">
                                <div class="trade-in-form-field">
                                    <label for="item_condition" class="trade-in-form-label">
                                        <?php esc_html_e( 'Condition', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <select id="item_condition" name="item_condition" class="trade-in-form-select" required>
                                        <option value=""><?php esc_html_e( 'Select Condition', 'aqualuxe' ); ?></option>
                                        <!-- Options will be populated via JavaScript -->
                                    </select>
                                </div>
                                
                                <div class="trade-in-form-field">
                                    <label for="item_age" class="trade-in-form-label">
                                        <?php esc_html_e( 'Age', 'aqualuxe' ); ?>
                                    </label>
                                    <input type="text" id="item_age" name="item_age" class="trade-in-form-input" placeholder="<?php esc_attr_e( 'e.g., 2 years', 'aqualuxe' ); ?>">
                                </div>
                            </div>
                            
                            <!-- Fish-specific fields -->
                            <div class="trade-in-form-row field-fish-specific hidden">
                                <div class="trade-in-form-field">
                                    <label for="fish_species" class="trade-in-form-label">
                                        <?php esc_html_e( 'Species', 'aqualuxe' ); ?>
                                    </label>
                                    <input type="text" id="fish_species" name="fish_species" class="trade-in-form-input">
                                </div>
                                
                                <div class="trade-in-form-field">
                                    <label for="fish_size" class="trade-in-form-label">
                                        <?php esc_html_e( 'Size (inches)', 'aqualuxe' ); ?>
                                    </label>
                                    <input type="text" id="fish_size" name="fish_size" class="trade-in-form-input">
                                </div>
                            </div>
                            
                            <!-- Equipment-specific fields -->
                            <div class="trade-in-form-row field-equipment-specific hidden">
                                <div class="trade-in-form-field">
                                    <label for="equipment_brand" class="trade-in-form-label">
                                        <?php esc_html_e( 'Brand', 'aqualuxe' ); ?>
                                    </label>
                                    <input type="text" id="equipment_brand" name="equipment_brand" class="trade-in-form-input">
                                </div>
                                
                                <div class="trade-in-form-field">
                                    <label for="equipment_model" class="trade-in-form-label">
                                        <?php esc_html_e( 'Model', 'aqualuxe' ); ?>
                                    </label>
                                    <input type="text" id="equipment_model" name="equipment_model" class="trade-in-form-input">
                                </div>
                            </div>
                            
                            <!-- Aquarium-specific fields -->
                            <div class="trade-in-form-row field-aquarium-specific hidden">
                                <div class="trade-in-form-field">
                                    <label for="aquarium_size" class="trade-in-form-label">
                                        <?php esc_html_e( 'Size (gallons)', 'aqualuxe' ); ?>
                                    </label>
                                    <input type="text" id="aquarium_size" name="aquarium_size" class="trade-in-form-input">
                                </div>
                                
                                <div class="trade-in-form-field">
                                    <label for="aquarium_material" class="trade-in-form-label">
                                        <?php esc_html_e( 'Material', 'aqualuxe' ); ?>
                                    </label>
                                    <select id="aquarium_material" name="aquarium_material" class="trade-in-form-select">
                                        <option value=""><?php esc_html_e( 'Select Material', 'aqualuxe' ); ?></option>
                                        <option value="glass"><?php esc_html_e( 'Glass', 'aqualuxe' ); ?></option>
                                        <option value="acrylic"><?php esc_html_e( 'Acrylic', 'aqualuxe' ); ?></option>
                                        <option value="other"><?php esc_html_e( 'Other', 'aqualuxe' ); ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="trade-in-form-field">
                                <label for="item_description" class="trade-in-form-label">
                                    <?php esc_html_e( 'Description', 'aqualuxe' ); ?>
                                </label>
                                <textarea id="item_description" name="item_description" rows="4" class="trade-in-form-textarea" placeholder="<?php esc_attr_e( 'Please provide details about your item including specifications, features, and any issues.', 'aqualuxe' ); ?>"></textarea>
                            </div>
                        </div>
                        
                        <!-- Trade-In Preferences -->
                        <div class="trade-in-form-section">
                            <h3 class="trade-in-form-section-title">
                                <?php esc_html_e( 'Trade-In Preferences', 'aqualuxe' ); ?>
                            </h3>
                            
                            <div class="trade-in-form-field">
                                <label for="preferred_value" class="trade-in-form-label">
                                    <?php esc_html_e( 'Preferred Value Type', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                </label>
                                <select id="preferred_value" name="preferred_value" class="trade-in-form-select" required>
                                    <option value=""><?php esc_html_e( 'Select Preferred Value', 'aqualuxe' ); ?></option>
                                    <option value="store_credit"><?php esc_html_e( 'Store Credit (Higher Value)', 'aqualuxe' ); ?></option>
                                    <option value="cash"><?php esc_html_e( 'Cash', 'aqualuxe' ); ?></option>
                                </select>
                                <p class="trade-in-form-help">
                                    <?php esc_html_e( 'Store credit typically offers 20-30% more value than cash.', 'aqualuxe' ); ?>
                                </p>
                            </div>
                            
                            <div class="trade-in-form-field">
                                <label for="notes" class="trade-in-form-label">
                                    <?php esc_html_e( 'Additional Notes', 'aqualuxe' ); ?>
                                </label>
                                <textarea id="notes" name="notes" rows="3" class="trade-in-form-textarea" placeholder="<?php esc_attr_e( 'Any additional information you would like us to know about your trade-in request.', 'aqualuxe' ); ?>"></textarea>
                            </div>
                        </div>
                        
                        <!-- Contact Information -->
                        <div class="trade-in-form-section">
                            <h3 class="trade-in-form-section-title">
                                <?php esc_html_e( 'Contact Information', 'aqualuxe' ); ?>
                            </h3>
                            
                            <div class="trade-in-form-row">
                                <div class="trade-in-form-field">
                                    <label for="customer_name" class="trade-in-form-label">
                                        <?php esc_html_e( 'Your Name', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" id="customer_name" name="customer_name" class="trade-in-form-input" required>
                                </div>
                                
                                <div class="trade-in-form-field">
                                    <label for="customer_email" class="trade-in-form-label">
                                        <?php esc_html_e( 'Your Email', 'aqualuxe' ); ?> <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" id="customer_email" name="customer_email" class="trade-in-form-input" required>
                                </div>
                            </div>
                            
                            <div class="trade-in-form-field">
                                <label for="customer_phone" class="trade-in-form-label">
                                    <?php esc_html_e( 'Phone Number', 'aqualuxe' ); ?>
                                </label>
                                <input type="tel" id="customer_phone" name="customer_phone" class="trade-in-form-input">
                            </div>
                        </div>
                        
                        <div class="trade-in-form-submit">
                            <button type="submit" class="trade-in-request-submit">
                                <?php esc_html_e( 'Submit Trade-In Request', 'aqualuxe' ); ?>
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Loading Overlay -->
                <div class="trade-in-loading hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
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