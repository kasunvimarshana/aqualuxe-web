<?php
/**
 * FAQ Accordion Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * FAQ Accordion Module Class
 */
class AquaLuxe_FAQ_Accordion {

    /**
     * Module settings
     *
     * @var array
     */
    private $settings;

    /**
     * Constructor
     */
    public function __construct() {
        $this->settings = get_option( 'aqualuxe_faq_accordion_settings', array(
            'schema_markup'      => true,
            'default_open_first' => true,
            'animation_speed'    => 300,
            'collapsible'        => true,
            'allow_multiple'     => false,
            'custom_css_class'   => '',
        ) );

        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Register shortcode
        add_shortcode( 'aqualuxe_faq', array( $this, 'faq_shortcode' ) );
        add_shortcode( 'aqualuxe_faq_item', array( $this, 'faq_item_shortcode' ) );
        
        // Register Gutenberg block
        add_action( 'init', array( $this, 'register_block' ) );
        
        // Register settings page
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        
        // Enqueue scripts and styles
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
        
        // Register FAQ post type
        add_action( 'init', array( $this, 'register_faq_post_type' ) );
        
        // Register FAQ category taxonomy
        add_action( 'init', array( $this, 'register_faq_taxonomy' ) );
    }

    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Enqueue styles
        wp_enqueue_style(
            'aqualuxe-faq-accordion',
            plugin_dir_url( __FILE__ ) . 'assets/css/faq-accordion.css',
            array(),
            '1.0.0'
        );

        // Enqueue scripts
        wp_enqueue_script(
            'aqualuxe-faq-accordion',
            plugin_dir_url( __FILE__ ) . 'assets/js/faq-accordion.js',
            array( 'jquery' ),
            '1.0.0',
            true
        );

        // Localize script
        wp_localize_script(
            'aqualuxe-faq-accordion',
            'aqualuxeFaqAccordion',
            array(
                'animationSpeed' => absint( $this->settings['animation_speed'] ),
                'collapsible'    => (bool) $this->settings['collapsible'],
                'allowMultiple'  => (bool) $this->settings['allow_multiple'],
            )
        );
    }

    /**
     * Register FAQ post type
     */
    public function register_faq_post_type() {
        $labels = array(
            'name'               => _x( 'FAQs', 'post type general name', 'aqualuxe' ),
            'singular_name'      => _x( 'FAQ', 'post type singular name', 'aqualuxe' ),
            'menu_name'          => _x( 'FAQs', 'admin menu', 'aqualuxe' ),
            'name_admin_bar'     => _x( 'FAQ', 'add new on admin bar', 'aqualuxe' ),
            'add_new'            => _x( 'Add New', 'faq', 'aqualuxe' ),
            'add_new_item'       => __( 'Add New FAQ', 'aqualuxe' ),
            'new_item'           => __( 'New FAQ', 'aqualuxe' ),
            'edit_item'          => __( 'Edit FAQ', 'aqualuxe' ),
            'view_item'          => __( 'View FAQ', 'aqualuxe' ),
            'all_items'          => __( 'All FAQs', 'aqualuxe' ),
            'search_items'       => __( 'Search FAQs', 'aqualuxe' ),
            'parent_item_colon'  => __( 'Parent FAQs:', 'aqualuxe' ),
            'not_found'          => __( 'No FAQs found.', 'aqualuxe' ),
            'not_found_in_trash' => __( 'No FAQs found in Trash.', 'aqualuxe' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'faq' ),
            'capability_type'    => 'post',
            'has_archive'        => true,
            'hierarchical'       => false,
            'menu_position'      => null,
            'menu_icon'          => 'dashicons-editor-help',
            'supports'           => array( 'title', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
            'show_in_rest'       => true,
        );

        register_post_type( 'aqualuxe_faq', $args );
    }

    /**
     * Register FAQ category taxonomy
     */
    public function register_faq_taxonomy() {
        $labels = array(
            'name'              => _x( 'FAQ Categories', 'taxonomy general name', 'aqualuxe' ),
            'singular_name'     => _x( 'FAQ Category', 'taxonomy singular name', 'aqualuxe' ),
            'search_items'      => __( 'Search FAQ Categories', 'aqualuxe' ),
            'all_items'         => __( 'All FAQ Categories', 'aqualuxe' ),
            'parent_item'       => __( 'Parent FAQ Category', 'aqualuxe' ),
            'parent_item_colon' => __( 'Parent FAQ Category:', 'aqualuxe' ),
            'edit_item'         => __( 'Edit FAQ Category', 'aqualuxe' ),
            'update_item'       => __( 'Update FAQ Category', 'aqualuxe' ),
            'add_new_item'      => __( 'Add New FAQ Category', 'aqualuxe' ),
            'new_item_name'     => __( 'New FAQ Category Name', 'aqualuxe' ),
            'menu_name'         => __( 'FAQ Categories', 'aqualuxe' ),
        );

        $args = array(
            'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'faq-category' ),
            'show_in_rest'      => true,
        );

        register_taxonomy( 'aqualuxe_faq_category', array( 'aqualuxe_faq' ), $args );
    }

    /**
     * Register Gutenberg block
     */
    public function register_block() {
        // Check if Gutenberg is available
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }

        // Register block script
        wp_register_script(
            'aqualuxe-faq-accordion-block',
            plugin_dir_url( __FILE__ ) . 'assets/js/faq-accordion-block.js',
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components' ),
            '1.0.0',
            true
        );

        // Register block
        register_block_type( 'aqualuxe/faq-accordion', array(
            'editor_script' => 'aqualuxe-faq-accordion-block',
            'render_callback' => array( $this, 'render_faq_block' ),
            'attributes' => array(
                'title' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'category' => array(
                    'type' => 'string',
                    'default' => '',
                ),
                'limit' => array(
                    'type' => 'number',
                    'default' => -1,
                ),
                'orderby' => array(
                    'type' => 'string',
                    'default' => 'date',
                ),
                'order' => array(
                    'type' => 'string',
                    'default' => 'DESC',
                ),
                'style' => array(
                    'type' => 'string',
                    'default' => 'default',
                ),
                'className' => array(
                    'type' => 'string',
                    'default' => '',
                ),
            ),
        ) );
    }

    /**
     * Render FAQ block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_faq_block( $attributes ) {
        $shortcode_atts = array(
            'title'    => $attributes['title'],
            'category' => $attributes['category'],
            'limit'    => $attributes['limit'],
            'orderby'  => $attributes['orderby'],
            'order'    => $attributes['order'],
            'style'    => $attributes['style'],
            'class'    => $attributes['className'],
        );

        $shortcode = '[aqualuxe_faq';
        
        foreach ( $shortcode_atts as $key => $value ) {
            if ( ! empty( $value ) ) {
                $shortcode .= ' ' . $key . '="' . esc_attr( $value ) . '"';
            }
        }
        
        $shortcode .= ']';
        
        return do_shortcode( $shortcode );
    }

    /**
     * FAQ shortcode
     *
     * @param array  $atts    Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string Shortcode output.
     */
    public function faq_shortcode( $atts, $content = null ) {
        $atts = shortcode_atts(
            array(
                'title'    => '',
                'category' => '',
                'limit'    => -1,
                'orderby'  => 'date',
                'order'    => 'DESC',
                'style'    => 'default',
                'class'    => '',
            ),
            $atts,
            'aqualuxe_faq'
        );

        // Check if we're using nested shortcodes or querying FAQs
        if ( ! empty( $content ) ) {
            // Using nested shortcodes
            $faq_items = do_shortcode( $content );
        } else {
            // Query FAQs
            $args = array(
                'post_type'      => 'aqualuxe_faq',
                'posts_per_page' => intval( $atts['limit'] ),
                'orderby'        => $atts['orderby'],
                'order'          => $atts['order'],
            );

            // Add category if specified
            if ( ! empty( $atts['category'] ) ) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'aqualuxe_faq_category',
                        'field'    => 'slug',
                        'terms'    => explode( ',', $atts['category'] ),
                    ),
                );
            }

            $faqs = get_posts( $args );
            
            if ( empty( $faqs ) ) {
                return '<div class="aqualuxe-faq-accordion-empty">' . __( 'No FAQs found.', 'aqualuxe' ) . '</div>';
            }
            
            $faq_items = '';
            
            foreach ( $faqs as $faq ) {
                $faq_items .= $this->render_faq_item( $faq->post_title, $faq->post_content );
            }
        }

        // Generate a unique ID for this accordion
        $accordion_id = 'aqualuxe-faq-' . uniqid();
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-faq-accordion',
            'aqualuxe-faq-style-' . sanitize_html_class( $atts['style'] ),
        );
        
        if ( ! empty( $atts['class'] ) ) {
            $classes[] = sanitize_html_class( $atts['class'] );
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        $output = '<div id="' . esc_attr( $accordion_id ) . '" class="' . esc_attr( implode( ' ', $classes ) ) . '">';
        
        // Add title if specified
        if ( ! empty( $atts['title'] ) ) {
            $output .= '<h3 class="aqualuxe-faq-accordion-title">' . esc_html( $atts['title'] ) . '</h3>';
        }
        
        // Add schema markup if enabled
        if ( $this->settings['schema_markup'] ) {
            $output .= '<div itemscope itemtype="https://schema.org/FAQPage">';
            $output .= $faq_items;
            $output .= '</div>';
        } else {
            $output .= $faq_items;
        }
        
        $output .= '</div>';
        
        return $output;
    }

    /**
     * FAQ item shortcode
     *
     * @param array  $atts    Shortcode attributes.
     * @param string $content Shortcode content.
     * @return string Shortcode output.
     */
    public function faq_item_shortcode( $atts, $content = null ) {
        $atts = shortcode_atts(
            array(
                'question' => '',
                'open'     => '',
            ),
            $atts,
            'aqualuxe_faq_item'
        );

        return $this->render_faq_item( $atts['question'], $content, $atts['open'] === 'true' );
    }

    /**
     * Render FAQ item
     *
     * @param string $question Question text.
     * @param string $answer   Answer text.
     * @param bool   $open     Whether the item should be open by default.
     * @return string FAQ item HTML.
     */
    private function render_faq_item( $question, $answer, $open = false ) {
        // Generate a unique ID for this item
        $item_id = 'aqualuxe-faq-item-' . uniqid();
        
        // Check if this is the first item and should be open by default
        $is_open = $open || ( $this->settings['default_open_first'] && did_action( 'aqualuxe_faq_first_item' ) === 0 );
        
        // Mark that we've processed the first item
        if ( did_action( 'aqualuxe_faq_first_item' ) === 0 ) {
            do_action( 'aqualuxe_faq_first_item' );
        }
        
        $output = '';
        
        // Add schema markup if enabled
        if ( $this->settings['schema_markup'] ) {
            $output .= '<div itemscope itemprop="mainEntity" itemtype="https://schema.org/Question" class="aqualuxe-faq-item' . ( $is_open ? ' aqualuxe-faq-item-active' : '' ) . '">';
            $output .= '<h4 id="' . esc_attr( $item_id . '-question' ) . '" class="aqualuxe-faq-question" itemprop="name">';
            $output .= '<button aria-expanded="' . ( $is_open ? 'true' : 'false' ) . '" aria-controls="' . esc_attr( $item_id . '-answer' ) . '" class="aqualuxe-faq-toggle">';
            $output .= esc_html( $question );
            $output .= '<span class="aqualuxe-faq-icon"></span>';
            $output .= '</button>';
            $output .= '</h4>';
            $output .= '<div id="' . esc_attr( $item_id . '-answer' ) . '" class="aqualuxe-faq-answer" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer"' . ( $is_open ? '' : ' hidden' ) . '>';
            $output .= '<div itemprop="text">';
            $output .= wpautop( do_shortcode( $answer ) );
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        } else {
            $output .= '<div class="aqualuxe-faq-item' . ( $is_open ? ' aqualuxe-faq-item-active' : '' ) . '">';
            $output .= '<h4 id="' . esc_attr( $item_id . '-question' ) . '" class="aqualuxe-faq-question">';
            $output .= '<button aria-expanded="' . ( $is_open ? 'true' : 'false' ) . '" aria-controls="' . esc_attr( $item_id . '-answer' ) . '" class="aqualuxe-faq-toggle">';
            $output .= esc_html( $question );
            $output .= '<span class="aqualuxe-faq-icon"></span>';
            $output .= '</button>';
            $output .= '</h4>';
            $output .= '<div id="' . esc_attr( $item_id . '-answer' ) . '" class="aqualuxe-faq-answer"' . ( $is_open ? '' : ' hidden' ) . '>';
            $output .= wpautop( do_shortcode( $answer ) );
            $output .= '</div>';
            $output .= '</div>';
        }
        
        return $output;
    }

    /**
     * Add settings page
     */
    public function add_settings_page() {
        add_submenu_page(
            'options-general.php',
            __( 'FAQ Accordion Settings', 'aqualuxe' ),
            __( 'FAQ Accordion', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-faq-accordion',
            array( $this, 'render_settings_page' )
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'aqualuxe_faq_accordion_settings',
            'aqualuxe_faq_accordion_settings',
            array( $this, 'sanitize_settings' )
        );

        // General settings section
        add_settings_section(
            'aqualuxe_faq_accordion_general',
            __( 'General Settings', 'aqualuxe' ),
            array( $this, 'render_general_section' ),
            'aqualuxe-faq-accordion'
        );

        // Add settings fields
        add_settings_field(
            'schema_markup',
            __( 'Enable Schema Markup', 'aqualuxe' ),
            array( $this, 'render_schema_markup_field' ),
            'aqualuxe-faq-accordion',
            'aqualuxe_faq_accordion_general'
        );

        add_settings_field(
            'default_open_first',
            __( 'Open First Item by Default', 'aqualuxe' ),
            array( $this, 'render_default_open_first_field' ),
            'aqualuxe-faq-accordion',
            'aqualuxe_faq_accordion_general'
        );

        add_settings_field(
            'animation_speed',
            __( 'Animation Speed (ms)', 'aqualuxe' ),
            array( $this, 'render_animation_speed_field' ),
            'aqualuxe-faq-accordion',
            'aqualuxe_faq_accordion_general'
        );

        add_settings_field(
            'collapsible',
            __( 'Allow All Items to be Closed', 'aqualuxe' ),
            array( $this, 'render_collapsible_field' ),
            'aqualuxe-faq-accordion',
            'aqualuxe_faq_accordion_general'
        );

        add_settings_field(
            'allow_multiple',
            __( 'Allow Multiple Items Open', 'aqualuxe' ),
            array( $this, 'render_allow_multiple_field' ),
            'aqualuxe-faq-accordion',
            'aqualuxe_faq_accordion_general'
        );

        add_settings_field(
            'custom_css_class',
            __( 'Custom CSS Class', 'aqualuxe' ),
            array( $this, 'render_custom_css_class_field' ),
            'aqualuxe-faq-accordion',
            'aqualuxe_faq_accordion_general'
        );
    }

    /**
     * Sanitize settings
     *
     * @param array $input Settings input.
     * @return array Sanitized settings.
     */
    public function sanitize_settings( $input ) {
        $sanitized = array();

        // Sanitize schema markup
        $sanitized['schema_markup'] = isset( $input['schema_markup'] ) ? (bool) $input['schema_markup'] : false;

        // Sanitize default open first
        $sanitized['default_open_first'] = isset( $input['default_open_first'] ) ? (bool) $input['default_open_first'] : false;

        // Sanitize animation speed
        $sanitized['animation_speed'] = isset( $input['animation_speed'] ) ? absint( $input['animation_speed'] ) : 300;

        // Sanitize collapsible
        $sanitized['collapsible'] = isset( $input['collapsible'] ) ? (bool) $input['collapsible'] : true;

        // Sanitize allow multiple
        $sanitized['allow_multiple'] = isset( $input['allow_multiple'] ) ? (bool) $input['allow_multiple'] : false;

        // Sanitize custom CSS class
        $sanitized['custom_css_class'] = isset( $input['custom_css_class'] ) ? sanitize_html_class( $input['custom_css_class'] ) : '';

        return $sanitized;
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'aqualuxe_faq_accordion_settings' );
                do_settings_sections( 'aqualuxe-faq-accordion' );
                submit_button();
                ?>
            </form>
            
            <div class="aqualuxe-faq-accordion-usage">
                <h2><?php esc_html_e( 'Usage', 'aqualuxe' ); ?></h2>
                <h3><?php esc_html_e( 'Shortcode', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'You can use the following shortcode to display FAQs:', 'aqualuxe' ); ?></p>
                <pre>[aqualuxe_faq title="Frequently Asked Questions" category="general" limit="5" orderby="date" order="DESC" style="default"]</pre>
                
                <h3><?php esc_html_e( 'Nested Shortcodes', 'aqualuxe' ); ?></h3>
                <p><?php esc_html_e( 'You can also use nested shortcodes to create custom FAQ items:', 'aqualuxe' ); ?></p>
                <pre>[aqualuxe_faq title="Custom FAQs" style="boxed"]
    [aqualuxe_faq_item question="What is your question?" open="true"]
        This is the answer to your question.
    [/aqualuxe_faq_item]
    [aqualuxe_faq_item question="Another question?"]
        This is the answer to another question.
    [/aqualuxe_faq_item]
[/aqualuxe_faq]</pre>
                
                <h3><?php esc_html_e( 'Available Styles', 'aqualuxe' ); ?></h3>
                <ul>
                    <li><code>default</code> - <?php esc_html_e( 'Simple accordion style', 'aqualuxe' ); ?></li>
                    <li><code>boxed</code> - <?php esc_html_e( 'Boxed style with borders', 'aqualuxe' ); ?></li>
                    <li><code>toggle</code> - <?php esc_html_e( 'Toggle style with plus/minus icons', 'aqualuxe' ); ?></li>
                    <li><code>minimal</code> - <?php esc_html_e( 'Minimal style with subtle borders', 'aqualuxe' ); ?></li>
                </ul>
            </div>
        </div>
        <?php
    }

    /**
     * Render general section
     */
    public function render_general_section() {
        echo '<p>' . esc_html__( 'Configure the general settings for FAQ accordions.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render schema markup field
     */
    public function render_schema_markup_field() {
        $schema_markup = isset( $this->settings['schema_markup'] ) ? $this->settings['schema_markup'] : true;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_faq_accordion_settings[schema_markup]" value="1" <?php checked( $schema_markup ); ?> />
            <?php esc_html_e( 'Add schema.org FAQ markup for better SEO', 'aqualuxe' ); ?>
        </label>
        <p class="description"><?php esc_html_e( 'This adds structured data that may help with rich results in search engines.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render default open first field
     */
    public function render_default_open_first_field() {
        $default_open_first = isset( $this->settings['default_open_first'] ) ? $this->settings['default_open_first'] : true;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_faq_accordion_settings[default_open_first]" value="1" <?php checked( $default_open_first ); ?> />
            <?php esc_html_e( 'Open the first FAQ item by default', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render animation speed field
     */
    public function render_animation_speed_field() {
        $animation_speed = isset( $this->settings['animation_speed'] ) ? absint( $this->settings['animation_speed'] ) : 300;
        ?>
        <input type="number" name="aqualuxe_faq_accordion_settings[animation_speed]" value="<?php echo esc_attr( $animation_speed ); ?>" min="0" max="1000" step="50" />
        <p class="description"><?php esc_html_e( 'Speed of the open/close animation in milliseconds.', 'aqualuxe' ); ?></p>
        <?php
    }

    /**
     * Render collapsible field
     */
    public function render_collapsible_field() {
        $collapsible = isset( $this->settings['collapsible'] ) ? $this->settings['collapsible'] : true;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_faq_accordion_settings[collapsible]" value="1" <?php checked( $collapsible ); ?> />
            <?php esc_html_e( 'Allow all items to be closed (otherwise one will always be open)', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render allow multiple field
     */
    public function render_allow_multiple_field() {
        $allow_multiple = isset( $this->settings['allow_multiple'] ) ? $this->settings['allow_multiple'] : false;
        ?>
        <label>
            <input type="checkbox" name="aqualuxe_faq_accordion_settings[allow_multiple]" value="1" <?php checked( $allow_multiple ); ?> />
            <?php esc_html_e( 'Allow multiple items to be open simultaneously', 'aqualuxe' ); ?>
        </label>
        <?php
    }

    /**
     * Render custom CSS class field
     */
    public function render_custom_css_class_field() {
        $custom_css_class = isset( $this->settings['custom_css_class'] ) ? $this->settings['custom_css_class'] : '';
        ?>
        <input type="text" name="aqualuxe_faq_accordion_settings[custom_css_class]" value="<?php echo esc_attr( $custom_css_class ); ?>" class="regular-text" />
        <p class="description"><?php esc_html_e( 'Add a custom CSS class to all FAQ accordions.', 'aqualuxe' ); ?></p>
        <?php
    }
}

// Initialize the module
new AquaLuxe_FAQ_Accordion();