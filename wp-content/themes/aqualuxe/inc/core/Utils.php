<?php
namespace AquaLuxe\Core;

class Utils {
    public static function ensure_page( string $title, string $slug, string $content = '' ): int {
    $existing = function_exists('get_page_by_path') ? call_user_func('get_page_by_path', $slug ) : null;
        if ( $existing ) { return (int) $existing->ID; }
    $id = function_exists('wp_insert_post') ? call_user_func('wp_insert_post', [
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_content' => $content,
            'post_type'    => 'page',
            'post_status'  => 'publish',
            'meta_input'   => [ 'aqlx_seed' => 1 ],
        ] ) : 0;
        return (int) $id;
    }

    public static function set_static_front_page( int $page_id ): void {
        if ( ! function_exists('update_option') ) return;
        update_option( 'show_on_front', 'page' );
        update_option( 'page_on_front', $page_id );
    }

    public static function remove_seed_content(): void {
        if ( ! function_exists('get_posts') ) return;
        $posts = call_user_func('get_posts', [ 'post_type' => [ 'page', 'post' ], 'numberposts' => -1, 'meta_key' => 'aqlx_seed', 'meta_value' => 1 ] );
        foreach ( $posts as $p ) {
            if ( function_exists('wp_delete_post') ) { call_user_func('wp_delete_post', $p->ID, true ); }
        }
    }

    public static function ensure_menu( string $location, string $name, array $items = [] ): int {
        if ( ! function_exists('wp_get_nav_menus') ) return 0;
        $menu_id = 0;
        foreach ( call_user_func('wp_get_nav_menus') as $menu ) {
            if ( $menu->name === $name ) { $menu_id = (int) $menu->term_id; break; }
        }
        if ( ! $menu_id && function_exists('wp_create_nav_menu') ) {
            $menu_id = (int) call_user_func('wp_create_nav_menu', $name );
        }
        // Assign location
        if ( $menu_id ) {
            $locations = function_exists('get_theme_mod') ? call_user_func('get_theme_mod', 'nav_menu_locations', [] ) : [];
            $locations[ $location ] = $menu_id;
            if ( function_exists('set_theme_mod') ) { call_user_func('set_theme_mod', 'nav_menu_locations', $locations ); }
        }
        // Add items
        foreach ( $items as $item ) {
            if ( ! is_array( $item ) ) continue;
            $args = function_exists('wp_parse_args') ? call_user_func('wp_parse_args', $item, [ 'title' => '', 'url' => '', 'object_id' => 0, 'object' => 'custom', 'type' => 'custom' ] ) : $item;
            if ( function_exists('wp_update_nav_menu_item') ) {
                call_user_func('wp_update_nav_menu_item', $menu_id, 0, [
                    'menu-item-title'  => $args['title'],
                    'menu-item-url'    => $args['url'],
                    'menu-item-object' => $args['object'],
                    'menu-item-object-id' => $args['object_id'],
                    'menu-item-type'   => $args['type'],
                    'menu-item-status' => 'publish',
                ] );
            }
        }
        return $menu_id;
    }

    public static function ensure_product_cat( string $name, string $slug ): int {
        if ( ! function_exists('taxonomy_exists') || ! call_user_func('taxonomy_exists', 'product_cat' ) ) return 0;
        $term = function_exists('term_exists') ? call_user_func('term_exists', $slug, 'product_cat' ) : 0;
        if ( $term ) { return (int) $term['term_id']; }
        $res = function_exists('wp_insert_term') ? call_user_func('wp_insert_term', $name, 'product_cat', [ 'slug' => $slug ] ) : 0;
        return is_wp_error( $res ) ? 0 : (int) $res['term_id'];
    }

    public static function create_simple_product( string $name, float $price, array $args = [] ): int {
        if ( ! function_exists('post_type_exists') || ! call_user_func('post_type_exists', 'product') ) return 0;
        $id = call_user_func('wp_insert_post', [
            'post_title'  => $name,
            'post_status' => 'publish',
            'post_type'   => 'product',
            'meta_input'  => [ 'aqlx_seed' => 1 ],
        ] );
        if ( function_exists('is_wp_error') && is_wp_error( $id ) ) return 0;
        if ( function_exists('update_post_meta') ) { update_post_meta( $id, '_regular_price', $price ); }
        if ( function_exists('update_post_meta') ) { update_post_meta( $id, '_price', $price ); }
        if ( function_exists('update_post_meta') ) { update_post_meta( $id, '_stock_status', 'instock' ); }
        if ( function_exists('wp_set_object_terms') ) { wp_set_object_terms( $id, 'simple', 'product_type' ); }
        if ( ! empty( $args['cats'] ) ) {
            if ( function_exists('wp_set_object_terms') ) { wp_set_object_terms( $id, (array) $args['cats'], 'product_cat' ); }
        }
        return (int) $id;
    }
}
