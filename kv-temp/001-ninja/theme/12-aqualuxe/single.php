<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php
    // Page header
    if (get_theme_mod('aqualuxe_blog_header_enable', true)) :
        $page_header_bg = get_theme_mod('aqualuxe_blog_header_bg', '');
    ?>
        <section class="page-header-wrapper" <?php if (!empty($page_header_bg)) : ?>style="background-image: url('<?php echo esc_url($page_header_bg); ?>');"<?php endif; ?>>
            <div class="container">
                <div class="page-header text-center">
                    <h1 class="page-title"><?php single_post_title(); ?></h1>
                    
                    <?php
                    if (function_exists('aqualuxe_breadcrumbs') && get_theme_mod('aqualuxe_breadcrumbs_enable', true)) {
                        aqualuxe_breadcrumbs();
                    }
                    ?>
                </div>
            </div>
        </section>
    <?php endif; ?>

    <section class="blog-content-section section">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <?php
                    while (have_posts()) :
                        the_post();
                    ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class('blog-single'); ?>>
                            <?php if (has_post_thumbnail() && get_theme_mod('aqualuxe_blog_featured_image', true)) : ?>
                                <div class="post-thumbnail" data-aos="fade-up">
                                    <?php the_post_thumbnail('full', array('class' => 'img-fluid')); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="post-meta" data-aos="fade-up">
                                <div class="post-meta-item post-date">
                                    <i class="far fa-calendar-alt"></i>
                                    <?php echo get_the_date(); ?>
                                </div>
                                
                                <div class="post-meta-item post-author">
                                    <i class="far fa-user"></i>
                                    <?php the_author_posts_link(); ?>
                                </div>
                                
                                <?php if (has_category()) : ?>
                                    <div class="post-meta-item post-categories">
                                        <i class="far fa-folder-open"></i>
                                        <?php the_category(', '); ?>
                                    </div>
                                <?php endif; ?>
                                
                                <?php if (get_comments_number() > 0) : ?>
                                    <div class="post-meta-item post-comments">
                                        <i class="far fa-comments"></i>
                                        <?php comments_popup_link(); ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="post-content" data-aos="fade-up">
                                <?php
                                the_content();
                                
                                wp_link_pages(
                                    array(
                                        'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                                        'after'  => '</div>',
                                    )
                                );
                                ?>
                            </div>
                            
                            <?php if (has_tag() && get_theme_mod('aqualuxe_blog_tags', true)) : ?>
                                <div class="post-tags" data-aos="fade-up">
                                    <span class="tags-title"><?php esc_html_e('Tags:', 'aqualuxe'); ?></span>
                                    <?php the_tags('', ', ', ''); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Social sharing
                            if (get_theme_mod('aqualuxe_blog_social_sharing', true)) :
                                $share_url = urlencode(get_permalink());
                                $share_title = urlencode(get_the_title());
                                $share_image = has_post_thumbnail() ? urlencode(get_the_post_thumbnail_url(get_the_ID(), 'large')) : '';
                            ?>
                                <div class="post-social-sharing" data-aos="fade-up">
                                    <span class="sharing-title"><?php esc_html_e('Share This Post:', 'aqualuxe'); ?></span>
                                    <div class="sharing-buttons">
                                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $share_url; ?>" target="_blank" class="facebook" title="<?php esc_attr_e('Share on Facebook', 'aqualuxe'); ?>"><i class="fab fa-facebook-f"></i></a>
                                        <a href="https://twitter.com/intent/tweet?url=<?php echo $share_url; ?>&text=<?php echo $share_title; ?>" target="_blank" class="twitter" title="<?php esc_attr_e('Share on Twitter', 'aqualuxe'); ?>"><i class="fab fa-twitter"></i></a>
                                        <a href="https://pinterest.com/pin/create/button/?url=<?php echo $share_url; ?>&media=<?php echo $share_image; ?>&description=<?php echo $share_title; ?>" target="_blank" class="pinterest" title="<?php esc_attr_e('Pin on Pinterest', 'aqualuxe'); ?>"><i class="fab fa-pinterest-p"></i></a>
                                        <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php echo $share_url; ?>&title=<?php echo $share_title; ?>" target="_blank" class="linkedin" title="<?php esc_attr_e('Share on LinkedIn', 'aqualuxe'); ?>"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="mailto:?subject=<?php echo $share_title; ?>&body=<?php echo $share_url; ?>" class="email" title="<?php esc_attr_e('Share via Email', 'aqualuxe'); ?>"><i class="fas fa-envelope"></i></a>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Author bio
                            if (get_theme_mod('aqualuxe_blog_author_bio', true) && get_the_author_meta('description')) :
                            ?>
                                <div class="post-author-bio" data-aos="fade-up">
                                    <div class="author-avatar">
                                        <?php echo get_avatar(get_the_author_meta('ID'), 100); ?>
                                    </div>
                                    <div class="author-content">
                                        <h4 class="author-name"><?php the_author(); ?></h4>
                                        <div class="author-description">
                                            <?php echo wpautop(get_the_author_meta('description')); ?>
                                        </div>
                                        <div class="author-links">
                                            <?php
                                            $author_website = get_the_author_meta('url');
                                            if (!empty($author_website)) :
                                            ?>
                                                <a href="<?php echo esc_url($author_website); ?>" target="_blank" class="author-website">
                                                    <i class="fas fa-globe"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php
                                            $author_email = get_the_author_meta('email');
                                            if (!empty($author_email) && get_theme_mod('aqualuxe_blog_author_email', false)) :
                                            ?>
                                                <a href="mailto:<?php echo esc_attr($author_email); ?>" class="author-email">
                                                    <i class="fas fa-envelope"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            // Post navigation
                            if (get_theme_mod('aqualuxe_blog_post_navigation', true)) :
                                the_post_navigation(
                                    array(
                                        'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous Post', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                                        'next_text' => '<span class="nav-subtitle">' . esc_html__('Next Post', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                                    )
                                );
                            endif;
                            
                            // Related posts
                            if (get_theme_mod('aqualuxe_blog_related_posts', true)) :
                                $categories = get_the_category();
                                
                                if ($categories) :
                                    $category_ids = array();
                                    foreach ($categories as $category) {
                                        $category_ids[] = $category->term_id;
                                    }
                                    
                                    $related_args = array(
                                        'post_type' => 'post',
                                        'posts_per_page' => 3,
                                        'post__not_in' => array(get_the_ID()),
                                        'category__in' => $category_ids,
                                        'orderby' => 'rand',
                                    );
                                    
                                    $related_posts = new WP_Query($related_args);
                                    
                                    if ($related_posts->have_posts()) :
                            ?>
                                        <div class="related-posts" data-aos="fade-up">
                                            <h3 class="related-posts-title"><?php esc_html_e('Related Posts', 'aqualuxe'); ?></h3>
                                            <div class="row">
                                                <?php
                                                while ($related_posts->have_posts()) :
                                                    $related_posts->the_post();
                                                ?>
                                                    <div class="col-md-4">
                                                        <div class="related-post-item">
                                                            <?php if (has_post_thumbnail()) : ?>
                                                                <div class="related-post-image">
                                                                    <a href="<?php the_permalink(); ?>">
                                                                        <?php the_post_thumbnail('medium', array('class' => 'img-fluid')); ?>
                                                                    </a>
                                                                </div>
                                                            <?php endif; ?>
                                                            
                                                            <div class="related-post-content">
                                                                <h4 class="related-post-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                                                                <div class="related-post-date">
                                                                    <i class="far fa-calendar-alt"></i>
                                                                    <?php echo get_the_date(); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php
                                                endwhile;
                                                wp_reset_postdata();
                                                ?>
                                            </div>
                                        </div>
                            <?php
                                    endif;
                                endif;
                            endif;
                            
                            // If comments are open or we have at least one comment, load up the comment template.
                            if (comments_open() || get_comments_number()) :
                                comments_template();
                            endif;
                            ?>
                        </article>
                    <?php endwhile; ?>
                </div>
                
                <div class="col-lg-4">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        </div>
    </section>

</main><!-- #primary -->

<?php
get_footer();