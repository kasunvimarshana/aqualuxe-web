<?php if (! defined('ABSPATH')) { exit; } ?>
    </main>
    <footer class="mt-12 border-t border-slate-200 dark:border-slate-800">
        <div class="container mx-auto px-4 py-8 grid gap-6 md:grid-cols-3">
            <div>
                <h2 class="font-semibold"><?php bloginfo('name'); ?></h2>
                <p class="text-sm opacity-80"><?php bloginfo('description'); ?></p>
            </div>
            <nav aria-label="Footer" class="md:col-span-2">
                <?php wp_nav_menu(['theme_location'=>'footer','container'=>false,'menu_class'=>'grid md:grid-cols-3 gap-2']); ?>
            </nav>
        </div>
        <div class="text-center text-xs opacity-70 py-4">© <span><?php echo esc_html(gmdate('Y')); ?></span> <?php bloginfo('name'); ?> – <?php esc_html_e('Bringing elegance to aquatic life – globally', 'aqualuxe'); ?></div>
    </footer>
    <?php wp_footer(); ?>
    </body>
    </html>
