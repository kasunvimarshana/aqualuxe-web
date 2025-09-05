<?php
namespace AquaLuxe\Shortcodes;

// Register a private CPT for storing submissions
if (\function_exists('add_action')) {
    \add_action('init', function(){
        if (\function_exists('register_post_type')) {
            \call_user_func('register_post_type', 'aqlx_submission', [
                'labels' => [ 'name' => (\function_exists('__') ? \call_user_func('__', 'Submissions', 'aqualuxe') : 'Submissions') ],
                'public' => false,
                'show_ui' => true,
                'supports' => ['title', 'custom-fields'],
            ]);
        }
    });
}

// Services grid [aqualuxe_services grid="3"]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_services', function($atts){
    $defaults = ['grid' => 3, 'count' => 9];
    $a = (\function_exists('shortcode_atts') ? \call_user_func('shortcode_atts', $defaults, $atts, 'aqualuxe_services') : (is_array($atts) ? array_merge($defaults, $atts) : $defaults));
    $qobj = (\function_exists('get_queried_object_id') ? (int) \call_user_func('get_queried_object_id') : 0);
    $key = 'aqlx_sc_services_' . md5(json_encode([$a['grid'], (int) $a['count'], $qobj]));
    return \AquaLuxe\fragment_cache($key, 300, function() use ($a) {
        $posts = (\function_exists('get_posts') ? \call_user_func('get_posts', ['post_type' => 'service', 'numberposts' => (int) $a['count']]) : []);
        ob_start();
        echo '<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-' . (int) $a['grid'] . '">';
        foreach ($posts as $p) {
            $permalink = (\function_exists('get_permalink') ? \call_user_func('get_permalink', $p) : '#');
            $title     = (\function_exists('get_the_title') ? \call_user_func('get_the_title', $p) : '');
            $excerpt   = (\function_exists('get_the_excerpt') ? \call_user_func('get_the_excerpt', $p) : '');
            $permalink = (\function_exists('esc_url') ? \call_user_func('esc_url', $permalink) : $permalink);
            $title     = (\function_exists('esc_html') ? \call_user_func('esc_html', $title) : htmlspecialchars((string) $title));
            $excerpt   = (\function_exists('wp_kses_post') ? \call_user_func('wp_kses_post', $excerpt) : htmlspecialchars((string) $excerpt));
            echo '<article class="border rounded p-4">';
            echo '<h3 class="font-semibold text-lg"><a href="' . $permalink . '">' . $title . '</a></h3>';
            echo '<div class="prose">' . $excerpt . '</div>';
            echo '</article>';
        }
        echo '</div>';
        return (string) ob_get_clean();
    });
}); }

// Events list [aqualuxe_upcoming_events count="6"]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_upcoming_events', function($atts){
    $defaults = ['count' => 6];
    $a = (\function_exists('shortcode_atts') ? \call_user_func('shortcode_atts', $defaults, $atts, 'aqualuxe_upcoming_events') : (is_array($atts) ? array_merge($defaults, $atts) : $defaults));
    $key = 'aqlx_sc_events_' . md5((string) (int) $a['count']);
    return \AquaLuxe\fragment_cache($key, 300, function() use ($a) {
        $posts = (\function_exists('get_posts') ? \call_user_func('get_posts', ['post_type' => 'event', 'numberposts' => (int) $a['count']]) : []);
        ob_start();
        echo '<ul class="space-y-4">';
        foreach ($posts as $p) {
            $permalink = (\function_exists('get_permalink') ? \call_user_func('get_permalink', $p) : '#');
            $title     = (\function_exists('get_the_title') ? \call_user_func('get_the_title', $p) : '');
            $permalink = (\function_exists('esc_url') ? \call_user_func('esc_url', $permalink) : $permalink);
            $title     = (\function_exists('esc_html') ? \call_user_func('esc_html', $title) : htmlspecialchars((string) $title));
            echo '<li><a class="underline" href="' . $permalink . '">' . $title . '</a></li>';
        }
        echo '</ul>';
        return (string) ob_get_clean();
    });
}); }

// (Cache invalidation moved to performance module to keep shortcode file purely presentational.)

// Featured products [aqualuxe_featured_products limit="8"] (requires Woo)
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_featured_products', function($atts){
    $defaults = ['limit' => 8];
    $a = (\function_exists('shortcode_atts') ? \call_user_func('shortcode_atts', $defaults, $atts, 'aqualuxe_featured_products') : (is_array($atts) ? array_merge($defaults, $atts) : $defaults));
    if (!\class_exists('WooCommerce')) { return ''; }
    $sc = '[products limit="' . (int) $a['limit'] . '" visibility="featured"]';
    return (\function_exists('do_shortcode') ? \call_user_func('do_shortcode', $sc) : '');
}); }

// Language switcher is provided by modules/multilingual; no registration here to avoid duplicates.

// Newsletter subscribe form [aqualuxe_subscribe_form]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_subscribe_form', function(){
    $action = (\function_exists('admin_url') ? \call_user_func('admin_url', 'admin-post.php') : '#');
    $action = (\function_exists('esc_url') ? \call_user_func('esc_url', $action) : $action);
    $nonce = (\function_exists('wp_create_nonce') ? \call_user_func('wp_create_nonce', 'aqlx_subscribe') : '');
    $ok = isset($_GET['subscribed']) ? (int) $_GET['subscribed'] : null;
    ob_start(); ?>
        <form class="space-y-3 max-w-md" method="post" action="<?php echo $action; ?>" aria-describedby="aqlx-subscribe-help">
                    <div id="aqlx-subscribe-status" role="status" aria-live="polite">
                        <?php if ($ok === 1): ?>
                            <div id="aqlx-subscribe-msg" class="text-green-700 bg-green-50 border border-green-200 rounded p-2"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Thanks for subscribing!','aqualuxe') : 'Thanks for subscribing!'; ?></div>
                        <?php elseif ($ok === 0): ?>
                            <div id="aqlx-subscribe-msg" class="text-red-700 bg-red-50 border border-red-200 rounded p-2"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Subscription failed. Please try again.','aqualuxe') : 'Subscription failed. Please try again.'; ?></div>
                        <?php endif; ?>
                    </div>
      <input type="hidden" name="action" value="aqlx_subscribe_submit" />
    <input type="hidden" name="_wpnonce" value="<?php echo function_exists('esc_attr') ? call_user_func('esc_attr', $nonce) : $nonce; ?>" />
            <div class="flex gap-2 items-end">
                <div class="flex-1">
                      <label class="sr-only" for="aqlx-subscribe-email"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Email','aqualuxe') : 'Email'; ?></label>
                    <input id="aqlx-subscribe-email" class="border p-2 w-full rounded" type="email" name="email" placeholder="you@example.com" required aria-describedby="aqlx-subscribe-help" autocomplete="email" />
                      <p id="aqlx-subscribe-help" class="text-xs opacity-70 mt-1"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','We respect your privacy. Unsubscribe anytime.','aqualuxe') : 'We respect your privacy. Unsubscribe anytime.'; ?></p>
                </div>
            <button class="btn btn-primary" type="submit"><?php echo function_exists('esc_html__') ? call_user_func('esc_html__','Subscribe','aqualuxe') : 'Subscribe'; ?></button>
            </div>
    </form>
    <?php return (string) ob_get_clean();
}); }

if (\function_exists('add_action')) {
    \add_action('admin_post_nopriv_aqlx_subscribe_submit', __NAMESPACE__ . '\\handle_subscribe');
    \add_action('admin_post_aqlx_subscribe_submit', __NAMESPACE__ . '\\handle_subscribe');
}

function handle_subscribe(): void
{
    if (\function_exists('wp_verify_nonce')) {
        if (!\call_user_func('wp_verify_nonce', $_POST['_wpnonce'] ?? '', 'aqlx_subscribe')) { \function_exists('wp_die') ? \call_user_func('wp_die', 'Bad request') : exit; }
    }
    $email = (\function_exists('sanitize_email') ? \call_user_func('sanitize_email', $_POST['email'] ?? '') : ($_POST['email'] ?? ''));
    $valid = (\function_exists('is_email') ? \call_user_func('is_email', $email) : (bool) $email);
    if (!$valid) {
        $redirect = (\function_exists('wp_get_referer') ? \call_user_func('wp_get_referer') : null) ?: (\function_exists('home_url') ? \call_user_func('home_url', '/') : '/');
        $redir = (\function_exists('add_query_arg') ? \call_user_func('add_query_arg', ['subscribed' => 0], $redirect) : $redirect);
        if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $redir); }
        exit;
    }
    if (\function_exists('wp_insert_post')) {
        $post_id = \call_user_func('wp_insert_post', [
            'post_type' => 'aqlx_submission',
            'post_status' => 'publish',
            'post_title' => 'Subscribe: ' . $email . ' - ' . gmdate('c'),
            'meta_input' => [ 'email' => $email, 'source' => 'newsletter' ],
        ]);
    } else { $post_id = 1; }
    $redirect = (\function_exists('wp_get_referer') ? \call_user_func('wp_get_referer') : null) ?: (\function_exists('home_url') ? \call_user_func('home_url', '/') : '/');
    $redir = (\function_exists('add_query_arg') ? \call_user_func('add_query_arg', ['subscribed' => ($post_id ? 1 : 0)], $redirect) : $redirect);
    if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $redir); }
    exit;
}

// Currency switcher is provided by modules/multicurrency; no registration here to avoid duplicates.

// Wholesale form [aqualuxe_wholesale_form]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_wholesale_form', function(){
    $action = (\function_exists('admin_url') ? \call_user_func('admin_url', 'admin-post.php') : '#');
    $action = (\function_exists('esc_url') ? \call_user_func('esc_url', $action) : $action);
    $nonce = (\function_exists('wp_create_nonce') ? \call_user_func('wp_create_nonce', 'aqlx_wholesale') : '');
        $ok = isset($_GET['submitted']) ? (int) $_GET['submitted'] : null;
    ob_start(); ?>
        <form class="space-y-4" method="post" action="<?php echo $action; ?>" aria-describedby="aqlx-wholesale-help">
                    <div id="aqlx-wholesale-status" role="status" aria-live="polite">
                        <?php if ($ok === 1): ?>
                            <div class="text-green-700 bg-green-50 border border-green-200 rounded p-2"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Application submitted. We will get back to you soon.', 'aqualuxe') : 'Application submitted. We will get back to you soon.'); ?></div>
                        <?php elseif ($ok === 0): ?>
                            <div class="text-red-700 bg-red-50 border border-red-200 rounded p-2"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Submission failed. Please try again.', 'aqualuxe') : 'Submission failed. Please try again.'); ?></div>
                        <?php endif; ?>
                    </div>
      <input type="hidden" name="action" value="aqlx_wholesale_apply" />
      <input type="hidden" name="_wpnonce" value="<?php echo (\function_exists('esc_attr') ? \call_user_func('esc_attr', $nonce) : $nonce); ?>" />
            <div>
            <label for="aqlx-wh-company"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Company Name', 'aqualuxe') : 'Company Name'); ?></label>
                <input id="aqlx-wh-company" class="border p-2 w-full" name="company" required autocomplete="organization" aria-describedby="aqlx-wholesale-help">
            </div>
            <div>
            <label for="aqlx-wh-contact"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Contact Name', 'aqualuxe') : 'Contact Name'); ?></label>
                <input id="aqlx-wh-contact" class="border p-2 w-full" name="contact" required autocomplete="name" aria-describedby="aqlx-wholesale-help">
            </div>
            <div>
            <label for="aqlx-wh-email"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Email', 'aqualuxe') : 'Email'); ?></label>
                <input id="aqlx-wh-email" class="border p-2 w-full" type="email" name="email" required autocomplete="email" aria-describedby="aqlx-wholesale-help">
            </div>
            <div>
            <label for="aqlx-wh-phone"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Phone', 'aqualuxe') : 'Phone'); ?></label>
                <input id="aqlx-wh-phone" class="border p-2 w-full" name="phone" autocomplete="tel" aria-describedby="aqlx-wholesale-help">
            </div>
            <div>
            <label for="aqlx-wh-website"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Website', 'aqualuxe') : 'Website'); ?></label>
                <input id="aqlx-wh-website" class="border p-2 w-full" name="website" inputmode="url" autocomplete="url" aria-describedby="aqlx-wholesale-help">
            </div>
            <div>
            <label for="aqlx-wh-country"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Country', 'aqualuxe') : 'Country'); ?></label>
                <input id="aqlx-wh-country" class="border p-2 w-full" name="country" required autocomplete="country-name" aria-describedby="aqlx-wholesale-help">
            </div>
            <div>
            <label for="aqlx-wh-message"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Message', 'aqualuxe') : 'Message'); ?></label>
                <textarea id="aqlx-wh-message" class="border p-2 w-full" name="message" rows="4" aria-describedby="aqlx-wholesale-help"></textarea>
            </div>
            <p id="aqlx-wholesale-help" class="text-xs opacity-70"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'We’ll review your application and respond via email.', 'aqualuxe') : 'We’ll review your application and respond via email.'); ?></p>
    <button class="btn btn-primary" type="submit"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Apply for Wholesale', 'aqualuxe') : 'Apply for Wholesale'); ?></button>
    </form>
    <?php return (string) ob_get_clean();
}); }

// Trade-in form [aqualuxe_tradein_form]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_tradein_form', function(){
    $action = (\function_exists('admin_url') ? \call_user_func('admin_url', 'admin-post.php') : '#');
    $action = (\function_exists('esc_url') ? \call_user_func('esc_url', $action) : $action);
    $nonce = (\function_exists('wp_create_nonce') ? \call_user_func('wp_create_nonce', 'aqlx_tradein') : '');
        $ok = isset($_GET['submitted']) ? (int) $_GET['submitted'] : null;
    ob_start(); ?>
        <form class="space-y-4" method="post" action="<?php echo $action; ?>">
                    <div id="aqlx-tradein-status" role="status" aria-live="polite">
                        <?php if ($ok === 1): ?>
                            <div class="text-green-700 bg-green-50 border border-green-200 rounded p-2"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Trade-in submitted. We will contact you.', 'aqualuxe') : 'Trade-in submitted. We will contact you.'); ?></div>
                        <?php elseif ($ok === 0): ?>
                            <div class="text-red-700 bg-red-50 border border-red-200 rounded p-2"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Submission failed. Please try again.', 'aqualuxe') : 'Submission failed. Please try again.'); ?></div>
                        <?php endif; ?>
                    </div>
      <input type="hidden" name="action" value="aqlx_tradein_submit" />
      <input type="hidden" name="_wpnonce" value="<?php echo (\function_exists('esc_attr') ? \call_user_func('esc_attr', $nonce) : $nonce); ?>" />
            <div>
            <label for="aqlx-ti-name"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Your Name', 'aqualuxe') : 'Your Name'); ?></label>
                <input id="aqlx-ti-name" class="border p-2 w-full" name="name" required autocomplete="name">
            </div>
            <div>
            <label for="aqlx-ti-email"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Email', 'aqualuxe') : 'Email'); ?></label>
                <input id="aqlx-ti-email" class="border p-2 w-full" type="email" name="email" required autocomplete="email">
            </div>
            <div>
            <label for="aqlx-ti-details"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Item Details', 'aqualuxe') : 'Item Details'); ?></label>
                <textarea id="aqlx-ti-details" class="border p-2 w-full" name="details" rows="4" required></textarea>
            </div>
    <button class="btn btn-secondary" type="submit"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Submit Trade-In', 'aqualuxe') : 'Submit Trade-In'); ?></button>
    </form>
    <?php return (string) ob_get_clean();
}); }

// Handlers for submissions
if (\function_exists('add_action')) {
    \add_action('admin_post_nopriv_aqlx_wholesale_apply', __NAMESPACE__ . '\\handle_wholesale');
    \add_action('admin_post_aqlx_wholesale_apply', __NAMESPACE__ . '\\handle_wholesale');
    \add_action('admin_post_nopriv_aqlx_tradein_submit', __NAMESPACE__ . '\\handle_tradein');
    \add_action('admin_post_aqlx_tradein_submit', __NAMESPACE__ . '\\handle_tradein');
}

// Contact section [aqualuxe_contact address="..." email-to="..."] with map + form
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_contact', function($atts){
    $defaults = [
        'address' => 'AquaLuxe HQ',
        'email_to' => (\function_exists('get_option') ? \call_user_func('get_option', 'admin_email') : ''),
    ];
    $a = (\function_exists('shortcode_atts') ? \call_user_func('shortcode_atts', $defaults, $atts, 'aqualuxe_contact') : (is_array($atts) ? array_merge($defaults, $atts) : $defaults));
        $mapSrc = 'https://www.google.com/maps?q=' . rawurlencode((string) $a['address']) . '&output=embed';
        $action = (\function_exists('admin_url') ? \call_user_func('admin_url', 'admin-post.php') : '#');
        $action = (\function_exists('esc_url') ? \call_user_func('esc_url', $action) : $action);
        $nonce = (\function_exists('wp_create_nonce') ? \call_user_func('wp_create_nonce', 'aqlx_contact') : '');
                $ok = isset($_GET['sent']) ? (int) $_GET['sent'] : null;
        ob_start(); ?>
        <div class="grid md:grid-cols-2 gap-8 items-start">
            <div>
                <?php $mapSrcEsc = (\function_exists('esc_url') ? \call_user_func('esc_url', $mapSrc) : $mapSrc); ?>
                <div class="aspect-video rounded overflow-hidden border"><iframe title="Map" src="<?php echo $mapSrcEsc; ?>" style="border:0;width:100%;height:100%" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div>
                <p class="mt-3 text-sm opacity-80"><?php echo (\function_exists('esc_html') ? \call_user_func('esc_html', $a['address']) : $a['address']); ?></p>
            </div>
            <form class="space-y-4" method="post" action="<?php echo $action; ?>">
                                <div id="aqlx-contact-status" role="status" aria-live="polite">
                                                        <?php if ($ok === 1): ?>
                                                            <div class="text-green-700 bg-green-50 border border-green-200 rounded p-2"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Message sent. Thank you!', 'aqualuxe') : 'Message sent. Thank you!'); ?></div>
                                                        <?php elseif ($ok === 0): ?>
                                                            <div class="text-red-700 bg-red-50 border border-red-200 rounded p-2"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Message failed to send. Please try again.', 'aqualuxe') : 'Message failed to send. Please try again.'); ?></div>
                                    <?php endif; ?>
                                </div>
                <input type="hidden" name="action" value="aqlx_contact_submit" />
                <input type="hidden" name="_wpnonce" value="<?php echo (\function_exists('esc_attr') ? \call_user_func('esc_attr', $nonce) : $nonce); ?>" />
                                <div>
                                    <label for="aqlx-ct-name"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Your Name', 'aqualuxe') : 'Your Name'); ?></label>
                                    <input id="aqlx-ct-name" class="border p-2 w-full" name="name" required autocomplete="name">
                                </div>
                                <div>
                                    <label for="aqlx-ct-email"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Email', 'aqualuxe') : 'Email'); ?></label>
                                    <input id="aqlx-ct-email" class="border p-2 w-full" type="email" name="email" required autocomplete="email">
                                </div>
                                <div>
                                    <label for="aqlx-ct-message"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Message', 'aqualuxe') : 'Message'); ?></label>
                                    <textarea id="aqlx-ct-message" class="border p-2 w-full" name="message" rows="4" required></textarea>
                                </div>
                <button class="btn btn-primary" type="submit"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Send Message', 'aqualuxe') : 'Send Message'); ?></button>
            </form>
        </div>
        <?php return (string) ob_get_clean();
}); }

if (\function_exists('add_action')) {
    \add_action('admin_post_nopriv_aqlx_contact_submit', __NAMESPACE__ . '\\handle_contact');
    \add_action('admin_post_aqlx_contact_submit', __NAMESPACE__ . '\\handle_contact');
}

function handle_contact(): void
{
    if (\function_exists('wp_verify_nonce')) {
        if (!\call_user_func('wp_verify_nonce', $_POST['_wpnonce'] ?? '', 'aqlx_contact')) { \function_exists('wp_die') ? \call_user_func('wp_die', 'Bad request') : exit; }
    }
    $name = (\function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['name'] ?? '') : ($_POST['name'] ?? ''));
    $email = (\function_exists('sanitize_email') ? \call_user_func('sanitize_email', $_POST['email'] ?? '') : ($_POST['email'] ?? ''));
    $message = (\function_exists('sanitize_textarea_field') ? \call_user_func('sanitize_textarea_field', $_POST['message'] ?? '') : ($_POST['message'] ?? ''));
        // Store as submission post
    if (\function_exists('wp_insert_post')) {
        $post_id = \call_user_func('wp_insert_post', [
            'post_type' => 'aqlx_submission',
            'post_status' => 'publish',
            'post_title' => 'Contact: ' . $name . ' - ' . gmdate('c'),
            'meta_input' => compact('email','message'),
        ]);
    } else { $post_id = 1; }
        // Optional: Email the admin
        try {
        $adminEmail = (\function_exists('get_option') ? \call_user_func('get_option', 'admin_email') : '');
        $isValidAdmin = (\function_exists('is_email') ? \call_user_func('is_email', $adminEmail) : false);
        if ($isValidAdmin && \function_exists('wp_mail')) {
            \call_user_func('wp_mail', $adminEmail, 'AquaLuxe Contact: ' . $name, $message . "\n\nFrom: " . $email);
        }
        } catch (\Throwable $e) {}
    $redirect = (\function_exists('wp_get_referer') ? \call_user_func('wp_get_referer') : null) ?: (\function_exists('home_url') ? \call_user_func('home_url', '/') : '/');
    $redir = (\function_exists('add_query_arg') ? \call_user_func('add_query_arg', ['sent' => $post_id ? 1 : 0], $redirect) : $redirect);
    if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $redir); }
        exit;
}

// Testimonials slider/grid [aqualuxe_testimonials count="6"]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_testimonials', function($atts){
    $defaults = ['count' => 6];
    $a = (\function_exists('shortcode_atts') ? \call_user_func('shortcode_atts', $defaults, $atts, 'aqualuxe_testimonials') : (is_array($atts) ? array_merge($defaults, $atts) : $defaults));
    $posts = (\function_exists('get_posts') ? \call_user_func('get_posts', ['post_type' => 'testimonial', 'numberposts' => (int) $a['count']]) : []);
    if (!$posts) { return ''; }
    ob_start();
    echo '<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">';
    foreach ($posts as $p) {
        $name = (\function_exists('get_the_title') ? \call_user_func('get_the_title', $p) : '');
        $name = (\function_exists('esc_html') ? \call_user_func('esc_html', $name) : htmlspecialchars((string) $name));
        $excerptRaw = (\function_exists('get_the_excerpt') ? \call_user_func('get_the_excerpt', $p) : '');
        if (!$excerptRaw && \function_exists('get_the_content')) {
            $content = \call_user_func('get_the_content', '', false, $p);
            if (\function_exists('wp_strip_all_tags') && \function_exists('wp_trim_words')) {
                $excerptRaw = \call_user_func('wp_trim_words', \call_user_func('wp_strip_all_tags', $content), 24);
            } else { $excerptRaw = substr(strip_tags((string) $content), 0, 160); }
        }
        $excerpt = (\function_exists('wp_kses_post') ? \call_user_func('wp_kses_post', $excerptRaw) : htmlspecialchars((string) $excerptRaw));
        $avatar = (\function_exists('get_the_post_thumbnail') && \function_exists('get_the_ID') ? \call_user_func('get_the_post_thumbnail', (\function_exists('get_the_ID') ? \call_user_func('get_the_ID') : 0), 'thumbnail', ['class' => 'w-12 h-12 rounded-full object-cover']) : '');
        echo '<figure class="rounded-lg border border-slate-200 dark:border-slate-800 p-5 bg-white/60 dark:bg-slate-900/60">';
        echo '<div class="flex items-center gap-3 mb-3">' . ($avatar ? $avatar : '') . '<figcaption class="font-semibold">' . $name . '</figcaption></div>';
        echo '<blockquote class="italic opacity-90">' . $excerpt . '</blockquote>';
        echo '</figure>';
    }
    echo '</div>';
    return (string) ob_get_clean();
}); }

function handle_wholesale(): void
{
    if (\function_exists('wp_verify_nonce')) {
        if (!\call_user_func('wp_verify_nonce', $_POST['_wpnonce'] ?? '', 'aqlx_wholesale')) { \function_exists('wp_die') ? \call_user_func('wp_die', 'Bad request') : exit; }
    }
    $data = [
        'company' => (\function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['company'] ?? '') : ($_POST['company'] ?? '')),
        'contact' => (\function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['contact'] ?? '') : ($_POST['contact'] ?? '')),
        'email'   => (\function_exists('sanitize_email') ? \call_user_func('sanitize_email', $_POST['email'] ?? '') : ($_POST['email'] ?? '')),
        'phone'   => (\function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['phone'] ?? '') : ($_POST['phone'] ?? '')),
        'website' => (\function_exists('esc_url_raw') ? \call_user_func('esc_url_raw', $_POST['website'] ?? '') : ($_POST['website'] ?? '')),
        'country' => (\function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['country'] ?? '') : ($_POST['country'] ?? '')),
        'message' => (\function_exists('sanitize_textarea_field') ? \call_user_func('sanitize_textarea_field', $_POST['message'] ?? '') : ($_POST['message'] ?? '')),
    ];
    if (\function_exists('wp_insert_post')) {
        $post_id = \call_user_func('wp_insert_post', [
            'post_type' => 'aqlx_submission',
            'post_status' => 'publish',
            'post_title' => 'Wholesale: ' . $data['company'] . ' - ' . gmdate('c'),
            'meta_input' => $data,
        ]);
    } else { $post_id = 1; }
    $redirect = (\function_exists('wp_get_referer') ? \call_user_func('wp_get_referer') : null) ?: (\function_exists('home_url') ? \call_user_func('home_url', '/') : '/');
    $redir = (\function_exists('add_query_arg') ? \call_user_func('add_query_arg', ['submitted' => $post_id ? 1 : 0], $redirect) : $redirect);
    if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $redir); }
    exit;
}

function handle_tradein(): void
{
    if (\function_exists('wp_verify_nonce')) {
        if (!\call_user_func('wp_verify_nonce', $_POST['_wpnonce'] ?? '', 'aqlx_tradein')) { \function_exists('wp_die') ? \call_user_func('wp_die', 'Bad request') : exit; }
    }
    $data = [
        'name'   => (\function_exists('sanitize_text_field') ? \call_user_func('sanitize_text_field', $_POST['name'] ?? '') : ($_POST['name'] ?? '')),
        'email'  => (\function_exists('sanitize_email') ? \call_user_func('sanitize_email', $_POST['email'] ?? '') : ($_POST['email'] ?? '')),
        'details'=> (\function_exists('sanitize_textarea_field') ? \call_user_func('sanitize_textarea_field', $_POST['details'] ?? '') : ($_POST['details'] ?? '')),
    ];
    if (\function_exists('wp_insert_post')) {
        $post_id = \call_user_func('wp_insert_post', [
            'post_type' => 'aqlx_submission',
            'post_status' => 'publish',
            'post_title' => 'Trade-in: ' . $data['name'] . ' - ' . gmdate('c'),
            'meta_input' => $data,
        ]);
    } else { $post_id = 1; }
    $redirect = (\function_exists('wp_get_referer') ? \call_user_func('wp_get_referer') : null) ?: (\function_exists('home_url') ? \call_user_func('home_url', '/') : '/');
    $redir = (\function_exists('add_query_arg') ? \call_user_func('add_query_arg', ['submitted' => $post_id ? 1 : 0], $redirect) : $redirect);
    if (\function_exists('wp_safe_redirect')) { \call_user_func('wp_safe_redirect', $redir); }
    exit;
}
