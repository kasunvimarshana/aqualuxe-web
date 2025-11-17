<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Displays the site logo, either using the WordPress custom logo feature, or a text fallback
 */
function aqualuxe_site_logo() {
	$logo_id = get_theme_mod( 'custom_logo' );
	$logo = wp_get_attachment_image_src( $logo_id, 'full' );
	
	if ( $logo ) {
		echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link" rel="home">';
		echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="custom-logo" width="' . esc_attr( $logo[1] ) . '" height="' . esc_attr( $logo[2] ) . '">';
		echo '</a>';
	} else {
		echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-title" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
		
		$description = get_bloginfo( 'description', 'display' );
		if ( $description || is_customize_preview() ) {
			echo '<p class="site-description">' . esc_html( $description ) . '</p>';
		}
	}
}

/**
 * Prints HTML with meta information for the current post-date/time.
 */
function aqualuxe_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}

	$time_string = sprintf(
		$time_string,
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

	echo '<span class="posted-on">' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the current author.
 */
function aqualuxe_posted_by() {
	$byline = sprintf(
		/* translators: %s: post author. */
		esc_html_x( 'by %s', 'post author', 'aqualuxe' ),
		'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
	);

	echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Prints HTML with meta information for the categories, tags and comments.
 */
function aqualuxe_entry_footer() {
	// Hide category and tag text for pages.
	if ( 'post' === get_post_type() ) {
		/* translators: used between list items, there is a space after the comma */
		$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
		if ( $categories_list ) {
			/* translators: 1: list of categories. */
			printf( '<span class="cat-links">' . esc_html__( 'Posted in %1$s', 'aqualuxe' ) . '</span>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		/* translators: used between list items, there is a space after the comma */
		$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
		if ( $tags_list ) {
			/* translators: 1: list of tags. */
			printf( '<span class="tags-links">' . esc_html__( 'Tagged %1$s', 'aqualuxe' ) . '</span>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
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
				wp_kses_post( get_the_title() )
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
			wp_kses_post( get_the_title() )
		),
		'<span class="edit-link">',
		'</span>'
	);
}

/**
 * Displays an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index views, or a div
 * element when on single views.
 */
function aqualuxe_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
		?>
		<div class="post-thumbnail">
			<?php the_post_thumbnail( 'aqualuxe-featured', array( 'class' => 'rounded-lg shadow-lg' ) ); ?>
		</div><!-- .post-thumbnail -->
	<?php else : ?>
		<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail(
				'post-thumbnail',
				array(
					'alt' => the_title_attribute(
						array(
							'echo' => false,
						)
					),
					'class' => 'rounded-lg shadow hover:shadow-xl transition-shadow duration-300',
				)
			);
			?>
		</a>
		<?php
	endif; // End is_singular().
}

/**
 * Displays the dark mode toggle button
 */
function aqualuxe_dark_mode_toggle() {
	$current_mode = aqualuxe_is_dark_mode() ? 'dark' : 'light';
	$toggle_text = aqualuxe_is_dark_mode() ? esc_html__( 'Light Mode', 'aqualuxe' ) : esc_html__( 'Dark Mode', 'aqualuxe' );
	$toggle_icon = aqualuxe_is_dark_mode() ? 'sun' : 'moon';
	
	echo '<button id="dark-mode-toggle" class="dark-mode-toggle" data-current-mode="' . esc_attr( $current_mode ) . '">';
	echo '<span class="screen-reader-text">' . esc_html( $toggle_text ) . '</span>';
	echo '<svg class="icon icon-' . esc_attr( $toggle_icon ) . '" aria-hidden="true" focusable="false">';
	echo '<use xlink:href="#icon-' . esc_attr( $toggle_icon ) . '"></use>';
	echo '</svg>';
	echo '</button>';
}

/**
 * Displays the language switcher if site is multilingual
 */
function aqualuxe_language_switcher() {
	if ( ! aqualuxe_is_multilingual() ) {
		return;
	}
	
	// WPML
	if ( defined( 'ICL_SITEPRESS_VERSION' ) && function_exists( 'icl_get_languages' ) ) {
		$languages = icl_get_languages( 'skip_missing=0' );
		
		if ( ! empty( $languages ) ) {
			echo '<div class="language-switcher">';
			echo '<ul>';
			
			foreach ( $languages as $lang ) {
				$class = $lang['active'] ? 'active' : '';
				echo '<li class="' . esc_attr( $class ) . '">';
				echo '<a href="' . esc_url( $lang['url'] ) . '">';
				
				if ( $lang['country_flag_url'] ) {
					echo '<img src="' . esc_url( $lang['country_flag_url'] ) . '" alt="' . esc_attr( $lang['language_code'] ) . '" width="18" height="12">';
				}
				
				echo esc_html( $lang['native_name'] );
				echo '</a>';
				echo '</li>';
			}
			
			echo '</ul>';
			echo '</div>';
		}
	}
	
	// Polylang
	if ( function_exists( 'pll_the_languages' ) ) {
		echo '<div class="language-switcher">';
		pll_the_languages( array( 'show_flags' => 1, 'show_names' => 1 ) );
		echo '</div>';
	}
}

/**
 * Displays breadcrumbs
 */
function aqualuxe_breadcrumbs() {
	// Check if breadcrumbs should be displayed
	if ( ! get_theme_mod( 'aqualuxe_display_breadcrumbs', true ) ) {
		return;
	}
	
	// Use WooCommerce breadcrumbs if available and on WooCommerce pages
	if ( aqualuxe_is_woocommerce_active() && ( is_woocommerce() || is_cart() || is_checkout() || is_account_page() ) ) {
		woocommerce_breadcrumb();
		return;
	}
	
	// Custom breadcrumbs for other pages
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumb', 'aqualuxe' ) . '">';
	echo '<ol>';
	
	// Home
	echo '<li>';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'aqualuxe' ) . '</a>';
	echo '</li>';
	
	if ( is_category() || is_single() ) {
		// Category
		if ( is_single() ) {
			$categories = get_the_category();
			if ( ! empty( $categories ) ) {
				echo '<li>';
				echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
				echo '</li>';
			}
		} else {
			echo '<li>';
			echo esc_html( single_cat_title( '', false ) );
			echo '</li>';
		}
	}
	
	// Current page
	if ( is_single() || is_page() ) {
		echo '<li>';
		echo esc_html( get_the_title() );
		echo '</li>';
	} elseif ( is_tag() ) {
		echo '<li>';
		echo esc_html( single_tag_title( '', false ) );
		echo '</li>';
	} elseif ( is_author() ) {
		echo '<li>';
		echo esc_html( get_the_author() );
		echo '</li>';
	} elseif ( is_year() ) {
		echo '<li>';
		echo esc_html( get_the_date( 'Y' ) );
		echo '</li>';
	} elseif ( is_month() ) {
		echo '<li>';
		echo esc_html( get_the_date( 'F Y' ) );
		echo '</li>';
	} elseif ( is_day() ) {
		echo '<li>';
		echo esc_html( get_the_date() );
		echo '</li>';
	} elseif ( is_search() ) {
		echo '<li>';
		echo esc_html__( 'Search Results', 'aqualuxe' );
		echo '</li>';
	} elseif ( is_404() ) {
		echo '<li>';
		echo esc_html__( 'Page Not Found', 'aqualuxe' );
		echo '</li>';
	}
	
	echo '</ol>';
	echo '</nav>';
}

/**
 * Displays social sharing buttons
 */
function aqualuxe_social_sharing() {
	// Check if social sharing should be displayed
	if ( ! get_theme_mod( 'aqualuxe_display_social_sharing', true ) ) {
		return;
	}
	
	$post_url = urlencode( get_permalink() );
	$post_title = urlencode( get_the_title() );
	
	echo '<div class="social-sharing">';
	echo '<span class="share-title">' . esc_html__( 'Share:', 'aqualuxe' ) . '</span>';
	
	// Facebook
	echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_attr( $post_url ) . '" target="_blank" rel="noopener noreferrer" class="share-facebook">';
	echo '<span class="screen-reader-text">' . esc_html__( 'Share on Facebook', 'aqualuxe' ) . '</span>';
	echo '<svg class="icon icon-facebook" aria-hidden="true" focusable="false"><use xlink:href="#icon-facebook"></use></svg>';
	echo '</a>';
	
	// Twitter
	echo '<a href="https://twitter.com/intent/tweet?url=' . esc_attr( $post_url ) . '&text=' . esc_attr( $post_title ) . '" target="_blank" rel="noopener noreferrer" class="share-twitter">';
	echo '<span class="screen-reader-text">' . esc_html__( 'Share on Twitter', 'aqualuxe' ) . '</span>';
	echo '<svg class="icon icon-twitter" aria-hidden="true" focusable="false"><use xlink:href="#icon-twitter"></use></svg>';
	echo '</a>';
	
	// LinkedIn
	echo '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' . esc_attr( $post_url ) . '&title=' . esc_attr( $post_title ) . '" target="_blank" rel="noopener noreferrer" class="share-linkedin">';
	echo '<span class="screen-reader-text">' . esc_html__( 'Share on LinkedIn', 'aqualuxe' ) . '</span>';
	echo '<svg class="icon icon-linkedin" aria-hidden="true" focusable="false"><use xlink:href="#icon-linkedin"></use></svg>';
	echo '</a>';
	
	// Pinterest (only if featured image exists)
	if ( has_post_thumbnail() ) {
		$post_thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );
		$post_thumbnail_url = urlencode( $post_thumbnail[0] );
		
		echo '<a href="https://pinterest.com/pin/create/button/?url=' . esc_attr( $post_url ) . '&media=' . esc_attr( $post_thumbnail_url ) . '&description=' . esc_attr( $post_title ) . '" target="_blank" rel="noopener noreferrer" class="share-pinterest">';
		echo '<span class="screen-reader-text">' . esc_html__( 'Share on Pinterest', 'aqualuxe' ) . '</span>';
		echo '<svg class="icon icon-pinterest" aria-hidden="true" focusable="false"><use xlink:href="#icon-pinterest"></use></svg>';
		echo '</a>';
	}
	
	// Email
	echo '<a href="mailto:?subject=' . esc_attr( $post_title ) . '&body=' . esc_attr( $post_url ) . '" class="share-email">';
	echo '<span class="screen-reader-text">' . esc_html__( 'Share via Email', 'aqualuxe' ) . '</span>';
	echo '<svg class="icon icon-email" aria-hidden="true" focusable="false"><use xlink:href="#icon-email"></use></svg>';
	echo '</a>';
	
	echo '</div>';
}

/**
 * Displays related posts
 */
function aqualuxe_related_posts() {
	global $post;
	
	// Check if related posts should be displayed
	if ( ! get_theme_mod( 'aqualuxe_display_related_posts', true ) ) {
		return;
	}
	
	// Get categories
	$categories = get_the_category( $post->ID );
	
	if ( $categories ) {
		$category_ids = array();
		
		foreach ( $categories as $category ) {
			$category_ids[] = $category->term_id;
		}
		
		$args = array(
			'category__in'        => $category_ids,
			'post__not_in'        => array( $post->ID ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => 1,
		);
		
		$related_query = new WP_Query( $args );
		
		if ( $related_query->have_posts() ) {
			echo '<div class="related-posts">';
			echo '<h3>' . esc_html__( 'Related Posts', 'aqualuxe' ) . '</h3>';
			echo '<div class="related-posts-grid">';
			
			while ( $related_query->have_posts() ) {
				$related_query->the_post();
				
				echo '<article class="related-post">';
				
				if ( has_post_thumbnail() ) {
					echo '<a href="' . esc_url( get_permalink() ) . '" class="related-post-thumbnail">';
					the_post_thumbnail( 'medium', array( 'class' => 'rounded' ) );
					echo '</a>';
				}
				
				echo '<h4 class="related-post-title"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h4>';
				echo '<div class="related-post-meta">' . esc_html( get_the_date() ) . '</div>';
				echo '</article>';
			}
			
			echo '</div>';
			echo '</div>';
			
			wp_reset_postdata();
		}
	}
}

/**
 * Displays the newsletter signup form
 */
function aqualuxe_newsletter_signup() {
	// Check if newsletter signup should be displayed
	if ( ! get_theme_mod( 'aqualuxe_display_newsletter', true ) ) {
		return;
	}
	
	$newsletter_title = get_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) );
	$newsletter_text = get_theme_mod( 'aqualuxe_newsletter_text', __( 'Stay updated with our latest products and news.', 'aqualuxe' ) );
	$newsletter_form = get_theme_mod( 'aqualuxe_newsletter_form', '' );
	
	echo '<div class="newsletter-signup">';
	echo '<div class="newsletter-content">';
	
	if ( $newsletter_title ) {
		echo '<h3 class="newsletter-title">' . esc_html( $newsletter_title ) . '</h3>';
	}
	
	if ( $newsletter_text ) {
		echo '<p class="newsletter-text">' . esc_html( $newsletter_text ) . '</p>';
	}
	
	echo '</div>';
	
	echo '<div class="newsletter-form">';
	
	if ( $newsletter_form ) {
		echo do_shortcode( $newsletter_form );
	} else {
		// Default form
		echo '<form action="#" method="post" class="newsletter-default-form">';
		echo '<div class="form-group">';
		echo '<input type="email" name="email" placeholder="' . esc_attr__( 'Your email address', 'aqualuxe' ) . '" required>';
		echo '<button type="submit">' . esc_html__( 'Subscribe', 'aqualuxe' ) . '</button>';
		echo '</div>';
		echo '<p class="newsletter-privacy">' . esc_html__( 'By subscribing, you agree to our Privacy Policy.', 'aqualuxe' ) . '</p>';
		echo '</form>';
	}
	
	echo '</div>';
	echo '</div>';
}

/**
 * Displays the currency switcher if WooCommerce is active
 */
function aqualuxe_currency_switcher() {
	// Check if WooCommerce is active
	if ( ! aqualuxe_is_woocommerce_active() ) {
		return;
	}
	
	// Check if WOOCS (WooCommerce Currency Switcher) is active
	if ( class_exists( 'WOOCS' ) ) {
		echo do_shortcode( '[woocs]' );
		return;
	}
	
	// Check if WCML (WooCommerce Multilingual) is active
	if ( class_exists( 'woocommerce_wpml' ) ) {
		do_action( 'wcml_currency_switcher', array( 'format' => '%code%' ) );
		return;
	}
	
	// Default currency display if no switcher plugin is active
	echo '<div class="currency-display">';
	echo esc_html( get_woocommerce_currency() );
	echo '</div>';
}