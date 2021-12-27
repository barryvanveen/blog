module.exports = {
  content: [
          './resources/views/**/*.php',
          './resources/js/**/*.js',
          './vendor/scrivo/highlight.php/styles/a11y-dark.css',
  ],
  theme: {
    screens: {
      'sm': '640px',
      'md': '768px',
      'lg': '1024px',
      //'xl': '1280px',
      //'2xl': '1536px',
    },
    extend: {
      container: {
        padding: '1rem',
        center: true,
      },
    },
  },
  plugins: [],
}
