module.exports = {
  // mode: 'jit',
  purge: {
      content: ['./resources/views/**/*.php'],
      options: {
          safelist: {
              standard: [/cursor-not-allowed/],
              deep: [/hljs-?/],
          },
          keyframes: true,
      }
  },
  darkMode: false, // or 'media' or 'class'
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
  variants: {
    extend: {

    },
  },
  plugins: [],
}
