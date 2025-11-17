<?php
/**
 * Event Widget Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Events
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Events;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Event Widget Class
 * 
 * This class handles the event widget functionality.
 */
class Event_Widget extends \WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_event_widget',
            __( 'AquaLuxe Events', 'aqualuxe' ),
            [
                'description' => __( 'Display upcoming events.', 'aqualuxe' ),
                'classname'   => 'aqualuxe-event-widget',
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args
     * @param array $instance
     * @return void
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Upcoming Events', 'aqualuxe' );
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
        $show_venue = isset( $instance['show_venue'] ) ? (bool) $instance['show_venue'] : true;
        $show_image = isset( $instance['show_image'] ) ? (bool) $instance['show_image'] : false;
        $category = ! empty( $instance['category'] ) ? $instance['category'] : '';
        $venue_id = ! empty( $instance['venue_id'] ) ? absint( $instance['venue_id'] ) : 0;
        $show_calendar = isset( $instance['show_calendar'] ) ? (bool) $instance['show_calendar'] : false;
        $view_all_text = ! empty( $instance['view_all_text'] ) ? $instance['view_all_text'] : __( 'View All Events', 'aqualuxe' );
        $view_all_url = ! empty( $instance['view_all_url'] ) ? $instance['view_all_url'] : get_post_type_archive_link( 'aqualuxe_event' );

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Get upcoming events
        $events = Event::get_events( [
            'start_date' => date( 'Y-m-d H:i:s' ),
            'category'   => $category,
            'venue_id'   => $venue_id,
            'limit'      => $number,
        ] );

        if ( $show_calendar ) {
            $calendar = Event_Calendar::get_instance();
            echo $calendar->render_mini_calendar( [
                'category' => $category,
                'venue_id' => $venue_id,
            ] );
        }

        if ( ! empty( $events ) ) {
            echo '<ul class="aqualuxe-event-widget-list">';

            foreach ( $events as $event ) {
                $event_data = $event->get_data();

                echo '<li class="aqualuxe-event-widget-item">';

                if ( $show_image && $event_data['featured_image_url'] ) {
                    echo '<div class="aqualuxe-event-widget-image">';
                    echo '<a href="' . esc_url( $event_data['permalink'] ) . '">';
                    echo '<img src="' . esc_url( $event->get_featured_image_url( 'thumbnail' ) ) . '" alt="' . esc_attr( $event_data['title'] ) . '">';
                    echo '</a>';
                    echo '</div>';
                }

                echo '<div class="aqualuxe-event-widget-content">';
                echo '<h4 class="aqualuxe-event-widget-title">';
                echo '<a href="' . esc_url( $event_data['permalink'] ) . '">' . esc_html( $event_data['title'] ) . '</a>';
                echo '</h4>';

                if ( $show_date ) {
                    echo '<div class="aqualuxe-event-widget-date">';
                    echo '<i class="fas fa-calendar-alt"></i> ';
                    echo esc_html( $event->get_start_date( get_option( 'date_format' ) ) );
                    echo ' ' . esc_html( $event->get_start_date( get_option( 'time_format' ) ) );
                    echo '</div>';
                }

                if ( $show_venue && $event_data['venue_name'] ) {
                    echo '<div class="aqualuxe-event-widget-venue">';
                    echo '<i class="fas fa-map-marker-alt"></i> ';
                    echo esc_html( $event_data['venue_name'] );
                    echo '</div>';
                }

                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';

            if ( $view_all_url && $view_all_text ) {
                echo '<div class="aqualuxe-event-widget-view-all">';
                echo '<a href="' . esc_url( $view_all_url ) . '">' . esc_html( $view_all_text ) . '</a>';
                echo '</div>';
            }
        } else {
            echo '<p class="aqualuxe-event-widget-no-events">' . esc_html__( 'No upcoming events found.', 'aqualuxe' ) . '</p>';
        }

        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance
     * @return void
     */
    public function form( $instance ) {
        $title = isset( $instance['title'] ) ? $instance['title'] : __( 'Upcoming Events', 'aqualuxe' );
        $number = isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : true;
        $show_venue = isset( $instance['show_venue'] ) ? (bool) $instance['show_venue'] : true;
        $show_image = isset( $instance['show_image'] ) ? (bool) $instance['show_image'] : false;
        $category = isset( $instance['category'] ) ? $instance['category'] : '';
        $venue_id = isset( $instance['venue_id'] ) ? absint( $instance['venue_id'] ) : 0;
        $show_calendar = isset( $instance['show_calendar'] ) ? (bool) $instance['show_calendar'] : false;
        $view_all_text = isset( $instance['view_all_text'] ) ? $instance['view_all_text'] : __( 'View All Events', 'aqualuxe' );
        $view_all_url = isset( $instance['view_all_url'] ) ? $instance['view_all_url'] : get_post_type_archive_link( 'aqualuxe_event' );

        // Get categories
        $categories = get_terms( [
            'taxonomy'   => 'event_category',
            'hide_empty' => false,
        ] );

        // Get venues
        $venues = get_posts( [
            'post_type'      => 'aqualuxe_venue',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ] );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of events to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display event date?', 'aqualuxe' ); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_venue ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_venue' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_venue' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_venue' ) ); ?>"><?php esc_html_e( 'Display event venue?', 'aqualuxe' ); ?></label>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_image ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Display event image?', 'aqualuxe' ); ?></label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
                <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                <?php if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) : ?>
                    <?php foreach ( $categories as $cat ) : ?>
                        <option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $category, $cat->slug ); ?>><?php echo esc_html( $cat->name ); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'venue_id' ) ); ?>"><?php esc_html_e( 'Venue:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'venue_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'venue_id' ) ); ?>">
                <option value="0"><?php esc_html_e( 'All Venues', 'aqualuxe' ); ?></option>
                <?php if ( ! empty( $venues ) ) : ?>
                    <?php foreach ( $venues as $venue ) : ?>
                        <option value="<?php echo esc_attr( $venue->ID ); ?>" <?php selected( $venue_id, $venue->ID ); ?>><?php echo esc_html( $venue->post_title ); ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </p>

        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_calendar ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_calendar' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_calendar' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_calendar' ) ); ?>"><?php esc_html_e( 'Display mini calendar?', 'aqualuxe' ); ?></label>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'view_all_text' ) ); ?>"><?php esc_html_e( 'View All Text:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_all_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_all_text' ) ); ?>" type="text" value="<?php echo esc_attr( $view_all_text ); ?>">
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'view_all_url' ) ); ?>"><?php esc_html_e( 'View All URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'view_all_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'view_all_url' ) ); ?>" type="text" value="<?php echo esc_url( $view_all_url ); ?>">
        </p>
        <?php
    }

    /**
     * Update widget settings
     *
     * @param array $new_instance
     * @param array $old_instance
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = sanitize_text_field( $new_instance['title'] );
        $instance['number'] = absint( $new_instance['number'] );
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $instance['show_venue'] = isset( $new_instance['show_venue'] ) ? (bool) $new_instance['show_venue'] : false;
        $instance['show_image'] = isset( $new_instance['show_image'] ) ? (bool) $new_instance['show_image'] : false;
        $instance['category'] = sanitize_text_field( $new_instance['category'] );
        $instance['venue_id'] = absint( $new_instance['venue_id'] );
        $instance['show_calendar'] = isset( $new_instance['show_calendar'] ) ? (bool) $new_instance['show_calendar'] : false;
        $instance['view_all_text'] = sanitize_text_field( $new_instance['view_all_text'] );
        $instance['view_all_url'] = esc_url_raw( $new_instance['view_all_url'] );

        return $instance;
    }
}

/**
 * Register Event Widget
 *
 * @return void
 */
function register_event_widget() {
    register_widget( __NAMESPACE__ . '\Event_Widget' );
}
add_action( 'widgets_init', __NAMESPACE__ . '\register_event_widget' );