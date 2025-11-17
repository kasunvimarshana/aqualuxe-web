<?php
/**
 * Heading Control
 *
 * Customizer control for section headings.
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Controls;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Heading Control Class
 *
 * Creates a heading control for the customizer.
 */
class Heading_Control extends \WP_Customize_Control {

	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-heading';

	/**
	 * Heading level (h1, h2, h3, h4, h5, h6)
	 *
	 * @var string
	 */
	public $heading_level = 'h3';

	/**
	 * Additional CSS class
	 *
	 * @var string
	 */
	public $css_class = '';

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {
		wp_enqueue_style(
			'aqualuxe-heading-control',
			get_template_directory_uri() . '/assets/css/admin/customizer-controls.css',
			array(),
			AQUALUXE_VERSION
		);
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['heading_level'] = $this->heading_level;
		$this->json['css_class']     = $this->css_class;
	}

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		$heading_tag = in_array( $this->heading_level, array( 'h1', 'h2', 'h3', 'h4', 'h5', 'h6' ), true ) ? $this->heading_level : 'h3';
		$css_class   = ! empty( $this->css_class ) ? ' ' . esc_attr( $this->css_class ) : '';
		?>
		<div class="aqualuxe-heading-control<?php echo esc_attr( $css_class ); ?>">
			<?php if ( ! empty( $this->label ) ) : ?>
				<<?php echo esc_attr( $heading_tag ); ?> class="aqualuxe-heading-title">
					<?php echo esc_html( $this->label ); ?>
				</<?php echo esc_attr( $heading_tag ); ?>>
			<?php endif; ?>

			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	/**
	 * Don't render any input in the control.
	 *
	 * @return void
	 */
	protected function render_input() {
		// No input needed for this control.
	}

	/**
	 * Render a JS template for the content of the control
	 */
	public function content_template() {
		?>
		<div class="aqualuxe-heading-control {{ data.css_class }}">
			<# if ( data.label ) { #>
				<{{ data.heading_level }} class="aqualuxe-heading-title">
					{{ data.label }}
				</{{ data.heading_level }}>
			<# } #>
			
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
		</div>
		<?php
	}
}