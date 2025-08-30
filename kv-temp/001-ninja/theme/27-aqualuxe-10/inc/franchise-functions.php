<?php
/**
 * AquaLuxe Franchise Functions
 *
 * Functions for handling franchise inquiries and related functionality.
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Register franchise inquiry shortcode
 */
function aqualuxe_franchise_inquiry_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'title' => __( 'Become an AquaLuxe Franchise Owner', 'aqualuxe' ),
            'description' => __( 'Fill out the form below to start your journey as an AquaLuxe franchise owner.', 'aqualuxe' ),
        ),
        $atts,
        'franchise_inquiry'
    );

    ob_start();
    ?>
    <div class="franchise-inquiry-container">
        <h2 class="franchise-inquiry-title"><?php echo esc_html( $atts['title'] ); ?></h2>
        <p class="franchise-inquiry-description"><?php echo esc_html( $atts['description'] ); ?></p>
        
        <div class="franchise-inquiry-result hidden"></div>
        
        <div class="franchise-form-progress">
            <div class="franchise-form-progress-bar" style="width: 0%;"></div>
            
            <div class="franchise-form-progress-step" data-step="0">
                <div class="franchise-form-progress-step-circle">1</div>
                <div class="franchise-form-progress-step-label"><?php esc_html_e( 'Personal Info', 'aqualuxe' ); ?></div>
            </div>
            
            <div class="franchise-form-progress-step" data-step="1">
                <div class="franchise-form-progress-step-circle">2</div>
                <div class="franchise-form-progress-step-label"><?php esc_html_e( 'Business Experience', 'aqualuxe' ); ?></div>
            </div>
            
            <div class="franchise-form-progress-step" data-step="2">
                <div class="franchise-form-progress-step-circle">3</div>
                <div class="franchise-form-progress-step-label"><?php esc_html_e( 'Financial Info', 'aqualuxe' ); ?></div>
            </div>
            
            <div class="franchise-form-progress-step" data-step="3">
                <div class="franchise-form-progress-step-circle">4</div>
                <div class="franchise-form-progress-step-label"><?php esc_html_e( 'Additional Info', 'aqualuxe' ); ?></div>
            </div>
        </div>
        
        <form class="franchise-inquiry-form" method="post">
            <?php wp_nonce_field( 'aqualuxe_ajax_nonce', 'nonce' ); ?>
            <input type="hidden" name="action" value="aqualuxe_submit_franchise_inquiry">
            
            <!-- Step 1: Personal Information -->
            <div class="franchise-form-section" data-step="0">
                <h3 class="franchise-form-section-title"><?php esc_html_e( 'Personal Information', 'aqualuxe' ); ?></h3>
                
                <div class="franchise-form-row">
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="first_name">
                            <?php esc_html_e( 'First Name', 'aqualuxe' ); ?>
                            <span class="franchise-form-required">*</span>
                        </label>
                        <input type="text" id="first_name" name="first_name" class="franchise-form-input" required>
                        <div class="franchise-form-error error-first_name hidden"><?php esc_html_e( 'First name is required.', 'aqualuxe' ); ?></div>
                    </div>
                    
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="last_name">
                            <?php esc_html_e( 'Last Name', 'aqualuxe' ); ?>
                            <span class="franchise-form-required">*</span>
                        </label>
                        <input type="text" id="last_name" name="last_name" class="franchise-form-input" required>
                        <div class="franchise-form-error error-last_name hidden"><?php esc_html_e( 'Last name is required.', 'aqualuxe' ); ?></div>
                    </div>
                </div>
                
                <div class="franchise-form-row">
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="email">
                            <?php esc_html_e( 'Email', 'aqualuxe' ); ?>
                            <span class="franchise-form-required">*</span>
                        </label>
                        <input type="email" id="email" name="email" class="franchise-form-input" required>
                        <div class="franchise-form-error error-email hidden"><?php esc_html_e( 'A valid email is required.', 'aqualuxe' ); ?></div>
                    </div>
                    
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="phone">
                            <?php esc_html_e( 'Phone', 'aqualuxe' ); ?>
                            <span class="franchise-form-required">*</span>
                        </label>
                        <input type="tel" id="phone" name="phone" class="franchise-form-input" required>
                        <div class="franchise-form-error error-phone hidden"><?php esc_html_e( 'A valid phone number is required.', 'aqualuxe' ); ?></div>
                    </div>
                </div>
                
                <div class="franchise-form-field">
                    <label class="franchise-form-label" for="address">
                        <?php esc_html_e( 'Address', 'aqualuxe' ); ?>
                    </label>
                    <input type="text" id="address" name="address" class="franchise-form-input">
                </div>
                
                <div class="franchise-form-row">
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="city">
                            <?php esc_html_e( 'City', 'aqualuxe' ); ?>
                        </label>
                        <input type="text" id="city" name="city" class="franchise-form-input">
                    </div>
                    
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="state">
                            <?php esc_html_e( 'State/Province', 'aqualuxe' ); ?>
                        </label>
                        <input type="text" id="state" name="state" class="franchise-form-input">
                    </div>
                </div>
                
                <div class="franchise-form-row">
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="zip">
                            <?php esc_html_e( 'ZIP/Postal Code', 'aqualuxe' ); ?>
                        </label>
                        <input type="text" id="zip" name="zip" class="franchise-form-input">
                    </div>
                    
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="country">
                            <?php esc_html_e( 'Country', 'aqualuxe' ); ?>
                        </label>
                        <input type="text" id="country" name="country" class="franchise-form-input">
                    </div>
                </div>
                
                <div class="franchise-form-buttons">
                    <div></div> <!-- Empty div for flex spacing -->
                    <button type="button" class="franchise-form-button franchise-form-next">
                        <?php esc_html_e( 'Next', 'aqualuxe' ); ?>
                    </button>
                </div>
            </div>
            
            <!-- Step 2: Business Experience -->
            <div class="franchise-form-section hidden" data-step="1">
                <h3 class="franchise-form-section-title"><?php esc_html_e( 'Business Experience', 'aqualuxe' ); ?></h3>
                
                <div class="franchise-form-row">
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="business_experience">
                            <?php esc_html_e( 'Business Experience', 'aqualuxe' ); ?>
                        </label>
                        <select id="business_experience" name="business_experience" class="franchise-form-select">
                            <option value=""><?php esc_html_e( 'Select Experience', 'aqualuxe' ); ?></option>
                            <option value="none"><?php esc_html_e( 'None', 'aqualuxe' ); ?></option>
                            <option value="1-3"><?php esc_html_e( '1-3 years', 'aqualuxe' ); ?></option>
                            <option value="3-5"><?php esc_html_e( '3-5 years', 'aqualuxe' ); ?></option>
                            <option value="5-10"><?php esc_html_e( '5-10 years', 'aqualuxe' ); ?></option>
                            <option value="10+"><?php esc_html_e( '10+ years', 'aqualuxe' ); ?></option>
                        </select>
                    </div>
                    
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="aquarium_experience">
                            <?php esc_html_e( 'Aquarium Industry Experience', 'aqualuxe' ); ?>
                        </label>
                        <select id="aquarium_experience" name="aquarium_experience" class="franchise-form-select">
                            <option value=""><?php esc_html_e( 'Select Experience', 'aqualuxe' ); ?></option>
                            <option value="none"><?php esc_html_e( 'None', 'aqualuxe' ); ?></option>
                            <option value="hobbyist"><?php esc_html_e( 'Hobbyist', 'aqualuxe' ); ?></option>
                            <option value="1-3"><?php esc_html_e( '1-3 years professional', 'aqualuxe' ); ?></option>
                            <option value="3-5"><?php esc_html_e( '3-5 years professional', 'aqualuxe' ); ?></option>
                            <option value="5+"><?php esc_html_e( '5+ years professional', 'aqualuxe' ); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="franchise-form-row">
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="current_occupation">
                            <?php esc_html_e( 'Current Occupation', 'aqualuxe' ); ?>
                        </label>
                        <input type="text" id="current_occupation" name="current_occupation" class="franchise-form-input">
                    </div>
                    
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="business_ownership">
                            <?php esc_html_e( 'Previous Business Ownership', 'aqualuxe' ); ?>
                        </label>
                        <select id="business_ownership" name="business_ownership" class="franchise-form-select">
                            <option value=""><?php esc_html_e( 'Select Option', 'aqualuxe' ); ?></option>
                            <option value="yes"><?php esc_html_e( 'Yes', 'aqualuxe' ); ?></option>
                            <option value="no"><?php esc_html_e( 'No', 'aqualuxe' ); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="franchise-form-row">
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="franchise_experience">
                            <?php esc_html_e( 'Previous Franchise Experience', 'aqualuxe' ); ?>
                        </label>
                        <select id="franchise_experience" name="franchise_experience" class="franchise-form-select">
                            <option value=""><?php esc_html_e( 'Select Option', 'aqualuxe' ); ?></option>
                            <option value="yes"><?php esc_html_e( 'Yes', 'aqualuxe' ); ?></option>
                            <option value="no"><?php esc_html_e( 'No', 'aqualuxe' ); ?></option>
                        </select>
                    </div>
                    
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="partners">
                            <?php esc_html_e( 'Business Partners', 'aqualuxe' ); ?>
                        </label>
                        <select id="partners" name="partners" class="franchise-form-select">
                            <option value=""><?php esc_html_e( 'Select Option', 'aqualuxe' ); ?></option>
                            <option value="none"><?php esc_html_e( 'None (Sole Owner)', 'aqualuxe' ); ?></option>
                            <option value="spouse"><?php esc_html_e( 'Spouse/Partner', 'aqualuxe' ); ?></option>
                            <option value="family"><?php esc_html_e( 'Family Members', 'aqualuxe' ); ?></option>
                            <option value="investors"><?php esc_html_e( 'Investors', 'aqualuxe' ); ?></option>
                            <option value="other"><?php esc_html_e( 'Other', 'aqualuxe' ); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="franchise-form-buttons">
                    <button type="button" class="franchise-form-button franchise-form-prev">
                        <?php esc_html_e( 'Previous', 'aqualuxe' ); ?>
                    </button>
                    <button type="button" class="franchise-form-button franchise-form-next">
                        <?php esc_html_e( 'Next', 'aqualuxe' ); ?>
                    </button>
                </div>
            </div>
            
            <!-- Step 3: Financial Information -->
            <div class="franchise-form-section hidden" data-step="2">
                <h3 class="franchise-form-section-title"><?php esc_html_e( 'Financial Information', 'aqualuxe' ); ?></h3>
                
                <div class="franchise-form-row">
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="investment_range">
                            <?php esc_html_e( 'Investment Range', 'aqualuxe' ); ?>
                            <span class="franchise-form-required">*</span>
                        </label>
                        <select id="investment_range" name="investment_range" class="franchise-form-select" required>
                            <option value=""><?php esc_html_e( 'Select Range', 'aqualuxe' ); ?></option>
                            <option value="50-100k"><?php esc_html_e( '$50,000 - $100,000', 'aqualuxe' ); ?></option>
                            <option value="100-150k"><?php esc_html_e( '$100,000 - $150,000', 'aqualuxe' ); ?></option>
                            <option value="150-200k"><?php esc_html_e( '$150,000 - $200,000', 'aqualuxe' ); ?></option>
                            <option value="200k+"><?php esc_html_e( '$200,000+', 'aqualuxe' ); ?></option>
                        </select>
                        <div class="franchise-form-error error-investment_range hidden"><?php esc_html_e( 'Please select an investment range.', 'aqualuxe' ); ?></div>
                    </div>
                    
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="liquid_assets">
                            <?php esc_html_e( 'Liquid Assets', 'aqualuxe' ); ?>
                        </label>
                        <select id="liquid_assets" name="liquid_assets" class="franchise-form-select">
                            <option value=""><?php esc_html_e( 'Select Range', 'aqualuxe' ); ?></option>
                            <option value="25-50k"><?php esc_html_e( '$25,000 - $50,000', 'aqualuxe' ); ?></option>
                            <option value="50-100k"><?php esc_html_e( '$50,000 - $100,000', 'aqualuxe' ); ?></option>
                            <option value="100-150k"><?php esc_html_e( '$100,000 - $150,000', 'aqualuxe' ); ?></option>
                            <option value="150k+"><?php esc_html_e( '$150,000+', 'aqualuxe' ); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="franchise-form-row">
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="net_worth">
                            <?php esc_html_e( 'Net Worth', 'aqualuxe' ); ?>
                        </label>
                        <select id="net_worth" name="net_worth" class="franchise-form-select">
                            <option value=""><?php esc_html_e( 'Select Range', 'aqualuxe' ); ?></option>
                            <option value="100-250k"><?php esc_html_e( '$100,000 - $250,000', 'aqualuxe' ); ?></option>
                            <option value="250-500k"><?php esc_html_e( '$250,000 - $500,000', 'aqualuxe' ); ?></option>
                            <option value="500k-1m"><?php esc_html_e( '$500,000 - $1,000,000', 'aqualuxe' ); ?></option>
                            <option value="1m+"><?php esc_html_e( '$1,000,000+', 'aqualuxe' ); ?></option>
                        </select>
                    </div>
                    
                    <div class="franchise-form-field">
                        <label class="franchise-form-label" for="financing">
                            <?php esc_html_e( 'Financing Required', 'aqualuxe' ); ?>
                        </label>
                        <select id="financing" name="financing" class="franchise-form-select">
                            <option value=""><?php esc_html_e( 'Select Option', 'aqualuxe' ); ?></option>
                            <option value="yes"><?php esc_html_e( 'Yes', 'aqualuxe' ); ?></option>
                            <option value="no"><?php esc_html_e( 'No', 'aqualuxe' ); ?></option>
                            <option value="unsure"><?php esc_html_e( 'Unsure', 'aqualuxe' ); ?></option>
                        </select>
                    </div>
                </div>
                
                <div class="franchise-form-buttons">
                    <button type="button" class="franchise-form-button franchise-form-prev">
                        <?php esc_html_e( 'Previous', 'aqualuxe' ); ?>
                    </button>
                    <button type="button" class="franchise-form-button franchise-form-next">
                        <?php esc_html_e( 'Next', 'aqualuxe' ); ?>
                    </button>
                </div>
            </div>
            
            <!-- Step 4: Additional Information -->
            <div class="franchise-form-section hidden" data-step="3">
                <h3 class="franchise-form-section-title"><?php esc_html_e( 'Additional Information', 'aqualuxe' ); ?></h3>
                
                <div class="franchise-form-field">
                    <label class="franchise-form-label" for="location">
                        <?php esc_html_e( 'Preferred Location', 'aqualuxe' ); ?>
                        <span class="franchise-form-required">*</span>
                    </label>
                    <input type="text" id="location" name="location" class="franchise-form-input" required>
                    <div class="franchise-form-error error-location hidden"><?php esc_html_e( 'Preferred location is required.', 'aqualuxe' ); ?></div>
                </div>
                
                <div class="franchise-form-field">
                    <label class="franchise-form-label" for="timeline">
                        <?php esc_html_e( 'Timeline', 'aqualuxe' ); ?>
                    </label>
                    <select id="timeline" name="timeline" class="franchise-form-select">
                        <option value=""><?php esc_html_e( 'Select Timeline', 'aqualuxe' ); ?></option>
                        <option value="0-3"><?php esc_html_e( '0-3 months', 'aqualuxe' ); ?></option>
                        <option value="3-6"><?php esc_html_e( '3-6 months', 'aqualuxe' ); ?></option>
                        <option value="6-12"><?php esc_html_e( '6-12 months', 'aqualuxe' ); ?></option>
                        <option value="12+"><?php esc_html_e( '12+ months', 'aqualuxe' ); ?></option>
                    </select>
                </div>
                
                <div class="franchise-form-field">
                    <label class="franchise-form-label" for="message">
                        <?php esc_html_e( 'Additional Message', 'aqualuxe' ); ?>
                    </label>
                    <textarea id="message" name="message" rows="4" class="franchise-form-textarea" placeholder="<?php esc_attr_e( 'Please provide any additional information about your interest in our franchise opportunity...', 'aqualuxe' ); ?>"></textarea>
                </div>
                
                <div class="franchise-form-buttons">
                    <button type="button" class="franchise-form-button franchise-form-prev">
                        <?php esc_html_e( 'Previous', 'aqualuxe' ); ?>
                    </button>
                    <button type="submit" class="franchise-form-button franchise-inquiry-submit">
                        <?php esc_html_e( 'Submit Inquiry', 'aqualuxe' ); ?>
                    </button>
                </div>
            </div>
        </form>
        
        <!-- Loading Overlay -->
        <div class="franchise-loading hidden">
            <div class="loader">
                <div class="animate-spin"></div>
                <p><?php esc_html_e( 'Processing...', 'aqualuxe' ); ?></p>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'franchise_inquiry', 'aqualuxe_franchise_inquiry_shortcode' );

/**
 * Display franchise benefits section
 */
function aqualuxe_franchise_benefits_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'title' => __( 'Why Choose AquaLuxe Franchise', 'aqualuxe' ),
            'count' => 6,
        ),
        $atts,
        'franchise_benefits'
    );

    // Define benefits
    $benefits = array(
        array(
            'icon' => 'chart-line',
            'title' => __( 'High Profit Margins', 'aqualuxe' ),
            'description' => __( 'Enjoy industry-leading profit margins with our premium aquatic products and services.', 'aqualuxe' ),
        ),
        array(
            'icon' => 'users',
            'title' => __( 'Comprehensive Support', 'aqualuxe' ),
            'description' => __( 'Receive ongoing training, marketing, and operational support from our experienced team.', 'aqualuxe' ),
        ),
        array(
            'icon' => 'medal',
            'title' => __( 'Established Brand', 'aqualuxe' ),
            'description' => __( 'Leverage our premium brand reputation and established customer base.', 'aqualuxe' ),
        ),
        array(
            'icon' => 'lightbulb',
            'title' => __( 'Innovative Products', 'aqualuxe' ),
            'description' => __( 'Access to exclusive, innovative aquatic products and technologies.', 'aqualuxe' ),
        ),
        array(
            'icon' => 'map-marker-alt',
            'title' => __( 'Prime Territories', 'aqualuxe' ),
            'description' => __( 'Secure exclusive rights to operate in high-demand territories.', 'aqualuxe' ),
        ),
        array(
            'icon' => 'tools',
            'title' => __( 'Turnkey Operation', 'aqualuxe' ),
            'description' => __( 'Start with a proven business model and comprehensive operating systems.', 'aqualuxe' ),
        ),
        array(
            'icon' => 'bullhorn',
            'title' => __( 'Marketing Support', 'aqualuxe' ),
            'description' => __( 'Benefit from national marketing campaigns and local marketing assistance.', 'aqualuxe' ),
        ),
        array(
            'icon' => 'graduation-cap',
            'title' => __( 'Expert Training', 'aqualuxe' ),
            'description' => __( 'Comprehensive training programs for you and your staff.', 'aqualuxe' ),
        ),
    );

    // Limit benefits to the specified count
    $benefits = array_slice( $benefits, 0, intval( $atts['count'] ) );

    ob_start();
    ?>
    <div class="franchise-benefits">
        <h2 class="franchise-benefits-title"><?php echo esc_html( $atts['title'] ); ?></h2>
        
        <div class="franchise-benefits-grid">
            <?php foreach ( $benefits as $benefit ) : ?>
                <div class="franchise-benefit-item">
                    <div class="franchise-benefit-icon">
                        <i class="fas fa-<?php echo esc_attr( $benefit['icon'] ); ?>"></i>
                    </div>
                    <h3 class="franchise-benefit-title"><?php echo esc_html( $benefit['title'] ); ?></h3>
                    <p class="franchise-benefit-description"><?php echo esc_html( $benefit['description'] ); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'franchise_benefits', 'aqualuxe_franchise_benefits_shortcode' );

/**
 * Display franchise stats section
 */
function aqualuxe_franchise_stats_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'title' => '',
        ),
        $atts,
        'franchise_stats'
    );

    // Define stats
    $stats = array(
        array(
            'value' => '50+',
            'label' => __( 'Franchise Locations', 'aqualuxe' ),
        ),
        array(
            'value' => '95%',
            'label' => __( 'Franchise Success Rate', 'aqualuxe' ),
        ),
        array(
            'value' => '$250K+',
            'label' => __( 'Average Annual Revenue', 'aqualuxe' ),
        ),
        array(
            'value' => '15+',
            'label' => __( 'Years of Experience', 'aqualuxe' ),
        ),
    );

    ob_start();
    ?>
    <div class="franchise-stats">
        <div class="container mx-auto px-4">
            <?php if ( ! empty( $atts['title'] ) ) : ?>
                <h2 class="franchise-stats-title text-center mb-8"><?php echo esc_html( $atts['title'] ); ?></h2>
            <?php endif; ?>
            
            <div class="franchise-stats-grid">
                <?php foreach ( $stats as $stat ) : ?>
                    <div class="franchise-stat-item">
                        <div class="franchise-stat-value"><?php echo esc_html( $stat['value'] ); ?></div>
                        <div class="franchise-stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'franchise_stats', 'aqualuxe_franchise_stats_shortcode' );

/**
 * Display franchise process section
 */
function aqualuxe_franchise_process_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'title' => __( 'Franchise Application Process', 'aqualuxe' ),
        ),
        $atts,
        'franchise_process'
    );

    // Define process steps
    $steps = array(
        array(
            'number' => '1',
            'title' => __( 'Initial Inquiry', 'aqualuxe' ),
            'description' => __( 'Submit your franchise inquiry form to express your interest and provide basic information.', 'aqualuxe' ),
        ),
        array(
            'number' => '2',
            'title' => __( 'Qualification & Interview', 'aqualuxe' ),
            'description' => __( 'Our franchise team will review your application and schedule an initial interview to discuss your qualifications.', 'aqualuxe' ),
        ),
        array(
            'number' => '3',
            'title' => __( 'Disclosure Document', 'aqualuxe' ),
            'description' => __( 'Qualified candidates will receive our Franchise Disclosure Document (FDD) with detailed information about the opportunity.', 'aqualuxe' ),
        ),
        array(
            'number' => '4',
            'title' => __( 'Discovery Day', 'aqualuxe' ),
            'description' => __( 'Visit our headquarters to meet the team, learn more about operations, and experience our culture firsthand.', 'aqualuxe' ),
        ),
        array(
            'number' => '5',
            'title' => __( 'Agreement & Financing', 'aqualuxe' ),
            'description' => __( 'Review and sign the franchise agreement and secure financing for your new AquaLuxe franchise.', 'aqualuxe' ),
        ),
        array(
            'number' => '6',
            'title' => __( 'Training & Launch', 'aqualuxe' ),
            'description' => __( 'Complete our comprehensive training program and work with our team to launch your new AquaLuxe franchise.', 'aqualuxe' ),
        ),
    );

    ob_start();
    ?>
    <div class="franchise-process">
        <h2 class="franchise-process-title"><?php echo esc_html( $atts['title'] ); ?></h2>
        
        <div class="franchise-process-steps">
            <?php foreach ( $steps as $step ) : ?>
                <div class="franchise-process-step">
                    <div class="franchise-process-step-number"><?php echo esc_html( $step['number'] ); ?></div>
                    <div class="franchise-process-step-content">
                        <h3 class="franchise-process-step-title"><?php echo esc_html( $step['title'] ); ?></h3>
                        <p class="franchise-process-step-description"><?php echo esc_html( $step['description'] ); ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'franchise_process', 'aqualuxe_franchise_process_shortcode' );

/**
 * Display franchise testimonials section
 */
function aqualuxe_franchise_testimonials_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'title' => __( 'Franchise Success Stories', 'aqualuxe' ),
            'count' => 3,
        ),
        $atts,
        'franchise_testimonials'
    );

    // Define testimonials
    $testimonials = array(
        array(
            'quote' => __( 'Joining the AquaLuxe franchise network was the best business decision I\'ve made. The support from the corporate team has been exceptional, and our store has exceeded our revenue projections for the first year.', 'aqualuxe' ),
            'name' => __( 'Michael Chen', 'aqualuxe' ),
            'location' => __( 'San Francisco, CA', 'aqualuxe' ),
            'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
        ),
        array(
            'quote' => __( 'As someone with no prior experience in the aquarium industry, I was concerned about the learning curve. AquaLuxe\'s training program was comprehensive and gave me all the tools I needed to succeed. Two years in, and we\'re already expanding to a second location.', 'aqualuxe' ),
            'name' => __( 'Sarah Johnson', 'aqualuxe' ),
            'location' => __( 'Austin, TX', 'aqualuxe' ),
            'avatar' => 'https://randomuser.me/api/portraits/women/44.jpg',
        ),
        array(
            'quote' => __( 'The exclusive products and premium positioning of AquaLuxe have allowed us to stand out in a competitive market. Our customers love the quality and uniqueness of what we offer, and it shows in our repeat business.', 'aqualuxe' ),
            'name' => __( 'David Rodriguez', 'aqualuxe' ),
            'location' => __( 'Miami, FL', 'aqualuxe' ),
            'avatar' => 'https://randomuser.me/api/portraits/men/67.jpg',
        ),
        array(
            'quote' => __( 'The marketing support from AquaLuxe headquarters has been invaluable. Their national campaigns drive traffic to our store, and the local marketing assistance has helped us build strong community relationships.', 'aqualuxe' ),
            'name' => __( 'Emily Thompson', 'aqualuxe' ),
            'location' => __( 'Seattle, WA', 'aqualuxe' ),
            'avatar' => 'https://randomuser.me/api/portraits/women/33.jpg',
        ),
    );

    // Limit testimonials to the specified count
    $testimonials = array_slice( $testimonials, 0, intval( $atts['count'] ) );

    ob_start();
    ?>
    <div class="franchise-testimonials">
        <div class="container mx-auto px-4">
            <h2 class="franchise-testimonials-title"><?php echo esc_html( $atts['title'] ); ?></h2>
            
            <div class="franchise-testimonials-grid">
                <?php foreach ( $testimonials as $testimonial ) : ?>
                    <div class="franchise-testimonial-item">
                        <p class="franchise-testimonial-quote">"<?php echo esc_html( $testimonial['quote'] ); ?>"</p>
                        <div class="franchise-testimonial-author">
                            <div class="franchise-testimonial-avatar">
                                <img src="<?php echo esc_url( $testimonial['avatar'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>">
                            </div>
                            <div class="franchise-testimonial-info">
                                <div class="franchise-testimonial-name"><?php echo esc_html( $testimonial['name'] ); ?></div>
                                <div class="franchise-testimonial-location"><?php echo esc_html( $testimonial['location'] ); ?></div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode( 'franchise_testimonials', 'aqualuxe_franchise_testimonials_shortcode' );

/**
 * Display franchise FAQ section
 */
function aqualuxe_franchise_faq_shortcode( $atts ) {
    $atts = shortcode_atts(
        array(
            'title' => __( 'Frequently Asked Questions', 'aqualuxe' ),
        ),
        $atts,
        'franchise_faq'
    );

    // Define FAQs
    $faqs = array(
        array(
            'question' => __( 'What is the initial investment required?', 'aqualuxe' ),
            'answer' => __( 'The initial investment for an AquaLuxe franchise ranges from $150,000 to $300,000, depending on location, size, and market. This includes the franchise fee, store build-out, initial inventory, equipment, and working capital.', 'aqualuxe' ),
        ),
        array(
            'question' => __( 'Do I need experience in the aquarium industry?', 'aqualuxe' ),
            'answer' => __( 'No, prior experience in the aquarium industry is not required. We provide comprehensive training and ongoing support to ensure you have the knowledge and skills needed to operate your franchise successfully. However, a passion for aquatic life and customer service is essential.', 'aqualuxe' ),
        ),
        array(
            'question' => __( 'What territories are currently available?', 'aqualuxe' ),
            'answer' => __( 'We have territories available across the United States, with a focus on major metropolitan areas. We also have international opportunities in select markets. Please contact us for the most up-to-date information on available territories.', 'aqualuxe' ),
        ),
        array(
            'question' => __( 'What ongoing support does AquaLuxe provide to franchisees?', 'aqualuxe' ),
            'answer' => __( 'AquaLuxe provides comprehensive ongoing support including marketing assistance, operational guidance, product sourcing, technical support, and regular training updates. Our franchise support team is dedicated to helping you succeed and is available for consultation on all aspects of your business.', 'aqualuxe' ),
        ),
        array(
            'question' => __( 'How long does it take to open an AquaLuxe franchise?', 'aqualuxe' ),
            'answer' => __( 'The typical timeline from signing the franchise agreement to opening your store is 6-9 months. This includes site selection, lease negotiation, store build-out, training, and preparation for grand opening.', 'aqualuxe' ),
        ),
        array(
            'question' => __( 'What are the ongoing fees?', 'aqualuxe' ),
            'answer' => __( 'Franchisees pay a royalty fee of 6% of gross sales and contribute 2% to the national marketing fund. These fees help support our ongoing services and national brand building efforts that benefit all franchisees.', 'aqualuxe' ),
        ),
    );

    ob_start();
    ?>
    <div class="franchise-faq">
        <h2 class="franchise-faq-title"><?php echo esc_html( $atts['title'] ); ?></h2>
        
        <div class="franchise-faq-list">
            <?php foreach ( $faqs as $index => $faq ) : ?>
                <div class="franchise-faq-item" data-index="<?php echo esc_attr( $index ); ?>">
                    <h3 class="franchise-faq-question"><?php echo esc_html( $faq['question'] ); ?></h3>
                    <div class="franchise-faq-answer"><?php echo esc_html( $faq['answer'] ); ?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <script>
    jQuery(document).ready(function($) {
        $('.franchise-faq-question').on('click', function() {
            const item = $(this).parent();
            
            if (item.hasClass('active')) {
                item.removeClass('active');
            } else {
                item.addClass('active');
            }
        });
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode( 'franchise_faq', 'aqualuxe_franchise_faq_shortcode' );
