<?php
/**
 * Template part for vendor store
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

// Demo vendor data
$vendor = array(
    'id' => 1,
    'name' => 'Coral Reef Specialists',
    'logo' => get_template_directory_uri() . '/assets/images/vendor-1.png',
    'banner' => get_template_directory_uri() . '/assets/images/vendor-banner.jpg',
    'description' => 'Specializing in premium coral and reef supplies for enthusiasts and professionals. With over 15 years of experience in the aquarium industry, we provide high-quality products sourced directly from sustainable suppliers around the world.',
    'rating' => 4.8,
    'reviews' => 124,
    'joined' => '2023-05-15',
    'products' => 47,
    'location' => 'San Diego, CA',
    'website' => 'https://coralreefspecialists.com',
    'social' => array(
        'facebook' => 'https://facebook.com/coralreefspecialists',
        'instagram' => 'https://instagram.com/coralreefspecialists',
        'twitter' => 'https://twitter.com/coralreefspec',
    ),
);

// Demo vendor products
$vendor_products = array(
    array(
        'id' => 'PRD-1234',
        'name' => 'Premium Live Rock',
        'image' => get_template_directory_uri() . '/assets/images/product-rock.jpg',
        'price' => 49.99,
        'rating' => 4.9,
        'reviews' => 37,
        'category' => 'Reef Supplies',
        'featured' => true,
    ),
    array(
        'id' => 'PRD-1235',
        'name' => 'Coral Food Blend',
        'image' => get_template_directory_uri() . '/assets/images/product-food.jpg',
        'price' => 24.99,
        'rating' => 4.7,
        'reviews' => 28,
        'category' => 'Food & Supplements',
        'featured' => true,
    ),
    array(
        'id' => 'PRD-1236',
        'name' => 'Reef LED Light',
        'image' => get_template_directory_uri() . '/assets/images/product-light.jpg',
        'price' => 199.99,
        'rating' => 4.8,
        'reviews' => 19,
        'category' => 'Equipment',
        'featured' => true,
    ),
    array(
        'id' => 'PRD-1237',
        'name' => 'Coral Frag Rack',
        'image' => get_template_directory_uri() . '/assets/images/product-rack.jpg',
        'price' => 34.99,
        'rating' => 4.6,
        'reviews' => 12,
        'category' => 'Reef Supplies',
        'featured' => false,
    ),
    array(
        'id' => 'PRD-1238',
        'name' => 'Reef Salt Mix',
        'image' => get_template_directory_uri() . '/assets/images/product-salt.jpg',
        'price' => 39.99,
        'rating' => 4.9,
        'reviews' => 42,
        'category' => 'Water Care',
        'featured' => false,
    ),
    array(
        'id' => 'PRD-1239',
        'name' => 'Coral Dipping Solution',
        'image' => get_template_directory_uri() . '/assets/images/product-dip.jpg',
        'price' => 19.99,
        'rating' => 4.7,
        'reviews' => 15,
        'category' => 'Maintenance',
        'featured' => false,
    ),
    array(
        'id' => 'PRD-1240',
        'name' => 'Protein Skimmer',
        'image' => get_template_directory_uri() . '/assets/images/product-skimmer.jpg',
        'price' => 149.99,
        'rating' => 4.8,
        'reviews' => 23,
        'category' => 'Equipment',
        'featured' => false,
    ),
    array(
        'id' => 'PRD-1241',
        'name' => 'Coral Glue',
        'image' => get_template_directory_uri() . '/assets/images/product-glue.jpg',
        'price' => 12.99,
        'rating' => 4.5,
        'reviews' => 31,
        'category' => 'Maintenance',
        'featured' => false,
    ),
);

// Demo vendor categories
$vendor_categories = array(
    'Reef Supplies' => 12,
    'Equipment' => 15,
    'Food & Supplements' => 8,
    'Water Care' => 6,
    'Maintenance' => 9,
    'Corals' => 18,
    'Fish' => 14,
    'Invertebrates' => 7,
);

// Demo vendor reviews
$vendor_reviews = array(
    array(
        'id' => 'REV-001',
        'customer_name' => 'Michael T.',
        'customer_image' => get_template_directory_uri() . '/assets/images/customer-1.jpg',
        'rating' => 5,
        'date' => '2025-08-10',
        'content' => 'Excellent products and fast shipping! The live rock I ordered was carefully packed and arrived in perfect condition. Will definitely order from this vendor again.',
        'product' => 'Premium Live Rock',
    ),
    array(
        'id' => 'REV-002',
        'customer_name' => 'Jennifer L.',
        'customer_image' => get_template_directory_uri() . '/assets/images/customer-2.jpg',
        'rating' => 4,
        'date' => '2025-08-05',
        'content' => 'Great quality coral food. My corals are showing improved polyp extension and coloration since I started using this product. The only reason for 4 stars instead of 5 is that the container is a bit difficult to open.',
        'product' => 'Coral Food Blend',
    ),
    array(
        'id' => 'REV-003',
        'customer_name' => 'Robert K.',
        'customer_image' => get_template_directory_uri() . '/assets/images/customer-3.jpg',
        'rating' => 5,
        'date' => '2025-07-28',
        'content' => 'The Reef LED Light is amazing! Easy to program, great spectrum options, and my corals are thriving. Customer service was also very helpful when I had questions about the settings.',
        'product' => 'Reef LED Light',
    ),
);
?>

<div class="vendor-store">
    <!-- Vendor Banner -->
    <div class="vendor-banner relative h-64 md:h-80 rounded-lg overflow-hidden mb-6">
        <img src="<?php echo esc_url($vendor['banner']); ?>" alt="<?php echo esc_attr($vendor['name']); ?> Banner" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent flex items-end">
            <div class="container mx-auto px-4 pb-6">
                <div class="flex items-center">
                    <div class="vendor-logo w-20 h-20 rounded-lg overflow-hidden border-4 border-white mr-4">
                        <img src="<?php echo esc_url($vendor['logo']); ?>" alt="<?php echo esc_attr($vendor['name']); ?>" class="w-full h-full object-cover">
                    </div>
                    <div class="vendor-info text-white">
                        <h1 class="text-2xl md:text-3xl font-bold mb-1"><?php echo esc_html($vendor['name']); ?></h1>
                        <div class="vendor-meta flex flex-wrap items-center text-sm">
                            <div class="vendor-rating flex items-center mr-4">
                                <div class="stars text-accent mr-1">
                                    <i class="fas fa-star"></i>
                                </div>
                                <span><?php echo esc_html($vendor['rating']); ?> (<?php echo esc_html($vendor['reviews']); ?> reviews)</span>
                            </div>
                            <div class="vendor-location flex items-center mr-4">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <span><?php echo esc_html($vendor['location']); ?></span>
                            </div>
                            <div class="vendor-since">
                                <i class="fas fa-store mr-1"></i>
                                <span>Member since <?php echo esc_html(date('F Y', strtotime($vendor['joined']))); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Content -->
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Sidebar -->
            <div class="vendor-sidebar lg:col-span-1">
                <!-- Vendor Info -->
                <div class="vendor-info-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">About the Vendor</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">
                        <?php echo esc_html($vendor['description']); ?>
                    </p>
                    <div class="vendor-contact space-y-2 mb-4">
                        <div class="flex items-center text-sm">
                            <i class="fas fa-globe w-5 text-primary mr-2"></i>
                            <a href="<?php echo esc_url($vendor['website']); ?>" target="_blank" class="text-primary hover:text-primary-dark transition-colors">
                                <?php echo esc_html(str_replace('https://', '', $vendor['website'])); ?>
                            </a>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-box w-5 text-primary mr-2"></i>
                            <span><?php echo esc_html($vendor['products']); ?> Products</span>
                        </div>
                    </div>
                    <div class="vendor-social flex space-x-2">
                        <?php if (!empty($vendor['social']['facebook'])) : ?>
                            <a href="<?php echo esc_url($vendor['social']['facebook']); ?>" target="_blank" class="w-8 h-8 rounded-full bg-light-dark dark:bg-dark-light flex items-center justify-center hover:bg-primary hover:text-white transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($vendor['social']['instagram'])) : ?>
                            <a href="<?php echo esc_url($vendor['social']['instagram']); ?>" target="_blank" class="w-8 h-8 rounded-full bg-light-dark dark:bg-dark-light flex items-center justify-center hover:bg-primary hover:text-white transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                        <?php endif; ?>
                        <?php if (!empty($vendor['social']['twitter'])) : ?>
                            <a href="<?php echo esc_url($vendor['social']['twitter']); ?>" target="_blank" class="w-8 h-8 rounded-full bg-light-dark dark:bg-dark-light flex items-center justify-center hover:bg-primary hover:text-white transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Categories -->
                <div class="vendor-categories bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6 mb-6">
                    <h3 class="text-lg font-bold mb-4">Categories</h3>
                    <ul class="space-y-2">
                        <?php foreach ($vendor_categories as $category => $count) : ?>
                            <li>
                                <a href="#" class="flex items-center justify-between text-sm hover:text-primary transition-colors">
                                    <span><?php echo esc_html($category); ?></span>
                                    <span class="bg-light-dark dark:bg-dark-light px-2 py-1 rounded-full text-xs">
                                        <?php echo esc_html($count); ?>
                                    </span>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <!-- Contact Vendor -->
                <div class="contact-vendor bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6">
                    <h3 class="text-lg font-bold mb-4">Contact Vendor</h3>
                    <form class="space-y-4">
                        <div class="form-group">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Name</label>
                            <input type="text" id="name" name="name" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white text-sm">
                        </div>
                        <div class="form-group">
                            <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Your Email</label>
                            <input type="email" id="email" name="email" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white text-sm">
                        </div>
                        <div class="form-group">
                            <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Message</label>
                            <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white text-sm"></textarea>
                        </div>
                        <button type="submit" class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors text-sm">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="vendor-main lg:col-span-3">
                <!-- Featured Products -->
                <div class="featured-products mb-8">
                    <div class="section-header flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">Featured Products</h2>
                        <a href="#" class="text-sm text-primary hover:text-primary-dark transition-colors">View All</a>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php 
                        $featured_count = 0;
                        foreach ($vendor_products as $product) : 
                            if ($product['featured'] && $featured_count < 3) :
                                $featured_count++;
                        ?>
                            <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                                <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                                    <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['name']); ?>" class="w-full h-full object-cover">
                                </a>
                                <div class="product-content p-4">
                                    <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                                        <?php echo esc_html($product['category']); ?>
                                    </div>
                                    <h3 class="product-title text-lg font-bold mb-2">
                                        <a href="#" class="hover:text-primary transition-colors">
                                            <?php echo esc_html($product['name']); ?>
                                        </a>
                                    </h3>
                                    <div class="product-rating flex items-center mb-2">
                                        <div class="stars text-accent">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                            <?php echo esc_html($product['rating']); ?> (<?php echo esc_html($product['reviews']); ?>)
                                        </span>
                                    </div>
                                    <div class="product-price-wrapper flex items-center justify-between mb-3">
                                        <div class="product-price font-bold text-lg">$<?php echo esc_html(number_format($product['price'], 2)); ?></div>
                                    </div>
                                    <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </div>
                </div>
                
                <!-- All Products -->
                <div class="all-products mb-8">
                    <div class="section-header flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">All Products</h2>
                        <div class="flex items-center space-x-2">
                            <select class="px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary dark:bg-dark-light dark:text-white text-sm">
                                <option value="newest">Newest</option>
                                <option value="price-low">Price: Low to High</option>
                                <option value="price-high">Price: High to Low</option>
                                <option value="rating">Rating</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($vendor_products as $product) : ?>
                            <div class="product-card bg-white dark:bg-dark-medium rounded-lg shadow-soft overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                                <a href="#" class="product-image block aspect-w-1 aspect-h-1">
                                    <img src="<?php echo esc_url($product['image']); ?>" alt="<?php echo esc_attr($product['name']); ?>" class="w-full h-full object-cover">
                                </a>
                                <div class="product-content p-4">
                                    <div class="product-category text-xs text-gray-500 dark:text-gray-400 mb-1">
                                        <?php echo esc_html($product['category']); ?>
                                    </div>
                                    <h3 class="product-title text-lg font-bold mb-2">
                                        <a href="#" class="hover:text-primary transition-colors">
                                            <?php echo esc_html($product['name']); ?>
                                        </a>
                                    </h3>
                                    <div class="product-rating flex items-center mb-2">
                                        <div class="stars text-accent">
                                            <i class="fas fa-star"></i>
                                        </div>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">
                                            <?php echo esc_html($product['rating']); ?> (<?php echo esc_html($product['reviews']); ?>)
                                        </span>
                                    </div>
                                    <div class="product-price-wrapper flex items-center justify-between mb-3">
                                        <div class="product-price font-bold text-lg">$<?php echo esc_html(number_format($product['price'], 2)); ?></div>
                                    </div>
                                    <button class="w-full py-2 px-4 bg-primary hover:bg-primary-dark text-white rounded-lg transition-colors">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="pagination flex justify-center mt-8">
                        <div class="flex items-center space-x-1">
                            <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                            <a href="#" class="px-4 py-2 bg-primary text-white rounded-md">1</a>
                            <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">2</a>
                            <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">3</a>
                            <span class="px-4 py-2">...</span>
                            <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">8</a>
                            <a href="#" class="px-4 py-2 bg-light-dark dark:bg-dark-light rounded-md hover:bg-primary hover:text-white transition-colors">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Reviews -->
                <div class="vendor-reviews">
                    <div class="section-header flex justify-between items-center mb-6">
                        <h2 class="text-xl font-bold">Customer Reviews</h2>
                        <a href="#" class="text-sm text-primary hover:text-primary-dark transition-colors">View All</a>
                    </div>
                    
                    <div class="space-y-6">
                        <?php foreach ($vendor_reviews as $review) : ?>
                            <div class="review-card bg-white dark:bg-dark-medium rounded-lg shadow-soft p-6">
                                <div class="review-header flex items-start justify-between mb-4">
                                    <div class="review-author flex items-center">
                                        <div class="review-author-image w-10 h-10 rounded-full overflow-hidden mr-3">
                                            <img src="<?php echo esc_url($review['customer_image']); ?>" alt="<?php echo esc_attr($review['customer_name']); ?>" class="w-full h-full object-cover">
                                        </div>
                                        <div class="review-author-info">
                                            <div class="review-author-name font-medium"><?php echo esc_html($review['customer_name']); ?></div>
                                            <div class="review-date text-xs text-gray-500 dark:text-gray-400"><?php echo esc_html(date('F j, Y', strtotime($review['date']))); ?></div>
                                        </div>
                                    </div>
                                    <div class="review-rating text-accent">
                                        <?php for ($i = 1; $i <= 5; $i++) : ?>
                                            <?php if ($i <= $review['rating']) : ?>
                                                <i class="fas fa-star"></i>
                                            <?php else : ?>
                                                <i class="far fa-star"></i>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="review-product text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    Product: <a href="#" class="text-primary hover:text-primary-dark transition-colors"><?php echo esc_html($review['product']); ?></a>
                                </div>
                                <div class="review-content text-gray-600 dark:text-gray-400">
                                    <?php echo esc_html($review['content']); ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>