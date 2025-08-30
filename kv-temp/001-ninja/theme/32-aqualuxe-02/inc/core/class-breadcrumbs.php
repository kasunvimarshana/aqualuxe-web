<?php
/**
 * AquaLuxe Breadcrumbs
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.2.0
 */

namespace AquaLuxe\Core;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Breadcrumbs Class
 *
 * Handles breadcrumb navigation for the theme.
 *
 * @since 1.2.0
 */
class Breadcrumbs {

	/**
	 * Breadcrumb separator.
	 *
	 * @var string
	 */
	private $separator;

	/**
	 * Breadcrumb home text.
	 *
	 * @var string
	 */
	private $home_text;

	/**
	 * Breadcrumb home URL.
	 *
	 * @var string
	 */
	private $home_url;

	/**
	 * Whether to show the home link.
	 *
	 * @var bool
	 */
	private $show_home;

	/**
	 * Whether to show the current page.
	 *
	 * @var bool
	 */
	private $show_current;

	/**
	 * Whether to show breadcrumbs on the front page.
	 *
	 * @var bool
	 */
	private $show_on_front;

	/**
	 * Breadcrumb items.
	 *
	 * @var array
	 */
	private $items = array();

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		// Set default properties.
		$this->separator    = get_theme_mod( 'aqualuxe_breadcrumb_separator', '/' );
		$this->home_text    = get_theme_mod( 'aqualuxe_breadcrumb_home_text', __( 'Home', 'aqualuxe' ) );
		$this->home_url     = home_url( '/' );
		$this->show_home    = get_theme_mod( 'aqualuxe_breadcrumb_show_home', true );
		$this->show_current = get_theme_mod( 'aqualuxe_breadcrumb_show_current', true );
		$this->show_on_front = get_theme_mod( 'aqualuxe_breadcrumb_show_on_front', false );
	}

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function register_hooks() {
		// Skip if breadcrumbs are disabled.
		if ( ! get_theme_mod( 'aqualuxe_enable_breadcrumbs', true ) ) {
			return;
		}
		
		// Add breadcrumbs to theme.
		add_action( 'aqualuxe_before_main_content', array( $this, 'render_breadcrumbs' ) );
		
		// Add breadcrumbs to WooCommerce.
		if ( class_exists( 'WooCommerce' ) ) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
			add_action( 'woocommerce_before_main_content', array( $this, 'render_breadcrumbs' ), 20 );
		}
		
		// Add filter for breadcrumbs.
		add_filter( 'aqualuxe_breadcrumbs', array( $this, 'get_breadcrumbs' ) );
	}

	/**
	 * Render breadcrumbs.
	 *
	 * @return void
	 */
	public function render_breadcrumbs() {
		// Skip on front page if disabled.
		if ( is_front_page() && ! $this->show_on_front ) {
			return;
		}
		
		// Get breadcrumbs.
		$breadcrumbs = $this->get_breadcrumbs();
		
		if ( empty( $breadcrumbs ) ) {
			return;
		}
		
		// Start breadcrumb markup.
		$html = '<div class="breadcrumbs-container">';
		$html .= '<div class="breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">';
		
		// Add breadcrumb items.
		foreach ( $breadcrumbs as $position => $item ) {
			$html .= '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
			
			if ( ! empty( $item['url'] ) ) {
				$html .= '<a href="' . esc_url( $item['url'] ) . '" itemprop="item"><span itemprop="name">' . esc_html( $item['text'] ) . '</span></a>';
			} else {
				$html .= '<span itemprop="name">' . esc_html( $item['text'] ) . '</span>';
			}
			
			$html .= '<meta itemprop="position" content="' . esc_attr( $position + 1 ) . '" />';
			$html .= '</span>';
			
			// Add separator if not the last item.
			if ( $position < count( $breadcrumbs ) - 1 ) {
				$html .= '<span class="breadcrumb-separator">' . esc_html( $this->separator ) . '</span>';
			}
		}
		
		$html .= '</div>';
		$html .= '</div>';
		
		// Output breadcrumbs.
		echo $html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		
		// Fire action after breadcrumbs.
		do_action( 'aqualuxe_breadcrumbs_after' );
	}

	/**
	 * Get breadcrumbs.
	 *
	 * @return array
	 */
	public function get_breadcrumbs() {
		// Reset items.
		$this->items = array();
		
		// Skip on front page if disabled.
		if ( is_front_page() && ! $this->show_on_front ) {
			return $this->items;
		}
		
		// Add home link.
		if ( $this->show_home ) {
			$this->add_home_link();
		}
		
		// Add breadcrumb items based on page type.
		if ( is_home() ) {
			$this->add_blog_link();
		} elseif ( is_singular() ) {
			$this->add_singular_links();
		} elseif ( is_archive() ) {
			$this->add_archive_links();
		} elseif ( is_search() ) {
			$this->add_search_link();
		} elseif ( is_404() ) {
			$this->add_404_link();
		}
		
		return $this->items;
	}

	/**
	 * Add home link.
	 *
	 * @return void
	 */
	private function add_home_link() {
		$this->items[] = array(
			'text' => $this->home_text,
			'url'  => $this->home_url,
		);
	}

	/**
	 * Add blog link.
	 *
	 * @return void
	 */
	private function add_blog_link() {
		if ( $this->show_current ) {
			$blog_page_id = get_option( 'page_for_posts' );
			$title = $blog_page_id ? get_the_title( $blog_page_id ) : __( 'Blog', 'aqualuxe' );
			
			$this->items[] = array(
				'text' => $title,
				'url'  => '',
			);
		}
	}

	/**
	 * Add singular links.
	 *
	 * @return void
	 */
	private function add_singular_links() {
		$post_type = get_post_type();
		$post_id   = get_the_ID();
		
		if ( 'post' === $post_type ) {
			// Add blog page link.
			$blog_page_id = get_option( 'page_for_posts' );
			if ( $blog_page_id ) {
				$this->items[] = array(
					'text' => get_the_title( $blog_page_id ),
					'url'  => get_permalink( $blog_page_id ),
				);
			}
			
			// Add categories.
			$this->add_post_categories();
			
			// Add current post.
			if ( $this->show_current ) {
				$this->items[] = array(
					'text' => get_the_title(),
					'url'  => '',
				);
			}
		} elseif ( 'page' === $post_type ) {
			// Add ancestor pages.
			$ancestors = get_post_ancestors( $post_id );
			if ( $ancestors ) {
				$ancestors = array_reverse( $ancestors );
				
				foreach ( $ancestors as $ancestor_id ) {
					$this->items[] = array(
						'text' => get_the_title( $ancestor_id ),
						'url'  => get_permalink( $ancestor_id ),
					);
				}
			}
			
			// Add current page.
			if ( $this->show_current ) {
				$this->items[] = array(
					'text' => get_the_title(),
					'url'  => '',
				);
			}
		} elseif ( 'product' === $post_type && class_exists( 'WooCommerce' ) ) {
			// Add shop page.
			$shop_page_id = wc_get_page_id( 'shop' );
			if ( $shop_page_id > 0 ) {
				$this->items[] = array(
					'text' => get_the_title( $shop_page_id ),
					'url'  => get_permalink( $shop_page_id ),
				);
			}
			
			// Add product categories.
			$this->add_product_categories();
			
			// Add current product.
			if ( $this->show_current ) {
				$this->items[] = array(
					'text' => get_the_title(),
					'url'  => '',
				);
			}
		} else {
			// Add post type archive link.
			$post_type_obj = get_post_type_object( $post_type );
			if ( $post_type_obj && $post_type_obj->has_archive ) {
				$this->items[] = array(
					'text' => $post_type_obj->labels->name,
					'url'  => get_post_type_archive_link( $post_type ),
				);
			}
			
			// Add taxonomies.
			$this->add_post_taxonomies( $post_type );
			
			// Add current post.
			if ( $this->show_current ) {
				$this->items[] = array(
					'text' => get_the_title(),
					'url'  => '',
				);
			}
		}
	}

	/**
	 * Add archive links.
	 *
	 * @return void
	 */
	private function add_archive_links() {
		if ( is_category() ) {
			$this->add_category_links();
		} elseif ( is_tag() ) {
			$this->add_tag_links();
		} elseif ( is_tax() ) {
			$this->add_taxonomy_links();
		} elseif ( is_post_type_archive() ) {
			$this->add_post_type_archive_links();
		} elseif ( is_author() ) {
			$this->add_author_links();
		} elseif ( is_date() ) {
			$this->add_date_links();
		}
	}

	/**
	 * Add category links.
	 *
	 * @return void
	 */
	private function add_category_links() {
		// Add blog page link.
		$blog_page_id = get_option( 'page_for_posts' );
		if ( $blog_page_id ) {
			$this->items[] = array(
				'text' => get_the_title( $blog_page_id ),
				'url'  => get_permalink( $blog_page_id ),
			);
		}
		
		// Get current category.
		$category = get_queried_object();
		
		// Add parent categories.
		if ( $category->parent ) {
			$this->add_category_parents( $category->parent );
		}
		
		// Add current category.
		if ( $this->show_current ) {
			$this->items[] = array(
				'text' => single_cat_title( '', false ),
				'url'  => '',
			);
		}
	}

	/**
	 * Add tag links.
	 *
	 * @return void
	 */
	private function add_tag_links() {
		// Add blog page link.
		$blog_page_id = get_option( 'page_for_posts' );
		if ( $blog_page_id ) {
			$this->items[] = array(
				'text' => get_the_title( $blog_page_id ),
				'url'  => get_permalink( $blog_page_id ),
			);
		}
		
		// Add tags link.
		$this->items[] = array(
			'text' => __( 'Tags', 'aqualuxe' ),
			'url'  => get_post_type_archive_link( 'post' ),
		);
		
		// Add current tag.
		if ( $this->show_current ) {
			$this->items[] = array(
				'text' => single_tag_title( '', false ),
				'url'  => '',
			);
		}
	}

	/**
	 * Add taxonomy links.
	 *
	 * @return void
	 */
	private function add_taxonomy_links() {
		// Get current term.
		$term = get_queried_object();
		$taxonomy = $term->taxonomy;
		$tax_obj = get_taxonomy( $taxonomy );
		
		// Add post type archive link.
		if ( $tax_obj->object_type ) {
			$post_type = $tax_obj->object_type[0];
			$post_type_obj = get_post_type_object( $post_type );
			
			if ( $post_type_obj && $post_type_obj->has_archive ) {
				$this->items[] = array(
					'text' => $post_type_obj->labels->name,
					'url'  => get_post_type_archive_link( $post_type ),
				);
			}
		}
		
		// Add parent terms.
		if ( $term->parent ) {
			$this->add_term_parents( $term->parent, $taxonomy );
		}
		
		// Add current term.
		if ( $this->show_current ) {
			$this->items[] = array(
				'text' => single_term_title( '', false ),
				'url'  => '',
			);
		}
	}

	/**
	 * Add post type archive links.
	 *
	 * @return void
	 */
	private function add_post_type_archive_links() {
		// Get post type.
		$post_type = get_post_type();
		$post_type_obj = get_post_type_object( $post_type );
		
		// Add current archive.
		if ( $this->show_current && $post_type_obj ) {
			$this->items[] = array(
				'text' => $post_type_obj->labels->name,
				'url'  => '',
			);
		}
	}

	/**
	 * Add author links.
	 *
	 * @return void
	 */
	private function add_author_links() {
		// Add blog page link.
		$blog_page_id = get_option( 'page_for_posts' );
		if ( $blog_page_id ) {
			$this->items[] = array(
				'text' => get_the_title( $blog_page_id ),
				'url'  => get_permalink( $blog_page_id ),
			);
		}
		
		// Add authors link.
		$this->items[] = array(
			'text' => __( 'Authors', 'aqualuxe' ),
			'url'  => '',
		);
		
		// Add current author.
		if ( $this->show_current ) {
			$this->items[] = array(
				'text' => get_the_author_meta( 'display_name', get_queried_object_id() ),
				'url'  => '',
			);
		}
	}

	/**
	 * Add date links.
	 *
	 * @return void
	 */
	private function add_date_links() {
		// Add blog page link.
		$blog_page_id = get_option( 'page_for_posts' );
		if ( $blog_page_id ) {
			$this->items[] = array(
				'text' => get_the_title( $blog_page_id ),
				'url'  => get_permalink( $blog_page_id ),
			);
		}
		
		// Add archives link.
		$this->items[] = array(
			'text' => __( 'Archives', 'aqualuxe' ),
			'url'  => '',
		);
		
		// Add year link.
		if ( is_year() ) {
			if ( $this->show_current ) {
				$this->items[] = array(
					'text' => get_the_date( 'Y' ),
					'url'  => '',
				);
			}
		} else {
			$this->items[] = array(
				'text' => get_the_date( 'Y' ),
				'url'  => get_year_link( get_the_date( 'Y' ) ),
			);
		}
		
		// Add month link.
		if ( is_month() ) {
			if ( $this->show_current ) {
				$this->items[] = array(
					'text' => get_the_date( 'F' ),
					'url'  => '',
				);
			}
		} elseif ( is_day() ) {
			$this->items[] = array(
				'text' => get_the_date( 'F' ),
				'url'  => get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ),
			);
			
			// Add day link.
			if ( $this->show_current ) {
				$this->items[] = array(
					'text' => get_the_date( 'j' ),
					'url'  => '',
				);
			}
		}
	}

	/**
	 * Add search link.
	 *
	 * @return void
	 */
	private function add_search_link() {
		if ( $this->show_current ) {
			$this->items[] = array(
				'text' => sprintf( __( 'Search results for: %s', 'aqualuxe' ), get_search_query() ),
				'url'  => '',
			);
		}
	}

	/**
	 * Add 404 link.
	 *
	 * @return void
	 */
	private function add_404_link() {
		if ( $this->show_current ) {
			$this->items[] = array(
				'text' => __( 'Page not found', 'aqualuxe' ),
				'url'  => '',
			);
		}
	}

	/**
	 * Add post categories.
	 *
	 * @return void
	 */
	private function add_post_categories() {
		$categories = get_the_category();
		
		if ( $categories ) {
			// Get primary category.
			$category = $categories[0];
			
			// Add parent categories.
			if ( $category->parent ) {
				$this->add_category_parents( $category->parent );
			}
			
			// Add current category.
			$this->items[] = array(
				'text' => $category->name,
				'url'  => get_category_link( $category ),
			);
		}
	}

	/**
	 * Add product categories.
	 *
	 * @return void
	 */
	private function add_product_categories() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}
		
		$product_id = get_the_ID();
		$terms = wc_get_product_terms(
			$product_id,
			'product_cat',
			array(
				'orderby' => 'parent',
				'order'   => 'DESC',
			)
		);
		
		if ( $terms ) {
			// Get primary category.
			$term = $terms[0];
			
			// Add parent categories.
			if ( $term->parent ) {
				$this->add_term_parents( $term->parent, 'product_cat' );
			}
			
			// Add current category.
			$this->items[] = array(
				'text' => $term->name,
				'url'  => get_term_link( $term ),
			);
		}
	}

	/**
	 * Add post taxonomies.
	 *
	 * @param string $post_type Post type.
	 * @return void
	 */
	private function add_post_taxonomies( $post_type ) {
		$taxonomies = get_object_taxonomies( $post_type, 'objects' );
		
		if ( empty( $taxonomies ) ) {
			return;
		}
		
		// Find a hierarchical taxonomy.
		$taxonomy = null;
		foreach ( $taxonomies as $tax ) {
			if ( $tax->hierarchical ) {
				$taxonomy = $tax->name;
				break;
			}
		}
		
		if ( ! $taxonomy ) {
			return;
		}
		
		$terms = get_the_terms( get_the_ID(), $taxonomy );
		
		if ( $terms ) {
			// Get primary term.
			$term = $terms[0];
			
			// Add parent terms.
			if ( $term->parent ) {
				$this->add_term_parents( $term->parent, $taxonomy );
			}
			
			// Add current term.
			$this->items[] = array(
				'text' => $term->name,
				'url'  => get_term_link( $term ),
			);
		}
	}

	/**
	 * Add category parents.
	 *
	 * @param int $parent_id Parent category ID.
	 * @return void
	 */
	private function add_category_parents( $parent_id ) {
		$parents = array();
		
		while ( $parent_id ) {
			$parent = get_term( $parent_id, 'category' );
			
			if ( is_wp_error( $parent ) ) {
				break;
			}
			
			$parents[] = array(
				'id'   => $parent->term_id,
				'name' => $parent->name,
				'url'  => get_category_link( $parent->term_id ),
			);
			
			$parent_id = $parent->parent;
		}
		
		// Add parents in correct order.
		$parents = array_reverse( $parents );
		
		foreach ( $parents as $parent ) {
			$this->items[] = array(
				'text' => $parent['name'],
				'url'  => $parent['url'],
			);
		}
	}

	/**
	 * Add term parents.
	 *
	 * @param int    $parent_id Parent term ID.
	 * @param string $taxonomy  Taxonomy.
	 * @return void
	 */
	private function add_term_parents( $parent_id, $taxonomy ) {
		$parents = array();
		
		while ( $parent_id ) {
			$parent = get_term( $parent_id, $taxonomy );
			
			if ( is_wp_error( $parent ) ) {
				break;
			}
			
			$parents[] = array(
				'id'   => $parent->term_id,
				'name' => $parent->name,
				'url'  => get_term_link( $parent->term_id, $taxonomy ),
			);
			
			$parent_id = $parent->parent;
		}
		
		// Add parents in correct order.
		$parents = array_reverse( $parents );
		
		foreach ( $parents as $parent ) {
			$this->items[] = array(
				'text' => $parent['name'],
				'url'  => $parent['url'],
			);
		}
	}
}