<?php
/**
 * AquaLuxe About Page Options
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * About Page Options Class
 * 
 * Handles the creation and management of all about page options
 */
class AquaLuxe_About_Options {

	/**
	 * Instance
	 * 
	 * @var AquaLuxe_About_Options The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Options group name
	 * 
	 * @var string
	 */
	private $option_group = 'aqualuxe_options_about';

	/**
	 * Options name
	 * 
	 * @var string
	 */
	private $option_name = 'aqualuxe_about_options';

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
			'aqualuxe_about_hero_section',
			__( 'Hero Section', 'aqualuxe' ),
			array( $this, 'render_hero_section' ),
			'aqualuxe-options-about'
		);

		add_settings_field(
			'hero_enabled',
			__( 'Enable Hero Section', 'aqualuxe' ),
			array( $this, 'render_hero_enabled_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_hero_section'
		);

		add_settings_field(
			'hero_title',
			__( 'Hero Title', 'aqualuxe' ),
			array( $this, 'render_hero_title_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_hero_section'
		);

		add_settings_field(
			'hero_subtitle',
			__( 'Hero Subtitle', 'aqualuxe' ),
			array( $this, 'render_hero_subtitle_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_hero_section'
		);

		add_settings_field(
			'hero_image',
			__( 'Hero Background Image', 'aqualuxe' ),
			array( $this, 'render_hero_image_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_hero_section'
		);

		// History Section
		add_settings_section(
			'aqualuxe_about_history_section',
			__( 'Company History', 'aqualuxe' ),
			array( $this, 'render_history_section' ),
			'aqualuxe-options-about'
		);

		add_settings_field(
			'history_enabled',
			__( 'Enable History Section', 'aqualuxe' ),
			array( $this, 'render_history_enabled_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_history_section'
		);

		add_settings_field(
			'history_title',
			__( 'Section Title', 'aqualuxe' ),
			array( $this, 'render_history_title_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_history_section'
		);

		add_settings_field(
			'history_content',
			__( 'History Content', 'aqualuxe' ),
			array( $this, 'render_history_content_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_history_section'
		);

		add_settings_field(
			'history_image',
			__( 'History Image', 'aqualuxe' ),
			array( $this, 'render_history_image_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_history_section'
		);

		// Mission & Values Section
		add_settings_section(
			'aqualuxe_about_mission_section',
			__( 'Mission & Values', 'aqualuxe' ),
			array( $this, 'render_mission_section' ),
			'aqualuxe-options-about'
		);

		add_settings_field(
			'mission_enabled',
			__( 'Enable Mission Section', 'aqualuxe' ),
			array( $this, 'render_mission_enabled_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_mission_section'
		);

		add_settings_field(
			'mission_title',
			__( 'Section Title', 'aqualuxe' ),
			array( $this, 'render_mission_title_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_mission_section'
		);

		add_settings_field(
			'mission_statement',
			__( 'Mission Statement', 'aqualuxe' ),
			array( $this, 'render_mission_statement_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_mission_section'
		);

		add_settings_field(
			'values',
			__( 'Core Values', 'aqualuxe' ),
			array( $this, 'render_values_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_mission_section'
		);

		// Team Section
		add_settings_section(
			'aqualuxe_about_team_section',
			__( 'Team Members', 'aqualuxe' ),
			array( $this, 'render_team_section' ),
			'aqualuxe-options-about'
		);

		add_settings_field(
			'team_enabled',
			__( 'Enable Team Section', 'aqualuxe' ),
			array( $this, 'render_team_enabled_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_team_section'
		);

		add_settings_field(
			'team_title',
			__( 'Section Title', 'aqualuxe' ),
			array( $this, 'render_team_title_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_team_section'
		);

		add_settings_field(
			'team_subtitle',
			__( 'Section Subtitle', 'aqualuxe' ),
			array( $this, 'render_team_subtitle_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_team_section'
		);

		add_settings_field(
			'team_members',
			__( 'Team Members', 'aqualuxe' ),
			array( $this, 'render_team_members_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_team_section'
		);

		// Facilities Section
		add_settings_section(
			'aqualuxe_about_facilities_section',
			__( 'Facilities', 'aqualuxe' ),
			array( $this, 'render_facilities_section' ),
			'aqualuxe-options-about'
		);

		add_settings_field(
			'facilities_enabled',
			__( 'Enable Facilities Section', 'aqualuxe' ),
			array( $this, 'render_facilities_enabled_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_facilities_section'
		);

		add_settings_field(
			'facilities_title',
			__( 'Section Title', 'aqualuxe' ),
			array( $this, 'render_facilities_title_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_facilities_section'
		);

		add_settings_field(
			'facilities_content',
			__( 'Facilities Description', 'aqualuxe' ),
			array( $this, 'render_facilities_content_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_facilities_section'
		);

		add_settings_field(
			'facilities_gallery',
			__( 'Facilities Gallery', 'aqualuxe' ),
			array( $this, 'render_facilities_gallery_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_facilities_section'
		);

		// CTA Section
		add_settings_section(
			'aqualuxe_about_cta_section',
			__( 'Call to Action', 'aqualuxe' ),
			array( $this, 'render_cta_section' ),
			'aqualuxe-options-about'
		);

		add_settings_field(
			'cta_enabled',
			__( 'Enable CTA Section', 'aqualuxe' ),
			array( $this, 'render_cta_enabled_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_cta_section'
		);

		add_settings_field(
			'cta_title',
			__( 'CTA Title', 'aqualuxe' ),
			array( $this, 'render_cta_title_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_cta_section'
		);

		add_settings_field(
			'cta_text',
			__( 'CTA Text', 'aqualuxe' ),
			array( $this, 'render_cta_text_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_cta_section'
		);

		add_settings_field(
			'cta_button_text',
			__( 'Button Text', 'aqualuxe' ),
			array( $this, 'render_cta_button_text_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_cta_section'
		);

		add_settings_field(
			'cta_button_url',
			__( 'Button URL', 'aqualuxe' ),
			array( $this, 'render_cta_button_url_field' ),
			'aqualuxe-options-about',
			'aqualuxe_about_cta_section'
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

		// History Section
		if ( isset( $input['history_enabled'] ) ) {
			$output['history_enabled'] = (bool) $input['history_enabled'];
		}

		if ( isset( $input['history_title'] ) ) {
			$output['history_title'] = sanitize_text_field( $input['history_title'] );
		}

		if ( isset( $input['history_content'] ) ) {
			$output['history_content'] = wp_kses_post( $input['history_content'] );
		}

		if ( isset( $input['history_image'] ) ) {
			$output['history_image'] = esc_url_raw( $input['history_image'] );
		}

		// Mission & Values Section
		if ( isset( $input['mission_enabled'] ) ) {
			$output['mission_enabled'] = (bool) $input['mission_enabled'];
		}

		if ( isset( $input['mission_title'] ) ) {
			$output['mission_title'] = sanitize_text_field( $input['mission_title'] );
		}

		if ( isset( $input['mission_statement'] ) ) {
			$output['mission_statement'] = wp_kses_post( $input['mission_statement'] );
		}

		if ( isset( $input['values'] ) && is_array( $input['values'] ) ) {
			foreach ( $input['values'] as $key => $value ) {
				$output['values'][$key]['title'] = sanitize_text_field( $value['title'] );
				$output['values'][$key]['description'] = wp_kses_post( $value['description'] );
				$output['values'][$key]['icon'] = sanitize_text_field( $value['icon'] );
			}
		}

		// Team Section
		if ( isset( $input['team_enabled'] ) ) {
			$output['team_enabled'] = (bool) $input['team_enabled'];
		}

		if ( isset( $input['team_title'] ) ) {
			$output['team_title'] = sanitize_text_field( $input['team_title'] );
		}

		if ( isset( $input['team_subtitle'] ) ) {
			$output['team_subtitle'] = sanitize_text_field( $input['team_subtitle'] );
		}

		if ( isset( $input['team_members'] ) && is_array( $input['team_members'] ) ) {
			foreach ( $input['team_members'] as $key => $member ) {
				$output['team_members'][$key]['name'] = sanitize_text_field( $member['name'] );
				$output['team_members'][$key]['position'] = sanitize_text_field( $member['position'] );
				$output['team_members'][$key]['bio'] = wp_kses_post( $member['bio'] );
				$output['team_members'][$key]['image'] = esc_url_raw( $member['image'] );
				
				// Social media links
				if ( isset( $member['social'] ) && is_array( $member['social'] ) ) {
					foreach ( $member['social'] as $social_key => $social_url ) {
						$output['team_members'][$key]['social'][$social_key] = esc_url_raw( $social_url );
					}
				}
			}
		}

		// Facilities Section
		if ( isset( $input['facilities_enabled'] ) ) {
			$output['facilities_enabled'] = (bool) $input['facilities_enabled'];
		}

		if ( isset( $input['facilities_title'] ) ) {
			$output['facilities_title'] = sanitize_text_field( $input['facilities_title'] );
		}

		if ( isset( $input['facilities_content'] ) ) {
			$output['facilities_content'] = wp_kses_post( $input['facilities_content'] );
		}

		if ( isset( $input['facilities_gallery'] ) && is_array( $input['facilities_gallery'] ) ) {
			foreach ( $input['facilities_gallery'] as $key => $image ) {
				$output['facilities_gallery'][$key]['url'] = esc_url_raw( $image['url'] );
				$output['facilities_gallery'][$key]['title'] = sanitize_text_field( $image['title'] );
				$output['facilities_gallery'][$key]['caption'] = sanitize_text_field( $image['caption'] );
			}
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

		return $output;
	}

	/**
	 * Render hero section
	 */
	public function render_hero_section() {
		echo '<p>' . esc_html__( 'Configure the hero section at the top of the about page.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render hero enabled field
	 */
	public function render_hero_enabled_field() {
		$hero_enabled = isset( $this->options['hero_enabled'] ) ? $this->options['hero_enabled'] : true;
		?>
		<label for="hero_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[hero_enabled]" id="hero_enabled" value="1" <?php checked( $hero_enabled ); ?> class="aqualuxe-field-toggle" data-target="hero_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable hero section on about page', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render hero title field
	 */
	public function render_hero_title_field() {
		$hero_title = isset( $this->options['hero_title'] ) ? $this->options['hero_title'] : __( 'About AquaLuxe', 'aqualuxe' );
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
		$hero_subtitle = isset( $this->options['hero_subtitle'] ) ? $this->options['hero_subtitle'] : __( 'Learn about our company, mission, and team', 'aqualuxe' );
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
	 * Render history section
	 */
	public function render_history_section() {
		echo '<p>' . esc_html__( 'Configure the company history section on the about page.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render history enabled field
	 */
	public function render_history_enabled_field() {
		$history_enabled = isset( $this->options['history_enabled'] ) ? $this->options['history_enabled'] : true;
		?>
		<label for="history_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[history_enabled]" id="history_enabled" value="1" <?php checked( $history_enabled ); ?> class="aqualuxe-field-toggle" data-target="history_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable history section on about page', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render history title field
	 */
	public function render_history_title_field() {
		$history_title = isset( $this->options['history_title'] ) ? $this->options['history_title'] : __( 'Our History', 'aqualuxe' );
		?>
		<div id="history_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[history_title]" id="history_title" value="<?php echo esc_attr( $history_title ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the title for the history section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render history content field
	 */
	public function render_history_content_field() {
		$history_content = isset( $this->options['history_content'] ) ? $this->options['history_content'] : '';
		
		if ( empty( $history_content ) ) {
			$history_content = '<p>AquaLuxe was founded in 2010 by a group of passionate aquarium enthusiasts with a vision to provide the highest quality ornamental fish and aquarium supplies to hobbyists and professionals alike.</p>
<p>What started as a small breeding operation in a local facility has grown into one of the premier ornamental fish suppliers in the region, with state-of-the-art breeding facilities and a commitment to sustainable aquaculture practices.</p>
<p>Over the years, we have expanded our operations to include a wide range of freshwater and saltwater species, as well as a comprehensive selection of aquarium supplies and equipment. Our team has grown from just three founders to over 50 dedicated professionals who share our passion for aquatic life.</p>
<p>Today, AquaLuxe continues to innovate and lead the industry with our focus on quality, sustainability, and customer satisfaction. We remain committed to our founding principles while embracing new technologies and practices to ensure the health and well-being of our fish and the success of our customers\' aquariums.</p>';
		}
		?>
		<div id="history_settings">
			<?php
			$editor_settings = array(
				'textarea_name' => $this->option_name . '[history_content]',
				'textarea_rows' => 10,
				'media_buttons' => true,
				'teeny'         => false,
				'tinymce'       => true,
			);
			wp_editor( $history_content, 'history_content', $editor_settings );
			?>
			<p class="description"><?php esc_html_e( 'Enter the content for the history section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render history image field
	 */
	public function render_history_image_field() {
		$history_image = isset( $this->options['history_image'] ) ? $this->options['history_image'] : '';
		?>
		<div id="history_settings" class="aqualuxe-media-field">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[history_image]" id="history_image" value="<?php echo esc_url( $history_image ); ?>" class="regular-text" />
			<button type="button" class="button aqualuxe-media-upload"><?php esc_html_e( 'Select Image', 'aqualuxe' ); ?></button>
			<button type="button" class="button aqualuxe-media-remove" <?php echo empty( $history_image ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Remove Image', 'aqualuxe' ); ?></button>
			
			<div class="aqualuxe-media-preview">
				<?php if ( ! empty( $history_image ) ) : ?>
					<img src="<?php echo esc_url( $history_image ); ?>" alt="" />
				<?php endif; ?>
			</div>
			<p class="description"><?php esc_html_e( 'Select an image for the history section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render mission section
	 */
	public function render_mission_section() {
		echo '<p>' . esc_html__( 'Configure the mission and values section on the about page.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render mission enabled field
	 */
	public function render_mission_enabled_field() {
		$mission_enabled = isset( $this->options['mission_enabled'] ) ? $this->options['mission_enabled'] : true;
		?>
		<label for="mission_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[mission_enabled]" id="mission_enabled" value="1" <?php checked( $mission_enabled ); ?> class="aqualuxe-field-toggle" data-target="mission_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable mission section on about page', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render mission title field
	 */
	public function render_mission_title_field() {
		$mission_title = isset( $this->options['mission_title'] ) ? $this->options['mission_title'] : __( 'Our Mission & Values', 'aqualuxe' );
		?>
		<div id="mission_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[mission_title]" id="mission_title" value="<?php echo esc_attr( $mission_title ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the title for the mission section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render mission statement field
	 */
	public function render_mission_statement_field() {
		$mission_statement = isset( $this->options['mission_statement'] ) ? $this->options['mission_statement'] : __( 'Our mission is to provide the highest quality ornamental fish and aquarium supplies while promoting sustainable aquaculture practices and educating our customers on proper fish care.', 'aqualuxe' );
		?>
		<div id="mission_settings">
			<textarea name="<?php echo esc_attr( $this->option_name ); ?>[mission_statement]" id="mission_statement" rows="4" class="large-text"><?php echo esc_textarea( $mission_statement ); ?></textarea>
			<p class="description"><?php esc_html_e( 'Enter your company\'s mission statement.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render values field
	 */
	public function render_values_field() {
		$values = isset( $this->options['values'] ) ? $this->options['values'] : array(
			array(
				'title' => __( 'Quality', 'aqualuxe' ),
				'description' => __( 'We are committed to providing the highest quality fish and products to our customers.', 'aqualuxe' ),
				'icon' => 'award',
			),
			array(
				'title' => __( 'Sustainability', 'aqualuxe' ),
				'description' => __( 'We practice and promote sustainable aquaculture to protect our natural resources.', 'aqualuxe' ),
				'icon' => 'leaf',
			),
			array(
				'title' => __( 'Education', 'aqualuxe' ),
				'description' => __( 'We believe in educating our customers on proper fish care and aquarium maintenance.', 'aqualuxe' ),
				'icon' => 'book',
			),
			array(
				'title' => __( 'Innovation', 'aqualuxe' ),
				'description' => __( 'We continuously seek new and better ways to breed, raise, and care for ornamental fish.', 'aqualuxe' ),
				'icon' => 'lightbulb',
			),
		);
		?>
		<div id="mission_settings">
			<div class="aqualuxe-repeater-container" data-name="<?php echo esc_attr( $this->option_name ); ?>[values]" data-max="8">
				<?php foreach ( $values as $index => $value ) : ?>
					<div class="aqualuxe-repeater-item">
						<div class="aqualuxe-repeater-header">
							<span class="aqualuxe-repeater-title"><?php echo esc_html( $value['title'] ); ?></span>
							<div class="aqualuxe-repeater-actions">
								<a href="#" class="aqualuxe-repeater-toggle"><?php esc_html_e( 'Edit', 'aqualuxe' ); ?></a>
								<a href="#" class="aqualuxe-repeater-remove"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></a>
							</div>
						</div>
						<div class="aqualuxe-repeater-content">
							<p>
								<label for="values_<?php echo esc_attr( $index ); ?>_title"><?php esc_html_e( 'Title', 'aqualuxe' ); ?></label>
								<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[values][<?php echo esc_attr( $index ); ?>][title]" id="values_<?php echo esc_attr( $index ); ?>_title" value="<?php echo esc_attr( $value['title'] ); ?>" class="regular-text" />
							</p>
							<p>
								<label for="values_<?php echo esc_attr( $index ); ?>_description"><?php esc_html_e( 'Description', 'aqualuxe' ); ?></label>
								<textarea name="<?php echo esc_attr( $this->option_name ); ?>[values][<?php echo esc_attr( $index ); ?>][description]" id="values_<?php echo esc_attr( $index ); ?>_description" rows="3" class="large-text"><?php echo esc_textarea( $value['description'] ); ?></textarea>
							</p>
							<p>
								<label for="values_<?php echo esc_attr( $index ); ?>_icon"><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></label>
								<select name="<?php echo esc_attr( $this->option_name ); ?>[values][<?php echo esc_attr( $index ); ?>][icon]" id="values_<?php echo esc_attr( $index ); ?>_icon">
									<option value="award" <?php selected( $value['icon'], 'award' ); ?>><?php esc_html_e( 'Award', 'aqualuxe' ); ?></option>
									<option value="leaf" <?php selected( $value['icon'], 'leaf' ); ?>><?php esc_html_e( 'Leaf', 'aqualuxe' ); ?></option>
									<option value="book" <?php selected( $value['icon'], 'book' ); ?>><?php esc_html_e( 'Book', 'aqualuxe' ); ?></option>
									<option value="lightbulb" <?php selected( $value['icon'], 'lightbulb' ); ?>><?php esc_html_e( 'Lightbulb', 'aqualuxe' ); ?></option>
									<option value="heart" <?php selected( $value['icon'], 'heart' ); ?>><?php esc_html_e( 'Heart', 'aqualuxe' ); ?></option>
									<option value="globe" <?php selected( $value['icon'], 'globe' ); ?>><?php esc_html_e( 'Globe', 'aqualuxe' ); ?></option>
									<option value="users" <?php selected( $value['icon'], 'users' ); ?>><?php esc_html_e( 'Users', 'aqualuxe' ); ?></option>
									<option value="shield" <?php selected( $value['icon'], 'shield' ); ?>><?php esc_html_e( 'Shield', 'aqualuxe' ); ?></option>
								</select>
							</p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
			<button type="button" class="button aqualuxe-repeater-add"><?php esc_html_e( 'Add Value', 'aqualuxe' ); ?></button>
			
			<div class="aqualuxe-repeater-template" style="display: none;">
				<div class="aqualuxe-repeater-item">
					<div class="aqualuxe-repeater-header">
						<span class="aqualuxe-repeater-title"><?php esc_html_e( 'New Value', 'aqualuxe' ); ?></span>
						<div class="aqualuxe-repeater-actions">
							<a href="#" class="aqualuxe-repeater-toggle"><?php esc_html_e( 'Edit', 'aqualuxe' ); ?></a>
							<a href="#" class="aqualuxe-repeater-remove"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></a>
						</div>
					</div>
					<div class="aqualuxe-repeater-content">
						<p>
							<label for="values_{index}_title"><?php esc_html_e( 'Title', 'aqualuxe' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[values][{index}][title]" id="values_{index}_title" value="" class="regular-text" />
						</p>
						<p>
							<label for="values_{index}_description"><?php esc_html_e( 'Description', 'aqualuxe' ); ?></label>
							<textarea name="<?php echo esc_attr( $this->option_name ); ?>[values][{index}][description]" id="values_{index}_description" rows="3" class="large-text"></textarea>
						</p>
						<p>
							<label for="values_{index}_icon"><?php esc_html_e( 'Icon', 'aqualuxe' ); ?></label>
							<select name="<?php echo esc_attr( $this->option_name ); ?>[values][{index}][icon]" id="values_{index}_icon">
								<option value="award"><?php esc_html_e( 'Award', 'aqualuxe' ); ?></option>
								<option value="leaf"><?php esc_html_e( 'Leaf', 'aqualuxe' ); ?></option>
								<option value="book"><?php esc_html_e( 'Book', 'aqualuxe' ); ?></option>
								<option value="lightbulb"><?php esc_html_e( 'Lightbulb', 'aqualuxe' ); ?></option>
								<option value="heart"><?php esc_html_e( 'Heart', 'aqualuxe' ); ?></option>
								<option value="globe"><?php esc_html_e( 'Globe', 'aqualuxe' ); ?></option>
								<option value="users"><?php esc_html_e( 'Users', 'aqualuxe' ); ?></option>
								<option value="shield"><?php esc_html_e( 'Shield', 'aqualuxe' ); ?></option>
							</select>
						</p>
					</div>
				</div>
			</div>
			
			<p class="description"><?php esc_html_e( 'Add your company\'s core values.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render team section
	 */
	public function render_team_section() {
		echo '<p>' . esc_html__( 'Configure the team members section on the about page.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render team enabled field
	 */
	public function render_team_enabled_field() {
		$team_enabled = isset( $this->options['team_enabled'] ) ? $this->options['team_enabled'] : true;
		?>
		<label for="team_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[team_enabled]" id="team_enabled" value="1" <?php checked( $team_enabled ); ?> class="aqualuxe-field-toggle" data-target="team_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable team section on about page', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render team title field
	 */
	public function render_team_title_field() {
		$team_title = isset( $this->options['team_title'] ) ? $this->options['team_title'] : __( 'Meet Our Team', 'aqualuxe' );
		?>
		<div id="team_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[team_title]" id="team_title" value="<?php echo esc_attr( $team_title ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the title for the team section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render team subtitle field
	 */
	public function render_team_subtitle_field() {
		$team_subtitle = isset( $this->options['team_subtitle'] ) ? $this->options['team_subtitle'] : __( 'The passionate experts behind AquaLuxe', 'aqualuxe' );
		?>
		<div id="team_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[team_subtitle]" id="team_subtitle" value="<?php echo esc_attr( $team_subtitle ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the subtitle for the team section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render team members field
	 */
	public function render_team_members_field() {
		$team_members = isset( $this->options['team_members'] ) ? $this->options['team_members'] : array(
			array(
				'name' => 'John Smith',
				'position' => 'Founder & CEO',
				'bio' => 'John has over 20 years of experience in aquaculture and is passionate about ornamental fish breeding.',
				'image' => '',
				'social' => array(
					'linkedin' => 'https://linkedin.com/',
					'twitter' => 'https://twitter.com/',
					'facebook' => '',
				),
			),
			array(
				'name' => 'Jane Doe',
				'position' => 'Head of Breeding Operations',
				'bio' => 'Jane oversees all breeding operations and has developed several innovative breeding techniques.',
				'image' => '',
				'social' => array(
					'linkedin' => 'https://linkedin.com/',
					'twitter' => '',
					'facebook' => 'https://facebook.com/',
				),
			),
			array(
				'name' => 'Mike Johnson',
				'position' => 'Chief Aquarium Designer',
				'bio' => 'Mike specializes in creating stunning aquascapes and has won numerous design awards.',
				'image' => '',
				'social' => array(
					'linkedin' => 'https://linkedin.com/',
					'twitter' => 'https://twitter.com/',
					'facebook' => 'https://facebook.com/',
				),
			),
			array(
				'name' => 'Sarah Williams',
				'position' => 'Customer Experience Manager',
				'bio' => 'Sarah ensures that every customer receives exceptional service and support.',
				'image' => '',
				'social' => array(
					'linkedin' => 'https://linkedin.com/',
					'twitter' => '',
					'facebook' => '',
				),
			),
		);
		?>
		<div id="team_settings">
			<div class="aqualuxe-repeater-container" data-name="<?php echo esc_attr( $this->option_name ); ?>[team_members]" data-max="12">
				<?php foreach ( $team_members as $index => $member ) : ?>
					<div class="aqualuxe-repeater-item">
						<div class="aqualuxe-repeater-header">
							<span class="aqualuxe-repeater-title"><?php echo esc_html( $member['name'] ); ?></span>
							<div class="aqualuxe-repeater-actions">
								<a href="#" class="aqualuxe-repeater-toggle"><?php esc_html_e( 'Edit', 'aqualuxe' ); ?></a>
								<a href="#" class="aqualuxe-repeater-remove"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></a>
							</div>
						</div>
						<div class="aqualuxe-repeater-content">
							<p>
								<label for="team_members_<?php echo esc_attr( $index ); ?>_name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?></label>
								<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][<?php echo esc_attr( $index ); ?>][name]" id="team_members_<?php echo esc_attr( $index ); ?>_name" value="<?php echo esc_attr( $member['name'] ); ?>" class="regular-text" />
							</p>
							<p>
								<label for="team_members_<?php echo esc_attr( $index ); ?>_position"><?php esc_html_e( 'Position', 'aqualuxe' ); ?></label>
								<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][<?php echo esc_attr( $index ); ?>][position]" id="team_members_<?php echo esc_attr( $index ); ?>_position" value="<?php echo esc_attr( $member['position'] ); ?>" class="regular-text" />
							</p>
							<p>
								<label for="team_members_<?php echo esc_attr( $index ); ?>_bio"><?php esc_html_e( 'Bio', 'aqualuxe' ); ?></label>
								<textarea name="<?php echo esc_attr( $this->option_name ); ?>[team_members][<?php echo esc_attr( $index ); ?>][bio]" id="team_members_<?php echo esc_attr( $index ); ?>_bio" rows="3" class="large-text"><?php echo esc_textarea( $member['bio'] ); ?></textarea>
							</p>
							<div class="aqualuxe-media-field">
								<label for="team_members_<?php echo esc_attr( $index ); ?>_image"><?php esc_html_e( 'Image', 'aqualuxe' ); ?></label>
								<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][<?php echo esc_attr( $index ); ?>][image]" id="team_members_<?php echo esc_attr( $index ); ?>_image" value="<?php echo esc_url( $member['image'] ); ?>" class="regular-text" />
								<button type="button" class="button aqualuxe-media-upload"><?php esc_html_e( 'Select Image', 'aqualuxe' ); ?></button>
								<button type="button" class="button aqualuxe-media-remove" <?php echo empty( $member['image'] ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Remove Image', 'aqualuxe' ); ?></button>
								
								<div class="aqualuxe-media-preview">
									<?php if ( ! empty( $member['image'] ) ) : ?>
										<img src="<?php echo esc_url( $member['image'] ); ?>" alt="" />
									<?php endif; ?>
								</div>
							</div>
							<div class="aqualuxe-social-media-fields">
								<h4><?php esc_html_e( 'Social Media Links', 'aqualuxe' ); ?></h4>
								<p>
									<label for="team_members_<?php echo esc_attr( $index ); ?>_social_linkedin"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></label>
									<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][<?php echo esc_attr( $index ); ?>][social][linkedin]" id="team_members_<?php echo esc_attr( $index ); ?>_social_linkedin" value="<?php echo esc_url( $member['social']['linkedin'] ?? '' ); ?>" class="regular-text" />
								</p>
								<p>
									<label for="team_members_<?php echo esc_attr( $index ); ?>_social_twitter"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></label>
									<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][<?php echo esc_attr( $index ); ?>][social][twitter]" id="team_members_<?php echo esc_attr( $index ); ?>_social_twitter" value="<?php echo esc_url( $member['social']['twitter'] ?? '' ); ?>" class="regular-text" />
								</p>
								<p>
									<label for="team_members_<?php echo esc_attr( $index ); ?>_social_facebook"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></label>
									<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][<?php echo esc_attr( $index ); ?>][social][facebook]" id="team_members_<?php echo esc_attr( $index ); ?>_social_facebook" value="<?php echo esc_url( $member['social']['facebook'] ?? '' ); ?>" class="regular-text" />
								</p>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
			<button type="button" class="button aqualuxe-repeater-add"><?php esc_html_e( 'Add Team Member', 'aqualuxe' ); ?></button>
			
			<div class="aqualuxe-repeater-template" style="display: none;">
				<div class="aqualuxe-repeater-item">
					<div class="aqualuxe-repeater-header">
						<span class="aqualuxe-repeater-title"><?php esc_html_e( 'New Team Member', 'aqualuxe' ); ?></span>
						<div class="aqualuxe-repeater-actions">
							<a href="#" class="aqualuxe-repeater-toggle"><?php esc_html_e( 'Edit', 'aqualuxe' ); ?></a>
							<a href="#" class="aqualuxe-repeater-remove"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></a>
						</div>
					</div>
					<div class="aqualuxe-repeater-content">
						<p>
							<label for="team_members_{index}_name"><?php esc_html_e( 'Name', 'aqualuxe' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][{index}][name]" id="team_members_{index}_name" value="" class="regular-text" />
						</p>
						<p>
							<label for="team_members_{index}_position"><?php esc_html_e( 'Position', 'aqualuxe' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][{index}][position]" id="team_members_{index}_position" value="" class="regular-text" />
						</p>
						<p>
							<label for="team_members_{index}_bio"><?php esc_html_e( 'Bio', 'aqualuxe' ); ?></label>
							<textarea name="<?php echo esc_attr( $this->option_name ); ?>[team_members][{index}][bio]" id="team_members_{index}_bio" rows="3" class="large-text"></textarea>
						</p>
						<div class="aqualuxe-media-field">
							<label for="team_members_{index}_image"><?php esc_html_e( 'Image', 'aqualuxe' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][{index}][image]" id="team_members_{index}_image" value="" class="regular-text" />
							<button type="button" class="button aqualuxe-media-upload"><?php esc_html_e( 'Select Image', 'aqualuxe' ); ?></button>
							<button type="button" class="button aqualuxe-media-remove" style="display:none;"><?php esc_html_e( 'Remove Image', 'aqualuxe' ); ?></button>
							
							<div class="aqualuxe-media-preview"></div>
						</div>
						<div class="aqualuxe-social-media-fields">
							<h4><?php esc_html_e( 'Social Media Links', 'aqualuxe' ); ?></h4>
							<p>
								<label for="team_members_{index}_social_linkedin"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></label>
								<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][{index}][social][linkedin]" id="team_members_{index}_social_linkedin" value="" class="regular-text" />
							</p>
							<p>
								<label for="team_members_{index}_social_twitter"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></label>
								<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][{index}][social][twitter]" id="team_members_{index}_social_twitter" value="" class="regular-text" />
							</p>
							<p>
								<label for="team_members_{index}_social_facebook"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></label>
								<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[team_members][{index}][social][facebook]" id="team_members_{index}_social_facebook" value="" class="regular-text" />
							</p>
						</div>
					</div>
				</div>
			</div>
			
			<p class="description"><?php esc_html_e( 'Add team members to display on the about page.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render facilities section
	 */
	public function render_facilities_section() {
		echo '<p>' . esc_html__( 'Configure the facilities section on the about page.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render facilities enabled field
	 */
	public function render_facilities_enabled_field() {
		$facilities_enabled = isset( $this->options['facilities_enabled'] ) ? $this->options['facilities_enabled'] : true;
		?>
		<label for="facilities_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[facilities_enabled]" id="facilities_enabled" value="1" <?php checked( $facilities_enabled ); ?> class="aqualuxe-field-toggle" data-target="facilities_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable facilities section on about page', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render facilities title field
	 */
	public function render_facilities_title_field() {
		$facilities_title = isset( $this->options['facilities_title'] ) ? $this->options['facilities_title'] : __( 'Our Facilities', 'aqualuxe' );
		?>
		<div id="facilities_settings">
			<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[facilities_title]" id="facilities_title" value="<?php echo esc_attr( $facilities_title ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the title for the facilities section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render facilities content field
	 */
	public function render_facilities_content_field() {
		$facilities_content = isset( $this->options['facilities_content'] ) ? $this->options['facilities_content'] : '';
		
		if ( empty( $facilities_content ) ) {
			$facilities_content = '<p>Our state-of-the-art facilities are designed to provide the optimal environment for breeding and raising ornamental fish. We maintain strict quality control standards and employ the latest technology to ensure the health and well-being of our fish.</p>
<p>Our facilities include:</p>
<ul>
<li>Temperature-controlled breeding rooms</li>
<li>Advanced filtration systems</li>
<li>Specialized quarantine areas</li>
<li>Research and development laboratory</li>
<li>Dedicated packing and shipping department</li>
</ul>
<p>We invite you to take a virtual tour of our facilities through the gallery below.</p>';
		}
		?>
		<div id="facilities_settings">
			<?php
			$editor_settings = array(
				'textarea_name' => $this->option_name . '[facilities_content]',
				'textarea_rows' => 8,
				'media_buttons' => true,
				'teeny'         => false,
				'tinymce'       => true,
			);
			wp_editor( $facilities_content, 'facilities_content', $editor_settings );
			?>
			<p class="description"><?php esc_html_e( 'Enter the content for the facilities section.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render facilities gallery field
	 */
	public function render_facilities_gallery_field() {
		$facilities_gallery = isset( $this->options['facilities_gallery'] ) ? $this->options['facilities_gallery'] : array(
			array(
				'url' => '',
				'title' => __( 'Breeding Tanks', 'aqualuxe' ),
				'caption' => __( 'Our specialized breeding tanks for rare species', 'aqualuxe' ),
			),
			array(
				'url' => '',
				'title' => __( 'Filtration System', 'aqualuxe' ),
				'caption' => __( 'Advanced filtration system for optimal water quality', 'aqualuxe' ),
			),
			array(
				'url' => '',
				'title' => __( 'Research Lab', 'aqualuxe' ),
				'caption' => __( 'Our research and development laboratory', 'aqualuxe' ),
			),
			array(
				'url' => '',
				'title' => __( 'Shipping Department', 'aqualuxe' ),
				'caption' => __( 'Specialized packing and shipping area', 'aqualuxe' ),
			),
		);
		?>
		<div id="facilities_settings">
			<div class="aqualuxe-repeater-container" data-name="<?php echo esc_attr( $this->option_name ); ?>[facilities_gallery]" data-max="12">
				<?php foreach ( $facilities_gallery as $index => $image ) : ?>
					<div class="aqualuxe-repeater-item">
						<div class="aqualuxe-repeater-header">
							<span class="aqualuxe-repeater-title"><?php echo esc_html( $image['title'] ); ?></span>
							<div class="aqualuxe-repeater-actions">
								<a href="#" class="aqualuxe-repeater-toggle"><?php esc_html_e( 'Edit', 'aqualuxe' ); ?></a>
								<a href="#" class="aqualuxe-repeater-remove"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></a>
							</div>
						</div>
						<div class="aqualuxe-repeater-content">
							<div class="aqualuxe-media-field">
								<label for="facilities_gallery_<?php echo esc_attr( $index ); ?>_url"><?php esc_html_e( 'Image', 'aqualuxe' ); ?></label>
								<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[facilities_gallery][<?php echo esc_attr( $index ); ?>][url]" id="facilities_gallery_<?php echo esc_attr( $index ); ?>_url" value="<?php echo esc_url( $image['url'] ); ?>" class="regular-text" />
								<button type="button" class="button aqualuxe-media-upload"><?php esc_html_e( 'Select Image', 'aqualuxe' ); ?></button>
								<button type="button" class="button aqualuxe-media-remove" <?php echo empty( $image['url'] ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Remove Image', 'aqualuxe' ); ?></button>
								
								<div class="aqualuxe-media-preview">
									<?php if ( ! empty( $image['url'] ) ) : ?>
										<img src="<?php echo esc_url( $image['url'] ); ?>" alt="" />
									<?php endif; ?>
								</div>
							</div>
							<p>
								<label for="facilities_gallery_<?php echo esc_attr( $index ); ?>_title"><?php esc_html_e( 'Title', 'aqualuxe' ); ?></label>
								<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[facilities_gallery][<?php echo esc_attr( $index ); ?>][title]" id="facilities_gallery_<?php echo esc_attr( $index ); ?>_title" value="<?php echo esc_attr( $image['title'] ); ?>" class="regular-text" />
							</p>
							<p>
								<label for="facilities_gallery_<?php echo esc_attr( $index ); ?>_caption"><?php esc_html_e( 'Caption', 'aqualuxe' ); ?></label>
								<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[facilities_gallery][<?php echo esc_attr( $index ); ?>][caption]" id="facilities_gallery_<?php echo esc_attr( $index ); ?>_caption" value="<?php echo esc_attr( $image['caption'] ); ?>" class="regular-text" />
							</p>
						</div>
					</div>
				<?php endforeach; ?>
			</div>
			
			<button type="button" class="button aqualuxe-repeater-add"><?php esc_html_e( 'Add Image', 'aqualuxe' ); ?></button>
			
			<div class="aqualuxe-repeater-template" style="display: none;">
				<div class="aqualuxe-repeater-item">
					<div class="aqualuxe-repeater-header">
						<span class="aqualuxe-repeater-title"><?php esc_html_e( 'New Image', 'aqualuxe' ); ?></span>
						<div class="aqualuxe-repeater-actions">
							<a href="#" class="aqualuxe-repeater-toggle"><?php esc_html_e( 'Edit', 'aqualuxe' ); ?></a>
							<a href="#" class="aqualuxe-repeater-remove"><?php esc_html_e( 'Remove', 'aqualuxe' ); ?></a>
						</div>
					</div>
					<div class="aqualuxe-repeater-content">
						<div class="aqualuxe-media-field">
							<label for="facilities_gallery_{index}_url"><?php esc_html_e( 'Image', 'aqualuxe' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[facilities_gallery][{index}][url]" id="facilities_gallery_{index}_url" value="" class="regular-text" />
							<button type="button" class="button aqualuxe-media-upload"><?php esc_html_e( 'Select Image', 'aqualuxe' ); ?></button>
							<button type="button" class="button aqualuxe-media-remove" style="display:none;"><?php esc_html_e( 'Remove Image', 'aqualuxe' ); ?></button>
							
							<div class="aqualuxe-media-preview"></div>
						</div>
						<p>
							<label for="facilities_gallery_{index}_title"><?php esc_html_e( 'Title', 'aqualuxe' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[facilities_gallery][{index}][title]" id="facilities_gallery_{index}_title" value="" class="regular-text" />
						</p>
						<p>
							<label for="facilities_gallery_{index}_caption"><?php esc_html_e( 'Caption', 'aqualuxe' ); ?></label>
							<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[facilities_gallery][{index}][caption]" id="facilities_gallery_{index}_caption" value="" class="regular-text" />
						</p>
					</div>
				</div>
			</div>
			
			<p class="description"><?php esc_html_e( 'Add images to the facilities gallery.', 'aqualuxe' ); ?></p>
		</div>
		<?php
	}

	/**
	 * Render CTA section
	 */
	public function render_cta_section() {
		echo '<p>' . esc_html__( 'Configure the call to action section on the about page.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render CTA enabled field
	 */
	public function render_cta_enabled_field() {
		$cta_enabled = isset( $this->options['cta_enabled'] ) ? $this->options['cta_enabled'] : true;
		?>
		<label for="cta_enabled">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[cta_enabled]" id="cta_enabled" value="1" <?php checked( $cta_enabled ); ?> class="aqualuxe-field-toggle" data-target="cta_settings" data-show-on="checked" />
			<?php esc_html_e( 'Enable call to action section on about page', 'aqualuxe' ); ?>
		</label>
		<?php
	}

	/**
	 * Render CTA title field
	 */
	public function render_cta_title_field() {
		$cta_title = isset( $this->options['cta_title'] ) ? $this->options['cta_title'] : __( 'Want to Learn More?', 'aqualuxe' );
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
		$cta_text = isset( $this->options['cta_text'] ) ? $this->options['cta_text'] : __( 'Contact us today to learn more about our products and services. Our team is ready to help you create the perfect aquatic environment.', 'aqualuxe' );
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
		$cta_button_text = isset( $this->options['cta_button_text'] ) ? $this->options['cta_button_text'] : __( 'Contact Us', 'aqualuxe' );
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
		$cta_button_url = isset( $this->options['cta_button_url'] ) ? $this->options['cta_button_url'] : home_url( '/contact/' );
		?>
		<div id="cta_settings">
			<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[cta_button_url]" id="cta_button_url" value="<?php echo esc_url( $cta_button_url ); ?>" class="regular-text" />
			<p class="description"><?php esc_html_e( 'Enter the URL for the button.', 'aqualuxe' ); ?></p>
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
AquaLuxe_About_Options::instance();