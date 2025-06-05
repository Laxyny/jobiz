/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./assets/**/*.js",
    "./templates/**/*.html.twig",
  ],
  theme: {
    extend: {
      colors: {
        primary: '#1e3a5c', // slate-700
        secondary: '#0d9488', // teal-600
        slate: {
          700: '#1e3a5c',
        },
        teal: {
          600: '#0d9488',
        }
      },
      fontFamily: {
        sans: ['system-ui', 'sans-serif'],
      },
    },
  },
  plugins: [],
}