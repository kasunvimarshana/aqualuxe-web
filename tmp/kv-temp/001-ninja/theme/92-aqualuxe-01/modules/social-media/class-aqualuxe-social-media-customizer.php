<?php
/**
 * Social Media Customizer Settings
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Social_Media_Customizer class.
 */
class AquaLuxe_Social_Media_Customizer {

	/**
	 * Instance of this class.
	 * @var object
	 */
	private static $instance;

	/**
	 * Provides access to a single instance of a module using the singleton pattern.
	 *
	 * @return object
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'customize_register', array( $this, 'register_social_links_section' ) );
	}

	/**
	 * Register the social links section in the Customizer.
	 *
	 * @param WP_Customize_Manager $wp_customize The Customizer object.
	 */
	public function register_social_links_section( $wp_customize ) {
		// Add Section
		$wp_customize->add_section(
			'aqualuxe_social_links_section',
			array(
				'title'    => __( 'Social Media Links', 'aqualuxe' ),
				'priority' => 160,
			)
		);

		// Add Setting
		$wp_customize->add_setting(
			'aqualuxe_social_links',
			array(
				'default'           => array(),
				'sanitize_callback' => array( $this, 'sanitize_social_links' ),
			)
		);

		// Add Control
		$wp_customize->add_control(
			new AquaLuxe_Repeater_Control(
				$wp_customize,
				'aqualuxe_social_links',
				array(
					'label'       => __( 'Social Links', 'aqualuxe' ),
					'section'     => 'aqualuxe_social_links_section',
					'settings'    => 'aqualuxe_social_links',
					'box_label'   => __( 'Social Link', 'aqualuxe' ),
					'add_label'   => __( 'Add New Link', 'aqualuxe' ),
					'field_label' => __( 'URL', 'aqualuxe' ),
				)
			)
		);
	}

	/**
	 * Sanitize the social links repeater field.
	 *
	 * @param array $value The value to sanitize.
	 * @return array
	 */
	public function sanitize_social_links( $value ) {
		$sanitized_value = array();
		foreach ( $value as $item ) {
			if ( ! empty( $item ) ) {
				$sanitized_value[] = esc_url_raw( $item );
			}
		}
		return $sanitized_value;
	}
}

// Since the repeater control is not standard, we need to define it.
if ( class_exists( 'WP_Customize_Control' ) ) {
	/**
	 * Repeater Custom Control
	 */
	class AquaLuxe_Repeater_Control extends WP_Customize_Control {
		public $type = 'repeater';
		public $box_label = '';
		public $add_label = '';
		public $field_label = '';

		public function __construct( $manager, $id, $args = array() ) {
			parent::__construct( $manager, $id, $args );
			$this->box_label   = $args['box_label'] ?? __( 'Item', 'aqualuxe' );
			$this->add_label   = $args['add_label'] ?? __( 'Add New', 'aqualuxe' );
			$this->field_label = $args['field_label'] ?? __( 'Value', 'aqualuxe' );
		}

		public function enqueue() {
			wp_enqueue_script( 'aqualuxe-repeater-control', get_template_directory_uri() . '/modules/social-media/repeater-control.js', array( 'jquery', 'customize-controls' ), AQUALUXE_VERSION, true );
			wp_enqueue_style( 'aqualuxe-repeater-control', get_template_directory_uri() . '/modules/social-media/repeater-control.css', array(), AQUALUXE_VERSION );
		}

		public function render_content() {
			?>
			<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
			<span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<ul class="repeater-fields">
				<?php
				$values = $this->value();
				if ( is_array( $values ) ) {
					foreach ( $values as $value ) {
						if ( ! empty( $value ) ) {
							?>
							<li class="repeater-field">
								<div class="repeater-field-header">
									<span class="repeater-field-title"><?php echo esc_html( $this->box_label ); ?></span>
									<button type="button" class="repeater-field-remove button-link-delete"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></button>
								</div>
								<input type="url" class="widefat" value="<?php echo esc_attr( $value ); ?>" />
							</li>
							<?php
						}
					}
				}
				?>
			</ul>
			<button type="button" class="button repeater-add"><?php echo esc_html( $this->add_label ); ?></button>
			<input type="hidden" <?php $this->link(); ?> class="repeater-collector" value="<?php echo esc_attr( implode( ',', (array) $this->value() ) ); ?>" />
			<?php
		}

		public function to_json() {
			parent::to_json();
			$this->json['box_label'] = $this->box_label;
			$this->json['field_label'] = $this->field_label;
		}
	}
}
