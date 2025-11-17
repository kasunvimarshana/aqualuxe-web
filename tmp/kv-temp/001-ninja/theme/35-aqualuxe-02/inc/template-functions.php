<?php

/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package AquaLuxe
 */

/**
 * Add a pingback url auto-discovery header for single posts, pages, or attachments.
 */
function aqualuxe_pingback_header()
{
	if (is_singular() && pings_open()) {
		printf('<link rel="pingback" href="%s">', esc_url(get_bloginfo('pingback_url')));
	}
}
add_action('wp_head', 'aqualuxe_pingback_header');

/**
 * Add custom classes to the body class
 *
 * @param array $classes Classes for the body element.
 * @return array
 */
function aqualuxe_body_classes($classes)
{
	// Adds a class of hfeed to non-singular pages.
	if (! is_singular()) {
		$classes[] = 'hfeed';
	}

	// Adds a class of no-sidebar when there is no sidebar present.
	if (! is_active_sidebar('sidebar-1')) {
		$classes[] = 'no-sidebar';
	}

	// Add class for header layout
	$header_layout = get_theme_mod('aqualuxe_header_layout', 'default');
	$classes[] = 'header-layout-' . $header_layout;

	// Add class for footer layout
	$footer_layout = get_theme_mod('aqualuxe_footer_layout', 'default');
	$classes[] = 'footer-layout-' . $footer_layout;

	// Add class for container type
	$container_type = get_theme_mod('aqualuxe_container_type', 'contained');
	$classes[] = 'container-' . $container_type;

	// Add class for sidebar position
	$sidebar_position = get_theme_mod('aqualuxe_sidebar_position', 'right');
	$classes[] = 'sidebar-' . $sidebar_position;

	// Add class if WooCommerce is active
	if (class_exists('WooCommerce')) {
		$classes[] = 'woocommerce-active';
	}

	return $classes;
}
add_filter('body_class', 'aqualuxe_body_classes');

/**
 * Add a custom excerpt length
 *
 * @param int $length Excerpt length.
 * @return int
 */
function aqualuxe_excerpt_length($length)
{
	return get_theme_mod('aqualuxe_excerpt_length', 55);
}
add_filter('excerpt_length', 'aqualuxe_excerpt_length');

/**
 * Change the excerpt more string
 *
 * @param string $more The excerpt more string.
 * @return string
 */
function aqualuxe_excerpt_more($more)
{
	return get_theme_mod('aqualuxe_excerpt_more', '&hellip;');
}
add_filter('excerpt_more', 'aqualuxe_excerpt_more');

/**
 * Add custom image sizes
 */
function aqualuxe_add_image_sizes()
{
	add_image_size('aqualuxe-featured', 1200, 600, true);
	add_image_size('aqualuxe-blog-grid', 600, 400, true);
	add_image_size('aqualuxe-portfolio', 600, 450, true);
}
add_action('after_setup_theme', 'aqualuxe_add_image_sizes');

/**
 * Register custom image sizes with WordPress
 *
 * @param array $sizes Image sizes.
 * @return array
 */
function aqualuxe_custom_image_sizes($sizes)
{
	return array_merge($sizes, array(
		'aqualuxe-featured' => __('Featured Image', 'aqualuxe'),
		'aqualuxe-blog-grid' => __('Blog Grid', 'aqualuxe'),
		'aqualuxe-portfolio' => __('Portfolio', 'aqualuxe'),
	));
}
add_filter('image_size_names_choose', 'aqualuxe_custom_image_sizes');

/**
 * Register portfolio post type
 */
function aqualuxe_register_portfolio_post_type()
{
	$labels = array(
		'name'                  => _x('Portfolio', 'Post Type General Name', 'aqualuxe'),
		'singular_name'         => _x('Portfolio Item', 'Post Type Singular Name', 'aqualuxe'),
		'menu_name'             => __('Portfolio', 'aqualuxe'),
		'name_admin_bar'        => __('Portfolio', 'aqualuxe'),
		'archives'              => __('Portfolio Archives', 'aqualuxe'),
		'attributes'            => __('Portfolio Attributes', 'aqualuxe'),
		'parent_item_colon'     => __('Parent Item:', 'aqualuxe'),
		'all_items'             => __('All Items', 'aqualuxe'),
		'add_new_item'          => __('Add New Item', 'aqualuxe'),
		'add_new'               => __('Add New', 'aqualuxe'),
		'new_item'              => __('New Item', 'aqualuxe'),
		'edit_item'             => __('Edit Item', 'aqualuxe'),
		'update_item'           => __('Update Item', 'aqualuxe'),
		'view_item'             => __('View Item', 'aqualuxe'),
		'view_items'            => __('View Items', 'aqualuxe'),
		'search_items'          => __('Search Item', 'aqualuxe'),
		'not_found'             => __('Not found', 'aqualuxe'),
		'not_found_in_trash'    => __('Not found in Trash', 'aqualuxe'),
		'featured_image'        => __('Featured Image', 'aqualuxe'),
		'set_featured_image'    => __('Set featured image', 'aqualuxe'),
		'remove_featured_image' => __('Remove featured image', 'aqualuxe'),
		'use_featured_image'    => __('Use as featured image', 'aqualuxe'),
		'insert_into_item'      => __('Insert into item', 'aqualuxe'),
		'uploaded_to_this_item' => __('Uploaded to this item', 'aqualuxe'),
		'items_list'            => __('Items list', 'aqualuxe'),
		'items_list_navigation' => __('Items list navigation', 'aqualuxe'),
		'filter_items_list'     => __('Filter items list', 'aqualuxe'),
	);
	$args = array(
		'label'                 => __('Portfolio Item', 'aqualuxe'),
		'description'           => __('Portfolio items for showcasing your work', 'aqualuxe'),
		'labels'                => $labels,
		'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
		'taxonomies'            => array('portfolio_category', 'portfolio_tag'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-portfolio',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => true,
	);
	register_post_type('portfolio', $args);
}
add_action('init', 'aqualuxe_register_portfolio_post_type');

/**
 * Register portfolio taxonomies
 */
function aqualuxe_register_portfolio_taxonomies()
{
	// Register Portfolio Category taxonomy
	$labels = array(
		'name'                       => _x('Portfolio Categories', 'Taxonomy General Name', 'aqualuxe'),
		'singular_name'              => _x('Portfolio Category', 'Taxonomy Singular Name', 'aqualuxe'),
		'menu_name'                  => __('Categories', 'aqualuxe'),
		'all_items'                  => __('All Categories', 'aqualuxe'),
		'parent_item'                => __('Parent Category', 'aqualuxe'),
		'parent_item_colon'          => __('Parent Category:', 'aqualuxe'),
		'new_item_name'              => __('New Category Name', 'aqualuxe'),
		'add_new_item'               => __('Add New Category', 'aqualuxe'),
		'edit_item'                  => __('Edit Category', 'aqualuxe'),
		'update_item'                => __('Update Category', 'aqualuxe'),
		'view_item'                  => __('View Category', 'aqualuxe'),
		'separate_items_with_commas' => __('Separate categories with commas', 'aqualuxe'),
		'add_or_remove_items'        => __('Add or remove categories', 'aqualuxe'),
		'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
		'popular_items'              => __('Popular Categories', 'aqualuxe'),
		'search_items'               => __('Search Categories', 'aqualuxe'),
		'not_found'                  => __('Not Found', 'aqualuxe'),
		'no_terms'                   => __('No categories', 'aqualuxe'),
		'items_list'                 => __('Categories list', 'aqualuxe'),
		'items_list_navigation'      => __('Categories list navigation', 'aqualuxe'),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy('portfolio_category', array('portfolio'), $args);

	// Register Portfolio Tag taxonomy
	$labels = array(
		'name'                       => _x('Portfolio Tags', 'Taxonomy General Name', 'aqualuxe'),
		'singular_name'              => _x('Portfolio Tag', 'Taxonomy Singular Name', 'aqualuxe'),
		'menu_name'                  => __('Tags', 'aqualuxe'),
		'all_items'                  => __('All Tags', 'aqualuxe'),
		'parent_item'                => __('Parent Tag', 'aqualuxe'),
		'parent_item_colon'          => __('Parent Tag:', 'aqualuxe'),
		'new_item_name'              => __('New Tag Name', 'aqualuxe'),
		'add_new_item'               => __('Add New Tag', 'aqualuxe'),
		'edit_item'                  => __('Edit Tag', 'aqualuxe'),
		'update_item'                => __('Update Tag', 'aqualuxe'),
		'view_item'                  => __('View Tag', 'aqualuxe'),
		'separate_items_with_commas' => __('Separate tags with commas', 'aqualuxe'),
		'add_or_remove_items'        => __('Add or remove tags', 'aqualuxe'),
		'choose_from_most_used'      => __('Choose from the most used', 'aqualuxe'),
		'popular_items'              => __('Popular Tags', 'aqualuxe'),
		'search_items'               => __('Search Tags', 'aqualuxe'),
		'not_found'                  => __('Not Found', 'aqualuxe'),
		'no_terms'                   => __('No tags', 'aqualuxe'),
		'items_list'                 => __('Tags list', 'aqualuxe'),
		'items_list_navigation'      => __('Tags list navigation', 'aqualuxe'),
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'show_in_rest'               => true,
	);
	register_taxonomy('portfolio_tag', array('portfolio'), $args);
}
add_action('init', 'aqualuxe_register_portfolio_taxonomies');

/**
 * Display social icons
 */
function aqualuxe_social_icons()
{
	$facebook = get_theme_mod('aqualuxe_facebook_url', '');
	$twitter = get_theme_mod('aqualuxe_twitter_url', '');
	$instagram = get_theme_mod('aqualuxe_instagram_url', '');
	$linkedin = get_theme_mod('aqualuxe_linkedin_url', '');
	$youtube = get_theme_mod('aqualuxe_youtube_url', '');
	$pinterest = get_theme_mod('aqualuxe_pinterest_url', '');

	if (empty($facebook) && empty($twitter) && empty($instagram) && empty($linkedin) && empty($youtube) && empty($pinterest)) {
		return;
	}

	echo '<div class="aqualuxe-social-icons">';

	if (! empty($facebook)) {
		echo '<a href="' . esc_url($facebook) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Facebook', 'aqualuxe') . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path></svg>';
		echo '</a>';
	}

	if (! empty($twitter)) {
		echo '<a href="' . esc_url($twitter) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Twitter', 'aqualuxe') . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>';
		echo '</a>';
	}

	if (! empty($instagram)) {
		echo '<a href="' . esc_url($instagram) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Instagram', 'aqualuxe') . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line></svg>';
		echo '</a>';
	}

	if (! empty($linkedin)) {
		echo '<a href="' . esc_url($linkedin) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('LinkedIn', 'aqualuxe') . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>';
		echo '</a>';
	}

	if (! empty($youtube)) {
		echo '<a href="' . esc_url($youtube) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('YouTube', 'aqualuxe') . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path><polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon></svg>';
		echo '</a>';
	}

	if (! empty($pinterest)) {
		echo '<a href="' . esc_url($pinterest) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__('Pinterest', 'aqualuxe') . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M8 12a4 4 0 1 0 8 0 4 4 0 0 0-8 0z"></path><path d="M12 2v2"></path><path d="M12 20v2"></path><path d="M4.93 4.93l1.41 1.41"></path><path d="M17.66 17.66l1.41 1.41"></path><path d="M2 12h2"></path><path d="M20 12h2"></path><path d="M4.93 19.07l1.41-1.41"></path><path d="M17.66 6.34l1.41-1.41"></path></svg>';
		echo '</a>';
	}

	echo '</div>';
}

/**
 * Display breadcrumbs
 */
if (! function_exists('aqualuxe_breadcrumbs')) :
	function aqualuxe_breadcrumbs()
	{
		// Check if breadcrumbs are enabled
		$show_breadcrumbs = get_theme_mod('aqualuxe_show_breadcrumbs', true);
		if (! $show_breadcrumbs) {
			return;
		}

		// Don't display on the homepage
		if (is_front_page()) {
			return;
		}

		// Define
		$delimiter = '<span class="aqualuxe-breadcrumb-delimiter">/</span>';
		$home = __('Home', 'aqualuxe');
		$before = '<span class="current">';
		$after = '</span>';

		// Start the breadcrumb
		echo '<div class="aqualuxe-breadcrumbs">';
		echo '<div class="aqualuxe-container">';
		echo '<a href="' . esc_url(home_url()) . '">' . esc_html($home) . '</a> ' . $delimiter . ' ';

		if (is_category()) {
			$thisCat = get_category(get_query_var('cat'), false);
			if ($thisCat->parent != 0) {
				echo get_category_parents($thisCat->parent, true, ' ' . $delimiter . ' ');
			}
			echo $before . single_cat_title('', false) . $after;
		} elseif (is_search()) {
			echo $before . __('Search Results for', 'aqualuxe') . ': "' . get_search_query() . '"' . $after;
		} elseif (is_day()) {
			echo '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo '<a href="' . esc_url(get_month_link(get_the_time('Y'), get_the_time('m'))) . '">' . get_the_time('F') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('d') . $after;
		} elseif (is_month()) {
			echo '<a href="' . esc_url(get_year_link(get_the_time('Y'))) . '">' . get_the_time('Y') . '</a> ' . $delimiter . ' ';
			echo $before . get_the_time('F') . $after;
		} elseif (is_year()) {
			echo $before . get_the_time('Y') . $after;
		} elseif (is_single() && ! is_attachment()) {
			if (get_post_type() != 'post') {
				$post_type = get_post_type_object(get_post_type());
				$slug = $post_type->rewrite;
				echo '<a href="' . esc_url(home_url('/' . $slug['slug'] . '/')) . '">' . $post_type->labels->singular_name . '</a>';
				if ($show_current == 1) {
					echo ' ' . $delimiter . ' ';
					echo $before . get_the_title() . $after;
				}
			} else {
				$cat = get_the_category();
				if ($cat) {
					$cat = $cat[0];
					echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
				}
				echo $before . get_the_title() . $after;
			}
		} elseif (! is_single() && ! is_page() && get_post_type() != 'post' && ! is_404()) {
			$post_type = get_post_type_object(get_post_type());
			echo $before . $post_type->labels->singular_name . $after;
		} elseif (is_attachment()) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID);
			if ($cat) {
				$cat = $cat[0];
				echo get_category_parents($cat, true, ' ' . $delimiter . ' ');
			}
			echo '<a href="' . esc_url(get_permalink($parent)) . '">' . $parent->post_title . '</a>';
			echo ' ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif (is_page() && ! $post->post_parent) {
			echo $before . get_the_title() . $after;
		} elseif (is_page() && $post->post_parent) {
			$parent_id = $post->post_parent;
			$breadcrumbs = array();
			while ($parent_id) {
				$page = get_page($parent_id);
				$breadcrumbs[] = '<a href="' . esc_url(get_permalink($page->ID)) . '">' . get_the_title($page->ID) . '</a>';
				$parent_id = $page->post_parent;
			}
			$breadcrumbs = array_reverse($breadcrumbs);
			for ($i = 0; $i < count($breadcrumbs); $i++) {
				echo $breadcrumbs[$i];
				if ($i != count($breadcrumbs) - 1) {
					echo ' ' . $delimiter . ' ';
				}
			}
			echo ' ' . $delimiter . ' ';
			echo $before . get_the_title() . $after;
		} elseif (is_tag()) {
			echo $before . __('Tag', 'aqualuxe') . ': ' . single_tag_title('', false) . $after;
		} elseif (is_author()) {
			global $author;
			$userdata = get_userdata($author);
			echo $before . __('Author', 'aqualuxe') . ': ' . $userdata->display_name . $after;
		} elseif (is_404()) {
			echo $before . __('Error 404', 'aqualuxe') . $after;
		}

		if (get_query_var('paged')) {
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				echo ' (';
			}
			echo __('Page', 'aqualuxe') . ' ' . get_query_var('paged');
			if (is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author()) {
				echo ')';
			}
		}

		echo '</div>';
		echo '</div>';
	}
endif;

/**
 * Display back to top button
 */
if (! function_exists('aqualuxe_back_to_top')) :
	function aqualuxe_back_to_top()
	{
		// Check if back to top is enabled
		$show_back_to_top = get_theme_mod('aqualuxe_show_back_to_top', true);
		if (! $show_back_to_top) {
			return;
		}

		echo '<a href="#" class="aqualuxe-back-to-top" aria-label="' . esc_attr__('Back to top', 'aqualuxe') . '">';
		echo '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>';
		echo '</a>';
	}
endif;
add_action('wp_footer', 'aqualuxe_back_to_top');

/**
 * Load more posts via AJAX for blog grid template
 */
function aqualuxe_load_more_posts()
{
	check_ajax_referer('aqualuxe-load-more', 'nonce');

	$paged = isset($_POST['page']) ? absint($_POST['page']) : 1;
	$category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

	$args = array(
		'post_type' => 'post',
		'posts_per_page' => get_theme_mod('aqualuxe_blog_grid_posts_per_page', 12),
		'paged' => $paged,
	);

	if (! empty($category)) {
		$args['category_name'] = $category;
	}

	$query = new WP_Query($args);

	ob_start();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			get_template_part('template-parts/content', 'grid');
		}
	}

	$html = ob_get_clean();

	wp_send_json_success(array(
		'html' => $html,
	));
}
add_action('wp_ajax_aqualuxe_load_more_posts', 'aqualuxe_load_more_posts');
add_action('wp_ajax_nopriv_aqualuxe_load_more_posts', 'aqualuxe_load_more_posts');

/**
 * Load more portfolio items via AJAX for portfolio template
 */
function aqualuxe_load_more_portfolio()
{
	check_ajax_referer('aqualuxe-load-more', 'nonce');

	$paged = isset($_POST['page']) ? absint($_POST['page']) : 1;
	$category = isset($_POST['category']) ? sanitize_text_field($_POST['category']) : '';

	$args = array(
		'post_type' => 'portfolio',
		'posts_per_page' => get_theme_mod('aqualuxe_portfolio_items_per_page', 12),
		'paged' => $paged,
	);

	if (! empty($category)) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'portfolio_category',
				'field' => 'slug',
				'terms' => $category,
			),
		);
	}

	$query = new WP_Query($args);

	ob_start();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			get_template_part('template-parts/content', 'portfolio');
		}
	}

	$html = ob_get_clean();

	wp_send_json_success(array(
		'html' => $html,
	));
}
add_action('wp_ajax_aqualuxe_load_more_portfolio', 'aqualuxe_load_more_portfolio');
add_action('wp_ajax_nopriv_aqualuxe_load_more_portfolio', 'aqualuxe_load_more_portfolio');

/**
 * Load more products via AJAX for WooCommerce
 */
function aqualuxe_load_more_products()
{
	check_ajax_referer('aqualuxe-woocommerce', 'nonce');

	$paged = isset($_POST['page']) ? absint($_POST['page']) : 1;

	$args = array(
		'post_type' => 'product',
		'posts_per_page' => get_option('woocommerce_catalog_columns', 4),
		'paged' => $paged,
	);

	$query = new WP_Query($args);

	ob_start();

	if ($query->have_posts()) {
		while ($query->have_posts()) {
			$query->the_post();
			wc_get_template_part('content', 'product');
		}
	}

	$html = ob_get_clean();

	wp_send_json_success(array(
		'html' => $html,
	));
}
add_action('wp_ajax_aqualuxe_load_more_products', 'aqualuxe_load_more_products');
add_action('wp_ajax_nopriv_aqualuxe_load_more_products', 'aqualuxe_load_more_products');
