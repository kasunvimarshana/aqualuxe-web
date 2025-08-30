<?php
/**
 * Template part for displaying the fish stocking calculator
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

$calculator_class = isset($class) ? ' ' . $class : '';
$calculator_title = isset($title) ? $title : __('Fish Stocking Calculator', 'aqualuxe');
?>

<div class="aqualuxe-calculator stocking-calculator<?php echo esc_attr($calculator_class); ?>">
    <h2 class="calculator-title"><?php echo esc_html($calculator_title); ?></h2>
    
    <div class="calculator-description">
        <p><?php esc_html_e('Calculate the appropriate stocking level for your aquarium based on tank size, filtration, and fish selection. This will help you maintain a healthy and balanced aquatic environment.', 'aqualuxe'); ?></p>
    </div>
    
    <form class="calculator-form stocking-calculator-form">
        <div class="form-section tank-details">
            <h3><?php esc_html_e('Tank Details', 'aqualuxe'); ?></h3>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tank-volume-stocking"><?php esc_html_e('Tank Volume (gallons):', 'aqualuxe'); ?></label>
                    <input type="number" id="tank-volume-stocking" name="tank_volume" min="1" step="0.1" required>
                </div>
                
                <div class="form-group">
                    <label for="filtration-capacity"><?php esc_html_e('Filtration Capacity (gallons):', 'aqualuxe'); ?></label>
                    <input type="number" id="filtration-capacity" name="filtration_capacity" min="0" step="0.1" placeholder="<?php esc_attr_e('Optional', 'aqualuxe'); ?>">
                    <span class="help-text"><?php esc_html_e('Leave blank if unknown or same as tank volume.', 'aqualuxe'); ?></span>
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="tank-type"><?php esc_html_e('Tank Type:', 'aqualuxe'); ?></label>
                    <select id="tank-type" name="tank_type" required>
                        <option value=""><?php esc_html_e('Select Tank Type', 'aqualuxe'); ?></option>
                        <option value="community"><?php esc_html_e('Community Tank', 'aqualuxe'); ?></option>
                        <option value="cichlid"><?php esc_html_e('Cichlid Tank', 'aqualuxe'); ?></option>
                        <option value="goldfish"><?php esc_html_e('Goldfish Tank', 'aqualuxe'); ?></option>
                        <option value="planted"><?php esc_html_e('Heavily Planted Tank', 'aqualuxe'); ?></option>
                        <option value="reef"><?php esc_html_e('Reef Tank', 'aqualuxe'); ?></option>
                        <option value="predator"><?php esc_html_e('Predator Tank', 'aqualuxe'); ?></option>
                    </select>
                </div>
            </div>
        </div>
        
        <div class="form-section fish-details">
            <h3><?php esc_html_e('Fish Details', 'aqualuxe'); ?></h3>
            <p class="section-description"><?php esc_html_e('Add the fish you have or plan to have in your aquarium.', 'aqualuxe'); ?></p>
            
            <div class="fish-list-container">
                <div class="fish-entry">
                    <div class="form-group">
                        <label for="fish-name-1"><?php esc_html_e('Fish Name:', 'aqualuxe'); ?></label>
                        <input type="text" id="fish-name-1" name="fish_name[]" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fish-count-1"><?php esc_html_e('Quantity:', 'aqualuxe'); ?></label>
                        <input type="number" id="fish-count-1" name="fish_count[]" min="1" value="1" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="fish-size-1"><?php esc_html_e('Adult Size (inches):', 'aqualuxe'); ?></label>
                        <input type="number" id="fish-size-1" name="fish_size[]" min="0.1" step="0.1" required>
                    </div>
                    
                    <button type="button" class="remove-fish" style="display: none;">&times;</button>
                </div>
            </div>
            
            <div class="fish-actions">
                <button type="button" class="add-fish button-secondary"><?php esc_html_e('Add Another Fish', 'aqualuxe'); ?></button>
            </div>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate Stocking Level', 'aqualuxe'); ?></button>
        </div>
    </form>
    
    <div class="stocking-calculator-results calculator-results" style="display: none;">
        <h3><?php esc_html_e('Stocking Level Results', 'aqualuxe'); ?></h3>
        
        <div class="stocking-meter">
            <div class="stocking-meter-bar">
                <div class="stocking-meter-fill"></div>
            </div>
            <div class="stocking-meter-labels">
                <span class="understocked"><?php esc_html_e('Understocked', 'aqualuxe'); ?></span>
                <span class="adequate"><?php esc_html_e('Adequate', 'aqualuxe'); ?></span>
                <span class="overstocked"><?php esc_html_e('Overstocked', 'aqualuxe'); ?></span>
            </div>
        </div>
        
        <div class="results-content"></div>
    </div>
    
    <div class="calculator-notes">
        <h3><?php esc_html_e('Notes', 'aqualuxe'); ?></h3>
        <ul>
            <li><?php esc_html_e('This calculator uses the inch-per-gallon rule as a baseline but adjusts for fish bioload, tank type, and filtration.', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Enter the adult size of the fish, not their current size.', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('The stocking level is an estimate and should be used as a guideline only.', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Always consider the specific needs of each fish species beyond just the stocking level.', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Regular water testing and maintenance are essential regardless of stocking level.', 'aqualuxe'); ?></li>
        </ul>
    </div>
</div>