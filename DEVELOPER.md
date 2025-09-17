# AquaLuxe Theme - Developer Documentation

## Architecture Overview

AquaLuxe implements Clean Architecture principles with clear separation of concerns and SOLID design patterns.

## Directory Structure

```
aqualuxe-web/
├── assets/
│   ├── src/                    # Source assets
│   │   ├── scss/              # Sass stylesheets
│   │   ├── js/                # JavaScript files
│   │   ├── images/            # Source images
│   │   └── fonts/             # Font files
│   └── dist/                  # Compiled assets
├── core/                      # Core theme architecture
│   ├── abstracts/             # Abstract base classes
│   ├── interfaces/            # Interface definitions
│   ├── class-theme-setup.php  # Main theme setup
│   └── class-service-container.php # DI container
├── modules/                   # Feature modules
│   ├── multilingual/          # Language features
│   ├── dark-mode/             # Theme switching
│   ├── performance/           # Optimizations
│   ├── security/              # Security features
│   ├── seo/                   # SEO enhancements
│   ├── services/              # Service management
│   ├── subscriptions/         # Membership systems
│   ├── bookings/              # Appointment scheduling
│   ├── events/                # Event management
│   ├── auctions/              # Auction functionality
│   ├── wholesale/             # B2B features
│   ├── franchise/             # Multi-location management
│   ├── rd/                    # R&D projects
│   ├── affiliates/            # Referral programs
│   └── multivendor/           # Marketplace functionality
├── inc/                       # Theme includes
│   ├── admin/                 # Admin functionality
│   ├── woocommerce/           # WooCommerce integration
│   ├── class-accessibility-manager.php
│   ├── class-seo-manager.php
│   ├── class-file-organizer.php
│   ├── class-code-review.php
│   ├── custom-post-types.php
│   ├── custom-taxonomies.php
│   ├── meta-fields.php
│   ├── template-functions.php
│   ├── template-hooks.php
│   └── customizer.php
├── templates/                 # Template files
│   ├── components/            # Reusable components
│   └── partials/              # Template partials
├── woocommerce/               # WooCommerce templates
├── functions.php              # Main functions file
├── style.css                  # Theme stylesheet
├── package.json               # Node dependencies
├── webpack.mix.js             # Build configuration
├── tailwind.config.js         # Tailwind configuration
└── README.md                  # Theme documentation
```

## Clean Architecture Implementation

### Domain Layer

Located in `core/interfaces/` and `core/abstracts/`

**Interfaces:**
- `Repository_Interface` - Data access abstraction
- `Service_Interface` - Business logic services
- `Entity_Interface` - Domain entities
- `Value_Object_Interface` - Immutable value objects
- `Specification_Interface` - Business rules

**Abstract Classes:**
- `Abstract_Entity` - Base entity implementation
- `Abstract_Value_Object` - Base value object
- `Abstract_Repository` - Repository base class
- `Abstract_Service` - Service base class
- `Abstract_Specification` - Specification pattern

### Application Layer

Business logic and use cases implemented in modules.

### Infrastructure Layer

WordPress-specific implementations and external integrations.

### Presentation Layer

Templates, components, and user interface elements.

## Module System

### Module Structure

Each module follows a consistent structure:

```
modules/module-name/
├── class-module.php           # Main module class
├── assets/                    # Module-specific assets
│   ├── scss/
│   └── js/
├── templates/                 # Module templates
├── includes/                  # Module includes
└── README.md                  # Module documentation
```

### Creating a Module

1. **Create module directory**
   ```bash
   mkdir modules/your-module
   ```

2. **Create module class**
   ```php
   <?php
   namespace AquaLuxe\Modules\Your_Module;
   
   use AquaLuxe\Core\Abstracts\Abstract_Module;
   
   class Module extends Abstract_Module {
       protected $name = 'Your Module';
       
       public function init() {
           // Module initialization
           add_action('init', array($this, 'register_post_types'));
           add_action('wp_enqueue_scripts', array($this, 'enqueue_assets'));
       }
       
       public function register_post_types() {
           // Register custom post types
       }
       
       public function enqueue_assets() {
           // Enqueue module assets
       }
   }
   ```

3. **Register module**
   ```php
   // In core/class-theme-setup.php
   if (get_theme_mod('aqualuxe_enable_your_module', true)) {
       \AquaLuxe\Modules\Your_Module\Module::get_instance();
   }
   ```

### Module Guidelines

- **Single Responsibility**: Each module should handle one feature
- **Loose Coupling**: Modules should not depend on each other
- **High Cohesion**: Related functionality should be grouped together
- **Toggleable**: Modules should be easily enabled/disabled
- **Documented**: Include README.md for each module

## Service Container

AquaLuxe includes a simple dependency injection container.

### Registering Services

```php
$container = AquaLuxe_Service_Container::get_instance();

// Register a class
$container->register('cache', 'WP_Object_Cache');

// Register with closure
$container->register('logger', function() {
    return new Custom_Logger();
});

// Register singleton
$container->register('api_client', 'API_Client', true);
```

### Using Services

```php
// Get service
$cache = $container->get('cache');

// Check if service exists
if ($container->has('logger')) {
    $logger = $container->get('logger');
}
```

## Development Workflow

### Setup Development Environment

1. **Clone repository**
   ```bash
   git clone https://github.com/kasunvimarshana/aqualuxe-web.git
   cd aqualuxe-web
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Start development**
   ```bash
   # Start Docker environment
   docker-compose up -d
   
   # Watch for changes
   npm run watch
   ```

### Build Process

**Development Build:**
```bash
npm run development
# or
npm run dev
```

**Production Build:**
```bash
npm run production
# or
npm run prod
```

**Watch Mode:**
```bash
npm run watch
```

**Hot Reload:**
```bash
npm run hot
```

### Asset Pipeline

Assets are processed through Laravel Mix:

- **SCSS** → CSS (with Tailwind, autoprefixer)
- **JavaScript** → Minified JS (with Babel)
- **Images** → Optimized images
- **Fonts** → Web fonts

### Code Standards

**PHP Standards:**
- WordPress Coding Standards
- PSR-4 autoloading
- SOLID principles
- Comprehensive PHPDoc

**JavaScript Standards:**
- ESLint configuration
- Modern ES6+ syntax
- Modular architecture

**CSS Standards:**
- BEM methodology
- Tailwind CSS utility classes
- SCSS organization

### Testing

**PHP Testing:**
```bash
# Syntax checking
find . -name "*.php" -exec php -l {} \;

# WordPress standards
phpcs --standard=WordPress .
```

**JavaScript Testing:**
```bash
# Linting
npm run lint:js

# Testing
npm run test
```

**CSS Testing:**
```bash
# Linting
npm run lint:css
```

## Custom Post Types

### Registering Post Types

Custom post types are managed in `inc/custom-post-types.php`:

```php
namespace AquaLuxe\PostTypes;

class Post_Types_Manager {
    public function register_post_types() {
        $this->register_services();
        $this->register_products();
        // ... other post types
    }
    
    private function register_services() {
        $args = array(
            'labels' => $this->get_service_labels(),
            'public' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'rewrite' => array('slug' => 'services'),
            'has_archive' => true,
        );
        
        register_post_type('aqualuxe_service', $args);
    }
}
```

### Available Post Types

- `aqualuxe_service` - Services
- `aqualuxe_product` - Products (when WooCommerce inactive)
- `aqualuxe_event` - Events
- `aqualuxe_booking` - Bookings
- `aqualuxe_testimonial` - Testimonials
- `aqualuxe_portfolio` - Portfolio items
- `aqualuxe_team_member` - Team members
- `aqualuxe_faq` - FAQs

## Custom Fields

### Meta Fields System

Custom fields are managed through `inc/meta-fields.php`:

```php
class Meta_Fields_Manager {
    public function add_meta_boxes() {
        add_meta_box(
            'service_details',
            __('Service Details', 'aqualuxe'),
            array($this, 'service_details_callback'),
            'aqualuxe_service'
        );
    }
    
    public function service_details_callback($post) {
        // Render meta fields
        $price = get_post_meta($post->ID, '_service_price', true);
        $duration = get_post_meta($post->ID, '_service_duration', true);
        
        // Output fields HTML
    }
}
```

### Common Meta Fields

- `_aqualuxe_meta_description` - SEO description
- `_aqualuxe_meta_keywords` - SEO keywords
- `_service_price` - Service pricing
- `_service_duration` - Service duration
- `_product_specifications` - Product specs
- `_event_date` - Event date and time

## WooCommerce Integration

### Dual-State Architecture

AquaLuxe works with or without WooCommerce:

**With WooCommerce:**
- Uses WooCommerce products
- Full e-commerce functionality
- Custom shop layouts

**Without WooCommerce:**
- Custom product post type
- Basic product display
- Contact forms for inquiries

### Template Overrides

WooCommerce templates are customized in `woocommerce/` directory:

```
woocommerce/
├── archive-product.php
├── single-product.php
├── cart/
├── checkout/
├── myaccount/
└── global/
```

### Hooks and Filters

```php
// Remove default WooCommerce styles
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Add custom product data
add_action('woocommerce_single_product_summary', 'custom_product_info', 25);

// Customize add to cart button
add_filter('woocommerce_product_add_to_cart_text', 'custom_add_to_cart_text');
```

## Customizer Integration

### Adding Customizer Options

```php
class Customizer_Manager {
    public function register_settings($wp_customize) {
        // Add section
        $wp_customize->add_section('aqualuxe_colors', array(
            'title' => __('Colors', 'aqualuxe'),
            'priority' => 30,
        ));
        
        // Add setting
        $wp_customize->add_setting('aqualuxe_primary_color', array(
            'default' => '#0073aa',
            'sanitize_callback' => 'sanitize_hex_color',
        ));
        
        // Add control
        $wp_customize->add_control(new WP_Customize_Color_Control(
            $wp_customize,
            'aqualuxe_primary_color',
            array(
                'label' => __('Primary Color', 'aqualuxe'),
                'section' => 'aqualuxe_colors',
            )
        ));
    }
}
```

## Accessibility Implementation

### WCAG 2.1 AA Compliance

**Features:**
- Keyboard navigation
- Screen reader support
- Color contrast compliance
- Focus indicators
- Skip links
- ARIA attributes

**Testing:**
```php
// Accessibility audit
$audit = new \AquaLuxe\Core\Accessibility_Manager();
$results = $audit->run_accessibility_audit();
```

### Best Practices

- Use semantic HTML5 elements
- Provide alt text for images
- Ensure keyboard accessibility
- Test with screen readers
- Maintain color contrast ratios

## SEO Implementation

### Schema.org Markup

Structured data is automatically generated:

```php
class SEO_Manager {
    public function add_structured_data() {
        $schemas = array();
        
        // Website schema
        $schemas[] = $this->get_website_schema();
        
        // Article schema for posts
        if (is_single()) {
            $schemas[] = $this->get_article_schema();
        }
        
        // Output JSON-LD
        echo '<script type="application/ld+json">';
        echo wp_json_encode($schemas);
        echo '</script>';
    }
}
```

### OpenGraph Tags

Social media meta tags are automatically generated:

```php
public function add_social_meta_tags() {
    echo '<meta property="og:title" content="' . $this->get_og_title() . '">';
    echo '<meta property="og:description" content="' . $this->get_meta_description() . '">';
    echo '<meta property="og:image" content="' . $this->get_og_image() . '">';
    // ... more tags
}
```

## Performance Optimization

### Caching Strategy

**Object Caching:**
```php
// Set cache
wp_cache_set('key', $data, 'group', 3600);

// Get cache
$data = wp_cache_get('key', 'group');
```

**Transient Caching:**
```php
// Set transient
set_transient('aqualuxe_data', $data, HOUR_IN_SECONDS);

// Get transient
$data = get_transient('aqualuxe_data');
```

### Asset Optimization

- **Minification**: CSS/JS automatically minified
- **Compression**: Gzip enabled
- **Lazy Loading**: Images lazy loaded
- **Critical CSS**: Above-fold CSS inlined
- **Resource Hints**: Preconnect/prefetch directives

## Security Features

### Input Validation

```php
// Sanitize input
$clean_data = sanitize_text_field($_POST['data']);

// Validate email
if (!is_email($email)) {
    wp_die('Invalid email');
}

// Escape output
echo esc_html($user_input);
echo esc_url($url);
echo esc_attr($attribute);
```

### Nonce Protection

```php
// Create nonce
wp_nonce_field('action_name', 'nonce_name');

// Verify nonce
if (!wp_verify_nonce($_POST['nonce_name'], 'action_name')) {
    wp_die('Security check failed');
}
```

### CSRF Protection

All forms include CSRF tokens and verification.

## Database Schema

### Custom Tables

If needed, custom tables can be created:

```php
class Database_Manager {
    public function create_tables() {
        global $wpdb;
        
        $table_name = $wpdb->prefix . 'aqualuxe_data';
        
        $charset_collate = $wpdb->get_charset_collate();
        
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name tinytext NOT NULL,
            data text NOT NULL,
            created datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
```

## API Integration

### REST API Endpoints

Custom endpoints can be registered:

```php
class API_Manager {
    public function register_routes() {
        register_rest_route('aqualuxe/v1', '/services', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_services'),
            'permission_callback' => '__return_true',
        ));
    }
    
    public function get_services($request) {
        $services = get_posts(array('post_type' => 'aqualuxe_service'));
        return rest_ensure_response($services);
    }
}
```

## Deployment

### Production Checklist

1. **Build assets**: `npm run production`
2. **Remove development files**:
   - `node_modules/`
   - `.git/`
   - Development configs
3. **Enable caching**
4. **Configure CDN**
5. **Set up monitoring**
6. **SSL certificate**
7. **Security hardening**

### CI/CD Pipeline

Example GitHub Actions workflow:

```yaml
name: Deploy
on:
  push:
    branches: [main]

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup Node.js
        uses: actions/setup-node@v2
        with:
          node-version: '18'
      - name: Install dependencies
        run: npm install
      - name: Build assets
        run: npm run production
      - name: Deploy
        run: # Your deployment script
```

## Troubleshooting

### Common Issues

**1. Build Failures**
- Check Node.js version
- Clear npm cache
- Reinstall dependencies

**2. Module Conflicts**
- Disable modules one by one
- Check error logs
- Use debug mode

**3. Performance Issues**
- Enable caching
- Optimize images
- Review slow queries

### Debug Tools

**Theme Tools:**
- **Appearance → Code Review**
- **Appearance → File Organizer**
- **Appearance → Accessibility**
- **Appearance → SEO Tools**

**WordPress Tools:**
- Site Health
- Query Monitor plugin
- Debug Bar plugin

## Contributing

### Code Style

Follow these standards:
- WordPress Coding Standards
- PSR-4 autoloading
- Comprehensive documentation
- Unit tests for complex logic

### Submission Process

1. Fork the repository
2. Create feature branch
3. Make changes with tests
4. Submit pull request
5. Code review process

## Resources

- [WordPress Developer Handbook](https://developer.wordpress.org/)
- [WooCommerce Developer Docs](https://woocommerce.github.io/code-reference/)
- [Clean Architecture Guide](https://blog.cleancoder.com/uncle-bob/2012/08/13/the-clean-architecture.html)
- [WCAG 2.1 Guidelines](https://www.w3.org/WAI/WCAG21/quickref/)
- [Schema.org Documentation](https://schema.org/)

---

**Last Updated**: September 2024  
**Version**: 1.0.0