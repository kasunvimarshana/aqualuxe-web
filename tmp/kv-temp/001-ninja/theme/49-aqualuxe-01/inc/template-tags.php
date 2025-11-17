<?php
/**
 * Custom template tags for this theme
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
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

	aqualuxe_before_post_thumbnail();

	if ( is_singular() ) :
		?>
		<div class="post-thumbnail mb-6">
			<?php the_post_thumbnail( 'full', array( 'class' => 'rounded-lg w-full h-auto' ) ); ?>
		</div><!-- .post-thumbnail -->
	<?php else : ?>
		<a class="post-thumbnail block mb-4 overflow-hidden rounded-lg" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php
			the_post_thumbnail(
				'post-thumbnail',
				array(
					'class' => 'w-full h-auto transition-transform duration-300 hover:scale-105',
					'alt'   => the_title_attribute(
						array(
							'echo' => false,
						)
					),
				)
			);
			?>
		</a>
		<?php
	endif; // End is_singular().

	aqualuxe_after_post_thumbnail();
}

/**
 * Prints HTML with meta information for post categories.
 */
function aqualuxe_post_categories() {
	// Hide category text for pages.
	if ( 'post' !== get_post_type() ) {
		return;
	}

	aqualuxe_before_post_categories();

	/* translators: used between list items, there is a space after the comma */
	$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
	if ( $categories_list ) {
		echo '<div class="post-categories mb-2">' . $categories_list . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	aqualuxe_after_post_categories();
}

/**
 * Prints HTML with meta information for post tags.
 */
function aqualuxe_post_tags() {
	// Hide tag text for pages.
	if ( 'post' !== get_post_type() ) {
		return;
	}

	aqualuxe_before_post_tags();

	/* translators: used between list items, there is a space after the comma */
	$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
	if ( $tags_list ) {
		echo '<div class="post-tags mt-6">';
		echo '<h3 class="post-tags-title text-lg font-semibold mb-2">' . esc_html__( 'Tags:', 'aqualuxe' ) . '</h3>';
		echo '<div class="post-tags-list flex flex-wrap gap-2">';
		
		$tags = get_the_tags();
		if ( $tags ) {
			foreach ( $tags as $tag ) {
				echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="inline-block px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-sm rounded-md hover:bg-gray-200 dark:hover:bg-gray-600 transition duration-200">' . esc_html( $tag->name ) . '</a>';
			}
		}
		
		echo '</div>';
		echo '</div>';
	}

	aqualuxe_after_post_tags();
}

/**
 * Prints HTML with meta information for the current author with avatar.
 */
function aqualuxe_post_author() {
	aqualuxe_before_post_author();

	$author_id = get_the_author_meta( 'ID' );
	$author_name = get_the_author_meta( 'display_name', $author_id );
	$author_url = get_author_posts_url( $author_id );
	
	echo '<div class="post-author flex items-center">';
	echo '<a href="' . esc_url( $author_url ) . '" class="post-author-avatar mr-2">';
	echo get_avatar( $author_id, 40, '', $author_name, array( 'class' => 'rounded-full' ) );
	echo '</a>';
	echo '<div class="post-author-name">';
	echo '<a href="' . esc_url( $author_url ) . '" class="font-medium text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">' . esc_html( $author_name ) . '</a>';
	echo '</div>';
	echo '</div>';

	aqualuxe_after_post_author();
}

/**
 * Prints HTML with meta information for the post date.
 */
function aqualuxe_post_date() {
	aqualuxe_before_post_date();

	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
	}

	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( DATE_W3C ) ),
		esc_html( get_the_date() )
	);

	echo '<div class="post-date text-gray-600 dark:text-gray-400">';
	echo '<a href="' . esc_url( get_permalink() ) . '" class="hover:text-primary-600 dark:hover:text-primary-400">' . $time_string . '</a>';
	echo '</div>';

	aqualuxe_after_post_date();
}

/**
 * Prints HTML with meta information for the post comments count.
 */
function aqualuxe_post_comments_count() {
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
		aqualuxe_before_post_comments_count();

		echo '<div class="post-comments-count text-gray-600 dark:text-gray-400">';
		
		$comments_number = get_comments_number();
		if ( '1' === $comments_number ) {
			/* translators: %s: post title */
			printf(
				'<a href="%1$s" class="hover:text-primary-600 dark:hover:text-primary-400">%2$s</a>',
				esc_url( get_comments_link() ),
				esc_html__( '1 Comment', 'aqualuxe' )
			);
		} else {
			printf(
				'<a href="%1$s" class="hover:text-primary-600 dark:hover:text-primary-400">%2$s</a>',
				esc_url( get_comments_link() ),
				sprintf(
					/* translators: %s: comment count number */
					esc_html( _nx( '%s Comment', '%s Comments', $comments_number, 'comments title', 'aqualuxe' ) ),
					esc_html( number_format_i18n( $comments_number ) )
				)
			);
		}
		
		echo '</div>';

		aqualuxe_after_post_comments_count();
	}
}

/**
 * Prints HTML with post meta information (author, date, comments).
 */
function aqualuxe_post_meta() {
	aqualuxe_before_post_meta();

	echo '<div class="post-meta flex flex-wrap items-center gap-4 text-sm mb-4">';
	
	// Author
	aqualuxe_post_author();
	
	// Date
	aqualuxe_post_date();
	
	// Comments count
	aqualuxe_post_comments_count();
	
	echo '</div>';

	aqualuxe_after_post_meta();
}

/**
 * Prints HTML with the post excerpt.
 */
function aqualuxe_post_excerpt() {
	aqualuxe_before_post_excerpt();

	echo '<div class="post-excerpt text-gray-700 dark:text-gray-300 mb-4">';
	the_excerpt();
	echo '</div>';

	aqualuxe_after_post_excerpt();
}

/**
 * Prints HTML with the read more link.
 */
function aqualuxe_post_read_more() {
	aqualuxe_before_post_read_more();

	echo '<div class="post-read-more">';
	echo '<a href="' . esc_url( get_permalink() ) . '" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition duration-200">';
	echo esc_html__( 'Read More', 'aqualuxe' );
	echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
	echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />';
	echo '</svg>';
	echo '</a>';
	echo '</div>';

	aqualuxe_after_post_read_more();
}

/**
 * Prints HTML with the post title.
 */
function aqualuxe_post_title() {
	aqualuxe_before_post_title();

	if ( is_singular() ) :
		the_title( '<h1 class="entry-title text-3xl md:text-4xl font-bold mb-4">', '</h1>' );
	else :
		the_title( '<h2 class="entry-title text-xl md:text-2xl font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">', '</a></h2>' );
	endif;

	aqualuxe_after_post_title();
}

/**
 * Displays the site logo or site title.
 */
function aqualuxe_site_logo() {
	aqualuxe_before_site_branding();

	if ( has_custom_logo() ) :
		the_custom_logo();
	else :
		aqualuxe_before_site_title();
		?>
		<h1 class="site-title text-xl font-bold">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">
				<?php bloginfo( 'name' ); ?>
			</a>
		</h1>
		<?php
		aqualuxe_after_site_title();

		$aqualuxe_description = get_bloginfo( 'description', 'display' );
		if ( $aqualuxe_description || is_customize_preview() ) :
			aqualuxe_before_site_description();
			?>
			<p class="site-description text-sm text-gray-600 dark:text-gray-400">
				<?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</p>
			<?php
			aqualuxe_after_site_description();
		endif;
	endif;

	aqualuxe_after_site_branding();
}

/**
 * Displays the dark mode toggle button.
 */
function aqualuxe_dark_mode_toggle() {
	if ( ! get_theme_mod( 'aqualuxe_dark_mode_toggle', true ) ) {
		return;
	}

	aqualuxe_before_dark_mode_toggle();
	?>
	<button id="dark-mode-toggle" class="dark-mode-toggle p-2" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
		</svg>
		<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
			<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
		</svg>
	</button>
	<?php
	aqualuxe_after_dark_mode_toggle();
}

/**
 * Displays the language switcher.
 */
function aqualuxe_language_switcher() {
	if ( ! function_exists( 'pll_the_languages' ) && ! function_exists( 'icl_get_languages' ) ) {
		return;
	}

	aqualuxe_before_language_switcher();

	echo '<div class="language-switcher relative inline-block text-left">';
	echo '<button type="button" class="language-switcher-toggle inline-flex items-center text-sm text-gray-200 hover:text-white" id="language-menu-button" aria-expanded="false" aria-haspopup="true">';
	
	// Current language
	if ( function_exists( 'pll_current_language' ) ) {
		echo esc_html( strtoupper( pll_current_language( 'slug' ) ) );
	} elseif ( function_exists( 'icl_get_languages' ) ) {
		$languages = icl_get_languages( 'skip_missing=0' );
		foreach ( $languages as $language ) {
			if ( $language['active'] ) {
				echo esc_html( strtoupper( $language['code'] ) );
				break;
			}
		}
	}
	
	echo '<svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">';
	echo '<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />';
	echo '</svg>';
	echo '</button>';
	
	echo '<div class="language-switcher-dropdown hidden absolute right-0 mt-2 w-24 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" tabindex="-1">';
	echo '<div class="py-1" role="none">';
	
	// Language list
	if ( function_exists( 'pll_the_languages' ) ) {
		$languages = pll_the_languages( array( 'raw' => 1 ) );
		foreach ( $languages as $language ) {
			$active_class = $language['current_lang'] ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700';
			echo '<a href="' . esc_url( $language['url'] ) . '" class="block px-4 py-2 text-sm ' . esc_attr( $active_class ) . '" role="menuitem" tabindex="-1">' . esc_html( $language['name'] ) . '</a>';
		}
	} elseif ( function_exists( 'icl_get_languages' ) ) {
		$languages = icl_get_languages( 'skip_missing=0' );
		foreach ( $languages as $language ) {
			$active_class = $language['active'] ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700';
			echo '<a href="' . esc_url( $language['url'] ) . '" class="block px-4 py-2 text-sm ' . esc_attr( $active_class ) . '" role="menuitem" tabindex="-1">' . esc_html( $language['native_name'] ) . '</a>';
		}
	}
	
	echo '</div>';
	echo '</div>';
	echo '</div>';

	aqualuxe_after_language_switcher();
}

/**
 * Displays the currency switcher.
 */
function aqualuxe_currency_switcher() {
	if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'get_woocommerce_currencies' ) ) {
		return;
	}

	// Check if WPML or Polylang with WooCommerce Multilingual is active
	$has_wcml = class_exists( 'woocommerce_wpml' ) || class_exists( 'Polylang_Woocommerce' );
	
	if ( ! $has_wcml ) {
		return;
	}

	aqualuxe_before_currency_switcher();

	echo '<div class="currency-switcher relative inline-block text-left">';
	echo '<button type="button" class="currency-switcher-toggle inline-flex items-center text-sm text-gray-200 hover:text-white" id="currency-menu-button" aria-expanded="false" aria-haspopup="true">';
	
	// Current currency
	if ( function_exists( 'wcml_get_woocommerce_currencies_info' ) ) {
		$currencies_info = wcml_get_woocommerce_currencies_info();
		$current_currency = wcml_get_client_currency();
		echo esc_html( $current_currency );
	} else {
		echo esc_html( get_woocommerce_currency() );
	}
	
	echo '<svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">';
	echo '<path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />';
	echo '</svg>';
	echo '</button>';
	
	echo '<div class="currency-switcher-dropdown hidden absolute right-0 mt-2 w-24 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 z-10" role="menu" aria-orientation="vertical" aria-labelledby="currency-menu-button" tabindex="-1">';
	echo '<div class="py-1" role="none">';
	
	// Currency list
	if ( function_exists( 'wcml_get_woocommerce_currencies_info' ) ) {
		$currencies_info = wcml_get_woocommerce_currencies_info();
		$current_currency = wcml_get_client_currency();
		
		foreach ( $currencies_info as $currency => $info ) {
			$active_class = $currency === $current_currency ? 'bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700';
			echo '<a href="' . esc_url( add_query_arg( 'currency', $currency ) ) . '" class="block px-4 py-2 text-sm ' . esc_attr( $active_class ) . '" role="menuitem" tabindex="-1">' . esc_html( $currency ) . '</a>';
		}
	}
	
	echo '</div>';
	echo '</div>';
	echo '</div>';

	aqualuxe_after_currency_switcher();
}

/**
 * Displays the newsletter form.
 */
function aqualuxe_newsletter_form() {
	if ( ! get_theme_mod( 'aqualuxe_newsletter_enable', true ) ) {
		return;
	}

	aqualuxe_before_newsletter_form();
	?>
	<div class="newsletter-form py-12 bg-primary-600 text-white">
		<div class="container mx-auto px-4">
			<div class="max-w-3xl mx-auto text-center">
				<h3 class="newsletter-title text-2xl font-bold mb-4">
					<?php echo esc_html( get_theme_mod( 'aqualuxe_newsletter_title', __( 'Subscribe to Our Newsletter', 'aqualuxe' ) ) ); ?>
				</h3>
				
				<p class="newsletter-description mb-6">
					<?php echo esc_html( get_theme_mod( 'aqualuxe_newsletter_description', __( 'Stay updated with our latest news and offers.', 'aqualuxe' ) ) ); ?>
				</p>
				
				<form class="newsletter-form-fields flex flex-col md:flex-row gap-4 justify-center">
					<input type="email" class="newsletter-email flex-grow px-4 py-3 rounded-md text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="<?php esc_attr_e( 'Your Email Address', 'aqualuxe' ); ?>" required>
					<button type="submit" class="newsletter-submit px-6 py-3 bg-white text-primary-600 hover:bg-gray-100 rounded-md font-medium transition duration-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-primary-600">
						<?php echo esc_html( get_theme_mod( 'aqualuxe_newsletter_button_text', __( 'Subscribe', 'aqualuxe' ) ) ); ?>
					</button>
				</form>
				
				<?php if ( get_theme_mod( 'aqualuxe_newsletter_privacy_text', true ) ) : ?>
					<p class="newsletter-privacy-text text-sm mt-4 text-white text-opacity-80">
						<?php echo wp_kses_post( get_theme_mod( 'aqualuxe_newsletter_privacy_text', __( 'By subscribing, you agree to our Privacy Policy and consent to receive marketing emails.', 'aqualuxe' ) ) ); ?>
					</p>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
	aqualuxe_after_newsletter_form();
}

/**
 * Displays social icons.
 */
function aqualuxe_social_icons() {
	$social_links = array(
		'facebook'  => get_theme_mod( 'aqualuxe_facebook_url', '' ),
		'twitter'   => get_theme_mod( 'aqualuxe_twitter_url', '' ),
		'instagram' => get_theme_mod( 'aqualuxe_instagram_url', '' ),
		'linkedin'  => get_theme_mod( 'aqualuxe_linkedin_url', '' ),
		'youtube'   => get_theme_mod( 'aqualuxe_youtube_url', '' ),
		'pinterest' => get_theme_mod( 'aqualuxe_pinterest_url', '' ),
	);
	
	$has_links = false;
	foreach ( $social_links as $link ) {
		if ( ! empty( $link ) ) {
			$has_links = true;
			break;
		}
	}
	
	if ( ! $has_links ) {
		return;
	}
	
	aqualuxe_before_footer_social_icons();
	?>
	<div class="social-icons flex items-center space-x-4">
		<?php if ( ! empty( $social_links['facebook'] ) ) : ?>
			<a href="<?php echo esc_url( $social_links['facebook'] ); ?>" class="social-icon text-gray-400 hover:text-white transition duration-200" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'aqualuxe' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" />
				</svg>
			</a>
		<?php endif; ?>
		
		<?php if ( ! empty( $social_links['twitter'] ) ) : ?>
			<a href="<?php echo esc_url( $social_links['twitter'] ); ?>" class="social-icon text-gray-400 hover:text-white transition duration-200" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Twitter', 'aqualuxe' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
				</svg>
			</a>
		<?php endif; ?>
		
		<?php if ( ! empty( $social_links['instagram'] ) ) : ?>
			<a href="<?php echo esc_url( $social_links['instagram'] ); ?>" class="social-icon text-gray-400 hover:text-white transition duration-200" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'aqualuxe' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" />
				</svg>
			</a>
		<?php endif; ?>
		
		<?php if ( ! empty( $social_links['linkedin'] ) ) : ?>
			<a href="<?php echo esc_url( $social_links['linkedin'] ); ?>" class="social-icon text-gray-400 hover:text-white transition duration-200" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'aqualuxe' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z" />
				</svg>
			</a>
		<?php endif; ?>
		
		<?php if ( ! empty( $social_links['youtube'] ) ) : ?>
			<a href="<?php echo esc_url( $social_links['youtube'] ); ?>" class="social-icon text-gray-400 hover:text-white transition duration-200" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'YouTube', 'aqualuxe' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
				</svg>
			</a>
		<?php endif; ?>
		
		<?php if ( ! empty( $social_links['pinterest'] ) ) : ?>
			<a href="<?php echo esc_url( $social_links['pinterest'] ); ?>" class="social-icon text-gray-400 hover:text-white transition duration-200" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Pinterest', 'aqualuxe' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
					<path d="M12 0C5.373 0 0 5.372 0 12c0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24 12 24c6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" />
				</svg>
			</a>
		<?php endif; ?>
	</div>
	<?php
	aqualuxe_after_footer_social_icons();
}

/**
 * Displays payment icons.
 */
function aqualuxe_payment_icons() {
	if ( ! get_theme_mod( 'aqualuxe_payment_icons_enable', true ) ) {
		return;
	}

	aqualuxe_before_footer_payment_icons();
	?>
	<div class="payment-icons flex flex-wrap items-center gap-3 mt-4">
		<?php if ( get_theme_mod( 'aqualuxe_payment_icon_visa', true ) ) : ?>
			<div class="payment-icon visa">
				<svg xmlns="http://www.w3.org/2000/svg" width="40" height="25" viewBox="0 0 40 25" fill="none" role="img" aria-labelledby="visa-icon">
					<title id="visa-icon"><?php esc_html_e( 'Visa', 'aqualuxe' ); ?></title>
					<rect width="40" height="25" rx="4" fill="#F3F4F6"/>
					<path d="M15.4 16.5H13.1L14.6 8.5H16.9L15.4 16.5Z" fill="#00579F"/>
					<path d="M23.3 8.7C22.8 8.5 22 8.3 21 8.3C18.7 8.3 17.1 9.5 17.1 11.2C17.1 12.5 18.3 13.2 19.2 13.6C20.1 14 20.4 14.3 20.4 14.6C20.4 15.1 19.8 15.3 19.2 15.3C18.4 15.3 17.9 15.2 17.2 14.9L16.9 14.8L16.6 16.8C17.2 17.1 18.2 17.3 19.3 17.3C21.8 17.3 23.3 16.1 23.3 14.3C23.3 13.3 22.7 12.5 21.4 11.9C20.6 11.5 20.1 11.2 20.1 10.8C20.1 10.5 20.5 10.1 21.3 10.1C22 10.1 22.5 10.2 22.9 10.4L23.1 10.5L23.3 8.7Z" fill="#00579F"/>
					<path d="M26.7 8.5H25C24.5 8.5 24.1 8.6 23.9 9.1L20.8 16.5H23.3L23.7 15.3H26.6L26.8 16.5H29V16.4L26.7 8.5ZM24.3 13.6C24.3 13.6 25 11.7 25.2 11.2C25.2 11.2 25.4 10.7 25.5 10.4L25.6 11.1C25.6 11.1 26 13 26.1 13.6H24.3Z" fill="#00579F"/>
					<path d="M12.4 8.5L10.1 14L9.8 12.7C9.4 11.4 8.1 10 6.7 9.3L8.8 16.5H11.3L15 8.5H12.4Z" fill="#00579F"/>
					<path d="M8.1 8.5H4.2L4.1 8.7C7 9.4 8.9 11 9.8 12.7L9 9.1C8.9 8.7 8.6 8.5 8.1 8.5Z" fill="#FAA61A"/>
				</svg>
			</div>
		<?php endif; ?>
		
		<?php if ( get_theme_mod( 'aqualuxe_payment_icon_mastercard', true ) ) : ?>
			<div class="payment-icon mastercard">
				<svg xmlns="http://www.w3.org/2000/svg" width="40" height="25" viewBox="0 0 40 25" fill="none" role="img" aria-labelledby="mastercard-icon">
					<title id="mastercard-icon"><?php esc_html_e( 'Mastercard', 'aqualuxe' ); ?></title>
					<rect width="40" height="25" rx="4" fill="#F3F4F6"/>
					<path d="M25.5 7.5H14.5V17.5H25.5V7.5Z" fill="#FF5F00"/>
					<path d="M15.3 12.5C15.3 10.4 16.3 8.6 17.8 7.5C16.8 6.7 15.5 6.2 14.1 6.2C10.8 6.2 8 9 8 12.5C8 16 10.8 18.8 14.1 18.8C15.5 18.8 16.8 18.3 17.8 17.5C16.3 16.4 15.3 14.6 15.3 12.5Z" fill="#EB001B"/>
					<path d="M32 12.5C32 16 29.2 18.8 25.9 18.8C24.5 18.8 23.2 18.3 22.2 17.5C23.7 16.4 24.7 14.6 24.7 12.5C24.7 10.4 23.7 8.6 22.2 7.5C23.2 6.7 24.5 6.2 25.9 6.2C29.2 6.2 32 9 32 12.5Z" fill="#F79E1B"/>
				</svg>
			</div>
		<?php endif; ?>
		
		<?php if ( get_theme_mod( 'aqualuxe_payment_icon_amex', true ) ) : ?>
			<div class="payment-icon amex">
				<svg xmlns="http://www.w3.org/2000/svg" width="40" height="25" viewBox="0 0 40 25" fill="none" role="img" aria-labelledby="amex-icon">
					<title id="amex-icon"><?php esc_html_e( 'American Express', 'aqualuxe' ); ?></title>
					<rect width="40" height="25" rx="4" fill="#F3F4F6"/>
					<path d="M35 15.2V14.8H34.7L34.5 15.1L34.3 14.8H34V15.2H34.2V14.9L34.4 15.2H34.6L34.8 14.9V15.2H35ZM33.6 15.2V15H34V14.8H33.6V14.7H34V14.5H33.4V15.2H33.6Z" fill="#006FCF"/>
					<path d="M33 12.5H7V17.5H33V12.5Z" fill="#006FCF"/>
					<path d="M9.6 16.5L10.1 15.3H12.1L12.6 16.5H13.8L11.8 11.5H10.4L8.4 16.5H9.6ZM10.4 14.4L11.1 12.6L11.8 14.4H10.4Z" fill="white"/>
					<path d="M13.9 16.5H15V14.7H17.2V13.8H15V12.4H17.3V11.5H13.9V16.5Z" fill="white"/>
					<path d="M21.5 16.5H22.7L20.7 14L22.6 11.5H21.4L20.1 13.2L18.8 11.5H17.6L19.5 13.9L17.5 16.5H18.7L20.1 14.7L21.5 16.5Z" fill="white"/>
					<path d="M7 7.5V12.5H8.1L8.6 11.3H9.1V12.5H10.2V11.3H10.7L11.2 12.5H14.2V11.9L14.5 12.5H16.1L16.4 11.9V12.5H22.1V11.1H22.3C22.4 11.1 22.4 11.1 22.4 11.3V12.5H24.8V12.1C25.2 12.4 25.8 12.5 26.4 12.5H27.5L28 11.3H28.5V12.5H30.6V11.3L31.2 12.5H33V7.5H31.2L30.7 8.7H30.2V7.5H28L27.6 8.4L27.2 7.5H23.7V8.9L23.3 7.5H21.6L20.7 9.6V7.5H18.9L18.5 8.7H18V7.5H15.8L15.3 8.7L14.8 7.5H13.1V12.5H14.8L15.3 11.3L15.8 12.5H17.6V11.3H18.1C18.2 11.3 18.3 11.3 18.3 11.5V12.5H21.1C21.7 12.5 22.2 12.4 22.6 12.1V12.5H24.3V11.3H24.8V12.5H26.5V11.3H27V12.5H28.7V10.9H29.4C29.5 10.9 29.5 10.9 29.5 11.1V12.5H31.1C31.7 12.5 32.3 12.3 32.7 12V12.5H33V7.5H7Z" fill="#006FCF"/>
					<path d="M9.1 8.5H8V10.3H9.1C9.5 10.3 9.7 10.3 9.9 10.1C10.1 10 10.2 9.7 10.2 9.4C10.2 9.1 10.1 8.8 9.9 8.7C9.7 8.5 9.5 8.5 9.1 8.5ZM9.5 9.8C9.4 9.9 9.3 9.9 9.1 9.9H8.5V8.9H9.1C9.3 8.9 9.4 8.9 9.5 9C9.6 9.1 9.6 9.2 9.6 9.4C9.6 9.6 9.6 9.7 9.5 9.8Z" fill="white"/>
					<path d="M11.1 8.5H10V10.3H11.1V8.5Z" fill="white"/>
					<path d="M13.3 9.2C13.3 8.8 13 8.5 12.5 8.5H11.4V10.3H12V9.6H12.3L12.8 10.3H13.5L12.9 9.5C13.2 9.5 13.3 9.3 13.3 9.2ZM12.5 9.2H12V8.9H12.5C12.6 8.9 12.7 9 12.7 9.1C12.7 9.1 12.6 9.2 12.5 9.2Z" fill="white"/>
					<path d="M13.8 10.3H15.3V9.9H14.3V9.5H15.3V9.1H14.3V8.9H15.3V8.5H13.8V10.3Z" fill="white"/>
					<path d="M17.2 9.1L16.5 8.5H15.8V10.3H16.3V9L17.1 10.3H17.7V8.5H17.2V9.1Z" fill="white"/>
					<path d="M18.1 10.3H18.6V8.5H18.1V10.3Z" fill="white"/>
					<path d="M20.3 8.5H19.8L19.1 9.3V8.5H18.6V10.3H19.1V10.2L19.3 10L19.9 10.3H20.5L19.6 9.6L20.3 8.5Z" fill="white"/>
					<path d="M20.6 10.3H22.1V9.9H21.1V9.5H22.1V9.1H21.1V8.9H22.1V8.5H20.6V10.3Z" fill="white"/>
					<path d="M25.1 9.2C25.1 8.8 24.8 8.5 24.3 8.5H23.2V10.3H23.8V9.6H24.1L24.6 10.3H25.3L24.7 9.5C25 9.5 25.1 9.3 25.1 9.2ZM24.3 9.2H23.8V8.9H24.3C24.4 8.9 24.5 9 24.5 9.1C24.5 9.1 24.4 9.2 24.3 9.2Z" fill="white"/>
					<path d="M25.6 10.3H27.1V9.9H26.1V9.5H27.1V9.1H26.1V8.9H27.1V8.5H25.6V10.3Z" fill="white"/>
					<path d="M28.5 8.9H29.1V8.5H27.4V8.9H28V10.3H28.5V8.9Z" fill="white"/>
					<path d="M31.1 9.2C31.1 8.8 30.8 8.5 30.3 8.5H29.2V10.3H29.8V9.6H30.1L30.6 10.3H31.3L30.7 9.5C31 9.5 31.1 9.3 31.1 9.2ZM30.3 9.2H29.8V8.9H30.3C30.4 8.9 30.5 9 30.5 9.1C30.5 9.1 30.4 9.2 30.3 9.2Z" fill="white"/>
				</svg>
			</div>
		<?php endif; ?>
		
		<?php if ( get_theme_mod( 'aqualuxe_payment_icon_paypal', true ) ) : ?>
			<div class="payment-icon paypal">
				<svg xmlns="http://www.w3.org/2000/svg" width="40" height="25" viewBox="0 0 40 25" fill="none" role="img" aria-labelledby="paypal-icon">
					<title id="paypal-icon"><?php esc_html_e( 'PayPal', 'aqualuxe' ); ?></title>
					<rect width="40" height="25" rx="4" fill="#F3F4F6"/>
					<path d="M17.5 10.7C17.5 12.1 16.4 13.2 15 13.2H13.7C13.5 13.2 13.4 13.3 13.4 13.5L13 15.9C13 16 12.9 16.1 12.8 16.1H11.2C11 16.1 10.9 16 11 15.8L12.7 8.2C12.7 8.1 12.8 8 13 8H16.3C17 8 17.5 8.5 17.5 9.2V10.7Z" fill="#253B80"/>
					<path d="M24.5 10.7C24.5 12.1 23.4 13.2 22 13.2H20.7C20.5 13.2 20.4 13.3 20.4 13.5L20 15.9C20 16 19.9 16.1 19.8 16.1H18.2C18 16.1 17.9 16 18 15.8L19.7 8.2C19.7 8.1 19.8 8 20 8H23.3C24 8 24.5 8.5 24.5 9.2V10.7Z" fill="#179BD7"/>
					<path d="M28.8 8H27.2C27 8 26.9 8.1 26.9 8.2L25.2 15.8C25.2 16 25.3 16.1 25.5 16.1H27.1C27.3 16.1 27.4 16 27.4 15.8L27.8 13.5C27.8 13.3 27.9 13.2 28.1 13.2H29.4C30.8 13.2 31.9 12.1 31.9 10.7V9.2C31.9 8.5 31.4 8 30.7 8H28.8ZM29.5 10.7C29.5 11.2 29.1 11.6 28.6 11.6H28C27.8 11.6 27.7 11.5 27.7 11.3L28 9.6C28 9.5 28.1 9.4 28.2 9.4H28.8C29.2 9.4 29.5 9.7 29.5 10.1V10.7Z" fill="#253B80"/>
					<path d="M21.8 8H20.2C20 8 19.9 8.1 19.9 8.2L18.2 15.8C18.2 16 18.3 16.1 18.5 16.1H20C20.2 16.1 20.3 16 20.3 15.8L20.7 13.5C20.7 13.3 20.8 13.2 21 13.2H22.3C23.7 13.2 24.8 12.1 24.8 10.7V9.2C24.8 8.5 24.3 8 23.6 8H21.8ZM22.5 10.7C22.5 11.2 22.1 11.6 21.6 11.6H21C20.8 11.6 20.7 11.5 20.7 11.3L21 9.6C21 9.5 21.1 9.4 21.2 9.4H21.8C22.2 9.4 22.5 9.7 22.5 10.1V10.7Z" fill="#179BD7"/>
					<path d="M14.8 8H13.2C13 8 12.9 8.1 12.9 8.2L11.2 15.8C11.2 16 11.3 16.1 11.5 16.1H13C13.2 16.1 13.3 16 13.3 15.8L13.7 13.5C13.7 13.3 13.8 13.2 14 13.2H15.3C16.7 13.2 17.8 12.1 17.8 10.7V9.2C17.8 8.5 17.3 8 16.6 8H14.8ZM15.5 10.7C15.5 11.2 15.1 11.6 14.6 11.6H14C13.8 11.6 13.7 11.5 13.7 11.3L14 9.6C14 9.5 14.1 9.4 14.2 9.4H14.8C15.2 9.4 15.5 9.7 15.5 10.1V10.7Z" fill="#253B80"/>
				</svg>
			</div>
		<?php endif; ?>
		
		<?php if ( get_theme_mod( 'aqualuxe_payment_icon_apple_pay', true ) ) : ?>
			<div class="payment-icon apple-pay">
				<svg xmlns="http://www.w3.org/2000/svg" width="40" height="25" viewBox="0 0 40 25" fill="none" role="img" aria-labelledby="apple-pay-icon">
					<title id="apple-pay-icon"><?php esc_html_e( 'Apple Pay', 'aqualuxe' ); ?></title>
					<rect width="40" height="25" rx="4" fill="#F3F4F6"/>
					<path d="M14.5 9.5C14.1 10 13.5 10.3 12.9 10.3C12.9 9.6 13.2 8.9 13.6 8.5C14 8 14.7 7.7 15.2 7.7C15.2 8.4 14.9 9.1 14.5 9.5Z" fill="black"/>
					<path d="M15.2 10.4C14.4 10.4 13.7 10.9 13.3 10.9C12.9 10.9 12.2 10.4 11.6 10.4C10.7 10.4 9.8 11 9.3 11.9C8.3 13.8 9 16.6 10 18.1C10.5 18.9 11.1 19.7 11.8 19.7C12.5 19.7 12.8 19.2 13.6 19.2C14.4 19.2 14.7 19.7 15.4 19.7C16.1 19.7 16.6 19 17.1 18.2C17.7 17.3 18 16.4 18 16.3C18 16.3 16.4 15.7 16.4 14C16.4 12.5 17.6 11.9 17.7 11.8C17 10.9 16 10.4 15.2 10.4Z" fill="black"/>
					<path d="M22.3 8.5H24.8L24.9 9H22.3V11.5H24.6V12H22.3V14.5H24.9V15H21.7V8.5H22.3Z" fill="black"/>
					<path d="M28.1 15L26.6 8.5H27.2L28.4 13.9L29.7 8.5H30.3L28.7 15H28.1Z" fill="black"/>
					<path d="M25.9 15L25.8 14.5H25.9L25.9 15Z" fill="black"/>
				</svg>
			</div>
		<?php endif; ?>
		
		<?php if ( get_theme_mod( 'aqualuxe_payment_icon_google_pay', true ) ) : ?>
			<div class="payment-icon google-pay">
				<svg xmlns="http://www.w3.org/2000/svg" width="40" height="25" viewBox="0 0 40 25" fill="none" role="img" aria-labelledby="google-pay-icon">
					<title id="google-pay-icon"><?php esc_html_e( 'Google Pay', 'aqualuxe' ); ?></title>
					<rect width="40" height="25" rx="4" fill="#F3F4F6"/>
					<path d="M19.8 12.5C19.8 12.2 19.8 11.9 19.7 11.6H16V13H18.1C18 13.4 17.8 13.7 17.4 14V15H18.6C19.4 14.3 19.8 13.5 19.8 12.5Z" fill="#4285F4"/>
					<path d="M16 16C17 16 17.8 15.7 18.6 15L17.4 14C17.1 14.2 16.6 14.3 16 14.3C15 14.3 14.2 13.7 13.9 12.8H12.7V13.8C13.4 15.1 14.6 16 16 16Z" fill="#34A853"/>
					<path d="M13.9 12.8C13.8 12.6 13.8 12.3 13.8 12C13.8 11.7 13.8 11.4 13.9 11.2V10.2H12.7C12.4 10.7 12.3 11.3 12.3 12C12.3 12.7 12.4 13.3 12.7 13.8L13.9 12.8Z" fill="#FBBC05"/>
					<path d="M16 9.7C16.6 9.7 17.1 9.9 17.5 10.3L18.6 9.2C17.8 8.5 17 8.1 16 8.1C14.6 8.1 13.4 9 12.7 10.2L13.9 11.2C14.2 10.3 15 9.7 16 9.7Z" fill="#EA4335"/>
					<path d="M24.3 14.3C23.4 14.3 22.7 13.6 22.7 12.7C22.7 11.8 23.4 11.1 24.3 11.1C24.8 11.1 25.2 11.3 25.5 11.6L25.9 11.2C25.5 10.8 25 10.5 24.3 10.5C23.1 10.5 22.1 11.5 22.1 12.7C22.1 13.9 23.1 14.9 24.3 14.9C25 14.9 25.5 14.6 25.9 14.2C26.2 13.9 26.3 13.5 26.3 13H24.3V13.6H25.7C25.7 13.9 25.6 14.1 25.4 14.3C25.1 14.3 24.7 14.3 24.3 14.3Z" fill="#4285F4"/>
					<path d="M28.2 11.1C27.4 11.1 26.7 11.8 26.7 12.7C26.7 13.6 27.4 14.3 28.2 14.3C29 14.3 29.7 13.6 29.7 12.7C29.7 11.8 29 11.1 28.2 11.1ZM28.2 13.7C27.7 13.7 27.3 13.2 27.3 12.7C27.3 12.2 27.7 11.7 28.2 11.7C28.7 11.7 29.1 12.1 29.1 12.7C29.1 13.2 28.7 13.7 28.2 13.7Z" fill="#4285F4"/>
					<path d="M31.4 11.1C30.6 11.1 29.9 11.8 29.9 12.7C29.9 13.6 30.6 14.3 31.4 14.3C32.2 14.3 32.9 13.6 32.9 12.7C32.9 11.8 32.2 11.1 31.4 11.1ZM31.4 13.7C30.9 13.7 30.5 13.2 30.5 12.7C30.5 12.2 30.9 11.7 31.4 11.7C31.9 11.7 32.3 12.1 32.3 12.7C32.3 13.2 31.9 13.7 31.4 13.7Z" fill="#4285F4"/>
					<path d="M34.5 11.1C33.8 11.1 33.2 11.7 33.2 12.6C33.2 13.6 33.8 14.1 34.4 14.1C34.7 14.1 35 14 35.2 13.7H35.3V14C35.3 14.6 35 14.9 34.5 14.9C34.1 14.9 33.8 14.7 33.7 14.4L33.2 14.6C33.4 15.1 33.9 15.5 34.5 15.5C35.2 15.5 35.9 15.1 35.9 13.9V11.2H35.3V11.5H35.2C35 11.2 34.8 11.1 34.5 11.1ZM34.6 13.7C34.1 13.7 33.8 13.3 33.8 12.7C33.8 12.1 34.1 11.7 34.6 11.7C35.1 11.7 35.4 12.1 35.4 12.7C35.4 13.3 35.1 13.7 34.6 13.7Z" fill="#4285F4"/>
					<path d="M21.7 14.3H22.3V10.6H21.7V14.3Z" fill="#4285F4"/>
					<path d="M21.7 10.1H22.3V9.5H21.7V10.1Z" fill="#4285F4"/>
				</svg>
			</div>
		<?php endif; ?>
	</div>
	<?php
	aqualuxe_after_footer_payment_icons();
}