<?php
namespace AquaLuxe\Core\Services;

/**
 * Service class to abstract interactions with event and ticketing plugins.
 *
 * Detects if a supported plugin (e.g., The Events Calendar) is active
 * and provides a unified API for event-related data.
 */
class EventService
{
    private ?string $active_plugin = null;

    public function __construct()
    {
        if (class_exists('Tribe__Events__Main')) {
            $this->active_plugin = 'the-events-calendar';
        }
        // Add checks for other event plugins here.
    }

    /**
     * Check if a supported event plugin is active.
     */
    public function is_active(): bool
    {
        return $this->active_plugin !== null;
    }

    /**
     * Check if a given post is an event.
     *
     * @param int|\WP_Post $post_id The post ID or post object.
     * @return bool
     */
    public function is_event($post_id): bool
    {
        if (!$this->is_active()) {
            return false;
        }

        if ($this->active_plugin === 'the-events-calendar' && function_exists('tribe_is_event')) {
            return \tribe_is_event($post_id);
        }

        return false;
    }

    /**
     * Get event details for a post.
     *
     * @param int $post_id
     * @return array|null A standardized array of event details.
     */
    public function get_event_details(int $post_id): ?array
    {
        if (!$this->is_event($post_id)) {
            return null;
        }

        if ($this->active_plugin === 'the-events-calendar') {
            $start_date = \tribe_get_start_date($post_id, true, 'Y-m-d H:i:s');
            $end_date = \tribe_get_end_date($post_id, true, 'Y-m-d H:i:s');

            return [
                'start_date' => $start_date,
                'end_date' => $end_date,
                'is_all_day' => \tribe_event_is_all_day($post_id),
                'venue' => \tribe_get_venue($post_id),
                'cost' => \tribe_get_cost($post_id, true),
            ];
        }

        return null;
    }
}
