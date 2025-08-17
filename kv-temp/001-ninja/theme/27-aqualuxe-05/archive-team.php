<?php
/**
 * The template for displaying team members archive
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <header class="page-header mb-12 text-center">
            <h1 class="page-title text-4xl md:text-5xl mb-4"><?php post_type_archive_title(); ?></h1>
            <div class="archive-description prose mx-auto">
                <?php the_archive_description(); ?>
            </div>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="team-filter mb-8">
                <form class="flex flex-wrap gap-4 justify-center" method="get">
                    <?php
                    // Get all team departments
                    $team_departments = get_terms( array(
                        'taxonomy' => 'team_department',
                        'hide_empty' => true,
                    ) );
                    
                    if ( ! empty( $team_departments ) && ! is_wp_error( $team_departments ) ) : ?>
                        <div class="filter-group">
                            <label for="team_department" class="sr-only"><?php esc_html_e( 'Filter by Department', 'aqualuxe' ); ?></label>
                            <select name="team_department" id="team_department" class="form-input">
                                <option value=""><?php esc_html_e( 'All Departments', 'aqualuxe' ); ?></option>
                                <?php foreach ( $team_departments as $department ) : ?>
                                    <option value="<?php echo esc_attr( $department->slug ); ?>" <?php selected( isset( $_GET['team_department'] ) ? sanitize_text_field( $_GET['team_department'] ) : '', $department->slug ); ?>>
                                        <?php echo esc_html( $department->name ); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn-primary"><?php esc_html_e( 'Filter', 'aqualuxe' ); ?></button>
                </form>
            </div>

            <div class="team-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php
                /* Start the Loop */
                while ( have_posts() ) :
                    the_post();
                    
                    // Get team member meta
                    $team_position = get_post_meta( get_the_ID(), 'team_position', true );
                    $team_email = get_post_meta( get_the_ID(), 'team_email', true );
                    $team_phone = get_post_meta( get_the_ID(), 'team_phone', true );
                    $team_linkedin = get_post_meta( get_the_ID(), 'team_linkedin', true );
                    $team_twitter = get_post_meta( get_the_ID(), 'team_twitter', true );
                    $team_facebook = get_post_meta( get_the_ID(), 'team_facebook', true );
                    $team_instagram = get_post_meta( get_the_ID(), 'team_instagram', true );
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'team-card card h-full flex flex-col' ); ?>>
                        <?php if ( has_post_thumbnail() ) : ?>
                            <div class="team-image relative overflow-hidden">
                                <a href="<?php the_permalink(); ?>" class="block">
                                    <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-80 object-cover transition-transform duration-500 hover:scale-105' ) ); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="team-content p-6 flex-grow flex flex-col">
                            <header class="entry-header mb-4">
                                <?php the_title( '<h2 class="entry-title text-xl font-bold"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
                                
                                <?php if ( $team_position ) : ?>
                                    <div class="team-position text-primary-600 dark:text-primary-400">
                                        <?php echo esc_html( $team_position ); ?>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <div class="entry-content mb-6 flex-grow">
                                <?php the_excerpt(); ?>
                            </div>

                            <footer class="entry-footer mt-auto">
                                <?php
                                // Display team departments
                                $team_departments = get_the_terms( get_the_ID(), 'team_department' );
                                if ( $team_departments && ! is_wp_error( $team_departments ) ) : ?>
                                    <div class="team-departments flex flex-wrap gap-2 mb-4">
                                        <?php foreach ( $team_departments as $department ) : ?>
                                            <a href="<?php echo esc_url( get_term_link( $department ) ); ?>" class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">
                                                <?php echo esc_html( $department->name ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="team-social flex space-x-3">
                                    <?php if ( $team_email ) : ?>
                                        <a href="mailto:<?php echo esc_attr( $team_email ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" aria-label="<?php esc_attr_e( 'Email', 'aqualuxe' ); ?>">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ( $team_phone ) : ?>
                                        <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $team_phone ) ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" aria-label="<?php esc_attr_e( 'Phone', 'aqualuxe' ); ?>">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ( $team_linkedin ) : ?>
                                        <a href="<?php echo esc_url( $team_linkedin ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'aqualuxe' ); ?>">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ( $team_twitter ) : ?>
                                        <a href="<?php echo esc_url( $team_twitter ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Twitter', 'aqualuxe' ); ?>">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ( $team_facebook ) : ?>
                                        <a href="<?php echo esc_url( $team_facebook ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'aqualuxe' ); ?>">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ( $team_instagram ) : ?>
                                        <a href="<?php echo esc_url( $team_instagram ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'aqualuxe' ); ?>">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </footer>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <?php
            // Pagination
            the_posts_pagination( array(
                'prev_text' => '<span class="screen-reader-text">' . esc_html__( 'Previous page', 'aqualuxe' ) . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>',
                'next_text' => '<span class="screen-reader-text">' . esc_html__( 'Next page', 'aqualuxe' ) . '</span><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>',
                'class' => 'mt-12 flex justify-center',
            ) );

        else :
            get_template_part( 'template-parts/content/content', 'none' );
        endif;
        ?>
    </div>
</main><!-- #main -->

<?php
get_footer();