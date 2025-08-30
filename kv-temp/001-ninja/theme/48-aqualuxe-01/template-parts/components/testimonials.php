<?php
/**
 * Template part for displaying testimonials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/**
 * Display testimonials
 *
 * @param array $args {
 *     Optional. Arguments to customize the testimonials section.
 *
 *     @type string $title       Section title.
 *     @type string $subtitle    Section subtitle.
 *     @type string $description Section description.
 *     @type string $style       Style of the testimonials (slider, grid, masonry).
 *     @type int    $number      Number of testimonials to display.
 *     @type string $orderby     Order by parameter (date, title, rand).
 *     @type string $order       Order parameter (ASC or DESC).
 *     @type string $category    Specific testimonial category slug.
 *     @type string $background  Background style (light, dark, primary, image).
 *     @type string $image_url   Background image URL if background is set to 'image'.
 * }
 */

$defaults = array(
    'title'       => esc_html__( 'What Our Customers Say', 'aqualuxe' ),
    'subtitle'    => esc_html__( 'Testimonials', 'aqualuxe' ),
    'description' => '',
    'style'       => 'slider',
    'number'      => 6,
    'orderby'     => 'date',
    'order'       => 'DESC',
    'category'    => '',
    'background'  => 'light',
    'image_url'   => '',
);

$args = wp_parse_args( $args, $defaults );

// Set background classes
$bg_classes = '';
$overlay_style = '';

switch ( $args['background'] ) {
    case 'dark':
        $bg_classes = 'bg-dark-light text-white';
        break;
    case 'primary':
        $bg_classes = 'bg-primary text-white';
        break;
    case 'image':
        $bg_classes = 'text-white relative bg-cover bg-center';
        $bg_style = 'background-image: url(' . esc_url( $args['image_url'] ) . ');';
        $overlay_style = 'background-color: rgba(0, 0, 0, 0.6);';
        break;
    default:
        $bg_classes = 'bg-light-dark dark:bg-dark-light';
}

// Set up query arguments for testimonials
$query_args = array(
    'post_type'      => 'aqualuxe_testimonial',
    'posts_per_page' => $args['number'],
    'orderby'        => $args['orderby'],
    'order'          => $args['order'],
);

// Add category filter if specified
if ( ! empty( $args['category'] ) ) {
    $query_args['tax_query'] = array(
        array(
            'taxonomy' => 'aqualuxe_testimonial_category',
            'field'    => 'slug',
            'terms'    => $args['category'],
        ),
    );
}

// Run the query
$testimonials = new WP_Query( $query_args );

// If no testimonials post type exists, use an array of sample testimonials
if ( ! post_type_exists( 'aqualuxe_testimonial' ) || ! $testimonials->have_posts() ) {
    $sample_testimonials = array(
        array(
            'name'      => 'John Smith',
            'position'  => 'Aquarium Enthusiast',
            'content'   => 'AquaLuxe has transformed my aquarium experience. The quality of their products is unmatched, and their customer service is exceptional. I highly recommend them to anyone passionate about aquatic life.',
            'rating'    => 5,
            'image_url' => '',
        ),
        array(
            'name'      => 'Emily Johnson',
            'position'  => 'Marine Biologist',
            'content'   => 'As a professional in the field, I\'m extremely impressed with AquaLuxe\'s commitment to sustainability and quality. Their products are designed with both the aquatic life and the environment in mind.',
            'rating'    => 5,
            'image_url' => '',
        ),
        array(
            'name'      => 'Michael Chen',
            'position'  => 'Aquarium Designer',
            'content'   => 'I\'ve been using AquaLuxe products for all my client installations. The reliability and aesthetic appeal of their equipment make them my go-to choice for luxury aquarium setups.',
            'rating'    => 4,
            'image_url' => '',
        ),
    );
}
?>

<section class="testimonials-section py-12 md:py-16 <?php echo esc_attr( $bg_classes ); ?>" <?php echo isset( $bg_style ) ? 'style="' . esc_attr( $bg_style ) . '"' : ''; ?>>
    <?php if ( 'image' === $args['background'] ) : ?>
        <div class="absolute inset-0" style="<?php echo esc_attr( $overlay_style ); ?>"></div>
    <?php endif; ?>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="section-header text-center mb-8 md:mb-12">
            <?php if ( $args['subtitle'] ) : ?>
                <div class="section-subtitle mb-2">
                    <span class="inline-block px-4 py-1 bg-primary text-white text-sm font-medium rounded-full">
                        <?php echo esc_html( $args['subtitle'] ); ?>
                    </span>
                </div>
            <?php endif; ?>
            
            <?php if ( $args['title'] ) : ?>
                <h2 class="section-title text-2xl md:text-3xl lg:text-4xl font-serif font-bold mb-4">
                    <?php echo esc_html( $args['title'] ); ?>
                </h2>
            <?php endif; ?>
            
            <?php if ( $args['description'] ) : ?>
                <div class="section-description max-w-3xl mx-auto <?php echo 'image' === $args['background'] ? 'text-white' : 'text-gray-600 dark:text-gray-400'; ?>">
                    <?php echo wp_kses_post( $args['description'] ); ?>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ( post_type_exists( 'aqualuxe_testimonial' ) && $testimonials->have_posts() ) : ?>
            <?php if ( 'slider' === $args['style'] ) : ?>
                <div class="testimonials-slider">
                    <?php while ( $testimonials->have_posts() ) : $testimonials->the_post(); ?>
                        <div class="testimonial-item text-center px-4">
                            <div class="testimonial-content bg-white dark:bg-dark text-gray-800 dark:text-white p-6 rounded-lg shadow-soft mb-8 relative">
                                <div class="testimonial-quote absolute top-4 left-4 text-primary opacity-20 text-4xl">
                                    <i class="fas fa-quote-left"></i>
                                </div>
                                
                                <?php
                                // Display rating if available
                                $rating = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_rating', true );
                                if ( $rating ) :
                                ?>
                                    <div class="testimonial-rating flex justify-center mb-4">
                                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                            <i class="fas fa-star <?php echo $i <= $rating ? 'text-accent' : 'text-gray-300'; ?> mx-0.5"></i>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-text mb-4">
                                    <?php the_content(); ?>
                                </div>
                                
                                <div class="testimonial-arrow absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-4 h-4 bg-white dark:bg-dark rotate-45"></div>
                            </div>
                            
                            <div class="testimonial-author flex flex-col items-center">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="testimonial-avatar w-16 h-16 rounded-full overflow-hidden mb-3">
                                        <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <h4 class="testimonial-name font-bold mb-1">
                                    <?php the_title(); ?>
                                </h4>
                                
                                <?php
                                // Display position if available
                                $position = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_position', true );
                                if ( $position ) :
                                ?>
                                    <div class="testimonial-position text-sm <?php echo 'image' === $args['background'] ? 'text-white opacity-80' : 'text-gray-600 dark:text-gray-400'; ?>">
                                        <?php echo esc_html( $position ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                
            <?php else : ?>
                <div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php while ( $testimonials->have_posts() ) : $testimonials->the_post(); ?>
                        <div class="testimonial-item">
                            <div class="testimonial-content bg-white dark:bg-dark text-gray-800 dark:text-white p-6 rounded-lg shadow-soft mb-6 relative">
                                <div class="testimonial-quote absolute top-4 left-4 text-primary opacity-20 text-4xl">
                                    <i class="fas fa-quote-left"></i>
                                </div>
                                
                                <?php
                                // Display rating if available
                                $rating = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_rating', true );
                                if ( $rating ) :
                                ?>
                                    <div class="testimonial-rating flex mb-4">
                                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                            <i class="fas fa-star <?php echo $i <= $rating ? 'text-accent' : 'text-gray-300'; ?> mr-0.5"></i>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-text mb-4">
                                    <?php the_content(); ?>
                                </div>
                                
                                <div class="testimonial-arrow absolute bottom-[-8px] left-6 w-4 h-4 bg-white dark:bg-dark rotate-45"></div>
                            </div>
                            
                            <div class="testimonial-author flex items-center">
                                <?php if ( has_post_thumbnail() ) : ?>
                                    <div class="testimonial-avatar w-12 h-12 rounded-full overflow-hidden mr-4">
                                        <?php the_post_thumbnail( 'thumbnail', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div>
                                    <h4 class="testimonial-name font-bold mb-0.5">
                                        <?php the_title(); ?>
                                    </h4>
                                    
                                    <?php
                                    // Display position if available
                                    $position = get_post_meta( get_the_ID(), '_aqualuxe_testimonial_position', true );
                                    if ( $position ) :
                                    ?>
                                        <div class="testimonial-position text-sm <?php echo 'image' === $args['background'] ? 'text-white opacity-80' : 'text-gray-600 dark:text-gray-400'; ?>">
                                            <?php echo esc_html( $position ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php endif; ?>
            
        <?php else : ?>
            <?php if ( 'slider' === $args['style'] ) : ?>
                <div class="testimonials-slider">
                    <?php foreach ( $sample_testimonials as $testimonial ) : ?>
                        <div class="testimonial-item text-center px-4">
                            <div class="testimonial-content bg-white dark:bg-dark text-gray-800 dark:text-white p-6 rounded-lg shadow-soft mb-8 relative">
                                <div class="testimonial-quote absolute top-4 left-4 text-primary opacity-20 text-4xl">
                                    <i class="fas fa-quote-left"></i>
                                </div>
                                
                                <?php if ( isset( $testimonial['rating'] ) ) : ?>
                                    <div class="testimonial-rating flex justify-center mb-4">
                                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                            <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? 'text-accent' : 'text-gray-300'; ?> mx-0.5"></i>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-text mb-4">
                                    <p><?php echo esc_html( $testimonial['content'] ); ?></p>
                                </div>
                                
                                <div class="testimonial-arrow absolute bottom-[-8px] left-1/2 transform -translate-x-1/2 w-4 h-4 bg-white dark:bg-dark rotate-45"></div>
                            </div>
                            
                            <div class="testimonial-author flex flex-col items-center">
                                <?php if ( ! empty( $testimonial['image_url'] ) ) : ?>
                                    <div class="testimonial-avatar w-16 h-16 rounded-full overflow-hidden mb-3">
                                        <img src="<?php echo esc_url( $testimonial['image_url'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>" class="w-full h-full object-cover">
                                    </div>
                                <?php else : ?>
                                    <div class="testimonial-avatar w-16 h-16 rounded-full overflow-hidden mb-3 bg-primary flex items-center justify-center text-white text-xl font-bold">
                                        <?php echo esc_html( substr( $testimonial['name'], 0, 1 ) ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <h4 class="testimonial-name font-bold mb-1">
                                    <?php echo esc_html( $testimonial['name'] ); ?>
                                </h4>
                                
                                <?php if ( ! empty( $testimonial['position'] ) ) : ?>
                                    <div class="testimonial-position text-sm <?php echo 'image' === $args['background'] ? 'text-white opacity-80' : 'text-gray-600 dark:text-gray-400'; ?>">
                                        <?php echo esc_html( $testimonial['position'] ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
            <?php else : ?>
                <div class="testimonials-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ( $sample_testimonials as $testimonial ) : ?>
                        <div class="testimonial-item">
                            <div class="testimonial-content bg-white dark:bg-dark text-gray-800 dark:text-white p-6 rounded-lg shadow-soft mb-6 relative">
                                <div class="testimonial-quote absolute top-4 left-4 text-primary opacity-20 text-4xl">
                                    <i class="fas fa-quote-left"></i>
                                </div>
                                
                                <?php if ( isset( $testimonial['rating'] ) ) : ?>
                                    <div class="testimonial-rating flex mb-4">
                                        <?php for ( $i = 1; $i <= 5; $i++ ) : ?>
                                            <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? 'text-accent' : 'text-gray-300'; ?> mr-0.5"></i>
                                        <?php endfor; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-text mb-4">
                                    <p><?php echo esc_html( $testimonial['content'] ); ?></p>
                                </div>
                                
                                <div class="testimonial-arrow absolute bottom-[-8px] left-6 w-4 h-4 bg-white dark:bg-dark rotate-45"></div>
                            </div>
                            
                            <div class="testimonial-author flex items-center">
                                <?php if ( ! empty( $testimonial['image_url'] ) ) : ?>
                                    <div class="testimonial-avatar w-12 h-12 rounded-full overflow-hidden mr-4">
                                        <img src="<?php echo esc_url( $testimonial['image_url'] ); ?>" alt="<?php echo esc_attr( $testimonial['name'] ); ?>" class="w-full h-full object-cover">
                                    </div>
                                <?php else : ?>
                                    <div class="testimonial-avatar w-12 h-12 rounded-full overflow-hidden mr-4 bg-primary flex items-center justify-center text-white text-lg font-bold">
                                        <?php echo esc_html( substr( $testimonial['name'], 0, 1 ) ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div>
                                    <h4 class="testimonial-name font-bold mb-0.5">
                                        <?php echo esc_html( $testimonial['name'] ); ?>
                                    </h4>
                                    
                                    <?php if ( ! empty( $testimonial['position'] ) ) : ?>
                                        <div class="testimonial-position text-sm <?php echo 'image' === $args['background'] ? 'text-white opacity-80' : 'text-gray-600 dark:text-gray-400'; ?>">
                                            <?php echo esc_html( $testimonial['position'] ); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</section>

<?php wp_reset_postdata(); ?>