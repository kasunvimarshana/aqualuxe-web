<?php
/** Footer template */
?>
</main>
<footer class="border-t">
    <div class="container mx-auto max-w-screen-xl px-4 py-10 grid gap-6 md:grid-cols-3">
        <div>
            <h2 class="text-lg font-semibold mb-2"><?php esc_html_e( 'About AquaLuxe', 'aqualuxe' ); ?></h2>
            <p class="text-sm opacity-80"><?php esc_html_e( 'Bringing elegance to aquatic life – globally.', 'aqualuxe' ); ?></p>
        </div>
        <div>
            <h2 class="text-lg font-semibold mb-2"><?php esc_html_e( 'Quick Links', 'aqualuxe' ); ?></h2>
            <?php wp_nav_menu( [ 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'space-y-2' ] ); ?>
        </div>
        <div>
            <h2 class="text-lg font-semibold mb-2"><?php esc_html_e( 'Newsletter', 'aqualuxe' ); ?></h2>
            <form method="post" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <label class="sr-only" for="newsletter-email"><?php esc_html_e( 'Email address', 'aqualuxe' ); ?></label>
                <input id="newsletter-email" name="newsletter_email" type="email" class="border rounded px-3 py-2 w-full" placeholder="you@example.com" required />
                <button class="mt-2 inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded" type="submit"><?php esc_html_e( 'Subscribe', 'aqualuxe' ); ?></button>
                <?php wp_nonce_field( 'aqlx_newsletter', 'aqlx_newsletter_nonce' ); ?>
            </form>
        </div>
    </div>
    <div class="text-center text-xs py-4 opacity-70">&copy; <?php echo esc_html( date('Y') ); ?> AquaLuxe</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
