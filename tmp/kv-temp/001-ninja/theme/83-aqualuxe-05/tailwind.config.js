/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './assets/src/**/*.{js,ts,scss,css}',
    './templates/**/*.php',
    './modules/**/*.php',
    './inc/**/*.php',
    './*.php'
  ],
  
  theme: {
    extend: {
      colors: {
        // AquaLuxe Brand Colors
        primary: {
          50: '#eff6ff',
          100: '#dbeafe',
          200: '#bfdbfe',
          300: '#93c5fd',
          400: '#60a5fa',
          500: '#3b82f6',
          600: '#2563eb',
          700: '#1d4ed8',
          800: '#1e40af',
          900: '#1e3a8a',
          950: '#172554'
        },
        
        secondary: {
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
          950: '#020617'
        },
        
        accent: {
          50: '#fffbeb',
          100: '#fef3c7',
          200: '#fde68a',
          300: '#fcd34d',
          400: '#fbbf24',
          500: '#f59e0b',
          600: '#d97706',
          700: '#b45309',
          800: '#92400e',
          900: '#78350f',
          950: '#451a03'
        },
        
        // Aquatic Theme Colors
        aqua: {
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
          950: '#083344'
        },
        
        ocean: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          200: '#bae6fd',
          300: '#7dd3fc',
          400: '#38bdf8',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1',
          800: '#075985',
          900: '#0c4a6e',
          950: '#082f49'
        },
        
        coral: {
          50: '#fef2f2',
          100: '#fee2e2',
          200: '#fecaca',
          300: '#fca5a5',
          400: '#f87171',
          500: '#ef4444',
          600: '#dc2626',
          700: '#b91c1c',
          800: '#991b1b',
          900: '#7f1d1d',
          950: '#450a0a'
        }
      },
      
      fontFamily: {
        sans: [
          'Inter',
          '-apple-system',
          'BlinkMacSystemFont',
          '"Segoe UI"',
          'Roboto',
          '"Helvetica Neue"',
          'Arial',
          'sans-serif'
        ],
        serif: [
          'Playfair Display',
          'Georgia',
          '"Times New Roman"',
          'Times',
          'serif'
        ],
        mono: [
          '"SF Mono"',
          'Monaco',
          'Inconsolata',
          '"Roboto Mono"',
          'Consolas',
          '"Courier New"',
          'monospace'
        ]
      },
      
      spacing: {
        '18': '4.5rem',
        '22': '5.5rem',
        '26': '6.5rem',
        '30': '7.5rem'
      },
      
      fontSize: {
        'xs': ['0.75rem', { lineHeight: '1rem' }],
        'sm': ['0.875rem', { lineHeight: '1.25rem' }],
        'base': ['1rem', { lineHeight: '1.5rem' }],
        'lg': ['1.125rem', { lineHeight: '1.75rem' }],
        'xl': ['1.25rem', { lineHeight: '1.75rem' }],
        '2xl': ['1.5rem', { lineHeight: '2rem' }],
        '3xl': ['1.875rem', { lineHeight: '2.25rem' }],
        '4xl': ['2.25rem', { lineHeight: '2.5rem' }],
        '5xl': ['3rem', { lineHeight: '1' }],
        '6xl': ['3.75rem', { lineHeight: '1' }],
        '7xl': ['4.5rem', { lineHeight: '1' }],
        '8xl': ['6rem', { lineHeight: '1' }],
        '9xl': ['8rem', { lineHeight: '1' }]
      },
      
      screens: {
        'xs': '475px',
        '3xl': '1600px',
        '4xl': '1920px'
      },
      
      boxShadow: {
        'aqua': '0 4px 14px 0 rgba(6, 182, 212, 0.15)',
        'ocean': '0 4px 14px 0 rgba(14, 165, 233, 0.15)',
        'coral': '0 4px 14px 0 rgba(239, 68, 68, 0.15)',
        'luxe': '0 10px 40px rgba(0, 0, 0, 0.1), 0 4px 25px rgba(0, 0, 0, 0.07)',
        'float': '0 25px 50px -12px rgba(0, 0, 0, 0.25)'
      },
      
      animation: {
        'float': 'float 6s ease-in-out infinite',
        'wave': 'wave 2s ease-in-out infinite',
        'bubble': 'bubble 3s ease-in-out infinite',
        'swim': 'swim 8s ease-in-out infinite',
        'ripple': 'ripple 2s ease-out infinite',
        'fade-in': 'fadeIn 0.5s ease-in-out',
        'slide-up': 'slideUp 0.5s ease-out',
        'scale-in': 'scaleIn 0.3s ease-out'
      },
      
      keyframes: {
        float: {
          '0%, 100%': { transform: 'translateY(0px)' },
          '50%': { transform: 'translateY(-20px)' }
        },
        wave: {
          '0%': { transform: 'rotate(0deg)' },
          '10%': { transform: 'rotate(14deg)' },
          '20%': { transform: 'rotate(-8deg)' },
          '30%': { transform: 'rotate(14deg)' },
          '40%': { transform: 'rotate(-4deg)' },
          '50%': { transform: 'rotate(10deg)' },
          '60%': { transform: 'rotate(0deg)' },
          '100%': { transform: 'rotate(0deg)' }
        },
        bubble: {
          '0%': { transform: 'translateY(0) scale(1)', opacity: '0' },
          '50%': { opacity: '1' },
          '100%': { transform: 'translateY(-100px) scale(0.3)', opacity: '0' }
        },
        swim: {
          '0%, 100%': { transform: 'translateX(0) rotateY(0)' },
          '25%': { transform: 'translateX(30px) rotateY(0)' },
          '50%': { transform: 'translateX(30px) rotateY(180deg)' },
          '75%': { transform: 'translateX(0) rotateY(180deg)' }
        },
        ripple: {
          '0%': { transform: 'scale(0)', opacity: '1' },
          '100%': { transform: 'scale(4)', opacity: '0' }
        },
        fadeIn: {
          '0%': { opacity: '0' },
          '100%': { opacity: '1' }
        },
        slideUp: {
          '0%': { transform: 'translateY(20px)', opacity: '0' },
          '100%': { transform: 'translateY(0)', opacity: '1' }
        },
        scaleIn: {
          '0%': { transform: 'scale(0.95)', opacity: '0' },
          '100%': { transform: 'scale(1)', opacity: '1' }
        }
      },
      
      backgroundImage: {
        'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
        'gradient-ocean': 'linear-gradient(135deg, #0ea5e9 0%, #0891b2 50%, #0e7490 100%)',
        'gradient-aqua': 'linear-gradient(135deg, #22d3ee 0%, #06b6d4 50%, #0891b2 100%)',
        'gradient-coral': 'linear-gradient(135deg, #f87171 0%, #ef4444 50%, #dc2626 100%)'
      },
      
      backdropBlur: {
        'xs': '2px'
      }
    }
  },
  
  plugins: [
    require('@tailwindcss/forms'),
    require('@tailwindcss/typography'),
    require('@tailwindcss/aspect-ratio'),
    require('@tailwindcss/container-queries'),
    
    // Custom plugins for AquaLuxe
    function({ addUtilities, addComponents, theme }) {
      // Custom utilities
      addUtilities({
        '.text-gradient': {
          'background-clip': 'text',
          '-webkit-background-clip': 'text',
          '-webkit-text-fill-color': 'transparent',
        },
        '.glass': {
          'backdrop-filter': 'blur(10px)',
          'background': 'rgba(255, 255, 255, 0.1)',
          'border': '1px solid rgba(255, 255, 255, 0.2)',
        },
        '.overflow-hidden-mobile': {
          '@screen max-sm': {
            'overflow': 'hidden'
          }
        }
      });
      
      // Custom components
      addComponents({
        '.btn-aqua': {
          '@apply bg-gradient-aqua text-white font-semibold py-3 px-6 rounded-lg shadow-aqua hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200': {}
        },
        '.btn-ocean': {
          '@apply bg-gradient-ocean text-white font-semibold py-3 px-6 rounded-lg shadow-ocean hover:shadow-lg transform hover:-translate-y-0.5 transition-all duration-200': {}
        },
        '.card-luxe': {
          '@apply bg-white rounded-xl shadow-luxe hover:shadow-float p-6 transition-all duration-300': {}
        },
        '.text-aqua-gradient': {
          '@apply bg-gradient-aqua text-gradient': {}
        },
        '.text-ocean-gradient': {
          '@apply bg-gradient-ocean text-gradient': {}
        }
      });
    }
  ],
  
  corePlugins: {
    container: false // We'll create our own container
  }
};
