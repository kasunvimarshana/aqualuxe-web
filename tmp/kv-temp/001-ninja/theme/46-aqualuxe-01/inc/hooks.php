<?php
/**
 * AquaLuxe Theme Hooks System
 *
 * This file contains the hook functions for the AquaLuxe theme.
 * It provides a flexible way to add, modify, or remove content without editing template files.
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Before HTML
 */
function aqualuxe_before_html() {
    do_action( 'aqualuxe_before_html' );
}

/**
 * After HTML
 */
function aqualuxe_after_html() {
    do_action( 'aqualuxe_after_html' );
}

/**
 * Before Header
 */
function aqualuxe_before_header() {
    do_action( 'aqualuxe_before_header' );
}

/**
 * After Header
 */
function aqualuxe_after_header() {
    do_action( 'aqualuxe_after_header' );
}

/**
 * Before Content
 */
function aqualuxe_before_content() {
    do_action( 'aqualuxe_before_content' );
}

/**
 * After Content
 */
function aqualuxe_after_content() {
    do_action( 'aqualuxe_after_content' );
}

/**
 * Before Footer
 */
function aqualuxe_before_footer() {
    do_action( 'aqualuxe_before_footer' );
}

/**
 * After Footer
 */
function aqualuxe_after_footer() {
    do_action( 'aqualuxe_after_footer' );
}

/**
 * Before Sidebar
 */
function aqualuxe_before_sidebar() {
    do_action( 'aqualuxe_before_sidebar' );
}

/**
 * After Sidebar
 */
function aqualuxe_after_sidebar() {
    do_action( 'aqualuxe_after_sidebar' );
}

/**
 * Before Post Content
 */
function aqualuxe_before_post_content() {
    do_action( 'aqualuxe_before_post_content' );
}

/**
 * After Post Content
 */
function aqualuxe_after_post_content() {
    do_action( 'aqualuxe_after_post_content' );
}

/**
 * Before Page Content
 */
function aqualuxe_before_page_content() {
    do_action( 'aqualuxe_before_page_content' );
}

/**
 * After Page Content
 */
function aqualuxe_after_page_content() {
    do_action( 'aqualuxe_after_page_content' );
}

/**
 * Before Comments
 */
function aqualuxe_before_comments() {
    do_action( 'aqualuxe_before_comments' );
}

/**
 * After Comments
 */
function aqualuxe_after_comments() {
    do_action( 'aqualuxe_after_comments' );
}

/**
 * Before Comment Form
 */
function aqualuxe_before_comment_form() {
    do_action( 'aqualuxe_before_comment_form' );
}

/**
 * After Comment Form
 */
function aqualuxe_after_comment_form() {
    do_action( 'aqualuxe_after_comment_form' );
}

/**
 * Before Archive Loop
 */
function aqualuxe_before_archive_loop() {
    do_action( 'aqualuxe_before_archive_loop' );
}

/**
 * After Archive Loop
 */
function aqualuxe_after_archive_loop() {
    do_action( 'aqualuxe_after_archive_loop' );
}

/**
 * Before Search Results
 */
function aqualuxe_before_search_results() {
    do_action( 'aqualuxe_before_search_results' );
}

/**
 * After Search Results
 */
function aqualuxe_after_search_results() {
    do_action( 'aqualuxe_after_search_results' );
}

/**
 * Before 404 Content
 */
function aqualuxe_before_404_content() {
    do_action( 'aqualuxe_before_404_content' );
}

/**
 * After 404 Content
 */
function aqualuxe_after_404_content() {
    do_action( 'aqualuxe_after_404_content' );
}

/**
 * Before Post Meta
 */
function aqualuxe_before_post_meta() {
    do_action( 'aqualuxe_before_post_meta' );
}

/**
 * After Post Meta
 */
function aqualuxe_after_post_meta() {
    do_action( 'aqualuxe_after_post_meta' );
}

/**
 * Before Post Navigation
 */
function aqualuxe_before_post_navigation() {
    do_action( 'aqualuxe_before_post_navigation' );
}

/**
 * After Post Navigation
 */
function aqualuxe_after_post_navigation() {
    do_action( 'aqualuxe_after_post_navigation' );
}

/**
 * Before Pagination
 */
function aqualuxe_before_pagination() {
    do_action( 'aqualuxe_before_pagination' );
}

/**
 * After Pagination
 */
function aqualuxe_after_pagination() {
    do_action( 'aqualuxe_after_pagination' );
}

/**
 * Before Related Posts
 */
function aqualuxe_before_related_posts() {
    do_action( 'aqualuxe_before_related_posts' );
}

/**
 * After Related Posts
 */
function aqualuxe_after_related_posts() {
    do_action( 'aqualuxe_after_related_posts' );
}

/**
 * Before Author Box
 */
function aqualuxe_before_author_box() {
    do_action( 'aqualuxe_before_author_box' );
}

/**
 * After Author Box
 */
function aqualuxe_after_author_box() {
    do_action( 'aqualuxe_after_author_box' );
}

/**
 * Before Share Buttons
 */
function aqualuxe_before_share_buttons() {
    do_action( 'aqualuxe_before_share_buttons' );
}

/**
 * After Share Buttons
 */
function aqualuxe_after_share_buttons() {
    do_action( 'aqualuxe_after_share_buttons' );
}

/**
 * Before Newsletter Form
 */
function aqualuxe_before_newsletter_form() {
    do_action( 'aqualuxe_before_newsletter_form' );
}

/**
 * After Newsletter Form
 */
function aqualuxe_after_newsletter_form() {
    do_action( 'aqualuxe_after_newsletter_form' );
}

/**
 * Before Footer Widgets
 */
function aqualuxe_before_footer_widgets() {
    do_action( 'aqualuxe_before_footer_widgets' );
}

/**
 * After Footer Widgets
 */
function aqualuxe_after_footer_widgets() {
    do_action( 'aqualuxe_after_footer_widgets' );
}

/**
 * Before Footer Bottom
 */
function aqualuxe_before_footer_bottom() {
    do_action( 'aqualuxe_before_footer_bottom' );
}

/**
 * After Footer Bottom
 */
function aqualuxe_after_footer_bottom() {
    do_action( 'aqualuxe_after_footer_bottom' );
}

/**
 * Before Mobile Menu
 */
function aqualuxe_before_mobile_menu() {
    do_action( 'aqualuxe_before_mobile_menu' );
}

/**
 * After Mobile Menu
 */
function aqualuxe_after_mobile_menu() {
    do_action( 'aqualuxe_after_mobile_menu' );
}

/**
 * Before Top Bar
 */
function aqualuxe_before_top_bar() {
    do_action( 'aqualuxe_before_top_bar' );
}

/**
 * After Top Bar
 */
function aqualuxe_after_top_bar() {
    do_action( 'aqualuxe_after_top_bar' );
}

/**
 * Before Breadcrumbs
 */
function aqualuxe_before_breadcrumbs() {
    do_action( 'aqualuxe_before_breadcrumbs' );
}

/**
 * After Breadcrumbs
 */
function aqualuxe_after_breadcrumbs() {
    do_action( 'aqualuxe_after_breadcrumbs' );
}

/**
 * Before Page Title
 */
function aqualuxe_before_page_title() {
    do_action( 'aqualuxe_before_page_title' );
}

/**
 * After Page Title
 */
function aqualuxe_after_page_title() {
    do_action( 'aqualuxe_after_page_title' );
}

/**
 * Before Post Title
 */
function aqualuxe_before_post_title() {
    do_action( 'aqualuxe_before_post_title' );
}

/**
 * After Post Title
 */
function aqualuxe_after_post_title() {
    do_action( 'aqualuxe_after_post_title' );
}

/**
 * Before Post Thumbnail
 */
function aqualuxe_before_post_thumbnail() {
    do_action( 'aqualuxe_before_post_thumbnail' );
}

/**
 * After Post Thumbnail
 */
function aqualuxe_after_post_thumbnail() {
    do_action( 'aqualuxe_after_post_thumbnail' );
}

/**
 * Before Post Tags
 */
function aqualuxe_before_post_tags() {
    do_action( 'aqualuxe_before_post_tags' );
}

/**
 * After Post Tags
 */
function aqualuxe_after_post_tags() {
    do_action( 'aqualuxe_after_post_tags' );
}

/**
 * Before Post Categories
 */
function aqualuxe_before_post_categories() {
    do_action( 'aqualuxe_before_post_categories' );
}

/**
 * After Post Categories
 */
function aqualuxe_after_post_categories() {
    do_action( 'aqualuxe_after_post_categories' );
}

/**
 * Before Post Author
 */
function aqualuxe_before_post_author() {
    do_action( 'aqualuxe_before_post_author' );
}

/**
 * After Post Author
 */
function aqualuxe_after_post_author() {
    do_action( 'aqualuxe_after_post_author' );
}

/**
 * Before Post Date
 */
function aqualuxe_before_post_date() {
    do_action( 'aqualuxe_before_post_date' );
}

/**
 * After Post Date
 */
function aqualuxe_after_post_date() {
    do_action( 'aqualuxe_after_post_date' );
}

/**
 * Before Post Comments Count
 */
function aqualuxe_before_post_comments_count() {
    do_action( 'aqualuxe_before_post_comments_count' );
}

/**
 * After Post Comments Count
 */
function aqualuxe_after_post_comments_count() {
    do_action( 'aqualuxe_after_post_comments_count' );
}

/**
 * Before Post Excerpt
 */
function aqualuxe_before_post_excerpt() {
    do_action( 'aqualuxe_before_post_excerpt' );
}

/**
 * After Post Excerpt
 */
function aqualuxe_after_post_excerpt() {
    do_action( 'aqualuxe_after_post_excerpt' );
}

/**
 * Before Post Read More
 */
function aqualuxe_before_post_read_more() {
    do_action( 'aqualuxe_before_post_read_more' );
}

/**
 * After Post Read More
 */
function aqualuxe_after_post_read_more() {
    do_action( 'aqualuxe_after_post_read_more' );
}

/**
 * Before Archive Title
 */
function aqualuxe_before_archive_title() {
    do_action( 'aqualuxe_before_archive_title' );
}

/**
 * After Archive Title
 */
function aqualuxe_after_archive_title() {
    do_action( 'aqualuxe_after_archive_title' );
}

/**
 * Before Archive Description
 */
function aqualuxe_before_archive_description() {
    do_action( 'aqualuxe_before_archive_description' );
}

/**
 * After Archive Description
 */
function aqualuxe_after_archive_description() {
    do_action( 'aqualuxe_after_archive_description' );
}

/**
 * Before Search Form
 */
function aqualuxe_before_search_form() {
    do_action( 'aqualuxe_before_search_form' );
}

/**
 * After Search Form
 */
function aqualuxe_after_search_form() {
    do_action( 'aqualuxe_after_search_form' );
}

/**
 * Before Primary Menu
 */
function aqualuxe_before_primary_menu() {
    do_action( 'aqualuxe_before_primary_menu' );
}

/**
 * After Primary Menu
 */
function aqualuxe_after_primary_menu() {
    do_action( 'aqualuxe_after_primary_menu' );
}

/**
 * Before Secondary Menu
 */
function aqualuxe_before_secondary_menu() {
    do_action( 'aqualuxe_before_secondary_menu' );
}

/**
 * After Secondary Menu
 */
function aqualuxe_after_secondary_menu() {
    do_action( 'aqualuxe_after_secondary_menu' );
}

/**
 * Before Footer Menu
 */
function aqualuxe_before_footer_menu() {
    do_action( 'aqualuxe_before_footer_menu' );
}

/**
 * After Footer Menu
 */
function aqualuxe_after_footer_menu() {
    do_action( 'aqualuxe_after_footer_menu' );
}

/**
 * Before Site Branding
 */
function aqualuxe_before_site_branding() {
    do_action( 'aqualuxe_before_site_branding' );
}

/**
 * After Site Branding
 */
function aqualuxe_after_site_branding() {
    do_action( 'aqualuxe_after_site_branding' );
}

/**
 * Before Site Title
 */
function aqualuxe_before_site_title() {
    do_action( 'aqualuxe_before_site_title' );
}

/**
 * After Site Title
 */
function aqualuxe_after_site_title() {
    do_action( 'aqualuxe_after_site_title' );
}

/**
 * Before Site Description
 */
function aqualuxe_before_site_description() {
    do_action( 'aqualuxe_before_site_description' );
}

/**
 * After Site Description
 */
function aqualuxe_after_site_description() {
    do_action( 'aqualuxe_after_site_description' );
}

/**
 * Before Header Actions
 */
function aqualuxe_before_header_actions() {
    do_action( 'aqualuxe_before_header_actions' );
}

/**
 * After Header Actions
 */
function aqualuxe_after_header_actions() {
    do_action( 'aqualuxe_after_header_actions' );
}

/**
 * Before Header Search
 */
function aqualuxe_before_header_search() {
    do_action( 'aqualuxe_before_header_search' );
}

/**
 * After Header Search
 */
function aqualuxe_after_header_search() {
    do_action( 'aqualuxe_after_header_search' );
}

/**
 * Before Header Cart
 */
function aqualuxe_before_header_cart() {
    do_action( 'aqualuxe_before_header_cart' );
}

/**
 * After Header Cart
 */
function aqualuxe_after_header_cart() {
    do_action( 'aqualuxe_after_header_cart' );
}

/**
 * Before Header Account
 */
function aqualuxe_before_header_account() {
    do_action( 'aqualuxe_before_header_account' );
}

/**
 * After Header Account
 */
function aqualuxe_after_header_account() {
    do_action( 'aqualuxe_after_header_account' );
}

/**
 * Before Header Wishlist
 */
function aqualuxe_before_header_wishlist() {
    do_action( 'aqualuxe_before_header_wishlist' );
}

/**
 * After Header Wishlist
 */
function aqualuxe_after_header_wishlist() {
    do_action( 'aqualuxe_after_header_wishlist' );
}

/**
 * Before Dark Mode Toggle
 */
function aqualuxe_before_dark_mode_toggle() {
    do_action( 'aqualuxe_before_dark_mode_toggle' );
}

/**
 * After Dark Mode Toggle
 */
function aqualuxe_after_dark_mode_toggle() {
    do_action( 'aqualuxe_after_dark_mode_toggle' );
}

/**
 * Before Language Switcher
 */
function aqualuxe_before_language_switcher() {
    do_action( 'aqualuxe_before_language_switcher' );
}

/**
 * After Language Switcher
 */
function aqualuxe_after_language_switcher() {
    do_action( 'aqualuxe_after_language_switcher' );
}

/**
 * Before Currency Switcher
 */
function aqualuxe_before_currency_switcher() {
    do_action( 'aqualuxe_before_currency_switcher' );
}

/**
 * After Currency Switcher
 */
function aqualuxe_after_currency_switcher() {
    do_action( 'aqualuxe_after_currency_switcher' );
}

/**
 * Before Mobile Menu Toggle
 */
function aqualuxe_before_mobile_menu_toggle() {
    do_action( 'aqualuxe_before_mobile_menu_toggle' );
}

/**
 * After Mobile Menu Toggle
 */
function aqualuxe_after_mobile_menu_toggle() {
    do_action( 'aqualuxe_after_mobile_menu_toggle' );
}

/**
 * Before Footer Copyright
 */
function aqualuxe_before_footer_copyright() {
    do_action( 'aqualuxe_before_footer_copyright' );
}

/**
 * After Footer Copyright
 */
function aqualuxe_after_footer_copyright() {
    do_action( 'aqualuxe_after_footer_copyright' );
}

/**
 * Before Footer Social Icons
 */
function aqualuxe_before_footer_social_icons() {
    do_action( 'aqualuxe_before_footer_social_icons' );
}

/**
 * After Footer Social Icons
 */
function aqualuxe_after_footer_social_icons() {
    do_action( 'aqualuxe_after_footer_social_icons' );
}

/**
 * Before Footer Payment Icons
 */
function aqualuxe_before_footer_payment_icons() {
    do_action( 'aqualuxe_before_footer_payment_icons' );
}

/**
 * After Footer Payment Icons
 */
function aqualuxe_after_footer_payment_icons() {
    do_action( 'aqualuxe_after_footer_payment_icons' );
}