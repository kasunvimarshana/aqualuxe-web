<?php
/**
 * Template part for displaying the pagination on the Blog page
 *
 * @package AquaLuxe
 */

global $wp_query;

// Don't print empty markup if there's only one page
if ( $wp_query->max_num_pages < 2 ) {
    return;
}

$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
$pagenum_link = html_entity_decode( get_pagenum_link() );
$query_args   = array();
$url_parts    = explode( '?', $pagenum_link );

if ( isset( $url_parts[1] ) ) {
    wp_parse_str( $url_parts[1], $query_args );
}

$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

$links = paginate_links( array(
    'base'      => $pagenum_link,
    'format'    => $format,
    'total'     => $wp_query->max_num_pages,
    'current'   => $paged,
    'mid_size'  => 1,
    'add_args'  => array_map( 'urlencode', $query_args ),
    'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>',
    'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
    'type'      => 'array',
) );

if ( ! empty( $links ) ) :
?>

<nav class="pagination flex justify-center" aria-label="<?php esc_attr_e( 'Pagination', 'aqualuxe' ); ?>">
    <ul class="inline-flex items-center -space-x-px">
        <?php foreach ( $links as $key => $link ) : ?>
            <li>
                <?php
                $link = str_replace( 'page-numbers', 'page-numbers px-3 py-2 leading-tight text-gray-500 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-200', $link );
                $link = str_replace( 'current', 'current bg-teal-600 text-white border-teal-600 dark:bg-teal-700 dark:border-teal-700 hover:bg-teal-700 dark:hover:bg-teal-800 hover:text-white', $link );
                
                echo wp_kses_post( $link );
                ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php
endif;
?>