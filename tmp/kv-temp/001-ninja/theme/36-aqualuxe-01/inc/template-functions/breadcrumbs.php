<?php
/**
 * Breadcrumbs functionality
 *
 * @package AquaLuxe
 */

if ( ! function_exists( 'aqualuxe_breadcrumbs' ) ) {
	/**
	 * Display breadcrumbs for the current page
	 *
	 * @return void
	 */
	function aqualuxe_breadcrumbs() {
		// Check if breadcrumbs are enabled
		if ( ! get_theme_mod( 'aqualuxe_breadcrumbs', true ) ) {
			return;
		}

		// Get breadcrumb settings
		$separator = get_theme_mod( 'aqualuxe_breadcrumb_separator', '/' );
		$home_text = get_theme_mod( 'aqualuxe_breadcrumb_home_text', __( 'Home', 'aqualuxe' ) );
		$show_on_home = get_theme_mod( 'aqualuxe_breadcrumb_show_on_home', false );

		// Get the current page URL
		$current_url = esc_url( home_url( add_query_arg( array(), $GLOBALS['wp']->request ) ) );

		// Start the breadcrumb
		echo '<nav class="breadcrumbs py-3" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '" itemscope itemtype="http://schema.org/BreadcrumbList">';
		echo '<div class="container mx-auto px-4">';
		echo '<ol class="flex flex-wrap items-center text-sm">';

		// Home page
		echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
		echo '<a href="' . esc_url( home_url() ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
		echo '<span itemprop="name">' . esc_html( $home_text ) . '</span>';
		echo '</a>';
		echo '<meta itemprop="position" content="1" />';
		echo '</li>';

		// Don't display breadcrumbs on the home page if not enabled
		if ( is_home() || is_front_page() ) {
			if ( ! $show_on_home ) {
				echo '</ol>';
				echo '</div>';
				echo '</nav>';
				return;
			}
		} else {
			// Add separator after home
			echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
		}

		// Position counter
		$position = 2;

		// Category, tag, taxonomy archives
		if ( is_category() || is_tag() || is_tax() ) {
			$term = get_queried_object();
			$taxonomy = get_taxonomy( $term->taxonomy );
			
			// Add taxonomy name if enabled
			if ( get_theme_mod( 'aqualuxe_breadcrumb_show_taxonomy', true ) ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
				echo '<span itemprop="name" class="text-gray-600">' . esc_html( $taxonomy->labels->singular_name ) . '</span>';
				echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
				echo '</li>';
				echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
				$position++;
			}
			
			// Add parent terms if any
			if ( $term->parent ) {
				$parents = get_ancestors( $term->term_id, $term->taxonomy );
				$parents = array_reverse( $parents );
				foreach ( $parents as $parent_id ) {
					$parent = get_term( $parent_id, $term->taxonomy );
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
					echo '<a href="' . esc_url( get_term_link( $parent ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
					echo '<span itemprop="name">' . esc_html( $parent->name ) . '</span>';
					echo '</a>';
					echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
					echo '</li>';
					echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
					$position++;
				}
			}
			
			// Add current term
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
			echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( $term->name ) . '</span>';
			echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
			echo '</li>';
		}
		// Posts
		elseif ( is_singular( 'post' ) ) {
			// Add blog page if set
			$blog_page_id = get_option( 'page_for_posts' );
			if ( $blog_page_id ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
				echo '<a href="' . esc_url( get_permalink( $blog_page_id ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
				echo '<span itemprop="name">' . esc_html( get_the_title( $blog_page_id ) ) . '</span>';
				echo '</a>';
				echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
				echo '</li>';
				echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
				$position++;
			}
			
			// Add categories if enabled
			if ( get_theme_mod( 'aqualuxe_breadcrumb_show_categories', true ) ) {
				$categories = get_the_category();
				if ( $categories ) {
					$category = $categories[0];
					
					// Add parent categories if any
					if ( $category->parent ) {
						$parents = get_ancestors( $category->term_id, 'category' );
						$parents = array_reverse( $parents );
						foreach ( $parents as $parent_id ) {
							$parent = get_term( $parent_id, 'category' );
							echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
							echo '<a href="' . esc_url( get_term_link( $parent ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
							echo '<span itemprop="name">' . esc_html( $parent->name ) . '</span>';
							echo '</a>';
							echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
							echo '</li>';
							echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
							$position++;
						}
					}
					
					// Add current category
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
					echo '<a href="' . esc_url( get_term_link( $category ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
					echo '<span itemprop="name">' . esc_html( $category->name ) . '</span>';
					echo '</a>';
					echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
					echo '</li>';
					echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
					$position++;
				}
			}
			
			// Add current post
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
			echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( get_the_title() ) . '</span>';
			echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
			echo '</li>';
		}
		// Pages
		elseif ( is_page() ) {
			// Add parent pages if any
			if ( wp_get_post_parent_id( get_the_ID() ) ) {
				$parents = get_post_ancestors( get_the_ID() );
				$parents = array_reverse( $parents );
				foreach ( $parents as $parent_id ) {
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
					echo '<a href="' . esc_url( get_permalink( $parent_id ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
					echo '<span itemprop="name">' . esc_html( get_the_title( $parent_id ) ) . '</span>';
					echo '</a>';
					echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
					echo '</li>';
					echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
					$position++;
				}
			}
			
			// Add current page
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
			echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( get_the_title() ) . '</span>';
			echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
			echo '</li>';
		}
		// Search results
		elseif ( is_search() ) {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
			echo '<span itemprop="name" class="text-primary font-medium">' . esc_html__( 'Search results for: ', 'aqualuxe' ) . '"' . esc_html( get_search_query() ) . '"</span>';
			echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
			echo '</li>';
		}
		// 404 page
		elseif ( is_404() ) {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
			echo '<span itemprop="name" class="text-primary font-medium">' . esc_html__( 'Page not found', 'aqualuxe' ) . '</span>';
			echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
			echo '</li>';
		}
		// Author archives
		elseif ( is_author() ) {
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
			echo '<span itemprop="name" class="text-primary font-medium">' . esc_html__( 'Author: ', 'aqualuxe' ) . esc_html( get_the_author() ) . '</span>';
			echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
			echo '</li>';
		}
		// Date archives
		elseif ( is_date() ) {
			// Add year
			if ( is_year() ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
				echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( get_the_date( 'Y' ) ) . '</span>';
				echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
				echo '</li>';
			}
			// Add month and year
			elseif ( is_month() ) {
				$year = get_the_date( 'Y' );
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
				echo '<a href="' . esc_url( get_year_link( $year ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
				echo '<span itemprop="name">' . esc_html( $year ) . '</span>';
				echo '</a>';
				echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
				echo '</li>';
				echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
				$position++;
				
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
				echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( get_the_date( 'F' ) ) . '</span>';
				echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
				echo '</li>';
			}
			// Add day, month, and year
			elseif ( is_day() ) {
				$year = get_the_date( 'Y' );
				$month = get_the_date( 'm' );
				$month_name = get_the_date( 'F' );
				
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
				echo '<a href="' . esc_url( get_year_link( $year ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
				echo '<span itemprop="name">' . esc_html( $year ) . '</span>';
				echo '</a>';
				echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
				echo '</li>';
				echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
				$position++;
				
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
				echo '<a href="' . esc_url( get_month_link( $year, $month ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
				echo '<span itemprop="name">' . esc_html( $month_name ) . '</span>';
				echo '</a>';
				echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
				echo '</li>';
				echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
				$position++;
				
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
				echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( get_the_date( 'j' ) ) . '</span>';
				echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
				echo '</li>';
			}
		}
		// Post type archives
		elseif ( is_post_type_archive() ) {
			$post_type = get_post_type_object( get_post_type() );
			echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
			echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( $post_type->labels->name ) . '</span>';
			echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
			echo '</li>';
		}
		// Blog page
		elseif ( is_home() ) {
			$blog_page_id = get_option( 'page_for_posts' );
			if ( $blog_page_id ) {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
				echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( get_the_title( $blog_page_id ) ) . '</span>';
				echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
				echo '</li>';
			} else {
				echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
				echo '<span itemprop="name" class="text-primary font-medium">' . esc_html__( 'Blog', 'aqualuxe' ) . '</span>';
				echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
				echo '</li>';
			}
		}

		// WooCommerce support
		if ( function_exists( 'aqualuxe_is_woocommerce_active' ) && aqualuxe_is_woocommerce_active() ) {
			if ( function_exists( 'is_woocommerce' ) && is_woocommerce() ) {
				// Remove default WooCommerce breadcrumbs
				remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
				
				// Shop page
				if ( is_shop() ) {
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
					echo '<span itemprop="name" class="text-primary font-medium">' . esc_html__( 'Shop', 'aqualuxe' ) . '</span>';
					echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
					echo '</li>';
				}
				// Product category
				elseif ( is_product_category() ) {
					$current_term = get_queried_object();
					
					// Add shop page if enabled
					if ( get_theme_mod( 'aqualuxe_breadcrumb_show_shop', true ) ) {
						echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
						echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
						echo '<span itemprop="name">' . esc_html__( 'Shop', 'aqualuxe' ) . '</span>';
						echo '</a>';
						echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
						echo '</li>';
						echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
						$position++;
					}
					
					// Add parent categories if any
					if ( $current_term->parent ) {
						$parents = get_ancestors( $current_term->term_id, 'product_cat' );
						$parents = array_reverse( $parents );
						foreach ( $parents as $parent_id ) {
							$parent = get_term( $parent_id, 'product_cat' );
							echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
							echo '<a href="' . esc_url( get_term_link( $parent ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
							echo '<span itemprop="name">' . esc_html( $parent->name ) . '</span>';
							echo '</a>';
							echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
							echo '</li>';
							echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
							$position++;
						}
					}
					
					// Add current category
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
					echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( $current_term->name ) . '</span>';
					echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
					echo '</li>';
				}
				// Product tag
				elseif ( is_product_tag() ) {
					$current_term = get_queried_object();
					
					// Add shop page if enabled
					if ( get_theme_mod( 'aqualuxe_breadcrumb_show_shop', true ) ) {
						echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
						echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
						echo '<span itemprop="name">' . esc_html__( 'Shop', 'aqualuxe' ) . '</span>';
						echo '</a>';
						echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
						echo '</li>';
						echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
						$position++;
					}
					
					// Add current tag
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
					echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( $current_term->name ) . '</span>';
					echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
					echo '</li>';
				}
				// Single product
				elseif ( is_product() ) {
					// Add shop page if enabled
					if ( get_theme_mod( 'aqualuxe_breadcrumb_show_shop', true ) ) {
						echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
						echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
						echo '<span itemprop="name">' . esc_html__( 'Shop', 'aqualuxe' ) . '</span>';
						echo '</a>';
						echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
						echo '</li>';
						echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
						$position++;
					}
					
					// Add product categories if enabled
					if ( get_theme_mod( 'aqualuxe_breadcrumb_show_product_categories', true ) ) {
						$terms = wc_get_product_terms( get_the_ID(), 'product_cat', array( 'orderby' => 'parent', 'order' => 'DESC' ) );
						if ( $terms ) {
							$main_term = $terms[0];
							
							// Add parent categories if any
							if ( $main_term->parent ) {
								$parents = get_ancestors( $main_term->term_id, 'product_cat' );
								$parents = array_reverse( $parents );
								foreach ( $parents as $parent_id ) {
									$parent = get_term( $parent_id, 'product_cat' );
									echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
									echo '<a href="' . esc_url( get_term_link( $parent ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
									echo '<span itemprop="name">' . esc_html( $parent->name ) . '</span>';
									echo '</a>';
									echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
									echo '</li>';
									echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
									$position++;
								}
							}
							
							// Add current category
							echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
							echo '<a href="' . esc_url( get_term_link( $main_term ) ) . '" itemprop="item" class="text-gray-600 hover:text-primary transition-colors">';
							echo '<span itemprop="name">' . esc_html( $main_term->name ) . '</span>';
							echo '</a>';
							echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
							echo '</li>';
							echo '<li class="breadcrumb-separator mx-2 text-gray-400">' . esc_html( $separator ) . '</li>';
							$position++;
						}
					}
					
					// Add current product
					echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem" class="breadcrumb-item">';
					echo '<span itemprop="name" class="text-primary font-medium">' . esc_html( get_the_title() ) . '</span>';
					echo '<meta itemprop="position" content="' . esc_attr( $position ) . '" />';
					echo '</li>';
				}
			}
		}

		echo '</ol>';
		echo '</div>';
		echo '</nav>';
	}
}