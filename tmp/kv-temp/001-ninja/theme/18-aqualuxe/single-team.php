<?php
/**
 * The template for displaying Team Member single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>

<main id="primary" class="site-main py-12">
    <div class="container-fluid">
        <?php
        // Breadcrumbs
        if (function_exists('aqualuxe_breadcrumbs')) :
            aqualuxe_breadcrumbs();
        endif;

        while (have_posts()) :
            the_post();
            
            // Get team member meta
            $position = get_post_meta(get_the_ID(), 'position', true);
            $email = get_post_meta(get_the_ID(), 'email', true);
            $phone = get_post_meta(get_the_ID(), 'phone', true);
            $linkedin = get_post_meta(get_the_ID(), 'linkedin', true);
            $twitter = get_post_meta(get_the_ID(), 'twitter', true);
            $facebook = get_post_meta(get_the_ID(), 'facebook', true);
            $instagram = get_post_meta(get_the_ID(), 'instagram', true);
            $expertise = get_post_meta(get_the_ID(), 'expertise', true);
            $education = get_post_meta(get_the_ID(), 'education', true);
            $certifications = get_post_meta(get_the_ID(), 'certifications', true);
            $years_experience = get_post_meta(get_the_ID(), 'years_experience', true);
            
            // Get taxonomy terms
            $departments = get_the_terms(get_the_ID(), 'department');
            ?>

            <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none overflow-hidden'); ?>>
                <div class="team-member-content p-6 md:p-8">
                    <div class="flex flex-col md:flex-row">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="team-member-image md:w-1/3 md:pr-8 mb-6 md:mb-0">
                                <div class="rounded-xl overflow-hidden">
                                    <?php the_post_thumbnail('large', ['class' => 'w-full h-auto']); ?>
                                </div>
                                
                                <div class="team-member-contact mt-6 space-y-3">
                                    <?php if ($email) : ?>
                                        <div class="contact-item flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <a href="mailto:<?php echo esc_attr($email); ?>" class="hover:text-primary transition-colors duration-300">
                                                <?php echo esc_html($email); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($phone) : ?>
                                        <div class="contact-item flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-3 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <a href="tel:<?php echo esc_attr($phone); ?>" class="hover:text-primary transition-colors duration-300">
                                                <?php echo esc_html($phone); ?>
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <?php if ($linkedin || $twitter || $facebook || $instagram) : ?>
                                    <div class="team-member-social mt-6 flex space-x-3">
                                        <?php if ($linkedin) : ?>
                                            <a href="<?php echo esc_url($linkedin); ?>" class="bg-primary hover:bg-primary-dark text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($twitter) : ?>
                                            <a href="<?php echo esc_url($twitter); ?>" class="bg-primary hover:bg-primary-dark text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($facebook) : ?>
                                            <a href="<?php echo esc_url($facebook); ?>" class="bg-primary hover:bg-primary-dark text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($instagram) : ?>
                                            <a href="<?php echo esc_url($instagram); ?>" class="bg-primary hover:bg-primary-dark text-white w-10 h-10 rounded-full flex items-center justify-center transition-colors duration-300" target="_blank" rel="noopener noreferrer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                </svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="team-member-details md:w-2/3">
                            <header class="entry-header mb-6">
                                <h1 class="entry-title text-3xl md:text-4xl font-bold mb-2"><?php the_title(); ?></h1>
                                
                                <?php if ($position) : ?>
                                    <p class="team-member-position text-xl text-primary mb-2"><?php echo esc_html($position); ?></p>
                                <?php endif; ?>
                                
                                <?php if ($departments && !is_wp_error($departments)) : ?>
                                    <div class="team-member-departments mb-4">
                                        <?php foreach ($departments as $department) : ?>
                                            <span class="bg-primary-light text-primary-dark text-sm px-3 py-1 rounded-full mr-2">
                                                <?php echo esc_html($department->name); ?>
                                            </span>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </header>

                            <div class="team-member-bio prose prose-lg dark:prose-invert max-w-none mb-8">
                                <?php the_content(); ?>
                            </div>
                            
                            <?php if ($expertise || $education || $certifications || $years_experience) : ?>
                                <div class="team-member-qualifications space-y-6 mb-8">
                                    <?php if ($expertise) : ?>
                                        <div class="qualification-item">
                                            <h3 class="text-xl font-bold mb-2 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                                </svg>
                                                <?php esc_html_e('Areas of Expertise', 'aqualuxe'); ?>
                                            </h3>
                                            <div class="expertise-list flex flex-wrap gap-2">
                                                <?php
                                                // Convert string to array if it's a comma-separated list
                                                if (strpos($expertise, ',') !== false) {
                                                    $expertise_items = explode(',', $expertise);
                                                    foreach ($expertise_items as $item) {
                                                        echo '<span class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-sm">' . esc_html(trim($item)) . '</span>';
                                                    }
                                                } else {
                                                    echo '<span class="bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full text-sm">' . esc_html($expertise) . '</span>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($education) : ?>
                                        <div class="qualification-item">
                                            <h3 class="text-xl font-bold mb-2 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                                                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                                                </svg>
                                                <?php esc_html_e('Education', 'aqualuxe'); ?>
                                            </h3>
                                            <div class="education-list">
                                                <?php
                                                // Convert string to array if it's a semicolon-separated list
                                                if (strpos($education, ';') !== false) {
                                                    $education_items = explode(';', $education);
                                                    echo '<ul class="space-y-2">';
                                                    foreach ($education_items as $item) {
                                                        echo '<li class="flex items-start">';
                                                        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">';
                                                        echo '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />';
                                                        echo '</svg>';
                                                        echo esc_html(trim($item));
                                                        echo '</li>';
                                                    }
                                                    echo '</ul>';
                                                } else {
                                                    echo '<p>' . esc_html($education) . '</p>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($certifications) : ?>
                                        <div class="qualification-item">
                                            <h3 class="text-xl font-bold mb-2 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                                </svg>
                                                <?php esc_html_e('Certifications', 'aqualuxe'); ?>
                                            </h3>
                                            <div class="certifications-list">
                                                <?php
                                                // Convert string to array if it's a semicolon-separated list
                                                if (strpos($certifications, ';') !== false) {
                                                    $certification_items = explode(';', $certifications);
                                                    echo '<ul class="space-y-2">';
                                                    foreach ($certification_items as $item) {
                                                        echo '<li class="flex items-start">';
                                                        echo '<svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary flex-shrink-0 mt-0.5" viewBox="0 0 20 20" fill="currentColor">';
                                                        echo '<path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />';
                                                        echo '</svg>';
                                                        echo esc_html(trim($item));
                                                        echo '</li>';
                                                    }
                                                    echo '</ul>';
                                                } else {
                                                    echo '<p>' . esc_html($certifications) . '</p>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <?php if ($years_experience) : ?>
                                        <div class="qualification-item">
                                            <h3 class="text-xl font-bold mb-2 flex items-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                                <?php esc_html_e('Experience', 'aqualuxe'); ?>
                                            </h3>
                                            <p class="text-lg">
                                                <?php
                                                printf(
                                                    /* translators: %s: years of experience */
                                                    _n('%s Year of Experience', '%s Years of Experience', intval($years_experience), 'aqualuxe'),
                                                    esc_html($years_experience)
                                                );
                                                ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Display team member's posts if they are an author
                            $author_id = get_post_meta(get_the_ID(), 'author_id', true);
                            if ($author_id) :
                                $args = array(
                                    'author' => $author_id,
                                    'post_type' => 'post',
                                    'posts_per_page' => 3,
                                );
                                $author_posts = new WP_Query($args);
                                
                                if ($author_posts->have_posts()) :
                                ?>
                                <div class="team-member-posts mb-8">
                                    <h3 class="text-2xl font-bold mb-4"><?php esc_html_e('Recent Articles', 'aqualuxe'); ?></h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                        <?php while ($author_posts->have_posts()) : $author_posts->the_post(); ?>
                                            <div class="post-card bg-gray-50 dark:bg-gray-800 rounded-xl overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <div class="post-image">
                                                        <a href="<?php the_permalink(); ?>">
                                                            <?php the_post_thumbnail('medium', ['class' => 'w-full h-48 object-cover']); ?>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <div class="post-content p-4">
                                                    <h4 class="post-title text-lg font-bold mb-2">
                                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                                            <?php the_title(); ?>
                                                        </a>
                                                    </h4>
                                                    
                                                    <div class="post-meta text-sm text-gray-600 dark:text-gray-400 mb-2">
                                                        <?php echo get_the_date(); ?>
                                                    </div>
                                                    
                                                    <a href="<?php the_permalink(); ?>" class="text-primary hover:text-primary-dark font-medium transition-colors duration-300 flex items-center text-sm">
                                                        <?php esc_html_e('Read Article', 'aqualuxe'); ?>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                    
                                    <div class="mt-4 text-center">
                                        <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="btn btn-outline">
                                            <?php esc_html_e('View All Articles', 'aqualuxe'); ?>
                                        </a>
                                    </div>
                                </div>
                                <?php
                                wp_reset_postdata();
                                endif;
                            endif;
                            ?>
                        </div>
                    </div>
                </div>
            </article><!-- #post-<?php the_ID(); ?> -->

            <?php
            // Display other team members
            $args = array(
                'post_type' => 'team',
                'posts_per_page' => 4,
                'post__not_in' => array(get_the_ID()),
                'orderby' => 'rand',
            );
            
            // If the current team member has a department, prioritize team members from the same department
            if ($departments && !is_wp_error($departments)) {
                $department_ids = wp_list_pluck($departments, 'term_id');
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => 'department',
                        'field' => 'term_id',
                        'terms' => $department_ids,
                    ),
                );
            }
            
            $team_members = new WP_Query($args);
            
            if ($team_members->have_posts()) :
            ?>
                <div class="other-team-members mt-8 bg-white dark:bg-dark-card rounded-xl shadow-soft dark:shadow-none p-6 md:p-8">
                    <h2 class="text-2xl font-bold mb-6"><?php esc_html_e('Meet Our Team', 'aqualuxe'); ?></h2>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                        <?php while ($team_members->have_posts()) : $team_members->the_post(); ?>
                            <div class="team-member-card bg-gray-50 dark:bg-gray-800 rounded-xl overflow-hidden transition-transform duration-300 hover:-translate-y-1">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="team-member-image">
                                        <a href="<?php the_permalink(); ?>">
                                            <?php the_post_thumbnail('medium', ['class' => 'w-full h-64 object-cover']); ?>
                                        </a>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="team-member-content p-4">
                                    <h3 class="team-member-name text-lg font-bold mb-1">
                                        <a href="<?php the_permalink(); ?>" class="hover:text-primary transition-colors duration-300">
                                            <?php the_title(); ?>
                                        </a>
                                    </h3>
                                    
                                    <?php
                                    $member_position = get_post_meta(get_the_ID(), 'position', true);
                                    if ($member_position) :
                                    ?>
                                        <p class="team-member-position text-primary text-sm mb-2"><?php echo esc_html($member_position); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php
                                    $member_departments = get_the_terms(get_the_ID(), 'department');
                                    if ($member_departments && !is_wp_error($member_departments)) :
                                        $department_names = array();
                                        foreach ($member_departments as $dept) {
                                            $department_names[] = $dept->name;
                                        }
                                    ?>
                                        <p class="team-member-department text-sm text-gray-600 dark:text-gray-400 mb-3">
                                            <?php echo esc_html(implode(', ', $department_names)); ?>
                                        </p>
                                    <?php endif; ?>
                                    
                                    <a href="<?php the_permalink(); ?>" class="text-primary hover:text-primary-dark font-medium transition-colors duration-300 flex items-center text-sm">
                                        <?php esc_html_e('View Profile', 'aqualuxe'); ?>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    
                    <div class="mt-6 text-center">
                        <a href="<?php echo esc_url(get_post_type_archive_link('team')); ?>" class="btn btn-primary">
                            <?php esc_html_e('View All Team Members', 'aqualuxe'); ?>
                        </a>
                    </div>
                </div>
            <?php
            wp_reset_postdata();
            endif;
            ?>

        <?php endwhile; // End of the loop. ?>
    </div>
</main><!-- #main -->

<?php
get_footer();