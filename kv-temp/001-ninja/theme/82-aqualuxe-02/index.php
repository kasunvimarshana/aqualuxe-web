<?php
if ( function_exists('get_header') ) { call_user_func('get_header'); } ?>
<main id="primary" class="site-main container mx-auto px-4 py-10">
	<?php if ( function_exists('have_posts') && call_user_func('have_posts') ) : while ( function_exists('have_posts') && call_user_func('have_posts') ) : if ( function_exists('the_post') ) call_user_func('the_post'); ?>
		<article <?php if ( function_exists('post_class') ) { call_user_func('post_class','prose max-w-3xl mx-auto'); } else { echo 'class="prose max-w-3xl mx-auto"'; } ?> itemscope itemtype="https://schema.org/Article">
			<h1 class="entry-title" itemprop="headline"><?php if ( function_exists('the_title') ) { call_user_func('the_title'); } ?></h1>
			<div class="entry-content" itemprop="articleBody"><?php if ( function_exists('the_content') ) { call_user_func('the_content'); } ?></div>
		</article>
	<?php endwhile; else: ?>
		<p><?php echo function_exists('esc_html__') ? call_user_func('esc_html__', 'Nothing found.', 'aqualuxe') : 'Nothing found.'; ?></p>
	<?php endif; ?>
</main>
<?php if ( function_exists('get_footer') ) { call_user_func('get_footer'); }
