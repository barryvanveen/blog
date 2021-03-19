import csrfTokenRefresher from './app/csrfTokenRefresher'
import initSyntaxHighlighting from './app/syntaxHighlighting'
import commentSubmitter from './app/commentSubmitter'
import commentCreatedNoticeDisplayer from './app/commentCreatedNoticeDisplayer'

const styles = require('../sass/app.pcss');  // eslint-disable-line

document.addEventListener('DOMContentLoaded', () => {
  csrfTokenRefresher()
  commentSubmitter()
  initSyntaxHighlighting()
  commentCreatedNoticeDisplayer()
})
