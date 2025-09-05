<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class to abstract interactions with multicurrency plugins for WooCommerce.
 *
 * This service detects popular currency switcher plugins like WPML Multicurrency,
 * Aelia Currency Switcher, and WOOCS, and provides a unified API to the theme.
 */
class CurrencyService
{
    private ?string $active_plugin = null;
    private array $currencies = [];

    public function __construct()
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        if (class_exists('woocommerce_wpml')) {
            $this->active_plugin = 'wpml';
        } elseif (class_exists('WC_Aelia_CurrencySwitcher')) {
            $this->active_plugin = 'aelia';
        } elseif (class_exists('WOOCS')) {
            $this->active_plugin = 'woocs';
        }
    }

    /**
     * Check if a supported multicurrency plugin is active.
     */
    public function is_active(): bool
    {
        return $this->active_plugin !== null;
    }

    /**
     * Get the list of available currencies in a standardized format.
     *
     * @return array An array of currency arrays.
     */
    public function get_currencies(): array
    {
        if (!$this->is_active() || !empty($this->currencies)) {
            return $this->currencies;
        }

        switch ($this->active_plugin) {
            case 'wpml':
                $this->currencies = $this->get_wpml_currencies();
                break;
            case 'aelia':
                $this->currencies = $this->get_aelia_currencies();
                break;
            case 'woocs':
                $this->currencies = $this->get_woocs_currencies();
                break;
            default:
                $this->currencies = [];
        }
        return $this->currencies;
    }

    /**
     * Get the currently selected currency code.
     */
    public function get_current_currency(): string
    {
        if (!$this->is_active()) {
            return \get_woocommerce_currency();
        }

        switch ($this->active_plugin) {
            case 'wpml':
                global $woocommerce_wpml;
                return $woocommerce_wpml->multi_currency->get_client_currency();
            case 'aelia':
                return \WC_Aelia_CurrencySwitcher::instance()->get_selected_currency();
            case 'woocs':
                global $WOOCS;
                return $WOOCS->current_currency;
            default:
                return \get_woocommerce_currency();
        }
    }

    private function get_wpml_currencies(): array
    {
        // Implementation for WPML currency switcher
        // This is a placeholder. Actual implementation requires WPML API.
        return [];
    }

    private function get_aelia_currencies(): array
    {
        $aelia_instance = \WC_Aelia_CurrencySwitcher::instance();
        $enabled_currencies = $aelia_instance->get_enabled_currencies();
        $current_currency = $this->get_current_currency();
        $output = [];

        foreach ($enabled_currencies as $code) {
             $output[$code] = [
                'code' => $code,
                'symbol' => \get_woocommerce_currency_symbol($code),
                'is_current' => $code === $current_currency,
                'switch_url' => \add_query_arg('aelia_cs_currency', $code),
            ];
        }
        return $output;
    }

    private function get_woocs_currencies(): array
    {
        global $WOOCS;
        if (!isset($WOOCS)) return [];

        $currencies = $WOOCS->get_currencies();
        $current_currency = $this->get_current_currency();
        $output = [];

        foreach ($currencies as $code => $currency) {
            $output[$code] = [
                'code' => $code,
                'symbol' => $currency['symbol'],
                'is_current' => $code === $current_currency,
                'switch_url' => \add_query_arg('currency', $code),
            ];
        }
        return $output;
    }
}
