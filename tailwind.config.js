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
        primary: '#181b23',
        gray: '#181b23',
      }
    },
  },
  plugins: [],
}