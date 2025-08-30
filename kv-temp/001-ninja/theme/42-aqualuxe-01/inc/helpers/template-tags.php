<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package AquaLuxe
 */

if ( ! function_exists( 'aqualuxe_posted_on' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function aqualuxe_posted_on() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr( get_the_date( DATE_W3C ) ),
			esc_html( get_the_date() ),
			esc_attr( get_the_modified_date( DATE_W3C ) ),
			esc_html( get_the_modified_date() )
		);

		$posted_on = sprintf(
			/* translators: %s: post date. */
			esc_html_x( '%s', 'post date', 'aqualuxe' ),
			'<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
		);

		echo '<span class="posted-on"><i class="fas fa-calendar-alt mr-1"></i> ' . $posted_on . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'aqualuxe_posted_by' ) ) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function aqualuxe_posted_by() {
		$byline = sprintf(
			/* translators: %s: post author. */
			esc_html_x( '%s', 'post author', 'aqualuxe' ),
			'<span class="author vcard"><a class="url fn n" href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a></span>'
		);

		echo '<span class="byline"><i class="fas fa-user mr-1"></i> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

	}
endif;

if ( ! function_exists( 'aqualuxe_entry_footer' ) ) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function aqualuxe_entry_footer() {
		// Hide category and tag text for pages.
		if ( 'post' === get_post_type() ) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list( esc_html__( ', ', 'aqualuxe' ) );
			if ( $categories_list ) {
				/* translators: 1: list of categories. */
				printf( '<div class="cat-links mb-3"><span class="text-gray-600 mr-2">' . esc_html__( 'Posted in:', 'aqualuxe' ) . '</span> %1$s</div>', $categories_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list( '', esc_html_x( ', ', 'list item separator', 'aqualuxe' ) );
			if ( $tags_list ) {
				/* translators: 1: list of tags. */
				printf( '<div class="tags-links flex flex-wrap items-center"><span class="text-gray-600 mr-2">' . esc_html__( 'Tagged:', 'aqualuxe' ) . '</span> <div class="tag-cloud flex flex-wrap gap-2">%1$s</div></div>', $tags_list ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			}
		}

		if ( ! is_single() && ! post_password_required() && ( comments_open() || get_comments_number() ) ) {
			echo '<span class="comments-link">';
			comments_popup_link(
				sprintf(
					wp_kses(
						/* translators: %s: post title */
						__( 'Leave a Comment<span class="screen-reader-text"> on %s</span>', 'aqualuxe' ),
						array(
							'span' => array(
								'class' => array(),
							),
						)
					),
					wp_kses_post( get_the_title() )
				)
			);
			echo '</span>';
		}

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__( 'Edit <span class="screen-reader-text">%s</span>', 'aqualuxe' ),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				wp_kses_post( get_the_title() )
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if ( ! function_exists( 'aqualuxe_post_thumbnail' ) ) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function aqualuxe_post_thumbnail() {
		if ( post_password_required() || is_attachment() || ! has_post_thumbnail() ) {
			return;
		}

		if ( is_singular() ) :
			?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail( 'full', array( 'class' => 'rounded-lg shadow-lg' ) ); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
					the_post_thumbnail(
						'post-thumbnail',
						array(
							'alt' => the_title_attribute(
								array(
									'echo' => false,
								)
							),
							'class' => 'rounded-lg shadow-md hover:shadow-lg transition-shadow',
						)
					);
				?>
			</a>

			<?php
		endif; // End is_singular().
	}
endif;

if ( ! function_exists( 'wp_body_open' ) ) :
	/**
	 * Shim for sites older than 5.2.
	 *
	 * @link https://core.trac.wordpress.org/ticket/12563
	 */
	function wp_body_open() {
		do_action( 'wp_body_open' );
	}
endif;

/**
 * Display the social sharing buttons
 */
function aqualuxe_social_sharing() {
	// Get current page URL
	$url = urlencode( get_permalink() );
	
	// Get current page title
	$title = urlencode( get_the_title() );
	
	// Get post thumbnail
	$thumbnail = '';
	if ( has_post_thumbnail() ) {
		$thumbnail_id = get_post_thumbnail_id();
		$thumbnail_url = wp_get_attachment_image_src( $thumbnail_id, 'full', true );
		$thumbnail = urlencode( $thumbnail_url[0] );
	}
	
	// Construct sharing URLs
	$facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $url;
	$twitter_url = 'https://twitter.com/intent/tweet?text=' . $title . '&url=' . $url;
	$linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $url . '&title=' . $title;
	$pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $url . '&media=' . $thumbnail . '&description=' . $title;
	$email_url = 'mailto:?subject=' . $title . '&body=' . $url;
	
	// Output sharing buttons
	?>
	<div class="social-sharing flex flex-wrap items-center">
		<span class="mr-4 text-gray-600"><?php esc_html_e( 'Share:', 'aqualuxe' ); ?></span>
		
		<div class="share-buttons flex space-x-2">
			<a href="<?php echo esc_url( $facebook_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-facebook bg-blue-600 hover:bg-blue-700 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors" aria-label="<?php esc_attr_e( 'Share on Facebook', 'aqualuxe' ); ?>">
				<i class="fab fa-facebook-f"></i>
			</a>
			
			<a href="<?php echo esc_url( $twitter_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-twitter bg-blue-400 hover:bg-blue-500 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors" aria-label="<?php esc_attr_e( 'Share on Twitter', 'aqualuxe' ); ?>">
				<i class="fab fa-twitter"></i>
			</a>
			
			<a href="<?php echo esc_url( $linkedin_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-linkedin bg-blue-800 hover:bg-blue-900 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors" aria-label="<?php esc_attr_e( 'Share on LinkedIn', 'aqualuxe' ); ?>">
				<i class="fab fa-linkedin-in"></i>
			</a>
			
			<a href="<?php echo esc_url( $pinterest_url ); ?>" target="_blank" rel="noopener noreferrer" class="share-pinterest bg-red-600 hover:bg-red-700 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors" aria-label="<?php esc_attr_e( 'Share on Pinterest', 'aqualuxe' ); ?>">
				<i class="fab fa-pinterest-p"></i>
			</a>
			
			<a href="<?php echo esc_url( $email_url ); ?>" class="share-email bg-gray-600 hover:bg-gray-700 text-white w-8 h-8 rounded-full flex items-center justify-center transition-colors" aria-label="<?php esc_attr_e( 'Share via Email', 'aqualuxe' ); ?>">
				<i class="fas fa-envelope"></i>
			</a>
		</div>
	</div>
	<?php
}

/**
 * Display post reading time
 */
function aqualuxe_reading_time() {
	$content = get_post_field( 'post_content', get_the_ID() );
	$word_count = str_word_count( strip_tags( $content ) );
	$reading_time = ceil( $word_count / 200 ); // Assuming 200 words per minute reading speed
	
	echo '<span class="reading-time"><i class="fas fa-clock mr-1"></i> ';
	/* translators: %d: Reading time in minutes */
	printf( esc_html( _n( '%d min read', '%d min read', $reading_time, 'aqualuxe' ) ), $reading_time );
	echo '</span>';
}

/**
 * Display post author with avatar
 */
function aqualuxe_post_author_with_avatar() {
	$author_id = get_the_author_meta( 'ID' );
	$author_name = get_the_author_meta( 'display_name' );
	$author_url = get_author_posts_url( $author_id );
	$author_avatar = get_avatar( $author_id, 40, '', '', array( 'class' => 'rounded-full' ) );
	
	?>
	<div class="post-author flex items-center">
		<a href="<?php echo esc_url( $author_url ); ?>" class="author-avatar mr-2">
			<?php echo $author_avatar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
		</a>
		<div class="author-info">
			<a href="<?php echo esc_url( $author_url ); ?>" class="author-name font-medium text-primary-700 hover:text-primary-600 transition-colors">
				<?php echo esc_html( $author_name ); ?>
			</a>
		</div>
	</div>
	<?php
}

/**
 * Display estimated reading time with progress bar
 */
function aqualuxe_reading_progress() {
	if ( ! is_single() ) {
		return;
	}
	
	?>
	<div class="reading-progress fixed top-0 left-0 w-full h-1 bg-gray-200 z-50">
		<div class="reading-progress-bar h-full bg-primary-600 w-0"></div>
	</div>
	<?php
}
add_action( 'wp_footer', 'aqualuxe_reading_progress' );

/**
 * Display post navigation with thumbnails
 */
function aqualuxe_post_navigation_with_thumbnails() {
	$prev_post = get_previous_post();
	$next_post = get_next_post();
	
	if ( ! $prev_post && ! $next_post ) {
		return;
	}
	
	?>
	<nav class="post-navigation flex flex-wrap justify-between mt-8 pt-6 border-t border-gray-200">
		<?php if ( $prev_post ) : ?>
			<div class="post-navigation-prev w-full md:w-1/2 md:pr-4 mb-4 md:mb-0">
				<span class="text-sm text-gray-500 block mb-1"><?php esc_html_e( 'Previous Post', 'aqualuxe' ); ?></span>
				<a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="flex items-start">
					<?php if ( has_post_thumbnail( $prev_post->ID ) ) : ?>
						<div class="post-navigation-thumbnail mr-3">
							<?php echo get_the_post_thumbnail( $prev_post->ID, 'thumbnail', array( 'class' => 'w-16 h-16 object-cover rounded' ) ); ?>
						</div>
					<?php endif; ?>
					<div class="post-navigation-title">
						<span class="text-lg font-medium text-primary-700 hover:text-primary-600 transition-colors">
							<?php echo esc_html( get_the_title( $prev_post->ID ) ); ?>
						</span>
					</div>
				</a>
			</div>
		<?php endif; ?>
		
		<?php if ( $next_post ) : ?>
			<div class="post-navigation-next w-full md:w-1/2 md:pl-4 text-left md:text-right">
				<span class="text-sm text-gray-500 block mb-1"><?php esc_html_e( 'Next Post', 'aqualuxe' ); ?></span>
				<a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="flex items-start md:flex-row-reverse">
					<?php if ( has_post_thumbnail( $next_post->ID ) ) : ?>
						<div class="post-navigation-thumbnail ml-0 mr-3 md:ml-3 md:mr-0">
							<?php echo get_the_post_thumbnail( $next_post->ID, 'thumbnail', array( 'class' => 'w-16 h-16 object-cover rounded' ) ); ?>
						</div>
					<?php endif; ?>
					<div class="post-navigation-title">
						<span class="text-lg font-medium text-primary-700 hover:text-primary-600 transition-colors">
							<?php echo esc_html( get_the_title( $next_post->ID ) ); ?>
						</span>
					</div>
				</a>
			</div>
		<?php endif; ?>
	</nav>
	<?php
}

/**
 * Display featured posts slider
 */
function aqualuxe_featured_posts_slider() {
	$featured_posts = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 3,
			'meta_key'       => 'aqualuxe_featured_post',
			'meta_value'     => '1',
		)
	);
	
	if ( $featured_posts->have_posts() ) :
		?>
		<div class="featured-posts-slider mb-8">
			<div class="swiper-container">
				<div class="swiper-wrapper">
					<?php
					while ( $featured_posts->have_posts() ) :
						$featured_posts->the_post();
						?>
						<div class="swiper-slide">
							<div class="featured-post relative rounded-lg overflow-hidden shadow-lg h-96">
								<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail( 'full', array( 'class' => 'absolute inset-0 w-full h-full object-cover' ) ); ?>
								<?php endif; ?>
								
								<div class="featured-post-overlay absolute inset-0 bg-gradient-to-t from-black to-transparent opacity-70"></div>
								
								<div class="featured-post-content absolute bottom-0 left-0 right-0 p-6 text-white">
									<?php
									$category = aqualuxe_get_first_category();
									if ( $category ) :
										?>
										<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="inline-block bg-primary-600 text-white text-xs font-medium px-2 py-1 rounded-md mb-3">
											<?php echo esc_html( $category->name ); ?>
										</a>
									<?php endif; ?>
									
									<h2 class="text-2xl md:text-3xl font-bold mb-2">
										<a href="<?php the_permalink(); ?>" class="text-white hover:text-primary-200 transition-colors">
											<?php the_title(); ?>
										</a>
									</h2>
									
									<div class="flex items-center text-sm text-gray-300">
										<?php aqualuxe_posted_by(); ?>
										<span class="mx-2">•</span>
										<?php aqualuxe_posted_on(); ?>
									</div>
								</div>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
				
				<div class="swiper-pagination"></div>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			</div>
		</div>
		<?php
	endif;
}

/**
 * Display post card
 */
function aqualuxe_post_card( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	
	?>
	<article id="post-<?php echo esc_attr( $post_id ); ?>" <?php post_class( 'post-card bg-white rounded-lg shadow-md overflow-hidden transition-shadow hover:shadow-lg', $post_id ); ?>>
		<?php if ( has_post_thumbnail( $post_id ) ) : ?>
			<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="block overflow-hidden">
				<?php echo get_the_post_thumbnail( $post_id, 'medium_large', array( 'class' => 'w-full h-48 object-cover hover:scale-105 transition-transform duration-300' ) ); ?>
			</a>
		<?php endif; ?>
		
		<div class="p-4">
			<header class="entry-header mb-3">
				<?php
				$category = aqualuxe_get_first_category();
				if ( $category ) :
					?>
					<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="inline-block bg-primary-100 text-primary-800 text-xs font-medium px-2 py-1 rounded-md mb-2">
						<?php echo esc_html( $category->name ); ?>
					</a>
				<?php endif; ?>
				
				<h2 class="entry-title text-xl font-bold text-primary-800 mb-2">
					<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="hover:text-primary-600 transition-colors">
						<?php echo esc_html( get_the_title( $post_id ) ); ?>
					</a>
				</h2>
				
				<div class="entry-meta flex flex-wrap items-center text-xs text-gray-600">
					<?php
					aqualuxe_posted_on();
					
					// Comments count
					if ( ! post_password_required( $post_id ) && ( comments_open( $post_id ) || get_comments_number( $post_id ) ) ) {
						echo '<span class="comments-link ml-3">';
						echo '<i class="fas fa-comment-alt mr-1"></i>';
						echo esc_html( get_comments_number( $post_id ) );
						echo '</span>';
					}
					?>
				</div>
			</header>
			
			<div class="entry-summary prose prose-sm max-w-none mb-4">
				<?php echo wp_trim_words( get_the_excerpt( $post_id ), 15, '...' ); ?>
			</div>
			
			<div class="entry-footer">
				<a href="<?php echo esc_url( get_permalink( $post_id ) ); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-800 font-medium transition-colors text-sm">
					<?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <i class="fas fa-arrow-right ml-1"></i>
				</a>
			</div>
		</div>
	</article>
	<?php
}

/**
 * Display popular posts
 */
function aqualuxe_popular_posts( $count = 4 ) {
	$popular_posts = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => $count,
			'meta_key'       => 'post_views_count',
			'orderby'        => 'meta_value_num',
			'order'          => 'DESC',
		)
	);
	
	if ( $popular_posts->have_posts() ) :
		?>
		<div class="popular-posts">
			<h3 class="text-xl font-bold text-primary-800 mb-4"><?php esc_html_e( 'Popular Posts', 'aqualuxe' ); ?></h3>
			
			<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
				<?php
				while ( $popular_posts->have_posts() ) :
					$popular_posts->the_post();
					aqualuxe_post_card( get_the_ID() );
				endwhile;
				wp_reset_postdata();
				?>
			</div>
		</div>
		<?php
	endif;
}

/**
 * Display author box
 */
function aqualuxe_author_box() {
	$author_id = get_the_author_meta( 'ID' );
	$author_name = get_the_author_meta( 'display_name' );
	$author_bio = get_the_author_meta( 'description' );
	$author_url = get_author_posts_url( $author_id );
	$author_posts_count = count_user_posts( $author_id );
	
	if ( ! $author_bio ) {
		return;
	}
	
	?>
	<div class="author-box bg-gray-50 rounded-lg p-6 flex flex-col md:flex-row items-start md:items-center">
		<div class="author-avatar mb-4 md:mb-0 md:mr-6">
			<?php
			$author_bio_avatar_size = apply_filters( 'aqualuxe_author_bio_avatar_size', 96 );
			echo get_avatar( $author_id, $author_bio_avatar_size, '', '', array( 'class' => 'rounded-full' ) );
			?>
		</div>
		<div class="author-info">
			<h3 class="author-title text-lg font-bold text-primary-800 mb-2">
				<?php echo esc_html( $author_name ); ?>
			</h3>
			
			<div class="author-meta text-sm text-gray-600 mb-3">
				<span class="author-posts-count">
					<?php
					/* translators: %d: number of posts */
					printf( esc_html( _n( '%d Article', '%d Articles', $author_posts_count, 'aqualuxe' ) ), $author_posts_count );
					?>
				</span>
			</div>
			
			<div class="author-description prose prose-sm mb-4">
				<?php echo wp_kses_post( $author_bio ); ?>
			</div>
			
			<div class="author-social flex space-x-3">
				<?php
				$author_website = get_the_author_meta( 'user_url' );
				$author_facebook = get_the_author_meta( 'facebook' );
				$author_twitter = get_the_author_meta( 'twitter' );
				$author_instagram = get_the_author_meta( 'instagram' );
				$author_linkedin = get_the_author_meta( 'linkedin' );
				
				if ( $author_website ) :
					?>
					<a href="<?php echo esc_url( $author_website ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary-600 transition-colors">
						<i class="fas fa-globe"></i>
					</a>
				<?php endif; ?>
				
				<?php if ( $author_facebook ) : ?>
					<a href="<?php echo esc_url( $author_facebook ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary-600 transition-colors">
						<i class="fab fa-facebook-f"></i>
					</a>
				<?php endif; ?>
				
				<?php if ( $author_twitter ) : ?>
					<a href="<?php echo esc_url( $author_twitter ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary-600 transition-colors">
						<i class="fab fa-twitter"></i>
					</a>
				<?php endif; ?>
				
				<?php if ( $author_instagram ) : ?>
					<a href="<?php echo esc_url( $author_instagram ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary-600 transition-colors">
						<i class="fab fa-instagram"></i>
					</a>
				<?php endif; ?>
				
				<?php if ( $author_linkedin ) : ?>
					<a href="<?php echo esc_url( $author_linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-600 hover:text-primary-600 transition-colors">
						<i class="fab fa-linkedin-in"></i>
					</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
	<?php
}