<?php
/**
 * Helper functions for the AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Get asset URL with cache busting
 *
 * @param string $path The path to the asset.
 * @return string The URL to the asset with cache busting.
 */
function aqualuxe_asset( $path ) {
	$manifest_path = get_template_directory() . '/assets/dist/mix-manifest.json';
	
	if ( file_exists( $manifest_path ) ) {
		$manifest = json_decode( file_get_contents( $manifest_path ), true );
		
		if ( isset( $manifest[ $path ] ) ) {
			return get_template_directory_uri() . '/assets/dist' . $manifest[ $path ];
		}
	}
	
	return get_template_directory_uri() . '/assets/dist/' . $path;
}

/**
 * Get SVG icon
 *
 * @param string $icon The icon name.
 * @return void
 */
function aqualuxe_get_icon( $icon ) {
	switch ( $icon ) {
		case 'fish':
			?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path d="M11.47 3.84a.75.75 0 011.06 0l8.69 8.69a.75.75 0 101.06-1.06l-8.689-8.69a2.25 2.25 0 00-3.182 0l-8.69 8.69a.75.75 0 001.061 1.06l8.69-8.69z" />
				<path d="M12 5.432l8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 01-.75-.75v-4.5a.75.75 0 00-.75-.75h-3a.75.75 0 00-.75.75V21a.75.75 0 01-.75.75H5.625a1.875 1.875 0 01-1.875-1.875v-6.198a2.29 2.29 0 00.091-.086L12 5.43z" />
			</svg>
			<?php
			break;
		case 'leaf':
			?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 00-1.071-.136 9.742 9.742 0 00-3.539 6.177A7.547 7.547 0 016.648 6.61a.75.75 0 00-1.152-.082A9 9 0 1015.68 4.534a7.46 7.46 0 01-2.717-2.248zM15.75 14.25a3.75 3.75 0 11-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 011.925-3.545 3.75 3.75 0 013.255 3.717z" clip-rule="evenodd" />
			</svg>
			<?php
			break;
		case 'globe':
			?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path d="M21.721 12.752a9.711 9.711 0 00-.945-5.003 12.754 12.754 0 01-4.339 2.708 18.991 18.991 0 01-.214 4.772 17.165 17.165 0 005.498-2.477zM14.634 15.55a17.324 17.324 0 00.332-4.647c-.952.227-1.945.347-2.966.347-1.021 0-2.014-.12-2.966-.347a17.515 17.515 0 00.332 4.647 17.385 17.385 0 005.268 0zM9.772 17.119a18.963 18.963 0 004.456 0A17.182 17.182 0 0112 21.724a17.18 17.18 0 01-2.228-4.605zM7.777 15.23a18.87 18.87 0 01-.214-4.774 12.753 12.753 0 01-4.34-2.708 9.711 9.711 0 00-.944 5.004 17.165 17.165 0 005.498 2.477zM21.356 14.752a9.765 9.765 0 01-7.478 6.817 18.64 18.64 0 001.988-4.718 18.627 18.627 0 005.49-2.098zM2.644 14.752c1.682.971 3.53 1.688 5.49 2.099a18.64 18.64 0 001.988 4.718 9.765 9.765 0 01-7.478-6.816zM13.878 2.43a9.755 9.755 0 016.116 3.986 11.267 11.267 0 01-3.746 2.504 18.63 18.63 0 00-2.37-6.49zM12 2.276a17.152 17.152 0 012.805 7.121c-.897.23-1.837.353-2.805.353-.968 0-1.908-.122-2.805-.353A17.151 17.151 0 0112 2.276zM10.122 2.43a18.629 18.629 0 00-2.37 6.49 11.266 11.266 0 01-3.746-2.504 9.754 9.754 0 016.116-3.985z" />
			</svg>
			<?php
			break;
		case 'design':
			?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path fill-rule="evenodd" d="M20.599 1.5c-.376 0-.743.111-1.055.32l-5.08 3.385a18.747 18.747 0 00-3.471 2.987 10.04 10.04 0 014.815 4.815 18.748 18.748 0 002.987-3.472l3.386-5.079A1.902 1.902 0 0020.599 1.5zm-8.3 14.025a18.76 18.76 0 001.896-1.207 8.026 8.026 0 00-4.513-4.513A18.75 18.75 0 008.475 11.7l-.278.5a5.26 5.26 0 013.601 3.602l.502-.278zM6.75 13.5A3.75 3.75 0 003 17.25a1.5 1.5 0 01-1.601 1.497.75.75 0 00-.7 1.123 5.25 5.25 0 009.8-2.62 3.75 3.75 0 00-3.75-3.75z" clip-rule="evenodd" />
			</svg>
			<?php
			break;
		case 'maintenance':
			?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path fill-rule="evenodd" d="M12 6.75a5.25 5.25 0 016.775-5.025.75.75 0 01.313 1.248l-3.32 3.319c.063.475.276.934.641 1.299.365.365.824.578 1.3.64l3.318-3.319a.75.75 0 011.248.313 5.25 5.25 0 01-5.472 6.756c-1.018-.086-1.87.1-2.309.634L7.344 21.3A3.298 3.298 0 112.7 16.657l8.684-7.151c.533-.44.72-1.291.634-2.309A5.342 5.342 0 0112 6.75zM4.117 19.125a.75.75 0 01.75-.75h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75h-.008a.75.75 0 01-.75-.75v-.008z" clip-rule="evenodd" />
			</svg>
			<?php
			break;
		case 'health':
			?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path fill-rule="evenodd" d="M12.75 3.03v.568c0 .334.148.65.405.864l1.068.89c.442.369.535 1.01.216 1.49l-.51.766a2.25 2.25 0 01-1.161.886l-.143.048a1.107 1.107 0 00-.57 1.664c.369.555.169 1.307-.427 1.605L9 13.125l.423 1.059a.956.956 0 01-1.652.928l-.679-.906a1.125 1.125 0 00-1.906.172L4.5 15.75l-.612.153M12.75 3.031a9 9 0 00-8.862 12.872M12.75 3.031a9 9 0 016.69 14.036m0 0l-.177-.529A2.25 2.25 0 0017.128 15H16.5l-.324-.324a1.453 1.453 0 00-2.328.377l-.036.073a1.586 1.586 0 01-.982.816l-.99.282c-.55.157-.894.702-.8 1.267l.073.438c.08.474.49.821.97.821.846 0 1.598.542 1.865 1.345l.215.643m5.276-3.67a9.012 9.012 0 01-5.276 3.67m0 0a9 9 0 01-10.275-4.835M15.75 9c0 .896-.393 1.7-1.016 2.25" clip-rule="evenodd" />
			</svg>
			<?php
			break;
		case 'consultation':
			?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path fill-rule="evenodd" d="M4.848 2.771A49.144 49.144 0 0112 2.25c2.43 0 4.817.178 7.152.52 1.978.292 3.348 2.024 3.348 3.97v6.02c0 1.946-1.37 3.678-3.348 3.97a48.901 48.901 0 01-3.476.383.39.39 0 00-.297.17l-2.755 4.133a.75.75 0 01-1.248 0l-2.755-4.133a.39.39 0 00-.297-.17 48.9 48.9 0 01-3.476-.384c-1.978-.29-3.348-2.024-3.348-3.97V6.741c0-1.946 1.37-3.68 3.348-3.97zM6.75 8.25a.75.75 0 01.75-.75h9a.75.75 0 010 1.5h-9a.75.75 0 01-.75-.75zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H7.5z" clip-rule="evenodd" />
			</svg>
			<?php
			break;
		default:
			// Default icon
			?>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 01-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 01-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 01-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584zM12 18a.75.75 0 100-1.5.75.75 0 000 1.5z" clip-rule="evenodd" />
			</svg>
			<?php
			break;
	}
}

/**
 * Get social icons
 *
 * @return void
 */
function aqualuxe_social_icons() {
	$social_links = array(
		'facebook'  => get_theme_mod( 'aqualuxe_social_facebook', '#' ),
		'twitter'   => get_theme_mod( 'aqualuxe_social_twitter', '#' ),
		'instagram' => get_theme_mod( 'aqualuxe_social_instagram', '#' ),
		'youtube'   => get_theme_mod( 'aqualuxe_social_youtube', '#' ),
		'linkedin'  => get_theme_mod( 'aqualuxe_social_linkedin', '#' ),
	);
	
	if ( ! empty( array_filter( $social_links ) ) ) {
		echo '<div class="social-icons">';
		
		foreach ( $social_links as $network => $url ) {
			if ( ! empty( $url ) ) {
				echo '<a href="' . esc_url( $url ) . '" class="social-icon ' . esc_attr( $network ) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr( ucfirst( $network ) ) . '">';
				
				switch ( $network ) {
					case 'facebook':
						?>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
							<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
						</svg>
						<?php
						break;
					case 'twitter':
						?>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
							<path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 9.99 9.99 0 01-3.127 1.195 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
						</svg>
						<?php
						break;
					case 'instagram':
						?>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
							<path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/>
						</svg>
						<?php
						break;
					case 'youtube':
						?>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
							<path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
						</svg>
						<?php
						break;
					case 'linkedin':
						?>
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
							<path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
						</svg>
						<?php
						break;
				}
				
				echo '</a>';
			}
		}
		
		echo '</div>';
	}
}

/**
 * Display post categories
 *
 * @return void
 */
function aqualuxe_post_categories() {
	$categories = get_the_category();
	
	if ( ! empty( $categories ) ) {
		$output = '<div class="post-categories-list">';
		
		foreach ( $categories as $category ) {
			$output .= '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '" class="post-category">' . esc_html( $category->name ) . '</a>';
		}
		
		$output .= '</div>';
		
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Display post tags
 *
 * @return void
 */
function aqualuxe_post_tags() {
	$tags = get_the_tags();
	
	if ( ! empty( $tags ) ) {
		$output = '<div class="post-tags-list">';
		
		foreach ( $tags as $tag ) {
			$output .= '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="post-tag">' . esc_html( $tag->name ) . '</a>';
		}
		
		$output .= '</div>';
		
		echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Display posted on date
 *
 * @return void
 */
function aqualuxe_posted_on() {
	$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
	
	if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
		$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time><time class="updated" datetime="%3$s">%4$s</time>';
	}
	
	$time_string = sprintf(
		$time_string,
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_attr( get_the_modified_date( 'c' ) ),
		esc_html( get_the_modified_date() )
	);
	
	echo $time_string; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}

/**
 * Display posted by author
 *
 * @return void
 */
function aqualuxe_posted_by() {
	printf(
		/* translators: %s: post author. */
		'%s',
		'<a href="' . esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ) . '">' . esc_html( get_the_author() ) . '</a>'
	);
}

/**
 * Display pagination
 *
 * @return void
 */
function aqualuxe_pagination() {
	$args = array(
		'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="20" height="20"><path fill-rule="evenodd" d="M12.79 5.23a.75.75 0 01-.02 1.06L8.832 10l3.938 3.71a.75.75 0 11-1.04 1.08l-4.5-4.25a.75.75 0 010-1.08l4.5-4.25a.75.75 0 011.06.02z" clip-rule="evenodd" /></svg>' . esc_html__( 'Previous', 'aqualuxe' ),
		'next_text' => esc_html__( 'Next', 'aqualuxe' ) . '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="20" height="20"><path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" /></svg>',
	);
	
	echo '<div class="pagination-container">';
	the_posts_pagination( $args );
	echo '</div>';
}

/**
 * Calculate reading time
 *
 * @return void
 */
function aqualuxe_reading_time() {
	$content = get_post_field( 'post_content', get_the_ID() );
	$word_count = str_word_count( strip_tags( $content ) );
	$reading_time = ceil( $word_count / 200 ); // Assuming 200 words per minute reading speed
	
	if ( $reading_time < 1 ) {
		$reading_time = 1;
	}
	
	printf(
		/* translators: %d: reading time in minutes */
		esc_html( _n( '%d min read', '%d min read', $reading_time, 'aqualuxe' ) ),
		esc_html( $reading_time )
	);
}

/**
 * Display social sharing buttons
 *
 * @return void
 */
function aqualuxe_social_sharing() {
	$post_url = urlencode( get_permalink() );
	$post_title = urlencode( get_the_title() );
	$post_thumbnail = urlencode( get_the_post_thumbnail_url( get_the_ID(), 'large' ) );
	
	$facebook_url = 'https://www.facebook.com/sharer/sharer.php?u=' . $post_url;
	$twitter_url = 'https://twitter.com/intent/tweet?url=' . $post_url . '&text=' . $post_title;
	$linkedin_url = 'https://www.linkedin.com/shareArticle?mini=true&url=' . $post_url . '&title=' . $post_title;
	$pinterest_url = 'https://pinterest.com/pin/create/button/?url=' . $post_url . '&media=' . $post_thumbnail . '&description=' . $post_title;
	$email_url = 'mailto:?subject=' . $post_title . '&body=' . $post_url;
	
	echo '<div class="social-sharing">';
	
	echo '<a href="' . esc_url( $facebook_url ) . '" target="_blank" rel="noopener noreferrer" class="share-facebook" aria-label="' . esc_attr__( 'Share on Facebook', 'aqualuxe' ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>';
	echo '</a>';
	
	echo '<a href="' . esc_url( $twitter_url ) . '" target="_blank" rel="noopener noreferrer" class="share-twitter" aria-label="' . esc_attr__( 'Share on Twitter', 'aqualuxe' ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723 9.99 9.99 0 01-3.127 1.195 4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>';
	echo '</a>';
	
	echo '<a href="' . esc_url( $linkedin_url ) . '" target="_blank" rel="noopener noreferrer" class="share-linkedin" aria-label="' . esc_attr__( 'Share on LinkedIn', 'aqualuxe' ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>';
	echo '</a>';
	
	echo '<a href="' . esc_url( $pinterest_url ) . '" target="_blank" rel="noopener noreferrer" class="share-pinterest" aria-label="' . esc_attr__( 'Share on Pinterest', 'aqualuxe' ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>';
	echo '</a>';
	
	echo '<a href="' . esc_url( $email_url ) . '" class="share-email" aria-label="' . esc_attr__( 'Share via Email', 'aqualuxe' ) . '">';
	echo '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" /><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" /></svg>';
	echo '</a>';
	
	echo '</div>';
}

/**
 * Display author box
 *
 * @return void
 */
function aqualuxe_author_box() {
	$author_id = get_the_author_meta( 'ID' );
	$author_name = get_the_author_meta( 'display_name' );
	$author_description = get_the_author_meta( 'description' );
	$author_url = get_author_posts_url( $author_id );
	$author_posts_count = count_user_posts( $author_id );
	
	if ( ! empty( $author_description ) ) {
		?>
		<div class="author-box">
			<div class="author-avatar">
				<?php echo get_avatar( $author_id, 100 ); ?>
			</div>
			
			<div class="author-info">
				<h4 class="author-name">
					<a href="<?php echo esc_url( $author_url ); ?>"><?php echo esc_html( $author_name ); ?></a>
				</h4>
				
				<div class="author-description">
					<?php echo wpautop( $author_description ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</div>
				
				<div class="author-meta">
					<span class="author-posts-count">
						<?php
						printf(
							/* translators: %d: number of posts */
							esc_html( _n( '%d Article', '%d Articles', $author_posts_count, 'aqualuxe' ) ),
							esc_html( $author_posts_count )
						);
						?>
					</span>
					
					<?php if ( get_the_author_meta( 'url' ) ) : ?>
						<a href="<?php echo esc_url( get_the_author_meta( 'url' ) ); ?>" class="author-website" target="_blank" rel="noopener noreferrer">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
								<path fill-rule="evenodd" d="M4.25 5.5a.75.75 0 00-.75.75v8.5c0 .414.336.75.75.75h8.5a.75.75 0 00.75-.75v-4a.75.75 0 011.5 0v4A2.25 2.25 0 0112.75 17h-8.5A2.25 2.25 0 012 14.75v-8.5A2.25 2.25 0 014.25 4h5a.75.75 0 010 1.5h-5z" clip-rule="evenodd" />
								<path fill-rule="evenodd" d="M6.194 12.753a.75.75 0 001.06.053L16.5 4.44v2.81a.75.75 0 001.5 0v-4.5a.75.75 0 00-.75-.75h-4.5a.75.75 0 000 1.5h2.553l-9.056 8.194a.75.75 0 00-.053 1.06z" clip-rule="evenodd" />
							</svg>
							<?php esc_html_e( 'Website', 'aqualuxe' ); ?>
						</a>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<?php
	}
}

/**
 * Display related posts
 *
 * @return void
 */
function aqualuxe_related_posts() {
	$post_id = get_the_ID();
	$cat_ids = wp_get_post_categories( $post_id );
	
	if ( $cat_ids ) {
		$args = array(
			'category__in'        => $cat_ids,
			'post__not_in'        => array( $post_id ),
			'posts_per_page'      => 3,
			'ignore_sticky_posts' => true,
		);
		
		$related_query = new WP_Query( $args );
		
		if ( $related_query->have_posts() ) {
			?>
			<div class="related-posts">
				<h3 class="related-posts-title"><?php esc_html_e( 'Related Articles', 'aqualuxe' ); ?></h3>
				
				<div class="related-posts-grid">
					<?php
					while ( $related_query->have_posts() ) {
						$related_query->the_post();
						?>
						<article class="related-post">
							<?php if ( has_post_thumbnail() ) : ?>
								<div class="related-post-thumbnail">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail( 'medium' ); ?>
									</a>
								</div>
							<?php endif; ?>
							
							<div class="related-post-content">
								<h4 class="related-post-title">
									<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
								</h4>
								
								<div class="related-post-meta">
									<span class="related-post-date">
										<?php echo esc_html( get_the_date() ); ?>
									</span>
								</div>
							</div>
						</article>
						<?php
					}
					?>
				</div>
			</div>
			<?php
			wp_reset_postdata();
		}
	}
}

/**
 * Display popular searches
 *
 * @return void
 */
function aqualuxe_popular_searches() {
	$popular_searches = get_theme_mod( 'aqualuxe_popular_searches', array(
		__( 'Discus Fish', 'aqualuxe' ),
		__( 'Aquarium Plants', 'aqualuxe' ),
		__( 'LED Lighting', 'aqualuxe' ),
		__( 'Aquascaping', 'aqualuxe' ),
	) );
	
	if ( ! empty( $popular_searches ) && is_array( $popular_searches ) ) {
		echo '<div class="popular-search-terms">';
		
		foreach ( $popular_searches as $search ) {
			echo '<a href="' . esc_url( home_url( '/?s=' . urlencode( $search ) ) ) . '" class="popular-search-term">' . esc_html( $search ) . '</a>';
		}
		
		echo '</div>';
	}
}

/**
 * Custom comment callback
 *
 * @param object $comment Comment object.
 * @param array  $args Comment arguments.
 * @param int    $depth Comment depth.
 * @return void
 */
function aqualuxe_comment_callback( $comment, $args, $depth ) {
	$tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
	?>
	<<?php echo $tag; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?> id="comment-<?php comment_ID(); ?>" <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?>>
		<article id="div-comment-<?php comment_ID(); ?>" class="comment-body">
			<div class="comment-meta">
				<div class="comment-author vcard">
					<?php
					if ( 0 !== $args['avatar_size'] ) {
						echo get_avatar( $comment, $args['avatar_size'] );
					}
					?>
					<div class="comment-author-info">
						<?php printf( '<cite class="fn">%s</cite>', get_comment_author_link() ); ?>
						
						<div class="comment-metadata">
							<a href="<?php echo esc_url( get_comment_link( $comment, $args ) ); ?>">
								<time datetime="<?php comment_time( 'c' ); ?>">
									<?php
									/* translators: 1: comment date, 2: comment time */
									printf( esc_html__( '%1$s at %2$s', 'aqualuxe' ), esc_html( get_comment_date() ), esc_html( get_comment_time() ) );
									?>
								</time>
							</a>
							
							<?php edit_comment_link( __( 'Edit', 'aqualuxe' ), '<span class="edit-link">', '</span>' ); ?>
						</div><!-- .comment-metadata -->
					</div><!-- .comment-author-info -->
				</div><!-- .comment-author -->

				<?php if ( '0' === $comment->comment_approved ) : ?>
					<p class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'aqualuxe' ); ?></p>
				<?php endif; ?>
			</div><!-- .comment-meta -->

			<div class="comment-content">
				<?php comment_text(); ?>
			</div><!-- .comment-content -->

			<div class="reply">
				<?php
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below' => 'div-comment',
							'depth'     => $depth,
							'max_depth' => $args['max_depth'],
							'before'    => '<div class="reply-link">',
							'after'     => '</div>',
						)
					)
				);
				?>
			</div><!-- .reply -->
		</article><!-- .comment-body -->
	<?php
}

/**
 * Primary menu fallback
 *
 * @return void
 */
function aqualuxe_primary_menu_fallback() {
	?>
	<ul id="primary-menu" class="menu">
		<li class="menu-item">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a>
		</li>
		<li class="menu-item">
			<a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About', 'aqualuxe' ); ?></a>
		</li>
		<li class="menu-item">
			<a href="<?php echo esc_url( home_url( '/services' ) ); ?>"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></a>
		</li>
		<li class="menu-item">
			<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a>
		</li>
		<li class="menu-item">
			<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a>
		</li>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<li class="menu-item">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Shop', 'aqualuxe' ); ?></a>
			</li>
		<?php endif; ?>
	</ul>
	<?php
}

/**
 * Mobile menu fallback
 *
 * @return void
 */
function aqualuxe_mobile_menu_fallback() {
	?>
	<ul id="mobile-menu-nav" class="menu">
		<li class="menu-item">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Home', 'aqualuxe' ); ?></a>
		</li>
		<li class="menu-item">
			<a href="<?php echo esc_url( home_url( '/about' ) ); ?>"><?php esc_html_e( 'About', 'aqualuxe' ); ?></a>
		</li>
		<li class="menu-item">
			<a href="<?php echo esc_url( home_url( '/services' ) ); ?>"><?php esc_html_e( 'Services', 'aqualuxe' ); ?></a>
		</li>
		<li class="menu-item">
			<a href="<?php echo esc_url( home_url( '/blog' ) ); ?>"><?php esc_html_e( 'Blog', 'aqualuxe' ); ?></a>
		</li>
		<li class="menu-item">
			<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>"><?php esc_html_e( 'Contact', 'aqualuxe' ); ?></a>
		</li>
		<?php if ( class_exists( 'WooCommerce' ) ) : ?>
			<li class="menu-item">
				<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><?php esc_html_e( 'Shop', 'aqualuxe' ); ?></a>
			</li>
		<?php endif; ?>
	</ul>
	<?php
}

/**
 * Check if WooCommerce is active
 *
 * @return bool
 */
function aqualuxe_is_woocommerce_active() {
	return class_exists( 'WooCommerce' );
}

/**
 * Get currency switcher
 *
 * @return void
 */
function aqualuxe_currency_switcher() {
	// This is a placeholder function for currency switcher
	// In a real implementation, this would integrate with a currency switching plugin
	$currencies = array(
		'USD' => array(
			'code'  => 'USD',
			'name'  => __( 'US Dollar', 'aqualuxe' ),
			'flag'  => get_template_directory_uri() . '/assets/dist/images/flags/us.svg',
			'symbol' => '$',
		),
		'EUR' => array(
			'code'  => 'EUR',
			'name'  => __( 'Euro', 'aqualuxe' ),
			'flag'  => get_template_directory_uri() . '/assets/dist/images/flags/eu.svg',
			'symbol' => '€',
		),
		'GBP' => array(
			'code'  => 'GBP',
			'name'  => __( 'British Pound', 'aqualuxe' ),
			'flag'  => get_template_directory_uri() . '/assets/dist/images/flags/gb.svg',
			'symbol' => '£',
		),
	);
	
	$current_currency = isset( $_COOKIE['aqualuxe_currency'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_currency'] ) ) : 'USD';
	
	if ( ! isset( $currencies[ $current_currency ] ) ) {
		$current_currency = 'USD';
	}
	
	?>
	<div class="currency-switcher">
		<div class="current-currency" data-toggle="currency-dropdown">
			<?php if ( ! empty( $currencies[ $current_currency ]['flag'] ) ) : ?>
				<img src="<?php echo esc_url( $currencies[ $current_currency ]['flag'] ); ?>" alt="<?php echo esc_attr( $currencies[ $current_currency ]['code'] ); ?>" class="currency-flag">
			<?php endif; ?>
			
			<span class="currency-code"><?php echo esc_html( $currencies[ $current_currency ]['code'] ); ?></span>
			
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16" class="currency-arrow">
				<path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
			</svg>
		</div>
		
		<div class="currency-dropdown" data-dropdown="currency-dropdown">
			<?php foreach ( $currencies as $code => $currency ) : ?>
				<div class="currency-option" data-currency="<?php echo esc_attr( $code ); ?>">
					<?php if ( ! empty( $currency['flag'] ) ) : ?>
						<img src="<?php echo esc_url( $currency['flag'] ); ?>" alt="<?php echo esc_attr( $code ); ?>" class="currency-flag">
					<?php endif; ?>
					
					<span class="currency-code"><?php echo esc_html( $code ); ?></span>
					<span class="currency-name"><?php echo esc_html( $currency['name'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

/**
 * Get language switcher
 *
 * @return void
 */
function aqualuxe_language_switcher() {
	// This is a placeholder function for language switcher
	// In a real implementation, this would integrate with a translation plugin like WPML or Polylang
	$languages = array(
		'en' => array(
			'code'  => 'en',
			'name'  => __( 'English', 'aqualuxe' ),
			'flag'  => get_template_directory_uri() . '/assets/dist/images/flags/us.svg',
		),
		'es' => array(
			'code'  => 'es',
			'name'  => __( 'Spanish', 'aqualuxe' ),
			'flag'  => get_template_directory_uri() . '/assets/dist/images/flags/es.svg',
		),
		'fr' => array(
			'code'  => 'fr',
			'name'  => __( 'French', 'aqualuxe' ),
			'flag'  => get_template_directory_uri() . '/assets/dist/images/flags/fr.svg',
		),
	);
	
	$current_language = isset( $_COOKIE['aqualuxe_language'] ) ? sanitize_text_field( wp_unslash( $_COOKIE['aqualuxe_language'] ) ) : 'en';
	
	if ( ! isset( $languages[ $current_language ] ) ) {
		$current_language = 'en';
	}
	
	?>
	<div class="language-switcher">
		<div class="current-language" data-toggle="language-dropdown">
			<?php if ( ! empty( $languages[ $current_language ]['flag'] ) ) : ?>
				<img src="<?php echo esc_url( $languages[ $current_language ]['flag'] ); ?>" alt="<?php echo esc_attr( $languages[ $current_language ]['code'] ); ?>" class="language-flag">
			<?php endif; ?>
			
			<span class="language-code"><?php echo esc_html( strtoupper( $languages[ $current_language ]['code'] ) ); ?></span>
			
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16" class="language-arrow">
				<path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
			</svg>
		</div>
		
		<div class="language-dropdown" data-dropdown="language-dropdown">
			<?php foreach ( $languages as $code => $language ) : ?>
				<div class="language-option" data-language="<?php echo esc_attr( $code ); ?>">
					<?php if ( ! empty( $language['flag'] ) ) : ?>
						<img src="<?php echo esc_url( $language['flag'] ); ?>" alt="<?php echo esc_attr( $code ); ?>" class="language-flag">
					<?php endif; ?>
					
					<span class="language-code"><?php echo esc_html( strtoupper( $code ) ); ?></span>
					<span class="language-name"><?php echo esc_html( $language['name'] ); ?></span>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<?php
}

/**
 * Get wishlist icon
 *
 * @return void
 */
function aqualuxe_wishlist_icon() {
	// This is a placeholder function for wishlist icon
	// In a real implementation, this would integrate with a wishlist plugin
	?>
	<div class="header-wishlist">
		<a href="#" class="wishlist-icon-link" aria-label="<?php esc_attr_e( 'View your wishlist', 'aqualuxe' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
			</svg>
			<span class="wishlist-count">0</span>
		</a>
	</div>
	<?php
}

/**
 * Get account icon
 *
 * @return void
 */
function aqualuxe_account_icon() {
	// Check if WooCommerce is active
	if ( ! class_exists( 'WooCommerce' ) ) {
		return;
	}
	
	$account_url = wc_get_page_permalink( 'myaccount' );
	?>
	<div class="header-account">
		<a href="<?php echo esc_url( $account_url ); ?>" class="account-icon-link" aria-label="<?php esc_attr_e( 'My Account', 'aqualuxe' ); ?>">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
				<path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
			</svg>
		</a>
	</div>
	<?php
}

/**
 * Get wishlist button
 *
 * @param int $product_id Product ID.
 * @return void
 */
function aqualuxe_wishlist_button( $product_id ) {
	// This is a placeholder function for wishlist button
	// In a real implementation, this would integrate with a wishlist plugin
	?>
	<button class="wishlist-toggle" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Add to wishlist', 'aqualuxe' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
			<path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z" />
		</svg>
	</button>
	<?php
}

/**
 * Get quick view button
 *
 * @param int $product_id Product ID.
 * @return void
 */
function aqualuxe_quick_view_button( $product_id ) {
	// This is a placeholder function for quick view button
	?>
	<button class="quick-view-button" data-product-id="<?php echo esc_attr( $product_id ); ?>" aria-label="<?php esc_attr_e( 'Quick view', 'aqualuxe' ); ?>">
		<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="20" height="20">
			<path d="M12 15a3 3 0 100-6 3 3 0 000 6z" />
			<path fill-rule="evenodd" d="M1.323 11.447C2.811 6.976 7.028 3.75 12.001 3.75c4.97 0 9.185 3.223 10.675 7.69.12.362.12.752 0 1.113-1.487 4.471-5.705 7.697-10.677 7.697-4.97 0-9.186-3.223-10.675-7.69a1.762 1.762 0 010-1.113zM17.25 12a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0z" clip-rule="evenodd" />
		</svg>
		<?php esc_html_e( 'Quick View', 'aqualuxe' ); ?>
	</button>
	<?php
}

/**
 * Get quick view modal
 *
 * @return void
 */
function aqualuxe_quick_view_modal() {
	?>
	<div id="quick-view-modal" class="quick-view-modal">
		<div class="quick-view-overlay"></div>
		<div class="quick-view-content">
			<button class="quick-view-close" aria-label="<?php esc_attr_e( 'Close quick view', 'aqualuxe' ); ?>">
				<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
					<path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z" clip-rule="evenodd" />
				</svg>
			</button>
			<div class="quick-view-product"></div>
		</div>
	</div>
	<?php
}