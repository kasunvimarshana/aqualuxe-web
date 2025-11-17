<?php
/**
 * Template part for displaying the standard header layout
 *
 * @package AquaLuxe
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<div class="header-main bg-white dark:bg-gray-900 shadow-sm">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-4">
            <div class="flex items-center">
                <div class="site-branding mr-8">
                    <?php if ( has_custom_logo() ) : ?>
                        <div class="site-logo"><?php the_custom_logo(); ?></div>
                    <?php else : ?>
                        <h1 class="site-title text-xl font-bold">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home" class="text-gray-900 dark:text-white hover:text-primary-600 dark:hover:text-primary-400">
                                <?php bloginfo( 'name' ); ?>
                            </a>
                        </h1>
                        <?php
                        $aqualuxe_description = get_bloginfo( 'description', 'display' );
                        if ( $aqualuxe_description || is_customize_preview() ) :
                            ?>
                            <p class="site-description text-sm text-gray-600 dark:text-gray-400"><?php echo $aqualuxe_description; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div><!-- .site-branding -->

                <div class="hidden lg:block">
                    <?php get_template_part( 'template-parts/navigation/primary-menu' ); ?>
                </div>
            </div>

            <div class="flex items-center">
                <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                    <div class="header-actions flex items-center space-x-4">
                        <?php if ( get_theme_mod( 'aqualuxe_header_search', true ) ) : ?>
                            <div class="header-search">
                                <button id="header-search-toggle" class="header-icon-btn" aria-expanded="false" aria-label="<?php esc_attr_e( 'Search', 'aqualuxe' ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </button>
                            </div>
                        <?php endif; ?>

                        <?php if ( get_theme_mod( 'aqualuxe_header_account', true ) ) : ?>
                            <div class="header-account">
                                <a href="<?php echo esc_url( wc_get_account_endpoint_url( 'dashboard' ) ); ?>" class="header-icon-btn" aria-label="<?php esc_attr_e( 'My Account', 'aqualuxe' ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ( get_theme_mod( 'aqualuxe_header_wishlist', true ) && function_exists( 'aqualuxe_get_wishlist_url' ) ) : ?>
                            <div class="header-wishlist">
                                <a href="<?php echo esc_url( aqualuxe_get_wishlist_url() ); ?>" class="header-icon-btn" aria-label="<?php esc_attr_e( 'Wishlist', 'aqualuxe' ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <?php if ( function_exists( 'aqualuxe_get_wishlist_count' ) ) : ?>
                                        <span class="wishlist-count"><?php echo esc_html( aqualuxe_get_wishlist_count() ); ?></span>
                                    <?php endif; ?>
                                </a>
                            </div>
                        <?php endif; ?>

                        <?php if ( get_theme_mod( 'aqualuxe_header_cart', true ) ) : ?>
                            <div class="header-cart">
                                <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="header-icon-btn cart-contents" aria-label="<?php esc_attr_e( 'View your shopping cart', 'aqualuxe' ); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                    </svg>
                                    <span class="cart-count"><?php echo esc_html( WC()->cart->get_cart_contents_count() ); ?></span>
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ( get_theme_mod( 'aqualuxe_dark_mode_toggle', true ) ) : ?>
                    <button id="dark-mode-toggle" class="dark-mode-toggle ml-4 p-2" aria-label="<?php esc_attr_e( 'Toggle Dark Mode', 'aqualuxe' ); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 dark:hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden dark:block" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </button>
                <?php endif; ?>

                <?php aqualuxe_mobile_menu_toggle(); ?>
            </div>
        </div>
    </div>
</div>

<?php if ( get_theme_mod( 'aqualuxe_header_search', true ) ) : ?>
    <div id="header-search-dropdown" class="header-search-dropdown hidden bg-white dark:bg-gray-900 shadow-md py-4 absolute left-0 right-0 z-20">
        <div class="container mx-auto px-4">
            <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <div class="flex items-center">
                    <input type="search" class="search-field flex-grow p-3 border border-gray-300 dark:border-gray-700 rounded-l-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500" placeholder="<?php echo esc_attr_x( 'Search &hellip;', 'placeholder', 'aqualuxe' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />
                    <?php if ( class_exists( 'WooCommerce' ) ) : ?>
                        <input type="hidden" name="post_type" value="product" />
                    <?php endif; ?>
                    <button type="submit" class="search-submit p-3 bg-primary-600 hover:bg-primary-700 text-white rounded-r-md focus:outline-none focus:ring-2 focus:ring-primary-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                        <span class="sr-only"><?php echo esc_html_x( 'Search', 'submit button', 'aqualuxe' ); ?></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
<?php endif; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Header search toggle
    const searchToggle = document.getElementById('header-search-toggle');
    const searchDropdown = document.getElementById('header-search-dropdown');
    
    if (searchToggle && searchDropdown) {
        searchToggle.addEventListener('click', function() {
            const expanded = searchToggle.getAttribute('aria-expanded') === 'true' || false;
            searchToggle.setAttribute('aria-expanded', !expanded);
            searchDropdown.classList.toggle('hidden');
            
            if (!expanded) {
                // Focus the search field when opened
                setTimeout(function() {
                    searchDropdown.querySelector('.search-field').focus();
                }, 100);
            }
        });
        
        // Close search dropdown when clicking outside
        document.addEventListener('click', function(event) {
            if (!searchToggle.contains(event.target) && !searchDropdown.contains(event.target)) {
                searchToggle.setAttribute('aria-expanded', 'false');
                searchDropdown.classList.add('hidden');
            }
        });
    }
    
    // Dark mode toggle
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    
    if (darkModeToggle) {
        darkModeToggle.addEventListener('click', function() {
            document.documentElement.classList.toggle('dark');
            
            // Save preference to localStorage
            if (document.documentElement.classList.contains('dark')) {
                localStorage.setItem('aqualuxe-color-scheme', 'dark');
            } else {
                localStorage.setItem('aqualuxe-color-scheme', 'light');
            }
        });
    }
});
</script>