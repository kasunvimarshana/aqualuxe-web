<?php
namespace AquaLuxe\Modules\TradeIns;

\add_action('init', function(){
    \register_post_type('tradein', [
        'label' => \__('Trade-ins', 'aqualuxe'),
        'public' => true,
        'menu_icon' => 'dashicons-randomize',
        'supports' => ['title','editor','thumbnail','comments'],
        'has_archive' => true,
        'show_in_rest' => true,
    ]);
});

\add_shortcode('aqualuxe_tradein', function(){
    \ob_start(); ?>
    <form method="post" action="<?php echo \esc_url(\admin_url('admin-post.php')); ?>" class="grid gap-3 max-w-xl">
      <input type="hidden" name="action" value="aqualuxe_tradein" />
      <?php \wp_nonce_field('aqualuxe_tradein'); ?>
      <label><?php \esc_html_e('Item Description', 'aqualuxe'); ?><textarea class="block w-full px-3 py-2 border rounded" name="desc" rows="4" required></textarea></label>
      <label><?php \esc_html_e('Email', 'aqualuxe'); ?><input class="block w-full px-3 py-2 border rounded" type="email" name="email" required /></label>
      <button class="btn-primary"><?php \esc_html_e('Submit', 'aqualuxe'); ?></button>
    </form>
    <?php return \ob_get_clean();
});

\add_action('admin_post_nopriv_aqualuxe_tradein', __NAMESPACE__.'\handle');
\add_action('admin_post_aqualuxe_tradein', __NAMESPACE__.'\handle');
function handle(){
    \check_admin_referer('aqualuxe_tradein');
    $desc = \wp_kses_post($_POST['desc'] ?? '');
    $email = \sanitize_email($_POST['email'] ?? '');
    $id = \wp_insert_post(['post_type' => 'tradein', 'post_status' => 'pending', 'post_title' => \wp_trim_words($desc, 6), 'post_content' => $desc]);
    if ($id) \update_post_meta($id, '_email', $email);
    \wp_safe_redirect(\wp_get_referer() ?: \home_url('/'));
    exit;
}
