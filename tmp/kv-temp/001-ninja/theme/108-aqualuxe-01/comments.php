<?php
/**
 * Comments Template
 * 
 * @package AquaLuxe
 * @since 1.0.0
 */

if (post_password_required()) {
    return;
}

?>
<div id="comments" class="comments-area mt-12">
    <?php if (have_comments()) : ?>
        <h2 class="comments-title text-2xl font-bold text-gray-900 mb-8">
            <?php
            $comments_number = get_comments_number();
            if ('1' === $comments_number) {
                printf(esc_html__('One thought on &ldquo;%s&rdquo;', 'aqualuxe'), get_the_title());
            } else {
                printf(
                    esc_html(_nx('%1$s thought on &ldquo;%2$s&rdquo;', '%1$s thoughts on &ldquo;%2$s&rdquo;', $comments_number, 'comments title', 'aqualuxe')),
                    number_format_i18n($comments_number),
                    get_the_title()
                );
            }
            ?>
        </h2>

        <?php the_comments_navigation(); ?>

        <ol class="comment-list space-y-6">
            <?php
            wp_list_comments([
                'style' => 'ol',
                'short_ping' => true,
                'callback' => 'aqualuxe_comment_author',
            ]);
            ?>
        </ol>

        <?php
        the_comments_navigation();

        if (!comments_open()) :
            ?>
            <p class="no-comments text-gray-600 text-center py-8">
                <?php esc_html_e('Comments are closed.', 'aqualuxe'); ?>
            </p>
        <?php endif; ?>

    <?php endif; ?>

    <?php
    comment_form([
        'title_reply_before' => '<h3 id="reply-title" class="comment-reply-title text-xl font-semibold text-gray-900 mb-6">',
        'title_reply_after' => '</h3>',
        'class_form' => 'comment-form bg-gray-50 rounded-lg p-8',
        'class_submit' => 'btn btn-primary',
        'comment_field' => '<div class="comment-form-comment form-group"><label for="comment" class="form-label">' . esc_html__('Comment', 'aqualuxe') . ' <span class="required">*</span></label><textarea id="comment" name="comment" class="form-textarea" rows="6" required aria-describedby="comment-notes"></textarea></div>',
        'fields' => [
            'author' => '<div class="comment-form-author form-group"><label for="author" class="form-label">' . esc_html__('Name', 'aqualuxe') . ' <span class="required">*</span></label><input id="author" name="author" type="text" class="form-input" required /></div>',
            'email' => '<div class="comment-form-email form-group"><label for="email" class="form-label">' . esc_html__('Email', 'aqualuxe') . ' <span class="required">*</span></label><input id="email" name="email" type="email" class="form-input" required /></div>',
            'url' => '<div class="comment-form-url form-group"><label for="url" class="form-label">' . esc_html__('Website', 'aqualuxe') . '</label><input id="url" name="url" type="url" class="form-input" /></div>',
        ],
    ]);
    ?>
</div>