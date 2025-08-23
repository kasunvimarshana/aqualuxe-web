<?php
/**
 * Template part for displaying content after the header
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Page title section for pages, archives, search, etc.
if ( ! is_front_page() && ! is_home() ) :
    ?>
    <div class="page-title-section">
        <div class="container">
            <div class="page-title-inner">
                <div class="page-title-content">
                    <?php
                    if ( is_archive() ) :
                        the_archive_title( '<h1 class="page-title">', '</h1>' );
                        the_archive_description( '<div class="archive-description">', '</div>' );
                    elseif ( is_search() ) :
                        ?>
                        <h1 class="page-title">
                            <?php
                            /* translators: %s: search query. */
                            printf( esc_html__( 'Search Results for: %s', 'aqualuxe' ), '<span>' . get_search_query() . '</span>' );
                            ?>
                        </h1>
                        <?php
                    elseif ( is_404() ) :
                        ?>
                        <h1 class="page-title"><?php esc_html_e( 'Oops! That page can&rsquo;t be found.', 'aqualuxe' ); ?></h1>
                        <?php
                    elseif ( is_singular() && ! is_front_page() ) :
                        ?>
                        <h1 class="page-title"><?php single_post_title(); ?></h1>
                        <?php
                    endif;
                    ?>
                </div>

                <?php
                // Breadcrumbs
                $breadcrumbs = \AquaLuxe\Helpers\Utils::get_breadcrumbs();
                if ( ! empty( $breadcrumbs ) ) :
                    ?>
                    <div class="breadcrumbs">
                        <ul class="breadcrumbs-list">
                            <?php foreach ( $breadcrumbs as $index => $item ) : ?>
                                <li class="breadcrumb-item <?php echo isset( $item['current'] ) && $item['current'] ? 'current' : ''; ?>">
                                    <?php if ( ! empty( $item['url'] ) ) : ?>
                                        <a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['text'] ); ?></a>
                                    <?php else : ?>
                                        <span><?php echo esc_html( $item['text'] ); ?></span>
                                    <?php endif; ?>
                                </li>
                                <?php if ( $index < count( $breadcrumbs ) - 1 ) : ?>
                                    <li class="breadcrumb-separator">/</li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <?php
                endif;
                ?>
            </div>
        </div>
    </div>
    <?php
endif;