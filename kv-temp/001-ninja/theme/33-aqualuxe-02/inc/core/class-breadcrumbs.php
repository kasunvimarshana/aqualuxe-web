<?php
/**
 * Breadcrumbs Class
 *
 * Handles the generation and display of breadcrumb navigation.
 *
 * @package AquaLuxe
 */

namespace AquaLuxe\Core;

use AquaLuxe\Core\Service;

/**
 * Breadcrumbs class.
 */
class Breadcrumbs extends Service {

	/**
	 * Breadcrumb separator
	 *
	 * @var string
	 */
	private $separator = '';

	/**
	 * Breadcrumb home icon
	 *
	 * @var string
	 */
	private $home_icon = '';

	/**
	 * Breadcrumb options
	 *
	 * @var array
	 */
	private $options = array();

	/**
	 * Register service features.
	 */
	public function register() {
		$this->setup_options();
		add_action( 'wp', array( $this, 'setup_breadcrumb_elements' ) );
	}

	/**
	 * Setup breadcrumb options.
	 */
	private function setup_options() {
		$this->options = array(
			'show_on_home'   => get_theme_mod( 'aqualuxe_breadcrumbs_show_on_home', false ),
			'show_home'      => get_theme_mod( 'aqualuxe_breadcrumbs_show_home', true ),
			'show_current'   => get_theme_mod( 'aqualuxe_breadcrumbs_show_current', true ),
			'show_on_pages'  => get_theme_mod( 'aqualuxe_breadcrumbs_show_on_pages', true ),
			'show_on_posts'  => get_theme_mod( 'aqualuxe_breadcrumbs_show_on_posts', true ),
			'show_on_archives' => get_theme_mod( 'aqualuxe_breadcrumbs_show_on_archives', true ),
			'home_text'      => get_theme_mod( 'aqualuxe_breadcrumbs_home_text', __( 'Home', 'aqualuxe' ) ),
			'blog_text'      => get_theme_mod( 'aqualuxe_breadcrumbs_blog_text', __( 'Blog', 'aqualuxe' ) ),
			'shop_text'      => get_theme_mod( 'aqualuxe_breadcrumbs_shop_text', __( 'Shop', 'aqualuxe' ) ),
			'error_text'     => get_theme_mod( 'aqualuxe_breadcrumbs_error_text', __( 'Error 404', 'aqualuxe' ) ),
			'search_text'    => get_theme_mod( 'aqualuxe_breadcrumbs_search_text', __( 'Search results for', 'aqualuxe' ) ),
		);
	}

	/**
	 * Setup breadcrumb elements.
	 */
	public function setup_breadcrumb_elements() {
		$separator_style = get_theme_mod( 'aqualuxe_breadcrumbs_separator', 'slash' );

		switch ( $separator_style ) {
			case 'arrow':
				$this->separator = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';
				break;
			case 'dot':
				$this->separator = '<span class="mx-2">&bull;</span>';
				break;
			case 'dash':
				$this->separator = '<span class="mx-2">&ndash;</span>';
				break;
			case 'slash':
			default:
				$this->separator = '<span class="mx-2">/</span>';
				break;
		}

		$home_icon_style = get_theme_mod( 'aqualuxe_breadcrumbs_home_icon', 'home' );

		switch ( $home_icon_style ) {
			case 'none':
				$this->home_icon = '';
				break;
			case 'home':
			default:
				$this->home_icon = '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>';
				break;
		}
	}

	/**
	 * Display breadcrumbs.
	 *
	 * @param array $args Optional. Arguments to customize breadcrumbs.
	 */
	public function display( $args = array() ) {
		// Parse arguments
		$args = wp_parse_args(
			$args,
			array(
				'container'       => 'nav',
				'container_class' => 'breadcrumbs py-3',
				'item_class'      => 'breadcrumb-item',
				'separator_class' => 'breadcrumb-separator',
				'current_class'   => 'breadcrumb-current',
				'home_class'      => 'breadcrumb-home',
				'show_on_home'    => $this->options['show_on_home'],
				'show_home'       => $this->options['show_home'],
				'show_current'    => $this->options['show_current'],
				'before'          => '',
				'after'           => '',
				'echo'            => true,
			)
		);

		// Check if breadcrumbs should be displayed
		if ( is_front_page() && ! $args['show_on_home'] ) {
			return;
		}

		if ( is_page() && ! $this->options['show_on_pages'] ) {
			return;
		}

		if ( is_single() && ! $this->options['show_on_posts'] ) {
			return;
		}

		if ( ( is_archive() || is_tax() ) && ! $this->options['show_on_archives'] ) {
			return;
		}

		// Start building breadcrumbs
		$breadcrumbs = array();

		// Add home link
		if ( $args['show_home'] ) {
			$breadcrumbs[] = array(
				'url'   => home_url( '/' ),
				'text'  => $this->options['home_text'],
				'class' => $args['home_class'],
				'icon'  => $this->home_icon,
			);
		}

		// Build breadcrumbs based on current page
		if ( is_home() ) {
			// Blog page
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => $this->options['blog_text'],
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_category() ) {
			// Category archive
			$category = get_queried_object();
			
			if ( $category->parent != 0 ) {
				$parent_categories = array();
				$parent_id = $category->parent;
				
				while ( $parent_id ) {
					$parent_category = get_term( $parent_id, 'category' );
					$parent_categories[] = array(
						'url'   => get_category_link( $parent_category->term_id ),
						'text'  => $parent_category->name,
						'class' => $args['item_class'],
						'icon'  => '',
					);
					$parent_id = $parent_category->parent;
				}
				
				$breadcrumbs = array_merge( $breadcrumbs, array_reverse( $parent_categories ) );
			}
			
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => single_cat_title( '', false ),
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_search() ) {
			// Search results
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => $this->options['search_text'] . ' "' . get_search_query() . '"',
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_day() ) {
			// Day archive
			$breadcrumbs[] = array(
				'url'   => get_year_link( get_the_time( 'Y' ) ),
				'text'  => get_the_time( 'Y' ),
				'class' => $args['item_class'],
				'icon'  => '',
			);
			
			$breadcrumbs[] = array(
				'url'   => get_month_link( get_the_time( 'Y' ), get_the_time( 'm' ) ),
				'text'  => get_the_time( 'F' ),
				'class' => $args['item_class'],
				'icon'  => '',
			);
			
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => get_the_time( 'd' ),
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_month() ) {
			// Month archive
			$breadcrumbs[] = array(
				'url'   => get_year_link( get_the_time( 'Y' ) ),
				'text'  => get_the_time( 'Y' ),
				'class' => $args['item_class'],
				'icon'  => '',
			);
			
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => get_the_time( 'F' ),
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_year() ) {
			// Year archive
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => get_the_time( 'Y' ),
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_single() && ! is_attachment() ) {
			// Single post
			if ( get_post_type() != 'post' ) {
				// Custom post type
				$post_type = get_post_type_object( get_post_type() );
				$archive_link = get_post_type_archive_link( $post_type->name );
				
				if ( $archive_link ) {
					$breadcrumbs[] = array(
						'url'   => $archive_link,
						'text'  => $post_type->labels->name,
						'class' => $args['item_class'],
						'icon'  => '',
					);
				}
				
				if ( $args['show_current'] ) {
					$breadcrumbs[] = array(
						'url'   => '',
						'text'  => get_the_title(),
						'class' => $args['current_class'],
						'icon'  => '',
					);
				}
			} else {
				// Standard post
				$categories = get_the_category();
				
				if ( ! empty( $categories ) ) {
					$category = $categories[0];
					
					if ( $category->parent != 0 ) {
						$parent_categories = array();
						$parent_id = $category->parent;
						
						while ( $parent_id ) {
							$parent_category = get_term( $parent_id, 'category' );
							$parent_categories[] = array(
								'url'   => get_category_link( $parent_category->term_id ),
								'text'  => $parent_category->name,
								'class' => $args['item_class'],
								'icon'  => '',
							);
							$parent_id = $parent_category->parent;
						}
						
						$breadcrumbs = array_merge( $breadcrumbs, array_reverse( $parent_categories ) );
					}
					
					$breadcrumbs[] = array(
						'url'   => get_category_link( $category->term_id ),
						'text'  => $category->name,
						'class' => $args['item_class'],
						'icon'  => '',
					);
				}
				
				if ( $args['show_current'] ) {
					$breadcrumbs[] = array(
						'url'   => '',
						'text'  => get_the_title(),
						'class' => $args['current_class'],
						'icon'  => '',
					);
				}
			}
		} elseif ( is_page() && ! is_front_page() ) {
			// Standard page
			if ( get_post()->post_parent ) {
				// Page with parent
				$parent_pages = array();
				$parent_id = get_post()->post_parent;
				
				while ( $parent_id ) {
					$parent_page = get_post( $parent_id );
					$parent_pages[] = array(
						'url'   => get_permalink( $parent_page->ID ),
						'text'  => get_the_title( $parent_page->ID ),
						'class' => $args['item_class'],
						'icon'  => '',
					);
					$parent_id = $parent_page->post_parent;
				}
				
				$breadcrumbs = array_merge( $breadcrumbs, array_reverse( $parent_pages ) );
			}
			
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => get_the_title(),
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_tag() ) {
			// Tag archive
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => single_tag_title( '', false ),
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_author() ) {
			// Author archive
			if ( $args['show_current'] ) {
				$author = get_queried_object();
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => $author->display_name,
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_404() ) {
			// 404 page
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => $this->options['error_text'],
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_post_type_archive() ) {
			// Post type archive
			$post_type = get_post_type_object( get_post_type() );
			
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => $post_type->labels->name,
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		} elseif ( is_tax() ) {
			// Taxonomy archive
			$taxonomy = get_taxonomy( get_queried_object()->taxonomy );
			$term = get_queried_object();
			
			if ( $term->parent != 0 ) {
				$parent_terms = array();
				$parent_id = $term->parent;
				
				while ( $parent_id ) {
					$parent_term = get_term( $parent_id, $term->taxonomy );
					$parent_terms[] = array(
						'url'   => get_term_link( $parent_term ),
						'text'  => $parent_term->name,
						'class' => $args['item_class'],
						'icon'  => '',
					);
					$parent_id = $parent_term->parent;
				}
				
				$breadcrumbs = array_merge( $breadcrumbs, array_reverse( $parent_terms ) );
			}
			
			if ( $args['show_current'] ) {
				$breadcrumbs[] = array(
					'url'   => '',
					'text'  => $term->name,
					'class' => $args['current_class'],
					'icon'  => '',
				);
			}
		}

		// WooCommerce specific breadcrumbs
		if ( class_exists( 'WooCommerce' ) ) {
			if ( is_shop() ) {
				if ( $args['show_current'] ) {
					$breadcrumbs[] = array(
						'url'   => '',
						'text'  => $this->options['shop_text'],
						'class' => $args['current_class'],
						'icon'  => '',
					);
				}
			}
		}

		// Build HTML output
		$output = '';
		
		if ( ! empty( $breadcrumbs ) ) {
			$output .= $args['before'];
			
			if ( ! empty( $args['container'] ) ) {
				$output .= '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
			}
			
			$output .= '<ol class="flex flex-wrap items-center text-sm">';
			
			$count = count( $breadcrumbs );
			$i = 1;
			
			foreach ( $breadcrumbs as $breadcrumb ) {
				$is_last = ( $i === $count );
				$item_class = $breadcrumb['class'];
				
				$output .= '<li class="' . esc_attr( $item_class ) . '">';
				
				if ( ! empty( $breadcrumb['url'] ) ) {
					$output .= '<a href="' . esc_url( $breadcrumb['url'] ) . '" class="text-gray-600 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200">';
					
					if ( ! empty( $breadcrumb['icon'] ) ) {
						$output .= $breadcrumb['icon'];
					}
					
					$output .= esc_html( $breadcrumb['text'] );
					$output .= '</a>';
				} else {
					$output .= '<span class="text-gray-900 dark:text-white font-medium">';
					
					if ( ! empty( $breadcrumb['icon'] ) ) {
						$output .= $breadcrumb['icon'];
					}
					
					$output .= esc_html( $breadcrumb['text'] );
					$output .= '</span>';
				}
				
				$output .= '</li>';
				
				if ( $i < $count ) {
					$output .= '<li class="' . esc_attr( $args['separator_class'] ) . ' text-gray-400 dark:text-gray-500">' . $this->separator . '</li>';
				}
				
				$i++;
			}
			
			$output .= '</ol>';
			
			if ( ! empty( $args['container'] ) ) {
				$output .= '</' . esc_attr( $args['container'] ) . '>';
			}
			
			$output .= $args['after'];
		}
		
		if ( $args['echo'] ) {
			echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $output;
		}
	}
}

/**
 * Helper function to display breadcrumbs.
 *
 * @param array $args Optional. Arguments to customize breadcrumbs.
 * @return void|string
 */
function aqualuxe_breadcrumbs( $args = array() ) {
	$breadcrumbs = aqualuxe()->get( 'breadcrumbs' );
	
	if ( $breadcrumbs ) {
		return $breadcrumbs->display( $args );
	}
	
	return '';
}