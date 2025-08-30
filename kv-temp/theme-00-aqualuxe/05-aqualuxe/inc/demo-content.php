<?php
/**
 * AquaLuxe Demo Content Import
 *
 * @package AquaLuxe
 */

/**
 * Import demo content
 */
function aqualuxe_import_demo_content() {
	// Check if user has permission
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	// Check if demo content already imported
	if ( get_option( 'aqualuxe_demo_content_imported' ) ) {
		return;
	}

	// Import demo posts
	aqualuxe_import_demo_posts();

	// Import demo products
	aqualuxe_import_demo_products();

	// Import demo pages
	aqualuxe_import_demo_pages();

	// Set option to prevent re-importing
	update_option( 'aqualuxe_demo_content_imported', true );
}

/**
 * Import demo posts
 */
function aqualuxe_import_demo_posts() {
	$demo_posts = array(
		array(
			'post_title'   => 'Welcome to Our Premium Ornamental Fish Store',
			'post_content' => '<p>Welcome to AquaLuxe, your premier destination for the finest ornamental fish from around the world. Our carefully curated collection features rare and exotic species that will transform your aquarium into a stunning underwater paradise.</p>
<p>Each fish in our collection is sourced from reputable breeders and suppliers who share our commitment to quality and sustainability. We take pride in providing healthy, vibrant fish that will thrive in your home aquarium.</p>',
			'post_status'  => 'publish',
			'post_type'    => 'post',
		),
		array(
			'post_title'   => 'The Art of Aquarium Care',
			'post_content' => '<p>Creating and maintaining a beautiful aquarium is both an art and a science. At AquaLuxe, we believe that proper care is essential to the health and happiness of your aquatic pets.</p>
<p>In this guide, we\'ll share our expert tips on water quality, feeding, and creating the perfect environment for your fish to flourish.</p>
<h3>Water Quality</h3>
<p>Maintaining optimal water conditions is the foundation of aquarium success. Regular testing and water changes are crucial for keeping your fish healthy.</p>
<h3>Feeding</h3>
<p>Different species have different dietary needs. We recommend a varied diet that includes high-quality flakes, pellets, and occasional treats like brine shrimp or bloodworms.</p>',
			'post_status'  => 'publish',
			'post_type'    => 'post',
		),
	);

	foreach ( $demo_posts as $post_data ) {
		// Check if post already exists
		$existing = get_page_by_title( $post_data['post_title'], OBJECT, $post_data['post_type'] );
		if ( ! $existing ) {
			wp_insert_post( $post_data );
		}
	}
}

/**
 * Import demo products
 */
function aqualuxe_import_demo_products() {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	$demo_products = array(
		array(
			'post_title'   => 'Premium Goldfish',
			'post_content' => '<p>Our Premium Goldfish are selectively bred for their vibrant colors and graceful swimming patterns. These beautiful fish are perfect for both beginners and experienced aquarists.</p>
<ul>
<li>Origin: Selectively bred varieties</li>
<li>Size: 6-8 inches</li>
<li>Lifespan: 10-15 years</li>
<li>Water Temperature: 65-72°F</li>
<li>Diet: Omnivore</li>
</ul>',
			'post_status'  => 'publish',
			'post_type'    => 'product',
			'meta_input'   => array(
				'_price'         => '29.99',
				'_regular_price' => '29.99',
				'_sale_price'    => '',
				'_sku'           => 'GF-001',
				'_stock'         => '20',
				'_manage_stock'  => 'yes',
			),
		),
		array(
			'post_title'   => 'Rare Koi Fish',
			'post_content' => '<p>These magnificent Koi are direct descendants of prized Japanese bloodlines. Known for their stunning color patterns and gentle nature, they make a spectacular addition to any pond.</p>
<ul>
<li>Origin: Japan</li>
<li>Size: 12-24 inches</li>
<li>Lifespan: 25-35 years</li>
<li>Water Temperature: 50-75°F</li>
<li>Diet: Omnivore</li>
</ul>',
			'post_status'  => 'publish',
			'post_type'    => 'product',
			'meta_input'   => array(
				'_price'         => '199.99',
				'_regular_price' => '199.99',
				'_sale_price'    => '179.99',
				'_sku'           => 'KOI-001',
				'_stock'         => '5',
				'_manage_stock'  => 'yes',
			),
		),
		array(
			'post_title'   => 'Exotic Discus Fish',
			'post_content' => '<p>Our Exotic Discus Fish are imported directly from the Amazon Basin. These stunning fish are known for their vibrant colors and unique disc shape.</p>
<ul>
<li>Origin: Amazon Basin</li>
<li>Size: 6-8 inches</li>
<li>Lifespan: 10-15 years</li>
<li>Water Temperature: 82-86°F</li>
<li>Diet: Carnivore</li>
</ul>',
			'post_status'  => 'publish',
			'post_type'    => 'product',
			'meta_input'   => array(
				'_price'         => '89.99',
				'_regular_price' => '89.99',
				'_sale_price'    => '',
				'_sku'           => 'DIS-001',
				'_stock'         => '8',
				'_manage_stock'  => 'yes',
			),
		),
	);

	foreach ( $demo_products as $product_data ) {
		// Check if product already exists
		$existing = get_page_by_title( $product_data['post_title'], OBJECT, $product_data['post_type'] );
		if ( ! $existing ) {
			$product_id = wp_insert_post( $product_data );
			
			// Set product type
			wp_set_object_terms( $product_id, 'simple', 'product_type' );
			
			// Set product visibility
			update_post_meta( $product_id, '_visibility', 'visible' );
			
			// Set product stock status
			update_post_meta( $product_id, '_stock_status', 'instock' );
		}
	}
}

/**
 * Import demo pages
 */
function aqualuxe_import_demo_pages() {
	$demo_pages = array(
		array(
			'post_title'   => 'About Us',
			'post_content' => '<h2>Our Story</h2>
<p>AquaLuxe was founded with a passion for bringing the beauty of aquatic life into homes around the world. Our team of experts has decades of experience in fish breeding, care, and aquarium design.</p>
<h2>Our Mission</h2>
<p>We are committed to providing the highest quality ornamental fish while promoting sustainable practices and responsible ownership. Every fish we sell comes with our guarantee of health and vitality.</p>
<h2>Quality Assurance</h2>
<p>All our fish undergo rigorous health checks before shipping. We work exclusively with trusted breeders and suppliers who meet our exacting standards.</p>',
			'post_status'  => 'publish',
			'post_type'    => 'page',
		),
		array(
			'post_title'   => 'Contact Us',
			'post_content' => '<h2>Get in Touch</h2>
<p>We\'d love to hear from you! Whether you have questions about our fish, need care advice, or want to place an order, our team is here to help.</p>
<h3>Address</h3>
<p>123 Aquarium Street<br>
Fishville, FV 12345<br>
Ocean Country</p>
<h3>Phone</h3>
<p>(555) 123-4567</p>
<h3>Email</h3>
<p>info@aqualuxe.com</p>
<h3>Hours</h3>
<p>Monday-Friday: 9am-6pm<br>
Saturday: 10am-4pm<br>
Sunday: Closed</p>',
			'post_status'  => 'publish',
			'post_type'    => 'page',
		),
		array(
			'post_title'   => 'Care Guide',
			'post_content' => '<h2>Fish Care Basics</h2>
<p>Proper care is essential for the health and happiness of your aquatic pets. Follow these guidelines to create a thriving environment for your fish.</p>
<h3>Water Quality</h3>
<p>Test your water regularly for pH, ammonia, nitrites, and nitrates. Perform 20-25% water changes weekly to maintain optimal conditions.</p>
<h3>Feeding</h3>
<p>Feed your fish 2-3 times daily with only as much food as they can consume in 2-3 minutes. Overfeeding is one of the most common causes of water quality problems.</p>
<h3>Filtration</h3>
<p>Ensure your filtration system is appropriate for your tank size. Mechanical, chemical, and biological filtration all play important roles in maintaining water quality.</p>',
			'post_status'  => 'publish',
			'post_type'    => 'page',
		),
	);

	foreach ( $demo_pages as $page_data ) {
		// Check if page already exists
		$existing = get_page_by_title( $page_data['post_title'], OBJECT, $page_data['post_type'] );
		if ( ! $existing ) {
			wp_insert_post( $page_data );
		}
	}
}

/**
 * Add demo content import page to admin menu
 */
function aqualuxe_add_demo_import_menu() {
	add_management_page(
		'AquaLuxe Demo Import',
		'AquaLuxe Demo Import',
		'manage_options',
		'aqualuxe-demo-import',
		'aqualuxe_demo_import_page'
	);
}
add_action( 'admin_menu', 'aqualuxe_add_demo_import_menu' );

/**
 * Demo import page content
 */
function aqualuxe_demo_import_page() {
	// Handle import request
	if ( isset( $_POST['import_demo_content'] ) && wp_verify_nonce( $_POST['aqualuxe_demo_nonce'], 'import_demo_content' ) ) {
		aqualuxe_import_demo_content();
		echo '<div class="notice notice-success"><p>Demo content imported successfully!</p></div>';
	}

	// Check if demo content already imported
	$imported = get_option( 'aqualuxe_demo_content_imported' );
	?>
	<div class="wrap">
		<h1>AquaLuxe Demo Content Import</h1>
		<?php if ( $imported ) : ?>
			<div class="notice notice-info">
				<p>Demo content has already been imported.</p>
			</div>
		<?php else : ?>
			<p>Import sample posts, products, and pages to see how your AquaLuxe theme looks with content.</p>
			<form method="post">
				<?php wp_nonce_field( 'import_demo_content', 'aqualuxe_demo_nonce' ); ?>
				<?php submit_button( 'Import Demo Content', 'primary', 'import_demo_content' ); ?>
			</form>
		<?php endif; ?>
	</div>
	<?php
}