import csrfTokenRefresher from './app/csrfTokenRefresher'
import commentSubmitter from './app/commentSubmitter'
import commentCreatedNoticeDisplayer from './app/commentCreatedNoticeDisplayer'
import mobileMenu from './app/mobileMenu'
import externalLinkFixer from './app/externalLinkFixer'

const styles = require('../sass/app.pcss');  // eslint-disable-line

document.addEventListener('DOMContentLoaded', () => {
  csrfTokenRefresher()
  commentSubmitter()
  commentCreatedNoticeDisplayer()
  mobileMenu()
  externalLinkFixer()
})
