<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

?>

<section class="no-results not-found bg-white dark:bg-gray-800 rounded-lg shadow-md p-8">
    <header class="page-header mb-6">
        <h1 class="page-title text-2xl font-bold"><?php esc_html_e( 'Nothing Found', 'aqualuxe' ); ?></h1>
    </header><!-- .page-header -->

    <div class="page-content prose dark:prose-invert max-w-none">
        <?php
        if ( is_home() && current_user_can( 'publish_posts' ) ) :

            printf(
                '<p>' . wp_kses(
                    /* translators: 1: link to WP admin new post page. */
                    __( 'Ready to publish your first post? <a href="%1$s">Get started here</a>.', 'aqualuxe' ),
                    array(
                        'a' => array(
                            'href' => array(),
                        ),
                    )
                ) . '</p>',
                esc_url( admin_url( 'post-new.php' ) )
            );

        elseif ( is_search() ) :
            ?>

            <p><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again with some different keywords.', 'aqualuxe' ); ?></p>
            
            <div class="search-form-container mt-6">
                <?php get_search_form(); ?>
            </div>

            <div class="search-suggestions mt-8">
                <h2 class="text-xl font-bold mb-4"><?php esc_html_e( 'Popular Searches', 'aqualuxe' ); ?></h2>
                
                <div class="flex flex-wrap gap-2">
                    <?php
                    // Get popular categories
                    $categories = get_categories( array(
                        'orderby'    => 'count',
                        'order'      => 'DESC',
                        'number'     => 5,
                        'hide_empty' => true,
                    ) );
                    
                    foreach ( $categories as $category ) {
                        echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="inline-block bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-3 py-1 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">' . esc_html( $category->name ) . '</a>';
                    }
                    ?>
                </div>
            </div>

            <?php
        else :
            ?>

            <p><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'aqualuxe' ); ?></p>
            
            <div class="search-form-container mt-6">
                <?php get_search_form(); ?>
            </div>

            <?php
        endif;
        ?>
    </div><!-- .page-content -->
</section><!-- .no-results -->