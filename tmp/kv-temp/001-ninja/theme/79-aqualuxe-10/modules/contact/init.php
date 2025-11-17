<?php
declare(strict_types=1);

namespace Aqualuxe\Modules\Contact;

// Secure AJAX contact form handler and a shortcode renderer.

add_action('wp_ajax_aqlx_contact', __NAMESPACE__ . '\\handle');
add_action('wp_ajax_nopriv_aqlx_contact', __NAMESPACE__ . '\\handle');

function handle(): void {
    check_ajax_referer('aqlx_contact');
    $name = sanitize_text_field($_POST['name'] ?? '');
    $email = sanitize_email($_POST['email'] ?? '');
    $message = trim(wp_kses_post($_POST['message'] ?? ''));
    if (!$name || !$email || !$message) { wp_send_json_error(['message'=>'missing fields'], 400); }
    // Very basic throttling
    if (isset($_COOKIE['aqlx_c_throttle'])) { wp_send_json_error(['message'=>'rate limited'], 429); }
    setcookie('aqlx_c_throttle', '1', time()+30, COOKIEPATH ?: '/');

    // Send email to admin
    $to = get_option('admin_email');
    $subject = sprintf(__('New contact from %s', 'aqualuxe'), $name);
    $body = sprintf("Name: %s\nEmail: %s\n\nMessage:\n%s", $name, $email, $message);
    $headers = ['Reply-To: '. $name .' <'. $email .'>'];
    $sent = wp_mail($to, $subject, $body, $headers);
    if ($sent) { wp_send_json_success(['ok'=>true]); }
    wp_send_json_error(['message'=>'mail failed'], 500);
}

add_shortcode('aqlx_contact_form', function(){
    $nonce = wp_create_nonce('aqlx_contact');
    ob_start();
    ?>
    <form class="aqlx-contact space-y-3" method="post" action="#">
      <div>
        <label class="block text-sm" for="aqlx_name"><?php esc_html_e('Name', 'aqualuxe'); ?></label>
        <input id="aqlx_name" name="name" required class="border rounded px-3 py-2 w-full" />
      </div>
      <div>
        <label class="block text-sm" for="aqlx_email"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
        <input id="aqlx_email" name="email" type="email" required class="border rounded px-3 py-2 w-full" />
      </div>
      <div>
        <label class="block text-sm" for="aqlx_message"><?php esc_html_e('Message', 'aqualuxe'); ?></label>
        <textarea id="aqlx_message" name="message" rows="5" required class="border rounded px-3 py-2 w-full"></textarea>
      </div>
      <button type="submit" class="bg-slate-900 text-white dark:bg-slate-100 dark:text-slate-900 px-4 py-2 rounded"><?php esc_html_e('Send', 'aqualuxe'); ?></button>
    </form>
    <script>
    (function(){
      var f=document.currentScript.previousElementSibling; if(!f||!f.classList.contains('aqlx-contact')) return;
      f.addEventListener('submit', function(e){ e.preventDefault();
        var fd=new FormData(f); fd.append('action','aqlx_contact'); fd.append('_wpnonce','<?php echo esc_js($nonce); ?>');
        fetch((window.ajaxurl||'/wp-admin/admin-ajax.php'), {method:'POST', credentials:'same-origin', body: fd}).then(r=>r.json()).then(function(res){
          if(res && res.success){ f.reset(); alert('<?php echo esc_js(__('Thanks! We will get back to you shortly.','aqualuxe')); ?>'); }
          else{ alert('<?php echo esc_js(__('Oops, please try again later.','aqualuxe')); ?>'); }
        });
      });
    })();
    </script>
    <?php
    return (string) ob_get_clean();
});
