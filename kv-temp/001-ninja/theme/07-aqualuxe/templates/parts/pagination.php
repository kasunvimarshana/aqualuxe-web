<?php
/**
 * Template part for displaying pagination
 *
 * @package AquaLuxe
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$prev_text = '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>' . esc_html__( 'Previous', 'aqualuxe' );
$next_text = esc_html__( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>';

$pagination_type = get_theme_mod( 'aqualuxe_pagination_type', 'numbered' );

if ( 'numbered' === $pagination_type ) {
	// Numbered pagination
	the_posts_pagination(
		array(
			'mid_size'           => 2,
			'prev_text'          => $prev_text,
			'next_text'          => $next_text,
			'screen_reader_text' => esc_html__( 'Posts navigation', 'aqualuxe' ),
			'class'              => 'pagination',
			'before_page_number' => '<span class="meta-nav screen-reader-text">' . esc_html__( 'Page', 'aqualuxe' ) . ' </span>',
		)
	);
} else {
	// Previous/Next pagination
	the_posts_navigation(
		array(
			'prev_text'          => $prev_text,
			'next_text'          => $next_text,
			'screen_reader_text' => esc_html__( 'Posts navigation', 'aqualuxe' ),
		)
	);
}
?>

<style>
	/* Custom pagination styles */
	.pagination {
		margin-top: 2rem;
	}
	
	.pagination .nav-links {
		display: flex;
		flex-wrap: wrap;
		justify-content: center;
		align-items: center;
	}
	
	.pagination .page-numbers {
		display: inline-flex;
		align-items: center;
		justify-content: center;
		min-width: 2.5rem;
		height: 2.5rem;
		margin: 0 0.25rem;
		padding: 0 0.75rem;
		border-radius: 0.375rem;
		background-color: white;
		color: #1f2937;
		font-weight: 500;
		text-decoration: none;
		transition: all 0.3s ease;
	}
	
	.pagination .page-numbers.current {
		background-color: var(--color-primary, #0ea5e9);
		color: white;
	}
	
	.pagination a.page-numbers:hover {
		background-color: #f3f4f6;
	}
	
	.pagination .prev,
	.pagination .next {
		display: inline-flex;
		align-items: center;
		padding: 0 1rem;
	}
	
	.pagination .dots {
		background-color: transparent;
	}
	
	/* Dark mode styles */
	.dark .pagination .page-numbers {
		background-color: #1f2937;
		color: #e5e7eb;
	}
	
	.dark .pagination .page-numbers.current {
		background-color: var(--color-primary, #0ea5e9);
		color: white;
	}
	
	.dark .pagination a.page-numbers:hover {
		background-color: #374151;
	}
	
	.dark .pagination .dots {
		background-color: transparent;
	}
	
	/* Navigation links for prev/next pagination */
	.posts-navigation {
		margin-top: 2rem;
	}
	
	.posts-navigation .nav-links {
		display: flex;
		justify-content: space-between;
	}
	
	.posts-navigation .nav-previous,
	.posts-navigation .nav-next {
		max-width: 48%;
	}
	
	.posts-navigation .nav-previous a,
	.posts-navigation .nav-next a {
		display: inline-flex;
		align-items: center;
		padding: 0.5rem 1rem;
		border-radius: 0.375rem;
		background-color: white;
		color: #1f2937;
		font-weight: 500;
		text-decoration: none;
		transition: all 0.3s ease;
	}
	
	.posts-navigation .nav-previous a:hover,
	.posts-navigation .nav-next a:hover {
		background-color: #f3f4f6;
	}
	
	/* Dark mode styles for navigation */
	.dark .posts-navigation .nav-previous a,
	.dark .posts-navigation .nav-next a {
		background-color: #1f2937;
		color: #e5e7eb;
	}
	
	.dark .posts-navigation .nav-previous a:hover,
	.dark .posts-navigation .nav-next a:hover {
		background-color: #374151;
	}
</style>