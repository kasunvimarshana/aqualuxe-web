<?php
/**
 * AquaLuxe Theme Options
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Theme Options Class
 * 
 * Handles the creation and management of all theme options
 */
class AquaLuxe_Theme_Options {

	/**
	 * Instance
	 * 
	 * @var AquaLuxe_Theme_Options The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Options group name
	 * 
	 * @var string
	 */
	private $option_group = 'aqualuxe_options';

	/**
	 * Options name
	 * 
	 * @var string
	 */
	private $option_name = 'aqualuxe_theme_options';

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
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Add menu page
	 */
	public function add_menu_page() {
		add_menu_page(
			__( 'AquaLuxe Options', 'aqualuxe' ),
			__( 'AquaLuxe', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options',
			array( $this, 'render_options_page' ),
			'dashicons-admin-customizer',
			59
		);

		// Add submenus
		add_submenu_page(
			'aqualuxe-options',
			__( 'General Settings', 'aqualuxe' ),
			__( 'General Settings', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options',
			array( $this, 'render_options_page' )
		);

		add_submenu_page(
			'aqualuxe-options',
			__( 'Homepage', 'aqualuxe' ),
			__( 'Homepage', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options-homepage',
			array( $this, 'render_homepage_options' )
		);

		add_submenu_page(
			'aqualuxe-options',
			__( 'About Page', 'aqualuxe' ),
			__( 'About Page', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options-about',
			array( $this, 'render_about_options' )
		);

		add_submenu_page(
			'aqualuxe-options',
			__( 'Shop Settings', 'aqualuxe' ),
			__( 'Shop Settings', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options-shop',
			array( $this, 'render_shop_options' )
		);

		add_submenu_page(
			'aqualuxe-options',
			__( 'Fish Species', 'aqualuxe' ),
			__( 'Fish Species', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options-species',
			array( $this, 'render_species_options' )
		);

		add_submenu_page(
			'aqualuxe-options',
			__( 'Services', 'aqualuxe' ),
			__( 'Services', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options-services',
			array( $this, 'render_services_options' )
		);

		add_submenu_page(
			'aqualuxe-options',
			__( 'Contact & Pages', 'aqualuxe' ),
			__( 'Contact & Pages', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options-pages',
			array( $this, 'render_pages_options' )
		);

		add_submenu_page(
			'aqualuxe-options',
			__( 'Multi-Currency', 'aqualuxe' ),
			__( 'Multi-Currency', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options-currency',
			array( $this, 'render_currency_options' )
		);

		add_submenu_page(
			'aqualuxe-options',
			__( 'Shipping', 'aqualuxe' ),
			__( 'Shipping', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options-shipping',
			array( $this, 'render_shipping_options' )
		);

		add_submenu_page(
			'aqualuxe-options',
			__( 'Import/Export', 'aqualuxe' ),
			__( 'Import/Export', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-options-import-export',
			array( $this, 'render_import_export_options' )
		);
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

		// General Settings
		add_settings_section(
			'aqualuxe_general_section',
			__( 'General Settings', 'aqualuxe' ),
			array( $this, 'render_general_section' ),
			'aqualuxe-options'
		);

		// Header Settings
		add_settings_field(
			'header_layout',
			__( 'Header Layout', 'aqualuxe' ),
			array( $this, 'render_header_layout_field' ),
			'aqualuxe-options',
			'aqualuxe_general_section'
		);

		add_settings_field(
			'logo_position',
			__( 'Logo Position', 'aqualuxe' ),
			array( $this, 'render_logo_position_field' ),
			'aqualuxe-options',
			'aqualuxe_general_section'
		);

		add_settings_field(
			'sticky_header',
			__( 'Sticky Header', 'aqualuxe' ),
			array( $this, 'render_sticky_header_field' ),
			'aqualuxe-options',
			'aqualuxe_general_section'
		);

		// Footer Settings
		add_settings_field(
			'footer_columns',
			__( 'Footer Columns', 'aqualuxe' ),
			array( $this, 'render_footer_columns_field' ),
			'aqualuxe-options',
			'aqualuxe_general_section'
		);

		add_settings_field(
			'footer_copyright',
			__( 'Copyright Text', 'aqualuxe' ),
			array( $this, 'render_footer_copyright_field' ),
			'aqualuxe-options',
			'aqualuxe_general_section'
		);

		// Social Media
		add_settings_field(
			'social_media',
			__( 'Social Media Links', 'aqualuxe' ),
			array( $this, 'render_social_media_field' ),
			'aqualuxe-options',
			'aqualuxe_general_section'
		);

		// Color Scheme
		add_settings_field(
			'color_scheme',
			__( 'Color Scheme', 'aqualuxe' ),
			array( $this, 'render_color_scheme_field' ),
			'aqualuxe-options',
			'aqualuxe_general_section'
		);

		// Typography
		add_settings_field(
			'typography',
			__( 'Typography', 'aqualuxe' ),
			array( $this, 'render_typography_field' ),
			'aqualuxe-options',
			'aqualuxe_general_section'
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

		// General settings
		if ( isset( $input['header_layout'] ) ) {
			$output['header_layout'] = sanitize_text_field( $input['header_layout'] );
		}

		if ( isset( $input['logo_position'] ) ) {
			$output['logo_position'] = sanitize_text_field( $input['logo_position'] );
		}

		if ( isset( $input['sticky_header'] ) ) {
			$output['sticky_header'] = (bool) $input['sticky_header'];
		}

		if ( isset( $input['footer_columns'] ) ) {
			$output['footer_columns'] = absint( $input['footer_columns'] );
		}

		if ( isset( $input['footer_copyright'] ) ) {
			$output['footer_copyright'] = wp_kses_post( $input['footer_copyright'] );
		}

		// Social media
		if ( isset( $input['social_media'] ) && is_array( $input['social_media'] ) ) {
			foreach ( $input['social_media'] as $key => $value ) {
				$output['social_media'][$key] = esc_url_raw( $value );
			}
		}

		// Color scheme
		if ( isset( $input['color_scheme'] ) && is_array( $input['color_scheme'] ) ) {
			foreach ( $input['color_scheme'] as $key => $value ) {
				$output['color_scheme'][$key] = sanitize_hex_color( $value );
			}
		}

		// Typography
		if ( isset( $input['typography'] ) && is_array( $input['typography'] ) ) {
			foreach ( $input['typography'] as $key => $value ) {
				$output['typography'][$key] = sanitize_text_field( $value );
			}
		}

		return $output;
	}

	/**
	 * Enqueue admin scripts
	 */
	public function enqueue_admin_scripts( $hook ) {
		// Only load on theme options pages
		if ( strpos( $hook, 'aqualuxe-options' ) === false ) {
			return;
		}

		// Admin styles
		wp_enqueue_style(
			'aqualuxe-admin-styles',
			AQUALUXE_URI . 'assets/css/admin/theme-options.css',
			array(),
			AQUALUXE_VERSION
		);

		// Color picker
		wp_enqueue_style( 'wp-color-picker' );
		
		// Media uploader
		wp_enqueue_media();

		// Admin scripts
		wp_enqueue_script(
			'aqualuxe-admin-scripts',
			AQUALUXE_URI . 'assets/js/admin/theme-options.js',
			array( 'jquery', 'wp-color-picker', 'jquery-ui-sortable' ),
			AQUALUXE_VERSION,
			true
		);

		wp_localize_script(
			'aqualuxe-admin-scripts',
			'aqualuxe_admin',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'aqualuxe-admin-nonce' ),
				'i18n'     => array(
					'select_image'  => __( 'Select Image', 'aqualuxe' ),
					'use_image'     => __( 'Use This Image', 'aqualuxe' ),
					'remove_image'  => __( 'Remove Image', 'aqualuxe' ),
					'add_item'      => __( 'Add Item', 'aqualuxe' ),
					'remove_item'   => __( 'Remove Item', 'aqualuxe' ),
					'confirm_reset' => __( 'Are you sure you want to reset all options to default values?', 'aqualuxe' ),
				),
			)
		);
	}

	/**
	 * Render options page
	 */
	public function render_options_page() {
		?>
		<div class="wrap aqualuxe-options-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="aqualuxe-options-header">
				<div class="aqualuxe-options-logo">
					<img src="<?php echo esc_url( AQUALUXE_URI . 'assets/images/admin/aqualuxe-logo.png' ); ?>" alt="AquaLuxe">
				</div>
				<div class="aqualuxe-options-version">
					<?php printf( esc_html__( 'Version: %s', 'aqualuxe' ), AQUALUXE_VERSION ); ?>
				</div>
			</div>

			<div class="aqualuxe-options-container">
				<form method="post" action="options.php" class="aqualuxe-options-form">
					<?php
					settings_fields( $this->option_group );
					do_settings_sections( 'aqualuxe-options' );
					submit_button();
					?>
					<button type="button" class="button aqualuxe-reset-options">
						<?php esc_html_e( 'Reset to Defaults', 'aqualuxe' ); ?>
					</button>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render homepage options
	 */
	public function render_homepage_options() {
		?>
		<div class="wrap aqualuxe-options-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="aqualuxe-options-container">
				<form method="post" action="options.php" class="aqualuxe-options-form">
					<?php
					settings_fields( $this->option_group . '_homepage' );
					do_settings_sections( 'aqualuxe-options-homepage' );
					submit_button();
					?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render about options
	 */
	public function render_about_options() {
		?>
		<div class="wrap aqualuxe-options-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="aqualuxe-options-container">
				<form method="post" action="options.php" class="aqualuxe-options-form">
					<?php
					settings_fields( $this->option_group . '_about' );
					do_settings_sections( 'aqualuxe-options-about' );
					submit_button();
					?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render shop options
	 */
	public function render_shop_options() {
		?>
		<div class="wrap aqualuxe-options-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="aqualuxe-options-container">
				<form method="post" action="options.php" class="aqualuxe-options-form">
					<?php
					settings_fields( $this->option_group . '_shop' );
					do_settings_sections( 'aqualuxe-options-shop' );
					submit_button();
					?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render species options
	 */
	public function render_species_options() {
		?>
		<div class="wrap aqualuxe-options-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="aqualuxe-options-container">
				<form method="post" action="options.php" class="aqualuxe-options-form">
					<?php
					settings_fields( $this->option_group . '_species' );
					do_settings_sections( 'aqualuxe-options-species' );
					submit_button();
					?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render services options
	 */
	public function render_services_options() {
		?>
		<div class="wrap aqualuxe-options-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="aqualuxe-options-container">
				<form method="post" action="options.php" class="aqualuxe-options-form">
					<?php
					settings_fields( $this->option_group . '_services' );
					do_settings_sections( 'aqualuxe-options-services' );
					submit_button();
					?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render pages options
	 */
	public function render_pages_options() {
		?>
		<div class="wrap aqualuxe-options-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="aqualuxe-options-container">
				<form method="post" action="options.php" class="aqualuxe-options-form">
					<?php
					settings_fields( $this->option_group . '_pages' );
					do_settings_sections( 'aqualuxe-options-pages' );
					submit_button();
					?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render currency options
	 */
	public function render_currency_options() {
		?>
		<div class="wrap aqualuxe-options-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="aqualuxe-options-container">
				<form method="post" action="options.php" class="aqualuxe-options-form">
					<?php
					settings_fields( $this->option_group . '_currency' );
					do_settings_sections( 'aqualuxe-options-currency' );
					submit_button();
					?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render shipping options
	 */
	public function render_shipping_options() {
		?>
		<div class="wrap aqualuxe-options-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="aqualuxe-options-container">
				<form method="post" action="options.php" class="aqualuxe-options-form">
					<?php
					settings_fields( $this->option_group . '_shipping' );
					do_settings_sections( 'aqualuxe-options-shipping' );
					submit_button();
					?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * Render import/export options
	 */
	public function render_import_export_options() {
		?>
		<div class="wrap aqualuxe-options-wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			
			<div class="aqualuxe-options-container">
				<div class="aqualuxe-import-export-container">
					<div class="aqualuxe-export-section">
						<h2><?php esc_html_e( 'Export Settings', 'aqualuxe' ); ?></h2>
						<p><?php esc_html_e( 'Export your theme settings for backup or transfer to another site.', 'aqualuxe' ); ?></p>
						<form method="post">
							<?php wp_nonce_field( 'aqualuxe_export_nonce', 'aqualuxe_export_nonce' ); ?>
							<input type="hidden" name="aqualuxe_action" value="export_settings" />
							<?php submit_button( __( 'Export Settings', 'aqualuxe' ), 'primary', 'submit', false ); ?>
						</form>
					</div>

					<div class="aqualuxe-import-section">
						<h2><?php esc_html_e( 'Import Settings', 'aqualuxe' ); ?></h2>
						<p><?php esc_html_e( 'Import theme settings from a JSON file.', 'aqualuxe' ); ?></p>
						<form method="post" enctype="multipart/form-data">
							<?php wp_nonce_field( 'aqualuxe_import_nonce', 'aqualuxe_import_nonce' ); ?>
							<input type="file" name="aqualuxe_import_file" />
							<input type="hidden" name="aqualuxe_action" value="import_settings" />
							<?php submit_button( __( 'Import Settings', 'aqualuxe' ), 'primary', 'submit', false ); ?>
						</form>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Render general section
	 */
	public function render_general_section() {
		echo '<p>' . esc_html__( 'Configure general theme settings including header, footer, colors, and typography.', 'aqualuxe' ) . '</p>';
	}

	/**
	 * Render header layout field
	 */
	public function render_header_layout_field() {
		$header_layout = isset( $this->options['header_layout'] ) ? $this->options['header_layout'] : 'default';
		?>
		<select name="<?php echo esc_attr( $this->option_name ); ?>[header_layout]" id="header_layout">
			<option value="default" <?php selected( $header_layout, 'default' ); ?>><?php esc_html_e( 'Default', 'aqualuxe' ); ?></option>
			<option value="centered" <?php selected( $header_layout, 'centered' ); ?>><?php esc_html_e( 'Centered', 'aqualuxe' ); ?></option>
			<option value="split" <?php selected( $header_layout, 'split' ); ?>><?php esc_html_e( 'Split', 'aqualuxe' ); ?></option>
			<option value="minimal" <?php selected( $header_layout, 'minimal' ); ?>><?php esc_html_e( 'Minimal', 'aqualuxe' ); ?></option>
		</select>
		<p class="description"><?php esc_html_e( 'Select the layout style for the site header.', 'aqualuxe' ); ?></p>
		<?php
	}

	/**
	 * Render logo position field
	 */
	public function render_logo_position_field() {
		$logo_position = isset( $this->options['logo_position'] ) ? $this->options['logo_position'] : 'left';
		?>
		<select name="<?php echo esc_attr( $this->option_name ); ?>[logo_position]" id="logo_position">
			<option value="left" <?php selected( $logo_position, 'left' ); ?>><?php esc_html_e( 'Left', 'aqualuxe' ); ?></option>
			<option value="center" <?php selected( $logo_position, 'center' ); ?>><?php esc_html_e( 'Center', 'aqualuxe' ); ?></option>
			<option value="right" <?php selected( $logo_position, 'right' ); ?>><?php esc_html_e( 'Right', 'aqualuxe' ); ?></option>
		</select>
		<p class="description"><?php esc_html_e( 'Select the position of the logo in the header.', 'aqualuxe' ); ?></p>
		<?php
	}

	/**
	 * Render sticky header field
	 */
	public function render_sticky_header_field() {
		$sticky_header = isset( $this->options['sticky_header'] ) ? $this->options['sticky_header'] : true;
		?>
		<label for="sticky_header">
			<input type="checkbox" name="<?php echo esc_attr( $this->option_name ); ?>[sticky_header]" id="sticky_header" value="1" <?php checked( $sticky_header ); ?> />
			<?php esc_html_e( 'Enable sticky header', 'aqualuxe' ); ?>
		</label>
		<p class="description"><?php esc_html_e( 'Keep the header visible when scrolling down the page.', 'aqualuxe' ); ?></p>
		<?php
	}

	/**
	 * Render footer columns field
	 */
	public function render_footer_columns_field() {
		$footer_columns = isset( $this->options['footer_columns'] ) ? $this->options['footer_columns'] : 4;
		?>
		<select name="<?php echo esc_attr( $this->option_name ); ?>[footer_columns]" id="footer_columns">
			<option value="1" <?php selected( $footer_columns, 1 ); ?>><?php esc_html_e( '1 Column', 'aqualuxe' ); ?></option>
			<option value="2" <?php selected( $footer_columns, 2 ); ?>><?php esc_html_e( '2 Columns', 'aqualuxe' ); ?></option>
			<option value="3" <?php selected( $footer_columns, 3 ); ?>><?php esc_html_e( '3 Columns', 'aqualuxe' ); ?></option>
			<option value="4" <?php selected( $footer_columns, 4 ); ?>><?php esc_html_e( '4 Columns', 'aqualuxe' ); ?></option>
		</select>
		<p class="description"><?php esc_html_e( 'Select the number of columns in the footer widget area.', 'aqualuxe' ); ?></p>
		<?php
	}

	/**
	 * Render footer copyright field
	 */
	public function render_footer_copyright_field() {
		$footer_copyright = isset( $this->options['footer_copyright'] ) ? $this->options['footer_copyright'] : '&copy; ' . date( 'Y' ) . ' AquaLuxe. All rights reserved.';
		?>
		<textarea name="<?php echo esc_attr( $this->option_name ); ?>[footer_copyright]" id="footer_copyright" rows="3" class="large-text"><?php echo esc_textarea( $footer_copyright ); ?></textarea>
		<p class="description"><?php esc_html_e( 'Enter the copyright text for the footer. Use {year} to dynamically insert the current year.', 'aqualuxe' ); ?></p>
		<?php
	}

	/**
	 * Render social media field
	 */
	public function render_social_media_field() {
		$social_media = isset( $this->options['social_media'] ) ? $this->options['social_media'] : array(
			'facebook'  => '',
			'twitter'   => '',
			'instagram' => '',
			'youtube'   => '',
			'pinterest' => '',
		);
		?>
		<div class="aqualuxe-social-media-fields">
			<div class="aqualuxe-social-media-field">
				<label for="social_media_facebook">
					<span class="dashicons dashicons-facebook"></span>
					<?php esc_html_e( 'Facebook', 'aqualuxe' ); ?>
				</label>
				<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[social_media][facebook]" id="social_media_facebook" value="<?php echo esc_url( $social_media['facebook'] ); ?>" class="regular-text" placeholder="https://facebook.com/yourusername" />
			</div>

			<div class="aqualuxe-social-media-field">
				<label for="social_media_twitter">
					<span class="dashicons dashicons-twitter"></span>
					<?php esc_html_e( 'Twitter', 'aqualuxe' ); ?>
				</label>
				<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[social_media][twitter]" id="social_media_twitter" value="<?php echo esc_url( $social_media['twitter'] ); ?>" class="regular-text" placeholder="https://twitter.com/yourusername" />
			</div>

			<div class="aqualuxe-social-media-field">
				<label for="social_media_instagram">
					<span class="dashicons dashicons-instagram"></span>
					<?php esc_html_e( 'Instagram', 'aqualuxe' ); ?>
				</label>
				<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[social_media][instagram]" id="social_media_instagram" value="<?php echo esc_url( $social_media['instagram'] ); ?>" class="regular-text" placeholder="https://instagram.com/yourusername" />
			</div>

			<div class="aqualuxe-social-media-field">
				<label for="social_media_youtube">
					<span class="dashicons dashicons-youtube"></span>
					<?php esc_html_e( 'YouTube', 'aqualuxe' ); ?>
				</label>
				<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[social_media][youtube]" id="social_media_youtube" value="<?php echo esc_url( $social_media['youtube'] ); ?>" class="regular-text" placeholder="https://youtube.com/yourchannel" />
			</div>

			<div class="aqualuxe-social-media-field">
				<label for="social_media_pinterest">
					<span class="dashicons dashicons-pinterest"></span>
					<?php esc_html_e( 'Pinterest', 'aqualuxe' ); ?>
				</label>
				<input type="url" name="<?php echo esc_attr( $this->option_name ); ?>[social_media][pinterest]" id="social_media_pinterest" value="<?php echo esc_url( $social_media['pinterest'] ); ?>" class="regular-text" placeholder="https://pinterest.com/yourusername" />
			</div>
		</div>
		<p class="description"><?php esc_html_e( 'Enter your social media profile URLs.', 'aqualuxe' ); ?></p>
		<?php
	}

	/**
	 * Render color scheme field
	 */
	public function render_color_scheme_field() {
		$color_scheme = isset( $this->options['color_scheme'] ) ? $this->options['color_scheme'] : array(
			'primary'   => '#0073aa',
			'secondary' => '#00a0d2',
			'accent'    => '#00c1b6',
			'text'      => '#333333',
			'heading'   => '#222222',
			'background' => '#ffffff',
		);
		?>
		<div class="aqualuxe-color-scheme-fields">
			<div class="aqualuxe-color-field">
				<label for="color_scheme_primary"><?php esc_html_e( 'Primary Color', 'aqualuxe' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[color_scheme][primary]" id="color_scheme_primary" value="<?php echo esc_attr( $color_scheme['primary'] ); ?>" class="aqualuxe-color-picker" data-default-color="#0073aa" />
			</div>

			<div class="aqualuxe-color-field">
				<label for="color_scheme_secondary"><?php esc_html_e( 'Secondary Color', 'aqualuxe' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[color_scheme][secondary]" id="color_scheme_secondary" value="<?php echo esc_attr( $color_scheme['secondary'] ); ?>" class="aqualuxe-color-picker" data-default-color="#00a0d2" />
			</div>

			<div class="aqualuxe-color-field">
				<label for="color_scheme_accent"><?php esc_html_e( 'Accent Color', 'aqualuxe' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[color_scheme][accent]" id="color_scheme_accent" value="<?php echo esc_attr( $color_scheme['accent'] ); ?>" class="aqualuxe-color-picker" data-default-color="#00c1b6" />
			</div>

			<div class="aqualuxe-color-field">
				<label for="color_scheme_text"><?php esc_html_e( 'Text Color', 'aqualuxe' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[color_scheme][text]" id="color_scheme_text" value="<?php echo esc_attr( $color_scheme['text'] ); ?>" class="aqualuxe-color-picker" data-default-color="#333333" />
			</div>

			<div class="aqualuxe-color-field">
				<label for="color_scheme_heading"><?php esc_html_e( 'Heading Color', 'aqualuxe' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[color_scheme][heading]" id="color_scheme_heading" value="<?php echo esc_attr( $color_scheme['heading'] ); ?>" class="aqualuxe-color-picker" data-default-color="#222222" />
			</div>

			<div class="aqualuxe-color-field">
				<label for="color_scheme_background"><?php esc_html_e( 'Background Color', 'aqualuxe' ); ?></label>
				<input type="text" name="<?php echo esc_attr( $this->option_name ); ?>[color_scheme][background]" id="color_scheme_background" value="<?php echo esc_attr( $color_scheme['background'] ); ?>" class="aqualuxe-color-picker" data-default-color="#ffffff" />
			</div>
		</div>
		<p class="description"><?php esc_html_e( 'Select colors for your theme.', 'aqualuxe' ); ?></p>
		<?php
	}

	/**
	 * Render typography field
	 */
	public function render_typography_field() {
		$typography = isset( $this->options['typography'] ) ? $this->options['typography'] : array(
			'body_font'     => 'Roboto',
			'heading_font'  => 'Montserrat',
			'base_size'     => '16',
			'line_height'   => '1.6',
			'heading_weight' => '600',
		);

		$google_fonts = array(
			'Roboto'       => 'Roboto',
			'Open Sans'    => 'Open Sans',
			'Lato'         => 'Lato',
			'Montserrat'   => 'Montserrat',
			'Raleway'      => 'Raleway',
			'Poppins'      => 'Poppins',
			'Nunito'       => 'Nunito',
			'Playfair Display' => 'Playfair Display',
			'Merriweather' => 'Merriweather',
			'Source Sans Pro' => 'Source Sans Pro',
		);
		?>
		<div class="aqualuxe-typography-fields">
			<div class="aqualuxe-typography-field">
				<label for="typography_body_font"><?php esc_html_e( 'Body Font', 'aqualuxe' ); ?></label>
				<select name="<?php echo esc_attr( $this->option_name ); ?>[typography][body_font]" id="typography_body_font">
					<?php foreach ( $google_fonts as $font_name => $font_value ) : ?>
						<option value="<?php echo esc_attr( $font_value ); ?>" <?php selected( $typography['body_font'], $font_value ); ?>><?php echo esc_html( $font_name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="aqualuxe-typography-field">
				<label for="typography_heading_font"><?php esc_html_e( 'Heading Font', 'aqualuxe' ); ?></label>
				<select name="<?php echo esc_attr( $this->option_name ); ?>[typography][heading_font]" id="typography_heading_font">
					<?php foreach ( $google_fonts as $font_name => $font_value ) : ?>
						<option value="<?php echo esc_attr( $font_value ); ?>" <?php selected( $typography['heading_font'], $font_value ); ?>><?php echo esc_html( $font_name ); ?></option>
					<?php endforeach; ?>
				</select>
			</div>

			<div class="aqualuxe-typography-field">
				<label for="typography_base_size"><?php esc_html_e( 'Base Font Size (px)', 'aqualuxe' ); ?></label>
				<input type="number" name="<?php echo esc_attr( $this->option_name ); ?>[typography][base_size]" id="typography_base_size" value="<?php echo esc_attr( $typography['base_size'] ); ?>" min="12" max="24" step="1" />
			</div>

			<div class="aqualuxe-typography-field">
				<label for="typography_line_height"><?php esc_html_e( 'Line Height', 'aqualuxe' ); ?></label>
				<input type="number" name="<?php echo esc_attr( $this->option_name ); ?>[typography][line_height]" id="typography_line_height" value="<?php echo esc_attr( $typography['line_height'] ); ?>" min="1" max="2" step="0.1" />
			</div>

			<div class="aqualuxe-typography-field">
				<label for="typography_heading_weight"><?php esc_html_e( 'Heading Font Weight', 'aqualuxe' ); ?></label>
				<select name="<?php echo esc_attr( $this->option_name ); ?>[typography][heading_weight]" id="typography_heading_weight">
					<option value="400" <?php selected( $typography['heading_weight'], '400' ); ?>><?php esc_html_e( 'Regular (400)', 'aqualuxe' ); ?></option>
					<option value="500" <?php selected( $typography['heading_weight'], '500' ); ?>><?php esc_html_e( 'Medium (500)', 'aqualuxe' ); ?></option>
					<option value="600" <?php selected( $typography['heading_weight'], '600' ); ?>><?php esc_html_e( 'Semi-Bold (600)', 'aqualuxe' ); ?></option>
					<option value="700" <?php selected( $typography['heading_weight'], '700' ); ?>><?php esc_html_e( 'Bold (700)', 'aqualuxe' ); ?></option>
				</select>
			</div>
		</div>
		<p class="description"><?php esc_html_e( 'Configure typography settings for your theme.', 'aqualuxe' ); ?></p>
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
AquaLuxe_Theme_Options::instance();