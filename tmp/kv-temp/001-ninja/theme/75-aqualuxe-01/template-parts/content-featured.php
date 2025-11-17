<?php
/**
 * Template part for displaying featured posts
 *
 * @package AquaLuxe
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'featured-post relative overflow-hidden bg-gradient-to-br from-primary-50 to-secondary-50 dark:from-gray-900 dark:to-gray-800 rounded-xl mb-12' ); ?>>
    <?php if ( has_post_thumbnail() ) : ?>
        <div class="featured-image absolute inset-0">
            <?php
            the_post_thumbnail( 'full', array(
                'class' => 'w-full h-full object-cover',
                'loading' => 'eager',
                'alt' => get_the_title(),
            ) );
            ?>
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
        </div>
    <?php endif; ?>
    
    <div class="featured-content relative z-10 p-8 lg:p-12 min-h-[400px] lg:min-h-[500px] flex items-end">
        <div class="max-w-3xl">
            <!-- Featured Badge -->
            <div class="featured-badge inline-flex items-center px-3 py-1 bg-primary-500 text-white text-sm font-medium rounded-full mb-4">
                <i class="fas fa-star mr-2" aria-hidden="true"></i>
                <?php esc_html_e( 'Featured', 'aqualuxe' ); ?>
            </div>
            
            <!-- Post Meta -->
            <div class="post-meta mb-4">
                <div class="flex flex-wrap items-center gap-4 text-white/90 text-sm">
                    <span class="post-date">
                        <i class="fas fa-calendar mr-1" aria-hidden="true"></i>
                        <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
                            <?php echo esc_html( get_the_date() ); ?>
                        </time>
                    </span>
                    
                    <?php if ( has_category() ) : ?>
                    <span class="post-categories">
                        <i class="fas fa-folder mr-1" aria-hidden="true"></i>
                        <?php
                        $categories = get_the_category();
                        if ( ! empty( $categories ) ) {
                            echo '<a href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '" class="text-white/90 hover:text-white transition-colors">' . esc_html( $categories[0]->name ) . '</a>';
                        }
                        ?>
                    </span>
                    <?php endif; ?>
                    
                    <span class="post-author">
                        <i class="fas fa-user mr-1" aria-hidden="true"></i>
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" class="text-white/90 hover:text-white transition-colors">
                            <?php echo esc_html( get_the_author() ); ?>
                        </a>
                    </span>
                    
                    <span class="reading-time">
                        <i class="fas fa-clock mr-1" aria-hidden="true"></i>
                        <?php echo aqualuxe_reading_time(); ?> <?php esc_html_e( 'min read', 'aqualuxe' ); ?>
                    </span>
                </div>
            </div>
            
            <!-- Post Title -->
            <h2 class="featured-title text-3xl lg:text-5xl font-bold text-white mb-4 leading-tight">
                <a href="<?php the_permalink(); ?>" class="text-white hover:text-primary-200 transition-colors">
                    <?php the_title(); ?>
                </a>
            </h2>
            
            <!-- Post Excerpt -->
            <div class="featured-excerpt text-lg text-white/90 mb-6 line-clamp-3">
                <?php echo wp_trim_words( get_the_excerpt(), 30, '...' ); ?>
            </div>
            
            <!-- Action Buttons -->
            <div class="featured-actions flex flex-col sm:flex-row gap-4">
                <a href="<?php the_permalink(); ?>" class="btn btn-white btn-lg">
                    <?php esc_html_e( 'Read Full Article', 'aqualuxe' ); ?>
                    <i class="fas fa-arrow-right ml-2" aria-hidden="true"></i>
                </a>
                
                <div class="flex items-center space-x-3">
                    <!-- Share Buttons -->
                    <div class="share-buttons flex items-center space-x-2">
                        <?php aqualuxe_share_buttons( 'featured' ); ?>
                    </div>
                    
                    <!-- Comments Count -->
                    <?php if ( comments_open() || get_comments_number() ) : ?>
                    <a href="<?php comments_link(); ?>" class="text-white/90 hover:text-white transition-colors text-sm">
                        <i class="fas fa-comments mr-1" aria-hidden="true"></i>
                        <?php
                        printf(
                            /* translators: %s: Comment count */
                            _n( '%s Comment', '%s Comments', get_comments_number(), 'aqualuxe' ),
                            number_format_i18n( get_comments_number() )
                        );
                        ?>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Scroll Indicator -->
    <div class="scroll-indicator absolute bottom-4 left-1/2 transform -translate-x-1/2 text-white/70 animate-bounce">
        <i class="fas fa-chevron-down" aria-hidden="true"></i>
    </div>
</article>

<!-- Featured Post Schema -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "NewsArticle",
    "headline": "<?php echo esc_js( get_the_title() ); ?>",
    "description": "<?php echo esc_js( wp_trim_words( get_the_excerpt(), 20, '...' ) ); ?>",
    "image": [
        "<?php echo esc_url( get_the_post_thumbnail_url( get_the_ID(), 'large' ) ); ?>"
    ],
    "datePublished": "<?php echo esc_js( get_the_date( 'c' ) ); ?>",
    "dateModified": "<?php echo esc_js( get_the_modified_date( 'c' ) ); ?>",
    "author": {
        "@type": "Person",
        "name": "<?php echo esc_js( get_the_author() ); ?>",
        "url": "<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"
    },
    "publisher": {
        "@type": "Organization",
        "name": "<?php echo esc_js( get_bloginfo( 'name' ) ); ?>",
        "logo": {
            "@type": "ImageObject",
            "url": "<?php echo esc_url( wp_get_attachment_image_url( get_theme_mod( 'custom_logo' ), 'full' ) ); ?>"
        }
    },
    "mainEntityOfPage": {
        "@type": "WebPage",
        "@id": "<?php echo esc_url( get_permalink() ); ?>"
    }
}
</script>
