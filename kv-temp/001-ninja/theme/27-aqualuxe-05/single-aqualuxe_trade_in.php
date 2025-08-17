<?php
/**
 * The template for displaying single trade-in items
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <?php while ( have_posts() ) : the_post(); ?>
            <?php
            // Get trade-in data
            $trade_in_id = get_the_ID();
            $condition = get_post_meta( $trade_in_id, '_trade_in_condition', true );
            $age = get_post_meta( $trade_in_id, '_trade_in_age', true );
            $brand = get_post_meta( $trade_in_id, '_trade_in_brand', true );
            $model = get_post_meta( $trade_in_id, '_trade_in_model', true );
            $specifications = get_post_meta( $trade_in_id, '_trade_in_specifications', true );
            $original_owner = get_post_meta( $trade_in_id, '_trade_in_original_owner', true ) === 'yes';
            $trade_value = get_post_meta( $trade_in_id, '_trade_in_value', true );
            $store_credit = get_post_meta( $trade_in_id, '_trade_in_store_credit', true );
            $cash_value = get_post_meta( $trade_in_id, '_trade_in_cash_value', true );
            
            // Get trade-in status
            $status_terms = get_the_terms( $trade_in_id, 'trade_in_status' );
            $status = ! empty( $status_terms ) ? $status_terms[0]->slug : '';
            $status_name = ! empty( $status_terms ) ? $status_terms[0]->name : '';
            
            // Get trade-in category
            $category_terms = get_the_terms( $trade_in_id, 'trade_in_category' );
            $category = ! empty( $category_terms ) ? $category_terms[0]->slug : '';
            $category_name = ! empty( $category_terms ) ? $category_terms[0]->name : '';
            
            // Format values
            $formatted_trade_value = $trade_value ? '$' . number_format( $trade_value, 2 ) : '';
            $formatted_store_credit = $store_credit ? '$' . number_format( $store_credit, 2 ) : '';
            $formatted_cash_value = $cash_value ? '$' . number_format( $cash_value, 2 ) : '';
            
            // Condition labels
            $condition_labels = array(
                'new'       => __( 'New', 'aqualuxe' ),
                'like-new'  => __( 'Like New', 'aqualuxe' ),
                'excellent' => __( 'Excellent', 'aqualuxe' ),
                'good'      => __( 'Good', 'aqualuxe' ),
                'fair'      => __( 'Fair', 'aqualuxe' ),
                'poor'      => __( 'Poor', 'aqualuxe' ),
            );
            
            $condition_label = isset( $condition_labels[ $condition ] ) ? $condition_labels[ $condition ] : '';
            ?>
            
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="trade-in-container" data-trade-in-id="<?php echo esc_attr( $trade_in_id ); ?>">
                    <header class="entry-header mb-8">
                        <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                            <h1 class="entry-title text-3xl md:text-4xl font-bold text-primary-900 dark:text-primary-100">
                                <?php the_title(); ?>
                            </h1>
                            
                            <span class="trade-in-status <?php echo esc_attr( $status ); ?>">
                                <?php echo esc_html( $status_name ); ?>
                            </span>
                        </div>
                        
                        <?php if ( $category_name ) : ?>
                            <div class="entry-meta">
                                <span class="inline-block bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm px-3 py-1 rounded-full">
                                    <?php echo esc_html( $category_name ); ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </header>
                    
                    <div class="entry-content">
                        <div class="trade-in-single">
                            <div class="trade-in-gallery">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="trade-in-item-slider">
                                        <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto' ) ); ?>
                                        
                                        <?php
                                        // Check for gallery images
                                        $gallery_images = get_post_meta( $trade_in_id, '_trade_in_gallery', true );
                                        if ( ! empty( $gallery_images ) ) :
                                            $gallery_ids = explode( ',', $gallery_images );
                                            foreach ( $gallery_ids as $gallery_id ) :
                                                $image_url = wp_get_attachment_image_url( $gallery_id, 'large' );
                                                if ( $image_url ) :
                                                    ?>
                                                    <div>
                                                        <img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-auto">
                                                    </div>
                                                    <?php
                                                endif;
                                            endforeach;
                                        endif;
                                        ?>
                                    </div>
                                    
                                    <?php if ( ! empty( $gallery_images ) ) : ?>
                                        <div class="trade-in-thumbnails">
                                            <div class="trade-in-thumbnail active">
                                                <?php the_post_thumbnail( 'thumbnail' ); ?>
                                            </div>
                                            
                                            <?php
                                            foreach ( $gallery_ids as $gallery_id ) :
                                                $thumb_url = wp_get_attachment_image_url( $gallery_id, 'thumbnail' );
                                                if ( $thumb_url ) :
                                                    ?>
                                                    <div class="trade-in-thumbnail">
                                                        <img src="<?php echo esc_url( $thumb_url ); ?>" alt="<?php the_title_attribute(); ?>">
                                                    </div>
                                                    <?php
                                                endif;
                                            endforeach;
                                            ?>
                                        </div>
                                    <?php endif; ?>
                                <?php else : ?>
                                    <div class="trade-in-no-image bg-gray-200 dark:bg-gray-700 rounded-lg flex items-center justify-center" style="height: 300px;">
                                        <span class="text-gray-500 dark:text-gray-400">
                                            <?php esc_html_e( 'No Image Available', 'aqualuxe' ); ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="trade-in-details">
                                <div class="trade-in-header">
                                    <div class="trade-in-description prose dark:prose-invert max-w-none">
                                        <?php the_content(); ?>
                                    </div>
                                </div>
                                
                                <div class="trade-in-specs">
                                    <?php if ( $condition_label ) : ?>
                                        <div class="trade-in-spec">
                                            <span class="trade-in-spec-label"><?php esc_html_e( 'Condition', 'aqualuxe' ); ?></span>
                                            <span class="trade-in-spec-value"><?php echo esc_html( $condition_label ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $age ) : ?>
                                        <div class="trade-in-spec">
                                            <span class="trade-in-spec-label"><?php esc_html_e( 'Age', 'aqualuxe' ); ?></span>
                                            <span class="trade-in-spec-value"><?php echo esc_html( $age ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $brand ) : ?>
                                        <div class="trade-in-spec">
                                            <span class="trade-in-spec-label"><?php esc_html_e( 'Brand', 'aqualuxe' ); ?></span>
                                            <span class="trade-in-spec-value"><?php echo esc_html( $brand ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $model ) : ?>
                                        <div class="trade-in-spec">
                                            <span class="trade-in-spec-label"><?php esc_html_e( 'Model', 'aqualuxe' ); ?></span>
                                            <span class="trade-in-spec-value"><?php echo esc_html( $model ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $original_owner ) : ?>
                                        <div class="trade-in-spec">
                                            <span class="trade-in-spec-label"><?php esc_html_e( 'Original Owner', 'aqualuxe' ); ?></span>
                                            <span class="trade-in-spec-value"><?php esc_html_e( 'Yes', 'aqualuxe' ); ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ( $specifications ) : ?>
                                    <div class="trade-in-specifications mt-4">
                                        <h3 class="text-lg font-semibold mb-2"><?php esc_html_e( 'Specifications', 'aqualuxe' ); ?></h3>
                                        <div class="bg-gray-50 dark:bg-gray-800 p-4 rounded-lg">
                                            <?php echo wp_kses_post( wpautop( $specifications ) ); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $formatted_trade_value || $formatted_store_credit || $formatted_cash_value ) : ?>
                                    <div class="trade-in-values">
                                        <?php if ( $formatted_trade_value ) : ?>
                                            <div class="trade-in-value-item">
                                                <span class="trade-in-value-label"><?php esc_html_e( 'Trade-In Value', 'aqualuxe' ); ?></span>
                                                <span class="trade-in-value-amount"><?php echo esc_html( $formatted_trade_value ); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ( $formatted_store_credit ) : ?>
                                            <div class="trade-in-value-item">
                                                <span class="trade-in-value-label"><?php esc_html_e( 'Store Credit', 'aqualuxe' ); ?></span>
                                                <span class="trade-in-value-amount"><?php echo esc_html( $formatted_store_credit ); ?></span>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ( $formatted_cash_value ) : ?>
                                            <div class="trade-in-value-item">
                                                <span class="trade-in-value-label"><?php esc_html_e( 'Cash Value', 'aqualuxe' ); ?></span>
                                                <span class="trade-in-value-amount"><?php echo esc_html( $formatted_cash_value ); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( 'available' === $status ) : ?>
                                    <div class="trade-in-cta">
                                        <a href="<?php echo esc_url( get_permalink( get_page_by_path( 'trade-in-request' ) ) ); ?>" class="trade-in-cta-button">
                                            <?php esc_html_e( 'Request This Item', 'aqualuxe' ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php
                        // Related trade-ins
                        if ( $category ) :
                            $related_args = array(
                                'post_type'      => 'aqualuxe_trade_in',
                                'posts_per_page' => 3,
                                'post__not_in'   => array( $trade_in_id ),
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => 'trade_in_category',
                                        'field'    => 'slug',
                                        'terms'    => $category,
                                    ),
                                    array(
                                        'taxonomy' => 'trade_in_status',
                                        'field'    => 'slug',
                                        'terms'    => 'available',
                                    ),
                                ),
                            );
                            
                            $related_query = new WP_Query( $related_args );
                            
                            if ( $related_query->have_posts() ) :
                                ?>
                                <div class="trade-in-related mt-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Related Items', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="trade-in-grid">
                                        <?php while ( $related_query->have_posts() ) : $related_query->the_post(); ?>
                                            <?php
                                            $related_id = get_the_ID();
                                            $related_value = get_post_meta( $related_id, '_trade_in_value', true );
                                            $related_condition = get_post_meta( $related_id, '_trade_in_condition', true );
                                            $related_condition_label = isset( $condition_labels[ $related_condition ] ) ? $condition_labels[ $related_condition ] : '';
                                            ?>
                                            
                                            <article class="trade-in-item">
                                                <div class="trade-in-item-image">
                                                    <?php if ( has_post_thumbnail() ) : ?>
                                                        <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                                                    <?php else : ?>
                                                        <div class="w-full h-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                                            <span class="text-gray-500 dark:text-gray-400">
                                                                <?php esc_html_e( 'No Image', 'aqualuxe' ); ?>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                
                                                <div class="trade-in-item-content">
                                                    <h3 class="trade-in-item-title">
                                                        <a href="<?php the_permalink(); ?>">
                                                            <?php the_title(); ?>
                                                        </a>
                                                    </h3>
                                                    
                                                    <div class="trade-in-item-meta">
                                                        <?php if ( $related_condition_label ) : ?>
                                                            <div class="trade-in-item-meta-item">
                                                                <strong><?php esc_html_e( 'Condition:', 'aqualuxe' ); ?></strong>
                                                                <?php echo esc_html( $related_condition_label ); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                    </div>
                                                    
                                                    <div class="trade-in-item-footer">
                                                        <?php if ( $related_value ) : ?>
                                                            <div class="trade-in-item-value">
                                                                <?php echo esc_html( '$' . number_format( $related_value, 2 ) ); ?>
                                                            </div>
                                                        <?php endif; ?>
                                                        
                                                        <a href="<?php the_permalink(); ?>" class="trade-in-item-button">
                                                            <?php esc_html_e( 'View Details', 'aqualuxe' ); ?>
                                                        </a>
                                                    </div>
                                                </div>
                                            </article>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <?php
                                wp_reset_postdata();
                            endif;
                        endif;
                        ?>
                    </div>
                </div>
            </article>
        <?php endwhile; ?>
    </div>
</main>

<?php
get_footer();