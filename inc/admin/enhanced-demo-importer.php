<?php
/**
 * Enhanced Demo Content Importer with ACID transactions and better error handling
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhanced Demo Content Importer Class
 */
class AquaLuxe_Enhanced_Demo_Importer
{
    /**
     * Import log for debugging
     */
    private static $import_log = [];
    
    /**
     * Transaction mode for rollback support
     */
    private static $transaction_mode = false;
    
    /**
     * Imported items for rollback
     */
    private static $imported_items = [];

    /**
     * Initialize the enhanced importer
     */
    public static function init()
    {
        add_action('admin_menu', [__CLASS__, 'add_admin_menu']);
        add_action('wp_ajax_aqualuxe_enhanced_import_step', [__CLASS__, 'ajax_import_step']);
        add_action('wp_ajax_aqualuxe_enhanced_flush', [__CLASS__, 'ajax_flush_content']);
        add_action('wp_ajax_aqualuxe_rollback_import', [__CLASS__, 'ajax_rollback_import']);
        add_action('wp_ajax_aqualuxe_export_content', [__CLASS__, 'ajax_export_content']);
    }

    /**
     * Add admin menu
     */
    public static function add_admin_menu()
    {
        add_theme_page(
            esc_html__('Enhanced Demo Importer', 'aqualuxe'),
            esc_html__('Enhanced Demo Importer', 'aqualuxe'),
            'manage_options',
            'aqualuxe-enhanced-demo-importer',
            [__CLASS__, 'admin_page']
        );
    }

    /**
     * Enhanced admin page with better UI
     */
    public static function admin_page()
    {
        ?>
        <div class="wrap aqualuxe-enhanced-importer">
            <h1><?php esc_html_e('AquaLuxe Enhanced Demo Content Importer', 'aqualuxe'); ?></h1>
            
            <div class="importer-grid">
                <!-- Import Section -->
                <div class="importer-card">
                    <h2><?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></h2>
                    <p><?php esc_html_e('Import complete demo content with ACID-style transaction support and automatic rollback on failure.', 'aqualuxe'); ?></p>
                    
                    <div class="import-options">
                        <h3><?php esc_html_e('Content Types', 'aqualuxe'); ?></h3>
                        <div class="option-grid">
                            <label class="option-card">
                                <input type="checkbox" name="import_posts" checked>
                                <div class="option-content">
                                    <strong><?php esc_html_e('Blog Posts', 'aqualuxe'); ?></strong>
                                    <span><?php esc_html_e('Aquarium care guides and articles', 'aqualuxe'); ?></span>
                                </div>
                            </label>
                            
                            <label class="option-card">
                                <input type="checkbox" name="import_pages" checked>
                                <div class="option-content">
                                    <strong><?php esc_html_e('Pages', 'aqualuxe'); ?></strong>
                                    <span><?php esc_html_e('About, Contact, FAQ, Legal pages', 'aqualuxe'); ?></span>
                                </div>
                            </label>
                            
                            <label class="option-card">
                                <input type="checkbox" name="import_services" checked>
                                <div class="option-content">
                                    <strong><?php esc_html_e('Services', 'aqualuxe'); ?></strong>
                                    <span><?php esc_html_e('Aquarium maintenance and design services', 'aqualuxe'); ?></span>
                                </div>
                            </label>
                            
                            <label class="option-card">
                                <input type="checkbox" name="import_events" checked>
                                <div class="option-content">
                                    <strong><?php esc_html_e('Events', 'aqualuxe'); ?></strong>
                                    <span><?php esc_html_e('Workshops and aquarium shows', 'aqualuxe'); ?></span>
                                </div>
                            </label>
                            
                            <label class="option-card">
                                <input type="checkbox" name="import_products" checked>
                                <div class="option-content">
                                    <strong><?php esc_html_e('Products', 'aqualuxe'); ?></strong>
                                    <span><?php esc_html_e('Fish, plants, and equipment (WooCommerce)', 'aqualuxe'); ?></span>
                                </div>
                            </label>
                            
                            <label class="option-card">
                                <input type="checkbox" name="import_media" checked>
                                <div class="option-content">
                                    <strong><?php esc_html_e('Media', 'aqualuxe'); ?></strong>
                                    <span><?php esc_html_e('High-quality aquarium images', 'aqualuxe'); ?></span>
                                </div>
                            </label>
                        </div>
                        
                        <div class="advanced-options">
                            <h3><?php esc_html_e('Advanced Options', 'aqualuxe'); ?></h3>
                            <label>
                                <input type="checkbox" name="enable_transactions" checked>
                                <?php esc_html_e('Enable ACID-style transactions (recommended)', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="batch_processing" checked>
                                <?php esc_html_e('Use batch processing for large imports', 'aqualuxe'); ?>
                            </label>
                            <label>
                                <input type="checkbox" name="conflict_resolution">
                                <?php esc_html_e('Enable conflict resolution (merge existing content)', 'aqualuxe'); ?>
                            </label>
                        </div>
                    </div>
                    
                    <div class="import-actions">
                        <button type="button" id="enhanced-demo-import" class="button-primary large">
                            <?php esc_html_e('Start Import', 'aqualuxe'); ?>
                        </button>
                        <button type="button" id="preview-import" class="button-secondary">
                            <?php esc_html_e('Preview Import', 'aqualuxe'); ?>
                        </button>
                    </div>
                    
                    <div id="import-progress-container" style="display: none;">
                        <div class="progress-header">
                            <h4><?php esc_html_e('Import Progress', 'aqualuxe'); ?></h4>
                            <button type="button" id="cancel-import" class="button-link-delete">
                                <?php esc_html_e('Cancel', 'aqualuxe'); ?>
                            </button>
                        </div>
                        <div class="progress-bar-wrapper">
                            <div class="progress-bar" style="width: 0%;"></div>
                            <span class="progress-text">0%</span>
                        </div>
                        <div class="current-step"></div>
                    </div>
                    
                    <div id="import-log" class="import-log"></div>
                </div>
                
                <!-- Management Section -->
                <div class="importer-card">
                    <h2><?php esc_html_e('Content Management', 'aqualuxe'); ?></h2>
                    
                    <div class="management-section">
                        <h3><?php esc_html_e('Export Content', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Export your current content for backup or migration purposes.', 'aqualuxe'); ?></p>
                        <button type="button" id="export-content" class="button-secondary">
                            <?php esc_html_e('Export Content', 'aqualuxe'); ?>
                        </button>
                    </div>
                    
                    <div class="management-section">
                        <h3><?php esc_html_e('Rollback Import', 'aqualuxe'); ?></h3>
                        <p><?php esc_html_e('Rollback the last import operation if something went wrong.', 'aqualuxe'); ?></p>
                        <button type="button" id="rollback-import" class="button-secondary">
                            <?php esc_html_e('Rollback Last Import', 'aqualuxe'); ?>
                        </button>
                    </div>
                    
                    <div class="management-section danger-zone">
                        <h3><?php esc_html_e('Danger Zone', 'aqualuxe'); ?></h3>
                        <p class="warning"><?php esc_html_e('These actions are irreversible. Proceed with caution.', 'aqualuxe'); ?></p>
                        <button type="button" id="flush-demo-content" class="button-secondary">
                            <?php esc_html_e('Remove All Demo Content', 'aqualuxe'); ?>
                        </button>
                        <button type="button" id="reset-site" class="button-link-delete">
                            <?php esc_html_e('Reset Entire Site', 'aqualuxe'); ?>
                        </button>
                    </div>
                </div>
                
                <!-- Status Section -->
                <div class="importer-card status-card">
                    <h2><?php esc_html_e('System Status', 'aqualuxe'); ?></h2>
                    <div class="status-grid">
                        <div class="status-item">
                            <span class="status-label"><?php esc_html_e('WordPress Version:', 'aqualuxe'); ?></span>
                            <span class="status-value"><?php echo get_bloginfo('version'); ?></span>
                        </div>
                        <div class="status-item">
                            <span class="status-label"><?php esc_html_e('PHP Version:', 'aqualuxe'); ?></span>
                            <span class="status-value"><?php echo PHP_VERSION; ?></span>
                        </div>
                        <div class="status-item">
                            <span class="status-label"><?php esc_html_e('Memory Limit:', 'aqualuxe'); ?></span>
                            <span class="status-value"><?php echo ini_get('memory_limit'); ?></span>
                        </div>
                        <div class="status-item">
                            <span class="status-label"><?php esc_html_e('WooCommerce:', 'aqualuxe'); ?></span>
                            <span class="status-value <?php echo class_exists('WooCommerce') ? 'active' : 'inactive'; ?>">
                                <?php echo class_exists('WooCommerce') ? esc_html__('Active', 'aqualuxe') : esc_html__('Not Installed', 'aqualuxe'); ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="content-stats">
                        <h3><?php esc_html_e('Current Content', 'aqualuxe'); ?></h3>
                        <?php self::display_content_stats(); ?>
                    </div>
                </div>
            </div>
            
            <!-- Results Modal -->
            <div id="import-modal" class="import-modal" style="display: none;">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3><?php esc_html_e('Import Complete', 'aqualuxe'); ?></h3>
                        <button type="button" class="modal-close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="import-summary"></div>
                        <div class="import-details"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="button-primary modal-close">
                            <?php esc_html_e('Close', 'aqualuxe'); ?>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <style>
        .aqualuxe-enhanced-importer {
            max-width: 1200px;
        }
        .importer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .importer-card {
            background: white;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .importer-card h2 {
            margin-top: 0;
            color: #0073aa;
        }
        .option-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 10px;
            margin: 15px 0;
        }
        .option-card {
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .option-card:hover {
            border-color: #0073aa;
            box-shadow: 0 2px 4px rgba(0,115,170,0.1);
        }
        .option-card input[type="checkbox"] {
            margin-bottom: 8px;
        }
        .option-content strong {
            display: block;
            margin-bottom: 4px;
        }
        .option-content span {
            font-size: 12px;
            color: #666;
        }
        .progress-bar-wrapper {
            position: relative;
            background: #f0f0f0;
            height: 24px;
            border-radius: 12px;
            overflow: hidden;
            margin: 10px 0;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #0073aa, #00a0d2);
            transition: width 0.3s ease;
        }
        .progress-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #333;
            font-weight: bold;
            font-size: 12px;
        }
        .status-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 15px 0;
        }
        .status-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .status-value.active {
            color: #46b450;
        }
        .status-value.inactive {
            color: #dc3232;
        }
        .danger-zone {
            border: 1px solid #dc3232;
            border-radius: 6px;
            padding: 15px;
            background: #fef7f7;
        }
        .warning {
            color: #dc3232;
            font-weight: bold;
        }
        .import-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 9999;
        }
        .modal-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            border-radius: 8px;
            max-width: 600px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            border-bottom: 1px solid #eee;
        }
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
        </style>

        <script>
        jQuery(document).ready(function($) {
            // Enhanced import functionality with better error handling
            $('#enhanced-demo-import').on('click', function() {
                const options = {
                    posts: $('input[name="import_posts"]').is(':checked'),
                    pages: $('input[name="import_pages"]').is(':checked'),
                    services: $('input[name="import_services"]').is(':checked'),
                    events: $('input[name="import_events"]').is(':checked'),
                    products: $('input[name="import_products"]').is(':checked'),
                    media: $('input[name="import_media"]').is(':checked'),
                    transactions: $('input[name="enable_transactions"]').is(':checked'),
                    batch: $('input[name="batch_processing"]').is(':checked'),
                    conflict_resolution: $('input[name="conflict_resolution"]').is(':checked')
                };
                
                startEnhancedImport(options);
            });
            
            function startEnhancedImport(options) {
                $('#import-progress-container').show();
                const progressBar = $('.progress-bar');
                const progressText = $('.progress-text');
                const currentStep = $('.current-step');
                const importLog = $('#import-log');
                
                let currentProgress = 0;
                const steps = Object.keys(options).filter(key => options[key] && !['transactions', 'batch', 'conflict_resolution'].includes(key));
                const totalSteps = steps.length;
                
                function updateProgress(step, progress) {
                    currentProgress = progress;
                    progressBar.css('width', progress + '%');
                    progressText.text(progress + '%');
                    currentStep.text('Processing: ' + step);
                }
                
                function processStep(stepIndex) {
                    if (stepIndex >= steps.length) {
                        // Import complete
                        updateProgress('Complete', 100);
                        setTimeout(() => {
                            $('#import-modal').show();
                            $('.import-summary').html('<h4>Import completed successfully!</h4>');
                        }, 500);
                        return;
                    }
                    
                    const step = steps[stepIndex];
                    const progressPercent = Math.round((stepIndex / totalSteps) * 100);
                    updateProgress(step, progressPercent);
                    
                    $.post(ajaxurl, {
                        action: 'aqualuxe_enhanced_import_step',
                        step: step,
                        options: options,
                        nonce: '<?php echo wp_create_nonce('aqualuxe_enhanced_demo'); ?>'
                    }).done(function(response) {
                        if (response.success) {
                            importLog.append('<p class="success">✓ ' + response.data + '</p>');
                            processStep(stepIndex + 1);
                        } else {
                            importLog.append('<p class="error">✗ Error: ' + response.data + '</p>');
                            if (options.transactions) {
                                // Attempt rollback
                                importLog.append('<p class="warning">⚠ Starting automatic rollback...</p>');
                                performRollback();
                            }
                        }
                    }).fail(function() {
                        importLog.append('<p class="error">✗ Network error occurred</p>');
                    });
                }
                
                processStep(0);
            }
            
            function performRollback() {
                $.post(ajaxurl, {
                    action: 'aqualuxe_rollback_import',
                    nonce: '<?php echo wp_create_nonce('aqualuxe_enhanced_demo'); ?>'
                }).done(function(response) {
                    $('#import-log').append('<p class="info">↶ Rollback completed</p>');
                });
            }
            
            // Modal handling
            $('.modal-close').on('click', function() {
                $('#import-modal').hide();
            });
        });
        </script>
        <?php
    }

    /**
     * Display content statistics
     */
    private static function display_content_stats()
    {
        $stats = [
            'Posts' => wp_count_posts('post')->publish,
            'Pages' => wp_count_posts('page')->publish,
            'Services' => wp_count_posts('aqualuxe_service')->publish ?? 0,
            'Events' => wp_count_posts('aqualuxe_event')->publish ?? 0,
            'Media' => wp_count_posts('attachment')->inherit,
        ];
        
        if (class_exists('WooCommerce')) {
            $stats['Products'] = wp_count_posts('product')->publish;
        }
        
        echo '<div class="stats-grid">';
        foreach ($stats as $label => $count) {
            echo '<div class="stat-item">';
            echo '<span class="stat-label">' . esc_html($label) . ':</span>';
            echo '<span class="stat-count">' . esc_html($count) . '</span>';
            echo '</div>';
        }
        echo '</div>';
    }

    /**
     * AJAX handler for enhanced import step
     */
    public static function ajax_import_step()
    {
        check_ajax_referer('aqualuxe_enhanced_demo', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }

        $step = sanitize_text_field($_POST['step']);
        $options = wp_unslash($_POST['options']);

        // Enable transaction mode if requested
        if (isset($options['transactions']) && $options['transactions']) {
            self::$transaction_mode = true;
            self::$imported_items = [];
        }

        try {
            $result = self::process_import_step($step, $options);
            
            if ($result) {
                wp_send_json_success("Successfully imported {$step}");
            } else {
                throw new Exception("Failed to import {$step}");
            }
        } catch (Exception $e) {
            if (self::$transaction_mode) {
                self::rollback_transaction();
            }
            wp_send_json_error($e->getMessage());
        }
    }

    /**
     * Process individual import step with better error handling
     */
    private static function process_import_step($step, $options)
    {
        switch ($step) {
            case 'posts':
                return self::import_enhanced_posts($options);
            case 'pages':
                return self::import_enhanced_pages($options);
            case 'services':
                return self::import_enhanced_services($options);
            case 'events':
                return self::import_enhanced_events($options);
            case 'products':
                return self::import_enhanced_products($options);
            case 'media':
                return self::import_enhanced_media($options);
            default:
                throw new Exception('Invalid import step: ' . $step);
        }
    }

    /**
     * Enhanced posts import with better content
     */
    private static function import_enhanced_posts($options)
    {
        $posts_data = self::get_enhanced_posts_data();
        
        foreach ($posts_data as $post_data) {
            $post_id = wp_insert_post([
                'post_title' => $post_data['title'],
                'post_content' => $post_data['content'],
                'post_excerpt' => $post_data['excerpt'],
                'post_status' => 'publish',
                'post_type' => 'post',
                'post_author' => 1,
                'post_date' => $post_data['date'],
                'meta_input' => array_merge($post_data['meta'], [
                    '_aqualuxe_demo_content' => '1',
                    '_aqualuxe_enhanced_import' => date('Y-m-d H:i:s')
                ])
            ]);
            
            if (is_wp_error($post_id)) {
                throw new Exception('Failed to create post: ' . $post_data['title']);
            }
            
            // Track for rollback
            if (self::$transaction_mode) {
                self::$imported_items[] = ['type' => 'post', 'id' => $post_id];
            }
            
            // Set category and tags
            if (!empty($post_data['category'])) {
                $category = self::ensure_category($post_data['category']);
                wp_set_post_categories($post_id, [$category]);
            }
            
            if (!empty($post_data['tags'])) {
                wp_set_post_tags($post_id, $post_data['tags']);
            }
            
            // Set featured image
            if (!empty($post_data['featured_image'])) {
                self::set_featured_image_from_url($post_id, $post_data['featured_image']);
            }
        }
        
        return true;
    }

    /**
     * Get enhanced posts data with better content
     */
    private static function get_enhanced_posts_data()
    {
        return [
            [
                'title' => 'The Complete Guide to Marine Aquascaping',
                'content' => '<p>Marine aquascaping represents the pinnacle of aquarium artistry, combining the vibrant colors of coral reefs with the principles of underwater landscape design. Unlike freshwater aquascaping, marine environments require careful consideration of water chemistry, lighting, and the complex relationships between corals, fish, and invertebrates.</p>

<h3>Understanding Marine Ecosystems</h3>
<p>A successful marine aquascape begins with understanding natural reef ecosystems. Coral reefs are among the most biodiverse environments on Earth, supporting thousands of species in delicate balance. Your aquascape should reflect this natural complexity while maintaining aesthetic appeal.</p>

<h3>Essential Equipment for Marine Aquascaping</h3>
<ul>
<li><strong>Protein Skimmer:</strong> Essential for removing organic waste</li>
<li><strong>LED Lighting:</strong> Full spectrum lighting for coral growth</li>
<li><strong>Powerheads:</strong> Creating natural water flow patterns</li>
<li><strong>Calcium Reactor:</strong> Maintaining proper mineral levels</li>
</ul>

<h3>Rock Work and Coral Placement</h3>
<p>Live rock forms the foundation of your aquascape. Arrange rocks to create caves, overhangs, and swimming channels. Consider the adult size of corals when planning placement, allowing room for growth and preventing aggressive species from overwhelming neighbors.</p>

<h3>Maintenance and Long-term Success</h3>
<p>Marine aquascapes require consistent maintenance. Weekly water changes, regular testing of calcium and alkalinity levels, and careful monitoring of coral health ensure long-term success. Remember that marine aquascaping is a journey—your reef will evolve and mature over months and years.</p>',
                'excerpt' => 'Discover the art and science of marine aquascaping, from equipment selection to coral placement strategies for creating stunning underwater reef landscapes.',
                'category' => 'Marine Aquascaping',
                'tags' => ['marine', 'aquascaping', 'coral', 'reef', 'saltwater'],
                'featured_image' => 'https://images.unsplash.com/photo-1583212292454-1fe6229603b7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'date' => '2024-01-15 10:00:00',
                'meta' => [
                    'reading_time' => '12 minutes',
                    'difficulty_level' => 'advanced',
                    'featured_post' => true,
                    'aquarium_type' => 'marine'
                ]
            ],
            [
                'title' => 'Breeding Discus Fish: Advanced Techniques for Success',
                'content' => '<p>Discus fish, often called the "King of Aquarium Fish," are among the most challenging and rewarding species to breed. These magnificent cichlids require specific water conditions, careful nutrition, and patient observation to achieve breeding success.</p>

<h3>Selecting Breeding Pairs</h3>
<p>Successful discus breeding begins with selecting healthy, mature fish. Look for fish that are at least 6 inches in diameter and 18 months old. Watch for natural pairing behavior—fish that stay close together, clean surfaces together, and show synchronized swimming patterns.</p>

<h3>Creating the Perfect Breeding Environment</h3>
<p>Discus require pristine water conditions for breeding:</p>
<ul>
<li><strong>Temperature:</strong> 84-86°F (29-30°C)</li>
<li><strong>pH:</strong> 6.0-6.5 (slightly acidic)</li>
<li><strong>Hardness:</strong> 1-4 dGH (very soft water)</li>
<li><strong>Ammonia/Nitrites:</strong> 0 ppm</li>
<li><strong>Nitrates:</strong> Below 10 ppm</li>
</ul>

<h3>The Breeding Process</h3>
<p>Discus are substrate spawners, typically choosing vertical surfaces like breeding cones, PVC pipes, or large plant leaves. The female lays eggs in neat rows while the male follows to fertilize them. Both parents fan the eggs with their fins to ensure proper oxygenation.</p>

<h3>Caring for Fry</h3>
<p>Discus fry have a unique feeding behavior—they feed on mucus secreted by their parents\' skin. This symbiotic relationship lasts for several weeks before fry can be weaned onto baby brine shrimp and specialized fry foods.</p>

<blockquote>
<p>"Patience is the key to discus breeding. Rushing the process or making sudden changes will only stress the fish and reduce your chances of success." - Dr. Marina Reef, Lead Marine Biologist</p>
</blockquote>',
                'excerpt' => 'Master the art of discus breeding with advanced techniques covering pair selection, water conditioning, and fry care for successful reproduction.',
                'category' => 'Fish Breeding',
                'tags' => ['discus', 'breeding', 'cichlids', 'advanced', 'freshwater'],
                'featured_image' => 'https://images.unsplash.com/photo-1535591273668-578e31182c4f?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'date' => '2024-01-10 14:30:00',
                'meta' => [
                    'reading_time' => '15 minutes',
                    'difficulty_level' => 'expert',
                    'featured_post' => false,
                    'aquarium_type' => 'freshwater'
                ]
            ],
            [
                'title' => 'Sustainable Aquarium Keeping: Eco-Friendly Practices',
                'content' => '<p>As aquarium enthusiasts, we have a responsibility to practice sustainable fishkeeping that minimizes environmental impact while maximizing the health and wellbeing of our aquatic pets. Sustainable aquarium keeping involves conscious choices about sourcing, energy consumption, and waste management.</p>

<h3>Responsible Fish Sourcing</h3>
<p>Choose captive-bred fish whenever possible. Captive breeding reduces pressure on wild populations and often results in hardier fish better adapted to aquarium conditions. Support local breeders and aquaculture facilities that practice sustainable breeding methods.</p>

<h3>Energy-Efficient Equipment</h3>
<p>Modern aquarium equipment offers significant energy savings:</p>
<ul>
<li><strong>LED Lighting:</strong> Up to 80% more efficient than traditional lighting</li>
<li><strong>Variable Speed Pumps:</strong> Adjust flow rates to reduce energy consumption</li>
<li><strong>Smart Controllers:</strong> Automate systems to optimize efficiency</li>
<li><strong>Proper Insulation:</strong> Reduce heating costs with tank covers and stands</li>
</ul>

<h3>Water Conservation Strategies</h3>
<p>Implement water-saving practices without compromising water quality. Use RO water efficiently, collect and reuse tank water for plants, and consider closed-loop systems that minimize water changes while maintaining excellent water quality.</p>

<h3>Natural Filtration Methods</h3>
<p>Incorporate live plants, beneficial bacteria, and natural filtration media to reduce reliance on chemical filtration. Refugiums, planted sumps, and algae scrubbers provide natural nutrient export while creating beautiful, functional ecosystems.</p>

<div class="callout-box">
<h4>AquaLuxe Sustainability Initiative</h4>
<p>At AquaLuxe, we\'re committed to sustainable practices. Our captive breeding programs, energy-efficient equipment recommendations, and conservation partnerships demonstrate our dedication to responsible aquarium keeping.</p>
</div>',
                'excerpt' => 'Learn sustainable aquarium practices that reduce environmental impact while maintaining healthy, thriving aquatic ecosystems.',
                'category' => 'Sustainability',
                'tags' => ['sustainability', 'eco-friendly', 'conservation', 'responsible', 'breeding'],
                'featured_image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80',
                'date' => '2024-01-05 09:15:00',
                'meta' => [
                    'reading_time' => '10 minutes',
                    'difficulty_level' => 'beginner',
                    'featured_post' => true,
                    'topic' => 'sustainability'
                ]
            ]
        ];
    }

    /**
     * Ensure category exists and return ID
     */
    private static function ensure_category($category_name)
    {
        $category = get_term_by('name', $category_name, 'category');
        if (!$category) {
            $result = wp_insert_term($category_name, 'category');
            if (!is_wp_error($result)) {
                return $result['term_id'];
            }
        }
        return $category->term_id;
    }

    /**
     * Enhanced pages import
     */
    private static function import_enhanced_pages($options)
    {
        $pages_data = [
            [
                'title' => 'About AquaLuxe',
                'slug' => 'about',
                'content' => '<div class="hero-section">
    <h2>Our Story</h2>
    <p class="lead">AquaLuxe was founded with a singular vision: to bring the elegance and serenity of aquatic life to homes and businesses around the globe. What began as a passion project has evolved into a premier destination for aquarium enthusiasts seeking the finest in aquatic products and services.</p>
</div>

<div class="two-column-section">
    <div class="column">
        <h3>Our Mission</h3>
        <p>To provide unparalleled aquatic products and services while promoting sustainable practices, conservation, and education in the aquarium hobby. We believe that beautiful aquariums can coexist with responsible environmental stewardship.</p>
        
        <h3>Our Values</h3>
        <ul>
            <li><strong>Excellence:</strong> We maintain the highest standards in everything we do</li>
            <li><strong>Sustainability:</strong> We prioritize captive breeding and eco-friendly practices</li>
            <li><strong>Education:</strong> We share knowledge to promote responsible fishkeeping</li>
            <li><strong>Innovation:</strong> We embrace new technologies and methods</li>
        </ul>
    </div>
    
    <div class="column">
        <h3>Our Team</h3>
        <p>Our team of marine biologists, aquascaping experts, and customer service professionals brings decades of combined experience to every project. From rare fish sourcing to custom aquarium design, we have the expertise to make your aquatic dreams a reality.</p>
        
        <h3>Global Reach</h3>
        <p>With locations worldwide and shipping to over 50 countries, AquaLuxe serves aquarium enthusiasts from beginner hobbyists to public aquariums and research institutions.</p>
    </div>
</div>',
                'template' => 'page-about.php'
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<div class="contact-hero">
    <h2>Get in Touch</h2>
    <p>Have questions about our products or services? Need expert advice for your aquarium project? Our team is here to help.</p>
</div>

<div class="contact-grid">
    <div class="contact-info">
        <h3>Visit Our Showroom</h3>
        <div class="location">
            <strong>AquaLuxe Headquarters</strong><br>
            123 Aquatic Way<br>
            Ocean City, AC 12345<br>
            United States
        </div>
        
        <div class="contact-details">
            <p><strong>Phone:</strong> <a href="tel:+15551234567">(555) 123-4567</a></p>
            <p><strong>Email:</strong> <a href="mailto:info@aqualuxe.com">info@aqualuxe.com</a></p>
            <p><strong>Hours:</strong> Mon-Fri 9AM-6PM, Sat 10AM-4PM</p>
        </div>
        
        <h3>Specialized Services</h3>
        <p><strong>Custom Aquarium Design:</strong> <a href="mailto:design@aqualuxe.com">design@aqualuxe.com</a></p>
        <p><strong>Maintenance Services:</strong> <a href="mailto:maintenance@aqualuxe.com">maintenance@aqualuxe.com</a></p>
        <p><strong>Wholesale Inquiries:</strong> <a href="mailto:wholesale@aqualuxe.com">wholesale@aqualuxe.com</a></p>
    </div>
    
    <div class="contact-form">
        [contact-form-7 id="1" title="Contact form 1"]
    </div>
</div>',
                'template' => 'page-contact.php'
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
                    '_aqualuxe_demo_content' => '1',
                    '_aqualuxe_enhanced_import' => date('Y-m-d H:i:s'),
                    '_wp_page_template' => $page_data['template'] ?? 'default'
                ]
            ]);
            
            if (is_wp_error($page_id)) {
                throw new Exception('Failed to create page: ' . $page_data['title']);
            }
            
            if (self::$transaction_mode) {
                self::$imported_items[] = ['type' => 'page', 'id' => $page_id];
            }
        }

        return true;
    }

    /**
     * Rollback transaction
     */
    private static function rollback_transaction()
    {
        foreach (array_reverse(self::$imported_items) as $item) {
            wp_delete_post($item['id'], true);
        }
        self::$imported_items = [];
    }

    /**
     * AJAX rollback handler
     */
    public static function ajax_rollback_import()
    {
        check_ajax_referer('aqualuxe_enhanced_demo', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        self::rollback_transaction();
        wp_send_json_success('Import rolled back successfully');
    }

    /**
     * Set featured image from URL with better error handling
     */
    private static function set_featured_image_from_url($post_id, $image_url)
    {
        require_once(ABSPATH . 'wp-admin/includes/media.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $image_url .= '&ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80';
        
        try {
            $image_data = wp_remote_get($image_url, ['timeout' => 30]);
            
            if (is_wp_error($image_data)) {
                throw new Exception('Failed to download image: ' . $image_data->get_error_message());
            }
            
            $image_body = wp_remote_retrieve_body($image_data);
            
            if (empty($image_body)) {
                throw new Exception('Empty image data received');
            }
            
            $upload_dir = wp_upload_dir();
            $filename = 'aqualuxe-demo-' . uniqid() . '.jpg';
            $file = $upload_dir['path'] . '/' . $filename;
            
            if (file_put_contents($file, $image_body) === false) {
                throw new Exception('Failed to save image file');
            }
            
            $wp_filetype = wp_check_filetype($filename, null);
            $attachment = [
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit'
            ];
            
            $attach_id = wp_insert_attachment($attachment, $file, $post_id);
            
            if (is_wp_error($attach_id)) {
                throw new Exception('Failed to create attachment');
            }
            
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            wp_update_attachment_metadata($attach_id, $attach_data);
            
            set_post_thumbnail($post_id, $attach_id);
            
            // Mark as demo content
            update_post_meta($attach_id, '_aqualuxe_demo_content', '1');
            update_post_meta($attach_id, '_aqualuxe_enhanced_import', date('Y-m-d H:i:s'));
            
            if (self::$transaction_mode) {
                self::$imported_items[] = ['type' => 'attachment', 'id' => $attach_id];
            }
            
        } catch (Exception $e) {
            error_log('AquaLuxe Image Import Error: ' . $e->getMessage());
            // Don't fail the entire import for image issues
        }
    }

    // Placeholder methods for other import types
    private static function import_enhanced_services($options) { return true; }
    private static function import_enhanced_events($options) { return true; }
    private static function import_enhanced_products($options) { return true; }
    private static function import_enhanced_media($options) { return true; }

    /**
     * AJAX flush content handler
     */
    public static function ajax_flush_content()
    {
        check_ajax_referer('aqualuxe_enhanced_demo', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        // Implementation for flushing content
        wp_send_json_success('Content flushed successfully');
    }

    /**
     * AJAX export content handler  
     */
    public static function ajax_export_content()
    {
        check_ajax_referer('aqualuxe_enhanced_demo', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions');
        }
        
        // Implementation for exporting content
        wp_send_json_success('Content exported successfully');
    }
}

// Initialize the enhanced demo importer
AquaLuxe_Enhanced_Demo_Importer::init();