# AquaLuxe Accessibility Guide

This guide outlines the accessibility features built into the AquaLuxe theme and provides recommendations for maintaining an accessible website that complies with WCAG 2.1 AA standards.

## Accessibility Features

AquaLuxe is designed with accessibility in mind and includes the following features:

### 1. Semantic HTML Structure

- **Proper Heading Hierarchy**: Logical heading structure (H1-H6)
- **Semantic Elements**: Appropriate use of HTML5 semantic elements (header, nav, main, footer, etc.)
- **ARIA Landmarks**: Proper ARIA landmark roles for screen readers
- **Skip Links**: Allows keyboard users to skip to main content
- **Structured Content**: Logical reading order and document structure

### 2. Keyboard Navigation

- **Focus Indicators**: Visible focus styles for all interactive elements
- **Keyboard Accessible Menus**: Dropdown menus accessible via keyboard
- **Tab Order**: Logical tab order for form elements and interactive components
- **No Keyboard Traps**: All functionality is accessible without getting stuck
- **Shortcut Keys**: Optional keyboard shortcuts for common actions

### 3. Color and Contrast

- **Color Contrast**: All text meets WCAG 2.1 AA contrast requirements (4.5:1 for normal text, 3:1 for large text)
- **Non-Color Indicators**: Information is not conveyed by color alone
- **Focus Visibility**: High-contrast focus indicators
- **Text Over Images**: Ensures readable text over background images
- **Customizable Colors**: Options to adjust colors for better contrast

### 4. Images and Media

- **Alt Text**: Support for alternative text for all images
- **Decorative Images**: Proper handling of decorative images
- **Responsive Images**: Images scale appropriately on zoom
- **Captions**: Support for captions in galleries and media
- **No Autoplay**: Media does not play automatically

### 5. Forms and Inputs

- **Labeled Inputs**: All form fields have associated labels
- **Error Identification**: Clear error messages and indicators
- **Form Instructions**: Clear instructions for form completion
- **Grouped Controls**: Related form controls are grouped logically
- **Accessible Validation**: Form validation messages are accessible to screen readers

### 6. WooCommerce Accessibility

- **Accessible Cart**: Screen reader friendly shopping cart
- **Product Information**: Structured product data for assistive technologies
- **Checkout Process**: Accessible, step-by-step checkout
- **Form Labels**: All form fields properly labeled
- **Error Handling**: Clear error messages during checkout

## Accessibility Settings

AquaLuxe includes accessibility settings that can be configured:

1. Navigate to **Appearance > Customize**
2. Click the **Accessibility** section
3. Configure the following options:

### General Accessibility

- **Skip Link**: Enable/disable and customize the skip to content link
- **Focus Outline**: Customize the appearance of focus indicators
- **Reduced Motion**: Option for users who prefer reduced motion
- **Font Sizing**: Adjust base font size for better readability
- **Link Underlines**: Option to always underline links for better visibility

### Keyboard Navigation

- **Enhanced Focus**: Improved focus visibility
- **Keyboard Shortcuts**: Enable/disable and customize keyboard shortcuts
- **Menu Behavior**: Adjust dropdown menu behavior for keyboard users
- **Form Navigation**: Customize form field navigation
- **Tabbing Order**: Adjust the tabbing order of key elements

### Screen Readers

- **ARIA Enhancements**: Additional ARIA attributes for better screen reader support
- **Announcement Messages**: Customize how dynamic content changes are announced
- **Form Descriptions**: Additional descriptive text for complex forms
- **Image Descriptions**: Default alt text behavior
- **Table Descriptions**: Caption and summary handling for data tables

## WCAG 2.1 AA Compliance

AquaLuxe is designed to meet WCAG 2.1 AA standards. Here's how the theme addresses key success criteria:

### Perceivable

- **1.1.1 Non-text Content**: All images have appropriate alt text support
- **1.3.1 Info and Relationships**: Semantic structure conveys information properly
- **1.3.2 Meaningful Sequence**: Content is presented in a meaningful order
- **1.3.3 Sensory Characteristics**: Instructions don't rely solely on sensory characteristics
- **1.3.4 Orientation**: Content is not restricted to a single orientation
- **1.3.5 Identify Input Purpose**: Input fields have appropriate autocomplete attributes
- **1.4.1 Use of Color**: Color is not the only visual means of conveying information
- **1.4.2 Audio Control**: Audio does not play automatically
- **1.4.3 Contrast (Minimum)**: Text has sufficient contrast against backgrounds
- **1.4.4 Resize Text**: Text can be resized up to 200% without loss of content
- **1.4.5 Images of Text**: Real text is used instead of images of text
- **1.4.10 Reflow**: Content reflows on small screens without horizontal scrolling
- **1.4.11 Non-text Contrast**: UI components have sufficient contrast
- **1.4.12 Text Spacing**: No loss of content when text spacing is adjusted
- **1.4.13 Content on Hover or Focus**: Popup content is dismissible and persistent

### Operable

- **2.1.1 Keyboard**: All functionality is available via keyboard
- **2.1.2 No Keyboard Trap**: Keyboard focus is not trapped
- **2.1.4 Character Key Shortcuts**: Shortcuts can be turned off or remapped
- **2.2.1 Timing Adjustable**: Time limits can be adjusted or extended
- **2.2.2 Pause, Stop, Hide**: Moving content can be paused
- **2.3.1 Three Flashes**: No content flashes more than three times per second
- **2.4.1 Bypass Blocks**: Skip links allow bypassing repeated content
- **2.4.2 Page Titled**: Each page has a descriptive title
- **2.4.3 Focus Order**: Focus order preserves meaning and operability
- **2.4.4 Link Purpose**: The purpose of each link is clear from its text
- **2.4.5 Multiple Ways**: Multiple ways to find pages are available
- **2.4.6 Headings and Labels**: Headings and labels are descriptive
- **2.4.7 Focus Visible**: Keyboard focus is clearly visible
- **2.5.1 Pointer Gestures**: Complex gestures have simpler alternatives
- **2.5.2 Pointer Cancellation**: Actions are completed on up-event
- **2.5.3 Label in Name**: Visible labels match their accessible names
- **2.5.4 Motion Actuation**: Functionality triggered by motion has alternatives

### Understandable

- **3.1.1 Language of Page**: The language of the page is programmatically set
- **3.1.2 Language of Parts**: The language of sections is programmatically set when different
- **3.2.1 On Focus**: Elements do not change context when receiving focus
- **3.2.2 On Input**: Changing a setting does not automatically change context
- **3.2.3 Consistent Navigation**: Navigation is consistent across the site
- **3.2.4 Consistent Identification**: Components with the same functionality are identified consistently
- **3.3.1 Error Identification**: Form errors are clearly identified
- **3.3.2 Labels or Instructions**: Form fields have clear labels and instructions
- **3.3.3 Error Suggestion**: Error messages suggest corrections
- **3.3.4 Error Prevention**: Important submissions can be reviewed and corrected

### Robust

- **4.1.1 Parsing**: HTML is well-formed
- **4.1.2 Name, Role, Value**: All UI components have appropriate names, roles, and values
- **4.1.3 Status Messages**: Status messages are programmatically determined

## Accessibility Testing

To ensure your site remains accessible:

### Automated Testing

Use these tools for initial testing:

1. **WAVE Web Accessibility Evaluation Tool**:
   - Visit [WAVE](https://wave.webaim.org/)
   - Enter your website URL
   - Review errors and warnings

2. **Lighthouse in Chrome DevTools**:
   - Open Chrome DevTools (F12)
   - Click the "Lighthouse" tab
   - Select "Accessibility" and run the audit

3. **axe DevTools**:
   - Install the axe DevTools browser extension
   - Run it on your pages to identify issues

### Manual Testing

Automated tools can't catch everything. Perform these manual tests:

1. **Keyboard Navigation**:
   - Tab through the entire site
   - Ensure all interactive elements are accessible
   - Check that focus is always visible
   - Verify dropdown menus work with keyboard

2. **Screen Reader Testing**:
   - Test with NVDA (Windows), VoiceOver (Mac), or JAWS
   - Navigate through the site using the screen reader
   - Verify that all content is announced correctly
   - Check that dynamic content changes are announced

3. **Zoom Testing**:
   - Zoom the browser to 200%
   - Ensure all content is still readable and usable
   - Check that no horizontal scrolling is required

4. **Color Contrast**:
   - Use the [WebAIM Contrast Checker](https://webaim.org/resources/contrastchecker/)
   - Verify all text meets minimum contrast requirements
   - Check contrast of UI components and form fields

## Making Your Content Accessible

Follow these guidelines when adding content to your site:

### Images

- Add descriptive alt text to all informative images
- Use empty alt text (`alt=""`) for decorative images
- Avoid text in images; use real text with styling instead
- Ensure infographics and charts have detailed descriptions

### Text Content

- Use clear, simple language
- Break content into logical sections with headings
- Use proper heading hierarchy (H1, H2, H3, etc.)
- Avoid justified text alignment
- Ensure sufficient line spacing
- Use descriptive link text (avoid "click here" or "read more")

### Tables

- Use tables for tabular data only, not for layout
- Include proper table headers (`<th>`)
- Add captions to describe table content
- Use scope attributes to associate headers with cells
- Consider adding a summary for complex tables

### Forms

- Label all form fields with `<label>` elements
- Group related fields with `<fieldset>` and `<legend>`
- Provide clear instructions and error messages
- Indicate required fields both visually and programmatically
- Ensure form validation errors are clearly identified

### Video and Audio

- Provide captions for videos
- Include transcripts for audio content
- Avoid autoplay
- Ensure media controls are keyboard accessible
- Provide audio descriptions for important visual information in videos

## WooCommerce Accessibility Tips

For an accessible WooCommerce store:

1. **Product Images**:
   - Add descriptive alt text to all product images
   - Include multiple images showing different aspects of products

2. **Product Descriptions**:
   - Write clear, detailed descriptions
   - Include important information like dimensions, materials, etc.
   - Structure content with headings and lists

3. **Product Variations**:
   - Ensure variation selectors are keyboard accessible
   - Provide clear labels for each variation option
   - Use proper ARIA attributes for dynamic content

4. **Cart and Checkout**:
   - Ensure form fields are properly labeled
   - Provide clear error messages
   - Allow users to review orders before submission
   - Make sure the process works with keyboard only

5. **Product Filters**:
   - Make filter controls accessible
   - Ensure results are announced to screen readers
   - Provide clear feedback when filters are applied

## Accessibility Statement

Consider adding an accessibility statement to your website:

1. Create a new page titled "Accessibility"
2. Include information about:
   - Your commitment to accessibility
   - The standards you follow (WCAG 2.1 AA)
   - Known accessibility issues and plans to address them
   - How users can report accessibility problems
   - Contact information for accessibility feedback

3. Link to this page from your footer

## Getting Help

If you need assistance with accessibility:

- Review our [accessibility tutorials](https://aqualuxetheme.com/accessibility-tutorials)
- Contact our support team for theme-specific accessibility questions
- Consider hiring an accessibility consultant for a professional audit

By following these guidelines and utilizing the built-in accessibility features of AquaLuxe, your website will be more inclusive and usable for all visitors, including those with disabilities.