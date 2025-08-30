<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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

	// Add a class for the header layout.
	$header_layout = get_theme_mod( 'aqualuxe_header_layout', 'standard' );
	$classes[] = 'header-layout-' . $header_layout;

	// Add a class for dark mode.
	if ( get_theme_mod( 'aqualuxe_dark_mode_default', false ) ) {
		$classes[] = 'dark-mode-default';
	}

	// Add a class for RTL support.
	if ( is_rtl() ) {
		$classes[] = 'rtl';
	}

	// Add a class for WooCommerce if active.
	if ( class_exists( 'WooCommerce' ) ) {
		$classes[] = 'woocommerce-active';
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
 * Display pagination for archive pages.
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
		'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg><span class="sr-only">' . esc_html__( 'Previous', 'aqualuxe' ) . '</span>',
		'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg><span class="sr-only">' . esc_html__( 'Next', 'aqualuxe' ) . '</span>',
	) );

	if ( is_array( $pages ) ) {
		echo '<nav class="pagination-wrapper flex justify-center my-8" aria-label="' . esc_attr__( 'Pagination', 'aqualuxe' ) . '">';
		echo '<ul class="pagination flex flex-wrap items-center gap-2">';

		foreach ( $pages as $page ) {
			$active = strpos( $page, 'current' ) !== false ? 'bg-primary-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700';
			echo '<li class="page-item">';
			echo str_replace( 
				array( 'page-numbers', 'current' ), 
				array( 'page-link inline-flex items-center justify-center w-10 h-10 rounded-md border border-gray-300 dark:border-gray-600 ' . $active, 'current' ), 
				$page 
			);
			echo '</li>';
		}

		echo '</ul>';
		echo '</nav>';
	}
}

/**
 * Get related posts based on post tags or categories.
 *
 * @param int    $post_id      The post ID.
 * @param int    $related_count Number of related posts to return.
 * @param string $taxonomy     The taxonomy to use for related posts (category or post_tag).
 * @return WP_Query
 */
function aqualuxe_get_related_posts( $post_id, $related_count = 3, $taxonomy = 'category' ) {
	$terms = get_the_terms( $post_id, $taxonomy );
	
	if ( ! $terms || is_wp_error( $terms ) ) {
		return new WP_Query();
	}
	
	$term_ids = wp_list_pluck( $terms, 'term_id' );
	
	$related_args = array(
		'post_type'      => 'post',
		'posts_per_page' => $related_count,
		'post_status'    => 'publish',
		'post__not_in'   => array( $post_id ),
		'orderby'        => 'rand',
		'tax_query'      => array(
			array(
				'taxonomy' => $taxonomy,
				'field'    => 'term_id',
				'terms'    => $term_ids,
			),
		),
	);
	
	return new WP_Query( $related_args );
}

/**
 * Display related posts.
 *
 * @param int    $post_id      The post ID.
 * @param int    $related_count Number of related posts to return.
 * @param string $taxonomy     The taxonomy to use for related posts (category or post_tag).
 */
function aqualuxe_related_posts( $post_id = null, $related_count = 3, $taxonomy = 'category' ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	$related_query = aqualuxe_get_related_posts( $post_id, $related_count, $taxonomy );
	
	if ( ! $related_query->have_posts() ) {
		return;
	}
	
	aqualuxe_before_related_posts();
	?>
	<div class="related-posts mt-12 pt-8 border-t border-gray-200 dark:border-gray-700">
		<h3 class="related-posts-title text-xl font-bold mb-6"><?php esc_html_e( 'Related Posts', 'aqualuxe' ); ?></h3>
		
		<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
			<?php
			while ( $related_query->have_posts() ) :
				$related_query->the_post();
				?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'related-post bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>" class="block">
							<?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
						</a>
					<?php endif; ?>
					
					<div class="p-4">
						<h4 class="entry-title text-lg font-semibold mb-2">
							<a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400"><?php the_title(); ?></a>
						</h4>
						
						<div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mb-2">
							<?php
							aqualuxe_posted_on();
							?>
						</div>
					</div>
				</article>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</div>
	</div>
	<?php
	aqualuxe_after_related_posts();
}

/**
 * Display author box.
 */
function aqualuxe_author_box() {
	if ( ! is_single() ) {
		return;
	}
	
	$author_id = get_the_author_meta( 'ID' );
	
	if ( ! $author_id ) {
		return;
	}
	
	$author_posts_url = get_author_posts_url( $author_id );
	$author_name      = get_the_author_meta( 'display_name', $author_id );
	$author_bio       = get_the_author_meta( 'description', $author_id );
	$author_website   = get_the_author_meta( 'user_url', $author_id );
	
	if ( ! $author_bio ) {
		return;
	}
	
	aqualuxe_before_author_box();
	?>
	<div class="author-box mt-12 p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md">
		<div class="flex flex-col md:flex-row items-center md:items-start gap-6">
			<div class="author-avatar">
				<?php echo get_avatar( $author_id, 96, '', $author_name, array( 'class' => 'rounded-full' ) ); ?>
			</div>
			
			<div class="author-info">
				<h3 class="author-name text-xl font-bold mb-2">
					<a href="<?php echo esc_url( $author_posts_url ); ?>" class="text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">
						<?php echo esc_html( $author_name ); ?>
					</a>
				</h3>
				
				<?php if ( $author_website ) : ?>
					<p class="author-website text-sm mb-3">
						<a href="<?php echo esc_url( $author_website ); ?>" class="text-primary-600 dark:text-primary-400 hover:underline" target="_blank" rel="noopener noreferrer">
							<?php echo esc_url( $author_website ); ?>
						</a>
					</p>
				<?php endif; ?>
				
				<div class="author-bio text-gray-700 dark:text-gray-300">
					<?php echo wpautop( wp_kses_post( $author_bio ) ); ?>
				</div>
				
				<a href="<?php echo esc_url( $author_posts_url ); ?>" class="inline-block mt-4 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition duration-200">
					<?php
					/* translators: %s: Author name. */
					printf( esc_html__( 'View all posts by %s', 'aqualuxe' ), esc_html( $author_name ) );
					?>
				</a>
			</div>
		</div>
	</div>
	<?php
	aqualuxe_after_author_box();
}

/**
 * Display social sharing buttons.
 */
function aqualuxe_social_sharing() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	
	$post_url   = urlencode( get_permalink() );
	$post_title = urlencode( get_the_title() );
	
	$facebook_url  = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
	$twitter_url   = 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title;
	$linkedin_url  = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title;
	$pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&description=' . $post_title;
	
	if ( has_post_thumbnail() ) {
		$pinterest_url .= '&media=' . urlencode( get_the_post_thumbnail_url( get_the_ID(), 'full' ) );
	}
	
	aqualuxe_before_share_buttons();
	?>
	<div class="social-sharing mt-8">
		<h3 class="social-sharing-title text-lg font-semibold mb-4"><?php esc_html_e( 'Share This Post', 'aqualuxe' ); ?></h3>
		
		<div class="flex flex-wrap gap-2">
			<a href="<?php echo esc_url( $facebook_url ); ?>" class="social-sharing-button facebook inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md transition duration-200" target="_blank" rel="noopener noreferrer">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
				</svg>
				<span><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
			</a>
			
			<a href="<?php echo esc_url( $twitter_url ); ?>" class="social-sharing-button twitter inline-flex items-center px-4 py-2 bg-blue-400 hover:bg-blue-500 text-white rounded-md transition duration-200" target="_blank" rel="noopener noreferrer">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
				</svg>
				<span><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
			</a>
			
			<a href="<?php echo esc_url( $linkedin_url ); ?>" class="social-sharing-button linkedin inline-flex items-center px-4 py-2 bg-blue-700 hover:bg-blue-800 text-white rounded-md transition duration-200" target="_blank" rel="noopener noreferrer">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
				</svg>
				<span><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
			</a>
			
			<a href="<?php echo esc_url( $pinterest_url ); ?>" class="social-sharing-button pinterest inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-md transition duration-200" target="_blank" rel="noopener noreferrer">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" />
				</svg>
				<span><?php esc_html_e( 'Pinterest', 'aqualuxe' ); ?></span>
			</a>
		</div>
	</div>
	<?php
	aqualuxe_after_share_buttons();
}

/**
 * Display post navigation.
 */
function aqualuxe_post_navigation() {
	if ( ! is_singular( 'post' ) ) {
		return;
	}
	
	$prev_post = get_previous_post();
	$next_post = get_next_post();
	
	if ( ! $prev_post && ! $next_post ) {
		return;
	}
	
	aqualuxe_before_post_navigation();
	?>
	<nav class="post-navigation mt-8 pt-6 border-t border-gray-200 dark:border-gray-700" aria-label="<?php esc_attr_e( 'Post Navigation', 'aqualuxe' ); ?>">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
			<?php if ( $prev_post ) : ?>
				<div class="post-navigation-prev">
					<span class="post-navigation-label text-sm text-gray-600 dark:text-gray-400 block mb-1"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></span>
					<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="post-navigation-link flex items-start gap-3 group">
						<?php if ( has_post_thumbnail( $prev_post->ID ) ) : ?>
							<div class="post-navigation-thumbnail w-16 h-16 flex-shrink-0">
								<?php echo get_the_post_thumbnail( $prev_post->ID, 'thumbnail', array( 'class' => 'w-full h-full object-cover rounded' ) ); ?>
							</div>
						<?php endif; ?>
						<span class="post-navigation-title font-medium text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition duration-200">
							<?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
						</span>
					</a>
				</div>
			<?php endif; ?>
			
			<?php if ( $next_post ) : ?>
				<div class="post-navigation-next text-right md:ml-auto">
					<span class="post-navigation-label text-sm text-gray-600 dark:text-gray-400 block mb-1"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></span>
					<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="post-navigation-link flex items-start gap-3 group justify-end">
						<span class="post-navigation-title font-medium text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition duration-200">
							<?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
						</span>
						<?php if ( has_post_thumbnail( $next_post->ID ) ) : ?>
							<div class="post-navigation-thumbnail w-16 h-16 flex-shrink-0">
								<?php echo get_the_post_thumbnail( $next_post->ID, 'thumbnail', array( 'class' => 'w-full h-full object-cover rounded' ) ); ?>
							</div>
						<?php endif; ?>
					</a>
				</div>
			<?php endif; ?>
		</div>
	</nav>
	<?php
	aqualuxe_after_post_navigation();
}

/**
 * Add schema.org markup to the HTML tag.
 *
 * @param string $output The output of the html tag.
 * @return string
 */
function aqualuxe_add_schema_markup( $output ) {
	$schema = 'http://schema.org/';
	
	// Default schema
	$type = 'WebPage';
	
	// Check for specific page types
	if ( is_singular( 'post' ) ) {
		$type = 'Article';
	} elseif ( is_author() ) {
		$type = 'ProfilePage';
	} elseif ( is_search() ) {
		$type = 'SearchResultsPage';
	} elseif ( is_front_page() || is_home() ) {
		$type = 'WebSite';
	}
	
	$schema_type = apply_filters( 'aqualuxe_schema_type', $type );
	
	return preg_replace( '/<html(.+?)>/', '<html$1 itemscope itemtype="' . esc_attr( $schema . $schema_type ) . '">', $output );
}
add_filter( 'language_attributes', 'aqualuxe_add_schema_markup' );

/**
 * Add Open Graph meta tags to the header.
 */
function aqualuxe_add_opengraph_tags() {
	global $post;
	
	if ( ! is_singular() ) {
		return;
	}
	
	$og_title = get_the_title();
	$og_description = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 55, '...' );
	$og_url = get_permalink();
	$og_site_name = get_bloginfo( 'name' );
	$og_image = has_post_thumbnail() ? get_the_post_thumbnail_url( get_the_ID(), 'large' ) : '';
	
	echo '<meta property="og:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
	echo '<meta property="og:description" content="' . esc_attr( $og_description ) . '" />' . "\n";
	echo '<meta property="og:url" content="' . esc_url( $og_url ) . '" />' . "\n";
	echo '<meta property="og:site_name" content="' . esc_attr( $og_site_name ) . '" />' . "\n";
	
	if ( is_singular( 'post' ) ) {
		echo '<meta property="og:type" content="article" />' . "\n";
		echo '<meta property="article:published_time" content="' . esc_attr( get_the_date( 'c' ) ) . '" />' . "\n";
		echo '<meta property="article:modified_time" content="' . esc_attr( get_the_modified_date( 'c' ) ) . '" />' . "\n";
	} else {
		echo '<meta property="og:type" content="website" />' . "\n";
	}
	
	if ( $og_image ) {
		echo '<meta property="og:image" content="' . esc_url( $og_image ) . '" />' . "\n";
		
		// Get image dimensions
		$image_id = get_post_thumbnail_id();
		if ( $image_id ) {
			$image_data = wp_get_attachment_image_src( $image_id, 'large' );
			if ( $image_data ) {
				echo '<meta property="og:image:width" content="' . esc_attr( $image_data[1] ) . '" />' . "\n";
				echo '<meta property="og:image:height" content="' . esc_attr( $image_data[2] ) . '" />' . "\n";
			}
		}
	}
	
	// Twitter Card tags
	echo '<meta name="twitter:card" content="summary_large_image" />' . "\n";
	echo '<meta name="twitter:title" content="' . esc_attr( $og_title ) . '" />' . "\n";
	echo '<meta name="twitter:description" content="' . esc_attr( $og_description ) . '" />' . "\n";
	
	if ( $og_image ) {
		echo '<meta name="twitter:image" content="' . esc_url( $og_image ) . '" />' . "\n";
	}
	
	// Twitter site username
	$twitter_username = get_theme_mod( 'aqualuxe_twitter_username', '' );
	if ( $twitter_username ) {
		echo '<meta name="twitter:site" content="@' . esc_attr( $twitter_username ) . '" />' . "\n";
	}
}
add_action( 'wp_head', 'aqualuxe_add_opengraph_tags' );

/**
 * Implement lazy loading for images.
 *
 * @param string $content The content to be filtered.
 * @return string The filtered content.
 */
function aqualuxe_lazy_load_images( $content ) {
	if ( is_admin() || is_feed() || is_preview() ) {
		return $content;
	}
	
	// Don't lazy load if the content already contains lazy-loaded images
	if ( strpos( $content, 'loading="lazy"' ) !== false ) {
		return $content;
	}
	
	// Replace <img> tags with lazy loading attribute
	$content = preg_replace_callback( '/<img([^>]+)>/i', function( $matches ) {
		// Skip if already has loading attribute
		if ( strpos( $matches[1], 'loading=' ) !== false ) {
			return $matches[0];
		}
		
		// Add loading="lazy" attribute
		return '<img' . $matches[1] . ' loading="lazy">';
	}, $content );
	
	return $content;
}
add_filter( 'the_content', 'aqualuxe_lazy_load_images', 99 );
add_filter( 'post_thumbnail_html', 'aqualuxe_lazy_load_images', 99 );
add_filter( 'widget_text_content', 'aqualuxe_lazy_load_images', 99 );

/**
 * Add dark mode script to footer.
 */
function aqualuxe_dark_mode_script() {
	?>
	<script>
	(function() {
		// Check for saved preference
		const darkModePreference = localStorage.getItem('aqualuxe-color-scheme');
		const prefersDarkMode = window.matchMedia('(prefers-color-scheme: dark)').matches;
		const defaultDarkMode = <?php echo get_theme_mod( 'aqualuxe_dark_mode_default', false ) ? 'true' : 'false'; ?>;
		
		// Set initial dark mode state
		if (darkModePreference === 'dark' || (darkModePreference === null && (prefersDarkMode || defaultDarkMode))) {
			document.documentElement.classList.add('dark');
		} else {
			document.documentElement.classList.remove('dark');
		}
	})();
	</script>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_dark_mode_script', 20 );

/**
 * Check if WooCommerce is active.
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Check if the current page is a WooCommerce page.
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_page() {
	if ( ! aqualuxe_is_woocommerce_active() ) {
		return false;
	}
	
	return is_woocommerce() || is_cart() || is_checkout() || is_account_page();
}