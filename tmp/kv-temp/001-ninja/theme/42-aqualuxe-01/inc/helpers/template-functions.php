<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes( $classes ) {
	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Add a class if dark mode is active
	if ( isset( $_COOKIE['aqualuxe_dark_mode'] ) && $_COOKIE['aqualuxe_dark_mode'] === 'true' ) {
		$classes[] = 'dark-mode';
	}

	// Add a class for the current page template
	if ( is_page_template() ) {
		$template_slug = get_page_template_slug();
		$template_parts = explode( '/', $template_slug );
		$template_name = str_replace( '.php', '', end( $template_parts ) );
		$classes[] = 'template-' . sanitize_html_class( $template_name );
	}

	// Add a class for WooCommerce pages
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) {
			$classes[] = 'woocommerce-page';
		}
	}

	return $classes;
}
add_filter( 'body_class', 'aqualuxe_body_classes' );

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header() {
	if ( is_singular() && pings_open() ) {
		printf( '<link rel="pingback" href="%s">', esc_url( get_bloginfo( 'pingback_url' ) ) );
	}
}
add_action( 'wp_head', 'aqualuxe_pingback_header' );

/**
 * Custom pagination for the theme
 */
function aqualuxe_pagination() {
	global $wp_query;

	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	$big = 999999999; // need an unlikely integer
	$pages = paginate_links( array(
		'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'    => '?paged=%#%',
		'current'   => max( 1, get_query_var( 'paged' ) ),
		'total'     => $wp_query->max_num_pages,
		'type'      => 'array',
		'prev_text' => '<i class="fas fa-chevron-left"></i> ' . esc_html__( 'Previous', 'aqualuxe' ),
		'next_text' => esc_html__( 'Next', 'aqualuxe' ) . ' <i class="fas fa-chevron-right"></i>',
	) );

	if ( is_array( $pages ) ) {
		echo '<nav class="pagination flex flex-wrap justify-center items-center space-x-2" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
		
		foreach ( $pages as $page ) {
			$active = strpos( $page, 'current' ) !== false ? 'bg-primary-600 text-white' : 'bg-white text-primary-600 hover:bg-primary-50';
			echo '<div class="pagination-item ' . esc_attr( $active ) . ' border border-primary-300 rounded-md transition-colors">' . $page . '</div>';
		}
		
		echo '</nav>';
	}
}

/**
 * Custom breadcrumbs for the theme
 */
function aqualuxe_breadcrumbs() {
	// Settings
	$separator          = '<i class="fas fa-chevron-right mx-2 text-gray-400"></i>';
	$breadcrums_id      = 'breadcrumbs';
	$breadcrums_class   = 'breadcrumbs text-sm text-gray-600 mb-6';
	$home_title         = esc_html__( 'Home', 'aqualuxe' );

	// Get the query & post information
	global $post, $wp_query;

	// Do not display on the homepage
	if ( is_front_page() ) {
		return;
	}

	// Build the breadcrumbs
	echo '<nav aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
	echo '<ol id="' . esc_attr( $breadcrums_id ) . '" class="' . esc_attr( $breadcrums_class ) . '">';

	// Home page
	echo '<li class="breadcrumb-item"><a href="' . esc_url( home_url() ) . '" class="text-primary-600 hover:text-primary-800 transition-colors">' . esc_html( $home_title ) . '</a></li>';
	echo $separator;

	if ( is_archive() && ! is_tax() && ! is_category() && ! is_tag() ) {
		echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_archive_title() . '</li>';
	} elseif ( is_archive() && is_tax() && ! is_category() && ! is_tag() ) {
		// If post is a custom post type
		$post_type = get_post_type();
		
		// If it is a custom post type display name and link
		if ( $post_type != 'post' ) {
			$post_type_object = get_post_type_object( $post_type );
			$post_type_archive = get_post_type_archive_link( $post_type );
			
			echo '<li class="breadcrumb-item"><a href="' . esc_url( $post_type_archive ) . '" class="text-primary-600 hover:text-primary-800 transition-colors">' . esc_html( $post_type_object->labels->name ) . '</a></li>';
			echo $separator;
		}
		
		$custom_tax_name = get_queried_object()->name;
		echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html( $custom_tax_name ) . '</li>';
	} elseif ( is_single() ) {
		// If post is a custom post type
		$post_type = get_post_type();
		
		// If it is a custom post type display name and link
		if ( $post_type != 'post' ) {
			$post_type_object = get_post_type_object( $post_type );
			$post_type_archive = get_post_type_archive_link( $post_type );
			
			echo '<li class="breadcrumb-item"><a href="' . esc_url( $post_type_archive ) . '" class="text-primary-600 hover:text-primary-800 transition-colors">' . esc_html( $post_type_object->labels->name ) . '</a></li>';
			echo $separator;
		}
		
		// Get post category info
		$category = get_the_category();
		
		if ( ! empty( $category ) ) {
			// Get last category post is in
			$last_category = end( $category );
			
			// Get parent any categories and create array
			$get_cat_parents = rtrim( get_category_parents( $last_category->term_id, true, ',' ), ',' );
			$cat_parents = explode( ',', $get_cat_parents );
			
			// Loop through parent categories and store in variable $cat_display
			$cat_display = '';
			foreach ( $cat_parents as $parents ) {
				$cat_display .= '<li class="breadcrumb-item">' . $parents . '</li>';
				$cat_display .= $separator;
			}
			
			echo $cat_display;
		}
		
		// Current page
		echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
	} elseif ( is_category() ) {
		// Category page
		echo '<li class="breadcrumb-item active" aria-current="page">' . single_cat_title( '', false ) . '</li>';
	} elseif ( is_page() ) {
		// Standard page
		if ( $post->post_parent ) {
			// If child page, get parents
			$anc = get_post_ancestors( $post->ID );
			
			// Get parents in the right order
			$anc = array_reverse( $anc );
			
			// Parent page loop
			foreach ( $anc as $ancestor ) {
				echo '<li class="breadcrumb-item"><a href="' . esc_url( get_permalink( $ancestor ) ) . '" class="text-primary-600 hover:text-primary-800 transition-colors">' . get_the_title( $ancestor ) . '</a></li>';
				echo $separator;
			}
		}
		
		// Current page
		echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
	} elseif ( is_tag() ) {
		// Tag page
		echo '<li class="breadcrumb-item active" aria-current="page">' . single_tag_title( '', false ) . '</li>';
	} elseif ( is_author() ) {
		// Author page
		echo '<li class="breadcrumb-item active" aria-current="page">' . get_the_author() . '</li>';
	} elseif ( is_search() ) {
		// Search page
		echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__( 'Search results for: ', 'aqualuxe' ) . get_search_query() . '</li>';
	} elseif ( is_404() ) {
		// 404 page
		echo '<li class="breadcrumb-item active" aria-current="page">' . esc_html__( 'Error 404', 'aqualuxe' ) . '</li>';
	}
	
	echo '</ol>';
	echo '</nav>';
}

/**
 * Display related posts
 */
function aqualuxe_related_posts() {
	global $post;
	
	// Get the current post categories
	$categories = get_the_category( $post->ID );
	
	if ( $categories ) {
		$category_ids = array();
		foreach ( $categories as $category ) {
			$category_ids[] = $category->term_id;
		}
		
		// Query related posts
		$related_args = array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => 3,
			'post__not_in'   => array( $post->ID ),
			'category__in'   => $category_ids,
			'orderby'        => 'rand',
		);
		
		$related_query = new WP_Query( $related_args );
		
		// Display related posts
		if ( $related_query->have_posts() ) {
			?>
			<div class="related-posts mt-12 pt-8 border-t border-gray-200">
				<h3 class="text-2xl font-bold text-primary-800 mb-6"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
				
				<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
					<?php
					while ( $related_query->have_posts() ) {
						$related_query->the_post();
						?>
						<div class="related-post bg-white rounded-lg shadow-md overflow-hidden transition-shadow hover:shadow-lg">
							<?php if ( has_post_thumbnail() ) : ?>
								<a href="<?php the_permalink(); ?>" class="block overflow-hidden">
									<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
								</a>
							<?php endif; ?>
							
							<div class="p-4">
								<h4 class="text-lg font-bold text-primary-800 mb-2">
									<a href="<?php the_permalink(); ?>" class="hover:text-primary-600 transition-colors">
										<?php the_title(); ?>
									</a>
								</h4>
								
								<div class="entry-meta text-xs text-gray-600 mb-2">
									<?php aqualuxe_posted_on(); ?>
								</div>
								
								<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-800 font-medium transition-colors text-sm mt-2">
									<?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <i class="fas fa-arrow-right ml-1"></i>
								</a>
							</div>
						</div>
						<?php
					}
					wp_reset_postdata();
					?>
				</div>
			</div>
			<?php
		}
	}
}

/**
 * Custom comment callback
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	
	$comment_class = 'comment-body bg-white p-6 rounded-lg shadow-sm';
	if ( $comment->comment_approved == '0' ) {
		$comment_class .= ' unapproved';
	}
	?>
	
	<li id="comment-<?php comment_ID(); ?>" <?php comment_class(); ?>>
		<article class="<?php echo esc_attr( $comment_class ); ?>">
			<footer class="comment-meta flex items-start mb-4">
				<div class="comment-author vcard mr-4">
					<?php echo get_avatar( $comment, 60, '', '', array( 'class' => 'rounded-full' ) ); ?>
				</div>
				
				<div class="comment-metadata">
					<h4 class="font-bold text-primary-800">
						<?php echo get_comment_author_link(); ?>
						
						<?php if ( $comment->user_id === get_the_author_meta( 'ID' ) ) : ?>
							<span class="author-badge bg-primary-100 text-primary-800 text-xs px-2 py-1 rounded-full ml-2">
								<?php esc_html_e( 'Author', 'aqualuxe' ); ?>
							</span>
						<?php endif; ?>
					</h4>
					
					<div class="text-sm text-gray-600">
						<time datetime="<?php echo esc_attr( get_comment_date( 'c' ) ); ?>">
							<?php
							/* translators: 1: comment date, 2: comment time */
							printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), get_comment_date(), get_comment_time() );
							?>
						</time>
						
						<?php edit_comment_link( esc_html__( 'Edit', 'aqualuxe' ), ' <span class="edit-link">', '</span>' ); ?>
					</div>
				</div>
			</footer>
			
			<div class="comment-content prose prose-sm max-w-none">
				<?php if ( '0' == $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation bg-yellow-50 border-l-4 border-yellow-400 p-4 text-yellow-800 mb-4">
						<?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?>
					</p>
				<?php endif; ?>
				
				<?php comment_text(); ?>
			</div>
			
			<div class="reply mt-4">
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'reply_text' => '<i class="fas fa-reply mr-1"></i> ' . esc_html__( 'Reply', 'aqualuxe' ),
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'before'     => '<div class="reply-link inline-flex items-center text-primary-600 hover:text-primary-800 transition-colors text-sm">',
							'after'      => '</div>',
						)
					)
				);
				?>
			</div>
		</article>
	<?php
}

/**
 * Get the first category of a post
 */
function aqualuxe_get_first_category() {
	$categories = get_the_category();
	if ( ! empty( $categories ) ) {
		return $categories[0];
	}
	return false;
}

/**
 * Get post views
 */
function aqualuxe_get_post_views( $post_id ) {
	$count_key = 'post_views_count';
	$count = get_post_meta( $post_id, $count_key, true );
	
	if ( $count == '' ) {
		delete_post_meta( $post_id, $count_key );
		add_post_meta( $post_id, $count_key, '0' );
		return '0';
	}
	
	return $count;
}

/**
 * Set post views
 */
function aqualuxe_set_post_views( $post_id ) {
	$count_key = 'post_views_count';
	$count = get_post_meta( $post_id, $count_key, true );
	
	if ( $count == '' ) {
		$count = 0;
		delete_post_meta( $post_id, $count_key );
		add_post_meta( $post_id, $count_key, '0' );
	} else {
		$count++;
		update_post_meta( $post_id, $count_key, $count );
	}
}

/**
 * Track post views
 */
function aqualuxe_track_post_views( $post_id ) {
	// Don't track post views for admins
	if ( ! is_user_logged_in() || ! current_user_can( 'manage_options' ) ) {
		if ( is_single() ) {
			aqualuxe_set_post_views( get_the_ID() );
		}
	}
}
add_action( 'wp_head', 'aqualuxe_track_post_views' );

/**
 * Add post views column to admin
 */
function aqualuxe_posts_column_views( $columns ) {
	$columns['post_views'] = esc_html__( 'Views', 'aqualuxe' );
	return $columns;
}
add_filter( 'manage_posts_columns', 'aqualuxe_posts_column_views' );

/**
 * Display post views in admin
 */
function aqualuxe_posts_custom_column_views( $column ) {
	if ( $column === 'post_views' ) {
		echo esc_html( aqualuxe_get_post_views( get_the_ID() ) );
	}
}
add_action( 'manage_posts_custom_column', 'aqualuxe_posts_custom_column_views' );

/**
 * Modify the search form
 */
function aqualuxe_search_form( $form ) {
	$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
		<div class="relative">
			<input type="search" class="search-field w-full px-4 py-2 pr-10 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-300 focus:border-transparent" placeholder="' . esc_attr__( 'Search&hellip;', 'aqualuxe' ) . '" value="' . get_search_query() . '" name="s" />
			<button type="submit" class="search-submit absolute right-0 top-0 h-full px-3 text-gray-500 hover:text-primary-600 transition-colors">
				<i class="fas fa-search"></i>
				<span class="screen-reader-text">' . esc_html__( 'Search', 'aqualuxe' ) . '</span>
			</button>
		</div>
	</form>';

	return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form' );

/**
 * Modify the excerpt length
 */
function aqualuxe_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'aqualuxe_excerpt_length', 999 );

/**
 * Modify the excerpt more
 */
function aqualuxe_excerpt_more( $more ) {
	return '&hellip;';
}
add_filter( 'excerpt_more', 'aqualuxe_excerpt_more' );

/**
 * Add schema markup to the body
 */
function aqualuxe_body_schema() {
	// Get the schema type
	$schema = 'WebPage';
	
	// Check if it's a single post or page
	if ( is_singular( 'post' ) ) {
		$schema = 'Article';
	} elseif ( is_author() ) {
		$schema = 'ProfilePage';
	} elseif ( is_search() ) {
		$schema = 'SearchResultsPage';
	}
	
	echo 'itemscope itemtype="https://schema.org/' . esc_attr( $schema ) . '"';
}