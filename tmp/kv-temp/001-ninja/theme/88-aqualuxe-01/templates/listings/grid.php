<?php
/** @var array $aqlx_listings_ctx */
$aqlx_listings_ctx = get_query_var('aqlx_listings_ctx');
$q = $aqlx_listings_ctx['query'] ?? null;
?>
<div class="listings-grid container" role="region" aria-label="Listings">
	<?php if ($q && $q->have_posts()) : ?>
		<div class="grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
		<?php while ($q->have_posts()) : $q->the_post(); ?>
			<article id="post-<?php the_ID(); ?>" <?php post_class('listing-card'); ?> aria-labelledby="title-<?php the_ID(); ?>">
				<?php if (has_post_thumbnail()) : ?>
					<a href="<?php the_permalink(); ?>" class="thumb" aria-hidden="true" tabindex="-1"><?php the_post_thumbnail('medium'); ?></a>
				<?php endif; ?>
				<h3 id="title-<?php the_ID(); ?>" class="listing-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
				<div class="excerpt"><?php the_excerpt(); ?></div>
			</article>
		<?php endwhile; ?>
		</div>
		<nav class="pagination" role="navigation" aria-label="Pagination">
			<?php
				echo paginate_links([
					'total' => $q->max_num_pages,
					'current' => max(1, (int) ($_GET['pg'] ?? 1)), // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					'format' => '?pg=%#%'
				]);
			?>
		</nav>
	<?php else : ?>
		<p><?php esc_html_e('No listings found.', 'aqualuxe'); ?></p>
	<?php endif; ?>
</div>
