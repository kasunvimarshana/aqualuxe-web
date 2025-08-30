<?php
/**
 * AquaLuxe Customizer Range Control
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Range control
 */
class AquaLuxe_Customize_Range_Control extends AquaLuxe_Customize_Control {

    /**
     * The control type.
     *
     * @access public
     * @var string
     */
    public $type = 'aqualuxe-range';

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @access public
     */
    public function to_json() {
        parent::to_json();
        
        $this->json['min']  = ( isset( $this->input_attrs['min'] ) ) ? $this->input_attrs['min'] : '0';
        $this->json['max']  = ( isset( $this->input_attrs['max'] ) ) ? $this->input_attrs['max'] : '100';
        $this->json['step'] = ( isset( $this->input_attrs['step'] ) ) ? $this->input_attrs['step'] : '1';
    }

    /**
     * Don't render the content via PHP. This control is handled with a JS template.
     *
     * @access public
     * @since 1.0.0
     * @return void
     */
    public function render_content() {}

    /**
     * An Underscore (JS) template for this control's content (but not its container).
     *
     * Class variables for this control class are available in the `data` JS object;
     * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
     *
     * @see WP_Customize_Control::print_template()
     *
     * @access protected
     */
    protected function content_template() {
        ?>
        <label>
            <# if ( data.label ) { #>
                <span class="customize-control-title">{{{ data.label }}}</span>
            <# } #>
            <# if ( data.description ) { #>
                <span class="description customize-control-description">{{{ data.description }}}</span>
            <# } #>
            <div class="aqualuxe-range-slider">
                <input type="range" {{{ data.inputAttrs }}} value="{{ data.value }}" min="{{ data.min }}" max="{{ data.max }}" step="{{ data.step }}" data-reset_value="{{ data.default }}" />
                <div class="aqualuxe-range-value-wrapper">
                    <input type="number" class="aqualuxe-range-value" value="{{ data.value }}" min="{{ data.min }}" max="{{ data.max }}" step="{{ data.step }}" {{{ data.link }}} />
                    <span class="aqualuxe-range-unit">px</span>
                </div>
                <# if ( data.default ) { #>
                    <span class="aqualuxe-range-reset" title="<?php esc_attr_e( 'Reset to default value', 'aqualuxe' ); ?>">
                        <span class="dashicons dashicons-image-rotate"></span>
                    </span>
                <# } #>
            </div>
        </label>
        <?php
    }
}