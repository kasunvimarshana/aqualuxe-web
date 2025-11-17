<?php
/**
 * Template part for displaying author biography
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Get author info
$author_id = get_the_author_meta('ID');
$author_name = get_the_author_meta('display_name');
$author_description = get_the_author_meta('description');
$author_posts_url = get_author_posts_url($author_id);
$author_avatar = get_avatar($author_id, 96);

// Only display if we have an author description
if (empty($author_description)) {
    return;
}
?>

<div class="author-bio">
    <div class="author-bio-inner">
        <?php if ($author_avatar) : ?>
            <div class="author-avatar">
                <a href="<?php echo esc_url($author_posts_url); ?>">
                    <?php echo $author_avatar; ?>
                </a>
            </div>
        <?php endif; ?>

        <div class="author-content">
            <h3 class="author-title">
                <?php
                printf(
                    /* translators: %s: Author name */
                    esc_html__('About %s', 'aqualuxe'),
                    esc_html($author_name)
                );
                ?>
            </h3>

            <div class="author-description">
                <?php echo wpautop($author_description); ?>
            </div>

            <div class="author-links">
                <a href="<?php echo esc_url($author_posts_url); ?>" class="author-posts-link">
                    <?php
                    printf(
                        /* translators: %s: Author name */
                        esc_html__('View all posts by %s', 'aqualuxe'),
                        esc_html($author_name)
                    );
                    ?>
                    <span class="icon-arrow-right"></span>
                </a>

                <?php
                // Display author social links if available
                $social_links = array(
                    'twitter' => get_the_author_meta('twitter'),
                    'facebook' => get_the_author_meta('facebook'),
                    'instagram' => get_the_author_meta('instagram'),
                    'linkedin' => get_the_author_meta('linkedin'),
                    'website' => get_the_author_meta('url'),
                );

                $has_social = false;
                foreach ($social_links as $link) {
                    if (!empty($link)) {
                        $has_social = true;
                        break;
                    }
                }

                if ($has_social) :
                ?>
                    <div class="author-social-links">
                        <?php if (!empty($social_links['website'])) : ?>
                            <a href="<?php echo esc_url($social_links['website']); ?>" class="author-website" target="_blank" rel="noopener noreferrer">
                                <span class="icon-globe"></span>
                                <span class="screen-reader-text"><?php esc_html_e('Website', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($social_links['twitter'])) : ?>
                            <a href="<?php echo esc_url($social_links['twitter']); ?>" class="author-twitter" target="_blank" rel="noopener noreferrer">
                                <span class="icon-twitter"></span>
                                <span class="screen-reader-text"><?php esc_html_e('Twitter', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($social_links['facebook'])) : ?>
                            <a href="<?php echo esc_url($social_links['facebook']); ?>" class="author-facebook" target="_blank" rel="noopener noreferrer">
                                <span class="icon-facebook"></span>
                                <span class="screen-reader-text"><?php esc_html_e('Facebook', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($social_links['instagram'])) : ?>
                            <a href="<?php echo esc_url($social_links['instagram']); ?>" class="author-instagram" target="_blank" rel="noopener noreferrer">
                                <span class="icon-instagram"></span>
                                <span class="screen-reader-text"><?php esc_html_e('Instagram', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty($social_links['linkedin'])) : ?>
                            <a href="<?php echo esc_url($social_links['linkedin']); ?>" class="author-linkedin" target="_blank" rel="noopener noreferrer">
                                <span class="icon-linkedin"></span>
                                <span class="screen-reader-text"><?php esc_html_e('LinkedIn', 'aqualuxe'); ?></span>
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>