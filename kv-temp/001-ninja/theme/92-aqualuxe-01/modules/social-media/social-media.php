<?php
/**
 * Module: Social Media Integration
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Include the customizer class.
require_once dirname( __FILE__ ) . '/class-aqualuxe-social-media-customizer.php';

/**
 * AquaLuxe_Social_Media class.
 */
class AquaLuxe_Social_Media {

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
		// Initialize the customizer settings.
		AquaLuxe_Social_Media_Customizer::get_instance();

		// Add shortcode to display icons.
		add_shortcode( 'aqualuxe_social_icons', array( $this, 'render_social_icons' ) );

		// Enqueue assets.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
	}

	/**
	 * Enqueue assets for the social media icons.
	 */
	public function enqueue_assets() {
		$manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
			if ( isset( $manifest['/css/social-media.css'] ) ) {
				wp_enqueue_style( 'aqualuxe-social-media', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/css/social-media.css'], array(), null );
			}
		}
	}

	/**
	 * Render the social media icons.
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string
	 */
	public function render_social_icons( $atts = array() ) {
		$defaults = array(
			'class' => 'flex space-x-4',
		);
		$atts     = shortcode_atts( $defaults, $atts, 'aqualuxe_social_icons' );

		$social_links = get_theme_mod( 'aqualuxe_social_links', array() );

		if ( empty( $social_links ) ) {
			return '';
		}

		ob_start();
		?>
		<div class="<?php echo esc_attr( $atts['class'] ); ?>">
			<?php foreach ( $social_links as $link ) : ?>
				<?php
				$domain = str_ireplace( 'www.', '', parse_url( $link, PHP_URL_HOST ) );
				$icon   = $this->get_svg_icon( $domain );
				if ( ! $icon ) {
					continue; // Skip if no icon is found.
				}
				?>
				<a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white">
					<span class="sr-only"><?php echo esc_html( ucwords( str_replace( '.com', '', $domain ) ) ); ?></span>
					<?php echo $icon; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</a>
			<?php endforeach; ?>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get SVG icon for a social media domain.
	 *
	 * @param string $domain The domain name (e.g., facebook.com).
	 * @return string SVG icon HTML.
	 */
	private function get_svg_icon( $domain ) {
		$icons = array(
			'facebook.com'  => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd" /></svg>',
			'twitter.com'   => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.71v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" /></svg>',
			'instagram.com' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.024.06 1.378.06 3.808s-.012 2.784-.06 3.808c-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.024.048-1.378.06-3.808.06s-2.784-.012-3.808-.06c-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.048-1.024-.06-1.378-.06-3.808s.012-2.784.06-3.808c.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 016.08 2.525c.636-.247 1.363-.416 2.427-.465C9.53 2.013 9.884 2 12.315 2zM12 7a5 5 0 100 10 5 5 0 000-10zm0 8a3 3 0 110-6 3 3 0 010 6zm6.406-11.845a1.25 1.25 0 100 2.5 1.25 1.25 0 000-2.5z" clip-rule="evenodd" /></svg>',
			'linkedin.com'  => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>',
			'youtube.com'   => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path fill-rule="evenodd" d="M19.812 5.418c.861.23 1.538.907 1.768 1.768C21.998 8.78 22 12 22 12s0 3.22-.42 4.814c-.23.861-.907 1.538-1.768 1.768C18.219 19 12 19 12 19s-6.219 0-7.812-.42c-.861-.23-1.538-.907-1.768-1.768C2.002 15.22 2 12 2 12s0-3.22.42-4.814c.23-.861.907-1.538 1.768-1.768C5.781 5 12 5 12 5s6.219 0 7.812.418zM9.75 15.5V8.5l6.5 3.5-6.5 3.5z" clip-rule="evenodd" /></svg>',
			'pinterest.com' => '<svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.198-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.56-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.971-5.36 11.971-11.987C23.97 5.39 18.626 0 12.017 0z"/></svg>',
		);

		return isset( $icons[ $domain ] ) ? $icons[ $domain ] : null;
	}
}

/**
 * Function to get social icons.
 * A wrapper for the class method for easier template usage.
 */
function aqualuxe_the_social_icons() {
	echo AquaLuxe_Social_Media::get_instance()->render_social_icons(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

AquaLuxe_Social_Media::get_instance();
