<?php
/**
 * Template Functions
 *
 * Functions used in templates and theme files
 *
 * @package AquaLuxe
 * @since 2.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Primary menu fallback
 */
function aqualuxe_primary_menu_fallback() {
	if ( current_user_can( 'manage_options' ) ) {
		echo '<ul class="primary-menu">';
		echo '<li><a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Add a menu', 'aqualuxe' ) . '</a></li>';
		echo '</ul>';
	}
}

/**
 * Output content wrapper start
 */
function aqualuxe_output_content_wrapper() {
	echo '<div class="content-wrapper">';
}
add_action( 'aqualuxe_before_main_content', 'aqualuxe_output_content_wrapper', 10 );

/**
 * Output content wrapper end
 */
function aqualuxe_output_content_wrapper_end() {
	echo '</div><!-- .content-wrapper -->';
}
add_action( 'aqualuxe_after_main_content', 'aqualuxe_output_content_wrapper_end', 10 );

/**
 * Get sidebar
 */
function aqualuxe_get_sidebar() {
	if ( aqualuxe_show_sidebar() ) {
		get_sidebar();
	}
}
add_action( 'aqualuxe_after_main_content', 'aqualuxe_get_sidebar', 20 );

/**
 * Check if sidebar should be shown
 */
function aqualuxe_show_sidebar() {
	// Don't show sidebar on WooCommerce pages
	if ( aqualuxe_is_woocommerce_page() ) {
		return false;
	}
	
	// Don't show sidebar on full-width pages
	if ( is_page_template( 'templates/pages/full-width.php' ) ) {
		return false;
	}
	
	// Check if sidebar has widgets
	return is_active_sidebar( 'sidebar-1' );
}

/**
 * Posts navigation
 */
function aqualuxe_posts_navigation() {
	the_posts_navigation( array(
		'prev_text' => aqualuxe_get_svg_icon( 'arrow-left' ) . '<span class="nav-text">' . esc_html__( 'Older posts', 'aqualuxe' ) . '</span>',
		'next_text' => '<span class="nav-text">' . esc_html__( 'Newer posts', 'aqualuxe' ) . '</span>' . aqualuxe_get_svg_icon( 'arrow-right' ),
	) );
}
add_action( 'aqualuxe_after_posts_loop', 'aqualuxe_posts_navigation', 10 );

/**
 * Header search form
 */
function aqualuxe_header_search() {
	if ( get_theme_mod( 'aqualuxe_show_search', true ) ) {
		?>
		<button class="search-toggle" aria-controls="search-form" aria-expanded="false" data-search-toggle>
			<?php echo aqualuxe_get_svg_icon( 'search' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
		</button>
		
		<div class="search-form-wrapper" data-search-form>
			<?php get_search_form(); ?>
		</div>
		<?php
	}
}
add_action( 'aqualuxe_header_actions', 'aqualuxe_header_search', 10 );

/**
 * Header cart (WooCommerce)
 */
function aqualuxe_header_cart() {
	if ( ! aqualuxe_is_woocommerce_active() ) {
		return;
	}
	
	if ( get_theme_mod( 'aqualuxe_show_cart', true ) ) {
		?>
		<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-link">
			<?php echo aqualuxe_get_svg_icon( 'cart' ); ?>
			<span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
			<span class="screen-reader-text"><?php esc_html_e( 'Shopping Cart', 'aqualuxe' ); ?></span>
		</a>
		<?php
	}
}
add_action( 'aqualuxe_header_actions', 'aqualuxe_header_cart', 20 );

/**
 * Header account link (WooCommerce)
 */
function aqualuxe_header_account() {
	if ( ! aqualuxe_is_woocommerce_active() ) {
		return;
	}
	
	if ( get_theme_mod( 'aqualuxe_show_account', true ) ) {
		?>
		<a href="<?php echo esc_url( get_permalink( get_option( 'woocommerce_myaccount_page_id' ) ) ); ?>" class="account-link">
			<?php echo aqualuxe_get_svg_icon( 'user' ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'My Account', 'aqualuxe' ); ?></span>
		</a>
		<?php
	}
}
add_action( 'aqualuxe_header_actions', 'aqualuxe_header_account', 30 );

/**
 * Header dark mode toggle
 */
function aqualuxe_header_dark_mode_toggle() {
	if ( get_theme_mod( 'aqualuxe_show_dark_mode_toggle', true ) ) {
		?>
		<button class="dark-mode-toggle" data-theme-toggle aria-label="<?php esc_attr_e( 'Toggle dark mode', 'aqualuxe' ); ?>">
			<span class="theme-icon" data-theme-icon>
				<?php echo aqualuxe_get_svg_icon( 'sun' ); ?>
			</span>
		</button>
		<?php
	}
}
add_action( 'aqualuxe_header_actions', 'aqualuxe_header_dark_mode_toggle', 40 );

/**
 * Output social links
 */
function aqualuxe_output_social_links() {
	$social_links = array(
		'facebook'  => get_theme_mod( 'aqualuxe_facebook_url', '' ),
		'twitter'   => get_theme_mod( 'aqualuxe_twitter_url', '' ),
		'instagram' => get_theme_mod( 'aqualuxe_instagram_url', '' ),
		'linkedin'  => get_theme_mod( 'aqualuxe_linkedin_url', '' ),
		'youtube'   => get_theme_mod( 'aqualuxe_youtube_url', '' ),
	);
	
	$social_links = array_filter( $social_links );
	
	if ( ! empty( $social_links ) ) {
		echo '<div class="social-links">';
		
		foreach ( $social_links as $platform => $url ) {
			printf(
				'<a href="%s" class="social-link social-link--%s" target="_blank" rel="noopener noreferrer" aria-label="%s">
					<span class="social-icon">%s</span>
				</a>',
				esc_url( $url ),
				esc_attr( $platform ),
				/* translators: %s: Social platform name */
				esc_attr( sprintf( __( 'Follow us on %s', 'aqualuxe' ), ucfirst( $platform ) ) ),
				aqualuxe_get_social_icon( $platform )
			);
		}
		
		echo '</div>';
	}
}
add_action( 'aqualuxe_footer_social', 'aqualuxe_output_social_links', 10 );

/**
 * Get social media icon
 */
function aqualuxe_get_social_icon( $platform ) {
	$icons = array(
		'facebook'  => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
		'twitter'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
		'instagram' => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>',
		'linkedin'  => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
		'youtube'   => '<svg viewBox="0 0 24 24" width="20" height="20" fill="currentColor"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
	);
	
	return isset( $icons[ $platform ] ) ? $icons[ $platform ] : '';
}

/**
 * Get current URL
 */
function aqualuxe_get_current_url() {
	global $wp;
	return home_url( add_query_arg( array(), $wp->request ) );
}

/**
 * Check if current page is blog-related
 */
function aqualuxe_is_blog() {
	return ( is_home() || is_archive() || is_author() || is_category() || is_tag() );
}

/**
 * Posted on date
 */
function aqualuxe_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf( $time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( DATE_W3C ) ),
		esc_html( get_the_modified_date() )
	);

	$posted_on = sprintf(
		/* translators: %s: post date. */
		esc_html_x( 'Posted on %s', 'post date', 'aqualuxe' ),
		'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
	);

	echo '<span class="posted-on">' . $posted_on . '</span>'; // WPCS: XSS OK.
}

/**
 * Posted by author
 */
function aqualuxe_posted_by() {
	$byline = sprintf(
		/* translators: %s: post author. */
		esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline"> ' . $byline . '</span>'; // WPCS: XSS OK.
}

/**
 * Entry footer
 */
function aqualuxe_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
		if ( $categories_list ) {
			/* translators: 1: list of categories. */
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // WPCS: XSS OK.
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
		if ( $tags_list ) {
			/* translators: 1: list of tags. */
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // WPCS: XSS OK.
		}
	}

	if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		echo '<span class="comments-link">';
		comments_popup_link(
			sprintf(
				wp_kses(
					/* translators: %s: post title */
					__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			)
		);
		echo '</span>';
	}

	edit_post_link(
		sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		),
		'<span class="edit-link">',
		'</span>'
	);
}