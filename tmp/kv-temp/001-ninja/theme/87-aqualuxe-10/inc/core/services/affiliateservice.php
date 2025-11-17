<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class for integrating with an affiliate marketing plugin.
 *
 * This service is designed to work with AffiliateWP. It provides a unified API
 * for checking affiliate status, getting referral URLs, and fetching stats.
 */
class AffiliateService
{
    private ?string $active_plugin = null;

    public function __construct()
    {
        if (class_exists('Affiliate_WP')) {
            $this->active_plugin = 'affiliatewp';
        }
    }

    /**
     * Check if a supported affiliate plugin is active.
     */
    public function is_active(): bool
    {
        return $this->active_plugin !== null;
    }

    /**
     * Check if the current user is an active affiliate.
     *
     * @param int|null $user_id The user ID. Defaults to the current user.
     * @return bool
     */
    public function is_affiliate(?int $user_id = null): bool
    {
        if (!$this->is_active() || !function_exists('affwp_is_affiliate')) {
            return false;
        }
        $user_id = $user_id ?? \get_current_user_id();
        return affwp_is_affiliate($user_id);
    }

    /**
     * Get the affiliate's referral URL.
     *
     * @param int|null $user_id The user ID. Defaults to the current user.
     * @return string The referral URL or an empty string.
     */
    public function get_referral_url(?int $user_id = null): string
    {
        if (!$this->is_affiliate($user_id) || !function_exists('affwp_get_affiliate_id')) {
            return '';
        }
        $user_id = $user_id ?? \get_current_user_id();
        $affiliate_id = affwp_get_affiliate_id($user_id);

        if (function_exists('affwp_get_referral_url')) {
            return affwp_get_referral_url($affiliate_id);
        }
        return '';
    }

    /**
     * Get the URL for the affiliate area.
     *
     * @return string
     */
    public function get_affiliate_area_url(): string
    {
        if (!$this->is_active() || !function_exists('affwp_get_affiliate_area_page_id')) {
            return '#';
        }
        $page_id = affwp_get_affiliate_area_page_id();
        return $page_id ? \get_permalink($page_id) : '#';
    }

    /**
     * Get basic stats for an affiliate.
     *
     * @param int|null $user_id
     * @return array
     */
    public function get_affiliate_stats(?int $user_id = null): array
    {
        if (!$this->is_affiliate($user_id)) {
            return [];
        }
        $user_id = $user_id ?? \get_current_user_id();
        $affiliate_id = affwp_get_affiliate_id($user_id);

        return [
            'referrals' => affwp_get_affiliate_referral_count($affiliate_id),
            'earnings'  => affwp_get_affiliate_earnings($affiliate_id, true),
            'rate'      => affwp_get_affiliate_rate($affiliate_id, true),
        ];
    }
}
