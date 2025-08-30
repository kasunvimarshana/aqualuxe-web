# AquaLuxe Bookings Module

This module adds comprehensive booking functionality to the AquaLuxe WordPress theme with calendar, availability checking, booking management, and payment integration.

## Features

- **Booking System**: Complete booking system with calendar, availability checking, and booking management
- **Payment Integration**: Support for multiple payment methods including Stripe, PayPal, bank transfer, and cash
- **Email Notifications**: Customizable email notifications for booking confirmation, status changes, and payment confirmation
- **Admin Dashboard**: Comprehensive admin dashboard for managing bookings
- **Availability Management**: Flexible availability rules for controlling when items can be booked
- **Pricing Rules**: Dynamic pricing based on dates, days of week, and time
- **Booking Calendar**: Interactive calendar for selecting dates and times
- **User Account Integration**: Integration with user accounts for managing bookings
- **WooCommerce Integration**: Optional integration with WooCommerce for payment processing

## Post Types

### Bookings (`aqualuxe_booking`)

The bookings post type stores individual booking records with the following metadata:

- Bookable item ID
- Start date/time
- End date/time
- Status (pending, confirmed, cancelled, completed, refunded, failed)
- Quantity
- Price
- Customer data (name, email, phone, address, notes)
- Payment data (method, status, transaction ID, amount, currency, date)
- Booking notes

### Bookable Items (`aqualuxe_bookable`)

The bookable items post type represents items that can be booked with the following metadata:

- Booking type (date, date_range, date_time, time)
- Booking duration
- Booking duration unit (minute, hour, day)
- Booking capacity
- Booking buffer
- Booking buffer unit (minute, hour, day)
- Availability type (default, custom)
- Availability rules
- Base price
- Pricing type (fixed, hourly, daily)
- Pricing rules
- Operating hours

## Taxonomies

### Booking Status (`aqualuxe_booking_status`)

Taxonomy for categorizing bookings by status:

- Pending
- Confirmed
- Cancelled
- Completed
- Refunded
- Failed

### Bookable Category (`aqualuxe_bookable_category`)

Taxonomy for categorizing bookable items.

## Classes

### Booking

The `Booking` class handles individual booking records with methods for:

- Creating and updating bookings
- Setting and getting booking data
- Calculating prices
- Managing booking status
- Handling customer data
- Processing payments

### Calendar

The `Calendar` class provides calendar functionality with methods for:

- Rendering booking calendars
- Getting available dates
- Getting available time slots
- Managing calendar settings

### Availability

The `Availability` class handles availability checking with methods for:

- Checking if dates/times are available
- Managing availability rules
- Getting available dates and time slots
- Handling booking capacity

### Payment

The `Payment` class handles payment processing with methods for:

- Processing payments with different methods (Stripe, PayPal, etc.)
- Refunding payments
- Managing payment status

### Email

The `Email` class handles email notifications with methods for:

- Sending admin notifications
- Sending customer notifications
- Sending status change notifications
- Sending payment notifications

## Shortcodes

The module provides the following shortcodes:

- `[aqualuxe_booking_form]`: Displays a booking form for a specific bookable item
- `[aqualuxe_booking_calendar]`: Displays a booking calendar for a specific bookable item
- `[aqualuxe_bookings]`: Displays a list of user bookings
- `[aqualuxe_booking]`: Displays a specific booking

## Templates

The module includes the following templates:

- `booking-form.php`: Booking form template
- `booking-calendar.php`: Booking calendar template
- `booking-confirmation.php`: Booking confirmation template
- `booking-details.php`: Booking details template
- `account/bookings.php`: User bookings template
- `admin/booking-details.php`: Admin booking details template
- `admin/customer-details.php`: Admin customer details template
- `admin/payment-details.php`: Admin payment details template
- `admin/booking-notes.php`: Admin booking notes template
- `admin/bookable-settings.php`: Admin bookable settings template
- `admin/availability-settings.php`: Admin availability settings template
- `admin/pricing-settings.php`: Admin pricing settings template
- `emails/admin-notification.php`: Admin notification email template
- `emails/customer-notification.php`: Customer notification email template
- `emails/status-change.php`: Status change notification email template
- `emails/payment-notification.php`: Payment notification email template

## Settings

The module provides the following settings:

- Booking page
- Confirmation page
- Calendar first day
- Time format
- Date format
- Minimum booking notice
- Maximum booking advance
- Enable payments
- Payment methods
- Require payment
- Admin email notification
- Customer email notification
- Admin email
- Email from name
- Email from address

## Usage

### Adding a Bookable Item

1. Go to Bookings > Bookable Items > Add New
2. Enter a title and description for the bookable item
3. Set the booking settings (type, duration, capacity, etc.)
4. Set the availability settings
5. Set the pricing settings
6. Publish the bookable item

### Adding a Booking Form to a Page

Use the `[aqualuxe_booking_form]` shortcode to add a booking form to a page:

```
[aqualuxe_booking_form id="123"]
```

Parameters:
- `id`: The ID of the bookable item (required)
- `title`: Custom title for the form (optional)
- `button_text`: Custom text for the submit button (optional)

### Adding a Booking Calendar to a Page

Use the `[aqualuxe_booking_calendar]` shortcode to add a booking calendar to a page:

```
[aqualuxe_booking_calendar id="123" months="2"]
```

Parameters:
- `id`: The ID of the bookable item (required)
- `months`: Number of months to display (optional, default: 1)
- `start_date`: Start date for the calendar (optional, default: current date)
- `inline`: Whether to display the calendar inline (optional, default: true)

### Displaying User Bookings

Use the `[aqualuxe_bookings]` shortcode to display a list of user bookings:

```
[aqualuxe_bookings limit="10" status="confirmed,pending"]
```

Parameters:
- `limit`: Number of bookings to display (optional, default: 10)
- `status`: Comma-separated list of booking statuses to display (optional, default: all)

### Displaying a Specific Booking

Use the `[aqualuxe_booking]` shortcode to display a specific booking:

```
[aqualuxe_booking id="456"]
```

Parameters:
- `id`: The ID of the booking (required)

## Hooks and Filters

The module provides the following hooks and filters:

- `aqualuxe_booking_created`: Action triggered when a booking is created
- `aqualuxe_booking_status_changed`: Action triggered when a booking status is changed
- `aqualuxe_booking_payment_processed`: Action triggered when a payment is processed
- `aqualuxe_booking_refunded`: Action triggered when a booking is refunded
- `aqualuxe_booking_form_fields`: Filter for modifying booking form fields
- `aqualuxe_booking_validate_form`: Filter for validating booking form data
- `aqualuxe_booking_price`: Filter for modifying booking price
- `aqualuxe_booking_available_dates`: Filter for modifying available dates
- `aqualuxe_booking_available_time_slots`: Filter for modifying available time slots
- `aqualuxe_booking_email_content`: Filter for modifying email content
- `aqualuxe_booking_payment_methods`: Filter for modifying payment methods

## Integration with WooCommerce

The module can be integrated with WooCommerce for payment processing. When WooCommerce is active, the module will use WooCommerce payment gateways for processing payments.

## Integration with User Accounts

The module integrates with WordPress user accounts and WooCommerce accounts (if active) to allow users to manage their bookings.

## Customization

The module can be customized by:

1. **Theme Templates**: Override module templates by creating a `aqualuxe/bookings` directory in your theme and copying the template files there.

2. **Email Templates**: Override email templates by creating a `aqualuxe/bookings/emails` directory in your theme and copying the email template files there.

3. **CSS Styles**: Override CSS styles by adding custom CSS to your theme.

4. **Hooks and Filters**: Use the provided hooks and filters to modify module behavior.

5. **Settings**: Configure module settings in the WordPress admin panel.