<?php
/**
 * Availability Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Availability Class
 * 
 * This class handles service availability.
 */
class Availability {
    /**
     * Service ID
     *
     * @var int
     */
    private $service_id;

    /**
     * Service
     *
     * @var Service
     */
    private $service;

    /**
     * Date
     *
     * @var string
     */
    private $date;

    /**
     * Constructor
     *
     * @param int    $service_id Service ID.
     * @param string $date Date in Y-m-d format.
     */
    public function __construct( $service_id, $date = '' ) {
        $this->service_id = $service_id;
        $this->date = $date ? $date : date( 'Y-m-d' );
        $this->service = new Service( $this->service_id );
    }

    /**
     * Get available time slots
     *
     * @return array
     */
    public function get_available_time_slots() {
        return $this->service->get_available_time_slots( $this->date );
    }

    /**
     * Generate availability HTML
     *
     * @return string
     */
    public function generate() {
        // Get available time slots
        $available_slots = $this->get_available_time_slots();
        
        // Start output buffering
        ob_start();
        
        // Availability header
        ?>
        <div class="aqualuxe-availability">
            <div class="availability-header">
                <h3 class="availability-title"><?php echo esc_html( sprintf( __( 'Available Times for %s', 'aqualuxe' ), date( 'F j, Y', strtotime( $this->date ) ) ) ); ?></h3>
            </div>
            <div class="availability-content">
                <?php if ( ! empty( $available_slots ) ) : ?>
                    <div class="time-slots">
                        <?php foreach ( $available_slots as $slot ) : ?>
                            <div class="time-slot">
                                <input type="radio" name="booking_time" id="time-<?php echo esc_attr( $slot ); ?>" value="<?php echo esc_attr( $slot ); ?>">
                                <label for="time-<?php echo esc_attr( $slot ); ?>"><?php echo esc_html( $this->format_time( $slot ) ); ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <div class="no-availability">
                        <p><?php esc_html_e( 'No available time slots for this date.', 'aqualuxe' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        
        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Format time
     *
     * @param string $time Time in HH:MM format.
     * @return string
     */
    private function format_time( $time ) {
        // Get time format
        $settings = get_option( 'aqualuxe_bookings_settings', [] );
        $time_format = isset( $settings['time_format'] ) ? $settings['time_format'] : '12';
        
        // Format time
        $time_obj = new \DateTime( $this->date . ' ' . $time );
        
        if ( $time_format === '12' ) {
            return $time_obj->format( 'g:i A' );
        } else {
            return $time_obj->format( 'H:i' );
        }
    }

    /**
     * Check if service is available on date
     *
     * @return bool
     */
    public function is_available() {
        return $this->service->is_available_on_date( $this->date );
    }

    /**
     * Check if time slot is available
     *
     * @param string $time Time in HH:MM format.
     * @return bool
     */
    public function is_time_slot_available( $time ) {
        $available_slots = $this->get_available_time_slots();
        return in_array( $time, $available_slots );
    }

    /**
     * Get next available date
     *
     * @return string
     */
    public function get_next_available_date() {
        return $this->service->get_next_available_date();
    }

    /**
     * Get next available time slot
     *
     * @return string
     */
    public function get_next_available_time_slot() {
        return $this->service->get_next_available_time_slot();
    }

    /**
     * Get available dates for the next X days
     *
     * @param int $days Number of days to check.
     * @return array
     */
    public function get_available_dates( $days = 30 ) {
        return $this->service->get_available_dates( $days );
    }

    /**
     * Generate date picker HTML
     *
     * @return string
     */
    public function generate_date_picker() {
        // Get available dates
        $available_dates = $this->get_available_dates();
        
        // Start output buffering
        ob_start();
        
        // Date picker header
        ?>
        <div class="aqualuxe-date-picker">
            <div class="date-picker-header">
                <h3 class="date-picker-title"><?php esc_html_e( 'Select a Date', 'aqualuxe' ); ?></h3>
            </div>
            <div class="date-picker-content">
                <?php if ( ! empty( $available_dates ) ) : ?>
                    <div class="date-picker-calendar">
                        <div class="date-picker-months">
                            <?php
                            // Group dates by month
                            $dates_by_month = [];
                            foreach ( $available_dates as $date ) {
                                $month_year = date( 'F Y', strtotime( $date ) );
                                if ( ! isset( $dates_by_month[ $month_year ] ) ) {
                                    $dates_by_month[ $month_year ] = [];
                                }
                                $dates_by_month[ $month_year ][] = $date;
                            }
                            
                            // Display months
                            foreach ( $dates_by_month as $month_year => $dates ) :
                                $month = date( 'n', strtotime( $dates[0] ) );
                                $year = date( 'Y', strtotime( $dates[0] ) );
                            ?>
                                <div class="date-picker-month">
                                    <h4 class="month-title"><?php echo esc_html( $month_year ); ?></h4>
                                    <div class="month-dates">
                                        <?php
                                        // Get first day of the month
                                        $first_day = mktime( 0, 0, 0, $month, 1, $year );
                                        
                                        // Get number of days in the month
                                        $num_days = date( 't', $first_day );
                                        
                                        // Get day of week for the first day (0 = Sunday, 6 = Saturday)
                                        $day_of_week = date( 'w', $first_day );
                                        
                                        // Calendar header
                                        ?>
                                        <table class="month-calendar">
                                            <thead>
                                                <tr>
                                                    <th><?php esc_html_e( 'S', 'aqualuxe' ); ?></th>
                                                    <th><?php esc_html_e( 'M', 'aqualuxe' ); ?></th>
                                                    <th><?php esc_html_e( 'T', 'aqualuxe' ); ?></th>
                                                    <th><?php esc_html_e( 'W', 'aqualuxe' ); ?></th>
                                                    <th><?php esc_html_e( 'T', 'aqualuxe' ); ?></th>
                                                    <th><?php esc_html_e( 'F', 'aqualuxe' ); ?></th>
                                                    <th><?php esc_html_e( 'S', 'aqualuxe' ); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                // Start calendar
                                                $day_count = 1;
                                                $calendar_rows = ceil( ( $num_days + $day_of_week ) / 7 );
                                                
                                                for ( $i = 0; $i < $calendar_rows; $i++ ) {
                                                    echo '<tr>';
                                                    
                                                    for ( $j = 0; $j < 7; $j++ ) {
                                                        if ( ( $i === 0 && $j < $day_of_week ) || ( $day_count > $num_days ) ) {
                                                            // Empty cell
                                                            echo '<td class="empty"></td>';
                                                        } else {
                                                            // Date cell
                                                            $date = sprintf( '%04d-%02d-%02d', $year, $month, $day_count );
                                                            $today = date( 'Y-m-d' );
                                                            $is_today = ( $date === $today );
                                                            $is_past = ( $date < $today );
                                                            $is_available = in_array( $date, $dates );
                                                            $is_selected = ( $date === $this->date );
                                                            
                                                            $cell_classes = [];
                                                            if ( $is_today ) {
                                                                $cell_classes[] = 'today';
                                                            }
                                                            if ( $is_past ) {
                                                                $cell_classes[] = 'past';
                                                            }
                                                            if ( $is_available ) {
                                                                $cell_classes[] = 'available';
                                                            } else {
                                                                $cell_classes[] = 'unavailable';
                                                            }
                                                            if ( $is_selected ) {
                                                                $cell_classes[] = 'selected';
                                                            }
                                                            
                                                            echo '<td class="' . esc_attr( implode( ' ', $cell_classes ) ) . '">';
                                                            
                                                            if ( $is_available ) {
                                                                echo '<a href="#" class="date-link" data-date="' . esc_attr( $date ) . '">' . esc_html( $day_count ) . '</a>';
                                                            } else {
                                                                echo '<span class="date-text">' . esc_html( $day_count ) . '</span>';
                                                            }
                                                            
                                                            echo '</td>';
                                                            
                                                            $day_count++;
                                                        }
                                                    }
                                                    
                                                    echo '</tr>';
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="no-availability">
                        <p><?php esc_html_e( 'No available dates for this service.', 'aqualuxe' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        
        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Generate time picker HTML
     *
     * @return string
     */
    public function generate_time_picker() {
        // Get available time slots
        $available_slots = $this->get_available_time_slots();
        
        // Start output buffering
        ob_start();
        
        // Time picker header
        ?>
        <div class="aqualuxe-time-picker">
            <div class="time-picker-header">
                <h3 class="time-picker-title"><?php esc_html_e( 'Select a Time', 'aqualuxe' ); ?></h3>
                <p class="time-picker-date"><?php echo esc_html( date( 'l, F j, Y', strtotime( $this->date ) ) ); ?></p>
            </div>
            <div class="time-picker-content">
                <?php if ( ! empty( $available_slots ) ) : ?>
                    <div class="time-slots">
                        <?php
                        // Group time slots by morning, afternoon, evening
                        $morning_slots = [];
                        $afternoon_slots = [];
                        $evening_slots = [];
                        
                        foreach ( $available_slots as $slot ) {
                            $hour = (int) substr( $slot, 0, 2 );
                            
                            if ( $hour < 12 ) {
                                $morning_slots[] = $slot;
                            } elseif ( $hour < 17 ) {
                                $afternoon_slots[] = $slot;
                            } else {
                                $evening_slots[] = $slot;
                            }
                        }
                        
                        // Display morning slots
                        if ( ! empty( $morning_slots ) ) :
                        ?>
                            <div class="time-slot-group">
                                <h4 class="group-title"><?php esc_html_e( 'Morning', 'aqualuxe' ); ?></h4>
                                <div class="group-slots">
                                    <?php foreach ( $morning_slots as $slot ) : ?>
                                        <div class="time-slot">
                                            <input type="radio" name="booking_time" id="time-<?php echo esc_attr( $slot ); ?>" value="<?php echo esc_attr( $slot ); ?>">
                                            <label for="time-<?php echo esc_attr( $slot ); ?>"><?php echo esc_html( $this->format_time( $slot ) ); ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display afternoon slots
                        if ( ! empty( $afternoon_slots ) ) :
                        ?>
                            <div class="time-slot-group">
                                <h4 class="group-title"><?php esc_html_e( 'Afternoon', 'aqualuxe' ); ?></h4>
                                <div class="group-slots">
                                    <?php foreach ( $afternoon_slots as $slot ) : ?>
                                        <div class="time-slot">
                                            <input type="radio" name="booking_time" id="time-<?php echo esc_attr( $slot ); ?>" value="<?php echo esc_attr( $slot ); ?>">
                                            <label for="time-<?php echo esc_attr( $slot ); ?>"><?php echo esc_html( $this->format_time( $slot ) ); ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        // Display evening slots
                        if ( ! empty( $evening_slots ) ) :
                        ?>
                            <div class="time-slot-group">
                                <h4 class="group-title"><?php esc_html_e( 'Evening', 'aqualuxe' ); ?></h4>
                                <div class="group-slots">
                                    <?php foreach ( $evening_slots as $slot ) : ?>
                                        <div class="time-slot">
                                            <input type="radio" name="booking_time" id="time-<?php echo esc_attr( $slot ); ?>" value="<?php echo esc_attr( $slot ); ?>">
                                            <label for="time-<?php echo esc_attr( $slot ); ?>"><?php echo esc_html( $this->format_time( $slot ) ); ?></label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php else : ?>
                    <div class="no-availability">
                        <p><?php esc_html_e( 'No available time slots for this date.', 'aqualuxe' ); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        
        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Set date
     *
     * @param string $date Date in Y-m-d format.
     * @return void
     */
    public function set_date( $date ) {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return string
     */
    public function get_date() {
        return $this->date;
    }

    /**
     * Get service
     *
     * @return Service
     */
    public function get_service() {
        return $this->service;
    }

    /**
     * Get service ID
     *
     * @return int
     */
    public function get_service_id() {
        return $this->service_id;
    }

    /**
     * Get formatted date
     *
     * @return string
     */
    public function get_formatted_date() {
        return date( 'l, F j, Y', strtotime( $this->date ) );
    }

    /**
     * Get day of week
     *
     * @return string
     */
    public function get_day_of_week() {
        return date( 'l', strtotime( $this->date ) );
    }

    /**
     * Get month
     *
     * @return string
     */
    public function get_month() {
        return date( 'F', strtotime( $this->date ) );
    }

    /**
     * Get day
     *
     * @return string
     */
    public function get_day() {
        return date( 'j', strtotime( $this->date ) );
    }

    /**
     * Get year
     *
     * @return string
     */
    public function get_year() {
        return date( 'Y', strtotime( $this->date ) );
    }
}