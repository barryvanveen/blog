module.exports = {
  env: {
    browser: true,
    es2021: true
  },
  extends: [
     'standard',
    'plugin:promise/recommended'
  ],
  parserOptions: {
    ecmaVersion: 12,
    sourceType: 'module'
  },
  plugins: [
    'promise'
  ],
  rules: {
  }
}
