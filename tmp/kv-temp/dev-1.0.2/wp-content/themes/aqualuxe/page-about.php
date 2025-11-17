<?php

/**
 * Template Name: About Us
 *
 * @package aqualuxe
 */

get_header(); ?>

<div class="aqualuxe-page about-page">
    <div class="container">
        <div class="page-header">
            <h1 class="page-title"><?php the_title(); ?></h1>
        </div>

        <div class="about-content">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                    <div class="about-text">
                        <?php the_content(); ?>
                    </div>
            <?php endwhile;
            endif; ?>

            <!-- Our Story -->
            <section class="our-story">
                <h2><?php esc_html_e('Our Story', 'aqualuxe'); ?></h2>
                <div class="story-content">
                    <div class="story-text">
                        <p><?php echo esc_html(get_theme_mod('aqualuxe_story_text', 'AquaLuxe began with a simple passion for the beauty of ornamental fish. What started as a small hobby has grown into a premier breeding facility dedicated to producing the highest quality specimens for enthusiasts and collectors around the world.')); ?></p>
                        <p><?php echo esc_html(get_theme_mod('aqualuxe_story_text2', 'Our team of expert breeders combines years of experience with a commitment to innovation, ensuring that every fish we produce meets the highest standards of health, color, and form.')); ?></p>
                    </div>
                    <div class="story-image">
                        <?php
                        $story_image = get_theme_mod('aqualuxe_story_image');
                        if ($story_image) :
                            echo wp_get_attachment_image($story_image, 'large', false, array('class' => 'story-img'));
                        else :
                            echo '<img src="' . esc_url(get_stylesheet_directory_uri()) . '/assets/images/our-story.jpg" alt="' . esc_attr__('Our Story', 'aqualuxe') . '" class="story-img">';
                        endif;
                        ?>
                    </div>
                </div>
            </section>

            <!-- Our Mission -->
            <section class="our-mission">
                <h2><?php esc_html_e('Our Mission', 'aqualuxe'); ?></h2>
                <div class="mission-content">
                    <div class="mission-image">
                        <?php
                        $mission_image = get_theme_mod('aqualuxe_mission_image');
                        if ($mission_image) :
                            echo wp_get_attachment_image($mission_image, 'large', false, array('class' => 'mission-img'));
                        else :
                            echo '<img src="' . esc_url(get_stylesheet_directory_uri()) . '/assets/images/our-mission.jpg" alt="' . esc_attr__('Our Mission', 'aqualuxe') . '" class="mission-img">';
                        endif;
                        ?>
                    </div>
                    <div class="mission-text">
                        <p><?php echo esc_html(get_theme_mod('aqualuxe_mission_text', 'At AquaLuxe, our mission is to bring the beauty and wonder of ornamental fish to enthusiasts worldwide. We are committed to sustainable breeding practices, exceptional fish health, and outstanding customer service.')); ?></p>
                        <div class="mission-values">
                            <div class="value">
                                <h3><?php esc_html_e('Quality', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('We never compromise on the health and quality of our fish', 'aqualuxe'); ?></p>
                            </div>
                            <div class="value">
                                <h3><?php esc_html_e('Sustainability', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('We practice responsible breeding methods that protect natural habitats', 'aqualuxe'); ?></p>
                            </div>
                            <div class="value">
                                <h3><?php esc_html_e('Innovation', 'aqualuxe'); ?></h3>
                                <p><?php esc_html_e('We continuously improve our breeding techniques and fish varieties', 'aqualuxe'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Our Team -->
            <section class="our-team">
                <h2><?php esc_html_e('Meet Our Team', 'aqualuxe'); ?></h2>
                <p class="section-subtitle"><?php esc_html_e('The passionate experts behind AquaLuxe', 'aqualuxe'); ?></p>

                <div class="team-grid">
                    <?php
                    $team_members = get_theme_mod('aqualuxe_team_members', json_encode(array(
                        array(
                            'name' => 'Dr. Richard Blue',
                            'position' => 'Chief Breeder',
                            'bio' => 'With over 20 years of experience in ornamental fish breeding, Dr. Blue leads our breeding programs with expertise and passion.',
                            'image' => ''
                        ),
                        array(
                            'name' => 'Sarah Waters',
                            'position' => 'Aquatic Specialist',
                            'bio' => 'Sarah ensures the health and wellbeing of all our fish through her extensive knowledge of aquatic ecosystems.',
                            'image' => ''
                        ),
                        array(
                            'name' => 'Michael Coral',
                            'position' => 'Genetics Expert',
                            'bio' => 'Michael specializes in developing new color variations and improving the genetic health of our fish populations.',
                            'image' => ''
                        )
                    )));

                    $team_data = json_decode($team_members, true);

                    if ($team_data) :
                        foreach ($team_data as $member) :
                    ?>
                            <div class="team-member">
                                <div class="member-image">
                                    <?php
                                    if (!empty($member['image'])) :
                                        echo wp_get_attachment_image($member['image'], 'medium', false, array('class' => 'member-img'));
                                    else :
                                        echo '<img src="' . esc_url(get_stylesheet_directory_uri()) . '/assets/images/team-placeholder.jpg" alt="' . esc_attr($member['name']) . '" class="member-img">';
                                    endif;
                                    ?>
                                </div>
                                <div class="member-info">
                                    <h3><?php echo esc_html($member['name']); ?></h3>
                                    <p class="member-position"><?php echo esc_html($member['position']); ?></p>
                                    <p class="member-bio"><?php echo esc_html($member['bio']); ?></p>
                                </div>
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </section>

            <!-- Our Facility -->
            <section class="our-facility">
                <h2><?php esc_html_e('Our Facility', 'aqualuxe'); ?></h2>
                <div class="facility-gallery">
                    <?php
                    $facility_images = get_theme_mod('aqualuxe_facility_images', json_encode(array(
                        array('image' => '', 'caption' => 'Breeding Tanks'),
                        array('image' => '', 'caption' => 'Quarantine Area'),
                        array('image' => '', 'caption' => 'Packing Station'),
                        array('image' => '', 'caption' => 'Research Lab')
                    )));

                    $facility_data = json_decode($facility_images, true);

                    if ($facility_data) :
                        foreach ($facility_data as $image) :
                    ?>
                            <div class="facility-item">
                                <?php
                                if (!empty($image['image'])) :
                                    echo wp_get_attachment_image($image['image'], 'large', false, array('class' => 'facility-img'));
                                else :
                                    echo '<img src="' . esc_url(get_stylesheet_directory_uri()) . '/assets/images/facility-' . sanitize_title($image['caption']) . '.jpg" alt="' . esc_attr($image['caption']) . '" class="facility-img">';
                                endif;
                                ?>
                                <div class="facility-caption"><?php echo esc_html($image['caption']); ?></div>
                            </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
                </div>
            </section>
        </div>
    </div>
</div>

<?php get_footer(); ?>