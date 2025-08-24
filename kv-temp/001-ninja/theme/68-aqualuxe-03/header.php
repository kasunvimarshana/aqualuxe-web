<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package AquaLuxe
 * @since 1.0.0
 */

?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<div id="page" class="site">
    <a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'aqualuxe' ); ?></a>

    <?php do_action( 'aqualuxe_before_header' ); ?>


    <header id="masthead" class="site-header">
        <?php do_action( 'aqualuxe_header' ); ?>

        <div class="aqualuxe-header-actions" style="position:absolute;top:1.5rem;right:2rem;z-index:100;display:flex;align-items:center;gap:2em;">
            <form class="aqualuxe-currency-switcher" method="get" style="margin:0;display:flex;gap:0.5em;align-items:center;">
                <?php
                // Inline multicurrency switcher (uses same logic as module)
                if ( class_exists('AquaLuxe\\Modules\\Multicurrency\\Loader') ) {
                    $currencies = \AquaLuxe\Modules\Multicurrency\Loader::$currencies;
                    $current = isset($_COOKIE['aqualuxe_currency']) && isset($currencies[$_COOKIE['aqualuxe_currency']]) ? $_COOKIE['aqualuxe_currency'] : 'USD';
                    foreach ( $currencies as $code => $data ) {
                        echo '<button type="submit" name="aqualuxe_currency" value="' . esc_attr( $code ) . '"' . ( $current === $code ? ' style="font-weight:bold;background:#1e40af;color:#fff;"' : '' ) . '>';
                        echo esc_html( $data['symbol'] . ' ' . $code );
                        echo '</button>';
                    }
                }
                ?>
            </form>
            <div class="aqualuxe-header-wishlist">
                <a href="<?php echo esc_url( site_url( '/wishlist/' ) ); ?>" class="wishlist-link" title="<?php esc_attr_e('View Wishlist','aqualuxe'); ?>">
                    <span class="wishlist-icon" style="font-size:1.5em;color:#e11d48;vertical-align:middle;">&#9825;</span>
                    <span class="wishlist-count" id="aqualuxe-wishlist-count">0</span>
                </a>
            </div>
        </div>
    </header><!-- #masthead -->
<style>
.aqualuxe-header-actions .aqualuxe-currency-switcher button {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 4px;
    padding: 0.3em 1em;
    color: #1e40af;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
.aqualuxe-header-actions .aqualuxe-currency-switcher button[style*="font-weight:bold"] {
    background: #1e40af;
    color: #fff;
    border-color: #1e40af;
}
.aqualuxe-header-actions .aqualuxe-currency-switcher button:hover {
    background: #2563eb;
    color: #fff;
}
.aqualuxe-header-wishlist .wishlist-link {
    display: inline-flex;
    align-items: center;
    gap: 0.3em;
    font-weight: 600;
    text-decoration: none;
}
.aqualuxe-header-wishlist .wishlist-count {
    background: #e11d48;
    color: #fff;
    border-radius: 50%;
    padding: 0.1em 0.6em;
    font-size: 0.95em;
    margin-left: 0.2em;
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateWishlistCount() {
        if (!window.aqualuxe_wishlist) return;
        fetch(aqualuxe_wishlist.ajax_url + '?action=aqualuxe_wishlist_count', {
            credentials: 'same-origin'
        })
            .then(r => r.json())
            .then(data => {
                if (data.success && data.data && typeof data.data.count !== 'undefined') {
                    document.getElementById('aqualuxe-wishlist-count').textContent = data.data.count;
                }
            });
    }
    updateWishlistCount();
    document.body.addEventListener('wishlist:updated', updateWishlistCount);
});
</script>

    <?php do_action( 'aqualuxe_after_header' ); ?>

    <div id="content" class="site-content">