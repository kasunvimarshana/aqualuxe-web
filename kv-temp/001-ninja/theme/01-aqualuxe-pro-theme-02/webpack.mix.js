const mix = require('laravel-mix');

mix.setPublicPath('assets/dist');
mix.setResourceRoot('../'); 

mix.js('assets/src/js/app.js', 'js')
    .sass('assets/src/scss/app.scss', 'css')
    .options({
        processCssUrls: false,
        postCss: [
            require('tailwindcss'),
        ],
    })
    .js('assets/src/js/dark-mode.js', 'js')
    .js('assets/src/js/customizer.js', 'js');


if (mix.inProduction()) {
    mix.version();
}

mix.webpackConfig({
    stats: {
        children: true,
    },
});
