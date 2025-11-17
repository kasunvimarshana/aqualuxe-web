<?php
/**
 * Template part for displaying the blog section on the homepage
 *
 * @package AquaLuxe
 */

// Get blog section settings from customizer or use defaults
$blog_title = get_theme_mod( 'aqualuxe_blog_title', __( 'Latest Articles', 'aqualuxe' ) );
$blog_subtitle = get_theme_mod( 'aqualuxe_blog_subtitle', __( 'Tips, guides, and news from the aquatic world', 'aqualuxe' ) );
$blog_count = get_theme_mod( 'aqualuxe_blog_count', 3 );
$blog_button_text = get_theme_mod( 'aqualuxe_blog_button_text', __( 'View All Articles', 'aqualuxe' ) );
$blog_button_url = get_theme_mod( 'aqualuxe_blog_button_url', get_permalink( get_option( 'page_for_posts' ) ) );

// Get latest posts
$args = array(
	'post_type'           => 'post',
	'posts_per_page'      => $blog_count,
	'post_status'         => 'publish',
	'ignore_sticky_posts' => true,
);

$latest_posts = new WP_Query( $args );

// Only display the section if posts are found
if ( $latest_posts->have_posts() ) :
?>

<section id="blog" class="blog-section">
	<div class="container">
		<div class="section-header">
			<?php if ( $blog_title ) : ?>
				<h2 class="section-title"><?php echo esc_html( $blog_title ); ?></h2>
			<?php endif; ?>
			
			<?php if ( $blog_subtitle ) : ?>
				<p class="section-subtitle"><?php echo esc_html( $blog_subtitle ); ?></p>
			<?php endif; ?>
		</div>
		
		<div class="blog-grid">
			<?php
			while ( $latest_posts->have_posts() ) :
				$latest_posts->the_post();
				
				// Get post data
				$post_id = get_the_ID();
				$post_url = get_permalink();
				$post_title = get_the_title();
				$post_excerpt = has_excerpt() ? get_the_excerpt() : wp_trim_words( get_the_content(), 20 );
				$post_date = get_the_date();
				$post_author = get_the_author();
				$post_categories = get_the_category();
				$post_image = has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'medium_large' ) : '';
				?>
				
				<article class="blog-card">
					<?php if ( $post_image ) : ?>
						<div class="blog-image">
							<a href="<?php echo esc_url( $post_url ); ?>">
								<img src="<?php echo esc_url( $post_image ); ?>" alt="<?php echo esc_attr( $post_title ); ?>">
							</a>
						</div>
					<?php endif; ?>
					
					<div class="blog-content">
						<?php if ( ! empty( $post_categories ) ) : ?>
							<div class="blog-categories">
								<?php
								$category = reset( $post_categories );
								echo '<a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a>';
								?>
							</div>
						<?php endif; ?>
						
						<h3 class="blog-title">
							<a href="<?php echo esc_url( $post_url ); ?>"><?php echo esc_html( $post_title ); ?></a>
						</h3>
						
						<div class="blog-meta">
							<span class="blog-date">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
									<path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
								</svg>
								<?php echo esc_html( $post_date ); ?>
							</span>
							
							<span class="blog-author">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
									<path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
								</svg>
								<?php echo esc_html( $post_author ); ?>
							</span>
						</div>
						
						<div class="blog-excerpt">
							<p><?php echo esc_html( $post_excerpt ); ?></p>
						</div>
						
						<a href="<?php echo esc_url( $post_url ); ?>" class="blog-read-more">
							<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="16" height="16">
								<path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
							</svg>
						</a>
					</div>
				</article>
				
			<?php endwhile; ?>
			<?php wp_reset_postdata(); ?>
		</div>
		
		<?php if ( $blog_button_text && $blog_button_url ) : ?>
			<div class="section-footer">
				<a href="<?php echo esc_url( $blog_button_url ); ?>" class="btn btn-primary"><?php echo esc_html( $blog_button_text ); ?></a>
			</div>
		<?php endif; ?>
	</div>
</section>

<?php endif; ?>