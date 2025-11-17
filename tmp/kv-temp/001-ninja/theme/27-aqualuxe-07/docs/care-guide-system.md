# AquaLuxe Fish Care Guide System Documentation

## Overview

The Fish Care Guide System is a comprehensive feature designed for the AquaLuxe WordPress theme that allows you to create, manage, and display detailed care guides for various fish species. This system helps customers learn about proper fish care, maintenance requirements, and compatibility with other species.

## Features

- **Custom Post Type**: Dedicated "Care Guide" post type for organizing fish care information
- **Taxonomies**: Categorize guides by fish species, care categories, and difficulty levels
- **Custom Fields**: Store specific fish data like tank size, water temperature, pH levels, etc.
- **Interactive Elements**: Tabbed content, expandable sections, tooltips, and more
- **Search Functionality**: Allow users to search for specific care guides
- **PDF Generation**: Let users download care guides as PDF files
- **Shortcodes**: Display care guides in various layouts on any page
- **Widget**: Show recent or featured care guides in widget areas
- **Responsive Design**: Optimized for all devices and screen sizes

## Getting Started

### Creating a New Care Guide

1. In your WordPress admin dashboard, navigate to **Care Guides > Add New**
2. Enter a title for your care guide (e.g., "Betta Fish Care Guide")
3. Add a featured image that shows the fish species
4. In the main content editor, provide an overview of the fish species
5. Use the "Care Guide Details" meta box to enter specific information:
   - Minimum tank size
   - Water temperature range
   - pH level range
   - Average lifespan
   - Diet requirements
   - Maintenance level
   - Compatible fish species
   - Incompatible fish species
6. Use the tabbed content meta boxes to add detailed information:
   - **Care Instructions**: Detailed care routines and maintenance
   - **Feeding**: Specific feeding requirements and schedules
   - **Tank Setup**: Ideal tank configuration, decorations, plants, etc.
   - **Common Issues**: Health problems, diseases, and troubleshooting
7. Assign appropriate taxonomies:
   - **Fish Species**: The specific species or breed (e.g., Betta, Guppy, Angelfish)
   - **Care Category**: The type of care guide (e.g., Freshwater, Saltwater, Breeding)
   - **Difficulty Level**: How challenging the fish is to keep (e.g., Beginner, Intermediate, Expert)
8. Optionally check "Mark as featured guide" to highlight important guides
9. Click "Publish" to make the care guide available on your site

### Managing Care Guides

- **All Care Guides**: View and manage all your care guides from the "Care Guides" menu
- **Quick Edit**: Make quick changes to titles, taxonomies, and featured status
- **Bulk Actions**: Apply changes to multiple care guides at once
- **Filtering**: Filter guides by species, category, or difficulty level
- **Sorting**: Sort guides by title, date, or featured status

### Displaying Care Guides

#### Archive Page

The care guide archive page automatically displays all your care guides in a grid layout with filtering options. Users can filter guides by:
- Fish species
- Care category
- Difficulty level

#### Single Care Guide Page

Each care guide has its own dedicated page with:
- Featured image
- Fish specifications
- Compatibility information
- Tabbed content sections
- Related care guides

#### Shortcodes

Display care guides anywhere on your site using these shortcodes:

1. **Display Care Guides Grid**:
   ```
   [care_guide limit="3" columns="3" species="betta,guppy" category="freshwater" difficulty="beginner"]
   ```
   
   Parameters:
   - `limit`: Number of guides to display (default: 3)
   - `columns`: Number of columns in the grid (default: 3)
   - `species`: Comma-separated list of species slugs
   - `category`: Comma-separated list of category slugs
   - `difficulty`: Comma-separated list of difficulty level slugs
   - `orderby`: Sort by 'date', 'title', or 'rand' (default: 'date')
   - `order`: 'ASC' or 'DESC' (default: 'DESC')

2. **Display Care Guide Search**:
   ```
   [care_guide_search placeholder="Search for fish care guides..."]
   ```
   
   Parameters:
   - `placeholder`: Custom placeholder text for the search input

#### Widget

Add the "AquaLuxe Care Guides" widget to any widget area to display:
- Recent care guides
- Featured care guides
- Guides filtered by species

Widget options include:
- Title
- Number of guides to show
- Order by (date, title, random, comment count)
- Order (ascending or descending)
- Filter by species
- Show/hide thumbnails
- Show/hide post dates
- Show only featured guides
- Show/hide "View All" link

## Advanced Features

### PDF Generation

Users can download any care guide as a PDF file by clicking the "Save as PDF" button on the care guide page. The PDF includes:
- All guide content and images
- Fish specifications
- Compatibility information
- Care instructions
- Proper formatting for printing

### Interactive Elements

The care guide system includes several interactive elements:

1. **Tabbed Content**: Organize information into easy-to-navigate tabs
2. **Expandable Sections**: Collapsible content sections for better organization
3. **Tooltips**: Hover-activated explanations for technical terms
4. **Print Functionality**: Optimized layout for printing
5. **Search**: Real-time AJAX search for finding guides quickly

### Customization

Developers can customize the care guide system through:

1. **Template Overrides**: Copy template files to your child theme to customize layouts
2. **CSS Customization**: Add custom CSS to modify the appearance
3. **Filter Hooks**: Use WordPress filters to modify functionality
4. **Action Hooks**: Add custom content or functionality at specific points

## Troubleshooting

### Common Issues

1. **Care guides not displaying**: Ensure the post type is registered correctly and permalinks are refreshed
2. **Taxonomies not working**: Check that taxonomies are registered and assigned properly
3. **PDF generation fails**: Verify that wkhtmltopdf is installed on your server
4. **Images not showing in PDFs**: Check image paths and permissions

### Getting Help

If you encounter any issues with the Care Guide System, please:
1. Check this documentation for solutions
2. Visit the AquaLuxe theme support forum
3. Contact our support team at support@aqualuxetheme.com

## Changelog

### Version 1.0.0
- Initial release of the Fish Care Guide System
- Custom post type and taxonomies
- Interactive elements and tabbed content
- PDF generation functionality
- Shortcodes and widget support