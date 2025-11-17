# AquaLuxe Multilingual Module

This module adds multilingual support to the AquaLuxe WordPress theme with language switcher and RTL support.

## Features

- Language switcher with multiple display styles (dropdown, list, buttons)
- RTL (Right-to-Left) language support
- Language switcher widget
- Language switcher shortcode
- Customizer options for language settings
- Support for flags and language names
- Cookie-based language preference storage
- Integration with WordPress admin bar

## Usage

### Language Switcher

The language switcher can be displayed in several ways:

1. **Header Integration**: The language switcher is automatically added to the header extras section.

2. **Widget**: Use the "AquaLuxe Language Switcher" widget in any widget area.

3. **Shortcode**: Use the `[aqualuxe_language_switcher]` shortcode in your content.

   Shortcode attributes:
   - `style`: dropdown (default), list, buttons
   - `show_flags`: true (default), false
   - `show_names`: true (default), false

   Example:
   ```
   [aqualuxe_language_switcher style="buttons" show_flags="true" show_names="false"]
   ```

4. **Template Function**: Use the template function in your theme files.

   ```php
   <?php
   if (function_exists('aqualuxe_language_switcher')) {
       aqualuxe_language_switcher('dropdown', true, true);
   }
   ?>
   ```

### RTL Support

RTL support is automatically enabled for languages that are marked as RTL in the language settings. The module includes:

- RTL stylesheets for the theme
- RTL body class
- RTL direction attribute
- RTL Tailwind CSS configuration

### Customizer Options

The module adds a "Language Settings" section to the WordPress Customizer with the following options:

- Default Language
- Switcher Style
- Show Flags
- Show Names
- Add to Primary Menu

## Adding New Languages

To add new languages, use the Customizer or update the language settings directly:

```php
$languages = [
    'en' => [
        'name' => 'English',
        'flag' => 'gb',
        'locale' => 'en_US',
        'rtl' => false,
    ],
    'fr' => [
        'name' => 'Français',
        'flag' => 'fr',
        'locale' => 'fr_FR',
        'rtl' => false,
    ],
    'ar' => [
        'name' => 'العربية',
        'flag' => 'sa',
        'locale' => 'ar',
        'rtl' => true,
    ],
];

update_option('aqualuxe_module_multilingual_languages', $languages);
```

## Translation Files

For each language, you need to create the corresponding WordPress translation files:

1. Create a `.po` file for each language in the `languages` directory
2. Compile the `.po` files to `.mo` files
3. Name the files according to the locale (e.g., `fr_FR.mo`)

## Hooks and Filters

The module provides several hooks and filters for customization:

- `aqualuxe_language_switcher_styles`: Filter the available language switcher styles
- `aqualuxe_language_switcher_output`: Filter the language switcher HTML output
- `aqualuxe_language_url`: Filter the URL for a specific language
- `aqualuxe_rtl_body_class`: Filter the RTL body class
- `aqualuxe_languages`: Filter the available languages