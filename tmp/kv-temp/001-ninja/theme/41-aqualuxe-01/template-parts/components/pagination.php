<?php
/**
 * Template part for displaying pagination
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$prev_text = '<svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>' . __( 'Previous', 'aqualuxe' );
$next_text = __( 'Next', 'aqualuxe' ) . '<svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>';

$pagination_args = array(
	'prev_text'          => $prev_text,
	'next_text'          => $next_text,
	'type'               => 'array',
	'mid_size'           => 2,
	'before_page_number' => '<span class="meta-nav sr-only">' . __( 'Page', 'aqualuxe' ) . ' </span>',
);

$pagination = paginate_links( $pagination_args );

// Return early if there's only one page.
if ( ! $pagination ) {
	return;
}
?>

<nav class="navigation pagination mt-12" role="navigation" aria-label="<?php esc_attr_e( 'Posts', 'aqualuxe' ); ?>">
	<div class="nav-links flex flex-wrap justify-center items-center gap-2">
		<?php
		foreach ( $pagination as $key => $page_link ) {
			// Get the page number from the link
			$page_number = preg_match( '/class="page-numbers[^"]*"[^>]*>(\d+)<\/a>/', $page_link, $matches );
			$is_current = strpos( $page_link, 'current' ) !== false;
			$is_dots = strpos( $page_link, 'dots' ) !== false;
			$is_prev_next = strpos( $page_link, 'prev' ) !== false || strpos( $page_link, 'next' ) !== false;
			
			if ( $is_current ) {
				// Current page
				echo '<span class="page-numbers current bg-primary-600 text-white px-4 py-2 rounded-md flex items-center justify-center min-w-[40px]">' . esc_html( $matches[1] ) . '</span>';
			} elseif ( $is_dots ) {
				// Dots
				echo '<span class="page-numbers dots px-4 py-2 text-dark-500 dark:text-dark-400">…</span>';
			} elseif ( $is_prev_next ) {
				// Previous/Next links
				$class = strpos( $page_link, 'prev' ) !== false ? 'prev' : 'next';
				$modified_link = str_replace( 'page-numbers', 'page-numbers ' . $class . ' flex items-center border border-gray-300 dark:border-dark-600 text-dark-700 dark:text-dark-300 hover:bg-gray-50 dark:hover:bg-dark-700 px-4 py-2 rounded-md transition-colors', $page_link );
				echo $modified_link;
			} else {
				// Regular page numbers
				$modified_link = str_replace( 'page-numbers', 'page-numbers bg-white dark:bg-dark-800 border border-gray-300 dark:border-dark-600 text-dark-700 dark:text-dark-300 hover:bg-gray-50 dark:hover:bg-dark-700 px-4 py-2 rounded-md flex items-center justify-center min-w-[40px] transition-colors', $page_link );
				echo $modified_link;
			}
		}
		?>
	</div>
</nav>