<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 */

if ( ! is_active_sidebar( 'sidebar-1' ) ) {
	return;
}
?>

<aside id="secondary" class="widget-area">
	<div class="sidebar-widgets space-y-8">
		<?php dynamic_sidebar( 'sidebar-1' ); ?>
		
		<?php if ( ! is_active_sidebar( 'sidebar-1' ) ) : ?>
			<!-- Default sidebar content if no widgets are active -->
			<div class="widget widget_search bg-white rounded-lg shadow-md p-6">
				<h2 class="widget-title text-xl font-bold text-primary-800 mb-4"><?php esc_html_e( 'Search', 'aqualuxe' ); ?></h2>
				<?php get_search_form(); ?>
			</div>
			
			<div class="widget widget_categories bg-white rounded-lg shadow-md p-6">
				<h2 class="widget-title text-xl font-bold text-primary-800 mb-4"><?php esc_html_e( 'Categories', 'aqualuxe' ); ?></h2>
				<ul class="space-y-2">
					<?php
					wp_list_categories(
						array(
							'title_li'  => '',
							'show_count' => true,
							'orderby'   => 'count',
							'order'     => 'DESC',
							'number'    => 10,
						)
					);
					?>
				</ul>
			</div>
			
			<div class="widget widget_recent_posts bg-white rounded-lg shadow-md p-6">
				<h2 class="widget-title text-xl font-bold text-primary-800 mb-4"><?php esc_html_e( 'Recent Posts', 'aqualuxe' ); ?></h2>
				<ul class="space-y-4">
					<?php
					$recent_posts = wp_get_recent_posts(
						array(
							'numberposts' => 5,
							'post_status' => 'publish',
						)
					);
					
					foreach ( $recent_posts as $recent ) {
						?>
						<li class="flex items-start">
							<?php if ( has_post_thumbnail( $recent['ID'] ) ) : ?>
								<a href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>" class="flex-shrink-0 mr-3">
									<?php echo get_the_post_thumbnail( $recent['ID'], 'thumbnail', array( 'class' => 'w-16 h-16 object-cover rounded' ) ); ?>
								</a>
							<?php endif; ?>
							<div>
								<a href="<?php echo esc_url( get_permalink( $recent['ID'] ) ); ?>" class="font-medium text-primary-700 hover:text-primary-600 transition-colors">
									<?php echo esc_html( $recent['post_title'] ); ?>
								</a>
								<div class="text-sm text-gray-500 mt-1">
									<?php echo esc_html( get_the_date( '', $recent['ID'] ) ); ?>
								</div>
							</div>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
			
			<div class="widget widget_tags bg-white rounded-lg shadow-md p-6">
				<h2 class="widget-title text-xl font-bold text-primary-800 mb-4"><?php esc_html_e( 'Tags', 'aqualuxe' ); ?></h2>
				<div class="tagcloud flex flex-wrap gap-2">
					<?php
					$tags = get_tags( array( 'orderby' => 'count', 'order' => 'DESC', 'number' => 20 ) );
					if ( $tags ) {
						foreach ( $tags as $tag ) {
							echo '<a href="' . esc_url( get_tag_link( $tag->term_id ) ) . '" class="inline-block bg-gray-100 hover:bg-primary-100 text-gray-700 hover:text-primary-700 px-3 py-1 rounded-full text-sm transition-colors">' . esc_html( $tag->name ) . '</a>';
						}
					} else {
						echo '<p>' . esc_html__( 'No tags found.', 'aqualuxe' ) . '</p>';
					}
					?>
				</div>
			</div>
			
			<div class="widget widget_about bg-white rounded-lg shadow-md p-6">
				<h2 class="widget-title text-xl font-bold text-primary-800 mb-4"><?php esc_html_e( 'About Us', 'aqualuxe' ); ?></h2>
				<div class="flex flex-col items-center text-center">
					<?php
					if ( has_custom_logo() ) :
						$custom_logo_id = get_theme_mod( 'custom_logo' );
						$logo = wp_get_attachment_image_src( $custom_logo_id, 'full' );
						?>
						<img src="<?php echo esc_url( $logo[0] ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" class="mb-4 max-h-16">
					<?php else : ?>
						<h3 class="text-xl font-bold text-primary-700 mb-4"><?php bloginfo( 'name' ); ?></h3>
					<?php endif; ?>
					
					<p class="text-gray-600 mb-4"><?php echo esc_html( get_theme_mod( 'aqualuxe_footer_about', 'AquaLuxe offers premium water-themed products and services with elegance and sophistication. Our commitment to quality and sustainability sets us apart in the industry.' ) ); ?></p>
					
					<a href="<?php echo esc_url( home_url( '/about/' ) ); ?>" class="inline-flex items-center text-primary-600 hover:text-primary-800 transition-colors">
						<?php esc_html_e( 'Read More', 'aqualuxe' ); ?> <i class="fas fa-arrow-right ml-1"></i>
					</a>
				</div>
			</div>
		<?php endif; ?>
	</div>
</aside><!-- #secondary -->