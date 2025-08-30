<?php
/**
 * WooCommerce Product Class
 *
 * @package AquaLuxe
 * @subpackage WooCommerce
 */

namespace AquaLuxe\WooCommerce;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Product Class
 *
 * Handles product customization for WooCommerce.
 */
class Product {

	/**
	 * Constructor
	 */
	public function __construct() {
		// Product loop modifications.
		add_action( 'woocommerce_before_shop_loop_item', array( $this, 'product_wrapper_start' ), 5 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_wrapper_end' ), 15 );
		
		// Product image wrapper.
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrapper_start' ), 5 );
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_image_wrapper_end' ), 15 );
		
		// Product badges.
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_badges' ), 10 );
		
		// Product content wrapper.
		add_action( 'woocommerce_before_shop_loop_item_title', array( $this, 'product_content_wrapper_start' ), 20 );
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_content_wrapper_end' ), 10 );
		
		// Product action buttons.
		add_action( 'woocommerce_after_shop_loop_item', array( $this, 'product_action_buttons' ), 5 );
		
		// Product card elements.
		add_action( 'woocommerce_shop_loop_item_title', array( $this, 'product_category' ), 5 );
		add_action( 'woocommerce_after_shop_loop_item_title', array( $this, 'product_short_description' ), 5 );
		
		// Single product modifications.
		add_action( 'woocommerce_before_single_product_summary', array( $this, 'single_product_wrapper_start' ), 5 );
		add_action( 'woocommerce_after_single_product_summary', array( $this, 'single_product_wrapper_end' ), 15 );
		
		// Single product image modifications.
		add_filter( 'woocommerce_single_product_image_gallery_classes', array( $this, 'single_product_gallery_classes' ) );
		add_filter( 'woocommerce_single_product_image_thumbnail_html', array( $this, 'single_product_image_thumbnail_html' ), 10, 2 );
		
		// Product tabs.
		add_filter( 'woocommerce_product_tabs', array( $this, 'customize_product_tabs' ) );
		add_filter( 'woocommerce_product_reviews_tab_title', array( $this, 'customize_reviews_tab_title' ) );
		
		// Product meta.
		add_action( 'woocommerce_product_meta_start', array( $this, 'product_meta_wrapper_start' ) );
		add_action( 'woocommerce_product_meta_end', array( $this, 'product_meta_wrapper_end' ) );
		
		// Related products.
		add_filter( 'woocommerce_output_related_products_args', array( $this, 'related_products_args' ) );
		add_action( 'woocommerce_before_related_products', array( $this, 'related_products_wrapper_start' ) );
		add_action( 'woocommerce_after_related_products', array( $this, 'related_products_wrapper_end' ) );
		
		// Upsells.
		add_filter( 'woocommerce_upsell_display_args', array( $this, 'upsell_products_args' ) );
		add_action( 'woocommerce_before_upsell_products', array( $this, 'upsell_products_wrapper_start' ) );
		add_action( 'woocommerce_after_upsell_products', array( $this, 'upsell_products_wrapper_end' ) );
		
		// Product gallery.
		add_filter( 'woocommerce_gallery_image_size', array( $this, 'gallery_image_size' ) );
		add_filter( 'woocommerce_gallery_thumbnail_size', array( $this, 'gallery_thumbnail_size' ) );
		
		// Product structured data.
		add_filter( 'woocommerce_structured_data_product', array( $this, 'enhance_product_structured_data' ), 10, 2 );
		
		// Stock status customization.
		add_filter( 'woocommerce_get_availability_text', array( $this, 'custom_stock_availability_text' ), 10, 2 );
		add_filter( 'woocommerce_get_availability_class', array( $this, 'custom_stock_availability_class' ), 10, 2 );
		
		// Product variation swatches.
		add_filter( 'woocommerce_dropdown_variation_attribute_options_html', array( $this, 'variation_swatches' ), 10, 2 );
	}

	/**
	 * Product wrapper start
	 */
	public function product_wrapper_start() {
		$product_card_style = get_theme_mod( 'aqualuxe_product_card_style', 'standard' );
		echo '<div class="aqualuxe-product-card product-style-' . esc_attr( $product_card_style ) . '">';
	}

	/**
	 * Product wrapper end
	 */
	public function product_wrapper_end() {
		echo '</div><!-- .aqualuxe-product-card -->';
	}

	/**
	 * Product image wrapper start
	 */
	public function product_image_wrapper_start() {
		$image_hover = get_theme_mod( 'aqualuxe_product_image_hover', 'zoom' );
		echo '<div class="aqualuxe-product-image-wrapper hover-effect-' . esc_attr( $image_hover ) . '">';
	}

	/**
	 * Product image wrapper end
	 */
	public function product_image_wrapper_end() {
		global $product;
		
		// Add second image for gallery hover effect.
		$image_hover = get_theme_mod( 'aqualuxe_product_image_hover', 'zoom' );
		if ( 'gallery' === $image_hover ) {
			$attachment_ids = $product->get_gallery_image_ids();
			if ( ! empty( $attachment_ids[0] ) ) {
				echo wp_get_attachment_image( $attachment_ids[0], 'woocommerce_thumbnail', false, array( 'class' => 'aqualuxe-product-hover-image' ) );
			}
		}
		
		// Add quick view button.
		$show_quick_view = get_theme_mod( 'aqualuxe_product_quick_view', true );
		if ( $show_quick_view ) {
			echo '<div class="aqualuxe-product-buttons">';
			echo '<a href="#" class="aqualuxe-quick-view-button" data-product-id="' . esc_attr( $product->get_id() ) . '">';
			echo '<i class="fas fa-eye"></i>';
			echo '<span>' . esc_html__( 'Quick View', 'aqualuxe' ) . '</span>';
			echo '</a>';
			echo '</div>';
		}
		
		echo '</div><!-- .aqualuxe-product-image-wrapper -->';
	}

	/**
	 * Product badges
	 */
	public function product_badges() {
		global $product;
		
		echo '<div class="aqualuxe-product-badges">';
		
		// Sale badge.
		if ( $product->is_on_sale() ) {
			$sale_text = esc_html__( 'Sale', 'aqualuxe' );
			
			// Show percentage if it's a simple product.
			if ( $product->is_type( 'simple' ) ) {
				$regular_price = $product->get_regular_price();
				$sale_price = $product->get_sale_price();
				
				if ( $regular_price && $sale_price ) {
					$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
					$sale_text = '-' . $percentage . '%';
				}
			}
			
			echo '<span class="aqualuxe-badge sale">' . esc_html( $sale_text ) . '</span>';
		}
		
		// New badge (products less than 30 days old).
		$days_as_new = 30;
		$created_date = strtotime( $product->get_date_created() );
		if ( ( time() - $created_date ) < ( $days_as_new * DAY_IN_SECONDS ) ) {
			echo '<span class="aqualuxe-badge new">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
		}
		
		// Featured badge.
		if ( $product->is_featured() ) {
			echo '<span class="aqualuxe-badge featured">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
		}
		
		// Out of stock badge.
		if ( ! $product->is_in_stock() ) {
			echo '<span class="aqualuxe-badge out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
		}
		
		echo '</div>';
	}

	/**
	 * Product content wrapper start
	 */
	public function product_content_wrapper_start() {
		echo '<div class="aqualuxe-product-content">';
	}

	/**
	 * Product content wrapper end
	 */
	public function product_content_wrapper_end() {
		echo '</div><!-- .aqualuxe-product-content -->';
	}

	/**
	 * Product action buttons
	 */
	public function product_action_buttons() {
		global $product;
		
		echo '<div class="aqualuxe-product-actions">';
		
		// Add to cart button is already added by WooCommerce.
		
		// Wishlist button.
		$show_wishlist = get_theme_mod( 'aqualuxe_product_wishlist', true );
		if ( $show_wishlist ) {
			echo '<a href="#" class="aqualuxe-wishlist-button" data-product-id="' . esc_attr( $product->get_id() ) . '">';
			echo '<i class="far fa-heart"></i>';
			echo '</a>';
		}
		
		// Compare button.
		$show_compare = get_theme_mod( 'aqualuxe_product_compare', true );
		if ( $show_compare ) {
			echo '<a href="#" class="aqualuxe-compare-button" data-product-id="' . esc_attr( $product->get_id() ) . '">';
			echo '<i class="fas fa-exchange-alt"></i>';
			echo '</a>';
		}
		
		echo '</div>';
	}

	/**
	 * Product category
	 */
	public function product_category() {
		global $product;
		
		$product_card_elements = get_theme_mod( 'aqualuxe_product_card_elements', array( 'title', 'rating', 'price', 'add-to-cart' ) );
		
		if ( in_array( 'categories', $product_card_elements, true ) ) {
			echo '<div class="aqualuxe-product-category">';
			echo wc_get_product_category_list( $product->get_id(), ', ', '<span>', '</span>' );
			echo '</div>';
		}
	}

	/**
	 * Product short description
	 */
	public function product_short_description() {
		global $product;
		
		$product_card_elements = get_theme_mod( 'aqualuxe_product_card_elements', array( 'title', 'rating', 'price', 'add-to-cart' ) );
		
		if ( in_array( 'excerpt', $product_card_elements, true ) ) {
			$short_description = $product->get_short_description();
			if ( ! empty( $short_description ) ) {
				echo '<div class="aqualuxe-product-excerpt">';
				echo wp_kses_post( wp_trim_words( $short_description, 10 ) );
				echo '</div>';
			}
		}
	}

	/**
	 * Single product wrapper start
	 */
	public function single_product_wrapper_start() {
		$product_layout = get_theme_mod( 'aqualuxe_product_layout', 'standard' );
		echo '<div class="aqualuxe-single-product-wrapper product-layout-' . esc_attr( $product_layout ) . '">';
	}

	/**
	 * Single product wrapper end
	 */
	public function single_product_wrapper_end() {
		echo '</div><!-- .aqualuxe-single-product-wrapper -->';
	}

	/**
	 * Single product gallery classes
	 *
	 * @param array $classes Gallery classes.
	 * @return array Modified gallery classes.
	 */
	public function single_product_gallery_classes( $classes ) {
		$gallery_style = get_theme_mod( 'aqualuxe_product_gallery_style', 'horizontal' );
		$classes[] = 'aqualuxe-gallery-style-' . $gallery_style;
		
		return $classes;
	}

	/**
	 * Single product image thumbnail HTML
	 *
	 * @param string $html Thumbnail HTML.
	 * @param int    $attachment_id Attachment ID.
	 * @return string Modified thumbnail HTML.
	 */
	public function single_product_image_thumbnail_html( $html, $attachment_id ) {
		// Add lightbox attribute if enabled.
		$image_lightbox = get_theme_mod( 'aqualuxe_product_image_lightbox', true );
		if ( $image_lightbox ) {
			$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
			if ( $full_size_image ) {
				$html = str_replace( '<a', '<a data-fancybox="gallery" data-caption="' . esc_attr( wp_get_attachment_caption( $attachment_id ) ) . '"', $html );
			}
		}
		
		return $html;
	}

	/**
	 * Customize product tabs
	 *
	 * @param array $tabs Product tabs.
	 * @return array Modified product tabs.
	 */
	public function customize_product_tabs( $tabs ) {
		// Change description tab title.
		if ( isset( $tabs['description'] ) ) {
			$tabs['description']['title'] = esc_html__( 'Product Details', 'aqualuxe' );
		}
		
		// Add custom tab.
		$tabs['shipping'] = array(
			'title'    => esc_html__( 'Shipping & Returns', 'aqualuxe' ),
			'priority' => 30,
			'callback' => array( $this, 'shipping_tab_content' ),
		);
		
		// Reorder tabs.
		if ( isset( $tabs['reviews'] ) ) {
			$tabs['reviews']['priority'] = 40;
		}
		
		return $tabs;
	}

	/**
	 * Customize reviews tab title
	 *
	 * @param string $title Tab title.
	 * @return string Modified tab title.
	 */
	public function customize_reviews_tab_title( $title ) {
		global $product;
		
		$count = $product->get_review_count();
		if ( $count > 0 ) {
			/* translators: %d: review count */
			$title = sprintf( esc_html__( 'Reviews (%d)', 'aqualuxe' ), $count );
		}
		
		return $title;
	}

	/**
	 * Shipping tab content
	 */
	public function shipping_tab_content() {
		// This content could come from theme options.
		?>
		<h3><?php esc_html_e( 'Shipping Information', 'aqualuxe' ); ?></h3>
		<p><?php esc_html_e( 'We ship worldwide with expedited delivery options available. Free standard shipping on all orders over $100.', 'aqualuxe' ); ?></p>
		
		<h3><?php esc_html_e( 'Return Policy', 'aqualuxe' ); ?></h3>
		<p><?php esc_html_e( 'If you are not completely satisfied with your purchase, you may return it within 30 days for a full refund or exchange.', 'aqualuxe' ); ?></p>
		<?php
	}

	/**
	 * Product meta wrapper start
	 */
	public function product_meta_wrapper_start() {
		echo '<div class="aqualuxe-product-meta">';
	}

	/**
	 * Product meta wrapper end
	 */
	public function product_meta_wrapper_end() {
		echo '</div><!-- .aqualuxe-product-meta -->';
	}

	/**
	 * Related products args
	 *
	 * @param array $args Related products args.
	 * @return array Modified related products args.
	 */
	public function related_products_args( $args ) {
		$args['posts_per_page'] = get_theme_mod( 'aqualuxe_product_related_count', 4 );
		$args['columns'] = 4;
		
		return $args;
	}

	/**
	 * Related products wrapper start
	 */
	public function related_products_wrapper_start() {
		echo '<div class="aqualuxe-related-products-wrapper">';
	}

	/**
	 * Related products wrapper end
	 */
	public function related_products_wrapper_end() {
		echo '</div><!-- .aqualuxe-related-products-wrapper -->';
	}

	/**
	 * Upsell products args
	 *
	 * @param array $args Upsell products args.
	 * @return array Modified upsell products args.
	 */
	public function upsell_products_args( $args ) {
		$args['posts_per_page'] = 4;
		$args['columns'] = 4;
		
		return $args;
	}

	/**
	 * Upsell products wrapper start
	 */
	public function upsell_products_wrapper_start() {
		echo '<div class="aqualuxe-upsell-products-wrapper">';
	}

	/**
	 * Upsell products wrapper end
	 */
	public function upsell_products_wrapper_end() {
		echo '</div><!-- .aqualuxe-upsell-products-wrapper -->';
	}

	/**
	 * Gallery image size
	 *
	 * @param string $size Image size.
	 * @return string Modified image size.
	 */
	public function gallery_image_size( $size ) {
		return 'woocommerce_single';
	}

	/**
	 * Gallery thumbnail size
	 *
	 * @param string $size Thumbnail size.
	 * @return string Modified thumbnail size.
	 */
	public function gallery_thumbnail_size( $size ) {
		return 'woocommerce_gallery_thumbnail';
	}

	/**
	 * Enhance product structured data
	 *
	 * @param array  $markup Structured data markup.
	 * @param object $product Product object.
	 * @return array Modified structured data markup.
	 */
	public function enhance_product_structured_data( $markup, $product ) {
		// Add brand information if available.
		$brand_taxonomy = 'pa_brand'; // Adjust to your brand taxonomy.
		$brands = get_the_terms( $product->get_id(), $brand_taxonomy );
		
		if ( $brands && ! is_wp_error( $brands ) ) {
			$brand = reset( $brands );
			$markup['brand'] = array(
				'@type' => 'Brand',
				'name'  => $brand->name,
			);
		}
		
		// Add review count and rating.
		if ( $product->get_review_count() > 0 ) {
			$markup['aggregateRating'] = array(
				'@type'       => 'AggregateRating',
				'ratingValue' => $product->get_average_rating(),
				'reviewCount' => $product->get_review_count(),
			);
		}
		
		return $markup;
	}

	/**
	 * Custom stock availability text
	 *
	 * @param string $text Stock text.
	 * @param object $product Product object.
	 * @return string Modified stock text.
	 */
	public function custom_stock_availability_text( $text, $product ) {
		if ( ! $product->is_in_stock() ) {
			$text = esc_html__( 'Out of Stock', 'aqualuxe' );
		} elseif ( $product->managing_stock() && $product->is_on_backorder( 1 ) ) {
			$text = esc_html__( 'Available on backorder', 'aqualuxe' );
		} elseif ( $product->managing_stock() ) {
			$stock = $product->get_stock_quantity();
			
			if ( $stock <= get_option( 'woocommerce_notify_low_stock_amount' ) && $stock > 0 ) {
				/* translators: %d: stock quantity */
				$text = sprintf( esc_html__( 'Only %d left in stock', 'aqualuxe' ), $stock );
			} elseif ( $stock > 0 ) {
				$text = esc_html__( 'In Stock', 'aqualuxe' );
			}
		} else {
			$text = esc_html__( 'In Stock', 'aqualuxe' );
		}
		
		return $text;
	}

	/**
	 * Custom stock availability class
	 *
	 * @param string $class Stock class.
	 * @param object $product Product object.
	 * @return string Modified stock class.
	 */
	public function custom_stock_availability_class( $class, $product ) {
		if ( ! $product->is_in_stock() ) {
			$class = 'out-of-stock';
		} elseif ( $product->managing_stock() && $product->is_on_backorder( 1 ) ) {
			$class = 'available-on-backorder';
		} elseif ( $product->managing_stock() ) {
			$stock = $product->get_stock_quantity();
			
			if ( $stock <= get_option( 'woocommerce_notify_low_stock_amount' ) && $stock > 0 ) {
				$class = 'low-stock';
			} else {
				$class = 'in-stock';
			}
		} else {
			$class = 'in-stock';
		}
		
		return $class;
	}

	/**
	 * Variation swatches
	 *
	 * @param string $html Dropdown HTML.
	 * @param array  $args Dropdown args.
	 * @return string Modified dropdown HTML.
	 */
	public function variation_swatches( $html, $args ) {
		$attribute = $args['attribute'];
		$options   = $args['options'];
		$product   = $args['product'];
		$name      = $args['name'] ? $args['name'] : 'attribute_' . sanitize_title( $attribute );
		$id        = $args['id'] ? $args['id'] : sanitize_title( $attribute );
		$class     = $args['class'];
		
		// Only convert to swatches for color and image attributes.
		$swatch_types = array( 'color', 'image' );
		$attribute_name = sanitize_title( $attribute );
		
		// Check if this is a color attribute.
		if ( strpos( $attribute_name, 'color' ) !== false || strpos( $attribute_name, 'colour' ) !== false ) {
			$swatch_type = 'color';
		} else {
			// Not a swatch attribute, return default dropdown.
			return $html;
		}
		
		// Start building the swatch HTML.
		$swatches_html = '<div class="aqualuxe-variation-swatches swatch-type-' . esc_attr( $swatch_type ) . '">';
		
		if ( ! empty( $options ) ) {
			if ( $product && taxonomy_exists( $attribute ) ) {
				// Get terms if this is a taxonomy.
				$terms = wc_get_product_terms( $product->get_id(), $attribute, array( 'fields' => 'all' ) );
				
				foreach ( $terms as $term ) {
					if ( in_array( $term->slug, $options, true ) ) {
						$selected = sanitize_title( $args['selected'] ) === $term->slug ? 'selected' : '';
						
						// Get color value from term meta or use the term name as fallback.
						$color = get_term_meta( $term->term_id, 'color', true );
						$color = $color ? $color : $term->name;
						
						$swatches_html .= '<div class="aqualuxe-swatch ' . esc_attr( $selected ) . '" data-value="' . esc_attr( $term->slug ) . '" title="' . esc_attr( $term->name ) . '">';
						
						if ( $swatch_type === 'color' ) {
							$swatches_html .= '<span class="aqualuxe-swatch-color" style="background-color:' . esc_attr( $color ) . ';"></span>';
						}
						
						$swatches_html .= '</div>';
					}
				}
			} else {
				// This is a custom attribute, not a taxonomy.
				foreach ( $options as $option ) {
					$selected = sanitize_title( $args['selected'] ) === $option ? 'selected' : '';
					
					$swatches_html .= '<div class="aqualuxe-swatch ' . esc_attr( $selected ) . '" data-value="' . esc_attr( $option ) . '" title="' . esc_attr( $option ) . '">';
					
					if ( $swatch_type === 'color' ) {
						$swatches_html .= '<span class="aqualuxe-swatch-color" style="background-color:' . esc_attr( $option ) . ';"></span>';
					}
					
					$swatches_html .= '</div>';
				}
			}
		}
		
		$swatches_html .= '</div>';
		
		// Add the original dropdown for accessibility and fallback.
		$swatches_html .= '<div class="aqualuxe-variation-dropdown" style="display:none;">' . $html . '</div>';
		
		// Add JavaScript to handle swatch selection.
		$swatches_html .= '
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".aqualuxe-swatch").on("click", function() {
				var $this = $(this);
				var $parent = $this.closest(".aqualuxe-variation-swatches");
				var $dropdown = $parent.next(".aqualuxe-variation-dropdown").find("select");
				
				// Update dropdown value.
				$dropdown.val($this.data("value")).trigger("change");
				
				// Update swatch selection.
				$parent.find(".aqualuxe-swatch").removeClass("selected");
				$this.addClass("selected");
			});
		});
		</script>
		';
		
		return $swatches_html;
	}
}

// Initialize the class.
new Product();