<?php
/**
 * Template part for displaying the services page FAQ section
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get page ID
$page_id = get_the_ID();

// Get section settings from page meta or theme options
$section_title = get_post_meta($page_id, '_aqualuxe_services_faq_title', true);
if (!$section_title) {
    $section_title = get_theme_mod('aqualuxe_services_faq_title', __('Frequently Asked Questions', 'aqualuxe'));
}

$section_subtitle = get_post_meta($page_id, '_aqualuxe_services_faq_subtitle', true);
if (!$section_subtitle) {
    $section_subtitle = get_theme_mod('aqualuxe_services_faq_subtitle', __('Common Questions', 'aqualuxe'));
}

$section_description = get_post_meta($page_id, '_aqualuxe_services_faq_description', true);
if (!$section_description) {
    $section_description = get_theme_mod('aqualuxe_services_faq_description', __('Find answers to the most common questions about our services.', 'aqualuxe'));
}

$faq_count = get_post_meta($page_id, '_aqualuxe_services_faq_count', true);
if (!$faq_count) {
    $faq_count = get_theme_mod('aqualuxe_services_faq_count', 6);
}

$faq_category = get_post_meta($page_id, '_aqualuxe_services_faq_category', true);
if (!$faq_category) {
    $faq_category = get_theme_mod('aqualuxe_services_faq_category', '');
}

$view_all_text = get_post_meta($page_id, '_aqualuxe_services_faq_view_all', true);
if (!$view_all_text) {
    $view_all_text = get_theme_mod('aqualuxe_services_faq_view_all', __('View All FAQs', 'aqualuxe'));
}

$view_all_url = get_post_meta($page_id, '_aqualuxe_services_faq_view_all_url', true);
if (!$view_all_url) {
    $view_all_url = get_theme_mod('aqualuxe_services_faq_view_all_url', '#');
}

$section_background = get_post_meta($page_id, '_aqualuxe_services_faq_background', true);
if ($section_background === '') {
    $section_background = get_theme_mod('aqualuxe_services_faq_background', 'gray');
}

// Set background class based on setting
$bg_class = $section_background === 'gray' ? 'bg-gray-50' : 'bg-white';
?>

<section class="services-faq-section py-16 <?php echo esc_attr($bg_class); ?>">
    <div class="container mx-auto px-4">
        <div class="section-header text-center mb-12">
            <?php if ($section_subtitle) : ?>
                <div class="section-subtitle text-primary text-lg mb-2">
                    <?php echo esc_html($section_subtitle); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($section_title) : ?>
                <h2 class="section-title text-3xl md:text-4xl font-bold mb-4">
                    <?php echo esc_html($section_title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($section_description) : ?>
                <div class="section-description max-w-3xl mx-auto text-gray-600">
                    <?php echo wp_kses_post($section_description); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="faq-container max-w-4xl mx-auto">
            <?php
            // Query FAQs
            $args = array(
                'post_type'      => 'faq',
                'posts_per_page' => $faq_count,
                'post_status'    => 'publish',
            );
            
            // Add category filter if set
            if ($faq_category) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'faq_category',
                        'field'    => 'term_id',
                        'terms'    => $faq_category,
                    ),
                );
            }
            
            $faq_query = new WP_Query($args);
            
            if ($faq_query->have_posts()) :
                ?>
                <div class="faq-accordion space-y-4">
                    <?php
                    while ($faq_query->have_posts()) :
                        $faq_query->the_post();
                        $faq_id = 'faq-' . get_the_ID();
                        ?>
                        
                        <div class="faq-item bg-white rounded-lg shadow-md overflow-hidden">
                            <button class="faq-question w-full flex justify-between items-center p-6 text-left font-bold text-lg focus:outline-none" aria-expanded="false" aria-controls="<?php echo esc_attr($faq_id); ?>">
                                <?php the_title(); ?>
                                <svg xmlns="http://www.w3.org/2000/svg" class="faq-icon h-6 w-6 transform transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div id="<?php echo esc_attr($faq_id); ?>" class="faq-answer hidden px-6 pb-6 prose max-w-none">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        
                    <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
                
                <?php if ($view_all_text && $view_all_url) : ?>
                    <div class="view-all text-center mt-12">
                        <a href="<?php echo esc_url($view_all_url); ?>" class="button button-primary">
                            <?php echo esc_html($view_all_text); ?>
                        </a>
                    </div>
                <?php endif; ?>
                
            <?php else : ?>
                <div class="no-faqs text-center py-8">
                    <p><?php esc_html_e('No FAQs found.', 'aqualuxe'); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', function() {
                    const answer = this.nextElementSibling;
                    const icon = this.querySelector('.faq-icon');
                    
                    // Toggle the answer visibility
                    answer.classList.toggle('hidden');
                    
                    // Toggle the icon rotation
                    icon.classList.toggle('rotate-180');
                    
                    // Update aria-expanded attribute
                    const isExpanded = answer.classList.contains('hidden') ? 'false' : 'true';
                    this.setAttribute('aria-expanded', isExpanded);
                });
            });
        });
    </script>
</section>