<?php
/**
 * The template for displaying the front page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    
    <!-- Hero Section -->
    <section class="hero-section bg-gradient-to-br from-aqua-50 to-luxe-50 relative overflow-hidden">
        <div class="container mx-auto px-4 py-20 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                <div class="hero-content" data-animate="fade-in">
                    <h1 class="text-4xl lg:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        <?php _e('Bringing elegance to', 'aqualuxe'); ?>
                        <span class="text-aqua-600"><?php _e('aquatic life', 'aqualuxe'); ?></span>
                        <span class="text-luxe-600"><?php _e('– globally', 'aqualuxe'); ?></span>
                    </h1>
                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        <?php _e('Discover premium ornamental fish, aquatic plants, and expert aquarium solutions from trusted breeders and suppliers worldwide.', 'aqualuxe'); ?>
                    </p>
                    <div class="hero-actions flex flex-col sm:flex-row gap-4">
                        <?php if (aqualuxe_is_woocommerce_active()) : ?>
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                               class="btn btn-primary bg-aqua-600 hover:bg-aqua-700 text-white px-8 py-4 rounded-lg font-semibold transition-all duration-300 transform hover:scale-105">
                                <?php _e('Explore Shop', 'aqualuxe'); ?>
                            </a>
                        <?php endif; ?>
                        <a href="#featured-categories" 
                           class="btn btn-secondary border-2 border-gray-300 text-gray-700 px-8 py-4 rounded-lg font-semibold hover:border-aqua-600 hover:text-aqua-600 transition-all duration-300">
                            <?php _e('Browse Categories', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
                <div class="hero-image" data-animate="fade-in">
                    <div class="relative">
                        <img src="<?php echo AQUALUXE_THEME_URI . '/assets/images/hero-aquarium.jpg'; ?>" 
                             alt="<?php esc_attr_e('Premium Aquarium Setup', 'aqualuxe'); ?>"
                             class="rounded-2xl shadow-2xl w-full h-auto"
                             loading="lazy">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-2xl"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Decorative elements -->
        <div class="absolute top-10 right-10 w-20 h-20 bg-aqua-200 rounded-full opacity-50"></div>
        <div class="absolute bottom-10 left-10 w-32 h-32 bg-luxe-200 rounded-full opacity-30"></div>
    </section>

    <!-- Featured Categories -->
    <section id="featured-categories" class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    <?php _e('Featured Categories', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?php _e('From rare exotic fish to professional aquarium equipment, find everything you need for your aquatic passion.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                <?php
                $categories = array(
                    array(
                        'title' => __('Rare Fish Species', 'aqualuxe'),
                        'description' => __('Exotic and rare ornamental fish from certified breeders', 'aqualuxe'),
                        'icon' => '🐠',
                        'link' => aqualuxe_is_woocommerce_active() ? wc_get_page_permalink('shop') . '?category=fish' : '#',
                    ),
                    array(
                        'title' => __('Aquatic Plants', 'aqualuxe'),
                        'description' => __('Premium aquascaping plants and mosses', 'aqualuxe'),
                        'icon' => '🌿',
                        'link' => aqualuxe_is_woocommerce_active() ? wc_get_page_permalink('shop') . '?category=plants' : '#',
                    ),
                    array(
                        'title' => __('Equipment & Care', 'aqualuxe'),
                        'description' => __('Professional aquarium equipment and supplies', 'aqualuxe'),
                        'icon' => '⚙️',
                        'link' => aqualuxe_is_woocommerce_active() ? wc_get_page_permalink('shop') . '?category=equipment' : '#',
                    ),
                    array(
                        'title' => __('Expert Services', 'aqualuxe'),
                        'description' => __('Design, installation, and maintenance services', 'aqualuxe'),
                        'icon' => '🔧',
                        'link' => home_url('/services/'),
                    ),
                );
                
                foreach ($categories as $index => $category) :
                ?>
                    <div class="category-card bg-gray-50 rounded-xl p-8 text-center hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2" 
                         data-animate="fade-in" style="animation-delay: <?php echo $index * 0.1; ?>s">
                        <div class="category-icon text-6xl mb-4"><?php echo $category['icon']; ?></div>
                        <h3 class="text-xl font-bold text-gray-900 mb-3"><?php echo $category['title']; ?></h3>
                        <p class="text-gray-600 mb-6"><?php echo $category['description']; ?></p>
                        <a href="<?php echo esc_url($category['link']); ?>" 
                           class="inline-flex items-center text-aqua-600 font-semibold hover:text-aqua-700 transition-colors">
                            <?php _e('Explore', 'aqualuxe'); ?>
                            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <?php if (aqualuxe_is_woocommerce_active()) : ?>
    <!-- Featured Products -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    <?php _e('Featured Products', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?php _e('Handpicked premium products from our trusted network of breeders and suppliers.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <?php
            // Get featured products
            $featured_products = wc_get_featured_product_ids();
            if (!empty($featured_products)) {
                $products_query = new WP_Query(array(
                    'post_type' => 'product',
                    'post__in' => array_slice($featured_products, 0, 8),
                    'posts_per_page' => 8,
                ));
                
                if ($products_query->have_posts()) :
                ?>
                    <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                        <?php 
                        $index = 0;
                        while ($products_query->have_posts()) : $products_query->the_post(); 
                            global $product;
                        ?>
                            <div class="product-card bg-white rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2" 
                                 data-animate="fade-in" style="animation-delay: <?php echo $index * 0.1; ?>s">
                                <div class="product-image relative">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
                                    </a>
                                    <?php if ($product->is_on_sale()) : ?>
                                        <span class="sale-badge absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                            <?php _e('Sale!', 'aqualuxe'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <div class="product-content p-6">
                                    <h3 class="text-lg font-bold text-gray-900 mb-2">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-aqua-600 transition-colors">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    <div class="product-price text-xl font-bold text-aqua-600 mb-4">
                                        <?php echo $product->get_price_html(); ?>
                                    </div>
                                    <div class="product-actions">
                                        <a href="<?php the_permalink(); ?>" 
                                           class="btn btn-primary w-full bg-aqua-600 hover:bg-aqua-700 text-white py-2 px-4 rounded-lg font-semibold transition-colors">
                                            <?php _e('View Details', 'aqualuxe'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            $index++;
                        endwhile; 
                        wp_reset_postdata();
                        ?>
                    </div>
                    
                    <div class="text-center mt-12">
                        <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                           class="btn btn-secondary border-2 border-aqua-600 text-aqua-600 px-8 py-4 rounded-lg font-semibold hover:bg-aqua-600 hover:text-white transition-all duration-300">
                            <?php _e('View All Products', 'aqualuxe'); ?>
                        </a>
                    </div>
                <?php endif; ?>
            <?php } else { ?>
                <div class="text-center py-16">
                    <p class="text-gray-600 text-lg"><?php _e('No featured products available yet.', 'aqualuxe'); ?></p>
                    <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" 
                       class="btn btn-primary mt-6 bg-aqua-600 hover:bg-aqua-700 text-white px-8 py-4 rounded-lg font-semibold transition-colors">
                        <?php _e('Browse Shop', 'aqualuxe'); ?>
                    </a>
                </div>
            <?php } ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- About Section -->
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="about-content" data-animate="fade-in">
                    <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-6">
                        <?php _e('Your trusted partner in aquatic excellence', 'aqualuxe'); ?>
                    </h2>
                    <p class="text-lg text-gray-600 mb-6 leading-relaxed">
                        <?php _e('AquaLuxe connects passionate aquarists with premium ornamental fish, plants, and equipment from trusted breeders and suppliers worldwide. We are committed to sustainable practices, ethical breeding, and exceptional customer service.', 'aqualuxe'); ?>
                    </p>
                    
                    <div class="features-list space-y-4 mb-8">
                        <?php
                        $features = array(
                            __('Certified and quarantined livestock', 'aqualuxe'),
                            __('Global shipping with live arrival guarantee', 'aqualuxe'),
                            __('Expert consultation and support', 'aqualuxe'),
                            __('Sustainable and ethical practices', 'aqualuxe'),
                        );
                        
                        foreach ($features as $feature) :
                        ?>
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-aqua-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-gray-700"><?php echo $feature; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <a href="<?php echo home_url('/about/'); ?>" 
                       class="btn btn-primary bg-luxe-600 hover:bg-luxe-700 text-white px-8 py-4 rounded-lg font-semibold transition-colors">
                        <?php _e('Learn More About Us', 'aqualuxe'); ?>
                    </a>
                </div>
                
                <div class="about-image" data-animate="fade-in">
                    <div class="relative">
                        <img src="<?php echo AQUALUXE_THEME_URI . '/assets/images/about-aquarist.jpg'; ?>" 
                             alt="<?php esc_attr_e('Professional Aquarist', 'aqualuxe'); ?>"
                             class="rounded-2xl shadow-2xl w-full h-auto"
                             loading="lazy">
                        <div class="absolute -bottom-6 -right-6 bg-aqua-600 text-white p-6 rounded-xl shadow-xl">
                            <div class="text-3xl font-bold">15+</div>
                            <div class="text-sm"><?php _e('Years Experience', 'aqualuxe'); ?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    <?php _e('What Our Customers Say', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    <?php _e('Join thousands of satisfied customers who trust AquaLuxe for their aquatic needs.', 'aqualuxe'); ?>
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <?php
                $testimonials = array(
                    array(
                        'content' => __('Absolutely amazing quality fish and plants! The packaging was perfect and everything arrived healthy. Customer service is top-notch.', 'aqualuxe'),
                        'author' => __('Sarah Johnson', 'aqualuxe'),
                        'location' => __('California, USA', 'aqualuxe'),
                        'rating' => 5,
                    ),
                    array(
                        'content' => __('I\'ve been ordering from AquaLuxe for over 2 years. Their expertise and quality are unmatched. Highly recommended for serious aquarists.', 'aqualuxe'),
                        'author' => __('Michael Chen', 'aqualuxe'),
                        'location' => __('Singapore', 'aqualuxe'),
                        'rating' => 5,
                    ),
                    array(
                        'content' => __('The consultation service helped me design the perfect aquarium setup. The team really knows their stuff and cares about customer success.', 'aqualuxe'),
                        'author' => __('Emma Rodriguez', 'aqualuxe'),
                        'location' => __('Madrid, Spain', 'aqualuxe'),
                        'rating' => 5,
                    ),
                );
                
                foreach ($testimonials as $index => $testimonial) :
                ?>
                    <div class="testimonial-card bg-white rounded-xl p-8 shadow-lg" 
                         data-animate="fade-in" style="animation-delay: <?php echo $index * 0.2; ?>s">
                        <div class="testimonial-rating mb-4">
                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                <svg class="w-5 h-5 text-yellow-400 inline" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>
                        <blockquote class="text-gray-700 text-lg mb-6 italic">
                            "<?php echo $testimonial['content']; ?>"
                        </blockquote>
                        <div class="testimonial-author">
                            <div class="font-semibold text-gray-900"><?php echo $testimonial['author']; ?></div>
                            <div class="text-gray-600"><?php echo $testimonial['location']; ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- Newsletter Signup -->
    <section class="py-20 bg-gradient-to-r from-aqua-600 to-luxe-600 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl lg:text-4xl font-bold mb-6">
                    <?php _e('Stay Updated with AquaLuxe', 'aqualuxe'); ?>
                </h2>
                <p class="text-xl mb-8 opacity-90">
                    <?php _e('Get the latest updates on new arrivals, expert tips, and exclusive offers delivered to your inbox.', 'aqualuxe'); ?>
                </p>
                
                <form class="newsletter-form max-w-lg mx-auto">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="email" 
                               placeholder="<?php esc_attr_e('Enter your email address', 'aqualuxe'); ?>"
                               class="flex-1 px-6 py-4 rounded-lg text-gray-900 border-0 focus:ring-4 focus:ring-white/25 outline-none"
                               required>
                        <button type="submit" 
                                class="bg-white text-aqua-600 px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            <?php _e('Subscribe', 'aqualuxe'); ?>
                        </button>
                    </div>
                    <p class="text-sm mt-4 opacity-75">
                        <?php _e('By subscribing, you agree to our Privacy Policy and Terms of Service.', 'aqualuxe'); ?>
                    </p>
                </form>
            </div>
        </div>
    </section>

</main>

<?php
get_footer();
?>