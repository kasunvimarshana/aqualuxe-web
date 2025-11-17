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
                                <?php endif; ?>
                            </fieldset>
                            <fieldset>
                                <legend><strong><?php esc_html_e('Options', 'aqualuxe'); ?></strong></legend>
                                <label><input type="checkbox" id="aqlx-reset"> <?php esc_html_e('Reset selected entities before import', 'aqualuxe'); ?></label><br>
                                <label><?php esc_html_e('Volume', 'aqualuxe'); ?> <input type="number" id="aqlx-volume" min="1" max="50" value="10" style="width:80px;"></label>
                            </fieldset>
                        </div>
                        <div style="margin-top:12px; display:flex; gap:8px; align-items:center;">
                            <button id="aqlx-start" class="button button-primary"><?php esc_html_e('Start Import', 'aqualuxe'); ?></button>
                            <button id="aqlx-export" class="button"><?php esc_html_e('Export Demo Content', 'aqualuxe'); ?></button>
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
                        status.textContent = 'Starting…'; setProgress(0); logEl.textContent='';
                        const start = await api('/import/start', 'POST', { entities, reset, volume }).catch(()=>({error:'start_failed'}));
                        if (start?.error) { status.textContent = 'Failed to start'; appendLog(start.error); return; }
                        let done = false; let progress = 0;
                        while(!done){
                            const step = await api('/import/step', 'POST', {} ).catch(()=>({error:'step_failed'}));
                            if (step?.error) { status.textContent = 'Error'; appendLog(step.error); break; }
                            if (typeof step.progress === 'number') { progress = step.progress; setProgress(progress); }
                            if (Array.isArray(step.log)) { step.log.forEach(appendLog); }
                            done = !!step.done;
                            status.textContent = done ? 'Completed' : 'Working…';
                            if (!done) { await new Promise(r => setTimeout(r, 250)); }
                        }
                    }
                    async function exportDemo(){
                        const entities = collectEntities();
                        const res = await api('/import/export', 'POST', { entities }).catch(()=>({error:'export_failed'}));
                        if (res?.url) { appendLog('Export ready: ' + res.url); window.open(res.url, '_blank'); }
                        else { appendLog(res?.error || 'Export failed'); }
                    }
                    document.getElementById('aqlx-start').addEventListener('click', run);
                    document.getElementById('aqlx-export').addEventListener('click', exportDemo);
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
        $types = ['post','page','attachment','service','event'];
        if (class_exists('WooCommerce')) {
            $types[] = 'product';
        }
        foreach ($types as $type) {
            $q = new \WP_Query(['post_type' => $type, 'posts_per_page' => -1, 'post_status' => 'any']);
            while ($q->have_posts()) { $q->the_post(); \wp_delete_post(\get_the_ID(), true); }
            \wp_reset_postdata();
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
        $cat = \wp_insert_term('Rare Fish', 'product_cat');
        $cat_id = is_wp_error($cat) ? (int) get_term_by('name','Rare Fish','product_cat')->term_id : (int) $cat['term_id'];
        for ($i=1;$i<=6;$i++) {
            $product = new \WC_Product_Simple();
            $product->set_name('AquaLuxe Specimen ' . $i);
            $product->set_status('publish');
            $product->set_regular_price((string) (50 + 10 * $i));
            $product->set_catalog_visibility('visible');
            $product_id = $product->save();
            if ($cat_id && $product_id) {
                \wp_set_object_terms($product_id, [$cat_id], 'product_cat');
            }
        }
        // Variable product with attributes
        try {
            $attr_size = 'pa_size'; $attr_color = 'pa_color';
            // Ensure attributes exist
            if (!\taxonomy_exists($attr_size)) { \register_taxonomy($attr_size, 'product', ['label' => 'Size', 'hierarchical' => false, 'public' => false, 'show_ui' => false]); }
            if (!\taxonomy_exists($attr_color)) { \register_taxonomy($attr_color, 'product', ['label' => 'Color', 'hierarchical' => false, 'public' => false, 'show_ui' => false]); }
            $vp = new \WC_Product_Variable();
            $vp->set_name('AquaLuxe Exhibit Tank');
            $vp->set_status('publish');
            $vp->set_catalog_visibility('visible');
            $vp_id = $vp->save();
            if ($vp_id) {
                \wp_set_object_terms($vp_id, ['small','medium','large'], $attr_size);
                \wp_set_object_terms($vp_id, ['blue','gold'], $attr_color);
                $attributes = [];
                foreach ([ $attr_size => ['small','medium','large'], $attr_color => ['blue','gold'] ] as $name => $opts) {
                    $tax = new \WC_Product_Attribute();
                    $tax->set_id(0);
                    $tax->set_name($name);
                    $tax->set_options($opts);
                    $tax->set_visible(true);
                    $tax->set_variation(true);
                    $attributes[] = $tax;
                }
                $vp->set_attributes($attributes);
                $vp->save();
                // Variations
                $variations = [
                    ['size'=>'small','color'=>'blue','price'=>199],
                    ['size'=>'medium','color'=>'blue','price'=>299],
                    ['size'=>'large','color'=>'gold','price'=>499],
                ];
                foreach ($variations as $v) {
                    $var = new \WC_Product_Variation();
                    $var->set_parent_id($vp_id);
                    $var->set_attributes([
                        $attr_size => $v['size'],
                        $attr_color => $v['color'],
                    ]);
                    $var->set_status('publish');
                    $var->set_regular_price((string) $v['price']);
                    $var->save();
                }
            }
        } catch (\Throwable $e) {}
        return 'Created sample WooCommerce products (including a variable product).';
    }

    // --- Below: Step-wise engine helpers (used by REST endpoints) ---
    public static function start(array $entities, bool $reset, int $volume = 10): array
    {
        $state = [
            'started' => time(),
            'entities' => $entities,
            'reset' => $reset,
            'volume' => max(1, min(100, $volume)),
            'steps' => self::build_steps($entities),
            'index' => 0,
            'created' => [],
            'log' => ['Started importer'],
        ];
        if ($reset) {
            $types = self::map_entities_to_types($entities);
            $state['log'][] = self::reset_selected($types);
        }
        \update_option('aqlx_import_state', $state, false);
        return ['ok' => true, 'progress' => 0, 'log' => $state['log']];
    }

    public static function step(): array
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$state) { return ['error' => 'no_state']; }
        $steps = $state['steps'] ?? [];
        $i = (int) ($state['index'] ?? 0);
        if ($i >= count($steps)) { return ['done' => true, 'progress' => 100, 'log' => ['Already completed']]; }
        $step = $steps[$i] ?? null;
        $log = [];
        try {
            switch ($step) {
                case 'pages':
                    $log[] = self::create_core_pages();
                    break;
                case 'cpts':
                    $log[] = self::create_cpts();
                    break;
                case 'products':
                    if (class_exists('WooCommerce')) { $log[] = self::create_wc_sample(); }
                    break;
                default:
                    $log[] = 'Skipped: ' . (string) $step;
            }
            $state['index'] = $i + 1;
            $state['log'] = array_merge($state['log'], $log);
            \update_option('aqlx_import_state', $state, false);
            $progress = (int) floor(($state['index'] / max(1, count($steps))) * 100);
            return ['done' => $state['index'] >= count($steps), 'progress' => $progress, 'log' => $log];
        } catch (\Throwable $e) {
            $log[] = 'Error: ' . $e->getMessage();
            // naive rollback: nothing to track yet beyond deletes in reset
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

    private static function reset_selected(array $types): string
    {
        foreach ($types as $type) {
            $q = new \WP_Query(['post_type' => $type, 'posts_per_page' => -1, 'post_status' => 'any']);
            while ($q->have_posts()) { $q->the_post(); \wp_delete_post(\get_the_ID(), true); }
            \wp_reset_postdata();
        }
        return 'Reset: ' . implode(', ', $types);
    }

    private static function build_steps(array $entities): array
    {
        $steps = [];
        foreach ($entities as $e) {
            if (in_array($e, ['pages','cpts','products'], true)) { $steps[] = $e; }
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
}
