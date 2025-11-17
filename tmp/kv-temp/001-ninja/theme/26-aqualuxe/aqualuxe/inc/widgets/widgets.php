<?php
/**
 * Custom widgets for AquaLuxe theme
 *
 * @package AquaLuxe
 */

/**
 * Register custom widgets
 */
function aqualuxe_register_widgets() {
    register_widget( 'AquaLuxe_Recent_Posts_Widget' );
    register_widget( 'AquaLuxe_Social_Widget' );
    register_widget( 'AquaLuxe_Contact_Info_Widget' );
    register_widget( 'AquaLuxe_Featured_Products_Widget' );
    register_widget( 'AquaLuxe_Newsletter_Widget' );
}
add_action( 'widgets_init', 'aqualuxe_register_widgets' );

/**
 * Recent Posts Widget with thumbnails
 */
class AquaLuxe_Recent_Posts_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_recent_posts',
            esc_html__( 'AquaLuxe: Recent Posts', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display recent posts with thumbnails.', 'aqualuxe' ),
                'classname'   => 'aqualuxe-recent-posts',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Recent Posts', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 5;
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
        $show_excerpt = isset( $instance['show_excerpt'] ) ? (bool) $instance['show_excerpt'] : false;

        $recent_posts = new WP_Query(
            array(
                'post_type'           => 'post',
                'posts_per_page'      => $number,
                'no_found_rows'       => true,
                'post_status'         => 'publish',
                'ignore_sticky_posts' => true,
            )
        );

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        if ( $recent_posts->have_posts() ) :
            ?>
            <ul class="aqualuxe-recent-posts-list">
                <?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
                    <li class="aqualuxe-recent-post-item flex mb-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0 last:mb-0 last:pb-0">
                        <?php if ( $show_thumbnail && has_post_thumbnail() ) : ?>
                            <a href="<?php the_permalink(); ?>" class="aqualuxe-recent-post-thumbnail flex-shrink-0 mr-4" aria-hidden="true" tabindex="-1">
                                <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-16 h-16 object-cover rounded' ) ); ?>
                            </a>
                        <?php endif; ?>
                        
                        <div class="aqualuxe-recent-post-content">
                            <h4 class="aqualuxe-recent-post-title text-sm font-medium mb-1">
                                <a href="<?php the_permalink(); ?>" class="text-gray-900 dark:text-gray-100 hover:text-primary dark:hover:text-primary-light transition-colors">
                                    <?php the_title(); ?>
                                </a>
                            </h4>
                            
                            <?php if ( $show_date ) : ?>
                                <span class="aqualuxe-recent-post-date text-xs text-gray-500 dark:text-gray-400">
                                    <?php echo get_the_date(); ?>
                                </span>
                            <?php endif; ?>
                            
                            <?php if ( $show_excerpt ) : ?>
                                <div class="aqualuxe-recent-post-excerpt text-xs text-gray-600 dark:text-gray-300 mt-1">
                                    <?php echo wp_trim_words( get_the_excerpt(), 10, '...' ); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php
            wp_reset_postdata();
        else :
            ?>
            <p><?php esc_html_e( 'No posts found.', 'aqualuxe' ); ?></p>
            <?php
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
        $show_date = isset( $instance['show_date'] ) ? (bool) $instance['show_date'] : false;
        $show_thumbnail = isset( $instance['show_thumbnail'] ) ? (bool) $instance['show_thumbnail'] : true;
        $show_excerpt = isset( $instance['show_excerpt'] ) ? (bool) $instance['show_excerpt'] : false;
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
            <input class="checkbox" type="checkbox" <?php checked( $show_date ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_date' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_date' ) ); ?>"><?php esc_html_e( 'Display post date?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_thumbnail ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_thumbnail' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_thumbnail' ) ); ?>"><?php esc_html_e( 'Display post thumbnail?', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_excerpt ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_excerpt' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_excerpt' ) ); ?>"><?php esc_html_e( 'Display post excerpt?', 'aqualuxe' ); ?></label>
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
        $instance['show_date'] = isset( $new_instance['show_date'] ) ? (bool) $new_instance['show_date'] : false;
        $instance['show_thumbnail'] = isset( $new_instance['show_thumbnail'] ) ? (bool) $new_instance['show_thumbnail'] : true;
        $instance['show_excerpt'] = isset( $new_instance['show_excerpt'] ) ? (bool) $new_instance['show_excerpt'] : false;

        return $instance;
    }
}

/**
 * Social Media Widget
 */
class AquaLuxe_Social_Widget extends WP_Widget {

    /**
     * Register widget with WordPress.
     */
    public function __construct() {
        parent::__construct(
            'aqualuxe_social',
            esc_html__( 'AquaLuxe: Social Media', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display social media icons with links.', 'aqualuxe' ),
                'classname'   => 'aqualuxe-social',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Follow Us', 'aqualuxe' );
        $facebook = ! empty( $instance['facebook'] ) ? $instance['facebook'] : '';
        $twitter = ! empty( $instance['twitter'] ) ? $instance['twitter'] : '';
        $instagram = ! empty( $instance['instagram'] ) ? $instance['instagram'] : '';
        $youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : '';
        $tiktok = ! empty( $instance['tiktok'] ) ? $instance['tiktok'] : '';
        $icon_style = ! empty( $instance['icon_style'] ) ? $instance['icon_style'] : 'rounded';
        $icon_size = ! empty( $instance['icon_size'] ) ? $instance['icon_size'] : 'medium';

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        // Set icon classes based on style and size
        $icon_classes = 'inline-flex items-center justify-center transition-colors';
        
        if ( $icon_style === 'rounded' ) {
            $icon_classes .= ' rounded-full';
        } elseif ( $icon_style === 'square' ) {
            $icon_classes .= ' rounded';
        }
        
        if ( $icon_size === 'small' ) {
            $icon_classes .= ' w-8 h-8';
        } elseif ( $icon_size === 'medium' ) {
            $icon_classes .= ' w-10 h-10';
        } elseif ( $icon_size === 'large' ) {
            $icon_classes .= ' w-12 h-12';
        }

        ?>
        <div class="aqualuxe-social-icons flex flex-wrap gap-2">
            <?php if ( $facebook ) : ?>
                <a href="<?php echo esc_url( $facebook ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $icon_classes ); ?> bg-blue-600 hover:bg-blue-700 text-white" aria-label="<?php esc_attr_e( 'Facebook', 'aqualuxe' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"/></svg>
                </a>
            <?php endif; ?>
            
            <?php if ( $twitter ) : ?>
                <a href="<?php echo esc_url( $twitter ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $icon_classes ); ?> bg-blue-400 hover:bg-blue-500 text-white" aria-label="<?php esc_attr_e( 'Twitter', 'aqualuxe' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>
                </a>
            <?php endif; ?>
            
            <?php if ( $instagram ) : ?>
                <a href="<?php echo esc_url( $instagram ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $icon_classes ); ?> bg-gradient-to-r from-purple-500 via-pink-500 to-yellow-500 hover:from-purple-600 hover:via-pink-600 hover:to-yellow-600 text-white" aria-label="<?php esc_attr_e( 'Instagram', 'aqualuxe' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
            <?php endif; ?>
            
            <?php if ( $youtube ) : ?>
                <a href="<?php echo esc_url( $youtube ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $icon_classes ); ?> bg-red-600 hover:bg-red-700 text-white" aria-label="<?php esc_attr_e( 'YouTube', 'aqualuxe' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.495 6.205a3.007 3.007 0 00-2.088-2.088c-1.87-.501-9.396-.501-9.396-.501s-7.507-.01-9.396.501A3.007 3.007 0 00.527 6.205a31.247 31.247 0 00-.522 5.805 31.247 31.247 0 00.522 5.783 3.007 3.007 0 002.088 2.088c1.868.502 9.396.502 9.396.502s7.506 0 9.396-.502a3.007 3.007 0 002.088-2.088 31.247 31.247 0 00.5-5.783 31.247 31.247 0 00-.5-5.805zM9.609 15.601V8.408l6.264 3.602z"/></svg>
                </a>
            <?php endif; ?>
            
            <?php if ( $linkedin ) : ?>
                <a href="<?php echo esc_url( $linkedin ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $icon_classes ); ?> bg-blue-700 hover:bg-blue-800 text-white" aria-label="<?php esc_attr_e( 'LinkedIn', 'aqualuxe' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
            <?php endif; ?>
            
            <?php if ( $pinterest ) : ?>
                <a href="<?php echo esc_url( $pinterest ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $icon_classes ); ?> bg-red-500 hover:bg-red-600 text-white" aria-label="<?php esc_attr_e( 'Pinterest', 'aqualuxe' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.162-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.401.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.354-.629-2.758-1.379l-.749 2.848c-.269 1.045-1.004 2.352-1.498 3.146 1.123.345 2.306.535 3.55.535 6.607 0 11.985-5.365 11.985-11.987C23.97 5.39 18.592.026 11.985.026L12.017 0z"/></svg>
                </a>
            <?php endif; ?>
            
            <?php if ( $tiktok ) : ?>
                <a href="<?php echo esc_url( $tiktok ); ?>" target="_blank" rel="noopener noreferrer" class="<?php echo esc_attr( $icon_classes ); ?> bg-black hover:bg-gray-800 text-white" aria-label="<?php esc_attr_e( 'TikTok', 'aqualuxe' ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/></svg>
                </a>
            <?php endif; ?>
        </div>
        <?php

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
        $youtube = ! empty( $instance['youtube'] ) ? $instance['youtube'] : '';
        $linkedin = ! empty( $instance['linkedin'] ) ? $instance['linkedin'] : '';
        $pinterest = ! empty( $instance['pinterest'] ) ? $instance['pinterest'] : '';
        $tiktok = ! empty( $instance['tiktok'] ) ? $instance['tiktok'] : '';
        $icon_style = ! empty( $instance['icon_style'] ) ? $instance['icon_style'] : 'rounded';
        $icon_size = ! empty( $instance['icon_size'] ) ? $instance['icon_size'] : 'medium';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>"><?php esc_html_e( 'Facebook URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'facebook' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'facebook' ) ); ?>" type="url" value="<?php echo esc_url( $facebook ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>"><?php esc_html_e( 'Twitter URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'twitter' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'twitter' ) ); ?>" type="url" value="<?php echo esc_url( $twitter ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>"><?php esc_html_e( 'Instagram URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'instagram' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'instagram' ) ); ?>" type="url" value="<?php echo esc_url( $instagram ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>"><?php esc_html_e( 'YouTube URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'youtube' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'youtube' ) ); ?>" type="url" value="<?php echo esc_url( $youtube ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>"><?php esc_html_e( 'LinkedIn URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'linkedin' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'linkedin' ) ); ?>" type="url" value="<?php echo esc_url( $linkedin ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>"><?php esc_html_e( 'Pinterest URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'pinterest' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'pinterest' ) ); ?>" type="url" value="<?php echo esc_url( $pinterest ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'tiktok' ) ); ?>"><?php esc_html_e( 'TikTok URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'tiktok' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'tiktok' ) ); ?>" type="url" value="<?php echo esc_url( $tiktok ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'icon_style' ) ); ?>"><?php esc_html_e( 'Icon Style:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'icon_style' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_style' ) ); ?>">
                <option value="rounded" <?php selected( $icon_style, 'rounded' ); ?>><?php esc_html_e( 'Rounded', 'aqualuxe' ); ?></option>
                <option value="square" <?php selected( $icon_style, 'square' ); ?>><?php esc_html_e( 'Square', 'aqualuxe' ); ?></option>
                <option value="none" <?php selected( $icon_style, 'none' ); ?>><?php esc_html_e( 'None', 'aqualuxe' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'icon_size' ) ); ?>"><?php esc_html_e( 'Icon Size:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'icon_size' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'icon_size' ) ); ?>">
                <option value="small" <?php selected( $icon_size, 'small' ); ?>><?php esc_html_e( 'Small', 'aqualuxe' ); ?></option>
                <option value="medium" <?php selected( $icon_size, 'medium' ); ?>><?php esc_html_e( 'Medium', 'aqualuxe' ); ?></option>
                <option value="large" <?php selected( $icon_size, 'large' ); ?>><?php esc_html_e( 'Large', 'aqualuxe' ); ?></option>
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
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
        $instance['facebook'] = ( ! empty( $new_instance['facebook'] ) ) ? esc_url_raw( $new_instance['facebook'] ) : '';
        $instance['twitter'] = ( ! empty( $new_instance['twitter'] ) ) ? esc_url_raw( $new_instance['twitter'] ) : '';
        $instance['instagram'] = ( ! empty( $new_instance['instagram'] ) ) ? esc_url_raw( $new_instance['instagram'] ) : '';
        $instance['youtube'] = ( ! empty( $new_instance['youtube'] ) ) ? esc_url_raw( $new_instance['youtube'] ) : '';
        $instance['linkedin'] = ( ! empty( $new_instance['linkedin'] ) ) ? esc_url_raw( $new_instance['linkedin'] ) : '';
        $instance['pinterest'] = ( ! empty( $new_instance['pinterest'] ) ) ? esc_url_raw( $new_instance['pinterest'] ) : '';
        $instance['tiktok'] = ( ! empty( $new_instance['tiktok'] ) ) ? esc_url_raw( $new_instance['tiktok'] ) : '';
        $instance['icon_style'] = ( ! empty( $new_instance['icon_style'] ) ) ? sanitize_text_field( $new_instance['icon_style'] ) : 'rounded';
        $instance['icon_size'] = ( ! empty( $new_instance['icon_size'] ) ) ? sanitize_text_field( $new_instance['icon_size'] ) : 'medium';

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
            'aqualuxe_contact_info',
            esc_html__( 'AquaLuxe: Contact Info', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display contact information.', 'aqualuxe' ),
                'classname'   => 'aqualuxe-contact-info',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Contact Us', 'aqualuxe' );
        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
        $email = ! empty( $instance['email'] ) ? $instance['email'] : '';
        $address = ! empty( $instance['address'] ) ? $instance['address'] : '';
        $hours = ! empty( $instance['hours'] ) ? $instance['hours'] : '';
        $show_icons = isset( $instance['show_icons'] ) ? (bool) $instance['show_icons'] : true;

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        ?>
        <div class="aqualuxe-contact-info-content">
            <?php if ( $phone ) : ?>
                <div class="aqualuxe-contact-item phone flex items-start mb-3">
                    <?php if ( $show_icons ) : ?>
                        <div class="aqualuxe-contact-icon mr-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="aqualuxe-contact-text">
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></span>
                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone ) ); ?>" class="text-gray-900 dark:text-gray-100 hover:text-primary dark:hover:text-primary-light transition-colors">
                            <?php echo esc_html( $phone ); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ( $email ) : ?>
                <div class="aqualuxe-contact-item email flex items-start mb-3">
                    <?php if ( $show_icons ) : ?>
                        <div class="aqualuxe-contact-icon mr-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="aqualuxe-contact-text">
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></span>
                        <a href="mailto:<?php echo esc_attr( $email ); ?>" class="text-gray-900 dark:text-gray-100 hover:text-primary dark:hover:text-primary-light transition-colors">
                            <?php echo esc_html( $email ); ?>
                        </a>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ( $address ) : ?>
                <div class="aqualuxe-contact-item address flex items-start mb-3">
                    <?php if ( $show_icons ) : ?>
                        <div class="aqualuxe-contact-icon mr-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="aqualuxe-contact-text">
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Address:', 'aqualuxe' ); ?></span>
                        <address class="not-italic text-gray-900 dark:text-gray-100">
                            <?php echo wp_kses_post( nl2br( $address ) ); ?>
                        </address>
                    </div>
                </div>
            <?php endif; ?>
            
            <?php if ( $hours ) : ?>
                <div class="aqualuxe-contact-item hours flex items-start">
                    <?php if ( $show_icons ) : ?>
                        <div class="aqualuxe-contact-icon mr-3 text-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    <?php endif; ?>
                    <div class="aqualuxe-contact-text">
                        <span class="block text-sm font-medium text-gray-500 dark:text-gray-400"><?php esc_html_e( 'Business Hours:', 'aqualuxe' ); ?></span>
                        <div class="text-gray-900 dark:text-gray-100">
                            <?php echo wp_kses_post( nl2br( $hours ) ); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Contact Us', 'aqualuxe' );
        $phone = ! empty( $instance['phone'] ) ? $instance['phone'] : '';
        $email = ! empty( $instance['email'] ) ? $instance['email'] : '';
        $address = ! empty( $instance['address'] ) ? $instance['address'] : '';
        $hours = ! empty( $instance['hours'] ) ? $instance['hours'] : '';
        $show_icons = isset( $instance['show_icons'] ) ? (bool) $instance['show_icons'] : true;
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>"><?php esc_html_e( 'Phone:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'phone' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'phone' ) ); ?>" type="text" value="<?php echo esc_attr( $phone ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>"><?php esc_html_e( 'Email:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'email' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'email' ) ); ?>" type="email" value="<?php echo esc_attr( $email ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>"><?php esc_html_e( 'Address:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'address' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'address' ) ); ?>"><?php echo esc_textarea( $address ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'hours' ) ); ?>"><?php esc_html_e( 'Business Hours:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" rows="4" id="<?php echo esc_attr( $this->get_field_id( 'hours' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hours' ) ); ?>"><?php echo esc_textarea( $hours ); ?></textarea>
            <small><?php esc_html_e( 'Enter each line separately. Example: Monday - Friday: 9:00 AM - 6:00 PM', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_icons ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_icons' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_icons' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_icons' ) ); ?>"><?php esc_html_e( 'Show icons', 'aqualuxe' ); ?></label>
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
        $instance['phone'] = ( ! empty( $new_instance['phone'] ) ) ? sanitize_text_field( $new_instance['phone'] ) : '';
        $instance['email'] = ( ! empty( $new_instance['email'] ) ) ? sanitize_email( $new_instance['email'] ) : '';
        $instance['address'] = ( ! empty( $new_instance['address'] ) ) ? sanitize_textarea_field( $new_instance['address'] ) : '';
        $instance['hours'] = ( ! empty( $new_instance['hours'] ) ) ? sanitize_textarea_field( $new_instance['hours'] ) : '';
        $instance['show_icons'] = isset( $new_instance['show_icons'] ) ? (bool) $new_instance['show_icons'] : true;

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
            'aqualuxe_featured_products',
            esc_html__( 'AquaLuxe: Featured Products', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display featured products.', 'aqualuxe' ),
                'classname'   => 'aqualuxe-featured-products',
            )
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

        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Featured Products', 'aqualuxe' );
        $number = ! empty( $instance['number'] ) ? absint( $instance['number'] ) : 4;
        $show_rating = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
        $show_price = isset( $instance['show_price'] ) ? (bool) $instance['show_price'] : true;
        $product_type = ! empty( $instance['product_type'] ) ? $instance['product_type'] : 'featured';

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

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
                $query_args['post__in'] = array_merge( array( 0 ), $product_ids_on_sale );
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

        $products = new WP_Query( $query_args );

        if ( $products->have_posts() ) :
            ?>
            <ul class="aqualuxe-featured-products-list grid grid-cols-2 gap-4">
                <?php while ( $products->have_posts() ) : $products->the_post(); ?>
                    <?php
                    $product = wc_get_product( get_the_ID() );
                    if ( ! $product ) {
                        continue;
                    }
                    ?>
                    <li class="aqualuxe-featured-product-item">
                        <a href="<?php the_permalink(); ?>" class="block group">
                            <div class="aqualuxe-featured-product-image relative mb-2 overflow-hidden rounded-lg">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <?php the_post_thumbnail( 'woocommerce_thumbnail', array( 'class' => 'w-full h-auto transition-transform duration-300 group-hover:scale-105' ) ); ?>
                                <?php else : ?>
                                    <img src="<?php echo esc_url( wc_placeholder_img_src() ); ?>" alt="<?php esc_attr_e( 'Placeholder', 'aqualuxe' ); ?>" class="w-full h-auto">
                                <?php endif; ?>
                                
                                <?php if ( $product->is_on_sale() ) : ?>
                                    <span class="absolute top-2 left-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded">
                                        <?php esc_html_e( 'Sale', 'aqualuxe' ); ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                            
                            <h4 class="aqualuxe-featured-product-title text-sm font-medium text-gray-900 dark:text-gray-100 group-hover:text-primary dark:group-hover:text-primary-light transition-colors">
                                <?php the_title(); ?>
                            </h4>
                            
                            <?php if ( $show_price ) : ?>
                                <div class="aqualuxe-featured-product-price text-sm font-medium mt-1">
                                    <?php echo wp_kses_post( $product->get_price_html() ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $show_rating && $product->get_rating_count() > 0 ) : ?>
                                <div class="aqualuxe-featured-product-rating flex items-center mt-1">
                                    <?php
                                    $rating = $product->get_average_rating();
                                    $rating_html = '';
                                    
                                    for ( $i = 1; $i <= 5; $i++ ) {
                                        if ( $i <= $rating ) {
                                            // Full star
                                            $rating_html .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>';
                                        } elseif ( $i - 0.5 <= $rating ) {
                                            // Half star
                                            $rating_html .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>';
                                        } else {
                                            // Empty star
                                            $rating_html .= '<svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>';
                                        }
                                    }
                                    
                                    echo $rating_html;
                                    ?>
                                    <span class="text-xs text-gray-500 ml-1">(<?php echo esc_html( $product->get_rating_count() ); ?>)</span>
                                </div>
                            <?php endif; ?>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
            <?php
            wp_reset_postdata();
        else :
            ?>
            <p><?php esc_html_e( 'No products found.', 'aqualuxe' ); ?></p>
            <?php
        endif;

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
        $show_rating = isset( $instance['show_rating'] ) ? (bool) $instance['show_rating'] : true;
        $show_price = isset( $instance['show_price'] ) ? (bool) $instance['show_price'] : true;
        $product_type = ! empty( $instance['product_type'] ) ? $instance['product_type'] : 'featured';
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'product_type' ) ); ?>"><?php esc_html_e( 'Product Type:', 'aqualuxe' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'product_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'product_type' ) ); ?>">
                <option value="featured" <?php selected( $product_type, 'featured' ); ?>><?php esc_html_e( 'Featured Products', 'aqualuxe' ); ?></option>
                <option value="sale" <?php selected( $product_type, 'sale' ); ?>><?php esc_html_e( 'On Sale Products', 'aqualuxe' ); ?></option>
                <option value="best_selling" <?php selected( $product_type, 'best_selling' ); ?>><?php esc_html_e( 'Best Selling Products', 'aqualuxe' ); ?></option>
                <option value="top_rated" <?php selected( $product_type, 'top_rated' ); ?>><?php esc_html_e( 'Top Rated Products', 'aqualuxe' ); ?></option>
                <option value="newest" <?php selected( $product_type, 'newest' ); ?>><?php esc_html_e( 'Newest Products', 'aqualuxe' ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of products to show:', 'aqualuxe' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" max="12" value="<?php echo esc_attr( $number ); ?>" size="3">
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_rating ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_rating' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_rating' ) ); ?>"><?php esc_html_e( 'Display product rating', 'aqualuxe' ); ?></label>
        </p>
        <p>
            <input class="checkbox" type="checkbox" <?php checked( $show_price ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_price' ) ); ?>">
            <label for="<?php echo esc_attr( $this->get_field_id( 'show_price' ) ); ?>"><?php esc_html_e( 'Display product price', 'aqualuxe' ); ?></label>
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
        $instance['show_rating'] = isset( $new_instance['show_rating'] ) ? (bool) $new_instance['show_rating'] : true;
        $instance['show_price'] = isset( $new_instance['show_price'] ) ? (bool) $new_instance['show_price'] : true;
        $instance['product_type'] = ( ! empty( $new_instance['product_type'] ) ) ? sanitize_text_field( $new_instance['product_type'] ) : 'featured';

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
            'aqualuxe_newsletter',
            esc_html__( 'AquaLuxe: Newsletter', 'aqualuxe' ),
            array(
                'description' => esc_html__( 'Display a newsletter subscription form.', 'aqualuxe' ),
                'classname'   => 'aqualuxe-newsletter',
            )
        );
    }

    /**
     * Front-end display of widget.
     *
     * @param array $args     Widget arguments.
     * @param array $instance Saved values from database.
     */
    public function widget( $args, $instance ) {
        $title = ! empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : esc_html__( 'Subscribe to Our Newsletter', 'aqualuxe' );
        $description = ! empty( $instance['description'] ) ? $instance['description'] : esc_html__( 'Stay updated with our latest news and special offers.', 'aqualuxe' );
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : esc_html__( 'Subscribe', 'aqualuxe' );
        $form_action = ! empty( $instance['form_action'] ) ? $instance['form_action'] : '#';
        $privacy_text = ! empty( $instance['privacy_text'] ) ? $instance['privacy_text'] : esc_html__( 'By subscribing, you agree to our Privacy Policy.', 'aqualuxe' );

        echo $args['before_widget'];

        if ( $title ) {
            echo $args['before_title'] . $title . $args['after_title'];
        }

        ?>
        <div class="aqualuxe-newsletter-content">
            <?php if ( $description ) : ?>
                <p class="aqualuxe-newsletter-description mb-4 text-gray-600 dark:text-gray-300">
                    <?php echo esc_html( $description ); ?>
                </p>
            <?php endif; ?>
            
            <form action="<?php echo esc_url( $form_action ); ?>" method="post" class="aqualuxe-newsletter-form">
                <div class="flex flex-col sm:flex-row gap-2">
                    <input type="email" name="email" placeholder="<?php esc_attr_e( 'Your email address', 'aqualuxe' ); ?>" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent dark:bg-gray-800 dark:text-gray-100">
                    <button type="submit" class="px-4 py-2 bg-primary hover:bg-primary-dark text-white font-medium rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2">
                        <?php echo esc_html( $button_text ); ?>
                    </button>
                </div>
                
                <?php if ( $privacy_text ) : ?>
                    <p class="aqualuxe-newsletter-privacy-text mt-2 text-xs text-gray-500 dark:text-gray-400">
                        <?php echo wp_kses_post( $privacy_text ); ?>
                    </p>
                <?php endif; ?>
            </form>
        </div>
        <?php

        echo $args['after_widget'];
    }

    /**
     * Back-end widget form.
     *
     * @param array $instance Previously saved values from database.
     */
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Subscribe to Our Newsletter', 'aqualuxe' );
        $description = ! empty( $instance['description'] ) ? $instance['description'] : esc_html__( 'Stay updated with our latest news and special offers.', 'aqualuxe' );
        $button_text = ! empty( $instance['button_text'] ) ? $instance['button_text'] : esc_html__( 'Subscribe', 'aqualuxe' );
        $form_action = ! empty( $instance['form_action'] ) ? $instance['form_action'] : '#';
        $privacy_text = ! empty( $instance['privacy_text'] ) ? $instance['privacy_text'] : esc_html__( 'By subscribing, you agree to our Privacy Policy.', 'aqualuxe' );
        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>"><?php esc_html_e( 'Description:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" rows="3" id="<?php echo esc_attr( $this->get_field_id( 'description' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'description' ) ); ?>"><?php echo esc_textarea( $description ); ?></textarea>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>"><?php esc_html_e( 'Button Text:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'button_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'button_text' ) ); ?>" type="text" value="<?php echo esc_attr( $button_text ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'form_action' ) ); ?>"><?php esc_html_e( 'Form Action URL:', 'aqualuxe' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'form_action' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'form_action' ) ); ?>" type="url" value="<?php echo esc_url( $form_action ); ?>">
            <small><?php esc_html_e( 'Enter the URL where the form data will be submitted. Use # for demo purposes.', 'aqualuxe' ); ?></small>
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'privacy_text' ) ); ?>"><?php esc_html_e( 'Privacy Text:', 'aqualuxe' ); ?></label>
            <textarea class="widefat" rows="2" id="<?php echo esc_attr( $this->get_field_id( 'privacy_text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'privacy_text' ) ); ?>"><?php echo esc_textarea( $privacy_text ); ?></textarea>
            <small><?php esc_html_e( 'Text displayed below the form. Leave empty to hide.', 'aqualuxe' ); ?></small>
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
        $instance['description'] = ( ! empty( $new_instance['description'] ) ) ? sanitize_textarea_field( $new_instance['description'] ) : '';
        $instance['button_text'] = ( ! empty( $new_instance['button_text'] ) ) ? sanitize_text_field( $new_instance['button_text'] ) : '';
        $instance['form_action'] = ( ! empty( $new_instance['form_action'] ) ) ? esc_url_raw( $new_instance['form_action'] ) : '#';
        $instance['privacy_text'] = ( ! empty( $new_instance['privacy_text'] ) ) ? wp_kses_post( $new_instance['privacy_text'] ) : '';

        return $instance;
    }
}