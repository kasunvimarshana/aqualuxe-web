# AquaLuxe Water Parameter Calculator Documentation

## Overview

The Water Parameter Calculator is a comprehensive tool designed for the AquaLuxe WordPress theme that helps aquarium owners calculate, analyze, and track their water parameters. This tool provides personalized recommendations based on tank type and fish species to ensure optimal water conditions for fish health.

## Features

- **Interactive Calculator**: Input water parameters and receive instant analysis
- **Personalized Recommendations**: Get specific recommendations based on tank type and fish species
- **Parameter History**: Track changes in water parameters over time
- **Export Functionality**: Save and export results as CSV files
- **Responsive Design**: Works on all devices and screen sizes
- **Local Storage**: Saves history directly in the browser for privacy and convenience
- **Multiple Tank Types**: Support for freshwater, saltwater, and brackish water aquariums
- **Fish-Specific Parameters**: Ideal ranges tailored to different fish species and tank setups

## Getting Started

### Adding the Calculator to a Page

1. Create a new page in WordPress where you want to display the calculator
2. Add the `[water_calculator]` shortcode to the page content
3. Publish the page

### Shortcode Options

The `[water_calculator]` shortcode accepts the following parameters:

```
[water_calculator title="Custom Title" description="Custom description text" show_history="yes|no"]
```

- **title**: Custom title for the calculator (default: "Water Parameter Calculator")
- **description**: Custom description text (default: "Calculate and analyze your aquarium water parameters to ensure optimal conditions for your fish.")
- **show_history**: Whether to show the parameter history section (default: "yes")

Example with custom parameters:
```
[water_calculator title="Aquarium Water Analysis Tool" description="Test your aquarium water and get expert recommendations" show_history="no"]
```

### Using the Calculator

1. **Select Tank Information**:
   - Choose your tank type (Freshwater, Saltwater, or Brackish)
   - Select your fish type or tank setup (e.g., Tropical Community, Cichlid, Reef Tank)
   - Enter your tank size in gallons (optional)

2. **Enter Water Parameters**:
   - Input the results from your water test kit
   - Different parameters will be shown based on your tank type
   - Common parameters include pH, ammonia, nitrite, nitrate, and temperature
   - Specialized parameters appear based on tank type (e.g., calcium and alkalinity for reef tanks)

3. **Calculate Results**:
   - Click the "Calculate Parameters" button
   - View the analysis table showing your values compared to ideal ranges
   - Read personalized recommendations for any parameters outside ideal ranges

4. **Save or Export Results**:
   - Click "Save Results" to store the data in your browser's local storage
   - Click "Export as CSV" to download the results as a spreadsheet file
   - View your parameter history to track changes over time

## Parameter Ranges and Recommendations

The calculator provides ideal parameter ranges for different tank types and fish species:

### Freshwater Tanks

#### Tropical Community
- **pH**: 6.5-7.5 (Ideal: 7.0)
- **Ammonia**: 0 ppm
- **Nitrite**: 0 ppm
- **Nitrate**: 0-20 ppm (Ideal: 5 ppm)
- **KH**: 4-8 dKH (Ideal: 6 dKH)
- **GH**: 5-15 dGH (Ideal: 10 dGH)
- **Temperature**: 75-82°F (Ideal: 78°F)

#### Goldfish
- **pH**: 7.0-8.4 (Ideal: 7.5)
- **Ammonia**: 0 ppm
- **Nitrite**: 0 ppm
- **Nitrate**: 0-40 ppm (Ideal: 20 ppm)
- **KH**: 5-10 dKH (Ideal: 7 dKH)
- **GH**: 10-20 dGH (Ideal: 15 dGH)
- **Temperature**: 65-74°F (Ideal: 70°F)

#### Cichlid
- **pH**: 7.8-8.5 (Ideal: 8.2)
- **Ammonia**: 0 ppm
- **Nitrite**: 0 ppm
- **Nitrate**: 0-20 ppm (Ideal: 10 ppm)
- **KH**: 10-18 dKH (Ideal: 14 dKH)
- **GH**: 12-20 dGH (Ideal: 16 dGH)
- **Temperature**: 74-82°F (Ideal: 78°F)

#### Discus
- **pH**: 6.0-7.0 (Ideal: 6.5)
- **Ammonia**: 0 ppm
- **Nitrite**: 0 ppm
- **Nitrate**: 0-10 ppm (Ideal: 5 ppm)
- **KH**: 2-6 dKH (Ideal: 4 dKH)
- **GH**: 3-8 dGH (Ideal: 5 dGH)
- **Temperature**: 82-86°F (Ideal: 84°F)

#### Planted Tank
- **pH**: 6.0-7.2 (Ideal: 6.8)
- **Ammonia**: 0 ppm
- **Nitrite**: 0 ppm
- **Nitrate**: 5-20 ppm (Ideal: 10 ppm)
- **KH**: 3-8 dKH (Ideal: 5 dKH)
- **GH**: 4-12 dGH (Ideal: 7 dGH)
- **Temperature**: 72-80°F (Ideal: 76°F)

#### Shrimp Tank
- **pH**: 6.4-7.6 (Ideal: 7.0)
- **Ammonia**: 0 ppm
- **Nitrite**: 0 ppm
- **Nitrate**: 0-10 ppm (Ideal: 5 ppm)
- **KH**: 2-6 dKH (Ideal: 4 dKH)
- **GH**: 6-10 dGH (Ideal: 8 dGH)
- **TDS**: 150-250 ppm (Ideal: 200 ppm)
- **Temperature**: 70-78°F (Ideal: 74°F)

### Saltwater Tanks

#### Reef Tank
- **pH**: 8.1-8.4 (Ideal: 8.3)
- **Ammonia**: 0 ppm
- **Nitrite**: 0 ppm
- **Nitrate**: 0-5 ppm (Ideal: 0 ppm)
- **Calcium**: 380-450 ppm (Ideal: 420 ppm)
- **Alkalinity**: 8-12 dKH (Ideal: 10 dKH)
- **Magnesium**: 1250-1350 ppm (Ideal: 1300 ppm)
- **Salinity**: 1.023-1.025 SG (Ideal: 1.024 SG)
- **Temperature**: 76-82°F (Ideal: 78°F)

### Brackish Tanks
- Parameters vary based on fish type, but generally fall between freshwater and saltwater values
- **Salinity**: Usually 1.005-1.015 SG depending on species

## Recommendations System

The calculator provides specific recommendations when parameters fall outside ideal ranges:

### pH Recommendations
- **Too Low**: Suggests using pH buffer products or crushed coral substrate
- **Too High**: Recommends pH down products or natural methods like adding driftwood

### Ammonia/Nitrite Recommendations
- **Too High**: Advises immediate water changes and checking filtration

### Nitrate Recommendations
- **Too High**: Suggests water changes, adding live plants, or improving filtration

### Hardness (KH/GH) Recommendations
- **Too Low**: Recommends appropriate mineral supplements
- **Too High**: Suggests using RO/DI water for water changes

### Temperature Recommendations
- **Too Low**: Advises adjusting heater settings
- **Too High**: Suggests cooling methods or adjusting heater

### Saltwater-Specific Recommendations
- Provides guidance for calcium, alkalinity, magnesium, and salinity issues

## Widget

The AquaLuxe Water Calculator Widget allows you to add a link to the calculator page in any widget area:

1. Go to Appearance > Widgets in your WordPress admin
2. Drag the "AquaLuxe Water Calculator" widget to any widget area
3. Configure the widget settings:
   - **Title**: Widget title (default: "Water Parameter Calculator")
   - **Description**: Short description text
   - **Calculator Page**: Select the page where you added the calculator shortcode
   - **Button Text**: Text for the link button

## Technical Information

### Data Storage

- All parameter history is stored in the browser's localStorage
- No data is sent to the server or stored in the WordPress database
- History is limited to the most recent 20 entries
- Users can clear their history at any time

### Browser Compatibility

- Works with all modern browsers (Chrome, Firefox, Safari, Edge)
- Requires JavaScript to be enabled
- Requires localStorage support (available in all modern browsers)

### Mobile Responsiveness

- Fully responsive design adapts to all screen sizes
- Optimized for touch input on mobile devices
- Tables scroll horizontally on small screens

## Troubleshooting

### Common Issues

1. **Calculator not displaying**: Ensure the shortcode is correctly added to the page
2. **History not saving**: Check that your browser has localStorage enabled and isn't in private/incognito mode
3. **Parameters not showing**: Different parameters are shown based on tank type selection
4. **Export not working**: Ensure your browser allows downloads

### Getting Help

If you encounter any issues with the Water Parameter Calculator, please:
1. Check this documentation for solutions
2. Visit the AquaLuxe theme support forum
3. Contact our support team at support@aqualuxetheme.com

## Changelog

### Version 1.0.0
- Initial release of the Water Parameter Calculator
- Support for freshwater, saltwater, and brackish tanks
- Parameter history tracking
- Export functionality
- Responsive design