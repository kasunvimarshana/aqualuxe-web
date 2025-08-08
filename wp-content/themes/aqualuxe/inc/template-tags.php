<?php
/**
 * AquaLuxe Template Tags
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Displays the site logo.
 */
function aqualuxe_site_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
	}
}

/**
 * Displays the site title.
 */
function aqualuxe_site_title() {
	if ( is_front_page() && is_home() ) {
		?>
		<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
		<?php
	} else {
		?>
		<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
		<?php
	}
}

/**
 * Displays the site description.
 */
function aqualuxe_site_description() {
	$aqualuxe_description = get_bloginfo( 'description', 'display' );
	if ( $aqualuxe_description || is_customize_preview() ) {
		?>
		<p class="site-description"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		<?php
	}
}

/**
 * Displays the post date.
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
 * Displays the post author.
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
 * Displays the post categories.
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
 * Displays the post thumbnail.
 */
function aqualuxe_post_thumbnail() {
	if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
		return;
	}

	if ( is_singular() ) :
		?>

		<div class="post-thumbnail">
			<?php the_post_thumbnail(); ?>
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
					)
				);
			?>
		</a>

		<?php
	endif; // End is_singular().
}

/**
 * Displays the posts navigation.
 */
function aqualuxe_the_posts_navigation() {
	the_posts_navigation(
		array(
			'prev_text' => '<span aria-hidden="true">' . __( '&larr;', 'aqualuxe' ) . '</span> ' . __( 'Older posts', 'aqualuxe' ),
			'next_text' => __( 'Newer posts', 'aqualuxe' ) . ' <span aria-hidden="true">' . __( '&rarr;', 'aqualuxe' ) . '</span>',
		)
	);
}

/**
 * Displays the post navigation.
 */
function aqualuxe_the_post_navigation() {
	$prev_text = '<span aria-hidden="true">' . __( '&larr;', 'aqualuxe' ) . '</span> ' . __( 'Previous', 'aqualuxe' );
	$next_text = __( 'Next', 'aqualuxe' ) . ' <span aria-hidden="true">' . __( '&rarr;', 'aqualuxe' ) . '</span>';

	the_post_navigation(
		array(
			'prev_text' => $prev_text,
			'next_text' => $next_text,
		)
	);
}

/**
 * Displays the comments navigation.
 */
function aqualuxe_the_comments_navigation() {
	$prev_text = '<span aria-hidden="true">' . __( '&larr;', 'aqualuxe' ) . '</span> ' . __( 'Older comments', 'aqualuxe' );
	$next_text = __( 'Newer comments', 'aqualuxe' ) . ' <span aria-hidden="true">' . __( '&rarr;', 'aqualuxe' ) . '</span>';

	the_comments_navigation(
		array(
			'prev_text' => $prev_text,
			'next_text' => $next_text,
		)
	);
}

/**
 * Displays the comment form.
 */
function aqualuxe_comment_form() {
	// Get the comment form arguments
	$args = array(
		'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title">',
		'title_reply_after'  => '</h3>',
	);
	
	comment_form( $args );
}

/**
 * Displays the search form.
 */
function aqualuxe_get_search_form() {
	$form = '<form role="search" method="get" class="search-form" action="' . esc_url( home_url( '/' ) ) . '">
		<label>
			<span class="screen-reader-text">' . _x( 'Search for:', 'label', 'aqualuxe' ) . '</span>
			<input type="search" class="search-field" placeholder="' . esc_attr_x( 'Search &hellip;', 'placeholder', 'aqualuxe' ) . '" value="' . get_search_query() . '" name="s" />
		</label>
		<input type="submit" class="search-submit" value="' . esc_attr_x( 'Search', 'submit button', 'aqualuxe' ) . '" />
	</form>';

	return $form;
}

/**
 * Displays the main navigation menu.
 */
function aqualuxe_main_navigation() {
	wp_nav_menu(
		array(
			'theme_location' => 'menu-1',
			'menu_id'        => 'primary-menu',
			'container'      => 'nav',
			'container_class' => 'main-navigation',
			'fallback_cb'    => 'aqualuxe_fallback_menu',
		)
	);
}

/**
 * Displays the footer navigation menu.
 */
function aqualuxe_footer_navigation() {
	wp_nav_menu(
		array(
			'theme_location' => 'menu-2',
			'menu_id'        => 'footer-menu',
			'container'      => 'nav',
			'container_class' => 'footer-navigation',
			'fallback_cb'    => 'aqualuxe_fallback_menu',
		)
	);
}

/**
 * Fallback menu when no menu is assigned.
 */
function aqualuxe_fallback_menu() {
	?>
	<ul id="primary-menu" class="menu">
		<li class="menu-item"><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a></li>
		<?php
		// Add additional fallback menu items as needed
		?>
	</ul>
	<?php
}

/**
 * Displays the social media links.
 */
function aqualuxe_social_links() {
	$facebook_url  = get_theme_mod( 'facebook_url', '' );
	$twitter_url   = get_theme_mod( 'twitter_url', '' );
	$instagram_url = get_theme_mod( 'instagram_url', '' );
	$linkedin_url  = get_theme_mod( 'linkedin_url', '' );
	$youtube_url   = get_theme_mod( 'youtube_url', '' );
	
	if ( $facebook_url || $twitter_url || $instagram_url || $linkedin_url || $youtube_url ) {
		echo '<div class="social-links">';
		
		if ( $facebook_url ) {
			echo '<a href="' . esc_url( $facebook_url ) . '" class="social-link facebook" target="_blank"><span class="screen-reader-text">' . esc_html__( 'Facebook', 'aqualuxe' ) . '</span></a>';
		}
		
		if ( $twitter_url ) {
			echo '<a href="' . esc_url( $twitter_url ) . '" class="social-link twitter" target="_blank"><span class="screen-reader-text">' . esc_html__( 'Twitter', 'aqualuxe' ) . '</span></a>';
		}
		
		if ( $instagram_url ) {
			echo '<a href="' . esc_url( $instagram_url ) . '" class="social-link instagram" target="_blank"><span class="screen-reader-text">' . esc_html__( 'Instagram', 'aqualuxe' ) . '</span></a>';
		}
		
		if ( $linkedin_url ) {
			echo '<a href="' . esc_url( $linkedin_url ) . '" class="social-link linkedin" target="_blank"><span class="screen-reader-text">' . esc_html__( 'LinkedIn', 'aqualuxe' ) . '</span></a>';
		}
		
		if ( $youtube_url ) {
			echo '<a href="' . esc_url( $youtube_url ) . '" class="social-link youtube" target="_blank"><span class="screen-reader-text">' . esc_html__( 'YouTube', 'aqualuxe' ) . '</span></a>';
		}
		
		echo '</div>';
	}
}

/**
 * Displays the contact information.
 */
function aqualuxe_contact_info() {
	$address = get_theme_mod( 'contact_address', '' );
	$phone   = get_theme_mod( 'contact_phone', '' );
	$email   = get_theme_mod( 'contact_email', '' );
	
	if ( $address || $phone || $email ) {
		echo '<div class="contact-info">';
		
		if ( $address ) {
			echo '<div class="contact-address">' . wp_kses_post( $address ) . '</div>';
		}
		
		if ( $phone ) {
			echo '<div class="contact-phone"><a href="tel:' . esc_attr( $phone ) . '">' . esc_html( $phone ) . '</a></div>';
		}
		
		if ( $email ) {
			echo '<div class="contact-email"><a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a></div>';
		}
		
		echo '</div>';
	}
}

/**
 * Displays the featured products.
 */
function aqualuxe_featured_products() {
	$featured_products = get_theme_mod( 'featured_products', array() );
	
	if ( ! empty( $featured_products ) ) {
		echo '<div class="featured-products">';
		
		foreach ( $featured_products as $product_id ) {
			$product = wc_get_product( $product_id );
			
			if ( $product ) {
				?>
				<div class="product-item">
					<a href="<?php echo esc_url( $product->get_permalink() ); ?>">
						<?php echo wp_kses_post( $product->get_image() ); ?>
						<h3 class="product-title"><?php echo esc_html( $product->get_name() ); ?></h3>
						<div class="product-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
					</a>
				</div>
				<?php
			}
		}
		
		echo '</div>';
	}
}

/**
 * Displays the testimonials.
 */
function aqualuxe_testimonials() {
	$testimonials = get_theme_mod( 'testimonials', array() );
	
	if ( ! empty( $testimonials ) ) {
		echo '<div class="testimonials-slider">';
		
		foreach ( $testimonials as $testimonial ) {
			?>
			<div class="testimonial-item">
				<div class="testimonial-content">
					<p><?php echo esc_html( $testimonial['content'] ); ?></p>
					<div class="testimonial-author">
						<h4><?php echo esc_html( $testimonial['author'] ); ?></h4>
						<span><?php echo esc_html( $testimonial['location'] ); ?></span>
					</div>
				</div>
			</div>
			<?php
		}
		
		echo '</div>';
	}
}