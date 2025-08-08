<?php
/**
 * AquaLuxe Demo Content Importer
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Add demo import page to admin menu
add_action( 'admin_menu', 'aqualuxe_add_demo_import_page' );
function aqualuxe_add_demo_import_page() {
	add_theme_page(
		__( 'Import Demo Data', 'aqualuxe' ),
		__( 'Import Demo Data', 'aqualuxe' ),
		'manage_options',
		'aqualuxe-demo-import',
		'aqualuxe_demo_import_page'
	);
}

// Demo import page content
function aqualuxe_demo_import_page() {
	// Check if user has permission
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( __( 'You do not have sufficient permissions to access this page.', 'aqualuxe' ) );
	}
	
	// Handle demo import
	if ( isset( $_POST['import_demo'] ) && wp_verify_nonce( $_POST['aqualuxe_demo_import_nonce'], 'aqualuxe_demo_import' ) ) {
		$demo_name = sanitize_text_field( $_POST['demo_name'] );
		$result = aqualuxe_import_demo_content( $demo_name );
		
		if ( $result === true ) {
			echo '<div class="notice notice-success"><p>' . __( 'Demo content imported successfully!', 'aqualuxe' ) . '</p></div>';
		} else {
			echo '<div class="notice notice-error"><p>' . sprintf( __( 'Demo import failed: %s', 'aqualuxe' ), $result ) . '</p></div>';
		}
	}
	
	// Demo data
	$demos = array(
		'main' => array(
			'name' => __( 'Main Demo', 'aqualuxe' ),
			'description' => __( 'Full demo content including pages, posts, products, and settings.', 'aqualuxe' )
		),
		'minimal' => array(
			'name' => __( 'Minimal Demo', 'aqualuxe' ),
			'description' => __( 'Minimal demo content with essential pages and settings.', 'aqualuxe' )
		)
	);
	?>
	<div class="wrap aqualuxe-demo-import-page">
		<h1><?php _e( 'AquaLuxe Demo Import', 'aqualuxe' ); ?></h1>
		
		<p><?php _e( 'Import demo content to quickly set up your site with sample data.', 'aqualuxe' ); ?></p>
		
		<?php foreach ( $demos as $demo_key => $demo ) : ?>
			<div class="demo-import-box">
				<h2><?php echo esc_html( $demo['name'] ); ?></h2>
				<p><?php echo esc_html( $demo['description'] ); ?></p>
				
				<form method="post">
					<?php wp_nonce_field( 'aqualuxe_demo_import', 'aqualuxe_demo_import_nonce' ); ?>
					<input type="hidden" name="demo_name" value="<?php echo esc_attr( $demo_key ); ?>">
					<input type="submit" name="import_demo" class="import-button button-primary" value="<?php _e( 'Import Demo', 'aqualuxe' ); ?>">
				</form>
				
				<div class="import-notice">
					<p><?php _e( 'Note: This will overwrite your existing content. Please backup your site before importing.', 'aqualuxe' ); ?></p>
				</div>
			</div>
		<?php endforeach; ?>
		
		<div class="import-instructions">
			<h3><?php _e( 'Import Instructions', 'aqualuxe' ); ?></h3>
			<ol>
				<li><?php _e( 'Backup your site before importing demo content.', 'aqualuxe' ); ?></li>
				<li><?php _e( 'Select a demo to import from the options above.', 'aqualuxe' ); ?></li>
				<li><?php _e( 'Click the "Import Demo" button to start the import process.', 'aqualuxe' ); ?></li>
				<li><?php _e( 'Wait for the import to complete. Do not navigate away from this page.', 'aqualuxe' ); ?></li>
				<li><?php _e( 'After import, review and customize the content as needed.', 'aqualuxe' ); ?></li>
			</ol>
		</div>
	</div>
	<?php
}

// Import demo content
function aqualuxe_import_demo_content( $demo_name ) {
	// Validate demo name
	$valid_demos = array( 'main', 'minimal' );
	if ( ! in_array( $demo_name, $valid_demos ) ) {
		return __( 'Invalid demo name.', 'aqualuxe' );
	}
	
	// Set time limit for import
	set_time_limit( 300 );
	
	// Import pages
	$pages_result = aqualuxe_import_demo_pages( $demo_name );
	if ( $pages_result !== true ) {
		return $pages_result;
	}
	
	// Import posts
	$posts_result = aqualuxe_import_demo_posts( $demo_name );
	if ( $posts_result !== true ) {
		return $posts_result;
	}
	
	// Import products
	$products_result = aqualuxe_import_demo_products( $demo_name );
	if ( $products_result !== true ) {
		return $products_result;
	}
	
	// Import settings
	$settings_result = aqualuxe_import_demo_settings( $demo_name );
	if ( $settings_result !== true ) {
		return $settings_result;
	}
	
	// Import widgets
	$widgets_result = aqualuxe_import_demo_widgets( $demo_name );
	if ( $widgets_result !== true ) {
		return $widgets_result;
	}
	
	// Import menus
	$menus_result = aqualuxe_import_demo_menus( $demo_name );
	if ( $menus_result !== true ) {
		return $menus_result;
	}
	
	// Import customizer settings
	$customizer_result = aqualuxe_import_demo_customizer( $demo_name );
	if ( $customizer_result !== true ) {
		return $customizer_result;
	}
	
	// Import media
	$media_result = aqualuxe_import_demo_media( $demo_name );
	if ( $media_result !== true ) {
		return $media_result;
	}
	
	// Import users
	$users_result = aqualuxe_import_demo_users( $demo_name );
	if ( $users_result !== true ) {
		return $users_result;
	}
	
	// Import comments
	$comments_result = aqualuxe_import_demo_comments( $demo_name );
	if ( $comments_result !== true ) {
		return $comments_result;
	}
	
	// Import terms
	$terms_result = aqualuxe_import_demo_terms( $demo_name );
	if ( $terms_result !== true ) {
		return $terms_result;
	}
	
	return true;
}

// Import demo pages
function aqualuxe_import_demo_pages( $demo_name ) {
	// Define demo pages
	$pages = array(
		'home' => array(
			'title' => __( 'Home', 'aqualuxe' ),
			'content' => '[aqualuxe_homepage]',
			'template' => 'page-homepage.php',
			'meta' => array(
				'_wp_page_template' => 'templates/page-homepage.php'
			)
		),
		'about' => array(
			'title' => __( 'About', 'aqualuxe' ),
			'content' => '[aqualuxe_about_page]',
			'template' => 'page-about.php',
			'meta' => array(
				'_wp_page_template' => 'templates/page-about.php'
			)
		),
		'services' => array(
			'title' => __( 'Services', 'aqualuxe' ),
			'content' => '[aqualuxe_services_page]',
			'template' => 'page-services.php',
			'meta' => array(
				'_wp_page_template' => 'templates/page-services.php'
			)
		),
		'blog' => array(
			'title' => __( 'Blog', 'aqualuxe' ),
			'content' => '',
			'template' => 'page-blog.php',
			'meta' => array(
				'_wp_page_template' => 'index.php'
			)
		),
		'contact' => array(
			'title' => __( 'Contact', 'aqualuxe' ),
			'content' => '[aqualuxe_contact_page]',
			'template' => 'page-contact.php',
			'meta' => array(
				'_wp_page_template' => 'templates/page-contact.php'
			)
		),
		'faq' => array(
			'title' => __( 'FAQ', 'aqualuxe' ),
			'content' => '[aqualuxe_faq_page]',
			'template' => 'page-faq.php',
			'meta' => array(
				'_wp_page_template' => 'templates/page-faq.php'
			)
		),
		'shop' => array(
			'title' => __( 'Shop', 'aqualuxe' ),
			'content' => '[woocommerce_shop]',
			'template' => 'page-shop.php',
			'meta' => array(
				'_wp_page_template' => 'woocommerce.php'
			)
		),
		'cart' => array(
			'title' => __( 'Cart', 'aqualuxe' ),
			'content' => '[woocommerce_cart]',
			'template' => 'page-cart.php',
			'meta' => array(
				'_wp_page_template' => 'woocommerce/cart/cart.php'
			)
		),
		'checkout' => array(
			'title' => __( 'Checkout', 'aqualuxe' ),
			'content' => '[woocommerce_checkout]',
			'template' => 'page-checkout.php',
			'meta' => array(
				'_wp_page_template' => 'woocommerce/checkout/form-checkout.php'
			)
		),
		'my-account' => array(
			'title' => __( 'My Account', 'aqualuxe' ),
			'content' => '[woocommerce_my_account]',
			'template' => 'page-my-account.php',
			'meta' => array(
				'_wp_page_template' => 'woocommerce/myaccount/my-account.php'
			)
		),
		'terms' => array(
			'title' => __( 'Terms and Conditions', 'aqualuxe' ),
			'content' => '[aqualuxe_terms_page]',
			'template' => 'page-terms.php',
			'meta' => array(
				'_wp_page_template' => 'page.php'
			)
		),
		'privacy' => array(
			'title' => __( 'Privacy Policy', 'aqualuxe' ),
			'content' => '[aqualuxe_privacy_page]',
			'template' => 'page-privacy.php',
			'meta' => array(
				'_wp_page_template' => 'page.php'
			)
		)
	);
	
	// Create pages
	foreach ( $pages as $page_slug => $page_data ) {
		$page_id = wp_insert_post( array(
			'post_title' => $page_data['title'],
			'post_content' => $page_data['content'],
			'post_type' => 'page',
			'post_status' => 'publish',
			'post_author' => get_current_user_id(),
			'post_name' => $page_slug
		) );
		
		if ( is_wp_error( $page_id ) ) {
			return $page_id->get_error_message();
		}
		
		// Set page template
		if ( ! empty( $page_data['template'] ) ) {
			update_post_meta( $page_id, '_wp_page_template', $page_data['template'] );
		}
		
		// Set additional meta
		if ( ! empty( $page_data['meta'] ) ) {
			foreach ( $page_data['meta'] as $meta_key => $meta_value ) {
				update_post_meta( $page_id, $meta_key, $meta_value );
			}
		}
		
		// Set front page
		if ( $page_slug === 'home' ) {
			update_option( 'page_on_front', $page_id );
			update_option( 'show_on_front', 'page' );
		}
		
		// Set posts page
		if ( $page_slug === 'blog' ) {
			update_option( 'page_for_posts', $page_id );
		}
	}
	
	return true;
}

// Import demo posts
function aqualuxe_import_demo_posts( $demo_name ) {
	// Define demo posts
	$posts = array(
		array(
			'title' => __( 'How to Care for Your New Fish', 'aqualuxe' ),
			'content' => __( 'Caring for ornamental fish requires attention to water quality, feeding schedules, and tank conditions...', 'aqualuxe' ),
			'excerpt' => __( 'Learn the basics of ornamental fish care in this comprehensive guide.', 'aqualuxe' ),
			'categories' => array( 'Fish Care' ),
			'tags' => array( 'care', 'tips', 'beginner' )
		),
		array(
			'title' => __( 'The Art of Aquarium Design', 'aqualuxe' ),
			'content' => __( 'Creating a beautiful aquarium is both a science and an art. Here are some tips for designing your perfect underwater landscape...', 'aqualuxe' ),
			'excerpt' => __( 'Discover the principles of aquarium design for a stunning display.', 'aqualuxe' ),
			'categories' => array( 'Aquarium Design' ),
			'tags' => array( 'design', 'aesthetics', 'layout' )
		),
		array(
			'title' => __( 'Rare Fish Species Available Now', 'aqualuxe' ),
			'content' => __( 'We\'re excited to announce the arrival of several rare fish species in our collection...', 'aqualuxe' ),
			'excerpt' => __( 'Check out our latest rare fish arrivals that are sure to impress.', 'aqualuxe' ),
			'categories' => array( 'News' ),
			'tags' => array( 'rare', 'species', 'new arrivals' )
		)
	);
	
	// Create posts
	foreach ( $posts as $post_data ) {
		$post_id = wp_insert_post( array(
			'post_title' => $post_data['title'],
			'post_content' => $post_data['content'],
			'post_excerpt' => $post_data['excerpt'],
			'post_type' => 'post',
			'post_status' => 'publish',
			'post_author' => get_current_user_id()
		) );
		
		if ( is_wp_error( $post_id ) ) {
			return $post_id->get_error_message();
		}
		
		// Set categories
		if ( ! empty( $post_data['categories'] ) ) {
			$category_ids = array();
			foreach ( $post_data['categories'] as $category_name ) {
				$category = get_category_by_slug( sanitize_title( $category_name ) );
				if ( ! $category ) {
					$category_id = wp_create_category( $category_name );
				} else {
					$category_id = $category->term_id;
				}
				$category_ids[] = $category_id;
			}
			wp_set_post_categories( $post_id, $category_ids );
		}
		
		// Set tags
		if ( ! empty( $post_data['tags'] ) ) {
			wp_set_post_tags( $post_id, $post_data['tags'] );
		}
	}
	
	return true;
}

// Import demo products
function aqualuxe_import_demo_products( $demo_name ) {
	// Check if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		return true; // Skip if WooCommerce is not active
	}
	
	// Define demo products
	$products = array(
		array(
			'name' => __( 'Premium Discus Fish', 'aqualuxe' ),
			'type' => 'simple',
			'regular_price' => '150.00',
			'description' => __( 'Beautiful, healthy Discus fish with vibrant colors. Imported directly from sustainable breeders.', 'aqualuxe' ),
			'short_description' => __( 'Rare Discus fish with exceptional coloration.', 'aqualuxe' ),
			'categories' => array( 'Rare Fish' ),
			'tags' => array( 'discus', 'rare', 'premium' ),
			'stock_status' => 'instock',
			'stock_quantity' => 10,
			'backorders' => 'no',
			'sold_individually' => true
		),
		array(
			'name' => __( 'Aquarium Plant Bundle', 'aqualuxe' ),
			'type' => 'simple',
			'regular_price' => '45.00',
			'description' => __( 'A carefully curated selection of premium aquarium plants to enhance your tank\'s beauty.', 'aqualuxe' ),
			'short_description' => __( 'Premium plants for a lush aquarium environment.', 'aqualuxe' ),
			'categories' => array( 'Aquarium Plants' ),
			'tags' => array( 'plants', 'aquascaping', 'bundle' ),
			'stock_status' => 'instock',
			'stock_quantity' => 50,
			'backorders' => 'no',
			'sold_individually' => false
		),
		array(
			'name' => __( 'Professional Fish Care Consultation', 'aqualuxe' ),
			'type' => 'simple',
			'regular_price' => '200.00',
			'description' => __( 'One-on-one consultation with our expert aquarists to optimize your fish care routine.', 'aqualuxe' ),
			'short_description' => __( 'Expert consultation for optimal fish health.', 'aqualuxe' ),
			'categories' => array( 'Services' ),
			'tags' => array( 'consultation', 'professional', 'care' ),
			'stock_status' => 'instock',
			'stock_quantity' => -1, // Unlimited
			'backorders' => 'no',
			'sold_individually' => true
		)
	);
	
	// Create products
	foreach ( $products as $product_data ) {
		$product = new WC_Product();
		$product->set_name( $product_data['name'] );
		$product->set_type( $product_data['type'] );
		$product->set_regular_price( $product_data['regular_price'] );
		$product->set_description( $product_data['description'] );
		$product->set_short_description( $product_data['short_description'] );
		$product->set_stock_status( $product_data['stock_status'] );
		$product->set_stock_quantity( $product_data['stock_quantity'] );
		$product->set_backorders( $product_data['backorders'] );
		$product->set_sold_individually( $product_data['sold_individually'] );
		$product->set_status( 'publish' );
		
		$product_id = $product->save();
		
		if ( is_wp_error( $product_id ) ) {
			return $product_id->get_error_message();
		}
		
		// Set categories
		if ( ! empty( $product_data['categories'] ) ) {
			$category_ids = array();
			foreach ( $product_data['categories'] as $category_name ) {
				$category = get_term_by( 'name', $category_name, 'product_cat' );
				if ( ! $category ) {
					$category_id = wp_create_category( $category_name, 0, 'product_cat' );
				} else {
					$category_id = $category->term_id;
				}
				$category_ids[] = $category_id;
			}
			wp_set_object_terms( $product_id, $category_ids, 'product_cat' );
		}
		
		// Set tags
		if ( ! empty( $product_data['tags'] ) ) {
			wp_set_object_terms( $product_id, $product_data['tags'], 'product_tag' );
		}
	}
	
	return true;
}

// Import demo settings
function aqualuxe_import_demo_settings( $demo_name ) {
	// Define demo settings
	$settings = array(
		'blogname' => __( 'AquaLuxe Ornamental Fish', 'aqualuxe' ),
		'blogdescription' => __( 'Premium Aquatic Specimens for Discerning Collectors', 'aqualuxe' ),
		'hero_title' => __( 'Premium Ornamental Fish for Discerning Collectors', 'aqualuxe' ),
		'hero_description' => __( 'Discover our exclusive collection of rare and exotic aquatic species, expertly bred and sustainably sourced for the most discerning collectors.', 'aqualuxe' ),
		'hero_button_text' => __( 'Explore Our Collection', 'aqualuxe' ),
		'hero_button_url' => '#',
		'featured_products_title' => __( 'Featured Products', 'aqualuxe' ),
		'testimonials_title' => __( 'What Our Customers Say', 'aqualuxe' ),
		'newsletter_title' => __( 'Join Our Community', 'aqualuxe' ),
		'newsletter_description' => __( 'Subscribe to our newsletter for exclusive offers, care tips, and the latest arrivals.', 'aqualuxe' ),
		'history_title' => __( 'Our History', 'aqualuxe' ),
		'team_title' => __( 'Meet Our Team', 'aqualuxe' ),
		'breeding_title' => __( 'Our Breeding Programs', 'aqualuxe' ),
		'consultation_title' => __( 'Expert Consultation', 'aqualuxe' ),
		'contact_address' => __( '123 Aquarium Lane, Marine City, MC 12345', 'aqualuxe' ),
		'contact_phone' => __( '+1 (555) 123-4567', 'aqualuxe' ),
		'contact_email' => __( 'info@aqualue.com', 'aqualuxe' )
	);
	
	// Update settings
	foreach ( $settings as $key => $value ) {
		update_option( $key, $value );
	}
	
	return true;
}

// Import demo widgets
function aqualuxe_import_demo_widgets( $demo_name ) {
	// Define demo widgets
	$widgets = array(
		'sidebar-1' => array(
			array(
				'widget_type' => 'search',
				'widget_data' => array(
					'title' => __( 'Search', 'aqualuxe' )
				)
			),
			array(
				'widget_type' => 'recent-posts',
				'widget_data' => array(
					'title' => __( 'Recent Posts', 'aqualuxe' ),
					'number' => 5
				)
			),
			array(
				'widget_type' => 'categories',
				'widget_data' => array(
					'title' => __( 'Categories', 'aqualuxe' ),
					'count' => 1,
					'hierarchical' => 1
				)
			)
		),
		'footer-1' => array(
			array(
				'widget_type' => 'text',
				'widget_data' => array(
					'title' => __( 'About AquaLuxe', 'aqualuxe' ),
					'text' => __( 'We specialize in premium ornamental fish and aquarium supplies for discerning collectors.', 'aqualuxe' )
				)
			)
		),
		'footer-2' => array(
			array(
				'widget_type' => 'nav_menu',
				'widget_data' => array(
					'title' => __( 'Quick Links', 'aqualuxe' ),
					'nav_menu' => 0 // Will be set when menu is created
				)
			)
		),
		'footer-3' => array(
			array(
				'widget_type' => 'text',
				'widget_data' => array(
					'title' => __( 'Contact Info', 'aqualuxe' ),
					'text' => __( '123 Aquarium Lane, Marine City, MC 12345', 'aqualuxe' ) . '<br>' .
					          __( 'Phone: +1 (555) 123-4567', 'aqualuxe' ) . '<br>' .
					          __( 'Email: info@aqualue.com', 'aqualuxe' )
				)
			)
		),
		'footer-4' => array(
			array(
				'widget_type' => 'social_media',
				'widget_data' => array(
					'title' => __( 'Follow Us', 'aqualuxe' ),
					'facebook' => 'https://facebook.com/aqualuxe',
					'twitter' => 'https://twitter.com/aqualuxe',
					'instagram' => 'https://instagram.com/aqualuxe'
				)
			)
		)
	);
	
	// Update widgets
	foreach ( $widgets as $sidebar_id => $sidebar_widgets ) {
		$widget_ids = array();
		
		foreach ( $sidebar_widgets as $widget ) {
			// Create widget instance
			$widget_id = wp_insert_widget( $widget['widget_type'], $widget['widget_data'], $sidebar_id );
			if ( $widget_id ) {
				$widget_ids[] = $widget['widget_type'] . '-' . $widget_id;
			}
		}
		
		// Assign widgets to sidebar
		update_option( 'sidebars_widgets', array_merge( get_option( 'sidebars_widgets', array() ), array( $sidebar_id => $widget_ids ) ) );
	}
	
	return true;
}

// Import demo menus
function aqualuxe_import_demo_menus( $demo_name ) {
	// Define demo menus
	$menus = array(
		'primary' => array(
			'name' => __( 'Primary Menu', 'aqualuxe' ),
			'items' => array(
				array(
					'title' => __( 'Home', 'aqualuxe' ),
					'url' => home_url( '/' )
				),
				array(
					'title' => __( 'Shop', 'aqualuxe' ),
					'url' => home_url( '/shop/' )
				),
				array(
					'title' => __( 'About', 'aqualuxe' ),
					'url' => home_url( '/about/' )
				),
				array(
					'title' => __( 'Services', 'aqualuxe' ),
					'url' => home_url( '/services/' )
				),
				array(
					'title' => __( 'Blog', 'aqualuxe' ),
					'url' => home_url( '/blog/' )
				),
				array(
					'title' => __( 'Contact', 'aqualuxe' ),
					'url' => home_url( '/contact/' )
				)
			)
		),
		'footer' => array(
			'name' => __( 'Footer Menu', 'aqualuxe' ),
			'items' => array(
				array(
					'title' => __( 'Terms and Conditions', 'aqualuxe' ),
					'url' => home_url( '/terms/' )
				),
				array(
					'title' => __( 'Privacy Policy', 'aqualuxe' ),
					'url' => home_url( '/privacy/' )
				),
				array(
					'title' => __( 'FAQ', 'aqualuxe' ),
					'url' => home_url( '/faq/' )
				)
			)
		)
	);
	
	// Create menus
	foreach ( $menus as $menu_location => $menu_data ) {
		$menu_id = wp_create_nav_menu( $menu_data['name'] );
		
		if ( is_wp_error( $menu_id ) ) {
			return $menu_id->get_error_message();
		}
		
		// Add menu items
		foreach ( $menu_data['items'] as $menu_item ) {
			wp_update_nav_menu_item( $menu_id, 0, array(
				'menu-item-title' => $menu_item['title'],
				'menu-item-url' => $menu_item['url'],
				'menu-item-status' => 'publish'
			) );
		}
		
		// Assign menu to location
		$locations = get_theme_mod( 'nav_menu_locations' );
		$locations[$menu_location] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );
	}
	
	return true;
}

// Import demo customizer settings
function aqualuxe_import_demo_customizer( $demo_name ) {
	// Define demo customizer settings
	$customizer_settings = array(
		'hero_title' => __( 'Premium Ornamental Fish for Discerning Collectors', 'aqualuxe' ),
		'hero_description' => __( 'Discover our exclusive collection of rare and exotic aquatic species, expertly bred and sustainably sourced for the most discerning collectors.', 'aqualuxe' ),
		'hero_button_text' => __( 'Explore Our Collection', 'aqualuxe' ),
		'hero_button_url' => '#',
		'featured_products_title' => __( 'Featured Products', 'aqualuxe' ),
		'testimonials_title' => __( 'What Our Customers Say', 'aqualuxe' ),
		'newsletter_title' => __( 'Join Our Community', 'aqualuxe' ),
		'newsletter_description' => __( 'Subscribe to our newsletter for exclusive offers, care tips, and the latest arrivals.', 'aqualuxe' ),
		'history_title' => __( 'Our History', 'aqualuxe' ),
		'team_title' => __( 'Meet Our Team', 'aqualuxe' ),
		'breeding_title' => __( 'Our Breeding Programs', 'aqualuxe' ),
		'consultation_title' => __( 'Expert Consultation', 'aqualuxe' ),
		'contact_address' => __( '123 Aquarium Lane, Marine City, MC 12345', 'aqualuxe' ),
		'contact_phone' => __( '+1 (555) 123-4567', 'aqualuxe' ),
		'contact_email' => __( 'info@aqualue.com', 'aqualuxe' ),
		'facebook_url' => 'https://facebook.com/aqualuxe',
		'twitter_url' => 'https://twitter.com/aqualuxe',
		'instagram_url' => 'https://instagram.com/aqualuxe',
		'linkedin_url' => 'https://linkedin.com/company/aqualuxe',
		'youtube_url' => 'https://youtube.com/aqualuxe'
	);
	
	// Update customizer settings
	foreach ( $customizer_settings as $key => $value ) {
		set_theme_mod( $key, $value );
	}
	
	return true;
}

// Import demo media
function aqualuxe_import_demo_media( $demo_name ) {
	// Define demo media files
	$media_files = array(
		'hero-bg.jpg',
		'product-1.jpg',
		'product-2.jpg',
		'product-3.jpg',
		'team-1.jpg',
		'team-2.jpg',
		'team-3.jpg'
	);
	
	// Import media files
	foreach ( $media_files as $filename ) {
		// In a real implementation, we would download and import the media files
		// For this demo, we'll just log the filenames
		error_log( 'Importing media file: ' . $filename );
	}
	
	return true;
}

// Import demo users
function aqualuxe_import_demo_users( $demo_name ) {
	// Define demo users
	$users = array(
		array(
			'user_login' => 'aquarium_admin',
			'user_pass' => 'aquarium_password',
			'user_email' => 'admin@aqualue.com',
			'first_name' => 'Aquarium',
			'last_name' => 'Admin',
			'role' => 'administrator'
		),
		array(
			'user_login' => 'aquarium_customer',
			'user_pass' => 'customer_password',
			'user_email' => 'customer@aqualue.com',
			'first_name' => 'Aquarium',
			'last_name' => 'Customer',
			'role' => 'customer'
		)
	);
	
	// Create users
	foreach ( $users as $user_data ) {
		$user_id = wp_create_user(
			$user_data['user_login'],
			$user_data['user_pass'],
			$user_data['user_email']
		);
		
		if ( is_wp_error( $user_id ) ) {
			// If user already exists, update the user data
			$user = get_user_by( 'login', $user_data['user_login'] );
			if ( $user ) {
				$user_id = $user->ID;
				wp_update_user( array(
					'ID' => $user_id,
					'first_name' => $user_data['first_name'],
					'last_name' => $user_data['last_name']
				) );
				$user->set_role( $user_data['role'] );
			} else {
				return $user_id->get_error_message();
			}
		} else {
			// Set user role
			$user = new WP_User( $user_id );
			$user->set_role( $user_data['role'] );
			
			// Update user meta
			update_user_meta( $user_id, 'first_name', $user_data['first_name'] );
			update_user_meta( $user_id, 'last_name', $user_data['last_name'] );
		}
	}
	
	return true;
}

// Import demo comments
function aqualuxe_import_demo_comments( $demo_name ) {
	// Define demo comments
	$comments = array(
		array(
			'comment_post_ID' => 1,
			'comment_author' => 'Happy Customer',
			'comment_author_email' => 'customer@example.com',
			'comment_content' => __( 'The Discus fish I purchased from AquaLuxe arrived healthy and vibrant. Excellent service!', 'aqualuxe' ),
			'comment_type' => '',
			'comment_parent' => 0,
			'user_id' => 0,
			'comment_approved' => 1
		),
		array(
			'comment_post_ID' => 1,
			'comment_author' => 'Aquarium Enthusiast',
			'comment_author_email' => 'enthusiast@example.com',
			'comment_content' => __( 'I\'ve been ordering from AquaLuxe for years. Their quality and service are unmatched.', 'aqualuxe' ),
			'comment_type' => '',
			'comment_parent' => 0,
			'user_id' => 0,
			'comment_approved' => 1
		)
	);
	
	// Create comments
	foreach ( $comments as $comment_data ) {
		$comment_id = wp_insert_comment( $comment_data );
		
		if ( is_wp_error( $comment_id ) ) {
			return $comment_id->get_error_message();
		}
	}
	
	return true;
}

// Import demo terms
function aqualuxe_import_demo_terms( $demo_name ) {
	// Define demo terms
	$terms = array(
		'category' => array(
			'Fish Care' => array(
				'description' => __( 'Tips and guides for caring for your ornamental fish.', 'aqualuxe' )
			),
			'Aquarium Design' => array(
				'description' => __( 'Articles about creating beautiful aquariums.', 'aqualuxe' )
			),
			'News' => array(
				'description' => __( 'Latest news and updates from AquaLuxe.', 'aqualuxe' )
			)
		),
		'product_cat' => array(
			'Rare Fish' => array(
				'description' => __( 'Our collection of rare and exotic fish species.', 'aqualuxe' )
			),
			'Aquarium Plants' => array(
				'description' => __( 'Premium plants for your aquarium.', 'aqualuxe' )
			),
			'Services' => array(
				'description' => __( 'Professional services for aquarium care.', 'aqualuxe' )
			)
		),
		'post_tag' => array(
			'care' => array(),
			'tips' => array(),
			'beginner' => array(),
			'design' => array(),
			'aesthetics' => array(),
			'layout' => array(),
			'rare' => array(),
			'species' => array(),
			'new arrivals' => array(),
			'plants' => array(),
			'aquascaping' => array(),
			'bundle' => array(),
			'consultation' => array(),
			'professional' => array()
		)
	);
	
	// Create terms
	foreach ( $terms as $taxonomy => $taxonomy_terms ) {
		foreach ( $taxonomy_terms as $term_name => $term_data ) {
			$term = get_term_by( 'name', $term_name, $taxonomy );
			
			if ( ! $term ) {
				$term_id = wp_insert_term( $term_name, $taxonomy, $term_data );
				
				if ( is_wp_error( $term_id ) ) {
					return $term_id->get_error_message();
				}
			}
		}
	}
	
	return true;
}

// Helper function to insert widget
function wp_insert_widget( $widget_type, $widget_data, $sidebar_id ) {
	// Get current widgets for this type
	$widgets = get_option( 'widget_' . $widget_type, array() );
	
	// Add new widget data
	$widget_id = count( $widgets ) + 1;
	$widgets[$widget_id] = $widget_data;
	
	// Update widgets option
	update_option( 'widget_' . $widget_type, $widgets );
	
	return $widget_id;
}