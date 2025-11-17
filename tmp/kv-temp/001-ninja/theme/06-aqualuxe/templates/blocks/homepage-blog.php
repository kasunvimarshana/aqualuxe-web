<?php
/**
 * Homepage Blog Block
 *
 * @package AquaLuxe
 */

// Get args from template part
$args = $args ?? array();

// Default values
$defaults = array(
    'blog_title' => __( 'Latest News', 'aqualuxe' ),
    'blog_subtitle' => __( 'Stay updated with our latest articles and tips', 'aqualuxe' ),
    'blog_count' => 3,
    'blog_category' => 0,
    'blog_button_text' => __( 'View All Posts', 'aqualuxe' ),
    'blog_button_url' => home_url( '/blog/' ),
);

// Merge defaults with args
$args = wp_parse_args( $args, $defaults );

// Extract variables
$title = $args['blog_title'];
$subtitle = $args['blog_subtitle'];
$count = $args['blog_count'];
$category = $args['blog_category'];
$button_text = $args['blog_button_text'];
$button_url = $args['blog_button_url'];

// Get posts
// In a real implementation, this would use WP_Query to get actual posts
// For demonstration, we'll use placeholder data
$posts = array(
    array(
        'id' => 1,
        'title' => __( 'How to Set Up Your First Aquarium', 'aqualuxe' ),
        'excerpt' => __( 'Setting up your first aquarium can be an exciting but challenging task. In this guide, we\'ll walk you through the essential steps to ensure success.', 'aqualuxe' ),
        'image' => get_template_directory_uri() . '/assets/images/blog/aquarium-setup.jpg',
        'url' => home_url( '/blog/how-to-set-up-your-first-aquarium/' ),
        'date' => '2025-07-15',
        'author' => 'John Smith',
        'comments' => 12,
    ),
    array(
        'id' => 2,
        'title' => __( 'Top 10 Beginner-Friendly Fish Species', 'aqualuxe' ),
        'excerpt' => __( 'New to fish keeping? Discover our top 10 recommended fish species that are perfect for beginners due to their hardiness and ease of care.', 'aqualuxe' ),
        'image' => get_template_directory_uri() . '/assets/images/blog/beginner-fish.jpg',
        'url' => home_url( '/blog/top-10-beginner-friendly-fish-species/' ),
        'date' => '2025-07-10',
        'author' => 'Jane Doe',
        'comments' => 8,
    ),
    array(
        'id' => 3,
        'title' => __( 'Essential Water Parameters for Healthy Fish', 'aqualuxe' ),
        'excerpt' => __( 'Understanding and maintaining proper water parameters is crucial for the health of your aquatic pets. Learn about pH, ammonia, nitrites, and more.', 'aqualuxe' ),
        'image' => get_template_directory_uri() . '/assets/images/blog/water-parameters.jpg',
        'url' => home_url( '/blog/essential-water-parameters-for-healthy-fish/' ),
        'date' => '2025-07-05',
        'author' => 'Mike Johnson',
        'comments' => 5,
    ),
    array(
        'id' => 4,
        'title' => __( 'Aquascaping Tips for Stunning Aquariums', 'aqualuxe' ),
        'excerpt' => __( 'Transform your aquarium into a beautiful underwater landscape with these professional aquascaping tips and techniques.', 'aqualuxe' ),
        'image' => get_template_directory_uri() . '/assets/images/blog/aquascaping.jpg',
        'url' => home_url( '/blog/aquascaping-tips-for-stunning-aquariums/' ),
        'date' => '2025-06-28',
        'author' => 'Sarah Williams',
        'comments' => 15,
    ),
    array(
        'id' => 5,
        'title' => __( 'Common Fish Diseases and How to Treat Them', 'aqualuxe' ),
        'excerpt' => __( 'Learn to identify and treat common fish diseases to keep your aquatic pets healthy and thriving in your home aquarium.', 'aqualuxe' ),
        'image' => get_template_directory_uri() . '/assets/images/blog/fish-diseases.jpg',
        'url' => home_url( '/blog/common-fish-diseases-and-how-to-treat-them/' ),
        'date' => '2025-06-20',
        'author' => 'Robert Brown',
        'comments' => 10,
    ),
);

// Filter by category if specified
if ( $category > 0 ) {
    // In a real implementation, this would filter by actual category
    // For demonstration, we'll just take the first few posts
    $posts = array_slice( $posts, 0, 2 );
}

// Limit posts to the specified count
$posts = array_slice( $posts, 0, $count );

// Format dates
foreach ( $posts as $key => $post ) {
    $posts[$key]['formatted_date'] = date_i18n( get_option( 'date_format' ), strtotime( $post['date'] ) );
}
?>

<section class="aqualuxe-blog">
    <div class="aqualuxe-container">
        <div class="aqualuxe-section-header">
            <?php if ( ! empty( $title ) ) : ?>
                <h2 class="aqualuxe-section-title"><?php echo esc_html( $title ); ?></h2>
            <?php endif; ?>
            
            <?php if ( ! empty( $subtitle ) ) : ?>
                <p class="aqualuxe-section-subtitle"><?php echo esc_html( $subtitle ); ?></p>
            <?php endif; ?>
        </div>
        
        <div class="aqualuxe-posts-grid">
            <?php foreach ( $posts as $post ) : ?>
                <article class="aqualuxe-post">
                    <div class="aqualuxe-post-image">
                        <a href="<?php echo esc_url( $post['url'] ); ?>">
                            <img src="<?php echo esc_url( $post['image'] ); ?>" alt="<?php echo esc_attr( $post['title'] ); ?>" />
                        </a>
                    </div>
                    <div class="aqualuxe-post-content">
                        <div class="aqualuxe-post-meta">
                            <span class="aqualuxe-post-date"><?php echo esc_html( $post['formatted_date'] ); ?></span>
                            <span class="aqualuxe-post-author"><?php echo esc_html( $post['author'] ); ?></span>
                            <span class="aqualuxe-post-comments"><?php 
                                /* translators: %d: number of comments */
                                printf( _n( '%d Comment', '%d Comments', $post['comments'], 'aqualuxe' ), $post['comments'] ); 
                            ?></span>
                        </div>
                        <h3 class="aqualuxe-post-title">
                            <a href="<?php echo esc_url( $post['url'] ); ?>"><?php echo esc_html( $post['title'] ); ?></a>
                        </h3>
                        <div class="aqualuxe-post-excerpt">
                            <?php echo esc_html( $post['excerpt'] ); ?>
                        </div>
                        <div class="aqualuxe-post-actions">
                            <a href="<?php echo esc_url( $post['url'] ); ?>" class="aqualuxe-button aqualuxe-button-small aqualuxe-button-outline"><?php esc_html_e( 'Read More', 'aqualuxe' ); ?></a>
                        </div>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
        
        <?php if ( ! empty( $button_text ) && ! empty( $button_url ) ) : ?>
            <div class="aqualuxe-section-footer">
                <a href="<?php echo esc_url( $button_url ); ?>" class="aqualuxe-button aqualuxe-button-secondary"><?php echo esc_html( $button_text ); ?></a>
            </div>
        <?php endif; ?>
    </div>
</section>