<?php
/**
 * Template Tags
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Display the site logo or site title
 */
function aqualuxe_site_logo() {
	$custom_logo_id = get_theme_mod( 'custom_logo' );
	
	if ( $custom_logo_id ) {
		$logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		
		if ( $logo ) {
			echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link" rel="home">';
			echo '<img src="' . esc_url( $logo[0] ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="custom-logo" width="' . esc_attr( $logo[1] ) . '" height="' . esc_attr( $logo[2] ) . '">';
			echo '</a>';
		}
	} else {
		echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="site-title" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
		
		$description = get_bloginfo( 'description', 'display' );
		if ( $description || is_customize_preview() ) {
			echo '<p class="site-description">' . esc_html( $description ) . '</p>';
		}
	}
}

/**
 * Display the site navigation
 */
function aqualuxe_site_navigation() {
	if ( has_nav_menu( 'primary' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'primary',
			'menu_id'        => 'primary-menu',
			'menu_class'     => 'primary-menu',
			'container'      => 'nav',
			'container_class' => 'primary-navigation',
			'container_id'   => 'primary-navigation',
			'fallback_cb'    => false,
		) );
	} else {
		echo '<nav id="primary-navigation" class="primary-navigation">';
		echo '<ul id="primary-menu" class="primary-menu">';
		wp_list_pages( array(
			'title_li' => '',
			'depth'    => 1,
		) );
		echo '</ul>';
		echo '</nav>';
	}
}

/**
 * Display the mobile navigation toggle button
 */
function aqualuxe_mobile_nav_toggle() {
	echo '<button id="mobile-nav-toggle" class="mobile-nav-toggle" aria-controls="primary-menu" aria-expanded="false">';
	echo '<span class="toggle-icon">';
	echo '<span class="toggle-line"></span>';
	echo '<span class="toggle-line"></span>';
	echo '<span class="toggle-line"></span>';
	echo '</span>';
	echo '<span class="screen-reader-text">' . esc_html__( 'Menu', 'aqualuxe' ) . '</span>';
	echo '</button>';
}

/**
 * Display the search toggle button
 */
function aqualuxe_search_toggle() {
	if ( ! get_theme_mod( 'aqualuxe_header_search', true ) ) {
		return;
	}
	
	echo '<button id="search-toggle" class="search-toggle" aria-expanded="false" aria-controls="search-container">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" /></svg>';
	echo '<span class="screen-reader-text">' . esc_html__( 'Search', 'aqualuxe' ) . '</span>';
	echo '</button>';
}

/**
 * Display the search form
 */
function aqualuxe_search_form() {
	if ( ! get_theme_mod( 'aqualuxe_header_search', true ) ) {
		return;
	}
	
	echo '<div id="search-container" class="search-container" aria-hidden="true">';
	echo '<div class="search-container-inner">';
	get_search_form();
	echo '</div>';
	echo '</div>';
}

/**
 * Display the cart toggle button
 */
function aqualuxe_cart_toggle() {
	if ( ! class_exists( 'WooCommerce' ) || ! get_theme_mod( 'aqualuxe_header_cart', true ) ) {
		return;
	}
	
	$cart_count = WC()->cart->get_cart_contents_count();
	$cart_url = wc_get_cart_url();
	
	echo '<a href="' . esc_url( $cart_url ) . '" class="cart-toggle">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M2.25 2.25a.75.75 0 000 1.5h1.386c.17 0 .318.114.362.278l2.558 9.592a3.752 3.752 0 00-2.806 3.63c0 .414.336.75.75.75h15.75a.75.75 0 000-1.5H5.378A2.25 2.25 0 017.5 15h11.218a.75.75 0 00.674-.421 60.358 60.358 0 002.96-7.228.75.75 0 00-.525-.965A60.864 60.864 0 005.68 4.509l-.232-.867A1.875 1.875 0 003.636 2.25H2.25zM3.75 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0zM16.5 20.25a1.5 1.5 0 113 0 1.5 1.5 0 01-3 0z" /></svg>';
	echo '<span class="cart-count">' . esc_html( $cart_count ) . '</span>';
	echo '<span class="screen-reader-text">' . esc_html__( 'View your shopping cart', 'aqualuxe' ) . '</span>';
	echo '</a>';
}

/**
 * Display the mini cart
 */
function aqualuxe_mini_cart() {
	if ( ! class_exists( 'WooCommerce' ) || ! get_theme_mod( 'aqualuxe_header_cart', true ) ) {
		return;
	}
	
	echo '<div class="mini-cart-container">';
	woocommerce_mini_cart();
	echo '</div>';
}

/**
 * Display the dark mode toggle button
 */
function aqualuxe_dark_mode_toggle() {
	if ( ! get_theme_mod( 'aqualuxe_enable_dark_mode', true ) ) {
		return;
	}
	
	echo '<button id="dark-mode-toggle" class="dark-mode-toggle" aria-label="' . esc_attr__( 'Toggle dark mode', 'aqualuxe' ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="dark-mode-icon"><path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd" /></svg>';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="light-mode-icon"><path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z" /></svg>';
	echo '</button>';
}

/**
 * Display the breadcrumbs
 */
function aqualuxe_breadcrumbs() {
	if ( is_front_page() ) {
		return;
	}
	
	echo '<nav class="breadcrumbs" aria-label="' . esc_attr__( 'Breadcrumbs', 'aqualuxe' ) . '">';
	echo '<ol class="breadcrumb-list">';
	
	// Home
	echo '<li class="breadcrumb-item">';
	echo '<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html__( 'Home', 'aqualuxe' ) . '</a>';
	echo '</li>';
	
	if ( is_category() ) {
		$category = get_queried_object();
		echo '<li class="breadcrumb-item">';
		echo esc_html( $category->name );
		echo '</li>';
	} elseif ( is_tag() ) {
		$tag = get_queried_object();
		echo '<li class="breadcrumb-item">';
		echo esc_html__( 'Tag: ', 'aqualuxe' ) . esc_html( $tag->name );
		echo '</li>';
	} elseif ( is_author() ) {
		echo '<li class="breadcrumb-item">';
		echo esc_html__( 'Author: ', 'aqualuxe' ) . esc_html( get_the_author() );
		echo '</li>';
	} elseif ( is_year() ) {
		echo '<li class="breadcrumb-item">';
		echo esc_html( get_the_date( 'Y' ) );
		echo '</li>';
	} elseif ( is_month() ) {
		echo '<li class="breadcrumb-item">';
		echo '<a href="' . esc_url( get_year_link( get_the_date( 'Y' ) ) ) . '">' . esc_html( get_the_date( 'Y' ) ) . '</a>';
		echo '</li>';
		echo '<li class="breadcrumb-item">';
		echo esc_html( get_the_date( 'F' ) );
		echo '</li>';
	} elseif ( is_day() ) {
		echo '<li class="breadcrumb-item">';
		echo '<a href="' . esc_url( get_year_link( get_the_date( 'Y' ) ) ) . '">' . esc_html( get_the_date( 'Y' ) ) . '</a>';
		echo '</li>';
		echo '<li class="breadcrumb-item">';
		echo '<a href="' . esc_url( get_month_link( get_the_date( 'Y' ), get_the_date( 'm' ) ) ) . '">' . esc_html( get_the_date( 'F' ) ) . '</a>';
		echo '</li>';
		echo '<li class="breadcrumb-item">';
		echo esc_html( get_the_date( 'd' ) );
		echo '</li>';
	} elseif ( is_singular( 'post' ) ) {
		$categories = get_the_category();
		if ( ! empty( $categories ) ) {
			echo '<li class="breadcrumb-item">';
			echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '">' . esc_html( $categories[0]->name ) . '</a>';
			echo '</li>';
		}
		echo '<li class="breadcrumb-item">';
		echo esc_html( get_the_title() );
		echo '</li>';
	} elseif ( is_page() ) {
		$ancestors = get_post_ancestors( get_the_ID() );
		if ( ! empty( $ancestors ) ) {
			$ancestors = array_reverse( $ancestors );
			foreach ( $ancestors as $ancestor ) {
				echo '<li class="breadcrumb-item">';
				echo '<a href="' . esc_url( get_permalink( $ancestor ) ) . '">' . esc_html( get_the_title( $ancestor ) ) . '</a>';
				echo '</li>';
			}
		}
		echo '<li class="breadcrumb-item">';
		echo esc_html( get_the_title() );
		echo '</li>';
	} elseif ( is_search() ) {
		echo '<li class="breadcrumb-item">';
		echo esc_html__( 'Search Results for: ', 'aqualuxe' ) . esc_html( get_search_query() );
		echo '</li>';
	} elseif ( is_404() ) {
		echo '<li class="breadcrumb-item">';
		echo esc_html__( 'Page Not Found', 'aqualuxe' );
		echo '</li>';
	} elseif ( is_home() ) {
		echo '<li class="breadcrumb-item">';
		echo esc_html__( 'Blog', 'aqualuxe' );
		echo '</li>';
	}
	
	// WooCommerce breadcrumbs
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_shop() ) {
			echo '<li class="breadcrumb-item">';
			echo esc_html__( 'Shop', 'aqualuxe' );
			echo '</li>';
		} elseif ( is_product_category() ) {
			echo '<li class="breadcrumb-item">';
			echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'Shop', 'aqualuxe' ) . '</a>';
			echo '</li>';
			
			$category = get_queried_object();
			echo '<li class="breadcrumb-item">';
			echo esc_html( $category->name );
			echo '</li>';
		} elseif ( is_product() ) {
			echo '<li class="breadcrumb-item">';
			echo '<a href="' . esc_url( get_permalink( wc_get_page_id( 'shop' ) ) ) . '">' . esc_html__( 'Shop', 'aqualuxe' ) . '</a>';
			echo '</li>';
			
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
				echo '<li class="breadcrumb-item">';
				echo '<a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a>';
				echo '</li>';
			}
			
			echo '<li class="breadcrumb-item">';
			echo esc_html( get_the_title() );
			echo '</li>';
		}
	}
	
	echo '</ol>';
	echo '</nav>';
}

/**
 * Display the pagination
 */
function aqualuxe_pagination() {
	$args = array(
		'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M7.72 12.53a.75.75 0 010-1.06l7.5-7.5a.75.75 0 111.06 1.06L9.31 12l6.97 6.97a.75.75 0 11-1.06 1.06l-7.5-7.5z" clip-rule="evenodd" /></svg><span class="screen-reader-text">' . esc_html__( 'Previous page', 'aqualuxe' ) . '</span>',
		'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'aqualuxe' ) . '</span><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M16.28 11.47a.75.75 0 010 1.06l-7.5 7.5a.75.75 0 01-1.06-1.06L14.69 12 7.72 5.03a.75.75 0 011.06-1.06l7.5 7.5z" clip-rule="evenodd" /></svg>',
		'mid_size'  => 2,
		'end_size'  => 1,
	);
	
	the_posts_pagination( $args );
}

/**
 * Display the post navigation
 */
function aqualuxe_post_navigation() {
	the_post_navigation( array(
		'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
		'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
	) );
}

/**
 * Display the social links
 */
function aqualuxe_social_links() {
	$facebook = get_theme_mod( 'aqualuxe_facebook_url', '' );
	$twitter = get_theme_mod( 'aqualuxe_twitter_url', '' );
	$instagram = get_theme_mod( 'aqualuxe_instagram_url', '' );
	$youtube = get_theme_mod( 'aqualuxe_youtube_url', '' );
	$linkedin = get_theme_mod( 'aqualuxe_linkedin_url', '' );
	
	if ( ! $facebook && ! $twitter && ! $instagram && ! $youtube && ! $linkedin ) {
		return;
	}
	
	echo '<div class="social-links">';
	
	if ( $facebook ) {
		echo '<a href="' . esc_url( $facebook ) . '" class="social-link facebook" target="_blank" rel="noopener noreferrer">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" fill="currentColor"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>';
		echo '<span class="screen-reader-text">' . esc_html__( 'Facebook', 'aqualuxe' ) . '</span>';
		echo '</a>';
	}
	
	if ( $twitter ) {
		echo '<a href="' . esc_url( $twitter ) . '" class="social-link twitter" target="_blank" rel="noopener noreferrer">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>';
		echo '<span class="screen-reader-text">' . esc_html__( 'Twitter', 'aqualuxe' ) . '</span>';
		echo '</a>';
	}
	
	if ( $instagram ) {
		echo '<a href="' . esc_url( $instagram ) . '" class="social-link instagram" target="_blank" rel="noopener noreferrer">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>';
		echo '<span class="screen-reader-text">' . esc_html__( 'Instagram', 'aqualuxe' ) . '</span>';
		echo '</a>';
	}
	
	if ( $youtube ) {
		echo '<a href="' . esc_url( $youtube ) . '" class="social-link youtube" target="_blank" rel="noopener noreferrer">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" fill="currentColor"><path d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"/></svg>';
		echo '<span class="screen-reader-text">' . esc_html__( 'YouTube', 'aqualuxe' ) . '</span>';
		echo '</a>';
	}
	
	if ( $linkedin ) {
		echo '<a href="' . esc_url( $linkedin ) . '" class="social-link linkedin" target="_blank" rel="noopener noreferrer">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/></svg>';
		echo '<span class="screen-reader-text">' . esc_html__( 'LinkedIn', 'aqualuxe' ) . '</span>';
		echo '</a>';
	}
	
	echo '</div>';
}

/**
 * Display the contact information
 */
function aqualuxe_contact_info() {
	$email = get_theme_mod( 'aqualuxe_contact_email', AQUALUXE_CONTACT_EMAIL );
	$phone = get_theme_mod( 'aqualuxe_contact_phone', AQUALUXE_CONTACT_PHONE );
	$address = get_theme_mod( 'aqualuxe_contact_address', AQUALUXE_CONTACT_ADDRESS );
	
	if ( ! $email && ! $phone && ! $address ) {
		return;
	}
	
	echo '<div class="contact-info">';
	
	if ( $email ) {
		echo '<div class="contact-item email">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" /><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" /></svg>';
		echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
		echo '</div>';
	}
	
	if ( $phone ) {
		echo '<div class="contact-item phone">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z" clip-rule="evenodd" /></svg>';
		echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
		echo '</div>';
	}
	
	if ( $address ) {
		echo '<div class="contact-item address">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>';
		echo '<address>' . esc_html( $address ) . '</address>';
		echo '</div>';
	}
	
	echo '</div>';
}

/**
 * Display the copyright text
 */
function aqualuxe_copyright_text() {
	$copyright = get_theme_mod( 'aqualuxe_copyright_text', sprintf( esc_html__( '© %s AquaLuxe. All Rights Reserved.', 'aqualuxe' ), date( 'Y' ) ) );
	$copyright = str_replace( '{year}', date( 'Y' ), $copyright );
	
	echo '<div class="copyright-text">';
	echo wp_kses_post( $copyright );
	echo '</div>';
}

/**
 * Display the footer menu
 */
function aqualuxe_footer_menu() {
	if ( has_nav_menu( 'footer' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'footer',
			'menu_id'        => 'footer-menu',
			'menu_class'     => 'footer-menu',
			'container'      => 'nav',
			'container_class' => 'footer-navigation',
			'container_id'   => 'footer-navigation',
			'depth'          => 1,
			'fallback_cb'    => false,
		) );
	}
}

/**
 * Display the social menu
 */
function aqualuxe_social_menu() {
	if ( has_nav_menu( 'social' ) ) {
		wp_nav_menu( array(
			'theme_location' => 'social',
			'menu_id'        => 'social-menu',
			'menu_class'     => 'social-menu',
			'container'      => 'nav',
			'container_class' => 'social-navigation',
			'container_id'   => 'social-navigation',
			'link_before'    => '<span class="screen-reader-text">',
			'link_after'     => '</span>',
			'depth'          => 1,
			'fallback_cb'    => false,
		) );
	}
}

/**
 * Display the post thumbnail with responsive image support
 *
 * @param string $size Image size.
 */
function aqualuxe_post_thumbnail( $size = 'aqualuxe-featured' ) {
	if ( ! has_post_thumbnail() ) {
		return;
	}
	
	$image_id = get_post_thumbnail_id();
	$image_alt = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
	$image_alt = $image_alt ? $image_alt : get_the_title();
	
	// Get image sizes
	$image_src = wp_get_attachment_image_src( $image_id, $size );
	$image_srcset = wp_get_attachment_image_srcset( $image_id, $size );
	$image_sizes = wp_get_attachment_image_sizes( $image_id, $size );
	
	if ( ! $image_src ) {
		return;
	}
	
	echo '<div class="post-thumbnail">';
	echo '<img src="' . esc_url( $image_src[0] ) . '" alt="' . esc_attr( $image_alt ) . '" width="' . esc_attr( $image_src[1] ) . '" height="' . esc_attr( $image_src[2] ) . '"';
	
	if ( $image_srcset ) {
		echo ' srcset="' . esc_attr( $image_srcset ) . '"';
	}
	
	if ( $image_sizes ) {
		echo ' sizes="' . esc_attr( $image_sizes ) . '"';
	}
	
	echo ' class="attachment-' . esc_attr( $size ) . ' size-' . esc_attr( $size ) . ' wp-post-image" loading="lazy">';
	echo '</div>';
}

/**
 * Display the featured image with caption
 */
function aqualuxe_featured_image_with_caption() {
	if ( ! has_post_thumbnail() ) {
		return;
	}
	
	$image_id = get_post_thumbnail_id();
	$image_caption = wp_get_attachment_caption( $image_id );
	
	echo '<figure class="featured-image">';
	aqualuxe_post_thumbnail();
	
	if ( $image_caption ) {
		echo '<figcaption class="featured-image-caption">' . wp_kses_post( $image_caption ) . '</figcaption>';
	}
	
	echo '</figure>';
}

/**
 * Display the post meta
 */
function aqualuxe_post_meta() {
	if ( ! get_theme_mod( 'aqualuxe_show_post_meta', true ) ) {
		return;
	}
	
	echo '<div class="entry-meta">';
	
	// Author
	echo '<span class="author">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" /></svg>';
	echo '<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>';
	echo '</span>';
	
	// Date
	echo '<span class="date">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M12.75 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM7.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM8.25 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM9.75 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM10.5 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM12.75 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM14.25 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 13.5a.75.75 0 100-1.5.75.75 0 000 1.5z" /><path fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z" clip-rule="evenodd" /></svg>';
	echo '<time datetime="' . esc_attr( get_the_date( 'c' ) ) . '">' . esc_html( get_the_date() ) . '</time>';
	echo '</span>';
	
	// Categories
	$categories = get_the_category();
	if ( ! empty( $categories ) ) {
		echo '<span class="categories">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M5.25 2.25a3 3 0 00-3 3v4.318a3 3 0 00.879 2.121l9.58 9.581c.92.92 2.39.92 3.31 0l4.908-4.908a2.25 2.25 0 000-3.182l-9.58-9.58a3 3 0 00-2.121-.879H5.25zM6.375 7.5a1.125 1.125 0 100-2.25 1.125 1.125 0 000 2.25z" clip-rule="evenodd" /></svg>';
		$category_links = array();
		foreach ( $categories as $category ) {
			$category_links[] = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
		}
		echo implode( ', ', $category_links );
		echo '</span>';
	}
	
	// Comments
	if ( comments_open() ) {
		echo '<span class="comments">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97zM6.75 8.25a.75.75 0 01.75-.75h9a.75.75 0 010 1.5h-9a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H7.5z" clip-rule="evenodd" /></svg>';
		comments_popup_link(
			esc_html__( 'Leave a comment', 'aqualuxe' ),
			esc_html__( '1 Comment', 'aqualuxe' ),
			esc_html__( '% Comments', 'aqualuxe' )
		);
		echo '</span>';
	}
	
	echo '</div>';
}

/**
 * Display the post tags
 */
function aqualuxe_post_tags() {
	$tags = get_the_tags();
	if ( ! $tags ) {
		return;
	}
	
	echo '<div class="entry-tags">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M5.25 2.25a3 3 0 00-3 3v4.318a3 3 0 00.879 2.121l9.58 9.581c.92.92 2.39.92 3.31 0l4.908-4.908a2.25 2.25 0 000-3.182l-9.58-9.58a3 3 0 00-2.121-.879H5.25zM6.375 7.5a1.125 1.125 0 100-2.25 1.125 1.125 0 000 2.25z" clip-rule="evenodd" /></svg>';
	$tag_links = array();
	foreach ( $tags as $tag ) {
		$tag_links[] = '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '">' . esc_html( $tag->name ) . '</a>';
	}
	echo implode( ', ', $tag_links );
	echo '</div>';
}

/**
 * Display the author bio
 */
function aqualuxe_author_bio() {
	if ( ! get_theme_mod( 'aqualuxe_show_author_bio', true ) ) {
		return;
	}
	
	$author_id = get_the_author_meta( 'ID' );
	$author_bio = get_the_author_meta( 'description' );
	
	if ( ! $author_bio ) {
		return;
	}
	
	echo '<div class="author-bio">';
	echo '<div class="author-avatar">';
	echo get_avatar( $author_id, 80 );
	echo '</div>';
	echo '<div class="author-content">';
	echo '<h3 class="author-title">' . esc_html__( 'About', 'aqualuxe' ) . ' ' . esc_html( get_the_author() ) . '</h3>';
	echo '<div class="author-description">' . wp_kses_post( $author_bio ) . '</div>';
	echo '<a class="author-link" href="' . esc_url( get_author_posts_url( $author_id ) ) . '">' . esc_html__( 'View all posts by', 'aqualuxe' ) . ' ' . esc_html( get_the_author() ) . '</a>';
	echo '</div>';
	echo '</div>';
}

/**
 * Display related posts
 */
function aqualuxe_related_posts() {
	if ( ! get_theme_mod( 'aqualuxe_show_related_posts', true ) ) {
		return;
	}
	
	$categories = get_the_category();
	if ( empty( $categories ) ) {
		return;
	}
	
	$category_ids = array();
	foreach ( $categories as $category ) {
		$category_ids[] = $category->term_id;
	}
	
	$args = array(
		'category__in'        => $category_ids,
		'post__not_in'        => array( get_the_ID() ),
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => 1,
	);
	
	$related_query = new WP_Query( $args );
	
	if ( ! $related_query->have_posts() ) {
		return;
	}
	
	echo '<div class="related-posts">';
	echo '<h3 class="related-title">' . esc_html__( 'Related Posts', 'aqualuxe' ) . '</h3>';
	echo '<div class="related-posts-grid">';
	
	while ( $related_query->have_posts() ) {
		$related_query->the_post();
		
		echo '<article class="related-post">';
		if ( has_post_thumbnail() ) {
			echo '<a href="' . esc_url( get_permalink() ) . '" class="related-thumbnail">';
			the_post_thumbnail( 'aqualuxe-card' );
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

/**
 * Display the post author
 */
function aqualuxe_post_author() {
	$author_id = get_the_author_meta( 'ID' );
	$author_name = get_the_author();
	$author_url = get_author_posts_url( $author_id );
	
	echo '<span class="byline">';
	echo '<span class="author vcard">';
	echo '<a class="url fn n" href="' . esc_url( $author_url ) . '">' . esc_html( $author_name ) . '</a>';
	echo '</span>';
	echo '</span>';
}

/**
 * Display the post date
 */
function aqualuxe_post_date() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}
	
	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	
	echo '<span class="posted-on">';
	echo '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>';
	echo '</span>';
}

/**
 * Display the post categories
 */
function aqualuxe_post_categories() {
	$categories = get_the_category();
	if ( ! empty( $categories ) ) {
		echo '<span class="cat-links">';
		$category_links = array();
		foreach ( $categories as $category ) {
			$category_links[] = '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
		}
		echo implode( ', ', $category_links );
		echo '</span>';
	}
}

/**
 * Display the post comments link
 */
function aqualuxe_post_comments_link() {
	if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
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
}

/**
 * Display the edit post link
 */
function aqualuxe_edit_post_link() {
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

/**
 * Display the post excerpt
 */
function aqualuxe_post_excerpt() {
	the_excerpt();
}

/**
 * Display the read more link
 */
function aqualuxe_read_more_link() {
	echo '<a href="' . esc_url( get_permalink() ) . '" class="read-more">' . esc_html__( 'Read More', 'aqualuxe' ) . '</a>';
}

/**
 * Display the post content
 */
function aqualuxe_post_content() {
	the_content(
		sprintf(
			wp_kses(
				/* translators: %s: Name of current post. Only visible to screen readers */
				__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'aqualuxe' ),
				array(
					'span' => array(
						'class' => array(),
					),
				)
			),
			get_the_title()
		)
	);
	
	wp_link_pages(
		array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
			'after'  => '</div>',
		)
	);
}

/**
 * Display the archive title
 */
function aqualuxe_archive_title() {
	if ( is_category() ) {
		$title = sprintf( esc_html__( 'Category: %s', 'aqualuxe' ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( esc_html__( 'Tag: %s', 'aqualuxe' ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( esc_html__( 'Author: %s', 'aqualuxe' ), '<span class="vcard">' . get_the_author() . '</span>' );
	} elseif ( is_year() ) {
		$title = sprintf( esc_html__( 'Year: %s', 'aqualuxe' ), get_the_date( esc_html_x( 'Y', 'yearly archives date format', 'aqualuxe' ) ) );
	} elseif ( is_month() ) {
		$title = sprintf( esc_html__( 'Month: %s', 'aqualuxe' ), get_the_date( esc_html_x( 'F Y', 'monthly archives date format', 'aqualuxe' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( esc_html__( 'Day: %s', 'aqualuxe' ), get_the_date( esc_html_x( 'F j, Y', 'daily archives date format', 'aqualuxe' ) ) );
	} elseif ( is_post_type_archive() ) {
		$title = sprintf( esc_html__( 'Archives: %s', 'aqualuxe' ), post_type_archive_title( '', false ) );
	} elseif ( is_tax() ) {
		$tax = get_taxonomy( get_queried_object()->taxonomy );
		$title = sprintf( esc_html__( '%1$s: %2$s', 'aqualuxe' ), $tax->labels->singular_name, single_term_title( '', false ) );
	} else {
		$title = esc_html__( 'Archives', 'aqualuxe' );
	}
	
	echo '<h1 class="archive-title">' . $title . '</h1>';
}

/**
 * Display the archive description
 */
function aqualuxe_archive_description() {
	$description = get_the_archive_description();
	
	if ( $description ) {
		echo '<div class="archive-description">' . wp_kses_post( $description ) . '</div>';
	}
}

/**
 * Display the search title
 */
function aqualuxe_search_title() {
	echo '<h1 class="search-title">';
	printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
	echo '</h1>';
}

/**
 * Display the 404 title
 */
function aqualuxe_404_title() {
	echo '<h1 class="error-404-title">' . esc_html__( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ) . '</h1>';
}

/**
 * Display the 404 content
 */
function aqualuxe_404_content() {
	echo '<div class="error-404-content">';
	echo '<p>' . esc_html__( 'It looks like nothing was found at this location. Maybe try a search?', 'aqualuxe' ) . '</p>';
	get_search_form();
	echo '</div>';
}

/**
 * Display the page title
 */
function aqualuxe_page_title() {
	the_title( '<h1 class="entry-title">', '</h1>' );
}

/**
 * Display the page content
 */
function aqualuxe_page_content() {
	the_content();
	
	wp_link_pages(
		array(
			'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'aqualuxe' ),
			'after'  => '</div>',
		)
	);
}

/**
 * Display the comments
 */
function aqualuxe_comments() {
	if ( comments_open() || get_comments_number() ) {
		comments_template();
	}
}

/**
 * Display the sidebar
 */
function aqualuxe_sidebar() {
	get_sidebar();
}

/**
 * Display the footer widgets
 */
function aqualuxe_footer_widgets() {
	$footer_columns = get_theme_mod( 'aqualuxe_footer_columns', '4' );
	
	if ( ! is_active_sidebar( 'footer-1' ) && ! is_active_sidebar( 'footer-2' ) && ! is_active_sidebar( 'footer-3' ) && ! is_active_sidebar( 'footer-4' ) ) {
		return;
	}
	
	echo '<div class="footer-widgets footer-widgets-' . esc_attr( $footer_columns ) . '-columns">';
	
	for ( $i = 1; $i <= $footer_columns; $i++ ) {
		if ( is_active_sidebar( 'footer-' . $i ) ) {
			echo '<div class="footer-widget footer-widget-' . esc_attr( $i ) . '">';
			dynamic_sidebar( 'footer-' . $i );
			echo '</div>';
		}
	}
	
	echo '</div>';
}

/**
 * Display the back to top button
 */
function aqualuxe_back_to_top_button() {
	echo '<a href="#page" class="back-to-top" aria-label="' . esc_attr__( 'Back to top', 'aqualuxe' ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M11.47 7.72a.75.75 0 011.06 0l7.5 7.5a.75.75 0 11-1.06 1.06L12 9.31l-6.97 6.97a.75.75 0 01-1.06-1.06l7.5-7.5z" clip-rule="evenodd" /></svg>';
	echo '</a>';
}

/**
 * Display the skip link
 */
function aqualuxe_skip_link() {
	echo '<a class="skip-link screen-reader-text" href="#content">' . esc_html__( 'Skip to content', 'aqualuxe' ) . '</a>';
}

/**
 * Display the site header
 */
function aqualuxe_site_header() {
	get_header();
}

/**
 * Display the site footer
 */
function aqualuxe_site_footer() {
	get_footer();
}

/**
 * Display the site content
 */
function aqualuxe_site_content() {
	echo '<div id="content" class="site-content">';
	echo '<div class="container">';
	
	if ( is_singular() ) {
		while ( have_posts() ) {
			the_post();
			get_template_part( 'templates/parts/content/content', get_post_type() );
			
			if ( comments_open() || get_comments_number() ) {
				comments_template();
			}
		}
	} elseif ( is_archive() || is_home() ) {
		if ( have_posts() ) {
			echo '<div class="archive-posts">';
			
			while ( have_posts() ) {
				the_post();
				get_template_part( 'templates/parts/content/content', get_post_type() );
			}
			
			echo '</div>';
			
			aqualuxe_pagination();
		} else {
			get_template_part( 'templates/parts/content/content', 'none' );
		}
	} elseif ( is_search() ) {
		if ( have_posts() ) {
			echo '<div class="search-results">';
			
			while ( have_posts() ) {
				the_post();
				get_template_part( 'templates/parts/content/content', 'search' );
			}
			
			echo '</div>';
			
			aqualuxe_pagination();
		} else {
			get_template_part( 'templates/parts/content/content', 'none' );
		}
	} else {
		get_template_part( 'templates/parts/content/content', 'none' );
	}
	
	echo '</div>';
	echo '</div>';
}

/**
 * Display the site sidebar
 */
function aqualuxe_site_sidebar() {
	$layout = get_theme_mod( 'aqualuxe_layout', 'right-sidebar' );
	
	if ( 'full-width' === $layout ) {
		return;
	}
	
	get_sidebar();
}

/**
 * Display the WooCommerce shop sidebar
 */
function aqualuxe_shop_sidebar() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	if ( ! is_active_sidebar( 'shop-sidebar' ) ) {
		return;
	}
	
	echo '<div id="shop-sidebar" class="shop-sidebar widget-area">';
	dynamic_sidebar( 'shop-sidebar' );
	echo '</div>';
}

/**
 * Display the WooCommerce product categories
 */
function aqualuxe_product_categories() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	$args = array(
		'before'        => '<ul class="product-categories">',
		'after'         => '</ul>',
		'title_li'      => '',
		'show_count'    => true,
		'hierarchical'  => true,
		'hide_empty'    => true,
		'taxonomy'      => 'product_cat',
	);
	
	echo '<div class="product-categories-container">';
	echo '<h3 class="product-categories-title">' . esc_html__( 'Product Categories', 'aqualuxe' ) . '</h3>';
	wp_list_categories( $args );
	echo '</div>';
}

/**
 * Display the WooCommerce product search
 */
function aqualuxe_product_search() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<div class="product-search-container">';
	get_product_search_form();
	echo '</div>';
}

/**
 * Display the WooCommerce product filters
 */
function aqualuxe_product_filters() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<div class="product-filters-container">';
	echo '<h3 class="product-filters-title">' . esc_html__( 'Product Filters', 'aqualuxe' ) . '</h3>';
	
	// Price filter
	echo '<div class="product-filter price-filter">';
	echo '<h4 class="filter-title">' . esc_html__( 'Filter by Price', 'aqualuxe' ) . '</h4>';
	woocommerce_price_filter();
	echo '</div>';
	
	// Attribute filters
	echo '<div class="product-filter attribute-filter">';
	echo '<h4 class="filter-title">' . esc_html__( 'Filter by Attributes', 'aqualuxe' ) . '</h4>';
	woocommerce_layered_nav();
	echo '</div>';
	
	echo '</div>';
}

/**
 * Display the WooCommerce product sorting
 */
function aqualuxe_product_sorting() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<div class="product-sorting-container">';
	woocommerce_catalog_ordering();
	echo '</div>';
}

/**
 * Display the WooCommerce product result count
 */
function aqualuxe_product_result_count() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<div class="product-result-count-container">';
	woocommerce_result_count();
	echo '</div>';
}

/**
 * Display the WooCommerce product quick view button
 */
function aqualuxe_product_quick_view_button() {
	if ( ! class_exists( 'WooCommerce' ) || ! get_theme_mod( 'aqualuxe_show_quick_view', true ) ) {
		return;
	}
	
	echo '<button class="quick-view-button" data-product-id="' . esc_attr( get_the_ID() ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M12 15a3 3 0 100-6 3 3 0 000 6z" /><path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" /></svg>';
	echo '<span class="screen-reader-text">' . esc_html__( 'Quick View', 'aqualuxe' ) . '</span>';
	echo '</button>';
}

/**
 * Display the WooCommerce wishlist button
 */
function aqualuxe_wishlist_button() {
	if ( ! class_exists( 'WooCommerce' ) || ! get_theme_mod( 'aqualuxe_show_wishlist', true ) ) {
		return;
	}
	
	echo '<button class="wishlist-button" data-product-id="' . esc_attr( get_the_ID() ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" /></svg>';
	echo '<span class="screen-reader-text">' . esc_html__( 'Add to Wishlist', 'aqualuxe' ) . '</span>';
	echo '</button>';
}

/**
 * Display the WooCommerce compare button
 */
function aqualuxe_compare_button() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<button class="compare-button" data-product-id="' . esc_attr( get_the_ID() ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M18.375 2.25c-1.035 0-1.875.84-1.875 1.875v15.75c0 1.035.84 1.875 1.875 1.875h.75c1.035 0 1.875-.84 1.875-1.875V4.125c0-1.036-.84-1.875-1.875-1.875h-.75zM9.75 8.625c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-.75a1.875 1.875 0 01-1.875-1.875V8.625zM3 13.125c0-1.036.84-1.875 1.875-1.875h.75c1.036 0 1.875.84 1.875 1.875v6.75c0 1.035-.84 1.875-1.875 1.875h-.75A1.875 1.875 0 013 19.875v-6.75z" /></svg>';
	echo '<span class="screen-reader-text">' . esc_html__( 'Compare', 'aqualuxe' ) . '</span>';
	echo '</button>';
}

/**
 * Display the WooCommerce product rating
 */
function aqualuxe_product_rating() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	if ( $product->get_rating_count() > 0 ) {
		woocommerce_template_loop_rating();
	} else {
		echo '<div class="star-rating">';
		echo '<span style="width:0%">' . esc_html__( 'Rated 0 out of 5', 'aqualuxe' ) . '</span>';
		echo '</div>';
	}
}

/**
 * Display the WooCommerce product price
 */
function aqualuxe_product_price() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_template_loop_price();
}

/**
 * Display the WooCommerce add to cart button
 */
function aqualuxe_add_to_cart_button() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_template_loop_add_to_cart();
}

/**
 * Display the WooCommerce product badge
 */
function aqualuxe_product_badge() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	if ( $product->is_on_sale() ) {
		echo '<span class="product-badge sale">' . esc_html__( 'Sale', 'aqualuxe' ) . '</span>';
	} elseif ( ! $product->is_in_stock() ) {
		echo '<span class="product-badge out-of-stock">' . esc_html__( 'Out of Stock', 'aqualuxe' ) . '</span>';
	} elseif ( $product->is_featured() ) {
		echo '<span class="product-badge featured">' . esc_html__( 'Featured', 'aqualuxe' ) . '</span>';
	} elseif ( $product->get_date_created() && ( time() - $product->get_date_created()->getTimestamp() ) < 7 * DAY_IN_SECONDS ) {
		echo '<span class="product-badge new">' . esc_html__( 'New', 'aqualuxe' ) . '</span>';
	}
}

/**
 * Display the WooCommerce product tabs
 */
function aqualuxe_product_tabs() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	woocommerce_output_product_data_tabs();
}

/**
 * Display the WooCommerce related products
 */
function aqualuxe_related_products() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	woocommerce_output_related_products();
}

/**
 * Display the WooCommerce upsell products
 */
function aqualuxe_upsell_products() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	woocommerce_upsell_display();
}

/**
 * Display the WooCommerce cross-sell products
 */
function aqualuxe_cross_sell_products() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	woocommerce_cross_sell_display();
}

/**
 * Display the WooCommerce product meta
 */
function aqualuxe_product_meta() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	woocommerce_template_single_meta();
}

/**
 * Display the WooCommerce product sharing
 */
function aqualuxe_product_sharing() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<div class="product-sharing">';
	echo '<h3 class="sharing-title">' . esc_html__( 'Share This Product', 'aqualuxe' ) . '</h3>';
	echo '<div class="sharing-links">';
	
	$product_url = urlencode( get_permalink() );
	$product_title = urlencode( get_the_title() );
	
	// Facebook
	echo '<a href="https://www.facebook.com/sharer/sharer.php?u=' . esc_attr( $product_url ) . '" class="sharing-link facebook" target="_blank" rel="noopener noreferrer">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" fill="currentColor"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/></svg>';
	echo '<span class="screen-reader-text">' . esc_html__( 'Share on Facebook', 'aqualuxe' ) . '</span>';
	echo '</a>';
	
	// Twitter
	echo '<a href="https://twitter.com/intent/tweet?url=' . esc_attr( $product_url ) . '&text=' . esc_attr( $product_title ) . '" class="sharing-link twitter" target="_blank" rel="noopener noreferrer">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" fill="currentColor"><path d="M459.37 151.716c.325 4.548.325 9.097.325 13.645 0 138.72-105.583 298.558-298.558 298.558-59.452 0-114.68-17.219-161.137-47.106 8.447.974 16.568 1.299 25.34 1.299 49.055 0 94.213-16.568 130.274-44.832-46.132-.975-84.792-31.188-98.112-72.772 6.498.974 12.995 1.624 19.818 1.624 9.421 0 18.843-1.3 27.614-3.573-48.081-9.747-84.143-51.98-84.143-102.985v-1.299c13.969 7.797 30.214 12.67 47.431 13.319-28.264-18.843-46.781-51.005-46.781-87.391 0-19.492 5.197-37.36 14.294-52.954 51.655 63.675 129.3 105.258 216.365 109.807-1.624-7.797-2.599-15.918-2.599-24.04 0-57.828 46.782-104.934 104.934-104.934 30.213 0 57.502 12.67 76.67 33.137 23.715-4.548 46.456-13.32 66.599-25.34-7.798 24.366-24.366 44.833-46.132 57.827 21.117-2.273 41.584-8.122 60.426-16.243-14.292 20.791-32.161 39.308-52.628 54.253z"/></svg>';
	echo '<span class="screen-reader-text">' . esc_html__( 'Share on Twitter', 'aqualuxe' ) . '</span>';
	echo '</a>';
	
	// Pinterest
	$product_image = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
	$product_image_url = $product_image ? urlencode( $product_image[0] ) : '';
	
	echo '<a href="https://pinterest.com/pin/create/button/?url=' . esc_attr( $product_url ) . '&media=' . esc_attr( $product_image_url ) . '&description=' . esc_attr( $product_title ) . '" class="sharing-link pinterest" target="_blank" rel="noopener noreferrer">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512" fill="currentColor"><path d="M204 6.5C101.4 6.5 0 74.9 0 185.6 0 256 39.6 296 63.6 296c9.9 0 15.6-27.6 15.6-35.4 0-9.3-23.7-29.1-23.7-67.8 0-80.4 61.2-137.4 140.4-137.4 68.1 0 118.5 38.7 118.5 109.8 0 53.1-21.3 152.7-90.3 152.7-24.9 0-46.2-18-46.2-43.8 0-37.8 26.4-74.4 26.4-113.4 0-66.2-93.9-54.2-93.9 25.8 0 16.8 2.1 35.4 9.6 50.7-13.8 59.4-42 147.9-42 209.1 0 18.9 2.7 37.5 4.5 56.4 3.4 3.8 1.7 3.4 6.9 1.5 50.4-69 48.6-82.5 71.4-172.8 12.3 23.4 44.1 36 69.3 36 106.2 0 153.9-103.5 153.9-196.8C384 71.3 298.2 6.5 204 6.5z"/></svg>';
	echo '<span class="screen-reader-text">' . esc_html__( 'Share on Pinterest', 'aqualuxe' ) . '</span>';
	echo '</a>';
	
	// Email
	echo '<a href="mailto:?subject=' . esc_attr( $product_title ) . '&body=' . esc_attr( $product_url ) . '" class="sharing-link email">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" /><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" /></svg>';
	echo '<span class="screen-reader-text">' . esc_html__( 'Share via Email', 'aqualuxe' ) . '</span>';
	echo '</a>';
	
	echo '</div>';
	echo '</div>';
}

/**
 * Display the WooCommerce cart
 */
function aqualuxe_woocommerce_cart() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<div class="woocommerce-cart-container">';
	echo do_shortcode( '[woocommerce_cart]' );
	echo '</div>';
}

/**
 * Display the WooCommerce checkout
 */
function aqualuxe_woocommerce_checkout() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<div class="woocommerce-checkout-container">';
	echo do_shortcode( '[woocommerce_checkout]' );
	echo '</div>';
}

/**
 * Display the WooCommerce account
 */
function aqualuxe_woocommerce_account() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<div class="woocommerce-account-container">';
	echo do_shortcode( '[woocommerce_my_account]' );
	echo '</div>';
}

/**
 * Display the WooCommerce order tracking
 */
function aqualuxe_woocommerce_order_tracking() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<div class="woocommerce-order-tracking-container">';
	echo do_shortcode( '[woocommerce_order_tracking]' );
	echo '</div>';
}

/**
 * Display the WooCommerce products
 *
 * @param array $args Query args.
 */
function aqualuxe_woocommerce_products( $args = array() ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	$default_args = array(
		'limit'        => 4,
		'columns'      => 4,
		'orderby'      => 'date',
		'order'        => 'DESC',
		'category'     => '',
		'cat_operator' => 'IN',
	);
	
	$args = wp_parse_args( $args, $default_args );
	
	$shortcode_args = array(
		'limit'        => $args['limit'],
		'columns'      => $args['columns'],
		'orderby'      => $args['orderby'],
		'order'        => $args['order'],
		'category'     => $args['category'],
		'cat_operator' => $args['cat_operator'],
	);
	
	$shortcode = '[products';
	
	foreach ( $shortcode_args as $key => $value ) {
		if ( ! empty( $value ) ) {
			$shortcode .= ' ' . $key . '="' . $value . '"';
		}
	}
	
	$shortcode .= ']';
	
	echo do_shortcode( $shortcode );
}

/**
 * Display the WooCommerce featured products
 *
 * @param int $limit Number of products to display.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_featured_products( $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[products limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '" visibility="featured"]' );
}

/**
 * Display the WooCommerce sale products
 *
 * @param int $limit Number of products to display.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_sale_products( $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[products limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '" on_sale="true"]' );
}

/**
 * Display the WooCommerce best selling products
 *
 * @param int $limit Number of products to display.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_best_selling_products( $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[products limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '" best_selling="true"]' );
}

/**
 * Display the WooCommerce top rated products
 *
 * @param int $limit Number of products to display.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_top_rated_products( $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[products limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '" orderby="rating"]' );
}

/**
 * Display the WooCommerce recent products
 *
 * @param int $limit Number of products to display.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_recent_products( $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[products limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '" orderby="date" order="DESC"]' );
}

/**
 * Display the WooCommerce product categories
 *
 * @param int $limit Number of categories to display.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_product_categories( $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[product_categories limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '"]' );
}

/**
 * Display the WooCommerce product attribute
 *
 * @param string $attribute Attribute name.
 * @param int $limit Number of products to display.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_product_attribute( $attribute, $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[products limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '" attribute="' . esc_attr( $attribute ) . '"]' );
}

/**
 * Display the WooCommerce product tag
 *
 * @param string $tag Tag name.
 * @param int $limit Number of products to display.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_product_tag( $tag, $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[products limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '" tag="' . esc_attr( $tag ) . '"]' );
}

/**
 * Display the WooCommerce product category
 *
 * @param string $category Category name.
 * @param int $limit Number of products to display.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_product_category( $category, $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[products limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '" category="' . esc_attr( $category ) . '"]' );
}

/**
 * Display the WooCommerce product ids
 *
 * @param string $ids Product IDs.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_product_ids( $ids, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[products ids="' . esc_attr( $ids ) . '" columns="' . esc_attr( $columns ) . '"]' );
}

/**
 * Display the WooCommerce product sku
 *
 * @param string $skus Product SKUs.
 * @param int $columns Number of columns.
 */
function aqualuxe_woocommerce_product_sku( $skus, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[products skus="' . esc_attr( $skus ) . '" columns="' . esc_attr( $columns ) . '"]' );
}

/**
 * Display the WooCommerce product attributes
 */
function aqualuxe_woocommerce_product_attributes() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	$attributes = $product->get_attributes();
	
	if ( empty( $attributes ) ) {
		return;
	}
	
	echo '<div class="product-attributes">';
	echo '<h3 class="attributes-title">' . esc_html__( 'Product Attributes', 'aqualuxe' ) . '</h3>';
	echo '<table class="attributes-table">';
	
	foreach ( $attributes as $attribute ) {
		if ( $attribute->get_visible() ) {
			echo '<tr>';
			echo '<th>' . esc_html( wc_attribute_label( $attribute->get_name() ) ) . '</th>';
			echo '<td>';
			
			if ( $attribute->is_taxonomy() ) {
				$values = wc_get_product_terms( $product->get_id(), $attribute->get_name(), array( 'fields' => 'names' ) );
				echo esc_html( implode( ', ', $values ) );
			} else {
				$values = $attribute->get_options();
				echo esc_html( implode( ', ', $values ) );
			}
			
			echo '</td>';
			echo '</tr>';
		}
	}
	
	echo '</table>';
	echo '</div>';
}

/**
 * Display the WooCommerce product dimensions
 */
function aqualuxe_woocommerce_product_dimensions() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	if ( ! $product->has_dimensions() ) {
		return;
	}
	
	echo '<div class="product-dimensions">';
	echo '<h3 class="dimensions-title">' . esc_html__( 'Dimensions', 'aqualuxe' ) . '</h3>';
	echo '<p class="dimensions">' . esc_html( wc_format_dimensions( $product->get_dimensions( false ) ) ) . '</p>';
	echo '</div>';
}

/**
 * Display the WooCommerce product weight
 */
function aqualuxe_woocommerce_product_weight() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	if ( ! $product->has_weight() ) {
		return;
	}
	
	echo '<div class="product-weight">';
	echo '<h3 class="weight-title">' . esc_html__( 'Weight', 'aqualuxe' ) . '</h3>';
	echo '<p class="weight">' . esc_html( wc_format_weight( $product->get_weight() ) ) . '</p>';
	echo '</div>';
}

/**
 * Display the WooCommerce product stock
 */
function aqualuxe_woocommerce_product_stock() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	echo '<div class="product-stock">';
	echo '<h3 class="stock-title">' . esc_html__( 'Availability', 'aqualuxe' ) . '</h3>';
	echo '<p class="stock ' . esc_attr( $product->get_availability()['class'] ) . '">' . esc_html( $product->get_availability()['availability'] ) . '</p>';
	echo '</div>';
}

/**
 * Display the WooCommerce product sku
 */
function aqualuxe_woocommerce_product_sku_display() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	if ( ! $product->get_sku() ) {
		return;
	}
	
	echo '<div class="product-sku">';
	echo '<h3 class="sku-title">' . esc_html__( 'SKU', 'aqualuxe' ) . '</h3>';
	echo '<p class="sku">' . esc_html( $product->get_sku() ) . '</p>';
	echo '</div>';
}

/**
 * Display the WooCommerce product categories
 */
function aqualuxe_woocommerce_product_categories_display() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	echo '<div class="product-categories">';
	echo '<h3 class="categories-title">' . esc_html__( 'Categories', 'aqualuxe' ) . '</h3>';
	echo '<p class="categories">' . wc_get_product_category_list( $product->get_id(), ', ' ) . '</p>';
	echo '</div>';
}

/**
 * Display the WooCommerce product tags
 */
function aqualuxe_woocommerce_product_tags_display() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	$tags = wc_get_product_tag_list( $product->get_id() );
	
	if ( ! $tags ) {
		return;
	}
	
	echo '<div class="product-tags">';
	echo '<h3 class="tags-title">' . esc_html__( 'Tags', 'aqualuxe' ) . '</h3>';
	echo '<p class="tags">' . $tags . '</p>';
	echo '</div>';
}

/**
 * Display the WooCommerce product additional information
 */
function aqualuxe_woocommerce_product_additional_information() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	wc_display_product_attributes( $product );
}

/**
 * Display the WooCommerce product reviews
 */
function aqualuxe_woocommerce_product_reviews() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	comments_template();
}

/**
 * Display the WooCommerce product description
 */
function aqualuxe_woocommerce_product_description() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	the_content();
}

/**
 * Display the WooCommerce product short description
 */
function aqualuxe_woocommerce_product_short_description() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_template_single_excerpt();
}

/**
 * Display the WooCommerce product title
 */
function aqualuxe_woocommerce_product_title() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_template_single_title();
}

/**
 * Display the WooCommerce product images
 */
function aqualuxe_woocommerce_product_images() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_show_product_images();
}

/**
 * Display the WooCommerce product price
 */
function aqualuxe_woocommerce_product_price_display() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_template_single_price();
}

/**
 * Display the WooCommerce product add to cart
 */
function aqualuxe_woocommerce_product_add_to_cart() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_template_single_add_to_cart();
}

/**
 * Display the WooCommerce product rating
 */
function aqualuxe_woocommerce_product_rating_display() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_template_single_rating();
}

/**
 * Display the WooCommerce product meta
 */
function aqualuxe_woocommerce_product_meta_display() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_template_single_meta();
}

/**
 * Display the WooCommerce product sharing
 */
function aqualuxe_woocommerce_product_sharing_display() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_template_single_sharing();
}

/**
 * Display the WooCommerce product data tabs
 */
function aqualuxe_woocommerce_product_data_tabs() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_output_product_data_tabs();
}

/**
 * Display the WooCommerce product related products
 */
function aqualuxe_woocommerce_product_related_products() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_output_related_products();
}

/**
 * Display the WooCommerce product upsells
 */
function aqualuxe_woocommerce_product_upsells() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_upsell_display();
}

/**
 * Display the WooCommerce product cross sells
 */
function aqualuxe_woocommerce_product_cross_sells() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	global $product;
	
	if ( ! $product ) {
		return;
	}
	
	woocommerce_cross_sell_display();
}

/**
 * Display the WooCommerce product recently viewed
 */
function aqualuxe_woocommerce_product_recently_viewed() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[recently_viewed_products]' );
}

/**
 * Display the WooCommerce product recently viewed
 */
function aqualuxe_woocommerce_product_recently_viewed_custom( $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo do_shortcode( '[recently_viewed_products limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '"]' );
}

/**
 * Display the WooCommerce product recently viewed
 */
function aqualuxe_woocommerce_product_recently_viewed_with_title( $limit = 4, $columns = 4 ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	echo '<div class="recently-viewed-products">';
	echo '<h2 class="recently-viewed-title">' . esc_html__( 'Recently Viewed Products', 'aqualuxe' ) . '</h2>';
	echo do_shortcode( '[recently_viewed_products limit="' . esc_attr( $limit ) . '" columns="' . esc_attr( $columns ) . '"]' );
	echo '</div>';
}