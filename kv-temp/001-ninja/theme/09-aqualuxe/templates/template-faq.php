<?php
/**
 * Template Name: FAQ
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">

    <div class="container py-12">
        <header class="page-header text-center mb-12">
            <h1 class="page-title text-4xl md:text-5xl font-bold mb-4"><?php the_title(); ?></h1>
            <?php if (has_excerpt()) : ?>
                <div class="page-description text-lg text-gray-600 max-w-3xl mx-auto">
                    <?php the_excerpt(); ?>
                </div>
            <?php endif; ?>
        </header>

        <div class="page-content">
            <?php
            // Display the page content first
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            
            // Get FAQ categories from theme options or use defaults
            $faq_categories = array(
                'general' => __('General Questions', 'aqualuxe'),
                'products' => __('Products & Livestock', 'aqualuxe'),
                'shipping' => __('Shipping & Delivery', 'aqualuxe'),
                'services' => __('Services & Maintenance', 'aqualuxe'),
                'wholesale' => __('Wholesale & Business', 'aqualuxe'),
            );
            
            // Get FAQs from theme options or use defaults
            $faqs = array(
                'general' => array(
                    array(
                        'question' => __('What is AquaLuxe?', 'aqualuxe'),
                        'answer' => __('AquaLuxe is a vertically integrated ornamental fish farming and aquatic lifestyle company. We serve both local and international markets across multiple segments, providing premium quality fish, plants, and aquarium solutions.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Where are you located?', 'aqualuxe'),
                        'answer' => __('Our main facility is located in Colombo, Sri Lanka. We also have distribution centers in several countries to ensure efficient delivery to our international customers.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Do you have a physical store I can visit?', 'aqualuxe'),
                        'answer' => __('Yes, we have a showroom at our main facility where you can view our products and speak with our experts. Please contact us to schedule a visit.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('How can I contact customer support?', 'aqualuxe'),
                        'answer' => __('You can reach our customer support team through email at support@aqualuxe.com, by phone at +94 123 456 7890, or by filling out the contact form on our website.', 'aqualuxe'),
                    ),
                ),
                'products' => array(
                    array(
                        'question' => __('What types of fish do you sell?', 'aqualuxe'),
                        'answer' => __('We offer a wide variety of freshwater and marine fish, including popular species like Guppies, Discus, Goldfish, Koi, Clownfish, Tangs, and many more. We also specialize in rare and exotic collector fish.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Are your fish farm-raised or wild-caught?', 'aqualuxe'),
                        'answer' => __('The majority of our fish are farm-raised in our own facilities or sourced from trusted local breeders. This ensures sustainable practices and healthy, disease-free livestock. We do offer some wild-caught species, but only from suppliers who follow ethical and sustainable collection practices.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Do you sell aquatic plants?', 'aqualuxe'),
                        'answer' => __('Yes, we offer a wide selection of aquatic plants, including submerged and emergent varieties, tissue culture cups, aquascaping mosses and ferns, and both CO₂-tolerant and low-maintenance species.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('What equipment and supplies do you offer?', 'aqualuxe'),
                        'answer' => __('We carry a comprehensive range of aquarium equipment and supplies, including tanks (standard and custom-made), filtration systems, lighting, CO₂ systems, substrate, decor, water conditioners, and feeding and health products.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Do you offer guarantees on your livestock?', 'aqualuxe'),
                        'answer' => __('Yes, we offer a 48-hour guarantee on all livestock. If your fish or plants arrive dead or die within 48 hours of delivery, we will replace them or provide a refund, provided water parameters are within acceptable ranges.', 'aqualuxe'),
                    ),
                ),
                'shipping' => array(
                    array(
                        'question' => __('Do you ship internationally?', 'aqualuxe'),
                        'answer' => __('Yes, we ship to most countries worldwide. Shipping costs and delivery times vary depending on your location. Please contact us for specific shipping information for your country.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('How do you ensure the health of fish during shipping?', 'aqualuxe'),
                        'answer' => __('We use specialized packaging techniques including insulated boxes, heat packs or cooling packs (depending on the season), and oxygen-filled bags. All fish are fasted before shipping and water is treated with stress-reducing additives.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('What is your shipping policy for live animals?', 'aqualuxe'),
                        'answer' => __('We only ship live animals via express shipping methods to ensure they arrive in good condition. We carefully monitor weather conditions and may delay shipments during extreme temperatures to ensure the safety of the animals.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('How long does shipping take?', 'aqualuxe'),
                        'answer' => __('Domestic shipping typically takes 1-2 business days. International shipping varies by destination but generally takes 2-5 business days. We provide tracking information for all shipments.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('What are the shipping costs?', 'aqualuxe'),
                        'answer' => __('Shipping costs depend on the size and weight of your order, as well as your location. You can view estimated shipping costs by adding items to your cart and entering your shipping address before checkout.', 'aqualuxe'),
                    ),
                ),
                'services' => array(
                    array(
                        'question' => __('What services do you offer?', 'aqualuxe'),
                        'answer' => __('We offer a range of professional services including custom aquarium design and installation, regular maintenance services, quarantine and health check services, training and consultancy, and event rentals for short-term aquarium setups.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Do you offer aquarium maintenance services?', 'aqualuxe'),
                        'answer' => __('Yes, we offer professional aquarium maintenance services for both residential and commercial clients. Our services include regular cleaning, water testing, equipment maintenance, and livestock health checks.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Can you design and install a custom aquarium for my home or business?', 'aqualuxe'),
                        'answer' => __('Absolutely! We specialize in custom aquarium design and installation for homes, offices, hotels, restaurants, and other commercial spaces. Our team will work with you to create a unique aquatic display that meets your specific requirements and complements your space.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Do you offer aquarium rentals for events?', 'aqualuxe'),
                        'answer' => __('Yes, we offer short-term aquarium rentals for events, exhibitions, and special occasions. Our team will handle the setup, maintenance during the event, and removal afterward.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Do you provide training for aquarium maintenance?', 'aqualuxe'),
                        'answer' => __('Yes, we offer training sessions for hobbyists and professionals on various aspects of aquarium maintenance, fish health, plant care, and aquascaping techniques. Contact us for more information about our training programs.', 'aqualuxe'),
                    ),
                ),
                'wholesale' => array(
                    array(
                        'question' => __('Do you offer wholesale pricing?', 'aqualuxe'),
                        'answer' => __('Yes, we offer wholesale pricing for pet stores, aquarium shops, and other businesses. Please contact our wholesale department for more information and to set up an account.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('What are the requirements for becoming a wholesale customer?', 'aqualuxe'),
                        'answer' => __('To become a wholesale customer, you need to have a registered business related to the aquatic industry. You\'ll need to provide your business registration documents, tax ID, and complete our wholesale application form.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Is there a minimum order quantity for wholesale purchases?', 'aqualuxe'),
                        'answer' => __('Yes, we have minimum order quantities for wholesale purchases, which vary depending on the product category. Please contact our wholesale department for specific information.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Do you offer dropshipping services?', 'aqualuxe'),
                        'answer' => __('Yes, we offer dropshipping services for selected products. Please contact our business development team for more information about our dropshipping program and requirements.', 'aqualuxe'),
                    ),
                    array(
                        'question' => __('Can I become an authorized distributor for AquaLuxe products?', 'aqualuxe'),
                        'answer' => __('We are always looking for distribution partners in new markets. Please contact our business development team with information about your company, market coverage, and experience in the aquatic industry to discuss distribution opportunities.', 'aqualuxe'),
                    ),
                ),
            );
            ?>
            
            <!-- FAQ Search -->
            <div class="faq-search mb-12">
                <div class="max-w-2xl mx-auto">
                    <div class="relative">
                        <input type="text" id="faq-search" class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" placeholder="<?php esc_attr_e('Search FAQs...', 'aqualuxe'); ?>">
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400">
                            <i class="fa fa-search"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Categories Navigation -->
            <div class="faq-categories mb-12">
                <div class="flex flex-wrap justify-center gap-4">
                    <?php foreach ($faq_categories as $slug => $name) : ?>
                        <a href="#<?php echo esc_attr($slug); ?>" class="category-link px-6 py-3 bg-gray-100 hover:bg-primary hover:text-white rounded-full transition duration-300">
                            <?php echo esc_html($name); ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- FAQ Content -->
            <div class="faq-content" x-data="{ openItem: null }">
                <?php foreach ($faq_categories as $slug => $name) : ?>
                    <section id="<?php echo esc_attr($slug); ?>" class="faq-section mb-16">
                        <h2 class="section-title text-3xl font-bold mb-8 pb-2 border-b border-gray-200"><?php echo esc_html($name); ?></h2>
                        
                        <div class="faq-items space-y-4">
                            <?php 
                            if (isset($faqs[$slug]) && !empty($faqs[$slug])) :
                                foreach ($faqs[$slug] as $index => $faq) : 
                                    $item_id = $slug . '-' . $index;
                            ?>
                                <div class="faq-item bg-white rounded-lg shadow-md overflow-hidden">
                                    <button 
                                        class="faq-question w-full text-left px-6 py-4 flex justify-between items-center focus:outline-none"
                                        @click="openItem = openItem === '<?php echo $item_id; ?>' ? null : '<?php echo $item_id; ?>'"
                                    >
                                        <span class="text-lg font-medium"><?php echo esc_html($faq['question']); ?></span>
                                        <span class="transform transition-transform" :class="{ 'rotate-180': openItem === '<?php echo $item_id; ?>' }">
                                            <i class="fa fa-chevron-down"></i>
                                        </span>
                                    </button>
                                    
                                    <div 
                                        class="faq-answer px-6 pb-4" 
                                        x-show="openItem === '<?php echo $item_id; ?>'"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 transform translate-y-0"
                                        x-transition:leave-end="opacity-0 transform -translate-y-2"
                                    >
                                        <p class="text-gray-600"><?php echo esc_html($faq['answer']); ?></p>
                                    </div>
                                </div>
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </div>
                    </section>
                <?php endforeach; ?>
            </div>
            
            <!-- Contact CTA -->
            <div class="contact-cta bg-gray-50 p-8 rounded-lg text-center">
                <h3 class="text-2xl font-bold mb-4"><?php esc_html_e('Still have questions?', 'aqualuxe'); ?></h3>
                <p class="text-lg text-gray-600 mb-6"><?php esc_html_e('If you couldn\'t find the answer to your question, please contact our customer support team.', 'aqualuxe'); ?></p>
                <a href="<?php echo esc_url(get_permalink(get_page_by_path('contact'))); ?>" class="btn btn-primary"><?php esc_html_e('Contact Us', 'aqualuxe'); ?></a>
            </div>
        </div>
    </div>

</main><!-- #main -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('faq-search');
        const faqItems = document.querySelectorAll('.faq-item');
        
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer p').textContent.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Show/hide sections based on visible items
            document.querySelectorAll('.faq-section').forEach(section => {
                const visibleItems = section.querySelectorAll('.faq-item[style="display: block"]').length;
                const hiddenItems = section.querySelectorAll('.faq-item[style="display: none"]').length;
                const totalItems = visibleItems + hiddenItems;
                
                if (visibleItems === 0 && totalItems > 0) {
                    section.style.display = 'none';
                } else {
                    section.style.display = 'block';
                }
            });
        });
    });
</script>

<?php
get_footer();