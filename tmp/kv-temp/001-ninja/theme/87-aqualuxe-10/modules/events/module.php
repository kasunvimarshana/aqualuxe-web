<?php
namespace AquaLuxe\Modules;

use AquaLuxe\Core\Contracts\ModuleInterface;
use AquaLuxe\Core\Services\EventService;

/**
 * Events Module.
 *
 * Integrates with event plugins to display event information.
 */
class Events implements ModuleInterface
{
    private ?EventService $event_service = null;

    public function boot(): void
    {
        $this->event_service = new EventService();

        if (!$this->event_service->is_active()) {
            return;
        }

        \add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);
        
        // Add a summary box to the top of single event pages.
        \add_action('tribe_events_single_event_before_the_content', [$this, 'display_event_summary']);
    }

    public function enqueue_assets(): void
    {
        $css_path = '/modules/events/assets/dist/events.css';
        $css_file = AQUALUXE_DIR . $css_path;
        if (file_exists($css_file)) {
            \wp_enqueue_style(
                'aqualuxe-events',
                AQUALUXE_URI . $css_path,
                [],
                filemtime($css_file)
            );
        }
    }

    /**
     * Displays a summary of event details on the single event page.
     */
    public function display_event_summary(): void
    {
        $post_id = \get_the_ID();

        if ($this->event_service->is_event($post_id)) {
            $details = $this->event_service->get_event_details($post_id);
            
            \get_template_part('modules/events/templates/event-summary', null, [
                'details' => $details,
            ]);
        }
    }
}
