<?php
/**
 * Customizer Control: Dimensions
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Dimensions control
 */
class AquaLuxe_Customize_Control_Dimensions extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-dimensions';

    /**
     * Dimensions
     *
     * @var array
     */
    public $dimensions = array(
        'top' => true,
        'right' => true,
        'bottom' => true,
        'left' => true,
    );

    /**
     * Unit
     *
     * @var string
     */
    public $unit = 'px';

    /**
     * Units
     *
     * @var array
     */
    public $units = array(
        'px' => 'px',
        'em' => 'em',
        'rem' => 'rem',
        '%' => '%',
    );

    /**
     * Render control content
     */
    public function render_content() {
        $value = $this->value();
        $value = !is_array($value) ? json_decode($value, true) : $value;
        $value = wp_parse_args($value, array(
            'top' => '',
            'right' => '',
            'bottom' => '',
            'left' => '',
            'unit' => $this->unit,
        ));
        ?>
        <div class="aqualuxe-customizer-dimensions">
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
            <?php endif; ?>
            <div class="aqualuxe-dimensions-control">
                <div class="aqualuxe-dimensions-inputs">
                    <?php foreach ($this->dimensions as $dimension => $is_enabled) : ?>
                        <?php if ($is_enabled) : ?>
                            <div class="aqualuxe-dimension-input">
                                <label for="<?php echo esc_attr($this->id . '-' . $dimension); ?>"><?php echo esc_html(ucfirst($dimension)); ?></label>
                                <input type="number" id="<?php echo esc_attr($this->id . '-' . $dimension); ?>" class="aqualuxe-dimension-value" data-dimension="<?php echo esc_attr($dimension); ?>" value="<?php echo esc_attr($value[$dimension]); ?>">
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <?php if (!empty($this->units)) : ?>
                    <div class="aqualuxe-dimensions-unit">
                        <select class="aqualuxe-dimension-unit">
                            <?php foreach ($this->units as $unit_value => $unit_label) : ?>
                                <option value="<?php echo esc_attr($unit_value); ?>" <?php selected($value['unit'], $unit_value); ?>><?php echo esc_html($unit_label); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                <?php endif; ?>
                <div class="aqualuxe-dimensions-link">
                    <button type="button" class="aqualuxe-dimensions-link-button" title="<?php esc_attr_e('Link values', 'aqualuxe'); ?>">
                        <span class="dashicons dashicons-admin-links"></span>
                    </button>
                </div>
            </div>
            <input type="hidden" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr(json_encode($value)); ?>" <?php $this->link(); ?>>
        </div>
        <?php
    }

    /**
     * Enqueue control scripts
     */
    public function enqueue() {
        wp_add_inline_style('customize-controls', '
            .aqualuxe-dimensions-control {
                display: flex;
                align-items: flex-end;
            }
            
            .aqualuxe-dimensions-inputs {
                display: flex;
                flex-grow: 1;
            }
            
            .aqualuxe-dimension-input {
                flex-grow: 1;
                margin-right: 5px;
                text-align: center;
            }
            
            .aqualuxe-dimension-input label {
                display: block;
                font-size: 10px;
                font-weight: 500;
                text-transform: uppercase;
                margin-bottom: 5px;
            }
            
            .aqualuxe-dimension-input input {
                width: 100%;
                text-align: center;
            }
            
            .aqualuxe-dimensions-unit {
                width: 60px;
                margin-right: 5px;
            }
            
            .aqualuxe-dimensions-unit select {
                width: 100%;
                height: 30px;
            }
            
            .aqualuxe-dimensions-link {
                width: 30px;
            }
            
            .aqualuxe-dimensions-link-button {
                width: 30px;
                height: 30px;
                padding: 0;
                background: none;
                border: 1px solid #ddd;
                border-radius: 4px;
                cursor: pointer;
            }
            
            .aqualuxe-dimensions-link-button.linked {
                background-color: #0073aa;
                border-color: #0073aa;
                color: #fff;
            }
        ');

        wp_add_inline_script('customize-controls', '
            jQuery(document).ready(function($) {
                $(".aqualuxe-dimensions-control").each(function() {
                    var control = $(this);
                    var inputs = control.find(".aqualuxe-dimension-value");
                    var linkButton = control.find(".aqualuxe-dimensions-link-button");
                    var unitSelect = control.find(".aqualuxe-dimension-unit");
                    var hiddenInput = control.closest(".aqualuxe-customizer-dimensions").find("input[type=\'hidden\']");
                    var isLinked = false;
                    
                    // Update hidden input
                    function updateValue() {
                        var value = {};
                        
                        inputs.each(function() {
                            var dimension = $(this).data("dimension");
                            value[dimension] = $(this).val();
                        });
                        
                        value.unit = unitSelect.val();
                        
                        hiddenInput.val(JSON.stringify(value)).trigger("change");
                    }
                    
                    // Link/unlink values
                    linkButton.on("click", function() {
                        isLinked = !isLinked;
                        $(this).toggleClass("linked", isLinked);
                    });
                    
                    // Handle input changes
                    inputs.on("input", function() {
                        if (isLinked) {
                            var value = $(this).val();
                            inputs.not(this).val(value);
                        }
                        
                        updateValue();
                    });
                    
                    // Handle unit changes
                    unitSelect.on("change", function() {
                        updateValue();
                    });
                });
            });
        ');
    }
}