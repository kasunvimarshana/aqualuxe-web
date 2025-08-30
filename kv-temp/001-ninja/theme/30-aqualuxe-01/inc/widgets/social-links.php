<?php
/**
 * Social Links Widget for AquaLuxe theme
 *
 * @package AquaLuxe
 */

if (!class_exists('AquaLuxe_Social_Links_Widget')) {
    /**
     * Social Links Widget Class
     */
    class AquaLuxe_Social_Links_Widget extends WP_Widget {
        /**
         * Constructor
         */
        public function __construct() {
            parent::__construct(
                'aqualuxe_social_links',
                __('AquaLuxe Social Links', 'aqualuxe'),
                array(
                    'description' => __('Displays social media links.', 'aqualuxe'),
                    'classname'   => 'aqualuxe-social-links',
                )
            );
        }

        /**
         * Widget Front End
         *
         * @param array $args     Widget arguments.
         * @param array $instance Saved values from database.
         */
        public function widget($args, $instance) {
            $title = !empty($instance['title']) ? apply_filters('widget_title', $instance['title']) : __('Follow Us', 'aqualuxe');
            $facebook = !empty($instance['facebook']) ? esc_url($instance['facebook']) : '';
            $twitter = !empty($instance['twitter']) ? esc_url($instance['twitter']) : '';
            $instagram = !empty($instance['instagram']) ? esc_url($instance['instagram']) : '';
            $linkedin = !empty($instance['linkedin']) ? esc_url($instance['linkedin']) : '';
            $youtube = !empty($instance['youtube']) ? esc_url($instance['youtube']) : '';
            $pinterest = !empty($instance['pinterest']) ? esc_url($instance['pinterest']) : '';
            $tiktok = !empty($instance['tiktok']) ? esc_url($instance['tiktok']) : '';
            $show_labels = isset($instance['show_labels']) ? (bool) $instance['show_labels'] : false;
            $icon_style = !empty($instance['icon_style']) ? esc_attr($instance['icon_style']) : 'filled';
            $icon_size = !empty($instance['icon_size']) ? esc_attr($instance['icon_size']) : 'medium';
            $alignment = !empty($instance['alignment']) ? esc_attr($instance['alignment']) : 'center';

            echo $args['before_widget'];

            if ($title) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            $social_links = array(
                'facebook' => array(
                    'url'   => $facebook,
                    'label' => __('Facebook', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>',
                ),
                'twitter' => array(
                    'url'   => $twitter,
                    'label' => __('Twitter', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>',
                ),
                'instagram' => array(
                    'url'   => $instagram,
                    'label' => __('Instagram', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.319 1.347 20.651.935 19.86.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg>',
                ),
                'linkedin' => array(
                    'url'   => $linkedin,
                    'label' => __('LinkedIn', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>',
                ),
                'youtube' => array(
                    'url'   => $youtube,
                    'label' => __('YouTube', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>',
                ),
                'pinterest' => array(
                    'url'   => $pinterest,
                    'label' => __('Pinterest', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>',
                ),
                'tiktok' => array(
                    'url'   => $tiktok,
                    'label' => __('TikTok', 'aqualuxe'),
                    'icon'  => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" class="aqualuxe-social-icon"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>',
                ),
            );

            // Add classes based on settings
            $classes = array(
                'aqualuxe-social-links-list',
                'icon-style-' . $icon_style,
                'icon-size-' . $icon_size,
                'align-' . $alignment,
            );

            echo '<ul class="' . esc_attr(implode(' ', $classes)) . '">';
            
            foreach ($social_links as $network => $data) {
                if (!empty($data['url'])) {
                    echo '<li class="aqualuxe-social-link-item aqualuxe-social-link-' . esc_attr($network) . '">';
                    echo '<a href="' . esc_url($data['url']) . '" target="_blank" rel="noopener noreferrer" aria-label="' . esc_attr($data['label']) . '">';
                    echo $data['icon'];
                    if ($show_labels) {
                        echo '<span class="aqualuxe-social-link-label">' . esc_html($data['label']) . '</span>';
                    }
                    echo '</a>';
                    echo '</li>';
                }
            }
            
            echo '</ul>';

            echo $args['after_widget'];
        }

        /**
         * Widget Backend
         *
         * @param array $instance Previously saved values from database.
         */
        public function form($instance) {
            $title = !empty($instance['title']) ? $instance['title'] : __('Follow Us', 'aqualuxe');
            $facebook = !empty($instance['facebook']) ? $instance['facebook'] : '';
            $twitter = !empty($instance['twitter']) ? $instance['twitter'] : '';
            $instagram = !empty($instance['instagram']) ? $instance['instagram'] : '';
            $linkedin = !empty($instance['linkedin']) ? $instance['linkedin'] : '';
            $youtube = !empty($instance['youtube']) ? $instance['youtube'] : '';
            $pinterest = !empty($instance['pinterest']) ? $instance['pinterest'] : '';
            $tiktok = !empty($instance['tiktok']) ? $instance['tiktok'] : '';
            $show_labels = isset($instance['show_labels']) ? (bool) $instance['show_labels'] : false;
            $icon_style = !empty($instance['icon_style']) ? $instance['icon_style'] : 'filled';
            $icon_size = !empty($instance['icon_size']) ? $instance['icon_size'] : 'medium';
            $alignment = !empty($instance['alignment']) ? $instance['alignment'] : 'center';
            ?>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('facebook')); ?>"><?php esc_html_e('Facebook URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('facebook')); ?>" name="<?php echo esc_attr($this->get_field_name('facebook')); ?>" type="url" value="<?php echo esc_url($facebook); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('twitter')); ?>"><?php esc_html_e('Twitter URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('twitter')); ?>" name="<?php echo esc_attr($this->get_field_name('twitter')); ?>" type="url" value="<?php echo esc_url($twitter); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('instagram')); ?>"><?php esc_html_e('Instagram URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('instagram')); ?>" name="<?php echo esc_attr($this->get_field_name('instagram')); ?>" type="url" value="<?php echo esc_url($instagram); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('linkedin')); ?>"><?php esc_html_e('LinkedIn URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('linkedin')); ?>" name="<?php echo esc_attr($this->get_field_name('linkedin')); ?>" type="url" value="<?php echo esc_url($linkedin); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('youtube')); ?>"><?php esc_html_e('YouTube URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('youtube')); ?>" name="<?php echo esc_attr($this->get_field_name('youtube')); ?>" type="url" value="<?php echo esc_url($youtube); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('pinterest')); ?>"><?php esc_html_e('Pinterest URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('pinterest')); ?>" name="<?php echo esc_attr($this->get_field_name('pinterest')); ?>" type="url" value="<?php echo esc_url($pinterest); ?>">
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('tiktok')); ?>"><?php esc_html_e('TikTok URL:', 'aqualuxe'); ?></label>
                <input class="widefat" id="<?php echo esc_attr($this->get_field_id('tiktok')); ?>" name="<?php echo esc_attr($this->get_field_name('tiktok')); ?>" type="url" value="<?php echo esc_url($tiktok); ?>">
            </p>
            <p>
                <input class="checkbox" type="checkbox" <?php checked($show_labels); ?> id="<?php echo esc_attr($this->get_field_id('show_labels')); ?>" name="<?php echo esc_attr($this->get_field_name('show_labels')); ?>">
                <label for="<?php echo esc_attr($this->get_field_id('show_labels')); ?>"><?php esc_html_e('Display labels?', 'aqualuxe'); ?></label>
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('icon_style')); ?>"><?php esc_html_e('Icon Style:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_style')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_style')); ?>">
                    <option value="filled" <?php selected($icon_style, 'filled'); ?>><?php esc_html_e('Filled', 'aqualuxe'); ?></option>
                    <option value="outline" <?php selected($icon_style, 'outline'); ?>><?php esc_html_e('Outline', 'aqualuxe'); ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('icon_size')); ?>"><?php esc_html_e('Icon Size:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('icon_size')); ?>" name="<?php echo esc_attr($this->get_field_name('icon_size')); ?>">
                    <option value="small" <?php selected($icon_size, 'small'); ?>><?php esc_html_e('Small', 'aqualuxe'); ?></option>
                    <option value="medium" <?php selected($icon_size, 'medium'); ?>><?php esc_html_e('Medium', 'aqualuxe'); ?></option>
                    <option value="large" <?php selected($icon_size, 'large'); ?>><?php esc_html_e('Large', 'aqualuxe'); ?></option>
                </select>
            </p>
            <p>
                <label for="<?php echo esc_attr($this->get_field_id('alignment')); ?>"><?php esc_html_e('Alignment:', 'aqualuxe'); ?></label>
                <select class="widefat" id="<?php echo esc_attr($this->get_field_id('alignment')); ?>" name="<?php echo esc_attr($this->get_field_name('alignment')); ?>">
                    <option value="left" <?php selected($alignment, 'left'); ?>><?php esc_html_e('Left', 'aqualuxe'); ?></option>
                    <option value="center" <?php selected($alignment, 'center'); ?>><?php esc_html_e('Center', 'aqualuxe'); ?></option>
                    <option value="right" <?php selected($alignment, 'right'); ?>><?php esc_html_e('Right', 'aqualuxe'); ?></option>
                </select>
            </p>
            <?php
        }

        /**
         * Sanitize widget form values as they are saved.
         *
         * @param array $new_instance Values just sent to be saved.
         * @param array $old_instance Previously saved values from database.
         *
         * @return array Updated safe values to be saved.
         */
        public function update($new_instance, $old_instance) {
            $instance = array();
            $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
            $instance['facebook'] = (!empty($new_instance['facebook'])) ? esc_url_raw($new_instance['facebook']) : '';
            $instance['twitter'] = (!empty($new_instance['twitter'])) ? esc_url_raw($new_instance['twitter']) : '';
            $instance['instagram'] = (!empty($new_instance['instagram'])) ? esc_url_raw($new_instance['instagram']) : '';
            $instance['linkedin'] = (!empty($new_instance['linkedin'])) ? esc_url_raw($new_instance['linkedin']) : '';
            $instance['youtube'] = (!empty($new_instance['youtube'])) ? esc_url_raw($new_instance['youtube']) : '';
            $instance['pinterest'] = (!empty($new_instance['pinterest'])) ? esc_url_raw($new_instance['pinterest']) : '';
            $instance['tiktok'] = (!empty($new_instance['tiktok'])) ? esc_url_raw($new_instance['tiktok']) : '';
            $instance['show_labels'] = isset($new_instance['show_labels']) ? (bool) $new_instance['show_labels'] : false;
            $instance['icon_style'] = (!empty($new_instance['icon_style'])) ? sanitize_text_field($new_instance['icon_style']) : 'filled';
            $instance['icon_size'] = (!empty($new_instance['icon_size'])) ? sanitize_text_field($new_instance['icon_size']) : 'medium';
            $instance['alignment'] = (!empty($new_instance['alignment'])) ? sanitize_text_field($new_instance['alignment']) : 'center';

            return $instance;
        }
    }
}

/**
 * Register the Social Links Widget
 */
function aqualuxe_register_social_links_widget() {
    register_widget('AquaLuxe_Social_Links_Widget');
}
add_action('widgets_init', 'aqualuxe_register_social_links_widget');