<?php
/**
 * Template part for displaying results in search pages
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden transition-shadow hover:shadow-lg' ); ?>>
    
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" class="block">
                <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-32 object-cover' ) ); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="p-4">
        <header class="entry-header mb-3">
            <?php the_title( sprintf( '<h3 class="entry-title text-lg font-semibold text-gray-900 dark:text-white mb-2 hover:text-primary-600 transition-colors"><a href="%s" rel="bookmark" class="no-underline">', esc_url( get_permalink() ) ), '</a></h3>' ); ?>
            
            <?php if ( 'post' === get_post_type() ) : ?>
                <div class="entry-meta text-xs text-gray-600 dark:text-gray-400 flex items-center gap-2 mb-2">
                    <span class="post-type bg-primary-100 dark:bg-primary-900 text-primary-600 dark:text-primary-400 px-2 py-1 rounded text-xs font-medium">
                        <?php echo esc_html( get_post_type_object( get_post_type() )->labels->singular_name ); ?>
                    </span>
                    <?php
                    aqualuxe_posted_on();
                    ?>
                </div>
            <?php endif; ?>
        </header><!-- .entry-header -->

        <div class="entry-summary text-gray-600 dark:text-gray-400 text-sm line-clamp-3">
            <?php the_excerpt(); ?>
        </div><!-- .entry-summary -->
        
        <div class="entry-footer mt-3">
            <a href="<?php the_permalink(); ?>" class="read-more-link inline-flex items-center text-primary-600 hover:text-primary-700 font-medium text-sm transition-colors">
                <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->