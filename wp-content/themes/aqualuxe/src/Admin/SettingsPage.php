<?php
namespace Aqualuxe\Admin;

use Aqualuxe\Core\Config;

class SettingsPage
{
	public function registerMenu(): void
	{
		add_theme_page(__('Aqualuxe Settings', 'aqualuxe'), __('Aqualuxe', 'aqualuxe'), 'manage_options', 'aqualuxe-settings', [$this, 'render']);
	}

	public function registerSettings(): void
	{
		register_setting('aqualuxe', 'aqualuxe_skin', ['type' => 'string', 'sanitize_callback' => 'sanitize_text_field']);
		register_setting('aqualuxe', 'aqualuxe_features', ['type' => 'array', 'sanitize_callback' => [$this, 'sanitizeFeatures']]);
		add_settings_section('aqlx_general', __('General', 'aqualuxe'), '__return_false', 'aqualuxe');
		add_settings_field('aqualuxe_skin', __('Active Skin', 'aqualuxe'), [$this, 'fieldSkin'], 'aqualuxe', 'aqlx_general');
		add_settings_field('aqualuxe_features', __('Features', 'aqualuxe'), [$this, 'fieldFeatures'], 'aqualuxe', 'aqlx_general');
	}

	public function sanitizeFeatures($value): array
	{
		$keys = ['listings','currency','vendors','languages','classifieds'];
		$out = [];
		foreach ($keys as $k) { $out[$k] = !empty($value[$k]); }
		return $out;
	}

	public function fieldSkin(): void
	{
		$value = get_option('aqualuxe_skin', Config::get('skin', 'skins/default.css'));
		$options = [
			'skins/default.css' => 'Default',
			'skins/dark.css' => 'Dark',
		];
		echo '<select name="aqualuxe_skin">';
		foreach ($options as $k => $label) {
			$sel = selected($value, $k, false);
			echo '<option value="' . esc_attr($k) . '" ' . $sel . '>' . esc_html($label) . '</option>';
		}
		echo '</select>';
	}

	public function fieldFeatures(): void
	{
		$defaults = (array) Config::get('features', []);
		$values = (array) get_option('aqualuxe_features', []);
		$keys = [
			'listings' => __('Listings', 'aqualuxe'),
			'currency' => __('Currency', 'aqualuxe'),
			'vendors' => __('Vendors', 'aqualuxe'),
			'languages' => __('Languages (hreflang)', 'aqualuxe'),
			'classifieds' => __('Classifieds taxonomies', 'aqualuxe'),
		];
		echo '<fieldset>';
		foreach ($keys as $k => $label) {
			$checked = isset($values[$k]) ? (bool) $values[$k] : (!empty($defaults[$k]));
			echo '<label style="display:block;margin:.25rem 0"><input type="checkbox" name="aqualuxe_features[' . esc_attr($k) . ']" value="1" ' . checked(true, $checked, false) . '> ' . esc_html($label) . '</label>';
		}
		echo '</fieldset>';
	}

	public function render(): void
	{
		echo '<div class="wrap"><h1>' . esc_html__('Aqualuxe Settings', 'aqualuxe') . '</h1>';
		echo '<form method="post" action="options.php">';
		settings_fields('aqualuxe');
		do_settings_sections('aqualuxe');
		submit_button();
		echo '</form></div>';
	}
}
