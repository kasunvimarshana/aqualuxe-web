<?php
/**
 * Pagination Functions
 *
 * Functions for handling pagination throughout the theme.
 *
 * @package AquaLuxe
 */

/**
 * Display pagination for archive pages.
 *
 * @param array $args Pagination arguments.
 */
function aqualuxe_pagination( $args = array() ) {
	$defaults = array(
		'type'               => 'numbered', // numbered, prev_next, load_more, infinite.
		'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>' . __( 'Previous', 'aqualuxe' ),
		'next_text'          => __( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
		'load_more_text'     => __( 'Load More', 'aqualuxe' ),
		'loading_text'       => __( 'Loading...', 'aqualuxe' ),
		'no_more_posts_text' => __( 'No more posts to load', 'aqualuxe' ),
		'container_class'    => 'pagination-container',
		'ul_class'           => 'pagination',
		'li_class'           => 'page-item',
		'a_class'            => 'page-link',
		'active_class'       => 'active',
		'disabled_class'     => 'disabled',
		'show_all'           => false,
		'end_size'           => 1,
		'mid_size'           => 2,
		'add_fragment'       => '',
		'screen_reader_text' => __( 'Posts navigation', 'aqualuxe' ),
		'aria_label'         => __( 'Posts', 'aqualuxe' ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Get the global $wp_query object.
	global $wp_query;

	// Stop execution if there's only one page.
	if ( $wp_query->max_num_pages <= 1 ) {
		return;
	}

	// Set the current page.
	$current = max( 1, get_query_var( 'paged' ) );

	// Get the max number of pages.
	$max_num_pages = $wp_query->max_num_pages;

	// Handle different pagination types.
	switch ( $args['type'] ) {
		case 'numbered':
			aqualuxe_numbered_pagination( $current, $max_num_pages, $args );
			break;

		case 'prev_next':
			aqualuxe_prev_next_pagination( $current, $max_num_pages, $args );
			break;

		case 'load_more':
			aqualuxe_load_more_pagination( $current, $max_num_pages, $args );
			break;

		case 'infinite':
			aqualuxe_infinite_pagination( $current, $max_num_pages, $args );
			break;

		default:
			aqualuxe_numbered_pagination( $current, $max_num_pages, $args );
			break;
	}
}

/**
 * Display numbered pagination.
 *
 * @param int   $current Current page number.
 * @param int   $max_num_pages Maximum number of pages.
 * @param array $args Pagination arguments.
 */
function aqualuxe_numbered_pagination( $current, $max_num_pages, $args ) {
	// Don't print empty markup if there's only one page.
	if ( $max_num_pages < 2 ) {
		return;
	}

	// Set up paginated links.
	$links = paginate_links(
		array(
			'base'      => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
			'format'    => '?paged=%#%',
			'current'   => $current,
			'total'     => $max_num_pages,
			'prev_text' => $args['prev_text'],
			'next_text' => $args['next_text'],
			'type'      => 'array',
			'end_size'  => $args['end_size'],
			'mid_size'  => $args['mid_size'],
			'add_args'  => false,
			'add_fragment' => $args['add_fragment'],
			'show_all'  => $args['show_all'],
		)
	);

	if ( ! $links ) {
		return;
	}

	// Build the pagination HTML.
	$pagination = '<nav class="' . esc_attr( $args['container_class'] ) . '" aria-label="' . esc_attr( $args['aria_label'] ) . '">';
	$pagination .= '<span class="screen-reader-text">' . esc_html( $args['screen_reader_text'] ) . '</span>';
	$pagination .= '<ul class="' . esc_attr( $args['ul_class'] ) . '">';

	foreach ( $links as $link ) {
		$is_current = strpos( $link, 'current' ) !== false;
		$is_dots = strpos( $link, 'dots' ) !== false;
		$is_disabled = strpos( $link, 'disabled' ) !== false;

		$li_class = $args['li_class'];
		
		if ( $is_current ) {
			$li_class .= ' ' . $args['active_class'];
		}
		
		if ( $is_disabled ) {
			$li_class .= ' ' . $args['disabled_class'];
		}

		$pagination .= '<li class="' . esc_attr( $li_class ) . '">';
		
		if ( $is_dots ) {
			$pagination .= '<span class="' . esc_attr( $args['a_class'] ) . '">' . $link . '</span>';
		} else {
			// Replace default classes with our custom classes.
			$link = str_replace( 'page-numbers', $args['a_class'], $link );
			$link = str_replace( 'current', $args['active_class'], $link );
			$link = str_replace( 'dots', '', $link );
			
			$pagination .= $link;
		}
		
		$pagination .= '</li>';
	}

	$pagination .= '</ul>';
	$pagination .= '</nav>';

	echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display previous/next pagination.
 *
 * @param int   $current Current page number.
 * @param int   $max_num_pages Maximum number of pages.
 * @param array $args Pagination arguments.
 */
function aqualuxe_prev_next_pagination( $current, $max_num_pages, $args ) {
	// Don't print empty markup if there's only one page.
	if ( $max_num_pages < 2 ) {
		return;
	}

	// Build the pagination HTML.
	$pagination = '<nav class="' . esc_attr( $args['container_class'] ) . '" aria-label="' . esc_attr( $args['aria_label'] ) . '">';
	$pagination .= '<span class="screen-reader-text">' . esc_html( $args['screen_reader_text'] ) . '</span>';
	$pagination .= '<ul class="' . esc_attr( $args['ul_class'] ) . ' flex justify-between">';

	// Previous link.
	$pagination .= '<li class="' . esc_attr( $args['li_class'] ) . ( $current <= 1 ? ' ' . $args['disabled_class'] : '' ) . '">';
	if ( $current <= 1 ) {
		$pagination .= '<span class="' . esc_attr( $args['a_class'] ) . ' opacity-50 cursor-not-allowed">' . $args['prev_text'] . '</span>';
	} else {
		$pagination .= '<a href="' . esc_url( get_pagenum_link( $current - 1 ) ) . '" class="' . esc_attr( $args['a_class'] ) . '">' . $args['prev_text'] . '</a>';
	}
	$pagination .= '</li>';

	// Next link.
	$pagination .= '<li class="' . esc_attr( $args['li_class'] ) . ( $current >= $max_num_pages ? ' ' . $args['disabled_class'] : '' ) . '">';
	if ( $current >= $max_num_pages ) {
		$pagination .= '<span class="' . esc_attr( $args['a_class'] ) . ' opacity-50 cursor-not-allowed">' . $args['next_text'] . '</span>';
	} else {
		$pagination .= '<a href="' . esc_url( get_pagenum_link( $current + 1 ) ) . '" class="' . esc_attr( $args['a_class'] ) . '">' . $args['next_text'] . '</a>';
	}
	$pagination .= '</li>';

	$pagination .= '</ul>';
	$pagination .= '</nav>';

	echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display load more pagination.
 *
 * @param int   $current Current page number.
 * @param int   $max_num_pages Maximum number of pages.
 * @param array $args Pagination arguments.
 */
function aqualuxe_load_more_pagination( $current, $max_num_pages, $args ) {
	// Don't print empty markup if there's only one page.
	if ( $max_num_pages < 2 ) {
		return;
	}

	// Get the current query.
	global $wp_query;

	// Build the pagination HTML.
	$pagination = '<div class="' . esc_attr( $args['container_class'] ) . ' text-center mt-8" data-max-pages="' . esc_attr( $max_num_pages ) . '" data-current-page="' . esc_attr( $current ) . '">';
	
	if ( $current < $max_num_pages ) {
		$pagination .= '<button id="load-more-button" class="load-more-button bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-6 rounded-md transition-colors duration-200 flex items-center mx-auto">';
		$pagination .= '<span class="button-text">' . esc_html( $args['load_more_text'] ) . '</span>';
		$pagination .= '<span class="loading-text hidden">' . esc_html( $args['loading_text'] ) . '</span>';
		$pagination .= '<svg class="animate-spin ml-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">';
		$pagination .= '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>';
		$pagination .= '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
		$pagination .= '</svg>';
		$pagination .= '</button>';
	} else {
		$pagination .= '<p class="no-more-posts text-gray-500 dark:text-gray-400">' . esc_html( $args['no_more_posts_text'] ) . '</p>';
	}
	
	$pagination .= '</div>';

	// Add the necessary JavaScript.
	$pagination .= '<script>
		document.addEventListener("DOMContentLoaded", function() {
			const loadMoreButton = document.getElementById("load-more-button");
			if (loadMoreButton) {
				loadMoreButton.addEventListener("click", function() {
					const container = this.closest(".' . esc_js( $args['container_class'] ) . '");
					const currentPage = parseInt(container.dataset.currentPage);
					const maxPages = parseInt(container.dataset.maxPages);
					
					if (currentPage < maxPages) {
						// Show loading state
						this.classList.add("loading");
						this.querySelector(".button-text").classList.add("hidden");
						this.querySelector(".loading-text").classList.remove("hidden");
						this.querySelector("svg").classList.remove("hidden");
						
						// Prepare the AJAX request
						const xhr = new XMLHttpRequest();
						xhr.open("POST", "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '", true);
						xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						
						xhr.onload = function() {
							if (xhr.status >= 200 && xhr.status < 400) {
								const response = JSON.parse(xhr.responseText);
								
								if (response.success) {
									// Append the new posts
									const postsContainer = document.querySelector(".posts-container");
									if (postsContainer) {
										postsContainer.insertAdjacentHTML("beforeend", response.data.html);
									}
									
									// Update the current page
									container.dataset.currentPage = currentPage + 1;
									
									// Hide the button if we\'ve reached the max pages
									if (currentPage + 1 >= maxPages) {
										loadMoreButton.remove();
										container.insertAdjacentHTML("beforeend", "<p class=&quot;no-more-posts text-gray-500 dark:text-gray-400&quot;>' . esc_js( $args['no_more_posts_text'] ) . '</p>");
									} else {
										// Reset the button state
										loadMoreButton.classList.remove("loading");
										loadMoreButton.querySelector(".button-text").classList.remove("hidden");
										loadMoreButton.querySelector(".loading-text").classList.add("hidden");
										loadMoreButton.querySelector("svg").classList.add("hidden");
									}
								}
							}
						};
						
						xhr.onerror = function() {
							// Reset the button state on error
							loadMoreButton.classList.remove("loading");
							loadMoreButton.querySelector(".button-text").classList.remove("hidden");
							loadMoreButton.querySelector(".loading-text").classList.add("hidden");
							loadMoreButton.querySelector("svg").classList.add("hidden");
						};
						
						// Send the AJAX request
						xhr.send("action=aqualuxe_load_more&nonce=' . esc_js( wp_create_nonce( 'aqualuxe_load_more_nonce' ) ) . '&paged=" + (currentPage + 1) + "&query=" + encodeURIComponent(JSON.stringify(' . wp_json_encode( $wp_query->query_vars ) . ')));
					}
				});
			}
		});
	</script>';

	echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display infinite scroll pagination.
 *
 * @param int   $current Current page number.
 * @param int   $max_num_pages Maximum number of pages.
 * @param array $args Pagination arguments.
 */
function aqualuxe_infinite_pagination( $current, $max_num_pages, $args ) {
	// Don't print empty markup if there's only one page.
	if ( $max_num_pages < 2 ) {
		return;
	}

	// Get the current query.
	global $wp_query;

	// Build the pagination HTML.
	$pagination = '<div class="' . esc_attr( $args['container_class'] ) . ' infinite-scroll-container text-center mt-8" data-max-pages="' . esc_attr( $max_num_pages ) . '" data-current-page="' . esc_attr( $current ) . '">';
	
	if ( $current < $max_num_pages ) {
		$pagination .= '<div class="infinite-scroll-status">';
		$pagination .= '<div class="infinite-scroll-request flex justify-center items-center py-4 hidden">';
		$pagination .= '<svg class="animate-spin h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">';
		$pagination .= '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>';
		$pagination .= '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
		$pagination .= '</svg>';
		$pagination .= '<span class="ml-2">' . esc_html( $args['loading_text'] ) . '</span>';
		$pagination .= '</div>';
		$pagination .= '<div class="infinite-scroll-error hidden">';
		$pagination .= '<p class="text-gray-500 dark:text-gray-400">' . esc_html__( 'Error loading posts. Please try again.', 'aqualuxe' ) . '</p>';
		$pagination .= '</div>';
		$pagination .= '</div>';
		$pagination .= '<div class="infinite-scroll-trigger" data-threshold="100"></div>';
	} else {
		$pagination .= '<p class="no-more-posts text-gray-500 dark:text-gray-400">' . esc_html( $args['no_more_posts_text'] ) . '</p>';
	}
	
	$pagination .= '</div>';

	// Add the necessary JavaScript.
	$pagination .= '<script>
		document.addEventListener("DOMContentLoaded", function() {
			const infiniteScrollContainer = document.querySelector(".infinite-scroll-container");
			if (infiniteScrollContainer) {
				const infiniteScrollTrigger = infiniteScrollContainer.querySelector(".infinite-scroll-trigger");
				const infiniteScrollRequest = infiniteScrollContainer.querySelector(".infinite-scroll-request");
				const infiniteScrollError = infiniteScrollContainer.querySelector(".infinite-scroll-error");
				
				if (infiniteScrollTrigger) {
					const threshold = parseInt(infiniteScrollTrigger.dataset.threshold) || 100;
					let currentPage = parseInt(infiniteScrollContainer.dataset.currentPage);
					const maxPages = parseInt(infiniteScrollContainer.dataset.maxPages);
					let loading = false;
					
					function handleInfiniteScroll() {
						if (loading || currentPage >= maxPages) return;
						
						const triggerRect = infiniteScrollTrigger.getBoundingClientRect();
						const triggerVisible = triggerRect.top <= window.innerHeight + threshold;
						
						if (triggerVisible) {
							loading = true;
							infiniteScrollRequest.classList.remove("hidden");
							
							// Prepare the AJAX request
							const xhr = new XMLHttpRequest();
							xhr.open("POST", "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '", true);
							xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
							
							xhr.onload = function() {
								if (xhr.status >= 200 && xhr.status < 400) {
									const response = JSON.parse(xhr.responseText);
									
									if (response.success) {
										// Append the new posts
										const postsContainer = document.querySelector(".posts-container");
										if (postsContainer) {
											postsContainer.insertAdjacentHTML("beforeend", response.data.html);
										}
										
										// Update the current page
										currentPage++;
										infiniteScrollContainer.dataset.currentPage = currentPage;
										
										// Hide the loading indicator
										infiniteScrollRequest.classList.add("hidden");
										
										// Remove the trigger if we\'ve reached the max pages
										if (currentPage >= maxPages) {
											infiniteScrollTrigger.remove();
											infiniteScrollContainer.insertAdjacentHTML("beforeend", "<p class=&quot;no-more-posts text-gray-500 dark:text-gray-400&quot;>' . esc_js( $args['no_more_posts_text'] ) . '</p>");
										}
										
										loading = false;
									} else {
										// Show error
										infiniteScrollRequest.classList.add("hidden");
										infiniteScrollError.classList.remove("hidden");
										loading = false;
									}
								}
							};
							
							xhr.onerror = function() {
								// Show error
								infiniteScrollRequest.classList.add("hidden");
								infiniteScrollError.classList.remove("hidden");
								loading = false;
							};
							
							// Send the AJAX request
							xhr.send("action=aqualuxe_load_more&nonce=' . esc_js( wp_create_nonce( 'aqualuxe_load_more_nonce' ) ) . '&paged=" + (currentPage + 1) + "&query=" + encodeURIComponent(JSON.stringify(' . wp_json_encode( $wp_query->query_vars ) . ')));
						}
					}
					
					// Initial check
					handleInfiniteScroll();
					
					// Add scroll event listener
					window.addEventListener("scroll", handleInfiniteScroll);
					window.addEventListener("resize", handleInfiniteScroll);
				}
			}
		});
	</script>';

	echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * AJAX handler for load more and infinite scroll pagination.
 */
function aqualuxe_load_more_ajax_handler() {
	// Check the nonce.
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_load_more_nonce' ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'aqualuxe' ) ) );
	}

	// Get the page number.
	$paged = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

	// Get the query.
	$query_vars = isset( $_POST['query'] ) ? json_decode( wp_unslash( $_POST['query'] ), true ) : array();
	$query_vars['paged'] = $paged;
	$query_vars['post_status'] = 'publish';

	// Run the query.
	$posts = new WP_Query( $query_vars );

	ob_start();

	if ( $posts->have_posts() ) {
		while ( $posts->have_posts() ) {
			$posts->the_post();
			get_template_part( 'template-parts/content/content', get_post_type() );
		}
	}

	$html = ob_get_clean();

	wp_reset_postdata();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_aqualuxe_load_more', 'aqualuxe_load_more_ajax_handler' );
add_action( 'wp_ajax_nopriv_aqualuxe_load_more', 'aqualuxe_load_more_ajax_handler' );

/**
 * Display post navigation.
 *
 * @param array $args Post navigation arguments.
 */
function aqualuxe_post_navigation( $args = array() ) {
	$defaults = array(
		'prev_text'          => '<span class="nav-subtitle">' . __( 'Previous Post', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
		'next_text'          => '<span class="nav-subtitle">' . __( 'Next Post', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
		'in_same_term'       => false,
		'excluded_terms'     => '',
		'taxonomy'           => 'category',
		'screen_reader_text' => __( 'Post navigation', 'aqualuxe' ),
		'aria_label'         => __( 'Posts', 'aqualuxe' ),
		'class'              => 'post-navigation',
	);

	$args = wp_parse_args( $args, $defaults );

	$navigation = '';

	$previous = get_previous_post( $args['in_same_term'], $args['excluded_terms'], $args['taxonomy'] );
	$next = get_next_post( $args['in_same_term'], $args['excluded_terms'], $args['taxonomy'] );

	if ( ! $next && ! $previous ) {
		return;
	}

	$navigation = '<nav class="' . esc_attr( $args['class'] ) . ' border-t border-gray-200 dark:border-dark-700 mt-8 pt-8" aria-label="' . esc_attr( $args['aria_label'] ) . '">';
	$navigation .= '<h2 class="screen-reader-text">' . esc_html( $args['screen_reader_text'] ) . '</h2>';
	$navigation .= '<div class="flex flex-col sm:flex-row justify-between">';

	if ( $previous ) {
		$previous_link = get_previous_post_link(
			'%link',
			$args['prev_text'],
			$args['in_same_term'],
			$args['excluded_terms'],
			$args['taxonomy']
		);

		if ( $previous_link ) {
			$navigation .= '<div class="nav-previous mb-4 sm:mb-0">';
			$navigation .= str_replace( '<a href=', '<a class="group flex flex-col hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" href=', $previous_link );
			$navigation .= '</div>';
		}
	}

	if ( $next ) {
		$next_link = get_next_post_link(
			'%link',
			$args['next_text'],
			$args['in_same_term'],
			$args['excluded_terms'],
			$args['taxonomy']
		);

		if ( $next_link ) {
			$navigation .= '<div class="nav-next text-right">';
			$navigation .= str_replace( '<a href=', '<a class="group flex flex-col hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" href=', $next_link );
			$navigation .= '</div>';
		}
	}

	$navigation .= '</div>';
	$navigation .= '</nav>';

	echo $navigation; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display comments pagination.
 *
 * @param array $args Comments pagination arguments.
 */
function aqualuxe_comments_pagination( $args = array() ) {
	$defaults = array(
		'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>' . __( 'Previous', 'aqualuxe' ),
		'next_text'          => __( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
		'container_class'    => 'comments-pagination-container',
		'ul_class'           => 'comments-pagination',
		'li_class'           => 'page-item',
		'a_class'            => 'page-link',
		'active_class'       => 'active',
		'disabled_class'     => 'disabled',
		'show_all'           => false,
		'end_size'           => 1,
		'mid_size'           => 2,
		'add_fragment'       => '',
		'screen_reader_text' => __( 'Comments navigation', 'aqualuxe' ),
		'aria_label'         => __( 'Comments', 'aqualuxe' ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Get the paginated links.
	$links = paginate_comments_links(
		array(
			'prev_text' => $args['prev_text'],
			'next_text' => $args['next_text'],
			'type'      => 'array',
			'echo'      => false,
			'end_size'  => $args['end_size'],
			'mid_size'  => $args['mid_size'],
			'add_fragment' => $args['add_fragment'],
			'show_all'  => $args['show_all'],
		)
	);

	if ( ! $links ) {
		return;
	}

	// Build the pagination HTML.
	$pagination = '<nav class="' . esc_attr( $args['container_class'] ) . ' mt-8" aria-label="' . esc_attr( $args['aria_label'] ) . '">';
	$pagination .= '<span class="screen-reader-text">' . esc_html( $args['screen_reader_text'] ) . '</span>';
	$pagination .= '<ul class="' . esc_attr( $args['ul_class'] ) . ' flex flex-wrap justify-center">';

	foreach ( $links as $link ) {
		$is_current = strpos( $link, 'current' ) !== false;
		$is_dots = strpos( $link, 'dots' ) !== false;
		$is_disabled = strpos( $link, 'disabled' ) !== false;

		$li_class = $args['li_class'];
		
		if ( $is_current ) {
			$li_class .= ' ' . $args['active_class'];
		}
		
		if ( $is_disabled ) {
			$li_class .= ' ' . $args['disabled_class'];
		}

		$pagination .= '<li class="' . esc_attr( $li_class ) . '">';
		
		if ( $is_dots ) {
			$pagination .= '<span class="' . esc_attr( $args['a_class'] ) . '">' . $link . '</span>';
		} else {
			// Replace default classes with our custom classes.
			$link = str_replace( 'page-numbers', $args['a_class'], $link );
			$link = str_replace( 'current', $args['active_class'], $link );
			$link = str_replace( 'dots', '', $link );
			
			$pagination .= $link;
		}
		
		$pagination .= '</li>';
	}

	$pagination .= '</ul>';
	$pagination .= '</nav>';

	echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display WooCommerce pagination.
 *
 * @param array $args WooCommerce pagination arguments.
 */
function aqualuxe_woocommerce_pagination( $args = array() ) {
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}

	$defaults = array(
		'type'               => 'numbered', // numbered, prev_next, load_more, infinite.
		'prev_text'          => '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>' . __( 'Previous', 'aqualuxe' ),
		'next_text'          => __( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" /></svg>',
		'load_more_text'     => __( 'Load More Products', 'aqualuxe' ),
		'loading_text'       => __( 'Loading...', 'aqualuxe' ),
		'no_more_posts_text' => __( 'No more products to load', 'aqualuxe' ),
		'container_class'    => 'woocommerce-pagination-container',
		'ul_class'           => 'woocommerce-pagination',
		'li_class'           => 'page-item',
		'a_class'            => 'page-link',
		'active_class'       => 'active',
		'disabled_class'     => 'disabled',
		'show_all'           => false,
		'end_size'           => 1,
		'mid_size'           => 2,
		'add_fragment'       => '',
		'screen_reader_text' => __( 'Products navigation', 'aqualuxe' ),
		'aria_label'         => __( 'Products', 'aqualuxe' ),
	);

	$args = wp_parse_args( $args, $defaults );

	// Handle different pagination types.
	switch ( $args['type'] ) {
		case 'numbered':
			woocommerce_pagination();
			break;

		case 'prev_next':
			aqualuxe_woocommerce_prev_next_pagination( $args );
			break;

		case 'load_more':
			aqualuxe_woocommerce_load_more_pagination( $args );
			break;

		case 'infinite':
			aqualuxe_woocommerce_infinite_pagination( $args );
			break;

		default:
			woocommerce_pagination();
			break;
	}
}

/**
 * Display WooCommerce previous/next pagination.
 *
 * @param array $args WooCommerce pagination arguments.
 */
function aqualuxe_woocommerce_prev_next_pagination( $args ) {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	// Set the current page.
	$current = max( 1, get_query_var( 'paged' ) );

	// Get the max number of pages.
	$max_num_pages = $wp_query->max_num_pages;

	// Build the pagination HTML.
	$pagination = '<nav class="' . esc_attr( $args['container_class'] ) . '" aria-label="' . esc_attr( $args['aria_label'] ) . '">';
	$pagination .= '<span class="screen-reader-text">' . esc_html( $args['screen_reader_text'] ) . '</span>';
	$pagination .= '<ul class="' . esc_attr( $args['ul_class'] ) . ' flex justify-between">';

	// Previous link.
	$pagination .= '<li class="' . esc_attr( $args['li_class'] ) . ( $current <= 1 ? ' ' . $args['disabled_class'] : '' ) . '">';
	if ( $current <= 1 ) {
		$pagination .= '<span class="' . esc_attr( $args['a_class'] ) . ' opacity-50 cursor-not-allowed">' . $args['prev_text'] . '</span>';
	} else {
		$pagination .= '<a href="' . esc_url( get_pagenum_link( $current - 1 ) ) . '" class="' . esc_attr( $args['a_class'] ) . '">' . $args['prev_text'] . '</a>';
	}
	$pagination .= '</li>';

	// Next link.
	$pagination .= '<li class="' . esc_attr( $args['li_class'] ) . ( $current >= $max_num_pages ? ' ' . $args['disabled_class'] : '' ) . '">';
	if ( $current >= $max_num_pages ) {
		$pagination .= '<span class="' . esc_attr( $args['a_class'] ) . ' opacity-50 cursor-not-allowed">' . $args['next_text'] . '</span>';
	} else {
		$pagination .= '<a href="' . esc_url( get_pagenum_link( $current + 1 ) ) . '" class="' . esc_attr( $args['a_class'] ) . '">' . $args['next_text'] . '</a>';
	}
	$pagination .= '</li>';

	$pagination .= '</ul>';
	$pagination .= '</nav>';

	echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display WooCommerce load more pagination.
 *
 * @param array $args WooCommerce pagination arguments.
 */
function aqualuxe_woocommerce_load_more_pagination( $args ) {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	// Set the current page.
	$current = max( 1, get_query_var( 'paged' ) );

	// Get the max number of pages.
	$max_num_pages = $wp_query->max_num_pages;

	// Build the pagination HTML.
	$pagination = '<div class="' . esc_attr( $args['container_class'] ) . ' text-center mt-8" data-max-pages="' . esc_attr( $max_num_pages ) . '" data-current-page="' . esc_attr( $current ) . '">';
	
	if ( $current < $max_num_pages ) {
		$pagination .= '<button id="load-more-products-button" class="load-more-button bg-primary-600 hover:bg-primary-700 text-white font-medium py-3 px-6 rounded-md transition-colors duration-200 flex items-center mx-auto">';
		$pagination .= '<span class="button-text">' . esc_html( $args['load_more_text'] ) . '</span>';
		$pagination .= '<span class="loading-text hidden">' . esc_html( $args['loading_text'] ) . '</span>';
		$pagination .= '<svg class="animate-spin ml-2 h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">';
		$pagination .= '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>';
		$pagination .= '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
		$pagination .= '</svg>';
		$pagination .= '</button>';
	} else {
		$pagination .= '<p class="no-more-posts text-gray-500 dark:text-gray-400">' . esc_html( $args['no_more_posts_text'] ) . '</p>';
	}
	
	$pagination .= '</div>';

	// Add the necessary JavaScript.
	$pagination .= '<script>
		document.addEventListener("DOMContentLoaded", function() {
			const loadMoreButton = document.getElementById("load-more-products-button");
			if (loadMoreButton) {
				loadMoreButton.addEventListener("click", function() {
					const container = this.closest(".' . esc_js( $args['container_class'] ) . '");
					const currentPage = parseInt(container.dataset.currentPage);
					const maxPages = parseInt(container.dataset.maxPages);
					
					if (currentPage < maxPages) {
						// Show loading state
						this.classList.add("loading");
						this.querySelector(".button-text").classList.add("hidden");
						this.querySelector(".loading-text").classList.remove("hidden");
						this.querySelector("svg").classList.remove("hidden");
						
						// Prepare the AJAX request
						const xhr = new XMLHttpRequest();
						xhr.open("POST", "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '", true);
						xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
						
						xhr.onload = function() {
							if (xhr.status >= 200 && xhr.status < 400) {
								const response = JSON.parse(xhr.responseText);
								
								if (response.success) {
									// Append the new products
									const productsContainer = document.querySelector(".products");
									if (productsContainer) {
										productsContainer.insertAdjacentHTML("beforeend", response.data.html);
									}
									
									// Update the current page
									container.dataset.currentPage = currentPage + 1;
									
									// Hide the button if we\'ve reached the max pages
									if (currentPage + 1 >= maxPages) {
										loadMoreButton.remove();
										container.insertAdjacentHTML("beforeend", "<p class=&quot;no-more-posts text-gray-500 dark:text-gray-400&quot;>' . esc_js( $args['no_more_posts_text'] ) . '</p>");
									} else {
										// Reset the button state
										loadMoreButton.classList.remove("loading");
										loadMoreButton.querySelector(".button-text").classList.remove("hidden");
										loadMoreButton.querySelector(".loading-text").classList.add("hidden");
										loadMoreButton.querySelector("svg").classList.add("hidden");
									}
								}
							}
						};
						
						xhr.onerror = function() {
							// Reset the button state on error
							loadMoreButton.classList.remove("loading");
							loadMoreButton.querySelector(".button-text").classList.remove("hidden");
							loadMoreButton.querySelector(".loading-text").classList.add("hidden");
							loadMoreButton.querySelector("svg").classList.add("hidden");
						};
						
						// Send the AJAX request
						xhr.send("action=aqualuxe_load_more_products&nonce=' . esc_js( wp_create_nonce( 'aqualuxe_load_more_products_nonce' ) ) . '&paged=" + (currentPage + 1));
					}
				});
			}
		});
	</script>';

	echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display WooCommerce infinite scroll pagination.
 *
 * @param array $args WooCommerce pagination arguments.
 */
function aqualuxe_woocommerce_infinite_pagination( $args ) {
	global $wp_query;

	// Don't print empty markup if there's only one page.
	if ( $wp_query->max_num_pages < 2 ) {
		return;
	}

	// Set the current page.
	$current = max( 1, get_query_var( 'paged' ) );

	// Get the max number of pages.
	$max_num_pages = $wp_query->max_num_pages;

	// Build the pagination HTML.
	$pagination = '<div class="' . esc_attr( $args['container_class'] ) . ' infinite-scroll-container text-center mt-8" data-max-pages="' . esc_attr( $max_num_pages ) . '" data-current-page="' . esc_attr( $current ) . '">';
	
	if ( $current < $max_num_pages ) {
		$pagination .= '<div class="infinite-scroll-status">';
		$pagination .= '<div class="infinite-scroll-request flex justify-center items-center py-4 hidden">';
		$pagination .= '<svg class="animate-spin h-6 w-6 text-primary-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">';
		$pagination .= '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>';
		$pagination .= '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>';
		$pagination .= '</svg>';
		$pagination .= '<span class="ml-2">' . esc_html( $args['loading_text'] ) . '</span>';
		$pagination .= '</div>';
		$pagination .= '<div class="infinite-scroll-error hidden">';
		$pagination .= '<p class="text-gray-500 dark:text-gray-400">' . esc_html__( 'Error loading products. Please try again.', 'aqualuxe' ) . '</p>';
		$pagination .= '</div>';
		$pagination .= '</div>';
		$pagination .= '<div class="infinite-scroll-trigger" data-threshold="100"></div>';
	} else {
		$pagination .= '<p class="no-more-posts text-gray-500 dark:text-gray-400">' . esc_html( $args['no_more_posts_text'] ) . '</p>';
	}
	
	$pagination .= '</div>';

	// Add the necessary JavaScript.
	$pagination .= '<script>
		document.addEventListener("DOMContentLoaded", function() {
			const infiniteScrollContainer = document.querySelector(".infinite-scroll-container");
			if (infiniteScrollContainer) {
				const infiniteScrollTrigger = infiniteScrollContainer.querySelector(".infinite-scroll-trigger");
				const infiniteScrollRequest = infiniteScrollContainer.querySelector(".infinite-scroll-request");
				const infiniteScrollError = infiniteScrollContainer.querySelector(".infinite-scroll-error");
				
				if (infiniteScrollTrigger) {
					const threshold = parseInt(infiniteScrollTrigger.dataset.threshold) || 100;
					let currentPage = parseInt(infiniteScrollContainer.dataset.currentPage);
					const maxPages = parseInt(infiniteScrollContainer.dataset.maxPages);
					let loading = false;
					
					function handleInfiniteScroll() {
						if (loading || currentPage >= maxPages) return;
						
						const triggerRect = infiniteScrollTrigger.getBoundingClientRect();
						const triggerVisible = triggerRect.top <= window.innerHeight + threshold;
						
						if (triggerVisible) {
							loading = true;
							infiniteScrollRequest.classList.remove("hidden");
							
							// Prepare the AJAX request
							const xhr = new XMLHttpRequest();
							xhr.open("POST", "' . esc_url( admin_url( 'admin-ajax.php' ) ) . '", true);
							xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
							
							xhr.onload = function() {
								if (xhr.status >= 200 && xhr.status < 400) {
									const response = JSON.parse(xhr.responseText);
									
									if (response.success) {
										// Append the new products
										const productsContainer = document.querySelector(".products");
										if (productsContainer) {
											productsContainer.insertAdjacentHTML("beforeend", response.data.html);
										}
										
										// Update the current page
										currentPage++;
										infiniteScrollContainer.dataset.currentPage = currentPage;
										
										// Hide the loading indicator
										infiniteScrollRequest.classList.add("hidden");
										
										// Remove the trigger if we\'ve reached the max pages
										if (currentPage >= maxPages) {
											infiniteScrollTrigger.remove();
											infiniteScrollContainer.insertAdjacentHTML("beforeend", "<p class=&quot;no-more-posts text-gray-500 dark:text-gray-400&quot;>' . esc_js( $args['no_more_posts_text'] ) . '</p>");
										}
										
										loading = false;
									} else {
										// Show error
										infiniteScrollRequest.classList.add("hidden");
										infiniteScrollError.classList.remove("hidden");
										loading = false;
									}
								}
							};
							
							xhr.onerror = function() {
								// Show error
								infiniteScrollRequest.classList.add("hidden");
								infiniteScrollError.classList.remove("hidden");
								loading = false;
							};
							
							// Send the AJAX request
							xhr.send("action=aqualuxe_load_more_products&nonce=' . esc_js( wp_create_nonce( 'aqualuxe_load_more_products_nonce' ) ) . '&paged=" + (currentPage + 1));
						}
					}
					
					// Initial check
					handleInfiniteScroll();
					
					// Add scroll event listener
					window.addEventListener("scroll", handleInfiniteScroll);
					window.addEventListener("resize", handleInfiniteScroll);
				}
			}
		});
	</script>';

	echo $pagination; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * AJAX handler for WooCommerce load more and infinite scroll pagination.
 */
function aqualuxe_load_more_products_ajax_handler() {
	// Check the nonce.
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'aqualuxe_load_more_products_nonce' ) ) {
		wp_send_json_error( array( 'message' => __( 'Invalid nonce', 'aqualuxe' ) ) );
	}

	// Get the page number.
	$paged = isset( $_POST['paged'] ) ? absint( $_POST['paged'] ) : 1;

	// Set up the query.
	$args = array(
		'post_type'      => 'product',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'paged'          => $paged,
		'post_status'    => 'publish',
	);

	// Add category filter if set.
	if ( isset( $_POST['category'] ) ) {
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'product_cat',
				'field'    => 'slug',
				'terms'    => sanitize_text_field( wp_unslash( $_POST['category'] ) ),
			),
		);
	}

	// Run the query.
	$products = new WP_Query( $args );

	ob_start();

	if ( $products->have_posts() ) {
		while ( $products->have_posts() ) {
			$products->the_post();
			wc_get_template_part( 'content', 'product' );
		}
	}

	$html = ob_get_clean();

	wp_reset_postdata();

	wp_send_json_success( array( 'html' => $html ) );
}
add_action( 'wp_ajax_aqualuxe_load_more_products', 'aqualuxe_load_more_products_ajax_handler' );
add_action( 'wp_ajax_nopriv_aqualuxe_load_more_products', 'aqualuxe_load_more_products_ajax_handler' );