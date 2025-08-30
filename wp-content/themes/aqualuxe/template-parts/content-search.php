<?php
/**
 * Content template for search results
 *
 * @package AquaLuxe
 * @since 1.0.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'search-result-item' ); ?>>
    <div class="search-result-card bg-white dark:bg-gray-800 rounded-lg p-6 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-200 dark:border-gray-700">
        
        <div class="search-result-content flex gap-6">
            <!-- Thumbnail -->
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="search-thumbnail flex-shrink-0">
                    <a href="<?php the_permalink(); ?>" class="block" aria-label="<?php the_title_attribute(); ?>">
                        <?php
                        the_post_thumbnail( 'thumbnail', array(
                            'class' => 'w-20 h-20 object-cover rounded-lg',
                            'alt'   => get_the_title(),
                        ) );
                        ?>
                    </a>
                </div>
            <?php endif; ?>
            
            <!-- Content -->
            <div class="search-content flex-1 min-w-0">
                <!-- Post Meta -->
                <div class="search-meta flex flex-wrap items-center gap-3 text-sm text-gray-500 dark:text-gray-400 mb-2">
                    <!-- Post Type -->
                    <span class="post-type inline-flex items-center px-2 py-1 bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 rounded text-xs font-medium">
                        <?php
                        $post_type_obj = get_post_type_object( get_post_type() );
                        echo esc_html( $post_type_obj->labels->singular_name ?? 'Post' );
                        ?>
                    </span>
                    
                    <!-- Date -->
                    <time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>" class="post-date">
                        <i class="fas fa-calendar-alt mr-1" aria-hidden="true"></i>
                        <?php echo get_the_date(); ?>
                    </time>
                    
                    <!-- Author -->
                    <div class="post-author">
                        <i class="fas fa-user mr-1" aria-hidden="true"></i>
                        <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>" 
                           class="hover:text-primary-500 transition-colors">
                            <?php the_author(); ?>
                        </a>
                    </div>
                    
                    <!-- Comments -->
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
                    
                    <!-- Relevance Score (if available) -->
                    <?php if ( function_exists( 'aqualuxe_get_search_relevance' ) ) : ?>
                        <div class="search-relevance">
                            <i class="fas fa-star mr-1" aria-hidden="true"></i>
                            <?php
                            $relevance = aqualuxe_get_search_relevance( get_the_ID(), get_search_query() );
                            printf(
                                /* translators: %s: Relevance percentage */
                                esc_html__( '%s%% match', 'aqualuxe' ),
                                $relevance
                            );
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Title -->
                <header class="entry-header">
                    <h2 class="entry-title text-xl font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">
                        <a href="<?php the_permalink(); ?>" 
                           class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                            <?php
                            $title = get_the_title();
                            $search_query = get_search_query();
                            
                            // Highlight search terms in title
                            if ( $search_query ) {
                                $highlighted_title = aqualuxe_highlight_search_terms( $title, $search_query );
                                echo $highlighted_title;
                            } else {
                                the_title();
                            }
                            ?>
                        </a>
                    </h2>
                </header>
                
                <!-- Excerpt with highlighted search terms -->
                <div class="entry-summary">
                    <p class="text-gray-600 dark:text-gray-300 mb-4 line-clamp-3">
                        <?php
                        $excerpt = get_the_excerpt();
                        $search_query = get_search_query();
                        
                        // If no excerpt, get content excerpt
                        if ( empty( $excerpt ) ) {
                            $excerpt = wp_trim_words( strip_shortcodes( get_the_content() ), 30, '...' );
                        }
                        
                        // Highlight search terms in excerpt
                        if ( $search_query ) {
                            $highlighted_excerpt = aqualuxe_highlight_search_terms( $excerpt, $search_query );
                            echo $highlighted_excerpt;
                        } else {
                            echo esc_html( $excerpt );
                        }
                        ?>
                    </p>
                </div>
                
                <!-- Categories (for posts) -->
                <?php if ( 'post' === get_post_type() ) : ?>
                    <?php
                    $categories = get_the_category();
                    if ( ! empty( $categories ) ) :
                    ?>
                        <div class="post-categories mb-3">
                            <div class="categories-list flex flex-wrap gap-2">
                                <?php foreach ( array_slice( $categories, 0, 3 ) as $category ) : ?>
                                    <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" 
                                       class="category-tag inline-block px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-primary-100 dark:hover:bg-primary-900 hover:text-primary-800 dark:hover:text-primary-200 transition-colors">
                                        <?php echo esc_html( $category->name ); ?>
                                    </a>
                                <?php endforeach; ?>
                                
                                <?php if ( count( $categories ) > 3 ) : ?>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        +<?php echo count( $categories ) - 3; ?> more
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <!-- Tags (show only first 3) -->
                <?php if ( 'post' === get_post_type() ) : ?>
                    <?php
                    $tags = get_the_tags();
                    if ( ! empty( $tags ) ) :
                        $display_tags = array_slice( $tags, 0, 3 );
                    ?>
                        <div class="post-tags mb-4">
                            <div class="tags-list flex flex-wrap gap-2">
                                <?php foreach ( $display_tags as $tag ) : ?>
                                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" 
                                       class="tag-link inline-block px-2 py-1 text-xs bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
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
                <?php endif; ?>
                
                <!-- URL/Breadcrumb for pages -->
                <?php if ( 'page' === get_post_type() ) : ?>
                    <div class="page-breadcrumb mb-3 text-sm text-gray-500 dark:text-gray-400">
                        <i class="fas fa-map-marker-alt mr-1" aria-hidden="true"></i>
                        <?php
                        $permalink = get_permalink();
                        $home_url = home_url();
                        $relative_url = str_replace( $home_url, '', $permalink );
                        echo esc_html( $relative_url );
                        ?>
                    </div>
                <?php endif; ?>
                
                <!-- Product specific info for WooCommerce -->
                <?php if ( 'product' === get_post_type() && class_exists( 'WooCommerce' ) ) : ?>
                    <?php
                    $product = wc_get_product( get_the_ID() );
                    if ( $product ) :
                    ?>
                        <div class="product-info mb-3 flex flex-wrap gap-4 text-sm">
                            <div class="product-price font-semibold text-primary-600 dark:text-primary-400">
                                <?php echo $product->get_price_html(); ?>
                            </div>
                            
                            <?php if ( $product->is_in_stock() ) : ?>
                                <div class="product-stock text-green-600 dark:text-green-400">
                                    <i class="fas fa-check-circle mr-1" aria-hidden="true"></i>
                                    <?php esc_html_e( 'In Stock', 'aqualuxe' ); ?>
                                </div>
                            <?php else : ?>
                                <div class="product-stock text-red-600 dark:text-red-400">
                                    <i class="fas fa-times-circle mr-1" aria-hidden="true"></i>
                                    <?php esc_html_e( 'Out of Stock', 'aqualuxe' ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $product->get_average_rating() ) : ?>
                                <div class="product-rating text-yellow-600 dark:text-yellow-400">
                                    <i class="fas fa-star mr-1" aria-hidden="true"></i>
                                    <?php
                                    printf(
                                        /* translators: 1: Average rating, 2: Rating count */
                                        esc_html__( '%1$s (%2$s reviews)', 'aqualuxe' ),
                                        $product->get_average_rating(),
                                        $product->get_review_count()
                                    );
                                    ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
                
                <!-- Action Buttons -->
                <footer class="entry-footer flex items-center justify-between">
                    <a href="<?php the_permalink(); ?>" 
                       class="read-more-btn inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium transition-colors">
                        <?php
                        switch ( get_post_type() ) {
                            case 'product':
                                esc_html_e( 'View Product', 'aqualuxe' );
                                break;
                            case 'page':
                                esc_html_e( 'Visit Page', 'aqualuxe' );
                                break;
                            default:
                                esc_html_e( 'Read More', 'aqualuxe' );
                        }
                        ?>
                        <i class="fas fa-arrow-right ml-2 text-sm" aria-hidden="true"></i>
                    </a>
                    
                    <!-- Share Button -->
                    <div class="search-actions flex gap-2">
                        <button class="share-btn p-2 text-gray-400 hover:text-primary-500 transition-colors" 
                                data-url="<?php the_permalink(); ?>" 
                                data-title="<?php echo esc_attr( get_the_title() ); ?>"
                                aria-label="<?php esc_attr_e( 'Share this result', 'aqualuxe' ); ?>">
                            <i class="fas fa-share-alt" aria-hidden="true"></i>
                        </button>
                        
                        <button class="bookmark-btn p-2 text-gray-400 hover:text-yellow-500 transition-colors" 
                                data-post-id="<?php echo get_the_ID(); ?>"
                                aria-label="<?php esc_attr_e( 'Bookmark this result', 'aqualuxe' ); ?>">
                            <i class="fas fa-bookmark" aria-hidden="true"></i>
                        </button>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</article>
