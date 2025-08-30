<?php
defined('ABSPATH') || exit;
if (post_password_required()) return;
?>
<div id="comments" class="comments-area mt-8">
  <?php if (have_comments()) : ?>
    <h2 class="text-xl font-semibold mb-4"><?php printf(esc_html(_n('%1$s Comment', '%1$s Comments', get_comments_number(), 'aqualuxe')), number_format_i18n(get_comments_number())); ?></h2>
    <ol class="comment-list space-y-4">
      <?php wp_list_comments(['style' => 'ol', 'avatar_size' => 48]); ?>
    </ol>
    <?php the_comments_pagination(); ?>
  <?php endif; ?>
  <?php if (!comments_open() && get_comments_number()) : ?>
    <p class="no-comments"><?php esc_html_e('Comments are closed.', 'aqualuxe'); ?></p>
  <?php endif; ?>
  <?php comment_form(); ?>
</div>
