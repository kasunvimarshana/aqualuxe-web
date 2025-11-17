<?php
/**
 * AquaLuxe Accessibility Features
 *
 * @package AquaLuxe
 * @since 1.1.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Accessibility Class
 *
 * Handles accessibility features for the theme.
 *
 * @since 1.1.0
 */
class Accessibility {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// No initialization needed.
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Add skip links.
		add_action( 'aqualuxe_before_header', array( $this, 'add_skip_links' ) );
		
		// Add ARIA attributes.
		add_filter( 'nav_menu_link_attributes', array( $this, 'nav_menu_link_attributes' ), 10, 4 );
		add_filter( 'comment_form_defaults', array( $this, 'comment_form_defaults' ) );
		add_filter( 'the_content', array( $this, 'add_image_aria_attributes' ) );
		add_filter( 'post_thumbnail_html', array( $this, 'add_image_aria_attributes' ) );
		add_filter( 'widget_text', array( $this, 'add_image_aria_attributes' ) );
		add_filter( 'wp_nav_menu', array( $this, 'add_nav_menu_aria_attributes' ), 10, 2 );
		add_filter( 'wp_list_categories', array( $this, 'add_list_categories_aria_attributes' ) );
		add_filter( 'get_archives_link', array( $this, 'add_archives_link_aria_attributes' ) );
		add_filter( 'wp_tag_cloud', array( $this, 'add_tag_cloud_aria_attributes' ) );
		
		// Add ARIA landmarks.
		add_filter( 'aqualuxe_header_attributes', array( $this, 'add_header_attributes' ) );
		add_filter( 'aqualuxe_main_attributes', array( $this, 'add_main_attributes' ) );
		add_filter( 'aqualuxe_sidebar_attributes', array( $this, 'add_sidebar_attributes' ) );
		add_filter( 'aqualuxe_footer_attributes', array( $this, 'add_footer_attributes' ) );
		add_filter( 'aqualuxe_navigation_attributes', array( $this, 'add_navigation_attributes' ) );
		add_filter( 'aqualuxe_content_attributes', array( $this, 'add_content_attributes' ) );
		add_filter( 'aqualuxe_article_attributes', array( $this, 'add_article_attributes' ) );
		
		// Add keyboard navigation.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_keyboard_navigation' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_focus_visible' ) );
		
		// Add focus styles.
		add_action( 'wp_head', array( $this, 'add_focus_styles' ) );
		
		// Add screen reader text.
		add_filter( 'the_title', array( $this, 'add_screen_reader_title' ), 10, 2 );
		add_filter( 'the_content_more_link', array( $this, 'add_screen_reader_more_link' ), 10, 2 );
		add_filter( 'excerpt_more', array( $this, 'add_screen_reader_excerpt_more' ), 20 );
		add_filter( 'next_posts_link_attributes', array( $this, 'add_screen_reader_pagination' ) );
		add_filter( 'previous_posts_link_attributes', array( $this, 'add_screen_reader_pagination' ) );
		
		// Add form labels.
		add_filter( 'get_search_form', array( $this, 'add_search_form_label' ) );
		add_filter( 'comment_id_fields', array( $this, 'add_comment_fields_labels' ) );
		
		// Add color contrast checker.
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'enqueue_color_contrast_checker' ) );
		
		// Add WooCommerce accessibility.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_woocommerce_accessibility' ) );
		add_filter( 'woocommerce_form_field_args', array( $this, 'woocommerce_form_field_args' ), 10, 3 );
		add_filter( 'woocommerce_quantity_input_args', array( $this, 'woocommerce_quantity_input_args' ), 10, 2 );
		
		// Add modal accessibility.
		add_action( 'wp_footer', array( $this, 'add_modal_accessibility' ) );
	}

	/**
	 * Add skip links.
	 *
	 * @return void
	 */
	public function add_skip_links() {
		echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
		echo '<a class="skip-link screen-reader-text" href="#site-navigation">' . esc_html__( 'Skip to navigation', 'aqualuxe' ) . '</a>';
		echo '<a class="skip-link screen-reader-text" href="#colophon">' . esc_html__( 'Skip to footer', 'aqualuxe' ) . '</a>';
	}

	/**
	 * Add ARIA attributes to nav menu links.
	 *
	 * @param array    $atts   The HTML attributes applied to the menu item's link element.
	 * @param WP_Post  $item   The current menu item.
	 * @param stdClass $args   An object of wp_nav_menu() arguments.
	 * @param int      $depth  Depth of menu item.
	 * @return array
	 */
	public function nav_menu_link_attributes( $atts, $item, $args, $depth ) {
		// Add ARIA attributes to menu items with children.
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}
		
		// Add current state.
		if ( in_array( 'current-menu-item', $item->classes, true ) ) {
			$atts['aria-current'] = 'page';
		} elseif ( in_array( 'current-menu-parent', $item->classes, true ) ) {
			$atts['aria-current'] = 'true';
		}
		
		return $atts;
	}

	/**
	 * Add ARIA attributes to comment form.
	 *
	 * @param array $defaults The default comment form arguments.
	 * @return array
	 */
	public function comment_form_defaults( $defaults ) {
		// Add ARIA attributes to comment form.
		$defaults['comment_field'] = str_replace( 'textarea', 'textarea aria-required="true"', $defaults['comment_field'] );
		$defaults['fields']['author'] = str_replace( '<input', '<input aria-required="true"', $defaults['fields']['author'] );
		$defaults['fields']['email'] = str_replace( '<input', '<input aria-required="true"', $defaults['fields']['email'] );
		$defaults['fields']['url'] = str_replace( '<input', '<input aria-required="false"', $defaults['fields']['url'] );
		
		// Add labels to comment form fields.
		$defaults['fields']['author'] = str_replace( 'id="author"', 'id="author" aria-labelledby="label-author"', $defaults['fields']['author'] );
		$defaults['fields']['author'] = '<label id="label-author" for="author">' . esc_html__( 'Name', 'aqualuxe' ) . ( $defaults['require_name_email'] ? ' <span class="required">*</span>' : '' ) . '</label> ' . $defaults['fields']['author'];
		
		$defaults['fields']['email'] = str_replace( 'id="email"', 'id="email" aria-labelledby="label-email"', $defaults['fields']['email'] );
		$defaults['fields']['email'] = '<label id="label-email" for="email">' . esc_html__( 'Email', 'aqualuxe' ) . ( $defaults['require_name_email'] ? ' <span class="required">*</span>' : '' ) . '</label> ' . $defaults['fields']['email'];
		
		$defaults['fields']['url'] = str_replace( 'id="url"', 'id="url" aria-labelledby="label-url"', $defaults['fields']['url'] );
		$defaults['fields']['url'] = '<label id="label-url" for="url">' . esc_html__( 'Website', 'aqualuxe' ) . '</label> ' . $defaults['fields']['url'];
		
		$defaults['comment_field'] = str_replace( 'id="comment"', 'id="comment" aria-labelledby="label-comment"', $defaults['comment_field'] );
		$defaults['comment_field'] = '<label id="label-comment" for="comment">' . esc_html__( 'Comment', 'aqualuxe' ) . ' <span class="required">*</span></label> ' . $defaults['comment_field'];
		
		return $defaults;
	}

	/**
	 * Add ARIA attributes to images.
	 *
	 * @param string $content The content.
	 * @return string
	 */
	public function add_image_aria_attributes( $content ) {
		// Add ARIA attributes to images without alt text.
		$content = preg_replace( '/<img(?!.*alt=)(.*?)>/i', '<img$1 alt="" aria-hidden="true">', $content );
		
		// Add ARIA attributes to decorative images.
		$content = preg_replace( '/<img(.*?)alt=""(.*?)>/i', '<img$1alt=""$2 aria-hidden="true">', $content );
		
		return $content;
	}

	/**
	 * Add ARIA attributes to nav menu.
	 *
	 * @param string   $nav_menu The HTML content for the navigation menu.
	 * @param stdClass $args     An object containing wp_nav_menu() arguments.
	 * @return string
	 */
	public function add_nav_menu_aria_attributes( $nav_menu, $args ) {
		// Add ARIA attributes to dropdown menus.
		$nav_menu = str_replace( '<ul class="sub-menu">', '<ul class="sub-menu" role="menu" aria-label="' . esc_attr__( 'Submenu', 'aqualuxe' ) . '">', $nav_menu );
		
		return $nav_menu;
	}

	/**
	 * Add ARIA attributes to list categories.
	 *
	 * @param string $output The HTML output.
	 * @return string
	 */
	public function add_list_categories_aria_attributes( $output ) {
		// Add ARIA attributes to category lists.
		$output = str_replace( '<ul>', '<ul role="list">', $output );
		
		return $output;
	}

	/**
	 * Add ARIA attributes to archives link.
	 *
	 * @param string $link The HTML link.
	 * @return string
	 */
	public function add_archives_link_aria_attributes( $link ) {
		// Add ARIA attributes to archives link.
		$link = str_replace( '<li>', '<li role="listitem">', $link );
		
		return $link;
	}

	/**
	 * Add ARIA attributes to tag cloud.
	 *
	 * @param string $output The HTML output.
	 * @return string
	 */
	public function add_tag_cloud_aria_attributes( $output ) {
		// Add ARIA attributes to tag cloud.
		$output = str_replace( '<div class="tagcloud">', '<div class="tagcloud" role="list">', $output );
		
		return $output;
	}

	/**
	 * Add header attributes.
	 *
	 * @param array $attributes The HTML attributes.
	 * @return array
	 */
	public function add_header_attributes( $attributes ) {
		$attributes['role'] = 'banner';
		
		return $attributes;
	}

	/**
	 * Add main attributes.
	 *
	 * @param array $attributes The HTML attributes.
	 * @return array
	 */
	public function add_main_attributes( $attributes ) {
		$attributes['role'] = 'main';
		$attributes['id'] = 'content';
		
		return $attributes;
	}

	/**
	 * Add sidebar attributes.
	 *
	 * @param array $attributes The HTML attributes.
	 * @return array
	 */
	public function add_sidebar_attributes( $attributes ) {
		$attributes['role'] = 'complementary';
		$attributes['aria-label'] = esc_attr__( 'Sidebar', 'aqualuxe' );
		
		return $attributes;
	}

	/**
	 * Add footer attributes.
	 *
	 * @param array $attributes The HTML attributes.
	 * @return array
	 */
	public function add_footer_attributes( $attributes ) {
		$attributes['role'] = 'contentinfo';
		
		return $attributes;
	}

	/**
	 * Add navigation attributes.
	 *
	 * @param array $attributes The HTML attributes.
	 * @return array
	 */
	public function add_navigation_attributes( $attributes ) {
		$attributes['role'] = 'navigation';
		
		return $attributes;
	}

	/**
	 * Add content attributes.
	 *
	 * @param array $attributes The HTML attributes.
	 * @return array
	 */
	public function add_content_attributes( $attributes ) {
		$attributes['role'] = 'region';
		$attributes['aria-label'] = esc_attr__( 'Content', 'aqualuxe' );
		
		return $attributes;
	}

	/**
	 * Add article attributes.
	 *
	 * @param array $attributes The HTML attributes.
	 * @return array
	 */
	public function add_article_attributes( $attributes ) {
		$attributes['role'] = 'article';
		
		return $attributes;
	}

	/**
	 * Enqueue keyboard navigation script.
	 *
	 * @return void
	 */
	public function enqueue_keyboard_navigation() {
		wp_enqueue_script(
			'aqualuxe-keyboard-navigation',
			AQUALUXE_URI . '/assets/js/keyboard-navigation.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Enqueue focus-visible polyfill.
	 *
	 * @return void
	 */
	public function enqueue_focus_visible() {
		wp_enqueue_script(
			'aqualuxe-focus-visible',
			AQUALUXE_URI . '/assets/js/focus-visible.min.js',
			array(),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Add focus styles.
	 *
	 * @return void
	 */
	public function add_focus_styles() {
		?>
		<style id="aqualuxe-focus-styles">
			/* Focus styles */
			:focus {
				outline: 2px solid var(--primary-color);
				outline-offset: 2px;
			}
			
			/* Remove focus styles for mouse users */
			.js-focus-visible :focus:not(.focus-visible) {
				outline: none;
			}
			
			/* Enhanced focus styles for keyboard users */
			.js-focus-visible .focus-visible {
				outline: 2px solid var(--primary-color);
				outline-offset: 2px;
				box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.3);
			}
			
			/* Skip link focus styles */
			.skip-link:focus {
				position: fixed;
				top: 10px;
				left: 10px;
				padding: 10px 20px;
				background-color: var(--primary-color);
				color: #ffffff;
				z-index: 999999;
				text-decoration: none;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
				outline: none;
			}

			/* Screen reader text */
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

			.screen-reader-text:focus {
				background-color: var(--primary-color);
				clip: auto !important;
				clip-path: none;
				color: #ffffff;
				display: block;
				font-size: 1em;
				height: auto;
				left: 5px;
				line-height: normal;
				padding: 15px 23px 14px;
				text-decoration: none;
				top: 5px;
				width: auto;
				z-index: 100000;
			}
		</style>
		<?php
	}

	/**
	 * Add screen reader text to empty titles.
	 *
	 * @param string $title The post title.
	 * @param int    $id    The post ID.
	 * @return string
	 */
	public function add_screen_reader_title( $title, $id ) {
		if ( '' === $title ) {
			return sprintf(
				/* translators: %d: Post ID */
				esc_html__( 'Untitled post #%d', 'aqualuxe' ),
				$id
			);
		}
		
		return $title;
	}

	/**
	 * Add screen reader text to more link.
	 *
	 * @param string $more_link The more link.
	 * @param string $more_text The more text.
	 * @return string
	 */
	public function add_screen_reader_more_link( $more_link, $more_text ) {
		return str_replace(
			$more_text,
			sprintf(
				'%s <span class="screen-reader-text">%s</span>',
				$more_text,
				get_the_title()
			),
			$more_link
		);
	}

	/**
	 * Add screen reader text to excerpt more.
	 *
	 * @param string $more The excerpt more string.
	 * @return string
	 */
	public function add_screen_reader_excerpt_more( $more ) {
		if ( is_admin() ) {
			return $more;
		}
		
		return sprintf(
			'&hellip; <a href="%s" class="more-link">%s <span class="screen-reader-text">%s</span></a>',
			esc_url( get_permalink() ),
			esc_html__( 'Continue reading', 'aqualuxe' ),
			get_the_title()
		);
	}

	/**
	 * Add screen reader text to pagination.
	 *
	 * @param string $attributes The HTML attributes.
	 * @return string
	 */
	public function add_screen_reader_pagination( $attributes ) {
		global $wp_query;
		
		if ( isset( $wp_query->max_num_pages ) && $wp_query->max_num_pages > 1 ) {
			$attributes .= ' aria-label="' . esc_attr__( 'Posts navigation', 'aqualuxe' ) . '"';
		}
		
		return $attributes;
	}

	/**
	 * Add label to search form.
	 *
	 * @param string $form The search form HTML.
	 * @return string
	 */
	public function add_search_form_label( $form ) {
		// Add label to search form.
		$form = str_replace(
			'<input type="search"',
			'<label for="search-field" class="screen-reader-text">' . esc_html__( 'Search', 'aqualuxe' ) . '</label><input type="search" id="search-field"',
			$form
		);
		
		// Add ARIA attributes to search form.
		$form = str_replace(
			'<form role="search"',
			'<form role="search" aria-label="' . esc_attr__( 'Site search', 'aqualuxe' ) . '"',
			$form
		);
		
		// Add ARIA attributes to search button.
		$form = str_replace(
			'<button type="submit"',
			'<button type="submit" aria-label="' . esc_attr__( 'Submit search', 'aqualuxe' ) . '"',
			$form
		);
		
		return $form;
	}

	/**
	 * Add labels to comment fields.
	 *
	 * @param string $fields The comment fields HTML.
	 * @return string
	 */
	public function add_comment_fields_labels( $fields ) {
		// Add ARIA attributes to comment fields.
		$fields = str_replace(
			'<input type="hidden"',
			'<input type="hidden" aria-hidden="true"',
			$fields
		);
		
		return $fields;
	}

	/**
	 * Enqueue color contrast checker for customizer.
	 *
	 * @return void
	 */
	public function enqueue_color_contrast_checker() {
		wp_enqueue_script(
			'aqualuxe-color-contrast-checker',
			AQUALUXE_URI . '/assets/js/color-contrast-checker.js',
			array( 'customize-controls', 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Enqueue WooCommerce accessibility scripts.
	 *
	 * @return void
	 */
	public function enqueue_woocommerce_accessibility() {
		// Only enqueue on WooCommerce pages.
		if ( ! class_exists( 'WooCommerce' ) || ! ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
			return;
		}
		
		wp_enqueue_script(
			'aqualuxe-woocommerce-accessibility',
			AQUALUXE_URI . '/assets/js/woocommerce-accessibility.js',
			array( 'jquery' ),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Add ARIA attributes to WooCommerce form fields.
	 *
	 * @param array  $args       Form field arguments.
	 * @param string $key        Field key.
	 * @param string $value      Field value.
	 * @return array
	 */
	public function woocommerce_form_field_args( $args, $key, $value ) {
		// Add ARIA attributes to form fields.
		if ( ! empty( $args['label'] ) && ! empty( $args['id'] ) ) {
			$args['label_id'] = $args['id'] . '-label';
			$args['custom_attributes']['aria-labelledby'] = $args['label_id'];
		}
		
		// Add ARIA required attribute.
		if ( ! empty( $args['required'] ) ) {
			$args['custom_attributes']['aria-required'] = 'true';
		}
		
		// Add ARIA invalid attribute for validation.
		if ( ! empty( $args['validate'] ) ) {
			$args['custom_attributes']['aria-invalid'] = 'false';
		}
		
		return $args;
	}

	/**
	 * Add ARIA attributes to WooCommerce quantity input.
	 *
	 * @param array      $args    Quantity input arguments.
	 * @param WC_Product $product Product object.
	 * @return array
	 */
	public function woocommerce_quantity_input_args( $args, $product ) {
		// Add ARIA attributes to quantity input.
		$args['label'] = esc_html__( 'Quantity', 'aqualuxe' );
		$args['input_id'] = uniqid( 'quantity_' );
		$args['input_name'] = 'quantity';
		$args['input_value'] = $args['input_value'];
		$args['min_value'] = $args['min_value'];
		$args['max_value'] = $args['max_value'];
		$args['step'] = $args['step'];
		$args['pattern'] = '[0-9]*';
		$args['inputmode'] = 'numeric';
		$args['product_name'] = $product ? $product->get_title() : '';
		
		// Add ARIA attributes.
		$args['custom_attributes']['aria-label'] = sprintf(
			/* translators: %s: Product name */
			esc_html__( 'Quantity for %s', 'aqualuxe' ),
			$args['product_name']
		);
		
		return $args;
	}

	/**
	 * Add modal accessibility.
	 *
	 * @return void
	 */
	public function add_modal_accessibility() {
		?>
		<script>
			(function() {
				// Add ARIA attributes to modals.
				var modals = document.querySelectorAll('.modal, .quick-view-modal, #search-overlay');
				
				modals.forEach(function(modal) {
					if (!modal.hasAttribute('role')) {
						modal.setAttribute('role', 'dialog');
					}
					
					if (!modal.hasAttribute('aria-modal')) {
						modal.setAttribute('aria-modal', 'true');
					}
					
					if (!modal.hasAttribute('aria-labelledby') && !modal.hasAttribute('aria-label')) {
						var heading = modal.querySelector('h1, h2, h3, h4, h5, h6');
						
						if (heading) {
							var headingId = heading.id || 'modal-heading-' + Math.random().toString(36).substr(2, 9);
							heading.id = headingId;
							modal.setAttribute('aria-labelledby', headingId);
						} else {
							modal.setAttribute('aria-label', '<?php echo esc_js( __( 'Modal dialog', 'aqualuxe' ) ); ?>');
						}
					}
				});
			})();
		</script>
		<?php
	}

	/**
	 * Check if a color has sufficient contrast.
	 *
	 * @param string $color1 The first color in hex format.
	 * @param string $color2 The second color in hex format.
	 * @return bool
	 */
	public function has_sufficient_contrast( $color1, $color2 ) {
		// Convert hex colors to RGB.
		$color1_rgb = $this->hex_to_rgb( $color1 );
		$color2_rgb = $this->hex_to_rgb( $color2 );
		
		// Calculate relative luminance.
		$color1_luminance = $this->calculate_luminance( $color1_rgb );
		$color2_luminance = $this->calculate_luminance( $color2_rgb );
		
		// Calculate contrast ratio.
		$ratio = ( max( $color1_luminance, $color2_luminance ) + 0.05 ) / ( min( $color1_luminance, $color2_luminance ) + 0.05 );
		
		// WCAG 2.1 AA requires a contrast ratio of at least 4.5:1 for normal text.
		return $ratio >= 4.5;
	}

	/**
	 * Convert hex color to RGB.
	 *
	 * @param string $hex The hex color.
	 * @return array
	 */
	private function hex_to_rgb( $hex ) {
		$hex = ltrim( $hex, '#' );
		
		if ( strlen( $hex ) === 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		
		return array( $r, $g, $b );
	}

	/**
	 * Calculate relative luminance.
	 *
	 * @param array $rgb The RGB values.
	 * @return float
	 */
	private function calculate_luminance( $rgb ) {
		$rgb = array_map( function( $val ) {
			$val = $val / 255;
			return $val <= 0.03928 ? $val / 12.92 : pow( ( $val + 0.055 ) / 1.055, 2.4 );
		}, $rgb );
		
		return 0.2126 * $rgb[0] + 0.7152 * $rgb[1] + 0.0722 * $rgb[2];
	}

	/**
	 * Add ARIA landmark roles.
	 *
	 * @param string $content The content.
	 * @param string $role    The ARIA role.
	 * @return string
	 */
	public function add_aria_landmark( $content, $role ) {
		// Add ARIA landmark role.
		if ( ! strpos( $content, 'role=' ) ) {
			$content = str_replace( 'class="', 'role="' . $role . '" class="', $content );
		}
		
		return $content;
	}

	/**
	 * Get screen reader text.
	 *
	 * @param string $text The text.
	 * @return string
	 */
	public function get_screen_reader_text( $text ) {
		return '<span class="screen-reader-text">' . $text . '</span>';
	}
}