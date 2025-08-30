# AquaLuxe Fish Compatibility Checker Documentation

## Overview

The Fish Compatibility Checker is a comprehensive tool designed for the AquaLuxe WordPress theme that helps aquarium owners determine if their fish will coexist peacefully in the same tank. This tool analyzes fish compatibility based on temperament, size, water parameter requirements, and known compatibility issues to provide personalized recommendations.

## Features

- **Interactive Fish Selection**: Search and select fish from a comprehensive database
- **Compatibility Matrix**: Visual representation of compatibility between selected fish
- **Space Analysis**: Tank space requirements calculation based on fish size and quantity
- **Water Parameter Analysis**: Check for overlapping water parameter requirements
- **Personalized Recommendations**: Get specific recommendations based on compatibility issues
- **PDF Export**: Save and export results as PDF files
- **Print Functionality**: Print compatibility results directly
- **Responsive Design**: Works on all devices and screen sizes
- **Multiple Tank Types**: Support for freshwater and saltwater aquariums

## Getting Started

### Adding the Compatibility Checker to a Page

1. Create a new page in WordPress where you want to display the compatibility checker
2. Add the `[compatibility_checker]` shortcode to the page content
3. Publish the page

### Shortcode Options

The `[compatibility_checker]` shortcode accepts the following parameters:

```
[compatibility_checker title="Custom Title" description="Custom description text"]
```

- **title**: Custom title for the compatibility checker (default: "Fish Compatibility Checker")
- **description**: Custom description text (default: "Check if your fish are compatible with each other and suitable for your aquarium.")

Example with custom parameters:
```
[compatibility_checker title="Aquarium Compatibility Tool" description="Ensure your fish can live together harmoniously"]
```

### Using the Compatibility Checker

1. **Enter Tank Information**:
   - Input your tank size in gallons
   - Select your tank type (Freshwater or Saltwater)

2. **Select Fish**:
   - Use the search box to find fish by name
   - Click on fish from the search results to add them to your selection
   - Remove fish by clicking the "×" button on each selected fish card
   - Select at least 2 fish to enable the compatibility check

3. **Check Compatibility**:
   - Click the "Check Compatibility" button
   - View the compatibility matrix showing how each fish interacts with others
   - Review space requirements and water parameter compatibility
   - Read personalized recommendations for any compatibility issues

4. **Save or Print Results**:
   - Click "Save as PDF" to download the results as a PDF file
   - Click "Print Results" to print the compatibility analysis directly

## Compatibility Analysis

The compatibility checker analyzes several factors to determine if fish can coexist peacefully:

### 1. Direct Compatibility

The system contains a database of known compatibility relationships between fish species. These relationships are categorized as:

- **Compatible**: Fish that can generally live together without issues
- **Caution**: Fish that may have some compatibility issues but could potentially coexist with careful monitoring
- **Incompatible**: Fish that should not be kept together due to aggression, predation, or other serious issues

### 2. Temperament Compatibility

Fish temperaments are categorized as:

- **Peaceful**: Non-aggressive fish that rarely show territorial behavior
- **Semi-aggressive**: Fish that may show some aggression or territorial behavior
- **Aggressive**: Fish that are known to be highly territorial or predatory

The system checks for problematic temperament combinations, such as aggressive fish with peaceful fish, especially when there's a significant size difference.

### 3. Size Compatibility

Fish size is an important factor in compatibility. The system checks for:

- Size differences that could lead to predation (larger fish eating smaller fish)
- Appropriate tank size for the combined fish selection
- Space requirements based on fish size and minimum group size

### 4. Water Parameter Compatibility

Fish require specific water conditions to thrive. The system checks if the selected fish have overlapping requirements for:

- pH level
- Temperature
- Water hardness

If there's no overlap in these parameters, the fish cannot share the same tank.

### 5. Schooling Requirements

Some fish are schooling species that need to be kept in groups. The system provides recommendations for minimum group sizes for schooling fish.

## Compatibility Matrix

The compatibility matrix is a visual representation of how each selected fish interacts with every other fish in your selection:

- **Green Check (✓)**: Fish are compatible
- **Yellow Exclamation (!)**: Fish may have some compatibility issues (caution)
- **Red X (✗)**: Fish are incompatible

Hovering over caution or incompatible indicators shows specific issues, such as "Temperament mismatch" or "Predator/prey risk."

## Tank Space Analysis

The space analysis calculates:

- Space required for each fish based on its size and minimum group size
- Total space required for all selected fish
- Tank utilization percentage based on your tank size
- Status indicator (adequate, full, or overstocked)

## Water Parameter Compatibility

The water parameter analysis shows:

- Compatible pH range for all selected fish
- Compatible temperature range for all selected fish
- Compatible hardness range for all selected fish
- Status indicator for each parameter (compatible or incompatible)

## Recommendations System

The system provides personalized recommendations based on the compatibility analysis:

### Priority Levels

Recommendations are categorized by priority:

- **High Priority**: Critical issues that must be addressed (incompatible fish, overstocked tank)
- **Medium Priority**: Important issues that should be considered (tank approaching full capacity, schooling requirements)
- **Low Priority**: Minor issues or suggestions for optimal care

### Recommendation Types

The system provides several types of recommendations:

- **Incompatible Fish**: Suggestions to remove one of the incompatible fish
- **Overstocked Tank**: Recommendations for a larger tank or reducing fish quantity
- **Water Parameter Issues**: Suggestions for selecting fish with compatible water requirements
- **Schooling Requirements**: Recommendations for maintaining proper group sizes for schooling fish

## Widget

The AquaLuxe Compatibility Checker Widget allows you to add a link to the compatibility checker page in any widget area:

1. Go to Appearance > Widgets in your WordPress admin
2. Drag the "AquaLuxe Compatibility Checker" widget to any widget area
3. Configure the widget settings:
   - **Title**: Widget title (default: "Fish Compatibility Checker")
   - **Description**: Short description text
   - **Checker Page**: Select the page where you added the compatibility checker shortcode
   - **Button Text**: Text for the link button

## Technical Information

### Fish Database

The compatibility checker includes a comprehensive database of fish with the following information for each species:

- **Basic Information**: Name, scientific name, common names, image
- **Physical Characteristics**: Size, minimum group size
- **Habitat**: Tank type, water level preference
- **Behavior**: Temperament, diet
- **Water Parameters**: pH range, temperature range, hardness range
- **Compatibility**: Known incompatible and caution species

### PDF Generation

The PDF export feature:

- Creates a professionally formatted PDF document
- Includes all compatibility analysis results
- Shows the compatibility matrix, space analysis, and water parameter compatibility
- Lists all recommendations
- Includes tank information and selected fish details
- Automatically names the file with the current date

### Browser Compatibility

- Works with all modern browsers (Chrome, Firefox, Safari, Edge)
- Requires JavaScript to be enabled
- Requires PDF generation capability for the export feature

### Mobile Responsiveness

- Fully responsive design adapts to all screen sizes
- Optimized for touch input on mobile devices
- Tables scroll horizontally on small screens

## Troubleshooting

### Common Issues

1. **Fish database not loading**: Check your internet connection and refresh the page
2. **Search not finding fish**: Try using scientific names or alternative common names
3. **PDF generation failing**: Ensure your server has wkhtmltopdf installed and properly configured
4. **Compatibility matrix not displaying**: Check that you have selected at least 2 fish

### Getting Help

If you encounter any issues with the Fish Compatibility Checker, please:
1. Check this documentation for solutions
2. Visit the AquaLuxe theme support forum
3. Contact our support team at support@aqualuxetheme.com

## Extending the Fish Database

For theme developers who want to extend the fish database:

1. Locate the `aqualuxe_get_fish_compatibility_data()` function in `includes/compatibility-checker-functions.php`
2. Add new fish entries to the array following the existing format
3. Ensure all required fields are included for each fish
4. Update compatibility relationships as needed

Example fish entry format:

```php
'fish_id' => array(
    'id' => 'fish_id',
    'name' => 'Fish Name',
    'scientific_name' => 'Scientific Name',
    'common_names' => array('Common Name 1', 'Common Name 2'),
    'image' => get_template_directory_uri() . '/assets/images/fish/fish_image.jpg',
    'size' => 3.0, // inches
    'min_group' => 1,
    'tank_type' => 'freshwater', // or 'saltwater' or 'both'
    'temperament' => 'peaceful', // or 'semi-aggressive' or 'aggressive'
    'water_level' => 'middle', // or 'top', 'bottom', or 'all'
    'diet' => 'omnivore', // or 'carnivore' or 'herbivore'
    'parameters' => array(
        'ph' => array('min' => 6.5, 'max' => 7.5),
        'temperature' => array('min' => 72, 'max' => 78),
        'hardness' => array('min' => 5, 'max' => 12)
    ),
    'compatibility' => array(
        'incompatible' => array('other_fish_id1', 'other_fish_id2'),
        'caution' => array('other_fish_id3', 'other_fish_id4')
    )
),
```

## Changelog

### Version 1.0.0
- Initial release of the Fish Compatibility Checker
- Support for freshwater and saltwater fish
- Compatibility matrix visualization
- Space requirements calculation
- Water parameter compatibility analysis
- Recommendations system
- PDF export functionality
- Print functionality
- Responsive design