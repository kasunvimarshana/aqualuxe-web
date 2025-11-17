<?php
/**
 * Customizer Control: Slider
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Slider control
 */
class AquaLuxe_Customize_Control_Slider extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-slider';

    /**
     * Minimum value
     *
     * @var int
     */
    public $min = 0;

    /**
     * Maximum value
     *
     * @var int
     */
    public $max = 100;

    /**
     * Step size
     *
     * @var int
     */
    public $step = 1;

    /**
     * Unit
     *
     * @var string
     */
    public $unit = '';

    /**
     * Render control content
     */
    public function render_content() {
        ?>
        <div class="aqualuxe-customizer-slider">
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
            <?php endif; ?>
            <div class="aqualuxe-slider-control">
                <div class="aqualuxe-slider-range" data-min="<?php echo esc_attr($this->min); ?>" data-max="<?php echo esc_attr($this->max); ?>" data-step="<?php echo esc_attr($this->step); ?>"></div>
                <div class="aqualuxe-slider-input">
                    <input type="number" <?php $this->link(); ?> value="<?php echo esc_attr($this->value()); ?>" min="<?php echo esc_attr($this->min); ?>" max="<?php echo esc_attr($this->max); ?>" step="<?php echo esc_attr($this->step); ?>" class="aqualuxe-slider-value">
                    <?php if (!empty($this->unit)) : ?>
                        <span class="aqualuxe-slider-unit"><?php echo esc_html($this->unit); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue control scripts
     */
    public function enqueue() {
        wp_enqueue_script('jquery-ui-slider');
        wp_enqueue_style('jquery-ui', 'https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css');

        wp_add_inline_script('customize-controls', '
            jQuery(document).ready(function($) {
                $(".aqualuxe-slider-range").each(function() {
                    var slider = $(this);
                    var input = slider.closest(".aqualuxe-slider-control").find(".aqualuxe-slider-value");
                    var min = slider.data("min");
                    var max = slider.data("max");
                    var step = slider.data("step");
                    
                    slider.slider({
                        range: "min",
                        min: min,
                        max: max,
                        step: step,
                        value: input.val(),
                        slide: function(event, ui) {
                            input.val(ui.value).trigger("change");
                        }
                    });
                    
                    input.on("change", function() {
                        slider.slider("value", $(this).val());
                    });
                });
            });
        ');
    }
}