<?php defined('ABSPATH') || exit; ?>
<div id="aqlx-qv-overlay" class="fixed inset-0 bg-black/60 hidden z-50"></div>
<div id="aqlx-qv" class="fixed inset-0 hidden z-50 items-center justify-center p-4">
  <div class="bg-white dark:bg-slate-900 rounded-lg max-w-3xl w-full shadow-xl relative">
    <button id="aqlx-qv-close" class="absolute top-3 right-3 p-2" aria-label="Close">✕</button>
    <div id="aqlx-qv-content" class="p-6">
      <p class="opacity-75"><?php esc_html_e('Loading…', 'aqualuxe'); ?></p>
    </div>
  </div>
  <style>.aqlx-modal-open{overflow:hidden}</style>
</div>
