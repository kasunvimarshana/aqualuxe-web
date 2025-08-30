<?php
/**
 * Content template for archive pages
 *
 * @package AquaLuxe
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'post-archive-item' ); ?>>
    <div class="post-archive-card bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 group">
        
        <?php if ( has_post_thumbnail() ) : ?>
            <div class="post-thumbnail relative overflow-hidden">
                <a href="<?php the_permalink(); ?>" class="block" aria-label="<?php the_title_attribute(); ?>">
                    <?php
                    the_post_thumbnail( 'medium_large', array(
                        'class' => 'w-full h-48 object-cover transition-transform duration-300 group-hover:scale-105',
                        'alt'   => get_the_title(),
                    ) );
                    ?>
                </a>
                
                <!-- Post Format Icon -->
                <?php if ( get_post_format() ) : ?>
                    <div class="post-format-icon absolute top-4 left-4 w-8 h-8 bg-primary-500 text-white rounded-full flex items-center justify-center text-sm">
                        <?php
                        switch ( get_post_format() ) {
                            case 'video':
                                echo '<i class="fas fa-play" aria-hidden="true"></i>';
                                break;
                            case 'audio':
                                echo '<i class="fas fa-music" aria-hidden="true"></i>';
                                break;
                            case 'gallery':
                                echo '<i class="fas fa-images" aria-hidden="true"></i>';
                                break;
                            case 'quote':
                                echo '<i class="fas fa-quote-left" aria-hidden="true"></i>';
                                break;
                            case 'link':
                                echo '<i class="fas fa-link" aria-hidden="true"></i>';
                                break;
                            default:
                                echo '<i class="fas fa-file-alt" aria-hidden="true"></i>';
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <!-- Reading Time -->
                <div class="reading-time absolute top-4 right-4 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                    <?php echo aqualuxe_get_reading_time(); ?>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="post-content p-6">
            <!-- Post Meta -->
            <div class="post-meta flex flex-wrap items-center gap-4 text-sm text-gray-500 dark:text-gray-400 mb-3">
                <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="post-date">
                    <i class="fas fa-calendar-alt mr-1" aria-hidden="true"></i>
                    <?php echo get_the_date(); ?>
                </time>
                
                <div class="post-author">
                    <i class="fas fa-user mr-1" aria-hidden="true"></i>
                    <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" 
                       class="hover:text-primary-500 transition-colors">
                        <?php the_author(); ?>
                    </a>
                </div>
                
                <?php if ( comments_open() || get_comments_number() ) : ?>
                    <div class="post-comments">
                        <i class="fas fa-comments mr-1" aria-hidden="true"></i>
                        <a href="<?php comments_link(); ?>" class="hover:text-primary-500 transition-colors">
                            <?php
                            printf(
                                /* translators: %s: Number of comments */
                                _n( '%s comment', '%s comments', get_comments_number(), 'aqualuxe' ),
                                number_format_i18n( get_comments_number() )
                            );
                            ?>
                        </a>
                    </div>
                <?php endif; ?>
                
                <?php if ( function_exists( 'aqualuxe_get_post_views' ) ) : ?>
                    <div class="post-views">
                        <i class="fas fa-eye mr-1" aria-hidden="true"></i>
                        <?php echo aqualuxe_get_post_views( get_the_ID() ); ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Post Title -->
            <header class="entry-header">
                <h2 class="entry-title text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">
                    <a href="<?php the_permalink(); ?>" 
                       class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                        <?php the_title(); ?>
                    </a>
                </h2>
            </header>
            
            <!-- Post Excerpt -->
            <div class="entry-summary">
                <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                    <?php
                    if ( has_excerpt() ) {
                        echo get_the_excerpt();
                    } else {
                        echo wp_trim_words( get_the_content(), 25, '...' );
                    }
                    ?>
                </p>
            </div>
            
            <!-- Post Categories -->
            <?php
            $categories = get_the_category();
            if ( ! empty( $categories ) ) :
            ?>
                <div class="post-categories mb-4">
                    <div class="categories-list flex flex-wrap gap-2">
                        <?php foreach ( $categories as $category ) : ?>
                            <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" 
                               class="category-tag inline-block px-2 py-1 text-xs bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded hover:bg-primary-200 dark:hover:bg-primary-800 transition-colors">
                                <?php echo esc_html( $category->name ); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Post Tags (show only first 3) -->
            <?php
            $tags = get_the_tags();
            if ( ! empty( $tags ) ) :
                $display_tags = array_slice( $tags, 0, 3 );
            ?>
                <div class="post-tags mb-4">
                    <div class="tags-list flex flex-wrap gap-2">
                        <?php foreach ( $display_tags as $tag ) : ?>
                            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" 
                               class="tag-link inline-block px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                                #<?php echo esc_html( $tag->name ); ?>
                            </a>
                        <?php endforeach; ?>
                        
                        <?php if ( count( $tags ) > 3 ) : ?>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                +<?php echo count( $tags ) - 3; ?> more
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <!-- Read More Button -->
            <footer class="entry-footer">
                <a href="<?php the_permalink(); ?>" 
                   class="read-more-btn inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors">
                    <?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
                    <i class="fas fa-arrow-right ml-2 text-sm" aria-hidden="true"></i>
                </a>
            </footer>
        </div>
    </div>
</article>
