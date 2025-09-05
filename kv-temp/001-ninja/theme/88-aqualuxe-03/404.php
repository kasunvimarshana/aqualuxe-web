<?php get_header(); ?>
<div class="container mx-auto max-w-screen-xl px-4 py-20 text-center">
    <h1 class="text-5xl font-bold mb-4"><?php esc_html_e( 'Page not found', 'aqualuxe' ); ?></h1>
    <p class="opacity-80 mb-6"><?php esc_html_e( 'The page you are looking for was moved or doesn\'t exist.', 'aqualuxe' ); ?></p>
    <a class="inline-flex items-center px-4 py-2 bg-sky-600 text-white rounded" href="<?php echo esc_url( home_url('/') ); ?>"><?php esc_html_e( 'Go Home', 'aqualuxe' ); ?></a>
    </div>
<?php get_footer(); ?>
