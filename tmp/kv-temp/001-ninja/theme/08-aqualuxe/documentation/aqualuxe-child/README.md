# AquaLuxe Child Theme

This is a child theme for the AquaLuxe WordPress theme. Using a child theme allows you to customize the theme without modifying the parent theme files, ensuring that your customizations won't be lost when the parent theme is updated.

## Installation

1. Make sure the parent AquaLuxe theme is installed in your WordPress themes directory.
2. Upload the `aqualuxe-child` folder to your WordPress themes directory (`wp-content/themes/`).
3. Log in to your WordPress admin panel and navigate to Appearance > Themes.
4. Activate the "AquaLuxe Child" theme.

## Structure

The child theme includes the following files:

- `style.css` - The main stylesheet that includes the theme information and your custom CSS.
- `functions.php` - Contains functions to enqueue the parent and child theme styles and scripts, as well as any custom functionality.
- `js/custom.js` - A JavaScript file for your custom scripts.
- `README.md` - This file, containing information about the child theme.

## Customization

### CSS Customization

To add custom CSS, edit the `style.css` file. Your styles will override the parent theme styles.

Example:

```css
/* Change the primary color */
:root {
    --color-primary: #00a0d2;
    --color-primary-dark: #0073aa;
    --color-primary-light: #33b3db;
}

/* Change the font family */
body {
    font-family: 'Your Font', sans-serif;
}
```

### PHP Customization

To add custom functionality, edit the `functions.php` file. Several example functions are included (commented out) that you can uncomment and modify as needed.

Example:

```php
// Add a new widget area
function aqualuxe_child_widgets_init() {
    register_sidebar( array(
        'name'          => __( 'Custom Sidebar', 'aqualuxe-child' ),
        'id'            => 'custom-sidebar',
        'description'   => __( 'Add widgets here to appear in your custom sidebar.', 'aqualuxe-child' ),
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title">',
        'after_title'   => '</h2>',
    ) );
}
add_action( 'widgets_init', 'aqualuxe_child_widgets_init' );
```

### JavaScript Customization

To add custom JavaScript, edit the `js/custom.js` file. Several example functions are included (commented out) that you can uncomment and modify as needed.

Example:

```javascript
// Add a sticky header on scroll
var header = $('.site-header');
var headerOffset = header.offset().top;

$(window).scroll(function() {
    if ($(this).scrollTop() > headerOffset) {
        header.addClass('sticky');
        $('body').css('padding-top', header.outerHeight());
    } else {
        header.removeClass('sticky');
        $('body').css('padding-top', 0);
    }
});
```

### Template Customization

To override a parent theme template file, create a file with the same name in your child theme directory. WordPress will use the child theme version instead of the parent theme version.

Example:

To override the `header.php` file, create a file named `header.php` in your child theme root directory and add your custom code.

## Adding New Files

You can add new files to your child theme as needed. For example:

- Create a `template-parts` directory for custom template parts.
- Create a `page-templates` directory for custom page templates.
- Create an `inc` directory for custom PHP include files.

## Support

If you need help with the child theme, please contact the AquaLuxe theme support team:

- Support forum: [support.aqualuxetheme.com](https://support.aqualuxetheme.com)
- Email support: support@aqualuxetheme.com