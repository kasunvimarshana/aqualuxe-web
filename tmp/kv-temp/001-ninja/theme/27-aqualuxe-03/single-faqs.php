<?php
/**
 * The template for displaying single FAQ
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <?php
        while ( have_posts() ) :
            the_post();
            
            // Get related FAQs
            $related_faqs = array();
            
            // Get FAQ categories
            $faq_categories = get_the_terms( get_the_ID(), 'faq_category' );
            $category_ids = array();
            
            if ( $faq_categories && ! is_wp_error( $faq_categories ) ) {
                foreach ( $faq_categories as $category ) {
                    $category_ids[] = $category->term_id;
                }
            }
            
            // Get FAQ tags
            $faq_tags = get_the_terms( get_the_ID(), 'faq_tag' );
            $tag_ids = array();
            
            if ( $faq_tags && ! is_wp_error( $faq_tags ) ) {
                foreach ( $faq_tags as $tag ) {
                    $tag_ids[] = $tag->term_id;
                }
            }
            
            // Query related FAQs by category and tag
            if ( ! empty( $category_ids ) || ! empty( $tag_ids ) ) {
                $tax_query = array( 'relation' => 'OR' );
                
                if ( ! empty( $category_ids ) ) {
                    $tax_query[] = array(
                        'taxonomy' => 'faq_category',
                        'field' => 'term_id',
                        'terms' => $category_ids,
                    );
                }
                
                if ( ! empty( $tag_ids ) ) {
                    $tax_query[] = array(
                        'taxonomy' => 'faq_tag',
                        'field' => 'term_id',
                        'terms' => $tag_ids,
                    );
                }
                
                $related_args = array(
                    'post_type' => 'faqs',
                    'posts_per_page' => 5,
                    'post__not_in' => array( get_the_ID() ),
                    'tax_query' => $tax_query,
                );
                
                $related_query = new WP_Query( $related_args );
                
                if ( $related_query->have_posts() ) {
                    while ( $related_query->have_posts() ) {
                        $related_query->the_post();
                        $related_faqs[] = array(
                            'id' => get_the_ID(),
                            'title' => get_the_title(),
                            'permalink' => get_permalink(),
                        );
                    }
                    wp_reset_postdata();
                }
            }
            
            // Reset to current post
            setup_postdata( $post );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="faq-layout max-w-4xl mx-auto">
                    <div class="faq-breadcrumbs mb-6 text-sm">
                        <a href="<?php echo esc_url( home_url() ); ?>" class="text-dark-500 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400">
                            <?php esc_html_e( 'Home', 'aqualuxe' ); ?>
                        </a>
                        <span class="mx-2">›</span>
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'faqs' ) ); ?>" class="text-dark-500 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400">
                            <?php esc_html_e( 'FAQs', 'aqualuxe' ); ?>
                        </a>
                        
                        <?php if ( $faq_categories && ! is_wp_error( $faq_categories ) ) : ?>
                            <span class="mx-2">›</span>
                            <a href="<?php echo esc_url( get_term_link( $faq_categories[0] ) ); ?>" class="text-dark-500 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400">
                                <?php echo esc_html( $faq_categories[0]->name ); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div class="faq-card bg-white dark:bg-dark-800 rounded-lg shadow-medium overflow-hidden">
                        <header class="faq-header p-8 bg-primary-50 dark:bg-primary-900/30 border-b border-primary-100 dark:border-primary-800">
                            <h1 class="faq-title text-2xl md:text-3xl font-bold mb-4"><?php the_title(); ?></h1>
                            
                            <?php if ( $faq_categories && ! is_wp_error( $faq_categories ) ) : ?>
                                <div class="faq-categories flex flex-wrap gap-2 mb-4">
                                    <?php foreach ( $faq_categories as $category ) : ?>
                                        <a href="<?php echo esc_url( get_term_link( $category ) ); ?>" class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">
                                            <?php echo esc_html( $category->name ); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="faq-meta text-sm text-dark-500 dark:text-dark-400">
                                <span class="faq-date">
                                    <?php esc_html_e( 'Last updated:', 'aqualuxe' ); ?> 
                                    <?php echo get_the_modified_date(); ?>
                                </span>
                            </div>
                        </header>
                        
                        <div class="faq-content p-8">
                            <div class="faq-answer prose max-w-none dark:prose-invert mb-8">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php if ( $faq_tags && ! is_wp_error( $faq_tags ) ) : ?>
                                <div class="faq-tags flex flex-wrap gap-2 mb-8 pt-4 border-t border-dark-200 dark:border-dark-700">
                                    <span class="text-dark-500 dark:text-dark-400 mr-2"><?php esc_html_e( 'Tags:', 'aqualuxe' ); ?></span>
                                    <?php foreach ( $faq_tags as $tag ) : ?>
                                        <a href="<?php echo esc_url( get_term_link( $tag ) ); ?>" class="inline-block px-2 py-1 bg-dark-100 text-dark-700 text-xs rounded-full hover:bg-dark-200 transition-colors dark:bg-dark-700 dark:text-dark-300 dark:hover:bg-dark-600">
                                            <?php echo esc_html( $tag->name ); ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="faq-helpful mb-8">
                                <h3 class="text-lg font-medium mb-3"><?php esc_html_e( 'Was this helpful?', 'aqualuxe' ); ?></h3>
                                
                                <div class="flex space-x-4">
                                    <button class="faq-helpful-yes btn-outline text-sm px-4 py-2" data-faq-id="<?php echo esc_attr( get_the_ID() ); ?>" data-helpful="yes">
                                        <svg class="w-5 h-5 mr-2 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M2 10.5a1.5 1.5 0 113 0v6a1.5 1.5 0 01-3 0v-6zM6 10.333v5.43a2 2 0 001.106 1.79l.05.025A4 4 0 008.943 18h5.416a2 2 0 001.962-1.608l1.2-6A2 2 0 0015.56 8H12V4a2 2 0 00-2-2 1 1 0 00-1 1v.667a4 4 0 01-.8 2.4L6.8 7.933a4 4 0 00-.8 2.4z"></path>
                                        </svg>
                                        <?php esc_html_e( 'Yes', 'aqualuxe' ); ?>
                                    </button>
                                    
                                    <button class="faq-helpful-no btn-outline text-sm px-4 py-2" data-faq-id="<?php echo esc_attr( get_the_ID() ); ?>" data-helpful="no">
                                        <svg class="w-5 h-5 mr-2 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M18 9.5a1.5 1.5 0 11-3 0v-6a1.5 1.5 0 013 0v6zM14 9.667v-5.43a2 2 0 00-1.105-1.79l-.05-.025A4 4 0 0011.055 2H5.64a2 2 0 00-1.962 1.608l-1.2 6A2 2 0 004.44 12H8v4a2 2 0 002 2 1 1 0 001-1v-.667a4 4 0 01.8-2.4l1.4-1.866a4 4 0 00.8-2.4z"></path>
                                        </svg>
                                        <?php esc_html_e( 'No', 'aqualuxe' ); ?>
                                    </button>
                                </div>
                                
                                <div class="faq-helpful-message mt-3 text-sm hidden"></div>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const yesButton = document.querySelector('.faq-helpful-yes');
                                        const noButton = document.querySelector('.faq-helpful-no');
                                        const messageDiv = document.querySelector('.faq-helpful-message');
                                        
                                        if (yesButton && noButton && messageDiv) {
                                            // Check if user has already voted
                                            const faqId = yesButton.dataset.faqId;
                                            const hasVoted = localStorage.getItem('faq_voted_' + faqId);
                                            
                                            if (hasVoted) {
                                                yesButton.disabled = true;
                                                noButton.disabled = true;
                                                messageDiv.textContent = '<?php esc_html_e( 'Thank you for your feedback!', 'aqualuxe' ); ?>';
                                                messageDiv.classList.remove('hidden');
                                            }
                                            
                                            // Handle yes button click
                                            yesButton.addEventListener('click', function() {
                                                handleVote('yes');
                                            });
                                            
                                            // Handle no button click
                                            noButton.addEventListener('click', function() {
                                                handleVote('no');
                                            });
                                            
                                            function handleVote(vote) {
                                                // Disable buttons
                                                yesButton.disabled = true;
                                                noButton.disabled = true;
                                                
                                                // Show thank you message
                                                messageDiv.textContent = '<?php esc_html_e( 'Thank you for your feedback!', 'aqualuxe' ); ?>';
                                                messageDiv.classList.remove('hidden');
                                                
                                                // Save vote in localStorage
                                                localStorage.setItem('faq_voted_' + faqId, vote);
                                                
                                                // Send vote to server (if you have AJAX endpoint)
                                                // This is a placeholder for actual AJAX implementation
                                                /*
                                                fetch('/wp-admin/admin-ajax.php', {
                                                    method: 'POST',
                                                    headers: {
                                                        'Content-Type': 'application/x-www-form-urlencoded',
                                                    },
                                                    body: new URLSearchParams({
                                                        action: 'faq_helpful_vote',
                                                        faq_id: faqId,
                                                        vote: vote,
                                                        nonce: '<?php echo wp_create_nonce( 'faq_helpful_nonce' ); ?>'
                                                    })
                                                })
                                                .then(response => response.json())
                                                .then(data => {
                                                    console.log('Vote recorded:', data);
                                                })
                                                .catch(error => {
                                                    console.error('Error recording vote:', error);
                                                });
                                                */
                                            }
                                        }
                                    });
                                </script>
                            </div>
                            
                            <div class="faq-share mb-8">
                                <h3 class="text-lg font-medium mb-3"><?php esc_html_e( 'Share This FAQ', 'aqualuxe' ); ?></h3>
                                
                                <div class="flex space-x-4">
                                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode( get_permalink() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Facebook', 'aqualuxe' ); ?>">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/>
                                        </svg>
                                    </a>
                                    
                                    <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode( get_permalink() ); ?>&text=<?php echo urlencode( get_the_title() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on Twitter', 'aqualuxe' ); ?>">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/>
                                        </svg>
                                    </a>
                                    
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode( get_permalink() ); ?>&title=<?php echo urlencode( get_the_title() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'aqualuxe' ); ?>">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                        </svg>
                                    </a>
                                    
                                    <a href="mailto:?subject=<?php echo urlencode( get_the_title() ); ?>&body=<?php echo urlencode( get_permalink() ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" aria-label="<?php esc_attr_e( 'Share via Email', 'aqualuxe' ); ?>">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            
                            <?php if ( ! empty( $related_faqs ) ) : ?>
                                <div class="related-faqs pt-6 border-t border-dark-200 dark:border-dark-700">
                                    <h3 class="text-lg font-medium mb-4"><?php esc_html_e( 'Related FAQs', 'aqualuxe' ); ?></h3>
                                    
                                    <ul class="space-y-3">
                                        <?php foreach ( $related_faqs as $related_faq ) : ?>
                                            <li>
                                                <a href="<?php echo esc_url( $related_faq['permalink'] ); ?>" class="flex items-center text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span><?php echo esc_html( $related_faq['title'] ); ?></span>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="faq-footer p-8 bg-dark-50 dark:bg-dark-800/50 border-t border-dark-200 dark:border-dark-700">
                            <div class="text-center">
                                <h3 class="text-lg font-medium mb-2"><?php esc_html_e( 'Still Have Questions?', 'aqualuxe' ); ?></h3>
                                <p class="mb-4"><?php esc_html_e( 'If you couldn\'t find the answer to your question, please contact our support team.', 'aqualuxe' ); ?></p>
                                <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'contact' ) ) ); ?>" class="btn-primary"><?php esc_html_e( 'Contact Us', 'aqualuxe' ); ?></a>
                            </div>
                        </div>
                    </div>
                    
                    <div class="faq-navigation mt-8 flex justify-between items-center">
                        <a href="<?php echo esc_url( get_post_type_archive_link( 'faqs' ) ); ?>" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                            </svg>
                            <?php esc_html_e( 'Back to FAQs', 'aqualuxe' ); ?>
                        </a>
                        
                        <?php if ( $faq_categories && ! is_wp_error( $faq_categories ) ) : ?>
                            <a href="<?php echo esc_url( get_term_link( $faq_categories[0] ) ); ?>" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                <?php 
                                /* translators: %s: category name */
                                printf( esc_html__( 'More in %s', 'aqualuxe' ), esc_html( $faq_categories[0]->name ) ); 
                                ?>
                                <svg class="w-5 h-5 ml-2 inline-block" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </article>

        <?php endwhile; // End of the loop. ?>
    </div>
</main><!-- #main -->

<?php
get_footer();