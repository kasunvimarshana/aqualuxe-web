# AquaLuxe WordPress Theme Shortcodes and Template Tags

This document provides a comprehensive list of shortcodes and template tags available in the AquaLuxe WordPress theme.

## Shortcodes

Shortcodes allow you to add dynamic content to your pages and posts. Simply insert the shortcode into the content editor.

### General Shortcodes

#### [aqualuxe_button]

Displays a styled button.

**Parameters:**
- `text` - The button text (required)
- `url` - The button URL (required)
- `style` - Button style: primary, secondary, outline (default: primary)
- `size` - Button size: small, medium, large (default: medium)
- `icon` - Icon name (optional)
- `target` - Link target: _self, _blank (default: _self)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_button text="Shop Now" url="/shop" style="primary" size="large" icon="arrow-right" target="_blank"]
```

#### [aqualuxe_icon]

Displays an icon.

**Parameters:**
- `name` - The icon name (required)
- `size` - Icon size: small, medium, large (default: medium)
- `color` - Icon color: primary, secondary, dark, light (default: primary)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_icon name="fish" size="large" color="primary"]
```

#### [aqualuxe_divider]

Displays a divider.

**Parameters:**
- `style` - Divider style: line, dots, wave (default: line)
- `color` - Divider color: primary, secondary, dark, light (default: primary)
- `width` - Divider width in percentage (default: 100)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_divider style="wave" color="primary" width="50"]
```

### Content Shortcodes

#### [aqualuxe_featured_products]

Displays featured products.

**Parameters:**
- `count` - Number of products to display (default: 4)
- `columns` - Number of columns (default: 4)
- `category` - Product category slug (optional)
- `orderby` - Order by: date, price, rating, popularity (default: date)
- `order` - Order: ASC, DESC (default: DESC)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_featured_products count="8" columns="4" category="filtration" orderby="popularity"]
```

#### [aqualuxe_testimonials]

Displays testimonials.

**Parameters:**
- `count` - Number of testimonials to display (default: 3)
- `category` - Testimonial category slug (optional)
- `style` - Display style: grid, slider (default: slider)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_testimonials count="5" style="slider"]
```

#### [aqualuxe_team_members]

Displays team members.

**Parameters:**
- `count` - Number of team members to display (default: 4)
- `columns` - Number of columns (default: 4)
- `department` - Department slug (optional)
- `orderby` - Order by: name, date, menu_order (default: menu_order)
- `order` - Order: ASC, DESC (default: ASC)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_team_members count="8" columns="4" department="management"]
```

#### [aqualuxe_services]

Displays services.

**Parameters:**
- `count` - Number of services to display (default: 3)
- `columns` - Number of columns (default: 3)
- `category` - Service category slug (optional)
- `style` - Display style: grid, list (default: grid)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_services count="6" columns="3" category="maintenance" style="grid"]
```

### Layout Shortcodes

#### [aqualuxe_row]

Creates a row for column layout. Must be used with [aqualuxe_column].

**Parameters:**
- `gap` - Gap between columns: none, small, medium, large (default: medium)
- `align` - Vertical alignment: top, center, bottom (default: top)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_row gap="medium" align="center"]
    [aqualuxe_column width="1/2"]Content for first column[/aqualuxe_column]
    [aqualuxe_column width="1/2"]Content for second column[/aqualuxe_column]
[/aqualuxe_row]
```

#### [aqualuxe_column]

Creates a column within a row.

**Parameters:**
- `width` - Column width: 1/1, 1/2, 1/3, 2/3, 1/4, 3/4 (default: 1/1)
- `align` - Text alignment: left, center, right (default: left)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_column width="1/3" align="center"]Column content[/aqualuxe_column]
```

#### [aqualuxe_container]

Creates a container with specified width.

**Parameters:**
- `width` - Container width: small, medium, large, full (default: medium)
- `padding` - Container padding: none, small, medium, large (default: medium)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_container width="small" padding="large"]Container content[/aqualuxe_container]
```

### Interactive Shortcodes

#### [aqualuxe_accordion]

Creates an accordion.

**Parameters:**
- `style` - Accordion style: default, boxed, minimal (default: default)
- `open` - Index of initially open item (default: 0)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_accordion style="boxed" open="1"]
    [aqualuxe_accordion_item title="Accordion Title 1"]Content for accordion item 1[/aqualuxe_accordion_item]
    [aqualuxe_accordion_item title="Accordion Title 2"]Content for accordion item 2[/aqualuxe_accordion_item]
    [aqualuxe_accordion_item title="Accordion Title 3"]Content for accordion item 3[/aqualuxe_accordion_item]
[/aqualuxe_accordion]
```

#### [aqualuxe_tabs]

Creates a tabbed interface.

**Parameters:**
- `style` - Tab style: default, pills, minimal (default: default)
- `active` - Index of initially active tab (default: 0)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_tabs style="pills" active="1"]
    [aqualuxe_tab title="Tab 1"]Content for tab 1[/aqualuxe_tab]
    [aqualuxe_tab title="Tab 2"]Content for tab 2[/aqualuxe_tab]
    [aqualuxe_tab title="Tab 3"]Content for tab 3[/aqualuxe_tab]
[/aqualuxe_tabs]
```

#### [aqualuxe_modal]

Creates a modal popup.

**Parameters:**
- `id` - Unique ID for the modal (required)
- `title` - Modal title (optional)
- `button_text` - Text for the button that opens the modal (required)
- `button_style` - Button style: primary, secondary, outline (default: primary)
- `size` - Modal size: small, medium, large (default: medium)
- `class` - Additional CSS classes (optional)

**Example:**
```
[aqualuxe_modal id="contact-modal" title="Contact Us" button_text="Get in Touch" button_style="primary" size="medium"]
    Modal content goes here. You can include shortcodes, text, and HTML.
[/aqualuxe_modal]
```

## Template Tags

Template tags are PHP functions that you can use in your theme templates to display dynamic content.

### General Template Tags

#### `aqualuxe_site_logo()`

Displays the site logo.

**Parameters:**
- `$size` - Image size (default: 'medium')
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_site_logo('large', 'my-custom-class'); ?>
```

#### `aqualuxe_social_icons()`

Displays social media icons.

**Parameters:**
- `$networks` - Array of networks to display (default: all configured networks)
- `$style` - Icon style: default, circle, square (default: 'default')
- `$size` - Icon size: small, medium, large (default: 'medium')
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_social_icons(array('facebook', 'twitter', 'instagram'), 'circle', 'large'); ?>
```

#### `aqualuxe_breadcrumbs()`

Displays breadcrumb navigation.

**Parameters:**
- `$separator` - Separator between breadcrumb items (default: '/')
- `$home_text` - Text for home link (default: 'Home')
- `$show_on_home` - Whether to show breadcrumbs on homepage (default: false)
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_breadcrumbs('>', 'Homepage', true); ?>
```

### Content Template Tags

#### `aqualuxe_featured_image()`

Displays the featured image.

**Parameters:**
- `$post_id` - Post ID (default: current post)
- `$size` - Image size (default: 'large')
- `$attr` - Image attributes (default: array())
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_featured_image(get_the_ID(), 'full', array('alt' => 'Featured Image')); ?>
```

#### `aqualuxe_post_meta()`

Displays post meta information.

**Parameters:**
- `$post_id` - Post ID (default: current post)
- `$elements` - Array of elements to display: date, author, categories, comments (default: array('date', 'author'))
- `$date_format` - Date format (default: get_option('date_format'))
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_post_meta(get_the_ID(), array('date', 'author', 'categories')); ?>
```

#### `aqualuxe_excerpt()`

Displays the post excerpt.

**Parameters:**
- `$post_id` - Post ID (default: current post)
- `$length` - Excerpt length in words (default: 55)
- `$more` - Text to append to excerpt (default: '...')
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_excerpt(get_the_ID(), 100, ' [Read More]'); ?>
```

### WooCommerce Template Tags

#### `aqualuxe_product_price()`

Displays the product price.

**Parameters:**
- `$product_id` - Product ID (default: current product)
- `$show_regular` - Whether to show regular price when on sale (default: true)
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_product_price(get_the_ID(), true); ?>
```

#### `aqualuxe_add_to_cart_button()`

Displays the add to cart button.

**Parameters:**
- `$product_id` - Product ID (default: current product)
- `$style` - Button style: default, primary, secondary, outline (default: 'primary')
- `$size` - Button size: small, medium, large (default: 'medium')
- `$quantity` - Default quantity (default: 1)
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_add_to_cart_button(get_the_ID(), 'outline', 'large'); ?>
```

#### `aqualuxe_product_rating()`

Displays the product rating.

**Parameters:**
- `$product_id` - Product ID (default: current product)
- `$show_count` - Whether to show review count (default: true)
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_product_rating(get_the_ID(), false); ?>
```

#### `aqualuxe_wishlist_button()`

Displays the wishlist button.

**Parameters:**
- `$product_id` - Product ID (default: current product)
- `$icon_only` - Whether to show only the icon (default: false)
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_wishlist_button(get_the_ID(), true); ?>
```

### Navigation Template Tags

#### `aqualuxe_pagination()`

Displays pagination for archives.

**Parameters:**
- `$query` - WP_Query object (default: global $wp_query)
- `$prev_text` - Previous page text (default: '&laquo; Previous')
- `$next_text` - Next page text (default: 'Next &raquo;')
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_pagination($custom_query, 'Prev', 'Next'); ?>
```

#### `aqualuxe_post_navigation()`

Displays previous/next post navigation.

**Parameters:**
- `$post_id` - Post ID (default: current post)
- `$prev_text` - Previous post text (default: 'Previous Post')
- `$next_text` - Next post text (default: 'Next Post')
- `$in_same_term` - Whether to limit to same taxonomy term (default: false)
- `$taxonomy` - Taxonomy name if $in_same_term is true (default: 'category')
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_post_navigation(get_the_ID(), 'Previous', 'Next', true, 'category'); ?>
```

### Utility Template Tags

#### `aqualuxe_dark_mode_toggle()`

Displays the dark mode toggle button.

**Parameters:**
- `$show_text` - Whether to show text label (default: false)
- `$light_text` - Text for light mode (default: 'Light')
- `$dark_text` - Text for dark mode (default: 'Dark')
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_dark_mode_toggle(true, 'Light Mode', 'Dark Mode'); ?>
```

#### `aqualuxe_language_switcher()`

Displays the language switcher.

**Parameters:**
- `$show_flags` - Whether to show flags (default: true)
- `$show_names` - Whether to show language names (default: true)
- `$dropdown` - Whether to use dropdown format (default: true)
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_language_switcher(true, true, false); ?>
```

#### `aqualuxe_currency_switcher()`

Displays the currency switcher.

**Parameters:**
- `$show_symbols` - Whether to show currency symbols (default: true)
- `$show_names` - Whether to show currency names (default: false)
- `$dropdown` - Whether to use dropdown format (default: true)
- `$class` - Additional CSS classes (default: '')

**Example:**
```php
<?php aqualuxe_currency_switcher(true, true, false); ?>
```

## Using Shortcodes in Templates

You can use shortcodes in your PHP templates with the `do_shortcode()` function:

```php
<?php echo do_shortcode('[aqualuxe_button text="Shop Now" url="/shop" style="primary" size="large"]'); ?>
```

## Creating Custom Shortcodes and Template Tags

For information on creating custom shortcodes and template tags, please refer to the [DEVELOPER.md](DEVELOPER.md) documentation.