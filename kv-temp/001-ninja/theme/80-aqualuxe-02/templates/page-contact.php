<?php /* Template Name: Contact */ get_header(); ?>
<div class="container mx-auto px-4 py-10 grid gap-8 md:grid-cols-2">
  <section>
    <h1 class="text-3xl font-semibold mb-4"><?php esc_html_e('Contact Us','aqualuxe'); ?></h1>
    <form method="post" class="grid gap-3 max-w-lg">
      <?php aqualuxe_nonce_field('aqlx_contact'); ?>
      <input class="border px-3 py-2" name="name" placeholder="<?php echo esc_attr__('Your Name','aqualuxe'); ?>" required />
      <input class="border px-3 py-2" name="email" type="email" placeholder="<?php echo esc_attr__('Email','aqualuxe'); ?>" required />
      <textarea class="border px-3 py-2" name="message" placeholder="<?php echo esc_attr__('How can we help?','aqualuxe'); ?>" required></textarea>
      <button class="btn"><?php esc_html_e('Send','aqualuxe'); ?></button>
    </form>
    <?php if (!empty($_POST['_aqualuxe_nonce']) && wp_verify_nonce($_POST['_aqualuxe_nonce'], 'aqlx_contact')){ echo '<p class="mt-3">' . esc_html__('Thanks! We will get back to you soon.','aqualuxe') . '</p>'; } ?>
  </section>
  <aside>
    <div class="aspect-video w-full border rounded" aria-label="Map placeholder"></div>
    <p class="mt-2 text-sm opacity-80"><?php esc_html_e('We use privacy-friendly map embeds.','aqualuxe'); ?></p>
  </aside>
</div>
<?php get_footer(); ?>