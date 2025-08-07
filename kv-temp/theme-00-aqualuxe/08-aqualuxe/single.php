<?php
/**
 * The template for displaying all single posts
 *
 * @package AquaLuxe
 * @version 1.0.0
 */

get_header();
?>

<main id="primary" class="site-main" role="main" <?php echo aqualuxe_schema_markup(); ?>>
    <div class="container">
        <div class="content-area">
            
            <?php while (have_posts()) : the_post(); ?>
                
                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/Article">
                    
                    <header class="entry-header">
                        <?php the_title('<h1 class="entry-title" itemprop="headline">', '</h1>'); ?>
                        
                        <div class="entry-meta">
                            <div class="entry-meta-item">
                                <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>" itemprop="datePublished">
                                    <?php echo esc_html(get_the_date()); ?>
                                </time>
                                <time class="updated" datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>" itemprop="dateModified">
                                    <?php echo esc_html(get_the_modified_date()); ?>
                                </time>
                            </div>
                            
                            <div class="entry-meta-item">
                                <span class="byline" itemprop="author" itemscope itemtype="http://schema.org/Person">
                                    <?php esc_html_e('By', 'aqualuxe'); ?> 
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" itemprop="url">
                                        <span itemprop="name"><?php echo esc_html(get_the_author()); ?></span>
                                    </a>
                                </span>
                            </div>
                            
                            <?php if (has_category()) : ?>
                                <div class="entry-meta-item">
                                    <span class="cat-links">
                                        <?php the_category(', '); ?>
                                    </span>
                                </div>
                            <?php endif; ?>
                        </div>
                    </header>
                    
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="entry-featured-image">
                            <?php the_post_thumbnail('aqualuxe-blog-large', array('class' => 'featured-image', 'itemprop' => 'image')); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="entry-content" itemprop="articleBody">
                        <?php
                        the_content();
                        
                        wp_link_pages(array(
                            'before' => '<div class="page-links">' . esc_html__('Pages:', 'aqualuxe'),
                            'after'  => '</div>',
                        ));
                        ?>
                    </div>
                    
                    <footer class="entry-footer">
                        <?php if (has_tag()) : ?>
                            <div class="tags-links">
                                <span class="tags-title"><?php esc_html_e('Tags:', 'aqualuxe'); ?></span>
                                <?php the_tags('', ', ', ''); ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php
                        edit_post_link(
                            sprintf(
                                wp_kses(
                                    __('Edit <span class="screen-reader-text">%s</span>', 'aqualuxe'),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                                ),
                                get_the_title()
                            ),
                            '<span class="edit-link">',
                            '</span>'
                        );
                        ?>
                    </footer>
                    
                </article>
                
                <?php
                // Previous/Next post navigation
                the_post_navigation(array(
                    'prev_text' => '<span class="nav-subtitle">' . esc_html__('Previous:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                    'next_text' => '<span class="nav-subtitle">' . esc_html__('Next:', 'aqualuxe') . '</span> <span class="nav-title">%title</span>',
                ));
                ?>
                
                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if (comments_open() || get_comments_number()) :
                    comments_template();
                endif;
                ?>
                
            <?php endwhile; ?>
            
        </div>
        
        <?php get_sidebar(); ?>
    </div>
</main>

<?php
get_footer();