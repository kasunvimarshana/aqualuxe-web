<?php
namespace Aqualuxe\Shortcodes;

class CurrencySwitcher
{
	public function register(): void
	{
		add_shortcode('aqualuxe_currency_switcher', [$this, 'render']);
	}

	public function render($atts = []): string
	{
		$current = apply_filters('aqualuxe_currency_current', 'USD');
		$currencies = ['USD','EUR','GBP'];
		$out = '<form method="get" class="aqlx-currency-switcher" aria-label="Currency switcher">';
		$out .= '<select name="currency" onchange="this.form.submit()">';
		foreach ($currencies as $cur) {
			$sel = selected($current, $cur, false);
			$out .= sprintf('<option value="%1$s" %3$s>%2$s</option>', esc_attr($cur), esc_html($cur), $sel);
		}
		$out .= '</select>';
		$out .= '<noscript><button type="submit">Set</button></noscript>';
		$out .= '</form>';
		return $out;
	}
}
