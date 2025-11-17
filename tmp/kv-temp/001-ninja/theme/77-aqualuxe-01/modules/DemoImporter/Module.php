<?php
namespace AquaLuxe\Modules\DemoImporter;

if (!defined('ABSPATH')) exit;

class Module {
    public static function boot(): void {
        add_action('admin_menu', [__CLASS__, 'menu']);
        add_action('admin_post_aqlx_demo_import', [__CLASS__, 'handle_import']);
    }

    public static function menu(): void {
        add_theme_page(__('AquaLuxe Demo Import', 'aqualuxe'), __('Demo Import', 'aqualuxe'), 'manage_options', 'aqlx-demo-import', [__CLASS__, 'page']);
    }

    public static function page(): void {
        if (!current_user_can('manage_options')) return;
        echo '<div class="wrap"><h1>' . esc_html__('AquaLuxe Demo Import', 'aqualuxe') . '</h1>';
        echo '<p>' . esc_html__('Import demo content (pages, menus, widgets, WooCommerce demo).', 'aqualuxe') . '</p>';
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '">';
        wp_nonce_field('aqlx_demo_import');
        echo '<input type="hidden" name="action" value="aqlx_demo_import" />';
        echo '<p><button class="button button-primary">' . esc_html__('Run Import', 'aqualuxe') . '</button></p>';
        echo '</form></div>';
    }

    public static function handle_import(): void {
        if (!current_user_can('manage_options')) wp_die('Forbidden', 403);
        check_admin_referer('aqlx_demo_import');

        // Minimal: create core pages if missing.
        $pages = [
            'Home' => [ 'slug' => 'home', 'content' => "[aqlx_services]\n\n[aqlx_events]" ],
            'About' => [ 'slug' => 'about', 'content' => '<h2>Our Story</h2><p>From a local farm to an international brand.</p><h3>Sustainability</h3><p>Ethical sourcing and eco-initiatives.</p>' ],
            'Services' => [ 'slug' => 'services', 'content' => '[aqlx_services]' ],
            'Blog' => [ 'slug' => 'blog', 'content' => '' ],
            'Contact' => [ 'slug' => 'contact', 'content' => '<div class="grid md:grid-cols-2 gap-6"><form class="p-4 border rounded"><label class="block mb-2">Name<input class="mt-1 w-full border rounded px-3 py-2" required></label><label class="block mb-2">Email<input type="email" class="mt-1 w-full border rounded px-3 py-2" required></label><label class="block mb-2">Message<textarea class="mt-1 w-full border rounded px-3 py-2" rows="5"></textarea></label><button class="mt-2 px-4 py-2 bg-sky-600 text-white rounded">Send</button></form><div><iframe title="Map" src="https://www.google.com/maps?q=Colombo%20Sri%20Lanka&output=embed" width="100%" height="300" style="border:0" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe></div></div>' ],
            'FAQ' => [ 'slug' => 'faq', 'content' => '<h2>FAQ</h2><p>Shipping, care, purchasing, export/import processes.</p>' ],
            'Privacy Policy' => [ 'slug' => 'privacy-policy', 'content' => '<h2>Privacy Policy</h2><p>Placeholder content.</p>' ],
            'Terms & Conditions' => [ 'slug' => 'terms', 'content' => '<h2>Terms & Conditions</h2><p>Placeholder content.</p>' ],
            'Shipping & Returns' => [ 'slug' => 'shipping-returns', 'content' => '<h2>Shipping & Returns</h2><p>Placeholder content.</p>' ],
            'Cookie Policy' => [ 'slug' => 'cookies', 'content' => '<h2>Cookie Policy</h2><p>Placeholder content.</p>' ],
            'Wholesale' => [ 'slug' => 'wholesale', 'content' => '[aqlx_wholesale_app]' ],
            'Auctions' => [ 'slug' => 'auctions', 'content' => '[aqlx_auctions]' ],
            'Franchise' => [ 'slug' => 'franchise', 'content' => '[aqlx_franchise]' ],
            'R&D & Sustainability' => [ 'slug' => 'rnd', 'content' => '[aqlx_rnd]' ],
            'Affiliate' => [ 'slug' => 'affiliate', 'content' => '[aqlx_affiliate]' ],
        ];
        foreach ($pages as $title => $data) {
            $slug = $data['slug'];
            $page = get_page_by_path($slug);
            if (!$page) {
                $id = wp_insert_post([
                    'post_title' => $title,
                    'post_name' => $slug,
                    'post_status' => 'publish',
                    'post_type' => 'page',
                    'post_content' => $data['content'] ?? '',
                ]);
            } else {
                // Do not overwrite existing content; optional update title.
                $id = $page->ID;
            }
        }

        // Set homepage and posts page.
        $home = get_page_by_path('home');
        $blog = get_page_by_path('blog');
        if ($home && $blog) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $home->ID);
            update_option('page_for_posts', $blog->ID);
        }

        wp_safe_redirect(admin_url('themes.php?page=aqlx-demo-import&import=done'));
        exit;
    }
}
