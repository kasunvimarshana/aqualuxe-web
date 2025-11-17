<?php
/**
 * Toggle Control Class
 *
 * @package AquaLuxe
 * @subpackage Customizer
 */

namespace AquaLuxe\Customizer\Controls;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Toggle Control Class
 *
 * Creates a toggle switch control for the customizer.
 */
class Toggle_Control extends \WP_Customize_Control {

	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-toggle';

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		?>
		<div class="aqualuxe-toggle-control">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>
			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php endif; ?>
			<div class="aqualuxe-toggle">
				<input id="<?php echo esc_attr( $this->id ); ?>" type="checkbox" class="aqualuxe-toggle-checkbox" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); checked( $this->value() ); ?> />
				<label class="aqualuxe-toggle-label" for="<?php echo esc_attr( $this->id ); ?>"></label>
			</div>
		</div>
		<?php
	}

	/**
	 * Enqueue control related scripts/styles
	 */
	public function enqueue() {
		// Enqueue control scripts and styles.
		wp_enqueue_style( 'aqualuxe-toggle-control', AQUALUXE_ASSETS_URI . 'css/customizer-toggle-control.css', array(), AQUALUXE_VERSION );
		wp_enqueue_script( 'aqualuxe-toggle-control', AQUALUXE_ASSETS_URI . 'js/customizer-toggle-control.js', array( 'jquery' ), AQUALUXE_VERSION, true );
	}

	/**
	 * Export data to JS
	 *
	 * @return array
	 */
	public function json() {
		$json = parent::json();
		$json['id'] = $this->id;
		$json['link'] = $this->get_link();
		$json['value'] = $this->value();
		return $json;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 */
	public function render_content_empty() {}

	/**
	 * Render a JS template for the content of the control
	 */
	public function content_template() {
		?>
		<div class="aqualuxe-toggle-control">
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
			<# } #>
			<# if ( data.description ) { #>
				<span class="customize-control-description">{{ data.description }}</span>
			<# } #>
			<div class="aqualuxe-toggle">
				<input id="{{ data.id }}" type="checkbox" class="aqualuxe-toggle-checkbox" value="{{ data.value }}" {{{ data.link }}} <# if ( data.value ) { #> checked="checked" <# } #> />
				<label class="aqualuxe-toggle-label" for="{{ data.id }}"></label>
			</div>
		</div>
		<?php
	}
}