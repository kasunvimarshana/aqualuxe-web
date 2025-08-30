<?php
/**
 * Template part for displaying pagination
 *
 * @package AquaLuxe
 */

// Don't show pagination if there's only one page
if ($GLOBALS['wp_query']->max_num_pages <= 1) {
    return;
}

$pagination_classes = 'pagination flex justify-center mt-8';
$current = max(1, get_query_var('paged'));
$total = $GLOBALS['wp_query']->max_num_pages;

// If we're using a custom query, use that instead
if (isset($args['query']) && $args['query'] instanceof WP_Query) {
    $current = max(1, $args['query']->get('paged'));
    $total = $args['query']->max_num_pages;
}

// Only show pagination if we have more than one page
if ($total <= 1) {
    return;
}
?>

<nav class="<?php echo esc_attr($pagination_classes); ?>" role="navigation" aria-label="<?php esc_attr_e('Pagination', 'aqualuxe'); ?>">
    <?php
    echo paginate_links(
        array(
            'base'         => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format'       => '?paged=%#%',
            'current'      => $current,
            'total'        => $total,
            'prev_text'    => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg><span class="sr-only">' . __('Previous Page', 'aqualuxe') . '</span>',
            'next_text'    => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg><span class="sr-only">' . __('Next Page', 'aqualuxe') . '</span>',
            'type'         => 'list',
            'end_size'     => 2,
            'mid_size'     => 1,
            'add_args'     => false,
            'add_fragment' => '',
            'before_page_number' => '<span class="sr-only">' . __('Page', 'aqualuxe') . ' </span>',
            'after_page_number'  => '',
        )
    );
    ?>
</nav>

<style>
    /* Custom pagination styles */
    .pagination ul {
        @apply flex list-none m-0 p-0;
    }
    
    .pagination li {
        @apply m-0;
    }
    
    .pagination a,
    .pagination span.current,
    .pagination span.dots {
        @apply inline-flex items-center justify-center px-4 py-2 mx-1 text-sm font-medium rounded-md;
    }
    
    .pagination a {
        @apply bg-white dark:bg-secondary-800 border border-secondary-300 dark:border-secondary-700 text-secondary-700 dark:text-white hover:bg-secondary-50 dark:hover:bg-secondary-700;
    }
    
    .pagination span.current {
        @apply bg-primary-500 border-primary-500 text-white;
    }
    
    .pagination span.dots {
        @apply border-0 bg-transparent;
    }
</style>