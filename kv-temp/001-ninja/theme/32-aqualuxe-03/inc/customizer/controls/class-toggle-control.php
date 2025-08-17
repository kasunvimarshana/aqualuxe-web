<?php

/** * AquaLuxe Toggle Control * * 
 * @package AquaLuxe * 
 * @subpackage Customizer 
 * */
if (! defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/** * Register the custom control class */
function aqualuxe_register_toggle_control()
{
    /**	 * Toggle Control Class	 */
    class AquaLuxe_Toggle_Control extends WP_Customize_Control
    {
        /**		
         *  Control type		 
         * @var string	
         */

        public $type = 'aqualuxe-toggle';
        /**		
         * Render the control's content.		 
         */
        public function render_content()
        {            ?>
            <span class="customize-control-title">
                <?php echo esc_html($this->label); ?>
            </span>
            <?php if (! empty($this->description)) : ?>
                <span class="description customize-control-description">
                    <?php echo esc_html($this->description); ?>
                </span>
            <?php endif; ?>
            <div class="aqualuxe-toggle-control">
                <input type="checkbox" id="<?php echo esc_attr($this->id); ?>" name="<?php echo esc_attr($this->id); ?>" class="aqualuxe-toggle-checkbox" value="<?php echo esc_attr($this->value()); ?>"
                    <?php $this->link(); ?>
                    <?php checked($this->value()); ?>>
                <label class="aqualuxe-toggle-label" for="<?php echo esc_attr($this->id); ?>"></label>
            </div>
<?php        }
        /**		
         * Enqueue control related scripts/styles.	
         */
        public function enqueue()
        {
            wp_enqueue_style(
                'aqualuxe-toggle-control',
                get_template_directory_uri() . '/assets/css/customizer/toggle-control.css',
                array(),
                AQUALUXE_VERSION
            );
        }
    }
}
add_action('customize_register', 'aqualuxe_register_toggle_control', 0);
