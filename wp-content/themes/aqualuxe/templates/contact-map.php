<?php
/*
Template Name: Contact (Map + Form)
*/
get_header();
?>
<main id="primary" class="container mx-auto px-4 py-8 grid lg:grid-cols-2 gap-8">
  <section>
    <h1 class="text-3xl font-semibold mb-4"><?php the_title(); ?></h1>
    <div class="mb-4 prose dark:prose-invert"><?php the_content(); ?></div>
    <?php echo do_shortcode('[aqlx_contact_form]'); ?>
  </section>
  <aside>
    <div class="aspect-video rounded-lg overflow-hidden">
      <iframe title="Map" src="https://www.google.com/maps?q=AquaLuxe&output=embed" width="600" height="450" style="border:0; width:100%; height:100%;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <ul class="mt-4 space-y-2 text-sm">
      <li><strong><?php esc_html_e('Phone:','aqualuxe'); ?></strong> +1 (555) 123-4567</li>
      <li><strong><?php esc_html_e('Email:','aqualuxe'); ?></strong> hello@example.com</li>
      <li><strong><?php esc_html_e('Address:','aqualuxe'); ?></strong> 123 Aqua Way, Ocean City</li>
    </ul>
  </aside>
</main>
<?php get_footer(); ?>
