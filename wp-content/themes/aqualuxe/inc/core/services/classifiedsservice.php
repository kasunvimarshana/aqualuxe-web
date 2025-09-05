<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class to abstract interactions with a classifieds plugin.
 *
 * This service is designed to work with the 'WP Adverts' plugin (wpadverts).
 * It provides a unified API for fetching and displaying classified ads.
 */
class ClassifiedsService
{
    private ?string $active_plugin = null;

    public function __construct()
    {
        if (function_exists('adverts_init')) {
            $this->active_plugin = 'wpadverts';
        }
    }

    /**
     * Check if a supported classifieds plugin is active.
     */
    public function is_active(): bool
    {
        return $this->active_plugin !== null;
    }

    /**
     * Get recent classified ads.
     *
     * @param int $count Number of ads to retrieve.
     * @return \WP_Query|null A WP_Query object with the ads, or null if plugin is inactive.
     */
    public function get_recent_ads(int $count = 5): ?\WP_Query
    {
        if (!$this->is_active()) {
            return null;
        }

        $args = [
            'post_type'      => 'advert',
            'posts_per_page' => $count,
            'post_status'    => 'publish',
            'orderby'        => 'date',
            'order'          => 'DESC',
        ];

        return new \WP_Query($args);
    }

    /**
     * Get the URL for the main ads list page.
     *
     * @return string
     */
    public function get_ads_list_url(): string
    {
        if (!$this->is_active()) {
            return '#';
        }
        // The 'adverts_get_main_page_id' function returns the ID of the page
        // that contains the [adverts_list] shortcode.
        $page_id = \adverts_get_main_page_id();
        return $page_id ? \get_permalink($page_id) : '#';
    }

    /**
     * Get the URL to post a new ad.
     *
     * @return string
     */
    public function get_post_ad_url(): string
    {
        if (!$this->is_active()) {
            return '#';
        }
        // Similarly, this gets the ID of the page with [adverts_add].
        $page_id = \adverts_get_main_page_id('add');
        return $page_id ? \get_permalink($page_id) : '#';
    }
}
