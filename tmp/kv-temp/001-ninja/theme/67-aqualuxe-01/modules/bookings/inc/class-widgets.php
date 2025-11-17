<?php
/**
 * Widgets Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Widgets Class
 * 
 * This class handles module widgets.
 */
class Widgets {
    /**
     * Instance of this class
     *
     * @var Widgets
     */
    private static $instance = null;

    /**
     * Get the singleton instance
     *
     * @return Widgets
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->init_hooks();
    }

    /**
     * Initialize hooks
     *
     * @return void
     */
    private function init_hooks() {
        // Register widgets
        add_action( 'widgets_init', [ $this, 'register_widgets' ] );
    }

    /**
     * Register widgets
     *
     * @return void
     */
    public function register_widgets() {
        register_widget( __NAMESPACE__ . '\Widgets\Booking_Form' );
        register_widget( __NAMESPACE__ . '\Widgets\Services' );
        register_widget( __NAMESPACE__ . '\Widgets\Calendar' );
        register_widget( __NAMESPACE__ . '\Widgets\Availability' );
    }
}

/**
 * Booking Form Widget
 */
namespace AquaLuxe\Modules\Bookings\Widgets;

/**
 * Booking Form Widget
 */
class Booking_Form extends \WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_booking_form',
            __( 'AquaLuxe Booking Form', 'aqualuxe' ),
            [
                'description' => __( 'Display a booking form.', 'aqualuxe' ),
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     * @return void
     */
    public function widget( $args, $instance ) {
        // Get widget settings
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Book an Appointment', 'aqualuxe' );
        $service_id = ! empty( $instance['service_id'] ) ? $instance['service_id'] : 0;
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : __( 'Book Now', 'aqualuxe' );
        
        // Before widget
        echo $args['before_widget'];
        
        // Title
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
        }
        
        // Widget content
        echo do_shortcode( '[aqualuxe_booking_form service_id="' . esc_attr( $service_id ) . '" button="' . esc_attr( $button_text ) . '"]' );
        
        // After widget
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        // Get widget settings
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Book an Appointment', 'aqualuxe' );
        $service_id = ! empty( $instance['service_id'] ) ? $instance['service_id'] : 0;
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : __( 'Book Now', 'aqualuxe' );
        
        // Title field
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        
        <?php
        // Service field
        $services = aqualuxe_get_services();
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'service_id' ) ); ?>"><?php esc_html_e( 'Service:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'service_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'service_id' ) ); ?>">
                <option value="0"><?php esc_html_e( 'Select a service', 'aqualuxe' ); ?></option>
                <?php foreach ( $services as $service ) : ?>
                    <option value="<?php echo esc_attr( $service->get_id() ); ?>" <?php selected( $service_id, $service->get_id() ); ?>><?php echo esc_html( $service->get_title() ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <?php
        // Button text field
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = [];
        
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['service_id'] = ! empty( $new_instance['service_id'] ) ? absint( $new_instance['service_id'] ) : 0;
        $instance['button_text'] = ! empty( $new_instance['button_text'] ) ? sanitize_text_field( $new_instance['button_text'] ) : '';
        
        return $instance;
    }
}

/**
 * Services Widget
 */
class Services extends \WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_services',
            __( 'AquaLuxe Services', 'aqualuxe' ),
            [
                'description' => __( 'Display a list of services.', 'aqualuxe' ),
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     * @return void
     */
    public function widget( $args, $instance ) {
        // Get widget settings
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Our Services', 'aqualuxe' );
        $category = ! empty( $instance['category'] ) ? $instance['category'] : '';
        $limit = ! empty( $instance['limit'] ) ? $instance['limit'] : 3;
        $show_image = isset( $instance['show_image'] ) ? $instance['show_image'] : true;
        $show_price = isset( $instance['show_price'] ) ? $instance['show_price'] : true;
        $show_desc = isset( $instance['show_desc'] ) ? $instance['show_desc'] : true;
        $show_button = isset( $instance['show_button'] ) ? $instance['show_button'] : true;
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : __( 'Book Now', 'aqualuxe' );
        
        // Before widget
        echo $args['before_widget'];
        
        // Title
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
        }
        
        // Widget content
        echo do_shortcode( '[aqualuxe_services category="' . esc_attr( $category ) . '" limit="' . esc_attr( $limit ) . '" show_image="' . esc_attr( $show_image ? 'true' : 'false' ) . '" show_price="' . esc_attr( $show_price ? 'true' : 'false' ) . '" show_desc="' . esc_attr( $show_desc ? 'true' : 'false' ) . '" show_button="' . esc_attr( $show_button ? 'true' : 'false' ) . '" button_text="' . esc_attr( $button_text ) . '"]' );
        
        // After widget
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        // Get widget settings
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Our Services', 'aqualuxe' );
        $category = ! empty( $instance['category'] ) ? $instance['category'] : '';
        $limit = ! empty( $instance['limit'] ) ? $instance['limit'] : 3;
        $show_image = isset( $instance['show_image'] ) ? $instance['show_image'] : true;
        $show_price = isset( $instance['show_price'] ) ? $instance['show_price'] : true;
        $show_desc = isset( $instance['show_desc'] ) ? $instance['show_desc'] : true;
        $show_button = isset( $instance['show_button'] ) ? $instance['show_button'] : true;
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : __( 'Book Now', 'aqualuxe' );
        
        // Title field
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        
        <?php
        // Category field
        $categories = aqualuxe_get_service_categories();
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>">
                <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                <?php foreach ( $categories as $cat ) : ?>
                    <option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $category, $cat->slug ); ?>><?php echo esc_html( $cat->name ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        
        <?php
        // Limit field
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Number of Services:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $limit ); ?>" size="3">
        </p>
        
        <?php
        // Show image field
        ?>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_image ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Show Image', 'aqualuxe' ); ?></label>
        </p>
        
        <?php
        // Show price field
        ?>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_price ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_price' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>"><?php esc_html_e( 'Show Price', 'aqualuxe' ); ?></label>
        </p>
        
        <?php
        // Show description field
        ?>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_desc ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_desc' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_desc' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_desc' ) ); ?>"><?php esc_html_e( 'Show Description', 'aqualuxe' ); ?></label>
        </p>
        
        <?php
        // Show button field
        ?>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_button ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_button' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_button' ) ); ?>" />
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_button' ) ); ?>"><?php esc_html_e( 'Show Button', 'aqualuxe' ); ?></label>
        </p>
        
        <?php
        // Button text field
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = [];
        
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['category'] = ! empty( $new_instance['category'] ) ? sanitize_text_field( $new_instance['category'] ) : '';
        $instance['limit'] = ! empty( $new_instance['limit'] ) ? absint( $new_instance['limit'] ) : 3;
        $instance['show_image'] = isset( $new_instance['show_image'] ) ? (bool) $new_instance['show_image'] : false;
        $instance['show_price'] = isset( $new_instance['show_price'] ) ? (bool) $new_instance['show_price'] : false;
        $instance['show_desc'] = isset( $new_instance['show_desc'] ) ? (bool) $new_instance['show_desc'] : false;
        $instance['show_button'] = isset( $new_instance['show_button'] ) ? (bool) $new_instance['show_button'] : false;
        $instance['button_text'] = ! empty( $new_instance['button_text'] ) ? sanitize_text_field( $new_instance['button_text'] ) : '';
        
        return $instance;
    }
}

/**
 * Calendar Widget
 */
class Calendar extends \WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_calendar',
            __( 'AquaLuxe Calendar', 'aqualuxe' ),
            [
                'description' => __( 'Display a booking calendar.', 'aqualuxe' ),
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     * @return void
     */
    public function widget( $args, $instance ) {
        // Get widget settings
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Booking Calendar', 'aqualuxe' );
        $service_id = ! empty( $instance['service_id'] ) ? $instance['service_id'] : 0;
        
        // Before widget
        echo $args['before_widget'];
        
        // Title
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
        }
        
        // Widget content
        echo do_shortcode( '[aqualuxe_calendar service_id="' . esc_attr( $service_id ) . '"]' );
        
        // After widget
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        // Get widget settings
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Booking Calendar', 'aqualuxe' );
        $service_id = ! empty( $instance['service_id'] ) ? $instance['service_id'] : 0;
        
        // Title field
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        
        <?php
        // Service field
        $services = aqualuxe_get_services();
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'service_id' ) ); ?>"><?php esc_html_e( 'Service:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'service_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'service_id' ) ); ?>">
                <option value="0"><?php esc_html_e( 'All Services', 'aqualuxe' ); ?></option>
                <?php foreach ( $services as $service ) : ?>
                    <option value="<?php echo esc_attr( $service->get_id() ); ?>" <?php selected( $service_id, $service->get_id() ); ?>><?php echo esc_html( $service->get_title() ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = [];
        
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['service_id'] = ! empty( $new_instance['service_id'] ) ? absint( $new_instance['service_id'] ) : 0;
        
        return $instance;
    }
}

/**
 * Availability Widget
 */
class Availability extends \WP_Widget {
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_availability',
            __( 'AquaLuxe Availability', 'aqualuxe' ),
            [
                'description' => __( 'Display service availability.', 'aqualuxe' ),
            ]
        );
    }

    /**
     * Widget output
     *
     * @param array $args Widget arguments.
     * @param array $instance Widget instance.
     * @return void
     */
    public function widget( $args, $instance ) {
        // Get widget settings
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Service Availability', 'aqualuxe' );
        $service_id = ! empty( $instance['service_id'] ) ? $instance['service_id'] : 0;
        
        // Before widget
        echo $args['before_widget'];
        
        // Title
        if ( ! empty( $title ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $title ) . $args['after_title'];
        }
        
        // Widget content
        echo do_shortcode( '[aqualuxe_availability service_id="' . esc_attr( $service_id ) . '"]' );
        
        // After widget
        echo $args['after_widget'];
    }

    /**
     * Widget form
     *
     * @param array $instance Widget instance.
     * @return void
     */
    public function form( $instance ) {
        // Get widget settings
        $title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Service Availability', 'aqualuxe' );
        $service_id = ! empty( $instance['service_id'] ) ? $instance['service_id'] : 0;
        
        // Title field
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        
        <?php
        // Service field
        $services = aqualuxe_get_services();
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'service_id' ) ); ?>"><?php esc_html_e( 'Service:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'service_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'service_id' ) ); ?>">
                <option value="0"><?php esc_html_e( 'Select a service', 'aqualuxe' ); ?></option>
                <?php foreach ( $services as $service ) : ?>
                    <option value="<?php echo esc_attr( $service->get_id() ); ?>" <?php selected( $service_id, $service->get_id() ); ?>><?php echo esc_html( $service->get_title() ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <?php
    }

    /**
     * Widget update
     *
     * @param array $new_instance New widget instance.
     * @param array $old_instance Old widget instance.
     * @return array
     */
    public function update( $new_instance, $old_instance ) {
        $instance = [];
        
        $instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['service_id'] = ! empty( $new_instance['service_id'] ) ? absint( $new_instance['service_id'] ) : 0;
        
        return $instance;
    }
}