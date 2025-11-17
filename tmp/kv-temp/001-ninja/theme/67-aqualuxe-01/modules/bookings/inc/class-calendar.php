<?php
/**
 * Calendar Class
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

namespace AquaLuxe\Modules\Bookings;

/**
 * Calendar Class
 * 
 * This class handles calendar-related functionality.
 */
class Calendar {
    /**
     * Service ID
     *
     * @var int
     */
    private $service_id;

    /**
     * Month
     *
     * @var int
     */
    private $month;

    /**
     * Year
     *
     * @var int
     */
    private $year;

    /**
     * Service
     *
     * @var Service
     */
    private $service;

    /**
     * Constructor
     *
     * @param int $service_id Service ID.
     * @param int $month Month.
     * @param int $year Year.
     */
    public function __construct( $service_id = 0, $month = 0, $year = 0 ) {
        $this->service_id = $service_id;
        $this->month = $month ? $month : date( 'n' );
        $this->year = $year ? $year : date( 'Y' );
        
        if ( $this->service_id ) {
            $this->service = new Service( $this->service_id );
        }
    }

    /**
     * Generate calendar HTML
     *
     * @return string
     */
    public function generate() {
        // Get first day of the month
        $first_day = mktime( 0, 0, 0, $this->month, 1, $this->year );
        
        // Get number of days in the month
        $num_days = date( 't', $first_day );
        
        // Get day of week for the first day (0 = Sunday, 6 = Saturday)
        $day_of_week = date( 'w', $first_day );
        
        // Get month and year names
        $month_name = date( 'F', $first_day );
        
        // Start output buffering
        ob_start();
        
        // Calendar header
        ?>
        <div class="aqualuxe-calendar">
            <div class="calendar-header">
                <div class="calendar-nav">
                    <a href="#" class="prev-month" data-month="<?php echo esc_attr( $this->get_prev_month() ); ?>" data-year="<?php echo esc_attr( $this->get_prev_year() ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
                    </a>
                    <h3 class="calendar-title"><?php echo esc_html( $month_name . ' ' . $this->year ); ?></h3>
                    <a href="#" class="next-month" data-month="<?php echo esc_attr( $this->get_next_month() ); ?>" data-year="<?php echo esc_attr( $this->get_next_year() ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </a>
                </div>
            </div>
            <table class="calendar-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e( 'Sun', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Mon', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Tue', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Wed', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Thu', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Fri', 'aqualuxe' ); ?></th>
                        <th><?php esc_html_e( 'Sat', 'aqualuxe' ); ?></th>
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
                                $date = sprintf( '%04d-%02d-%02d', $this->year, $this->month, $day_count );
                                $today = date( 'Y-m-d' );
                                $is_today = ( $date === $today );
                                $is_past = ( $date < $today );
                                $is_available = $this->is_date_available( $date );
                                
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
                                
                                echo '<td class="' . esc_attr( implode( ' ', $cell_classes ) ) . '" data-date="' . esc_attr( $date ) . '">';
                                echo '<span class="day-number">' . esc_html( $day_count ) . '</span>';
                                
                                if ( $this->service_id && $is_available ) {
                                    $available_slots = $this->get_available_slots_count( $date );
                                    echo '<span class="available-slots">' . sprintf( _n( '%d slot', '%d slots', $available_slots, 'aqualuxe' ), $available_slots ) . '</span>';
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
            <?php if ( $this->service_id ) : ?>
            <div class="calendar-legend">
                <div class="legend-item">
                    <span class="legend-color available"></span>
                    <span class="legend-text"><?php esc_html_e( 'Available', 'aqualuxe' ); ?></span>
                </div>
                <div class="legend-item">
                    <span class="legend-color unavailable"></span>
                    <span class="legend-text"><?php esc_html_e( 'Unavailable', 'aqualuxe' ); ?></span>
                </div>
                <div class="legend-item">
                    <span class="legend-color today"></span>
                    <span class="legend-text"><?php esc_html_e( 'Today', 'aqualuxe' ); ?></span>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php
        
        // Return buffered output
        return ob_get_clean();
    }

    /**
     * Check if date is available
     *
     * @param string $date Date in Y-m-d format.
     * @return bool
     */
    private function is_date_available( $date ) {
        // Check if date is in the past
        $today = date( 'Y-m-d' );
        if ( $date < $today ) {
            return false;
        }
        
        // If no service is specified, all future dates are available
        if ( ! $this->service_id ) {
            return true;
        }
        
        // Check if service is available on this date
        return $this->service->is_available_on_date( $date );
    }

    /**
     * Get available slots count
     *
     * @param string $date Date in Y-m-d format.
     * @return int
     */
    private function get_available_slots_count( $date ) {
        if ( ! $this->service_id ) {
            return 0;
        }
        
        $available_slots = $this->service->get_available_time_slots( $date );
        return count( $available_slots );
    }

    /**
     * Get previous month
     *
     * @return int
     */
    private function get_prev_month() {
        if ( $this->month == 1 ) {
            return 12;
        } else {
            return $this->month - 1;
        }
    }

    /**
     * Get previous year
     *
     * @return int
     */
    private function get_prev_year() {
        if ( $this->month == 1 ) {
            return $this->year - 1;
        } else {
            return $this->year;
        }
    }

    /**
     * Get next month
     *
     * @return int
     */
    private function get_next_month() {
        if ( $this->month == 12 ) {
            return 1;
        } else {
            return $this->month + 1;
        }
    }

    /**
     * Get next year
     *
     * @return int
     */
    private function get_next_year() {
        if ( $this->month == 12 ) {
            return $this->year + 1;
        } else {
            return $this->year;
        }
    }

    /**
     * Get bookings for a specific date
     *
     * @param string $date Date in Y-m-d format.
     * @return array
     */
    public function get_bookings_for_date( $date ) {
        $args = [
            'post_type'      => 'aqualuxe_booking',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'   => '_date',
                    'value' => $date,
                ],
            ],
            'tax_query'      => [
                [
                    'taxonomy' => 'aqualuxe_booking_status',
                    'field'    => 'slug',
                    'terms'    => [ 'pending', 'confirmed' ],
                ],
            ],
        ];
        
        // Add service filter if specified
        if ( $this->service_id ) {
            $args['meta_query'][] = [
                'key'   => '_service_id',
                'value' => $this->service_id,
            ];
        }
        
        $bookings_query = new \WP_Query( $args );
        $bookings = [];
        
        if ( $bookings_query->have_posts() ) {
            while ( $bookings_query->have_posts() ) {
                $bookings_query->the_post();
                $booking_id = get_the_ID();
                
                $booking = new Booking( $booking_id );
                $bookings[] = $booking;
            }
        }
        
        wp_reset_postdata();
        return $bookings;
    }

    /**
     * Get bookings for a specific month
     *
     * @return array
     */
    public function get_bookings_for_month() {
        $start_date = sprintf( '%04d-%02d-01', $this->year, $this->month );
        $end_date = sprintf( '%04d-%02d-%02d', $this->year, $this->month, date( 't', mktime( 0, 0, 0, $this->month, 1, $this->year ) ) );
        
        $args = [
            'post_type'      => 'aqualuxe_booking',
            'posts_per_page' => -1,
            'meta_query'     => [
                [
                    'key'     => '_date',
                    'value'   => [ $start_date, $end_date ],
                    'compare' => 'BETWEEN',
                    'type'    => 'DATE',
                ],
            ],
            'tax_query'      => [
                [
                    'taxonomy' => 'aqualuxe_booking_status',
                    'field'    => 'slug',
                    'terms'    => [ 'pending', 'confirmed' ],
                ],
            ],
        ];
        
        // Add service filter if specified
        if ( $this->service_id ) {
            $args['meta_query'][] = [
                'key'   => '_service_id',
                'value' => $this->service_id,
            ];
        }
        
        $bookings_query = new \WP_Query( $args );
        $bookings = [];
        
        if ( $bookings_query->have_posts() ) {
            while ( $bookings_query->have_posts() ) {
                $bookings_query->the_post();
                $booking_id = get_the_ID();
                
                $booking = new Booking( $booking_id );
                $bookings[] = $booking;
            }
        }
        
        wp_reset_postdata();
        return $bookings;
    }

    /**
     * Get bookings grouped by date
     *
     * @return array
     */
    public function get_bookings_by_date() {
        $bookings = $this->get_bookings_for_month();
        $bookings_by_date = [];
        
        foreach ( $bookings as $booking ) {
            $date = $booking->get_date();
            
            if ( ! isset( $bookings_by_date[ $date ] ) ) {
                $bookings_by_date[ $date ] = [];
            }
            
            $bookings_by_date[ $date ][] = $booking;
        }
        
        return $bookings_by_date;
    }

    /**
     * Get available dates for the month
     *
     * @return array
     */
    public function get_available_dates() {
        $available_dates = [];
        $days_in_month = date( 't', mktime( 0, 0, 0, $this->month, 1, $this->year ) );
        
        for ( $day = 1; $day <= $days_in_month; $day++ ) {
            $date = sprintf( '%04d-%02d-%02d', $this->year, $this->month, $day );
            
            if ( $this->is_date_available( $date ) ) {
                $available_dates[] = $date;
            }
        }
        
        return $available_dates;
    }

    /**
     * Get unavailable dates for the month
     *
     * @return array
     */
    public function get_unavailable_dates() {
        $unavailable_dates = [];
        $days_in_month = date( 't', mktime( 0, 0, 0, $this->month, 1, $this->year ) );
        
        for ( $day = 1; $day <= $days_in_month; $day++ ) {
            $date = sprintf( '%04d-%02d-%02d', $this->year, $this->month, $day );
            
            if ( ! $this->is_date_available( $date ) ) {
                $unavailable_dates[] = $date;
            }
        }
        
        return $unavailable_dates;
    }

    /**
     * Get month name
     *
     * @return string
     */
    public function get_month_name() {
        return date( 'F', mktime( 0, 0, 0, $this->month, 1, $this->year ) );
    }

    /**
     * Get month and year
     *
     * @return string
     */
    public function get_month_year() {
        return date( 'F Y', mktime( 0, 0, 0, $this->month, 1, $this->year ) );
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
     * Set service ID
     *
     * @param int $service_id Service ID.
     * @return void
     */
    public function set_service_id( $service_id ) {
        $this->service_id = $service_id;
        $this->service = new Service( $this->service_id );
    }

    /**
     * Set month
     *
     * @param int $month Month.
     * @return void
     */
    public function set_month( $month ) {
        $this->month = $month;
    }

    /**
     * Set year
     *
     * @param int $year Year.
     * @return void
     */
    public function set_year( $year ) {
        $this->year = $year;
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
     * Get month
     *
     * @return int
     */
    public function get_month() {
        return $this->month;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function get_year() {
        return $this->year;
    }
}