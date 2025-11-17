<?php
/** Generic content partial */
if ( ! defined( 'ABSPATH' ) ) { exit; }
?>
<article id="post-<?php the_ID(); ?>" <?php post_class( 'prose max-w-none p-4 border-b border-slate-200' ); ?>>
	<header>
		<h2 class="entry-title text-2xl font-semibold"><a href="<?php the_permalink(); ?>" class="no-underline hover:underline"><?php the_title(); ?></a></h2>
	</header>
	<div class="entry-summary">
		<?php the_excerpt(); ?>
	</div>
</article>
