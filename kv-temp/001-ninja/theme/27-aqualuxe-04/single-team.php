<?php
/**
 * The template for displaying single team member
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="container mx-auto px-4 py-12">
        <?php
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
            $team_education = get_post_meta( get_the_ID(), 'team_education', true );
            $team_experience = get_post_meta( get_the_ID(), 'team_experience', true );
            $team_expertise = get_post_meta( get_the_ID(), 'team_expertise', true );
            $team_certifications = get_post_meta( get_the_ID(), 'team_certifications', true );
            $team_languages = get_post_meta( get_the_ID(), 'team_languages', true );
            $team_quote = get_post_meta( get_the_ID(), 'team_quote', true );
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="team-layout grid grid-cols-1 lg:grid-cols-12 gap-12">
                    <div class="team-sidebar lg:col-span-4">
                        <div class="team-profile-card bg-white dark:bg-dark-800 rounded-lg shadow-medium overflow-hidden sticky top-32">
                            <?php if ( has_post_thumbnail() ) : ?>
                                <div class="team-image">
                                    <?php the_post_thumbnail( 'large', array( 'class' => 'w-full h-auto' ) ); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="team-info p-6">
                                <h1 class="team-name text-2xl font-bold mb-2"><?php the_title(); ?></h1>
                                
                                <?php if ( $team_position ) : ?>
                                    <div class="team-position text-primary-600 dark:text-primary-400 text-lg mb-4">
                                        <?php echo esc_html( $team_position ); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php
                                // Display team departments
                                $team_departments = get_the_terms( get_the_ID(), 'team_department' );
                                if ( $team_departments && ! is_wp_error( $team_departments ) ) : ?>
                                    <div class="team-departments flex flex-wrap gap-2 mb-6">
                                        <?php foreach ( $team_departments as $department ) : ?>
                                            <a href="<?php echo esc_url( get_term_link( $department ) ); ?>" class="inline-block px-3 py-1 bg-primary-100 text-primary-800 text-sm rounded-full hover:bg-primary-200 transition-colors dark:bg-primary-900 dark:text-primary-200 dark:hover:bg-primary-800">
                                                <?php echo esc_html( $department->name ); ?>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="team-contact space-y-3 mb-6">
                                    <?php if ( $team_email ) : ?>
                                        <div class="team-email flex items-center">
                                            <svg class="w-5 h-5 mr-3 text-primary-600 dark:text-primary-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                            </svg>
                                            <a href="mailto:<?php echo esc_attr( $team_email ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                                <?php echo esc_html( $team_email ); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ( $team_phone ) : ?>
                                        <div class="team-phone flex items-center">
                                            <svg class="w-5 h-5 mr-3 text-primary-600 dark:text-primary-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                            </svg>
                                            <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $team_phone ) ); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                                <?php echo esc_html( $team_phone ); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="team-social flex space-x-4 mb-6">
                                    <?php if ( $team_linkedin ) : ?>
                                        <a href="<?php echo esc_url( $team_linkedin ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'LinkedIn', 'aqualuxe' ); ?>">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ( $team_twitter ) : ?>
                                        <a href="<?php echo esc_url( $team_twitter ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Twitter', 'aqualuxe' ); ?>">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ( $team_facebook ) : ?>
                                        <a href="<?php echo esc_url( $team_facebook ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Facebook', 'aqualuxe' ); ?>">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.5 3.44 9.5 5.32v2.15h-3v4h3v12h5v-12h3.85l.42-4z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ( $team_instagram ) : ?>
                                        <a href="<?php echo esc_url( $team_instagram ); ?>" class="text-dark-600 hover:text-primary-600 dark:text-dark-400 dark:hover:text-primary-400" target="_blank" rel="noopener noreferrer" aria-label="<?php esc_attr_e( 'Instagram', 'aqualuxe' ); ?>">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"></path>
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ( $team_quote ) : ?>
                                    <div class="team-quote bg-primary-50 dark:bg-primary-900/30 p-4 rounded-lg border-l-4 border-primary-500 dark:border-primary-600 italic text-dark-700 dark:text-dark-300">
                                        <?php echo wp_kses_post( $team_quote ); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="team-main lg:col-span-8">
                        <div class="team-content bg-white dark:bg-dark-800 rounded-lg shadow-medium p-8">
                            <div class="team-bio prose max-w-none dark:prose-invert mb-8">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php if ( $team_expertise && is_array( $team_expertise ) && !empty( $team_expertise ) ) : ?>
                                <div class="team-expertise mb-8">
                                    <h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Areas of Expertise', 'aqualuxe' ); ?></h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <?php foreach ( $team_expertise as $expertise ) : 
                                            if ( empty( $expertise ) ) continue;
                                            ?>
                                            <div class="expertise-item flex items-center">
                                                <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span><?php echo esc_html( $expertise ); ?></span>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <div class="team-details grid grid-cols-1 md:grid-cols-2 gap-8">
                                <?php if ( $team_education && is_array( $team_education ) && !empty( $team_education ) ) : ?>
                                    <div class="team-education">
                                        <h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Education', 'aqualuxe' ); ?></h2>
                                        
                                        <ul class="space-y-4">
                                            <?php foreach ( $team_education as $education ) : 
                                                if ( empty( $education['degree'] ) ) continue;
                                                ?>
                                                <li class="education-item">
                                                    <div class="font-medium"><?php echo esc_html( $education['degree'] ); ?></div>
                                                    <?php if ( !empty( $education['institution'] ) ) : ?>
                                                        <div class="text-dark-600 dark:text-dark-400"><?php echo esc_html( $education['institution'] ); ?></div>
                                                    <?php endif; ?>
                                                    <?php if ( !empty( $education['year'] ) ) : ?>
                                                        <div class="text-sm text-dark-500 dark:text-dark-500"><?php echo esc_html( $education['year'] ); ?></div>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $team_experience && is_array( $team_experience ) && !empty( $team_experience ) ) : ?>
                                    <div class="team-experience">
                                        <h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Experience', 'aqualuxe' ); ?></h2>
                                        
                                        <ul class="space-y-4">
                                            <?php foreach ( $team_experience as $experience ) : 
                                                if ( empty( $experience['position'] ) ) continue;
                                                ?>
                                                <li class="experience-item">
                                                    <div class="font-medium"><?php echo esc_html( $experience['position'] ); ?></div>
                                                    <?php if ( !empty( $experience['company'] ) ) : ?>
                                                        <div class="text-dark-600 dark:text-dark-400"><?php echo esc_html( $experience['company'] ); ?></div>
                                                    <?php endif; ?>
                                                    <?php if ( !empty( $experience['period'] ) ) : ?>
                                                        <div class="text-sm text-dark-500 dark:text-dark-500"><?php echo esc_html( $experience['period'] ); ?></div>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="team-additional grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                                <?php if ( $team_certifications && is_array( $team_certifications ) && !empty( $team_certifications ) ) : ?>
                                    <div class="team-certifications">
                                        <h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Certifications', 'aqualuxe' ); ?></h2>
                                        
                                        <ul class="space-y-2">
                                            <?php foreach ( $team_certifications as $certification ) : 
                                                if ( empty( $certification ) ) continue;
                                                ?>
                                                <li class="certification-item flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span><?php echo esc_html( $certification ); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ( $team_languages && is_array( $team_languages ) && !empty( $team_languages ) ) : ?>
                                    <div class="team-languages">
                                        <h2 class="text-2xl font-bold mb-4"><?php esc_html_e( 'Languages', 'aqualuxe' ); ?></h2>
                                        
                                        <ul class="space-y-2">
                                            <?php foreach ( $team_languages as $language ) : 
                                                if ( empty( $language ) ) continue;
                                                ?>
                                                <li class="language-item flex items-center">
                                                    <svg class="w-5 h-5 mr-2 text-primary-600 dark:text-primary-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M7 2a1 1 0 011 1v1h3a1 1 0 110 2H9.20l-.8 2.4a1 1 0 01-.95.68h-1.9a1 1 0 01-.95-.68L3.8 6H2a1 1 0 010-2h3V3a1 1 0 011-1zm.5 5.48a1 1 0 00-1 0l-3 1.75a1 1 0 00-.5.87v3.48a1 1 0 00.5.87l3 1.75a1 1 0 001 0l3-1.75a1 1 0 00.5-.87V10.1a1 1 0 00-.5-.87l-3-1.75zM14 7a1 1 0 011 1v1h2a1 1 0 110 2h-2v1a1 1 0 11-2 0v-1h-2a1 1 0 110-2h2V8a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span><?php echo esc_html( $language ); ?></span>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php
                            // Get team member's posts if any
                            $team_posts_args = array(
                                'post_type' => 'post',
                                'posts_per_page' => 3,
                                'author_name' => get_post_field( 'post_name', get_the_ID() ),
                            );
                            
                            $team_posts = new WP_Query( $team_posts_args );
                            
                            if ( $team_posts->have_posts() ) : ?>
                                <div class="team-posts mt-12">
                                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e( 'Articles by', 'aqualuxe' ); ?> <?php the_title(); ?></h2>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                        <?php while ( $team_posts->have_posts() ) : $team_posts->the_post(); ?>
                                            <div class="team-post card overflow-hidden">
                                                <?php if ( has_post_thumbnail() ) : ?>
                                                    <a href="<?php the_permalink(); ?>" class="block">
                                                        <?php the_post_thumbnail( 'medium', array( 'class' => 'w-full h-48 object-cover' ) ); ?>
                                                    </a>
                                                <?php endif; ?>
                                                
                                                <div class="p-4">
                                                    <h3 class="text-lg font-bold mb-2">
                                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary-600 dark:hover:text-primary-400">
                                                            <?php the_title(); ?>
                                                        </a>
                                                    </h3>
                                                    
                                                    <div class="text-sm text-dark-500 dark:text-dark-400 mb-3">
                                                        <?php echo get_the_date(); ?>
                                                    </div>
                                                    
                                                    <div class="text-sm mb-3">
                                                        <?php echo wp_trim_words( get_the_excerpt(), 15, '...' ); ?>
                                                    </div>
                                                    
                                                    <a href="<?php the_permalink(); ?>" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-sm">
                                                        <?php esc_html_e( 'Read more', 'aqualuxe' ); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                </div>
                                <?php
                                wp_reset_postdata();
                            endif;
                            ?>
                            
                            <div class="team-navigation mt-12 pt-6 border-t border-dark-200 dark:border-dark-700">
                                <a href="<?php echo esc_url( get_post_type_archive_link( 'team' ) ); ?>" class="btn-outline">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                    <?php esc_html_e( 'Back to Team', 'aqualuxe' ); ?>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </article>

        <?php endwhile; // End of the loop. ?>
    </div>
</main><!-- #main -->

<?php
get_footer();