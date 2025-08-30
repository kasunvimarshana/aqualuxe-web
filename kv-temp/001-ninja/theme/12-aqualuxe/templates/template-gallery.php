<?php
/**
 * Template Name: Gallery Page
 *
 * The template for displaying the gallery page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Page header
    if (get_theme_mod('aqualuxe_page_header_enable', true)) :
        $page_title = get_the_title();
        $page_subtitle = get_post_meta(get_the_ID(), 'page_subtitle', true);
        $page_header_bg = get_post_meta(get_the_ID(), 'page_header_bg', true);
        
        if (empty($page_header_bg)) {
            $page_header_bg = get_theme_mod('aqualuxe_page_header_bg', '');
        }
    ?>
        <section class="page-header-wrapper" <?php if (!empty($page_header_bg)) : ?>style="background-image: url('<?php echo esc_url($page_header_bg); ?>');"<?php endif; ?>>
            <div class="container">
                <div class="page-header text-center">
                    <h1 class="page-title"><?php echo esc_html($page_title); ?></h1>
                    
                    <?php if (!empty($page_subtitle)) : ?>
                        <div class="page-subtitle"><?php echo esc_html($page_subtitle); ?></div>
                    <?php endif; ?>
                    
                    <?php
                    if (function_exists('aqualuxe_breadcrumbs') && get_theme_mod('aqualuxe_breadcrumbs_enable', true)) {
                        aqualuxe_breadcrumbs();
                    }
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="gallery-section section">
        <div class="container">
            <?php
            // Gallery introduction
            while (have_posts()) :
                the_post();
                
                if (get_the_content()) :
            ?>
                <div class="gallery-intro" data-aos="fade-up">
                    <?php the_content(); ?>
                </div>
            <?php
                endif;
            endwhile;
            
            // Gallery categories
            $gallery_categories = get_terms(array(
                'taxonomy' => 'gallery_category',
                'hide_empty' => true,
            ));
            
            if (!empty($gallery_categories) && !is_wp_error($gallery_categories)) :
            ?>
                <div class="gallery-filter" data-aos="fade-up">
                    <ul class="filter-nav">
                        <li class="active"><a href="#" data-filter="*"><?php esc_html_e('All', 'aqualuxe'); ?></a></li>
                        <?php foreach ($gallery_categories as $category) : ?>
                            <li><a href="#" data-filter=".<?php echo esc_attr($category->slug); ?>"><?php echo esc_html($category->name); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>
            
            <?php
            // Gallery items
            $gallery_args = array(
                'post_type' => 'gallery',
                'posts_per_page' => -1,
                'orderby' => 'date',
                'order' => 'DESC',
            );
            
            $gallery_query = new WP_Query($gallery_args);
            
            if ($gallery_query->have_posts()) :
            ?>
                <div class="gallery-grid" data-aos="fade-up">
                    <div class="row">
                        <?php
                        while ($gallery_query->have_posts()) :
                            $gallery_query->the_post();
                            
                            // Get gallery categories
                            $gallery_cats = get_the_terms(get_the_ID(), 'gallery_category');
                            $gallery_cat_classes = '';
                            
                            if (!empty($gallery_cats) && !is_wp_error($gallery_cats)) {
                                foreach ($gallery_cats as $cat) {
                                    $gallery_cat_classes .= ' ' . $cat->slug;
                                }
                            }
                            
                            // Get gallery image
                            $gallery_image = get_post_meta(get_the_ID(), 'gallery_image', true);
                            if (empty($gallery_image) && has_post_thumbnail()) {
                                $gallery_image = get_post_thumbnail_id();
                            }
                        ?>
                            <div class="col-lg-4 col-md-6 gallery-item<?php echo esc_attr($gallery_cat_classes); ?>">
                                <div class="gallery-item-inner">
                                    <?php if (!empty($gallery_image)) : ?>
                                        <div class="gallery-image">
                                            <a href="<?php echo esc_url(wp_get_attachment_image_url($gallery_image, 'full')); ?>" class="lightbox" title="<?php the_title_attribute(); ?>">
                                                <?php echo wp_get_attachment_image($gallery_image, 'large', false, array('class' => 'img-fluid')); ?>
                                                <div class="gallery-overlay">
                                                    <div class="gallery-icon">
                                                        <i class="fas fa-search-plus"></i>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="gallery-content">
                                        <h3 class="gallery-title"><?php the_title(); ?></h3>
                                        
                                        <?php if (!empty($gallery_cats) && !is_wp_error($gallery_cats)) : ?>
                                            <div class="gallery-categories">
                                                <?php
                                                $cat_names = array();
                                                foreach ($gallery_cats as $cat) {
                                                    $cat_names[] = $cat->name;
                                                }
                                                echo esc_html(implode(', ', $cat_names));
                                                ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php
                wp_reset_postdata();
            else :
            ?>
                <div class="no-gallery-items" data-aos="fade-up">
                    <p><?php esc_html_e('No gallery items found.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
            
            <?php
            // Gallery from ACF field
            $gallery_images = get_post_meta(get_the_ID(), 'gallery_images', true);
            
            if (!empty($gallery_images) && is_array($gallery_images)) :
            ?>
                <div class="custom-gallery" data-aos="fade-up">
                    <div class="row">
                        <?php foreach ($gallery_images as $image_id) : ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="gallery-item-inner">
                                    <div class="gallery-image">
                                        <a href="<?php echo esc_url(wp_get_attachment_image_url($image_id, 'full')); ?>" class="lightbox">
                                            <?php echo wp_get_attachment_image($image_id, 'large', false, array('class' => 'img-fluid')); ?>
                                            <div class="gallery-overlay">
                                                <div class="gallery-icon">
                                                    <i class="fas fa-search-plus"></i>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();