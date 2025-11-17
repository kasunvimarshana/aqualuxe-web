<?php
/**
 * The template for displaying single Species posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    while (have_posts()) :
        the_post();
        
        // Get species meta
        $scientific_name = get_post_meta(get_the_ID(), 'scientific_name', true);
        $origin = get_post_meta(get_the_ID(), 'origin', true);
        $habitat = get_post_meta(get_the_ID(), 'habitat', true);
        $care_level = get_post_meta(get_the_ID(), 'care_level', true);
        $size = get_post_meta(get_the_ID(), 'size', true);
        $lifespan = get_post_meta(get_the_ID(), 'lifespan', true);
        $diet = get_post_meta(get_the_ID(), 'diet', true);
        $temperature = get_post_meta(get_the_ID(), 'temperature', true);
        $ph_level = get_post_meta(get_the_ID(), 'ph_level', true);
        $tank_size = get_post_meta(get_the_ID(), 'tank_size', true);
        $temperament = get_post_meta(get_the_ID(), 'temperament', true);
        $breeding = get_post_meta(get_the_ID(), 'breeding', true);
        $gallery_images = get_post_meta(get_the_ID(), 'gallery_images', true);
        $video_url = get_post_meta(get_the_ID(), 'video_url', true);
        
        // Get related products if WooCommerce is active
        $related_products = array();
        if (class_exists('WooCommerce')) {
            $related_product_ids = get_post_meta(get_the_ID(), 'related_products', true);
            
            if (!empty($related_product_ids) && is_array($related_product_ids)) {
                $args = array(
                    'post_type' => 'product',
                    'post__in' => $related_product_ids,
                    'posts_per_page' => -1,
                );
                
                $related_products = new WP_Query($args);
            }
        }
    ?>

    <section class="species-header-section">
        <div class="container">
            <div class="species-header">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="species-header-content" data-aos="fade-right">
                            <h1 class="species-title"><?php the_title(); ?></h1>
                            
                            <?php if (!empty($scientific_name)) : ?>
                                <div class="species-scientific-name"><?php echo esc_html($scientific_name); ?></div>
                            <?php endif; ?>
                            
                            <div class="species-meta">
                                <?php if (!empty($origin)) : ?>
                                    <div class="species-meta-item">
                                        <span class="meta-label"><?php esc_html_e('Origin:', 'aqualuxe'); ?></span>
                                        <span class="meta-value"><?php echo esc_html($origin); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($habitat)) : ?>
                                    <div class="species-meta-item">
                                        <span class="meta-label"><?php esc_html_e('Habitat:', 'aqualuxe'); ?></span>
                                        <span class="meta-value"><?php echo esc_html($habitat); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($care_level)) : ?>
                                    <div class="species-meta-item">
                                        <span class="meta-label"><?php esc_html_e('Care Level:', 'aqualuxe'); ?></span>
                                        <span class="meta-value"><?php echo esc_html($care_level); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($size)) : ?>
                                    <div class="species-meta-item">
                                        <span class="meta-label"><?php esc_html_e('Size:', 'aqualuxe'); ?></span>
                                        <span class="meta-value"><?php echo esc_html($size); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($lifespan)) : ?>
                                    <div class="species-meta-item">
                                        <span class="meta-label"><?php esc_html_e('Lifespan:', 'aqualuxe'); ?></span>
                                        <span class="meta-value"><?php echo esc_html($lifespan); ?></span>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($temperament)) : ?>
                                    <div class="species-meta-item">
                                        <span class="meta-label"><?php esc_html_e('Temperament:', 'aqualuxe'); ?></span>
                                        <span class="meta-value"><?php echo esc_html($temperament); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="species-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <?php
                            // Species categories
                            $species_categories = get_the_terms(get_the_ID(), 'species_category');
                            if (!empty($species_categories) && !is_wp_error($species_categories)) :
                            ?>
                                <div class="species-categories">
                                    <span class="categories-label"><?php esc_html_e('Categories:', 'aqualuxe'); ?></span>
                                    <span class="categories-list">
                                        <?php
                                        $category_links = array();
                                        foreach ($species_categories as $category) {
                                            $category_links[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                                        }
                                        echo implode(', ', $category_links);
                                        ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Social sharing
                            if (get_theme_mod('aqualuxe_species_social_sharing', true)) :
                                $share_url = urlencode(get_permalink());
                                $share_title = urlencode(get_the_title());
                                $share_image = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(get_the_ID(), 'large')) : '';
                            ?>
                                <div class="species-social-sharing">
                                    <span class="sharing-label"><?php esc_html_e('Share:', 'aqualuxe'); ?></span>
                                    <div class="sharing-buttons">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="facebook" title="<?php esc_attr_e('Share on Facebook', 'aqualuxe'); ?>"><i class="fab fa-facebook-f"></i></a>
                                        <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" class="twitter" title="<?php esc_attr_e('Share on Twitter', 'aqualuxe'); ?>"><i class="fab fa-twitter"></i></a>
                                        <a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&media=<?php echo $share_image; ?>&description=<?php echo $share_title; ?>" target="_blank" class="pinterest" title="<?php esc_attr_e('Pin on Pinterest', 'aqualuxe'); ?>"><i class="fab fa-pinterest-p"></i></a>
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>" target="_blank" class="linkedin" title="<?php esc_attr_e('Share on LinkedIn', 'aqualuxe'); ?>"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="mailto:?subject=<?php echo $share_title; ?>&body=<?php echo $share_url; ?>" class="email" title="<?php esc_attr_e('Share via Email', 'aqualuxe'); ?>"><i class="fas fa-envelope"></i></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="col-lg-6">
                        <div class="species-header-image" data-aos="fade-left">
                            <?php
                            if (has_post_thumbnail()) {
                                the_post_thumbnail('large', array('class' => 'img-fluid'));
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="species-content-section section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="species-content" data-aos="fade-up">
                        <div class="species-description">
                            <?php the_content(); ?>
                        </div>
                        
                        <?php
                        // Species gallery
                        if (!empty($gallery_images) && is_array($gallery_images)) :
                        ?>
                            <div class="species-gallery" data-aos="fade-up">
                                <h3><?php esc_html_e('Species Gallery', 'aqualuxe'); ?></h3>
                                <div class="row">
                                    <?php foreach ($gallery_images as $image_id) : ?>
                                        <div class="col-md-4 col-6">
                                            <div class="gallery-item">
                                                <a href="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'full')); ?>" class="lightbox">
                                                    <?php echo wp_get_attachment_image($image_id, 'medium', false, array('class' => 'img-fluid')); ?>
                                                </a>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Species video
                        if (!empty($video_url)) :
                            // Extract video ID from YouTube URL
                            $video_id = '';
                            if (preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $video_url, $match)) {
                                $video_id = $match[1];
                            }
                            
                            if (!empty($video_id)) :
                        ?>
                            <div class="species-video" data-aos="fade-up">
                                <h3><?php esc_html_e('Watch Video', 'aqualuxe'); ?></h3>
                                <div class="video-wrapper">
                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/<?php echo esc_attr($video_id); ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                </div>
                            </div>
                        <?php
                            endif;
                        endif;
                        ?>
                    </div>
                </div>
                
                <div class="col-lg-4">
                    <div class="species-sidebar">
                        <?php if (!empty($temperature) || !empty($ph_level) || !empty($tank_size) || !empty($diet)) : ?>
                            <div class="species-care-info" data-aos="fade-up">
                                <h3><?php esc_html_e('Care Information', 'aqualuxe'); ?></h3>
                                <ul class="care-info-list">
                                    <?php if (!empty($temperature)) : ?>
                                        <li>
                                            <span class="info-icon"><i class="fas fa-thermometer-half"></i></span>
                                            <span class="info-label"><?php esc_html_e('Temperature:', 'aqualuxe'); ?></span>
                                            <span class="info-value"><?php echo esc_html($temperature); ?></span>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($ph_level)) : ?>
                                        <li>
                                            <span class="info-icon"><i class="fas fa-flask"></i></span>
                                            <span class="info-label"><?php esc_html_e('pH Level:', 'aqualuxe'); ?></span>
                                            <span class="info-value"><?php echo esc_html($ph_level); ?></span>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($tank_size)) : ?>
                                        <li>
                                            <span class="info-icon"><i class="fas fa-cube"></i></span>
                                            <span class="info-label"><?php esc_html_e('Tank Size:', 'aqualuxe'); ?></span>
                                            <span class="info-value"><?php echo esc_html($tank_size); ?></span>
                                        </li>
                                    <?php endif; ?>
                                    
                                    <?php if (!empty($diet)) : ?>
                                        <li>
                                            <span class="info-icon"><i class="fas fa-utensils"></i></span>
                                            <span class="info-label"><?php esc_html_e('Diet:', 'aqualuxe'); ?></span>
                                            <span class="info-value"><?php echo esc_html($diet); ?></span>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($breeding)) : ?>
                            <div class="species-breeding-info" data-aos="fade-up">
                                <h3><?php esc_html_e('Breeding Information', 'aqualuxe'); ?></h3>
                                <div class="breeding-content">
                                    <?php echo wp_kses_post($breeding); ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Related species
                        $related_args = array(
                            'post_type' => 'species',
                            'posts_per_page' => 3,
                            'post__not_in' => array(get_the_ID()),
                            'orderby' => 'rand',
                        );
                        
                        // If has categories, get related by category
                        $species_categories = get_the_terms(get_the_ID(), 'species_category');
                        if (!empty($species_categories) && !is_wp_error($species_categories)) {
                            $category_ids = array();
                            foreach ($species_categories as $category) {
                                $category_ids[] = $category->term_id;
                            }
                            
                            $related_args['tax_query'] = array(
                                array(
                                    'taxonomy' => 'species_category',
                                    'field' => 'term_id',
                                    'terms' => $category_ids,
                                ),
                            );
                        }
                        
                        $related_species = new WP_Query($related_args);
                        
                        if ($related_species->have_posts()) :
                        ?>
                            <div class="related-species" data-aos="fade-up">
                                <h3><?php esc_html_e('Related Species', 'aqualuxe'); ?></h3>
                                <div class="related-species-list">
                                    <?php
                                    while ($related_species->have_posts()) :
                                        $related_species->the_post();
                                        $related_scientific_name = get_post_meta(get_the_ID(), 'scientific_name', true);
                                    ?>
                                        <div class="related-species-item">
                                            <a href="<?php the_permalink(); ?>" class="related-species-link">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <div class="related-species-image">
                                                        <?php the_post_thumbnail('thumbnail', array('class' => 'img-fluid')); ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="related-species-content">
                                                    <h4 class="related-species-title"><?php the_title(); ?></h4>
                                                    
                                                    <?php if (!empty($related_scientific_name)) : ?>
                                                        <div class="related-species-scientific"><?php echo esc_html($related_scientific_name); ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </a>
                                        </div>
                                    <?php
                                    endwhile;
                                    wp_reset_postdata();
                                    ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <?php
    // Related products section
    if (!empty($related_products) && $related_products->have_posts()) :
    ?>
        <section class="related-products-section section bg-light">
            <div class="container">
                <div class="section-header text-center">
                    <h2 class="section-title" data-aos="fade-up"><?php esc_html_e('Related Products', 'aqualuxe'); ?></h2>
                </div>
                
                <div class="related-products" data-aos="fade-up" data-aos-delay="100">
                    <div class="row">
                        <?php
                        while ($related_products->have_posts()) :
                            $related_products->the_post();
                            global $product;
                            
                            if (!$product || !$product->is_visible()) {
                                continue;
                            }
                        ?>
                            <div class="col-lg-3 col-md-6">
                                <div class="product-card">
                                    <div class="product-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php echo woocommerce_get_product_thumbnail(); ?>
                                        </a>
                                        
                                        <?php if ($product->is_on_sale()) : ?>
                                            <span class="onsale"><?php esc_html_e('Sale!', 'aqualuxe'); ?></span>
                                        <?php endif; ?>
                                        
                                        <div class="product-actions">
                                            <?php
                                            echo apply_filters(
                                                'woocommerce_loop_add_to_cart_link',
                                                sprintf(
                                                    '<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
                                                    esc_url($product->add_to_cart_url()),
                                                    esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
                                                    esc_attr(isset($args['class']) ? $args['class'] : 'button'),
                                                    isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
                                                    '<i class="fas fa-shopping-cart"></i>'
                                                ),
                                                $product
                                            );
                                            ?>
                                            <a href="<?php the_permalink(); ?>" class="view-product"><i class="fas fa-eye"></i></a>
                                        </div>
                                    </div>
                                    
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        
                                        <div class="product-price">
                                            <?php echo $product->get_price_html(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                        ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>
    
    <?php
    // Navigation
    $prev_post = get_previous_post();
    $next_post = get_next_post();
    
    if ($prev_post || $next_post) :
    ?>
        <section class="species-navigation">
            <div class="container">
                <div class="post-navigation">
                    <div class="nav-links">
                        <?php if ($prev_post) : ?>
                            <div class="nav-previous">
                                <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>">
                                    <span class="nav-subtitle"><?php esc_html_e('Previous Species', 'aqualuxe'); ?></span>
                                    <span class="nav-title"><?php echo esc_html($prev_post->post_title); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ($next_post) : ?>
                            <div class="nav-next">
                                <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>">
                                    <span class="nav-subtitle"><?php esc_html_e('Next Species', 'aqualuxe'); ?></span>
                                    <span class="nav-title"><?php echo esc_html($next_post->post_title); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <?php endwhile; // End of the loop. ?>

</main><!-- #primary -->

<?php
get_footer();