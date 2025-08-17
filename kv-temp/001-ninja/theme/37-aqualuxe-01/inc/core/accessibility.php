<?php
/**
 * Accessibility features for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * AquaLuxe Accessibility Class
 */
class AquaLuxe_Accessibility {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Add skip link.
		add_action( 'wp_body_open', array( $this, 'skip_link' ) );
		
		// Add ARIA landmarks.
		add_filter( 'aqualuxe_header_classes', array( $this, 'add_aria_landmark_header' ) );
		add_filter( 'aqualuxe_main_classes', array( $this, 'add_aria_landmark_main' ) );
		add_filter( 'aqualuxe_footer_classes', array( $this, 'add_aria_landmark_footer' ) );
		add_filter( 'aqualuxe_sidebar_classes', array( $this, 'add_aria_landmark_sidebar' ) );
		add_filter( 'aqualuxe_nav_menu_args', array( $this, 'add_aria_landmark_nav' ) );
		
		// Add screen reader text utility.
		add_action( 'wp_head', array( $this, 'screen_reader_text_style' ) );
		
		// Add focus outline styles.
		add_action( 'wp_head', array( $this, 'focus_styles' ) );
		
		// Add reduced motion styles.
		add_action( 'wp_head', array( $this, 'reduced_motion_styles' ) );
        
        // Add keyboard navigation for dropdown menus
        add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_args' ) );
        
        // Add ARIA attributes to pagination
        add_filter( 'navigation_markup_template', array( $this, 'navigation_markup_template' ), 10, 2 );
        
        // Add ARIA attributes to comment form
        add_filter( 'comment_form_defaults', array( $this, 'comment_form_defaults' ) );
        
        // Add ARIA attributes to search form
        add_filter( 'get_search_form', array( $this, 'search_form' ) );
	}

	/**
	 * Add skip link
	 */
	public function skip_link() {
		echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
	}

	/**
	 * Add ARIA landmark to header
	 *
	 * @param array $classes Header classes.
	 * @return array
	 */
	public function add_aria_landmark_header( $classes ) {
		$classes[] = 'site-header';
		return $classes;
	}

	/**
	 * Add ARIA landmark to main content
	 *
	 * @param array $classes Main content classes.
	 * @return array
	 */
	public function add_aria_landmark_main( $classes ) {
		$classes[] = 'site-main';
		return $classes;
	}

	/**
	 * Add ARIA landmark to footer
	 *
	 * @param array $classes Footer classes.
	 * @return array
	 */
	public function add_aria_landmark_footer( $classes ) {
		$classes[] = 'site-footer';
		return $classes;
	}

	/**
	 * Add ARIA landmark to sidebar
	 *
	 * @param array $classes Sidebar classes.
	 * @return array
	 */
	public function add_aria_landmark_sidebar( $classes ) {
		$classes[] = 'widget-area';
		return $classes;
	}

	/**
	 * Add ARIA landmark to navigation
	 *
	 * @param array $args Navigation arguments.
	 * @return array
	 */
	public function add_aria_landmark_nav( $args ) {
		if ( ! isset( $args['menu_id'] ) ) {
			return $args;
		}

		// Add ARIA attributes based on menu location.
		switch ( $args['theme_location'] ) {
			case 'primary':
				$args['container_aria_label'] = esc_attr__( 'Primary Navigation', 'aqualuxe' );
				break;
			case 'secondary':
				$args['container_aria_label'] = esc_attr__( 'Secondary Navigation', 'aqualuxe' );
				break;
			case 'footer':
				$args['container_aria_label'] = esc_attr__( 'Footer Navigation', 'aqualuxe' );
				break;
			case 'social':
				$args['container_aria_label'] = esc_attr__( 'Social Links', 'aqualuxe' );
				break;
		}

		return $args;
	}

	/**
	 * Add screen reader text style
	 */
	public function screen_reader_text_style() {
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
				font-size: 0.875rem;
				font-weight: 700;
				height: auto;
				left: 5px;
				line-height: normal;
				padding: 15px 23px 14px;
				text-decoration: none;
				top: 5px;
				width: auto;
				z-index: 100000;
			}
            
            /* Skip link focus fix */
            [tabindex="-1"]:focus {
                outline: 0 !important;
            }
            
            /* For when the skip link target needs additional padding */
            .skip-link-target {
                display: block;
                height: 1px;
                margin: -1px 0 0;
                outline: 0;
                overflow: hidden;
                width: 1px;
            }
		</style>
		<?php
	}

	/**
	 * Add focus styles
	 */
	public function focus_styles() {
		?>
		<style>
			:focus {
				outline: 2px solid var(--color-primary, #00afcc);
				outline-offset: 2px;
			}
			
			:focus:not(:focus-visible) {
				outline: none;
			}
			
			:focus-visible {
				outline: 2px solid var(--color-primary, #00afcc);
				outline-offset: 2px;
			}
            
            /* Ensure buttons and inputs have visible focus styles */
            button:focus,
            input[type="button"]:focus,
            input[type="reset"]:focus,
            input[type="submit"]:focus,
            input[type="text"]:focus,
            input[type="email"]:focus,
            input[type="url"]:focus,
            input[type="password"]:focus,
            input[type="search"]:focus,
            input[type="number"]:focus,
            input[type="tel"]:focus,
            input[type="range"]:focus,
            input[type="date"]:focus,
            input[type="month"]:focus,
            input[type="week"]:focus,
            input[type="time"]:focus,
            input[type="datetime"]:focus,
            input[type="datetime-local"]:focus,
            input[type="color"]:focus,
            textarea:focus,
            select:focus {
                outline: 2px solid var(--color-primary, #00afcc);
                outline-offset: 2px;
            }
		</style>
		<?php
	}

	/**
	 * Add reduced motion styles
	 */
	public function reduced_motion_styles() {
		?>
		<style>
			@media (prefers-reduced-motion: reduce) {
				* {
					animation-duration: 0.01ms !important;
					animation-iteration-count: 1 !important;
					transition-duration: 0.01ms !important;
					scroll-behavior: auto !important;
				}
                
                /* Specific overrides for common animations */
                .fade,
                .fade-in,
                .fade-out,
                .slide-in,
                .slide-out,
                .zoom-in,
                .zoom-out {
                    transition: none !important;
                    animation: none !important;
                }
			}
		</style>
		<?php
	}
    
    /**
     * Add keyboard navigation for dropdown menus
     *
     * @param array $args Menu arguments.
     * @return array
     */
    public function nav_menu_args( $args ) {
        // Add CSS class for keyboard navigation
        if ( ! isset( $args['menu_class'] ) ) {
            $args['menu_class'] = '';
        }
        
        $args['menu_class'] .= ' nav-menu';
        
        return $args;
    }
    
    /**
     * Add ARIA attributes to pagination
     *
     * @param string $template The default template.
     * @param string $class The class passed by the calling function.
     * @return string
     */
    public function navigation_markup_template( $template, $class ) {
        $template = '
        <nav class="navigation %1$s" aria-label="%2$s" role="navigation">
            <h2 class="screen-reader-text">%2$s</h2>
            <div class="nav-links">%3$s</div>
        </nav>';
        
        return $template;
    }
    
    /**
     * Add ARIA attributes to comment form
     *
     * @param array $defaults Comment form defaults.
     * @return array
     */
    public function comment_form_defaults( $defaults ) {
        $defaults['title_reply_before'] = '<h3 id="reply-title" class="comment-reply-title" aria-label="' . esc_attr__( 'Leave a comment', 'aqualuxe' ) . '">';
        $defaults['title_reply_after'] = '</h3>';
        
        return $defaults;
    }
    
    /**
     * Add ARIA attributes to search form
     *
     * @param string $form Search form HTML.
     * @return string
     */
    public function search_form( $form ) {
        $form = str_replace( 'class="search-form"', 'class="search-form" role="search" aria-label="' . esc_attr__( 'Site Search', 'aqualuxe' ) . '"', $form );
        $form = str_replace( 'class="search-field"', 'class="search-field" aria-label="' . esc_attr__( 'Search', 'aqualuxe' ) . '"', $form );
        
        return $form;
    }

	/**
	 * Get screen reader text
	 *
	 * @param string $text Text to hide visually.
	 * @return string
	 */
	public static function get_screen_reader_text( $text ) {
		return '<span class="screen-reader-text">' . $text . '</span>';
	}

	/**
	 * Create accessible modal
	 *
	 * @param string $id Modal ID.
	 * @param string $title Modal title.
	 * @param string $content Modal content.
	 * @param array  $args Modal arguments.
	 * @return string
	 */
	public static function get_modal( $id, $title, $content, $args = array() ) {
		$defaults = array(
			'class'        => '',
			'close_text'   => esc_html__( 'Close', 'aqualuxe' ),
			'show_title'   => true,
			'initial_state' => 'hidden',
		);

		$args = wp_parse_args( $args, $defaults );

		$modal_classes = 'modal';
		if ( ! empty( $args['class'] ) ) {
			$modal_classes .= ' ' . $args['class'];
		}

		$output = '<div id="' . esc_attr( $id ) . '" class="' . esc_attr( $modal_classes ) . '" role="dialog" aria-modal="true" aria-labelledby="' . esc_attr( $id . '-title' ) . '" aria-hidden="' . ( 'hidden' === $args['initial_state'] ? 'true' : 'false' ) . '" tabindex="-1">';
		$output .= '<div class="modal-dialog">';
		$output .= '<div class="modal-content">';
		$output .= '<div class="modal-header">';
		
		if ( $args['show_title'] ) {
			$output .= '<h2 id="' . esc_attr( $id . '-title' ) . '" class="modal-title">' . $title . '</h2>';
		} else {
			$output .= '<h2 id="' . esc_attr( $id . '-title' ) . '" class="screen-reader-text">' . $title . '</h2>';
		}
		
		$output .= '<button type="button" class="modal-close" aria-label="' . esc_attr( $args['close_text'] ) . '">';
		$output .= '<span aria-hidden="true">&times;</span>';
		$output .= '<span class="screen-reader-text">' . esc_html( $args['close_text'] ) . '</span>';
		$output .= '</button>';
		$output .= '</div>';
		$output .= '<div class="modal-body">';
		$output .= $content;
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';
		$output .= '</div>';

		return $output;
	}

	/**
	 * Create accessible tabs
	 *
	 * @param array $tabs Array of tabs with 'title' and 'content' keys.
	 * @param array $args Tab arguments.
	 * @return string
	 */
	public static function get_tabs( $tabs, $args = array() ) {
		if ( empty( $tabs ) ) {
			return '';
		}

		$defaults = array(
			'id'           => 'tabs-' . uniqid(),
			'class'        => '',
			'active_index' => 0,
		);

		$args = wp_parse_args( $args, $defaults );

		$tabs_classes = 'tabs';
		if ( ! empty( $args['class'] ) ) {
			$tabs_classes .= ' ' . $args['class'];
		}

		$output = '<div id="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( $tabs_classes ) . '">';
		
		// Tab list.
		$output .= '<div class="tabs-nav" role="tablist">';
		foreach ( $tabs as $index => $tab ) {
			$tab_id = $args['id'] . '-tab-' . $index;
			$panel_id = $args['id'] . '-panel-' . $index;
			$is_active = $index === $args['active_index'];
			
			$output .= '<button id="' . esc_attr( $tab_id ) . '" class="tab-button' . ( $is_active ? ' active' : '' ) . '" role="tab" aria-selected="' . ( $is_active ? 'true' : 'false' ) . '" aria-controls="' . esc_attr( $panel_id ) . '" tabindex="' . ( $is_active ? '0' : '-1' ) . '">';
			$output .= $tab['title'];
			$output .= '</button>';
		}
		$output .= '</div>';
		
		// Tab panels.
		foreach ( $tabs as $index => $tab ) {
			$tab_id = $args['id'] . '-tab-' . $index;
			$panel_id = $args['id'] . '-panel-' . $index;
			$is_active = $index === $args['active_index'];
			
			$output .= '<div id="' . esc_attr( $panel_id ) . '" class="tab-panel' . ( $is_active ? ' active' : '' ) . '" role="tabpanel" aria-labelledby="' . esc_attr( $tab_id ) . '" tabindex="0"' . ( ! $is_active ? ' hidden' : '' ) . '>';
			$output .= $tab['content'];
			$output .= '</div>';
		}
		
		$output .= '</div>';

		return $output;
	}
    
    /**
     * Create accessible accordion
     *
     * @param array $items Array of items with 'title' and 'content' keys.
     * @param array $args Accordion arguments.
     * @return string
     */
    public static function get_accordion( $items, $args = array() ) {
        if ( empty( $items ) ) {
            return '';
        }
        
        $defaults = array(
            'id'           => 'accordion-' . uniqid(),
            'class'        => '',
            'multi_expand' => false,
            'active_index' => -1, // -1 means all closed
        );
        
        $args = wp_parse_args( $args, $defaults );
        
        $accordion_classes = 'accordion';
        if ( ! empty( $args['class'] ) ) {
            $accordion_classes .= ' ' . $args['class'];
        }
        
        $output = '<div id="' . esc_attr( $args['id'] ) . '" class="' . esc_attr( $accordion_classes ) . '" data-multi-expand="' . ( $args['multi_expand'] ? 'true' : 'false' ) . '">';
        
        foreach ( $items as $index => $item ) {
            $header_id = $args['id'] . '-header-' . $index;
            $panel_id = $args['id'] . '-panel-' . $index;
            $is_active = $index === $args['active_index'];
            
            $output .= '<div class="accordion-item">';
            $output .= '<h3 id="' . esc_attr( $header_id ) . '" class="accordion-header">';
            $output .= '<button class="accordion-button' . ( $is_active ? ' active' : '' ) . '" aria-expanded="' . ( $is_active ? 'true' : 'false' ) . '" aria-controls="' . esc_attr( $panel_id ) . '">';
            $output .= $item['title'];
            $output .= '<span class="accordion-icon" aria-hidden="true"></span>';
            $output .= '</button>';
            $output .= '</h3>';
            $output .= '<div id="' . esc_attr( $panel_id ) . '" class="accordion-panel" role="region" aria-labelledby="' . esc_attr( $header_id ) . '"' . ( ! $is_active ? ' hidden' : '' ) . '>';
            $output .= '<div class="accordion-content">';
            $output .= $item['content'];
            $output .= '</div>';
            $output .= '</div>';
            $output .= '</div>';
        }
        
        $output .= '</div>';
        
        return $output;
    }
    
    /**
     * Create accessible tooltip
     *
     * @param string $content Content to add tooltip to.
     * @param string $tooltip Tooltip text.
     * @param array  $args Tooltip arguments.
     * @return string
     */
    public static function get_tooltip( $content, $tooltip, $args = array() ) {
        $defaults = array(
            'id'           => 'tooltip-' . uniqid(),
            'class'        => '',
            'position'     => 'top', // top, right, bottom, left
        );
        
        $args = wp_parse_args( $args, $defaults );
        
        $tooltip_classes = 'tooltip-container';
        if ( ! empty( $args['class'] ) ) {
            $tooltip_classes .= ' ' . $args['class'];
        }
        
        $output = '<span class="' . esc_attr( $tooltip_classes ) . '">';
        $output .= '<span class="tooltip-content" aria-describedby="' . esc_attr( $args['id'] ) . '">' . $content . '</span>';
        $output .= '<span id="' . esc_attr( $args['id'] ) . '" role="tooltip" class="tooltip tooltip-' . esc_attr( $args['position'] ) . '">' . $tooltip . '</span>';
        $output .= '</span>';
        
        return $output;
    }
}

// Initialize accessibility features.
new AquaLuxe_Accessibility();