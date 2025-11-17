/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './*.php',
    './inc/**/*.php',
    './templates/**/*.php',
    './woocommerce/**/*.php',
    './assets/src/js/**/*.js',
  ],
  darkMode: 'class', // Enable dark mode with class-based switching
  theme: {
    extend: {
      colors: {
        primary: {
          DEFAULT: '#0891b2', // Cyan-600
          50: '#ecfeff',
          100: '#cffafe',
          200: '#a5f3fc',
          300: '#67e8f9',
          400: '#22d3ee',
          500: '#06b6d4',
          600: '#0891b2',
          700: '#0e7490',
          800: '#155e75',
          900: '#164e63',
          950: '#083344',
        },
        secondary: {
          DEFAULT: '#6366f1', // Indigo-500
          50: '#eef2ff',
          100: '#e0e7ff',
          200: '#c7d2fe',
          300: '#a5b4fc',
          400: '#818cf8',
          500: '#6366f1',
          600: '#4f46e5',
          700: '#4338ca',
          800: '#3730a3',
          900: '#312e81',
          950: '#1e1b4b',
        },
        accent: {
          DEFAULT: '#8b5cf6', // Violet-500
          50: '#f5f3ff',
          100: '#ede9fe',
          200: '#ddd6fe',
          300: '#c4b5fd',
          400: '#a78bfa',
          500: '#8b5cf6',
          600: '#7c3aed',
          700: '#6d28d9',
          800: '#5b21b6',
          900: '#4c1d95',
          950: '#2e1065',
        },
        dark: {
          DEFAULT: '#0f172a', // Slate-900
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
          950: '#020617',
        },
        light: {
          DEFAULT: '#f8fafc', // Slate-50
          50: '#f8fafc',
          100: '#f1f5f9',
          200: '#e2e8f0',
          300: '#cbd5e1',
          400: '#94a3b8',
          500: '#64748b',
          600: '#475569',
          700: '#334155',
          800: '#1e293b',
          900: '#0f172a',
          950: '#020617',
        },
      },
      fontFamily: {
        heading: ['Playfair Display', 'serif'],
        body: ['Montserrat', 'sans-serif'],
      },
      spacing: {
        '128': '32rem',
        '144': '36rem',
      },
      borderRadius: {
        '4xl': '2rem',
      },
      typography: (theme) => ({
        DEFAULT: {
          css: {
            color: theme('colors.dark.700'),
            a: {
              color: theme('colors.primary.600'),
              '&:hover': {
                color: theme('colors.primary.800'),
              },
            },
            h1: {
              color: theme('colors.dark.900'),
              fontFamily: theme('fontFamily.heading').join(', '),
              fontWeight: '700',
            },
            h2: {
              color: theme('colors.dark.900'),
              fontFamily: theme('fontFamily.heading').join(', '),
              fontWeight: '700',
            },
            h3: {
              color: theme('colors.dark.900'),
              fontFamily: theme('fontFamily.heading').join(', '),
              fontWeight: '600',
            },
            h4: {
              color: theme('colors.dark.900'),
              fontFamily: theme('fontFamily.heading').join(', '),
              fontWeight: '600',
            },
            h5: {
              color: theme('colors.dark.900'),
              fontFamily: theme('fontFamily.heading').join(', '),
              fontWeight: '500',
            },
            h6: {
              color: theme('colors.dark.900'),
              fontFamily: theme('fontFamily.heading').join(', '),
              fontWeight: '500',
            },
          },
        },
        dark: {
          css: {
            color: theme('colors.light.300'),
            a: {
              color: theme('colors.primary.400'),
              '&:hover': {
                color: theme('colors.primary.300'),
              },
            },
            h1: {
              color: theme('colors.light.100'),
            },
            h2: {
              color: theme('colors.light.100'),
            },
            h3: {
              color: theme('colors.light.100'),
            },
            h4: {
              color: theme('colors.light.100'),
            },
            h5: {
              color: theme('colors.light.100'),
            },
            h6: {
              color: theme('colors.light.100'),
            },
          },
        },
      }),
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
      screens: {
        'sm': '640px',
        'md': '768px',
        'lg': '1024px',
        'xl': '1280px',
        '2xl': '1536px',
      },
      boxShadow: {
        'soft': '0 4px 20px rgba(0, 0, 0, 0.05)',
        'medium': '0 4px 20px rgba(0, 0, 0, 0.1)',
        'hard': '0 4px 20px rgba(0, 0, 0, 0.15)',
      },
      transitionProperty: {
        'height': 'height',
        'spacing': 'margin, padding',
      },
      zIndex: {
        '-10': '-10',
        '60': '60',
        '70': '70',
        '80': '80',
        '90': '90',
        '100': '100',
      },
    },
  },
  plugins: [
    require('@tailwindcss/typography'),
    require('@tailwindcss/forms'),
  ],
};