# Initialize npm
```
npm init -y
```

# Install Tailwind CSS and its dependencies
```
npm install -D tailwindcss@3.4.10 postcss autoprefixer
npx tailwindcss init -p
```

# Configure Tailwind (tailwind.config.js)
```
module.exports = {
  content: [
    "./**/*.php",
    "./assets/js/**/*.js",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#006994',
        secondary: '#00a8cc',
        accent: '#ffd166',
        light: '#f8f9fa',
        dark: '#343a40',
        success: '#28a745',
        warning: '#ffc107',
      },
      fontFamily: {
        sans: ['Helvetica Neue', 'Helvetica', 'Arial', 'sans-serif'],
      },
      spacing: {
        '18': '4.5rem',
        '88': '22rem',
      },
    },
  },
  plugins: [],
}
```

# Create a Tailwind CSS file (assets/css/tailwind.css)
```
@tailwind base;
@tailwind components;
@tailwind utilities;
```

# Update package.json scripts
```
"scripts": {
  "build:css": "tailwindcss -i ./assets/css/tailwind.css -o ./assets/css/style.css",
  "watch:css": "tailwindcss -i ./assets/css/tailwind.css -o ./assets/css/style.css --watch"
}
```

# Run the build script
```
npm run build:css
```

# Update Theme to Use Tailwind CSS (Replace the existing aqualuxe_enqueue_styles function in functions.php)

```
function aqualuxe_enqueue_styles() {
    // Parent theme stylesheet
    wp_enqueue_style( 'storefront-style', get_template_directory_uri() . '/style.css' );
    
    // Child theme stylesheet (compiled Tailwind CSS)
    wp_enqueue_style( 'aqualuxe-style',
        get_stylesheet_directory_uri() . '/assets/css/style.css',
        array( 'storefront-style' ),
        AQUALUXE_VERSION
    );
    
    // Enqueue custom scripts
    wp_enqueue_script( 'aqualuxe-navigation', get_stylesheet_directory_uri() . '/assets/js/navigation.js', array(), AQUALUXE_VERSION, true );
    wp_enqueue_script( 'aqualuxe-site', get_stylesheet_directory_uri() . '/assets/js/site.js', array( 'jquery' ), AQUALUXE_VERSION, true );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_enqueue_styles' );
```

# Create a custom CSS file for WooCommerce (assets/css/woocommerce.css)

```
/* WooCommerce specific styles */
@tailwind components;
@tailwind utilities;

@layer components {
  .woocommerce-loop-product__title {
    @apply text-lg font-semibold text-dark mb-2;
  }
  
  .price {
    @apply text-primary font-bold text-xl;
  }
  
  .button {
    @apply bg-accent text-dark px-4 py-2 rounded-full font-semibold uppercase tracking-wide transition hover:bg-yellow-500 hover:shadow-lg transform hover:-translate-y-1;
  }
  
  .woocommerce-error, .woocommerce-info, .woocommerce-message {
    @apply p-4 mb-6 rounded-lg;
  }
  
  .woocommerce-error {
    @apply bg-red-100 border border-red-400 text-red-700;
  }
  
  .woocommerce-info {
    @apply bg-blue-100 border border-blue-400 text-blue-700;
  }
  
  .woocommerce-message {
    @apply bg-green-100 border border-green-400 text-green-700;
  }
}
```

# Update functions.php to include WooCommerce styles
```
function aqualuxe_woocommerce_scripts() {
    wp_enqueue_style( 'aqualuxe-woocommerce-style', get_stylesheet_directory_uri() . '/assets/css/woocommerce.css', array(), AQUALUXE_VERSION );
}
add_action( 'wp_enqueue_scripts', 'aqualuxe_woocommerce_scripts' );
```