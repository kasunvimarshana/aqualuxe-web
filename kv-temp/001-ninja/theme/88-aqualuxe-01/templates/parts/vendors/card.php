<?php
/** @var \WP_User $aqlx_vendor */
$aqlx_vendor = \get_query_var('aqlx_vendor');
if (!$aqlx_vendor instanceof \WP_User) return;
$slug = $aqlx_vendor->user_nicename;
$base = (string) \apply_filters('aqualuxe/vendor_base', 'vendors');
$url = \home_url(\user_trailingslashit($base . '/' . $slug));
?>
<article class="vendor-card" aria-labelledby="vendor-<?php echo (int) $aqlx_vendor->ID; ?>-title">
	<a class="vendor-link" href="<?php echo \esc_url($url); ?>" aria-hidden="true" tabindex="-1">
		<?php echo \get_avatar($aqlx_vendor->ID, 96); ?>
	</a>
	<h3 id="vendor-<?php echo (int) $aqlx_vendor->ID; ?>-title" class="vendor-title">
		<a href="<?php echo \esc_url($url); ?>"><?php echo \esc_html($aqlx_vendor->display_name ?: $slug); ?></a>
	</h3>
</article>
