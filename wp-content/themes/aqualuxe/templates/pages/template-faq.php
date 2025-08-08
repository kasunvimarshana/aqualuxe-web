<?php
/**
 * Template Name: FAQ Page
 *
 * This is the template that displays the FAQ page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    $hero_image = get_post_meta(get_the_ID(), 'faq_hero_image', true);
    if (!$hero_image) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    $hero_title = get_post_meta(get_the_ID(), 'faq_hero_title', true);
    if (!$hero_title) {
        $hero_title = get_the_title();
    }
    $hero_subtitle = get_post_meta(get_the_ID(), 'faq_hero_subtitle', true);
    ?>
    <section class="faq-hero relative py-16 bg-cover bg-center" <?php if ($hero_image) : ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_subtitle) : ?>
                    <p class="text-xl md:text-2xl"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <section class="faq-search py-8 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="max-w-2xl mx-auto">
                <form role="search" method="get" class="faq-search-form flex" action="<?php echo esc_url(home_url('/')); ?>">
                    <input type="hidden" name="post_type" value="aqualuxe_faq" />
                    <div class="relative flex-grow">
                        <input type="search" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-l-md focus:ring-2 focus:ring-primary-500 dark:bg-gray-700 dark:text-white" placeholder="<?php esc_attr_e('Search FAQs...', 'aqualuxe'); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        <button type="submit" class="absolute right-0 top-0 h-full px-4 flex items-center justify-center text-gray-500 dark:text-gray-300">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </button>
                    </div>
                    <button type="submit" class="bg-primary-600 hover:bg-primary-700 text-white px-6 py-3 rounded-r-md transition-colors">
                        <?php esc_html_e('Search', 'aqualuxe'); ?>
                    </button>
                </form>
            </div>
        </div>
    </section>

    <section class="faq-main py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="md:col-span-1">
                    <?php
                    // Get FAQ categories
                    $faq_cats = get_terms(array(
                        'taxonomy' => 'aqualuxe_faq_cat',
                        'hide_empty' => true,
                    ));
                    
                    if (!is_wp_error($faq_cats) && !empty($faq_cats)) :
                    ?>
                    <div class="faq-categories bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md sticky top-24">
                        <h3 class="text-xl font-bold mb-4"><?php esc_html_e('FAQ Categories', 'aqualuxe'); ?></h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="#all" class="faq-cat-link active block px-3 py-2 bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded-md hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">
                                    <?php esc_html_e('All FAQs', 'aqualuxe'); ?>
                                </a>
                            </li>
                            <?php foreach ($faq_cats as $cat) : ?>
                                <li>
                                    <a href="#<?php echo esc_attr($cat->slug); ?>" class="faq-cat-link block px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-md transition-colors">
                                        <?php echo esc_html($cat->name); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="md:col-span-3">
                    <?php
                    // Get FAQ items from custom field
                    $faq_items = get_post_meta(get_the_ID(), 'faq_items', true);
                    
                    if ($faq_items && is_array($faq_items)) :
                    ?>
                        <div class="faq-accordion" x-data="{ activeTab: 0 }">
                            <?php foreach ($faq_items as $index => $item) : ?>
                                <div class="faq-item mb-4 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                    <button 
                                        @click="activeTab = activeTab === <?php echo esc_attr($index); ?> ? null : <?php echo esc_attr($index); ?>"
                                        class="w-full flex justify-between items-center p-4 bg-white dark:bg-gray-700 text-left font-medium focus:outline-none"
                                    >
                                        <span><?php echo esc_html($item['question']); ?></span>
                                        <svg 
                                            xmlns="http://www.w3.org/2000/svg" 
                                            class="h-5 w-5 transform transition-transform" 
                                            :class="{ 'rotate-180': activeTab === <?php echo esc_attr($index); ?> }"
                                            fill="none" 
                                            viewBox="0 0 24 24" 
                                            stroke="currentColor"
                                        >
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div 
                                        x-show="activeTab === <?php echo esc_attr($index); ?>"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                                        class="p-4 bg-gray-50 dark:bg-gray-800 prose dark:prose-invert max-w-none"
                                    >
                                        <?php echo wp_kses_post(wpautop($item['answer'])); ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php
                    else :
                        // Get FAQs from custom post type
                        $args = array(
                            'post_type' => 'aqualuxe_faq',
                            'posts_per_page' => -1,
                        );
                        $faq_query = new WP_Query($args);
                        
                        if ($faq_query->have_posts()) :
                            // Group FAQs by category
                            $faqs_by_category = array();
                            $all_faqs = array();
                            
                            while ($faq_query->have_posts()) : $faq_query->the_post();
                                $faq_item = array(
                                    'id' => get_the_ID(),
                                    'question' => get_the_title(),
                                    'answer' => get_the_content(),
                                );
                                
                                $all_faqs[] = $faq_item;
                                
                                $terms = get_the_terms(get_the_ID(), 'aqualuxe_faq_cat');
                                if (!empty($terms) && !is_wp_error($terms)) {
                                    foreach ($terms as $term) {
                                        if (!isset($faqs_by_category[$term->slug])) {
                                            $faqs_by_category[$term->slug] = array(
                                                'name' => $term->name,
                                                'items' => array(),
                                            );
                                        }
                                        $faqs_by_category[$term->slug]['items'][] = $faq_item;
                                    }
                                }
                            endwhile;
                            wp_reset_postdata();
                    ?>
                            <div class="faq-sections" x-data="{ activeTab: 0 }">
                                <div id="all" class="faq-section mb-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('All FAQs', 'aqualuxe'); ?></h2>
                                    
                                    <div class="faq-accordion">
                                        <?php foreach ($all_faqs as $index => $faq) : ?>
                                            <div class="faq-item mb-4 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                                <button 
                                                    @click="activeTab = activeTab === <?php echo esc_attr($index); ?> ? null : <?php echo esc_attr($index); ?>"
                                                    class="w-full flex justify-between items-center p-4 bg-white dark:bg-gray-700 text-left font-medium focus:outline-none"
                                                >
                                                    <span><?php echo esc_html($faq['question']); ?></span>
                                                    <svg 
                                                        xmlns="http://www.w3.org/2000/svg" 
                                                        class="h-5 w-5 transform transition-transform" 
                                                        :class="{ 'rotate-180': activeTab === <?php echo esc_attr($index); ?> }"
                                                        fill="none" 
                                                        viewBox="0 0 24 24" 
                                                        stroke="currentColor"
                                                    >
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </button>
                                                <div 
                                                    x-show="activeTab === <?php echo esc_attr($index); ?>"
                                                    x-transition:enter="transition ease-out duration-200"
                                                    x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                    x-transition:enter-end="opacity-100 transform translate-y-0"
                                                    x-transition:leave="transition ease-in duration-200"
                                                    x-transition:leave-start="opacity-100 transform translate-y-0"
                                                    x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                    class="p-4 bg-gray-50 dark:bg-gray-800 prose dark:prose-invert max-w-none"
                                                >
                                                    <?php echo wp_kses_post(wpautop($faq['answer'])); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                
                                <?php foreach ($faqs_by_category as $cat_slug => $category) : ?>
                                    <div id="<?php echo esc_attr($cat_slug); ?>" class="faq-section mb-12 hidden">
                                        <h2 class="text-2xl font-bold mb-6"><?php echo esc_html($category['name']); ?></h2>
                                        
                                        <div class="faq-accordion">
                                            <?php foreach ($category['items'] as $index => $faq) : 
                                                $unique_id = $cat_slug . '-' . $index;
                                            ?>
                                                <div class="faq-item mb-4 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                                    <button 
                                                        @click="activeTab = activeTab === '<?php echo esc_attr($unique_id); ?>' ? null : '<?php echo esc_attr($unique_id); ?>'"
                                                        class="w-full flex justify-between items-center p-4 bg-white dark:bg-gray-700 text-left font-medium focus:outline-none"
                                                    >
                                                        <span><?php echo esc_html($faq['question']); ?></span>
                                                        <svg 
                                                            xmlns="http://www.w3.org/2000/svg" 
                                                            class="h-5 w-5 transform transition-transform" 
                                                            :class="{ 'rotate-180': activeTab === '<?php echo esc_attr($unique_id); ?>' }"
                                                            fill="none" 
                                                            viewBox="0 0 24 24" 
                                                            stroke="currentColor"
                                                        >
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                        </svg>
                                                    </button>
                                                    <div 
                                                        x-show="activeTab === '<?php echo esc_attr($unique_id); ?>'"
                                                        x-transition:enter="transition ease-out duration-200"
                                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                                        x-transition:leave="transition ease-in duration-200"
                                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                                                        class="p-4 bg-gray-50 dark:bg-gray-800 prose dark:prose-invert max-w-none"
                                                    >
                                                        <?php echo wp_kses_post(wpautop($faq['answer'])); ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                    <?php
                        else :
                            if (get_the_content()) :
                                the_content();
                            else :
                    ?>
                                <div class="bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                                    <p class="text-center text-gray-600 dark:text-gray-300"><?php esc_html_e('No FAQs found. Please add some FAQs or content to this page.', 'aqualuxe'); ?></p>
                                </div>
                    <?php
                            endif;
                        endif;
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </section>

    <?php
    // Contact Section
    $show_contact = get_post_meta(get_the_ID(), 'faq_show_contact', true);
    $contact_title = get_post_meta(get_the_ID(), 'faq_contact_title', true);
    $contact_text = get_post_meta(get_the_ID(), 'faq_contact_text', true);
    $contact_button_text = get_post_meta(get_the_ID(), 'faq_contact_button_text', true);
    $contact_button_url = get_post_meta(get_the_ID(), 'faq_contact_button_url', true);
    
    if ($show_contact) :
    ?>
    <section class="faq-contact py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <?php if ($contact_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($contact_title); ?></h2>
                <?php else : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php esc_html_e('Still Have Questions?', 'aqualuxe'); ?></h2>
                <?php endif; ?>
                
                <?php if ($contact_text) : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-300 mb-8"><?php echo esc_html($contact_text); ?></p>
                <?php else : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-300 mb-8"><?php esc_html_e('If you couldn\'t find the answer to your question, please feel free to contact us.', 'aqualuxe'); ?></p>
                <?php endif; ?>
                
                <?php if ($contact_button_text && $contact_button_url) : ?>
                    <a href="<?php echo esc_url($contact_button_url); ?>" class="inline-block px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-colors text-lg font-medium">
                        <?php echo esc_html($contact_button_text); ?>
                    </a>
                <?php else : ?>
                    <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="inline-block px-8 py-4 bg-primary-600 hover:bg-primary-700 text-white rounded-md transition-colors text-lg font-medium">
                        <?php esc_html_e('Contact Us', 'aqualuxe'); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main><!-- #main -->

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ Category Navigation
    const faqCatLinks = document.querySelectorAll('.faq-cat-link');
    const faqSections = document.querySelectorAll('.faq-section');
    
    faqCatLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all links
            faqCatLinks.forEach(l => l.classList.remove('active', 'bg-primary-100', 'dark:bg-primary-900', 'text-primary-800', 'dark:text-primary-200'));
            faqCatLinks.forEach(l => l.classList.add('hover:bg-gray-100', 'dark:hover:bg-gray-600'));
            
            // Add active class to clicked link
            this.classList.add('active', 'bg-primary-100', 'dark:bg-primary-900', 'text-primary-800', 'dark:text-primary-200');
            this.classList.remove('hover:bg-gray-100', 'dark:hover:bg-gray-600');
            
            // Hide all sections
            faqSections.forEach(section => section.classList.add('hidden'));
            
            // Show the target section
            const targetId = this.getAttribute('href').substring(1);
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.classList.remove('hidden');
            }
        });
    });
});
</script>

<?php
get_footer();