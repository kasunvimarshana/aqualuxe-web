<?php
/**
 * FAQ Page Search Section
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Get search settings from customizer or ACF
$section_title = get_theme_mod( 'aqualuxe_faq_search_title', __( 'Can\'t Find What You\'re Looking For?', 'aqualuxe' ) );
$section_subtitle = get_theme_mod( 'aqualuxe_faq_search_subtitle', __( 'Search our knowledge base or contact our support team', 'aqualuxe' ) );
$show_search = get_theme_mod( 'aqualuxe_faq_show_search_section', true );
$background_color = get_theme_mod( 'aqualuxe_faq_search_background', 'light' );

// Skip if search section is disabled
if ( ! $show_search ) {
    return;
}

// Section classes
$section_classes = array( 'faq-search-section', 'section' );
if ( $background_color === 'dark' ) {
    $section_classes[] = 'bg-dark text-light';
} else {
    $section_classes[] = 'bg-light';
}
?>

<section class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>">
    <div class="container">
        <div class="section-header text-center">
            <?php if ( $section_title ) : ?>
                <h2 class="section-title"><?php echo esc_html( $section_title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( $section_subtitle ) : ?>
                <div class="section-subtitle">
                    <p><?php echo wp_kses_post( $section_subtitle ); ?></p>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="search-form-container">
            <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="input-group">
                    <input type="search" class="search-field form-control" placeholder="<?php esc_attr_e( 'Search our knowledge base...', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                    <input type="hidden" name="post_type" value="faq" />
                    <button type="submit" class="search-submit btn btn-primary">
                        <i class="icon-search"></i>
                        <span><?php esc_html_e( 'Search', 'aqualuxe' ); ?></span>
                    </button>
                </div>
            </form>
        </div>
        
        <?php
        // Popular searches
        $show_popular_searches = get_theme_mod( 'aqualuxe_faq_show_popular_searches', true );
        $popular_searches_title = get_theme_mod( 'aqualuxe_faq_popular_searches_title', __( 'Popular Searches', 'aqualuxe' ) );
        $popular_searches = get_theme_mod( 'aqualuxe_faq_popular_searches', 'shipping, returns, payment, account, order' );
        
        if ( $show_popular_searches && $popular_searches ) :
            $searches = explode( ',', $popular_searches );
        ?>
            <div class="popular-searches">
                <?php if ( $popular_searches_title ) : ?>
                    <h3 class="popular-searches-title"><?php echo esc_html( $popular_searches_title ); ?></h3>
                <?php endif; ?>
                
                <div class="search-tags">
                    <?php foreach ( $searches as $search ) : ?>
                        <?php $search = trim( $search ); ?>
                        <?php if ( $search ) : ?>
                            <a href="<?php echo esc_url( add_query_arg( array( 's' => $search, 'post_type' => 'faq' ), home_url( '/' ) ) ); ?>" class="search-tag"><?php echo esc_html( $search ); ?></a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>