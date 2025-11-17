# AquaLuxe Theme Dark Mode Documentation

This document provides information on how to use and customize the dark mode functionality in the AquaLuxe WordPress theme.

## Overview

The AquaLuxe theme includes a fully customizable dark mode feature that allows users to switch between light and dark color schemes. The dark mode implementation includes:

- A toggle button that can be positioned in various locations
- Customizer settings for controlling dark mode behavior
- System preference detection for automatic switching
- Persistent user preference storage
- Comprehensive styling for all theme elements

## Customizer Settings

The dark mode settings can be found in the WordPress Customizer under **AquaLuxe Theme Options > Dark Mode**. The following options are available:

### Enable Dark Mode

Toggle to enable or disable the dark mode functionality entirely.

- **Default**: Enabled

### Default Mode

Choose the default color mode when a user first visits the site.

- **Light**: Start in light mode
- **Dark**: Start in dark mode
- **System Preference**: Follow the user's system preference

### Auto Dark Mode

When enabled, the theme will automatically switch to dark mode based on the user's system preferences if they haven't manually set a preference.

- **Default**: Enabled

### Toggle Position

Choose where to display the dark mode toggle button.

- **Top Bar**: In the top bar (default)
- **Header**: In the main header actions area
- **Footer**: In the footer copyright section
- **Floating**: As a floating button on the page

### Dark Mode Primary Color

Choose the primary accent color for dark mode.

- **Default**: #0ea5e9

### Dark Mode Background Color

Choose the background color for dark mode.

- **Default**: #121212

### Dark Mode Text Color

Choose the text color for dark mode.

- **Default**: #e5e5e5

## Implementation Details

### Files

The dark mode functionality is implemented across several files:

1. **inc/dark-mode.php**: Contains the main functionality for dark mode
2. **assets/src/js/dark-mode.js**: JavaScript for handling dark mode toggle and persistence
3. **assets/src/css/utilities/_dark-mode-custom.scss**: Custom dark mode styles
4. **assets/src/js/customizer.js**: Live preview functionality for dark mode customizer settings

### How It Works

1. The dark mode toggle button is added to the theme based on the position selected in the customizer
2. When clicked, the toggle adds or removes the `dark` class from the `<body>` element
3. CSS styles target the `body.dark` selector to apply dark mode styling
4. The user's preference is stored in localStorage for persistence across page loads
5. If auto dark mode is enabled, the theme will check the user's system preference

### CSS Variables

The following CSS variables are available for use in your custom CSS:

```css
:root {
    --dark-mode-primary: #0ea5e9;  /* Primary accent color for dark mode */
    --dark-mode-bg: #121212;       /* Background color for dark mode */
    --dark-mode-text: #e5e5e5;     /* Text color for dark mode */
}
```

## Customizing Dark Mode

### Adding Custom Dark Mode Styles

To add custom dark mode styles, you can use the following pattern in your CSS:

```css
/* Light mode styles */
.my-element {
    background-color: white;
    color: black;
}

/* Dark mode styles */
body.dark .my-element {
    background-color: var(--dark-mode-bg);
    color: var(--dark-mode-text);
}
```

### Programmatically Checking Dark Mode

You can check if dark mode is active in JavaScript:

```javascript
const isDarkMode = document.body.classList.contains('dark');

// Or listen for changes
document.addEventListener('darkModeChanged', function(e) {
    const mode = e.detail; // 'light' or 'dark'
    console.log('Dark mode changed to:', mode);
});
```

### Adding Custom Toggle Positions

To add a custom position for the dark mode toggle, you can use the following hooks:

```php
// Add a custom position option in the customizer
add_filter('aqualuxe_dark_mode_toggle_positions', function($positions) {
    $positions['custom-location'] = __('Custom Location', 'your-textdomain');
    return $positions;
});

// Add the toggle to your custom location
add_action('your_custom_action_hook', function() {
    $toggle_position = get_theme_mod('aqualuxe_dark_mode_toggle_position', 'top-bar');
    
    if ('custom-location' === $toggle_position) {
        echo aqualuxe_dark_mode_toggle();
    }
});
```

## Troubleshooting

### Toggle Not Appearing

1. Check if dark mode is enabled in the customizer
2. Verify the toggle position setting
3. Check if your theme has the necessary action hooks (`aqualuxe_top_bar_right`, `aqualuxe_header_actions`, etc.)

### Dark Mode Not Working

1. Check browser console for JavaScript errors
2. Verify that dark-mode.js is being loaded
3. Check if the CSS variables are being properly set in the head

### Custom Styles Not Applying

1. Make sure you're targeting `body.dark` in your CSS
2. Check the specificity of your selectors
3. Verify that your custom CSS is loading after the theme's CSS

## Further Customization

For advanced customization, you can:

1. Create a child theme and override the dark mode files
2. Use the WordPress hooks system to modify the dark mode behavior
3. Add custom JavaScript to extend the functionality

For more information, please refer to the theme documentation or contact support.