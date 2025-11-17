<?php
/**
 * Module loader class
 *
 * @package AquaLuxe
 * @subpackage Core
 * @since 1.0.0
 */

namespace AquaLuxe\Core;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Module Loader class
 */
class Module_Loader {
    /**
     * Modules registry
     *
     * @var array
     */
    private $modules = [];

    /**
     * Constructor
     */
    public function __construct() {
        // Register hooks
        add_action( 'after_setup_theme', [ $this, 'load_modules' ], 20 );
        add_action( 'admin_menu', [ $this, 'register_modules_page' ] );
        add_action( 'admin_init', [ $this, 'handle_module_activation' ] );
    }

    /**
     * Load modules
     *
     * @return array The loaded modules.
     */
    public function load_modules() {
        // Get all modules
        $modules = $this->get_available_modules();

        // Load active modules
        foreach ( $modules as $module_id => $module ) {
            if ( $this->is_module_active( $module_id ) ) {
                $this->load_module( $module_id, $module );
            }
        }

        return $this->modules;
    }

    /**
     * Get available modules
     *
     * @return array The available modules.
     */
    public function get_available_modules() {
        $modules = [];
        $module_dirs = glob( AQUALUXE_MODULES_DIR . '*', GLOB_ONLYDIR );

        foreach ( $module_dirs as $module_dir ) {
            $module_id = basename( $module_dir );
            $module_file = $module_dir . '/module.php';

            if ( file_exists( $module_file ) ) {
                $module_data = $this->get_module_data( $module_file );
                if ( $module_data ) {
                    $modules[ $module_id ] = $module_data;
                }
            }
        }

        return $modules;
    }

    /**
     * Get module data
     *
     * @param string $module_file The module file.
     * @return array|false The module data or false if not found.
     */
    private function get_module_data( $module_file ) {
        $default_headers = [
            'name'        => 'Module Name',
            'description' => 'Description',
            'version'     => 'Version',
            'author'      => 'Author',
            'author_uri'  => 'Author URI',
            'module_uri'  => 'Module URI',
            'requires'    => 'Requires',
            'tags'        => 'Tags',
        ];

        $module_data = get_file_data( $module_file, $default_headers );

        if ( empty( $module_data['name'] ) ) {
            return false;
        }

        $module_data['file'] = $module_file;
        $module_data['path'] = dirname( $module_file );
        $module_data['url'] = AQUALUXE_URI . 'modules/' . basename( dirname( $module_file ) );

        return $module_data;
    }

    /**
     * Is module active
     *
     * @param string $module_id The module ID.
     * @return bool Whether the module is active.
     */
    public function is_module_active( $module_id ) {
        $active_modules = get_option( 'aqualuxe_active_modules', [] );
        return in_array( $module_id, $active_modules, true ) || $this->is_module_required( $module_id );
    }

    /**
     * Is module required
     *
     * @param string $module_id The module ID.
     * @return bool Whether the module is required.
     */
    public function is_module_required( $module_id ) {
        $required_modules = [
            'dark-mode',
            'multilingual',
        ];

        return in_array( $module_id, $required_modules, true );
    }

    /**
     * Load module
     *
     * @param string $module_id The module ID.
     * @param array  $module_data The module data.
     * @return bool Whether the module was loaded.
     */
    public function load_module( $module_id, $module_data ) {
        $module_file = $module_data['file'];

        if ( ! file_exists( $module_file ) ) {
            return false;
        }

        // Load module file
        require_once $module_file;

        // Get module class
        $module_class = $this->get_module_class( $module_id );
        if ( ! class_exists( $module_class ) ) {
            return false;
        }

        // Initialize module
        $module = new $module_class();
        $this->modules[ $module_id ] = $module;

        // Register module hooks
        $this->register_module_hooks( $module_id, $module );

        return true;
    }

    /**
     * Get module class
     *
     * @param string $module_id The module ID.
     * @return string The module class.
     */
    private function get_module_class( $module_id ) {
        $module_id = str_replace( '-', '_', $module_id );
        return 'AquaLuxe\\Modules\\' . ucfirst( $module_id ) . '\\Module';
    }

    /**
     * Register module hooks
     *
     * @param string $module_id The module ID.
     * @param object $module The module instance.
     */
    private function register_module_hooks( $module_id, $module ) {
        if ( method_exists( $module, 'register_hooks' ) ) {
            $module->register_hooks();
        }
    }

    /**
     * Register modules page
     */
    public function register_modules_page() {
        add_submenu_page(
            'themes.php',
            esc_html__( 'AquaLuxe Modules', 'aqualuxe' ),
            esc_html__( 'Modules', 'aqualuxe' ),
            'manage_options',
            'aqualuxe-modules',
            [ $this, 'render_modules_page' ]
        );
    }

    /**
     * Render modules page
     */
    public function render_modules_page() {
        $modules = $this->get_available_modules();
        ?>
        <div class="wrap">
            <h1><?php esc_html_e( 'AquaLuxe Modules', 'aqualuxe' ); ?></h1>
            <p><?php esc_html_e( 'Enable or disable modules to customize your theme functionality.', 'aqualuxe' ); ?></p>

            <?php if ( isset( $_GET['activated'] ) ) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php esc_html_e( 'Module activated successfully.', 'aqualuxe' ); ?></p>
                </div>
            <?php endif; ?>

            <?php if ( isset( $_GET['deactivated'] ) ) : ?>
                <div class="notice notice-success is-dismissible">
                    <p><?php esc_html_e( 'Module deactivated successfully.', 'aqualuxe' ); ?></p>
                </div>
            <?php endif; ?>

            <table class="wp-list-table widefat plugins">
                <thead>
                    <tr>
                        <th scope="col" class="manage-column column-name column-primary"><?php esc_html_e( 'Module', 'aqualuxe' ); ?></th>
                        <th scope="col" class="manage-column column-description"><?php esc_html_e( 'Description', 'aqualuxe' ); ?></th>
                    </tr>
                </thead>
                <tbody id="the-list">
                    <?php foreach ( $modules as $module_id => $module ) : ?>
                        <tr class="<?php echo $this->is_module_active( $module_id ) ? 'active' : 'inactive'; ?>">
                            <td class="plugin-title column-primary">
                                <strong><?php echo esc_html( $module['name'] ); ?></strong>
                                <div class="row-actions visible">
                                    <?php if ( $this->is_module_active( $module_id ) && ! $this->is_module_required( $module_id ) ) : ?>
                                        <span class="deactivate">
                                            <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'themes.php?page=aqualuxe-modules&action=deactivate&module=' . $module_id ), 'deactivate-module_' . $module_id ) ); ?>"><?php esc_html_e( 'Deactivate', 'aqualuxe' ); ?></a>
                                        </span>
                                    <?php elseif ( ! $this->is_module_required( $module_id ) ) : ?>
                                        <span class="activate">
                                            <a href="<?php echo esc_url( wp_nonce_url( admin_url( 'themes.php?page=aqualuxe-modules&action=activate&module=' . $module_id ), 'activate-module_' . $module_id ) ); ?>"><?php esc_html_e( 'Activate', 'aqualuxe' ); ?></a>
                                        </span>
                                    <?php else : ?>
                                        <span class="required"><?php esc_html_e( 'Required', 'aqualuxe' ); ?></span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="column-description desc">
                                <div class="plugin-description">
                                    <p><?php echo esc_html( $module['description'] ); ?></p>
                                </div>
                                <div class="active second plugin-version-author-uri">
                                    <?php
                                    printf(
                                        /* translators: 1: Module version, 2: Module author */
                                        esc_html__( 'Version %1$s | By %2$s', 'aqualuxe' ),
                                        esc_html( $module['version'] ),
                                        esc_html( $module['author'] )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th scope="col" class="manage-column column-name column-primary"><?php esc_html_e( 'Module', 'aqualuxe' ); ?></th>
                        <th scope="col" class="manage-column column-description"><?php esc_html_e( 'Description', 'aqualuxe' ); ?></th>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php
    }

    /**
     * Handle module activation
     */
    public function handle_module_activation() {
        if ( ! isset( $_GET['page'] ) || 'aqualuxe-modules' !== $_GET['page'] ) {
            return;
        }

        if ( isset( $_GET['action'], $_GET['module'] ) ) {
            $action = sanitize_text_field( wp_unslash( $_GET['action'] ) );
            $module = sanitize_text_field( wp_unslash( $_GET['module'] ) );

            if ( 'activate' === $action ) {
                $this->activate_module( $module );
            } elseif ( 'deactivate' === $action ) {
                $this->deactivate_module( $module );
            }
        }
    }

    /**
     * Activate module
     *
     * @param string $module_id The module ID.
     */
    private function activate_module( $module_id ) {
        check_admin_referer( 'activate-module_' . $module_id );

        if ( $this->is_module_active( $module_id ) ) {
            wp_safe_redirect( admin_url( 'themes.php?page=aqualuxe-modules' ) );
            exit;
        }

        $active_modules = get_option( 'aqualuxe_active_modules', [] );
        $active_modules[] = $module_id;
        update_option( 'aqualuxe_active_modules', array_unique( $active_modules ) );

        wp_safe_redirect( admin_url( 'themes.php?page=aqualuxe-modules&activated=true' ) );
        exit;
    }

    /**
     * Deactivate module
     *
     * @param string $module_id The module ID.
     */
    private function deactivate_module( $module_id ) {
        check_admin_referer( 'deactivate-module_' . $module_id );

        if ( ! $this->is_module_active( $module_id ) || $this->is_module_required( $module_id ) ) {
            wp_safe_redirect( admin_url( 'themes.php?page=aqualuxe-modules' ) );
            exit;
        }

        $active_modules = get_option( 'aqualuxe_active_modules', [] );
        $active_modules = array_diff( $active_modules, [ $module_id ] );
        update_option( 'aqualuxe_active_modules', $active_modules );

        wp_safe_redirect( admin_url( 'themes.php?page=aqualuxe-modules&deactivated=true' ) );
        exit;
    }

    /**
     * Get active modules
     *
     * @return array The active modules.
     */
    public function get_active_modules() {
        return $this->modules;
    }

    /**
     * Get module
     *
     * @param string $module_id The module ID.
     * @return object|false The module instance or false if not found.
     */
    public function get_module( $module_id ) {
        return isset( $this->modules[ $module_id ] ) ? $this->modules[ $module_id ] : false;
    }
}

// Initialize the class
new Module_Loader();