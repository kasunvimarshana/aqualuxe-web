<?php
/**
 * Template part for displaying post entry headers
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

// Get post format
$aqualuxe_post_format = get_post_format() ?: 'standard';

// Get featured image size based on post format
$aqualuxe_thumbnail_size = 'post-thumbnail';
if ( is_singular() ) {
	$aqualuxe_thumbnail_size = 'full';
}

// Check if we should display the featured image
$aqualuxe_show_featured_image = true;
if ( is_singular() && get_theme_mod( 'aqualuxe_single_hide_featured_image', false ) ) {
	$aqualuxe_show_featured_image = false;
}

// Check if we should display the post meta
$aqualuxe_show_post_meta = true;
if ( is_singular() && get_theme_mod( 'aqualuxe_single_hide_post_meta', false ) ) {
	$aqualuxe_show_post_meta = false;
}
?>

<header class="entry-header">
	<?php
	// Display post format icon for non-standard post formats
	if ( 'standard' !== $aqualuxe_post_format && ! is_singular() ) :
		?>
		<div class="post-format-badge absolute top-4 right-4 bg-primary-600 text-white rounded-full p-2 z-10">
			<?php
			switch ( $aqualuxe_post_format ) {
				case 'video':
					echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>';
					break;
				case 'audio':
					echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3" /></svg>';
					break;
				case 'gallery':
					echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>';
					break;
				case 'quote':
					echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" /></svg>';
					break;
				case 'link':
					echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" /></svg>';
					break;
				default:
					echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>';
			}
			?>
		</div>
	<?php endif; ?>

	<?php
	// Featured image for standard post format
	if ( $aqualuxe_show_featured_image && has_post_thumbnail() && 'standard' === $aqualuxe_post_format ) :
		?>
		<div class="post-thumbnail relative overflow-hidden <?php echo is_singular() ? 'mb-8 rounded-lg' : 'mb-4'; ?>">
			<?php if ( ! is_singular() ) : ?>
				<a href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
			<?php endif; ?>
				<?php
				the_post_thumbnail(
					$aqualuxe_thumbnail_size,
					array(
						'class' => is_singular() ? 'w-full h-auto' : 'w-full h-56 sm:h-64 object-cover transition-transform duration-500 hover:scale-105',
						'alt'   => the_title_attribute( array( 'echo' => false ) ),
					)
				);
				?>
			<?php if ( ! is_singular() ) : ?>
				</a>
			<?php endif; ?>
			
			<?php
			// Display category badge for posts
			if ( ! is_singular() && 'post' === get_post_type() ) :
				$categories = get_the_category();
				if ( ! empty( $categories ) ) :
					$category = $categories[0];
					?>
					<a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>" class="category-badge absolute top-4 left-4 bg-primary-600 hover:bg-primary-700 text-white text-xs font-medium px-2 py-1 rounded-md transition-colors duration-200">
						<?php echo esc_html( $category->name ); ?>
					</a>
					<?php
				endif;
			endif;
			?>
		</div>
	<?php endif; ?>

	<?php
	// Post title
	if ( is_singular() ) :
		the_title( '<h1 class="entry-title text-3xl sm:text-4xl font-serif font-bold text-dark-800 dark:text-white mb-4">', '</h1>' );
	else :
		the_title( '<h2 class="entry-title text-xl sm:text-2xl font-serif font-bold mb-2"><a href="' . esc_url( get_permalink() ) . '" class="text-dark-800 dark:text-white hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200" rel="bookmark">', '</a></h2>' );
	endif;
	?>

	<?php
	// Post meta
	if ( 'post' === get_post_type() && $aqualuxe_show_post_meta ) :
		?>
		<div class="entry-meta text-sm text-gray-500 dark:text-gray-400 mb-4">
			<?php
			aqualuxe_posted_on();
			aqualuxe_posted_by();
			
			// Add estimated reading time if enabled
			if ( function_exists( 'aqualuxe_reading_time' ) && get_theme_mod( 'aqualuxe_show_reading_time', true ) ) :
				aqualuxe_reading_time();
			endif;
			
			// Add comment count if comments are open or has comments
			if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
				?>
				<span class="comments-link">
					<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline-block mr-1 -mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
					</svg>
					<?php comments_popup_link(); ?>
				</span>
				<?php
			endif;
			?>
		</div><!-- .entry-meta -->
	<?php endif; ?>

	<?php
	// Post format specific content
	switch ( $aqualuxe_post_format ) {
		case 'video':
			// Get video URL from post meta
			$video_url = get_post_meta( get_the_ID(), 'aqualuxe_video_url', true );
			if ( ! empty( $video_url ) ) :
				?>
				<div class="post-format-content video-content mb-6 rounded-lg overflow-hidden">
					<?php
					// Check if it's a YouTube or Vimeo URL
					if ( strpos( $video_url, 'youtube.com' ) !== false || strpos( $video_url, 'youtu.be' ) !== false ) {
						echo wp_oembed_get( $video_url, array( 'width' => 1200 ) );
					} elseif ( strpos( $video_url, 'vimeo.com' ) !== false ) {
						echo wp_oembed_get( $video_url, array( 'width' => 1200 ) );
					} else {
						// For self-hosted videos
						echo do_shortcode( '[video src="' . esc_url( $video_url ) . '" width="1200" height="675"]' );
					}
					?>
				</div>
				<?php
			endif;
			break;
			
		case 'audio':
			// Get audio URL from post meta
			$audio_url = get_post_meta( get_the_ID(), 'aqualuxe_audio_url', true );
			if ( ! empty( $audio_url ) ) :
				?>
				<div class="post-format-content audio-content mb-6 rounded-lg overflow-hidden">
					<?php
					// Check if it's a SoundCloud URL
					if ( strpos( $audio_url, 'soundcloud.com' ) !== false ) {
						echo wp_oembed_get( $audio_url );
					} else {
						// For self-hosted audio
						echo do_shortcode( '[audio src="' . esc_url( $audio_url ) . '"]' );
					}
					?>
				</div>
				<?php
			endif;
			break;
			
		case 'gallery':
			// Get gallery images from post meta
			$gallery_images = get_post_meta( get_the_ID(), 'aqualuxe_gallery_images', true );
			if ( ! empty( $gallery_images ) ) :
				?>
				<div class="post-format-content gallery-content mb-6 rounded-lg overflow-hidden">
					<?php
					// Display gallery
					echo do_shortcode( '[gallery ids="' . esc_attr( $gallery_images ) . '" size="large" link="file"]' );
					?>
				</div>
				<?php
			endif;
			break;
			
		case 'quote':
			// Get quote content and source from post meta
			$quote_content = get_post_meta( get_the_ID(), 'aqualuxe_quote_content', true );
			$quote_source = get_post_meta( get_the_ID(), 'aqualuxe_quote_source', true );
			if ( ! empty( $quote_content ) ) :
				?>
				<div class="post-format-content quote-content mb-6 bg-gray-100 dark:bg-dark-700 p-6 rounded-lg border-l-4 border-primary-600 dark:border-primary-400">
					<blockquote class="text-xl italic font-serif text-gray-700 dark:text-gray-200">
						<?php echo wp_kses_post( $quote_content ); ?>
						<?php if ( ! empty( $quote_source ) ) : ?>
							<footer class="mt-2 text-sm text-gray-500 dark:text-gray-400">
								— <?php echo esc_html( $quote_source ); ?>
							</footer>
						<?php endif; ?>
					</blockquote>
				</div>
				<?php
			endif;
			break;
			
		case 'link':
			// Get link URL and title from post meta
			$link_url = get_post_meta( get_the_ID(), 'aqualuxe_link_url', true );
			$link_title = get_post_meta( get_the_ID(), 'aqualuxe_link_title', true );
			if ( ! empty( $link_url ) ) :
				?>
				<div class="post-format-content link-content mb-6 bg-gray-100 dark:bg-dark-700 p-6 rounded-lg">
					<a href="<?php echo esc_url( $link_url ); ?>" class="flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors duration-200" target="_blank" rel="noopener noreferrer">
						<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
						</svg>
						<span class="text-lg font-medium">
							<?php echo ! empty( $link_title ) ? esc_html( $link_title ) : esc_url( $link_url ); ?>
						</span>
					</a>
				</div>
				<?php
			endif;
			break;
	}
	?>
</header><!-- .entry-header -->