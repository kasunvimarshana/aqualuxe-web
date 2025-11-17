<?php
/**
 * Custom Blocks Module
 *
 * @package AquaLuxe
 * @subpackage Modules
 * @since 1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Custom Blocks Module Class
 */
class AquaLuxe_Custom_Blocks {

    /**
     * Module settings
     *
     * @var array
     */
    private $settings;

    /**
     * Constructor
     */
    public function __construct() {
        $this->settings = get_option( 'aqualuxe_custom_blocks_settings', array(
            'enabled_blocks' => array(
                'cta'               => true,
                'features'          => true,
                'team'              => true,
                'pricing'           => true,
                'stats'             => true,
                'content_image'     => true,
                'services'          => true,
                'portfolio'         => true,
                'timeline'          => true,
                'accordion'         => true,
            ),
            'custom_css_class' => '',
        ) );

        $this->init_hooks();
    }

    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Register blocks
        add_action( 'init', array( $this, 'register_blocks' ) );
        
        // Enqueue scripts and styles
        add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_frontend_assets' ) );
        
        // Register settings page
        add_action( 'admin_menu', array( $this, 'add_settings_page' ) );
        add_action( 'admin_init', array( $this, 'register_settings' ) );
        
        // Register block categories
        add_filter( 'block_categories_all', array( $this, 'register_block_category' ), 10, 2 );
    }

    /**
     * Register blocks
     */
    public function register_blocks() {
        // Check if Gutenberg is available
        if ( ! function_exists( 'register_block_type' ) ) {
            return;
        }

        // Get enabled blocks
        $enabled_blocks = $this->settings['enabled_blocks'];
        
        // Register blocks
        $blocks = array(
            'cta' => array(
                'render_callback' => array( $this, 'render_cta_block' ),
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'content' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'buttonText' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'buttonUrl' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'buttonStyle' => array(
                        'type' => 'string',
                        'default' => 'primary',
                    ),
                    'align' => array(
                        'type' => 'string',
                        'default' => 'center',
                    ),
                    'backgroundColor' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'textColor' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'backgroundImage' => array(
                        'type' => 'object',
                        'default' => null,
                    ),
                    'className' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
            ),
            'features' => array(
                'render_callback' => array( $this, 'render_features_block' ),
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'subtitle' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'columns' => array(
                        'type' => 'number',
                        'default' => 3,
                    ),
                    'features' => array(
                        'type' => 'array',
                        'default' => array(),
                    ),
                    'align' => array(
                        'type' => 'string',
                        'default' => 'center',
                    ),
                    'iconStyle' => array(
                        'type' => 'string',
                        'default' => 'circle',
                    ),
                    'className' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
            ),
            'team' => array(
                'render_callback' => array( $this, 'render_team_block' ),
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'subtitle' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'columns' => array(
                        'type' => 'number',
                        'default' => 3,
                    ),
                    'members' => array(
                        'type' => 'array',
                        'default' => array(),
                    ),
                    'style' => array(
                        'type' => 'string',
                        'default' => 'card',
                    ),
                    'showSocial' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    'className' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
            ),
            'pricing' => array(
                'render_callback' => array( $this, 'render_pricing_block' ),
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'subtitle' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'columns' => array(
                        'type' => 'number',
                        'default' => 3,
                    ),
                    'plans' => array(
                        'type' => 'array',
                        'default' => array(),
                    ),
                    'style' => array(
                        'type' => 'string',
                        'default' => 'boxed',
                    ),
                    'className' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
            ),
            'stats' => array(
                'render_callback' => array( $this, 'render_stats_block' ),
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'subtitle' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'columns' => array(
                        'type' => 'number',
                        'default' => 4,
                    ),
                    'stats' => array(
                        'type' => 'array',
                        'default' => array(),
                    ),
                    'style' => array(
                        'type' => 'string',
                        'default' => 'default',
                    ),
                    'className' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
            ),
            'content_image' => array(
                'render_callback' => array( $this, 'render_content_image_block' ),
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'content' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'image' => array(
                        'type' => 'object',
                        'default' => null,
                    ),
                    'imagePosition' => array(
                        'type' => 'string',
                        'default' => 'right',
                    ),
                    'buttonText' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'buttonUrl' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'buttonStyle' => array(
                        'type' => 'string',
                        'default' => 'primary',
                    ),
                    'className' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
            ),
            'services' => array(
                'render_callback' => array( $this, 'render_services_block' ),
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'subtitle' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'columns' => array(
                        'type' => 'number',
                        'default' => 3,
                    ),
                    'services' => array(
                        'type' => 'array',
                        'default' => array(),
                    ),
                    'style' => array(
                        'type' => 'string',
                        'default' => 'card',
                    ),
                    'showButton' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    'className' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
            ),
            'portfolio' => array(
                'render_callback' => array( $this, 'render_portfolio_block' ),
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'subtitle' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'columns' => array(
                        'type' => 'number',
                        'default' => 3,
                    ),
                    'items' => array(
                        'type' => 'array',
                        'default' => array(),
                    ),
                    'style' => array(
                        'type' => 'string',
                        'default' => 'grid',
                    ),
                    'showFilters' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    'className' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
            ),
            'timeline' => array(
                'render_callback' => array( $this, 'render_timeline_block' ),
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'subtitle' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'items' => array(
                        'type' => 'array',
                        'default' => array(),
                    ),
                    'style' => array(
                        'type' => 'string',
                        'default' => 'vertical',
                    ),
                    'className' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
            ),
            'accordion' => array(
                'render_callback' => array( $this, 'render_accordion_block' ),
                'attributes' => array(
                    'title' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                    'items' => array(
                        'type' => 'array',
                        'default' => array(),
                    ),
                    'style' => array(
                        'type' => 'string',
                        'default' => 'default',
                    ),
                    'openFirst' => array(
                        'type' => 'boolean',
                        'default' => true,
                    ),
                    'allowMultiple' => array(
                        'type' => 'boolean',
                        'default' => false,
                    ),
                    'className' => array(
                        'type' => 'string',
                        'default' => '',
                    ),
                ),
            ),
        );
        
        // Register each enabled block
        foreach ( $blocks as $block_name => $block_config ) {
            if ( isset( $enabled_blocks[ $block_name ] ) && $enabled_blocks[ $block_name ] ) {
                register_block_type(
                    'aqualuxe/' . $block_name,
                    $block_config
                );
            }
        }
    }

    /**
     * Enqueue editor assets
     */
    public function enqueue_editor_assets() {
        // Enqueue block editor script
        wp_enqueue_script(
            'aqualuxe-custom-blocks-editor',
            plugin_dir_url( __FILE__ ) . 'assets/js/blocks-editor.js',
            array( 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n' ),
            '1.0.0',
            true
        );
        
        // Enqueue editor styles
        wp_enqueue_style(
            'aqualuxe-custom-blocks-editor',
            plugin_dir_url( __FILE__ ) . 'assets/css/blocks-editor.css',
            array( 'wp-edit-blocks' ),
            '1.0.0'
        );
        
        // Pass settings to editor script
        wp_localize_script(
            'aqualuxe-custom-blocks-editor',
            'aqualuxeCustomBlocks',
            array(
                'enabledBlocks' => $this->settings['enabled_blocks'],
            )
        );
    }

    /**
     * Enqueue frontend assets
     */
    public function enqueue_frontend_assets() {
        // Enqueue frontend styles
        wp_enqueue_style(
            'aqualuxe-custom-blocks',
            plugin_dir_url( __FILE__ ) . 'assets/css/blocks.css',
            array(),
            '1.0.0'
        );
        
        // Enqueue frontend scripts
        wp_enqueue_script(
            'aqualuxe-custom-blocks',
            plugin_dir_url( __FILE__ ) . 'assets/js/blocks.js',
            array( 'jquery' ),
            '1.0.0',
            true
        );
    }

    /**
     * Register block category
     *
     * @param array  $categories Block categories.
     * @param object $post       Post object.
     * @return array Modified block categories.
     */
    public function register_block_category( $categories, $post ) {
        return array_merge(
            $categories,
            array(
                array(
                    'slug'  => 'aqualuxe-blocks',
                    'title' => __( 'AquaLuxe Blocks', 'aqualuxe' ),
                ),
            )
        );
    }

    /**
     * Render CTA block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_cta_block( $attributes ) {
        // Extract attributes
        $title = isset( $attributes['title'] ) ? $attributes['title'] : '';
        $content = isset( $attributes['content'] ) ? $attributes['content'] : '';
        $button_text = isset( $attributes['buttonText'] ) ? $attributes['buttonText'] : '';
        $button_url = isset( $attributes['buttonUrl'] ) ? $attributes['buttonUrl'] : '';
        $button_style = isset( $attributes['buttonStyle'] ) ? $attributes['buttonStyle'] : 'primary';
        $align = isset( $attributes['align'] ) ? $attributes['align'] : 'center';
        $background_color = isset( $attributes['backgroundColor'] ) ? $attributes['backgroundColor'] : '';
        $text_color = isset( $attributes['textColor'] ) ? $attributes['textColor'] : '';
        $background_image = isset( $attributes['backgroundImage'] ) ? $attributes['backgroundImage'] : null;
        $class_name = isset( $attributes['className'] ) ? $attributes['className'] : '';
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-block',
            'aqualuxe-cta',
            'aqualuxe-cta-align-' . sanitize_html_class( $align ),
        );
        
        if ( ! empty( $class_name ) ) {
            $classes[] = $class_name;
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Build inline styles
        $styles = array();
        
        if ( ! empty( $background_color ) ) {
            $styles[] = 'background-color: ' . $background_color;
        }
        
        if ( ! empty( $text_color ) ) {
            $styles[] = 'color: ' . $text_color;
        }
        
        if ( ! empty( $background_image ) && isset( $background_image['url'] ) ) {
            $styles[] = 'background-image: url(' . esc_url( $background_image['url'] ) . ')';
            $styles[] = 'background-size: cover';
            $styles[] = 'background-position: center';
            $classes[] = 'aqualuxe-cta-has-bg-image';
        }
        
        // Start output
        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo ! empty( $styles ) ? ' style="' . esc_attr( implode( '; ', $styles ) ) . '"' : ''; ?>>
            <div class="aqualuxe-cta-container">
                <?php if ( ! empty( $title ) ) : ?>
                    <h2 class="aqualuxe-cta-title"><?php echo wp_kses_post( $title ); ?></h2>
                <?php endif; ?>
                
                <?php if ( ! empty( $content ) ) : ?>
                    <div class="aqualuxe-cta-content"><?php echo wp_kses_post( $content ); ?></div>
                <?php endif; ?>
                
                <?php if ( ! empty( $button_text ) && ! empty( $button_url ) ) : ?>
                    <div class="aqualuxe-cta-button-wrap">
                        <a href="<?php echo esc_url( $button_url ); ?>" class="aqualuxe-cta-button btn btn-<?php echo esc_attr( $button_style ); ?>">
                            <?php echo esc_html( $button_text ); ?>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render features block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_features_block( $attributes ) {
        // Extract attributes
        $title = isset( $attributes['title'] ) ? $attributes['title'] : '';
        $subtitle = isset( $attributes['subtitle'] ) ? $attributes['subtitle'] : '';
        $columns = isset( $attributes['columns'] ) ? intval( $attributes['columns'] ) : 3;
        $features = isset( $attributes['features'] ) ? $attributes['features'] : array();
        $align = isset( $attributes['align'] ) ? $attributes['align'] : 'center';
        $icon_style = isset( $attributes['iconStyle'] ) ? $attributes['iconStyle'] : 'circle';
        $class_name = isset( $attributes['className'] ) ? $attributes['className'] : '';
        
        // Validate columns
        if ( $columns < 1 ) {
            $columns = 1;
        } elseif ( $columns > 4 ) {
            $columns = 4;
        }
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-block',
            'aqualuxe-features',
            'aqualuxe-features-align-' . sanitize_html_class( $align ),
            'aqualuxe-features-icon-' . sanitize_html_class( $icon_style ),
            'aqualuxe-features-columns-' . $columns,
        );
        
        if ( ! empty( $class_name ) ) {
            $classes[] = $class_name;
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Start output
        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="aqualuxe-features-container">
                <?php if ( ! empty( $title ) || ! empty( $subtitle ) ) : ?>
                    <div class="aqualuxe-features-header">
                        <?php if ( ! empty( $title ) ) : ?>
                            <h2 class="aqualuxe-features-title"><?php echo wp_kses_post( $title ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $subtitle ) ) : ?>
                            <div class="aqualuxe-features-subtitle"><?php echo wp_kses_post( $subtitle ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $features ) ) : ?>
                    <div class="aqualuxe-features-grid">
                        <?php foreach ( $features as $feature ) : ?>
                            <div class="aqualuxe-feature">
                                <?php if ( ! empty( $feature['icon'] ) ) : ?>
                                    <div class="aqualuxe-feature-icon">
                                        <i class="<?php echo esc_attr( $feature['icon'] ); ?>"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="aqualuxe-feature-content">
                                    <?php if ( ! empty( $feature['title'] ) ) : ?>
                                        <h3 class="aqualuxe-feature-title"><?php echo wp_kses_post( $feature['title'] ); ?></h3>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $feature['description'] ) ) : ?>
                                        <div class="aqualuxe-feature-description"><?php echo wp_kses_post( $feature['description'] ); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render team block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_team_block( $attributes ) {
        // Extract attributes
        $title = isset( $attributes['title'] ) ? $attributes['title'] : '';
        $subtitle = isset( $attributes['subtitle'] ) ? $attributes['subtitle'] : '';
        $columns = isset( $attributes['columns'] ) ? intval( $attributes['columns'] ) : 3;
        $members = isset( $attributes['members'] ) ? $attributes['members'] : array();
        $style = isset( $attributes['style'] ) ? $attributes['style'] : 'card';
        $show_social = isset( $attributes['showSocial'] ) ? (bool) $attributes['showSocial'] : true;
        $class_name = isset( $attributes['className'] ) ? $attributes['className'] : '';
        
        // Validate columns
        if ( $columns < 1 ) {
            $columns = 1;
        } elseif ( $columns > 4 ) {
            $columns = 4;
        }
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-block',
            'aqualuxe-team',
            'aqualuxe-team-style-' . sanitize_html_class( $style ),
            'aqualuxe-team-columns-' . $columns,
        );
        
        if ( ! empty( $class_name ) ) {
            $classes[] = $class_name;
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Start output
        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="aqualuxe-team-container">
                <?php if ( ! empty( $title ) || ! empty( $subtitle ) ) : ?>
                    <div class="aqualuxe-team-header">
                        <?php if ( ! empty( $title ) ) : ?>
                            <h2 class="aqualuxe-team-title"><?php echo wp_kses_post( $title ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $subtitle ) ) : ?>
                            <div class="aqualuxe-team-subtitle"><?php echo wp_kses_post( $subtitle ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $members ) ) : ?>
                    <div class="aqualuxe-team-grid">
                        <?php foreach ( $members as $member ) : ?>
                            <div class="aqualuxe-team-member">
                                <?php if ( ! empty( $member['image'] ) && isset( $member['image']['url'] ) ) : ?>
                                    <div class="aqualuxe-team-member-image">
                                        <img src="<?php echo esc_url( $member['image']['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $member['name'] ) ? $member['name'] : '' ); ?>">
                                    </div>
                                <?php endif; ?>
                                
                                <div class="aqualuxe-team-member-content">
                                    <?php if ( ! empty( $member['name'] ) ) : ?>
                                        <h3 class="aqualuxe-team-member-name"><?php echo esc_html( $member['name'] ); ?></h3>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $member['position'] ) ) : ?>
                                        <div class="aqualuxe-team-member-position"><?php echo esc_html( $member['position'] ); ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $member['bio'] ) ) : ?>
                                        <div class="aqualuxe-team-member-bio"><?php echo wp_kses_post( $member['bio'] ); ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $show_social && ! empty( $member['social'] ) ) : ?>
                                        <div class="aqualuxe-team-member-social">
                                            <?php foreach ( $member['social'] as $social ) : ?>
                                                <?php if ( ! empty( $social['icon'] ) && ! empty( $social['url'] ) ) : ?>
                                                    <a href="<?php echo esc_url( $social['url'] ); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-team-member-social-link">
                                                        <i class="<?php echo esc_attr( $social['icon'] ); ?>"></i>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render pricing block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_pricing_block( $attributes ) {
        // Extract attributes
        $title = isset( $attributes['title'] ) ? $attributes['title'] : '';
        $subtitle = isset( $attributes['subtitle'] ) ? $attributes['subtitle'] : '';
        $columns = isset( $attributes['columns'] ) ? intval( $attributes['columns'] ) : 3;
        $plans = isset( $attributes['plans'] ) ? $attributes['plans'] : array();
        $style = isset( $attributes['style'] ) ? $attributes['style'] : 'boxed';
        $class_name = isset( $attributes['className'] ) ? $attributes['className'] : '';
        
        // Validate columns
        if ( $columns < 1 ) {
            $columns = 1;
        } elseif ( $columns > 4 ) {
            $columns = 4;
        }
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-block',
            'aqualuxe-pricing',
            'aqualuxe-pricing-style-' . sanitize_html_class( $style ),
            'aqualuxe-pricing-columns-' . $columns,
        );
        
        if ( ! empty( $class_name ) ) {
            $classes[] = $class_name;
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Start output
        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="aqualuxe-pricing-container">
                <?php if ( ! empty( $title ) || ! empty( $subtitle ) ) : ?>
                    <div class="aqualuxe-pricing-header">
                        <?php if ( ! empty( $title ) ) : ?>
                            <h2 class="aqualuxe-pricing-title"><?php echo wp_kses_post( $title ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $subtitle ) ) : ?>
                            <div class="aqualuxe-pricing-subtitle"><?php echo wp_kses_post( $subtitle ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $plans ) ) : ?>
                    <div class="aqualuxe-pricing-grid">
                        <?php foreach ( $plans as $plan ) : ?>
                            <div class="aqualuxe-pricing-plan<?php echo ! empty( $plan['featured'] ) ? ' aqualuxe-pricing-plan-featured' : ''; ?>">
                                <?php if ( ! empty( $plan['featured'] ) ) : ?>
                                    <div class="aqualuxe-pricing-plan-badge"><?php echo esc_html( ! empty( $plan['featuredText'] ) ? $plan['featuredText'] : __( 'Popular', 'aqualuxe' ) ); ?></div>
                                <?php endif; ?>
                                
                                <div class="aqualuxe-pricing-plan-header">
                                    <?php if ( ! empty( $plan['name'] ) ) : ?>
                                        <h3 class="aqualuxe-pricing-plan-name"><?php echo esc_html( $plan['name'] ); ?></h3>
                                    <?php endif; ?>
                                    
                                    <?php if ( isset( $plan['price'] ) ) : ?>
                                        <div class="aqualuxe-pricing-plan-price">
                                            <?php if ( ! empty( $plan['currency'] ) ) : ?>
                                                <span class="aqualuxe-pricing-plan-currency"><?php echo esc_html( $plan['currency'] ); ?></span>
                                            <?php endif; ?>
                                            
                                            <span class="aqualuxe-pricing-plan-amount"><?php echo esc_html( $plan['price'] ); ?></span>
                                            
                                            <?php if ( ! empty( $plan['period'] ) ) : ?>
                                                <span class="aqualuxe-pricing-plan-period"><?php echo esc_html( $plan['period'] ); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ( ! empty( $plan['description'] ) ) : ?>
                                    <div class="aqualuxe-pricing-plan-description"><?php echo wp_kses_post( $plan['description'] ); ?></div>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $plan['features'] ) ) : ?>
                                    <ul class="aqualuxe-pricing-plan-features">
                                        <?php foreach ( $plan['features'] as $feature ) : ?>
                                            <li class="aqualuxe-pricing-plan-feature">
                                                <?php if ( isset( $feature['included'] ) && ! $feature['included'] ) : ?>
                                                    <span class="aqualuxe-pricing-plan-feature-icon not-included">✕</span>
                                                <?php else : ?>
                                                    <span class="aqualuxe-pricing-plan-feature-icon included">✓</span>
                                                <?php endif; ?>
                                                
                                                <span class="aqualuxe-pricing-plan-feature-text"><?php echo esc_html( $feature['text'] ); ?></span>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $plan['buttonText'] ) && ! empty( $plan['buttonUrl'] ) ) : ?>
                                    <div class="aqualuxe-pricing-plan-action">
                                        <a href="<?php echo esc_url( $plan['buttonUrl'] ); ?>" class="aqualuxe-pricing-plan-button btn btn-<?php echo ! empty( $plan['featured'] ) ? 'primary' : 'outline-primary'; ?>">
                                            <?php echo esc_html( $plan['buttonText'] ); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render stats block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_stats_block( $attributes ) {
        // Extract attributes
        $title = isset( $attributes['title'] ) ? $attributes['title'] : '';
        $subtitle = isset( $attributes['subtitle'] ) ? $attributes['subtitle'] : '';
        $columns = isset( $attributes['columns'] ) ? intval( $attributes['columns'] ) : 4;
        $stats = isset( $attributes['stats'] ) ? $attributes['stats'] : array();
        $style = isset( $attributes['style'] ) ? $attributes['style'] : 'default';
        $class_name = isset( $attributes['className'] ) ? $attributes['className'] : '';
        
        // Validate columns
        if ( $columns < 1 ) {
            $columns = 1;
        } elseif ( $columns > 4 ) {
            $columns = 4;
        }
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-block',
            'aqualuxe-stats',
            'aqualuxe-stats-style-' . sanitize_html_class( $style ),
            'aqualuxe-stats-columns-' . $columns,
        );
        
        if ( ! empty( $class_name ) ) {
            $classes[] = $class_name;
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Start output
        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="aqualuxe-stats-container">
                <?php if ( ! empty( $title ) || ! empty( $subtitle ) ) : ?>
                    <div class="aqualuxe-stats-header">
                        <?php if ( ! empty( $title ) ) : ?>
                            <h2 class="aqualuxe-stats-title"><?php echo wp_kses_post( $title ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $subtitle ) ) : ?>
                            <div class="aqualuxe-stats-subtitle"><?php echo wp_kses_post( $subtitle ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $stats ) ) : ?>
                    <div class="aqualuxe-stats-grid">
                        <?php foreach ( $stats as $stat ) : ?>
                            <div class="aqualuxe-stat">
                                <?php if ( ! empty( $stat['icon'] ) ) : ?>
                                    <div class="aqualuxe-stat-icon">
                                        <i class="<?php echo esc_attr( $stat['icon'] ); ?>"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( isset( $stat['value'] ) ) : ?>
                                    <div class="aqualuxe-stat-value" data-count="<?php echo esc_attr( $stat['value'] ); ?>">
                                        <?php echo esc_html( $stat['value'] ); ?>
                                        
                                        <?php if ( ! empty( $stat['suffix'] ) ) : ?>
                                            <span class="aqualuxe-stat-suffix"><?php echo esc_html( $stat['suffix'] ); ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( ! empty( $stat['label'] ) ) : ?>
                                    <div class="aqualuxe-stat-label"><?php echo esc_html( $stat['label'] ); ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render content image block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_content_image_block( $attributes ) {
        // Extract attributes
        $title = isset( $attributes['title'] ) ? $attributes['title'] : '';
        $content = isset( $attributes['content'] ) ? $attributes['content'] : '';
        $image = isset( $attributes['image'] ) ? $attributes['image'] : null;
        $image_position = isset( $attributes['imagePosition'] ) ? $attributes['imagePosition'] : 'right';
        $button_text = isset( $attributes['buttonText'] ) ? $attributes['buttonText'] : '';
        $button_url = isset( $attributes['buttonUrl'] ) ? $attributes['buttonUrl'] : '';
        $button_style = isset( $attributes['buttonStyle'] ) ? $attributes['buttonStyle'] : 'primary';
        $class_name = isset( $attributes['className'] ) ? $attributes['className'] : '';
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-block',
            'aqualuxe-content-image',
            'aqualuxe-content-image-' . sanitize_html_class( $image_position ),
        );
        
        if ( ! empty( $class_name ) ) {
            $classes[] = $class_name;
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Start output
        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="aqualuxe-content-image-container">
                <div class="aqualuxe-content-image-content">
                    <?php if ( ! empty( $title ) ) : ?>
                        <h2 class="aqualuxe-content-image-title"><?php echo wp_kses_post( $title ); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $content ) ) : ?>
                        <div class="aqualuxe-content-image-text"><?php echo wp_kses_post( $content ); ?></div>
                    <?php endif; ?>
                    
                    <?php if ( ! empty( $button_text ) && ! empty( $button_url ) ) : ?>
                        <div class="aqualuxe-content-image-button-wrap">
                            <a href="<?php echo esc_url( $button_url ); ?>" class="aqualuxe-content-image-button btn btn-<?php echo esc_attr( $button_style ); ?>">
                                <?php echo esc_html( $button_text ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if ( ! empty( $image ) && isset( $image['url'] ) ) : ?>
                    <div class="aqualuxe-content-image-media">
                        <img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $image['alt'] ) ? $image['alt'] : $title ); ?>" class="aqualuxe-content-image-img">
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render services block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_services_block( $attributes ) {
        // Extract attributes
        $title = isset( $attributes['title'] ) ? $attributes['title'] : '';
        $subtitle = isset( $attributes['subtitle'] ) ? $attributes['subtitle'] : '';
        $columns = isset( $attributes['columns'] ) ? intval( $attributes['columns'] ) : 3;
        $services = isset( $attributes['services'] ) ? $attributes['services'] : array();
        $style = isset( $attributes['style'] ) ? $attributes['style'] : 'card';
        $show_button = isset( $attributes['showButton'] ) ? (bool) $attributes['showButton'] : true;
        $class_name = isset( $attributes['className'] ) ? $attributes['className'] : '';
        
        // Validate columns
        if ( $columns < 1 ) {
            $columns = 1;
        } elseif ( $columns > 4 ) {
            $columns = 4;
        }
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-block',
            'aqualuxe-services',
            'aqualuxe-services-style-' . sanitize_html_class( $style ),
            'aqualuxe-services-columns-' . $columns,
        );
        
        if ( ! empty( $class_name ) ) {
            $classes[] = $class_name;
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Start output
        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="aqualuxe-services-container">
                <?php if ( ! empty( $title ) || ! empty( $subtitle ) ) : ?>
                    <div class="aqualuxe-services-header">
                        <?php if ( ! empty( $title ) ) : ?>
                            <h2 class="aqualuxe-services-title"><?php echo wp_kses_post( $title ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $subtitle ) ) : ?>
                            <div class="aqualuxe-services-subtitle"><?php echo wp_kses_post( $subtitle ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $services ) ) : ?>
                    <div class="aqualuxe-services-grid">
                        <?php foreach ( $services as $service ) : ?>
                            <div class="aqualuxe-service">
                                <?php if ( ! empty( $service['image'] ) && isset( $service['image']['url'] ) ) : ?>
                                    <div class="aqualuxe-service-image">
                                        <img src="<?php echo esc_url( $service['image']['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $service['title'] ) ? $service['title'] : '' ); ?>">
                                    </div>
                                <?php elseif ( ! empty( $service['icon'] ) ) : ?>
                                    <div class="aqualuxe-service-icon">
                                        <i class="<?php echo esc_attr( $service['icon'] ); ?>"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="aqualuxe-service-content">
                                    <?php if ( ! empty( $service['title'] ) ) : ?>
                                        <h3 class="aqualuxe-service-title"><?php echo esc_html( $service['title'] ); ?></h3>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $service['description'] ) ) : ?>
                                        <div class="aqualuxe-service-description"><?php echo wp_kses_post( $service['description'] ); ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $show_button && ! empty( $service['buttonText'] ) && ! empty( $service['buttonUrl'] ) ) : ?>
                                        <div class="aqualuxe-service-button-wrap">
                                            <a href="<?php echo esc_url( $service['buttonUrl'] ); ?>" class="aqualuxe-service-button btn btn-<?php echo ! empty( $service['buttonStyle'] ) ? esc_attr( $service['buttonStyle'] ) : 'outline-primary'; ?>">
                                                <?php echo esc_html( $service['buttonText'] ); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render portfolio block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_portfolio_block( $attributes ) {
        // Extract attributes
        $title = isset( $attributes['title'] ) ? $attributes['title'] : '';
        $subtitle = isset( $attributes['subtitle'] ) ? $attributes['subtitle'] : '';
        $columns = isset( $attributes['columns'] ) ? intval( $attributes['columns'] ) : 3;
        $items = isset( $attributes['items'] ) ? $attributes['items'] : array();
        $style = isset( $attributes['style'] ) ? $attributes['style'] : 'grid';
        $show_filters = isset( $attributes['showFilters'] ) ? (bool) $attributes['showFilters'] : true;
        $class_name = isset( $attributes['className'] ) ? $attributes['className'] : '';
        
        // Validate columns
        if ( $columns < 1 ) {
            $columns = 1;
        } elseif ( $columns > 4 ) {
            $columns = 4;
        }
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-block',
            'aqualuxe-portfolio',
            'aqualuxe-portfolio-style-' . sanitize_html_class( $style ),
            'aqualuxe-portfolio-columns-' . $columns,
        );
        
        if ( ! empty( $class_name ) ) {
            $classes[] = $class_name;
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Extract categories for filters
        $categories = array();
        
        if ( $show_filters && ! empty( $items ) ) {
            foreach ( $items as $item ) {
                if ( ! empty( $item['category'] ) ) {
                    $item_categories = explode( ',', $item['category'] );
                    
                    foreach ( $item_categories as $category ) {
                        $category = trim( $category );
                        
                        if ( ! empty( $category ) && ! in_array( $category, $categories, true ) ) {
                            $categories[] = $category;
                        }
                    }
                }
            }
            
            sort( $categories );
        }
        
        // Generate unique ID for this portfolio
        $portfolio_id = 'aqualuxe-portfolio-' . uniqid();
        
        // Start output
        ob_start();
        ?>
        <div id="<?php echo esc_attr( $portfolio_id ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="aqualuxe-portfolio-container">
                <?php if ( ! empty( $title ) || ! empty( $subtitle ) ) : ?>
                    <div class="aqualuxe-portfolio-header">
                        <?php if ( ! empty( $title ) ) : ?>
                            <h2 class="aqualuxe-portfolio-title"><?php echo wp_kses_post( $title ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $subtitle ) ) : ?>
                            <div class="aqualuxe-portfolio-subtitle"><?php echo wp_kses_post( $subtitle ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( $show_filters && ! empty( $categories ) ) : ?>
                    <div class="aqualuxe-portfolio-filters">
                        <button class="aqualuxe-portfolio-filter active" data-filter="*"><?php esc_html_e( 'All', 'aqualuxe' ); ?></button>
                        
                        <?php foreach ( $categories as $category ) : ?>
                            <button class="aqualuxe-portfolio-filter" data-filter="<?php echo esc_attr( sanitize_title( $category ) ); ?>">
                                <?php echo esc_html( $category ); ?>
                            </button>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $items ) ) : ?>
                    <div class="aqualuxe-portfolio-grid">
                        <?php foreach ( $items as $item ) : ?>
                            <?php
                            // Prepare item categories for filtering
                            $item_category_classes = '';
                            $item_category_data = '';
                            
                            if ( ! empty( $item['category'] ) ) {
                                $item_categories = explode( ',', $item['category'] );
                                $item_category_slugs = array();
                                
                                foreach ( $item_categories as $category ) {
                                    $category = trim( $category );
                                    
                                    if ( ! empty( $category ) ) {
                                        $item_category_slugs[] = sanitize_title( $category );
                                    }
                                }
                                
                                $item_category_classes = implode( ' ', $item_category_slugs );
                                $item_category_data = implode( ', ', array_map( 'trim', explode( ',', $item['category'] ) ) );
                            }
                            ?>
                            <div class="aqualuxe-portfolio-item<?php echo ! empty( $item_category_classes ) ? ' ' . esc_attr( $item_category_classes ) : ''; ?>" data-category="<?php echo esc_attr( $item_category_data ); ?>">
                                <?php if ( ! empty( $item['image'] ) && isset( $item['image']['url'] ) ) : ?>
                                    <div class="aqualuxe-portfolio-item-image">
                                        <img src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( ! empty( $item['title'] ) ? $item['title'] : '' ); ?>">
                                        
                                        <div class="aqualuxe-portfolio-item-overlay">
                                            <?php if ( ! empty( $item['title'] ) ) : ?>
                                                <h3 class="aqualuxe-portfolio-item-title"><?php echo esc_html( $item['title'] ); ?></h3>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $item['category'] ) ) : ?>
                                                <div class="aqualuxe-portfolio-item-category"><?php echo esc_html( $item['category'] ); ?></div>
                                            <?php endif; ?>
                                            
                                            <?php if ( ! empty( $item['url'] ) ) : ?>
                                                <div class="aqualuxe-portfolio-item-links">
                                                    <a href="<?php echo esc_url( $item['url'] ); ?>" class="aqualuxe-portfolio-item-link">
                                                        <i class="fas fa-link"></i>
                                                    </a>
                                                    
                                                    <?php if ( ! empty( $item['image'] ) && isset( $item['image']['url'] ) ) : ?>
                                                        <a href="<?php echo esc_url( $item['image']['url'] ); ?>" class="aqualuxe-portfolio-item-zoom" data-lightbox="<?php echo esc_attr( $portfolio_id ); ?>">
                                                            <i class="fas fa-search-plus"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                </div>
                                            <?php elseif ( ! empty( $item['image'] ) && isset( $item['image']['url'] ) ) : ?>
                                                <div class="aqualuxe-portfolio-item-links">
                                                    <a href="<?php echo esc_url( $item['image']['url'] ); ?>" class="aqualuxe-portfolio-item-zoom" data-lightbox="<?php echo esc_attr( $portfolio_id ); ?>">
                                                        <i class="fas fa-search-plus"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $style === 'caption' ) : ?>
                                    <div class="aqualuxe-portfolio-item-caption">
                                        <?php if ( ! empty( $item['title'] ) ) : ?>
                                            <h3 class="aqualuxe-portfolio-item-title">
                                                <?php if ( ! empty( $item['url'] ) ) : ?>
                                                    <a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['title'] ); ?></a>
                                                <?php else : ?>
                                                    <?php echo esc_html( $item['title'] ); ?>
                                                <?php endif; ?>
                                            </h3>
                                        <?php endif; ?>
                                        
                                        <?php if ( ! empty( $item['category'] ) ) : ?>
                                            <div class="aqualuxe-portfolio-item-category"><?php echo esc_html( $item['category'] ); ?></div>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render timeline block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_timeline_block( $attributes ) {
        // Extract attributes
        $title = isset( $attributes['title'] ) ? $attributes['title'] : '';
        $subtitle = isset( $attributes['subtitle'] ) ? $attributes['subtitle'] : '';
        $items = isset( $attributes['items'] ) ? $attributes['items'] : array();
        $style = isset( $attributes['style'] ) ? $attributes['style'] : 'vertical';
        $class_name = isset( $attributes['className'] ) ? $attributes['className'] : '';
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-block',
            'aqualuxe-timeline',
            'aqualuxe-timeline-style-' . sanitize_html_class( $style ),
        );
        
        if ( ! empty( $class_name ) ) {
            $classes[] = $class_name;
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Start output
        ob_start();
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
            <div class="aqualuxe-timeline-container">
                <?php if ( ! empty( $title ) || ! empty( $subtitle ) ) : ?>
                    <div class="aqualuxe-timeline-header">
                        <?php if ( ! empty( $title ) ) : ?>
                            <h2 class="aqualuxe-timeline-title"><?php echo wp_kses_post( $title ); ?></h2>
                        <?php endif; ?>
                        
                        <?php if ( ! empty( $subtitle ) ) : ?>
                            <div class="aqualuxe-timeline-subtitle"><?php echo wp_kses_post( $subtitle ); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ( ! empty( $items ) ) : ?>
                    <div class="aqualuxe-timeline-items">
                        <?php foreach ( $items as $index => $item ) : ?>
                            <div class="aqualuxe-timeline-item<?php echo ( $index % 2 === 0 ) ? ' aqualuxe-timeline-item-even' : ' aqualuxe-timeline-item-odd'; ?>">
                                <?php if ( $style === 'vertical' ) : ?>
                                    <div class="aqualuxe-timeline-marker"></div>
                                <?php endif; ?>
                                
                                <div class="aqualuxe-timeline-content">
                                    <?php if ( ! empty( $item['date'] ) ) : ?>
                                        <div class="aqualuxe-timeline-date"><?php echo esc_html( $item['date'] ); ?></div>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $item['title'] ) ) : ?>
                                        <h3 class="aqualuxe-timeline-item-title"><?php echo esc_html( $item['title'] ); ?></h3>
                                    <?php endif; ?>
                                    
                                    <?php if ( ! empty( $item['content'] ) ) : ?>
                                        <div class="aqualuxe-timeline-item-content"><?php echo wp_kses_post( $item['content'] ); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Render accordion block
     *
     * @param array $attributes Block attributes.
     * @return string Block output.
     */
    public function render_accordion_block( $attributes ) {
        // Extract attributes
        $title = isset( $attributes['title'] ) ? $attributes['title'] : '';
        $items = isset( $attributes['items'] ) ? $attributes['items'] : array();
        $style = isset( $attributes['style'] ) ? $attributes['style'] : 'default';
        $open_first = isset( $attributes['openFirst'] ) ? (bool) $attributes['openFirst'] : true;
        $allow_multiple = isset( $attributes['allowMultiple'] ) ? (bool) $attributes['allowMultiple'] : false;
        $class_name = isset( $attributes['className'] ) ? $attributes['className'] : '';
        
        // Build CSS classes
        $classes = array(
            'aqualuxe-block',
            'aqualuxe-accordion',
            'aqualuxe-accordion-style-' . sanitize_html_class( $style ),
        );
        
        if ( ! empty( $class_name ) ) {
            $classes[] = $class_name;
        }
        
        if ( ! empty( $this->settings['custom_css_class'] ) ) {
            $classes[] = sanitize_html_class( $this->settings['custom_css_class'] );
        }
        
        // Generate unique ID for this accordion
        $accordion_id = 'aqualuxe-accordion-' . uniqid();
        
        // Start output
        ob_start();
        ?>
        <div id="<?php echo esc_attr( $accordion_id ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" data-allow-multiple="<?php echo $allow_multiple ? 'true' : 'false'; ?>">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="aqualuxe-accordion-title"><?php echo wp_kses_post( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $items ) ) : ?>
                <div class="aqualuxe-accordion-items">
                    <?php foreach ( $items as $index => $item ) : ?>
                        <?php
                        // Check if this item should be open
                        $is_open = ( $index === 0 && $open_first );
                        
                        // Generate unique IDs for accessibility
                        $item_id = 'aqualuxe-accordion-item-' . uniqid();
                        $header_id = $item_id . '-header';
                        $panel_id = $item_id . '-panel';
                        ?>
                        <div class="aqualuxe-accordion-item<?php echo $is_open ? ' aqualuxe-accordion-item-active' : ''; ?>">
                            <h3 class="aqualuxe-accordion-header" id="<?php echo esc_attr( $header_id ); ?>">
                                <button class="aqualuxe-accordion-button" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $panel_id ); ?>">
                                    <?php echo esc_html( ! empty( $item['title'] ) ? $item['title'] : '' ); ?>
                                    <span class="aqualuxe-accordion-icon"></span>
                                </button>
                            </h3>
                            
                            <div id="<?php echo esc_attr( $panel_id ); ?>" class="aqualuxe-accordion-panel" aria-labelledby="<?php echo esc_attr( $header_id ); ?>" role="region"<?php echo ! $is_open ? ' hidden' : ''; ?>>
                                <div class="aqualuxe-accordion-content">
                                    <?php echo wp_kses_post( ! empty( $item['content'] ) ? $item['content'] : '' ); ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        return ob_get_clean();
    }

    /**
     * Add settings page
     */
    public function add_settings_page() {
        add_submenu_page(
            'options-general.php',
            __( 'Custom Blocks Settings', 'aqualuxe' ),
            __( 'Custom Blocks', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-custom-blocks',
            array( $this, 'render_settings_page' )
        );
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting(
            'aqualuxe_custom_blocks_settings',
            'aqualuxe_custom_blocks_settings',
            array( $this, 'sanitize_settings' )
        );

        // General settings section
        add_settings_section(
            'aqualuxe_custom_blocks_general',
            __( 'General Settings', 'aqualuxe' ),
            array( $this, 'render_general_section' ),
            'aqualuxe-custom-blocks'
        );

        // Add settings fields
        add_settings_field(
            'enabled_blocks',
            __( 'Enabled Blocks', 'aqualuxe' ),
            array( $this, 'render_enabled_blocks_field' ),
            'aqualuxe-custom-blocks',
            'aqualuxe_custom_blocks_general'
        );

        add_settings_field(
            'custom_css_class',
            __( 'Custom CSS Class', 'aqualuxe' ),
            array( $this, 'render_custom_css_class_field' ),
            'aqualuxe-custom-blocks',
            'aqualuxe_custom_blocks_general'
        );
    }

    /**
     * Sanitize settings
     *
     * @param array $input Settings input.
     * @return array Sanitized settings.
     */
    public function sanitize_settings( $input ) {
        $sanitized = array();

        // Sanitize enabled blocks
        $sanitized['enabled_blocks'] = array();
        
        $available_blocks = array(
            'cta',
            'features',
            'team',
            'pricing',
            'stats',
            'content_image',
            'services',
            'portfolio',
            'timeline',
            'accordion',
        );
        
        foreach ( $available_blocks as $block ) {
            $sanitized['enabled_blocks'][ $block ] = isset( $input['enabled_blocks'][ $block ] ) ? (bool) $input['enabled_blocks'][ $block ] : false;
        }

        // Sanitize custom CSS class
        $sanitized['custom_css_class'] = isset( $input['custom_css_class'] ) ? sanitize_html_class( $input['custom_css_class'] ) : '';

        return $sanitized;
    }

    /**
     * Render settings page
     */
    public function render_settings_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
            <form action="options.php" method="post">
                <?php
                settings_fields( 'aqualuxe_custom_blocks_settings' );
                do_settings_sections( 'aqualuxe-custom-blocks' );
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    /**
     * Render general section
     */
    public function render_general_section() {
        echo '<p>' . esc_html__( 'Configure the general settings for custom blocks.', 'aqualuxe' ) . '</p>';
    }

    /**
     * Render enabled blocks field
     */
    public function render_enabled_blocks_field() {
        $enabled_blocks = isset( $this->settings['enabled_blocks'] ) ? $this->settings['enabled_blocks'] : array();
        
        $available_blocks = array(
            'cta'           => __( 'Call to Action', 'aqualuxe' ),
            'features'      => __( 'Features', 'aqualuxe' ),
            'team'          => __( 'Team', 'aqualuxe' ),
            'pricing'       => __( 'Pricing', 'aqualuxe' ),
            'stats'         => __( 'Stats', 'aqualuxe' ),
            'content_image' => __( 'Content with Image', 'aqualuxe' ),
            'services'      => __( 'Services', 'aqualuxe' ),
            'portfolio'     => __( 'Portfolio', 'aqualuxe' ),
            'timeline'      => __( 'Timeline', 'aqualuxe' ),
            'accordion'     => __( 'Accordion', 'aqualuxe' ),
        );
        
        foreach ( $available_blocks as $block_id => $block_name ) {
            $checked = isset( $enabled_blocks[ $block_id ] ) ? $enabled_blocks[ $block_id ] : true;
            ?>
            <label>
                <input type="checkbox" name="aqualuxe_custom_blocks_settings[enabled_blocks][<?php echo esc_attr( $block_id ); ?>]" value="1" <?php checked( $checked ); ?> />
                <?php echo esc_html( $block_name ); ?>
            </label><br>
            <?php
        }
    }

    /**
     * Render custom CSS class field
     */
    public function render_custom_css_class_field() {
        $custom_css_class = isset( $this->settings['custom_css_class'] ) ? $this->settings['custom_css_class'] : '';
        ?>
        <input type="text" name="aqualuxe_custom_blocks_settings[custom_css_class]" value="<?php echo esc_attr( $custom_css_class ); ?>" class="regular-text" />
        <p class="description"><?php esc_html_e( 'Add a custom CSS class to all blocks.', 'aqualuxe' ); ?></p>
        <?php
    }
}

// Initialize the module
new AquaLuxe_Custom_Blocks();