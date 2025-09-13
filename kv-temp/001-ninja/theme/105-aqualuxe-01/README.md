# AquaLuxe WordPress Theme

**Version:** 1.0.0  
**Tagline:** Bringing elegance to aquatic life – globally  
**License:** GPL v2 or later  

## Description

AquaLuxe is a comprehensive, modular WordPress theme designed specifically for luxury aquatic retail businesses. Built with modern web standards and best practices, it provides a complete solution for aquarium stores, aquatic service providers, and marine life enthusiasts.

## Features

### 🏗️ **Modular Architecture**
- **Clean separation of concerns** with core/ and modules/ directories
- **Loosely coupled modules** that can be enabled/disabled independently
- **SOLID principles** implementation throughout the codebase
- **Dependency injection** and proper abstraction layers

### 🌐 **Multilingual & Multicurrency Support**
- Built-in multilingual functionality with 7+ languages
- Automatic language detection from browser preferences
- Persistent user language preferences
- RTL language support
- Multicurrency readiness for international commerce

### 🛒 **WooCommerce Integration**
- **Dual-state architecture** - works with or without WooCommerce
- Graceful fallbacks when WooCommerce is inactive
- Support for all product types (simple, variable, grouped, digital)
- Advanced product filtering and search
- Wishlist functionality
- Quick view modals
- Optimized checkout and cart processes

### 🎨 **Design & User Experience**
- **Mobile-first responsive design**
- **Dark/Light mode toggle** with persistent preferences
- **Accessibility compliant** (WCAG 2.1 AA)
- **Semantic HTML5** markup
- **Progressive enhancement** - fully functional without JavaScript
- **Micro-interactions** and smooth animations

### 📦 **Content Management**
- **Custom Post Types**: Services, Events, Bookings
- **Custom Taxonomies**: Service categories, Event types
- **Advanced Custom Fields** integration
- **Demo Content Importer** with ACID transactions
- **Comprehensive Admin Dashboard**

### 🔧 **Professional Services**
- Service booking and scheduling system
- Inquiry forms with AJAX submissions
- Service categories and tagging
- Pricing and duration management
- Location and availability tracking

### 🚀 **Performance & SEO**
- **Asset optimization** with Webpack and Laravel Mix
- **Lazy loading** for images and content
- **Critical CSS inlining**
- **Schema.org markup** for rich snippets
- **Open Graph** and Twitter Card support
- **XML Sitemap** generation
- **Minified and cached assets**

### 🔒 **Security & Compliance**
- **Input sanitization** and output escaping
- **CSRF protection** with nonces
- **XSS and SQL injection prevention**
- **Secure coding practices**
- **Privacy-compliant** cookie handling

### 🛠️ **Development Features**
- **Modern build tools** (Webpack, Babel, Sass, Tailwind CSS)
- **Automated testing** setup (Jest, PHPUnit)
- **Code linting** (ESLint, Stylelint, PHPCS)
- **Version control** integration
- **CI/CD pipeline** configuration

## Installation

### Requirements
- **WordPress:** 5.0+
- **PHP:** 7.4+
- **Node.js:** 16.0+
- **NPM:** 8.0+

### Quick Start

1. **Download and Install**
   ```bash
   # Download the theme
   git clone https://github.com/kasunvimarshana/aqualuxe.git
   
   # Navigate to theme directory
   cd aqualuxe
   
   # Install dependencies
   npm install
   
   # Build assets
   npm run build
   ```

2. **Activate Theme**
   - Upload the theme to your `/wp-content/themes/` directory
   - Activate "AquaLuxe" from WordPress Admin → Appearance → Themes

3. **Import Demo Content**
   - Go to Appearance → Demo Importer
   - Select "Full Import" or choose selective options
   - Click "Start Import" and wait for completion

4. **Configure Settings**
   - Customize your site via Appearance → Customize
   - Set up menus in Appearance → Menus
   - Configure widgets in Appearance → Widgets

## File Structure

```
aqualuxe/
├── assets/
│   ├── src/                    # Source assets
│   │   ├── js/                # JavaScript files
│   │   │   ├── modules/       # Module-specific JS
│   │   │   └── admin/         # Admin interface JS
│   │   └── scss/              # Sass stylesheets
│   └── dist/                  # Compiled assets (auto-generated)
├── core/
│   ├── classes/               # Core PHP classes
│   │   ├── ThemeCore.php     # Main theme class
│   │   └── ModuleManager.php  # Module management
│   └── functions/             # Core functions
├── modules/                   # Feature modules
│   ├── multilingual/         # Multilingual support
│   ├── services/             # Professional services
│   ├── dark-mode/            # Dark/light mode toggle
│   └── [other-modules]/      # Additional modules
├── templates/                 # Template files
│   ├── parts/                # Template parts
│   ├── pages/                # Page templates
│   └── woocommerce/          # WooCommerce overrides
├── inc/                      # Additional includes
│   ├── admin/                # Admin functionality
│   ├── customizer/           # Theme customizer
│   ├── demo-importer/        # Demo content importer
│   ├── config.php            # Theme configuration
│   ├── custom-post-types.php # Custom post types
│   └── custom-taxonomies.php # Custom taxonomies
├── languages/                # Translation files
├── docs/                     # Documentation
├── tests/                    # Test files
└── demo-content/            # Demo content files
```

## Modules

### Available Modules

| Module | Description | Status |
|--------|-------------|--------|
| **Multilingual** | Multi-language support with auto-detection | ✅ Active |
| **Dark Mode** | Toggle between light and dark themes | ✅ Active |
| **Services** | Professional service management | ✅ Active |
| **Subscriptions** | Recurring payment and memberships | 🚧 Planned |
| **Bookings** | Appointment and scheduling system | 🚧 Planned |
| **Events** | Event management and ticketing | 🚧 Planned |
| **Auctions** | Auction and trade-in functionality | 🚧 Planned |
| **Wholesale** | B2B pricing and features | 🚧 Planned |
| **Franchise** | Partner portal and management | 🚧 Planned |
| **Sustainability** | R&D and eco-initiatives | 🚧 Planned |
| **Affiliates** | Referral and affiliate system | 🚧 Planned |
| **Marketplace** | Multi-vendor platform | 🚧 Planned |

## Development

### Build Commands

```bash
# Development build with watching
npm run dev

# Production build (minified)
npm run build

# Watch files for changes
npm run watch

# Hot reload development server
npm run hot

# Lint code
npm run lint

# Run tests
npm run test

# Clean dist folder
npm run clean
```

### Coding Standards

- **PHP:** WordPress Coding Standards, PSR-4 autoloading
- **JavaScript:** ESLint with WordPress configuration
- **CSS/Sass:** Stylelint with standard configuration
- **File Naming:** snake_case for PHP, kebab-case for assets
- **Comments:** Comprehensive inline documentation

## License

This theme is licensed under the GPL v2 or later.

```
Copyright (C) 2024 AquaLuxe Team

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

---

**AquaLuxe** - Premium WordPress Theme for Aquatic Retail  
*Bringing elegance to aquatic life – globally*
