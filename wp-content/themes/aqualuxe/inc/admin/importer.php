<?php
namespace AquaLuxe\Admin;

class Importer
{
    public static function init(): void
    {
    \add_action('admin_menu', [__CLASS__, 'menu']);
    \add_action('admin_init', [__CLASS__, 'maybe_schedule']);
    }

    public static function menu(): void
    {
        \add_theme_page(
            \__('AquaLuxe Setup & Demo Import', 'aqualuxe'),
            \__('AquaLuxe Setup', 'aqualuxe'),
            'manage_options',
            'aqualuxe-importer',
            [__CLASS__, 'render']
        );
    }

    public static function render(): void
    {
                if (isset($_POST['aqualuxe_import'])) {
            \check_admin_referer('aqualuxe_import');
            $reset = !empty($_POST['aqualuxe_reset']);
            $log = [];
            if ($reset) {
                $log[] = self::reset_content();
            }
            $log[] = self::create_core_pages();
            $log[] = self::create_cpts();
            if (class_exists('WooCommerce')) {
                $log[] = self::create_wc_sample();
            }
            echo '<div class="notice notice-success"><p>' . \esc_html__('Import completed.', 'aqualuxe') . '</p></div>';
            echo '<pre class="bg-white p-4 border">' . \esc_html(implode("\n", array_filter($log))) . '</pre>';
        }
        ?>
        <div class="wrap">
          <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
          <p><?php esc_html_e('Run the guided setup to create demo content, menus, and settings.', 'aqualuxe'); ?></p>
                    <h2 class="title"><?php esc_html_e('Advanced Importer (Step-wise with Progress)', 'aqualuxe'); ?></h2>
                    <p class="description"><?php esc_html_e('Use the controls below for partial resets and a step-by-step importer with progress tracking.', 'aqualuxe'); ?></p>
                    <div id="aqlx-importer" class="card" style="padding:16px; max-width:920px;">
                        <div style="display:flex; gap:16px; flex-wrap:wrap;">
                            <fieldset>
                                <legend><strong><?php esc_html_e('Entities to import', 'aqualuxe'); ?></strong></legend>
                                <label><input type="checkbox" class="aqlx-entity" value="pages" checked> <?php esc_html_e('Pages & Menus', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="cpts" checked> <?php esc_html_e('CPTs (Services, Events)', 'aqualuxe'); ?></label><br>
                                <?php if (class_exists('WooCommerce')): ?>
                                <label><input type="checkbox" class="aqlx-entity" value="products" checked> <?php esc_html_e('Products (simple + variable)', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="wc_config" checked> <?php esc_html_e('WooCommerce Settings (payments, shipping)', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="media" checked> <?php esc_html_e('Media (placeholders)', 'aqualuxe'); ?></label><br>
                                <?php endif; ?>
                            </fieldset>
                            <fieldset>
                                <legend><strong><?php esc_html_e('Options', 'aqualuxe'); ?></strong></legend>
                                <label><input type="checkbox" id="aqlx-reset"> <?php esc_html_e('Reset selected entities before import', 'aqualuxe'); ?></label><br>
                                                                <label><?php esc_html_e('Volume', 'aqualuxe'); ?> <input type="number" id="aqlx-volume" min="1" max="50" value="10" style="width:80px;"></label><br>
                                                                <label><?php esc_html_e('Conflict Policy', 'aqualuxe'); ?>
                                                                    <select id="aqlx-policy">
                                                                        <option value="skip"><?php esc_html_e('Skip', 'aqualuxe'); ?></option>
                                                                        <option value="overwrite"><?php esc_html_e('Overwrite', 'aqualuxe'); ?></option>
                                                                        <option value="merge"><?php esc_html_e('Merge', 'aqualuxe'); ?></option>
                                                                    </select>
                                                                </label><br>
                                                                <label><?php esc_html_e('Locale', 'aqualuxe'); ?>
                                                                    <select id="aqlx-locale">
                                                                        <option value="en_US">en_US</option>
                                                                    </select>
                                                                </label>
                                                        </fieldset>
                                                        <fieldset>
                                                                <legend><strong><?php esc_html_e('Reset Filters (optional)', 'aqualuxe'); ?></strong></legend>
                                                                <label><?php esc_html_e('From date', 'aqualuxe'); ?> <input type="date" id="aqlx-from"></label>
                                                                <label><?php esc_html_e('To date', 'aqualuxe'); ?> <input type="date" id="aqlx-to"></label>
                            </fieldset>
                        </div>
                        <div style="margin-top:12px; display:flex; gap:8px; align-items:center;">
                            <button id="aqlx-start" class="button button-primary"><?php esc_html_e('Start Import', 'aqualuxe'); ?></button>
                            <button id="aqlx-preview" class="button"><?php esc_html_e('Preview', 'aqualuxe'); ?></button>
                            <button id="aqlx-export" class="button"><?php esc_html_e('Export Demo Content', 'aqualuxe'); ?></button>
                            <button id="aqlx-pause" class="button" title="Pause" style="margin-left:8px;">Pause</button>
                            <button id="aqlx-resume" class="button" title="Resume">Resume</button>
                            <button id="aqlx-cancel" class="button" title="Cancel" style="color:#b32d2e; border-color:#b32d2e;">Cancel</button>
                            <button id="aqlx-flush" class="button button-secondary" style="margin-left:auto; color:#b32d2e; border-color:#b32d2e;">
                                <?php esc_html_e('Flush All (Danger)', 'aqualuxe'); ?>
                            </button>
                            <a id="aqlx-audit" href="#" target="_blank" style="display:none; margin-left:8px;">View audit log</a>
                            <span id="aqlx-status" style="margin-left:8px;"></span>
                        </div>
                        <div id="aqlx-progress" style="margin-top:12px; background:#eee; height:10px; border-radius:4px; overflow:hidden;">
                            <div id="aqlx-progress-bar" style="height:100%; width:0%; background:#2271b1;"></div>
                        </div>
                        <pre id="aqlx-log" style="margin-top:12px; max-height:220px; overflow:auto; background:#fff; padding:12px; border:1px solid #ccd0d4;"></pre>
                    </div>
          <form method="post">
            <?php wp_nonce_field('aqualuxe_import'); ?>
            <label><input type="checkbox" name="aqualuxe_reset" value="1"> <?php esc_html_e('Reset (danger) — delete existing demo content before importing', 'aqualuxe'); ?></label>
            <p><button class="button button-primary" name="aqualuxe_import" value="1"><?php esc_html_e('Run Import', 'aqualuxe'); ?></button></p>
          </form>
        </div>
                <script>
                (function(){
                    const el = (id) => document.getElementById(id);
                    const status = el('aqlx-status');
                    const logEl = el('aqlx-log');
                    const bar = el('aqlx-progress-bar');
                    const auditEl = el('aqlx-audit');
                    const api = (p, method, body) => fetch((window.wpApiSettings?.root || '<?php echo esc_js( rest_url() ); ?>') + 'aqualuxe/v1' + p, {
                        method: method || 'GET',
                        headers: { 'X-WP-Nonce': window.wpApiSettings?.nonce || '<?php echo esc_js( wp_create_nonce('wp_rest') ); ?>', 'Content-Type': 'application/json' },
                        body: body ? JSON.stringify(body) : undefined
                    }).then(r => r.json());
                    const collectEntities = () => Array.from(document.querySelectorAll('.aqlx-entity:checked')).map(i => i.value);
                    function appendLog(msg){ if (!msg) return; logEl.textContent += (msg + '\n'); logEl.scrollTop = logEl.scrollHeight; }
                    function setProgress(p){ bar.style.width = Math.max(0, Math.min(100, p)) + '%'; }
                    async function run(){
                        const entities = collectEntities();
                        const reset = document.getElementById('aqlx-reset').checked;
                        const volume = parseInt(document.getElementById('aqlx-volume').value || '10', 10);
                        const policy = document.getElementById('aqlx-policy')?.value || 'skip';
                        const locale = document.getElementById('aqlx-locale')?.value || 'en_US';
                        const from = document.getElementById('aqlx-from')?.value || '';
                        const to = document.getElementById('aqlx-to')?.value || '';
                        status.textContent = 'Starting…'; setProgress(0); logEl.textContent='';
                        const start = await api('/import/start', 'POST', { entities, reset, volume, policy, locale, range:{from, to} }).catch(()=>({error:'start_failed'}));
                        if (start?.error) { status.textContent = 'Failed to start'; appendLog(start.error); return; }
                        if (start?.audit_url) { auditEl.href = start.audit_url; auditEl.style.display = 'inline'; }
                        let done = false; let progress = 0;
                        while(!done){
                            const step = await api('/import/step', 'POST', {} ).catch(()=>({error:'step_failed'}));
                            if (step?.error) { status.textContent = 'Error'; appendLog(step.error); break; }
                            if (typeof step.progress === 'number') { progress = step.progress; setProgress(progress); }
                            if (Array.isArray(step.log)) { step.log.forEach(appendLog); }
                            if (step?.audit_url) { auditEl.href = step.audit_url; auditEl.style.display = 'inline'; }
                            done = !!step.done;
                            status.textContent = done ? 'Completed' : 'Working…';
                            if (!done) { await new Promise(r => setTimeout(r, 250)); }
                        }
                    }
                    async function preview(){
                        const entities = collectEntities();
                        const volume = parseInt(document.getElementById('aqlx-volume').value || '10', 10);
                        const res = await api('/import/preview', 'POST', { entities, volume }).catch(()=>({error:'preview_failed'}));
                        if (res?.error) { appendLog(res.error); return; }
                        appendLog('Preview:');
                        appendLog(JSON.stringify(res, null, 2));
                    }
                    async function exportDemo(){
                        const entities = collectEntities();
                        const res = await api('/import/export', 'POST', { entities }).catch(()=>({error:'export_failed'}));
                        if (res?.url) { appendLog('Export ready: ' + res.url); window.open(res.url, '_blank'); }
                        else { appendLog(res?.error || 'Export failed'); }
                    }
                    async function flushAll(){
                        if (!confirm('This will delete demo content and reset state. Continue?')) return;
                        const res = await api('/import/flush', 'POST', {}).catch(()=>({error:'flush_failed'}));
                        if (res?.ok) { appendLog('Flushed.'); setProgress(0); status.textContent = 'Flushed'; }
                        else { appendLog(res?.error || 'Flush failed'); }
                    }
                    async function pauseRun(){
                        const res = await api('/import/pause', 'POST', {}).catch(()=>({error:'pause_failed'}));
                        if (res?.ok) { status.textContent = 'Paused'; appendLog('Paused.'); }
                        else { appendLog(res?.error || 'Pause failed'); }
                    }
                    async function resumeRun(){
                        const res = await api('/import/resume', 'POST', {}).catch(()=>({error:'resume_failed'}));
                        if (res?.ok) { status.textContent = 'Working…'; appendLog('Resumed.'); }
                        else { appendLog(res?.error || 'Resume failed'); }
                    }
                    async function cancelRun(){
                        if (!confirm('Cancel will rollback created items from this run and clear state. Continue?')) return;
                        const res = await api('/import/cancel', 'POST', {}).catch(()=>({error:'cancel_failed'}));
                        if (res?.ok) { status.textContent = 'Canceled'; appendLog('Canceled and rolled back.'); setProgress(0); }
                        else { appendLog(res?.error || 'Cancel failed'); }
                    }
                    document.getElementById('aqlx-start').addEventListener('click', run);
                    document.getElementById('aqlx-preview').addEventListener('click', preview);
                    document.getElementById('aqlx-export').addEventListener('click', exportDemo);
                    document.getElementById('aqlx-flush').addEventListener('click', flushAll);
                    document.getElementById('aqlx-pause').addEventListener('click', pauseRun);
                    document.getElementById('aqlx-resume').addEventListener('click', resumeRun);
                    document.getElementById('aqlx-cancel').addEventListener('click', cancelRun);
                })();
                </script>
        <?php
    }

        /** Schedule helper: allow clearing failed schedules. */
        public static function maybe_schedule(): void
        {
                if (isset($_GET['aqlx_clear_schedule']) && current_user_can('manage_options')) {
                        \wp_clear_scheduled_hook('aqlx_scheduled_reinit');
                }
        }

        public static function schedule(array $entities, bool $reset, int $volume, string $recurrence = 'daily'): array
        {
            $recurrence = in_array($recurrence, ['hourly','twicedaily','daily'], true) ? $recurrence : 'daily';
            if (\function_exists('update_option')) { \call_user_func('update_option', 'aqlx_import_schedule', compact('entities','reset','volume','recurrence'), false); }
            $hasNext = \function_exists('wp_next_scheduled') ? \call_user_func('wp_next_scheduled', 'aqlx_scheduled_reinit') : true;
            if (!$hasNext && \function_exists('wp_schedule_event')) {
                \call_user_func('wp_schedule_event', time() + 60, $recurrence, 'aqlx_scheduled_reinit');
            }
            if (\function_exists('add_action')) { \call_user_func('add_action', 'aqlx_scheduled_reinit', function(){
                $cfg = (array) (\function_exists('get_option') ? \call_user_func('get_option', 'aqlx_import_schedule', []) : []);
                if (!$cfg) return;
                // Run a fresh cycle each schedule
                self::start((array) ($cfg['entities'] ?? []), (bool) ($cfg['reset'] ?? false), (int) ($cfg['volume'] ?? 10));
                while (!(self::step()['done'] ?? false)) { /* iterate */ }
            }); }
            return ['ok' => true, 'scheduled' => true, 'recurrence' => $recurrence];
        }

    private static function reset_content(): string
    {
        $types = ['post','page','attachment','service','event','nav_menu_item'];
        if (class_exists('WooCommerce')) {
            $types[] = 'product';
            $types[] = 'product_variation';
        }
        foreach ($types as $type) {
            $q = new \WP_Query(['post_type' => $type, 'posts_per_page' => -1, 'post_status' => 'any']);
            while ($q->have_posts()) { $q->the_post(); \wp_delete_post(\get_the_ID(), true); }
            \wp_reset_postdata();
        }
        // Woo terms
        if (class_exists('WooCommerce')) {
            foreach (['product_cat','product_tag'] as $tax) {
                $terms = get_terms(['taxonomy'=>$tax,'hide_empty'=>false]);
                if (!is_wp_error($terms)) {
                    foreach ($terms as $t) { if ($t->term_id && $t->slug !== 'uncategorized') { wp_delete_term($t->term_id, $tax); } }
                }
            }
        }
        return 'Reset content for: ' . implode(', ', $types);
    }

    private static function create_core_pages(): string
    {
        $pages = [
            'Home' => ['slug' => 'home'],
            'About' => ['slug' => 'about'],
            'Services' => ['slug' => 'services'],
            'Blog' => ['slug' => 'blog'],
            'Contact' => ['slug' => 'contact'],
            'FAQ' => ['slug' => 'faq'],
            'Privacy Policy' => ['slug' => 'privacy-policy'],
            'Terms & Conditions' => ['slug' => 'terms'],
            'Shipping & Returns' => ['slug' => 'shipping-returns'],
            'Cookie Policy' => ['slug' => 'cookies'],
            'Wholesale & B2B' => ['slug' => 'wholesale', 'template' => 'page-wholesale.php', 'content' => '[aqualuxe_wholesale_form]'],
            'Buy, Sell & Trade' => ['slug' => 'trade', 'template' => 'page-trade.php', 'content' => '[aqualuxe_tradein_form]'],
        ];
        foreach ($pages as $title => $data) {
            if (!\get_page_by_path($data['slug'])) {
                \wp_insert_post([
                    'post_title' => $title,
                    'post_name'  => $data['slug'],
                    'post_type'  => 'page',
                    'post_status'=> 'publish',
                    'post_content' => isset($data['content']) ? \wp_kses_post($data['content']) : \wp_kses_post('<p>Demo content for ' . $title . '.</p>')
                ]);
                // assign template when applicable
                $page = \get_page_by_path($data['slug']);
                if ($page && !empty($data['template'])) {
                    \update_post_meta($page->ID, '_wp_page_template', $data['template']);
                }
            }
        }
        // Assign home and posts pages
        $home = \get_page_by_path('home');
        $blog = \get_page_by_path('blog');
        if ($home && $blog) {
            \update_option('show_on_front', 'page');
            \update_option('page_on_front', $home->ID);
            \update_option('page_for_posts', $blog->ID);
        }
        // Menus
        $primary_menu = \wp_get_nav_menu_object('Primary');
        if (!$primary_menu) {
            $menu_id = \wp_create_nav_menu('Primary');
            $home_id = $home ? $home->ID : 0;
            if ($menu_id && $home_id) {
                \wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title' => 'Home',
                    'menu-item-object' => 'page',
                    'menu-item-object-id' => $home_id,
                    'menu-item-type' => 'post_type',
                    'menu-item-status' => 'publish',
                ]);
                // also add Wholesale and Trade to menu if exist
                foreach (['wholesale','trade'] as $slug) {
                    $p = \get_page_by_path($slug);
                    if ($p) {
                        \wp_update_nav_menu_item($menu_id, 0, [
                            'menu-item-title' => $p->post_title,
                            'menu-item-object' => 'page',
                            'menu-item-object-id' => $p->ID,
                            'menu-item-type' => 'post_type',
                            'menu-item-status' => 'publish',
                        ]);
                    }
                }
            }
            \set_theme_mod('nav_menu_locations', array_merge((array) \get_theme_mod('nav_menu_locations'), ['primary' => $menu_id]));
        }
        return 'Created/verified core pages and menus.';
    }

    private static function create_cpts(): string
    {
        // Services
        for ($i=1;$i<=4;$i++) {
            \wp_insert_post([
                'post_title' => 'Service ' . $i,
                'post_type' => 'service',
                'post_status' => 'publish',
                'post_content' => 'Professional aquarium service #' . $i,
            ]);
        }
        // Events
        for ($i=1;$i<=3;$i++) {
            \wp_insert_post([
                'post_title' => 'Event ' . $i,
                'post_type' => 'event',
                'post_status' => 'publish',
                'post_content' => 'AquaLuxe event #' . $i,
            ]);
        }
        return 'Created sample Services and Events.';
    }

    private static function create_wc_sample(): string
    {
        if (!class_exists('WC_Product_Simple')) { return 'WooCommerce not fully available'; }
        // Categories
        $cats = ['Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies'];
        $cat_ids = [];
        foreach ($cats as $c) {
            $term = \term_exists($c, 'product_cat');
            if (!$term) { $term = \wp_insert_term($c, 'product_cat'); }
            if (!is_wp_error($term)) { $cat_ids[] = (int) ($term['term_id'] ?? $term); }
        }
        // Tags
        $tags = ['Exotic','Quarantined','Aquascape','Rare'];
        $tag_ids = [];
        foreach ($tags as $t) {
            $term = \term_exists($t, 'product_tag');
            if (!$term) { $term = \wp_insert_term($t, 'product_tag'); }
            if (!is_wp_error($term)) { $tag_ids[] = (int) ($term['term_id'] ?? $term); }
        }
        // Simple products
        for ($i=1;$i<=6;$i++) {
            $p = new \WC_Product_Simple();
            $p->set_name('AquaLuxe Specimen ' . $i);
            $p->set_status('publish');
            $p->set_regular_price((string) (50 + 10 * $i));
            $p->set_manage_stock(true);
            $p->set_stock_quantity(5 + $i);
            $p->set_catalog_visibility('visible');
            $pid = $p->save();
            if (!empty($cat_ids)) { \wp_set_object_terms($pid, [$cat_ids[$i % count($cat_ids)]], 'product_cat'); }
            if (!empty($tag_ids)) { \wp_set_object_terms($pid, [$tag_ids[$i % count($tag_ids)]], 'product_tag', true); }
            $att_id = self::ensure_demo_image('specimen-' . $i);
            if ($att_id) { set_post_thumbnail($pid, $att_id); }
        }
        // Variable product
        try {
            $attr_size = 'pa_size'; $attr_color = 'pa_color'; $attr_material = 'pa_material';
            if (!\taxonomy_exists($attr_size)) { \register_taxonomy($attr_size, 'product', ['label' => 'Size', 'hierarchical' => false, 'public' => false, 'show_ui' => false]); }
            if (!\taxonomy_exists($attr_color)) { \register_taxonomy($attr_color, 'product', ['label' => 'Color', 'hierarchical' => false, 'public' => false, 'show_ui' => false]); }
            if (!\taxonomy_exists($attr_material)) { \register_taxonomy($attr_material, 'product', ['label' => 'Material', 'hierarchical' => false, 'public' => false, 'show_ui' => false]); }
            $vp = new \WC_Product_Variable();
            $vp->set_name('AquaLuxe Exhibit Tank');
            $vp->set_status('publish');
            $vp->set_catalog_visibility('visible');
            $vid = $vp->save();
            if ($vid) {
                \wp_set_object_terms($vid, ['small','medium','large'], $attr_size);
                \wp_set_object_terms($vid, ['blue','gold'], $attr_color);
                \wp_set_object_terms($vid, ['glass','acrylic'], $attr_material);
                $attributes = [];
                foreach ([ $attr_size => ['small','medium','large'], $attr_color => ['blue','gold'], $attr_material => ['glass','acrylic'] ] as $name => $opts) {
                    $tax = new \WC_Product_Attribute();
                    $tax->set_id(0);
                    $tax->set_name($name);
                    $tax->set_options($opts);
                    $tax->set_visible(true);
                    $tax->set_variation(true);
                    $attributes[] = $tax;
                }
                $vp->set_attributes($attributes);
                $thumb = self::ensure_demo_image('exhibit-tank');
                if ($thumb) { set_post_thumbnail($vid, $thumb); }
                $vp->save();
                $vars = [
                    ['size'=>'small','color'=>'blue','material'=>'glass','price'=>199,'stock'=>7],
                    ['size'=>'medium','color'=>'blue','material'=>'glass','price'=>299,'stock'=>5],
                    ['size'=>'large','color'=>'gold','material'=>'acrylic','price'=>499,'stock'=>3],
                ];
                foreach ($vars as $v) {
                    $var = new \WC_Product_Variation();
                    $var->set_parent_id($vid);
                    $var->set_attributes([
                        $attr_size => $v['size'],
                        $attr_color => $v['color'],
                        $attr_material => $v['material'],
                    ]);
                    $var->set_status('publish');
                    $var->set_regular_price((string) $v['price']);
                    $var->set_manage_stock(true);
                    $var->set_stock_quantity((int) $v['stock']);
                    $var->save();
                }
            }
        } catch (\Throwable $e) {}
        // Minimal WC settings
        try { \update_option('woocommerce_currency', 'USD'); } catch (\Throwable $e) {}
        return 'Created WooCommerce categories, simple and variable products, basic settings.';
    }

    /** Configure WooCommerce core pages, payments, and shipping */
    private static function configure_wc(): string
    {
        if (!class_exists('WooCommerce')) { return 'Woo not available'; }
        // Ensure core pages
        $pages = [
            'Cart' => [ 'option' => 'woocommerce_cart_page_id', 'slug' => 'cart', 'content' => '[woocommerce_cart]' ],
            'Checkout' => [ 'option' => 'woocommerce_checkout_page_id', 'slug' => 'checkout', 'content' => '[woocommerce_checkout]' ],
            'My account' => [ 'option' => 'woocommerce_myaccount_page_id', 'slug' => 'my-account', 'content' => '[woocommerce_my_account]' ],
            'Shop' => [ 'option' => 'woocommerce_shop_page_id', 'slug' => 'shop', 'content' => '' ],
        ];
        foreach ($pages as $title => $cfg) {
            $page = \get_page_by_path($cfg['slug']);
            if (!$page) {
                $id = \wp_insert_post([
                    'post_title' => $title,
                    'post_name' => $cfg['slug'],
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'post_content' => $cfg['content'] ? \wp_kses_post($cfg['content']) : '',
                ]);
                if ($id) { \update_option($cfg['option'], $id); }
            } else {
                \update_option($cfg['option'], $page->ID);
            }
        }
        // Enable COD, BACS, Cheque
        try {
            if (function_exists('WC')) {
                $gateways = \WC()->payment_gateways();
                if ($gateways) {
                    foreach ($gateways->get_available_payment_gateways() as $id => $gw) {
                        $enabled = in_array($id, ['cod','bacs','cheque'], true) ? 'yes' : 'no';
                        \update_option('woocommerce_' . $id . '_settings', array_merge((array) get_option('woocommerce_' . $id . '_settings', []), ['enabled' => $enabled]));
                    }
                }
            }
        } catch (\Throwable $e) {}
        // Shipping: ensure Rest of World (zone ID 0) has a Flat Rate
        try {
            if (class_exists('WC_Shipping_Zones') && class_exists('WC_Shipping_Zone')) {
                $zone = new \WC_Shipping_Zone(0); // Locations not covered by your other zones
                $methods = $zone->get_shipping_methods();
                $hasFlat = false; foreach ($methods as $m) { if ($m->id === 'flat_rate') { $hasFlat = true; break; } }
                if (!$hasFlat) {
                    $zone->add_shipping_method('flat_rate');
                    foreach ($zone->get_shipping_methods() as $m) { if ($m->id === 'flat_rate') { $m->title = 'Flat Rate'; $m->cost = '10'; $m->enabled = 'yes'; $m->save(); } }
                }
            }
        } catch (\Throwable $e) {}
        return 'Configured WooCommerce pages, payments, and shipping.';
    }

    // --- Below: Step-wise engine helpers (used by REST endpoints) ---
    public static function start(array $entities, bool $reset, int $volume = 10, string $policy = 'skip', string $locale = 'en_US', array $range = []): array
    {
        $runId = 'aqlx-' . gmdate('Ymd-His') . '-' . substr(wp_hash((string) wp_rand()), 0, 8);
        $upload = \wp_upload_dir();
        $logDir = trailingslashit($upload['basedir']) . 'aqualuxe-import-logs/';
        \wp_mkdir_p($logDir);
        $auditFile = $logDir . $runId . '.jsonl';
        $auditUrl = trailingslashit($upload['baseurl']) . 'aqualuxe-import-logs/' . $runId . '.jsonl';
        $state = [
            'started' => time(),
            'run_id' => $runId,
            'entities' => $entities,
            'reset' => $reset,
            'volume' => max(1, min(100, $volume)),
            'steps' => self::build_steps($entities),
            'index' => 0,
            'paused' => false,
            'created_posts' => [],
            'created_terms' => [],
            'policy' => in_array($policy, ['skip','overwrite','merge'], true) ? $policy : 'skip',
            'locale' => $locale ?: 'en_US',
            'range' => [ 'from' => (string) ($range['from'] ?? ''), 'to' => (string) ($range['to'] ?? '') ],
            'log' => ['Started importer'],
            'counters' => [],
            'step_state' => [],
            'audit_file' => $auditFile,
            'audit_url' => $auditUrl,
        ];
        if ($reset) {
            $types = self::map_entities_to_types($entities);
            $state['log'][] = self::reset_selected($types, $state['range']);
        }
        \update_option('aqlx_import_state', $state, false);
        // audit start
        self::audit($state, 'start', [
            'entities' => $entities,
            'reset' => $reset,
            'volume' => $state['volume'],
            'policy' => $state['policy'],
            'locale' => $state['locale'],
            'range' => $state['range'],
        ]);
    return ['ok' => true, 'progress' => 0, 'log' => $state['log'], 'audit_url' => $state['audit_url']];
    }

    public static function step(): array
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$state) { return ['error' => 'no_state']; }
        if (!empty($state['paused'])) {
            $progress = self::compute_progress($state, 0.0);
            return ['done' => false, 'progress' => $progress, 'paused' => true, 'log' => ['Paused'] , 'audit_url' => (string) ($state['audit_url'] ?? '')];
        }
        $steps = $state['steps'] ?? [];
        $i = (int) ($state['index'] ?? 0);
        if ($i >= count($steps)) { return ['done' => true, 'progress' => 100, 'log' => ['Already completed']]; }
        $step = $steps[$i] ?? null;
        $log = [];
        $partial = 0.0; // partial completion within current step [0..1]
        try {
            switch ($step) {
                case 'pages': {
                    [$logStep, $done, $partial] = self::process_pages_step($state);
                    $log = array_merge($log, $logStep);
                    if ($done) { $state['index'] = $i + 1; }
                    break; }
                case 'cpts': {
                    [$logStep, $done, $partial] = self::process_cpts_step($state);
                    $log = array_merge($log, $logStep);
                    if ($done) { $state['index'] = $i + 1; }
                    break; }
                case 'products': {
                    if (class_exists('WooCommerce')) {
                        [$logStep, $done, $partial] = self::process_products_step($state);
                        $log = array_merge($log, $logStep);
                        if ($done) { $state['index'] = $i + 1; }
                    } else {
                        $log[] = 'WooCommerce not active; skipping products.';
                        $state['index'] = $i + 1;
                    }
                    break; }
                case 'wc_config':
                    if (class_exists('WooCommerce')) { $log[] = self::configure_wc(); }
                    else { $log[] = 'WooCommerce not active; skipping wc_config.'; }
                    $state['index'] = $i + 1;
                    $partial = 1.0;
                    break;
                case 'media':
                    $log[] = 'Media placeholders are created alongside content.';
                    $state['index'] = $i + 1;
                    $partial = 1.0;
                    break;
                default:
                    $log[] = 'Skipped: ' . (string) $step;
                    $state['index'] = $i + 1;
                    $partial = 1.0;
            }
            $state['log'] = array_merge($state['log'], $log);
            \update_option('aqlx_import_state', $state, false);
            $doneAll = $state['index'] >= count($steps);
            $progress = self::compute_progress($state, $partial);
            // audit tick
            try { self::audit($state, 'tick', [ 'step' => (string) $step, 'index' => $state['index'], 'progress' => $progress, 'messages' => $log ]); } catch (\Throwable $ie) {}
            if ($doneAll) {
                try {
                    self::audit($state, 'done', [
                        'steps' => $steps,
                        'created_posts' => count((array) ($state['created_posts'] ?? [])),
                        'created_terms' => count((array) ($state['created_terms'] ?? []))
                    ]);
                } catch (\Throwable $ie) {}
            }
            return ['done' => $doneAll, 'progress' => $progress, 'log' => $log, 'audit_url' => (string) ($state['audit_url'] ?? '')];
        } catch (\Throwable $e) {
            $log[] = 'Error: ' . $e->getMessage();
            // rollback created entities in this run
            try { self::rollback($state); $log[] = 'Rolled back created entities in this step.'; } catch (\Throwable $ie) {}
            try { self::audit($state, 'error', [ 'step' => (string) ($step ?? ''), 'message' => $e->getMessage() ]); } catch (\Throwable $ie) {}
            $state['log'] = array_merge($state['log'], $log);
            \update_option('aqlx_import_state', $state, false);
            return ['error' => 'step_failed', 'log' => $log];
        }
    }

    public static function export(array $entities): array
    {
        $types = self::map_entities_to_types($entities);
        $items = [];
        foreach ($types as $t) {
            $q = new \WP_Query(['post_type' => $t, 'posts_per_page' => -1, 'post_status' => 'any']);
            while ($q->have_posts()) { $q->the_post(); $items[] = [
                'ID' => get_the_ID(),
                'post_type' => get_post_type(),
                'post_title' => get_the_title(),
                'post_name' => get_post_field('post_name'),
                'meta' => \get_post_meta(get_the_ID()),
            ]; }
            \wp_reset_postdata();
        }
        $upload = \wp_upload_dir();
        $file = trailingslashit($upload['basedir']) . 'aqualuxe-export-' . gmdate('Ymd-His') . '.json';
        \wp_mkdir_p($upload['basedir']);
        file_put_contents($file, json_encode(['generated' => gmdate('c'), 'items' => $items], JSON_PRETTY_PRINT));
        $url = trailingslashit($upload['baseurl']) . basename($file);
        return ['url' => $url];
    }

    /** Flush all importer state and demo content. */
    public static function flush(): array
    {
        $msg = self::reset_content();
        \delete_option('aqlx_import_state');
        \delete_option('aqlx_import_schedule');
        return ['ok' => true, 'message' => $msg];
    }

    /** Return current importer state for debugging */
    public static function state(): array
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$state) { return ['ok' => false]; }
        $steps = (array) ($state['steps'] ?? []);
        $i = (int) ($state['index'] ?? 0);
        $partial = self::compute_partial_from_state($state);
        $progress = self::compute_progress($state, $partial);
        $done = $i >= count($steps);
        return [
            'ok' => true,
            'state' => $state,
            'progress' => $progress,
            'paused' => (bool) ($state['paused'] ?? false),
            'done' => $done,
            'audit_url' => (string) ($state['audit_url'] ?? ''),
            'created_posts' => count((array) ($state['created_posts'] ?? [])),
            'created_terms' => count((array) ($state['created_terms'] ?? [])),
        ];
    }

    /** Pause the importer */
    public static function pause(): array
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$state) { return ['error' => 'no_state']; }
        $state['paused'] = true;
        \update_option('aqlx_import_state', $state, false);
        try { self::audit($state, 'pause', []); } catch (\Throwable $ie) {}
        return ['ok' => true, 'paused' => true];
    }

    /** Resume the importer */
    public static function resume(): array
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$state) { return ['error' => 'no_state']; }
        $state['paused'] = false;
        \update_option('aqlx_import_state', $state, false);
        try { self::audit($state, 'resume', []); } catch (\Throwable $ie) {}
        return ['ok' => true, 'paused' => false];
    }

    /** Cancel the importer: rollback created and clear state */
    public static function cancel(): array
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$state) { return ['error' => 'no_state']; }
        try { self::rollback($state); } catch (\Throwable $e) {}
        try { self::audit($state, 'cancel', []); } catch (\Throwable $ie) {}
        \delete_option('aqlx_import_state');
        return ['ok' => true, 'canceled' => true];
    }

    private static function reset_selected(array $types, array $range = []): string
    {
        $from = !empty($range['from']) ? strtotime($range['from'] . ' 00:00:00') : 0;
        $to = !empty($range['to']) ? strtotime($range['to'] . ' 23:59:59') : 0;
        foreach ($types as $type) {
            $args = ['post_type' => $type, 'posts_per_page' => -1, 'post_status' => 'any'];
            if ($from || $to) {
                $args['date_query'] = [[
                    'after' => $from ? gmdate('Y-m-d H:i:s', $from) : null,
                    'before' => $to ? gmdate('Y-m-d H:i:s', $to) : null,
                    'inclusive' => true,
                ]];
            }
            $q = new \WP_Query($args);
            while ($q->have_posts()) { $q->the_post(); \wp_delete_post(\get_the_ID(), true); }
            \wp_reset_postdata();
        }
        return 'Reset: ' . implode(', ', $types);
    }

    private static function build_steps(array $entities): array
    {
        $steps = [];
        foreach ($entities as $e) {
            if (in_array($e, ['pages','cpts','products','wc_config','media'], true)) { $steps[] = $e; }
        }
        return $steps;
    }

    private static function map_entities_to_types(array $entities): array
    {
        $map = [
            'pages' => ['page','nav_menu_item'],
            'cpts' => ['service','event'],
            'products' => ['product','product_variation'],
        ];
        $types = [];
        foreach ($entities as $e) { $types = array_merge($types, $map[$e] ?? []); }
        return array_values(array_unique($types));
    }

    /** Create or reuse a simple SVG placeholder and attach to Media Library. */
    private static function ensure_demo_image(string $slug): int
    {
        $upload = \wp_upload_dir();
        $dir = trailingslashit($upload['basedir']) . 'aqualuxe-demo/';
        \wp_mkdir_p($dir);
        $file = $dir . sanitize_file_name($slug) . '.svg';
        if (!file_exists($file)) {
            $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="800"><defs><linearGradient id="g" x1="0" x2="1" y1="0" y2="1"><stop offset="0%" stop-color="#0ea5e9"/><stop offset="100%" stop-color="#0f172a"/></linearGradient></defs><rect width="100%" height="100%" fill="url(#g)"/><g fill="#fff" opacity="0.85"><circle cx="200" cy="300" r="60"/><circle cx="260" cy="340" r="30"/><circle cx="900" cy="200" r="50"/></g><text x="50%" y="50%" text-anchor="middle" fill="#e2e8f0" font-size="42" font-family="sans-serif">AquaLuxe</text></svg>';
            file_put_contents($file, $svg);
        }
        $url = trailingslashit($upload['baseurl']) . 'aqualuxe-demo/' . basename($file);
        $existing = get_page_by_title($slug, OBJECT, 'attachment');
        if ($existing) { return (int) $existing->ID; }
        $attachment = [
            'post_title' => $slug,
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'guid' => $url,
            'post_mime_type' => 'image/svg+xml',
        ];
        $attach_id = \wp_insert_post($attachment);
        if ($attach_id) {
            \update_post_meta($attach_id, '_wp_attached_file', 'aqualuxe-demo/' . basename($file));
            \update_post_meta($attach_id, '_wp_attachment_metadata', ['file' => 'aqualuxe-demo/' . basename($file)]);
            // track created attachment for potential rollback
            self::track_created_post($attach_id);
        }
        return (int) $attach_id;
    }

    // Lightweight preview report for UI
    public static function preview(array $entities, int $volume = 10): array
    {
        $report = [ 'entities' => $entities, 'volume' => max(1, min(100, $volume)), 'counts' => [] ];
        foreach ($entities as $e) {
            switch ($e) {
                case 'pages': $report['counts']['pages'] = 10; break;
                case 'cpts': $report['counts']['cpts'] = 7; break;
                case 'products': $report['counts']['products'] = 7; break; // 6 simple + 1 variable
                case 'wc_config': $report['counts']['wc_config'] = 1; break;
                case 'media': $report['counts']['media'] = 10; break;
            }
        }
        $report['sample'] = [ 'product' => 'AquaLuxe Specimen 1', 'page' => 'Home' ];
        return $report;
    }

    // --- Helpers for batching, conflict policy, and rollback ---
    private static function compute_progress(array $state, float $partial): int
    {
        $steps = (array) ($state['steps'] ?? []);
        $i = (int) ($state['index'] ?? 0);
        $count = max(1, count($steps));
        $base = min($i, $count);
        $fraction = 0.0;
        if ($base < $count) { $fraction = max(0.0, min(1.0, $partial)); }
        $pct = (int) floor((($base + $fraction) / $count) * 100);
        return max(0, min(100, $pct));
    }

    private static function &ensure_step_state(array &$state, string $key, array $defaults = []): array
    {
        if (!isset($state['step_state']) || !is_array($state['step_state'])) { $state['step_state'] = []; }
        if (!isset($state['step_state'][$key]) || !is_array($state['step_state'][$key])) { $state['step_state'][$key] = $defaults; }
        return $state['step_state'][$key];
    }

    private static function track_created_post(int $id): void
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$id) return;
        $list = (array) ($state['created_posts'] ?? []);
        if (!in_array($id, $list, true)) { $list[] = $id; $state['created_posts'] = $list; \update_option('aqlx_import_state', $state, false); }
    }

    private static function track_created_term(int $id): void
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$id) return;
        $list = (array) ($state['created_terms'] ?? []);
        if (!in_array($id, $list, true)) { $list[] = $id; $state['created_terms'] = $list; \update_option('aqlx_import_state', $state, false); }
    }

    private static function rollback(array &$state): void
    {
        // Delete posts we created in this run
        $posts = array_reverse((array) ($state['created_posts'] ?? []));
        foreach ($posts as $pid) { if ($pid && get_post($pid)) { \wp_delete_post((int) $pid, true); } }
        // Delete any terms created (best-effort)
        $terms = array_reverse((array) ($state['created_terms'] ?? []));
        foreach ($terms as $tid) {
            $term = get_term((int) $tid);
            if ($term && !is_wp_error($term) && $term->term_id) { \wp_delete_term((int) $term->term_id, $term->taxonomy); }
        }
        // Clear trackers after rollback
        $state['created_posts'] = [];
        $state['created_terms'] = [];
    \update_option('aqlx_import_state', $state, false);
    try { self::audit($state, 'rollback', [ 'postsRolledBack' => count($posts), 'termsRolledBack' => count($terms) ]); } catch (\Throwable $ie) {}
    }

    /** Append a JSONL audit event for the current run. */
    private static function audit(array $state, string $event, array $payload = []): void
    {
        $file = (string) ($state['audit_file'] ?? '');
        if (!$file) { return; }
        $row = [
            'ts' => gmdate('c'),
            'run' => (string) ($state['run_id'] ?? ''),
            'event' => $event,
            'payload' => $payload,
        ];
        // best-effort write
        try {
            $line = wp_json_encode($row) . "\n";
            file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
        } catch (\Throwable $e) {}
    }

    private static function process_pages_step(array &$state): array
    {
        $volume = (int) ($state['volume'] ?? 10);
        $policy = (string) ($state['policy'] ?? 'skip');
        $planDefaults = [
            'items' => [
                ['title' => 'Home', 'slug' => 'home'],
                ['title' => 'About', 'slug' => 'about'],
                ['title' => 'Services', 'slug' => 'services'],
                ['title' => 'Blog', 'slug' => 'blog'],
                ['title' => 'Contact', 'slug' => 'contact'],
                ['title' => 'FAQ', 'slug' => 'faq'],
                ['title' => 'Privacy Policy', 'slug' => 'privacy-policy'],
                ['title' => 'Terms & Conditions', 'slug' => 'terms'],
                ['title' => 'Shipping & Returns', 'slug' => 'shipping-returns'],
                ['title' => 'Cookie Policy', 'slug' => 'cookies'],
                ['title' => 'Wholesale & B2B', 'slug' => 'wholesale', 'template' => 'page-wholesale.php', 'content' => '[aqualuxe_wholesale_form]'],
                ['title' => 'Buy, Sell & Trade', 'slug' => 'trade', 'template' => 'page-trade.php', 'content' => '[aqualuxe_tradein_form]'],
            ],
            'index' => 0,
            'total' => 12,
            'homeSet' => false,
            'menuSet' => false,
        ];
        $ps = &self::ensure_step_state($state, 'pages', $planDefaults);
        $items = (array) ($ps['items'] ?? []);
        $idx = (int) ($ps['index'] ?? 0);
        $total = max(1, (int) ($ps['total'] ?? count($items)));
        $log = [];
        $processed = 0;
        while ($idx < count($items) && $processed < $volume) {
            $it = $items[$idx];
            $slug = $it['slug'];
            $title = $it['title'];
            $existing = \get_page_by_path($slug);
            if (!$existing) {
                $pid = \wp_insert_post([
                    'post_title' => $title,
                    'post_name'  => $slug,
                    'post_type'  => 'page',
                    'post_status'=> 'publish',
                    'post_content' => isset($it['content']) ? \wp_kses_post($it['content']) : \wp_kses_post('<p>Demo content for ' . $title . '.</p>')
                ]);
                if ($pid) { self::track_created_post((int) $pid); $log[] = 'Created page: ' . $title; try { self::audit($state, 'create', [ 'type' => 'page', 'id' => (int) $pid, 'slug' => $slug ]); } catch (\Throwable $ie) {} }
                if (!empty($it['template']) && $pid) { \update_post_meta($pid, '_wp_page_template', $it['template']); }
            } else {
                if ($policy === 'overwrite') {
                    \wp_update_post([
                        'ID' => $existing->ID,
                        'post_content' => isset($it['content']) ? \wp_kses_post($it['content']) : $existing->post_content,
                    ]);
                    if (!empty($it['template'])) { \update_post_meta($existing->ID, '_wp_page_template', $it['template']); }
                    $log[] = 'Updated page: ' . $title; try { self::audit($state, 'update', [ 'type' => 'page', 'id' => (int) $existing->ID, 'slug' => $slug ]); } catch (\Throwable $ie) {}
                } elseif ($policy === 'merge') {
                    if (!empty($it['template'])) { \update_post_meta($existing->ID, '_wp_page_template', $it['template']); $log[] = 'Ensured template for page: ' . $title; try { self::audit($state, 'merge', [ 'type' => 'page', 'id' => (int) $existing->ID, 'slug' => $slug, 'fields' => ['template'] ]); } catch (\Throwable $ie) {} }
                    // keep content
                } else {
                    $log[] = 'Skipped existing page: ' . $title; try { self::audit($state, 'skip', [ 'type' => 'page', 'id' => (int) $existing->ID, 'slug' => $slug ]); } catch (\Throwable $ie) {}
                }
            }
            $idx++; $processed++;
        }
        $ps['index'] = $idx; $ps['total'] = count($items);
        // After creating pages, set home/blog and menu once
        if (!$ps['homeSet']) {
            $home = \get_page_by_path('home'); $blog = \get_page_by_path('blog');
            if ($home && $blog) { \update_option('show_on_front', 'page'); \update_option('page_on_front', $home->ID); \update_option('page_for_posts', $blog->ID); $ps['homeSet'] = true; $log[] = 'Configured front and posts pages.'; try { self::audit($state, 'set-option', [ 'show_on_front' => 'page', 'page_on_front' => (int) $home->ID, 'page_for_posts' => (int) $blog->ID ]); } catch (\Throwable $ie) {} }
        }
        if (!$ps['menuSet']) {
            $primary_menu = \wp_get_nav_menu_object('Primary');
            if (!$primary_menu) {
                $menu_id = \wp_create_nav_menu('Primary');
                $home = \get_page_by_path('home');
                if ($menu_id && $home) {
                    \wp_update_nav_menu_item($menu_id, 0, [
                        'menu-item-title' => 'Home',
                        'menu-item-object' => 'page',
                        'menu-item-object-id' => $home->ID,
                        'menu-item-type' => 'post_type',
                        'menu-item-status' => 'publish',
                    ]);
                    foreach (['wholesale','trade'] as $slug) {
                        $p = \get_page_by_path($slug);
                        if ($p) { \wp_update_nav_menu_item($menu_id, 0, [ 'menu-item-title' => $p->post_title, 'menu-item-object' => 'page', 'menu-item-object-id' => $p->ID, 'menu-item-type' => 'post_type', 'menu-item-status' => 'publish' ]); }
                    }
                    \set_theme_mod('nav_menu_locations', array_merge((array) \get_theme_mod('nav_menu_locations'), ['primary' => $menu_id]));
                    try { self::audit($state, 'set-menu', [ 'location' => 'primary', 'menu_id' => (int) $menu_id ]); } catch (\Throwable $ie) {}
                }
            }
            $ps['menuSet'] = true;
        }
        // Persist step state
        $state['step_state']['pages'] = $ps; \update_option('aqlx_import_state', $state, false);
        $done = $ps['index'] >= count($items);
        $partial = min(1.0, max(0.0, (count($items) ? $ps['index'] / count($items) : 1.0)));
        return [$log, $done, $partial];
    }

    private static function process_cpts_step(array &$state): array
    {
        $volume = (int) ($state['volume'] ?? 10);
        $policy = (string) ($state['policy'] ?? 'skip');
        $defaults = [ 'service_total' => 4, 'service_index' => 0, 'event_total' => 3, 'event_index' => 0 ];
        $cs = &self::ensure_step_state($state, 'cpts', $defaults);
        $log = [];
        $processed = 0;
        // Services first
        while ($cs['service_index'] < $cs['service_total'] && $processed < $volume) {
            $i = $cs['service_index'] + 1; $title = 'Service ' . $i; $existing = \post_exists($title, '', '', 'service');
            if (!$existing) {
                $pid = \wp_insert_post([ 'post_title' => $title, 'post_type' => 'service', 'post_status' => 'publish', 'post_content' => 'Professional aquarium service #' . $i ]);
                if ($pid) { self::track_created_post((int) $pid); $log[] = 'Created service #' . $i; }
            } else {
                if ($policy === 'overwrite') { \wp_update_post([ 'ID' => $existing, 'post_status' => 'publish' ]); $log[] = 'Updated service #' . $i; }
                else { $log[] = 'Skipped service #' . $i; }
            }
            $cs['service_index']++; $processed++;
        }
        // Then events
        while ($cs['event_index'] < $cs['event_total'] && $processed < $volume) {
            $i = $cs['event_index'] + 1; $title = 'Event ' . $i; $existing = \post_exists($title, '', '', 'event');
            if (!$existing) {
                $pid = \wp_insert_post([ 'post_title' => $title, 'post_type' => 'event', 'post_status' => 'publish', 'post_content' => 'AquaLuxe event #' . $i ]);
                if ($pid) { self::track_created_post((int) $pid); $log[] = 'Created event #' . $i; }
            } else {
                if ($policy === 'overwrite') { \wp_update_post([ 'ID' => $existing, 'post_status' => 'publish' ]); $log[] = 'Updated event #' . $i; }
                else { $log[] = 'Skipped event #' . $i; }
            }
            $cs['event_index']++; $processed++;
        }
        $state['step_state']['cpts'] = $cs; \update_option('aqlx_import_state', $state, false);
        $total = max(1, (int) $cs['service_total'] + (int) $cs['event_total']);
        $doneCount = (int) $cs['service_index'] + (int) $cs['event_index'];
        $partial = min(1.0, max(0.0, $doneCount / $total));
        $done = $doneCount >= $total;
        return [$log, $done, $partial];
    }

    private static function process_products_step(array &$state): array
    {
        $volume = (int) ($state['volume'] ?? 10);
        $policy = (string) ($state['policy'] ?? 'skip');
        $defaults = [ 'simple_total' => 6, 'simple_index' => 0, 'variable_done' => false, 'terms_seeded' => false ];
        $ps = &self::ensure_step_state($state, 'products', $defaults);
        $log = [];
        // Seed categories/tags once
        if (!$ps['terms_seeded']) {
            $cats = ['Rare Fish','Aquatic Plants','Premium Equipment','Care Supplies'];
            foreach ($cats as $c) { $term = \term_exists($c, 'product_cat'); if (!$term) { $term = \wp_insert_term($c, 'product_cat'); if (!is_wp_error($term)) { self::track_created_term((int) ($term['term_id'] ?? $term)); $log[] = 'Created category: ' . $c; } } }
            $tags = ['Exotic','Quarantined','Aquascape','Rare'];
            foreach ($tags as $t) { $term = \term_exists($t, 'product_tag'); if (!$term) { $term = \wp_insert_term($t, 'product_tag'); if (!is_wp_error($term)) { self::track_created_term((int) ($term['term_id'] ?? $term)); $log[] = 'Created tag: ' . $t; } } }
            $ps['terms_seeded'] = true;
        }
        // Collect term ids for assignment
        $cat_terms = get_terms(['taxonomy'=>'product_cat','hide_empty'=>false]); $cat_ids = [];
        if (!is_wp_error($cat_terms)) { foreach ($cat_terms as $t) { $cat_ids[] = (int) $t->term_id; } }
        $tag_terms = get_terms(['taxonomy'=>'product_tag','hide_empty'=>false]); $tag_ids = [];
        if (!is_wp_error($tag_terms)) { foreach ($tag_terms as $t) { $tag_ids[] = (int) $t->term_id; } }
        $processed = 0;
        // Simple products batch
        while ($ps['simple_index'] < $ps['simple_total'] && $processed < $volume) {
            $i = $ps['simple_index'] + 1;
            $name = 'AquaLuxe Specimen ' . $i;
            $existing_id = 0;
            $existing = get_page_by_title($name, OBJECT, 'product'); if ($existing) { $existing_id = (int) $existing->ID; }
            if (!$existing_id) {
                $p = new \WC_Product_Simple();
                $p->set_name($name);
                $p->set_status('publish');
                $p->set_regular_price((string) (50 + 10 * $i));
                $p->set_manage_stock(true);
                $p->set_stock_quantity(5 + $i);
                $p->set_catalog_visibility('visible');
                $pid = $p->save();
                if ($pid) { self::track_created_post((int) $pid); $log[] = 'Created product: ' . $name; }
                if (!empty($cat_ids)) { \wp_set_object_terms($pid, [$cat_ids[$i % max(1,count($cat_ids))]], 'product_cat'); }
                if (!empty($tag_ids)) { \wp_set_object_terms($pid, [$tag_ids[$i % max(1,count($tag_ids))]], 'product_tag', true); }
                $att_id = self::ensure_demo_image('specimen-' . $i); if ($att_id) { set_post_thumbnail($pid, $att_id); }
            } else {
                if ($policy === 'overwrite') {
                    $p = wc_get_product($existing_id); if ($p) { $p->set_regular_price((string) (50 + 10 * $i)); $p->save(); $log[] = 'Updated product: ' . $name; }
                } elseif ($policy === 'merge') {
                    // Ensure thumbnail only
                    if (!has_post_thumbnail($existing_id)) { $att_id = self::ensure_demo_image('specimen-' . $i); if ($att_id) { set_post_thumbnail($existing_id, $att_id); $log[] = 'Added image to product: ' . $name; } }
                } else { $log[] = 'Skipped product: ' . $name; }
            }
            $ps['simple_index']++; $processed++;
        }
        // Variable product (counts as one unit)
        if (!$ps['variable_done'] && $processed < $volume) {
            $name = 'AquaLuxe Exhibit Tank';
            $existing = get_page_by_title($name, OBJECT, 'product');
            if (!$existing) {
                // ensure attributes
                $attr_size = 'pa_size'; $attr_color = 'pa_color'; $attr_material = 'pa_material';
                if (!\taxonomy_exists($attr_size)) { \register_taxonomy($attr_size, 'product', ['label' => 'Size', 'hierarchical' => false, 'public' => false, 'show_ui' => false]); }
                if (!\taxonomy_exists($attr_color)) { \register_taxonomy($attr_color, 'product', ['label' => 'Color', 'hierarchical' => false, 'public' => false, 'show_ui' => false]); }
                if (!\taxonomy_exists($attr_material)) { \register_taxonomy($attr_material, 'product', ['label' => 'Material', 'hierarchical' => false, 'public' => false, 'show_ui' => false]); }
                $vp = new \WC_Product_Variable();
                $vp->set_name($name); $vp->set_status('publish'); $vp->set_catalog_visibility('visible');
                $vid = $vp->save(); if ($vid) { self::track_created_post((int) $vid); $log[] = 'Created variable product: ' . $name; }
                if ($vid) {
                    \wp_set_object_terms($vid, ['small','medium','large'], $attr_size);
                    \wp_set_object_terms($vid, ['blue','gold'], $attr_color);
                    \wp_set_object_terms($vid, ['glass','acrylic'], $attr_material);
                    $attributes = [];
                    foreach ([ $attr_size => ['small','medium','large'], $attr_color => ['blue','gold'], $attr_material => ['glass','acrylic'] ] as $tname => $opts) {
                        $tax = new \WC_Product_Attribute(); $tax->set_id(0); $tax->set_name($tname); $tax->set_options($opts); $tax->set_visible(true); $tax->set_variation(true); $attributes[] = $tax;
                    }
                    $vp->set_attributes($attributes);
                    $thumb = self::ensure_demo_image('exhibit-tank'); if ($thumb) { set_post_thumbnail($vid, $thumb); }
                    $vp->save();
                    $vars = [ ['size'=>'small','color'=>'blue','material'=>'glass','price'=>199,'stock'=>7], ['size'=>'medium','color'=>'blue','material'=>'glass','price'=>299,'stock'=>5], ['size'=>'large','color'=>'gold','material'=>'acrylic','price'=>499,'stock'=>3], ];
                    foreach ($vars as $v) { $var = new \WC_Product_Variation(); $var->set_parent_id($vid); $var->set_attributes([ 'pa_size' => $v['size'], 'pa_color' => $v['color'], 'pa_material' => $v['material'] ]); $var->set_status('publish'); $var->set_regular_price((string) $v['price']); $var->set_manage_stock(true); $var->set_stock_quantity((int) $v['stock']); $var->save(); }
                }
            } else {
                if ($policy === 'overwrite') { $p = wc_get_product($existing->ID); if ($p) { $p->save(); $log[] = 'Verified variable product: ' . $name; } }
                else { $log[] = 'Skipped variable product: already exists.'; }
            }
            $ps['variable_done'] = true; $processed++;
        }
        // Minimal WC settings (once)
        if (!get_option('woocommerce_currency')) { \update_option('woocommerce_currency', 'USD'); }
        $state['step_state']['products'] = $ps; \update_option('aqlx_import_state', $state, false);
        $totalUnits = (int) $ps['simple_total'] + 1; $doneUnits = (int) $ps['simple_index'] + (int) ($ps['variable_done'] ? 1 : 0);
        $partial = min(1.0, max(0.0, $doneUnits / max(1,$totalUnits)));
        $done = $doneUnits >= $totalUnits;
        return [$log, $done, $partial];
    }

    /** Estimate partial completion for current step using step_state. */
    private static function compute_partial_from_state(array $state): float
    {
        $steps = (array) ($state['steps'] ?? []);
        $i = (int) ($state['index'] ?? 0);
        if ($i >= count($steps)) { return 1.0; }
        $current = (string) ($steps[$i] ?? '');
        $ss = (array) ($state['step_state'] ?? []);
        switch ($current) {
            case 'pages':
                $ps = (array) ($ss['pages'] ?? []); $items = (array) ($ps['items'] ?? []); $idx = (int) ($ps['index'] ?? 0); $total = max(1, count($items)); return max(0.0, min(1.0, $idx / $total));
            case 'cpts':
                $cs = (array) ($ss['cpts'] ?? []); $tot = (int) (($cs['service_total'] ?? 0) + ($cs['event_total'] ?? 0)); $done = (int) (($cs['service_index'] ?? 0) + ($cs['event_index'] ?? 0)); $tot = max(1, $tot); return max(0.0, min(1.0, $done / $tot));
            case 'products':
                $ps2 = (array) ($ss['products'] ?? []); $tot2 = (int) (($ps2['simple_total'] ?? 0) + 1); $done2 = (int) (($ps2['simple_index'] ?? 0) + (!empty($ps2['variable_done']) ? 1 : 0)); $tot2 = max(1, $tot2); return max(0.0, min(1.0, $done2 / $tot2));
            default:
                return 0.0;
        }
    }
}
