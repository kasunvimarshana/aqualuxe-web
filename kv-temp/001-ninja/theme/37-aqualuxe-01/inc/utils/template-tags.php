<?php
/**
 * Template tags and utility functions for AquaLuxe theme
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display breadcrumbs
 *
 * @param array $args Breadcrumb arguments.
 */
function aqualuxe_breadcrumbs( $args = array() ) {
	$defaults = array(
		'container'     => 'nav',
		'container_id'  => 'breadcrumbs',
		'container_class' => 'breadcrumbs',
		'item_class'    => 'breadcrumb-item',
		'separator'     => '<span class="breadcrumb-separator">/</span>',
		'home_label'    => esc_html__( 'Home', 'aqualuxe' ),
		'show_on_home'  => false,
		'show_on_404'   => true,
		'schema'        => true,
	);

	$args = wp_parse_args( $args, $defaults );

	// Return early if on front page and show_on_home is false.
	if ( is_front_page() && ! $args['show_on_home'] ) {
		return;
	}

	// Return early if on 404 page and show_on_404 is false.
	if ( is_404() && ! $args['show_on_404'] ) {
		return;
	}

	// Get breadcrumbs.
	$breadcrumbs = aqualuxe_get_breadcrumbs();

	if ( empty( $breadcrumbs ) ) {
		return;
	}

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' id="' . esc_attr( $args['container_id'] ) . '" class="' . esc_attr( $args['container_class'] ) . '"';
	
	// Add schema markup.
	if ( $args['schema'] ) {
		$output .= ' aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '" itemscope itemtype="https://schema.org/BreadcrumbList"';
	}
	
	$output .= '>';

	// Add breadcrumbs.
	foreach ( $breadcrumbs as $index => $breadcrumb ) {
		$is_last = ( $index === count( $breadcrumbs ) - 1 );
		
		$output .= '<span class="' . esc_attr( $args['item_class'] ) . ( $is_last ? ' active' : '' ) . '"';
		
		// Add schema markup.
		if ( $args['schema'] ) {
			$output .= ' itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem"';
		}
		
		$output .= '>';
		
		if ( ! empty( $breadcrumb['url'] ) && ! $is_last ) {
			$output .= '<a href="' . esc_url( $breadcrumb['url'] ) . '"';
			
			// Add schema markup.
			if ( $args['schema'] ) {
				$output .= ' itemprop="item"';
			}
			
			$output .= '>';
			
			// Add schema markup.
			if ( $args['schema'] ) {
				$output .= '<span itemprop="name">';
			}
			
			$output .= esc_html( $breadcrumb['label'] );
			
			// Add schema markup.
			if ( $args['schema'] ) {
				$output .= '</span>';
			}
			
			$output .= '</a>';
		} else {
			// Add schema markup.
			if ( $args['schema'] ) {
				$output .= '<span itemprop="name">';
			}
			
			$output .= esc_html( $breadcrumb['label'] );
			
			// Add schema markup.
			if ( $args['schema'] ) {
				$output .= '</span>';
			}
		}
		
		// Add schema markup.
		if ( $args['schema'] ) {
			$output .= '<meta itemprop="position" content="' . esc_attr( $index + 1 ) . '" />';
		}
		
		$output .= '</span>';
		
		// Add separator.
		if ( ! $is_last ) {
			$output .= $args['separator'];
		}
	}

	// Close container.
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get breadcrumbs
 *
 * @return array Breadcrumbs.
 */
function aqualuxe_get_breadcrumbs() {
	$breadcrumbs = array(
		array(
			'label' => esc_html__( 'Home', 'aqualuxe' ),
			'url'   => home_url( '/' ),
		),
	);

	if ( is_home() && ! is_front_page() ) {
		// Blog page.
		$breadcrumbs[] = array(
			'label' => get_the_title( get_option( 'page_for_posts' ) ),
			'url'   => '',
		);
	} elseif ( is_category() ) {
		// Category archive.
		$breadcrumbs[] = array(
			'label' => single_cat_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_tag() ) {
		// Tag archive.
		$breadcrumbs[] = array(
			'label' => single_tag_title( '', false ),
			'url'   => '',
		);
	} elseif ( is_author() ) {
		// Author archive.
		$breadcrumbs[] = array(
			'label' => get_the_author(),
			'url'   => '',
		);
	} elseif ( is_year() ) {
		// Year archive.
		$breadcrumbs[] = array(
			'label' => get_the_date( 'Y' ),
			'url'   => '',
		);
	} elseif ( is_month() ) {
		// Month archive.
		$breadcrumbs[] = array(
			'label' => get_the_date( 'F Y' ),
			'url'   => '',
		);
	} elseif ( is_day() ) {
		// Day archive.
		$breadcrumbs[] = array(
			'label' => get_the_date(),
			'url'   => '',
		);
	} elseif ( is_tax() ) {
		// Term archive.
		$term = get_queried_object();
		
		if ( $term && ! is_wp_error( $term ) ) {
			$breadcrumbs[] = array(
				'label' => $term->name,
				'url'   => '',
			);
		}
	} elseif ( is_post_type_archive() ) {
		// Post type archive.
		$post_type = get_post_type_object( get_post_type() );
		
		if ( $post_type ) {
			$breadcrumbs[] = array(
				'label' => $post_type->labels->name,
				'url'   => '',
			);
		}
	} elseif ( is_singular() ) {
		// Single post/page/CPT.
		$post_type = get_post_type();
		
		if ( 'post' === $post_type ) {
			// Add blog page if available.
			$blog_page_id = get_option( 'page_for_posts' );
			
			if ( $blog_page_id ) {
				$breadcrumbs[] = array(
					'label' => get_the_title( $blog_page_id ),
					'url'   => get_permalink( $blog_page_id ),
				);
			}
			
			// Add categories.
			$categories = get_the_category();
			
			if ( ! empty( $categories ) ) {
				$category = $categories[0];
				
				$breadcrumbs[] = array(
					'label' => $category->name,
					'url'   => get_category_link( $category->term_id ),
				);
			}
		} elseif ( 'product' === $post_type && class_exists( 'WooCommerce' ) ) {
			// Add shop page.
			$shop_page_id = wc_get_page_id( 'shop' );
			
			if ( $shop_page_id > 0 ) {
				$breadcrumbs[] = array(
					'label' => get_the_title( $shop_page_id ),
					'url'   => get_permalink( $shop_page_id ),
				);
			}
			
			// Add product categories.
			$terms = wc_get_product_terms(
				get_the_ID(),
				'product_cat',
				array(
					'orderby' => 'parent',
					'order'   => 'DESC',
				)
			);
			
			if ( ! empty( $terms ) ) {
				$term = $terms[0];
				
				$breadcrumbs[] = array(
					'label' => $term->name,
					'url'   => get_term_link( $term ),
				);
			}
		} elseif ( $post_type !== 'page' ) {
			// Add CPT archive.
			$post_type_obj = get_post_type_object( $post_type );
			
			if ( $post_type_obj ) {
				$breadcrumbs[] = array(
					'label' => $post_type_obj->labels->name,
					'url'   => get_post_type_archive_link( $post_type ),
				);
			}
		}
		
		// Add ancestors for hierarchical post types.
		if ( is_post_type_hierarchical( $post_type ) ) {
			$ancestors = get_post_ancestors( get_the_ID() );
			
			if ( ! empty( $ancestors ) ) {
				$ancestors = array_reverse( $ancestors );
				
				foreach ( $ancestors as $ancestor ) {
					$breadcrumbs[] = array(
						'label' => get_the_title( $ancestor ),
						'url'   => get_permalink( $ancestor ),
					);
				}
			}
		}
		
		// Add current post/page.
		$breadcrumbs[] = array(
			'label' => get_the_title(),
			'url'   => '',
		);
	} elseif ( is_search() ) {
		// Search results.
		$breadcrumbs[] = array(
			'label' => sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), get_search_query() ),
			'url'   => '',
		);
	} elseif ( is_404() ) {
		// 404 page.
		$breadcrumbs[] = array(
			'label' => esc_html__( '404 Error', 'aqualuxe' ),
			'url'   => '',
		);
	}

	return apply_filters( 'aqualuxe_breadcrumbs', $breadcrumbs );
}

/**
 * Display pagination
 *
 * @param array $args Pagination arguments.
 */
function aqualuxe_pagination( $args = array() ) {
	$defaults = array(
		'container'      => 'nav',
		'container_id'   => 'pagination',
		'container_class' => 'pagination-container',
		'ul_class'       => 'pagination',
		'li_class'       => 'page-item',
		'link_class'     => 'page-link',
		'prev_text'      => esc_html__( 'Previous', 'aqualuxe' ),
		'next_text'      => esc_html__( 'Next', 'aqualuxe' ),
		'mid_size'       => 2,
		'end_size'       => 1,
		'type'           => 'array',
		'prev_next'      => true,
		'screen_reader_text' => esc_html__( 'Page', 'aqualuxe' ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Get pagination links.
	$links = paginate_links( $args );

	if ( empty( $links ) ) {
		return;
	}

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' id="' . esc_attr( $args['container_id'] ) . '" class="' . esc_attr( $args['container_class'] ) . '" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
	$output .= '<ul class="' . esc_attr( $args['ul_class'] ) . '">';

	// Add pagination links.
	foreach ( $links as $link ) {
		$output .= '<li class="' . esc_attr( $args['li_class'] ) . ( strpos( $link, 'current' ) !== false ? ' active' : '' ) . '">';
		$output .= str_replace( 'page-numbers', $args['link_class'], $link );
		$output .= '</li>';
	}

	// Close container.
	$output .= '</ul>';
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display post thumbnail with responsive image support
 *
 * @param string $size Image size.
 * @param array  $attr Image attributes.
 */
function aqualuxe_post_thumbnail( $size = 'post-thumbnail', $attr = array() ) {
	if ( ! has_post_thumbnail() ) {
		return;
	}

	$default_attr = array(
		'class' => 'post-thumbnail',
		'loading' => 'lazy',
	);

	$attr = wp_parse_args( $attr, $default_attr );

	the_post_thumbnail( $size, $attr );
}

/**
 * Display responsive image
 *
 * @param int    $attachment_id Attachment ID.
 * @param string $size Image size.
 * @param array  $attr Image attributes.
 * @return string
 */
function aqualuxe_responsive_image( $attachment_id, $size = 'full', $attr = array() ) {
	if ( empty( $attachment_id ) ) {
		return '';
	}

	$default_attr = array(
		'class' => 'responsive-image',
		'loading' => 'lazy',
	);

	$attr = wp_parse_args( $attr, $default_attr );

	return wp_get_attachment_image( $attachment_id, $size, false, $attr );
}

/**
 * Display post meta
 *
 * @param array $args Post meta arguments.
 */
function aqualuxe_post_meta( $args = array() ) {
	$defaults = array(
		'container'      => 'div',
		'container_class' => 'post-meta',
		'date'           => true,
		'author'         => true,
		'categories'     => true,
		'tags'           => true,
		'comments'       => true,
		'date_format'    => get_option( 'date_format' ),
		'date_prefix'    => esc_html__( 'Posted on', 'aqualuxe' ),
		'author_prefix'  => esc_html__( 'by', 'aqualuxe' ),
		'categories_prefix' => esc_html__( 'Categories:', 'aqualuxe' ),
		'tags_prefix'    => esc_html__( 'Tags:', 'aqualuxe' ),
		'separator'      => '<span class="post-meta-separator">|</span>',
	);

	$args = wp_parse_args( $args, $defaults );

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';

	$meta_items = array();

	// Date.
	if ( $args['date'] ) {
		$meta_items[] = '<span class="post-date">' . esc_html( $args['date_prefix'] ) . ' <time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date( $args['date_format'] ) ) . '</time></span>';
	}

	// Author.
	if ( $args['author'] ) {
		$meta_items[] = '<span class="post-author">' . esc_html( $args['author_prefix'] ) . ' <a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>';
	}

	// Categories.
	if ( $args['categories'] && has_category() ) {
		$meta_items[] = '<span class="post-categories">' . esc_html( $args['categories_prefix'] ) . ' ' . get_the_category_list( ', ' ) . '</span>';
	}

	// Tags.
	if ( $args['tags'] && has_tag() ) {
		$meta_items[] = '<span class="post-tags">' . esc_html( $args['tags_prefix'] ) . ' ' . get_the_tag_list( '', ', ' ) . '</span>';
	}

	// Comments.
	if ( $args['comments'] && comments_open() ) {
		$meta_items[] = '<span class="post-comments"><a href="' . esc_url( get_comments_link() ) . '">' . esc_html( get_comments_number_text() ) . '</a></span>';
	}

	// Add meta items.
	$output .= implode( $args['separator'], $meta_items );

	// Close container.
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display post excerpt with custom length
 *
 * @param int   $length Excerpt length.
 * @param array $args Excerpt arguments.
 */
function aqualuxe_excerpt( $length = 55, $args = array() ) {
	$defaults = array(
		'container'      => 'div',
		'container_class' => 'post-excerpt',
		'more'           => true,
		'more_text'      => esc_html__( 'Read More', 'aqualuxe' ),
		'more_class'     => 'read-more',
	);

	$args = wp_parse_args( $args, $defaults );

	// Get excerpt.
	$excerpt = get_the_excerpt();
	
	if ( empty( $excerpt ) ) {
		$excerpt = get_the_content();
		$excerpt = strip_shortcodes( $excerpt );
		$excerpt = excerpt_remove_blocks( $excerpt );
		$excerpt = wp_strip_all_tags( $excerpt );
		$excerpt = wp_trim_words( $excerpt, $length, '...' );
	}

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';

	// Add excerpt.
	$output .= $excerpt;

	// Add read more link.
	if ( $args['more'] ) {
		$output .= ' <a href="' . esc_url( get_permalink() ) . '" class="' . esc_attr( $args['more_class'] ) . '">' . esc_html( $args['more_text'] ) . '</a>';
	}

	// Close container.
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display page title
 *
 * @param array $args Page title arguments.
 */
function aqualuxe_page_title( $args = array() ) {
	$defaults = array(
		'container'      => 'div',
		'container_class' => 'page-title-container',
		'title_class'    => 'page-title',
		'description_class' => 'page-description',
		'show_description' => true,
	);

	$args = wp_parse_args( $args, $defaults );

	// Get title.
	$title = '';
	$description = '';

	if ( is_home() ) {
		$title = get_the_title( get_option( 'page_for_posts' ) );
	} elseif ( is_archive() ) {
		$title = get_the_archive_title();
		$description = get_the_archive_description();
	} elseif ( is_search() ) {
		$title = sprintf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), get_search_query() );
	} elseif ( is_404() ) {
		$title = esc_html__( 'Nothing Found', 'aqualuxe' );
	} else {
		$title = get_the_title();
	}

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';

	// Add title.
	$output .= '<h1 class="' . esc_attr( $args['title_class'] ) . '">' . $title . '</h1>';

	// Add description.
	if ( $args['show_description'] && ! empty( $description ) ) {
		$output .= '<div class="' . esc_attr( $args['description_class'] ) . '">' . $description . '</div>';
	}

	// Close container.
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get archive title
 *
 * @return string
 */
function aqualuxe_get_archive_title() {
	if ( is_category() ) {
		return single_cat_title( '', false );
	} elseif ( is_tag() ) {
		return single_tag_title( '', false );
	} elseif ( is_author() ) {
		return sprintf( esc_html__( 'Author Archives: %s', 'aqualuxe' ), get_the_author() );
	} elseif ( is_year() ) {
		return get_the_date( 'Y' );
	} elseif ( is_month() ) {
		return get_the_date( 'F Y' );
	} elseif ( is_day() ) {
		return get_the_date();
	} elseif ( is_tax( 'post_format' ) ) {
		if ( is_tax( 'post_format', 'post-format-aside' ) ) {
			return esc_html__( 'Asides', 'aqualuxe' );
		} elseif ( is_tax( 'post_format', 'post-format-gallery' ) ) {
			return esc_html__( 'Galleries', 'aqualuxe' );
		} elseif ( is_tax( 'post_format', 'post-format-image' ) ) {
			return esc_html__( 'Images', 'aqualuxe' );
		} elseif ( is_tax( 'post_format', 'post-format-video' ) ) {
			return esc_html__( 'Videos', 'aqualuxe' );
		} elseif ( is_tax( 'post_format', 'post-format-quote' ) ) {
			return esc_html__( 'Quotes', 'aqualuxe' );
		} elseif ( is_tax( 'post_format', 'post-format-link' ) ) {
			return esc_html__( 'Links', 'aqualuxe' );
		} elseif ( is_tax( 'post_format', 'post-format-status' ) ) {
			return esc_html__( 'Statuses', 'aqualuxe' );
		} elseif ( is_tax( 'post_format', 'post-format-audio' ) ) {
			return esc_html__( 'Audios', 'aqualuxe' );
		} elseif ( is_tax( 'post_format', 'post-format-chat' ) ) {
			return esc_html__( 'Chats', 'aqualuxe' );
		}
	} elseif ( is_post_type_archive() ) {
		return post_type_archive_title( '', false );
	} elseif ( is_tax() ) {
		return single_term_title( '', false );
	} elseif ( is_home() && is_front_page() ) {
		return esc_html__( 'Archives', 'aqualuxe' );
	} elseif ( is_home() ) {
		return get_the_title( get_option( 'page_for_posts', true ) );
	} elseif ( class_exists( 'WooCommerce' ) && is_shop() ) {
		return esc_html__( 'Shop', 'aqualuxe' );
	} elseif ( class_exists( 'WooCommerce' ) && is_product() ) {
		return sprintf( esc_html__( 'Product: %s', 'aqualuxe' ), get_the_title() );
	} elseif ( is_search() ) {
		return sprintf( esc_html__( 'Search results for: %s', 'aqualuxe' ), get_search_query() );
	} elseif ( is_404() ) {
		return esc_html__( '404 Error', 'aqualuxe' );
	}

	return esc_html__( 'Archives', 'aqualuxe' );
}

/**
 * Display social icons
 *
 * @param array $args Social icons arguments.
 */
function aqualuxe_social_icons( $args = array() ) {
	$defaults = array(
		'container'      => 'div',
		'container_class' => 'social-icons',
		'ul_class'       => 'social-icons-list',
		'li_class'       => 'social-icons-item',
		'link_class'     => 'social-icons-link',
		'icon_class'     => 'social-icons-icon',
		'show_labels'    => false,
		'target'         => '_blank',
		'rel'            => 'noopener noreferrer',
	);

	$args = wp_parse_args( $args, $defaults );

	// Get social profiles.
	$social_profiles = array(
		'facebook'  => array(
			'url'   => get_theme_mod( 'social_facebook' ),
			'label' => esc_html__( 'Facebook', 'aqualuxe' ),
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>',
		),
		'twitter'   => array(
			'url'   => get_theme_mod( 'social_twitter' ),
			'label' => esc_html__( 'Twitter', 'aqualuxe' ),
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg>',
		),
		'instagram' => array(
			'url'   => get_theme_mod( 'social_instagram' ),
			'label' => esc_html__( 'Instagram', 'aqualuxe' ),
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0-2a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm6.5-.25a1.25 1.25 0 0 1-2.5 0 1.25 1.25 0 0 1 2.5 0zM12 4c-2.474 0-2.878.007-4.029.058-.784.037-1.31.142-1.798.332-.434.168-.747.369-1.08.703a2.89 2.89 0 0 0-.704 1.08c-.19.49-.295 1.015-.331 1.798C4.006 9.075 4 9.461 4 12c0 2.474.007 2.878.058 4.029.037.783.142 1.31.331 1.797.17.435.37.748.702 1.08.337.336.65.537 1.08.703.494.191 1.02.297 1.8.333C9.075 19.994 9.461 20 12 20c2.474 0 2.878-.007 4.029-.058.782-.037 1.309-.142 1.797-.331.433-.169.748-.37 1.08-.702.337-.337.538-.65.704-1.08.19-.493.296-1.02.332-1.8.052-1.104.058-1.49.058-4.029 0-2.474-.007-2.878-.058-4.029-.037-.782-.142-1.31-.332-1.798a2.911 2.911 0 0 0-.703-1.08 2.884 2.884 0 0 0-1.08-.704c-.49-.19-1.016-.295-1.798-.331C14.925 4.006 14.539 4 12 4zm0-2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 0 1 1.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772 4.915 4.915 0 0 1-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2z"/></svg>',
		),
		'linkedin'  => array(
			'url'   => get_theme_mod( 'social_linkedin' ),
			'label' => esc_html__( 'LinkedIn', 'aqualuxe' ),
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M6.94 5a2 2 0 1 1-4-.002 2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z"/></svg>',
		),
		'youtube'   => array(
			'url'   => get_theme_mod( 'social_youtube' ),
			'label' => esc_html__( 'YouTube', 'aqualuxe' ),
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15.5l6-3.5-6-3.5v7z"/></svg>',
		),
		'pinterest' => array(
			'url'   => get_theme_mod( 'social_pinterest' ),
			'label' => esc_html__( 'Pinterest', 'aqualuxe' ),
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.37 2.094A10.003 10.003 0 0 0 8.002 21.17a7.757 7.757 0 0 1 .163-2.293c.185-.839 1.296-5.463 1.296-5.463a3.739 3.739 0 0 1-.324-1.577c0-1.485.857-2.593 1.923-2.593a1.334 1.334 0 0 1 1.342 1.508c0 .9-.578 2.262-.88 3.54a1.544 1.544 0 0 0 1.575 1.923c1.898 0 3.17-2.431 3.17-5.301 0-2.2-1.457-3.848-4.143-3.848a4.746 4.746 0 0 0-4.93 4.794 2.96 2.96 0 0 0 .648 1.97.48.48 0 0 1 .162.554c-.46.184-.162.623-.208.784a.354.354 0 0 1-.51.254c-1.384-.554-2.036-2.077-2.036-3.816 0-2.847 2.384-6.255 7.154-6.255 3.796 0 6.32 2.777 6.32 5.747 0 3.909-2.177 6.848-5.394 6.848a2.861 2.861 0 0 1-2.454-1.246s-.578 2.316-.692 2.754a8.026 8.026 0 0 1-1.019 2.131c.923.28 1.882.42 2.846.416a9.988 9.988 0 0 0 9.996-10.003 10.002 10.002 0 0 0-8.635-9.903z"/></svg>',
		),
	);

	// Filter out empty profiles.
	$social_profiles = array_filter( $social_profiles, function( $profile ) {
		return ! empty( $profile['url'] );
	} );

	if ( empty( $social_profiles ) ) {
		return;
	}

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
	$output .= '<ul class="' . esc_attr( $args['ul_class'] ) . '">';

	// Add social profiles.
	foreach ( $social_profiles as $platform => $profile ) {
		$output .= '<li class="' . esc_attr( $args['li_class'] ) . ' ' . esc_attr( $args['li_class'] . '-' . $platform ) . '">';
		$output .= '<a href="' . esc_url( $profile['url'] ) . '" class="' . esc_attr( $args['link_class'] ) . ' ' . esc_attr( $args['link_class'] . '-' . $platform ) . '" target="' . esc_attr( $args['target'] ) . '" rel="' . esc_attr( $args['rel'] ) . '">';
		$output .= '<span class="' . esc_attr( $args['icon_class'] ) . ' ' . esc_attr( $args['icon_class'] . '-' . $platform ) . '">' . $profile['icon'] . '</span>';
		
		if ( $args['show_labels'] ) {
			$output .= '<span class="social-icons-label">' . esc_html( $profile['label'] ) . '</span>';
		} else {
			$output .= '<span class="screen-reader-text">' . esc_html( $profile['label'] ) . '</span>';
		}
		
		$output .= '</a>';
		$output .= '</li>';
	}

	// Close container.
	$output .= '</ul>';
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display contact information
 *
 * @param array $args Contact information arguments.
 */
function aqualuxe_contact_info( $args = array() ) {
	$defaults = array(
		'container'      => 'div',
		'container_class' => 'contact-info',
		'ul_class'       => 'contact-info-list',
		'li_class'       => 'contact-info-item',
		'show_labels'    => true,
		'show_icons'     => true,
		'phone'          => true,
		'email'          => true,
		'address'        => true,
		'hours'          => true,
		'phone_label'    => esc_html__( 'Phone', 'aqualuxe' ),
		'email_label'    => esc_html__( 'Email', 'aqualuxe' ),
		'address_label'  => esc_html__( 'Address', 'aqualuxe' ),
		'hours_label'    => esc_html__( 'Hours', 'aqualuxe' ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Get contact information.
	$contact_info = array(
		'phone'   => array(
			'value' => get_theme_mod( 'contact_phone' ),
			'label' => $args['phone_label'],
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M9.366 10.682a10.556 10.556 0 0 0 3.952 3.952l.884-1.238a1 1 0 0 1 1.294-.296 11.422 11.422 0 0 0 4.583 1.364 1 1 0 0 1 .921.997v4.462a1 1 0 0 1-.898.995c-.53.055-1.064.082-1.602.082C9.94 21 3 14.06 3 5.5c0-.538.027-1.072.082-1.602A1 1 0 0 1 4.077 3h4.462a1 1 0 0 1 .997.921A11.422 11.422 0 0 0 10.9 8.504a1 1 0 0 1-.296 1.294l-1.238.884zm-2.522-.657l1.9-1.357A13.41 13.41 0 0 1 7.647 5H5.01c-.006.166-.009.333-.009.5C5 12.956 11.044 19 18.5 19c.167 0 .334-.003.5-.01v-2.637a13.41 13.41 0 0 1-3.668-1.097l-1.357 1.9a12.442 12.442 0 0 1-1.588-.75l-.058-.033a12.556 12.556 0 0 1-4.702-4.702l-.033-.058a12.442 12.442 0 0 1-.75-1.588z"/></svg>',
			'show'  => $args['phone'],
		),
		'email'   => array(
			'value' => get_theme_mod( 'contact_email' ),
			'label' => $args['email_label'],
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm17 4.238l-7.928 7.1L4 7.216V19h16V7.238zM4.511 5l7.55 6.662L19.502 5H4.511z"/></svg>',
			'show'  => $args['email'],
		),
		'address' => array(
			'value' => get_theme_mod( 'contact_address' ),
			'label' => $args['address_label'],
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 20.9l4.95-4.95a7 7 0 1 0-9.9 0L12 20.9zm0 2.828l-6.364-6.364a9 9 0 1 1 12.728 0L12 23.728zM12 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 2a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/></svg>',
			'show'  => $args['address'],
		),
		'hours'   => array(
			'value' => get_theme_mod( 'contact_hours' ),
			'label' => $args['hours_label'],
			'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm1-8h4v2h-6V7h2v5z"/></svg>',
			'show'  => $args['hours'],
		),
	);

	// Filter out empty or disabled contact info.
	$contact_info = array_filter( $contact_info, function( $info ) {
		return ! empty( $info['value'] ) && $info['show'];
	} );

	if ( empty( $contact_info ) ) {
		return;
	}

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';
	$output .= '<ul class="' . esc_attr( $args['ul_class'] ) . '">';

	// Add contact info.
	foreach ( $contact_info as $type => $info ) {
		$output .= '<li class="' . esc_attr( $args['li_class'] ) . ' ' . esc_attr( $args['li_class'] . '-' . $type ) . '">';
		
		if ( $args['show_icons'] ) {
			$output .= '<span class="contact-info-icon contact-info-icon-' . esc_attr( $type ) . '">' . $info['icon'] . '</span>';
		}
		
		if ( $args['show_labels'] ) {
			$output .= '<span class="contact-info-label contact-info-label-' . esc_attr( $type ) . '">' . esc_html( $info['label'] ) . '</span>';
		}
		
		if ( 'phone' === $type ) {
			$output .= '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $info['value'] ) ) . '" class="contact-info-value contact-info-value-' . esc_attr( $type ) . '">' . esc_html( $info['value'] ) . '</a>';
		} elseif ( 'email' === $type ) {
			$output .= '<a href="mailto:' . esc_attr( $info['value'] ) . '" class="contact-info-value contact-info-value-' . esc_attr( $type ) . '">' . esc_html( $info['value'] ) . '</a>';
		} else {
			$output .= '<span class="contact-info-value contact-info-value-' . esc_attr( $type ) . '">' . esc_html( $info['value'] ) . '</span>';
		}
		
		$output .= '</li>';
	}

	// Close container.
	$output .= '</ul>';
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display copyright text
 *
 * @param array $args Copyright arguments.
 */
function aqualuxe_copyright( $args = array() ) {
	$defaults = array(
		'container'      => 'div',
		'container_class' => 'copyright',
		'text'           => get_theme_mod( 'footer_text', sprintf( esc_html__( 'Copyright &copy; %s AquaLuxe. All rights reserved.', 'aqualuxe' ), date( 'Y' ) ) ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Replace {year} with current year.
	$text = str_replace( '{year}', date( 'Y' ), $args['text'] );

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';

	// Add copyright text.
	$output .= wp_kses_post( $text );

	// Close container.
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display dark mode toggle
 *
 * @param array $args Dark mode toggle arguments.
 */
function aqualuxe_dark_mode_toggle( $args = array() ) {
	// Return early if dark mode is disabled.
	if ( ! get_theme_mod( 'enable_dark_mode', true ) ) {
		return;
	}

	$defaults = array(
		'container'      => 'div',
		'container_class' => 'dark-mode-toggle',
		'button_class'   => 'dark-mode-toggle-button',
		'icon_class'     => 'dark-mode-toggle-icon',
		'show_labels'    => false,
		'light_label'    => esc_html__( 'Light', 'aqualuxe' ),
		'dark_label'     => esc_html__( 'Dark', 'aqualuxe' ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';

	// Add button.
	$output .= '<button type="button" class="' . esc_attr( $args['button_class'] ) . '" aria-label="' . esc_attr__( 'Toggle dark mode', 'aqualuxe' ) . '">';
	
	// Add light icon.
	$output .= '<span class="' . esc_attr( $args['icon_class'] ) . ' ' . esc_attr( $args['icon_class'] . '-light' ) . '">';
	$output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 18a6 6 0 1 1 0-12 6 6 0 0 1 0 12zm0-2a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM11 1h2v3h-2V1zm0 19h2v3h-2v-3zM3.515 4.929l1.414-1.414L7.05 5.636 5.636 7.05 3.515 4.93zM16.95 18.364l1.414-1.414 2.121 2.121-1.414 1.414-2.121-2.121zm2.121-14.85l1.414 1.415-2.121 2.121-1.414-1.414 2.121-2.121zM5.636 16.95l1.414 1.414-2.121 2.121-1.414-1.414 2.121-2.121zM23 11v2h-3v-2h3zM4 11v2H1v-2h3z"/></svg>';
	
	if ( $args['show_labels'] ) {
		$output .= '<span class="dark-mode-toggle-label dark-mode-toggle-label-light">' . esc_html( $args['light_label'] ) . '</span>';
	}
	
	$output .= '</span>';
	
	// Add dark icon.
	$output .= '<span class="' . esc_attr( $args['icon_class'] ) . ' ' . esc_attr( $args['icon_class'] . '-dark' ) . '">';
	$output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M10 7a7 7 0 0 0 12 4.9v.1c0 5.523-4.477 10-10 10S2 17.523 2 12 6.477 2 12 2h.1A6.979 6.979 0 0 0 10 7zm-6 5a8 8 0 0 0 15.062 3.762A9 9 0 0 1 8.238 4.938 7.999 7.999 0 0 0 4 12z"/></svg>';
	
	if ( $args['show_labels'] ) {
		$output .= '<span class="dark-mode-toggle-label dark-mode-toggle-label-dark">' . esc_html( $args['dark_label'] ) . '</span>';
	}
	
	$output .= '</span>';
	
	$output .= '</button>';

	// Close container.
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display search form
 *
 * @param array $args Search form arguments.
 */
function aqualuxe_search_form( $args = array() ) {
	$defaults = array(
		'container'      => 'div',
		'container_class' => 'search-form-container',
		'form_class'     => 'search-form',
		'label_class'    => 'search-form-label',
		'input_class'    => 'search-form-input',
		'button_class'   => 'search-form-button',
		'show_label'     => false,
		'placeholder'    => esc_html__( 'Search...', 'aqualuxe' ),
		'button_text'    => esc_html__( 'Search', 'aqualuxe' ),
		'aria_label'     => esc_html__( 'Search', 'aqualuxe' ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';

	// Add form.
	$output .= '<form role="search" method="get" class="' . esc_attr( $args['form_class'] ) . '" action="' . esc_url( home_url( '/' ) ) . '">';
	
	// Add label.
	if ( $args['show_label'] ) {
		$output .= '<label class="' . esc_attr( $args['label_class'] ) . '" for="search-form-input">' . esc_html( $args['aria_label'] ) . '</label>';
	} else {
		$output .= '<label class="screen-reader-text" for="search-form-input">' . esc_html( $args['aria_label'] ) . '</label>';
	}
	
	// Add input.
	$output .= '<input type="search" id="search-form-input" class="' . esc_attr( $args['input_class'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" value="' . esc_attr( get_search_query() ) . '" name="s" />';
	
	// Add button.
	$output .= '<button type="submit" class="' . esc_attr( $args['button_class'] ) . '">';
	$output .= '<span class="search-form-button-icon">';
	$output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>';
	$output .= '</span>';
	$output .= '<span class="search-form-button-text">' . esc_html( $args['button_text'] ) . '</span>';
	$output .= '</button>';
	
	$output .= '</form>';

	// Close container.
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display mobile menu toggle
 *
 * @param array $args Mobile menu toggle arguments.
 */
function aqualuxe_mobile_menu_toggle( $args = array() ) {
	$defaults = array(
		'container'      => 'div',
		'container_class' => 'mobile-menu-toggle-container',
		'button_class'   => 'mobile-menu-toggle',
		'icon_class'     => 'mobile-menu-toggle-icon',
		'show_label'     => true,
		'label'          => esc_html__( 'Menu', 'aqualuxe' ),
		'aria_label'     => esc_html__( 'Toggle mobile menu', 'aqualuxe' ),
		'target'         => 'mobile-menu',
	);

	$args = wp_parse_args( $args, $defaults );

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';

	// Add button.
	$output .= '<button type="button" class="' . esc_attr( $args['button_class'] ) . '" aria-label="' . esc_attr( $args['aria_label'] ) . '" aria-expanded="false" aria-controls="' . esc_attr( $args['target'] ) . '">';
	
	// Add icon.
	$output .= '<span class="' . esc_attr( $args['icon_class'] ) . '">';
	$output .= '<span class="mobile-menu-toggle-bar"></span>';
	$output .= '<span class="mobile-menu-toggle-bar"></span>';
	$output .= '<span class="mobile-menu-toggle-bar"></span>';
	$output .= '</span>';
	
	// Add label.
	if ( $args['show_label'] ) {
		$output .= '<span class="mobile-menu-toggle-label">' . esc_html( $args['label'] ) . '</span>';
	}
	
	$output .= '</button>';

	// Close container.
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display back to top button
 *
 * @param array $args Back to top arguments.
 */
function aqualuxe_back_to_top( $args = array() ) {
	$defaults = array(
		'container'      => 'div',
		'container_class' => 'back-to-top-container',
		'button_class'   => 'back-to-top',
		'icon_class'     => 'back-to-top-icon',
		'show_label'     => false,
		'label'          => esc_html__( 'Back to top', 'aqualuxe' ),
		'aria_label'     => esc_html__( 'Back to top', 'aqualuxe' ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Open container.
	$output = '<' . esc_attr( $args['container'] ) . ' class="' . esc_attr( $args['container_class'] ) . '">';

	// Add button.
	$output .= '<button type="button" class="' . esc_attr( $args['button_class'] ) . '" aria-label="' . esc_attr( $args['aria_label'] ) . '">';
	
	// Add icon.
	$output .= '<span class="' . esc_attr( $args['icon_class'] ) . '">';
	$output .= '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>';
	$output .= '</span>';
	
	// Add label.
	if ( $args['show_label'] ) {
		$output .= '<span class="back-to-top-label">' . esc_html( $args['label'] ) . '</span>';
	} else {
		$output .= '<span class="screen-reader-text">' . esc_html( $args['label'] ) . '</span>';
	}
	
	$output .= '</button>';

	// Close container.
	$output .= '</' . esc_attr( $args['container'] ) . '>';

	echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Get SVG icon
 *
 * @param string $icon Icon name.
 * @param array  $args Icon arguments.
 * @return string
 */
function aqualuxe_get_svg_icon( $icon, $args = array() ) {
	$defaults = array(
		'class'   => 'svg-icon',
		'width'   => 24,
		'height'  => 24,
	);

	$args = wp_parse_args( $args, $defaults );

	// Define icons.
	$icons = array(
		'search'   => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg>',
		'cart'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 16V4H2V2h3a1 1 0 0 1 1 1v12h12.438l2-8H8V5h13.72a1 1 0 0 1 .97 1.243l-2.5 10a1 1 0 0 1-.97.757H5a1 1 0 0 1-1-1zm2 7a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm12 0a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>',
		'user'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M4 22a8 8 0 1 1 16 0h-2a6 6 0 1 0-12 0H4zm8-9c-3.315 0-6-2.685-6-6s2.685-6 6-6 6 2.685 6 6-2.685 6-6 6zm0-2c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"/></svg>',
		'heart'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M12.001 4.529c2.349-2.109 5.979-2.039 8.242.228 2.262 2.268 2.34 5.88.236 8.236l-8.48 8.492-8.478-8.492c-2.104-2.356-2.025-5.974.236-8.236 2.265-2.264 5.888-2.34 8.244-.228zm6.826 1.641c-1.5-1.502-3.92-1.563-5.49-.153l-1.335 1.198-1.336-1.197c-1.575-1.412-3.99-1.35-5.494.154-1.49 1.49-1.565 3.875-.192 5.451L12 18.654l7.02-7.03c1.374-1.577 1.299-3.959-.193-5.454z"/></svg>',
		'menu'     => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/></svg>',
		'close'    => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/></svg>',
		'chevron-down' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 13.172l4.95-4.95 1.414 1.414L12 16 5.636 9.636 7.05 8.222z"/></svg>',
		'chevron-up' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 10.828l-4.95 4.95-1.414-1.414L12 8l6.364 6.364-1.414 1.414z"/></svg>',
		'chevron-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M10.828 12l4.95 4.95-1.414 1.414L8 12l6.364-6.364 1.414 1.414z"/></svg>',
		'chevron-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.172 12l-4.95-4.95 1.414-1.414L16 12l-6.364 6.364-1.414-1.414z"/></svg>',
		'arrow-left' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M7.828 11H20v2H7.828l5.364 5.364-1.414 1.414L4 12l7.778-7.778 1.414 1.414z"/></svg>',
		'arrow-right' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M16.172 11l-5.364-5.364 1.414-1.414L20 12l-7.778 7.778-1.414-1.414L16.172 13H4v-2z"/></svg>',
		'phone' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M9.366 10.682a10.556 10.556 0 0 0 3.952 3.952l.884-1.238a1 1 0 0 1 1.294-.296 11.422 11.422 0 0 0 4.583 1.364 1 1 0 0 1 .921.997v4.462a1 1 0 0 1-.898.995c-.53.055-1.064.082-1.602.082C9.94 21 3 14.06 3 5.5c0-.538.027-1.072.082-1.602A1 1 0 0 1 4.077 3h4.462a1 1 0 0 1 .997.921A11.422 11.422 0 0 0 10.9 8.504a1 1 0 0 1-.296 1.294l-1.238.884zm-2.522-.657l1.9-1.357A13.41 13.41 0 0 1 7.647 5H5.01c-.006.166-.009.333-.009.5C5 12.956 11.044 19 18.5 19c.167 0 .334-.003.5-.01v-2.637a13.41 13.41 0 0 1-3.668-1.097l-1.357 1.9a12.442 12.442 0 0 1-1.588-.75l-.058-.033a12.556 12.556 0 0 1-4.702-4.702l-.033-.058a12.442 12.442 0 0 1-.75-1.588z"/></svg>',
		'email' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm17 4.238l-7.928 7.1L4 7.216V19h16V7.238zM4.511 5l7.55 6.662L19.502 5H4.511z"/></svg>',
		'map' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 20.9l4.95-4.95a7 7 0 1 0-9.9 0L12 20.9zm0 2.828l-6.364-6.364a9 9 0 1 1 12.728 0L12 23.728zM12 13a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm0 2a4 4 0 1 1 0-8 4 4 0 0 1 0 8z"/></svg>',
		'clock' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm1-8h4v2h-6V7h2v5z"/></svg>',
		'facebook' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 13.5h2.5l1-4H14v-2c0-1.03 0-2 2-2h1.5V2.14c-.326-.043-1.557-.14-2.857-.14C11.928 2 10 3.657 10 6.7v2.8H7v4h3V22h4v-8.5z"/></svg>',
		'twitter' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M22.162 5.656a8.384 8.384 0 0 1-2.402.658A4.196 4.196 0 0 0 21.6 4c-.82.488-1.719.83-2.656 1.015a4.182 4.182 0 0 0-7.126 3.814 11.874 11.874 0 0 1-8.62-4.37 4.168 4.168 0 0 0-.566 2.103c0 1.45.738 2.731 1.86 3.481a4.168 4.168 0 0 1-1.894-.523v.052a4.185 4.185 0 0 0 3.355 4.101 4.21 4.21 0 0 1-1.89.072A4.185 4.185 0 0 0 7.97 16.65a8.394 8.394 0 0 1-6.191 1.732 11.83 11.83 0 0 0 6.41 1.88c7.693 0 11.9-6.373 11.9-11.9 0-.18-.005-.362-.013-.54a8.496 8.496 0 0 0 2.087-2.165z"/></svg>',
		'instagram' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 9a3 3 0 1 0 0 6 3 3 0 0 0 0-6zm0-2a5 5 0 1 1 0 10 5 5 0 0 1 0-10zm6.5-.25a1.25 1.25 0 0 1-2.5 0 1.25 1.25 0 0 1 2.5 0zM12 4c-2.474 0-2.878.007-4.029.058-.784.037-1.31.142-1.798.332-.434.168-.747.369-1.08.703a2.89 2.89 0 0 0-.704 1.08c-.19.49-.295 1.015-.331 1.798C4.006 9.075 4 9.461 4 12c0 2.474.007 2.878.058 4.029.037.783.142 1.31.331 1.797.17.435.37.748.702 1.08.337.336.65.537 1.08.703.494.191 1.02.297 1.8.333C9.075 19.994 9.461 20 12 20c2.474 0 2.878-.007 4.029-.058.782-.037 1.309-.142 1.797-.331.433-.169.748-.37 1.08-.702.337-.337.538-.65.704-1.08.19-.493.296-1.02.332-1.8.052-1.104.058-1.49.058-4.029 0-2.474-.007-2.878-.058-4.029-.037-.782-.142-1.31-.332-1.798a2.911 2.911 0 0 0-.703-1.08 2.884 2.884 0 0 0-1.08-.704c-.49-.19-1.016-.295-1.798-.331C14.925 4.006 14.539 4 12 4zm0-2c2.717 0 3.056.01 4.122.06 1.065.05 1.79.217 2.428.465.66.254 1.216.598 1.772 1.153a4.908 4.908 0 0 1 1.153 1.772c.247.637.415 1.363.465 2.428.047 1.066.06 1.405.06 4.122 0 2.717-.01 3.056-.06 4.122-.05 1.065-.218 1.79-.465 2.428a4.883 4.883 0 0 1-1.153 1.772 4.915 4.915 0 0 1-1.772 1.153c-.637.247-1.363.415-2.428.465-1.066.047-1.405.06-4.122.06-2.717 0-3.056-.01-4.122-.06-1.065-.05-1.79-.218-2.428-.465a4.89 4.89 0 0 1-1.772-1.153 4.904 4.904 0 0 1-1.153-1.772c-.248-.637-.415-1.363-.465-2.428C2.013 15.056 2 14.717 2 12c0-2.717.01-3.056.06-4.122.05-1.066.217-1.79.465-2.428a4.88 4.88 0 0 1 1.153-1.772A4.897 4.897 0 0 1 5.45 2.525c.638-.248 1.362-.415 2.428-.465C8.944 2.013 9.283 2 12 2z"/></svg>',
		'linkedin' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M6.94 5a2 2 0 1 1-4-.002 2 2 0 0 1 4 .002zM7 8.48H3V21h4V8.48zm6.32 0H9.34V21h3.94v-6.57c0-3.66 4.77-4 4.77 0V21H22v-7.93c0-6.17-7.06-5.94-8.72-2.91l.04-1.68z"/></svg>',
		'youtube' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M21.543 6.498C22 8.28 22 12 22 12s0 3.72-.457 5.502c-.254.985-.997 1.76-1.938 2.022C17.896 20 12 20 12 20s-5.893 0-7.605-.476c-.945-.266-1.687-1.04-1.938-2.022C2 15.72 2 12 2 12s0-3.72.457-5.502c.254-.985.997-1.76 1.938-2.022C6.107 4 12 4 12 4s5.896 0 7.605.476c.945.266 1.687 1.04 1.938 2.022zM10 15.5l6-3.5-6-3.5v7z"/></svg>',
		'pinterest' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="{width}" height="{height}"><path fill="none" d="M0 0h24v24H0z"/><path d="M13.37 2.094A10.003 10.003 0 0 0 8.002 21.17a7.757 7.757 0 0 1 .163-2.293c.185-.839 1.296-5.463 1.296-5.463a3.739 3.739 0 0 1-.324-1.577c0-1.485.857-2.593 1.923-2.593a1.334 1.334 0 0 1 1.342 1.508c0 .9-.578 2.262-.88 3.54a1.544 1.544 0 0 0 1.575 1.923c1.898 0 3.17-2.431 3.17-5.301 0-2.2-1.457-3.848-4.143-3.848a4.746 4.746 0 0 0-4.93 4.794 2.96 2.96 0 0 0 .648 1.97.48.48 0 0 1 .162.554c-.46.184-.162.623-.208.784a.354.354 0 0 1-.51.254c-1.384-.554-2.036-2.077-2.036-3.816 0-2.847 2.384-6.255 7.154-6.255 3.796 0 6.32 2.777 6.32 5.747 0 3.909-2.177 6.848-5.394 6.848a2.861 2.861 0 0 1-2.454-1.246s-.578 2.316-.692 2.754a8.026 8.026 0 0 1-1.019 2.131c.923.28 1.882.42 2.846.416a9.988 9.988 0 0 0 9.996-10.003 10.002 10.002 0 0 0-8.635-9.903z"/></svg>',
	);

	// Check if icon exists.
	if ( ! isset( $icons[ $icon ] ) ) {
		return '';
	}

	// Replace width and height.
	$svg = str_replace( '{width}', esc_attr( $args['width'] ), $icons[ $icon ] );
	$svg = str_replace( '{height}', esc_attr( $args['height'] ), $svg );

	// Add class.
	$svg = str_replace( '<svg', '<svg class="' . esc_attr( $args['class'] ) . '"', $svg );

	return $svg;
}

/**
 * Display SVG icon
 *
 * @param string $icon Icon name.
 * @param array  $args Icon arguments.
 */
function aqualuxe_svg_icon( $icon, $args = array() ) {
	echo aqualuxe_get_svg_icon( $icon, $args ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}