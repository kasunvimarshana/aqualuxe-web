# AquaLuxe WordPress Theme Testing Suite

This directory contains testing scripts and utilities for ensuring the AquaLuxe WordPress theme meets quality standards across responsiveness, performance, accessibility, and cross-browser compatibility.

## Testing Areas

### 1. Responsive Testing
Tests the theme's layout and functionality across different viewport sizes:
- Mobile devices (320px-480px)
- Tablets (481px-768px)
- Small laptops (769px-1024px)
- Large screens (1025px+)

### 2. Performance Testing
Analyzes the theme's loading speed and performance metrics:
- First Contentful Paint (FCP)
- Largest Contentful Paint (LCP)
- Total Blocking Time (TBT)
- Cumulative Layout Shift (CLS)
- Overall performance score

### 3. Accessibility Testing
Ensures the theme meets WCAG accessibility standards:
- Color contrast
- Keyboard navigation
- Screen reader compatibility
- ARIA attributes
- Focus management

### 4. Cross-Browser Testing
Verifies consistent appearance and functionality across browsers:
- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Mobile browsers

## Setup Instructions

1. Install dependencies:
   ```
   npm install
   ```

2. Make sure you have a local WordPress installation with the AquaLuxe theme running at http://localhost:8000 (or update the baseUrl in each script)

3. Run individual tests:
   ```
   npm run test:responsive
   npm run test:performance
   npm run test:accessibility
   npm run test:cross-browser
   ```

4. Or run all tests:
   ```
   npm run test:all
   ```

## Test Results

Test results are saved in the following directories:
- Responsive screenshots: `screenshots/`
- Performance reports: `reports/`
- Accessibility reports: `reports/`
- Cross-browser screenshots: `browser-screenshots/`

## Interpreting Results

### Responsive Testing
Review the screenshots to ensure the layout adapts properly to different screen sizes. Look for:
- Proper text wrapping
- No horizontal scrolling
- Appropriate image scaling
- Correctly functioning navigation menus

### Performance Testing
Review the performance reports and aim for:
- Performance score > 90
- FCP < 1.8s
- LCP < 2.5s
- TBT < 200ms
- CLS < 0.1

### Accessibility Testing
Review the accessibility reports and fix all violations. Priority should be given to:
- Critical and serious issues
- Color contrast problems
- Keyboard navigation issues
- Missing alternative text

### Cross-Browser Testing
Compare screenshots across browsers to identify inconsistencies in:
- Layout and positioning
- Font rendering
- Color display
- Interactive elements