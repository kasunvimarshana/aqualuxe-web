<?php
/**
 * Template Name: FAQ Page
 *
 * The template for displaying the FAQ page.
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

    <section class="faq-section section">
        <div class="container">
            <?php
            // FAQ Introduction
            $faq_intro = get_post_meta(get_the_ID(), 'faq_intro', true);
            
            if (empty($faq_intro)) {
                $faq_intro = get_theme_mod('aqualuxe_faq_intro', '');
            }
            
            if (!empty($faq_intro)) :
            ?>
                <div class="faq-intro text-center" data-aos="fade-up">
                    <?php echo wp_kses_post($faq_intro); ?>
                </div>
            <?php endif; ?>
            
            <?php
            // FAQ Search
            $faq_search_enable = get_post_meta(get_the_ID(), 'faq_search_enable', true);
            
            if (empty($faq_search_enable)) {
                $faq_search_enable = get_theme_mod('aqualuxe_faq_search_enable', true);
            }
            
            if ($faq_search_enable) :
            ?>
                <div class="faq-search" data-aos="fade-up" data-aos-delay="100">
                    <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                        <div class="form-group">
                            <input type="search" class="search-field form-control" placeholder="<?php esc_attr_e('Search FAQs...', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                            <input type="hidden" name="post_type" value="faq" />
                            <button type="submit" class="search-submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
            <?php endif; ?>
            
            <?php
            // FAQ Categories
            if (post_type_exists('faq') && taxonomy_exists('faq_category')) :
                $faq_categories = get_terms(array(
                    'taxonomy' => 'faq_category',
                    'hide_empty' => true,
                ));
                
                if (!empty($faq_categories) && !is_wp_error($faq_categories)) :
            ?>
                <div class="faq-categories" data-aos="fade-up" data-aos-delay="200">
                    <ul class="nav nav-tabs" id="faqTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="all-tab" data-toggle="tab" href="#all" role="tab" aria-controls="all" aria-selected="true"><?php esc_html_e('All FAQs', 'aqualuxe'); ?></a>
                        </li>
                        
                        <?php foreach ($faq_categories as $category) : ?>
                            <li class="nav-item">
                                <a class="nav-link" id="<?php echo esc_attr($category->slug); ?>-tab" data-toggle="tab" href="#<?php echo esc_attr($category->slug); ?>" role="tab" aria-controls="<?php echo esc_attr($category->slug); ?>" aria-selected="false"><?php echo esc_html($category->name); ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="tab-content" id="faqTabContent">
                    <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                        <div class="faq-accordion" id="faqAccordionAll" data-aos="fade-up" data-aos-delay="300">
                            <?php
                            $args = array(
                                'post_type' => 'faq',
                                'posts_per_page' => -1,
                                'orderby' => 'menu_order',
                                'order' => 'ASC',
                            );
                            
                            $faqs = new WP_Query($args);
                            
                            if ($faqs->have_posts()) :
                                $counter = 0;
                                while ($faqs->have_posts()) :
                                    $faqs->the_post();
                                    $counter++;
                            ?>
                                <div class="card">
                                    <div class="card-header" id="heading-all-<?php echo esc_attr($counter); ?>">
                                        <h3 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse-all-<?php echo esc_attr($counter); ?>" aria-expanded="false" aria-controls="collapse-all-<?php echo esc_attr($counter); ?>">
                                                <?php the_title(); ?>
                                                <span class="icon"><i class="fas fa-chevron-down"></i></span>
                                            </button>
                                        </h3>
                                    </div>
                                    
                                    <div id="collapse-all-<?php echo esc_attr($counter); ?>" class="collapse" aria-labelledby="heading-all-<?php echo esc_attr($counter); ?>" data-parent="#faqAccordionAll">
                                        <div class="card-body">
                                            <?php the_content(); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                endwhile;
                                wp_reset_postdata();
                            else :
                            ?>
                                <div class="faq-no-results">
                                    <p><?php esc_html_e('No FAQs found.', 'aqualuxe'); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php foreach ($faq_categories as $category) : ?>
                        <div class="tab-pane fade" id="<?php echo esc_attr($category->slug); ?>" role="tabpanel" aria-labelledby="<?php echo esc_attr($category->slug); ?>-tab">
                            <div class="faq-accordion" id="faqAccordion-<?php echo esc_attr($category->slug); ?>" data-aos="fade-up" data-aos-delay="300">
                                <?php
                                $args = array(
                                    'post_type' => 'faq',
                                    'posts_per_page' => -1,
                                    'orderby' => 'menu_order',
                                    'order' => 'ASC',
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'faq_category',
                                            'field' => 'slug',
                                            'terms' => $category->slug,
                                        ),
                                    ),
                                );
                                
                                $faqs = new WP_Query($args);
                                
                                if ($faqs->have_posts()) :
                                    $counter = 0;
                                    while ($faqs->have_posts()) :
                                        $faqs->the_post();
                                        $counter++;
                                ?>
                                    <div class="card">
                                        <div class="card-header" id="heading-<?php echo esc_attr($category->slug); ?>-<?php echo esc_attr($counter); ?>">
                                            <h3 class="mb-0">
                                                <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse-<?php echo esc_attr($category->slug); ?>-<?php echo esc_attr($counter); ?>" aria-expanded="false" aria-controls="collapse-<?php echo esc_attr($category->slug); ?>-<?php echo esc_attr($counter); ?>">
                                                    <?php the_title(); ?>
                                                    <span class="icon"><i class="fas fa-chevron-down"></i></span>
                                                </button>
                                            </h3>
                                        </div>
                                        
                                        <div id="collapse-<?php echo esc_attr($category->slug); ?>-<?php echo esc_attr($counter); ?>" class="collapse" aria-labelledby="heading-<?php echo esc_attr($category->slug); ?>-<?php echo esc_attr($counter); ?>" data-parent="#faqAccordion-<?php echo esc_attr($category->slug); ?>">
                                            <div class="card-body">
                                                <?php the_content(); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    endwhile;
                                    wp_reset_postdata();
                                else :
                                ?>
                                    <div class="faq-no-results">
                                        <p><?php esc_html_e('No FAQs found in this category.', 'aqualuxe'); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php
                else :
                    // If no categories, just show all FAQs
            ?>
                <div class="faq-accordion" id="faqAccordion" data-aos="fade-up" data-aos-delay="200">
                    <?php
                    $args = array(
                        'post_type' => 'faq',
                        'posts_per_page' => -1,
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                    );
                    
                    $faqs = new WP_Query($args);
                    
                    if ($faqs->have_posts()) :
                        $counter = 0;
                        while ($faqs->have_posts()) :
                            $faqs->the_post();
                            $counter++;
                    ?>
                        <div class="card">
                            <div class="card-header" id="heading-<?php echo esc_attr($counter); ?>">
                                <h3 class="mb-0">
                                    <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" data-target="#collapse-<?php echo esc_attr($counter); ?>" aria-expanded="false" aria-controls="collapse-<?php echo esc_attr($counter); ?>">
                                        <?php the_title(); ?>
                                        <span class="icon"><i class="fas fa-chevron-down"></i></span>
                                    </button>
                                </h3>
                            </div>
                            
                            <div id="collapse-<?php echo esc_attr($counter); ?>" class="collapse" aria-labelledby="heading-<?php echo esc_attr($counter); ?>" data-parent="#faqAccordion">
                                <div class="card-body">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                        <div class="faq-no-results">
                            <p><?php esc_html_e('No FAQs found.', 'aqualuxe'); ?></p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php
                endif;
            else :
                // If FAQ post type doesn't exist, use page content
                while (have_posts()) :
                    the_post();
            ?>
                <div class="entry-content">
                    <?php the_content(); ?>
                </div>
            <?php
                endwhile;
            endif;
            ?>
            
            <?php
            // Contact CTA
            $contact_cta_enable = get_post_meta(get_the_ID(), 'contact_cta_enable', true);
            $contact_cta_title = get_post_meta(get_the_ID(), 'contact_cta_title', true);
            $contact_cta_text = get_post_meta(get_the_ID(), 'contact_cta_text', true);
            $contact_cta_button_text = get_post_meta(get_the_ID(), 'contact_cta_button_text', true);
            $contact_cta_button_url = get_post_meta(get_the_ID(), 'contact_cta_button_url', true);
            
            if (empty($contact_cta_enable)) {
                $contact_cta_enable = get_theme_mod('aqualuxe_faq_contact_cta_enable', true);
            }
            
            if (empty($contact_cta_title)) {
                $contact_cta_title = get_theme_mod('aqualuxe_faq_contact_cta_title', __('Still Have Questions?', 'aqualuxe'));
            }
            
            if (empty($contact_cta_text)) {
                $contact_cta_text = get_theme_mod('aqualuxe_faq_contact_cta_text', __('If you couldn\'t find the answer to your question, please feel free to contact us.', 'aqualuxe'));
            }
            
            if (empty($contact_cta_button_text)) {
                $contact_cta_button_text = get_theme_mod('aqualuxe_faq_contact_cta_button_text', __('Contact Us', 'aqualuxe'));
            }
            
            if (empty($contact_cta_button_url)) {
                $contact_cta_button_url = get_theme_mod('aqualuxe_faq_contact_cta_button_url', '#');
            }
            
            if ($contact_cta_enable && (!empty($contact_cta_title) || !empty($contact_cta_text))) :
            ?>
                <div class="faq-contact-cta text-center" data-aos="fade-up">
                    <?php if (!empty($contact_cta_title)) : ?>
                        <h3 class="contact-cta-title"><?php echo esc_html($contact_cta_title); ?></h3>
                    <?php endif; ?>
                    
                    <?php if (!empty($contact_cta_text)) : ?>
                        <div class="contact-cta-text">
                            <p><?php echo esc_html($contact_cta_text); ?></p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($contact_cta_button_text) && !empty($contact_cta_button_url)) : ?>
                        <div class="contact-cta-button">
                            <a href="<?php echo esc_url($contact_cta_button_url); ?>" class="btn btn-primary"><?php echo esc_html($contact_cta_button_text); ?></a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();