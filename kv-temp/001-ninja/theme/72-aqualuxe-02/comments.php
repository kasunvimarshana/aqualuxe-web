<?php
if (post_password_required()) return;
?>
<section id="comments" class="container mx-auto px-4 py-8">
  <?php if (have_comments()): ?>
    <h2 class="text-xl font-semibold mb-4"><?php comments_number(); ?></h2>
    <ol class="space-y-4">
      <?php wp_list_comments(['style'=>'ol']); ?>
    </ol>
    <?php the_comments_navigation(); ?>
  <?php endif; ?>
  <?php comment_form(); ?>
</section>
