<?php
/**
 * Template for Portfolio Gallery Module
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Get module settings
$title = $this->get_setting( 'title' );
$subtitle = $this->get_setting( 'subtitle' );
$description = $this->get_setting( 'description' );
$layout = $this->get_setting( 'layout', 'grid' );
$style = $this->get_setting( 'style', 'default' );
$columns = $this->get_setting( 'columns', 3 );
$show_filters = $this->get_setting( 'show_filters', true );
$show_title = $this->get_setting( 'show_title', true );
$show_excerpt = $this->get_setting( 'show_excerpt', true );
$show_zoom = $this->get_setting( 'show_zoom', true );
$animation = $this->get_setting( 'animation', 'fade' );
$items_per_page = $this->get_setting( 'items_per_page', 9 );
$categories = $this->get_setting( 'categories', [] );

// Get portfolio items
$portfolio_items = $this->get_portfolio_items();

// Module classes
$module_classes = [
    'aqualuxe-portfolio-gallery',
    'aqualuxe-module',
    'layout-' . $layout,
    'style-' . $style,
    'columns-' . $columns,
    'animation-' . $animation,
];

if ( $show_filters ) {
    $module_classes[] = 'has-filters';
}

$module_class = implode( ' ', $module_classes );
?>

<section id="<?php echo esc_attr( $this->get_id() ); ?>" class="<?php echo esc_attr( $module_class ); ?>">
    <div class="container mx-auto px-4">
        <?php if ( $title || $subtitle || $description ) : ?>
            <div class="aqualuxe-portfolio-gallery__header text-center mb-12">
                <?php if ( $subtitle ) : ?>
                    <div class="aqualuxe-portfolio-gallery__subtitle text-primary text-sm uppercase tracking-wider font-semibold mb-2">
                        <?php echo esc_html( $subtitle ); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $title ) : ?>
                    <h2 class="aqualuxe-portfolio-gallery__title text-3xl md:text-4xl font-bold mb-4">
                        <?php echo esc_html( $title ); ?>
                    </h2>
                <?php endif; ?>
                
                <?php if ( $description ) : ?>
                    <div class="aqualuxe-portfolio-gallery__description max-w-2xl mx-auto text-gray-600 dark:text-gray-400">
                        <?php echo wp_kses_post( $description ); ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ( $show_filters && ! empty( $categories ) ) : ?>
            <div class="aqualuxe-portfolio-gallery__filters flex flex-wrap justify-center mb-8">
                <ul class="flex flex-wrap gap-2 md:gap-4">
                    <?php foreach ( $categories as $cat_key => $cat_name ) : ?>
                        <li>
                            <button 
                                class="portfolio-filter-btn <?php echo $cat_key === 'all' ? 'active' : ''; ?>" 
                                data-filter="<?php echo esc_attr( $cat_key ); ?>"
                            >
                                <?php echo esc_html( $cat_name ); ?>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="aqualuxe-portfolio-gallery__items">
            <div class="portfolio-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-<?php echo esc_attr( $columns ); ?> gap-6">
                <?php if ( ! empty( $portfolio_items ) ) : ?>
                    <?php foreach ( $portfolio_items as $item ) : ?>
                        <div class="portfolio-item" data-category="<?php echo esc_attr( $item['category'] ); ?>">
                            <div class="portfolio-item__inner overflow-hidden rounded-lg shadow-lg transition-all duration-300 h-full">
                                <?php if ( ! empty( $item['image'] ) ) : ?>
                                    <div class="portfolio-item__image relative overflow-hidden">
                                        <img 
                                            src="<?php echo esc_url( $item['image'] ); ?>" 
                                            alt="<?php echo esc_attr( $item['title'] ); ?>"
                                            class="w-full h-64 object-cover transition-transform duration-500"
                                        >
                                        
                                        <div class="portfolio-item__overlay absolute inset-0 bg-primary bg-opacity-80 flex items-center justify-center opacity-0 transition-opacity duration-300">
                                            <?php if ( $show_zoom && ! empty( $item['image'] ) ) : ?>
                                                <a href="<?php echo esc_url( $item['image'] ); ?>" class="portfolio-zoom-btn text-white bg-black bg-opacity-50 hover:bg-opacity-70 rounded-full p-3 mx-2 transition-all duration-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7" />
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $item['url'] ) ) : ?>
                                                <a href="<?php echo esc_url( $item['url'] ); ?>" class="portfolio-link-btn text-white bg-black bg-opacity-50 hover:bg-opacity-70 rounded-full p-3 mx-2 transition-all duration-300">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                                    </svg>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $show_title || $show_excerpt ) : ?>
                                    <div class="portfolio-item__content p-6">
                                        <?php if ( $show_title && ! empty( $item['title'] ) ) : ?>
                                            <h3 class="portfolio-item__title text-xl font-bold mb-2">
                                                <?php if ( ! empty( $item['url'] ) ) : ?>
                                                    <a href="<?php echo esc_url( $item['url'] ); ?>" class="hover:text-primary transition-colors duration-300">
                                                        <?php echo esc_html( $item['title'] ); ?>
                                                    </a>
                                                <?php else : ?>
                                                    <?php echo esc_html( $item['title'] ); ?>
                                                <?php endif; ?>
                                            </h3>
                                        <?php endif; ?>
                                        
                                        <?php if ( $show_excerpt && ! empty( $item['excerpt'] ) ) : ?>
                                            <div class="portfolio-item__excerpt text-gray-600 dark:text-gray-400">
                                                <?php echo wp_kses_post( $item['excerpt'] ); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $item['category_name'] ) ) : ?>
                                            <div class="portfolio-item__category mt-3">
                                                <span class="inline-block bg-gray-200 dark:bg-gray-700 rounded-full px-3 py-1 text-xs font-semibold text-gray-700 dark:text-gray-300">
                                                    <?php echo esc_html( $item['category_name'] ); ?>
                                                </span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-span-full text-center py-12">
                        <p><?php esc_html_e( 'No portfolio items found.', 'aqualuxe' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>