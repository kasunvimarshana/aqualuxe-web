<?php
/**
 * AquaLuxe Admin Page
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * AquaLuxe Admin Page Class
 */
class AquaLuxe_Admin_Page {

	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
		add_action( 'admin_init', array( $this, 'redirect_to_welcome_page' ) );
	}

	/**
	 * Add admin menu
	 */
	public function add_admin_menu() {
		add_theme_page(
			esc_html__( 'AquaLuxe Theme', 'aqualuxe' ),
			esc_html__( 'AquaLuxe Theme', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-theme',
			array( $this, 'create_admin_page' )
		);
	}

	/**
	 * Enqueue admin scripts
	 */
	public function enqueue_admin_scripts( $hook ) {
		if ( 'appearance_page_aqualuxe-theme' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'aqualuxe-admin-style', get_template_directory_uri() . '/assets/css/admin.css', array(), AQUALUXE_VERSION );
		wp_enqueue_script( 'aqualuxe-admin-script', get_template_directory_uri() . '/assets/js/admin.js', array( 'jquery' ), AQUALUXE_VERSION, true );
	}

	/**
	 * Redirect to welcome page after theme activation
	 */
	public function redirect_to_welcome_page() {
		global $pagenow;

		if ( isset( $_GET['activated'] ) && 'themes.php' === $pagenow ) {
			wp_safe_redirect( admin_url( 'themes.php?page=aqualuxe-theme' ) );
			exit;
		}
	}

	/**
	 * Create admin page
	 */
	public function create_admin_page() {
		?>
		<div class="wrap aqualuxe-admin-wrap">
			<div class="aqualuxe-admin-header">
				<div class="aqualuxe-admin-logo">
					<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/aqualuxe-logo.png' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Theme', 'aqualuxe' ); ?>">
				</div>
				<h1><?php esc_html_e( 'Welcome to AquaLuxe Theme', 'aqualuxe' ); ?></h1>
				<p class="aqualuxe-admin-version">
					<?php
					/* translators: %s: Theme version */
					printf( esc_html__( 'Version: %s', 'aqualuxe' ), AQUALUXE_VERSION );
					?>
				</p>
			</div>

			<div class="aqualuxe-admin-content">
				<div class="aqualuxe-admin-tabs">
					<nav class="nav-tab-wrapper">
						<a href="#welcome" class="nav-tab nav-tab-active"><?php esc_html_e( 'Welcome', 'aqualuxe' ); ?></a>
						<a href="#customize" class="nav-tab"><?php esc_html_e( 'Customize', 'aqualuxe' ); ?></a>
						<a href="#plugins" class="nav-tab"><?php esc_html_e( 'Plugins', 'aqualuxe' ); ?></a>
						<a href="#demo" class="nav-tab"><?php esc_html_e( 'Demo Import', 'aqualuxe' ); ?></a>
						<a href="#support" class="nav-tab"><?php esc_html_e( 'Support', 'aqualuxe' ); ?></a>
					</nav>

					<div class="aqualuxe-admin-tab-content">
						<!-- Welcome Tab -->
						<div id="welcome" class="aqualuxe-admin-tab-pane active">
							<div class="aqualuxe-admin-tab-pane-half">
								<h2><?php esc_html_e( 'Thank you for choosing AquaLuxe!', 'aqualuxe' ); ?></h2>
								<p><?php esc_html_e( 'AquaLuxe is a premium WordPress theme designed specifically for high-end ornamental fish farming businesses and aquarium shops. With its elegant design and powerful features, AquaLuxe helps you create a stunning online presence for your aquatic business.', 'aqualuxe' ); ?></p>
								<p><?php esc_html_e( 'This admin panel will help you get started with the theme and provide quick access to all its features and settings.', 'aqualuxe' ); ?></p>

								<h3><?php esc_html_e( 'Getting Started', 'aqualuxe' ); ?></h3>
								<ul class="aqualuxe-admin-list">
									<li>
										<a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" class="button button-primary">
											<?php esc_html_e( 'Customize Your Site', 'aqualuxe' ); ?>
										</a>
										<p><?php esc_html_e( 'Use the WordPress Customizer to personalize your site\'s appearance and settings.', 'aqualuxe' ); ?></p>
									</li>
									<li>
										<a href="<?php echo esc_url( admin_url( 'edit.php?post_type=page' ) ); ?>" class="button button-secondary">
											<?php esc_html_e( 'Edit Pages', 'aqualuxe' ); ?>
										</a>
										<p><?php esc_html_e( 'Create and edit your website\'s pages to add your content.', 'aqualuxe' ); ?></p>
									</li>
									<li>
										<a href="<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>" class="button button-secondary">
											<?php esc_html_e( 'Set Up Menus', 'aqualuxe' ); ?>
										</a>
										<p><?php esc_html_e( 'Create and manage your site\'s navigation menus.', 'aqualuxe' ); ?></p>
									</li>
								</ul>
							</div>

							<div class="aqualuxe-admin-tab-pane-half">
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/aqualuxe-preview.jpg' ); ?>" alt="<?php esc_attr_e( 'AquaLuxe Theme Preview', 'aqualuxe' ); ?>">
								
								<h3><?php esc_html_e( 'Theme Features', 'aqualuxe' ); ?></h3>
								<ul class="aqualuxe-admin-features">
									<li>
										<span class="dashicons dashicons-admin-customizer"></span>
										<?php esc_html_e( 'Powerful Customizer Options', 'aqualuxe' ); ?>
									</li>
									<li>
										<span class="dashicons dashicons-cart"></span>
										<?php esc_html_e( 'WooCommerce Integration', 'aqualuxe' ); ?>
									</li>
									<li>
										<span class="dashicons dashicons-smartphone"></span>
										<?php esc_html_e( 'Fully Responsive Design', 'aqualuxe' ); ?>
									</li>
									<li>
										<span class="dashicons dashicons-translation"></span>
										<?php esc_html_e( 'Multilingual Support', 'aqualuxe' ); ?>
									</li>
									<li>
										<span class="dashicons dashicons-admin-appearance"></span>
										<?php esc_html_e( 'Dark Mode Support', 'aqualuxe' ); ?>
									</li>
									<li>
										<span class="dashicons dashicons-performance"></span>
										<?php esc_html_e( 'Performance Optimized', 'aqualuxe' ); ?>
									</li>
								</ul>
							</div>
						</div>

						<!-- Customize Tab -->
						<div id="customize" class="aqualuxe-admin-tab-pane">
							<h2><?php esc_html_e( 'Customize Your Theme', 'aqualuxe' ); ?></h2>
							<p><?php esc_html_e( 'AquaLuxe comes with extensive customization options that allow you to tailor your site to your specific needs. Use the WordPress Customizer to modify these settings.', 'aqualuxe' ); ?></p>

							<div class="aqualuxe-admin-grid">
								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-admin-generic"></span>
										<h3><?php esc_html_e( 'General Settings', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Configure general theme settings like layout, container width, and more.', 'aqualuxe' ); ?></p>
										<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=aqualuxe_general_settings' ) ); ?>" class="button button-primary">
											<?php esc_html_e( 'Customize', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>

								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-menu"></span>
										<h3><?php esc_html_e( 'Header Settings', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Customize your site\'s header, including logo, navigation, and topbar options.', 'aqualuxe' ); ?></p>
										<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=aqualuxe_header_settings' ) ); ?>" class="button button-primary">
											<?php esc_html_e( 'Customize', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>

								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-admin-home"></span>
										<h3><?php esc_html_e( 'Homepage Settings', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Configure your homepage layout and content sections.', 'aqualuxe' ); ?></p>
										<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=aqualuxe_homepage_settings' ) ); ?>" class="button button-primary">
											<?php esc_html_e( 'Customize', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>

								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-admin-appearance"></span>
										<h3><?php esc_html_e( 'Colors & Typography', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Customize your site\'s colors and typography to match your brand.', 'aqualuxe' ); ?></p>
										<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=aqualuxe_colors_settings' ) ); ?>" class="button button-primary">
											<?php esc_html_e( 'Customize', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>

								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-cart"></span>
										<h3><?php esc_html_e( 'WooCommerce Settings', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Customize your online store\'s appearance and functionality.', 'aqualuxe' ); ?></p>
										<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=aqualuxe_woocommerce_settings' ) ); ?>" class="button button-primary">
											<?php esc_html_e( 'Customize', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>

								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-admin-site-alt3"></span>
										<h3><?php esc_html_e( 'Footer Settings', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Configure your site\'s footer layout, widgets, and copyright information.', 'aqualuxe' ); ?></p>
										<a href="<?php echo esc_url( admin_url( 'customize.php?autofocus[section]=aqualuxe_footer_settings' ) ); ?>" class="button button-primary">
											<?php esc_html_e( 'Customize', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>
							</div>
						</div>

						<!-- Plugins Tab -->
						<div id="plugins" class="aqualuxe-admin-tab-pane">
							<h2><?php esc_html_e( 'Recommended Plugins', 'aqualuxe' ); ?></h2>
							<p><?php esc_html_e( 'Enhance your AquaLuxe theme with these recommended plugins. These plugins work seamlessly with AquaLuxe to extend its functionality.', 'aqualuxe' ); ?></p>

							<div class="aqualuxe-admin-plugins">
								<?php
								$recommended_plugins = array(
									array(
										'name'        => 'WooCommerce',
										'slug'        => 'woocommerce',
										'description' => esc_html__( 'The most popular eCommerce platform for selling your products online.', 'aqualuxe' ),
										'icon'        => 'dashicons-cart',
									),
									array(
										'name'        => 'Elementor Page Builder',
										'slug'        => 'elementor',
										'description' => esc_html__( 'Create beautiful, responsive designs with the drag & drop Elementor page builder.', 'aqualuxe' ),
										'icon'        => 'dashicons-editor-kitchensink',
									),
									array(
										'name'        => 'Contact Form 7',
										'slug'        => 'contact-form-7',
										'description' => esc_html__( 'Create customizable contact forms for your website.', 'aqualuxe' ),
										'icon'        => 'dashicons-email-alt',
									),
									array(
										'name'        => 'Yoast SEO',
										'slug'        => 'wordpress-seo',
										'description' => esc_html__( 'Improve your website\'s SEO rankings and readability.', 'aqualuxe' ),
										'icon'        => 'dashicons-chart-line',
									),
									array(
										'name'        => 'WP Rocket',
										'slug'        => 'wp-rocket',
										'description' => esc_html__( 'Speed up your website with advanced caching and optimization features.', 'aqualuxe' ),
										'icon'        => 'dashicons-performance',
									),
									array(
										'name'        => 'WPML',
										'slug'        => 'sitepress-multilingual-cms',
										'description' => esc_html__( 'Make your website multilingual to reach a global audience.', 'aqualuxe' ),
										'icon'        => 'dashicons-translation',
									),
								);

								foreach ( $recommended_plugins as $plugin ) {
									$is_installed = $this->is_plugin_installed( $plugin['slug'] );
									$is_active    = $this->is_plugin_active( $plugin['slug'] );
									$button_class = $is_active ? 'button-disabled' : 'button-primary';
									$button_text  = $is_active ? esc_html__( 'Active', 'aqualuxe' ) : ( $is_installed ? esc_html__( 'Activate', 'aqualuxe' ) : esc_html__( 'Install', 'aqualuxe' ) );
									$button_link  = $is_active ? '#' : ( $is_installed ? $this->get_activation_url( $plugin['slug'] ) : $this->get_install_url( $plugin['slug'] ) );
									?>
									<div class="aqualuxe-admin-plugin-card">
										<div class="aqualuxe-admin-plugin-card-header">
											<span class="dashicons <?php echo esc_attr( $plugin['icon'] ); ?>"></span>
											<h3><?php echo esc_html( $plugin['name'] ); ?></h3>
											<?php if ( $is_active ) : ?>
												<span class="aqualuxe-admin-plugin-status active"><?php esc_html_e( 'Active', 'aqualuxe' ); ?></span>
											<?php elseif ( $is_installed ) : ?>
												<span class="aqualuxe-admin-plugin-status installed"><?php esc_html_e( 'Installed', 'aqualuxe' ); ?></span>
											<?php else : ?>
												<span class="aqualuxe-admin-plugin-status not-installed"><?php esc_html_e( 'Not Installed', 'aqualuxe' ); ?></span>
											<?php endif; ?>
										</div>
										<div class="aqualuxe-admin-plugin-card-content">
											<p><?php echo esc_html( $plugin['description'] ); ?></p>
										</div>
										<div class="aqualuxe-admin-plugin-card-footer">
											<a href="<?php echo esc_url( $button_link ); ?>" class="button <?php echo esc_attr( $button_class ); ?>">
												<?php echo esc_html( $button_text ); ?>
											</a>
										</div>
									</div>
									<?php
								}
								?>
							</div>
						</div>

						<!-- Demo Import Tab -->
						<div id="demo" class="aqualuxe-admin-tab-pane">
							<h2><?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?></h2>
							<p><?php esc_html_e( 'Get started quickly by importing our demo content. This will set up your site with pre-configured pages, posts, menus, widgets, and customizer settings to match our demo site.', 'aqualuxe' ); ?></p>

							<div class="aqualuxe-admin-demo-content">
								<div class="aqualuxe-admin-demo-content-preview">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/demo-preview.jpg' ); ?>" alt="<?php esc_attr_e( 'Demo Preview', 'aqualuxe' ); ?>">
								</div>
								<div class="aqualuxe-admin-demo-content-info">
									<h3><?php esc_html_e( 'What will be imported?', 'aqualuxe' ); ?></h3>
									<ul>
										<li><?php esc_html_e( 'Pages (Home, About, Services, Contact, etc.)', 'aqualuxe' ); ?></li>
										<li><?php esc_html_e( 'Posts with sample content', 'aqualuxe' ); ?></li>
										<li><?php esc_html_e( 'Navigation menus', 'aqualuxe' ); ?></li>
										<li><?php esc_html_e( 'Widgets in sidebars and footer', 'aqualuxe' ); ?></li>
										<li><?php esc_html_e( 'Customizer settings', 'aqualuxe' ); ?></li>
										<li><?php esc_html_e( 'Sample products (if WooCommerce is active)', 'aqualuxe' ); ?></li>
									</ul>

									<div class="aqualuxe-admin-demo-content-notice">
										<p><strong><?php esc_html_e( 'Important:', 'aqualuxe' ); ?></strong> <?php esc_html_e( 'We recommend importing demo content on a fresh WordPress installation to avoid conflicts with your existing content. The import process may take several minutes to complete.', 'aqualuxe' ); ?></p>
									</div>

									<div class="aqualuxe-admin-demo-content-actions">
										<?php if ( class_exists( 'AquaLuxe_Demo_Importer' ) ) : ?>
											<a href="<?php echo esc_url( admin_url( 'themes.php?page=aqualuxe-demo-import' ) ); ?>" class="button button-primary">
												<?php esc_html_e( 'Import Demo Content', 'aqualuxe' ); ?>
											</a>
										<?php else : ?>
											<p><?php esc_html_e( 'To import demo content, you need to install and activate the AquaLuxe Demo Importer plugin.', 'aqualuxe' ); ?></p>
											<a href="<?php echo esc_url( $this->get_install_url( 'aqualuxe-demo-importer' ) ); ?>" class="button button-primary">
												<?php esc_html_e( 'Install Demo Importer Plugin', 'aqualuxe' ); ?>
											</a>
										<?php endif; ?>
									</div>
								</div>
							</div>
						</div>

						<!-- Support Tab -->
						<div id="support" class="aqualuxe-admin-tab-pane">
							<h2><?php esc_html_e( 'Support & Documentation', 'aqualuxe' ); ?></h2>
							<p><?php esc_html_e( 'Need help with AquaLuxe? Check out our documentation and support resources below.', 'aqualuxe' ); ?></p>

							<div class="aqualuxe-admin-grid">
								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-book"></span>
										<h3><?php esc_html_e( 'Documentation', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Comprehensive documentation with detailed instructions on how to use the theme.', 'aqualuxe' ); ?></p>
										<a href="https://aqualuxe.ninjatech.ai/documentation/" target="_blank" class="button button-primary">
											<?php esc_html_e( 'View Documentation', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>

								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-sos"></span>
										<h3><?php esc_html_e( 'Support Center', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Get help from our support team if you encounter any issues with the theme.', 'aqualuxe' ); ?></p>
										<a href="https://aqualuxe.ninjatech.ai/support/" target="_blank" class="button button-primary">
											<?php esc_html_e( 'Get Support', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>

								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-video-alt3"></span>
										<h3><?php esc_html_e( 'Video Tutorials', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Watch video tutorials to learn how to use various features of the theme.', 'aqualuxe' ); ?></p>
										<a href="https://aqualuxe.ninjatech.ai/tutorials/" target="_blank" class="button button-primary">
											<?php esc_html_e( 'Watch Tutorials', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>

								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-format-chat"></span>
										<h3><?php esc_html_e( 'Community Forum', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Join our community forum to connect with other users and get help.', 'aqualuxe' ); ?></p>
										<a href="https://aqualuxe.ninjatech.ai/forum/" target="_blank" class="button button-primary">
											<?php esc_html_e( 'Join Forum', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>

								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-admin-customizer"></span>
										<h3><?php esc_html_e( 'Customization Services', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'Need custom modifications? Our team can help customize the theme to your needs.', 'aqualuxe' ); ?></p>
										<a href="https://aqualuxe.ninjatech.ai/customization/" target="_blank" class="button button-primary">
											<?php esc_html_e( 'Request Customization', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>

								<div class="aqualuxe-admin-grid-item">
									<div class="aqualuxe-admin-grid-item-inner">
										<span class="dashicons dashicons-update"></span>
										<h3><?php esc_html_e( 'Changelog', 'aqualuxe' ); ?></h3>
										<p><?php esc_html_e( 'View the theme\'s changelog to see what\'s new in the latest version.', 'aqualuxe' ); ?></p>
										<a href="https://aqualuxe.ninjatech.ai/changelog/" target="_blank" class="button button-primary">
											<?php esc_html_e( 'View Changelog', 'aqualuxe' ); ?>
										</a>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Check if a plugin is installed
	 *
	 * @param string $slug Plugin slug.
	 * @return bool
	 */
	private function is_plugin_installed( $slug ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins = get_plugins();

		foreach ( $all_plugins as $plugin_path => $plugin_data ) {
			$plugin_file = basename( $plugin_path );
			$plugin_dir  = dirname( $plugin_path );

			if ( $plugin_dir === $slug || strpos( $plugin_file, $slug ) === 0 ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Check if a plugin is active
	 *
	 * @param string $slug Plugin slug.
	 * @return bool
	 */
	private function is_plugin_active( $slug ) {
		if ( ! function_exists( 'is_plugin_active' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins = get_plugins();

		foreach ( $all_plugins as $plugin_path => $plugin_data ) {
			$plugin_file = basename( $plugin_path );
			$plugin_dir  = dirname( $plugin_path );

			if ( ( $plugin_dir === $slug || strpos( $plugin_file, $slug ) === 0 ) && is_plugin_active( $plugin_path ) ) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Get plugin activation URL
	 *
	 * @param string $slug Plugin slug.
	 * @return string
	 */
	private function get_activation_url( $slug ) {
		if ( ! function_exists( 'get_plugins' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$all_plugins = get_plugins();
		$plugin_path = '';

		foreach ( $all_plugins as $path => $plugin_data ) {
			$plugin_file = basename( $path );
			$plugin_dir  = dirname( $path );

			if ( $plugin_dir === $slug || strpos( $plugin_file, $slug ) === 0 ) {
				$plugin_path = $path;
				break;
			}
		}

		if ( ! empty( $plugin_path ) ) {
			return wp_nonce_url( admin_url( 'plugins.php?action=activate&plugin=' . $plugin_path ), 'activate-plugin_' . $plugin_path );
		}

		return admin_url( 'plugin-install.php?tab=search&s=' . $slug );
	}

	/**
	 * Get plugin installation URL
	 *
	 * @param string $slug Plugin slug.
	 * @return string
	 */
	private function get_install_url( $slug ) {
		return wp_nonce_url( admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug );
	}
}

// Initialize the admin page
new AquaLuxe_Admin_Page();