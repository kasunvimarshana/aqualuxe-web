<?php
/**
 * AquaLuxe Water Parameter Calculator
 *
 * Provides tools for calculating and managing aquarium water parameters
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * AquaLuxe Water Calculator Class
 */
class AquaLuxe_Water_Calculator {
    /**
     * Singleton instance
     *
     * @var AquaLuxe_Water_Calculator
     */
    private static $instance = null;

    /**
     * Get singleton instance
     *
     * @return AquaLuxe_Water_Calculator
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Add shortcodes
        add_shortcode('water_parameter_calculator', array($this, 'water_parameter_calculator_shortcode'));
        add_shortcode('tank_volume_calculator', array($this, 'tank_volume_calculator_shortcode'));
        add_shortcode('stocking_calculator', array($this, 'stocking_calculator_shortcode'));
        
        // Add AJAX handlers
        add_action('wp_ajax_aqualuxe_calculate_parameters', array($this, 'ajax_calculate_parameters'));
        add_action('wp_ajax_nopriv_aqualuxe_calculate_parameters', array($this, 'ajax_calculate_parameters'));
        
        add_action('wp_ajax_aqualuxe_calculate_volume', array($this, 'ajax_calculate_volume'));
        add_action('wp_ajax_nopriv_aqualuxe_calculate_volume', array($this, 'ajax_calculate_volume'));
        
        add_action('wp_ajax_aqualuxe_calculate_stocking', array($this, 'ajax_calculate_stocking'));
        add_action('wp_ajax_nopriv_aqualuxe_calculate_stocking', array($this, 'ajax_calculate_stocking'));
        
        // Enqueue scripts and styles
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        wp_enqueue_style(
            'aqualuxe-calculator',
            AQUALUXE_ASSETS_URI . '/css/calculator.css',
            array(),
            AQUALUXE_VERSION
        );
        
        wp_enqueue_script(
            'aqualuxe-calculator',
            AQUALUXE_ASSETS_URI . '/js/calculator.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script(
            'aqualuxe-calculator',
            'aqualuxeCalculator',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('aqualuxe-calculator-nonce'),
                'i18n' => array(
                    'error' => __('Error occurred. Please try again.', 'aqualuxe'),
                    'success' => __('Calculation complete!', 'aqualuxe'),
                    'loading' => __('Calculating...', 'aqualuxe'),
                ),
            )
        );
    }

    /**
     * Water parameter calculator shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function water_parameter_calculator_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Water Parameter Calculator', 'aqualuxe'),
        ), $atts);
        
        ob_start();
        ?>
        <div class="aqualuxe-calculator water-parameter-calculator">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            
            <div class="calculator-tabs">
                <ul class="tab-navigation">
                    <li class="active" data-tab="ph-adjustment"><?php esc_html_e('pH Adjustment', 'aqualuxe'); ?></li>
                    <li data-tab="hardness-adjustment"><?php esc_html_e('Water Hardness', 'aqualuxe'); ?></li>
                    <li data-tab="medication-dosage"><?php esc_html_e('Medication Dosage', 'aqualuxe'); ?></li>
                </ul>
                
                <div class="tab-content">
                    <!-- pH Adjustment Tab -->
                    <div class="tab-pane active" id="ph-adjustment">
                        <form class="calculator-form ph-calculator-form">
                            <div class="form-row">
                                <label for="current-ph"><?php esc_html_e('Current pH:', 'aqualuxe'); ?></label>
                                <input type="number" id="current-ph" name="current_ph" min="0" max="14" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="target-ph"><?php esc_html_e('Target pH:', 'aqualuxe'); ?></label>
                                <input type="number" id="target-ph" name="target_ph" min="0" max="14" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="tank-volume-ph"><?php esc_html_e('Tank Volume (gallons):', 'aqualuxe'); ?></label>
                                <input type="number" id="tank-volume-ph" name="tank_volume" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="adjustment-type"><?php esc_html_e('Adjustment Type:', 'aqualuxe'); ?></label>
                                <select id="adjustment-type" name="adjustment_type" required>
                                    <option value=""><?php esc_html_e('Select Adjustment Type', 'aqualuxe'); ?></option>
                                    <option value="increase"><?php esc_html_e('Increase pH', 'aqualuxe'); ?></option>
                                    <option value="decrease"><?php esc_html_e('Decrease pH', 'aqualuxe'); ?></option>
                                </select>
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                            </div>
                        </form>
                        
                        <div class="calculator-results ph-calculator-results" style="display: none;">
                            <h4><?php esc_html_e('Recommended Adjustments', 'aqualuxe'); ?></h4>
                            <div class="results-content"></div>
                        </div>
                    </div>
                    
                    <!-- Water Hardness Tab -->
                    <div class="tab-pane" id="hardness-adjustment">
                        <form class="calculator-form hardness-calculator-form">
                            <div class="form-row">
                                <label for="current-hardness"><?php esc_html_e('Current Hardness (dGH):', 'aqualuxe'); ?></label>
                                <input type="number" id="current-hardness" name="current_hardness" min="0" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="target-hardness"><?php esc_html_e('Target Hardness (dGH):', 'aqualuxe'); ?></label>
                                <input type="number" id="target-hardness" name="target_hardness" min="0" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="tank-volume-hardness"><?php esc_html_e('Tank Volume (gallons):', 'aqualuxe'); ?></label>
                                <input type="number" id="tank-volume-hardness" name="tank_volume" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="hardness-adjustment-type"><?php esc_html_e('Adjustment Type:', 'aqualuxe'); ?></label>
                                <select id="hardness-adjustment-type" name="adjustment_type" required>
                                    <option value=""><?php esc_html_e('Select Adjustment Type', 'aqualuxe'); ?></option>
                                    <option value="increase"><?php esc_html_e('Increase Hardness', 'aqualuxe'); ?></option>
                                    <option value="decrease"><?php esc_html_e('Decrease Hardness', 'aqualuxe'); ?></option>
                                </select>
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                            </div>
                        </form>
                        
                        <div class="calculator-results hardness-calculator-results" style="display: none;">
                            <h4><?php esc_html_e('Recommended Adjustments', 'aqualuxe'); ?></h4>
                            <div class="results-content"></div>
                        </div>
                    </div>
                    
                    <!-- Medication Dosage Tab -->
                    <div class="tab-pane" id="medication-dosage">
                        <form class="calculator-form medication-calculator-form">
                            <div class="form-row">
                                <label for="medication-type"><?php esc_html_e('Medication Type:', 'aqualuxe'); ?></label>
                                <select id="medication-type" name="medication_type" required>
                                    <option value=""><?php esc_html_e('Select Medication Type', 'aqualuxe'); ?></option>
                                    <option value="ich"><?php esc_html_e('Ich Treatment', 'aqualuxe'); ?></option>
                                    <option value="fungal"><?php esc_html_e('Fungal Treatment', 'aqualuxe'); ?></option>
                                    <option value="bacterial"><?php esc_html_e('Bacterial Treatment', 'aqualuxe'); ?></option>
                                    <option value="parasite"><?php esc_html_e('Parasite Treatment', 'aqualuxe'); ?></option>
                                    <option value="custom"><?php esc_html_e('Custom Medication', 'aqualuxe'); ?></option>
                                </select>
                            </div>
                            
                            <div class="form-row custom-medication-row" style="display: none;">
                                <label for="custom-dosage"><?php esc_html_e('Dosage Rate (per gallon):', 'aqualuxe'); ?></label>
                                <input type="text" id="custom-dosage" name="custom_dosage" placeholder="e.g., 1 drop">
                            </div>
                            
                            <div class="form-row">
                                <label for="tank-volume-medication"><?php esc_html_e('Tank Volume (gallons):', 'aqualuxe'); ?></label>
                                <input type="number" id="tank-volume-medication" name="tank_volume" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                            </div>
                        </form>
                        
                        <div class="calculator-results medication-calculator-results" style="display: none;">
                            <h4><?php esc_html_e('Recommended Dosage', 'aqualuxe'); ?></h4>
                            <div class="results-content"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="calculator-notes">
                <h4><?php esc_html_e('Important Notes', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php esc_html_e('Always make water parameter changes gradually to avoid stressing your fish.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Test your water parameters regularly after making adjustments.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Different fish species have different water parameter requirements.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('These calculations are estimates. Always follow product instructions for exact dosages.', 'aqualuxe'); ?></li>
                </ul>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Tank volume calculator shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function tank_volume_calculator_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Tank Volume Calculator', 'aqualuxe'),
        ), $atts);
        
        ob_start();
        ?>
        <div class="aqualuxe-calculator tank-volume-calculator">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            
            <div class="calculator-tabs">
                <ul class="tab-navigation">
                    <li class="active" data-tab="rectangular"><?php esc_html_e('Rectangular Tank', 'aqualuxe'); ?></li>
                    <li data-tab="cylindrical"><?php esc_html_e('Cylindrical Tank', 'aqualuxe'); ?></li>
                    <li data-tab="bowfront"><?php esc_html_e('Bowfront Tank', 'aqualuxe'); ?></li>
                </ul>
                
                <div class="tab-content">
                    <!-- Rectangular Tank Tab -->
                    <div class="tab-pane active" id="rectangular">
                        <form class="calculator-form rectangular-calculator-form">
                            <div class="form-row">
                                <label for="rect-length"><?php esc_html_e('Length (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="rect-length" name="length" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="rect-width"><?php esc_html_e('Width (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="rect-width" name="width" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="rect-height"><?php esc_html_e('Height (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="rect-height" name="height" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="rect-substrate"><?php esc_html_e('Substrate Depth (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="rect-substrate" name="substrate" min="0" step="0.1" value="0">
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                            </div>
                        </form>
                        
                        <div class="calculator-results rectangular-calculator-results" style="display: none;">
                            <h4><?php esc_html_e('Tank Volume Results', 'aqualuxe'); ?></h4>
                            <div class="results-content"></div>
                        </div>
                    </div>
                    
                    <!-- Cylindrical Tank Tab -->
                    <div class="tab-pane" id="cylindrical">
                        <form class="calculator-form cylindrical-calculator-form">
                            <div class="form-row">
                                <label for="cyl-diameter"><?php esc_html_e('Diameter (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="cyl-diameter" name="diameter" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="cyl-height"><?php esc_html_e('Height (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="cyl-height" name="height" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="cyl-substrate"><?php esc_html_e('Substrate Depth (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="cyl-substrate" name="substrate" min="0" step="0.1" value="0">
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                            </div>
                        </form>
                        
                        <div class="calculator-results cylindrical-calculator-results" style="display: none;">
                            <h4><?php esc_html_e('Tank Volume Results', 'aqualuxe'); ?></h4>
                            <div class="results-content"></div>
                        </div>
                    </div>
                    
                    <!-- Bowfront Tank Tab -->
                    <div class="tab-pane" id="bowfront">
                        <form class="calculator-form bowfront-calculator-form">
                            <div class="form-row">
                                <label for="bow-length"><?php esc_html_e('Length (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="bow-length" name="length" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="bow-width-back"><?php esc_html_e('Width at Back (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="bow-width-back" name="width_back" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="bow-width-center"><?php esc_html_e('Width at Center (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="bow-width-center" name="width_center" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="bow-height"><?php esc_html_e('Height (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="bow-height" name="height" min="1" step="0.1" required>
                            </div>
                            
                            <div class="form-row">
                                <label for="bow-substrate"><?php esc_html_e('Substrate Depth (inches):', 'aqualuxe'); ?></label>
                                <input type="number" id="bow-substrate" name="substrate" min="0" step="0.1" value="0">
                            </div>
                            
                            <div class="form-row">
                                <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                            </div>
                        </form>
                        
                        <div class="calculator-results bowfront-calculator-results" style="display: none;">
                            <h4><?php esc_html_e('Tank Volume Results', 'aqualuxe'); ?></h4>
                            <div class="results-content"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="calculator-notes">
                <h4><?php esc_html_e('Volume Conversion', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php esc_html_e('1 US Gallon = 3.78541 Liters', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('1 US Gallon = 0.83267 Imperial Gallons', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('1 Cubic Foot = 7.48052 US Gallons', 'aqualuxe'); ?></li>
                </ul>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Stocking calculator shortcode
     *
     * @param array $atts Shortcode attributes.
     * @return string Shortcode output.
     */
    public function stocking_calculator_shortcode($atts) {
        $atts = shortcode_atts(array(
            'title' => __('Fish Stocking Calculator', 'aqualuxe'),
        ), $atts);
        
        ob_start();
        ?>
        <div class="aqualuxe-calculator stocking-calculator">
            <h3><?php echo esc_html($atts['title']); ?></h3>
            
            <form class="calculator-form stocking-calculator-form">
                <div class="form-row">
                    <label for="tank-volume-stocking"><?php esc_html_e('Tank Volume (gallons):', 'aqualuxe'); ?></label>
                    <input type="number" id="tank-volume-stocking" name="tank_volume" min="1" step="0.1" required>
                </div>
                
                <div class="form-row">
                    <label for="filtration-capacity"><?php esc_html_e('Filtration Capacity (gallons):', 'aqualuxe'); ?></label>
                    <input type="number" id="filtration-capacity" name="filtration_capacity" min="0" step="0.1" placeholder="<?php esc_attr_e('Optional', 'aqualuxe'); ?>">
                </div>
                
                <div class="form-row">
                    <label for="tank-type"><?php esc_html_e('Tank Type:', 'aqualuxe'); ?></label>
                    <select id="tank-type" name="tank_type" required>
                        <option value=""><?php esc_html_e('Select Tank Type', 'aqualuxe'); ?></option>
                        <option value="freshwater"><?php esc_html_e('Freshwater Community', 'aqualuxe'); ?></option>
                        <option value="cichlid"><?php esc_html_e('Cichlid Tank', 'aqualuxe'); ?></option>
                        <option value="goldfish"><?php esc_html_e('Goldfish Tank', 'aqualuxe'); ?></option>
                        <option value="saltwater"><?php esc_html_e('Saltwater/Reef', 'aqualuxe'); ?></option>
                        <option value="planted"><?php esc_html_e('Heavily Planted', 'aqualuxe'); ?></option>
                    </select>
                </div>
                
                <div class="form-row">
                    <label><?php esc_html_e('Fish List:', 'aqualuxe'); ?></label>
                    <div class="fish-list-container">
                        <div class="fish-entry">
                            <input type="text" name="fish_name[]" placeholder="<?php esc_attr_e('Fish Name', 'aqualuxe'); ?>" required>
                            <input type="number" name="fish_count[]" placeholder="<?php esc_attr_e('Count', 'aqualuxe'); ?>" min="1" required>
                            <input type="number" name="fish_size[]" placeholder="<?php esc_attr_e('Adult Size (inches)', 'aqualuxe'); ?>" min="0.1" step="0.1" required>
                            <button type="button" class="button remove-fish" style="display: none;"><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                        </div>
                    </div>
                    <button type="button" class="button add-fish"><?php esc_html_e('Add Another Fish', 'aqualuxe'); ?></button>
                </div>
                
                <div class="form-row">
                    <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate Stocking Level', 'aqualuxe'); ?></button>
                </div>
            </form>
            
            <div class="calculator-results stocking-calculator-results" style="display: none;">
                <h4><?php esc_html_e('Stocking Level Results', 'aqualuxe'); ?></h4>
                <div class="results-content"></div>
                <div class="stocking-meter">
                    <div class="stocking-meter-bar">
                        <div class="stocking-meter-fill"></div>
                    </div>
                    <div class="stocking-meter-labels">
                        <span class="understocked"><?php esc_html_e('Understocked', 'aqualuxe'); ?></span>
                        <span class="ideal"><?php esc_html_e('Ideal', 'aqualuxe'); ?></span>
                        <span class="overstocked"><?php esc_html_e('Overstocked', 'aqualuxe'); ?></span>
                    </div>
                </div>
            </div>
            
            <div class="calculator-notes">
                <h4><?php esc_html_e('Stocking Guidelines', 'aqualuxe'); ?></h4>
                <ul>
                    <li><?php esc_html_e('The general rule is 1 inch of fish per gallon for small fish, and 1 inch per 2 gallons for larger fish.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Consider the adult size of fish when calculating stocking levels.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('More active fish require more space than sedentary fish.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Overstocking can lead to poor water quality and stressed fish.', 'aqualuxe'); ?></li>
                    <li><?php esc_html_e('Regular water changes and proper filtration can help maintain water quality in heavily stocked tanks.', 'aqualuxe'); ?></li>
                </ul>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * AJAX handler for calculating water parameters
     */
    public function ajax_calculate_parameters() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-calculator-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
            exit;
        }
        
        $tab = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : '';
        
        switch ($tab) {
            case 'ph-adjustment':
                $this->calculate_ph_adjustment();
                break;
                
            case 'hardness-adjustment':
                $this->calculate_hardness_adjustment();
                break;
                
            case 'medication-dosage':
                $this->calculate_medication_dosage();
                break;
                
            default:
                wp_send_json_error(array('message' => __('Invalid calculation type.', 'aqualuxe')));
                break;
        }
        
        exit;
    }

    /**
     * Calculate pH adjustment
     */
    private function calculate_ph_adjustment() {
        $current_ph = isset($_POST['current_ph']) ? floatval($_POST['current_ph']) : 0;
        $target_ph = isset($_POST['target_ph']) ? floatval($_POST['target_ph']) : 0;
        $tank_volume = isset($_POST['tank_volume']) ? floatval($_POST['tank_volume']) : 0;
        $adjustment_type = isset($_POST['adjustment_type']) ? sanitize_text_field($_POST['adjustment_type']) : '';
        
        // Validate inputs
        if ($current_ph <= 0 || $current_ph > 14 || $target_ph <= 0 || $target_ph > 14 || $tank_volume <= 0) {
            wp_send_json_error(array('message' => __('Please enter valid values.', 'aqualuxe')));
            exit;
        }
        
        // Check if adjustment type matches the actual change needed
        $actual_change = ($target_ph > $current_ph) ? 'increase' : 'decrease';
        if ($adjustment_type !== $actual_change) {
            wp_send_json_error(array(
                'message' => sprintf(
                    __('Your selected adjustment type (%1$s) does not match the change needed (%2$s) based on your current and target pH values.', 'aqualuxe'),
                    $adjustment_type === 'increase' ? __('increase', 'aqualuxe') : __('decrease', 'aqualuxe'),
                    $actual_change === 'increase' ? __('increase', 'aqualuxe') : __('decrease', 'aqualuxe')
                )
            ));
            exit;
        }
        
        $ph_difference = abs($target_ph - $current_ph);
        $html = '';
        
        if ($adjustment_type === 'increase') {
            $html .= '<p>' . sprintf(__('To increase pH from %1$s to %2$s in your %3$s gallon tank:', 'aqualuxe'), $current_ph, $target_ph, $tank_volume) . '</p>';
            $html .= '<ul>';
            
            // Baking soda method
            $baking_soda = $this->calculate_baking_soda_for_ph($ph_difference, $tank_volume);
            $html .= '<li>' . sprintf(__('<strong>Baking Soda Method:</strong> Dissolve %s teaspoons of baking soda in a cup of tank water, then add slowly to the tank.', 'aqualuxe'), $baking_soda) . '</li>';
            
            // Crushed coral method
            $coral_amount = $tank_volume * 0.1;
            $html .= '<li>' . sprintf(__('<strong>Crushed Coral Method:</strong> Add approximately %s cups of crushed coral to your filter media.', 'aqualuxe'), number_format($coral_amount, 1)) . '</li>';
            
            // Limestone method
            $limestone_amount = $tank_volume * 0.05;
            $html .= '<li>' . sprintf(__('<strong>Limestone Method:</strong> Add approximately %s pounds of limestone rocks to your aquarium.', 'aqualuxe'), number_format($limestone_amount, 1)) . '</li>';
            
            $html .= '</ul>';
            
        } else { // decrease
            $html .= '<p>' . sprintf(__('To decrease pH from %1$s to %2$s in your %3$s gallon tank:', 'aqualuxe'), $current_ph, $target_ph, $tank_volume) . '</p>';
            $html .= '<ul>';
            
            // Driftwood method
            $driftwood_amount = $tank_volume * 0.1;
            $html .= '<li>' . sprintf(__('<strong>Driftwood Method:</strong> Add approximately %s pounds of driftwood to your aquarium.', 'aqualuxe'), number_format($driftwood_amount, 1)) . '</li>';
            
            // Peat moss method
            $peat_amount = $tank_volume * 0.05;
            $html .= '<li>' . sprintf(__('<strong>Peat Moss Method:</strong> Add approximately %s cups of peat moss to your filter media.', 'aqualuxe'), number_format($peat_amount, 1)) . '</li>';
            
            // Almond leaves method
            $leaves_count = ceil($tank_volume / 10);
            $html .= '<li>' . sprintf(__('<strong>Indian Almond Leaves:</strong> Add %d leaves per 10 gallons (approximately %d leaves for your tank).', 'aqualuxe'), 1, $leaves_count) . '</li>';
            
            // CO2 method
            $html .= '<li>' . __('<strong>CO2 Injection:</strong> Consider a CO2 injection system for planted tanks to naturally lower pH.', 'aqualuxe') . '</li>';
            
            $html .= '</ul>';
        }
        
        $html .= '<div class="calculator-warning">';
        $html .= '<p><strong>' . __('Important:', 'aqualuxe') . '</strong></p>';
        $html .= '<ul>';
        $html .= '<li>' . __('Make pH changes gradually (no more than 0.2 pH per day) to avoid stressing fish.', 'aqualuxe') . '</li>';
        $html .= '<li>' . __('Test your water regularly while making adjustments.', 'aqualuxe') . '</li>';
        $html .= '<li>' . __('Consider your fish species\' specific pH requirements.', 'aqualuxe') . '</li>';
        $html .= '</ul>';
        $html .= '</div>';
        
        wp_send_json_success(array('html' => $html));
    }

    /**
     * Calculate baking soda amount for pH adjustment
     * 
     * @param float $ph_difference pH difference.
     * @param float $tank_volume Tank volume in gallons.
     * @return string Formatted amount.
     */
    private function calculate_baking_soda_for_ph($ph_difference, $tank_volume) {
        // Base amount: 1 teaspoon per 10 gallons for a 0.2 pH increase
        $base_amount = $tank_volume / 10;
        $ph_factor = $ph_difference / 0.2;
        $amount = $base_amount * $ph_factor;
        
        return number_format($amount, 1);
    }

    /**
     * Calculate hardness adjustment
     */
    private function calculate_hardness_adjustment() {
        $current_hardness = isset($_POST['current_hardness']) ? floatval($_POST['current_hardness']) : 0;
        $target_hardness = isset($_POST['target_hardness']) ? floatval($_POST['target_hardness']) : 0;
        $tank_volume = isset($_POST['tank_volume']) ? floatval($_POST['tank_volume']) : 0;
        $adjustment_type = isset($_POST['adjustment_type']) ? sanitize_text_field($_POST['adjustment_type']) : '';
        
        // Validate inputs
        if ($current_hardness < 0 || $target_hardness < 0 || $tank_volume <= 0) {
            wp_send_json_error(array('message' => __('Please enter valid values.', 'aqualuxe')));
            exit;
        }
        
        // Check if adjustment type matches the actual change needed
        $actual_change = ($target_hardness > $current_hardness) ? 'increase' : 'decrease';
        if ($adjustment_type !== $actual_change) {
            wp_send_json_error(array(
                'message' => sprintf(
                    __('Your selected adjustment type (%1$s) does not match the change needed (%2$s) based on your current and target hardness values.', 'aqualuxe'),
                    $adjustment_type === 'increase' ? __('increase', 'aqualuxe') : __('decrease', 'aqualuxe'),
                    $actual_change === 'increase' ? __('increase', 'aqualuxe') : __('decrease', 'aqualuxe')
                )
            ));
            exit;
        }
        
        $hardness_difference = abs($target_hardness - $current_hardness);
        $html = '';
        
        if ($adjustment_type === 'increase') {
            $html .= '<p>' . sprintf(__('To increase water hardness from %1$s dGH to %2$s dGH in your %3$s gallon tank:', 'aqualuxe'), $current_hardness, $target_hardness, $tank_volume) . '</p>';
            $html .= '<ul>';
            
            // Calcium carbonate method
            $calcium_amount = $this->calculate_calcium_for_hardness($hardness_difference, $tank_volume);
            $html .= '<li>' . sprintf(__('<strong>Calcium Carbonate Method:</strong> Add %s teaspoons of crushed coral or limestone to your filter media.', 'aqualuxe'), $calcium_amount) . '</li>';
            
            // Epsom salt method
            $epsom_amount = $this->calculate_epsom_for_hardness($hardness_difference, $tank_volume);
            $html .= '<li>' . sprintf(__('<strong>Epsom Salt Method:</strong> Dissolve %s teaspoons of Epsom salt in a cup of tank water, then add slowly to the tank.', 'aqualuxe'), $epsom_amount) . '</li>';
            
            // Commercial hardness increaser
            $commercial_amount = $tank_volume * ($hardness_difference / 4);
            $html .= '<li>' . sprintf(__('<strong>Commercial Hardness Increaser:</strong> Use approximately %s ml of commercial hardness increaser (follow product instructions for exact dosage).', 'aqualuxe'), number_format($commercial_amount, 1)) . '</li>';
            
            $html .= '</ul>';
            
        } else { // decrease
            $html .= '<p>' . sprintf(__('To decrease water hardness from %1$s dGH to %2$s dGH in your %3$s gallon tank:', 'aqualuxe'), $current_hardness, $target_hardness, $tank_volume) . '</p>';
            $html .= '<ul>';
            
            // RO water method
            $ro_percent = min(100, ($hardness_difference / $current_hardness) * 100);
            $ro_gallons = ($tank_volume * $ro_percent) / 100;
            $html .= '<li>' . sprintf(__('<strong>RO Water Method:</strong> Replace approximately %s%% of your tank water (about %s gallons) with RO or distilled water.', 'aqualuxe'), number_format($ro_percent, 0), number_format($ro_gallons, 1)) . '</li>';
            
            // Peat moss method
            $peat_amount = $tank_volume * 0.05 * ($hardness_difference / 4);
            $html .= '<li>' . sprintf(__('<strong>Peat Moss Method:</strong> Add approximately %s cups of peat moss to your filter media.', 'aqualuxe'), number_format($peat_amount, 1)) . '</li>';
            
            // Driftwood method
            $html .= '<li>' . __('<strong>Driftwood Method:</strong> Add driftwood to your aquarium to gradually soften water over time.', 'aqualuxe') . '</li>';
            
            $html .= '</ul>';
        }
        
        $html .= '<div class="calculator-warning">';
        $html .= '<p><strong>' . __('Important:', 'aqualuxe') . '</strong></p>';
        $html .= '<ul>';
        $html .= '<li>' . __('Make hardness changes gradually to avoid stressing fish.', 'aqualuxe') . '</li>';
        $html .= '<li>' . __('Test your water regularly while making adjustments.', 'aqualuxe') . '</li>';
        $html .= '<li>' . __('Consider your fish species\' specific hardness requirements.', 'aqualuxe') . '</li>';
        $html .= '</ul>';
        $html .= '</div>';
        
        wp_send_json_success(array('html' => $html));
    }

    /**
     * Calculate calcium amount for hardness adjustment
     * 
     * @param float $hardness_difference Hardness difference.
     * @param float $tank_volume Tank volume in gallons.
     * @return string Formatted amount.
     */
    private function calculate_calcium_for_hardness($hardness_difference, $tank_volume) {
        // Base amount: 1 teaspoon per 10 gallons for a 2 dGH increase
        $base_amount = $tank_volume / 10;
        $hardness_factor = $hardness_difference / 2;
        $amount = $base_amount * $hardness_factor;
        
        return number_format($amount, 1);
    }

    /**
     * Calculate Epsom salt amount for hardness adjustment
     * 
     * @param float $hardness_difference Hardness difference.
     * @param float $tank_volume Tank volume in gallons.
     * @return string Formatted amount.
     */
    private function calculate_epsom_for_hardness($hardness_difference, $tank_volume) {
        // Base amount: 1 teaspoon per 10 gallons for a 1 dGH increase
        $base_amount = $tank_volume / 10;
        $hardness_factor = $hardness_difference / 1;
        $amount = $base_amount * $hardness_factor;
        
        return number_format($amount, 1);
    }

    /**
     * Calculate medication dosage
     */
    private function calculate_medication_dosage() {
        $medication_type = isset($_POST['medication_type']) ? sanitize_text_field($_POST['medication_type']) : '';
        $custom_dosage = isset($_POST['custom_dosage']) ? sanitize_text_field($_POST['custom_dosage']) : '';
        $tank_volume = isset($_POST['tank_volume']) ? floatval($_POST['tank_volume']) : 0;
        
        // Validate inputs
        if (empty($medication_type) || $tank_volume <= 0) {
            wp_send_json_error(array('message' => __('Please enter valid values.', 'aqualuxe')));
            exit;
        }
        
        if ($medication_type === 'custom' && empty($custom_dosage)) {
            wp_send_json_error(array('message' => __('Please enter a custom dosage rate.', 'aqualuxe')));
            exit;
        }
        
        $html = '';
        
        switch ($medication_type) {
            case 'ich':
                $html .= '<p>' . sprintf(__('Recommended Ich Treatment Dosage for %s gallon tank:', 'aqualuxe'), $tank_volume) . '</p>';
                $html .= '<ul>';
                $html .= '<li>' . sprintf(__('<strong>Malachite Green:</strong> %s ml', 'aqualuxe'), number_format($tank_volume * 0.05, 1)) . '</li>';
                $html .= '<li>' . sprintf(__('<strong>Formalin:</strong> %s ml', 'aqualuxe'), number_format($tank_volume * 0.1, 1)) . '</li>';
                $html .= '<li>' . sprintf(__('<strong>Salt Treatment:</strong> %s tablespoons of aquarium salt', 'aqualuxe'), number_format($tank_volume * 0.1, 1)) . '</li>';
                $html .= '</ul>';
                break;
                
            case 'fungal':
                $html .= '<p>' . sprintf(__('Recommended Fungal Treatment Dosage for %s gallon tank:', 'aqualuxe'), $tank_volume) . '</p>';
                $html .= '<ul>';
                $html .= '<li>' . sprintf(__('<strong>Methylene Blue:</strong> %s ml', 'aqualuxe'), number_format($tank_volume * 0.03, 1)) . '</li>';
                $html .= '<li>' . sprintf(__('<strong>Pimafix:</strong> %s ml', 'aqualuxe'), number_format($tank_volume * 0.5, 1)) . '</li>';
                $html .= '</ul>';
                break;
                
            case 'bacterial':
                $html .= '<p>' . sprintf(__('Recommended Bacterial Treatment Dosage for %s gallon tank:', 'aqualuxe'), $tank_volume) . '</p>';
                $html .= '<ul>';
                $html .= '<li>' . sprintf(__('<strong>Erythromycin:</strong> %s mg', 'aqualuxe'), number_format($tank_volume * 5, 0)) . '</li>';
                $html .= '<li>' . sprintf(__('<strong>Melafix:</strong> %s ml', 'aqualuxe'), number_format($tank_volume * 0.5, 1)) . '</li>';
                $html .= '</ul>';
                break;
                
            case 'parasite':
                $html .= '<p>' . sprintf(__('Recommended Parasite Treatment Dosage for %s gallon tank:', 'aqualuxe'), $tank_volume) . '</p>';
                $html .= '<ul>';
                $html .= '<li>' . sprintf(__('<strong>Praziquantel:</strong> %s mg', 'aqualuxe'), number_format($tank_volume * 2, 0)) . '</li>';
                $html .= '<li>' . sprintf(__('<strong>Levamisole:</strong> %s mg', 'aqualuxe'), number_format($tank_volume * 3, 0)) . '</li>';
                $html .= '</ul>';
                break;
                
            case 'custom':
                $html .= '<p>' . sprintf(__('Custom Medication Dosage for %s gallon tank:', 'aqualuxe'), $tank_volume) . '</p>';
                $html .= '<p>' . sprintf(__('Based on your dosage rate of %1$s per gallon, you should use %2$s for your entire tank.', 'aqualuxe'), $custom_dosage, '<strong>' . $custom_dosage . ' × ' . $tank_volume . '</strong>') . '</p>';
                break;
        }
        
        $html .= '<div class="calculator-warning">';
        $html .= '<p><strong>' . __('Important:', 'aqualuxe') . '</strong></p>';
        $html .= '<ul>';
        $html .= '<li>' . __('These are general guidelines. Always follow the specific instructions on your medication product.', 'aqualuxe') . '</li>';
        $html .= '<li>' . __('Remove carbon filtration before medicating.', 'aqualuxe') . '</li>';
        $html .= '<li>' . __('Some medications may not be safe for all fish species, plants, or invertebrates.', 'aqualuxe') . '</li>';
        $html .= '<li>' . __('Perform a water change before adding a second dose or a different medication.', 'aqualuxe') . '</li>';
        $html .= '</ul>';
        $html .= '</div>';
        
        wp_send_json_success(array('html' => $html));
    }

    /**
     * AJAX handler for calculating tank volume
     */
    public function ajax_calculate_volume() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-calculator-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
            exit;
        }
        
        $tank_type = isset($_POST['tank_type']) ? sanitize_text_field($_POST['tank_type']) : '';
        
        switch ($tank_type) {
            case 'rectangular':
                $this->calculate_rectangular_volume();
                break;
                
            case 'cylindrical':
                $this->calculate_cylindrical_volume();
                break;
                
            case 'bowfront':
                $this->calculate_bowfront_volume();
                break;
                
            default:
                wp_send_json_error(array('message' => __('Invalid tank type.', 'aqualuxe')));
                break;
        }
        
        exit;
    }

    /**
     * Calculate rectangular tank volume
     */
    private function calculate_rectangular_volume() {
        $length = isset($_POST['length']) ? floatval($_POST['length']) : 0;
        $width = isset($_POST['width']) ? floatval($_POST['width']) : 0;
        $height = isset($_POST['height']) ? floatval($_POST['height']) : 0;
        $substrate = isset($_POST['substrate']) ? floatval($_POST['substrate']) : 0;
        
        // Validate inputs
        if ($length <= 0 || $width <= 0 || $height <= 0) {
            wp_send_json_error(array('message' => __('Please enter valid dimensions.', 'aqualuxe')));
            exit;
        }
        
        // Adjust height for substrate
        $water_height = $height - $substrate;
        if ($water_height <= 0) {
            wp_send_json_error(array('message' => __('Substrate depth cannot be greater than or equal to tank height.', 'aqualuxe')));
            exit;
        }
        
        // Calculate volume in cubic inches
        $volume_cubic_inches = $length * $width * $water_height;
        
        // Convert to gallons (1 gallon = 231 cubic inches)
        $volume_gallons = $volume_cubic_inches / 231;
        
        // Convert to liters (1 gallon = 3.78541 liters)
        $volume_liters = $volume_gallons * 3.78541;
        
        // Calculate substrate volume
        $substrate_volume_cubic_inches = $length * $width * $substrate;
        $substrate_volume_gallons = $substrate_volume_cubic_inches / 231;
        
        $html = '';
        $html .= '<div class="volume-results">';
        $html .= '<p>' . __('Tank Dimensions:', 'aqualuxe') . ' ' . $length . '" × ' . $width . '" × ' . $height . '"</p>';
        
        if ($substrate > 0) {
            $html .= '<p>' . __('Water Height (after substrate):', 'aqualuxe') . ' ' . $water_height . '"</p>';
            $html .= '<p>' . __('Substrate Volume:', 'aqualuxe') . ' ' . number_format($substrate_volume_gallons, 1) . ' ' . __('gallons', 'aqualuxe') . '</p>';
        }
        
        $html .= '<p class="volume-main">' . __('Total Water Volume:', 'aqualuxe') . ' <strong>' . number_format($volume_gallons, 1) . ' ' . __('gallons', 'aqualuxe') . ' (' . number_format($volume_liters, 1) . ' ' . __('liters', 'aqualuxe') . ')</strong></p>';
        
        // Standard tank size comparison
        $html .= $this->get_standard_tank_comparison($volume_gallons);
        
        $html .= '</div>';
        
        wp_send_json_success(array('html' => $html));
    }

    /**
     * Calculate cylindrical tank volume
     */
    private function calculate_cylindrical_volume() {
        $diameter = isset($_POST['diameter']) ? floatval($_POST['diameter']) : 0;
        $height = isset($_POST['height']) ? floatval($_POST['height']) : 0;
        $substrate = isset($_POST['substrate']) ? floatval($_POST['substrate']) : 0;
        
        // Validate inputs
        if ($diameter <= 0 || $height <= 0) {
            wp_send_json_error(array('message' => __('Please enter valid dimensions.', 'aqualuxe')));
            exit;
        }
        
        // Adjust height for substrate
        $water_height = $height - $substrate;
        if ($water_height <= 0) {
            wp_send_json_error(array('message' => __('Substrate depth cannot be greater than or equal to tank height.', 'aqualuxe')));
            exit;
        }
        
        // Calculate radius
        $radius = $diameter / 2;
        
        // Calculate volume in cubic inches (π × r² × h)
        $volume_cubic_inches = M_PI * pow($radius, 2) * $water_height;
        
        // Convert to gallons (1 gallon = 231 cubic inches)
        $volume_gallons = $volume_cubic_inches / 231;
        
        // Convert to liters (1 gallon = 3.78541 liters)
        $volume_liters = $volume_gallons * 3.78541;
        
        // Calculate substrate volume
        $substrate_volume_cubic_inches = M_PI * pow($radius, 2) * $substrate;
        $substrate_volume_gallons = $substrate_volume_cubic_inches / 231;
        
        $html = '';
        $html .= '<div class="volume-results">';
        $html .= '<p>' . __('Tank Dimensions:', 'aqualuxe') . ' ' . __('Diameter:', 'aqualuxe') . ' ' . $diameter . '", ' . __('Height:', 'aqualuxe') . ' ' . $height . '"</p>';
        
        if ($substrate > 0) {
            $html .= '<p>' . __('Water Height (after substrate):', 'aqualuxe') . ' ' . $water_height . '"</p>';
            $html .= '<p>' . __('Substrate Volume:', 'aqualuxe') . ' ' . number_format($substrate_volume_gallons, 1) . ' ' . __('gallons', 'aqualuxe') . '</p>';
        }
        
        $html .= '<p class="volume-main">' . __('Total Water Volume:', 'aqualuxe') . ' <strong>' . number_format($volume_gallons, 1) . ' ' . __('gallons', 'aqualuxe') . ' (' . number_format($volume_liters, 1) . ' ' . __('liters', 'aqualuxe') . ')</strong></p>';
        
        // Standard tank size comparison
        $html .= $this->get_standard_tank_comparison($volume_gallons);
        
        $html .= '</div>';
        
        wp_send_json_success(array('html' => $html));
    }

    /**
     * Calculate bowfront tank volume
     */
    private function calculate_bowfront_volume() {
        $length = isset($_POST['length']) ? floatval($_POST['length']) : 0;
        $width_back = isset($_POST['width_back']) ? floatval($_POST['width_back']) : 0;
        $width_center = isset($_POST['width_center']) ? floatval($_POST['width_center']) : 0;
        $height = isset($_POST['height']) ? floatval($_POST['height']) : 0;
        $substrate = isset($_POST['substrate']) ? floatval($_POST['substrate']) : 0;
        
        // Validate inputs
        if ($length <= 0 || $width_back <= 0 || $width_center <= 0 || $height <= 0) {
            wp_send_json_error(array('message' => __('Please enter valid dimensions.', 'aqualuxe')));
            exit;
        }
        
        // Adjust height for substrate
        $water_height = $height - $substrate;
        if ($water_height <= 0) {
            wp_send_json_error(array('message' => __('Substrate depth cannot be greater than or equal to tank height.', 'aqualuxe')));
            exit;
        }
        
        // Calculate average width
        $average_width = ($width_back + $width_center) / 2;
        
        // Calculate volume in cubic inches
        $volume_cubic_inches = $length * $average_width * $water_height;
        
        // Convert to gallons (1 gallon = 231 cubic inches)
        $volume_gallons = $volume_cubic_inches / 231;
        
        // Convert to liters (1 gallon = 3.78541 liters)
        $volume_liters = $volume_gallons * 3.78541;
        
        // Calculate substrate volume
        $substrate_volume_cubic_inches = $length * $average_width * $substrate;
        $substrate_volume_gallons = $substrate_volume_cubic_inches / 231;
        
        $html = '';
        $html .= '<div class="volume-results">';
        $html .= '<p>' . __('Tank Dimensions:', 'aqualuxe') . ' ' . __('Length:', 'aqualuxe') . ' ' . $length . '", ' . __('Width (Back):', 'aqualuxe') . ' ' . $width_back . '", ' . __('Width (Center):', 'aqualuxe') . ' ' . $width_center . '", ' . __('Height:', 'aqualuxe') . ' ' . $height . '"</p>';
        
        if ($substrate > 0) {
            $html .= '<p>' . __('Water Height (after substrate):', 'aqualuxe') . ' ' . $water_height . '"</p>';
            $html .= '<p>' . __('Substrate Volume:', 'aqualuxe') . ' ' . number_format($substrate_volume_gallons, 1) . ' ' . __('gallons', 'aqualuxe') . '</p>';
        }
        
        $html .= '<p class="volume-main">' . __('Total Water Volume:', 'aqualuxe') . ' <strong>' . number_format($volume_gallons, 1) . ' ' . __('gallons', 'aqualuxe') . ' (' . number_format($volume_liters, 1) . ' ' . __('liters', 'aqualuxe') . ')</strong></p>';
        
        // Standard tank size comparison
        $html .= $this->get_standard_tank_comparison($volume_gallons);
        
        $html .= '</div>';
        
        wp_send_json_success(array('html' => $html));
    }

    /**
     * Get standard tank size comparison
     * 
     * @param float $volume_gallons Volume in gallons.
     * @return string HTML output.
     */
    private function get_standard_tank_comparison($volume_gallons) {
        $standard_tanks = array(
            2.5 => array('12" × 6" × 8"', __('Nano Tank', 'aqualuxe')),
            5 => array('16" × 8" × 10"', __('Small Tank', 'aqualuxe')),
            10 => array('20" × 10" × 12"', __('Standard 10 Gallon', 'aqualuxe')),
            15 => array('24" × 12" × 12"', __('Standard 15 Gallon', 'aqualuxe')),
            20 => array('24" × 12" × 16"', __('Standard 20 Gallon High', 'aqualuxe')),
            29 => array('30" × 12" × 18"', __('Standard 29 Gallon', 'aqualuxe')),
            40 => array('36" × 12" × 20"', __('Standard 40 Gallon Breeder', 'aqualuxe')),
            55 => array('48" × 13" × 21"', __('Standard 55 Gallon', 'aqualuxe')),
            75 => array('48" × 18" × 21"', __('Standard 75 Gallon', 'aqualuxe')),
            90 => array('48" × 18" × 24"', __('Standard 90 Gallon', 'aqualuxe')),
            125 => array('72" × 18" × 21"', __('Standard 125 Gallon', 'aqualuxe')),
            150 => array('72" × 18" × 28"', __('Standard 150 Gallon', 'aqualuxe')),
            180 => array('72" × 24" × 25"', __('Standard 180 Gallon', 'aqualuxe')),
        );
        
        // Find closest standard tank size
        $closest_size = null;
        $closest_diff = PHP_INT_MAX;
        
        foreach ($standard_tanks as $size => $details) {
            $diff = abs($volume_gallons - $size);
            if ($diff < $closest_diff) {
                $closest_diff = $diff;
                $closest_size = $size;
            }
        }
        
        $html = '';
        if ($closest_size !== null) {
            $html .= '<div class="standard-tank-comparison">';
            $html .= '<p>' . __('Closest Standard Tank Size:', 'aqualuxe') . ' <strong>' . $closest_size . ' ' . __('gallon', 'aqualuxe') . ' ' . $standard_tanks[$closest_size][1] . '</strong></p>';
            $html .= '<p>' . __('Standard Dimensions:', 'aqualuxe') . ' ' . $standard_tanks[$closest_size][0] . '</p>';
            $html .= '</div>';
        }
        
        return $html;
    }

    /**
     * AJAX handler for calculating stocking level
     */
    public function ajax_calculate_stocking() {
        // Check nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'aqualuxe-calculator-nonce')) {
            wp_send_json_error(array('message' => __('Security check failed.', 'aqualuxe')));
            exit;
        }
        
        $tank_volume = isset($_POST['tank_volume']) ? floatval($_POST['tank_volume']) : 0;
        $filtration_capacity = isset($_POST['filtration_capacity']) ? floatval($_POST['filtration_capacity']) : 0;
        $tank_type = isset($_POST['tank_type']) ? sanitize_text_field($_POST['tank_type']) : '';
        $fish_names = isset($_POST['fish_name']) ? $_POST['fish_name'] : array();
        $fish_counts = isset($_POST['fish_count']) ? $_POST['fish_count'] : array();
        $fish_sizes = isset($_POST['fish_size']) ? $_POST['fish_size'] : array();
        
        // Validate inputs
        if ($tank_volume <= 0 || empty($tank_type) || empty($fish_names) || empty($fish_counts) || empty($fish_sizes)) {
            wp_send_json_error(array('message' => __('Please enter all required information.', 'aqualuxe')));
            exit;
        }
        
        // Calculate stocking level
        $total_fish_inches = 0;
        $fish_list = array();
        
        for ($i = 0; $i < count($fish_names); $i++) {
            if (empty($fish_names[$i]) || empty($fish_counts[$i]) || empty($fish_sizes[$i])) {
                continue;
            }
            
            $name = sanitize_text_field($fish_names[$i]);
            $count = intval($fish_counts[$i]);
            $size = floatval($fish_sizes[$i]);
            
            $total_inches = $count * $size;
            $total_fish_inches += $total_inches;
            
            $fish_list[] = array(
                'name' => $name,
                'count' => $count,
                'size' => $size,
                'total_inches' => $total_inches,
            );
        }
        
        // Calculate stocking percentage based on tank type
        $stocking_factor = $this->get_stocking_factor($tank_type);
        $recommended_inches = $tank_volume * $stocking_factor;
        
        // Adjust for filtration
        if ($filtration_capacity > $tank_volume) {
            $filtration_bonus = min(0.2, ($filtration_capacity - $tank_volume) / $tank_volume * 0.1);
            $recommended_inches *= (1 + $filtration_bonus);
        }
        
        $stocking_percentage = ($total_fish_inches / $recommended_inches) * 100;
        
        // Determine stocking level
        $stocking_level = '';
        $stocking_class = '';
        
        if ($stocking_percentage < 70) {
            $stocking_level = __('Understocked', 'aqualuxe');
            $stocking_class = 'understocked';
        } elseif ($stocking_percentage <= 90) {
            $stocking_level = __('Ideal (Low)', 'aqualuxe');
            $stocking_class = 'ideal-low';
        } elseif ($stocking_percentage <= 110) {
            $stocking_level = __('Ideal', 'aqualuxe');
            $stocking_class = 'ideal';
        } elseif ($stocking_percentage <= 130) {
            $stocking_level = __('Ideal (High)', 'aqualuxe');
            $stocking_class = 'ideal-high';
        } elseif ($stocking_percentage <= 150) {
            $stocking_level = __('Slightly Overstocked', 'aqualuxe');
            $stocking_class = 'slightly-overstocked';
        } else {
            $stocking_level = __('Overstocked', 'aqualuxe');
            $stocking_class = 'overstocked';
        }
        
        $html = '';
        $html .= '<div class="stocking-results">';
        $html .= '<p>' . sprintf(__('Tank Volume: %s gallons', 'aqualuxe'), $tank_volume) . '</p>';
        
        if ($filtration_capacity > 0) {
            $html .= '<p>' . sprintf(__('Filtration Capacity: %s gallons', 'aqualuxe'), $filtration_capacity) . '</p>';
        }
        
        $html .= '<p>' . sprintf(__('Tank Type: %s', 'aqualuxe'), $this->get_tank_type_label($tank_type)) . '</p>';
        $html .= '<p>' . sprintf(__('Total Fish Inches: %s inches', 'aqualuxe'), number_format($total_fish_inches, 1)) . '</p>';
        $html .= '<p>' . sprintf(__('Recommended Maximum: %s inches', 'aqualuxe'), number_format($recommended_inches, 1)) . '</p>';
        $html .= '<p class="stocking-percentage ' . $stocking_class . '">' . __('Stocking Level:', 'aqualuxe') . ' <strong>' . $stocking_level . ' (' . number_format($stocking_percentage, 0) . '%)</strong></p>';
        
        // Fish list
        $html .= '<div class="fish-stocking-list">';
        $html .= '<h4>' . __('Fish List', 'aqualuxe') . '</h4>';
        $html .= '<table class="fish-table">';
        $html .= '<thead><tr>';
        $html .= '<th>' . __('Fish', 'aqualuxe') . '</th>';
        $html .= '<th>' . __('Count', 'aqualuxe') . '</th>';
        $html .= '<th>' . __('Size (in)', 'aqualuxe') . '</th>';
        $html .= '<th>' . __('Total (in)', 'aqualuxe') . '</th>';
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        
        foreach ($fish_list as $fish) {
            $html .= '<tr>';
            $html .= '<td>' . esc_html($fish['name']) . '</td>';
            $html .= '<td>' . esc_html($fish['count']) . '</td>';
            $html .= '<td>' . number_format($fish['size'], 1) . '</td>';
            $html .= '<td>' . number_format($fish['total_inches'], 1) . '</td>';
            $html .= '</tr>';
        }
        
        $html .= '</tbody>';
        $html .= '</table>';
        $html .= '</div>';
        
        // Recommendations
        $html .= '<div class="stocking-recommendations">';
        $html .= '<h4>' . __('Recommendations', 'aqualuxe') . '</h4>';
        
        if ($stocking_percentage < 70) {
            $html .= '<p>' . __('Your tank is understocked. You can safely add more fish.', 'aqualuxe') . '</p>';
        } elseif ($stocking_percentage <= 110) {
            $html .= '<p>' . __('Your tank has an ideal stocking level. Fish have plenty of space and water quality should be easy to maintain.', 'aqualuxe') . '</p>';
        } elseif ($stocking_percentage <= 130) {
            $html .= '<p>' . __('Your tank is at the high end of ideal stocking. Monitor water quality closely and ensure adequate filtration.', 'aqualuxe') . '</p>';
        } elseif ($stocking_percentage <= 150) {
            $html .= '<p>' . __('Your tank is slightly overstocked. Consider upgrading filtration and performing more frequent water changes.', 'aqualuxe') . '</p>';
        } else {
            $html .= '<p>' . __('Your tank is overstocked. Consider rehoming some fish or upgrading to a larger tank. Increase water change frequency and monitor water parameters carefully.', 'aqualuxe') . '</p>';
        }
        
        $html .= '</div>';
        
        $html .= '</div>';
        
        wp_send_json_success(array(
            'html' => $html,
            'stocking_percentage' => $stocking_percentage,
            'stocking_class' => $stocking_class
        ));
    }

    /**
     * Get stocking factor based on tank type
     * 
     * @param string $tank_type Tank type.
     * @return float Stocking factor.
     */
    private function get_stocking_factor($tank_type) {
        switch ($tank_type) {
            case 'freshwater':
                return 1.0; // 1 inch per gallon
            case 'cichlid':
                return 0.5; // 1 inch per 2 gallons (more aggressive)
            case 'goldfish':
                return 0.33; // 1 inch per 3 gallons (messy fish)
            case 'saltwater':
                return 0.5; // 1 inch per 2 gallons (more sensitive)
            case 'planted':
                return 1.2; // 1.2 inches per gallon (plants help with filtration)
            default:
                return 1.0;
        }
    }

    /**
     * Get tank type label
     * 
     * @param string $tank_type Tank type.
     * @return string Tank type label.
     */
    private function get_tank_type_label($tank_type) {
        switch ($tank_type) {
            case 'freshwater':
                return __('Freshwater Community', 'aqualuxe');
            case 'cichlid':
                return __('Cichlid Tank', 'aqualuxe');
            case 'goldfish':
                return __('Goldfish Tank', 'aqualuxe');
            case 'saltwater':
                return __('Saltwater/Reef', 'aqualuxe');
            case 'planted':
                return __('Heavily Planted', 'aqualuxe');
            default:
                return $tank_type;
        }
    }
}