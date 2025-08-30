<?php
/**
 * Toggle Control
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Toggle Control Class
 */
class AquaLuxe_Toggle_Control extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-toggle';

    /**
     * Render control content
     *
     * @return void
     */
    public function render_content() {
        ?>
        <div class="aqualuxe-toggle-control">
            <?php if ( ! empty( $this->label ) ) : ?>
                <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <?php endif; ?>

            <?php if ( ! empty( $this->description ) ) : ?>
                <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
            <?php endif; ?>

            <div class="aqualuxe-toggle-switch">
                <input
                    type="checkbox"
                    id="<?php echo esc_attr( $this->id ); ?>"
                    name="<?php echo esc_attr( $this->id ); ?>"
                    class="aqualuxe-toggle-checkbox"
                    value="<?php echo esc_attr( $this->value() ); ?>"
                    <?php $this->link(); ?>
                    <?php checked( $this->value() ); ?>
                />
                <label for="<?php echo esc_attr( $this->id ); ?>" class="aqualuxe-toggle-label"></label>
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
            'aqualuxe-toggle-control',
            get_template_directory_uri() . '/assets/dist/css/customizer.css',
            array(),
            AQUALUXE_VERSION
        );

        wp_enqueue_script(
            'aqualuxe-toggle-control',
            get_template_directory_uri() . '/assets/dist/js/customizer.js',
            array( 'jquery', 'customize-controls' ),
            AQUALUXE_VERSION,
            true
        );
    }
}