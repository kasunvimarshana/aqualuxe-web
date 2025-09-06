<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<div class="alx-container py-8">
	<header class="mb-6"><h1 class="text-2xl font-bold"><?php esc_html_e( 'Events', 'aqualuxe' ); ?></h1></header>
	<?php if ( have_posts() ) : ?>
		<div class="grid gap-6 md:grid-cols-3">
			<?php while ( have_posts() ) : the_post(); ?>
				<?php $pid = function_exists('get_the_ID') ? get_the_ID() : 0; ?>
				<article id="post-<?php echo (int) $pid; ?>" <?php post_class( 'border rounded p-4' ); ?>>
					<h2 class="text-lg font-semibold mb-2"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="text-sm opacity-80"><?php the_excerpt(); ?></div>
				</article>
			<?php endwhile; ?>
		</div>
		<div class="mt-6"><?php the_posts_pagination(); ?></div>
	<?php else : ?>
		<p><?php esc_html_e( 'No events available.', 'aqualuxe' ); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
