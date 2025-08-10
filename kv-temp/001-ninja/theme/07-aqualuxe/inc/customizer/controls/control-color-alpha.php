<?php
/**
 * Customizer Control: Color Alpha
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Color Alpha control
 */
class AquaLuxe_Customize_Control_Color_Alpha extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-color-alpha';

    /**
     * Default color
     *
     * @var string
     */
    public $default = '';

    /**
     * Render control content
     */
    public function render_content() {
        // Process the palette
        if (is_array($this->palette)) {
            $palette = implode('|', $this->palette);
        } else {
            // Default to true
            $palette = (false === $this->palette || 'false' === $this->palette) ? 'false' : 'true';
        }

        // Support passing show_opacity as string or boolean
        $show_opacity = (false === $this->show_opacity || 'false' === $this->show_opacity) ? 'false' : 'true';

        // Begin the output
        ?>
        <div class="aqualuxe-customizer-color-alpha">
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
            <?php endif; ?>
            <div class="aqualuxe-color-alpha-control">
                <input type="text" data-palette="<?php echo esc_attr($palette); ?>" data-default-color="<?php echo esc_attr($this->default); ?>" data-show-opacity="<?php echo esc_attr($show_opacity); ?>" data-customize-setting-link="<?php echo esc_attr($this->id); ?>" class="aqualuxe-color-alpha-picker" value="<?php echo esc_attr($this->value()); ?>">
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue control scripts
     */
    public function enqueue() {
        wp_enqueue_script('wp-color-picker');
        wp_enqueue_style('wp-color-picker');

        wp_enqueue_script('wp-color-picker-alpha', AQUALUXE_URI . '/assets/js/wp-color-picker-alpha.min.js', array('wp-color-picker'), '3.0.0', true);

        wp_add_inline_script('customize-controls', '
            jQuery(document).ready(function($) {
                $(".aqualuxe-color-alpha-picker").wpColorPicker({
                    change: function(event, ui) {
                        $(this).val(ui.color.toString()).trigger("change");
                    },
                    clear: function() {
                        $(this).val("").trigger("change");
                    }
                });
            });
        ');
    }
}