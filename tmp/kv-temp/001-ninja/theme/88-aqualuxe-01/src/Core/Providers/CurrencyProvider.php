<?php
namespace Aqualuxe\Core\Providers;

use Aqualuxe\Core\ServiceProviderInterface;

class CurrencyProvider implements ServiceProviderInterface
{
	private array $allowed = ['USD' => '$', 'EUR' => '€', 'GBP' => '£'];

	public function register(): void
	{
		add_action('init', [$this, 'detect']);
		add_filter('aqualuxe_currency_current', [$this, 'current']);
		add_filter('aqualuxe_currency_format', [$this, 'format'], 10, 2);
	}

	public function detect(): void
	{
		$cur = isset($_GET['currency']) ? strtoupper(sanitize_text_field($_GET['currency'])) : null; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ($cur && isset($this->allowed[$cur])) {
			setcookie('aqlx_currency', $cur, time()+MONTH_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true);
			$_COOKIE['aqlx_currency'] = $cur;
		}
	}

	public function current($default = 'USD')
	{
		$cur = strtoupper(sanitize_text_field($_COOKIE['aqlx_currency'] ?? $default));
		return isset($this->allowed[$cur]) ? $cur : $default;
	}

	public function format($amount, $currency = null)
	{
		$cur = $currency ?: apply_filters('aqualuxe_currency_current', 'USD');
		$symbol = $this->allowed[$cur] ?? '$';
		$fmt = number_format_i18n((float) $amount, 2);
		return sprintf('%s%s', $symbol, $fmt);
	}
}
