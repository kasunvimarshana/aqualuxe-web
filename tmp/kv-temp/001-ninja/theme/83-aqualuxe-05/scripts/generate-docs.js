#!/usr/bin/env node

/**
 * AquaLuxe Theme Documentation Generator
 * 
 * Generates comprehensive documentation for the theme.
 */

const fs = require('fs');
const path = require('path');

class DocumentationGenerator {
    constructor() {
        this.themeDir = path.resolve(__dirname, '..');
        this.docsDir = path.join(this.themeDir, 'docs');
    }

    async generate() {
        console.log('📚 Generating AquaLuxe theme documentation...');
        
        try {
            // Ensure docs directory exists
            if (!fs.existsSync(this.docsDir)) {
                fs.mkdirSync(this.docsDir, { recursive: true });
            }
            
            // Generate documentation files
            await this.generateReadme();
            await this.generateAPIReference();
            await this.generateCustomizationGuide();
            await this.generateTroubleshooting();
            await this.generateDeveloperGuide();
            
            console.log('✅ Documentation generated successfully!');
            console.log(`📁 Documentation location: ${this.docsDir}`);
            
        } catch (error) {
            console.error('❌ Documentation generation failed:', error);
            process.exit(1);
        }
    }

    async generateReadme() {
        const readmeContent = `# AquaLuxe WordPress Theme

![AquaLuxe Theme](https://via.placeholder.com/1200x600/0ea5e9/ffffff?text=AquaLuxe+Theme)

A modern, interactive WordPress theme featuring stunning Three.js fish tank animations, complete WooCommerce integration, and accessibility-first design.

## 🌊 Features

### Interactive Elements
- **3D Fish Tank Hero**: Immersive underwater scene with swimming fish and bubbles
- **GSAP Animations**: Smooth micro-interactions throughout the site
- **Dark Mode**: Seamless light/dark theme switching
- **Modal System**: Flexible popups for various content types

### E-commerce Ready
- **WooCommerce Integration**: Complete shop functionality
- **AJAX Cart**: Real-time cart updates
- **Product Quick View**: Modal product previews
- **Wishlist & Compare**: Advanced product features

### Performance & Accessibility
- **WCAG 2.1 AA Compliant**: Full accessibility support
- **Mobile-First**: Responsive design for all devices
- **Performance Optimized**: Fast loading with lazy loading
- **SEO Ready**: Schema markup and clean code

## 🚀 Quick Start

### Requirements
- WordPress 5.0+
- PHP 7.4+
- WooCommerce 5.0+ (optional)

### Installation
1. Download the theme zip file
2. Upload to WordPress Admin → Appearance → Themes
3. Activate the theme
4. Import demo content (optional)

### Demo Content
Go to **Appearance → Demo Importer** to import:
- Sample pages and posts
- WooCommerce products
- Theme settings
- Navigation menus

## 🎨 Customization

### Theme Customizer
Access **Appearance → Customize** to modify:
- Colors and fonts
- Logo and branding
- Layout options
- Fish tank settings

### Supported Page Builders
- Gutenberg (full support)
- Elementor (compatible)
- Beaver Builder (compatible)

## 🛠️ Development

### Build System
The theme uses a modern build system with:
- Webpack for asset compilation
- Tailwind CSS for styling
- ESLint for code quality
- Sass preprocessing

### Commands
\`\`\`bash
npm install          # Install dependencies
npm run dev          # Development server
npm run build        # Production build
npm run watch        # Watch for changes
\`\`\`

## 📖 Documentation

- [Installation Guide](./INSTALLATION.md)
- [Customization Guide](./docs/customization.md)
- [Developer Guide](./docs/developer.md)
- [API Reference](./docs/api.md)
- [Troubleshooting](./docs/troubleshooting.md)

## 🆘 Support

- **Documentation**: [Theme Docs](https://aqualuxe.com/docs)
- **Support Forum**: [Community Support](https://aqualuxe.com/support)
- **Email**: support@aqualuxe.com

## 📄 License

This theme is licensed under the MIT License. See [LICENSE.txt](LICENSE.txt) for details.

## 🙏 Credits

- **Three.js**: 3D graphics library
- **GSAP**: Animation library
- **Tailwind CSS**: Utility-first CSS framework
- **WordPress**: Content management system

---

Made with ❤️ by the AquaLuxe Team
`;
        
        fs.writeFileSync(path.join(this.themeDir, 'README.md'), readmeContent);
    }

    async generateAPIReference() {
        const apiContent = `# AquaLuxe Theme API Reference

## Hooks and Filters

### Action Hooks

#### \`aqualuxe_before_header\`
Fires before the header content.

\`\`\`php
do_action('aqualuxe_before_header');
\`\`\`

#### \`aqualuxe_after_header\`
Fires after the header content.

#### \`aqualuxe_before_content\`
Fires before the main content area.

#### \`aqualuxe_after_content\`
Fires after the main content area.

#### \`aqualuxe_before_footer\`
Fires before the footer content.

#### \`aqualuxe_after_footer\`
Fires after the footer content.

### Filter Hooks

#### \`aqualuxe_fish_tank_settings\`
Filter the fish tank configuration settings.

\`\`\`php
function custom_fish_tank_settings($settings) {
    $settings['fish_count'] = 15;
    $settings['bubble_intensity'] = 'high';
    return $settings;
}
add_filter('aqualuxe_fish_tank_settings', 'custom_fish_tank_settings');
\`\`\`

#### \`aqualuxe_color_palette\`
Filter the theme color palette.

\`\`\`php
function custom_color_palette($colors) {
    $colors['primary'] = '#custom-color';
    return $colors;
}
add_filter('aqualuxe_color_palette', 'custom_color_palette');
\`\`\`

#### \`aqualuxe_animation_settings\`
Filter animation configuration.

\`\`\`php
function custom_animation_settings($settings) {
    $settings['duration'] = 0.5;
    $settings['easing'] = 'power2.out';
    return $settings;
}
add_filter('aqualuxe_animation_settings', 'custom_animation_settings');
\`\`\`

## JavaScript API

### FishTank Component

#### Initialize
\`\`\`javascript
const fishTank = new FishTank({
    container: '#fish-tank',
    fishCount: 10,
    enableInteraction: true
});
\`\`\`

#### Methods
- \`fishTank.addFish(count)\` - Add fish to the tank
- \`fishTank.removeFish(count)\` - Remove fish from the tank
- \`fishTank.pause()\` - Pause the animation
- \`fishTank.resume()\` - Resume the animation
- \`fishTank.destroy()\` - Clean up and destroy

#### Events
\`\`\`javascript
fishTank.on('fishClick', (fish) => {
    console.log('Fish clicked:', fish);
});

fishTank.on('ready', () => {
    console.log('Fish tank initialized');
});
\`\`\`

### WooCommerce Component

#### Cart Management
\`\`\`javascript
// Add to cart
AquaLuxe.woocommerce.addToCart(productId, quantity);

// Update cart
AquaLuxe.woocommerce.updateCart(itemKey, quantity);

// Remove from cart
AquaLuxe.woocommerce.removeFromCart(itemKey);
\`\`\`

#### Wishlist
\`\`\`javascript
// Add to wishlist
AquaLuxe.woocommerce.addToWishlist(productId);

// Remove from wishlist
AquaLuxe.woocommerce.removeFromWishlist(productId);
\`\`\`

### Modal System

#### Open Modal
\`\`\`javascript
AquaLuxe.modal.open({
    content: 'Modal content',
    size: 'large',
    closeOnOverlay: true
});
\`\`\`

#### Close Modal
\`\`\`javascript
AquaLuxe.modal.close();
\`\`\`

## PHP Classes

### AquaLuxe_Theme
Main theme class handling initialization and setup.

#### Methods
- \`get_instance()\` - Get singleton instance
- \`init()\` - Initialize theme
- \`enqueue_scripts()\` - Enqueue theme assets

### AquaLuxe_Customizer
Theme customizer functionality.

#### Methods
- \`register_panels()\` - Register customizer panels
- \`register_sections()\` - Register customizer sections
- \`register_controls()\` - Register customizer controls

### AquaLuxe_Demo_Importer
Demo content import functionality.

#### Methods
- \`import_content()\` - Import demo content
- \`import_widgets()\` - Import widget data
- \`import_customizer()\` - Import customizer settings

## Constants

### Theme Options
- \`AQUALUXE_VERSION\` - Theme version
- \`AQUALUXE_THEME_DIR\` - Theme directory path
- \`AQUALUXE_THEME_URL\` - Theme URL
- \`AQUALUXE_ASSETS_URL\` - Assets URL

### Feature Flags
- \`AQUALUXE_ENABLE_FISH_TANK\` - Enable/disable fish tank
- \`AQUALUXE_ENABLE_ANIMATIONS\` - Enable/disable animations
- \`AQUALUXE_DEBUG_MODE\` - Enable debug mode
`;
        
        fs.writeFileSync(path.join(this.docsDir, 'api.md'), apiContent);
    }

    async generateCustomizationGuide() {
        const customizationContent = `# AquaLuxe Customization Guide

## Theme Customizer

### Accessing the Customizer
1. Go to **WordPress Admin → Appearance → Customize**
2. You'll see AquaLuxe-specific options in the sidebar

### Available Options

#### Site Identity
- **Logo**: Upload your custom logo
- **Site Title & Tagline**: Set your site branding
- **Site Icon**: Upload a favicon

#### Colors
- **Primary Color**: Main brand color
- **Secondary Color**: Accent color
- **Text Color**: Default text color
- **Background Color**: Site background
- **Header Background**: Header area background
- **Footer Background**: Footer area background

#### Typography
- **Heading Font**: Choose from Google Fonts
- **Body Font**: Choose from Google Fonts
- **Font Sizes**: Adjust heading and body font sizes

#### Fish Tank Settings
- **Enable Fish Tank**: Turn on/off the hero animation
- **Fish Count**: Number of fish (5-20)
- **Animation Speed**: Slow, medium, or fast
- **Bubble Intensity**: Low, medium, or high
- **Water Color**: Customize water tint
- **Enable Interaction**: Allow user interaction

#### Layout Options
- **Container Width**: Max width of content
- **Sidebar Position**: Left, right, or none
- **Header Layout**: Different header styles
- **Footer Layout**: Different footer styles

#### WooCommerce
- **Shop Layout**: Grid or list view
- **Products per Page**: Number of products shown
- **Product Image Size**: Thumbnail dimensions
- **Cart Icon**: Show/hide cart in header

## Custom CSS

### Adding Custom Styles
1. Go to **Appearance → Customize → Additional CSS**
2. Add your custom CSS code
3. Preview changes in real-time

### Common Customizations

#### Change Fish Tank Height
\`\`\`css
.fish-tank-hero {
    height: 80vh; /* Default is 100vh */
}
\`\`\`

#### Customize Button Styles
\`\`\`css
.btn-primary {
    background-color: #your-color;
    border-color: #your-color;
}

.btn-primary:hover {
    background-color: #darker-color;
    border-color: #darker-color;
}
\`\`\`

#### Adjust Header Transparency
\`\`\`css
.site-header {
    background-color: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(10px);
}
\`\`\`

#### Modify Fish Tank Overlay
\`\`\`css
.fish-tank-overlay {
    background: linear-gradient(
        to bottom,
        rgba(0, 0, 0, 0.3),
        rgba(0, 0, 0, 0.7)
    );
}
\`\`\`

## Child Theme Development

### Creating a Child Theme
1. Create a new folder: \`aqualuxe-child\`
2. Create \`style.css\`:

\`\`\`css
/*
Theme Name: AquaLuxe Child
Description: Child theme of AquaLuxe
Template: aqualuxe
Version: 1.0.0
*/

@import url("../aqualuxe/style.css");

/* Your custom styles here */
\`\`\`

3. Create \`functions.php\`:

\`\`\`php
<?php
function aqualuxe_child_enqueue_styles() {
    wp_enqueue_style('aqualuxe-parent-style', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('aqualuxe-child-style', get_stylesheet_directory_uri() . '/style.css', array('aqualuxe-parent-style'));
}
add_action('wp_enqueue_scripts', 'aqualuxe_child_enqueue_styles');
?>
\`\`\`

### Overriding Template Files
Copy any template file from the parent theme to your child theme and modify:

- \`header.php\` - Header template
- \`footer.php\` - Footer template
- \`index.php\` - Main template
- \`single.php\` - Single post template
- \`page.php\` - Page template

## Advanced Customizations

### Custom Fish Tank Settings
Add to your child theme's \`functions.php\`:

\`\`\`php
function custom_fish_tank_config($settings) {
    $settings['fish_count'] = 15;
    $settings['fish_speed'] = 0.8;
    $settings['bubble_rate'] = 30;
    return $settings;
}
add_filter('aqualuxe_fish_tank_settings', 'custom_fish_tank_config');
\`\`\`

### Custom Color Schemes
\`\`\`php
function custom_color_scheme($colors) {
    $colors['primary'] = '#e74c3c';
    $colors['secondary'] = '#3498db';
    $colors['accent'] = '#f39c12';
    return $colors;
}
add_filter('aqualuxe_color_palette', 'custom_color_scheme');
\`\`\`

### Disable Specific Features
\`\`\`php
// Disable fish tank
add_filter('aqualuxe_enable_fish_tank', '__return_false');

// Disable animations
add_filter('aqualuxe_enable_animations', '__return_false');

// Disable dark mode
add_filter('aqualuxe_enable_dark_mode', '__return_false');
\`\`\`

## CSS Custom Properties

The theme uses CSS custom properties for easy customization:

\`\`\`css
:root {
    --color-primary: #0ea5e9;
    --color-secondary: #06b6d4;
    --color-accent: #f59e0b;
    --font-family-heading: 'Montserrat', sans-serif;
    --font-family-body: 'Open Sans', sans-serif;
    --border-radius: 8px;
    --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}
\`\`\`

Override these in your custom CSS or child theme for instant changes.

## Troubleshooting

### Fish Tank Not Loading
1. Check if WebGL is supported in the browser
2. Ensure JavaScript is enabled
3. Check browser console for errors
4. Try disabling other plugins that might conflict

### Performance Issues
1. Reduce fish count in customizer
2. Disable animations if not needed
3. Optimize images
4. Use a caching plugin

### Styling Issues
1. Clear browser cache
2. Check for CSS conflicts
3. Use browser developer tools
4. Ensure child theme is properly configured
`;
        
        fs.writeFileSync(path.join(this.docsDir, 'customization.md'), customizationContent);
    }

    async generateTroubleshooting() {
        const troubleshootingContent = `# AquaLuxe Troubleshooting Guide

## Common Issues

### Fish Tank Not Displaying

#### Symptoms
- Black screen where fish tank should be
- No fish or bubbles visible
- Console errors related to WebGL

#### Solutions
1. **Check WebGL Support**
   - Visit [webglreport.com](https://webglreport.com) to verify WebGL support
   - Update graphics drivers
   - Try a different browser

2. **JavaScript Errors**
   - Open browser developer tools (F12)
   - Check Console tab for errors
   - Disable other plugins that might conflict

3. **Theme Settings**
   - Go to Customizer → Fish Tank Settings
   - Ensure "Enable Fish Tank" is checked
   - Try reducing fish count to minimum

#### Code Fix
If fish tank is completely broken, add to your functions.php:
\`\`\`php
add_filter('aqualuxe_enable_fish_tank', '__return_false');
\`\`\`

### Performance Issues

#### Symptoms
- Slow page loading
- Laggy animations
- High CPU usage

#### Solutions
1. **Reduce Animation Complexity**
   \`\`\`php
   function reduce_fish_count($settings) {
       $settings['fish_count'] = 5; // Reduce from default
       $settings['animation_quality'] = 'low';
       return $settings;
   }
   add_filter('aqualuxe_fish_tank_settings', 'reduce_fish_count');
   \`\`\`

2. **Disable Animations on Mobile**
   \`\`\`php
   function disable_mobile_animations() {
       if (wp_is_mobile()) {
           add_filter('aqualuxe_enable_animations', '__return_false');
       }
   }
   add_action('init', 'disable_mobile_animations');
   \`\`\`

3. **Optimize Images**
   - Use WebP format when possible
   - Compress images before upload
   - Enable lazy loading

### WooCommerce Issues

#### Cart Not Updating
1. Check AJAX is working:
   \`\`\`javascript
   // Test in browser console
   jQuery.post(wc_add_to_cart_params.ajax_url, {
       action: 'test'
   });
   \`\`\`

2. Clear all caches
3. Deactivate other WooCommerce plugins temporarily

#### Product Images Not Loading
1. Regenerate thumbnails:
   - Install "Regenerate Thumbnails" plugin
   - Run regeneration process

2. Check file permissions:
   - Uploads folder should be writable (755 or 644)

### Styling Issues

#### Customizer Changes Not Appearing
1. **Clear All Caches**
   - Browser cache (Ctrl+F5)
   - WordPress cache plugins
   - Server-side cache

2. **Check CSS Priority**
   \`\`\`css
   /* Add !important if needed */
   .your-element {
       color: #your-color !important;
   }
   \`\`\`

3. **Verify Child Theme**
   - Ensure child theme is active
   - Check functions.php syntax

#### Mobile Responsiveness Issues
1. **Test Viewport Meta Tag**
   \`\`\`html
   <meta name="viewport" content="width=device-width, initial-scale=1">
   \`\`\`

2. **Check CSS Media Queries**
   \`\`\`css
   @media (max-width: 768px) {
       .fish-tank-hero {
           height: 50vh;
       }
   }
   \`\`\`

### Plugin Conflicts

#### Identifying Conflicts
1. **Plugin Deactivation Test**
   - Deactivate all plugins
   - Test if issue persists
   - Reactivate plugins one by one

2. **Theme Twenty Twenty-One Test**
   - Switch to default theme temporarily
   - Check if issue still occurs

#### Common Conflicting Plugins
- **Page Builders**: May override theme styles
- **Caching Plugins**: Can break AJAX functionality
- **Security Plugins**: May block JavaScript execution
- **Optimization Plugins**: Can interfere with asset loading

### Browser-Specific Issues

#### Safari Issues
- Fish tank may not load due to WebGL restrictions
- Add fallback content:
  \`\`\`php
  function safari_fish_tank_fallback() {
      if (strpos($_SERVER['HTTP_USER_AGENT'], 'Safari') !== false) {
          add_filter('aqualuxe_fish_tank_fallback', '__return_true');
      }
  }
  add_action('init', 'safari_fish_tank_fallback');
  \`\`\`

#### Internet Explorer
- Theme uses modern CSS not supported in IE
- Display compatibility message
- Provide graceful degradation

### Server Issues

#### Memory Limit Errors
Add to wp-config.php:
\`\`\`php
ini_set('memory_limit', '256M');
\`\`\`

#### Max Execution Time
\`\`\`php
ini_set('max_execution_time', 300);
\`\`\`

#### File Upload Limits
\`\`\`php
ini_set('upload_max_filesize', '64M');
ini_set('post_max_size', '64M');
\`\`\`

## Debug Mode

### Enable Debug Mode
Add to wp-config.php:
\`\`\`php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
define('AQUALUXE_DEBUG', true);
\`\`\`

### Debug Information
Access debug info at: \`yoursite.com/?aqualuxe_debug=1\`

This will show:
- PHP version and extensions
- WordPress version
- Active plugins
- Theme settings
- WebGL support status

## Getting Help

### Before Contacting Support
1. **Gather Information**
   - WordPress version
   - PHP version
   - Active plugins list
   - Browser and version
   - Error messages (exact text)

2. **Try Safe Mode**
   - Deactivate all plugins
   - Switch to default theme
   - Test functionality

3. **Check Logs**
   - WordPress debug log
   - Server error logs
   - Browser console

### Support Channels
- **Documentation**: [docs.aqualuxe.com](https://docs.aqualuxe.com)
- **Community Forum**: [forum.aqualuxe.com](https://forum.aqualuxe.com)
- **Email Support**: support@aqualuxe.com
- **GitHub Issues**: [github.com/aqualuxe/theme](https://github.com/aqualuxe/theme)

### Premium Support
For priority support and custom development:
- **Email**: premium@aqualuxe.com
- **Response Time**: Within 24 hours
- **Includes**: Custom code assistance, migration help, performance optimization
`;
        
        fs.writeFileSync(path.join(this.docsDir, 'troubleshooting.md'), troubleshootingContent);
    }

    async generateDeveloperGuide() {
        const developerContent = `# AquaLuxe Developer Guide

## Development Environment Setup

### Prerequisites
- Node.js 14+
- npm or yarn
- PHP 7.4+
- WordPress 5.0+

### Installation
1. Clone the theme repository
2. Install dependencies:
   \`\`\`bash
   npm install
   \`\`\`
3. Start development server:
   \`\`\`bash
   npm run dev
   \`\`\`

### Build Process
The theme uses Webpack for asset compilation:

\`\`\`bash
npm run dev          # Development build with watch
npm run build        # Production build
npm run build:dev    # Development build
npm run lint         # Run linters
npm run test         # Run tests
\`\`\`

## Project Structure

\`\`\`
aqualuxe/
├── assets/
│   ├── src/
│   │   ├── js/
│   │   │   ├── components/
│   │   │   │   ├── fishtank.js
│   │   │   │   ├── woocommerce.js
│   │   │   │   ├── navigation.js
│   │   │   │   ├── modal.js
│   │   │   │   └── accessibility.js
│   │   │   └── main.js
│   │   ├── scss/
│   │   │   ├── components/
│   │   │   ├── layout/
│   │   │   ├── utils/
│   │   │   └── main.scss
│   │   └── images/
│   └── dist/          # Compiled assets
├── inc/
│   ├── classes/
│   ├── customizer/
│   └── woocommerce/
├── template-parts/
├── templates/
└── languages/
\`\`\`

## JavaScript Architecture

### Main Entry Point
\`\`\`javascript
// assets/src/js/main.js
import { FishTank } from './components/fishtank';
import { WooCommerce } from './components/woocommerce';
import { Navigation } from './components/navigation';

class AquaLuxe {
    constructor() {
        this.init();
    }
    
    init() {
        this.initComponents();
        this.bindEvents();
    }
    
    initComponents() {
        if (document.querySelector('.fish-tank')) {
            this.fishTank = new FishTank();
        }
        
        this.woocommerce = new WooCommerce();
        this.navigation = new Navigation();
    }
}

// Initialize when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    window.AquaLuxe = new AquaLuxe();
});
\`\`\`

### Component Structure
Each component follows this pattern:

\`\`\`javascript
class ComponentName {
    constructor(options = {}) {
        this.options = { ...this.defaults, ...options };
        this.element = null;
        this.isInitialized = false;
        
        this.init();
    }
    
    get defaults() {
        return {
            // Default options
        };
    }
    
    init() {
        this.bindElements();
        this.bindEvents();
        this.setup();
        
        this.isInitialized = true;
    }
    
    bindElements() {
        // Cache DOM elements
    }
    
    bindEvents() {
        // Bind event listeners
    }
    
    setup() {
        // Component-specific setup
    }
    
    destroy() {
        // Cleanup
        this.isInitialized = false;
    }
}
\`\`\`

### Event System
Components communicate through custom events:

\`\`\`javascript
// Dispatch event
this.element.dispatchEvent(new CustomEvent('component:ready', {
    detail: { component: this }
}));

// Listen for event
document.addEventListener('component:ready', (event) => {
    console.log('Component ready:', event.detail.component);
});
\`\`\`

## PHP Architecture

### Theme Class Structure
\`\`\`php
<?php
class AquaLuxe_Theme {
    private static $instance = null;
    
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    private function __construct() {
        $this->init();
    }
    
    public function init() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_styles'));
        add_action('init', array($this, 'register_menus'));
        add_action('widgets_init', array($this, 'register_sidebars'));
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script(
            'aqualuxe-main',
            get_template_directory_uri() . '/assets/dist/js/main.js',
            array('jquery'),
            AQUALUXE_VERSION,
            true
        );
        
        wp_localize_script('aqualuxe-main', 'aqualuxe_vars', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('aqualuxe_nonce'),
            'fish_tank_settings' => $this->get_fish_tank_settings()
        ));
    }
}

// Initialize theme
AquaLuxe_Theme::get_instance();
?>
\`\`\`

### Custom Post Types
\`\`\`php
<?php
class AquaLuxe_Post_Types {
    public function __construct() {
        add_action('init', array($this, 'register_post_types'));
    }
    
    public function register_post_types() {
        register_post_type('portfolio', array(
            'labels' => array(
                'name' => __('Portfolio', 'aqualuxe'),
                'singular_name' => __('Portfolio Item', 'aqualuxe')
            ),
            'public' => true,
            'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
            'has_archive' => true,
            'rewrite' => array('slug' => 'portfolio')
        ));
    }
}

new AquaLuxe_Post_Types();
?>
\`\`\`

## SCSS Architecture

### File Organization
\`\`\`scss
// main.scss
@import 'utils/variables';
@import 'utils/mixins';
@import 'utils/functions';

@import 'base/reset';
@import 'base/typography';
@import 'base/forms';

@import 'layout/header';
@import 'layout/footer';
@import 'layout/sidebar';

@import 'components/buttons';
@import 'components/fish-tank';
@import 'components/modal';
@import 'components/navigation';

@import 'pages/home';
@import 'pages/shop';
@import 'pages/single';
\`\`\`

### Naming Convention
Follow BEM methodology:
\`\`\`scss
.block {}
.block__element {}
.block--modifier {}
.block__element--modifier {}

// Example
.fish-tank {}
.fish-tank__container {}
.fish-tank__fish {}
.fish-tank--loading {}
.fish-tank__fish--swimming {}
\`\`\`

### Responsive Mixins
\`\`\`scss
@mixin respond-to($breakpoint) {
    @if $breakpoint == 'mobile' {
        @media (max-width: 767px) { @content; }
    }
    @if $breakpoint == 'tablet' {
        @media (min-width: 768px) and (max-width: 1023px) { @content; }
    }
    @if $breakpoint == 'desktop' {
        @media (min-width: 1024px) { @content; }
    }
}

// Usage
.fish-tank {
    height: 100vh;
    
    @include respond-to('mobile') {
        height: 50vh;
    }
}
\`\`\`

## Testing

### Unit Tests
\`\`\`javascript
// tests/components/fishtank.test.js
import { FishTank } from '../../assets/src/js/components/fishtank';

describe('FishTank Component', () => {
    let fishTank;
    let container;
    
    beforeEach(() => {
        container = document.createElement('div');
        container.id = 'fish-tank';
        document.body.appendChild(container);
        
        fishTank = new FishTank();
    });
    
    afterEach(() => {
        fishTank.destroy();
        document.body.removeChild(container);
    });
    
    test('should initialize with default settings', () => {
        expect(fishTank.isInitialized).toBe(true);
        expect(fishTank.options.fishCount).toBe(10);
    });
    
    test('should add fish to tank', () => {
        const initialCount = fishTank.fish.length;
        fishTank.addFish(5);
        expect(fishTank.fish.length).toBe(initialCount + 5);
    });
});
\`\`\`

### PHP Tests
\`\`\`php
<?php
class Test_AquaLuxe_Theme extends WP_UnitTestCase {
    public function test_theme_initialization() {
        $theme = AquaLuxe_Theme::get_instance();
        $this->assertInstanceOf('AquaLuxe_Theme', $theme);
    }
    
    public function test_scripts_enqueued() {
        do_action('wp_enqueue_scripts');
        $this->assertTrue(wp_script_is('aqualuxe-main'));
    }
    
    public function test_fish_tank_settings() {
        $settings = AquaLuxe_Theme::get_instance()->get_fish_tank_settings();
        $this->assertArrayHasKey('fish_count', $settings);
        $this->assertIsInt($settings['fish_count']);
    }
}
?>
\`\`\`

## Performance Optimization

### JavaScript Optimization
1. **Code Splitting**
   \`\`\`javascript
   // Lazy load components
   const lazyLoadFishTank = () => {
       import('./components/fishtank').then(({ FishTank }) => {
           new FishTank();
       });
   };
   
   // Load only when needed
   if (document.querySelector('.fish-tank')) {
       lazyLoadFishTank();
   }
   \`\`\`

2. **Debouncing**
   \`\`\`javascript
   const debounce = (func, wait) => {
       let timeout;
       return function executedFunction(...args) {
           const later = () => {
               clearTimeout(timeout);
               func(...args);
           };
           clearTimeout(timeout);
           timeout = setTimeout(later, wait);
       };
   };
   
   window.addEventListener('resize', debounce(handleResize, 250));
   \`\`\`

### CSS Optimization
1. **Critical CSS**
   - Inline critical CSS for above-the-fold content
   - Load non-critical CSS asynchronously

2. **CSS Custom Properties**
   \`\`\`css
   :root {
       --color-primary: #0ea5e9;
       --font-size-base: 1rem;
   }
   
   .button {
       background-color: var(--color-primary);
       font-size: var(--font-size-base);
   }
   \`\`\`

### PHP Optimization
1. **Caching**
   \`\`\`php
   function get_cached_fish_tank_settings() {
       $cache_key = 'aqualuxe_fish_tank_settings';
       $settings = wp_cache_get($cache_key);
       
       if (false === $settings) {
           $settings = calculate_fish_tank_settings();
           wp_cache_set($cache_key, $settings, '', HOUR_IN_SECONDS);
       }
       
       return $settings;
   }
   \`\`\`

2. **Conditional Loading**
   \`\`\`php
   function enqueue_fish_tank_assets() {
       if (is_front_page() || has_shortcode(get_post()->post_content, 'fish_tank')) {
           wp_enqueue_script('three-js');
           wp_enqueue_script('fish-tank');
       }
   }
   \`\`\`

## Deployment

### Production Build
\`\`\`bash
npm run build
\`\`\`

### Theme Package
\`\`\`bash
npm run package
\`\`\`

### Deployment Script
\`\`\`bash
npm run deploy:staging
npm run deploy:production
\`\`\`

## Contributing

### Code Standards
- Follow WordPress Coding Standards
- Use ESLint for JavaScript
- Use Stylelint for CSS/SCSS
- Write unit tests for new features

### Git Workflow
1. Create feature branch from \`develop\`
2. Make changes with descriptive commits
3. Open pull request to \`develop\`
4. Code review and testing
5. Merge to \`develop\`
6. Release to \`main\`

### Commit Messages
\`\`\`
feat: add fish tank interaction controls
fix: resolve mobile navigation issue
docs: update API documentation
style: improve button hover animations
refactor: optimize fish movement algorithm
test: add unit tests for modal component
\`\`\`
`;
        
        fs.writeFileSync(path.join(this.docsDir, 'developer.md'), developerContent);
    }
}

// Run the documentation generator
if (require.main === module) {
    const generator = new DocumentationGenerator();
    generator.generate();
}

module.exports = DocumentationGenerator;
