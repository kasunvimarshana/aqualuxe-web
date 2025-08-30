<?php
/**
 * Template part for displaying author bio
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$author_id = get_the_author_meta( 'ID' );
if ( ! $author_id ) {
    return;
}

$author_name = get_the_author_meta( 'display_name', $author_id );
$author_description = get_the_author_meta( 'description', $author_id );
$author_posts_url = get_author_posts_url( $author_id );
$author_avatar = get_avatar( $author_id, 96, '', $author_name, array( 'class' => 'rounded-full' ) );
$author_website = get_the_author_meta( 'user_url', $author_id );
?>

<div class="author-bio bg-white dark:bg-dark-800 rounded-lg shadow-sm p-6 mt-8">
    <div class="flex flex-col sm:flex-row items-center sm:items-start">
        <?php if ( $author_avatar ) : ?>
            <div class="author-avatar mb-4 sm:mb-0 sm:mr-6">
                <a href="<?php echo esc_url( $author_posts_url ); ?>" class="block">
                    <?php echo $author_avatar; ?>
                </a>
            </div>
        <?php endif; ?>
        
        <div class="author-content text-center sm:text-left">
            <h3 class="author-name text-xl font-serif font-bold text-dark-900 dark:text-white mb-2">
                <a href="<?php echo esc_url( $author_posts_url ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                    <?php echo esc_html( $author_name ); ?>
                </a>
            </h3>
            
            <?php if ( $author_description ) : ?>
                <div class="author-description prose dark:prose-dark mb-4">
                    <?php echo wpautop( $author_description ); ?>
                </div>
            <?php endif; ?>
            
            <div class="author-links flex flex-wrap justify-center sm:justify-start gap-4">
                <a href="<?php echo esc_url( $author_posts_url ); ?>" class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:underline">
                    <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                    </svg>
                    <?php
                    printf(
                        /* translators: %s: author name */
                        esc_html__( 'View all posts by %s', 'aqualuxe' ),
                        esc_html( $author_name )
                    );
                    ?>
                </a>
                
                <?php if ( $author_website ) : ?>
                    <a href="<?php echo esc_url( $author_website ); ?>" class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 hover:underline" target="_blank" rel="noopener noreferrer">
                        <svg class="w-4 h-4 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                        </svg>
                        <?php esc_html_e( 'Website', 'aqualuxe' ); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>