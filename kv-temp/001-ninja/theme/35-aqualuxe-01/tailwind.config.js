/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        'primary': {
          DEFAULT: 'var(--color-primary)',
          '50': 'var(--color-primary-50)',
          '100': 'var(--color-primary-100)',
          '200': 'var(--color-primary-200)',
          '300': 'var(--color-primary-300)',
          '400': 'var(--color-primary-400)',
          '500': 'var(--color-primary-500)',
          '600': 'var(--color-primary-600)',
          '700': 'var(--color-primary-700)',
          '800': 'var(--color-primary-800)',
          '900': 'var(--color-primary-900)',
        },
        'secondary': {
          DEFAULT: 'var(--color-secondary)',
          '50': 'var(--color-secondary-50)',
          '100': 'var(--color-secondary-100)',
          '200': 'var(--color-secondary-200)',
          '300': 'var(--color-secondary-300)',
          '400': 'var(--color-secondary-400)',
          '500': 'var(--color-secondary-500)',
          '600': 'var(--color-secondary-600)',
          '700': 'var(--color-secondary-700)',
          '800': 'var(--color-secondary-800)',
          '900': 'var(--color-secondary-900)',
        },
      },
      fontFamily: {
        'sans': ['var(--font-family-sans)', 'sans-serif'],
        'serif': ['var(--font-family-serif)', 'serif'],
        'mono': ['var(--font-family-mono)', 'monospace'],
      },
      spacing: {
        'container-padding': 'var(--container-padding)',
      },
      maxWidth: {
        'container': 'var(--container-max-width)',
      },
    },
    screens: {
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      'xl': '1280px',
      '2xl': '1536px',
    },
  },
  plugins: [],
  corePlugins: {
    preflight: false, // Disable Tailwind's base styles to avoid conflicts with WordPress
  },
  important: false, // Set to true if you need to override WordPress styles
}