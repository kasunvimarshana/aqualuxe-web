<?php
/**
 * AquaLuxe Theme Customizer - Pro Section
 *
 * @package AquaLuxe
 */




namespace AquaLuxe\Customizer\Sections;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


	/**
	 * Pro customizer section.
	 *
	 * @since  1.0.0
	 * @access public
	 */
class ProSection {

		/**
		 * The type of customize section being rendered.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $type = 'aqualuxe-pro';

		/**
		 * Custom button text to output.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $pro_text = '';

		/**
		 * Custom pro button URL.
		 *
		 * @since  1.0.0
		 * @access public
		 * @var    string
		 */
		public $pro_url = '';

		/**
		 * Add custom parameters to pass to the JS via JSON.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return array
		 */
		public function json() {
			$json = parent::json();

			$json['pro_text'] = $this->pro_text;
			$json['pro_url']  = esc_url( $this->pro_url );

			return $json;
		}

		/**
		 * Outputs the Underscore.js template.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return void
		 */
		protected function render_template() {
			?>
			<li id="accordion-section-{{ data.id }}" class="accordion-section control-section control-section-{{ data.type }} cannot-expand">
				<h3 class="accordion-section-title">
					{{ data.title }}
					<# if ( data.pro_text && data.pro_url ) { #>
						<a href="{{ data.pro_url }}" class="button button-secondary alignright" target="_blank">{{ data.pro_text }}</a>
					<# } #>
				</h3>
			</li>
			<?php
		}
	}
}

/**
 * Register customizer settings
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function aqualuxe_register_pro_section( $wp_customize ) {
	// Register custom section types.
	$wp_customize->register_section_type( 'AquaLuxe_Customizer_Pro_Section' );

	// Register sections.
	$wp_customize->add_section(
		new AquaLuxe_Customizer_Pro_Section(
			$wp_customize,
			'aqualuxe_pro',
			array(
				'title'    => esc_html__( 'AquaLuxe Pro', 'aqualuxe' ),
				'priority' => 0,
				'pro_text' => esc_html__( 'Upgrade to Pro', 'aqualuxe' ),
				'pro_url'  => 'https://aqualuxetheme.com/pro',
			)
		)
	);
}
add_action( 'customize_register', 'aqualuxe_register_pro_section' );
