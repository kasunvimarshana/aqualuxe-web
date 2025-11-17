<?php
/**
 * Template part for displaying author biography
 *
 * @package AquaLuxe
 */

?>

<div class="author-bio my-8 p-6 bg-gray-50 dark:bg-gray-800 rounded-lg">
    <div class="flex flex-col md:flex-row items-center md:items-start gap-6">
        <div class="author-avatar shrink-0">
            <?php
            $author_bio_avatar_size = apply_filters( 'aqualuxe_author_bio_avatar_size', 96 );
            echo get_avatar( get_the_author_meta( 'user_email' ), $author_bio_avatar_size, '', get_the_author(), array(
                'class' => 'rounded-full',
            ) );
            ?>
        </div>

        <div class="author-content text-center md:text-left">
            <h3 class="author-title text-lg font-bold mb-2">
                <?php
                printf(
                    /* translators: %s: Author name */
                    esc_html__( 'About %s', 'aqualuxe' ),
                    get_the_author()
                );
                ?>
            </h3>

            <div class="author-description prose dark:prose-invert max-w-none">
                <?php the_author_meta( 'description' ); ?>
            </div>

            <div class="author-links mt-4">
                <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="inline-flex items-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    <?php
                    printf(
                        /* translators: %s: Author name */
                        esc_html__( 'View all posts by %s', 'aqualuxe' ),
                        get_the_author()
                    );
                    ?>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
                
                <?php
                // Display author social links if available
                $author_id = get_the_author_meta( 'ID' );
                $social_links = array(
                    'twitter' => get_the_author_meta( 'twitter', $author_id ),
                    'facebook' => get_the_author_meta( 'facebook', $author_id ),
                    'instagram' => get_the_author_meta( 'instagram', $author_id ),
                    'linkedin' => get_the_author_meta( 'linkedin', $author_id ),
                    'website' => get_the_author_meta( 'url', $author_id ),
                );
                
                $has_social = false;
                foreach ( $social_links as $link ) {
                    if ( ! empty( $link ) ) {
                        $has_social = true;
                        break;
                    }
                }
                
                if ( $has_social ) :
                ?>
                    <div class="author-social mt-3 flex flex-wrap gap-3 items-center">
                        <span class="text-sm text-gray-600 dark:text-gray-400"><?php esc_html_e( 'Follow:', 'aqualuxe' ); ?></span>
                        
                        <?php if ( ! empty( $social_links['twitter'] ) ) : ?>
                            <a href="<?php echo esc_url( $social_links['twitter'] ); ?>" class="text-gray-600 hover:text-blue-500 dark:text-gray-400 dark:hover:text-blue-400" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e( 'Twitter', 'aqualuxe' ); ?></span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $social_links['facebook'] ) ) : ?>
                            <a href="<?php echo esc_url( $social_links['facebook'] ); ?>" class="text-gray-600 hover:text-blue-600 dark:text-gray-400 dark:hover:text-blue-500" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e( 'Facebook', 'aqualuxe' ); ?></span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $social_links['instagram'] ) ) : ?>
                            <a href="<?php echo esc_url( $social_links['instagram'] ); ?>" class="text-gray-600 hover:text-pink-600 dark:text-gray-400 dark:hover:text-pink-500" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e( 'Instagram', 'aqualuxe' ); ?></span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z" clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $social_links['linkedin'] ) ) : ?>
                            <a href="<?php echo esc_url( $social_links['linkedin'] ); ?>" class="text-gray-600 hover:text-blue-700 dark:text-gray-400 dark:hover:text-blue-600" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e( 'LinkedIn', 'aqualuxe' ); ?></span>
                                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $social_links['website'] ) ) : ?>
                            <a href="<?php echo esc_url( $social_links['website'] ); ?>" class="text-gray-600 hover:text-green-600 dark:text-gray-400 dark:hover:text-green-500" target="_blank" rel="noopener noreferrer">
                                <span class="sr-only"><?php esc_html_e( 'Website', 'aqualuxe' ); ?></span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>