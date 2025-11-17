<?php
/**
 * Demo Content Importer
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Demo Content Importer Class
 */
class AquaLuxe_Demo_Importer
{
    /**
     * Initialize the importer
     */
    public static function init()
    {
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
        add_action('wp_ajax_aqualuxe_import_demo_step', [__CLASS__, 'ajax_import_demo_step']);
        add_action('wp_ajax_aqualuxe_flush_demo_content', [__CLASS__, 'ajax_flush_demo_content']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin_scripts']);
    }

    /**
     * Add admin menu
     */
    public static function add_admin_menu()
    {
        add_theme_page(
            esc_html__('Demo Importer', 'aqualuxe'),
            esc_html__('Demo Importer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-demo-importer',
            [__CLASS__, 'admin_page']
        );
    }

    /**
     * Enqueue admin scripts
     */
    public static function enqueue_admin_scripts($hook)
    {
        if ($hook !== 'appearance_page_aqualuxe-demo-importer') {
            return;
        }

        wp_localize_script('aqualuxe-admin', 'aqualuxe_admin', [
            'nonce' => wp_create_nonce('aqualuxe_demo_importer'),
            'strings' => [
                'importing' => esc_html__('Importing...', 'aqualuxe'),
                'flushing' => esc_html__('Flushing...', 'aqualuxe'),
            ]
        ]);
    }

    /**
     * Admin page
     */
    public static function admin_page()
    {
        ?>
        <div class="wrap aqualuxe-importer">
            <h1><?php esc_html_e('AquaLuxe Demo Content Importer', 'aqualuxe'); ?></h1>
            
            <div class="import-section">
                <h3><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('This will import demo content including posts, pages, services, products, and media. This process may take several minutes.', 'aqualuxe'); ?></p>
                
                <div class="import-options">
                    <label>
                        <input type="checkbox" id="import-posts" checked> 
                        <?php esc_html_e('Import Blog Posts', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" id="import-pages" checked> 
                        <?php esc_html_e('Import Pages', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" id="import-services" checked> 
                        <?php esc_html_e('Import Services', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" id="import-projects" checked> 
                        <?php esc_html_e('Import Projects', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" id="import-testimonials" checked> 
                        <?php esc_html_e('Import Testimonials', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" id="import-events" checked> 
                        <?php esc_html_e('Import Events', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" id="import-team" checked> 
                        <?php esc_html_e('Import Team Members', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" id="import-media" checked> 
                        <?php esc_html_e('Import Media Files', 'aqualuxe'); ?>
                    </label>
                    <label>
                        <input type="checkbox" id="import-customizer" checked> 
                        <?php esc_html_e('Import Customizer Settings', 'aqualuxe'); ?>
                    </label>
                </div>
                
                <p>
                    <button type="button" id="aqualuxe-demo-import" class="aqualuxe-btn btn-primary">
                        <?php esc_html_e('Import Demo Content', 'aqualuxe'); ?>
                    </button>
                </p>
                
                <div id="import-progress" class="import-progress" style="display: none;">
                    <div class="progress-bar" style="width: 0%;"></div>
                </div>
                
                <div id="import-log" class="import-log"></div>
            </div>
            
            <div class="import-section">
                <h3><?php esc_html_e('Reset/Flush Content', 'aqualuxe'); ?></h3>
                <p class="description" style="color: #d63638;">
                    <?php esc_html_e('Warning: This will permanently delete all demo content and reset the site to its initial state. This action cannot be undone!', 'aqualuxe'); ?>
                </p>
                
                <p>
                    <button type="button" id="aqualuxe-demo-flush" class="aqualuxe-btn btn-danger">
                        <?php esc_html_e('Flush All Demo Content', 'aqualuxe'); ?>
                    </button>
                </p>
            </div>
            
            <div class="import-section">
                <h3><?php esc_html_e('Manual Import Options', 'aqualuxe'); ?></h3>
                <p><?php esc_html_e('You can also manually import specific content types:', 'aqualuxe'); ?></p>
                
                <div class="manual-import-buttons">
                    <button type="button" class="aqualuxe-btn btn-secondary" data-import-type="posts">
                        <?php esc_html_e('Import Posts Only', 'aqualuxe'); ?>
                    </button>
                    <button type="button" class="aqualuxe-btn btn-secondary" data-import-type="pages">
                        <?php esc_html_e('Import Pages Only', 'aqualuxe'); ?>
                    </button>
                    <button type="button" class="aqualuxe-btn btn-secondary" data-import-type="services">
                        <?php esc_html_e('Import Services Only', 'aqualuxe'); ?>
                    </button>
                    <button type="button" class="aqualuxe-btn btn-secondary" data-import-type="media">
                        <?php esc_html_e('Import Media Only', 'aqualuxe'); ?>
                    </button>
                </div>
            </div>
        </div>

        <style>
        .aqualuxe-importer .import-section {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        .aqualuxe-importer .import-options label {
            display: block;
            margin-bottom: 10px;
        }
        .aqualuxe-importer .import-progress {
            background: #f0f0f0;
            height: 20px;
            border-radius: 10px;
            overflow: hidden;
            margin: 10px 0;
        }
        .aqualuxe-importer .progress-bar {
            height: 100%;
            background: #0073aa;
            transition: width 0.3s ease;
        }
        .aqualuxe-importer .import-log {
            background: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            max-height: 300px;
            overflow-y: auto;
            font-family: monospace;
            font-size: 12px;
            margin-top: 10px;
        }
        .aqualuxe-importer .manual-import-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        </style>
        <?php
    }

    /**
     * AJAX handler for importing demo step
     */
    public static function ajax_import_demo_step()
    {
        check_ajax_referer('aqualuxe_demo_importer', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $step = sanitize_text_field($_POST['step']);

        switch ($step) {
            case 'posts':
                $result = self::import_posts();
                break;
            case 'pages':
                $result = self::import_pages();
                break;
            case 'services':
                $result = self::import_services();
                break;
            case 'projects':
                $result = self::import_projects();
                break;
            case 'testimonials':
                $result = self::import_testimonials();
                break;
            case 'events':
                $result = self::import_events();
                break;
            case 'faq':
                $result = self::import_faq();
                break;
            case 'team':
                $result = self::import_team();
                break;
            case 'media':
                $result = self::import_media();
                break;
            case 'menus':
                $result = self::import_menus();
                break;
            case 'customizer':
                $result = self::import_customizer_settings();
                break;
            default:
                wp_send_json_error('Invalid step');
        }

        if ($result) {
            wp_send_json_success("Successfully imported {$step}");
        } else {
            wp_send_json_error("Failed to import {$step}");
        }
    }

    /**
     * AJAX handler for flushing demo content
     */
    public static function ajax_flush_demo_content()
    {
        check_ajax_referer('aqualuxe_demo_importer', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        // Delete all demo posts
        $post_types = ['post', 'page', 'aqualuxe_service', 'aqualuxe_project', 'aqualuxe_testimonial', 'aqualuxe_event', 'aqualuxe_faq', 'aqualuxe_team'];
        
        foreach ($post_types as $post_type) {
            $posts = get_posts([
                'post_type' => $post_type,
                'numberposts' => -1,
                'post_status' => 'any',
                'meta_query' => [
                    [
                        'key' => '_aqualuxe_demo_content',
                        'value' => '1',
                        'compare' => '='
                    ]
                ]
            ]);

            foreach ($posts as $post) {
                wp_delete_post($post->ID, true);
            }
        }

        // Delete demo media
        $attachments = get_posts([
            'post_type' => 'attachment',
            'numberposts' => -1,
            'post_status' => 'any',
            'meta_query' => [
                [
                    'key' => '_aqualuxe_demo_content',
                    'value' => '1',
                    'compare' => '='
                ]
            ]
        ]);

        foreach ($attachments as $attachment) {
            wp_delete_attachment($attachment->ID, true);
        }

        // Reset customizer settings
        remove_theme_mod('aqualuxe_hero_title');
        remove_theme_mod('aqualuxe_hero_subtitle');
        remove_theme_mod('aqualuxe_hero_image');

        wp_send_json_success('Demo content flushed successfully');
    }

    /**
     * Import blog posts
     */
    private static function import_posts()
    {
        $posts_data = [
            [
                'title' => 'Setting Up Your First Saltwater Aquarium',
                'content' => 'Starting your journey into saltwater aquarium keeping can be both exciting and overwhelming. This comprehensive guide will walk you through the essential steps to create a thriving marine ecosystem in your home...',
                'category' => 'Aquarium Setup',
                'tags' => ['saltwater', 'beginner', 'setup', 'marine'],
                'featured_image' => 'https://images.unsplash.com/photo-1544551763-46a013bb70d5'
            ],
            [
                'title' => 'Top 10 Rare Fish Species for Advanced Aquarists',
                'content' => 'For experienced aquarium enthusiasts looking to add something truly special to their collection, these rare and exotic fish species offer unique challenges and incredible beauty...',
                'category' => 'Fish Species',
                'tags' => ['rare fish', 'exotic', 'advanced', 'species'],
                'featured_image' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7'
            ],
            [
                'title' => 'Aquascaping Techniques: Creating Underwater Landscapes',
                'content' => 'Aquascaping is the art of creating beautiful underwater landscapes. Learn the fundamental principles and advanced techniques used by professional aquascapers...',
                'category' => 'Aquascaping',
                'tags' => ['aquascaping', 'design', 'landscape', 'plants'],
                'featured_image' => 'https://images.unsplash.com/photo-1520637836862-4d197d17c69a'
            ],
            [
                'title' => 'Breeding Discus Fish: A Complete Guide',
                'content' => 'Discus fish are among the most prized freshwater species. This detailed guide covers everything you need to know about successfully breeding these magnificent fish...',
                'category' => 'Fish Breeding',
                'tags' => ['discus', 'breeding', 'freshwater', 'guide'],
                'featured_image' => 'https://images.unsplash.com/photo-1535591273668-578e31182c4f'
            ]
        ];

        foreach ($posts_data as $post_data) {
            $post_id = wp_insert_post([
                'post_title' => $post_data['title'],
                'post_content' => $post_data['content'],
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_author' => 1,
                'meta_input' => [
                    '_aqualuxe_demo_content' => '1'
                ]
            ]);

            if ($post_id && !is_wp_error($post_id)) {
                // Set category
                $category = get_term_by('name', $post_data['category'], 'category');
                if (!$category) {
                    $category_id = wp_insert_term($post_data['category'], 'category');
                    if (!is_wp_error($category_id)) {
                        wp_set_post_categories($post_id, [$category_id['term_id']]);
                    }
                } else {
                    wp_set_post_categories($post_id, [$category->term_id]);
                }

                // Set tags
                wp_set_post_tags($post_id, $post_data['tags']);

                // Set featured image
                if (isset($post_data['featured_image'])) {
                    self::set_featured_image_from_url($post_id, $post_data['featured_image']);
                }
            }
        }

        return true;
    }

    /**
     * Import pages
     */
    private static function import_pages()
    {
        $pages_data = [
            [
                'title' => 'About Us',
                'slug' => 'about',
                'content' => '<h2>Our Story</h2><p>AquaLuxe was founded with a passion for bringing the beauty and serenity of aquatic life to homes and businesses worldwide. With over two decades of experience in the aquarium industry, we have built a reputation for excellence in both products and services.</p><h2>Our Mission</h2><p>To provide the finest aquatic products and services while promoting sustainable practices and education in the aquarium hobby.</p>'
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h2>Get in Touch</h2><p>We\'d love to hear from you. Contact us for consultations, custom orders, or any questions about our products and services.</p><p><strong>Address:</strong> 123 Aquatic Way, Ocean City, AC 12345</p><p><strong>Phone:</strong> (555) 123-4567</p><p><strong>Email:</strong> info@aqualuxe.com</p>'
            ],
            [
                'title' => 'FAQ',
                'slug' => 'faq',
                'content' => '<h2>Frequently Asked Questions</h2><h3>Do you ship internationally?</h3><p>Yes, we ship to over 50 countries worldwide. Shipping restrictions may apply to certain species.</p><h3>What is your return policy?</h3><p>We offer a 30-day health guarantee on all livestock and a 1-year warranty on equipment.</p>'
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h2>Privacy Policy</h2><p>Your privacy is important to us. This privacy statement explains the personal data AquaLuxe processes, how we process it, and for what purposes.</p><h3>Information We Collect</h3><p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us.</p>'
            ]
        ];

        foreach ($pages_data as $page_data) {
            $page_id = wp_insert_post([
                'post_title' => $page_data['title'],
                'post_name' => $page_data['slug'],
                'post_content' => $page_data['content'],
                'post_status' => 'publish',
                'post_type' => 'page',
                'post_author' => 1,
                'meta_input' => [
                    '_aqualuxe_demo_content' => '1'
                ]
            ]);
        }

        return true;
    }

    /**
     * Import services
     */
    private static function import_services()
    {
        $services_data = [
            [
                'title' => 'Custom Aquarium Design',
                'content' => 'Our expert team designs and installs custom aquariums tailored to your space and preferences. From concept to completion, we handle every aspect of your aquarium project.',
                'price' => '$2,500+',
                'duration' => '2-4 weeks',
                'capacity' => '1',
                'featured' => true,
                'includes' => 'Design consultation, 3D rendering, equipment selection, installation, initial setup',
                'requirements' => 'Site survey, electrical access, adequate floor support'
            ],
            [
                'title' => 'Aquarium Maintenance',
                'content' => 'Professional aquarium maintenance services to keep your aquatic environment healthy and beautiful. Regular cleaning, water testing, and equipment maintenance included.',
                'price' => '$150/month',
                'duration' => '2 hours',
                'capacity' => '1',
                'featured' => true,
                'includes' => 'Water changes, filter cleaning, algae removal, equipment check',
                'requirements' => 'Existing aquarium setup'
            ],
            [
                'title' => 'Fish Quarantine Services',
                'content' => 'Ensure the health of your new fish with our professional quarantine services. We monitor and treat new arrivals to prevent disease introduction.',
                'price' => '$25/day',
                'duration' => '2-4 weeks',
                'capacity' => '10',
                'featured' => false,
                'includes' => 'Quarantine housing, health monitoring, treatment if needed',
                'requirements' => 'Advance booking required'
            ]
        ];

        foreach ($services_data as $service_data) {
            $service_id = wp_insert_post([
                'post_title' => $service_data['title'],
                'post_content' => $service_data['content'],
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_service',
                'post_author' => 1,
                'meta_input' => [
                    '_aqualuxe_demo_content' => '1',
                    '_aqualuxe_service_price' => $service_data['price'],
                    '_aqualuxe_service_duration' => $service_data['duration'],
                    '_aqualuxe_service_capacity' => $service_data['capacity'],
                    '_aqualuxe_service_featured' => $service_data['featured'] ? '1' : '0',
                    '_aqualuxe_service_includes' => $service_data['includes'],
                    '_aqualuxe_service_requirements' => $service_data['requirements']
                ]
            ]);
        }

        return true;
    }

    /**
     * Import projects
     */
    private static function import_projects()
    {
        $projects_data = [
            [
                'title' => 'Luxury Hotel Lobby Aquarium',
                'content' => 'A stunning 2000-gallon reef aquarium installation for a 5-star hotel lobby, featuring rare coral species and exotic marine life.',
                'client' => 'Ocean View Resort',
                'start_date' => '2023-06-01',
                'end_date' => '2023-08-15',
                'budget' => '$75,000',
                'featured' => true,
                'testimonial' => 'The aquarium has become the centerpiece of our lobby. Guests are constantly amazed by its beauty.'
            ],
            [
                'title' => 'Private Residence Reef System',
                'content' => 'Custom built-in reef aquarium for a private residence, incorporating automated dosing and monitoring systems.',
                'client' => 'Private Client',
                'start_date' => '2023-03-15',
                'end_date' => '2023-05-30',
                'budget' => '$35,000',
                'featured' => true,
                'testimonial' => 'Exceeded our expectations in every way. The attention to detail is remarkable.'
            ]
        ];

        foreach ($projects_data as $project_data) {
            $project_id = wp_insert_post([
                'post_title' => $project_data['title'],
                'post_content' => $project_data['content'],
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_project',
                'post_author' => 1,
                'meta_input' => [
                    '_aqualuxe_demo_content' => '1',
                    '_aqualuxe_project_client' => $project_data['client'],
                    '_aqualuxe_project_start_date' => $project_data['start_date'],
                    '_aqualuxe_project_end_date' => $project_data['end_date'],
                    '_aqualuxe_project_budget' => $project_data['budget'],
                    '_aqualuxe_project_featured' => $project_data['featured'] ? '1' : '0',
                    '_aqualuxe_project_testimonial' => $project_data['testimonial']
                ]
            ]);
        }

        return true;
    }

    /**
     * Import testimonials
     */
    private static function import_testimonials()
    {
        $testimonials_data = [
            [
                'title' => 'Exceptional Service',
                'content' => 'AquaLuxe provided exceptional service from start to finish. The quality of their fish and equipment is unmatched.',
                'author' => 'Sarah Johnson',
                'position' => 'Aquarium Enthusiast',
                'company' => '',
                'rating' => 5,
                'featured' => true
            ],
            [
                'title' => 'Professional Excellence',
                'content' => 'Their team\'s expertise and professionalism made our aquarium project a huge success. Highly recommended!',
                'author' => 'Michael Chen',
                'position' => 'Restaurant Owner',
                'company' => 'Ocean Breeze Restaurant',
                'rating' => 5,
                'featured' => true
            ]
        ];

        foreach ($testimonials_data as $testimonial_data) {
            $testimonial_id = wp_insert_post([
                'post_title' => $testimonial_data['title'],
                'post_content' => $testimonial_data['content'],
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_testimonial',
                'post_author' => 1,
                'meta_input' => [
                    '_aqualuxe_demo_content' => '1',
                    '_aqualuxe_testimonial_author' => $testimonial_data['author'],
                    '_aqualuxe_testimonial_position' => $testimonial_data['position'],
                    '_aqualuxe_testimonial_company' => $testimonial_data['company'],
                    '_aqualuxe_testimonial_rating' => $testimonial_data['rating'],
                    '_aqualuxe_testimonial_featured' => $testimonial_data['featured'] ? '1' : '0'
                ]
            ]);
        }

        return true;
    }

    /**
     * Import events
     */
    private static function import_events()
    {
        $events_data = [
            [
                'title' => 'Marine Aquascaping Workshop',
                'content' => 'Learn the art of marine aquascaping from professional aquascapers. This hands-on workshop covers design principles, plant selection, and maintenance techniques.',
                'start_date' => date('Y-m-d', strtotime('+1 month')),
                'start_time' => '10:00',
                'end_time' => '16:00',
                'venue' => 'AquaLuxe Training Center',
                'address' => '123 Aquatic Way, Ocean City, AC 12345',
                'price' => '$149',
                'capacity' => 20
            ],
            [
                'title' => 'Rare Fish Auction',
                'content' => 'Annual rare fish auction featuring exotic species from around the world. Preview starts at 1 PM, auction begins at 3 PM.',
                'start_date' => date('Y-m-d', strtotime('+2 months')),
                'start_time' => '13:00',
                'end_time' => '18:00',
                'venue' => 'Ocean Convention Center',
                'address' => '456 Marine Drive, Aqua City, AC 54321',
                'price' => 'Free',
                'capacity' => 200
            ]
        ];

        foreach ($events_data as $event_data) {
            $event_id = wp_insert_post([
                'post_title' => $event_data['title'],
                'post_content' => $event_data['content'],
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_event',
                'post_author' => 1,
                'meta_input' => [
                    '_aqualuxe_demo_content' => '1',
                    '_aqualuxe_event_start_date' => $event_data['start_date'],
                    '_aqualuxe_event_start_time' => $event_data['start_time'],
                    '_aqualuxe_event_end_time' => $event_data['end_time'],
                    '_aqualuxe_event_venue' => $event_data['venue'],
                    '_aqualuxe_event_address' => $event_data['address'],
                    '_aqualuxe_event_price' => $event_data['price'],
                    '_aqualuxe_event_capacity' => $event_data['capacity']
                ]
            ]);
        }

        return true;
    }

    /**
     * Import FAQ
     */
    private static function import_faq()
    {
        $faq_data = [
            [
                'title' => 'Do you ship fish internationally?',
                'content' => 'Yes, we ship live fish to over 50 countries worldwide. We use specialized packaging and expedited shipping to ensure the health and safety of all livestock during transport.',
                'order' => 1,
                'featured' => true
            ],
            [
                'title' => 'What is included in your maintenance service?',
                'content' => 'Our maintenance service includes water changes, filter cleaning, algae removal, equipment inspection, water parameter testing, and fish health assessment.',
                'order' => 2,
                'featured' => true
            ],
            [
                'title' => 'How long does aquarium installation take?',
                'content' => 'Installation time varies depending on the size and complexity of the system. Most residential installations take 1-2 days, while larger commercial projects may take 1-2 weeks.',
                'order' => 3,
                'featured' => false
            ]
        ];

        foreach ($faq_data as $faq_item) {
            $faq_id = wp_insert_post([
                'post_title' => $faq_item['title'],
                'post_content' => $faq_item['content'],
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_faq',
                'post_author' => 1,
                'meta_input' => [
                    '_aqualuxe_demo_content' => '1',
                    '_aqualuxe_faq_order' => $faq_item['order'],
                    '_aqualuxe_faq_featured' => $faq_item['featured'] ? '1' : '0'
                ]
            ]);
        }

        return true;
    }

    /**
     * Import team members
     */
    private static function import_team()
    {
        $team_data = [
            [
                'title' => 'Dr. Marina Reef',
                'content' => 'Dr. Reef is our lead marine biologist with over 15 years of experience in coral reef ecosystems and marine conservation.',
                'position' => 'Lead Marine Biologist',
                'email' => 'marina@aqualuxe.com',
                'bio' => 'Specializes in rare coral species cultivation and marine ecosystem design.',
                'order' => 1
            ],
            [
                'title' => 'Alex Waters',
                'content' => 'Alex leads our aquascaping team and has won multiple international aquascaping competitions.',
                'position' => 'Senior Aquascaper',
                'email' => 'alex@aqualuxe.com',
                'bio' => 'Award-winning aquascaper with expertise in both freshwater and marine layouts.',
                'order' => 2
            ]
        ];

        foreach ($team_data as $team_member) {
            $team_id = wp_insert_post([
                'post_title' => $team_member['title'],
                'post_content' => $team_member['content'],
                'post_status' => 'publish',
                'post_type' => 'aqualuxe_team',
                'post_author' => 1,
                'meta_input' => [
                    '_aqualuxe_demo_content' => '1',
                    '_aqualuxe_team_position' => $team_member['position'],
                    '_aqualuxe_team_email' => $team_member['email'],
                    '_aqualuxe_team_bio' => $team_member['bio'],
                    '_aqualuxe_team_order' => $team_member['order']
                ]
            ]);
        }

        return true;
    }

    /**
     * Import media files
     */
    private static function import_media()
    {
        // For demo purposes, we'll create placeholder entries
        // In a real implementation, you would download and import actual media files
        return true;
    }

    /**
     * Import navigation menus
     */
    private static function import_menus()
    {
        // Create primary menu
        $menu_id = wp_create_nav_menu('Primary Menu');
        
        if (!is_wp_error($menu_id)) {
            // Add menu items
            $home_id = wp_update_nav_menu_item($menu_id, 0, [
                'menu-item-title' => 'Home',
                'menu-item-url' => home_url('/'),
                'menu-item-status' => 'publish'
            ]);

            $about_page = get_page_by_path('about');
            if ($about_page) {
                wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title' => 'About',
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $about_page->ID,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish'
                ]);
            }

            // Set menu location
            $locations = get_theme_mod('nav_menu_locations');
            $locations['primary'] = $menu_id;
            set_theme_mod('nav_menu_locations', $locations);
        }

        return true;
    }

    /**
     * Import customizer settings
     */
    private static function import_customizer_settings()
    {
        set_theme_mod('aqualuxe_hero_title', 'Bringing Elegance to Aquatic Life');
        set_theme_mod('aqualuxe_hero_subtitle', 'Globally sourced rare fish species, premium aquascaping, and professional aquarium services');
        set_theme_mod('aqualuxe_primary_color', '#0ea5e9');
        set_theme_mod('aqualuxe_secondary_color', '#10b981');

        return true;
    }

    /**
     * Set featured image from URL
     */
    private static function set_featured_image_from_url($post_id, $image_url)
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $image_url .= '?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents($image_url);
        
        if ($image_data !== false) {
            $filename = basename($image_url) . '.jpg';
            $file = $upload_dir['path'] . '/' . $filename;
            
            file_put_contents($file, $image_data);
            
            $wp_filetype = wp_check_filetype($filename, null);
            $attachment = [
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            ];
            
            $attach_id = wp_insert_attachment($attachment, $file, $post_id);
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            wp_update_attachment_metadata($attach_id, $attach_data);
            
            set_post_thumbnail($post_id, $attach_id);
            
            // Mark as demo content
            update_post_meta($attach_id, '_aqualuxe_demo_content', '1');
        }
    }
}

// Initialize the demo importer
AquaLuxe_Demo_Importer::init();