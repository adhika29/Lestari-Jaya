module.exports = {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'brown': {
          100: '#E6D7D0', // Warna coklat muda
          500: '#6D4534',
          600: '#5A3A2C',
        },
        'green': {
          300: '#A7D7A9',
          400: '#8BC98E',
        },
      },
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
  // Pastikan scroll-smooth utility tersedia
  corePlugins: {
    scrollBehavior: true
  }
}
