<?php
namespace AquaLuxe\Blocks;

class Patterns {
    public static function register() : void {
        if ( ! \function_exists('register_block_pattern_category') || ! \function_exists('register_block_pattern') ) {
            return;
        }

        \register_block_pattern_category( 'aqualuxe', [ 'label' => \__( 'AquaLuxe', 'aqualuxe' ) ] );

        $headline = \esc_html__( 'Bringing elegance to aquatic life – globally', 'aqualuxe' );
        $subline  = \esc_html__( 'Premium livestock, plants, equipment & services', 'aqualuxe' );
        $cta1     = \esc_html__( 'Shop Now', 'aqualuxe' );
        $cta2     = \esc_html__( 'Book a Service', 'aqualuxe' );

        $hero = '<!-- wp:group {"align":"full","className":"relative overflow-hidden"} -->'
            . '<div class="wp-block-group alignfull relative overflow-hidden"><div class="wp-block-group__inner-container">'
            . '<!-- wp:html -->'
            . '<div id="alx-hero-canvas" class="w-full" style="height:70vh" data-ambient="off"></div>'
            . '<!-- /wp:html -->'
            . '<!-- wp:group {"layout":{"type":"constrained","contentSize":"1200px"},"className":"absolute inset-0 flex items-center justify-center text-center"} -->'
            . '<div class="wp-block-group absolute inset-0 flex items-center justify-center text-center"><div class="wp-block-group__inner-container">'
            . '<!-- wp:heading {"textAlign":"center","level":1,"className":"text-4xl md:text-6xl font-bold text-white"} -->'
            . '<h1 class="wp-block-heading has-text-align-center text-4xl md:text-6xl font-bold text-white">' . $headline . '</h1>'
            . '<!-- /wp:heading -->'
            . '<!-- wp:paragraph {"align":"center","className":"mt-4 md:text-lg opacity-90 text-white"} -->'
            . '<p class="has-text-align-center mt-4 md:text-lg opacity-90 text-white">' . $subline . '</p>'
            . '<!-- /wp:paragraph -->'
            . '<!-- wp:buttons {"className":"mt-6"} -->'
            . '<div class="wp-block-buttons mt-6">'
            . '<!-- wp:button {"className":"btn btn-primary"} -->'
            . '<div class="wp-block-button btn btn-primary"><a class="wp-block-button__link wp-element-button" href="/shop">' . $cta1 . '</a></div>'
            . '<!-- /wp:button -->'
            . '<!-- wp:button {"className":"btn btn-ghost"} -->'
            . '<div class="wp-block-button btn btn-ghost"><a class="wp-block-button__link wp-element-button" href="/services">' . $cta2 . '</a></div>'
            . '<!-- /wp:button -->'
            . '</div>'
            . '<!-- /wp:buttons -->'
            . '</div></div>'
            . '<!-- /wp:group -->'
            . '</div></div>'
            . '<!-- /wp:group -->';

        \register_block_pattern( 'aqualuxe/hero', [
            'title'       => \__( 'AquaLuxe Hero', 'aqualuxe' ),
            'categories'  => [ 'aqualuxe' ],
            'description' => \__( 'Immersive canvas hero with headline and CTAs.', 'aqualuxe' ),
            'content'     => $hero,
        ] );

        $feat_title = \esc_html__( 'Featured Products', 'aqualuxe' );
        $feat = '<!-- wp:group {"layout":{"type":"constrained","contentSize":"1200px"}} -->'
            . '<div class="wp-block-group"><div class="wp-block-group__inner-container">'
            . '<!-- wp:heading {"level":2,"className":"text-2xl md:text-3xl font-semibold mb-6"} -->'
            . '<h2 class="wp-block-heading text-2xl md:text-3xl font-semibold mb-6">' . $feat_title . '</h2>'
            . '<!-- /wp:heading -->'
            . '<!-- wp:shortcode -->[products limit="8" columns="4" visibility="featured"]<!-- /wp:shortcode -->'
            . '</div></div>'
            . '<!-- /wp:group -->';

        \register_block_pattern( 'aqualuxe/featured-products', [
            'title'       => \__( 'Featured Products Grid', 'aqualuxe' ),
            'categories'  => [ 'aqualuxe' ],
            'description' => \__( 'Grid of featured products (WooCommerce).', 'aqualuxe' ),
            'content'     => $feat,
        ] );
    }
}
