# AquaLuxe WordPress Theme

AquaLuxe is a premium WordPress theme designed specifically for ornamental fish farming businesses targeting both local and international markets. The theme combines elegant aquatic visuals with refined typography and smooth micro-interactions, emphasizing product rarity, quality, and exclusivity.

## Features

### Core Features
- **Responsive Design**: Mobile-first approach using Tailwind CSS
- **Dark Mode**: Toggle between light and dark themes with persistent preferences
- **WooCommerce Integration**: Full support for e-commerce functionality
- **Multilingual Support**: Compatible with WPML and Polylang
- **Custom Post Types**: Services, Events, Testimonials, Team Members, Projects, FAQs
- **Custom Taxonomies**: Categories and tags for all custom post types
- **Custom Widgets**: Contact info, social icons, recent posts, featured products, etc.

### Business-Specific Features
- **Product Showcase**: Highlight rare and exotic fish with detailed specifications
- **Service Listings**: Display aquarium maintenance, consulting, and other services
- **Event Management**: Promote auctions, exhibitions, and educational workshops
- **Team Profiles**: Showcase experts with their specializations and contact info
- **Project Gallery**: Display custom aquarium installations and special projects
- **Testimonials**: Build trust with customer reviews and success stories
- **FAQ System**: Comprehensive knowledge base for customer support

## Installation

1. Upload the `aqualuxe` folder to the `/wp-content/themes/` directory
2. Activate the theme through the 'Themes' menu in WordPress
3. Configure theme settings via Appearance > Customize

## Theme Structure

```
aqualuxe/
├── assets/
│   ├── css/
│   │   ├── main.css
│   │   └── tailwind.css
│   ├── fonts/
│   ├── images/
│   └── js/
│       ├── dark-mode.js
│       ├── main.js
│       └── navigation.js
├── inc/
│   ├── blocks.php
│   ├── custom-post-types.php
│   ├── custom-taxonomies.php
│   ├── customizer/
│   │   └── customizer.php
│   ├── dark-mode.php
│   ├── helpers/
│   │   └── helpers.php
│   ├── multilingual.php
│   ├── shortcodes.php
│   ├── template-functions.php
│   ├── template-tags.php
│   ├── widgets/
│   │   └── widgets.php
│   └── woocommerce.php
├── languages/
│   └── aqualuxe.pot
├── template-parts/
│   ├── blocks/
│   ├── content/
│   │   ├── content-none.php
│   │   ├── content-page.php
│   │   ├── content-search.php
│   │   └── content.php
│   ├── footer/
│   └── header/
├── templates/
├── woocommerce/
│   ├── archive-product.php
│   ├── cart/
│   │   └── cart.php
│   ├── checkout/
│   │   └── form-checkout.php
│   ├── content-product.php
│   ├── global/
│   │   └── quantity-input.php
│   ├── myaccount/
│   │   └── my-account.php
│   └── single-product.php
├── 404.php
├── archive-events.php
├── archive-faqs.php
├── archive-projects.php
├── archive-services.php
├── archive-team.php
├── archive-testimonials.php
├── archive.php
├── comments.php
├── footer.php
├── functions.php
├── header.php
├── index.php
├── page.php
├── README.md
├── screenshot.png
├── search.php
├── sidebar.php
├── single-events.php
├── single-faqs.php
├── single-projects.php
├── single-services.php
├── single-team.php
├── single-testimonials.php
├── single.php
└── style.css
```

## Customization

### Theme Customizer
Navigate to Appearance > Customize to access the following settings:
- Site Identity: Logo, site title, tagline, and favicon
- Colors: Primary, secondary, and accent color schemes
- Typography: Font families, sizes, and weights
- Layout: Container width, sidebar positions, etc.
- Header: Navigation style, sticky header, etc.
- Footer: Widget areas, copyright text, etc.
- WooCommerce: Shop layout, product cards, etc.

### Custom Post Types
The theme includes the following custom post types:
- **Services**: For aquarium maintenance, consulting, etc.
- **Events**: For auctions, exhibitions, workshops, etc.
- **Testimonials**: For customer reviews and success stories
- **Team**: For staff profiles and experts
- **Projects**: For custom aquarium installations
- **FAQs**: For frequently asked questions

### Custom Taxonomies
Each custom post type has its own taxonomies:
- **Service Categories & Tags**: Group and filter services
- **Event Categories & Tags**: Organize events by type
- **Testimonial Categories & Tags**: Group testimonials by source or topic
- **Team Departments**: Organize team members by department
- **Project Categories & Types**: Categorize projects by type or client
- **FAQ Categories & Tags**: Organize FAQs by topic

## WooCommerce Integration

AquaLuxe is fully compatible with WooCommerce and includes custom templates for:
- Product archives
- Single product pages
- Shopping cart
- Checkout
- My Account

The theme also adds custom fields for ornamental fish products:
- Origin
- Care Level
- Tank Size
- Adult Size
- Water Conditions
- Diet
- Breeding
- Compatibility
- Rarity Level

## Advanced Features

### Dark Mode
The theme includes a toggle for switching between light and dark modes. The user's preference is saved using localStorage.

### Multilingual Support
AquaLuxe is compatible with popular translation plugins like WPML and Polylang. All theme strings are translation-ready.

### Performance Optimization
The theme is optimized for performance with:
- Tailwind CSS for minimal CSS footprint
- Deferred JavaScript loading
- Lazy loading of images
- Responsive image handling

## Support

For theme support, please contact us at support@aqualuxe.com or visit our [support forum](https://aqualuxe.com/support).

## Credits

- [Tailwind CSS](https://tailwindcss.com/)
- [Alpine.js](https://alpinejs.dev/)
- [Swiper](https://swiperjs.com/)

## License

This theme is licensed under the GNU General Public License v2 or later.