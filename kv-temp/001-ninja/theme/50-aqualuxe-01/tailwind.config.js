module.exports = {
  content: [
    './**/*.php',
    './assets/src/js/**/*.js',
  ],
  safelist: [
    'rtl',
    'home',
    'blog',
    'archive',
    'single',
    'page',
    'search',
    'error404',
    'woocommerce',
    'woocommerce-page',
    'dark-mode',
  ],
  darkMode: 'class', // or 'media' based on user preference
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#0077B6', // Deep blue for primary brand color
          50: '#E6F3F8',
          100: '#CCE7F1',
          200: '#99CFE3',
          300: '#66B7D5',
          400: '#339FC7',
          500: '#0077B6', // Primary
          600: '#006092',
          700: '#00486D',
          800: '#003049',
          900: '#001824',
        },
        secondary: {
          DEFAULT: '#00B4D8', // Lighter blue for secondary elements
          50: '#E6FAFF',
          100: '#CCF5FF',
          200: '#99EBFF',
          300: '#66E1FF',
          400: '#33D7FF',
          500: '#00B4D8', // Secondary
          600: '#0090AD',
          700: '#006C82',
          800: '#004857',
          900: '#00242B',
        },
        accent: {
          DEFAULT: '#90E0EF', // Light blue for accents
          50: '#F5FCFE',
          100: '#EBF9FD',
          200: '#D7F3FB',
          300: '#C3EDF9',
          400: '#AFE7F7',
          500: '#90E0EF', // Accent
          600: '#5CD3E8',
          700: '#28C6E1',
          800: '#1A9BB1',
          900: '#136F7E',
        },
        luxe: {
          DEFAULT: '#CAB79F', // Gold/beige for luxury elements
          50: '#F9F7F5',
          100: '#F3EFEB',
          200: '#E7DFD7',
          300: '#DBCFC3',
          400: '#D0C3AF',
          500: '#CAB79F', // Luxe
          600: '#B69D7A',
          700: '#A28456',
          800: '#7D663F',
          900: '#594929',
        },
        dark: {
          DEFAULT: '#023E8A', // Deep navy for dark mode
          50: '#E6EDF8',
          100: '#CCDBF1',
          200: '#99B7E3',
          300: '#6693D5',
          400: '#336FC7',
          500: '#023E8A', // Dark
          600: '#01326E',
          700: '#012552',
          800: '#001937',
          900: '#000C1B',
        },
      },
      fontFamily: {
        sans: ['Montserrat', 'sans-serif'],
        serif: ['Playfair Display', 'serif'],
        mono: ['Roboto Mono', 'monospace'],
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      borderRadius: {
        'xl': '1rem',
        '2xl': '2rem',
      },
      boxShadow: {
        'soft': '0 4px 20px rgba(0, 0, 0, 0.05)',
        'medium': '0 8px 30px rgba(0, 0, 0, 0.1)',
        'hard': '0 12px 40px rgba(0, 0, 0, 0.2)',
      },
      typography: {
        DEFAULT: {
          css: {
            color: '#333333',
            a: {
              color: '#0077B6',
              '&:hover': {
                color: '#00486D',
              },
            },
            h1: {
              fontFamily: 'Playfair Display, serif',
            },
            h2: {
              fontFamily: 'Playfair Display, serif',
            },
            h3: {
              fontFamily: 'Playfair Display, serif',
            },
          },
        },
      },
      container: {
        center: true,
        padding: {
          DEFAULT: '1rem',
          sm: '2rem',
          lg: '4rem',
          xl: '5rem',
          '2xl': '6rem',
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/line-clamp'),
  ],
}