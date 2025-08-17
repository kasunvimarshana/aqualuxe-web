<?php
/**
 * Action and filter hooks for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Header hooks
 */
function aqualuxe_header_before() {
    /**
     * Hook: aqualuxe_header_before
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_header_before');
}

function aqualuxe_header() {
    /**
     * Hook: aqualuxe_header
     *
     * @hooked aqualuxe_header_content - 10
     */
    do_action('aqualuxe_header');
}

function aqualuxe_header_after() {
    /**
     * Hook: aqualuxe_header_after
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_header_after');
}

/**
 * Footer hooks
 */
function aqualuxe_footer_before() {
    /**
     * Hook: aqualuxe_footer_before
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_footer_before');
}

function aqualuxe_footer() {
    /**
     * Hook: aqualuxe_footer
     *
     * @hooked aqualuxe_footer_content - 10
     */
    do_action('aqualuxe_footer');
}

function aqualuxe_footer_after() {
    /**
     * Hook: aqualuxe_footer_after
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_footer_after');
}

/**
 * Content hooks
 */
function aqualuxe_content_before() {
    /**
     * Hook: aqualuxe_content_before
     *
     * @hooked aqualuxe_breadcrumbs - 10
     */
    do_action('aqualuxe_content_before');
}

function aqualuxe_content_after() {
    /**
     * Hook: aqualuxe_content_after
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_content_after');
}

/**
 * Entry hooks
 */
function aqualuxe_entry_before() {
    /**
     * Hook: aqualuxe_entry_before
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_entry_before');
}

function aqualuxe_entry_header() {
    /**
     * Hook: aqualuxe_entry_header
     *
     * @hooked aqualuxe_post_thumbnail - 10
     * @hooked aqualuxe_entry_header_content - 20
     */
    do_action('aqualuxe_entry_header');
}

function aqualuxe_entry_content() {
    /**
     * Hook: aqualuxe_entry_content
     *
     * @hooked aqualuxe_entry_content_content - 10
     */
    do_action('aqualuxe_entry_content');
}

function aqualuxe_entry_footer() {
    /**
     * Hook: aqualuxe_entry_footer
     *
     * @hooked aqualuxe_entry_footer_content - 10
     */
    do_action('aqualuxe_entry_footer');
}

function aqualuxe_entry_after() {
    /**
     * Hook: aqualuxe_entry_after
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_entry_after');
}

/**
 * Sidebar hooks
 */
function aqualuxe_sidebar_before() {
    /**
     * Hook: aqualuxe_sidebar_before
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_sidebar_before');
}

function aqualuxe_sidebar() {
    /**
     * Hook: aqualuxe_sidebar
     *
     * @hooked aqualuxe_get_sidebar - 10
     */
    do_action('aqualuxe_sidebar');
}

function aqualuxe_sidebar_after() {
    /**
     * Hook: aqualuxe_sidebar_after
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_sidebar_after');
}

/**
 * Comments hooks
 */
function aqualuxe_comments_before() {
    /**
     * Hook: aqualuxe_comments_before
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_comments_before');
}

function aqualuxe_comments() {
    /**
     * Hook: aqualuxe_comments
     *
     * @hooked aqualuxe_comments_content - 10
     */
    do_action('aqualuxe_comments');
}

function aqualuxe_comments_after() {
    /**
     * Hook: aqualuxe_comments_after
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_comments_after');
}

/**
 * Hook implementations
 */

/**
 * Header content
 */
function aqualuxe_header_content() {
    get_template_part('template-parts/header/header', 'content');
}
add_action('aqualuxe_header', 'aqualuxe_header_content', 10);

/**
 * Footer content
 */
function aqualuxe_footer_content() {
    get_template_part('template-parts/footer/footer', 'content');
}
add_action('aqualuxe_footer', 'aqualuxe_footer_content', 10);

/**
 * Breadcrumbs
 */
function aqualuxe_breadcrumbs_display() {
    aqualuxe_breadcrumbs();
}
add_action('aqualuxe_content_before', 'aqualuxe_breadcrumbs_display', 10);

/**
 * Entry header content
 */
function aqualuxe_entry_header_content() {
    if (is_singular()) {
        get_template_part('template-parts/content/entry-header', get_post_type());
    } else {
        get_template_part('template-parts/content/entry-header', 'archive');
    }
}
add_action('aqualuxe_entry_header', 'aqualuxe_entry_header_content', 20);

/**
 * Post thumbnail
 */
function aqualuxe_post_thumbnail_display() {
    aqualuxe_post_thumbnail();
}
add_action('aqualuxe_entry_header', 'aqualuxe_post_thumbnail_display', 10);

/**
 * Entry content
 */
function aqualuxe_entry_content_content() {
    get_template_part('template-parts/content/entry-content', get_post_type());
}
add_action('aqualuxe_entry_content', 'aqualuxe_entry_content_content', 10);

/**
 * Entry footer content
 */
function aqualuxe_entry_footer_content() {
    if (is_singular()) {
        get_template_part('template-parts/content/entry-footer', get_post_type());
    } else {
        get_template_part('template-parts/content/entry-footer', 'archive');
    }
}
add_action('aqualuxe_entry_footer', 'aqualuxe_entry_footer_content', 10);

/**
 * Sidebar
 */
function aqualuxe_get_sidebar() {
    get_sidebar();
}
add_action('aqualuxe_sidebar', 'aqualuxe_get_sidebar', 10);

/**
 * Comments
 */
function aqualuxe_comments_content() {
    // If comments are open or we have at least one comment, load up the comment template.
    if (comments_open() || get_comments_number()) {
        comments_template();
    }
}
add_action('aqualuxe_comments', 'aqualuxe_comments_content', 10);

/**
 * Custom hooks for WooCommerce integration
 */
if (class_exists('WooCommerce')) {
    /**
     * Shop hooks
     */
    function aqualuxe_shop_before() {
        /**
         * Hook: aqualuxe_shop_before
         *
         * @hooked none - 10
         */
        do_action('aqualuxe_shop_before');
    }

    function aqualuxe_shop_after() {
        /**
         * Hook: aqualuxe_shop_after
         *
         * @hooked none - 10
         */
        do_action('aqualuxe_shop_after');
    }

    /**
     * Product hooks
     */
    function aqualuxe_product_before() {
        /**
         * Hook: aqualuxe_product_before
         *
         * @hooked none - 10
         */
        do_action('aqualuxe_product_before');
    }

    function aqualuxe_product_after() {
        /**
         * Hook: aqualuxe_product_after
         *
         * @hooked none - 10
         */
        do_action('aqualuxe_product_after');
    }

    /**
     * Cart hooks
     */
    function aqualuxe_cart_before() {
        /**
         * Hook: aqualuxe_cart_before
         *
         * @hooked none - 10
         */
        do_action('aqualuxe_cart_before');
    }

    function aqualuxe_cart_after() {
        /**
         * Hook: aqualuxe_cart_after
         *
         * @hooked none - 10
         */
        do_action('aqualuxe_cart_after');
    }

    /**
     * Checkout hooks
     */
    function aqualuxe_checkout_before() {
        /**
         * Hook: aqualuxe_checkout_before
         *
         * @hooked none - 10
         */
        do_action('aqualuxe_checkout_before');
    }

    function aqualuxe_checkout_after() {
        /**
         * Hook: aqualuxe_checkout_after
         *
         * @hooked none - 10
         */
        do_action('aqualuxe_checkout_after');
    }

    /**
     * Account hooks
     */
    function aqualuxe_account_before() {
        /**
         * Hook: aqualuxe_account_before
         *
         * @hooked none - 10
         */
        do_action('aqualuxe_account_before');
    }

    function aqualuxe_account_after() {
        /**
         * Hook: aqualuxe_account_after
         *
         * @hooked none - 10
         */
        do_action('aqualuxe_account_after');
    }
}

/**
 * Custom hooks for dark mode
 */
function aqualuxe_dark_mode_before() {
    /**
     * Hook: aqualuxe_dark_mode_before
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_dark_mode_before');
}

function aqualuxe_dark_mode_after() {
    /**
     * Hook: aqualuxe_dark_mode_after
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_dark_mode_after');
}

/**
 * Custom hooks for multilingual support
 */
function aqualuxe_language_switcher_before() {
    /**
     * Hook: aqualuxe_language_switcher_before
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_language_switcher_before');
}

function aqualuxe_language_switcher_after() {
    /**
     * Hook: aqualuxe_language_switcher_after
     *
     * @hooked none - 10
     */
    do_action('aqualuxe_language_switcher_after');
}

/**
 * Add dark mode toggle to header
 */
function aqualuxe_add_dark_mode_toggle() {
    aqualuxe_dark_mode_toggle();
}
add_action('aqualuxe_header', 'aqualuxe_add_dark_mode_toggle', 20);

/**
 * Add language switcher to header
 */
function aqualuxe_add_language_switcher() {
    aqualuxe_language_switcher();
}
add_action('aqualuxe_header', 'aqualuxe_add_language_switcher', 30);

/**
 * Add social links to footer
 */
function aqualuxe_add_social_links() {
    aqualuxe_social_links();
}
add_action('aqualuxe_footer', 'aqualuxe_add_social_links', 20);