# AquaLuxe Trade-In System Documentation

## Overview

The AquaLuxe Trade-In System provides a complete solution for managing trade-ins of fish, equipment, aquariums, and accessories. It includes a frontend request form for customers, a catalog of available trade-in items, and a backend management system for administrators.

## Features

- **Trade-In Item Management**: Create and manage trade-in items with detailed information
- **Trade-In Request System**: Allow customers to submit trade-in requests
- **Value Calculation**: Set different values for store credit vs. cash
- **Item Categories**: Organize trade-in items by categories (Fish, Equipment, etc.)
- **Item Status Tracking**: Track items as Available, Pending, Traded, or Rejected
- **Request Status Tracking**: Track requests as New, Reviewing, Approved, Rejected, or Completed
- **Customer Notifications**: Automated emails for trade-in request status updates
- **Admin Dashboard**: Complete trade-in management in WordPress admin

## Setup Instructions

### 1. Creating Trade-In Items

1. In your WordPress admin, go to **Trade-Ins > Add New**
2. Enter a title and description for the trade-in item
3. Set the item details:
   - Condition
   - Age
   - Brand
   - Model
   - Specifications
   - Original Owner status
4. Set the trade-in values:
   - Trade-In Value (base value)
   - Store Credit Value
   - Cash Value
5. Select the category and status
6. Add a featured image and gallery images
7. Publish the trade-in item

### 2. Managing Trade-In Requests

#### Viewing Requests

1. In your WordPress admin, go to **Trade-Ins > Trade-In Requests**
2. You'll see a list of all trade-in requests with customer information, item details, and status
3. Click on a request to view and manage it

#### Processing a Request

1. Review the request details
2. Update the status:
   - **New**: Request has been submitted but not reviewed
   - **Reviewing**: Request is being evaluated
   - **Approved**: Request has been approved and customer can bring in the item
   - **Rejected**: Request has been rejected
   - **Completed**: Trade-in has been completed
3. Set an estimated value if approving the request
4. Add admin notes as needed
5. Use the "Notify Customer" button to send an email notification

### 3. Trade-In Request Form

The trade-in request form is available on the "Trade-In Request" page. Customers can use this form to:

1. Provide item information (name, type, condition, age, etc.)
2. Select their preferred value type (store credit or cash)
3. Provide their contact information
4. Submit the request for review

### 4. Trade-In Catalog

The trade-in catalog displays all available trade-in items. Customers can:

1. Browse items by category
2. Sort items by various criteria
3. View detailed information about each item
4. Request specific items

## Customization Options

### Adding Custom Fields

You can add custom fields to the trade-in system by modifying the `trade-in.php` file and the corresponding template files.

### Styling

The trade-in system uses Tailwind CSS classes for styling. You can customize the appearance by modifying the `trade-in.css` file or adding custom CSS to your theme.

### JavaScript Customization

The trade-in system's JavaScript functionality is contained in `trade-in.js`. You can customize the behavior by modifying this file.

## Frontend Display

### Trade-In Archive Page

The archive page displays all trade-in items in a grid layout with:
- Featured image
- Title
- Condition
- Category
- Value
- Status
- "View Details" button

Visitors can filter items by:
- Category
- Sort by newest, oldest, price low to high, or price high to low

### Single Trade-In Page

The single trade-in page displays:
- Item details (condition, age, brand, model, etc.)
- Specifications
- Trade-in values (base value, store credit, cash)
- Image gallery
- Related items
- "Request This Item" button (for available items)

### Trade-In Request Page

The trade-in request page includes:
- Form for submitting trade-in requests
- Dynamic fields based on item type
- Validation for required fields
- Success/error messaging

## Email Notifications

The system sends various email notifications:

1. **Request Confirmation**: Sent to the customer when a request is submitted
2. **Request Reviewing**: Sent to the customer when their request status changes to "Reviewing"
3. **Request Approved**: Sent to the customer when their request is approved
4. **Request Rejected**: Sent to the customer when their request is rejected
5. **Request Completed**: Sent to the customer when their trade-in is completed
6. **Admin Notification**: Sent to the admin when a new request is submitted

## Troubleshooting

### Common Issues

1. **Request form not submitting**: Check that the AJAX URL and nonce are correctly set in the JavaScript file.

2. **Email notifications not being sent**: Check your WordPress email configuration. You may need to use an SMTP plugin for reliable email delivery.

3. **Images not displaying**: Verify that the featured image and gallery images are correctly set and that the image sizes are appropriate.

### Support

For additional support or customization, please contact the theme developer.