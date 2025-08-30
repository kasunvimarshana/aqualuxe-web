# AquaLuxe Dark Mode Module

This module adds dark mode support to the AquaLuxe WordPress theme with persistent preference storage.

## Features

- Dark mode toggle with multiple display styles (switch, button, icon)
- Automatic mode based on system preference
- Persistent preference storage using cookies
- Customizable colors for dark mode
- Smooth transition between light and dark modes
- Dark mode widget
- Dark mode shortcode
- Customizer options for dark mode settings

## Usage

### Dark Mode Toggle

The dark mode toggle can be displayed in several ways:

1. **Header Integration**: The dark mode toggle is automatically added to the header extras section.

2. **Widget**: Use the "AquaLuxe Dark Mode Toggle" widget in any widget area.

3. **Shortcode**: Use the `[aqualuxe_dark_mode_toggle]` shortcode in your content.

   Shortcode attributes:
   - `style`: switch (default), button, icon
   - `show_icon`: true (default), false
   - `show_text`: true (default), false

   Example:
   ```
   [aqualuxe_dark_mode_toggle style="button" show_icon="true" show_text="false"]
   ```

4. **Template Function**: Use the template function in your theme files.

   ```php
   <?php
   if (function_exists('aqualuxe_dark_mode_toggle')) {
       aqualuxe_dark_mode_toggle('switch', true, true);
   }
   ?>
   ```

### Dark Mode Modes

The dark mode module supports three modes:

1. **Light Mode**: Forces light mode regardless of system preference.
2. **Dark Mode**: Forces dark mode regardless of system preference.
3. **Auto Mode**: Follows the system preference (uses `prefers-color-scheme` media query).

Users can cycle through these modes by clicking the dark mode toggle.

### Customizer Options

The module adds a "Dark Mode" section to the WordPress Customizer with the following options:

- Default Mode (Light, Dark, Auto)
- Toggle Style (Switch, Button, Icon)
- Show Icon
- Show Text
- Transition Duration
- Custom Colors
  - Background Color
  - Text Color
  - Link Color
  - Accent Color

## CSS Variables

The dark mode module uses CSS variables for styling. You can override these variables in your custom CSS:

```css
:root {
    --dark-bg: #121212;
    --dark-surface: #1e1e1e;
    --dark-surface-2: #2d2d2d;
    --dark-text: #e0e0e0;
    --dark-text-secondary: #a0a0a0;
    --dark-border: #333333;
    --dark-link: #90caf9;
    --dark-accent: #64b5f6;
    --dark-error: #f44336;
    --dark-success: #4caf50;
    --dark-warning: #ff9800;
    --dark-info: #2196f3;
    --dark-transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}
```

## JavaScript Events

The dark mode module triggers the following events:

- `aqualuxe:dark-mode-enabled`: Triggered when dark mode is enabled.
- `aqualuxe:dark-mode-disabled`: Triggered when dark mode is disabled.

Example usage:

```javascript
$(document).on('aqualuxe:dark-mode-enabled', function() {
    console.log('Dark mode enabled');
});

$(document).on('aqualuxe:dark-mode-disabled', function() {
    console.log('Dark mode disabled');
});
```

## Tailwind CSS Integration

The dark mode module adds the `dark` class to the `html` element when dark mode is enabled. This allows you to use Tailwind's dark mode variant:

```html
<div class="bg-white dark:bg-gray-800 text-black dark:text-white">
    This element will have a white background and black text in light mode,
    and a dark gray background and white text in dark mode.
</div>
```

## Hooks and Filters

The module provides several hooks and filters for customization:

- `aqualuxe_dark_mode_toggle_styles`: Filter the available dark mode toggle styles
- `aqualuxe_dark_mode_toggle_output`: Filter the dark mode toggle HTML output
- `aqualuxe_dark_mode_body_class`: Filter the dark mode body class
- `aqualuxe_dark_mode_css_variables`: Filter the dark mode CSS variables