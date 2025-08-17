<?php
/**
 * Water Parameter Calculator Functions
 *
 * Functions for the water parameter calculator tool
 *
 * @package AquaLuxe
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue water calculator scripts and styles
 */
function aqualuxe_enqueue_water_calculator_assets() {
    // Only enqueue on pages with the shortcode
    global $post;
    if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'water_calculator')) {
        // Enqueue CSS
        wp_enqueue_style(
            'aqualuxe-water-calculator-styles',
            get_template_directory_uri() . '/assets/css/water-calculator.css',
            array(),
            AQUALUXE_VERSION
        );
        
        // Enqueue JavaScript
        wp_enqueue_script(
            'aqualuxe-water-calculator-script',
            get_template_directory_uri() . '/assets/js/water-calculator.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_enqueue_water_calculator_assets');

/**
 * Water Parameter Calculator shortcode
 */
function aqualuxe_water_calculator_shortcode($atts) {
    // Extract attributes
    $atts = shortcode_atts(
        array(
            'title' => __('Water Parameter Calculator', 'aqualuxe'),
            'description' => __('Calculate and analyze your aquarium water parameters to ensure optimal conditions for your fish.', 'aqualuxe'),
            'show_history' => 'yes',
        ),
        $atts,
        'water_calculator'
    );
    
    // Start output buffer
    ob_start();
    
    ?>
    <div class="water-calculator-container">
        <div class="water-calculator-header">
            <h2><?php echo esc_html($atts['title']); ?></h2>
            <p><?php echo esc_html($atts['description']); ?></p>
        </div>
        
        <form id="water-parameter-calculator-form" class="water-calculator-form">
            <div class="form-section">
                <h3 class="form-section-title"><?php _e('Tank Information', 'aqualuxe'); ?></h3>
                <div class="form-row">
                    <div class="form-group">
                        <label for="tank-type"><?php _e('Tank Type', 'aqualuxe'); ?></label>
                        <select id="tank-type" name="tank_type" required>
                            <option value="freshwater"><?php _e('Freshwater', 'aqualuxe'); ?></option>
                            <option value="saltwater"><?php _e('Saltwater', 'aqualuxe'); ?></option>
                            <option value="brackish"><?php _e('Brackish', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="fish-type"><?php _e('Fish Type', 'aqualuxe'); ?></label>
                        <select id="fish-type" name="fish_type" required>
                            <option value="tropical"><?php _e('Tropical Community', 'aqualuxe'); ?></option>
                            <option value="goldfish"><?php _e('Goldfish', 'aqualuxe'); ?></option>
                            <option value="cichlid"><?php _e('Cichlid', 'aqualuxe'); ?></option>
                            <option value="discus"><?php _e('Discus', 'aqualuxe'); ?></option>
                            <option value="planted"><?php _e('Planted Tank', 'aqualuxe'); ?></option>
                            <option value="reef"><?php _e('Reef Tank', 'aqualuxe'); ?></option>
                            <option value="shrimp"><?php _e('Shrimp Tank', 'aqualuxe'); ?></option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="tank-size"><?php _e('Tank Size (gallons)', 'aqualuxe'); ?></label>
                        <input type="number" id="tank-size" name="tank_size" min="1" step="0.1" placeholder="<?php _e('Enter tank size', 'aqualuxe'); ?>">
                    </div>
                </div>
            </div>
            
            <div class="form-section">
                <h3 class="form-section-title"><?php _e('Water Parameters', 'aqualuxe'); ?></h3>
                
                <!-- Common Parameters (for all tank types) -->
                <div class="parameter-group parameter-common">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="ph"><?php _e('pH Level', 'aqualuxe'); ?></label>
                            <input type="number" id="ph" name="ph" min="0" max="14" step="0.1" class="parameter-input" placeholder="<?php _e('e.g., 7.0', 'aqualuxe'); ?>">
                            <span class="parameter-range range-ph"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="ammonia"><?php _e('Ammonia (NH₃) in ppm', 'aqualuxe'); ?></label>
                            <input type="number" id="ammonia" name="ammonia" min="0" step="0.01" class="parameter-input" placeholder="<?php _e('e.g., 0.0', 'aqualuxe'); ?>">
                            <span class="parameter-range range-ammonia"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="nitrite"><?php _e('Nitrite (NO₂) in ppm', 'aqualuxe'); ?></label>
                            <input type="number" id="nitrite" name="nitrite" min="0" step="0.01" class="parameter-input" placeholder="<?php _e('e.g., 0.0', 'aqualuxe'); ?>">
                            <span class="parameter-range range-nitrite"></span>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nitrate"><?php _e('Nitrate (NO₃) in ppm', 'aqualuxe'); ?></label>
                            <input type="number" id="nitrate" name="nitrate" min="0" step="0.1" class="parameter-input" placeholder="<?php _e('e.g., 10.0', 'aqualuxe'); ?>">
                            <span class="parameter-range range-nitrate"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="temperature"><?php _e('Temperature (°F)', 'aqualuxe'); ?></label>
                            <input type="number" id="temperature" name="temperature" min="32" max="100" step="0.1" class="parameter-input" placeholder="<?php _e('e.g., 78.0', 'aqualuxe'); ?>">
                            <span class="parameter-range range-temperature"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Freshwater Specific Parameters -->
                <div class="parameter-group parameter-freshwater">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="kh"><?php _e('Carbonate Hardness (KH) in dKH', 'aqualuxe'); ?></label>
                            <input type="number" id="kh" name="kh" min="0" step="0.1" class="parameter-input" placeholder="<?php _e('e.g., 6.0', 'aqualuxe'); ?>">
                            <span class="parameter-range range-kh"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="gh"><?php _e('General Hardness (GH) in dGH', 'aqualuxe'); ?></label>
                            <input type="number" id="gh" name="gh" min="0" step="0.1" class="parameter-input" placeholder="<?php _e('e.g., 10.0', 'aqualuxe'); ?>">
                            <span class="parameter-range range-gh"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Saltwater Specific Parameters -->
                <div class="parameter-group parameter-saltwater">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="salinity"><?php _e('Salinity (Specific Gravity)', 'aqualuxe'); ?></label>
                            <input type="number" id="salinity" name="salinity" min="1.000" max="1.035" step="0.001" class="parameter-input" placeholder="<?php _e('e.g., 1.024', 'aqualuxe'); ?>">
                            <span class="parameter-range range-salinity"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="calcium"><?php _e('Calcium (Ca) in ppm', 'aqualuxe'); ?></label>
                            <input type="number" id="calcium" name="calcium" min="0" step="1" class="parameter-input" placeholder="<?php _e('e.g., 420', 'aqualuxe'); ?>">
                            <span class="parameter-range range-calcium"></span>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="alkalinity"><?php _e('Alkalinity in dKH', 'aqualuxe'); ?></label>
                            <input type="number" id="alkalinity" name="alkalinity" min="0" step="0.1" class="parameter-input" placeholder="<?php _e('e.g., 10.0', 'aqualuxe'); ?>">
                            <span class="parameter-range range-alkalinity"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="magnesium"><?php _e('Magnesium (Mg) in ppm', 'aqualuxe'); ?></label>
                            <input type="number" id="magnesium" name="magnesium" min="0" step="1" class="parameter-input" placeholder="<?php _e('e.g., 1300', 'aqualuxe'); ?>">
                            <span class="parameter-range range-magnesium"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Brackish Specific Parameters -->
                <div class="parameter-group parameter-brackish">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="salinity-brackish"><?php _e('Salinity (Specific Gravity)', 'aqualuxe'); ?></label>
                            <input type="number" id="salinity-brackish" name="salinity" min="1.000" max="1.020" step="0.001" class="parameter-input" placeholder="<?php _e('e.g., 1.010', 'aqualuxe'); ?>">
                            <span class="parameter-range range-salinity"></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="kh-brackish"><?php _e('Carbonate Hardness (KH) in dKH', 'aqualuxe'); ?></label>
                            <input type="number" id="kh-brackish" name="kh" min="0" step="0.1" class="parameter-input" placeholder="<?php _e('e.g., 8.0', 'aqualuxe'); ?>">
                            <span class="parameter-range range-kh"></span>
                        </div>
                    </div>
                </div>
                
                <!-- Shrimp Tank Specific Parameters -->
                <div class="parameter-group parameter-freshwater" id="shrimp-parameters">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="tds"><?php _e('Total Dissolved Solids (TDS) in ppm', 'aqualuxe'); ?></label>
                            <input type="number" id="tds" name="tds" min="0" step="1" class="parameter-input" placeholder="<?php _e('e.g., 200', 'aqualuxe'); ?>">
                            <span class="parameter-range range-tds"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="submit" id="calculate-parameters" class="btn btn-primary"><?php _e('Calculate Parameters', 'aqualuxe'); ?></button>
                <button type="button" id="reset-parameters" class="btn btn-secondary"><?php _e('Reset Form', 'aqualuxe'); ?></button>
            </div>
        </form>
        
        <div id="calculator-results" class="calculator-results">
            <div class="results-header">
                <h3><?php _e('Water Parameter Analysis', 'aqualuxe'); ?></h3>
                <p><?php _e('Below is an analysis of your water parameters and recommendations for optimal fish health.', 'aqualuxe'); ?></p>
            </div>
            
            <div id="results-table"></div>
            
            <div id="recommendations-container"></div>
            
            <div class="results-actions">
                <button type="button" id="save-results" class="btn btn-primary"><?php _e('Save Results', 'aqualuxe'); ?></button>
                <button type="button" id="export-results" class="btn btn-secondary"><?php _e('Export as CSV', 'aqualuxe'); ?></button>
            </div>
        </div>
        
        <?php if ($atts['show_history'] === 'yes') : ?>
        <div id="history-container" class="history-container" style="display: none;">
            <div class="history-header">
                <h3><?php _e('Parameter History', 'aqualuxe'); ?></h3>
                <button type="button" id="clear-history" class="btn btn-secondary"><?php _e('Clear History', 'aqualuxe'); ?></button>
            </div>
            
            <table class="history-table">
                <thead>
                    <tr>
                        <th><?php _e('Date/Time', 'aqualuxe'); ?></th>
                        <th><?php _e('Tank Type', 'aqualuxe'); ?></th>
                        <th><?php _e('Fish Type', 'aqualuxe'); ?></th>
                        <th><?php _e('pH', 'aqualuxe'); ?></th>
                        <th><?php _e('Ammonia', 'aqualuxe'); ?></th>
                        <th><?php _e('Nitrate', 'aqualuxe'); ?></th>
                        <th><?php _e('Actions', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody id="history-table">
                    <!-- History entries will be populated here -->
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
    <?php
    
    // Return the output buffer contents
    return ob_get_clean();
}
add_shortcode('water_calculator', 'aqualuxe_water_calculator_shortcode');

/**
 * Register Water Parameter Calculator widget
 */
class Aqualuxe_Water_Calculator_Widget extends WP_Widget {
    /**
     * Register widget with WordPress
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_water_calculator_widget', // Base ID
            __('AquaLuxe Water Calculator', 'aqualuxe'), // Name
            array('description' => __('Display a link to the water parameter calculator', 'aqualuxe')) // Args
        );
    }
    
    /**
     * Front-end display of widget
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        if (!empty($instance['title'])) {
            echo $args['before_title'] . apply_filters('widget_title', $instance['title']) . $args['after_title'];
        }
        
        $calculator_page = !empty($instance['calculator_page']) ? get_permalink($instance['calculator_page']) : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Calculate Water Parameters', 'aqualuxe');
        $description = !empty($instance['description']) ? $instance['description'] : '';
        
        ?>
        <div class="water-calculator-widget">
            <?php if (!empty($description)) : ?>
                <p><?php echo esc_html($description); ?></p>
            <?php endif; ?>
            
            <?php if (!empty($calculator_page)) : ?>
                <a href="<?php echo esc_url($calculator_page); ?>" class="water-calculator-widget-button"><?php echo esc_html($button_text); ?></a>
            <?php else : ?>
                <p class="water-calculator-widget-error"><?php _e('Please select a calculator page in the widget settings.', 'aqualuxe'); ?></p>
            <?php endif; ?>
        </div>
        
        <style>
            .water-calculator-widget {
                text-align: center;
                padding: 1rem;
                background-color: var(--color-background-light, #f8f9fa);
                border-radius: 8px;
            }
            
            .water-calculator-widget-button {
                display: inline-block;
                padding: 0.75rem 1.5rem;
                background-color: var(--color-primary, #0056b3);
                color: white;
                text-decoration: none;
                border-radius: 4px;
                font-weight: 500;
                margin-top: 0.5rem;
                transition: background-color 0.2s ease;
            }
            
            .water-calculator-widget-button:hover {
                background-color: var(--color-primary-dark, #004494);
                color: white;
            }
            
            .water-calculator-widget-error {
                color: var(--color-danger, #dc3545);
                font-size: 0.9rem;
            }
        </style>
        <?php
        
        echo $args['after_widget'];
    }
    
    /**
     * Back-end widget form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Water Parameter Calculator', 'aqualuxe');
        $calculator_page = !empty($instance['calculator_page']) ? $instance['calculator_page'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : __('Calculate Water Parameters', 'aqualuxe');
        $description = !empty($instance['description']) ? $instance['description'] : __('Use our calculator to ensure optimal water conditions for your fish.', 'aqualuxe');
        
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>"><?php esc_html_e('Description:', 'aqualuxe'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>" rows="3"><?php echo esc_textarea($description); ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('calculator_page')); ?>"><?php esc_html_e('Calculator Page:', 'aqualuxe'); ?></label>
            <?php
            wp_dropdown_pages(array(
                'name' => $this->get_field_name('calculator_page'),
                'id' => $this->get_field_id('calculator_page'),
                'selected' => $calculator_page,
                'show_option_none' => __('Select a page', 'aqualuxe'),
            ));
            ?>
            <small><?php _e('Select the page where you have added the [water_calculator] shortcode.', 'aqualuxe'); ?></small>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Button Text:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        <?php
    }
    
    /**
     * Sanitize widget form values as they are saved
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['calculator_page'] = (!empty($new_instance['calculator_page'])) ? absint($new_instance['calculator_page']) : '';
        $instance['button_text'] = (!empty($new_instance['button_text'])) ? sanitize_text_field($new_instance['button_text']) : '';
        $instance['description'] = (!empty($new_instance['description'])) ? sanitize_textarea_field($new_instance['description']) : '';
        
        return $instance;
    }
}

/**
 * Register water calculator widget
 */
function aqualuxe_register_water_calculator_widget() {
    register_widget('Aqualuxe_Water_Calculator_Widget');
}
add_action('widgets_init', 'aqualuxe_register_water_calculator_widget');