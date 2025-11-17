<?php
/**
 * Template Name: Homepage
 *
 * The template for displaying the homepage.
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    /**
     * Hook: aqualuxe_before_homepage_content.
     *
     * @hooked aqualuxe_homepage_hero - 10
     * @hooked aqualuxe_homepage_featured_categories - 20
     */
    do_action('aqualuxe_before_homepage_content');
    ?>

    <section class="homepage-hero">
        <div class="homepage-hero__slider">
            <div class="homepage-hero__slide" style="background-image: url('<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/hero-1.jpg')); ?>');">
                <div class="container">
                    <div class="homepage-hero__content">
                        <h2 class="homepage-hero__title">Luxury Aquatic Products</h2>
                        <p class="homepage-hero__subtitle">Discover our premium collection of aquatic supplies and accessories</p>
                        <div class="homepage-hero__buttons">
                            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary"><?php esc_html_e('Shop Now', 'aqualuxe'); ?></a>
                            <a href="<?php echo esc_url(get_permalink(get_page_by_path('about-us'))); ?>" class="btn btn-outline"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="homepage-categories">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e('Shop by Category', 'aqualuxe'); ?></h2>
                <p class="section-description"><?php esc_html_e('Explore our wide range of premium aquatic products', 'aqualuxe'); ?></p>
            </div>

            <div class="category-grid">
                <?php
                // Get product categories
                $categories = get_terms(array(
                    'taxonomy' => 'product_cat',
                    'hide_empty' => true,
                    'parent' => 0,
                    'number' => 4,
                ));

                if ($categories) {
                    foreach ($categories as $category) {
                        // Get category image
                        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);
                        $image = $thumbnail_id ? wp_get_attachment_url($thumbnail_id) : wc_placeholder_img_src();
                ?>
                        <div class="category-card">
                            <a href="<?php echo esc_url(get_term_link($category)); ?>" class="category-card__link">
                                <div class="category-card__image" style="background-image: url('<?php echo esc_url($image); ?>');">
                                    <div class="category-card__overlay"></div>
                                    <h3 class="category-card__title"><?php echo esc_html($category->name); ?></h3>
                                </div>
                            </a>
                        </div>
                <?php
                    }
                }
                ?>
            </div>
        </div>
    </section>

    <section class="homepage-featured">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e('Featured Products', 'aqualuxe'); ?></h2>
                <p class="section-description"><?php esc_html_e('Our handpicked selection of premium products', 'aqualuxe'); ?></p>
            </div>

            <div class="featured-products">
                <?php
                // Display featured products
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 4,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'product_visibility',
                            'field' => 'name',
                            'terms' => 'featured',
                            'operator' => 'IN',
                        ),
                    ),
                );

                $featured_query = new WP_Query($args);

                if ($featured_query->have_posts()) {
                    woocommerce_product_loop_start();

                    while ($featured_query->have_posts()) {
                        $featured_query->the_post();
                        wc_get_template_part('content', 'product');
                    }

                    woocommerce_product_loop_end();
                    wp_reset_postdata();
                } else {
                    echo '<p class="no-products">' . esc_html__('No featured products found', 'aqualuxe') . '</p>';
                }
                ?>
            </div>

            <div class="section-footer">
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-primary"><?php esc_html_e('View All Products', 'aqualuxe'); ?></a>
            </div>
        </div>
    </section>

    <section class="homepage-banner" style="background-image: url('<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/banner.jpg')); ?>');">
        <div class="container">
            <div class="homepage-banner__content">
                <h2 class="homepage-banner__title"><?php esc_html_e('Premium Aquatic Solutions', 'aqualuxe'); ?></h2>
                <p class="homepage-banner__text"><?php esc_html_e('Discover our exclusive collection of luxury aquatic products designed for enthusiasts and professionals', 'aqualuxe'); ?></p>
                <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="btn btn-light"><?php esc_html_e('Explore Collection', 'aqualuxe'); ?></a>
            </div>
        </div>
    </section>

    <section class="homepage-bestsellers">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e('Best Sellers', 'aqualuxe'); ?></h2>
                <p class="section-description"><?php esc_html_e('Our most popular products loved by customers', 'aqualuxe'); ?></p>
            </div>

            <div class="bestseller-products">
                <?php
                // Display best selling products
                $args = array(
                    'post_type' => 'product',
                    'posts_per_page' => 4,
                    'meta_key' => 'total_sales',
                    'orderby' => 'meta_value_num',
                    'order' => 'DESC',
                );

                $bestseller_query = new WP_Query($args);

                if ($bestseller_query->have_posts()) {
                    woocommerce_product_loop_start();

                    while ($bestseller_query->have_posts()) {
                        $bestseller_query->the_post();
                        wc_get_template_part('content', 'product');
                    }

                    woocommerce_product_loop_end();
                    wp_reset_postdata();
                } else {
                    echo '<p class="no-products">' . esc_html__('No products found', 'aqualuxe') . '</p>';
                }
                ?>
            </div>
        </div>
    </section>

    <section class="homepage-features">
        <div class="container">
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M8.965 18a3.5 3.5 0 0 1-6.93 0H1V6a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v2h3l3 4.056V18h-2.035a3.5 3.5 0 0 1-6.93 0h-5.07zM15 7H3v8.05a3.5 3.5 0 0 1 5.663.95h5.674c.168-.353.393-.674.663-.95V7zm2 6h4v-.285L18.5 10.5H17V13zm-8 5a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3zm12 0a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"/></svg>
                    </div>
                    <h3 class="feature-card__title"><?php esc_html_e('Free Shipping', 'aqualuxe'); ?></h3>
                    <p class="feature-card__text"><?php esc_html_e('Free shipping on all orders over $100', 'aqualuxe'); ?></p>
                </div>

                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm1-8h4v2h-6V7h2v5z"/></svg>
                    </div>
                    <h3 class="feature-card__title"><?php esc_html_e('24/7 Support', 'aqualuxe'); ?></h3>
                    <p class="feature-card__text"><?php esc_html_e('Round-the-clock customer support', 'aqualuxe'); ?></p>
                </div>

                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-5-7h9v2h-4v3l-5-5zm5-4V6l5 5H8V9h4z"/></svg>
                    </div>
                    <h3 class="feature-card__title"><?php esc_html_e('Easy Returns', 'aqualuxe'); ?></h3>
                    <p class="feature-card__text"><?php esc_html_e('30-day money-back guarantee', 'aqualuxe'); ?></p>
                </div>

                <div class="feature-card">
                    <div class="feature-card__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm0-2a8 8 0 1 0 0-16 8 8 0 0 0 0 16zm-.997-4L6.76 11.757l1.414-1.414 2.829 2.829 5.656-5.657 1.415 1.414L11.003 16z"/></svg>
                    </div>
                    <h3 class="feature-card__title"><?php esc_html_e('Secure Payments', 'aqualuxe'); ?></h3>
                    <p class="feature-card__text"><?php esc_html_e('100% secure payment processing', 'aqualuxe'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="homepage-testimonials">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e('What Our Customers Say', 'aqualuxe'); ?></h2>
                <p class="section-description"><?php esc_html_e('Testimonials from our satisfied customers', 'aqualuxe'); ?></p>
            </div>

            <div class="testimonials-slider">
                <div class="testimonial-card">
                    <div class="testimonial-card__content">
                        <p class="testimonial-card__text">"The quality of AquaLuxe products is outstanding. I've been using their aquarium systems for years and couldn't be happier with the results."</p>
                    </div>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">
                            <img src="<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/avatar-1.jpg')); ?>" alt="Customer Avatar">
                        </div>
                        <div class="testimonial-card__info">
                            <h4 class="testimonial-card__name">John Smith</h4>
                            <p class="testimonial-card__title">Aquarium Enthusiast</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-card__content">
                        <p class="testimonial-card__text">"Exceptional customer service and premium products. AquaLuxe has transformed my aquatic setup into something truly special."</p>
                    </div>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">
                            <img src="<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/avatar-2.jpg')); ?>" alt="Customer Avatar">
                        </div>
                        <div class="testimonial-card__info">
                            <h4 class="testimonial-card__name">Sarah Johnson</h4>
                            <p class="testimonial-card__title">Marine Biologist</p>
                        </div>
                    </div>
                </div>

                <div class="testimonial-card">
                    <div class="testimonial-card__content">
                        <p class="testimonial-card__text">"The attention to detail in every AquaLuxe product is remarkable. Their filtration systems are the best in the industry."</p>
                    </div>
                    <div class="testimonial-card__author">
                        <div class="testimonial-card__avatar">
                            <img src="<?php echo esc_url(get_theme_file_uri('/assets/dist/images/placeholder/avatar-3.jpg')); ?>" alt="Customer Avatar">
                        </div>
                        <div class="testimonial-card__info">
                            <h4 class="testimonial-card__name">Michael Brown</h4>
                            <p class="testimonial-card__title">Professional Aquarist</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="homepage-blog">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e('Latest Articles', 'aqualuxe'); ?></h2>
                <p class="section-description"><?php esc_html_e('Tips, guides, and news from our blog', 'aqualuxe'); ?></p>
            </div>

            <div class="blog-grid">
                <?php
                // Display latest blog posts
                $args = array(
                    'post_type' => 'post',
                    'posts_per_page' => 3,
                    'ignore_sticky_posts' => true,
                );

                $blog_query = new WP_Query($args);

                if ($blog_query->have_posts()) {
                    while ($blog_query->have_posts()) {
                        $blog_query->the_post();
                ?>
                        <article class="blog-card">
                            <a href="<?php the_permalink(); ?>" class="blog-card__image-link">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="blog-card__image">
                                        <?php the_post_thumbnail('medium_large'); ?>
                                    </div>
                                <?php endif; ?>
                            </a>
                            <div class="blog-card__content">
                                <div class="blog-card__meta">
                                    <span class="blog-card__date"><?php echo get_the_date(); ?></span>
                                    <?php
                                    $categories = get_the_category();
                                    if (!empty($categories)) {
                                        echo '<span class="blog-card__category">' . esc_html($categories[0]->name) . '</span>';
                                    }
                                    ?>
                                </div>
                                <h3 class="blog-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <div class="blog-card__excerpt">
                                    <?php the_excerpt(); ?>
                                </div>
                                <a href="<?php the_permalink(); ?>" class="blog-card__read-more"><?php esc_html_e('Read More', 'aqualuxe'); ?></a>
                            </div>
                        </article>
                <?php
                    }
                    wp_reset_postdata();
                } else {
                    echo '<p class="no-posts">' . esc_html__('No posts found', 'aqualuxe') . '</p>';
                }
                ?>
            </div>

            <div class="section-footer">
                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline"><?php esc_html_e('View All Articles', 'aqualuxe'); ?></a>
            </div>
        </div>
    </section>

    <section class="homepage-newsletter">
        <div class="container">
            <div class="newsletter-container">
                <div class="newsletter-content">
                    <h2 class="newsletter-title"><?php esc_html_e('Subscribe to Our Newsletter', 'aqualuxe'); ?></h2>
                    <p class="newsletter-text"><?php esc_html_e('Get the latest updates, offers, and tips delivered directly to your inbox', 'aqualuxe'); ?></p>
                </div>
                <div class="newsletter-form">
                    <form action="#" method="post" class="newsletter-form__inner">
                        <div class="newsletter-form__input">
                            <input type="email" name="email" placeholder="<?php esc_attr_e('Your email address', 'aqualuxe'); ?>" required>
                        </div>
                        <div class="newsletter-form__submit">
                            <button type="submit" class="btn btn-primary"><?php esc_html_e('Subscribe', 'aqualuxe'); ?></button>
                        </div>
                    </form>
                    <p class="newsletter-privacy"><?php esc_html_e('By subscribing, you agree to our Privacy Policy', 'aqualuxe'); ?></p>
                </div>
            </div>
        </div>
    </section>

    <?php
    /**
     * Hook: aqualuxe_after_homepage_content.
     *
     * @hooked aqualuxe_homepage_instagram_feed - 10
     * @hooked aqualuxe_homepage_brands - 20
     */
    do_action('aqualuxe_after_homepage_content');
    ?>
</main><!-- #main -->

<?php
get_footer();