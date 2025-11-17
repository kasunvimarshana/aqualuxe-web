<?php
/**
 * Template part for displaying the tank volume calculator
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get the active tab
$active_tab = isset($tab) ? $tab : 'rectangular';
$calculator_class = isset($class) ? ' ' . $class : '';
$calculator_title = isset($title) ? $title : __('Tank Volume Calculator', 'aqualuxe');
?>

<div class="aqualuxe-calculator tank-volume-calculator<?php echo esc_attr($calculator_class); ?>">
    <h2 class="calculator-title"><?php echo esc_html($calculator_title); ?></h2>
    
    <div class="calculator-description">
        <p><?php esc_html_e('Calculate the volume of your aquarium based on its dimensions. This will help you determine the appropriate amount of water, substrate, and other materials needed for your tank.', 'aqualuxe'); ?></p>
    </div>
    
    <div class="calculator-tabs">
        <ul class="tab-navigation">
            <li data-tab="rectangular" <?php echo $active_tab === 'rectangular' ? 'class="active"' : ''; ?>>
                <?php esc_html_e('Rectangular Tank', 'aqualuxe'); ?>
            </li>
            <li data-tab="cylindrical" <?php echo $active_tab === 'cylindrical' ? 'class="active"' : ''; ?>>
                <?php esc_html_e('Cylindrical Tank', 'aqualuxe'); ?>
            </li>
            <li data-tab="bowfront" <?php echo $active_tab === 'bowfront' ? 'class="active"' : ''; ?>>
                <?php esc_html_e('Bowfront Tank', 'aqualuxe'); ?>
            </li>
        </ul>
        
        <div class="tabs-content">
            <!-- Rectangular Tank Tab -->
            <div class="tab-pane <?php echo $active_tab === 'rectangular' ? 'active' : ''; ?>" id="rectangular">
                <div class="tank-illustration rectangular-tank">
                    <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . '/images/rectangular-tank.png'); ?>" alt="<?php esc_attr_e('Rectangular Tank', 'aqualuxe'); ?>">
                </div>
                
                <form class="calculator-form rectangular-calculator-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="rect-length"><?php esc_html_e('Length (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="rect-length" name="length" min="1" step="0.1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="rect-width"><?php esc_html_e('Width (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="rect-width" name="width" min="1" step="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="rect-height"><?php esc_html_e('Height (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="rect-height" name="height" min="1" step="0.1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="rect-substrate"><?php esc_html_e('Substrate Depth (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="rect-substrate" name="substrate" min="0" step="0.1" value="0">
                            <span class="help-text"><?php esc_html_e('Optional. Leave at 0 if not applicable.', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                    </div>
                </form>
                
                <div class="rectangular-calculator-results calculator-results" style="display: none;">
                    <h3><?php esc_html_e('Tank Volume Results', 'aqualuxe'); ?></h3>
                    <div class="results-content"></div>
                </div>
            </div>
            
            <!-- Cylindrical Tank Tab -->
            <div class="tab-pane <?php echo $active_tab === 'cylindrical' ? 'active' : ''; ?>" id="cylindrical">
                <div class="tank-illustration cylindrical-tank">
                    <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . '/images/cylindrical-tank.png'); ?>" alt="<?php esc_attr_e('Cylindrical Tank', 'aqualuxe'); ?>">
                </div>
                
                <form class="calculator-form cylindrical-calculator-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cyl-diameter"><?php esc_html_e('Diameter (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="cyl-diameter" name="diameter" min="1" step="0.1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="cyl-height"><?php esc_html_e('Height (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="cyl-height" name="height" min="1" step="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="cyl-substrate"><?php esc_html_e('Substrate Depth (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="cyl-substrate" name="substrate" min="0" step="0.1" value="0">
                            <span class="help-text"><?php esc_html_e('Optional. Leave at 0 if not applicable.', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                    </div>
                </form>
                
                <div class="cylindrical-calculator-results calculator-results" style="display: none;">
                    <h3><?php esc_html_e('Tank Volume Results', 'aqualuxe'); ?></h3>
                    <div class="results-content"></div>
                </div>
            </div>
            
            <!-- Bowfront Tank Tab -->
            <div class="tab-pane <?php echo $active_tab === 'bowfront' ? 'active' : ''; ?>" id="bowfront">
                <div class="tank-illustration bowfront-tank">
                    <img src="<?php echo esc_url(AQUALUXE_ASSETS_URI . '/images/bowfront-tank.png'); ?>" alt="<?php esc_attr_e('Bowfront Tank', 'aqualuxe'); ?>">
                </div>
                
                <form class="calculator-form bowfront-calculator-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bow-length"><?php esc_html_e('Length (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="bow-length" name="length" min="1" step="0.1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="bow-width-back"><?php esc_html_e('Width at Back (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="bow-width-back" name="width_back" min="1" step="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bow-width-center"><?php esc_html_e('Width at Center (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="bow-width-center" name="width_center" min="1" step="0.1" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="bow-height"><?php esc_html_e('Height (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="bow-height" name="height" min="1" step="0.1" required>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="bow-substrate"><?php esc_html_e('Substrate Depth (inches):', 'aqualuxe'); ?></label>
                            <input type="number" id="bow-substrate" name="substrate" min="0" step="0.1" value="0">
                            <span class="help-text"><?php esc_html_e('Optional. Leave at 0 if not applicable.', 'aqualuxe'); ?></span>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="button calculate-button"><?php esc_html_e('Calculate', 'aqualuxe'); ?></button>
                    </div>
                </form>
                
                <div class="bowfront-calculator-results calculator-results" style="display: none;">
                    <h3><?php esc_html_e('Tank Volume Results', 'aqualuxe'); ?></h3>
                    <div class="results-content"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="calculator-notes">
        <h3><?php esc_html_e('Notes', 'aqualuxe'); ?></h3>
        <ul>
            <li><?php esc_html_e('All measurements should be in inches.', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('For substrate depth, enter the average depth of your substrate in inches.', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('The calculator accounts for displacement caused by substrate, decorations, and equipment.', 'aqualuxe'); ?></li>
            <li><?php esc_html_e('Results are approximate and may vary slightly from actual tank volume.', 'aqualuxe'); ?></li>
        </ul>
    </div>
</div>