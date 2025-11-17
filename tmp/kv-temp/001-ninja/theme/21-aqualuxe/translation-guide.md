# AquaLuxe Theme Translation Guide

## Introduction

AquaLuxe is fully translation-ready, allowing you to use the theme in any language. This guide will walk you through the process of translating the AquaLuxe theme to your preferred language.

## Table of Contents

1. [Understanding Theme Translation](#understanding-theme-translation)
2. [Translation Methods](#translation-methods)
3. [Using Loco Translate Plugin](#using-loco-translate-plugin)
4. [Manual Translation with Poedit](#manual-translation-with-poedit)
5. [Creating RTL Styles](#creating-rtl-styles)
6. [Translation Best Practices](#translation-best-practices)
7. [Troubleshooting](#troubleshooting)

## Understanding Theme Translation

WordPress themes use a system called "internationalization" (often abbreviated as "i18n") to make text strings translatable. The AquaLuxe theme has been properly internationalized, meaning all text strings are wrapped in special translation functions.

### Key Concepts

- **Text Domain**: A unique identifier for the theme's translations. AquaLuxe uses the text domain `aqualuxe`.
- **Translation Files**:
  - `.pot` (Portable Object Template) - The template containing all translatable strings
  - `.po` (Portable Object) - Contains the translated strings for a specific language
  - `.mo` (Machine Object) - A compiled binary version of the .po file that WordPress uses

### Translation File Locations

- The main POT template file is located at: `/wp-content/themes/aqualuxe-theme/languages/aqualuxe.pot`
- Your custom translation files should be placed in: `/wp-content/languages/themes/`

## Translation Methods

There are several ways to translate the AquaLuxe theme:

1. **WordPress.org Translation Platform** - If the theme is available on WordPress.org
2. **Loco Translate Plugin** - A user-friendly WordPress plugin for in-dashboard translation
3. **Manual Translation with Poedit** - Using desktop software for more control
4. **Professional Translation Service** - Hiring translators for high-quality results

## Using Loco Translate Plugin

Loco Translate is one of the easiest ways to translate a WordPress theme directly from your dashboard.

### Step 1: Install and Activate Loco Translate

1. Go to Plugins > Add New
2. Search for "Loco Translate"
3. Click "Install Now" and then "Activate"

### Step 2: Create a New Translation

1. Go to Loco Translate > Themes
2. Find "AquaLuxe" in the list and click on it
3. Click "New language" to start a new translation
4. Select your language from the dropdown menu
5. Choose where to store the translation files:
   - "Theme languages directory" (recommended for child themes)
   - "WordPress languages directory" (recommended for parent themes)
   - "Loco Translate's custom directory"
6. Click "Start translating"

### Step 3: Translate the Strings

1. You'll see a list of all translatable strings in the theme
2. Click on a string you want to translate
3. Enter the translation in the field below
4. Click "Save" to store your translations

### Step 4: Generate MO File

1. After translating strings, click the "Save" button in the top right
2. Loco Translate will automatically generate both .po and .mo files

## Manual Translation with Poedit

For more control over the translation process, you can use Poedit, a desktop application for creating and editing translation files.

### Step 1: Download and Install Poedit

1. Download Poedit from [https://poedit.net/](https://poedit.net/)
2. Install the application on your computer

### Step 2: Create a New Translation

1. Open Poedit
2. Go to File > New From POT/PO File...
3. Navigate to and select the AquaLuxe POT file: `/wp-content/themes/aqualuxe-theme/languages/aqualuxe.pot`
4. Select your language
5. Poedit will create a new .po file for your translations

### Step 3: Translate the Strings

1. You'll see a list of all translatable strings
2. Click on a string you want to translate
3. Enter the translation in the "Translation" field
4. Continue until all strings are translated

### Step 4: Save and Export

1. Go to File > Save
2. Poedit will automatically generate both .po and .mo files
3. Upload these files to your server in the appropriate directory:
   - For parent theme: `/wp-content/languages/themes/aqualuxe-{locale}.po` and `.mo`
   - For child theme: `/wp-content/themes/aqualuxe-child/languages/aqualuxe-{locale}.po` and `.mo`

Replace `{locale}` with your language code (e.g., `fr_FR` for French, `de_DE` for German, etc.)

## Creating RTL Styles

For right-to-left (RTL) languages like Arabic, Hebrew, or Persian, you'll need RTL styles in addition to translations.

### Using the Built-in RTL Support

AquaLuxe includes built-in RTL support. When you set your WordPress site to an RTL language, the theme will automatically load the RTL styles.

To activate RTL mode:

1. Go to Settings > General
2. Set your site language to an RTL language
3. Save changes

### Customizing RTL Styles

If you need to customize the RTL styles:

1. Create a child theme (recommended)
2. Create an `rtl.css` file in your child theme directory
3. Add your custom RTL styles to this file
4. Enqueue the RTL stylesheet in your child theme's `functions.php`:

```php
function aqualuxe_child_enqueue_rtl_styles() {
    if (is_rtl()) {
        wp_enqueue_style('aqualuxe-child-rtl', 
            get_stylesheet_directory_uri() . '/rtl.css',
            array('aqualuxe-rtl'),
            wp_get_theme()->get('Version')
        );
    }
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_rtl_styles', 20);
```

## Translation Best Practices

### 1. Understand Context

- Pay attention to the context of strings
- Look at the theme to see where and how the text is used
- Use the "Context" information provided in the translation tools

### 2. Maintain Consistency

- Use consistent terminology throughout your translation
- Create a glossary of common terms and their translations
- Review your translations for consistency before finalizing

### 3. Consider Space Limitations

- Some languages require more space than English
- Be concise in your translations when possible
- Test your translations in the theme to ensure they fit properly

### 4. Handle Variables Carefully

- Watch for placeholders like `%s`, `%d`, or `{0}` - these are replaced with dynamic content
- Maintain these placeholders in your translations
- Adjust the sentence structure if needed to accommodate the placeholders naturally

### 5. Update Translations Regularly

- When the theme is updated, new strings may be added
- Regularly check for and translate new strings
- Use translation tools that show the percentage of completion

## Troubleshooting

### Translations Not Working

1. **Check Language Settings**
   - Verify your site language is set correctly in Settings > General
   - Make sure the translation files are named correctly with the proper locale

2. **Check File Locations**
   - Ensure translation files are in the correct directory
   - Check file permissions (should be readable)

3. **Clear Caches**
   - Clear any caching plugins
   - Clear browser cache

### Missing Translations

1. **Check for Untranslated Strings**
   - Some strings might be hardcoded or missing translation functions
   - Report these to the theme developer

2. **Update Translation Files**
   - After theme updates, new strings may be added
   - Update your translation files to include these new strings

### RTL Layout Issues

1. **Check Theme RTL Support**
   - Verify the theme's RTL stylesheet is loading
   - Inspect elements with browser tools to identify styling issues

2. **Custom RTL Fixes**
   - Create custom RTL styles in your child theme
   - Target specific elements that need adjustment

## Additional Resources

- [WordPress Polyglots Handbook](https://make.wordpress.org/polyglots/handbook/)
- [Loco Translate Documentation](https://localise.biz/wordpress/plugin)
- [Poedit Documentation](https://poedit.net/docs/)
- [WordPress Language Codes](https://wpastra.com/docs/complete-list-wordpress-locale-codes/)

## Contributing Translations

If you've created a complete translation for AquaLuxe, consider sharing it with the community:

1. Contact the theme developer at translations@aqualuxetheme.com
2. Include your .po and .mo files
3. Provide your name and website (if you'd like credit)
4. The developer may include your translation in future theme updates

## Conclusion

Translating the AquaLuxe theme allows you to provide a better experience for users who speak different languages. Whether you're creating a multilingual site or simply want to use the theme in your native language, these translation methods will help you achieve your goal.

Remember that translation is an ongoing process, especially as the theme receives updates with new features and content. Regularly updating your translations will ensure the best experience for your users.