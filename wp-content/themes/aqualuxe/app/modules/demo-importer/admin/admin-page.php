<div class="wrap aqualuxe-importer-wrap">
    <h1><?php esc_html_e('Aqualuxe Demo Importer', 'aqualuxe'); ?></h1>

    <div id="aqualuxe-importer-messages"></div>

    <div class="aqualuxe-importer-main">
        <div class="aqualuxe-importer-panel">
            <h2><span class="dashicons dashicons-download"></span> <?php esc_html_e('Import Demo Content', 'aqualuxe'); ?></h2>
            <p><?php esc_html_e('Click the button below to import the demo content. This will add pages, posts, products, menus, widgets, and more to your site. It is recommended to do this on a fresh WordPress installation.', 'aqualuxe'); ?></p>
            <button id="aqualuxe-import-btn" class="button button-primary button-hero">
                <?php esc_html_e('Import Demo Content', 'aqualuxe'); ?>
            </button>
        </div>

        <div class="aqualuxe-importer-panel aqualuxe-importer-flush-panel">
            <h2><span class="dashicons dashicons-trash"></span> <?php esc_html_e('Remove Demo Content', 'aqualuxe'); ?></h2>
            <p><?php esc_html_e('This will remove all content that was imported by the demo importer. Please be careful, this action is not reversible.', 'aqualuxe'); ?></p>
            <button id="aqualuxe-flush-btn" class="button button-secondary">
                <?php esc_html_e('Remove Demo Content', 'aqualuxe'); ?>
            </button>
        </div>
    </div>

    <div id="aqualuxe-importer-progress" class="hidden">
        <h3><?php esc_html_e('Import Progress', 'aqualuxe'); ?></h3>
        <div class="aqualuxe-importer-log">
            <pre id="aqualuxe-importer-log-content"></pre>
        </div>
    </div>

</div>
