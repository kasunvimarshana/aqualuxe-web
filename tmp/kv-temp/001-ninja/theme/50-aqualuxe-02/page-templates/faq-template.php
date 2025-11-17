<?php
/**
 * Template Name: FAQ Page
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container">
        <?php
        // Display breadcrumbs if a breadcrumb plugin is active
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<div class="breadcrumbs">', '</div>' );
        } elseif ( function_exists( 'bcn_display' ) ) {
            echo '<div class="breadcrumbs">';
            bcn_display();
            echo '</div>';
        }
        ?>

        <div class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
            
            <?php
            // Get page subtitle
            $subtitle = get_post_meta( get_the_ID(), 'page_subtitle', true );
            if ( empty( $subtitle ) ) {
                $subtitle = __( 'Find answers to your questions about our products and services', 'aqualuxe' );
            }
            ?>
            
            <p class="page-subtitle"><?php echo esc_html( $subtitle ); ?></p>
        </div>

        <?php
        // Check if the page has a featured image
        if ( has_post_thumbnail() ) :
        ?>
        <div class="faq-hero">
            <?php the_post_thumbnail( 'full', array( 'class' => 'faq-hero-image' ) ); ?>
        </div>
        <?php endif; ?>

        <div class="faq-intro">
            <?php
            while ( have_posts() ) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>

        <?php
        // FAQ Search
        $enable_search = get_post_meta( get_the_ID(), 'enable_faq_search', true );
        
        if ( $enable_search !== 'no' ) :
        ?>
        <div class="faq-search-section">
            <h2><?php esc_html_e( 'How can we help?', 'aqualuxe' ); ?></h2>
            <div class="faq-search-container">
                <form role="search" method="get" class="faq-search-form" action="javascript:void(0);">
                    <input type="text" id="faq-search-input" placeholder="<?php esc_attr_e( 'Search for answers...', 'aqualuxe' ); ?>">
                    <button type="submit" class="search-submit">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                            <path fill-rule="evenodd" d="M10.5 3.75a6.75 6.75 0 100 13.5 6.75 6.75 0 000-13.5zM2.25 10.5a8.25 8.25 0 1114.59 5.28l4.69 4.69a.75.75 0 11-1.06 1.06l-4.69-4.69A8.25 8.25 0 012.25 10.5z" clip-rule="evenodd" />
                        </svg>
                        <span class="screen-reader-text"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                    </button>
                </form>
            </div>
        </div>
        <?php endif; ?>

        <?php
        // FAQ Categories
        $faq_categories = get_post_meta( get_the_ID(), 'faq_categories', true );
        
        if ( empty( $faq_categories ) ) {
            // Default FAQ categories if none are defined
            $faq_categories = array(
                array(
                    'title' => __( 'General Questions', 'aqualuxe' ),
                    'slug' => 'general',
                ),
                array(
                    'title' => __( 'Products', 'aqualuxe' ),
                    'slug' => 'products',
                ),
                array(
                    'title' => __( 'Services', 'aqualuxe' ),
                    'slug' => 'services',
                ),
                array(
                    'title' => __( 'Maintenance', 'aqualuxe' ),
                    'slug' => 'maintenance',
                ),
                array(
                    'title' => __( 'Shipping & Returns', 'aqualuxe' ),
                    'slug' => 'shipping-returns',
                ),
            );
        }
        
        if ( ! empty( $faq_categories ) && is_array( $faq_categories ) ) :
        ?>
        <div class="faq-categories">
            <div class="category-filters">
                <button class="category-filter active" data-category="all"><?php esc_html_e( 'All', 'aqualuxe' ); ?></button>
                <?php foreach ( $faq_categories as $category ) : ?>
                    <button class="category-filter" data-category="<?php echo esc_attr( $category['slug'] ); ?>">
                        <?php echo esc_html( $category['title'] ); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php
        // FAQs
        $faqs = get_post_meta( get_the_ID(), 'faqs', true );
        
        if ( empty( $faqs ) ) {
            // Default FAQs if none are defined
            $faqs = array(
                // General Questions
                array(
                    'question' => __( 'What types of aquariums do you offer?', 'aqualuxe' ),
                    'answer' => __( 'We offer a wide range of aquariums including freshwater, saltwater, reef, planted, and specialty aquariums. Our designs range from small desktop aquariums to large custom installations for homes and businesses. Each aquarium is tailored to meet the specific needs and preferences of our clients.', 'aqualuxe' ),
                    'category' => 'general',
                ),
                array(
                    'question' => __( 'How do I choose the right aquarium for my space?', 'aqualuxe' ),
                    'answer' => __( 'Choosing the right aquarium depends on several factors including available space, budget, maintenance commitment, and aesthetic preferences. We recommend scheduling a consultation with our experts who can assess your space and requirements to recommend the best options. Factors to consider include the size of the aquarium, type of ecosystem, equipment needs, and long-term maintenance.', 'aqualuxe' ),
                    'category' => 'general',
                ),
                array(
                    'question' => __( 'Do you offer financing options?', 'aqualuxe' ),
                    'answer' => __( 'Yes, we offer flexible financing options for larger aquarium installations. Our financing plans include 0% interest for qualified customers, extended payment terms, and lease-to-own options for businesses. Please contact our sales team for detailed information about our current financing programs and to discuss which option might be best for your situation.', 'aqualuxe' ),
                    'category' => 'general',
                ),
                
                // Products
                array(
                    'question' => __( 'What brands of equipment do you carry?', 'aqualuxe' ),
                    'answer' => __( 'We carry premium equipment from trusted brands including Eheim, Fluval, ADA, Red Sea, Neptune Systems, EcoTech Marine, Kessil, and many more. We select our product offerings based on reliability, performance, and value to ensure our customers receive the best possible equipment for their aquatic systems.', 'aqualuxe' ),
                    'category' => 'products',
                ),
                array(
                    'question' => __( 'Do you sell live fish and plants?', 'aqualuxe' ),
                    'answer' => __( 'Yes, we offer a carefully curated selection of healthy fish, corals, invertebrates, and aquatic plants. Our livestock is sourced from reputable suppliers and undergoes a quarantine period before being made available for sale. We prioritize ethically sourced specimens and support sustainable aquaculture practices whenever possible.', 'aqualuxe' ),
                    'category' => 'products',
                ),
                array(
                    'question' => __( 'What is your warranty policy on products?', 'aqualuxe' ),
                    'answer' => __( 'Most equipment we sell comes with the manufacturer\'s warranty, typically ranging from 1-3 years depending on the product. Additionally, we offer an extended warranty program that provides coverage beyond the manufacturer\'s warranty period. For custom installations, we provide a comprehensive warranty that covers both equipment and workmanship. Detailed warranty information is provided with each purchase.', 'aqualuxe' ),
                    'category' => 'products',
                ),
                
                // Services
                array(
                    'question' => __( 'What maintenance services do you provide?', 'aqualuxe' ),
                    'answer' => __( 'Our maintenance services include regular cleaning, water testing and adjustments, equipment checks and maintenance, algae control, livestock health assessment, and aquascaping touch-ups. We offer weekly, bi-weekly, and monthly maintenance plans tailored to your specific aquarium needs. Our technicians are professionally trained and follow strict protocols to ensure the health and beauty of your aquatic system.', 'aqualuxe' ),
                    'category' => 'services',
                ),
                array(
                    'question' => __( 'How long does a custom aquarium installation take?', 'aqualuxe' ),
                    'answer' => __( 'The timeline for a custom aquarium installation varies depending on the complexity and size of the project. Typically, the process includes design (1-2 weeks), manufacturing (2-6 weeks), and installation (1-3 days). After installation, the cycling process takes an additional 4-6 weeks before the aquarium is ready for full stocking. Throughout the project, we provide regular updates and detailed timelines specific to your installation.', 'aqualuxe' ),
                    'category' => 'services',
                ),
                array(
                    'question' => __( 'Do you offer aquarium relocation services?', 'aqualuxe' ),
                    'answer' => __( 'Yes, we provide professional aquarium relocation services for both residential and commercial clients. Our relocation process includes careful planning, safe transport of livestock and equipment, proper setup at the new location, and system stabilization. We take every precaution to minimize stress on aquatic life and ensure your aquarium is set up correctly in its new location.', 'aqualuxe' ),
                    'category' => 'services',
                ),
                
                // Maintenance
                array(
                    'question' => __( 'How often should my aquarium be maintained?', 'aqualuxe' ),
                    'answer' => __( 'Maintenance frequency depends on the type and size of your aquarium, bioload, and filtration system. Generally, freshwater aquariums benefit from maintenance every 2-4 weeks, while reef and saltwater systems often require attention every 1-2 weeks. Our maintenance plans are customized based on your specific setup to ensure optimal water quality and system health.', 'aqualuxe' ),
                    'category' => 'maintenance',
                ),
                array(
                    'question' => __( 'What should I do if my fish appear sick?', 'aqualuxe' ),
                    'answer' => __( 'If you notice signs of illness in your fish (unusual behavior, loss of appetite, visible spots or growths, labored breathing, etc.), contact us immediately. In the meantime, test your water parameters and make note of any recent changes to the aquarium. Avoid adding medications without proper diagnosis as this can sometimes worsen the situation. Our aquatic health specialists can provide guidance over the phone and schedule an emergency service visit if necessary.', 'aqualuxe' ),
                    'category' => 'maintenance',
                ),
                array(
                    'question' => __( 'How do I maintain proper water quality?', 'aqualuxe' ),
                    'answer' => __( 'Maintaining proper water quality involves regular water changes (typically 10-25% every 2-4 weeks), testing key parameters (ammonia, nitrite, nitrate, pH, etc.), ensuring adequate filtration, avoiding overfeeding, and maintaining appropriate stocking levels. We recommend using quality test kits and keeping a log of your results. Our maintenance services include comprehensive water quality management, and we offer water testing kits and detailed guides for DIY maintenance.', 'aqualuxe' ),
                    'category' => 'maintenance',
                ),
                
                // Shipping & Returns
                array(
                    'question' => __( 'Do you ship products nationally?', 'aqualuxe' ),
                    'answer' => __( 'Yes, we ship products throughout the continental United States. We use reliable shipping partners and specialized packaging to ensure products arrive safely. Live organisms are shipped with temperature control packs and oxygen when necessary. Shipping rates are calculated based on weight, dimensions, and destination. Express shipping options are available for time-sensitive orders.', 'aqualuxe' ),
                    'category' => 'shipping-returns',
                ),
                array(
                    'question' => __( 'What is your return policy?', 'aqualuxe' ),
                    'answer' => __( 'Non-livestock products in new condition can be returned within 30 days of purchase with original packaging and receipt. Custom orders and special orders are non-returnable. Livestock returns are evaluated on a case-by-case basis and generally require photographic evidence within 48 hours of receipt. Store credit or exchanges are offered for most approved returns, while refunds to the original payment method are available for defective products.', 'aqualuxe' ),
                    'category' => 'shipping-returns',
                ),
                array(
                    'question' => __( 'How do you ensure safe shipping of live organisms?', 'aqualuxe' ),
                    'answer' => __( 'We take extensive precautions when shipping live organisms. Each shipment includes proper insulation, heat or cold packs as needed for the season, oxygen bags for fish, moisture retention for plants and corals, and secure packaging to prevent movement during transit. We only ship when weather conditions are appropriate and use expedited shipping methods. All livestock is acclimated and observed before shipping to ensure they are healthy and travel-ready.', 'aqualuxe' ),
                    'category' => 'shipping-returns',
                ),
            );
        }
        
        if ( ! empty( $faqs ) && is_array( $faqs ) ) :
        ?>
        <div class="faq-accordion">
            <?php foreach ( $faqs as $index => $faq ) : 
                $category = isset( $faq['category'] ) ? $faq['category'] : 'general';
            ?>
                <div class="faq-item" data-category="<?php echo esc_attr( $category ); ?>">
                    <div class="faq-question" id="faq-question-<?php echo esc_attr( $index ); ?>" aria-expanded="false" aria-controls="faq-answer-<?php echo esc_attr( $index ); ?>">
                        <h3><?php echo esc_html( $faq['question'] ); ?></h3>
                        <span class="faq-toggle">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" class="icon-plus">
                                <path fill-rule="evenodd" d="M12 3.75a.75.75 0 01.75.75v6.75h6.75a.75.75 0 010 1.5h-6.75v6.75a.75.75 0 01-1.5 0v-6.75H4.5a.75.75 0 010-1.5h6.75V4.5a.75.75 0 01.75-.75z" clip-rule="evenodd" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24" class="icon-minus hidden">
                                <path fill-rule="evenodd" d="M3.75 12a.75.75 0 01.75-.75h15a.75.75 0 010 1.5h-15a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    </div>
                    <div class="faq-answer hidden" id="faq-answer-<?php echo esc_attr( $index ); ?>" aria-labelledby="faq-question-<?php echo esc_attr( $index ); ?>">
                        <?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="no-results-message hidden">
            <p><?php esc_html_e( 'No FAQs found matching your search. Please try different keywords or browse all categories.', 'aqualuxe' ); ?></p>
        </div>

        <?php
        // Call to action
        $cta_title = get_post_meta( get_the_ID(), 'cta_title', true );
        $cta_text = get_post_meta( get_the_ID(), 'cta_text', true );
        $cta_button_text = get_post_meta( get_the_ID(), 'cta_button_text', true );
        $cta_button_url = get_post_meta( get_the_ID(), 'cta_button_url', true );
        
        if ( empty( $cta_title ) ) {
            $cta_title = __( 'Still Have Questions?', 'aqualuxe' );
        }
        
        if ( empty( $cta_text ) ) {
            $cta_text = __( 'Our team is ready to assist you with any questions or concerns you may have.', 'aqualuxe' );
        }
        
        if ( empty( $cta_button_text ) ) {
            $cta_button_text = __( 'Contact Us', 'aqualuxe' );
        }
        
        if ( empty( $cta_button_url ) ) {
            $cta_button_url = home_url( '/contact' );
        }
        ?>
        <div class="faq-cta">
            <div class="cta-content">
                <h2 class="cta-title"><?php echo esc_html( $cta_title ); ?></h2>
                <p class="cta-text"><?php echo esc_html( $cta_text ); ?></p>
                <a href="<?php echo esc_url( $cta_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $cta_button_text ); ?></a>
            </div>
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // FAQ accordion functionality
    const faqQuestions = document.querySelectorAll('.faq-question');
    
    faqQuestions.forEach(function(question) {
        question.addEventListener('click', function() {
            const answer = document.getElementById(this.getAttribute('aria-controls'));
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            const iconPlus = this.querySelector('.icon-plus');
            const iconMinus = this.querySelector('.icon-minus');
            
            // Toggle current FAQ item
            this.setAttribute('aria-expanded', !isExpanded);
            answer.classList.toggle('hidden');
            iconPlus.classList.toggle('hidden');
            iconMinus.classList.toggle('hidden');
        });
    });
    
    // Category filtering
    const categoryFilters = document.querySelectorAll('.category-filter');
    const faqItems = document.querySelectorAll('.faq-item');
    
    categoryFilters.forEach(function(filter) {
        filter.addEventListener('click', function() {
            const category = this.getAttribute('data-category');
            
            // Update active filter
            categoryFilters.forEach(function(btn) {
                btn.classList.remove('active');
            });
            this.classList.add('active');
            
            // Filter FAQ items
            faqItems.forEach(function(item) {
                if (category === 'all' || item.getAttribute('data-category') === category) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
            
            // Check if any items are visible
            checkNoResults();
        });
    });
    
    // Search functionality
    const searchInput = document.getElementById('faq-search-input');
    const noResultsMessage = document.querySelector('.no-results-message');
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase().trim();
            
            if (searchTerm.length > 2) {
                // Reset category filters
                categoryFilters.forEach(function(btn) {
                    btn.classList.remove('active');
                });
                document.querySelector('[data-category="all"]').classList.add('active');
                
                // Filter by search term
                let matchFound = false;
                
                faqItems.forEach(function(item) {
                    const question = item.querySelector('h3').textContent.toLowerCase();
                    const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
                    
                    if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                        item.style.display = '';
                        matchFound = true;
                        
                        // Highlight search term (optional)
                        // This is a simple implementation; a more robust solution would use proper DOM manipulation
                    } else {
                        item.style.display = 'none';
                    }
                });
                
                // Show/hide no results message
                if (matchFound) {
                    noResultsMessage.classList.add('hidden');
                } else {
                    noResultsMessage.classList.remove('hidden');
                }
            } else if (searchTerm.length === 0) {
                // Reset to show all
                faqItems.forEach(function(item) {
                    item.style.display = '';
                });
                noResultsMessage.classList.add('hidden');
            }
        });
        
        // Handle search form submission
        const searchForm = document.querySelector('.faq-search-form');
        if (searchForm) {
            searchForm.addEventListener('submit', function(e) {
                e.preventDefault();
                // The search is already handled by the input event
            });
        }
    }
    
    // Helper function to check if any results are visible
    function checkNoResults() {
        let visibleItems = 0;
        
        faqItems.forEach(function(item) {
            if (item.style.display !== 'none') {
                visibleItems++;
            }
        });
        
        if (visibleItems === 0) {
            noResultsMessage.classList.remove('hidden');
        } else {
            noResultsMessage.classList.add('hidden');
        }
    }
});
</script>

<?php
get_footer();