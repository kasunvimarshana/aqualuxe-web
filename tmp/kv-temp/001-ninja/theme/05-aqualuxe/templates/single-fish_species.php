<?php
/**
 * The template for displaying single fish species
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php
        while ( have_posts() ) :
            the_post();
            
            // Get fish species details
            $scientific_name = get_post_meta( get_the_ID(), '_fish_scientific_name', true );
            $common_names = get_post_meta( get_the_ID(), '_fish_common_names', true );
            $origin = get_post_meta( get_the_ID(), '_fish_origin', true );
            $adult_size = get_post_meta( get_the_ID(), '_fish_adult_size', true );
            $lifespan = get_post_meta( get_the_ID(), '_fish_lifespan', true );
            $temperament = get_post_meta( get_the_ID(), '_fish_temperament', true );
            $diet = get_post_meta( get_the_ID(), '_fish_diet', true );
            $breeding = get_post_meta( get_the_ID(), '_fish_breeding', true );
            
            // Get fish care information
            $tank_size = get_post_meta( get_the_ID(), '_fish_tank_size', true );
            $water_temp = get_post_meta( get_the_ID(), '_fish_water_temp', true );
            $water_ph = get_post_meta( get_the_ID(), '_fish_water_ph', true );
            $water_hardness = get_post_meta( get_the_ID(), '_fish_water_hardness', true );
            $substrate = get_post_meta( get_the_ID(), '_fish_substrate', true );
            $plants = get_post_meta( get_the_ID(), '_fish_plants', true );
            $lighting = get_post_meta( get_the_ID(), '_fish_lighting', true );
            $tank_mates = get_post_meta( get_the_ID(), '_fish_tank_mates', true );
            $special_requirements = get_post_meta( get_the_ID(), '_fish_special_requirements', true );
            
            // Get related products
            $related_products = get_post_meta( get_the_ID(), '_fish_related_products', true );
            
            // Get taxonomies
            $categories = get_the_terms( get_the_ID(), 'fish_category' );
            $habitats = get_the_terms( get_the_ID(), 'fish_habitat' );
            $care_levels = get_the_terms( get_the_ID(), 'fish_care_level' );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('fish-species-single'); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                    
                    <?php if ( $scientific_name ) : ?>
                        <h2 class="fish-scientific-name"><em><?php echo esc_html( $scientific_name ); ?></em></h2>
                    <?php endif; ?>
                    
                    <div class="fish-taxonomy">
                        <?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
                            <div class="fish-categories">
                                <span class="fish-taxonomy-label"><?php esc_html_e( 'Categories:', 'aqualuxe' ); ?></span>
                                <span class="fish-taxonomy-terms">
                                    <?php
                                    $category_links = array();
                                    foreach ( $categories as $category ) {
                                        $category_links[] = '<a href="' . esc_url( get_term_link( $category ) ) . '">' . esc_html( $category->name ) . '</a>';
                                    }
                                    echo implode( ', ', $category_links );
                                    ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $habitats && ! is_wp_error( $habitats ) ) : ?>
                            <div class="fish-habitats">
                                <span class="fish-taxonomy-label"><?php esc_html_e( 'Habitats:', 'aqualuxe' ); ?></span>
                                <span class="fish-taxonomy-terms">
                                    <?php
                                    $habitat_links = array();
                                    foreach ( $habitats as $habitat ) {
                                        $habitat_links[] = '<a href="' . esc_url( get_term_link( $habitat ) ) . '">' . esc_html( $habitat->name ) . '</a>';
                                    }
                                    echo implode( ', ', $habitat_links );
                                    ?>
                                </span>
                            </div>
                        <?php endif; ?>
                        
                        <?php if ( $care_levels && ! is_wp_error( $care_levels ) ) : ?>
                            <div class="fish-care-levels">
                                <span class="fish-taxonomy-label"><?php esc_html_e( 'Care Level:', 'aqualuxe' ); ?></span>
                                <span class="fish-taxonomy-terms">
                                    <?php
                                    $care_level_links = array();
                                    foreach ( $care_levels as $care_level ) {
                                        $care_level_links[] = '<a href="' . esc_url( get_term_link( $care_level ) ) . '">' . esc_html( $care_level->name ) . '</a>';
                                    }
                                    echo implode( ', ', $care_level_links );
                                    ?>
                                </span>
                            </div>
                        <?php endif; ?>
                    </div>
                </header>

                <div class="fish-species-content">
                    <div class="fish-species-main">
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="fish-species-image">
                                <?php the_post_thumbnail( 'large' ); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="fish-species-description">
                            <h2><?php esc_html_e( 'Description', 'aqualuxe' ); ?></h2>
                            <?php the_content(); ?>
                        </div>
                        
                        <div class="fish-species-details">
                            <h2><?php esc_html_e( 'Fish Details', 'aqualuxe' ); ?></h2>
                            
                            <div class="fish-species-details-grid">
                                <?php if ( $common_names ) : ?>
                                    <div class="fish-species-detail">
                                        <h3><?php esc_html_e( 'Common Names', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $common_names ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $origin ) : ?>
                                    <div class="fish-species-detail">
                                        <h3><?php esc_html_e( 'Origin', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $origin ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $adult_size ) : ?>
                                    <div class="fish-species-detail">
                                        <h3><?php esc_html_e( 'Adult Size', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $adult_size ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $lifespan ) : ?>
                                    <div class="fish-species-detail">
                                        <h3><?php esc_html_e( 'Lifespan', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $lifespan ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $temperament ) : ?>
                                    <div class="fish-species-detail">
                                        <h3><?php esc_html_e( 'Temperament', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $temperament ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $diet ) : ?>
                                    <div class="fish-species-detail">
                                        <h3><?php esc_html_e( 'Diet', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $diet ); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ( $breeding ) : ?>
                                <div class="fish-species-breeding">
                                    <h3><?php esc_html_e( 'Breeding', 'aqualuxe' ); ?></h3>
                                    <p><?php echo esc_html( $breeding ); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="fish-species-care">
                            <h2><?php esc_html_e( 'Care Information', 'aqualuxe' ); ?></h2>
                            
                            <div class="fish-species-care-grid">
                                <?php if ( $tank_size ) : ?>
                                    <div class="fish-species-care-item">
                                        <h3><?php esc_html_e( 'Tank Size', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $tank_size ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $water_temp ) : ?>
                                    <div class="fish-species-care-item">
                                        <h3><?php esc_html_e( 'Water Temperature', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $water_temp ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $water_ph ) : ?>
                                    <div class="fish-species-care-item">
                                        <h3><?php esc_html_e( 'Water pH', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $water_ph ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $water_hardness ) : ?>
                                    <div class="fish-species-care-item">
                                        <h3><?php esc_html_e( 'Water Hardness', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $water_hardness ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $substrate ) : ?>
                                    <div class="fish-species-care-item">
                                        <h3><?php esc_html_e( 'Substrate', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $substrate ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $plants ) : ?>
                                    <div class="fish-species-care-item">
                                        <h3><?php esc_html_e( 'Plants', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $plants ); ?></p>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $lighting ) : ?>
                                    <div class="fish-species-care-item">
                                        <h3><?php esc_html_e( 'Lighting', 'aqualuxe' ); ?></h3>
                                        <p><?php echo esc_html( $lighting ); ?></p>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php if ( $tank_mates ) : ?>
                                <div class="fish-species-tank-mates">
                                    <h3><?php esc_html_e( 'Compatible Tank Mates', 'aqualuxe' ); ?></h3>
                                    <p><?php echo esc_html( $tank_mates ); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ( $special_requirements ) : ?>
                                <div class="fish-species-special-requirements">
                                    <h3><?php esc_html_e( 'Special Requirements', 'aqualuxe' ); ?></h3>
                                    <p><?php echo esc_html( $special_requirements ); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ( is_array( $related_products ) && ! empty( $related_products ) && class_exists( 'WooCommerce' ) ) : ?>
                            <div class="fish-species-products">
                                <h2><?php esc_html_e( 'Recommended Products', 'aqualuxe' ); ?></h2>
                                
                                <ul class="products columns-3">
                                    <?php
                                    $args = array(
                                        'post_type'      => 'product',
                                        'posts_per_page' => -1,
                                        'post__in'       => $related_products,
                                    );
                                    
                                    $products = new WP_Query( $args );
                                    
                                    if ( $products->have_posts() ) {
                                        while ( $products->have_posts() ) {
                                            $products->the_post();
                                            wc_get_template_part( 'content', 'product' );
                                        }
                                    }
                                    
                                    wp_reset_postdata();
                                    ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <aside class="fish-species-sidebar">
                        <div class="fish-species-summary">
                            <h3><?php esc_html_e( 'Quick Facts', 'aqualuxe' ); ?></h3>
                            
                            <ul class="fish-species-facts">
                                <?php if ( $care_levels && ! is_wp_error( $care_levels ) ) : ?>
                                    <li>
                                        <span class="fact-label"><?php esc_html_e( 'Care Level:', 'aqualuxe' ); ?></span>
                                        <span class="fact-value">
                                            <?php
                                            $care_level_names = array();
                                            foreach ( $care_levels as $care_level ) {
                                                $care_level_names[] = $care_level->name;
                                            }
                                            echo esc_html( implode( ', ', $care_level_names ) );
                                            ?>
                                        </span>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ( $temperament ) : ?>
                                    <li>
                                        <span class="fact-label"><?php esc_html_e( 'Temperament:', 'aqualuxe' ); ?></span>
                                        <span class="fact-value"><?php echo esc_html( $temperament ); ?></span>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ( $adult_size ) : ?>
                                    <li>
                                        <span class="fact-label"><?php esc_html_e( 'Adult Size:', 'aqualuxe' ); ?></span>
                                        <span class="fact-value"><?php echo esc_html( $adult_size ); ?></span>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ( $diet ) : ?>
                                    <li>
                                        <span class="fact-label"><?php esc_html_e( 'Diet:', 'aqualuxe' ); ?></span>
                                        <span class="fact-value"><?php echo esc_html( $diet ); ?></span>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ( $lifespan ) : ?>
                                    <li>
                                        <span class="fact-label"><?php esc_html_e( 'Lifespan:', 'aqualuxe' ); ?></span>
                                        <span class="fact-value"><?php echo esc_html( $lifespan ); ?></span>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ( $tank_size ) : ?>
                                    <li>
                                        <span class="fact-label"><?php esc_html_e( 'Tank Size:', 'aqualuxe' ); ?></span>
                                        <span class="fact-value"><?php echo esc_html( $tank_size ); ?></span>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ( $water_temp ) : ?>
                                    <li>
                                        <span class="fact-label"><?php esc_html_e( 'Water Temp:', 'aqualuxe' ); ?></span>
                                        <span class="fact-value"><?php echo esc_html( $water_temp ); ?></span>
                                    </li>
                                <?php endif; ?>
                                
                                <?php if ( $water_ph ) : ?>
                                    <li>
                                        <span class="fact-label"><?php esc_html_e( 'Water pH:', 'aqualuxe' ); ?></span>
                                        <span class="fact-value"><?php echo esc_html( $water_ph ); ?></span>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                        
                        <?php
                        // Get related fish species
                        if ( $categories && ! is_wp_error( $categories ) ) {
                            $category_ids = wp_list_pluck( $categories, 'term_id' );
                            
                            $related_args = array(
                                'post_type'      => 'fish_species',
                                'posts_per_page' => 5,
                                'post__not_in'   => array( get_the_ID() ),
                                'tax_query'      => array(
                                    array(
                                        'taxonomy' => 'fish_category',
                                        'field'    => 'term_id',
                                        'terms'    => $category_ids,
                                    ),
                                ),
                            );
                            
                            $related_fish = new WP_Query( $related_args );
                            
                            if ( $related_fish->have_posts() ) :
                                ?>
                                <div class="fish-species-related">
                                    <h3><?php esc_html_e( 'Related Fish Species', 'aqualuxe' ); ?></h3>
                                    
                                    <ul class="fish-species-related-list">
                                        <?php
                                        while ( $related_fish->have_posts() ) :
                                            $related_fish->the_post();
                                            ?>
                                            <li>
                                                <a href="<?php the_permalink(); ?>" class="fish-species-related-item">
                                                    <?php if ( has_post_thumbnail() ) : ?>
                                                        <?php the_post_thumbnail( 'thumbnail' ); ?>
                                                    <?php endif; ?>
                                                    <span class="fish-species-related-title"><?php the_title(); ?></span>
                                                </a>
                                            </li>
                                            <?php
                                        endwhile;
                                        wp_reset_postdata();
                                        ?>
                                    </ul>
                                </div>
                                <?php
                            endif;
                        }
                        ?>
                    </aside>
                </div>

                <footer class="entry-footer">
                    <?php
                    // Display post navigation
                    the_post_navigation( array(
                        'prev_text' => '<span class="nav-subtitle">' . esc_html__( 'Previous:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
                        'next_text' => '<span class="nav-subtitle">' . esc_html__( 'Next:', 'aqualuxe' ) . '</span> <span class="nav-title">%title</span>',
                    ) );
                    
                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                    ?>
                </footer>
            </article>

        <?php endwhile; ?>

    </main><!-- #main -->
</div><!-- #primary -->

<style>
    /* Fish Species Single Styles */
    .fish-species-single {
        margin-bottom: 40px;
    }
    
    .fish-species-single .entry-title {
        margin-bottom: 10px;
    }
    
    .fish-scientific-name {
        margin-top: 0;
        margin-bottom: 20px;
        font-size: 1.5rem;
        color: var(--color-medium-gray);
    }
    
    .fish-taxonomy {
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--color-light-gray);
    }
    
    .fish-taxonomy > div {
        margin-bottom: 5px;
    }
    
    .fish-taxonomy-label {
        font-weight: 600;
        margin-right: 5px;
    }
    
    .fish-species-content {
        display: flex;
        flex-wrap: wrap;
        margin: 0 -20px;
    }
    
    .fish-species-main {
        flex: 1;
        min-width: 0;
        padding: 0 20px;
        width: 70%;
    }
    
    .fish-species-sidebar {
        width: 30%;
        padding: 0 20px;
    }
    
    .fish-species-image {
        margin-bottom: 30px;
    }
    
    .fish-species-image img {
        width: 100%;
        height: auto;
        border-radius: var(--border-radius-md);
    }
    
    .fish-species-description {
        margin-bottom: 40px;
    }
    
    .fish-species-details {
        margin-bottom: 40px;
    }
    
    .fish-species-details-grid,
    .fish-species-care-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
        margin-bottom: 20px;
    }
    
    .fish-species-detail h3,
    .fish-species-care-item h3 {
        margin-bottom: 5px;
        font-size: 1.1rem;
    }
    
    .fish-species-breeding,
    .fish-species-tank-mates,
    .fish-species-special-requirements {
        margin-top: 20px;
    }
    
    .fish-species-care {
        margin-bottom: 40px;
    }
    
    .fish-species-products {
        margin-bottom: 40px;
    }
    
    .fish-species-summary {
        background-color: var(--color-very-light-gray);
        padding: 20px;
        border-radius: var(--border-radius-md);
        margin-bottom: 30px;
    }
    
    .fish-species-facts {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .fish-species-facts li {
        padding: 8px 0;
        border-bottom: 1px solid var(--color-light-gray);
        display: flex;
        justify-content: space-between;
    }
    
    .fish-species-facts li:last-child {
        border-bottom: none;
    }
    
    .fact-label {
        font-weight: 600;
    }
    
    .fish-species-related {
        margin-bottom: 30px;
    }
    
    .fish-species-related-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .fish-species-related-list li {
        margin-bottom: 15px;
    }
    
    .fish-species-related-item {
        display: flex;
        align-items: center;
        text-decoration: none;
        color: var(--color-dark-gray);
    }
    
    .fish-species-related-item img {
        width: 60px;
        height: 60px;
        border-radius: var(--border-radius-sm);
        margin-right: 10px;
        object-fit: cover;
    }
    
    .fish-species-related-title {
        font-weight: 500;
    }
    
    @media (max-width: 991px) {
        .fish-species-main,
        .fish-species-sidebar {
            width: 100%;
        }
        
        .fish-species-sidebar {
            margin-top: 40px;
        }
    }
    
    @media (max-width: 767px) {
        .fish-species-details-grid,
        .fish-species-care-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<?php
get_sidebar();
get_footer();