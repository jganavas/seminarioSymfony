/** @type {import('tailwindcss').Config} */
module.exports = {
  darkMode: 'selector',
  content: [
    "./assets/**/*.js",
    "./templates/**/*.{html,twig}",
  ],
  safelist: [
    'dark:bg-gray-900',
    'dark:text-gray-50',
    'dark'
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
