<?php
/**
 * The template for displaying 404 pages (not found)
 * 
 * @package KV_Enterprise
 * @version 1.0.0
 * @since 1.0.0
 */

get_header(); ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <main id="primary" class="site-main error-404-content" role="main">
                
                <section class="error-404 not-found">
                    
                    <div class="error-404-header text-center">
                        <div class="error-code">
                            <span class="error-number">404</span>
                            <div class="error-animation">
                                <div class="floating-shapes">
                                    <div class="shape shape-1"></div>
                                    <div class="shape shape-2"></div>
                                    <div class="shape shape-3"></div>
                                </div>
                            </div>
                        </div>
                        
                        <header class="page-header">
                            <h1 class="page-title">
                                <?php esc_html_e('Oops! That page can&rsquo;t be found.', KV_THEME_TEXTDOMAIN); ?>
                            </h1>
                        </header>
                        
                        <div class="page-content">
                            <p class="error-description">
                                <?php esc_html_e('It looks like nothing was found at this location. Maybe try one of the links below or a search?', KV_THEME_TEXTDOMAIN); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="error-404-actions">
                        
                        <!-- Search Form -->
                        <div class="search-section">
                            <h3><?php esc_html_e('Search', KV_THEME_TEXTDOMAIN); ?></h3>
                            <div class="search-form-wrapper">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                        
                        <!-- Quick Links -->
                        <div class="quick-links-section">
                            <h3><?php esc_html_e('Quick Links', KV_THEME_TEXTDOMAIN); ?></h3>
                            <div class="quick-links">
                                <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary">
                                    <i class="fas fa-home" aria-hidden="true"></i>
                                    <?php esc_html_e('Go to Homepage', KV_THEME_TEXTDOMAIN); ?>
                                </a>
                                
                                <a href="<?php echo esc_url(get_permalink(get_option('page_for_posts'))); ?>" class="btn btn-outline-primary">
                                    <i class="fas fa-blog" aria-hidden="true"></i>
                                    <?php esc_html_e('Visit Blog', KV_THEME_TEXTDOMAIN); ?>
                                </a>
                                
                                <?php
                                $contact_page = get_option('kv_contact_page');
                                if ($contact_page) : ?>
                                    <a href="<?php echo esc_url(get_permalink($contact_page)); ?>" class="btn btn-outline-primary">
                                        <i class="fas fa-envelope" aria-hidden="true"></i>
                                        <?php esc_html_e('Contact Us', KV_THEME_TEXTDOMAIN); ?>
                                    </a>
                                <?php endif; ?>
                                
                                <button onclick="history.back()" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left" aria-hidden="true"></i>
                                    <?php esc_html_e('Go Back', KV_THEME_TEXTDOMAIN); ?>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Recent Posts -->
                        <?php
                        $recent_posts = wp_get_recent_posts(array(
                            'numberposts' => 6,
                            'post_status' => 'publish'
                        ));
                        
                        if ($recent_posts) : ?>
                            <div class="recent-posts-section">
                                <h3><?php esc_html_e('Recent Posts', KV_THEME_TEXTDOMAIN); ?></h3>
                                <div class="recent-posts-grid">
                                    <?php foreach ($recent_posts as $recent) : ?>
                                        <article class="recent-post-card">
                                            <a href="<?php echo esc_url(get_permalink($recent['ID'])); ?>" class="recent-post-link">
                                                <?php if (has_post_thumbnail($recent['ID'])) : ?>
                                                    <div class="recent-post-thumbnail">
                                                        <?php echo get_the_post_thumbnail($recent['ID'], 'medium', array(
                                                            'alt' => esc_attr(get_the_title($recent['ID'])),
                                                            'loading' => 'lazy'
                                                        )); ?>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="recent-post-content">
                                                    <h4 class="recent-post-title"><?php echo esc_html($recent['post_title']); ?></h4>
                                                    <time class="recent-post-date" datetime="<?php echo esc_attr(get_the_date('c', $recent['ID'])); ?>">
                                                        <?php echo esc_html(get_the_date('', $recent['ID'])); ?>
                                                    </time>
                                                </div>
                                            </a>
                                        </article>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Popular Categories -->
                        <?php
                        $categories = get_categories(array(
                            'orderby' => 'count',
                            'order'   => 'DESC',
                            'number'  => 8,
                            'hide_empty' => true
                        ));
                        
                        if ($categories) : ?>
                            <div class="categories-section">
                                <h3><?php esc_html_e('Browse Categories', KV_THEME_TEXTDOMAIN); ?></h3>
                                <div class="categories-grid">
                                    <?php foreach ($categories as $category) : ?>
                                        <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="category-card">
                                            <div class="category-info">
                                                <h4 class="category-name"><?php echo esc_html($category->name); ?></h4>
                                                <span class="category-count">
                                                    <?php printf(
                                                        _n('%d post', '%d posts', $category->count, KV_THEME_TEXTDOMAIN),
                                                        $category->count
                                                    ); ?>
                                                </span>
                                            </div>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Error Report -->
                        <?php if (kv_get_theme_option('enable_error_reporting', true)) : ?>
                            <div class="error-report-section">
                                <h3><?php esc_html_e('Report This Error', KV_THEME_TEXTDOMAIN); ?></h3>
                                <p><?php esc_html_e('Help us improve by reporting this broken link.', KV_THEME_TEXTDOMAIN); ?></p>
                                <form class="error-report-form" method="post" action="<?php echo esc_url(admin_url('admin-ajax.php')); ?>">
                                    <div class="form-group">
                                        <label for="error-url" class="form-label">
                                            <?php esc_html_e('Broken URL', KV_THEME_TEXTDOMAIN); ?>
                                        </label>
                                        <input type="url" 
                                               id="error-url" 
                                               name="error_url" 
                                               class="form-control" 
                                               value="<?php echo esc_url(kv_get_current_url()); ?>" 
                                               readonly>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="error-referrer" class="form-label">
                                            <?php esc_html_e('Came From (optional)', KV_THEME_TEXTDOMAIN); ?>
                                        </label>
                                        <input type="url" 
                                               id="error-referrer" 
                                               name="error_referrer" 
                                               class="form-control" 
                                               value="<?php echo esc_url(wp_get_referer()); ?>" 
                                               placeholder="<?php esc_attr_e('Previous page URL', KV_THEME_TEXTDOMAIN); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="error-description" class="form-label">
                                            <?php esc_html_e('Additional Information (optional)', KV_THEME_TEXTDOMAIN); ?>
                                        </label>
                                        <textarea id="error-description" 
                                                  name="error_description" 
                                                  class="form-control" 
                                                  rows="3" 
                                                  placeholder="<?php esc_attr_e('What were you looking for?', KV_THEME_TEXTDOMAIN); ?>"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-bug" aria-hidden="true"></i>
                                        <?php esc_html_e('Report Error', KV_THEME_TEXTDOMAIN); ?>
                                    </button>
                                    
                                    <input type="hidden" name="action" value="kv_report_404_error">
                                    <?php wp_nonce_field('kv_report_404_error', 'error_report_nonce'); ?>
                                </form>
                            </div>
                        <?php endif; ?>
                        
                    </div>
                    
                </section><!-- .error-404 -->
                
            </main><!-- #primary -->
        </div>
    </div>
</div>

<style>
/* 404 Page Styles */
.error-404-content {
    min-height: 60vh;
    padding: 4rem 0;
}

.error-404-header {
    margin-bottom: 4rem;
}

.error-code {
    position: relative;
    margin-bottom: 2rem;
}

.error-number {
    font-size: 8rem;
    font-weight: 900;
    color: var(--primary-color);
    text-shadow: 2px 2px 4px rgba(0,0,0,0.1);
    display: inline-block;
    animation: float 3s ease-in-out infinite;
}

.error-animation {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: -1;
}

.floating-shapes {
    position: relative;
    width: 200px;
    height: 200px;
}

.shape {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 60px;
    height: 60px;
    background: var(--primary-color);
    top: 20px;
    left: 30px;
    animation-delay: 0s;
}

.shape-2 {
    width: 40px;
    height: 40px;
    background: var(--secondary-color);
    top: 80px;
    right: 20px;
    animation-delay: 2s;
}

.shape-3 {
    width: 80px;
    height: 80px;
    background: var(--accent-color);
    bottom: 30px;
    left: 50px;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-20px); }
}

.error-404-actions {
    display: grid;
    gap: 3rem;
    margin-top: 3rem;
}

.error-404-actions h3 {
    margin-bottom: 1.5rem;
    color: var(--heading-color);
    font-size: 1.5rem;
}

.search-form-wrapper {
    max-width: 500px;
    margin: 0 auto;
}

.quick-links {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    justify-content: center;
}

.recent-posts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.recent-post-card {
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.recent-post-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.recent-post-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.recent-post-thumbnail img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.recent-post-content {
    padding: 1.5rem;
}

.recent-post-title {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
    line-height: 1.4;
}

.recent-post-date {
    color: var(--text-muted);
    font-size: 0.9rem;
}

.categories-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1.5rem;
}

.category-card {
    padding: 1.5rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    text-decoration: none;
    color: inherit;
    text-align: center;
    transition: all 0.3s ease;
}

.category-card:hover {
    border-color: var(--primary-color);
    background-color: var(--primary-color);
    color: white;
    transform: translateY(-2px);
}

.category-name {
    margin: 0 0 0.5rem 0;
    font-size: 1.1rem;
}

.category-count {
    font-size: 0.9rem;
    opacity: 0.8;
}

.error-report-form {
    max-width: 600px;
    margin: 0 auto;
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--heading-color);
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-color);
    border-radius: var(--border-radius);
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 2px rgba(var(--primary-color-rgb), 0.1);
}

/* Responsive Design */
@media (max-width: 768px) {
    .error-number {
        font-size: 6rem;
    }
    
    .quick-links {
        flex-direction: column;
        align-items: center;
    }
    
    .recent-posts-grid {
        grid-template-columns: 1fr;
    }
    
    .categories-grid {
        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    }
}

@media (max-width: 480px) {
    .error-number {
        font-size: 4rem;
    }
    
    .error-404-content {
        padding: 2rem 0;
    }
    
    .error-404-header {
        margin-bottom: 2rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced 404 page functionality
    
    // Track 404 errors for analytics
    if (typeof gtag !== 'undefined') {
        gtag('event', 'page_view', {
            'page_title': '404 - Page Not Found',
            'page_location': window.location.href
        });
    }
    
    // Error report form handling
    const errorReportForm = document.querySelector('.error-report-form');
    if (errorReportForm) {
        errorReportForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('<?php esc_html_e('Thank you for reporting this error. We will look into it.', KV_THEME_TEXTDOMAIN); ?>');
                    this.reset();
                } else {
                    alert('<?php esc_html_e('Sorry, there was an error submitting your report. Please try again.', KV_THEME_TEXTDOMAIN); ?>');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('<?php esc_html_e('Sorry, there was an error submitting your report. Please try again.', KV_THEME_TEXTDOMAIN); ?>');
            });
        });
    }
    
    // Auto-focus search field
    const searchField = document.querySelector('.search-form input[type="search"]');
    if (searchField) {
        setTimeout(() => {
            searchField.focus();
        }, 500);
    }
});
</script>

<?php get_footer(); ?>
