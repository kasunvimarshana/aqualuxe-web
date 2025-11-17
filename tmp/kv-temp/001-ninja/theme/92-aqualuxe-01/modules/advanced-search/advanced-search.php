<?php
/**
 * Module: Advanced Search
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * AquaLuxe_Advanced_Search class.
 */
class AquaLuxe_Advanced_Search {

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
		add_action( 'wp_footer', array( $this, 'add_search_modal_html' ) );

		// AJAX handlers
		add_action( 'wp_ajax_aqualuxe_advanced_search', array( $this, 'ajax_search_results' ) );
		add_action( 'wp_ajax_nopriv_aqualuxe_advanced_search', array( $this, 'ajax_search_results' ) );

		// Add search icon to header
		add_filter( 'wp_nav_menu_items', array( $this, 'add_search_icon_to_menu' ), 10, 2 );
	}

	/**
	 * Enqueue assets for the advanced search.
	 */
	public function enqueue_assets() {
		$manifest_path = AQUALUXE_THEME_DIR . '/assets/dist/mix-manifest.json';
		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );
			if ( isset( $manifest['/css/advanced-search.css'] ) ) {
				wp_enqueue_style( 'aqualuxe-advanced-search', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/css/advanced-search.css'], array(), null );
			}
			if ( isset( $manifest['/js/advanced-search.js'] ) ) {
				wp_enqueue_script( 'aqualuxe-advanced-search', AQUALUXE_THEME_URI . '/assets/dist' . $manifest['/js/advanced-search.js'], array('jquery'), null, true );
				wp_localize_script(
					'aqualuxe-advanced-search',
					'aqualuxe_search_params',
					array(
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'aqualuxe_search_nonce' ),
					)
				);
			}
		}
	}

	/**
	 * Add search icon to the primary navigation menu.
	 *
	 * @param string $items The HTML list content for the menu items.
	 * @param object $args  An object containing wp_nav_menu() arguments.
	 * @return string
	 */
	public function add_search_icon_to_menu( $items, $args ) {
		if ( isset($args->theme_location) && 'menu-1' === $args->theme_location ) {
			$search_icon = '<li class="menu-item"><a href="#" id="aqualuxe-search-trigger" aria-label="' . esc_attr__( 'Open Search', 'aqualuxe' ) . '"><svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg></a></li>';
			$items .= $search_icon;
		}
		return $items;
	}

	/**
	 * Add the search modal HTML to the footer.
	 */
	public function add_search_modal_html() {
		?>
		<div id="aqualuxe-search-modal" class="fixed inset-0 bg-black bg-opacity-75 z-50 hidden" role="dialog" aria-modal="true" aria-labelledby="aqualuxe-search-title">
			<div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-11/12 md:w-2/3 lg:w-1/2">
				<div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-2xl">
					<button id="aqualuxe-search-close" class="absolute top-4 right-4 text-gray-500 dark:text-gray-400 hover:text-gray-800 dark:hover:text-white" aria-label="<?php esc_attr_e( 'Close Search', 'aqualuxe' ); ?>">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
					</button>
					<h2 id="aqualuxe-search-title" class="text-2xl font-bold text-gray-900 dark:text-white mb-6 text-center"><?php esc_html_e( 'Search Products & Articles', 'aqualuxe' ); ?></h2>
					<form role="search" method="get" class="relative" action="<?php echo esc_url( home_url( '/' ) ); ?>">
						<input type="search" id="aqualuxe-search-input" class="w-full p-4 border border-gray-300 dark:border-gray-700 rounded-md bg-gray-50 dark:bg-gray-800 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="<?php esc_attr_e( 'Start typing...', 'aqualuxe' ); ?>" value="" name="s" autocomplete="off">
						<div class="absolute top-full left-0 w-full mt-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-md shadow-lg z-10" id="aqualuxe-search-results">
							<!-- AJAX results will be loaded here -->
						</div>
					</form>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Handle AJAX search request.
	 */
	public function ajax_search_results() {
		check_ajax_referer( 'aqualuxe_search_nonce', 'nonce' );

		$search_query = isset( $_POST['query'] ) ? sanitize_text_field( wp_unslash( $_POST['query'] ) ) : '';

		if ( empty( $search_query ) ) {
			wp_send_json_success( '<div class="p-4 text-center text-gray-500">' . esc_html__( 'Please enter a search term.', 'aqualuxe' ) . '</div>' );
		}

		$args = array(
			's'              => $search_query,
			'posts_per_page' => 10,
			'post_type'      => array( 'post', 'page', 'product' ),
		);

		$query = new WP_Query( $args );

		ob_start();

		if ( $query->have_posts() ) {
			echo '<ul class="divide-y divide-gray-200 dark:divide-gray-700">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$post_type        = get_post_type_object( get_post_type() );
				$post_type_label  = $post_type ? $post_type->labels->singular_name : esc_html__( 'Post', 'aqualuxe' );
				$is_product       = 'product' === get_post_type();
				$product          = $is_product ? wc_get_product( get_the_ID() ) : null;
				?>
				<li class="p-4 hover:bg-gray-100 dark:hover:bg-gray-800">
					<a href="<?php the_permalink(); ?>" class="flex items-center space-x-4">
						<?php if ( $is_product && has_post_thumbnail() ) : ?>
							<div class="w-16 h-16 flex-shrink-0">
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover rounded-md' ) ); ?>
							</div>
						<?php elseif ( has_post_thumbnail() ) : ?>
							<div class="w-16 h-16 flex-shrink-0">
								<?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover rounded-md' ) ); ?>
							</div>
						<?php else : ?>
							<div class="w-16 h-16 flex-shrink-0 bg-gray-200 dark:bg-gray-700 rounded-md flex items-center justify-center">
								<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
							</div>
						<?php endif; ?>
						<div class="flex-grow">
							<h4 class="font-bold text-gray-900 dark:text-white"><?php the_title(); ?></h4>
							<div class="text-sm text-gray-500 dark:text-gray-400">
								<?php if ( $is_product && $product ) : ?>
									<span class="price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
								<?php else : ?>
									<span><?php echo esc_html( $post_type_label ); ?></span>
								<?php endif; ?>
							</div>
						</div>
					</a>
				</li>
				<?php
			}
			echo '</ul>';
		} else {
			echo '<div class="p-4 text-center text-gray-500">' . esc_html__( 'No results found.', 'aqualuxe' ) . '</div>';
		}

		wp_reset_postdata();

		$html = ob_get_clean();
		wp_send_json_success( $html );
	}
}

AquaLuxe_Advanced_Search::get_instance();
