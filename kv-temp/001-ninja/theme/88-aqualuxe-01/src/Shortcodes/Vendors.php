<?php
namespace Aqualuxe\Shortcodes;

class Vendors
{
	public function register(): void
	{
		\add_shortcode('aqualuxe_vendors', [$this, 'render']);
		\add_shortcode('aqualuxe_vendor_card', [$this, 'renderCard']);
	}

	public function render($atts = []): string
	{
		$atts = \shortcode_atts(['per_page' => 12], $atts, 'aqualuxe_vendors');
		$pg = max(1, (int) ($_GET['pg'] ?? 1)); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$q = new \WP_User_Query([
			'role' => 'vendor',
			'number' => (int) $atts['per_page'],
			'offset' => ((int)$atts['per_page']) * ($pg - 1),
		]);
		$users = (array) $q->get_results();
		$total = (int) $q->get_total();
		ob_start();
		echo '<div class="grid vendors-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:1rem;">';
		foreach ($users as $u) { \set_query_var('aqlx_vendor', $u); \get_template_part('templates/parts/vendors/card'); }
		echo '</div>';
		$pages = (int) ceil(max(1, $total) / (int) $atts['per_page']);
		if ($pages > 1) {
			echo '<nav class="pagination" role="navigation" aria-label="Pagination">';
			echo \paginate_links(['total' => $pages, 'current' => $pg, 'format' => '?pg=%#%']);
			echo '</nav>';
		}
		return ob_get_clean();
	}

	public function renderCard($atts = []): string
	{
		$atts = \shortcode_atts(['id' => 0, 'slug' => ''], $atts, 'aqualuxe_vendor_card');
		$user = null;
		if (!empty($atts['id'])) { $user = \get_user_by('id', (int) $atts['id']); }
		if (!$user && !empty($atts['slug'])) { $user = \get_user_by('slug', \sanitize_title($atts['slug'])); }
		if (!$user || !\user_can($user, 'read')) { return ''; }
		ob_start();
		\set_query_var('aqlx_vendor', $user);
		\get_template_part('templates/parts/vendors/card');
		return ob_get_clean();
	}
}
