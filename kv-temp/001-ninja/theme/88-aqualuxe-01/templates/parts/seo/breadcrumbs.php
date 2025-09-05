<?php
// Minimal breadcrumb with schema.org BreadcrumbList
$items = [];
$items[] = ['url' => \home_url('/'), 'label' => \get_bloginfo('name')];

$base = (string) \apply_filters('aqualuxe/vendor_base', 'vendors');
$is_vendor_archive = (bool) \get_query_var('vendors');
$vendor_slug = (string) \get_query_var('vendor_store');
if ($is_vendor_archive) {
	$items[] = ['url' => \home_url(\user_trailingslashit($base)), 'label' => \__('Vendors', 'aqualuxe')];
} elseif (!empty($vendor_slug)) {
	$items[] = ['url' => \home_url(\user_trailingslashit($base)), 'label' => \__('Vendors', 'aqualuxe')];
	$user = \get_user_by('slug', $vendor_slug);
	if ($user) {
		$items[] = ['url' => \home_url(\user_trailingslashit($base . '/' . $vendor_slug)), 'label' => $user->display_name ?: $user->user_nicename];
	}
} elseif (\is_singular()) {
	$items[] = ['url' => \get_permalink(), 'label' => \get_the_title()];
} elseif (\is_post_type_archive()) {
	$items[] = ['url' => \get_post_type_archive_link(\get_post_type()), 'label' => \post_type_archive_title('', false)];
}
?>
<nav class="breadcrumbs container" aria-label="Breadcrumbs" itemscope itemtype="https://schema.org/BreadcrumbList">
	<ol>
		<?php foreach ($items as $i => $it): ?>
			<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
				<a itemprop="item" href="<?php echo \esc_url($it['url']); ?>">
					<span itemprop="name"><?php echo \esc_html($it['label']); ?></span>
				</a>
				<meta itemprop="position" content="<?php echo (int) ($i+1); ?>" />
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
