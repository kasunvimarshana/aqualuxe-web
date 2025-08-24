<?php
/**
 * About Page Team Section
 * @package AquaLuxe
 */
?>
<section class="about-team">
    <div class="container">
        <h2>Meet the Team</h2>
        <div class="team-grid">
            <?php
            $team_query = new WP_Query([
                'post_type' => 'team_member',
                'posts_per_page' => 8,
                'orderby' => 'menu_order',
                'order' => 'ASC',
            ]);
            if ( $team_query->have_posts() ) :
                while ( $team_query->have_posts() ) : $team_query->the_post();
            ?>
                <div class="team-member">
                    <?php if ( has_post_thumbnail() ) : ?>
                        <div class="team-photo"><?php the_post_thumbnail('medium'); ?></div>
                    <?php endif; ?>
                    <h3 class="team-name"><?php the_title(); ?></h3>
                    <p class="team-role"><?php echo esc_html( get_post_meta(get_the_ID(), 'role', true) ); ?></p>
                    <div class="team-bio"><?php the_excerpt(); ?></div>
                </div>
            <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>
