<?php
/**
 * AquaLuxe Customizer Control Base Class
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * AquaLuxe Customizer Control Base Class
 */
class AquaLuxe_Customize_Control extends WP_Customize_Control {

    /**
     * Used to automatically generate all CSS output.
     *
     * @access public
     * @var array
     */
    public $output = array();

    /**
     * Data type
     *
     * @access public
     * @var string
     */
    public $option_type = 'theme_mod';

    /**
     * The control type.
     *
     * @access public
     * @var string
     */
    public $type = 'aqualuxe-base';

    /**
     * Constructor.
     *
     * @param WP_Customize_Manager $manager Customizer bootstrap instance.
     * @param string               $id      Control ID.
     * @param array                $args    Optional. Arguments to override class property defaults.
     */
    public function __construct( $manager, $id, $args = array() ) {
        parent::__construct( $manager, $id, $args );
        
        // Set option type
        if ( isset( $args['option_type'] ) ) {
            $this->option_type = $args['option_type'];
        }
    }

    /**
     * Refresh the parameters passed to the JavaScript via JSON.
     *
     * @access public
     */
    public function to_json() {
        parent::to_json();

        $this->json['default'] = $this->setting->default;
        if ( isset( $this->default ) ) {
            $this->json['default'] = $this->default;
        }
        $this->json['output']  = $this->output;
        $this->json['value']   = $this->value();
        $this->json['choices'] = $this->choices;
        $this->json['link']    = $this->get_link();
        $this->json['id']      = $this->id;

        $this->json['inputAttrs'] = '';
        foreach ( $this->input_attrs as $attr => $value ) {
            $this->json['inputAttrs'] .= $attr . '="' . esc_attr( $value ) . '" ';
        }
    }

    /**
     * Enqueue control related scripts/styles.
     *
     * @access public
     */
    public function enqueue() {
        // Enqueue the script and style
        wp_enqueue_style( 'aqualuxe-customizer-controls', AQUALUXE_ASSETS_URI . 'css/customizer-controls.css', array(), AQUALUXE_VERSION );
        wp_enqueue_script( 'aqualuxe-customizer-controls', AQUALUXE_ASSETS_URI . 'js/customizer-controls.js', array( 'jquery', 'customize-base' ), AQUALUXE_VERSION, true );
    }

    /**
     * An Underscore (JS) template for this control's content (but not its container).
     *
     * Class variables for this control class are available in the `data` JS object;
     * export custom variables by overriding {@see WP_Customize_Control::to_json()}.
     *
     * @see WP_Customize_Control::print_template()
     *
     * @access protected
     */
    protected function content_template() {}

    /**
     * Render the control's content.
     *
     * @access protected
     */
    protected function render_content() {}
}