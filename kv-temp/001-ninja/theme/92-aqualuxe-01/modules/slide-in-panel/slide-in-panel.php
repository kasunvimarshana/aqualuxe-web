<?php
/**
 * Module: Off-Canvas / Slide-in Panel
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Slide_In_Panel class.
 */
class AquaLuxe_Slide_In_Panel {

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
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
		add_action( 'wp_footer', array( $this, 'add_panel_html' ) );
	}

	/**
	 * Enqueue assets for the slide-in panel.
	 */
	public function enqueue_assets() {
		$manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
			if ( isset( $manifest['/css/slide-in-panel.css'] ) ) {
				wp_enqueue_style( 'aqualuxe-slide-in-panel', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/css/slide-in-panel.css'], array(), null );
			}
			if ( isset( $manifest['/js/slide-in-panel.js'] ) ) {
				wp_enqueue_script( 'aqualuxe-slide-in-panel', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/js/slide-in-panel.js'], array('jquery'), null, true );
			}
		}
	}

	/**
	 * Add the panel HTML to the footer.
	 */
	public function add_panel_html() {
		?>
		<button id="aqualuxe-panel-trigger" class="fixed bottom-8 right-8 bg-blue-600 text-white rounded-full p-4 shadow-lg z-40" aria-label="<?php esc_attr_e( 'Open Panel', 'aqualuxe' ); ?>" aria-controls="aqualuxe-slide-in-panel" aria-expanded="false">
			<!-- Icon placeholder -->
			<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" />
			</svg>
		</button>

		<div id="aqualuxe-slide-in-panel" class="fixed top-0 right-0 h-full w-full md:w-96 bg-white dark:bg-gray-800 shadow-2xl z-50 transform translate-x-full transition-transform duration-300 ease-in-out" role="dialog" aria-modal="true" aria-labelledby="aqualuxe-panel-title" hidden>
			<div class="p-6 h-full flex flex-col">
				<div class="flex justify-between items-center mb-6">
					<h2 id="aqualuxe-panel-title" class="text-2xl font-bold text-gray-900 dark:text-white"><?php esc_html_e( 'Quick Menu', 'aqualuxe' ); ?></h2>
					<button id="aqualuxe-panel-close" class="text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white" aria-label="<?php esc_attr_e( 'Close Panel', 'aqualuxe' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
						</svg>
					</button>
				</div>
				<div class="flex-grow overflow-y-auto">
					<?php
					if ( has_nav_menu( 'menu-1' ) ) {
						wp_nav_menu(
							array(
								'theme_location' => 'menu-1',
								'container'      => false,
								'menu_class'     => 'space-y-4',
								'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
							)
						);
					} else {
						echo '<p>' . esc_html__( 'Please assign a menu to the Primary menu location.', 'aqualuxe' ) . '</p>';
					}
					?>
				</div>
			</div>
		</div>
		<div id="aqualuxe-panel-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden"></div>
		<?php
	}
}

AquaLuxe_Slide_In_Panel::get_instance();
