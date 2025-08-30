<?php
/**
 * Customizer Control: Separator
 *
 * @package AquaLuxe
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Separator control
 */
class AquaLuxe_Customize_Control_Separator extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-separator';

    /**
     * Render control content
     */
    public function render_content() {
        ?>
        <div class="aqualuxe-customizer-separator">
            <hr>
            <?php if (!empty($this->label)) : ?>
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
            <?php endif; ?>
            <?php if (!empty($this->description)) : ?>
                <span class="customize-control-description"><?php echo wp_kses_post($this->description); ?></span>
            <?php endif; ?>
        </div>
        <?php
    }
}