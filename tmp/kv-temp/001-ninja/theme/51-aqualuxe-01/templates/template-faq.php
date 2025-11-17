<?php
/**
 * Template Name: FAQ Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div class="container">
    <div class="row no-sidebar">
        <main id="primary" class="site-main">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-faq-page'); ?>>
                    <header class="aqualuxe-faq-header">
                        <?php the_title('<h1 class="aqualuxe-faq-title">', '</h1>'); ?>
                        
                        <?php if (has_excerpt()) : ?>
                            <div class="aqualuxe-faq-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <div class="aqualuxe-faq-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                
                <?php
                // Get FAQ categories and items
                $faq_categories = array();
                $faq_items = array();
                
                // Check if ACF is available
                if (function_exists('get_field')) {
                    // Get FAQ items from ACF
                    $faq_items_acf = get_field('faq_items');
                    
                    if ($faq_items_acf) {
                        foreach ($faq_items_acf as $item) {
                            $category = isset($item['category']) ? $item['category'] : __('General', 'aqualuxe');
                            
                            if (!isset($faq_items[$category])) {
                                $faq_items[$category] = array();
                                $faq_categories[] = $category;
                            }
                            
                            $faq_items[$category][] = array(
                                'question' => $item['question'],
                                'answer' => $item['answer'],
                            );
                        }
                    }
                }
                
                // If no FAQ items from ACF, use default ones
                if (empty($faq_items)) {
                    $faq_categories = array(
                        __('Shipping', 'aqualuxe'),
                        __('Care', 'aqualuxe'),
                        __('Purchasing', 'aqualuxe'),
                        __('Export/Import', 'aqualuxe'),
                    );
                    
                    $faq_items = array(
                        __('Shipping', 'aqualuxe') => array(
                            array(
                                'question' => __('How do you ship live fish?', 'aqualuxe'),
                                'answer' => __('We ship live fish in specially designed containers with oxygen and heat packs when necessary. All fish are packed by our expert team to ensure they arrive safely at your doorstep.', 'aqualuxe'),
                            ),
                            array(
                                'question' => __('What countries do you ship to?', 'aqualuxe'),
                                'answer' => __('We currently ship to most countries in North America, Europe, and Asia. Some restrictions may apply due to local import regulations. Please contact us for specific information about your location.', 'aqualuxe'),
                            ),
                            array(
                                'question' => __('How long does shipping take?', 'aqualuxe'),
                                'answer' => __('Domestic shipping typically takes 1-2 business days. International shipping can take 2-5 business days depending on the destination and customs clearance.', 'aqualuxe'),
                            ),
                        ),
                        __('Care', 'aqualuxe') => array(
                            array(
                                'question' => __('How should I acclimate my new fish?', 'aqualuxe'),
                                'answer' => __('We recommend the drip acclimation method for all new fish. Float the bag in your aquarium for 15 minutes, then open the bag and add small amounts of your tank water every 5 minutes for at least 30 minutes before transferring the fish.', 'aqualuxe'),
                            ),
                            array(
                                'question' => __('What water parameters are best for tropical fish?', 'aqualuxe'),
                                'answer' => __('Most tropical freshwater fish thrive in water with a temperature of 75-80°F (24-27°C), pH of 6.8-7.5, and moderate hardness. However, specific species may have different requirements, so always research your particular fish.', 'aqualuxe'),
                            ),
                            array(
                                'question' => __('How often should I feed my fish?', 'aqualuxe'),
                                'answer' => __('Most fish should be fed 1-2 times daily, with only as much food as they can consume in 2-3 minutes. Overfeeding can lead to poor water quality and health problems.', 'aqualuxe'),
                            ),
                        ),
                        __('Purchasing', 'aqualuxe') => array(
                            array(
                                'question' => __('Do you offer guarantees on live fish?', 'aqualuxe'),
                                'answer' => __('Yes, we offer a 7-day guarantee on all live fish. If your fish arrives dead or dies within 7 days, we will replace it or provide a refund with proper documentation.', 'aqualuxe'),
                            ),
                            array(
                                'question' => __('Can I place a special order for fish not listed on your website?', 'aqualuxe'),
                                'answer' => __('Absolutely! We have connections with suppliers worldwide and can source many rare and exotic species. Contact our customer service team with your request, and we\'ll do our best to find what you\'re looking for.', 'aqualuxe'),
                            ),
                            array(
                                'question' => __('What payment methods do you accept?', 'aqualuxe'),
                                'answer' => __('We accept all major credit cards, PayPal, bank transfers, and cryptocurrency for international orders.', 'aqualuxe'),
                            ),
                        ),
                        __('Export/Import', 'aqualuxe') => array(
                            array(
                                'question' => __('Do I need a permit to import fish to my country?', 'aqualuxe'),
                                'answer' => __('Many countries require import permits for live fish. We can help guide you through the process, but ultimately it is the customer\'s responsibility to obtain any necessary permits for their country.', 'aqualuxe'),
                            ),
                            array(
                                'question' => __('Are there any restricted species that cannot be shipped internationally?', 'aqualuxe'),
                                'answer' => __('Yes, certain species are restricted due to CITES regulations or local import laws. This includes some endangered species and invasive species. Our team can advise you on what species are allowed in your country.', 'aqualuxe'),
                            ),
                            array(
                                'question' => __('How do you handle customs documentation?', 'aqualuxe'),
                                'answer' => __('We prepare all necessary export documentation including health certificates, commercial invoices, and packing lists. For CITES-listed species, we obtain all required permits before shipping.', 'aqualuxe'),
                            ),
                        ),
                    );
                }
                
                // Display FAQ items
                if (!empty($faq_items)) :
                ?>
                    <div class="aqualuxe-faq-container">
                        <?php if (count($faq_categories) > 1) : ?>
                            <div class="aqualuxe-faq-categories">
                                <ul class="aqualuxe-faq-categories-list">
                                    <?php foreach ($faq_categories as $index => $category) : ?>
                                        <li class="aqualuxe-faq-categories-item<?php echo $index === 0 ? ' active' : ''; ?>">
                                            <a href="#faq-category-<?php echo esc_attr(sanitize_title($category)); ?>" class="aqualuxe-faq-categories-link">
                                                <?php echo esc_html($category); ?>
                                            </a>
                                        </li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <div class="aqualuxe-faq-items">
                            <?php foreach ($faq_categories as $index => $category) : ?>
                                <div id="faq-category-<?php echo esc_attr(sanitize_title($category)); ?>" class="aqualuxe-faq-category<?php echo $index === 0 ? ' active' : ''; ?>">
                                    <h2 class="aqualuxe-faq-category-title"><?php echo esc_html($category); ?></h2>
                                    
                                    <div class="aqualuxe-faq-category-items">
                                        <?php foreach ($faq_items[$category] as $item) : ?>
                                            <div class="aqualuxe-faq-item">
                                                <div class="aqualuxe-faq-question">
                                                    <h3><?php echo esc_html($item['question']); ?></h3>
                                                    <span class="aqualuxe-faq-toggle">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-faq-toggle-plus"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"></path></svg>
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-faq-toggle-minus"><path d="M19 13H5v-2h14v2z"></path></svg>
                                                    </span>
                                                </div>
                                                <div class="aqualuxe-faq-answer">
                                                    <?php echo wp_kses_post($item['answer']); ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            // FAQ category tabs
                            const categoryLinks = document.querySelectorAll('.aqualuxe-faq-categories-link');
                            const categories = document.querySelectorAll('.aqualuxe-faq-category');
                            
                            categoryLinks.forEach(link => {
                                link.addEventListener('click', function(e) {
                                    e.preventDefault();
                                    
                                    const targetId = this.getAttribute('href');
                                    const targetCategory = document.querySelector(targetId);
                                    
                                    // Remove active class from all links and categories
                                    categoryLinks.forEach(link => link.parentElement.classList.remove('active'));
                                    categories.forEach(category => category.classList.remove('active'));
                                    
                                    // Add active class to clicked link and target category
                                    this.parentElement.classList.add('active');
                                    targetCategory.classList.add('active');
                                });
                            });
                            
                            // FAQ accordion
                            const faqQuestions = document.querySelectorAll('.aqualuxe-faq-question');
                            
                            faqQuestions.forEach(question => {
                                question.addEventListener('click', function() {
                                    const faqItem = this.parentElement;
                                    const faqAnswer = this.nextElementSibling;
                                    
                                    // Toggle active class
                                    faqItem.classList.toggle('active');
                                    
                                    // Toggle answer visibility
                                    if (faqItem.classList.contains('active')) {
                                        faqAnswer.style.maxHeight = faqAnswer.scrollHeight + 'px';
                                    } else {
                                        faqAnswer.style.maxHeight = null;
                                    }
                                });
                            });
                        });
                    </script>
                <?php endif; ?>
                
                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
            endwhile; // End of the loop.
            ?>
        </main><!-- #main -->
    </div>
</div>

<?php
get_footer();