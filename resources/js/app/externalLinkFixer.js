import getVariable from '../util/variables'

const initExternalLinkFixer = async () => {
  const baseUrl = getVariable('base_url')

  if (!baseUrl) {
    return
  }

  const externalLinks = document.querySelectorAll('main a[href*="//"]:not([href*="' + baseUrl + '"])')

  if (externalLinks.length === 0) {
    return
  }

  externalLinks.forEach(link => {
    link.setAttribute('target', '_blank')
    link.setAttribute('rel', 'noopener')
  })
}

const externalLinkFixer = () => {
  initExternalLinkFixer()
}

export default externalLinkFixer
