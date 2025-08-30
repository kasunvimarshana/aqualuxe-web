<?php
/**
 * AquaLuxe Homepage Options
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Homepage Options Class
 * 
 * Handles the creation and management of all homepage options
 */
class AquaLuxe_Homepage_Options {

	/**
	 * Instance
	 * 
	 * @var AquaLuxe_Homepage_Options The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Options group name
	 * 
	 * @var string
	 */
	private $option_group = 'aqualuxe_options_homepage';

	/**
	 * Options name
	 * 
	 * @var string
	 */
	private $option_name = 'aqualuxe_homepage_options';

	/**
	 * Options array
	 * 
	 * @var array
	 */
	private $options = array();

	/**
	 * Instance
	 * 
	 * Ensures only one instance of the class is loaded or can be loaded.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		// Load options
		$this->options = get_option( $this->option_name, array() );

		// Initialize
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Register settings
	 */
	public function register_settings() {
		register_setting(
			$this->option_group,
			$this->option_name,
			array( $this, 'sanitize_options' )
		);

		// Hero Section
		add_settings_section(
			'aqualuxe_homepage_hero_section',
			__( 'Hero Section', 'aqualuxe' ),
			array( $this, 'render_hero_section' ),
			'aqualuxe-options-homepage'
		);

		add_settings_field(
			'hero_enabled',
			__( 'Enable Hero Section', 'aqualuxe' ),
			array( $this, 'render_hero_enabled_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_hero_section'
		);

		add_settings_field(
			'hero_title',
			__( 'Hero Title', 'aqualuxe' ),
			array( $this, 'render_hero_title_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_hero_section'
		);

		add_settings_field(
			'hero_subtitle',
			__( 'Hero Subtitle', 'aqualuxe' ),
			array( $this, 'render_hero_subtitle_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_hero_section'
		);

		add_settings_field(
			'hero_image',
			__( 'Hero Background Image', 'aqualuxe' ),
			array( $this, 'render_hero_image_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_hero_section'
		);

		add_settings_field(
			'hero_button_text',
			__( 'Hero Button Text', 'aqualuxe' ),
			array( $this, 'render_hero_button_text_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_hero_section'
		);

		add_settings_field(
			'hero_button_url',
			__( 'Hero Button URL', 'aqualuxe' ),
			array( $this, 'render_hero_button_url_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_hero_section'
		);

		// Featured Products Section
		add_settings_section(
			'aqualuxe_homepage_featured_products_section',
			__( 'Featured Products Section', 'aqualuxe' ),
			array( $this, 'render_featured_products_section' ),
			'aqualuxe-options-homepage'
		);

		add_settings_field(
			'featured_products_enabled',
			__( 'Enable Featured Products Section', 'aqualuxe' ),
			array( $this, 'render_featured_products_enabled_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_featured_products_section'
		);

		add_settings_field(
			'featured_products_title',
			__( 'Section Title', 'aqualuxe' ),
			array( $this, 'render_featured_products_title_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_featured_products_section'
		);

		add_settings_field(
			'featured_products_subtitle',
			__( 'Section Subtitle', 'aqualuxe' ),
			array( $this, 'render_featured_products_subtitle_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_featured_products_section'
		);

		add_settings_field(
			'featured_products_count',
			__( 'Number of Products to Display', 'aqualuxe' ),
			array( $this, 'render_featured_products_count_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_featured_products_section'
		);

		add_settings_field(
			'featured_products_selection',
			__( 'Product Selection Method', 'aqualuxe' ),
			array( $this, 'render_featured_products_selection_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_featured_products_section'
		);

		add_settings_field(
			'featured_products_ids',
			__( 'Specific Products', 'aqualuxe' ),
			array( $this, 'render_featured_products_ids_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_featured_products_section'
		);

		add_settings_field(
			'featured_products_button_text',
			__( 'Button Text', 'aqualuxe' ),
			array( $this, 'render_featured_products_button_text_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_featured_products_section'
		);

		add_settings_field(
			'featured_products_button_url',
			__( 'Button URL', 'aqualuxe' ),
			array( $this, 'render_featured_products_button_url_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_featured_products_section'
		);

		// Fish Species Section
		add_settings_section(
			'aqualuxe_homepage_fish_species_section',
			__( 'Fish Species Section', 'aqualuxe' ),
			array( $this, 'render_fish_species_section' ),
			'aqualuxe-options-homepage'
		);

		add_settings_field(
			'fish_species_enabled',
			__( 'Enable Fish Species Section', 'aqualuxe' ),
			array( $this, 'render_fish_species_enabled_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_fish_species_section'
		);

		add_settings_field(
			'fish_species_title',
			__( 'Section Title', 'aqualuxe' ),
			array( $this, 'render_fish_species_title_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_fish_species_section'
		);

		add_settings_field(
			'fish_species_subtitle',
			__( 'Section Subtitle', 'aqualuxe' ),
			array( $this, 'render_fish_species_subtitle_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_fish_species_section'
		);

		add_settings_field(
			'fish_species_count',
			__( 'Number of Species to Display', 'aqualuxe' ),
			array( $this, 'render_fish_species_count_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_fish_species_section'
		);

		add_settings_field(
			'fish_species_selection',
			__( 'Species Selection Method', 'aqualuxe' ),
			array( $this, 'render_fish_species_selection_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_fish_species_section'
		);

		add_settings_field(
			'fish_species_ids',
			__( 'Specific Species', 'aqualuxe' ),
			array( $this, 'render_fish_species_ids_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_fish_species_section'
		);

		add_settings_field(
			'fish_species_button_text',
			__( 'Button Text', 'aqualuxe' ),
			array( $this, 'render_fish_species_button_text_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_fish_species_section'
		);

		add_settings_field(
			'fish_species_button_url',
			__( 'Button URL', 'aqualuxe' ),
			array( $this, 'render_fish_species_button_url_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_fish_species_section'
		);

		// Testimonials Section
		add_settings_section(
			'aqualuxe_homepage_testimonials_section',
			__( 'Testimonials Section', 'aqualuxe' ),
			array( $this, 'render_testimonials_section' ),
			'aqualuxe-options-homepage'
		);

		add_settings_field(
			'testimonials_enabled',
			__( 'Enable Testimonials Section', 'aqualuxe' ),
			array( $this, 'render_testimonials_enabled_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_testimonials_section'
		);

		add_settings_field(
			'testimonials_title',
			__( 'Section Title', 'aqualuxe' ),
			array( $this, 'render_testimonials_title_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_testimonials_section'
		);

		add_settings_field(
			'testimonials_subtitle',
			__( 'Section Subtitle', 'aqualuxe' ),
			array( $this, 'render_testimonials_subtitle_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_testimonials_section'
		);

		add_settings_field(
			'testimonials_items',
			__( 'Testimonials', 'aqualuxe' ),
			array( $this, 'render_testimonials_items_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_testimonials_section'
		);

		// Blog Section
		add_settings_section(
			'aqualuxe_homepage_blog_section',
			__( 'Blog Section', 'aqualuxe' ),
			array( $this, 'render_blog_section' ),
			'aqualuxe-options-homepage'
		);

		add_settings_field(
			'blog_enabled',
			__( 'Enable Blog Section', 'aqualuxe' ),
			array( $this, 'render_blog_enabled_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_blog_section'
		);

		add_settings_field(
			'blog_title',
			__( 'Section Title', 'aqualuxe' ),
			array( $this, 'render_blog_title_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_blog_section'
		);

		add_settings_field(
			'blog_subtitle',
			__( 'Section Subtitle', 'aqualuxe' ),
			array( $this, 'render_blog_subtitle_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_blog_section'
		);

		add_settings_field(
			'blog_count',
			__( 'Number of Posts to Display', 'aqualuxe' ),
			array( $this, 'render_blog_count_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_blog_section'
		);

		add_settings_field(
			'blog_category',
			__( 'Post Category', 'aqualuxe' ),
			array( $this, 'render_blog_category_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_blog_section'
		);

		add_settings_field(
			'blog_button_text',
			__( 'Button Text', 'aqualuxe' ),
			array( $this, 'render_blog_button_text_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_blog_section'
		);

		add_settings_field(
			'blog_button_url',
			__( 'Button URL', 'aqualuxe' ),
			array( $this, 'render_blog_button_url_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_blog_section'
		);

		// CTA Section
		add_settings_section(
			'aqualuxe_homepage_cta_section',
			__( 'Call to Action Section', 'aqualuxe' ),
			array( $this, 'render_cta_section' ),
			'aqualuxe-options-homepage'
		);

		add_settings_field(
			'cta_enabled',
			__( 'Enable CTA Section', 'aqualuxe' ),
			array( $this, 'render_cta_enabled_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_cta_section'
		);

		add_settings_field(
			'cta_title',
			__( 'CTA Title', 'aqualuxe' ),
			array( $this, 'render_cta_title_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_cta_section'
		);

		add_settings_field(
			'cta_text',
			__( 'CTA Text', 'aqualuxe' ),
			array( $this, 'render_cta_text_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_cta_section'
		);

		add_settings_field(
			'cta_button_text',
			__( 'Button Text', 'aqualuxe' ),
			array( $this, 'render_cta_button_text_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_cta_section'
		);

		add_settings_field(
			'cta_button_url',
			__( 'Button URL', 'aqualuxe' ),
			array( $this, 'render_cta_button_url_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_cta_section'
		);

		add_settings_field(
			'cta_background',
			__( 'Background Image', 'aqualuxe' ),
			array( $this, 'render_cta_background_field' ),
			'aqualuxe-options-homepage',
			'aqualuxe_homepage_cta_section'
		);
	}

	/**
	 * Sanitize options
	 * 
	 * @param array $input The input options.
	 * @return array
	 */
	public function sanitize_options( $input ) {
		$output = array();

		// Hero Section
		if ( isset( $input['hero_enabled'] ) ) {
			$output['hero_enabled'] = (bool) $input['hero_enabled'];
		}

		if ( isset( $input['hero_title'] ) ) {
			$output['hero_title'] = sanitize_text_field( $input['hero_title'] );
		}

		if ( isset( $input['hero_subtitle'] ) ) {
			$output['hero_subtitle'] = sanitize_text_field( $input['hero_subtitle'] );
		}

		if ( isset( $input['hero_image'] ) ) {
			$output['hero_image'] = esc_url_raw( $input['hero_image'] );
		}

		if ( isset( $input['hero_button_text'] ) ) {
			$output['hero_button_text'] = sanitize_text_field( $input['hero_button_text'] );
		}

		if ( isset( $input['hero_button_url'] ) ) {
			$output['hero_button_url'] = esc_url_raw( $input['hero_button_url'] );
		}

		// Featured Products Section
		if ( isset( $input['featured_products_enabled'] ) ) {
			$output['featured_products_enabled'] = (bool) $input['featured_products_enabled'];
		}

		if ( isset( $input['featured_products_title'] ) ) {
			$output['featured_products_title'] = sanitize_text_field( $input['featured_products_title'] );
		}

		if ( isset( $input['featured_products_subtitle'] ) ) {
			$output['featured_products_subtitle'] = sanitize_text_field( $input['featured_products_subtitle'] );
		}

		if ( isset( $input['featured_products_count'] ) ) {
			$output['featured_products_count'] = absint( $input['featured_products_count'] );
		}

		if ( isset( $input['featured_products_selection'] ) ) {
			$output['featured_products_selection'] = sanitize_text_field( $input['featured_products_selection'] );
		}

		if ( isset( $input['featured_products_ids'] ) && is_array( $input['featured_products_ids'] ) ) {
			$output['featured_products_ids'] = array_map( 'absint', $input['featured_products_ids'] );
		}

		if ( isset( $input['featured_products_button_text'] ) ) {
			$output['featured_products_button_text'] = sanitize_text_field( $input['featured_products_button_text'] );
		}

		if ( isset( $input['featured_products_button_url'] ) ) {
			$output['featured_products_button_url'] = esc_url_raw( $input['featured_products_button_url'] );
		}

		// Fish Species Section
		if ( isset( $input['fish_species_enabled'] ) ) {
			$output['fish_species_enabled'] = (bool) $input['fish_species_enabled'];
		}

		if ( isset( $input['fish_species_title'] ) ) {
			$output['fish_species_title'] = sanitize_text_field( $input['fish_species_title'] );
		}

		if ( isset( $input['fish_species_subtitle'] ) ) {
			$output['fish_species_subtitle'] = sanitize_text_field( $input['fish_species_subtitle'] );
		}

		if ( isset( $input['fish_species_count'] ) ) {
			$output['fish_species_count'] = absint( $input['fish_species_count'] );
		}

		if ( isset( $input['fish_species_selection'] ) ) {
			$output['fish_species_selection'] = sanitize_text_field( $input['fish_species_selection'] );
		}

		if ( isset( $input['fish_species_ids'] ) && is_array( $input['fish_species_ids'] ) ) {
			$output['fish_species_ids'] = array_map( 'absint', $input['fish_species_ids'] );
		}

		if ( isset( $input['fish_species_button_text'] ) ) {
			$output['fish_species_button_text'] = sanitize_text_field( $input['fish_species_button_text'] );
		}

		if ( isset( $input['fish_species_button_url'] ) ) {
			$output['fish_species_button_url'] = esc_url_raw( $input['fish_species_button_url'] );
		}

		// Testimonials Section
		if ( isset( $input['testimonials_enabled'] ) ) {
			$output['testimonials_enabled'] = (bool) $input['testimonials_enabled'];
		}

		if ( isset( $input['testimonials_title'] ) ) {
			$output['testimonials_title'] = sanitize_text_field( $input['testimonials_title'] );
		}

		if ( isset( $input['testimonials_subtitle'] ) ) {
			$output['testimonials_subtitle'] = sanitize_text_field( $input['testimonials_subtitle'] );
		}

		if ( isset( $input['testimonials_items'] ) && is_array( $input['testimonials_items'] ) ) {
			foreach ( $input['testimonials_items'] as $key => $testimonial ) {
				$output['testimonials_items'][$key]['name'] = sanitize_text_field( $testimonial['name'] );
				$output['testimonials_items'][$key]['position'] = sanitize_text_field( $testimonial['position'] );
				$output['testimonials_items'][$key]['content'] = wp_kses_post( $testimonial['content'] );
				$output['testimonials_items'][$key]['image'] = esc_url_raw( $testimonial['image'] );
			}
		}

		// Blog Section
		if ( isset( $input['blog_enabled'] ) ) {
			$output['blog_enabled'] = (bool) $input['blog_enabled'];
		}

		if ( isset( $input['blog_title'] ) ) {
			$output['blog_title'] = sanitize_text_field( $input['blog_title'] );
		}

		if ( isset( $input['blog_subtitle'] ) ) {
			$output['blog_subtitle'] = sanitize_text_field( $input['blog_subtitle'] );
		}

		if ( isset( $input['blog_count'] ) ) {
			$output['blog_count'] = absint( $input['blog_count'] );
		}

		if ( isset( $input['blog_category'] ) ) {
			$output['blog_category'] = absint( $input['blog_category'] );
		}

		if ( isset( $input['blog_button_text'] ) ) {
			$output['blog_button_text'] = sanitize_text_field( $input['blog_button_text'] );
		}

		if ( isset( $input['blog_button_url'] ) ) {
			$output['blog_button_url'] = esc_url_raw( $input['blog_button_url'] );
		}

		// CTA Section
		if ( isset( $input['cta_enabled'] ) ) {
			$output['cta_enabled'] = (bool) $input['cta_enabled'];
		}

		if ( isset( $input['cta_title'] ) ) {
			$output['cta_title'] = sanitize_text_field( $input['cta_title'] );
		}

		if ( isset( $input['cta_text'] ) ) {
			$output['cta_text'] = wp_kses_post( $input['cta_text'] );
		}

		if ( isset( $input['cta_button_text'] ) ) {
			$output['cta_button_text'] = sanitize_text_field( $input['cta_button_text'] );
		}

		if ( isset( $input['cta_button_url'] ) ) {
			$output['cta_button_url'] = esc_url_raw( $input['cta_button_url'] );
		}

		if ( isset( $input['cta_background'] ) ) {
			$output['cta_background'] = esc_url_raw( $input['cta_background'] );
		}

		return $output;
	}

	/**
	 * Render hero section
	 */
	public function render_hero_section() {
		echo '<p>' . esc_html__( 'Configure the hero section that appears at the top of the homepage.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render hero enabled field
	 */
	public function render_hero_enabled_field() {
		$hero_enabled = isset( $this->options['hero_enabled'] ) ? $this->options['hero_enabled'] : true;
		?>
		<label for="hero_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[hero_enabled]" id="hero_enabled" value="1" <?php checked( $hero_enabled ); ?> class="aqualuxe-field-toggle" data-target="hero_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable hero section on homepage', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render hero title field
	 */
	public function render_hero_title_field() {
		$hero_title = isset( $this->options['hero_title'] ) ? $this->options['hero_title'] : __( 'Welcome to AquaLuxe', 'aqualuxe' );
		?>
		<div id="hero_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[hero_title]" id="hero_title" value="<?php echo esc_attr( $hero_title ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the main title for the hero section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render hero subtitle field
	 */
	public function render_hero_subtitle_field() {
		$hero_subtitle = isset( $this->options['hero_subtitle'] ) ? $this->options['hero_subtitle'] : __( 'Premium Ornamental Fish for Your Aquarium', 'aqualuxe' );
		?>
		<div id="hero_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[hero_subtitle]" id="hero_subtitle" value="<?php echo esc_attr( $hero_subtitle ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the subtitle for the hero section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render hero image field
	 */
	public function render_hero_image_field() {
		$hero_image = isset( $this->options['hero_image'] ) ? $this->options['hero_image'] : '';
		?>
		<div id="hero_settings" class="aqualuxe-media-field">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[hero_image]" id="hero_image" value="<?php echo esc_url( $hero_image ); ?>" class="regular-text" />
			<button type="button" class="button aqualuxe-media-upload"><?php esc_html_e( 'Select Image', 'aqualuxe' ); ?></button>
			<button type="button" class="button aqualuxe-media-remove" <?php echo empty( $hero_image ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Remove Image', 'aqualuxe' ); ?></button>
			
			<div class="aqualuxe-media-preview">
				<?php if ( ! empty( $hero_image ) ) : ?>
					<img src="<?php echo esc_url( $hero_image ); ?>" alt="" />
				<?php endif; ?>
			</div>
			<p class="description"><?php esc_html_e( 'Select the background image for the hero section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render hero button text field
	 */
	public function render_hero_button_text_field() {
		$hero_button_text = isset( $this->options['hero_button_text'] ) ? $this->options['hero_button_text'] : __( 'Shop Now', 'aqualuxe' );
		?>
		<div id="hero_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[hero_button_text]" id="hero_button_text" value="<?php echo esc_attr( $hero_button_text ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the text for the hero button.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render hero button URL field
	 */
	public function render_hero_button_url_field() {
		$hero_button_url = isset( $this->options['hero_button_url'] ) ? $this->options['hero_button_url'] : home_url( '/shop/' );
		?>
		<div id="hero_settings">
			<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[hero_button_url]" id="hero_button_url" value="<?php echo esc_url( $hero_button_url ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the URL for the hero button.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render featured products section
	 */
	public function render_featured_products_section() {
		echo '<p>' . esc_html__( 'Configure the featured products section on the homepage.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render featured products enabled field
	 */
	public function render_featured_products_enabled_field() {
		$featured_products_enabled = isset( $this->options['featured_products_enabled'] ) ? $this->options['featured_products_enabled'] : true;
		?>
		<label for="featured_products_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[featured_products_enabled]" id="featured_products_enabled" value="1" <?php checked( $featured_products_enabled ); ?> class="aqualuxe-field-toggle" data-target="featured_products_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable featured products section on homepage', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render featured products title field
	 */
	public function render_featured_products_title_field() {
		$featured_products_title = isset( $this->options['featured_products_title'] ) ? $this->options['featured_products_title'] : __( 'Featured Products', 'aqualuxe' );
		?>
		<div id="featured_products_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[featured_products_title]" id="featured_products_title" value="<?php echo esc_attr( $featured_products_title ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the title for the featured products section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render featured products subtitle field
	 */
	public function render_featured_products_subtitle_field() {
		$featured_products_subtitle = isset( $this->options['featured_products_subtitle'] ) ? $this->options['featured_products_subtitle'] : __( 'Our most popular aquarium products', 'aqualuxe' );
		?>
		<div id="featured_products_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[featured_products_subtitle]" id="featured_products_subtitle" value="<?php echo esc_attr( $featured_products_subtitle ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the subtitle for the featured products section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render featured products count field
	 */
	public function render_featured_products_count_field() {
		$featured_products_count = isset( $this->options['featured_products_count'] ) ? $this->options['featured_products_count'] : 4;
		?>
		<div id="featured_products_settings">
			<input type="number" name="<?php echo esc_attr( $this->option_name ); ?>[featured_products_count]" id="featured_products_count" value="<?php echo esc_attr( $featured_products_count ); ?>" min="1" max="12" step="1" />
			<p class="description"><?php esc_html_e( 'Select the number of products to display.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render featured products selection field
	 */
	public function render_featured_products_selection_field() {
		$featured_products_selection = isset( $this->options['featured_products_selection'] ) ? $this->options['featured_products_selection'] : 'featured';
		?>
		<div id="featured_products_settings">
			<select name="<?php echo esc_attr( $this->option_name ); ?>[featured_products_selection]" id="featured_products_selection" class="aqualuxe-field-toggle" data-target="featured_products_ids_field" data-show-on="specific">
				<option value="featured" <?php selected( $featured_products_selection, 'featured' ); ?>><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></option>
				<option value="best_selling" <?php selected( $featured_products_selection, 'best_selling' ); ?>><?php esc_html_e( 'Best Selling Products', 'aqualuxe' ); ?></option>
				<option value="newest" <?php selected( $featured_products_selection, 'newest' ); ?>><?php esc_html_e( 'Newest Products', 'aqualuxe' ); ?></option>
				<option value="sale" <?php selected( $featured_products_selection, 'sale' ); ?>><?php esc_html_e( 'On Sale Products', 'aqualuxe' ); ?></option>
				<option value="specific" <?php selected( $featured_products_selection, 'specific' ); ?>><?php esc_html_e( 'Specific Products', 'aqualuxe' ); ?></option>
			</select>
			<p class="description"><?php esc_html_e( 'Select how to choose the featured products.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render featured products IDs field
	 */
	public function render_featured_products_ids_field() {
		$featured_products_ids = isset( $this->options['featured_products_ids'] ) ? $this->options['featured_products_ids'] : array();
		?>
		<div id="featured_products_ids_field" style="<?php echo ( isset( $this->options['featured_products_selection'] ) && $this->options['featured_products_selection'] === 'specific' ) ? '' : 'display: none;'; ?>">
			<select name="<?php echo esc_attr( $this->option_name ); ?>[featured_products_ids][]" id="featured_products_ids" multiple="multiple" class="regular-text">
				<?php
				// This would normally query WooCommerce products
				// For demonstration, we'll use placeholder values
				$sample_products = array(
					1 => 'Blue Neon Tetra',
					2 => 'Red Crystal Shrimp',
					3 => 'Platinum Angelfish',
					4 => 'Fancy Guppy',
					5 => 'Betta Fish',
					6 => 'Discus Fish',
					7 => 'Corydoras Catfish',
					8 => 'Aquarium Plant Bundle',
					9 => 'Premium Fish Food',
					10 => 'Water Conditioner',
				);

				foreach ( $sample_products as $id => $name ) {
					echo '<option value="' . esc_attr( $id ) . '" ' . ( in_array( $id, $featured_products_ids ) ? 'selected="selected"' : '' ) . '>' . esc_html( $name ) . '</option>';
				}
				?>
			</select>
			<p class="description"><?php esc_html_e( 'Select specific products to display.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render featured products button text field
	 */
	public function render_featured_products_button_text_field() {
		$featured_products_button_text = isset( $this->options['featured_products_button_text'] ) ? $this->options['featured_products_button_text'] : __( 'View All Products', 'aqualuxe' );
		?>
		<div id="featured_products_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[featured_products_button_text]" id="featured_products_button_text" value="<?php echo esc_attr( $featured_products_button_text ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the text for the button.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render featured products button URL field
	 */
	public function render_featured_products_button_url_field() {
		$featured_products_button_url = isset( $this->options['featured_products_button_url'] ) ? $this->options['featured_products_button_url'] : home_url( '/shop/' );
		?>
		<div id="featured_products_settings">
			<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[featured_products_button_url]" id="featured_products_button_url" value="<?php echo esc_url( $featured_products_button_url ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the URL for the button.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render fish species section
	 */
	public function render_fish_species_section() {
		echo '<p>' . esc_html__( 'Configure the fish species showcase section on the homepage.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render fish species enabled field
	 */
	public function render_fish_species_enabled_field() {
		$fish_species_enabled = isset( $this->options['fish_species_enabled'] ) ? $this->options['fish_species_enabled'] : true;
		?>
		<label for="fish_species_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[fish_species_enabled]" id="fish_species_enabled" value="1" <?php checked( $fish_species_enabled ); ?> class="aqualuxe-field-toggle" data-target="fish_species_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable fish species section on homepage', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render fish species title field
	 */
	public function render_fish_species_title_field() {
		$fish_species_title = isset( $this->options['fish_species_title'] ) ? $this->options['fish_species_title'] : __( 'Explore Our Fish Species', 'aqualuxe' );
		?>
		<div id="fish_species_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[fish_species_title]" id="fish_species_title" value="<?php echo esc_attr( $fish_species_title ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the title for the fish species section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render fish species subtitle field
	 */
	public function render_fish_species_subtitle_field() {
		$fish_species_subtitle = isset( $this->options['fish_species_subtitle'] ) ? $this->options['fish_species_subtitle'] : __( 'Discover our premium ornamental fish collection', 'aqualuxe' );
		?>
		<div id="fish_species_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[fish_species_subtitle]" id="fish_species_subtitle" value="<?php echo esc_attr( $fish_species_subtitle ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the subtitle for the fish species section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render fish species count field
	 */
	public function render_fish_species_count_field() {
		$fish_species_count = isset( $this->options['fish_species_count'] ) ? $this->options['fish_species_count'] : 3;
		?>
		<div id="fish_species_settings">
			<input type="number" name="<?php echo esc_attr( $this->option_name ); ?>[fish_species_count]" id="fish_species_count" value="<?php echo esc_attr( $fish_species_count ); ?>" min="1" max="12" step="1" />
			<p class="description"><?php esc_html_e( 'Select the number of fish species to display.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render fish species selection field
	 */
	public function render_fish_species_selection_field() {
		$fish_species_selection = isset( $this->options['fish_species_selection'] ) ? $this->options['fish_species_selection'] : 'featured';
		?>
		<div id="fish_species_settings">
			<select name="<?php echo esc_attr( $this->option_name ); ?>[fish_species_selection]" id="fish_species_selection" class="aqualuxe-field-toggle" data-target="fish_species_ids_field" data-show-on="specific">
				<option value="featured" <?php selected( $fish_species_selection, 'featured' ); ?>><?php esc_html_e( 'Featured Species', 'aqualuxe' ); ?></option>
				<option value="popular" <?php selected( $fish_species_selection, 'popular' ); ?>><?php esc_html_e( 'Popular Species', 'aqualuxe' ); ?></option>
				<option value="newest" <?php selected( $fish_species_selection, 'newest' ); ?>><?php esc_html_e( 'Newest Species', 'aqualuxe' ); ?></option>
				<option value="specific" <?php selected( $fish_species_selection, 'specific' ); ?>><?php esc_html_e( 'Specific Species', 'aqualuxe' ); ?></option>
			</select>
			<p class="description"><?php esc_html_e( 'Select how to choose the fish species.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render fish species IDs field
	 */
	public function render_fish_species_ids_field() {
		$fish_species_ids = isset( $this->options['fish_species_ids'] ) ? $this->options['fish_species_ids'] : array();
		?>
		<div id="fish_species_ids_field" style="<?php echo ( isset( $this->options['fish_species_selection'] ) && $this->options['fish_species_selection'] === 'specific' ) ? '' : 'display: none;'; ?>">
			<select name="<?php echo esc_attr( $this->option_name ); ?>[fish_species_ids][]" id="fish_species_ids" multiple="multiple" class="regular-text">
				<?php
				// This would normally query fish species custom post type
				// For demonstration, we'll use placeholder values
				$sample_species = array(
					1 => 'Betta Fish',
					2 => 'Guppy',
					3 => 'Angelfish',
					4 => 'Discus',
					5 => 'Neon Tetra',
					6 => 'Goldfish',
					7 => 'Koi',
					8 => 'Plecostomus',
					9 => 'Corydoras',
					10 => 'Rainbowfish',
				);

				foreach ( $sample_species as $id => $name ) {
					echo '<option value="' . esc_attr( $id ) . '" ' . ( in_array( $id, $fish_species_ids ) ? 'selected="selected"' : '' ) . '>' . esc_html( $name ) . '</option>';
				}
				?>
			</select>
			<p class="description"><?php esc_html_e( 'Select specific fish species to display.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render fish species button text field
	 */
	public function render_fish_species_button_text_field() {
		$fish_species_button_text = isset( $this->options['fish_species_button_text'] ) ? $this->options['fish_species_button_text'] : __( 'View All Species', 'aqualuxe' );
		?>
		<div id="fish_species_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[fish_species_button_text]" id="fish_species_button_text" value="<?php echo esc_attr( $fish_species_button_text ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the text for the button.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render fish species button URL field
	 */
	public function render_fish_species_button_url_field() {
		$fish_species_button_url = isset( $this->options['fish_species_button_url'] ) ? $this->options['fish_species_button_url'] : home_url( '/fish-species/' );
		?>
		<div id="fish_species_settings">
			<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[fish_species_button_url]" id="fish_species_button_url" value="<?php echo esc_url( $fish_species_button_url ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the URL for the button.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render testimonials section
	 */
	public function render_testimonials_section() {
		echo '<p>' . esc_html__( 'Configure the testimonials section on the homepage.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render testimonials enabled field
	 */
	public function render_testimonials_enabled_field() {
		$testimonials_enabled = isset( $this->options['testimonials_enabled'] ) ? $this->options['testimonials_enabled'] : true;
		?>
		<label for="testimonials_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_enabled]" id="testimonials_enabled" value="1" <?php checked( $testimonials_enabled ); ?> class="aqualuxe-field-toggle" data-target="testimonials_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable testimonials section on homepage', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render testimonials title field
	 */
	public function render_testimonials_title_field() {
		$testimonials_title = isset( $this->options['testimonials_title'] ) ? $this->options['testimonials_title'] : __( 'What Our Customers Say', 'aqualuxe' );
		?>
		<div id="testimonials_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_title]" id="testimonials_title" value="<?php echo esc_attr( $testimonials_title ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the title for the testimonials section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render testimonials subtitle field
	 */
	public function render_testimonials_subtitle_field() {
		$testimonials_subtitle = isset( $this->options['testimonials_subtitle'] ) ? $this->options['testimonials_subtitle'] : __( 'Hear from our satisfied customers', 'aqualuxe' );
		?>
		<div id="testimonials_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_subtitle]" id="testimonials_subtitle" value="<?php echo esc_attr( $testimonials_subtitle ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the subtitle for the testimonials section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render testimonials items field
	 */
	public function render_testimonials_items_field() {
		$testimonials_items = isset( $this->options['testimonials_items'] ) ? $this->options['testimonials_items'] : array(
			array(
				'name' => 'John Doe',
				'position' => 'Aquarium Enthusiast',
				'content' => 'The fish I received from AquaLuxe were healthy and vibrant. The packaging was excellent and the fish arrived in perfect condition.',
				'image' => '',
			),
			array(
				'name' => 'Jane Smith',
				'position' => 'Professional Breeder',
				'content' => 'I\'ve been buying from AquaLuxe for years. Their fish are always top quality and their customer service is outstanding.',
				'image' => '',
			),
			array(
				'name' => 'Mike Johnson',
				'position' => 'Aquarium Shop Owner',
				'content' => 'As a shop owner, I need reliable suppliers. AquaLuxe has never disappointed me with their quality and consistency.',
				'image' => '',
			),
		);
		?>
		<div id="testimonials_settings">
			<div class="aqualuxe-repeater-container" data-name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_items]" data-max="10">
				<?php foreach ( $testimonials_items as $index => $testimonial ) : ?>
					<div class="aqualuxe-repeater-item">
						<div class="aqualuxe-repeater-header">
							<span class="aqualuxe-repeater-title"><?php echo esc_html( $testimonial['name'] ); ?></span>
							<div class="aqualuxe-repeater-actions">
								<a href="#" class="aqualuxe-repeater-toggle"><?php esc_html_e( 'Edit', 'aqualuxe' ); ?></a>
								<a href="#" class="aqualuxe-repeater-remove"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></a>
							</div>
						</div>
						<div class="aqualuxe-repeater-content">
							<p>
								<label for="testimonials_items_<?php echo esc_attr( $index ); ?>_name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?></label>
								<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_items][<?php echo esc_attr( $index ); ?>][name]" id="testimonials_items_<?php echo esc_attr( $index ); ?>_name" value="<?php echo esc_attr( $testimonial['name'] ); ?>" class="regular-text" />
							</p>
							<p>
								<label for="testimonials_items_<?php echo esc_attr( $index ); ?>_position"><?php esc_html_e( 'Position', 'aqualuxe' ); ?></label>
								<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_items][<?php echo esc_attr( $index ); ?>][position]" id="testimonials_items_<?php echo esc_attr( $index ); ?>_position" value="<?php echo esc_attr( $testimonial['position'] ); ?>" class="regular-text" />
							</p>
							<p>
								<label for="testimonials_items_<?php echo esc_attr( $index ); ?>_content"><?php esc_html_e( 'Testimonial', 'aqualuxe' ); ?></label>
								<textarea name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_items][<?php echo esc_attr( $index ); ?>][content]" id="testimonials_items_<?php echo esc_attr( $index ); ?>_content" rows="4" class="large-text"><?php echo esc_textarea( $testimonial['content'] ); ?></textarea>
							</p>
							<div class="aqualuxe-media-field">
								<label for="testimonials_items_<?php echo esc_attr( $index ); ?>_image"><?php esc_html_e( 'Image', 'aqualuxe' ); ?></label>
								<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_items][<?php echo esc_attr( $index ); ?>][image]" id="testimonials_items_<?php echo esc_attr( $index ); ?>_image" value="<?php echo esc_url( $testimonial['image'] ); ?>" class="regular-text" />
								<button type="button" class="button aqualuxe-media-upload"><?php esc_html_e( 'Select Image', 'aqualuxe' ); ?></button>
								<button type="button" class="button aqualuxe-media-remove" <?php echo empty( $testimonial['image'] ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Remove Image', 'aqualuxe' ); ?></button>
								
								<div class="aqualuxe-media-preview">
									<?php if ( ! empty( $testimonial['image'] ) ) : ?>
										<img src="<?php echo esc_url( $testimonial['image'] ); ?>" alt="" />
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
			<button type="button" class="button aqualuxe-repeater-add"><?php esc_html_e( 'Add Testimonial', 'aqualuxe' ); ?></button>
			
			<div class="aqualuxe-repeater-template" style="display: none;">
				<div class="aqualuxe-repeater-item">
					<div class="aqualuxe-repeater-header">
						<span class="aqualuxe-repeater-title"><?php esc_html_e( 'New Testimonial', 'aqualuxe' ); ?></span>
						<div class="aqualuxe-repeater-actions">
							<a href="#" class="aqualuxe-repeater-toggle"><?php esc_html_e( 'Edit', 'aqualuxe' ); ?></a>
							<a href="#" class="aqualuxe-repeater-remove"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></a>
						</div>
					</div>
					<div class="aqualuxe-repeater-content">
						<p>
							<label for="testimonials_items_{index}_name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_items][{index}][name]" id="testimonials_items_{index}_name" value="" class="regular-text" />
						</p>
						<p>
							<label for="testimonials_items_{index}_position"><?php esc_html_e( 'Position', 'aqualuxe' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_items][{index}][position]" id="testimonials_items_{index}_position" value="" class="regular-text" />
						</p>
						<p>
							<label for="testimonials_items_{index}_content"><?php esc_html_e( 'Testimonial', 'aqualuxe' ); ?></label>
							<textarea name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_items][{index}][content]" id="testimonials_items_{index}_content" rows="4" class="large-text"></textarea>
						</p>
						<div class="aqualuxe-media-field">
							<label for="testimonials_items_{index}_image"><?php esc_html_e( 'Image', 'aqualuxe' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[testimonials_items][{index}][image]" id="testimonials_items_{index}_image" value="" class="regular-text" />
							<button type="button" class="button aqualuxe-media-upload"><?php esc_html_e( 'Select Image', 'aqualuxe' ); ?></button>
							<button type="button" class="button aqualuxe-media-remove" style="display:none;"><?php esc_html_e( 'Remove Image', 'aqualuxe' ); ?></button>
							
							<div class="aqualuxe-media-preview"></div>
						</div>
					</div>
				</div>
			</div>
			
			<p class="description"><?php esc_html_e( 'Add testimonials to display on the homepage.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render blog section
	 */
	public function render_blog_section() {
		echo '<p>' . esc_html__( 'Configure the blog/news section on the homepage.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render blog enabled field
	 */
	public function render_blog_enabled_field() {
		$blog_enabled = isset( $this->options['blog_enabled'] ) ? $this->options['blog_enabled'] : true;
		?>
		<label for="blog_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[blog_enabled]" id="blog_enabled" value="1" <?php checked( $blog_enabled ); ?> class="aqualuxe-field-toggle" data-target="blog_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable blog section on homepage', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render blog title field
	 */
	public function render_blog_title_field() {
		$blog_title = isset( $this->options['blog_title'] ) ? $this->options['blog_title'] : __( 'Latest News', 'aqualuxe' );
		?>
		<div id="blog_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[blog_title]" id="blog_title" value="<?php echo esc_attr( $blog_title ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the title for the blog section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render blog subtitle field
	 */
	public function render_blog_subtitle_field() {
		$blog_subtitle = isset( $this->options['blog_subtitle'] ) ? $this->options['blog_subtitle'] : __( 'Stay updated with our latest articles and tips', 'aqualuxe' );
		?>
		<div id="blog_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[blog_subtitle]" id="blog_subtitle" value="<?php echo esc_attr( $blog_subtitle ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the subtitle for the blog section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render blog count field
	 */
	public function render_blog_count_field() {
		$blog_count = isset( $this->options['blog_count'] ) ? $this->options['blog_count'] : 3;
		?>
		<div id="blog_settings">
			<input type="number" name="<?php echo esc_attr( $this->option_name ); ?>[blog_count]" id="blog_count" value="<?php echo esc_attr( $blog_count ); ?>" min="1" max="12" step="1" />
			<p class="description"><?php esc_html_e( 'Select the number of posts to display.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render blog category field
	 */
	public function render_blog_category_field() {
		$blog_category = isset( $this->options['blog_category'] ) ? $this->options['blog_category'] : 0;
		?>
		<div id="blog_settings">
			<select name="<?php echo esc_attr( $this->option_name ); ?>[blog_category]" id="blog_category">
				<option value="0" <?php selected( $blog_category, 0 ); ?>><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
				<?php
				// This would normally query WordPress categories
				// For demonstration, we'll use placeholder values
				$sample_categories = array(
					1 => 'Fish Care',
					2 => 'Aquarium Maintenance',
					3 => 'Breeding Tips',
					4 => 'Product Reviews',
					5 => 'Industry News',
				);

				foreach ( $sample_categories as $id => $name ) {
					echo '<option value="' . esc_attr( $id ) . '" ' . selected( $blog_category, $id, false ) . '>' . esc_html( $name ) . '</option>';
				}
				?>
			</select>
			<p class="description"><?php esc_html_e( 'Select a category to display posts from, or choose "All Categories" to display posts from all categories.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render blog button text field
	 */
	public function render_blog_button_text_field() {
		$blog_button_text = isset( $this->options['blog_button_text'] ) ? $this->options['blog_button_text'] : __( 'View All Posts', 'aqualuxe' );
		?>
		<div id="blog_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[blog_button_text]" id="blog_button_text" value="<?php echo esc_attr( $blog_button_text ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the text for the button.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render blog button URL field
	 */
	public function render_blog_button_url_field() {
		$blog_button_url = isset( $this->options['blog_button_url'] ) ? $this->options['blog_button_url'] : home_url( '/blog/' );
		?>
		<div id="blog_settings">
			<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[blog_button_url]" id="blog_button_url" value="<?php echo esc_url( $blog_button_url ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the URL for the button.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render CTA section
	 */
	public function render_cta_section() {
		echo '<p>' . esc_html__( 'Configure the call to action section on the homepage.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render CTA enabled field
	 */
	public function render_cta_enabled_field() {
		$cta_enabled = isset( $this->options['cta_enabled'] ) ? $this->options['cta_enabled'] : true;
		?>
		<label for="cta_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[cta_enabled]" id="cta_enabled" value="1" <?php checked( $cta_enabled ); ?> class="aqualuxe-field-toggle" data-target="cta_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable call to action section on homepage', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render CTA title field
	 */
	public function render_cta_title_field() {
		$cta_title = isset( $this->options['cta_title'] ) ? $this->options['cta_title'] : __( 'Ready to Transform Your Aquarium?', 'aqualuxe' );
		?>
		<div id="cta_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[cta_title]" id="cta_title" value="<?php echo esc_attr( $cta_title ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the title for the call to action section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render CTA text field
	 */
	public function render_cta_text_field() {
		$cta_text = isset( $this->options['cta_text'] ) ? $this->options['cta_text'] : __( 'Discover our premium selection of ornamental fish and aquarium supplies. Elevate your aquatic experience today.', 'aqualuxe' );
		?>
		<div id="cta_settings">
			<textarea name="<?php echo esc_attr( $this->option_name ); ?>[cta_text]" id="cta_text" rows="4" class="large-text"><?php echo esc_textarea( $cta_text ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Enter the text for the call to action section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render CTA button text field
	 */
	public function render_cta_button_text_field() {
		$cta_button_text = isset( $this->options['cta_button_text'] ) ? $this->options['cta_button_text'] : __( 'Shop Now', 'aqualuxe' );
		?>
		<div id="cta_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[cta_button_text]" id="cta_button_text" value="<?php echo esc_attr( $cta_button_text ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the text for the button.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render CTA button URL field
	 */
	public function render_cta_button_url_field() {
		$cta_button_url = isset( $this->options['cta_button_url'] ) ? $this->options['cta_button_url'] : home_url( '/shop/' );
		?>
		<div id="cta_settings">
			<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[cta_button_url]" id="cta_button_url" value="<?php echo esc_url( $cta_button_url ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the URL for the button.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render CTA background field
	 */
	public function render_cta_background_field() {
		$cta_background = isset( $this->options['cta_background'] ) ? $this->options['cta_background'] : '';
		?>
		<div id="cta_settings" class="aqualuxe-media-field">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[cta_background]" id="cta_background" value="<?php echo esc_url( $cta_background ); ?>" class="regular-text" />
			<button type="button" class="button aqualuxe-media-upload"><?php esc_html_e( 'Select Image', 'aqualuxe' ); ?></button>
			<button type="button" class="button aqualuxe-media-remove" <?php echo empty( $cta_background ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Remove Image', 'aqualuxe' ); ?></button>
			
			<div class="aqualuxe-media-preview">
				<?php if ( ! empty( $cta_background ) ) : ?>
					<img src="<?php echo esc_url( $cta_background ); ?>" alt="" />
				<?php endif; ?>
			</div>
			<p class="description"><?php esc_html_e( 'Select the background image for the call to action section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Get option
	 * 
	 * @param string $key The option key.
	 * @param mixed $default The default value.
	 * @return mixed
	 */
	public function get_option( $key, $default = false ) {
		if ( isset( $this->options[ $key ] ) ) {
			return $this->options[ $key ];
		}
		return $default;
	}
}

// Initialize the class
AquaLuxe_Homepage_Options::instance();