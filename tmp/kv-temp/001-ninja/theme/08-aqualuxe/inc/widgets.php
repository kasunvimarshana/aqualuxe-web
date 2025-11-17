<?php
/**
 * Custom Widgets for AquaLuxe Theme
 *
 * @package AquaLuxe
 */

/**
 * Register custom widgets
 */
function aqualuxe_register_widgets() {
    register_widget( 'AquaLuxe_About_Widget' );
    register_widget( 'AquaLuxe_Recent_Posts_Widget' );
    register_widget( 'AquaLuxe_Social_Widget' );
    register_widget( 'AquaLuxe_Contact_Info_Widget' );
    
    if ( class_exists( 'WooCommerce' ) ) {
        register_widget( 'AquaLuxe_Featured_Products_Widget' );
        register_widget( 'AquaLuxe_Product_Categories_Widget' );
        register_widget( 'AquaLuxe_Product_Filter_Widget' );
    }
    
    register_widget( 'AquaLuxe_Services_Widget' );
    register_widget( 'AquaLuxe_Testimonials_Widget' );
    register_widget( 'AquaLuxe_Newsletter_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_register_widgets' );

/**
 * About Widget
 */
class AquaLuxe_About_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_about_widget', // Base ID
            esc_html__( 'AquaLuxe: About', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display about information with logo and text.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $logo = ! empty( $instance['logo'] ) ? $instance['logo'] : '';
        $text = ! empty( $instance['text'] ) ? $instance['text'] : '';
        $show_social = ! empty( $instance['show_social'] ) ? (bool) $instance['show_social'] : false;
        
        echo '<div class="about-widget">';
        
        if ( ! empty( $logo ) ) {
            echo '<div class="about-logo mb-4">';
            echo '<img src="' . esc_url( $logo ) . '" alt="' . esc_attr( get_bloginfo( 'name' ) ) . '" class="max-w-[200px] h-auto">';
            echo '</div>';
        }
        
        if ( ! empty( $text ) ) {
            echo '<div class="about-text text-gray-700 dark:text-gray-300 mb-4">';
            echo wp_kses_post( wpautop( $text ) );
            echo '</div>';
        }
        
        if ( $show_social ) {
            echo '<div class="about-social">';
            aqualuxe_social_icons();
            echo '</div>';
        }
        
        echo '</div>';
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : '';
        $logo = ! empty( $instance['logo'] ) ? $instance['logo'] : '';
        $text = ! empty( $instance['text'] ) ? $instance['text'] : '';
        $show_social = ! empty( $instance['show_social'] ) ? (bool) $instance['show_social'] : false;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'logo' ) ); ?>"><?php esc_html_e( 'Logo URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'logo' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'logo' ) ); ?>" type="text" value="<?php echo esc_url( $logo ); ?>">
            <button class="button button-secondary aqualuxe-media-upload" data-target="#<?php echo esc_attr( $this->get_field_id( 'logo' ) ); ?>" type="button"><?php esc_html_e( 'Upload Logo', 'aqualuxe' ); ?></button>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>"><?php esc_html_e( 'About Text:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" rows="5"><?php echo esc_textarea( $text ); ?></textarea>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_social ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_social' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_social' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_social' ) ); ?>"><?php esc_html_e( 'Show social icons', 'aqualuxe' ); ?></label>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['logo'] = ( ! empty( $new_instance['logo'] ) ) ? esc_url_raw( $new_instance['logo'] ) : '';
        $instance['text'] = ( ! empty( $new_instance['text'] ) ) ? wp_kses_post( $new_instance['text'] ) : '';
        $instance['show_social'] = ( ! empty( $new_instance['show_social'] ) ) ? true : false;

        return $instance;
    }
}

/**
 * Recent Posts Widget
 */
class AquaLuxe_Recent_Posts_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_recent_posts_widget', // Base ID
            esc_html__( 'AquaLuxe: Recent Posts', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display recent posts with thumbnails.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = ! empty( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = ! empty( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        
        $query_args = array(
            'post_type'           => 'post',
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
        );
        
        if ( $category ) {
            $query_args['cat'] = $category;
        }
        
        $recent_posts = new WP_Query( $query_args );
        
        if ( $recent_posts->have_posts() ) :
            echo '<div class="recent-posts-widget">';
            
            while ( $recent_posts->have_posts() ) :
                $recent_posts->the_post();
                ?>
                <div class="recent-post flex mb-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0 last:mb-0 last:pb-0">
                    <?php if ( $show_thumbnail && has_post_thumbnail() ) : ?>
                        <div class="recent-post-thumbnail mr-4">
                            <a href="<?php the_permalink(); ?>" class="block w-16 h-16 overflow-hidden rounded">
                                <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                            </a>
                        </div>
                    <?php endif; ?>
                    
                    <div class="recent-post-content flex-1">
                        <h4 class="recent-post-title text-sm font-medium mb-1">
                            <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">
                                <?php the_title(); ?>
                            </a>
                        </h4>
                        
                        <?php if ( $show_date ) : ?>
                            <div class="recent-post-date text-xs text-gray-600 dark:text-gray-400">
                                <?php echo get_the_date(); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php
            endwhile;
            
            echo '</div>';
            
            wp_reset_postdata();
        endif;
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Posts', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = ! empty( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = ! empty( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'aqualuxe' ); ?></label>
            <?php
            wp_dropdown_categories( array(
                'show_option_all' => esc_html__( 'All Categories', 'aqualuxe' ),
                'name'            => $this->get_field_name( 'category' ),
                'selected'        => $category,
                'hierarchical'    => true,
                'class'           => 'widefat',
            ) );
            ?>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"><?php esc_html_e( 'Display post thumbnail?', 'aqualuxe' ); ?></label>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;
        $instance['show_date'] = ( ! empty( $new_instance['show_date'] ) ) ? true : false;
        $instance['show_thumbnail'] = ( ! empty( $new_instance['show_thumbnail'] ) ) ? true : false;
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? absint( $new_instance['category'] ) : 0;

        return $instance;
    }
}

/**
 * Social Widget
 */
class AquaLuxe_Social_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_social_widget', // Base ID
            esc_html__( 'AquaLuxe: Social Icons', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display social media icons.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : '';
        $tiktok = ! empty( $instance['tiktok'] ) ? $instance['tiktok'] : '';
        
        echo '<div class="social-widget flex flex-wrap gap-3">';
        
        if ( ! empty( $facebook ) ) {
            echo '<a href="' . esc_url( $facebook ) . '" target="_blank" rel="noopener noreferrer" class="social-icon w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 hover:bg-blue-600 text-gray-700 hover:text-white dark:bg-gray-700 dark:hover:bg-blue-600 dark:text-gray-300 dark:hover:text-white transition-colors duration-300" aria-label="' . esc_attr__( 'Facebook', 'aqualuxe' ) . '">';
            echo '<i class="fab fa-facebook-f"></i>';
            echo '</a>';
        }
        
        if ( ! empty( $twitter ) ) {
            echo '<a href="' . esc_url( $twitter ) . '" target="_blank" rel="noopener noreferrer" class="social-icon w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 hover:bg-blue-400 text-gray-700 hover:text-white dark:bg-gray-700 dark:hover:bg-blue-400 dark:text-gray-300 dark:hover:text-white transition-colors duration-300" aria-label="' . esc_attr__( 'Twitter', 'aqualuxe' ) . '">';
            echo '<i class="fab fa-twitter"></i>';
            echo '</a>';
        }
        
        if ( ! empty( $instagram ) ) {
            echo '<a href="' . esc_url( $instagram ) . '" target="_blank" rel="noopener noreferrer" class="social-icon w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 hover:bg-pink-600 text-gray-700 hover:text-white dark:bg-gray-700 dark:hover:bg-pink-600 dark:text-gray-300 dark:hover:text-white transition-colors duration-300" aria-label="' . esc_attr__( 'Instagram', 'aqualuxe' ) . '">';
            echo '<i class="fab fa-instagram"></i>';
            echo '</a>';
        }
        
        if ( ! empty( $linkedin ) ) {
            echo '<a href="' . esc_url( $linkedin ) . '" target="_blank" rel="noopener noreferrer" class="social-icon w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 hover:bg-blue-700 text-gray-700 hover:text-white dark:bg-gray-700 dark:hover:bg-blue-700 dark:text-gray-300 dark:hover:text-white transition-colors duration-300" aria-label="' . esc_attr__( 'LinkedIn', 'aqualuxe' ) . '">';
            echo '<i class="fab fa-linkedin-in"></i>';
            echo '</a>';
        }
        
        if ( ! empty( $youtube ) ) {
            echo '<a href="' . esc_url( $youtube ) . '" target="_blank" rel="noopener noreferrer" class="social-icon w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 hover:bg-red-600 text-gray-700 hover:text-white dark:bg-gray-700 dark:hover:bg-red-600 dark:text-gray-300 dark:hover:text-white transition-colors duration-300" aria-label="' . esc_attr__( 'YouTube', 'aqualuxe' ) . '">';
            echo '<i class="fab fa-youtube"></i>';
            echo '</a>';
        }
        
        if ( ! empty( $pinterest ) ) {
            echo '<a href="' . esc_url( $pinterest ) . '" target="_blank" rel="noopener noreferrer" class="social-icon w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 hover:bg-red-700 text-gray-700 hover:text-white dark:bg-gray-700 dark:hover:bg-red-700 dark:text-gray-300 dark:hover:text-white transition-colors duration-300" aria-label="' . esc_attr__( 'Pinterest', 'aqualuxe' ) . '">';
            echo '<i class="fab fa-pinterest-p"></i>';
            echo '</a>';
        }
        
        if ( ! empty( $tiktok ) ) {
            echo '<a href="' . esc_url( $tiktok ) . '" target="_blank" rel="noopener noreferrer" class="social-icon w-10 h-10 flex items-center justify-center rounded-full bg-gray-200 hover:bg-black text-gray-700 hover:text-white dark:bg-gray-700 dark:hover:bg-black dark:text-gray-300 dark:hover:text-white transition-colors duration-300" aria-label="' . esc_attr__( 'TikTok', 'aqualuxe' ) . '">';
            echo '<i class="fab fa-tiktok"></i>';
            echo '</a>';
        }
        
        echo '</div>';
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Follow Us', 'aqualuxe' );
        $facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : '';
        $tiktok = ! empty( $instance['tiktok'] ) ? $instance['tiktok'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="url" value="<?php echo esc_url( $facebook ); ?>" placeholder="https://">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="url" value="<?php echo esc_url( $twitter ); ?>" placeholder="https://">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="url" value="<?php echo esc_url( $instagram ); ?>" placeholder="https://">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="url" value="<?php echo esc_url( $linkedin ); ?>" placeholder="https://">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'YouTube:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" type="url" value="<?php echo esc_url( $youtube ); ?>" placeholder="https://">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>"><?php esc_html_e( 'Pinterest:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest' ) ); ?>" type="url" value="<?php echo esc_url( $pinterest ); ?>" placeholder="https://">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'tiktok' ) ); ?>"><?php esc_html_e( 'TikTok:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tiktok' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tiktok' ) ); ?>" type="url" value="<?php echo esc_url( $tiktok ); ?>" placeholder="https://">
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['facebook'] = ( ! empty( $new_instance['facebook'] ) ) ? esc_url_raw( $new_instance['facebook'] ) : '';
        $instance['twitter'] = ( ! empty( $new_instance['twitter'] ) ) ? esc_url_raw( $new_instance['twitter'] ) : '';
        $instance['instagram'] = ( ! empty( $new_instance['instagram'] ) ) ? esc_url_raw( $new_instance['instagram'] ) : '';
        $instance['linkedin'] = ( ! empty( $new_instance['linkedin'] ) ) ? esc_url_raw( $new_instance['linkedin'] ) : '';
        $instance['youtube'] = ( ! empty( $new_instance['youtube'] ) ) ? esc_url_raw( $new_instance['youtube'] ) : '';
        $instance['pinterest'] = ( ! empty( $new_instance['pinterest'] ) ) ? esc_url_raw( $new_instance['pinterest'] ) : '';
        $instance['tiktok'] = ( ! empty( $new_instance['tiktok'] ) ) ? esc_url_raw( $new_instance['tiktok'] ) : '';

        return $instance;
    }
}

/**
 * Contact Info Widget
 */
class AquaLuxe_Contact_Info_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_contact_info_widget', // Base ID
            esc_html__( 'AquaLuxe: Contact Info', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display contact information.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $address = ! empty( $instance['address'] ) ? $instance['address'] : '';
        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
        $email = ! empty( $instance['email'] ) ? $instance['email'] : '';
        $hours = ! empty( $instance['hours'] ) ? $instance['hours'] : '';
        
        echo '<div class="contact-info-widget">';
        
        if ( ! empty( $address ) ) {
            echo '<div class="contact-address flex items-start mb-3">';
            echo '<div class="contact-icon mr-3 text-primary-600 dark:text-primary-400">';
            echo '<i class="fas fa-map-marker-alt mt-1"></i>';
            echo '</div>';
            echo '<div class="contact-text text-gray-700 dark:text-gray-300">';
            echo wp_kses_post( wpautop( $address ) );
            echo '</div>';
            echo '</div>';
        }
        
        if ( ! empty( $phone ) ) {
            echo '<div class="contact-phone flex items-start mb-3">';
            echo '<div class="contact-icon mr-3 text-primary-600 dark:text-primary-400">';
            echo '<i class="fas fa-phone-alt mt-1"></i>';
            echo '</div>';
            echo '<div class="contact-text text-gray-700 dark:text-gray-300">';
            $phones = explode( ',', $phone );
            foreach ( $phones as $phone_number ) {
                $phone_number = trim( $phone_number );
                echo '<a href="tel:' . esc_attr( preg_replace( '/[^0-9+]/', '', $phone_number ) ) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">' . esc_html( $phone_number ) . '</a><br>';
            }
            echo '</div>';
            echo '</div>';
        }
        
        if ( ! empty( $email ) ) {
            echo '<div class="contact-email flex items-start mb-3">';
            echo '<div class="contact-icon mr-3 text-primary-600 dark:text-primary-400">';
            echo '<i class="fas fa-envelope mt-1"></i>';
            echo '</div>';
            echo '<div class="contact-text text-gray-700 dark:text-gray-300">';
            $emails = explode( ',', $email );
            foreach ( $emails as $email_address ) {
                $email_address = trim( $email_address );
                echo '<a href="mailto:' . esc_attr( $email_address ) . '" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">' . esc_html( $email_address ) . '</a><br>';
            }
            echo '</div>';
            echo '</div>';
        }
        
        if ( ! empty( $hours ) ) {
            echo '<div class="contact-hours flex items-start">';
            echo '<div class="contact-icon mr-3 text-primary-600 dark:text-primary-400">';
            echo '<i class="fas fa-clock mt-1"></i>';
            echo '</div>';
            echo '<div class="contact-text text-gray-700 dark:text-gray-300">';
            echo wp_kses_post( wpautop( $hours ) );
            echo '</div>';
            echo '</div>';
        }
        
        echo '</div>';
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Contact Info', 'aqualuxe' );
        $address = ! empty( $instance['address'] ) ? $instance['address'] : '';
        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
        $email = ! empty( $instance['email'] ) ? $instance['email'] : '';
        $hours = ! empty( $instance['hours'] ) ? $instance['hours'] : '';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>" rows="3"><?php echo esc_textarea( $address ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>" placeholder="<?php esc_attr_e( 'Separate multiple numbers with commas', 'aqualuxe' ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="text" value="<?php echo esc_attr( $email ); ?>" placeholder="<?php esc_attr_e( 'Separate multiple emails with commas', 'aqualuxe' ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'hours' ) ); ?>"><?php esc_html_e( 'Business Hours:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'hours' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hours' ) ); ?>" rows="3"><?php echo esc_textarea( $hours ); ?></textarea>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['address'] = ( ! empty( $new_instance['address'] ) ) ? wp_kses_post( $new_instance['address'] ) : '';
        $instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? sanitize_text_field( $new_instance['phone'] ) : '';
        $instance['email'] = ( ! empty( $new_instance['email'] ) ) ? sanitize_text_field( $new_instance['email'] ) : '';
        $instance['hours'] = ( ! empty( $new_instance['hours'] ) ) ? wp_kses_post( $new_instance['hours'] ) : '';

        return $instance;
    }
}

/**
 * Featured Products Widget
 */
class AquaLuxe_Featured_Products_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_featured_products_widget', // Base ID
            esc_html__( 'AquaLuxe: Featured Products', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display featured products.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
        $show_rating = ! empty( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
        $product_type = ! empty( $instance['product_type'] ) ? $instance['product_type'] : 'featured';
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        
        $query_args = array(
            'posts_per_page' => $number,
            'post_type'      => 'product',
            'post_status'    => 'publish',
            'no_found_rows'  => true,
        );
        
        switch ( $product_type ) {
            case 'featured':
                $query_args['tax_query'] = array(
                    array(
                        'taxonomy' => 'product_visibility',
                        'field'    => 'name',
                        'terms'    => 'featured',
                        'operator' => 'IN',
                    ),
                );
                break;
            case 'sale':
                $product_ids_on_sale = wc_get_product_ids_on_sale();
                $query_args['post__in'] = $product_ids_on_sale;
                break;
            case 'best_selling':
                $query_args['meta_key'] = 'total_sales';
                $query_args['orderby'] = 'meta_value_num';
                break;
            case 'top_rated':
                $query_args['meta_key'] = '_wc_average_rating';
                $query_args['orderby'] = 'meta_value_num';
                break;
            case 'newest':
                $query_args['orderby'] = 'date';
                $query_args['order'] = 'DESC';
                break;
        }
        
        if ( $category ) {
            $query_args['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field'    => 'term_id',
                'terms'    => $category,
            );
        }
        
        $products = new WP_Query( $query_args );
        
        if ( $products->have_posts() ) {
            echo '<div class="featured-products-widget grid grid-cols-2 gap-4">';
            
            while ( $products->have_posts() ) {
                $products->the_post();
                global $product;
                
                if ( ! $product ) {
                    continue;
                }
                
                echo '<div class="featured-product bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden transition-shadow duration-300 hover:shadow-md">';
                
                echo '<a href="' . esc_url( get_permalink() ) . '" class="block">';
                if ( has_post_thumbnail() ) {
                    echo '<div class="product-image relative overflow-hidden">';
                    echo woocommerce_get_product_thumbnail( 'woocommerce_thumbnail' );
                    
                    if ( $product->is_on_sale() ) {
                        echo '<span class="onsale absolute top-2 left-2 bg-red-600 text-white text-xs font-bold uppercase py-1 px-2 rounded">' . esc_html__( 'Sale!', 'aqualuxe' ) . '</span>';
                    }
                    
                    echo '</div>';
                }
                echo '</a>';
                
                echo '<div class="product-details p-3">';
                
                echo '<h4 class="product-title text-sm font-medium mb-1">';
                echo '<a href="' . esc_url( get_permalink() ) . '" class="text-gray-900 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">';
                echo esc_html( get_the_title() );
                echo '</a>';
                echo '</h4>';
                
                echo '<div class="product-price text-primary-600 dark:text-primary-400 font-bold text-sm">';
                echo wp_kses_post( $product->get_price_html() );
                echo '</div>';
                
                if ( $show_rating && $product->get_average_rating() > 0 ) {
                    echo '<div class="product-rating text-yellow-400 text-xs mt-1">';
                    echo wc_get_rating_html( $product->get_average_rating() );
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>';
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__( 'No products found.', 'aqualuxe' ) . '</p>';
        }
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Featured Products', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
        $show_rating = ! empty( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
        $product_type = ! empty( $instance['product_type'] ) ? $instance['product_type'] : 'featured';
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        
        $product_types = array(
            'featured'     => esc_html__( 'Featured Products', 'aqualuxe' ),
            'sale'         => esc_html__( 'On Sale Products', 'aqualuxe' ),
            'best_selling' => esc_html__( 'Best Selling Products', 'aqualuxe' ),
            'top_rated'    => esc_html__( 'Top Rated Products', 'aqualuxe' ),
            'newest'       => esc_html__( 'Newest Products', 'aqualuxe' ),
        );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'product_type' ) ); ?>"><?php esc_html_e( 'Product Type:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'product_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'product_type' ) ); ?>">
                <?php foreach ( $product_types as $type => $label ) : ?>
                    <option value="<?php echo esc_attr( $type ); ?>" <?php selected( $product_type, $type ); ?>><?php echo esc_html( $label ); ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of products to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'aqualuxe' ); ?></label>
            <?php
            $args = array(
                'show_option_all' => esc_html__( 'All Categories', 'aqualuxe' ),
                'name'            => $this->get_field_name( 'category' ),
                'id'              => $this->get_field_id( 'category' ),
                'selected'        => $category,
                'hierarchical'    => true,
                'class'           => 'widefat',
                'taxonomy'        => 'product_cat',
                'hide_empty'      => false,
            );
            wp_dropdown_categories( $args );
            ?>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_rating ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php esc_html_e( 'Display product rating?', 'aqualuxe' ); ?></label>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 4;
        $instance['show_rating'] = ( ! empty( $new_instance['show_rating'] ) ) ? true : false;
        $instance['product_type'] = ( ! empty( $new_instance['product_type'] ) ) ? sanitize_text_field( $new_instance['product_type'] ) : 'featured';
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? absint( $new_instance['category'] ) : 0;

        return $instance;
    }
}

/**
 * Product Categories Widget
 */
class AquaLuxe_Product_Categories_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_product_categories_widget', // Base ID
            esc_html__( 'AquaLuxe: Product Categories', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display product categories with images.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        if ( ! class_exists( 'WooCommerce' ) ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_count = ! empty( $instance['show_count'] ) ? (bool) $instance['show_count'] : true;
        $show_image = ! empty( $instance['show_image'] ) ? (bool) $instance['show_image'] : true;
        $hide_empty = ! empty( $instance['hide_empty'] ) ? (bool) $instance['hide_empty'] : true;
        $parent = ! empty( $instance['parent'] ) ? absint( $instance['parent'] ) : 0;
        
        $categories = get_terms( array(
            'taxonomy'     => 'product_cat',
            'orderby'      => 'name',
            'order'        => 'ASC',
            'hide_empty'   => $hide_empty,
            'number'       => $number,
            'parent'       => $parent,
        ) );
        
        if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
            echo '<div class="product-categories-widget">';
            
            foreach ( $categories as $category ) {
                $category_link = get_term_link( $category, 'product_cat' );
                
                if ( is_wp_error( $category_link ) ) {
                    continue;
                }
                
                echo '<div class="product-category flex items-center mb-3 pb-3 border-b border-gray-200 dark:border-gray-700 last:border-0 last:mb-0 last:pb-0">';
                
                if ( $show_image ) {
                    $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                    
                    if ( $thumbnail_id ) {
                        echo '<a href="' . esc_url( $category_link ) . '" class="category-image mr-3 block w-12 h-12 overflow-hidden rounded">';
                        echo wp_get_attachment_image( $thumbnail_id, 'thumbnail', false, array( 'class' => 'w-full h-full object-cover' ) );
                        echo '</a>';
                    } else {
                        echo '<a href="' . esc_url( $category_link ) . '" class="category-image mr-3 block w-12 h-12 overflow-hidden rounded bg-gray-200 dark:bg-gray-700 flex items-center justify-center">';
                        echo '<i class="fas fa-box text-gray-400 dark:text-gray-500"></i>';
                        echo '</a>';
                    }
                }
                
                echo '<div class="category-details flex-1">';
                echo '<h4 class="category-title text-sm font-medium">';
                echo '<a href="' . esc_url( $category_link ) . '" class="text-gray-900 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">';
                echo esc_html( $category->name );
                echo '</a>';
                echo '</h4>';
                
                if ( $show_count ) {
                    echo '<div class="category-count text-xs text-gray-600 dark:text-gray-400">';
                    echo sprintf( _n( '%s product', '%s products', $category->count, 'aqualuxe' ), number_format_i18n( $category->count ) );
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</div>';
            }
            
            echo '</div>';
        } else {
            echo '<p>' . esc_html__( 'No product categories found.', 'aqualuxe' ) . '</p>';
        }
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Product Categories', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_count = ! empty( $instance['show_count'] ) ? (bool) $instance['show_count'] : true;
        $show_image = ! empty( $instance['show_image'] ) ? (bool) $instance['show_image'] : true;
        $hide_empty = ! empty( $instance['hide_empty'] ) ? (bool) $instance['hide_empty'] : true;
        $parent = ! empty( $instance['parent'] ) ? absint( $instance['parent'] ) : 0;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of categories to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'parent' ) ); ?>"><?php esc_html_e( 'Parent Category:', 'aqualuxe' ); ?></label>
            <?php
            $args = array(
                'show_option_all' => esc_html__( 'All Categories (Top Level)', 'aqualuxe' ),
                'name'            => $this->get_field_name( 'parent' ),
                'id'              => $this->get_field_id( 'parent' ),
                'selected'        => $parent,
                'hierarchical'    => true,
                'class'           => 'widefat',
                'taxonomy'        => 'product_cat',
                'hide_empty'      => false,
            );
            wp_dropdown_categories( $args );
            ?>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_count ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_count' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_count' ) ); ?>"><?php esc_html_e( 'Display product counts?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_image ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Display category images?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $hide_empty ); ?> id="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_empty' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'hide_empty' ) ); ?>"><?php esc_html_e( 'Hide empty categories?', 'aqualuxe' ); ?></label>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 5;
        $instance['show_count'] = ( ! empty( $new_instance['show_count'] ) ) ? true : false;
        $instance['show_image'] = ( ! empty( $new_instance['show_image'] ) ) ? true : false;
        $instance['hide_empty'] = ( ! empty( $new_instance['hide_empty'] ) ) ? true : false;
        $instance['parent'] = ( ! empty( $new_instance['parent'] ) ) ? absint( $new_instance['parent'] ) : 0;

        return $instance;
    }
}

/**
 * Product Filter Widget
 */
class AquaLuxe_Product_Filter_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_product_filter_widget', // Base ID
            esc_html__( 'AquaLuxe: Product Filter', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Filter products by price, category, and attributes.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        if ( ! class_exists( 'WooCommerce' ) || ! is_shop() && ! is_product_category() && ! is_product_tag() ) {
            return;
        }
        
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $show_price_filter = ! empty( $instance['show_price_filter'] ) ? (bool) $instance['show_price_filter'] : true;
        $show_category_filter = ! empty( $instance['show_category_filter'] ) ? (bool) $instance['show_category_filter'] : true;
        $show_attribute_filter = ! empty( $instance['show_attribute_filter'] ) ? (bool) $instance['show_attribute_filter'] : true;
        $attribute = ! empty( $instance['attribute'] ) ? $instance['attribute'] : '';
        
        // Get min and max prices
        $min_price = isset( $_GET['min_price'] ) ? esc_attr( $_GET['min_price'] ) : '';
        $max_price = isset( $_GET['max_price'] ) ? esc_attr( $_GET['max_price'] ) : '';
        
        // Get current category
        $current_cat = '';
        if ( is_product_category() ) {
            $current_cat = get_queried_object_id();
        } elseif ( isset( $_GET['product_cat'] ) ) {
            $current_cat = sanitize_text_field( $_GET['product_cat'] );
        }
        
        // Get current attribute
        $current_attribute = '';
        if ( isset( $_GET[ 'filter_' . $attribute ] ) ) {
            $current_attribute = sanitize_text_field( $_GET[ 'filter_' . $attribute ] );
        }
        
        echo '<div class="product-filter-widget">';
        
        // Price filter
        if ( $show_price_filter ) {
            echo '<div class="price-filter mb-6">';
            echo '<h4 class="filter-title text-lg font-semibold mb-3">' . esc_html__( 'Filter by Price', 'aqualuxe' ) . '</h4>';
            
            // Get min and max prices from products
            $prices = aqualuxe_get_filtered_price();
            $min = floor( $prices->min_price );
            $max = ceil( $prices->max_price );
            
            echo '<div class="price-slider-wrapper">';
            echo '<div class="price-slider" data-min="' . esc_attr( $min ) . '" data-max="' . esc_attr( $max ) . '" data-current-min="' . esc_attr( $min_price ? $min_price : $min ) . '" data-current-max="' . esc_attr( $max_price ? $max_price : $max ) . '"></div>';
            echo '<div class="price-slider-amount mt-4">';
            echo '<div class="price-label mb-2">' . esc_html__( 'Price:', 'aqualuxe' ) . ' <span class="from"></span> - <span class="to"></span></div>';
            echo '<input type="hidden" id="min_price" name="min_price" value="' . esc_attr( $min_price ) . '">';
            echo '<input type="hidden" id="max_price" name="max_price" value="' . esc_attr( $max_price ) . '">';
            echo '<button type="button" class="filter-price-button bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium py-2 px-4 rounded transition-colors duration-300">' . esc_html__( 'Filter', 'aqualuxe' ) . '</button>';
            echo '</div>';
            echo '</div>';
            
            echo '</div>';
        }
        
        // Category filter
        if ( $show_category_filter ) {
            echo '<div class="category-filter mb-6">';
            echo '<h4 class="filter-title text-lg font-semibold mb-3">' . esc_html__( 'Product Categories', 'aqualuxe' ) . '</h4>';
            
            $categories = get_terms( array(
                'taxonomy'   => 'product_cat',
                'hide_empty' => true,
            ) );
            
            if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
                echo '<ul class="category-list">';
                
                foreach ( $categories as $category ) {
                    $active_class = $current_cat == $category->term_id ? ' font-semibold text-primary-600 dark:text-primary-400' : '';
                    
                    echo '<li class="category-item mb-2">';
                    echo '<a href="' . esc_url( add_query_arg( 'product_cat', $category->slug, wc_get_page_permalink( 'shop' ) ) ) . '" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300' . esc_attr( $active_class ) . '">';
                    echo esc_html( $category->name );
                    echo ' <span class="count text-gray-500 dark:text-gray-500">(' . esc_html( $category->count ) . ')</span>';
                    echo '</a>';
                    echo '</li>';
                }
                
                echo '</ul>';
            } else {
                echo '<p>' . esc_html__( 'No categories found.', 'aqualuxe' ) . '</p>';
            }
            
            echo '</div>';
        }
        
        // Attribute filter
        if ( $show_attribute_filter && ! empty( $attribute ) ) {
            $taxonomy = 'pa_' . $attribute;
            
            if ( taxonomy_exists( $taxonomy ) ) {
                echo '<div class="attribute-filter mb-6">';
                
                $attribute_label = wc_attribute_label( $taxonomy );
                echo '<h4 class="filter-title text-lg font-semibold mb-3">' . esc_html( sprintf( __( 'Filter by %s', 'aqualuxe' ), $attribute_label ) ) . '</h4>';
                
                $terms = get_terms( array(
                    'taxonomy'   => $taxonomy,
                    'hide_empty' => true,
                ) );
                
                if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                    echo '<ul class="attribute-list">';
                    
                    foreach ( $terms as $term ) {
                        $active_class = $current_attribute == $term->slug ? ' font-semibold text-primary-600 dark:text-primary-400' : '';
                        
                        echo '<li class="attribute-item mb-2">';
                        echo '<a href="' . esc_url( add_query_arg( 'filter_' . $attribute, $term->slug, wc_get_page_permalink( 'shop' ) ) ) . '" class="text-gray-700 dark:text-gray-300 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300' . esc_attr( $active_class ) . '">';
                        echo esc_html( $term->name );
                        echo ' <span class="count text-gray-500 dark:text-gray-500">(' . esc_html( $term->count ) . ')</span>';
                        echo '</a>';
                        echo '</li>';
                    }
                    
                    echo '</ul>';
                } else {
                    echo '<p>' . esc_html__( 'No attributes found.', 'aqualuxe' ) . '</p>';
                }
                
                echo '</div>';
            }
        }
        
        // Reset filter button
        echo '<div class="reset-filter">';
        echo '<a href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '" class="reset-filter-button inline-block bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 text-sm font-medium py-2 px-4 rounded transition-colors duration-300">';
        echo esc_html__( 'Reset Filters', 'aqualuxe' );
        echo '</a>';
        echo '</div>';
        
        echo '</div>';
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Filter Products', 'aqualuxe' );
        $show_price_filter = ! empty( $instance['show_price_filter'] ) ? (bool) $instance['show_price_filter'] : true;
        $show_category_filter = ! empty( $instance['show_category_filter'] ) ? (bool) $instance['show_category_filter'] : true;
        $show_attribute_filter = ! empty( $instance['show_attribute_filter'] ) ? (bool) $instance['show_attribute_filter'] : true;
        $attribute = ! empty( $instance['attribute'] ) ? $instance['attribute'] : '';
        
        // Get available attributes
        $attributes = array();
        if ( class_exists( 'WooCommerce' ) ) {
            $attribute_taxonomies = wc_get_attribute_taxonomies();
            if ( ! empty( $attribute_taxonomies ) ) {
                foreach ( $attribute_taxonomies as $tax ) {
                    $attributes[ $tax->attribute_name ] = $tax->attribute_label;
                }
            }
        }
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_price_filter ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_price_filter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_price_filter' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_price_filter' ) ); ?>"><?php esc_html_e( 'Show price filter', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_category_filter ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_category_filter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_category_filter' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_category_filter' ) ); ?>"><?php esc_html_e( 'Show category filter', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_attribute_filter ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_attribute_filter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_attribute_filter' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_attribute_filter' ) ); ?>"><?php esc_html_e( 'Show attribute filter', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'attribute' ) ); ?>"><?php esc_html_e( 'Attribute:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'attribute' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'attribute' ) ); ?>">
                <option value=""><?php esc_html_e( 'Select an attribute', 'aqualuxe' ); ?></option>
                <?php foreach ( $attributes as $name => $label ) : ?>
                    <option value="<?php echo esc_attr( $name ); ?>" <?php selected( $attribute, $name ); ?>><?php echo esc_html( $label ); ?></option>
                <?php endforeach; ?>
            </select>
            <?php if ( empty( $attributes ) ) : ?>
                <span class="description"><?php esc_html_e( 'No product attributes found.', 'aqualuxe' ); ?></span>
            <?php endif; ?>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['show_price_filter'] = ( ! empty( $new_instance['show_price_filter'] ) ) ? true : false;
        $instance['show_category_filter'] = ( ! empty( $new_instance['show_category_filter'] ) ) ? true : false;
        $instance['show_attribute_filter'] = ( ! empty( $new_instance['show_attribute_filter'] ) ) ? true : false;
        $instance['attribute'] = ( ! empty( $new_instance['attribute'] ) ) ? sanitize_text_field( $new_instance['attribute'] ) : '';

        return $instance;
    }
}

/**
 * Services Widget
 */
class AquaLuxe_Services_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_services_widget', // Base ID
            esc_html__( 'AquaLuxe: Services', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display services with icons.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $show_icon = ! empty( $instance['show_icon'] ) ? (bool) $instance['show_icon'] : true;
        
        $query_args = array(
            'post_type'      => 'service',
            'posts_per_page' => $number,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        );
        
        if ( $category ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'service_category',
                    'field'    => 'term_id',
                    'terms'    => $category,
                ),
            );
        }
        
        $services = new WP_Query( $query_args );
        
        if ( $services->have_posts() ) {
            echo '<div class="services-widget">';
            
            while ( $services->have_posts() ) {
                $services->the_post();
                
                $service_icon = get_post_meta( get_the_ID(), '_aqualuxe_service_icon', true );
                $service_price = get_post_meta( get_the_ID(), '_aqualuxe_service_price', true );
                
                echo '<div class="service-item mb-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0 last:mb-0 last:pb-0">';
                
                echo '<div class="service-header flex items-center mb-2">';
                
                if ( $show_icon && ! empty( $service_icon ) ) {
                    echo '<div class="service-icon mr-3 text-primary-600 dark:text-primary-400">';
                    echo '<i class="' . esc_attr( $service_icon ) . '"></i>';
                    echo '</div>';
                }
                
                echo '<h4 class="service-title text-base font-medium">';
                echo '<a href="' . esc_url( get_permalink() ) . '" class="text-gray-900 dark:text-gray-100 hover:text-primary-600 dark:hover:text-primary-400 transition-colors duration-300">';
                echo esc_html( get_the_title() );
                echo '</a>';
                echo '</h4>';
                
                if ( ! empty( $service_price ) ) {
                    echo '<div class="service-price ml-auto text-primary-600 dark:text-primary-400 font-semibold">';
                    echo esc_html( $service_price );
                    echo '</div>';
                }
                
                echo '</div>';
                
                echo '<div class="service-excerpt text-sm text-gray-700 dark:text-gray-300">';
                echo wp_trim_words( get_the_excerpt(), 15, '...' );
                echo '</div>';
                
                echo '</div>';
            }
            
            echo '</div>';
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__( 'No services found.', 'aqualuxe' ) . '</p>';
        }
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Our Services', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $show_icon = ! empty( $instance['show_icon'] ) ? (bool) $instance['show_icon'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of services to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'aqualuxe' ); ?></label>
            <?php
            $args = array(
                'show_option_all' => esc_html__( 'All Categories', 'aqualuxe' ),
                'name'            => $this->get_field_name( 'category' ),
                'id'              => $this->get_field_id( 'category' ),
                'selected'        => $category,
                'hierarchical'    => true,
                'class'           => 'widefat',
                'taxonomy'        => 'service_category',
                'hide_empty'      => false,
            );
            
            if ( taxonomy_exists( 'service_category' ) ) {
                wp_dropdown_categories( $args );
            } else {
                echo '<span class="description">' . esc_html__( 'Service categories not found. Please create some services first.', 'aqualuxe' ) . '</span>';
            }
            ?>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_icon ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_icon' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_icon' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_icon' ) ); ?>"><?php esc_html_e( 'Show service icon', 'aqualuxe' ); ?></label>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 3;
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? absint( $new_instance['category'] ) : 0;
        $instance['show_icon'] = ( ! empty( $new_instance['show_icon'] ) ) ? true : false;

        return $instance;
    }
}

/**
 * Testimonials Widget
 */
class AquaLuxe_Testimonials_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_testimonials_widget', // Base ID
            esc_html__( 'AquaLuxe: Testimonials', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display testimonials with slider.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $show_rating = ! empty( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
        $show_image = ! empty( $instance['show_image'] ) ? (bool) $instance['show_image'] : true;
        $slider = ! empty( $instance['slider'] ) ? (bool) $instance['slider'] : true;
        
        $query_args = array(
            'post_type'      => 'testimonial',
            'posts_per_page' => $number,
            'orderby'        => 'rand',
        );
        
        if ( $category ) {
            $query_args['tax_query'] = array(
                array(
                    'taxonomy' => 'testimonial_category',
                    'field'    => 'term_id',
                    'terms'    => $category,
                ),
            );
        }
        
        $testimonials = new WP_Query( $query_args );
        
        if ( $testimonials->have_posts() ) {
            $slider_class = $slider ? ' testimonial-slider' : '';
            
            echo '<div class="testimonials-widget' . esc_attr( $slider_class ) . '">';
            
            while ( $testimonials->have_posts() ) {
                $testimonials->the_post();
                
                $testimonial_name = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_name', true );
                $testimonial_position = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_position', true );
                $testimonial_company = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_company', true );
                $testimonial_rating = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_rating', true );
                
                echo '<div class="testimonial-item bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4">';
                
                if ( $show_rating && ! empty( $testimonial_rating ) ) {
                    echo '<div class="testimonial-rating mb-3 text-yellow-400">';
                    
                    $full_stars = floor( $testimonial_rating );
                    $half_star = ( $testimonial_rating - $full_stars ) >= 0.5;
                    
                    for ( $i = 1; $i <= 5; $i++ ) {
                        if ( $i <= $full_stars ) {
                            echo '<i class="fas fa-star"></i>';
                        } elseif ( $i === $full_stars + 1 && $half_star ) {
                            echo '<i class="fas fa-star-half-alt"></i>';
                        } else {
                            echo '<i class="far fa-star"></i>';
                        }
                    }
                    
                    echo '</div>';
                }
                
                echo '<div class="testimonial-content text-gray-700 dark:text-gray-300 mb-4">';
                echo '<i class="fas fa-quote-left text-gray-400 dark:text-gray-600 text-xl mr-2"></i>';
                echo wp_trim_words( get_the_content(), 25, '...' );
                echo '</div>';
                
                echo '<div class="testimonial-author flex items-center">';
                
                if ( $show_image && has_post_thumbnail() ) {
                    echo '<div class="testimonial-avatar mr-3">';
                    echo get_the_post_thumbnail( get_the_ID(), 'thumbnail', array( 'class' => 'w-10 h-10 rounded-full' ) );
                    echo '</div>';
                }
                
                echo '<div class="testimonial-info">';
                
                if ( ! empty( $testimonial_name ) ) {
                    echo '<div class="testimonial-name font-semibold text-gray-900 dark:text-gray-100">';
                    echo esc_html( $testimonial_name );
                    echo '</div>';
                }
                
                if ( ! empty( $testimonial_position ) || ! empty( $testimonial_company ) ) {
                    echo '<div class="testimonial-meta text-xs text-gray-600 dark:text-gray-400">';
                    
                    if ( ! empty( $testimonial_position ) ) {
                        echo esc_html( $testimonial_position );
                        
                        if ( ! empty( $testimonial_company ) ) {
                            echo ', ';
                        }
                    }
                    
                    if ( ! empty( $testimonial_company ) ) {
                        echo esc_html( $testimonial_company );
                    }
                    
                    echo '</div>';
                }
                
                echo '</div>';
                echo '</div>';
                
                echo '</div>';
            }
            
            echo '</div>';
            
            if ( $slider ) {
                ?>
                <script>
                    jQuery(document).ready(function($) {
                        $('.testimonial-slider').slick({
                            dots: true,
                            arrows: false,
                            infinite: true,
                            speed: 500,
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            autoplay: true,
                            autoplaySpeed: 5000,
                            adaptiveHeight: true
                        });
                    });
                </script>
                <?php
            }
            
            wp_reset_postdata();
        } else {
            echo '<p>' . esc_html__( 'No testimonials found.', 'aqualuxe' ) . '</p>';
        }
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Testimonials', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 3;
        $category = ! empty( $instance['category'] ) ? absint( $instance['category'] ) : 0;
        $show_rating = ! empty( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
        $show_image = ! empty( $instance['show_image'] ) ? (bool) $instance['show_image'] : true;
        $slider = ! empty( $instance['slider'] ) ? (bool) $instance['slider'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of testimonials to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'category' ) ); ?>"><?php esc_html_e( 'Category:', 'aqualuxe' ); ?></label>
            <?php
            $args = array(
                'show_option_all' => esc_html__( 'All Categories', 'aqualuxe' ),
                'name'            => $this->get_field_name( 'category' ),
                'id'              => $this->get_field_id( 'category' ),
                'selected'        => $category,
                'hierarchical'    => true,
                'class'           => 'widefat',
                'taxonomy'        => 'testimonial_category',
                'hide_empty'      => false,
            );
            
            if ( taxonomy_exists( 'testimonial_category' ) ) {
                wp_dropdown_categories( $args );
            } else {
                echo '<span class="description">' . esc_html__( 'Testimonial categories not found. Please create some testimonials first.', 'aqualuxe' ) . '</span>';
            }
            ?>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_rating ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php esc_html_e( 'Show rating', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_image ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_image' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_image' ) ); ?>"><?php esc_html_e( 'Show author image', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $slider ); ?> id="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'slider' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'slider' ) ); ?>"><?php esc_html_e( 'Enable slider', 'aqualuxe' ); ?></label>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['number'] = ( ! empty( $new_instance['number'] ) ) ? absint( $new_instance['number'] ) : 3;
        $instance['category'] = ( ! empty( $new_instance['category'] ) ) ? absint( $new_instance['category'] ) : 0;
        $instance['show_rating'] = ( ! empty( $new_instance['show_rating'] ) ) ? true : false;
        $instance['show_image'] = ( ! empty( $new_instance['show_image'] ) ) ? true : false;
        $instance['slider'] = ( ! empty( $new_instance['slider'] ) ) ? true : false;

        return $instance;
    }
}

/**
 * Newsletter Widget
 */
class AquaLuxe_Newsletter_Widget extends WP_Widget {
    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_newsletter_widget', // Base ID
            esc_html__( 'AquaLuxe: Newsletter', 'aqualuxe' ), // Name
            array( 'description' => esc_html__( 'Display newsletter subscription form.', 'aqualuxe' ) ) // Args
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        echo $args['before_widget'];
        
        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }
        
        $description = ! empty( $instance['description'] ) ? $instance['description'] : '';
        $name_field = ! empty( $instance['name_field'] ) ? (bool) $instance['name_field'] : false;
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : esc_html__( 'Subscribe', 'aqualuxe' );
        $privacy_text = ! empty( $instance['privacy_text'] ) ? $instance['privacy_text'] : '';
        
        echo '<div class="newsletter-widget">';
        
        if ( ! empty( $description ) ) {
            echo '<div class="newsletter-description text-gray-700 dark:text-gray-300 mb-4">';
            echo wp_kses_post( wpautop( $description ) );
            echo '</div>';
        }
        
        echo '<form class="newsletter-form" method="post">';
        
        if ( $name_field ) {
            echo '<div class="newsletter-name-field mb-3">';
            echo '<label for="newsletter-name-' . esc_attr( $this->id ) . '" class="sr-only">' . esc_html__( 'Your Name', 'aqualuxe' ) . '</label>';
            echo '<input type="text" id="newsletter-name-' . esc_attr( $this->id ) . '" name="newsletter_name" class="newsletter-name w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-100" placeholder="' . esc_attr__( 'Your Name', 'aqualuxe' ) . '">';
            echo '</div>';
        }
        
        echo '<div class="newsletter-email-field mb-3">';
        echo '<label for="newsletter-email-' . esc_attr( $this->id ) . '" class="sr-only">' . esc_html__( 'Your Email', 'aqualuxe' ) . '</label>';
        echo '<input type="email" id="newsletter-email-' . esc_attr( $this->id ) . '" name="newsletter_email" class="newsletter-email w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-100" placeholder="' . esc_attr__( 'Your Email', 'aqualuxe' ) . '" required>';
        echo '</div>';
        
        echo '<div class="newsletter-submit-field">';
        echo '<button type="submit" class="newsletter-submit w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded transition-colors duration-300">' . esc_html( $button_text ) . '</button>';
        echo '</div>';
        
        if ( ! empty( $privacy_text ) ) {
            echo '<div class="newsletter-privacy text-xs text-gray-600 dark:text-gray-400 mt-2">';
            echo wp_kses_post( $privacy_text );
            echo '</div>';
        }
        
        echo '<div class="newsletter-response mt-3 hidden"></div>';
        
        echo '<input type="hidden" name="action" value="aqualuxe_newsletter_subscription">';
        echo '<input type="hidden" name="widget_id" value="' . esc_attr( $this->id ) . '">';
        wp_nonce_field( 'aqualuxe-newsletter', 'newsletter_nonce' );
        
        echo '</form>';
        
        echo '</div>';
        
        // Add JavaScript for AJAX submission
        ?>
        <script>
            jQuery(document).ready(function($) {
                $('.newsletter-form').on('submit', function(e) {
                    e.preventDefault();
                    
                    var form = $(this);
                    var responseDiv = form.find('.newsletter-response');
                    var submitButton = form.find('.newsletter-submit');
                    var formData = form.serialize();
                    
                    // Disable submit button and show loading state
                    submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <?php echo esc_js( __( 'Subscribing...', 'aqualuxe' ) ); ?>');
                    
                    $.ajax({
                        url: aqualuxeData.ajaxUrl,
                        type: 'POST',
                        data: formData + '&nonce=' + aqualuxeData.nonce,
                        success: function(response) {
                            responseDiv.removeClass('hidden');
                            
                            if (response.success) {
                                responseDiv.html('<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded">' + response.data + '</div>');
                                form.find('input[type="email"], input[type="text"]').val('');
                            } else {
                                responseDiv.html('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded">' + response.data + '</div>');
                            }
                            
                            // Re-enable submit button
                            submitButton.prop('disabled', false).html('<?php echo esc_js( $button_text ); ?>');
                        },
                        error: function() {
                            responseDiv.removeClass('hidden').html('<div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded"><?php echo esc_js( __( 'An error occurred. Please try again.', 'aqualuxe' ) ); ?></div>');
                            submitButton.prop('disabled', false).html('<?php echo esc_js( $button_text ); ?>');
                        }
                    });
                });
            });
        </script>
        <?php
        
        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Newsletter', 'aqualuxe' );
        $description = ! empty( $instance['description'] ) ? $instance['description'] : esc_html__( 'Subscribe to our newsletter to receive updates on new products, special offers, and more.', 'aqualuxe' );
        $name_field = ! empty( $instance['name_field'] ) ? (bool) $instance['name_field'] : false;
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : esc_html__( 'Subscribe', 'aqualuxe' );
        $privacy_text = ! empty( $instance['privacy_text'] ) ? $instance['privacy_text'] : esc_html__( 'By subscribing, you agree to our Privacy Policy and consent to receive marketing communications.', 'aqualuxe' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>" rows="3"><?php echo esc_textarea( $description ); ?></textarea>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $name_field ); ?> id="<?php echo esc_attr( $this->get_field_id( 'name_field' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'name_field' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'name_field' ) ); ?>"><?php esc_html_e( 'Show name field', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'privacy_text' ) ); ?>"><?php esc_html_e( 'Privacy Text:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'privacy_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'privacy_text' ) ); ?>" rows="3"><?php echo esc_textarea( $privacy_text ); ?></textarea>
            <small><?php esc_html_e( 'Leave empty to hide.', 'aqualuxe' ); ?></small>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? wp_kses_post( $new_instance['description'] ) : '';
        $instance['name_field'] = ( ! empty( $new_instance['name_field'] ) ) ? true : false;
        $instance['button_text'] = ( ! empty( $new_instance['button_text'] ) ) ? sanitize_text_field( $new_instance['button_text'] ) : '';
        $instance['privacy_text'] = ( ! empty( $new_instance['privacy_text'] ) ) ? wp_kses_post( $new_instance['privacy_text'] ) : '';

        return $instance;
    }
}

/**
 * Enqueue admin scripts for widgets
 */
function aqualuxe_widgets_admin_scripts() {
    wp_enqueue_media();
    
    wp_enqueue_script(
        'aqualuxe-widgets-admin',
        AQUALUXE_URI . 'assets/js/widgets-admin.js',
        array( 'jquery' ),
        AQUALUXE_VERSION,
        true
    );
}
add_action( 'admin_enqueue_scripts', 'aqualuxe_widgets_admin_scripts' );

/**
 * Get filtered price for WooCommerce products
 */
function aqualuxe_get_filtered_price() {
    global $wpdb;
    
    $args = wc()->query->get_main_query();
    
    $tax_query  = isset( $args->tax_query->queries ) ? $args->tax_query->queries : array();
    $meta_query = isset( $args->query_vars['meta_query'] ) ? $args->query_vars['meta_query'] : array();
    
    foreach ( $meta_query + $tax_query as $key => $query ) {
        if ( ! empty( $query['price_filter'] ) || ! empty( $query['rating_filter'] ) ) {
            unset( $meta_query[ $key ] );
        }
    }
    
    $meta_query = new WP_Meta_Query( $meta_query );
    $tax_query  = new WP_Tax_Query( $tax_query );
    
    $meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
    $tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );
    
    $sql  = "SELECT min( FLOOR( price_meta.meta_value ) ) as min_price, max( CEILING( price_meta.meta_value ) ) as max_price FROM {$wpdb->posts} ";
    $sql .= " LEFT JOIN {$wpdb->postmeta} as price_meta ON {$wpdb->posts}.ID = price_meta.post_id " . $tax_query_sql['join'] . $meta_query_sql['join'];
    $sql .= " WHERE {$wpdb->posts}.post_type IN ('" . implode( "','", array_map( 'esc_sql', array( 'product' ) ) ) . "')
        AND {$wpdb->posts}.post_status = 'publish'
        AND price_meta.meta_key IN ('" . implode( "','", array_map( 'esc_sql', array( '_price' ) ) ) . "')
        AND price_meta.meta_value > '' ";
    $sql .= $tax_query_sql['where'] . $meta_query_sql['where'];
    
    $prices = $wpdb->get_row( $sql ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
    
    return $prices;
}