<?php
/** Vendor archive page: lists users with role vendor. */
if (!defined('ABSPATH')) { exit; }
\get_header();
$pg = max(1, (int) ($_GET['pg'] ?? 1)); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$per = 12;
$args = [
	'role' => 'vendor',
	'number' => $per,
	'paged' => $pg,
];
// WP_User_Query uses offset rather than paged by default
$args['offset'] = ($pg - 1) * $per;
$q = new \WP_User_Query($args);
$total = (int) $q->get_total();
$users = (array) $q->get_results();
?>
<main id="primary" class="site-main container" role="main">
	<?php \do_action('aqualuxe/breadcrumbs'); ?>
	<h1><?php \esc_html_e('Vendors', 'aqualuxe'); ?></h1>
	<div class="grid vendors-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">
		<?php foreach ($users as $u): ?>
			<?php \set_query_var('aqlx_vendor', $u); \get_template_part('templates/parts/vendors/card'); ?>
		<?php endforeach; ?>
	</div>
	<?php
		$pages = (int) ceil(max(1, $total) / $per);
		if ($pages > 1) {
			echo '<nav class="pagination" role="navigation" aria-label="Pagination">';
			echo \paginate_links([
				'total' => $pages,
				'current' => $pg,
				'format' => '?pg=%#%'
			]);
			echo '</nav>';
		}
	?>
</main>
<?php \get_footer();
