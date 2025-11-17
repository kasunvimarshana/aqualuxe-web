# AquaLuxe Homepage Setup Guide

This guide will walk you through setting up and customizing the AquaLuxe theme homepage to create a stunning and effective landing page for your aquarium business.

## Table of Contents

1. [Setting Up the Homepage](#setting-up-the-homepage)
2. [Customizing Homepage Sections](#customizing-homepage-sections)
3. [Advanced Homepage Customization](#advanced-homepage-customization)
4. [Optimizing Your Homepage](#optimizing-your-homepage)
5. [Troubleshooting](#troubleshooting)

## Setting Up the Homepage

### Step 1: Create a Static Homepage

1. Go to **Pages > Add New**
2. Enter "Home" as the title
3. In the Page Attributes panel, select "Front Page" as the template
4. Publish the page

### Step 2: Set Your Static Homepage

1. Go to **Settings > Reading**
2. Under "Your homepage displays," select "A static page"
3. For "Homepage," select the "Home" page you just created
4. For "Posts page," select an existing page or create a new one called "Blog"
5. Click "Save Changes"

### Step 3: Access Homepage Customization

1. Go to **Appearance > Customize**
2. Click on "Homepage Sections"
3. You'll see all available homepage sections that you can customize

## Customizing Homepage Sections

The AquaLuxe homepage is divided into several sections that you can customize individually. Here's how to configure each section:

### Hero Section

The hero section is the first thing visitors see when they land on your site.

1. In the Customizer, go to **Homepage Sections > Hero Section**
2. Choose between "Slider" or "Static Image" mode
3. For Slider mode:
   - Add up to 5 slides
   - For each slide, upload an image (recommended size: 1920×800px)
   - Add heading, subheading, and button text
   - Set button URL and style
   - Configure slider speed and animation
4. For Static Image mode:
   - Upload a background image (recommended size: 1920×800px)
   - Add heading, subheading, and button text
   - Set button URL and style
   - Adjust overlay opacity and color
5. Click "Publish" to save changes

### Featured Products Section

Showcase your best-selling or featured aquarium products.

1. In the Customizer, go to **Homepage Sections > Featured Products**
2. Toggle "Enable Featured Products Section" to display this section
3. Enter section title and subtitle
4. Choose display options:
   - Number of products to display (4-12)
   - Number of columns (2-4)
   - Product source (featured, best-selling, newest, or manual selection)
   - If "Manual Selection," choose specific products to display
5. Configure "View All" button text and URL
6. Adjust section padding and background color/image
7. Click "Publish" to save changes

### Categories Section

Highlight your main product categories with images and descriptions.

1. In the Customizer, go to **Homepage Sections > Categories Section**
2. Toggle "Enable Categories Section" to display this section
3. Enter section title and subtitle
4. Select which product categories to display
5. Choose display style:
   - Grid (2-4 columns)
   - Carousel
   - Featured (one large, others small)
6. For each category, you can:
   - Override the default category image
   - Add a custom description
   - Customize button text
7. Adjust section padding and background color/image
8. Click "Publish" to save changes

### About Section

Tell visitors about your business and expertise.

1. In the Customizer, go to **Homepage Sections > About Section**
2. Toggle "Enable About Section" to display this section
3. Enter section title and content
4. Upload an image (recommended size: 800×600px)
5. Choose image position (left or right)
6. Add up to 3 feature boxes with icons and text
7. Configure button text and URL
8. Adjust section padding and background color/image
9. Click "Publish" to save changes

### Services Section

Showcase the services you offer to aquarium enthusiasts.

1. In the Customizer, go to **Homepage Sections > Services Section**
2. Toggle "Enable Services Section" to display this section
3. Enter section title and subtitle
4. Choose display options:
   - Number of services to display (3-9)
   - Number of columns (1-3)
   - Display style (grid, list, or carousel)
5. Select which services to feature (pulls from Services custom post type)
6. Configure "View All Services" button text and URL
7. Adjust section padding and background color/image
8. Click "Publish" to save changes

### Testimonials Section

Display customer reviews and feedback.

1. In the Customizer, go to **Homepage Sections > Testimonials Section**
2. Toggle "Enable Testimonials Section" to display this section
3. Enter section title and subtitle
4. Choose display options:
   - Number of testimonials to display (1-6)
   - Display style (slider or grid)
   - If grid, number of columns (1-3)
5. Select which testimonials to feature (pulls from Testimonials custom post type)
6. Adjust section padding and background color/image
7. Click "Publish" to save changes

### Blog Section

Feature your latest blog posts to share your aquarium expertise.

1. In the Customizer, go to **Homepage Sections > Blog Section**
2. Toggle "Enable Blog Section" to display this section
3. Enter section title and subtitle
4. Choose display options:
   - Number of posts to display (3-9)
   - Number of columns (1-3)
   - Post information to show (date, author, excerpt, categories)
5. Configure "View All Posts" button text
6. Adjust section padding and background color/image
7. Click "Publish" to save changes

### Call to Action (CTA) Section

Add a compelling call to action to drive conversions.

1. In the Customizer, go to **Homepage Sections > CTA Section**
2. Toggle "Enable CTA Section" to display this section
3. Enter heading and subheading text
4. Configure primary and secondary buttons (text and URLs)
5. Upload a background image or choose a solid color
6. Adjust overlay opacity and color
7. Set section height and padding
8. Click "Publish" to save changes

## Advanced Homepage Customization

### Reordering Sections

You can change the order of homepage sections:

1. In the Customizer, go to **Homepage Sections > Section Order**
2. Drag and drop sections to reorder them
3. Click "Publish" to save changes

### Adding Custom Sections

To add custom content sections to your homepage:

1. Create a child theme (recommended)
2. Create a custom template part in your child theme at `templates/parts/custom-section.php`
3. Add your custom HTML and PHP code
4. Add the following code to your child theme's `functions.php`:

```php
function aqualuxe_child_add_homepage_section($sections) {
    $sections['custom-section'] = array(
        'label' => __('Custom Section', 'aqualuxe-child'),
        'priority' => 85, // Position in the section order
    );
    return $sections;
}
add_filter('aqualuxe_homepage_sections', 'aqualuxe_child_add_homepage_section');

function aqualuxe_child_render_custom_section() {
    get_template_part('templates/parts/custom-section');
}
add_action('aqualuxe_homepage_custom-section', 'aqualuxe_child_render_custom_section');
```

5. Your custom section will now appear in the Customizer under Homepage Sections

### Custom CSS for Homepage

To add custom CSS specifically for the homepage:

1. Go to **Appearance > Customize > Additional CSS**
2. Add your custom CSS using the `.home` class as a parent selector:

```css
.home .hero-section {
    /* Custom styles for hero section */
}

.home .featured-products {
    /* Custom styles for featured products section */
}
```

3. Click "Publish" to save changes

## Optimizing Your Homepage

### Performance Optimization

1. **Image Optimization**
   - Compress all images before uploading
   - Use appropriate image dimensions (don't upload oversized images)
   - Consider using WebP format for better compression

2. **Content Prioritization**
   - Place your most important content "above the fold"
   - Limit the number of sections to prevent excessive scrolling
   - Focus on clear calls-to-action

3. **Loading Speed**
   - Enable browser caching
   - Consider using a caching plugin
   - Minimize the use of heavy sliders and animations

### SEO Optimization

1. **Content Optimization**
   - Use relevant keywords in section headings and text
   - Write compelling, unique content that provides value
   - Include proper heading structure (H1, H2, H3)

2. **Image SEO**
   - Add descriptive alt text to all images
   - Use descriptive filenames for images

3. **Technical SEO**
   - Ensure your homepage loads quickly
   - Make sure it's mobile-friendly
   - Add schema markup for your business type

### Conversion Optimization

1. **Clear Value Proposition**
   - Communicate your unique selling points clearly
   - Highlight benefits rather than just features

2. **Strategic CTAs**
   - Use contrasting colors for CTA buttons
   - Place CTAs at logical points in the user journey
   - Use action-oriented button text

3. **Trust Elements**
   - Display testimonials prominently
   - Show any certifications or awards
   - Include trust badges if applicable

## Troubleshooting

### Common Issues and Solutions

1. **Sections Not Displaying**
   - Verify the section is enabled in the Customizer
   - Check if you have the necessary content (e.g., products, services)
   - Clear your cache and refresh

2. **Layout Problems**
   - Check for responsive issues at different screen sizes
   - Verify custom CSS is properly formatted
   - Inspect element with browser tools to identify conflicts

3. **Slow Loading**
   - Optimize images
   - Check for plugin conflicts
   - Consider enabling caching

4. **Content Not Updating**
   - Clear your browser cache
   - Clear any caching plugin cache
   - Verify changes were published in the Customizer

### Getting Help

If you encounter issues that you can't resolve:

1. Check the [AquaLuxe documentation](https://aqualuxetheme.com/documentation/)
2. Visit the [support forum](https://aqualuxetheme.com/support/)
3. Contact support at support@aqualuxetheme.com

## Conclusion

Your homepage is the face of your aquarium business online. By carefully configuring each section of the AquaLuxe homepage, you can create a compelling, visually stunning, and effective landing page that converts visitors into customers.

Remember to regularly update your homepage content to keep it fresh and relevant. Test different layouts and content to see what resonates best with your audience.