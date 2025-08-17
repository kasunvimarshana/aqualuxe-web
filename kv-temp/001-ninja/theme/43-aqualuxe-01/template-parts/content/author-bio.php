<?php
/**
 * Template part for displaying author bio
 *
 * @package AquaLuxe
 */

?>

<div class="author-bio mt-8 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
    <div class="flex flex-col md:flex-row items-center md:items-start">
        <div class="author-avatar mb-4 md:mb-0 md:mr-6">
            <?php echo get_avatar( get_the_author_meta( 'ID' ), 96, '', get_the_author(), array( 'class' => 'rounded-full' ) ); ?>
        </div>
        
        <div class="author-content">
            <h3 class="author-name text-xl font-bold mb-2">
                <?php
                printf(
                    /* translators: %s: Author name */
                    esc_html__( 'About %s', 'aqualuxe' ),
                    esc_html( get_the_author() )
                );
                ?>
            </h3>
            
            <div class="author-description prose prose-sm">
                <?php echo wp_kses_post( wpautop( get_the_author_meta( 'description' ) ) ); ?>
            </div>
            
            <div class="author-links mt-4">
                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="text-primary hover:text-primary-dark font-medium">
                    <?php
                    printf(
                        /* translators: %s: Author name */
                        esc_html__( 'View all posts by %s', 'aqualuxe' ),
                        esc_html( get_the_author() )
                    );
                    ?>
                </a>
                
                <?php
                // Author social links
                $author_website = get_the_author_meta( 'url' );
                $author_twitter = get_the_author_meta( 'twitter' );
                $author_facebook = get_the_author_meta( 'facebook' );
                $author_instagram = get_the_author_meta( 'instagram' );
                $author_linkedin = get_the_author_meta( 'linkedin' );
                
                if ( $author_website || $author_twitter || $author_facebook || $author_instagram || $author_linkedin ) :
                    ?>
                    <div class="author-social mt-3 flex">
                        <?php if ( $author_website ) : ?>
                            <a href="<?php echo esc_url( $author_website ); ?>" class="mr-3 text-gray-600 hover:text-primary dark:text-gray-400" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e( 'Website', 'aqualuxe' ); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( $author_twitter ) : ?>
                            <a href="<?php echo esc_url( 'https://twitter.com/' . $author_twitter ); ?>" class="mr-3 text-gray-600 hover:text-primary dark:text-gray-400" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( $author_facebook ) : ?>
                            <a href="<?php echo esc_url( $author_facebook ); ?>" class="mr-3 text-gray-600 hover:text-primary dark:text-gray-400" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( $author_instagram ) : ?>
                            <a href="<?php echo esc_url( $author_instagram ); ?>" class="mr-3 text-gray-600 hover:text-primary dark:text-gray-400" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( $author_linkedin ) : ?>
                            <a href="<?php echo esc_url( $author_linkedin ); ?>" class="mr-3 text-gray-600 hover:text-primary dark:text-gray-400" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>