<?php
namespace Aqualuxe\Modules\Importer;

defined('ABSPATH') || exit;

final class ImporterModule {
    public static function register(): void {
        \add_action( 'admin_menu', [ __CLASS__, 'menu' ] );
        \add_action( 'admin_enqueue_scripts', [ __CLASS__, 'assets' ] );
        \add_action( 'wp_ajax_aqlx_import', [ __CLASS__, 'handle_import' ] );
    }

    public static function menu(): void {
        \add_menu_page( __( 'Aqualuxe Importer', 'aqualuxe' ), __( 'Aqualuxe Importer', 'aqualuxe' ), 'manage_options', 'aqlx-importer', [ __CLASS__, 'render' ], 'dashicons-database-import', 59 );
    }

    public static function assets( $hook ): void {
        if ( $hook !== 'toplevel_page_aqlx-importer' ) return;
    // Use theme asset resolver for hashed path
    $adminJs = function_exists( 'aqlx_asset' ) ? aqlx_asset( 'js/app.js' ) : ( get_template_directory_uri() . '/assets/dist/js/app.js' );
    \wp_enqueue_script( 'aqlx-admin', $adminJs, [ 'jquery' ], AQUALUXE_VERSION, true );
        \wp_localize_script( 'aqlx-admin', 'AQLX_IMPORT', [ 'nonce' => \wp_create_nonce( 'aqlx_import' ), 'ajaxUrl' => \admin_url( 'admin-ajax.php' ) ] );
    }

    public static function render(): void {
        echo '<div class="wrap"><h1>' . esc_html__( 'Aqualuxe Demo Importer', 'aqualuxe' ) . '</h1>';
        echo '<p>' . esc_html__( 'Import demo content, taxonomies, menus, and optional WooCommerce products. Safe to re-run (idempotent).', 'aqualuxe' ) . '</p>';
        echo '<button class="button button-primary" id="aqlx-import-start">' . esc_html__( 'Start Import', 'aqualuxe' ) . '</button>';
        echo '<div id="aqlx-import-log" style="margin-top:1rem; max-height:320px; overflow:auto;"></div></div>';
        echo '<script>(function(){const steps=["prepare","pages","taxonomies","listings","stores","menu","woocommerce","finish"];const btn=document.getElementById("aqlx-import-start");const log=document.getElementById("aqlx-import-log");function add(msg){const p=document.createElement("div");p.textContent=msg;log.appendChild(p);log.scrollTop=log.scrollHeight;}async function run(stepIndex){if(stepIndex>=steps.length){add("✔ Done");btn.disabled=false;return;}const step=steps[stepIndex];const fd=new FormData();fd.append("action","aqlx_import");fd.append("nonce",AQLX_IMPORT.nonce);fd.append("step",step);try{const res=await fetch(AQLX_IMPORT.ajaxUrl,{method:"POST",body:fd,credentials:"same-origin"});const data=await res.json();if(!data.success){add("✖ "+(data.data&&data.data.message?data.data.message:"Error"));btn.disabled=false;return;}add("→ "+data.data.label+": "+data.data.message);if(data.data.skip){return run(stepIndex+1);}return run(stepIndex+1);}catch(e){add("✖ "+e);btn.disabled=false;}}btn.addEventListener("click",()=>{btn.disabled=true;log.innerHTML="";run(0)});})();</script>';
    }

    public static function handle_import(): void {
        \check_ajax_referer( 'aqlx_import', 'nonce' );
        if ( ! current_user_can( 'manage_options' ) ) {
            \wp_send_json_error( [ 'message' => __( 'Permission denied.', 'aqualuxe' ) ], 403 );
        }

        $step = isset( $_POST['step'] ) ? sanitize_key( (string) $_POST['step'] ) : 'prepare';
        $result = [ 'label' => ucfirst( $step ), 'message' => '', 'skip' => false ];
        try {
            switch ( $step ) {
                case 'prepare':
                    self::step_prepare();
                    $result['message'] = __( 'Environment ready.', 'aqualuxe' );
                    break;
                case 'pages':
                    $created = self::step_pages();
                    $result['message'] = sprintf( __( '%d pages ensured.', 'aqualuxe' ), $created );
                    break;
                case 'taxonomies':
                    $terms = self::step_taxonomies();
                    $result['message'] = sprintf( __( '%d terms ensured.', 'aqualuxe' ), $terms );
                    break;
                case 'listings':
                    $count = self::step_listings();
                    $result['message'] = sprintf( __( '%d listings ensured.', 'aqualuxe' ), $count );
                    break;
                case 'stores':
                    $count = self::step_stores();
                    $result['message'] = sprintf( __( '%d stores ensured.', 'aqualuxe' ), $count );
                    break;
                case 'menu':
                    self::step_menu();
                    $result['message'] = __( 'Primary menu ensured and assigned.', 'aqualuxe' );
                    break;
                case 'woocommerce':
                    if ( class_exists( 'WooCommerce' ) ) {
                        $count = self::step_products();
                        $result['message'] = sprintf( __( '%d products ensured.', 'aqualuxe' ), $count );
                    } else {
                        $result['message'] = __( 'WooCommerce not active. Skipped.', 'aqualuxe' );
                        $result['skip'] = true;
                    }
                    break;
                case 'finish':
                    $result['message'] = __( 'All steps completed.', 'aqualuxe' );
                    break;
                default:
                    throw new \RuntimeException( 'Unknown step' );
            }
        } catch ( \Throwable $e ) {
            \wp_send_json_error( [ 'message' => $e->getMessage() ], 500 );
        }
        \wp_send_json_success( $result );
    }

    private static function step_prepare(): void {
        if ( function_exists( 'wp_raise_memory_limit' ) ) { \wp_raise_memory_limit( 'admin' ); }
        @set_time_limit( 60 ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
    }

    private static function ensure_page( string $title, string $slug, string $content = '' ): int {
        $existing = get_page_by_path( $slug );
        if ( $existing ) {
            return (int) $existing->ID;
        }
        $id = \wp_insert_post([
            'post_title'   => $title,
            'post_name'    => $slug,
            'post_status'  => 'publish',
            'post_type'    => 'page',
            'post_content' => $content,
        ]);
        return (int) $id;
    }

    private static function step_pages(): int {
        $count = 0;
        $home = self::ensure_page( __( 'Home', 'aqualuxe' ), 'home', __( 'Welcome to Aqualuxe.', 'aqualuxe' ) );
        $count++;
        $blog = self::ensure_page( __( 'Blog', 'aqualuxe' ), 'blog', '' );
        $count++;
        self::ensure_page( __( 'About', 'aqualuxe' ), 'about', __( 'About our aquatic vision.', 'aqualuxe' ) );
        $count++;
        self::ensure_page( __( 'Contact', 'aqualuxe' ), 'contact', __( 'Get in touch.', 'aqualuxe' ) );
        $count++;
        if ( class_exists( 'WooCommerce' ) ) { self::ensure_page( __( 'Shop', 'aqualuxe' ), 'shop', '' ); $count++; }

        // Set front and posts pages
        if ( get_option( 'show_on_front' ) !== 'page' ) { update_option( 'show_on_front', 'page' ); }
        if ( get_option( 'page_on_front' ) != $home ) { update_option( 'page_on_front', $home ); }
        if ( get_option( 'page_for_posts' ) != $blog ) { update_option( 'page_for_posts', $blog ); }
        return $count;
    }

    private static function ensure_term( string $taxonomy, string $name, string $slug ): int {
        $existing = get_term_by( 'slug', $slug, $taxonomy );
        if ( $existing && ! is_wp_error( $existing ) ) {
            return (int) $existing->term_id;
        }
        $t = \wp_insert_term( $name, $taxonomy, [ 'slug' => $slug ] );
        if ( is_wp_error( $t ) ) { return 0; }
        return (int) $t['term_id'];
    }

    private static function step_taxonomies(): int {
        $n = 0;
        $n += self::ensure_term( 'listing_category', __( 'Fish', 'aqualuxe' ), 'fish' ) ? 1 : 0;
        $n += self::ensure_term( 'listing_category', __( 'Equipment', 'aqualuxe' ), 'equipment' ) ? 1 : 0;
        $n += self::ensure_term( 'listing_category', __( 'Services', 'aqualuxe' ), 'services' ) ? 1 : 0;
        return $n;
    }

    private static function step_listings(): int {
        $titles = [ 'Blue Tang', 'Clownfish Pair', 'Live Coral Frag', 'Premium Filter', 'Aquascape Kit', 'CO2 System', 'LED Lighting', 'Heater 300W' ];
        $count = 0;
        foreach ( $titles as $t ) {
            $slug = sanitize_title( $t );
            $existing = get_page_by_path( $slug, OBJECT, 'listing' );
            if ( $existing ) { $count++; continue; }
            $id = \wp_insert_post([
                'post_type' => 'listing',
                'post_title' => $t,
                'post_name' => $slug,
                'post_status' => 'publish',
                'post_content' => __( 'Demo listing content for Aqualuxe.', 'aqualuxe' ),
            ]);
            if ( $id && ! is_wp_error( $id ) ) {
                // Assign a category if available
                $terms = [ 'fish', 'equipment', 'services' ];
                $term = $terms[ array_rand( $terms ) ];
                \wp_set_object_terms( (int) $id, $term, 'listing_category', false );
                $count++;
            }
        }
        return $count;
    }

    private static function step_stores(): int {
        $stores = [ 'Oceanic Vendor', 'Reef Central', 'Aqua Pro Shop' ];
        $count = 0;
        foreach ( $stores as $t ) {
            $slug = sanitize_title( $t );
            $existing = get_page_by_path( $slug, OBJECT, 'store' );
            if ( $existing ) { $count++; continue; }
            $id = \wp_insert_post([
                'post_type' => 'store',
                'post_title' => $t,
                'post_name' => $slug,
                'post_status' => 'publish',
                'post_content' => __( 'Demo store bio.', 'aqualuxe' ),
            ]);
            if ( $id && ! is_wp_error( $id ) ) { $count++; }
        }
        return $count;
    }

    private static function step_menu(): void {
        $menu_name = 'Primary';
        $loc = 'primary';
        $menu_id = \wp_get_nav_menu_object( $menu_name ) ? \wp_get_nav_menu_object( $menu_name )->term_id : 0;
        if ( ! $menu_id ) {
            $menu_id = (int) \wp_create_nav_menu( $menu_name );
        }

        // Ensure items
        $home = get_page_by_path( 'home' );
        $blog = get_page_by_path( 'blog' );
        $items = [
            [ 'title' => __( 'Home', 'aqualuxe' ), 'url' => home_url( '/' ), 'object_id' => $home ? $home->ID : 0, 'type' => $home ? 'post_type' : 'custom', 'object' => $home ? 'page' : '' ],
            [ 'title' => __( 'Listings', 'aqualuxe' ), 'url' => get_post_type_archive_link( 'listing' ), 'type' => 'custom' ],
            [ 'title' => __( 'Blog', 'aqualuxe' ), 'url' => $blog ? get_permalink( $blog ) : home_url( '/blog/' ), 'object_id' => $blog ? $blog->ID : 0, 'type' => $blog ? 'post_type' : 'custom', 'object' => $blog ? 'page' : '' ],
            [ 'title' => __( 'Contact', 'aqualuxe' ), 'url' => home_url( '/contact/' ), 'type' => 'custom' ],
        ];
        if ( class_exists( 'WooCommerce' ) ) {
            $items[] = [ 'title' => __( 'Shop', 'aqualuxe' ), 'url' => home_url( '/shop/' ), 'type' => 'custom' ];
        }
        foreach ( $items as $it ) {
            $exists = false;
            $menu_items = wp_get_nav_menu_items( $menu_id ) ?: [];
            foreach ( $menu_items as $mi ) {
                if ( trim( $mi->title ) === trim( $it['title'] ) ) { $exists = true; break; }
            }
            if ( ! $exists ) {
                \wp_update_nav_menu_item( $menu_id, 0, [
                    'menu-item-title' => $it['title'],
                    'menu-item-url'   => $it['url'],
                    'menu-item-status'=> 'publish',
                    'menu-item-type'  => $it['type'],
                    'menu-item-object'=> $it['object'] ?? '',
                    'menu-item-object-id' => $it['object_id'] ?? 0,
                ] );
            }
        }
        // Assign to location
        $locations = get_theme_mod( 'nav_menu_locations' );
        if ( ! is_array( $locations ) ) { $locations = []; }
        $locations[ $loc ] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }

    private static function step_products(): int {
        if ( ! class_exists( 'WC_Product_Simple' ) ) { return 0; }
        $titles = [ 'Aquarium Starter', 'Reef Salt 10kg', 'Pro Skimmer' ];
        $count = 0;
        foreach ( $titles as $t ) {
            $slug = sanitize_title( $t );
            $existing = get_page_by_path( $slug, OBJECT, 'product' );
            if ( $existing ) { $count++; continue; }
            $p = new \WC_Product_Simple();
            $p->set_name( $t );
            $p->set_status( 'publish' );
            $p->set_catalog_visibility( 'visible' );
            $p->set_regular_price( (string) rand( 20, 200 ) );
            $p->save();
            $count++;
        }
        return $count;
    }
}
