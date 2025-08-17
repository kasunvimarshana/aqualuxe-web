# AquaLuxe Auction System Documentation

## Overview

The AquaLuxe Auction System provides a complete solution for managing auctions of rare and exotic fish specimens. It includes a frontend bidding interface for customers and a backend management system for administrators.

## Features

- **Custom Auction Post Type**: Dedicated post type for auctions with custom fields and taxonomies
- **Bidding System**: Real-time bidding with minimum bid increments
- **Countdown Timer**: Dynamic countdown to auction end
- **Auction Categories**: Organize auctions by categories (Rare Species, Limited Edition, etc.)
- **Auction Statuses**: Track auctions as Upcoming, Active, Ended, or Cancelled
- **Featured Auctions**: Highlight special auctions
- **Bid History**: Complete record of all bids placed
- **Winner Management**: Automatic winner determination and notification
- **Payment Tracking**: Track payment status for completed auctions
- **Admin Dashboard**: Complete auction management in WordPress admin

## Setup Instructions

### 1. Creating an Auction

1. In your WordPress admin, go to **Auctions > Add New**
2. Enter a title and description for the auction item
3. Set the auction details:
   - Start Date & Time
   - End Date & Time
   - Starting Price
   - Reserve Price (optional)
   - Minimum Bid Increment
4. Select the auction status (Upcoming, Active, etc.)
5. Assign categories as needed
6. Set as Featured if desired
7. Add a featured image
8. Publish the auction

### 2. Managing Auctions

#### Viewing Auctions

1. In your WordPress admin, go to **Auctions**
2. You'll see a list of all auctions with their status, current bid, and dates
3. Use the filters at the top to sort by status or category

#### Editing an Auction

1. Click on an auction title to edit it
2. You can modify:
   - Auction details (dates, prices, etc.)
   - Status
   - Featured setting
   - View bid history
   - Set or view winner information

#### Auction Statuses

- **Upcoming**: Auctions scheduled for the future
- **Active**: Currently running auctions
- **Ended**: Completed auctions
- **Cancelled**: Auctions that have been cancelled

### 3. Automatic Status Updates

The system includes a scheduled task that automatically:
- Changes auctions from "Upcoming" to "Active" when the start date is reached
- Changes auctions from "Active" to "Ended" when the end date is reached
- Determines the winner when an auction ends

### 4. Winner Management

When an auction ends:
1. The highest bidder is automatically determined as the winner
2. The winner is notified by email
3. The auction status is updated to "Ended"
4. Admin can track payment status (Pending, Paid, Failed)
5. Admin can manually notify the winner if needed

## Customization Options

### Adding Custom Fields

You can add custom fields to the auction system by modifying the `auction.php` file and the corresponding template files.

### Styling

The auction system uses Tailwind CSS classes for styling. You can customize the appearance by modifying the `auction.css` file or adding custom CSS to your theme.

### JavaScript Customization

The auction system's JavaScript functionality is contained in `auction.js`. You can customize the behavior by modifying this file.

## Frontend Display

### Auction Archive Page

The archive page displays all auctions in a grid layout with:
- Featured image
- Title
- Current bid
- Status
- End date (for active auctions)
- Bid count
- "Bid Now" or "View Details" button

Visitors can filter auctions by:
- Category
- Status

### Single Auction Page

The single auction page displays:
- Auction details (start/end dates, starting price, etc.)
- Current bid
- Countdown timer (for active auctions)
- Bid form (for active auctions)
- Bid history
- Winner information (for ended auctions)

## Bidding System

### Placing Bids

1. Users can place bids on active auctions
2. Bids must be at least the minimum bid amount (current bid + increment)
3. Registered users can bid directly
4. Guest users must provide name and email

### Bid Validation

The system validates bids to ensure:
- The auction is active
- The bid is high enough
- The auction hasn't ended

### Bid Notifications

- Bidders receive confirmation when their bid is placed
- Admin receives notification of new bids
- Winners receive notification when they win an auction

## Troubleshooting

### Common Issues

1. **Auctions not changing status automatically**: Check that the WordPress cron system is working properly.

2. **Bid form not showing**: Verify that the auction status is set to "Active" and the end date is in the future.

3. **Emails not being sent**: Check your WordPress email configuration. You may need to use an SMTP plugin for reliable email delivery.

### Support

For additional support or customization, please contact the theme developer.