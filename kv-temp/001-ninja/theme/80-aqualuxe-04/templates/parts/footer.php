<?php
?><footer class="site-footer mt-12" role="contentinfo">
  <div class="container mx-auto px-4 py-8 grid grid-cols-1 md:grid-cols-3 gap-8">
    <div>
      <p class="text-sm">&copy; <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All rights reserved.', 'aqualuxe'); ?></p>
      <p class="text-xs opacity-75"><?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?></p>
    </div>
    <div>
      <nav class="footer-nav" aria-label="<?php esc_attr_e('Footer','aqualuxe'); ?>">
        <?php wp_nav_menu(['theme_location'=>'footer','container'=>false,'menu_class'=>'space-y-2']); ?>
      </nav>
    </div>
    <div>
      <?php if (isset($_GET['nl'])): ?>
        <?php if ($_GET['nl'] === 'ok'): ?>
          <div class="aqlx-notice aqlx-notice--success" role="status"><?php esc_html_e('Thanks! You are on the list.','aqualuxe'); ?></div>
        <?php elseif ($_GET['nl'] === 'err'): ?>
          <div class="aqlx-notice aqlx-notice--error" role="alert"><?php esc_html_e('Please enter a valid email address.','aqualuxe'); ?></div>
        <?php elseif ($_GET['nl'] === 'consent'): ?>
          <div class="aqlx-notice aqlx-notice--error" role="alert"><?php esc_html_e('Please confirm consent to receive emails.','aqualuxe'); ?></div>
        <?php elseif ($_GET['nl'] === 'slow'): ?>
          <div class="aqlx-notice aqlx-notice--error" role="alert"><?php esc_html_e('Please wait a moment before trying again.','aqualuxe'); ?></div>
        <?php endif; ?>
      <?php endif; ?>
      <form id="newsletter" action="" method="post" class="flex flex-col gap-3 max-w-xl ml-auto md:ml-0">
        <?php aqualuxe_nonce_field('newsletter'); ?>
        <input type="hidden" name="aqlx_newsletter" value="1" />
        <label for="email" class="sr-only">Email</label>
        <div class="flex gap-2">
          <input id="email" name="email" type="email" class="border px-3 py-2 flex-1" placeholder="you@example.com" required>
          <button class="btn whitespace-nowrap"><?php esc_html_e('Subscribe','aqualuxe'); ?></button>
        </div>
        <div class="text-sm leading-snug">
          <?php $pp = function_exists('get_privacy_policy_url') ? get_privacy_policy_url() : home_url('/privacy-policy/'); ?>
          <label class="inline-flex items-start gap-2">
            <input type="checkbox" name="consent" value="1" required>
            <span><?php echo wp_kses(sprintf(__('I agree to receive emails and accept the <a href="%s">Privacy Policy</a>.','aqualuxe'), esc_url($pp)), ['a'=>['href'=>[]]]); ?></span>
          </label>
        </div>
        <!-- Honeypot -->
        <div aria-hidden="true" style="position:absolute;left:-9999px;top:auto;width:1px;height:1px;overflow:hidden">
          <label for="aqlx_company">Company</label>
          <input id="aqlx_company" name="aqlx_company" type="text" tabindex="-1" autocomplete="off">
        </div>
      </form>
    </div>
  </div>
</footer>
