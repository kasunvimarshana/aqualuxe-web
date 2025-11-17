<?php
/**
 * The template for displaying Species archive pages
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
    // Archive header
    $archive_title = get_theme_mod('aqualuxe_species_archive_title', __('Our Species Collection', 'aqualuxe'));
    $archive_subtitle = get_theme_mod('aqualuxe_species_archive_subtitle', __('Discover Our Aquatic Species', 'aqualuxe'));
    $archive_description = get_theme_mod('aqualuxe_species_archive_description', '');
    $archive_header_bg = get_theme_mod('aqualuxe_species_archive_header_bg', '');
    ?>

    <section class="page-header-wrapper" <?php if (!empty($archive_header_bg)) : ?>style="background-image: url('<?php echo esc_url($archive_header_bg); ?>');"<?php endif; ?>>
        <div class="container">
            <div class="page-header text-center">
                <h1 class="page-title"><?php echo esc_html($archive_title); ?></h1>
                
                <?php if (!empty($archive_subtitle)) : ?>
                    <div class="page-subtitle"><?php echo esc_html($archive_subtitle); ?></div>
                <?php endif; ?>
                
                <?php
                if (function_exists('aqualuxe_breadcrumbs') && get_theme_mod('aqualuxe_breadcrumbs_enable', true)) {
                    aqualuxe_breadcrumbs();
                }
                ?>
            </div>
        </div>
    </section>

    <section class="species-archive-section section">
        <div class="container">
            <?php if (!empty($archive_description)) : ?>
                <div class="archive-description text-center" data-aos="fade-up">
                    <?php echo wp_kses_post($archive_description); ?>
                </div>
            <?php endif; ?>
            
            <?php
            // Species Filters
            $show_filters = get_theme_mod('aqualuxe_species_archive_filters', true);
            
            if ($show_filters) :
                // Get species taxonomies
                $taxonomies = array('species_category', 'species_origin', 'species_habitat');
                $has_terms = false;
                
                foreach ($taxonomies as $taxonomy) {
                    if (taxonomy_exists($taxonomy) && get_terms(array('taxonomy' => $taxonomy, 'hide_empty' => true))) {
                        $has_terms = true;
                        break;
                    }
                }
                
                if ($has_terms) :
            ?>
                <div class="species-filters" data-aos="fade-up" data-aos-delay="100">
                    <form id="species-filter-form" class="species-filter-form" method="get">
                        <div class="row">
                            <?php
                            foreach ($taxonomies as $taxonomy) :
                                if (taxonomy_exists($taxonomy)) :
                                    $terms = get_terms(array(
                                        'taxonomy' => $taxonomy,
                                        'hide_empty' => true,
                                    ));
                                    
                                    if (!empty($terms) && !is_wp_error($terms)) :
                                        $tax_obj = get_taxonomy($taxonomy);
                                        $tax_name = $tax_obj->labels->singular_name;
                            ?>
                                <div class="col-lg-4 col-md-6">
                                    <div class="form-group">
                                        <label for="<?php echo esc_attr($taxonomy); ?>"><?php echo esc_html($tax_name); ?></label>
                                        <select name="<?php echo esc_attr($taxonomy); ?>" id="<?php echo esc_attr($taxonomy); ?>" class="form-control">
                                            <option value=""><?php printf(esc_html__('All %s', 'aqualuxe'), $tax_obj->labels->name); ?></option>
                                            <?php foreach ($terms as $term) : ?>
                                                <option value="<?php echo esc_attr($term->slug); ?>" <?php selected(isset($_GET[$taxonomy]) && $_GET[$taxonomy] === $term->slug); ?>><?php echo esc_html($term->name); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            <?php
                                    endif;
                                endif;
                            endforeach;
                            ?>
                            
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="species-search"><?php esc_html_e('Search', 'aqualuxe'); ?></label>
                                    <div class="search-input-group">
                                        <input type="text" name="species-search" id="species-search" class="form-control" placeholder="<?php esc_attr_e('Search species...', 'aqualuxe'); ?>" value="<?php echo isset($_GET['species-search']) ? esc_attr($_GET['species-search']) : ''; ?>">
                                        <button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-6">
                                <div class="form-group">
                                    <label for="species-orderby"><?php esc_html_e('Sort By', 'aqualuxe'); ?></label>
                                    <select name="orderby" id="species-orderby" class="form-control">
                                        <option value="title" <?php selected(isset($_GET['orderby']) && $_GET['orderby'] === 'title'); ?>><?php esc_html_e('Name (A-Z)', 'aqualuxe'); ?></option>
                                        <option value="title-desc" <?php selected(isset($_GET['orderby']) && $_GET['orderby'] === 'title-desc'); ?>><?php esc_html_e('Name (Z-A)', 'aqualuxe'); ?></option>
                                        <option value="date" <?php selected(!isset($_GET['orderby']) || $_GET['orderby'] === 'date'); ?>><?php esc_html_e('Newest First', 'aqualuxe'); ?></option>
                                        <option value="date-asc" <?php selected(isset($_GET['orderby']) && $_GET['orderby'] === 'date-asc'); ?>><?php esc_html_e('Oldest First', 'aqualuxe'); ?></option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <div class="filter-buttons">
                                    <button type="submit" class="btn btn-primary"><?php esc_html_e('Apply Filters', 'aqualuxe'); ?></button>
                                    <a href="<?php echo esc_url(get_post_type_archive_link('species')); ?>" class="btn btn-outline-primary"><?php esc_html_e('Reset Filters', 'aqualuxe'); ?></a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            <?php
                endif;
            endif;
            
            // Check if we have species posts
            if (have_posts()) :
            ?>
                <div class="species-grid" data-aos="fade-up" data-aos-delay="200">
                    <div class="row">
                        <?php
                        // Start the Loop
                        while (have_posts()) :
                            the_post();
                            
                            // Get species meta
                            $scientific_name = get_post_meta(get_the_ID(), 'scientific_name', true);
                            $origin = get_post_meta(get_the_ID(), 'origin', true);
                            $habitat = get_post_meta(get_the_ID(), 'habitat', true);
                            $care_level = get_post_meta(get_the_ID(), 'care_level', true);
                            $size = get_post_meta(get_the_ID(), 'size', true);
                        ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="species-card">
                                    <?php if (has_post_thumbnail()) : ?>
                                        <div class="species-image">
                                            <a href="<?php the_permalink(); ?>">
                                                <?php the_post_thumbnail('medium_large', array('class' => 'img-fluid')); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <div class="species-content">
                                        <h3 class="species-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                        
                                        <?php if (!empty($scientific_name)) : ?>
                                            <div class="species-scientific-name"><?php echo esc_html($scientific_name); ?></div>
                                        <?php endif; ?>
                                        
                                        <div class="species-meta">
                                            <?php if (!empty($origin)) : ?>
                                                <span class="species-origin"><i class="fas fa-map-marker-alt"></i> <?php echo esc_html($origin); ?></span>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($habitat)) : ?>
                                                <span class="species-habitat"><i class="fas fa-water"></i> <?php echo esc_html($habitat); ?></span>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($care_level)) : ?>
                                                <span class="species-care"><i class="fas fa-hand-holding-water"></i> <?php echo esc_html($care_level); ?></span>
                                            <?php endif; ?>
                                            
                                            <?php if (!empty($size)) : ?>
                                                <span class="species-size"><i class="fas fa-ruler-horizontal"></i> <?php echo esc_html($size); ?></span>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="species-excerpt">
                                            <?php the_excerpt(); ?>
                                        </div>
                                        
                                        <a href="<?php the_permalink(); ?>" class="btn btn-outline-primary btn-sm"><?php esc_html_e('Learn More', 'aqualuxe'); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <?php
                    // Pagination
                    the_posts_pagination(array(
                        'prev_text' => '<i class="fas fa-chevron-left"></i> ' . esc_html__('Previous', 'aqualuxe'),
                        'next_text' => esc_html__('Next', 'aqualuxe') . ' <i class="fas fa-chevron-right"></i>',
                        'screen_reader_text' => esc_html__('Posts navigation', 'aqualuxe'),
                    ));
                    ?>
                </div>
            <?php
            else :
                // If no content, include the "No posts found" template
                get_template_part('template-parts/content/content', 'none');
            endif;
            ?>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();