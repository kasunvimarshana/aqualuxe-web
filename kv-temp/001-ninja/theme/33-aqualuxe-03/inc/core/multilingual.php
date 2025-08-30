<?php
/**
 * Multilingual Functions
 *
 * Functions for handling multilingual features throughout the theme.
 *
 * @package AquaLuxe
 */

/**
 * Check if a multilingual plugin is active.
 *
 * @return bool True if a multilingual plugin is active, false otherwise.
 */
function aqualuxe_is_multilingual() {
	return ( function_exists( 'pll_current_language' ) || defined( 'ICL_LANGUAGE_CODE' ) );
}

/**
 * Get the current language code.
 *
 * @return string The current language code.
 */
function aqualuxe_get_current_language() {
	if ( function_exists( 'pll_current_language' ) ) {
		return pll_current_language();
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		return ICL_LANGUAGE_CODE;
	}
	
	return get_locale();
}

/**
 * Get the default language code.
 *
 * @return string The default language code.
 */
function aqualuxe_get_default_language() {
	if ( function_exists( 'pll_default_language' ) ) {
		return pll_default_language();
	} elseif ( defined( 'ICL_SITEPRESS_VERSION' ) ) {
		global $sitepress;
		return $sitepress->get_default_language();
	}
	
	return get_locale();
}

/**
 * Check if the current language is the default language.
 *
 * @return bool True if the current language is the default language, false otherwise.
 */
function aqualuxe_is_default_language() {
	return aqualuxe_get_current_language() === aqualuxe_get_default_language();
}

/**
 * Get available languages.
 *
 * @return array Available languages.
 */
function aqualuxe_get_languages() {
	$languages = array();
	
	if ( function_exists( 'pll_languages_list' ) ) {
		$languages = pll_languages_list( array( 'fields' => 'slug' ) );
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		global $sitepress;
		$languages = array_keys( $sitepress->get_active_languages() );
	}
	
	return $languages;
}

/**
 * Get language name from code.
 *
 * @param string $code Language code.
 * @return string Language name.
 */
function aqualuxe_get_language_name( $code ) {
	if ( function_exists( 'pll_language_properties' ) ) {
		$properties = pll_language_properties( $code );
		return isset( $properties['name'] ) ? $properties['name'] : $code;
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		global $sitepress;
		$languages = $sitepress->get_active_languages();
		return isset( $languages[ $code ]['display_name'] ) ? $languages[ $code ]['display_name'] : $code;
	}
	
	return $code;
}

/**
 * Get language flag URL from code.
 *
 * @param string $code Language code.
 * @return string Language flag URL.
 */
function aqualuxe_get_language_flag( $code ) {
	if ( function_exists( 'pll_language_properties' ) ) {
		$properties = pll_language_properties( $code );
		return isset( $properties['flag_url'] ) ? $properties['flag_url'] : '';
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		global $sitepress;
		$languages = $sitepress->get_active_languages();
		return isset( $languages[ $code ]['country_flag_url'] ) ? $languages[ $code ]['country_flag_url'] : '';
	}
	
	return '';
}

/**
 * Get translated URL.
 *
 * @param string $url    URL to translate.
 * @param string $lang   Language code.
 * @return string Translated URL.
 */
function aqualuxe_get_translated_url( $url, $lang ) {
	if ( function_exists( 'pll_get_post' ) ) {
		$post_id = url_to_postid( $url );
		
		if ( $post_id ) {
			$translated_id = pll_get_post( $post_id, $lang );
			
			if ( $translated_id ) {
				return get_permalink( $translated_id );
			}
		}
		
		return pll_home_url( $lang );
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		return apply_filters( 'wpml_permalink', $url, $lang );
	}
	
	return $url;
}

/**
 * Get translated post ID.
 *
 * @param int    $post_id Post ID.
 * @param string $lang    Language code.
 * @return int Translated post ID.
 */
function aqualuxe_get_translated_post_id( $post_id, $lang ) {
	if ( function_exists( 'pll_get_post' ) ) {
		return pll_get_post( $post_id, $lang );
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		return apply_filters( 'wpml_object_id', $post_id, get_post_type( $post_id ), true, $lang );
	}
	
	return $post_id;
}

/**
 * Get translated term ID.
 *
 * @param int    $term_id  Term ID.
 * @param string $taxonomy Taxonomy name.
 * @param string $lang     Language code.
 * @return int Translated term ID.
 */
function aqualuxe_get_translated_term_id( $term_id, $taxonomy, $lang ) {
	if ( function_exists( 'pll_get_term' ) ) {
		return pll_get_term( $term_id, $lang );
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		return apply_filters( 'wpml_object_id', $term_id, $taxonomy, true, $lang );
	}
	
	return $term_id;
}

/**
 * Register a string for translation.
 *
 * @param string $name    String name.
 * @param string $value   String value.
 * @param string $context String context.
 */
function aqualuxe_register_string( $name, $value, $context = 'AquaLuxe' ) {
	if ( function_exists( 'pll_register_string' ) ) {
		pll_register_string( $name, $value, $context );
	} elseif ( function_exists( 'icl_register_string' ) ) {
		icl_register_string( $context, $name, $value );
	}
}

/**
 * Translate a registered string.
 *
 * @param string $name    String name.
 * @param string $value   String value.
 * @param string $context String context.
 * @return string Translated string.
 */
function aqualuxe_translate_string( $name, $value, $context = 'AquaLuxe' ) {
	if ( function_exists( 'pll__' ) ) {
		return pll__( $value );
	} elseif ( function_exists( 'icl_t' ) ) {
		return icl_t( $context, $name, $value );
	}
	
	return $value;
}

/**
 * Display language switcher.
 *
 * @param array $args Language switcher arguments.
 */
function aqualuxe_language_switcher( $args = array() ) {
	$defaults = array(
		'dropdown'      => true,
		'show_flags'    => true,
		'show_names'    => true,
		'display_names' => 'name',
		'echo'          => true,
	);
	
	$args = wp_parse_args( $args, $defaults );
	
	// Return if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return;
	}
	
	$output = '';
	$current_language = aqualuxe_get_current_language();
	$languages = aqualuxe_get_languages();
	
	if ( empty( $languages ) ) {
		return;
	}
	
	if ( $args['dropdown'] ) {
		$output .= '<div class="language-switcher dropdown">';
		$output .= '<button class="language-switcher-toggle flex items-center space-x-2 text-dark-700 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 focus:outline-none" aria-expanded="false" aria-haspopup="true">';
		
		if ( $args['show_flags'] ) {
			$flag_url = aqualuxe_get_language_flag( $current_language );
			if ( $flag_url ) {
				$output .= '<img src="' . esc_url( $flag_url ) . '" alt="' . esc_attr( aqualuxe_get_language_name( $current_language ) ) . '" class="w-5 h-auto">';
			}
		}
		
		if ( $args['show_names'] ) {
			$output .= '<span>' . esc_html( aqualuxe_get_language_name( $current_language ) ) . '</span>';
		}
		
		$output .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>';
		$output .= '</button>';
		
		$output .= '<div class="language-switcher-dropdown absolute right-0 mt-2 py-2 w-48 bg-white dark:bg-dark-800 rounded-md shadow-lg z-10 hidden">';
		
		foreach ( $languages as $language ) {
			$is_current = $language === $current_language;
			$language_url = aqualuxe_get_translated_url( $_SERVER['REQUEST_URI'], $language );
			
			$output .= '<a href="' . esc_url( $language_url ) . '" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-dark-700 ' . ( $is_current ? 'bg-gray-100 dark:bg-dark-700' : '' ) . '">';
			
			if ( $args['show_flags'] ) {
				$flag_url = aqualuxe_get_language_flag( $language );
				if ( $flag_url ) {
					$output .= '<img src="' . esc_url( $flag_url ) . '" alt="' . esc_attr( aqualuxe_get_language_name( $language ) ) . '" class="inline-block w-5 h-auto mr-2">';
				}
			}
			
			if ( $args['show_names'] ) {
				$output .= esc_html( aqualuxe_get_language_name( $language ) );
			}
			
			$output .= '</a>';
		}
		
		$output .= '</div>';
		$output .= '</div>';
		
		// Add JavaScript for dropdown functionality.
		$output .= '<script>
			document.addEventListener("DOMContentLoaded", function() {
				const toggles = document.querySelectorAll(".language-switcher-toggle");
				
				toggles.forEach(function(toggle) {
					toggle.addEventListener("click", function(e) {
						e.preventDefault();
						const dropdown = this.nextElementSibling;
						const expanded = this.getAttribute("aria-expanded") === "true";
						
						this.setAttribute("aria-expanded", !expanded);
						dropdown.classList.toggle("hidden");
						
						// Close dropdown when clicking outside.
						if (!expanded) {
							document.addEventListener("click", function closeDropdown(e) {
								if (!toggle.contains(e.target)) {
									toggle.setAttribute("aria-expanded", "false");
									dropdown.classList.add("hidden");
									document.removeEventListener("click", closeDropdown);
								}
							});
						}
					});
				});
			});
		</script>';
	} else {
		$output .= '<ul class="language-switcher-list flex space-x-4">';
		
		foreach ( $languages as $language ) {
			$is_current = $language === $current_language;
			$language_url = aqualuxe_get_translated_url( $_SERVER['REQUEST_URI'], $language );
			
			$output .= '<li class="language-item' . ( $is_current ? ' current-language' : '' ) . '">';
			$output .= '<a href="' . esc_url( $language_url ) . '" class="flex items-center' . ( $is_current ? ' text-primary-600 dark:text-primary-400' : ' text-dark-700 dark:text-white hover:text-primary-600 dark:hover:text-primary-400' ) . '">';
			
			if ( $args['show_flags'] ) {
				$flag_url = aqualuxe_get_language_flag( $language );
				if ( $flag_url ) {
					$output .= '<img src="' . esc_url( $flag_url ) . '" alt="' . esc_attr( aqualuxe_get_language_name( $language ) ) . '" class="w-5 h-auto' . ( $args['show_names'] ? ' mr-1' : '' ) . '">';
				}
			}
			
			if ( $args['show_names'] ) {
				$output .= '<span>' . esc_html( aqualuxe_get_language_name( $language ) ) . '</span>';
			}
			
			$output .= '</a>';
			$output .= '</li>';
		}
		
		$output .= '</ul>';
	}
	
	if ( $args['echo'] ) {
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	} else {
		return $output;
	}
}

/**
 * Register theme strings for translation.
 */
function aqualuxe_register_theme_strings() {
	// Skip if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return;
	}
	
	// Register theme mod strings.
	$translatable_theme_mods = array(
		'aqualuxe_header_button_text'       => __( 'Contact Us', 'aqualuxe' ),
		'aqualuxe_footer_copyright_text'     => __( '© {year} {site_title}. All rights reserved.', 'aqualuxe' ),
		'aqualuxe_blog_title'                => __( 'Blog', 'aqualuxe' ),
		'aqualuxe_blog_subtitle'             => __( 'Latest News & Updates', 'aqualuxe' ),
		'aqualuxe_archive_title_prefix'      => __( 'Archive:', 'aqualuxe' ),
		'aqualuxe_category_title_prefix'     => __( 'Category:', 'aqualuxe' ),
		'aqualuxe_tag_title_prefix'          => __( 'Tag:', 'aqualuxe' ),
		'aqualuxe_author_title_prefix'       => __( 'Author:', 'aqualuxe' ),
		'aqualuxe_search_title_prefix'       => __( 'Search Results for:', 'aqualuxe' ),
		'aqualuxe_404_title'                 => __( '404', 'aqualuxe' ),
		'aqualuxe_404_subtitle'              => __( 'Page Not Found', 'aqualuxe' ),
		'aqualuxe_404_text'                  => __( 'The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'aqualuxe' ),
		'aqualuxe_404_button_text'           => __( 'Back to Home', 'aqualuxe' ),
		'aqualuxe_breadcrumbs_home_text'     => __( 'Home', 'aqualuxe' ),
		'aqualuxe_read_more_text'            => __( 'Read More', 'aqualuxe' ),
		'aqualuxe_pagination_prev_text'      => __( 'Previous', 'aqualuxe' ),
		'aqualuxe_pagination_next_text'      => __( 'Next', 'aqualuxe' ),
		'aqualuxe_related_posts_title'       => __( 'Related Posts', 'aqualuxe' ),
		'aqualuxe_comments_title'            => __( 'Comments', 'aqualuxe' ),
		'aqualuxe_comment_form_title'        => __( 'Leave a Comment', 'aqualuxe' ),
		'aqualuxe_comment_form_submit_text'  => __( 'Submit Comment', 'aqualuxe' ),
	);
	
	foreach ( $translatable_theme_mods as $key => $default ) {
		$value = get_theme_mod( $key, $default );
		aqualuxe_register_string( $key, $value );
	}
	
	// Register WooCommerce strings if WooCommerce is active.
	if ( class_exists( 'WooCommerce' ) ) {
		$translatable_woocommerce_mods = array(
			'aqualuxe_shop_title'                => __( 'Shop', 'aqualuxe' ),
			'aqualuxe_shop_subtitle'             => __( 'Our Products', 'aqualuxe' ),
			'aqualuxe_product_related_title'     => __( 'Related Products', 'aqualuxe' ),
			'aqualuxe_product_upsell_title'      => __( 'You may also like', 'aqualuxe' ),
			'aqualuxe_cart_empty_text'           => __( 'Your cart is currently empty.', 'aqualuxe' ),
			'aqualuxe_cart_empty_button_text'    => __( 'Return to Shop', 'aqualuxe' ),
			'aqualuxe_checkout_title'            => __( 'Checkout', 'aqualuxe' ),
			'aqualuxe_checkout_subtitle'         => __( 'Complete Your Order', 'aqualuxe' ),
			'aqualuxe_account_title'             => __( 'My Account', 'aqualuxe' ),
			'aqualuxe_account_subtitle'          => __( 'Manage Your Account', 'aqualuxe' ),
		);
		
		foreach ( $translatable_woocommerce_mods as $key => $default ) {
			$value = get_theme_mod( $key, $default );
			aqualuxe_register_string( $key, $value, 'AquaLuxe WooCommerce' );
		}
	}
}
add_action( 'after_setup_theme', 'aqualuxe_register_theme_strings' );

/**
 * Translate theme mod.
 *
 * @param string $value Theme mod value.
 * @param string $key   Theme mod key.
 * @return string Translated theme mod value.
 */
function aqualuxe_translate_theme_mod( $value, $key ) {
	// Skip if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return $value;
	}
	
	// List of translatable theme mods.
	$translatable_mods = array(
		'aqualuxe_header_button_text',
		'aqualuxe_footer_copyright_text',
		'aqualuxe_blog_title',
		'aqualuxe_blog_subtitle',
		'aqualuxe_archive_title_prefix',
		'aqualuxe_category_title_prefix',
		'aqualuxe_tag_title_prefix',
		'aqualuxe_author_title_prefix',
		'aqualuxe_search_title_prefix',
		'aqualuxe_404_title',
		'aqualuxe_404_subtitle',
		'aqualuxe_404_text',
		'aqualuxe_404_button_text',
		'aqualuxe_breadcrumbs_home_text',
		'aqualuxe_read_more_text',
		'aqualuxe_pagination_prev_text',
		'aqualuxe_pagination_next_text',
		'aqualuxe_related_posts_title',
		'aqualuxe_comments_title',
		'aqualuxe_comment_form_title',
		'aqualuxe_comment_form_submit_text',
		'aqualuxe_shop_title',
		'aqualuxe_shop_subtitle',
		'aqualuxe_product_related_title',
		'aqualuxe_product_upsell_title',
		'aqualuxe_cart_empty_text',
		'aqualuxe_cart_empty_button_text',
		'aqualuxe_checkout_title',
		'aqualuxe_checkout_subtitle',
		'aqualuxe_account_title',
		'aqualuxe_account_subtitle',
	);
	
	if ( in_array( $key, $translatable_mods, true ) ) {
		$context = strpos( $key, 'woocommerce' ) !== false ? 'AquaLuxe WooCommerce' : 'AquaLuxe';
		return aqualuxe_translate_string( $key, $value, $context );
	}
	
	return $value;
}
add_filter( 'theme_mod_aqualuxe_header_button_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_footer_copyright_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_blog_title', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_blog_subtitle', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_archive_title_prefix', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_category_title_prefix', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_tag_title_prefix', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_author_title_prefix', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_search_title_prefix', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_404_title', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_404_subtitle', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_404_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_404_button_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_breadcrumbs_home_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_read_more_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_pagination_prev_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_pagination_next_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_related_posts_title', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_comments_title', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_comment_form_title', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_comment_form_submit_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_shop_title', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_shop_subtitle', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_product_related_title', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_product_upsell_title', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_cart_empty_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_cart_empty_button_text', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_checkout_title', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_checkout_subtitle', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_account_title', 'aqualuxe_translate_theme_mod', 10, 2 );
add_filter( 'theme_mod_aqualuxe_account_subtitle', 'aqualuxe_translate_theme_mod', 10, 2 );

/**
 * Add language attributes to HTML tag.
 *
 * @param string $output HTML language attributes.
 * @return string Modified HTML language attributes.
 */
function aqualuxe_language_attributes( $output ) {
	if ( aqualuxe_is_multilingual() ) {
		$language = aqualuxe_get_current_language();
		$output = 'lang="' . esc_attr( $language ) . '" dir="' . ( is_rtl() ? 'rtl' : 'ltr' ) . '"';
	}
	
	return $output;
}
add_filter( 'language_attributes', 'aqualuxe_language_attributes' );

/**
 * Add hreflang links to head.
 */
function aqualuxe_hreflang_links() {
	// Skip if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return;
	}
	
	$languages = aqualuxe_get_languages();
	
	if ( empty( $languages ) ) {
		return;
	}
	
	foreach ( $languages as $language ) {
		$url = aqualuxe_get_translated_url( $_SERVER['REQUEST_URI'], $language );
		echo '<link rel="alternate" hreflang="' . esc_attr( $language ) . '" href="' . esc_url( $url ) . '">' . "\n";
	}
	
	// Add x-default hreflang.
	$default_language = aqualuxe_get_default_language();
	$default_url = aqualuxe_get_translated_url( $_SERVER['REQUEST_URI'], $default_language );
	echo '<link rel="alternate" hreflang="x-default" href="' . esc_url( $default_url ) . '">' . "\n";
}
add_action( 'wp_head', 'aqualuxe_hreflang_links', 1 );

/**
 * Filter the archive title.
 *
 * @param string $title Archive title.
 * @return string Modified archive title.
 */
function aqualuxe_archive_title( $title ) {
	if ( is_category() ) {
		$title = sprintf( '%s %s', get_theme_mod( 'aqualuxe_category_title_prefix', __( 'Category:', 'aqualuxe' ) ), single_cat_title( '', false ) );
	} elseif ( is_tag() ) {
		$title = sprintf( '%s %s', get_theme_mod( 'aqualuxe_tag_title_prefix', __( 'Tag:', 'aqualuxe' ) ), single_tag_title( '', false ) );
	} elseif ( is_author() ) {
		$title = sprintf( '%s %s', get_theme_mod( 'aqualuxe_author_title_prefix', __( 'Author:', 'aqualuxe' ) ), get_the_author() );
	} elseif ( is_post_type_archive() ) {
		$title = post_type_archive_title( '', false );
	} elseif ( is_tax() ) {
		$title = sprintf( '%s %s', get_theme_mod( 'aqualuxe_archive_title_prefix', __( 'Archive:', 'aqualuxe' ) ), single_term_title( '', false ) );
	}
	
	return $title;
}
add_filter( 'get_the_archive_title', 'aqualuxe_archive_title' );

/**
 * Filter the search title.
 *
 * @param string $title Search title.
 * @return string Modified search title.
 */
function aqualuxe_search_title( $title ) {
	if ( is_search() ) {
		$title = sprintf( '%s %s', get_theme_mod( 'aqualuxe_search_title_prefix', __( 'Search Results for:', 'aqualuxe' ) ), get_search_query() );
	}
	
	return $title;
}
add_filter( 'get_the_archive_title', 'aqualuxe_search_title' );

/**
 * Filter the blog page title.
 *
 * @param string $title Blog page title.
 * @return string Modified blog page title.
 */
function aqualuxe_blog_title( $title ) {
	if ( is_home() && ! is_front_page() ) {
		$title = get_theme_mod( 'aqualuxe_blog_title', __( 'Blog', 'aqualuxe' ) );
	}
	
	return $title;
}
add_filter( 'single_post_title', 'aqualuxe_blog_title' );

/**
 * Add RTL support.
 */
function aqualuxe_rtl_support() {
	// Skip if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return;
	}
	
	// Check if current language is RTL.
	$is_rtl = false;
	
	if ( function_exists( 'pll_current_language' ) ) {
		$language = pll_current_language( 'locale' );
		$is_rtl = in_array( $language, array( 'ar', 'he', 'fa', 'ur' ), true );
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		global $sitepress;
		$language = $sitepress->get_locale( ICL_LANGUAGE_CODE );
		$is_rtl = in_array( $language, array( 'ar', 'he', 'fa', 'ur' ), true );
	}
	
	// Set RTL direction.
	if ( $is_rtl ) {
		global $wp_locale, $wp_styles;
		
		$wp_locale->text_direction = 'rtl';
		
		if ( ! is_a( $wp_styles, 'WP_Styles' ) ) {
			$wp_styles = new WP_Styles();
		}
		
		$wp_styles->text_direction = 'rtl';
	}
}
add_action( 'init', 'aqualuxe_rtl_support' );

/**
 * Add RTL stylesheet.
 */
function aqualuxe_rtl_stylesheet() {
	if ( is_rtl() ) {
		wp_enqueue_style( 'aqualuxe-rtl', get_template_directory_uri() . '/assets/css/rtl.css', array( 'aqualuxe-style' ), AQUALUXE_VERSION );
	}
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_rtl_stylesheet', 20 );

/**
 * Filter the locale.
 *
 * @param string $locale WordPress locale.
 * @return string Modified locale.
 */
function aqualuxe_locale( $locale ) {
	if ( aqualuxe_is_multilingual() ) {
		$current_language = aqualuxe_get_current_language();
		
		if ( function_exists( 'pll_current_language' ) ) {
			$locale = pll_current_language( 'locale' );
		} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
			global $sitepress;
			$locale = $sitepress->get_locale( $current_language );
		}
	}
	
	return $locale;
}
add_filter( 'locale', 'aqualuxe_locale' );

/**
 * Make URLs in content translatable.
 *
 * @param string $content Post content.
 * @return string Modified post content.
 */
function aqualuxe_translate_urls_in_content( $content ) {
	// Skip if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return $content;
	}
	
	// Skip if we're in the default language.
	if ( aqualuxe_is_default_language() ) {
		return $content;
	}
	
	// Get site URL.
	$site_url = site_url();
	$site_url_pattern = preg_quote( $site_url, '/' );
	
	// Replace URLs in content.
	$content = preg_replace_callback(
		'/(href|src)=(["\'])('. $site_url_pattern .'[^"\']+)(["\'])/i',
		function( $matches ) {
			$url = $matches[3];
			$translated_url = aqualuxe_get_translated_url( $url, aqualuxe_get_current_language() );
			return $matches[1] . '=' . $matches[2] . $translated_url . $matches[4];
		},
		$content
	);
	
	return $content;
}
add_filter( 'the_content', 'aqualuxe_translate_urls_in_content' );
add_filter( 'widget_text', 'aqualuxe_translate_urls_in_content' );

/**
 * Add language to AJAX URL.
 *
 * @param string $url AJAX URL.
 * @return string Modified AJAX URL.
 */
function aqualuxe_ajax_url( $url ) {
	if ( aqualuxe_is_multilingual() ) {
		$current_language = aqualuxe_get_current_language();
		$url = add_query_arg( 'lang', $current_language, $url );
	}
	
	return $url;
}
add_filter( 'admin_url', 'aqualuxe_ajax_url', 10, 1 );

/**
 * Add language to menu items.
 *
 * @param array $items Menu items.
 * @return array Modified menu items.
 */
function aqualuxe_nav_menu_objects( $items ) {
	// Skip if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return $items;
	}
	
	// Skip if we're in the default language.
	if ( aqualuxe_is_default_language() ) {
		return $items;
	}
	
	$current_language = aqualuxe_get_current_language();
	
	foreach ( $items as $key => $item ) {
		// Skip items with no URL.
		if ( empty( $item->url ) ) {
			continue;
		}
		
		// Skip external URLs.
		if ( strpos( $item->url, site_url() ) === false ) {
			continue;
		}
		
		// Translate URL.
		$items[ $key ]->url = aqualuxe_get_translated_url( $item->url, $current_language );
	}
	
	return $items;
}
add_filter( 'wp_nav_menu_objects', 'aqualuxe_nav_menu_objects' );

/**
 * Add language to custom logo.
 *
 * @param string $html Custom logo HTML.
 * @return string Modified custom logo HTML.
 */
function aqualuxe_custom_logo( $html ) {
	// Skip if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return $html;
	}
	
	// Skip if we're in the default language.
	if ( aqualuxe_is_default_language() ) {
		return $html;
	}
	
	$current_language = aqualuxe_get_current_language();
	
	// Replace home URL in logo HTML.
	$home_url = home_url( '/' );
	$translated_home_url = aqualuxe_get_translated_url( $home_url, $current_language );
	
	$html = str_replace( $home_url, $translated_home_url, $html );
	
	return $html;
}
add_filter( 'get_custom_logo', 'aqualuxe_custom_logo' );

/**
 * Add language to pagination links.
 *
 * @param string $link Pagination link.
 * @return string Modified pagination link.
 */
function aqualuxe_paginate_links( $link ) {
	// Skip if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return $link;
	}
	
	$current_language = aqualuxe_get_current_language();
	
	// Add language parameter to pagination links.
	if ( function_exists( 'pll_current_language' ) ) {
		$link = add_query_arg( 'lang', $current_language, $link );
	} elseif ( defined( 'ICL_LANGUAGE_CODE' ) ) {
		$link = add_query_arg( 'lang', $current_language, $link );
	}
	
	return $link;
}
add_filter( 'paginate_links', 'aqualuxe_paginate_links' );

/**
 * Add language to comment form.
 *
 * @param array $defaults Comment form defaults.
 * @return array Modified comment form defaults.
 */
function aqualuxe_comment_form_defaults( $defaults ) {
	// Skip if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return $defaults;
	}
	
	$current_language = aqualuxe_get_current_language();
	
	// Add language field to comment form.
	$defaults['fields']['lang'] = '<input type="hidden" name="lang" value="' . esc_attr( $current_language ) . '">';
	
	return $defaults;
}
add_filter( 'comment_form_defaults', 'aqualuxe_comment_form_defaults' );

/**
 * Add language to search form.
 *
 * @param string $form Search form HTML.
 * @return string Modified search form HTML.
 */
function aqualuxe_search_form( $form ) {
	// Skip if no multilingual plugin is active.
	if ( ! aqualuxe_is_multilingual() ) {
		return $form;
	}
	
	$current_language = aqualuxe_get_current_language();
	
	// Add language field to search form.
	$form = str_replace( '</form>', '<input type="hidden" name="lang" value="' . esc_attr( $current_language ) . '"></form>', $form );
	
	return $form;
}
add_filter( 'get_search_form', 'aqualuxe_search_form' );