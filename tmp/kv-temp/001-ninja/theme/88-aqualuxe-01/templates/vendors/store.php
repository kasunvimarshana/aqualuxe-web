<?php
/** Vendor store page: shows vendor info + listings. */
if (!defined('ABSPATH')) { exit; }
$slug = \get_query_var('vendor_store');
$vendor = $slug ? \get_user_by('slug', $slug) : null;
$roles = $vendor ? (array) ($vendor->roles ?? []) : [];
if (!$vendor || !in_array('vendor', $roles, true)) { \status_header(404); \nocache_headers(); include \get_404_template(); return; }
\get_header();
$paged = max(1, (int) ($_GET['pg'] ?? 1)); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$listing_q = new \WP_Query([
	'post_type' => 'listing',
	'posts_per_page' => 12,
	'paged' => $paged,
	'author' => (int) $vendor->ID,
]);
?>
<main id="primary" class="site-main container" role="main">
	<?php \do_action('aqualuxe/breadcrumbs'); ?>
	<?php $banner = \get_user_meta($vendor->ID, 'aqlx_vendor_banner', true); $logo = \get_user_meta($vendor->ID, 'aqlx_vendor_logo', true); $website = \get_user_meta($vendor->ID, 'aqlx_vendor_website', true); ?>
	<header class="vendor-header" style="display:flex;flex-direction:column;gap:1rem;align-items:flex-start;">
		<?php if (!empty($banner)): ?>
			<div class="vendor-banner" style="width:100%;max-height:220px;overflow:hidden;border-radius:.5rem;">
				<img src="<?php echo \esc_url($banner); ?>" alt="" style="width:100%;height:auto;display:block;" />
			</div>
		<?php endif; ?>
		<div class="vendor-meta" style="display:flex;gap:1rem;align-items:center;">
			<div class="avatar">
				<?php if (!empty($logo)) { echo '<img src="' . \esc_url($logo) . '" alt="" style="width:96px;height:96px;border-radius:50%;object-fit:cover;" />'; } else { echo \get_avatar($vendor->ID, 96); } ?>
			</div>
			<div>
				<h1 class="vendor-name"><?php echo \esc_html($vendor->display_name ?: $vendor->user_nicename); ?></h1>
				<p class="vendor-bio"><?php echo \esc_html(\get_user_meta($vendor->ID, 'description', true)); ?></p>
				<nav class="vendor-links" aria-label="<?php \esc_attr_e('Vendor links', 'aqualuxe'); ?>" style="display:flex;gap:.75rem;flex-wrap:wrap;">
					<?php if (!empty($website)) { echo '<a class="btn" href="' . \esc_url($website) . '" target="_blank" rel="noopener noreferrer">' . \esc_html__('Website', 'aqualuxe') . '</a>'; }
					$fb = \get_user_meta($vendor->ID, 'aqlx_vendor_facebook', true);
					$ig = \get_user_meta($vendor->ID, 'aqlx_vendor_instagram', true);
					$tw = \get_user_meta($vendor->ID, 'aqlx_vendor_twitter', true);
					if (!empty($fb)) { echo '<a href="' . \esc_url($fb) . '" target="_blank" rel="noopener noreferrer">Facebook</a>'; }
					if (!empty($ig)) { echo '<a href="' . \esc_url($ig) . '" target="_blank" rel="noopener noreferrer">Instagram</a>'; }
					if (!empty($tw)) { echo '<a href="' . \esc_url($tw) . '" target="_blank" rel="noopener noreferrer">Twitter</a>'; }
					?>
				</nav>
			</div>
		</div>
	</header>

	<section class="vendor-listings" aria-label="<?php \esc_attr_e('Vendor listings', 'aqualuxe'); ?>">
		<h2><?php \esc_html_e('Listings', 'aqualuxe'); ?></h2>
		<div class="grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
			<?php if ($listing_q->have_posts()): while ($listing_q->have_posts()): $listing_q->the_post(); ?>
				<article id="post-<?php \the_ID(); ?>" <?php \post_class('listing-card'); ?>>
					<?php if (\has_post_thumbnail()) { \the_post_thumbnail('medium'); } ?>
					<h3 class="listing-title"><a href="<?php \the_permalink(); ?>"><?php \the_title(); ?></a></h3>
					<div class="excerpt"><?php \the_excerpt(); ?></div>
				</article>
			<?php endwhile; else: ?>
				<p><?php \esc_html_e('No listings yet.', 'aqualuxe'); ?></p>
			<?php endif; \wp_reset_postdata(); ?>
		</div>
		<?php
			echo \paginate_links([
				'total' => (int) $listing_q->max_num_pages,
				'current' => $paged,
				'format' => '?pg=%#%'
			]);
		?>
	</section>
</main>
<?php \get_footer();
