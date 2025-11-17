<?php
/**
 * Color Alpha Control
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Color Alpha Control Class
 */
class AquaLuxe_Color_Alpha_Control extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-color-alpha';

    /**
     * Add support for palettes to be passed in.
     *
     * @var bool
     */
    public $palette;

    /**
     * Add support for showing the opacity value on the slider handle.
     *
     * @var bool
     */
    public $show_opacity;

    /**
     * Render control content
     *
     * @return void
     */
    public function render_content() {
        // Process palette.
        $palette = ( is_array( $this->palette ) ) ? $this->palette : true;

        // Support passing show_opacity as string or boolean.
        $show_opacity = ( true === $this->show_opacity || 'true' === $this->show_opacity ) ? true : false;

        // Begin the output.
        ?>
        <div class="aqualuxe-color-alpha-control">
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif; ?>

            <?php if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <?php endif; ?>

            <div class="aqualuxe-color-alpha-wrapper">
                <input
                    type="text"
                    class="aqualuxe-color-alpha-control-input"
                    id="<?php echo esc_attr( $this->id ); ?>"
                    name="<?php echo esc_attr( $this->id ); ?>"
                    value="<?php echo esc_attr( $this->value() ); ?>"
                    data-show-opacity="<?php echo esc_attr( $show_opacity ); ?>"
                    data-palette="<?php echo esc_attr( is_array( $palette ) ? implode( '|', $palette ) : $palette ); ?>"
                    <?php $this->link(); ?>
                />
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
            'wp-color-picker'
        );

        wp_enqueue_script(
            'wp-color-picker-alpha',
            get_template_directory_uri() . '/assets/dist/js/wp-color-picker-alpha.min.js',
            array( 'wp-color-picker' ),
            AQUALUXE_VERSION,
            true
        );

        wp_enqueue_script(
            'aqualuxe-color-alpha-control',
            get_template_directory_uri() . '/assets/dist/js/customizer.js',
            array( 'jquery', 'customize-controls', 'wp-color-picker-alpha' ),
            AQUALUXE_VERSION,
            true
        );
    }
}