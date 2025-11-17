<?php
/*
Template Name: Contact Page
*/

defined('ABSPATH') || exit;

get_header();
?>
<main id="primary" class="site-main container mx-auto px-4 py-10">
  <header class="mb-8">
    <h1 class="text-3xl font-semibold"><?php the_title(); ?></h1>
    <?php if (function_exists('yoast_breadcrumb')) yoast_breadcrumb('<p class="text-sm text-gray-500" id="breadcrumbs">','</p>'); ?>
  </header>

  <div class="grid md:grid-cols-2 gap-8">
    <section aria-labelledby="contact-form-title">
      <h2 id="contact-form-title" class="text-xl font-semibold mb-4"><?php esc_html_e('Send us a message', 'aqualuxe'); ?></h2>
      <?php if (!empty($_GET['sent']) && $_GET['sent'] === '1') : ?>
        <div role="status" class="p-3 rounded bg-green-50 text-green-800 mb-4"><?php esc_html_e('Thanks! Your message has been sent.', 'aqualuxe'); ?></div>
      <?php elseif (!empty($_GET['error'])) : ?>
        <div role="alert" class="p-3 rounded bg-red-50 text-red-800 mb-4"><?php echo esc_html($_GET['error']); ?></div>
      <?php endif; ?>
      <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" class="space-y-4" novalidate>
        <?php wp_nonce_field('aqlx_contact'); ?>
        <input type="hidden" name="action" value="aqlx_contact_submit" />
        <div>
          <label for="aqlx_name" class="block text-sm font-medium mb-1"><?php esc_html_e('Name', 'aqualuxe'); ?></label>
          <input id="aqlx_name" name="name" type="text" required class="w-full border rounded px-3 py-2" />
        </div>
        <div>
          <label for="aqlx_email" class="block text-sm font-medium mb-1"><?php esc_html_e('Email', 'aqualuxe'); ?></label>
          <input id="aqlx_email" name="email" type="email" required class="w-full border rounded px-3 py-2" />
        </div>
        <div>
          <label for="aqlx_msg" class="block text-sm font-medium mb-1"><?php esc_html_e('Message', 'aqualuxe'); ?></label>
          <textarea id="aqlx_msg" name="message" rows="6" required class="w-full border rounded px-3 py-2"></textarea>
        </div>
        <button type="submit" class="inline-flex items-center px-4 py-2 rounded bg-sky-600 text-white hover:bg-sky-700">
          <?php esc_html_e('Send', 'aqualuxe'); ?>
        </button>
      </form>
    </section>

    <aside aria-labelledby="contact-details-title">
      <h2 id="contact-details-title" class="text-xl font-semibold mb-4"><?php esc_html_e('Find us', 'aqualuxe'); ?></h2>
      <div class="prose max-w-none mb-4">
        <?php
        $map = get_theme_mod('aqualuxe_map_embed', '');
        if ($map) {
            echo $map; // Safe: sanitized in Customizer setting via wp_kses subset
        } else {
            echo '<p>' . esc_html__('Map coming soon.', 'aqualuxe') . '</p>';
        }
        ?>
      </div>
      <p class="text-sm text-gray-600"><?php esc_html_e('Prefer email?', 'aqualuxe'); ?>
        <a class="text-sky-700 underline" href="mailto:<?php echo esc_attr(get_theme_mod('aqualuxe_contact_email', get_option('admin_email'))); ?>">
          <?php echo esc_html(get_theme_mod('aqualuxe_contact_email', get_option('admin_email'))); ?>
        </a>
      </p>
    </aside>
  </div>
</main>
<?php
get_footer();
