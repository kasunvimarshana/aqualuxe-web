<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header(); ?>
<div class="alx-container py-8">
	<header class="mb-6"><h1 class="text-2xl font-bold"><?php printf( esc_html__( 'Search results for “%s”', 'aqualuxe' ), get_search_query() ); ?></h1></header>
	<?php if ( have_posts() ) : ?>
		<div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
			<?php while ( have_posts() ) : the_post(); ?>
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'border rounded overflow-hidden' ); ?>>
					<a href="<?php the_permalink(); ?>" class="block">
						<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'alx_card', [ 'loading' => 'lazy', 'class' => 'w-full h-auto' ] ); } ?>
						<h2 class="p-4 text-lg font-semibold"><?php the_title(); ?></h2>
					</a>
				</article>
			<?php endwhile; ?>
		</div>
		<div class="mt-6"><?php the_posts_pagination(); ?></div>
	<?php else : ?>
		<p><?php esc_html_e( 'No results found.', 'aqualuxe' ); ?></p>
	<?php endif; ?>
</div>
<?php get_footer(); ?>
