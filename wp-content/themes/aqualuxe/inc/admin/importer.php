<?php
namespace AquaLuxe\Admin;

class Importer
{
    public static function init(): void
    {
    \add_action('admin_menu', [__CLASS__, 'menu']);
    \add_action('admin_init', [__CLASS__, 'maybe_schedule']);
    \add_action('admin_enqueue_scripts', [__CLASS__, 'enqueue_admin']);
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
                    <div id="aqlx-importer" class="card aqlx-card">
                        <div class="aqlx-toolbar" role="group" aria-label="Importer controls">
                            <div class="aqlx-group" aria-label="Run">
                                <button id="aqlx-start" class="button button-primary"><?php esc_html_e('Start Import', 'aqualuxe'); ?></button>
                                <button id="aqlx-preview" class="button"><?php esc_html_e('Preview', 'aqualuxe'); ?></button>
                                <button id="aqlx-export" class="button"><?php esc_html_e('Export Demo Content', 'aqualuxe'); ?></button>
                                <a id="aqlx-export-link" href="#" target="_blank" class="is-hidden">&nbsp;<?php esc_html_e('Open export', 'aqualuxe'); ?></a>
                                <button id="aqlx-download-export" type="button" class="button is-hidden">
                                    <?php esc_html_e('Download Export', 'aqualuxe'); ?>
                                </button>
                            </div>
                            <div class="aqlx-group" aria-label="Controls">
                                <button id="aqlx-pause" class="button" title="<?php esc_attr_e('Pause', 'aqualuxe'); ?>">&nbsp;<?php esc_html_e('Pause', 'aqualuxe'); ?></button>
                                <button id="aqlx-resume" class="button" title="<?php esc_attr_e('Resume', 'aqualuxe'); ?>"><?php esc_html_e('Resume', 'aqualuxe'); ?></button>
                                <button id="aqlx-cancel" class="button aqlx-button-danger" title="<?php esc_attr_e('Cancel', 'aqualuxe'); ?>">&nbsp;<?php esc_html_e('Cancel', 'aqualuxe'); ?></button>
                                <button id="aqlx-state" class="button" title="<?php esc_attr_e('View importer state', 'aqualuxe'); ?>"><?php esc_html_e('View State', 'aqualuxe'); ?></button>
                            </div>
                            <div class="aqlx-group" aria-label="Schedule">
                                <span id="aqlx-scheduled-badge" class="aqlx-badge is-hidden">
                                    <?php esc_html_e('Scheduled', 'aqualuxe'); ?>
                                </span>
                                <select id="aqlx-recurrence">
                                    <option value="hourly"><?php esc_html_e('Hourly', 'aqualuxe'); ?></option>
                                    <option value="twicedaily"><?php esc_html_e('Twice Daily', 'aqualuxe'); ?></option>
                                    <option value="daily" selected><?php esc_html_e('Daily', 'aqualuxe'); ?></option>
                                </select>
                                <button id="aqlx-schedule" class="button" title="<?php esc_attr_e('Schedule', 'aqualuxe'); ?>"><?php esc_html_e('Schedule', 'aqualuxe'); ?></button>
                                <button id="aqlx-clear-schedule" class="button" title="<?php esc_attr_e('Clear Schedule', 'aqualuxe'); ?>"><?php esc_html_e('Clear', 'aqualuxe'); ?></button>
                            </div>
                            <div class="aqlx-group" aria-label="Logs">
                                <button id="aqlx-open-audit" class="button" title="<?php esc_attr_e('Open latest audit log', 'aqualuxe'); ?>"><?php esc_html_e('Open Audit', 'aqualuxe'); ?></button>
                                <button id="aqlx-download-audit" class="button" title="<?php esc_attr_e('Download latest audit log', 'aqualuxe'); ?>"><?php esc_html_e('Download Audit', 'aqualuxe'); ?></button>
                                <label class="aqlx-audits-controls">
                                    <?php esc_html_e('Recent audits', 'aqualuxe'); ?>
                                    <select id="aqlx-audits" style="max-width:260px;">
                                        <option value="">--</option>
                                    </select>
                                    <button id="aqlx-open-selected-audit" type="button" class="button">&nbsp;<?php esc_html_e('Open', 'aqualuxe'); ?></button>
                                    <button id="aqlx-download-selected-audit" type="button" class="button">&nbsp;<?php esc_html_e('Download', 'aqualuxe'); ?></button>
                                </label>
                                <a id="aqlx-audit" href="#" target="_blank" style="display:none;">&nbsp;<?php esc_html_e('Open audit', 'aqualuxe'); ?></a>
                                <button id="aqlx-copy-log" class="button" title="<?php esc_attr_e('Copy the session log', 'aqualuxe'); ?>"><?php esc_html_e('Copy Log', 'aqualuxe'); ?></button>
                                <button id="aqlx-clear-log" class="button" title="<?php esc_attr_e('Clear the session log', 'aqualuxe'); ?>"><?php esc_html_e('Clear Log', 'aqualuxe'); ?></button>
                            </div>
                            <div class="aqlx-group aqlx-status" aria-live="polite">
                                <span id="aqlx-spinner" class="spinner" aria-hidden="true" hidden></span>
                                <span id="aqlx-status" role="status" aria-live="polite"></span>
                                <span id="aqlx-next" class="aqlx-muted" data-label="<?php echo esc_attr(__('Next run:', 'aqualuxe')); ?>"></span>
                                <span id="aqlx-sched" class="aqlx-muted" data-label="<?php echo esc_attr(__('Schedule:', 'aqualuxe')); ?>"></span>
                                <span id="aqlx-last" class="aqlx-muted" data-label="<?php echo esc_attr(__('Last run:', 'aqualuxe')); ?>"></span>
                            </div>
                            <div class="aqlx-group aqlx-danger" aria-label="Danger zone">
                                <button id="aqlx-flush" class="button button-secondary aqlx-button-danger">
                                    <?php esc_html_e('Delete demo content and reset state', 'aqualuxe'); ?>
                                </button>
                            </div>
                        </div>
                            <fieldset>
                                <legend><strong><?php esc_html_e('Entities to import', 'aqualuxe'); ?></strong></legend>
                                <label><input type="checkbox" id="aqlx-entities-all"> <?php esc_html_e('Select all', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="pages" checked> <?php esc_html_e('Pages & Menus', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="cpts" checked> <?php esc_html_e('CPTs (Services, Events, Testimonials)', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="users"> <?php esc_html_e('Users (customers, shop manager)', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="roles"> <?php esc_html_e('Roles & Capabilities (partner role)', 'aqualuxe'); ?></label><br>
                                <?php if (class_exists('WooCommerce')): ?>
                                <label><input type="checkbox" class="aqlx-entity" value="products" checked> <?php esc_html_e('Products (simple + variable)', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="wc_config" checked> <?php esc_html_e('WooCommerce Settings (payments, shipping)', 'aqualuxe'); ?></label><br>
                                <?php endif; ?>
                                <label><input type="checkbox" class="aqlx-entity" value="media" checked> <?php esc_html_e('Media (assets)', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="widgets"> <?php esc_html_e('Widgets (sidebar/footer)', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="options"> <?php esc_html_e('Options (tagline, locale hints)', 'aqualuxe'); ?></label><br>
                                <label><input type="checkbox" class="aqlx-entity" value="i18n"> <?php esc_html_e('Multi-language (duplicate into extra locales)', 'aqualuxe'); ?></label><br>
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
                                                                        <option value="en_GB">en_GB</option>
                                                                        <option value="de_DE">de_DE</option>
                                                                        <option value="fr_FR">fr_FR</option>
                                                                        <option value="es_ES">es_ES</option>
                                                                        <option value="si_LK">si_LK</option>
                                                                    </select>
                                                                </label><br>
                                                                <label><?php esc_html_e('Extra locales (comma-separated)', 'aqualuxe'); ?>
                                                                    <input type="text" id="aqlx-locales-extra" placeholder="fr_FR, de_DE" style="min-width:260px;">
                                                                </label><br>
                                                                <label><?php esc_html_e('Currency', 'aqualuxe'); ?>
                                                                    <select id="aqlx-currency">
                                                                        <option value="USD">USD</option>
                                                                        <option value="EUR">EUR</option>
                                                                        <option value="GBP">GBP</option>
                                                                        <option value="LKR">LKR</option>
                                                                        <option value="AUD">AUD</option>
                                                                        <option value="CAD">CAD</option>
                                                                    </select>
                                                                </label>
                                                        </fieldset>
                                                        <fieldset>
                                                                <legend><strong><?php esc_html_e('Reset Filters (optional)', 'aqualuxe'); ?></strong></legend>
                                                                    <label><?php esc_html_e('From date', 'aqualuxe'); ?> <input type="date" id="aqlx-from"></label>
                                                                    <label><?php esc_html_e('To date', 'aqualuxe'); ?> <input type="date" id="aqlx-to"></label>
                                                                    <button type="button" id="aqlx-last30" class="button" style="margin-left:8px;" title="<?php esc_attr_e('Fill last 30 days', 'aqualuxe'); ?>"><?php esc_html_e('Last 30 days', 'aqualuxe'); ?></button>
                            </fieldset>
                            <fieldset>
                                <legend><strong><?php esc_html_e('Assets', 'aqualuxe'); ?></strong></legend>
                                <label><?php esc_html_e('Image source', 'aqualuxe'); ?>
                                    <select id="aqlx-asset-provider">
                                        <option value="local_svg"><?php esc_html_e('Local placeholders (SVG)', 'aqualuxe'); ?></option>
                                        <option value="picsum"><?php esc_html_e('Picsum (random photos)', 'aqualuxe'); ?></option>
                                        <option value="wikimedia"><?php esc_html_e('Wikimedia Commons (free, requires attribution)', 'aqualuxe'); ?></option>
                                        <option value="custom"><?php esc_html_e('Custom URLs (one per line)', 'aqualuxe'); ?></option>
                                    </select>
                                </label><br>
                                <label><?php esc_html_e('Search keywords', 'aqualuxe'); ?> <input type="text" id="aqlx-asset-query" placeholder="aquarium, fish, coral" style="min-width:260px;"></label><br>
                                <label><?php esc_html_e('Media count (for Media step)', 'aqualuxe'); ?> <input type="number" id="aqlx-asset-count" min="1" max="50" value="8" style="width:80px;"></label><br>
                                <label style="display:block; margin-top:6px;">
                                    <?php esc_html_e('Custom media URLs (one per line)', 'aqualuxe'); ?>
                                    <textarea id="aqlx-asset-custom-urls" rows="3" style="width:100%; max-width:600px;" placeholder="https://example.com/image1.jpg\nhttps://example.com/image2.png"></textarea>
                                </label>
                                <p class="description"><?php esc_html_e('For Wikimedia, ensure attribution in theme or product pages. For Custom URLs, you must have rights to import those assets.', 'aqualuxe'); ?></p>
                            </fieldset>
                        </div>
                        
                        <div id="aqlx-progress" role="progressbar" aria-label="<?php echo esc_attr(__('Import progress', 'aqualuxe')); ?>" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                            <div id="aqlx-progress-bar"></div>
                        </div>
                                                <pre id="aqlx-log" class="aqlx-log" role="log" aria-live="polite" aria-atomic="false"></pre>
                    </div>
          <form method="post">
            <?php wp_nonce_field('aqualuxe_import'); ?>
            <label><input type="checkbox" name="aqualuxe_reset" value="1"> <?php esc_html_e('Reset (danger) — delete existing demo content before importing', 'aqualuxe'); ?></label>
                        <p><button class="button button-primary" name="aqualuxe_import" value="1"><?php esc_html_e('Run Import', 'aqualuxe'); ?></button></p>
                    </form>
                </div>
        <?php
    }

    public static function enqueue_admin($hook): void
    {
        if (!\current_user_can('manage_options')) return;
        if (strpos((string)$hook, 'aqualuxe-importer') === false) {
            $screen = function_exists('get_current_screen') ? get_current_screen() : null;
            if (!$screen || strpos((string)$screen->id, 'aqualuxe-importer') === false) {
                return;
            }
        }
        $theme_uri = \get_template_directory_uri();
        $theme_dir = \get_template_directory();

        // Prefer Mix-built assets from manifest; fall back to raw files if not built.
        $manifest_file = $theme_dir . '/assets/dist/mix-manifest.json';
        $css_uri = '';
        $js_uri = '';
        if (file_exists($manifest_file)) {
            $manifest = json_decode((string)file_get_contents($manifest_file), true) ?: [];
            $assets_uri = \defined('AQUALUXE_ASSETS_URI') ? AQUALUXE_ASSETS_URI : ($theme_uri . '/assets/dist');
            if (!empty($manifest['/css/importer.css'])) {
                $css_uri = rtrim($assets_uri, '/') . $manifest['/css/importer.css'];
            }
            if (!empty($manifest['/js/importer.js'])) {
                $js_uri = rtrim($assets_uri, '/') . $manifest['/js/importer.js'];
            }
        }
        if (!$css_uri) {
            $css_rel = '/assets/admin/importer.css';
            if (file_exists($theme_dir . $css_rel)) {
                $css_uri = $theme_uri . $css_rel;
            }
        }
        if (!$js_uri) {
            $js_rel = '/assets/admin/importer.js';
            if (file_exists($theme_dir . $js_rel)) {
                $js_uri = $theme_uri . $js_rel;
            }
        }
        if ($css_uri) {
            \wp_enqueue_style('aqlx-importer-admin', $css_uri, [], AQUALUXE_VERSION);
        }
        if ($js_uri) {
            \wp_enqueue_script('aqlx-importer-admin', $js_uri, [], AQUALUXE_VERSION, true);
            \wp_localize_script('aqlx-importer-admin', 'AQLX_ADMIN', [
                'restUrl' => \esc_url_raw(\get_rest_url(null, '/')),
                'nonce'   => \wp_create_nonce('wp_rest'),
                'i18n'    => [
                    'hourly' => \__('Hourly', 'aqualuxe'),
                    'twiceDaily' => \__('Twice Daily', 'aqualuxe'),
                    'daily' => \__('Daily', 'aqualuxe'),
                    'selectEntity' => \__('Select at least one entity to import.', 'aqualuxe'),
                    'invalidRange' => \__('From date must be before To date.', 'aqualuxe'),
                    'noExport' => \__('No export available. Use "Export Demo Content" first.', 'aqualuxe'),
                    'copyOk' => \__('Log copied to clipboard.', 'aqualuxe'),
                    'copyFail' => \__('Copy failed.', 'aqualuxe'),
                    'noAudit' => \__('No audit log available yet.', 'aqualuxe'),
                    'auditDlFail' => \__('Download failed. Opening instead…', 'aqualuxe'),
                    'confirmFlush' => \__('This will delete demo content and reset state. Continue?', 'aqualuxe'),
                    'confirmCancel' => \__('Cancel will rollback created items from this run and clear state. Continue?', 'aqualuxe'),
                    'scheduledActive' => \__('A schedule is active; clear it to run manually.', 'aqualuxe'),
                    'labels' => [
                        'recurrence' => [
                            'hourly' => \__('Hourly', 'aqualuxe'),
                            'twicedaily' => \__('Twice Daily', 'aqualuxe'),
                            'daily' => \__('Daily', 'aqualuxe'),
                        ],
                        'entities' => [
                            'pages' => \__('Pages & Menus', 'aqualuxe'),
                            'cpts' => \__('CPTs (Services, Events, Testimonials)', 'aqualuxe'),
                            'users' => \__('Users', 'aqualuxe'),
                            'roles' => \__('Roles & Capabilities', 'aqualuxe'),
                            'products' => \__('Products', 'aqualuxe'),
                            'wc_config' => \__('WooCommerce Settings', 'aqualuxe'),
                            'media' => \__('Media', 'aqualuxe'),
                            'widgets' => \__('Widgets', 'aqualuxe'),
                            'options' => \__('Options', 'aqualuxe'),
                        ],
                        'next' => \__('Next run:', 'aqualuxe'),
                        'schedule' => \__('Schedule:', 'aqualuxe'),
                        'last' => \__('Last run:', 'aqualuxe'),
                        'reset' => \__('Reset:', 'aqualuxe'),
                        'yes' => \__('Yes', 'aqualuxe'),
                        'no' => \__('No', 'aqualuxe'),
                    ],
                ],
            ]);
        }
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
                self::start((array) ($cfg['entities'] ?? []), (bool) ($cfg['reset'] ?? false), (int) ($cfg['volume'] ?? 10), 'skip', 'en_US', [], [], 'USD');
                while (!(self::step()['done'] ?? false)) { /* iterate */ }
            }); }
            return ['ok' => true, 'scheduled' => true, 'recurrence' => $recurrence];
        }

    private static function reset_content(): string
    {
    $types = ['post','page','attachment','service','event','testimonial','nav_menu_item'];
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
            'Contact' => ['slug' => 'contact', 'content' => '[aqualuxe_contact address="AquaLuxe HQ, Colombo" ]'],
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
        // Testimonials
        $testimonials = [
            ['name' => 'Liam', 'text' => 'AquaLuxe transformed our hotel lobby with a stunning reef display. Guests love it!'],
            ['name' => 'Maya', 'text' => 'The maintenance plan keeps our tank pristine. Friendly, knowledgeable team.'],
            ['name' => 'Kenji', 'text' => 'Rare fish arrived healthy and vibrant. Professional export handling.'],
        ];
        foreach ($testimonials as $t) {
            \wp_insert_post([
                'post_title' => $t['name'],
                'post_type' => 'testimonial',
                'post_status' => 'publish',
                'post_content' => $t['text'],
                'post_excerpt' => $t['text'],
            ]);
        }
    return 'Created sample Services, Events, and Testimonials.';
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
    $cat_count = count($cat_ids);
    $tag_count = count($tag_ids);
    for ($i=1;$i<=6;$i++) {
            $p = new \WC_Product_Simple();
            $p->set_name('AquaLuxe Specimen ' . $i);
            $p->set_status('publish');
            $p->set_regular_price((string) (50 + 10 * $i));
            $p->set_manage_stock(true);
            $p->set_stock_quantity(5 + $i);
            $p->set_catalog_visibility('visible');
            $pid = $p->save();
            if (!empty($cat_ids)) { \wp_set_object_terms($pid, [$cat_ids[$i % max(1, $cat_count)]], 'product_cat'); }
            if (!empty($tag_ids)) { \wp_set_object_terms($pid, [$tag_ids[$i % max(1, $tag_count)]], 'product_tag', true); }
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
    public static function start(array $entities, bool $reset, int $volume = 10, string $policy = 'skip', string $locale = 'en_US', array $range = [], array $assets = [], string $currency = 'USD', array $localesExtra = []): array
    {
        $runId = 'aqlx-' . gmdate('Ymd-His') . '-' . substr(wp_hash((string) wp_rand()), 0, 8);
        $upload = \wp_upload_dir();
        $logDir = trailingslashit($upload['basedir']) . 'aqualuxe-import-logs/';
        \wp_mkdir_p($logDir);
        $auditFile = $logDir . $runId . '.jsonl';
        $auditUrl = trailingslashit($upload['baseurl']) . 'aqualuxe-import-logs/' . $runId . '.jsonl';
        // sanitize inputs
        $entities = array_values(array_filter(array_map('sanitize_key', (array) $entities)));
        $policy = in_array($policy, ['skip','overwrite','merge'], true) ? $policy : 'skip';
        $locale = sanitize_text_field($locale ?: 'en_US');
        $assets = is_array($assets) ? $assets : [];
        $assets = [
            'provider' => sanitize_key($assets['provider'] ?? 'local_svg'),
            'query' => sanitize_text_field($assets['query'] ?? ''),
            'count' => max(1, min(100, (int) ($assets['count'] ?? 8))),
            'urls' => array_values(array_filter(array_map('esc_url_raw', (array) ($assets['urls'] ?? []))))
        ];
        $currency = strtoupper(sanitize_text_field($currency ?: 'USD'));
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
            'created_users' => [],
            'created_roles' => [],
            'created_widgets' => [],
            'options_backup' => [],
            'policy' => $policy,
            'locale' => $locale ?: 'en_US',
            'range' => [ 'from' => (string) ($range['from'] ?? ''), 'to' => (string) ($range['to'] ?? '') ],
            'assets' => $assets,
            'currency' => $currency,
            'locales_extra' => array_values(array_filter(array_map('sanitize_text_field', (array) $localesExtra))),
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
                case 'pages':
                {
                    [$logStep, $done, $partial] = self::process_pages_step($state);
                    $log = array_merge($log, $logStep);
                    if ($done) { $state['index'] = $i + 1; }
                    break; }
                case 'cpts':
                {
                    [$logStep, $done, $partial] = self::process_cpts_step($state);
                    $log = array_merge($log, $logStep);
                    if ($done) { $state['index'] = $i + 1; }
                    break; }
                case 'users':
                {
                    [$logStep, $done, $partial] = self::process_users_step($state);
                    $log = array_merge($log, $logStep);
                    if ($done) { $state['index'] = $i + 1; }
                    break; }
                case 'roles':
                {
                    [$logStep, $done, $partial] = self::process_roles_step($state);
                    $log = array_merge($log, $logStep);
                    if ($done) { $state['index'] = $i + 1; }
                    break; }
                case 'products':
                {
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
                    if (class_exists('WooCommerce')) {
                        // Set currency if provided
                        $cur = (string) ($state['currency'] ?? ''); if ($cur) { try { \update_option('woocommerce_currency', $cur); } catch (\Throwable $e) {} }
                        $log[] = self::configure_wc();
                    } elseif (!class_exists('WooCommerce')) {
                        $log[] = 'WooCommerce not active; skipping wc_config.';
                    }
                    $state['index'] = $i + 1;
                    $partial = 1.0;
                    break;
                case 'media':
                {
                    [$logStep, $done, $partial] = self::process_media_step($state);
                    $log = array_merge($log, $logStep);
                    if ($done) { $state['index'] = $i + 1; }
                    break; }
                case 'widgets':
                {
                    [$logStep, $done, $partial] = self::process_widgets_step($state);
                    $log = array_merge($log, $logStep);
                    if ($done) { $state['index'] = $i + 1; }
                    break; }
                case 'options':
                {
                    [$logStep, $done, $partial] = self::process_options_step($state);
                    $log = array_merge($log, $logStep);
                    if ($done) { $state['index'] = $i + 1; }
                    break; }
                case 'i18n':
                {
                    [$logStep, $done, $partial] = self::process_i18n_step($state);
                    $log = array_merge($log, $logStep);
                    if ($done) { $state['index'] = $i + 1; }
                    break; }
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
                        'created_terms' => count((array) ($state['created_terms'] ?? [])),
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
            if (in_array($e, ['pages','cpts','users','roles','products','wc_config','media','widgets','options','i18n'], true)) { $steps[] = $e; }
        }
    // Allow plugins/themes to add or reorder steps
    return apply_filters('aqlx_importer_build_steps', $steps, $entities);
    }

    private static function map_entities_to_types(array $entities): array
    {
        $map = [
            'pages' => ['page','nav_menu_item'],
            'cpts' => ['service','event','testimonial'],
            'users' => [],
            'roles' => [],
            'products' => ['product','product_variation'],
            'widgets' => [],
            'options' => [],
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
        // Lookup by sanitized name to avoid deprecated get_page_by_title
        $name = sanitize_title($slug);
        $q = new \WP_Query([
            'post_type' => 'attachment',
            'name' => $name,
            'post_status' => 'inherit',
            'posts_per_page' => 1,
            'fields' => 'ids',
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ]);
        if ($q->have_posts()) { return (int) ($q->posts[0]); }
        $attachment = [
            'post_title' => $slug,
            'post_type' => 'attachment',
            'post_status' => 'inherit',
            'guid' => $url,
            'post_mime_type' => 'image/svg+xml',
            'post_name' => $name,
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
                case 'cpts': $report['counts']['cpts'] = 10; break;
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

    private static function track_created_user(int $id): void
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$id) return;
        $list = (array) ($state['created_users'] ?? []);
        if (!in_array($id, $list, true)) { $list[] = $id; $state['created_users'] = $list; \update_option('aqlx_import_state', $state, false); }
    }

    private static function track_created_role(string $role): void
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$role) return;
        $list = (array) ($state['created_roles'] ?? []);
        if (!in_array($role, $list, true)) { $list[] = $role; $state['created_roles'] = $list; \update_option('aqlx_import_state', $state, false); }
    }

    private static function track_created_widget(string $id): void
    {
        $state = (array) \get_option('aqlx_import_state', []);
        if (!$id) return;
        $list = (array) ($state['created_widgets'] ?? []);
        if (!in_array($id, $list, true)) { $list[] = $id; $state['created_widgets'] = $list; \update_option('aqlx_import_state', $state, false); }
    }

    // Find a post by slug (derived from title) for a given post type; returns ID or 0.
    /**
     * Find an existing post by slug derived from a title for a given post type.
     *
     * @param string $post_type Post type to search.
     * @param string $title     Title to derive slug from.
     * @return int Post ID or 0 when not found.
     */
    private static function find_post_by_title_slug(string $post_type, string $title): int
    {
        $slug = sanitize_title($title);
        $q = new \WP_Query([
            'post_type' => $post_type,
            'name' => $slug,
            'post_status' => 'any',
            'posts_per_page' => 1,
            'fields' => 'ids',
            'no_found_rows' => true,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        ]);
        return $q->have_posts() ? (int) $q->posts[0] : 0;
    }

    private static function backup_option(string $key): void
    {
        $state = (array) \get_option('aqlx_import_state', []);
        $bk = (array) ($state['options_backup'] ?? []);
        if (!array_key_exists($key, $bk)) { $bk[$key] = get_option($key, null); $state['options_backup'] = $bk; \update_option('aqlx_import_state', $state, false); }
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
        // Delete created users (best-effort)
        $users = array_reverse((array) ($state['created_users'] ?? []));
        foreach ($users as $uid) { if ($uid && get_user_by('id', $uid)) { require_once ABSPATH . 'wp-admin/includes/user.php'; \wp_delete_user((int) $uid); } }
        $state['created_users'] = [];
        // Remove created roles
        $roles = array_reverse((array) ($state['created_roles'] ?? []));
        foreach ($roles as $r) { if (get_role($r)) { remove_role($r); } }
        $state['created_roles'] = [];
        // Remove created widgets from sidebars
        $widgets = array_reverse((array) ($state['created_widgets'] ?? []));
        if ($widgets) {
            $sidebars = get_option('sidebars_widgets', []);
            foreach ($sidebars as $sb => $items) {
                if (!is_array($items)) continue;
                $sidebars[$sb] = array_values(array_filter($items, function($wid) use ($widgets){ return !in_array($wid, $widgets, true); }));
            }
            update_option('sidebars_widgets', $sidebars);
        }
        $state['created_widgets'] = [];
        // Restore options backup
        $bk = (array) ($state['options_backup'] ?? []);
        foreach ($bk as $k => $v) { update_option($k, $v); }
        $state['options_backup'] = [];
        \update_option('aqlx_import_state', $state, false);
        try { self::audit($state, 'rollback', [ 'postsRolledBack' => count($posts), 'termsRolledBack' => count($terms) ]); } catch (\Throwable $ie) {}
    }

    // Append a JSONL audit event for the current run.
    /**
     * Append a JSONL audit line.
     *
     * @param array  $state   Importer state (must include audit_file).
     * @param string $event   Event name (start, tick, done, error, etc.).
     * @param array  $payload Arbitrary payload to include.
     */
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
    $itemsCount = count($items);
    while ($idx < $itemsCount && $processed < $volume) {
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
            ++$idx; ++$processed;
        }
    $ps['index'] = $idx; $ps['total'] = count($items);
        // After creating pages, set home/blog and menu once
        if (!$ps['homeSet']) {
            $home = \get_page_by_path('home'); $blog = \get_page_by_path('blog');
            if ($home && $blog) {
                // Backup options so rollback can restore
                self::backup_option('show_on_front');
                self::backup_option('page_on_front');
                self::backup_option('page_for_posts');
                \update_option('show_on_front', 'page'); \update_option('page_on_front', $home->ID); \update_option('page_for_posts', $blog->ID);
                $ps['homeSet'] = true; $log[] = 'Configured front and posts pages.'; try { self::audit($state, 'set-option', [ 'show_on_front' => 'page', 'page_on_front' => (int) $home->ID, 'page_for_posts' => (int) $blog->ID ]); } catch (\Throwable $ie) {}
            }
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
    $done = $ps['index'] >= $itemsCount;
    $partial = min(1.0, max(0.0, ($itemsCount ? $ps['index'] / $itemsCount : 1.0)));
        return [$log, $done, $partial];
    }

    private static function process_cpts_step(array &$state): array
    {
        $volume = (int) ($state['volume'] ?? 10);
        $policy = (string) ($state['policy'] ?? 'skip');
        $defaults = [ 'service_total' => 4, 'service_index' => 0, 'event_total' => 3, 'event_index' => 0, 'testimonial_total' => 3, 'testimonial_index' => 0 ];
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
            ++$cs['service_index']; ++$processed;
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
            ++$cs['event_index']; ++$processed;
        }
        // Finally testimonials
        while ($cs['testimonial_index'] < $cs['testimonial_total'] && $processed < $volume) {
            $i = $cs['testimonial_index'] + 1;
            $title = ['Liam','Maya','Kenji'][$cs['testimonial_index']] ?? ('Client ' . $i);
            $text = [
                'AquaLuxe transformed our hotel lobby with a stunning reef display. Guests love it!',
                'The maintenance plan keeps our tank pristine. Friendly, knowledgeable team.',
                'Rare fish arrived healthy and vibrant. Professional export handling.',
            ][$cs['testimonial_index']] ?? 'Great experience with AquaLuxe.';
            $existing = \post_exists($title, '', '', 'testimonial');
            if (!$existing) {
                $pid = \wp_insert_post([ 'post_title' => $title, 'post_type' => 'testimonial', 'post_status' => 'publish', 'post_content' => $text, 'post_excerpt' => $text ]);
                if ($pid) { self::track_created_post((int) $pid); $log[] = 'Created testimonial #' . $i; }
            } else {
                if ($policy === 'overwrite') { \wp_update_post([ 'ID' => $existing, 'post_status' => 'publish' ]); $log[] = 'Updated testimonial #' . $i; }
                else { $log[] = 'Skipped testimonial #' . $i; }
            }
            ++$cs['testimonial_index']; ++$processed;
        }
        $state['step_state']['cpts'] = $cs; \update_option('aqlx_import_state', $state, false);
        $total = max(1, (int) $cs['service_total'] + (int) $cs['event_total'] + (int) $cs['testimonial_total']);
        $doneCount = (int) $cs['service_index'] + (int) $cs['event_index'] + (int) $cs['testimonial_index'];
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
            $existing_id = self::find_post_by_title_slug('product', $name);
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
            ++$ps['simple_index']; ++$processed;
        }
        // Variable product (counts as one unit)
        if (!$ps['variable_done'] && $processed < $volume) {
            $name = 'AquaLuxe Exhibit Tank';
            $existing_id = self::find_post_by_title_slug('product', $name);
            if (!$existing_id) {
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
                if ($policy === 'overwrite') { $p = wc_get_product($existing_id); if ($p) { $p->save(); $log[] = 'Verified variable product: ' . $name; } }
                else { $log[] = 'Skipped variable product: already exists.'; }
            }
            $ps['variable_done'] = true; ++$processed;
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
                $cs = (array) ($ss['cpts'] ?? []);
                $tot = (int) (($cs['service_total'] ?? 0) + ($cs['event_total'] ?? 0) + ($cs['testimonial_total'] ?? 0));
                $done = (int) (($cs['service_index'] ?? 0) + ($cs['event_index'] ?? 0) + ($cs['testimonial_index'] ?? 0));
                $tot = max(1, $tot);
                return max(0.0, min(1.0, $done / $tot));
            case 'products':
                $ps2 = (array) ($ss['products'] ?? []); $tot2 = (int) (($ps2['simple_total'] ?? 0) + 1); $done2 = (int) (($ps2['simple_index'] ?? 0) + (!empty($ps2['variable_done']) ? 1 : 0)); $tot2 = max(1, $tot2); return max(0.0, min(1.0, $done2 / $tot2));
            case 'users':
                $us = (array) ($ss['users'] ?? []); $t = max(1, (int) ($us['total'] ?? 0)); $d = (int) ($us['index'] ?? 0); return max(0.0, min(1.0, ($t ? $d / $t : 1.0)));
            case 'media':
                $ms = (array) ($ss['media'] ?? []); $t2 = max(1, (int) ($ms['total'] ?? 0)); $d2 = (int) ($ms['index'] ?? 0); return max(0.0, min(1.0, ($t2 ? $d2 / $t2 : 1.0)));
            default:
                return 0.0;
        }
    }

    private static function process_users_step(array &$state): array
    {
        $volume = (int) ($state['volume'] ?? 10);
        $defaults = [ 'total' => 5, 'index' => 0 ];
        $us = &self::ensure_step_state($state, 'users', $defaults);
        $log = []; $processed = 0;
        while ($us['index'] < $us['total'] && $processed < $volume) {
            $i = $us['index'] + 1;
            $email = 'customer' . $i . '@example.com'; $username = 'customer' . $i;
            if (!email_exists($email) && !username_exists($username)) {
                $uid = wp_insert_user([
                    'user_login' => $username,
                    'user_email' => $email,
                    'user_pass' => wp_generate_password(12, true),
                    'display_name' => 'Customer ' . $i,
                    'role' => class_exists('WooCommerce') ? 'customer' : 'subscriber',
                ]);
                if (!is_wp_error($uid)) { self::track_created_user((int) $uid); $log[] = 'Created user: ' . $username; try { self::audit($state, 'create', [ 'type' => 'user', 'id' => (int) $uid, 'username' => $username ]); } catch (\Throwable $ie) {} }
            } else {
                $log[] = 'Skipped existing user: ' . $username;
            }
            ++$us['index']; ++$processed;
        }
        $state['step_state']['users'] = $us; \update_option('aqlx_import_state', $state, false);
        $done = $us['index'] >= $us['total'];
        $partial = min(1.0, max(0.0, ($us['total'] ? $us['index'] / $us['total'] : 1.0)));
        return [$log, $done, $partial];
    }

    private static function process_roles_step(array &$state): array
    {
        $log = [];
        // Ensure a lightweight 'partner' role exists
        if (!get_role('partner')) {
            add_role('partner', 'Partner', [ 'read' => true ]);
            self::track_created_role('partner');
            $log[] = 'Created role: partner'; try { self::audit($state, 'create', [ 'type' => 'role', 'slug' => 'partner' ]); } catch (\Throwable $ie) {}
        } else { $log[] = 'Role already exists: partner'; }
        return [$log, true, 1.0];
    }

    private static function process_widgets_step(array &$state): array
    {
        // Minimal footer widgets seeding using sidebars_widgets and widget_text
        $log = [];
    // Backup options before mutation for rollback
    self::backup_option('sidebars_widgets');
    self::backup_option('widget_text');
    $sidebars = get_option('sidebars_widgets', []);
    $widgets = get_option('widget_text', []);
        if (!is_array($widgets)) { $widgets = []; }
    $newIdx = 1; while (isset($widgets[$newIdx])) { ++$newIdx; }
        $widgets[$newIdx] = [ 'title' => 'AquaLuxe', 'text' => '<p>Bringing elegance to aquatic life – globally.</p>' ];
        update_option('widget_text', $widgets);
        if (!isset($sidebars['footer-1'])) { $sidebars['footer-1'] = []; }
    $sidebars['footer-1'][] = 'text-' . $newIdx;
        update_option('sidebars_widgets', $sidebars);
    self::track_created_widget('text-' . $newIdx);
    $log[] = 'Added footer widget'; try { self::audit($state, 'create', [ 'type' => 'widget', 'id' => 'text-' . $newIdx, 'sidebar' => 'footer-1' ]); } catch (\Throwable $ie) {}
        return [$log, true, 1.0];
    }

    private static function process_options_step(array &$state): array
    {
        $log = [];
    // Backup before mutation
    self::backup_option('blogdescription');
    self::backup_option('aqlx_locale_hint');
    if (!get_option('blogdescription')) { update_option('blogdescription', 'Bringing elegance to aquatic life – globally.'); $log[] = 'Set tagline.'; }
        // Example locale hint stored under theme option
        $locale = (string) ($state['locale'] ?? 'en_US');
        update_option('aqlx_locale_hint', $locale);
        try { self::audit($state, 'set-option', [ 'blogdescription' => get_option('blogdescription'), 'aqlx_locale_hint' => $locale ]); } catch (\Throwable $ie) {}
        return [$log, true, 1.0];
    }

    /** i18n step: duplicate created posts into extra locales; optionally link via Polylang if present. */
    private static function process_i18n_step(array &$state): array
    {
        $logs = [];
        $locales = array_values(array_filter(array_map('sanitize_text_field', (array) ($state['locales_extra'] ?? []))));
        if (!$locales) { return [['i18n: no extra locales provided'], true, 1.0]; }
        $created = array_values(array_unique(array_map('intval', (array) ($state['created_posts'] ?? []))));
        if (!$created) { return [['i18n: nothing to translate'], true, 1.0]; }

        $batch = max(1, (int) ($state['volume'] ?? 10));
        $offset = (int) ($state['i18n_offset'] ?? 0);
        $slice = array_slice($created, $offset, $batch);
        $done = ($offset + count($slice)) >= count($created);

        foreach ($slice as $orig_id) {
            if (!get_post($orig_id)) { continue; }
            foreach ($locales as $loc) {
                // Skip if already duplicated
                $key = 'aqlx_i18n_' . $loc;
                $existing = (int) get_post_meta($orig_id, $key, true);
                if ($existing && get_post($existing)) { continue; }

                $orig = get_post($orig_id);
                $new_post = [
                    'post_type' => $orig->post_type,
                    'post_status' => $orig->post_status,
                    'post_title' => $orig->post_title . ' [' . $loc . ']',
                    'post_content' => $orig->post_content,
                    'post_excerpt' => $orig->post_excerpt,
                    'post_author' => $orig->post_author,
                    'post_parent' => $orig->post_parent,
                    'menu_order' => $orig->menu_order,
                ];
                $new_id = wp_insert_post($new_post, true);
                if (is_wp_error($new_id) || !$new_id) { $logs[] = 'i18n: failed for post ' . $orig_id . ' -> ' . $loc; continue; }

                // Copy terms
                $taxes = get_object_taxonomies($orig->post_type, 'names');
                foreach ($taxes as $tax) {
                    $terms = wp_get_object_terms($orig_id, $tax, ['fields' => 'ids']);
                    if (!is_wp_error($terms) && $terms) { wp_set_object_terms($new_id, $terms, $tax); }
                }
                // Copy thumbnail
                $thumb = get_post_thumbnail_id($orig_id);
                if ($thumb) { set_post_thumbnail($new_id, $thumb); }
                // Mark origin and locale
                update_post_meta($new_id, '_aqlx_locale', $loc);
                update_post_meta($new_id, '_aqlx_origin', $orig_id);
                update_post_meta($orig_id, $key, $new_id);

                // Polylang linking if available
                if (function_exists('\\pll_set_post_language') && function_exists('\\pll_save_post_translations')) {
                    $default_lang = function_exists('\\pll_default_language') ? \call_user_func('\\pll_default_language', 'slug') : null;
                    $lang_map = [];
                    // Try assign languages to both posts
                    if ($default_lang) { \call_user_func('\\pll_set_post_language', $orig_id, $default_lang); }
                    \call_user_func('\\pll_set_post_language', $new_id, $loc);
                    // Link as translations
                    $lang_map[$loc] = $new_id;
                    if ($default_lang) { $lang_map[$default_lang] = $orig_id; }
                    try { \call_user_func('pll_save_post_translations', $lang_map); } catch (\Throwable $e) {}
                }

                // Track as created
                $state['created_posts'][] = (int) $new_id;
                $logs[] = 'i18n: duplicated post ' . $orig_id . ' -> ' . $new_id . ' (' . $loc . ')';
            }
        }
        $state['i18n_offset'] = $offset + count($slice);
        $total = max(1, count($created));
        $partial = min(1.0, $state['i18n_offset'] / $total);
        return [$logs, $done, $partial];
    }

    /**
     * Media step: fetch and import media assets based on chosen provider.
     * Supports providers: local_svg (placeholders), picsum (random photos), wikimedia (Commons API).
     */
    private static function process_media_step(array &$state): array
    {
        $volume = (int) ($state['volume'] ?? 10);
        $assetsCfg = (array) ($state['assets'] ?? []);
        $defaults = [ 'total' => max(1, (int) ($assetsCfg['count'] ?? 8)), 'index' => 0, 'items' => [] ];

        $ms = &self::ensure_step_state($state, 'media', $defaults);
        $log = []; $processed = 0;
        // Prefetch candidates once
        if (empty($ms['items'])) {
            $ms['items'] = self::fetch_media_candidates($assetsCfg);
            if (!$ms['items']) { $log[] = 'No media candidates found; falling back to local placeholders.'; $assetsCfg['provider'] = 'local_svg'; }
        }
        while ($ms['index'] < $ms['total'] && $processed < $volume) {
            $item = $ms['items'][$ms['index']] ?? null;
            $attId = 0;
            if ($assetsCfg['provider'] === 'local_svg' || empty($item)) {
                // fallback: create local SVG placeholder
                $attId = self::ensure_demo_image('media-' . ($ms['index'] + 1));
            } else {
                $url = (string) ($item['url'] ?? '');
                $title = (string) ($item['title'] ?? ('Media ' . ($ms['index'] + 1)));
                $meta = [
                    '_aqlx_attribution' => [
                        'provider' => (string) ($item['provider'] ?? ($assetsCfg['provider'] ?? '')),
                        'source_url' => (string) ($item['source'] ?? $url),
                        'author' => (string) ($item['author'] ?? ''),
                        'license' => (string) ($item['license'] ?? ''),
                    ]
                ];
                $attId = self::sideload_media($url, $title, $meta);
                if (!$attId) { $log[] = 'Failed to import media: ' . $url; }
            }
            if ($attId) { self::track_created_post((int) $attId); $log[] = 'Imported media #' . ($ms['index'] + 1); }
            ++$ms['index']; ++$processed;
        }
        $state['step_state']['media'] = $ms; \update_option('aqlx_import_state', $state, false);
        $done = $ms['index'] >= $ms['total'];
        $partial = min(1.0, max(0.0, ($ms['total'] ? $ms['index'] / $ms['total'] : 1.0)));
        return [$log, $done, $partial];
    }

    /** Build candidate media items based on provider config. */
    private static function fetch_media_candidates(array $assets): array
    {
        $provider = sanitize_key($assets['provider'] ?? 'local_svg');
        $count = max(1, min(50, (int) ($assets['count'] ?? 8)));
        $query = sanitize_text_field($assets['query'] ?? 'aquarium');
        $urls = array_values(array_filter(array_map('esc_url_raw', (array) ($assets['urls'] ?? []))));
        $items = [];
        // Allow plugins/themes to override providers
        $override = apply_filters('aqlx_importer_media_candidates', null, $assets);
        if (is_array($override)) { return array_slice($override, 0, $count); }
        if ($provider === 'custom' && $urls) {
            foreach (array_slice($urls, 0, $count) as $i => $u) {
                $items[] = [ 'url' => $u, 'title' => 'Custom ' . ($i+1), 'provider' => 'custom', 'source' => $u, 'author' => '', 'license' => '' ];
            }
        } elseif ($provider === 'picsum') {
            for ($i=0; $i<$count; $i++) {
                $seed = substr(wp_hash($query . '-' . $i . '-' . wp_rand()), 0, 8);
                $url = 'https://picsum.photos/seed/' . rawurlencode($seed) . '/1200/800';
                $items[] = [ 'url' => $url, 'title' => 'Picsum ' . ($i+1), 'provider' => 'picsum', 'source' => 'https://picsum.photos', 'author' => '', 'license' => 'CC0-like' ];
            }
        } elseif ($provider === 'wikimedia') {
            // Query Wikimedia Commons for images matching the search
            $cacheKey = 'aqlx_wm_' . md5(strtolower($query) . '|' . $count);
            $cached = get_transient($cacheKey);
            if (is_array($cached)) { return array_slice($cached, 0, $count); }
            $endpoint = 'https://commons.wikimedia.org/w/api.php';
            $params = [ 'action' => 'query', 'generator' => 'search', 'gsrsearch' => $query, 'gsrlimit' => min(25, $count), 'prop' => 'imageinfo', 'iiprop' => 'url|extmetadata', 'iiurlwidth' => 1200, 'format' => 'json', 'origin' => '*' ];
            $url = $endpoint . '?' . http_build_query($params, '', '&');
            $resp = wp_remote_get($url, [ 'timeout' => 15, 'user-agent' => 'AquaLuxeImporter/1.0; (+https://aqualuxe.example)' ]);
            if (!is_wp_error($resp) && wp_remote_retrieve_response_code($resp) === 200) {
                $body = json_decode(wp_remote_retrieve_body($resp), true);
                $pages = (array) ($body['query']['pages'] ?? []);
                foreach ($pages as $p) {
                    $ii = (array) ($p['imageinfo'][0] ?? []);
                    $imgUrl = (string) ($ii['thumburl'] ?? $ii['url'] ?? '');
                    if ($imgUrl) {
                        $meta = (array) ($ii['extmetadata'] ?? []);
                        $author = (string) (($meta['Artist']['value'] ?? '') ?: '');
                        $license = (string) (($meta['LicenseShortName']['value'] ?? '') ?: '');
                        $items[] = [ 'url' => $imgUrl, 'title' => (string) ($p['title'] ?? 'Wikimedia Image'), 'provider' => 'wikimedia', 'source' => (string) ($ii['descriptionurl'] ?? 'https://commons.wikimedia.org'), 'author' => wp_strip_all_tags($author), 'license' => wp_strip_all_tags($license) ];
                        if (count($items) >= $count) break;
                    }
                }
                // Cache for an hour
                set_transient($cacheKey, $items, HOUR_IN_SECONDS);
            }
        }
        // Fallback to local placeholders if remote empty
        if (!$items) {
            for ($i=0; $i<$count; $i++) { $items[] = [ 'url' => '', 'title' => 'Placeholder ' . ($i+1), 'provider' => 'local_svg' ]; }
        }
        return $items;
    }

    /** Download a remote media file and create an attachment; returns attachment ID or 0. */
    private static function sideload_media(string $url, string $title, array $meta = []): int
    {
        if (!$url) return 0;
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';
        $id = 0;
        try {
            $attId = media_sideload_image($url, 0, $title, 'id');
            if (!is_wp_error($attId)) {
                $id = (int) $attId;
                if ($id && is_array($meta)) {
                    foreach ($meta as $k => $v) { update_post_meta($id, $k, $v); }
                }
            }
        } catch (\Throwable $e) {
            $id = 0;
        }
        return $id;
    }
}
