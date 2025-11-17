<?php
/**
 * Customizer Control: Toggle
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Toggle control
 */
class AquaLuxe_Customize_Control_Toggle extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-toggle';

    /**
     * Render control content
     */
    public function render_content() {
        ?>
        <div class="aqualuxe-customizer-toggle">
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
            <?php endif; ?>
            <div class="aqualuxe-toggle-control">
                <input type="checkbox" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" class="aqualuxe-toggle-checkbox" value="<?php echo esc_attr($this->value()); ?>" <?php $this->link(); checked($this->value()); ?>>
                <label for="<?php echo esc_attr($this->id); ?>" class="aqualuxe-toggle-label"></label>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue control scripts
     */
    public function enqueue() {
        wp_add_inline_style('customize-controls', '
            .aqualuxe-toggle-control {
                position: relative;
                display: inline-block;
                width: 52px;
                height: 26px;
            }
            
            .aqualuxe-toggle-checkbox {
                opacity: 0;
                width: 0;
                height: 0;
            }
            
            .aqualuxe-toggle-label {
                position: absolute;
                cursor: pointer;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: #ccc;
                -webkit-transition: .4s;
                transition: .4s;
                border-radius: 34px;
            }
            
            .aqualuxe-toggle-label:before {
                position: absolute;
                content: "";
                height: 18px;
                width: 18px;
                left: 4px;
                bottom: 4px;
                background-color: white;
                -webkit-transition: .4s;
                transition: .4s;
                border-radius: 50%;
            }
            
            .aqualuxe-toggle-checkbox:checked + .aqualuxe-toggle-label {
                background-color: #0ea5e9;
            }
            
            .aqualuxe-toggle-checkbox:focus + .aqualuxe-toggle-label {
                box-shadow: 0 0 1px #0ea5e9;
            }
            
            .aqualuxe-toggle-checkbox:checked + .aqualuxe-toggle-label:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }
        ');
    }
}