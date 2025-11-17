# Multilingual Module

This module provides basic integration with the [Polylang](https://wordpress.org/plugins/polylang/) plugin to enable multilingual capabilities for the AquaLuxe theme.

## Features

*   Adds a language switcher to the primary navigation menu.
*   Provides a function to register theme strings for translation via Polylang's String Translations feature.

## Setup

1.  **Install and activate the Polylang plugin.**
2.  **Configure Languages:** Go to `Languages > Languages` in the WordPress admin to add the languages you want to support.
3.  **Translate Content:** Translate your posts, pages, and other content using the Polylang interface.
4.  **Translate Strings:** Go to `Languages > Strings translations` to translate any registered theme strings.

## How to Register a String for Translation

To make a theme string (like text from the Customizer) translatable, use the `pll_register_string()` function.

Example in `modules/multilingual/multilingual.php`:

```php
if ( function_exists( 'pll_register_string' ) ) {
    pll_register_string('Copyright Text', 'Your Copyright Text', 'Theme Options');
}
```

To display the translated string in your theme, use the `pll__()` function:

```php
echo pll__( 'Your Copyright Text' );
```
