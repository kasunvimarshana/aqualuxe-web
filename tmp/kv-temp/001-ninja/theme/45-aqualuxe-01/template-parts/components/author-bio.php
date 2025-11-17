<?php
/**
 * Template part for displaying author bio on single posts
 *
 * @package AquaLuxe
 */

// Get author data
$author_id = get_the_author_meta('ID');
$author_name = get_the_author_meta('display_name');
$author_description = get_the_author_meta('description');
$author_posts_url = get_author_posts_url($author_id);
$author_website = get_the_author_meta('user_url');
$author_email = get_the_author_meta('user_email');
$author_facebook = get_the_author_meta('facebook');
$author_twitter = get_the_author_meta('twitter');
$author_instagram = get_the_author_meta('instagram');
$author_linkedin = get_the_author_meta('linkedin');

// Check if we have an author description
if (empty($author_description)) {
    return;
}

// Get author bio options from theme customizer
$show_author_bio = get_theme_mod('aqualuxe_show_author_bio', true);
$show_author_social = get_theme_mod('aqualuxe_show_author_social', true);
$show_author_posts_link = get_theme_mod('aqualuxe_show_author_posts_link', true);

// Check if author bio should be displayed
if (!$show_author_bio) {
    return;
}
?>

<div class="author-bio">
    <div class="author-bio-inner">
        <div class="author-avatar">
            <?php echo get_avatar($author_id, 100, '', $author_name, array('class' => 'img-fluid rounded-circle')); ?>
        </div>
        
        <div class="author-content">
            <h3 class="author-name">
                <?php
                if ($show_author_posts_link) {
                    printf('<a href="%s">%s</a>', esc_url($author_posts_url), esc_html($author_name));
                } else {
                    echo esc_html($author_name);
                }
                ?>
            </h3>
            
            <div class="author-description">
                <?php echo wpautop($author_description); ?>
            </div>
            
            <?php if ($show_author_social) : ?>
                <div class="author-social">
                    <?php if (!empty($author_website)) : ?>
                        <a href="<?php echo esc_url($author_website); ?>" target="_blank" rel="noopener noreferrer" class="author-website">
                            <i class="fas fa-globe"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($author_email)) : ?>
                        <a href="mailto:<?php echo esc_attr($author_email); ?>" class="author-email">
                            <i class="fas fa-envelope"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($author_facebook)) : ?>
                        <a href="<?php echo esc_url($author_facebook); ?>" target="_blank" rel="noopener noreferrer" class="author-facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($author_twitter)) : ?>
                        <a href="<?php echo esc_url($author_twitter); ?>" target="_blank" rel="noopener noreferrer" class="author-twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($author_instagram)) : ?>
                        <a href="<?php echo esc_url($author_instagram); ?>" target="_blank" rel="noopener noreferrer" class="author-instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if (!empty($author_linkedin)) : ?>
                        <a href="<?php echo esc_url($author_linkedin); ?>" target="_blank" rel="noopener noreferrer" class="author-linkedin">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($show_author_posts_link) : ?>
                <div class="author-posts-link">
                    <a href="<?php echo esc_url($author_posts_url); ?>" class="btn btn-outline-primary btn-sm">
                        <?php
                        /* translators: %s: author name */
                        printf(esc_html__('View all posts by %s', 'aqualuxe'), esc_html($author_name));
                        ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>