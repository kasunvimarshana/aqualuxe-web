<?php
/**
 * Custom Widgets for AquaLuxe Theme
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register custom widgets
 */
function aqualuxe_register_widgets() {
    register_widget('AquaLuxe_Hero_Widget');
    register_widget('AquaLuxe_Promo_Banner_Widget');
    register_widget('AquaLuxe_Testimonial_Widget');
    register_widget('AquaLuxe_CTA_Widget');
}
add_action('widgets_init', 'aqualuxe_register_widgets');

/**
 * Hero Section Widget
 */
class AquaLuxe_Hero_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_hero_widget',
            esc_html__('AquaLuxe Hero Section', 'aqualuxe'),
            array('description' => esc_html__('Display a luxury hero section with background image', 'aqualuxe'))
        );
    }
    
    /**
     * Widget frontend display
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        $background_image = !empty($instance['background_image']) ? $instance['background_image'] : '';
        
        ?>
        <section class="hero-section" <?php if ($background_image) : ?>style="background-image: url('<?php echo esc_url($background_image); ?>');"<?php endif; ?>>
            <div class="hero-content">
                <?php if ($title) : ?>
                    <h1 class="hero-title"><?php echo esc_html($title); ?></h1>
                <?php endif; ?>
                
                <?php if ($subtitle) : ?>
                    <p class="hero-subtitle"><?php echo esc_html($subtitle); ?></p>
                <?php endif; ?>
                
                <?php if ($button_text && $button_url) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="hero-button"><?php echo esc_html($button_text); ?></a>
                <?php endif; ?>
            </div>
        </section>
        <?php
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget backend form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $subtitle = !empty($instance['subtitle']) ? $instance['subtitle'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        $background_image = !empty($instance['background_image']) ? $instance['background_image'] : '';
        
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('subtitle')); ?>"><?php esc_html_e('Subtitle:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('subtitle')); ?>" name="<?php echo esc_attr($this->get_field_name('subtitle')); ?>" type="text" value="<?php echo esc_attr($subtitle); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Button Text:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_url')); ?>"><?php esc_html_e('Button URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_url')); ?>" name="<?php echo esc_attr($this->get_field_name('button_url')); ?>" type="text" value="<?php echo esc_attr($button_url); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('background_image')); ?>"><?php esc_html_e('Background Image URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('background_image')); ?>" name="<?php echo esc_attr($this->get_field_name('background_image')); ?>" type="text" value="<?php echo esc_attr($background_image); ?>">
        </p>
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['subtitle'] = (!empty($new_instance['subtitle'])) ? sanitize_text_field($new_instance['subtitle']) : '';
        $instance['button_text'] = (!empty($new_instance['button_text'])) ? sanitize_text_field($new_instance['button_text']) : '';
        $instance['button_url'] = (!empty($new_instance['button_url'])) ? esc_url_raw($new_instance['button_url']) : '';
        $instance['background_image'] = (!empty($new_instance['background_image'])) ? esc_url_raw($new_instance['background_image']) : '';
        
        return $instance;
    }
}

/**
 * Promotional Banner Widget
 */
class AquaLuxe_Promo_Banner_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_promo_banner_widget',
            esc_html__('AquaLuxe Promo Banner', 'aqualuxe'),
            array('description' => esc_html__('Display a luxury promotional banner', 'aqualuxe'))
        );
    }
    
    /**
     * Widget frontend display
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        
        ?>
        <section class="promo-banner">
            <div class="promo-banner-content">
                <?php if ($title) : ?>
                    <h3><?php echo esc_html($title); ?></h3>
                <?php endif; ?>
                
                <?php if ($description) : ?>
                    <p><?php echo esc_html($description); ?></p>
                <?php endif; ?>
                
                <?php if ($button_text && $button_url) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="button button-accent"><?php echo esc_html($button_text); ?></a>
                <?php endif; ?>
            </div>
        </section>
        <?php
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget backend form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>"><?php esc_html_e('Description:', 'aqualuxe'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>"><?php echo esc_textarea($description); ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Button Text:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_url')); ?>"><?php esc_html_e('Button URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_url')); ?>" name="<?php echo esc_attr($this->get_field_name('button_url')); ?>" type="text" value="<?php echo esc_attr($button_url); ?>">
        </p>
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['description'] = (!empty($new_instance['description'])) ? sanitize_textarea_field($new_instance['description']) : '';
        $instance['button_text'] = (!empty($new_instance['button_text'])) ? sanitize_text_field($new_instance['button_text']) : '';
        $instance['button_url'] = (!empty($new_instance['button_url'])) ? esc_url_raw($new_instance['button_url']) : '';
        
        return $instance;
    }
}

/**
 * Testimonial Widget
 */
class AquaLuxe_Testimonial_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_testimonial_widget',
            esc_html__('AquaLuxe Testimonial', 'aqualuxe'),
            array('description' => esc_html__('Display a luxury testimonial', 'aqualuxe'))
        );
    }
    
    /**
     * Widget frontend display
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $testimonial = !empty($instance['testimonial']) ? $instance['testimonial'] : '';
        $author = !empty($instance['author']) ? $instance['author'] : '';
        
        ?>
        <div class="luxury-testimonial">
            <?php if ($testimonial) : ?>
                <div class="luxury-testimonial-content">
                    <?php echo esc_html($testimonial); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($author) : ?>
                <div class="luxury-testimonial-author">
                    <?php echo esc_html($author); ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget backend form
     */
    public function form($instance) {
        $testimonial = !empty($instance['testimonial']) ? $instance['testimonial'] : '';
        $author = !empty($instance['author']) ? $instance['author'] : '';
        
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('testimonial')); ?>"><?php esc_html_e('Testimonial:', 'aqualuxe'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('testimonial')); ?>" name="<?php echo esc_attr($this->get_field_name('testimonial')); ?>"><?php echo esc_textarea($testimonial); ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('author')); ?>"><?php esc_html_e('Author:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('author')); ?>" name="<?php echo esc_attr($this->get_field_name('author')); ?>" type="text" value="<?php echo esc_attr($author); ?>">
        </p>
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['testimonial'] = (!empty($new_instance['testimonial'])) ? sanitize_textarea_field($new_instance['testimonial']) : '';
        $instance['author'] = (!empty($new_instance['author'])) ? sanitize_text_field($new_instance['author']) : '';
        
        return $instance;
    }
}

/**
 * Call to Action Widget
 */
class AquaLuxe_CTA_Widget extends WP_Widget {
    
    /**
     * Constructor
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_cta_widget',
            esc_html__('AquaLuxe Call to Action', 'aqualuxe'),
            array('description' => esc_html__('Display a luxury call to action section', 'aqualuxe'))
        );
    }
    
    /**
     * Widget frontend display
     */
    public function widget($args, $instance) {
        echo $args['before_widget'];
        
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        
        ?>
        <section class="cta-section">
            <div class="cta-section-content">
                <?php if ($title) : ?>
                    <h2><?php echo esc_html($title); ?></h2>
                <?php endif; ?>
                
                <?php if ($description) : ?>
                    <p><?php echo esc_html($description); ?></p>
                <?php endif; ?>
                
                <?php if ($button_text && $button_url) : ?>
                    <a href="<?php echo esc_url($button_url); ?>" class="button button-accent"><?php echo esc_html($button_text); ?></a>
                <?php endif; ?>
            </div>
        </section>
        <?php
        
        echo $args['after_widget'];
    }
    
    /**
     * Widget backend form
     */
    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : '';
        $description = !empty($instance['description']) ? $instance['description'] : '';
        $button_text = !empty($instance['button_text']) ? $instance['button_text'] : '';
        $button_url = !empty($instance['button_url']) ? $instance['button_url'] : '';
        
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('description')); ?>"><?php esc_html_e('Description:', 'aqualuxe'); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr($this->get_field_id('description')); ?>" name="<?php echo esc_attr($this->get_field_name('description')); ?>"><?php echo esc_textarea($description); ?></textarea>
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_text')); ?>"><?php esc_html_e('Button Text:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_text')); ?>" name="<?php echo esc_attr($this->get_field_name('button_text')); ?>" type="text" value="<?php echo esc_attr($button_text); ?>">
        </p>
        
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('button_url')); ?>"><?php esc_html_e('Button URL:', 'aqualuxe'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('button_url')); ?>" name="<?php echo esc_attr($this->get_field_name('button_url')); ?>" type="text" value="<?php echo esc_attr($button_url); ?>">
        </p>
        <?php
    }
    
    /**
     * Update widget settings
     */
    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        $instance['description'] = (!empty($new_instance['description'])) ? sanitize_textarea_field($new_instance['description']) : '';
        $instance['button_text'] = (!empty($new_instance['button_text'])) ? sanitize_text_field($new_instance['button_text']) : '';
        $instance['button_url'] = (!empty($new_instance['button_url'])) ? esc_url_raw($new_instance['button_url']) : '';
        
        return $instance;
    }
}