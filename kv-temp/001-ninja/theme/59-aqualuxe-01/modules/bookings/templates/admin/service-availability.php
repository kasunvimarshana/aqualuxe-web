<?php
/**
 * Admin Service Availability Meta Box Template
 *
 * @package AquaLuxe
 * @subpackage Modules/Bookings
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Get availability data
$availability = isset($availability) ? $availability : [
    'rules' => [
        'monday' => [
            'enabled' => true,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ],
        'tuesday' => [
            'enabled' => true,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ],
        'wednesday' => [
            'enabled' => true,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ],
        'thursday' => [
            'enabled' => true,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ],
        'friday' => [
            'enabled' => true,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ],
        'saturday' => [
            'enabled' => false,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ],
        'sunday' => [
            'enabled' => false,
            'start_time' => '09:00',
            'end_time' => '17:00',
        ],
    ],
    'blocked_dates' => [],
];

// Get days of week
$days_of_week = [
    'monday' => __('Monday', 'aqualuxe'),
    'tuesday' => __('Tuesday', 'aqualuxe'),
    'wednesday' => __('Wednesday', 'aqualuxe'),
    'thursday' => __('Thursday', 'aqualuxe'),
    'friday' => __('Friday', 'aqualuxe'),
    'saturday' => __('Saturday', 'aqualuxe'),
    'sunday' => __('Sunday', 'aqualuxe'),
];
?>

<div class="aqualuxe-service-availability">
    <div class="aqualuxe-service-availability__days">
        <?php foreach ($days_of_week as $day_key => $day_label) : ?>
            <div class="aqualuxe-service-availability__day">
                <div class="aqualuxe-service-availability__day-name"><?php echo esc_html($day_label); ?></div>
                
                <div class="aqualuxe-service-availability__day-toggle">
                    <input type="checkbox" id="aqualuxe-service-availability-<?php echo esc_attr($day_key); ?>-enabled" name="aqualuxe_service_availability[<?php echo esc_attr($day_key); ?>][enabled]" value="1" <?php checked(isset($availability['rules'][$day_key]['enabled']) && $availability['rules'][$day_key]['enabled']); ?> data-day="<?php echo esc_attr($day_key); ?>">
                </div>
                
                <div class="aqualuxe-service-availability__day-times">
                    <input type="text" class="aqualuxe-service-availability__time" name="aqualuxe_service_availability[<?php echo esc_attr($day_key); ?>][start_time]" value="<?php echo esc_attr(isset($availability['rules'][$day_key]['start_time']) ? $availability['rules'][$day_key]['start_time'] : '09:00'); ?>" <?php disabled(isset($availability['rules'][$day_key]['enabled']) && !$availability['rules'][$day_key]['enabled']); ?> data-day="<?php echo esc_attr($day_key); ?>">
                    
                    <span class="aqualuxe-service-availability__time-separator"><?php esc_html_e('to', 'aqualuxe'); ?></span>
                    
                    <input type="text" class="aqualuxe-service-availability__time" name="aqualuxe_service_availability[<?php echo esc_attr($day_key); ?>][end_time]" value="<?php echo esc_attr(isset($availability['rules'][$day_key]['end_time']) ? $availability['rules'][$day_key]['end_time'] : '17:00'); ?>" <?php disabled(isset($availability['rules'][$day_key]['enabled']) && !$availability['rules'][$day_key]['enabled']); ?> data-day="<?php echo esc_attr($day_key); ?>">
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <h4 class="aqualuxe-service-availability__blocked-dates-title"><?php esc_html_e('Blocked Dates', 'aqualuxe'); ?></h4>
    
    <div class="aqualuxe-service-availability__blocked-dates">
        <?php
        // Get blocked dates
        $blocked_dates = isset($availability['blocked_dates']) ? $availability['blocked_dates'] : [];
        
        // Sort blocked dates
        sort($blocked_dates);
        
        // Output blocked dates
        foreach ($blocked_dates as $date) {
            echo '<div class="aqualuxe-service-availability__blocked-date">';
            echo '<span class="aqualuxe-service-availability__blocked-date-text">' . esc_html(date_i18n(get_option('date_format'), strtotime($date))) . '</span>';
            echo '<button type="button" class="aqualuxe-service-availability__remove-blocked-date" data-date="' . esc_attr($date) . '">×</button>';
            echo '</div>';
        }
        ?>
    </div>
    
    <button type="button" class="aqualuxe-service-availability__add-blocked-date"><?php esc_html_e('Add Blocked Date', 'aqualuxe'); ?></button>
    
    <div class="aqualuxe-service-availability__blocked-date-picker" style="display: none;"></div>
    
    <input type="hidden" id="aqualuxe-service-blocked-dates" name="aqualuxe_service_blocked_dates" value="<?php echo esc_attr(json_encode($blocked_dates)); ?>">
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        // Initialize time pickers
        $('.aqualuxe-service-availability__time').timepicker({
            timeFormat: 'HH:mm',
            step: 15,
            scrollDefault: '09:00'
        });
        
        // Initialize datepicker
        $('.aqualuxe-service-availability__blocked-date-picker').datepicker({
            dateFormat: 'yy-mm-dd',
            minDate: 0,
            onSelect: function(date) {
                // Add blocked date
                addBlockedDate(date);
                
                // Hide datepicker
                $(this).hide();
            }
        });
        
        // Add blocked date button click
        $('.aqualuxe-service-availability__add-blocked-date').on('click', function() {
            // Show datepicker
            $('.aqualuxe-service-availability__blocked-date-picker').show();
        });
        
        // Remove blocked date button click
        $('.aqualuxe-service-availability__blocked-dates').on('click', '.aqualuxe-service-availability__remove-blocked-date', function() {
            // Get date
            var date = $(this).data('date');
            
            // Remove blocked date
            removeBlockedDate(date);
        });
        
        // Day toggle change
        $('.aqualuxe-service-availability__day-toggle input').on('change', function() {
            // Get day
            var day = $(this).data('day');
            
            // Get enabled state
            var enabled = $(this).prop('checked');
            
            // Enable/disable time fields
            $('.aqualuxe-service-availability__time[data-day="' + day + '"]').prop('disabled', !enabled);
        });
        
        // Add blocked date function
        function addBlockedDate(date) {
            // Get blocked dates
            var blockedDates = getBlockedDates();
            
            // Check if date is already blocked
            if (blockedDates.indexOf(date) !== -1) {
                return;
            }
            
            // Add date to blocked dates
            blockedDates.push(date);
            
            // Sort blocked dates
            blockedDates.sort();
            
            // Update hidden field
            $('#aqualuxe-service-blocked-dates').val(JSON.stringify(blockedDates));
            
            // Add blocked date element
            var blockedDateElement = $('<div class="aqualuxe-service-availability__blocked-date"></div>');
            blockedDateElement.append('<span class="aqualuxe-service-availability__blocked-date-text">' + formatDate(date) + '</span>');
            blockedDateElement.append('<button type="button" class="aqualuxe-service-availability__remove-blocked-date" data-date="' + date + '">×</button>');
            
            // Add blocked date element to container
            $('.aqualuxe-service-availability__blocked-dates').append(blockedDateElement);
        }
        
        // Remove blocked date function
        function removeBlockedDate(date) {
            // Get blocked dates
            var blockedDates = getBlockedDates();
            
            // Remove date from blocked dates
            var index = blockedDates.indexOf(date);
            if (index !== -1) {
                blockedDates.splice(index, 1);
            }
            
            // Update hidden field
            $('#aqualuxe-service-blocked-dates').val(JSON.stringify(blockedDates));
            
            // Remove blocked date element
            $('.aqualuxe-service-availability__remove-blocked-date[data-date="' + date + '"]').closest('.aqualuxe-service-availability__blocked-date').remove();
        }
        
        // Get blocked dates function
        function getBlockedDates() {
            // Get blocked dates from hidden field
            var blockedDatesJson = $('#aqualuxe-service-blocked-dates').val();
            
            // Parse blocked dates
            var blockedDates = [];
            try {
                blockedDates = JSON.parse(blockedDatesJson);
            } catch (e) {
                blockedDates = [];
            }
            
            return blockedDates;
        }
        
        // Format date function
        function formatDate(date) {
            // Create date object
            var dateObj = new Date(date);
            
            // Get month names
            var monthNames = [
                '<?php echo esc_js(__('January', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('February', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('March', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('April', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('May', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('June', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('July', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('August', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('September', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('October', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('November', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('December', 'aqualuxe')); ?>'
            ];
            
            // Get day names
            var dayNames = [
                '<?php echo esc_js(__('Sunday', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('Monday', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('Tuesday', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('Wednesday', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('Thursday', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('Friday', 'aqualuxe')); ?>',
                '<?php echo esc_js(__('Saturday', 'aqualuxe')); ?>'
            ];
            
            // Format date
            return dayNames[dateObj.getDay()] + ', ' + monthNames[dateObj.getMonth()] + ' ' + dateObj.getDate() + ', ' + dateObj.getFullYear();
        }
    });
</script>