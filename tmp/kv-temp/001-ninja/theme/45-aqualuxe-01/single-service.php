<?php
/**
 * The template for displaying single service posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();

// Get service details
$price = get_post_meta(get_the_ID(), 'service_price', true);
$duration = get_post_meta(get_the_ID(), 'service_duration', true);
$features = get_post_meta(get_the_ID(), 'service_features', true);
$gallery = get_post_meta(get_the_ID(), 'service_gallery', true);
$video_url = get_post_meta(get_the_ID(), 'service_video_url', true);
$product_id = get_post_meta(get_the_ID(), 'service_product_id', true);
?>

<main id="primary" class="site-main">
    <?php
    // Single Service Header
    get_template_part('template-parts/components/single-header', 'service');
    ?>

    <div class="container">
        <div class="service-single">
            <div class="row">
                <div class="col-lg-8">
                    <?php
                    while (have_posts()) :
                        the_post();
                        ?>
                        
                        <div class="service-content">
                            <?php
                            // Display gallery if available
                            if (!empty($gallery)) :
                            ?>
                            <div class="service-gallery">
                                <div class="service-gallery-slider">
                                    <?php
                                    foreach ($gallery as $image_id) :
                                        $image_url = wp_get_attachment_image_url($image_id, 'large');
                                        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                        ?>
                                        <div class="gallery-item">
                                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="img-fluid">
                                        </div>
                                        <?php
                                    endforeach;
                                    ?>
                                </div>
                                
                                <div class="service-gallery-thumbs">
                                    <?php
                                    foreach ($gallery as $image_id) :
                                        $image_url = wp_get_attachment_image_url($image_id, 'thumbnail');
                                        $image_alt = get_post_meta($image_id, '_wp_attachment_image_alt', true);
                                        ?>
                                        <div class="thumb-item">
                                            <img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>" class="img-fluid">
                                        </div>
                                        <?php
                                    endforeach;
                                    ?>
                                </div>
                            </div>
                            <?php
                            // If no gallery, display featured image
                            elseif (has_post_thumbnail()) :
                            ?>
                            <div class="service-featured-image">
                                <?php the_post_thumbnail('large', array('class' => 'img-fluid')); ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php
                            // Display video if available
                            if (!empty($video_url)) :
                            ?>
                            <div class="service-video">
                                <div class="embed-responsive embed-responsive-16by9">
                                    <?php
                                    // Check if YouTube or Vimeo URL
                                    if (strpos($video_url, 'youtube.com') !== false || strpos($video_url, 'youtu.be') !== false) {
                                        echo wp_oembed_get($video_url);
                                    } elseif (strpos($video_url, 'vimeo.com') !== false) {
                                        echo wp_oembed_get($video_url);
                                    } else {
                                        echo do_shortcode('[video src="' . esc_url($video_url) . '"]');
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="service-description">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php
                            // Display features if available
                            if (!empty($features)) :
                            ?>
                            <div class="service-features">
                                <h3><?php echo esc_html__('Service Features', 'aqualuxe'); ?></h3>
                                <ul class="feature-list">
                                    <?php foreach ($features as $feature) : ?>
                                        <li>
                                            <i class="fas fa-check-circle"></i>
                                            <span><?php echo esc_html($feature); ?></span>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php
                    endwhile; // End of the loop.
                    ?>
                </div>
                
                <div class="col-lg-4">
                    <div class="service-sidebar">
                        <div class="service-info-card">
                            <h3><?php echo esc_html__('Service Details', 'aqualuxe'); ?></h3>
                            
                            <div class="service-meta">
                                <?php if (!empty($price)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-tag"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Price', 'aqualuxe'); ?></span>
                                            <span class="meta-value"><?php echo esc_html(aqualuxe_format_price($price)); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($duration)) : ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="far fa-clock"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Duration', 'aqualuxe'); ?></span>
                                            <span class="meta-value"><?php echo esc_html($duration); ?></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php
                                // Display service categories
                                $service_categories = get_the_terms(get_the_ID(), 'service_category');
                                if (!empty($service_categories) && !is_wp_error($service_categories)) :
                                ?>
                                    <div class="meta-item">
                                        <div class="meta-icon">
                                            <i class="fas fa-folder"></i>
                                        </div>
                                        <div class="meta-content">
                                            <span class="meta-label"><?php echo esc_html__('Category', 'aqualuxe'); ?></span>
                                            <span class="meta-value">
                                                <?php
                                                $category_names = array();
                                                foreach ($service_categories as $category) {
                                                    $category_names[] = '<a href="' . esc_url(get_term_link($category)) . '">' . esc_html($category->name) . '</a>';
                                                }
                                                echo implode(', ', $category_names);
                                                ?>
                                            </span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php
                            // Display booking button if WooCommerce is active and product is linked
                            if (aqualuxe_is_woocommerce_active() && !empty($product_id)) :
                                $product = wc_get_product($product_id);
                                
                                if ($product && $product->is_purchasable()) :
                                ?>
                                <div class="service-booking">
                                    <a href="<?php echo esc_url($product->add_to_cart_url()); ?>" class="btn btn-primary btn-block">
                                        <?php echo esc_html__('Book This Service', 'aqualuxe'); ?>
                                    </a>
                                </div>
                                <?php
                                endif;
                            endif;
                            ?>
                        </div>
                        
                        <?php
                        // Display contact form
                        get_template_part('template-parts/components/service-inquiry-form');
                        ?>
                        
                        <?php
                        // Display related services
                        get_template_part('template-parts/components/related-services');
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- .container -->
</main><!-- #primary -->

<?php
get_footer();