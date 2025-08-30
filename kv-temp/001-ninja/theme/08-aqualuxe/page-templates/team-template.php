<?php
/**
 * Template Name: Team Page
 *
 * This is the template for displaying the Team page.
 *
 * @package AquaLuxe
 */

get_header();
?>

<main id="primary" class="site-main team-page">
    <?php
    // Hero Section
    $hero_image = get_post_meta(get_the_ID(), 'aqualuxe_team_hero_image', true);
    $hero_title = get_post_meta(get_the_ID(), 'aqualuxe_team_hero_title', true) ?: get_the_title();
    $hero_subtitle = get_post_meta(get_the_ID(), 'aqualuxe_team_hero_subtitle', true);
    
    if (empty($hero_image)) {
        $hero_image = get_the_post_thumbnail_url(get_the_ID(), 'full');
    }
    ?>
    
    <section class="team-hero" <?php if ($hero_image) : ?>style="background-image: url('<?php echo esc_url($hero_image); ?>');"<?php endif; ?>>
        <div class="container">
            <div class="hero-content">
                <h1 class="hero-title"><?php echo esc_html($hero_title); ?></h1>
                <?php if ($hero_subtitle) : ?>
                    <p class="hero-subtitle"><?php echo esc_html($hero_subtitle); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <div class="container">
        <?php if (function_exists('aqualuxe_breadcrumbs')) : ?>
            <?php aqualuxe_breadcrumbs(); ?>
        <?php endif; ?>

        <div class="team-content">
            <div class="team-main-content">
                <?php
                while (have_posts()) :
                    the_post();
                    ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        <div class="entry-content">
                            <?php the_content(); ?>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
        </div>
    </div>

    <?php
    // Leadership Team Section
    $leadership_title = get_post_meta(get_the_ID(), 'aqualuxe_team_leadership_title', true) ?: __('Our Leadership', 'aqualuxe');
    $leadership_description = get_post_meta(get_the_ID(), 'aqualuxe_team_leadership_description', true);
    
    // Check if Team Members CPT exists
    if (post_type_exists('team_members')) :
    ?>
    <section class="team-leadership">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html($leadership_title); ?></h2>
                <?php if ($leadership_description) : ?>
                    <p class="section-description"><?php echo esc_html($leadership_description); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="leadership-team">
                <?php
                $args = array(
                    'post_type' => 'team_members',
                    'posts_per_page' => -1,
                    'orderby' => 'menu_order',
                    'order' => 'ASC',
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'team_category',
                            'field'    => 'slug',
                            'terms'    => 'leadership',
                        ),
                    ),
                );
                
                $leadership_query = new WP_Query($args);
                
                if ($leadership_query->have_posts()) :
                    echo '<div class="leadership-grid">';
                    
                    while ($leadership_query->have_posts()) :
                        $leadership_query->the_post();
                        
                        // Get team member meta
                        $position = get_post_meta(get_the_ID(), 'aqualuxe_team_position', true);
                        $email = get_post_meta(get_the_ID(), 'aqualuxe_team_email', true);
                        $linkedin = get_post_meta(get_the_ID(), 'aqualuxe_team_linkedin', true);
                        $twitter = get_post_meta(get_the_ID(), 'aqualuxe_team_twitter', true);
                        $bio = get_post_meta(get_the_ID(), 'aqualuxe_team_bio', true);
                        ?>
                        <div class="leadership-member">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="member-image">
                                    <?php the_post_thumbnail('aqualuxe-card'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="member-info">
                                <h3 class="member-name"><?php the_title(); ?></h3>
                                <?php if ($position) : ?>
                                    <p class="member-position"><?php echo esc_html($position); ?></p>
                                <?php endif; ?>
                                
                                <div class="member-bio">
                                    <?php 
                                    if ($bio) {
                                        echo wp_kses_post(wpautop($bio));
                                    } else {
                                        the_content();
                                    }
                                    ?>
                                </div>
                                
                                <div class="member-social">
                                    <?php if ($email) : ?>
                                        <a href="mailto:<?php echo esc_attr($email); ?>" class="social-link email" aria-label="<?php esc_attr_e('Email', 'aqualuxe'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($linkedin) : ?>
                                        <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" class="social-link linkedin" aria-label="<?php esc_attr_e('LinkedIn', 'aqualuxe'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($twitter) : ?>
                                        <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="social-link twitter" aria-label="<?php esc_attr_e('Twitter', 'aqualuxe'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    
                    echo '</div>';
                    
                    wp_reset_postdata();
                else :
                    // Try without the taxonomy filter
                    $args = array(
                        'post_type' => 'team_members',
                        'posts_per_page' => 4,
                        'orderby' => 'menu_order',
                        'order' => 'ASC',
                    );
                    
                    $leadership_query = new WP_Query($args);
                    
                    if ($leadership_query->have_posts()) :
                        echo '<div class="leadership-grid">';
                        
                        while ($leadership_query->have_posts()) :
                            $leadership_query->the_post();
                            
                            // Get team member meta
                            $position = get_post_meta(get_the_ID(), 'aqualuxe_team_position', true);
                            $email = get_post_meta(get_the_ID(), 'aqualuxe_team_email', true);
                            $linkedin = get_post_meta(get_the_ID(), 'aqualuxe_team_linkedin', true);
                            $twitter = get_post_meta(get_the_ID(), 'aqualuxe_team_twitter', true);
                            $bio = get_post_meta(get_the_ID(), 'aqualuxe_team_bio', true);
                            ?>
                            <div class="leadership-member">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="member-image">
                                        <?php the_post_thumbnail('aqualuxe-card'); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <div class="member-info">
                                    <h3 class="member-name"><?php the_title(); ?></h3>
                                    <?php if ($position) : ?>
                                        <p class="member-position"><?php echo esc_html($position); ?></p>
                                    <?php endif; ?>
                                    
                                    <div class="member-bio">
                                        <?php 
                                        if ($bio) {
                                            echo wp_kses_post(wpautop($bio));
                                        } else {
                                            the_content();
                                        }
                                        ?>
                                    </div>
                                    
                                    <div class="member-social">
                                        <?php if ($email) : ?>
                                            <a href="mailto:<?php echo esc_attr($email); ?>" class="social-link email" aria-label="<?php esc_attr_e('Email', 'aqualuxe'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($linkedin) : ?>
                                            <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" class="social-link linkedin" aria-label="<?php esc_attr_e('LinkedIn', 'aqualuxe'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                                            </a>
                                        <?php endif; ?>
                                        
                                        <?php if ($twitter) : ?>
                                            <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="social-link twitter" aria-label="<?php esc_attr_e('Twitter', 'aqualuxe'); ?>">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        endwhile;
                        
                        echo '</div>';
                        
                        wp_reset_postdata();
                    else :
                        echo '<p class="no-team-members">' . esc_html__('No team members found.', 'aqualuxe') . '</p>';
                    endif;
                endif;
                ?>
            </div>
        </div>
    </section>
    
    <?php
    // Team Members Section
    $team_title = get_post_meta(get_the_ID(), 'aqualuxe_team_members_title', true) ?: __('Our Team', 'aqualuxe');
    $team_description = get_post_meta(get_the_ID(), 'aqualuxe_team_members_description', true);
    
    // Check if we have team members that are not in the leadership category
    $args = array(
        'post_type' => 'team_members',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
        'tax_query' => array(
            array(
                'taxonomy' => 'team_category',
                'field'    => 'slug',
                'terms'    => 'leadership',
                'operator' => 'NOT IN',
            ),
        ),
    );
    
    $team_query = new WP_Query($args);
    
    if ($team_query->have_posts()) :
    ?>
    <section class="team-members">
        <div class="container">
            <div class="section-header">
                <h2 class="section-title"><?php echo esc_html($team_title); ?></h2>
                <?php if ($team_description) : ?>
                    <p class="section-description"><?php echo esc_html($team_description); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="team-grid">
                <?php
                while ($team_query->have_posts()) :
                    $team_query->the_post();
                    
                    // Get team member meta
                    $position = get_post_meta(get_the_ID(), 'aqualuxe_team_position', true);
                    $email = get_post_meta(get_the_ID(), 'aqualuxe_team_email', true);
                    $linkedin = get_post_meta(get_the_ID(), 'aqualuxe_team_linkedin', true);
                    $twitter = get_post_meta(get_the_ID(), 'aqualuxe_team_twitter', true);
                    ?>
                    <div class="team-member">
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="member-image">
                                <?php the_post_thumbnail('aqualuxe-card'); ?>
                            </div>
                        <?php endif; ?>
                        
                        <div class="member-info">
                            <h3 class="member-name"><?php the_title(); ?></h3>
                            <?php if ($position) : ?>
                                <p class="member-position"><?php echo esc_html($position); ?></p>
                            <?php endif; ?>
                            
                            <div class="member-social">
                                <?php if ($email) : ?>
                                    <a href="mailto:<?php echo esc_attr($email); ?>" class="social-link email" aria-label="<?php esc_attr_e('Email', 'aqualuxe'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($linkedin) : ?>
                                    <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" class="social-link linkedin" aria-label="<?php esc_attr_e('LinkedIn', 'aqualuxe'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($twitter) : ?>
                                    <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="social-link twitter" aria-label="<?php esc_attr_e('Twitter', 'aqualuxe'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>
    <?php 
    elseif (!$leadership_query->have_posts()) :
        // If no leadership team and no regular team members, show all team members
        $args = array(
            'post_type' => 'team_members',
            'posts_per_page' => -1,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        );
        
        $all_team_query = new WP_Query($args);
        
        if ($all_team_query->have_posts()) :
        ?>
        <section class="team-members">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title"><?php echo esc_html($team_title); ?></h2>
                    <?php if ($team_description) : ?>
                        <p class="section-description"><?php echo esc_html($team_description); ?></p>
                    <?php endif; ?>
                </div>
                
                <div class="team-grid">
                    <?php
                    while ($all_team_query->have_posts()) :
                        $all_team_query->the_post();
                        
                        // Get team member meta
                        $position = get_post_meta(get_the_ID(), 'aqualuxe_team_position', true);
                        $email = get_post_meta(get_the_ID(), 'aqualuxe_team_email', true);
                        $linkedin = get_post_meta(get_the_ID(), 'aqualuxe_team_linkedin', true);
                        $twitter = get_post_meta(get_the_ID(), 'aqualuxe_team_twitter', true);
                        ?>
                        <div class="team-member">
                            <?php if (has_post_thumbnail()) : ?>
                                <div class="member-image">
                                    <?php the_post_thumbnail('aqualuxe-card'); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="member-info">
                                <h3 class="member-name"><?php the_title(); ?></h3>
                                <?php if ($position) : ?>
                                    <p class="member-position"><?php echo esc_html($position); ?></p>
                                <?php endif; ?>
                                
                                <div class="member-social">
                                    <?php if ($email) : ?>
                                        <a href="mailto:<?php echo esc_attr($email); ?>" class="social-link email" aria-label="<?php esc_attr_e('Email', 'aqualuxe'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($linkedin) : ?>
                                        <a href="<?php echo esc_url($linkedin); ?>" target="_blank" rel="noopener noreferrer" class="social-link linkedin" aria-label="<?php esc_attr_e('LinkedIn', 'aqualuxe'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"></path><rect x="2" y="9" width="4" height="12"></rect><circle cx="4" cy="4" r="2"></circle></svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php if ($twitter) : ?>
                                        <a href="<?php echo esc_url($twitter); ?>" target="_blank" rel="noopener noreferrer" class="social-link twitter" aria-label="<?php esc_attr_e('Twitter', 'aqualuxe'); ?>">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"></path></svg>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    endwhile;
                    
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
        </section>
        <?php
        endif;
    endif;
    endif;
    ?>

    <?php
    // Join Our Team Section
    $join_title = get_post_meta(get_the_ID(), 'aqualuxe_team_join_title', true) ?: __('Join Our Team', 'aqualuxe');
    $join_description = get_post_meta(get_the_ID(), 'aqualuxe_team_join_description', true) ?: __('We\'re always looking for talented individuals to join our team. Check out our current openings and apply today!', 'aqualuxe');
    $join_button_text = get_post_meta(get_the_ID(), 'aqualuxe_team_join_button_text', true) ?: __('View Open Positions', 'aqualuxe');
    $join_button_url = get_post_meta(get_the_ID(), 'aqualuxe_team_join_button_url', true) ?: '#';
    $join_background = get_post_meta(get_the_ID(), 'aqualuxe_team_join_background', true);
    ?>
    
    <section class="team-join" <?php if ($join_background) : ?>style="background-image: url('<?php echo esc_url($join_background); ?>');"<?php endif; ?>>
        <div class="container">
            <div class="join-content">
                <h2 class="join-title"><?php echo esc_html($join_title); ?></h2>
                <p class="join-description"><?php echo esc_html($join_description); ?></p>
                <a href="<?php echo esc_url($join_button_url); ?>" class="btn btn-accent btn-large"><?php echo esc_html($join_button_text); ?></a>
            </div>
        </div>
    </section>
</main>

<?php
get_footer();