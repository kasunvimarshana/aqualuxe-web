<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class('mb-8 pb-8 border-b border-gray-200 dark:border-gray-700 last:border-0'); ?>>
    <header class="entry-header mb-4">
        <?php the_title( sprintf( '<h2 class="entry-title text-xl font-bold"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>

        <?php if ( 'post' === get_post_type() ) : ?>
            <div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mt-2">
                <?php
                aqualuxe_posted_on();
                aqualuxe_posted_by();
                ?>
            </div><!-- .entry-meta -->
        <?php endif; ?>
    </header><!-- .entry-header -->

    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail mb-4">
            <a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
                <?php the_post_thumbnail( 'medium', array( 'class' => 'rounded w-full h-auto' ) ); ?>
            </a>
        </div>
    <?php endif; ?>

    <div class="entry-summary prose max-w-none">
        <?php the_excerpt(); ?>
    </div><!-- .entry-summary -->

    <footer class="entry-footer mt-4">
        <div class="flex items-center">
            <div class="post-type-badge text-xs px-2 py-1 rounded bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 mr-2">
                <?php
                $post_type = get_post_type();
                $post_type_obj = get_post_type_object( $post_type );
                echo esc_html( $post_type_obj->labels->singular_name );
                ?>
            </div>
            
            <a href="<?php the_permalink(); ?>" class="text-primary hover:text-primary-dark text-sm font-medium flex items-center">
                <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                </svg>
            </a>
        </div>
    </footer><!-- .entry-footer -->
</article><!-- #post-<?php the_ID(); ?> -->