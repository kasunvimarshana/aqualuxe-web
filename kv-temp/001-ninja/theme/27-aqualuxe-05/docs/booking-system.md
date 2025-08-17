# AquaLuxe Booking System Documentation

## Overview

The AquaLuxe Booking System provides a complete solution for managing consultations and appointments for your ornamental fish business. It includes a frontend booking form for customers and a backend management system for administrators.

## Features

- **Frontend Booking Form**: Allows customers to book consultations
- **Service Selection**: Customers can choose from different consultation services
- **Date & Time Picker**: Interactive calendar for selecting appointment dates and times
- **Real-time Availability**: Shows only available time slots
- **Booking Confirmation**: Instant confirmation with booking details
- **Email Notifications**: Automated emails for customers and administrators
- **Admin Dashboard**: Complete booking management in WordPress admin
- **Booking Status Management**: Track pending, confirmed, completed, and cancelled bookings

## Setup Instructions

### 1. Create a Booking Page

1. In your WordPress admin, go to **Pages > Add New**
2. Enter a title (e.g., "Book a Consultation")
3. Add any introductory content in the editor
4. In the **Page Attributes** panel, select **Booking Page** from the Template dropdown
5. Publish the page

### 2. Configure Services

The booking system comes with default services, but you can customize them by adding a filter to your theme's `functions.php` file:

```php
/**
 * Customize booking services
 */
function custom_booking_services( $services ) {
    // Modify existing services or add new ones
    $services['aquarium-consultation'] = array(
        'name'     => __( 'Aquarium Setup Consultation', 'aqualuxe' ),
        'duration' => __( '60 minutes', 'aqualuxe' ),
        'price'    => '$99.00',
    );
    
    // Add a new service
    $services['custom-service'] = array(
        'name'     => __( 'Custom Service', 'aqualuxe' ),
        'duration' => __( '45 minutes', 'aqualuxe' ),
        'price'    => '$79.00',
    );
    
    return $services;
}
add_filter( 'aqualuxe_booking_services', 'custom_booking_services' );
```

### 3. Customize Email Templates

You can customize the email templates by adding filters to your theme's `functions.php` file:

```php
/**
 * Customize customer confirmation email
 */
function custom_booking_confirmation_email( $message, $booking_id ) {
    // Your custom email message
    return $message;
}
add_filter( 'aqualuxe_booking_confirmation_email', 'custom_booking_confirmation_email', 10, 2 );

/**
 * Customize admin notification email
 */
function custom_booking_admin_notification( $message, $booking_id ) {
    // Your custom email message
    return $message;
}
add_filter( 'aqualuxe_booking_admin_notification', 'custom_booking_admin_notification', 10, 2 );
```

## Managing Bookings

### Viewing Bookings

1. In your WordPress admin, go to **Bookings**
2. You'll see a list of all bookings with customer information, service type, date/time, and status
3. Use the filters at the top to sort by status or date

### Editing a Booking

1. Click on a booking title to edit it
2. You can modify:
   - Customer information
   - Service type
   - Date and time
   - Booking status
   - Add admin notes

### Booking Statuses

- **Pending**: New bookings awaiting confirmation
- **Confirmed**: Bookings that have been confirmed
- **Completed**: Consultations that have been completed
- **Cancelled**: Bookings that have been cancelled

## Customization Options

### Adding Custom Fields

You can add custom fields to the booking form by modifying the `page-booking.php` template and adding the corresponding fields to the booking.php file.

### Styling

The booking system uses Tailwind CSS classes for styling. You can customize the appearance by modifying the `booking.css` file or adding custom CSS to your theme.

### JavaScript Customization

The booking system's JavaScript functionality is contained in `booking.js`. You can customize the behavior by modifying this file.

## Troubleshooting

### Common Issues

1. **Time slots not showing**: Make sure the date picker is working correctly and that there are available slots for the selected date.

2. **Emails not being sent**: Check your WordPress email configuration. You may need to use an SMTP plugin for reliable email delivery.

3. **Form validation errors**: Ensure all required fields are properly marked and that the JavaScript validation is working.

### Support

For additional support or customization, please contact the theme developer.