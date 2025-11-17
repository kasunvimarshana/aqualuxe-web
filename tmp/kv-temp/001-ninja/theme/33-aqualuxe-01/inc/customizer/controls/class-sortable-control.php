<?php
/**
 * Sortable Control
 *
 * Customizer control for sortable items.
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Customizer\Controls;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Sortable Control Class
 *
 * Creates a sortable list control for the customizer.
 */
class Sortable_Control extends \WP_Customize_Control {

	/**
	 * The type of control being rendered
	 *
	 * @var string
	 */
	public $type = 'aqualuxe-sortable';

	/**
	 * Button labels
	 *
	 * @var array
	 */
	public $button_labels = array();

	/**
	 * Constructor
	 *
	 * @param WP_Customize_Manager $manager Customizer bootstrap instance.
	 * @param string               $id      Control ID.
	 * @param array                $args    Control arguments.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		
		// Set button labels with defaults.
		$this->button_labels = wp_parse_args(
			$this->button_labels,
			array(
				'add'    => esc_html__( 'Add', 'aqualuxe' ),
				'remove' => esc_html__( 'Remove', 'aqualuxe' ),
				'edit'   => esc_html__( 'Edit', 'aqualuxe' ),
			)
		);
	}

	/**
	 * Enqueue control related scripts/styles.
	 */
	public function enqueue() {
		wp_enqueue_style(
			'aqualuxe-sortable-control',
			get_template_directory_uri() . '/assets/css/admin/customizer-controls.css',
			array(),
			AQUALUXE_VERSION
		);

		wp_enqueue_script(
			'aqualuxe-sortable-control',
			get_template_directory_uri() . '/assets/js/admin/customizer-controls.js',
			array( 'jquery', 'customize-base', 'jquery-ui-sortable' ),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Refresh the parameters passed to the JavaScript via JSON.
	 */
	public function to_json() {
		parent::to_json();
		$this->json['default'] = $this->setting->default;
		if ( isset( $this->default ) ) {
			$this->json['default'] = $this->default;
		}
		$this->json['value']         = maybe_unserialize( $this->value() );
		$this->json['choices']       = $this->choices;
		$this->json['link']          = $this->get_link();
		$this->json['id']            = $this->id;
		$this->json['button_labels'] = $this->button_labels;
	}

	/**
	 * Don't render the control content from PHP, as it's rendered via JS on load.
	 */
	public function render_content() {}

	/**
	 * Render a JS template for the content of the control
	 */
	public function content_template() {
		?>
		<div class="aqualuxe-sortable-control">
			<# if ( data.label ) { #>
				<span class="customize-control-title">{{{ data.label }}}</span>
			<# } #>
			
			<# if ( data.description ) { #>
				<span class="description customize-control-description">{{{ data.description }}}</span>
			<# } #>
			
			<ul class="aqualuxe-sortable-list">
				<# _.each( data.value, function( choiceID ) { #>
					<# if ( data.choices[ choiceID ] ) { #>
						<li class="aqualuxe-sortable-item" data-value="{{ choiceID }}">
							<span class="aqualuxe-sortable-title">{{ data.choices[ choiceID ] }}</span>
							<span class="aqualuxe-sortable-handle dashicons dashicons-menu"></span>
						</li>
					<# } #>
				<# }); #>
				
				<# _.each( data.choices, function( choiceLabel, choiceID ) { #>
					<# if ( -1 === data.value.indexOf( choiceID ) ) { #>
						<li class="aqualuxe-sortable-item aqualuxe-sortable-inactive" data-value="{{ choiceID }}">
							<span class="aqualuxe-sortable-title">{{ choiceLabel }}</span>
							<span class="aqualuxe-sortable-handle dashicons dashicons-plus"></span>
						</li>
					<# } #>
				<# }); #>
			</ul>
			
			<div class="aqualuxe-sortable-buttons">
				<button type="button" class="button-secondary aqualuxe-sortable-reset">
					<?php esc_html_e( 'Reset to Default', 'aqualuxe' ); ?>
				</button>
			</div>
			
			<input type="hidden" {{{ data.link }}} value="{{ JSON.stringify( data.value ) }}" />
		</div>
		<?php
	}
}