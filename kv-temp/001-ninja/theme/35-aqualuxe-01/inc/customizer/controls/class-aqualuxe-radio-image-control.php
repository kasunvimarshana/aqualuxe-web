<?php
/**
 * Radio Image Control
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Radio Image Control Class
 */
class AquaLuxe_Radio_Image_Control extends WP_Customize_Control {
    /**
     * Control type
     *
     * @var string
     */
    public $type = 'aqualuxe-radio-image';

    /**
     * Render control content
     *
     * @return void
     */
    public function render_content() {
        if ( empty( $this->choices ) ) {
            return;
        }

        $name = '_customize-radio-' . $this->id;
        ?>
        <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
        <?php if ( ! empty( $this->description ) ) : ?>
            <span class="description customize-control-description"><?php echo esc_html( $this->description ); ?></span>
        <?php endif; ?>

        <div class="aqualuxe-radio-image-control">
            <?php foreach ( $this->choices as $value => $choice ) : ?>
                <label for="<?php echo esc_attr( $this->id . '-' . $value ); ?>" class="aqualuxe-radio-image-label">
                    <input
                        type="radio"
                        id="<?php echo esc_attr( $this->id . '-' . $value ); ?>"
                        name="<?php echo esc_attr( $name ); ?>"
                        value="<?php echo esc_attr( $value ); ?>"
                        <?php $this->link(); ?>
                        <?php checked( $this->value(), $value ); ?>
                    />
                    <span class="aqualuxe-radio-image-item">
                        <?php if ( isset( $choice['icon'] ) ) : ?>
                            <span class="aqualuxe-radio-image-icon aqualuxe-icon-<?php echo esc_attr( $choice['icon'] ); ?>"></span>
                        <?php endif; ?>
                        <span class="aqualuxe-radio-image-label-text"><?php echo esc_html( $choice['label'] ); ?></span>
                    </span>
                </label>
            <?php endforeach; ?>
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
            'aqualuxe-radio-image-control',
            get_template_directory_uri() . '/assets/dist/css/customizer.css',
            array(),
            AQUALUXE_VERSION
        );

        wp_enqueue_script(
            'aqualuxe-radio-image-control',
            get_template_directory_uri() . '/assets/dist/js/customizer.js',
            array( 'jquery', 'customize-controls' ),
            AQUALUXE_VERSION,
            true
        );
    }
}