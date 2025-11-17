<?php
declare(strict_types=1);

namespace Aqualuxe\Modules\Wishlist;

// Simple cookie-based wishlist for guests; user meta for logged-in users.
const COOKIE = 'aqlx_wishlist';

function get_list(): array {
    if (is_user_logged_in()) {
        $list = (array) get_user_meta(get_current_user_id(), '_aqlx_wishlist', true);
        return array_values(array_unique(array_map('intval', $list)));
    }
    $raw = wp_unslash($_COOKIE[COOKIE] ?? '');
    if (!$raw) return [];
    $ids = array_filter(array_map('intval', explode(',', $raw)));
    return array_values(array_unique($ids));
}

function save_list(array $ids): void {
    $ids = array_values(array_unique(array_filter(array_map('intval', $ids))));
    if (is_user_logged_in()) {
        update_user_meta(get_current_user_id(), '_aqlx_wishlist', $ids);
    } else {
        setcookie(COOKIE, implode(',', $ids), time()+3600*24*60, COOKIEPATH ?: '/');
        $_COOKIE[COOKIE] = implode(',', $ids);
    }
}

add_action('wp_ajax_aqlx_wishlist_toggle', __NAMESPACE__ . '\\ajax_toggle');
add_action('wp_ajax_nopriv_aqlx_wishlist_toggle', __NAMESPACE__ . '\\ajax_toggle');

function ajax_toggle(): void {
    check_ajax_referer('aqlx_wishlist');
    $pid = max(0, (int) ($_POST['product_id'] ?? 0));
    if (!$pid) wp_send_json_error(['message' => 'missing id'], 400);
    $list = get_list();
    if (in_array($pid, $list, true)) {
        $list = array_values(array_diff($list, [$pid]));
        $state = 'removed';
    } else {
        $list[] = $pid;
        $state = 'added';
    }
    save_list($list);
    wp_send_json_success(['state'=>$state,'ids'=>$list]);
}

// Shortcode to render wishlist button
add_shortcode('aqlx_wishlist_button', function($atts){
    $atts = shortcode_atts(['id' => get_the_ID()], $atts);
    $pid = (int) $atts['id'];
    $list = get_list();
    $in = in_array($pid, $list, true);
    $nonce = wp_create_nonce('aqlx_wishlist');
    ob_start();
        ?>
    <button class="aqlx-wishlist-btn" data-id="<?php echo esc_attr((string) $pid); ?>" data-nonce="<?php echo esc_attr($nonce); ?>" aria-pressed="<?php echo $in ? 'true' : 'false'; ?>" data-label-on="<?php echo esc_attr(__('Remove from wishlist','aqualuxe')); ?>" data-label-off="<?php echo esc_attr(__('Add to wishlist','aqualuxe')); ?>">
            <?php echo $in ? esc_html__('Remove from wishlist', 'aqualuxe') : esc_html__('Add to wishlist', 'aqualuxe'); ?>
        </button>
        <?php
    return (string) ob_get_clean();
});

// Shortcode to render wishlist list
add_shortcode('aqlx_wishlist', function(){
        $ids = get_list();
        $cta_label = esc_html__('Continue shopping', 'aqualuxe');
        $empty_label = esc_html__('Your wishlist is empty.', 'aqualuxe');
        $cta_url = home_url('/');
        if (class_exists('WooCommerce')) {
            $shop = (int) get_option('woocommerce_shop_page_id');
            if ($shop && 'page' === get_post_type($shop)) { $cta_url = get_permalink($shop); }
        }
        ob_start();
        ?>
        <div class="aqlx-wishlist-list space-y-3" data-empty-label="<?php echo esc_attr($empty_label); ?>" data-empty-url="<?php echo esc_url($cta_url); ?>" data-empty-cta="<?php echo esc_attr($cta_label); ?>">
            <?php if (!$ids): ?>
                <p><?php echo esc_html($empty_label); ?> <a class="underline" href="<?php echo esc_url($cta_url); ?>"><?php echo esc_html($cta_label); ?></a></p>
            <?php else: foreach ($ids as $pid): $p = get_post($pid); if(!$p) continue; ?>
                <div class="flex items-center justify-between border p-3 rounded" data-id="<?php echo esc_attr((string) $pid); ?>">
                    <div class="flex items-center gap-3">
                        <?php if (has_post_thumbnail($pid)) echo get_the_post_thumbnail($pid, 'thumbnail', ['class'=>'w-16 h-16 object-cover rounded']); ?>
                        <div>
                            <a href="<?php echo esc_url(get_permalink($pid)); ?>" class="font-medium"><?php echo esc_html(get_the_title($pid)); ?></a>
                            <?php if (function_exists('wc_get_product')) { $prod = call_user_func('wc_get_product', $pid); if ($prod) echo '<div class="text-sm opacity-80">'.$prod->get_price_html().'</div>'; } ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <?php if (function_exists('wc_get_product')) { $prod = call_user_func('wc_get_product', $pid); if ($prod && $prod->is_purchasable()) echo do_shortcode('[add_to_cart id="'.$pid.'" show_price="false"]'); } ?>
                        <button class="aqlx-wl-remove button" data-id="<?php echo esc_attr((string) $pid); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('aqlx_wishlist')); ?>"><?php esc_html_e('Remove', 'aqualuxe'); ?></button>
                    </div>
                </div>
            <?php endforeach; endif; ?>
        </div>
        <script>
        (function(){
            var wrap=document.currentScript.previousElementSibling; if(!wrap||!wrap.classList.contains('aqlx-wishlist-list')) return;
            wrap.addEventListener('click', function(e){
                var b=e.target.closest('.aqlx-wl-remove'); if(!b) return;
                e.preventDefault();
                var fd=new FormData(); fd.append('action','aqlx_wishlist_toggle'); fd.append('product_id', b.dataset.id); fd.append('_wpnonce', b.dataset.nonce);
                fetch((window.ajaxurl||'/wp-admin/admin-ajax.php'), {method:'POST', credentials:'same-origin', body: fd}).then(r=>r.json()).then(function(res){
                    if(res && res.success){
                        var row=b.closest('[data-id]'); if(row) row.remove();
                        // Update header wishlist badges if the API returned new ids
                        if(res.data && Array.isArray(res.data.ids)){
                            var count = res.data.ids.length;
                            document.querySelectorAll('.aqlx-wishlist-count').forEach(function(el){ el.textContent = String(count); });
                        }
                        // Empty state
                        if(!wrap.querySelector('[data-id]')){
                            var p=document.createElement('p');
                            var label = (wrap.getAttribute('data-empty-label')||'Your wishlist is empty.');
                            var url = wrap.getAttribute('data-empty-url');
                            var cta = (wrap.getAttribute('data-empty-cta')||'Continue shopping');
                            p.appendChild(document.createTextNode(label + ' '));
                            if(url){ var a=document.createElement('a'); a.href=url; a.className='underline'; a.textContent=cta; p.appendChild(a); }
                            wrap.appendChild(p);
                        }
                    }
                });
            });
        })();
        </script>
        <?php
        return (string) ob_get_clean();
});

// Shortcode: wishlist count
add_shortcode('aqlx_wishlist_count', function(){
        return (string) count(get_list());
});

// Add wishlist button in product loops (compact)
add_action('woocommerce_after_shop_loop_item', function(){
    global $product; if (!$product) return; $pid = (int) $product->get_id();
    $in = in_array($pid, get_list(), true);
    $nonce = wp_create_nonce('aqlx_wishlist');
    $label_on = esc_html__('Remove from wishlist','aqualuxe');
    $label_off = esc_html__('Add to wishlist','aqualuxe');
    echo '<button class="aqlx-wishlist-btn text-sm" data-id="'.esc_attr((string) $pid).'" data-nonce="'.esc_attr($nonce).'" aria-pressed="'.($in?'true':'false').'" data-label-on="'.esc_attr($label_on).'" data-label-off="'.esc_attr($label_off).'">'.($in?esc_html__('Wishlisted','aqualuxe'):esc_html__('Wishlist','aqualuxe')).'</button>';
}, 20);

// Footer script: delegate wishlist toggle events
add_action('wp_footer', function(){
        ?>
        <script>
        (function(){
            function toggle(btn){
                var fd=new FormData(); fd.append('action','aqlx_wishlist_toggle'); fd.append('product_id', btn.dataset.id); fd.append('_wpnonce', btn.dataset.nonce);
                                fetch((window.ajaxurl||'/wp-admin/admin-ajax.php'), {method:'POST', credentials:'same-origin', body: fd}).then(r=>r.json()).then(function(res){
                                        if(res && res.success){
                                                var on=res.data.state==='added';
                                                btn.setAttribute('aria-pressed', on?'true':'false');
                                                if(btn.tagName==='BUTTON'){
                                                    btn.textContent = on? (btn.getAttribute('data-label-on')||'Remove from wishlist') : (btn.getAttribute('data-label-off')||'Add to wishlist');
                                                }
                                                // Refresh wishlist count badges if present
                                                if(res.data && Array.isArray(res.data.ids)){
                                                    var count = res.data.ids.length;
                                                    document.querySelectorAll('.aqlx-wishlist-count').forEach(function(el){ el.textContent = String(count); });
                                                }
                                        }
                                });
            }
            document.addEventListener('click', function(e){ var b=e.target.closest('.aqlx-wishlist-btn'); if(!b) return; e.preventDefault(); toggle(b); });
        })();
        </script>
        <?php
});

// Template hook: show button on product summary if WooCommerce active
add_action('woocommerce_single_product_summary', function(){
    if (!function_exists('is_product')) return;
    echo do_shortcode('[aqlx_wishlist_button]');
}, 35);
