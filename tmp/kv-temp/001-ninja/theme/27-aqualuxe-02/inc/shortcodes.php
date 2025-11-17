<?php
/**
 * Custom Shortcodes for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Register all shortcodes.
 *
 * @return void
 */
function aqualuxe_register_all_shortcodes() {
    // Content shortcodes.
    add_shortcode( 'aqualuxe_button', 'aqualuxe_button_shortcode' );
    add_shortcode( 'aqualuxe_icon', 'aqualuxe_icon_shortcode' );
    add_shortcode( 'aqualuxe_highlight', 'aqualuxe_highlight_shortcode' );
    add_shortcode( 'aqualuxe_tooltip', 'aqualuxe_tooltip_shortcode' );
    add_shortcode( 'aqualuxe_accordion', 'aqualuxe_accordion_shortcode' );
    add_shortcode( 'aqualuxe_accordion_item', 'aqualuxe_accordion_item_shortcode' );
    add_shortcode( 'aqualuxe_tabs', 'aqualuxe_tabs_shortcode' );
    add_shortcode( 'aqualuxe_tab', 'aqualuxe_tab_shortcode' );
    add_shortcode( 'aqualuxe_alert', 'aqualuxe_alert_shortcode' );
    add_shortcode( 'aqualuxe_divider', 'aqualuxe_divider_shortcode' );
    add_shortcode( 'aqualuxe_spacer', 'aqualuxe_spacer_shortcode' );
    add_shortcode( 'aqualuxe_column', 'aqualuxe_column_shortcode' );
    add_shortcode( 'aqualuxe_row', 'aqualuxe_row_shortcode' );
    add_shortcode( 'aqualuxe_container', 'aqualuxe_container_shortcode' );

    // Feature shortcodes.
    add_shortcode( 'aqualuxe_contact_info', 'aqualuxe_contact_info_shortcode' );
    add_shortcode( 'aqualuxe_social_icons', 'aqualuxe_social_icons_shortcode' );
    add_shortcode( 'aqualuxe_map', 'aqualuxe_map_shortcode' );
    add_shortcode( 'aqualuxe_video', 'aqualuxe_video_shortcode' );
    add_shortcode( 'aqualuxe_gallery', 'aqualuxe_gallery_shortcode' );
    add_shortcode( 'aqualuxe_counter', 'aqualuxe_counter_shortcode' );
    add_shortcode( 'aqualuxe_pricing_table', 'aqualuxe_pricing_table_shortcode' );
    add_shortcode( 'aqualuxe_pricing_item', 'aqualuxe_pricing_item_shortcode' );
    add_shortcode( 'aqualuxe_team_member', 'aqualuxe_team_member_shortcode' );
    add_shortcode( 'aqualuxe_testimonial', 'aqualuxe_testimonial_shortcode' );
    add_shortcode( 'aqualuxe_countdown', 'aqualuxe_countdown_shortcode' );

    // WooCommerce shortcodes.
    if ( class_exists( 'WooCommerce' ) ) {
        add_shortcode( 'aqualuxe_featured_products', 'aqualuxe_featured_products_shortcode' );
        add_shortcode( 'aqualuxe_product_categories', 'aqualuxe_product_categories_shortcode' );
        add_shortcode( 'aqualuxe_product_filter', 'aqualuxe_product_filter_shortcode' );
        add_shortcode( 'aqualuxe_product_search', 'aqualuxe_product_search_shortcode' );
        add_shortcode( 'aqualuxe_mini_cart', 'aqualuxe_mini_cart_shortcode' );
    }

    // Form shortcodes.
    add_shortcode( 'aqualuxe_contact_form', 'aqualuxe_contact_form_shortcode' );
    add_shortcode( 'aqualuxe_newsletter', 'aqualuxe_newsletter_shortcode' );
    add_shortcode( 'aqualuxe_search_form', 'aqualuxe_search_form_shortcode' );

    // Custom post type shortcodes.
    add_shortcode( 'aqualuxe_recent_posts', 'aqualuxe_recent_posts_shortcode' );
    add_shortcode( 'aqualuxe_post_grid', 'aqualuxe_post_grid_shortcode' );
    add_shortcode( 'aqualuxe_post_carousel', 'aqualuxe_post_carousel_shortcode' );
}

add_action( 'init', 'aqualuxe_register_all_shortcodes' );
