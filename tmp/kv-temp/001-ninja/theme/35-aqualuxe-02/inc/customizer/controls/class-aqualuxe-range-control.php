<?php
/**
 * Range Control
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Range Control Class
 */
class AquaLuxe_Range_Control extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-range';

    /**
     * Control input attributes
     *
     * @var array
     */
    public $input_attrs = array(
        'min'  => 0,
        'max'  => 100,
        'step' => 1,
        'unit' => 'px',
    );

    /**
     * Render control content
     *
     * @return void
     */
    public function render_content() {
        $min = isset( $this->input_attrs['min'] ) ? $this->input_attrs['min'] : 0;
        $max = isset( $this->input_attrs['max'] ) ? $this->input_attrs['max'] : 100;
        $step = isset( $this->input_attrs['step'] ) ? $this->input_attrs['step'] : 1;
        $unit = isset( $this->input_attrs['unit'] ) ? $this->input_attrs['unit'] : 'px';
        ?>
        <div class="aqualuxe-range-control">
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif; ?>

            <?php if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <?php endif; ?>

            <div class="aqualuxe-range-wrapper">
                <input
                    type="range"
                    id="<?php echo esc_attr( $this->id ); ?>-range"
                    class="aqualuxe-range-input"
                    min="<?php echo esc_attr( $min ); ?>"
                    max="<?php echo esc_attr( $max ); ?>"
                    step="<?php echo esc_attr( $step ); ?>"
                    value="<?php echo esc_attr( $this->value() ); ?>"
                    data-unit="<?php echo esc_attr( $unit ); ?>"
                    <?php $this->link(); ?>
                />
                <div class="aqualuxe-range-value">
                    <input
                        type="number"
                        id="<?php echo esc_attr( $this->id ); ?>-number"
                        class="aqualuxe-range-number"
                        min="<?php echo esc_attr( $min ); ?>"
                        max="<?php echo esc_attr( $max ); ?>"
                        step="<?php echo esc_attr( $step ); ?>"
                        value="<?php echo esc_attr( $this->value() ); ?>"
                    />
                    <span class="aqualuxe-range-unit"><?php echo esc_html( $unit ); ?></span>
                </div>
                <div class="aqualuxe-range-reset">
                    <span class="dashicons dashicons-image-rotate"></span>
                </div>
            </div>
        </div>
        <?php
    }

    /**
     * Enqueue control scripts
     *
     * @return void
     */
    public function enqueue() {
        wp_enqueue_style(
            'aqualuxe-range-control',
            get_template_directory_uri() . '/assets/dist/css/customizer.css',
            array(),
            AQUALUXE_VERSION
        );

        wp_enqueue_script(
            'aqualuxe-range-control',
            get_template_directory_uri() . '/assets/dist/js/customizer.js',
            array( 'jquery', 'customize-controls' ),
            AQUALUXE_VERSION,
            true
        );
    }
}