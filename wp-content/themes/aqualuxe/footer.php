<?php
/** Footer template */
?>
</main>
<footer class="aqlx-footer border-t border-slate-200 dark:border-slate-800 py-10 text-sm">
  <div class="container mx-auto px-4 grid md:grid-cols-4 gap-6">
    <div>
      <?php aqualuxe_site_branding(); ?>
      <p class="mt-3 opacity-80"><?php echo esc_html__('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
    </div>
    <div>
      <h4 class="font-semibold mb-2"><?php echo esc_html__('Explore', 'aqualuxe'); ?></h4>
      <?php aqualuxe_footer_nav(); ?>
    </div>
    <div>
      <h4 class="font-semibold mb-2"><?php echo esc_html__('Newsletter', 'aqualuxe'); ?></h4>
      <form method="post" action="#" onsubmit="event.preventDefault(); alert('Subscribed');">
        <label class="sr-only" for="nl">Email</label>
        <input id="nl" type="email" class="w-full px-3 py-2 rounded border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900" placeholder="you@example.com" required />
        <button class="mt-2 px-4 py-2 bg-sky-600 text-white rounded"><?php echo esc_html__('Subscribe','aqualuxe'); ?></button>
      </form>
    </div>
    <div>
      <h4 class="font-semibold mb-2"><?php echo esc_html__('Contact', 'aqualuxe'); ?></h4>
      <ul>
        <li>Email: info@aqualuxe.tld</li>
        <li>Phone: +1 (555) 123-4567</li>
      </ul>
    </div>
  </div>
  <div class="container mx-auto px-4 mt-8 opacity-70">&copy; <?php echo esc_html(date('Y')); ?> AquaLuxe</div>
</footer>
<?php wp_footer(); ?>
</body>
</html>
