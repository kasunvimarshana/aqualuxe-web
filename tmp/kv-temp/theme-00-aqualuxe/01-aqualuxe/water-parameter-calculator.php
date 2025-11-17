
<?php
/**
 * Template part for displaying the water parameter calculator
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get the active tab
$active_tab = isset($tab) ? $tab : 'ph-adjustment';
$calculator_class = isset($class) ? ' ' . $class : '';
$calculator_title = isset($title) ? $title : __('Water Parameter Calculator', 'aqualuxe');
?>

<div class="aqualuxe-calculator water-parameter-calculator<?php echo esc_attr($calculator_class); ?>">
    <h2 class="calculator-title"><?php echo esc_html($calculator_title); ?></h2>
    
    <div class="calculator-tabs">
        <ul class="tab-navigation">
            <li data-tab="ph-adjustment" <?php echo $active_tab === 'ph-adjustment' ? 'class="active"' : ''; ?>>
                <?php esc_html_e('pH Adjustment', 'aqualuxe'); ?>
            </li>
            <li data-tab="hardness-adjustment" <?php echo $active_tab === 'hardness-adjustment' ? 'class="active"' : ''; ?>>
                <?php esc_html_e('Water Hardness', 'aqualuxe'); ?>
            </li>
            <li data-tab="medication-dosage" <?php echo $active_tab === 'medication-dosage' ? 'class="active"' : ''; ?>>
                <?php esc_html_e('Medication Dosage', 'aqualuxe'); ?>
            </li>
        </ul>
        
        <div class="tabs-content">
            <!-- pH Adjustment Tab -->
            <div class="tab-pane <?php echo $active_tab === 'ph-adjustment' ? 'active' : ''; ?>" id="ph-adjustment">
                <div class="calculator-description">
                    <p><?php esc_html_e('Use this calculator to determine how much pH adjuster to add to your aquarium to reach your target pH level.', 'aqualuxe'); ?></p>
                </div>
                
                <form class="calculator-form ph-calculator-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="current-ph"><?php esc_html_e('Current pH:', 'aqualuxe'); ?></label>
                            <input type="number" id="current-ph" name="current_ph" min="0" max="14" step="0.1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="target-ph"><?php esc_html_e('Target pH:', 'aqualuxe'); ?></label>
                            <input type="number" id="target-ph" name="target_ph" min="0" max="14" step="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tank-volume-ph"><?php esc_html_e('Tank Volume (gallons):', 'aqualuxe'); ?></label>
                            <input type="number" id="tank-volume-ph" name="tank_volume" min="1" step="0.1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="adjustment-type"><?php esc_html_e('Adjustment Type:', 'aqualuxe'); ?></label>
                            <select id="adjustment-type" name="adjustment_type" required>
                                <option value=""><?php esc_html_e('Select Type', 'aqualuxe'); ?></option>
                                <option value="increase"><?php esc_html_e('Increase pH', 'aqualuxe'); ?></option>
                                <option value="decrease"><?php esc_html_e('Decrease pH', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                    </div>
                </form>
                
                <div class="ph-calculator-results calculator-results" style="display: none;">
                    <h3><?php esc_html_e('pH Adjustment Results', 'aqualuxe'); ?></h3>
                    <div class="results-content"></div>
                </div>
            </div>
            
            <!-- Water Hardness Tab -->
            <div class="tab-pane <?php echo $active_tab === 'hardness-adjustment' ? 'active' : ''; ?>" id="hardness-adjustment">
                <div class="calculator-description">
                    <p><?php esc_html_e('Use this calculator to determine how to adjust your water hardness to reach your target level.', 'aqualuxe'); ?></p>
                </div>
                
                <form class="calculator-form hardness-calculator-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="current-hardness"><?php esc_html_e('Current Hardness (dGH):', 'aqualuxe'); ?></label>
                            <input type="number" id="current-hardness" name="current_hardness" min="0" step="0.1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="target-hardness"><?php esc_html_e('Target Hardness (dGH):', 'aqualuxe'); ?></label>
                            <input type="number" id="target-hardness" name="target_hardness" min="0" step="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tank-volume-hardness"><?php esc_html_e('Tank Volume (gallons):', 'aqualuxe'); ?></label>
                            <input type="number" id="tank-volume-hardness" name="tank_volume" min="1" step="0.1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="hardness-adjustment-type"><?php esc_html_e('Adjustment Type:', 'aqualuxe'); ?></label>
                            <select id="hardness-adjustment-type" name="hardness_adjustment_type" required>
                                <option value=""><?php esc_html_e('Select Type', 'aqualuxe'); ?></option>
                                <option value="increase"><?php esc_html_e('Increase Hardness', 'aqualuxe'); ?></option>
                                <option value="decrease"><?php esc_html_e('Decrease Hardness', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                    </div>
                </form>
                
                <div class="hardness-calculator-results calculator-results" style="display: none;">
                    <h3><?php esc_html_e('Water Hardness Adjustment Results', 'aqualuxe'); ?></h3>
                    <div class="results-content"></div>
                </div>
            </div>
            
            <!-- Medication Dosage Tab -->
            <div class="tab-pane <?php echo $active_tab === 'medication-dosage' ? 'active' : ''; ?>" id="medication-dosage">
                <div class="calculator-description">
                    <p><?php esc_html_e('Use this calculator to determine the correct dosage of medication for your aquarium.', 'aqualuxe'); ?></p>
                </div>
                
                <form class="calculator-form medication-calculator-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="medication-type"><?php esc_html_e('Medication Type:', 'aqualuxe'); ?></label>
                            <select id="medication-type" name="medication_type" required>
                                <option value=""><?php esc_html_e('Select Medication', 'aqualuxe'); ?></option>
                                <option value="ich-treatment"><?php esc_html_e('Ich Treatment', 'aqualuxe'); ?></option>
                                <option value="anti-fungal"><?php esc_html_e('Anti-Fungal', 'aqualuxe'); ?></option>
                                <option value="anti-bacterial"><?php esc_html_e('Anti-Bacterial', 'aqualuxe'); ?></option>
                                <option value="parasite-treatment"><?php esc_html_e('Parasite Treatment', 'aqualuxe'); ?></option>
                                <option value="custom"><?php esc_html_e('Custom Medication', 'aqualuxe'); ?></option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tank-volume-medication"><?php esc_html_e('Tank Volume (gallons):', 'aqualuxe'); ?></label>
                            <input type="number" id="tank-volume-medication" name="tank_volume" min="1" step="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-row custom-medication-row" style="display: none;">
                        <div class="form-group">
                            <label for="custom-dosage"><?php esc_html_e('Custom Dosage Rate:', 'aqualuxe'); ?></label>
                            <input type="text" id="custom-dosage" name="custom_dosage" placeholder="<?php esc_attr_e('e.g., 1 ml per 10 gallons', 'aqualuxe'); ?>">
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                    </div>
                </form>
                
                <div class="medication-calculator-results calculator-results" style="display: none;">
                    <h3><?php esc_html_e('Medication Dosage Results', 'aqualuxe'); ?></h3>
                    <div class="results-content"></div>
                </div>
            </div>
        </div>
    </div>
</div>
