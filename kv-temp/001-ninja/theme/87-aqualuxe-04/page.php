<?php get_header(); ?>
<main id="primary" class="site-main container mx-auto px-4 py-8" role="main">
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class( 'prose dark:prose-invert max-w-none' ); ?> itemscope itemtype="https://schema.org/Article">
			<header class="mb-6">
				<h1 class="text-3xl font-semibold"><?php the_title(); ?></h1>
			</header>
			<div class="entry-content" itemprop="articleBody"><?php the_content(); ?></div>
		</article>
		<?php comments_template(); ?>
	<?php endwhile; ?>
</main>
<?php get_footer(); ?>
