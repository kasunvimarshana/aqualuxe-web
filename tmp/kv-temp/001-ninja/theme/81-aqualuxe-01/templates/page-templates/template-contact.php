<?php /* Template Name: Contact */ if (!defined('ABSPATH')) exit; get_header(); ?>
<div class="mx-auto px-4 <?php echo esc_attr(get_theme_mod('aqualuxe_container_width','max-w-7xl')); ?> py-8 grid md:grid-cols-2 gap-8">
  <div>
    <h1 class="text-3xl font-bold mb-4"><?php the_title(); ?></h1>
    <?php the_content(); ?>
    <form class="grid gap-2 mt-4">
      <label class="grid"><span class="text-sm"><?php esc_html_e('Name','aqualuxe'); ?></span><input class="border p-2" required></label>
      <label class="grid"><span class="text-sm"><?php esc_html_e('Email','aqualuxe'); ?></span><input type="email" class="border p-2" required></label>
      <label class="grid"><span class="text-sm"><?php esc_html_e('Message','aqualuxe'); ?></span><textarea class="border p-2" rows="5"></textarea></label>
      <button class="ax-btn" type="submit"><?php esc_html_e('Send','aqualuxe'); ?></button>
    </form>
  </div>
  <div>
    <iframe title="Map" class="w-full h-80" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://www.openstreetmap.org/export/embed.html?bbox=79.84%2C6.9%2C79.9%2C6.96&layer=mapnik"></iframe>
  </div>
</div>
<?php get_footer(); ?>
