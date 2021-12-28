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
      colors: {
        turmeric: '#f4c148',
        terracotta: '#971a20',
        lightBlue: '#8ed0e0',
        lightTeal: '#9de1de',
        darkTeal: '#31a29d',
        lightNavy: '#7e87cd',
        darkNavy: '#2d3574',
        pomelo: '#ba4a77',
      },
    },
  },
  plugins: [],
}
