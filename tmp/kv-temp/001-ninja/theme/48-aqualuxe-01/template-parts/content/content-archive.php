<?php
/**
 * Template part for displaying posts in an archive
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('archive-post bg-white dark:bg-dark-light rounded-lg shadow-soft overflow-hidden mb-8 transition-transform duration-300 hover:-translate-y-1'); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="post-thumbnail">
            <a href="<?php the_permalink(); ?>" class="block overflow-hidden">
                <?php the_post_thumbnail('aqualuxe-blog', array('class' => 'w-full h-auto transition-transform duration-500 hover:scale-105')); ?>
            </a>
        </div>
    <?php endif; ?>
    
    <div class="post-content p-6">
        <header class="post-header mb-4">
            <?php
            // Display categories
            $categories = get_the_category();
            if ( ! empty( $categories ) ) :
            ?>
                <div class="post-categories mb-2">
                    <?php
                    $category = $categories[0];
                    echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="inline-block px-3 py-1 text-xs font-medium bg-primary text-white rounded-full hover:bg-primary-dark transition-colors">' . esc_html( $category->name ) . '</a>';
                    ?>
                </div>
            <?php endif; ?>
            
            <h2 class="post-title text-xl md:text-2xl font-serif font-bold mb-2">
                <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors"><?php the_title(); ?></a>
            </h2>
            
            <div class="post-meta text-sm text-gray-600 dark:text-gray-400">
                <span class="post-date">
                    <i class="fas fa-calendar-alt mr-1"></i>
                    <?php echo get_the_date(); ?>
                </span>
                
                <?php if ( ! post_password_required() && comments_open() ) : ?>
                    <span class="post-comments ml-4">
                        <i class="fas fa-comment mr-1"></i>
                        <?php
                        comments_popup_link(
                            esc_html__( 'No Comments', 'aqualuxe' ),
                            esc_html__( '1 Comment', 'aqualuxe' ),
                            esc_html__( '% Comments', 'aqualuxe' )
                        );
                        ?>
                    </span>
                <?php endif; ?>
            </div>
        </header>
        
        <div class="post-excerpt prose dark:prose-invert max-w-none mb-4">
            <?php the_excerpt(); ?>
        </div>
        
        <div class="post-footer">
            <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary hover:text-primary-dark font-medium transition-colors">
                <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>
</article>