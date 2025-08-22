<?php
/**
 * Events Calendar Admin
 *
 * @package AquaLuxe
 * @subpackage Events
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * AquaLuxe_Events_Admin class.
 */
class AquaLuxe_Events_Admin {

    /**
     * Constructor.
     */
    public function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks.
     */
    private function init_hooks() {
        // Admin menu
        add_action('admin_menu', array($this, 'admin_menu'));
        
        // Meta boxes
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_meta_boxes'), 10, 2);
        
        // Admin scripts and styles
        add_action('admin_enqueue_scripts', array($this, 'admin_scripts'));
        
        // Admin columns
        add_filter('manage_aqualuxe_event_posts_columns', array($this, 'event_columns'));
        add_action('manage_aqualuxe_event_posts_custom_column', array($this, 'event_column_content'), 10, 2);
        add_filter('manage_edit-aqualuxe_event_sortable_columns', array($this, 'event_sortable_columns'));
        
        add_filter('manage_aqualuxe_ticket_posts_columns', array($this, 'ticket_columns'));
        add_action('manage_aqualuxe_ticket_posts_custom_column', array($this, 'ticket_column_content'), 10, 2);
        add_filter('manage_edit-aqualuxe_ticket_sortable_columns', array($this, 'ticket_sortable_columns'));
        
        // Category admin
        add_action('aqualuxe_event_category_add_form_fields', array($this, 'add_category_fields'));
        add_action('aqualuxe_event_category_edit_form_fields', array($this, 'edit_category_fields'), 10, 2);
        add_action('created_aqualuxe_event_category', array($this, 'save_category_fields'), 10, 2);
        add_action('edited_aqualuxe_event_category', array($this, 'save_category_fields'), 10, 2);
        
        // Admin filters
        add_action('restrict_manage_posts', array($this, 'admin_filters'));
        add_filter('parse_query', array($this, 'admin_filter_query'));
    }

    /**
     * Add admin menu items.
     */
    public function admin_menu() {
        add_submenu_page(
            'edit.php?post_type=aqualuxe_event',
            __('Event Settings', 'aqualuxe'),
            __('Settings', 'aqualuxe'),
            'manage_options',
            'aqualuxe-event-settings',
            array($this, 'settings_page')
        );
        
        add_submenu_page(
            'edit.php?post_type=aqualuxe_event',
            __('Event Reports', 'aqualuxe'),
            __('Reports', 'aqualuxe'),
            'manage_options',
            'aqualuxe-event-reports',
            array($this, 'reports_page')
        );
    }

    /**
     * Settings page.
     */
    public function settings_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Save settings
        if (isset($_POST['aqualuxe_events_settings_nonce']) && wp_verify_nonce($_POST['aqualuxe_events_settings_nonce'], 'aqualuxe_events_settings')) {
            $options = array(
                'calendar_page' => isset($_POST['calendar_page']) ? absint($_POST['calendar_page']) : 0,
                'events_per_page' => isset($_POST['events_per_page']) ? absint($_POST['events_per_page']) : 10,
                'default_view' => isset($_POST['default_view']) ? sanitize_text_field($_POST['default_view']) : 'month',
                'show_past_events' => isset($_POST['show_past_events']) ? 1 : 0,
                'enable_tickets' => isset($_POST['enable_tickets']) ? 1 : 0,
                'currency_symbol' => isset($_POST['currency_symbol']) ? sanitize_text_field($_POST['currency_symbol']) : '$',
                'date_format' => isset($_POST['date_format']) ? sanitize_text_field($_POST['date_format']) : 'F j, Y',
                'time_format' => isset($_POST['time_format']) ? sanitize_text_field($_POST['time_format']) : 'g:i a',
                'google_maps_api_key' => isset($_POST['google_maps_api_key']) ? sanitize_text_field($_POST['google_maps_api_key']) : '',
            );
            
            update_option('aqualuxe_events_settings', $options);
            
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Settings saved.', 'aqualuxe') . '</p></div>';
        }
        
        // Get current settings
        $options = get_option('aqualuxe_events_settings', array(
            'calendar_page' => 0,
            'events_per_page' => 10,
            'default_view' => 'month',
            'show_past_events' => 1,
            'enable_tickets' => 1,
            'currency_symbol' => '$',
            'date_format' => 'F j, Y',
            'time_format' => 'g:i a',
            'google_maps_api_key' => '',
        ));
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Event Settings', 'aqualuxe'); ?></h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('aqualuxe_events_settings', 'aqualuxe_events_settings_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="calendar_page"><?php echo esc_html__('Calendar Page', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <?php
                            wp_dropdown_pages(array(
                                'name' => 'calendar_page',
                                'id' => 'calendar_page',
                                'selected' => $options['calendar_page'],
                                'show_option_none' => __('Select a page', 'aqualuxe'),
                            ));
                            ?>
                            <p class="description"><?php echo esc_html__('Select the page where you want to display the events calendar.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="events_per_page"><?php echo esc_html__('Events Per Page', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="number" name="events_per_page" id="events_per_page" value="<?php echo esc_attr($options['events_per_page']); ?>" min="1" max="100" step="1" />
                            <p class="description"><?php echo esc_html__('Number of events to display per page in the events list.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="default_view"><?php echo esc_html__('Default Calendar View', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <select name="default_view" id="default_view">
                                <?php foreach (aqualuxe_get_event_calendar_views() as $view => $label) : ?>
                                    <option value="<?php echo esc_attr($view); ?>" <?php selected($options['default_view'], $view); ?>><?php echo esc_html($label); ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php echo esc_html__('Default view for the events calendar.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo esc_html__('Past Events', 'aqualuxe'); ?>
                        </th>
                        <td>
                            <label for="show_past_events">
                                <input type="checkbox" name="show_past_events" id="show_past_events" value="1" <?php checked($options['show_past_events'], 1); ?> />
                                <?php echo esc_html__('Show past events in the calendar and event lists', 'aqualuxe'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <?php echo esc_html__('Tickets', 'aqualuxe'); ?>
                        </th>
                        <td>
                            <label for="enable_tickets">
                                <input type="checkbox" name="enable_tickets" id="enable_tickets" value="1" <?php checked($options['enable_tickets'], 1); ?> />
                                <?php echo esc_html__('Enable ticket sales for events', 'aqualuxe'); ?>
                            </label>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="currency_symbol"><?php echo esc_html__('Currency Symbol', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="currency_symbol" id="currency_symbol" value="<?php echo esc_attr($options['currency_symbol']); ?>" />
                            <p class="description"><?php echo esc_html__('Currency symbol for ticket prices.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="date_format"><?php echo esc_html__('Date Format', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="date_format" id="date_format" value="<?php echo esc_attr($options['date_format']); ?>" />
                            <p class="description"><?php echo esc_html__('Date format for event dates.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="time_format"><?php echo esc_html__('Time Format', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="time_format" id="time_format" value="<?php echo esc_attr($options['time_format']); ?>" />
                            <p class="description"><?php echo esc_html__('Time format for event times.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">
                            <label for="google_maps_api_key"><?php echo esc_html__('Google Maps API Key', 'aqualuxe'); ?></label>
                        </th>
                        <td>
                            <input type="text" name="google_maps_api_key" id="google_maps_api_key" value="<?php echo esc_attr($options['google_maps_api_key']); ?>" class="regular-text" />
                            <p class="description"><?php echo esc_html__('Google Maps API key for displaying event locations.', 'aqualuxe'); ?></p>
                        </td>
                    </tr>
                </table>
                
                <?php submit_button(__('Save Settings', 'aqualuxe')); ?>
            </form>
        </div>
        <?php
    }

    /**
     * Reports page.
     */
    public function reports_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'overview';
        
        ?>
        <div class="wrap">
            <h1><?php echo esc_html__('Event Reports', 'aqualuxe'); ?></h1>
            
            <nav class="nav-tab-wrapper">
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=aqualuxe_event&page=aqualuxe-event-reports&tab=overview')); ?>" class="nav-tab <?php echo $tab === 'overview' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Overview', 'aqualuxe'); ?></a>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=aqualuxe_event&page=aqualuxe-event-reports&tab=tickets')); ?>" class="nav-tab <?php echo $tab === 'tickets' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Ticket Sales', 'aqualuxe'); ?></a>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=aqualuxe_event&page=aqualuxe-event-reports&tab=attendees')); ?>" class="nav-tab <?php echo $tab === 'attendees' ? 'nav-tab-active' : ''; ?>"><?php echo esc_html__('Attendees', 'aqualuxe'); ?></a>
            </nav>
            
            <div class="tab-content">
                <?php
                switch ($tab) {
                    case 'overview':
                        $this->reports_overview_tab();
                        break;
                        
                    case 'tickets':
                        $this->reports_tickets_tab();
                        break;
                        
                    case 'attendees':
                        $this->reports_attendees_tab();
                        break;
                }
                ?>
            </div>
        </div>
        <?php
    }

    /**
     * Reports overview tab.
     */
    private function reports_overview_tab() {
        // Get event stats
        $total_events = wp_count_posts('aqualuxe_event')->publish;
        $upcoming_events = count(aqualuxe_get_upcoming_events(-1));
        $past_events = count(aqualuxe_get_past_events(-1));
        
        // Get ticket stats
        $total_tickets = wp_count_posts('aqualuxe_ticket')->publish;
        $tickets_sold = 0;
        $revenue = 0;
        
        $ticket_posts = get_posts(array(
            'post_type'      => 'aqualuxe_ticket',
            'posts_per_page' => -1,
        ));
        
        foreach ($ticket_posts as $ticket_post) {
            $ticket = new AquaLuxe_Event_Ticket($ticket_post);
            $tickets_sold += $ticket->get_sold();
            $revenue += $ticket->get_sold() * $ticket->get_price(false);
        }
        
        // Get category stats
        $categories = aqualuxe_get_event_categories();
        
        ?>
        <div class="aqualuxe-events-reports-overview">
            <h2><?php echo esc_html__('Events Overview', 'aqualuxe'); ?></h2>
            
            <div class="aqualuxe-events-stats-grid">
                <div class="aqualuxe-events-stat-box">
                    <h3><?php echo esc_html__('Total Events', 'aqualuxe'); ?></h3>
                    <div class="aqualuxe-events-stat-value"><?php echo esc_html($total_events); ?></div>
                </div>
                
                <div class="aqualuxe-events-stat-box">
                    <h3><?php echo esc_html__('Upcoming Events', 'aqualuxe'); ?></h3>
                    <div class="aqualuxe-events-stat-value"><?php echo esc_html($upcoming_events); ?></div>
                </div>
                
                <div class="aqualuxe-events-stat-box">
                    <h3><?php echo esc_html__('Past Events', 'aqualuxe'); ?></h3>
                    <div class="aqualuxe-events-stat-value"><?php echo esc_html($past_events); ?></div>
                </div>
                
                <div class="aqualuxe-events-stat-box">
                    <h3><?php echo esc_html__('Total Tickets', 'aqualuxe'); ?></h3>
                    <div class="aqualuxe-events-stat-value"><?php echo esc_html($total_tickets); ?></div>
                </div>
                
                <div class="aqualuxe-events-stat-box">
                    <h3><?php echo esc_html__('Tickets Sold', 'aqualuxe'); ?></h3>
                    <div class="aqualuxe-events-stat-value"><?php echo esc_html($tickets_sold); ?></div>
                </div>
                
                <div class="aqualuxe-events-stat-box">
                    <h3><?php echo esc_html__('Revenue', 'aqualuxe'); ?></h3>
                    <div class="aqualuxe-events-stat-value"><?php echo esc_html(sprintf(
                        get_woocommerce_price_format(),
                        get_woocommerce_currency_symbol(),
                        number_format($revenue, 2)
                    )); ?></div>
                </div>
            </div>
            
            <h2><?php echo esc_html__('Events by Category', 'aqualuxe'); ?></h2>
            
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('Category', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Events', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Upcoming', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Past', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categories as $category) : ?>
                        <?php
                        $category_events = aqualuxe_get_events_by_category($category->id, -1);
                        $category_upcoming = 0;
                        $category_past = 0;
                        
                        foreach ($category_events as $event) {
                            $status = aqualuxe_get_event_status($event);
                            
                            if ($status === 'upcoming' || $status === 'today') {
                                $category_upcoming++;
                            } elseif ($status === 'past') {
                                $category_past++;
                            }
                        }
                        ?>
                        <tr>
                            <td><?php echo esc_html($category->get_name()); ?></td>
                            <td><?php echo esc_html(count($category_events)); ?></td>
                            <td><?php echo esc_html($category_upcoming); ?></td>
                            <td><?php echo esc_html($category_past); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <style>
            .aqualuxe-events-stats-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 20px;
                margin-bottom: 30px;
            }
            
            .aqualuxe-events-stat-box {
                background: #fff;
                border: 1px solid #ccd0d4;
                padding: 20px;
                text-align: center;
                box-shadow: 0 1px 1px rgba(0,0,0,0.04);
            }
            
            .aqualuxe-events-stat-box h3 {
                margin-top: 0;
                color: #23282d;
            }
            
            .aqualuxe-events-stat-value {
                font-size: 24px;
                font-weight: 600;
                color: #0073aa;
            }
        </style>
        <?php
    }

    /**
     * Reports tickets tab.
     */
    private function reports_tickets_tab() {
        // Get events with tickets
        $events = aqualuxe_get_events(array(
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_event_tickets_available',
                    'value'   => '1',
                    'compare' => '=',
                ),
            ),
        ));
        
        ?>
        <div class="aqualuxe-events-reports-tickets">
            <h2><?php echo esc_html__('Ticket Sales', 'aqualuxe'); ?></h2>
            
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('Event', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Date', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Ticket Types', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Tickets Sold', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Capacity', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Revenue', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event) : ?>
                        <?php
                        $tickets = aqualuxe_get_event_tickets($event->id);
                        $tickets_sold = 0;
                        $capacity = 0;
                        $revenue = 0;
                        
                        foreach ($tickets as $ticket) {
                            $tickets_sold += $ticket->get_sold();
                            $capacity += $ticket->get_capacity();
                            $revenue += $ticket->get_sold() * $ticket->get_price(false);
                        }
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($event->id)); ?>">
                                    <?php echo esc_html($event->get_title()); ?>
                                </a>
                            </td>
                            <td><?php echo esc_html(aqualuxe_format_event_date_range($event)); ?></td>
                            <td><?php echo esc_html(count($tickets)); ?></td>
                            <td><?php echo esc_html($tickets_sold); ?></td>
                            <td><?php echo $capacity > 0 ? esc_html($capacity) : esc_html__('Unlimited', 'aqualuxe'); ?></td>
                            <td><?php echo esc_html(sprintf(
                                get_woocommerce_price_format(),
                                get_woocommerce_currency_symbol(),
                                number_format($revenue, 2)
                            )); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Reports attendees tab.
     */
    private function reports_attendees_tab() {
        // Get events with attendees
        $events = aqualuxe_get_events(array(
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_event_tickets_sold',
                    'value'   => '0',
                    'compare' => '>',
                    'type'    => 'NUMERIC',
                ),
            ),
        ));
        
        ?>
        <div class="aqualuxe-events-reports-attendees">
            <h2><?php echo esc_html__('Event Attendees', 'aqualuxe'); ?></h2>
            
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th><?php echo esc_html__('Event', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Date', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Attendees', 'aqualuxe'); ?></th>
                        <th><?php echo esc_html__('Actions', 'aqualuxe'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event) : ?>
                        <?php
                        $attendees = aqualuxe_get_event_attendees($event->id);
                        $attendee_count = 0;
                        
                        foreach ($attendees as $attendee) {
                            $attendee_count += $attendee['quantity'];
                        }
                        ?>
                        <tr>
                            <td>
                                <a href="<?php echo esc_url(get_edit_post_link($event->id)); ?>">
                                    <?php echo esc_html($event->get_title()); ?>
                                </a>
                            </td>
                            <td><?php echo esc_html(aqualuxe_format_event_date_range($event)); ?></td>
                            <td><?php echo esc_html($attendee_count); ?></td>
                            <td>
                                <a href="<?php echo esc_url(admin_url('edit.php?post_type=aqualuxe_event&page=aqualuxe-event-attendees&event_id=' . $event->id)); ?>" class="button">
                                    <?php echo esc_html__('View Attendees', 'aqualuxe'); ?>
                                </a>
                                <a href="<?php echo esc_url(admin_url('edit.php?post_type=aqualuxe_event&page=aqualuxe-event-attendees&event_id=' . $event->id . '&export=csv')); ?>" class="button">
                                    <?php echo esc_html__('Export CSV', 'aqualuxe'); ?>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php
    }

    /**
     * Add meta boxes.
     */
    public function add_meta_boxes() {
        // Event details meta box
        add_meta_box(
            'aqualuxe_event_details',
            __('Event Details', 'aqualuxe'),
            array($this, 'event_details_meta_box'),
            'aqualuxe_event',
            'normal',
            'high'
        );
        
        // Event location meta box
        add_meta_box(
            'aqualuxe_event_location',
            __('Event Location', 'aqualuxe'),
            array($this, 'event_location_meta_box'),
            'aqualuxe_event',
            'normal',
            'high'
        );
        
        // Event tickets meta box
        add_meta_box(
            'aqualuxe_event_tickets',
            __('Event Tickets', 'aqualuxe'),
            array($this, 'event_tickets_meta_box'),
            'aqualuxe_event',
            'normal',
            'high'
        );
        
        // Event organizer meta box
        add_meta_box(
            'aqualuxe_event_organizer',
            __('Event Organizer', 'aqualuxe'),
            array($this, 'event_organizer_meta_box'),
            'aqualuxe_event',
            'side',
            'default'
        );
        
        // Ticket details meta box
        add_meta_box(
            'aqualuxe_ticket_details',
            __('Ticket Details', 'aqualuxe'),
            array($this, 'ticket_details_meta_box'),
            'aqualuxe_ticket',
            'normal',
            'high'
        );
    }

    /**
     * Event details meta box.
     *
     * @param WP_Post $post Post object.
     */
    public function event_details_meta_box($post) {
        wp_nonce_field('aqualuxe_event_details', 'aqualuxe_event_details_nonce');
        
        $event = new AquaLuxe_Event($post);
        
        ?>
        <div class="aqualuxe-event-meta-box">
            <div class="aqualuxe-event-field-group">
                <div class="aqualuxe-event-field">
                    <label for="event_start_date"><?php echo esc_html__('Start Date', 'aqualuxe'); ?></label>
                    <input type="date" id="event_start_date" name="event_start_date" value="<?php echo esc_attr($event->get_data('start_date')); ?>" required />
                </div>
                
                <div class="aqualuxe-event-field">
                    <label for="event_start_time"><?php echo esc_html__('Start Time', 'aqualuxe'); ?></label>
                    <input type="time" id="event_start_time" name="event_start_time" value="<?php echo esc_attr($event->get_data('start_time')); ?>" />
                </div>
            </div>
            
            <div class="aqualuxe-event-field-group">
                <div class="aqualuxe-event-field">
                    <label for="event_end_date"><?php echo esc_html__('End Date', 'aqualuxe'); ?></label>
                    <input type="date" id="event_end_date" name="event_end_date" value="<?php echo esc_attr($event->get_data('end_date')); ?>" required />
                </div>
                
                <div class="aqualuxe-event-field">
                    <label for="event_end_time"><?php echo esc_html__('End Time', 'aqualuxe'); ?></label>
                    <input type="time" id="event_end_time" name="event_end_time" value="<?php echo esc_attr($event->get_data('end_time')); ?>" />
                </div>
            </div>
            
            <div class="aqualuxe-event-field">
                <label for="event_all_day">
                    <input type="checkbox" id="event_all_day" name="event_all_day" value="1" <?php checked($event->get_data('all_day'), true); ?> />
                    <?php echo esc_html__('All Day Event', 'aqualuxe'); ?>
                </label>
            </div>
            
            <div class="aqualuxe-event-field">
                <label for="event_recurring">
                    <input type="checkbox" id="event_recurring" name="event_recurring" value="1" <?php checked($event->get_data('recurring'), true); ?> />
                    <?php echo esc_html__('Recurring Event', 'aqualuxe'); ?>
                </label>
            </div>
            
            <div class="aqualuxe-event-field event-recurrence-options" style="<?php echo $event->get_data('recurring') ? '' : 'display: none;'; ?>">
                <label for="event_recurrence_pattern"><?php echo esc_html__('Recurrence Pattern', 'aqualuxe'); ?></label>
                <select id="event_recurrence_pattern" name="event_recurrence_pattern">
                    <?php foreach (aqualuxe_get_event_recurrence_patterns() as $pattern => $label) : ?>
                        <option value="<?php echo esc_attr($pattern); ?>" <?php selected($event->get_data('recurrence_pattern'), $pattern); ?>><?php echo esc_html($label); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                $('#event_all_day').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('#event_start_time, #event_end_time').prop('disabled', true);
                    } else {
                        $('#event_start_time, #event_end_time').prop('disabled', false);
                    }
                });
                
                $('#event_recurring').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.event-recurrence-options').show();
                    } else {
                        $('.event-recurrence-options').hide();
                    }
                });
                
                // Initialize
                if ($('#event_all_day').is(':checked')) {
                    $('#event_start_time, #event_end_time').prop('disabled', true);
                }
            });
        </script>
        
        <style>
            .aqualuxe-event-meta-box {
                margin-bottom: 20px;
            }
            
            .aqualuxe-event-field-group {
                display: flex;
                gap: 20px;
                margin-bottom: 15px;
            }
            
            .aqualuxe-event-field {
                margin-bottom: 15px;
            }
            
            .aqualuxe-event-field label {
                display: block;
                margin-bottom: 5px;
                font-weight: 600;
            }
            
            .aqualuxe-event-field input[type="text"],
            .aqualuxe-event-field input[type="date"],
            .aqualuxe-event-field input[type="time"],
            .aqualuxe-event-field input[type="number"],
            .aqualuxe-event-field select,
            .aqualuxe-event-field textarea {
                width: 100%;
            }
            
            .aqualuxe-event-field input[type="checkbox"] + label {
                display: inline;
                font-weight: normal;
            }
        </style>
        <?php
    }

    /**
     * Event location meta box.
     *
     * @param WP_Post $post Post object.
     */
    public function event_location_meta_box($post) {
        wp_nonce_field('aqualuxe_event_location', 'aqualuxe_event_location_nonce');
        
        $event = new AquaLuxe_Event($post);
        
        ?>
        <div class="aqualuxe-event-meta-box">
            <div class="aqualuxe-event-field">
                <label for="event_venue"><?php echo esc_html__('Venue Name', 'aqualuxe'); ?></label>
                <input type="text" id="event_venue" name="event_venue" value="<?php echo esc_attr($event->get_data('venue')); ?>" />
            </div>
            
            <div class="aqualuxe-event-field">
                <label for="event_address"><?php echo esc_html__('Address', 'aqualuxe'); ?></label>
                <input type="text" id="event_address" name="event_address" value="<?php echo esc_attr($event->get_data('address')); ?>" />
            </div>
            
            <div class="aqualuxe-event-field-group">
                <div class="aqualuxe-event-field">
                    <label for="event_city"><?php echo esc_html__('City', 'aqualuxe'); ?></label>
                    <input type="text" id="event_city" name="event_city" value="<?php echo esc_attr($event->get_data('city')); ?>" />
                </div>
                
                <div class="aqualuxe-event-field">
                    <label for="event_state"><?php echo esc_html__('State/Province', 'aqualuxe'); ?></label>
                    <input type="text" id="event_state" name="event_state" value="<?php echo esc_attr($event->get_data('state')); ?>" />
                </div>
            </div>
            
            <div class="aqualuxe-event-field-group">
                <div class="aqualuxe-event-field">
                    <label for="event_zip"><?php echo esc_html__('ZIP/Postal Code', 'aqualuxe'); ?></label>
                    <input type="text" id="event_zip" name="event_zip" value="<?php echo esc_attr($event->get_data('zip')); ?>" />
                </div>
                
                <div class="aqualuxe-event-field">
                    <label for="event_country"><?php echo esc_html__('Country', 'aqualuxe'); ?></label>
                    <input type="text" id="event_country" name="event_country" value="<?php echo esc_attr($event->get_data('country')); ?>" />
                </div>
            </div>
            
            <div class="aqualuxe-event-field-group">
                <div class="aqualuxe-event-field">
                    <label for="event_latitude"><?php echo esc_html__('Latitude', 'aqualuxe'); ?></label>
                    <input type="text" id="event_latitude" name="event_latitude" value="<?php echo esc_attr($event->get_data('latitude')); ?>" />
                </div>
                
                <div class="aqualuxe-event-field">
                    <label for="event_longitude"><?php echo esc_html__('Longitude', 'aqualuxe'); ?></label>
                    <input type="text" id="event_longitude" name="event_longitude" value="<?php echo esc_attr($event->get_data('longitude')); ?>" />
                </div>
            </div>
            
            <div class="aqualuxe-event-field">
                <button type="button" class="button" id="event_geocode"><?php echo esc_html__('Get Coordinates from Address', 'aqualuxe'); ?></button>
            </div>
        </div>
        
        <?php
        // Get Google Maps API key from settings
        $options = get_option('aqualuxe_events_settings', array());
        $api_key = isset($options['google_maps_api_key']) ? $options['google_maps_api_key'] : '';
        
        if (!empty($api_key)) {
            ?>
            <script>
                jQuery(document).ready(function($) {
                    $('#event_geocode').on('click', function() {
                        var address = [
                            $('#event_address').val(),
                            $('#event_city').val(),
                            $('#event_state').val(),
                            $('#event_zip').val(),
                            $('#event_country').val()
                        ].filter(Boolean).join(', ');
                        
                        if (!address) {
                            alert('<?php echo esc_js(__('Please enter an address.', 'aqualuxe')); ?>');
                            return;
                        }
                        
                        $.ajax({
                            url: 'https://maps.googleapis.com/maps/api/geocode/json',
                            data: {
                                address: address,
                                key: '<?php echo esc_js($api_key); ?>'
                            },
                            success: function(response) {
                                if (response.status === 'OK' && response.results.length > 0) {
                                    var location = response.results[0].geometry.location;
                                    $('#event_latitude').val(location.lat);
                                    $('#event_longitude').val(location.lng);
                                } else {
                                    alert('<?php echo esc_js(__('Could not geocode address.', 'aqualuxe')); ?>');
                                }
                            },
                            error: function() {
                                alert('<?php echo esc_js(__('Error geocoding address.', 'aqualuxe')); ?>');
                            }
                        });
                    });
                });
            </script>
            <?php
        }
    }

    /**
     * Event tickets meta box.
     *
     * @param WP_Post $post Post object.
     */
    public function event_tickets_meta_box($post) {
        wp_nonce_field('aqualuxe_event_tickets', 'aqualuxe_event_tickets_nonce');
        
        $event = new AquaLuxe_Event($post);
        $tickets = aqualuxe_get_event_tickets($post->ID);
        
        ?>
        <div class="aqualuxe-event-meta-box">
            <div class="aqualuxe-event-field">
                <label for="event_tickets_available">
                    <input type="checkbox" id="event_tickets_available" name="event_tickets_available" value="1" <?php checked($event->get_data('tickets_available'), true); ?> />
                    <?php echo esc_html__('Tickets Available', 'aqualuxe'); ?>
                </label>
            </div>
            
            <div class="aqualuxe-event-field-group event-tickets-options" style="<?php echo $event->get_data('tickets_available') ? '' : 'display: none;'; ?>">
                <div class="aqualuxe-event-field">
                    <label for="event_cost"><?php echo esc_html__('General Admission Cost', 'aqualuxe'); ?></label>
                    <input type="number" id="event_cost" name="event_cost" value="<?php echo esc_attr($event->get_data('cost')); ?>" min="0" step="0.01" />
                </div>
                
                <div class="aqualuxe-event-field">
                    <label for="event_max_tickets"><?php echo esc_html__('Maximum Tickets', 'aqualuxe'); ?></label>
                    <input type="number" id="event_max_tickets" name="event_max_tickets" value="<?php echo esc_attr($event->get_data('max_tickets')); ?>" min="0" step="1" />
                    <p class="description"><?php echo esc_html__('Enter 0 for unlimited tickets.', 'aqualuxe'); ?></p>
                </div>
            </div>
            
            <div class="event-tickets-list" style="<?php echo $event->get_data('tickets_available') ? '' : 'display: none;'; ?>">
                <h3><?php echo esc_html__('Ticket Types', 'aqualuxe'); ?></h3>
                
                <?php if (!empty($tickets)) : ?>
                    <table class="widefat fixed striped">
                        <thead>
                            <tr>
                                <th><?php echo esc_html__('Ticket', 'aqualuxe'); ?></th>
                                <th><?php echo esc_html__('Price', 'aqualuxe'); ?></th>
                                <th><?php echo esc_html__('Capacity', 'aqualuxe'); ?></th>
                                <th><?php echo esc_html__('Sold', 'aqualuxe'); ?></th>
                                <th><?php echo esc_html__('Actions', 'aqualuxe'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tickets as $ticket) : ?>
                                <tr>
                                    <td>
                                        <a href="<?php echo esc_url(get_edit_post_link($ticket->id)); ?>">
                                            <?php echo esc_html($ticket->get_title()); ?>
                                        </a>
                                    </td>
                                    <td><?php echo esc_html($ticket->get_price()); ?></td>
                                    <td><?php echo $ticket->get_capacity() > 0 ? esc_html($ticket->get_capacity()) : esc_html__('Unlimited', 'aqualuxe'); ?></td>
                                    <td><?php echo esc_html($ticket->get_sold()); ?></td>
                                    <td>
                                        <a href="<?php echo esc_url(get_edit_post_link($ticket->id)); ?>" class="button">
                                            <?php echo esc_html__('Edit', 'aqualuxe'); ?>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else : ?>
                    <p><?php echo esc_html__('No ticket types defined.', 'aqualuxe'); ?></p>
                <?php endif; ?>
                
                <p>
                    <a href="<?php echo esc_url(admin_url('post-new.php?post_type=aqualuxe_ticket&event_id=' . $post->ID)); ?>" class="button">
                        <?php echo esc_html__('Add Ticket Type', 'aqualuxe'); ?>
                    </a>
                </p>
            </div>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                $('#event_tickets_available').on('change', function() {
                    if ($(this).is(':checked')) {
                        $('.event-tickets-options, .event-tickets-list').show();
                    } else {
                        $('.event-tickets-options, .event-tickets-list').hide();
                    }
                });
            });
        </script>
        <?php
    }

    /**
     * Event organizer meta box.
     *
     * @param WP_Post $post Post object.
     */
    public function event_organizer_meta_box($post) {
        wp_nonce_field('aqualuxe_event_organizer', 'aqualuxe_event_organizer_nonce');
        
        $event = new AquaLuxe_Event($post);
        
        ?>
        <div class="aqualuxe-event-meta-box">
            <div class="aqualuxe-event-field">
                <label for="event_organizer"><?php echo esc_html__('Organizer Name', 'aqualuxe'); ?></label>
                <input type="text" id="event_organizer" name="event_organizer" value="<?php echo esc_attr($event->get_data('organizer')); ?>" />
            </div>
            
            <div class="aqualuxe-event-field">
                <label for="event_organizer_phone"><?php echo esc_html__('Phone', 'aqualuxe'); ?></label>
                <input type="text" id="event_organizer_phone" name="event_organizer_phone" value="<?php echo esc_attr($event->get_data('organizer_phone')); ?>" />
            </div>
            
            <div class="aqualuxe-event-field">
                <label for="event_organizer_email"><?php echo esc_html__('Email', 'aqualuxe'); ?></label>
                <input type="email" id="event_organizer_email" name="event_organizer_email" value="<?php echo esc_attr($event->get_data('organizer_email')); ?>" />
            </div>
            
            <div class="aqualuxe-event-field">
                <label for="event_organizer_website"><?php echo esc_html__('Website', 'aqualuxe'); ?></label>
                <input type="url" id="event_organizer_website" name="event_organizer_website" value="<?php echo esc_attr($event->get_data('organizer_website')); ?>" />
            </div>
        </div>
        <?php
    }

    /**
     * Ticket details meta box.
     *
     * @param WP_Post $post Post object.
     */
    public function ticket_details_meta_box($post) {
        wp_nonce_field('aqualuxe_ticket_details', 'aqualuxe_ticket_details_nonce');
        
        $ticket = new AquaLuxe_Event_Ticket($post);
        $event_id = $ticket->get_data('event_id');
        
        if (!$event_id && isset($_GET['event_id'])) {
            $event_id = absint($_GET['event_id']);
        }
        
        $events = aqualuxe_get_events(array(
            'posts_per_page' => -1,
            'meta_query'     => array(
                array(
                    'key'     => '_event_tickets_available',
                    'value'   => '1',
                    'compare' => '=',
                ),
            ),
        ));
        
        ?>
        <div class="aqualuxe-event-meta-box">
            <div class="aqualuxe-event-field">
                <label for="ticket_event_id"><?php echo esc_html__('Event', 'aqualuxe'); ?></label>
                <select id="ticket_event_id" name="ticket_event_id" required>
                    <option value=""><?php echo esc_html__('Select an event', 'aqualuxe'); ?></option>
                    <?php foreach ($events as $event) : ?>
                        <option value="<?php echo esc_attr($event->id); ?>" <?php selected($event_id, $event->id); ?>>
                            <?php echo esc_html($event->get_title()); ?> (<?php echo esc_html($event->get_start_date()); ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="aqualuxe-event-field-group">
                <div class="aqualuxe-event-field">
                    <label for="ticket_price"><?php echo esc_html__('Price', 'aqualuxe'); ?></label>
                    <input type="number" id="ticket_price" name="ticket_price" value="<?php echo esc_attr($ticket->get_data('price')); ?>" min="0" step="0.01" required />
                </div>
                
                <div class="aqualuxe-event-field">
                    <label for="ticket_sale_price"><?php echo esc_html__('Sale Price', 'aqualuxe'); ?></label>
                    <input type="number" id="ticket_sale_price" name="ticket_sale_price" value="<?php echo esc_attr($ticket->get_data('sale_price')); ?>" min="0" step="0.01" />
                </div>
            </div>
            
            <div class="aqualuxe-event-field-group">
                <div class="aqualuxe-event-field">
                    <label for="ticket_capacity"><?php echo esc_html__('Capacity', 'aqualuxe'); ?></label>
                    <input type="number" id="ticket_capacity" name="ticket_capacity" value="<?php echo esc_attr($ticket->get_data('capacity')); ?>" min="0" step="1" />
                    <p class="description"><?php echo esc_html__('Enter 0 for unlimited capacity.', 'aqualuxe'); ?></p>
                </div>
                
                <div class="aqualuxe-event-field">
                    <label for="ticket_sold"><?php echo esc_html__('Sold', 'aqualuxe'); ?></label>
                    <input type="number" id="ticket_sold" name="ticket_sold" value="<?php echo esc_attr($ticket->get_data('sold')); ?>" min="0" step="1" readonly />
                </div>
            </div>
            
            <div class="aqualuxe-event-field-group">
                <div class="aqualuxe-event-field">
                    <label for="ticket_min_purchase"><?php echo esc_html__('Minimum Purchase', 'aqualuxe'); ?></label>
                    <input type="number" id="ticket_min_purchase" name="ticket_min_purchase" value="<?php echo esc_attr($ticket->get_data('min_purchase')); ?>" min="1" step="1" />
                </div>
                
                <div class="aqualuxe-event-field">
                    <label for="ticket_max_purchase"><?php echo esc_html__('Maximum Purchase', 'aqualuxe'); ?></label>
                    <input type="number" id="ticket_max_purchase" name="ticket_max_purchase" value="<?php echo esc_attr($ticket->get_data('max_purchase')); ?>" min="0" step="1" />
                    <p class="description"><?php echo esc_html__('Enter 0 for unlimited.', 'aqualuxe'); ?></p>
                </div>
            </div>
            
            <div class="aqualuxe-event-field-group">
                <div class="aqualuxe-event-field">
                    <label for="ticket_start_sale_date"><?php echo esc_html__('Start Sale Date', 'aqualuxe'); ?></label>
                    <input type="date" id="ticket_start_sale_date" name="ticket_start_sale_date" value="<?php echo esc_attr($ticket->get_data('start_sale_date')); ?>" />
                </div>
                
                <div class="aqualuxe-event-field">
                    <label for="ticket_end_sale_date"><?php echo esc_html__('End Sale Date', 'aqualuxe'); ?></label>
                    <input type="date" id="ticket_end_sale_date" name="ticket_end_sale_date" value="<?php echo esc_attr($ticket->get_data('end_sale_date')); ?>" />
                </div>
            </div>
            
            <div class="aqualuxe-event-field">
                <label for="ticket_features"><?php echo esc_html__('Features', 'aqualuxe'); ?></label>
                <textarea id="ticket_features" name="ticket_features" rows="5"><?php echo esc_textarea(implode("\n", $ticket->get_data('features'))); ?></textarea>
                <p class="description"><?php echo esc_html__('Enter one feature per line.', 'aqualuxe'); ?></p>
            </div>
        </div>
        <?php
    }

    /**
     * Save meta boxes.
     *
     * @param int     $post_id Post ID.
     * @param WP_Post $post    Post object.
     */
    public function save_meta_boxes($post_id, $post) {
        // Check if our nonce is set.
        if (
            !isset($_POST['aqualuxe_event_details_nonce']) &&
            !isset($_POST['aqualuxe_event_location_nonce']) &&
            !isset($_POST['aqualuxe_event_tickets_nonce']) &&
            !isset($_POST['aqualuxe_event_organizer_nonce']) &&
            !isset($_POST['aqualuxe_ticket_details_nonce'])
        ) {
            return;
        }
        
        // Verify that the nonce is valid.
        if (
            (isset($_POST['aqualuxe_event_details_nonce']) && !wp_verify_nonce($_POST['aqualuxe_event_details_nonce'], 'aqualuxe_event_details')) &&
            (isset($_POST['aqualuxe_event_location_nonce']) && !wp_verify_nonce($_POST['aqualuxe_event_location_nonce'], 'aqualuxe_event_location')) &&
            (isset($_POST['aqualuxe_event_tickets_nonce']) && !wp_verify_nonce($_POST['aqualuxe_event_tickets_nonce'], 'aqualuxe_event_tickets')) &&
            (isset($_POST['aqualuxe_event_organizer_nonce']) && !wp_verify_nonce($_POST['aqualuxe_event_organizer_nonce'], 'aqualuxe_event_organizer')) &&
            (isset($_POST['aqualuxe_ticket_details_nonce']) && !wp_verify_nonce($_POST['aqualuxe_ticket_details_nonce'], 'aqualuxe_ticket_details'))
        ) {
            return;
        }
        
        // Check if this is an autosave.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }
        
        // Check the user's permissions.
        if ('aqualuxe_event' === $post->post_type) {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
            
            $this->save_event_meta($post_id);
        } elseif ('aqualuxe_ticket' === $post->post_type) {
            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
            
            $this->save_ticket_meta($post_id);
        }
    }

    /**
     * Save event meta.
     *
     * @param int $post_id Post ID.
     */
    private function save_event_meta($post_id) {
        $event = new AquaLuxe_Event($post_id);
        
        // Event details
        if (isset($_POST['event_start_date'])) {
            $event->set_data('start_date', sanitize_text_field($_POST['event_start_date']));
        }
        
        if (isset($_POST['event_end_date'])) {
            $event->set_data('end_date', sanitize_text_field($_POST['event_end_date']));
        }
        
        if (isset($_POST['event_start_time'])) {
            $event->set_data('start_time', sanitize_text_field($_POST['event_start_time']));
        }
        
        if (isset($_POST['event_end_time'])) {
            $event->set_data('end_time', sanitize_text_field($_POST['event_end_time']));
        }
        
        $event->set_data('all_day', isset($_POST['event_all_day']));
        $event->set_data('recurring', isset($_POST['event_recurring']));
        
        if (isset($_POST['event_recurrence_pattern'])) {
            $event->set_data('recurrence_pattern', sanitize_text_field($_POST['event_recurrence_pattern']));
        }
        
        // Event location
        if (isset($_POST['event_venue'])) {
            $event->set_data('venue', sanitize_text_field($_POST['event_venue']));
        }
        
        if (isset($_POST['event_address'])) {
            $event->set_data('address', sanitize_text_field($_POST['event_address']));
        }
        
        if (isset($_POST['event_city'])) {
            $event->set_data('city', sanitize_text_field($_POST['event_city']));
        }
        
        if (isset($_POST['event_state'])) {
            $event->set_data('state', sanitize_text_field($_POST['event_state']));
        }
        
        if (isset($_POST['event_zip'])) {
            $event->set_data('zip', sanitize_text_field($_POST['event_zip']));
        }
        
        if (isset($_POST['event_country'])) {
            $event->set_data('country', sanitize_text_field($_POST['event_country']));
        }
        
        if (isset($_POST['event_latitude'])) {
            $event->set_data('latitude', sanitize_text_field($_POST['event_latitude']));
        }
        
        if (isset($_POST['event_longitude'])) {
            $event->set_data('longitude', sanitize_text_field($_POST['event_longitude']));
        }
        
        // Event tickets
        $event->set_data('tickets_available', isset($_POST['event_tickets_available']));
        
        if (isset($_POST['event_cost'])) {
            $event->set_data('cost', floatval($_POST['event_cost']));
        }
        
        if (isset($_POST['event_max_tickets'])) {
            $event->set_data('max_tickets', intval($_POST['event_max_tickets']));
        }
        
        // Event organizer
        if (isset($_POST['event_organizer'])) {
            $event->set_data('organizer', sanitize_text_field($_POST['event_organizer']));
        }
        
        if (isset($_POST['event_organizer_phone'])) {
            $event->set_data('organizer_phone', sanitize_text_field($_POST['event_organizer_phone']));
        }
        
        if (isset($_POST['event_organizer_email'])) {
            $event->set_data('organizer_email', sanitize_email($_POST['event_organizer_email']));
        }
        
        if (isset($_POST['event_organizer_website'])) {
            $event->set_data('organizer_website', esc_url_raw($_POST['event_organizer_website']));
        }
        
        $event->save();
    }

    /**
     * Save ticket meta.
     *
     * @param int $post_id Post ID.
     */
    private function save_ticket_meta($post_id) {
        $ticket = new AquaLuxe_Event_Ticket($post_id);
        
        if (isset($_POST['ticket_event_id'])) {
            $ticket->set_data('event_id', absint($_POST['ticket_event_id']));
        }
        
        if (isset($_POST['ticket_price'])) {
            $ticket->set_data('price', floatval($_POST['ticket_price']));
        }
        
        if (isset($_POST['ticket_sale_price'])) {
            $ticket->set_data('sale_price', floatval($_POST['ticket_sale_price']));
        }
        
        if (isset($_POST['ticket_capacity'])) {
            $ticket->set_data('capacity', intval($_POST['ticket_capacity']));
        }
        
        if (isset($_POST['ticket_min_purchase'])) {
            $ticket->set_data('min_purchase', max(1, intval($_POST['ticket_min_purchase'])));
        }
        
        if (isset($_POST['ticket_max_purchase'])) {
            $ticket->set_data('max_purchase', intval($_POST['ticket_max_purchase']));
        }
        
        if (isset($_POST['ticket_start_sale_date'])) {
            $ticket->set_data('start_sale_date', sanitize_text_field($_POST['ticket_start_sale_date']));
        }
        
        if (isset($_POST['ticket_end_sale_date'])) {
            $ticket->set_data('end_sale_date', sanitize_text_field($_POST['ticket_end_sale_date']));
        }
        
        if (isset($_POST['ticket_features'])) {
            $features = explode("\n", sanitize_textarea_field($_POST['ticket_features']));
            $features = array_map('trim', $features);
            $features = array_filter($features);
            
            $ticket->set_data('features', $features);
        }
        
        $ticket->save();
    }

    /**
     * Enqueue admin scripts and styles.
     *
     * @param string $hook Current admin page.
     */
    public function admin_scripts($hook) {
        $screen = get_current_screen();
        
        if (!$screen) {
            return;
        }
        
        if (in_array($screen->id, array('aqualuxe_event', 'edit-aqualuxe_event', 'aqualuxe_ticket', 'edit-aqualuxe_ticket'))) {
            wp_enqueue_style(
                'aqualuxe-events-admin',
                AQUALUXE_EVENTS_PLUGIN_URL . '/assets/css/admin.css',
                array(),
                AQUALUXE_EVENTS_VERSION
            );
            
            wp_enqueue_script(
                'aqualuxe-events-admin',
                AQUALUXE_EVENTS_PLUGIN_URL . '/assets/js/admin.js',
                array('jquery'),
                AQUALUXE_EVENTS_VERSION,
                true
            );
        }
    }

    /**
     * Add custom columns to event list table.
     *
     * @param array $columns Columns.
     * @return array
     */
    public function event_columns($columns) {
        $date_column = isset($columns['date']) ? $columns['date'] : '';
        
        unset($columns['date']);
        
        $columns['event_date'] = __('Event Date', 'aqualuxe');
        $columns['event_location'] = __('Location', 'aqualuxe');
        $columns['event_tickets'] = __('Tickets', 'aqualuxe');
        
        if ($date_column) {
            $columns['date'] = $date_column;
        }
        
        return $columns;
    }

    /**
     * Add content to custom columns in event list table.
     *
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function event_column_content($column, $post_id) {
        $event = new AquaLuxe_Event($post_id);
        
        switch ($column) {
            case 'event_date':
                echo esc_html(aqualuxe_format_event_date_range($event));
                break;
                
            case 'event_location':
                if ($event->get_venue()) {
                    echo esc_html($event->get_venue());
                    
                    if ($event->get_data('city') || $event->get_data('state')) {
                        echo '<br>';
                        
                        if ($event->get_data('city')) {
                            echo esc_html($event->get_data('city'));
                            
                            if ($event->get_data('state')) {
                                echo ', ';
                            }
                        }
                        
                        if ($event->get_data('state')) {
                            echo esc_html($event->get_data('state'));
                        }
                    }
                } else {
                    echo '&mdash;';
                }
                break;
                
            case 'event_tickets':
                if ($event->get_data('tickets_available')) {
                    $tickets = aqualuxe_get_event_tickets($post_id);
                    $tickets_sold = $event->get_data('tickets_sold');
                    $max_tickets = $event->get_data('max_tickets');
                    
                    if ($max_tickets > 0) {
                        echo esc_html(sprintf(
                            __('%1$d / %2$d sold', 'aqualuxe'),
                            $tickets_sold,
                            $max_tickets
                        ));
                    } else {
                        echo esc_html(sprintf(
                            __('%d sold', 'aqualuxe'),
                            $tickets_sold
                        ));
                    }
                    
                    if (!empty($tickets)) {
                        echo '<br>';
                        echo esc_html(sprintf(
                            _n('%d ticket type', '%d ticket types', count($tickets), 'aqualuxe'),
                            count($tickets)
                        ));
                    }
                } else {
                    echo esc_html__('No tickets', 'aqualuxe');
                }
                break;
        }
    }

    /**
     * Make custom columns sortable in event list table.
     *
     * @param array $columns Sortable columns.
     * @return array
     */
    public function event_sortable_columns($columns) {
        $columns['event_date'] = 'event_date';
        
        return $columns;
    }

    /**
     * Add custom columns to ticket list table.
     *
     * @param array $columns Columns.
     * @return array
     */
    public function ticket_columns($columns) {
        $date_column = isset($columns['date']) ? $columns['date'] : '';
        
        unset($columns['date']);
        
        $columns['ticket_event'] = __('Event', 'aqualuxe');
        $columns['ticket_price'] = __('Price', 'aqualuxe');
        $columns['ticket_sales'] = __('Sales', 'aqualuxe');
        
        if ($date_column) {
            $columns['date'] = $date_column;
        }
        
        return $columns;
    }

    /**
     * Add content to custom columns in ticket list table.
     *
     * @param string $column  Column name.
     * @param int    $post_id Post ID.
     */
    public function ticket_column_content($column, $post_id) {
        $ticket = new AquaLuxe_Event_Ticket($post_id);
        
        switch ($column) {
            case 'ticket_event':
                $event_id = $ticket->get_data('event_id');
                
                if ($event_id) {
                    $event = new AquaLuxe_Event($event_id);
                    
                    echo '<a href="' . esc_url(get_edit_post_link($event_id)) . '">' . esc_html($event->get_title()) . '</a>';
                } else {
                    echo '&mdash;';
                }
                break;
                
            case 'ticket_price':
                echo esc_html($ticket->get_price());
                
                if ($ticket->is_on_sale()) {
                    echo '<br><del>' . esc_html($ticket->get_regular_price()) . '</del>';
                }
                break;
                
            case 'ticket_sales':
                $sold = $ticket->get_sold();
                $capacity = $ticket->get_capacity();
                
                if ($capacity > 0) {
                    echo esc_html(sprintf(
                        __('%1$d / %2$d sold', 'aqualuxe'),
                        $sold,
                        $capacity
                    ));
                    
                    $percentage = $capacity > 0 ? round(($sold / $capacity) * 100) : 0;
                    
                    echo '<div class="ticket-sales-bar">';
                    echo '<div class="ticket-sales-progress" style="width: ' . esc_attr($percentage) . '%;"></div>';
                    echo '</div>';
                } else {
                    echo esc_html(sprintf(
                        __('%d sold', 'aqualuxe'),
                        $sold
                    ));
                }
                break;
        }
    }

    /**
     * Make custom columns sortable in ticket list table.
     *
     * @param array $columns Sortable columns.
     * @return array
     */
    public function ticket_sortable_columns($columns) {
        $columns['ticket_event'] = 'ticket_event';
        $columns['ticket_price'] = 'ticket_price';
        $columns['ticket_sales'] = 'ticket_sales';
        
        return $columns;
    }

    /**
     * Add category fields.
     */
    public function add_category_fields() {
        ?>
        <div class="form-field">
            <label for="category_color"><?php echo esc_html__('Color', 'aqualuxe'); ?></label>
            <input type="color" name="category_color" id="category_color" value="#3498db" />
            <p><?php echo esc_html__('Choose a color for this category.', 'aqualuxe'); ?></p>
        </div>
        
        <div class="form-field">
            <label for="category_image"><?php echo esc_html__('Image', 'aqualuxe'); ?></label>
            <div class="category-image-container">
                <img src="" style="max-width: 100%; display: none;" />
                <input type="hidden" name="category_image_id" id="category_image_id" value="" />
                <button type="button" class="button upload_image_button"><?php echo esc_html__('Upload/Add image', 'aqualuxe'); ?></button>
                <button type="button" class="button remove_image_button" style="display: none;"><?php echo esc_html__('Remove image', 'aqualuxe'); ?></button>
            </div>
            <p><?php echo esc_html__('Upload an image for this category.', 'aqualuxe'); ?></p>
        </div>
        
        <script>
            jQuery(document).ready(function($) {
                // Image upload
                $('.upload_image_button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var container = button.closest('.category-image-container');
                    var imageIdInput = container.find('input[name="category_image_id"]');
                    var imagePreview = container.find('img');
                    var removeButton = container.find('.remove_image_button');
                    
                    var frame = wp.media({
                        title: '<?php echo esc_js(__('Select or Upload Image', 'aqualuxe')); ?>',
                        button: {
                            text: '<?php echo esc_js(__('Use this image', 'aqualuxe')); ?>'
                        },
                        multiple: false
                    });
                    
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        
                        imageIdInput.val(attachment.id);
                        imagePreview.attr('src', attachment.url).show();
                        removeButton.show();
                    });
                    
                    frame.open();
                });
                
                $('.remove_image_button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var container = button.closest('.category-image-container');
                    var imageIdInput = container.find('input[name="category_image_id"]');
                    var imagePreview = container.find('img');
                    
                    imageIdInput.val('');
                    imagePreview.attr('src', '').hide();
                    button.hide();
                });
            });
        </script>
        <?php
    }

    /**
     * Edit category fields.
     *
     * @param WP_Term $term     Term object.
     * @param string  $taxonomy Taxonomy slug.
     */
    public function edit_category_fields($term, $taxonomy) {
        $category = new AquaLuxe_Event_Category($term);
        $color = $category->get_color();
        $image_id = $category->get_data('image_id');
        $image_url = $image_id ? wp_get_attachment_image_url($image_id, 'thumbnail') : '';
        
        ?>
        <tr class="form-field">
            <th scope="row">
                <label for="category_color"><?php echo esc_html__('Color', 'aqualuxe'); ?></label>
            </th>
            <td>
                <input type="color" name="category_color" id="category_color" value="<?php echo esc_attr($color); ?>" />
                <p class="description"><?php echo esc_html__('Choose a color for this category.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row">
                <label for="category_image"><?php echo esc_html__('Image', 'aqualuxe'); ?></label>
            </th>
            <td>
                <div class="category-image-container">
                    <img src="<?php echo esc_url($image_url); ?>" style="max-width: 100%; <?php echo $image_url ? '' : 'display: none;'; ?>" />
                    <input type="hidden" name="category_image_id" id="category_image_id" value="<?php echo esc_attr($image_id); ?>" />
                    <button type="button" class="button upload_image_button"><?php echo esc_html__('Upload/Add image', 'aqualuxe'); ?></button>
                    <button type="button" class="button remove_image_button" <?php echo $image_url ? '' : 'style="display: none;"'; ?>><?php echo esc_html__('Remove image', 'aqualuxe'); ?></button>
                </div>
                <p class="description"><?php echo esc_html__('Upload an image for this category.', 'aqualuxe'); ?></p>
            </td>
        </tr>
        
        <script>
            jQuery(document).ready(function($) {
                // Image upload
                $('.upload_image_button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var container = button.closest('.category-image-container');
                    var imageIdInput = container.find('input[name="category_image_id"]');
                    var imagePreview = container.find('img');
                    var removeButton = container.find('.remove_image_button');
                    
                    var frame = wp.media({
                        title: '<?php echo esc_js(__('Select or Upload Image', 'aqualuxe')); ?>',
                        button: {
                            text: '<?php echo esc_js(__('Use this image', 'aqualuxe')); ?>'
                        },
                        multiple: false
                    });
                    
                    frame.on('select', function() {
                        var attachment = frame.state().get('selection').first().toJSON();
                        
                        imageIdInput.val(attachment.id);
                        imagePreview.attr('src', attachment.url).show();
                        removeButton.show();
                    });
                    
                    frame.open();
                });
                
                $('.remove_image_button').on('click', function(e) {
                    e.preventDefault();
                    
                    var button = $(this);
                    var container = button.closest('.category-image-container');
                    var imageIdInput = container.find('input[name="category_image_id"]');
                    var imagePreview = container.find('img');
                    
                    imageIdInput.val('');
                    imagePreview.attr('src', '').hide();
                    button.hide();
                });
            });
        </script>
        <?php
    }

    /**
     * Save category fields.
     *
     * @param int    $term_id  Term ID.
     * @param int    $tt_id    Term taxonomy ID.
     */
    public function save_category_fields($term_id, $tt_id) {
        if (isset($_POST['category_color'])) {
            update_term_meta($term_id, 'category_color', sanitize_hex_color($_POST['category_color']));
        }
        
        if (isset($_POST['category_image_id'])) {
            update_term_meta($term_id, 'category_image_id', absint($_POST['category_image_id']));
        }
    }

    /**
     * Add admin filters.
     */
    public function admin_filters() {
        global $typenow;
        
        if ('aqualuxe_event' === $typenow) {
            // Category filter
            $categories = aqualuxe_get_event_categories();
            
            if (!empty($categories)) {
                $selected = isset($_GET['event_category']) ? sanitize_text_field($_GET['event_category']) : '';
                
                echo '<select name="event_category" id="event_category">';
                echo '<option value="">' . esc_html__('All Categories', 'aqualuxe') . '</option>';
                
                foreach ($categories as $category) {
                    echo '<option value="' . esc_attr($category->get_slug()) . '" ' . selected($selected, $category->get_slug(), false) . '>' . esc_html($category->get_name()) . '</option>';
                }
                
                echo '</select>';
            }
            
            // Date range filter
            $start_date = isset($_GET['event_start_date']) ? sanitize_text_field($_GET['event_start_date']) : '';
            $end_date = isset($_GET['event_end_date']) ? sanitize_text_field($_GET['event_end_date']) : '';
            
            echo '<input type="date" name="event_start_date" placeholder="' . esc_attr__('Start Date', 'aqualuxe') . '" value="' . esc_attr($start_date) . '" />';
            echo '<input type="date" name="event_end_date" placeholder="' . esc_attr__('End Date', 'aqualuxe') . '" value="' . esc_attr($end_date) . '" />';
        } elseif ('aqualuxe_ticket' === $typenow) {
            // Event filter
            $events = aqualuxe_get_events(array(
                'posts_per_page' => -1,
                'meta_query'     => array(
                    array(
                        'key'     => '_event_tickets_available',
                        'value'   => '1',
                        'compare' => '=',
                    ),
                ),
            ));
            
            if (!empty($events)) {
                $selected = isset($_GET['ticket_event_id']) ? absint($_GET['ticket_event_id']) : 0;
                
                echo '<select name="ticket_event_id" id="ticket_event_id">';
                echo '<option value="">' . esc_html__('All Events', 'aqualuxe') . '</option>';
                
                foreach ($events as $event) {
                    echo '<option value="' . esc_attr($event->id) . '" ' . selected($selected, $event->id, false) . '>' . esc_html($event->get_title()) . '</option>';
                }
                
                echo '</select>';
            }
        }
    }

    /**
     * Filter admin query.
     *
     * @param WP_Query $query Query object.
     * @return WP_Query
     */
    public function admin_filter_query($query) {
        global $typenow, $pagenow;
        
        if ('edit.php' !== $pagenow || !is_admin()) {
            return $query;
        }
        
        if ('aqualuxe_event' === $typenow) {
            // Category filter
            if (isset($_GET['event_category']) && !empty($_GET['event_category'])) {
                $query->query_vars['tax_query'][] = array(
                    'taxonomy' => 'aqualuxe_event_category',
                    'field'    => 'slug',
                    'terms'    => sanitize_text_field($_GET['event_category']),
                );
            }
            
            // Date range filter
            $meta_query = array();
            
            if (isset($_GET['event_start_date']) && !empty($_GET['event_start_date'])) {
                $meta_query[] = array(
                    'key'     => '_event_start_date',
                    'value'   => sanitize_text_field($_GET['event_start_date']),
                    'compare' => '>=',
                    'type'    => 'DATE',
                );
            }
            
            if (isset($_GET['event_end_date']) && !empty($_GET['event_end_date'])) {
                $meta_query[] = array(
                    'key'     => '_event_end_date',
                    'value'   => sanitize_text_field($_GET['event_end_date']),
                    'compare' => '<=',
                    'type'    => 'DATE',
                );
            }
            
            if (!empty($meta_query)) {
                $query->query_vars['meta_query'] = $meta_query;
            }
            
            // Sorting
            if (isset($query->query_vars['orderby'])) {
                switch ($query->query_vars['orderby']) {
                    case 'event_date':
                        $query->query_vars['meta_key'] = '_event_start_date';
                        $query->query_vars['orderby'] = 'meta_value';
                        break;
                }
            }
        } elseif ('aqualuxe_ticket' === $typenow) {
            // Event filter
            if (isset($_GET['ticket_event_id']) && !empty($_GET['ticket_event_id'])) {
                $query->query_vars['meta_query'][] = array(
                    'key'   => '_ticket_event_id',
                    'value' => absint($_GET['ticket_event_id']),
                );
            }
            
            // Sorting
            if (isset($query->query_vars['orderby'])) {
                switch ($query->query_vars['orderby']) {
                    case 'ticket_event':
                        $query->query_vars['meta_key'] = '_ticket_event_id';
                        $query->query_vars['orderby'] = 'meta_value_num';
                        break;
                        
                    case 'ticket_price':
                        $query->query_vars['meta_key'] = '_ticket_price';
                        $query->query_vars['orderby'] = 'meta_value_num';
                        break;
                        
                    case 'ticket_sales':
                        $query->query_vars['meta_key'] = '_ticket_sold';
                        $query->query_vars['orderby'] = 'meta_value_num';
                        break;
                }
            }
        }
        
        return $query;
    }
}

// Initialize the admin class
new AquaLuxe_Events_Admin();