<?php
namespace AquaLuxe\Shortcodes;

// Register a private CPT for storing submissions
\add_action('init', function(){
    \register_post_type('aqlx_submission', [
        'labels' => [ 'name' => \__('Submissions', 'aqualuxe') ],
        'public' => false,
        'show_ui' => true,
        'supports' => ['title', 'custom-fields'],
    ]);
});

// Services grid [aqualuxe_services grid="3"]
\add_shortcode('aqualuxe_services', function($atts){
    $a = \shortcode_atts(['grid' => 3, 'count' => 9], $atts, 'aqualuxe_services');
    $q = new \WP_Query(['post_type' => 'service', 'posts_per_page' => (int) $a['count']]);
    ob_start();
    echo '<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-' . (int) $a['grid'] . '">';
    while ($q->have_posts()) { $q->the_post();
        echo '<article class="border rounded p-4">';
        echo '<h3 class="font-semibold text-lg"><a href="' . \esc_url(\get_permalink()) . '">' . \esc_html(\get_the_title()) . '</a></h3>';
        echo '<div class="prose">' . \wp_kses_post(\get_the_excerpt()) . '</div>';
        echo '</article>';
    }
    echo '</div>';
    \wp_reset_postdata();
    return (string) ob_get_clean();
});

// Events list [aqualuxe_upcoming_events count="6"]
\add_shortcode('aqualuxe_upcoming_events', function($atts){
    $a = \shortcode_atts(['count' => 6], $atts, 'aqualuxe_upcoming_events');
    $q = new \WP_Query(['post_type' => 'event', 'posts_per_page' => (int) $a['count']]);
    ob_start();
    echo '<ul class="space-y-4">';
    while ($q->have_posts()) { $q->the_post();
        echo '<li><a class="underline" href="' . \esc_url(\get_permalink()) . '">' . \esc_html(\get_the_title()) . '</a></li>';
    }
    echo '</ul>';
    \wp_reset_postdata();
    return (string) ob_get_clean();
});

// Featured products [aqualuxe_featured_products limit="8"] (requires Woo)
\add_shortcode('aqualuxe_featured_products', function($atts){
    $a = \shortcode_atts(['limit' => 8], $atts, 'aqualuxe_featured_products');
    if (!\class_exists('WooCommerce')) { return ''; }
    return \do_shortcode('[products limit="' . (int) $a['limit'] . '" visibility="featured"]');
});

// Wholesale form [aqualuxe_wholesale_form]
\add_shortcode('aqualuxe_wholesale_form', function(){
    $action = \esc_url(\admin_url('admin-post.php'));
    $nonce = \wp_create_nonce('aqlx_wholesale');
    ob_start(); ?>
    <form class="space-y-4" method="post" action="<?php echo $action; ?>">
      <input type="hidden" name="action" value="aqlx_wholesale_apply" />
      <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>" />
      <div><label>Company Name<input class="border p-2 w-full" name="company" required></label></div>
      <div><label>Contact Name<input class="border p-2 w-full" name="contact" required></label></div>
      <div><label>Email<input class="border p-2 w-full" type="email" name="email" required></label></div>
      <div><label>Phone<input class="border p-2 w-full" name="phone"></label></div>
      <div><label>Website<input class="border p-2 w-full" name="website"></label></div>
      <div><label>Country<input class="border p-2 w-full" name="country" required></label></div>
      <div><label>Message<textarea class="border p-2 w-full" name="message" rows="4"></textarea></label></div>
      <button class="btn btn-primary" type="submit"><?php echo esc_html__('Apply for Wholesale', 'aqualuxe'); ?></button>
    </form>
    <?php return (string) ob_get_clean();
});

// Trade-in form [aqualuxe_tradein_form]
\add_shortcode('aqualuxe_tradein_form', function(){
    $action = \esc_url(\admin_url('admin-post.php'));
    $nonce = \wp_create_nonce('aqlx_tradein');
    ob_start(); ?>
    <form class="space-y-4" method="post" action="<?php echo $action; ?>">
      <input type="hidden" name="action" value="aqlx_tradein_submit" />
      <input type="hidden" name="_wpnonce" value="<?php echo esc_attr($nonce); ?>" />
      <div><label>Your Name<input class="border p-2 w-full" name="name" required></label></div>
      <div><label>Email<input class="border p-2 w-full" type="email" name="email" required></label></div>
      <div><label>Item Details<textarea class="border p-2 w-full" name="details" rows="4" required></textarea></label></div>
      <button class="btn btn-secondary" type="submit"><?php echo esc_html__('Submit Trade-In', 'aqualuxe'); ?></button>
    </form>
    <?php return (string) ob_get_clean();
});

// Handlers for submissions
\add_action('admin_post_nopriv_aqlx_wholesale_apply', __NAMESPACE__ . '\\handle_wholesale');
\add_action('admin_post_aqlx_wholesale_apply', __NAMESPACE__ . '\\handle_wholesale');
\add_action('admin_post_nopriv_aqlx_tradein_submit', __NAMESPACE__ . '\\handle_tradein');
\add_action('admin_post_aqlx_tradein_submit', __NAMESPACE__ . '\\handle_tradein');

function handle_wholesale(): void
{
    if (!\wp_verify_nonce($_POST['_wpnonce'] ?? '', 'aqlx_wholesale')) { \wp_die('Bad request'); }
    $data = [
        'company' => \sanitize_text_field($_POST['company'] ?? ''),
        'contact' => \sanitize_text_field($_POST['contact'] ?? ''),
        'email'   => \sanitize_email($_POST['email'] ?? ''),
        'phone'   => \sanitize_text_field($_POST['phone'] ?? ''),
        'website' => \esc_url_raw($_POST['website'] ?? ''),
        'country' => \sanitize_text_field($_POST['country'] ?? ''),
        'message' => \sanitize_textarea_field($_POST['message'] ?? ''),
    ];
    $post_id = \wp_insert_post([
        'post_type' => 'aqlx_submission',
        'post_status' => 'publish',
        'post_title' => 'Wholesale: ' . $data['company'] . ' - ' . gmdate('c'),
        'meta_input' => $data,
    ]);
    $redirect = \wp_get_referer() ?: \home_url('/');
    \wp_safe_redirect(\add_query_arg(['submitted' => $post_id ? 1 : 0], $redirect));
    exit;
}

function handle_tradein(): void
{
    if (!\wp_verify_nonce($_POST['_wpnonce'] ?? '', 'aqlx_tradein')) { \wp_die('Bad request'); }
    $data = [
        'name'   => \sanitize_text_field($_POST['name'] ?? ''),
        'email'  => \sanitize_email($_POST['email'] ?? ''),
        'details'=> \sanitize_textarea_field($_POST['details'] ?? ''),
    ];
    $post_id = \wp_insert_post([
        'post_type' => 'aqlx_submission',
        'post_status' => 'publish',
        'post_title' => 'Trade-in: ' . $data['name'] . ' - ' . gmdate('c'),
        'meta_input' => $data,
    ]);
    $redirect = \wp_get_referer() ?: \home_url('/');
    \wp_safe_redirect(\add_query_arg(['submitted' => $post_id ? 1 : 0], $redirect));
    exit;
}
