<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header(); ?>

<div class="site-content error-404-page">
    <div class="container">
        
        <main id="main" class="site-main" role="main">
            
            <section class="error-404 not-found">
                <header class="page-header">
                    <h1 class="page-title"><?php _e('Oops! That page can&rsquo;t be found.', AquaLuxeTheme::TEXT_DOMAIN); ?></h1>
                </header>
                
                <div class="page-content">
                    <p><?php _e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', AquaLuxeTheme::TEXT_DOMAIN); ?></p>
                    
                    <?php get_search_form(); ?>
                    
                    <?php if (aqualuxe_is_woocommerce_active()) : ?>
                        <div class="error-404-actions">
                            <a href="<?php echo esc_url(get_permalink(wc_get_page_id('shop'))); ?>" class="button">
                                <?php _e('Browse Products', AquaLuxeTheme::TEXT_DOMAIN); ?>
                            </a>
                            <a href="<?php echo esc_url(home_url('/')); ?>" class="button alt">
                                <?php _e('Go Home', AquaLuxeTheme::TEXT_DOMAIN); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
            
        </main>
        
    </div>
</div>

<?php get_footer(); ?>
