import csrfTokenRefresher from './app/csrfTokenRefresher'
import initSyntaxHighlighting from './app/syntaxHighlighting'

const styles = require('../sass/app.pcss');  // eslint-disable-line

document.addEventListener('DOMContentLoaded', () => {
  csrfTokenRefresher()
  initSyntaxHighlighting()
})
