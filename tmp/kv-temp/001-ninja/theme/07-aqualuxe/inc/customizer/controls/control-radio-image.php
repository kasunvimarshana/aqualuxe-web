<?php
/**
 * Customizer Control: Radio Image
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Radio Image control
 */
class AquaLuxe_Customize_Control_Radio_Image extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-radio-image';

    /**
     * Render control content
     */
    public function render_content() {
        if (empty($this->choices)) {
            return;
        }
        ?>
        <div class="aqualuxe-customizer-radio-image">
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
            <?php endif; ?>
            <div class="aqualuxe-radio-image-choices">
                <?php foreach ($this->choices as $value => $args) : ?>
                    <?php
                    $label = isset($args['label']) ? $args['label'] : '';
                    $image = isset($args['image']) ? $args['image'] : '';
                    ?>
                    <label class="aqualuxe-radio-image-label">
                        <input type="radio" name="<?php echo esc_attr($this->id); ?>" value="<?php echo esc_attr($value); ?>" <?php $this->link(); checked($this->value(), $value); ?>>
                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($label); ?>" title="<?php echo esc_attr($label); ?>">
                        <span class="aqualuxe-radio-image-title"><?php echo esc_html($label); ?></span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue control scripts
     */
    public function enqueue() {
        wp_add_inline_style('customize-controls', '
            .aqualuxe-radio-image-choices {
                display: flex;
                flex-wrap: wrap;
                margin: -5px;
            }
            
            .aqualuxe-radio-image-label {
                display: inline-block;
                margin: 5px;
                position: relative;
                text-align: center;
            }
            
            .aqualuxe-radio-image-label input {
                opacity: 0;
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                z-index: 1;
                margin: 0;
                cursor: pointer;
            }
            
            .aqualuxe-radio-image-label img {
                border: 2px solid #ddd;
                border-radius: 4px;
                transition: all 0.3s;
                width: 75px;
                height: auto;
            }
            
            .aqualuxe-radio-image-label input:checked + img {
                border-color: #0ea5e9;
                box-shadow: 0 0 5px rgba(14, 165, 233, 0.5);
            }
            
            .aqualuxe-radio-image-title {
                display: block;
                font-size: 12px;
                margin-top: 5px;
            }
        ');
    }
}