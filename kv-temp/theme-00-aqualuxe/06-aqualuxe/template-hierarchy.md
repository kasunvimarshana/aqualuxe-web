# AquaLuxe Theme Template Hierarchy

## Overview
This document outlines the template hierarchy for the AquaLuxe WooCommerce child theme. It explains how WordPress selects templates for different content types and how the theme extends and customizes these templates.

## Template Hierarchy Basics
WordPress uses a specific hierarchy to determine which template file to use for displaying content. The AquaLuxe theme follows this hierarchy while adding its own custom templates for enhanced functionality.

## 1. Homepage Templates

### Static Front Page
When "Front page displays" is set to "A static page":

1. **Custom Front Page Template**: `front-page-{id}.php`
2. **Front Page Template**: `front-page.php`
3. **Page Template**: `page.php`
4. **Singular Template**: `singular.php`
5. **Index Template**: `index.php`

### Blog Posts Index
When "Front page displays" is set to "Your latest posts":

1. **Home Template**: `home.php`
2. **Index Template**: `index.php`

## 2. Page Templates

### Default Page Hierarchy
1. **Custom Page Template**: `page-{slug}.php`
2. **Page ID Template**: `page-{id}.php`
3. **Page Template**: `page.php`
4. **Singular Template**: `singular.php`
5. **Index Template**: `index.php`

### Custom Page Templates
The AquaLuxe theme includes several custom page templates:

- **Full Width Page**: `page-templates/full-width.php`
- **Left Sidebar Page**: `page-templates/left-sidebar.php`
- **Right Sidebar Page**: `page-templates/right-sidebar.php`

### WooCommerce Page Templates
For WooCommerce-specific pages:

- **Shop Page**: `woocommerce/archive-product.php`
- **Cart Page**: `woocommerce/cart/cart.php`
- **Checkout Page**: `woocommerce/checkout/form-checkout.php`
- **Account Page**: `woocommerce/myaccount/dashboard.php`
- **Single Product**: `woocommerce/single-product.php`

## 3. Post Templates

### Single Post Hierarchy
1. **Custom Post Type Template**: `single-{post_type}.php`
2. **Single Post Template**: `single.php`
3. **Singular Template**: `singular.php`
4. **Index Template**: `index.php`

### Post Format Templates
For posts with specific formats:

1. **Format Specific**: `format-{format}.php`
2. **Single Post Template**: `single.php`
3. **Singular Template**: `singular.php`
4. **Index Template**: `index.php`

### Category Templates
For category archives:

1. **Category Slug**: `category-{slug}.php`
2. **Category ID**: `category-{id}.php`
3. **Category Template**: `category.php`
4. **Archive Template**: `archive.php`
5. **Index Template**: `index.php`

### Tag Templates
For tag archives:

1. **Tag Slug**: `tag-{slug}.php`
2. **Tag ID**: `tag-{id}.php`
3. **Tag Template**: `tag.php`
4. **Archive Template**: `archive.php`
5. **Index Template**: `index.php`

### Custom Taxonomy Templates
For custom taxonomies:

1. **Taxonomy and Term**: `taxonomy-{taxonomy}-{term}.php`
2. **Taxonomy**: `taxonomy-{taxonomy}.php`
3. **Taxonomy Template**: `taxonomy.php`
4. **Archive Template**: `archive.php`
5. **Index Template**: `index.php`

## 4. Archive Templates

### Date Archive Hierarchy
1. **Yearly Archive**: `date-{year}.php`
2. **Monthly Archive**: `date-{year}-{month}.php`
3. **Daily Archive**: `date-{year}-{month}-{day}.php`
4. **Date Template**: `date.php`
5. **Archive Template**: `archive.php`
6. **Index Template**: `index.php`

### Author Templates
For author archives:

1. **Author Nickname**: `author-{nickname}.php`
2. **Author ID**: `author-{id}.php`
3. **Author Template**: `author.php`
4. **Archive Template**: `archive.php`
5. **Index Template**: `index.php`

### Custom Post Type Archive
For custom post type archives:

1. **Post Type Archive**: `archive-{post_type}.php`
2. **Archive Template**: `archive.php`
3. **Index Template**: `index.php`

## 5. Search Templates

### Search Results Hierarchy
1. **Search Template**: `search.php`
2. **Index Template**: `index.php`

### Search with No Results
When search returns no results:
1. **Search Template**: `search.php` (handles no results case)
2. **Index Template**: `index.php`

## 6. Error Templates

### 404 Error Hierarchy
1. **404 Template**: `404.php`
2. **Index Template**: `index.php`

### Other Error Templates
- **Maintenance Mode**: `maintenance.php`
- **Offline Mode**: `offline.php`

## 7. WooCommerce Template Hierarchy

### Product Archive Templates
1. **Shop Page**: `woocommerce/archive-product.php`
2. **Product Taxonomy**: `woocommerce/taxonomy-product_cat.php`
3. **Archive Template**: `woocommerce/archive.php`
4. **Index Template**: `index.php`

### Single Product Templates
1. **Product ID**: `woocommerce/single-product-{id}.php`
2. **Product Type**: `woocommerce/single-product-{type}.php`
3. **Single Product**: `woocommerce/single-product.php`
4. **Singular Template**: `singular.php`
5. **Index Template**: `index.php`

### Cart Templates
1. **Cart Page**: `woocommerce/cart/cart.php`
2. **Cart Template**: `woocommerce/cart.php`
3. **Page Template**: `page.php`
4. **Index Template**: `index.php`

### Checkout Templates
1. **Checkout Page**: `woocommerce/checkout/form-checkout.php`
2. **Checkout Template**: `woocommerce/checkout.php`
3. **Page Template**: `page.php`
4. **Index Template**: `index.php`

### Account Templates
1. **Account Dashboard**: `woocommerce/myaccount/dashboard.php`
2. **Account Endpoint**: `woocommerce/myaccount/{endpoint}.php`
3. **Account Template**: `woocommerce/myaccount.php`
4. **Page Template**: `page.php`
5. **Index Template**: `index.php`

## 8. Template Parts

### Header Templates
- **Main Header**: `template-parts/header/header.php`
- **Site Branding**: `template-parts/header/site-branding.php`
- **Site Navigation**: `template-parts/header/site-navigation.php`

### Footer Templates
- **Main Footer**: `template-parts/footer/footer.php`
- **Footer Widgets**: `template-parts/footer/footer-widgets.php`
- **Site Info**: `template-parts/footer/site-info.php`

### Content Templates
- **Page Content**: `template-parts/content/content-page.php`
- **Single Post**: `template-parts/content/content-single.php`
- **Archive Content**: `template-parts/content/content-archive.php`
- **Search Results**: `template-parts/content/content-search.php`

### WooCommerce Template Parts
- **Product Loop Start**: `woocommerce/loop/loop-start.php`
- **Product Loop End**: `woocommerce/loop/loop-end.php`
- **Product Sale Flash**: `woocommerce/loop/sale-flash.php`
- **Product Title**: `woocommerce/single-product/title.php`
- **Product Price**: `woocommerce/single-product/price.php`
- **Product Add to Cart**: `woocommerce/single-product/add-to-cart.php`

## 9. Custom Template Files

### Theme-Specific Templates
- **Quick View Modal**: `templates/quick-view.php`
- **Product Filters**: `templates/product-filters.php`
- **Custom Widgets**: `templates/widgets/*.php`

### Layout Templates
- **Full Width Layout**: `templates/layouts/full-width.php`
- **Sidebar Layout**: `templates/layouts/sidebar.php`
- **Two Column Layout**: `templates/layouts/two-column.php`

## 10. Template Loading Process

### Template Selection Flow
1. **WordPress Core**: Determines content type
2. **Template Hierarchy**: Applies hierarchy rules
3. **Child Theme Check**: Looks for template in child theme first
4. **Parent Theme Check**: Falls back to parent theme
5. **Default Template**: Uses default if no custom template found

### Template Override Process
1. **Create Template**: Create custom template in child theme
2. **Template Name**: Use appropriate template name
3. **Template Content**: Add custom content and markup
4. **Template Functions**: Use theme functions for consistency

### Template Fallback System
1. **Specific Template**: Uses most specific template first
2. **Generic Template**: Falls back to generic templates
3. **Index Template**: Uses index.php as final fallback
4. **Error Handling**: Displays error if no templates found

## 11. Template Functions

### Template Loading Functions
- `get_template_part()`: Load template parts
- `locate_template()`: Find template files
- `load_template()`: Load template file
- `get_query_template()`: Get query-specific template

### Custom Template Functions
- `aqualuxe_get_template_part()`: Custom template part loader
- `aqualuxe_locate_template()`: Custom template locator
- `aqualuxe_get_template()`: Custom template loader

## 12. Template Variables

### Global Variables Available
- `$post`: Current post object
- `$wp_query`: Current query object
- `$wpdb`: Database object
- `$wp`: WordPress object

### Custom Variables
- `$aqualuxe_options`: Theme options
- `$product`: Current product object (WooCommerce)
- `$cart`: Current cart object (WooCommerce)

## 13. Template Conditional Functions

### WordPress Conditional Tags
- `is_home()`: Home page
- `is_front_page()`: Front page
- `is_page()`: Page
- `is_single()`: Single post
- `is_archive()`: Archive page
- `is_search()`: Search results
- `is_404()`: 404 error page

### WooCommerce Conditional Tags
- `is_shop()`: Shop page
- `is_product()`: Single product
- `is_product_category()`: Product category
- `is_cart()`: Cart page
- `is_checkout()`: Checkout page
- `is_account_page()`: Account page

### Custom Conditional Functions
- `aqualuxe_is_woocommerce_active()`: Check if WooCommerce is active
- `aqualuxe_is_dev_mode()`: Check if development mode is enabled
- `aqualuxe_has_sidebar()`: Check if sidebar should be displayed

## 14. Template Best Practices

### Template Structure
1. **Header**: Include header template
2. **Content**: Main content area
3. **Sidebar**: Include sidebar if needed
4. **Footer**: Include footer template

### Template Coding Standards
1. **PHP Tags**: Use full PHP tags
2. **HTML Structure**: Follow semantic HTML
3. **WordPress Functions**: Use WordPress functions
4. **Security**: Escape output properly
5. **Accessibility**: Include ARIA attributes

### Template Performance
1. **Minimize Queries**: Reduce database queries
2. **Cache Data**: Use transients for expensive operations
3. **Optimize Assets**: Minimize CSS and JavaScript
4. **Lazy Loading**: Implement lazy loading for images

## 15. Template Customization

### Child Theme Templates
1. **Create Template**: Copy template to child theme
2. **Modify Content**: Customize template content
3. **Test Changes**: Verify template works correctly
4. **Document Changes**: Document customizations

### Template Hooks
1. **Action Hooks**: Add content at specific points
2. **Filter Hooks**: Modify content before display
3. **Custom Hooks**: Create custom theme hooks
4. **Hook Removal**: Remove unwanted hooks

## Conclusion

The AquaLuxe theme follows WordPress template hierarchy standards while providing enhanced templates for WooCommerce functionality. Understanding this hierarchy allows developers to create custom templates that integrate seamlessly with the theme's existing structure.

Key points to remember:
1. Child themes should use the same template hierarchy
2. Custom templates should follow naming conventions
3. Template parts should be modular and reusable
4. WooCommerce templates should maintain compatibility
5. Performance and security should be considered in all templates

This template hierarchy documentation will be updated as new templates are added to the theme, ensuring developers always have access to the latest template information.