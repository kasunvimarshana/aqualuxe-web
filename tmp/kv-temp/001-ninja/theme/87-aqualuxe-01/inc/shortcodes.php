<?php
namespace AquaLuxe\Shortcodes;

// Register a private CPT for storing submissions
if (\function_exists('add_action')) {
    \add_action('init', function(){
        \register_post_type('aqlx_submission', [
            'labels' => [ 'name' => (\function_exists('__') ? \call_user_func('__', 'Submissions', 'aqualuxe') : 'Submissions') ],
            'public' => false,
            'show_ui' => true,
            'supports' => ['title', 'custom-fields'],
        ]);
    });
}

// Services grid [aqualuxe_services grid="3"]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_services', function($atts){
    $a = \shortcode_atts(['grid' => 3, 'count' => 9], $atts, 'aqualuxe_services');
    $key = 'aqlx_sc_services_' . md5(json_encode([$a['grid'], (int) $a['count'], (int) (\get_queried_object_id() ?: 0)]));
    return \AquaLuxe\fragment_cache($key, 300, function() use ($a) {
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
}); }

// Events list [aqualuxe_upcoming_events count="6"]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_upcoming_events', function($atts){
    $a = \shortcode_atts(['count' => 6], $atts, 'aqualuxe_upcoming_events');
    $key = 'aqlx_sc_events_' . md5((string) (int) $a['count']);
    return \AquaLuxe\fragment_cache($key, 300, function() use ($a) {
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
}); }

// (Cache invalidation moved to performance module to keep shortcode file purely presentational.)

// Featured products [aqualuxe_featured_products limit="8"] (requires Woo)
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_featured_products', function($atts){
    $a = \shortcode_atts(['limit' => 8], $atts, 'aqualuxe_featured_products');
    if (!\class_exists('WooCommerce')) { return ''; }
    return \do_shortcode('[products limit="' . (int) $a['limit'] . '" visibility="featured"]');
}); }

// Language switcher [aqualuxe_language_switcher]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_language_switcher', function(){
    // Try Polylang
    if (\function_exists('pll_the_languages')) {
        $langs = \call_user_func('pll_the_languages', ['raw' => 1]);
        if (is_array($langs) && !empty($langs)) {
            ob_start();
            echo '<nav aria-label="Language" class="language-switcher">';
            echo '<ul class="flex items-center gap-3">';
            foreach ($langs as $code => $l) {
                $active = !empty($l['current_lang']);
                $class = $active ? 'font-semibold underline' : 'opacity-80 hover:opacity-100 underline';
                echo '<li><a class="' . \esc_attr($class) . '" hreflang="' . \esc_attr($code) . '" href="' . \esc_url($l['url']) . '">' . \esc_html($l['name']) . '</a></li>';
            }
            echo '</ul></nav>';
            return (string) ob_get_clean();
        }
    }
    // Try WPML
    if (\function_exists('icl_get_languages')) {
        $langs = \call_user_func('icl_get_languages', 'skip_missing=0');
        if (is_array($langs) && !empty($langs)) {
            ob_start();
            echo '<nav aria-label="Language" class="language-switcher">';
            echo '<ul class="flex items-center gap-3">';
            foreach ($langs as $l) {
                $active = !empty($l['active']);
                $class = $active ? 'font-semibold underline' : 'opacity-80 hover:opacity-100 underline';
                echo '<li><a class="' . \esc_attr($class) . '" hreflang="' . \esc_attr($l['language_code']) . '" href="' . \esc_url($l['url']) . '">' . \esc_html($l['native_name'] ?? $l['translated_name'] ?? $l['language_code']) . '</a></li>';
            }
            echo '</ul></nav>';
            return (string) ob_get_clean();
        }
    }
    // Fallback to site language display only
    $locale = \get_locale();
    $short = strtoupper(explode('_', $locale)[0] ?? $locale);
    return '<span class="language-switcher text-sm opacity-80" aria-label="Language">' . \esc_html($short) . '</span>';
}); }

// Newsletter subscribe form [aqualuxe_subscribe_form]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_subscribe_form', function(){
    $action = \esc_url(\admin_url('admin-post.php'));
    $nonce = \wp_create_nonce('aqlx_subscribe');
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
    if (!\wp_verify_nonce($_POST['_wpnonce'] ?? '', 'aqlx_subscribe')) { \wp_die('Bad request'); }
    $email = \sanitize_email($_POST['email'] ?? '');
    if (!\is_email($email)) {
        $redirect = \wp_get_referer() ?: \home_url('/');
        \wp_safe_redirect(\add_query_arg(['subscribed' => 0], $redirect));
        exit;
    }
    $post_id = \wp_insert_post([
        'post_type' => 'aqlx_submission',
        'post_status' => 'publish',
        'post_title' => 'Subscribe: ' . $email . ' - ' . gmdate('c'),
        'meta_input' => [ 'email' => $email, 'source' => 'newsletter' ],
    ]);
    $redirect = \wp_get_referer() ?: \home_url('/');
    \wp_safe_redirect(\add_query_arg(['subscribed' => $post_id ? 1 : 0], $redirect));
    exit;
}

// Currency switcher [aqualuxe_currency_switcher]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_currency_switcher', function(){
        $allowed = \apply_filters('aqualuxe/currency/allowed', ['USD','EUR','GBP']);
        $current = \apply_filters('aqualuxe/currency/current', 'USD');
        $action = (\function_exists('home_url') && \function_exists('add_query_arg') && \function_exists('esc_url')) ? \esc_url(\home_url(\add_query_arg([]))) : '#';
        ob_start(); ?>
        <form method="get" action="<?php echo $action; ?>" class="inline-flex items-center gap-2">
            <label for="aqlx-currency" class="sr-only"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Currency', 'aqualuxe') : 'Currency'); ?></label>
            <select id="aqlx-currency" name="currency" class="border rounded px-2 py-1" aria-label="<?php echo (\function_exists('esc_attr__') ? \call_user_func('esc_attr__', 'Currency', 'aqualuxe') : 'Currency'); ?>">
                <?php foreach ($allowed as $c): ?>
                    <option value="<?php echo (\function_exists('esc_attr') ? \call_user_func('esc_attr', $c) : $c); ?>"<?php echo ($current === $c ? ' selected' : ''); ?>><?php echo (\function_exists('esc_html') ? \call_user_func('esc_html', $c) : $c); ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-secondary" type="submit"><?php echo (\function_exists('esc_html__') ? \call_user_func('esc_html__', 'Set', 'aqualuxe') : 'Set'); ?></button>
        </form>
        <?php return (string) ob_get_clean();
}); }

// Wholesale form [aqualuxe_wholesale_form]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_wholesale_form', function(){
    $action = \esc_url(\admin_url('admin-post.php'));
    $nonce = \wp_create_nonce('aqlx_wholesale');
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
    $action = \esc_url(\admin_url('admin-post.php'));
    $nonce = \wp_create_nonce('aqlx_tradein');
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
    $a = \shortcode_atts([
        'address' => 'AquaLuxe HQ',
        'email_to' => \get_option('admin_email'),
    ], $atts, 'aqualuxe_contact');
        $mapSrc = 'https://www.google.com/maps?q=' . rawurlencode((string) $a['address']) . '&output=embed';
        $action = \esc_url(\admin_url('admin-post.php'));
        $nonce = \wp_create_nonce('aqlx_contact');
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
        if (!\wp_verify_nonce($_POST['_wpnonce'] ?? '', 'aqlx_contact')) { \wp_die('Bad request'); }
        $name = \sanitize_text_field($_POST['name'] ?? '');
        $email = \sanitize_email($_POST['email'] ?? '');
        $message = \sanitize_textarea_field($_POST['message'] ?? '');
        // Store as submission post
        $post_id = \wp_insert_post([
                'post_type' => 'aqlx_submission',
                'post_status' => 'publish',
                'post_title' => 'Contact: ' . $name . ' - ' . gmdate('c'),
                'meta_input' => compact('email','message'),
        ]);
        // Optional: Email the admin
        try {
                if (\is_email(get_option('admin_email'))) {
                        \wp_mail(get_option('admin_email'), 'AquaLuxe Contact: ' . $name, $message . "\n\nFrom: " . $email);
                }
        } catch (\Throwable $e) {}
        $redirect = \wp_get_referer() ?: \home_url('/');
        \wp_safe_redirect(\add_query_arg(['sent' => $post_id ? 1 : 0], $redirect));
        exit;
}

// Testimonials slider/grid [aqualuxe_testimonials count="6"]
if (\function_exists('add_shortcode')) { \add_shortcode('aqualuxe_testimonials', function($atts){
    $a = \shortcode_atts(['count' => 6], $atts, 'aqualuxe_testimonials');
    $q = new \WP_Query(['post_type' => 'testimonial', 'posts_per_page' => (int) $a['count']]);
    if (!$q->have_posts()) { return ''; }
    ob_start();
    echo '<div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">';
    while ($q->have_posts()) { $q->the_post();
        $name = \esc_html(\get_the_title());
    $excerpt = \wp_kses_post(\get_the_excerpt() ?: \wp_trim_words(\wp_strip_all_tags(\get_the_content('')), 24));
        $avatar = \get_the_post_thumbnail(\get_the_ID(), 'thumbnail', ['class' => 'w-12 h-12 rounded-full object-cover']);
        echo '<figure class="rounded-lg border border-slate-200 dark:border-slate-800 p-5 bg-white/60 dark:bg-slate-900/60">';
        echo '<div class="flex items-center gap-3 mb-3">' . ($avatar ? $avatar : '') . '<figcaption class="font-semibold">' . $name . '</figcaption></div>';
        echo '<blockquote class="italic opacity-90">' . $excerpt . '</blockquote>';
        echo '</figure>';
    }
    echo '</div>';
    \wp_reset_postdata();
    return (string) ob_get_clean();
}); }

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
