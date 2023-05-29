/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.ts",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        gray: {
          'gray-100': '#f1f1f2',
          'gray-200': '#d7d8dc',
          'gray-300': '#bdbfc4',
          'gray-400': '#a4a6ad',
          'gray-500': '#181b23',
          'gray-600': '#16181e',
          'gray-700': '#13151a',
          'gray-800': '#101216',
          'gray-900': '#0d0e12',
        },
      }
    },
  },
  plugins: [],
}