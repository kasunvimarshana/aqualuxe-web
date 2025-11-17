<?php
/**
 * Template Name: About Page
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<div class="container">
    <div class="row no-sidebar">
        <main id="primary" class="site-main">
            <?php
            while (have_posts()) :
                the_post();
                ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class('aqualuxe-about-page'); ?>>
                    <header class="aqualuxe-about-header">
                        <?php the_title('<h1 class="aqualuxe-about-title">', '</h1>'); ?>
                        
                        <?php if (has_excerpt()) : ?>
                            <div class="aqualuxe-about-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                        <?php endif; ?>
                    </header>

                    <?php if (has_post_thumbnail()) : ?>
                        <div class="aqualuxe-about-thumbnail">
                            <?php the_post_thumbnail('full'); ?>
                        </div>
                    <?php endif; ?>

                    <div class="aqualuxe-about-content">
                        <?php the_content(); ?>
                    </div>
                </article>
                
                <?php
                // Get ACF fields if available
                if (function_exists('get_field')) :
                    // Mission & Values section
                    $mission = get_field('mission');
                    $values = get_field('values');
                    
                    if ($mission || $values) :
                        ?>
                        <section class="aqualuxe-about-mission-values">
                            <div class="aqualuxe-about-section-inner">
                                <?php if ($mission) : ?>
                                    <div class="aqualuxe-about-mission">
                                        <h2><?php esc_html_e('Our Mission', 'aqualuxe'); ?></h2>
                                        <div class="aqualuxe-about-mission-content">
                                            <?php echo wp_kses_post($mission); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if ($values) : ?>
                                    <div class="aqualuxe-about-values">
                                        <h2><?php esc_html_e('Our Values', 'aqualuxe'); ?></h2>
                                        <div class="aqualuxe-about-values-content">
                                            <?php echo wp_kses_post($values); ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </section>
                        <?php
                    endif;
                    
                    // Team section
                    $team_title = get_field('team_title');
                    $team_description = get_field('team_description');
                    $team_members = get_field('team_members');
                    
                    if ($team_members) :
                        ?>
                        <section class="aqualuxe-about-team">
                            <div class="aqualuxe-about-section-inner">
                                <div class="aqualuxe-about-team-header">
                                    <h2><?php echo esc_html($team_title ? $team_title : __('Our Team', 'aqualuxe')); ?></h2>
                                    
                                    <?php if ($team_description) : ?>
                                        <div class="aqualuxe-about-team-description">
                                            <?php echo wp_kses_post($team_description); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="aqualuxe-about-team-members">
                                    <?php foreach ($team_members as $member) : ?>
                                        <div class="aqualuxe-about-team-member">
                                            <?php if (!empty($member['photo'])) : ?>
                                                <div class="aqualuxe-about-team-member-photo">
                                                    <img src="<?php echo esc_url($member['photo']['url']); ?>" alt="<?php echo esc_attr($member['name']); ?>">
                                                </div>
                                            <?php endif; ?>
                                            
                                            <div class="aqualuxe-about-team-member-info">
                                                <h3 class="aqualuxe-about-team-member-name"><?php echo esc_html($member['name']); ?></h3>
                                                
                                                <?php if (!empty($member['position'])) : ?>
                                                    <div class="aqualuxe-about-team-member-position">
                                                        <?php echo esc_html($member['position']); ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($member['bio'])) : ?>
                                                    <div class="aqualuxe-about-team-member-bio">
                                                        <?php echo wp_kses_post($member['bio']); ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($member['social_links'])) : ?>
                                                    <div class="aqualuxe-about-team-member-social">
                                                        <?php foreach ($member['social_links'] as $social) : ?>
                                                            <a href="<?php echo esc_url($social['url']); ?>" target="_blank" rel="noopener noreferrer" class="aqualuxe-about-team-member-social-link">
                                                                <?php echo esc_html($social['platform']); ?>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                        <?php
                    endif;
                    
                    // History section
                    $history_title = get_field('history_title');
                    $history_description = get_field('history_description');
                    $history_timeline = get_field('history_timeline');
                    
                    if ($history_timeline) :
                        ?>
                        <section class="aqualuxe-about-history">
                            <div class="aqualuxe-about-section-inner">
                                <div class="aqualuxe-about-history-header">
                                    <h2><?php echo esc_html($history_title ? $history_title : __('Our History', 'aqualuxe')); ?></h2>
                                    
                                    <?php if ($history_description) : ?>
                                        <div class="aqualuxe-about-history-description">
                                            <?php echo wp_kses_post($history_description); ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="aqualuxe-about-history-timeline">
                                    <?php foreach ($history_timeline as $item) : ?>
                                        <div class="aqualuxe-about-history-item">
                                            <div class="aqualuxe-about-history-item-year">
                                                <?php echo esc_html($item['year']); ?>
                                            </div>
                                            
                                            <div class="aqualuxe-about-history-item-content">
                                                <h3 class="aqualuxe-about-history-item-title"><?php echo esc_html($item['title']); ?></h3>
                                                
                                                <?php if (!empty($item['description'])) : ?>
                                                    <div class="aqualuxe-about-history-item-description">
                                                        <?php echo wp_kses_post($item['description']); ?>
                                                    </div>
                                                <?php endif; ?>
                                                
                                                <?php if (!empty($item['image'])) : ?>
                                                    <div class="aqualuxe-about-history-item-image">
                                                        <img src="<?php echo esc_url($item['image']['url']); ?>" alt="<?php echo esc_attr($item['title']); ?>">
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </section>
                        <?php
                    endif;
                endif;
                
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
            endwhile; // End of the loop.
            ?>
        </main><!-- #main -->
    </div>
</div>

<?php
get_footer();