<?php
/**
 * Accessibility Class
 *
 * @package AquaLuxe
 * @subpackage Core
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Accessibility Class
 *
 * This class handles accessibility-related functionality.
 */
class Accessibility extends Service {

	/**
	 * Initialize the service
	 *
	 * @return void
	 */
	public function initialize() {
		$this->register_hooks();
	}

	/**
	 * Register hooks
	 *
	 * @return void
	 */
	public function register_hooks() {
		add_action( 'wp_head', array( $this, 'skip_links' ) );
		add_filter( 'nav_menu_link_attributes', array( $this, 'nav_menu_link_attributes' ), 10, 4 );
		add_filter( 'wp_get_attachment_image_attributes', array( $this, 'attachment_image_attributes' ), 10, 3 );
		add_filter( 'the_content', array( $this, 'add_aria_landmarks' ) );
		add_filter( 'comment_form_defaults', array( $this, 'comment_form_defaults' ) );
		add_filter( 'comment_reply_link', array( $this, 'comment_reply_link' ) );
		add_filter( 'get_comment_author_link', array( $this, 'comment_author_link' ) );
		add_filter( 'wp_nav_menu', array( $this, 'nav_menu_aria_current' ), 10, 2 );
		add_filter( 'next_posts_link_attributes', array( $this, 'posts_link_attributes' ) );
		add_filter( 'previous_posts_link_attributes', array( $this, 'posts_link_attributes' ) );
	}

	/**
	 * Add skip links
	 *
	 * @return void
	 */
	public function skip_links() {
		// Add skip links for keyboard navigation.
		echo '<a class="skip-link screen-reader-text" href="#primary">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
		echo '<a class="skip-link screen-reader-text" href="#site-navigation">' . esc_html__( 'Skip to navigation', 'aqualuxe' ) . '</a>';
		echo '<a class="skip-link screen-reader-text" href="#colophon">' . esc_html__( 'Skip to footer', 'aqualuxe' ) . '</a>';
	}

	/**
	 * Add ARIA attributes to navigation menu links
	 *
	 * @param array    $atts Link attributes.
	 * @param WP_Post  $item Menu item.
	 * @param stdClass $args Menu arguments.
	 * @param int      $depth Menu depth.
	 * @return array
	 */
	public function nav_menu_link_attributes( $atts, $item, $args, $depth ) {
		// Add ARIA attributes to menu items with children.
		if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
			$atts['aria-haspopup'] = 'true';
			$atts['aria-expanded'] = 'false';
		}

		// Add ARIA current attribute to active menu items.
		if ( in_array( 'current-menu-item', $item->classes, true ) ) {
			$atts['aria-current'] = 'page';
		}

		return $atts;
	}

	/**
	 * Add ARIA attributes to attachment images
	 *
	 * @param array   $attr Image attributes.
	 * @param WP_Post $attachment Attachment post.
	 * @param string  $size Image size.
	 * @return array
	 */
	public function attachment_image_attributes( $attr, $attachment, $size ) {
		// Add alt text to images.
		if ( empty( $attr['alt'] ) ) {
			$attr['alt'] = get_the_title( $attachment->ID );
		}

		// Add loading attribute for better performance.
		if ( ! isset( $attr['loading'] ) ) {
			$attr['loading'] = 'lazy';
		}

		return $attr;
	}

	/**
	 * Add ARIA landmarks to content
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public function add_aria_landmarks( $content ) {
		// Add ARIA landmarks to headings.
		$content = preg_replace_callback(
			'/<h([1-6])(.*?)>(.*?)<\/h\1>/',
			function( $matches ) {
				$level = $matches[1];
				$attrs = $matches[2];
				$text = $matches[3];
				
				// Add ID attribute if not present.
				if ( ! strpos( $attrs, 'id=' ) ) {
					$id = sanitize_title( $text );
					$attrs .= ' id="' . $id . '"';
				}
				
				return '<h' . $level . $attrs . '>' . $text . '</h' . $level . '>';
			},
			$content
		);

		// Add ARIA landmarks to tables.
		$content = preg_replace_callback(
			'/<table(.*?)>(.*?)<\/table>/s',
			function( $matches ) {
				$attrs = $matches[1];
				$table_content = $matches[2];
				
				// Add role attribute if not present.
				if ( ! strpos( $attrs, 'role=' ) ) {
					$attrs .= ' role="table"';
				}
				
				// Add aria-label if not present.
				if ( ! strpos( $attrs, 'aria-label=' ) && ! strpos( $attrs, 'aria-labelledby=' ) ) {
					$attrs .= ' aria-label="' . esc_attr__( 'Table', 'aqualuxe' ) . '"';
				}
				
				return '<table' . $attrs . '>' . $table_content . '</table>';
			},
			$content
		);

		// Add ARIA landmarks to forms.
		$content = preg_replace_callback(
			'/<form(.*?)>(.*?)<\/form>/s',
			function( $matches ) {
				$attrs = $matches[1];
				$form_content = $matches[2];
				
				// Add role attribute if not present.
				if ( ! strpos( $attrs, 'role=' ) ) {
					$attrs .= ' role="form"';
				}
				
				return '<form' . $attrs . '>' . $form_content . '</form>';
			},
			$content
		);

		return $content;
	}

	/**
	 * Add ARIA attributes to comment form
	 *
	 * @param array $defaults Comment form defaults.
	 * @return array
	 */
	public function comment_form_defaults( $defaults ) {
		// Add ARIA attributes to comment form.
		$defaults['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title">';
		$defaults['title_reply_after'] = '</h3>';
		$defaults['comment_notes_before'] = '<p class="comment-notes"><span id="email-notes">' . esc_html__( 'Your email address will not be published.', 'aqualuxe' ) . '</span> ' . esc_html__( 'Required fields are marked', 'aqualuxe' ) . ' <span class="required">*</span></p>';
		
		return $defaults;
	}

	/**
	 * Add ARIA attributes to comment reply link
	 *
	 * @param string $link Comment reply link.
	 * @return string
	 */
	public function comment_reply_link( $link ) {
		// Add ARIA attributes to comment reply link.
		$link = str_replace( 'class="comment-reply-link"', 'class="comment-reply-link" role="button"', $link );
		
		return $link;
	}

	/**
	 * Add ARIA attributes to comment author link
	 *
	 * @param string $link Comment author link.
	 * @return string
	 */
	public function comment_author_link( $link ) {
		// Add ARIA attributes to comment author link.
		$link = str_replace( '<a', '<a rel="external nofollow" class="comment-author-link"', $link );
		
		return $link;
	}

	/**
	 * Add ARIA current attribute to navigation menu
	 *
	 * @param string   $nav_menu Navigation menu HTML.
	 * @param stdClass $args Menu arguments.
	 * @return string
	 */
	public function nav_menu_aria_current( $nav_menu, $args ) {
		// Add ARIA current attribute to active menu items.
		$nav_menu = preg_replace( '/(current_page_item|current-menu-item)[^<]*<a /', '$1$2 aria-current="page"><a ', $nav_menu );
		
		return $nav_menu;
	}

	/**
	 * Add ARIA attributes to posts navigation links
	 *
	 * @param string $attributes Link attributes.
	 * @return string
	 */
	public function posts_link_attributes( $attributes ) {
		// Add ARIA attributes to posts navigation links.
		$attributes .= ' aria-label="' . esc_attr__( 'Posts', 'aqualuxe' ) . '"';
		
		return $attributes;
	}

	/**
	 * Get screen reader text
	 *
	 * @param string $text Text to be hidden visually but available to screen readers.
	 * @return string
	 */
	public function get_screen_reader_text( $text ) {
		return '<span class="screen-reader-text">' . $text . '</span>';
	}

	/**
	 * Check if keyboard navigation is enabled
	 *
	 * @return bool
	 */
	public function is_keyboard_navigation_enabled() {
		return $this->get_theme_option( 'aqualuxe_enable_keyboard_navigation', true );
	}

	/**
	 * Check if high contrast mode is enabled
	 *
	 * @return bool
	 */
	public function is_high_contrast_mode_enabled() {
		// Check if high contrast mode is enabled in the customizer.
		$high_contrast_enabled = $this->get_theme_option( 'aqualuxe_enable_high_contrast', false );
		
		// If high contrast mode is not enabled in the customizer, return false.
		if ( ! $high_contrast_enabled ) {
			return false;
		}

		// Check if the user has set a preference.
		if ( isset( $_COOKIE['aqualuxe_high_contrast'] ) ) {
			return 'true' === $_COOKIE['aqualuxe_high_contrast'];
		}

		return false;
	}

	/**
	 * Get focus outline style
	 *
	 * @return string
	 */
	public function get_focus_outline_style() {
		$focus_outline_style = $this->get_theme_option( 'aqualuxe_focus_outline_style', 'default' );
		
		return $focus_outline_style;
	}
}