<?php
/**
 * The template for displaying all single posts
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="<?php echo esc_attr(kv_get_content_classes()); ?>">
            <main id="primary" class="site-main single-post" role="main">
                
                <?php while (have_posts()) : the_post(); ?>
                    
                    <article id="post-<?php the_ID(); ?>" <?php post_class('single-post-article'); ?>>
                        
                        <!-- Post Header -->
                        <header class="entry-header">
                            
                            <!-- Breadcrumbs -->
                            <?php if (kv_get_theme_option('show_breadcrumbs', true)) : ?>
                                <nav class="breadcrumbs" aria-label="<?php esc_attr_e('Breadcrumb', KV_THEME_TEXTDOMAIN); ?>">
                                    <?php kv_breadcrumbs(); ?>
                                </nav>
                            <?php endif; ?>
                            
                            <!-- Post Categories -->
                            <?php if (has_category()) : ?>
                                <div class="entry-categories">
                                    <?php
                                    $categories = get_the_category();
                                    echo '<span class="categories-label">' . esc_html__('Posted in:', KV_THEME_TEXTDOMAIN) . '</span>';
                                    foreach ($categories as $category) {
                                        echo '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-link">' . esc_html($category->name) . '</a>';
                                    }
                                    ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Post Title -->
                            <h1 class="entry-title"><?php the_title(); ?></h1>
                            
                            <!-- Post Meta -->
                            <div class="entry-meta">
                                <div class="meta-primary">
                                    <span class="posted-on">
                                        <i class="fas fa-calendar-alt" aria-hidden="true"></i>
                                        <time class="entry-date published" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                            <?php echo esc_html(get_the_date()); ?>
                                        </time>
                                        <?php if (get_the_time('U') !== get_the_modified_time('U')) : ?>
                                            <time class="updated" datetime="<?php echo esc_attr(get_the_modified_date('c')); ?>">
                                                <?php printf(esc_html__('Updated: %s', KV_THEME_TEXTDOMAIN), get_the_modified_date()); ?>
                                            </time>
                                        <?php endif; ?>
                                    </span>
                                    
                                    <span class="byline">
                                        <i class="fas fa-user" aria-hidden="true"></i>
                                        <span class="author vcard">
                                            <a class="url fn n" href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                                <?php echo esc_html(get_the_author()); ?>
                                            </a>
                                        </span>
                                    </span>
                                    
                                    <?php if (comments_open() || get_comments_number()) : ?>
                                        <span class="comments-link">
                                            <i class="fas fa-comments" aria-hidden="true"></i>
                                            <?php comments_popup_link(
                                                esc_html__('No Comments', KV_THEME_TEXTDOMAIN),
                                                esc_html__('1 Comment', KV_THEME_TEXTDOMAIN),
                                                esc_html__('% Comments', KV_THEME_TEXTDOMAIN)
                                            ); ?>
                                        </span>
                                    <?php endif; ?>
                                    
                                    <!-- Reading time estimate -->
                                    <span class="reading-time">
                                        <i class="fas fa-clock" aria-hidden="true"></i>
                                        <?php echo kv_get_reading_time(); ?>
                                    </span>
                                    
                                    <!-- Post views -->
                                    <?php if (kv_get_theme_option('show_post_views', true)) : ?>
                                        <span class="post-views">
                                            <i class="fas fa-eye" aria-hidden="true"></i>
                                            <?php echo kv_get_post_views(); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <!-- Social sharing buttons -->
                                <?php if (kv_get_theme_option('show_social_sharing', true)) : ?>
                                    <div class="social-sharing">
                                        <span class="sharing-label"><?php esc_html_e('Share:', KV_THEME_TEXTDOMAIN); ?></span>
                                        <div class="sharing-buttons">
                                            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode(get_permalink()); ?>" 
                                               class="share-button facebook" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               aria-label="<?php esc_attr_e('Share on Facebook', KV_THEME_TEXTDOMAIN); ?>">
                                                <i class="fab fa-facebook-f" aria-hidden="true"></i>
                                            </a>
                                            
                                            <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode(get_permalink()); ?>&text=<?php echo urlencode(get_the_title()); ?>" 
                                               class="share-button twitter" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               aria-label="<?php esc_attr_e('Share on Twitter', KV_THEME_TEXTDOMAIN); ?>">
                                                <i class="fab fa-twitter" aria-hidden="true"></i>
                                            </a>
                                            
                                            <a href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo urlencode(get_permalink()); ?>" 
                                               class="share-button linkedin" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               aria-label="<?php esc_attr_e('Share on LinkedIn', KV_THEME_TEXTDOMAIN); ?>">
                                                <i class="fab fa-linkedin-in" aria-hidden="true"></i>
                                            </a>
                                            
                                            <a href="https://wa.me/?text=<?php echo urlencode(get_the_title() . ' ' . get_permalink()); ?>" 
                                               class="share-button whatsapp" 
                                               target="_blank" 
                                               rel="noopener noreferrer"
                                               aria-label="<?php esc_attr_e('Share on WhatsApp', KV_THEME_TEXTDOMAIN); ?>">
                                                <i class="fab fa-whatsapp" aria-hidden="true"></i>
                                            </a>
                                            
                                            <button class="share-button copy-link" 
                                                    onclick="kvCopyToClipboard('<?php echo esc_js(get_permalink()); ?>')"
                                                    aria-label="<?php esc_attr_e('Copy link', KV_THEME_TEXTDOMAIN); ?>">
                                                <i class="fas fa-link" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div><!-- .entry-meta -->
                            
                        </header><!-- .entry-header -->
                        
                        <!-- Featured Image -->
                        <?php if (has_post_thumbnail() && kv_get_theme_option('show_featured_image', true)) : ?>
                            <div class="post-thumbnail">
                                <?php
                                the_post_thumbnail('full', array(
                                    'alt' => esc_attr(get_the_title()),
                                    'class' => 'featured-image'
                                ));
                                
                                // Image caption
                                $thumbnail_id = get_post_thumbnail_id();
                                $caption = wp_get_attachment_caption($thumbnail_id);
                                if ($caption) : ?>
                                    <figcaption class="wp-caption-text"><?php echo esc_html($caption); ?></figcaption>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Post Content -->
                        <div class="entry-content">
                            
                            <!-- Table of Contents -->
                            <?php if (kv_get_theme_option('show_table_of_contents', true)) : ?>
                                <div id="table-of-contents" class="table-of-contents">
                                    <h3><?php esc_html_e('Table of Contents', KV_THEME_TEXTDOMAIN); ?></h3>
                                    <div class="toc-content">
                                        <!-- Generated by JavaScript -->
                                    </div>
                                    <button class="toc-toggle" aria-expanded="true">
                                        <span class="toggle-text"><?php esc_html_e('Hide', KV_THEME_TEXTDOMAIN); ?></span>
                                        <i class="fas fa-chevron-up" aria-hidden="true"></i>
                                    </button>
                                </div>
                            <?php endif; ?>
                            
                            <?php
                            the_content();
                            
                            wp_link_pages(array(
                                'before' => '<div class="page-links">' . esc_html__('Pages:', KV_THEME_TEXTDOMAIN),
                                'after'  => '</div>',
                            ));
                            ?>
                            
                        </div><!-- .entry-content -->
                        
                        <!-- Post Footer -->
                        <footer class="entry-footer">
                            
                            <!-- Tags -->
                            <?php if (has_tag()) : ?>
                                <div class="entry-tags">
                                    <span class="tags-label">
                                        <i class="fas fa-tags" aria-hidden="true"></i>
                                        <?php esc_html_e('Tags:', KV_THEME_TEXTDOMAIN); ?>
                                    </span>
                                    <div class="tags-list">
                                        <?php the_tags('', ''); ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Edit post link (for logged-in users with permission) -->
                            <?php
                            edit_post_link(
                                sprintf(
                                    wp_kses(
                                        __('Edit <span class="screen-reader-text">"%s"</span>', KV_THEME_TEXTDOMAIN),
                                        array(
                                            'span' => array(
                                                'class' => array(),
                                            ),
                                        )
                                    ),
                                    get_the_title()
                                ),
                                '<div class="edit-link"><i class="fas fa-edit" aria-hidden="true"></i>',
                                '</div>'
                            );
                            ?>
                            
                        </footer><!-- .entry-footer -->
                        
                    </article><!-- #post-<?php the_ID(); ?> -->
                    
                    <!-- Author Bio -->
                    <?php if (kv_get_theme_option('show_author_bio', true) && get_the_author_meta('description')) : ?>
                        <div class="author-bio">
                            <div class="author-avatar">
                                <?php echo get_avatar(get_the_author_meta('ID'), 80); ?>
                            </div>
                            <div class="author-info">
                                <h3 class="author-name">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>">
                                        <?php echo esc_html(get_the_author()); ?>
                                    </a>
                                </h3>
                                <div class="author-description">
                                    <?php echo wp_kses_post(get_the_author_meta('description')); ?>
                                </div>
                                <div class="author-links">
                                    <a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'))); ?>" class="author-posts-link">
                                        <?php esc_html_e('View all posts', KV_THEME_TEXTDOMAIN); ?>
                                    </a>
                                    <?php if (get_the_author_meta('user_url')) : ?>
                                        <a href="<?php echo esc_url(get_the_author_meta('user_url')); ?>" class="author-website-link" target="_blank" rel="noopener">
                                            <?php esc_html_e('Visit website', KV_THEME_TEXTDOMAIN); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Related Posts -->
                    <?php if (kv_get_theme_option('show_related_posts', true)) : ?>
                        <div class="related-posts">
                            <h3><?php esc_html_e('Related Posts', KV_THEME_TEXTDOMAIN); ?></h3>
                            <div class="related-posts-grid">
                                <?php
                                $related_posts = kv_get_related_posts(get_the_ID(), 3);
                                if ($related_posts) :
                                    foreach ($related_posts as $post) :
                                        setup_postdata($post); ?>
                                        <article class="related-post-item">
                                            <a href="<?php the_permalink(); ?>" class="related-post-link">
                                                <?php if (has_post_thumbnail()) : ?>
                                                    <div class="related-post-thumbnail">
                                                        <?php the_post_thumbnail('medium', array(
                                                            'alt' => esc_attr(get_the_title()),
                                                            'loading' => 'lazy'
                                                        )); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="related-post-content">
                                                    <h4 class="related-post-title"><?php the_title(); ?></h4>
                                                    <time class="related-post-date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
                                                        <?php echo esc_html(get_the_date()); ?>
                                                    </time>
                                                </div>
                                            </a>
                                        </article>
                                    <?php endforeach;
                                    wp_reset_postdata();
                                endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Post Navigation -->
                    <nav class="post-navigation" aria-label="<?php esc_attr_e('Posts', KV_THEME_TEXTDOMAIN); ?>">
                        <div class="nav-links">
                            <?php
                            $prev_post = get_previous_post();
                            $next_post = get_next_post();
                            
                            if ($prev_post) : ?>
                                <div class="nav-previous">
                                    <a href="<?php echo esc_url(get_permalink($prev_post->ID)); ?>" rel="prev">
                                        <span class="nav-subtitle"><?php esc_html_e('Previous Post', KV_THEME_TEXTDOMAIN); ?></span>
                                        <span class="nav-title"><?php echo esc_html(get_the_title($prev_post->ID)); ?></span>
                                    </a>
                                </div>
                            <?php endif;
                            
                            if ($next_post) : ?>
                                <div class="nav-next">
                                    <a href="<?php echo esc_url(get_permalink($next_post->ID)); ?>" rel="next">
                                        <span class="nav-subtitle"><?php esc_html_e('Next Post', KV_THEME_TEXTDOMAIN); ?></span>
                                        <span class="nav-title"><?php echo esc_html(get_the_title($next_post->ID)); ?></span>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </nav>
                    
                    <!-- Comments -->
                    <?php
                    if (comments_open() || get_comments_number()) {
                        comments_template();
                    }
                    ?>
                    
                <?php endwhile; ?>
                
            </main><!-- #primary -->
        </div>
        
        <?php if (kv_has_sidebar()) : ?>
            <div class="<?php echo esc_attr(kv_get_sidebar_classes()); ?>">
                <?php get_sidebar(); ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
/* Single Post Styles */
.single-post-article {
    margin-bottom: 2rem;
}

.entry-header {
    margin-bottom: 2rem;
    text-align: center;
}

.breadcrumbs {
    margin-bottom: 1rem;
    text-align: left;
}

.entry-categories {
    margin-bottom: 1rem;
}

.categories-label {
    font-weight: 600;
    margin-right: 0.5rem;
    color: var(--text-muted);
}

.category-link {
    display: inline-block;
    margin: 0 0.25rem;
    padding: 0.25rem 0.75rem;
    background-color: var(--primary-color);
    color: white;
    text-decoration: none;
    border-radius: var(--border-radius);
    font-size: 0.9rem;
    transition: background-color 0.3s ease;
}

.category-link:hover {
    background-color: var(--primary-color-dark);
    color: white;
}

.entry-title {
    font-size: 2.5rem;
    margin: 0 0 1.5rem 0;
    line-height: 1.2;
    color: var(--heading-color);
}

.entry-meta {
    color: var(--text-muted);
    font-size: 0.9rem;
    margin-bottom: 1rem;
}

.meta-primary {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.meta-primary span {
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.social-sharing {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    flex-wrap: wrap;
}

.sharing-label {
    font-weight: 600;
    margin-right: 0.5rem;
}

.sharing-buttons {
    display: flex;
    gap: 0.5rem;
}

.share-button {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    border-radius: 50%;
    text-decoration: none;
    color: white;
    transition: transform 0.3s ease;
}

.share-button:hover {
    transform: scale(1.1);
    color: white;
}

.share-button.facebook { background-color: #1877f2; }
.share-button.twitter { background-color: #1da1f2; }
.share-button.linkedin { background-color: #0077b5; }
.share-button.whatsapp { background-color: #25d366; }
.share-button.copy-link { background-color: var(--text-color); border: none; cursor: pointer; }

.post-thumbnail {
    margin-bottom: 2rem;
    text-align: center;
}

.featured-image {
    width: 100%;
    height: auto;
    border-radius: var(--border-radius);
}

.wp-caption-text {
    margin-top: 0.5rem;
    font-style: italic;
    color: var(--text-muted);
    font-size: 0.9rem;
}

.entry-content {
    line-height: 1.8;
    margin-bottom: 2rem;
}

.table-of-contents {
    background: var(--background-light);
    padding: 1.5rem;
    border-radius: var(--border-radius);
    margin-bottom: 2rem;
    border-left: 4px solid var(--primary-color);
}

.table-of-contents h3 {
    margin: 0 0 1rem 0;
    font-size: 1.2rem;
}

.toc-toggle {
    background: none;
    border: none;
    color: var(--primary-color);
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
    font-size: 0.9rem;
}

.page-links {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
    text-align: center;
}

.entry-footer {
    margin-top: 2rem;
    padding-top: 1rem;
    border-top: 1px solid var(--border-color);
}

.entry-tags {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    margin-bottom: 1rem;
    flex-wrap: wrap;
}

.tags-label {
    font-weight: 600;
    color: var(--text-color);
    white-space: nowrap;
}

.tags-list a {
    display: inline-block;
    margin: 0 0.25rem 0.25rem 0;
    padding: 0.25rem 0.5rem;
    background-color: var(--background-light);
    color: var(--text-color);
    text-decoration: none;
    border-radius: var(--border-radius);
    font-size: 0.85rem;
    border: 1px solid var(--border-color);
    transition: all 0.3s ease;
}

.tags-list a:hover {
    background-color: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.edit-link {
    text-align: center;
    margin-top: 1rem;
}

.edit-link a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}

.author-bio {
    display: flex;
    gap: 1.5rem;
    padding: 2rem;
    background: var(--background-light);
    border-radius: var(--border-radius);
    margin: 2rem 0;
}

.author-avatar img {
    border-radius: 50%;
}

.author-info {
    flex: 1;
}

.author-name {
    margin: 0 0 0.5rem 0;
    font-size: 1.3rem;
}

.author-name a {
    color: var(--heading-color);
    text-decoration: none;
}

.author-name a:hover {
    color: var(--primary-color);
}

.author-description {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.author-links {
    display: flex;
    gap: 1rem;
}

.author-links a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
    font-size: 0.9rem;
}

.related-posts {
    margin: 2rem 0;
}

.related-posts h3 {
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    text-align: center;
}

.related-posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.related-post-item {
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.related-post-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.related-post-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.related-post-thumbnail img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.related-post-content {
    padding: 1.5rem;
}

.related-post-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    line-height: 1.4;
    color: var(--heading-color);
}

.related-post-date {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.post-navigation {
    margin: 3rem 0;
    padding: 2rem 0;
    border-top: 1px solid var(--border-color);
    border-bottom: 1px solid var(--border-color);
}

.nav-links {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
}

.nav-previous a,
.nav-next a {
    display: block;
    padding: 1.5rem;
    background: var(--background-light);
    border-radius: var(--border-radius);
    text-decoration: none;
    color: var(--text-color);
    transition: all 0.3s ease;
}

.nav-previous a:hover,
.nav-next a:hover {
    background: var(--primary-color);
    color: white;
}

.nav-subtitle {
    display: block;
    font-size: 0.9rem;
    color: var(--text-muted);
    margin-bottom: 0.5rem;
}

.nav-title {
    display: block;
    font-weight: 600;
    font-size: 1.1rem;
    line-height: 1.4;
}

.nav-next {
    text-align: right;
}

/* Responsive design */
@media (max-width: 768px) {
    .entry-title {
        font-size: 2rem;
    }
    
    .meta-primary {
        justify-content: flex-start;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .social-sharing {
        justify-content: flex-start;
        margin-top: 1rem;
    }
    
    .author-bio {
        flex-direction: column;
        text-align: center;
    }
    
    .related-posts-grid {
        grid-template-columns: 1fr;
    }
    
    .nav-links {
        grid-template-columns: 1fr;
    }
    
    .nav-next {
        text-align: left;
    }
    
    .entry-tags {
        flex-direction: column;
        gap: 0.5rem;
    }
}

@media (max-width: 480px) {
    .entry-title {
        font-size: 1.5rem;
    }
    
    .sharing-buttons {
        justify-content: center;
    }
    
    .author-bio {
        padding: 1rem;
    }
    
    .author-links {
        flex-direction: column;
        gap: 0.5rem;
    }
}
</style>

<script>
// Single post functionality
document.addEventListener('DOMContentLoaded', function() {
    // Table of Contents generation
    const tocContainer = document.querySelector('.toc-content');
    const tocToggle = document.querySelector('.toc-toggle');
    
    if (tocContainer) {
        generateTableOfContents();
    }
    
    if (tocToggle) {
        tocToggle.addEventListener('click', function() {
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            
            const toc = document.getElementById('table-of-contents');
            const toggleText = this.querySelector('.toggle-text');
            const toggleIcon = this.querySelector('i');
            
            if (isExpanded) {
                toc.classList.add('collapsed');
                toggleText.textContent = '<?php esc_html_e('Show', KV_THEME_TEXTDOMAIN); ?>';
                toggleIcon.className = 'fas fa-chevron-down';
            } else {
                toc.classList.remove('collapsed');
                toggleText.textContent = '<?php esc_html_e('Hide', KV_THEME_TEXTDOMAIN); ?>';
                toggleIcon.className = 'fas fa-chevron-up';
            }
        });
    }
    
    // Reading progress indicator
    if (typeof KVEnterprise !== 'undefined' && KVEnterprise.initReadingProgress) {
        KVEnterprise.initReadingProgress();
    }
    
    // Post views tracking
    if (typeof KVEnterprise !== 'undefined' && KVEnterprise.trackPostView) {
        KVEnterprise.trackPostView(<?php echo get_the_ID(); ?>);
    }
});

function generateTableOfContents() {
    const headings = document.querySelectorAll('.entry-content h1, .entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6');
    const tocContainer = document.querySelector('.toc-content');
    
    if (headings.length === 0) {
        document.getElementById('table-of-contents').style.display = 'none';
        return;
    }
    
    let tocHTML = '<ul>';
    
    headings.forEach((heading, index) => {
        const id = 'heading-' + index;
        heading.id = id;
        
        const level = parseInt(heading.tagName.charAt(1));
        const text = heading.textContent;
        
        tocHTML += `<li class="toc-level-${level}"><a href="#${id}">${text}</a></li>`;
    });
    
    tocHTML += '</ul>';
    tocContainer.innerHTML = tocHTML;
    
    // Add smooth scrolling to TOC links
    const tocLinks = tocContainer.querySelectorAll('a');
    tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

function kvCopyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        if (typeof KVEnterprise !== 'undefined' && KVEnterprise.showNotification) {
            KVEnterprise.showNotification('<?php esc_html_e('Link copied to clipboard!', KV_THEME_TEXTDOMAIN); ?>', 'success');
        } else {
            alert('<?php esc_html_e('Link copied to clipboard!', KV_THEME_TEXTDOMAIN); ?>');
        }
    }).catch(function() {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = text;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        if (typeof KVEnterprise !== 'undefined' && KVEnterprise.showNotification) {
            KVEnterprise.showNotification('<?php esc_html_e('Link copied to clipboard!', KV_THEME_TEXTDOMAIN); ?>', 'success');
        } else {
            alert('<?php esc_html_e('Link copied to clipboard!', KV_THEME_TEXTDOMAIN); ?>');
        }
    });
}
</script>

<?php get_footer(); ?>
