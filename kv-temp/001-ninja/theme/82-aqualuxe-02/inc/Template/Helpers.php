<?php
namespace AquaLuxe\Template;

class Helpers {
    public static function breadcrumbs() : void {
        if ( \is_front_page() || \is_home() ) { return; }
        echo '<nav class="container mx-auto px-4 py-3 text-sm" aria-label="Breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
        echo '<ol class="flex flex-wrap items-center gap-2">';
        $pos = 1;
        $home_url = \home_url('/');
        self::crumb( \esc_html__( 'Home', 'aqualuxe' ), $home_url, $pos++ );

        // WooCommerce contexts
        if ( \function_exists('is_shop') && \is_shop() ) {
            self::crumb( \esc_html__( 'Shop', 'aqualuxe' ), null, $pos++ );
            echo '</ol></nav>'; return;
        }
        if ( \function_exists('is_product') && \is_product() ) {
            $shop_id = \function_exists('wc_get_page_id') ? \wc_get_page_id('shop') : 0;
            $shop_link = $shop_id ? \get_permalink($shop_id) : null;
            self::crumb( \esc_html__( 'Shop', 'aqualuxe' ), $shop_link, $pos++ );
            self::crumb( \get_the_title(), null, $pos++ );
            echo '</ol></nav>'; return;
        }
        if ( \function_exists('is_product_category') && \is_product_category() ) {
            $shop_id = \function_exists('wc_get_page_id') ? \wc_get_page_id('shop') : 0;
            $shop_link = $shop_id ? \get_permalink($shop_id) : null;
            self::crumb( \esc_html__( 'Shop', 'aqualuxe' ), $shop_link, $pos++ );
            self::crumb( \single_term_title('', false), null, $pos++ );
            echo '</ol></nav>'; return;
        }

        // Services/Events archives
        if ( \is_post_type_archive('service') ) {
            self::crumb( \esc_html__( 'Services', 'aqualuxe' ), null, $pos++ ); echo '</ol></nav>'; return;
        }
        if ( \is_post_type_archive('event') ) {
            self::crumb( \esc_html__( 'Events', 'aqualuxe' ), null, $pos++ ); echo '</ol></nav>'; return;
        }

        // Singular with ancestors
        if ( \is_singular() ) {
            $post = \get_post();
            if ( $post ) {
                $anc = array_reverse( \get_post_ancestors( $post ) );
                foreach ( $anc as $aid ) {
                    self::crumb( \get_the_title($aid), \get_permalink($aid), $pos++ );
                }
                self::crumb( \get_the_title($post), null, $pos++ );
                echo '</ol></nav>'; return;
            }
        }

        // Category/Tag/Tax
        if ( \is_category() ) { self::crumb( \single_cat_title('', false), null, $pos++ ); echo '</ol></nav>'; return; }
        if ( \is_tag() ) { self::crumb( \single_tag_title('', false), null, $pos++ ); echo '</ol></nav>'; return; }
        if ( \is_tax() ) { self::crumb( \single_term_title('', false), null, $pos++ ); echo '</ol></nav>'; return; }

        // Search
        if ( \is_search() ) { self::crumb( sprintf( \esc_html__('Search: %s','aqualuxe'), \get_search_query() ), null, $pos++ ); echo '</ol></nav>'; return; }

        // Fallback archive title
        if ( \is_archive() ) { self::crumb( \get_the_archive_title(), null, $pos++ ); echo '</ol></nav>'; return; }

        echo '</ol></nav>';
    }

    private static function crumb( string $label, ?string $url, int $pos ) : void {
        $label = trim( $label ); if ( $label === '' ) { return; }
        if ( $url ) {
            echo '<li class="flex items-center gap-2" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'
               . '<a class="text-white/80 hover:text-white underline" href="' . \esc_url($url) . '" itemprop="item"><span itemprop="name">' . \esc_html($label) . '</span></a>'
               . '<meta itemprop="position" content="' . (int)$pos . '" />'
               . '<span class="opacity-50">/</span></li>';
        } else {
            echo '<li class="flex items-center gap-2" itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">'
               . '<span class="text-white" itemprop="name">' . \esc_html($label) . '</span>'
               . '<meta itemprop="position" content="' . (int)$pos . '" />'
               . '</li>';
        }
    }
}
