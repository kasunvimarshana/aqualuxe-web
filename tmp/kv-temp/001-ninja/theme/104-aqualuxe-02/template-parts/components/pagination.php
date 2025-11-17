<?php
/**
 * Template part for displaying pagination
 *
 * @package AquaLuxe
 */

global $wp_query;

// Don't show pagination if there's only one page
if ($wp_query->max_num_pages <= 1) {
    return;
}

$current_page = max(1, get_query_var('paged'));
$max_pages = $wp_query->max_num_pages;
?>

<nav class="pagination-wrapper py-8" aria-label="<?php esc_attr_e('Posts pagination', 'aqualuxe'); ?>">
    <div class="container mx-auto px-4">
        <?php
        $pagination_args = array(
            'base'      => str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999))),
            'format'    => '?paged=%#%',
            'current'   => $current_page,
            'total'     => $max_pages,
            'prev_text' => '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>' . esc_html__('Previous', 'aqualuxe'),
            'next_text' => esc_html__('Next', 'aqualuxe') . '<svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
            'mid_size'  => 2,
            'end_size'  => 1,
            'type'      => 'array',
        );
        
        $pagination_links = paginate_links($pagination_args);
        
        if ($pagination_links) :
            ?>
            <div class="pagination flex flex-wrap justify-center items-center space-x-1">
                <?php foreach ($pagination_links as $link) : ?>
                    <div class="pagination-item">
                        <?php
                        // Style the pagination links
                        $styled_link = str_replace(
                            array('page-numbers', 'prev', 'next', 'current'),
                            array(
                                'page-numbers inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200 rounded-lg',
                                'prev',
                                'next', 
                                'current inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 border border-primary-600 rounded-lg cursor-default'
                            ),
                            $link
                        );
                        echo $styled_link;
                        ?>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <!-- Pagination Info -->
            <div class="pagination-info text-center mt-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    <?php
                    /* translators: 1: Current page number, 2: Total pages */
                    printf(
                        esc_html__('Page %1$s of %2$s', 'aqualuxe'),
                        number_format_i18n($current_page),
                        number_format_i18n($max_pages)
                    );
                    ?>
                </p>
            </div>
            <?php
        endif;
        ?>
    </div>
</nav>