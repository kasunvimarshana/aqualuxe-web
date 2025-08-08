<?php
/**
 * Template Name: About Page
 *
 * This is the template that displays the about page.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main">
    <?php
    // Hero Section
    $hero_image = get_post_meta(get_the_ID(), 'about_hero_image', true);
    if (!$hero_image) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    $hero_title = get_post_meta(get_the_ID(), 'about_hero_title', true);
    if (!$hero_title) {
        $hero_title = get_the_title();
    }
    $hero_subtitle = get_post_meta(get_the_ID(), 'about_hero_subtitle', true);
    ?>
    <section class="about-hero relative py-20 bg-cover bg-center" <?php if ($hero_image) : ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
        <div class="absolute inset-0 bg-black bg-opacity-50"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center text-white">
                <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_subtitle) : ?>
                    <p class="text-xl md:text-2xl"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php
    // Our Story Section
    $story_title = get_post_meta(get_the_ID(), 'about_story_title', true);
    $story_content = get_post_meta(get_the_ID(), 'about_story_content', true);
    $story_image = get_post_meta(get_the_ID(), 'about_story_image', true);
    
    if ($story_title || $story_content || $story_image || get_the_content()) :
    ?>
    <section class="about-story py-16">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="about-story-content">
                    <?php if ($story_title) : ?>
                        <h2 class="text-3xl font-bold mb-6"><?php echo esc_html($story_title); ?></h2>
                    <?php endif; ?>
                    
                    <?php if ($story_content) : ?>
                        <div class="prose dark:prose-invert max-w-none">
                            <?php echo wp_kses_post(wpautop($story_content)); ?>
                        </div>
                    <?php elseif (get_the_content()) : ?>
                        <div class="prose dark:prose-invert max-w-none">
                            <?php the_content(); ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="about-story-image">
                    <?php if ($story_image) : ?>
                        <img src="<?php echo esc_url($story_image); ?>" alt="<?php echo esc_attr($story_title); ?>" class="rounded-lg shadow-lg w-full h-auto">
                    <?php elseif (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('large', array('class' => 'rounded-lg shadow-lg w-full h-auto')); ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Mission & Vision Section
    $mission_title = get_post_meta(get_the_ID(), 'about_mission_title', true);
    $mission_content = get_post_meta(get_the_ID(), 'about_mission_content', true);
    $vision_title = get_post_meta(get_the_ID(), 'about_vision_title', true);
    $vision_content = get_post_meta(get_the_ID(), 'about_vision_content', true);
    
    if ($mission_title || $mission_content || $vision_title || $vision_content) :
    ?>
    <section class="about-mission-vision py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-2 gap-12">
                <?php if ($mission_title || $mission_content) : ?>
                <div class="mission-block bg-white dark:bg-gray-700 p-8 rounded-lg shadow-md">
                    <?php if ($mission_title) : ?>
                        <h3 class="text-2xl font-bold mb-4"><?php echo esc_html($mission_title); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ($mission_content) : ?>
                        <div class="prose dark:prose-invert max-w-none">
                            <?php echo wp_kses_post(wpautop($mission_content)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($vision_title || $vision_content) : ?>
                <div class="vision-block bg-white dark:bg-gray-700 p-8 rounded-lg shadow-md">
                    <?php if ($vision_title) : ?>
                        <h3 class="text-2xl font-bold mb-4"><?php echo esc_html($vision_title); ?></h3>
                    <?php endif; ?>
                    
                    <?php if ($vision_content) : ?>
                        <div class="prose dark:prose-invert max-w-none">
                            <?php echo wp_kses_post(wpautop($vision_content)); ?>
                        </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Team Section
    $team_title = get_post_meta(get_the_ID(), 'about_team_title', true);
    $team_subtitle = get_post_meta(get_the_ID(), 'about_team_subtitle', true);
    $team_members = get_post_meta(get_the_ID(), 'about_team_members', true);
    
    if ($team_title || $team_subtitle || $team_members) :
    ?>
    <section class="about-team py-16">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <?php if ($team_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($team_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($team_subtitle) : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($team_subtitle); ?></p>
                <?php endif; ?>
            </div>
            
            <?php
            // Check if we have team members from custom field
            if ($team_members && is_array($team_members)) :
            ?>
                <div class="team-grid grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php foreach ($team_members as $member) : ?>
                        <div class="team-member text-center">
                            <?php if (!empty($member['image'])) : ?>
                                <div class="member-image mb-4">
                                    <img src="<?php echo esc_url($member['image']); ?>" alt="<?php echo esc_attr($member['name']); ?>" class="rounded-full w-40 h-40 object-cover mx-auto">
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="member-name text-xl font-bold mb-1"><?php echo esc_html($member['name']); ?></h3>
                            
                            <?php if (!empty($member['position'])) : ?>
                                <p class="member-position text-primary-600 dark:text-primary-400 mb-3"><?php echo esc_html($member['position']); ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($member['bio'])) : ?>
                                <p class="member-bio text-gray-600 dark:text-gray-300 mb-4"><?php echo esc_html($member['bio']); ?></p>
                            <?php endif; ?>
                            
                            <?php if (!empty($member['social'])) : ?>
                                <div class="member-social flex justify-center space-x-3">
                                    <?php foreach ($member['social'] as $platform => $url) : ?>
                                        <a href="<?php echo esc_url($url); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                            <?php if ($platform === 'facebook') : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                                </svg>
                                            <?php elseif ($platform === 'twitter') : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                                </svg>
                                            <?php elseif ($platform === 'linkedin') : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                                                </svg>
                                            <?php elseif ($platform === 'instagram') : ?>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                                </svg>
                                            <?php endif; ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php
            // Otherwise, check for team members from custom post type
            else :
                $args = array(
                    'post_type' => 'aqualuxe_team',
                    'posts_per_page' => 8,
                );
                $team_query = new WP_Query($args);
                
                if ($team_query->have_posts()) :
            ?>
                <div class="team-grid grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    <?php while ($team_query->have_posts()) : $team_query->the_post(); 
                        $position = get_post_meta(get_the_ID(), 'aqualuxe_team_position', true);
                        $facebook = get_post_meta(get_the_ID(), 'aqualuxe_team_facebook', true);
                        $twitter = get_post_meta(get_the_ID(), 'aqualuxe_team_twitter', true);
                        $linkedin = get_post_meta(get_the_ID(), 'aqualuxe_team_linkedin', true);
                        $instagram = get_post_meta(get_the_ID(), 'aqualuxe_team_instagram', true);
                    ?>
                        <div class="team-member text-center">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="member-image mb-4">
                                    <?php the_post_thumbnail('thumbnail', array('class' => 'rounded-full w-40 h-40 object-cover mx-auto')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <h3 class="member-name text-xl font-bold mb-1"><?php the_title(); ?></h3>
                            
                            <?php if ($position) : ?>
                                <p class="member-position text-primary-600 dark:text-primary-400 mb-3"><?php echo esc_html($position); ?></p>
                            <?php endif; ?>
                            
                            <div class="member-bio text-gray-600 dark:text-gray-300 mb-4">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <div class="member-social flex justify-center space-x-3">
                                <?php if ($facebook) : ?>
                                    <a href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($twitter) : ?>
                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($linkedin) : ?>
                                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($instagram) : ?>
                                    <a href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener noreferrer" class="text-gray-500 hover:text-primary-600 dark:hover:text-primary-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php
                endif;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Testimonials Section
    $testimonials_title = get_post_meta(get_the_ID(), 'about_testimonials_title', true);
    $testimonials_subtitle = get_post_meta(get_the_ID(), 'about_testimonials_subtitle', true);
    
    if ($testimonials_title || $testimonials_subtitle) :
    ?>
    <section class="about-testimonials py-16 bg-gray-50 dark:bg-gray-800">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <?php if ($testimonials_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($testimonials_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($testimonials_subtitle) : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($testimonials_subtitle); ?></p>
                <?php endif; ?>
            </div>
            
            <?php
            $args = array(
                'post_type' => 'aqualuxe_testimonial',
                'posts_per_page' => 3,
            );
            $testimonials_query = new WP_Query($args);
            
            if ($testimonials_query->have_posts()) :
            ?>
                <div class="testimonials-grid grid md:grid-cols-3 gap-8">
                    <?php while ($testimonials_query->have_posts()) : $testimonials_query->the_post(); 
                        $rating = get_post_meta(get_the_ID(), 'aqualuxe_testimonial_rating', true);
                        $position = get_post_meta(get_the_ID(), 'aqualuxe_testimonial_position', true);
                    ?>
                        <div class="testimonial-item bg-white dark:bg-gray-700 p-6 rounded-lg shadow-md">
                            <div class="testimonial-content mb-4">
                                <svg class="h-8 w-8 text-primary-400 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z" />
                                </svg>
                                <div class="testimonial-text text-gray-600 dark:text-gray-300">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                            
                            <div class="testimonial-meta flex items-center">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="testimonial-avatar mr-4">
                                        <?php the_post_thumbnail('thumbnail', array('class' => 'w-12 h-12 rounded-full')); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="testimonial-info">
                                    <h4 class="testimonial-name font-bold"><?php the_title(); ?></h4>
                                    <?php if ($position) : ?>
                                        <p class="testimonial-position text-sm text-gray-500 dark:text-gray-400"><?php echo esc_html($position); ?></p>
                                    <?php endif; ?>
                                    
                                    <?php if ($rating) : ?>
                                        <div class="testimonial-rating flex text-yellow-400 mt-1">
                                            <?php for ($i = 1; $i <= 5; $i++) : ?>
                                                <?php if ($i <= $rating) : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                                    </svg>
                                                <?php else : ?>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                                    </svg>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // Partners Section
    $partners_title = get_post_meta(get_the_ID(), 'about_partners_title', true);
    $partners_subtitle = get_post_meta(get_the_ID(), 'about_partners_subtitle', true);
    $partners = get_post_meta(get_the_ID(), 'about_partners', true);
    
    if ($partners_title || $partners_subtitle || $partners) :
    ?>
    <section class="about-partners py-16">
        <div class="container mx-auto px-4">
            <div class="section-header text-center mb-12">
                <?php if ($partners_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($partners_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($partners_subtitle) : ?>
                    <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto"><?php echo esc_html($partners_subtitle); ?></p>
                <?php endif; ?>
            </div>
            
            <?php if ($partners && is_array($partners)) : ?>
                <div class="partners-grid grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-8 items-center">
                    <?php foreach ($partners as $partner) : ?>
                        <div class="partner-item flex justify-center">
                            <?php if (!empty($partner['url'])) : ?>
                                <a href="<?php echo esc_url($partner['url']); ?>" target="_blank" rel="noopener noreferrer">
                            <?php endif; ?>
                            
                            <?php if (!empty($partner['logo'])) : ?>
                                <img src="<?php echo esc_url($partner['logo']); ?>" alt="<?php echo esc_attr($partner['name']); ?>" class="max-h-16 w-auto grayscale hover:grayscale-0 transition-all">
                            <?php else : ?>
                                <span class="text-lg font-medium text-gray-600 dark:text-gray-300"><?php echo esc_html($partner['name']); ?></span>
                            <?php endif; ?>
                            
                            <?php if (!empty($partner['url'])) : ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php
    // CTA Section
    $cta_title = get_post_meta(get_the_ID(), 'about_cta_title', true);
    $cta_text = get_post_meta(get_the_ID(), 'about_cta_text', true);
    $cta_button_text = get_post_meta(get_the_ID(), 'about_cta_button_text', true);
    $cta_button_url = get_post_meta(get_the_ID(), 'about_cta_button_url', true);
    $cta_bg_image = get_post_meta(get_the_ID(), 'about_cta_bg_image', true);
    
    if ($cta_title || $cta_text) :
    ?>
    <section class="about-cta py-16 bg-cover bg-center relative" <?php if ($cta_bg_image) : ?>style="background-image: url('<?php echo esc_url($cta_bg_image); ?>');"<?php else : ?>style="background-color: #0f172a;"<?php endif; ?>>
        <div class="absolute inset-0 bg-primary-900 bg-opacity-80"></div>
        <div class="container mx-auto px-4 relative z-10">
            <div class="max-w-3xl mx-auto text-center text-white">
                <?php if ($cta_title) : ?>
                    <h2 class="text-3xl md:text-4xl font-bold mb-4"><?php echo esc_html($cta_title); ?></h2>
                <?php endif; ?>
                
                <?php if ($cta_text) : ?>
                    <p class="text-xl mb-8"><?php echo esc_html($cta_text); ?></p>
                <?php endif; ?>
                
                <?php if ($cta_button_text && $cta_button_url) : ?>
                    <a href="<?php echo esc_url($cta_button_url); ?>" class="btn-primary inline-block px-8 py-4 bg-white hover:bg-gray-100 text-primary-900 rounded-md transition-colors text-lg font-medium">
                        <?php echo esc_html($cta_button_text); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>
</main><!-- #main -->

<?php
get_footer();