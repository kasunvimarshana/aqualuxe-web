<?php
/**
 * Template part for displaying posts
 *
 * @package AquaLuxe
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'card fade-in' ); ?>>
	<?php if ( has_post_thumbnail() ) : ?>
	<div class="card-image">
		<a href="<?php the_permalink(); ?>" class="block aspect-w-16 aspect-h-9 overflow-hidden">
			<?php
			the_post_thumbnail( 'aqualuxe-featured', array(
				'class' => 'w-full h-full object-cover transform hover:scale-105 transition-transform duration-500',
				'alt' => get_the_title(),
			) );
			?>
		</a>
		
		<?php if ( is_sticky() ) : ?>
		<div class="absolute top-4 left-4">
			<span class="inline-flex items-center px-3 py-1 text-xs font-semibold text-white bg-primary-600 rounded-full">
				<?php esc_html_e( 'Featured', 'aqualuxe' ); ?>
			</span>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<div class="card-body">
		<header class="entry-header mb-4">
			<div class="entry-meta text-sm text-gray-600 dark:text-gray-400 mb-2">
				<time datetime="<?php echo esc_attr( get_the_date( DATE_W3C ) ); ?>" class="published">
					<?php echo esc_html( get_the_date() ); ?>
				</time>
				
				<?php if ( has_category() ) : ?>
				<span class="separator mx-2">•</span>
				<div class="categories inline">
					<?php the_category( ', ' ); ?>
				</div>
				<?php endif; ?>
				
				<span class="separator mx-2">•</span>
				<?php echo aqualuxe_reading_time(); ?>
			</div>
			
			<?php
			if ( is_singular() ) :
				the_title( '<h1 class="entry-title text-3xl font-bold text-gray-900 dark:text-white mb-4">', '</h1>' );
			else :
				the_title( '<h2 class="entry-title text-xl font-semibold text-gray-900 dark:text-white mb-3 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-200"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif;
			?>
		</header>

		<div class="entry-content text-gray-600 dark:text-gray-300 leading-relaxed">
			<?php
			if ( is_singular() || is_home() ) :
				the_content(
					sprintf(
						wp_kses(
							/* translators: %s: Name of current post. Only visible to screen readers */
							__( 'Continue reading<span class="sr-only"> "%s"</span>', 'aqualuxe' ),
							array(
								'span' => array(
									'class' => array(),
								),
							)
						),
						wp_kses_post( get_the_title() )
					)
				);
			else :
				echo '<p>' . esc_html( aqualuxe_custom_excerpt( 25 ) ) . '</p>';
			endif;
			?>
		</div>

		<?php if ( ! is_singular() ) : ?>
		<footer class="entry-footer mt-6">
			<a href="<?php the_permalink(); ?>" class="inline-flex items-center text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium text-sm transition-colors duration-200">
				<?php esc_html_e( 'Read More', 'aqualuxe' ); ?>
				<svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
				</svg>
			</a>
		</footer>
		<?php endif; ?>
	</div>
</article>