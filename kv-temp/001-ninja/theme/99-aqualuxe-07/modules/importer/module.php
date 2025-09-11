<?php
namespace AquaLuxe\Modules\Importer;

class Module
{
    private $progress = 0;
    private $steps = [];
    private $current_step = 0;

    public function boot(): void
    {
        add_action('wp_ajax_aqualuxe_demo_import', [$this, 'handle_demo_import']);
        add_action('wp_ajax_aqualuxe_demo_flush', [$this, 'handle_demo_flush']);
        add_action('admin_menu', [$this, 'add_admin_menu'], 15);
        add_action('admin_enqueue_scripts', [$this, 'admin_assets']);
    }

    public function add_admin_menu(): void
    {
        add_submenu_page(
            'themes.php',
            __('AquaLuxe Demo Importer', 'aqualuxe'),
            __('Demo Importer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-importer',
            [$this, 'admin_page']
        );
    }

    public function admin_page(): void
    {
        ?>
        <div class="wrap aqualuxe-admin">
            <div class="admin-header">
                <h1><?php esc_html_e('AquaLuxe Demo Content Importer', 'aqualuxe'); ?></h1>
                <p><?php esc_html_e('Import comprehensive demo content including products, pages, and sample data for the AquaLuxe aquatic business theme.', 'aqualuxe'); ?></p>
            </div>

            <div class="admin-content">
                <div class="import-options">
                    <h2><?php esc_html_e('Import Options', 'aqualuxe'); ?></h2>
                    
                    <div class="import-actions">
                        <button type="button" class="button button-primary demo-import-btn">
                            <?php esc_html_e('Import Demo Content', 'aqualuxe'); ?>
                        </button>
                        
                        <button type="button" class="button button-secondary demo-flush-btn">
                            <?php esc_html_e('Flush Demo Content', 'aqualuxe'); ?>
                        </button>
                    </div>

                    <div class="import-progress" style="display: none;">
                        <div class="progress-container">
                            <div class="progress-bar"></div>
                        </div>
                        <p class="import-status"><?php esc_html_e('Preparing import...', 'aqualuxe'); ?></p>
                    </div>
                </div>

                <div class="import-details">
                    <h3><?php esc_html_e('What will be imported:', 'aqualuxe'); ?></h3>
                    <ul>
                        <li><?php esc_html_e('✓ Core pages (Home, About, Services, Contact, Blog)', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('✓ Aquatic industry products (Fish, Plants, Equipment)', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('✓ Blog posts about aquarium care and maintenance', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('✓ Customer testimonials and reviews', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('✓ Service offerings and pricing', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('✓ Sample images and media', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('✓ Navigation menus and widgets', 'aqualuxe'); ?></li>
                        <li><?php esc_html_e('✓ Theme customizer settings', 'aqualuxe'); ?></li>
                    </ul>
                    
                    <p><strong><?php esc_html_e('Note:', 'aqualuxe'); ?></strong> <?php esc_html_e('Demo content can be safely removed using the flush function.', 'aqualuxe'); ?></p>
                </div>
            </div>
        </div>
        <?php
    }

    public function admin_assets($hook): void
    {
        if ('appearance_page_aqualuxe-demo-importer' !== $hook) {
            return;
        }

        // Admin script is already enqueued by the main theme
    }

    public function handle_demo_import(): void
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Unauthorized access.', 'aqualuxe')]);
        }

        if (!check_admin_referer('aqualuxe_nonce', 'nonce')) {
            wp_send_json_error(['message' => __('Invalid security token.', 'aqualuxe')]);
        }

        $step = sanitize_text_field($_POST['step'] ?? 'prepare');
        
        $this->init_import_steps();
        
        switch ($step) {
            case 'prepare':
                $result = $this->prepare_import();
                break;
            case 'pages':
                $result = $this->import_pages();
                break;
            case 'products':
                $result = $this->import_products();
                break;
            case 'blog':
                $result = $this->import_blog_posts();
                break;
            case 'testimonials':
                $result = $this->import_testimonials();
                break;
            case 'services':
                $result = $this->import_services();
                break;
            case 'menus':
                $result = $this->setup_menus();
                break;
            case 'customizer':
                $result = $this->setup_customizer();
                break;
            case 'finalize':
                $result = $this->finalize_import();
                break;
            default:
                wp_send_json_error(['message' => __('Invalid import step.', 'aqualuxe')]);
        }

        wp_send_json_success($result);
    }

    public function handle_demo_flush(): void
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error(['message' => __('Unauthorized access.', 'aqualuxe')]);
        }

        if (!check_admin_referer('aqualuxe_nonce', 'nonce')) {
            wp_send_json_error(['message' => __('Invalid security token.', 'aqualuxe')]);
        }

        $this->flush_demo_content();
        
        wp_send_json_success(['message' => __('Demo content flushed successfully.', 'aqualuxe')]);
    }

    private function init_import_steps(): void
    {
        $this->steps = [
            'prepare' => __('Preparing import...', 'aqualuxe'),
            'pages' => __('Creating core pages...', 'aqualuxe'),
            'products' => __('Importing aquatic products...', 'aqualuxe'),
            'blog' => __('Adding blog content...', 'aqualuxe'),
            'testimonials' => __('Creating testimonials...', 'aqualuxe'),
            'services' => __('Setting up services...', 'aqualuxe'),
            'menus' => __('Configuring navigation...', 'aqualuxe'),
            'customizer' => __('Applying theme settings...', 'aqualuxe'),
            'finalize' => __('Finalizing import...', 'aqualuxe'),
        ];
    }

    private function prepare_import(): array
    {
        // Check if WooCommerce is active
        $has_woocommerce = class_exists('WooCommerce');
        
        return [
            'progress' => 10,
            'status' => __('Import preparation complete.', 'aqualuxe'),
            'next_step' => 'pages'
        ];
    }

    private function import_pages(): array
    {
        $pages = [
            'home' => [
                'title' => __('Home', 'aqualuxe'),
                'content' => $this->get_home_content(),
                'template' => 'front-page'
            ],
            'about' => [
                'title' => __('About Us', 'aqualuxe'),
                'content' => $this->get_about_content(),
                'slug' => 'about'
            ],
            'services' => [
                'title' => __('Our Services', 'aqualuxe'),
                'content' => $this->get_services_content(),
                'slug' => 'services'
            ],
            'contact' => [
                'title' => __('Contact Us', 'aqualuxe'),
                'content' => $this->get_contact_content(),
                'slug' => 'contact'
            ],
            'faq' => [
                'title' => __('Frequently Asked Questions', 'aqualuxe'),
                'content' => $this->get_faq_content(),
                'slug' => 'faq'
            ],
            'privacy' => [
                'title' => __('Privacy Policy', 'aqualuxe'),
                'content' => $this->get_privacy_content(),
                'slug' => 'privacy-policy'
            ],
            'terms' => [
                'title' => __('Terms of Service', 'aqualuxe'),
                'content' => $this->get_terms_content(),
                'slug' => 'terms'
            ],
            'shipping' => [
                'title' => __('Shipping & Returns', 'aqualuxe'),
                'content' => $this->get_shipping_content(),
                'slug' => 'shipping-returns'
            ]
        ];

        foreach ($pages as $key => $page) {
            $page_id = wp_insert_post([
                'post_title' => $page['title'],
                'post_content' => $page['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_name' => $page['slug'] ?? sanitize_title($page['title']),
                'meta_input' => [
                    '_aqualuxe_demo' => 1,
                    '_wp_page_template' => $page['template'] ?? 'default'
                ]
            ]);

            if ($key === 'home') {
                update_option('page_on_front', $page_id);
                update_option('show_on_front', 'page');
            }
        }

        return [
            'progress' => 25,
            'status' => __('Core pages created successfully.', 'aqualuxe'),
            'next_step' => 'products'
        ];
    }

    private function import_products(): array
    {
        if (!class_exists('WooCommerce')) {
            return [
                'progress' => 50,
                'status' => __('WooCommerce not active, skipping products.', 'aqualuxe'),
                'next_step' => 'blog'
            ];
        }

        $products = $this->get_aquatic_products();
        
        foreach ($products as $product_data) {
            $product = new \WC_Product_Simple();
            $product->set_name($product_data['name']);
            $product->set_description($product_data['description']);
            $product->set_short_description($product_data['short_description']);
            $product->set_regular_price($product_data['price']);
            $product->set_sku($product_data['sku']);
            $product->set_stock_quantity($product_data['stock']);
            $product->set_manage_stock(true);
            $product->set_catalog_visibility('visible');
            $product->set_status('publish');
            
            $product_id = $product->save();
            
            // Add demo meta
            update_post_meta($product_id, '_aqualuxe_demo', 1);
            
            // Set product category
            if (!empty($product_data['category'])) {
                wp_set_object_terms($product_id, $product_data['category'], 'product_cat');
            }
        }

        return [
            'progress' => 50,
            'status' => sprintf(__('Imported %d aquatic products.', 'aqualuxe'), count($products)),
            'next_step' => 'blog'
        ];
    }

    private function import_blog_posts(): array
    {
        $posts = $this->get_blog_posts();
        
        foreach ($posts as $post_data) {
            $post_id = wp_insert_post([
                'post_title' => $post_data['title'],
                'post_content' => $post_data['content'],
                'post_excerpt' => $post_data['excerpt'],
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_category' => [get_option('default_category')],
                'meta_input' => [
                    '_aqualuxe_demo' => 1
                ]
            ]);
        }

        return [
            'progress' => 65,
            'status' => sprintf(__('Created %d blog posts.', 'aqualuxe'), count($posts)),
            'next_step' => 'testimonials'
        ];
    }

    private function import_testimonials(): array
    {
        $testimonials = $this->get_testimonials();
        
        foreach ($testimonials as $testimonial) {
            $post_id = wp_insert_post([
                'post_title' => $testimonial['name'],
                'post_content' => $testimonial['content'],
                'post_status' => 'publish',
                'post_type' => 'testimonial',
                'meta_input' => [
                    '_aqualuxe_demo' => 1,
                    '_testimonial_rating' => $testimonial['rating'],
                    '_testimonial_company' => $testimonial['company']
                ]
            ]);
        }

        return [
            'progress' => 75,
            'status' => sprintf(__('Added %d testimonials.', 'aqualuxe'), count($testimonials)),
            'next_step' => 'services'
        ];
    }

    private function import_services(): array
    {
        $services = $this->get_services();
        
        foreach ($services as $service) {
            $post_id = wp_insert_post([
                'post_title' => $service['title'],
                'post_content' => $service['content'],
                'post_status' => 'publish',
                'post_type' => 'service',
                'meta_input' => [
                    '_aqualuxe_demo' => 1,
                    '_service_price' => $service['price'],
                    '_service_duration' => $service['duration']
                ]
            ]);
        }

        return [
            'progress' => 85,
            'status' => sprintf(__('Created %d services.', 'aqualuxe'), count($services)),
            'next_step' => 'menus'
        ];
    }

    private function setup_menus(): array
    {
        // Create primary menu
        $menu_id = wp_create_nav_menu('Primary Menu');
        
        $menu_items = [
            ['title' => __('Home', 'aqualuxe'), 'url' => home_url('/')],
            ['title' => __('Shop', 'aqualuxe'), 'url' => home_url('/shop')],
            ['title' => __('Services', 'aqualuxe'), 'url' => home_url('/services')],
            ['title' => __('About', 'aqualuxe'), 'url' => home_url('/about')],
            ['title' => __('Blog', 'aqualuxe'), 'url' => home_url('/blog')],
            ['title' => __('Contact', 'aqualuxe'), 'url' => home_url('/contact')],
        ];

        foreach ($menu_items as $item) {
            wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title' => $item['title'],
                'menu-item-url' => $item['url'],
                'menu-item-status' => 'publish'
            ]);
        }

        // Assign to theme location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);

        return [
            'progress' => 90,
            'status' => __('Navigation menus configured.', 'aqualuxe'),
            'next_step' => 'customizer'
        ];
    }

    private function setup_customizer(): array
    {
        // Set theme customizer defaults
        set_theme_mod('aqualuxe_primary_color', '#0ea5e9');
        set_theme_mod('aqualuxe_secondary_color', '#06b6d4');
        set_theme_mod('aqualuxe_accent_color', '#eab308');
        
        return [
            'progress' => 95,
            'status' => __('Theme settings applied.', 'aqualuxe'),
            'next_step' => 'finalize'
        ];
    }

    private function finalize_import(): array
    {
        // Flush rewrite rules
        flush_rewrite_rules();
        
        // Set import complete flag
        update_option('aqualuxe_demo_imported', time());

        return [
            'progress' => 100,
            'status' => __('Demo content import completed successfully!', 'aqualuxe'),
            'next_step' => null
        ];
    }

    private function flush_demo_content(): void
    {
        // Remove all demo content
        $demo_posts = get_posts([
            'post_type' => 'any',
            'meta_key' => '_aqualuxe_demo',
            'meta_value' => 1,
            'posts_per_page' => -1,
            'fields' => 'ids'
        ]);

        foreach ($demo_posts as $post_id) {
            wp_delete_post($post_id, true);
        }

        // Reset options
        delete_option('aqualuxe_demo_imported');
        update_option('show_on_front', 'posts');
        delete_option('page_on_front');

        // Clear theme mods
        remove_theme_mod('aqualuxe_primary_color');
        remove_theme_mod('aqualuxe_secondary_color');
        remove_theme_mod('aqualuxe_accent_color');
    }

    // Content generation methods
    private function get_home_content(): string
    {
        return '<div class="hero-section">
            <h1>Welcome to AquaLuxe</h1>
            <p class="lead">Bringing elegance to aquatic life – globally</p>
            <div class="hero-buttons">
                <a href="/shop" class="btn btn-primary">Shop Now</a>
                <a href="/services" class="btn btn-secondary">Our Services</a>
            </div>
        </div>
        
        <div class="featured-products">
            <h2>Featured Products</h2>
            [products limit="4" columns="4" visibility="featured"]
        </div>
        
        <div class="testimonials-section">
            <h2>What Our Customers Say</h2>
            [aqualuxe_testimonials count="3"]
        </div>';
    }

    private function get_about_content(): string
    {
        return '<h1>About AquaLuxe</h1>
        <p class="lead">We are passionate aquatic specialists dedicated to bringing elegance and beauty to aquatic environments worldwide.</p>
        
        <h2>Our Story</h2>
        <p>Founded with a vision to transform how people experience aquatic life, AquaLuxe has grown from a small local aquarium shop to a globally recognized brand in premium aquatic solutions.</p>
        
        <h2>Our Mission</h2>
        <p>To provide the highest quality aquatic products and services while promoting sustainable practices and educating our community about responsible aquatic care.</p>
        
        <h2>Why Choose AquaLuxe?</h2>
        <ul>
            <li>Premium quality products from trusted suppliers</li>
            <li>Expert advice and professional services</li>
            <li>Sustainable and ethical practices</li>
            <li>Global shipping and support</li>
            <li>Community-focused education</li>
        </ul>';
    }

    private function get_services_content(): string
    {
        return '<h1>Professional Aquatic Services</h1>
        <p class="lead">From design to maintenance, we offer comprehensive aquatic solutions for homes and businesses.</p>
        
        <div class="services-grid">
            <div class="service-item">
                <h3>Aquarium Design & Installation</h3>
                <p>Custom aquarium design and professional installation services.</p>
            </div>
            <div class="service-item">
                <h3>Maintenance & Care</h3>
                <p>Regular maintenance packages to keep your aquarium healthy and beautiful.</p>
            </div>
            <div class="service-item">
                <h3>Consultation Services</h3>
                <p>Expert advice on fish selection, tank setup, and aquatic care.</p>
            </div>
        </div>';
    }

    private function get_contact_content(): string
    {
        return '<h1>Contact Us</h1>
        <p>Get in touch with our aquatic experts for personalized advice and support.</p>
        
        <div class="contact-form">
            [contact-form-7 id="123" title="Contact form 1"]
        </div>
        
        <div class="contact-info">
            <h3>Visit Our Store</h3>
            <p>123 Aquatic Avenue<br>Ocean City, AC 12345<br>Phone: (555) 123-4567<br>Email: info@aqualuxe.com</p>
        </div>';
    }

    private function get_faq_content(): string
    {
        return '<h1>Frequently Asked Questions</h1>
        
        <h3>Do you ship internationally?</h3>
        <p>Yes, we ship to most countries worldwide. Live fish shipments are subject to import regulations.</p>
        
        <h3>What is your return policy?</h3>
        <p>We offer a 30-day return policy on equipment and a 7-day guarantee on live fish.</p>
        
        <h3>Do you offer installation services?</h3>
        <p>Yes, we provide professional installation services in select areas.</p>';
    }

    private function get_privacy_content(): string
    {
        return '<h1>Privacy Policy</h1>
        <p>Your privacy is important to us. This policy explains how we collect, use, and protect your information.</p>
        
        <h2>Information We Collect</h2>
        <p>We collect information you provide directly to us, such as when you create an account or make a purchase.</p>
        
        <h2>How We Use Information</h2>
        <p>We use your information to process orders, provide customer service, and improve our services.</p>';
    }

    private function get_terms_content(): string
    {
        return '<h1>Terms of Service</h1>
        <p>By using our website and services, you agree to these terms and conditions.</p>
        
        <h2>Use of Service</h2>
        <p>Our services are intended for personal and commercial use in accordance with applicable laws.</p>
        
        <h2>Product Information</h2>
        <p>We strive to provide accurate product information, but cannot guarantee complete accuracy.</p>';
    }

    private function get_shipping_content(): string
    {
        return '<h1>Shipping & Returns</h1>
        
        <h2>Shipping Information</h2>
        <p>We offer various shipping options including express delivery for live fish and plants.</p>
        
        <h2>Return Policy</h2>
        <p>Equipment can be returned within 30 days. Live animals have a 7-day guarantee.</p>
        
        <h2>International Shipping</h2>
        <p>We ship worldwide, subject to local import regulations and restrictions.</p>';
    }

    private function get_aquatic_products(): array
    {
        return [
            [
                'name' => 'Premium Discus Fish',
                'description' => 'Beautiful premium discus fish, perfect for advanced aquarists.',
                'short_description' => 'Premium quality discus fish',
                'price' => '89.99',
                'sku' => 'DISC-001',
                'stock' => 5,
                'category' => 'Fish'
            ],
            [
                'name' => 'Aquascaping Plant Pack',
                'description' => 'Complete plant pack for creating stunning aquascapes.',
                'short_description' => 'Complete aquascaping plant collection',
                'price' => '49.99',
                'sku' => 'PLANT-001',
                'stock' => 20,
                'category' => 'Plants'
            ],
            [
                'name' => 'Professional LED Light',
                'description' => 'High-quality LED lighting system for planted tanks.',
                'short_description' => 'Professional aquarium LED lighting',
                'price' => '199.99',
                'sku' => 'LED-001',
                'stock' => 10,
                'category' => 'Equipment'
            ],
            [
                'name' => 'Premium Substrate',
                'description' => 'Nutrient-rich substrate for planted aquariums.',
                'short_description' => 'Premium aquarium substrate',
                'price' => '29.99',
                'sku' => 'SUB-001',
                'stock' => 30,
                'category' => 'Supplies'
            ]
        ];
    }

    private function get_blog_posts(): array
    {
        return [
            [
                'title' => 'Getting Started with Aquascaping',
                'content' => 'Aquascaping is the art of creating beautiful underwater landscapes...',
                'excerpt' => 'Learn the basics of aquascaping and create stunning underwater gardens.'
            ],
            [
                'title' => 'Caring for Discus Fish',
                'content' => 'Discus fish are known for their beauty and elegance...',
                'excerpt' => 'Essential care tips for keeping healthy and vibrant discus fish.'
            ],
            [
                'title' => 'Setting Up Your First Planted Tank',
                'content' => 'Planted aquariums bring nature into your home...',
                'excerpt' => 'Step-by-step guide to creating your first planted aquarium.'
            ]
        ];
    }

    private function get_testimonials(): array
    {
        return [
            [
                'name' => 'Sarah Johnson',
                'content' => 'AquaLuxe helped me create the most beautiful aquarium. Their expertise is unmatched!',
                'rating' => 5,
                'company' => 'Private Customer'
            ],
            [
                'name' => 'Mike Chen',
                'content' => 'Professional service and high-quality products. Highly recommended!',
                'rating' => 5,
                'company' => 'Restaurant Owner'
            ],
            [
                'name' => 'Lisa Williams',
                'content' => 'The best place for aquatic supplies. Great customer service!',
                'rating' => 5,
                'company' => 'Aquarium Enthusiast'
            ]
        ];
    }

    private function get_services(): array
    {
        return [
            [
                'title' => 'Aquarium Design',
                'content' => 'Custom aquarium design services for homes and businesses.',
                'price' => '299.00',
                'duration' => '2-3 weeks'
            ],
            [
                'title' => 'Maintenance Service',
                'content' => 'Regular aquarium maintenance to keep your tank healthy.',
                'price' => '89.00',
                'duration' => '1 hour'
            ],
            [
                'title' => 'Consultation',
                'content' => 'Expert consultation on fish selection and tank setup.',
                'price' => '49.00',
                'duration' => '30 minutes'
            ]
        ];
    }
}
        foreach ($ids as $id) { \wp_delete_post($id, true); }
    }

    private function import_core_pages(): void
    {
        $pages = [
            'Home' => 'home',
            'About' => 'about',
            'Services' => 'services',
            'Blog' => 'blog',
            'Contact' => 'contact',
            'FAQ' => 'faq',
            'Privacy Policy' => 'privacy-policy',
            'Terms & Conditions' => 'terms',
            'Shipping & Returns' => 'shipping-returns',
            'Cookie Policy' => 'cookies',
        ];
        foreach ($pages as $title => $slug) {
            $id = \wp_insert_post([
                'post_type'   => 'page',
                'post_title'  => $title,
                'post_name'   => $slug,
                'post_status' => 'publish',
                'meta_input'  => ['_aqlx_demo' => 1],
                'post_content'=> '<!-- wp:paragraph --><p>Demo content for ' . esc_html($title) . '</p><!-- /wp:paragraph -->',
            ]);
        }
    }

    private function import_blog(): void
    {
        for ($i=1; $i<=6; $i++) {
            \wp_insert_post([
                'post_type'   => 'post',
                'post_title'  => 'Aquatic Insights #' . $i,
                'post_status' => 'publish',
                'meta_input'  => ['_aqlx_demo' => 1],
                'post_content'=> '<p>Exploring the elegance of aquatic life. Entry #' . $i . '</p>',
            ]);
        }
    }

    private function import_products(): void
    {
        // Minimal product examples; full variable products omitted for brevity.
        if (! class_exists('WC_Product_Simple')) { return; }
        $titles = ['Rare Betta', 'Premium Koi', 'Aquascape LED Light'];
        foreach ($titles as $title) {
            $id = \wp_insert_post([
                'post_type'   => 'product',
                'post_title'  => $title,
                'post_status' => 'publish',
                'meta_input'  => ['_aqlx_demo' => 1],
            ]);
            if ($id && ! is_wp_error($id)) {
                \wp_set_object_terms($id, 'simple', 'product_type');
                \update_post_meta($id, '_price', '99.00');
                \update_post_meta($id, '_regular_price', '99.00');
                \update_post_meta($id, '_stock_status', 'instock');
            }
        }
    }
}
