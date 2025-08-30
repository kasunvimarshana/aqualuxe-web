<?php
/**
 * Template Name: FAQ Page
 *
 * This is the template for displaying the FAQ page.
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main faq-page">
    <?php
    // Hero Section
    $hero_image = get_post_meta(get_the_ID(), 'aqualuxe_faq_hero_image', true);
    $hero_title = get_post_meta(get_the_ID(), 'aqualuxe_faq_hero_title', true) ?: get_the_title();
    $hero_subtitle = get_post_meta(get_the_ID(), 'aqualuxe_faq_hero_subtitle', true);
    
    if (empty($hero_image)) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    ?>
    
    <section class="faq-hero" <?php if ($hero_image) : ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_subtitle) : ?>
                    <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="container">
        <?php if (function_exists('aqualuxe_breadcrumbs')) : ?>
            <?php aqualuxe_breadcrumbs(); ?>
        <?php endif; ?>

        <div class="faq-content">
            <div class="faq-main-content">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php
    // FAQ Search Section
    $search_title = get_post_meta(get_the_ID(), 'aqualuxe_faq_search_title', true) ?: __('Find Answers Quickly', 'aqualuxe');
    $search_description = get_post_meta(get_the_ID(), 'aqualuxe_faq_search_description', true) ?: __('Search our FAQ database for quick answers to your questions.', 'aqualuxe');
    ?>
    
    <section class="faq-search">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html($search_title); ?></h2>
                <?php if ($search_description) : ?>
                    <p class="section-description"><?php echo esc_html($search_description); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="faq-search-form">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url(home_url('/')); ?>">
                    <div class="search-input-group">
                        <input type="search" class="search-field" placeholder="<?php echo esc_attr__('Search FAQs...', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        <input type="hidden" name="post_type" value="faqs" />
                        <button type="submit" class="search-submit btn btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                            <span class="screen-reader-text"><?php echo esc_html__('Search', 'aqualuxe'); ?></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <?php
    // FAQ Categories Section
    // Check if FAQs CPT exists and has categories
    if (post_type_exists('faqs') && taxonomy_exists('faq_category')) :
        $faq_categories = get_terms(array(
            'taxonomy' => 'faq_category',
            'hide_empty' => true,
        ));
        
        if (!empty($faq_categories) && !is_wp_error($faq_categories)) :
    ?>
    <section class="faq-categories">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e('FAQ Categories', 'aqualuxe'); ?></h2>
            </div>
            
            <div class="category-tabs">
                <div class="tabs-navigation">
                    <ul class="tabs-list" role="tablist">
                        <li class="tab-item">
                            <button id="tab-all" class="tab-button active" role="tab" aria-selected="true" aria-controls="panel-all">
                                <?php esc_html_e('All FAQs', 'aqualuxe'); ?>
                            </button>
                        </li>
                        <?php foreach ($faq_categories as $category) : ?>
                            <li class="tab-item">
                                <button id="tab-<?php echo esc_attr($category->slug); ?>" class="tab-button" role="tab" aria-selected="false" aria-controls="panel-<?php echo esc_attr($category->slug); ?>">
                                    <?php echo esc_html($category->name); ?>
                                </button>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                
                <div class="tabs-content">
                    <div id="panel-all" class="tab-panel active" role="tabpanel" aria-labelledby="tab-all">
                        <?php
                        $args = array(
                            'post_type' => 'faqs',
                            'posts_per_page' => -1,
                            'orderby' => 'menu_order',
                            'order' => 'ASC',
                        );
                        
                        $faq_query = new WP_Query($args);
                        
                        if ($faq_query->have_posts()) :
                            echo '<div class="faq-accordion">';
                            
                            while ($faq_query->have_posts()) :
                                $faq_query->the_post();
                                ?>
                                <div class="faq-item">
                                    <h3 class="faq-question">
                                        <button class="accordion-trigger" aria-expanded="false">
                                            <?php the_title(); ?>
                                            <span class="accordion-icon">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="plus-icon"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="minus-icon"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                            </span>
                                        </button>
                                    </h3>
                                    <div class="faq-answer" hidden>
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                                <?php
                            endwhile;
                            
                            echo '</div>';
                            
                            wp_reset_postdata();
                        else :
                            echo '<p class="no-faqs">' . esc_html__('No FAQs found.', 'aqualuxe') . '</p>';
                        endif;
                        ?>
                    </div>
                    
                    <?php foreach ($faq_categories as $category) : ?>
                        <div id="panel-<?php echo esc_attr($category->slug); ?>" class="tab-panel" role="tabpanel" aria-labelledby="tab-<?php echo esc_attr($category->slug); ?>">
                            <?php
                            $args = array(
                                'post_type' => 'faqs',
                                'posts_per_page' => -1,
                                'orderby' => 'menu_order',
                                'order' => 'ASC',
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'faq_category',
                                        'field'    => 'slug',
                                        'terms'    => $category->slug,
                                    ),
                                ),
                            );
                            
                            $faq_query = new WP_Query($args);
                            
                            if ($faq_query->have_posts()) :
                                echo '<div class="faq-accordion">';
                                
                                while ($faq_query->have_posts()) :
                                    $faq_query->the_post();
                                    ?>
                                    <div class="faq-item">
                                        <h3 class="faq-question">
                                            <button class="accordion-trigger" aria-expanded="false">
                                                <?php the_title(); ?>
                                                <span class="accordion-icon">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="plus-icon"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="minus-icon"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                                </span>
                                            </button>
                                        </h3>
                                        <div class="faq-answer" hidden>
                                            <?php the_content(); ?>
                                        </div>
                                    </div>
                                    <?php
                                endwhile;
                                
                                echo '</div>';
                                
                                wp_reset_postdata();
                            else :
                                echo '<p class="no-faqs">' . esc_html__('No FAQs found in this category.', 'aqualuxe') . '</p>';
                            endif;
                            ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
    <?php 
        else :
            // If no categories, just show all FAQs
    ?>
    <section class="faq-list">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php esc_html_e('Frequently Asked Questions', 'aqualuxe'); ?></h2>
            </div>
            
            <div class="faq-wrapper">
                <?php
                $args = array(
                    'post_type' => 'faqs',
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'order' => 'ASC',
                );
                
                $faq_query = new WP_Query($args);
                
                if ($faq_query->have_posts()) :
                    echo '<div class="faq-accordion">';
                    
                    while ($faq_query->have_posts()) :
                        $faq_query->the_post();
                        ?>
                        <div class="faq-item">
                            <h3 class="faq-question">
                                <button class="accordion-trigger" aria-expanded="false">
                                    <?php the_title(); ?>
                                    <span class="accordion-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="plus-icon"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="minus-icon"><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    </span>
                                </button>
                            </h3>
                            <div class="faq-answer" hidden>
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    
                    echo '</div>';
                    
                    wp_reset_postdata();
                else :
                    echo '<p class="no-faqs">' . esc_html__('No FAQs found.', 'aqualuxe') . '</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
    <?php
        endif;
    endif;
    ?>

    <?php
    // Contact Section
    $contact_title = get_post_meta(get_the_ID(), 'aqualuxe_faq_contact_title', true) ?: __('Still Have Questions?', 'aqualuxe');
    $contact_description = get_post_meta(get_the_ID(), 'aqualuxe_faq_contact_description', true) ?: __('If you couldn\'t find the answer to your question, please don\'t hesitate to contact us.', 'aqualuxe');
    $contact_button_text = get_post_meta(get_the_ID(), 'aqualuxe_faq_contact_button_text', true) ?: __('Contact Us', 'aqualuxe');
    $contact_button_url = get_post_meta(get_the_ID(), 'aqualuxe_faq_contact_button_url', true);
    
    if (!$contact_button_url) {
        // Try to find the contact page
        $contact_page = get_page_by_title('Contact');
        if ($contact_page) {
            $contact_button_url = get_permalink($contact_page->ID);
        } else {
            $contact_button_url = '#';
        }
    }
    ?>
    
    <section class="faq-contact">
        <div class="container">
            <div class="contact-box">
                <div class="contact-content">
                    <h2 class="contact-title"><?php echo esc_html($contact_title); ?></h2>
                    <p class="contact-description"><?php echo esc_html($contact_description); ?></p>
                    <a href="<?php echo esc_url($contact_button_url); ?>" class="btn btn-primary"><?php echo esc_html($contact_button_text); ?></a>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();