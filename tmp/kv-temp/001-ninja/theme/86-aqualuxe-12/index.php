<?php
/* Main template file */
\get_header();
?>
<main id="primary" class="site-main container mx-auto px-4 py-8" role="main">
    <?php if (\have_posts()) : ?>
        <?php while (\have_posts()) : \the_post(); ?>
            <article id="post-<?php \the_ID(); ?>" <?php \post_class('prose dark:prose-invert max-w-none'); ?> itemscope itemtype="https://schema.org/Article">
                <header class="mb-4">
                    <h1 class="entry-title text-3xl font-semibold" itemprop="headline">
                        <a href="<?php \the_permalink(); ?>" class="hover:underline"><?php \the_title(); ?></a>
                    </h1>
                </header>
                <div class="entry-content" itemprop="articleBody">
                    <?php \the_excerpt(); ?>
                </div>
            </article>
        <?php endwhile; ?>
        <?php \the_posts_pagination(); ?>
    <?php else : ?>
        <p><?php \esc_html_e('No content found.', 'aqualuxe'); ?></p>
    <?php endif; ?>
</main>
<?php \get_footer();
