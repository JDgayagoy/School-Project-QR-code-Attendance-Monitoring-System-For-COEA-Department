/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./src/**/*.{html,js,php}", // for files in the src folder
    "./*.{html,php}"            // for root directory files
  ],  
  theme: {
    extend: {},
  },
  plugins: [],
}