<?php
defined('ABSPATH') || exit;
?>
<div id="aqlx-quote" class="fixed inset-0 hidden items-center justify-center z-50">
  <div id="aqlx-quote-overlay" class="absolute inset-0 bg-black/50" aria-hidden="true"></div>
  <div class="relative bg-white dark:bg-slate-900 rounded-lg shadow-xl w-full max-w-xl mx-4">
    <button id="aqlx-quote-close" class="absolute top-3 right-3 p-2 rounded hover:bg-slate-100 dark:hover:bg-slate-800" aria-label="Close">
      <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.3 5.71a1 1 0 00-1.41 0L12 10.59 7.11 5.7a1 1 0 10-1.41 1.41L10.59 12l-4.9 4.89a1 1 0 101.41 1.41L12 13.41l4.89 4.9a1 1 0 001.41-1.41L13.41 12l4.9-4.89a1 1 0 000-1.4z"/></svg>
    </button>
    <div class="p-6">
      <h3 class="text-lg font-semibold mb-4"><?php esc_html_e('Request a Quote', 'aqualuxe'); ?></h3>
      <form id="aqlx-quote-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="space-y-3">
        <?php wp_nonce_field('aqlx_quote'); ?>
        <input type="hidden" name="action" value="aqlx_quote_submit" />
        <input type="hidden" id="aqlx-quote-product-id" name="product_id" value="" />
        <input type="text" id="aqlx-quote-company-site" name="company_site" class="hidden" tabindex="-1" autocomplete="off" />

        <div>
          <label class="block text-sm mb-1"><?php esc_html_e('Product', 'aqualuxe'); ?></label>
          <input id="aqlx-quote-product-name" type="text" class="w-full border rounded px-3 py-2 bg-slate-50 dark:bg-slate-800" disabled />
        </div>
        <div class="grid md:grid-cols-2 gap-3">
          <div>
            <label for="aqlx-quote-name" class="block text-sm mb-1"><?php esc_html_e('Your Name', 'aqualuxe'); ?></label>
            <input id="aqlx-quote-name" name="name" type="text" class="w-full border rounded px-3 py-2" required />
          </div>
          <div>
            <label for="aqlx-quote-email" class="block text-sm mb-1"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
            <input id="aqlx-quote-email" name="email" type="email" class="w-full border rounded px-3 py-2" required />
          </div>
        </div>
        <div>
          <label for="aqlx-quote-company" class="block text-sm mb-1"><?php esc_html_e('Company', 'aqualuxe'); ?></label>
          <input id="aqlx-quote-company" name="company" type="text" class="w-full border rounded px-3 py-2" />
        </div>
        <div class="grid md:grid-cols-2 gap-3">
          <div>
            <label for="aqlx-quote-qty" class="block text-sm mb-1"><?php esc_html_e('Quantity', 'aqualuxe'); ?></label>
            <input id="aqlx-quote-qty" name="quantity" type="number" min="1" value="1" class="w-full border rounded px-3 py-2" required />
          </div>
        </div>
        <div>
          <label for="aqlx-quote-notes" class="block text-sm mb-1"><?php esc_html_e('Notes', 'aqualuxe'); ?></label>
          <textarea id="aqlx-quote-notes" name="notes" rows="5" class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <div class="pt-2 flex justify-end gap-2">
          <button type="button" id="aqlx-quote-cancel" class="px-4 py-2 rounded border"><?php esc_html_e('Cancel', 'aqualuxe'); ?></button>
          <button type="submit" class="px-4 py-2 rounded bg-sky-600 text-white hover:bg-sky-700"><?php esc_html_e('Send Request', 'aqualuxe'); ?></button>
        </div>
      </form>
    </div>
  </div>
  <span class="sr-only" role="status"><?php esc_html_e('Quote form modal', 'aqualuxe'); ?></span>
  <span class="sr-only" id="aqlx-quote-live" aria-live="polite"></span>
  </div>
