<?php
/**
 * Main Theme Class
 *
 * @package AquaLuxe
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AquaLuxe_Theme {
	
	/**
	 * Single instance of the class
	 *
	 * @var AquaLuxe_Theme
	 */
	protected static $_instance = null;

	/**
	 * Main instance
	 *
	 * @return AquaLuxe_Theme
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->init_hooks();
	}

	/**
	 * Initialize hooks
	 */
	private function init_hooks() {
		add_action( 'wp_head', array( $this, 'add_meta_tags' ), 5 );
		add_action( 'wp_head', array( $this, 'add_schema_markup' ), 10 );
		add_action( 'wp_footer', array( $this, 'add_structured_data' ), 20 );
		add_filter( 'body_class', array( $this, 'body_classes' ) );
		add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_args' ) );
	}

	/**
	 * Add meta tags to head
	 */
	public function add_meta_tags() {
		// Viewport meta tag for responsive design
		echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">' . "\n";
		
		// Theme color
		echo '<meta name="theme-color" content="#14b8a6">' . "\n";
		
		// Open Graph tags
		$this->add_open_graph_tags();
		
		// Twitter Card tags
		$this->add_twitter_card_tags();
	}

	/**
	 * Add Open Graph tags
	 */
	private function add_open_graph_tags() {
		global $post;
		
		$site_name = get_bloginfo( 'name' );
		$site_description = get_bloginfo( 'description' );
		
		echo '<meta property="og:site_name" content="' . esc_attr( $site_name ) . '">' . "\n";
		echo '<meta property="og:locale" content="' . esc_attr( get_locale() ) . '">' . "\n";
		
		if ( is_singular() && $post ) {
			$title = get_the_title( $post->ID );
			$description = get_post_meta( $post->ID, '_yoast_wpseo_metadesc', true );
			if ( ! $description ) {
				$description = wp_trim_words( get_post_field( 'post_content', $post->ID ), 25 );
			}
			$image = get_the_post_thumbnail_url( $post->ID, 'large' );
			
			echo '<meta property="og:type" content="article">' . "\n";
			echo '<meta property="og:title" content="' . esc_attr( $title ) . '">' . "\n";
			echo '<meta property="og:description" content="' . esc_attr( $description ) . '">' . "\n";
			echo '<meta property="og:url" content="' . esc_url( get_permalink( $post->ID ) ) . '">' . "\n";
			
			if ( $image ) {
				echo '<meta property="og:image" content="' . esc_url( $image ) . '">' . "\n";
			}
		} else {
			echo '<meta property="og:type" content="website">' . "\n";
			echo '<meta property="og:title" content="' . esc_attr( $site_name ) . '">' . "\n";
			echo '<meta property="og:description" content="' . esc_attr( $site_description ) . '">' . "\n";
			echo '<meta property="og:url" content="' . esc_url( home_url() ) . '">' . "\n";
		}
	}

	/**
	 * Add Twitter Card tags
	 */
	private function add_twitter_card_tags() {
		echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
		
		$twitter_handle = get_theme_mod( 'aqualuxe_twitter_handle', '' );
		if ( $twitter_handle ) {
			echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_handle ) . '">' . "\n";
		}
	}

	/**
	 * Add schema markup to head
	 */
	public function add_schema_markup() {
		$schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'Organization',
			'name' => get_bloginfo( 'name' ),
			'url' => home_url(),
			'description' => get_bloginfo( 'description' ),
		);
		
		$logo = get_theme_mod( 'custom_logo' );
		if ( $logo ) {
			$logo_url = wp_get_attachment_image_url( $logo, 'full' );
			$schema['logo'] = $logo_url;
		}
		
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}

	/**
	 * Add structured data to footer
	 */
	public function add_structured_data() {
		if ( is_product() && function_exists( 'wc_get_product' ) ) {
			$this->add_product_schema();
		}
	}

	/**
	 * Add product schema for WooCommerce products
	 */
	private function add_product_schema() {
		global $product;
		
		if ( ! $product ) {
			return;
		}
		
		$schema = array(
			'@context' => 'https://schema.org',
			'@type' => 'Product',
			'name' => $product->get_name(),
			'description' => wp_strip_all_tags( $product->get_description() ),
			'sku' => $product->get_sku(),
			'offers' => array(
				'@type' => 'Offer',
				'price' => $product->get_price(),
				'priceCurrency' => get_woocommerce_currency(),
				'availability' => $product->is_in_stock() ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
				'url' => get_permalink( $product->get_id() ),
			),
		);
		
		$image = wp_get_attachment_image_url( $product->get_image_id(), 'large' );
		if ( $image ) {
			$schema['image'] = $image;
		}
		
		echo '<script type="application/ld+json">' . wp_json_encode( $schema, JSON_UNESCAPED_SLASHES ) . '</script>' . "\n";
	}

	/**
	 * Add custom body classes
	 */
	public function body_classes( $classes ) {
		// Add class for WooCommerce status
		if ( aqualuxe_is_woocommerce_active() ) {
			$classes[] = 'woocommerce-active';
		} else {
			$classes[] = 'woocommerce-inactive';
		}
		
		// Add class for customizer preview
		if ( is_customize_preview() ) {
			$classes[] = 'customizer-preview';
		}
		
		// Add class for dark mode
		if ( get_theme_mod( 'aqualuxe_dark_mode_default', false ) ) {
			$classes[] = 'dark-mode-default';
		}
		
		// Add page-specific classes
		if ( is_page_template() ) {
			$template = str_replace( '.php', '', basename( get_page_template() ) );
			$classes[] = 'page-template-' . $template;
		}
		
		return $classes;
	}

	/**
	 * Modify navigation menu arguments
	 */
	public function nav_menu_args( $args ) {
		// Add ARIA labels for accessibility
		if ( ! isset( $args['container_aria_label'] ) && isset( $args['theme_location'] ) ) {
			switch ( $args['theme_location'] ) {
				case 'primary':
					$args['container_aria_label'] = __( 'Primary Navigation', 'aqualuxe' );
					break;
				case 'secondary':
					$args['container_aria_label'] = __( 'Secondary Navigation', 'aqualuxe' );
					break;
				case 'footer':
					$args['container_aria_label'] = __( 'Footer Navigation', 'aqualuxe' );
					break;
				case 'mobile':
					$args['container_aria_label'] = __( 'Mobile Navigation', 'aqualuxe' );
					break;
			}
		}
		
		return $args;
	}
}