<?php
/**
 * Services FAQ Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get customizer options
$title = get_theme_mod('aqualuxe_services_faq_title', __('Frequently Asked Questions', 'aqualuxe'));
$subtitle = get_theme_mod('aqualuxe_services_faq_subtitle', __('Common Questions', 'aqualuxe'));
$description = get_theme_mod('aqualuxe_services_faq_description', __('Find answers to the most common questions about our services.', 'aqualuxe'));
$layout = get_theme_mod('aqualuxe_services_faq_layout', 'accordion'); // accordion or grid
$show_search = get_theme_mod('aqualuxe_services_faq_show_search', true);
$show_categories = get_theme_mod('aqualuxe_services_faq_show_categories', true);
$contact_text = get_theme_mod('aqualuxe_services_faq_contact_text', __('Can\'t find the answer you\'re looking for? Contact our team for assistance.', 'aqualuxe'));
$contact_button = get_theme_mod('aqualuxe_services_faq_contact_button', __('Contact Us', 'aqualuxe'));
$contact_url = get_theme_mod('aqualuxe_services_faq_contact_url', '#contact');

// Get FAQs
// In a real implementation, this would likely use a custom post type for FAQs
// For this template, we'll use sample data
$faqs = [
    [
        'category' => 'installation',
        'question' => __('How long does a typical pool installation take?', 'aqualuxe'),
        'answer' => __('The timeline for pool installation varies depending on the size, type, and complexity of the project. A standard in-ground pool typically takes 2-4 weeks from excavation to completion. Factors like weather conditions, permit approvals, and site accessibility can affect the timeline. During your consultation, we\'ll provide a detailed schedule specific to your project.', 'aqualuxe'),
    ],
    [
        'category' => 'installation',
        'question' => __('Do I need permits for pool installation?', 'aqualuxe'),
        'answer' => __('Yes, most jurisdictions require permits for pool installation. The specific requirements vary by location. As part of our service, we handle the permit application process on your behalf, ensuring all necessary approvals are obtained before beginning construction. This includes zoning compliance, electrical permits, and safety barrier requirements.', 'aqualuxe'),
    ],
    [
        'category' => 'maintenance',
        'question' => __('How often should I service my pool?', 'aqualuxe'),
        'answer' => __('For optimal water quality and equipment longevity, residential pools should be serviced weekly during the swimming season. This includes water testing, chemical balancing, cleaning, and equipment checks. During off-seasons, bi-weekly or monthly maintenance may be sufficient depending on your climate and usage. Our maintenance plans can be customized to your specific needs and schedule.', 'aqualuxe'),
    ],
    [
        'category' => 'maintenance',
        'question' => __('What chemicals are needed for pool maintenance?', 'aqualuxe'),
        'answer' => __('The essential chemicals for pool maintenance include chlorine or alternative sanitizers, pH adjusters (muriatic acid and sodium bicarbonate), calcium hardness increasers, algaecides, and stabilizers. The specific combination and amounts depend on your pool type, usage, and local water conditions. Our technicians are trained to properly balance these chemicals for safe, clear water while minimizing chemical usage.', 'aqualuxe'),
    ],
    [
        'category' => 'repairs',
        'question' => __('How do I know if my pool pump needs replacement?', 'aqualuxe'),
        'answer' => __('Signs that your pool pump may need replacement include unusual noises (grinding, screeching), leaking, inconsistent water flow, frequent overheating, difficulty starting, or age (most pumps last 8-12 years). If your energy bills have increased significantly, a more efficient pump might be a cost-effective replacement. Our technicians can evaluate your pump\'s condition and recommend repair or replacement options.', 'aqualuxe'),
    ],
    [
        'category' => 'repairs',
        'question' => __('Can pool leaks be repaired without draining?', 'aqualuxe'),
        'answer' => __('Many pool leaks can be detected and repaired without completely draining the pool. Using specialized equipment, we can locate leaks in the pool shell, plumbing, or equipment while the pool is still filled. Some repairs may require lowering the water level partially. Our leak detection service uses non-invasive methods first, minimizing water loss and disruption to your pool.', 'aqualuxe'),
    ],
    [
        'category' => 'costs',
        'question' => __('What factors affect the cost of pool installation?', 'aqualuxe'),
        'answer' => __('The cost of pool installation is influenced by several factors: pool size and depth, material quality (vinyl, fiberglass, concrete), site accessibility and preparation needs, additional features (lighting, heating, water features), deck and landscaping requirements, and local permit costs. Premium finishes, automation systems, and custom designs will also affect the final price. We provide detailed, transparent quotes after a thorough site evaluation.', 'aqualuxe'),
    ],
    [
        'category' => 'costs',
        'question' => __('Are there financing options available for pool projects?', 'aqualuxe'),
        'answer' => __('Yes, we offer several financing options to help make your pool project affordable. These include installment plans, home improvement loans, and partnerships with financial institutions offering competitive rates. Some options allow for no payments for several months, giving you time to enjoy your pool before payments begin. Our financial specialists can help you explore the best options for your situation.', 'aqualuxe'),
    ],
];

// Get unique categories
$categories = [];
if ($show_categories) {
    foreach ($faqs as $faq) {
        if (!empty($faq['category']) && !in_array($faq['category'], $categories)) {
            $categories[] = $faq['category'];
        }
    }
}

// Category labels
$category_labels = [
    'installation' => __('Installation', 'aqualuxe'),
    'maintenance' => __('Maintenance', 'aqualuxe'),
    'repairs' => __('Repairs', 'aqualuxe'),
    'costs' => __('Costs & Financing', 'aqualuxe'),
];
?>

<section id="faq" class="services-faq py-16 md:py-24 bg-gray-50 dark:bg-gray-900">
    <div class="container mx-auto px-4">
        <div class="text-center max-w-3xl mx-auto mb-12">
            <?php if ($subtitle) : ?>
                <span class="inline-block px-3 py-1 mb-4 text-sm font-semibold tracking-wider uppercase rounded-full text-blue-700 dark:text-blue-300 bg-blue-100 dark:bg-blue-900 bg-opacity-50">
                    <?php echo esc_html($subtitle); ?>
                </span>
            <?php endif; ?>
            
            <?php if ($title) : ?>
                <h2 class="text-3xl md:text-4xl font-bold mb-6 text-gray-900 dark:text-white">
                    <?php echo esc_html($title); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ($description) : ?>
                <p class="text-lg text-gray-700 dark:text-gray-300">
                    <?php echo esc_html($description); ?>
                </p>
            <?php endif; ?>
        </div>
        
        <?php if ($show_search) : ?>
            <div class="max-w-2xl mx-auto mb-10">
                <div class="relative">
                    <input type="text" id="faq-search" class="w-full px-4 py-3 pl-12 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="<?php esc_attr_e('Search for questions...', 'aqualuxe'); ?>">
                    <div class="absolute left-4 top-3.5 text-gray-500 dark:text-gray-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if ($show_categories && !empty($categories)) : ?>
            <div class="flex flex-wrap justify-center gap-2 mb-10">
                <button class="category-filter active px-4 py-2 rounded-full bg-blue-600 text-white font-medium" data-category="all">
                    <?php esc_html_e('All', 'aqualuxe'); ?>
                </button>
                
                <?php foreach ($categories as $category) : ?>
                    <button class="category-filter px-4 py-2 rounded-full bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-medium hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors" data-category="<?php echo esc_attr($category); ?>">
                        <?php echo esc_html($category_labels[$category] ?? ucfirst($category)); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($layout === 'accordion') : ?>
            <div class="max-w-3xl mx-auto">
                <div class="faq-accordion space-y-4">
                    <?php foreach ($faqs as $index => $faq) : ?>
                        <div class="faq-item bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden" data-category="<?php echo esc_attr($faq['category'] ?? ''); ?>">
                            <button class="faq-question w-full flex items-center justify-between px-6 py-4 text-left focus:outline-none" aria-expanded="false" aria-controls="faq-answer-<?php echo esc_attr($index); ?>">
                                <span class="text-lg font-semibold text-gray-900 dark:text-white"><?php echo esc_html($faq['question']); ?></span>
                                <svg class="faq-icon w-6 h-6 text-blue-600 dark:text-blue-400 transform transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <div id="faq-answer-<?php echo esc_attr($index); ?>" class="faq-answer hidden px-6 pb-4">
                                <p class="text-gray-700 dark:text-gray-300">
                                    <?php echo esc_html($faq['answer']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php elseif ($layout === 'grid') : ?>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <?php foreach ($faqs as $faq) : ?>
                    <div class="faq-item bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" data-category="<?php echo esc_attr($faq['category'] ?? ''); ?>">
                        <h3 class="text-lg font-semibold mb-3 text-gray-900 dark:text-white">
                            <?php echo esc_html($faq['question']); ?>
                        </h3>
                        
                        <p class="text-gray-700 dark:text-gray-300">
                            <?php echo esc_html($faq['answer']); ?>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        
        <?php if ($contact_text || ($contact_button && $contact_url)) : ?>
            <div class="text-center mt-12">
                <?php if ($contact_text) : ?>
                    <p class="text-gray-700 dark:text-gray-300 mb-4">
                        <?php echo esc_html($contact_text); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($contact_button && $contact_url) : ?>
                    <a href="<?php echo esc_url($contact_url); ?>" class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <?php echo esc_html($contact_button); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // FAQ Accordion functionality
            const faqQuestions = document.querySelectorAll('.faq-question');
            
            faqQuestions.forEach(question => {
                question.addEventListener('click', () => {
                    const faqItem = question.parentElement;
                    const answer = question.nextElementSibling;
                    const icon = question.querySelector('.faq-icon');
                    
                    // Toggle current item
                    answer.classList.toggle('hidden');
                    icon.classList.toggle('rotate-180');
                    question.setAttribute('aria-expanded', answer.classList.contains('hidden') ? 'false' : 'true');
                    
                    // Optional: Close other items
                    // faqQuestions.forEach(otherQuestion => {
                    //     if (otherQuestion !== question) {
                    //         const otherAnswer = otherQuestion.nextElementSibling;
                    //         const otherIcon = otherQuestion.querySelector('.faq-icon');
                    //         
                    //         otherAnswer.classList.add('hidden');
                    //         otherIcon.classList.remove('rotate-180');
                    //         otherQuestion.setAttribute('aria-expanded', 'false');
                    //     }
                    // });
                });
            });
            
            // FAQ Search functionality
            const searchInput = document.getElementById('faq-search');
            
            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    const searchTerm = searchInput.value.toLowerCase();
                    const faqItems = document.querySelectorAll('.faq-item');
                    
                    faqItems.forEach(item => {
                        const question = item.querySelector('.faq-question').textContent.toLowerCase();
                        const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
                        
                        if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    });
                });
            }
            
            // Category filtering
            const categoryFilters = document.querySelectorAll('.category-filter');
            
            if (categoryFilters.length > 0) {
                categoryFilters.forEach(filter => {
                    filter.addEventListener('click', () => {
                        // Update active state
                        categoryFilters.forEach(f => f.classList.remove('active', 'bg-blue-600', 'text-white'));
                        categoryFilters.forEach(f => f.classList.add('bg-gray-200', 'dark:bg-gray-700', 'text-gray-800', 'dark:text-gray-200'));
                        
                        filter.classList.add('active', 'bg-blue-600', 'text-white');
                        filter.classList.remove('bg-gray-200', 'dark:bg-gray-700', 'text-gray-800', 'dark:text-gray-200');
                        
                        // Filter items
                        const category = filter.getAttribute('data-category');
                        const faqItems = document.querySelectorAll('.faq-item');
                        
                        faqItems.forEach(item => {
                            if (category === 'all' || item.getAttribute('data-category') === category) {
                                item.style.display = '';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    });
                });
            }
        });
    </script>
</section>