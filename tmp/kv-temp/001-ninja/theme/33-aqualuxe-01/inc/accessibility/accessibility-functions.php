<?php
/**
 * AquaLuxe Theme - Accessibility Functions
 *
 * This file contains functions to enhance accessibility throughout the theme.
 *
 * @package AquaLuxe
 * @subpackage Accessibility
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class AquaLuxe_Accessibility
 */
class AquaLuxe_Accessibility {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Initialize accessibility features.
		$this->init();
	}

	/**
	 * Initialize accessibility features
	 */
	public function init() {
		// Add skip link.
		add_action( 'wp_body_open', array( $this, 'add_skip_link' ), 5 );
		
		// Add ARIA attributes to navigation.
		add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_args' ) );
		
		// Add ARIA attributes to comments.
		add_filter( 'comment_form_defaults', array( $this, 'comment_form_defaults' ) );
		
		// Add ARIA attributes to pagination.
		add_filter( 'navigation_markup_template', array( $this, 'navigation_markup_template' ), 10, 2 );
		
		// Add screen reader text class.
		add_action( 'wp_head', array( $this, 'add_screen_reader_styles' ) );
		
		// Add keyboard navigation enhancements.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_keyboard_scripts' ) );
		
		// Add focus outline styles.
		add_action( 'wp_head', array( $this, 'add_focus_styles' ) );
		
		// Add ARIA attributes to widgets.
		add_filter( 'dynamic_sidebar_params', array( $this, 'widget_params' ) );
		
		// Add ARIA attributes to forms.
		add_filter( 'comment_id_fields', array( $this, 'comment_id_fields' ) );
		add_filter( 'woocommerce_form_field_args', array( $this, 'woocommerce_form_field_args' ), 10, 3 );
		
		// Add ARIA attributes to WooCommerce.
		add_filter( 'woocommerce_loop_add_to_cart_args', array( $this, 'add_to_cart_args' ), 10, 2 );
		
		// Add color contrast checker.
		add_action( 'admin_init', array( $this, 'add_color_contrast_checker' ) );
		
		// Add accessibility checker to TinyMCE.
		add_filter( 'mce_external_plugins', array( $this, 'add_accessibility_checker_plugin' ) );
		add_filter( 'mce_buttons', array( $this, 'add_accessibility_checker_button' ) );
	}

	/**
	 * Add skip link
	 */
	public function add_skip_link() {
		echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
	}

	/**
	 * Add ARIA attributes to navigation
	 *
	 * @param array $args Navigation menu arguments.
	 * @return array Modified arguments.
	 */
	public function nav_menu_args( $args ) {
		// Add ARIA attributes to menu.
		if ( ! isset( $args['container_aria_label'] ) ) {
			$args['container_aria_label'] = 'Site Navigation';
		}
		
		// Add ARIA attributes to menu items.
		$args['walker'] = new AquaLuxe_Accessible_Menu_Walker();
		
		return $args;
	}

	/**
	 * Add ARIA attributes to comment form
	 *
	 * @param array $defaults Comment form defaults.
	 * @return array Modified defaults.
	 */
	public function comment_form_defaults( $defaults ) {
		// Add ARIA attributes to comment form.
		$defaults['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title">';
		$defaults['title_reply_after']  = '</h3>';
		$defaults['comment_notes_before'] = '<p class="comment-notes"><span id="email-notes">' . __( 'Your email address will not be published.', 'aqualuxe' ) . '</span> ' . ( $defaults['require_name_email'] ? __( 'Required fields are marked', 'aqualuxe' ) . ' <span class="required">*</span>' : '' ) . '</p>';
		
		// Add ARIA attributes to comment form fields.
		$defaults['fields']['author'] = str_replace( 'name="author"', 'name="author" aria-required="true" aria-label="' . __( 'Name', 'aqualuxe' ) . '"', $defaults['fields']['author'] );
		$defaults['fields']['email'] = str_replace( 'name="email"', 'name="email" aria-required="true" aria-label="' . __( 'Email', 'aqualuxe' ) . '"', $defaults['fields']['email'] );
		$defaults['fields']['url'] = str_replace( 'name="url"', 'name="url" aria-label="' . __( 'Website', 'aqualuxe' ) . '"', $defaults['fields']['url'] );
		
		// Add ARIA attributes to comment form textarea.
		$defaults['comment_field'] = str_replace( 'name="comment"', 'name="comment" aria-required="true" aria-label="' . __( 'Comment', 'aqualuxe' ) . '"', $defaults['comment_field'] );
		
		return $defaults;
	}

	/**
	 * Add ARIA attributes to pagination
	 *
	 * @param string $template The navigation markup template.
	 * @param string $class The class passed by the calling function.
	 * @return string Modified template.
	 */
	public function navigation_markup_template( $template, $class ) {
		// Add ARIA attributes to pagination.
		$template = str_replace( '<nav class="navigation %1$s"', '<nav class="navigation %1$s" aria-label="' . __( 'Posts', 'aqualuxe' ) . '"', $template );
		
		return $template;
	}

	/**
	 * Add screen reader text styles
	 */
	public function add_screen_reader_styles() {
		?>
		<style>
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
				background-color: #f1f1f1;
				border-radius: 3px;
				box-shadow: 0 0 2px 2px rgba(0, 0, 0, 0.6);
				clip: auto !important;
				clip-path: none;
				color: #21759b;
				display: block;
				font-size: 14px;
				font-weight: bold;
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
	 * Enqueue keyboard navigation scripts
	 */
	public function enqueue_keyboard_scripts() {
		wp_enqueue_script(
			'aqualuxe-keyboard-navigation',
			get_template_directory_uri() . '/assets/dist/js/keyboard-navigation.min.js',
			array(),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Add focus styles
	 */
	public function add_focus_styles() {
		?>
		<style>
			:focus {
				outline: 2px solid var(--color-primary);
				outline-offset: 2px;
			}
			
			/* Remove outline for mouse users */
			.js-focus-visible :focus:not(.focus-visible) {
				outline: none;
			}
			
			/* Custom focus styles for keyboard users */
			.js-focus-visible .focus-visible {
				outline: 2px solid var(--color-primary);
				outline-offset: 2px;
			}
		</style>
		<?php
	}

	/**
	 * Add ARIA attributes to widgets
	 *
	 * @param array $params Widget parameters.
	 * @return array Modified parameters.
	 */
	public function widget_params( $params ) {
		// Add ARIA attributes to widgets.
		$params[0]['before_widget'] = str_replace( 'class="widget', 'class="widget" role="complementary" aria-labelledby="widget-title-' . $params[0]['widget_id'] . '"', $params[0]['before_widget'] );
		$params[0]['before_title'] = str_replace( 'class="widget-title', 'id="widget-title-' . $params[0]['widget_id'] . '" class="widget-title', $params[0]['before_title'] );
		
		return $params;
	}

	/**
	 * Add ARIA attributes to comment ID fields
	 *
	 * @param string $result Comment ID fields HTML.
	 * @return string Modified HTML.
	 */
	public function comment_id_fields( $result ) {
		// Add ARIA attributes to comment ID fields.
		$result = str_replace( 'id="comment_post_ID"', 'id="comment_post_ID" aria-hidden="true"', $result );
		$result = str_replace( 'id="comment_parent"', 'id="comment_parent" aria-hidden="true"', $result );
		
		return $result;
	}

	/**
	 * Add ARIA attributes to WooCommerce form fields
	 *
	 * @param array  $args Form field arguments.
	 * @param string $key Field key.
	 * @param string $value Field value.
	 * @return array Modified arguments.
	 */
	public function woocommerce_form_field_args( $args, $key, $value ) {
		// Add ARIA attributes to WooCommerce form fields.
		if ( ! empty( $args['label'] ) ) {
			$args['label_id'] = $args['id'] . '_label';
			$args['custom_attributes']['aria-labelledby'] = $args['label_id'];
		}
		
		// Add ARIA attributes for required fields.
		if ( ! empty( $args['required'] ) ) {
			$args['custom_attributes']['aria-required'] = 'true';
		}
		
		return $args;
	}

	/**
	 * Add ARIA attributes to add to cart buttons
	 *
	 * @param array      $args Button arguments.
	 * @param WC_Product $product Product object.
	 * @return array Modified arguments.
	 */
	public function add_to_cart_args( $args, $product ) {
		// Add ARIA attributes to add to cart buttons.
		$args['attributes']['aria-label'] = sprintf(
			/* translators: %s: Product title */
			__( 'Add "%s" to your cart', 'aqualuxe' ),
			strip_tags( $product->get_name() )
		);
		
		return $args;
	}

	/**
	 * Add color contrast checker
	 */
	public function add_color_contrast_checker() {
		// Only add to admin.
		if ( ! is_admin() ) {
			return;
		}
		
		// Add color contrast checker script.
		wp_enqueue_script(
			'aqualuxe-color-contrast',
			get_template_directory_uri() . '/assets/dist/js/color-contrast.min.js',
			array( 'wp-color-picker' ),
			AQUALUXE_VERSION,
			true
		);
	}

	/**
	 * Add accessibility checker plugin to TinyMCE
	 *
	 * @param array $plugins TinyMCE plugins.
	 * @return array Modified plugins.
	 */
	public function add_accessibility_checker_plugin( $plugins ) {
		// Add accessibility checker plugin.
		$plugins['a11ychecker'] = get_template_directory_uri() . '/assets/dist/js/tinymce-a11y-checker.min.js';
		
		return $plugins;
	}

	/**
	 * Add accessibility checker button to TinyMCE
	 *
	 * @param array $buttons TinyMCE buttons.
	 * @return array Modified buttons.
	 */
	public function add_accessibility_checker_button( $buttons ) {
		// Add accessibility checker button.
		$buttons[] = 'a11ycheck';
		
		return $buttons;
	}
}

/**
 * Accessible Menu Walker
 */
class AquaLuxe_Accessible_Menu_Walker extends Walker_Nav_Menu {
	/**
	 * Starts the element output.
	 *
	 * @param string   $output Used to append additional content (passed by reference).
	 * @param WP_Post  $item Menu item data object.
	 * @param int      $depth Depth of menu item.
	 * @param stdClass $args An object of wp_nav_menu() arguments.
	 * @param int      $id Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		if ( isset( $args->item_spacing ) && 'discard' === $args->item_spacing ) {
			$t = '';
			$n = '';
		} else {
			$t = "\t";
			$n = "\n";
		}
		$indent = ( $depth ) ? str_repeat( $t, $depth ) : '';

		$classes   = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Add ARIA attributes for menu items with children.
		$has_children = in_array( 'menu-item-has-children', $classes, true );
		$aria_attributes = '';
		
		if ( $has_children ) {
			$aria_attributes = ' aria-haspopup="true" aria-expanded="false"';
		}

		$args = apply_filters( 'nav_menu_item_args', $args, $item, $depth );

		$class_names = implode( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args, $depth ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names . '>';

		$atts           = array();
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		if ( '_blank' === $item->target && empty( $item->xfn ) ) {
			$atts['rel'] = 'noopener';
		} else {
			$atts['rel'] = $item->xfn;
		}
		$atts['href']         = ! empty( $item->url ) ? $item->url : '';
		$atts['aria-current'] = $item->current ? 'page' : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( is_scalar( $value ) && '' !== $value && false !== $value ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters( 'the_title', $item->title, $item->ID );
		$title = apply_filters( 'nav_menu_item_title', $title, $item, $args, $depth );

		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . $aria_attributes . '>';
		$item_output .= $args->link_before . $title . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

// Initialize the accessibility class.
new AquaLuxe_Accessibility();

/**
 * Add screen reader text
 *
 * @param string $text Screen reader text.
 * @return string HTML with screen reader text.
 */
function aqualuxe_screen_reader_text( $text ) {
	return '<span class="screen-reader-text">' . $text . '</span>';
}