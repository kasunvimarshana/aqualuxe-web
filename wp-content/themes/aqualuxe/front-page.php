<?php get_header(); ?>

<section class="relative overflow-hidden">
  <div class="container mx-auto px-4 py-16 text-center">
    <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight">
      <?php esc_html_e('Bringing elegance to aquatic life – globally.', 'aqualuxe'); ?>
    </h1>
    <p class="mt-4 text-lg opacity-90 max-w-3xl mx-auto">
      <?php esc_html_e('Premium ornamental fish, plants, and bespoke aquariums with sustainable practices.', 'aqualuxe'); ?>
    </p>
    <div class="mt-8 flex justify-center gap-4">
      <a href="<?php echo esc_url( home_url('/shop') ); ?>" class="px-5 py-3 rounded bg-sky-600 text-white hover:bg-sky-700">
        <?php esc_html_e('Shop Now', 'aqualuxe'); ?>
      </a>
      <a href="<?php echo esc_url( home_url('/services') ); ?>" class="px-5 py-3 rounded border border-slate-300 dark:border-slate-700 hover:bg-slate-50/50 dark:hover:bg-slate-800/50">
        <?php esc_html_e('Book a Service', 'aqualuxe'); ?>
      </a>
    </div>
  </div>
</section>

<section class="container mx-auto px-4 py-12">
  <h2 class="text-2xl font-semibold mb-6"><?php esc_html_e('Featured', 'aqualuxe'); ?></h2>
  <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
    <?php
    $q = new WP_Query(['posts_per_page' => 6]);
    if ($q->have_posts()) :
      while ($q->have_posts()) : $q->the_post(); ?>
        <article <?php post_class('rounded-lg border border-slate-200/70 dark:border-slate-800/80 p-5'); ?>>
          <a href="<?php the_permalink(); ?>" class="block"><h3 class="text-lg font-semibold mb-2"><?php the_title(); ?></h3></a>
          <div class="prose dark:prose-invert max-w-none"><?php the_excerpt(); ?></div>
        </article>
    <?php endwhile; wp_reset_postdata(); endif; ?>
  </div>
</section>

<?php get_footer(); ?>
