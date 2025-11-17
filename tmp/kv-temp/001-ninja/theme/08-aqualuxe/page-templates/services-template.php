<?php
/**
 * Template Name: Services Page
 *
 * This is the template for displaying the Services page.
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main services-page">
    <?php
    // Hero Section
    $hero_image = get_post_meta(get_the_ID(), 'aqualuxe_services_hero_image', true);
    $hero_title = get_post_meta(get_the_ID(), 'aqualuxe_services_hero_title', true) ?: get_the_title();
    $hero_subtitle = get_post_meta(get_the_ID(), 'aqualuxe_services_hero_subtitle', true);
    
    if (empty($hero_image)) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    ?>
    
    <section class="services-hero" <?php if ($hero_image) : ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
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

        <div class="services-content">
            <div class="services-main-content">
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
    // Services List Section
    $services_title = get_post_meta(get_the_ID(), 'aqualuxe_services_list_title', true) ?: __('Our Services', 'aqualuxe');
    $services_description = get_post_meta(get_the_ID(), 'aqualuxe_services_list_description', true);
    
    // Check if Services CPT exists
    if (post_type_exists('services')) :
    ?>
    <section class="services-list">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html($services_title); ?></h2>
                <?php if ($services_description) : ?>
                    <p class="section-description"><?php echo esc_html($services_description); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="services-wrapper">
                <?php
                $args = array(
                    'post_type' => 'services',
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'order' => 'ASC',
                );
                
                $services_query = new WP_Query($args);
                
                if ($services_query->have_posts()) :
                    echo '<div class="services-grid">';
                    
                    while ($services_query->have_posts()) :
                        $services_query->the_post();
                        
                        // Get service meta
                        $service_icon = get_post_meta(get_the_ID(), 'aqualuxe_service_icon', true);
                        $service_short_description = get_post_meta(get_the_ID(), 'aqualuxe_service_short_description', true);
                        ?>
                        <div class="service-card">
                            <?php if ($service_icon) : ?>
                                <div class="service-icon">
                                    <?php echo wp_kses($service_icon, array(
                                        'svg' => array(
                                            'xmlns' => array(),
                                            'width' => array(),
                                            'height' => array(),
                                            'viewBox' => array(),
                                            'fill' => array(),
                                            'stroke' => array(),
                                            'stroke-width' => array(),
                                            'stroke-linecap' => array(),
                                            'stroke-linejoin' => array(),
                                        ),
                                        'path' => array(
                                            'd' => array(),
                                            'fill' => array(),
                                            'stroke' => array(),
                                        ),
                                        'circle' => array(
                                            'cx' => array(),
                                            'cy' => array(),
                                            'r' => array(),
                                            'fill' => array(),
                                            'stroke' => array(),
                                        ),
                                        'line' => array(
                                            'x1' => array(),
                                            'y1' => array(),
                                            'x2' => array(),
                                            'y2' => array(),
                                            'stroke' => array(),
                                        ),
                                        'polyline' => array(
                                            'points' => array(),
                                            'stroke' => array(),
                                        ),
                                        'rect' => array(
                                            'x' => array(),
                                            'y' => array(),
                                            'width' => array(),
                                            'height' => array(),
                                            'rx' => array(),
                                            'ry' => array(),
                                            'fill' => array(),
                                            'stroke' => array(),
                                        ),
                                    )); ?>
                                </div>
                            <?php elseif (has_post_thumbnail()) : ?>
                                <div class="service-image">
                                    <?php the_post_thumbnail('aqualuxe-card'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="service-content">
                                <h3 class="service-title">
                                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                </h3>
                                
                                <div class="service-description">
                                    <?php
                                    if ($service_short_description) {
                                        echo wp_kses_post(wpautop($service_short_description));
                                    } else {
                                        the_excerpt();
                                    }
                                    ?>
                                </div>
                                
                                <a href="<?php the_permalink(); ?>" class="btn btn-outline service-link">
                                    <?php echo esc_html__('Learn More', 'aqualuxe'); ?>
                                </a>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    
                    echo '</div>';
                    
                    wp_reset_postdata();
                else :
                    echo '<p class="no-services">' . esc_html__('No services found.', 'aqualuxe') . '</p>';
                endif;
                ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Process Section
    $process_title = get_post_meta(get_the_ID(), 'aqualuxe_services_process_title', true) ?: __('Our Process', 'aqualuxe');
    $process_description = get_post_meta(get_the_ID(), 'aqualuxe_services_process_description', true);
    $process_steps = get_post_meta(get_the_ID(), 'aqualuxe_services_process_steps', true);
    
    if ($process_steps) :
        $steps = maybe_unserialize($process_steps);
        if (!is_array($steps)) {
            $steps = array();
        }
    ?>
    <section class="services-process">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html($process_title); ?></h2>
                <?php if ($process_description) : ?>
                    <p class="section-description"><?php echo esc_html($process_description); ?></p>
                <?php endif; ?>
            </div>
            
            <?php if (!empty($steps)) : ?>
            <div class="process-steps">
                <?php foreach ($steps as $index => $step) : ?>
                    <div class="process-step">
                        <div class="step-number"><?php echo esc_html($index + 1); ?></div>
                        <div class="step-content">
                            <?php if (!empty($step['title'])) : ?>
                                <h3 class="step-title"><?php echo esc_html($step['title']); ?></h3>
                            <?php endif; ?>
                            
                            <?php if (!empty($step['description'])) : ?>
                                <div class="step-description">
                                    <?php echo wp_kses_post(wpautop($step['description'])); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // FAQ Section
    $faq_title = get_post_meta(get_the_ID(), 'aqualuxe_services_faq_title', true) ?: __('Frequently Asked Questions', 'aqualuxe');
    $faq_description = get_post_meta(get_the_ID(), 'aqualuxe_services_faq_description', true);
    $faq_count = get_post_meta(get_the_ID(), 'aqualuxe_services_faq_count', true) ?: 6;
    
    // Check if FAQs CPT exists
    if (post_type_exists('faqs')) :
    ?>
    <section class="services-faq">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html($faq_title); ?></h2>
                <?php if ($faq_description) : ?>
                    <p class="section-description"><?php echo esc_html($faq_description); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="faq-wrapper">
                <?php
                $args = array(
                    'post_type' => 'faqs',
                    'posts_per_page' => intval($faq_count),
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
            
            <?php
            // View all FAQs link
            $faq_archive_url = get_post_type_archive_link('faqs');
            if ($faq_archive_url) :
            ?>
            <div class="view-all-wrapper">
                <a href="<?php echo esc_url($faq_archive_url); ?>" class="btn btn-primary"><?php esc_html_e('View All FAQs', 'aqualuxe'); ?></a>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Call to Action Section
    $cta_title = get_post_meta(get_the_ID(), 'aqualuxe_services_cta_title', true) ?: __('Ready to Get Started?', 'aqualuxe');
    $cta_content = get_post_meta(get_the_ID(), 'aqualuxe_services_cta_content', true) ?: __('Contact us today to learn more about our services and how we can help you.', 'aqualuxe');
    $cta_button_text = get_post_meta(get_the_ID(), 'aqualuxe_services_cta_button_text', true) ?: __('Contact Us', 'aqualuxe');
    $cta_button_url = get_post_meta(get_the_ID(), 'aqualuxe_services_cta_button_url', true);
    $cta_background = get_post_meta(get_the_ID(), 'aqualuxe_services_cta_background', true);
    
    if (!$cta_button_url) {
        // Try to find the contact page
        $contact_page = get_page_by_title('Contact');
        if ($contact_page) {
            $cta_button_url = get_permalink($contact_page->ID);
        } else {
            $cta_button_url = '#';
        }
    }
    ?>
    <section class="services-cta" <?php if ($cta_background) : ?>style="background-image: url('<?php echo esc_url($cta_background); ?>');"<?php endif; ?>>
        <div class="container">
            <div class="cta-content">
                <h2 class="cta-title"><?php echo esc_html($cta_title); ?></h2>
                <div class="cta-text">
                    <?php echo wp_kses_post(wpautop($cta_content)); ?>
                </div>
                <a href="<?php echo esc_url($cta_button_url); ?>" class="btn btn-accent btn-large"><?php echo esc_html($cta_button_text); ?></a>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();