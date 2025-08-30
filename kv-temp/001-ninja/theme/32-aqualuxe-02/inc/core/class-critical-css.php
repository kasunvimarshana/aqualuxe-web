<?php
/**
 * Critical CSS Handler
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Critical CSS Class
 *
 * Handles the generation and implementation of critical CSS for improved page load performance.
 * Critical CSS is the minimal CSS needed to render the above-the-fold content of a page.
 *
 * @since 1.1.0
 */
class Critical_CSS {

	/**
	 * Critical CSS for different page types.
	 *
	 * @var array
	 */
	private $critical_css = [];

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		$this->load_critical_css();
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'wp_head', array( $this, 'print_critical_css' ), 1 );
		add_filter( 'style_loader_tag', array( $this, 'add_preload_to_stylesheets' ), 10, 4 );
		add_action( 'wp_footer', array( $this, 'load_deferred_styles' ), 99 );
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	/**
	 * Load critical CSS for different page types.
	 *
	 * @return void
	 */
	private function load_critical_css() {
		// Define critical CSS file paths
		$device = wp_is_mobile() ? 'mobile' : 'desktop';
		$base_path = AQUALUXE_DIR . '/assets/css/critical/';
		
		$this->critical_css = array(
			'front-page' => file_exists($base_path . "front-page.{$device}.css") ? 
				file_get_contents($base_path . "front-page.{$device}.css") : '',
			'blog' => file_exists($base_path . "blog.{$device}.css") ? 
				file_get_contents($base_path . "blog.{$device}.css") : '',
			'woocommerce' => file_exists($base_path . "woocommerce.{$device}.css") ? 
				file_get_contents($base_path . "woocommerce.{$device}.css") : '',
			'woocommerce-single' => file_exists($base_path . "woocommerce-single.{$device}.css") ? 
				file_get_contents($base_path . "woocommerce-single.{$device}.css") : '',
		);

		// If critical CSS is not set, generate default critical CSS.
		if ( empty( $this->critical_css['front-page'] ) ) {
			$this->critical_css['front-page'] = $this->generate_default_critical_css();
		}
	}

	/**
	 * Generate default critical CSS.
	 *
	 * @return string Default critical CSS.
	 */
	private function generate_default_critical_css() {
		// Basic critical CSS for typography, layout, and colors.
		return '
			/* Basic Typography */
			:root {
				--primary-color: #0077b6;
				--secondary-color: #00b4d8;
				--accent-color: #90e0ef;
				--text-color: #333333;
				--light-text-color: #ffffff;
				--background-color: #ffffff;
				--light-background: #f8f9fa;
				--border-color: #e0e0e0;
				--font-family: "Poppins", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
				--heading-font-family: "Playfair Display", Georgia, serif;
			}
			body {
				font-family: var(--font-family);
				line-height: 1.5;
				color: var(--text-color);
				margin: 0;
				padding: 0;
				background-color: var(--background-color);
			}
			h1, h2, h3, h4, h5, h6 {
				font-family: var(--heading-font-family);
				margin-top: 0;
				margin-bottom: 0.5rem;
				font-weight: 700;
				line-height: 1.2;
				color: var(--text-color);
			}
			h1 {
				font-size: 2.5rem;
			}
			p {
				margin-top: 0;
				margin-bottom: 1rem;
			}
			a {
				color: var(--primary-color);
				text-decoration: none;
			}
			
			/* Layout */
			.container {
				width: 100%;
				max-width: 1200px;
				margin-left: auto;
				margin-right: auto;
				padding-left: 1rem;
				padding-right: 1rem;
			}
			.site-header {
				padding-top: 1rem;
				padding-bottom: 1rem;
				background-color: var(--background-color);
				box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
			}
			.site-branding {
				display: flex;
				align-items: center;
			}
			.site-title {
				font-size: 1.5rem;
				margin: 0;
			}
			
			/* Navigation */
			.main-navigation {
				display: flex;
				align-items: center;
			}
			.main-navigation ul {
				display: flex;
				list-style: none;
				margin: 0;
				padding: 0;
			}
			.main-navigation li {
				margin-right: 1.5rem;
			}
			
			/* Accessibility */
			.screen-reader-text {
				border: 0;
				clip: rect(1px, 1px, 1px, 1px);
				clip-path: inset(50%);
				height: 1px;
				margin: -1px;
				overflow: hidden;
				padding: 0;
				position: absolute !important;
				width: 1px;
				word-wrap: normal !important;
			}
			.skip-link {
				background-color: var(--primary-color);
				color: var(--light-text-color);
				font-weight: 700;
				left: 50%;
				padding: 10px;
				position: absolute;
				transform: translateY(-100%);
				transition: transform 0.3s;
				z-index: 100;
			}
			.skip-link:focus {
				transform: translateY(0%);
			}
		';
	}

	/**
	 * Print critical CSS in the head.
	 *
	 * @return void
	 */
	public function print_critical_css() {
		// Skip if critical CSS is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_critical_css', true ) ) {
			return;
		}

		// Get the appropriate critical CSS based on the current page.
		$css = $this->get_critical_css_for_current_page();

		// Print critical CSS.
		if ( ! empty( $css ) ) {
			echo '<style id="aqualuxe-critical-css">' . wp_strip_all_tags( $css ) . '</style>';
		}
	}

	/**
	 * Get critical CSS for the current page.
	 *
	 * @return string Critical CSS for the current page.
	 */
	private function get_critical_css_for_current_page() {
		// Determine which critical CSS to use based on the current page
		if ( is_front_page() ) {
			return $this->critical_css['front-page'];
		} elseif ( is_singular( 'post' ) || is_page() || is_archive() || is_search() || is_home() ) {
			return $this->critical_css['blog'];
		} elseif ( function_exists( 'is_product' ) && is_product() ) {
			return $this->critical_css['woocommerce-single'];
		} elseif ( function_exists( 'is_shop' ) && ( is_shop() || is_product_category() || is_product_tag() ) ) {
			return $this->critical_css['woocommerce'];
		}
		
		// Default to front page critical CSS
		return $this->critical_css['front-page'];
	}

	/**
	 * Add preload to stylesheets.
	 *
	 * @param string $html    The link tag for the enqueued style.
	 * @param string $handle  The style's registered handle.
	 * @param string $href    The stylesheet's source URL.
	 * @param string $media   The stylesheet's media attribute.
	 * @return string Modified link tag.
	 */
	public function add_preload_to_stylesheets( $html, $handle, $href, $media ) {
		// Skip if critical CSS is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_critical_css', true ) ) {
			return $html;
		}

		// Skip if this is not a main stylesheet.
		$main_styles = array( 'aqualuxe-style', 'aqualuxe-main', 'aqualuxe-woocommerce' );
		if ( ! in_array( $handle, $main_styles, true ) ) {
			return $html;
		}

		// Replace rel="stylesheet" with rel="preload" and add as="style" and onload attributes.
		$html = str_replace(
			'rel="stylesheet"',
			'rel="preload" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"',
			$html
		);

		// Add noscript fallback.
		$html .= '<noscript><link rel="stylesheet" href="' . esc_url( $href ) . '"></noscript>';

		return $html;
	}

	/**
	 * Load deferred styles in the footer.
	 *
	 * @return void
	 */
	public function load_deferred_styles() {
		// Skip if critical CSS is disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_critical_css', true ) ) {
			return;
		}

		// Add loadCSS polyfill.
		echo '<script>
			/* loadCSS polyfill */
			(function(w){"use strict";if(!w.loadCSS){w.loadCSS=function(){}}
			var rp=loadCSS.relpreload={};rp.support=(function(){var ret;try{ret=w.document.createElement("link").relList.supports("preload")}catch(e){ret=false}return function(){return ret}})();rp.bindMediaToggle=function(link){var finalMedia=link.media||"all";function enableStylesheet(){link.media=finalMedia}if(link.addEventListener){link.addEventListener("load",enableStylesheet)}else if(link.attachEvent){link.attachEvent("onload",enableStylesheet)}setTimeout(function(){link.rel="stylesheet";link.media="only x"});setTimeout(enableStylesheet,3000)};rp.poly=function(){if(rp.support()){return}var links=w.document.getElementsByTagName("link");for(var i=0;i<links.length;i++){var link=links[i];if(link.rel==="preload"&&link.getAttribute("as")==="style"&&!link.getAttribute("data-loadcss")){link.setAttribute("data-loadcss",true);rp.bindMediaToggle(link)}}};if(!rp.support()){rp.poly();var run=w.setInterval(rp.poly,500);if(w.addEventListener){w.addEventListener("load",function(){rp.poly();w.clearInterval(run)})}else if(w.attachEvent){w.attachEvent("onload",function(){rp.poly();w.clearInterval(run)})}}if(typeof exports!=="undefined"){exports.loadCSS=loadCSS}else{w.loadCSS=loadCSS}}(typeof global!=="undefined"?global:this));
		</script>';
	}

	/**
	 * Add admin menu for critical CSS.
	 *
	 * @return void
	 */
	public function add_admin_menu() {
		add_submenu_page(
			'themes.php',
			__( 'Critical CSS', 'aqualuxe' ),
			__( 'Critical CSS', 'aqualuxe' ),
			'manage_options',
			'aqualuxe-critical-css',
			array( $this, 'admin_page' )
		);
	}

	/**
	 * Register settings for critical CSS.
	 *
	 * @return void
	 */
	public function register_settings() {
		register_setting( 'aqualuxe_critical_css', 'aqualuxe_enable_critical_css', array(
			'type' => 'boolean',
			'default' => true,
		));
		
		register_setting( 'aqualuxe_critical_css', 'aqualuxe_critical_css_regenerate', array(
			'type' => 'boolean',
			'default' => false,
		));
	}

	/**
	 * Admin page for critical CSS.
	 *
	 * @return void
	 */
	public function admin_page() {
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Critical CSS', 'aqualuxe' ); ?></h1>
			<p><?php esc_html_e( 'Critical CSS is the minimal CSS needed to render the above-the-fold content of a page. It helps improve page load performance by inlining critical CSS in the head and deferring the loading of non-critical CSS.', 'aqualuxe' ); ?></p>
			
			<form method="post" action="options.php">
				<?php settings_fields( 'aqualuxe_critical_css' ); ?>
				<?php do_settings_sections( 'aqualuxe_critical_css' ); ?>
				
				<table class="form-table">
					<tr>
						<th scope="row"><?php esc_html_e( 'Enable Critical CSS', 'aqualuxe' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="aqualuxe_enable_critical_css" value="1" <?php checked( get_option( 'aqualuxe_enable_critical_css', true ) ); ?>>
								<?php esc_html_e( 'Enable Critical CSS for improved page load performance', 'aqualuxe' ); ?>
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php esc_html_e( 'Regenerate Critical CSS', 'aqualuxe' ); ?></th>
						<td>
							<label>
								<input type="checkbox" name="aqualuxe_critical_css_regenerate" value="1">
								<?php esc_html_e( 'Regenerate Critical CSS files on next page load', 'aqualuxe' ); ?>
							</label>
							<p class="description"><?php esc_html_e( 'This will regenerate all critical CSS files based on your current theme settings.', 'aqualuxe' ); ?></p>
						</td>
					</tr>
				</table>
				
				<h2><?php esc_html_e( 'Critical CSS Files', 'aqualuxe' ); ?></h2>
				<p><?php esc_html_e( 'The following critical CSS files are currently being used:', 'aqualuxe' ); ?></p>
				
				<table class="widefat">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Page Type', 'aqualuxe' ); ?></th>
							<th><?php esc_html_e( 'File Path', 'aqualuxe' ); ?></th>
							<th><?php esc_html_e( 'Size', 'aqualuxe' ); ?></th>
							<th><?php esc_html_e( 'Status', 'aqualuxe' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$device = wp_is_mobile() ? 'mobile' : 'desktop';
						$base_path = AQUALUXE_DIR . '/assets/css/critical/';
						$files = array(
							'front-page' => "front-page.{$device}.css",
							'blog' => "blog.{$device}.css",
							'woocommerce' => "woocommerce.{$device}.css",
							'woocommerce-single' => "woocommerce-single.{$device}.css",
						);
						
						foreach ( $files as $type => $file ) {
							$file_path = $base_path . $file;
							$exists = file_exists( $file_path );
							$size = $exists ? size_format( filesize( $file_path ) ) : 'N/A';
							$status = $exists ? '<span style="color: green;">' . esc_html__( 'Available', 'aqualuxe' ) . '</span>' : '<span style="color: red;">' . esc_html__( 'Missing', 'aqualuxe' ) . '</span>';
							?>
							<tr>
								<td><?php echo esc_html( ucfirst( str_replace( '-', ' ', $type ) ) ); ?></td>
								<td><?php echo esc_html( $file ); ?></td>
								<td><?php echo esc_html( $size ); ?></td>
								<td><?php echo $status; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
				
				<?php submit_button(); ?>
			</form>
			
			<h2><?php esc_html_e( 'Generate Critical CSS', 'aqualuxe' ); ?></h2>
			<p><?php esc_html_e( 'You can use the following tools to generate critical CSS:', 'aqualuxe' ); ?></p>
			<ul>
				<li><a href="https://criticalcss.com/" target="_blank">Critical CSS</a></li>
				<li><a href="https://github.com/addyosmani/critical" target="_blank">Critical by Addy Osmani</a></li>
				<li><a href="https://www.sitelocity.com/critical-path-css-generator" target="_blank">Critical Path CSS Generator</a></li>
			</ul>
		</div>
		<?php
	}
}