<?php
/**
 * Calendar Template
 *
 * @package AquaLuxe
 * @subpackage Modules\Bookings
 * @since 1.0.0
 */

// Get attributes
$service_id = isset( $atts['service_id'] ) ? absint( $atts['service_id'] ) : 0;
$month = isset( $atts['month'] ) ? absint( $atts['month'] ) : date( 'n' );
$year = isset( $atts['year'] ) ? absint( $atts['year'] ) : date( 'Y' );
$class = isset( $atts['class'] ) ? sanitize_text_field( $atts['class'] ) : '';

// Get service
$service = null;

if ( $service_id ) {
    $service = aqualuxe_get_service( $service_id );
    
    // Check if service exists
    if ( ! $service->get_id() ) {
        $service = null;
    }
}

// Get settings
$settings = AquaLuxe\Modules\Bookings\Settings::get_instance();
$booking_page_url = $settings->get_booking_page_url();

// Get first day of the month
$first_day = mktime( 0, 0, 0, $month, 1, $year );

// Get number of days in the month
$num_days = date( 't', $first_day );

// Get day of week for the first day (0 = Sunday, 6 = Saturday)
$day_of_week = date( 'w', $first_day );

// Get month and year names
$month_name = date( 'F', $first_day );

// Get previous and next month/year
$prev_month = $month == 1 ? 12 : $month - 1;
$prev_year = $month == 1 ? $year - 1 : $year;
$next_month = $month == 12 ? 1 : $month + 1;
$next_year = $month == 12 ? $year + 1 : $year;

// Get available dates for the month
$available_dates = [];

if ( $service ) {
    for ( $day = 1; $day <= $num_days; $day++ ) {
        $date = sprintf( '%04d-%02d-%02d', $year, $month, $day );
        
        if ( $service->is_available_on_date( $date ) ) {
            $available_dates[] = $date;
        }
    }
}
?>

<div class="aqualuxe-calendar <?php echo esc_attr( $class ); ?>">
    <div class="calendar-header">
        <div class="calendar-nav">
            <a href="#" class="prev-month" data-month="<?php echo esc_attr( $prev_month ); ?>" data-year="<?php echo esc_attr( $prev_year ); ?>">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
            </a>
            <h3 class="calendar-title"><?php echo esc_html( $month_name . ' ' . $year ); ?></h3>
            <a href="#" class="next-month" data-month="<?php echo esc_attr( $next_month ); ?>" data-year="<?php echo esc_attr( $next_year ); ?>">
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
                        $date = sprintf( '%04d-%02d-%02d', $year, $month, $day_count );
                        $today = date( 'Y-m-d' );
                        $is_today = ( $date === $today );
                        $is_past = ( $date < $today );
                        $is_available = in_array( $date, $available_dates );
                        
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
                        
                        if ( $is_available && $booking_page_url && ! $is_past ) {
                            echo '<a href="' . esc_url( add_query_arg( [ 'service_id' => $service_id, 'date' => $date ], $booking_page_url ) ) . '" class="day-link">';
                            echo '<span class="day-number">' . esc_html( $day_count ) . '</span>';
                            
                            // Get available slots count
                            if ( $service ) {
                                $availability = new AquaLuxe\Modules\Bookings\Availability( $service_id, $date );
                                $available_slots = $availability->get_available_time_slots();
                                $slots_count = count( $available_slots );
                                
                                if ( $slots_count > 0 ) {
                                    echo '<span class="available-slots">' . sprintf( _n( '%d slot', '%d slots', $slots_count, 'aqualuxe' ), $slots_count ) . '</span>';
                                }
                            }
                            
                            echo '</a>';
                        } else {
                            echo '<span class="day-number">' . esc_html( $day_count ) . '</span>';
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
    
    <?php if ( $service ) : ?>
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

<style>
    .aqualuxe-calendar {
        max-width: 100%;
        margin-bottom: 30px;
    }
    
    .aqualuxe-calendar .calendar-header {
        margin-bottom: 15px;
    }
    
    .aqualuxe-calendar .calendar-nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    
    .aqualuxe-calendar .calendar-title {
        margin: 0;
        font-size: 1.2em;
        text-align: center;
    }
    
    .aqualuxe-calendar .prev-month,
    .aqualuxe-calendar .next-month {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background-color: #f5f5f5;
        color: #333;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }
    
    .aqualuxe-calendar .prev-month:hover,
    .aqualuxe-calendar .next-month:hover {
        background-color: #e0e0e0;
    }
    
    .aqualuxe-calendar .calendar-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .aqualuxe-calendar .calendar-table th {
        padding: 10px;
        text-align: center;
        font-weight: bold;
        border-bottom: 1px solid #eee;
    }
    
    .aqualuxe-calendar .calendar-table td {
        padding: 0;
        text-align: center;
        border: 1px solid #eee;
        height: 60px;
        width: 14.28%;
        position: relative;
    }
    
    .aqualuxe-calendar .calendar-table td.empty {
        background-color: #f9f9f9;
    }
    
    .aqualuxe-calendar .calendar-table td.today {
        background-color: #f0f8ff;
    }
    
    .aqualuxe-calendar .calendar-table td.past {
        opacity: 0.5;
    }
    
    .aqualuxe-calendar .calendar-table td.available {
        background-color: #f0fff0;
    }
    
    .aqualuxe-calendar .calendar-table td.unavailable {
        background-color: #fff0f0;
    }
    
    .aqualuxe-calendar .calendar-table td .day-link {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        text-decoration: none;
        color: #333;
        padding: 5px;
    }
    
    .aqualuxe-calendar .calendar-table td .day-number {
        font-size: 1.1em;
        font-weight: bold;
    }
    
    .aqualuxe-calendar .calendar-table td .available-slots {
        font-size: 0.8em;
        color: #0073aa;
    }
    
    .aqualuxe-calendar .calendar-legend {
        display: flex;
        justify-content: center;
        margin-top: 15px;
    }
    
    .aqualuxe-calendar .legend-item {
        display: flex;
        align-items: center;
        margin: 0 10px;
    }
    
    .aqualuxe-calendar .legend-color {
        display: inline-block;
        width: 15px;
        height: 15px;
        margin-right: 5px;
        border: 1px solid #ddd;
    }
    
    .aqualuxe-calendar .legend-color.available {
        background-color: #f0fff0;
    }
    
    .aqualuxe-calendar .legend-color.unavailable {
        background-color: #fff0f0;
    }
    
    .aqualuxe-calendar .legend-color.today {
        background-color: #f0f8ff;
    }
    
    .aqualuxe-calendar .legend-text {
        font-size: 0.9em;
    }
</style>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Month navigation
        $('.aqualuxe-calendar .prev-month, .aqualuxe-calendar .next-month').on('click', function(e) {
            e.preventDefault();
            
            var month = $(this).data('month');
            var year = $(this).data('year');
            var serviceId = <?php echo $service_id ? $service_id : 0; ?>;
            
            // Reload calendar
            $.ajax({
                url: aqualuxeBookings.ajaxUrl,
                type: 'POST',
                data: {
                    action: 'aqualuxe_get_calendar',
                    service_id: serviceId,
                    month: month,
                    year: year,
                    nonce: aqualuxeBookings.nonce
                },
                success: function(response) {
                    if (response.success) {
                        $('.aqualuxe-calendar').replaceWith(response.data.html);
                    }
                }
            });
        });
    });
</script>