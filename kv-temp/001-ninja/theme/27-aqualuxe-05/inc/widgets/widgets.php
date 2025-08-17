<?php
/**
 * Custom Widgets for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Register custom widgets
 */
function aqualuxe_register_widgets() {
    register_widget( 'AquaLuxe_Contact_Info_Widget' );
    register_widget( 'AquaLuxe_Social_Icons_Widget' );
    register_widget( 'AquaLuxe_Recent_Posts_Widget' );
    register_widget( 'AquaLuxe_Featured_Products_Widget' );
    register_widget( 'AquaLuxe_Business_Hours_Widget' );
    register_widget( 'AquaLuxe_Services_Widget' );
    register_widget( 'AquaLuxe_Call_To_Action_Widget' );
    register_widget( 'AquaLuxe_Newsletter_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_register_widgets' );

/**
 * Contact Info Widget
 */
class AquaLuxe_Contact_Info_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_contact_info',
            esc_html__( 'AquaLuxe: Contact Info', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display contact information.', 'aqualuxe' ),
                'classname'   => 'widget_contact_info',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : aqualuxe_get_option( 'aqualuxe_phone_number', '' );
        $email = ! empty( $instance['email'] ) ? $instance['email'] : aqualuxe_get_option( 'aqualuxe_email', '' );
        $address = ! empty( $instance['address'] ) ? $instance['address'] : aqualuxe_get_option( 'aqualuxe_address', '' );
        $show_map = ! empty( $instance['show_map'] ) ? $instance['show_map'] : false;

        echo '<div class="contact-info">';

        if ( ! empty( $phone ) ) {
            echo '<div class="contact-info-item phone mb-2 flex items-start">';
            echo '<div class="contact-info-icon mr-2 mt-1">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />';
            echo '</svg>';
            echo '</div>';
            echo '<div class="contact-info-text">';
            echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ) . '">' . esc_html( $phone ) . '</a>';
            echo '</div>';
            echo '</div>';
        }

        if ( ! empty( $email ) ) {
            echo '<div class="contact-info-item email mb-2 flex items-start">';
            echo '<div class="contact-info-icon mr-2 mt-1">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />';
            echo '</svg>';
            echo '</div>';
            echo '<div class="contact-info-text">';
            echo '<a href="mailto:' . esc_attr( $email ) . '">' . esc_html( $email ) . '</a>';
            echo '</div>';
            echo '</div>';
        }

        if ( ! empty( $address ) ) {
            echo '<div class="contact-info-item address mb-2 flex items-start">';
            echo '<div class="contact-info-icon mr-2 mt-1">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />';
            echo '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />';
            echo '</svg>';
            echo '</div>';
            echo '<div class="contact-info-text">';
            echo esc_html( $address );
            echo '</div>';
            echo '</div>';

            if ( $show_map ) {
                echo '<div class="contact-info-map mt-4">';
                $api_key = aqualuxe_get_option( 'aqualuxe_google_maps_api_key', '' );
                
                if ( ! empty( $api_key ) ) {
                    $map_url = 'https://www.google.com/maps/embed/v1/place?key=' . urlencode( $api_key ) . '&q=' . urlencode( $address );
                    echo '<iframe width="100%" height="200" frameborder="0" style="border:0" src="' . esc_url( $map_url ) . '" allowfullscreen></iframe>';
                } else {
                    echo '<a href="https://maps.google.com/?q=' . urlencode( $address ) . '" target="_blank" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded transition-colors">';
                    echo esc_html__( 'View on Map', 'aqualuxe' );
                    echo '</a>';
                }
                echo '</div>';
            }
        }

        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Contact Info', 'aqualuxe' );
        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
        $email = ! empty( $instance['email'] ) ? $instance['email'] : '';
        $address = ! empty( $instance['address'] ) ? $instance['address'] : '';
        $show_map = ! empty( $instance['show_map'] ) ? $instance['show_map'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
            <small><?php esc_html_e( 'Leave empty to use the phone number from theme options.', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="email" value="<?php echo esc_attr( $email ); ?>">
            <small><?php esc_html_e( 'Leave empty to use the email from theme options.', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" rows="3"><?php echo esc_textarea( $address ); ?></textarea>
            <small><?php esc_html_e( 'Leave empty to use the address from theme options.', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_map ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_map' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_map' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_map' ) ); ?>"><?php esc_html_e( 'Show map', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? sanitize_text_field( $new_instance['phone'] ) : '';
        $instance['email'] = ( ! empty( $new_instance['email'] ) ) ? sanitize_email( $new_instance['email'] ) : '';
        $instance['address'] = ( ! empty( $new_instance['address'] ) ) ? sanitize_textarea_field( $new_instance['address'] ) : '';
        $instance['show_map'] = ( ! empty( $new_instance['show_map'] ) ) ? true : false;

        return $instance;
    }
}

/**
 * Social Icons Widget
 */
class AquaLuxe_Social_Icons_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_social_icons',
            esc_html__( 'AquaLuxe: Social Icons', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display social media icons.', 'aqualuxe' ),
                'classname'   => 'widget_social_icons',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : aqualuxe_get_option( 'aqualuxe_facebook', '' );
        $twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : aqualuxe_get_option( 'aqualuxe_twitter', '' );
        $instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : aqualuxe_get_option( 'aqualuxe_instagram', '' );
        $linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : aqualuxe_get_option( 'aqualuxe_linkedin', '' );
        $youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : aqualuxe_get_option( 'aqualuxe_youtube', '' );
        $pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : aqualuxe_get_option( 'aqualuxe_pinterest', '' );

        echo '<div class="social-icons flex space-x-3">';

        if ( ! empty( $facebook ) ) {
            echo '<a href="' . esc_url( $facebook ) . '" class="social-icon facebook" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'Facebook', 'aqualuxe' ) . '">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/></svg>';
            echo '</a>';
        }

        if ( ! empty( $twitter ) ) {
            echo '<a href="' . esc_url( $twitter ) . '" class="social-icon twitter" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'Twitter', 'aqualuxe' ) . '">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>';
            echo '</a>';
        }

        if ( ! empty( $instagram ) ) {
            echo '<a href="' . esc_url( $instagram ) . '" class="social-icon instagram" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'Instagram', 'aqualuxe' ) . '">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>';
            echo '</a>';
        }

        if ( ! empty( $linkedin ) ) {
            echo '<a href="' . esc_url( $linkedin ) . '" class="social-icon linkedin" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'LinkedIn', 'aqualuxe' ) . '">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/></svg>';
            echo '</a>';
        }

        if ( ! empty( $youtube ) ) {
            echo '<a href="' . esc_url( $youtube ) . '" class="social-icon youtube" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'YouTube', 'aqualuxe' ) . '">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.615 3.184c-3.604-.246-11.631-.245-15.23 0-3.897.266-4.356 2.62-4.385 8.816.029 6.185.484 8.549 4.385 8.816 3.6.245 11.626.246 15.23 0 3.897-.266 4.356-2.62 4.385-8.816-.029-6.185-.484-8.549-4.385-8.816zm-10.615 12.816v-8l8 3.993-8 4.007z"/></svg>';
            echo '</a>';
        }

        if ( ! empty( $pinterest ) ) {
            echo '<a href="' . esc_url( $pinterest ) . '" class="social-icon pinterest" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr__( 'Pinterest', 'aqualuxe' ) . '">';
            echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd"/></svg>';
            echo '</a>';
        }

        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow Us', 'aqualuxe' );
        $facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="url" value="<?php echo esc_url( $facebook ); ?>">
            <small><?php esc_html_e( 'Leave empty to use the URL from theme options.', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="url" value="<?php echo esc_url( $twitter ); ?>">
            <small><?php esc_html_e( 'Leave empty to use the URL from theme options.', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="url" value="<?php echo esc_url( $instagram ); ?>">
            <small><?php esc_html_e( 'Leave empty to use the URL from theme options.', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="url" value="<?php echo esc_url( $linkedin ); ?>">
            <small><?php esc_html_e( 'Leave empty to use the URL from theme options.', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'YouTube URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" type="url" value="<?php echo esc_url( $youtube ); ?>">
            <small><?php esc_html_e( 'Leave empty to use the URL from theme options.', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>"><?php esc_html_e( 'Pinterest URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest' ) ); ?>" type="url" value="<?php echo esc_url( $pinterest ); ?>">
            <small><?php esc_html_e( 'Leave empty to use the URL from theme options.', 'aqualuxe' ); ?></small>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['facebook'] = ( ! empty( $new_instance['facebook'] ) ) ? esc_url_raw( $new_instance['facebook'] ) : '';
        $instance['twitter'] = ( ! empty( $new_instance['twitter'] ) ) ? esc_url_raw( $new_instance['twitter'] ) : '';
        $instance['instagram'] = ( ! empty( $new_instance['instagram'] ) ) ? esc_url_raw( $new_instance['instagram'] ) : '';
        $instance['linkedin'] = ( ! empty( $new_instance['linkedin'] ) ) ? esc_url_raw( $new_instance['linkedin'] ) : '';
        $instance['youtube'] = ( ! empty( $new_instance['youtube'] ) ) ? esc_url_raw( $new_instance['youtube'] ) : '';
        $instance['pinterest'] = ( ! empty( $new_instance['pinterest'] ) ) ? esc_url_raw( $new_instance['pinterest'] ) : '';

        return $instance;
    }
}

/**
 * Recent Posts Widget
 */
class AquaLuxe_Recent_Posts_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_recent_posts',
            esc_html__( 'AquaLuxe: Recent Posts', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display recent posts with thumbnails.', 'aqualuxe' ),
                'classname'   => 'widget_recent_posts',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = ! empty( $instance['show_date'] ) ? true : false;
        $show_thumbnail = ! empty( $instance['show_thumbnail'] ) ? true : false;
        $show_excerpt = ! empty( $instance['show_excerpt'] ) ? true : false;
        $excerpt_length = ! empty( $instance['excerpt_length'] ) ? absint( $instance['excerpt_length'] ) : 20;

        $recent_posts = wp_get_recent_posts(
            array(
                'numberposts' => $number,
                'post_status' => 'publish',
            )
        );

        if ( ! empty( $recent_posts ) ) {
            echo '<ul class="recent-posts">';

            foreach ( $recent_posts as $recent ) {
                echo '<li class="recent-post mb-4 pb-4 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0">';

                if ( $show_thumbnail && has_post_thumbnail( $recent['ID'] ) ) {
                    echo '<div class="recent-post-thumbnail mb-2">';
                    echo '<a href="' . esc_url( get_permalink( $recent['ID'] ) ) . '">';
                    echo get_the_post_thumbnail( $recent['ID'], 'thumbnail', array( 'class' => 'rounded' ) );
                    echo '</a>';
                    echo '</div>';
                }

                echo '<div class="recent-post-content">';
                echo '<h4 class="recent-post-title text-base font-medium mb-1"><a href="' . esc_url( get_permalink( $recent['ID'] ) ) . '">' . esc_html( $recent['post_title'] ) . '</a></h4>';

                if ( $show_date ) {
                    echo '<div class="recent-post-date text-sm text-gray-600 mb-1">' . esc_html( get_the_date( '', $recent['ID'] ) ) . '</div>';
                }

                if ( $show_excerpt ) {
                    echo '<div class="recent-post-excerpt text-sm">' . wp_trim_words( get_the_excerpt( $recent['ID'] ), $excerpt_length, '&hellip;' ) . '</div>';
                }

                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
        }

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = ! empty( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = ! empty( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : false;
        $show_excerpt = ! empty( $instance['show_excerpt'] ) ? (bool) $instance['show_excerpt'] : false;
        $excerpt_length = ! empty( $instance['excerpt_length'] ) ? absint( $instance['excerpt_length'] ) : 20;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"><?php esc_html_e( 'Display post thumbnail?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_excerpt ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_html_e( 'Display post excerpt?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>"><?php esc_html_e( 'Excerpt length:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'excerpt_length' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'excerpt_length' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $excerpt_length ); ?>" size="3">
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : false;
        $instance['show_excerpt'] = isset( $new_instance['show_excerpt'] ) ? (bool) $new_instance['show_excerpt'] : false;
        $instance['excerpt_length'] = ( ! empty( $new_instance['excerpt_length'] ) ) ? absint( $new_instance['excerpt_length'] ) : 20;

        return $instance;
    }
}

/**
 * Featured Products Widget
 */
class AquaLuxe_Featured_Products_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_featured_products',
            esc_html__( 'AquaLuxe: Featured Products', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display featured products.', 'aqualuxe' ),
                'classname'   => 'widget_featured_products',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }

        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
        $show_rating = ! empty( $instance['show_rating'] ) ? true : false;

        $query_args = array(
            'posts_per_page' => $number,
            'post_status'    => 'publish',
            'post_type'      => 'product',
            'meta_query'     => array(
                array(
                    'key'   => '_featured',
                    'value' => 'yes',
                ),
            ),
            'tax_query'      => array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'exclude-from-catalog',
                    'operator' => 'NOT IN',
                ),
            ),
        );

        $products = new WP_Query( $query_args );

        if ( $products->have_posts() ) {
            echo '<ul class="featured-products">';

            while ( $products->have_posts() ) {
                $products->the_post();
                global $product;

                echo '<li class="featured-product mb-4 pb-4 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0">';
                echo '<div class="featured-product-inner flex">';

                if ( has_post_thumbnail() ) {
                    echo '<div class="featured-product-thumbnail mr-4">';
                    echo '<a href="' . esc_url( get_permalink() ) . '">';
                    echo woocommerce_get_product_thumbnail( 'thumbnail' );
                    echo '</a>';
                    echo '</div>';
                }

                echo '<div class="featured-product-content">';
                echo '<h4 class="featured-product-title text-base font-medium mb-1"><a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a></h4>';
                echo '<div class="featured-product-price mb-1">' . $product->get_price_html() . '</div>';

                if ( $show_rating && $product->get_rating_count() > 0 ) {
                    echo '<div class="featured-product-rating">';
                    echo wc_get_rating_html( $product->get_average_rating() );
                    echo '</div>';
                }

                echo '</div>';
                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
        }

        wp_reset_postdata();

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Featured Products', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
        $show_rating = ! empty( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of products to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_rating ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php esc_html_e( 'Display product rating?', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 4;
        $instance['show_rating'] = isset( $new_instance['show_rating'] ) ? (bool) $new_instance['show_rating'] : false;

        return $instance;
    }
}

/**
 * Business Hours Widget
 */
class AquaLuxe_Business_Hours_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_business_hours',
            esc_html__( 'AquaLuxe: Business Hours', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display business hours.', 'aqualuxe' ),
                'classname'   => 'widget_business_hours',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $days = array(
            'monday'    => __( 'Monday', 'aqualuxe' ),
            'tuesday'   => __( 'Tuesday', 'aqualuxe' ),
            'wednesday' => __( 'Wednesday', 'aqualuxe' ),
            'thursday'  => __( 'Thursday', 'aqualuxe' ),
            'friday'    => __( 'Friday', 'aqualuxe' ),
            'saturday'  => __( 'Saturday', 'aqualuxe' ),
            'sunday'    => __( 'Sunday', 'aqualuxe' ),
        );

        echo '<ul class="business-hours">';

        foreach ( $days as $day_key => $day_label ) {
            $hours = ! empty( $instance[ $day_key ] ) ? $instance[ $day_key ] : '';

            echo '<li class="business-hours-item mb-2 flex justify-between">';
            echo '<span class="business-hours-day">' . esc_html( $day_label ) . '</span>';
            echo '<span class="business-hours-time">' . esc_html( $hours ) . '</span>';
            echo '</li>';
        }

        echo '</ul>';

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Business Hours', 'aqualuxe' );
        $days = array(
            'monday'    => __( 'Monday', 'aqualuxe' ),
            'tuesday'   => __( 'Tuesday', 'aqualuxe' ),
            'wednesday' => __( 'Wednesday', 'aqualuxe' ),
            'thursday'  => __( 'Thursday', 'aqualuxe' ),
            'friday'    => __( 'Friday', 'aqualuxe' ),
            'saturday'  => __( 'Saturday', 'aqualuxe' ),
            'sunday'    => __( 'Sunday', 'aqualuxe' ),
        );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <?php foreach ( $days as $day_key => $day_label ) : ?>
            <p>
                <label for="<?php echo esc_attr( $this->get_field_id( $day_key ) ); ?>"><?php echo esc_html( $day_label ); ?>:</label>
                <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( $day_key ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( $day_key ) ); ?>" type="text" value="<?php echo esc_attr( ! empty( $instance[ $day_key ] ) ? $instance[ $day_key ] : '' ); ?>">
                <small><?php esc_html_e( 'e.g. 9:00 AM - 5:00 PM or Closed', 'aqualuxe' ); ?></small>
            </p>
        <?php endforeach; ?>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $days = array( 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday' );

        foreach ( $days as $day ) {
            $instance[ $day ] = ( ! empty( $new_instance[ $day ] ) ) ? sanitize_text_field( $new_instance[ $day ] ) : '';
        }

        return $instance;
    }
}

/**
 * Services Widget
 */
class AquaLuxe_Services_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_services',
            esc_html__( 'AquaLuxe: Services', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display services.', 'aqualuxe' ),
                'classname'   => 'widget_services',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
        $category = ! empty( $instance['category'] ) ? $instance['category'] : '';
        $show_excerpt = ! empty( $instance['show_excerpt'] ) ? true : false;

        $query_args = array(
            'post_type'      => 'service',
            'posts_per_page' => $number,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        );

        if ( ! empty( $category ) ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'service_category',
                    'field'    => 'slug',
                    'terms'    => $category,
                ),
            );
        }

        $services = new WP_Query( $query_args );

        if ( $services->have_posts() ) {
            echo '<ul class="services-list">';

            while ( $services->have_posts() ) {
                $services->the_post();

                $service_icon = get_post_meta( get_the_ID(), '_service_icon', true );

                echo '<li class="service-item mb-4 pb-4 border-b border-gray-200 last:border-0 last:mb-0 last:pb-0">';
                echo '<div class="service-item-inner">';

                echo '<h4 class="service-title text-base font-medium mb-1">';
                if ( ! empty( $service_icon ) ) {
                    echo '<i class="' . esc_attr( $service_icon ) . ' mr-1"></i> ';
                }
                echo '<a href="' . esc_url( get_permalink() ) . '">' . esc_html( get_the_title() ) . '</a>';
                echo '</h4>';

                if ( $show_excerpt ) {
                    echo '<div class="service-excerpt text-sm">' . wp_trim_words( get_the_excerpt(), 20, '&hellip;' ) . '</div>';
                }

                echo '</div>';
                echo '</li>';
            }

            echo '</ul>';
        }

        wp_reset_postdata();

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Our Services', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
        $category = ! empty( $instance['category'] ) ? $instance['category'] : '';
        $show_excerpt = ! empty( $instance['show_excerpt'] ) ? (bool) $instance['show_excerpt'] : false;

        // Get service categories
        $categories = get_terms( array(
            'taxonomy'   => 'service_category',
            'hide_empty' => false,
        ) );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of services to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'aqualuxe' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'category' ) ); ?>" class="widefat">
                <option value=""><?php esc_html_e( 'All Categories', 'aqualuxe' ); ?></option>
                <?php foreach ( $categories as $cat ) : ?>
                    <option value="<?php echo esc_attr( $cat->slug ); ?>" <?php selected( $category, $cat->slug ); ?>><?php echo esc_html( $cat->name ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_excerpt ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_html_e( 'Display service excerpt?', 'aqualuxe' ); ?></label>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 3;
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? sanitize_text_field( $new_instance['category'] ) : '';
        $instance['show_excerpt'] = isset( $new_instance['show_excerpt'] ) ? (bool) $new_instance['show_excerpt'] : false;

        return $instance;
    }
}

/**
 * Call to Action Widget
 */
class AquaLuxe_Call_To_Action_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_call_to_action',
            esc_html__( 'AquaLuxe: Call to Action', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display a call to action box.', 'aqualuxe' ),
                'classname'   => 'widget_call_to_action',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $text = ! empty( $instance['text'] ) ? $instance['text'] : '';
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : '';
        $button_url = ! empty( $instance['button_url'] ) ? $instance['button_url'] : '';
        $bg_color = ! empty( $instance['bg_color'] ) ? $instance['bg_color'] : '#1e40af';
        $text_color = ! empty( $instance['text_color'] ) ? $instance['text_color'] : '#ffffff';
        $button_style = ! empty( $instance['button_style'] ) ? $instance['button_style'] : 'solid';

        echo '<div class="call-to-action p-6 rounded-lg" style="background-color: ' . esc_attr( $bg_color ) . '; color: ' . esc_attr( $text_color ) . ';">';

        if ( ! empty( $title ) ) {
            echo '<h3 class="call-to-action-title text-xl font-bold mb-4">' . esc_html( $title ) . '</h3>';
        }

        if ( ! empty( $text ) ) {
            echo '<div class="call-to-action-text mb-4">' . wp_kses_post( $text ) . '</div>';
        }

        if ( ! empty( $button_text ) && ! empty( $button_url ) ) {
            $button_classes = 'solid' === $button_style ? 'bg-white hover:bg-gray-100 text-blue-900' : 'bg-transparent hover:bg-white hover:bg-opacity-20 text-white border border-white';

            echo '<a href="' . esc_url( $button_url ) . '" class="call-to-action-button inline-block ' . esc_attr( $button_classes ) . ' font-medium py-2 px-6 rounded transition-colors">';
            echo esc_html( $button_text );
            echo '</a>';
        }

        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $text = ! empty( $instance['text'] ) ? $instance['text'] : '';
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : '';
        $button_url = ! empty( $instance['button_url'] ) ? $instance['button_url'] : '';
        $bg_color = ! empty( $instance['bg_color'] ) ? $instance['bg_color'] : '#1e40af';
        $text_color = ! empty( $instance['text_color'] ) ? $instance['text_color'] : '#ffffff';
        $button_style = ! empty( $instance['button_style'] ) ? $instance['button_style'] : 'solid';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'Text:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" rows="4"><?php echo esc_textarea( $text ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_url' ) ); ?>"><?php esc_html_e( 'Button URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_url' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_url' ) ); ?>" type="url" value="<?php echo esc_url( $button_url ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>"><?php esc_html_e( 'Background Color:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'bg_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'bg_color' ) ); ?>" type="color" value="<?php echo esc_attr( $bg_color ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>"><?php esc_html_e( 'Text Color:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text_color' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text_color' ) ); ?>" type="color" value="<?php echo esc_attr( $text_color ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_style' ) ); ?>"><?php esc_html_e( 'Button Style:', 'aqualuxe' ); ?></label>
            <select id="<?php echo esc_attr( $this->get_field_id( 'button_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_style' ) ); ?>" class="widefat">
                <option value="solid" <?php selected( $button_style, 'solid' ); ?>><?php esc_html_e( 'Solid', 'aqualuxe' ); ?></option>
                <option value="outline" <?php selected( $button_style, 'outline' ); ?>><?php esc_html_e( 'Outline', 'aqualuxe' ); ?></option>
            </select>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['text'] = ( ! empty( $new_instance['text'] ) ) ? wp_kses_post( $new_instance['text'] ) : '';
        $instance['button_text'] = ( ! empty( $new_instance['button_text'] ) ) ? sanitize_text_field( $new_instance['button_text'] ) : '';
        $instance['button_url'] = ( ! empty( $new_instance['button_url'] ) ) ? esc_url_raw( $new_instance['button_url'] ) : '';
        $instance['bg_color'] = ( ! empty( $new_instance['bg_color'] ) ) ? sanitize_hex_color( $new_instance['bg_color'] ) : '#1e40af';
        $instance['text_color'] = ( ! empty( $new_instance['text_color'] ) ) ? sanitize_hex_color( $new_instance['text_color'] ) : '#ffffff';
        $instance['button_style'] = ( ! empty( $new_instance['button_style'] ) ) ? sanitize_text_field( $new_instance['button_style'] ) : 'solid';

        return $instance;
    }
}

/**
 * Newsletter Widget
 */
class AquaLuxe_Newsletter_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_newsletter',
            esc_html__( 'AquaLuxe: Newsletter', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display a newsletter subscription form.', 'aqualuxe' ),
                'classname'   => 'widget_newsletter',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @see WP_Widget::widget()
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];

        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }

        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';
        $form_action = ! empty( $instance['form_action'] ) ? $instance['form_action'] : '#';
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : __( 'Subscribe', 'aqualuxe' );
        $privacy_text = ! empty( $instance['privacy_text'] ) ? $instance['privacy_text'] : '';

        echo '<div class="newsletter-widget">';

        if ( ! empty( $description ) ) {
            echo '<div class="newsletter-description mb-4">' . wp_kses_post( $description ) . '</div>';
        }

        echo '<form class="newsletter-form" action="' . esc_url( $form_action ) . '" method="post">';
        echo '<div class="newsletter-form-fields">';
        echo '<input type="email" name="email" placeholder="' . esc_attr__( 'Your Email Address', 'aqualuxe' ) . '" required class="w-full p-3 border border-gray-300 rounded mb-3" />';
        echo '<button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded transition-colors">' . esc_html( $button_text ) . '</button>';
        echo '</div>';

        if ( ! empty( $privacy_text ) ) {
            echo '<div class="newsletter-privacy mt-3 text-sm text-gray-600">';
            echo '<label class="flex items-start">';
            echo '<input type="checkbox" name="privacy" required class="mt-1 mr-2" />';
            echo '<span>' . wp_kses_post( $privacy_text ) . '</span>';
            echo '</label>';
            echo '</div>';
        }

        echo '</form>';
        echo '</div>';

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @see WP_Widget::form()
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Newsletter', 'aqualuxe' );
        $description = ! empty( $instance['description'] ) ? $instance['description'] : esc_html__( 'Subscribe to our newsletter to receive updates and special offers.', 'aqualuxe' );
        $form_action = ! empty( $instance['form_action'] ) ? $instance['form_action'] : '#';
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : esc_html__( 'Subscribe', 'aqualuxe' );
        $privacy_text = ! empty( $instance['privacy_text'] ) ? $instance['privacy_text'] : esc_html__( 'I agree to the privacy policy and consent to having my email address processed to receive newsletters.', 'aqualuxe' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" rows="3"><?php echo esc_textarea( $description ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'form_action' ) ); ?>"><?php esc_html_e( 'Form Action URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_action' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form_action' ) ); ?>" type="url" value="<?php echo esc_url( $form_action ); ?>">
            <small><?php esc_html_e( 'Enter the URL where the form should be submitted (e.g., Mailchimp form action URL).', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'privacy_text' ) ); ?>"><?php esc_html_e( 'Privacy Text:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'privacy_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'privacy_text' ) ); ?>" rows="3"><?php echo esc_textarea( $privacy_text ); ?></textarea>
            <small><?php esc_html_e( 'Leave empty to hide the privacy checkbox.', 'aqualuxe' ); ?></small>
        </p>
        <?php
    }

    /**
     * Sanitize widget form values as they are saved.
     *
     * @see WP_Widget::update()
     *
     * @param array $new_instance Values just sent to be saved.
     * @param array $old_instance Previously saved values from database.
     *
     * @return array Updated safe values to be saved.
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? wp_kses_post( $new_instance['description'] ) : '';
        $instance['form_action'] = ( ! empty( $new_instance['form_action'] ) ) ? esc_url_raw( $new_instance['form_action'] ) : '#';
        $instance['button_text'] = ( ! empty( $new_instance['button_text'] ) ) ? sanitize_text_field( $new_instance['button_text'] ) : '';
        $instance['privacy_text'] = ( ! empty( $new_instance['privacy_text'] ) ) ? wp_kses_post( $new_instance['privacy_text'] ) : '';

        return $instance;
    }
}