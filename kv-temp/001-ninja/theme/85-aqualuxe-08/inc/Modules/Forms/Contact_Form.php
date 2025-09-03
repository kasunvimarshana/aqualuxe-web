<?php
namespace Aqualuxe\Modules\Forms;

class Contact_Form
{
    public static function render(): string
    {
        $action = esc_url(admin_url('admin-post.php'));
        $nonce = wp_nonce_field('aqualuxe_contact', '_aqlx', true, false);
        return <<<HTML
<form method="post" action="{$action}" novalidate>
  <input type="hidden" name="action" value="aqualuxe_contact_submit" />
  {$nonce}
  <label>
    <span>Name</span>
    <input name="name" required aria-required="true" />
  </label>
  <label>
    <span>Email</span>
    <input type="email" name="email" required aria-required="true" />
  </label>
  <label>
    <span>Message</span>
    <textarea name="message" required aria-required="true"></textarea>
  </label>
  <button type="submit">Send</button>
</form>
HTML;
    }
}
