<?php
/**
 * Template Name: AquaLuxe Homepage
 * 
 * Homepage template for AquaLuxe Premium Ornamental Aquatic Solutions
 * Showcases business model, products, services, and global capabilities
 * 
 * @package AquaLuxe_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

get_header(); ?>

<!-- Hero Section -->
<section class="hero-section aqualuxe-hero" id="hero">
    <div class="hero-background">
        <div class="hero-video-container">
            <video autoplay muted loop class="hero-video">
                <source src="<?php echo AQUALUXE_THEME_URL; ?>/assets/videos/aquarium-hero.mp4" type="video/mp4">
            </video>
            <div class="hero-overlay"></div>
        </div>
    </div>
    
    <div class="container">
        <div class="row align-items-center min-vh-100">
            <div class="col-lg-8 mx-auto text-center">
                <div class="hero-content">
                    <h1 class="hero-title animate-fade-up">
                        <span class="brand-name">AquaLuxe</span>
                        <span class="hero-subtitle">Premium Ornamental Aquatic Solutions</span>
                    </h1>
                    
                    <p class="hero-description animate-fade-up delay-1">
                        <?php echo AquaLuxe_Config::BUSINESS_TAGLINE; ?>
                    </p>
                    
                    <div class="hero-features animate-fade-up delay-2">
                        <div class="feature-pills">
                            <span class="feature-pill">🐠 Premium Fish</span>
                            <span class="feature-pill">🌿 Aquatic Plants</span>
                            <span class="feature-pill">🏗️ Custom Aquariums</span>
                            <span class="feature-pill">🌍 Global Export</span>
                        </div>
                    </div>
                    
                    <div class="hero-actions animate-fade-up delay-3">
                        <a href="/shop/" class="btn btn-primary btn-lg me-3">
                            <i class="fas fa-shopping-cart me-2"></i>
                            Shop Now
                        </a>
                        <a href="/services/" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-tools me-2"></i>
                            Our Services
                        </a>
                    </div>
                    
                    <div class="hero-stats animate-fade-up delay-4">
                        <div class="row text-center">
                            <div class="col-3">
                                <div class="stat-item">
                                    <div class="stat-number">500+</div>
                                    <div class="stat-label">Fish Species</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="stat-item">
                                    <div class="stat-number">50+</div>
                                    <div class="stat-label">Countries</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="stat-item">
                                    <div class="stat-number">10k+</div>
                                    <div class="stat-label">Happy Customers</div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="stat-item">
                                    <div class="stat-number">24/7</div>
                                    <div class="stat-label">Support</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="hero-scroll-indicator">
        <a href="#business-models" class="scroll-down">
            <i class="fas fa-chevron-down"></i>
        </a>
    </div>
</section>

<!-- Business Models Section -->
<section class="business-models-section py-5" id="business-models">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Our Business Solutions</h2>
                <p class="section-subtitle">Comprehensive aquatic solutions for every need</p>
            </div>
        </div>
        
        <div class="row">
            <?php 
            $business_models = AquaLuxe_Config::get_business_models();
            foreach ($business_models as $key => $model) :
                if (!$model['enabled']) continue;
            ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="business-model-card">
                        <div class="card-icon">
                            <?php
                            $icons = array(
                                'retail' => 'fas fa-store',
                                'wholesale' => 'fas fa-warehouse',
                                'trading' => 'fas fa-exchange-alt',
                                'services' => 'fas fa-tools',
                                'export' => 'fas fa-globe',
                                'subscription' => 'fas fa-sync-alt'
                            );
                            ?>
                            <i class="<?php echo $icons[$key]; ?>"></i>
                        </div>
                        <h3 class="card-title"><?php echo esc_html($model['name']); ?></h3>
                        <p class="card-description"><?php echo esc_html($model['description']); ?></p>
                        <ul class="card-features">
                            <?php foreach ($model['features'] as $feature) : ?>
                                <li><i class="fas fa-check text-success me-2"></i><?php echo esc_html(str_replace('_', ' ', ucfirst($feature))); ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <div class="card-action">
                            <a href="/<?php echo $key; ?>/" class="btn btn-outline-primary">Learn More</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Product Categories Section -->
<section class="product-categories-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Product Categories</h2>
                <p class="section-subtitle">Discover our extensive range of aquatic products</p>
            </div>
        </div>
        
        <div class="row">
            <?php 
            $categories = AquaLuxe_Config::get_product_categories();
            foreach ($categories as $key => $category) :
            ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="category-card">
                        <div class="category-image">
                            <img src="<?php echo AQUALUXE_THEME_URL; ?>/assets/images/categories/<?php echo $key; ?>.jpg" 
                                 alt="<?php echo esc_attr($category['name']); ?>" 
                                 class="img-fluid" 
                                 loading="lazy">
                            <div class="category-overlay">
                                <i class="<?php echo $category['icon']; ?> category-icon"></i>
                            </div>
                        </div>
                        <div class="category-content">
                            <h3 class="category-title"><?php echo esc_html($category['name']); ?></h3>
                            <div class="category-subcategories">
                                <?php foreach ($category['subcategories'] as $sub_key => $sub_name) : ?>
                                    <a href="/shop/<?php echo $key; ?>/<?php echo $sub_key; ?>/" class="subcategory-link">
                                        <?php echo esc_html($sub_name); ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                            <div class="category-action">
                                <a href="/shop/<?php echo $key; ?>/" class="btn btn-primary">
                                    Explore <?php echo esc_html($category['name']); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Services Showcase -->
<section class="services-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Professional Services</h2>
                <p class="section-subtitle">Expert aquarium solutions from design to maintenance</p>
            </div>
        </div>
        
        <div class="row">
            <?php 
            $services = AquaLuxe_Config::get_service_types();
            $service_icons = array(
                'design_installation' => 'fas fa-drafting-compass',
                'maintenance' => 'fas fa-wrench',
                'quarantine' => 'fas fa-shield-alt',
                'training' => 'fas fa-graduation-cap',
                'rental' => 'fas fa-calendar-check'
            );
            
            foreach ($services as $key => $service) :
            ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="service-card">
                        <div class="service-icon">
                            <i class="<?php echo $service_icons[$key]; ?>"></i>
                        </div>
                        <h3 class="service-title"><?php echo esc_html($service['name']); ?></h3>
                        <p class="service-description"><?php echo esc_html($service['description']); ?></p>
                        <div class="service-meta">
                            <span class="service-duration">
                                <i class="fas fa-clock me-1"></i>
                                Duration: <?php echo esc_html($service['duration']); ?>
                            </span>
                            <span class="service-pricing">
                                <i class="fas fa-tag me-1"></i>
                                Pricing: <?php echo esc_html(str_replace('_', ' ', ucfirst($service['pricing_type']))); ?>
                            </span>
                        </div>
                        <div class="service-action">
                            <a href="/services/<?php echo $key; ?>/" class="btn btn-outline-primary">
                                Learn More
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Global Reach Section -->
<section class="global-reach-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title text-white">Global Export Capabilities</h2>
                <p class="section-subtitle text-white-50">Serving aquatic enthusiasts worldwide with certified exports</p>
                
                <div class="export-features">
                    <div class="export-feature">
                        <i class="fas fa-certificate text-warning me-3"></i>
                        <div>
                            <h4>Certified Exports</h4>
                            <p class="mb-0">All necessary certifications and compliance documentation</p>
                        </div>
                    </div>
                    <div class="export-feature">
                        <i class="fas fa-shipping-fast text-warning me-3"></i>
                        <div>
                            <h4>Fast Shipping</h4>
                            <p class="mb-0">Express delivery to major international destinations</p>
                        </div>
                    </div>
                    <div class="export-feature">
                        <i class="fas fa-shield-alt text-warning me-3"></i>
                        <div>
                            <h4>Health Guarantee</h4>
                            <p class="mb-0">Quarantined and health-checked livestock</p>
                        </div>
                    </div>
                </div>
                
                <div class="export-action mt-4">
                    <a href="/export/" class="btn btn-light btn-lg me-3">
                        <i class="fas fa-globe me-2"></i>
                        Export Information
                    </a>
                    <a href="/contact/" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-envelope me-2"></i>
                        Get Quote
                    </a>
                </div>
            </div>
            
            <div class="col-lg-6">
                <div class="export-map">
                    <img src="<?php echo AQUALUXE_THEME_URL; ?>/assets/images/world-map.svg" 
                         alt="Global Export Map" 
                         class="img-fluid">
                    
                    <div class="export-stats-grid">
                        <?php 
                        $export_countries = AquaLuxe_Config::get_export_countries();
                        foreach ($export_countries as $key => $region) :
                        ?>
                            <div class="export-stat">
                                <h3><?php echo count($region['countries']); ?>+</h3>
                                <p><?php echo esc_html($region['region']); ?></p>
                                <small><?php echo esc_html($region['lead_time']); ?> delivery</small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Products -->
<section class="featured-products-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">Featured Products</h2>
                <p class="section-subtitle">Handpicked premium specimens and equipment</p>
            </div>
        </div>
        
        <div class="featured-products-carousel">
            <div class="row">
                <!-- This would typically be populated with WooCommerce products -->
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="product-card">
                        <div class="product-image">
                            <img src="<?php echo AQUALUXE_THEME_URL; ?>/assets/images/products/discus-fish.jpg" 
                                 alt="Premium Discus Fish" 
                                 class="img-fluid" 
                                 loading="lazy">
                            <div class="product-badges">
                                <span class="badge badge-featured">Featured</span>
                                <span class="badge badge-new">New</span>
                            </div>
                        </div>
                        <div class="product-content">
                            <h3 class="product-title">Premium Discus Fish</h3>
                            <p class="product-price">$89.99</p>
                            <div class="product-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <span class="rating-count">(24)</span>
                            </div>
                            <div class="product-action">
                                <a href="/product/premium-discus-fish/" class="btn btn-primary btn-block">
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Additional featured products would go here -->
            </div>
        </div>
        
        <div class="text-center mt-4">
            <a href="/shop/" class="btn btn-outline-primary btn-lg">
                <i class="fas fa-th-large me-2"></i>
                View All Products
            </a>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials-section py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title">What Our Customers Say</h2>
                <p class="section-subtitle">Trusted by aquatic enthusiasts worldwide</p>
            </div>
        </div>
        
        <div class="testimonials-carousel">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <div class="testimonial-card">
                        <div class="testimonial-content">
                            <p>"AquaLuxe provided exceptional service for our hotel lobby aquarium. The fish arrived healthy and the installation was flawless."</p>
                        </div>
                        <div class="testimonial-author">
                            <img src="<?php echo AQUALUXE_THEME_URL; ?>/assets/images/testimonials/author-1.jpg" 
                                 alt="John Smith" 
                                 class="author-avatar">
                            <div class="author-info">
                                <h4>John Smith</h4>
                                <p>Hotel Manager, Luxury Resort</p>
                            </div>
                        </div>
                        <div class="testimonial-rating">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Additional testimonials would go here -->
            </div>
        </div>
    </div>
</section>

<!-- Newsletter & CTA Section -->
<section class="newsletter-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2>Stay Updated with AquaLuxe</h2>
                <p>Get the latest news on new arrivals, care tips, and exclusive offers</p>
            </div>
            <div class="col-lg-6">
                <form class="newsletter-form d-flex gap-3">
                    <input type="email" 
                           class="form-control" 
                           placeholder="Enter your email address" 
                           required>
                    <button type="submit" class="btn btn-light">
                        <i class="fas fa-paper-plane me-2"></i>
                        Subscribe
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<style>
/* AquaLuxe Homepage Styles */
.aqualuxe-hero {
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #0077BE 0%, #00A86B 100%);
}

.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1;
}

.hero-video-container {
    position: relative;
    width: 100%;
    height: 100%;
}

.hero-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.hero-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 119, 190, 0.7);
}

.hero-content {
    position: relative;
    z-index: 2;
    color: white;
}

.brand-name {
    display: block;
    font-size: 4rem;
    font-weight: 700;
    font-family: 'Playfair Display', serif;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
}

.hero-subtitle {
    display: block;
    font-size: 1.5rem;
    font-weight: 300;
    margin-top: 0.5rem;
    opacity: 0.9;
}

.hero-description {
    font-size: 1.25rem;
    margin: 2rem 0;
    opacity: 0.9;
}

.feature-pills {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
    margin: 2rem 0;
}

.feature-pill {
    background: rgba(255, 255, 255, 0.2);
    padding: 0.5rem 1rem;
    border-radius: 25px;
    font-size: 0.9rem;
    backdrop-filter: blur(10px);
}

.hero-actions {
    margin: 3rem 0;
}

.hero-stats {
    margin-top: 4rem;
}

.stat-item {
    color: white;
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: #FFD700;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.8;
}

.hero-scroll-indicator {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    z-index: 2;
}

.scroll-down {
    color: white;
    font-size: 1.5rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

/* Animation Classes */
.animate-fade-up {
    opacity: 0;
    transform: translateY(30px);
    animation: fadeUp 0.8s ease forwards;
}

.delay-1 { animation-delay: 0.2s; }
.delay-2 { animation-delay: 0.4s; }
.delay-3 { animation-delay: 0.6s; }
.delay-4 { animation-delay: 0.8s; }

@keyframes fadeUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Business Model Cards */
.business-model-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    height: 100%;
}

.business-model-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.card-icon {
    font-size: 3rem;
    color: #0077BE;
    margin-bottom: 1rem;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #1C2331;
}

.card-description {
    color: #666;
    margin-bottom: 1.5rem;
}

.card-features {
    list-style: none;
    padding: 0;
    margin-bottom: 2rem;
    text-align: left;
}

.card-features li {
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
}

/* Category Cards */
.category-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    height: 100%;
}

.category-card:hover {
    transform: translateY(-5px);
}

.category-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.category-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.category-card:hover .category-image img {
    transform: scale(1.1);
}

.category-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 119, 190, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.category-card:hover .category-overlay {
    opacity: 1;
}

.category-icon {
    color: white;
    font-size: 3rem;
}

.category-content {
    padding: 1.5rem;
}

.category-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #1C2331;
}

.category-subcategories {
    margin-bottom: 1.5rem;
}

.subcategory-link {
    display: inline-block;
    margin: 0.25rem;
    padding: 0.25rem 0.75rem;
    background: #F8F9FA;
    color: #666;
    text-decoration: none;
    border-radius: 20px;
    font-size: 0.85rem;
    transition: all 0.3s ease;
}

.subcategory-link:hover {
    background: #0077BE;
    color: white;
}

/* Service Cards */
.service-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    text-align: center;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    height: 100%;
}

.service-card:hover {
    transform: translateY(-5px);
}

.service-icon {
    font-size: 3rem;
    color: #00A86B;
    margin-bottom: 1rem;
}

.service-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    color: #1C2331;
}

.service-description {
    color: #666;
    margin-bottom: 1.5rem;
}

.service-meta {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1.5rem;
    font-size: 0.9rem;
    color: #666;
}

/* Export Features */
.export-feature {
    display: flex;
    align-items: flex-start;
    margin-bottom: 2rem;
}

.export-feature h4 {
    margin-bottom: 0.5rem;
    color: white;
}

.export-feature p {
    color: rgba(255, 255, 255, 0.8);
}

.export-map {
    position: relative;
    text-align: center;
}

.export-stats-grid {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.export-stat {
    background: rgba(255, 255, 255, 0.9);
    padding: 1rem;
    border-radius: 10px;
    color: #1C2331;
}

.export-stat h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 0.25rem;
    color: #0077BE;
}

/* Product Cards */
.product-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    transition: transform 0.3s ease;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-5px);
}

.product-image {
    position: relative;
    height: 250px;
    overflow: hidden;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.product-badges {
    position: absolute;
    top: 1rem;
    left: 1rem;
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.badge-featured {
    background: #FFD700;
    color: #1C2331;
}

.badge-new {
    background: #00C851;
    color: white;
}

.product-content {
    padding: 1.5rem;
}

.product-title {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #1C2331;
}

.product-price {
    font-size: 1.1rem;
    font-weight: 700;
    color: #0077BE;
    margin-bottom: 0.5rem;
}

.product-rating {
    color: #FFD700;
    margin-bottom: 1rem;
    font-size: 0.9rem;
}

.rating-count {
    color: #666;
    margin-left: 0.5rem;
}

/* Testimonial Cards */
.testimonial-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    height: 100%;
}

.testimonial-content {
    margin-bottom: 1.5rem;
    font-style: italic;
    color: #666;
}

.testimonial-author {
    display: flex;
    align-items: center;
    margin-bottom: 1rem;
}

.author-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    margin-right: 1rem;
}

.author-info h4 {
    margin-bottom: 0.25rem;
    font-size: 1rem;
    color: #1C2331;
}

.author-info p {
    margin: 0;
    font-size: 0.9rem;
    color: #666;
}

.testimonial-rating {
    color: #FFD700;
}

/* Newsletter Section */
.newsletter-form {
    max-width: 400px;
    margin-left: auto;
}

/* Responsive Design */
@media (max-width: 768px) {
    .brand-name {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.2rem;
    }
    
    .feature-pills {
        flex-direction: column;
        align-items: center;
    }
    
    .hero-actions .btn {
        display: block;
        width: 100%;
        margin-bottom: 1rem;
    }
    
    .export-stats-grid {
        position: relative;
        top: auto;
        left: auto;
        transform: none;
        grid-template-columns: 1fr;
        margin-top: 2rem;
    }
    
    .newsletter-form {
        flex-direction: column;
        max-width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Newsletter form submission
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Here you would typically send the email to your backend
            // For now, we'll just show a success message
            alert('Thank you for subscribing to AquaLuxe updates!');
            this.reset();
        });
    }
    
    // Initialize animations when elements come into view
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.animate-fade-up').forEach(el => {
        el.style.animationPlayState = 'paused';
        observer.observe(el);
    });
});
</script>

<?php get_footer(); ?>
