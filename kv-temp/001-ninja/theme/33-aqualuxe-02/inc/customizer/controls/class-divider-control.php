<?php
/**
 * Divider Control
 *
 * Customizer control for visual dividers.
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Controls;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Divider Control Class
 *
 * Creates a visual divider in the customizer.
 */
class Divider_Control extends \WP_Customize_Control {

	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-divider';

	/**
	 * Divider style (solid, dashed, dotted)
	 *
	 * @var string
	 */
	public $style = 'solid';

	/**
	 * Divider thickness in pixels
	 *
	 * @var int
	 */
	public $thickness = 1;

	/**
	 * Divider margin in pixels
	 *
	 * @var int
	 */
	public $margin = 15;

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
			'aqualuxe-divider-control',
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
		$this->json['style']     = $this->style;
		$this->json['thickness'] = $this->thickness;
		$this->json['margin']    = $this->margin;
		$this->json['css_class'] = $this->css_class;
	}

	/**
	 * Render the control's content.
	 */
	public function render_content() {
		$style     = in_array( $this->style, array( 'solid', 'dashed', 'dotted' ), true ) ? $this->style : 'solid';
		$thickness = absint( $this->thickness ) > 0 ? absint( $this->thickness ) : 1;
		$margin    = absint( $this->margin ) > 0 ? absint( $this->margin ) : 15;
		$css_class = ! empty( $this->css_class ) ? ' ' . esc_attr( $this->css_class ) : '';
		?>
		<div class="aqualuxe-divider-control<?php echo esc_attr( $css_class ); ?>">
			<?php if ( ! empty( $this->label ) ) : ?>
				<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<?php endif; ?>

			<?php if ( ! empty( $this->description ) ) : ?>
				<span class="description customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
			<?php endif; ?>

			<div class="aqualuxe-divider" style="border-bottom: <?php echo esc_attr( $thickness ); ?>px <?php echo esc_attr( $style ); ?> #ccc; margin: <?php echo esc_attr( $margin ); ?>px 0;"></div>
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
		<div class="aqualuxe-divider-control {{ data.css_class }}">
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{ data.label }}</span>
			<# } #>
			
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
			
			<div class="aqualuxe-divider" style="border-bottom: {{ data.thickness }}px {{ data.style }} #ccc; margin: {{ data.margin }}px 0;"></div>
		</div>
		<?php
	}
}