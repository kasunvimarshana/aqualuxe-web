const mix = require('laravel-mix');
const path = require('path');
const fs = require('fs');

// Set public path
mix.setPublicPath('assets');

// Source directories
const srcPath = 'src';
const srcJS = `${srcPath}/js`;
const srcCSS = `${srcPath}/css`;
const srcImg = `${srcPath}/img`;
const srcFonts = `${srcPath}/fonts`;

// Destination directories
const destPath = 'assets';
const destJS = `${destPath}/js`;
const destCSS = `${destPath}/css`;
const destImg = `${destPath}/img`;
const destFonts = `${destPath}/fonts`;

// BrowserSync configuration
mix.browserSync({
    proxy: 'localhost',
    open: false,
    files: [
        '**/*.php',
        `${destPath}/**/*`,
    ],
    ignore: [
        'node_modules',
        'vendor',
    ],
});

// Process JavaScript files
mix.js(`${srcJS}/main.js`, destJS)
   .js(`${srcJS}/customizer.js`, destJS)
   .js(`${srcJS}/editor.js`, destJS);

// Process WooCommerce specific JS if it exists
if (fs.existsSync(`${srcJS}/woocommerce.js`)) {
    mix.js(`${srcJS}/woocommerce.js`, destJS);
}

// Process CSS with PostCSS and Tailwind
mix.postCss(`${srcCSS}/main.css`, destCSS, [
    require('postcss-import'),
    require('tailwindcss'),
    require('postcss-nested'),
    require('autoprefixer'),
])
.postCss(`${srcCSS}/editor.css`, destCSS, [
    require('postcss-import'),
    require('tailwindcss'),
    require('postcss-nested'),
    require('autoprefixer'),
]);

// Process WooCommerce specific CSS if it exists
if (fs.existsSync(`${srcCSS}/woocommerce.css`)) {
    mix.postCss(`${srcCSS}/woocommerce.css`, destCSS, [
        require('postcss-import'),
        require('tailwindcss'),
        require('postcss-nested'),
        require('autoprefixer'),
    ]);
}

// Copy images and fonts
mix.copyDirectory(srcImg, destImg);
mix.copyDirectory(srcFonts, destFonts);

// Version files in production
if (mix.inProduction()) {
    mix.version();
    
    // Add additional optimization for production
    mix.options({
        terser: {
            extractComments: false,
            terserOptions: {
                compress: {
                    drop_console: true,
                }
            }
        },
        postCss: [
            require('cssnano')({
                preset: ['default', {
                    discardComments: {
                        removeAll: true,
                    },
                }],
            }),
        ],
    });
}

// Disable OS notifications
mix.disableNotifications();