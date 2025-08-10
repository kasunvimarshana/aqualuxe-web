<?php
/**
 * The template for displaying FAQs archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <header class="page-header mb-12 text-center">
            <h1 class="page-title text-4xl md:text-5xl mb-4"><?php post_type_archive_title(); ?></h1>
            <div class="archive-description prose mx-auto">
                <?php the_archive_description(); ?>
            </div>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="faqs-search mb-8">
                <form role="search" method="get" class="search-form max-w-xl mx-auto" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="relative">
                        <input type="search" class="form-input w-full pl-12 pr-4 py-3 rounded-full" placeholder="<?php esc_attr_e( 'Search FAQs...', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                        <input type="hidden" name="post_type" value="faqs" />
                        <button type="submit" class="absolute left-0 top-0 h-full px-4 flex items-center justify-center text-dark-500 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </form>
            </div>

            <div class="faqs-filter mb-8">
                <form class="flex flex-wrap gap-4 justify-center" method="get">
                    <?php
                    // Get all FAQ categories
                    $faq_categories = get_terms( array(
                        'taxonomy' => 'faq_category',
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $faq_categories ) && ! is_wp_error( $faq_categories ) ) : ?>
                        <div class="filter-group">
                            <label for="faq_category" class="sr-only"><?php esc_html_e( 'Filter by Category', 'aqualuxe' ); ?></label>
                            <select name="faq_category" id="faq_category" class="form-input">
                                <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                                <?php foreach ( $faq_categories as $category ) : ?>
                                    <option value="<?php echo esc_attr( $category->slug ); ?>" <?php selected( isset( $_GET['faq_category'] ) ? sanitize_text_field( $_GET['faq_category'] ) : '', $category->slug ); ?>>
                                        <?php echo esc_html( $category->name ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn-primary"><?php esc_html_e( 'Filter', 'aqualuxe' ); ?></button>
                </form>
            </div>

            <?php
            // Group FAQs by category
            $current_category = '';
            $categories = array();
            $uncategorized = array();
            
            // First, collect all posts by category
            while ( have_posts() ) {
                the_post();
                
                $post_categories = get_the_terms( get_the_ID(), 'faq_category' );
                
                if ( $post_categories && ! is_wp_error( $post_categories ) ) {
                    foreach ( $post_categories as $category ) {
                        if ( ! isset( $categories[ $category->term_id ] ) ) {
                            $categories[ $category->term_id ] = array(
                                'name' => $category->name,
                                'slug' => $category->slug,
                                'posts' => array(),
                            );
                        }
                        
                        $categories[ $category->term_id ]['posts'][] = get_post();
                    }
                } else {
                    $uncategorized[] = get_post();
                }
            }
            
            // Reset the post data
            rewind_posts();
            
            // If we're filtering by category, show only that category
            if ( isset( $_GET['faq_category'] ) && ! empty( $_GET['faq_category'] ) ) {
                $filter_slug = sanitize_text_field( $_GET['faq_category'] );
                
                foreach ( $categories as $cat_id => $category ) {
                    if ( $category['slug'] !== $filter_slug ) {
                        unset( $categories[ $cat_id ] );
                    }
                }
                
                // Clear uncategorized if we're filtering
                $uncategorized = array();
            }
            
            // Display FAQs by category
            if ( ! empty( $categories ) || ! empty( $uncategorized ) ) : ?>
                <div class="faqs-accordion" id="faqs-accordion">
                    <?php
                    // Display categorized FAQs
                    foreach ( $categories as $category ) : ?>
                        <div class="faq-category mb-12">
                            <h2 class="text-2xl font-bold mb-6"><?php echo esc_html( $category['name'] ); ?></h2>
                            
                            <div class="faqs-list space-y-4">
                                <?php foreach ( $category['posts'] as $post ) : 
                                    setup_postdata( $post );
                                    ?>
                                    <div class="faq-item bg-white dark:bg-dark-800 rounded-lg shadow-soft overflow-hidden">
                                        <button class="faq-question w-full text-left p-6 flex justify-between items-center focus:outline-none" aria-expanded="false">
                                            <h3 class="text-lg font-medium pr-8"><?php the_title(); ?></h3>
                                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        
                                        <div class="faq-answer px-6 pb-6 hidden">
                                            <div class="prose max-w-none dark:prose-invert">
                                                <?php the_content(); ?>
                                            </div>
                                            
                                            <?php if ( has_term( '', 'faq_tag' ) ) : ?>
                                                <div class="faq-tags mt-4 flex flex-wrap gap-2">
                                                    <?php
                                                    $tags = get_the_terms( get_the_ID(), 'faq_tag' );
                                                    if ( $tags && ! is_wp_error( $tags ) ) {
                                                        foreach ( $tags as $tag ) {
                                                            echo '<a href="' . esc_url( get_term_link( $tag ) ) . '" class="inline-block px-2 py-1 bg-primary-100 text-primary-800 text-xs rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">';
                                                            echo esc_html( $tag->name );
                                                            echo '</a>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <?php
                    // Display uncategorized FAQs
                    if ( ! empty( $uncategorized ) ) : ?>
                        <div class="faq-category mb-12">
                            <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'General FAQs', 'aqualuxe' ); ?></h2>
                            
                            <div class="faqs-list space-y-4">
                                <?php foreach ( $uncategorized as $post ) : 
                                    setup_postdata( $post );
                                    ?>
                                    <div class="faq-item bg-white dark:bg-dark-800 rounded-lg shadow-soft overflow-hidden">
                                        <button class="faq-question w-full text-left p-6 flex justify-between items-center focus:outline-none" aria-expanded="false">
                                            <h3 class="text-lg font-medium pr-8"><?php the_title(); ?></h3>
                                            <svg class="w-6 h-6 text-primary-600 dark:text-primary-400 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                            </svg>
                                        </button>
                                        
                                        <div class="faq-answer px-6 pb-6 hidden">
                                            <div class="prose max-w-none dark:prose-invert">
                                                <?php the_content(); ?>
                                            </div>
                                            
                                            <?php if ( has_term( '', 'faq_tag' ) ) : ?>
                                                <div class="faq-tags mt-4 flex flex-wrap gap-2">
                                                    <?php
                                                    $tags = get_the_terms( get_the_ID(), 'faq_tag' );
                                                    if ( $tags && ! is_wp_error( $tags ) ) {
                                                        foreach ( $tags as $tag ) {
                                                            echo '<a href="' . esc_url( get_term_link( $tag ) ) . '" class="inline-block px-2 py-1 bg-primary-100 text-primary-800 text-xs rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">';
                                                            echo esc_html( $tag->name );
                                                            echo '</a>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const faqItems = document.querySelectorAll('.faq-question');
                        
                        faqItems.forEach(item => {
                            item.addEventListener('click', function() {
                                const answer = this.nextElementSibling;
                                const icon = this.querySelector('svg');
                                const expanded = this.getAttribute('aria-expanded') === 'true';
                                
                                // Toggle current item
                                this.setAttribute('aria-expanded', !expanded);
                                answer.classList.toggle('hidden');
                                icon.classList.toggle('rotate-180');
                                
                                // Get the URL hash from the current URL
                                const urlHash = window.location.hash;
                                
                                // If there's no hash in the URL, update it with the current FAQ's ID
                                if (!expanded && !urlHash) {
                                    const faqId = this.closest('.faq-item').id;
                                    if (faqId) {
                                        history.pushState(null, null, `#${faqId}`);
                                    }
                                }
                            });
                        });
                        
                        // Check if there's a hash in the URL and open the corresponding FAQ
                        const urlHash = window.location.hash;
                        if (urlHash) {
                            const targetFaq = document.querySelector(urlHash);
                            if (targetFaq) {
                                const faqQuestion = targetFaq.querySelector('.faq-question');
                                if (faqQuestion) {
                                    faqQuestion.click();
                                    
                                    // Scroll to the FAQ with a slight delay to ensure it's open
                                    setTimeout(() => {
                                        targetFaq.scrollIntoView({ behavior: 'smooth', block: 'start' });
                                    }, 100);
                                }
                            }
                        }
                    });
                </script>
            <?php else : ?>
                <div class="no-faqs text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-dark-300 dark:text-dark-600 mb-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                    </svg>
                    <h2 class="text-2xl font-bold mb-2"><?php esc_html_e( 'No FAQs Found', 'aqualuxe' ); ?></h2>
                    <p class="text-dark-500 dark:text-dark-400 mb-6"><?php esc_html_e( 'No FAQs were found matching your selection.', 'aqualuxe' ); ?></p>
                    <a href="<?php echo esc_url( get_post_type_archive_link( 'faqs' ) ); ?>" class="btn-primary"><?php esc_html_e( 'View All FAQs', 'aqualuxe' ); ?></a>
                </div>
            <?php endif; ?>

            <div class="faqs-contact mt-16 bg-primary-50 dark:bg-primary-900/30 rounded-lg p-8 text-center">
                <h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Still Have Questions?', 'aqualuxe' ); ?></h2>
                <p class="text-lg mb-6"><?php esc_html_e( 'If you couldn\'t find the answer to your question, please contact our support team.', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
            </div>

        <?php else : ?>
            <div class="no-faqs text-center py-12">
                <svg class="w-16 h-16 mx-auto text-dark-300 dark:text-dark-600 mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                </svg>
                <h2 class="text-2xl font-bold mb-2"><?php esc_html_e( 'No FAQs Found', 'aqualuxe' ); ?></h2>
                <p class="text-dark-500 dark:text-dark-400 mb-6"><?php esc_html_e( 'No FAQs have been created yet.', 'aqualuxe' ); ?></p>
                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</main><!-- #main -->

<?php
get_footer();