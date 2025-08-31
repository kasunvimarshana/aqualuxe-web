<section class="container max-w-7xl mx-auto px-4 py-12">
  <div class="rounded border p-6 flex flex-col md:flex-row items-center gap-4">
    <div class="flex-1">
      <h3 class="text-xl font-semibold mb-2"><?php esc_html_e('Join our newsletter', 'aqualuxe'); ?></h3>
      <p class="opacity-80"><?php esc_html_e('Get updates on new arrivals, events, and exclusive offers.', 'aqualuxe'); ?></p>
    </div>
    <form class="flex gap-2 w-full md:w-auto">
      <label for="home_email" class="sr-only"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
      <input id="home_email" type="email" required class="border rounded px-3 py-2 flex-1" placeholder="you@example.com" />
      <button class="bg-slate-900 text-white dark:bg-white dark:text-slate-900 px-4 rounded"><?php esc_html_e('Subscribe','aqualuxe'); ?></button>
    </form>
  </div>
</section>
